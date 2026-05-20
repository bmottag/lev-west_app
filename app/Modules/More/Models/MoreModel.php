<?php
namespace App\Modules\More\Models;

use CodeIgniter\Model;

class MoreModel extends Model
{
    protected $protectFields = false;

	/**
	 * environmental list
	 * @since 10/1/2018
	 */
    public function get_environmental($arrDatos)
    {
        $builder = $this->db->table('job_environmental E');
        $builder->select('E.*, CONCAT(R.first_name, " " , R.last_name) name, CONCAT(U.first_name, " " , U.last_name) inspector, CONCAT(X.first_name, " " , X.last_name) manager, J.id_job, J.job_description');
        $builder->join('param_jobs J', 'J.id_job = E.fk_id_job', 'INNER');
        $builder->join('user R', 'R.id_user = E.fk_id_user', 'INNER');
        $builder->join('user U', 'U.id_user = E.fk_id_user_inspector', 'LEFT');
        $builder->join('user X', 'X.id_user = E.fk_id_user_manager', 'LEFT');

        if (isset($arrDatos['idJob'])) {
            $builder->where('E.fk_id_job', $arrDatos['idJob']);
        }

        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

	/**
	 * Add environmental
	 * @since 13/1/2018
	 */
    public function add_environmental($post, $idUser)
    {
        $idEnvironmental = $post['hddIdentificador'] ?? '';

        $data = [
            'fk_id_user_inspector'       => $post['inspector'] ?? null,
            'fk_id_user_manager'         => $post['manager'] ?? null,
            'sites_watered'              => $post['sites_watered'] ?? null,
            'sites_watered_remarks'      => $post['sites_watered_remarks'] ?? null,
            'being_swept'                => $post['being_swept'] ?? null,
            'being_swept_remarks'        => $post['being_swept_remarks'] ?? null,
            'dusty_covered'              => $post['dusty_covered'] ?? null,
            'dusty_covered_remarks'      => $post['dusty_covered_remarks'] ?? null,
            'speed_control'              => $post['speed_control'] ?? null,
            'speed_control_remarks'      => $post['speed_control_remarks'] ?? null,
            'noise_permit'               => $post['noise_permit'] ?? null,
            'noise_permit_remarks'       => $post['noise_permit_remarks'] ?? null,
            'air_compressors'            => $post['air_compressors'] ?? null,
            'air_compressors_remarks'    => $post['air_compressors_remarks'] ?? null,
            'noise_mitigation'           => $post['noise_mitigation'] ?? null,
            'noise_mitigation_remarks'   => $post['noise_mitigation_remarks'] ?? null,
            'idle_plan'                  => $post['idle_plan'] ?? null,
            'idle_plan_remarks'          => $post['idle_plan_remarks'] ?? null,
            'garbage_bin'                => $post['garbage_bin'] ?? null,
            'garbage_bin_remarks'        => $post['garbage_bin_remarks'] ?? null,
            'disposed_periodically'      => $post['disposed_periodically'] ?? null,
            'disposed_periodically_remarks' => $post['disposed_periodically_remarks'] ?? null,
            'recycling_being'            => $post['recycling_being'] ?? null,
            'recycling_being_remarks'    => $post['recycling_being_remarks'] ?? null,
            'spill_containment'          => $post['spill_containment'] ?? null,
            'spill_containment_remarks'  => $post['spill_containment_remarks'] ?? null,
            'spillage_happen'            => $post['spillage_happen'] ?? null,
            'spillage_happen_remarks'    => $post['spillage_happen_remarks'] ?? null,
            'chemicals_stored'           => $post['chemicals_stored'] ?? null,
            'chemicals_stored_remarks'   => $post['chemicals_stored_remarks'] ?? null,
            'absorbing_chemical'         => $post['absorbing_chemical'] ?? null,
            'absorbing_chemical_remarks' => $post['absorbing_chemical_remarks'] ?? null,
            'spill_kits'                 => $post['spill_kits'] ?? null,
            'spill_kits_remarks'         => $post['spill_kits_remarks'] ?? null,
            'excessive_use'              => $post['excessive_use'] ?? null,
            'excessive_use_remarks'      => $post['excessive_use_remarks'] ?? null,
            'materials_stored'           => $post['materials_stored'] ?? null,
            'materials_stored_remarks'   => $post['materials_stored_remarks'] ?? null,
            'fire_extinguishers'         => $post['fire_extinguishers'] ?? null,
            'fire_extinguishers_remarks' => $post['fire_extinguishers_remarks'] ?? null,
            'preventive_actions'         => $post['preventive_actions'] ?? null,
            'preventive_actions_remarks' => $post['preventive_actions_remarks'] ?? null,
        ];

        if ($idEnvironmental == '') {
            $data['fk_id_user'] = $idUser;
            $data['fk_id_job'] = $post['hddIdJob'];
            $data['date_environmental'] = date('Y-m-d');
            $this->db->table('job_environmental')->insert($data);
            return $this->db->insertID();
        } else {
            $this->db->table('job_environmental')->where('id_job_environmental', $idEnvironmental)->update($data);
            return $idEnvironmental;
        }
    }

    public function get_ppe_inspection($arrDatos)
    {
        $builder = $this->db->table('ppe_inspection T');
        $builder->select('T.*, CONCAT(U.first_name, " " , U.last_name) name');
        $builder->join('user U', 'U.id_user = T.fk_id_user', 'INNER');

        if (isset($arrDatos['idPPEInspection'])) {
            $builder->where('id_ppe_inspection', $arrDatos['idPPEInspection']);
        }

        $builder->orderBy('id_ppe_inspection', 'desc');
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

    public function savePPEInspection($post, $idUser, $userRol)
    {
        $idPPEInspection = $post['hddId'] ?? '';
        $dateIssue = $post['date'] ?? '';

        $data = [
            'observation' => $post['observation'] ?? null,
        ];

        if ($idPPEInspection == '') {
            $data['fk_id_user'] = $idUser;
            if ($userRol == 99 && $dateIssue != '') {
                $data['date_ppe_inspection'] = $dateIssue;
            } else {
                $data['date_ppe_inspection'] = date('Y-m-d');
            }
            $this->db->table('ppe_inspection')->insert($data);
            return $this->db->insertID();
        } else {
            if ($userRol == 99 && $dateIssue != '') {
                $data['date_ppe_inspection'] = $dateIssue;
            }
            $this->db->table('ppe_inspection')->where('id_ppe_inspection', $idPPEInspection)->update($data);
            return $idPPEInspection;
        }
    }

    public function get_ppe_inspection_workers($idPPEInspection)
    {
        $builder = $this->db->table('ppe_inspection_workers W');
        $builder->select("W.*, CONCAT(first_name, ' ', last_name) name");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->where('W.fk_id_ppe_inspection', $idPPEInspection);
        $builder->orderBy('U.first_name, U.last_name', 'asc');
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

    public function get_ppe_inspection_byIdworker_byIdPPEIspection($idPPEInspection, $idWorker)
    {
        $builder = $this->db->table('ppe_inspection_workers');
        $builder->where('fk_id_ppe_inspection', $idPPEInspection);
        $builder->where('fk_id_user', $idWorker);
        return $builder->countAllResults() == 1;
    }

    public function add_ppe_inspection_worker($idPPEInspection, $workers)
    {
        if (empty($workers)) return true;
        foreach ($workers as $workerId) {
            $this->db->table('ppe_inspection_workers')->insert([
                'fk_id_ppe_inspection' => $idPPEInspection,
                'fk_id_user'           => $workerId,
                'date_issue'           => date('Y-m-d G:i:s'),
            ]);
        }
        return true;
    }

    public function update_ppe_inspection_worker($post)
    {
        $data = [
            'safety_boots'    => $post['safety_boots'] ?? null,
            'hart_hat'        => $post['hart_hat'] ?? null,
            'reflective_vest' => $post['reflective_vest'] ?? null,
            'safety_glasses'  => $post['safety_glasses'] ?? null,
            'gloves'          => $post['gloves'] ?? null,
        ];
        $this->db->table('ppe_inspection_workers')
                 ->where('id_ppe_inspection_worker', $post['hddIdPPEInspectionWorker'])
                 ->update($data);
        return true;
    }

    public function addOneWorker($post)
    {
        $this->db->table('ppe_inspection_workers')->insert([
            'fk_id_ppe_inspection' => $post['hddIdPPEInspection'],
            'fk_id_user'           => $post['worker'],
            'date_issue'           => date('Y-m-d G:i:s'),
        ]);
        return $this->db->insertID() > 0;
    }

    public function add_confined($post, $idUser, $userRol)
    {
        $idConfined = $post['hddIdentificador'] ?? '';

        $fechaStart  = ($post['start_date'] ?? '') . ' ' . ($post['start_hour'] ?? '00') . ':' . ($post['start_min'] ?? '00') . ':00';
        $fechaFinish = ($post['finish_date'] ?? '') . ' ' . ($post['finish_hour'] ?? '00') . ':' . ($post['finish_min'] ?? '00') . ':00';

        $data = [
            'completed_flha'           => $post['completed_flha'] ?? null,
            'location'                 => $post['location'] ?? null,
            'purpose'                  => $post['purpose'] ?? null,
            'scheduled_start'          => $fechaStart,
            'scheduled_finish'         => $fechaFinish,
            'oxygen_deficient'         => $post['oxygen_deficient'] ?? null,
            'oxygen_enriched'          => $post['oxygen_enriched'] ?? null,
            'welding'                  => $post['welding'] ?? null,
            'engulfment'               => $post['engulfment'] ?? null,
            'toxic_atmosphere'         => $post['toxic_atmosphere'] ?? null,
            'flammable_atmosphere'     => $post['flammable_atmosphere'] ?? null,
            'energized_equipment'      => $post['energized_equipment'] ?? null,
            'entrapment'               => $post['entrapment'] ?? null,
            'hazardous_chemical'       => $post['hazardous_chemical'] ?? null,
            'breathing_apparatus'      => $post['breathing_apparatus'] ?? null,
            'line_respirator'          => $post['line_respirator'] ?? null,
            'resistant_clothing'       => $post['resistant_clothing'] ?? null,
            'ventilation'              => $post['ventilation'] ?? null,
            'protective_gloves'        => $post['protective_gloves'] ?? null,
            'linelines'                => $post['linelines'] ?? null,
            'respirators'              => $post['respirators'] ?? null,
            'lockout'                  => $post['lockout'] ?? null,
            'fire_extinguishers'       => $post['fire_extinguishers'] ?? null,
            'barricade'                => $post['barricade'] ?? null,
            'signs_posted'             => $post['signs_posted'] ?? null,
            'clearance_secured'        => $post['clearance_secured'] ?? null,
            'lighting'                 => $post['lighting'] ?? null,
            'interrupter'              => $post['interrupter'] ?? null,
            'oxygen'                   => $post['oxygen'] ?? null,
            'oxygen_time'              => $post['oxygen_time'] ?? null,
            'explosive_limit'          => $post['explosive_limit'] ?? null,
            'explosive_limit_time'     => $post['explosive_limit_time'] ?? null,
            'toxic_atmosphere_cond'    => $post['toxic_atmosphere_cond'] ?? null,
            'instruments_used'         => $post['instruments_used'] ?? null,
            'remarks'                  => $post['remarks'] ?? null,
            'fk_id_user_authorization' => $post['authorization'] ?? null,
            'fk_id_user_cancellation'  => $post['cancellation'] ?? null,
        ];

        $dateIssue = $post['date'] ?? '';

        if ($idConfined == '') {
            $data['fk_id_user'] = $idUser;
            $data['fk_id_job'] = $post['hddIdJob'];
            $data['date_confined'] = date('Y-m-d');
            if ($userRol == 99 && $dateIssue != '') {
                $data['date_confined'] = $dateIssue;
            }
            $this->db->table('job_confined')->insert($data);
            return $this->db->insertID();
        } else {
            if ($userRol == 99 && $dateIssue != '') {
                $data['date_confined'] = $dateIssue;
            }
            $this->db->table('job_confined')->where('id_job_confined', $idConfined)->update($data);
            return $idConfined;
        }
    }

    public function get_confined_workers($idConfined, $wos)
    {
        $builder = $this->db->table('job_confined_workers W');
        $builder->select("W.*, CONCAT(first_name, ' ', last_name) name");
        $builder->join('user U', 'U.id_user = W.fk_id_user', 'INNER');
        $builder->where('W.fk_id_job_confined', $idConfined);
        if ($wos !== null) {
            $builder->where('W.flag_workers', $wos);
        }
        $builder->orderBy('U.first_name, U.last_name', 'asc');
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

    public function get_confined_byIdworker_byIdConfined($idConfined, $idWorker, $wos)
    {
        $builder = $this->db->table('job_confined_workers');
        $builder->where('fk_id_job_confined', $idConfined);
        $builder->where('fk_id_user', $idWorker);
        $builder->where('flag_workers', $wos);
        return $builder->countAllResults() == 1;
    }

    public function add_confined_worker($idConfined, $wos, $workers)
    {
        if (empty($workers)) return true;
        foreach ($workers as $workerId) {
            $this->db->table('job_confined_workers')->insert([
                'fk_id_job_confined' => $idConfined,
                'fk_id_user'         => $workerId,
                'flag_workers'       => $wos,
            ]);
        }
        return true;
    }

    public function confinedSaveOneWorker($post)
    {
        $this->db->table('job_confined_workers')->insert([
            'fk_id_job_confined' => $post['hddIdConfined'],
            'fk_id_user'         => $post['worker'],
            'flag_workers'       => 2,
        ]);
        return $this->db->insertID() > 0;
    }

    public function confinedSaveWorkerOnSite($post)
    {
        $this->db->table('job_confined_workers')->insert([
            'fk_id_job_confined' => $post['hddIdConfined'],
            'fk_id_user'         => $post['worker'],
            'flag_workers'       => 1,
        ]);
        return $this->db->insertID() > 0;
    }

    public function saveRetesting($post)
    {
        $idRetesting = $post['hddId'] ?? '';
        $data = [
            'fk_id_job_confined'        => $post['hddIdConfined'],
            're_oxygen'                 => $post['re_oxygen'] ?? null,
            're_oxygen_time'            => $post['re_oxygen_time'] ?? null,
            're_explosive_limit'        => $post['re_explosive_limit'] ?? null,
            're_explosive_limit_time'   => $post['re_explosive_limit_time'] ?? null,
            're_toxic_atmosphere'       => $post['re_toxic_atmosphere'] ?? null,
            're_instruments_used'       => $post['re_instruments_used'] ?? null,
        ];

        if ($idRetesting == '') {
            $this->db->table('job_confined_re_testing')->insert($data);
            return $this->db->insertID();
        } else {
            $this->db->table('job_confined_re_testing')->where('id_job_confined_re_testing', $idRetesting)->update($data);
            return $idRetesting;
        }
    }

    public function get_confined_re_testing($arrDatos)
    {
        $builder = $this->db->table('job_confined_re_testing');
        if (isset($arrDatos['idRetesting'])) {
            $builder->where('id_job_confined_re_testing', $arrDatos['idRetesting']);
        }
        if (isset($arrDatos['idConfined'])) {
            $builder->where('fk_id_job_confined', $arrDatos['idConfined']);
        }
        $builder->orderBy('id_job_confined_re_testing', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function saveConfinedWorker($post)
    {
        $data = [
            'task'                   => $post['task'] ?? null,
            'fk_id_safety_watch_user' => $post['safety_watch'] ?? null,
        ];
        $this->db->table('job_confined_workers')->where('id_job_confined_worker', $post['hddId'])->update($data);
        return true;
    }

    public function updateConfinedWorkerInOut($arrDatos)
    {
        $this->db->table('job_confined_workers')
                 ->where('id_job_confined_worker', $arrDatos['id'])
                 ->update([$arrDatos['column'] => date('Y-m-d G:i:s')]);
        return true;
    }

    public function save_post_entry($post)
    {
        $data = [
            'personnel_out'           => $post['personnel_out'] ?? null,
            'isolation'               => $post['isolation'] ?? null,
            'lockouts_removed'        => $post['lockouts_removed'] ?? null,
            'tags_removed'            => $post['tags_removed'] ?? null,
            'equipment_removed'       => $post['equipment_removed'] ?? null,
            'ppe_cleaned'             => $post['ppe_cleaned'] ?? null,
            'rescue_equipment'        => $post['rescue_equipment'] ?? null,
            'permits_signed'          => $post['permits_signed'] ?? null,
            'areas_notified'          => $post['areas_notified'] ?? null,
            'fk_id_post_entry_user'   => $post['post_entry'] ?? null,
        ];
        $this->db->table('job_confined')->where('id_job_confined', $post['hddConfined'])->update($data);
        return true;
    }

    public function save_rescue_plan($post)
    {
        $fields = [
            'rescue_phone','rescue_radio','rescue_audible','rescue_intercom',
            'rescue_w_phone','rescue_w_intercom','rescue_w_visual','rescue_w_radio',
            'rescue_w_audible','rescue_w_rope','rescue_congested_value','rescue_hauling',
            'rescue_hauling_value','rescue_patient','rescue_patient_value','rescue_anchor',
            'rescue_anchor_value','rescue_beam','rescue_strut','rescue_stairwell','rescue_column',
            'rescue_other','rescue_e_hauling','rescue_e_hauling_value','rescue_e_carabiners',
            'rescue_e_carabiners_value','rescue_e_pulleys','rescue_e_pulleys_value',
            'rescue_e_absorbers','rescue_e_absorbers_value','rescue_e_straps','rescue_e_straps_value',
            'rescue_e_webbing','rescue_e_webbing_value','rescue_e_ascenders','rescue_e_ascenders_value',
            'rescue_e_harnesses','rescue_e_harnesses_value','rescue_e_rigging','rescue_e_rigging_value',
            'rescue_e_lines','rescue_e_lines_value','rescue_e_m_lines','rescue_e_m_lines_value',
            'rescue_e_wrist_har','rescue_e_wrist_har_value','rescue_e_extinguishers',
            'rescue_equipment_inspected','rescue_employer','rescue_first_aid','rescue_first_aid_value',
            'rescue_packaging','rescue_packaging_value','rescue_vests','rescue_glasses','rescue_hearing',
            'rescue_gloves','rescue_boots','rescue_face','rescue_hats','rescue_description',
        ];
        $data = [];
        foreach ($fields as $f) {
            $data[$f] = $post[$f] ?? null;
        }
        $this->db->table('job_confined')->where('id_job_confined', $post['hddConfined'])->update($data);
        return true;
    }

    public function get_task_control($arrDatos)
    {
        $builder = $this->db->table('job_task_control T');
        $builder->select('T.*, CONCAT(U.first_name, " " , U.last_name) supervisor, J.id_job, J.job_description, C.company_name');
        $builder->join('param_jobs J', 'J.id_job = T.fk_id_job', 'INNER');
        $builder->join('param_company C', 'C.id_company = T.fk_id_company', 'LEFT');
        $builder->join('user U', 'U.id_user = T.fk_id_user', 'INNER');

        if (isset($arrDatos['idJob'])) {
            $builder->where('T.fk_id_job', $arrDatos['idJob']);
        }
        if (isset($arrDatos['idTaskControl'])) {
            $builder->where('T.id_job_task_control', $arrDatos['idTaskControl']);
        }

        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

    public function add_task_control($post, $idUser)
    {
        $idTaskControl = $post['hddIdentificador'] ?? '';

        $data = [
            'name'                  => $post['name'] ?? null,
            'contact_phone_number'  => $post['phone_number'] ?? null,
            'superintendent'        => $post['superintendent'] ?? null,
            'fk_id_company'         => $post['company'] ?? null,
            'work_location'         => $post['work_location'] ?? null,
            'crew_size'             => $post['crew_size'] ?? null,
            'task'                  => $post['task'] ?? null,
            'distancing'            => $post['distancing'] ?? null,
            'distancing_comments'   => $post['distancing_comments'] ?? null,
            'sharing_tools'         => $post['sharing_tools'] ?? null,
            'sharing_tools_comments' => $post['sharing_tools_comments'] ?? null,
            'required_ppe'          => $post['required_ppe'] ?? null,
            'required_ppe_comments' => $post['required_ppe_comments'] ?? null,
            'symptoms'              => $post['symptoms'] ?? null,
            'symptoms_comments'     => $post['symptoms_comments'] ?? null,
            'protocols'             => $post['protocols'] ?? null,
            'protocols_comments'    => $post['protocols_comments'] ?? null,
        ];

        if ($idTaskControl == '') {
            $data['fk_id_user'] = $idUser;
            $data['fk_id_job'] = $post['hddIdJob'];
            $data['date_task_control'] = date('Y-m-d');
            $this->db->table('job_task_control')->insert($data);
            return $this->db->insertID();
        } else {
            $this->db->table('job_task_control')->where('id_job_task_control', $idTaskControl)->update($data);
            return $idTaskControl;
        }
    }
}
