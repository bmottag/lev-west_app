<?php
namespace App\Modules\Template\Models;

use CodeIgniter\Model;

class TemplateModel extends Model
{
    protected $protectFields = false;

    /**
     * Add/Edit TEMPLATE
     * @since 14/6/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function saveTemplate(array $post, int $idUser): bool
    {
        $idTemplate = $post['hddId'] ?? '';

        $data = [
            'template_name'        => $post['templateName'],
            'template_description' => $post['description'],
            'location'             => $post['location'],
        ];

        if ($idTemplate === '') {
            $data['fk_id_user'] = $idUser;
            $data['date_issue'] = date('Y-m-d G:i:s');
            return $this->db->table('templates')->insert($data);
        } else {
            return $this->db->table('templates')->where('id_template', $idTemplate)->update($data);
        }
    }

    /**
     * Get workers of a template
     * @since 14/6/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_templates_workers(int|string $idUsedTemplate): array|false
    {
        $query = $this->db->table('template_used_workers W')
            ->select("W.id_template_used_worker, W.fk_id_template_used, W.signature, CONCAT(first_name, ' ', last_name) name")
            ->join('user U', 'U.id_user = W.fk_id_user', 'inner')
            ->where('W.fk_id_template_used', $idUsedTemplate)
            ->orderBy('U.first_name, U.last_name', 'asc')
            ->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Consulta de empleados para un template
     * @author BMOTTAG
     * @since 14/6/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_template_byIdworker_byIdSafety(int|string $idTemplate, int|string $idWorker): bool
    {
        $query = $this->db->table('template_used_workers')
            ->where('fk_id_template_used', $idTemplate)
            ->where('fk_id_user', $idWorker)
            ->get();

        return $query->getNumRows() === 1;
    }

    /**
     * Add TEMPLATE WORKER
     * @since 14/6/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function add_template_worker(int|string $idTemplate, array $workers): bool
    {
        if (empty($workers)) {
            return true;
        }

        foreach ($workers as $workerId) {
            $this->db->table('template_used_workers')->insert([
                'fk_id_template_used' => $idTemplate,
                'fk_id_user'          => $workerId,
            ]);
        }

        return true;
    }

    /**
     * Save one worker
     * @since 2/7/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function saveOneWorker(array $post): bool
    {
        return $this->db->table('template_used_workers')->insert([
            'fk_id_template_used' => $post['hddId'],
            'fk_id_user'          => $post['worker'],
        ]);
    }

    /**
     * Template info
     * @since 2/7/2017
     * @review 05/05/2026 - new CI4 version
     */
    public function get_template(array $arrData): array|false
    {
        $builder = $this->db->table('templates T')
            ->select("T.*, CONCAT(first_name, ' ', last_name) name")
            ->join('user U', 'U.id_user = T.fk_id_user', 'inner');

        if (isset($arrData['idTemplate'])) {
            $builder->where('T.id_template', $arrData['idTemplate']);
        }

        $query = $builder->get();

        return $query->getNumRows() > 0 ? $query->getResultArray() : false;
    }

    /**
     * Add/Edit VALVES
     * @since 15/04/2025
     * @review 05/05/2026 - new CI4 version
     */
    public function saveValve(array $post, int $idUser): bool
    {
        $idValve = $post['hddId'] ?? '';

        $data = [
            'valve_number'    => $post['valve_number'],
            'number_of_turns' => $post['number_of_turns'],
            'position'        => $post['position'],
            'status'          => $post['status'],
            'direction'       => $post['direction'],
            'rewarks'         => $post['rewarks'],
        ];

        if ($idValve === '') {
            $data['fk_id_user'] = $idUser;
            $data['date_issue'] = date('Y-m-d G:i:s');
            return $this->db->table('valves')->insert($data);
        } else {
            return $this->db->table('valves')->where('id_valve', $idValve)->update($data);
        }
    }
}
