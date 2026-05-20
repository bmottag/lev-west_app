<?php
namespace App\Modules\Inspection\Controllers;

use App\Controllers\BaseController;
use App\Modules\Inspection\Models\InspectionModel;
use App\Models\GeneralModel;

class Inspection extends BaseController
{
    protected $inspectionModel;
    protected $generalModel;

    public function __construct()
    {
        $this->inspectionModel   = new InspectionModel();
        $this->generalModel   = new GeneralModel();
    }

    /**
     * Form Add Heavy Inspection
     * @since 17/12/2016
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function index()
    {
        return redirect()->to(base_url('inspection/search_vehicle'));
    }

    /**
     * Form Add daily Inspection
     * @since 27/12/2016
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function add_daily_inspection($id = 'x')
    {
        $data['information'] = false;

        if ($id !== 'x') {
            $data['information'] = $this->generalModel->get_basic_search([
                'table'  => 'inspection_daily',
                'order'  => 'id_inspection_daily',
                'column' => 'id_inspection_daily',
                'id'     => $id,
            ]);
            $idVehicle = $data['information'][0]['fk_id_vehicle'];
        } else {
            $idVehicle = $this->session->get('idVehicle');
            if (!$idVehicle || $idVehicle == 'x') {
                return $this->response->setStatusCode(403)->setBody('ERROR!!! - You are in the wrong place.');
            }
        }

        $data['vehicleInfo'] = $this->generalModel->get_vehicle_by(['idVehicle' => $idVehicle]);
        $data['trailerList'] = $this->generalModel->get_basic_search([
            'table'  => 'param_vehicle',
            'order'  => 'id_vehicle',
            'column' => 'type_level_2',
            'id'     => 5,
        ]);

        return $this->render('App\Modules\Inspection\Views\form_daily_inspection', $data);
    }

    /**
     * Save daily_inspection
     * @since 27/12/2016
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function save_daily_inspection()
    {
        $post              = $this->request->getPost();
        $idDailyInspection = $post['hddId'] ?? '';
        $idVehicle         = (int) ($post['hddIdVehicle'] ?? 0);
        $idUser            = (int) $this->session->get('id');
        $userRol           = (int) $this->session->get('rol');
        $data              = [];

        $msj  = 'You have saved your inspection record, please do not forget to sign!!';
        $flag = true;
        if ($idDailyInspection !== '') {
            $msj  = 'You have updated the Inspection record!!';
            $flag = false;
        }

        $trailer        = $post['trailer'] ?? '';
        $trailerLights  = $post['trailerLights'] ?? '';
        $trailerTires   = $post['trailerTires'] ?? '';
        $trailerSlings  = $post['trailerSlings'] ?? '';
        $trailerClean   = $post['trailerClean'] ?? '';
        $trailerChains  = $post['trailerChains'] ?? '';
        $trailerRatchet = $post['trailerRatchet'] ?? '';

        if ($trailer !== '' && ($trailerLights == '' || $trailerTires == '' || $trailerSlings == '' || $trailerClean == '' || $trailerChains == '' || $trailerRatchet == '')) {
            $data["status"]             = 'error';
            $data['idDailyInspection'] = $idDailyInspection;
            session()->setFlashdata('retornoError', 'If you are using a Trailer, you must fill out the TRAILER or PUP form.');
            return $this->response->setJSON($data);
        }

        $idDailyInspection = $this->inspectionModel->saveDailyInspection($post, $idUser, $userRol);

        if ($idDailyInspection) {
            $this->inspectionModel->saveSeguimiento($post, $idVehicle);

            if ($flag) {
                $this->inspectionModel->saveInspectionTotal($idVehicle);
                $this->inspectionModel->saveVehicleNextOilChange($idVehicle, 1, $idDailyInspection, $post, $idUser);

                $comments = $post['comments'] ?? '';
                $hours    = $post['hours'] ?? 0;

                $headLamps       = $post['headLamps'] ?? 1;
                $hazardLights    = $post['hazardLights'] ?? 1;
                $bakeLights      = $post['bakeLights'] ?? 1;
                $workLights      = $post['workLights'] ?? 1;
                $turnSignals     = $post['turnSignals'] ?? 1;
                $beaconLight     = $post['beaconLight'] ?? 1;
                $clearanceLights = $post['clearanceLights'] ?? 1;
                $lightsCheck     = ($headLamps == 0 || $hazardLights == 0 || $bakeLights == 0 || $workLights == 0 || $turnSignals == 0 || $beaconLight == 0 || $clearanceLights == 0) ? 0 : 1;

                $heaterCheck     = $post['heater'] ?? 1;
                $brakesCheck     = $post['brakePedal'] ?? 1;
                $steeringCheck   = $post['steering_wheel'] ?? 1;
                $suspensionCheck = $post['suspension_system'] ?? 1;
                $tiresCheck      = $post['nuts'] ?? 1;
                $wipersCheck     = $post['wipers'] ?? 1;
                $airBrakeCheck   = $post['air_brake'] ?? 1;
                $driverSeatCheck = $post['passengerDoor'] ?? 1;
                $fuelCheck       = $post['fuel_system'] ?? 1;

                $sendNotification = false;
                $subject          = '';
                $emailMsnTitle    = '';
                if ($comments !== '') {
                    $emailMsnTitle    = '<p>The following inspection have comments please check the complete report in the system.</p>';
                    $subject          = 'Inspection with comments';
                    $sendNotification = true;
                }

                $failsEmail = '';
                $fails      = '';
                if ($heaterCheck == 0 || $brakesCheck == 0 || $lightsCheck == 0 || $steeringCheck == 0 || $suspensionCheck == 0 || $tiresCheck == 0 || $wipersCheck == 0 || $airBrakeCheck == 0 || $driverSeatCheck == 0 || $fuelCheck == 0) {
                    $majorDefect   = '<p>A major defect has been identified in the last inspection, a driver is not legally permitted to operate the vehicle until that defect is repaired.</p>';
                    $emailMsnTitle = $sendNotification ? $emailMsnTitle . $majorDefect : $majorDefect;
                    if ($heaterCheck == 0)     { $failsEmail .= '<br>Heater - Fail';                   $fails .= "\nHeater - Fail"; }
                    if ($brakesCheck == 0)     { $failsEmail .= '<br>Brake pedal - Fail';               $fails .= "\nBrake pedal - Fail"; }
                    if ($lightsCheck == 0)     { $failsEmail .= '<br>Lamps and reflectors - Fail';      $fails .= "\nLamps and reflectors - Fail"; }
                    if ($steeringCheck == 0)   { $failsEmail .= '<br>Steering wheel - Fail';            $fails .= "\nSteering wheel - Fail"; }
                    if ($suspensionCheck == 0) { $failsEmail .= '<br>Suspension system - Fail';         $fails .= "\nSuspension system - Fail"; }
                    if ($tiresCheck == 0)      { $failsEmail .= '<br>Tires/Lug Nuts/Pressure - Fail';   $fails .= "\nTires/Lug Nuts/Pressure - Fail"; }
                    if ($wipersCheck == 0)     { $failsEmail .= '<br>Wipers/Washers - Fail';            $fails .= "\nWipers/Washers - Fail"; }
                    if ($airBrakeCheck == 0)   { $failsEmail .= '<br>Air brake system - Fail';          $fails .= "\nAir brake system - Fail"; }
                    if ($driverSeatCheck == 0) { $failsEmail .= '<br>Driver and Passenger door - Fail'; $fails .= "\nDriver and Passenger door - Fail"; }
                    if ($fuelCheck == 0)       { $failsEmail .= '<br>Fuel system - Fail';               $fails .= "\nFuel system - Fail"; }
                    $subject          = $sendNotification ? $subject . ' & ' : '';
                    $subject         .= 'Inspection with major defect';
                    $sendNotification = true;
                }

                if ($sendNotification) {
                    $vehicleInfo = $this->generalModel->get_basic_search(['table' => 'param_vehicle', 'order' => 'id_vehicle', 'column' => 'id_vehicle', 'id' => $idVehicle]);
                    $urlMovil    = base_url('login/index/x/' . base64url_encode('INSPECTION_LIST_BY_EQUIPMENT_ID') . '/' . base64url_encode($idVehicle));

                    $emailBody  = $emailMsnTitle;
                    $emailBody .= '<strong>Make: </strong>' . esc($vehicleInfo[0]['make']);
                    $emailBody .= '<br><strong>Model: </strong>' . esc($vehicleInfo[0]['model']);
                    $emailBody .= '<br><strong>Unit Number: </strong>' . esc($vehicleInfo[0]['unit_number']);
                    $emailBody .= '<br><strong>Description: </strong>' . esc($vehicleInfo[0]['description']);
                    $emailBody .= '<br><strong>Equipment Hours/Kilometers: </strong>' . number_format($hours);
                    $emailBody .= $comments !== '' ? '<br><strong>Comments: </strong>' . esc($comments) : '';
                    $emailBody .= $failsEmail ? '<p><b>Fails:</b><br>' . $failsEmail . '</p>' : '';
                    $emailBody .= "<p>Follow the link to see the list. <a href='{$urlMovil}'>Click here</a></p>";

                    $smsMessage  = $subject . ' App - VCI';
                    $smsMessage .= "\nUnit Number: " . $vehicleInfo[0]['unit_number'];
                    $smsMessage .= $comments !== '' ? "\nComments: " . $comments : '';
                    $smsMessage .= $fails;
                    $smsMessage .= "\n\nSee: " . $urlMovil;

                    $configuracionAlertas = $this->generalModel->get_notifications_access(['idNotification' => ID_NOTIFICATION_INSPECTIONS]);
                    if ($configuracionAlertas) {
                        send_notification($configuracionAlertas, $subject, $emailBody, $smsMessage);
                    }
                }
            }

            $data["status"]             = "success";
            $data['idDailyInspection'] = $idDailyInspection;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data["status"]             = 'error';
            $data['idDailyInspection'] = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Form Add Heavy Inspection
     * @since 17/12/2016
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function add_heavy_inspection($id = 'x')
    {
        $data['information'] = false;

        if ($id !== 'x') {
            $data['information'] = $this->generalModel->get_basic_search([
                'table'  => 'inspection_heavy',
                'order'  => 'id_inspection_heavy',
                'column' => 'id_inspection_heavy',
                'id'     => $id,
            ]);
            $idVehicle = $data['information'][0]['fk_id_vehicle'];
        } else {
            $idVehicle = $this->session->get('idVehicle');
            if (!$idVehicle || $idVehicle == 'x') {
                return $this->response->setStatusCode(403)->setBody('ERROR!!! - You are in the wrong place.');
            }
        }

        $data['vehicleInfo'] = $this->generalModel->get_vehicle_by(['idVehicle' => $idVehicle]);

        return $this->render('App\Modules\Inspection\Views\\' . $data['vehicleInfo'][0]['form'], $data);
    }

    /**
     * Save heavy_inspection
     * @since 27/12/2016
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function save_heavy_inspection()
    {
        $post               = $this->request->getPost();
        $idHeavyInspection  = $post['hddId'] ?? '';
        $idVehicle          = (int) ($post['hddIdVehicle'] ?? 0);
        $idUser             = (int) $this->session->get('id');
        $userRol            = (int) $this->session->get('rol');
        $data               = [];

        $msj  = 'You have saved your inspection record, please do not forget to sign!!';
        $flag = true;
        if ($idHeavyInspection !== '') {
            $flag = false;
            $msj  = 'You have updated the Inspection record!!';
        }

        $idHeavyInspection = $this->inspectionModel->saveHeavyInspection($post, $idUser, $userRol);

        if ($idHeavyInspection) {
            if ($flag) {
                $this->inspectionModel->saveInspectionTotal($idVehicle);
                $this->inspectionModel->saveVehicleNextOilChange($idVehicle, 1, $idHeavyInspection, $post, $idUser);

                $comments = $post['comments'] ?? '';
                $hours    = $post['hours'] ?? 0;

                if ($comments !== '') {
                    $vehicleInfo = $this->generalModel->get_basic_search(['table' => 'param_vehicle', 'order' => 'id_vehicle', 'column' => 'id_vehicle', 'id' => $idVehicle]);
                    $urlMovil    = base_url('login/index/x/' . base64url_encode('INSPECTION_LIST_BY_EQUIPMENT_ID') . '/' . base64url_encode($idVehicle));

                    $emailBody  = '<p>The following inspection have comments please check the complete report in the system.</p>';
                    $emailBody .= '<strong>Make: </strong>' . esc($vehicleInfo[0]['make']);
                    $emailBody .= '<br><strong>Model: </strong>' . esc($vehicleInfo[0]['model']);
                    $emailBody .= '<br><strong>Unit Number: </strong>' . esc($vehicleInfo[0]['unit_number']);
                    $emailBody .= '<br><strong>Description: </strong>' . esc($vehicleInfo[0]['description']);
                    $emailBody .= '<br><strong>Equipment Hours/Kilometers: </strong>' . number_format($hours);
                    $emailBody .= '<br><strong>Comments: </strong>' . esc($comments);
                    $emailBody .= "<p>Follow the link to see the list. <a href='{$urlMovil}'>Click here</a></p>";

                    $smsMessage  = 'Inspection with comments App - VCI';
                    $smsMessage .= "\nUnit Number: " . $vehicleInfo[0]['unit_number'];
                    $smsMessage .= "\nComments: " . $comments;
                    $smsMessage .= "\n\nSee: " . $urlMovil;

                    $configuracionAlertas = $this->generalModel->get_notifications_access(['idNotification' => ID_NOTIFICATION_INSPECTIONS]);
                    if ($configuracionAlertas) {
                        send_notification($configuracionAlertas, 'Inspection with comments', $emailBody, $smsMessage);
                    }
                }
            }

            $data["status"]             = "success";
            $data['idHeavyInspection'] = $idHeavyInspection;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data["status"]             = 'error';
            $data['mensaje']           = 'Error!!! Ask for help.';
            $data['idHeavyInspection'] = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Form Generator Inspection
     * @since 16/3/2017
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function add_generator_inspection($id = 'x')
    {
        $data['information'] = false;

        if ($id !== 'x') {
            $data['information'] = $this->generalModel->get_basic_search([
                'table'  => 'inspection_generator',
                'order'  => 'id_inspection_generator',
                'column' => 'id_inspection_generator',
                'id'     => $id,
            ]);
            $idVehicle = $data['information'][0]['fk_id_vehicle'];
        } else {
            $idVehicle = $this->session->get('idVehicle');
            if (!$idVehicle || $idVehicle == 'x') {
                return $this->response->setStatusCode(403)->setBody('ERROR!!! - You are in the wrong place.');
            }
        }

        $data['vehicleInfo'] = $this->generalModel->get_vehicle_by(['idVehicle' => $idVehicle]);

        return $this->render('App\Modules\Inspection\Views\\' . $data['vehicleInfo'][0]['form'], $data);
    }

    /**
     * Save generator_inspection
     * @since 17/3/2017
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function save_generator_inspection()
    {
        $post                   = $this->request->getPost();
        $idGeneratorInspection  = $post['hddId'] ?? '';
        $idVehicle              = (int) ($post['hddIdVehicle'] ?? 0);
        $idUser                 = (int) $this->session->get('id');
        $userRol                = (int) $this->session->get('rol');
        $data                   = [];

        $msj  = 'You have saved your inspection record, please do not forget to sign!!';
        $flag = true;
        if ($idGeneratorInspection !== '') {
            $flag = false;
            $msj  = 'You have updated the Inspection record!!';
        }

        $idGeneratorInspection = $this->inspectionModel->saveGeneratorInspection($post, $idUser, $userRol);

        if ($idGeneratorInspection) {
            if ($flag) {
                $this->inspectionModel->saveVehicleNextOilChange($idVehicle, 1, $idGeneratorInspection, $post, $idUser);

                $comments = $post['comments'] ?? '';
                $hours    = $post['hours'] ?? 0;

                if ($comments !== '') {
                    $vehicleInfo = $this->generalModel->get_basic_search(['table' => 'param_vehicle', 'order' => 'id_vehicle', 'column' => 'id_vehicle', 'id' => $idVehicle]);
                    $urlMovil    = base_url('login/index/x/' . base64url_encode('INSPECTION_LIST_BY_EQUIPMENT_ID') . '/' . base64url_encode($idVehicle));

                    $emailBody  = '<p>The following inspection have comments please check the complete report in the system.</p>';
                    $emailBody .= '<strong>Make: </strong>' . esc($vehicleInfo[0]['make']);
                    $emailBody .= '<br><strong>Model: </strong>' . esc($vehicleInfo[0]['model']);
                    $emailBody .= '<br><strong>Unit Number: </strong>' . esc($vehicleInfo[0]['unit_number']);
                    $emailBody .= '<br><strong>Description: </strong>' . esc($vehicleInfo[0]['description']);
                    $emailBody .= '<br><strong>Equipment Hours/Kilometers: </strong>' . number_format($hours);
                    $emailBody .= '<br><strong>Comments: </strong>' . esc($comments);
                    $emailBody .= "<p>Follow the link to see the list. <a href='{$urlMovil}'>Click here</a></p>";

                    $smsMessage  = 'Inspection with comments App - VCI';
                    $smsMessage .= "\nUnit Number: " . $vehicleInfo[0]['unit_number'];
                    $smsMessage .= "\nComments: " . $comments;
                    $smsMessage .= "\n\nSee: " . $urlMovil;

                    $configuracionAlertas = $this->generalModel->get_notifications_access(['idNotification' => ID_NOTIFICATION_INSPECTIONS]);
                    if ($configuracionAlertas) {
                        send_notification($configuracionAlertas, $smsMessage, $emailBody, $smsMessage);
                    }
                }
            }

            $data["status"]                 = "success";
            $data['idGeneratorInspection'] = $idGeneratorInspection;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data["status"]                 = 'error';
            $data['mensaje']               = 'Error!!! Ask for help.';
            $data['idGeneratorInspection'] = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Form SWEEPER Inspection
     * @since 22/4/2017
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function add_sweeper_inspection($id = 'x')
    {
        $data['information'] = false;

        if ($id !== 'x') {
            $data['information'] = $this->generalModel->get_basic_search([
                'table'  => 'inspection_sweeper',
                'order'  => 'id_inspection_sweeper',
                'column' => 'id_inspection_sweeper',
                'id'     => $id,
            ]);
            $idVehicle = $data['information'][0]['fk_id_vehicle'];
        } else {
            $idVehicle = $this->session->get('idVehicle');
            if (!$idVehicle || $idVehicle == 'x') {
                return $this->response->setStatusCode(403)->setBody('ERROR!!! - You are in the wrong place.');
            }
        }

        $data['vehicleInfo'] = $this->generalModel->get_vehicle_by(['idVehicle' => $idVehicle]);

        return $this->render('App\Modules\Inspection\Views\\' . $data['vehicleInfo'][0]['form'], $data);
    }

    /**
     * Save sweeper inspection
     * @since 22/4/2017
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function save_sweeper_inspection()
    {
        $post                  = $this->request->getPost();
        $idSweeperInspection   = $post['hddId'] ?? '';
        $idVehicle             = (int) ($post['hddIdVehicle'] ?? 0);
        $idUser                = (int) $this->session->get('id');
        $userRol               = (int) $this->session->get('rol');
        $data                  = [];

        $msj  = 'You have saved your inspection record, please do not forget to sign!!';
        $flag = true;
        if ($idSweeperInspection !== '') {
            $flag = false;
            $msj  = 'You have updated the Inspection record!!';
        }

        $idSweeperInspection = $this->inspectionModel->saveSweeperInspection($post, $idUser, $userRol);

        if ($idSweeperInspection) {
            if ($flag) {
                $this->inspectionModel->saveInspectionTotal($idVehicle);
                $this->inspectionModel->saveVehicleNextOilChange($idVehicle, 1, $idSweeperInspection, $post, $idUser);

                $comments = $post['comments'] ?? '';
                $hours    = $post['hours'] ?? 0;
                $hours2   = $post['hours2'] ?? 0;

                if ($comments !== '') {
                    $vehicleInfo = $this->generalModel->get_basic_search(['table' => 'param_vehicle', 'order' => 'id_vehicle', 'column' => 'id_vehicle', 'id' => $idVehicle]);
                    $urlMovil    = base_url('login/index/x/' . base64url_encode('INSPECTION_LIST_BY_EQUIPMENT_ID') . '/' . base64url_encode($idVehicle));

                    $emailBody  = '<p>The following inspection have comments please check the complete report in the system.</p>';
                    $emailBody .= '<strong>Make: </strong>' . esc($vehicleInfo[0]['make']);
                    $emailBody .= '<br><strong>Model: </strong>' . esc($vehicleInfo[0]['model']);
                    $emailBody .= '<br><strong>Unit Number: </strong>' . esc($vehicleInfo[0]['unit_number']);
                    $emailBody .= '<br><strong>Description: </strong>' . esc($vehicleInfo[0]['description']);
                    $emailBody .= '<br><strong>Truck Engine Hours: </strong>' . number_format($hours);
                    $emailBody .= '<br><strong>Sweeper Engine Hours: </strong>' . number_format($hours2);
                    $emailBody .= '<br><strong>Comments: </strong>' . esc($comments);
                    $emailBody .= "<p>Follow the link to see the list. <a href='{$urlMovil}'>Click here</a></p>";

                    $smsMessage  = 'Inspection with comments App - VCI';
                    $smsMessage .= "\nUnit Number: " . $vehicleInfo[0]['unit_number'];
                    $smsMessage .= "\nComments: " . $comments;
                    $smsMessage .= "\n\nSee: " . $urlMovil;

                    $configuracionAlertas = $this->generalModel->get_notifications_access(['idNotification' => ID_NOTIFICATION_INSPECTIONS]);
                    if ($configuracionAlertas) {
                        send_notification($configuracionAlertas, $smsMessage, $emailBody, $smsMessage);
                    }
                }
            }

            $data["status"]                = "success";
            $data['idSweeperInspection']  = $idSweeperInspection;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data["status"]                = 'error';
            $data['idSweeperInspection']  = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Form hydrovac Inspection
     * @since 23/4/2017
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function add_hydrovac_inspection($id = 'x')
    {
        $data['information'] = false;

        if ($id !== 'x') {
            $data['information'] = $this->generalModel->get_basic_search([
                'table'  => 'inspection_hydrovac',
                'order'  => 'id_inspection_hydrovac',
                'column' => 'id_inspection_hydrovac',
                'id'     => $id,
            ]);
            $idVehicle = $data['information'][0]['fk_id_vehicle'];
        } else {
            $idVehicle = $this->session->get('idVehicle');
            if (!$idVehicle || $idVehicle == 'x') {
                return $this->response->setStatusCode(403)->setBody('ERROR!!! - You are in the wrong place.');
            }
        }

        $data['vehicleInfo'] = $this->generalModel->get_vehicle_by(['idVehicle' => $idVehicle]);

        return $this->render('App\Modules\Inspection\Views\\' . $data['vehicleInfo'][0]['form'], $data);
    }

    /**
     * Save hydrovac inspection
     * @since 23/4/2017
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function save_hydrovac_inspection()
    {
        $post                   = $this->request->getPost();
        $idHydrovacInspection   = $post['hddId'] ?? '';
        $idVehicle              = (int) ($post['hddIdVehicle'] ?? 0);
        $idUser                 = (int) $this->session->get('id');
        $userRol                = (int) $this->session->get('rol');
        $data                   = [];

        $msj  = 'You have saved your inspection record, please do not forget to sign!!';
        $flag = true;
        if ($idHydrovacInspection !== '') {
            $flag = false;
            $msj  = 'You have updated the Inspection record!!';
        }

        $idHydrovacInspection = $this->inspectionModel->saveHydrovacInspection($post, $idUser, $userRol);

        if ($idHydrovacInspection) {
            $this->inspectionModel->saveSeguimientoHydrovac($post, $idVehicle);

            if ($flag) {
                $this->inspectionModel->saveInspectionTotal($idVehicle);
                $this->inspectionModel->saveVehicleNextOilChange($idVehicle, 1, $idHydrovacInspection, $post, $idUser);

                $comments = $post['comments'] ?? '';
                $hours    = $post['hours'] ?? 0;
                $hours2   = $post['hours2'] ?? 0;
                $hours3   = $post['hours3'] ?? 0;

                $headLamps       = $post['headLamps'] ?? 1;
                $hazardLights    = $post['hazardLights'] ?? 1;
                $clearanceLights = $post['clearanceLights'] ?? 1;
                $tailLights      = $post['tailLights'] ?? 1;
                $workLights      = $post['workLights'] ?? 1;
                $turnSignals     = $post['turnSignals'] ?? 1;
                $beaconLight     = $post['beaconLights'] ?? 1;
                $lightsCheck     = ($headLamps == 0 || $hazardLights == 0 || $tailLights == 0 || $workLights == 0 || $turnSignals == 0 || $beaconLight == 0 || $clearanceLights == 0) ? 0 : 1;

                $heaterCheck     = $post['heater'] ?? 1;
                $brakesCheck     = $post['brake'] ?? 1;
                $steeringCheck   = $post['steering_wheel'] ?? 1;
                $suspensionCheck = $post['suspension_system'] ?? 1;
                $tiresCheck      = $post['tires'] ?? 1;
                $wipersCheck     = $post['wipers'] ?? 1;
                $airBrakeCheck   = $post['air_brake'] ?? 1;
                $driverSeatCheck = $post['door'] ?? 1;
                $fuelCheck       = $post['fuel_system'] ?? 1;

                $sendNotification = false;
                $subject          = '';
                $emailMsnTitle    = '';
                if ($comments !== '') {
                    $emailMsnTitle    = '<p>The following inspection have comments please check the complete report in the system.</p>';
                    $subject          = 'Inspection with comments';
                    $sendNotification = true;
                }

                $failsEmail = '';
                $fails      = '';
                if ($heaterCheck == 0 || $brakesCheck == 0 || $lightsCheck == 0 || $steeringCheck == 0 || $suspensionCheck == 0 || $tiresCheck == 0 || $wipersCheck == 0 || $airBrakeCheck == 0 || $driverSeatCheck == 0 || $fuelCheck == 0) {
                    $majorDefect   = '<p>A major defect has been identified in the last inspection, a driver is not legally permitted to operate the vehicle until that defect is repaired.</p>';
                    $emailMsnTitle = $sendNotification ? $emailMsnTitle . $majorDefect : $majorDefect;
                    if ($heaterCheck == 0)     { $failsEmail .= '<br>Heater - Fail';                   $fails .= "\nHeater - Fail"; }
                    if ($brakesCheck == 0)     { $failsEmail .= '<br>Brake pedal - Fail';               $fails .= "\nBrake pedal - Fail"; }
                    if ($lightsCheck == 0)     { $failsEmail .= '<br>Lamps and reflectors - Fail';      $fails .= "\nLamps and reflectors - Fail"; }
                    if ($steeringCheck == 0)   { $failsEmail .= '<br>Steering wheel - Fail';            $fails .= "\nSteering wheel - Fail"; }
                    if ($suspensionCheck == 0) { $failsEmail .= '<br>Suspension system - Fail';         $fails .= "\nSuspension system - Fail"; }
                    if ($tiresCheck == 0)      { $failsEmail .= '<br>Tires/Lug Nuts/Pressure - Fail';   $fails .= "\nTires/Lug Nuts/Pressure - Fail"; }
                    if ($wipersCheck == 0)     { $failsEmail .= '<br>Wipers/Washers - Fail';            $fails .= "\nWipers/Washers - Fail"; }
                    if ($airBrakeCheck == 0)   { $failsEmail .= '<br>Air brake system - Fail';          $fails .= "\nAir brake system - Fail"; }
                    if ($driverSeatCheck == 0) { $failsEmail .= '<br>Driver and Passenger door - Fail'; $fails .= "\nDriver and Passenger door - Fail"; }
                    if ($fuelCheck == 0)       { $failsEmail .= '<br>Fuel system - Fail';               $fails .= "\nFuel system - Fail"; }
                    $subject          = $sendNotification ? $subject . ' & ' : '';
                    $subject         .= 'Inspection with major defect';
                    $sendNotification = true;
                }

                if ($sendNotification) {
                    $vehicleInfo = $this->generalModel->get_basic_search(['table' => 'param_vehicle', 'order' => 'id_vehicle', 'column' => 'id_vehicle', 'id' => $idVehicle]);
                    $urlMovil    = base_url('login/index/x/' . base64url_encode('INSPECTION_LIST_BY_EQUIPMENT_ID') . '/' . base64url_encode($idVehicle));

                    $emailBody  = $emailMsnTitle;
                    $emailBody .= '<strong>Make: </strong>' . esc($vehicleInfo[0]['make']);
                    $emailBody .= '<br><strong>Model: </strong>' . esc($vehicleInfo[0]['model']);
                    $emailBody .= '<br><strong>Unit Number: </strong>' . esc($vehicleInfo[0]['unit_number']);
                    $emailBody .= '<br><strong>Description: </strong>' . esc($vehicleInfo[0]['description']);
                    $emailBody .= '<br><strong>Engine Hours: </strong>' . number_format($hours);
                    $emailBody .= '<br><strong>Hydraulic Pump Hours: </strong>' . number_format($hours2);
                    $emailBody .= '<br><strong>Blower Hours: </strong>' . number_format($hours3);
                    $emailBody .= $comments !== '' ? '<br><strong>Comments: </strong>' . esc($comments) : '';
                    $emailBody .= $failsEmail ?: '';
                    $emailBody .= "<p>Follow the link to see the list. <a href='{$urlMovil}'>Click here</a></p>";

                    $smsMessage  = $subject . ' App - VCI';
                    $smsMessage .= "\nUnit Number: " . $vehicleInfo[0]['unit_number'];
                    $smsMessage .= $comments !== '' ? "\nComments: " . $comments : '';
                    $smsMessage .= $fails;
                    $smsMessage .= "\n\nSee: " . $urlMovil;

                    $configuracionAlertas = $this->generalModel->get_notifications_access(['idNotification' => ID_NOTIFICATION_INSPECTIONS]);
                    if ($configuracionAlertas) {
                        send_notification($configuracionAlertas, $subject, $emailBody, $smsMessage);
                    }
                }
            }

            $data["status"]                  = "success";
            $data['idHydrovacInspection']   = $idHydrovacInspection;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data["status"]                  = 'error';
            $data['mensaje']                = 'Error!!! Ask for help.';
            $data['idHydrovacInspection']   = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Form water truck Inspection
     * @since 11/6/2017
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function add_watertruck_inspection($id = 'x')
    {
        $data['information'] = false;

        if ($id !== 'x') {
            $data['information'] = $this->generalModel->get_basic_search([
                'table'  => 'inspection_watertruck',
                'order'  => 'id_inspection_watertruck',
                'column' => 'id_inspection_watertruck',
                'id'     => $id,
            ]);
            $idVehicle = $data['information'][0]['fk_id_vehicle'];
        } else {
            $idVehicle = $this->session->get('idVehicle');
            if (!$idVehicle || $idVehicle == 'x') {
                return $this->response->setStatusCode(403)->setBody('ERROR!!! - You are in the wrong place.');
            }
        }

        $data['vehicleInfo'] = $this->generalModel->get_vehicle_by(['idVehicle' => $idVehicle]);

        return $this->render('App\Modules\Inspection\Views\\' . $data['vehicleInfo'][0]['form'], $data);
    }

    /**
     * Save water truck inspection
     * @since 12/6/2017
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function save_watertruck_inspection()
    {
        $post                     = $this->request->getPost();
        $idWatertruckInspection   = $post['hddId'] ?? '';
        $idVehicle                = (int) ($post['hddIdVehicle'] ?? 0);
        $idUser                   = (int) $this->session->get('id');
        $userRol                  = (int) $this->session->get('rol');
        $data                     = [];

        $msj  = 'You have saved your inspection record, please do not forget to sign!!';
        $flag = true;
        if ($idWatertruckInspection !== '') {
            $flag = false;
            $msj  = 'You have updated the Inspection record!!';
        }

        $idWatertruckInspection = $this->inspectionModel->saveWatertruckInspection($post, $idUser, $userRol);

        if ($idWatertruckInspection) {
            $this->inspectionModel->saveSeguimientoWatertruck($post, $idVehicle);

            if ($flag) {
                $this->inspectionModel->saveInspectionTotal($idVehicle);
                $this->inspectionModel->saveVehicleNextOilChange($idVehicle, 1, $idWatertruckInspection, $post, $idUser);

                $comments = $post['comments'] ?? '';
                $hours    = $post['hours'] ?? 0;

                $headLamps       = $post['headLamps'] ?? 1;
                $hazardLights    = $post['hazardLights'] ?? 1;
                $clearanceLights = $post['clearanceLights'] ?? 1;
                $tailLights      = $post['tailLights'] ?? 1;
                $workLights      = $post['workLights'] ?? 1;
                $turnSignals     = $post['turnSignals'] ?? 1;
                $beaconLight     = $post['beaconLights'] ?? 1;
                $lightsCheck     = ($headLamps == 0 || $hazardLights == 0 || $tailLights == 0 || $workLights == 0 || $turnSignals == 0 || $beaconLight == 0 || $clearanceLights == 0) ? 0 : 1;

                $heaterCheck     = $post['heater'] ?? 1;
                $brakesCheck     = $post['brake'] ?? 1;
                $steeringCheck   = $post['steering_wheel'] ?? 1;
                $suspensionCheck = $post['suspension_system'] ?? 1;
                $tiresCheck      = $post['tires'] ?? 1;
                $wipersCheck     = $post['wipers'] ?? 1;
                $airBrakeCheck   = $post['air_brake'] ?? 1;
                $driverSeatCheck = $post['door'] ?? 1;
                $fuelCheck       = $post['fuel_system'] ?? 1;

                $sendNotification = false;
                $subject          = '';
                $emailMsnTitle    = '';
                if ($comments !== '') {
                    $emailMsnTitle    = '<p>The following inspection have comments please check the complete report in the system.</p>';
                    $subject          = 'Inspection with comments';
                    $sendNotification = true;
                }

                $failsEmail = '';
                $fails      = '';
                if ($heaterCheck == 0 || $brakesCheck == 0 || $lightsCheck == 0 || $steeringCheck == 0 || $suspensionCheck == 0 || $tiresCheck == 0 || $wipersCheck == 0 || $airBrakeCheck == 0 || $driverSeatCheck == 0 || $fuelCheck == 0) {
                    $majorDefect   = '<p>A major defect has been identified in the last inspection, a driver is not legally permitted to operate the vehicle until that defect is repaired.</p>';
                    $emailMsnTitle = $sendNotification ? $emailMsnTitle . $majorDefect : $majorDefect;
                    if ($heaterCheck == 0)     { $failsEmail .= '<br>Heater - Fail';                   $fails .= "\nHeater - Fail"; }
                    if ($brakesCheck == 0)     { $failsEmail .= '<br>Brake pedal - Fail';               $fails .= "\nBrake pedal - Fail"; }
                    if ($lightsCheck == 0)     { $failsEmail .= '<br>Lamps and reflectors - Fail';      $fails .= "\nLamps and reflectors - Fail"; }
                    if ($steeringCheck == 0)   { $failsEmail .= '<br>Steering wheel - Fail';            $fails .= "\nSteering wheel - Fail"; }
                    if ($suspensionCheck == 0) { $failsEmail .= '<br>Suspension system - Fail';         $fails .= "\nSuspension system - Fail"; }
                    if ($tiresCheck == 0)      { $failsEmail .= '<br>Tires/Lug Nuts/Pressure - Fail';   $fails .= "\nTires/Lug Nuts/Pressure - Fail"; }
                    if ($wipersCheck == 0)     { $failsEmail .= '<br>Wipers/Washers - Fail';            $fails .= "\nWipers/Washers - Fail"; }
                    if ($airBrakeCheck == 0)   { $failsEmail .= '<br>Air brake system - Fail';          $fails .= "\nAir brake system - Fail"; }
                    if ($driverSeatCheck == 0) { $failsEmail .= '<br>Driver and Passenger door - Fail'; $fails .= "\nDriver and Passenger door - Fail"; }
                    if ($fuelCheck == 0)       { $failsEmail .= '<br>Fuel system - Fail';               $fails .= "\nFuel system - Fail"; }
                    $subject          = $sendNotification ? $subject . ' & ' : '';
                    $subject         .= 'Inspection with major defect';
                    $sendNotification = true;
                }

                if ($sendNotification) {
                    $vehicleInfo = $this->generalModel->get_basic_search(['table' => 'param_vehicle', 'order' => 'id_vehicle', 'column' => 'id_vehicle', 'id' => $idVehicle]);
                    $urlMovil    = base_url('login/index/x/' . base64url_encode('INSPECTION_LIST_BY_EQUIPMENT_ID') . '/' . base64url_encode($idVehicle));

                    $emailBody  = $emailMsnTitle;
                    $emailBody .= '<strong>Make: </strong>' . esc($vehicleInfo[0]['make']);
                    $emailBody .= '<br><strong>Model: </strong>' . esc($vehicleInfo[0]['model']);
                    $emailBody .= '<br><strong>Unit Number: </strong>' . esc($vehicleInfo[0]['unit_number']);
                    $emailBody .= '<br><strong>Description: </strong>' . esc($vehicleInfo[0]['description']);
                    $emailBody .= '<br><strong>Equipment Hours/Kilometers: </strong>' . number_format($hours);
                    $emailBody .= $comments !== '' ? '<br><strong>Comments: </strong>' . esc($comments) : '';
                    $emailBody .= $failsEmail ?: '';
                    $emailBody .= "<p>Follow the link to see the list. <a href='{$urlMovil}'>Click here</a></p>";

                    $smsMessage  = $subject . ' App - VCI';
                    $smsMessage .= "\nUnit Number: " . $vehicleInfo[0]['unit_number'];
                    $smsMessage .= $comments !== '' ? "\nComments: " . $comments : '';
                    $smsMessage .= $fails;
                    $smsMessage .= "\n\nSee: " . $urlMovil;

                    $configuracionAlertas = $this->generalModel->get_notifications_access(['idNotification' => ID_NOTIFICATION_INSPECTIONS]);
                    if ($configuracionAlertas) {
                        send_notification($configuracionAlertas, $subject, $emailBody, $smsMessage);
                    }
                }
            }

            $data["status"]                    = "success";
            $data['idWatertruckInspection']   = $idWatertruckInspection;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data["status"]                    = 'error';
            $data['mensaje']                  = 'Error!!! Ask for help.';
            $data['idWatertruckInspection']   = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Search vehicle by vin number
     * @since 14/4/2020
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function search_vehicle()
    {
        return $this->render('App\Modules\Inspection\Views\form_search_vehicle', []);
    }

    /**
     * Vehicle information
     * @since 14/4/2020
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function vehicleInfo()
    {
        $vehicleInfo = $this->generalModel->get_vehicle_by([
            'vinNumber'    => $this->request->getPost('vinNumber'),
            'vehicleState' => 1,
        ]);

        $html = '';

        if ($vehicleInfo) {
            foreach ($vehicleInfo as $lista) {
                $html .= '<div class="panel panel-info">';
                $html .= '<div class="panel-heading"><i class="fa fa-automobile"></i> <b>INFO - </b>' . esc($lista['description']) . '</div>';
                $html .= '<div class="panel-body">';

                if ($lista['photo']) {
                    $html .= '<div class="form-group"><div class="row" align="center">';
                    $html .= '<img src="' . base_url(esc($lista['photo'])) . '" class="img-rounded" alt="Vehicle Photo" />';
                    $html .= '</div></div>';
                }

                $html .= '<strong>Make: </strong>' . esc($lista['make']) . '<br>';
                $html .= '<strong>Model: </strong>' . esc($lista['model']) . '<br>';
                $html .= '<strong>Description: </strong>' . esc($lista['description']) . '<br>';
                $html .= '<strong>Unit Number: </strong>' . esc($lista['unit_number']) . '<br>';
                $html .= '<strong>VIN Number: </strong>' . esc($lista['vin_number']) . '<br>';
                $html .= '<strong>Type: </strong><br>';

                switch ($lista['type_level_1']) {
                    case 1:  $type = 'Fleet';  break;
                    case 2:  $type = 'Rental'; break;
                    case 99: $type = 'Other';  break;
                    default: $type = '';
                }
                $html .= esc($type) . ' - ' . esc($lista['type_2']) . '<br>';

                $tipo = $lista['type_level_2'];
                $html .= "<p class='text-danger'>";
                if ($tipo == 15) {
                    $html .= '<strong>Truck Engine Hours: </strong>' . number_format($lista['hours']);
                    $html .= '<br><strong>Sweeper Engine Hours: </strong>' . number_format($lista['hours_2']);
                } elseif ($tipo == 16) {
                    $html .= '<strong>Engine Hours: </strong>' . number_format($lista['hours']);
                    $html .= '<br><strong>Hydraulic Pump Hours: </strong>' . number_format($lista['hours_2']);
                    $html .= '<br><strong>Blower Hours: </strong>' . number_format($lista['hours_3']);
                } else {
                    $html .= '<strong>Equipment Hours/Kilometers: </strong>' . number_format($lista['hours']);
                }
                $html .= '</p>';

                $inspectionType  = $lista['inspection_type'];
                $linkInspection  = $lista['link_inspection'];

                if ($inspectionType == 99 || $linkInspection == 'NA') {
                    $html .= "<div class='alert alert-danger'><b>No Inspection Format</b></div>";
                } else {
                    $html .= "<a class='btn btn-info btn-block' href='" . base_url('inspection/set_vehicle/' . (int) $lista['id_vehicle']) . "'>";
                    $html .= " Inspection Form <span class='fa fa-wrench' aria-hidden='true'></span></a>";
                }

                $html .= '</div></div>';
            }
        } else {
            $html = "<p class='text-danger'>There are no records with that VIN number.</p>";
        }

        return $this->response
            ->setContentType('text/html; charset=utf-8')
            ->setBody($html);
    }

    /**
     * Set session with vehicle ID to do inspection
     * @since 14/4/2020
     * @author BMOTTAG
     * @review 30/04/2026 - new CI4 version
     */
    public function set_vehicle(int $idVehicle)
    {
        $vehicleInfo = $this->generalModel->get_vehicle_by(['idVehicle' => $idVehicle]);

        $this->session->set([
            'idVehicle'       => $idVehicle,
            'inspectionType'  => $vehicleInfo[0]['inspection_type'],
            'linkInspection'  => $vehicleInfo[0]['link_inspection'],
            'formInspection'  => $vehicleInfo[0]['form'],
        ]);

        return redirect()->to(base_url($vehicleInfo[0]['link_inspection']));
    }
}
