<?php
namespace App\Modules\Dashboard\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = session();
    }

    /**
     * Contar registros del modulo SAFETY
     * @author BMOTTAG
     * @since  8/12/2016
     */
    public function countSafety()
    {
            $userRol = $this->session->get("rol");
            $idUser = $this->session->get("id");
        
            $year = date('Y');
            $firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año

            $sql = "SELECT count(id_safety) CONTEO";
            $sql.= " FROM safety";
            $sql.= " WHERE date >= '$firstDay'";
            if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
                $sql.= " AND fk_id_user = $idUser";
            }

            $query = $this->db->query($sql);
            $row = $query->row();
            return $row->CONTEO;
    }

    /**
     * Contar registros del modulo JOBS
     * @author BMOTTAG
     * @since  31/1/2017
     */
    public function countJobs()
    {
            $sql = "SELECT count(id_job) CONTEO";
            $sql.= " FROM param_jobs";
            $sql.= " WHERE state = 1";

            $query = $this->db->query($sql);
            $row = $query->row();
            return $row->CONTEO;
    }

    
    /**
     * Verificar si aprobaron o negaron ul dia de permiso en los ultimos 7 dias
     * @since 9/12/2016
     */
    public function dayOffInfo() 
    {
        $idUser = $this->session->get("id");

        $fecha = date('Y-m-d');
        $nuevafecha = strtotime ( '-7 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha ); 
        
        $builder = $this->db->table('dayoff');
        $builder->where('fk_id_user', $idUser);
        $builder->where('state <>', 1);
        $builder->where('date_update >=', $nuevafecha);
        $builder->orderBy('id_dayoff', 'DESC');
        $builder->limit(1);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }
    
    /**
     * Contar registros del modulo HAULING
     * si no es ADMIN entonces filtra por usuario
     * @author BMOTTAG
     * @since  13/1/2017
     */
    public function countHauling()
    {
            $userRol = $this->session->get("rol");
            $idUser = $this->session->get("id");
            
            $year = date('Y');
            $firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año

            $sql = "SELECT count(id_hauling) CONTEO";
            $sql.= " FROM hauling";
            $sql.= " WHERE date_issue >= '$firstDay'";
            if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
                $sql.= " AND fk_id_user = $idUser";
            }			

            $query = $this->db->query($sql);
            $row = $query->row();
            return $row->CONTEO;
    }
    
    /**
     * Contar registros del modulo DAILY INSPECTION
     * si no es ADMIN entonces filtra por usuario
     * @author BMOTTAG
     * @since  14/1/2017
     */
    public function countDailyInspection()
    {
            $userRol = $this->session->get("rol");
            $idUser = $this->session->get("id");
            
            $year = date('Y');
            $firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año

            $sql = "SELECT count(id_inspection_daily) CONTEO";
            $sql.= " FROM inspection_daily";
            $sql.= " WHERE date_issue >= '$firstDay'";
            if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
                $sql.= " AND fk_id_user = $idUser";
            }			

            $query = $this->db->query($sql);
            $row = $query->row();
            return $row->CONTEO;
    }
    
    /**
     * Contar registros del modulo HEAVY INSPECTION
     * si no es ADMIN entonces filtra por usuario
     * @author BMOTTAG
     * @since  14/1/2017
     */
    public function countHeavyInspection()
    {
            $userRol = $this->session->get("rol");
            $idUser = $this->session->get("id");
            
            $year = date('Y');
            $firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año

            $sql = "SELECT count(id_inspection_heavy) CONTEO";
            $sql.= " FROM inspection_heavy";
            $sql.= " WHERE date_issue >= '$firstDay'";
            if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
                $sql.= " AND fk_id_user = $idUser";
            }			

            $query = $this->db->query($sql);
            $row = $query->row();
            return $row->CONTEO;
    }
    
    /**
     * Contar registros del modulo SPECIAL INSPECTION
     * si no es ADMIN entonces filtra por usuario
     * @author BMOTTAG
     * @since  8/5/2017
     */
    public function countSpecialInspection()
    {
            $userRol = $this->session->get("rol");
            $idUser = $this->session->get("id");
            
            $year = date('Y');
            $firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año

            $sql = "SELECT count(id_inspection_generator) CONTEO";
            $sql.= " FROM inspection_generator";
            $sql.= " WHERE date_issue >= '$firstDay'";
            if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
                $sql.= " AND fk_id_user = $idUser";
            }			

            $query = $this->db->query($sql);
            $row = $query->row();
            $generator = $row->CONTEO;

            $sql = "SELECT count(id_inspection_hydrovac) CONTEO";
            $sql.= " FROM inspection_hydrovac";
            $sql.= " WHERE date_issue >= '$firstDay'";
            if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
                $sql.= " AND fk_id_user = $idUser";
            }			

            $query = $this->db->query($sql);
            $row = $query->row();
            $hydrovac = $row->CONTEO;

            
            $sql = "SELECT count(id_inspection_sweeper) CONTEO";
            $sql.= " FROM inspection_sweeper";
            $sql.= " WHERE date_issue >= '$firstDay'";
            if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
                $sql.= " AND fk_id_user = $idUser";
            }			

            $query = $this->db->query($sql);
            $row = $query->row();
            $sweeper = $row->CONTEO;

            $number = $generator+ $hydrovac + $sweeper;
            return $number;
    }

    public function get_trailers()
    {
        $builder = $this->db->table('inspection_daily');
        $builder->select('id_inspection_daily, date_issue, fk_id_trailer, trailer_lights, trailer_tires, trailer_slings, trailer_clean, trailer_chains, trailer_ratchet, trailer_comments, param_vehicle.description');
        $builder->join('param_vehicle', 'inspection_daily.fk_id_trailer = param_vehicle.id_vehicle', 'INNER');
        $builder->where('(fk_id_trailer, date_issue) IN (SELECT fk_id_trailer, MAX(date_issue) FROM inspection_daily GROUP BY fk_id_trailer)');
        $builder->where('type_level_2', 5);
        $builder->orderBy('id_inspection_daily', 'desc');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }
        return false;
    }

    public function get_not_inspection($month)
    {
        $sub = '-' . $month . ' months';
        $date = date("Y-m-d", strtotime($sub));

        $builder = $this->db->table('inspection_daily');
        $builder->select('fk_id_trailer');
        $builder->join('param_vehicle', 'inspection_daily.fk_id_trailer = param_vehicle.id_vehicle', 'INNER');
        $builder->where('(fk_id_trailer, date_issue) IN (SELECT fk_id_trailer, MAX(date_issue) FROM inspection_daily GROUP BY fk_id_trailer)');
        $builder->where('type_level_2', 5);
        $builder->where('date_issue <', $date);
        $builder->where('param_vehicle.state', 1);
        $builder->orderBy('fk_id_trailer', 'asc');

        $trailersMonthsInspect = $builder->get();

        $clean = [];
        if ($trailersMonthsInspect->getNumRows() > 0) {
            foreach ($trailersMonthsInspect->getResultArray() as $value) {
                $clean[] = $value['fk_id_trailer'];
            }
        }

        $builder2 = $this->db->table('param_vehicle');
        $builder2->select('id_vehicle');
        $builder2->where('type_level_2', 5);
        $builder2->where('param_vehicle.id_vehicle NOT IN (
            SELECT fk_id_trailer
            FROM inspection_daily
            JOIN param_vehicle ON inspection_daily.fk_id_trailer = param_vehicle.id_vehicle
            WHERE (fk_id_trailer, date_issue) IN (SELECT fk_id_trailer, MAX(date_issue) FROM inspection_daily GROUP BY fk_id_trailer)
            AND type_level_2 = 5
            AND param_vehicle.state = 1
            ORDER BY id_inspection_daily DESC
        )');
        $builder2->where('state', 1);

        $trailersNotInspect = $builder2->get();

        if ($trailersNotInspect->getNumRows() > 0) {
            foreach ($trailersNotInspect->getResultArray() as $value) {
                $clean[] = $value['id_vehicle'];
            }
        }

        if (empty($clean)) {
            return [];
        }

        $cleanStr = implode(",", $clean);

        $sql = "SELECT description, state
        FROM param_vehicle
        WHERE type_level_2 = 5 AND id_vehicle IN ($cleanStr) AND param_vehicle.state = 1";

        return $this->db->query($sql)->getResultArray();
    }


    
    
    
    
    
    
}