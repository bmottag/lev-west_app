<?php

namespace App\Modules\Acs\Controllers;

use App\Controllers\BaseController;
use App\Modules\Acs\Models\AcsModel;
use App\Models\GeneralModel;
use App\Libraries\PdfBuilder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class Acs extends BaseController
{
    protected $acsModel;
    protected $generalModel;

    public function __construct()
    {
        $this->acsModel     = new AcsModel();
        $this->generalModel = new GeneralModel();
    }

    /**
     * View info ACS
     * @since 21/2/2017
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     */
    public function view_acs($idACS)
    {
        $arrParam = ['idACS' => $idACS];
        $data = [
            'acs_info'     => $this->acsModel->get_acs($arrParam),
            'acsPersonal'  => $this->acsModel->get_acs_personal($arrParam),
            'acsMaterials' => $this->acsModel->get_acs_materials($arrParam),
            'acsReceipt'   => $this->acsModel->get_acs_receipt($arrParam),
            'acsEquipment' => $this->acsModel->get_acs_equipment($arrParam),
            'acsOcasional' => $this->acsModel->get_acs_ocasional($arrParam),
        ];

        return $this->render('App\Modules\Acs\Views\acs_view', $data);
    }

    /**
     * Save Info ACS Personal
     * @since 17/01/2025
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     */
    public function save_info_acs_personal()
    {
        $records      = $this->request->getPost('records');
        $successCount = 0;
        $errorCount   = 0;

        foreach ($records as $record) {
            $dataToSave = [
                'view_pdf' => isset($record['check_pdf']) ? 1 : 2,
                'hours'    => $record['hours'],
                'rate'     => $record['rate'],
                'value'    => $record['rate'] * $record['hours'],
            ];

            if ($this->acsModel->saveInfoACS($record['hddId'], $dataToSave, $this->request->getPost('formType'))) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        if ($errorCount === 0) {
            session()->setFlashdata('retornoExito', "$successCount records saved successfully!");
        } else {
            session()->setFlashdata('retornoError', "$errorCount records failed to save.");
        }

        return redirect()->to(base_url('acs/view_acs/' . $this->request->getPost('hddIdACS')));
    }

    /**
     * Save Info ACS Material
     * @since 17/01/2025
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     */
    public function save_info_acs_materials()
    {
        $records      = $this->request->getPost('records');
        $successCount = 0;
        $errorCount   = 0;

        foreach ($records as $record) {
            $dataToSave = [
                'view_pdf' => isset($record['check_pdf']) ? 1 : 2,
                'quantity' => $record['quantity'],
                'rate'     => $record['rate'],
                'markup'   => $record['markup'],
                'value'    => $record['rate'] * $record['quantity'] * ($record['markup'] + 100) / 100,
            ];

            if ($this->acsModel->saveInfoACS($record['hddId'], $dataToSave, $this->request->getPost('formType'))) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        if ($errorCount === 0) {
            session()->setFlashdata('retornoExito', "$successCount records saved successfully!");
        } else {
            session()->setFlashdata('retornoError', "$errorCount records failed to save.");
        }

        return redirect()->to(base_url('acs/view_acs/' . $this->request->getPost('hddIdACS')));
    }

    /**
     * Save Info ACS Receipt
     * @since 18/01/2025
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     */
    public function save_info_acs_receipt()
    {
        $records      = $this->request->getPost('records');
        $successCount = 0;
        $errorCount   = 0;

        foreach ($records as $record) {
            $price = $record['price'] / 1.05;
            $value = $price + ($price * $record['markup'] / 100);

            $dataToSave = [
                'view_pdf' => isset($record['check_pdf']) ? 1 : 2,
                'price'    => $record['price'],
                'markup'   => $record['markup'],
                'value'    => $value,
            ];

            if ($this->acsModel->saveInfoACS($record['hddId'], $dataToSave, $this->request->getPost('formType'))) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        if ($errorCount === 0) {
            session()->setFlashdata('retornoExito', "$successCount records saved successfully!");
        } else {
            session()->setFlashdata('retornoError', "$errorCount records failed to save.");
        }

        return redirect()->to(base_url('acs/view_acs/' . $this->request->getPost('hddIdACS')));
    }

    /**
     * Save Info ACS Equipment
     * @since 18/01/2025
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     */
    public function save_info_acs_equipment()
    {
        $records      = $this->request->getPost('records');
        $successCount = 0;
        $errorCount   = 0;

        foreach ($records as $record) {
            $dataToSave = [
                'view_pdf'       => isset($record['check_pdf']) ? 1 : 2,
                'fk_id_company'  => 1,
                'quantity'       => $record['quantity'],
                'rate'           => $record['rate'],
                'value'          => $record['hours'] * $record['quantity'] * $record['rate'],
            ];

            if ($this->acsModel->saveInfoACS($record['hddId'], $dataToSave, $this->request->getPost('formType'))) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        if ($errorCount === 0) {
            session()->setFlashdata('retornoExito', "$successCount records saved successfully!");
        } else {
            session()->setFlashdata('retornoError', "$errorCount records failed to save.");
        }

        return redirect()->to(base_url('acs/view_acs/' . $this->request->getPost('hddIdACS')));
    }

    /**
     * Save Info ACS Ocasional
     * @since 18/01/2025
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     */
    public function save_info_acs_ocasional()
    {
        $records      = $this->request->getPost('records');
        $successCount = 0;
        $errorCount   = 0;

        foreach ($records as $record) {
            $dataToSave = [
                'view_pdf' => isset($record['check_pdf']) ? 1 : 2,
                'quantity' => $record['quantity'],
                'hours'    => $record['hours'],
                'rate'     => $record['rate'],
                'markup'   => $record['markup'],
                'value'    => $record['quantity'] * $record['hours'] * $record['rate'] * ($record['markup'] + 100) / 100,
            ];

            if ($this->acsModel->saveInfoACS($record['hddId'], $dataToSave, $this->request->getPost('formType'))) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        if ($errorCount === 0) {
            session()->setFlashdata('retornoExito', "$successCount records saved successfully!");
        } else {
            session()->setFlashdata('retornoError', "$errorCount records failed to save.");
        }

        return redirect()->to(base_url('acs/view_acs/' . $this->request->getPost('hddIdACS')));
    }

    /**
     * Delete workorder record
     * @param varchar $tabla: nombre de la tabla de la cual se va a borrar
     * @param int $idValue: id que se va a borrar
     * @param int $idACS: llave primaria de ACS
     * @param varchar $vista: vista a la que redirigir
     * @review 18/05/2026 - new CI4 version
     */
    public function deleteACSRecord($tabla, $idValue, $idACS, $vista)
    {
        if (empty($tabla) || empty($idValue) || empty($idACS)) {
            return $this->response->setStatusCode(400)->setBody('ERROR!!! - You are in the wrong place.');
        }

        $arrParam = [
            'table'      => 'acs_' . $tabla,
            'primaryKey' => 'id_acs_' . $tabla,
            'id'         => $idValue,
        ];

        if ($this->generalModel->deleteRecord($arrParam)) {
            session()->setFlashdata('retornoExito', 'You have deleted one record from <strong>' . strtoupper($tabla) . '</strong> table.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('acs/' . $vista . '/' . $idACS));
    }

    /**
     * Cargo modal - formulario de captura personal - ACS
     * @since 18/01/2025
     * @review 18/05/2026 - new CI4 version
     */
    public function cargarModalPersonalACS()
    {
        $data['idACS']            = $this->request->getPost('idACS');
        $data['workersList']      = $this->generalModel->get_user(['state' => 1]);
        $data['employeeTypeList'] = $this->generalModel->get_basic_search([
            'table' => 'param_employee_type',
            'order' => 'employee_type',
            'id'    => 'x',
        ]);

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Acs\Views\modal_personal_acs', $data));
    }

    /**
     * Cargo modal - formulario de captura Material - ACS
     * @since 13/1/2017
     * @review 18/05/2026 - new CI4 version
     */
    public function cargarModalMaterialsACS()
    {
        $idACS    = $this->request->getPost('idACS');
        $porciones = explode('-', $idACS);

        $data['idACS']        = $porciones[1];
        $data['materialList'] = $this->generalModel->get_basic_search([
            'table' => 'param_material_type',
            'order' => 'material',
            'id'    => 'x',
        ]);

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Acs\Views\modal_material_acs', $data));
    }

    /**
     * Cargo modal - formulario de captura Equipment - ACS
     * @since 25/1/2017
     * @review 18/05/2026 - new CI4 version
     */
    public function cargarModalEquipmentACS()
    {
        $idACS    = $this->request->getPost('idACS');
        $porciones = explode('-', $idACS);

        $data['idACS']         = $porciones[1];
        $data['equipmentType'] = $this->generalModel->get_basic_search([
            'table'  => 'param_vehicle_type_2',
            'order'  => 'type_2',
            'column' => 'show_workorder',
            'id'     => 1,
        ]);
        $data['workersList'] = $this->generalModel->get_user(['state' => 1]);

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Acs\Views\modal_equipment_acs', $data));
    }

    /**
     * Cargo modal - formulario de captura Ocasional - ACS
     * @since 20/2/2017
     * @review 18/05/2026 - new CI4 version
     */
    public function cargarModalOcasionalACS()
    {
        $idACS    = $this->request->getPost('idACS');
        $porciones = explode('-', $idACS);

        if (count($porciones) > 1) {
            $data['idACS']       = $porciones[1];
            $data['companyList'] = $this->generalModel->get_basic_search([
                'table'  => 'param_company',
                'order'  => 'company_name',
                'column' => 'company_type',
                'id'     => 2,
            ]);

            return $this->response
                ->setContentType('text/html')
                ->setBody(view('App\Modules\Acs\Views\modal_ocasional_acs', $data));
        }

        return $this->response->setBody('');
    }

    /**
     * Cargo modal - formulario de captura Invoice - ACS
     * @since 18/01/2025
     * @review 18/05/2026 - new CI4 version
     */
    public function cargarModalReceiptsACS()
    {
        $idACS    = $this->request->getPost('idACS');
        $porciones = explode('-', $idACS);

        $data['idACS'] = $porciones[1];

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Acs\Views\modal_receipt_acs', $data));
    }

    /**
     * Save formularios
     * @param varchar $modalToUse: indica que funcion del modelo se debe usar
     * @since 13/1/2017
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     */
    public function save($modalToUse)
    {
        $post = $this->request->getPost();
        $data = ['idRecord' => $post['hddIdACS']];

        if ($this->acsModel->$modalToUse($post)) {
            $data['status'] = 'success';
            session()->setFlashdata('retornoExito', 'You have added a new record!!');
        } else {
            $data['status'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Generate ACS Report in PDF
     * @param int $idACS
     * @since 29/01/2025
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     */
    public function reportPDF($idACS)
    {
        $arrParam        = ['idACS' => $idACS];
        $data['info']    = $this->acsModel->get_acs($arrParam);

        $arrParam['view_pdf']  = true;
        $data['acsPersonal']   = $this->acsModel->get_acs_personal($arrParam);
        $data['acsMaterials']  = $this->acsModel->get_acs_materials($arrParam);
        $data['acsReceipt']    = $this->acsModel->get_acs_receipt($arrParam);
        $data['acsEquipment']  = $this->acsModel->get_acs_equipment($arrParam);
        $data['acsOcasional']  = $this->acsModel->get_acs_ocasional($arrParam);

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Accounting Control Sheet (ACS)');

        $html = view('App\Modules\Acs\Views\reporte_acs', $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('acs_' . $idACS . '.pdf', 'I'));
    }

    /**
     * Income by JOB CODE
     * @since 04/02/2025
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     */
    public function income()
    {
        $jobList = $this->generalModel->get_job(['state' => 1]);

        $incomeData = [];
        foreach ($jobList as $job) {
            $idJob = $job['id_job'];
            $personal     = $this->acsModel->countIncome(['idJob' => $idJob, 'table' => 'acs_personal']);
            $material     = $this->acsModel->countIncome(['idJob' => $idJob, 'table' => 'acs_materials']);
            $receipt      = $this->acsModel->countIncome(['idJob' => $idJob, 'table' => 'acs_receipt']);
            $equipment    = $this->acsModel->countIncome(['idJob' => $idJob, 'table' => 'acs_equipment']);
            $subcontractor = $this->acsModel->countIncome(['idJob' => $idJob, 'table' => 'acs_ocasional']);

            $incomeData[] = [
                'id_job'          => $idJob,
                'job_description' => $job['job_description'],
                'noACS'           => $this->acsModel->countACS(['idJob' => $idJob]),
                'hoursPersonal'   => $this->acsModel->countHoursPersonal(['idJob' => $idJob]),
                'incomePersonal'  => $personal     ?? 0,
                'incomeMaterial'  => $material     ?? 0,
                'incomeReceipt'   => $receipt      ?? 0,
                'incomeEquipment' => $equipment    ?? 0,
                'incomeSubcontractor' => $subcontractor ?? 0,
                'total'           => ($personal ?? 0) + ($material ?? 0) + ($receipt ?? 0) + ($equipment ?? 0) + ($subcontractor ?? 0),
            ];
        }

        $data['incomeData'] = $incomeData;

        return $this->render('App\Modules\Acs\Views\income', $data);
    }

    /**
     * Generate ACS Report in XLS
     * @param int $jobId
     * @since 04/02/2025
     * @author BMOTTAG
     * @review 18/05/2026 - new CI4 version
     * @note XLS generation — identify only, not implemented
     */
    public function generaACSXLS($jobId)
    {
        $info    = $this->acsModel->get_acs(['jobId' => $jobId]);
        $jobCode = $info[0]['job_description'] ?? 'acs';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Accounting Control Sheet (ACS) Report');

        $headers = [
            'A1' => 'Work Order #', 'B1' => 'Supervisor', 'C1' => 'Date of Issue',
            'D1' => 'Work Order Date', 'E1' => 'Job Code/Name', 'F1' => 'Work Done',
            'G1' => 'Description', 'H1' => 'Employee Name', 'I1' => 'Employee Type',
            'J1' => 'Material', 'K1' => 'Equipment', 'L1' => 'Hours',
            'M1' => 'Quantity', 'N1' => 'Unit', 'O1' => 'Unit price',
            'P1' => 'Operated by', 'Q1' => 'Line Total',
        ];

        // Sheet 0: Full ACS
        $spreadsheet->setActiveSheetIndex(0);
        foreach ($headers as $cell => $label) {
            $spreadsheet->getActiveSheet()->setCellValue($cell, $label);
        }

        $j     = 2;
        $total = 0;
        foreach ($info as $data) {
            $arrParam    = ['idACS' => $data['id_acs']];
            $observation = $data['observation'] ?? '';

            $acsPersonal = $this->acsModel->get_acs_personal($arrParam);
            if ($acsPersonal) {
                foreach ($acsPersonal as $infoP) {
                    $total += $infoP['value'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $infoP['description'])
                        ->setCellValue('H' . $j, $infoP['name'])
                        ->setCellValue('I' . $j, $infoP['employee_type'])
                        ->setCellValue('L' . $j, $infoP['hours'])
                        ->setCellValue('N' . $j, 'Hours')
                        ->setCellValue('O' . $j, $infoP['rate'])
                        ->setCellValue('Q' . $j, $infoP['value']);
                    $j++;
                }
            }

            $acsMaterials = $this->acsModel->get_acs_materials($arrParam);
            if ($acsMaterials) {
                foreach ($acsMaterials as $infoM) {
                    $total += $infoM['value'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $infoM['description'])
                        ->setCellValue('J' . $j, $infoM['material'])
                        ->setCellValue('M' . $j, $infoM['quantity'])
                        ->setCellValue('N' . $j, $infoM['unit'])
                        ->setCellValue('O' . $j, $infoM['rate'])
                        ->setCellValue('Q' . $j, $infoM['value']);
                    $j++;
                }
            }

            $acsReceipt = $this->acsModel->get_acs_receipt($arrParam);
            if ($acsReceipt) {
                foreach ($acsReceipt as $infoR) {
                    $total += $infoR['value'];
                    $desc   = $infoR['description'] . ' - ' . $infoR['place'];
                    if ($infoR['markup'] > 0) {
                        $desc .= ' - Plus M.U.';
                    }
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $desc)
                        ->setCellValue('Q' . $j, $infoR['value']);
                    $j++;
                }
            }

            $acsEquipment = $this->acsModel->get_acs_equipment($arrParam);
            if ($acsEquipment) {
                foreach ($acsEquipment as $infoE) {
                    $total    += $infoE['value'];
                    $equipment = $infoE['fk_id_type_2'] == 8
                        ? $infoE['miscellaneous'] . ' - ' . $infoE['other']
                        : $infoE['type_2'] . ' - ' . $infoE['unit_number'] . ' - ' . $infoE['v_description'];
                    $quantity  = $infoE['quantity'] == 0 ? 1 : $infoE['quantity'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $infoE['description'])
                        ->setCellValue('K' . $j, $equipment)
                        ->setCellValue('L' . $j, $infoE['hours'])
                        ->setCellValue('M' . $j, $quantity)
                        ->setCellValue('N' . $j, 'Hours')
                        ->setCellValue('O' . $j, $infoE['rate'])
                        ->setCellValue('P' . $j, $infoE['operatedby'])
                        ->setCellValue('Q' . $j, $infoE['value']);
                    $j++;
                }
            }

            $acsOcasional = $this->acsModel->get_acs_ocasional($arrParam);
            if ($acsOcasional) {
                foreach ($acsOcasional as $infoO) {
                    $total    += $infoO['value'];
                    $equipment = $infoO['company_name'] . '-' . $infoO['equipment'];
                    $hours     = $infoO['hours'] == 0 ? 1 : $infoO['hours'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $infoO['description'])
                        ->setCellValue('K' . $j, $equipment)
                        ->setCellValue('L' . $j, $hours)
                        ->setCellValue('M' . $j, $infoO['quantity'])
                        ->setCellValue('N' . $j, $infoO['unit'])
                        ->setCellValue('O' . $j, $infoO['rate'])
                        ->setCellValue('Q' . $j, $infoO['value']);
                    $j++;
                }
            }
        }

        $spreadsheet->getActiveSheet()->setCellValue('P' . $j, 'Total Income');
        $spreadsheet->getActiveSheet()->setCellValue('Q' . $j, '$ ' . number_format($total, 2));
        $spreadsheet->getActiveSheet()->getStyle('P' . $j . ':Q' . $j)->getFont()->setBold(true);
        $this->_acsSheetSetup($spreadsheet);

        // Sheet 1: Personal Income
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $spreadsheet->getActiveSheet()->setTitle('Personal Income');
        foreach ($headers as $cell => $label) {
            $spreadsheet->getActiveSheet()->setCellValue($cell, $label);
        }

        $j      = 2;
        $totalP = 0;
        foreach ($info as $data) {
            $acsPersonal = $this->acsModel->get_acs_personal(['idACS' => $data['id_acs']]);
            $observation = $data['observation'] ?? '';
            if ($acsPersonal) {
                foreach ($acsPersonal as $infoP) {
                    $totalP += $infoP['value'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $infoP['description'])
                        ->setCellValue('H' . $j, $infoP['name'])
                        ->setCellValue('I' . $j, $infoP['employee_type'])
                        ->setCellValue('L' . $j, $infoP['hours'])
                        ->setCellValue('N' . $j, 'Hours')
                        ->setCellValue('O' . $j, $infoP['rate'])
                        ->setCellValue('Q' . $j, $infoP['value']);
                    $j++;
                }
            }
        }

        $spreadsheet->getActiveSheet()->setCellValue('P' . $j, 'Total Income');
        $spreadsheet->getActiveSheet()->setCellValue('Q' . $j, '$ ' . number_format($totalP, 2));
        $spreadsheet->getActiveSheet()->getStyle('P' . $j . ':Q' . $j)->getFont()->setBold(true);
        $this->_acsSheetSetup($spreadsheet);

        // Sheet 2: Material Income
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2);
        $spreadsheet->getActiveSheet()->setTitle('Material Income');
        foreach ($headers as $cell => $label) {
            $spreadsheet->getActiveSheet()->setCellValue($cell, $label);
        }

        $j      = 2;
        $totalM = 0;
        foreach ($info as $data) {
            $acsMaterials = $this->acsModel->get_acs_materials(['idACS' => $data['id_acs']]);
            $observation  = $data['observation'] ?? '';
            if ($acsMaterials) {
                foreach ($acsMaterials as $infoM) {
                    $totalM += $infoM['value'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $infoM['description'])
                        ->setCellValue('J' . $j, $infoM['material'])
                        ->setCellValue('M' . $j, $infoM['quantity'])
                        ->setCellValue('N' . $j, $infoM['unit'])
                        ->setCellValue('O' . $j, $infoM['rate'])
                        ->setCellValue('Q' . $j, $infoM['value']);
                    $j++;
                }
            }
        }

        $spreadsheet->getActiveSheet()->setCellValue('P' . $j, 'Total Income');
        $spreadsheet->getActiveSheet()->setCellValue('Q' . $j, '$ ' . number_format($totalM, 2));
        $spreadsheet->getActiveSheet()->getStyle('P' . $j . ':Q' . $j)->getFont()->setBold(true);
        $this->_acsSheetSetup($spreadsheet);

        // Sheet 3: Receipt Income
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(3);
        $spreadsheet->getActiveSheet()->setTitle('Receipt Income');
        foreach ($headers as $cell => $label) {
            $spreadsheet->getActiveSheet()->setCellValue($cell, $label);
        }

        $j      = 2;
        $totalR = 0;
        foreach ($info as $data) {
            $acsReceipt  = $this->acsModel->get_acs_receipt(['idACS' => $data['id_acs']]);
            $observation = $data['observation'] ?? '';
            if ($acsReceipt) {
                foreach ($acsReceipt as $infoR) {
                    $totalR += $infoR['value'];
                    $desc    = $infoR['description'] . ' - ' . $infoR['place'];
                    if ($infoR['markup'] > 0) {
                        $desc .= ' - Plus M.U.';
                    }
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $desc)
                        ->setCellValue('Q' . $j, $infoR['value']);
                    $j++;
                }
            }
        }

        $spreadsheet->getActiveSheet()->setCellValue('P' . $j, 'Total Income');
        $spreadsheet->getActiveSheet()->setCellValue('Q' . $j, '$ ' . number_format($totalR, 2));
        $spreadsheet->getActiveSheet()->getStyle('P' . $j . ':Q' . $j)->getFont()->setBold(true);
        $this->_acsSheetSetup($spreadsheet);

        // Sheet 4: Equipment Income
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(4);
        $spreadsheet->getActiveSheet()->setTitle('Equipment Income');
        foreach ($headers as $cell => $label) {
            $spreadsheet->getActiveSheet()->setCellValue($cell, $label);
        }

        $j      = 2;
        $totalE = 0;
        foreach ($info as $data) {
            $acsEquipment = $this->acsModel->get_acs_equipment(['idACS' => $data['id_acs']]);
            $observation  = $data['observation'] ?? '';
            if ($acsEquipment) {
                foreach ($acsEquipment as $infoE) {
                    $totalE   += $infoE['value'];
                    $equipment = $infoE['fk_id_type_2'] == 8
                        ? $infoE['miscellaneous'] . ' - ' . $infoE['other']
                        : $infoE['type_2'] . ' - ' . $infoE['unit_number'] . ' - ' . $infoE['v_description'];
                    $quantity  = $infoE['quantity'] == 0 ? 1 : $infoE['quantity'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $infoE['description'])
                        ->setCellValue('K' . $j, $equipment)
                        ->setCellValue('L' . $j, $infoE['hours'])
                        ->setCellValue('M' . $j, $quantity)
                        ->setCellValue('N' . $j, 'Hours')
                        ->setCellValue('O' . $j, $infoE['rate'])
                        ->setCellValue('P' . $j, $infoE['operatedby'])
                        ->setCellValue('Q' . $j, $infoE['value']);
                    $j++;
                }
            }
        }

        $spreadsheet->getActiveSheet()->setCellValue('P' . $j, 'Total Income');
        $spreadsheet->getActiveSheet()->setCellValue('Q' . $j, '$ ' . number_format($totalE, 2));
        $spreadsheet->getActiveSheet()->getStyle('P' . $j . ':Q' . $j)->getFont()->setBold(true);
        $this->_acsSheetSetup($spreadsheet);

        // Sheet 5: Subcontractor Income
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(5);
        $spreadsheet->getActiveSheet()->setTitle('Subcontractor Income');
        foreach ($headers as $cell => $label) {
            $spreadsheet->getActiveSheet()->setCellValue($cell, $label);
        }

        $j      = 2;
        $totalS = 0;
        foreach ($info as $data) {
            $acsOcasional = $this->acsModel->get_acs_ocasional(['idACS' => $data['id_acs']]);
            $observation  = $data['observation'] ?? '';
            if ($acsOcasional) {
                foreach ($acsOcasional as $infoO) {
                    $totalS   += $infoO['value'];
                    $equipment = $infoO['company_name'] . '-' . $infoO['equipment'];
                    $hours     = $infoO['hours'] == 0 ? 1 : $infoO['hours'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $j, $data['fk_id_workorder'])
                        ->setCellValue('B' . $j, $data['name'])
                        ->setCellValue('C' . $j, $data['date_issue'])
                        ->setCellValue('D' . $j, $data['date'])
                        ->setCellValue('E' . $j, $data['job_description'])
                        ->setCellValue('F' . $j, $observation)
                        ->setCellValue('G' . $j, $infoO['description'])
                        ->setCellValue('K' . $j, $equipment)
                        ->setCellValue('L' . $j, $hours)
                        ->setCellValue('M' . $j, $infoO['quantity'])
                        ->setCellValue('N' . $j, $infoO['unit'])
                        ->setCellValue('O' . $j, $infoO['rate'])
                        ->setCellValue('Q' . $j, $infoO['value']);
                    $j++;
                }
            }
        }

        $spreadsheet->getActiveSheet()->setCellValue('P' . $j, 'Total Income');
        $spreadsheet->getActiveSheet()->setCellValue('Q' . $j, '$ ' . number_format($totalS, 2));
        $spreadsheet->getActiveSheet()->getStyle('P' . $j . ':Q' . $j)->getFont()->setBold(true);
        $this->_acsSheetSetup($spreadsheet);

        $spreadsheet->setActiveSheetIndex(0);

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $content = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename=acs_' . $jobCode . '.xlsx')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($content);
    }

    private function _acsSheetSetup(Spreadsheet $spreadsheet): void
    {
        $sheet      = $spreadsheet->getActiveSheet();
        $colWidths  = ['A' => 15, 'B' => 22, 'C' => 22, 'D' => 20, 'E' => 50, 'F' => 60, 'G' => 60,
                       'H' => 20, 'I' => 20, 'J' => 15, 'K' => 50, 'L' => 15, 'M' => 15,
                       'N' => 15, 'O' => 15, 'P' => 15, 'Q' => 15];
        foreach ($colWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
        $style = $sheet->getStyle('A1:Q1');
        $style->getFont()->setSize(11)->setBold(true);
        $style->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('236e09');
        $style->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
    }
}
