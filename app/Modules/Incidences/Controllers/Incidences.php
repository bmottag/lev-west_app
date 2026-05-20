<?php
namespace App\Modules\Incidences\Controllers;

use App\Controllers\BaseController;
use App\Modules\Incidences\Models\IncidencesModel;
use App\Models\GeneralModel;
use TCPDF;

class Incidences extends BaseController
{
    protected $incidencesModel;
    protected $generalModel;
    
    public function __construct()
    {
        $this->incidencesModel   = new IncidencesModel();
        $this->generalModel   = new GeneralModel();
    }

	/**
	 * Near Miss list
     * @since 17/3/2017
     * @author BMOTTAG
	 * @review 08/04/2026 - new CI4 version
	 */
	public function near_miss($idJob)
	{
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['nearMissInfo'] = $this->incidencesModel->get_near_miss_by_idUser(["jobId" => $idJob]);
		return $this->render('App\Modules\Incidences\Views\near_miss_list', $data);
	}

	/**
	 * Form Near Miss
     * @since 17/3/2017
     * @author BMOTTAG
	 * @review 08/04/2026 - new CI4 version
	 */
	public function add_near_miss($idJob, $id = 'x')
	{
		$data = [];
		$data['information'] = null;
		$data['deshabilitar'] = '';
			
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);

		//incident type list
		$arrParam = [
			"table" => "param_incident_type",
			"order" => "id_incident_type",
			"id" => "x"
		];
		$data['incidentType'] = $this->generalModel->get_basic_search($arrParam);

		//workers list
		$arrWorkers = [
			"table" => "user",
			"order" => "first_name, last_name",
			"column" => "state",
			"id" => 1
		];
		$data['workersList'] = $this->generalModel->get_basic_search($arrWorkers);

		$arrJob = [
			"table" => "param_jobs",
			"order" => "job_description",
			"column" => "state",
			"id" => 1
		];
		$data['jobs'] = $this->generalModel->get_basic_search($arrJob);

		//si envio el id, entonces busco la informacion 
		if ($id != 'x') 
		{	
			$data['information'] = $this->incidencesModel->get_near_miss_by_idUser(["idNearMiss" => $id]);
			if (!$data['information']) { 
				throw new \Exception('ERROR!!! - You are in the wrong place.');
			}

			//busco lista de personal involucrado, para el formulario de NEAR MISS (1)
			$arrIncident = [
				'idIncident' => $id,
				'form' => 1
			];
			$data['personsInvolved'] = $this->incidencesModel->get_persons_involved($arrIncident);
			}			

		return $this->render('App\Modules\Incidences\Views\form_near_miss', $data);
	}

	/**
	 * Save near miss
     * @since 28/3/2017
     * @author BMOTTAG
	 * @review 08/04/2026 - new CI4 version
	 */
	public function save_near_miss()
	{
		$post = $this->request->getPost();
		$idReport = $post['hddIdentificador'] ?? null;
		$data = [];
		$data["idJob"] = $post['jobName'] ?? null;
		if ($idNearmiss = $this->incidencesModel->add_near_miss($post)) {
			$data["idNearmiss"] = $idNearmiss;
			if ($idReport == '') {
				$this->email_to($idNearmiss, 1);//si es un reporte nuevo envio correo
			}	
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', "You have saved the Near Miss Report, continue uploading the information.");
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

    /**
     * Safe Person Involved
     * @since 24/4/2021
     * @author BMOTTAG
	 * @review 08/04/2026 - new CI4 version
     */
    public function save_person_involved() 
	{
		$post = $this->request->getPost();
		$idIncident = $this->request->getPost('hddId');
		$formIdentifier = $this->request->getPost('hddFormIdentifier');
		$idJob = $this->request->getPost('hddIdJob');
		
		if($formIdentifier==1){
			$path = 'incidences/add_near_miss/' . $idJob . '/' . $idIncident;
		}else{
			$path = 'incidences/add_incident/' . $idJob . '/' . $idIncident;
		}

		if ($this->incidencesModel->savePersonInvolved($post)) {
			session()->setFlashdata('retornoExito', 'You have added a Person Involved.');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}
		return redirect()->to(base_url($path));
    }

    /**
     * Delete personl involved
     * @since 24/4/2021
     * @author BMOTTAG
	 * @review 08/04/2026 - new CI4 version
     */
    public function deleteIncidentPersonInvolved($idPerson, $idIncident, $formIdentifier, $idJob) 
	{
		$arrParam = [
			"table" => "incidence_incident_person",
			"primaryKey" => "id_incident_person",
			"id" => $idPerson
		];
		if ($this->generalModel->deleteRecord($arrParam)) {
			session()->setFlashdata('retornoExito', 'You have deleted a Person Involved.');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}
		$path = $formIdentifier==1 ? 'incidences/add_near_miss/' . $idJob . '/' . $idIncident : 'incidences/add_incident/' . $idJob . '/' . $idIncident;
		return redirect()->to(base_url($path));
    }

	/**
	 * Signature
	 * param $idPersonal: llave principal del formulario
     * @since 15/5/2017
     * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function save_signature_person_involved()
	{
		$imageData = $this->request->getPost('image'); 
		$idPersonal = $this->request->getPost('id'); 
		$fileName = 'personsInvolved_' . $idPersonal. '.png';
		$filePath = WRITEPATH . '../public/images/signature/safety/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('error', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->generalModel->updateRecord([
				"table" => "incidence_incident_person",
				"primaryKey" => "id_incident_person ",
				"id" => $idPersonal,
				"column" => "person_signature",
				"value" => 'images/signature/safety/' . $fileName
			]);
			return redirect()->back()->with('retornoExito', 'Signature saved successfully.');
		} else {
			return redirect()->back()->with('retornoError', 'Error saving signature.');
		}
	}

	/**
	 * Signature
	 * param $incidencesType: near_miss / incident / accident
	 * param $userType: supervisor / coordinator
	 * param $idFormulario: llave principal del formulario
     * @since 15/5/2017
     * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
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
	 * Incident list
     * @since 15/5/2017
     * @author BMOTTAG
	 * @review 08/04/2026 - new CI4 version
	 */
	public function incident($idJob)
	{
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
		$data['incidentInfo'] = $this->incidencesModel->get_incident_by(["jobId" => $idJob]);
		return $this->render('App\Modules\Incidences\Views\incident_list', $data);
	}

	/**
	 * Form Incident
     * @since 15/5/2017
     * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function add_incident($idJob, $id = 'x')
	{
		$data = [];
		$data['information'] = null;
		$data['deshabilitar'] = '';
			
		$data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);

		//incident type list
		$arrParam = [
			"table" => "param_incident_type",
			"order" => "id_incident_type",
			"id" => "x"
		];
		$data['incidentType'] = $this->generalModel->get_basic_search($arrParam);

		//workers list
		$arrWorkers = [
			"table" => "user",
			"order" => "first_name, last_name",
			"column" => "state",
			"id" => 1
		];
		$data['workersList'] = $this->generalModel->get_basic_search($arrWorkers);

		$arrJob = [
			"table" => "param_jobs",
			"order" => "job_description",
			"column" => "state",
			"id" => 1
		];
		$data['jobs'] = $this->generalModel->get_basic_search($arrJob);
			
		//si envio el id, entonces busco la informacion 
		if($id != 'x')
		{			
			$data['information'] = $this->incidencesModel->get_incident_by(['idIncident' => $id]);
			if (!$data['information']) { 
				throw new \Exception('ERROR!!! - You are in the wrong place.');
			}

			//busco lista de personal involucrado, para el formulario de INCIDENT (2)
			$arrIncident= [
				'idIncident' => $id,
				'form' => 2
			];
			$data['personsInvolved'] = $this->incidencesModel->get_persons_involved($arrIncident);
		}			

		return $this->render('App\Modules\Incidences\Views\form_incident', $data);
	}

	/**
	 * Save incident
     * @since 15/5/2017
     * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function save_incident()
	{
		$post = $this->request->getPost();
		$idReport = $post['hddIdentificador'] ?? null;
		$data = [];
		$data["idJob"] = $post['jobName'] ?? null;
		if ($idRecord = $this->incidencesModel->add_incident($post)) {
			$data["idRecord"] = $idRecord;
			if ($idReport == '') {
				$this->email_to($idRecord, 2);//si es un reporte nuevo envio correo
			}	
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have saved the Incident Report, continue uploading the information!!');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Generate Report in PDF
	 * @param int $idIncident
	 * @param int $type
     * @since 3/7/2017
     * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function generaPDF($idIncident, $type)
	{
		$pdf = new TCPDF();

		$pdf->SetCreator('Lev West');
		$pdf->SetAuthor('Lev West');
		$pdf->SetTitle('Incidences Report');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// 👇 espacio para logo
		$pdf->SetMargins(10, 25, 10);
		$pdf->SetAutoPageBreak(TRUE, 10);

		$pdf->SetFont('dejavusans', '', 8);

		$vista = null;

		switch ($type) {
			case 1:
				$data['info'] = $this->incidencesModel->get_near_miss_by_idUser([
					"idNearMiss" => $idIncident
				]);
				$data['title'] = "NEAR MISS REPORT";
				$vista = "incidences/reporte_near_miss_pdf";
				break;

			case 2:
				$data['info'] = $this->incidencesModel->get_incident_by([
					"idIncident" => $idIncident
				]);
				$data['title'] = "INCIDENT/ACCIDENT REPORT";
				$vista = "incidences/reporte_incident_pdf";
				break;
		}

		if (!$vista) {
			throw new \Exception("Invalid report type");
		}

		$data['personsInvolved'] = $this->incidencesModel->get_persons_involved([
			'idIncident' => $idIncident,
			'form' => $type
		]);

		$pdf->AddPage();

		// LOGO
		$logo = FCPATH . 'images/logo.png';

		if (is_file($logo)) {
			$pdf->Image($logo, 10, 8, 30);
		}

		$html = view($vista, $data);

		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();

		$project = $data['info'][0]["job_description"] ?? 'report';
		$filename = 'incident_report_' . preg_replace('/\s+/', '_', $project) . '.pdf';

		return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setBody($pdf->Output($filename, 'I'));
	}

	/**
	 * Envio de mensaje para firmar INCIDENCES
	 * param $idFormulario: id del formulario
	 * param $incidencesType: 1:Near Miss / 2: Incident
     * @since 25/4/2021
     * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function sendSMSIncidencesPersons($idFormulario, $incidencesType)
	{
		$smsService = new \App\Libraries\SmsService();

		switch ($incidencesType) {
			case 1:
				$data['info'] = $this->incidencesModel->get_near_miss_by_idUser(["idNearMiss" => $idFormulario]);
				break;
			case 2:
				$data['info'] = $this->incidencesModel->get_incident_by(["idIncident" => $idFormulario]);
				break;
		}

		$data['infoPersonsInvolved'] = $this->incidencesModel->get_persons_involved([
			'idIncident'  => $idFormulario,
			'form'        => $incidencesType,
			'movilNumber' => true,
		]);

		if (empty($data['infoPersonsInvolved'])) {
			session()->setFlashdata('retornoError', 'No persons found');
			$path = $incidencesType == 1
				? 'incidences/add_near_miss/' . $idFormulario
				: 'incidences/add_incident/' . $idFormulario;
			return redirect()->to(base_url($path));
		}

		$mensaje  = "Lev West INCIDENCES - " . date('F j, Y', strtotime($data['info'][0]['date_issue']));
		$mensaje .= "\n" . $data['info'][0]['job_description'];
		$mensaje .= "\nFollow the link, read and sign.";
		$mensaje .= "\n\n";
		$mensaje .= $incidencesType == 1
			? base_url("incidences/review_near_miss/" . $idFormulario)
			: base_url("incidences/review_incident/" . $idFormulario);

		$numbers = array_map(function ($p) {
			return '+1' . $p['person_movil_number'];
		}, $data['infoPersonsInvolved']);

		$path = $incidencesType == 1
			? 'incidences/add_near_miss/' . $idFormulario . '/' . $incidencesType
			: 'incidences/add_incident/' . $idFormulario . '/' . $incidencesType;

		try {
			$smsService->sendBulk($numbers, $mensaje);
			session()->setFlashdata('retornoExito', 'You have send the SMS to Persons Involved.');
		} catch (\Exception $e) {
			session()->setFlashdata('retornoError', '<strong>Error!</strong> SMS could not be sent: ' . $e->getMessage());
		}

		return redirect()->to(base_url($path));
	}

	/**
	 * Subcontractors view to sign
     * @since 15/4/2021
     * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function review_incident($idFormulario)
	{			
		$data['information'] = $this->incidencesModel->get_incident_by(['idIncident' => $idFormulario]);

		//busco lista de personal involucrado, para el formulario de INCIDENT (2)
		$arrParam = array(
			'idIncident' => $idFormulario,
			'form' => 2
		);
		$data['personsInvolved'] = $this->incidencesModel->get_persons_involved($arrParam);

		return $this->renderTopOnly('App\Modules\Incidences\Views\review_incident', $data);
	}

	/**
	 * Evio de correo
     * @since 15/5/2017
     * @author BMOTTAG
	 * @review 13/04/2026 - new CI4 version
	 */
	public function email_to($idIncidence, $incidencesType)
	{
		switch($incidencesType){
			case 1: //near miss report
				$model = "get_near_miss_by_idUser";
				$subjet = "Near Miss Report";
				$arrParam = array('idNearMiss' => $idIncidence);
				break;
			case 2: //incident report
				$model = "get_incident_by";
				$subjet = "Incident Report App - Lev West";
				$arrParam = array('idIncident' => $idIncidence);
				break;
		}
		$infoIncident = $this->incidencesModel->$model($arrParam);

		$configuracionAlertas = $this->generalModel->get_notifications_access(['idNotification' => ID_NOTIFICATION_INCIDENT]);

		if ($configuracionAlertas) {
			$emailBody  = "<p>It is a new " . $subjet . ":</p>";
			$emailBody .= "<strong>Report by: </strong>" . esc($infoIncident[0]["name"]);

			$smsMessage  = "Incident Notification App - Lev West";
			$smsMessage .= "\nIt is a new " . $subjet . ":";
			$smsMessage .= "\nReport by: " . $infoIncident[0]["name"];
			if ($incidencesType == 3) {
				$smsMessage .= "\nBrief explanation: " . $infoIncident[0]["brief_explanation"];
			} else {
				$smsMessage .= "\nWhat happened: " . $infoIncident[0]["what_happened"];
			}

			send_notification($configuracionAlertas, $subjet, $emailBody, $smsMessage);
		}
	}


}