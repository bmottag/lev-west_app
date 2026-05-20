<?php
namespace App\Modules\Dayoff\Models;

use CodeIgniter\Model;

class DayoffModel extends Model
{

	protected $protectFields = false;

	/**
	 * Add dayoff
	 * @since 7/12/2016
	 */
	public function add_dayoff(array $post)
	{
		$idUser = session()->get("id");

		// Validación básica
		if (empty($post['type']) || empty($post['date'])) {
			return false;
		}

		$data = [
			'fk_id_user'     => $idUser,
			'id_type_dayoff' => $post['type'],
			'date_issue'     => date("Y-m-d H:i:s"),
			'date_dayoff'    => $post['date'],
			'observation'    => $post['observation'] ?? null,
			'state'          => 1,
		];

		$builder = $this->db->table('dayoff');
		$result = $builder->insert($data);

		return $result ? $this->db->insertID() : false;
	}
		
	/**
	 * Update dayoff´s state
	 * @author BMOTTAG
	 * @since  8/12/2016
	 */
	public function update_dayoff(array $post)
	{
		$idUser = session()->get("id");

		// Validación básica
		if (empty($post['hddIdParam']) || !isset($post['state'])) {
			return false;
		}

		$idDayoff   = (int) $post['hddIdParam'];
		$state      = (int) $post['state'];
		$observation = $post['observation'] ?? null;

		$data = [
			'fk_id_boss'        => $idUser,
			'state'             => $state,
			'admin_observation' => $observation,
			'date_update'       => date("Y-m-d H:i:s"),
		];

		$builder = $this->db->table('dayoff');
		$builder->where('id_dayoff', $idDayoff);

		return $builder->update($data);
	}



}
