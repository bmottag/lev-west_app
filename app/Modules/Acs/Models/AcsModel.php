<?php

namespace App\Modules\Acs\Models;

use CodeIgniter\Model;

class AcsModel extends Model
{
    protected $protectFields = false;

    /**
     * ACS list
     * @since 10/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function get_acs($arrDatos)
    {
        $builder = $this->db->table('acs W');
        $builder->select('W.*, J.id_job, job_description, CONCAT(U.first_name, " ", U.last_name) name, C.company_name company, C.id_company');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');

        if (array_key_exists('idACS', $arrDatos)) {
            $builder->where('id_acs', $arrDatos['idACS']);
        }
        if (array_key_exists('jobId', $arrDatos) && $arrDatos['jobId'] != '' && $arrDatos['jobId'] != 0) {
            $builder->where('W.fk_id_job', $arrDatos['jobId']);
        }

        $builder->orderBy('id_acs', 'DESC');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get ACS personal info
     * @since 10/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function get_acs_personal($arrData)
    {
        $builder = $this->db->table('acs_personal W');
        $builder->select("W.*, CONCAT(first_name, ' ', last_name) name, T.employee_type");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->join('param_employee_type T', 'T.id_employee_type = W.fk_id_employee_type', 'INNER');

        if (array_key_exists('idACS', $arrData)) {
            $builder->where('W.fk_id_acs', $arrData['idACS']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }

        $builder->orderBy('U.first_name, U.last_name', 'ASC');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get ACS materials info
     * @since 10/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function get_acs_materials($arrData)
    {
        $builder = $this->db->table('acs_materials W');
        $builder->select('W.*, M.material');
        $builder->join('param_material_type M', 'M.id_material = W.fk_id_material', 'INNER');

        if (array_key_exists('idACS', $arrData)) {
            $builder->where('W.fk_id_acs', $arrData['idACS']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }

        $builder->orderBy('M.material', 'ASC');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get ACS Receipt info
     * @since 10/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function get_acs_receipt($arrData)
    {
        $builder = $this->db->table('acs_receipt W');

        if (array_key_exists('idACS', $arrData)) {
            $builder->where('W.fk_id_acs', $arrData['idACS']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }

        $builder->orderBy('W.place', 'ASC');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get ACS equipment info
     * @since 10/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function get_acs_equipment($arrData)
    {
        $builder = $this->db->table('acs_equipment W');
        $builder->select("W.*, V.make, V.model, V.unit_number, V.description v_description, M.miscellaneous, T.type_2, C.*, CONCAT(U.first_name,' ', U.last_name) as operatedby, A.attachment_number, A.attachment_description");
        $builder->join('param_vehicle V', 'V.id_vehicle = W.fk_id_vehicle', 'LEFT');
        $builder->join('param_attachments A', 'A.id_attachment = W.fk_id_attachment', 'LEFT');
        $builder->join('param_miscellaneous M', 'M.id_miscellaneous = W.fk_id_vehicle', 'LEFT');
        $builder->join('user U', 'U.id_user = W.operatedby', 'LEFT');
        $builder->join('param_vehicle_type_2 T', 'T.id_type_2 = W.fk_id_type_2', 'INNER');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');

        if (array_key_exists('idACS', $arrData)) {
            $builder->where('W.fk_id_acs', $arrData['idACS']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }

        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get ACS ocasional info
     * @since 10/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function get_acs_ocasional($arrData)
    {
        $builder = $this->db->table('acs_ocasional W');
        $builder->select('W.*, C.company_name');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'INNER');

        if (array_key_exists('idACS', $arrData)) {
            $builder->where('W.fk_id_acs', $arrData['idACS']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }

        $builder->orderBy('C.company_name', 'ASC');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update ACS information
     * @since 17/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function saveInfoACS($id, $data, $formType)
    {
        return $this->db->table('acs_' . $formType)
            ->where('id_acs_' . $formType, $id)
            ->update($data);
    }

    /**
     * Add personal
     * @since 18/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function savePersonal($post)
    {
        $data = [
            'fk_id_acs'           => $post['hddIdACS'],
            'fk_id_user'          => $post['employee'],
            'fk_id_employee_type' => $post['type'],
            'hours'               => $post['hour'],
            'description'         => $post['description'],
        ];

        return $this->db->table('acs_personal')->insert($data);
    }

    /**
     * Add Material
     * @since 18/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function saveMaterial($post)
    {
        $data = [
            'fk_id_acs'      => $post['hddIdACS'],
            'fk_id_material' => $post['material'],
            'quantity'       => $post['quantity'],
            'unit'           => $post['unit'],
            'description'    => $post['description'],
        ];

        return $this->db->table('acs_materials')->insert($data);
    }

    /**
     * Add Equipment
     * @since 18/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function saveEquipment($post)
    {
        $type  = $post['type'];
        $truck = $post['truck'];

        if ($type == 8) {
            $truck = 5;
        }

        $standby = ($type != 3) ? 2 : $post['standby'];

        $data = [
            'fk_id_acs'        => $post['hddIdACS'],
            'fk_id_type_2'     => $type,
            'fk_id_vehicle'    => $truck,
            'fk_id_attachment' => $post['attachment'],
            'other'            => $post['otherEquipment'],
            'operatedby'       => $post['operatedby'],
            'hours'            => $post['hour'],
            'quantity'         => $post['quantity'],
            'standby'          => $standby,
            'description'      => $post['description'],
        ];

        return $this->db->table('acs_equipment')->insert($data);
    }

    /**
     * Add Invoice
     * @since 18/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function saveReceipt($post)
    {
        $price = $post['price'] ?: 0;

        $data = [
            'fk_id_acs'   => $post['hddIdACS'],
            'place'       => $post['place'],
            'price'       => $price,
            'description' => $post['description'],
        ];

        return $this->db->table('acs_receipt')->insert($data);
    }

    /**
     * Add Ocasional
     * @since 18/01/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function saveOcasional($post)
    {
        $data = [
            'fk_id_acs'    => $post['hddIdACS'],
            'fk_id_company'=> $post['company'],
            'equipment'    => $post['equipment'],
            'quantity'     => $post['quantity'],
            'unit'         => $post['unit'],
            'hours'        => $post['hour'],
            'contact'      => $post['contact'],
            'description'  => $post['description'],
        ];

        return $this->db->table('acs_ocasional')->insert($data);
    }

    /**
     * Contar registros de workorder
     * @author BMOTTAG
     * @since 04/02/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function countACS($arrDatos)
    {
        $sql = "SELECT count(id_acs) CONTEO FROM acs W WHERE 1=1";
        if (array_key_exists('idJob', $arrDatos)) {
            $sql .= " AND fk_id_job = " . (int) $arrDatos['idJob'];
        }
        $query = $this->db->query($sql);
        $row   = $query->getRow();
        return $row->CONTEO;
    }

    /**
     * Sumatoria de horas de personal
     * @author BMOTTAG
     * @since 04/02/2025
     * @review 06/05/2026 - new CI4 version
     */
    public function countHoursPersonal($arrDatos)
    {
        $sql  = "SELECT ROUND(SUM(hours),2) TOTAL";
        $sql .= " FROM acs_personal P";
        $sql .= " INNER JOIN acs W ON W.id_acs = P.fk_id_acs";
        $sql .= " WHERE W.fk_id_job = " . (int) $arrDatos['idJob'];
        $query = $this->db->query($sql);
        $row   = $query->getRow();
        return $row->TOTAL;
    }

    /**
     * Sumatoria de valores para ACS
     * @author BMOTTAG
     * @since 10/2/2020
     * @review 06/05/2026 - new CI4 version
     */
    public function countIncome($arrDatos)
    {
        $sql  = "SELECT ROUND(SUM(value),2) TOTAL";
        $sql .= " FROM " . $arrDatos['table'] . " P";
        $sql .= " INNER JOIN acs W ON W.id_acs = P.fk_id_acs";
        $sql .= " WHERE W.fk_id_job = " . (int) $arrDatos['idJob'];
        $query = $this->db->query($sql);
        $row   = $query->getRow();
        return $row->TOTAL;
    }
}
