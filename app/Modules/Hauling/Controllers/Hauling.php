<?php
namespace App\Modules\Hauling\Controllers;

use App\Controllers\BaseController;
use App\Modules\Hauling\Models\HaulingModel;
use App\Models\GeneralModel;

class Hauling extends BaseController
{
    protected $haulingModel;
    protected $generalModel;
    
    public function __construct()
    {
        $this->haulingModel   = new HaulingModel();
        $this->generalModel   = new GeneralModel();
    }

	/**
	 * Form Add Hauling
	 * @since 11/12/2016
	 * @author BMOTTAG
	 * @review 16/04/2026 - new CI4 version
	 */
	public function add_hauling($id = 'x')
	{
		$data = [
			'information' => null,
			'HaulingClose' => false,
			'companyList' => $this->generalModel->get_company(["isHauling" => true]),
			'truckTypeList' => $this->generalModel->get_basic_search([
				"table" => "param_truck_type",
				"order" => "truck_type",
				"id" => "x"
			]),
			'materialTypeList' => $this->generalModel->get_basic_search([
				"table" => "param_material_type",
				"order" => "material",
				"id" => "x"
			]),
			'jobs' => $this->generalModel->get_job(['state' => 1]),
			'paymentList' => $this->generalModel->get_basic_search([
				"table" => "param_payment",
				"order" => "payment",
				"id" => "x"
			]),
			'truckList' => $this->generalModel->get_basic_search([
				"table" => "param_vehicle",
				"order" => "unit_number",
				"id" => "x"
			]),
		];

		if ($id != 'x') {

			$data['information'] = $this->haulingModel->get_hauling_byId($id);

			if (!$data['information']) {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}

			$idWorkorder = $data['information']['fk_id_workorder'];

			$workorder = $this->generalModel->get_basic_search([
				"table" => "workorder",
				"order" => "id_workorder",
				"column" => "id_workorder",
				"id" => $idWorkorder
			]);

			$data['workorder'] = $workorder
				? $workorder[0]['id_workorder'] . ' - ' . $workorder[0]['observation']
				: 'Not Work Order';
		}

		return $this->render('App\Modules\Hauling\Views\form_add_hauling', $data);
	}

	/**
	 * Company list
	 * @since 4/2/2017
	 * @author BMOTTAG
	 * @review 16/04/2026 - new CI4 version
	 */
	public function companyList()
	{
		$CompanyType = $this->request->getPost('CompanyType');

		if($CompanyType == 1){
			$lista = $this->generalModel->get_company(["company_type" => $CompanyType]);
		}else{
			$lista = $this->generalModel->get_company(["isHauling" => true]);
		}

		echo "<option value=''>Select...</option>";
		if ($lista) {
			foreach ($lista as $fila) {
				echo "<option value='" . $fila["id_company"] . "' >" . $fila["company_name"] . "</option>";
			}
		}
	}

	/**
	 * Trucks´list by company
	 * @since 12/12/2016
	 * @author BMOTTAG
	 * @review 16/04/2026 - new CI4 version
	 */
	public function truckList()
	{
		$identificador = $this->request->getPost('identificador');
		$lista = $this->haulingModel->get_trucks_by_id($identificador);
		echo "<option value=''>Select...</option>";
		if ($lista) {
			foreach ($lista as $fila) {
				echo "<option value='" . $fila["id_truck"] . "' >" . $fila["unit_number"] . "</option>";
			}
		}
	}

	/**
	 * Save hauling
	 * @since 16/12/2016
	 * @author BMOTTAG
	 * @review 16/04/2026 - new CI4 version
	 */
	public function save_hauling()
	{
		$post = $this->request->getPost();

		$result = $this->haulingModel->saveHauling($post);

		if ($result['status']) {

			session()->setFlashdata(
				'retornoExito',
				'You have saved your hauling record, remember to sign and get the contractor signature!!'
			);

			return $this->response->setJSON([
				'status' => 'success',
				'idHauling' => $result['idHauling']
			]);

		} else {

			session()->setFlashdata('retornoError', $result['message']);

			return $this->response->setJSON([
				'status' => 'error',
				'message' => $result['message']
			]);
		}
	}

	/**
	 * Work Order list
	 * @since 9/1/2025
	 * @author BMOTTAG
	 * @review 16/04/2026 - new CI4 version
	 */
	public function list_by_job_code()
	{
		$jobCode = $this->request->getPost('jobCode');
		$id_workorder = $this->haulingModel->list_by_job_code($jobCode);

		echo $id_workorder;
	}

	/**
	 * Work Order list
	 * @since 9/1/2025
	 * @author BMOTTAG
	 * @review 16/04/2026 - new CI4 version
	 */
	public function woList()
	{
		$jobCode = $this->request->getPost('jobCode');
		$list = $this->haulingModel->get_wo_job_code($jobCode);
		echo "<option value=''>Select...</option>";
		if ($list) {
			foreach ($list as $fila) {
				echo "<option value='" . $fila["id_workorder"] . "' >" . $fila["id_workorder"] . " - " . $fila["observation"] . "</option>";
			}
		}
	}

	/**
	 * Update hauling state
	 * @since 6/2/2017
	 * @author BMOTTAG
	 * @review 05/05/2026 - new CI4 version
	 */
	public function update_hauling_state()
	{
		$post = $this->request->getPost();
		$idHauling = $post['hddId'] ?? null;
		$state = $post['delete'] ? 3 : 2;

		$data = [];
		$arrParam = [
			"table" => "hauling",
			"primaryKey" => "id_hauling",
			"id" => $idHauling,
			"column" => "state",
			"value" => $state
		];
		if ($this->generalModel->updateRecord($arrParam)) {
			if ($state == 3) {
				$this->haulingModel->deleteOccasionalByHauling($idHauling);
			}
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have closed the Hauling Report');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Signature
	 * @since 9/1/201/
	 * @author BMOTTAG
	 */
	public function save_signature()
	{
		$imageData = $this->request->getPost('image'); 
		$idHauling = $this->request->getPost('extraValue'); 
		$type = $this->request->getPost('id'); 
		$fileName = $type . "_" . $idHauling . ".png";
		$filePath = WRITEPATH . '../public/images/signature/hauling/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('error', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->generalModel->updateRecord([
				"table" => "hauling",
				"primaryKey" => "id_hauling",
				"id" => $idHauling,
				"column" => $type . "_signature",
				"value" => "images/signature/hauling/" . $fileName
			]);
			return redirect()->back()->with('retornoExito', 'Signature saved successfully.');
		} else {
			return redirect()->back()->with('retornoError', 'Error saving signature.');
		}
	}

	/**
	 * Evio de correo a la empresa
	 * @since 23/1/2017
	 * @author BMOTTAG
	 * @review 13/05/2026 - new CI4 version
	 */
	public function email($id)
	{
		$infoHauling = $this->haulingModel->get_hauling_byId($id);

		$subject = "Hauling Information App - Lev West";
		$user    = $infoHauling["contact"];
		$to      = $infoHauling["email"];

		$emailBody  = "<p>The following is the Hauling Information:</p>";
		$emailBody .= "<strong>Haul report number: </strong>" . esc($infoHauling["id_hauling"]);
		$emailBody .= "<br><strong>Company: </strong>" . esc($infoHauling["company_name"]);
		$emailBody .= "<br><strong>Truck: </strong>" . esc($infoHauling["unit_number"]);
		$emailBody .= "<br><strong>Truck Type: </strong>" . esc($infoHauling["truck_type"]);
		$emailBody .= "<br><strong>Material Type: </strong>" . esc($infoHauling["material"]);
		$emailBody .= "<br><strong>Job Code/Name: </strong>" . esc($infoHauling["job_from"]);
		$emailBody .= "<br><strong>To Site: </strong>" . esc($infoHauling["job_to"]);
		$emailBody .= "<br><strong>Time In: </strong>" . esc($infoHauling["time_in"]);
		$emailBody .= "<br><strong>Time Out: </strong>" . esc($infoHauling["time_out"]);
		$emailBody .= "<br><strong>Payment: </strong>" . esc($infoHauling["payment"]);
		$emailBody .= "<br><strong>Comments: </strong>" . esc($infoHauling["comments"]);
		$emailBody .= "<br><br><a href='" . base_url('report/generaHaulingPDF/x/x/x/x/' . $infoHauling['id_hauling']) . "' target='_blank'>Download Report</a>";

		if ($infoHauling["contractor_signature"]) {
			$emailBody .= "<br><br><strong><a href='" . base_url($infoHauling["contractor_signature"]) . "'>View Subcontractor Signature</a></strong><br>";
		}

		if ($infoHauling["vci_signature"]) {
			$emailBody .= "<br><br><strong><a href='" . base_url($infoHauling["vci_signature"]) . "'>View V-Contracting Signature</a></strong><br>";
		}

		$fullEmail = "<html><head><title>{$subject}</title></head><body>"
			. "<p>Dear {$user}:</p>"
			. $emailBody
			. "<p>Cordially,</p><p><strong>Lev West</strong></p>"
			. "</body></html>";

		$emailService = new \App\Libraries\EmailService();
		$emailService->sendRaw($to, $subject, $fullEmail);

		$configuracionAlertas = $this->generalModel->get_notifications_access(['idNotification' => ID_NOTIFICATION_HAULING]);
		if ($configuracionAlertas) {
			send_notification($configuracionAlertas, $subject, $emailBody, '');
		}

		$nota = 'You have send an email to <strong>' . esc($infoHauling["company_name"]) . '</strong> with the information.';

		session()->setFlashdata('retornoExito', $nota);
		return redirect()->to(base_url('hauling/add_hauling/' . $id));
	}


}
