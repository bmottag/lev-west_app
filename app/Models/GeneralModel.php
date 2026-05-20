<?php
namespace App\Models;

use CodeIgniter\Model;

class GeneralModel extends Model
{

	/**
	 * Consulta BASICA A UNA TABLA
	 * @param $TABLA: nombre de la tabla
	 * @param $ORDEN: orden por el que se quiere organizar los datos
	 * @param $COLUMNA: nombre de la columna en la tabla para realizar un filtro (NO ES OBLIGATORIO)
	 * @param $VALOR: valor de la columna para realizar un filtro (NO ES OBLIGATORIO)
	 * @since 8/11/2016
	 */
    public function get_basic_search($arrData)
    {
        $builder = $this->db->table($arrData["table"]);

        // Filtro opcional
        if (isset($arrData["id"]) && $arrData["id"] != 'x') {
            $builder->where($arrData["column"], $arrData["id"]);
        }

        // Orden
        $builder->orderBy($arrData["order"], "ASC");

        $query = $builder->get();

        $result = $query->getResultArray();

        return !empty($result) ? $result : false;
    }

	/**
	 * Update field in a table
	 * @since 11/12/2016
	 */
	public function updateRecord(array $arrDatos)
	{
		$builder = $this->db->table($arrDatos["table"]);
		$data = [
			$arrDatos["column"] => $arrDatos["value"]
		];
		$builder->where($arrDatos["primaryKey"], $arrDatos["id"]);

		return $builder->update($data);
	}

	/**
	 * Delete Record
	 * @since 5/12/2016
	 */
	public function deleteRecord($arrDatos)
	{
		return $this->db->table($arrDatos["table"])
						->where($arrDatos["primaryKey"], $arrDatos["id"])
						->delete();
	}

	/**
	 * Verify if the user already exist by specific column
	 * @author BMOTTAG
	 * @since  8/11/2016
	 * @review 31/01/2022
	 */
	public function verifyUser($arrData)
	{
		$builder = $this->db->table("user");
		$builder->where($arrData["column"], $arrData["value"]);

		return $builder->countAllResults() > 0;
	}

	/**
	 * Lista de roles
	 * Modules: ROL
	 * @since 30/3/2020
	 */
    public function get_roles(array $arrData)
    {
        $builder = $this->db->table('param_rol');

        // Filtro opcional
        if (isset($arrData['filtro'])) {
            $builder->where('id_rol !=', 99);
        }

        if (isset($arrData['idRol'])) {
            $builder->where('id_rol', $arrData['idRol']);
        }

        $builder->orderBy('rol_name', 'ASC');

        $query = $builder->get();
        $result = $query->getResultArray();

        return !empty($result) ? $result : false;
    }

	/**
	 * Get vehicle list -> Se usa en el Login y en Inspection
	 * Param varchar $encryption -> dato que viene del QR code
	 * Param varchar $idVehicle -> identificador del vehiculo
	 * @since 3/3/2016
	 */
    public function get_vehicle_by($arrData)
    {
        $builder = $this->db->table('param_vehicle V');

        $builder->select('*');
        $builder->join('param_vehicle_type_2 T', 'T.id_type_2 = V.type_level_2', 'inner');

        if (array_key_exists("encryption", $arrData)) {
            $builder->where('V.encryption', $arrData["encryption"]);
        }

        if (array_key_exists("idVehicle", $arrData)) {
            $builder->where('V.id_vehicle', $arrData["idVehicle"]);
        }

        if (array_key_exists("vehicleState", $arrData)) {
            $builder->where('V.state', $arrData["vehicleState"]);
        }

        if (array_key_exists("vinNumber", $arrData)) {
            if ($arrData["vinNumber"] != "false") {
                $builder->like('V.vin_number', $arrData["vinNumber"]);
            }
        }

        if (array_key_exists("vehicleType", $arrData)) {
            if ($arrData["vehicleType"] != "false") {
                $builder->where('V.fk_id_company', 1);
                $builder->where('T.inspection_type', $arrData["vehicleType"]);
            }
        }

        $builder->orderBy('V.unit_number', 'ASC');

        $query = $builder->get();

        $result = $query->getResultArray();

        return !empty($result) ? $result : false;
    }


	/**
	 * Info Planning for the Employee
	 * @since 27/8/2023
     * @review 20/03/2026 - new CI4 version
	 */
    public function get_planning_for_employee(array $arrData)
    {
        // Conectar al builder
        $builder = $this->db->table('programming P');

        $builder->select("P.date_programming, P.observation, X.job_description, W.*, CONCAT(V.unit_number,' -----> ', V.description) as unit_description, H.hora");
        $builder->join('programming_worker W', 'W.fk_id_programming = P.id_programming', 'INNER');
        $builder->join('param_jobs X', 'X.id_job = P.fk_id_job', 'INNER');
        $builder->join('param_vehicle V', 'V.id_vehicle = W.fk_id_machine', 'LEFT'); 
        $builder->join('param_horas H', 'H.id_hora = W.fk_id_hour', 'LEFT');

        // Filtros condicionales
        if (isset($arrData['idUser'])) {
            $builder->where('W.fk_id_programming_user', $arrData['idUser']);
        }
        if (isset($arrData['fecha'])) {
            $builder->where('P.date_programming', $arrData['fecha']);
        }
        if (isset($arrData['nextPlanning']) && $arrData['nextPlanning']) {
            $builder->where('P.date_programming >=', date('Y-m-d'));
        }

        $builder->where('P.state !=', 3);
        $builder->orderBy('P.date_programming', 'ASC');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray(); // CI4
        } else {
            return false;
        }
    }

	/**
	 * Lista de programacion
	 * @since 21/12/2020
     * @review 20/03/2026 - new CI4 version
	 */
	public function get_programming_info($arrData)
	{
        // Conectar al builder
        $builder = $this->db->table('programming P');

		$builder->select("P.*, X.id_job, X.job_description, U.id_user, CONCAT(U.first_name, ' ', U.last_name) name");
		$builder->join('user U', 'U.id_user = P.fk_id_user', 'INNER');
		$builder->join('param_jobs X', 'X.id_job = P.fk_id_job', 'INNER');

		if (isset($arrData["nextPlanning"])) {
			$currentDate = date('Y-m-d');
			$plus2 = date('Y-m-d', strtotime($currentDate . ' +2 days'));

			$builder->where('P.date_programming >', $currentDate);
			$builder->where('P.date_programming <', $plus2);
			$builder->where('P.state !=', 3);
		}
		if (isset($arrData["idProgramming"])) {
			$builder->where('P.id_programming', $arrData["idProgramming"]);
		}
		if (isset($arrData["fecha"])) {
			$builder->where('P.date_programming', $arrData["fecha"]);
		}
		if (isset($arrData["estado"])) {
			if ($arrData["estado"] == "ACTIVAS") {
				$builder->where('P.state !=', 3);
			} else {
				$builder->where('P.state', $arrData["estado"]);
			}
		}
		if (isset($arrData["from"]) && $arrData["from"] != '') {
			$builder->where('P.date_programming >=', $arrData["from"]);
		}
		if (isset($arrData["to"]) && $arrData["to"] != '' && $arrData["from"] != '') {
			$builder->where('P.date_programming <', $arrData["to"]);
		}

		$builder->orderBy("P.date_programming DESC");
		$query = $builder->get();

        $result = $query->getResultArray();

        return !empty($result) ? $result : false;
	}

	/**
	 * Maintenance Check list
	 * @since 13/3/2020
     * @review 20/03/2026 - new CI4 version
	 */
	public function get_maintenance_check()
	{
        $builder = $this->db->table('maintenance_check C');

		$builder->select();
		$builder->join('preventive_maintenance M', 'M.id_preventive_maintenance = C.fk_id_maintenance', 'INNER');
		$builder->join('maintenance_type T', 'T.id_maintenance_type = M.fk_id_maintenance_type', 'INNER');
		$builder->join('param_vehicle V', 'V.id_vehicle = M.fk_id_equipment', 'INNER');

		$builder->orderBy('V.unit_number', 'asc');
		$query = $builder->get();

        $result = $query->getResultArray();

        return !empty($result) ? $result : false;
	}

    /**
     * Contar registros del modulo VEHICLES
     * @author BMOTTAG
     * @review 20/03/2026 - new CI4 version
     */
    public function get_without_work_order()
    {
        $sql = "SELECT u.first_name, u.last_name, j.job_description, t.*
                FROM task t
                JOIN user u ON t.fk_id_user = u.id_user
                JOIN param_jobs j ON t.fk_id_job_finish = j.id_job
                WHERE t.wo_end_project IS NULL
                    AND t.hours_end_project IS NOT NULL
                    AND t.hours_end_project <> 0
                    AND t.start >= '2025-01-01'
                UNION ALL
                SELECT u.first_name, u.last_name, j.job_description, t.*
                FROM task t
                JOIN user u ON t.fk_id_user = u.id_user
                JOIN param_jobs j ON t.fk_id_job = j.id_job
                WHERE t.fk_id_programming IS NULL
                    AND t.start >= '2025-01-01'
                ORDER BY id_task DESC;";

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

	/**
	 * Task list
	 * Modules: Dashboard - Payroll
	 * @since 10/11/2016
     * @review 20/03/2026 - new CI4 version
	 */
	public function get_task($arrData)
	{
        $builder = $this->db->table('task T');
		$builder->select('T.*, id_user, first_name, last_name, log_user, J.job_description job_start, H.job_description job_finish, O.task');
		$builder->join('user U', 'U.id_user = T.fk_id_user', 'INNER');
		$builder->join('param_jobs J', 'J.id_job = T.fk_id_job', 'INNER');
		$builder->join('param_jobs H', 'H.id_job = T.fk_id_job_finish', 'LEFT');
		$builder->join('param_operation O', 'O.id_operation = T.fk_id_operation', 'INNER');

		if (isset($arrData["idTask"])) {
			$builder->where('id_task', $arrData["idTask"]);
		}
		if (isset($arrData["idEmployee"])) {
			$builder->where('U.id_user', $arrData["idEmployee"]);
		}
		if (isset($arrData["from"]) && $arrData["from"] != '') {
			$builder->where('T.start >=', $arrData["from"]);
		}
		if (isset($arrData["to"]) && $arrData["to"] != '' && $arrData["from"] != '') {
			$builder->where('T.start <', $arrData["to"]);
		}
		if (isset($arrData["toLimit"]) && $arrData["toLimit"] != '') {
			$builder->where('T.start <', $arrData["toLimit"]);
		}
		if (isset($arrData["fecha"])) {
			$builder->like('T.start', $arrData["fecha"]);
		}
		if (isset($arrData["idWorkOrder"])) {
			$builder->where($arrData["column"], $arrData["idWorkOrder"]);
		}

        $builder->orderBy('id_task', 'desc');

		if (isset($arrData["limit"])) {
            $builder->limit($arrData["limit"]);
		}
		$query = $builder->get();

        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * Safety´s list
	 * Modules: Dashboard
	 * @since 6/12/2016
	 * @review 9/3/2017
     * @review 20/03/2026 - new CI4 version
	 */
	public function get_safety($arrData)
	{
        $builder = $this->db->table('safety S');
		$builder->select("S.*, CONCAT(first_name, ' ', last_name) name, J.job_description");
		$builder->join('user U', 'U.id_user = S.fk_id_user', 'INNER');
		$builder->join('param_jobs J', 'J.id_job = S.fk_id_job', 'INNER');

		if (isset($arrData["idSafety"])) {
			$builder->where('S.id_safety', $arrData["idSafety"]);
		}
		if (isset($arrData["idJob"])) {
			$builder->where('S.fk_id_job', $arrData["idJob"]);
		}
		if (isset($arrData["fecha"])) {
			$fecha = $arrData["fecha"] . '%';
			$builder->where('S.date LIKE', $fecha);
		}

        $builder->orderBy('id_safety', 'desc');

		if (isset($arrData["limit"])) {
			$builder->limit($arrData["limit"]);
		}
		$query = $builder->get();

        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * Special inspection list
	 * Modules: Dashboard 
	 * @since 27/6/2017
     * @review 20/03/2026 - new CI4 version
	 */
	public function get_special_inspection_water_truck($arrData)
	{
        $builder = $this->db->table('inspection_watertruck I');
		$builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');

		if (isset($arrData["idEmployee"])) {
			$builder->where('U.id_user', $arrData["idEmployee"]);
		}

		$builder->orderBy('I.date_issue', 'desc');
		$builder->limit($arrData["limit"]);
		$query = $builder->get();


        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * Special inspection list
	 * Modules: Dashboard 
	 * @since 8/5/2017
     * @review 20/03/2026 - new CI4 version
	 */
	public function get_special_inspection_hydrovac($arrData)
	{
		$builder = $this->db->table('inspection_hydrovac I');
		$builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');

		if (isset($arrData["idEmployee"])) {
			$builder->where('U.id_user', $arrData["idEmployee"]);
		}

		$builder->orderBy('I.date_issue', 'desc');
		$builder->limit($arrData["limit"]);
		$query = $builder->get();

        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * Special inspection list
	 * Modules: Dashboard 
	 * @since 8/5/2017
     * @review 20/03/2026 - new CI4 version
	 */
	public function get_special_inspection_sweeper($arrData)
	{
        $builder = $this->db->table('inspection_sweeper I');
		$builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');

		if (isset($arrData["idEmployee"])) {
			$builder->where('U.id_user', $arrData["idEmployee"]);
		}

		$builder->orderBy('I.date_issue', 'desc');
		$query = $builder->get();

        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * Special inspection list
	 * Modules: Dashboard 
	 * @since 8/5/2017
     * @review 20/03/2026 - new CI4 version
     * @author BMOTTAG
	 */
	public function get_special_inspection_generator($arrData)
	{
		$builder = $this->db->table('inspection_generator I');
		$builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');

		if (isset($arrData["idEmployee"])) {
			$builder->where('U.id_user', $arrData["idEmployee"]);
		}

		$builder->orderBy('I.date_issue', 'desc');
		$query = $builder->get();

        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * menu list for a role
	 * Modules: MENU
	 * @since 2/4/2020
     * @review 20/03/2026 - new CI4 version
     * @author BMOTTAG
	 */
    public function get_role_menu($arrData)
    {
        $builder = $this->db->table('param_menu_permisos P');
        $builder->distinct();
        $builder->select();
        $builder->join('param_menu M', 'M.id_menu = P.fk_id_menu', 'INNER');

        if (array_key_exists("idRole", $arrData)) {
            $builder->where('P.fk_id_rol', $arrData["idRole"]);
        }
        if (array_key_exists("menuType", $arrData)) {
            $builder->where('M.menu_type', $arrData["menuType"]);
        }
        if (array_key_exists("menuState", $arrData)) {
            $builder->where('M.menu_state', $arrData["menuState"]);
        }

        $builder->orderBy('M.menu_order', 'ASC');

        $query = $builder->get();
        $result = $query->getResultArray();

        return !empty($result) ? $result : false;
    }

	/**
	 * Lista de permisos
	 * Modules: MENU
	 * @since 31/3/2020
     * @review 20/03/2026 - new CI4 version
      * @author BMOTTAG
	 */
	public function get_role_access($arrData)
	{
        $builder = $this->db->table('param_menu_permisos P');
		$builder->select('P.id_permiso, P.fk_id_menu, P.fk_id_link, P.fk_id_rol, M.menu_name, M.menu_order, M.menu_type, L.link_name, L.link_url, L.order, L.link_icon, L.link_type, R.rol_name, R.estilos');
		$builder->join('param_menu M', 'M.id_menu = P.fk_id_menu', 'INNER');
		$builder->join('param_menu_links L', 'L.id_link = P.fk_id_link', 'LEFT');
		$builder->join('param_rol R', 'R.id_rol = P.fk_id_rol', 'INNER');

		if (isset($arrData["idPermiso"])) {
			$builder->where('P.id_permiso', $arrData["idPermiso"]);
		}
		if (isset($arrData["idMenu"])) {
			$builder->where('P.fk_id_menu', $arrData["idMenu"]);
		}
		if (isset($arrData["idLink"])) {
			$builder->where('P.fk_id_link', $arrData["idLink"]);
		}
		if (isset($arrData["idRole"])) {
			$builder->where('P.fk_id_rol', $arrData["idRole"]);
		}
		if (isset($arrData["menuType"])) {
			$builder->where('M.menu_type', $arrData["menuType"]);
		}
		if (isset($arrData["linkState"])) {
			$builder->where('L.link_state', $arrData["linkState"]);
		}
		if (isset($arrData["menuURL"])) {
			$builder->where('M.menu_url', $arrData["menuURL"]);
		}
		if (isset($arrData["linkURL"])) {
			$builder->where('L.link_url', $arrData["linkURL"]);
		}

		$builder->orderBy('M.menu_order, L.order', 'asc');
        $query = $builder->get();
    
        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * Hauling list
	 * Modules: Dashboard 
	 * @since 13/1/2017
	 */
	public function get_hauling($arrData)
	{
		$builder = $this->db->table('hauling H');
		$builder->select("H.*, CONCAT(first_name, ' ', last_name) name, C.company_name, V.unit_number, T.truck_type, J.job_description site_from, Z.job_description site_to, M.material, P.payment");
		$builder->join('user U', 'U.id_user = H.fk_id_user', 'INNER');
		$builder->join('param_company C', 'C.id_company = H.fk_id_company', 'INNER');
		$builder->join('param_vehicle V', 'V.id_vehicle = H.fk_id_truck', 'LEFT');
		$builder->join('param_truck_type T', 'T.id_truck_type = H.fk_id_truck_type', 'LEFT');
		$builder->join('param_jobs J', 'J.id_job = H.fk_id_site_from', 'INNER');
		$builder->join('param_jobs Z', 'Z.id_job = H.fk_id_site_to', 'LEFT');
		$builder->join('param_material_type M', 'M.id_material = H.fk_id_material', 'LEFT');
		$builder->join('param_payment P', 'P.id_payment = H.fk_id_payment', 'LEFT');

		if (isset($arrData["idEmployee"])) {
			$builder->where('U.id_user', $arrData["idEmployee"]);
		}
		if (isset($arrData["fecha"])) {
			$builder->where('H.date_issue', $arrData["fecha"]);
		}
		if (isset($arrData["from"]) && $arrData["from"] != '') {
			$builder->where('H.date_issue >=', $arrData["from"]);
		}
		if (isset($arrData["to"]) && $arrData["to"] != '' && $arrData["from"] != '') {
			$builder->where('H.date_issue <', $arrData["to"]);
		}
		if (isset($arrData["state_delete"])) {
			$builder->where('H.state', 3);
		}
		if (isset($arrData["state_active"])) {
			$builder->where('H.state !=', 3);
		}

		$builder->orderBy('H.id_hauling', 'desc');

		if (isset($arrData["limit"])) {
			$builder->limit($arrData["limit"]);
		}
		$query = $builder->get();

        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * Daily inspection list
	 * Modules: Dashboard 
	 * @since 14/1/2017
	 * @review 20/03/2026 - new CI4 version
	  * @author BMOTTAG	
	 */
	public function get_daily_inspection($arrData)
	{
		$builder = $this->db->table('inspection_daily I');
		$builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');

		if (array_key_exists("idEmployee", $arrData)) {
			$builder->where('U.id_user', $arrData["idEmployee"]);
		}

		$builder->orderBy('I.date_issue', 'desc');
		$builder->limit($arrData["limit"]);
		$query = $builder->get();

        $result = $query->getResultArray();
        return !empty($result) ? $result : false;
	}

	/**
	 * Heavy inspection list
	 * Modules: Dashboard 
	 * @since 14/1/2017
	 * @review 20/03/2026 - new CI4 version
	  * @author BMOTTAG
	 */
	public function get_heavy_inspection($arrData)
	{
		$builder = $this->db->table('inspection_heavy I');
		$builder->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$builder->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$builder->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');

		if (isset($arrData["idEmployee"])) {
			$builder->where('U.id_user', $arrData["idEmployee"]);
		}

		$builder->orderBy('I.date_issue', 'desc');
		$builder->limit($arrData["limit"]);
		$query = $builder->get();

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Work Order
	 * @since 21/12/2020
	 * @review 20/03/2026 - new CI4 version
	 * @author BMOTTAG
	 */
	public function get_workorder_info($arrData)
	{
		$builder = $this->db->table('workorder W');
		$builder->select("W.*, CONCAT(first_name, ' ', last_name) name, J.id_job, J.job_description, C.*");
		$builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
		$builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');
		$builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');

		if (isset($arrData["jobId"]) && $arrData["jobId"] != '' && $arrData["jobId"] != 0) {
			$builder->where('W.fk_id_job', $arrData["jobId"]);
		}
		if (isset($arrData["idClaim"])) {
			$builder->where('W.fk_id_claim', $arrData["idClaim"]);
		}
		if (isset($arrData["idWorkOrder"]) && $arrData["idWorkOrder"] != '' && $arrData["idWorkOrder"] != 0) {
			$builder->where('W.id_workorder', $arrData["idWorkOrder"]);
		}
		if (isset($arrData["idWorkOrderFrom"]) && $arrData["idWorkOrderFrom"] != '' && $arrData["idWorkOrderFrom"] != 0) {
			$builder->where('W.id_workorder >=', $arrData["idWorkOrderFrom"]);
		}
		if (isset($arrData["idWorkOrderTo"]) && $arrData["idWorkOrderTo"] != '' && $arrData["idWorkOrderTo"] != 0) {
			$builder->where('W.id_workorder <=', $arrData["idWorkOrderTo"]);
		}
		if (isset($arrData["from"]) && $arrData["from"] != '') {
			$builder->where('W.date >=', $arrData["from"]);
		}
		if (isset($arrData["to"]) && $arrData["to"] != '' && $arrData["from"] != '') {
			$builder->where('W.date <', $arrData["to"]);
		}
		if (isset($arrData["state"]) && $arrData["state"] != '') {
			$builder->where('W.state', $arrData["state"]);
		}
		if (isset($arrData["fecha"])) {
			$builder->where('W.date', $arrData["fecha"]);
		}

		$builder->orderBy('W.id_workorder', 'desc');
		$query = $builder->get();

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Forceaccounts info
	 * @since 05/05/2025
	 * @review 20/03/2026 - new CI4 version
	 */
	public function get_forceaccount_info($arrData)
	{
		$builder = $this->db->table('forceaccount W');
		$builder->select('W.*, J.id_job, job_description, CONCAT(U.first_name, " ", U.last_name) name, C.company_name company, C.id_company, A.id_acs');
		$builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');
		$builder->join('param_company C', 'C.id_company = W.fk_id_company', 'LEFT');
		$builder->join('acs A', 'A.fk_id_workorder = W.id_forceaccount', 'LEFT');
		$builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');

		if (isset($arrData["from"]) && $arrData["from"] != '') {
			$builder->where('W.date >=', $arrData["from"]);
		}
		if (isset($arrData["to"]) && $arrData["to"] != '' && $arrData["from"] != '') {
			$builder->where('W.date <', $arrData["to"]);
		}

		$builder->orderBy('W.id_forceaccount', 'desc');
		$query = $builder->get();

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Check In List
	 * @since 1/6/2022
	 */
	public function get_checkin($arrDatos)
	{
		$builder = $this->db->table('new_checkin C');
		$builder->select();
		$builder->join('new_workers W', 'W.id_worker = C.fk_id_worker', 'INNER');
		$builder->join('param_jobs J', 'J.id_job = C.fk_id_job', 'INNER');
		if (isset($arrDatos["idCheckin"])) {
			$builder->where('C.id_checkin', $arrDatos["idCheckin"]);
		}
		if (isset($arrDatos["idJob"])) {
			$builder->where('C.fk_id_job', $arrDatos["idJob"]);
		}
		if (isset($arrDatos["today"])) {
			$builder->where('C.checkin_date', $arrDatos["today"]);
		}
		if (isset($arrDatos["checkout"])) {
			$builder->where('C.checkout_time', '0000-00-00 00:00:00');
		}
		$builder->orderBy('C.fk_id_job, C.id_checkin', 'asc');
		$query = $builder->get();

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}


	/**
	 * User list
	 * @since 30/3/2020
	 * @review 20/03/2026 - new CI4 version
	 */
	public function get_user($arrData)
	{
		$builder = $this->db->table('user U');
		$builder->select();
		$builder->join('param_rol R', 'R.id_rol = U.perfil', 'INNER');
		if (isset($arrData["idUser"])) {
			$builder->where('U.id_user', $arrData["idUser"]);
		}
		if (isset($arrData["idUserMANAGERS"])) {
			$IDmagers = array(2, 3);
			$builder->whereIn('U.id_user', $IDmagers);
		}
		if (isset($arrData["state"])) {
			$builder->where('U.state', $arrData["state"]);
		}
		//list without inactive users
		if (isset($arrData["filtroState"])) {
			$builder->where('U.state !=', 2);
		}
		if (isset($arrData["employee_subcontractor"])) {
			$builder->where('U.employee_subcontractor', $arrData["employee_subcontractor"]);
		}
		if (isset($arrData["idRolesSupervisors"])) {
			$idRoles = array(ID_ROL_SUPER_ADMIN, ID_ROL_MANAGER, ID_ROL_SAFETY, ID_ROL_SUPERVISOR);
			$builder->whereIn('U.perfil', $idRoles);
			$builder->where('U.id_user !=', 1);
		}

		$builder->orderBy("first_name, last_name", "ASC");
		$query = $builder->get();

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Get user certificates
	 * @since 15/1/2022
	 * @review 20/03/2026 - new CI4 version
	 */
	public function get_user_certificates($arrData)
	{
		$builder = $this->db->table('user_certificates X');
		$builder->select();
		$builder->join('user U', 'U.id_user = X.fk_id_user', 'INNER');
		$builder->join('param_certificates C', 'C.id_certificate = X.fk_id_certificate ', 'INNER');
		if (isset($arrData["idUserCertificate"])) {
			$builder->where('X.id_user_certificate', $arrData["idUserCertificate"]);
		}
		if (isset($arrData["idUser"])) {
			$builder->where('U.id_user', $arrData["idUser"]);
		}
		if (isset($arrData["state"])) {
			$builder->where('U.state', $arrData["state"]);
		}
		if (isset($arrData["expires"])) {
			$builder->where('X.expires', $arrData["expires"]);
		}
		if (isset($arrData["idCertificate"])) {
			$builder->where('C.id_certificate', $arrData["idCertificate"]);
		}
		if (isset($arrData["date"])) {
			$builder->where('X.date_through <=', $arrData["date"]);
			$builder->where('X.expires', 1);
		}
		$builder->orderBy('C.certificate', 'asc');
		$query = $builder->get();

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Get workorder expenses info
	 * @since 18/4/2023
	 */
	public function get_certificate_list($arrData)
	{
		$builder = $this->db->table('param_certificates');
		if (isset($arrData["idCertificate"])) {
			$builder->where('id_certificate', $arrData["idCertificate"]);
		}
		$builder->orderBy('certificate', 'asc');
		$query = $builder->get();

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}

	public function get_certificates_with_users($arrData = [])
	{
		$builder = $this->db->table('param_certificates C');

		$builder->select('
			C.id_certificate,
			C.certificate,
			C.certificate_description,
			U.id_user,
			U.first_name,
			U.last_name,
			X.date_through
		');

		$builder->join('user_certificates X', 'X.fk_id_certificate = C.id_certificate', 'left');
		$builder->join('user U', 'U.id_user = X.fk_id_user', 'left');

		// filtros
		if (!empty($arrData["idCertificate"])) {
			$builder->where('C.id_certificate', $arrData["idCertificate"]);
		}

		if (!empty($arrData["date"])) {
			$builder->where('X.date_through <=', $arrData["date"]);
			$builder->where('X.expires', 1);
		}

		$builder->orderBy('C.certificate', 'asc');

		$query = $builder->get();

		return $query->getResultArray();
	}

	/**
	 * Get JOBs 
	 * @since 03/01/2025
	 */
	public function get_job($arrData)
	{
		$builder = $this->db->table('param_jobs J');
		$builder->join('param_company C', 'C.id_company = J.fk_id_company', 'LEFT');
		$builder->join('param_company_foreman F', 'F.fk_id_job = J.id_job', 'LEFT');
		if (isset($arrData["idJob"])) {
			$builder->where('J.id_job', $arrData["idJob"]);
		}
		if (isset($arrData["state"])) {
			$builder->where('state', $arrData["state"]);
		}
		if (isset($arrData["withLIC"])) {
			$builder->where('flag_upload_details', 1);
		}
		$builder->orderBy("J.job_description", "asc");
		$query = $builder->get();

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Verificar si el job code ya existe en la base de datos
	 * @author BMOTTAG
	 * @since  30/12/2022
	 */
	public function jobCodeVerify($arrData)
	{
		$builder = $this->db->table('param_jobs');
		if (isset($arrData["idJob"])) {
			$builder->where('id_job !=', $arrData["idJob"]);
		}

		$builder->where($arrData["column"], $arrData["value"]);
		$query = $builder->get();

		$result = $query->getResultArray();
		return !empty($result) ? true : false;
	}

	/**
	 * Get NOTIFICATION ACCESS
	 * @since 22/12/2022
	 * @review 12/05/2026 - new CI4 version
	 */
	public function get_notifications_access(array $arrData): array|false
	{
		$builder = $this->db->table('notifications_access A');
		$builder->select("
			A.*,
			N.notification,
			N.description,
			CONCAT(U.first_name, ' ', U.last_name) AS name_email,
			U.email,
			U.id_user AS id_user_email,
			CONCAT(X.first_name, ' ', X.last_name) AS name_sms,
			X.movil,
			X.id_user AS id_user_sms
		");
		$builder->join('notifications N', 'N.id_notification = A.fk_id_notification', 'INNER');
		$builder->join('user U', 'JSON_CONTAINS(A.fk_id_user_email, JSON_QUOTE(CAST(U.id_user AS CHAR)))', 'LEFT');
		$builder->join('user X', 'JSON_CONTAINS(A.fk_id_user_sms, JSON_QUOTE(CAST(X.id_user AS CHAR)))', 'LEFT');

		if (array_key_exists('idNotificationAccess', $arrData)) {
			$builder->where('A.id_notification_access', $arrData['idNotificationAccess']);
		}

		if (array_key_exists('idNotification', $arrData)) {
			$builder->where('A.fk_id_notification', $arrData['idNotification']);
		}

		$builder->orderBy('N.notification', 'ASC');
		$results = $builder->get()->getResultArray();

		if (empty($results)) {
			return false;
		}

		$uniqueEmails   = [];
		$uniqueMoviles  = [];

		foreach ($results as &$item) {
			if (in_array($item['email'], $uniqueEmails)) {
				$item['email']      = '';
				$item['name_email'] = '';
			} else {
				$uniqueEmails[] = $item['email'];
			}

			if (in_array($item['movil'], $uniqueMoviles)) {
				$item['movil']    = '';
				$item['name_sms'] = '';
			} else {
				$uniqueMoviles[] = $item['movil'];
			}
		}

		return $results;
	}

	public function get_notifications_access_view(array $arrData = []): array
	{
		$builder = $this->db->table('notifications_access A');

		$builder->select("
			A.*, 
			N.notification, 
			N.description, 
			GROUP_CONCAT(DISTINCT CONCAT(U.first_name, ' ', U.last_name) ORDER BY U.id_user ASC SEPARATOR ', ') AS name_email, 
			GROUP_CONCAT(DISTINCT U.email ORDER BY U.id_user ASC SEPARATOR ', ') AS email, 
			GROUP_CONCAT(DISTINCT CONCAT(X.first_name, ' ', X.last_name) ORDER BY X.id_user ASC SEPARATOR ', ') AS name_sms, 
			GROUP_CONCAT(DISTINCT X.movil ORDER BY X.id_user ASC SEPARATOR ', ') AS movil
		");

		$builder->join('notifications N', 'N.id_notification = A.fk_id_notification', 'inner');

		// JOIN con JSON (se deja como string porque Query Builder no lo parsea)
		$builder->join(
			'user U',
			"JSON_CONTAINS(A.fk_id_user_email, JSON_QUOTE(CAST(U.id_user AS CHAR)))",
			'left'
		);

		$builder->join(
			'user X',
			"JSON_CONTAINS(A.fk_id_user_sms, JSON_QUOTE(CAST(X.id_user AS CHAR)))",
			'left'
		);

		// Filtros
		if (!empty($arrData["idNotificationAccess"])) {
			$builder->where('A.id_notification_access', $arrData["idNotificationAccess"]);
		}

		if (!empty($arrData["idNotification"])) {
			$builder->where('A.fk_id_notification', $arrData["idNotification"]);
		}

		$builder->groupBy('A.id_notification_access');
		$builder->orderBy('N.notification', 'ASC');

		$query = $builder->get();

		return $query->getNumRows() > 0
			? $query->getResultArray()
			: [];
	}

	public function getAvailableNotifications()
	{
		$builder = $this->db->table('notifications n');

		$builder->select('n.*');
		$builder->join(
			'notifications_access na',
			'n.id_notification = na.fk_id_notification',
			'left'
		);

		$builder->where('na.fk_id_notification IS NULL');
		$builder->where('n.setup', 1);

		return $builder->get()->getResultArray();
	}

	/**
	 * Equipment by Type
	 * @author BMOTTAG
	 * @since  24/06/2023
	 */
	public function equipmentByTypeList(): array
	{
		$builder = $this->db->table('param_vehicle_type_2');

		$builder->distinct();
		$builder->select('inspection_type, header_inspection_type');

		return $builder->get()->getResultArray();
	}


	/**
	 * Get dayoff info
	 * @since 7/12/2016
	 * @review 6/2/2017
	 */
	public function get_day_off($arrData)
	{
		$idUser = session()->get("id");

		$firstDay = (new \DateTime())->modify('-6 months')->format('Y-m-d');
		$beforeYesterday = (new \DateTime())->modify('-2 days')->format('Y-m-d');

		$builder = $this->db->table('dayoff D');
		$builder->select("D.*, CONCAT(first_name, ' ', last_name) name");
		$builder->join('user U', 'U.id_user = D.fk_id_user', 'INNER');

		// empleado
		if (isset($arrData["idEmployee"])) {
			$builder->where('U.id_user', $idUser);
		}

		// estado
		if (isset($arrData["state"])) {
			$builder->where('D.state', $arrData["state"]);

			if ($arrData["state"] > 1) {
				$builder->where('D.date_dayoff >=', $beforeYesterday);
			}
		}

		// id específico
		if (isset($arrData["idDayoff"])) {
			$builder->where('D.id_dayoff', $arrData["idDayoff"]);
		}

		// últimos 6 meses
		$builder->where('D.date_issue >=', $firstDay);

		$builder->orderBy('D.id_dayoff', 'DESC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Lista de menu
	 * Modules: MENU
	 * @since 30/3/2020
	 */
	public function get_menu($arrData)
	{
		$builder = $this->db->table('param_menu');
		if (isset($arrData["idMenu"])) {
			$builder->where('id_menu', $arrData["idMenu"]);
		}
		if (isset($arrData["menuType"])) {
			$builder->where('menu_type', $arrData["menuType"]);
		}
		if (isset($arrData["menuState"])) {
			$builder->where('menu_state', $arrData["menuState"]);
		}
		if (isset($arrData["columnOrder"])) {
			$builder->orderBy($arrData["columnOrder"], 'asc');
		} else {
			$builder->orderBy('menu_order', 'asc');
		}

		return $builder->get()->getResultArray();
	}

	/**
	 * Lista de enlaces
	 * Modules: MENU
	 * @since 31/3/2020
	 */
	public function get_links($arrData)
	{
		$builder = $this->db->table('param_menu_links L');

		$builder->join('param_menu M', 'M.id_menu = L.fk_id_menu', 'INNER');

		if (isset($arrData["idMenu"])) {
			$builder->where('fk_id_menu', $arrData["idMenu"]);
		}
		if (isset($arrData["idLink"])) {
			$builder->where('id_link', $arrData["idLink"]);
		}
		if (isset($arrData["linkType"])) {
			$builder->where('link_type', $arrData["linkType"]);
		}
		if (isset($arrData["linkState"])) {
			$builder->where('link_state', $arrData["linkState"]);
		}

		$builder->orderBy('M.menu_order, L.order', 'asc');
		return $builder->get()->getResultArray();
	}

	/**
	 * Get job employee_type_unit_price
	 * @since 27/11/2017
	 */
	public function get_job_employee_type_unit_price($idJob)
	{
		$builder = $this->db->table('job_employee_type_price JE');
		$builder->select();
		$builder->join('param_employee_type PE', 'PE.id_employee_type = JE.fk_id_employee_type ', 'INNER');
		$builder->where('JE.fk_id_job', $idJob);
		$builder->orderBy('PE.employee_type', 'asc');
		return $builder->get()->getResultArray();
	}

	/**
	 * Get equipment list
	 * Param int $companyType -> 1: Lev West; 2: Subcontractor
	 * @since 6/11/2020
	 */
	public function get_equipment_info_by($arrData)
	{
		$builder = $this->db->table('param_vehicle A');

		$builder->select('
			A.id_vehicle,
			A.make,
			A.unit_number,
			A.model,
			T.type_2,
			A.equipment_unit_price,
			A.equipment_unit_cost,
			A.equipment_unit_price_without_driver
		');

		$builder->join('param_vehicle_type_2 T', 'T.id_type_2 = A.type_level_2', 'INNER');

		if (isset($arrData["idVehicle"])) {
			$builder->where('A.id_vehicle', $arrData["idVehicle"]);
		}
		if (isset($arrData["vehicleState"])) {
			$builder->where('A.state', $arrData["vehicleState"]);
		}
		if (isset($arrData["companyType"])) {
			$builder->where('A.type_level_1', $arrData["companyType"]);
		}

		$builder->orderBy('T.inspection_type, A.unit_number', 'ASC');
		return $builder->get()->getResultArray();
	}

	/**
	 * Get equipment list
	 * Param int $companyType -> 1: Lev West; 2: Subcontractor
	 * @since 6/11/2020
	 */
	public function get_equipment_price(array $arrData)
	{
		$builder = $this->db->table('job_equipment_price JE');
		$builder->select('JE.*, id_vehicle, make, unit_number,model, type_2');
		$builder->join('param_vehicle A', 'A.id_vehicle = JE.fk_id_equipment', 'INNER');
		$builder->join('param_vehicle_type_2 T', 'T.id_type_2 = A.type_level_2', 'INNER');
		$builder->where('JE.fk_id_job', $arrData["idJob"]);
		if (isset($arrData["vehicleState"])) {
			$builder->where('A.state', $arrData["vehicleState"]);
		}
		if (isset($arrData["companyType"])) {
			$builder->where('A.type_level_1', $arrData["companyType"]);
		}

		$builder->orderBy('T.inspection_type, A.unit_number', 'ASC');
		return $builder->get()->getResultArray();
	}

	/**
	 * Get job hazard info
	 * @since 27/11/2017
	 */
	public function get_job_hazards($idJob)
	{
		$builder = $this->db->table('job_hazards H');
		$builder->select();
		$builder->join('param_hazard PH', 'PH.id_hazard = H.fk_id_hazard', 'INNER');
		$builder->join('param_hazard_activity PA', 'PA.id_hazard_activity = PH.fk_id_hazard_activity', 'INNER');
		$builder->join('param_hazard_priority PP', 'PP.id_priority = PH.fk_id_priority', 'INNER');
		$builder->where('H.fk_id_job', $idJob);
		$builder->orderBy('PA.hazard_activity, PH.hazard_description', 'asc');

		return $builder->get()->getResultArray();
	}

	/**
	 * Get safety subcontractor workers info
	 * @since 26/2/2016
	 */
	public function get_safety_subcontractors_workers($arrData)
	{
		$builder = $this->db->table('safety_workers_subcontractor W');
		$builder->select();
		$builder->join('param_company C', 'C.id_company = W.fk_id_company', 'INNER');
		if (isset($arrData["idSafetySubcontractor"]) && $arrData["idSafetySubcontractor"] != 'x') {
			$builder->where('W.id_safety_subcontractor', $arrData["idSafetySubcontractor"]);
		}
		if (isset($arrData["idSafety"])) {
			$builder->where('W.fk_id_safety', $arrData["idSafety"]);
		}
		if (isset($arrData["movilNumber"])) {
			$where = "W.worker_movil_number != ''";
			$builder->where($where);
		}
		$builder->orderBy('C.company_name, W.worker_name', 'asc');

		return $builder->get()->getResultArray();
	}

	/**
	 * Get validate user credentials
	 * @since 26/1/2023
	 */
	public function validateCredentials($arrData)
	{
		$login = $arrData["login"];
		$passwd = $arrData["passwd"];

		$builder = $this->db->table('user');
		$builder->where('id_user', $arrData["idUser"]);
		$builder->where('log_user', $login);

		$query = $builder->get();
		$row = $query->getRowArray();

		if (!$row) {
			return false;
		}

		// ✅ Verificación segura del password
		if (password_verify($passwd, $row['password'])) {
			return $row;
		}

		return false;
	}

	/**
	 * Get Companys List
	 * @since 13/02/2025
	 */
	public function get_company($arrData)
	{
		$builder = $this->db->table('param_company C');
		if (isset($arrData["idCompany"])) {
			$builder->where('C.id_company', $arrData["idCompany"]);
		}
		if (isset($arrData["company_type"])) {
			$builder->where('C.company_type', $arrData["company_type"]);
		}
		if (isset($arrData["allSubcontractors"])) {
			$types = array(2, 3);
			$builder->whereIn('C.company_type', $types);
		}
		if (isset($arrData["isHauling"])) {
			$builder->where('C.does_hauling', 1);
		}
		$builder->orderBy("C.company_name", "ASC");
		return $builder->get()->getResultArray();
	}

	/**
	 * tool_box list
	 * para año vigente
	 * @since 24/10/2017
	 */
	public function get_tool_box($arrDatos)
	{
		$builder = $this->db->table('tool_box T');
		$builder->select('T.*, CONCAT(U.first_name, " " , U.last_name) name, J.id_job, J.job_description');
		$builder->join('param_jobs J', 'J.id_job = T.fk_id_job', 'INNER');
		$builder->join('user U', 'U.id_user = T.fk_id_user', 'INNER');
		if (isset($arrDatos["idJob"])) {
			$builder->where('fk_id_job', $arrDatos["idJob"]);
		}
		if (isset($arrDatos["fecha"])) {
			$builder->where('date_tool_box', $arrDatos["fecha"]);
		}
		if (isset($arrDatos["idToolBox"])) {
			$builder->where('id_tool_box', $arrDatos["idToolBox"]);
		}
		$builder->orderBy('id_tool_box', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Excavation and Trenching Plan list
	 * For current year
	 * @since 1/08/2021
	 */
	public function get_excavation($arrDatos)
	{
		$builder = $this->db->table('job_excavation E');
		$builder->select('E.*, CONCAT(W.first_name, " " , W.last_name) name, CONCAT(U.first_name, " " , U.last_name) manager, CONCAT(X.first_name, " " , X.last_name) operator, CONCAT(Z.first_name, " " , Z.last_name) supervisor, J.id_job, J.job_description');
		$builder->join('param_jobs J', 'J.id_job = E.fk_id_job', 'INNER');
		$builder->join('user W', 'W.id_user = E.fk_id_user', 'INER');
		$builder->join('user U', 'U.id_user = E.fk_id_user_manager', 'LEFT');
		$builder->join('user X', 'X.id_user = E.fk_id_user_operator', 'LEFT');
		$builder->join('user Z', 'Z.id_user = E.fk_id_user_supervisor', 'LEFT');
		if (isset($arrDatos["idJob"])) {
			$builder->where('fk_id_job', $arrDatos["idJob"]);
		}
		if (isset($arrDatos["fecha"])) {
			$builder->where('date_excavation', $arrDatos["fecha"]);
		}
		if (isset($arrDatos["idExcavation"])) {
			$builder->where('id_job_excavation', $arrDatos["idExcavation"]);
		}

		$builder->orderBy('id_job_excavation', 'ASC');
		return $builder->get()->getResultArray();
	}

	/**
	 * confined space entry permit list
	 * @since 13/1/2020
	 */
	public function get_confined_space($arrDatos)
	{
		$builder = $this->db->table('job_confined C');
		$builder->select('C.*, CONCAT(W.first_name, " " , W.last_name) name_post_entry, CONCAT(U.first_name, " " , U.last_name) name, J.id_job, J.job_description, CONCAT(X.first_name, " " , X.last_name) user_authorization, CONCAT(Z.first_name, " " , Z.last_name) user_cancellation');
		$builder->join('param_jobs J', 'J.id_job = C.fk_id_job', 'INNER');
		$builder->join('user U', 'U.id_user = C.fk_id_user', 'INNER');
		$builder->join('user X', 'X.id_user = C.fk_id_user_authorization', 'INNER');
		$builder->join('user Z', 'Z.id_user = C.fk_id_user_cancellation', 'INNER');
		$builder->join('user W', 'W.id_user = C.fk_id_post_entry_user', 'INNER');

		if (isset($arrDatos["idJob"]) && $arrDatos["idJob"] != 'x') {
			$builder->where('fk_id_job', $arrDatos["idJob"]);
		}

		if (isset($arrDatos["idConfined"])) {
			$builder->where('id_job_confined', $arrDatos["idConfined"]);
		}

		if (isset($arrDatos["from"])) {
			$builder->where('date_confined >=', $arrDatos["from"]);
		}
		if (isset($arrDatos["to"])) {
			$builder->where('date_confined <=', $arrDatos["to"]);
		}

		$builder->orderBy('id_job_confined', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Get Excavation workers info
	 * @since 2/8/2021
	 */
	public function get_excavation_workers($arrData)
	{
		$builder = $this->db->table('job_excavation_workers W');
		$builder->select("W.*, CONCAT(first_name, ' ', last_name) name");
		$builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
		if (array_key_exists("idExcavation", $arrData)) {
			$builder->where('W.fk_id_job_excavation', $arrData["idExcavation"]);
		}
		$builder->orderBy('U.first_name, U.last_name', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Get Excavation subcontractor workers info
	 * @since 2/8/2021
	 */
	public function get_excavation_subcontractors($arrData)
	{
		$builder = $this->db->table('job_excavation_subcontractor W');
		$builder->select();
		$builder->join('param_company C', 'C.id_company = W.fk_id_company', 'INNER');
		if (array_key_exists("idExcavation", $arrData)) {
			$builder->where('W.fk_id_job_excavation', $arrData["idExcavation"]);
		}
		if (array_key_exists("idSubcontractor", $arrData) && $arrData["idSubcontractor"] != 'x') {
			$builder->where('W.id_excavation_subcontractor', $arrData["idSubcontractor"]);
		}
		if (array_key_exists("movilNumber", $arrData)) {
			$where = "W.worker_movil_number != ''";
			$builder->where($where);
		}
		$builder->orderBy('C.company_name, W.worker_name', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Payroll period list
	 * @since 9/02/2022
	 * @review 30/04/2026 - new CI4 version
	 */
	public function get_period(array $arrData)
	{
		$builder = $this->db->table('payroll_period');

		if (array_key_exists('idPeriod', $arrData)) {
			$builder->where('id_period', $arrData['idPeriod']);
		}
		if (array_key_exists('year_period', $arrData)) {
			$builder->where('year_period', $arrData['year_period']);
		}
		$builder->orderBy('id_period', 'DESC');

		if (array_key_exists('limit', $arrData)) {
			$query = $builder->get($arrData['limit']);
		} else {
			$query = $builder->get();
		}

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Payroll weak period list
	 * @since 9/02/2022
	 * @review 30/04/2026 - new CI4 version
	 */
	public function get_weak_period(array $arrData)
	{
		$builder = $this->db->table('payroll_period_weaks');

		if (array_key_exists('idPeriodWeak', $arrData)) {
			$builder->where('id_period_weak', $arrData['idPeriodWeak']);
		}
		if (array_key_exists('idPeriod', $arrData)) {
			$builder->where('fk_id_period', $arrData['idPeriod']);
		}
		$builder->orderBy('id_period_weak', 'DESC');

		if (array_key_exists('limit', $arrData)) {
			$query = $builder->get($arrData['limit']);
		} else {
			$query = $builder->get();
		}

		$result = $query->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Task list grouped by user
	 * Modules: Payroll/search
	 * @since 10/02/2022
	 * @review 30/04/2026 - new CI4 version
	 */
	public function get_users_by_period(array $arrData)
	{
		$builder = $this->db->table('task T');
		$builder->select('T.fk_id_user');
		$builder->join('user U', 'U.id_user = T.fk_id_user', 'INNER');
		$builder->join('payroll_period_weaks W', 'W.id_period_weak = T.fk_id_weak_period', 'LEFT');
		$builder->join('payroll_period P', 'P.id_period = W.fk_id_period', 'INNER');

		if (array_key_exists('idPeriod', $arrData)) {
			$builder->where('P.id_period', $arrData['idPeriod']);
		}
		if (array_key_exists('idEmployee', $arrData) && $arrData['idEmployee'] != '') {
			$builder->where('T.fk_id_user', $arrData['idEmployee']);
		}
		if (array_key_exists('from', $arrData)) {
			$builder->where('T.start >=', $arrData['from']);
		}
		if (array_key_exists('to', $arrData)) {
			$builder->where('T.start <=', $arrData['to']);
		}
		if (array_key_exists('employee_subcontractor', $arrData)) {
			$builder->where('U.employee_subcontractor', $arrData['employee_subcontractor']);
		}
		$builder->groupBy('T.fk_id_user');
		$builder->orderBy('U.first_name, U.last_name', 'ASC');

		$result = $builder->get()->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Task list by period and user
	 * Modules: Payroll/search
	 * @since 11/02/2022
	 * @review 30/04/2026 - new CI4 version
	 */
	public function get_task_by_period(array $arrData)
	{
		$builder = $this->db->table('task T');
		$builder->select("T.*, CONCAT(first_name, ' ', last_name) name, id_user, W.period_weak, J.job_description job_start, H.job_description job_finish");
		$builder->join('user U', 'U.id_user = T.fk_id_user', 'INNER');
		$builder->join('param_jobs J', 'J.id_job = T.fk_id_job', 'INNER');
		$builder->join('param_jobs H', 'H.id_job = T.fk_id_job_finish', 'LEFT');
		$builder->join('payroll_period_weaks W', 'W.id_period_weak = T.fk_id_weak_period', 'LEFT');
		$builder->join('payroll_period P', 'P.id_period = W.fk_id_period', 'INNER');

		if (array_key_exists('idUser', $arrData)) {
			$builder->where('U.id_user', $arrData['idUser']);
		}
		if (array_key_exists('idPeriod', $arrData)) {
			$builder->where('P.id_period', $arrData['idPeriod']);
		}
		if (array_key_exists('weakNumber', $arrData)) {
			$builder->where('W.weak_number', $arrData['weakNumber']);
		}
		if (array_key_exists('from', $arrData)) {
			$builder->where('T.start >=', $arrData['from']);
		}
		if (array_key_exists('to', $arrData)) {
			$builder->where('T.start <=', $arrData['to']);
		}
		$builder->orderBy('id_task', 'ASC');

		$result = $builder->get()->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Paystub list by period or employee
	 * Modules: Payroll/search
	 * @since 25/02/2022
	 * @review 30/04/2026 - new CI4 version
	 */
	public function get_paystub_by_period(array $arrData)
	{
		$builder = $this->db->table('payroll_paystub P');
		$builder->select("P.*, CONCAT(U.first_name, ' ', U.last_name) name, CONCAT(W.first_name, ' ', W.last_name) employee, W.address, W.postal_code, X.date_start, X.date_finish");
		$builder->join('user U', 'U.id_user = P.paystub_fk_id_user', 'INNER');
		$builder->join('user W', 'W.id_user = P.fk_id_employee', 'INNER');
		$builder->join('payroll_period X', 'X.id_period = P.fk_id_period', 'INNER');

		if (array_key_exists('idPaytsub', $arrData)) {
			$builder->where('P.id_paystub', $arrData['idPaytsub']);
		}
		if (array_key_exists('idEmployee', $arrData) && $arrData['idEmployee'] != '') {
			$builder->where('P.fk_id_employee', $arrData['idEmployee']);
		}
		if (array_key_exists('idPeriod', $arrData)) {
			$builder->where('P.fk_id_period', $arrData['idPeriod']);
		}
		if (array_key_exists('year', $arrData)) {
			$builder->where('X.year_period', $arrData['year']);
		}
		$builder->orderBy('W.first_name, W.last_name, P.fk_id_period', 'ASC');

		$result = $builder->get()->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Total paystub by year and employee
	 * Modules: Payroll/search
	 * @since 27/02/2022
	 * @review 30/04/2026 - new CI4 version
	 */
	public function get_total_yearly(array $arrData)
	{
		$builder = $this->db->table('payroll_total_yearly Y');
		$builder->select("Y.*, CONCAT(U.first_name, ' ', U.last_name) employee");
		$builder->join('user U', 'U.id_user = Y.fk_id_employee', 'INNER');

		if (array_key_exists('idUser', $arrData) && $arrData['idUser'] != '') {
			$builder->where('Y.fk_id_employee', $arrData['idUser']);
		}
		if (array_key_exists('year', $arrData)) {
			$builder->where('Y.year', $arrData['year']);
		}
		$builder->orderBy('U.first_name, U.last_name, year', 'ASC');

		$result = $builder->get()->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Save bank time balance
	 * @since 9/9/2022
	 * @review 30/04/2026 - new CI4 version
	 */
	public function saveBankTimeBalance(array $arrData): bool
	{
		$idUser = session()->get('id');

		return $this->db->table('payroll_bank_time')->insert([
			'fk_id_period'    => $arrData['idPeriod'],
			'fk_id_employee'  => $arrData['idEmployee'],
			'time_in'         => $arrData['bankTimeAdd'],
			'time_out'        => $arrData['bankTimeSubtract'],
			'balance'         => $arrData['bankNewBalance'],
			'change_done_by'  => $idUser,
			'observation'     => $arrData['observation'],
			'date_issue'      => date('Y-m-d G:i:s'),
		]);
	}

	/**
	 * Payroll check - tasks without finish time
	 * @since 12/04/2022
	 * @review 30/04/2026 - new CI4 version
	 */
	public function get_payroll_check()
	{
		$builder = $this->db->table('task T');
		$builder->select('T.*, id_user, first_name, last_name, log_user, J.job_description job_start, H.job_description job_finish, O.task');
		$builder->join('user U', 'U.id_user = T.fk_id_user', 'INNER');
		$builder->join('param_jobs J', 'J.id_job = T.fk_id_job', 'INNER');
		$builder->join('param_jobs H', 'H.id_job = T.fk_id_job_finish', 'LEFT');
		$builder->join('param_operation O', 'O.id_operation = T.fk_id_operation', 'INNER');
		$builder->where("(T.finish IS NULL OR CAST(T.finish AS CHAR) = '0000-00-00 00:00:00')", null, false);
		$builder->orderBy('id_task', 'DESC');

		$result = $builder->get()->getResultArray();
		return !empty($result) ? $result : false;
	}

	public function get_payroll_full(array $arrData)
	{
		$builder = $this->db->table('task T');

		$builder->select("
			T.*,
			U.id_user,
			U.first_name,
			U.last_name,
			CONCAT(U.first_name, ' ', U.last_name) AS employee_name,
			J.job_description AS job_start,
			H.job_description AS job_finish,
			W.period_weak
		");

		$builder->join('user U', 'U.id_user = T.fk_id_user', 'INNER');
		$builder->join('param_jobs J', 'J.id_job = T.fk_id_job', 'INNER');
		$builder->join('param_jobs H', 'H.id_job = T.fk_id_job_finish', 'LEFT');
		$builder->join('payroll_period_weaks W', 'W.id_period_weak = T.fk_id_weak_period', 'LEFT');
		$builder->join('payroll_period P', 'P.id_period = W.fk_id_period', 'INNER');

		if (!empty($arrData['idEmployee'])) {
			$builder->where('T.fk_id_user', $arrData['idEmployee']);
		}

		if (!empty($arrData['from'])) {
			$builder->where('T.start >=', $arrData['from']);
		}

		if (!empty($arrData['to'])) {
			$builder->where('T.start <=', $arrData['to']);
		}

		$builder->orderBy('U.first_name, U.last_name, T.start', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Employee bank time list
	 * @since 11/9/2022
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	public function get_bank_time($arrData)
	{
		$builder = $this->db->table('payroll_bank_time T');
		$builder->select("T.*, CONCAT(U.first_name, ' ', U.last_name) employee, CONCAT(W.first_name, ' ', W.last_name) done_by, X.period");
		$builder->join('user U', 'U.id_user = T.fk_id_employee', 'INNER');
		$builder->join('user W', 'W.id_user = T.change_done_by', 'INNER');
		$builder->join('payroll_period X', 'X.id_period = T.fk_id_period', 'LEFT');

		if (array_key_exists('idUser', $arrData)) {
			$builder->where('T.fk_id_employee', $arrData['idUser']);
		}

		$builder->orderBy('id_bank_time', 'DESC');

		if (array_key_exists('limit', $arrData)) {
			$query = $builder->get($arrData['limit']);
		} else {
			$query = $builder->get();
		}

		return $query->getNumRows() >= 1 ? $query->getResultArray() : false;
	}

	/**
	 * Lista de programacion
	 * @since 15/1/2018
	 */
	public function get_programming($arrData)
	{
		$year = date('Y');
		$firstDay = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year - 1));

		$builder = $this->db->table('programming P');
		$builder->select("P.*, X.id_job, X.job_description, X.fk_id_company id_company, U.id_user, CONCAT(U.first_name, ' ', U.last_name) name");
		$builder->join('user U', 'U.id_user = P.fk_id_user', 'INNER');
		$builder->join('param_jobs X', 'X.id_job = P.fk_id_job', 'INNER');

		if (array_key_exists("idUser", $arrData)) {
			$builder->where('P.fk_id_user', $arrData["idUser"]);
		}
		if (array_key_exists("idProgramming", $arrData)) {
			$builder->where('P.id_programming', $arrData["idProgramming"]);
		}
		if (array_key_exists("jobId", $arrData)) {
			$builder->where('P.fk_id_job', $arrData["jobId"]);
		}
		if (array_key_exists("idParent", $arrData)) {
			$builder->where('P.parent_id', $arrData["idParent"]);
		}
		if (array_key_exists("fecha", $arrData)) {
			$builder->where('P.date_programming', $arrData["fecha"]);
		}
		if (array_key_exists("smsAutomatic", $arrData)) {
			$builder->where('X.planning_message', 1);
		}
		if (array_key_exists("estado", $arrData)) {
			if ($arrData["estado"] == "ACTIVAS") {
				$builder->where('P.state !=', 3);
			} else {
				$builder->where('P.state', $arrData["estado"]);
			}
		}

		$builder->where('P.date_issue >=', $firstDay); //se filtran por registros mayores al primer dia del año

		$builder->orderBy("P.date_programming DESC");
		$query = $builder->get();

		return $query->getResultArray();
	}

	/**
	 * Lista trabajadores para una programacion
	 * @since 15/1/2019
	 */
	public function get_programming_workers(array $arrData = [])
	{
		$builder = $this->db->table('programming_worker P');

		$builder->select("
			U.movil,
			CONCAT(U.first_name, ' ', U.last_name) as name,
			P.*,
			H.hora,
			H.formato_24
		");

		$builder->join('user U', 'U.id_user = P.fk_id_programming_user');
		$builder->join('param_horas H', 'H.id_hora = P.fk_id_hour', 'left');

		// filtros dinámicos
		if (!empty($arrData['idUser'])) {
			$builder->where('P.fk_id_programming_user', $arrData['idUser']);
		}

		if (!empty($arrData['idProgramming'])) {
			$builder->where('P.fk_id_programming', $arrData['idProgramming']);
		}

		if (!empty($arrData['createWO'])) {
			$builder->where('P.creat_wo', 1);
		}

		if (!empty($arrData['withEquipment'])) {
			$builder->where('P.fk_id_machine IS NOT NULL');
			$builder->where('P.fk_id_machine !=', '');
		}

		if (isset($arrData['safety'])) {
			$builder->where('P.safety', $arrData['safety']);
		}

		$builder->orderBy('U.first_name', 'ASC');
		$builder->orderBy('U.last_name', 'ASC');

		$query = $builder->get();

		return $query->getNumRows() > 0 ? $query->getResultArray() : [];
	}

	/**
	 * Lista de horas
	 * @since 18/1/2019
	 */
	public function get_horas()
	{
		$builder = $this->db->table('param_horas');
		$builder->orderBy('`order`', 'ASC');

		$query = $builder->get();

		return $query->getNumRows() > 0 ? $query->getResultArray() : [];
	}

	/**
	 * Lista de horas
	 * @since 18/1/2019
	 */
	public function get_day_off_planning(array $arrData = [])
	{
		$firstDay       = date('Y-m-d', strtotime('-2 months'));
		$actualDay      = date('Y-m-d');
		$afterTomorrow  = date('Y-m-d', strtotime('+2 days'));

		$builder = $this->db->table('dayoff D');

		$builder->select("
			U.id_user,
			CONCAT(U.first_name, ' ', U.last_name) AS name,
			GROUP_CONCAT(
				DATE_FORMAT(D.date_dayoff, '%d %b %Y')
				ORDER BY D.date_dayoff ASC
				SEPARATOR ', '
			) AS days_off
		");

		$builder->join('user U', 'U.id_user = D.fk_id_user', 'inner');

		// filtro planning
		if (!empty($arrData['forPlanning'])) {
			$builder->where('D.state', 2);
			$builder->where('D.date_dayoff >=', $actualDay);
			$builder->where('D.date_dayoff <=', $afterTomorrow);
		}

		// últimos 2 meses
		$builder->where('D.date_issue >=', $firstDay);

		$builder->groupBy('U.id_user');
		$builder->orderBy('U.first_name', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Verificacion si existe maquina programada para una fecha
	 * @since 8/1/2021
	 */
	public function get_programming_machine_vs_date_programming(array $arrData = []): bool
	{
		$builder = $this->db->table('programming_worker W');

		$builder->select('1'); // optimización: solo validar existencia
		$builder->join('programming P', 'P.id_programming = W.fk_id_programming', 'inner');

		if (!empty($arrData['idProgrammingWorker'])) {
			$builder->where('W.id_programming_worker !=', $arrData['idProgrammingWorker']);
		}

		if (!empty($arrData['fechaProgramming'])) {
			$builder->where('P.date_programming', $arrData['fechaProgramming']);
		}

		if (!empty($arrData['maquina']) && is_array($arrData['maquina'])) {
			$builder->whereIn('W.fk_id_machine', $arrData['maquina']);
		}

		return $builder->countAllResults() > 0;
	}

	public function get_missing_programming_inspecciones(array $arrData = [])
	{
		$machines = json_decode($arrData['maquina'] ?? '[]', true);

		if (empty($machines)) {
			return [];
		}

		$builder = $this->db->table('inspection_total');

		$registered_machines = $builder
			->select('fk_id_machine')
			->where('date_inspection', $arrData['fecha'] ?? null)
			->get()
			->getResultArray();

		$registered_ids = array_column($registered_machines, 'fk_id_machine');

		$missing_machines = array_diff($machines, $registered_ids);

		return empty($missing_machines) ? [] : $missing_machines;
	}

	/**
	 * Trucks list by company and type
	 * @since 8/3/2017
	 * @review 05/05/2026 - new CI4 version
	 */
	public function get_trucks_by_id2($idCompany, $type)
	{
		$sql = "SELECT id_vehicle, CONCAT(unit_number,' -----> ', description) as unit_description
				FROM param_vehicle
				WHERE fk_id_company = " . (int)$idCompany . " AND type_level_2 = " . (int)$type . " AND state = 1
				ORDER BY unit_number";

		$query  = $this->db->query($sql);
		$trucks = [];
		foreach ($query->getResult() as $row) {
			$trucks[] = ['id_truck' => $row->id_vehicle, 'unit_number' => $row->unit_description];
		}
		return $trucks;
	}

	/**
	 * Get Distinct Chapter list
	 * @since 25/1/2023
	 * @review 05/05/2026 - new CI4 version
	 */
	public function get_chapter_list($arrData)
	{
		$builder = $this->db->table('job_details');
		$builder->select('distinct(chapter_number), chapter_name');
		$builder->where('fk_id_job', $arrData['idJob']);
		$builder->orderBy('chapter_number', 'asc');
		$query = $builder->get();

		return $query->getNumRows() > 0 ? $query->getResultArray() : false;
	}

	/**
	 * Sumatoria de valores de porcentage para un job
	 * @author BMOTTAG
	 * @since 6/6/2023
	 * @review 05/05/2026 - new CI4 version
	 */
	public function sumPercentageByJob($arrDatos)
	{
		$sql = "SELECT ROUND(SUM(percentage),2) TOTAL FROM job_details WHERE fk_id_job = " . (int)$arrDatos['idJob'];
		$row = $this->db->query($sql)->getRow();
		return $row->TOTAL;
	}

	/**
	 * Get workorder expenses info (with workorder join)
	 * @since 13/1/2023
	 * @review 05/05/2026 - new CI4 version
	 */
	public function get_workorder_expense($arrData)
	{
		$builder = $this->db->table('workorder_expense E');
		$builder->join('workorder W', 'W.id_workorder = E.fk_id_workorder', 'INNER');
		$builder->join('job_details J', 'J.id_job_detail = E.fk_id_job_detail', 'INNER');

		if (array_key_exists('idWorkOrder', $arrData)) {
			$builder->where('E.fk_id_workorder', $arrData['idWorkOrder']);
		}
		if (array_key_exists('idJobDetail', $arrData)) {
			$builder->where('E.fk_id_job_detail', $arrData['idJobDetail']);
		}
		$query = $builder->get();

		return $query->getNumRows() > 0 ? $query->getResultArray() : false;
	}

	/**
	 * Get job detail list
	 * @since 2037/legacy
	 * @review 05/05/2026 - new CI4 version
	 */
	public function get_job_detail($arrData)
	{
		$builder = $this->db->table('job_details D');
		$builder->select('D.*, (SELECT ROUND(SUM(W.expense_value), 2)
			FROM workorder_expense W
			WHERE W.fk_id_job_detail = D.id_job_detail) AS expenses');

		if (array_key_exists('idJobDetail', $arrData)) {
			$builder->where('id_job_detail', $arrData['idJobDetail']);
		}
		if (array_key_exists('idJob', $arrData)) {
			$builder->where('fk_id_job', $arrData['idJob']);
		}
		if (array_key_exists('chapterNumber', $arrData)) {
			$builder->where('chapter_number', $arrData['chapterNumber']);
		}
		if (array_key_exists('status', $arrData)) {
			$builder->where('status', $arrData['status']);
		}
		$builder->orderBy('id_job_detail', 'asc');
		$query = $builder->get();

		return $query->getNumRows() > 0 ? $query->getResultArray() : false;
	}

	/**
	 * Get attachments by equipment
	 * @since 28/6/2023 (legacy)
	 * @review 05/05/2026 - new CI4 version
	 */
	public function get_attachments_by_equipment($arrDatos)
	{
		$builder = $this->db->table('param_attachments P');
		$builder->select('id_attachment, attachment_number, attachment_description');
		$builder->join('param_attachments_equipment A', 'A.fk_id_attachment = P.id_attachment', 'INNER');

		if (array_key_exists('idEquipment', $arrDatos)) {
			$builder->where('fk_id_equipment', $arrDatos['idEquipment']);
		}
		$builder->orderBy('attachment_number', 'asc');
		$query = $builder->get();

		return $query->getNumRows() > 0 ? $query->getResultArray() : false;
	}

	public function get_vehicle_info_for_planning(array $arrData = [])
	{
		$ids = $arrData['idValues'] ?? [];

		// soporta "1,2,3" o array
		if (!is_array($ids)) {
			$ids = explode(',', $ids);
		}

		if (empty($ids)) {
			return [];
		}

		$separator = !empty($arrData['forTextMessague']) ? " \n" : "<br>";

		$builder = $this->db->table('param_vehicle');

		$builder->select("
			GROUP_CONCAT(CONCAT(unit_number, '-', description) SEPARATOR '{$separator}') AS unit_description
		");

		$builder->whereIn('id_vehicle', $ids);

		$row = $builder->get()->getRowArray();

		return $row ?? [];
	}

	/**
	 * Sumatoria de horas de personal for Calendar
	 * @param $idWorkorder
	 * @param $idUser
	 * @author BMOTTAG
	 * @since  24/02/2025
	 * @review 05/05/2026 - new CI4 version
	 */
	public function countHoursPersonal($arrDatos)
	{
		$sql = "SELECT ROUND(SUM(hours),2) TOTAL";
		$sql .= " FROM workorder_personal P";
		$sql .= " WHERE P.fk_id_workorder =" . $arrDatos["idWorkorder"];
		$sql .= " AND P.fk_id_user =" . $arrDatos["idUser"];

		$query = $this->db->query($sql);
		$row = $query->getRow();
		return $row->TOTAL ?? 0;
	}

	/**
	 * Sumatoria total de costos para un job_detail
	 * @param $idJobDetail
	 * @author BMOTTAG
	 * @since  15/05/2025
	 * @review 08/05/2026 - new CI4 version
	 */
	public function get_total_cost_by_job_detail($arrData)
	{
		$builder = $this->db->table('claim_apus');
		$builder->selectSum('cost');
		$builder->where('fk_id_job_detail', $arrData['idJobDetail']);
		$row = $builder->get()->getRow();

		return $row->cost ?? 0;
	}

	/**
	 * Sumatoria de horas de personal en los equipos for Calendar
	 * @param $idWorkorder
	 * @param $idUser
	 * @author BMOTTAG
	 * @since  24/02/2025
	 * @review 05/05/2026 - new CI4 version
	 */
	public function countHoursEquipmentPersonal($arrDatos)
	{
		$sql = "SELECT ROUND(SUM(hours),2) TOTAL";
		$sql .= " FROM workorder_equipment P";
		$sql .= " WHERE P.fk_id_workorder =" . $arrDatos["idWorkorder"];
		$sql .= " AND P.operatedby =" . $arrDatos["idUser"];

		$query = $this->db->query($sql);
		$row = $query->getRow();
		return $row->TOTAL ?? 0;
	}

	/**
	 * Get get_claims_by_id_job_detail
	 * @since 16/05/2025
	 * @review 08/05/2026 - new CI4 version
	 */
	public function get_claims_by_id_job_detail($arrData)
	{
		$builder = $this->db->table('claim_apus A');
		$builder->select('C.claim_number, A.quantity, A.cost');
		$builder->join('claim C', 'C.id_claim = A.fk_id_claim', 'INNER');

		if (array_key_exists('idJobDetail', $arrData)) {
			$builder->where('A.fk_id_job_detail', $arrData['idJobDetail']);
		}

		$query = $builder->get();
		$result = $query->getResultArray();

		return $result ?: false;
	}

	/**
	 * Count Equipment by Type
	 * @since 14/06/2023
	 * @review 13/05/2026 - new CI4 version
	 */
	public function countEquipmentByType(): array|false
	{
		$builder = $this->db->table('param_vehicle A');

		$builder->select('
			T.inspection_type,
			T.header_inspection_type,
			COUNT(A.id_vehicle) as number
		');

		$builder->join(
			'param_vehicle_type_2 T',
			'T.id_type_2 = A.type_level_2',
			'INNER'
		);

		$builder->where('A.state', 1);

		$builder->groupBy([
			'T.inspection_type',
			'T.header_inspection_type'
		]);

		$result = $builder->get()->getResultArray();

		return !empty($result) ? $result : false;
	}

	/**
	 * Count SO by Status and Year
	 * @author BMOTTAG
	 * @since  14/06/2023
	 * @review 13/05/2026 - new CI4 version
	 */
	public function countSOByStatus(): array|false
	{
		$firstDay = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));

		$builder = $this->db->table('service_order S');

		$builder->select('
			P.status_name,
			P.status_slug,
			P.status_style,
			P.status_icon,
			COUNT(S.id_service_order) as number
		');

		$builder->join(
			'param_status P',
			'P.status_slug = S.service_status',
			'INNER'
		);

		$builder->where('P.status_key', 'serviceorder');
		$builder->where('S.created_at >=', $firstDay);

		$builder->groupBy([
			'P.status_name',
			'P.status_slug',
			'P.status_style',
			'P.status_icon'
		]);

		$result = $builder->get()->getResultArray();

		return !empty($result) ? $result : false;
	}

	/**
	 * Get Chat Info
	 * @since 29/5/2023
	 * @review 13/05/2026 - new CI4 version
	 */
	public function get_chat_info(array $arrData): array|false
	{
		$builder = $this->db->table('chat C');
		$builder->select("C.*, CONCAT(U.first_name, ' ', U.last_name) user_from, W.first_name user_to");
		$builder->join('user U', 'U.id_user = C.fk_id_user_from', 'INNER');
		$builder->join('user W', 'W.id_user = C.fk_id_user_to', 'LEFT');
		if (array_key_exists('idChat', $arrData)) {
			$builder->where('C.id_chat', $arrData['idChat']);
		}
		if (array_key_exists('idModule', $arrData)) {
			$builder->where('C.fk_id_module', $arrData['idModule']);
		}
		if (array_key_exists('module', $arrData)) {
			$builder->where('C.module', $arrData['module']);
		}
		$builder->orderBy('C.id_chat', 'ASC');
		$result = $builder->get()->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Save Chat
	 * @since 29/5/2023
	 * @review 13/05/2026 - new CI4 version
	 */
	public function saveChat(array $arrData): bool
	{
		return $this->db->table('chat')->insert([
			'fk_id_module'    => $arrData['fk_id_module'],
			'module'          => $arrData['module'],
			'fk_id_user_from' => session()->get('id'),
			'created_at'      => date('Y-m-d G:i:s'),
			'message'         => $arrData['message'],
		]);
	}

	/**
	 * Get vehicle oil change history (last 30 records)
	 * @since 17/1/2017
	 * @review 13/05/2026 - new CI4 version
	 */
	public function get_vehicle_oil_change(array $infoVehicle): array|false
	{
		$table   = $infoVehicle[0]['table_inspection'] . ' T';
		$idTable = 'T.' . $infoVehicle[0]['id_table_inspection'];

		$builder = $this->db->table('vehicle_oil_change A');
		$builder->select("A.*, T.comments, CONCAT(U.first_name, ' ', U.last_name) name");
		$builder->join('user U', 'U.id_user = A.fk_id_user', 'INNER');
		$builder->join($table, "$idTable = A.fk_id_inspection", 'LEFT');
		$builder->where('A.fk_id_vehicle', $infoVehicle[0]['id_vehicle']);
		$builder->orderBy('A.id_oil_change', 'DESC');
		$result = $builder->get()->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Get workers with equipment assigned in a programming
	 * @since 17/05/2026
	 * @review 17/05/2026 - new CI4 version
	 */
	public function get_programming_equipment(array $arrData): array|false
	{
		$builder = $this->db->table('programming_worker P');
		$builder->select('P.fk_id_programming_user, P.description, V.id_vehicle, V.type_level_2');
		$builder->join('param_vehicle V', "FIND_IN_SET(V.id_vehicle, REPLACE(REPLACE(P.fk_id_machine, '[', ''), ']', '')) > 0", 'left');
		$builder->where('P.fk_id_machine IS NOT NULL');
		$builder->where("P.fk_id_machine !=", '');

		if (isset($arrData['idProgramming'])) {
			$builder->where('P.fk_id_programming', $arrData['idProgramming']);
		}

		$result = $builder->get()->getResultArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Get the most recent unconfirmed programming worker by mobile number
	 * Used by the SMS webhook to identify which worker replied
	 * @since 27/8/2023
	 * @author BMOTTAG
	 * @review 17/05/2026 - new CI4 version
	 */
	public function get_programming_user(array $arrData): array|false
	{
		$builder = $this->db->table('programming_worker W');
		$builder->select("W.id_programming_worker, CONCAT(U.first_name, ' ', U.last_name) AS employee, Z.movil, P.date_programming, H.hora");
		$builder->join('user U',         'U.id_user = W.fk_id_programming_user', 'inner');
		$builder->join('programming P',  'P.id_programming = W.fk_id_programming', 'inner');
		$builder->join('user Z',         'Z.id_user = P.fk_id_user', 'inner');
		$builder->join('param_horas H',  'H.id_hora = W.fk_id_hour', 'left');
		$builder->where('W.confirmation', 2);
		$builder->where('W.sms_sent', 1);

		if (isset($arrData['movil'])) {
			$builder->where('U.movil', $arrData['movil']);
		}

		$builder->orderBy('W.id_programming_worker', 'DESC');
		$builder->limit(1);

		$result = $builder->get()->getRowArray();
		return !empty($result) ? $result : false;
	}

	/**
	 * Get job detail info joined with claim_apus for a specific claim
	 * @since 2072/legacy
	 * @review 18/05/2026 - new CI4 version
	 */
	public function get_job_detail_claims_info(array $arrData)
	{
		$builder = $this->db->table('job_details D');
		$builder->select('D.*, C.quantity quantity_claim, C.cost');
		$builder->join('claim_apus C', 'C.fk_id_job_detail = D.id_job_detail AND C.fk_id_claim = ' . (int) $arrData['idClaim'], 'inner');

		if (array_key_exists('idJobDetail', $arrData)) {
			$builder->where('id_job_detail', $arrData['idJobDetail']);
		}
		if (array_key_exists('idJob', $arrData)) {
			$builder->where('fk_id_job', $arrData['idJob']);
		}
		if (array_key_exists('chapterNumber', $arrData)) {
			$builder->where('chapter_number', $arrData['chapterNumber']);
		}
		if (array_key_exists('status', $arrData)) {
			$builder->where('status', $arrData['status']);
		}

		$builder->orderBy('id_job_detail', 'asc');
		$query = $builder->get();

		return $query->getNumRows() > 0 ? $query->getResultArray() : false;
	}

}