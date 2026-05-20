<?php
namespace App\Modules\Enlaces\Controllers;

use App\Controllers\BaseController;
use App\Modules\Enlaces\Models\EnlacesModel;
use App\Models\GeneralModel;

class Enlaces extends BaseController
{
    protected $enlacesModel;
	protected $generalModel;
    
    public function __construct()
    {
        $this->enlacesModel   = new EnlacesModel();
		$this->generalModel   = new GeneralModel();
    }

	/**
	 * Listado Menu
     * @since 30/3/2020
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function menu()
	{
		$data['info'] = $this->generalModel->get_menu([]);
		return $this->render('App\Modules\Enlaces\Views\menu', $data);
	}
	
    /**
     * Cargo modal - formulario menu
     * @since 30/3/2020
	 * @review 03/04/2026 - new CI4 version
     */
	public function cargarModalMenu()
	{
		$data = [];
		$data['information'] = null;

		$idMenu = $this->request->getPost("idMenu");
		$data["idMenu"] = $idMenu;

		if (!empty($idMenu) && $idMenu !== 'x') {
			$data['information'] = $this->generalModel->get_menu(["idMenu" => $data["idMenu"]]);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Enlaces\Views\menu_modal', $data));
	}
	
	/**
	 * Update Menu
     * @since 30/3/2020
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function save_menu()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$msj = $id 
			? "You have updated a Menu link!!" 
			: "You have added a new Menu link!!";

		$data = [];

		if ($this->enlacesModel->saveMenu($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Delete Link
	 * @since 27/02/2025
	 * @review 03/04/2026 - new CI4 version
	 */
	public function delete_menu()
	{
		$post = $this->request->getPost();

		$idMenu = $post['identificador'];
		$data = [];

		$arrParam = [
			"table" => "param_menu_permisos",
			"primaryKey" => "fk_id_menu",
			"id" => $idMenu
		];
		if ($this->generalModel->deleteRecord($arrParam)) {

			$arrParam = [
				"table" => "param_menu_links",
				"primaryKey" => "fk_id_menu",
				"id" => $idMenu
			];
			$this->generalModel->deleteRecord($arrParam);

			$arrParam = [
				"table" => "param_menu",
				"primaryKey" => "id_menu",
				"id" => $idMenu
			];
			$this->generalModel->deleteRecord($arrParam);

			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'The record has been deleted.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Links list
     * @since 31/3/2020
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function links()
	{
		$data['info'] = $this->generalModel->get_links([]);
		return $this->render('App\Modules\Enlaces\Views\links', $data);
	}
	
    /**
     * Cargo modal - formulario link
     * @since 31/3/2020
	 * @review 03/04/2026 - new CI4 version
     */
	public function cargarModalLink()
	{
		$data = [];
		$data['information'] = null;

		$idLink = $this->request->getPost("idLink");
		$data["idLink"] = $idLink;

		$data['menuList'] = $this->generalModel->get_menu(["columnOrder" => "menu_name"]);

		if (!empty($idLink) && $idLink !== 'x') {
			$data['information'] = $this->generalModel->get_links(["idLink" => $data["idLink"]]);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Enlaces\Views\links_modal', $data));
	}
		
	/**
	 * Update link
     * @since 31/3/2020
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function save_link()
	{
		$post = $this->request->getPost();

		$idLink = $post['hddId'] ?? null;
		$newIdMenu = $post['id_menu'];
		$oldIdMenu = $post['hddIdMenu'];

		$msj = $idLink 
			? "You have updated a Link!!" 
			: "You have added a new Link!!";

		$data = [];

		if ($this->enlacesModel->saveLink($post)) {
			if ($idLink != '' && $newIdMenu != $oldIdMenu) {
				//Update fk_id_menu in param_menu_permisos
				$arrParam = array(
					"table" => "param_menu_permisos ",
					"primaryKey" => "fk_id_link",
					"id" => $idLink,
					"column" => "fk_id_menu",
					"value" => $newIdMenu
				);
				$this->generalModel->updateRecord($arrParam);
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
	 * Delete Link
	 * @since 27/02/2025
	 * @review 03/04/2026 - new CI4 version
	 */
	public function delete_link()
	{
		$post = $this->request->getPost();

		$idLink = $post['identificador'];
		$data = [];

		$arrParam = [
			"table" => "param_menu_permisos",
			"primaryKey" => "fk_id_link",
			"id" => $idLink
		];
		if ($this->generalModel->deleteRecord($arrParam)) 
		{
			$arrParam = [
				"table" => "param_menu_links",
				"primaryKey" => "id_link",
				"id" => $idLink
			];
			$this->generalModel->deleteRecord($arrParam);

			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'The record has been deleted.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}
	
	/**
	 * Access list
     * @since 31/3/2020
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function role_access()
	{
		$data['info'] = $this->generalModel->get_role_access([]);
		return $this->render('App\Modules\Enlaces\Views\role_access', $data);
	}
	
    /**
     * Cargo modal - formulario Acesss
     * @since 31/3/2020
	 * @review 03/04/2026 - new CI4 version
     */
	public function cargarModalRoleAccess()
	{
		$data = [];
		$data['information'] = null;
		$data['linkList'] = null;

		$idPermiso = $this->request->getPost("idPermiso");
		$data["idPermiso"] = $idPermiso;

		$arrParam = [
			"columnOrder" => "menu_name",
			"menuState" => 1
		];
		$data['menuList'] = $this->generalModel->get_menu($arrParam);
		
		$data['roles'] = $this->generalModel->get_roles([]);

		if (!empty($idPermiso) && $idPermiso !== 'x') {
			$data['information'] = $this->generalModel->get_role_access(["idPermiso" => $data["idPermiso"]]);

			//busca lista de links para el menu guardado
			$data['linkList'] = $this->generalModel->get_links(["idMenu" => $data['information'][0]['fk_id_menu']]);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Enlaces\Views\role_access_modal', $data));
	}
    	
	/**
	 * Update access
     * @since 31/3/2020
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */

	public function save_role_access()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$msj = $id 
			? "You have updated an Access!!" 
			: "You have added a new Access!!";

		$data = [];


		//para verificar si ya existe este permiso
		$arrParam = [
			"idMenu" => $post['id_menu'],
			"idLink" => $post['id_link'],
			"idRole" => $post['id_rol']
		];		
		if ($this->generalModel->get_role_access($arrParam)) {
			return $this->response->setJSON([
				"status" => "error",
				"message" => "Error. The access already exist."
			]);
		}

		if ($this->enlacesModel->saveRoleAccess($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Delete link acces
     * @since 1/4/2020
	 */
	public function delete_role_access()
	{
		$post = $this->request->getPost();

		$idPermiso = $post['identificador'];
		$data = [];

		$arrParam = [
			"table" => "param_menu_permisos",
			"primaryKey" => "id_permiso",
			"id" => $idPermiso
		];
		if ($this->generalModel->deleteRecord($arrParam)) 
		{
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'The record has been deleted.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}
	
	/**
	 * Listado de enlaces
     * @since 2/4/2018
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function videos()
	{
		$data['info'] = $this->generalModel->get_links(["linkType" => 4]);
		return $this->render('App\Modules\Enlaces\Views\videos_links', $data);
	}
	
    /**
     * Cargo modal - formulario enlace
     * @since 2/4/2018
	 * @review 03/04/2026 - new CI4 version
     */
	public function cargarModalVideoLinks()
	{
		$data = [];
		$data['information'] = null;

		$idLink = $this->request->getPost("idLink");
		$data["idLink"] = $idLink;

		if (!empty($idLink) && $idLink !== 'x') {
			$data['information'] = $this->generalModel->get_links(["idLink" => $data["idLink"]]);
		}

		return $this->response
					->setContentType('text/html')
					->setBody(view('App\Modules\Enlaces\Views\videos_modal', $data));
	}
	
	/**
	 * Update Enlace
     * @since 2/4/2018
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function save_video()
	{
		$post = $this->request->getPost();

		$id = $post['hddId'] ?? null;
		$msj = $id 
			? "You have updated a Link!!" 
			: "You have added a new Link!!";

		$data = [];

		if ($this->enlacesModel->saveVideo($post)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', $msj);
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Listado de enlaces para los manuales
     * @since 27/4/2018
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function manuals()
	{
		$data['info'] = $this->generalModel->get_links(["linkType" => 5]);
		return $this->render('App\Modules\Enlaces\Views\manuals_links', $data);
	}
	
	/**
	 * Form Upload Locates 
     * @since 27/4/2018
     * @author BMOTTAG
	 * @review 03/04/2026 - new CI4 version
	 */
	public function manuals_form($idLink = 'x', $error = '')
	{			
			$data['information'] = null;

			if ($idLink != 'x' && $idLink != '') {
				$data['information'] = $this->generalModel->get_links(["idLink" => $idLink]);
			}
			
			$data['error'] = $error; //se usa para mostrar los errores al cargar la imagen 			
			return $this->render('App\Modules\Enlaces\Views\manuals_form', $data);
	}
	
	/**
	 * FUNCIÓN PARA SUBIR el archivo
	 * @review 03/04/2026 - new CI4 version
	 */
	public function do_upload_manual()
	{
		$post = $this->request->getPost();
		$idLink = $post['hddId'];

		$file = $this->request->getFile('userfile');

		// Validar si existe archivo
		if (!$file || !$file->isValid()) {
			return $this->manuals_form($idLink, 'No file selected or upload error.');
		}

		// Validaciones
		if ($file->getExtension() !== 'pdf') {
			return $this->manuals_form($idLink, 'Only PDF files are allowed.');
		}

		if ($file->getSize() > 3000000) { // ~3MB
			return $this->manuals_form($idLink, 'File too large.');
		}

		// nombre original
		$fileName = $file->getClientName();

		// limpiar nombre
		$fileName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $fileName);

		// carpeta destino
		$uploadPath = ROOTPATH . 'public/files/';

		// mover archivo
		$file->move($uploadPath, $fileName);

		// 👉 ruta para BD (RELATIVA, no absoluta)
		$dbPath = base_url() . "public/files/" . $fileName;

		// guardar en BD
		if ($this->enlacesModel->saveManual($post, $dbPath)) {
			session()->setFlashdata('retornoExito', 'File uploaded successfully.');
		} else {
			session()->setFlashdata('retornoError', 'Error saving data.');
		}

		return redirect()->to(base_url('enlaces/manuals'));
	}

    /**
     * Delete Manual
	 * @review 03/04/2026 - new CI4 version
     */
    public function deleteManual($idJobLocate, $idJob) 
	{
			if (empty($idJobLocate) || empty($idJob) ) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
		
			$arrParam = array(
				"table" => "job_locates",
				"primaryKey" => "id_job_locates",
				"id" => $idJobLocate
			);
			
			$this->load->model("general_model");
			if ($this->general_model->deleteRecord($arrParam)) {
				$this->session->set_flashdata('retornoExito', 'You have deleted the image.');
			} else {
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}
			redirect(base_url('jobs/locates/' . $idJob), 'refresh');
    }

	public function linkListInfo()
	{
		$idMenu = $this->request->getPost('idMenu');

		$arrParam = [
			"idMenu"   => $idMenu,
			"linkState" => 1
		];
		$linkList = $this->generalModel->get_links($arrParam);

		return $this->response->setJSON($linkList);
	}


}
