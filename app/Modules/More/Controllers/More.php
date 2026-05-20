<?php
namespace App\Modules\More\Controllers;

use App\Controllers\BaseController;
use App\Modules\More\Models\MoreModel;
use App\Models\GeneralModel;
use App\Libraries\PdfBuilder;

class More extends BaseController
{
    protected $moreModel;
    protected $generalModel;

    public function __construct()
    {
        $this->moreModel   = new MoreModel();
        $this->generalModel = new GeneralModel();
    }

	/**
	 * environmental list
	 * @since 10/1/2018
	 * @author BMOTTAG
     * @review 27/04/2026 - new CI4 version
	 */
    public function environmental($idJob)
    {
        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
        $data['information'] = $this->moreModel->get_environmental(['idJob' => $idJob]);

        return $this->render('App\Modules\More\Views\environmental_list', $data);
    }

	/**
	 * Form enviromental
	 * @since 10/1/2018
	 * @author BMOTTAG
     * @review 27/04/2026 - new CI4 version
	 */
    public function add_environmental($idJob, $idEnvironmental = 'x')
    {
        $data['information'] = false;
        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
        $data['workersList'] = $this->generalModel->get_user(['state' => 1]);

        if ($idEnvironmental != 'x') {
            $data['information'] = $this->moreModel->get_environmental(['idJob' => $idJob]);
            if (!$data['information']) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Record not found.');
            }
        }

        return $this->render('App\Modules\More\Views\form_environmental', $data);
    }

	/**
	 * save_environmental
	 * @since 13/1/2018
	 * @author BMOTTAG
     * @review 27/04/2026 - new CI4 version
	 */
    public function save_environmental()
    {
        $post    = $this->request->getPost();
        $idUser  = $this->session->get('id');
        $data    = ['idRecord' => $post['hddIdJob'] ?? null];

        if ($idEnvironmental = $this->moreModel->add_environmental($post, $idUser)) {
            $data["status"] = "success";
            $data['idEnvironmental'] = $idEnvironmental;
            session()->setFlashdata('retornoExito', 'You have saved the Environmental Site Inspection, continue uploading the information!!');
        } else {
            $data['status']          = 'error';
            $data['idEnvironmental'] = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

	/**
	 * Signature
	 * param $type: supervisor / manager
	 * param $idEnvironmental: llave principal del formulario
	 * @since 13/1/2018
	 * @author BMOTTAG
     * @review 27/04/2026 - new CI4 version
	 */
	public function add_signature_esi()
	{
		$imageData = $this->request->getPost('image'); 
		$id = $this->request->getPost('extraValue'); 
		$type = $this->request->getPost('otherValue'); 

        $fileName = $type . '_' . $id . ".png";
        $arrParam = [
            "table" => "job_environmental",
            "primaryKey" => "id_job_environmental",
            "id" => $id,
            "column" => $type . '_signature',
            "value" => 'images/signature/esi/' . $fileName
        ];

		$filePath = WRITEPATH . '../public/images/signature/esi/' . $fileName;

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
	 * Generate Environmental Report in PDF
	 * @param int $idJob
	 * @since 14/1/2018
	 * @author BMOTTAG
     * @review 27/04/2026 - new CI4 version
	 */
    public function generaEnvironmentalPDF($idJob)
    {
        $data['info'] = $this->moreModel->get_environmental(['idJob' => $idJob]);

        $builder = new PdfBuilder();
        $pdf = $builder->create('ESI Report');

        $html = view('App\Modules\More\Views\reporte_esi', $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();
        if (ob_get_length()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('esi_' . $idJob . '.pdf', 'I'));        
    }

	/**
	 * PPE inspection list
	 * @since 15/1/2018
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function ppe_inspection()
    {
        $data['information'] = $this->moreModel->get_ppe_inspection([]);
        return $this->render('App\Modules\More\Views\ppe_inspection_list', $data);
    }

	/**
	 * Cargo modal - pps inspection
	 * @since 15/1/2018
     * @review 28/04/2026 - new CI4 version
	 */
    public function cargarModalPPEInspection()
    {
        $data['information']     = false;
        $data['idPPEInspection'] = $this->request->getPost('idPPEInspection');

        if ($data['idPPEInspection'] != 'x') {
            $data['information'] = $this->generalModel->get_basic_search([
                'table'  => 'templates',
                'order'  => 'id_template',
                'column' => 'id_template',
                'id'     => $data['idPPEInspection'],
            ]);
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\More\Views\ppe_modal', $data));
    }

	/**
	 * Save PPE INSPECTION
	 * @since 15/1/2018
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function save_ppe_inspection()
    {
        $post            = $this->request->getPost();
        $idUser          = $this->session->get('id');
        $userRol         = $this->session->get('rol');
        $idPPEInspection = $post['hddId'] ?? '';

        $msj = $idPPEInspection != '' ? 'You have updated a PPE Inspection!!' : 'You have added a new PPE Inspection';
        $data = [];

        if ($id = $this->moreModel->savePPEInspection($post, $idUser, $userRol)) {
            $data["status"] = "success";
            $data['idRecord'] = $id;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['status'] = 'error';
            $data['idRecord'] = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

	/**
	 * Form PPE INSPECTION
	 * @since 15/1/2018
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function add_ppe_inspection($idPPEInspection = 'x')
    {
        $data['information'] = false;

        if ($idPPEInspection != 'x') {
            $data['information']          = $this->moreModel->get_ppe_inspection(['idPPEInspection' => $idPPEInspection]);
            $data['ppeInspectionWorkers'] = $this->moreModel->get_ppe_inspection_workers($idPPEInspection);
            $data['workersList']          = $this->generalModel->get_user(['state' => 1]);

            if (!$data['information']) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Record not found.');
            }
        }

        return $this->render('App\Modules\More\Views\form_ppe_inspection', $data);
    }

	/**
	 * Signature
	 * param $typo: inspector / worker
	 * param $idPPEInspection: llave principal del formulario
	 * param $idWorker: llave principal del trabajador
	 * @since 22/1/2018
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
	public function save_signature_ppe()
	{
		$imageData = $this->request->getPost('image'); 
		$id = $this->request->getPost('extraValue'); 
		$type = $this->request->getPost('otherValue'); 
		
		switch ($type) {
			case "worker":
				$fileName = $type . '_' . $id . '.png';
				$arrParam = [
					"table" => "ppe_inspection_workers",
					"primaryKey" => "id_ppe_inspection_worker",
					"id" => $id,
					"column" => "signature",
					"value" => 'images/signature/ppe_inspection/' . $fileName
				];
				break;

			case "inspector":
				$fileName = $type . '_' . $id . '.png';
				$arrParam = [
					"table" => "ppe_inspection",
					"primaryKey" => "id_ppe_inspection",
					"id" => $id,
					"column" => "inspector_signature",
					"value" => 'images/signature/ppe_inspection/' . $fileName
				];
				break;

			default:
				return $this->response->setJSON([
					"status" => "error",
					"message" => "Invalid user type"
				]);
		}
		$filePath = WRITEPATH . '../public/images/signature/ppe_inspection/' . $fileName;

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
	 * Form Add Workers PPE INSPECTION
	 * @since 20/1/2018
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function add_workers_ppe_inspection($idPPEInspection)
    {
        if (empty($idPPEInspection)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid parameters.');
        }

        $data['workersList']    = $this->generalModel->get_user(['state' => 1]);
        $data['idPPEInspection'] = $idPPEInspection;

        return $this->render('App\Modules\More\Views\form_add_workers', $data);
    }

	/**
	 * Save worker
	 * @since 21/1/2018
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function save_ppe_inspection_workers()
    {
        $post            = $this->request->getPost();
        $idPPEInspection = $post['hddIdPPEInpection'] ?? null;
        $data            = ['idRecord' => $idPPEInspection];
        $workers         = $post['workers'] ?? [];

        if ($this->moreModel->add_ppe_inspection_worker($idPPEInspection, $workers)) {
            $data["status"] = "success";
            session()->setFlashdata('retornoExito', 'You have added the Workers, remember to get the signature of each one.');
        } else {
            $data['status'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

	/**
	 * Delete PPE INSPECTION worker
     * @review 28/04/2026 - new CI4 version
	 */
    public function deleteInspectionWorker()
    {
        $identificador = $this->request->getPost('identificador');
        $porciones     = explode('-', $identificador);
        $idPPEInspection       = $porciones[0];
        $idPPEInspectionWorker = $porciones[1];

        $data = ['idRecord' => $idPPEInspection];
        $arrParam = ['table' => 'ppe_inspection_workers', 'primaryKey' => 'id_ppe_inspection_worker', 'id' => $idPPEInspectionWorker];

        if ($this->generalModel->deleteRecord($arrParam)) {
            $data["status"] = "success";
            session()->setFlashdata('retornoExito', 'You have deleted one worker.');
        } else {
            $data['status'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

	/**
	 * Update inspection
	 * para editar el estado de la inspeccion
	 * @since 29/1/2018
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function updateInspection()
    {
        $post            = $this->request->getPost();
        $idPPEInspection = $post['hddIdPPEInspection'] ?? null;

        if ($this->moreModel->update_ppe_inspection_worker($post)) {
            session()->setFlashdata('retornoExito', 'You have updated the Inspection');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('more/add_ppe_inspection/' . $idPPEInspection));
    }

	/**
	 * Safe one worker for the inspection
     * @review 28/04/2026 - new CI4 version
	 */
    public function add_one_worker()
    {
        $post            = $this->request->getPost();
        $idPPEInspection = $post['hddIdPPEInspection'] ?? null;

        if ($this->moreModel->addOneWorker($post)) {
            session()->setFlashdata('retornoExito', 'You have added one Worker.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('more/add_ppe_inspection/' . $idPPEInspection));
    }

	/**
	 * Generate PPE INSPECTION Report in PDF
	 * @param int $idPPEInspection
	 * @since 29/1/2018
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function generaPPEInspectionPDF($idPPEInspection)
    {
        $data['info']                 = $this->moreModel->get_ppe_inspection(['idPPEInspection' => $idPPEInspection]);
        $data['ppeInspectionWorkers'] = $this->moreModel->get_ppe_inspection_workers($idPPEInspection);

        $builder = new PdfBuilder();
        $pdf = $builder->create('PPE INSPECTION REPORT');

        $html = view('App\Modules\More\Views\reporte_ppe_inspection', $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();
        if (ob_get_length()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('ppe_inspection_' . $idPPEInspection . '.pdf', 'I'));        
    }

	/**
	 * confined space entry permit list
	 * @since 13/1/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function confined($idJob)
    {
        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
        $data['information'] = $this->generalModel->get_confined_space(['idJob' => $idJob]);

        return $this->render('App\Modules\More\Views\confined_list', $data);
    }

	/**
	 * Form confined space entry permit
	 * @since 14/1/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function add_confined($idJob, $idConfined = 'x')
    {
        $data['information'] = false;
        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
        $data['workersList'] = $this->generalModel->get_user(['state' => 1]);

        if ($idConfined != 'x') {
            $data['information']    = $this->generalModel->get_confined_space(['idConfined' => $idConfined]);
            $data['confinedWorkers'] = $this->moreModel->get_confined_workers($idConfined, null);

            if (!$data['information']) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Record not found.');
            }
        }

        return $this->render('App\Modules\More\Views\form_confined', $data);
    }

	/**
	 * Save confined space entry permit
	 * @since 13/1/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function save_confined()
    {
        $post    = $this->request->getPost();
        $idUser  = $this->session->get('id');
        $userRol = $this->session->get('rol');
        $data    = ['idRecord' => $post['hddIdJob'] ?? null];

        if ($idConfined = $this->moreModel->add_confined($post, $idUser, $userRol)) {
            $data['idConfined'] = $idConfined;    
            $data["status"] = "success";
            session()->setFlashdata('retornoExito', 'You have saved the Confined Space Entry Permit, continue uploading the information. Add Worker(s) in charge of entry and signatures at the end of the form.');
        } else {
            $data['status']    = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

	/**
	 * Form confined space entry permit WORKERS
	 * @since 5/2/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function confined_workers($idJob, $idConfined)
    {
        $data['information']    = false;
        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
        $data['workersList']    = $this->generalModel->get_user(['state' => 1]);

        if ($idConfined != 'x') {
            $data['information']    = $this->generalModel->get_confined_space(['idConfined' => $idConfined]);
            $data['confinedWorkers'] = $this->moreModel->get_confined_workers($idConfined, 2);

            if (!$data['information']) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Record not found.');
            }
        }

        return $this->render('App\Modules\More\Views\form_confined_workers', $data);
    }

	/**
	 * Form confined space entry permit WORKERS
	 * @since 5/2/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function workers_site($idJob, $idConfined)
    {
        $data['information']    = false;
        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
        $data['workersList']    = $this->generalModel->get_user(['state' => 1]);

        if ($idConfined != 'x') {
            $data['information']    = $this->generalModel->get_confined_space(['idConfined' => $idConfined]);
            $data['confinedWorkers'] = $this->moreModel->get_confined_workers($idConfined, 1);

            if (!$data['information']) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Record not found.');
            }
        }

        return $this->render('App\Modules\More\Views\form_confined_workers_site', $data);
    }

	/**
	 * Form Add Workers confined
	 * @since 20/1/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function add_workers_confined($idJob, $idConfined, $wos)
    {
        $data['workersList'] = $this->generalModel->get_user(['state' => 1]);
        $data['idConfined']  = $idConfined;
        $data['idJob']       = $idJob;
        $data['wos']         = $wos;

        return $this->render('App\Modules\More\Views\form_add_workers_confined', $data);
    }

	/**
	 * Save worker
	 * @since 20/1/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function save_confined_workers()
    {
        $post      = $this->request->getPost();
        $idConfined = $post['hddIdConfined'] ?? null;
        $idJob      = $post['hddIdJob'] ?? null;
        $wos        = $post['hddWOS'] ?? null;
        $workers    = $post['workers'] ?? [];

        $data = ['idRecord' => $wos == 2 ? 'confined_workers/' . $idJob . '/' . $idConfined : 'workers_site/' . $idJob . '/' . $idConfined];

        if ($this->moreModel->add_confined_worker($idConfined, $wos, $workers)) {
            $data["status"] = "success";
            session()->setFlashdata('retornoExito', 'You have added the Workers, remember to get the signature of each one.');
        } else {
            $data['status'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

	/**
	 * Delete confined worker
     * @review 28/04/2026 - new CI4 version
	 */
    public function deleteConfinedWorker($idJob, $idConfined, $idConfinedWorker)
    {
        if (empty($idJob) || empty($idConfined) || empty($idConfinedWorker)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid parameters.');
        }

        $arrParam = ['table' => 'job_confined_workers', 'primaryKey' => 'id_job_confined_worker', 'id' => $idConfinedWorker];
        if ($this->generalModel->deleteRecord($arrParam)) {
            session()->setFlashdata('retornoExito', 'You have deleted one worker.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('more/confined_workers/' . $idJob . '/' . $idConfined));
    }

	/**
	 * Delete confined worker
     * @review 28/04/2026 - new CI4 version
	 */
    public function deleteConfinedWorkerSite($idJob, $idConfined, $idConfinedWorker)
    {
        if (empty($idJob) || empty($idConfined) || empty($idConfinedWorker)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid parameters.');
        }

        $arrParam = ['table' => 'job_confined_workers', 'primaryKey' => 'id_job_confined_worker', 'id' => $idConfinedWorker];
        if ($this->generalModel->deleteRecord($arrParam)) {
            session()->setFlashdata('retornoExito', 'You have deleted one worker.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('more/workers_site/' . $idJob . '/' . $idConfined));
    }

	/**
	 * Safe one worker to Confined Space Entry
     * @review 28/04/2026 - new CI4 version
	 */
    public function confined_One_Worker()
    {
        $post      = $this->request->getPost();
        $idJob     = $post['hddIdJob'] ?? null;
        $idConfined = $post['hddIdConfined'] ?? null;

        if ($this->moreModel->confinedSaveOneWorker($post)) {
            session()->setFlashdata('retornoExito', "You have added one Worker. Don't forget to sign.");
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('more/confined_workers/' . $idJob . '/' . $idConfined));
    }

    public function confined_worker_site()
    {
        $post      = $this->request->getPost();
        $idJob     = $post['hddIdJob'] ?? null;
        $idConfined = $post['hddIdConfined'] ?? null;

        if ($this->moreModel->confinedSaveWorkerOnSite($post)) {
            session()->setFlashdata('retornoExito', "You have added one Worker. Don't forget to sign.");
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('more/workers_site/' . $idJob . '/' . $idConfined));
    }

	/**
	 * Signature
	 * param $type: supervisor / worker
	 * param $idConfined: llave principal del formulario
	 * param $idWorker: llave principal del trabajador
	 * @since 20/1/2020
	 * @author BMOTTAG
	 */
	public function save_signature_confined()
	{
		$imageData = $this->request->getPost('image'); 
		$id = $this->request->getPost('extraValue'); 
		$type = $this->request->getPost('otherValue'); 
		
		switch ($type) {
			case "worker":
				$fileName = $type . '_' . $id . '.png';
                $this->moreModel->updateConfinedWorkerInOut(['id' => $id, 'column' => 'date_time_in']);
				$arrParam = [
					"table" => "job_confined_workers",
					"primaryKey" => "id_job_confined_worker",
					"id" => $id,
					"column" => "signature",
					"value" => 'images/signature/confined/' . $fileName
				];
				break;

			case "worker_out":
				$fileName = $type . '_' . $id . '.png';
                $this->moreModel->updateConfinedWorkerInOut(['id' => $id, 'column' => 'date_time_out']);
				$arrParam = [
					"table" => "job_confined_workers",
					"primaryKey" => "id_job_confined_worker",
					"id" => $id,
					"column" => "signature_out",
					"value" => 'images/signature/confined/' . $fileName
				];
				break;
                
			case "post_entry":
				$fileName = $type . '_' . $id . '.png';
				$arrParam = [
					"table" => "job_confined",
					"primaryKey" => "id_job_confined",
					"id" => $id,
					"column" => "post_entry_signature",
					"value" => 'images/signature/confined/' . $fileName
				];
				break;

			case "authorization":
				$fileName = $type . '_' . $id . '.png';
				$arrParam = [
					"table" => "job_confined",
					"primaryKey" => "id_job_confined",
					"id" => $id,
					"column" => "authorization_signature",
					"value" => 'images/signature/confined/' . $fileName
				];
				break;

			case "cancellation":
				$fileName = $type . '_' . $id . '.png';
				$arrParam = [
					"table" => "job_confined",
					"primaryKey" => "id_job_confined",
					"id" => $id,
					"column" => "cancellation_signature",
					"value" => 'images/signature/confined/' . $fileName
				];
				break;

			default:
				return $this->response->setJSON([
					"status" => "error",
					"message" => "Invalid user type"
				]);
		}
		$filePath = WRITEPATH . '../public/images/signature/confined/' . $fileName;

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
	 * Update datos trabajdores
	 * @since 5/2/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function update_confined_worker()
    {
        $post      = $this->request->getPost();
        $idJob     = $post['hddIdJob'] ?? null;
        $idConfined = $post['hddIdConfined'] ?? null;

        if ($this->moreModel->saveConfinedWorker($post)) {
            session()->setFlashdata('retornoExito', 'You have updated the record!!');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('more/confined_workers/' . $idJob . '/' . $idConfined));
    }

	/**
	 * Form re testing
	 * @since 4/2/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function re_testing($idJob, $idConfined)
    {
        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
        $data['information'] = $this->generalModel->get_confined_space(['idConfined' => $idConfined]);
        $data['info']        = $this->moreModel->get_confined_re_testing(['idConfined' => $idConfined]);

        return $this->render('App\Modules\More\Views\form_confined_re_testing', $data);
    }

	/**
	 * Cargo modal - formulario re-testing
	 * @since 4/2/2020
     * @review 28/04/2026 - new CI4 version
	 */
    public function cargarModalRetesting()
    {
        $data['information'] = false;
        $data['idConfined']  = $this->request->getPost('idConfined');
        $data['idRetesting'] = $this->request->getPost('idRetesting');

        if ($data['idRetesting'] != 'x') {
            $result = $this->moreModel->get_confined_re_testing(['idRetesting' => $data['idRetesting']]);
            $data['information'] = $result;
            $data['idConfined']  = $result[0]['fk_id_job_confined'];
        }

        $infoConfined  = $this->generalModel->get_confined_space(['idConfined' => $data['idConfined']]);
        $data['idJob'] = $infoConfined[0]['fk_id_job'];

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\More\Views\re_testing_modal', $data));
    }

	/**
	 * ADD retesting
	 * @since 4/2/2020
     * @review 28/04/2026 - new CI4 version
	 */
    public function save_re_testing()
    {
        $post      = $this->request->getPost();
        $idConfined = $post['hddIdConfined'] ?? null;
        $idJob      = $post['hddIdJob'] ?? null;
        $data       = ['idRecord' => $idJob . '/' . $idConfined];

        if ($this->moreModel->saveRetesting($post)) {
            $data["status"] = "success";
            session()->setFlashdata('retornoExito', 'You have saved the ENVIRONMENTAL CONDITIONS - RE TESTING');
        } else {
            $data['status'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }
    
	/**
	 * Form post entry inspection
	 * @since 6/2/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function post_entry($idJob, $idConfined)
    {
        $data['jobInfo'] = $this->generalModel->get_job(['idJob' => $idJob]);
        $data['workersList'] = $this->generalModel->get_user(['state' => 1]);
        $data['information'] = $this->generalModel->get_confined_space(['idConfined' => $idConfined]);

        return $this->render('App\Modules\More\Views\form_confined_post_entry', $data);
    }

	/**
	 * Save post entry inspection
	 * @since 6/2/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function save_post_entry()
    {
        $post      = $this->request->getPost();
        $idJob     = $post['hddIdJob'] ?? null;
        $idConfined = $post['hddConfined'] ?? null;
        $data       = ['idRecord' => $idJob . '/' . $idConfined];

        if ($this->moreModel->save_post_entry($post)) {
            $data["status"] = "success";
            session()->setFlashdata('retornoExito', 'You have saved the Post-entry Inspection.');
        } else {
            $data['status'] = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

	/**
	 * Generate Template Report in PDF
	 * @param int $idConfined
	 * @since 29/1/2020
	 * @author BMOTTAG
     * @review 28/04/2026 - new CI4 version
	 */
    public function generaConfinedPDF($idConfined)
    {
        $data['info']          = $this->generalModel->get_confined_space(['idConfined' => $idConfined]);
        $data['confinedWorkers'] = $this->moreModel->get_confined_workers($idConfined, 2);
        $data['WorkersOnSite'] = $this->moreModel->get_confined_workers($idConfined, 1);
        $data['retesting']     = $this->moreModel->get_confined_re_testing(['idConfined' => $idConfined]);

        $builder = new PdfBuilder();
        $pdf = $builder->create('Confined space entry permit report');

        $html = view('App\Modules\More\Views\reporte_confined', $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();
        if (ob_get_length()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('confined_' . $idConfined . '.pdf', 'I'));        
    }

}
