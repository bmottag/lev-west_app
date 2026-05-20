<?php
namespace App\Modules\Prices\Models;

use CodeIgniter\Model;


class PricesModel extends Model
{

	protected $protectFields = false;

	/**
	 * Add JOB EMPLOYEE TYPE PRICES
	 * @since 5/11/2020
	 */
	public function add_employee_type($idJob, $employeeTypeList)
	{
		$this->db->transStart();

		$this->db->table('job_employee_type_price')
		->where('fk_id_job', $idJob)
		->delete();

		if (!empty($employeeTypeList)) {

			$dataBatch = [];

			foreach ($employeeTypeList as $item) {
				$dataBatch[] = [
					'fk_id_job' => $idJob,
					'fk_id_employee_type' => $item['id_employee_type'],
					'job_employee_type_unit_price' => $item['employee_type_unit_price']
				];
			}

			$this->db->table('job_employee_type_price')->insertBatch($dataBatch);
		}

		$this->db->transComplete();

		return $this->db->transStatus();
	}

	/**
	 * Update job employee type prices
	 * @since 6/11/2020
	 */
	public function updateEmployeeTypePrice(array $post)
	{
		$employeeType = $post['form'] ?? null;

		if (empty($employeeType)) {
			return false;
		}

		$dataBatch = [];

		foreach ($employeeType['id'] as $index => $id) {
			$dataBatch[] = [
				'id_employee_type_price' => $id,
				'job_employee_type_unit_price' => $employeeType['price'][$index]
			];
		}

		return $this->db->table('job_employee_type_price')
						->updateBatch($dataBatch, 'id_employee_type_price');
	}
	
	/**
	 * Update Equipment prices
	 * @since 6/11/2020
	 */
	public function updateEquipmentPrice(array $post)
	{
		$equipment = $post['form'] ?? null;

		if (empty($equipment)) {
			return false;
		}

		$dataBatch = [];

		foreach ($equipment['id'] as $index => $id) {
			$dataBatch[] = [
				'id_vehicle' => $id,
				'equipment_unit_cost' => $equipment['cost'][$index],
				'equipment_unit_price_without_driver' => $equipment['priceWithoutDriver'][$index],
				'equipment_unit_price' => $equipment['price'][$index]
			];
		}

		return $this->db->table('param_vehicle')
				->updateBatch($dataBatch, 'id_vehicle');
	}

	/**
	 * Add JOB EQUIPMENT PRICES
	 * @since 7/11/2020
	 */
	public function add_equipment($idJob, $equipmentList)
	{
		$this->db->transStart();

		$this->db->table('job_equipment_price')
		->where('fk_id_job', $idJob)
		->delete();

		if (!empty($equipmentList)) {

			$dataBatch = [];

			foreach ($equipmentList as $item) {
				$dataBatch[] = [
					'fk_id_job' => $idJob,
					'fk_id_equipment' => $item['id_vehicle'],
					'job_equipment_without_driver' => $item['equipment_unit_price_without_driver'],
					'job_equipment_unit_price' => $item['equipment_unit_price']
				];
			}

			$this->db->table('job_equipment_price')->insertBatch($dataBatch);
		}

		$this->db->transComplete();

		return $this->db->transStatus();
	}


	/**
	 * Update Job Equipment prices
	 * @since 7/11/2020
	 */
	public function updateJobEquipmentPrice(array $post)
	{
		$equipment = $post['form'] ?? null;

		if (empty($equipment)) {
			return false;
		}

		$dataBatch = [];

		foreach ($equipment['id'] as $index => $id) {
			$dataBatch[] = [
				'id_equipment_price' => $id,
				'job_equipment_without_driver' => $equipment['priceWithoutDriver'][$index],
				'job_equipment_unit_price' => $equipment['price'][$index]
			];
		}

		return $this->db->table('job_equipment_price')
				->updateBatch($dataBatch, 'id_equipment_price');
	}

	/**
	 * Update employee type prices
	 * @since 13/11/2020
	 */
	public function updateGeneralEmployeeTypePrice(array $post) 
	{
		$employeeType = $post['form'] ?? null;

		if (empty($employeeType)) {
			return false;
		}

		$dataBatch = [];

		foreach ($employeeType['id'] as $index => $id) {
			$dataBatch[] = [
				'id_employee_type' => $id,
				'employee_type_unit_price' => $employeeType['price'][$index]
			];
		}
				
		return $this->db->table('param_employee_type')
				->updateBatch($dataBatch, 'id_employee_type');
	}

	/**
	 * Update material prices
	 * @since 18/12/2020
	 */
	public function updateGeneralMaterialPrice(array $post) 
	{
		$material = $post['form'] ?? null;

		if (empty($material)) {
			return false;
		}

		$dataBatch = [];

		foreach ($material['id'] as $index => $id) {
			$dataBatch[] = [
				'id_material' => $id,
				'material_price' => $material['price'][$index]
			];
		}
		
		return $this->db->table('param_material_type')
				->updateBatch($dataBatch, 'id_material');
	}




}
