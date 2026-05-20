<?php
namespace App\Modules\Programming\Controllers;

use App\Controllers\BaseController;
use App\Modules\Programming\Models\ProgrammingModel;
use App\Models\GeneralModel;
use App\Libraries\SmsService;

class Programming extends BaseController
{
    protected $programmingModel;
    protected $generalModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->programmingModel = new ProgrammingModel();
        $this->generalModel     = new GeneralModel();
    }

    /**
     * Listado de programaciones
     * @since 15/1/2019
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function index($idJob, $idProgramming = 'x')
    {
        $data = [
            'information'           => false,
            'informationWorker'     => false,
            'idProgramming'         => $idProgramming,
            'workersList'           => false,
            'dayoffList'            => false,
            'programmingMaterials'  => false,
            'programmingOccasional' => false,
        ];

        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);

        if ($idProgramming != 'x') {
            $data['employeeTypeList'] = $this->generalModel->get_basic_search([
                'table' => 'param_employee_type',
                'order' => 'employee_type',
                'id'    => 'x',
            ]);

            $arrParam                  = ['idProgramming' => $idProgramming];
            $data['information']       = $this->generalModel->get_programming($arrParam);
            $data['informationWorker'] = $this->generalModel->get_programming_workers($arrParam);

            $equipmentSelected         = $this->programmingModel->get_vehicles_selected($data['information']);
            $data['informationVehicles'] = $this->programmingModel->get_vehicles_inspection(['vehicleToExclude' => $equipmentSelected]);

            $data['programmingOccasional'] = $this->programmingModel->get_programming_occasional($arrParam);
            $data['horas']                 = $this->generalModel->get_horas();
            $data['workersList']           = $this->generalModel->get_user(['state' => 1]);
            $data['dayoffList']            = $this->generalModel->get_day_off_planning(['forPlanning' => true]);

            if ($data['informationWorker']) {

                foreach ($data['informationWorker'] as &$worker) {

                    if (!empty($worker['fk_id_machine'])) {

                        $ids = json_decode($worker['fk_id_machine'], true);
                        $ids = is_array($ids) ? implode(',', $ids) : $ids;

                        $arrParam = ["idValues" => $ids];

                        $info = $this->generalModel->get_vehicle_info_for_planning($arrParam);

                        $worker['informationEquipments'] = $info['unit_description'] ?? '';
                    } else {
                        $worker['informationEquipments'] = '';
                    }
                }
                unset($worker);

                $data['memo']          = $this->verificacion($idProgramming, $data['information'][0]['date_programming']);
                $data['memo_flha']     = $this->verificacion_flha($idProgramming, $data['information'][0]['date_programming']);
                $data['memo_tool_box'] = $this->verificacion_tool_box($idProgramming, $data['information'][0]['date_programming']);
            }

            $jobPlanningData = $this->generalModel->get_job(['idJob' => $data['information'][0]['fk_id_job']]);
            $data['job_planning'] = $jobPlanningData[0]['planning_message'];

            $data['programmingMaterials'] = $this->programmingModel->get_programming_materials(["idProgramming" => $idProgramming]);
        } else {
            $data['information'] = $this->generalModel->get_programming(['jobId' => $idJob, 'estado' => 'ACTIVAS']);
        }

        return $this->render('App\Modules\Programming\Views\programming_list', $data);
    }

    /**
     * Form programming
     * @since 15/1/2019
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function add_programming($idJob, $idProgramming = 'x')
    {
        $data['information'] = false;

        $data['jobs'] = $this->generalModel->get_job(['state' => 1]);

        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);

        if ($idProgramming != 'x') {
            $data['information'] = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        }

        return $this->render('App\Modules\Programming\Views\form_programming', $data);
    }

    /**
     * Guardar programacion
     * @since 15/1/2019
     * @review 04/05/2026 - new CI4 version
     */
    public function save_programming()
    {
        $idProgramming  = $this->request->getPost('hddId');
        $idJob          = $this->request->getPost('jobName');
        $date           = $this->request->getPost('date')
            ? $this->request->getPost('date')
            : date('Y-m-d', strtotime($this->request->getPost('from')));

        $msj            = 'You have added a new Planning. Do not forget to asign the workers.';
        $result_project = false;

        if ($idProgramming != '') {
            $msj = 'You have updated a Planning!!';
        } else {
            $result_project = $this->programmingModel->verifyProject(['idJob' => $idJob, 'date' => $date]);
        }

        if ($result_project) {
            $data = ['result' => 'error', 'mensaje' => 'Error. This project is already scheduled for that date.'];
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> This project is already scheduled for that date.');
        } else {
            if ($idProgramming = $this->programmingModel->saveProgramming()) {
                $flagDate = $this->request->getPost('flag_date');
                $parentId = $this->request->getPost('hddIdParent');

                if ($flagDate == 2 && $parentId == '') {
                    $this->generalModel->deleteRecord([
                        'table'      => 'programming',
                        'primaryKey' => 'parent_id',
                        'id'         => $idProgramming,
                    ]);

                    $date_from          = strtotime(formatear_fecha($this->request->getPost('from')));
                    $date_to            = strtotime(formatear_fecha($this->request->getPost('to')));
                    $diferencia_en_dias = floor(($date_to - $date_from) / (60 * 60 * 24));

                    for ($i = 1; $i <= $diferencia_en_dias; $i++) {
                        $applyFor = $this->request->getPost('apply_for');
                        $nextDate = date('Y-m-d', strtotime('+' . $i . ' day', strtotime(formatear_fecha($this->request->getPost('from')))));
                        $weekDay  = (int) date('N', strtotime($nextDate));

                        if ($applyFor == 1) {
                            $this->programmingModel->savePeriodProgramming($nextDate, $idProgramming);
                        } elseif ($applyFor == 2 && $weekDay >= 1 && $weekDay <= 5) {
                            $this->programmingModel->savePeriodProgramming($nextDate, $idProgramming);
                        } elseif ($applyFor == 3 && $weekDay >= 6 && $weekDay <= 7) {
                            $this->programmingModel->savePeriodProgramming($nextDate, $idProgramming);
                        }
                    }
                }

                $data = ['status' => 'success'];
                $this->session->setFlashdata('retornoExito', $msj);
            } else {
                $data = ['status' => 'error'];
                $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Contactarse con el Administrador.');
            }
        }

        $data['path'] = $idJob . '/' . $idProgramming;
        return $this->response->setJSON($data);
    }

    /**
     * Form Add Workers
     * @since 16/1/2019
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function add_programming_workers($idProgramming)
    {
        if (empty($idProgramming)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('You are in the wrong place.');
        }

        $data['workersList']  = $this->generalModel->get_user(['state' => 1]);
        $data['idProgramming'] = $idProgramming;

        return $this->render('App\Modules\Programming\Views\form_add_workers', $data);
    }

    /**
     * Save workers
     * @since 16/1/2019
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function save_programming_workers()
    {
        $idProgramming   = $this->request->getPost('hddId');
        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        $data            = ['path' => $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming];

        if ($this->programmingModel->addProgrammingWorker()) {
            $data['status']  = 'success';
            $this->update_state($idProgramming);
            $this->session->setFlashdata('retornoExito', 'You have added Workers to the Planning, if they are going to use a machine remember to assign it to the worker.');
        } else {
            $data['status'] = 'error';
            $data['mensaje'] = 'Error al guardar. Intente nuevamente o actualice la página.';
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Delete programming
     * @since 8/7/2018
     * @review 04/05/2026 - new CI4 version
     */
    public function delete_programming()
    {
        $idProgramming   = $this->request->getPost('identificador');
        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        $data            = ['path' => $infoProgramming[0]['fk_id_job']];

        if ($this->programmingModel->deleteProgramming()) {
            $data['status'] = 'success';
            $this->session->setFlashdata('retornoExito', 'You have delete the record.');
        } else {
            $data['status'] = 'error';
            $data['mensaje'] = 'Error!!! Contactarse con el Administrador.';
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Update datos trabajadores
     * @since 16/1/2019
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function update_worker()
    {
        $idProgramming       = $this->request->getPost('hddIdProgramming');
        $idProgrammingWorker = $this->request->getPost('hddId');

        $infoProgramming  = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        $fechaProgramming  = $infoProgramming[0]['date_programming'];
        $idMachine         = $this->request->getPost('machine');

        $inspecciones = false;
        if ($idMachine != '') {
            $inspecciones = $this->generalModel->get_programming_machine_vs_date_programming([
                'idProgrammingWorker' => $idProgrammingWorker,
                'fechaProgramming'    => $fechaProgramming,
                'maquina'             => $idMachine,
            ]);
        }

        if ($inspecciones) {
            $this->session->setFlashdata('retornoError', 'This Equipment has already been used');
        } else {
            if ($this->programmingModel->saveWorker()) {
                $this->session->setFlashdata('retornoExito', 'You have updated the record!!');
            } else {
                $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        }

        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        return redirect()->to(base_url('programming/index/' . $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming));
    }

    /**
     * Generate child workers
     * @since 21/10/2023
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function generate_child_workers()
    {
        $idProgramming = $this->request->getPost('hddIdProgramming');
        $childList     = $this->generalModel->get_programming(['idParent' => $idProgramming]);

        if (!$childList) {
            $this->session->setFlashdata('retornoError', 'This Planning does not have any child.');
        } else {
            $informationWorker = $this->generalModel->get_programming_workers(['idProgramming' => $idProgramming]);

            foreach ($childList as $child) {
                $this->delete_child_workers($child['id_programming']);
                $this->programmingModel->saveChildWorkers($child['id_programming'], $informationWorker);
                $this->update_state($child['id_programming']);
            }

            $this->session->setFlashdata('retornoExito', 'You have updated the record!!');
        }

        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        return redirect()->to(base_url('programming/index/' . $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming));
    }

    /**
     * Delete worker
     * @review 04/05/2026 - new CI4 version
     */
    public function deleteWorker($idProgramming, $idWorker)
    {
        if (empty($idProgramming) || empty($idWorker)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('You are in the wrong place.');
        }

        if ($this->generalModel->deleteRecord([
            'table'      => 'programming_worker',
            'primaryKey' => 'id_programming_worker',
            'id'         => $idWorker,
        ])) {
            $this->update_state($idProgramming);

            $woResult = $this->generalModel->get_basic_search([
                'table'  => 'workorder_personal',
                'order'  => 'fk_id_programming_worker',
                'column' => 'fk_id_programming_worker',
                'id'     => $idWorker,
            ]);
            if ($woResult) {
                $this->generalModel->deleteRecord([
                    'table'      => 'workorder_personal',
                    'primaryKey' => 'fk_id_programming_worker',
                    'id'         => $idWorker,
                ]);
            }

            $this->session->setFlashdata('retornoExito', 'You have deleted one worker.');
        } else {
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        return redirect()->to(base_url('programming/index/' . $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming));
    }

    /**
     * Envio de mensaje a trabajadores vía SMS (Twilio)
     * @since 16/1/2019
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function send($idProgramming, $flashPlanning = false)
    {
        $arrParam          = ['idProgramming' => $idProgramming];
        $information       = $this->generalModel->get_programming($arrParam);
        $idWorkorder       = $information[0]['fk_id_workorder'] ?: $this->create_work_order($information);
        $informationWorker = $this->generalModel->get_programming_workers($arrParam);

        $msgHeader  = date('F j, Y', strtotime($information[0]['date_programming']));
        $msgHeader .= "\n" . $information[0]['job_description'];
        $msgHeader .= "\n" . $information[0]['observation'];
        $msgHeader .= "\n\nPlease confirm by replying '1' to this text message!\n";

        if ($informationWorker) {
            $smsService       = new \App\Libraries\SmsService();
            $excluded_numbers = ['686289126', '5068494482', '5068393681', '5870000000'];

            foreach ($informationWorker as $info) {
                $informationEquipments = [];
                if ($info['fk_id_machine'] != null) {
                    $id_values             = implode(',', json_decode($info['fk_id_machine'], true));
                    $informationEquipments = $this->generalModel->get_vehicle_info_for_planning([
                        'idValues'        => $id_values,
                        'forTextMessague' => true,
                    ]);
                }

                $siteMap  = [1 => 'At the yard - ', 2 => 'At the site - ', 3 => 'At Terminal - ', 4 => 'On-line training - ', 5 => 'At training facility - ', 6 => "At client's office - "];
                $mensaje  = $msgHeader . "\n";
                $mensaje .= $siteMap[$info['site']] ?? 'At the yard - ';
                $mensaje .= $info['hora'];
                $mensaje .= "\n" . $info['name'];
                $mensaje .= $info['description'] ? "\n" . $info['description'] : '';
                $mensaje .= $info['fk_id_machine'] != null ? "\nInspect following unit(s):\n" . ($informationEquipments['unit_description'] ?? '') : '';

                if ($info['safety'] == 1) {
                    $mensaje .= "\nFLHA has being assigned to you.";
                } elseif ($info['safety'] == 2) {
                    $mensaje .= "\nIHSR has being assigned to you.";
                } elseif ($info['safety'] == 3) {
                    $mensaje .= "\nJSO has being assigned to you.";
                }

                if ($info['creat_wo'] == 1) {
                    $mensaje .= "\nYou are in charge of the W.O. #" . $idWorkorder;
                }

                if (!in_array($info['movil'], $excluded_numbers)) {
                    try {
                        $message = $smsService->send('+1' . $info['movil'], $mensaje);
                        $this->programmingModel->updateSMSWorkerStatus($info['id_programming_worker'], $message->status, $message->sid);
                    } catch (\Exception $e) {
                        log_message('error', 'SMS send failed for worker ' . $info['id_programming_worker'] . ': ' . $e->getMessage());
                    }
                }
            }
        }

        if ($flashPlanning) {
            return true;
        }

        return $this->render('template/answer', [
            'linkBack' => 'programming/index/' . $information[0]['fk_id_job'] . '/' . $idProgramming,
            'titulo'   => "<i class='fa fa-list'></i> PLANNING LIST",
            'clase'    => 'alert-info',
            'msj'      => 'The message has been sent to the workers.',
        ]);
    }

    /**
     * Actualiza estado de la programacion (2 = completa, 1 = incompleta)
     * @since 17/1/2019
     * @review 05/05/2026 - new CI4 version
     */
    protected function update_state($idProgramming)
    {
        $state = $this->programmingModel->countWorkers($idProgramming) >= 1 ? 2 : 1;
        return $this->generalModel->updateRecord([
            'table'      => 'programming',
            'primaryKey' => 'id_programming',
            'id'         => $idProgramming,
            'column'     => 'state',
            'value'      => $state,
        ]);
    }

    /**
     * Actualiza estado de los mensajes SMS del trabajador
     * @since 17/1/2019
     * @review 04/05/2026 - new CI4 version
     */
    protected function update_sms_worker($idProgrammingWorker, $columnaTipoSMS, $nuevoEstadoSMS)
    {
        return $this->generalModel->updateRecord([
            'table'      => 'programming_worker',
            'primaryKey' => 'id_programming_worker',
            'id'         => $idProgrammingWorker,
            'column'     => $columnaTipoSMS,
            'value'      => $nuevoEstadoSMS,
        ]);
    }

    /**
     * CRON - Verificar inspecciones de maquinas pendientes
     * @since 17/1/2019
     * @review 04/05/2026 - new CI4 version
     */
    public function verificacion($idProgramming = 'x', $fecha = 'x')
    {
        $bandera = false;

        if ($fecha != 'x') {
            $fechaBusqueda = $fecha;
            $arrParam      = ['idProgramming' => $idProgramming, 'fecha' => $fechaBusqueda];
        } else {
            $fechaBusqueda = date('Y-m-d');
            $bandera       = true;
            $arrParam      = ['fecha' => $fechaBusqueda];
        }

        $information = $this->generalModel->get_programming($arrParam);
        $i           = 0;
        $nombres     = '';

        if ($information) {
            if ($bandera) {
                $parametric = $this->generalModel->get_basic_search([
                    'table' => 'parametric',
                    'order' => 'id_parametric',
                    'id'    => 'x',
                ]);
                $phoneAdmin = '+1' . $parametric[6]['value'];
            }

            foreach ($information as $lista) {
                $informationWorker = $this->generalModel->get_programming_workers([
                    'idProgramming' => $lista['id_programming'],
                    'withEquipment' => true,
                ]);

                if ($informationWorker) {
                    foreach ($informationWorker as $dato) {
                        if (!empty(json_decode($dato['fk_id_machine']))) {
                            $inspecciones = $this->generalModel->get_missing_programming_inspecciones([
                                'fecha'   => $fechaBusqueda,
                                'maquina' => $dato['fk_id_machine'],
                            ]);

                            if ($inspecciones) {
                                $inspeccionesValues = $this->generalModel->get_vehicle_info_for_planning([
                                    'idValues' => implode(',', $inspecciones),
                                ]);

                                if ($inspeccionesValues) {
                                    $i++;
                                    $nombres .= '<br>' . $dato['name'] . ' - ' . $inspeccionesValues['unit_description'];

                                    if ($bandera && $dato['sms_inspection'] != 2) {
                                        $fechaProgramacion = $fechaBusqueda . ' ' . $dato['formato_24'];
                                        $datetime1         = date_create($fechaProgramacion);
                                        $ajuste            = strtotime('-2 hour', strtotime(date('Y-m-d G:i')));
                                        $datetime2         = date_create(date('Y-m-d G:i', $ajuste));

                                        if ($datetime1 < $datetime2) {
                                            if ($dato['sms_inspection'] == 0) {
                                                $this->update_sms_worker($dato['id_programming_worker'], 'sms_inspection', 1);
                                                $mensaje  = "INSPECTION APP-VCI";
                                                $mensaje .= "\nDo not forget to do the Inspection:";
                                                $mensaje .= "\n" . $inspeccionesValues['unit_description'];
                                                try {
                                                    (new SmsService())->send('+1' . $dato['movil'], $mensaje);
                                                } catch (\Exception $e) {
                                                    log_message('error', 'SMS Inspection send failed: ' . $e->getMessage());
                                                }
                                            } elseif ($dato['sms_inspection'] == 1) {
                                                $this->update_sms_worker($dato['id_programming_worker'], 'sms_inspection', 2);
                                                $mensaje  = "INSPECTION APP-VCI";
                                                $mensaje .= "\nThe user has not done the Inspection:";
                                                $mensaje .= "\n" . $dato['name'] . ' - ' . $inspeccionesValues['unit_description'];
                                                try {
                                                    (new SmsService())->send($phoneAdmin, $mensaje);
                                                } catch (\Exception $e) {
                                                    log_message('error', 'SMS Inspection admin send failed: ' . $e->getMessage());
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $i != 0 ? 'INSPECTIONS missing:' . $nombres : 'There is no INSPECTIONS missing.';
    }

    /**
     * Save one worker
     * @review 05/05/2026 - new CI4 version
     */
    public function save_One_Worker_programming()
    {
        $idProgramming = $this->request->getPost('hddId');

        if ($this->programmingModel->saveOneWorkerProgramming()) {
            $this->session->setFlashdata('retornoExito', 'You have added one Worker.');
        } else {
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        return redirect()->to(base_url('programming/index/' . $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming));
    }

    /**
     * CRON - Verificar FLHA y JSO pendientes
     * @since 17/1/2019
     * @review 04/05/2026 - new CI4 version
     */
    public function verificacion_flha($idProgramming = 'x', $fecha = 'x')
    {
        $bandera = false;

        if ($fecha != 'x') {
            $fechaBusqueda = $fecha;
            $arrParam      = ['idProgramming' => $idProgramming, 'fecha' => $fechaBusqueda];
        } else {
            $fechaBusqueda = date('Y-m-d');
            $bandera       = true;
            $arrParam      = ['fecha' => $fechaBusqueda];
        }

        $information = $this->generalModel->get_programming($arrParam);
        $i           = 0;
        $j           = 0;
        $nombres     = '';
        $nombresJSO  = '';

        if ($information) {
            if ($bandera) {
                $parametric = $this->generalModel->get_basic_search([
                    'table' => 'parametric',
                    'order' => 'id_parametric',
                    'id'    => 'x',
                ]);
                $phoneAdmin = '+1' . $parametric[6]['value'];
            }

            foreach ($information as $lista) {
                $informationWorkerFLHA = $this->generalModel->get_programming_workers([
                    'idProgramming' => $lista['id_programming'],
                    'safety'        => 1,
                ]);

                if ($informationWorkerFLHA) {
                    foreach ($informationWorkerFLHA as $dato) {
                        $inspecciones = $this->generalModel->get_safety([
                            'fecha' => $fechaBusqueda,
                            'idJob' => $lista['fk_id_job'],
                            'limit' => 30,
                        ]);
                        if (!$inspecciones) {
                            $i++;
                            $nombres .= '<br>' . $dato['name'] . ' - Missing FLHA';

                            if ($bandera && $dato['sms_safety'] != 2) {
                                $fechaProgramacion = $fechaBusqueda . ' ' . $dato['formato_24'];
                                $datetime1         = date_create($fechaProgramacion);
                                $ajuste            = strtotime('-2 hour', strtotime(date('Y-m-d G:i')));
                                $datetime2         = date_create(date('Y-m-d G:i', $ajuste));

                                if ($datetime1 < $datetime2) {
                                    if ($dato['sms_safety'] == 0) {
                                        $this->update_sms_worker($dato['id_programming_worker'], 'sms_safety', 1);
                                        $mensaje  = "FLHA APP-VCI";
                                        $mensaje .= "\nDo not forget to do the FLHA:";
                                        $mensaje .= "\n" . $lista['job_description'];
                                        try {
                                            (new SmsService())->send('+1' . $dato['movil'], $mensaje);
                                        } catch (\Exception $e) {
                                            log_message('error', 'SMS FLHA send failed: ' . $e->getMessage());
                                        }
                                    } elseif ($dato['sms_safety'] == 1) {
                                        $this->update_sms_worker($dato['id_programming_worker'], 'sms_safety', 2);
                                        $mensaje  = "FLHA APP-VCI";
                                        $mensaje .= "\nThe user has not done the FLHA:";
                                        $mensaje .= "\n" . $dato['name'];
                                        try {
                                            (new SmsService())->send($phoneAdmin, $mensaje);
                                        } catch (\Exception $e) {
                                            log_message('error', 'SMS FLHA admin send failed: ' . $e->getMessage());
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $informationWorkerJSO = $this->generalModel->get_programming_workers([
                    'idProgramming' => $lista['id_programming'],
                    'safety'        => 3,
                ]);

                if ($informationWorkerJSO) {
                    foreach ($informationWorkerJSO as $dato) {
                        $inspecciones = $this->generalModel->get_safety([
                            'fecha' => $fechaBusqueda,
                            'idJob' => $lista['fk_id_job'],
                            'limit' => 30,
                        ]);
                        if (!$inspecciones) {
                            $j++;
                            $nombresJSO .= '<br>' . $dato['name'] . ' - Missing JSO';

                            if ($bandera && $dato['sms_jso'] != 2) {
                                $fechaProgramacion = $fechaBusqueda . ' ' . $dato['formato_24'];
                                $datetime1         = date_create($fechaProgramacion);
                                $ajuste            = strtotime('-2 hour', strtotime(date('Y-m-d G:i')));
                                $datetime2         = date_create(date('Y-m-d G:i', $ajuste));

                                if ($datetime1 < $datetime2) {
                                    if ($dato['sms_jso'] == 0) {
                                        $this->update_sms_worker($dato['id_programming_worker'], 'sms_jso', 1);
                                        $mensaje  = "JSO APP-VCI";
                                        $mensaje .= "\nDo not forget to do the JSO:";
                                        $mensaje .= "\n" . $lista['job_description'];
                                        try {
                                            (new SmsService())->send('+1' . $dato['movil'], $mensaje);
                                        } catch (\Exception $e) {
                                            log_message('error', 'SMS JSO send failed: ' . $e->getMessage());
                                        }
                                    } elseif ($dato['sms_jso'] == 1) {
                                        $this->update_sms_worker($dato['id_programming_worker'], 'sms_jso', 2);
                                        $mensaje  = "JSO APP-VCI";
                                        $mensaje .= "\nThe user has not done the JSO:";
                                        $mensaje .= "\n" . $dato['name'];
                                        try {
                                            (new SmsService())->send($phoneAdmin, $mensaje);
                                        } catch (\Exception $e) {
                                            log_message('error', 'SMS JSO admin send failed: ' . $e->getMessage());
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $memo = $i != 0 ? 'Missing FLHA:' . $nombres : 'There is no FLHA missing';
        if ($j != 0) {
            $memo .= ', Missing JSO:' . $nombresJSO;
        }
        return $memo;
    }

    /**
     * CRON - Verificar TOOL BOX (IHSR) pendientes
     * @since 20/1/2019
     * @review 04/05/2026 - new CI4 version
     */
    public function verificacion_tool_box($idProgramming = 'x', $fecha = 'x')
    {
        $bandera = false;

        if ($fecha != 'x') {
            $fechaBusqueda = $fecha;
            $arrParam      = ['idProgramming' => $idProgramming, 'fecha' => $fechaBusqueda];
        } else {
            $fechaBusqueda = date('Y-m-d');
            $bandera       = true;
            $arrParam      = ['fecha' => $fechaBusqueda];
        }

        $information = $this->generalModel->get_programming($arrParam);
        $i           = 0;
        $nombres     = '';

        if ($information) {
            if ($bandera) {
                $parametric = $this->generalModel->get_basic_search([
                    'table' => 'parametric',
                    'order' => 'id_parametric',
                    'id'    => 'x',
                ]);
                $phoneAdmin = '+1' . $parametric[6]['value'];
            }

            foreach ($information as $lista) {
                $informationWorker = $this->generalModel->get_programming_workers([
                    'idProgramming' => $lista['id_programming'],
                    'safety'        => 2,
                ]);

                if ($informationWorker) {
                    foreach ($informationWorker as $dato) {
                        $inspecciones = $this->generalModel->get_tool_box([
                            'fecha' => $fechaBusqueda,
                            'idJob' => $lista['fk_id_job'],
                        ]);
                        if (!$inspecciones) {
                            $i++;
                            $nombres .= '<br>' . $dato['name'] . ' - Missing IHSR';

                            if ($bandera && $dato['sms_safety'] != 2) {
                                $fechaProgramacion = $fechaBusqueda . ' ' . $dato['formato_24'];
                                $datetime1         = date_create($fechaProgramacion);
                                $ajuste            = strtotime('-2 hour', strtotime(date('Y-m-d G:i')));
                                $datetime2         = date_create(date('Y-m-d G:i', $ajuste));

                                if ($datetime1 < $datetime2) {
                                    if ($dato['sms_safety'] == 0) {
                                        $this->update_sms_worker($dato['id_programming_worker'], 'sms_safety', 1);
                                        $mensaje  = "IHSR APP-VCI";
                                        $mensaje .= "\nDo not forget to do the IHSR:";
                                        $mensaje .= "\n" . $lista['job_description'];
                                        try {
                                            (new SmsService())->send('+1' . $dato['movil'], $mensaje);
                                        } catch (\Exception $e) {
                                            log_message('error', 'SMS IHSR send failed: ' . $e->getMessage());
                                        }
                                    } elseif ($dato['sms_safety'] == 1) {
                                        $this->update_sms_worker($dato['id_programming_worker'], 'sms_safety', 2);
                                        $mensaje  = "IHSR APP-VCI";
                                        $mensaje .= "\nThe user has not done the IHSR:";
                                        $mensaje .= "\n" . $dato['name'];
                                        try {
                                            (new SmsService())->send($phoneAdmin, $mensaje);
                                        } catch (\Exception $e) {
                                            log_message('error', 'SMS IHSR admin send failed: ' . $e->getMessage());
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $i != 0 ? 'Missing IHSR:' . $nombres : 'There is no IHSR missing';
    }

    /**
     * Form Flash Planning
     * @since 28/12/2022
     * @author BMOTTAG
     * @review 05/05/2026 - new CI4 version
     */
    public function flash_planning()
    {
        $data['information']       = false;
        $data['informationVehicles'] = $this->programmingModel->get_vehicles_inspection([]);

        $data['jobs'] = $this->generalModel->get_job(['state' => 1]);

        $data['workersList'] = $this->generalModel->get_user(['state' => 1]);

        return $this->render('App\Modules\Programming\Views\form_planning_flash', $data);
    }

    /**
     * Save Flash Planning
     * @since 28/12/2022
     * @review 05/05/2026 - new CI4 version
     */
    public function save_flash_planning()
    {
        $idProgramming = $this->request->getPost('hddId');
        $msj           = $idProgramming != '' ? 'You have updated a Planning!!' : 'You have added a new Planning.';

        $horas      = $this->generalModel->get_horas();
        $horaActual = date('G:i');
        $idHora     = '';

        foreach ($horas as $hora) {
            if (strtotime($hora['formato_24']) > strtotime($horaActual)) {
                $idHora = $hora['id_hora'];
                break;
            }
        }

        if ($idProgramming = $this->programmingModel->saveProgramming()) {
            if ($this->programmingModel->saveWorkerFashPlanning($idProgramming, $idHora)) {
                // SMS SENDING - REVIEW LATER
                // $this->send($idProgramming, true);
                // $msj .= ' Message is sent to the worker.';
            }
            $this->update_state($idProgramming);
            $data['status'] = 'success';
            $this->session->setFlashdata('retornoExito', $msj);
        } else {
            $data['status'] = 'error';
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Contactarse con el Administrador.');
        }

        $idJob        = $this->request->getPost('jobName');
        $data['path'] = $idJob . '/' . $idProgramming;

        return $this->response->setJSON($data);
    }

    /**
     * Receive SMS from Twilio webhook
     * Worker replies with "1" to confirm their planning assignment
     * @since 27/8/2023
     * @author BMOTTAG
     * @review 17/05/2026 - new CI4 version
     */
    public function receive_sms()
    {
        $twiml = '<Response></Response>';

        try {
            $incomingMessage = $this->request->getPost('Body');
            $senderNumber    = $this->request->getPost('From');

            if (empty($incomingMessage) || empty($senderNumber)) {
                return $this->response->setContentType('text/xml')->setBody($twiml);
            }

            $movil  = str_replace('+1', '', $senderNumber);
            $worker = $this->generalModel->get_programming_user(['movil' => $movil]);

            if (!$worker) {
                return $this->response->setContentType('text/xml')->setBody($twiml);
            }

            if (trim($incomingMessage) === '1') {
                $this->generalModel->updateRecord([
                    'table'      => 'programming_worker',
                    'primaryKey' => 'id_programming_worker',
                    'id'         => $worker['id_programming_worker'],
                    'column'     => 'confirmation',
                    'value'      => 1,
                ]);

                $this->send_confirmation(
                    $worker['employee'],
                    $worker['date_programming'],
                    $worker['hora'],
                    $worker['movil']
                );

                $twiml = '<Response><Message>Thank you for your response!</Message></Response>';
            } else {
                $twiml = '<Response><Message>The confirmation should be sent by replying with the number 1.</Message></Response>';
            }
        } catch (\Throwable $e) {
            log_message('error', '[receive_sms] ' . $e->getMessage());
        }

        return $this->response->setContentType('text/xml')->setBody($twiml);
    }

    /**
     * Send confirmation SMS to the supervisor when a worker confirms their planning
     * @since 29/08/2023
     * @author BMOTTAG
     * @review 17/05/2026 - new CI4 version
     */
    protected function send_confirmation(string $employee, string $dateProgramming, string $hora, string $movil): bool
    {
        try {
            $smsService = new \App\Libraries\SmsService();
            $mensaje    = "APP VCI - Planning\n\n{$employee} confirmed the plan for {$dateProgramming} at {$hora}.";
            $smsService->send('+1' . $movil, $mensaje);
        } catch (\Throwable $e) {
            log_message('error', '[send_confirmation] ' . $e->getMessage());
        }

        return true;
    }

    /**
     * Delete child workers before regenerating
     * @since 21/10/2023
     * @review 01/05/2026 - new CI4 version
     */
    protected function delete_child_workers($idProgramming)
    {
        return $this->generalModel->deleteRecord([
            'table'      => 'programming_worker',
            'primaryKey' => 'fk_id_programming',
            'id'         => $idProgramming,
        ]);
    }

    /**
     * CRON - Envio automatico de programacion
     * @since 22/10/2023
     * @review 01/05/2026 - new CI4 version
     */
    public function automatic_planning_message()
    {
        $nextDate    = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));
        $information = $this->generalModel->get_programming([
            'fecha'        => $nextDate,
            'estado'       => 'ACTIVAS',
            'smsAutomatic' => true,
        ]);

        if ($information) {
            foreach ($information as $lista) {
                $this->send($lista['id_programming'], true);
            }
        }
    }

    /**
     * Clone Planning
     * @since 28/10/2023
     * @author BMOTTAG
     * @review 05/05/2026 - new CI4 version
     */
    public function clone_planning()
    {
        $idProgramming = $this->request->getPost('hddIdProgramming');
        $infoPlanning  = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);

        if ($idProgrammingClone = $this->programmingModel->createClone($infoPlanning)) {
            $informationWorker = $this->generalModel->get_programming_workers(['idProgramming' => $idProgramming]);
            $this->programmingModel->saveChildWorkers($idProgrammingClone, $informationWorker);
            $this->update_state($idProgrammingClone);
            $data['status'] = 'success';
        } else {
            $data['status'] = 'error';
        }

        return $this->response->setJSON($data);
    }

    /**
     * Cargo modal - formulario de captura Material
     * @since 20/1/2024
     * @review 05/05/2026 - new CI4 version
     */
    public function loadModalMaterials()
    {
        $idProgramming = $this->request->getPost('idProgramming');
        $porciones     = explode('-', $idProgramming);

        $data['idProgramming'] = $porciones[1];
        $data['materialList']  = $this->generalModel->get_basic_search([
            'table' => 'param_material_type',
            'order' => 'material',
            'id'    => 'x',
        ]);

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Programming\Views\modal_material', $data));
    }

    /**
     * Save material
     * @since 20/1/2024
     * @author BMOTTAG
     * @review 05/05/2026 - new CI4 version
     */
    public function save_material()
    {
        $idProgramming   = $this->request->getPost('hddidProgramming');
        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        $data            = [
            'path'       => $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming,
            'controller' => 'index',
        ];

        if ($this->programmingModel->saveMaterial()) {
            $data['status'] = 'success';
            $this->session->setFlashdata('retornoExito', 'You have added a new record!!');
        } else {
            $data['status'] = 'error';
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Delete material record
     * @review 05/05/2026 - new CI4 version
     */
    public function deleteMaterial($idProgrammingMaterial, $fk_id_programming)
    {
        if (empty($idProgrammingMaterial) || empty($fk_id_programming)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('You are in the wrong place.');
        }

        if ($this->generalModel->deleteRecord([
            'table'      => 'programming_material',
            'primaryKey' => 'id_programming_material',
            'id'         => $idProgrammingMaterial,
        ])) {
            $woResult = $this->generalModel->get_basic_search([
                'table'  => 'workorder_materials',
                'order'  => 'fk_id_programming_materials',
                'column' => 'fk_id_programming_materials',
                'id'     => $idProgrammingMaterial,
            ]);
            if ($woResult) {
                $this->generalModel->deleteRecord([
                    'table'      => 'workorder_materials',
                    'primaryKey' => 'fk_id_programming_materials',
                    'id'         => $idProgrammingMaterial,
                ]);
            }
            $this->session->setFlashdata('retornoExito', 'You have deleted one record from <strong> Materials </strong> table.');
        } else {
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $fk_id_programming]);
        return redirect()->to(base_url('programming/index/' . $infoProgramming[0]['fk_id_job'] . '/' . $fk_id_programming));
    }

    /**
     * Updated material
     * @review 05/05/2026 - new CI4 version
     */
    public function updated_material()
    {
        $idProgramming = $this->request->getPost('hddidProgramming');

        if ($this->programmingModel->updatedMaterial()) {
            $this->session->setFlashdata('retornoExito', 'You have updated a record!!');
        } else {
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        return redirect()->to(base_url('programming/index/' . $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming));
    }

    /**
     * Create work order from planning
     * @since 23/1/2023
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function create_work_order($infoPlanning)
    {
        $idProgramming              = $infoPlanning[0]['id_programming'];
        $arrParam                   = ['idProgramming' => $idProgramming];
        $informationWorker          = $this->generalModel->get_programming_workers($arrParam);
        $informationWorkerWithEquip = $this->generalModel->get_programming_equipment($arrParam);
        $programmingMaterials       = $this->programmingModel->get_programming_materials($arrParam);
        $programmingSubcontractor   = $this->programmingModel->get_programming_occasional($arrParam);

        $informationWorkerWO = $this->generalModel->get_programming_workers(['idProgramming' => $idProgramming, 'createWO' => true]);
        $idUser              = $informationWorkerWO ? $informationWorkerWO[0]['fk_id_programming_user'] : $infoPlanning[0]['fk_id_user'];

        $idJob     = $infoPlanning[0]['fk_id_job'];
        $idCompany = $infoPlanning[0]['id_company'];

        $foremanData = ['foreman_name' => '', 'foreman_movil' => '', 'foreman_email' => ''];
        $infoForeman = $this->getForemanData('param_company_foreman', 'id_company_foreman', 'fk_id_job', $idJob);
        if (!$infoForeman && $idCompany > 0) {
            $infoForeman = $this->getForemanData('param_company_foreman', 'id_company_foreman', 'fk_id_param_company', $idCompany);
        }
        if ($infoForeman) {
            $foremanData = [
                'foreman_name'  => $infoForeman['foreman_name'],
                'foreman_movil' => $infoForeman['foreman_movil_number'],
                'foreman_email' => $infoForeman['foreman_email'],
            ];
        }

        $message  = 'A new Work Order was created from the Planning.';
        $arrParam = [
            'idUser'       => $idUser,
            'idJob'        => $idJob,
            'date'         => $infoPlanning[0]['date_programming'],
            'idCompany'    => $idCompany,
            'foremanName'  => $foremanData['foreman_name'],
            'foremanMovil' => $foremanData['foreman_movil'],
            'foremanEmail' => $foremanData['foreman_email'],
            'observation'  => $infoPlanning[0]['observation'],
            'message'      => $message,
        ];

        if ($idWorkorder = $this->programmingModel->add_workorder($arrParam)) {
            $this->generalModel->updateRecord([
                'table'      => 'programming',
                'primaryKey' => 'id_programming',
                'id'         => $idProgramming,
                'column'     => 'fk_id_workorder',
                'value'      => $idWorkorder,
            ]);

            $this->programmingModel->add_workorder_state([
                'idUser'      => $infoPlanning[0]['fk_id_user'],
                'idWorkorder' => $idWorkorder,
                'observation' => $message,
                'state'       => 0,
            ]);

            if ($informationWorker) {
                $map = [
                    'fk_id_programming_user' => 'fk_id_user',
                    'fk_id_employee_type'    => 'fk_id_employee_type',
                    'description'            => 'description',
                    'id_programming_worker'  => 'fk_id_programming_worker',
                ];
                foreach ($informationWorker as $row) {
                    $item = ['fk_id_workorder' => $idWorkorder, 'hours' => 0];
                    foreach ($row as $col => $val) {
                        if (isset($map[$col])) {
                            $dest        = $map[$col];
                            $item[$dest] = ($dest == 'fk_id_employee_type' && (empty($val) || is_null($val))) ? 1 : $val;
                        }
                    }
                    $this->programmingModel->add_item_workorder('workorder_personal', $item);
                }
            }

            if ($informationWorkerWithEquip) {
                $map = [
                    'type_level_2'           => 'fk_id_type_2',
                    'id_vehicle'             => 'fk_id_vehicle',
                    'fk_id_programming_user' => 'operatedby',
                    'description'            => 'description',
                ];
                foreach ($informationWorkerWithEquip as $row) {
                    $item = ['fk_id_workorder' => $idWorkorder, 'quantity' => 1, 'hours' => 0, 'standby' => 2];
                    foreach ($row as $col => $val) {
                        if (isset($map[$col])) {
                            $item[$map[$col]] = $val;
                        }
                    }
                    $this->programmingModel->add_item_workorder('workorder_equipment', $item);
                }
            }

            if ($programmingMaterials) {
                $map = [
                    'fk_id_material'          => 'fk_id_material',
                    'quantity'                => 'quantity',
                    'unit'                    => 'unit',
                    'description'             => 'description',
                    'id_programming_material' => 'fk_id_programming_materials',
                ];
                foreach ($programmingMaterials as $row) {
                    $item = ['fk_id_workorder' => $idWorkorder];
                    foreach ($row as $col => $val) {
                        if (isset($map[$col])) {
                            $item[$map[$col]] = $val;
                        }
                    }
                    $this->programmingModel->add_item_workorder('workorder_materials', $item);
                }
            }

            if ($programmingSubcontractor) {
                $map = [
                    'fk_id_company' => 'fk_id_company', 'equipment'     => 'equipment',
                    'quantity'      => 'quantity',       'unit'          => 'unit',
                    'hours'         => 'hours',          'rate'          => 'rate',
                    'markup'        => 'markup',         'value'         => 'value',
                    'contact'       => 'contact',        'description'   => 'description',
                    'view_pdf'      => 'view_pdf',       'flag_expenses' => 'flag_expenses',
                ];
                foreach ($programmingSubcontractor as $row) {
                    $item = ['fk_id_workorder' => $idWorkorder, 'unit' => ' ', 'contact' => ' ', 'description' => ' '];
                    foreach ($row as $col => $val) {
                        if (isset($map[$col])) {
                            $item[$map[$col]] = $val;
                        }
                    }
                    $insertedId = $this->programmingModel->add_item_workorder('workorder_ocasional', $item);

                    if ($row['does_hauling'] == 1) {
                        $this->programmingModel->add_item_workorder('hauling', [
                            'fk_id_user'        => $infoPlanning[0]['fk_id_user'],
                            'fk_id_company'     => $row['fk_id_company'],
                            'fk_id_site_from'   => $infoPlanning[0]['fk_id_job'],
                            'fk_id_site_to'     => $infoPlanning[0]['fk_id_job'],
                            'comments'          => $row['description'],
                            'plate'             => $row['unit'],
                            'date_issue'        => $infoPlanning[0]['date_programming'],
                            'fk_id_workorder'   => $idWorkorder,
                            'fk_id_submodule'   => $insertedId,
                            'fk_id_programming' => $idProgramming,
                        ]);
                    }
                }
            }

            return $idWorkorder;
        }

        return false;
    }

    /**
     * Get foreman data from a param table
     * @review 01/05/2026 - new CI4 version
     */
    protected function getForemanData($table, $order, $column, $id)
    {
        $result = $this->generalModel->get_basic_search([
            'table'  => $table,
            'order'  => $order,
            'column' => $column,
            'id'     => $id,
        ]);
        return $result ? $result[0] : null;
    }

    /**
     * Cargo modal - formulario de captura Ocasional
     * @since 20/2/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function cargarModalOcasional()
    {
        $idProgramming = $this->request->getPost('idProgramming');
        $porciones     = explode('-', $idProgramming);

        if (count($porciones) > 1) {
            $data['idProgramming'] = $porciones[1];
            $data['companyList']   = $this->generalModel->get_basic_search([
                'table'  => 'param_company',
                'order'  => 'company_name',
                'column' => 'company_type',
                'id'     => 2,
            ]);

            return $this->response
                ->setContentType('text/html')
                ->setBody(view('App\Modules\Programming\Views\modal_ocasional', $data));
        }

        return $this->response->setBody('');
    }

    /**
     * Save Subcontractor
     * @since 20/1/2024
     * @author BMOTTAG
     * @review 05/05/2026 - new CI4 version
     */
    public function save_ocasional()
    {
        $idProgramming   = $this->request->getPost('hddidProgramming');
        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        $data            = [
            'path'       => $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming,
            'controller' => 'index',
        ];

        if ($this->programmingModel->saveOcasional()) {
            $data['status'] = 'success';
            $this->session->setFlashdata('retornoExito', 'You have added a new record!!');
        } else {
            $data['status'] = 'error';
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Save hour / rate
     * @review 05/05/2026 - new CI4 version
     */
    public function save_hour()
    {
        $idProgramming = $this->request->getPost('hddIdProgramming');

        if ($this->programmingModel->saveRate()) {
            $this->session->setFlashdata('retornoExito', 'You have saved the Rate!!');
        } else {
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        return redirect()->to(base_url('programming/index/' . $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming));
    }

    /**
     * Delete record from a programming sub-table
     * @review 05/05/2026 - new CI4 version
     */
    public function deleteRecord($tabla, $idValue, $idProgramming, $vista)
    {
        if (empty($tabla) || empty($idValue) || empty($idProgramming)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('You are in the wrong place.');
        }

        if ($this->generalModel->deleteRecord([
            'table'      => 'programming_' . $tabla,
            'primaryKey' => 'id_programming_' . $tabla,
            'id'         => $idValue,
        ])) {
            $this->session->setFlashdata('retornoExito', 'You have deleted one record from <strong>' . esc($tabla) . '</strong> table.');
        } else {
            $this->session->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        $infoProgramming = $this->generalModel->get_programming(['idProgramming' => $idProgramming]);
        return redirect()->to(base_url('programming/index/' . $infoProgramming[0]['fk_id_job'] . '/' . $idProgramming));
    }


}
