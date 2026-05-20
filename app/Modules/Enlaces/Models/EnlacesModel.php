<?php
namespace App\Modules\Enlaces\Models;

use CodeIgniter\Model;


class EnlacesModel extends Model
{

	protected $protectFields = false;

	/**
	 * Add/Edit MENU
	 * @since 30/3/2020
	 */
	public function saveMenu(array $post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'menu_name' => $post['menu_name'] ?? null,
			'menu_url' => $post['menu_url'] ?? null,
			'menu_icon' => $post['menu_icon'] ?? null,
			'menu_order' => $post['order'] ?? null,
			'menu_type' => $post['menu_type'] ?? null,
			'menu_state' => $post['menu_state'] ?? null
		];

		$builder = $this->db->table('param_menu');

		if (empty($id)) {
			return $builder->insert($data);
		} else {
			return $builder->where('id_menu', $id)
							->update($data);
		}
	}

	/**
	 * Add/Edit LINK
	 * @since 31/3/2020
	 */
	public function saveLink(array $post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'fk_id_menu' => $post['id_menu'] ?? null,
			'link_name' => $post['link_name'] ?? null,
			'link_url' => $post['link_url'] ?? null,
			'link_icon' => $post['link_icon'] ?? null,
			'order' => $post['order'] ?? null,
			'link_state' => $post['link_state'] ?? null,
			'link_type' => $post['link_type'] ?? null
		];

		$builder = $this->db->table('param_menu_links');

		if (empty($id)) {
			$data['date_issue'] = date("Y-m-d G:i:s");
			return $builder->insert($data);
		} else {
			return $builder->where('id_link', $id)
							->update($data);
		}
	}

	/**
	 * Add/Edit LINK ACCESS
	 * @since 1/4/2020
	 */
	public function saveRoleAccess(array $post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'fk_id_menu' => $post['id_menu'] ?? null,
			'fk_id_link' => $post['id_link'] ?? null,
			'fk_id_rol' => $post['id_rol'] ?? null
		];

		$builder = $this->db->table('param_menu_permisos');

		if (empty($id)) {
			return $builder->insert($data);
		} else {
			return $builder->where('id_permiso', $id)
							->update($data);
		}
	}

	/**
	 * Add/Edit ENLACE
	 * @since 2/4/2018
	 */
	public function saveVideo(array $post)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'link_name' => $post['link_name'] ?? null,
			'link_url' => $post['link_url'] ?? null,
			'order' => $post['order'] ?? null,
			'link_state' => $post['link_state'] ?? null
		];

		$builder = $this->db->table('param_menu_links');

		if (empty($id)) {
			$data['fk_id_menu'] = 9;//menu manuals
			$data['link_icon'] = 'fa-hand-o-up';
			$data['date_issue'] = date("Y-m-d G:i:s");
			$data['link_type'] = 4;//Complete URL; Videos
			return $builder->insert($data);
		} else {
			return $builder->where('id_link', $id)
							->update($data);
		}
	}

	/**
	 * Add/Edit MANUAL
	 * @since 27/4/2018
	 */
	public function saveManual(array $post, $path)
	{
		$id = $post['hddId'] ?? null;

		$data = [
			'link_name' => $post['link_name'] ?? null,
			'link_url' => $path,
			'order' => $post['order'] ?? null,
			'link_state' => $post['link_state'] ?? null
		];

		$builder = $this->db->table('param_menu_links');

		if (empty($id)) {
			$data['fk_id_menu'] = 9;//menu manuals
			$data['link_icon'] = 'fa-hand-o-up';
			$data['date_issue'] = date("Y-m-d G:i:s");
			$data['link_type'] = 5;//Complete URL; Videos
			return $builder->insert($data);
		} else {
			return $builder->where('id_link', $id)
							->update($data);
		}
	}


}
