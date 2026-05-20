<?php
namespace App\Modules\Serviceorder\Controllers;

use App\Controllers\BaseController;
use App\Modules\Serviceorder\Models\ServiceorderModel;
use App\Models\GeneralModel;
use App\Libraries\PdfBuilder;
use App\Libraries\SmsService;

class Serviceorder extends BaseController
{
    protected $serviceorderModel;
    protected $generalModel;

    public function __construct()
    {
        $this->serviceorderModel = new ServiceorderModel();
        $this->generalModel      = new GeneralModel();
    }

    /**
     * Service Order Control Panel
     * @since 19/5/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function index($idServiceOrder = 'x', $idEquipment = 'x', $moduleView = 'x')
    {
        $data = [
            'infoSpecificSO' => false,
            'infoEquipment'  => false,
            'infoSO'         => false,
            'information'    => false,
            'idEquipment'    => false,
            'moduleView'     => $moduleView,
        ];

        if ($idServiceOrder !== 'x') {
            $data['infoSpecificSO'] = $this->serviceorderModel->get_service_order([
                'idServiceOrder' => base64url_decode($idServiceOrder),
            ]);
        } elseif ($idEquipment !== 'x') {
            $data['idEquipment'] = base64url_decode($idEquipment);
        } else {
            $data['infoEquipment'] = $this->generalModel->countEquipmentByType();
            $data['infoSO']        = $this->generalModel->countSOByStatus();
            $data['information']   = $this->serviceorderModel->get_service_order(['limit' => 50]);
        }

        return $this->render('App\Modules\Serviceorder\Views\dashboard_so', $data);
    }

    /**
     * Cargo modal - service order
     * @since 18/5/2023
     * @review 14/05/2026 - new CI4 version
     */
    public function cargarModalServiceOrder()
    {
        $data['information']  = false;
        $data['idServiceOrder'] = $this->request->getPost('idServiceOrder');
        $data['idEquipment'] = $this->request->getPost('idEquipment');
        $data['idMaintenance'] = $this->request->getPost('idMaintenance');

        $data['workersList'] = $this->generalModel->get_user(['state' => 1]);

        $data['priorityList'] = $this->generalModel->get_basic_search([
            'table'  => 'param_status',
            'order'  => 'status_order',
            'column' => 'status_key',
            'id'     => 'priority',
        ]);

        if ($data['idServiceOrder'] !== 'x') {
            $data['statusList'] = $this->generalModel->get_basic_search([
                'table'  => 'param_status',
                'order'  => 'status_order',
                'column' => 'status_key',
                'id'     => 'serviceorder',
            ]);

            $data['information']    = $this->serviceorderModel->get_service_order(['idServiceOrder' => $data['idServiceOrder']]);
            $data['maintenanceType'] = $data['information'][0]['maintenace_type'];
            $idMaintenance           = $data['information'][0]['fk_id_maintenace'];
        } else {
            $data['maintenanceType'] = $this->request->getPost('maintenanceType');
            $idMaintenance           = $this->request->getPost('idMaintenance');
        }

        $data['currentMaintenance']         = '';
        $data['nextMaintenance']            = '';
        $data['nextMaintenanceValue']       = '';
        $data['maintenanceDescription']     = '';
        $data['maintenanceDescriptionSMS']  = '';
        $data['maintenanceTypeDescription'] = '';

        if ($data['maintenanceType'] === 'corrective') {
            $infoMaintenance = $this->serviceorderModel->get_corrective_maintenance(['idMaintenance' => $idMaintenance]);
            $data['maintenanceDescriptionSMS'] = $data['maintenanceDescription'] = $infoMaintenance[0]['description_failure'];
            $data['maintenanceTypeDescription'] = 'Corrective Maintenance';
        } else {
            $infoMaintenance = $this->serviceorderModel->get_preventive_maintenance(['idMaintenance' => $idMaintenance]);

            if ($infoMaintenance) {
                $data['maintenanceDescription']    = $infoMaintenance[0]['maintenance_description'] . '<br><strong>Maintennace Type: </strong>' . $infoMaintenance[0]['maintenance_type'];
                $data['maintenanceDescriptionSMS'] = $infoMaintenance[0]['maintenance_description'];
                $data['maintenanceTypeDescription'] = 'Preventive Maintenance';

                if ($data['idServiceOrder'] !== 'x') {
                    $tipo = $data['information'][0]['type_level_2'];
                    if ($tipo == 15) {
                        $data['currentMaintenance'] = $infoMaintenance[0]['id_maintenance_type'] == 8
                            ? '<br><b>Current Sweeper Engine Hours: </b>' . number_format($data['information'][0]['hours_2'])
                            : '<b>Current Truck Engine Hours: </b>' . number_format($data['information'][0]['hours']);
                    } elseif ($tipo == 16) {
                        if ($infoMaintenance[0]['id_maintenance_type'] == 10) {
                            $data['currentMaintenance'] = '<br><strong>Blower Hours: </strong>' . number_format($data['information'][0]['hours_3']);
                        } elseif ($infoMaintenance[0]['id_maintenance_type'] == 9) {
                            $data['currentMaintenance'] = '<br><strong>Hydraulic Pump Hours: </strong>' . number_format($data['information'][0]['hours_2']);
                        } else {
                            $data['currentMaintenance'] = '<br><strong>Engine Hours: </strong>' . number_format($data['information'][0]['hours']);
                        }
                    } else {
                        $data['currentMaintenance'] = '<br><b>Current Equipment Hours/Kilometers: </b>' . number_format($data['information'][0]['hours']);
                    }
                }

                $data['nextMaintenance']      = $infoMaintenance[0]['verification_by'] == 1
                    ? '<br><b>Next Hours/Kilometers Maintenance: </b>' . number_format($infoMaintenance[0]['next_hours_maintenance'])
                    : '<br><b>Next Date Maintenance: </b>' . $infoMaintenance[0]['next_date_maintenance'];
                $data['nextMaintenanceValue'] = $infoMaintenance[0]['verification_by'] == 1
                    ? $infoMaintenance[0]['next_hours_maintenance']
                    : $infoMaintenance[0]['next_date_maintenance'];
            }
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Serviceorder\Views\service_order_modal', $data));
    }

    /**
     * Cargo modal - Preventive Maintenance
     * @since 23/5/2023
     * @review 14/05/2026 - new CI4 version
     */
    public function cargarModalPreventiveMaintenance()
    {
        $data['information']  = false;
        $data['idMaintenance'] = $this->request->getPost('idMaintenance');
        $data['idEquipment'] = $this->request->getPost('idEquipment');

        $data['infoTypeMaintenance'] = $this->generalModel->get_basic_search([
            'table' => 'maintenance_type',
            'order' => 'maintenance_type',
            'id'    => 'x',
        ]);

        if ($data['idMaintenance'] !== 'x') {
            $data['information'] = $this->serviceorderModel->get_preventive_maintenance([
                'idMaintenance' => $data['idMaintenance'],
            ]);
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Serviceorder\Views\preventive_maintenance_modal', $data));
    }

    /**
     * Cargo modal - Corrective Maintenance
     * @since 26/5/2023
     * @review 14/05/2026 - new CI4 version
     */
    public function cargarModalCorrectiveMaintenance()
    {
        $data['information']  = false;
        $data['idMaintenance'] = $this->request->getPost('idMaintenance');
        $data['idEquipment'] = $this->request->getPost('idEquipment');

        if ($data['idMaintenance'] !== 'x') {
            $data['information'] = $this->serviceorderModel->get_corrective_maintenance([
                'idMaintenance' => $data['idMaintenance'],
            ]);
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Serviceorder\Views\corrective_maintenance_modal', $data));
    }

    /**
     * Save Service Order
     * @since 18/5/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function save_service_order()
    {
        $post           = $this->request->getPost();
        $idServiceOrder = $post['hddIdServiceOrder'] ?? '';
        $idEquipment    = $post['hddIdEquipment'] ?? '';
        $maintenaceType = $post['hddMaintenanceType'] ?? '';
        $status         = $post['status'] ?? '';
        $oldStatus      = $post['hddStatus'] ?? '';

        $msj  = $idServiceOrder !== '' ? 'You have updated the Service Order!!' : 'You have added a new Service Order!!';
        $data = ['idEquipment' => $idEquipment];

        if ($data['idServiceOrder'] = $this->serviceorderModel->saveServiceOrder($post)) {
            if ($idServiceOrder === '' && $maintenaceType === 'corrective') {
                $this->generalModel->updateRecord([
                    'table'      => 'corrective_maintenance',
                    'primaryKey' => 'id_corrective_maintenance',
                    'id'         => $post['hddIdMaintenance'],
                    'column'     => 'maintenance_status',
                    'value'      => 'in_progress',
                ]);
            } elseif ($idServiceOrder !== '' && $maintenaceType === 'corrective' && $status === 'closed_so') {
                $this->generalModel->updateRecord([
                    'table'      => 'corrective_maintenance',
                    'primaryKey' => 'id_corrective_maintenance',
                    'id'         => $post['hddIdMaintenance'],
                    'column'     => 'maintenance_status',
                    'value'      => 'closed',
                ]);
            }

            if ($idServiceOrder === '') {
                $this->generalModel->saveChat([
                    'fk_id_module' => $data['idServiceOrder'],
                    'module'       => ID_MODULE_SERVICE_ORDER,
                    'message'      => 'New Service Order',
                ]);

                $vehicleInfo = $this->generalModel->get_vehicle_by(['idVehicle' => $idEquipment]);
                $userInfo    = $this->generalModel->get_user(['idUser' => $post['assign_to']]);

                $module    = base64url_encode('ID_MODULE_SERVICE_ORDER');
                $idModule  = base64url_encode($data['idServiceOrder']);
                $urlMovil  = base_url('login/index/x/' . $module . '/' . $idModule);
                $mensajeSMS = "New Service Order App - Lev West"
                    . "\nSO #: " . $data['idServiceOrder']
                    . "\nUnit #: " . $vehicleInfo[0]['unit_number']
                    . "\nVIN #: " . $vehicleInfo[0]['vin_number']
                    . "\n" . ($post['hddMaintenanceDescription'] ?? '')
                    . "\n\n" . $urlMovil;

                (new SmsService())->send('+1' . $userInfo[0]['movil'], $mensajeSMS);
            } else {
                $hddIdCanBeUsed = $post['hddIdCanBeUsed'] ?? '';
                $can_be_used    = $post['can_be_used'] ?? '';

                $arrParamVehicle = [
                    'table'      => 'param_vehicle',
                    'primaryKey' => 'id_vehicle',
                    'id'         => $idEquipment,
                    'column'     => 'so_blocked',
                ];
                if ($can_be_used == 2 && $status === 'closed_so') {
                    $arrParamVehicle['value'] = 1;
                    $this->generalModel->updateRecord($arrParamVehicle);
                } elseif ($can_be_used != $hddIdCanBeUsed) {
                    $arrParamVehicle['value'] = $can_be_used;
                    $this->generalModel->updateRecord($arrParamVehicle);
                }

                $verification = $post['hddVerificationBy'] ?? '';
                if ($maintenaceType === 'preventive' && $status === 'closed_so') {
                    $arrParamPM = [
                        'table'      => 'preventive_maintenance',
                        'primaryKey' => 'id_preventive_maintenance',
                        'id'         => $post['hddIdMaintenance'],
                    ];
                    if ($verification == 1) {
                        $arrParamPM['column'] = 'next_hours_maintenance';
                        $arrParamPM['value']  = $post['next_hours_maintenance'] ?? 0;
                    } else {
                        $arrParamPM['column'] = 'next_date_maintenance';
                        $arrParamPM['value']  = $post['next_date_maintenance'] ?? '';
                    }
                    $this->generalModel->updateRecord($arrParamPM);
                }

                if ($status === 'closed_so' && $oldStatus !== $status) {
                    $vehicleInfo = $this->generalModel->get_vehicle_by(['idVehicle' => $idEquipment]);
                    $userInfo    = $this->generalModel->get_user(['idUser' => $post['hddIdAssignedBy']]);

                    $module   = base64url_encode('ID_MODULE_SERVICE_ORDER');
                    $idModule = base64url_encode($data['idServiceOrder']);
                    $urlMovil = base_url('login/index/x/' . $module . '/' . $idModule);
                    $mensajeSMS = "Service Order App - Lev West"
                        . "\nSO #: " . $data['idServiceOrder']
                        . "\nUnit #: " . $vehicleInfo[0]['unit_number']
                        . "\nVIN #: " . $vehicleInfo[0]['vin_number']
                        . "\n: " . ($post['hddMaintenanceDescription'] ?? '')
                        . "\nComments: " . ($post['comments'] ?? '')
                        . "\n\n" . $urlMovil;

                    (new SmsService())->send('+1' . $userInfo[0]['movil'], $mensajeSMS);

                    $this->serviceorderModel->saveTime([
                        'idServiceOrder' => $post['hddIdServiceOrder'],
                        'idTime'         => $post['hddIdTime'],
                        'timeDate'       => $post['hddTimeDate'],
                        'time'           => $post['hddTime'] ?? 0,
                    ]);
                }

                if ($oldStatus !== $status) {
                    $this->generalModel->saveChat([
                        'fk_id_module' => $data['idServiceOrder'],
                        'module'       => ID_MODULE_SERVICE_ORDER,
                        'message'      => 'The Service Order status has been updated: ' . $oldStatus . ' ---> ' . $status . '.',
                    ]);
                }

                if ($status === 'in_progress_so' && $oldStatus !== 'in_progress_so') {
                    $inProgressSO = $this->serviceorderModel->get_service_order([
                        'idAssignTo'         => $post['hddIdAssignedTo'],
                        'diffIdServiceOrder' => $data['idServiceOrder'],
                        'status'             => $status,
                    ]);

                    if ($inProgressSO) {
                        $msj .= ' <b>Remember that you can only have one SO as In Progress.</b>';
                        $this->serviceorderModel->saveTime([
                            'idServiceOrder' => $inProgressSO[0]['id_service_order'],
                            'idTime'         => $inProgressSO[0]['id_time'],
                            'timeDate'       => $inProgressSO[0]['time_date'],
                            'time'           => $inProgressSO[0]['time'],
                        ]);
                        $this->generalModel->updateRecord([
                            'table'      => 'service_order',
                            'primaryKey' => 'id_service_order',
                            'id'         => $inProgressSO[0]['id_service_order'],
                            'column'     => 'service_status',
                            'value'      => 'on_hold',
                        ]);
                    }

                    $this->serviceorderModel->saveTime([
                        'idServiceOrder' => $post['hddIdServiceOrder'],
                        'idTime'         => $post['hddIdTime'],
                    ]);
                } elseif ($status !== 'in_progress_so' && $oldStatus === 'in_progress_so') {
                    $this->serviceorderModel->saveTime([
                        'idServiceOrder' => $post['hddIdServiceOrder'],
                        'idTime'         => $post['hddIdTime'],
                        'timeDate'       => $post['hddTimeDate'],
                        'time'           => $post['hddTime'] ?? 0,
                    ]);
                }
            }

            $data['result'] = true;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['result'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Save Preventive Maintenance
     * @since 22/5/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function save_preventive_maintenance()
    {
        $post          = $this->request->getPost();
        $idMaintenance = $post['hddIdMaintenance'] ?? '';
        $data          = ['idEquipment' => $post['hddIdEquipment'] ?? ''];
        $msj           = $idMaintenance !== '' ? 'You have updated the Preventive Maintenance!!' : 'You have added a new Preventive Maintenance!!';

        if ($this->serviceorderModel->savePreventiveMaintenance($post)) {
            $data['result'] = true;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['result'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Save Corrective Maintenance
     * @since 26/5/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function save_corrective_maintenance()
    {
        $post          = $this->request->getPost();
        $idMaintenance = $post['hddIdMaintenance'] ?? '';
        $data          = ['idEquipment' => $post['hddIdEquipment'] ?? ''];
        $msj           = $idMaintenance !== '' ? 'You have updated the Corrective Maintenance!!' : 'You have added a new Corrective Maintenance!!';

        if ($this->serviceorderModel->saveCorrectiveMaintenance($post)) {
            $data['result'] = true;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['result'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Equipment List
     * @since 20/5/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function equipmentList()
    {
        $data['subTitle']    = $this->request->getPost('headerInspectionType');
        $data['vehicleInfo'] = $this->generalModel->get_vehicle_by([
            'vehicleType'  => $this->request->getPost('inspectionType'),
            'vinNumber'    => $this->request->getPost('vinNumber'),
            'vehicleState' => 1,
        ]);

        if ($data['vehicleInfo']) {
            $body = view('App\Modules\Serviceorder\Views\equipment_list', $data);
        } else {
            $body = "<p class='text-danger'>There are no records.</p>";
        }

        return $this->response->setContentType('text/html')->setBody($body);
    }

    /**
     * SO List
     * @since 14/6/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function serviceOrderList()
    {
        $status = $this->request->getPost('status');
        if ($status !== 'x') {
            $arrParam = ['status' => $status];
        } else {
            $arrParam = ['idServiceOrder' => $this->request->getPost('soNumber')];
        }

        $data['information'] = $this->serviceorderModel->get_service_order($arrParam);

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Serviceorder\Views\service_order_list', $data));
    }

    /**
     * Equipment Detail
     * @since 21/5/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function equipmentDetail()
    {
        $arrParam             = ['idVehicle' => $this->request->getPost('equipmentId')];
        $data['vehicleInfo']  = $this->generalModel->get_vehicle_by($arrParam);
        $data['tabview']      = $this->request->getPost('tabview');

        if ($data['tabview'] === 'tab_service_order') {
            $data['information'] = $this->serviceorderModel->get_service_order($arrParam);
        } elseif ($data['tabview'] === 'tab_corrective_maintenance') {
            $data['infoCorrectiveMaintenance'] = $this->serviceorderModel->get_corrective_maintenance($arrParam);
        } elseif ($data['tabview'] === 'tab_preventive_maintenance') {
            $data['infoPreventiveMaintenance'] = $this->serviceorderModel->get_preventive_maintenance($arrParam);
        } elseif ($data['tabview'] === 'tab_inspections') {
            $data['infoInspections'] = false;
            if ($data['vehicleInfo'][0]['table_inspection']) {
                $data['infoInspections'] = $this->generalModel->get_vehicle_oil_change($data['vehicleInfo']);
            }
        } elseif ($data['tabview'] === 'tab_service_order_detail') {
            $arrParam['idServiceOrder'] = $this->request->getPost('serviceOrderId');
            $data['information']        = $this->serviceorderModel->get_service_order($arrParam);
            $data['chatInfo']           = $this->generalModel->get_chat_info([
                'idModule' => $this->request->getPost('serviceOrderId'),
                'module'   => ID_MODULE_SERVICE_ORDER,
            ]);
            $data['infoParts'] = $this->serviceorderModel->get_parts([
                'idServiceOrder' => $this->request->getPost('serviceOrderId'),
            ]);
        } elseif ($data['tabview'] === 'tab_parts_by_store') {
            $data['infoPartsByStore'] = $this->serviceorderModel->get_parts_store_by_equipment($arrParam);
        }

        if ($data['vehicleInfo']) {
            $body = view('App\Modules\Serviceorder\Views\equipment_detail', $data);
        } else {
            $body = "<p class='text-danger'>There are no records.</p>";
        }

        return $this->response->setContentType('text/html')->setBody($body);
    }

    /**
     * Save chat
     * @since 29/5/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function save_chat()
    {
        $post           = $this->request->getPost();
        $idServiceOrder = $post['hddId'] ?? '';
        $idAssignedTo   = $post['hddIdAssignedTo'] ?? '';
        $idAssignedBy   = $post['hddIdAssignedBy'] ?? '';

        $data = [
            'idEquipment' => $post['hddIdEquipment'] ?? '',
            'view'        => $post['hddView'] ?? '',
            'idModule'    => $idServiceOrder,
        ];

        if ($this->generalModel->saveChat([
            'fk_id_module' => $idServiceOrder,
            'module'       => ID_MODULE_SERVICE_ORDER,
            'message'      => esc($post['message'] ?? ''),
        ])) {
            $idUser      = session()->get('id');
            $vehicleInfo = $this->generalModel->get_vehicle_by(['idVehicle' => $data['idEquipment']]);
            $idUserTo    = $idUser == $idAssignedTo ? $idAssignedBy : $idAssignedTo;
            $userInfo    = $this->generalModel->get_user(['idUser' => $idUserTo]);

            $module   = base64url_encode('ID_MODULE_SERVICE_ORDER');
            $idModule = base64url_encode($idServiceOrder);
            $urlMovil = base_url('login/index/x/' . $module . '/' . $idModule);
            $mensajeSMS = "Service Order App - Lev West"
                . "\nSO #: " . $idServiceOrder
                . "\nUnit #: " . $vehicleInfo[0]['unit_number']
                . "\nVIN #: " . $vehicleInfo[0]['vin_number']
                . "\n" . ($post['hddMaintenanceDescription'] ?? '')
                . "\nMessage: " . esc($post['message'] ?? '')
                . "\n\n" . $urlMovil;

            (new SmsService())->send('+1' . $userInfo[0]['movil'], $mensajeSMS);

            $data['result'] = true;
            session()->setFlashdata('retornoExito', 'You have added a message');
        } else {
            $data['result'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Cargo modal - service order Parts
     * @since 30/5/2023
     * @review 14/05/2026 - new CI4 version
     */
    public function cargarModalParts()
    {
        $data['information'] = false;
        $data['idPart']      = $this->request->getPost('idPart');
        $data['idServiceOrder']      = $this->request->getPost('idServiceOrder');
        $data['idEquipment']      = $this->request->getPost('idEquipment');

        if ($data['idPart'] !== 'x') {
            $data['information'] = $this->serviceorderModel->get_parts(['idPart' => $data['idPart']]);
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Serviceorder\Views\parts_modal', $data));
    }

    /**
     * Save Parts
     * @since 30/5/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function save_parts()
    {
        $post    = $this->request->getPost();
        $idParts = $post['hddIdPart'] ?? '';
        $msj     = $idParts !== '' ? 'You have updated the information!!' : 'You have added a new record!!';

        $data = [
            'idServiceOrder' => $post['hddIdServiceOrder'] ?? '',
            'idEquipment'    => $post['hddIdEquipment'] ?? '',
        ];

        if ($this->serviceorderModel->saveParts($post)) {
            $data['result'] = true;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['result'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Maintenance Check
     * CRON: Time: Every Day at 12am
     * @review 13/05/2026 - new CI4 version
     */
    public function maintenance_check()
    {
        $fecha       = date('Y-m-d');
        $filtroFecha = strtotime('+7 day', strtotime($fecha));

        $infoMaintenance = $this->serviceorderModel->get_preventive_maintenance(['maintenanceStatus' => 1]);

        $this->serviceorderModel->delete_maintenance_check();

        foreach ($infoMaintenance as $lista) {
            $diferencia = $lista['next_hours_maintenance'] - $lista['hours'];

            if ($lista['fk_id_maintenance_type'] == 8 || $lista['fk_id_maintenance_type'] == 9) {
                $diferencia = $lista['next_hours_maintenance'] - $lista['hours_2'];
            } elseif ($lista['fk_id_maintenance_type'] == 10) {
                $diferencia = $lista['next_hours_maintenance'] - $lista['hours_3'];
            }

            $nextDateMaintenance = strtotime($lista['next_date_maintenance']);

            if (
                ($lista['verification_by'] == 1 && $lista['next_hours_maintenance'] != 0 && $diferencia <= 50)
                || ($lista['verification_by'] == 2 && $lista['next_date_maintenance'] != '' && $lista['next_date_maintenance'] != '0000-00-00' && $nextDateMaintenance <= $filtroFecha)
            ) {
                $this->serviceorderModel->add_maintenance_check((int) $lista['id_preventive_maintenance']);
            }
        }

        if ($this->generalModel->get_maintenance_check()) {
            $subject = 'Preventive Maintenance List';
            $module  = base64url_encode('DASHBOARD_MAINTENANCE_LIST');
            $emailBody = 'There is an urgent need to carry out <b>Preventive Maintenance</b> as soon as possible.'
                . '<br>Follow the link to see the list. '
                . '<a href=\'' . base_url('login/index/x/' . $module . '/x') . '\'>Click here</a>';
            $smsMessage = $subject . ' App - Lev West'
                . "\nThere is an urgent need to carry out Preventive Maintenance as soon as possible."
                . "\nFollow the link to see the list."
                . "\n\n" . base_url('login/index/x/' . $module . '/x');

            $configuracionAlertas = $this->generalModel->get_notifications_access(['idNotification' => ID_NOTIFICATION_MAINTENANCE]);
            if ($configuracionAlertas) {
                send_notification($configuracionAlertas, $subject, $emailBody, $smsMessage);
            }
        }

        return true;
    }

    /**
     * Generate Service Order Report in PDF
     * @param int $idServiceOrder
     * @since 20/07/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function generateSOReportPDF($idServiceOrder)
    {
        $data['info']      = $this->serviceorderModel->get_service_order(['idServiceOrder' => $idServiceOrder]);
        $data['chatInfo']  = $this->generalModel->get_chat_info([
            'idModule' => $idServiceOrder,
            'module'   => ID_MODULE_SERVICE_ORDER,
        ]);
        $data['infoParts'] = $this->serviceorderModel->get_parts(['idServiceOrder' => $idServiceOrder]);

        $builder = new PdfBuilder();
        $pdf     = $builder->create('SERVICE ORDER');

        $html = view('App\Modules\Serviceorder\Views\service_order_report', $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('service_order_report' . $idServiceOrder . '.pdf', 'I'));
    }

    /**
     * Expenses
     * @since 21/7/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function expenses()
    {
        $data['information'] = $this->serviceorderModel->get_expenses();

        if ($data['information']) {
            $body = view('App\Modules\Serviceorder\Views\expenses', $data);
        } else {
            $body = "<p class='text-danger'>There are no records.</p>";
        }

        return $this->response->setContentType('text/html')->setBody($body);
    }

    /**
     * Expenses by Equipment
     * @since 21/7/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function expensesByEquipment()
    {
        $idVehicle           = $this->request->getPost('equipmentId');
        $data['information'] = $this->serviceorderModel->get_expenses_by_equipment(['idVehicle' => $idVehicle]);
        $data['vehicleInfo'] = $this->generalModel->get_vehicle_by(['idVehicle' => $idVehicle]);

        if ($data['information']) {
            $body = view('App\Modules\Serviceorder\Views\expenses_by_equipment', $data);
        } else {
            $body = "<p class='text-danger'>There are no records.</p>";
        }

        return $this->response->setContentType('text/html')->setBody($body);
    }

    /**
     * Parts List
     * @since 30/10/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function parts_by_store()
    {
        $data['info'] = $this->serviceorderModel->get_parts_by_store([]);
        return $this->render('App\Modules\Serviceorder\Views\parts_list', $data);
    }

    /**
     * Cargo modal - part form
     * @since 30/10/2023
     * @review 14/05/2026 - new CI4 version
     */
    public function cargarModalShopParts()
    {
        $data['information']      = false;
        $data['informationParts'] = false;
        $data['idPartShop']       = $this->request->getPost('idPartShop');

        $data['equipmentType'] = $this->generalModel->equipmentByTypeList();
        $data['shopList']      = $this->generalModel->get_basic_search([
            'table' => 'param_shop',
            'order' => 'shop_name',
            'id'    => 'x',
        ]);

        if ($data['idPartShop'] !== 'x') {
            $data['information']      = $this->serviceorderModel->get_parts_by_store(['idPartShop' => $data['idPartShop']]);
            $data['informationParts'] = $this->serviceorderModel->get_parts_equipment(['idEquipmentPart' => $data['idPartShop']]);
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Serviceorder\Views\parts_shop_modal', $data));
    }

    /**
     * Save Shop Parts
     * @since 30/10/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function save_shop_parts()
    {
        $post       = $this->request->getPost();
        $idPartShop = $post['hddId'] ?? '';
        $msj        = $idPartShop !== '' ? 'You have updated a Part!!' : 'You have added a Part!!';

        if ($idPartShop = $this->serviceorderModel->saveShopParts($post)) {
            $equipment = $post['equipment'] ?? [];
            $this->serviceorderModel->add_equipment_shop_parts((int) $idPartShop, $equipment);
            $data['result'] = true;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['result'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Equipment list for Parts Shop
     * @since 24/6/2023
     * @author BMOTTAG
     * @review 14/05/2026 - new CI4 version
     */
    public function equipmentListForPartsShop()
    {
        $lista = $this->generalModel->get_vehicle_by([
            'vehicleType'  => $this->request->getPost('type'),
            'vehicleState' => 1,
        ]);

        $arrayInformationAttachments = false;
        $idEquipmentPart             = $this->request->getPost('idEquipmentPart');
        if ($idEquipmentPart !== '') {
            $arrayInformationAttachments = $this->serviceorderModel->get_parts_equipment([
                'idEquipmentPart' => $idEquipmentPart,
                'relation'        => true,
            ]);
        }

        $html = "<option value=''>Select...</option>";
        if ($lista) {
            foreach ($lista as $fila) {
                $s = '';
                if ($arrayInformationAttachments) {
                    $found = false;
                    foreach ($arrayInformationAttachments as $idVehicle) {
                        if (in_array($fila['id_vehicle'], $idVehicle)) {
                            $found = true;
                            break;
                        }
                    }
                    $s = $found ? 'selected' : '';
                }
                $html .= "<option value='" . esc($fila['id_vehicle']) . "'" . $s . ">"
                    . esc($fila['unit_number']) . " -----> " . esc($fila['description']) . "</option>";
            }
        }

        return $this->response->setContentType('text/html')->setBody($html);
    }
}
