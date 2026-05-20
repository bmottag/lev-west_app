<?php
namespace App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use App\Modules\Dashboard\Models\DashboardModel;
use App\Models\GeneralModel;

class Dashboard extends BaseController
{
    protected $dashboardModel;
    protected $generalModel;
    protected $session;

    public function __construct()
    {
        $this->dashboardModel   = new DashboardModel();
        $this->generalModel   = new GeneralModel();
        $this->session = session();
    }

	/**
	 * Index Page for this controller.
	 * BASIC dashboard
	 * @review 19/05/2026 - new CI4 version
	 */
	public function index()
	{
		if ($redirect = $this->guardDashboard()) return $redirect;
		$data = [];
		$data['infoMaintenance']  = false;
		$data['noJobs']           = false;
		$data['infoNextPlanning'] = false;
		$data['noHauling']        = true;
		$data['noDailyInspection'] = true;
		$data['noHeavyInspection'] = true;

		$data['dayoff'] = $this->dashboardModel->dayOffInfo();

		$arrParam = [
			'idUser'       => $this->session->get('id'),
			'nextPlanning' => true,
		];
		$data['infoPlanning'] = $this->generalModel->get_planning_for_employee($arrParam);

		$arrParam['idEmployee'] = $this->session->get('id');

		$arrParam['limit'] = 60;
		$data['info']       = $this->generalModel->get_task($arrParam);
		$data['infoSafety'] = $this->generalModel->get_safety($arrParam);

		$arrParam['limit']          = 6;
		$data['infoWaterTruck']     = $this->generalModel->get_special_inspection_water_truck($arrParam);
		$data['infoHydrovac']       = $this->generalModel->get_special_inspection_hydrovac($arrParam);
		$data['infoSweeper']        = $this->generalModel->get_special_inspection_sweeper($arrParam);
		$data['infoGenerator']      = $this->generalModel->get_special_inspection_generator($arrParam);

		return $this->render('App\Modules\Dashboard\Views\dashboard', $data);
	}

	/**
	 * SUPER ADMIN DASHBOARD
	 */
	public function admin()
	{
		if ($redirect = $this->guardDashboard()) return $redirect;
        $data = [];
		$data['noJobs'] = TRUE;
		$data['noHauling'] = TRUE;
		$data['noDailyInspection'] = TRUE;
		$data['noHeavyInspection'] = TRUE;

		//informacion de un dayoff si lo aprobaron y lo negaron
		$data['dayoff'] = $this->dashboardModel->dayOffInfo();

        // Info next planning
        $arrParam = [
            "idUser" => $this->session->get("id"),
            "nextPlanning" => true
        ];
		$data['infoPlanning'] = $this->generalModel->get_planning_for_employee($arrParam); //info planning

		$data['infoNextPlanning'] = $this->generalModel->get_programming_info($arrParam); //info planning

		if (!empty($data['infoNextPlanning'])) {

			$vehicleCache = [];

			foreach ($data['infoNextPlanning'] as &$planning) {

				$workers = $this->generalModel->get_programming_workers([
					"idProgramming" => $planning['id_programming']
				]);

				foreach ($workers as &$worker) {

					if (!empty($worker['fk_id_machine'])) {

						$machines = json_decode($worker['fk_id_machine'], true);

						if (is_array($machines) && !empty($machines)) {

							$ids = implode(',', $machines);

							if (!isset($vehicleCache[$ids])) {
								$vehicleCache[$ids] = $this->generalModel->get_vehicle_info_for_planning([
									"idValues" => $ids
								]);
							}

							$worker['vehicles'] = $vehicleCache[$ids];
						} else {
							$worker['vehicles'] = [];
						}
					}
				}

				$planning['workers'] = $workers;
			}

			unset($planning, $worker);
		}

		$data['infoMaintenance'] = $this->generalModel->get_maintenance_check();
		$data['infoTask'] = $this->generalModel->get_without_work_order();

		$arrParam["limit"] = 60; //Limite de registros para la consulta
		$data['info'] = $this->generalModel->get_task($arrParam); //search the last 5 records 

		$data['infoSafety'] = $this->generalModel->get_safety($arrParam); //info de safety

		$arrParam["limit"] = 6; //Limite de registros para la consulta
		$data['infoWaterTruck'] = $this->generalModel->get_special_inspection_water_truck($arrParam); //info de water truck
		$data['infoHydrovac'] = $this->generalModel->get_special_inspection_hydrovac($arrParam); //info de hydrovac
		$data['infoSweeper'] = $this->generalModel->get_special_inspection_sweeper($arrParam); //info de sweeper
		$data['infoGenerator'] = $this->generalModel->get_special_inspection_generator($arrParam); //info de generador

		return $this->render('App\Modules\Dashboard\Views\dashboard', $data);
	}

	/**
	 * MECHANIC DASHBOARD
	 */
	public function mechanic()
	{
		if ($redirect = $this->guardDashboard()) return $redirect;
		$data = [];
		$data['noJobs'] = FALSE;
		$data['noHauling'] = TRUE;
		$data['noDailyInspection'] = TRUE;
		$data['noHeavyInspection'] = TRUE;
		$data['infoNextPlanning']  = FALSE;

		$data['infoMaintenance'] = $this->generalModel->get_maintenance_check();
		//informacion de un dayoff si lo aprobaron y lo negaron
		$data['dayoff'] = $this->dashboardModel->dayOffInfo();

		//info next planning
		$arrParam = [
			"idUser" => $this->session->get("id"),
			"nextPlanning" => true
		];
		$data['infoPlanning'] = $this->generalModel->get_planning_for_employee($arrParam); //info planning

		//Filtro datos por id del Usuario
		$arrParam["idEmployee"] = $this->session->get("id");

		$arrParam["limit"] = 60; //Limite de registros para la consulta
		$data['info'] = $this->generalModel->get_task($arrParam); //search the last 5 records 

		$data['infoSafety'] = $this->generalModel->get_safety($arrParam); //info de safety

		$arrParam["limit"] = 6; //Limite de registros para la consulta
		$data['infoWaterTruck'] = $this->generalModel->get_special_inspection_water_truck($arrParam); //info de water truck
		$data['infoHydrovac'] = $this->generalModel->get_special_inspection_hydrovac($arrParam); //info de hydrovac
		$data['infoSweeper'] = $this->generalModel->get_special_inspection_sweeper($arrParam); //info de sweeper
		$data['infoGenerator'] = $this->generalModel->get_special_inspection_generator($arrParam); //info de generador

		return $this->render('App\Modules\Dashboard\Views\dashboard', $data);
	}

	/**
	 * hauling list
	 * @since 31/1/2018
	 * @author BMOTTAG
	 */
	public function hauling()
	{
		$userRol = $this->session->get("rol");
		$data = [];
		$data['dashboardURL'] = $this->session->get("dashboardURL");

		if ($userRol == ID_ROL_BASIC) { //If it is a BASIC USER, just show the records of the user session
			$arrParam["idEmployee"] = $this->session->get("id");
		}
		$arrParam["limit"] = 30; //Limite de registros para la consulta
		$arrParam["state_active"] = true;
		$data['infoHauling'] = $this->generalModel->get_hauling($arrParam); //info de hauling

		$data['active'] = 1;
		return $this->render('App\Modules\Dashboard\Views\hauling_list', $data);
	}

	public function hauling_delete()
	{
		$userRol = session()->get('rol');
		$data = [];
		$data['dashboardURL'] = session()->get('dashboardURL');

		if ($userRol == ID_ROL_BASIC) { //If it is a BASIC USER, just show the records of the user session
			$arrParam["idEmployee"] = session()->get('id');
		}
		$arrParam["limit"] = 30; //Limite de registros para la consulta
		$arrParam["state_delete"] = true;
		$data['infoHauling'] = $this->generalModel->get_hauling($arrParam); //info de hauling

		$data['active'] = 2;
		return $this->render('App\Modules\Dashboard\Views\hauling_list_delete', $data);
	}

	/**
	 * pickup inpection list
	 * @since 31/1/2018
	 * @author BMOTTAG
	 * @review 21/03/2026 - new CI4 version
	 */
	public function pickups_inspection()
	{
		$userRol = $this->session->get("rol");
		$data = [];
		$data['dashboardURL'] = $this->session->get("dashboardURL");

		if ($userRol == ID_ROL_BASIC) { //If it is a BASIC USER, just show the records of the user session
			$arrParam["idEmployee"] = $this->session->get("id");
		}
		$arrParam["limit"] = 30; //Limite de registros para la consulta

		$data['infoDaily'] = $this->generalModel->get_daily_inspection($arrParam); //info pickups inspection

		return $this->render('App\Modules\Dashboard\Views\pickups_inspection_list', $data);
	}

	/**
	 * construction equipment inpection list
	 * @since 31/1/2018
	 * @author BMOTTAG
	 * @review 21/03/2026 - new CI4 version
	 */
	public function construction_equipment_inspection()
	{
		$userRol = $this->session->get("rol");
		$data['dashboardURL'] = $this->session->get("dashboardURL");

		if ($userRol == ID_ROL_BASIC) { //If it is a BASIC USER, just show the records of the user session
			$arrParam["idEmployee"] = $this->session->get("id");
		}
		$arrParam["limit"] = 30; //Limite de registros para la consulta

		$data['infoHeavy'] = $this->generalModel->get_heavy_inspection($arrParam); //info de contruction

		return $this->render('App\Modules\Dashboard\Views\construction_equipment_inspection_list', $data);
	}

	/**
	 * Maintenance list
	 * @since 14/3/2020
	 * @author BMOTTAG
	 * @review 21/03/2026 - new CI4 version
	 */
	public function maintenance()
	{
		$data['dashboardURL'] = $this->session->get("dashboardURL");

		$data['infoMaintenance'] = $this->generalModel->get_maintenance_check();

		return $this->render('App\Modules\Dashboard\Views\maintenance_list', $data);
	}

	/**
	 * System general info
	 * @since 28/3/2020
	 * @author BMOTTAG
	 */
	public function info()
	{
		return $this->render('App\Modules\Dashboard\Views\general_info');
	}

	/**
	 * SUPERVISOR DASHBOARD
	 */
	public function supervisor()
	{
		if ($redirect = $this->guardDashboard()) return $redirect;
		$userRol = $this->session->get("rol");

		$data = [];
		$data['noJobs'] = TRUE;
		$data['noHauling'] = TRUE;
		$data['noDailyInspection'] = TRUE;
		$data['noHeavyInspection'] = TRUE;
		$data['infoMaintenance'] = FALSE;
		$data['infoNextPlanning']  = FALSE;

		//informacion de un dayoff si lo aprobaron y lo negaron
		$data['dayoff'] = $this->dashboardModel->dayOffInfo();

		//info next planning
		$arrParam = array(
			"idUser" => $this->session->get("id"),
			"nextPlanning" => true
		);
		$data['infoPlanning'] = $this->generalModel->get_planning_for_employee($arrParam); //info planning

		$arrParam["limit"] = 60; //Limite de registros para la consulta
		$data['info'] = $this->generalModel->get_task($arrParam); //search the last 5 records 

		$data['infoSafety'] = $this->generalModel->get_safety($arrParam); //info de safety

		$arrParam["limit"] = 6; //Limite de registros para la consulta
		$data['infoWaterTruck'] = $this->generalModel->get_special_inspection_water_truck($arrParam); //info de water truck
		$data['infoHydrovac'] = $this->generalModel->get_special_inspection_hydrovac($arrParam); //info de hydrovac
		$data['infoSweeper'] = $this->generalModel->get_special_inspection_sweeper($arrParam); //info de sweeper
		$data['infoGenerator'] = $this->generalModel->get_special_inspection_generator($arrParam); //info de generador

		return $this->render('App\Modules\Dashboard\Views\dashboard', $data);
	}

	/**
	 * WORK ORDER DASHBOARD
	 */
	public function work_order()
	{
		if ($redirect = $this->guardDashboard()) return $redirect;
		$data = [];
		$data['infoMaintenance'] = FALSE;
		$data['noJobs'] = FALSE;
		$data['noHauling'] = TRUE;
		$data['noDailyInspection'] = FALSE;
		$data['noHeavyInspection'] = FALSE;

		$data['infoNextPlanning']  = FALSE;

		//informacion de un dayoff si lo aprobaron y lo negaron
		$data['dayoff'] = $this->dashboardModel->dayOffInfo();

		//info next planning
		$arrParam = array(
			"idUser" => $this->session->get("id"),
			"nextPlanning" => true
		);
		$data['infoPlanning'] = $this->generalModel->get_planning_for_employee($arrParam); //info planning

		$arrParam["limit"] = 60; //Limite de registros para la consulta
		$data['info'] = $this->generalModel->get_task($arrParam); //search the last 5 records 

		$data['infoSafety'] = $this->generalModel->get_safety($arrParam); //info de safety

		$arrParam["limit"] = 6; //Limite de registros para la consulta
		$data['infoWaterTruck'] = $this->generalModel->get_special_inspection_water_truck($arrParam); //info de water truck
		$data['infoHydrovac'] = $this->generalModel->get_special_inspection_hydrovac($arrParam); //info de hydrovac
		$data['infoSweeper'] = $this->generalModel->get_special_inspection_sweeper($arrParam); //info de sweeper
		$data['infoGenerator'] = $this->generalModel->get_special_inspection_generator($arrParam); //info de generador

		return $this->render('App\Modules\Dashboard\Views\dashboard', $data);
	}

	/**
	 * SAFETY DASHBOARD
	 */
	public function safety()
	{
		if ($redirect = $this->guardDashboard()) return $redirect;
		$data = [];
		$data['noJobs'] = TRUE;
		$data['noHauling'] = FALSE;
		$data['noDailyInspection'] = TRUE;
		$data['noHeavyInspection'] = TRUE;
		$data['infoNextPlanning']  = FALSE;

		//informacion de un dayoff si lo aprobaron y lo negaron
		$data['dayoff'] = $this->dashboardModel->dayOffInfo();

		//info next planning
		$arrParam = array(
			"idUser" => $this->session->get("id"),
			"nextPlanning" => true
		);
		$data['infoPlanning'] = $this->generalModel->get_planning_for_employee($arrParam); //info planning

		$arrParam["idEmployee"] = $this->session->get("id");

		$data['infoMaintenance'] = $this->generalModel->get_maintenance_check();

		$arrParam["limit"] = 60; //Limite de registros para la consulta
		$data['info'] = $this->generalModel->get_task($arrParam); //search the last 5 records 

		$data['infoSafety'] = $this->generalModel->get_safety($arrParam); //info de safety

		$arrParamEquipment["limit"] = 6; //Limite de registros para la consulta
		$data['infoWaterTruck'] = $this->generalModel->get_special_inspection_water_truck($arrParamEquipment); //info de water truck
		$data['infoHydrovac'] = $this->generalModel->get_special_inspection_hydrovac($arrParamEquipment); //info de hydrovac
		$data['infoSweeper'] = $this->generalModel->get_special_inspection_sweeper($arrParamEquipment); //info de sweeper
		$data['infoGenerator'] = $this->generalModel->get_special_inspection_generator($arrParamEquipment); //info de generador

		return $this->render('App\Modules\Dashboard\Views\dashboard', $data);
	}

	/**
	 * Accounting DASHBOARD
	 */
	public function accounting()
	{
		if ($redirect = $this->guardDashboard()) return $redirect;
		$data = [];
		$data['noJobs'] = FALSE;
		$data['noHauling'] = TRUE;
		$data['noDailyInspection'] = FALSE;
		$data['noHeavyInspection'] = FALSE;
		$data['infoNextPlanning']  = FALSE;

		$data['dayoff'] = FALSE;
		$data['infoMaintenance'] = FALSE;
		$data['infoWaterTruck'] = FALSE;
		$data['infoHydrovac'] = FALSE;
		$data['infoSweeper'] = FALSE;
		$data['infoGenerator'] = FALSE;

		//info next planning
		$arrParam = array(
			"idUser" => $this->session->get("id"),
			"nextPlanning" => true
		);
		$data['infoPlanning'] = $this->generalModel->get_planning_for_employee($arrParam); //info planning

		$arrParam["limit"] = 60; //Limite de registros para la consulta
		$data['info'] = $this->generalModel->get_task($arrParam); //search the last 5 records 

		$data['infoSafety'] = $this->generalModel->get_safety($arrParam); //info de safety

		return $this->render('App\Modules\Dashboard\Views\dashboard', $data);
	}

	/**
	 * Management DASHBOARD
	 */
	public function management()
	{
		if ($redirect = $this->guardDashboard()) return $redirect;
		$data = [];
		$data['noJobs'] = TRUE;
		$data['noHauling'] = TRUE;
		$data['noDailyInspection'] = TRUE;
		$data['noHeavyInspection'] = TRUE;
		$data['infoNextPlanning']  = FALSE;

		$data['dayoff'] = FALSE;
		$data['infoMaintenance'] = FALSE;

		//info next planning
		$arrParam = array(
			"idUser" => $this->session->get("id"),
			"nextPlanning" => true
		);
		$data['infoPlanning'] = $this->generalModel->get_planning_for_employee($arrParam); //info planning

		$arrParam["limit"] = 60; //Limite de registros para la consulta
		$data['info'] = $this->generalModel->get_task($arrParam); //search the last 5 records 

		$data['infoSafety'] = $this->generalModel->get_safety($arrParam); //info de safety

		$arrParam["limit"] = 6; //Limite de registros para la consulta
		$data['infoWaterTruck'] = $this->generalModel->get_special_inspection_water_truck($arrParam); //info de water truck
		$data['infoHydrovac'] = $this->generalModel->get_special_inspection_hydrovac($arrParam); //info de hydrovac
		$data['infoSweeper'] = $this->generalModel->get_special_inspection_sweeper($arrParam); //info de sweeper
		$data['infoGenerator'] = $this->generalModel->get_special_inspection_generator($arrParam); //info de generador

		return $this->render('App\Modules\Dashboard\Views\dashboard', $data);
	}

	/**
	 * Calendario
	 * @since 18/12/2020
	 * @author BMOTTAG
	 */
	public function calendar()
	{
		$data['dashboardURL'] = $this->session->get("dashboardURL");
		return $this->renderTopOnly('App\Modules\Dashboard\Views\calendar', $data);
	}

	/**
	 * Consulta desde el calendario
	 * @since 21/12/2020
	 * @author BMOTTAG
	 */
	public function consulta()
	{
		header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos

		$start = $this->request->getPost('start');
		$end = $this->request->getPost('end');
		$start = substr($start, 0, 10);
		$end = substr($end, 0, 10);

		$arrParam = array(
			"from" => $start,
			"to" => $end
		);

		//informacion Work Order
		$workOrderInfo = $this->generalModel->get_workorder_info($arrParam);

		//informacion Planning
		$planningInfo = $this->generalModel->get_programming_info($arrParam);

		//Informacion de Payroll
		$payrollInfo = $this->generalModel->get_task($arrParam);

		//Informacion de Hauling
		$haulingInfo = $this->generalModel->get_hauling($arrParam);

		//Informacion de Force Account
		$forceAccountInfo = $this->generalModel->get_forceaccount_info($arrParam);

		echo  '[';

		if ($workOrderInfo) {
			$longitud = count($workOrderInfo);
			$i = 1;
			foreach ($workOrderInfo as $data) :
				echo  '{
						      "title": "W.O. #: ' . $data['id_workorder'] . ' - Job Code/Name: ' . $data['job_description'] . '",
						      "start": "' . $data['date'] . '",
						      "end": "' . $data['date'] . '",
						      "color": "green",
						      "url": "' . base_url("dashboard/info_by_day/workOrderInfo/" . $data['date']) . '"
						    }';

				if ($i < $longitud) {
					echo ',';
				}
				$i++;
			endforeach;
		}

		if ($workOrderInfo && $planningInfo) {
			echo ',';
		}

		if ($planningInfo) {
			$longitud = count($planningInfo);
			$i = 1;
			foreach ($planningInfo as $data) :
				echo  '{
						      "title": "Planning. #: ' . $data['id_programming'] . ' - Job Code/Name: ' . $data['job_description'] . '",
						      "start": "' . $data['date_programming'] . '",
						      "end": "' . $data['date_programming'] . '",
						      "color": "yellow",
						      "url": "' . base_url("dashboard/info_by_day/planningInfo/" . $data['date_programming']) . '"
						    }';

				if ($i < $longitud) {
					echo ',';
				}
				$i++;
			endforeach;
		}

		if (($workOrderInfo || $planningInfo) && $haulingInfo) {
			echo ',';
		}

		if ($haulingInfo) {
			$longitud = count($haulingInfo);
			$i = 1;
			foreach ($haulingInfo as $data) :

				$material = preg_replace('([^A-Za-z0-9 ])', ' ', $data['material']);
				echo  '{
						      "title": "Hauling. #: ' . $data['id_hauling'] . ' - Report done by: ' . $data['name'] . ' - Hauling done by: ' . $data['company_name'] . '  - From Site: ' . $data['site_from'] . ' - To Site: ' . $data['site_to'] . ' - Truck - Unit Number: ' . $data['unit_number'] . ' - Material Type: ' . $material . '",
						      "start": "' . $data['date_issue'] . ' ' . $data['time_in'] . '",
						      "end": "' . $data['date_issue'] . ' ' . $data['time_out'] . '",
						      "color": "red",
						      "url": "' . base_url("dashboard/info_by_day/haulingInfo/" . $data['date_issue']) . '"
						    }';

				if ($i < $longitud) {
					echo ',';
				}
				$i++;
			endforeach;
		}

		if (($workOrderInfo || $planningInfo || $haulingInfo) && $payrollInfo) {
			echo ',';
		}

		if ($payrollInfo) {
			$payrollDays = []; // Array para almacenar los días que tienen payroll
		
			foreach ($payrollInfo as $data) {
				$startPayroll = substr($data['start'], 0, 10); // Obtener solo la fecha
				$payrollDays[$startPayroll] = true; // Marcar que hay payroll en este día
			}
		
			$longitud = count($payrollDays);
			$i = 1;
		
			foreach (array_keys($payrollDays) as $date) {
				echo  '{
							"title": "Payroll",
							"start": "' . $date . '",
							"end": "' . $date . '",
							"color": "blue",
							"url": "' . base_url("dashboard/info_by_day/payrollInfo/" . $date) . '"
						}';
		
				if ($i < $longitud) {
					echo ',';
				}
				$i++;
			}

			
			/*
			$longitud = count($payrollInfo);
			$i = 1;
			foreach ($payrollInfo as $data) :
				if ($data['fk_id_job'] != $data['fk_id_job_finish']) {
					$startPayroll = substr($data['start'], 0, 10);
					$payrollInfo = "Payroll: " . $data['first_name'] . ' ' . $data['last_name'];
					$payrollInfo .=	", Start Job Code/Name: " . $data['job_start'];
					$payrollInfo .= " - Working Hours: " . $data['hours_start_project'];
					$payrollInfo .=	", Finish Job Code/Name: " . $data['job_finish'];
					$payrollInfo .= " - Working Hours: " . $data['hours_end_project'];
				} else {
					$startPayroll = substr($data['start'], 0, 10);
					$payrollInfo = "Payroll: " . $data['first_name'] . ' ' . $data['last_name'];
					$payrollInfo .=	" Job Code/Name: " . $data['job_start'];
					$payrollInfo .= " - Working Hours: " . $data['working_hours'];
				}

				if ($data['task_description']) {
					$taskDescription = trim(preg_replace('/\s+/', ' ', $data['task_description']));
					$payrollInfo .= " - Task description: " . $taskDescription;
				}
				echo  '{
						      "title": "' . $payrollInfo . '",
						      "start": "' . $data['start'] . '",
						      "end": "' . $data['finish'] . '",
						      "color": "blue",
						      "url": "' . base_url("dashboard/info_by_day/payrollInfo/" . $startPayroll) . '"
						    }';

				if ($i < $longitud) {
					echo ',';
				}
				$i++;
			endforeach;
			*/
		}

		if (($workOrderInfo || $planningInfo || $haulingInfo || $payrollInfo) && $forceAccountInfo) {
			echo ',';
		}

		if ($forceAccountInfo) {
			$longitud = count($forceAccountInfo);
			$i = 1;
			foreach ($forceAccountInfo as $data) :
				echo  '{
						      "title": "Force Account #: ' . $data['id_forceaccount'] . ' - Job Code/Name: ' . $data['job_description'] . '",
						      "start": "' . $data['date'] . '",
						      "end": "' . $data['date'] . '",
						      "color": "orange",
						      "url": "' . base_url("dashboard/info_by_day/forceAccountInfo/" . $data['date']) . '"
						    }';

				if ($i < $longitud) {
					echo ',';
				}
				$i++;
			endforeach;
		}

		echo  ']';
	}

	/**
	 * Consulta desde el calendario
	 * @since 22/12/2020
	 * @author BMOTTAG
	 * @review 05/05/2026 - new CI4 version
	 */
	public function info_by_day($view, $infoDate)
	{
		$data['fecha'] = $infoDate;
		$arrParam = [
			"fecha" => $infoDate,
			"estado" => "ACTIVAS"
		];
	
		// Definir las vistas y sus funciones correspondientes
		$viewsMapping = [
			"planningInfo"  => "get_programming_info",
			"payrollInfo"   => "get_task",
			"workOrderCheck" => "get_workorder_info",
			"workOrderInfo" => "get_workorder_info",
			"haulingInfo"   => "get_hauling",
			"safetyInfo"	=> "get_safety",
			"toolBoxInfo"   => "get_tool_box",
			"forceAccountInfo"   => "get_forceaccount_info"
		];
	
		if ($view === "all") {
			// Si la vista es "all", obtener todas las consultas
			foreach ($viewsMapping as $key => $method) {
				$data[$key] = $this->generalModel->$method($arrParam);
			}

			if (!empty($data["workOrderCheck"]) && !empty($data["planningInfo"])) {

				foreach ($data["planningInfo"] as &$planning) {

					$userId = $planning['fk_id_user'];

					foreach ($data["workOrderCheck"] as &$wo) {

						$arrParamCheck = [
							"idWorkorder" => $wo['id_workorder'],
							"idUser" => $userId
						];

						$hoursPersonal = (float)$this->generalModel->countHoursPersonal($arrParamCheck);
						$hoursEquipment = (float)$this->generalModel->countHoursEquipmentPersonal($arrParamCheck);

						$wo['hours_by_user'][$userId] = $hoursPersonal + $hoursEquipment;
					}
				}

				unset($planning, $wo);
			}
		} elseif (isset($viewsMapping[$view])) {
			// Si la vista es una específica, obtener solo esa
			$method = $viewsMapping[$view];
			$data[$view] = $this->generalModel->$method($arrParam);

			if ($view === "planningInfo") {
				if (!empty($data['planningInfo'])) {

					$vehicleCache = [];

					foreach ($data['planningInfo'] as &$planning) {

						$workers = $this->generalModel->get_programming_workers([
							"idProgramming" => $planning['id_programming']
						]);

						foreach ($workers as &$worker) {

							// Texto del site (mueve el switch aquí 👇)
							switch ($worker['site']) {
								case 1: $worker['site_text'] = "At the yard - "; break;
								case 2: $worker['site_text'] = "At the site - "; break;
								case 3: $worker['site_text'] = "At Terminal - "; break;
								case 4: $worker['site_text'] = "On-line training - "; break;
								case 5: $worker['site_text'] = "At training facility - "; break;
								case 6: $worker['site_text'] = "At client's office - "; break;
								default: $worker['site_text'] = "At the yard - "; break;
							}

							// Vehículos
							if (!empty($worker['fk_id_machine']) && $worker['fk_id_machine'] != 0) {

								$machines = json_decode($worker['fk_id_machine'], true);

								if (!is_array($machines)) {
									$machines = [$worker['fk_id_machine']];
								}

								$ids = implode(',', $machines);

								if (!isset($vehicleCache[$ids])) {
									$vehicleCache[$ids] = $this->generalModel->get_vehicle_info_for_planning([
										"idValues" => $ids
									]);
								}

								$worker['vehicles'] = $vehicleCache[$ids];
							} else {
								$worker['vehicles'] = [];
							}
						}

						$planning['workers'] = $workers;
					}

					unset($planning, $worker);
				}
			}

			// Si es payrollInfo, agregar workOrderCheck y calcular horas por usuario (igual que en "all")
			if ($view === "payrollInfo") {
				$data["workOrderCheck"] = $this->generalModel->get_workorder_info($arrParam);

				if (!empty($data["workOrderCheck"]) && !empty($data["payrollInfo"])) {
					foreach ($data["payrollInfo"] as &$task) {
						$userId = $task['fk_id_user'];
						foreach ($data["workOrderCheck"] as &$wo) {
							$arrParamCheck = [
								"idWorkorder" => $wo['id_workorder'],
								"idUser"      => $userId
							];
							$hoursPersonal  = (float)$this->generalModel->countHoursPersonal($arrParamCheck);
							$hoursEquipment = (float)$this->generalModel->countHoursEquipmentPersonal($arrParamCheck);
							$wo['hours_by_user'][$userId] = $hoursPersonal + $hoursEquipment;
						}
					}
					unset($task, $wo);
				}
			}
		} else {
			// Manejo de error si la vista no es válida
			$data["error"] = "Vista no encontrada";
		}
	
		return $this->renderTopOnly('App\Modules\Dashboard\Views\info_by_day', $data);
	}

	/**
	 * General info
	 * @since 26/12/2020
	 * @author BMOTTAG
	 */
	public function settings()
	{
		//busco datos parametricos
		$arrParam = [
			"table" => "parametric",
			"order" => "id_parametric",
			"id" => "x"
		];
		$data['parametric'] = $this->generalModel->get_basic_search($arrParam);

		return $this->render('App\Modules\Dashboard\Views\settings', $data);
	}

	/**
	 * Check In list
	 * @since 5/6/2022
	 * @author BMOTTAG
	 */
	public function checkin()
	{
		$data['dashboardURL'] = $this->session->get("dashboardURL");

		$data['requestDate'] = date('Y-m-d');
		if ($_POST) {
			$data['requestDate'] = $this->request->getPost('date');
		}
		$arrParam = array("today" => $data['requestDate']);
		$data['checkinList'] = $this->generalModel->get_checkin($arrParam);

		return $this->render('App\Modules\Dashboard\Views\checkin_list', $data);
	}

	/**
	 * System changes
	 * @since 21/12/2022
	 * @author BMOTTAG
	 */
	public function versions()
	{
		return $this->render('App\Modules\Dashboard\Views\versions');
	}

	/**
	 * Update planning confirmation
	 * @since 15/1/2022
	 * @review 05/05/2026 - new CI4 version
	 */
	public function confirmPlanning()
	{
		$data["dashboardURL"] = session()->get("dashboardURL");

		$arrParam = [
			"table" => "programming_worker",
			"primaryKey" => "id_programming_worker",
			"id" => $this->request->getPost('identificador'),
			"column" => "confirmation",
			"value" => 1
		];
		if ($this->generalModel->updateRecord($arrParam)) {
			$data["status"] = "success";
			session()->setFlashdata('retornoExito', 'You have updated the information');
		} else {
			$data["status"] = "error";
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Without Hours WO list
	 * @since 27/01/2025
	 * @author FOROZCO
	 */
	public function without_work_order()
	{
		$data['dashboardURL'] = $this->session->get("dashboardURL");
		$data['infoTask'] = $this->generalModel->get_without_work_order();
		return $this->render('App\Modules\Dashboard\Views\without_work_order', $data);
	}

	/**
	 * Cargo modal- formulario de captura personal
	 * @since 13/1/2017
	 */
	public function modalListWo()
	{
		header('Content-Type: application/json');

		$data["taskId"] = $this->request->getPost("taskId");

		$time = $this->request->getPost("time");

		//task INFO
		$arrParam = array(
			"table" => "task",
			"order" => "id_task",
			"column" => "id_task",
			"id" => $this->request->getPost("taskId")
		);
		$data['task'] = $this->generalModel->get_basic_search($arrParam); //employee type list

		if ($time == "start") {
			$date = $data['task'][0]['start'];
			$idJob = $data['task'][0]['fk_id_job'];
		}elseif ($time == "end") {
			$date = $data['task'][0]['start'];
			$idJob = $data['task'][0]['fk_id_job_finish'];
		} else {
			$date = $data['task'][0]['start'];
			$idJob = $data['task'][0]['fk_id_job'];
		}

		$sql = "SELECT * FROM workorder WHERE date = DATE('$date') AND fk_id_job = $idJob";

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) {
			$result = $query->row_array();
		} else {
			$result = null;
		}

		$this->db->close();
		echo json_encode($result);
	}

	private function guardDashboard(): ?\CodeIgniter\HTTP\RedirectResponse
	{
		$current  = trim(uri_string(), '/');
		$userDash = trim($this->session->get('dashboardURL'), '/');

		if ($current !== $userDash) {
			return redirect()->to(base_url($userDash));
		}
		return null;
	}

	public function assignHoursWo()
	{
		header('Content-Type: application/json');

		//task INFO
		$arrParam = array(
			"table" => "task",
			"order" => "id_task",
			"column" => "id_task",
			"id" => $this->request->getPost("taskId")
		);
		$task = $this->generalModel->get_basic_search($arrParam); //employee type list

		$data["woID"] = $this->request->getPost("woID");
		$wo = $this->request->getPost("woID");

		$flagTime = $this->request->getPost("time");
		if($flagTime == 'start'){
			$column = 'wo_start_project';
		}elseif($flagTime == 'end'){
			$column = 'wo_end_project';
		}else{
			$column = 'bothColumns';
		}

		$data["dashboardURL"] = $this->session->get("dashboardURL");

		$arrParam = array(
			"id" => $this->request->getPost("taskId"),
			"column" => $column,
			"value" => $this->request->getPost("woID")
		);
		if ($this->generalModel->updateWOTasks($arrParam)) {
			$data["result"] = true;

			if ($this->request->getPost("time") == 'start') {
				$hours_project = $task[0]['hours_start_project'];
			}elseif ($this->request->getPost("time") == 'end') {
				$hours_project = $task[0]['hours_end_project'];
			} else {
				$hours_project = $task[0]['working_hours'];
			}

			$fk_id_user = $task[0]['fk_id_user'];
			$description = $task[0]['task_description'];


			$sql = "SELECT * FROM workorder_personal WHERE fk_id_workorder = ? AND fk_id_user = ?";
			$query = $this->db->query($sql, array($wo, $fk_id_user));
			
			if ($query->num_rows() >= 1) {

				$data = array(
					'hours' => $hours_project,
					'description' => $description
				);

				$this->db->where('fk_id_workorder  ', $wo);
				$this->db->where('fk_id_user  ', $fk_id_user);
				$this->db->update('workorder_personal', $data);
			} else {
				$data = array(
					'fk_id_workorder' => $this->request->getPost("woID"),
					'fk_id_user' => $fk_id_user,
					'fk_id_employee_type' => 1,
					'hours' => $hours_project,
					'description' => $description
				);

				$this->db->insert('workorder_personal', $data);
			}

			$this->session->set_flashdata('retornoExito', 'You have updated the information');
		} else {
			$data["result"] = "error";
			$data["mensaje"] = "Error!!! Ask for help.";
			$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		echo json_encode($data);
	}

    /**
     * Trailers List
     * @since 06/01/2024
     * @author FOROZCO
	 * @review 08/05/2026 - new CI4 version
     */
    public function trailers()
    {
        $month = 2;
        $data['trailer_not_inspect'] = $this->dashboardModel->get_not_inspection($month);
        $data['trailer_inspect'] = $this->dashboardModel->get_trailers();

        $data['dashboardURL'] = session()->get("dashboardURL");
		return $this->render('App\Modules\Dashboard\Views\trailers', $data);
    }


}