<?php

namespace App\Modules\Invoices\Controllers;

use App\Controllers\BaseController;
use App\Modules\Invoices\Models\InvoicesModel;
use App\Modules\Workorders\Models\WorkordersModel;
use App\Models\GeneralModel;
use TCPDF;

class Invoices extends BaseController
{
    protected $invoicesModel;
	protected $generalModel;

    public function __construct()
    {
        $this->invoicesModel   = new InvoicesModel();
        $this->generalModel   = new GeneralModel();
    }

    /**
     * Predios
     * @since 04/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function index()
    {
        $arrParam = [];

        if ($this->request->getPost('date')) {
            $arrParam['date'] = $this->request->getPost('date');
        }
        if ($this->request->getPost('idJobCode')) {
            $arrParam['idJobCode'] = $this->request->getPost('idJobCode');
        }
        if ($this->request->getPost('status')) {
            $arrParam['status'] = $this->request->getPost('status');
        }
        if ($this->request->getPost('number')) {
            $arrParam['number'] = $this->request->getPost('number');
        }

        $data['info'] = $this->invoicesModel->get_invoices($arrParam);

        $data['jobs'] = $this->generalModel->get_basic_search([
            'table'  => 'param_jobs',
            'order'  => 'job_description',
            'column' => 'state',
            'id'     => 1,
        ]);

        $data['statusList'] = $this->generalModel->get_basic_search([
            'table'  => 'param_status',
            'order'  => 'status_order',
            'column' => 'status_key',
            'id'     => 'invoices',
        ]);

        return $this->render('App\Modules\Invoices\Views\invoices', $data);
    }

    /**
     * Form Add Invoice
     * @since 5/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function add_invoice($id = 'x')
    {
        $data['information'] = false;
        $data['deshabilitar'] = '';

        $data['jobs'] = $this->generalModel->get_basic_search([
            'table'  => 'param_jobs',
            'order'  => 'job_description',
            'column' => 'state',
            'id'     => 1,
        ]);

        $data['statusList'] = $this->generalModel->get_basic_search([
            'table'  => 'param_status',
            'order'  => 'status_order',
            'column' => 'status_key',
            'id'     => 'invoices',
        ]);

        $data['nextInvoiceNumber'] = $this->invoicesModel->getNextInvoiceNumber();

        if ($id != 'x') {
            $arrParam = ['idInvoice' => $id];

            $data['idInvoice']   = $id;
            $data['information'] = $this->invoicesModel->get_invoices($arrParam);
            $data['items']       = $this->invoicesModel->get_invoices_items($arrParam);
            $data['files']       = $this->invoicesModel->get_files($id);
            $data['payments']    = $this->invoicesModel->get_payments($id);

            if (!$data['information']) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('ERROR!!! - You are in the wrong place.');
            }
        }

        return $this->render('App\Modules\Invoices\Views\form_invoice', $data);
    }

    /**
     * Save Invoice
     * @since 05/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function save_invoice()
    {
        $post              = $this->request->getPost();
        $idInvoiceInicial  = $post['hddIdentificador'] ?? '';
        $idUser            = $this->session->get('id');

        $msj = $idInvoiceInicial != ''
            ? 'You have updated the Invoice, continue uploading the information.'
            : 'You have added a new Invoice, continue uploading the information.';

        $idInvoice = $this->invoicesModel->add_invoice($post, $idUser);

        if ($idInvoice) {
            if ($idInvoiceInicial == '') {
                $isWoOrClaim = $post['link_to'] ?? '';

                if ($isWoOrClaim == 'wo') {
                    $workordersModel    = new WorkordersModel();
                    $arrParam           = ['idWorkOrder' => $post['list_work_order']];
                    $workorderPersonal  = $workordersModel->get_workorder_personal($arrParam);
                    $workorderMaterials = $workordersModel->get_workorder_materials($arrParam);
                    $workorderReceipt   = $workordersModel->get_workorder_receipt($arrParam);
                    $workorderEquipment = $workordersModel->get_workorder_equipment($arrParam);
                    $workorderOcasional = $workordersModel->get_workorder_ocasional($arrParam);

                    $this->insertInvoiceItems($workorderPersonal, $idInvoice, 'personal');
                    $this->insertInvoiceItems($workorderMaterials, $idInvoice, 'materials');
                    $this->insertInvoiceItems($workorderEquipment, $idInvoice, 'equipment');
                    $this->insertInvoiceItems($workorderOcasional, $idInvoice, 'ocasional');
                    $this->insertInvoiceItems($workorderReceipt, $idInvoice, 'receipt');

                } elseif ($isWoOrClaim == 'claim') {
                    $claimInfo = $this->generalModel->get_job_detail_claims_info(['idClaim' => $post['list_work_order']]);
                    $this->insertInvoiceItems($claimInfo, $idInvoice, 'claim');
                }
            }

            session()->setFlashdata('retornoExito', $msj);
            return $this->response->setJSON([
                'result'    => true,
                'mensaje'   => $msj,
                'idInvoice' => $idInvoice,
            ]);
        }

        session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        return $this->response->setJSON([
            'result'    => 'error',
            'mensaje'   => 'Error!!! Ask for help.',
            'idInvoice' => '',
        ]);
    }

    /**
     * Claim list
     * @since 10/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function claimList()
    {
        $jobCode = $this->request->getPost('jobCode');
        $list    = $this->invoicesModel->get_claim_by_job_code($jobCode);

        $html = "<option value=''>Select...</option>";
        foreach ($list as $fila) {
            $html .= "<option value='" . esc($fila['id_claim'], 'attr') . "'>" . esc($fila['id_claim']) . ' - ' . esc($fila['observation']) . '</option>';
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody($html);
    }

    /**
     * Cargo modal - formulario de captura items
     * @since 10/03/2026
     * @review 20/05/2026 - new CI4 version
     */
    public function cargarModalItems()
    {
        $data['idInvoice'] = $this->request->getPost('idInvoice');

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Invoices\Views\modal_items', $data));
    }

    /**
     * Save items
     * @since 11/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function save_item()
    {
        $post = $this->request->getPost();
        $data = ['idRecord' => $post['hddIdInvoice']];

        if ($this->invoicesModel->saveItem($post)) {
            $data['result'] = true;
            session()->setFlashdata('retornoExito', 'You have added a new record!!');
        } else {
            $data['result'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Save all information
     * @since 11/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function save_all()
    {
        $post        = $this->request->getPost();
        $ids         = $post['id_item'];
        $descriptions = $post['description'];
        $quantities  = $post['quantity'];
        $units       = $post['unit'];
        $rates       = $post['rate'];
        $markup      = $post['markup'];

        for ($i = 0; $i < count($ids); $i++) {
            $data = [
                'description' => $descriptions[$i],
                'quantity'    => $quantities[$i],
                'unit'        => $units[$i],
                'rate'        => $rates[$i],
                'markup'      => $markup[$i],
                'value'       => $rates[$i] * $quantities[$i],
            ];
            $this->invoicesModel->updateItem($ids[$i], $data);
        }

        return redirect()->to(base_url('invoices/add_invoice/' . $post['hddIdInvoice']));
    }

    /**
     * Delete item record
     * @param int $idItem
     * @param int $idInvoice
     * @review 20/05/2026 - new CI4 version
     */
    public function delete_item($idItem, $idInvoice)
    {
        if (empty($idItem) || empty($idInvoice)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ERROR!!! - You are in the wrong place.');
        }

        $arrParam = [
            'table'      => 'invoices_items',
            'primaryKey' => 'id_invoices_items',
            'id'         => $idItem,
        ];

        if ($this->generalModel->deleteRecord($arrParam)) {
            session()->setFlashdata('retornoExito', 'You have deleted one Item.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('invoices/add_invoice/' . $idInvoice));
    }

    public function delete_payment($idPayment, $idInvoice)
    {
        if ($this->invoicesModel->delete_payment($idPayment)) {
            session()->setFlashdata('retornoExito', 'You have deleted one Payment.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('invoices/add_invoice/' . $idInvoice));
    }

    /**
     * Generate INVOICE Report in PDF
     * @param int $idInvoice
     * @since 12/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function generaInvoicePDF($idInvoice, $returnAsString = false)
    {
        $arrParam      = ['idInvoice' => $idInvoice];
        $data['info']  = $this->invoicesModel->get_invoices($arrParam);
        $data['logo']  = FCPATH . 'images/logo_black.png';

        if (empty($data['info'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No Invoice found');
        }

        $data['items'] = $this->invoicesModel->get_invoices_items($arrParam);

        $pdf = new class() extends TCPDF {
            public function Footer()
            {
                $this->SetY(-1);
                $this->Image(
                    FCPATH . 'images/flowers.png',
                    3,
                    $this->GetY() - 80,
                    30,
                    0
                );

                $this->SetFont('helvetica', '', 8);
                $this->SetTextColor(0, 0, 0);

                $pageWidth = $this->getPageWidth();
                $margin    = 20;

                $this->SetY(-25);
                $this->Cell(
                    0,
                    5,
                    'Send electronic payments to: Invoice@Lev-west.com       GST No.: 791493158RT0001',
                    0,
                    1,
                    'C'
                );

                $this->SetFont('helvetica', '', 7.5);
                $this->SetTextColor(120, 120, 120);
                $this->SetY($this->GetY() + 2);

                $legal = 'All invoices are due and payable within fifteen (15) days of the invoice date. Any invoice outstanding beyond thirty (30) days will be subject to an administrative fee of eight percent (8%) per month until payment is received in full.';

                $this->MultiCell(
                    $pageWidth - 2 * $margin,
                    4,
                    $legal,
                    0,
                    'C',
                    false,
                    1,
                    '',
                    '',
                    true,
                    0,
                    false,
                    true,
                    0,
                    'T',
                    false
                );
            }
        };
        $pdf->SetCreator('Lev West');
        $pdf->SetAuthor('Lev West');
        $pdf->SetTitle('Invoice');
        $pdf->setPrintHeader(false);
        $pdf->setFooterMargin(20);
        $pdf->setPrintFooter(true);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 80);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->AddPage();

        $pdf->StartTransform();
        $pdf->Rotate(90, 15, 70);
        $pdf->SetFont('helvetica', 'B', 36);
        $pdf->Text(15, 70, 'INVOICE');
        $pdf->StopTransform();

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetXY(35, 10);

        $html = view('App\Modules\Invoices\Views\report_invoice', $data);
        $pdf->writeHTML($html, true, false, false, false, '');

        $fileName = $data['info'][0]['job_description'] . 'invoice_' . $data['info'][0]['number'] . '.pdf';

        if ($returnAsString) {
            return $pdf->Output($fileName, 'S');
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output($fileName, 'S'));
    }

    public function upload_file($idInvoice)
    {
        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid() || $file->getName() === '') {
            return redirect()->to(base_url('invoices/add_invoice/' . $idInvoice));
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];

        if (!in_array(strtolower($file->getExtension()), $allowedTypes)) {
            session()->setFlashdata('retornoError', 'File type not allowed.');
            return redirect()->to(base_url('invoices/add_invoice/' . $idInvoice));
        }

        if ($file->getSize() > 10000 * 1024) {
            session()->setFlashdata('retornoError', 'File too large.');
            return redirect()->to(base_url('invoices/add_invoice/' . $idInvoice));
        }

        $fileName = $file->getName();
        $file->move(FCPATH . 'files/invoices/', $fileName, true);

        $this->invoicesModel->save_file([
            'fk_id_invoice' => $idInvoice,
            'file_name'     => $fileName,
        ]);

        return redirect()->to(base_url('invoices/add_invoice/' . $idInvoice));
    }

    public function add_payment($idInvoice)
    {
        $post = $this->request->getPost();

        $this->invoicesModel->save_payment([
            'fk_id_invoice' => $idInvoice,
            'amount'        => $post['amount'],
            'date_paid'     => $post['date_paid'],
            'reference'     => $post['reference'],
        ]);

        return redirect()->to(base_url('invoices/add_invoice/' . $idInvoice));
    }

    public function sendInvoiceEmail($idInvoice)
    {
        $arrParam = ['idInvoice' => $idInvoice];
        $invoice  = $this->invoicesModel->get_invoices($arrParam);

        if (!$invoice) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invoice not found');
        }

        $invoice       = $invoice[0];
        $clientEmail   = $invoice['email'];
        $invoiceNumber = $invoice['number'];
        $subject       = $invoice['job_description'] . ' - Invoice #' . $invoiceNumber;

        $pdfContent = $this->generaInvoicePDF($idInvoice, true);

        $tmpFile = tempnam(sys_get_temp_dir(), 'invoice_') . '.pdf';
        file_put_contents($tmpFile, $pdfContent);

        $emailSvc = \Config\Services::email();
        $emailSvc->setFrom('no-reply@lev-west.com', 'Lev-West');
        $emailSvc->setTo($clientEmail);
        $emailSvc->setSubject($subject);
        $emailSvc->setMessage($this->invoiceEmailTemplate($invoice, $idInvoice));
        $emailSvc->setMailType('html');
        $emailSvc->attach($tmpFile, '', 'invoice_' . $invoiceNumber . '.pdf', 'application/pdf');
        $emailSvc->send();

        unlink($tmpFile);

        $this->generalModel->updateRecord([
            'table'      => 'invoices',
            'primaryKey' => 'id_invoice',
            'id'         => $idInvoice,
            'column'     => 'invoice_status',
            'value'      => 'sent',
        ]);

        session()->setFlashdata('retornoExito', 'Invoice email sent successfully.');
        return redirect()->to(base_url('invoices/add_invoice/' . $idInvoice));
    }

    /**
     * Work Order list
     * @since 24/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function woList()
    {
        $jobCode = $this->request->getPost('jobCode');
        $list    = $this->invoicesModel->get_wo_job_code($jobCode);

        $html = "<option value=''>Select...</option>";
        foreach ($list as $fila) {
            $html .= "<option value='" . esc($fila['id_workorder'], 'attr') . "'>" . esc($fila['id_workorder']) . ' - ' . esc($fila['observation']) . '</option>';
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody($html);
    }

    private function insertInvoiceItems($items, $idInvoice, $type)
    {
        if (!$items) {
            return;
        }

        foreach ($items as $item) {
            switch ($type) {
                case 'personal':
                    $description = $item['employee_type'] . ' - ' . $item['description'] . ' by ' . $item['name'];
                    $quantity    = $item['hours'];
                    $unit        = 'Hours';
                    $rate        = $item['rate'];
                    $markup      = 0;
                    $value       = $item['value'];
                    break;

                case 'materials':
                    $description = $item['description'] . ' - ' . $item['material'];
                    if ($item['markup'] > 0) {
                        $description .= ' - Plus M.U.';
                    }
                    $quantity = $item['quantity'];
                    $unit     = $item['unit'];
                    $rate     = $item['rate'];
                    $markup   = $item['markup'];
                    $value    = $item['value'];
                    break;

                case 'equipment':
                    $attachment = '';
                    if ($item['fk_id_attachment'] != '' && $item['fk_id_attachment'] != 0) {
                        $attachment = 'ATTACHMENT: ' . $item['attachment_number'] . ' - ' . $item['attachment_description'] . ' ';
                    }
                    if ($item['fk_id_type_2'] == 8) {
                        $equipment   = $item['miscellaneous'] . ' - ' . $item['other'];
                        $description = preg_replace('([^A-Za-z0-9 ])', ' ', $item['description']);
                    } else {
                        $equipment   = 'Unit #: ' . $item['unit_number'] . ' Make: ' . $item['make'] . ' Model: ' . $item['model'];
                        $description = $item['v_description'] . ' - ' . preg_replace('([^A-Za-z0-9 ])', ' ', $item['description']);
                    }
                    $description = $attachment . $equipment . ' Description: ' . $description . ', operated by ' . $item['operatedby'];
                    $quantity    = $item['hours'];
                    $unit        = 'Hours';
                    $rate        = $item['rate'];
                    $markup      = 0;
                    $value       = $item['value'];
                    break;

                case 'ocasional':
                    $description = $item['description'];
                    if ($item['markup'] > 0) {
                        $description .= ' - Plus M.U.';
                    }
                    $quantity = $item['quantity'] * $item['hours'];
                    $unit     = $item['unit'];
                    $rate     = $item['rate'];
                    $markup   = $item['markup'];
                    $value    = $item['value'];
                    break;

                case 'receipt':
                    $description = $item['description'] . ' - ' . $item['place'];
                    if ($item['markup'] > 0) {
                        $description .= ' - Plus M.U.';
                    }
                    $quantity = 1;
                    $unit     = 'Receipt';
                    $rate     = $item['price'] / 1.05;
                    $markup   = $item['markup'];
                    $value    = $item['value'];
                    break;

                case 'claim':
                    $description = $item['chapter_number'] . '.' . $item['item'] . ' ' . $item['description'];
                    $quantity    = $item['quantity_claim'] == 0 ? 1 : $item['quantity_claim'];
                    $unit        = $item['unit'];
                    $rate        = $item['quantity_claim'] != 0 ? $item['unit_price'] : $item['cost'];
                    $markup      = 0;
                    $value       = $item['cost'];
                    break;

                default:
                    return;
            }

            $this->invoicesModel->insertItem([
                'fk_id_invoice' => $idInvoice,
                'description'   => $description,
                'quantity'      => $quantity,
                'unit'          => $unit,
                'rate'          => $rate,
                'markup'        => $markup,
                'value'         => $value,
            ]);
        }
    }

    private function invoiceEmailTemplate($invoice, $idInvoice)
    {
        $invoiceLink = base_url('invoices/genera_invoice_pdf/' . $idInvoice);

        return '
        <html>
        <body style="font-family:Arial;background:#f4f4f4;padding:20px;">
            <table width="600" align="center" style="background:#ffffff;border-collapse:collapse">
                <tr>
                    <td style="background:#2c3e50;color:#fff;padding:15px;font-size:20px;">Lev-West</td>
                </tr>
                <tr>
                    <td style="padding:20px">
                        <p>Dear Customer,</p>
                        <p>We hope you are doing well. Please find your invoice attached to this email.</p>
                        <p>You can also view or download it using the link below:</p>
                        <p style="text-align:center;margin:30px 0">
                            <a href="' . $invoiceLink . '" style="background:#27ae60;color:#fff;padding:12px 25px;text-decoration:none;border-radius:4px;font-weight:bold;">
                                View Invoice
                            </a>
                        </p>
                        <p>If you have any questions regarding this invoice, please feel free to contact us.</p>
                        <p>Best regards,<br><b>Lev-West Team</b></p>
                    </td>
                </tr>
                <tr>
                    <td style="background:#f2f2f2;padding:15px;font-size:12px;color:#777;text-align:center">
                        This is an automated message from the Lev-West system.
                    </td>
                </tr>
            </table>
        </body>
        </html>';
    }
}
