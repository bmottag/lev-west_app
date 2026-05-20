<?php
namespace App\Modules\Employee\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{

	protected $protectFields = false;

	/**
	 * Add/Edit Attachment
	 * @since 23/06/2023
	 */
	public function saveAttachment(array $post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'attachment_number' => $post['attachment_number'] ?? null,
			'attachment_description' => $post['attachment_description'] ?? null,
		];

		$builder = $this->db->table('param_attachments');

		if (empty($id)) {
			if ($builder->insert($data)) {
				return $this->db->insertID();
			}
			return false;
		} else {
			$update = $builder->where('id_attachment', $id)
							->update($data);

			return $update ? $id : false;
		}
	}

	/**
	 * Job Log
	 * @since 20/02/2024
	 */
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


}
