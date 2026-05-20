<?php
namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\AdminModel;
use App\Models\GeneralModel;

class Admin extends BaseController
{
    protected $adminModel;
    protected $generalModel;
    protected $helpers = ['form'];
    
    public function __construct()
    {
        $this->adminModel   = new AdminModel();
        $this->generalModel   = new GeneralModel();
    }

	/**
	 * employee List
	 * @since 15/12/2016
	 * @author BMOTTAG
	 * @review 21/03/2026 - new CI4 version
	 */
	public function employee($state)
	{
		$data = [];
		$data['state'] = $state;
		if ($state == 1) {
			$arrParam = ["filtroState" => TRUE];
		} else {
			$arrParam = ["state" => $state];
		}

		// 1. Obtener usuarios
		$info = $this->generalModel->get_user($arrParam);

		// 2. Obtener TODOS los certificados (una sola consulta)
		$certificates = $this->generalModel->get_user_certificates([]);

		// 3. Agrupar certificados por usuario
		$groupedCertificates = [];
		if ($certificates) {
			foreach ($certificates as $cert) {
				$groupedCertificates[$cert['fk_id_user']][] = $cert;
			}
		}

		// 4. Asignar certificados a cada usuario
		if ($info) {
			foreach ($info as &$user) {
				$user['certificates'] = $groupedCertificates[$user['id_user']] ?? [];
			}
		}

		$data['info'] = $info;
		return $this->render('App\Modules\Admin\Views\employee', $data);
	}

	/**
	 * Cargo modal - formulario Employee
	 * @since 15/12/2016
	 * @review 21/03/2026 - new CI4 version
	 */
	public function cargarModalEmployee()
	{
		$data = [];
		$data['information'] = null;

		$idEmployee = $this->request->getPost("idEmployee");
		$data["idEmployee"] = $idEmployee;

		$arrParam = array("filtro" => TRUE);
		$data['roles'] = $this->generalModel->get_roles($arrParam);

		if (!empty($idEmployee) && $idEmployee !== 'x') {
			$arrParam = array(
				"table" => "user",
				"order" => "id_user",
				"column" => "id_user",
				"id" => $idEmployee
			);
			$data['information'] = $this->generalModel->get_basic_search($arrParam);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\employee_modal', $data));
	}

	/**
	 * Update Employee
	 * @since 15/12/2016
	 * @author BMOTTAG
	 * @review 21/03/2026 - new CI4 version
	 */
	public function save_employee()
	{
		$post = $this->request->getPost();
		$idUser = $post['hddId'] ?? null;

		$msj = $idUser ? "You have updated an Employee!!" : "You have added a new Employee!!";

		$log_user = $post['user'];
		$social_insurance = $post['insuranceNumber'];

		$result_user = false;
		$result_insurance = false;

		if (empty($idUser)) {
			$result_user = $this->generalModel->verifyUser([
				"column" => "log_user",
				"value" => $log_user
			]);
			$result_insurance = $this->generalModel->verifyUser([
				"column" => "social_insurance",
				"value" => $social_insurance
			]);
		}

		if ($result_user || $result_insurance) {

			$mensaje = $result_user && $result_insurance
				? "User and SIN already exist."
				: ($result_user ? "User already exists." : "Social Insurance Number already exists.");

			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> ' . $mensaje);

			return $this->response->setJSON([
				"result" => "error",
				"mensaje" => $mensaje
			]);

		} else {
			if ($this->adminModel->saveEmployee($post)) {
				session()->setFlashdata('retornoExito', $msj);

				// ⚡ IMPORTANTE: enviar 'state' para la redirección
				$state = $post['state'] ?? 1;

				return $this->response->setJSON([
					"result" => true,
					"mensaje" => $msj,
					"state" => $state
				]);
			} else {
				$error = "Error saving data";
				session()->setFlashdata('retornoError', '<strong>Error!!!</strong> ' . $error);

				return $this->response->setJSON([
					"result" => "error",
					"mensaje" => $error
				]);
			}
		}
	}

	/**
	 * Reset employee password
	 * Reset the password to '123456'
	 * And change the status to '0' to changue de password 
	 * @since 11/1/2017
	 * @author BMOTTAG
	 */
	public function resetPassword($idUser)
	{
		if ($this->adminModel->resetEmployeePassword($idUser)) {
			$this->session->set_flashdata('retornoExito', 'You have reset the Employee pasword to: 123456');
		} else {
			$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		redirect("/admin/employee/", 'refresh');
	}

	/**
	 * Material List
	 * @since 13/12/2016
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function material()
	{
		$data['info'] = $this->adminModel->get_material_with_shop();
		return $this->render('App\Modules\Admin\Views\material', $data);
	}

	/**
	 * Cargo modal - formulario material type
	 * @since 13/12/2016
	 * @review 28/03/2026 - new CI4 version
	 */
	public function cargarModalMaterial()
	{
		$data = [];
		$data['information'] = null;

		$idMaterial = $this->request->getPost("idMaterial");
		$data["idMaterial"] = $idMaterial;

		if (!empty($idMaterial) && $idMaterial !== 'x') {
			$arrParam = array(
				"table" => "param_material_type",
				"order" => "id_material",
				"column" => "id_material",
				"id" => $idMaterial
			);
			$data['information'] = $this->generalModel->get_basic_search($arrParam);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\material_modal', $data));
	}

	/**
	 * Update material
	 * @since 13/12/2016
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function save_material()
	{
		$post = $this->request->getPost();

		$idMaterial = $post['hddId'] ?? null;
		$msj = $idMaterial 
			? "You have updated a Material Type!!" 
			: "You have added a new Material Type!!";

		$data = [];

		if ($this->adminModel->saveMaterial($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Cargo modal - Shop
	 * @since 30/10/2023
	 */
	public function loadModalShop()
	{
		header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos

		$data["idMaterial"] = $this->request->getPost("idMaterial");

		$arrParam = array(
			"table" => "param_shop",
			"order" => "shop_name",
			"id" => "x"
		);
		$data['shopList'] = $this->generalModel->get_basic_search($arrParam);

		return view('App\Modules\Admin\Views\shop_modal', $data);
	}

	/**
	 * Save Shop Parts
	 * @since 30/10/2023
	 * @author BMOTTAG
	 */
	public function save_shop_materials()
	{
		header('Content-Type: application/json');
		$data = array();

		$idMaterial = $this->request->getPost('hddId');

		$msj = "You have added the Shop Information for Material!!";
		if ($idMaterial != '') {
			$msj = "You have updated the Shop Information for Material!!";
		}

		if ($idMaterial = $this->adminModel->saveShopParts()) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		echo json_encode($data);
	}

	/**
	 * Company List
	 * @since 15/12/2016
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function company()
	{
		//se filtra por company_type para que solo se pueda editar los subcontratistas
		$arrParam = array(
			"table" => "param_company",
			"order" => "id_company",
			"column" => "company_type",
			"id" => 2
		);
		$data['info'] = $this->generalModel->get_basic_search($arrParam);

		return $this->render('App\Modules\Admin\Views\company', $data);
	}

	/**
	 * Cargo modal - formulario company
	 * @since 15/12/2016
	 * @review 28/03/2026 - new CI4 version
	 */
	public function cargarModalCompany()
	{
		$data = [];
		$data['information'] = null;

		$idCompany = $this->request->getPost("idCompany");
		$data["idCompany"] = $idCompany;

		if (!empty($idCompany) && $idCompany !== 'x') {
			$arrParam = array(
				"table" => "param_company",
				"order" => "id_company",
				"column" => "id_company",
				"id" => $idCompany
			);
			$data['information'] = $this->generalModel->get_basic_search($arrParam);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\company_modal', $data));
	}

	/**
	 * Update company
	 * @since 15/12/2016
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function save_company()
	{
		$post = $this->request->getPost();

		$idCompany = $post['hddId'] ?? null;
		$msj = $idCompany 
			? "You have updated a Company!!" 
			: "You have added a new Company!!";

		$data = [];

		if ($this->adminModel->saveCompany($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * hazard List
	 * @since 15/12/2016
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function hazard()
	{
		$data['info'] = $this->adminModel->get_hazard_list();
		return $this->render('App\Modules\Admin\Views\hazard', $data);
	}

	/**
	 * Cargo modal - formulario hazard
	 * @since 15/12/2016
	 * @review 28/03/2026 - new CI4 version
	 */
	public function cargarModalHazard()
	{
		$data = [];
		$data['information'] = null;

		$idHazard = $this->request->getPost("idHazard");
		$data["idHazard"] = $idHazard;

		$arrParam = array(
			"table" => "param_hazard_activity",
			"order" => "hazard_activity",
			"id" => "x"
		);
		$data['activityList'] = $this->generalModel->get_basic_search($arrParam);

		$arrParam = array(
			"table" => "param_hazard_priority",
			"order" => "priority_description",
			"id" => "x"
		);
		$data['priorityList'] = $this->generalModel->get_basic_search($arrParam);

		if (!empty($idHazard) && $idHazard !== 'x') {
			$arrParam = array(
				"table" => "param_hazard",
				"order" => "id_hazard",
				"column" => "id_hazard",
				"id" => $idHazard
			);
			$data['information'] = $this->generalModel->get_basic_search($arrParam);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\hazard_modal', $data));
	}

	/**
	 * Update hazard
	 * @since 15/12/2016
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function save_hazard()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$msj = $id 
			? "You have updated a Hazard!!" 
			: "You have added a new Hazard!!";

		$data = [];

		if ($this->adminModel->saveHazard($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * job List
	 * @since 15/12/2016
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function job($state)
	{
		if ($state == 'log') {

			//job list
			$data['jobList'] = $this->generalModel->get_job(['state' => 1]);

			$arrParam = array(
				"table" => "user",
				"order" => "id_user",
				"column" => "id_user",
				"id" => "x"
			);
			$data['user'] = $this->generalModel->get_basic_search($arrParam);

			if ($this->request->getPost('jobName') || $this->request->getPost('user') || $this->request->getPost('from')) {

				$data['jobName'] =  $this->request->getPost('jobName');
				$data['user'] =  $this->request->getPost('user');
				$data['from'] =  $this->request->getPost('from');
				$data['to'] =  $this->request->getPost('to');

				//le sumo un dia al dia final para que ingrese ese dia en la consulta
				if ($data['to']) {
					$to = date('Y-m-d', strtotime('+1 day ', strtotime(formatear_fecha($data['to']))));
				} else {
					$to = "";
				}
				if ($data['from']) {
					$from = formatear_fecha($data['from']);
				} else {
					$from = "";
				}

				$arrParam = array(
					"jobId" => $this->request->getPost('jobName'),
					"userId" => $this->request->getPost('user'),
					"from" => $from,
					"to" => $to
				);

				//informacion Work Order
				$data['workOrderInfo'] = $this->adminModel->get_job_log($arrParam);

				$data["view"] = "log_list";
				$this->load->view("layout_calendar", $data);
			} else {
				$data["view"] = 'job_log';
				$this->load->view("layout", $data);
			}
		} else {
			$data['state'] = $state;
			$data['info'] = $this->generalModel->get_job(['state' => $state]);
			$data['dashboardURL'] = $this->session->get("dashboardURL");
			return $this->renderTopOnly('App\Modules\Admin\Views\job', $data);

		}
	}

	/**
	 * Cargo modal - formulario job
	 * @since 15/12/2016
	 * @review 28/03/2026 - new CI4 version
	 */
	public function cargarModalJob()
	{
		$data = [];
		$data['information'] = null;

		$idJob = $this->request->getPost("idJob");
		$data["idJob"] = $idJob;

		//company list
		$arrParam = array(
			"table" => "param_company",
			"order" => "company_name",
			"column" => "company_type",
			"id" => 2
		);
		$data['companyList'] = $this->generalModel->get_basic_search($arrParam);

		if (!empty($idJob) && $idJob !== 'x') {
			$arrParam['idJob'] = $idJob;
			$data['information'] = $this->generalModel->get_job($arrParam);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\job_modal', $data));
	}

	/**
	 * Update job
	 * @since 15/12/2016
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function save_job()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;

		$jobCode = trim($post['jobCode'] ?? '');
		$jobName = trim($post['jobName'] ?? '');
		$jobDescription = $jobCode . " " . $jobName;
		$companyId = $post['company'] ?? null;

		$msj = $id 
			? "You have updated a Job!!" 
			: "You have added a new Job!!";

		// Verificar si ya existe el job_code
		$arrParam = [
			"idJob" => $id,
			"column" => "job_code",
			"value" => $jobCode
		];

		$result_job = $this->generalModel->jobCodeVerify($arrParam);

		if ($result_job) {
			return $this->response->setJSON([
				"status" => "error",
				"message" => "The Job Code already exists."
			]);
		}

		if ($idJobSaved = $this->adminModel->saveJob($post)) {

			//If it is a new JOB, then send text messague to SAFETY USER
			if ($id == '') {
				//revisar si se envia mensaje de texto y a quien se le envia
				$configuracionAlertas = $this->generalModel->get_notifications_access(["idNotification" => ID_NOTIFICATION_NEW_JOB]);
					
				if ($configuracionAlertas) {

					//mensaje de texto
					$mensajeSMS = "NEW JOB APP-Lev West";
					$mensajeSMS .= "\nFor your records, a new Job Code has been created in the system.";
					$mensajeSMS .= "\nJob Code/Name: " . $jobDescription;

					if ($companyId) {
						$company = $this->generalModel->get_basic_search([
																"table" => "param_company",
																"order" => "id_company",
																"column" => "id_company",
																"id" => $companyId
															]);

						$company = $company[0]['company_name'];

						$mensajeSMS .= "\nCompany name: " . $company;

						//foreman company
						$company_foreman = $this->generalModel->get_basic_search([
																	"table" => "param_company_foreman",
																	"order" => "fk_id_param_company",
																	"column" => "fk_id_param_company",
																	"id" => $companyId
																]);

						if ($company_foreman) {
							$company_foreman = $company_foreman[0]['foreman_name'];

							$mensajeSMS .= "\nForeman name: " . $company_foreman;
						}
					}

					$this->sendNotifications($configuracionAlertas, $mensajeSMS);
				}
			}

			//save info FOREMAN
			$nameForeman = $this->request->getPost('foreman');
			if ($nameForeman != '') {
				$this->adminModel->save_foreman($idJobSaved, $post);
			}

			session()->setFlashdata('retornoExito', $msj);
			return $this->response->setJSON([
				"status" => "success"
			]);
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			return $this->response->setJSON([
				"status" => "error",
				"message" => "Database error"
			]);
		}
	}


	public function save_job_borrar()
	{
		header('Content-Type: application/json');
		$data = array();

		$idJob = $this->request->getPost('hddId');
		$jobCode = trim($this->security->xss_clean($this->request->getPost('jobCode')));
		$jobName = trim($this->security->xss_clean($this->request->getPost('jobName')));
		$jobDescription = $jobCode . " " . $jobName;
		$companyId = trim($this->security->xss_clean($this->request->getPost('company')));

		$msj = "You have added a new job!!";
		if ($idJob != '') {
			$msj = "You have updated a Job!!";
		}

		//verificar si ya el job code
		$arrParam = array(
			"idJob" => $idJob,
			"column" => "job_code",
			"value" => $jobCode
		);
		$result_job = $this->generalModel->jobCodeVerify($arrParam);

		if ($result_job) {
			$data["result"] = "error";
			$data["mensaje"] = " Error. The Job Code already exist.";
			$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> The Job Code already exist.');
		} else {
			if ($idJobSaved = $this->adminModel->saveJob()) {

				//If it is a new JOB, then send text messague to SAFETY USER
				if ($idJob == '') {
					//revisar si se envia mensaje de texto y a quien se le envia
					$arrParam = array("idNotification" => ID_NOTIFICATION_NEW_JOB);
					$configuracionAlertas = $this->generalModel->get_notifications_access($arrParam);

					if ($configuracionAlertas) {

						//mensaje de texto
						$mensajeSMS = "NEW JOB APP-Lev West";
						$mensajeSMS .= "\nFor your records, a new Job Code has been created in the system.";
						$mensajeSMS .= "\nJob Code/Name: " . $jobDescription;

						if ($companyId) {
							$arrParam = array(
								"table" => "param_company",
								"order" => "id_company",
								"column" => "id_company",
								"id" => $companyId
							);
							$company = $this->generalModel->get_basic_search($arrParam); //company list

							$company = $company[0]['company_name'];

							$mensajeSMS .= "\nCompany name: " . $company;

							//foreman company
							$arrParam = array(
								"table" => "param_company_foreman",
								"order" => "fk_id_param_company",
								"column" => "fk_id_param_company",
								"id" => $companyId
							);
							$company_foreman = $this->generalModel->get_basic_search($arrParam); //company list

							if ($company_foreman) {
								$company_foreman = $company_foreman[0]['foreman_name'];

								$mensajeSMS .= "\nForeman name: " . $company_foreman;
							}
						}

						$this->db->select("L.*, CONCAT(first_name, ' ', last_name) name, J.job_description");
						$this->db->join('user U', 'U.id_user = L.created_by', 'INNER');
						$this->db->join('param_jobs j', 'L.type_id = j.id_job', 'LEFT');
						$this->db->order_by('L.id', 'asc');
						$this->db->where('L.type_id', $idJobSaved);
						$this->db->where('L.token', 'insert');
						$query = $this->db->get('logger L');
						$data = $query->result_array();

						$mensajeSMS .= "\nCreate for: " . $data[0]['name'];

						$this->sendNotifications($configuracionAlertas, $mensajeSMS);
					}
				}

				//save info FOREMAN
				$nameForeman = $this->request->getPost('foreman');
				if ($nameForeman != '') {
					$this->adminModel->save_foreman($idJobSaved);
				}

				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}
		}

		echo json_encode($data);
	}

	/**
	 * vehicle List
	 * @since 15/12/2016
	 * @review 5/5/2017
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function vehicle($companyType, $vehicleType = 1, $vehicleState = 1)
	{
		$data = [];

		$data['companyType'] = $companyType;
		$data['vehicleType'] = $vehicleType;
		$data['vehicleState'] = $vehicleState;
		$data['title'] = $companyType == 1 ? "Lev West" : "RENTALS";

		$arrParam = [
			"companyType" => $companyType,
			"vehicleState" => $vehicleState
		];

		if ($vehicleState == 1) {
			$arrParam['vehicleType'] = $vehicleType;
		}

		$data['info'] = $this->adminModel->get_vehicle_info_by($arrParam);
		return $this->render('App\Modules\Admin\Views\vehicle', $data);
	}

	/**
	 * Cargo modal - formulario vehicle
	 * @since 15/12/2016
	 * @review 03/04/2026 - new CI4 version
	 * 
	 */
	public function cargarModalVehicle()
	{
		$data = [];
		$data['information'] = null;

		$idVehicle = $this->request->getPost("idVehicle");
		//como se coloca un ID diferente para que no entre en conflicto con los otros modales, toca sacar el ID
		$porciones = explode("-", $idVehicle);

		$data["companyType"] = $porciones[0];
		$data["idVehicle"] = $porciones[1];

		$arrParam = [
			"table" => "param_company",
			"order" => "company_name",
			"column" => "company_type",
			"id" => 2
		];
		$data['company'] = $this->generalModel->get_basic_search($arrParam); //company list

		//buscar la lista de tipo de vehiculo
		$arrParam = [
			"table" => "param_vehicle_type_2",
			"order" => "type_2",
			"column" => "show_vehicle",
			"id" => 1
		];
		$data['vehicleType'] = $this->generalModel->get_basic_search($arrParam); //vehicleType list

		if ($data["idVehicle"] != 'x') {
			$arrParam = [
				"idVehicle" => $data["idVehicle"]
			];
			$data['information'] = $this->adminModel->get_vehicle_info_by($arrParam);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\vehicle_modal', $data));
	}

	/**
	 * Update vehicle
	 * @since 15/12/2016
	 * @review 27/12/2016
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function save_vehicle()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$idCompany = $post['company'] ?? null;
		$data = [];
		$data["compannyType"] = $idCompany == 1 ? 1 : 2; //1:Lev West; 2:Subcontractor

		$msj = $id 
			? "You have updated a Vehicle!!" 
			: "You have added a new Vehicle!!";

		if ($idVehicleSaved = $this->adminModel->saveVehicle($post)) {
			if (!$id) { //si es un registro nuevo entonces guardo el historial de cambio de aceite
				$status = 0; //primer registro
				$this->adminModel->saveVehicleNextOilChange($idVehicleSaved, $status, $post);
			}
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * photo
	 * @review 03/04/2026 - new CI4 version
	 */
	public function photo(int $idVehicle)
	{
		if (empty($idVehicle)) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid vehicle');
		}

		$arrParam = [
			"idVehicle" => $idVehicle
		];
		$vehicleInfo = $this->adminModel->get_vehicle_info_by($arrParam);

		if (empty($vehicleInfo)) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException('Vehicle not found');
		}

		$data = [
			'vehicleInfo' => $vehicleInfo,
			'idVehicle'   => $idVehicle,
			'error'       => session()->getFlashdata('error')
		];

		return $this->render('App\Modules\Admin\Views\vehicle_photo', $data);
	}

	/**
	 * FUNCIÓN PARA SUBIR LA IMAGEN 
	 * @param int vistaRegreso -> para saber si es de Lev West o RENTADA
	 * @review 03/04/2026 - new CI4 version
	 */
	public function do_upload($type, $vistaRegreso = null)
	{
		$idVehicle = $this->request->getPost('hddId');

		$file = $this->request->getFile('userfile');

		if (!$file->isValid()) {
			session()->setFlashdata('retornoError', $file->getErrorString());
			return redirect()->to(base_url('admin/' . ($vistaRegreso ?? 'vehicle')));
		}

		// Generar nombre seguro
		$newName = $idVehicle . '_' . $type . '.' . $file->getExtension();

		// Ruta absoluta
		$path = FCPATH . 'images/vehicle/';

		// Mover archivo
		$file->move($path, $newName, true); // true = overwrite

		// Crear thumbnail si es photo
		if ($type === 'photo') {
			$this->_create_thumbnail($newName);
			$pathDb = 'images/vehicle/thumbs/' . $newName;
		} else {
			$pathDb = 'images/vehicle/' . $newName;
		}

		// Actualizar DB
		$arrParam = [
			"table" => "param_vehicle",
			"primaryKey" => "id_vehicle",
			"id" => $idVehicle,
			"column" => $type,
			"value" => $pathDb
		];

		if ($this->generalModel->updateRecord($arrParam)) {
			session()->setFlashdata('retornoExito', 'Good job, you have uploaded the photo.');
		} else {
			session()->setFlashdata('retornoError', 'Ask for help.');
		}

		// Redirigir a la vista de regreso
		return redirect()->to(base_url('admin/photo/' . ($idVehicle ?? 'vehicle')));
	}

	//FUNCIÓN PARA CREAR LA MINIATURA
	private function _create_thumbnail(string $filename)
	{
		// Ruta absoluta de la imagen original
		$sourcePath = FCPATH . 'images/vehicle/' . $filename;

		// Ruta donde se guardará el thumbnail
		$thumbPath = FCPATH . 'images/vehicle/thumbs/' . $filename;

		// Crear thumbnail usando el servicio de imágenes de CI4
		\Config\Services::image()
			->withFile($sourcePath)
			->fit(150, 150, 'center') // recorta y mantiene proporción
			->save($thumbPath);
	}

	/**
	 * qr_code
	 */
	public function qr_code($idVehicle, $error = '')
	{
		if (empty($idVehicle)) {
			show_error('ERROR!!! - You are in the wrong place.');
		}

		//busco datos del vehiculo
		$arrParam = array(
			"idVehicle" => $idVehicle
		);
		$data['vehicleInfo'] = $this->adminModel->get_vehicle_info_by($arrParam);

		$data['error'] = $error; //se usa para mostrar los errores al cargar la imagen 
		$data['idVehicle'] = $idVehicle;
		$data["view"] = 'vehicle_qr_code';
		$this->load->view("layout", $data);
	}

	/**
	 * Employee Type List
	 * @since 4/2/2017
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function employeeType()
	{
		$arrParam = [
			"table" => "param_employee_type",
			"order" => "employee_type",
			"id" => "x"
		];
		$data['info'] = $this->generalModel->get_basic_search($arrParam);
		return $this->render('App\Modules\Admin\Views\employee_type', $data);
	}

	/**
	 * Cargo modal - formulario employee type
	 * @since 4/2/2017
	 * @review 03/04/2026 - new CI4 version
	 */
	public function cargarModalEmployeeType()
	{
		$data = [];
		$data['information'] = null;

		$idEmployeeType = $this->request->getPost("idEmployeeType");
		$data["idEmployeeType"] = $idEmployeeType;

		if (!empty($idEmployeeType) && $idEmployeeType !== 'x') {
			$arrParam = array(
				"table" => "param_employee_type",
				"order" => "id_employee_type",
				"column" => "id_employee_type",
				"id" => $idEmployeeType
			);
			$data['information'] = $this->generalModel->get_basic_search($arrParam);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\employee_type_modal', $data));
	}

	/**
	 * Update employee type
	 * @since 4/2/2017
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function save_employee_type()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$msj = $id 
			? "You have updated an Employee Type!!" 
			: "You have added a new Employee Type!!";

		$data = [];

		if ($this->adminModel->saveEmployeeType($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Hazard Activity List
	 * @since 5/2/2017
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function hazardActivity()
	{
		$arrParam = [
			"table" => "param_hazard_activity",
			"order" => "hazard_activity",
			"id" => "x"
		];
		$data['info'] = $this->generalModel->get_basic_search($arrParam);
		return $this->render('App\Modules\Admin\Views\hazard_activity', $data);
	}

	/**
	 * Cargo modal - formulario hazard Activity
	 * @since 5/2/2017
	 * @review 03/04/2026 - new CI4 version
	 */
	public function cargarModalHazardActivity()
	{
		$data = [];
		$data['information'] = null;

		$idHazardActivity = $this->request->getPost("idHazardActivity");
		$data["idHazardActivity"] = $idHazardActivity;

		if (!empty($idHazardActivity) && $idHazardActivity !== 'x') {
			$arrParam = array(
				"table" => "param_hazard_activity",
				"order" => "id_hazard_activity",
				"column" => "id_hazard_activity",
				"id" => $idHazardActivity
			);
			$data['information'] = $this->generalModel->get_basic_search($arrParam);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\hazard_activity_modal', $data));
	}

	/**
	 * Update Hazard Activity
	 * @since 5/2/2017
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function save_hazard_activity()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$msj = $id 
			? "You have updated an Activity!!" 
			: "You have added a new Activity!!";

		$data = [];

		if ($this->adminModel->saveHazardActivity($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Change password
	 * @since 15/4/2017
	 * @author BMOTTAG
	 * @review 21/03/2026 - new CI4 version
	 */
	public function change_password($idUser)
	{
		if (empty($idUser)) {
			show_error('ERROR!!! - You are in the wrong place. The ID USER is missing.');
		}

		$arrParam = array(
			"table" => "user",
			"order" => "id_user",
			"column" => "id_user",
			"id" => $idUser
		);
		$data['information'] = $this->generalModel->get_basic_search($arrParam);

		return $this->render('App\Modules\Admin\Views\form_password', $data);
	}

	/**
	 * Update user´s password
	 * @review 27/03/2026 - new CI4 version
	 */
	public function update_password()
	{
		$data = [];
		$data["titulo"] = "<i class='fa fa-unlock fa-fw'></i>CHANGE PASSWORD";
		$data['linkBack'] = "admin/employee/1";

		$newPassword = $this->request->getPost("inputPassword");
		$confirm     = $this->request->getPost("inputConfirm");
		$user        = $this->request->getPost("hddUser");
		$idUser = $this->request->getPost("hddId");

		// Validación básica
		if (!$newPassword || !$confirm) {
			$data["msj"] = "Password is required.";
			$data["clase"] = "alert-danger";
			return $this->render('App\Views\template\answer', $data);
		}

		if ($newPassword !== $confirm) {
			$data["msj"] = "Passwords do not match.";
			$data["clase"] = "alert-danger";
			return $this->render('App\Views\template\answer', $data);
		}

		// Enviar al modelo
		if ($this->adminModel->updatePassword($idUser, $newPassword)) {
			$data["msj"] = "Password updated successfully.<br><strong>User:</strong> " . $user;
			$data["clase"] = "alert-success";
		} else {
			$data["msj"] = "<strong>Error!</strong> Please try again.";
			$data["clase"] = "alert-danger";
		}

		return $this->render('App\Views\template\answer', $data);
	}

	/**
	 * Cargo modal- formulario OIL CHANGE
	 * @since 13/1/2019
	 */
	public function cargarModalOilChange()
	{
		header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos

		//busco datos del vehiculo
		$arrParam['idVehicle'] = $this->request->getPost("idVehicle");
		$data['vehicleInfo'] = $this->generalModel->get_vehicle_by($arrParam);

		$tipo = $data['vehicleInfo'][0]['type_level_2'];

		//si es la sweeper o hydrovac muestro un formulario diferente
		if ($tipo == 15) {
			$vista = "modal_oil_change_sweeper";
		} elseif ($tipo == 16) {
			$vista = "modal_oil_change_hydrovac";
		} else {
			$vista = "modal_oil_change_normal";
		}

		$view = "App\Modules\Admin/Views/" . $vista;
		return view($view, $data);
	}

	/**
	 * Certificate List
	 * @since 14/1/2022
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function certificate()
	{
		$params = [];
		$data['certificateList'] = $this->generalModel->get_certificate_list($params);
		$idCertificate = $this->request->getPost('idCertificate');
		$date = $this->request->getPost('date');
		if (!empty($idCertificate)) {
			$params['idCertificate'] = $idCertificate;
		}
		if (!empty($date)) {
			$params['date'] = $date;
		}
		$rows = $this->generalModel->get_certificates_with_users($params);

		$data['info'] = [];
		foreach ($rows as $row) {

			$id = $row['id_certificate'];

			if (!isset($data['info'][$id])) {
				$data['info'][$id] = [
					'id_certificate' => $row['id_certificate'],
					'certificate' => $row['certificate'],
					'certificate_description' => $row['certificate_description'],
					'employees' => []
				];
			}

			if (!empty($row['id_user'])) {
				$data['info'][$id]['employees'][] = [
					'first_name' => $row['first_name'],
					'last_name' => $row['last_name'],
					'date_through' => $row['date_through']
				];
			}
		}

		return $this->render('App\Modules\Admin\Views\certificate', $data);
	}

	/**
	 * Cargo modal - Certificados
	 * @since 14/1/2022
	 * @review 28/03/2026 - new CI4 version
	 */
	public function cargarModalCertificate()
	{
		$data = [];
		$data['information'] = null;

		$idCertificate = $this->request->getPost("idCertificate");
		$data["idCertificate"] = $idCertificate;

		if (!empty($idCertificate) && $idCertificate !== 'x') {
			$params['idCertificate'] = $idCertificate;
			$data['information'] = $this->generalModel->get_certificate_list($params);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\certificate_modal', $data));
	}

	/**
	 * Guardar certificados
	 * @since 14/1/2022
	 * @author BMOTTAG
	 * @review 28/03/2026 - new CI4 version
	 */
	public function save_certificate()
	{
		$post = $this->request->getPost();

		$idCertificate = $post['hddId'] ?? null;
		$msj = $idCertificate 
			? "You have updated a Certificate!!" 
			: "You have added a new Certificate!!";

		$data = [];

		if ($this->adminModel->saveCertificate($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * User Certificates
	 * @param int $idEmployee
	 * @since 15/1/2022
	 * @review 21/03/2026 - new CI4 version
	 */
	public function userCertificates($idUser)
	{
		if (empty($idUser)) {
			show_error('ERROR!!! - You are in the wrong place.');
		}

		//busco datos del vehiculo
		$arrParam['idUser'] = $idUser;
		$data = [];
		$data['UserInfo'] = $this->generalModel->get_user($arrParam);

		$data['info'] = $this->generalModel->get_user_certificates($arrParam);

		return $this->render('App\Modules\Admin\Views\employee_certificates', $data);
	}

	/**
	 * Cargo modal- Formulario de certificados
	 * @since 15/1/2022
	 */
	public function cargarModalUserCertificate()
	{
		header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos

		$data["idEmployee"] = $this->request->getPost("idEmployee");

		$arrParam = array(
			"table" => "param_certificates ",
			"order" => "certificate",
			"id" => "x"
		);
		$data['certificateList'] = $this->generalModel->get_basic_search($arrParam);

		return view('App\Modules\Admin\Views\employee_certificates_modal', $data);
	}

	/**
	 * Save employee certificate
	 * @since 15/1/2022
	 * @author BMOTTAG
	 */
	public function save_employee_certificate()
	{
		$post = $this->request->getPost();

		$data = [];

		$idEmployee = $post['hddidEmployee'] ?? null;
		$idEmployeeCertificate = $post['hddidEmployeeCertificate'] ?? null;
		$data["idRecord"] = $idEmployee;

		$msj = "You have added a new Certificate!!";

		$certificate_exist = false;

		// Validar si ya existe
		if (empty($idEmployeeCertificate)) {
			$certificate_exist = $this->generalModel->get_user_certificates([
				"idUser" => $idEmployee,
				"idCertificate" => $post['certificate']
			]);
		}

		if ($certificate_exist) {

			$data["result"] = "error";
			$data["mensaje"] = "Error. The Employee already has the certificate.";

			session()->setFlashdata(
				'retornoError',
				'<strong>Error!!!</strong> The certificate already exists.'
			);

		} else {

			if ($this->adminModel->saveEmployeeCertificate($post)) {
				$data["result"] = true;
				session()->setFlashdata('retornoExito', $msj);
			} else {

				$data["result"] = "error";
				$data["mensaje"] = "Error saving data";

				session()->setFlashdata(
					'retornoError',
					'<strong>Error!!!</strong> Ask for help'
				);
			}
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Delete Employee Certificatte
	 * @since 8/7/2018
	 */
	public function delete_user_certificate()
	{
		$post = $this->request->getPost();

		$data = [];

		$idUserCertificate = $post['identificador'] ?? null;

		// Buscar certificado
		$certificate = $this->generalModel->get_user_certificates([
			'idUserCertificate' => $idUserCertificate
		]);

		if (!$certificate) {
			$data["result"] = "error";
			$data["mensaje"] = "Record not found";

			return $this->response->setJSON($data);
		}

		$idUser = $certificate[0]['fk_id_user'];
		$data["idRecord"] = $idUser;

		// Eliminar registro
		$arrParam = [
			"table" => "user_certificates",
			"primaryKey" => "id_user_certificate",
			"id" => $idUserCertificate
		];

		if ($this->generalModel->deleteRecord($arrParam)) {

			$data["result"] = true;
			$data["mensaje"] = "You have deleted one record.";

			session()->setFlashdata(
				'retornoExito',
				'You have deleted one record'
			);

		} else {

			$data["result"] = "error";
			$data["mensaje"] = "Error!!! Ask for help.";

			session()->setFlashdata(
				'retornoError',
				'<strong>Error!!!</strong> Ask for help'
			);
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Update User Certificate
	 * @since 15/1/2022
	 * @author BMOTTAG
	 */
	public function update_user_certificate()
	{
		$post = $this->request->getPost();

		$idUserCertificate = $post['hddidEmployeeCertificate'] ?? null;

		// Buscar info del certificado
		$certificate_exist = $this->generalModel->get_user_certificates([
			'idUserCertificate' => $idUserCertificate
		]);

		if (!$certificate_exist) {
			session()->setFlashdata(
				'retornoError',
				'<strong>Error!!!</strong> Record not found'
			);

			return redirect()->to(base_url('admin/employee/1'));
		}

		$idUser = $certificate_exist[0]['fk_id_user'];

		// Guardar (UPDATE)
		if ($this->adminModel->saveEmployeeCertificate($post)) {

			session()->setFlashdata(
				'retornoExito',
				"You have updated the Date!!"
			);

		} else {

			session()->setFlashdata(
				'retornoError',
				'<strong>Error!!!</strong> Ask for help'
			);
		}
		return redirect()->to(base_url('admin/userCertificates/' . $idUser));
	}

	/**
	 * Alert List
	 * @since 23/01/2022
	 * @review 22/12/2022
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function notifications()
	{
		$arrParam = [];
		$data['info'] = $this->generalModel->get_notifications_access_view($arrParam);
		return $this->render('App\Modules\Admin\Views\notifications', $data);
	}

	/**
	 * Cargo modal - formulario configuracion de alertas
	 * @since 23/01/2022
	 * @review 03/04/2026 - new CI4 version
	 */
	public function cargarModalNotification()
	{
		$idNotificationAccess = $this->request->getPost('idNotificationAccess') ?? null;

		$data = [
			'information' => null,
			'idNotificationAccess' => $idNotificationAccess,
			'workersList' => $this->generalModel->get_user([
				"state" => 1
			]),
			'notificationsList' => $this->generalModel->getAvailableNotifications()
		];

		if (!empty($idNotificationAccess) && $idNotificationAccess !== 'x') {
			$data['information'] = $this->generalModel
				->get_notifications_access_view([
					"idNotificationAccess" => $idNotificationAccess
				]);
		}

		return $this->response
			->setContentType('text/html')
			->setBody(view('App\Modules\Admin\Views\notifications_modal', $data));
	}

	/**
	 * Save notifications access settings
	 * @since 23/01/2022
	 * @review 22/12/2022
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function save_notifications()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$msj = $id 
			? "You have updated a Notification Access!!" 
			: "You have added a new Notification Access!!";

		$data = [];

		if ($this->adminModel->saveNotification($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * CRON
	 * Verificar si esta proximo a vencerse los certificados para los empleados activos
	 * El CRON se corre todos los lunes
	 * @since 23/1/2022
	 * @review 13/05/2026 - new CI4 version
	 */
	public function certifications_check()
	{
		$information = $this->generalModel->get_user_certificates(["state" => 1, "expires" => 1]);

		if ($information) {
			$configuracionAlertas = $this->generalModel->get_notifications_access(["idNotification" => ID_NOTIFICATION_CERTIFICATION]);

			if ($configuracionAlertas) {
				$filtroFecha  = strtotime(date('Y-m-d'));
				$certificates = [];

				foreach ($information as $lista) :
					$fechaVencimiento = strtotime($lista['date_through']);
					$diferencia       = $fechaVencimiento - $filtroFecha;
					$updateAlert      = false;

					// < 90 days and first alert
					if ($diferencia < 8035200 && $lista['alerts_sent'] == 0) {
						$updateAlert = true;
					// <= 60 days and second alert
					} elseif ($diferencia <= 5356800 && $lista['alerts_sent'] == 1) {
						$updateAlert = true;
					// <= 30 days and third alert
					} elseif ($diferencia <= 2678400 && $diferencia >= 0 && $lista['alerts_sent'] == 2) {
						$updateAlert = true;
					}

					if ($updateAlert) {
						$certificates[] = [
							'first_name'  => $lista['first_name'],
							'last_name'   => $lista['last_name'],
							'certificate' => $lista['certificate'],
							'date_through' => $lista['date_through'],
						];

						$this->generalModel->updateRecord([
							"table"      => "user_certificates",
							"primaryKey" => "id_user_certificate",
							"id"         => $lista['id_user_certificate'],
							"column"     => "alerts_sent",
							"value"      => $lista['alerts_sent'] + 1,
						]);
					}
				endforeach;

				$emailService = new \App\Libraries\EmailService();
				$smsService   = new \App\Libraries\SmsService();

				foreach ($configuracionAlertas as $envioAlerta) :
					if ($envioAlerta['email'] && $certificates) {
						$result = $emailService->sendTemplate(
							$envioAlerta['email'],
							'Certificate Overdue',
							'emails/certificates_overdue',
							[
								'name'         => $envioAlerta['name_email'],
								'certificates' => $certificates,
							]
						);
						if ($result !== true) {
							log_message('error', 'certifications_check email error: ' . print_r($result, true));
						}
					}

					if ($envioAlerta['movil'] && $certificates) {
						$smsMensaje  = "APP Lev West - Employees Certificates";
						$smsMensaje .= "\nThere are employees with certificates about to expire. Go to Settings - Employee and check.";
						$smsService->send('+1' . $envioAlerta['movil'], $smsMensaje);
					}
				endforeach;
			}
		}
		return true;
	}

	/**
	 * Employee Rate List
	 * @since 16/2/2022
	 * @author BMOTTAG
	 * @review 01/04/2026 - new CI4 version
	 */
	public function employeeSettings()
	{
		$data['dashboardURL'] = session()->get("dashboardURL");
		$data['info'] = $this->generalModel->get_user(["filtroState" => TRUE]);
		return $this->renderTopOnly('App\Modules\Admin\Views\employee_settings', $data);
	}

	/**
	 * Update the employee rate of each field
	 * @since 16/2/2022
	 * @author BMOTTAG
	 * @review 01/04/2026 - new CI4 version
	 */
	public function update_employee_rate()
	{
		$post = $this->request->getPost();
		if ($this->adminModel->updateEmployeeRate($post['form'])) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', "You have updated the Employee Rate List!!");
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('admin/employeeSettings'));
	}

	/**
	 * Form CkeckIN check
	 * Used by cron
	 * @since 4/06/2022
	 * @author BMOTTAG
	 */
	public function checkin_check()
	{
		$this->load->library('encrypt');
		require 'vendor/Twilio/autoload.php';

		//busco datos parametricos twilio
		$arrParam = array(
			"table" => "parametric",
			"order" => "id_parametric",
			"id" => "x"
		);
		$parametric = $this->generalModel->get_basic_search($arrParam);
		$twilioSID = $this->encrypt->decode($parametric[3]["value"]);
		$twilioToken = $this->encrypt->decode($parametric[4]["value"]);
		$twilioPhone = $parametric[5]["value"];

		$client = new Twilio\Rest\Client($twilioSID, $twilioToken);

		$arrParam = array(
			"today" => date('Y-m-d'),
			"checkout" => true
		);
		$checkinList = $this->generalModel->get_checkin($arrParam);

		if ($checkinList) {
			$x = 0;
			foreach ($checkinList as $data) :
				$x++;
				//send sms to the employee
				$mensaje = "Lev West Sign-Out";
				$mensaje .= "\n" . $data['worker_name'];
				$mensaje .= "\n";
				$mensaje .= "This message is to remind you that you still ON the working list at the work site, it is possible that you forgot to sign out.";
				$mensaje .= "\nUse the following link to Sign-Out.";
				$mensaje .= "\n";
				$mensaje .= "\n";
				$mensaje .= base_url("external/checkin/" . $data['fk_id_job'] . "/" . $data['id_checkin']);

				$to = '+1' . $data['worker_movil'];
				$client->messages->create(
					$to,
					array(
						'from' => $twilioPhone,
						'body' => $mensaje
					)
				);
			endforeach;
			echo $x . " messages have been sent to people that haven't Check-Out";
		} else {
			echo "Everybody have the Check-Out done.";
		}
	}

	/**
	 * Employee Bank Time List
	 * @since 9/9/2022
	 * @author BMOTTAG
	 */
	public function employeBankTime($idUser)
	{
		$data["idUser"] = $idUser;

		$arrParam = array("idUser" => $idUser);
		$data['info'] = $this->generalModel->get_bank_time($arrParam);

		$data["view"] = 'employee_bank_time';
		$this->load->view("layout_calendar", $data);
	}

	/**
	 * Cargo modal - formulario Add Balance to Bank time
	 * @since 9/9/2022
	 */
	public function cargarModalBankTimeBalance()
	{
		header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos

		$data['information'] = FALSE;
		$data["idEmployee"] = $this->request->getPost("idEmployee");

		$arrParam = array(
			"idUser" => $data["idEmployee"]
		);
		$data['information'] = $this->generalModel->get_user($arrParam);

		return view('App\Modules\Admin\Views\employee_bank_time_modal', $data);
	}

	/**
	 * Insert bank time
	 * @since 9/9/2022
	 * @author BMOTTAG
	 */
	public function save_bank_time_balance()
	{
		header('Content-Type: application/json');
		$data = array();

		$data["idEmployee"] = $this->request->getPost('hddId');
		$bankTimeAdd = $this->request->getPost('time');
		$msj = "You have added Balance to Bank Time!!";

		$arrParam = array(
			"idUser" => $data["idEmployee"],
			"limit" => 1
		);
		$infoBankTime = $this->generalModel->get_bank_time($arrParam);

		$bankNewBalance = $infoBankTime ? $infoBankTime[0]["balance"] + $bankTimeAdd : $bankTimeAdd;

		$arrParamBankTime = array(
			"idPeriod" => 0,
			"idEmployee" => $data["idEmployee"],
			"bankTimeAdd" => $bankTimeAdd,
			"bankTimeSubtract" => 0,
			"bankNewBalance" => $bankNewBalance,
			"observation" => "Bank Time Added"
		);
		if ($this->generalModel->saveBankTimeBalance($arrParamBankTime)) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		echo json_encode($data);
	}

	/**
	 * Attachments List
	 * @since 23/06/2023
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function attachments($status)
	{
		$data['status'] = $status;
		$arrParam = [
			"status" => $status
		];
		$data['info'] = $this->adminModel->get_attachments($arrParam);
		return $this->render('App\Modules\Admin\Views\attachment', $data);
	}

	/**
	 * Cargo modal - formulario company
	 * @since 23/06/2023
	 * @review 03/04/2026 - new CI4 version
	 */
	public function cargarModalAttachments()
	{
		$data = [];
		$data['information'] = null;
		$data['informationAttachments'] = null;

		$idAttachment = $this->request->getPost("idAttachment");
		$data["idAttachment"] = $idAttachment;

		$data['equipmentType'] = $this->generalModel->equipmentByTypeList();

		if (!empty($idAttachment) && $idAttachment !== 'x') {
			$arrParam = [
				"idAttachment" => $data["idAttachment"]
			];
			$data['information'] = $this->adminModel->get_attachments($arrParam);
			$data['informationAttachments'] = $this->adminModel->get_attachments_equipment($arrParam);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\attachment_modal', $data));
	}

	/**
	 * Update Attachments
	 * @since 23/06/2023
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function save_attachments()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$msj = $id 
			? "You have updated an Attachment!!" 
			: "You have added a new Attachment!!";

		$data = [];

		if ($idAttachment = $this->adminModel->saveAttachment($post)) {
			$equipment = $this->request->getPost('equipment'); 
			$this->adminModel->add_equipment_attachement($idAttachment, $equipment);
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Update Attachments
	 * @since 23/06/2023
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function update_status()
	{
		$post = $this->request->getPost();

		$idAttachment = $post['attachmentId'] ?? null;
		$status = $post['status'] ?? null;
		$value = $status == "active" ? "inactive" : "active";

		$arrParam = [
			"table" => "param_attachments",
			"primaryKey" => "id_attachment",
			"id" => $idAttachment,
			"column" => "attachment_status",
			"value" => $value
		];

		if ($this->generalModel->updateRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', "You have changed the Attachment status!!");
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Equipment list
	 * @since 24/6/2023
	 * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function equipmentList()
	{
		$type = $this->request->getPost('type');
		$idAttachment = $this->request->getPost('idAttachment');

		// Lista de equipos
		$lista = $this->generalModel->get_vehicle_by([
			"vehicleType" => $type,
			"vehicleState" => 1
		]);

		// Inicializar siempre
		$arrayInformationAttachments = [];

		if (!empty($idAttachment)) {
			$arrayInformationAttachments = $this->adminModel
				->get_attachments_equipment([
					"idAttachment" => $idAttachment,
					"relation" => true
				]);
		}

		// Convertir a array plano de IDs (MEJOR PERFORMANCE)
		$selectedIds = [];

		if (!empty($arrayInformationAttachments)) {
			foreach ($arrayInformationAttachments as $row) {
				$selectedIds[] = $row['fk_id_equipment']; // 🔥 clave correcta
			}
		}

		// Construir HTML
		$html = "<option value=''>Select...</option>";

		if (!empty($lista)) {
			foreach ($lista as $fila) {

				$selected = in_array($fila['id_vehicle'], $selectedIds)
					? " selected"
					: "";

				$html .= "<option value='{$fila["id_vehicle"]}'{$selected}>"
					. $fila["unit_number"] . " -----> " . $fila["description"]
					. "</option>";
			}
		}

		return $this->response
			->setContentType('text/html')
			->setBody($html);
	}

	/**
	 * Notifications
	 * @author BMOTTAG
	 * @since  14/01/2025
	 */
	public function sendNotifications($configuracionAlertas, $mensajeSMS)
	{
		$numbers = [];

		foreach ($configuracionAlertas as $envioAlerta) {
			if (!empty($envioAlerta['movil'])) {
				$numbers[] = '+1' . $envioAlerta['movil'];
			}
		}

		if (!empty($numbers)) {
			$smsService = new \App\Libraries\SmsService();
			$smsService->sendBulk($numbers, $mensajeSMS);
		}

		return true;
	}

	/**
	 * Tags List
	 * @since 24/04/2026
	 * @author BMOTTAG
	 */
	public function tags()
	{
		$data['info'] = $this->adminModel->get_tags([]);

		return $this->render('App\Modules\Admin\Views\tag', $data);
	}

	/**
	 * Cargo modal - formulario Tags
	 * @since 24/04/2026
	 */
	public function cargarModalTag()
	{
		$data = [];
		$data['information'] = null;

		$idTag = $this->request->getPost("idTag");
		$data["idTag"] = $idTag;

		$data['jobList'] = $this->generalModel->get_job(['state' => 1]);

		if (!empty($idTag) && $idTag !== 'x') {
			$data['information'] = $this->adminModel->get_tags(['idTag' => $data["idTag"]]);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Admin\Views\tag_modal', $data));
	}

	/**
	 * Update Tag
	 * @since 24/04/2026
	 * @author BMOTTAG
	 */
	public function save_tag()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$msj = $id 
			? "You have updated a Tag!!" 
			: "You have added a new Tag!!";

		$data = [];

		if ($this->adminModel->saveTag($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

}
