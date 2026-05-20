<?php
namespace App\Modules\Report\Controllers;

use App\Controllers\BaseController;
use App\Modules\Report\Models\ReportModel;
use App\Models\GeneralModel;
use App\Libraries\PdfBuilder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class Report extends BaseController
{
    protected $reportModel;
    protected $generalModel;
    protected $helpers = ['form', 'funciones'];

    public function __construct()
    {
        $this->reportModel  = new ReportModel();
        $this->generalModel = new GeneralModel();
    }

    /**
     * Search by daterange safety reports
     * @since 6/01/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function searchByDateRange($modulo)
    {
        if (empty($modulo)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('You are in the wrong place.');
        }

        $data['workersList']      = false;
        $data['companyList']      = false;
        $data['materialList']     = false;
        $data['jobList']          = false;
        $data['vehicleList']      = false;
        $data['trailerList']      = false;
        $data['truckList']        = false;
        $data['vehicleRequired']  = false;

        switch ($modulo) {
            case 'payroll':
                $data['titulo'] = "<i class='fa fa-book fa-fw'></i> PAYROLL REPORT";
                break;
            case 'payrollByAdmin':
                $data['titulo']      = "<i class='fa fa-book fa-fw'></i> PAYROLL REPORT";
                $data['workersList'] = $this->generalModel->get_user(['state' => 1]);
                break;
            case 'safety':
                $data['jobList'] = $this->generalModel->get_basic_search([
                    'table'  => 'param_jobs',
                    'order'  => 'job_description',
                    'column' => 'state',
                    'id'     => 1,
                ]);
                $data['titulo'] = "<i class='fa fa-life-saver fa-fw'></i> FLHA REPORT";
                break;
            case 'hauling':
                $data['titulo']      = "<i class='fa fa-truck fa-fw'></i> HAULING REPORT";
                $data['truckList']   = $this->reportModel->get_trucks();
                $data['workersList'] = $this->generalModel->get_user(['state' => 1]);
                $data['jobList']     = $this->generalModel->get_basic_search([
                    'table'  => 'param_jobs',
                    'order'  => 'job_description',
                    'column' => 'state',
                    'id'     => 1,
                ]);
                $data['companyList'] = $this->generalModel->get_basic_search([
                    'table' => 'param_company',
                    'order' => 'company_name',
                    'id'    => 'x',
                ]);
                $data['materialList'] = $this->generalModel->get_basic_search([
                    'table' => 'param_material_type',
                    'order' => 'material',
                    'id'    => 'x',
                ]);
                break;
            case 'dailyInspection':
                $data['workersList'] = $this->generalModel->get_user(['state' => 1]);
                $data['vehicleList'] = $this->reportModel->get_vehicle_by_type(['state' => 1, 'tipo' => 'daily']);
                $data['trailerList'] = $this->reportModel->get_vehicle_by_type(['state' => 1, 'tipo' => 'trailer']);
                $data['titulo']      = "<i class='fa fa-search fa-fw'></i> PICKUPS & TRUCKS INSPECTION REPORT";
                break;
            case 'heavyInspection':
                $data['workersList'] = $this->generalModel->get_user(['state' => 1]);
                $data['vehicleList'] = $this->reportModel->get_vehicle_by_type(['state' => 1, 'tipo' => 'heavy']);
                $data['titulo']      = "<i class='fa fa-search fa-fw'></i> CONTRUCTION EQUIPMENT INSPECTION REPORT";
                break;
            case 'specialInspection':
                $data['workersList'] = $this->generalModel->get_user(['state' => 1]);
                $data['vehicleList'] = $this->reportModel->get_vehicle_by_type(['state' => 1, 'tipo' => 'special']);
                $data['titulo']      = "<i class='fa fa-search fa-fw'></i> SPECIAL EQUIPMENT INSPECTION REPORT";
                break;
            case 'workorder':
                $data['jobList'] = $this->generalModel->get_basic_search([
                    'table'  => 'param_jobs',
                    'order'  => 'job_description',
                    'column' => 'state',
                    'id'     => 1,
                ]);
                $data['titulo'] = "<i class='fa fa-money fa-fw'></i> WORK ORDER REPORT";
                break;
            case 'maintenance':
                $data['vehicleList']    = $this->reportModel->get_vehicle_by_type(['company_type' => true]);
                $data['vehicleRequired'] = 'required';
                $data['titulo']         = "<i class='fa fa-money fa-fw'></i> MAINTENANCE PROGRAM";
                break;
            case 'csep_report':
                $data['jobList'] = $this->generalModel->get_basic_search([
                    'table'  => 'param_jobs',
                    'order'  => 'job_description',
                    'column' => 'state',
                    'id'     => 1,
                ]);
                $data['titulo'] = "<i class='fa fa-life-saver fa-fw'></i> CSEP Report";
                break;
            case 'near_miss':
                $data['jobList'] = $this->generalModel->get_basic_search([
                    'table'  => 'param_jobs',
                    'order'  => 'job_description',
                    'column' => 'state',
                    'id'     => 1,
                ]);
                $data['titulo'] = "<i class='fa fa-ambulance fa-fw'></i> INCIDENCES - NEAR MISS REPORT";
                break;
            case 'incident':
                $data['jobList'] = $this->generalModel->get_basic_search([
                    'table'  => 'param_jobs',
                    'order'  => 'job_description',
                    'column' => 'state',
                    'id'     => 1,
                ]);
                $data['titulo'] = "<i class='fa fa-ambulance fa-fw'></i> INCIDENCES - INCIDENT/ACCIDENT REPORT";
                break;
        }

        $data['view'] = 'form_search';

        if ($this->request->getPost('from')) {
            $from = $this->request->getPost('from');
            $to   = $this->request->getPost('to');

            $data['employee'] = $this->request->getPost('employee') ?: 'x';
            $data['from']     = formatear_fecha($from);
            $data['to']       = formatear_fecha($to);
            $data['to']       = date('Y-m-d', strtotime('+1 day', strtotime($data['to'])));

            $arrParam = [
                'from'     => $data['from'],
                'to'       => $data['to'],
                'employee' => $data['employee'],
            ];

            switch ($modulo) {
                case 'payroll':
                    $data['info']   = $this->reportModel->get_payroll($arrParam);
                    $data['view']   = 'list_payroll';
                    $data['modulo'] = $modulo;
                    break;
                case 'payrollByAdmin':
                    $data['info']   = $this->reportModel->get_payroll($arrParam);
                    $data['view']   = 'list_payroll';
                    $data['modulo'] = $modulo;
                    break;
                case 'safety':
                    $arrParam['jobId'] = $this->request->getPost('jobName') ?: 'x';
                    $data['jobId']     = $arrParam['jobId'];
                    $data['info']      = $this->reportModel->get_safety($arrParam);
                    $data['view']      = 'list_safety';
                    break;
                case 'hauling':
                    $arrParam['jobId']    = $this->request->getPost('jobName') ?: 'x';
                    $data['jobId']        = $arrParam['jobId'];
                    $arrParam['company']  = $this->request->getPost('company') ?: 'x';
                    $data['company']      = $arrParam['company'];
                    $arrParam['material'] = $this->request->getPost('material') ?: 'x';
                    $data['material']     = $arrParam['material'];
                    $arrParam['vehicleId'] = $this->request->getPost('truck') ?: 'x';
                    $data['vehicleId']    = $arrParam['vehicleId'];
                    $data['info']         = $this->reportModel->get_hauling($arrParam);
                    $data['view']         = 'list_hauling';
                    break;
                case 'dailyInspection':
                    $arrParam['vehicleId'] = $this->request->getPost('vehicleId') ?: 'x';
                    $data['vehicleId']     = $arrParam['vehicleId'];
                    $arrParam['trailerId'] = $this->request->getPost('trailerId') ?: 'x';
                    $data['trailerId']     = $arrParam['trailerId'];
                    $data['info']          = $this->reportModel->get_daily_inspection($arrParam);
                    $data['view']          = 'list_daily_inspection';
                    break;
                case 'heavyInspection':
                    $arrParam['vehicleId'] = $this->request->getPost('vehicleId') ?: 'x';
                    $data['vehicleId']     = $arrParam['vehicleId'];
                    $data['info']          = $this->reportModel->get_heavy_inspection($arrParam);
                    $data['view']          = 'list_heavy_inspection';
                    break;
                case 'specialInspection':
                    $arrParam['vehicleId']    = $this->request->getPost('vehicleId') ?: 'x';
                    $data['vehicleId']        = $arrParam['vehicleId'];
                    $data['infoWaterTruck']   = $this->reportModel->get_water_truck_inspection($arrParam);
                    $data['infoHydrovac']     = $this->reportModel->get_hydrovac_inspection($arrParam);
                    $data['infoSweeper']      = $this->reportModel->get_sweeper_inspection($arrParam);
                    $data['infoGenerator']    = $this->reportModel->get_generator_inspection($arrParam);
                    $data['view']             = 'list_special_inspection';
                    break;
                case 'workorder':
                    $arrParam['jobId'] = $this->request->getPost('jobName') ?: 'x';
                    $data['jobId']     = $arrParam['jobId'];
                    $data['info']      = $this->reportModel->get_workorder($arrParam);
                    $data['view']      = 'list_workorder';
                    break;
                case 'maintenance':
                    $arrParam['vehicleId'] = $this->request->getPost('vehicleId');
                    $data['info']          = $this->reportModel->get_maintenance($arrParam);
                    $data['view']          = 'list_maintenance';
                    break;
                case 'csep_report':
                    $arrParam['jobId'] = $this->request->getPost('jobName') ?: 'x';
                    $data['jobId']     = $arrParam['jobId'];
                    $data['info']      = $this->reportModel->get_csep($arrParam);
                    $data['view']      = 'list_csep_report';
                    break;
                case 'near_miss':
                    $arrParam['jobId'] = $this->request->getPost('jobName') ?: 'x';
                    $data['jobId']     = $arrParam['jobId'];
                    $data['info']      = $this->reportModel->get_near_miss($arrParam);
                    $data['view']      = 'list_near_miss';
                    break;
                case 'incident':
                    $arrParam['jobId'] = $this->request->getPost('jobName') ?: 'x';
                    $data['jobId']     = $arrParam['jobId'];
                    $data['info']      = $this->reportModel->get_incident($arrParam);
                    $data['view']      = 'list_incident';
                    break;
            }
        }

        return $this->render('App\Modules\Report\Views\\' . $data['view'], $data);
    }

    /**
     * Generate Safety Report in PDF
     * @since 7/01/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function generaSafetyPDF($jobId, $from, $to, $idSafety = 'x')
    {
        set_time_limit(60);

        $jobId    = $jobId == 'x' ? '' : $jobId;
        $arrParam = [
            'from'     => $from,
            'to'       => $to,
            'jobId'    => $jobId,
            'idSafety' => $idSafety,
        ];

        $info     = $this->reportModel->get_safety($arrParam);
        $idSafety = $info[0]['id_safety'];
        $fileName = 'flha_' . $info[0]['job_description'] . '_' . $idSafety . '.pdf';

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Safety Report');
        $pdf->setPrintFooter(false);

        $first = true;
        foreach ($info as $lista) :

            if (!$first) {
                $pdf->AddPage();
            }
            $first = false;

            $hazards        = $this->reportModel->get_safety_hazard($lista['id_safety']);
            $safetyCovid    = $this->reportModel->get_safety_covid($lista['id_safety']);
            $workers        = $this->reportModel->get_safety_workers($lista['id_safety']);
            $subcontractors = $this->reportModel->get_safety_subcontractors($lista['id_safety']);

            $ppe  = $lista['ppe'] == 1 ? 'Yes' : 'No';
            $html = '<br><h1 align="center" style="color:#337ab7;">FIELD LEVEL HAZARD ASSESSMENT<br><br></h1>
                    <style>
                    table { font-family: arial, sans-serif; border-collapse: collapse; width: 100%; }
                    td, th { border: 1px solid #dddddd; text-align: left; padding: 8px; }
                    </style>
                    <table cellspacing="0" cellpadding="5">
                        <tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Task(s) to be done: </strong></th>
                            <th>' . $lista['work'] . '</th>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Date: </strong></th>
                            <th>' . $lista['date'] . '</th>
                        </tr>
                        <tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>PPE inspected: </strong></th>
                            <th>' . $ppe . '</th>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Job Code/Name: </strong></th>
                            <th>' . $lista['job_description'] . '</th>
                        </tr>';

            if ($lista['specify_ppe']) {
                $html .= '<tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Specialized PPE: </strong></th>
                            <th colspan="3">' . $lista['specify_ppe'] . '</th>
                          </tr>';
            }

            $html .= '<tr>
                        <th bgcolor="#337ab7" style="color:white;"><strong>Primary muster point: </strong></th>
                        <th colspan="3">' . $lista['muster_point'] . '</th>
                      </tr>';
            if ($lista['muster_point_2']) {
                $html .= '<tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Secondary muster point: </strong></th>
                            <th colspan="3">' . $lista['muster_point_2'] . '</th>
                          </tr>';
            }
            if ($lista['primary_head_counter']) {
                $html .= '<tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Primary head counter: </strong></th>
                            <th colspan="3">' . $lista['primary_head_counter'] . '</th>
                          </tr>';
            }
            if ($lista['secondary_head_counter']) {
                $html .= '<tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Secondary head counter: </strong></th>
                            <th colspan="3">' . $lista['secondary_head_counter'] . '</th>
                          </tr>';
            }
            $html .= '</table>';

            $html .= '<br><br>
                    <table cellspacing="0" cellpadding="5">
                        <tr>
                            <th bgcolor="#337ab7" style="color:white; text-align: center;" colspan="5"><strong>Priority: High (6-8) Medium (4-5) Low (2-3) </strong></th>
                        </tr>
                        <tr>
                            <th width="28%" bgcolor="#337ab7" style="color:white; text-align: center;" rowspan="2"><strong>Frecuency </strong></th>
                            <th width="72%" bgcolor="#337ab7" style="color:white; text-align: center;" colspan="4"><strong>Severity </strong></th>
                        </tr>
                        <tr>
                            <th width="18%" style="text-align: center;">1. No health impacts</th>
                            <th width="18%" style="text-align: center;">2. Minor health impacts</th>
                            <th width="18%" style="text-align: center;">3. Moderate / Reversible health impacts</th>
                            <th width="18%" style="text-align: center;">4. Permanent consequences / Death</th>
                        </tr>
                        <tr>
                            <th width="28%">1. Not expected to occur</th>
                            <th width="18%" style="text-align: center;">2</th>
                            <th width="18%" style="text-align: center;">3</th>
                            <th width="18%" style="text-align: center;">4</th>
                            <th width="18%" style="text-align: center;">5</th>
                        </tr>
                        <tr>
                            <th>2. Could occur once</th>
                            <th style="text-align: center;">3</th>
                            <th style="text-align: center;">4</th>
                            <th style="text-align: center;">5</th>
                            <th style="text-align: center;">6</th>
                        </tr>
                        <tr>
                            <th>3. Could occur several time</th>
                            <th style="text-align: center;">4</th>
                            <th style="text-align: center;">5</th>
                            <th style="text-align: center;">6</th>
                            <th style="text-align: center;">7</th>
                        </tr>
                        <tr>
                            <th>4. Could occur continuously</th>
                            <th style="text-align: center;">5</th>
                            <th style="text-align: center;">6</th>
                            <th style="text-align: center;">7</th>
                            <th style="text-align: center;">8</th>
                        </tr>
                    </table><br><br>';

            $html .= '<table border="1" cellspacing="0" cellpadding="5">
                        <tr>
                            <th colspan="4"><strong><i>Identify and prioritize hazards below, then identify plans to eliminate/control the hazards</i></strong></th>
                        </tr>
                        <tr bgcolor="#337ab7" style="color:white;">
                            <th width="5%" align="center"><strong>#</strong></th>
                            <th width="25%" align="center"><strong>Activity</strong></th>
                            <th width="20%" align="center"><strong>Hazard</strong></th>
                            <th width="10%" align="center"><strong>Priority</strong></th>
                            <th width="40%" align="center"><strong>Control/Eliminate</strong></th>
                        </tr>';
            if (!$hazards) {
                $html .= '<tr><th colspan="4" align="center"> ---- No data was found for Hazard -----</th></tr>';
            } else {
                $i = 0;
                foreach ($hazards as $h) {
                    $i++;
                    $priority = $h['priority_description'] == '' ? '-' : $h['priority_description'];
                    $html .= '<tr>
                                <th align="center">' . $i . '</th>
                                <th>' . $h['hazard_activity'] . '</th>
                                <th>' . $h['hazard_description'] . '</th>
                                <th align="center">' . $priority . '</th>
                                <th>' . $h['solution'] . '</th>
                              </tr>';
                }
            }
            $html .= '</table><br><br><br><br>';

            if ($safetyCovid) {
                $html .= '<table border="1" cellspacing="0" cellpadding="5">
                            <tr bgcolor="#337ab7" style="color:white;">
                                <th width="40%" align="center"><strong>Questions must be answered prior to performing work</strong></th>
                                <th width="10%" align="center"><strong>Yes</strong></th>
                                <th width="10%" align="center"><strong>No</strong></th>
                                <th width="40%" align="center"><strong>Comments</strong></th>
                            </tr>';

                $covidFields = [
                    ['distancing',     'Can 6ft distancing be maintained between workers during the task?',              'distancing_comments'],
                    ['sharing_tools',  'Workers can perform their tasks without sharing tools or equipment?',             'sharing_tools_comments'],
                    ['required_ppe',   'All workers have the required PPE to safely perform their work? GLOVES ARE MANDATORY.', 'required_ppe_comments'],
                    ['symptoms',       'All workers have no signs or symptoms of being ill (i.e.: Sore throat, fever, dry cough, shortness of breath)?', 'symptoms_comments'],
                    ['protocols',      'Crew is aware of site COVID protocols for breaks, lunchrooms, washrooms, elevator use, etc. and practices for hygiene?', 'protocols_comments'],
                ];
                foreach ($covidFields as $cf) {
                    $yes = $safetyCovid[0][$cf[0]] == 1 ? '<strong>X</strong>' : '';
                    $no  = $safetyCovid[0][$cf[0]] != 1 ? '<strong>X</strong>' : '';
                    $html .= '<tr>
                                <th>' . $cf[1] . '</th>
                                <th align="center">' . $yes . '</th>
                                <th align="center">' . $no . '</th>
                                <th>' . $safetyCovid[0][$cf[2]] . '</th>
                              </tr>';
                }
                $html .= '</table>';
                $html .= '<p><h4>Sample Mitigation Strategies</h4>
                          The mitigation strategies can include, but are not limited, to items such as…<br>
                          You must not start work until you have:
                          <ul>
                              <li>Splitting crew sizes</li>
                              <li>Providing respirators and full-faceshields when distance cannot be maintained</li>
                              <li>Utilizing additional equipment to maintain distancing</li>
                              <li>Providing shielding to provide a barrier between workers</li>
                              <li>Staggering breaks to prevent exposure</li>
                              <li>Disinfecting tools that must be shared</li>
                              <li>Cleaning offices lunch rooms and other common areas as per COVID-19 Cleaning schedule</li>
                              <li>Social distancing of 6 feet required</li>
                          </ul></p>';
            }

            if (!$workers) {
                $html .= 'No data was found for workers';
            } else {
                $html     .= '<table border="1" cellspacing="0" cellpadding="5">';
                $total     = count($workers);
                $totalFilas = 1;
                if ($total >= 4) {
                    $totalFilas = ceil($total / 4);
                }
                $n = 1;
                for ($i = 0; $i < $totalFilas; $i++) {
                    $finish = $n * 4;
                    $star   = $finish - 4;
                    if ($finish > $total) {
                        $finish = $total;
                    }
                    $n++;

                    $html .= '<tr><th align="center" width="20%"><strong><p>Initials</p></strong></th>';
                    for ($j = $star; $j < $finish; $j++) {
                        $sig  = $workers[$j]['signature'] ? '<img src="' . $workers[$j]['signature'] . '" border="0" width="70" height="70" />' : '';
                        $html .= '<th align="center" width="20%">' . $sig . '</th>';
                    }
                    $html .= '</tr>';

                    $html .= '<tr bgcolor="#337ab7" style="color:white;"><th align="center"><strong>Company</strong></th>';
                    for ($j = $star; $j < $finish; $j++) {
                        $html .= '<th align="center"><strong>VCI</strong></th>';
                    }
                    $html .= '</tr>';

                    $html .= '<tr bgcolor="#337ab7" style="color:white;"><th align="center"><strong>Worker Name</strong></th>';
                    for ($j = $star; $j < $finish; $j++) {
                        $html .= '<th align="center"><strong>' . $workers[$j]['name'] . '</strong></th>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</table>';
            }

            $html .= '<br><br>';

            if ($subcontractors) {
                $html     .= '<table border="1" cellspacing="0" cellpadding="5">';
                $total     = count($subcontractors);
                $totalFilas = 1;
                if ($total >= 4) {
                    $totalFilas = ceil($total / 4);
                }
                $n = 1;
                for ($i = 0; $i < $totalFilas; $i++) {
                    $finish = $n * 4;
                    $star   = $finish - 4;
                    if ($finish > $total) {
                        $finish = $total;
                    }
                    $n++;

                    $html .= '<tr><th align="center" width="20%"><strong><p>Initials</p></strong></th>';
                    for ($j = $star; $j < $finish; $j++) {
                        $sig  = $subcontractors[$j]['signature'] ? '<img src="' . $subcontractors[$j]['signature'] . '" border="0" width="70" height="70" />' : '';
                        $html .= '<th align="center" width="20%">' . $sig . '</th>';
                    }
                    $html .= '</tr>';

                    $html .= '<tr bgcolor="#337ab7" style="color:white;"><th align="center"><strong>Company</strong></th>';
                    for ($j = $star; $j < $finish; $j++) {
                        $html .= '<th align="center"><strong>' . $subcontractors[$j]['company_name'] . '</strong></th>';
                    }
                    $html .= '</tr>';

                    $html .= '<tr bgcolor="#337ab7" style="color:white;"><th align="center"><strong>Worker Name</strong></th>';
                    for ($j = $star; $j < $finish; $j++) {
                        $html .= '<th align="center"><strong>' . $subcontractors[$j]['worker_name'] . '</strong></th>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</table>';
            }

            $html .= '<br><br>';

            $signature = $lista['signature'] ? '<img src="' . $lista['signature'] . '" border="0" width="70" height="70" />' : '';
            $html .= '<table border="1" cellspacing="0" cellpadding="5" width="40%">
                        <tr>
                            <th align="center"><strong><p>Meeting conducted by</p></strong></th>
                            <th align="center">' . $signature . '</th>
                        </tr>
                        <tr bgcolor="#337ab7" style="color:white;">
                            <th align="center"><strong>Name</strong></th>
                            <th align="center"><strong>' . $lista['name'] . '</strong></th>
                        </tr>
                      </table>';

            $pdf->writeHTML($html, true, false, true, false, '');
        endforeach;

        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output($fileName, 'I'));
    }

    /**
     * Generate Hauling Report in PDF
     * @since 8/01/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function generaHaulingPDF($idCompany, $idMaterial, $from, $to, $idHauling = 'x')
    {
        $arrParam = [
            'company'   => $idCompany,
            'material'  => $idMaterial,
            'from'      => $from,
            'to'        => $to,
            'idHauling' => $idHauling,
        ];
        $info = $this->reportModel->get_hauling($arrParam);

        if (!$info) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No hauling data found for the selected parameters.');
        }

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Hauling Report');
        $pdf->SetFont('dejavusans', '', 8);
        $pdf->setPrintFooter(false);

        $first = true;
        foreach ($info as $lista) :

            if (!$first) {
                $pdf->AddPage();
            }
            $first = false;

            $titlePlate = 'Plate Number';
            $unitNumber = $lista['plate'];
            if ($lista['fk_id_company'] == 1) {
                $unitNumber = $lista['unit_number'];
            }

            $signatureVCI        = $lista['vci_signature'] ? '<img src="' . $lista['vci_signature'] . '" border="0" width="100" height="100" />' : '';
            $signatureContractor = $lista['contractor_signature'] ? '<img src="' . $lista['contractor_signature'] . '" border="0" width="100" height="100" />' : '';

            $html = '<br><h1 align="center" style="color:#337ab7;">HAULING REPORT<br><br></h1>
                    <style>
                    table { font-family: arial, sans-serif; border-collapse: collapse; width: 100%; }
                    td, th { border: 1px solid #dddddd; text-align: left; padding: 8px; }
                    </style>
                    <table border="0" cellspacing="0" cellpadding="5">
                        <tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Number: </strong></th>
                            <th>' . $lista['id_hauling'] . '</th>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Employee: </strong></th>
                            <th>' . $lista['name'] . '</th>
                        </tr>
                        <tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Hauling done by: </strong></th>
                            <th>' . $lista['company_name'] . '</th>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Date: </strong></th>
                            <th>' . $lista['date_issue'] . '</th>
                        </tr>
                        <tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Truck - ' . $titlePlate . ': </strong></th>
                            <th>' . $unitNumber . '</th>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Truck Type: </strong></th>
                            <th>' . $lista['truck_type'] . '</th>
                        </tr>
                        <tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Material Type: </strong></th>
                            <th>' . $lista['material'] . '</th>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Payment: </strong></th>
                            <th>' . $lista['payment'] . '</th>
                        </tr>
                        <tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Job Code/Name: </strong></th>
                            <th>' . $lista['site_from'] . '</th>
                            <th bgcolor="#337ab7" style="color:white;"><strong>To Site: </strong></th>
                            <th>' . $lista['site_to'] . '</th>
                        </tr>
                        <tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Time In: </strong></th>
                            <th>' . $lista['time_in'] . '</th>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Time Out: </strong></th>
                            <th>' . $lista['time_out'] . '</th>
                        </tr>
                        <tr>
                            <th bgcolor="#337ab7" style="color:white;"><strong>Comments: </strong></th>
                            <th colspan="3">' . $lista['comments'] . '</th>
                        </tr>
                    </table>
                    <table border="0" cellspacing="0" cellpadding="5">
                        <tr bgcolor="#337ab7" style="color:white;">
                            <th align="center" colspan="3"><strong>Signatures</strong></th>
                        </tr>
                        <tr>
                            <th width="35%" align="center">' . $signatureVCI . '</th>
                            <th width="30%"></th>
                            <th width="35%" align="center">' . $signatureContractor . '</th>
                        </tr>
                        <tr bgcolor="#337ab7" style="color:white;">
                            <th align="center"><strong>VCI Representative<br>' . $lista['name'] . '</strong></th>
                            <th></th>
                            <th align="center"><strong>Contractor</strong></th>
                        </tr>
                    </table>';

            $pdf->writeHTML($html, true, false, true, false, '');
        endforeach;

        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('hauling_' . $idHauling . '.pdf', 'I'));
    }

    /**
     * Generate Inspection Daily Report in PDF
     * @since 8/01/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function generaInsectionDailyPDF($idEmployee, $idVehicle, $idTrailer, $from, $to, $idInspection = 'x')
    {
        $arrParam = [
            'employee'     => $idEmployee,
            'vehicleId'    => $idVehicle,
            'trailerId'    => $idTrailer,
            'from'         => $from,
            'to'           => $to,
            'idInspection' => $idInspection,
        ];
        $info = $this->reportModel->get_daily_inspection($arrParam);

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Inspection Report');
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->setPrintFooter(false);

        $first = true;
        foreach ($info as $lista) :

            if (!$first) {
                $pdf->AddPage();
            }
            $first = false;

            switch ($lista['type_level_1']) {
                case 1:  $type1 = 'Fleet';  break;
                case 2:  $type1 = 'Rental'; break;
                default: $type1 = 'Other';  break;
            }

            $inspectionType = $lista['inspection_type'];
            $truck          = $inspectionType == 3;
            $title          = $truck ? 'TRUCK' : 'PICKUP';

            $html = '<h1 align="center" style="color:#337ab7;">' . $title . ' INSPECTION REPORT</h1>
                    <style>
                    table { font-family: arial, sans-serif; border-collapse: collapse; width: 100%; }
                    td, th { border: 1px solid #dddddd; text-align: left; padding: 8px; }
                    </style>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <tr bgcolor="#337ab7" style="color:white;">
                            <th><strong>Type: </strong><br>' . $type1 . ' - ' . $lista['type_2'] . '</th>
                            <th><strong>Make: </strong><br>' . $lista['make'] . '</th>
                            <th><strong>Model: </strong><br>' . $lista['model'] . '</th>
                            <th><strong>Unit Number: </strong><br>' . $lista['unit_number'] . '</th>
                            <th><strong>Hours/Kilometers: </strong><br>' . $lista['hours'] . '</th>
                            <th><strong>Date & Time: </strong><br>' . $lista['date_issue'] . '</th>
                        </tr>
                    </table><br><br>';

            $checkCell = function ($val) {
                if ($val == 1)    return ['<th align="center"><strong>Y</strong></th>', '<th align="center"><strong></strong></th>'];
                if ($val == 0)    return ['<th align="center"><strong></strong></th>', '<th align="center"><strong>X</strong></th>'];
                return ['<th align="center"><strong>N/A</strong></th>', '<th align="center"><strong>N/A</strong></th>'];
            };

            $html .= '<table border="1" cellspacing="0" cellpadding="5">
                        <tr bgcolor="#337ab7" style="color:white;">
                            <th align="center" width="30%"><strong>Items to Check</strong></th>
                            <th align="center" width="10%"><strong>Pass</strong></th>
                            <th align="center" width="10%"><strong>Fail</strong></th>
                            <th align="center" width="30%"><strong>Items to Check</strong></th>
                            <th align="center" width="10%"><strong>Pass</strong></th>
                            <th align="center" width="10%"><strong>Fail</strong></th>
                        </tr>
                        <tr bgcolor="#337ab7" style="color:white;">
                            <th align="center" colspan="3"><strong>ENGINE</strong></th>
                            <th align="center" colspan="3"><strong>LIGHTS</strong></th>
                        </tr>';

            $engineLightRows = [
                ['belt', 'Belts/Hoses', 'head_lamps', 'Head Lamps'],
                ['power_steering', 'Power Steering Fluid', 'hazard_lights', 'Hazard Lights'],
                ['oil_level', 'Oil Level', 'bake_lights', 'Tail Lights'],
                ['coolant_level', 'Coolant Level', 'work_lights', 'Work Lights'],
                ['water_leaks', 'Coolant/Oil Leaks', 'turn_signals', 'Turn signals lights'],
            ];
            foreach ($engineLightRows as $row) {
                [$ya, $na] = $checkCell($lista[$row[0]]);
                [$yb, $nb] = $checkCell($lista[$row[2]]);
                $html .= '<tr><th align="center"><strong>' . $row[1] . '</strong></th>' . $ya . $na . '<th align="center"><strong>' . $row[3] . '</strong></th>' . $yb . $nb . '</tr>';
            }

            [$ya, $na] = $checkCell($lista['beacon_light']);
            $html .= '<tr>
                        <th align="center"><strong>DEF Level:</strong></th>
                        <th colspan="2" align="center"><strong>' . $lista['def'] . ' %</strong></th>
                        <th align="center"><strong>Beacon Light:</strong></th>' . $ya . $na . '
                      </tr>';

            if ($truck) {
                [$ya, $na] = $checkCell($lista['clearance_lights']);
                $html .= '<tr><th align="center"><strong>Clearance Lights</strong></th>' . $ya . $na . '</tr>';
            }

            $html .= '<tr bgcolor="#337ab7" style="color:white;">
                        <th align="center" colspan="3"><strong>SERVICE</strong></th>
                        <th align="center" colspan="3">' . ($truck ? 'GREASING' : '') . '<strong></strong></th>
                      </tr>';

            $serviceExteriorRows = [
                ['brake_pedal', 'Brake pedal', 'nuts', 'Tires/Lug Nuts/Pressure'],
                ['emergency_brake', 'Emergency brake', 'glass', 'Glass (All) & Mirror'],
                ['gauges', 'Gauges: Volt/Fuel/Temp/Oil', 'clean_exterior', 'Clean exterior'],
                ['horn', 'Electrical & Air Horn', 'wipers', 'Wipers/Washers'],
                ['seatbelts', 'Seatbelts', 'backup_beeper', 'Backup Beeper'],
                ['driver_seat', 'Driver & Passenger seat', 'passenger_door', 'Driver and Passenger door'],
                ['insurance', 'Insurance information', 'proper_decals', 'Decals'],
            ];
            foreach ($serviceExteriorRows as $row) {
                [$ya, $na] = $checkCell($lista[$row[0]]);
                [$yb, $nb] = $checkCell($lista[$row[2]]);
                $html .= '<tr><th align="center"><strong>' . $row[1] . '</strong></th>' . $ya . $na . '<th align="center"><strong>' . $row[3] . '</strong></th>' . $yb . $nb . '</tr>';
            }

            [$ya, $na] = $checkCell($lista['registration']);
            $extra = $truck ? '<th align="center" colspan="3" rowspan="2"><strong></strong></th>' : '';
            $html .= '<tr><th align="center"><strong>Registration</strong></th>' . $ya . $na . $extra . '</tr>';

            [$ya, $na] = $checkCell($lista['clean_interior']);
            $html .= '<tr><th align="center"><strong>Clean interior</strong></th>' . $ya . $na . '</tr>';

            $html .= '<tr bgcolor="#337ab7" style="color:white;">
                        <th align="center" colspan="3"><strong>SAFETY</strong></th>
                        <th align="center" colspan="3">' . ($truck ? '<strong>GREASING</strong>' : '') . '</th>
                      </tr>';

            [$ya, $na] = $checkCell($lista['fire_extinguisher']);
            if ($truck) {
                [$yb, $nb] = $checkCell($lista['steering_axle']);
                $html .= '<tr><th align="center"><strong>Fire extinguisher</strong></th>' . $ya . $na . '<th align="center"><strong>Steering Axle</strong></th>' . $yb . $nb . '</tr>';
            } else {
                $html .= '<tr><th align="center"><strong>Fire extinguisher</strong></th>' . $ya . $na . '<th colspan="3" rowspan="4"></th></tr>';
            }

            [$ya, $na] = $checkCell($lista['first_aid']);
            if ($truck) {
                [$yb, $nb] = $checkCell($lista['drives_axle']);
                $html .= '<tr><th align="center"><strong>First Aid</strong></th>' . $ya . $na . '<th align="center"><strong>Drives Axles</strong></th>' . $yb . $nb . '</tr>';
            } else {
                $html .= '<tr><th align="center"><strong>First Aid</strong></th>' . $ya . $na . '</tr>';
            }

            [$ya, $na] = $checkCell($lista['emergency_reflectors']);
            if ($truck) {
                [$yb, $nb] = $checkCell($lista['grease_front']);
                $html .= '<tr><th align="center"><strong>Emergency Kit</strong></th>' . $ya . $na . '<th align="center"><strong>Front drive shaft</strong></th>' . $yb . $nb . '</tr>';
            } else {
                $html .= '<tr><th align="center"><strong>Emergency Kit</strong></th>' . $ya . $na . '</tr>';
            }

            [$ya, $na] = $checkCell($lista['spill_kit']);
            if ($truck) {
                [$yb, $nb] = $checkCell($lista['grease_end']);
                $html .= '<tr><th align="center"><strong>Spill Kit</strong></th>' . $ya . $na . '<th align="center"><strong>Back drive shaft</strong></th>' . $yb . $nb . '</tr>';
            } else {
                $html .= '<tr><th align="center"><strong>Spill Kit</strong></th>' . $ya . $na . '</tr>';
            }

            if ($truck) {
                [$ya, $na] = $checkCell($lista['grease']);
                $html .= '<tr><th colspan="3" rowspan="2"></th><th align="center"><strong>Grease 5th wheel</strong></th>' . $ya . $na . '</tr>';
                [$ya, $na] = $checkCell($lista['hoist']);
                $html .= '<tr><th align="center"><strong>Box hoist & hinge</strong></th>' . $ya . $na . '</tr>';
            }

            $html .= '<tr><th colspan="6"><strong>Comments : </strong>' . $lista['comments'] . '</th></tr>';

            if ($lista['with_trailer'] == 1) {
                $html .= '<tr bgcolor="#337ab7" style="color:white;"><th colspan="6"><strong>TRAILER :  ' . $lista['trailer'] . '</strong></th></tr>';

                [$ya, $na] = $checkCell($lista['trailer_lights'] == 1 ? 1 : ($lista['trailer_lights'] == 2 ? 0 : null));
                [$yb, $nb] = $checkCell($lista['trailer_tires'] == 1 ? 1 : ($lista['trailer_tires'] == 2 ? 0 : null));
                $html .= '<tr><th align="center"><strong>Lights</strong></th>' . $ya . $na . '<th align="center"><strong>Tires</strong></th>' . $yb . $nb . '</tr>';

                [$ya, $na] = $checkCell($lista['trailer_clean'] == 1 ? 1 : ($lista['trailer_clean'] == 2 ? 0 : null));
                $html .= '<tr><th align="center"><strong>Clean</strong></th>' . $ya . $na . '<th align="center"><strong>Slings</strong></th><th colspan="2" align="center"><strong>' . $lista['trailer_slings'] . '</strong></th></tr>';

                $html .= '<tr>
                            <th align="center"><strong>Chains</strong></th>
                            <th colspan="2" align="center"><strong>' . $lista['trailer_chains'] . '</strong></th>
                            <th align="center"><strong>Ratchet</strong></th>
                            <th colspan="2" align="center"><strong>' . $lista['trailer_ratchet'] . '</strong></th>
                          </tr>';
                $html .= '<tr><th colspan="6"><strong>Comments : </strong>' . $lista['trailer_comments'] . '</th></tr>';
            }

            $html .= '</table>';

            $signature = $lista['signature'] ? '<img src="' . $lista['signature'] . '" border="0" width="70" height="70" />' : '';
            $html .= '<br><br>
                    <table border="1" cellspacing="0" cellpadding="5" width="40%">
                        <tr>
                            <th align="center"><strong><p>Driver</p></strong></th>
                            <th align="center">' . $signature . '</th>
                        </tr>
                        <tr bgcolor="#337ab7" style="color:white;">
                            <th align="center"><strong>Name</strong></th>
                            <th align="center"><strong>' . $lista['name'] . '</strong></th>
                        </tr>
                    </table>';

            $pdf->writeHTML($html, true, false, true, false, '');
        endforeach;

        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('daily_inspection.pdf', 'I'));
    }

    /**
     * Generate Inspection Heavy Report in PDF
     * @since 9/01/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function generaInsectionHeavyPDF($idEmployee, $idVehicle, $from, $to, $idInspection = 'x')
    {
        $arrParam = [
            'employee'     => $idEmployee,
            'vehicleId'    => $idVehicle,
            'from'         => $from,
            'to'           => $to,
            'idInspection' => $idInspection,
        ];
        $data['info']        = $this->reportModel->get_heavy_inspection($arrParam);
        $data['consecutivo'] = 0;

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Inspection Report');
        $pdf->setPrintFooter(false);

        $first = true;
        foreach ($data['info'] as $lista) {

            if (!$first) {
                $pdf->AddPage();
            }
            $first = false;

            $html = view('App\Modules\Report\Views\\' . $lista['form'], $data);
            $pdf->writeHTML($html, true, false, true, false, '');
            $data['consecutivo']++;
        }

        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('heavy_inspection.pdf', 'I'));
    }

    /**
     * Generate Inspection Special Report in PDF
     * @since 23/04/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function generaInsectionSpecialPDF($idEmployee, $idVehicle, $from, $to, $type, $idInspection = 'x')
    {
        $arrParam = [
            'employee'     => $idEmployee,
            'vehicleId'    => $idVehicle,
            'from'         => $from,
            'to'           => $to,
            'idInspection' => $idInspection,
        ];

        switch ($type) {
            case 'watertruck': $data['info'] = $this->reportModel->get_water_truck_inspection($arrParam); break;
            case 'hydrovac':   $data['info'] = $this->reportModel->get_hydrovac_inspection($arrParam);   break;
            case 'sweeper':    $data['info'] = $this->reportModel->get_sweeper_inspection($arrParam);    break;
            case 'generator':  $data['info'] = $this->reportModel->get_generator_inspection($arrParam);  break;
            default:
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Unknown inspection type.');
        }

        $data['consecutivo'] = 0;

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Inspection Report');
        $pdf->setPrintFooter(false);

        $first = true;
        foreach ($data['info'] as $lista) {

            if (!$first) {
                $pdf->AddPage();
            }
            $first = false;

            $html = view('App\Modules\Report\Views\\' . $lista['form'], $data);
            $pdf->writeHTML($html, true, false, true, false, '');
            $data['consecutivo']++;
        }

        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('special_inspection.pdf', 'I'));
    }

    /**
     * Generate Payroll Report in PDF
     * @since 16/01/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function generaPayrollPDF($idUser, $from, $to)
    {
        $arrParam = [
            'from'     => $from,
            'to'       => $to,
            'employee' => $idUser,
        ];
        $info = $this->reportModel->get_payroll($arrParam);

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Payroll Report');
        $pdf->SetFont('dejavusans', '', 7);

        $html = '<h1 align="center" style="color:#337ab7;">PAYROLL REPORT</h1>
                <p style="color:#337ab7;"><strong><i>From Date: </i></strong>' . $from . '<br>
                <strong><i>To Date: </i></strong>' . $to . '</p>
                <style>
                table { font-family: arial, sans-serif; border-collapse: collapse; width: 100%; }
                td, th { border: 1px solid #dddddd; text-align: left; padding: 8px; }
                </style>
                <table border="0" cellspacing="1" cellpadding="3">
                    <tr bgcolor="#337ab7" style="color:white;">
                        <th rowspan="2" align="center" width="12%"><strong>Employee Name</strong></th>
                        <th colspan="2" align="center" width="17%"><strong>Date & Time</strong></th>
                        <th colspan="2" align="center" width="20%"><strong>Job</strong></th>
                        <th rowspan="2" align="center" width="8%"><strong>Working Hours</strong></th>
                        <th rowspan="2" align="center" width="7%"><strong>Total Hours</strong></th>
                        <th rowspan="2" align="center" width="15%"><strong>Task description</strong></th>
                        <th rowspan="2" align="center" width="21%"><strong>Observation</strong></th>
                    </tr>
                    <tr bgcolor="#337ab7" style="color:white;">
                        <th align="center"><strong>In</strong></th>
                        <th align="center"><strong>Out</strong></th>
                        <th align="center"><strong>Start</strong></th>
                        <th align="center"><strong>Finish</strong></th>
                    </tr>';

        $total = 0;
        foreach ($info as $row) {
            $parts  = explode(':', $row['working_hours_new']);
            $total += ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
            $hours = intdiv($total, 3600);
            $mins  = intdiv($total, 60) % 60;
            $finish = $row['finish'] == "0000-00-00 00:00:00"?"":date('M j, Y - G:i', strtotime($row['finish']));

            $html .= '<tr>
                        <th>' . $row['name'] . '</th>
                        <th align="center">' . date('M j, Y - G:i', strtotime($row['start'])) . '</th>
                        <th align="center">' . $finish . '</th>
                        <th>' . $row['job_start'] . '</th>
                        <th>' . $row['job_finish'] . '</th>
                        <th align="center">' . substr($row['working_hours_new'], 0, 5) . '</th>
                        <th align="center">' . sprintf('%02d:%02d', $hours, $mins) . '</th>
                        <th>' . $row['task_description'] . '</th>
                        <th>' . $row['observation'] . '</th>
                      </tr>';
        }
        $html .= '</table><br><br>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('payroll.pdf', 'I'));
    }

    /**
     * Generate Check-In Report in PDF
     * @since 5/06/2022
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function checkinPDF($date)
    {
        $data['requestDate'] = $date;
        $data['checkinList'] = $this->generalModel->get_checkin(['today' => $date]);

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Check-In report');
        $pdf->SetFont('dejavusans', '', 8);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        $html = view('App\Modules\Report\Views\checkin_report', $data);
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('check_in_' . $date . '.pdf', 'I'));
    }

    /**
     * Generate Payroll Report in XLS
     * @since 27/01/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function generaPayrollXLS($idUser, $from, $to)
    {
        $arrParam = [
            'from'     => $from,
            'to'       => $to,
            'employee' => $idUser,
        ];
        $info = $this->reportModel->get_payroll($arrParam);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Consolidado');
        $spreadsheet->getActiveSheet(0)
            ->setCellValue('A1', 'PAYROLL REPORT')
            ->setCellValue('A2', 'Date Range:')
            ->setCellValue('B2', $from . '-' . $to)
            ->setCellValue('A4', 'Employee Name')
            ->setCellValue('B4', 'Time In')
            ->setCellValue('C4', 'Time Out')
            ->setCellValue('D4', 'Job Start')
            ->setCellValue('E4', 'Job Finish')
            ->setCellValue('F4', 'Task Description')
            ->setCellValue('G4', 'Observation')
            ->setCellValue('H4', 'Working Hours')
            ->setCellValue('I4', 'Total Hours');

        $j     = 5;
        $total = 0;
        foreach ($info as $row) {
            $parts  = explode(':', $row['working_hours_new']);
            $total += ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
            $hours = intdiv($total, 3600);
            $mins  = intdiv($total, 60) % 60;
            $finish = $row['finish'] == "0000-00-00 00:00:00"?"":date('M j, Y - G:i', strtotime($row['finish']));
            
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $j, $row['name'])
                ->setCellValue('B' . $j, date('M j, Y - G:i', strtotime($row['start'])))
                ->setCellValue('C' . $j, $finish)
                ->setCellValue('D' . $j, $row['job_start'])
                ->setCellValue('E' . $j, $row['job_finish'])
                ->setCellValue('F' . $j, $row['task_description'])
                ->setCellValue('G' . $j, $row['observation'])
                ->setCellValue('H' . $j, substr($row['working_hours_new'], 0, 5))
                ->setCellValue('I' . $j, sprintf('%02d:%02d', $hours, $mins));
            $j++;
        }

        $colWidths = ['A' => 23, 'B' => 20, 'C' => 20, 'D' => 30, 'E' => 30, 'F' => 30, 'G' => 30, 'H' => 15, 'I' => 15];
        foreach ($colWidths as $col => $width) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth($width);
        }

        $this->_xlsHeaderStyle($spreadsheet, 'A1:I4');
        $spreadsheet->setActiveSheetIndex(0);

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $content = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename=payroll_' . date('Y-m-d') . '.xlsx')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($content);
    }

    /**
     * Generate Hauling Report in XLS
     * @since 8/05/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function generaHaulingXLS($idCompany, $idMaterial, $from, $to)
    {
        $arrParam = [
            'company'  => $idCompany,
            'material' => $idMaterial,
            'from'     => $from,
            'to'       => $to,
        ];
        $info = $this->reportModel->get_hauling($arrParam);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Hauling Report');
        $spreadsheet->getActiveSheet(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Hauling done by')
            ->setCellValue('C1', 'Employee')
            ->setCellValue('D1', 'Truck - Unit Number')
            ->setCellValue('E1', 'Truck Type')
            ->setCellValue('F1', 'Plate')
            ->setCellValue('G1', 'Material Type')
            ->setCellValue('H1', 'Job Code/Name')
            ->setCellValue('I1', 'Payment')
            ->setCellValue('J1', 'Date of Issue')
            ->setCellValue('K1', 'Time In')
            ->setCellValue('L1', 'Time Out')
            ->setCellValue('M1', 'Comments');

        $j = 2;
        foreach ($info as $row) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $j, $row['id_hauling'])
                ->setCellValue('B' . $j, $row['company_name'])
                ->setCellValue('C' . $j, $row['name'])
                ->setCellValue('D' . $j, $row['unit_number'])
                ->setCellValue('E' . $j, $row['truck_type'])
                ->setCellValue('F' . $j, $row['plate'])
                ->setCellValue('G' . $j, $row['material'])
                ->setCellValue('H' . $j, $row['site_from'])
                ->setCellValue('I' . $j, $row['payment'])
                ->setCellValue('J' . $j, $row['date_issue'])
                ->setCellValue('K' . $j, $row['time_in'])
                ->setCellValue('L' . $j, $row['time_out'])
                ->setCellValue('M' . $j, $row['comments']);
            $j++;
        }

        $colWidths = ['A' => 15, 'B' => 20, 'C' => 20, 'D' => 30, 'E' => 30, 'F' => 30, 'G' => 30, 'H' => 15, 'I' => 15, 'J' => 15, 'K' => 15, 'L' => 30, 'M' => 50];
        foreach ($colWidths as $col => $width) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth($width);
        }

        $this->_xlsHeaderStyle($spreadsheet, 'A1:M1');
        $spreadsheet->setActiveSheetIndex(0);

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $content = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename=hauling_report_' . $from . '_' . $to . '.xlsx')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($content);
    }

    /**
     * Generate Work Order Report in XLS
     * @since 30/01/2017
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function generaWorkOrderXLS($jobId, $from, $to)
    {
        $jobId    = $jobId == 'x' ? '' : $jobId;
        $arrParam = ['from' => $from, 'to' => $to, 'jobId' => $jobId];
        $info     = $this->reportModel->get_workorder($arrParam);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Work Order Report');
        $spreadsheet->getActiveSheet(0)
            ->setCellValue('A1', 'Work Order #')
            ->setCellValue('B1', 'Supervisor')
            ->setCellValue('C1', 'Date of Issue')
            ->setCellValue('D1', 'Work Order Date')
            ->setCellValue('E1', 'Job Code/Name')
            ->setCellValue('F1', 'Work Done')
            ->setCellValue('G1', 'Description')
            ->setCellValue('H1', 'Employee Name')
            ->setCellValue('I1', 'Employee Type')
            ->setCellValue('J1', 'Material')
            ->setCellValue('K1', 'Equipment')
            ->setCellValue('L1', 'Hours')
            ->setCellValue('M1', 'Quantity')
            ->setCellValue('N1', 'Unit')
            ->setCellValue('O1', 'Unit price')
            ->setCellValue('P1', 'Operated by')
            ->setCellValue('Q1', 'Line Total');

        $j = 2;
        foreach ($info as $row) {
            $idWorkOrder        = $row['id_workorder'];
            $workorderPersonal  = $this->reportModel->get_workorder_personal($idWorkOrder);
            $workorderMaterials = $this->reportModel->get_workorder_materials($idWorkOrder);
            $workorderReceipt   = $this->reportModel->get_workorder_receipt(['idWorkOrder' => $idWorkOrder]);
            $workorderEquipment = $this->reportModel->get_workorder_equipment($idWorkOrder);
            $workorderOcasional = $this->reportModel->get_workorder_ocasional($idWorkOrder);
            $observation        = $row['observation'] ?? '';

            if ($workorderPersonal) {
                foreach ($workorderPersonal as $p) {
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $row['id_workorder'])
                        ->setCellValue('B' . $j, $row['name'])
                        ->setCellValue('C' . $j, $row['date_issue'])
                        ->setCellValue('D' . $j, $row['date'])
                        ->setCellValue('E' . $j, $row['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $p['description'])
                        ->setCellValue('H' . $j, $p['name'])
                        ->setCellValue('I' . $j, $p['type'])
                        ->setCellValue('L' . $j, $p['hours'])
                        ->setCellValue('N' . $j, 'Hours')
                        ->setCellValue('O' . $j, $p['rate'])
                        ->setCellValue('Q' . $j, $p['value']);
                    $j++;
                }
            }

            if ($workorderMaterials) {
                foreach ($workorderMaterials as $m) {
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $row['id_workorder'])
                        ->setCellValue('B' . $j, $row['name'])
                        ->setCellValue('C' . $j, $row['date_issue'])
                        ->setCellValue('D' . $j, $row['date'])
                        ->setCellValue('E' . $j, $row['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $m['description'])
                        ->setCellValue('J' . $j, $m['material'])
                        ->setCellValue('M' . $j, $m['quantity'])
                        ->setCellValue('N' . $j, $m['unit'])
                        ->setCellValue('O' . $j, $m['rate'])
                        ->setCellValue('Q' . $j, $m['value']);
                    $j++;
                }
            }

            if ($workorderReceipt) {
                foreach ($workorderReceipt as $r) {
                    $desc = $r['description'] . ' - ' . $r['place'];
                    if ($r['markup'] > 0) {
                        $desc .= ' - Plus M.U.';
                    }
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $row['id_workorder'])
                        ->setCellValue('B' . $j, $row['name'])
                        ->setCellValue('C' . $j, $row['date_issue'])
                        ->setCellValue('D' . $j, $row['date'])
                        ->setCellValue('E' . $j, $row['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $desc)
                        ->setCellValue('Q' . $j, $r['value']);
                    $j++;
                }
            }

            if ($workorderEquipment) {
                foreach ($workorderEquipment as $e) {
                    $equipment = $e['fk_id_type_2'] == 8
                        ? $e['miscellaneous'] . ' - ' . $e['other']
                        : $e['type_2'] . ' - ' . $e['unit_number'] . ' - ' . $e['v_description'];
                    $quantity = $e['quantity'] == 0 ? 1 : $e['quantity'];

                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $row['id_workorder'])
                        ->setCellValue('B' . $j, $row['name'])
                        ->setCellValue('C' . $j, $row['date_issue'])
                        ->setCellValue('D' . $j, $row['date'])
                        ->setCellValue('E' . $j, $row['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $e['description'])
                        ->setCellValue('K' . $j, $equipment)
                        ->setCellValue('L' . $j, $e['hours'])
                        ->setCellValue('M' . $j, $quantity)
                        ->setCellValue('N' . $j, 'Hours')
                        ->setCellValue('O' . $j, $e['rate'])
                        ->setCellValue('P' . $j, $e['operatedby'])
                        ->setCellValue('Q' . $j, $e['value']);
                    $j++;
                }
            }

            if ($workorderOcasional) {
                foreach ($workorderOcasional as $o) {
                    $equipment = $o['company_name'] . '-' . $o['equipment'];
                    $hours     = $o['hours'] == 0 ? 1 : $o['hours'];

                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $row['id_workorder'])
                        ->setCellValue('B' . $j, $row['name'])
                        ->setCellValue('C' . $j, $row['date_issue'])
                        ->setCellValue('D' . $j, $row['date'])
                        ->setCellValue('E' . $j, $row['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $o['description'])
                        ->setCellValue('K' . $j, $equipment)
                        ->setCellValue('L' . $j, $hours)
                        ->setCellValue('M' . $j, $o['quantity'])
                        ->setCellValue('N' . $j, $o['unit'])
                        ->setCellValue('O' . $j, $o['rate'])
                        ->setCellValue('Q' . $j, $o['value']);
                    $j++;
                }
            }
        }

        $colWidths = ['A' => 15, 'B' => 22, 'C' => 22, 'D' => 20, 'E' => 50, 'F' => 60, 'G' => 60, 'H' => 20, 'I' => 20, 'J' => 15, 'K' => 50, 'L' => 15, 'M' => 15, 'N' => 15, 'O' => 15, 'P' => 15, 'Q' => 15];
        foreach ($colWidths as $col => $width) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth($width);
        }

        $this->_xlsHeaderStyle($spreadsheet, 'A1:Q1');
        $spreadsheet->setActiveSheetIndex(0);

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $content = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename=workorder_report.xlsx')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($content);
    }

    /**
     * Valves Report in XLS
     * @since unknown
     * @author BMOTTAG
     * @review 04/05/2026 - new CI4 version
     */
    public function valvesReport()
    {
        $info = $this->generalModel->get_basic_search([
            'table' => 'valves',
            'order' => 'valve_number',
            'id'    => 'x',
        ]);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Valves Report');
        $spreadsheet->getActiveSheet(0)
            ->setCellValue('A1', 'Date Issued')
            ->setCellValue('B1', 'Valve #s')
            ->setCellValue('C1', '# of Turns')
            ->setCellValue('D1', 'Position that valve was found')
            ->setCellValue('E1', 'Condition')
            ->setCellValue('F1', 'The direction of the turn for operation')
            ->setCellValue('G1', 'Rewarks');

        $j = 2;
        foreach ($info as $row) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $j, $row['date_issue'])
                ->setCellValue('B' . $j, $row['valve_number'])
                ->setCellValue('C' . $j, $row['number_of_turns'])
                ->setCellValue('D' . $j, $row['position'])
                ->setCellValue('E' . $j, str_replace(["\r", "\n"], ' ', $row['status']))
                ->setCellValue('F' . $j, $row['direction'])
                ->setCellValue('G' . $j, str_replace(["\r", "\n"], ' ', $row['rewarks']));
            $j++;
        }

        $colWidths = ['A' => 20, 'B' => 10, 'C' => 20, 'D' => 30, 'E' => 100, 'F' => 40, 'G' => 100];
        foreach ($colWidths as $col => $width) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth($width);
        }

        $this->_xlsHeaderStyle($spreadsheet, 'A1:G1');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('E1:E' . ($j - 1))->getAlignment()->setWrapText(true);
        $sheet->getStyle('G1:G' . ($j - 1))->getAlignment()->setWrapText(true);
        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        $spreadsheet->setActiveSheetIndex(0);

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $content = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename="valves_report.xlsx"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($content);
    }

    /**
     * Payroll info with edit hours button
     * @since 5/6/2018
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function botonEditHour($idPayroll)
    {
        if (empty($idPayroll)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('You are in the wrong place.');
        }

        $data['titulo'] = "<i class='fa fa-book fa-fw'></i> PAYROLL REPORT";
        $data['modulo'] = 'payrollByAdmin';
        $data['info']   = $this->reportModel->get_payroll(['idPayroll' => $idPayroll]);
        $data['view']   = 'list_payroll_v2';

        return $this->renderTopOnly('App\Modules\Report\Views\list_payroll_v2', $data);
    }

    /**
     * Employee bank time list
     * @since 11/9/2022
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function employeBankTime()
    {
        $idUser         = $this->session->get('id');
        $data['info']   = $this->generalModel->get_bank_time(['idUser' => $idUser]);
        $data['view']   = 'employee_bank_time';

        return $this->renderTopOnly('App\Modules\Report\Views\employee_bank_time', $data);
    }

    /**
     * Apply header styles to XLS sheet range
     */
    private function _xlsHeaderStyle(Spreadsheet $spreadsheet, string $range): void
    {
        $style = $spreadsheet->getActiveSheet()->getStyle($range);
        $style->getFont()->setSize(11)->setBold(true);
        $style->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('236e09');
        $style->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
    }
}
