<?php
namespace App\Modules\Incidences\Models;

use CodeIgniter\Model;


class IncidencesModel extends Model
{

	protected $protectFields = false;

	/**
	 * Near miss list
	 * para año vigente
	 * last 20 records
	 * @since 31/3/2017
	 */
	public function get_near_miss_by_idUser($arrDatos) 
	{
		$builder = $this->db->table('incidence_near_miss W');
		$year = date('Y');
		$firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year-1));
		
		$builder->select('W.*, CONCAT(U.first_name, " " , U.last_name) name, J.id_job, job_description, T.*, CONCAT(X.first_name, " " , X.last_name) supervisor, CONCAT(Y.first_name, " " , Y.last_name) coordinator');
		$builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'INNER');
		$builder->join('param_incident_type T', 'T.id_incident_type = W.fk_incident_type', 'INNER');
		$builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
		$builder->join('user X', 'X.id_user = W.manager_user', 'INNER');
		$builder->join('user Y', 'Y.id_user = W.safety_user', 'INNER');
		if (isset($arrDatos["idNearMiss"])) {
			$builder->where('id_near_miss', $arrDatos["idNearMiss"]);
		}
		if (isset($arrDatos["idEmployee"])) {
			$builder->where('W.fk_id_user', $arrDatos["idEmployee"]);
		}
		if (isset($arrDatos["jobId"])) {
			$builder->where('fk_id_job =', $arrDatos["jobId"]);
		}
		
		$builder->where('W.date_issue >=', $firstDay);
		
		$builder->orderBy('id_near_miss', 'DESC');
		$builder->limit(20);
		return $builder->get()->getResultArray();
	}

	/**
	 * Get incidente Persons involved
	 * @since 24/4/2021
	 */
	public function get_persons_involved($arrData) 
	{		
		$builder = $this->db->table('incidence_incident_person P');
		$builder->select();
		if (isset($arrData["idIncident"])) {
			$builder->where('P.fk_id_incident', $arrData["idIncident"]);
		}
		if (isset($arrData["form"])) {
			$builder->where('P.form_identifier', $arrData["form"]);
		}
		if (isset($arrData["movilNumber"])) {
			$where = "P.person_movil_number != ''";
			$builder->where($where);
		}
		$builder->orderBy('P.person_name', 'ASC');

		return $builder->get()->getResultArray();
	}

	/**
	 * Add near miss
	 * @since 29/3/2017
	 */
	public function add_near_miss(array $post)
	{
		$id = $post['hddIdentificador'] ?? null;

		$hour = $post['hour'] ?? null;
		$hour = $hour<10?"0".$hour:$hour;
		$time = $hour . ":" . $post['min'];

		$data = [
			'fk_incident_type' => $post['nearMissType'] ?? null,
			'what_happened' => $post['happened'] ?? null,
			'date_near_miss' => $post['date'] ?? null,
			'time' => $time,
			'location' => $post['location'] ?? null,
			'fk_id_job' => $post['jobName'] ?? null,
			'immediate_cause' => $post['cause'] ?? null,
			'uderlying_causes' => $post['uderlyingCauses'] ?? null,
			'corrective_actions' => $post['correctiveActions'] ?? null,
			'preventative_action' => $post['preventativeAction'] ?? null,
			'manager_user' => $post['manager'] ?? null,
			'safety_user' => $post['coordinator'] ?? null,
			'comments' => $post['comments'] ?? null
		];

		$builder = $this->db->table('incidence_near_miss');

		if (empty($id)) {
			$data['fk_id_user'] = session()->get('id');
			$data['date_issue'] = date("Y-m-d G:i:s");
			$data['state_incidence'] = 1;
			if ($builder->insert($data)) {
				return $this->db->insertID();
			}
			return false;
		} else {
			$update = $builder->where('id_near_miss', $id)
							->update($data);

			return $update ? $id : false;
		}
	}

	/**
	 * Save Person involved
	 * @since 24/4/2021
	 */			
	public function savePersonInvolved(array $post)
	{
		$data = [
			'fk_id_incident' => $post['hddId'] ?? null,
			'person_name' => $post['workerName'] ?? null,
			'person_movil_number' => $post['phone_number'] ?? null,
			'form_identifier' => $post['hddFormIdentifier'] ?? null,
		];

		$builder = $this->db->table('incidence_incident_person');
		return $builder->insert($data);
	}

	/**
	 * Update INCIDENCE
	 * @since 15/5/2017
	 */
	public function updateInfoSignature(array $arrDatos): bool
	{
		$table           = $arrDatos["table"];
		$signatureColumn = $arrDatos["signatureColumn"];
		$valSignature    = $arrDatos["valSignature"];
		$fechaColumn     = $arrDatos["fechaColumn"];
		$idColumn        = $arrDatos["idColumn"];
		$idValue         = $arrDatos["idValue"];

		$fecha = date("Y-m-d H:i:s");

		$builder = $this->db->table($table);

		$data = [
			$signatureColumn => $valSignature,
			$fechaColumn     => $fecha,
		];

		$builder->where($idColumn, $idValue);

		return $builder->update($data);
	}

	/**
	 * Add Incident
	 * @since 15/5/2017
	 */
	public function add_incident(array $post)
	{
		$id = $post['hddIdentificador'] ?? null;

		$hour = $post['hour'] ?? null;
		$hour = $hour<10?"0".$hour:$hour;
		$time = $hour . ":" . $post['min'];

		$data = [
			'fk_incident_type' => $post['incidentType'] ?? null,
			'what_happened' => $post['happened'] ?? null,
			'fk_id_job' => $post['jobName'] ?? null,
			'date_incident' => $post['date'] ?? null,
			'time' => $time,
			'call_manager' => $post['callManager'] ?? null,
			'immediate_cause' => $post['cause'] ?? null,
			'uderlying_causes' => $post['uderlyingCauses'] ?? null,
			'instruction_before' => $post['instructionBefore'] ?? null,
			'corrective_actions' => $post['correctiveActions'] ?? null,
			'preventative_action' => $post['preventativeAction'] ?? null,
			'manager_user' => $post['manager'] ?? null,
			'safety_user' => $post['coordinator'] ?? null,
			'comments' => $post['comments'] ?? null
		];

		$builder = $this->db->table('incidence_incident');

		if (empty($id)) {
			$data['fk_id_user'] = session()->get('id');
			$data['date_issue'] = date("Y-m-d G:i:s");
			$data['state_incidence'] = 1;
			if ($builder->insert($data)) {
				return $this->db->insertID();
			}
			return false;
		} else {
			$update = $builder->where('id_incident', $id)
							->update($data);

			return $update ? $id : false;
		}
	}

	/**
	 * Incident list
	 * para año vigente
	 * last 20 records
	 * @since 15/5/2017
	 */
	public function get_incident_by($arrDatos) 
	{
		$year = date('Y');
		$firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year-1));
		
		$builder = $this->db->table('incidence_incident W');
		$builder->select('W.*, T.*, J.id_job, job_description, CONCAT(U.first_name, " " , U.last_name) name, CONCAT(X.first_name, " " , X.last_name) supervisor, CONCAT(Y.first_name, " " , Y.last_name) coordinator');
		$builder->join('param_jobs J', 'J.id_job = W.fk_id_job', 'LEFT');
		$builder->join('param_incident_type T', 'T.id_incident_type = W.fk_incident_type', 'INNER');
		$builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
		$builder->join('user X', 'X.id_user = W.manager_user', 'INNER');
		$builder->join('user Y', 'Y.id_user = W.safety_user', 'INNER');
		
		if (isset($arrDatos["idIncident"])) {
			$builder->where('id_incident', $arrDatos["idIncident"]);
		}
		if (isset($arrDatos["idEmployee"])) {
			$builder->where('W.fk_id_user', $arrDatos["idEmployee"]);
		}
		if (isset($arrDatos["jobId"])) {
			$builder->where('fk_id_job =', $arrDatos["jobId"]);
		}
		
		$builder->where('W.date_issue >=', $firstDay);
		
		$builder->orderBy('id_incident', 'DESC');
		$builder->limit(20);
		return $builder->get()->getResultArray();
	}



}
