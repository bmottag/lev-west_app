<?php
namespace App\Modules\Dayoff\Controllers;

use App\Controllers\BaseController;
use App\Modules\Dayoff\Models\DayoffModel;
use App\Models\GeneralModel;

class Dayoff extends BaseController
{
    protected $dayoffModel;
    protected $generalModel;
    
    public function __construct()
    {
        $this->dayoffModel   = new DayoffModel();
        $this->generalModel   = new GeneralModel();
    }

	/**
	 * List Day Off
     * @since 7/12/2016
     * @author BMOTTAG
	 * @review 04/04/2026 - new CI4 version
	 */
	public function index()
	{
			$data['dayoffList'] = $this->generalModel->get_day_off(["idEmployee" => true]);
			return $this->render('App\Modules\Dayoff\Views\dayoff_list', $data);
	}
	
    /**
     * Cargo modal- formulario dayoff
     * @since 07/12/2016
	 * @review 04/04/2026 - new CI4 version
     */
	public function cargarModal()
	{
		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Dayoff\Views\modal_dayoff'));
	}

	/**
	 * Save dayoff
     * @since 04/12/2016
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function save_dayoff()
	{
		$post = $this->request->getPost();

		//verify ---- 24 hours in advaced for Family/medical appointment and 72 hours for regular
		$type =  $post['type'];//1: Family/medical appointment; 2: Regular
		
		date_default_timezone_set('America/Phoenix');
		$today = date("Y-m-d H:i:s"); 
		$date =  $post['date'];

		// Validar formato YYYY-MM-DD
		if (!$date || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
			return $this->response->setJSON([
				"status" => "error",
				"message" => "Invalid date format. Please select a valid date."
			]);
		}

		// Validar que realmente sea una fecha válida (ej. 2025-02-30 no existe)
		list($y, $m, $d) = explode('-', $date);
		if (!checkdate((int)$m, (int)$d, (int)$y)) {
			return $this->response->setJSON([
				"status" => "error",
				"message" => "The date is not valid. Please select a correct one."
			]);
		}

		//START hours calculation
		$minutes = (strtotime($today)-strtotime($date))/60;
		$minutes = abs($minutes);  
		$minutes = round($minutes);

		$hours = $minutes/60;
		
		if($type == 1 && 24 > $hours ){
			return $this->response->setJSON([
				"status" => "error",
				"message" => "Error!!. You need more than 24 hours to request the dayoff."
			]);
		}
		if($type == 2 && 72 > $hours ){
			return $this->response->setJSON([
				"status" => "error",
				"message" => "Error!!. You need more than 72 hours to request the dayoff."
			]);
		}

		$data = [];

		if ($idDayoff = $this->dayoffModel->add_dayoff($post)) {

			//revisar si se envia correo o se envia mensaje de texto y a quien se le envia
			$configuracionAlertas = $this->generalModel->get_notifications_access(["idNotification" => ID_NOTIFICATION_DAYOFF]);

			if ($configuracionAlertas) {
				$dayoffInfo = $this->generalModel->get_day_off(["idDayoff" => $idDayoff]);

				$tipo = match ((int)$dayoffInfo[0]['id_type_dayoff']) {
					1 => 'Family/medical appointment',
					2 => 'Regular',
					default => 'Unknown',
				};

				$subject     = "Day Off App - Lev West";
				$observation = esc($post['observation']);

				$emailBody  = "<p>There is a new request for a Day Off:</p>";
				$emailBody .= "<strong>Employee: </strong>" . esc($dayoffInfo[0]["name"]);
				$emailBody .= "<br><strong>Type: </strong>" . $tipo;
				$emailBody .= "<br><strong>Date of dayoff: </strong>" . esc($dayoffInfo[0]["date_dayoff"]);
				$emailBody .= "<br><strong>Observation: </strong>" . $observation;
				$emailBody .= "<p>Follow the link to approve or deny the Day Off: </p>";

				$smsMessage  = "Day Off App - Lev West";
				$smsMessage .= "\nThere is a new request for a Day Off:";
				$smsMessage .= "\nEmployee: " . $dayoffInfo[0]["name"];
				$smsMessage .= "\nType: " . $tipo;
				$smsMessage .= "\nDate of dayoff: " . $dayoffInfo[0]["date_dayoff"];
				$smsMessage .= "\nObservation: " . $dayoffInfo[0]["observation"];
				$smsMessage .= "\nFollow the link to review: ";

				send_notification($configuracionAlertas, $subject, $emailBody, $smsMessage, 'external/aprove_day_off', $idDayoff);
			}

			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'Thank you. The ADMIN will review your request.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}
	
	/**
	 * List Day Off, for ADMIN
     * @since 8/12/2016
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function newDayoffList()
	{
			$data["state"] = 1;//new
			$data['dayoffList'] = $this->generalModel->get_day_off(['state' => 1]);
			
			$data["tittle"] = "New Request";
			$data["icon"] = "fa-hand-o-right";
			return $this->render('App\Modules\Dayoff\Views\admin_dayoff_list', $data);
	}
	
    /**
     * Cargo modal - formulario aprobar dayoff
     * @since 8/12/2016
	 * @review 03/04/2026 - new CI4 version
     */
    public function cargarModalApproved() 
	{
		$data["idDayoff"] = $this->request->getPost("idDayoff");
		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Dayoff\Views\modal_approved', $data));
    }
	
	/**
	 * Save approved
     * @since 8/12/2016
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */

	public function save_approved()
	{
		$post = $this->request->getPost();
		$state =  $post['state'];
		$observation =  $post['observation'];
		
		if($state == 3 && $observation ==''){
			return $this->response->setJSON([
				"status" => "error",
				"message" => "You must write an observation."
			]);
		}

		$data = [];
		if ($this->dayoffModel->update_dayoff($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'Information saved successfully!!');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}
	
	/**
	 * List Day Off, for ADMIN
     * @since 8/12/2016
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function approvedDayoffList()
	{
			$data["state"] = 2;//approved
			$data['dayoffList'] = $this->generalModel->get_day_off(['state' => 2]);
			
			$data["tittle"] = "Approved Request";
			$data["icon"] = "fa-hand-o-up";
			return $this->render('App\Modules\Dayoff\Views\admin_dayoff_list', $data);
	}
	
	/**
	 * List Day Off, for ADMIN
     * @since 8/12/2016
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function deniedDayoffList()
	{
			$data["state"] = 3;//Denied
			$data['dayoffList'] = $this->generalModel->get_day_off(['state' => 3]);
			
			$data["tittle"] = "Denied Request";
			$data["icon"] = "fa-hand-o-down";
			return $this->render('App\Modules\Dayoff\Views\admin_dayoff_list', $data);
	}

}
