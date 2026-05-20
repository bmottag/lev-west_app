<?php
namespace App\Modules\Prices\Controllers;

use App\Controllers\BaseController;
use App\Modules\Prices\Models\PricesModel;
use App\Models\GeneralModel;

class Prices extends BaseController
{
    protected $pricesModel;
    protected $generalModel;
    
    public function __construct()
    {
        $this->pricesModel   = new PricesModel();
        $this->generalModel   = new GeneralModel();
    }

	/**
	 * Lista de precios para tipos de empleados
     * @since 5/11/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function employeeTypeUnitPrice($idJob)
	{
		//job info
		$data['jobInfo'] = $this->generalModel->get_job(["idJob" => $idJob]);
		
		//job_employee_type_unit_price list
		$data['employeeTypeUnitPrice'] = $this->generalModel->get_job_employee_type_unit_price($idJob);

		return $this->render('App\Modules\Prices\Views\employeeTypeUnitPrice_list', $data);
	}

	/**
	 * Load employee types
     * @since 5/11/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function load_employee_type()
	{
		$data = [];

		$idJob = $this->request->getPost("identificador");
		$data["idJob"] = $idJob;

		$arrParam = array(
			"table" => "param_employee_type",
			"order" => "employee_type",
			"id" => "x"
		);
		$employeeTypeUnitPrice = $this->generalModel->get_basic_search($arrParam);

		if ($this->pricesModel->add_employee_type($idJob, $employeeTypeUnitPrice)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have loaded the data.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Update the price of each field
     * @since 6/11/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function update_employee_type_price()
	{
		$post = $this->request->getPost();
		$idJob = $post['hddIdJob'];

		if ($this->pricesModel->updateEmployeeTypePrice($post)) {
			session()->setFlashdata('retornoExito', 'You have updated the Employee Type Unit Price!!');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('prices/employeeTypeUnitPrice/'.$idJob));
	}

	/**
	 * Equipment list
     * @since 6/11/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function equipmentList($companyType)
	{
		$data['vehicleState'] = 1;
		$data['companyType'] = $companyType;
		$data['title'] = $companyType==1?"Lev West":"RENTALS";

		$arrParam = array(
			"companyType" => $companyType,
			"vehicleState" => $data['vehicleState']
		);		
		$data['info'] = $this->generalModel->get_equipment_info_by($arrParam);//vehicle list

		return $this->render('App\Modules\Prices\Views\equipment_list', $data);
	}

	/**
	 * Update the price of each field
     * @since 6/11/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function update_equipment_price()
	{	
		$post = $this->request->getPost();
		$companyType = $post['hddIdCompanyType'];

		if ($this->pricesModel->updateEquipmentPrice($post)) {
			session()->setFlashdata('retornoExito', 'You have updated the Equipment Prices!!');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('prices/equipmentList/'.$companyType));
	}

	/**
	 * Lista de precios de quipos por hora para un proyecto
     * @since 5/11/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function equipmentUnitPrice($idJob, $companyType)
	{
		$data['vehicleState'] = 1;
		$data['companyType'] = $companyType;
		$data['title'] = $companyType==1?"Lev West":"RENTALS";

		//job info
		$data['jobInfo'] = $this->generalModel->get_job(["idJob" => $idJob]);

		$arrParam = [
			"companyType" => $companyType,
			"vehicleState" => $data['vehicleState'],
			"idJob" => $idJob
		];		
		$data['equipmentUnitPrice'] = $this->generalModel->get_equipment_price($arrParam);//vehicle list

		return $this->render('App\Modules\Prices\Views\equipmentPrice_list', $data);
	}

	/**
	 * Load equipment
     * @since 7/11/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function load_equipment()
	{
		$data = [];

		$idJob = $this->request->getPost("identificador");
		$data["idJob"] = $idJob;
		$data['vehicleState'] = 1;

		$equipmentUnitPrice = $this->generalModel->get_equipment_info_by(["vehicleState" => $data['vehicleState']]);//vehicle list

		if ($this->pricesModel->add_equipment($idJob, $equipmentUnitPrice)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have loaded the data.');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Update Equipment price of each field for job
     * @since 7/11/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function update_job_equipment_price()
	{	
		$post = $this->request->getPost();
		$idJob = $post['hddIdJob'];
		$companyType = $post['hddIdCompanyType'];

		if ($this->pricesModel->updateJobEquipmentPrice($post)) {
			session()->setFlashdata('retornoExito', 'You have updated the Equipment Prices!!');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('prices/equipmentUnitPrice/'.$idJob.'/'.$companyType));
	}

	/**
	 * Update the price of each field
     * @since 13/11/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function update_general_employee_type_price()
	{	
		$post = $this->request->getPost();

		if ($this->pricesModel->updateGeneralEmployeeTypePrice($post)) {
			session()->setFlashdata('retornoExito', 'You have updated the Employee Type Unit Price!!');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('admin/employeeType'));
	}

	/**
	 * Update the price of each field - Material
     * @since 18/12/2020
     * @author BMOTTAG
	 * @review 06/04/2026 - new CI4 version
	 */
	public function update_general_material_price()
	{	
		$post = $this->request->getPost();

		if ($this->pricesModel->updateGeneralMaterialPrice($post)) {
			session()->setFlashdata('retornoExito', 'You have updated the Material Price!!');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('admin/material'));
	}


}
