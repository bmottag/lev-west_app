<?php
namespace App\Modules\Hauling\Models;

use CodeIgniter\Model;
use App\Models\GeneralModel;


class HaulingModel extends Model
{

	protected $protectFields = false;
	protected $generalModel;

    public function __construct()
    {
        parent::__construct();
        $this->generalModel = new GeneralModel();
    }

	/**
	 * Obtiene datos de hauling
	 * @param int $idHaulig: ID hauling
	 * @author BMOTTAG
	 * @since  23/1/2017
	 */
	public function get_hauling_byId($idHauling)
	{
		if (!is_numeric($idHauling)) {
			return null;
		}

		$builder = $this->db->table('hauling H');

		$builder->select('
			H.*,
			C.company_name, C.contact, C.email, C.company_type,
			V.unit_number,
			T.truck_type,
			M.material,
			P.payment,
			J.job_description AS job_from,
			L.job_description AS job_to
		');

		$builder->where('H.id_hauling', $idHauling);

		$builder->join('param_company C', 'C.id_company = H.fk_id_company', 'LEFT');
		$builder->join('param_vehicle V', 'V.id_vehicle = H.fk_id_truck', 'LEFT');
		$builder->join('param_truck_type T', 'T.id_truck_type = H.fk_id_truck_type', 'LEFT');
		$builder->join('param_material_type M', 'M.id_material = H.fk_id_material', 'LEFT');
		$builder->join('param_jobs J', 'J.id_job = H.fk_id_site_from', 'LEFT');
		$builder->join('param_jobs L', 'L.id_job = H.fk_id_site_from', 'LEFT');
		$builder->join('param_payment P', 'P.id_payment = H.fk_id_payment', 'LEFT');

		return $builder->get()->getRowArray();
	}

	/**
	 * Trucks´list by company
	 * @since 12/12/2016
	 */
	public function get_trucks_by_id($idCompany)
	{
		if (!is_numeric($idCompany)) {
			return [];
		}

		$builder = $this->db->table('param_vehicle');

		$builder->select("
			id_vehicle,
			CONCAT(unit_number, ' -----> ', description) AS unit_description
		");

		$builder->where([
			'fk_id_company' => $idCompany,
			'type_level_2' => 4,
			'state' => 1
		]);

		$builder->orderBy('unit_number');

		$query = $builder->get();

		$rows = $query->getResultArray();

		// Transformación más limpia
		return array_map(function ($row) {
			return [
				"id_truck" => $row["id_vehicle"],
				"unit_number" => $row["unit_description"]
			];
		}, $rows);
	}

	/**
	 * Add/Edit hauling
	 * @since 16/12/2016
	 */
	public function saveHauling(array $post)
	{
		$idUser = session()->get('id');
		$idHauling = $post['hddId'] ?? null;
		//solo usuarios SUPER_ADMIN pueden ingresar la fecha de la inspeccion
		$userRol = session()->get('rol');
		$dateIssue = $post['date'] ?? null;
		$fk_id_submodule = null;

		if (!empty($idHauling)) {
			$row = $this->db->table('hauling')
				->select('fk_id_submodule')
				->where('id_hauling', $idHauling)
				->get()
				->getRowArray();

			$fk_id_submodule = $row['fk_id_submodule'] ?? null;
		}


		$hourIn = $post['hourIn'];
		$hourOut = $post['hourOut'];

		$hourIn = $hourIn < 10 ? "0" . $hourIn : $hourIn;
		$hourOut = $hourOut < 10 ? "0" . $hourOut : $hourOut;

		$timeIn = $hourIn . ":" . $post['minIn'];
		$timeOut = $hourOut . ":" . $post['minOut'];

		$isUsingWO = ($post['id_work_order'] == '') ? null : $post['id_work_order'];
		$id_work_order = $post['list_work_order'];

		if ($isUsingWO == 1) {
			$workOrderData = [
				'date' => date("Y-m-d"),
				'fk_id_job' => $post['fromSite'] ?? null,
				'observation' => $post['comments'] ?? null,
				'state' => 0,
				'last_message' => 'A new Work Order was created from the Hauling',
				'fk_id_user' => $idUser,
				'date_issue' => date("Y-m-d H:i:s"),
				'fk_id_company' => $post['company'] ?? null,
			];

			$this->db->table('workorder')->insert($workOrderData);
			$id_work_order = $this->db->insertID();

			$workOrderState = [
				'fk_id_workorder' => $id_work_order,
				'fk_id_user' => $idUser,
				'date_issue' => date("Y-m-d H:i:s"),
				'observation' => 'A new Work Order was created from the Hauling',
				'state' => 0
			];

			$this->db->table('workorder_state')->insert($workOrderState);
		}

		$minIn = (int)$post['minIn'];
		$minOut = (int)$post['minOut'];
		// Asegurarse de que las horas y minutos estén en el rango adecuado
		if (
			$hourIn < 0 || $hourIn > 23 || $minIn < 0 || $minIn > 59 ||
			$hourOut < 0 || $hourOut > 23 || $minOut < 0 || $minOut > 59
		) {
			return [
				'status' => false,
				'message' => 'Las horas deben estar entre 0-23 y los minutos entre 0-59.'
			];
		}

		// Convertir todo a minutos
		$totalMinutesIn = ($hourIn * 60) + $minIn;
		$totalMinutesOut = ($hourOut * 60) + $minOut;

		// Calcular la diferencia en minutos
		$differenceInMinutes = $totalMinutesOut - $totalMinutesIn;

		// Asegurarse de que la diferencia no sea negativa
		if ($differenceInMinutes < 0) {
			return [
				'status' => false,
				'message' => 'La hora de salida no puede ser anterior a la hora de entrada.'
			];
		}

		// Convertir la diferencia a horas fraccionarias
		$fractionalHours = $differenceInMinutes / 60.0; // Dividir por 60 para obtener horas

		// Redondear a dos decimales si es necesario
		$fractionalHours = round($fractionalHours, 2);

		if (!empty($id_work_order)) {

			$companyId = (int)($post['company'] ?? 0);

			// 🔵 CASO 1: EQUIPO PROPIO
			if ($companyId === 1) {

				$dataEquipment = [
					'fk_id_workorder' => $id_work_order,
					'fk_id_type_2' => 10,
					'fk_id_vehicle' => $post['truck'] ?? null,
					'fk_id_attachment' => null,
					'other' => null,
					'operatedby' => $idUser,
					'hours' => $fractionalHours,
					'quantity' => 1,
					'standby' => 2,
					'description' => $post['comments'] ?? null,
				];

				if (empty($idHauling)) {

					$this->db->table('workorder_equipment')->insert($dataEquipment);
					$fk_id_submodule = $this->db->insertID();

				} else {

					if (!empty($fk_id_submodule)) {
						$this->db->table('workorder_equipment')
							->where('id_workorder_equipment', $fk_id_submodule)
							->update($dataEquipment);
					}
				}

			} 
			// 🔵 CASO 2: EQUIPO EXTERNO
			else {

				// Obtener compañía
				$contactRow = $this->generalModel->get_basic_search([
					"table" => "param_company",
					"order" => "company_name",
					"column" => "id_company",
					"id" => $companyId
				]);

				$contact = $contactRow[0]['contact'] ?? null;

				// Obtener tipo equipo
				$equipmentRow = $this->generalModel->get_basic_search([
					"table" => "param_truck_type",
					"order" => "truck_type",
					"column" => "id_truck_type",
					"id" => $post['truckType'] ?? null
				]);

				$equipmentName = $equipmentRow[0]['truck_type'] ?? null;

				$dataOccasional = [
					'fk_id_workorder' => $id_work_order,
					'fk_id_company' => $companyId,
					'equipment' => $equipmentName,
					'quantity' => 1,
					'unit' => $post['plate'] ?? null,
					'hours' => $fractionalHours,
					'contact' => $contact,
					'description' => $post['comments'] ?? null,
				];

				if (empty($idHauling)) {

					$this->db->table('workorder_ocasional')->insert($dataOccasional);
					$fk_id_submodule = $this->db->insertID();

				} else {

					if (!empty($fk_id_submodule)) {
						$this->db->table('workorder_ocasional')
							->where('id_workorder_ocasional', $fk_id_submodule)
							->update($dataOccasional);
					}
				}
			}
		}

		$data = [
			'fk_id_company' => $post['company'] ?? null,
			'fk_id_truck' => $post['truck'] ?? null,
			'fk_id_truck_type' => $post['truckType'] ?? null,
			'fk_id_material' => $post['materialType'] ?? null,
			'fk_id_site_from' => $post['fromSite'] ?? null,
			'fk_id_site_to' => $post['toSite'] ?? null,
			'plate' => $post['plate'] ?? null,
			'time_in' => $timeIn,
			'time_out' => $timeOut,
			'fk_id_payment' => $post['payment'] ?? null,
			'comments' => $post['comments'] ?? null,
			'fk_id_workorder' => $id_work_order,
			'fk_id_submodule' => $fk_id_submodule,
		];

		$builder = $this->db->table('hauling');

		if (empty($idHauling)) {
			$data['date_issue'] = date("Y-m-d");
			$data['fk_id_user'] = $idUser;
			if (($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER) && $dateIssue != "") {
				$data['date_issue'] = $dateIssue;
			}
			if ($builder->insert($data)) {
				return [
					'status' => true,
					'idHauling' => $this->db->insertID()
				];
			}
			return ['status' => false];
		} else {
			if (($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER) && $dateIssue != "") {
				$data['date_issue'] = $dateIssue;
			}
			$update = $builder->where('id_hauling', $idHauling)
							->update($data);

			if($update){
				return [
					'status' => true,
					'idHauling' => $idHauling
				];
			}
			return ['status' => false];			
		}
	}
	
	/**
	 * ID WorkOrder by job code
	 * @since 12/12/2016
	 */
	public function list_by_job_code($jobCode)
	{
		$row = $this->db->table('hauling')
			->select('fk_id_workorder')
			->where('fk_id_site_from', $jobCode)
			->where('DATE(date_issue)', date('Y-m-d'))
			->orderBy('date_issue', 'DESC')
			->get()
			->getRowArray();

		return $row['fk_id_workorder'] ?? 0;
	}

	/**
	 * Trucks´list by company
	 * @since 12/12/2016
	 */
	public function get_wo_job_code($jobCode)
	{
		$dateLimit = date('Y-m-d', strtotime('-5 days'));

		$rows = $this->db->table('workorder')
			->select('id_workorder, observation')
			->where('fk_id_job', $jobCode)
			->where('state', 0)
			->where('date >=', $dateLimit)
			->orderBy('date', 'DESC')
			->get()
			->getResultArray();

		return array_map(function ($row) {
			return [
				"id_workorder" => $row["id_workorder"],
				"observation" => $row["observation"]
			];
		}, $rows);
	}

	public function deleteOccasionalByHauling($idHauling)
	{
		if (!is_numeric($idHauling)) {
			return false;
		}

		$row = $this->db->table('hauling')
			->select('fk_id_submodule')
			->where('id_hauling', $idHauling)
			->get()
			->getRowArray();

		$fk_id_submodule = $row['fk_id_submodule'] ?? null;

		if ($fk_id_submodule) {
			return $this->db->table('workorder_ocasional')
				->where('id_workorder_ocasional', $fk_id_submodule)
				->delete();
		}

		return false;
	}


}
