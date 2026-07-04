<?php

namespace App\Modules\Invoices\Models;

use CodeIgniter\Model;

class InvoicesModel extends Model
{
    /**
     * Predios
     * @since 27/02/2025
     * @review 20/05/2026 - new CI4 version
     */
    public function get_invoices($arrData)
    {
        $builder = $this->db->table('invoices I');
        $builder->select('I.*, S.*, J.id_job, job_description, C.*');
        $builder->join('param_status S', 'S.status_slug = I.invoice_status', 'INNER');
        $builder->join('param_jobs J', 'J.id_job = I.fk_id_job', 'INNER');
        $builder->join('param_company C', 'C.id_company = J.fk_id_company', 'LEFT');

        if (array_key_exists('idInvoice', $arrData)) {
            $builder->where('I.id_invoice', $arrData['idInvoice']);
        }
        if (array_key_exists('date', $arrData) && $arrData['date'] != 'x') {
            $builder->where('I.date_issue', $arrData['date']);
        }
        if (array_key_exists('idJobCode', $arrData) && $arrData['idJobCode'] != 'x') {
            $builder->where('I.fk_id_job', $arrData['idJobCode']);
        }
        if (array_key_exists('status', $arrData) && $arrData['status'] != 'x') {
            $builder->where('I.invoice_status', $arrData['status']);
        }
        if (array_key_exists('number', $arrData) && $arrData['number'] != 'x') {
            $builder->like('I.number', $arrData['number']);
        }

        $builder->orderBy('I.id_invoice', 'DESC');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add Invoice
     * @since 05/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function add_invoice($post, $idUser)
    {
        $idInvoice = $post['hddIdentificador'] ?? '';

        $data = [
            'fk_id_job'         => $post['jobName'],
            'date_issue'        => $post['date'],
            'due_date'          => $post['due_date'],
            'number'            => $post['number'],
            'is_wo_or_claim'    => $post['link_to'],
            'fk_id_wo_or_claim' => $post['list_work_order'],
        ];

        if ($idInvoice == '') {
            $data['fk_id_user']     = $idUser;
            $data['invoice_status'] = 'draft';
            $this->db->table('invoices')->insert($data);
            return $this->db->insertID();
        } else {
            $data['invoice_status'] = $post['status'];
            $this->db->table('invoices')->where('id_invoice', $idInvoice)->update($data);
            return $idInvoice;
        }
    }

    public function getNextInvoiceNumber()
    {
        $year    = date('Y');
        $builder = $this->db->table('invoices');
        $builder->select('number');
        $builder->like('number', $year . '-', 'after');
        $builder->orderBy('number', 'DESC');
        $builder->limit(1);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $row   = $query->getRowArray();
            $parts = explode('-', $row['number']);
            $next  = intval($parts[1]) + 1;
        } else {
            $next = 1;
        }

        return str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get Invoices Items
     * @since 9/03/2026
     * @review 20/05/2026 - new CI4 version
     */
    public function get_invoices_items($arrData)
    {
        $builder = $this->db->table('invoices_items I');
        $builder->select();

        if (array_key_exists('idInvoice', $arrData)) {
            $builder->where('I.fk_id_invoice', $arrData['idInvoice']);
        }

        $query = $builder->get();
        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Claim list by Job Code
     * @since 10/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function get_claim_by_job_code($jobCode)
    {
        $query = $this->db->query(
            /*'SELECT * FROM claim WHERE date_issue_claim >= CURDATE() - INTERVAL 10 DAY AND fk_id_job = ?',*/ 
            'SELECT * FROM claim WHERE fk_id_job = ?',
            [$jobCode]
        );

        $wos = [];
        foreach ($query->getResultArray() as $row) {
            $wos[] = [
                'id_claim'    => $row['id_claim'],
                'observation' => $row['observation_claim'],
            ];
        }

        return $wos;
    }

    /**
     * Add invoice item
     * @since 10/03/2026
     * @author BMOTTAG
     * @review 20/05/2026 - new CI4 version
     */
    public function saveItem($post)
    {
        $rate     = $post['rate'];
        $quantity = $post['quantity'];
		$markup = $post['markup'] ?? 0;
        $value    = $rate * $quantity * ($markup + 100) / 100;

        $data = [
            'fk_id_invoice' => $post['hddIdInvoice'],
            'description'   => $post['description'],
            'quantity'      => $quantity,
            'unit'          => $post['unit'],
            'rate'          => $rate,
            'markup'        => $markup,
            'value'         => $value,
        ];

        return $this->db->table('invoices_items')->insert($data);
    }

    public function insertItem($data)
    {
        $this->db->table('invoices_items')->insert($data);
        return $this->db->insertID();
    }

    public function updateItem($idItem, $data)
    {
        return $this->db->table('invoices_items')
            ->where('id_invoices_items', $idItem)
            ->update($data);
    }

    public function save_file($data)
    {
        return $this->db->table('invoices_files')->insert($data);
    }

    public function get_files($invoice_id)
    {
        return $this->db->table('invoices_files')
            ->where('fk_id_invoice', $invoice_id)
            ->get()
            ->getResultArray();
    }

    public function save_payment($data)
    {
        $this->db->table('invoices_payments')->insert($data);
    }

    public function get_payments($invoice_id)
    {
        return $this->db->table('invoices_payments')
            ->where('fk_id_invoice', $invoice_id)
            ->orderBy('date_paid', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function delete_payment($idPayment)
    {
        return $this->db->table('invoices_payments')
            ->where('id_invoice_payment', $idPayment)
            ->delete();
    }

    /**
     * last WO
     * @since 24/03/2026
     * @review 20/05/2026 - new CI4 version
     */
    public function get_wo_job_code($jobCode)
    {
        $query = $this->db->table('workorder')
            ->select('*')
            ->where('state', 0)
            ->where('fk_id_job', $jobCode)
            ->orderBy('id_workorder', 'DESC')
            ->limit(10)
            ->get();

        $wos = [];
        foreach ($query->getResultArray() as $row) {
            $wos[] = [
                'id_workorder' => $row['id_workorder'],
                'observation'  => $row['observation'],
            ];
        }

        return $wos;
    }
}
