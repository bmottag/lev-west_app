<?php
namespace App\Modules\Claims\Controllers;

use App\Controllers\BaseController;
use App\Modules\Claims\Models\ClaimsModel;
use App\Models\GeneralModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Claims extends BaseController
{
    protected $claimsModel;
    protected $generalModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->claimsModel     = new ClaimsModel();
        $this->generalModel = new GeneralModel();
    }

    /**
     * Form Workorders
     * @since 3/2/2021
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function index($idJob)
    {
        $arrParam = ['idJob' => $idJob];
        $data = [
            'tituloListado' => 'LIST OF CLAIMS',
            'jobInfo'       => $this->generalModel->get_job($arrParam),
            'claimsInfo'    => $this->claimsModel->get_claims($arrParam),
        ];
        return $this->render('App\Modules\Claims\Views\claims', $data);
    }

    /**
     * Cargo modal - formulario CLAIMS
     * @since 3/2/2021
     * @review 19/05/2026 - new CI4 version
     */
    public function cargarModalClaim()
    {
        $idJob    = $this->request->getPost('idJob');
        $arrParam = ['idJob' => $idJob];

        $claim = $this->claimsModel->get_claims(['idJob' => $idJob, 'limit' => 1]);

        $data = [
            'information'     => false,
            'jobs'            => $this->generalModel->get_job($arrParam),
            'nextClaimNumber' => $claim ? ($claim[0]['claim_number'] + 1) : 1,
            'lastObservation' => $claim ? $claim[0]['observation_claim'] : false,
        ];

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Claims\Views\claims_modal', $data));
    }

    /**
     * Guardar claim
     * @since 3/2/2021
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function guardar_claims()
    {
        $post           = $this->request->getPost();
        $idClaimInicial = $post['hddId'] ?? '';
        $msj            = $idClaimInicial
            ? 'You have updated the Claim, continue uploading the information.'
            : 'You have added a Claim, continue uploading the information.';

        $data          = [];
        $resultSearch  = false;

        if ($idClaimInicial == '') {
            $resultSearch = $this->claimsModel->get_claims([
                'idJob'             => $post['id_job'],
                'claimNumberSearch' => $post['claimNumber'],
            ]);
        }

        if ($resultSearch) {
            $data['status']  = 'error';
            $data['mensaje'] = ' Error. Duplicate entry: This claim number already exists for the selected job.';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Duplicate entry: This claim number already exists for the selected job.');
        } else {
            $idClaim = $this->claimsModel->guardarClaim($post);
            if ($idClaim) {
                if (!$idClaimInicial) {
                    $this->claimsModel->add_claim_state([
                        'idClaim' => $idClaim,
                        'message' => 'New Claim.',
                        'state'   => 1,
                    ]);
                }
                $data['idRecord'] = $idClaim;
                $data['status']   = 'success';
                session()->setFlashdata('retornoExito', '<strong>Right!</strong> ' . $msj);
            } else {
                $data['status'] = 'error';
                session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        }

        return $this->response->setJSON($data);
    }

    /**
     * Form Upload APU to claim
     * @since 12/05/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function upload_apu($idClaim = 'x')
    {
        $arrParam    = ['idClaim' => $idClaim];
        $claimsInfo  = $this->claimsModel->get_claims($arrParam);
        $chapterList = $this->generalModel->get_chapter_list(['idJob' => $claimsInfo[0]['fk_id_job']]);

        $jobDetailsByChapter = [];
        if ($chapterList) {
            foreach ($chapterList as $chapter) {
                $jobDetailsByChapter[$chapter['chapter_number']] = $this->generalModel->get_job_detail_claims_info([
                    'idJob'         => $claimsInfo[0]['fk_id_job'],
                    'chapterNumber' => $chapter['chapter_number'],
                    'idClaim'       => $claimsInfo[0]['id_claim'],
                    'status'        => 1,
                ]);
            }
        }

        $data = [
            'claimsInfo'          => $claimsInfo,
            'claimsHistory'       => $this->claimsModel->get_claims_history($arrParam),
            'chapterList'         => $chapterList,
            'jobDetailsByChapter' => $jobDetailsByChapter,
        ];

        return $this->renderTopOnly('App\Modules\Claims\Views\form_upload_info_claim', $data);
    }

    /**
     * Update Claim info
     * @since 12/05/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function update_claim()
    {
        $idClaim      = $this->request->getPost('hddIdClaim');
        $records      = $this->request->getPost('records');
        $successCount = 0;
        $errorCount   = 0;

        foreach ($records as $record) {
            $dataToSave = [
                'fk_id_claim'      => $idClaim,
                'fk_id_job_detail' => $record['id_job_detail'],
                'quantity'         => $record['quantity'],
                'cost'             => $record['quantity']
                    ? $record['unit_price'] * $record['quantity']
                    : $record['cost'],
            ];

            if ($this->claimsModel->updateInfoAPU($dataToSave)) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        return $this->response->setJSON([
            'status'  => $errorCount === 0 ? 'success' : 'error',
            'message' => $errorCount === 0
                ? "$successCount records saved successfully!"
                : "$errorCount records failed to save.",
        ]);
    }

    /**
     * Form Add APU to Claim
     * Muestra lista de APU y los que estan asignados al CLAIM
     * @since 24/05/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function add_apu($idJob, $idClaim)
    {
        if (empty($idJob) || empty($idClaim)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ERROR!!! - You are in the wrong place.');
        }

        $arrParam    = ['idJob' => $idJob];
        $chapterList = $this->generalModel->get_chapter_list($arrParam);

        // IDs ya asignados a este claim (un solo query para todos los capítulos)
        $assignedRaw = $this->generalModel->get_job_detail_claims_info(['idJob' => $idJob, 'idClaim' => $idClaim]);
        $assignedIds = [];
        if ($assignedRaw) {
            foreach ($assignedRaw as $row) {
                $assignedIds[$row['id_job_detail']] = true;
            }
        }

        $jobDetailsByChapter = [];
        if ($chapterList) {
            foreach ($chapterList as $chapter) {
                $details = $this->generalModel->get_job_detail(['idJob' => $idJob, 'chapterNumber' => $chapter['chapter_number']]);
                if ($details) {
                    foreach ($details as &$detail) {
                        $detail['found'] = isset($assignedIds[$detail['id_job_detail']]);
                    }
                    unset($detail);
                }
                $jobDetailsByChapter[$chapter['chapter_number']] = $details;
            }
        }

        $data = [
            'chapterList'         => $chapterList,
            'jobInfo'             => $this->generalModel->get_job($arrParam),
            'idJob'               => $idJob,
            'idClaim'             => $idClaim,
            'jobDetailsByChapter' => $jobDetailsByChapter,
        ];

        return $this->renderTopOnly('App\Modules\Claims\Views\form_add_apu', $data);
    }

    /**
     * Asignar APU al claim
     * @since 25/05/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function save_claim_apu()
    {
        $post = $this->request->getPost();
        $data = ['idRecord' => $post['hddId'] ?? null];

        $apu = $post['apu'] ?? null;
        if ($apu) {
            if ($this->claimsModel->saveClaimAPU($post)) {
                $data['status'] = 'success';
                session()->setFlashdata('retornoExito', 'LIC assigned to the claim!!');
            } else {
                $data['status'] = 'error';
                session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
            }
        } else {
            $data['status']  = 'error';
            $data['mensaje'] = ' You have to select LIC';
            session()->setFlashdata('retornoError', 'You have to select LIC');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Delete WO de Claims
     * @since 4/2/2021
     * @review 18/05/2026 - new CI4 version
     */
    public function delete_wo_from_claim()
    {
        $idCompuesto = $this->request->getPost('identificador');
        $porciones   = explode('-', $idCompuesto);
        $idWO        = $porciones[0];

        $data = ['idRecord' => $porciones[1]];

        $arrParam = [
            'table'      => 'workorder',
            'primaryKey' => 'id_workorder',
            'id'         => $idWO,
            'column'     => 'fk_id_claim',
            'value'      => 0,
        ];

        if ($this->generalModel->updateRecord($arrParam)) {
            $data['status'] = 'success';
            session()->setFlashdata('retornoExito', 'You have delete the W.O. from the claim.');
        } else {
            $data['status']  = 'error';
            $data['mensaje'] = 'Error!!! Contactarse con el Administrador.';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Cargo modal - formulario Estado Claim
     * @since 5/02/2021
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function cargarModalClaimState()
    {
        $data = ['idClaim' => $this->request->getPost('idClaim')];

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Claims\Views\claim_state_modal', $data));
    }

    /**
     * Guardar orden de trabajo estado
     * @since 29/1/2021
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function save_claim_state()
    {
        $idClaim    = $this->request->getPost('hddIdClaim');
        $claimState = (int) $this->request->getPost('state');
        $message    = $this->request->getPost('message');
        $data       = ['idRecord' => $idClaim];

        $arrParam = [
            'idClaim' => $idClaim,
            'message' => $message,
            'state'   => $claimState,
        ];

        if ($this->claimsModel->add_claim_state($arrParam)) {
            $this->claimsModel->update_claim($arrParam);

            $msj = 'You have updated the information!';

            if ($claimState == 2 || $claimState == 6) {
                $stateMSJ = $claimState == 2 ? 'Send to Client' : 'Closed';
                $msj     .= ' And Word Order state changed to <strong>' . $stateMSJ . '</strong>.';

                $WOList = $this->generalModel->get_workorder_info(['idClaim' => $idClaim]);
                if ($WOList) {
                    $this->claimsModel->updateWOStateFromClaimChange($WOList, $claimState, $message);
                }
            }

            $data['status'] = 'success';
            session()->setFlashdata('retornoExito', '<strong>Right!</strong> ' . $msj);
        } else {
            $data['status'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Claim history
     * @since 20/05/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function claim_history($idClaim = 'x')
    {
        $arrParam    = ['idClaim' => $idClaim];
        $claimsInfo  = $this->claimsModel->get_claims($arrParam);
        $idJob       = $claimsInfo[0]['fk_id_job'];
        $chapterList = $this->generalModel->get_chapter_list(['idJob' => $idJob]);
        $allClaims   = $this->claimsModel->get_claims(['idJob' => $idJob, 'order' => 'asc']);

        $jobDetailsByChapter = [];
        if ($chapterList) {
            foreach ($chapterList as $chapter) {
                $jobDetailsByChapter[$chapter['chapter_number']] = $this->generalModel->get_job_detail([
                    'idJob'         => $idJob,
                    'chapterNumber' => $chapter['chapter_number'],
                ]);
            }
        }

        // Mapa [id_claim][id_job_detail] => row — un query por claim, cero queries en la vista
        $claimDetailMap = [];
        if ($allClaims) {
            foreach ($allClaims as $claim) {
                $rows = $this->generalModel->get_job_detail_claims_info(['idJob' => $idJob, 'idClaim' => $claim['id_claim']]);
                $claimDetailMap[$claim['id_claim']] = [];
                if ($rows) {
                    foreach ($rows as $row) {
                        $claimDetailMap[$claim['id_claim']][$row['id_job_detail']] = $row;
                    }
                }
            }
        }

        $data = [
            'claimsInfo'          => $claimsInfo,
            'claimsHistory'       => $this->claimsModel->get_claims_history($arrParam),
            'chapterList'         => $chapterList,
            'allClaims'           => $allClaims,
            'jobDetailsByChapter' => $jobDetailsByChapter,
            'claimDetailMap'      => $claimDetailMap,
        ];

        return $this->renderTopOnly('App\Modules\Claims\Views\claim_history', $data);
    }

    /**
     * Generate Project Progress Report for claim in XLS
     * @param int $idJob
     * @since 22/5/2025
     * @author BMOTTAG
     * @review 19/05/2026 - new CI4 version
     */
    public function generaProgressreportXLS($idJob)
    {
        $arrParam  = ['idJob' => $idJob];
        $jobInfo   = $this->generalModel->get_job($arrParam);
        $chapters  = $this->generalModel->get_chapter_list($arrParam);
        $allClaims = $this->claimsModel->get_claims(['idJob' => $idJob, 'order' => 'asc']);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Project Progress Report');
        $sheet = $spreadsheet->getActiveSheet();

        // LOGO
        $logoPath = FCPATH . 'images/logo.png';
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Company Logo');
            $drawing->setPath($logoPath);
            $drawing->setHeight(120);
            $drawing->setCoordinates('B1');
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(10);
            $drawing->setWorksheet($sheet);
        }

        // Project Information
        foreach (['D3:E3','D4:E4','D5:E5','D6:E6','F3:G3','F4:G4','F5:G5','F6:G6'] as $range) {
            $sheet->mergeCells($range);
        }
        $sheet->getStyle('D3:E6')->getFont()->setBold(true);
        foreach (['D3:G3','D4:G4','D5:G5','D6:G6'] as $range) {
            $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        $sheet->getStyle('D3:G6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('D3:G6')->getFont()->setSize(14);

        $sheet->setCellValue('D3', 'Project Name:')->setCellValue('D4', 'Project No:')
              ->setCellValue('D5', 'Client:')->setCellValue('D6', 'Date:')
              ->setCellValue('F3', $jobInfo[0]['job_name'])->setCellValue('F4', $jobInfo[0]['job_code'])
              ->setCellValue('F5', $jobInfo[0]['company_name'])->setCellValue('F6', date('m/d/Y'));

        // Project Description
        $sheet->mergeCells('D8:G9');
        $sheet->getStyle('D8:G9')->getFont()->setBold(true);
        $sheet->getStyle('D8:G9')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('D8:G9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D8:G9')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('D8:G9')->getFont()->setSize(14);
        $sheet->getStyle('D8:G9')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('D8:G9')->getFill()->getStartColor()->setARGB('FFCCE5FF');
        $sheet->setCellValue('D8', $jobInfo[0]['job_description']);

        // Headers row
        $sheet->setCellValue('B11', 'Item')->setCellValue('C11', 'Description')
              ->setCellValue('D11', 'Unit')->setCellValue('E11', 'Qty')
              ->setCellValue('F11', 'Unit Price')->setCellValue('G11', 'Extended Amount');

        $colIndex = 8;
        if ($allClaims) {
            foreach ($allClaims as $claim) {
                $colQty  = Coordinate::stringFromColumnIndex($colIndex);
                $colCost = Coordinate::stringFromColumnIndex($colIndex + 1);
                $sheet->setCellValue($colQty . '11', 'Qty Claim ' . $claim['claim_number']);
                $sheet->setCellValue($colCost . '11', 'Cost Claim ' . $claim['claim_number']);
                $sheet->getColumnDimension($colQty)->setWidth(18);
                $sheet->getColumnDimension($colCost)->setWidth(18);
                $colIndex += 2;
            }

            $colGreen       = Coordinate::stringFromColumnIndex($colIndex - 1);
            $colQtyComplete = Coordinate::stringFromColumnIndex($colIndex);
            $colCostComplete= Coordinate::stringFromColumnIndex($colIndex + 1);
            $colPerComplete = Coordinate::stringFromColumnIndex($colIndex + 2);

            $sheet->setCellValue($colQtyComplete . '11', 'Qty to complete')
                  ->setCellValue($colCostComplete . '11', 'Cost to complete')
                  ->setCellValue($colPerComplete . '11', '% completed');

            $sheet->getColumnDimension($colQtyComplete)->setWidth(20);
            $sheet->getColumnDimension($colCostComplete)->setWidth(20);
            $sheet->getColumnDimension($colPerComplete)->setWidth(20);

            $colClaimIni = Coordinate::stringFromColumnIndex(8);
            $sheet->getStyle($colClaimIni . '11:' . $colPerComplete . '11')->getFont()->setBold(true);
            $sheet->getStyle($colQtyComplete . '11:' . $colPerComplete . '11')
                  ->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle($colQtyComplete . '11:' . $colPerComplete . '11')
                  ->getFill()->getStartColor()->setARGB('FFFFCCCC');

            $colIndex += 3;
        }

        $finalCol = Coordinate::stringFromColumnIndex($colIndex - 1);
        $sheet->getStyle('B11:' . $finalCol . '11')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Data rows
        $j = 12;
        foreach ($chapters as $chapter) {
            $j++;
            $ini = $j;
            $sheet->setCellValue('B' . $j, $chapter['chapter_number'] . '. ' . $chapter['chapter_name']);
            $sheet->getStyle('B' . $j . ':' . $finalCol . $j)->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle('B' . $j . ':' . $finalCol . $j)->getFill()->getStartColor()->setARGB('FFD9D9D9');
            $sheet->getStyle('B' . $j . ':' . $finalCol . $j)->getFont()->setBold(true);

            $jobDetails = $this->generalModel->get_job_detail(['idJob' => $idJob, 'chapterNumber' => $chapter['chapter_number']]);

            if ($jobDetails) {
                $sumExtended = 0;
                foreach ($jobDetails as $detail) {
                    $j++;
                    $sumExtended += $detail['extended_amount'];
                    $sheet->setCellValue('B' . $j, $detail['chapter_number'] . '.' . $detail['item'])
                          ->setCellValue('C' . $j, $detail['description'])
                          ->setCellValue('D' . $j, $detail['unit'])
                          ->setCellValue('E' . $j, $detail['quantity'])
                          ->setCellValue('F' . $j, $detail['unit_price'])
                          ->setCellValue('G' . $j, $detail['extended_amount']);

                    $sheet->getStyle('F' . $j)->getNumberFormat()->setFormatCode('"$"#,##0.00');
                    $sheet->getStyle('G' . $j)->getNumberFormat()->setFormatCode('"$"#,##0.00');

                    if ($allClaims) {
                        $colIdx      = 8;
                        $subTotalQty = 0;
                        $subTotalCost= 0;
                        foreach ($allClaims as $claim) {
                            $claimInfo = $this->generalModel->get_job_detail_claims_info([
                                'idClaim'     => $claim['id_claim'],
                                'idJobDetail' => $detail['id_job_detail'],
                            ]);
                            $colQty  = Coordinate::stringFromColumnIndex($colIdx);
                            $colCost = Coordinate::stringFromColumnIndex($colIdx + 1);

                            $qty  = $claimInfo[0]['quantity_claim'] ?? '';
                            $cost = $claimInfo[0]['cost'] ?? '';
                            $subTotalQty  += $qty  ?: 0;
                            $subTotalCost += $cost ?: 0;

                            $sheet->setCellValue($colQty . $j, $qty);
                            $sheet->setCellValue($colCost . $j, $cost);
                            if ($cost !== '') {
                                $sheet->getStyle($colCost . $j)->getNumberFormat()->setFormatCode('"$"#,##0.00');
                            }
                            $colIdx += 2;
                        }

                        $totalQty  = $detail['quantity'] - $subTotalQty;
                        $totalCost = $detail['extended_amount'] - $subTotalCost;
                        $percentage= $qty ? (100 * ($totalQty / $qty)) : 0;

                        $colQtyC = Coordinate::stringFromColumnIndex($colIdx);
                        $colCostC= Coordinate::stringFromColumnIndex($colIdx + 1);
                        $colPerC = Coordinate::stringFromColumnIndex($colIdx + 2);

                        $sheet->setCellValue($colQtyC . $j, $totalQty)
                              ->setCellValue($colCostC . $j, $totalCost)
                              ->setCellValue($colPerC . $j, $percentage . '%');
                        $sheet->getStyle($colCostC . $j)->getNumberFormat()->setFormatCode('"$"#,##0.00');
                    }
                }

                $j++;
                $sheet->setCellValue('B' . $j, 'SUB-TOTAL PART ' . $chapter['chapter_number'])
                      ->setCellValue('G' . $j, $sumExtended);
                $sheet->getStyle('G' . $j)->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->getStyle('B' . $j . ':' . $finalCol . $j)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $j . ':' . $finalCol . $j)->getFont()->setSize(14);
                $sheet->getStyle('B' . $j . ':' . $finalCol . $j)->getFill()->setFillType(Fill::FILL_SOLID);
                $sheet->getStyle('B' . $j . ':' . $finalCol . $j)->getFill()->getStartColor()->setARGB('FFCCE5FF');
                $sheet->getStyle('B' . $j . ':' . $finalCol . $j)->getFont()->setBold(true);
                $sheet->mergeCells('B' . $j . ':C' . $j);
                $sheet->getStyle('B' . $ini . ':' . $finalCol . $j)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }

            $j++;
        }

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(140);
        $sheet->getStyle('C')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(25);

        $sheet->getStyle('B11:' . $finalCol . '11')->getFont()->setSize(11);
        $sheet->getStyle('B11:' . $finalCol . '11')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('B11:' . (isset($colGreen) ? $colGreen : $finalCol) . '11')->getFill()->getStartColor()->setARGB('FFCCFFCC');
        $sheet->getStyle('B11:' . $finalCol . '11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B11:' . $finalCol . '11')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename=' . $jobInfo[0]['job_code'] . ' Project Progress Report.xlsx');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
