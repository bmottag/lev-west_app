<?php
namespace App\Modules\Forceaccount\Models;

use CodeIgniter\Model;
use App\Libraries\Logger;

class ForceaccountModel extends Model
{
    protected $protectFields = false;
    protected $logger;

    public function __construct()
    {
        parent::__construct();
        $this->logger = new Logger();
    }

    /**
     * Forceaccounts's list
     * @since 16/04/2025
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_by_idUser($arrDatos)
    {
        $builder = $this->db->table('forceaccount W');
        $builder->select('W.*, J.id_job, job_description, CONCAT(U.first_name, " ", U.last_name) name, C.company_name company, C.id_company, A.id_acs');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');
        $builder->join('acs A', 'A.fk_id_workorder = W.id_forceaccount', 'LEFT');
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');

        if (array_key_exists('idForceAccount', $arrDatos)) {
            $builder->where('id_forceaccount', $arrDatos['idForceAccount']);
        }
        if (array_key_exists('idEmployee', $arrDatos)) {
            $builder->where('W.fk_id_user', $arrDatos['idEmployee']);
        }

        $builder->orderBy('id_forceaccount', 'desc');
        $query = $builder->get(50);

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add forceaccount
     * @since 13/1/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function add_forceaccount($post, $idUser)
    {
        $idForceaccount = $post['hddIdentificador'] ?? '';

        $data = [
            'fk_id_job'               => $post['jobName'],
            'date'                    => $post['date'],
            'fk_id_company'           => $post['company'],
            'foreman_name_wo'         => $post['foreman'],
            'foreman_movil_number_wo' => $post['movilNumber'],
            'foreman_email_wo'        => $post['email'],
            'observation'             => $post['observation'] ?? null,
            'profit'                  => $post['profit'] ?? null,
        ];

        if ($idForceaccount == '') {
            $data['fk_id_user']   = $idUser;
            $data['date_issue']   = date('Y-m-d G:i:s');
            $data['state']        = 0;
            $data['last_message'] = 'New Force Account.';

            $this->db->table('forceaccount')->insert($data);
            $idForceaccount = $this->db->insertID();

            $this->logger->user($idUser)->type('forceaccount')->id($idForceaccount)
                ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();
        } else {
            $old = $this->db->table('forceaccount')->where('id_forceaccount', $idForceaccount)->get()->getRowArray();

            $this->db->table('forceaccount')->where('id_forceaccount', $idForceaccount)->update($data);

            $this->logger->user($idUser)->type('forceaccount')->id($idForceaccount)
                ->token('update')->comment(json_encode(['old' => $old, 'new' => json_encode($data)]))->log();
        }

        return $idForceaccount ?: false;
    }

    /**
     * Get forceaccount personal info
     * @since 9/2/2021
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_personal($arrData)
    {
        $builder = $this->db->table('forceaccount_personal W');
        $builder->select("W.*, CONCAT(first_name, ' ', last_name) name, T.employee_type");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->join('param_employee_type T', 'T.id_employee_type = W.fk_id_employee_type', 'INNER');

        if (array_key_exists('idForceAccount', $arrData)) {
            $builder->where('W.fk_id_forceaccount', $arrData['idForceAccount']);
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
     * @review 07/05/2026 - new CI4 version
     */
    public function savePersonal($post, $idUser = null)
    {
        $idForceaccount = $post['hddidForceaccount'];

        $data = [
            'fk_id_forceaccount'  => $idForceaccount,
            'fk_id_user'          => $post['employee'],
            'fk_id_employee_type' => $post['type'],
            'hours'               => $post['hour'],
            'description'         => $post['description'],
        ];

        $result = $this->db->table('forceaccount_personal')->insert($data);

        $this->logger->user($idUser)->type('forceaccount_personal')->id($idForceaccount)
            ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();

        return $result;
    }

    /**
     * Get forceaccount materials info
     * @since 13/1/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_materials($arrData)
    {
        $builder = $this->db->table('forceaccount_materials W');
        $builder->join('param_material_type M', 'M.id_material = W.fk_id_material', 'INNER');

        if (array_key_exists('idForceAccount', $arrData)) {
            $builder->where('W.fk_id_forceaccount', $arrData['idForceAccount']);
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
     * @review 07/05/2026 - new CI4 version
     */
    public function saveMaterial($post, $idUser = null)
    {
        $idForceaccount = $post['hddidForceaccount'];

        $data = [
            'fk_id_forceaccount' => $idForceaccount,
            'fk_id_material'     => $post['material'],
            'quantity'           => $post['quantity'],
            'unit'               => $post['unit'],
            'markup'             => $post['markup'],
            'description'        => $post['description'],
        ];

        $result = $this->db->table('forceaccount_materials')->insert($data);

        $this->logger->user($idUser)->type('forceaccount_materials')->id($idForceaccount)
            ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();

        return $result;
    }

    /**
     * Add Ocasional
     * @since 20/2/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function saveOcasional($post, $idUser = null)
    {
        $idForceaccount = $post['hddidForceaccount'];

        $data = [
            'fk_id_forceaccount' => $idForceaccount,
            'fk_id_company'      => $post['company'],
            'equipment'          => $post['equipment'],
            'quantity'           => $post['quantity'],
            'unit'               => $post['unit'],
            'hours'              => $post['hour'],
            'markup'             => $post['markup'],
            'contact'            => $post['contact'],
            'description'        => $post['description'],
        ];

        $result = $this->db->table('forceaccount_ocasional')->insert($data);

        $this->logger->user($idUser)->type('forceaccount_ocasional')->id($idForceaccount)
            ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();

        return $result;
    }

    /**
     * Add HOLD BACK
     * @since 12/11/2018
     * @review 07/05/2026 - new CI4 version
     */
    public function saveHoldBack($post, $idUser = null)
    {
        $data = [
            'fk_id_forceaccount' => $post['hddidForceaccount'],
            'value'              => $post['value'],
            'description'        => $post['description'],
        ];

        return $this->db->table('forceaccount_hold_back')->insert($data);
    }

    /**
     * Trucks list by type1 = rentals
     * @since 8/3/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function get_trucks_by_id1()
    {
        $sql = "SELECT id_vehicle, CONCAT(unit_number,' -----> ', description) as unit_description
                FROM param_vehicle
                WHERE type_level_1 = 2 AND state = 1
                ORDER BY unit_number";

        $query  = $this->db->query($sql);
        $trucks = [];
        foreach ($query->getResultArray() as $row) {
            $trucks[] = ['id_truck' => $row['id_vehicle'], 'unit_number' => $row['unit_description']];
        }
        return $trucks;
    }

    /**
     * Add Equipment
     * @since 25/1/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function saveEquipment($post, $idUser = null)
    {
        $idForceaccount = $post['hddidForceaccount'];
        $type           = $post['type'];
        $truck          = $post['truck'] ?? null;

        if ($type == 8) {
            $truck = 5;
        }
        $standby = ($type != 3) ? 2 : ($post['standby'] ?? null);

        $data = [
            'fk_id_forceaccount' => $idForceaccount,
            'fk_id_type_2'       => $type,
            'fk_id_vehicle'      => $truck,
            'fk_id_attachment'   => $post['attachment'] ?? null,
            'other'              => $post['otherEquipment'] ?? null,
            'operatedby'         => $post['operatedby'] ?? null,
            'hours'              => $post['hour'],
            'quantity'           => $post['quantity'],
            'standby'            => $standby,
            'description'        => $post['description'] ?? null,
        ];

        $result = $this->db->table('forceaccount_equipment')->insert($data);

        $this->logger->user($idUser)->type('forceaccount_equipment')->id($idForceaccount)
            ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();

        return $result;
    }

    /**
     * Get forceaccount equipment info
     * @since 25/1/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_equipment($arrData)
    {
        $builder = $this->db->table('forceaccount_equipment W');
        $builder->select("W.*, V.make, V.model, V.unit_number, V.description v_description, M.miscellaneous, T.type_2, C.*, CONCAT(U.first_name,' ', U.last_name) as operatedby, A.attachment_number, A.attachment_description");
        $builder->join('param_vehicle V', 'V.id_vehicle = W.fk_id_vehicle', 'LEFT');
        $builder->join('param_attachments A', 'A.id_attachment = W.fk_id_attachment', 'LEFT');
        $builder->join('param_miscellaneous M', 'M.id_miscellaneous = W.fk_id_vehicle', 'LEFT');
        $builder->join('user U', 'U.id_user = W.operatedby', 'LEFT');
        $builder->join('param_vehicle_type_2 T', 'T.id_type_2 = W.fk_id_type_2', 'INNER');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');

        if (array_key_exists('idForceAccount', $arrData)) {
            $builder->where('W.fk_id_forceaccount', $arrData['idForceAccount']);
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
     * Get forceaccount ocasional info
     * @since 20/2/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_ocasional($arrData)
    {
        $builder = $this->db->table('forceaccount_ocasional W');
        $builder->select('W.*, C.company_name, H.id_hauling');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'INNER');
        $builder->join('hauling H', 'H.fk_id_submodule = W.id_forceaccount_ocasional', 'LEFT');

        if (array_key_exists('idForceAccount', $arrData)) {
            $builder->where('W.fk_id_forceaccount', $arrData['idForceAccount']);
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
     * Force Account
     * @since 21/02/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_by_idJob($arrData)
    {
        $builder = $this->db->table('forceaccount W');
        $builder->select("W.*, CONCAT(first_name, ' ', last_name) name, J.job_description, J.markup, J.notes, C.*");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');

        if (array_key_exists('jobId', $arrData) && $arrData['jobId'] != '' && $arrData['jobId'] != 0) {
            $builder->where('W.fk_id_job', $arrData['jobId']);
        }
        if (array_key_exists('idForceAccount', $arrData) && $arrData['idForceAccount'] != '' && $arrData['idForceAccount'] != 0) {
            $builder->where('W.id_forceaccount', $arrData['idForceAccount']);
        }
        if (array_key_exists('idForceAccountFrom', $arrData) && $arrData['idForceAccountFrom'] != '' && $arrData['idForceAccountFrom'] != 0) {
            $builder->where('W.id_forceaccount >=', $arrData['idForceAccountFrom']);
        }
        if (array_key_exists('idForceAccountTo', $arrData) && $arrData['idForceAccountTo'] != '' && $arrData['idForceAccountTo'] != 0) {
            $builder->where('W.id_forceaccount <=', $arrData['idForceAccountTo']);
        }
        if (array_key_exists('from', $arrData) && $arrData['from'] != '') {
            $builder->where('W.date >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != '' && $arrData['from'] != '') {
            $builder->where('W.date <=', $arrData['to']);
        }
        if (array_key_exists('state', $arrData) && $arrData['state'] != '') {
            $builder->where('W.state', $arrData['state']);
        }

        $builder->orderBy('W.id_forceaccount', 'desc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update Rate
     * @since 27/2/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function saveRate($post, $idUser, $generalModel)
    {
        $hddId          = $post['hddId'];
        $formType       = $post['formType'];
        $description    = $post['description'];
        $unit           = $post['unit'] ?? null;
        $rate           = $post['rate'];
        $quantity       = $post['quantity'];
        $hours          = $post['hours'];
        $checkPDF       = !empty($post['check_pdf']) ? 1 : 2;
        $idForceaccount = $post['hddIdWorkOrder'];

        $value = $rate * $quantity * $hours;

        $data = [
            'description' => $description,
            'rate'        => $rate,
            'value'       => $value,
            'view_pdf'    => $checkPDF,
        ];

        $table  = 'forceaccount_' . $formType;
        $oldRow = $this->db->table($table)->where('id_forceaccount_' . $formType, $hddId)->get()->getRowArray();

        switch ($formType) {
            case 'personal':
                $data['fk_id_employee_type'] = $post['type_personal'];
                $data['hours']               = $hours;

                if ($oldRow['hours'] != $hours) {
                    foreach (['wo_start_project' => 'hours_start_project', 'wo_end_project' => 'hours_end_project'] as $col => $hoursCol) {
                        $task = $generalModel->get_task([
                            'idWorkOrder' => $idForceaccount,
                            'idEmployee'  => $oldRow['fk_id_user'],
                            'column'      => $col,
                        ]);
                        if ($task) {
                            $generalModel->updateRecord([
                                'table'      => 'task',
                                'primaryKey' => 'id_task',
                                'id'         => $task[0]['id_task'],
                                'column'     => $hoursCol,
                                'value'      => $hours,
                            ]);
                        }
                    }
                }
                break;

            case 'materials':
                $markup          = $post['markup'];
                $value           = $value * ($markup + 100) / 100;
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
                $markup           = $post['markup'];
                $value            = $value * ($markup + 100) / 100;
                $data['markup']   = $markup;
                $data['value']    = $value;
                $data['hours']    = $hours;
                $data['quantity'] = $quantity;
                $data['unit']     = $unit;
                break;

            case 'hold_back':
                break;
        }

        $result = $this->db->table($table)->where('id_forceaccount_' . $formType, $hddId)->update($data);

        if (!empty($oldRow['flag_expenses'])) {
            $this->db->table('forceaccount_expense')
                ->where('fk_id_forceaccount', $idForceaccount)
                ->where('fk_id_submodule', $hddId)
                ->where('submodule', $formType)
                ->update(['expense_value' => $value]);
        }

        $log = ['old' => $oldRow, 'new' => json_encode($data)];
        $this->logger->user($idUser)->type($table)->id($idForceaccount)
            ->token('update')->comment(json_encode($log))->log();

        return $result;
    }

    /**
     * Add info boton go back
     * @since 11/11/2018
     * @review 07/05/2026 - new CI4 version
     */
    public function saveInfoGoBack($arrData, $idUser)
    {
        $this->db->table('forceaccount_go_back')->where('fk_id_user', $idUser)->delete();

        $data = [
            'fk_id_user' => $idUser,
            'post_from'  => $arrData['from'],
            'post_to'    => $arrData['to'],
        ];

        if (array_key_exists('jobId', $arrData)) {
            $data['post_id_job'] = $arrData['jobId'];
        }
        if (array_key_exists('idForceAccount', $arrData)) {
            $data['post_id_work_order'] = $arrData['idForceAccount'];
        }
        if (array_key_exists('idForceAccountFrom', $arrData)) {
            $data['post_id_wo_from'] = $arrData['idForceAccountFrom'];
        }
        if (array_key_exists('idForceAccountTo', $arrData)) {
            $data['post_id_wo_to'] = $arrData['idForceAccountTo'];
        }
        if (array_key_exists('state', $arrData)) {
            $data['post_state'] = $arrData['state'];
        }

        $this->db->table('forceaccount_go_back')->insert($data);
    }

    /**
     * Force Account info go back
     * @since 16/04/2025
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_go_back($idUser)
    {
        $query = $this->db->table('forceaccount_go_back')
            ->where('fk_id_user', $idUser)
            ->get();

        $result = $query->getRowArray();
        return $result ?: false;
    }

    /**
     * Get forceaccount additional info
     * @since 11/1/2020
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_state($idForceaccount)
    {
        $builder = $this->db->table('forceaccount_state W');
        $builder->select("W.*, U.first_name");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->where('W.fk_id_forceaccount', $idForceaccount);
        $builder->orderBy('W.id_forceaccount_state', 'desc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add forceaccount state
     * @since 11/1/2020
     * @review 07/05/2026 - new CI4 version
     */
    public function add_forceaccount_state($arrData, $idUser)
    {
        $idForceaccount = $arrData['idForceaccount'];

        $data = [
            'fk_id_forceaccount' => $idForceaccount,
            'fk_id_user'         => $idUser,
            'date_issue'         => date('Y-m-d G:i:s'),
            'observation'        => $arrData['observation'],
            'state'              => $arrData['state'],
        ];

        $result = $this->db->table('forceaccount_state')->insert($data);

        $this->logger->user($idUser)->type('forceaccount_state')->id($idForceaccount)
            ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();

        return $result;
    }

    /**
     * Contar registros de forceaccount
     * @author BMOTTAG
     * @since  7/2/2020
     * @review 07/05/2026 - new CI4 version
     */
    public function countForceaccounts($arrDatos)
    {
        $sql = "SELECT count(id_forceaccount) CONTEO FROM forceaccount W WHERE 1=1";

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
     * @review 07/05/2026 - new CI4 version
     */
    public function countHoursPersonal($arrDatos)
    {
        $sql = "SELECT ROUND(SUM(hours),2) TOTAL
                FROM forceaccount_personal P
                INNER JOIN forceaccount W on W.id_forceaccount = P.fk_id_forceaccount
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
     * @review 07/05/2026 - new CI4 version
     */
    public function countIncome($arrDatos)
    {
        $sql = "SELECT ROUND(SUM(value),2) TOTAL
                FROM " . $arrDatos['table'] . " P
                INNER JOIN forceaccount W on W.id_forceaccount = P.fk_id_forceaccount
                WHERE W.fk_id_job = " . (int)$arrDatos['idJob'];

        if (array_key_exists('idForceAccount', $arrDatos)) {
            $sql .= " AND P.fk_id_forceaccount = " . (int)$arrDatos['idForceAccount'];
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
     * @review 07/05/2026 - new CI4 version
     */
    public function info_foreman($idForeman, $post)
    {
        $data = [
            'foreman_name'         => $post['foreman'],
            'foreman_movil_number' => $post['movilNumber'],
            'foreman_email'        => $post['email'],
        ];

        if ($idForeman == '') {
            $data['fk_id_job']           = $post['jobName'];
            $data['fk_id_param_company'] = $post['company'];
            return $this->db->table('param_company_foreman')->insert($data);
        } else {
            return $this->db->table('param_company_foreman')
                ->where('id_company_foreman', $idForeman)
                ->update($data);
        }
    }

    /**
     * Get Prices for forceaccount personal
     * @since 13/1/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_personal_prices($idForceaccount, $idJob)
    {
        $builder = $this->db->table('forceaccount_personal W');
        $builder->select("W.*, T.job_employee_type_unit_price");
        $builder->join('job_employee_type_price T', 'T.fk_id_employee_type = W.fk_id_employee_type', 'INNER');
        $builder->where('W.fk_id_forceaccount', $idForceaccount);
        $builder->where('T.fk_id_job', $idJob);
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update WO personal rate and value
     * @since 7/11/2020
     * @review 07/05/2026 - new CI4 version
     */
    public function update_wo_personal_rate($forceaccountPersonalRate)
    {
        $result = true;
        if ($forceaccountPersonalRate) {
            foreach ($forceaccountPersonalRate as $item) {
                $rate  = $item['job_employee_type_unit_price'];
                $hours = $item['hours'];
                $value = $rate * $hours;

                $result = $this->db->table('forceaccount_personal')
                    ->where('id_forceaccount_personal', $item['id_forceaccount_personal'])
                    ->update(['rate' => $rate, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Get Prices for forceaccount equipment
     * @since 10/11/2020
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_equipment_prices($idForceaccount, $idJob)
    {
        $builder = $this->db->table('forceaccount_equipment W');
        $builder->select("W.*, T.job_equipment_unit_price, T.job_equipment_without_driver");
        $builder->join('job_equipment_price T', 'T.fk_id_equipment = W.fk_id_vehicle', 'INNER');
        $builder->where('W.fk_id_forceaccount', $idForceaccount);
        $builder->where('W.fk_id_type_2 !=', 8);
        $builder->where('T.fk_id_job', $idJob);
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update WO equipment rate and value
     * @since 10/11/2020
     * @review 07/05/2026 - new CI4 version
     */
    public function update_wo_equipment_rate($forceaccountEquipmentRate)
    {
        $result = true;
        if ($forceaccountEquipmentRate) {
            foreach ($forceaccountEquipmentRate as $item) {
                $standby  = $item['standby'];
                $rate     = ($standby == 1) ? $item['job_equipment_without_driver'] : $item['job_equipment_unit_price'];
                $hours    = $item['hours'];
                $quantity = $item['quantity'] == 0 ? 1 : $item['quantity'];
                $value    = $rate * $quantity * $hours;

                $result = $this->db->table('forceaccount_equipment')
                    ->where('id_forceaccount_equipment', $item['id_forceaccount_equipment'])
                    ->update(['rate' => $rate, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Get Prices for forceaccount material
     * @since 16/12/2020
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_material_prices($idForceaccount)
    {
        $builder = $this->db->table('forceaccount_materials W');
        $builder->select("W.*, T.material_price");
        $builder->join('param_material_type T', 'T.id_material = W.fk_id_material', 'INNER');
        $builder->where('W.fk_id_forceaccount', $idForceaccount);
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update WO material rate and value
     * @since 18/12/2020
     * @review 07/05/2026 - new CI4 version
     */
    public function update_wo_material_rate($forceaccountMaterialRate)
    {
        $result = true;
        if ($forceaccountMaterialRate) {
            foreach ($forceaccountMaterialRate as $item) {
                $rate     = $item['material_price'];
                $quantity = $item['quantity'];
                $value    = $rate * $quantity;

                $result = $this->db->table('forceaccount_materials')
                    ->where('id_forceaccount_materials', $item['id_forceaccount_materials'])
                    ->update(['rate' => $rate, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Update state and last message
     * @since 23/12/2020
     * @review 07/05/2026 - new CI4 version
     */
    public function update_forceaccount($arrData)
    {
        $data = [
            'state'        => $arrData['state'],
            'last_message' => $arrData['lastMessage'],
        ];

        return $this->db->table('forceaccount')
            ->where('id_forceaccount', $arrData['idForceaccount'])
            ->update($data);
    }

    /**
     * Get forceaccount Receipt info
     * @since 4/1/2021
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_receipt($arrData)
    {
        $builder = $this->db->table('forceaccount_receipt W');

        if (array_key_exists('idForceAccount', $arrData)) {
            $builder->where('W.fk_id_forceaccount', $arrData['idForceAccount']);
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
     * @review 07/05/2026 - new CI4 version
     */
    public function saveReceipt($post, $idUser = null)
    {
        $idForceaccount = $post['hddidForceaccount'];
        $idWOReceipt    = $post['hddId'] ?? '';
        $price          = $post['price'] ?? 0;
        $markup         = $post['markup'] ?? 0;
        $checkPDF       = !empty($post['check_pdf']) ? 1 : 2;

        if (!$price) $price = 0;
        if (!$markup) $markup = 0;

        $data = [
            'place'       => $post['place'],
            'price'       => $price,
            'markup'      => $markup,
            'description' => $post['description'],
        ];

        if ($idWOReceipt == '') {
            $price              = $price / 1.05;
            $value              = $price + ($price * $markup / 100);
            $data['fk_id_forceaccount'] = $idForceaccount;
            $data['view_pdf']   = 1;
            $data['value']      = $value;

            $result = $this->db->table('forceaccount_receipt')->insert($data);

            $this->logger->user($idUser)->type('forceaccount_receipt')->id($idForceaccount)
                ->token('insert')->comment(json_encode(['old' => null, 'new' => json_encode($data)]))->log();
        } else {
            $oldRow = $this->db->table('forceaccount_receipt')
                ->where('id_forceaccount_receipt', $idWOReceipt)
                ->get()->getRowArray();

            $price          = $price / 1.05;
            $value          = $price + ($price * $markup / 100);
            $data['markup'] = $markup;
            $data['value']  = $value;
            $data['view_pdf'] = $checkPDF;

            $result = $this->db->table('forceaccount_receipt')
                ->where('id_forceaccount_receipt', $idWOReceipt)
                ->update($data);

            $this->logger->user($idUser)->type('forceaccount_receipt')->id($idForceaccount)
                ->token('update')->comment(json_encode(['old' => $oldRow, 'new' => json_encode($data)]))->log();
        }

        return $result;
    }

    /**
     * Update WO INVOICE markup and value
     * @since 4/1/2021
     * @review 07/05/2026 - new CI4 version
     */
    public function update_wo_invoice_markup($forceaccountReceipt, $markup)
    {
        $result = true;
        if ($forceaccountReceipt) {
            foreach ($forceaccountReceipt as $item) {
                $price = $item['price'] / 1.05;
                $value = $price + ($price * $markup / 100);

                $result = $this->db->table('forceaccount_receipt')
                    ->where('id_forceaccount_receipt', $item['id_forceaccount_receipt'])
                    ->update(['markup' => $markup, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Update WO MATERIAL markup and value
     * @since 5/1/2021
     * @review 07/05/2026 - new CI4 version
     */
    public function update_wo_material_markup($forceaccountMaterials, $markup)
    {
        $result = true;
        if ($forceaccountMaterials) {
            foreach ($forceaccountMaterials as $item) {
                $value = $item['rate'] * $item['quantity'];
                $value = $value * ($markup + 100) / 100;

                $result = $this->db->table('forceaccount_materials')
                    ->where('id_forceaccount_materials', $item['id_forceaccount_materials'])
                    ->update(['markup' => $markup, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Update WO OCASIONAL markup and value
     * @since 5/1/2021
     * @review 07/05/2026 - new CI4 version
     */
    public function update_wo_ocasional_markup($forceaccountOcasional, $markup)
    {
        $result = true;
        if ($forceaccountOcasional) {
            foreach ($forceaccountOcasional as $item) {
                $value = $item['rate'] * $item['quantity'] * $item['hours'];
                $value = $value * ($markup + 100) / 100;

                $result = $this->db->table('forceaccount_ocasional')
                    ->where('id_forceaccount_ocasional', $item['id_forceaccount_ocasional'])
                    ->update(['markup' => $markup, 'value' => $value]);
            }
        }
        return $result;
    }

    /**
     * Update WO state
     * @since 12/1/2021
     * @review 07/05/2026 - new CI4 version
     */
    public function updateWOState($post, $idUser)
    {
        $state       = $post['state'];
        $information = $post['information'];
        $result      = true;

        if (!empty($post['wo'])) {
            foreach ($post['wo'] as $woId) {
                $stateData = [
                    'fk_id_forceaccount' => $woId,
                    'fk_id_user'         => $idUser,
                    'date_issue'         => date('Y-m-d G:i:s'),
                    'observation'        => $information,
                    'state'              => $state,
                ];
                $result = $this->db->table('forceaccount_state')->insert($stateData);

                $result = $this->db->table('forceaccount')
                    ->where('id_forceaccount', $woId)
                    ->update(['state' => $state, 'last_message' => $information]);
            }
        }
        return $result;
    }

    /**
     * Get forceaccount expenses info
     * @since 13/1/2023
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_expense($arrData)
    {
        $builder = $this->db->table('forceaccount_expense W');
        $builder->join('job_details J', 'J.id_job_detail = W.fk_id_job_detail', 'INNER');

        if (array_key_exists('idForceAccount', $arrData)) {
            $builder->where('W.fk_id_forceaccount', $arrData['idForceAccount']);
        }
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add expense
     * @since 13/1/2023
     * @review 07/05/2026 - new CI4 version
     */
    public function saveExpense($post, $idUser = null)
    {
        $idForceaccount = $post['hddidForceaccount'];
        $this->db->table('forceaccount_expense')->where('fk_id_forceaccount', $idForceaccount)->delete();

        $result = true;
        if (!empty($post['expense'])) {
            foreach ($post['expense'] as $expense) {
                $data   = ['fk_id_forceaccount' => $idForceaccount, 'fk_id_job_detail' => $expense];
                $result = $this->db->table('forceaccount_expense')->insert($data);
            }
        }
        return $result;
    }

    /**
     * Update WO Expenses Values
     * @since 21/1/2023
     * @review 07/05/2026 - new CI4 version
     */
    public function updateExpensesValues($forceaccountExpenses, $totalWOIncome, $sumPercentageExpense)
    {
        $result = true;
        if ($forceaccountExpenses) {
            foreach ($forceaccountExpenses as $item) {
                $expenseValue = $item['percentage'] * $totalWOIncome / $sumPercentageExpense;
                $result       = $this->db->table('forceaccount_expense')
                    ->where('id_forceaccount_expense', $item['id_forceaccount_expense'])
                    ->update(['expense_value' => $expenseValue]);
            }
        }
        return $result;
    }

    /**
     * Sumatoria de valores de porcentage para los gastos guardados
     * @author BMOTTAG
     * @since  24/1/2023
     * @review 07/05/2026 - new CI4 version
     */
    public function sumPercentageExpense($arrDatos)
    {
        $sql = "SELECT ROUND(SUM(percentage),2) TOTAL
                FROM forceaccount_expense W
                INNER JOIN job_details J on J.id_job_detail = W.fk_id_job_detail
                WHERE W.fk_id_forceaccount = " . (int)$arrDatos['idForceAccount'];

        $row = $this->db->query($sql)->getRow();
        return $row->TOTAL;
    }

    /**
     * Force Account Log
     * @since 20/02/2024
     * @review 07/05/2026 - new CI4 version
     */
    public function get_forceaccount_log($arrData)
    {
        $builder = $this->db->table('logger L');
        $builder->select("L.*, CONCAT(first_name, ' ', last_name) name, J.job_description");
        $builder->join('user U', 'U.id_user = L.created_by', 'INNER');
        $builder->join('forceaccount W', 'L.type_id = W.id_forceaccount', 'LEFT');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');

        $parameters = ['forceaccount', 'forceaccount_state', 'forceaccount_personal', 'forceaccount_materials', 'forceaccount_equipment', 'forceaccount_receipt', 'forceaccount_ocasional'];
        $builder->whereIn('L.type', $parameters);

        if (array_key_exists('jobId', $arrData) && $arrData['jobId'] != '' && $arrData['jobId'] != 0) {
            $builder->where('J.id_job', $arrData['jobId']);
        }
        if (array_key_exists('idForceAccount', $arrData) && $arrData['idForceAccount'] != '' && $arrData['idForceAccount'] != 0) {
            $builder->where('L.type_id', $arrData['idForceAccount']);
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
     * @review 07/05/2026 - new CI4 version
     */
    public function saveFAExpenses($post, $idUser = null)
    {
        $idForceAccount = $post['hddidForceaccount'];
        $idJobDetail    = $post['item_job_detail'];
        $result         = true;

        if (!empty($post['item'])) {
            foreach ($post['item'] as $item) {
                $parts = explode('__', $item);
                $data  = [
                    'fk_id_forceaccount' => $idForceAccount,
                    'fk_id_job_detail'   => $idJobDetail,
                    'submodule'          => $parts[0],
                    'fk_id_submodule'    => $parts[1],
                    'expense_value'      => $parts[2],
                ];
                $result = $this->db->table('forceaccount_expense')->insert($data);

                $result = $this->db->table('forceaccount_' . $parts[0])
                    ->where('id_forceaccount_' . $parts[0], $parts[1])
                    ->update(['flag_expenses' => 1]);
            }
        }
        return $result;
    }

    /**
     * Delete Expenses
     * @since 2/05/2024
     * @review 07/05/2026 - new CI4 version
     */
    public function deleteExpenses($arrDatos)
    {
        return $this->db->table('forceaccount_expense')
            ->where([
                'fk_id_forceaccount' => $arrDatos['fk_id_forceaccount'],
                'submodule'          => $arrDatos['submodule'],
                'fk_id_submodule'    => $arrDatos['fk_id_submodule'],
            ])
            ->delete();
    }

    /**
     * Clone forceaccount to create Accounting Control Sheet (ACS)
     * @since 09/01/2025
     * @review 07/05/2026 - new CI4 version
     */
    public function clone_forceaccount($arrData)
    {
        $fa = $arrData['forceaccount'][0];

        $data   = [
            'fk_id_forceaccount'      => $fa['id_forceaccount'],
            'fk_id_user'              => $fa['fk_id_user'],
            'fk_id_job'               => $fa['fk_id_job'],
            'date_issue'              => date('Y-m-d G:i:s'),
            'date'                    => $fa['date'],
            'fk_id_company'           => $fa['fk_id_company'],
            'foreman_name_wo'         => $fa['foreman_name_wo'],
            'foreman_movil_number_wo' => $fa['foreman_movil_number_wo'],
            'foreman_email_wo'        => $fa['foreman_email_wo'],
            'observation'             => $fa['observation'],
            'state'                   => $fa['state'],
            'last_message'            => $fa['last_message'],
        ];
        $result = $this->db->table('acs')->insert($data);
        $idACS  = $this->db->insertID();

        if (!empty($arrData['forceaccountPersonal'])) {
            foreach ($arrData['forceaccountPersonal'] as $personal) {
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

        if (!empty($arrData['forceaccountMaterials'])) {
            foreach ($arrData['forceaccountMaterials'] as $material) {
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

        if (!empty($arrData['forceaccountReceipt'])) {
            foreach ($arrData['forceaccountReceipt'] as $receipt) {
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

        if (!empty($arrData['forceaccountEquipment'])) {
            foreach ($arrData['forceaccountEquipment'] as $equipment) {
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

        if (!empty($arrData['forceaccountOcasional'])) {
            foreach ($arrData['forceaccountOcasional'] as $ocasional) {
                $this->db->table('acs_ocasional')->insert([
                    'fk_id_acs'      => $idACS,
                    'fk_id_company'  => $ocasional['fk_id_company'],
                    'equipment'      => $ocasional['equipment'],
                    'quantity'       => $ocasional['quantity'],
                    'unit'           => $ocasional['unit'],
                    'hours'          => $ocasional['hours'],
                    'rate'           => $ocasional['rate'],
                    'markup'         => $ocasional['markup'],
                    'value'          => $ocasional['value'],
                    'contact'        => $ocasional['contact'],
                    'description'    => $ocasional['description'],
                    'view_pdf'       => $ocasional['view_pdf'],
                ]);
            }
        }

        return $result;
    }

    /**
     * Add/Edit Subcontractors Invoices
     * @since 13/02/2025
     * @review 07/05/2026 - new CI4 version
     */
    public function saveSubcontractorInvoice($archivo, $post)
    {
        $idSubcontractorInvoice = $post['hddIdentificador'] ?? '';
        $idSubcontractor        = $post['company'] ?? '';

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

        if ($archivo != 'xxx') {
            $data['file'] = $archivo;
        }

        if ($idSubcontractorInvoice == '') {
            $this->db->table('subcontractor_invoice')->insert($data);
            $idSubcontractorInvoice = $this->db->insertID();
        } else {
            $this->db->table('subcontractor_invoice')
                ->where('id_subcontractor_invoice', $idSubcontractorInvoice)
                ->update($data);
        }

        return $idSubcontractorInvoice ?: false;
    }

    /**
     * Subcontractors list
     * @since 16/04/2025
     * @review 07/05/2026 - new CI4 version
     */
    public function get_subcontractors_invoice($arrDatos)
    {
        $builder = $this->db->table('subcontractor_invoice S');
        $builder->select('S.*, C.company_name company');
        $builder->join('param_company C', 'C.id_company = S.fk_id_company', 'INNER');

        if (array_key_exists('idSubcontractorInvoice', $arrDatos)) {
            $builder->where('id_subcontractor_invoice', $arrDatos['idSubcontractorInvoice']);
        }

        $builder->orderBy('id_subcontractor_invoice', 'desc');
        $query = $builder->get(50);

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }
}
