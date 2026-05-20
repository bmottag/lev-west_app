<?php
namespace App\Modules\Jobs\Models;

use CodeIgniter\Model;


class JobsModel extends Model
{

	protected $protectFields = false;

	/**
	 * Get activity list
	 * @since 23/2/2017
	 */
	public function get_activity_list()
	{
		$builder = $this->db->table('param_hazard H');

		$builder->select('A.id_hazard_activity, A.hazard_activity');

		$builder->join(
			'param_hazard_activity A',
			'A.id_hazard_activity = H.fk_id_hazard_activity',
			'INNER'
		);

		$builder->groupBy('A.id_hazard_activity');
		$builder->orderBy('A.hazard_activity', 'asc');

		return $builder->get()->getResultArray();
	}

	public function get_hazards_grouped($idJob)
	{
		$builder = $this->db->table('param_hazard H');

		$builder->select('
			H.id_hazard,
			H.hazard_description,
			H.solution,
			A.id_hazard_activity,
			A.hazard_activity,
			JH.id_job_hazard
		');

		$builder->join('param_hazard_activity A', 'A.id_hazard_activity = H.fk_id_hazard_activity');
		$builder->join('job_hazards JH', 'JH.fk_id_hazard = H.id_hazard AND JH.fk_id_job = '.$idJob, 'left');

		$query = $builder->get()->getResultArray();

		$result = [];

		foreach ($query as $row) {
			$result[$row['id_hazard_activity']][] = $row;
		}

		return $result;
	}

	/**
	 * Add JOB HAZARDS
	 * @since 27/11/2016
	 */
	public function add_safety_hazard(array $post = []): bool
	{
		$idJob =  $post['hddId'];
		$hazards =  $post['hazards'];
		// 🔥 DELETE hazards
		$this->db->table('job_hazards')
				->where('fk_id_job', $idJob)
				->delete();

		// 🔥 INSERT hazards
		if (!empty($hazards)) {
			$dataBatch = [];
			foreach ($hazards as $idHazard) {
				$dataBatch[] = [
					'fk_id_job' => $idJob,
					'fk_id_hazard' => (int)$idHazard
				];
			}

			return (bool) $this->db->table('job_hazards')
								->insertBatch($dataBatch);
		}

		return true;
	}

	/**
	 * Add JOB HAZARDS LOG
	 * @since 27/11/2016
	 */
	public function add_hazard_log(array $post): bool
	{
		$data = [
			'date_log' => date("Y-m-d G:i:s"),
			'fk_id_job' => $post['hddId'] ?? null,
			'fk_id_user' => session()->get("id"),
			'observation' => $post['observation'] ?? null,
		];

		$builder = $this->db->table('job_hazards_log');
		return $builder->insert($data);
	}

	/**
	 * Lista hazards logs
	 * @since 21/8/2018
	 */
	public function get_hazards_logs($arrDatos)
	{
		$builder = $this->db->table('job_hazards_log A');
		$builder->select('A.*, J.*, CONCAT(U.first_name, " " , U.last_name) name');
		$builder->join('param_jobs J', 'J.id_job = A.fk_id_job', 'INNER');
		$builder->join('user U', 'U.id_user = A.fk_id_user', 'INNER');

		if (isset($arrDatos["idJob"])) {
			$builder->where('fk_id_job', $arrDatos["idJob"]);
		}

		if (isset($arrDatos["idJobHazardLog"])) {
			$builder->where('id_job_hazard_log', $arrDatos["idJobHazardLog"]);
		}

		$builder->orderBy('date_log', 'DESC');
		return $builder->get()->getResultArray();
	}

	/**
	 * Get job hazard info
	 * @since 27/11/2017
	 */
	public function get_job_hazards_v2($idJob)
	{
		$builder = $this->db->table('job_hazards H');
		$builder->select();
		$builder->join('param_hazard PH', 'PH.id_hazard = H.fk_id_hazard', 'INNER');
		$builder->join('param_hazard_activity PA', 'PA.id_hazard_activity = PH.fk_id_hazard_activity', 'INNER');
		$builder->join('param_hazard_priority PP', 'PP.id_priority = PH.fk_id_priority', 'INNER');
		$builder->where('H.fk_id_job', $idJob);
		$builder->orderBy('PA.hazard_activity, PH.hazard_description', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Add tool box
	 * @since 24/10/2017
	 */
	public function add_TOOLBOX(array $post)
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'new_safety' => $post['newSafety'] ?? null,
			'activities' => $post['activities'] ?? null,
			'suggestions' => $post['suggestions'] ?? null,
			'corrective_actions' => $post['correctiveActions'] ?? null
		];

		//solo usuarios SUPER_ADMIN pueden ingresar la fecha de la inspeccion
		$userRol = session()->get('rol');
		$dateIssue = $post['date'] ?? null;

		$builder = $this->db->table('tool_box');

		if (empty($id)) {
			$data['fk_id_user'] = session()->get('id');
			$data['fk_id_job'] = $post['hddIdJob'];

			//solo usuarios SUPER_ADMIN pueden ingresar la fecha de la inspeccion
			$data['date_tool_box'] = date("Y-m-d G:i:s");
			if ($userRol == 99 && $dateIssue != "") {
				$data['date_tool_box'] = $dateIssue;
			}
			if ($builder->insert($data)) {
				return $this->db->insertID();
			}
			return false;
		} else {
			if ($userRol == 99 && $dateIssue != "") {
				$data['date_tool_box'] = $dateIssue;
			}
			$update = $builder->where('id_tool_box', $id)
							->update($data);

			return $update ? $id : false;
		}
	}

	/**
	 * Get tool box new hazards info
	 * @since 25/10/2017
	 */
	public function get_new_hazards($idToolBox)
	{
		$builder = $this->db->table('tool_box_new_hazard W');
		$builder->select();
		$builder->where('W.fk_id_tool_box', $idToolBox);
		$builder->orderBy('W.hazard', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Get tool box workers info
	 * @since 2/11/2017
	 */
	public function get_tool_box_workers($idToolBox)
	{
		$builder = $this->db->table('tool_box_workers W');
		$builder->select("W.id_tool_box_worker, W.fk_id_tool_box, W.signature, CONCAT(first_name, ' ', last_name) name");
		$builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
		$builder->where('W.fk_id_tool_box', $idToolBox);
		$builder->orderBy('U.first_name, U.last_name', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Get tool box subcontractor workers info
	 * @since 26/2/2018
	 */
	public function get_tool_box_subcontractors_workers($idToolBox)
	{
		$builder = $this->db->table('tool_box_workers_subcontractor W');
		$builder->select();
		$builder->join('param_company C', 'C.id_company = W.fk_id_company', 'INNER');
		$builder->where('W.fk_id_tool_box', $idToolBox);
		$builder->orderBy('C.company_name, W.worker_name', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Add new hazard
	 * @since 25/10/2017
	 */
	public function saveNewHazard($post)
	{
		$data = [
			'fk_id_tool_box' => $post['hddidToolBox'] ?? null,
			'hazard' => $post['hazard'] ?? null,
			'hazard_type' => $post['hazardType'] ?? null,
			'actions' => $post['actions'] ?? null
		];

		$builder = $this->db->table('tool_box_new_hazard');
		return $builder->insert($data);
	}

	/**
	 * Update NEW HAZARD
	 * @since 25/10/2017
	 */
	public function updateNewHazard($post)
	{
		$id = $post['hddIdNewHazard'] ?? null;

		$data = [
			'hazard' => $post['hazard'] ?? null,
			'hazard_type' => $post['hazardType'] ?? null,
			'actions' => $post['actions'] ?? null,
		];

		$builder = $this->db->table('tool_box_new_hazard');

		return $builder->where('id_new_hazard', $id)
						->update($data);
	}

	/**
	 * @author BMOTTAG
	 * @since 2/11/2017
	 * Consulta de empleados para un tool box especifico
	 */
	public function get_selected_workers_toolbox($idToolBox)
	{
		return $this->db->table('tool_box_workers')
			->select('fk_id_user')
			->where('fk_id_tool_box', $idToolBox)
			->get()
			->getResultArray();
	}

	/**
	 * Add TOOL BOX WORKER
	 * @since 2/11/2017
	 */
	public function add_tool_box_worker(array $post = []): bool
	{
		$idToolBox =  $post['hddIdToolBox'];
		$workers =  $post['workers'];

		// 🔥 INSERT hazards
		if (!empty($workers)) {
			$dataBatch = [];
			foreach ($workers as $idWorker) {
				$dataBatch[] = [
					'fk_id_tool_box' => (int)$idToolBox,
					'fk_id_user' => (int)$idWorker
				];
			}

			return (bool) $this->db->table('tool_box_workers')
								->insertBatch($dataBatch);
		}

		return true;
	}

	/**
	 * Save one worker TOOL BOX
	 * @since 8/5/2018
	 */
	public function toolBoxSaveOneWorker(array $post = []): bool
	{
		$data = [
			'fk_id_tool_box' => $post['hddIdToolBox'] ?? null,
			'fk_id_user' => $post['worker'] ?? null
		];

		$builder = $this->db->table('tool_box_workers');
		return $builder->insert($data);
	}

	/**
	 * Save subcontractor worker
	 * @since 26/2/2018
	 */
	public function saveSubcontractorWorker(array $post = []): bool
	{
		$data = [
			'fk_id_tool_box' => $post['hddIdToolBox'] ?? null,
			'fk_id_company' => $post['company'] ?? null,
			'worker_name' => $post['workerName'] ?? null
		];

		$builder = $this->db->table('tool_box_workers_subcontractor');
		return $builder->insert($data);
	}

	/**
	 * ERP
	 * @since 20/11/2017
	 */
	public function get_erp($arrDatos)
	{
		$builder = $this->db->table('erp E');
		$builder->select('E.*, U.movil phone_res, X.movil phone_co, CONCAT(U.first_name, " " , U.last_name) responsible, CONCAT(X.first_name, " " , X.last_name) coordinator, J.id_job, J.job_description,
Y.movil phone_emer_1, CONCAT(Y.first_name, " " , Y.last_name) emer_1, Z.movil phone_emer_2, CONCAT(Z.first_name, " " , Z.last_name) emer_2');
		$builder->join('param_jobs J', 'J.id_job = E.fk_id_job', 'INNER');
		$builder->join('user U', 'U.id_user = E.responsible_user', 'INNER');
		$builder->join('user X', 'X.id_user = E.coordinator_user', 'INNER');
		$builder->join('user Y', 'Y.id_user = E.emergency_user_1', 'INNER');
		$builder->join('user Z', 'Z.id_user = E.emergency_user_2', 'INNER');
		if (isset($arrDatos["idJob"])) {
			$builder->where('fk_id_job', $arrDatos["idJob"]);
		}
		if (isset($arrDatos["idERP"])) {
			$builder->where('id_erp', $arrDatos["idERP"]);
		}

		return $builder->get()->getResultArray();
	}

	/**
	 * Get ERP training workers info
	 * @since 23/11/2017
	 */
	public function get_erp_training_workers($idJob)
	{
		$builder = $this->db->table('erp_training_workers W');
		$builder->select("W.*, CONCAT(first_name, ' ', last_name) name, U.*");
		$builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
		$builder->where('W.fk_id_job', $idJob);
		$builder->orderBy('U.first_name, U.last_name', 'asc');

		return $builder->get()->getResultArray();
	}

	/**
	 * Add ERP
	 * @since 20/11/2017
	 */
	public function add_erp(array $post = []): bool
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'address' => $post['address'] ?? null,
			'responsible_user' => $post['responsible'] ?? null,
			'coordinator_user' => $post['coordinator'] ?? null,
			'fire_department' => $post['fire_department'] ?? null,
			'paramedics' => $post['paramedics'] ?? null,
			'ambulance' => $post['ambulance'] ?? null,
			'police' => $post['police'] ?? null,
			'federal_protective' => $post['federal_protective'] ?? null,
			'security' => $post['security'] ?? null,
			'manager' => $post['manager'] ?? null,
			'electric' => $post['electric'] ?? null,
			'water' => $post['water'] ?? null,
			'gas' => $post['gas'] ?? null,
			'emergency_user_1' => $post['contact1'] ?? null,
			'emergency_user_2' => $post['contact2'] ?? null,
			'voice' => $post['voice'] ?? null,
			'radio' => $post['radio'] ?? null,
			'phone' => $post['phone'] ?? null,
			'other' => $post['other'] ?? null,
			'specify' => $post['specify'] ?? null,
			'location' => $post['location'] ?? null,
			'location2' => $post['location2'] ?? null,
			'location3' => $post['location3'] ?? null,
			'directions' => $post['directions'] ?? null
		];

		$builder = $this->db->table('erp');

		if (empty($id)) {
			$data['fk_id_user'] = session()->get('id');
			$data['fk_id_job'] = $post['hddIdJob'];
			$data['date_erp'] = date("Y-m-d G:i:s");
			return $builder->insert($data);
		} else {
			return $builder->where('id_erp', $id)
						->update($data);
		}
	}

	/**
	 * @author BMOTTAG
	 * @since 23/11/2017
	 * Consulta de empleados para un job especifico
	 */
	public function get_selected_workers_erp($idJob)
	{
		return $this->db->table('erp_training_workers')
			->select('fk_id_user')
			->where('fk_id_job', $idJob)
			->get()
			->getResultArray();
	}

	/**
	 * Add ERP TRAINING WORKER
	 * @since 23/11/2017
	 */
	public function add_training_worker(array $post = []): bool
	{
		$idJob =  $post['hddIdJob'];
		$workers =  $post['workers'];

		// 🔥 INSERT hazards
		if (!empty($workers)) {
			$dataBatch = [];
			foreach ($workers as $idWorker) {
				$dataBatch[] = [
					'fk_id_job' => (int)$idJob,
					'fk_id_user' => (int)$idWorker
				];
			}

			return (bool) $this->db->table('erp_training_workers')
								->insertBatch($dataBatch);
		}

		return true;
	}

	/**
	 * Save one worker
	 * @since 23/11/2017
	 */
	public function saveOneWorker(array $post = []): bool
	{
		$data = [
			'fk_id_job' => $post['hddId'] ?? null,
			'fk_id_user' => $post['worker'] ?? null
		];

		$builder = $this->db->table('erp_training_workers');
		return $builder->insert($data);
	}

	/**
	 * Update Rate
	 * @since 11/4/2021
	 */
	public function updateERPWorker($post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'title' => $post['title'] ?? null,
			'responsability' => $post['responsability'] ?? null
		];

		$builder = $this->db->table('erp_training_workers');

		return $builder->where('id_erp_training_worker', $id)
						->update($data);
	}

	/**
	 * JSO
	 * @since 4/1/2018
	 */
	public function get_jso($arrDatos)
	{
		$builder = $this->db->table('job_jso S');
		$builder->select('S.*, CONCAT(U.first_name, " " , U.last_name) supervisor, CONCAT(X.first_name, " " , X.last_name) manager, J.id_job, J.job_description');
		$builder->join('param_jobs J', 'J.id_job = S.fk_id_job', 'INNER');
		$builder->join('user U', 'U.id_user = S.fk_id_user_supervisor', 'INNER');
		$builder->join('user X', 'X.id_user = S.fk_id_user_manager', 'INNER');

		if (isset($arrDatos["idJob"])) {
			$builder->where('fk_id_job', $arrDatos["idJob"]);
		}
		if (isset($arrDatos["idJobJso"])) {
			$builder->where('id_job_jso', $arrDatos["idJobJso"]);
		}

		return $builder->get()->getResultArray();
	}

	/**
	 * JSO
	 * @since 5/1/2018
	 */
	public function get_jso_workers($arrDatos)
	{
		$builder = $this->db->table('job_jso_workers');
		$builder->select();

		if (isset($arrDatos["idJobJso"])) {
			$builder->where('fk_id_job_jso', $arrDatos["idJobJso"]);
		}
		if (isset($arrDatos["idJobJsoWorker"])) {
			$builder->where('id_job_jso_worker', $arrDatos["idJobJsoWorker"]);
		}

		return $builder->get()->getResultArray();
	}

	/**
	 * Add JSO
	 * @since 4/1/2018
	 */
	public function addJSO(array $post)
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'fk_id_user_manager' => $post['manager'] ?? null,
			'fk_id_user_supervisor' => $post['supervisor'] ?? null,
			'potential_hazards' => $post['potential_hazards'] ?? null,
			'health_safety' => $post['health_safety'] ?? null,
			'rights_responsibilities' => $post['rights_responsibilities'] ?? null,
			'company_safety_rules' => $post['company_safety_rules'] ?? null,
			'hazard_awareness' => $post['hazard_awareness'] ?? null,
			'reporting_procedures' => $post['reporting_procedures'] ?? null,
			'personal_equipment' => $post['personal_equipment'] ?? null,
			'drug_alcohol' => $post['drug_alcohol'] ?? null,
			'violence_workplace' => $post['violence_workplace'] ?? null,
			'whmis' => $post['whmis'] ?? null,
			'equipment_operation' => $post['equipment_operation'] ?? null,
			'workplace_inspections' => $post['workplace_inspections'] ?? null,
			'accident_forms' => $post['accident_forms'] ?? null,
			'first_aid' => $post['first_aid'] ?? null,
			'erp' => $post['erp'] ?? null,
			'flha' => $post['flha'] ?? null,
			'near_miss' => $post['near_miss'] ?? null,
			'erp_subcontractor' => $post['erp_subcontractor'] ?? null,
			'accident_incident' => $post['accident_incident'] ?? null,
			'preventive_maintenance' => $post['preventive_maintenance'] ?? null,
			'msds' => $post['msds'] ?? null,
			'notification_hazards' => $post['notification_hazards'] ?? null,
			'first_aid_subcontractor' => $post['first_aid_subcontractor'] ?? null,
			'smoking_drug' => $post['smoking_drug'] ?? null,
			'flha_subcontractor' => $post['flha_subcontractor'] ?? null,
			'environmental_management' => $post['environmental_management'] ?? null,
			'working_alone' => $post['working_alone'] ?? null,
			'muster_point' => $post['muster_point'] ?? null,
			'fire_extinguishers' => $post['fire_extinguishers'] ?? null,
			'personal_equipment_subcontractor' => $post['personal_equipment_subcontractor'] ?? null,
			'equipment_inspections' => $post['equipment_inspections'] ?? null,
			'housekeeping' => $post['housekeeping'] ?? null,
			'hazard_identification' => $post['hazard_identification'] ?? null,
			'site_safe_work' => $post['site_safe_work'] ?? null,
			'site_safe_job' => $post['site_safe_job'] ?? null,
			'reporting' => $post['reporting'] ?? null,
			'attendance' => $post['attendance'] ?? null,
			'site_rules' => $post['site_rules'] ?? null,
			'confined_space' => $post['confined_space'] ?? null,
			'fall_protection' => $post['fall_protection'] ?? null,
			'tdg' => $post['tdg'] ?? null,
			'first_aid_site' => $post['first_aid_site'] ?? null,
			'whmis_site' => $post['whmis_site'] ?? null,
			'traffic_control' => $post['traffic_control'] ?? null,
			'backhoe' => $post['backhoe'] ?? null,
			'excavator' => $post['excavator'] ?? null,
			'forklift' => $post['forklift'] ?? null,
			'cranes' => $post['cranes'] ?? null,
			'trailer_towing' => $post['trailer_towing'] ?? null,
			'power_tools' => $post['power_tools'] ?? null,
			'dump_truck' => $post['dump_truck'] ?? null,
			'hoists' => $post['hoists'] ?? null,
			'loader' => $post['loader'] ?? null,
			'light_vehicles' => $post['light_vehicles'] ?? null,
			'conveyors' => $post['conveyors'] ?? null,
			'compressor' => $post['compressor'] ?? null,
			'environmental_reporting' => $post['environmental_reporting'] ?? null,
			'low_boys' => $post['low_boys'] ?? null,
			'scaffolds' => $post['scaffolds'] ?? null,
			'light_towers' => $post['light_towers'] ?? null,
			'generators' => $post['generators'] ?? null,
			'hydrovacs' => $post['hydrovacs'] ?? null,
			'hydroseeds' => $post['hydroseeds'] ?? null,
			'ground_disturbance' => $post['ground_disturbance'] ?? null,
			'load_securement' => $post['load_securement'] ?? null,
			'traffic_accommodation' => $post['traffic_accommodation'] ?? null,
			'safety_advisor' => $post['safety_advisor'] ?? null,
			'wib' => $post['wib'] ?? null,
			'safe_trenching' => $post['safe_trenching'] ?? null,
			'street_sweeper' => $post['street_sweeper'] ?? null,
			'skid_steer' => $post['skid_steer'] ?? null,
			'dozers' => $post['dozers'] ?? null,
		];

		$builder = $this->db->table('job_jso');

		if (empty($id)) {
			$data['fk_id_user'] = session()->get('id');
			$data['fk_id_job'] = $post['hddIdJob'];
			$data['date_issue_jso'] = date("Y-m-d G:i:s");
			if ($builder->insert($data)) {
				return $this->db->insertID();
			}
			return false;
		} else {
			$update = $builder->where('id_job_jso', $id)
							->update($data);

			return $update ? $id : false;
		}
	}

	/**
	 * Add jso worker
	 * @since 5/1/2018
	 */
	public function saveJSOWorker(array $post): bool
	{
		$id = $post['hddidJobJsoWorker'] ?? null;

		$data = [
			'fk_id_job_jso' => $post['hddidJobJso'] ?? null,
			'name' => $post['name'] ?? null,
			'position' => $post['position'] ?? null,
			'emergency_contact' => $post['emergency_contact'] ?? null,
			'driver_license_required' => $post['license'] ?? null,
			'license_number' => $post['license_number'] ?? null,
			'city' => $post['city'] ?? null,
			'works_for' => $post['worksfor'] ?? null,
			'works_phone_number' => $post['phone_number'] ?? null
		];

		$builder = $this->db->table('job_jso_workers');

		if (empty($id)) {
			$data['date_oriented'] = date('Y-m-d');
			return $builder->insert($data);
		} else {
			return $builder->where('id_job_jso_worker', $id)
						->update($data);
		}
	}

	/**
	 * Get bitacora
	 * @since 03/01/2024
	 */
	public function get_bitacora_job($id_job)
	{
		$builder = $this->db->table('bitacora');
		$builder->select("*");
		$builder->join('user', 'bitacora.fk_id_user = user.id_user', 'left');
		$builder->where('fk_id_job =', $id_job);

		$builder->orderBy('id_bitacora', 'DESC');
		return $builder->get()->getResultArray();
	}

	/**
	 * Add new bitacora
	 * @since 03/01/2024
	 */
	public function saveBitacora(array $post): bool
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'fk_id_job' => $post['hddId'] ?? null,
			'fk_id_user' => session()->get('id'),
			'date_bitacora' => date("Y-m-d H:i:s"),
			'notification' => $post['notification'] ?? null
		];

		$builder = $this->db->table('bitacora');

		return $builder->insert($data);
	}

	/**
	 * Get job locates info
	 * @since 29/11/2017
	 */
	public function get_job_locates($idJob)
	{
		$builder = $this->db->table('job_locates L');
		$builder->select();
		$builder->where('L.fk_id_job', $idJob);
		$builder->orderBy('L.id_job_locates', 'asc');
		return $builder->get()->getResultArray();
	}

	/**
	 * Add locates
	 * @since 29/11/2017
	 */
	public function add_locates($post, $path): bool
	{
		$data = [
			'fk_id_job' => $post['hddIdJob'] ?? null,
			'fk_id_user' => session()->get('id'),
			'locates_description' => $post['description'] ?? null,
			'locates_photo' => $path,
			'locates_date' => date("Y-m-d H:i:s"),
		];

		$builder = $this->db->table('job_locates');

		return $builder->insert($data);
	}

	/**
	 * Add Excavation
	 * @since 2/8/2021
	 */
	public function addExcavation(array $post)
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'fk_id_job' => $post['hddIdJob'] ?? null,
			'project_location' => $post['project_location'] ?? null,
			'depth' => $post['depth'] ?? null,
			'width' => $post['width'] ?? null,
			'length' => $post['length'] ?? null,
			'confined_space' => $post['confined_space'] ?? null,
			'fk_id_confined' => $post['idConfined'] ?? null,
			'tested_daily' => $post['tested_daily'] ?? null,
			'tested_daily_explanation' => $post['tested_daily_explanation'] ?? null,
			'ventilation' => $post['ventilation'] ?? null,
			'ventilation_explanation' => $post['ventilation_explanation'] ?? null,
			'soil_classification' => $post['soil_classification'] ?? null,
			'soil_type' => $post['soil_type'] ?? null,
			'description_safe_work' => $post['description_safe_work'] ?? null,
			'practice_work_alone' => $post['practice_work_alone'] ?? null,
			'practice_eye_contact' => $post['practice_eye_contact'] ?? null,
			'practice_communication' => $post['practice_communication'] ?? null,
			'practice_walls' => $post['practice_walls'] ?? null,
			'practice_protective_structures' => $post['practice_protective_structures'] ?? null,
			'practice_identify_underground' => $post['practice_identify_underground'] ?? null,
			'practice_scope' => $post['practice_scope'] ?? null,
			'practice_site_locates' => $post['practice_site_locates'] ?? null,
			'practice_provided_safe' => $post['practice_provided_safe'] ?? null,
			'practice_traffic_control' => $post['practice_traffic_control'] ?? null,
			'practice_flaggers' => $post['practice_flaggers'] ?? null,
			'practice_barricades' => $post['practice_barricades'] ?? null,
		];

		//solo usuarios SUPER_ADMIN pueden ingresar la fecha de la inspeccion
		$userRol = session()->get('rol');
		$dateIssue = $post['date'] ?? null;
		$data['date_excavation'] = date("Y-m-d"); //fecha del registro
		if ($userRol == 99 && $dateIssue != "") {
			$data['date_excavation'] = $dateIssue;
		}
		
		$builder = $this->db->table('job_excavation');

		if (empty($id)) {
			$data['fk_id_user'] = session()->get('id');
			if ($builder->insert($data)) {
				return $this->db->insertID();
			}
			return false;
		} else {
			$update = $builder->where('id_job_excavation', $id)
							->update($data);

			return $update ? $id : false;
		}
	}

	/**
	 * Update Excavation - Personnel
	 * @since 14/8/2021
	 */
	public function updatePersonnel($post)
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'fk_id_user_manager' => $post['manager'] ?? null,
			'fk_id_user_operator' => $post['operator'] ?? null,
			'fk_id_user_supervisor' => $post['supervisor'] ?? null,
		];

		$builder = $this->db->table('job_excavation');

		return $builder->where('id_job_excavation', $id)
						->update($data);
	}

	/**
	 * @author BMOTTAG
	 * @since 14/11/2021
	 * Consulta de empleados para un formato de excavation
	 */
	public function get_selected_workers_excavation($idExcavation)
	{
		return $this->db->table('job_excavation_workers')
			->select('fk_id_user')
			->where('fk_id_job_excavation', $idExcavation)
			->get()
			->getResultArray();
	}

	/**
	 * Add Excavation and Trenching Plan WORKER
	 * @since 14/8/2021
	 */
	public function add_excavation_worker(array $post = []): bool
	{
		$id =  $post['hddIdExcavation'];
		$workers =  $post['workers'];

		// 🔥 INSERT hazards
		if (!empty($workers)) {
			$dataBatch = [];
			foreach ($workers as $idWorker) {
				$dataBatch[] = [
					'fk_id_job_excavation' => (int)$id,
					'fk_id_user' => (int)$idWorker
				];
			}

			return (bool) $this->db->table('job_excavation_workers')
								->insertBatch($dataBatch);
		}

		return true;
	}

	/**
	 * Save one worker - Excavation and Trenching Plan
	 * @since 14/8/2021
	 */
	public function excavationSaveOneWorker(array $post = []): bool
	{
		$data = [
			'fk_id_job_excavation' => $post['hddIdExcavation'] ?? null,
			'fk_id_user' => $post['worker'] ?? null
		];

		$builder = $this->db->table('job_excavation_workers');
		return $builder->insert($data);
	}

	/**
	 * Save subcontractor worker
	 * @since 26/2/2018
	 */
	public function saveSubcontractorWorkerExcavation(array $post = []): bool
	{
		$data = [
			'fk_id_job_excavation' => $post['hddIdExcavation'] ?? null,
			'fk_id_company' => $post['company'] ?? null,
			'worker_name' => $post['workerName'] ?? null,
			'worker_movil_number' => $post['phone_number'] ?? null
		];

		$builder = $this->db->table('job_excavation_subcontractor');
		return $builder->insert($data);
	}

	/**
	 * Update Excavation - Protection Methods
	 * @since 8/8/2021
	 */
	public function updateExcavation($post, $file)
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'protection_sloping' => $post['sloping'] ?? null,
			'protection_type_a' => $post['type_a'] ?? null,
			'protection_type_b' => $post['type_b'] ?? null,
			'protection_type_c' => $post['type_c'] ?? null,
			'protection_benching' => $post['benching'] ?? null,
			'protection_shoring' => $post['shoring'] ?? null,
			'protection_shielding' => $post['shielding'] ?? null,
			'additional_comments' => $post['additional_comments'] ?? null
		];

		if ($file !== null) {
			$data['method_system_doc'] = $file;
		}

		$builder = $this->db->table('job_excavation');

		return $builder->where('id_job_excavation', $id)
						->update($data);
	}

	/**
	 * Update Excavation - Access & Egress
	 * @since 8/8/2021
	 */
	public function updateExcavationAccess(array $post = []): bool
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'access_ladder' => $post['ladder'] ?? null,
			'access_ramp' => $post['ramp'] ?? null,
			'access_other' => $post['other'] ?? null,
			'access_explain' => $post['access_explain'] ?? null
		];

		$builder = $this->db->table('job_excavation');

		return $builder->where('id_job_excavation', $id)
						->update($data);
	}

	/**
	 * Update Excavation - Affected Zone, Traffic & Utilities
	 * @since 8/8/2021
	 */
	public function updateExcavationAffectedZone($post, $file)
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'located' => $post['located'] ?? null,
			'permit_required' => $post['permit_required'] ?? null,
			'utility_lines' => $post['utility_lines'] ?? null,
			'utility_lines_explain' => $post['utility_lines_explain'] ?? null,
			'encumbrances' => $post['encumbrances'] ?? null,
			'method_support' => $post['method_support'] ?? null,
			'utility_shutdown' => $post['utility_shutdown'] ?? null,
			'spoil_piles' => $post['spoil_piles'] ?? null,
			'spoils_transported' => $post['spoils_transported'] ?? null,
			'environmental_controls' => $post['environmental_controls'] ?? null,
			'open_overnight' => $post['open_overnight'] ?? null,
			'methods_secure' => $post['methods_secure'] ?? null,
			'vehicle_traffic' => $post['vehicle_traffic'] ?? null,
		];

		if ($file !== null) {
			$data['permit_required_doc'] = $file;
		}

		$builder = $this->db->table('job_excavation');

		return $builder->where('id_job_excavation', $id)
					->update($data);
	}

	/**
	 * Update Excavation - De-Watering
	 * @since 8/8/2021
	 */
	public function updateExcavationDeWatering(array $post = []): bool
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'dewatering_needed' => $post['dewatering_needed'] ?? null,
			'explain_equipment' => $post['explain_equipment'] ?? null,
			'body_water' => $post['body_water'] ?? null,
			'water_conducted' => $post['water_conducted'] ?? null,
			'additional_notes' => $post['additional_notes'] ?? null
		];

		$builder = $this->db->table('job_excavation');

		return $builder->where('id_job_excavation', $id)
						->update($data);
	}

	/**
	 * Fire watch setup
	 * @since 27/1/2023
	 */
	public function get_fire_watch_setup($arrDatos)
	{
		$builder = $this->db->table('job_fire_watch_setttings F');
		$builder->select('F.*, CONCAT(U.first_name, " " , U.last_name) reportedby, CONCAT(Z.first_name, " " , Z.last_name) supervisor, Z.movil as super_number, J.id_job, J.job_description');
		$builder->join('param_jobs J', 'J.id_job = F.fk_id_job', 'INNER');
		$builder->join('user U', 'U.id_user = F.fk_id_user', 'INNER');
		$builder->join('user Z', 'Z.id_user = F.fk_id_supervisor', 'INNER');
		if (isset($arrDatos["idJob"])) {
			$builder->where('fk_id_job', $arrDatos["idJob"]);
		}
		$builder->orderBy('id_job_fire_watch_settings', 'DESC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Fire watch list
	 * @since 27/1/2023
	 */
	public function get_fire_watch($arrDatos)
	{
		$builder = $this->db->table('job_fire_watch F');
		$builder->select('F.*, CONCAT(U.first_name, " " , U.last_name) reportedby, CONCAT(X.first_name, " " , X.last_name) conductedby, CONCAT(Z.first_name, " " , Z.last_name) supervisor, Z.movil as super_number, J.id_job, J.job_description');
		$builder->join('param_jobs J', 'J.id_job = F.fk_id_job', 'INNER');
		$builder->join('user U', 'U.id_user = F.fk_id_user', 'INNER');
		$builder->join('user X', 'X.id_user = F.fk_id_conducted_by', 'INNER');
		$builder->join('user Z', 'Z.id_user = F.fk_id_supervisor', 'INNER');
		if (isset($arrDatos["idJob"])) {
			$builder->where('fk_id_job', $arrDatos["idJob"]);
		}
		if (isset($arrDatos["idFireWatch"])) {
			$builder->where('id_job_fire_watch', $arrDatos["idFireWatch"]);
		}

		$builder->orderBy('id_job_fire_watch', 'DESC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Add/Edit FIRE WATCH
	 * @since 27/3/2023
	 */
	public function saveFireWatchSetup(array $post = []): bool
	{
		$metodo = $post['hddMetodo'] ?? null;
		$date = $post['date'] ?? null;
		$time = $post['time'] ?? null;
		$completeDate = ($date && $time) ? $date . " " . $time . ":00:00" : null;

		$dateRestored = $post['dateRestored'] ?? null;
		$timeRestored = $post['timeRestored'] ?? null;
		$completeDateRestored = ($dateRestored && $timeRestored)
			? $dateRestored . " " . $timeRestored . ":00:00"
			: null;

		$data = [
			'fk_id_user' => session()->get('id'),
			'fk_id_supervisor' => $post['supervisor'] ?? null,
			'building_address' => $post['address'] ?? null,
			'date_out' => $completeDate,
			'date_restored' => $completeDateRestored,
			'fire_alarm' => $post['fire_alarm'] ?? null,
			'fire_sprinkler' => $post['fire_sprinkler'] ?? null,
			'standpipe' => $post['standpipe'] ?? null,
			'fire_pump' => $post['fire_pump'] ?? null,
			'fire_suppression' => $post['fire_suppression'] ?? null,
			'other' => $post['other'] ?? null,
			'areas' => $post['areas'] ?? null,
		];

		$builder = $this->db->table('job_fire_watch_setttings');

		if ($metodo == 'create') {
			$data["fk_id_job"] = $post['hddIdJob'];
			return $builder->insert($data);
		} else {
			return $builder->where('fk_id_job', $post['hddIdJob'])
						->update($data);
		}
	}

	/**
	 * Add/Edit FIRE WATCH
	 * @since 27/3/2023
	 */
	public function saveFireWatch(array $post = []): bool
	{
		$idUser = session()->get('id'); 
		$id = $post['hddIdFireWatch'] ?? null;
		$date = $post['date'] ?? null;
		$time = $post['time'] ?? null;
		$completeDate = ($date && $time) ? $date . " " . $time . ":00:00" : null;

		$dateRestored = $post['dateRestored'] ?? null;
		$timeRestored = $post['timeRestored'] ?? null;
		$completeDateRestored = ($dateRestored && $timeRestored)
			? $dateRestored . " " . $timeRestored . ":00:00"
			: null;

		$data = [
			'fk_id_job' => $post['hddIdJob'] ?? null,
			'fk_id_user' => $idUser,
			'fk_id_conducted_by' => $idUser,
			'fk_id_supervisor' => $post['supervisor'] ?? null,
			'building_address' => $post['address'] ?? null,
			'date_out' => $completeDate,
			'date_restored' => $completeDateRestored,
			'fire_alarm' => $post['fire_alarm'] ?? null,
			'fire_sprinkler' => $post['fire_sprinkler'] ?? null,
			'standpipe' => $post['standpipe'] ?? null,
			'fire_pump' => $post['fire_pump'] ?? null,
			'fire_suppression' => $post['fire_suppression'] ?? null,
			'other' => $post['other'] ?? null,
			'areas' => $post['areas'] ?? null,
			'training_completed' => $post['training'] ?? null,
			'safety_shoes' => $post['safety_shoes'] ?? null,
			'safety_vest' => $post['safety_vest'] ?? null,
			'safety_glasses' => $post['safety_glasses'] ?? null,
			'hearing_protection' => $post['hearing_protection'] ?? null,
			'snow_cleets' => $post['snow_cleets'] ?? null,
			'dust_proof_mask' => $post['dust_proof_mask'] ?? null,
			'hard_hat' => $post['hard_hat'] ?? null,
			'gloves' => $post['gloves'] ?? null,
			'other_ppe' => $post['other_ppe'] ?? null,
			'operational_impacts' => $post['operational_impacts'] ?? null,
			'map_routing' => $post['map_routing'] ?? null,
			'raic_access' => $post['raic_access'] ?? null,
			'radio' => $post['radio'] ?? null,
			'emergency_contacts' => $post['emergency_contacts'] ?? null,
			'keys_access' => $post['keys_access'] ?? null,
		];

		$builder = $this->db->table('job_fire_watch');

		if (empty($id)) {
			$data["date_commenced"] = date("Y-m-d G:i:s");
			return $builder->insert($data);
		} else {
			return $builder->where('id_job_fire_watch', $id)
						->update($data);
		}
	}

	/**
	 * Check In List for fire watch
	 * @since 1/6/2022
	 */
	public function get_fire_watch_checkin($arrDatos)
	{
		$builder = $this->db->table('job_fire_watch_checkin C');
		$builder->join('user U', 'U.id_user = C.fk_id_worker', 'INNER');

		if (isset($arrDatos["distinctUser"])) {
			$builder->select('U.id_user, U.first_name, U.last_name, U.movil');
			$builder->groupBy('U.id_user');
			$builder->orderBy('U.first_name, U.last_name', 'ASC');
		} else {
			$builder->select('C.*, U.first_name, U.last_name, U.movil');
			$builder->orderBy('C.fk_id_job_fire_watch, C.id_checkin', 'ASC');
		}		

		
		if (isset($arrDatos["idCheckin"])) {
			$builder->where('C.id_checkin', $arrDatos["idCheckin"]);
		}
		if (isset($arrDatos["idFireWatch"])) {
			$builder->where('C.fk_id_job_fire_watch', $arrDatos["idFireWatch"]);
		}
		if (isset($arrDatos["checkout"])) {
			$builder->where('C.checkout_time', '0000-00-00 00:00:00');
		}

		if (isset($arrDatos["distinctUser"])) {
			$builder->orderBy('U.first_name, U.last_name', 'ASC');
		} else {
			$builder->orderBy('C.fk_id_job_fire_watch, C.id_checkin', 'ASC');
		}

		return $builder->get()->getResultArray();
	}

	/**
	 * Add Fire Watch checkin
	 * @since 3/02/2023
	 */
	public function saveFireWatchCheckin(array $post): bool
	{
		$site = session()->get("current_tag_name") ?? null;

		$data = [
			'fk_id_job_fire_watch' => $post['idFireWatch'] ?? null,
			'fk_id_worker' => session()->get("id"),
			'checkin_date' => date('Y-m-d'),
			'checkin_time' => date("Y-m-d H:i:s"),
			'address_start' => $post['address'] ?? null,
			'latitude_start' => $post['latitude'] ?? null,
			'longitude_start' => $post['longitude'] ?? null,
			'notes' => $post['notes'] ?? null,
			'site'  => $site
		];

		$builder = $this->db->table('job_fire_watch_checkin');
		return $builder->insert($data);
	}

	/**
	 * Get workorder expenses grouped by workorder for a given job detail
	 * @since 6/1/2023
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function countExpenses(array $arrParam)
	{
		$builder = $this->db->table('workorder_expense E');
		$builder->select('W.id_workorder, W.date, W.observation, ROUND(SUM(E.expense_value), 2) AS total_expenses');
		$builder->join('workorder W', 'W.id_workorder = E.fk_id_workorder', 'INNER');
		$builder->where('E.fk_id_job_detail', $arrParam['idJobDetail']);
		$builder->groupBy('W.id_workorder');
		$query = $builder->get();

		return $query->getNumRows() > 0 ? $query->getResultArray() : false;
	}

	/**
	 * Insert a job detail record from CSV upload
	 * @since 20/06/2022
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function upload_file_detail(array $data): bool
	{
		return $this->db->table('job_details')->insert($data);
	}

	/**
	 * Sum extended_amount for all job details of a job
	 * @since 13/1/2023
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function sumExtendedAmount(array $arrParam)
	{
		$builder = $this->db->table('job_details J');
		$builder->selectSum('extended_amount', 'TOTAL');
		$builder->where('J.fk_id_job', $arrParam['idJob']);
		$row = $builder->get()->getRowArray();

		return $row['TOTAL'] ?? 0;
	}

	/**
	 * Update percentage field on each job detail based on its proportion of total
	 * @since 13/1/2023
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function updateJobDetail(array $jobDetails, $totalExtendedAmount): bool
	{
		$result = true;

		foreach ($jobDetails as $detail) {
			$percentage = $totalExtendedAmount != 0
				? number_format($detail['extended_amount'] * 100 / $totalExtendedAmount, 2)
				: 0.00;

			$ok = $this->db->table('job_details')
				->where('id_job_detail', $detail['id_job_detail'])
				->update(['percentage' => $percentage]);

			if (!$ok) {
				$result = false;
			}
		}

		return $result;
	}

	/**
	 * Delete workorder expenses linked to all job details of a job
	 * @since 20/06/2022
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function deleteWOExpenses(array $arrParam): bool
	{
		$ids = $this->db->table('job_details')
			->select('id_job_detail')
			->where('fk_id_job', $arrParam['idJob'])
			->get()
			->getResultArray();

		if (empty($ids)) {
			return true;
		}

		return $this->db->table('workorder_expense')
			->whereIn('fk_id_job_detail', array_column($ids, 'id_job_detail'))
			->delete();
	}

	/**
	 * Update Flags Param Job
	 * @since 31/1/2024
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function resetFlagsParamJob(array $arrParam): bool
	{
		return $this->db->table('param_jobs')
			->where('id_job', $arrParam['idJob'])
			->update(['flag_expenses' => 0, 'flag_upload_details' => 0]);
	}

	/**
	 * Update flag expenses in WO SUBMODULES
	 * @since 30/03/2024
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function updateWOSubmoduleFlag(array $arrData): bool
	{
		$sql = "UPDATE {$arrData['table']} X
					INNER JOIN workorder W ON W.id_workorder = X.fk_id_workorder
					SET X.flag_expenses = 0
					WHERE W.fk_id_job = ?";

		return $this->db->query($sql, [(int) $arrData['idJob']]);
	}

	/**
	 * Add/Edit JOB DETAIL
	 * @since 6/1/2023
	 * @review 08/05/2026 - new CI4 version
	 */
	public function saveJobDetail(array $post): bool
	{
		$idJobDetail = $post['hddId'] ?? '';

		$data = [
			'description'     => $post['description']  ?? null,
			'unit'            => $post['unit']          ?? null,
			'quantity'        => $post['quantity']      ?? 0,
			'unit_price'      => $post['unit_price']    ?? 0,
			'extended_amount' => ($post['quantity'] ?? 0) * ($post['unit_price'] ?? 0),
		];

		if ($idJobDetail == '') {
			$data['fk_id_user']     = session()->get('id');
			$data['fk_id_job']      = $post['hddIdJob']       ?? null;
			$data['chapter_number'] = $post['chapter_number'] ?? null;
			$data['chapter_name']   = $post['chapter']        ?? null;
			$data['item']           = $post['item']           ?? null;
			$data['status']         = 1;

			return $this->db->table('job_details')->insert($data);
		}

		$data['status'] = $post['status'] ?? null;

		return $this->db->table('job_details')
			->where('id_job_detail', $idJobDetail)
			->update($data);
	}



}
