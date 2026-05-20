<?php
namespace App\Modules\Report\Models;

use CodeIgniter\Model;
use App\Models\GeneralModel;

class ReportModel extends Model
{
    protected $protectFields = false;
    protected $generalModel;

    public function __construct()
    {
        parent::__construct();
        $this->generalModel = new GeneralModel();
    }

    /**
     * Payroll
     * @since 24/11/2016
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_payroll($arrData)
    {
        $builder = $this->db->table('task T');
        $builder->select("T.*, CONCAT(first_name, ' ', last_name) name, J.job_description job_start, H.job_description job_finish");
        $builder->join('user U', 'U.id_user = T.fk_id_user', 'INNER');
        $builder->join('param_jobs J', 'J.id_job = T.fk_id_job', 'INNER');
        $builder->join('param_jobs H', 'H.id_job = T.fk_id_job_finish', 'LEFT');

        if (array_key_exists('employee', $arrData) && $arrData['employee'] != 'x') {
            $builder->where('U.id_user', $arrData['employee']);
        }
        if (array_key_exists('from', $arrData)) {
            $builder->where('T.start >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData)) {
            $builder->where('T.start <=', $arrData['to']);
        }
        if (array_key_exists('idPayroll', $arrData)) {
            $builder->where('T.id_task', $arrData['idPayroll']);
        }

        $builder->orderBy('T.start', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Safety list
     * @since 6/01/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_safety($arrData)
    {
        $builder = $this->db->table('safety S');
        $builder->select("S.*, CONCAT(first_name, ' ', last_name) name, J.job_description");
        $builder->join('user U', 'U.id_user = S.fk_id_user', 'INNER');
        $builder->join('param_jobs J', 'J.id_job = S.fk_id_job', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('S.date >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('S.date <=', $arrData['to']);
        }
        if (array_key_exists('jobId', $arrData) && $arrData['jobId'] != '' && $arrData['jobId'] != 'x') {
            $builder->where('S.fk_id_job', $arrData['jobId']);
        }
        if (array_key_exists('idSafety', $arrData) && $arrData['idSafety'] != '' && $arrData['idSafety'] != 'x') {
            $builder->where('S.id_safety', $arrData['idSafety']);
        }

        $builder->orderBy('S.date', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Near miss list
     * @since 20/10/2024
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_near_miss($arrData)
    {
        $builder = $this->db->table('incidence_near_miss W');
        $builder->select('W.*, CONCAT(U.first_name, " " , U.last_name) name, J.id_job, job_description, T.*, CONCAT(X.first_name, " " , X.last_name) supervisor, CONCAT(Y.first_name, " " , Y.last_name) coordinator');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');
        $builder->join('param_incident_type T', 'T.id_incident_type = W.fk_incident_type', 'INNER');
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->join('user X', 'X.id_user = W.manager_user', 'INNER');
        $builder->join('user Y', 'Y.id_user = W.safety_user', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('W.date_issue >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('W.date_issue <=', $arrData['to']);
        }
        if (array_key_exists('jobId', $arrData) && $arrData['jobId'] != '' && $arrData['jobId'] != 'x') {
            $builder->where('W.fk_id_job', $arrData['jobId']);
        }

        $builder->orderBy('id_near_miss', 'desc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Incident list
     * @since 20/10/2024
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_incident($arrData)
    {
        $builder = $this->db->table('incidence_incident W');
        $builder->select('W.*, T.*, J.id_job, job_description, CONCAT(U.first_name, " " , U.last_name) name, CONCAT(X.first_name, " " , X.last_name) supervisor, CONCAT(Y.first_name, " " , Y.last_name) coordinator');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'LEFT');
        $builder->join('param_incident_type T', 'T.id_incident_type = W.fk_incident_type', 'INNER');
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->join('user X', 'X.id_user = W.manager_user', 'INNER');
        $builder->join('user Y', 'Y.id_user = W.safety_user', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('W.date_issue >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('W.date_issue <=', $arrData['to']);
        }
        if (array_key_exists('jobId', $arrData) && $arrData['jobId'] != '' && $arrData['jobId'] != 'x') {
            $builder->where('W.fk_id_job', $arrData['jobId']);
        }

        $builder->orderBy('id_incident', 'desc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * CSEP report (confined space)
     * @since 6/01/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_csep($arrData)
    {
        $arrParam = [
            'idJob' => $arrData['jobId'],
            'from'  => $arrData['from'],
            'to'    => $arrData['to'],
        ];

        $result = $this->generalModel->get_confined_space($arrParam);
        return $result ?: false;
    }

    /**
     * Get safety hazard info
     * @since 4/12/2016
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_safety_hazard($idSafety)
    {
        $builder = $this->db->table('safety_hazards H');
        $builder->select();
        $builder->join('param_hazard PH', 'PH.id_hazard = H.fk_id_hazard', 'INNER');
        $builder->join('param_hazard_activity PA', 'PA.id_hazard_activity = PH.fk_id_hazard_activity', 'INNER');
        $builder->join('param_hazard_priority PP', 'PP.id_priority = PH.fk_id_priority', 'INNER');
        $builder->where('H.fk_id_safety', $idSafety);
        $builder->orderBy('PA.id_hazard_activity, PH.hazard_description', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get safety workers info
     * @since 6/12/2016
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_safety_workers($idSafety)
    {
        $builder = $this->db->table('safety_workers W');
        $builder->select("W.id_safety_worker, W.fk_id_safety, W.signature, CONCAT(first_name, ' ', last_name) name");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->where('W.fk_id_safety', $idSafety);
        $builder->orderBy('U.first_name, U.last_name', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get safety subcontractors info
     * @since 27/2/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_safety_subcontractors($idSafety)
    {
        $builder = $this->db->table('safety_workers_subcontractor W');
        $builder->select("W.*, C.company_name");
        $builder->join('param_company C', 'C.id_company = W.fk_id_company', 'INNER');
        $builder->where('W.fk_id_safety', $idSafety);
        $builder->orderBy('C.company_name', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get safety COVID info
     * @since 15/4/2021
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_safety_covid($idSafety)
    {
        $builder = $this->db->table('safety_covid W');
        $builder->select();
        $builder->where('W.fk_id_safety', $idSafety);
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Hauling list
     * @since 6/01/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_hauling($arrData)
    {
        $builder = $this->db->table('hauling H');
        $builder->select("H.*, CONCAT(first_name, ' ', last_name) name, C.company_name, V.unit_number, T.truck_type, J.job_description site_from, Z.job_description site_to, M.material, P.payment");
        $builder->join('user U', 'U.id_user = H.fk_id_user', 'INNER');
        $builder->join('param_company C', 'C.id_company = H.fk_id_company', 'INNER');
        $builder->join('param_vehicle V', 'V.id_vehicle = H.fk_id_truck', 'LEFT');
        $builder->join('param_truck_type T', 'T.id_truck_type = H.fk_id_truck_type', 'INNER');
        $builder->join('param_jobs J', 'J.id_job = H.fk_id_site_from', 'INNER');
        $builder->join('param_jobs Z', 'Z.id_job = H.fk_id_site_to', 'INNER');
        $builder->join('param_material_type M', 'M.id_material = H.fk_id_material', 'INNER');
        $builder->join('param_payment P', 'P.id_payment = H.fk_id_payment', 'INNER');

        if (array_key_exists('employee', $arrData) && $arrData['employee'] != 'x') {
            $builder->where('U.id_user', $arrData['employee']);
        }
        if (array_key_exists('vehicleId', $arrData) && $arrData['vehicleId'] != 'x') {
            $builder->where('V.id_vehicle', $arrData['vehicleId']);
        }
        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('H.date_issue >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('H.date_issue <=', $arrData['to']);
        }
        if (array_key_exists('company', $arrData) && $arrData['company'] != 'x') {
            $builder->where('H.fk_id_company', $arrData['company']);
        }
        if (array_key_exists('material', $arrData) && $arrData['material'] != 'x') {
            $builder->where('H.fk_id_material', $arrData['material']);
        }
        if (array_key_exists('idHauling', $arrData) && $arrData['idHauling'] != 'x') {
            $builder->where('H.id_hauling', $arrData['idHauling']);
        }
        if (array_key_exists('jobId', $arrData) && $arrData['jobId'] != 'x') {
            $builder->where('H.fk_id_site_from', $arrData['jobId']);
        }

        $builder->orderBy('H.id_hauling', 'desc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Vehicle by inspection type
     * @since 8/03/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_vehicle_by_type($arrData)
    {
        $builder = $this->db->table('param_vehicle V');
        $builder->select();
        $builder->join('param_vehicle_type_2 T', 'T.id_type_2 = V.type_level_2', 'INNER');
        $builder->join('param_company C', 'C.id_company = V.fk_id_company', 'INNER');
        $builder->where('V.state', 1);

        if (array_key_exists('tipo', $arrData) && $arrData['tipo'] == 'daily') {
            $builder->where('T.inspection_type IN (1,3)', null, false);
        } elseif (array_key_exists('tipo', $arrData) && $arrData['tipo'] == 'heavy') {
            $builder->where('T.inspection_type', 2);
        } elseif (array_key_exists('tipo', $arrData) && $arrData['tipo'] == 'trailer') {
            $builder->where('V.type_level_2', 5);
        } elseif (array_key_exists('tipo', $arrData) && $arrData['tipo'] == 'special') {
            $builder->where('T.inspection_type', 4);
        }

        if (array_key_exists('company_type', $arrData)) {
            $builder->where('C.company_type', 1);
        }

        $builder->orderBy('T.inspection_type, V.unit_number', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Daily inspection list
     * @since 6/01/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_daily_inspection($arrData)
    {
        $builder = $this->db->table('inspection_daily I');
        $builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*, CONCAT(T.unit_number, ' - ', T.description) trailer, TY.type_2, TY.inspection_type");
        $builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
        $builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
        $builder->join('param_vehicle T', 'T.id_vehicle = I.fk_id_trailer', 'LEFT');
        $builder->join('param_vehicle_type_2 TY', 'TY.id_type_2 = V.type_level_2', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('I.date_issue >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('I.date_issue <=', $arrData['to']);
        }
        if (array_key_exists('employee', $arrData) && $arrData['employee'] != 'x') {
            $builder->where('I.fk_id_user', $arrData['employee']);
        }
        if (array_key_exists('idInspection', $arrData) && $arrData['idInspection'] != 'x') {
            $builder->where('I.id_inspection_daily', $arrData['idInspection']);
        }
        if (array_key_exists('vehicleId', $arrData) && $arrData['vehicleId'] != 'x') {
            $builder->where('I.fk_id_vehicle', $arrData['vehicleId']);
        }
        if (array_key_exists('trailerId', $arrData) && $arrData['trailerId'] != 'x') {
            $builder->where('I.fk_id_trailer', $arrData['trailerId']);
        }

        $builder->orderBy('I.date_issue', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Heavy inspection list
     * @since 7/01/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_heavy_inspection($arrData)
    {
        $builder = $this->db->table('inspection_heavy I');
        $builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*, TY.*");
        $builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
        $builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
        $builder->join('param_vehicle_type_2 TY', 'TY.id_type_2 = V.type_level_2', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('I.date_issue >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('I.date_issue <=', $arrData['to']);
        }
        if (array_key_exists('employee', $arrData) && $arrData['employee'] != 'x') {
            $builder->where('I.fk_id_user', $arrData['employee']);
        }
        if (array_key_exists('idInspection', $arrData) && $arrData['idInspection'] != 'x') {
            $builder->where('I.id_inspection_heavy', $arrData['idInspection']);
        }
        if (array_key_exists('vehicleId', $arrData) && $arrData['vehicleId'] != 'x') {
            $builder->where('I.fk_id_vehicle', $arrData['vehicleId']);
        }

        $builder->orderBy('I.date_issue', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Sweeper inspection list
     * @since 23/04/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_sweeper_inspection($arrData)
    {
        $builder = $this->db->table('inspection_sweeper I');
        $builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*, TY.*");
        $builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
        $builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
        $builder->join('param_vehicle_type_2 TY', 'TY.id_type_2 = V.type_level_2', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('I.date_issue >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('I.date_issue <=', $arrData['to']);
        }
        if (array_key_exists('employee', $arrData) && $arrData['employee'] != 'x') {
            $builder->where('I.fk_id_user', $arrData['employee']);
        }
        if (array_key_exists('idInspection', $arrData) && $arrData['idInspection'] != 'x') {
            $builder->where('I.id_inspection_sweeper', $arrData['idInspection']);
        }
        if (array_key_exists('vehicleId', $arrData) && $arrData['vehicleId'] != 'x') {
            $builder->where('I.fk_id_vehicle', $arrData['vehicleId']);
        }

        $builder->orderBy('I.date_issue', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Hydrovac inspection list
     * @since 23/04/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_hydrovac_inspection($arrData)
    {
        $builder = $this->db->table('inspection_hydrovac I');
        $builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*, TY.*");
        $builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
        $builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
        $builder->join('param_vehicle_type_2 TY', 'TY.id_type_2 = V.type_level_2', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('I.date_issue >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('I.date_issue <=', $arrData['to']);
        }
        if (array_key_exists('employee', $arrData) && $arrData['employee'] != 'x') {
            $builder->where('I.fk_id_user', $arrData['employee']);
        }
        if (array_key_exists('idInspection', $arrData) && $arrData['idInspection'] != 'x') {
            $builder->where('I.id_inspection_hydrovac', $arrData['idInspection']);
        }
        if (array_key_exists('vehicleId', $arrData) && $arrData['vehicleId'] != 'x') {
            $builder->where('I.fk_id_vehicle', $arrData['vehicleId']);
        }

        $builder->orderBy('I.date_issue', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Generator inspection list
     * @since 23/04/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_generator_inspection($arrData)
    {
        $builder = $this->db->table('inspection_generator I');
        $builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*, TY.*");
        $builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
        $builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
        $builder->join('param_vehicle_type_2 TY', 'TY.id_type_2 = V.type_level_2', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('I.date_issue >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('I.date_issue <=', $arrData['to']);
        }
        if (array_key_exists('employee', $arrData) && $arrData['employee'] != 'x') {
            $builder->where('I.fk_id_user', $arrData['employee']);
        }
        if (array_key_exists('idInspection', $arrData) && $arrData['idInspection'] != 'x') {
            $builder->where('I.id_inspection_generator', $arrData['idInspection']);
        }
        if (array_key_exists('vehicleId', $arrData) && $arrData['vehicleId'] != 'x') {
            $builder->where('I.fk_id_vehicle', $arrData['vehicleId']);
        }

        $builder->orderBy('I.date_issue', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Water truck inspection list
     * @since 29/06/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_water_truck_inspection($arrData)
    {
        $builder = $this->db->table('inspection_watertruck I');
        $builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*, TY.*");
        $builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
        $builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
        $builder->join('param_vehicle_type_2 TY', 'TY.id_type_2 = V.type_level_2', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('I.date_issue >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('I.date_issue <=', $arrData['to']);
        }
        if (array_key_exists('employee', $arrData) && $arrData['employee'] != 'x') {
            $builder->where('I.fk_id_user', $arrData['employee']);
        }
        if (array_key_exists('idInspection', $arrData) && $arrData['idInspection'] != 'x') {
            $builder->where('I.id_inspection_watertruck', $arrData['idInspection']);
        }
        if (array_key_exists('vehicleId', $arrData) && $arrData['vehicleId'] != 'x') {
            $builder->where('I.fk_id_vehicle', $arrData['vehicleId']);
        }

        $builder->orderBy('I.date_issue', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Work order list
     * @since 26/01/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_workorder($arrData)
    {
        $builder = $this->db->table('workorder W');
        $builder->select("W.*, CONCAT(first_name, ' ', last_name) name, J.job_description");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');

        if (array_key_exists('from', $arrData)) {
            $builder->where('W.date >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData)) {
            $builder->where('W.date <=', $arrData['to']);
        }
        if (array_key_exists('jobId', $arrData) && $arrData['jobId'] != '' && $arrData['jobId'] != 'x') {
            $builder->where('W.fk_id_job', $arrData['jobId']);
        }
        if (array_key_exists('idWorkOrder', $arrData)) {
            $builder->where('W.id_workorder', $arrData['idWorkOrder']);
        }

        $builder->orderBy('W.id_workorder', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get workorder personal info
     * @since 13/1/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_workorder_personal($idWorkorder)
    {
        $builder = $this->db->table('workorder_personal W');
        $builder->select("W.*, CONCAT(first_name, ' ', last_name) name, T.employee_type as type");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->join('param_employee_type T', 'T.id_employee_type = W.fk_id_employee_type', 'INNER');
        $builder->where('W.fk_id_workorder', $idWorkorder);
        $builder->orderBy('U.first_name, U.last_name', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get workorder materials info
     * @since 13/1/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_workorder_materials($idWorkorder)
    {
        $builder = $this->db->table('workorder_materials W');
        $builder->select();
        $builder->join('param_material_type M', 'M.id_material = W.fk_id_material', 'INNER');
        $builder->where('W.fk_id_workorder', $idWorkorder);
        $builder->orderBy('M.material', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get workorder equipment info
     * @since 25/1/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_workorder_equipment($idWorkorder)
    {
        $builder = $this->db->table('workorder_equipment W');
        $builder->select('W.*, V.unit_number, V.description v_description, M.miscellaneous, T.type_2');
        $builder->join('param_vehicle V', 'V.id_vehicle = W.fk_id_vehicle', 'INNER');
        $builder->join('param_company C', 'C.id_company = V.fk_id_company', 'INNER');
        $builder->join('param_miscellaneous M', 'M.id_miscellaneous = W.fk_id_vehicle', 'LEFT');
        $builder->join('param_vehicle_type_2 T', 'T.id_type_2 = W.fk_id_type_2', 'INNER');
        $builder->where('W.fk_id_workorder', $idWorkorder);
        $builder->orderBy('C.company_name', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get workorder ocasional info
     * @since 27/2/2017
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_workorder_ocasional($idWorkorder)
    {
        $builder = $this->db->table('workorder_ocasional O');
        $builder->select('O.*, C.company_name');
        $builder->join('param_company C', 'C.id_company = O.fk_id_company', 'INNER');
        $builder->where('O.fk_id_workorder', $idWorkorder);
        $builder->orderBy('C.company_name', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Get workorder receipt info
     * @since 4/1/2021
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_workorder_receipt($arrData)
    {
        $builder = $this->db->table('workorder_receipt O');
        $builder->select();

        if (array_key_exists('idWorkOrder', $arrData)) {
            $builder->where('O.fk_id_workorder', $arrData['idWorkOrder']);
        }
        if (array_key_exists('view_pdf', $arrData)) {
            $builder->where('O.view_pdf', 1);
        }

        $builder->orderBy('O.place', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Trucks list
     * @since 7/1/2020
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_trucks()
    {
        $sql = "SELECT id_vehicle, CONCAT(unit_number,' -----> ', description) as unit_description
                FROM param_vehicle
                WHERE type_level_2 = 4 AND state = 1
                ORDER BY unit_number";

        $query = $this->db->query($sql);

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Maintenance list
     * @since 25/05/2022
     * @author BMOTTAG
     * @review 01/05/2026 - new CI4 version
     */
    public function get_maintenance($arrData)
    {
        $builder = $this->db->table('maintenance M');
        $builder->select("M.date_maintenance, M.maintenance_description, M.done_by, M.next_hours_maintenance, M.next_date_maintenance, M.maintenance_state, M.fk_id_vehicle, S.stock_description, T.maintenance_type, V.make, V.description");
        $builder->join('maintenance_type T', 'T.id_maintenance_type = M.fk_id_maintenance_type', 'INNER');
        $builder->join('stock S', 'S.id_stock = M.fk_id_stock', 'LEFT');
        $builder->join('param_vehicle V', 'V.id_vehicle = M.fk_id_vehicle', 'INNER');

        if (array_key_exists('from', $arrData) && $arrData['from'] != 'x') {
            $builder->where('M.date_maintenance >=', $arrData['from']);
        }
        if (array_key_exists('to', $arrData) && $arrData['to'] != 'x') {
            $builder->where('M.date_maintenance <=', $arrData['to']);
        }
        if (array_key_exists('vehicleId', $arrData) && $arrData['vehicleId'] != 'x') {
            $builder->where('M.fk_id_vehicle', $arrData['vehicleId']);
        }
        if (array_key_exists('maitenanceType', $arrData)) {
            $builder->where('M.fk_id_maintenance_type', $arrData['maitenanceType']);
        }

        $builder->orderBy('M.id_maintenance', 'asc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }
}
