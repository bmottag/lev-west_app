<?php
namespace App\Modules\External\Models;

use CodeIgniter\Model;

class ExternalModel extends Model
{
    protected $protectFields = false;

    /**
     * Add employee
     * @since 31/01/2022
     * @review 05/05/2026 - new CI4 version
     */
    public function saveEmployee(array $post): bool
    {
        $passwd = str_replace(['<', '>', '[', ']', '*', '^', '-', "'", '='], '', $post['inputPassword'] ?? '');

        $data = [
            'first_name'      => $post['firstName'] ?? null,
            'last_name'       => $post['lastName'] ?? null,
            'log_user'        => $post['user'] ?? null,
            'password'        => password_hash($passwd, PASSWORD_DEFAULT),
            'social_insurance' => $post['insuranceNumber'] ?? null,
            'health_number'   => $post['healthNumber'] ?? null,
            'birthdate'       => $post['birth'] ?? null,
            'movil'           => $post['movilNumber'] ?? null,
            'email'           => $post['email'] ?? null,
            'address'         => $post['address'] ?? null,
            'perfil'          => 7,
            'state'           => 1,
        ];

        return $this->db->table('user')->insert($data);
    }

    /**
     * Add new worker
     * @since 1/06/2022
     * @review 05/05/2026 - new CI4 version
     */
    public function saveNewWorker(array $post): int|false
    {
        $data = [
            'worker_name'  => $post['new_name'] ?? null,
            'worker_movil' => $post['new_phone_number'] ?? null,
        ];

        if (!$this->db->table('new_workers')->insert($data)) {
            return false;
        }
        return (int) $this->db->insertID();
    }

    /**
     * Add checkin
     * @since 1/06/2022
     * @review 05/05/2026 - new CI4 version
     */
    public function saveCheckin(int $idWorker, array $post): int|false
    {
        $data = [
            'fk_id_worker' => $idWorker,
            'checkin_date' => date('Y-m-d'),
            'checkin_time' => date('Y-m-d G:i:s'),
            'fk_id_job'    => $post['idProject'] ?? null,
        ];

        if (!$this->db->table('new_checkin')->insert($data)) {
            return false;
        }
        return (int) $this->db->insertID();
    }

    /**
     * Update Checkin - Checkout
     * @since 4/06/2022
     * @review 05/05/2026 - new CI4 version
     */
    public function saveCheckout(array $post): bool
    {
        return $this->db->table('new_checkin')
            ->where('id_checkin', $post['hddId'] ?? null)
            ->update(['checkout_time' => date('Y-m-d G:i:s')]);
    }

    /**
     * Update dayoff's state
     * @author BMOTTAG
     * @since  27/12/2022
     * @review 05/05/2026 - new CI4 version
     */
    public function update_dayoff(array $post): bool
    {
        return $this->db->table('dayoff')
            ->where('id_dayoff', $post['hddIdDayOff'] ?? null)
            ->update([
                'fk_id_boss'        => $post['hddIdUser'] ?? null,
                'state'             => $post['status'] ?? null,
                'admin_observation' => $post['observation'] ?? '',
                'date_update'       => date('Y-m-d G:i:s'),
            ]);
    }
}
