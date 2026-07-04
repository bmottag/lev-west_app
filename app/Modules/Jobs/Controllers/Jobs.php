<?php
namespace App\Modules\Jobs\Controllers;

use App\Controllers\BaseController;
use App\Modules\Jobs\Models\JobsModel;
use App\Models\GeneralModel;
use TCPDF;

class Jobs extends BaseController
{
    protected $jobsModel;
    protected $generalModel;
	protected $helpers = ['form'];
    
    public function __construct()
    {
        $this->jobsModel   = new JobsModel();
        $this->generalModel   = new GeneralModel();
    }
	/**
	 * Attachments List
	 * @since 23/06/2023
	 * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function index()
	{
		$data['info'] = $this->generalModel->get_job(['state' => 1]);
		$data['dashboardURL'] = $this->session->get("dashboardURL");
		return $this->renderTopOnly('App\Modules\Jobs\Views\jobs_safety_list', $data);
	}

	/**
	 * SAFETY list
	 * @since 2/1/2018
	 * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function safety($idJob)
	{
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);

		//info de safety
		$arrParam = [
			"limit" => 30,
			"idJob" => $idJob
		];
		$data['information'] = $this->generalModel->get_safety($arrParam); //info de safety

		//hazards list
		$data['hazards'] = $this->generalModel->get_job_hazards($idJob);

		return $this->render('App\Modules\Jobs\Views\safety_list', $data);
	}

	/**
	 * Form Upload Hazards 
	 * @since 27/11/2017
	 * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function hazards($idJob)
	{
		$data = [];
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['hazards'] = $this->generalModel->get_job_hazards($idJob);

		return $this->render('App\Modules\Jobs\Views\hazards_list', $data);
	}

	/**
	 * Form Add Hazards
	 * @since 27/11/2017
	 * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function add_hazards($idJob)
	{
		$data = [];
		$data['activityList'] = $this->jobsModel->get_activity_list();
		$data['hazardsByActivity'] = $this->jobsModel->get_hazards_grouped($idJob);
		$data["idJob"] = $idJob;
		return $this->render('App\Modules\Jobs\Views\form_add_hazards', $data);
	}

	/**
	 * Save hazards
	 * @since 27/11/2017
	 * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function save_safety_hazards()
	{
		$post = $this->request->getPost();

		$data = [];
		$data["idJob"] = $id = $post['hddId'] ?? null;

		if ($this->jobsModel->add_safety_hazard($post)) {
			$this->jobsModel->add_hazard_log($post);
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have added Hazards.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Delete Job hazard
	 * @review 14/04/2026 - new CI4 version
	 */
	public function deleteJobHazard($idJobHazard, $idJob)
	{
		if (empty($idJobHazard) || empty($idJob)) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid IDs');
		}

		$arrParam = [
			"table" => "job_hazards",
			"primaryKey" => "id_job_hazard",
			"id" => $idJobHazard
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted one hazard.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/hazards/' . $idJob));
	}

	/**
	 * Hazards logs
	 * @since 21/8/2018
	 * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function hazards_logs($idJob)
	{
		$data = [];
		$data['info'] = $this->jobsModel->get_hazards_logs(["idJob" => $idJob]);
		$data["idJob"] = $idJob;
		return $this->render('App\Modules\Jobs\Views\hazards_logs', $data);
	}

	/**
	 * Generate JHA - JOB HAZARDS ANALYSIS Report in PDF
	 * @param int $idJobHazardLog
	 * @since 6/9/2018
	 * @author BMOTTAG
	 * @review 14/04/2026 - new CI4 version
	 */
	public function generaJHAPDF($idJobHazardLog)
	{
		$pdf = new TCPDF();

		$pdf->SetCreator('Lev West');
		$pdf->SetAuthor('Lev West');
		$pdf->SetTitle('JOB HAZARDS ANALYSIS Report');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// 👇 espacio para logo
		$pdf->SetMargins(10, 25, 10);
		$pdf->SetAutoPageBreak(TRUE, 10);

		$pdf->SetFont('dejavusans', '', 8);

		$data['info'] = $this->jobsModel->get_hazards_logs(["idJobHazardLog" => $idJobHazardLog]);

		//hazards list
		$data['hazards'] = $this->jobsModel->get_job_hazards_v2($data['info'][0]['id_job']);
		$vista = "jobs/reporte_jha_pdf";

		$pdf->AddPage();

		// LOGO
		$logo = FCPATH . 'images/logo.png';

		if (is_file($logo)) {
			$pdf->Image($logo, 10, 8, 30);
		}

		$html = view($vista, $data);

		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();

		$filename = 'jha_' . $idJobHazardLog . '.pdf';

		return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setBody($pdf->Output($filename, 'I'));
	}

	/**
	 * tool_box list
	 * @since 24/10/2017
	 * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function tool_box($idJob)
	{
		$data = [];
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['information'] = $this->generalModel->get_tool_box(["idJob" => $idJob]);
		return $this->render('App\Modules\Jobs\Views\tool_box_list', $data);
	}

	/**
	 * Form Tool Box
	 * @since 24/10/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function add_tool_box($idJob, $idToolBox = 'x')
	{
		$data = [];
		$data['information'] = FALSE;
		$data['deshabilitar'] = '';
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);

		$data['companyList'] = $this->generalModel->get_basic_search([
			"table" => "param_company",
			"order" => "company_name",
			"column" => "company_type",
			"id" => 2
		]); //company list

		$data['workersList'] = $this->generalModel->get_user(["state" => 1]); //workers list

		//si envio el id, entonces busco la informacion 
		if ($idToolBox != 'x') {

			$data['information'] = $this->generalModel->get_tool_box(["idToolBox" => $idToolBox]);
			if (!$data['information']) {
				throw new \Exception('ERROR!!! - You are in the wrong place.');
			}
			$data['newHazards'] = $this->jobsModel->get_new_hazards($idToolBox); //new hazard list
			$data['toolBoxWorkers'] = $this->jobsModel->get_tool_box_workers($idToolBox); //workers list

			//tool box subcontractors workers list
			$data['toolBoxSubcontractorsWorkers'] = $this->jobsModel->get_tool_box_subcontractors_workers($idToolBox);
		}
		return $this->render('App\Modules\Jobs\Views\form_tool_box', $data);
	}

	/**
	 * Save tool box
	 * @since 24/10/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function save_tool_box()
	{
		$data = [];
		$post = $this->request->getPost();
		$data["idRecord"] =  $post['hddIdJob'];
		if ($idToolBox = $this->jobsModel->add_TOOLBOX($post)) {
			$data["idToolBox"] = $idToolBox;
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have saved the IHSR, continue uploading the information!!');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Signature
	 * param $type: supervisor / worker
	 * param $idToolBox: llave principal del formulario
	 * param $idWorker: llave principal del trabajador
	 * @since 24/5/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function save_signature_tool_box()
	{
		$imageData = $this->request->getPost('image'); 
		$id = $this->request->getPost('extraValue'); 
		$type = $this->request->getPost('otherValue'); 
		
		switch ($type) {
			case "supervisor":
				$fileName = "supervisor_" . $id . ".png";
				$arrParam = [
					"table" => "tool_box",
					"primaryKey" => "id_tool_box",
					"id" => $id,
					"column" => "signature",
					"value" => 'images/signature/tool_box/' . $fileName
				];
				break;

			case "worker":
				$fileName = "worker_" . $id . ".png";
				$arrParam = [
					"table" => "tool_box_workers",
					"primaryKey" => "id_tool_box_worker",
					"id" => $id,
					"column" => "signature",
					"value" => 'images/signature/tool_box/' . $fileName
				];
				break;

			case "subcontractor":
				$fileName = "subcontractor_" . $id . ".png";
				$arrParam = [
					"table" => "tool_box_workers_subcontractor",
					"primaryKey" => "id_tool_box_subcontractor",
					"id" => $id,
					"column" => "signature",
					"value" => 'images/signature/tool_box/' . $fileName
				];
				break;

			default:
				return $this->response->setJSON([
					"status" => "error",
					"message" => "Invalid user type"
				]);
		}
		$filePath = WRITEPATH . '../public/images/signature/tool_box/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('error', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->generalModel->updateRecord($arrParam);
			return redirect()->back()->with('retornoExito', 'Signature saved successfully.');
		} else {
			return redirect()->back()->with('retornoError', 'Error saving signature.');
		}
	}

	/**
	 * Cargo modal- formulario de captura new hazard
	 * @since 25/10/2017
	 * @review 20/04/2026 - new CI4 version
	 */
	public function cargarModalNewHazard()
	{
		$data = [];

		$idToolBox = $this->request->getPost("idToolBox");
		//como se coloca un ID diferente para que no entre en conflicto con los otros modales, toca sacar el ID
		$porciones = explode("-", $idToolBox);
		$data["idToolBox"] = $porciones[1];

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Jobs\Views\modal_new_hazard', $data));
	}

	/**
	 * Save new hazard
	 * @since 25/10/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function save_modal_new_hazard()
	{
		$data = [];
		$post = $this->request->getPost();
		$data["idToolBox"] = $post['hddidToolBox'];
		//buscar ID del JOB
		$infoToolBox = $this->generalModel->get_tool_box(["idToolBox" => $data["idToolBox"]]);
		$data["idJob"] = $infoToolBox[0]["fk_id_job"];

		if ($this->jobsModel->saveNewHazard($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', "You have added a new record!!");
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Delete NEW HAZARD
	 * @review 20/04/2026 - new CI4 version
	 */
	public function deleteRecordNewHazard()
	{
		$data = [];
		$post = $this->request->getPost();
		$identificador = $post['identificador'];
		//toca recuperar todos los ID
		$porciones = explode("-", $identificador);

		$idNewHazard = $porciones[0];
		$idToolBox = $porciones[1];
		$idJob = $porciones[2];

		$data["idRecord"] = $idJob . '/' . $idToolBox;

		//eliminaos registros
		$arrParam = array(
			"table" => "tool_box_new_hazard",
			"primaryKey" => "id_new_hazard",
			"id" => $idNewHazard
		);
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted one record');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Update new hazard
	 * para editar un registro de new hazard
	 * @since 25/10/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function update_new_hazard()
	{
		$post = $this->request->getPost();
		$idJob = $post['hddIdJob'] ?? null;
		$idToolBox = $post['hddIdToolBox'] ?? null;

		if ($this->jobsModel->updateNewHazard($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', "You have updated the record!!");
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/add_tool_box/' . $idJob . '/' . $idToolBox));
	}

	/**
	 * Form Add Workers tool box
	 * @since 2/11/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function add_workers_tool_box($idJob, $idToolBox)
	{
		$workersList = $this->generalModel->get_user(["state" => 1]); //workers list

		// 🔥 traemos todos los workers asignados de una sola vez
		$selectedWorkers = array_column(
			$this->jobsModel->get_selected_workers_toolbox($idToolBox),
			'fk_id_user'
		);

		// 🔁 marcamos checked en memoria
		foreach ($workersList as &$worker) {
			$worker['found'] = in_array($worker['id_user'], $selectedWorkers);
		}

		$data = [
			'workersList' => $workersList,
			'idToolBox' => $idToolBox,
			'idJob' => $idJob
		];

		return $this->render('App\Modules\Jobs\Views\form_add_workers', $data);
	}
	
	/**
	 * Save worker
	 * @since 2/11/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function save_tool_box_workers()
	{			
		$post = $this->request->getPost();
		$idToolBox = $post['hddIdToolBox'];
		$idJob = $post['hddIdJob'];
		
		$data = [];
		$data["idRecord"] = $idJob . "/" . $idToolBox;
		if ($this->jobsModel->add_tool_box_worker($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have added the Workers, remember to get the signature of each one.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
    }

	/**
	 * Delete tool box worker
	 * @review 20/04/2026 - new CI4 version
	 */
	public function deleteToolBoxWorker($idJob, $idToolBox, $idToolBoxWorker)
	{
		$arrParam = [
			"table" => "tool_box_workers",
			"primaryKey" => "id_tool_box_worker",
			"id" => $idToolBoxWorker
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted one worker.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/add_tool_box/' . $idJob . '/' . $idToolBox));
	}

	/**
	 * Safe one worker to the TOOL BOX
	 * @review 20/04/2026 - new CI4 version
	 */
	public function tool_box_One_Worker()
	{
		$post = $this->request->getPost();
		$idToolBox = $post['hddIdToolBox'];
		$idJob = $post['hddIdJob'];
		
		if ($this->jobsModel->toolBoxSaveOneWorker($post)) {
			session()->setFlashdata('retornoExito', 'You have added one Worker.');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/add_tool_box/' . $idJob . '/' . $idToolBox));
	}

	/**
	 * Tool box subcontractor worker
	 * @review 20/04/2026 - new CI4 version
	 */
	public function tool_box_subcontractor_Worker()
	{
		$post = $this->request->getPost();
		$idToolBox = $post['hddIdToolBox'];
		$idJob = $post['hddIdJob'];

		if ($this->jobsModel->saveSubcontractorWorker($post)) {
			session()->setFlashdata('retornoExito', 'You have added one Worker.');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/add_tool_box/' . $idJob . '/' . $idToolBox));
	}

	/**
	 * Delete tool box subcontractor
	 * @review 20/04/2026 - new CI4 version
	 */
	public function deleteToolBoxSubcontractorWorker($idJob, $idToolBox, $idToolBoxSubcontractor)
	{
		$arrParam = [
			"table" => "tool_box_workers_subcontractor",
			"primaryKey" => "id_tool_box_subcontractor",
			"id" => $idToolBoxSubcontractor
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted one worker.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/add_tool_box/' . $idJob . '/' . $idToolBox));
	}

	/**
	 * Generate Template Report in PDF
	 * @param int $idToolBox
	 * @since 2/11/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function generaTemplatePDF($idToolBox)
	{
		$pdf = new TCPDF();

		$pdf->SetCreator('Lev West');
		$pdf->SetAuthor('Lev West');
		$pdf->SetTitle('IHSR Report');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// 👇 espacio para logo
		$pdf->SetMargins(10, 25, 10);
		$pdf->SetAutoPageBreak(TRUE, 10);

		$pdf->SetFont('dejavusans', '', 8);

		$data['info'] = $this->generalModel->get_tool_box(["idToolBox" => $idToolBox]);
		$data['newHazards'] = $this->jobsModel->get_new_hazards($idToolBox); //new hazard list
		$data['toolBoxWorkers'] = $this->jobsModel->get_tool_box_workers($idToolBox); //workers list
		$data['subcontractors'] = $this->jobsModel->get_tool_box_subcontractors_workers($idToolBox); //subcontractor list

		$pdf->AddPage();

		// LOGO
		$logo = FCPATH . 'images/logo.png';

		if (is_file($logo)) {
			$pdf->Image($logo, 10, 8, 30);
		}

		$html = view('jobs/reporte_pdf', $data);

		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();

		$filename = 'IHSR_' . $idToolBox . '.pdf';

		return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setBody($pdf->Output($filename, 'I'));
	}

	/**
	 * Form ERP
	 * @since 20/11/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function erp($idJob)
	{
		$data = [];
		$data['information'] = FALSE;
		$data['trainingWorkers'] = FALSE;
		$data['deshabilitar'] = '';

		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['workersList'] = $this->generalModel->get_user(["state" => 1]); //worker list

		//ERP info
		$data['information'] = $this->jobsModel->get_erp(["idJob" => $idJob]);

		//erp training list
		$data['trainingWorkers'] = $this->jobsModel->get_erp_training_workers($idJob);

		return $this->render('App\Modules\Jobs\Views\form_erp', $data);
	}

	/**
	 * Save ERP
	 * @since 20/11/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function save_erp()
	{
		$data = [];
		$post = $this->request->getPost();
		$data["idRecord"] =  $post['hddIdJob'];
		if ($this->jobsModel->add_erp($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have saved the ERP!!');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Form ERP - PERSONNEL
	 * @since 4/5/2018
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function erp_personnel($idJob)
	{
		$data = [];
		$data['information'] = FALSE;
		$data['trainingWorkers'] = FALSE;
		$data['deshabilitar'] = '';

		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['workersList'] = $this->generalModel->get_user(["state" => 1]); //worker list

		//ERP info
		$data['information'] = $this->jobsModel->get_erp(["idJob" => $idJob]);

		//erp training list
		$data['trainingWorkers'] = $this->jobsModel->get_erp_training_workers($idJob);

		return $this->render('App\Modules\Jobs\Views\form_erp_personnel', $data);
	}

	/**
	 * Form Add Workers for ERP Trainning
	 * @since 23/11/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function add_workers_training($id)
	{
		$workersList = $this->generalModel->get_user(["state" => 1]); //workers list

		// 🔥 traemos todos los workers asignados de una sola vez
		$selectedWorkers = array_column(
			$this->jobsModel->get_selected_workers_erp($id),
			'fk_id_user'
		);

		// 🔁 marcamos checked en memoria
		foreach ($workersList as &$worker) {
			$worker['found'] = in_array($worker['id_user'], $selectedWorkers);
		}

		$data = [
			'workersList' => $workersList,
			'idJob' => $id
		];

		return $this->render('App\Modules\Jobs\Views\form_add_workers_training', $data);
	}

	/**
	 * Save worker trainigno
	 * @since 23/11/2017
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function save_training_workers()
	{
		$data = [];
		$post = $this->request->getPost();
		$data["idRecord"] =  $post['hddIdJob'];
		if ($this->jobsModel->add_training_worker($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have added the Workers, remember to get the signature of each one.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Save one worker to the ERP TRAINING
	 * @review 20/04/2026 - new CI4 version
	 */
	public function save_one_erp_training_worker()
	{
		$post = $this->request->getPost();
		$idJob = $post['hddId'];
		
		if ($this->jobsModel->saveOneWorker($post)) {
			session()->setFlashdata('retornoExito', 'You have added one Worker.');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/erp_personnel/' . $idJob ));
	}

	/**
	 * Update infor personal
	 * @since 11/4/2021
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function update_erp_personnel()
	{
		$post = $this->request->getPost();
		$idJob = $post['hddIdERP'];

		if ($this->jobsModel->updateERPWorker($post)) {
			session()->setFlashdata('retornoExito', "You have saved the Worker Information!!");
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/erp_personnel/' . $idJob ));
	}

	/**
	 * Delete ERP TRAINING worker
	 * @review 20/04/2026 - new CI4 version
	 */
	public function deleteERPTRAINGINWorker($idJob, $idErpTrainingWorker)
	{
		$arrParam = [
			"table" => "erp_training_workers",
			"primaryKey" => "id_erp_training_worker",
			"id" => $idErpTrainingWorker
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted one worker.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/erp_personnel/' . $idJob));
	}

	/**
	 * Form ERP - MAP
	 * @since 4/5/2018
	 * @author BMOTTAG
	 * @review 20/04/2026 - new CI4 version
	 */
	public function erp_map($idJob)
	{
		$data['information'] = FALSE;
		$data['deshabilitar'] = '';
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['information'] = $this->jobsModel->get_erp(["idJob" => $idJob]);
		return $this->render('App\Modules\Jobs\Views\form_erp_map', $data);
	}

	/**
	 * FUNCIÓN PARA SUBIR LA IMAGEN 
	 * @review 20/04/2026 - new CI4 version
	 */
	public function do_upload()
	{
		$idJob = $this->request->getPost('hddIdJobMap');
		$file = $this->request->getFile('userfile');

		if (!$file->isValid()) {
			session()->setFlashdata('retornoError', $file->getErrorString());
			return redirect()->to(base_url('jobs/erp_map' . $idJob));
		}

		// Generar nombre seguro
		$newName = $idJob . '.' . $file->getExtension();

		// Ruta absoluta
		$path = FCPATH . 'images/erp/';

		// Mover archivo
		$file->move($path, $newName, true); // true = overwrite

		// Crear thumbnail si es photo
		$pathDb = 'images/erp/' . $newName;

		// Actualizar DB
		$arrParam = [
			"table" => "erp",
			"primaryKey" => "fk_id_job",
			"id" => $idJob,
			"column" => "evacuation_map",
			"value" => $pathDb
		];
		if ($this->generalModel->updateRecord($arrParam)) {
			session()->setFlashdata('retornoExito', 'Good job, you have uploaded the photo.');
		} else {
			session()->setFlashdata('retornoError', 'Ask for help.');
		}

		// Redirigir a la vista de regreso
		return redirect()->to(base_url('jobs/erp_map/' . $idJob));
	}

	/**
	 * Generate ERP Report in PDF
	 * @param int $idERP
	 * @since 20/11/2017
	 * @author BMOTTAG
	 * @review 21/04/2026 - new CI4 version
	 */
	public function generaERPPDF($idERP)
	{
		$pdf = new TCPDF();

		$pdf->SetCreator('Lev West');
		$pdf->SetAuthor('Lev West');
		$pdf->SetTitle('ERP Report');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// 👇 espacio para logo
		$pdf->SetMargins(10, 25, 10);
		$pdf->SetAutoPageBreak(TRUE, 10);

		$pdf->SetFont('dejavusans', '', 8);

		$data['info'] = $this->jobsModel->get_erp(["idERP" => $idERP]);

		$data['trainingWorkers'] = $this->jobsModel->get_erp_training_workers($data['info'][0]['fk_id_job']);

		$pdf->AddPage();

		// LOGO
		$logo = FCPATH . 'images/logo.png';

		if (is_file($logo)) {
			$pdf->Image($logo, 10, 8, 30);
		}

		// create some HTML content
		$html = '<p></p><p></p><p></p><p></p>
				<p><h1 align="center" style="color:#337ab7;">EMERGENCY RESPONSE PLAN</h1></p>
				<p></p><p></p><p></p><p></p><p></p>
				<p><h2 align="center" style="color:#337ab7;">Project code:<br>' . $data['info'][0]["job_description"] . '</h2></p>
				<p></p><p></p><p></p><p></p><p></p>
				<p><h2 align="center" style="color:#337ab7;">Facility Address:<br>' . $data['info'][0]["address"] . '</h2></p>
				<p></p><p></p><p></p><p></p><p></p>
				<p><h2 align="center" style="color:#337ab7;">DATE PREPARED:<br>' . $data['info'][0]["date_erp"] . '</h2></p>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');

		// add a page
		$pdf->AddPage();

		// create some HTML content
		$html = '<p><h1 align="center" style="color:#337ab7;">EMERGENCY PERSONNEL NAMES AND PHONE NUMBERS</h1></p>
				<style>
				table {
					font-family: arial, sans-serif;
					border-collapse: collapse;
					width: 100%;
				}

				td, th {
					border: 1px solid #dddddd;
					text-align: left;
					padding: 8px;
				}
				</style>
			<table border="0" cellspacing="0" cellpadding="5">
	
				<tr>
					<th bgcolor="#337ab7" style="color:white;" width="30%"><strong>Site supervisor: </strong></th>
					<th width="30%">' . $data['info'][0]['responsible'] . '</th>
					<th bgcolor="#337ab7" style="color:white;" width="20%"><strong>Phone: </strong></th>
					<th width="20%">' . $data['info'][0]['phone_res'] . '</th>
				</tr>
				
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Emergency coordinator: </strong></th>
					<th>' . $data['info'][0]['coordinator'] . '</th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Phone: </strong></th>
					<th>' . $data['info'][0]['phone_co'] . '</th>
				</tr>
			
			</table>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');

		$html = view("jobs/reporte_evacuation_pdf", $data);
		$pdf->writeHTML($html, true, false, true, false, '');

		// add a page
		$pdf->AddPage();
		$html = view("jobs/reporte_evacuation_procedures_pdf", $data);
		$pdf->writeHTML($html, true, false, true, false, '');

		// add a page
		$pdf->AddPage();
		$html = view("jobs/reporte_evacuation_procedures_fire_pdf", $data);
		$pdf->writeHTML($html, true, false, true, false, '');

		// add a page
		$pdf->AddPage();
		$html = view("jobs/reporte_evacuation_procedures_chemical_pdf", $data);
		$pdf->writeHTML($html, true, false, true, false, '');

		// add a page
		$pdf->AddPage();
		$html = view("jobs/reporte_evacuation_procedures_weather_pdf", $data);
		$pdf->writeHTML($html, true, false, true, false, '');

		// add a page
		$pdf->AddPage();
		$html = view("jobs/reporte_training_pdf", $data);
		$pdf->writeHTML($html, true, false, true, false, '');

		// reset pointer to the last page
		$pdf->lastPage();

		$filename = 'erp_' . $idERP . '.pdf';

		return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setBody($pdf->Output($filename, 'I'));
	}

	/**
	 * JSO
	 * @since 24/10/2017
	 * @author BMOTTAG
	 * @review 21/04/2026 - new CI4 version
	 */
	public function jso($idJob)
	{
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		//jso info
		$data['information'] = $this->jobsModel->get_jso(['idJob' => $idJob]);
		return $this->renderTopOnly('App\Modules\Jobs\Views\jso_list', $data);
	}

	/**
	 * Form JSO
	 * @since 3/1/2018
	 * @author BMOTTAG
	 * @review 21/04/2026 - new CI4 version
	 */
	public function add_jso($idJob, $idJobJso = 'x')
	{
		$data = [];
		$data['information'] = FALSE;
		$data['trainingWorkers'] = FALSE;
		$data['deshabilitar'] = '';

		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['workersList'] = $this->generalModel->get_user(["state" => 1]); //workers list

		//si envio el id, entonces busco la informacion 
		if ($idJobJso != 'x') {
			//JSO info
			$data['information'] = $this->jobsModel->get_jso(["idJobJso" => $idJobJso]);

			if (!$data['information']) {
				throw new \Exception('ERROR!!! - You are in the wrong place.');
			}

			//workers list
			$data['infoWorkers'] = "";
			if ($data['information']) {
				$data['infoWorkers'] = $this->jobsModel->get_jso_workers(["idJobJso" => $idJobJso]);
			}
		}

		return $this->render('App\Modules\Jobs\Views\form_jso', $data);
	}

	/**
	 * Save JSO
	 * @since 3/1/2018
	 * @author BMOTTAG
	 * @review 21/04/2026 - new CI4 version
	 */
	public function save_jso()
	{
		$post = $this->request->getPost();

		$data = [];
		$data["idRecord"] = $post['hddIdJob'] ?? null;
		if ($idJSO = $this->jobsModel->addJSO($post)) {
			$data["idJSO"] = $idJSO;
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have saved the JSO!!');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Signature
	 * param $incidencesType: near_miss / incident / accident
	 * param $userType: supervisor / coordinator
	 * param $idFormulario: llave principal del formulario
     * @since 15/5/2017
     * @author BMOTTAG
	 * @review 21/04/2026 - new CI4 version
	 */
	public function save_signature()
	{
		$imageData = $this->request->getPost('image'); 
		$id = $this->request->getPost('otherValue'); 
		$incidencesType = $this->request->getPost('extraValue'); 
		$userType = $this->request->getPost('id'); 
		$fileName = $incidencesType . "_" . $userType . "_" . $id . ".png";
		$filePath = WRITEPATH . '../public/images/signature/incidences/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('error', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->incidencesModel->updateInfoSignature([
				"table" => "incidence_" . $incidencesType,
				"signatureColumn" => $userType. "_signature",
				"valSignature" => 'images/signature/incidences/' . $fileName,
				"fechaColumn" => "date_" . $userType,
				"idColumn" => "id_" . $incidencesType,
				"idValue" => $id
			]);
			return redirect()->back()->with('retornoExito', 'Signature saved successfully.');
		} else {
			return redirect()->back()->with('retornoError', 'Error saving signature.');
		}
	}

	/**
	 * Signature
	 * param $type: supervisor / worker
	 * param $idJSO: llave principal del formulario
	 * param $idWorker: llave principal del trabajador
	 * @since 5/1/2018
	 * @author BMOTTAG
	 * @review 21/04/2026 - new CI4 version
	 */
	public function save_signature_jso()
	{
		$imageData = $this->request->getPost('image'); 
		$id = $this->request->getPost('extraValue'); 
		$type = $this->request->getPost('otherValue'); 
		
		switch ($type) {
			case "supervisor":
				$fileName = "supervisor_" . $id . ".png";
				$arrParam = [
					"table" => "job_jso",
					"primaryKey" => "id_job_jso",
					"id" => $id,
					"column" => "supervisor_signature",
					"value" => 'images/signature/jso/' . $fileName
				];
				break;

			case "manager":
				$fileName = "manager_" . $id . ".png";
				$arrParam = [
					"table" => "job_jso",
					"primaryKey" => "id_job_jso",
					"id" => $id,
					"column" => "manager_signature",
					"value" => 'images/signature/jso/' . $fileName
				];
				break;

			case "worker":
				$fileName = "worker_" . $id . ".png";
				$arrParam = [
					"table" => "job_jso_workers",
					"primaryKey" => "id_job_jso_worker",
					"id" => $id,
					"column" => "signature",
					"value" => 'images/signature/jso/' . $fileName
				];
				break;

			default:
				return $this->response->setJSON([
					"status" => "error",
					"message" => "Invalid user type"
				]);
		}
		$filePath = WRITEPATH . '../public/images/signature/jso/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('error', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->generalModel->updateRecord($arrParam);
			return redirect()->back()->with('retornoExito', 'Signature saved successfully.');
		} else {
			return redirect()->back()->with('retornoError', 'Error saving signature.');
		}
	}

	/**
	 * Cargo modal- formulario de captura workers para jso
	 * @since 5/1/2018
	 * @review 21/04/2026 - new CI4 version
	 */
	public function cargarModalWorker()
	{
		$data = [];
		$data['information'] = null;

		$idJobJso = $this->request->getPost("idJobJso");
		$idJobJsoWorker = $this->request->getPost("idJobJsoWorker");
		$data["idJobJso"] = $idJobJso;
		$data["idJobJsoWorker"] = $idJobJsoWorker;

		if (!empty($idJobJsoWorker) && $idJobJsoWorker !== 'x') {
			$data['information'] = $this->jobsModel->get_jso_workers(["idJobJsoWorker" => $data["idJobJsoWorker"]]);
		
			if ($data['information'] && is_array($data['information']) && isset($data['information'][0])) {
				$data["idJobJso"] = $data['information'][0]['fk_id_job_jso'];
			}
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Jobs\Views\modal_jso_worker', $data));
	}

	/**
	 * Save formularios
	 * @since 5/1/2018
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function saveJSOWorker()
	{
		$post = $this->request->getPost();

		$idJobJso = $post['hddidJobJso'] ?? null;
		$idJobWorker = $post['hddidJobJsoWorker'] ?? null;

		//JSO info
		$infoJSO = $this->jobsModel->get_jso(["idJobJso" => $idJobJso]);

		$data = [];
		$data["idRecord"] = $infoJSO[0]['fk_id_job'];
		$data["idJSO"] = $idJobJso;
		$data["idRecordExternal"] = $idJobWorker;

		$msj = $idJobWorker 
			? "You have edited the information!!" 
			: "You have added a new worker!!";

		if ($this->jobsModel->saveJSOWorker($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Generate JSO Report in PDF
	 * @param int $idJSO
	 * @since 7/1/2018
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function generaJSOPDF($idJSO)
	{
		$pdf = new TCPDF();

		$pdf->SetCreator('Lev West');
		$pdf->SetAuthor('Lev West');
		$pdf->SetTitle('JSO Report');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// 👇 espacio para logo
		$pdf->SetMargins(10, 25, 10);
		$pdf->SetAutoPageBreak(TRUE, 10);

		$pdf->SetFont('dejavusans', '', 8);

		$data['info'] = $this->jobsModel->get_jso(["idJobJso" => $idJSO]);
		$data['workers'] = $this->jobsModel->get_jso_workers(["idJobJso" => $idJSO]);

		$pdf->AddPage();

		// LOGO
		$logo = FCPATH . 'images/logo.png';

		if (is_file($logo)) {
			$pdf->Image($logo, 10, 8, 30);
		}

		$html = view('jobs/reporte_jso', $data);
		if ($data['workers']) {
			$html .= view('jobs/reporte_jso_workers', $data);
		}

		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();

		$filename = 'JSO_' . $data['info'][0]['job_description']  . $idJSO . '.pdf';

		return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setBody($pdf->Output($filename, 'I'));
	}

	/**
	 * Informacion detallada del proyecto
	 * @since 23/12/2020
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function bitacora($idJob)
	{
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		//informacion Work Order
		$data['workOrderInfo'] = $this->generalModel->get_workorder_info(["jobId" => $idJob]);
		//bitacora
		$data['bitacora'] = $this->jobsModel->get_bitacora_job($idJob);
		return $this->render('App\Modules\Jobs\Views\job_bitacora', $data);
	}

	public function loadModalBitacora()
	{
		$data = [];
		$data["idBitacora"] = $this->request->getPost("idBitacora");
		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Jobs\Views\bitacora_modal', $data));
	}

	public function save_bitacora()
	{
		$post = $this->request->getPost();

		$data = [];
		$data["idJob"] = $post['hddId'] ?? null;

		if ($this->jobsModel->saveBitacora($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', "You have added a new note!!");
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Form Upload Locates 
	 * @since 29/11/2017
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function locates($idJob)
	{
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		//locates list
		$data['locates'] = $this->jobsModel->get_job_locates($idJob);
		
		return $this->render('App\Modules\Jobs\Views\locates_list', $data);
	}

	/**
	 * FUNCIÓN PARA SUBIR LA IMAGEN 
	 * @review 22/04/2026 - new CI4 version
	 */
	public function do_upload_locates()
	{
		$post = $this->request->getPost();
		$idJob = $post['hddIdJob'] ?? null;

		$file = $this->request->getFile('userfile');

		if (!$file || !$file->isValid() || $file->hasMoved()) {
			session()->setFlashdata('retornoError', $file ? $file->getErrorString() : 'No file uploaded');
			return redirect()->to(base_url('jobs/locates/' . $idJob));
		}

		$newName = $file->getName();

		// Ruta absoluta
		$path = FCPATH . 'images/locates/';

		// Mover archivo
		$file->move($path, $newName, true); // true = overwrite

		// Ruta para DB
		$pathDb = 'images/locates/' . $newName;

		if ($this->jobsModel->add_locates($post, $pathDb)) {
			session()->setFlashdata('retornoExito', 'Good job, you have uploaded the photo.');
		} else {
			session()->setFlashdata('retornoError', 'Ask for help.');
		}

		// Redirigir a la vista de regreso
		return redirect()->to(base_url('jobs/locates/' . $idJob));
	}

	/**
	 * Delete Job locate
	 * @review 22/04/2026 - new CI4 version
	 */
	public function deleteJobLocate($idJobLocate, $idJob)
	{
		if (empty($idJobLocate) || empty($idJob)) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid IDs');
		}

		$arrParam = [
			"table" => "job_locates",
			"primaryKey" => "id_job_locates",
			"id" => $idJobLocate
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted the image.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/locates/' . $idJob));
	}

	/**
	 * Excavation and Trenching Plan list
	 * @since 1/08/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function excavation($idJob)
	{
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);

		//Excavation and trenching info
		$data['information'] = $this->generalModel->get_excavation(["idJob" => $idJob]);
		
		return $this->render('App\Modules\Jobs\Views\excavation_list', $data);
	}

	/**
	 * Form Excavation and Trenching Plan
	 * @since 1/08/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function add_excavation($idJob, $idExcavation = 'x')
	{
		$data['information'] = null;
		$data['deshabilitar'] = '';

		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);

		$data['confinedList'] = $this->generalModel->get_confined_space(['idJob' => $idJob]);

		//si envio el id, entonces busco la informacion 
		if ($idExcavation != 'x') {

			$data['information'] = $this->generalModel->get_excavation(["idExcavation" => $idExcavation]);

			if (!$data['information']) {
				throw new \Exception('ERROR!!! - You are in the wrong place.');
			}
		}

		return $this->render('App\Modules\Jobs\Views\form_excavation', $data);
	}

	/**
	 * Save Excavation and Trenching Plan
	 * @since 1/08/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function save_excavation()
	{
		$post = $this->request->getPost();

		$data = [];

		if ($idExcavation = $this->jobsModel->addExcavation($post)) {
			$data["idExcavation"] = $idExcavation;
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have saved your Excavation and Trenching Plan, do not forget to add Workers and signatures.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Form Upload Personnel to Excavation Plan
	 * @since 2/8/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function upload_excavation_personnel($idExcavation)
	{
		$data = [
			'adminList' => $this->generalModel->get_user([
				"state" => 1, "idUserMANAGERS" => true
			]),
			'workersList' => $this->generalModel->get_user(["state" => 1]),
			'information' => $this->generalModel->get_excavation(["idExcavation" => $idExcavation]),
			'excavationWorkers' => $this->generalModel->get_excavation_workers(["idExcavation" => $idExcavation]),
			'excavationSubcontractors' => $this->generalModel->get_excavation_subcontractors(["idExcavation" => $idExcavation]),
			'companyList' => $this->generalModel->get_company(["company_type" => 2]),
		];

		return $this->render('App\Modules\Jobs\Views\form_excavation_personnel', $data);
	}

	/**
	 * Save Excavation and Trenching Plan - Personnel
	 * @since 14/08/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function save_personnel()
	{
		$post = $this->request->getPost();

		$data = [];

		$idExcavation =  $post['hddIdentificador'] ?? null;

		if ($this->jobsModel->updatePersonnel($post)) {
			$data["idExcavation"] = $idExcavation;
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have updated the information of  your Excavation and Trenching Plan.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Form Add Workers - Excavation
	 * @since 14/8/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function add_workers_excavation($idExcavation)
	{
		$workersList = $this->generalModel->get_user(["state" => 1]); //workers list

		// 🔥 traemos todos los workers asignados de una sola vez
		$selectedWorkers = array_column(
			$this->jobsModel->get_selected_workers_excavation($idExcavation),
			'fk_id_user'
		);

		// 🔁 marcamos checked en memoria
		foreach ($workersList as &$worker) {
			$worker['found'] = in_array($worker['id_user'], $selectedWorkers);
		}

		$data = [
			'workersList' => $workersList,
			'idExcavation' => $idExcavation
		];

		return $this->render('App\Modules\Jobs\Views\form_add_workers_excavation', $data);
	}

	/**
	 * Save worker - Excavation and Trenching Plan
	 * @since 14/8/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function save_excavation_workers()
	{
		$post = $this->request->getPost();

		$data = [];

		$data["idRecord"] =  $post['hddIdExcavation'] ?? null;

		if ($this->jobsModel->add_excavation_worker($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have added the Workers, remember to get the signature of each one.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Delete worker - Excavation and Trenching Plan
	 * @since 14/8/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function deleteExcavationWorker($idExcavation, $idWorker)
	{
		$arrParam = [
			"table" => "job_excavation_workers",
			"primaryKey" => "id_excavation_worker",
			"id" => $idWorker
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted one worker.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/upload_excavation_personnel/' . $idExcavation));
	}

	/**
	 * Save one worker to Excavation and Trenching Plan
	 * @since 14/8/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function excavation_One_Worker()
	{
		$post = $this->request->getPost();
		$id = $post['hddIdExcavation'];
		
		if ($this->jobsModel->excavationSaveOneWorker($post)) {
			session()->setFlashdata('retornoExito', 'You have added one Worker.');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/upload_excavation_personnel/' . $id));
	}

	/**
	 * Excavation - Subcontractor worker
	 * @since 14/8/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function excavation_subcontractor_Worker()
	{
		$post = $this->request->getPost();
		$id = $post['hddIdExcavation'];

		if ($this->jobsModel->saveSubcontractorWorkerExcavation($post)) {
			session()->setFlashdata('retornoExito', 'You have added one Worker.');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/upload_excavation_personnel/' . $id));
	}

	/**
	 * Delete subcontractor - Excavation and Trenching Plan
	 * @since 14/8/2021
	 * @author BMOTTAG
	 * @review 22/04/2026 - new CI4 version
	 */
	public function deleteExcavationSubcontractorWorker($idExcavation, $idSubcontractor)
	{
		$arrParam = [
			"table" => "job_excavation_subcontractor",
			"primaryKey" => "id_excavation_subcontractor",
			"id" => $idSubcontractor
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have deleted one worker.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('jobs/upload_excavation_personnel/' . $idExcavation));
	}

	/**
	 * Form Excavation and Trenching Plan - Protection Methods
	 * @since 3/08/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function upload_protection_methods($idExcavation)
	{
		$data['information'] = $this->generalModel->get_excavation(["idExcavation" => $idExcavation]);
		return $this->render('App\Modules\Jobs\Views\form_excavation_protection_methods', $data);
	}

	/**
	 * Save Excavation and Trenching Plan - Protection Methods
	 * @since 8/08/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function save_protection_methods()
	{
		$post = $this->request->getPost();
		$idExcavation = $post['hddIdentificador'] ?? null;

		$file = $this->request->getFile('userfile');

		$name = null;
		if ($file && $file->isValid() && !$file->hasMoved()) 
		{
			$name = $file->getName();

			$path = FCPATH . 'files/excavation/';

			// crear carpeta si no existe
			if (!is_dir($path)) {
				mkdir($path, 0777, true);
			}

			$file->move($path, $name, true);
		}

		if ($this->jobsModel->updateExcavation($post, $name)) {
			session()->setFlashdata('retornoExito', 'You have updated the information of your Excavation and Trenching Plan.');
		} else {
			session()->setFlashdata('retornoError', 'Ask for help.');
		}

		// Redirigir a la vista de regreso
		return redirect()->to(base_url('jobs/upload_protection_methods/' . $idExcavation));
	}

	/**
	 * Form Excavation and Trenching Plan - Access & Egress
	 * @since 3/08/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function upload_access_egress($idExcavation)
	{
		$data['information'] = $this->generalModel->get_excavation(["idExcavation" => $idExcavation]);
		return $this->render('App\Modules\Jobs\Views\form_excavation_access_egress', $data);
	}

	/**
	 * Save Excavation and Trenching Plan - Access & Egress
	 * @since 8/08/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function save_access_egress()
	{
		$post = $this->request->getPost();

		$data = [];

		$data["idExcavation"] =  $post['hddIdentificador'] ?? null;

		if ($this->jobsModel->updateExcavationAccess($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have updated the information of your Excavation and Trenching Plan.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Form Excavation and Trenching Plan - Affected Zone, Traffic & Utilities
	 * @since 3/08/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function upload_affected_zone($idExcavation)
	{
		$data['information'] = $this->generalModel->get_excavation(["idExcavation" => $idExcavation]);
		return $this->render('App\Modules\Jobs\Views\form_excavation_affected_zone', $data);
	}

	/**
	 * Save Excavation and Trenching Plan - Affected Zone, Traffic & Utilities
	 * @since 8/08/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function save_affected_zone()
	{
		$post = $this->request->getPost();
		$idExcavation = $post['hddIdentificador'] ?? null;

		$file = $this->request->getFile('userfile');

		$name = null;
		if ($file && $file->isValid() && !$file->hasMoved()) 
		{
			$name = $file->getName();

			$path = FCPATH . 'files/excavation/';

			// crear carpeta si no existe
			if (!is_dir($path)) {
				mkdir($path, 0777, true);
			}

			$file->move($path, $name, true);
		}

		if ($this->jobsModel->updateExcavationAffectedZone($post, $name)) {
			session()->setFlashdata('retornoExito', 'You have updated the information of your Excavation and Trenching Plan.');
		} else {
			session()->setFlashdata('retornoError', 'Ask for help.');
		}

		// Redirigir a la vista de regreso
		return redirect()->to(base_url('jobs/upload_affected_zone/' . $idExcavation));
	}

	/**
	 * Form Excavation and Trenching Plan - De-Watering
	 * @since 3/08/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function upload_de_watering($idExcavation)
	{
		$data['information'] = $this->generalModel->get_excavation(["idExcavation" => $idExcavation]);
		return $this->render('App\Modules\Jobs\Views\form_excavation_de_watering', $data);
	}

	/**
	 * Save Excavation and Trenching Plan - De-Watering
	 * @since 8/08/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function save_de_watering()
	{
		$post = $this->request->getPost();

		$data = [];

		$data["idExcavation"] =  $post['hddIdentificador'] ?? null;

		if ($this->jobsModel->updateExcavationDeWatering($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have updated the information of your Excavation and Trenching Plan.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Form Excavation and Trenching Plan - Sketch
	 * @since 20/01/2022
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function upload_sketch($idExcavation)
	{
		$data['information'] = $this->generalModel->get_excavation(["idExcavation" => $idExcavation]);
		return $this->render('App\Modules\Jobs\Views\form_excavation_sketch', $data);
	}

	/**
	 * Signature
	 * param $type: supervisor / worker
	 * param $idExcavation: llave principal del formulario
	 * param $idWorker: llave principal del trabajador
	 * @since 14/8/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function add_signature_excavation()
	{
		$imageData = $this->request->getPost('image'); 
		$id = $this->request->getPost('extraValue'); 
		$type = $this->request->getPost('otherValue'); 

		$msj = "Signature saved successfully.";
		
		switch ($type) {
			case "sketch":
				$msj = "Good job, you have saved the excavation/trench sketch.";
				$fileName = "sketch_" . $id . ".png";
				$arrParam = [
					"table" => "job_excavation",
					"primaryKey" => "id_job_excavation",
					"id" => $id,
					"column" => "excavation_sketch",
					"value" => 'images/signature/etp/' . $fileName
				];
				break;

			case "manager":
			case "supervisor":
			case "operator":
				$fileName = $type . "_" . $id . ".png";
				$arrParam = [
					"table" => "job_excavation",
					"primaryKey" => "id_job_excavation",
					"id" => $id,
					"column" => $type . "_signature",
					"value" => 'images/signature/etp/' . $fileName
				];
				break;

			case "worker":
				$fileName = "worker_" . $id . ".png";
				$arrParam = [
					"table" => "job_excavation_workers",
					"primaryKey" => "id_excavation_worker",
					"id" => $id,
					"column" => "signature",
					"value" => 'images/signature/etp/' . $fileName
				];
				break;

			case "subcontractor":
				$fileName = "subcontractor_" . $id . ".png";
				$arrParam = [
					"table" => "job_excavation_subcontractor",
					"primaryKey" => "id_excavation_subcontractor",
					"id" => $id,
					"column" => "signature",
					"value" => 'images/signature/etp/' . $fileName
				];
				break;

			default:
				return $this->response->setJSON([
					"status" => "error",
					"message" => "Invalid user type"
				]);
		}
		$filePath = WRITEPATH . '../public/images/signature/etp/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('error', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->generalModel->updateRecord($arrParam);
			return redirect()->back()->with('retornoExito', $msj);
		} else {
			return redirect()->back()->with('retornoError', 'Error saving signature.');
		}
	}

	/**
	 * Save Trenching Plan - Sketch
	 * @since 12/03/2022
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function save_upload_sketch()
	{
		$post = $this->request->getPost();
		$idExcavation = $post['hddIdentificador'] ?? null;

		$file = $this->request->getFile('userfile');

		if (!$file || !$file->isValid() || $file->hasMoved()) {
			session()->setFlashdata('retornoError', $file ? $file->getErrorString() : 'No file uploaded');
			return redirect()->to(base_url('jobs/upload_sketch/' . $idExcavation));
		}

		$newName = $file->getName();

		// Ruta absoluta
		$path = FCPATH . 'files/excavation/';

		// Mover archivo
		$file->move($path, $newName, true); // true = overwrite

		$arrParam = array(
			"table" => "job_excavation",
			"primaryKey" => "id_job_excavation",
			"id" => $idExcavation,
			"column" => "excavation_sketch_doc",
			"value" => $newName
		);
		if ($this->generalModel->updateRecord($arrParam)) {
			session()->setFlashdata('retornoExito', 'You have updated the information of your Excavation and Trenching Plan.');
		} else {
			session()->setFlashdata('retornoError', 'Ask for help.');
		}

		// Redirigir a la vista de regreso
		return redirect()->to(base_url('jobs/upload_sketch/' . $idExcavation));
	}

	/**
	 * Subcontractors view to sign
	 * @since 14/8/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function review_excavation($idExcavation)
	{
		$data = [
			'information' => $this->generalModel->get_excavation(["idExcavation" => $idExcavation]),
			'excavationWorkers' => $this->generalModel->get_excavation_workers(["idExcavation" => $idExcavation]),
			'excavationSubcontractors' => $this->generalModel->get_excavation_subcontractors(["idExcavation" => $idExcavation])
		];

		return $this->renderTopOnly('App\Modules\Jobs\Views\review_excavation', $data);
	}

	/**
	 * Generate Report in PDF - Excavation and Trenching Plan
	 * @param int $idExcavation
	 * @since 15/08/2021
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function generaExcavationPDF($idExcavation)
	{
		$pdf = new TCPDF();

		$pdf->SetCreator('Lev West');
		$pdf->SetAuthor('Lev West');
		$pdf->SetTitle('Excavation and Trenching Plan Report');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// 👇 espacio para logo
		$pdf->SetMargins(10, 25, 10);
		$pdf->SetAutoPageBreak(TRUE, 10);

		$pdf->SetFont('dejavusans', '', 8);

		$data = [
			'info' => $this->generalModel->get_excavation(["idExcavation" => $idExcavation]),
			'excavationWorkers' => $this->generalModel->get_excavation_workers(["idExcavation" => $idExcavation]),
			'excavationSubcontractors' => $this->generalModel->get_excavation_subcontractors(["idExcavation" => $idExcavation])
		];

		$pdf->AddPage();

		// LOGO
		$logo = FCPATH . 'images/logo.png';

		if (is_file($logo)) {
			$pdf->Image($logo, 10, 8, 30);
		}

		$html = view('jobs/reporte_excavation', $data);

		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();

		$date = $data['info'][0]['date_excavation'];
		$filename = 'Excavation_Trenching_Plan_' . $date . '.pdf';

		return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setBody($pdf->Output($filename, 'I'));
	}

	/**
	 * Fire watch list
	 * @since 27/1/2023
	 * @author BMOTTAG
	 * @review 23/04/2026 - new CI4 version
	 */
	public function fire_watch($idJob, $token = null)
	{
		if($token){
			$token = base64url_decode($token);
			$tagInfo = $this->generalModel->get_basic_search([
				"table" => "param_tags",
				"order" => "id_tag",
				"column" => "token",
				"id" => $token 
			]);

			if ($tagInfo) {
				$idJob = $tagInfo[0]['fk_id_job'];

				// 🔥 AQUÍ guardas el punto en sesión
				session()->set(['current_tag_name' => $tagInfo[0]['name']]);
			}
		}else {
			// 🔥 limpiar si NO viene token
			session()->remove(['current_tag_name']);
		}

		$data = [
			'jobInfo' => $this->generalModel->get_job(['idJob' => $idJob]),
			'infoFireWatchSetup' => $this->jobsModel->get_fire_watch_setup(['idJob' => $idJob]),
			'information' => $this->jobsModel->get_fire_watch(['idJob' => $idJob])
		];

		return $this->render('App\Modules\Jobs\Views\fire_watch_list', $data);
	}

	/**
	 * Cargo modal - formulario fire watch setup
	 * @since 27/1/2023
	 * @review 24/04/2026 - new CI4 version
	 */
	public function cargarModalFireWatchSetup()
	{
		$data = [];

		$idJob = $this->request->getPost("idJob");

		$data = [
			'metodo'	  => $this->request->getPost("metodo"),
			'idJob'		  => $idJob,
			'workersList' => $this->generalModel->get_user(["state" => 1, "idRolesSupervisors" => true]),
			'information' => $this->jobsModel->get_fire_watch_setup(['idJob' => $idJob])
		];

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Jobs\Views\fire_watch_setup_modal', $data));
	}

	/**
	 * Cargo modal - formulario fire watch
	 * @since 27/1/2023
	 * @review 24/04/2026 - new CI4 version
	 */
	public function cargarModalFireWatch()
	{
		$idJob = $this->request->getPost("idJob");
		
		$data = [];

		$data = [
			'idFireWatch' => $this->request->getPost("idFireWatch"),
			'idJob'		  => $idJob,
			'workersList' => $this->generalModel->get_user(["state" => 1]),
			'information' => $this->jobsModel->get_fire_watch_setup(['idJob' => $idJob])
		];

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Jobs\Views\fire_watch_modal', $data));
	}

	/**
	 * Save fire watch
	 * @since 27/1/2023
	 * @author BMOTTAG
	 * @review 24/04/2026 - new CI4 version
	 */
	public function save_fire_watch_setup()
	{
		$post = $this->request->getPost();

		$data = [];
		$data["idRecord"] = $post['hddIdJob'] ?? null;

		if ($this->jobsModel->saveFireWatchSetup($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', "You have saved the information!!");
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Save fire watch
	 * @since 27/1/2023
	 * @author BMOTTAG
	 * @review 24/04/2026 - new CI4 version
	 */
	public function save_fire_watch()
	{
		$post = $this->request->getPost();

		$id = $post['hddIdFireWatch'] ?? null;
		$msj = $id 
			? "You have updated a Fire Watch!!" 
			: "You have added a new Fire Watch!!";

		$data = [];
		$data["idRecord"] = $post['hddIdJob'] ?? null;

		if ($this->jobsModel->saveFireWatch($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Form Checkin for Fire Watch
	 * @since 3/2/2023
	 * @author BMOTTAG
	 * @review 24/04/2026 - new CI4 version
	 */
	public function fire_watch_checkin($idFireWatch, $idCheckin = null)
	{
		$arrParam = [
			"today" => date('Y-m-d'),
			"idFireWatch" => $idFireWatch
		];
		if ($idCheckin !== null) {
			$arrParam = ["idCheckin" => $idCheckin];
		}
		$data = [
			'idCheckin' => $idCheckin ?? false,
			'checkinList' => $this->jobsModel->get_fire_watch_checkin($arrParam),
			'information' => $this->jobsModel->get_fire_watch(["idFireWatch" => $idFireWatch])
		];

		return $this->renderTopOnly('App\Modules\Jobs\Views\form_fire_watch_checkin', $data);
	}

	/**
	 * Save Fire Watch Checkin
	 * @since 3/2/2023
	 * @author BMOTTAG
	 * @review 24/04/2026 - new CI4 version
	 */
	public function save_fire_watch_checkin()
	{
		$post = $this->request->getPost();
		$data = [];
		$data["idFireWatch"] = $post['idFireWatch'] ?? null;
		$msj = "Information saved successfully!";

		if ($this->jobsModel->saveFireWatchCheckin($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Generate Fire Watch Record Report in PDF
	 * @param int $idFireWatch
	 * @since 26/07/2023
	 * @author BMOTTAG
	 */
	public function generaFIREWATCHPDF($idFireWatch)
	{
		$pdf = new TCPDF();

		$pdf->SetCreator('Lev West');
		$pdf->SetAuthor('Lev West');
		$pdf->SetTitle('Fire Watch Report');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// 👇 espacio para logo
		$pdf->SetMargins(10, 25, 10);
		$pdf->SetAutoPageBreak(TRUE, 10);

		$pdf->SetFont('dejavusans', '', 8);

		$data = [
			'info' => $this->jobsModel->get_fire_watch(["idFireWatch" => $idFireWatch]),
			'checkinList' => $this->jobsModel->get_fire_watch_checkin([
				"idFireWatch" => $idFireWatch,
				"distinctUser" => true
			]),
			'checkinList_log' => $this->jobsModel->get_fire_watch_checkin(["idFireWatch" => $idFireWatch])
			
		];

		$pdf->AddPage();

		// LOGO
		$logo = FCPATH . 'images/logo.png';

		if (is_file($logo)) {
			$pdf->Image($logo, 10, 8, 30);
		}

		$html = view('jobs/reporte_fire_watch_pdf', $data);
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->AddPage();
		$html = view('jobs/reporte_fire_watch_log_pdf', $data);
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();

		$filename = 'fire_watch_report_' . $data['info'][0]['job_description'] . '.pdf';

		return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setBody($pdf->Output($filename, 'I'));
	}

	/**
	 * Job Detail List
	 * @since 6/1/2023
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function job_detail($idJob)
	{
		$data['jobInfo']     = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['chapterList'] = $this->generalModel->get_chapter_list(['idJob' => $idJob]);
		$data['isJobDetail'] = true;

		$chapterDetails  = [];
		$expensesByDetail = [];
		$forceAccountExpensesByDetail = [];
		$faExpensesTotalByDetail = [];

		if ($data['chapterList']) {
			foreach ($data['chapterList'] as $chapter) {
				$arrParam = ['idJob' => $idJob, 'chapterNumber' => $chapter['chapter_number'], 'status' => 1];
				$details  = $this->generalModel->get_job_detail($arrParam);
				$chapterDetails[$chapter['chapter_number']] = $details;

				if ($details) {
					foreach ($details as $detail) {
						$expensesByDetail[$detail['id_job_detail']] = $this->jobsModel->countExpenses(['idJobDetail' => $detail['id_job_detail']]);
						$forceAccountExpensesByDetail[$detail['id_job_detail']] = $this->jobsModel->countForceAccountExpenses(['idJobDetail' => $detail['id_job_detail']]);
					}
				}

				$faDetails = $this->jobsModel->get_job_detail_fa_expenses($arrParam);
				if ($faDetails) {
					foreach ($faDetails as $faDetail) {
						$faExpensesTotalByDetail[$faDetail['id_job_detail']] = $faDetail['fa_expenses'] ?? 0;
					}
				}
			}
		}

		$data['chapterDetails']   = $chapterDetails;
		$data['expensesByDetail'] = $expensesByDetail;
		$data['forceAccountExpensesByDetail'] = $forceAccountExpensesByDetail;
		$data['faExpensesTotalByDetail'] = $faExpensesTotalByDetail;

		return $this->renderTopOnly('App\Modules\Jobs\Views\job', $data);
	}

	/**
	 *Cargue de archivo
	 * @since 20/06/2022
	 * @review 08/05/2026 - new CI4 version
	 */
	public function do_upload_job_info()
	{
		$idJob = $this->request->getPost('hddIdJob');
		$file  = $this->request->getFile('userfile');

		if (!$file || !$file->isValid()) {
			$msg = $file ? $file->getErrorString() : 'No file uploaded.';
			session()->setFlashdata('retornoError', $msg);
			return redirect()->to(base_url('jobs/job_detail/' . $idJob));
		}

		// VALIDAR CSV
		if ($file->getExtension() !== 'csv') {
			session()->setFlashdata(
				'retornoError',
				'Only CSV files are allowed.'
			);
			return redirect()->to(base_url('jobs/job_detail/' . $idJob));
		}

		$flagExpenses      = $this->request->getPost('hddFlagExpenses');
		$flagUploadDetails = $this->request->getPost('hddFlagUploadDetails');

		if ($flagUploadDetails == 1) {
			$this->generalModel->deleteRecord([
				'table'      => 'job_details',
				'primaryKey' => 'fk_id_job',
				'id'         => $idJob,
			]);
		}

		if ($flagExpenses == 1) {
			$this->jobsModel->deleteWOExpenses(['idJob' => $idJob]);

			$this->generalModel->updateRecord([
				'table'      => 'param_jobs',
				'primaryKey' => 'id_job',
				'id'         => $idJob,
				'column'     => 'flag_expenses',
				'value'      => 0,
			]);
		}

		$path = WRITEPATH . 'uploads/';

        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }

        $file->move($path, 'job_detail.csv', true);

		$records = [];
		if (($handle = fopen(WRITEPATH . 'uploads/job_detail.csv', 'r')) !== false) {
			$idUser     = $this->session->get('id');
			$fieldNames = fgetcsv($handle, 0, ';');
			$numFields  = count($fieldNames);

			while (($row = fgetcsv($handle, 0, ';')) !== false) {
				$record = [
					'fk_id_user' => $idUser,
					'fk_id_job'  => $idJob,
				];
				for ($i = 0; $i < $numFields; $i++) {
					$record[$fieldNames[$i]] = $row[$i];
				}
				$record['extended_amount'] = floatval($record['quantity']) * floatval($record['unit_price']);
				$records[] = $record;
			}
			fclose($handle);
		}

		$errorRows = [];
		foreach ($records as $index => $record) {
			if (!$this->jobsModel->upload_file_detail($record)) {
				$errorRows[] = $index + 1;
			}
		}

		$this->generalModel->updateRecord([
			'table'      => 'param_jobs',
			'primaryKey' => 'id_job',
			'id'         => $idJob,
			'column'     => 'flag_upload_details',
			'value'      => 1,
		]);

		if (!empty($errorRows)) {
			session()->setFlashdata('retornoError', 'El archivo se cargó pero hay errores en los registros: ' . implode(', ', $errorRows));
		} else {
			session()->setFlashdata('retornoExito', 'The file was uploaded successfully.');
		}

		$this->update_job_detail($idJob);

		return redirect()->to(base_url('jobs/job_detail/' . $idJob));
	}

	/**
	 * Update Job Details Percentage
	 * @since 11/1/2023
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function update_job_detail($idJob)
	{
		$arrParam   = ['idJob' => $idJob];
		$jobDetails = $this->generalModel->get_job_detail($arrParam);
		$total      = $this->jobsModel->sumExtendedAmount($arrParam);

		$this->jobsModel->updateJobDetail($jobDetails ?: [], $total);
	}

	/**
	 * Cargo modal - Job Detail
	 * @since 6/1/2023
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function cargarModalJobDetail()
	{
		$data = [];
		$data['information'] = null;
		$data['claimPercentage'] = null;
		$identification = $this->request->getPost("identification");
		$data["idJob"] = "";
		if($identification != ""){
			$porciones = explode("-", $identification);
			$data["idJob"] = $porciones[0];
			$data["chapterNumber"] = $porciones[1];
			$data["chapterName"] = $porciones[2];
		}

		$idJobDetail = $this->request->getPost("idJobDetail");
		$data["idJobDetail"] = $idJobDetail;

		if (!empty($idJobDetail) && $idJobDetail !== 'x') {
			$arrParam = ["idJobDetail" => $data["idJobDetail"]];
			$data['information'] = $this->generalModel->get_job_detail($arrParam);

			//calcuate %
			$claimCost = $this->generalModel->get_total_cost_by_job_detail($arrParam);
			$totalAmount = $data['information'][0]['extended_amount'];

			if ($totalAmount > 0) {
				$data['claimPercentage'] = $percentage = round(($claimCost / $totalAmount) * 100, 2);
			} else {
				$data['claimPercentage'] = 0;
			}
			$data["idJob"] = $data['information'][0]['fk_id_job'];
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Jobs\Views\job_detail_modal', $data));
	}
	
	/**
	 * Update Job Detail
	 * @since 6/1/2023
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function save_job_detail()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		
		$msj = $id 
			? "You have updated a Register!!" 
			: "You have added a new Register!!";

		$data = [];
		$data["idRecord"] = $post['hddIdJob'] ?? null;

		if ($this->jobsModel->saveJobDetail($post)) {
			$this->update_job_detail($data["idRecord"]);
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Job Details View
	 * @since 16/05/2025
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function load_claims_view()
	{
		$data['claims'] = $this->generalModel->get_claims_by_id_job_detail([
			'idJobDetail' => $this->request->getPost('idJobDetail'),
		]);

		return $this->response
			->setContentType('text/html')
			->setBody(view('App\Modules\Jobs\Views\claims_view', $data));
	}

	/**
	 * Delete All Deteails Info
	 * @since 31/1/2024
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function delete_job_detail_info()
	{
		$idJob = $this->request->getPost('hddIdJob');
		$result = true;
		//DELETE PREVIOS INFORMATION
		$arrParam = array(
			"table" => "job_details",
			"primaryKey" => "fk_id_job ",
			"id" => $idJob
		);
		if(!$this->generalModel->deleteRecord($arrParam)){
			$result = false;
		}

		//DELETE expenses
		$arrParam = array(
			"idJob" => $idJob
		);
		if(!$this->jobsModel->deleteWOExpenses($arrParam)){
			$result = false;
		}

		//update FLAGS in table param_job
		if(!$this->jobsModel->resetFlagsParamJob($arrParam)){
			$result = false;
		}

		//update WO expenses flag in table workorders
		$arrParam = array(
			"table" => "workorder",
			"primaryKey" => "fk_id_job",
			"id" => $idJob,
			"column" => "expenses_flag",
			"value" => 2
		);
		if(!$this->generalModel->updateRecord($arrParam)){
			$result = false;
		}

		//update submodule flag for that job
		$arrParam = array(
			"idJob" => $idJob,
			"table" => "workorder_personal"
		);
		if(!$this->jobsModel->updateWOSubmoduleFlag($arrParam)){
			$result = false;
		}
		$arrParam['table'] = "workorder_materials";
		if(!$this->jobsModel->updateWOSubmoduleFlag($arrParam)){
			$result = false;
		}
		$arrParam['table'] = "workorder_equipment";
		if(!$this->jobsModel->updateWOSubmoduleFlag($arrParam)){
			$result = false;
		}
		$arrParam['table'] = "workorder_ocasional";
		if(!$this->jobsModel->updateWOSubmoduleFlag($arrParam)){
			$result = false;
		}
		$arrParam['table'] = "workorder_receipt";
		if(!$this->jobsModel->updateWOSubmoduleFlag($arrParam)){
			$result = false;
		}

		if ($result) {
			session()->setFlashdata('retornoExito', "All information has been reset.");
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Error occurred. Please contact support');
		}

		return redirect()->to(base_url('jobs/job_detail/' . $idJob));
	}

	/**
	 * Job Detail List
	 * @since 12/05/2025
	 * @author BMOTTAG
	 * @review 08/05/2026 - new CI4 version
	 */
	public function charged_lic($idJob, $status)
	{
		$data['jobInfo']     = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['status']      = $status;

		$chapterList = $this->generalModel->get_chapter_list(['idJob' => $idJob]);

		if ($chapterList) {
			foreach ($chapterList as &$chapter) {
				$chapter['jobDetails'] = $this->generalModel->get_job_detail([
					'idJob'         => $idJob,
					'chapterNumber' => $chapter['chapter_number'],
					'status'        => $status,
				]);
			}
		}

		$data['chapterList'] = $chapterList;

		return $this->renderTopOnly('App\Modules\Jobs\Views\job_lic_charged', $data);
	}

	/**
	 * Envio de mensaje
	 * @since 26/11/2020
	 * @author BMOTTAG
	 * @review 14/05/2026 - new CI4 version
	 */
	public function sendSMSworkerJSO($idJSOworker)
	{
		$smsService = new \App\Libraries\SmsService();

		$information = $this->jobsModel->get_jso_workers(['idJobJsoWorker' => $idJSOworker]);
		$idJobJso    = $information[0]['fk_id_job_jso'];
		$JSOInfo     = $this->jobsModel->get_jso(['idJobJso' => $idJobJso]);
		$idJob       = $JSOInfo[0]['id_job'];

		$rawPhone = preg_replace('/[^0-9]/', '', $information[0]['works_phone_number'] ?? '');

		if (strlen($rawPhone) !== 10) {
			session()->setFlashdata('retornoError', '<strong>Error!</strong> Worker phone number is invalid or not registered.');
			return redirect()->to(base_url('jobs/add_jso/' . $idJob . '/' . $idJobJso));
		}

		$mensaje  = date('F j, Y', strtotime($JSOInfo[0]['date_issue_jso']));
		$mensaje .= "\n" . $JSOInfo[0]['job_description'];
		$mensaje .= "\nClick the link below to sign the JOB SITE ORIENTATION.";
		$mensaje .= "\n\n" . base_url('jobs/jso_worker_view/' . $idJSOworker);

		try {
			$smsService->send('+1' . $rawPhone, $mensaje);
			session()->setFlashdata('retornoExito', 'You have send the SMS to the worker.');
		} catch (\Exception $e) {
			session()->setFlashdata('retornoError', '<strong>Error!</strong> SMS could not be sent: ' . $e->getMessage());
		}

		return redirect()->to(base_url('jobs/add_jso/' . $idJob . '/' . $idJobJso));
	}

	/**
	 * JSO workorder view to sign
	 * @since 26/11/2020
	 * @author BMOTTAG
	 * @review 14/05/2026 - new CI4 version
	 */
	public function jso_worker_view($idJSOworker)
	{
		//Worker info		
		$data['information'] = $this->jobsModel->get_jso_workers(["idJobJsoWorker" => $idJSOworker]);

		//JSO info
		$idJobJso = $data['information'][0]['fk_id_job_jso'];
		$data['JSOInfo'] = $this->jobsModel->get_jso(["idJobJso" => $idJobJso]);

		return $this->render('App\Modules\Jobs\Views\jso_worker_view', $data);
	}

}