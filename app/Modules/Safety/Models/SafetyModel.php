<?php
namespace App\Modules\Safety\Models;

use CodeIgniter\Model;


class SafetyModel extends Model
{

	protected $protectFields = false;

	/**
	 * Payroll
	 * @since 4/02/2017
	 */
	public function get_safety_by_id($idSafety)
	{
		$builder = $this->db->table('safety S');

		$builder->select("S.*, CONCAT(first_name, ' ', last_name) name, J.job_description");
		$builder->join('user U', 'U.id_user = S.fk_id_user', 'INNER');
		$builder->join('param_jobs J', 'J.id_job = S.fk_id_job', 'INNER');
		
		$builder->where('S.id_safety', $idSafety);
		
		$builder->orderBy('S.date', 'asc');

		return $builder->get()->getResultArray();
	}

	/**
	 * Add SAFETY
	 * @since 3/12/2016
	 */
	public function add_safety(array $post)
	{
		$id = $post['hddIdentificador'] ?? null;

		$data = [
			'fk_id_operation' => $post['hddTask'] ?? null,
			'work' => $post['work'] ?? null,
			'fk_id_job' => $post['hddIdJob'] ?? null,
			'muster_point' => $post['musterPoint'] ?? null,
			'muster_point_2' => $post['musterPoint2'] ?? null,
			'primary_head_counter' => $post['primaryHeadCounter'] ?? null,
			'secondary_head_counter' => $post['secondaryHeadCounter'] ?? null,
			'specify_ppe' => $post['specify'] ?? null,
		];

		$ppe = $post['hddTask'] ?? null; // 1: con ppe; 2: sin ppe

		if(!empty($ppe)) {
			if($ppe == 'on') {
				$data['ppe'] = 1;
			} else if($ppe == 'off') {
				unset($data['ppe']);
			}
		} else {
			$data['ppe'] = 2;
		}

		$builder = $this->db->table('safety');

		if (empty($id)) {
			$data['fk_id_user'] = session()->get("id"); //solo se ingresa el usuario cuando se crea
			$data['date'] = date("Y-m-d G:i:s");//fecha del registro
			if ($builder->insert($data)) {
				return $this->db->insertID();
			}
			return false;
		} else {
			$update = $builder->where('id_safety', $id)
							->update($data);

			return $update ? $id : false;
		}
	}

	/**
	 * Get safety hazard info
	 * @since 4/12/2016
	 */
	public function get_safety_hazard($idSafety) 
	{		
		$builder = $this->db->table('safety_hazards H');
		$builder->select();
		$builder->join('param_hazard PH', 'PH.id_hazard = H.fk_id_hazard', 'INNER');
		$builder->join('param_hazard_activity PA', 'PA.id_hazard_activity = PH.fk_id_hazard_activity', 'INNER');
		$builder->join('param_hazard_priority PP', 'PP.id_priority = PH.fk_id_priority', 'INNER');
		$builder->where('H.fk_id_safety', $idSafety); 
		$builder->orderBy('PA.id_hazard_activity, PH.hazard_description', 'asc');
		return $builder->get()->getResultArray();
	}

	/**
	 * Get activity list
	 * @since 23/2/2017
	 */
	public function get_activity_list_by_job($idJob)
	{
		$builder = $this->db->table('job_hazards J');

		$builder->select('A.id_hazard_activity, A.hazard_activity');
		$builder->join('param_hazard H', 'H.id_hazard = J.fk_id_hazard', 'INNER');
		$builder->join('param_hazard_activity A', 'A.id_hazard_activity = H.fk_id_hazard_activity', 'INNER');
		$builder->where('J.fk_id_job', $idJob);

		$builder->groupBy(['A.id_hazard_activity', 'A.hazard_activity']);

		$builder->orderBy('A.hazard_activity', 'ASC');

		$query = $builder->get();

		return $query->getNumRows() > 0 ? $query->getResultArray() : false;
	}

	/**
	 * Get hazard list
	 * @since 17/05/2019
	 */
	public function get_hazard_list_by_job($idActivity, $idJob) 
	{		
		$builder = $this->db->table('job_hazards J');
		$builder->select();
		$builder->join('param_hazard H', 'H.id_hazard = J.fk_id_hazard', 'INNER');
		$builder->join('param_hazard_activity A', 'A.id_hazard_activity = H.fk_id_hazard_activity', 'INNER');
		$builder->where('A.id_hazard_activity', $idActivity);
		$builder->where('J.fk_id_job', $idJob);
		$builder->orderBy('A.hazard_activity, H.hazard_description', 'asc');
		return $builder->get()->getResultArray();
	}

	/**
	 * @author BMOTTAG
	 * @since 10/12/2016
	 * Consulta de hazards para un safety especifico
	 */
	public function get_selected_hazards($idSafety)
	{
		return $this->db->table('safety_hazards')
			->select('fk_id_hazard')
			->where('fk_id_safety', $idSafety)
			->get()
			->getResultArray();
	}

	/**
	 * Add SAFETY HAZARD
	 * @since 4/12/2016
	 * @review 10/12/2016
	 */
	public function add_safety_hazard(array $post = []): bool
	{
		$idSafety =  $post['hddId'];
		$hazards =  $post['hazards'];
		// 🔥 DELETE hazards
		$this->db->table('safety_hazards')
				->where('fk_id_safety', $idSafety)
				->delete();

		// 🔥 INSERT hazards
		if (!empty($hazards)) {
			$dataBatch = [];
			foreach ($hazards as $idHazard) {
				$dataBatch[] = [
					'fk_id_safety' => $idSafety,
					'fk_id_hazard' => (int)$idHazard
				];
			}

			return (bool) $this->db->table('safety_hazards')
								->insertBatch($dataBatch);
		}

		return true;
	}

	/**
	 * Get safety workers info
	 * @since 6/12/2016
	 */
	public function get_safety_workers($idSafety) 
	{		
		$builder = $this->db->table('safety_workers W');
		$builder->select("W.id_safety_worker, W.fk_id_safety, W.signature, W.fk_id_user, W.understanding, CONCAT(first_name, ' ', last_name) name");
		$builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
		$builder->where('W.fk_id_safety', $idSafety); 
		$builder->orderBy('U.first_name, U.last_name', 'asc');
		return $builder->get()->getResultArray();
	}

	public function get_selected_workers($idSafety)
	{
		return $this->db->table('safety_workers')
			->select('fk_id_user')
			->where('fk_id_safety', $idSafety)
			->get()
			->getResultArray();
	}

	/**
	 * Add SAFETY WORKER
	 * @since 6/12/2016
	 * @review 10/12/2016
	 */
	public function add_safety_worker(array $post = []): bool
	{
		$idSafety =  $post['hddId'];
		$workers =  $post['workers'];

		// 🔥 INSERT hazards
		if (!empty($workers)) {
			$dataBatch = [];
			foreach ($workers as $idWorker) {
				$dataBatch[] = [
					'fk_id_safety' => (int)$idSafety,
					'fk_id_user' => (int)$idWorker
				];
			}

			return (bool) $this->db->table('safety_workers')
								->insertBatch($dataBatch);
		}

		return true;
	}

	/**
	 * Save one worker
	 * @since 18/1/2017
	 */
	public function saveOneWorker(array $post = []): bool
	{							
		$data = [
			'fk_id_safety' => $post['hddId'] ?? null,
			'fk_id_user' => $post['worker'] ?? null,
		];

		$builder = $this->db->table('safety_workers');
		return $builder->insert($data);
	}

	/**
	 * Save subcontractor worker
	 * @since 26/2/2017
	 */
	public function saveSubcontractorWorker(array $post = []): bool
	{							
		$data = [
			'fk_id_safety' => $post['hddId'] ?? null,
			'fk_id_company' => $post['company'] ?? null,
			'worker_name' => $post['workerName'] ?? null,
			'worker_movil_number' => $post['phone_number'] ?? null,
		];

		$builder = $this->db->table('safety_workers_subcontractor');
		return $builder->insert($data);
	}

		
	



}
