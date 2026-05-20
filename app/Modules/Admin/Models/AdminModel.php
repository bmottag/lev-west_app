<?php
namespace App\Modules\Admin\Models;

use CodeIgniter\Model;
use App\Models\GeneralModel;

class AdminModel extends Model
{

	protected $protectFields = false;
	/**
	 * Add/Edit USER
	 * @since 8/11/2016
	 */

	public function saveEmployee($post)
	{
		$idUser = $post['hddId'] ?? null;

		$bankTime = ($post['employee_subcontractor'] ?? null) == 1 
			? 2 
			: ($post['bank_time'] ?? null);

		$data = [
			'first_name' => $post['firstName'],
			'last_name' => $post['lastName'],
			'log_user' => $post['user'],
			'social_insurance' => $post['insuranceNumber'],
			'health_number' => $post['healthNumber'],
			'birthdate' => $post['birth'],
			'movil' => $post['movilNumber'],
			'email' => $post['email'],
			'address' => $post['address'],
			'postal_code' => $post['postalCode'],
			'perfil' => $post['perfil'],
			'employee_rate' => $post['employee_rate'],
			'employee_type' => $post['employee_type'],
			'employee_subcontractor' => $post['employee_subcontractor'],
			'bank_time' => $bankTime
		];

		$builder = $this->db->table('user');

		if (empty($idUser)) {
			$data['state'] = 0;
			$data['password'] = password_hash('123456', PASSWORD_DEFAULT);

			return $builder->insert($data);
		} else {
			$data['state'] = $post['state'];

			return $builder->where('id_user', $idUser)
						->update($data);
		}
	}

	/**
	 * Add/Edit JOB
	 * @since 10/11/2016
	 */
	public function saveJob($post)
	{
		$logger = service('appLogger');

		$id = $post['hddId'] ?? null;

		$jobCode = trim($post['jobCode'] ?? '');
		$jobName = trim($post['jobName'] ?? '');
		$notes   = $post['notes'] ?? null;

		$data = [
			'job_code' => $jobCode,
			'job_name' => $jobName,
			'job_description' => $jobCode . " " . $jobName,
			'fk_id_company' => $post['company'] ?? null,
			'markup' => $post['markup'] ?? null,
			'profit' => $post['profit'] ?? null,
			'state' => $post['stateJob'] ?? null,
			'notes' => $notes,
			'planning_message' => $post['planning_message'] ?? null
		];

		$builder = $this->db->table('param_jobs');

		$userId = session()->get("id");
		if (empty($id)) {

			$data['created_by'] = $userId;
			$builder->insert($data);
			$idJob = $this->db->insertID();

			// LOGGER
			$logger
				->user($userId)
				->type('job_code')
				->id($idJob)
				->token('insert')
				->comment(json_encode([
					'old' => null,
					'new' => $data
				]))
				->log();
			//END LOGGER

			return $idJob;
		} else {

			$data['updated_by'] = $userId;

			$generalModel = new GeneralModel();

			$arrParam = [
				"table" => "param_jobs",
				"order" => "id_job",
				"column" => "id_job",
				"id" => $id
			];

			$oldData = $generalModel->get_basic_search($arrParam);
			$oldData = $oldData[0] ?? [];

			$builder->where('id_job', $id)->update($data);

			$changes = array_diff_assoc($data, $oldData);

			if (!empty($changes)) {

				$logger
					->user($userId)
					->type('job_code')
					->id($id)
					->token('update')
					->comment(json_encode([
						'old' => $oldData,
						'new' => $changes
					]))
					->log();
			}

			return $id;
		}
	}

	/**
	 * Add/Edit HAZARD
	 * @since 11/12/2016
	 */
	public function saveHazard($post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'fk_id_hazard_activity' => $post['activity'] ?? null,
			'hazard_description' => $post['hazardName'] ?? null,
			'solution' => $post['solution'] ?? null,
			'fk_id_priority' => $post['priority'] ?? null
		];

		$builder = $this->db->table('param_hazard');

		if (empty($id)) {
			return $builder->insert($data);
		} else {
			return $builder->where('id_hazard', $id)
						->update($data);
		}
	}

	/**
	 * Add/Edit COMPANY
	 * @since 13/12/2016
	 */
	public function saveCompany($post)
	{
		$idCompany = $post['hddId'] ?? null;

		$data = [
			'company_name' => $post['company'] ?? null,
			'contact' => $post['contact'] ?? null,
			'movil_number' => $post['movilNumber'] ?? null,
			'email' => $post['email'] ?? null,
			'does_hauling' => $post['does_hauling'] ?? null
		];

		$builder = $this->db->table('param_company');

		if (empty($idCompany)) {
			return $builder->insert($data);
		} else {
			return $builder->where('id_company', $idCompany)
						->update($data);
		}
	}

	/**
	 * Add/Edit MATERIAL
	 * @since 13/12/2016
	 */
	public function saveMaterial($post)
	{
		$idMaterial = $post['hddId'] ?? null;

		$data = [
			'material' => $post['material'] ?? null,
			'material_price' => $post['unit_price'] ?? null
		];

		$builder = $this->db->table('param_material_type');

		if (empty($idMaterial)) {
			return $builder->insert($data);
		} else {
			return $builder->where('id_material', $idMaterial)
						->update($data);
		}
	}

	/**
	 * Add/Edit Shop Parts
	 * @since 30/10/2023
	 */
	public function saveShopParts()
	{
		$idMaterial = $this->request->getPost('hddId');
		$idShop = $this->request->getPost('id_shop');

		if ($idShop == "") {
			$data = array(
				'shop_name' => addslashes($this->security->xss_clean($this->request->getPost('shop_name'))),
				'shop_contact' => addslashes($this->security->xss_clean($this->request->getPost('shop_contact'))),
				'shop_address' => addslashes($this->security->xss_clean($this->request->getPost('shop_address'))),
				'mobile_number' => addslashes($this->security->xss_clean($this->request->getPost('mobile_number'))),
				'shop_email' => addslashes($this->security->xss_clean($this->request->getPost('shop_email')))
			);

			$query = $this->db->insert('param_shop', $data);
			$idShop = $this->db->insert_id();
		}

		$data = array(
			'fk_id_material' => $idMaterial,
			'fk_id_shop' => $idShop,
			'date' => date('Y-m-d'),
		);

		$query = $this->db->insert('material_shop', $data);
		$idMaterial = $this->db->insert_id();

		if ($query) {
			return $idMaterial;
		} else {
			return false;
		}
	}

	public function get_material_with_shop()
	{
		$builder = $this->db->table('param_material_type P');

		$builder->select('P.*,
			(
				SELECT GROUP_CONCAT(
					"<b>", V.shop_name, "</b> ",
					V.shop_contact,
					" - Email: ", V.shop_email,
					" - Address: ", V.shop_address,
					" - Mobile: ", V.mobile_number
					SEPARATOR "<br>"
				)
				FROM material_shop E
				JOIN param_shop V ON V.id_shop = E.fk_id_shop
				WHERE E.fk_id_material = P.id_material
				GROUP BY E.fk_id_material
			) AS shops
		');

		$query = $builder->get();

		return $query->getNumRows() > 0 
			? $query->getResultArray() 
			: false;
	}

	/**
	 * Add/Edit vehicle
	 * @since 15/12/2016
	 * @review 27/12/2016
	 */
	public function saveVehicle($post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'fk_id_company' => $post['company'] ?? null,
			'type_level_1' => $post['type1'] ?? null,
			'type_level_2' => $post['type2'] ?? null,
			'make' => $post['make'] ?? null,
			'model' => $post['model'] ?? null,
			'manufacturer_date' => $post['manufacturer'] ?? null,
			'description' => $post['description'] ?? null,
			'unit_number' => $post['unitNumber'] ?? null,
			'vin_number' => $post['vinNumber'] ?? null,
			'state' => $post['state'] ?? null,
			'hours' => $post['hours'] ?? null
		];

		$builder = $this->db->table('param_vehicle');

		if (empty($id)) {
			$builder->insert($data);
			$idVehicle = $this->db->insertID();
			// generar token seguro
			$token = bin2hex(random_bytes(25));

			$builder->where('id_vehicle', $idVehicle)
					->update([
						'encryption' => $idVehicle . $token
					]);
			return $idVehicle;
		} else {
			$builder->where('id_vehicle', $id)
						->update($data);
			return $id;
		}
	}

	/**
	 * Get vehicle list
	 * Param int $companyType -> 1: Lev West; 2: Subcontractor
	 * @since 15/12/2016
	 * @review 26/2/2017
	 */
	public function get_vehicle_list($companyType)
	{
		$this->db->select();
		$this->db->join('param_company C', 'C.id_company = A.fk_id_company', 'INNER');
		$this->db->join('param_vehicle_type_2 T', 'T.id_type_2 = A.type_level_2', 'INNER');
		$this->db->where('C.company_type', $companyType);

		$this->db->orderBy('C.id_company, A.unit_number', 'asc');
		$query = $this->db->get('param_vehicle A');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	/**
	 * Get vehicle list
	 * Param int $companyType -> 1: Lev West; 2: Subcontractor
	 * Param int $vehicleType -> 1: Pickup; 2: Construction Equipment; 3: Trucks; 4: Special Equipment; 99: Otros
	 * @since 5/5/2017
	 */
	public function get_vehicle_info_by($arrData)
	{
		$builder = $this->db->table('param_vehicle A');
		$builder->select();
		$builder->join('param_company C', 'C.id_company = A.fk_id_company', 'INNER');
		$builder->join('param_vehicle_type_2 T', 'T.id_type_2 = A.type_level_2', 'INNER');

		if (isset($arrData["companyType"])) {
			$builder->where('C.company_type', $arrData["companyType"]);

			//si es de Lev West entonces filtrar por tipo de inspeccion de lo contrario no se hace el filtro
			if ($arrData["companyType"] == 1) {
				if (isset($arrData["vehicleType"])) {
					$builder->where('T.inspection_type', $arrData["vehicleType"]);
				}
			}
		}

		if (isset($arrData["idVehicle"])) {
			$builder->where('A.id_vehicle', $arrData["idVehicle"]);
		}
		if (isset($arrData["vehicleState"])) {
			$builder->where('A.state', $arrData["vehicleState"]);
		}

		$builder->orderBy('T.inspection_type, C.id_company, A.unit_number', 'asc');
		$query = $builder->get();

        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * Reset user´s password
	 * @author BMOTTAG
	 * @since  11/1/2017
	 */
	public function resetEmployeePassword($idUser)
	{
		$passwd = '123456';
		$passwd = md5($passwd);

		$data = array(
			'password' => $passwd,
			'state' => 0
		);

		$this->db->where('id_user', $idUser);
		$query = $this->db->update('user', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add vehicle next oil change
	 * @since 17/1/2017
	 */
	public function saveVehicleNextOilChange($idVehicle, $status, $post)
	{
		$idUser = session()->get("id");
		$data = [
			'fk_id_vehicle' => $idVehicle,
			'fk_id_user' => $idUser,
			'current_hours' => $post['hours'] ?? null,
			'next_oil_change' => $post['oilChange'] ?? null,
			'state' => $status,
			'current_hours_2' => $post['hours2'] ?? null,
			'next_oil_change_2' => $post['oilChange2'] ?? null,
			'current_hours_3' => $post['hours3'] ?? null,
			'next_oil_change_3' => $post['oilChange3'] ?? null,
			'date_issue' => date("Y-m-d G:i:s"),
		];

		$builder = $this->db->table('vehicle_oil_change');

		if ($builder->insert($data)) {
			$builderVehicle = $this->db->table('param_vehicle');
			$dataUpdate = [
				'hours' => $post['hours'] ?? null,
				'oil_change' => $post['oilChange'] ?? null,
				'hours_2' => $post['hours2'] ?? null,
				'oil_change_2' => $post['oilChange2'] ?? null,
				'hours_3' => $post['hours3'] ?? null,
				'oil_change_3' => $post['oilChange3'] ?? null,
			];

			return $builderVehicle->where('id_vehicle', $idVehicle)
								->update($dataUpdate);
		} else {
			return false;
		}
	}

	/**
	 * Add/Edit EMPLOYEE TYPE
	 * @since 4/2/2017
	 */
	public function saveEmployeeType($post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'employee_type' => $post['employeeType'] ?? null,
			'employee_type_unit_price' => $post['unit_price'] ?? null
		];

		$builder = $this->db->table('param_employee_type');

		if (empty($id)) {
			return $builder->insert($data);
		} else {
			return $builder->where('id_employee_type', $id)
						->update($data);
		}
	}

	/**
	 * Add/Edit HAZARD ACTIVITY
	 * @since 5/2/2017
	 */
	public function saveHazardActivity($post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'hazard_activity' => $post['hazardActivity'] ?? null
		];

		$builder = $this->db->table('param_hazard_activity');

		if (empty($id)) {
			return $builder->insert($data);
		} else {
			return $builder->where('id_hazard_activity', $id)
						->update($data);
		}
	}

	/**
	 * Get hazard list
	 * @since 5/2/2017
	 */
	public function get_hazard_list()
	{
		return $this->db->table('param_hazard H')
			->select('H.*, A.hazard_activity, P.*')
			->join('param_hazard_activity A', 'A.id_hazard_activity = H.fk_id_hazard_activity', 'inner')
			->join('param_hazard_priority P', 'P.id_priority = H.fk_id_priority', 'inner')
			->orderBy('A.hazard_activity, H.hazard_description', 'asc')
			->get()
			->getResultArray();
	}

	/**
	 * Update user´s password
	 * @author BMOTTAG
	 * @since  8/11/2016
	 */
	public function updatePassword($idUser, $plainPassword)
	{
		$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

		return $this->db->table('user')
				->where('id_user', $idUser)
				->update(['password' => $hashedPassword]);
	}

	/**
	 * Update jobs state
	 * @since 12/1/2019
	 */
	public function updateJobsState($state)
	{
		$this->load->library('logger');

		//if it comes from the active view, then inactive everything
		//else do nothing and continue with the activation
		if ($state == 1) {
			//update all states to inactive
			$data['state'] = 2;
			$data['updated_by'] = $this->session->userdata("id");
			$query = $this->db->update('param_jobs', $data);

			$sql = "SELECT id_job, state FROM param_jobs";

			$queryJob = $this->db->query($sql);
			$jobs = $queryJob->result_array();

			foreach ($jobs as $key => $job) {
				$log['old'] = json_encode($job['state']);
				$log['new'] = json_encode($data);

				$this->logger
					->user($this->session->userdata("id")) //$this->session->userdata("id");//Set UserID, who created this  Action
					->type('job_code_status') //Entry type like, Post, Page, Entry
					->id($job['id_job']) //Entry ID
					->token('update') //Token identify Action
					->comment(json_encode($log))
					->log(); //Add Database Entry
			}
		}

		//update states
		$query = 1;
		if ($jobs = $this->request->getPost('job')) {
			$tot = count($jobs);
			for ($i = 0; $i < $tot; $i++) {
				$data['state'] = 1;
				$data['updated_by'] = $this->session->userdata("id");
				$this->db->where('id_job', $jobs[$i]);
				$query = $this->db->update('param_jobs', $data);

				$sql = "SELECT id_job, state FROM param_jobs where id_job = " . $jobs[$i];

				$queryJob = $this->db->query($sql);
				$job = $queryJob->result_array()[0];

				$log['old'] = json_encode($job['state']);
				$log['new'] = json_encode($data);

				$this->logger
					->user($this->session->userdata("id")) //$this->session->userdata("id");//Set UserID, who created this  Action
					->type('job_code_status') //Entry type like, Post, Page, Entry
					->id($jobs[$i]) //Entry ID
					->token('update') //Token identify Action
					->comment(json_encode($log))
					->log(); //Add Database Entry
			}
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add/Edit STOCK
	 * @since 17/3/2020
	 */
	public function saveStock()
	{
		$idStock = $this->request->getPost('hddId');

		$data = array(
			'stock_description' => $this->request->getPost('stockDescription'),
			'stock_price' => $this->request->getPost('price'),
			'quantity' => $this->request->getPost('quantity')
		);

		//revisar si es para adicionar o editar
		if ($idStock == '') {
			$query = $this->db->insert('stock', $data);
			$idStock = $this->db->insert_id();
		} else {
			$this->db->where('id_stock', $idStock);
			$query = $this->db->update('stock', $data);
		}
		if ($query) {
			return $idStock;
		} else {
			return false;
		}
	}

	/**
	 * Add/Edit CERTIFICATE
	 * @since 14/1/2022
	 */
	public function saveCertificate($post)
	{
		$idCertificate = $post['hddId'] ?? null;

		$data = [
			'certificate' => $post['certificate'] ?? null,
			'certificate_description' => $post['description'] ?? null
		];

		$builder = $this->db->table('param_certificates');

		if (empty($idCertificate)) {
			return $builder->insert($data);
		} else {
			return $builder->where('id_certificate', $idCertificate)
						->update($data);
		}
	}

	/**
	 * Add/Edit CERTIFICATE to Employee
	 * @since 15/1/2022
	 */
	public function saveEmployeeCertificate($post)
	{
		$idEmployeeCertificate = $post['hddidEmployeeCertificate'] ?? null;

		$builder = $this->db->table('user_certificates'); // 🔥 SIN espacio

		if (empty($idEmployeeCertificate)) {

			// INSERT
			$expire = $post['expire'] ?? null;
			$date_through = ($expire == 2) ? null : ($post['dateThrough'] ?? null);

			$data = [
				'expires' => $expire,
				'date_through' => $date_through,
				'fk_id_user' => $post['hddidEmployee'],
				'fk_id_certificate' => $post['certificate'],
				'alerts_sent' => 0
			];

			return $builder->insert($data);

		} else {

			// UPDATE
			$expire = $post['expiresUpdate'] ?? null;
			$date_through = ($expire == 2) ? null : ($post['dateThroughUpdate'] ?? null);

			$data = [
				'expires' => $expire,
				'date_through' => $date_through
			];

			return $builder->where('id_user_certificate', $idEmployeeCertificate)
						->update($data);
		}
	}

	/**
	 * Add/Edit NOTIFICATIONS ACCESS SETTINGS
	 * @since 23/01/2022
	 * @REVIEW 22/12/2022
	 */
	public function saveNotification($post)
	{
		$id = $post['hddId'] ?? null;

		$smsTo = !empty($post['smsTo']) ? json_encode($post['smsTo']) : null;
		$emailTo = !empty($post['emailTo']) ? json_encode($post['emailTo']) : null;

		$data = [
			'fk_id_user_email' => $emailTo,
			'fk_id_user_sms' => $smsTo
		];

		$builder = $this->db->table('notifications_access');

		if (empty($id)) {
			$data['fk_id_notification'] = $post['notification'] ;
			return $builder->insert($data);
		} else {
			return $builder->where('id_notification_access', $id)
						->update($data);
		}
	}

	/**
	 * Update employee rate
	 * @since 16/2/2022
	 */
	public function updateEmployeeRate(array $post = []): bool
	{
		if (empty($post['id'])) {
			return false;
		}

		$batch = [];

		foreach ($post['id'] as $i => $idUser) {

			$bankTime = ($post['employee_subcontractor'][$i] == 1)
				? 2
				: $post['bank_time'][$i];

			$batch[] = [
				'id_user'                => $idUser,
				'employee_rate'          => $post['employee_rate'][$i],
				'employee_type'          => $post['type'][$i],
				'employee_subcontractor' => $post['employee_subcontractor'][$i],
				'bank_time'              => $bankTime,
			];
		}

		return (bool) $this->db
			->table('user')
			->updateBatch($batch, 'id_user');
	}

	/**
	 * Add/Edit Attachment
	 * @since 23/06/2023
	 */
	public function saveAttachment(array $post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'attachment_number' => $post['attachment_number'] ?? null,
			'attachment_description' => $post['attachment_description'] ?? null,
		];

		$builder = $this->db->table('param_attachments');

		if (empty($id)) {
			if ($builder->insert($data)) {
				return $this->db->insertID();
			}
			return false;
		} else {
			$update = $builder->where('id_attachment', $id)
							->update($data);

			return $update ? $id : false;
		}
	}

	/**
	 * Attachments list
	 * @since 23/6/2023
	 */
	public function get_attachments(array $arrDatos = []): array
	{
		$builder = $this->db->table('param_attachments P');

		$builder->select("
			P.*, 
			S.*, 
			(
				SELECT GROUP_CONCAT(
					CONCAT(V.unit_number, ' -----> ', V.description) 
					SEPARATOR '<br>'
				)
				FROM param_attachments_equipment A
				JOIN param_vehicle V ON V.id_vehicle = A.fk_id_equipment
				WHERE A.fk_id_attachment = P.id_attachment
				GROUP BY A.fk_id_attachment
			) AS equipments
		");

		$builder->join('param_status S', 'S.status_slug = P.attachment_status', 'inner');

		// Filtros
		if (!empty($arrDatos["idAttachment"])) {
			$builder->where('P.id_attachment', $arrDatos["idAttachment"]);
		}

		if (!empty($arrDatos["status"])) {
			$builder->where('P.attachment_status', $arrDatos["status"]);
		}

		$builder->orderBy('P.attachment_number', 'ASC');

		$query = $builder->get();

		return $query->getNumRows() > 0
			? $query->getResultArray()
			: [];
	}

	/**
	 * Add Equipment to Attachements
	 * @since 26/06/2023
	 */
	public function add_equipment_attachement(int $idAttachment, array $equipment = []): bool
	{
		if (empty($idAttachment)) {
			return false;
		}

		// 🔥 DELETE relaciones anteriores
		$this->db->table('param_attachments_equipment')
				->where('fk_id_attachment', $idAttachment)
				->delete();

		// 🔥 INSERT nuevas relaciones si hay equipment
		if (!empty($equipment)) {
			$dataBatch = [];
			foreach ($equipment as $idEquipment) {
				$dataBatch[] = [
					'fk_id_attachment' => $idAttachment,
					'fk_id_equipment' => (int)$idEquipment
				];
			}

			return (bool) $this->db->table('param_attachments_equipment')
								->insertBatch($dataBatch);
		}

		return true;
	}

	/**
	 * Attachments list
	 * @since 26/6/2023
	 */
	public function get_attachments_equipment($arrDatos)
	{
		$builder = $this->db->table('param_attachments_equipment P');
		if (array_key_exists("relation", $arrDatos)) {
			$builder->select('P.fk_id_equipment');
		} else {
			$builder->select('P.*, T.inspection_type');
			$builder->join('param_vehicle V', 'V.id_vehicle = P.fk_id_equipment', 'INNER');
			$builder->join('param_vehicle_type_2 T', 'T.id_type_2 = V.type_level_2', 'INNER');
		}
		if (array_key_exists("idAttachment", $arrDatos)) {
			$builder->where('fk_id_attachment', $arrDatos["idAttachment"]);
		}
		return $builder->get()->getResultArray();
	}

	/**
	 * Informacion del foreman
	 * @since 13/01/2025
	 */
	public function save_foreman($idJob, $post)
	{
		$id = $post['hddIdForeman'] ?? null;

		$data = [
			'foreman_name' => $post['foreman'] ?? null,
			'foreman_movil_number' => $post['movilNumber'] ?? null,
			'foreman_email' => $post['email'] ?? null
		];

		$builder = $this->db->table('param_company_foreman');

		if (empty($id)) {
			$data['fk_id_job'] = $idJob;
			$data['fk_id_param_company'] = $post['company'];
			return $builder->insert($data);
		} else {
			return $builder->where('id_company_foreman', $id)
						->update($data);
		}
	}

	/**
	 * Job Log
	 * @since 20/02/2024
	 */
	public function get_job_log($arrData)
	{
		$this->db->select("L.*, CONCAT(first_name, ' ', last_name) name, j.job_description");
		$this->db->join('user U', 'U.id_user = L.created_by', 'INNER');
		$this->db->join('param_jobs j', 'L.type_id = j.id_job', 'LEFT');
		$this->db->join('param_company C', 'C.id_company = j.fk_id_company', 'LEFT');

		$parameters = array('job_code_status', 'job_code');
		$this->db->where_in('L.type', $parameters);
		
		if (array_key_exists("jobId", $arrData) && $arrData["jobId"] != '' && $arrData["jobId"] != 0) {
			$this->db->where('j.id_job', $arrData["jobId"]);
		}

		if (array_key_exists("userId", $arrData) && $arrData["userId"] != '' && $arrData["userId"] != 0) {
			$this->db->where('L.created_by', $arrData["userId"]);
		}

		if (array_key_exists("from", $arrData) && $arrData["from"] != '') {
			$this->db->where('L.created_on >=', $arrData["from"]);
		}

		if (array_key_exists("to", $arrData) && $arrData["to"] != '' && $arrData["from"] != '') {
			$this->db->where('L.created_on <=', $arrData["to"]);
		}
		$this->db->orderBy('L.id', 'asc');
		$query = $this->db->get('logger L');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function getAvailableNotifications()
	{
		$builder = $this->db->table('notifications n');

		$builder->select('n.*');
		$builder->join(
			'notifications_access na',
			'n.id_notification = na.fk_id_notification',
			'left'
		);

		$builder->where('na.fk_id_notification IS NULL');
		$builder->where('n.setup', 1);

		return $builder->get()->getResultArray();
	}

	/**
	 * Tag
	 * @since 25/04/2026
	 */
	public function saveTag($post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'fk_id_job' => $post['idJob'] ?? null,
			'name' => $post['name'] ?? null,
			'number' => $post['number'] ?? null
		];

		$builder = $this->db->table('param_tags');

		if (empty($id)) {
			$data['token'] = bin2hex(random_bytes(16));
			return $builder->insert($data);
		} else {
			return $builder->where('id_tag', $id)
						->update($data);
		}
	}

	/**
	 * Get Tags list
	 * @since 25/04/2026
	 */
	public function get_tags($arrData)
	{
		$builder = $this->db->table('param_tags T');
		$builder->select();
		$builder->join('param_jobs J', 'J.id_job = T.fk_id_job', 'INNER');

		if (isset($arrData["idTag"])) {
			$builder->where('T.id_tag', $arrData["idTag"]);
		}

		$builder->orderBy('T.fk_id_job, T.name', 'ASC');

		return $builder->get()->getResultArray();
	}
}
