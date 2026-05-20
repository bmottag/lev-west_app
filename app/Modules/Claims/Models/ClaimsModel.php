<?php
namespace App\Modules\Claims\Models;

use CodeIgniter\Model;

class ClaimsModel extends Model
{
    protected $protectFields = false;

    /**
     * Claims list
     * @since 3/2/2021
     * @review 18/05/2026 - new CI4 version
     */
    public function get_claims(array $arrData)
    {
        $builder = $this->db->table('claim C');
        $builder->select('C.*, J.id_job, job_description, CONCAT(U.first_name, " ", U.last_name) name');
        $builder->join('param_jobs J', 'J.id_job = C.fk_id_job', 'inner');
        $builder->join('user U', 'U.id_user = C.fk_id_user', 'inner');

        if (!empty($arrData['idClaim'])) {
            $builder->where('id_claim', $arrData['idClaim']);
        }
        if (!empty($arrData['claimNumberSearch'])) {
            $builder->where('claim_number', $arrData['claimNumberSearch']);
        }
        if (!empty($arrData['idJob'])) {
            $builder->where('fk_id_job', $arrData['idJob']);
        }
        if (!empty($arrData['state'])) {
            $builder->where('current_status_claim', $arrData['state']);
        }

        $order = $arrData['order'] ?? 'desc';
        $builder->orderBy('id_claim', $order);

        if (isset($arrData['limit'])) {
            $builder->limit($arrData['limit']);
        }

        $query = $builder->get();
        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Guardar claim
     * @since 3/2/2021
     * @review 18/05/2026 - new CI4 version
     */
    public function guardarClaim(array $post)
    {
        $idClaim = $post['hddId'] ?? '';
        $idUser  = session()->get('id');

        $data = [
            'fk_id_user'        => $idUser,
            'claim_number'      => $post['claimNumber'],
            'fk_id_job'         => $post['id_job'],
            'observation_claim' => $post['observation'],
        ];

        if ($idClaim == '') {
            $data['current_status_claim'] = 1;
            $data['date_issue_claim']     = date('Y-m-d G:i:s');
            $this->db->table('claim')->insert($data);
            return $this->db->insertID();
        } else {
            $this->db->table('claim')->where('id_claim', $idClaim)->update($data);
            return $idClaim;
        }
    }

    /**
     * Save Claim LIC
     * @since 25/05/2025
     * @review 18/05/2026 - new CI4 version
     */
    public function saveClaimAPU(array $post)
    {
        $idClaim = $post['hddId'];
        $apus    = $post['apu'] ?? [];

        foreach ($apus as $idJobDetail) {
            $exists = $this->db->table('claim_apus')
                ->where('fk_id_claim', $idClaim)
                ->where('fk_id_job_detail', $idJobDetail)
                ->countAllResults() > 0;

            if (!$exists) {
                $ok = $this->db->table('claim_apus')->insert([
                    'fk_id_claim'      => $idClaim,
                    'fk_id_job_detail' => $idJobDetail,
                ]);
                if (!$ok) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Add Claim state
     * @since 5/2/2021
     * @review 18/05/2026 - new CI4 version
     */
    public function add_claim_state(array $arrData)
    {
        $data = [
            'fk_id_claim'             => $arrData['idClaim'],
            'fk_id_user_claim'        => session()->get('id'),
            'date_issue_claim_state'  => date('Y-m-d G:i:s'),
            'message_claim'           => $arrData['message'],
            'state_claim'             => $arrData['state'],
        ];

        return $this->db->table('claim_state')->insert($data);
    }

    /**
     * Claims list History
     * @since 5/2/2021
     * @review 18/05/2026 - new CI4 version
     */
    public function get_claims_history(array $arrData)
    {
        $builder = $this->db->table('claim_state C');
        $builder->select('C.*, U.first_name');
        $builder->join('user U', 'U.id_user = C.fk_id_user_claim', 'inner');

        if (array_key_exists('idClaim', $arrData)) {
            $builder->where('C.fk_id_claim', $arrData['idClaim']);
        }

        $builder->orderBy('id_claim_state', 'desc');
        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Update Claim current state and last message
     * @since 5/2/2021
     * @review 18/05/2026 - new CI4 version
     */
    public function update_claim(array $arrData)
    {
        $data = [
            'current_status_claim' => $arrData['state'],
            'last_message_claim'   => $arrData['message'],
        ];

        return $this->db->table('claim')
            ->where('id_claim', $arrData['idClaim'])
            ->update($data);
    }

    /**
     * Update WO state
     * @since 12/1/2021
     * @review 18/05/2026 - new CI4 version
     */
    public function updateWOStateFromClaimChange(array $WOList, int $claimState, string $message)
    {
        $idUser  = session()->get('id');
        $claimWO = $claimState == 2 ? 3 : 4;

        foreach ($WOList as $wo) {
            $this->db->table('workorder_state')->insert([
                'fk_id_workorder' => $wo['id_workorder'],
                'fk_id_user'      => $idUser,
                'date_issue'      => date('Y-m-d G:i:s'),
                'observation'     => $message,
                'state'           => $claimWO,
            ]);

            $this->db->table('workorder')
                ->where('id_workorder', $wo['id_workorder'])
                ->update(['state' => $claimWO, 'last_message' => $message]);
        }

        return true;
    }

    /**
     * Update APU information
     * @since 12/05/2025
     * @review 18/05/2026 - new CI4 version
     */
    public function updateInfoAPU(array $data)
    {
        return $this->db->table('claim_apus')
            ->where('fk_id_claim', $data['fk_id_claim'])
            ->where('fk_id_job_detail', $data['fk_id_job_detail'])
            ->update([
                'quantity' => $data['quantity'],
                'cost'     => $data['cost'],
            ]);
    }
}
