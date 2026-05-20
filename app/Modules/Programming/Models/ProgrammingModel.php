<?php
namespace App\Modules\Programming\Models;

use CodeIgniter\Model;
use App\Models\GeneralModel;

class ProgrammingModel extends Model
{
    protected $protectFields = false;
    protected $generalModel;

    public function __construct()
    {
        parent::__construct();
        $this->generalModel = new GeneralModel();
    }

    /**
     * Add/Edit PROGRAMMING
     * @since 2/7/2018
     * @review 01/05/2026 - new CI4 version
     */
    public function saveProgramming()
    {
        $request       = service('request');
        $idUser        = session()->get('id');
        $idProgramming = $request->getPost('hddId');
        $parentId      = $request->getPost('hddIdParent');
        $flagDate      = ($request->getPost('job_planning') == 1) ? $request->getPost('flag_date') : 1;

        $data = [
            'fk_id_job'   => $request->getPost('jobName'),
            'observation' => $request->getPost('observation'),
            'flag_date'   => $flagDate,
        ];

        if ($flagDate == 2 && $parentId == '') {
            $data['date_programming'] = formatear_fecha($request->getPost('from'));
            $data['date_to']          = formatear_fecha($request->getPost('to'));
            $data['apply_for']        = $request->getPost('apply_for');
        } else {
            $data['date_programming'] = $request->getPost('date');
        }

        if ($idProgramming == '') {
            $data['fk_id_user'] = $idUser;
            $data['date_issue'] = date('Y-m-d G:i:s');
            $data['state']      = 1;
            $this->db->table('programming')->insert($data);
            $idProgramming = $this->db->insertID();
        } else {
            $this->db->table('programming')->where('id_programming', $idProgramming)->update($data);

            $result          = $this->generalModel->get_basic_search([
                'table'  => 'programming',
                'order'  => 'id_programming',
                'column' => 'id_programming',
                'id'     => $idProgramming,
            ]);
            $fk_id_workorder = $result[0]['fk_id_workorder'];

            if ($fk_id_workorder) {
                $this->db->table('workorder')->where('id_workorder', $fk_id_workorder)->update([
                    'fk_id_job'   => $data['fk_id_job'],
                    'observation' => $data['observation'],
                    'date'        => $data['date_programming'],
                    'date_issue'  => $data['date_programming'] . ' ' . date('G:i:s'),
                ]);
            }
        }

        return $idProgramming ?: false;
    }

    /**
     * Add Period Programming
     * @since 16/10/2023
     * @review 01/05/2026 - new CI4 version
     */
    public function savePeriodProgramming($date, $idParent)
    {
        $request = service('request');
        $this->db->table('programming')->insert([
            'fk_id_user'       => session()->get('id'),
            'date_issue'       => date('Y-m-d G:i:s'),
            'fk_id_job'        => $request->getPost('jobName'),
            'observation'      => $request->getPost('observation'),
            'date_programming' => $date,
            'parent_id'        => $idParent,
            'apply_for'        => $request->getPost('apply_for'),
            'flag_date'        => $request->getPost('flag_date'),
            'state'            => 1,
        ]);
        $idProgramming = $this->db->insertID();
        return $idProgramming ?: false;
    }

    /**
     * Add PROGRAMMING WORKER
     * @since 16/1/2019
     * @review 01/05/2026 - new CI4 version
     */
    public function addProgrammingWorker()
    {
        $request = service('request');
        $result  = true;
        if ($workers = $request->getPost('workers')) {
            foreach ($workers as $workerId) {
                $result = $this->db->table('programming_worker')->insert([
                    'fk_id_programming'      => $request->getPost('hddId'),
                    'fk_id_programming_user' => $workerId,
                    'fk_id_employee_type'    => 1,
                    'fk_id_hour'             => 15,
                    'site'                   => 1,
                ]);
            }
        }
        return (bool) $result;
    }

    /**
     * Verify if the project already exist for that date
     * @author BMOTTAG
     * @since  15/1/2019
     * @review 01/05/2026 - new CI4 version
     */
    public function verifyProject($arrData)
    {
        $builder = $this->db->table('programming');
        $builder->where('fk_id_job', $arrData['idJob']);
        $builder->where('date_programming', $arrData['date']);
        $builder->where('state !=', 3);
        return $builder->get()->getNumRows() >= 1;
    }

    /**
     * Cambia el estado de la programacion
     * @since 8/7/2018
     * @review 01/05/2026 - new CI4 version
     */
    public function deleteProgramming()
    {
        $idProgramming = service('request')->getPost('identificador');
        $result        = $this->db->table('programming')
            ->where('id_programming', $idProgramming)
            ->update(['state' => 3]);
        return (bool) $result;
    }

    /**
     * Lista de vehiculos para asignarlos a trabajadores en la programacion
     * @since 16/1/2019
     * @review 01/05/2026 - new CI4 version
     */
    public function get_vehicles_inspection($arrData)
    {
        $sql = "SELECT id_vehicle, CONCAT(unit_number,' -----> ', description) as unit_description
                FROM param_vehicle V
                INNER JOIN param_vehicle_type_2 T ON T.id_type_2 = V.type_level_2
                WHERE fk_id_company = 1
                AND T.link_inspection != 'NA' AND V.id_vehicle NOT IN(41,42,43,44,61,62) AND V.state = 1 AND V.so_blocked = 1";

        if (!empty($arrData['vehicleToExclude'])) {
            $sql .= ' AND V.id_vehicle NOT IN (' . implode(',', $arrData['vehicleToExclude']) . ')';
        }
        $sql .= ' ORDER BY unit_number';

        $query  = $this->db->query($sql);
        $trucks = [];
        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $i => $row) {
                $trucks[$i]['id_truck']    = $row['id_vehicle'];
                $trucks[$i]['unit_number'] = $row['unit_description'];
            }
        }
        return $trucks;
    }

    /**
     * Lista de equipment ID para una fecha
     * @since 17/02/2025
     * @review 01/05/2026 - new CI4 version
     */
    public function get_vehicles_selected($filters)
    {
        $sql   = "SELECT fk_id_machine FROM programming_worker W
                  INNER JOIN programming P ON P.id_programming = W.fk_id_programming
                  WHERE P.id_programming != ? AND P.date_programming = ? AND fk_id_machine IS NOT NULL";
        $query  = $this->db->query($sql, [$filters[0]['id_programming'], $filters[0]['date_programming']]);
        $trucks = [];
        if ($query->getNumRows() > 0) {
            foreach ($query->getResultArray() as $row) {
                $machines = json_decode($row['fk_id_machine'], true);
                if (is_array($machines)) {
                    $trucks = array_merge($trucks, $machines);
                }
            }
        }
        return $trucks;
    }

    /**
     * Update worker
     * @since 16/1/2019
     * @review 01/05/2026 - new CI4 version
     */
    public function saveWorker()
    {
        $request = service('request');
        $hddId   = $request->getPost('hddId');

        $maquina = null;
        if (!empty($request->getPost('machine'))) {
            $maquina = '[' . implode(',', $request->getPost('machine')) . ']';
        }

        $result = $this->db->table('programming_worker')
            ->where('id_programming_worker', $hddId)
            ->update([
                'fk_id_employee_type' => $request->getPost('type'),
                'description'         => $request->getPost('description'),
                'fk_id_machine'       => $maquina,
                'fk_id_hour'          => $request->getPost('hora_inicio'),
                'site'                => $request->getPost('site'),
                'safety'              => $request->getPost('safety'),
                'creat_wo'            => $request->getPost('creat_wo'),
            ]);

        $woResult = $this->generalModel->get_basic_search([
            'table'  => 'workorder_personal',
            'order'  => 'fk_id_programming_worker',
            'column' => 'fk_id_programming_worker',
            'id'     => $hddId,
        ]);
        if ($woResult) {
            $this->db->table('workorder_personal')
                ->where('fk_id_programming_worker', $hddId)
                ->update([
                    'fk_id_employee_type' => $request->getPost('type'),
                    'description'         => $request->getPost('description'),
                ]);
        }

        return (bool) $result;
    }

    /**
     * Contar trabajadores para una programacion
     * @since  17/1/2019
     * @review 01/05/2026 - new CI4 version
     */
    public function countWorkers($idProgramming)
    {
        $query = $this->db->query(
            'SELECT count(id_programming_worker) CONTEO FROM programming_worker WHERE fk_id_programming = ?',
            [$idProgramming]
        );
        return $query->getRow()->CONTEO;
    }

    /**
     * Save one worker
     * @since 19/1/2019
     * @review 01/05/2026 - new CI4 version
     */
    public function saveOneWorkerProgramming()
    {
        $request       = service('request');
        $idProgramming = $request->getPost('hddId');

        $result                = $this->db->table('programming_worker')->insert([
            'fk_id_programming'      => $idProgramming,
            'fk_id_programming_user' => $request->getPost('worker'),
            'fk_id_employee_type'    => 1,
            'fk_id_hour'             => 15,
            'site'                   => 1,
        ]);
        $id_programming_worker = $this->db->insertID();

        $programData     = $this->generalModel->get_basic_search([
            'table'  => 'programming',
            'order'  => 'id_programming',
            'column' => 'id_programming',
            'id'     => $idProgramming,
        ]);
        $fk_id_workorder = $programData[0]['fk_id_workorder'];

        if ($fk_id_workorder) {
            $this->db->table('workorder_personal')->insert([
                'fk_id_workorder'          => $fk_id_workorder,
                'fk_id_user'               => $request->getPost('worker'),
                'fk_id_employee_type'      => 1,
                'hours'                    => 0,
                'fk_id_programming_worker' => $id_programming_worker,
            ]);
        }

        return (bool) $result;
    }

    /**
     * Save worker for Flash Planning
     * @since 28/12/2022
     * @review 01/05/2026 - new CI4 version
     */
    public function saveWorkerFashPlanning($idProgramming, $idHora)
    {
        $request = service('request');
        $result  = $this->db->table('programming_worker')->insert([
            'fk_id_programming'      => $idProgramming,
            'fk_id_programming_user' => $request->getPost('worker'),
            'fk_id_employee_type'    => 1,
            'fk_id_machine'          => '[' . $request->getPost('machine') . ']',
            'fk_id_hour'             => $idHora,
            'site'                   => 2,
            'safety'                 => 1,
        ]);
        return (bool) $result;
    }

    /**
     * Save child workers for children
     * @since 21/10/2023
     * @review 01/05/2026 - new CI4 version
     */
    public function saveChildWorkers($idProgramming, $informationWorker)
    {
        $result = true;
        foreach ($informationWorker as $worker) {
            $result = $this->db->table('programming_worker')->insert([
                'fk_id_programming'      => $idProgramming,
                'fk_id_programming_user' => $worker['fk_id_programming_user'],
                'fk_id_hour'             => $worker['fk_id_hour'],
                'site'                   => $worker['site'],
                'description'            => $worker['description'],
                'fk_id_machine'          => $worker['fk_id_machine'],
                'safety'                 => $worker['safety'],
            ]);
        }
        return (bool) $result;
    }

    /**
     * Update worker SMS status
     * @since 22/10/2023
     * @review 01/05/2026 - new CI4 version
     */
    public function updateSMSWorkerStatus($idProgrammingWorker, $smsStatus, $smsSID)
    {
        $result = $this->db->table('programming_worker')
            ->where('id_programming_worker', $idProgrammingWorker)
            ->update(['sms_sent' => 1, 'sms_status' => $smsStatus, 'sms_sid' => $smsSID]);
        return (bool) $result;
    }

    /**
     * Add Clone
     * @since 28/10/2023
     * @review 01/05/2026 - new CI4 version
     */
    public function createClone($infoPlanning)
    {
        $this->db->table('programming')->insert([
            'fk_id_user'       => session()->get('id'),
            'date_issue'       => date('Y-m-d G:i:s'),
            'fk_id_job'        => $infoPlanning[0]['fk_id_job'],
            'observation'      => $infoPlanning[0]['observation'],
            'date_programming' => service('request')->getPost('date'),
            'parent_id'        => '',
            'apply_for'        => '',
            'flag_date'        => '',
            'state'            => 1,
        ]);
        $idProgramming = $this->db->insertID();
        return $idProgramming ?: false;
    }

    /**
     * Get programming materials info
     * @since 20/1/2024
     * @review 01/05/2026 - new CI4 version
     */
    public function get_programming_materials($arrData)
    {
        $builder = $this->db->table('programming_material P');
        $builder->join('param_material_type M', 'M.id_material = P.fk_id_material', 'inner');
        if (array_key_exists('idProgramming', $arrData)) {
            $builder->where('P.fk_id_programming', $arrData['idProgramming']);
        }
        $builder->orderBy('M.material', 'asc');
        $query = $builder->get();
        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add Material
     * @since 20/1/2024
     * @review 01/05/2026 - new CI4 version
     */
    public function saveMaterial()
    {
        $request = service('request');
        $this->db->table('programming_material')->insert([
            'fk_id_programming' => $request->getPost('hddidProgramming'),
            'fk_id_material'    => $request->getPost('material'),
            'quantity'          => $request->getPost('quantity'),
            'unit'              => $request->getPost('unit'),
            'description'       => $request->getPost('description'),
        ]);
        $id_programming_materials = $this->db->insertID();

        $programData     = $this->generalModel->get_basic_search([
            'table'  => 'programming',
            'order'  => 'id_programming',
            'column' => 'id_programming',
            'id'     => $request->getPost('hddidProgramming'),
        ]);
        $fk_id_workorder = $programData[0]['fk_id_workorder'];

        if ($fk_id_workorder) {
            $this->db->table('workorder_materials')->insert([
                'fk_id_workorder'             => $fk_id_workorder,
                'fk_id_material'              => $request->getPost('material'),
                'quantity'                    => $request->getPost('quantity'),
                'unit'                        => $request->getPost('unit'),
                'description'                 => $request->getPost('description'),
                'fk_id_programming_materials' => $id_programming_materials,
            ]);
        }

        return $id_programming_materials > 0;
    }

    /**
     * Updated Material
     * @since 20/1/2024
     * @review 01/05/2026 - new CI4 version
     */
    public function updatedMaterial()
    {
        $request = service('request');
        $hddId   = $request->getPost('hddId');
        $data    = [
            'quantity'    => $request->getPost('quantity'),
            'unit'        => $request->getPost('unit'),
            'description' => $request->getPost('description'),
        ];

        $result   = $this->db->table('programming_material')
            ->where('id_programming_material', $hddId)
            ->update($data);

        $woResult = $this->generalModel->get_basic_search([
            'table'  => 'workorder_materials',
            'order'  => 'fk_id_programming_materials',
            'column' => 'fk_id_programming_materials',
            'id'     => $hddId,
        ]);
        if ($woResult) {
            $this->db->table('workorder_materials')
                ->where('fk_id_programming_materials', $hddId)
                ->update($data);
        }

        return (bool) $result;
    }

    /**
     * Add workorder
     * @since 23/1/2023
     * @review 01/05/2026 - new CI4 version
     */
    public function add_workorder($arrDatos)
    {
        $this->db->table('workorder')->insert([
            'fk_id_job'               => $arrDatos['idJob'],
            'date'                    => $arrDatos['date'],
            'fk_id_user'              => $arrDatos['idUser'],
            'date_issue'              => date('Y-m-d G:i:s'),
            'state'                   => 0,
            'last_message'            => $arrDatos['message'],
            'fk_id_company'           => $arrDatos['idCompany'],
            'foreman_name_wo'         => $arrDatos['foremanName'],
            'foreman_movil_number_wo' => $arrDatos['foremanMovil'],
            'foreman_email_wo'        => $arrDatos['foremanEmail'],
            'observation'             => $arrDatos['observation'],
        ]);
        $idWorkorder = $this->db->insertID();
        return $idWorkorder ?: false;
    }

    /**
     * Add workorder state
     * @since 24/01/2024
     * @review 01/05/2026 - new CI4 version
     */
    public function add_workorder_state($arrData)
    {
        $result = $this->db->table('workorder_state')->insert([
            'fk_id_workorder' => $arrData['idWorkorder'],
            'fk_id_user'      => $arrData['idUser'],
            'date_issue'      => date('Y-m-d G:i:s'),
            'observation'     => $arrData['observation'],
            'state'           => $arrData['state'],
        ]);
        return (bool) $result;
    }

    /**
     * Add item to workorder table
     * @since 13/1/2017
     * @review 01/05/2026 - new CI4 version
     */
    public function add_item_workorder($table, $data)
    {
        $this->db->table($table)->insert($data);
        if ($this->db->affectedRows() > 0) {
            return $this->db->insertID();
        }
        return false;
    }

    /**
     * Get programming occasional info
     * @since 20/2/2017
     * @review 01/05/2026 - new CI4 version
     */
    public function get_programming_occasional($arrData)
    {
        $idProgramming = $arrData['idProgramming'];
        $sql           = "SELECT P.*, C.company_name, C.does_hauling
                          FROM programming_ocasional P
                          INNER JOIN param_company C ON C.id_company = P.fk_id_company
                          WHERE (P.fk_id_programming = ? OR ? IS NULL)
                          ORDER BY C.company_name ASC";
        $query = $this->db->query($sql, [$idProgramming, $idProgramming]);
        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add Subcontractor
     * @since 20/1/2024
     * @review 01/05/2026 - new CI4 version
     */
    public function saveOcasional()
    {
        $request  = service('request');
        $result   = $this->generalModel->get_basic_search([
            'table'  => 'param_company',
            'order'  => 'id_company',
            'column' => 'id_company',
            'id'     => $request->getPost('company'),
        ]);
        $hauling  = $result[0]['does_hauling'];
        $query    = false;

        if ($hauling == 2) {
            $query = $this->db->table('programming_ocasional')->insert([
                'fk_id_programming' => $request->getPost('hddidProgramming'),
                'fk_id_company'     => $request->getPost('company'),
                'equipment'         => $request->getPost('equipment'),
                'quantity'          => $request->getPost('quantity'),
                'unit'              => $request->getPost('unit'),
                'hours'             => $request->getPost('hour'),
                'contact'           => $request->getPost('contact'),
                'description'       => $request->getPost('description'),
            ]);
        } elseif ($hauling == 1) {
            $quantity = (int) $request->getPost('quantity');
            for ($i = 0; $i < $quantity; $i++) {
                $query = $this->db->table('programming_ocasional')->insert([
                    'fk_id_programming' => $request->getPost('hddidProgramming'),
                    'fk_id_company'     => $request->getPost('company'),
                    'equipment'         => $request->getPost('equipment'),
                    'quantity'          => 1,
                    'unit'              => $request->getPost('unit'),
                    'hours'             => $request->getPost('hour'),
                    'contact'           => $request->getPost('contact'),
                    'description'       => $request->getPost('description'),
                ]);
            }
        }

        return (bool) $query;
    }

    /**
     * Save Rate
     * @review 01/05/2026 - new CI4 version
     */
    public function saveRate()
    {
        $request  = service('request');
        $hddId    = $request->getPost('hddId');
        $formType = $request->getPost('formType');
        $rate     = $request->getPost('rate');
        $quantity = $request->getPost('quantity');
        $hours    = $request->getPost('hours');
        $value    = $rate * $quantity * $hours;
        $checkPDF = $request->getPost('check_pdf') ? 1 : 2;

        $data = [
            'description' => $request->getPost('description'),
            'rate'        => $rate,
            'value'       => $value,
            'view_pdf'    => $checkPDF,
        ];

        switch ($formType) {
            case 'personal':
                $data['fk_id_employee_type'] = $request->getPost('type_personal');
                $data['hours']               = $hours;
                break;
            case 'materials':
                $markup           = $request->getPost('markup');
                $data['markup']   = $markup;
                $data['value']    = $value * ($markup + 100) / 100;
                $data['quantity'] = $quantity;
                $data['unit']     = $request->getPost('unit');
                break;
            case 'equipment':
                $data['hours']    = $hours;
                $data['quantity'] = $quantity;
                break;
            case 'ocasional':
                $markup           = $request->getPost('markup');
                $data['markup']   = $markup;
                $data['value']    = $value * ($markup + 100) / 100;
                $data['hours']    = $hours;
                $data['quantity'] = $quantity;
                $data['unit']     = $request->getPost('unit');
                break;
        }

        $result = $this->db->table('programming_' . $formType)
            ->where('id_programming_' . $formType, $hddId)
            ->update($data);

        return (bool) $result;
    }

    /**
     * Update worker - equipment
     * @since 11/02/2025
     * @review 01/05/2026 - new CI4 version
     */
    public function updateWorkerEquipment()
    {
        $request = service('request');
        $hddId   = $request->getPost('hddidProgrammingWorker');

        $result  = $this->generalModel->get_basic_search([
            'table'  => 'programming_worker',
            'order'  => 'id_programming_worker',
            'column' => 'id_programming_worker',
            'id'     => $hddId,
        ]);
        $machine = $result[0]['fk_id_machine'];

        if (!is_array($machine)) {
            $machine = json_decode($machine, true);
            if (!is_array($machine)) {
                $machine = [];
            }
        }
        $machine[] = $request->getPost('truck');

        $queryResult = $this->db->table('programming_worker')
            ->where('id_programming_worker', $hddId)
            ->update([
                'description'   => $request->getPost('description'),
                'fk_id_machine' => '[' . implode(',', $machine) . ']',
            ]);

        return (bool) $queryResult;
    }
}
