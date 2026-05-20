<?php
namespace App\Modules\Safety\Controllers;

use App\Controllers\BaseController;
use App\Modules\Safety\Models\SafetyModel;
use App\Models\GeneralModel;

class Safety extends BaseController
{
    protected $safetyModel;
    protected $generalModel;
	protected $helpers = ['form'];
    
    public function __construct()
    {
        $this->safetyModel   = new SafetyModel();
        $this->generalModel   = new GeneralModel();
    }

	/**
	 * Form Add Safety
     * @since 13/4/2021
     * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function add_safety($idJob, $idSafety = 'x')
	{
		$data = [];
		$data['information'] = FALSE;
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['hazards'] = $this->generalModel->get_job_hazards($idJob);
		//si envio el idSafety, entonces busco la informacion 
		if ($idSafety != 'x') {
			$data['information'] = $this->safetyModel->get_safety_by_id($idSafety);//info safety
		}			
		return $this->render('App\Modules\Safety\Views\form_add_safety', $data);
	}

	/**
	 * Save safety
     * @since 14/4/2021
     * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function save_safety()
	{
		$post = $this->request->getPost();
		$data = [];
		if ($idSafety = $this->safetyModel->add_safety($post)) {
			$data["idSafety"] = $idSafety;
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have saved your FLHA record, do not forget to add Hazards, Workers and signatures.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Form Upload safety info
     * @since 15/4/2021
     * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function upload_info_safety($id = 'x')
	{
		$data = [];
		$data['information'] = FALSE;
		$data['safetyClose'] = FALSE;	
		if ($id != 'x') 
		{
			$data['information'] = $this->safetyModel->get_safety_by_id($id);//info safety
			$data['safetyHazard'] = $this->safetyModel->get_safety_hazard($id);//safety_hazard list
			//consultar si esta cerrado
			if($data['information'][0]['state'] == 2){
				$data['safetyClose'] = TRUE;
				return $this->render('App\Modules\Safety\Views\view_safety', $data);
			}
		}		
		return $this->render('App\Modules\Safety\Views\form_upload_info_safety', $data);
	}

	/**
	 * Form Add Hazards to FLHA
	 * Muestre lista de Hazards por trabajo y los que estan asignados al FLHA estan con check
     * @since 16/5/2019
     * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function add_hazards_flha($idJob, $idSafety)
	{
		if (empty($idJob) || empty($idSafety)) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid IDs');
		}

		$activityList = $this->safetyModel->get_activity_list_by_job($idJob);

		// 🔥 SOLO UNA QUERY
		$selected = array_column(
			$this->safetyModel->get_selected_hazards($idSafety),
			'fk_id_hazard'
		);

		if (!empty($activityList)) {
			foreach ($activityList as &$activity) {

				$hazards = $this->safetyModel
					->get_hazard_list_by_job($activity['id_hazard_activity'], $idJob);

				if (!empty($hazards)) {
					foreach ($hazards as &$hazard) {

						// ✅ ahora es solo memoria (rápido)
						$hazard['found'] = in_array($hazard['id_hazard'], $selected);
					}
				}

				$activity['hazards'] = $hazards;
			}
		}

		$data = [
			'activityList' => $activityList,
			'idJob' => $idJob,
			'idSafety' => $idSafety
		];

		return $this->render('App\Modules\Safety\Views\form_add_hazards_flha', $data);
	}

	/**
	 * Save hazards
     * @since 06/12/2016
	 * @review 10/12/2016
     * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function save_safety_hazards()
	{			
		$post = $this->request->getPost();
		$idSafety = $post['hddId'];
		$data = [];
		if ($this->safetyModel->add_safety_hazard($post)) {
			$data["idSafety"] = $idSafety;
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have added Hazards.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
    }

	/**
	 * Form Upload workers to safety
     * @since 15/4/2021
     * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function upload_workers($idSafety = 'x')
	{
		$data = [];
		$data['safetyClose'] = FALSE;
		$data['information'] = $this->safetyModel->get_safety_by_id($idSafety);//info safety
		$arrParam = [
			"table" => "param_company",
			"order" => "company_name",
			"column" => "company_type",
			"id" => 2
		];
		$data['companyList'] = $this->generalModel->get_basic_search($arrParam);//company list
		$data['workersList'] = $this->generalModel->get_user(["state" => 1]);//workers list
		$data['safetyWorkers'] = $this->safetyModel->get_safety_workers($idSafety);//safety_worker list
		$data['safetySubcontractorsWorkers'] = $this->generalModel->get_safety_subcontractors_workers(['idSafety' => $idSafety]);//safety subcontractors workers list

		return $this->render('App\Modules\Safety\Views\form_upload_safety_workers', $data);
	}

	/**
	 * Form Add Workers
     * @since 10/12/2016
     * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function add_workers($id)
	{
		$workersList = $this->generalModel->get_user(["state" => 1]);

		// 🔥 traemos todos los workers asignados de una sola vez
		$selectedWorkers = array_column(
			$this->safetyModel->get_selected_workers($id),
			'fk_id_user'
		);

		// 🔁 marcamos checked en memoria
		foreach ($workersList as &$worker) {
			$worker['found'] = in_array($worker['id_user'], $selectedWorkers);
		}

		$data = [
			'workersList' => $workersList,
			'idSafety' => $id
		];

		return $this->render('App\Modules\Safety\Views\form_add_workers', $data);
	}

	/**
	 * Save worker
     * @since 06/12/2016
     * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function save_safety_workers()
	{			
		$post = $this->request->getPost();
		$idSafety = $post['hddId'];
		$data = [];
		if ($this->safetyModel->add_safety_worker($post)) {
			$data["idSafety"] = $idSafety;
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have added the Workers, remember to get the signature of each one.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
    }

    /**
     * Delete safety worker
	 * @review 14/04/2026 - new CI4 version
     */
    public function deleteSafetyWorker($idSafetyWorker, $idSafety) 
	{
		$arrParam = [
			"table" => "safety_workers",
			"primaryKey" => "id_safety_worker",
			"id" => $idSafetyWorker
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted one worker.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('safety/upload_workers/' . $idSafety));
    }

    /**
     * Safe one worker
	 * @review 14/04/2026 - new CI4 version
     */
    public function safet_One_Worker() 
	{
		$post = $this->request->getPost();
		$idSafety = $post['hddId'];
		$data = [];

		if ($this->safetyModel->saveOneWorker($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have added one Worker.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('safety/upload_workers/' . $idSafety));
    }

    /**
     * Safe subcontractor worker
	 * @review 14/04/2026 - new CI4 version
     */
    public function safet_subcontractor_Worker() 
	{
		$post = $this->request->getPost();
		$idSafety = $post['hddId'];
		$data = [];

		if ($this->safetyModel->saveSubcontractorWorker($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have added one Worker.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('safety/upload_workers/' . $idSafety));
    }

    /**
     * Delete safety subcontractor
	 * @review 14/04/2026 - new CI4 version
     */
    public function deleteSafetySubcontractor($idSafetySubcontractor, $idSafety) 
	{
		$arrParam = [
			"table" => "safety_workers_subcontractor",
			"primaryKey" => "id_safety_subcontractor",
			"id" => $idSafetySubcontractor
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted one worker.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('safety/upload_workers/' . $idSafety));
    }

	/**
	 * Subcontractors view to sign
     * @since 15/4/2021
     * @author BMOTTAG
	 * @review 15/04/2026 - new CI4 version
	 */
	public function review_flha($idSafety)
	{
		$data = [];
		$data['information'] = $this->safetyModel->get_safety_by_id($idSafety);
		$data['safetyHazard'] = $this->safetyModel->get_safety_hazard($idSafety);
		$data['safetyWorkers'] = $this->safetyModel->get_safety_workers($idSafety);
		$data['safetySubcontractorsWorkers'] = $this->generalModel->get_safety_subcontractors_workers(['idSafety' => $idSafety]);
	
		return $this->render('App\Modules\Safety\Views\review_flha', $data);
	}

    /**
     * Cargo modal - formulario Employee Verification
     * @since 26/1/2023
	 * @review 15/04/2026 - new CI4 version
     */
	public function cargarModalEmployeeVerification()
	{
		$data = [];

		$data["idRecord"] = $this->request->getPost("idRecord");
		$data["table"] = $this->request->getPost("table");
		$data["backURL"] = $this->request->getPost("backURL");
		
		$information = $this->request->getPost('information');
		$porciones = explode("-", $information);
		$data["userType"] = $porciones[0];
		$data["idUser"]  = $porciones[1];
		$data["idSafetyWorker"] = $porciones[2];

		$arrParam = array(
			"table" => "user",
			"order" => "id_user",
			"column" => "id_user",
			"id" => $data["idUser"]
		);
		$data['information'] = $this->generalModel->get_basic_search($arrParam);

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Safety\Views\modal_verification', $data));
	}

	/**
	 * Varify credentials and save signature for employee
     * @since 26/1/2023
     * @author BMOTTAG
	 * @review 15/04/2026 - new CI4 version
	 */
	public function save_signature_credentials()
	{
		$idUser = $this->request->getPost('hddIdUser');
		$idSafetyWorker = $this->request->getPost('hddIdSafetyWorker');
		$idRecord = $this->request->getPost('hddIdRecord');
		$table = $this->request->getPost("hddTable");
		$backURL = $this->request->getPost('backURL');
		$userType = $this->request->getPost('hddUserType');

		$login = $this->request->getPost("login");
		$passwd = $this->request->getPost("password");

		$data = [
			"path" => $backURL . $idRecord
		];

		$user = $this->generalModel->validateCredentials([
			"idUser" => $idUser,
			"login" => $login,
			"passwd" => $passwd
		]);

		if (!$user) {
			return $this->response->setJSON([
				"status" => "error",
				"message" => " Error. Invalid credentials"
			]);
		}

		if (!$user["user_signature"]) {
			return $this->response->setJSON([
				"status" => "error",
				"message" => " Error. You have not saved your signature. Go to User Profile to set your signature."
			]);
		}

		switch ($userType) {
			case "advisor":
				$arrParam = [
					"table" => "safety",
					"primaryKey" => "id_safety",
					"id" => $idRecord,
					"column" => "signature",
					"value" => $user["user_signature"]
				];
				break;

			case "worker":
				$arrParam = [
					"table" => "safety_workers",
					"primaryKey" => "id_safety_worker",
					"id" => $idSafetyWorker,
					"column" => "signature",
					"value" => $user["user_signature"]
				];
				break;

			case "inspection":
				$arrParam = [
					"table" => $table,
					"primaryKey" => "id_" . $table,
					"id" => $idRecord,
					"column" => "signature",
					"value" => $user["user_signature"]
				];
				break;

			default:
				return $this->response->setJSON([
					"status" => "error",
					"message" => "Invalid user type"
				]);
		}

		if ($this->generalModel->updateRecord($arrParam)) {
			session()->setFlashdata('retornoExito', 'You have saved your signature.');

			return $this->response->setJSON([
				"status" => "success",
				"path" => $data["path"]
			]);
		}

		session()->setFlashdata('retornoError', 'Error!!! Ask for help');

		return $this->response->setJSON([
			"status" => "error"
		]);
	}
	
	public function save_signature()
	{
		$imageData = $this->request->getPost('image'); 
		$idWorker = $this->request->getPost('id'); 
		$fileName = "subcontractor_" . $idWorker . ".png";
		$filePath = WRITEPATH . '../public/images/signature/safety/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('error', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->generalModel->updateRecord([
				"table" => "safety_workers_subcontractor",
				"primaryKey" => "id_safety_subcontractor",
				"id" => $idWorker,
				"column" => "signature",
				"value" =>  'images/signature/safety/' . $fileName
			]);
			return redirect()->back()->with('retornoExito', 'Signature saved successfully.');
		} else {
			return redirect()->back()->with('retornoError', 'Error saving signature.');
		}
	}
	
	/**
	 * Save safetty undestanding
	 * @since 8/02/2026
	 * @author BMOTTAG
	 * @review 15/04/2026 - new CI4 version
	 */
	public function save_worker_undestanding()
	{
		$idSafety = $this->request->getPost('hddId');

		$arrParam = [
			"table" => "safety_workers",
			"primaryKey" => "id_safety_worker",
			"id" => $this->request->getPost('hddIdSafetyWorker'),
			"column" => "understanding",
			"value" => $this->request->getPost('description')
		];
		if ($this->generalModel->updateRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', "You have saved the understanding information!!");
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('safety/review_flha/' . $idSafety));
	}




}