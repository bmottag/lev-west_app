<?php
namespace App\Modules\Template\Controllers;

use App\Controllers\BaseController;
use App\Modules\Template\Models\TemplateModel;
use App\Models\GeneralModel;
use App\Libraries\PdfBuilder;

class Template extends BaseController
{
    protected $templateModel;
    protected $generalModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->templateModel = new TemplateModel();
        $this->generalModel  = new GeneralModel();
    }

    /**
     * Generar plantillas
     * @since 14/6/2017
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function templates()
    {
        $data['info'] = $this->generalModel->get_basic_search([
            'table' => 'templates',
            'order' => 'template_name',
            'id'    => 'x',
        ]);

        return $this->render('App\Modules\Template\Views\templates', $data);
    }

    /**
     * Cargo modal - formulario template
     * @since 14/6/2017
     * @review 07/05/2026 - new CI4 version
     */
    public function cargarModalTemplate()
    {
        $data['information'] = false;
        $data['idTemplate']  = $this->request->getPost('idTemplate');

        if ($data['idTemplate'] !== 'x') {
            $data['information'] = $this->generalModel->get_basic_search([
                'table'  => 'templates',
                'order'  => 'id_template',
                'column' => 'id_template',
                'id'     => $data['idTemplate'],
            ]);
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Template\Views\templates_modal', $data));
    }

    /**
     * Update Template
     * @since 14/6/2017
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function save_template()
    {
        $post       = $this->request->getPost();
        $idTemplate = $post['hddId'] ?? '';

        $msj  = $idTemplate !== '' ? 'You have updated a Template!!' : 'You have added a new Template!!';
        $data = [];

        if ($this->templateModel->saveTemplate($post, (int) $this->session->get('id'))) {
            $data['status']   = 'success';
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['status']   = 'error';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Form Upload Workers
     * @since 14/6/2017
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function use_template($id = 'x')
    {
        $data['template'] = $this->generalModel->get_basic_search([
            'table'  => 'templates',
            'order'  => 'id_template',
            'column' => 'id_template',
            'id'     => $id,
        ]);

        if ($id !== 'x') {
            $data['workersList']    = $this->generalModel->get_user(['state' => 1]);
            $data['templateWorkers'] = $this->templateModel->get_templates_workers($id);
        }

        return $this->render('App\Modules\Template\Views\form_use_template', $data);
    }

    /**
     * Form Add Workers
     * @since 14/6/2017
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function add_workers_template($idTemplate)
    {
        if (empty($idTemplate)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ERROR!!! - You are in the wrong place.');
        }

        $workersList = $this->generalModel->get_user(['state' => 1]);

		$data = [
			'workersList' => $workersList,
			'idTemplate' => $idTemplate
		];

        return $this->render('App\Modules\Template\Views\form_add_workers', $data);
    }

    /**
     * Save workers
     * @since 14/6/2017
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function save_temlate_workers()
    {
        $post       = $this->request->getPost();
        $idTemplate = $post['hddId'] ?? '';
        $workers    = $post['workers'] ?? [];
        $data       = [];

        if ($this->templateModel->add_template_worker($idTemplate, $workers)) {
            $data['status']     = 'success';
            $data['idTemplate'] = $idTemplate;
            session()->setFlashdata('retornoExito', 'You have added the Workers, remember to get the signature of each one.');
        } else {
            $data['status']     = 'error';
            $data['idTemplate'] = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }

    /**
     * Signature
     * @since 14/6/2017
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
	public function save_signature()
	{
		$imageData = $this->request->getPost('image'); 
		$id = $this->request->getPost('id'); 
		$templateId = $this->request->getPost('extraValue'); 
		$fileName = "worker_" . $id . ".png";
		$filePath = WRITEPATH . '../public/images/signature/template/' . $fileName;

		if(!$imageData){
			return redirect()->back()->with('error', 'No signature provided.');
		}

		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		$imageData = str_replace(' ', '+', $imageData);

		if(!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0755, true);

		if(file_put_contents($filePath, base64_decode($imageData))){
			$this->generalModel->updateRecord([
                    'table'      => 'template_used_workers',
                    'primaryKey' => 'id_template_used_worker',
                    'id'         => $id,
                    'column'     => 'signature',
                    'value'      => 'images/signature/template/' . $fileName
			]);
			return redirect()->back()->with('retornoExito', 'Signature saved successfully.');
		} else {
			return redirect()->back()->with('retornoError', 'Error saving signature.');
		}
	}

    /**
     * Delete template worker
     * @review 07/05/2026 - new CI4 version
     */
    public function deleteTemplateWorker($idTemplateWorker, $idTemplate)
    {
        if (empty($idTemplateWorker) || empty($idTemplate)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ERROR!!! - You are in the wrong place.');
        }

        if ($this->generalModel->deleteRecord([
            'table'      => 'template_used_workers',
            'primaryKey' => 'id_template_used_worker',
            'id'         => $idTemplateWorker,
        ])) {
            session()->setFlashdata('retornoExito', 'You have deleted one worker.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('template/use_template/' . $idTemplate));
    }

    /**
     * Save one worker to the template
     * @review 07/05/2026 - new CI4 version
     */
    public function save_one_worker()
    {
        $post       = $this->request->getPost();
        $idTemplate = $post['hddId'] ?? '';

        if ($this->templateModel->saveOneWorker($post)) {
            session()->setFlashdata('retornoExito', 'You have added one Worker.');
        } else {
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return redirect()->to(base_url('template/use_template/' . $idTemplate));
    }

    /**
     * Generate Template Report in PDF
     * @param int $idTemplate
     * @since 2/7/2017
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function generaTemplatePDF($idTemplate)
    {
        $data['info']    = $this->templateModel->get_template(['idTemplate' => $idTemplate]);
        $data['workers'] = $this->templateModel->get_templates_workers($idTemplate);

        $builder = new PdfBuilder();
        $pdf     = $builder->create('Template report');

        $html = view('App\Modules\Template\Views\reporte_pdf', $data);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('template_' . $idTemplate . '.pdf', 'I'));
    }

    /**
     * Valves
     * @since 15/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function valves()
    {
        $data['info'] = $this->generalModel->get_basic_search([
            'table' => 'valves',
            'order' => 'valve_number',
            'id'    => 'x',
        ]);

        return $this->render('App\Modules\Template\Views\valves', $data);
    }

    /**
     * Cargo modal - formulario valves
     * @since 15/04/2025
     * @review 07/05/2026 - new CI4 version
     */
    public function cargarModalValve()
    {
        $data['information'] = false;
        $data['idValve']     = $this->request->getPost('idValve');

        if ($data['idValve'] !== 'x') {
            $data['information'] = $this->generalModel->get_basic_search([
                'table'  => 'valves',
                'order'  => 'id_valve',
                'column' => 'id_valve',
                'id'     => $data['idValve'],
            ]);
        }

        return $this->response
            ->setContentType('text/html')
            ->setBody(view('App\Modules\Template\Views\valves_modal', $data));
    }

    /**
     * Update Valves
     * @since 15/04/2025
     * @author BMOTTAG
     * @review 07/05/2026 - new CI4 version
     */
    public function save_valve()
    {
        $post    = $this->request->getPost();
        $idValve = $post['hddId'] ?? '';

        $msj  = $idValve !== '' ? 'You have updated a Valve!!' : 'You have added a new Valve!!';
        $data = [];

        if ($this->templateModel->saveValve($post, (int) $this->session->get('id'))) {
            $data['status']   = 'success';
            $data['idRecord'] = $idValve;
            session()->setFlashdata('retornoExito', $msj);
        } else {
            $data['status']   = 'error';
            $data['idRecord'] = '';
            session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
        }

        return $this->response->setJSON($data);
    }
}
