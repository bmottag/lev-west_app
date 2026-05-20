<?php
namespace App\Modules\Workorders\Models;

use CodeIgniter\Model;
use App\Libraries\Logger;

class WorkordersModel extends Model
{
    protected $protectFields = false;
    protected $logger;

    public function __construct()
    {
        parent::__construct();
        $this->logger = new Logger();
    }

    /**
     * Workorders´s list
     * las 10 records
     * @since 12/1/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workordes_by_idUser($arrDatos)
    {
        $builder = $this->db->table('workorder W');
        $builder->select('W.*, J.id_job, job_description, CONCAT(U.first_name, " ", U.last_name) name, C.company_name company, C.id_company, A.id_acs');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');
        $builder->join('acs A', 'A.fk_id_workorder = W.id_workorder', 'LEFT');
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');

        if (array_key_exists('idWorkOrder', $arrDatos)) {
            $builder->where('id_workorder', $arrDatos['idWorkOrder']);
        }
        if (array_key_exists('idEmployee', $arrDatos)) {
            $builder->where('W.fk_id_user', $arrDatos['idEmployee']);
        }

        $builder->orderBy('id_workorder', 'desc');
        $query = $builder->get(50);

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add workorder
     * @since 13/1/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function add_workorder($post, $idUser)
    {
        $idWorkorder = $post['hddIdentificador'] ?? '';

        $data = [
            'fk_id_job'              => $post['jobName'],
            'date'                   => $post['date'],
            'fk_id_company'          => $post['company'],
            'foreman_name_wo'        => $post['foreman'],
            'foreman_movil_number_wo' => $post['movilNumber'],
            'foreman_email_wo'       => $post['email'],
            'observation'            => $post['observation'] ?? null
        ];

        if ($idWorkorder == '') {
            $data['fk_id_user']   = $idUser;
            $data['date_issue']   = date('Y-m-d G:i:s');
            $data['state']        = 0;
            $data['last_message'] = 'New work order.';

            $this->db->table('workorder')->insert($data);
            $idWorkorder = $this->db->insertID();

            $this->logger->user($idUser)->type('workorder')->id($idWorkorder)
                ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();
        } else {
            $old = $this->db->table('workorder')->where('id_workorder', $idWorkorder)->get()->getRowArray();

            $this->db->table('workorder')->where('id_workorder', $idWorkorder)->update($data);

            $this->logger->user($idUser)->type('workorder')->id($idWorkorder)
                ->token('update')->comment(json_encode(['old' => $old, 'new' => json_encode($data)]))->log();
        }

        return $idWorkorder ?: false;
    }

    /**
     * Get workorder personal info
     * @since 9/2/2021
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_personal($arrData)
    {
        $builder = $this->db->table('workorder_personal W');
        $builder->select("W.*, CONCAT(first_name, ' ', last_name) name, T.employee_type");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->join('param_employee_type T', 'T.id_employee_type = W.fk_id_employee_type', 'INNER');

        if (array_key_exists('idWorkOrder', $arrData)) {
            $builder->where('W.fk_id_workorder', $arrData['idWorkOrder']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }
        if (array_key_exists('flag_expenses', $arrData)) {
            $builder->where('W.flag_expenses', 0);
        }
        $builder->orderBy('U.first_name, U.last_name', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add personal
     * @since 13/1/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function savePersonal($post, $idUser)
    {
        $idWorkorder = $post['hddidWorkorder'];

        $data = [
            'fk_id_workorder'      => $idWorkorder,
            'fk_id_user'           => $post['employee'],
            'fk_id_employee_type'  => $post['type'],
            'hours'                => $post['hour'],
            'description'          => $post['description'],
        ];

        $result = $this->db->table('workorder_personal')->insert($data);

        $this->logger->user($idUser)->type('workorder_personal')->id($idWorkorder)
            ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();

        return $result;
    }

    /**
     * Get workorder materials info
     * @since 13/1/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_materials($arrData)
    {
        $builder = $this->db->table('workorder_materials W');
        $builder->join('param_material_type M', 'M.id_material = W.fk_id_material', 'INNER');

        if (array_key_exists('idWorkOrder', $arrData)) {
            $builder->where('W.fk_id_workorder', $arrData['idWorkOrder']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }
        if (array_key_exists('flag_expenses', $arrData)) {
            $builder->where('W.flag_expenses', 0);
        }
        $builder->orderBy('M.material', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add Material
     * @since 13/1/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function saveMaterial($post, $idUser)
    {
        $idWorkorder = $post['hddidWorkorder'];

        $data = [
            'fk_id_workorder' => $idWorkorder,
            'fk_id_material'  => $post['material'],
            'quantity'        => $post['quantity'],
            'unit'            => $post['unit'],
            'description'     => $post['description'],
        ];

        $result = $this->db->table('workorder_materials')->insert($data);

        $this->logger->user($idUser)->type('workorder_materials')->id($idWorkorder)
            ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();

        return $result;
    }

    /**
     * Add Ocasional
     * @since 20/2/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function saveOcasional($post, $idUser)
    {
        $idWorkorder = $post['hddidWorkorder'];

        $data = [
            'fk_id_workorder' => $idWorkorder,
            'fk_id_company'   => $post['company'],
            'equipment'       => $post['equipment'],
            'quantity'        => $post['quantity'],
            'unit'            => $post['unit'],
            'hours'           => $post['hour'],
            'contact'         => $post['contact'],
            'description'     => $post['description'],
        ];

        $result = $this->db->table('workorder_ocasional')->insert($data);

        $this->logger->user($idUser)->type('workorder_ocasional')->id($idWorkorder)
            ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();

        return $result;
    }

    /**
     * Trucks list by type1 = rentals
     * @since 8/3/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_trucks_by_id1()
    {
        $sql = "SELECT id_vehicle, CONCAT(unit_number,' -----> ', description) as unit_description
                FROM param_vehicle
                WHERE type_level_1 = 2 AND state = 1
                ORDER BY unit_number";

        $query = $this->db->query($sql);
        $trucks = [];
        foreach ($query->getResult() as $row) {
            $trucks[] = ['id_truck' => $row->id_vehicle, 'unit_number' => $row->unit_description];
        }
        return $trucks;
    }

    /**
     * Add Equipment
     * @since 25/1/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function saveEquipment($post, $idUser)
    {
        $idWorkorder = $post['hddidWorkorder'];
        $type        = $post['type'];
        $truck       = $post['truck'] ?? null;

        if ($type == 8) {
            $truck = 5;
        }

        $standby = ($type != 3) ? 2 : ($post['standby'] ?? null);

        $data = [
            'fk_id_workorder'  => $idWorkorder,
            'fk_id_type_2'     => $type,
            'fk_id_vehicle'    => $truck,
            'fk_id_attachment' => $post['attachment'] ?? null,
            'other'            => $post['otherEquipment'] ?? null,
            'operatedby'       => $post['operatedby'] ?? null,
            'hours'            => $post['hour'] ?? null,
            'quantity'         => $post['quantity'] ?? null,
            'standby'          => $standby,
            'description'      => $post['description'] ?? null,
        ];

        $result = $this->db->table('workorder_equipment')->insert($data);

        $this->logger->user($idUser)->type('workorder_equipment')->id($idWorkorder)
            ->token('insert')
            ->comment(json_encode([
                'old' => null,
                'new' => $data
            ]))
            ->log();

        return $result;
    }

    /**
     * Get workorder equipment info
     * @since 25/1/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_equipment($arrData)
    {
        $builder = $this->db->table('workorder_equipment W');
        $builder->select("W.*, V.make, V.model, V.unit_number, V.description v_description, M.miscellaneous, T.type_2, C.*, CONCAT(U.first_name,' ', U.last_name) as operatedby, A.attachment_number, A.attachment_description");
        $builder->join('param_vehicle V', 'V.id_vehicle = W.fk_id_vehicle', 'LEFT');
        $builder->join('param_attachments A', 'A.id_attachment = W.fk_id_attachment', 'LEFT');
        $builder->join('param_miscellaneous M', 'M.id_miscellaneous = W.fk_id_vehicle', 'LEFT');
        $builder->join('user U', 'U.id_user = W.operatedby', 'LEFT');
        $builder->join('param_vehicle_type_2 T', 'T.id_type_2 = W.fk_id_type_2', 'INNER');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');

        if (array_key_exists('idWorkOrder', $arrData)) {
            $builder->where('W.fk_id_workorder', $arrData['idWorkOrder']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }
        if (array_key_exists('flag_expenses', $arrData)) {
            $builder->where('W.flag_expenses', 0);
        }
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get workorder ocasional info
     * @since 20/2/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_ocasional($arrData)
    {
        $builder = $this->db->table('workorder_ocasional W');
        $builder->select('W.*, C.company_name, H.id_hauling');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'INNER');
        $builder->join('hauling H', 'H.fk_id_submodule = W.id_workorder_ocasional', 'LEFT');

        if (array_key_exists('idWorkOrder', $arrData)) {
            $builder->where('W.fk_id_workorder', $arrData['idWorkOrder']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }
        if (array_key_exists('flag_expenses', $arrData)) {
            $builder->where('W.flag_expenses', 0);
        }
        $builder->orderBy('C.company_name', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get workorder HOLD BACK info
     * @since 12/11/2018
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_hold_back($idWorkorder)
    {
        $builder = $this->db->table('workorder_hold_back H');
        $builder->where('H.fk_id_workorder', $idWorkorder);
        $builder->orderBy('H.id_workorder_hold_back', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Work Order
     * @since 21/02/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_by_idJob($arrData)
    {
        $builder = $this->db->table('workorder W');
        $builder->select("W.*, CONCAT(first_name, ' ', last_name) name, J.job_description, J.markup, J.notes, C.*");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');

        if (array_key_exists('jobId', $arrData) && $arrData['jobId'] != '' && $arrData['jobId'] != 0) {
            $builder->where('W.fk_id_job', $arrData['jobId']);
        }
        if (array_key_exists('idWorkOrder', $arrData) && $arrData['idWorkOrder'] != '' && $arrData['idWorkOrder'] != 0) {
            $builder->where('W.id_workorder', $arrData['idWorkOrder']);
        }
        if (array_key_exists('idWorkOrderFrom', $arrData) && $arrData['idWorkOrderFrom'] != '' && $arrData['idWorkOrderFrom'] != 0) {
            $builder->where('W.id_workorder >=', $arrData['idWorkOrderFrom']);
        }
        if (array_key_exists('idWorkOrderTo', $arrData) && $arrData['idWorkOrderTo'] != '' && $arrData['idWorkOrderTo'] != 0) {
            $builder->where('W.id_workorder <=', $arrData['idWorkOrderTo']);
        }
        if (!empty($arrData['from'])) {
            $builder->where('W.date >=', $arrData['from']);
        }

        if (!empty($arrData['to']) && !empty($arrData['from'])) {
            $builder->where('W.date <=', $arrData['to']);
        }
        if (array_key_exists('state', $arrData) && $arrData['state'] !== '') {
            $builder->where('W.state', $arrData['state']);
        }

        $builder->orderBy('W.id_workorder', 'desc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update Rate
     * @since 27/2/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function saveRate($post, $idUser, $generalModel)
    {
        $hddId      = $post['hddId'];
        $formType   = $post['formType'];
        $description = $post['description'];
        $unit       = $post['unit'] ?? null;
        $rate       = $post['rate'];
        $quantity   = $post['quantity'];
        $hours      = $post['hours'];
        $checkPDF = array_key_exists('check_pdf', $post)
                        ? ($post['check_pdf'] ? 1 : 2)
                        : 2;
        $idWorkorder = $post['hddIdWorkOrder'];

        $value = $rate * $quantity * $hours;

        $data = [
            'description' => $description,
            'rate'        => $rate,
            'value'       => $value,
            'view_pdf'    => $checkPDF,
        ];

        $table  = 'workorder_' . $formType;
        $oldRow = $this->db->table($table)->where('id_workorder_' . $formType, $hddId)->get()->getRowArray();
        $log    = ['old' => $oldRow, 'new' => null];

        switch ($formType) {
            case 'personal':
                $type = $post['type_personal'];
                $data['fk_id_employee_type'] = $type;
                $data['hours'] = $hours;

                if ($oldRow['hours'] != $hours) {
                    foreach (['wo_start_project' => 'hours_start_project', 'wo_end_project' => 'hours_end_project'] as $col => $hoursCol) {
                        $taskStart = $generalModel->get_task([
                            'idWorkOrder' => $idWorkorder,
                            'idEmployee'  => $oldRow['fk_id_user'],
                            'column'      => $col,
                        ]);
                        if ($taskStart) {
                            $generalModel->updateRecord([
                                'table'      => 'task',
                                'primaryKey' => 'id_task',
                                'id'         => $taskStart[0]['id_task'],
                                'column'     => $hoursCol,
                                'value'      => $hours,
                            ]);
                        }
                    }
                }
                break;

            case 'materials':
                $markup = $post['markup'];
                $value  = $value * ($markup + 100) / 100;
                $data['markup']   = $markup;
                $data['value']    = $value;
                $data['quantity'] = $quantity;
                $data['unit']     = $unit;
                break;

            case 'equipment':
                $data['hours']    = $hours;
                $data['quantity'] = $quantity;
                break;

            case 'ocasional':
                $markup = $post['markup'];
                $value  = $value * ($markup + 100) / 100;
                $data['markup']   = $markup;
                $data['value']    = $value;
                $data['hours']    = $hours;
                $data['quantity'] = $quantity;
                $data['unit']     = $unit;
                break;
        }

        $result = $this->db->table($table)->where('id_workorder_' . $formType, $hddId)->update($data);

        if ($oldRow['flag_expenses'] == 1) {
            $this->db->table('workorder_expense')
                ->where('fk_id_workorder', $idWorkorder)
                ->where('fk_id_submodule', $hddId)
                ->where('submodule', $formType)
                ->update(['expense_value' => $value]);
        }

        $log['new'] = json_encode($data);
        $this->logger->user($idUser)->type($table)->id($idWorkorder)
            ->token('update')->comment(json_encode($log))->log();

        return $result;
    }

    /**
     * Add info boton go back
     * @since 11/11/2018
     * @review 05/05/2026 - new CI4 version
     */
    public function saveInfoGoBack($arrData, $idUser)
    {
        $this->db->table('workorder_go_back')->where('fk_id_user', $idUser)->delete();

        $data = [
            'fk_id_user' => $idUser,
            'post_from'  => $arrData['from'],
            'post_to'    => $arrData['to'],
        ];

        if (array_key_exists('jobId', $arrData)) {
            $data['post_id_job'] = $arrData['jobId'];
        }
        if (array_key_exists('idWorkOrder', $arrData)) {
            $data['post_id_work_order'] = $arrData['idWorkOrder'];
        }
        if (array_key_exists('idWorkOrderFrom', $arrData)) {
            $data['post_id_wo_from'] = $arrData['idWorkOrderFrom'];
        }
        if (array_key_exists('idWorkOrderTo', $arrData)) {
            $data['post_id_wo_to'] = $arrData['idWorkOrderTo'];
        }
        if (array_key_exists('state', $arrData)) {
            $data['post_state'] = $arrData['state'];
        }

        $this->db->table('workorder_go_back')->insert($data);
    }

    /**
     * Work Order info go back
     * @since 11/11/2018
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_go_back($idUser)
    {
        $query = $this->db->table('workorder_go_back')->where('fk_id_user', $idUser)->get();

        return $query->getNumRows() > 0 ? $query->getRowArray() : false;
    }

    /**
     * Get workorder additional info
     * @since 11/1/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_state($idWorkorder)
    {
        $builder = $this->db->table('workorder_state W');
        $builder->select("W.*, U.first_name");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->where('W.fk_id_workorder', $idWorkorder);
        $builder->orderBy('W.id_workorder_state', 'desc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add workorder state
     * @since 11/1/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function add_workorder_state($arrData, $idUser)
    {
        $idWorkorder = $arrData['idWorkorder'];

        $data = [
            'fk_id_workorder' => $idWorkorder,
            'fk_id_user'      => $idUser,
            'date_issue'      => date('Y-m-d G:i:s'),
            'observation'     => $arrData['observation'],
            'state'           => $arrData['state'],
        ];

        $result = $this->db->table('workorder_state')->insert($data);

        $this->logger->user($idUser)->type('workorder_state')->id($idWorkorder)
            ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();

        return $result;
    }

    /**
     * Contar registros de workorder
     * @author BMOTTAG
     * @since  7/2/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function countWorkorders($arrDatos)
    {
        $sql = "SELECT count(id_workorder) CONTEO FROM workorder W WHERE 1=1";

        if (array_key_exists('idJob', $arrDatos)) {
            $sql .= " AND fk_id_job = " . (int)$arrDatos['idJob'];
        } elseif (array_key_exists('year', $arrDatos)) {
            $year     = $arrDatos['year'];
            $firstDay = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
            $lastDay  = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year + 1));
            $sql .= " AND date >= '$firstDay' AND date <= '$lastDay'";
        } else {
            $year     = date('Y');
            $firstDay = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
            $sql .= " AND date >= '$firstDay'";
        }

        if (array_key_exists('from', $arrDatos) && $arrDatos['from'] != '') {
            $sql .= " AND date >= '" . $arrDatos['from'] . "'";
        }
        if (array_key_exists('to', $arrDatos) && $arrDatos['to'] != '' && $arrDatos['from'] != '') {
            $sql .= " AND date <= '" . $arrDatos['to'] . "'";
        }
        if (array_key_exists('state', $arrDatos)) {
            $sql .= " AND state = " . (int)$arrDatos['state'];
        }

        $row = $this->db->query($sql)->getRow();
        return $row->CONTEO;
    }

    /**
     * Sumatoria de horas de personal
     * @author BMOTTAG
     * @since  10/2/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function countHoursPersonal($arrDatos)
    {
        $sql = "SELECT ROUND(SUM(hours),2) TOTAL
                FROM workorder_personal P
                INNER JOIN workorder W on W.id_workorder = P.fk_id_workorder
                WHERE W.fk_id_job = " . (int)$arrDatos['idJob'];

        if (array_key_exists('from', $arrDatos) && $arrDatos['from'] != '') {
            $sql .= " AND date >= '" . $arrDatos['from'] . "'";
        }
        if (array_key_exists('to', $arrDatos) && $arrDatos['to'] != '' && $arrDatos['from'] != '') {
            $sql .= " AND date <= '" . $arrDatos['to'] . "'";
        }

        $row = $this->db->query($sql)->getRow();
        return $row->TOTAL;
    }

    /**
     * Sumatoria de valores para la WO
     * @author BMOTTAG
     * @since  10/2/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function countIncome($arrDatos)
    {
        $sql = "SELECT ROUND(SUM(value),2) TOTAL
                FROM " . $arrDatos['table'] . " P
                INNER JOIN workorder W on W.id_workorder = P.fk_id_workorder
                WHERE W.fk_id_job = " . (int)$arrDatos['idJob'];

        if (array_key_exists('idWorkOrder', $arrDatos)) {
            $sql .= " AND P.fk_id_workorder = " . (int)$arrDatos['idWorkOrder'];
        }
        if (array_key_exists('from', $arrDatos) && $arrDatos['from'] != '') {
            $sql .= " AND date >= '" . $arrDatos['from'] . "'";
        }
        if (array_key_exists('to', $arrDatos) && $arrDatos['to'] != '' && $arrDatos['from'] != '') {
            $sql .= " AND date <= '" . $arrDatos['to'] . "'";
        }

        $row = $this->db->query($sql)->getRow();
        return $row->TOTAL;
    }

    /**
     * Informacion del foreman
     * @since 4/6/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function info_foreman($idForeman, $post)
    {
        $data = [
            'foreman_name'         => $post['foreman'],
            'foreman_movil_number' => $post['movilNumber'],
            'foreman_email'        => $post['email'],
        ];

        if ($idForeman == '') {
            $data['fk_id_job']          = $post['jobName'];
            $data['fk_id_param_company'] = $post['company'];
            return $this->db->table('param_company_foreman')->insert($data);
        } else {
            return $this->db->table('param_company_foreman')->where('id_company_foreman', $idForeman)->update($data);
        }
    }

    /**
     * Get Prices for workorder personal
     * @since 13/1/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_personal_prices($idWorkorder, $idJob)
    {
        $builder = $this->db->table('workorder_personal W');
        $builder->select("W.*, T.job_employee_type_unit_price");
        $builder->join('job_employee_type_price T', 'T.fk_id_employee_type = W.fk_id_employee_type', 'INNER');
        $builder->where('W.fk_id_workorder', $idWorkorder);
        $builder->where('T.fk_id_job', $idJob);
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update WO personal rate and value
     * @since 7/11/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function update_wo_personal_rate($workorderPersonalRate)
    {
        $result = 1;
        if ($workorderPersonalRate) {
            foreach ($workorderPersonalRate as $row) {
                $rate  = $row['job_employee_type_unit_price'];
                $value = $rate * $row['hours'];
                $result = $this->db->table('workorder_personal')
                    ->where('id_workorder_personal', $row['id_workorder_personal'])
                    ->update(['rate' => $rate, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Get Prices for workorder equipment
     * @since 10/11/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_equipment_prices($idWorkorder, $idJob)
    {
        $builder = $this->db->table('workorder_equipment W');
        $builder->select("W.*, T.job_equipment_unit_price, T.job_equipment_without_driver");
        $builder->join('job_equipment_price T', 'T.fk_id_equipment = W.fk_id_vehicle', 'INNER');
        $builder->where('W.fk_id_workorder', $idWorkorder);
        $builder->where('W.fk_id_type_2 !=', 8);
        $builder->where('T.fk_id_job', $idJob);
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update WO equipment rate and value
     * @since 10/11/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function update_wo_equipment_rate($workorderEquipmentRate)
    {
        $result = 1;
        if ($workorderEquipmentRate) {
            foreach ($workorderEquipmentRate as $row) {
                $rate     = ($row['standby'] == 1) ? $row['job_equipment_without_driver'] : $row['job_equipment_unit_price'];
                $quantity = $row['quantity'] == 0 ? 1 : $row['quantity'];
                $value    = $rate * $quantity * $row['hours'];
                $result   = $this->db->table('workorder_equipment')
                    ->where('id_workorder_equipment', $row['id_workorder_equipment'])
                    ->update(['rate' => $rate, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Get Prices for workorder material
     * @since 16/12/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_material_prices($idWorkorder)
    {
        $builder = $this->db->table('workorder_materials W');
        $builder->select("W.*, T.material_price");
        $builder->join('param_material_type T', 'T.id_material = W.fk_id_material', 'INNER');
        $builder->where('W.fk_id_workorder', $idWorkorder);
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update WO material rate and value
     * @since 18/12/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function update_wo_material_rate($workorderMaterialRate)
    {
        $result = 1;
        if ($workorderMaterialRate) {
            foreach ($workorderMaterialRate as $row) {
                $value  = $row['material_price'] * $row['quantity'];
                $result = $this->db->table('workorder_materials')
                    ->where('id_workorder_materials', $row['id_workorder_materials'])
                    ->update(['rate' => $row['material_price'], 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Update state and last message
     * @since 23/12/2020
     * @review 05/05/2026 - new CI4 version
     */
    public function update_workorder($arrData)
    {
        return $this->db->table('workorder')
            ->where('id_workorder', $arrData['idWorkorder'])
            ->update(['state' => $arrData['state'], 'last_message' => $arrData['lastMessage']]);
    }

    /**
     * Get workorder Receipt info
     * @since 4/1/2021
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_receipt($arrData)
    {
        $builder = $this->db->table('workorder_receipt W');

        if (array_key_exists('idWorkOrder', $arrData)) {
            $builder->where('W.fk_id_workorder', $arrData['idWorkOrder']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('W.view_pdf', 1);
        }
        if (array_key_exists('flag_expenses', $arrData)) {
            $builder->where('W.flag_expenses', 0);
        }
        $builder->orderBy('W.place', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add Invoice
     * @since 4/1/2021
     * @review 05/05/2026 - new CI4 version
     */
    public function saveReceipt($post, $idUser)
    {
        $idWorkorder  = $post['hddidWorkorder'];
        $idWOReceipt  = $post['hddId'];
        $price        = $post['price'] ?: 0;
        $checkPDF = array_key_exists('check_pdf', $post)
                        ? ($post['check_pdf'] ? 1 : 2)
                        : null;

        $data = [
            'place'       => $post['place'],
            'price'       => $price,
            'description' => $post['description'],
        ];

        if ($idWOReceipt == '') {
            $value = $price / 1.05;
            $data['fk_id_workorder'] = $idWorkorder;
            $data['markup']   = 0;
            $data['view_pdf'] = 1;
            $data['value']    = $value;

            $result = $this->db->table('workorder_receipt')->insert($data);

            $this->logger->user($idUser)->type('workorder_receipt')->id($idWorkorder)
                ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();
        } else {
            $markup = $post['markup'];
            $oldRow = $this->db->table('workorder_receipt')->where('fk_id_workorder', $idWorkorder)->get()->getResultArray();

            $price  = $price / 1.05;
            $value  = $price + ($price * $markup / 100);

            $data['markup']   = $markup;
            $data['value']    = $value;
            $data['view_pdf'] = $checkPDF;

            $result = $this->db->table('workorder_receipt')->where('id_workorder_receipt', $idWOReceipt)->update($data);

            $this->logger->user($idUser)->type('workorder_receipt')->id($idWorkorder)
                ->token('update')->comment(json_encode(['old' => $oldRow, 'new' => json_encode($data)]))->log();
        }

        return $result;
    }

    /**
     * Update WO INVOICE markup and value
     * @since 4/1/2021
     * @review 05/05/2026 - new CI4 version
     */
    public function update_wo_invoice_markup($workorderReceipt, $markup)
    {
        $result = 1;
        if ($workorderReceipt) {
            foreach ($workorderReceipt as $row) {
                $price = ($row['price'] / 1.05);
                $value = $price + ($price * $markup / 100);
                $result = $this->db->table('workorder_receipt')
                    ->where('id_workorder_receipt', $row['id_workorder_receipt'])
                    ->update(['markup' => $markup, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Update WO MATERIAL markup and value
     * @since 5/1/2021
     * @review 05/05/2026 - new CI4 version
     */
    public function update_wo_material_markup($workorderMaterials, $markup)
    {
        $result = 1;
        if ($workorderMaterials) {
            foreach ($workorderMaterials as $row) {
                $value  = $row['rate'] * $row['quantity'];
                $value  = $value * ($markup + 100) / 100;
                $result = $this->db->table('workorder_materials')
                    ->where('id_workorder_materials', $row['id_workorder_materials'])
                    ->update(['markup' => $markup, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Update WO OCASIONAL markup and value
     * @since 5/1/2021
     * @review 05/05/2026 - new CI4 version
     */
    public function update_wo_ocasional_markup($workorderOcasional, $markup)
    {
        $result = 1;
        if ($workorderOcasional) {
            foreach ($workorderOcasional as $row) {
                $value  = $row['rate'] * $row['quantity'] * $row['hours'];
                $value  = $value * ($markup + 100) / 100;
                $result = $this->db->table('workorder_ocasional')
                    ->where('id_workorder_ocasional', $row['id_workorder_ocasional'])
                    ->update(['markup' => $markup, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Update WO state
     * @since 12/1/2021
     * @review 05/05/2026 - new CI4 version
     */
    public function updateWOState($post, $idUser)
    {
        $state       = $post['state'];
        $information = $post['information'];
        $result      = 1;

        if ($wo = $post['wo'] ?? null) {
            foreach ($wo as $idWo) {
                $result = $this->db->table('workorder_state')->insert([
                    'fk_id_workorder' => $idWo,
                    'fk_id_user'      => $idUser,
                    'date_issue'      => date('Y-m-d G:i:s'),
                    'observation'     => $information,
                    'state'           => $state,
                ]);

                $result = $this->db->table('workorder')->where('id_workorder', $idWo)
                    ->update(['state' => $state, 'last_message' => $information]);
            }
        }
        return $result;
    }

    /**
     * Get workorder expenses info
     * @since 13/1/2023
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_expense($arrData)
    {
        $builder = $this->db->table('workorder_expense W');
        $builder->join('job_details J', 'J.id_job_detail = W.fk_id_job_detail', 'INNER');

        if (array_key_exists('idWorkOrder', $arrData)) {
            $builder->where('W.fk_id_workorder', $arrData['idWorkOrder']);
        }
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add expense
     * @since 13/1/2023
     * @review 05/05/2026 - new CI4 version
     */
    public function saveExpense($post)
    {
        $idWorkorder = $post['hddidWorkorder'];
        $this->db->table('workorder_expense')->where('fk_id_workorder', $idWorkorder)->delete();

        $result = 1;
        if ($expenses = $post['expense'] ?? null) {
            foreach ($expenses as $expense) {
                $result = $this->db->table('workorder_expense')->insert([
                    'fk_id_workorder'  => $idWorkorder,
                    'fk_id_job_detail' => $expense,
                ]);
            }
        }
        return $result;
    }

    /**
     * Update WO Expenses Values
     * @since 21/1/2023
     * @review 05/05/2026 - new CI4 version
     */
    public function updateExpensesValues($workorderExpenses, $totalWOIncome, $sumPercentageExpense)
    {
        $result = 1;
        if ($workorderExpenses) {
            foreach ($workorderExpenses as $row) {
                $expenseValue = $row['percentage'] * $totalWOIncome / $sumPercentageExpense;
                $result = $this->db->table('workorder_expense')
                    ->where('id_workorder_expense', $row['id_workorder_expense'])
                    ->update(['expense_value' => $expenseValue]);
            }
        }
        return $result;
    }

    /**
     * Sumatoria de valores de porcentage para los gastos guardados
     * @author BMOTTAG
     * @since  24/1/2023
     * @review 05/05/2026 - new CI4 version
     */
    public function sumPercentageExpense($arrDatos)
    {
        $sql = "SELECT ROUND(SUM(percentage),2) TOTAL
                FROM workorder_expense W
                INNER JOIN job_details J on J.id_job_detail = W.fk_id_job_detail
                WHERE W.fk_id_workorder = " . (int)$arrDatos['idWorkOrder'];

        $row = $this->db->query($sql)->getRow();
        return $row->TOTAL;
    }

    /**
     * Work Order Log
     * @since 20/02/2024
     * @review 05/05/2026 - new CI4 version
     */
    public function get_workorder_log($arrData)
    {
        $builder = $this->db->table('logger L');
        $builder->select("L.*, CONCAT(first_name, ' ', last_name) name, J.job_description");
        $builder->join('user U', 'U.id_user = L.created_by', 'INNER');
        $builder->join('workorder W', 'L.type_id = W.id_workorder', 'LEFT');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');

        $parameters = ['workorder', 'workorder_state', 'workorder_personal', 'workorder_materials', 'workorder_equipment', 'workorder_receipt', 'workorder_ocasional'];
        $builder->whereIn('L.type', $parameters);

        if (array_key_exists('jobId', $arrData) && $arrData['jobId'] != '' && $arrData['jobId'] != 0) {
            $builder->where('J.id_job', $arrData['jobId']);
        }
        if (array_key_exists('idWorkOrder', $arrData) && $arrData['idWorkOrder'] != '' && $arrData['idWorkOrder'] != 0) {
            $builder->where('L.type_id', $arrData['idWorkOrder']);
        }
        if (array_key_exists('userId', $arrData) && $arrData['userId'] != '' && $arrData['userId'] != 0) {
            $builder->where('L.created_by', $arrData['userId']);
        }
        if (array_key_exists('from', $arrData) && $arrData['from'] != '') {
            $builder->where('L.created_on >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != '' && $arrData['from'] != '') {
            $builder->where('L.created_on <=', $arrData['to']);
        }
        $builder->orderBy('L.id', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Save WO Expenses
     * @since 24/03/2024
     * @review 05/05/2026 - new CI4 version
     */
    public function saveWOExpenses($post)
    {
        $result      = 1;
        $idWorkOrder = $post['hddidWorkorder'];
        $idJobDetail = $post['item_job_detail'];

        if ($items = $post['item'] ?? null) {
            foreach ($items as $item) {
                $parts  = explode('__', $item);
                $result = $this->db->table('workorder_expense')->insert([
                    'fk_id_workorder'  => $idWorkOrder,
                    'fk_id_job_detail' => $idJobDetail,
                    'submodule'        => $parts[0],
                    'fk_id_submodule'  => $parts[1],
                    'expense_value'    => $parts[2],
                ]);

                $this->db->table('workorder_' . $parts[0])
                    ->where('id_workorder_' . $parts[0], $parts[1])
                    ->update(['flag_expenses' => 1]);
            }
        }
        return $result;
    }

    /**
     * Delete Expenses
     * @since 2/05/2024
     * @review 05/05/2026 - new CI4 version
     */
    public function deleteExpenses($arrDatos)
    {
        return $this->db->table('workorder_expense')->where([
            'fk_id_workorder'  => $arrDatos['fk_id_workorder'],
            'submodule'        => $arrDatos['submodule'],
            'fk_id_submodule'  => $arrDatos['fk_id_submodule'],
        ])->delete();
    }

    /**
     * Clone workorder to create Accounting Control Sheet (ACS)
     * @since 09/01/2025
     * @review 05/05/2026 - new CI4 version
     */
    public function clone_workorder($arrData)
    {
        $wo   = $arrData['workorder'][0];
        $data = [
            'fk_id_workorder'        => $wo['id_workorder'],
            'fk_id_user'             => $wo['fk_id_user'],
            'fk_id_job'              => $wo['fk_id_job'],
            'date_issue'             => date('Y-m-d G:i:s'),
            'date'                   => $wo['date'],
            'fk_id_company'          => $wo['fk_id_company'],
            'foreman_name_wo'        => $wo['foreman_name_wo'],
            'foreman_movil_number_wo' => $wo['foreman_movil_number_wo'],
            'foreman_email_wo'       => $wo['foreman_email_wo'],
            'observation'            => $wo['observation'],
            'state'                  => $wo['state'],
            'last_message'           => $wo['last_message'],
        ];

        $result = $this->db->table('acs')->insert($data);
        $idACS  = $this->db->insertID();

        if (!empty($arrData['workorderPersonal'])) {
            foreach ($arrData['workorderPersonal'] as $personal) {
                $this->db->table('acs_personal')->insert([
                    'fk_id_acs'           => $idACS,
                    'fk_id_user'          => $personal['fk_id_user'],
                    'fk_id_employee_type' => $personal['fk_id_employee_type'],
                    'hours'               => $personal['hours'],
                    'rate'                => $personal['rate'],
                    'value'               => $personal['value'],
                    'description'         => $personal['description'],
                    'view_pdf'            => $personal['view_pdf'],
                ]);
            }
        }

        if (!empty($arrData['workorderMaterials'])) {
            foreach ($arrData['workorderMaterials'] as $material) {
                $this->db->table('acs_materials')->insert([
                    'fk_id_acs'      => $idACS,
                    'fk_id_material' => $material['fk_id_material'],
                    'quantity'       => $material['quantity'],
                    'unit'           => $material['unit'],
                    'rate'           => $material['rate'],
                    'markup'         => $material['markup'],
                    'value'          => $material['value'],
                    'description'    => $material['description'],
                    'view_pdf'       => $material['view_pdf'],
                ]);
            }
        }

        if (!empty($arrData['workorderReceipt'])) {
            foreach ($arrData['workorderReceipt'] as $receipt) {
                $this->db->table('acs_receipt')->insert([
                    'fk_id_acs'   => $idACS,
                    'place'       => $receipt['place'],
                    'price'       => $receipt['price'],
                    'markup'      => $receipt['markup'],
                    'value'       => $receipt['value'],
                    'description' => $receipt['description'],
                    'view_pdf'    => $receipt['view_pdf'],
                ]);
            }
        }

        if (!empty($arrData['workorderEquipment'])) {
            foreach ($arrData['workorderEquipment'] as $equipment) {
                $this->db->table('acs_equipment')->insert([
                    'fk_id_acs'        => $idACS,
                    'fk_id_type_2'     => $equipment['fk_id_type_2'],
                    'fk_id_vehicle'    => $equipment['fk_id_vehicle'],
                    'fk_id_attachment' => $equipment['fk_id_attachment'],
                    'fk_id_company'    => $equipment['fk_id_company'],
                    'other'            => $equipment['other'],
                    'operatedby'       => $equipment['operatedby'],
                    'hours'            => $equipment['hours'],
                    'quantity'         => $equipment['quantity'],
                    'rate'             => $equipment['rate'],
                    'standby'          => $equipment['standby'],
                    'value'            => $equipment['value'],
                    'description'      => $equipment['description'],
                    'view_pdf'         => $equipment['view_pdf'],
                ]);
            }
        }

        if (!empty($arrData['workorderOcasional'])) {
            foreach ($arrData['workorderOcasional'] as $ocasional) {
                $this->db->table('acs_ocasional')->insert([
                    'fk_id_acs'     => $idACS,
                    'fk_id_company' => $ocasional['fk_id_company'],
                    'equipment'     => $ocasional['equipment'],
                    'quantity'      => $ocasional['quantity'],
                    'unit'          => $ocasional['unit'],
                    'hours'         => $ocasional['hours'],
                    'rate'          => $ocasional['rate'],
                    'markup'        => $ocasional['markup'],
                    'value'         => $ocasional['value'],
                    'contact'       => $ocasional['contact'],
                    'description'   => $ocasional['description'],
                    'view_pdf'      => $ocasional['view_pdf'],
                ]);
            }
        }

        return $result;
    }

    /**
     * Add/Edit Subcontractors Invoices
     * @since 13/02/2025
     * @review 05/05/2026 - new CI4 version
     */
    public function saveSubcontractorInvoice($post, $archivo)
    {
        $idSubcontractorInvoice = $post['hddIdentificador'];
        $idSubcontractor        = $post['company'];

        if ($idSubcontractor == '') {
            $companyData = [
                'company_name' => $post['subcontractor_name'],
                'company_type' => 3,
                'contact'      => $post['subcontractor_contact'],
                'movil_number' => $post['subcontractor_mobile_number'],
                'email'        => $post['subcontractor_email'],
                'does_hauling' => 2,
            ];
            $this->db->table('param_company')->insert($companyData);
            $idSubcontractor = $this->db->insertID();
        }

        $data = [
            'date_issue'     => date('Y-m-d'),
            'fk_id_company'  => $idSubcontractor,
            'invoice_number' => $post['invoice_number'],
            'invoice_amount' => $post['amount'],
        ];

        if ($archivo !== 'xxx') {
            $data['file'] = $archivo;
        }

        if ($idSubcontractorInvoice == '') {
            $this->db->table('subcontractor_invoice')->insert($data);
            $idSubcontractorInvoice = $this->db->insertID();
        } else {
            $this->db->table('subcontractor_invoice')->where('id_subcontractor_invoice', $idSubcontractorInvoice)->update($data);
        }

        return $idSubcontractorInvoice ?: false;
    }

    /**
     * Subcontractors list
     * @since 12/1/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_subcontractors_invoice($arrDatos)
    {
        $builder = $this->db->table('subcontractor_invoice S');
        $builder->select('
            S.*,
            C.company_name AS company,
            (
                SELECT GROUP_CONCAT(DISTINCT O.fk_id_workorder)
                FROM workorder_ocasional O
                WHERE O.fk_id_subcontractor_invoice = S.id_subcontractor_invoice
            ) AS related_workorders,
            (
                SELECT COALESCE(SUM(O.value), 0)
                FROM workorder_ocasional O
                WHERE O.fk_id_subcontractor_invoice = S.id_subcontractor_invoice
            ) AS total_workorder_value
        ');
        $builder->join('param_company C', 'C.id_company = S.fk_id_company', 'INNER');

        if (array_key_exists('idSubcontractorInvoice', $arrDatos)) {
            $builder->where('id_subcontractor_invoice', $arrDatos['idSubcontractorInvoice']);
        }
        if (array_key_exists('idCompany', $arrDatos)) {
            $builder->where('S.fk_id_company', $arrDatos['idCompany']);
        }
        $builder->orderBy('id_subcontractor_invoice', 'desc');
        $query = $builder->get(50);

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    public function getJobsDashboard($filters = [])
    {
        $whereWO  = "WHERE 1=1";
        $whereSub = "WHERE 1=1";
        $binds = [];

        // =========================
        // FILTRO FECHA DESDE
        // =========================
        if (!empty($filters['from'])) {
            $whereWO  .= " AND W.date >= ?";
            $whereSub .= " AND W.date >= ?";
            $binds[] = $filters['from'];
            $binds[] = $filters['from'];
        }

        // =========================
        // FILTRO FECHA HASTA
        // =========================
        if (!empty($filters['to'])) {
            $whereWO  .= " AND W.date <= ?";
            $whereSub .= " AND W.date <= ?";
            $binds[] = $filters['to'];
            $binds[] = $filters['to'];
        }

        // =========================
        // FILTRO ESTADO WORKORDER
        // =========================
        if (isset($filters['state']) && $filters['state'] !== '') {
            $state = (int)$filters['state'];
            $whereWO  .= " AND W.state = $state";
            $whereSub .= " AND W.state = $state";
        }

        $sql = "
            SELECT 
                J.id_job,
                J.job_description,

                COUNT(DISTINCT W.id_workorder) as noWO,

                COALESCE(HP.total_hours, 0) as hoursPersonal,

                COALESCE(IP.total, 0) as incomePersonal,
                COALESCE(IM.total, 0) as incomeMaterial,
                COALESCE(IR.total, 0) as incomeReceipt,
                COALESCE(IE.total, 0) as incomeEquipment,
                COALESCE(ISUB.total, 0) as incomeSubcontractor

            FROM param_jobs J

            LEFT JOIN workorder W 
                ON W.fk_id_job = J.id_job
                $whereWO

            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.hours) as total_hours
                FROM workorder_personal P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                $whereSub
                GROUP BY W.fk_id_job
            ) HP ON HP.fk_id_job = J.id_job

            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) as total
                FROM workorder_personal P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                $whereSub
                GROUP BY W.fk_id_job
            ) IP ON IP.fk_id_job = J.id_job

            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) as total
                FROM workorder_materials P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                $whereSub
                GROUP BY W.fk_id_job
            ) IM ON IM.fk_id_job = J.id_job

            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) as total
                FROM workorder_receipt P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                $whereSub
                GROUP BY W.fk_id_job
            ) IR ON IR.fk_id_job = J.id_job

            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) as total
                FROM workorder_equipment P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                $whereSub
                GROUP BY W.fk_id_job
            ) IE ON IE.fk_id_job = J.id_job

            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) as total
                FROM workorder_ocasional P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                $whereSub
                GROUP BY W.fk_id_job
            ) ISUB ON ISUB.fk_id_job = J.id_job

            WHERE J.state = 1

            GROUP BY J.id_job
            ORDER BY J.job_description ASC
        ";

        return $this->db->query($sql, $binds)->getResultArray();
    }

    public function getJobsIncomeDashboard()
    {
        $sql = "SELECT 
                J.id_job,
                J.job_description,

                COUNT(DISTINCT W.id_workorder) AS noWO,

                COALESCE(HP.total_hours, 0) AS hoursPersonal,

                COALESCE(IP.total, 0) AS incomePersonal,
                COALESCE(IM.total, 0) AS incomeMaterial,
                COALESCE(IR.total, 0) AS incomeReceipt,
                COALESCE(IE.total, 0) AS incomeEquipment,
                COALESCE(ISUB.total, 0) AS incomeSubcontractor,

                (
                    COALESCE(IP.total, 0) +
                    COALESCE(IM.total, 0) +
                    COALESCE(IR.total, 0) +
                    COALESCE(IE.total, 0) +
                    COALESCE(ISUB.total, 0)
                ) AS totalIncome

            FROM param_jobs J

            LEFT JOIN workorder W 
                ON W.fk_id_job = J.id_job

            /* HORAS PERSONAL */
            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.hours) AS total_hours
                FROM workorder_personal P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                GROUP BY W.fk_id_job
            ) HP ON HP.fk_id_job = J.id_job

            /* INGRESOS PERSONAL */
            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) AS total
                FROM workorder_personal P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                GROUP BY W.fk_id_job
            ) IP ON IP.fk_id_job = J.id_job

            /* MATERIALES */
            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) AS total
                FROM workorder_materials P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                GROUP BY W.fk_id_job
            ) IM ON IM.fk_id_job = J.id_job

            /* RECIBOS */
            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) AS total
                FROM workorder_receipt P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                GROUP BY W.fk_id_job
            ) IR ON IR.fk_id_job = J.id_job

            /* EQUIPO */
            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) AS total
                FROM workorder_equipment P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                GROUP BY W.fk_id_job
            ) IE ON IE.fk_id_job = J.id_job

            /* SUBCONTRATOS */
            LEFT JOIN (
                SELECT W.fk_id_job, SUM(P.value) AS total
                FROM workorder_ocasional P
                INNER JOIN workorder W ON W.id_workorder = P.fk_id_workorder
                GROUP BY W.fk_id_job
            ) ISUB ON ISUB.fk_id_job = J.id_job

            WHERE J.state = 1

            GROUP BY J.id_job
            ORDER BY J.job_description ASC;";

        return $this->db->query($sql)->getResultArray();
    }
}
