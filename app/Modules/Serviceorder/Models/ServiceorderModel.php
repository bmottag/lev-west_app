<?php
namespace App\Modules\Serviceorder\Models;

use CodeIgniter\Model;

class ServiceorderModel extends Model
{
    protected $protectFields = false;

    /**
     * Service Order Info
     * @since 18/5/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function get_service_order(array $arrDatos): array|false
    {
        $builder = $this->db->table('service_order S');

        $builder->select(
            'S.*, 
            CONCAT(U.first_name, " ", U.last_name) AS assigned_by,
            CONCAT(Z.first_name, " ", Z.last_name) AS assigned_to,

            CONCAT(V.unit_number, " -----> ", V.description) AS unit_description,

            V.vin_number,
            V.hours,
            V.hours_2,
            V.hours_3,
            V.type_level_2,

            P.status_name,
            P.status_style,
            P.status_icon,

            W.status_name AS priority_name,
            W.status_style AS priority_style,
            W.status_icon AS priority_icon,

            S.maintenace_type,

            CASE
                WHEN S.maintenace_type = "preventive"
                    THEN PM.maintenance_description

                WHEN S.maintenace_type = "corrective"
                    THEN CM.description_failure
            END AS main_description,

            PM.verification_by,

            T.id_time,
            T.time_date,
            T.time',
            false
        );

        $builder->join(
            'user U',
            'U.id_user = S.fk_id_assign_by',
            'INNER'
        );

        $builder->join(
            'user Z',
            'Z.id_user = S.fk_id_assign_to',
            'INNER'
        );

        $builder->join(
            'param_vehicle V',
            'V.id_vehicle = S.fk_id_equipment',
            'INNER'
        );

        $builder->join(
            'param_status P',
            'P.status_slug = S.service_status',
            'INNER'
        );

        $builder->join(
            'param_status W',
            'W.status_slug = S.priority',
            'INNER'
        );

        $builder->join(
            'preventive_maintenance PM',
            'S.maintenace_type = "preventive"
            AND PM.id_preventive_maintenance = S.fk_id_maintenace',
            'LEFT'
        );

        $builder->join(
            'corrective_maintenance CM',
            'S.maintenace_type = "corrective"
            AND CM.id_corrective_maintenance = S.fk_id_maintenace',
            'LEFT'
        );

        $builder->join(
            'service_order_time T',
            'T.fk_id_service_order = S.id_service_order',
            'LEFT'
        );

        $builder->where('P.status_key', 'serviceorder');

        if (array_key_exists('idServiceOrder', $arrDatos)) {
            $builder->where(
                'S.id_service_order',
                $arrDatos['idServiceOrder']
            );
        }

        if (array_key_exists('idVehicle', $arrDatos)) {
            $builder->where(
                'S.fk_id_equipment',
                $arrDatos['idVehicle']
            );
        }

        if (array_key_exists('idAssignTo', $arrDatos)) {

            $builder->where(
                'S.fk_id_assign_to',
                $arrDatos['idAssignTo']
            );

            $builder->where(
                'S.id_service_order !=',
                $arrDatos['diffIdServiceOrder']
            );

            $builder->where(
                'S.service_status',
                $arrDatos['status']
            );

        } elseif (array_key_exists('status', $arrDatos)) {

            $firstDay = date(
                'Y-m-d',
                mktime(0, 0, 0, 1, 1, date('Y'))
            );

            $builder->where(
                'S.service_status',
                $arrDatos['status']
            );

            $builder->where(
                'S.created_at >=',
                $firstDay
            );
        }

        $builder->orderBy('S.id_service_order', 'DESC');

        if (array_key_exists('limit', $arrDatos)) {
            $builder->limit($arrDatos['limit']);
        }

        $result = $builder->get()->getResultArray();

        return !empty($result) ? $result : false;
    }

    /**
     * Add/Edit SERVICE ORDER
     * @since 18/5/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function saveServiceOrder(array $post): int|false
    {
        $idServiceOrder = $post['hddIdServiceOrder'] ?? '';

        $data = [
            'fk_id_assign_to' => $post['assign_to'],
            'priority'        => $post['priority'],
        ];

        if ($idServiceOrder === '') {
            $data['fk_id_assign_by']  = session()->get('id');
            $data['fk_id_equipment']  = $post['hddIdEquipment'];
            $data['fk_id_maintenace'] = $post['hddIdMaintenance'];
            $data['maintenace_type']  = $post['hddMaintenanceType'];
            $data['created_at']       = date('Y-m-d G:i:s');
            $data['current_hours']    = 0;
            $data['service_status']   = 'new';
            $this->db->table('service_order')->insert($data);
            return $this->db->insertID();
        }

        $data['service_status']   = $post['status'];
        $data['updated_at']       = date('Y-m-d G:i:s');
        $data['current_hours']    = $post['hour'] ?? 0;
        $data['damages']          = $post['damages'] ?? null;
        $data['can_be_used']      = $post['can_be_used'] ?? null;
        $data['purchasing_staff'] = $post['purchasing_staff'] ?? null;
        $data['mechanic']         = $post['mechanic'] ?? null;
        $data['engine_oil']       = $post['engine_oil'] ?? null;
        $data['transmission_oil'] = $post['transmission_oil'] ?? null;
        $data['hydraulic_oil']    = $post['hydraulic_oil'] ?? null;
        $data['fuel']             = $post['fuel'] ?? null;
        $data['filters']          = $post['filters'] ?? null;
        $data['parts']            = $post['parts'] ?? null;
        $data['blade']            = $post['blade'] ?? null;
        $data['ripper']           = $post['ripper'] ?? null;
        $data['other']            = $post['other'] ?? null;
        $data['comments']         = $post['comments'] ?? null;

        if (($post['status'] ?? '') === 'closed_so') {
            if (($post['hddVerificationBy'] ?? '') == 1) {
                $data['next_hours'] = $post['next_hours_maintenance'] ?? null;
            } else {
                $data['next_date'] = $post['next_date_maintenance'] ?? null;
            }
        }

        $result = $this->db->table('service_order')
            ->where('id_service_order', $idServiceOrder)
            ->update($data);

        return $result ? (int) $idServiceOrder : false;
    }

    /**
     * Preventive Maintenance Info
     * @since 22/5/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function get_preventive_maintenance(array $arrDatos): array|false
    {
        $builder = $this->db->table('preventive_maintenance P');
        $builder->select('P.*, T.maintenance_type, T.id_maintenance_type, V.hours, V.hours_2, V.hours_3');
        $builder->join('maintenance_type T', 'T.id_maintenance_type = P.fk_id_maintenance_type', 'INNER');
        $builder->join('param_vehicle V', 'V.id_vehicle = P.fk_id_equipment', 'INNER');

        if (array_key_exists('idVehicle', $arrDatos)) {
            $builder->where('P.fk_id_equipment', $arrDatos['idVehicle']);
        }
        if (array_key_exists('idMaintenance', $arrDatos)) {
            $builder->where('P.id_preventive_maintenance', $arrDatos['idMaintenance']);
        }
        if (array_key_exists('maintenanceStatus', $arrDatos)) {
            $builder->where('P.maintenance_status', $arrDatos['maintenanceStatus']);
        }

        $builder->orderBy('P.id_preventive_maintenance', 'DESC');
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

    /**
     * Add/Edit PREVENTIVE MAINTENANCE
     * @since 22/5/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function savePreventiveMaintenance(array $post): bool
    {
        $idMaintenance = $post['hddIdMaintenance'] ?? '';
        $nextHours     = $post['next_hours_maintenance'] ?? 0;
        $nextDate      = $post['next_date_maintenance'] ?? '';

        if (($post['verification'] ?? '') == 1) {
            $nextDate = '';
        } else {
            $nextHours = 0;
        }

        $data = [
            'fk_id_equipment'         => $post['hddIdEquipment'],
            'fk_id_maintenance_type'  => $post['maintenance_type'],
            'maintenance_description' => $post['description'],
            'verification_by'         => $post['verification'],
            'next_hours_maintenance'  => $nextHours,
            'next_date_maintenance'   => $nextDate,
            'maintenance_status'      => $post['maintenance_status'],
        ];

        if ($idMaintenance === '') {
            return $this->db->table('preventive_maintenance')->insert($data);
        }

        return $this->db->table('preventive_maintenance')
            ->where('id_preventive_maintenance', $idMaintenance)
            ->update($data);
    }

    /**
     * Corrective Maintenance Info
     * @since 26/5/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function get_corrective_maintenance(array $arrDatos): array|false
    {
        $builder = $this->db->table('corrective_maintenance P');
        $builder->select('P.*, CONCAT(U.first_name, " ", U.last_name) request_by, S.status_name, S.status_style, S.status_icon');
        $builder->join('user U', 'U.id_user = P.request_by', 'INNER');
        $builder->join('param_status S', 'S.status_slug = P.maintenance_status', 'INNER');

        if (array_key_exists('idVehicle', $arrDatos)) {
            $builder->where('P.fk_id_equipment', $arrDatos['idVehicle']);
        }
        if (array_key_exists('idMaintenance', $arrDatos)) {
            $builder->where('P.id_corrective_maintenance', $arrDatos['idMaintenance']);
        }

        $builder->orderBy('P.id_corrective_maintenance', 'DESC');
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

    /**
     * Add/Edit CORRECTIVE MAINTENANCE
     * @since 22/5/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function saveCorrectiveMaintenance(array $post): bool
    {
        $idMaintenance = $post['hddIdMaintenance'] ?? '';
        $data = [
            'fk_id_equipment'    => $post['hddIdEquipment'],
            'description_failure' => $post['description'],
        ];

        if ($idMaintenance === '') {
            $data['request_by']         = session()->get('id');
            $data['created_at']         = date('Y-m-d G:i:s');
            $data['maintenance_status'] = 'pending';
            return $this->db->table('corrective_maintenance')->insert($data);
        }

        return $this->db->table('corrective_maintenance')
            ->where('id_corrective_maintenance', $idMaintenance)
            ->update($data);
    }

    /**
     * Parts info
     * @since 30/5/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function get_parts(array $arrDatos): array|false
    {
        $builder = $this->db->table('service_order_parts P');

        if (array_key_exists('idServiceOrder', $arrDatos)) {
            $builder->where('P.fk_id_service_order', $arrDatos['idServiceOrder']);
        }
        if (array_key_exists('idPart', $arrDatos)) {
            $builder->where('P.id_part', $arrDatos['idPart']);
        }

        $builder->orderBy('P.id_part', 'ASC');
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

    /**
     * Add/Edit PARTS
     * @since 18/5/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function saveParts(array $post): bool
    {
        $idParts = $post['hddIdPart'] ?? '';
        $data = [
            'part_description' => $post['part_description'],
            'quantity'         => $post['quantity'],
            'value'            => $post['value'],
            'supplier'         => $post['supplier'] ?? null,
        ];

        if ($idParts === '') {
            $data['fk_id_service_order'] = $post['hddIdServiceOrder'];
            $data['created_at']          = date('Y-m-d G:i:s');
            $data['part_status']         = 'new_request';
            return $this->db->table('service_order_parts')->insert($data);
        }

        $data['part_status'] = $post['status'] ?? null;
        $data['updated_at']  = date('Y-m-d G:i:s');
        return $this->db->table('service_order_parts')
            ->where('id_part', $idParts)
            ->update($data);
    }

    /**
     * Add/Edit TIME
     * @since 1/7/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function saveTime(array $arrDatos): bool
    {
        $date = date('Y-m-d G:i:s');
        $data = ['time_date' => $date];

        if (($arrDatos['idTime'] ?? '') === '') {
            $data['fk_id_service_order'] = $arrDatos['idServiceOrder'];
            $data['time'] = 0;
            return $this->db->table('service_order_time')->insert($data);
        }

        if (array_key_exists('timeDate', $arrDatos)) {
            $minutes      = abs((strtotime($arrDatos['timeDate']) - strtotime($date)) / 60);
            $data['time'] = round(round($minutes) / 60, 2) + ($arrDatos['time'] ?? 0);
        }

        return $this->db->table('service_order_time')
            ->where('id_time', $arrDatos['idTime'])
            ->update($data);
    }

    /**
     * Delete Maintenance check
     * @since 13/2/2020
     * @review 13/05/2026 - new CI4 version
     */
    public function delete_maintenance_check(): bool
    {
        return (bool) $this->db->query('TRUNCATE maintenance_check');
    }

    /**
     * Add Maintenance check
     * @since 13/2/2020
     * @review 13/05/2026 - new CI4 version
     */
    public function add_maintenance_check(int $idMaintenace): bool
    {
        return $this->db->table('maintenance_check')->insert(['fk_id_maintenance' => $idMaintenace]);
    }

    /**
     * Expenses Info
     * @author BMOTTAG
     * @since  22/7/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function get_expenses(): array|false
    {
        $firstDay = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));

        $sql = 'SELECT
                    V.id_vehicle, V.unit_number, V.description,
                    COUNT(S.fk_id_equipment) AS so_number,
                    ROUND(SUM(T.time),2) AS time_expenses,
                    ROUND(SUM(P.value),2) AS parts_expenses
                FROM param_vehicle V
                INNER JOIN service_order S ON V.id_vehicle = S.fk_id_equipment
                LEFT JOIN service_order_time T ON S.id_service_order = T.fk_id_service_order
                LEFT JOIN service_order_parts P ON S.id_service_order = P.fk_id_service_order
                WHERE V.fk_id_company = 1 AND V.state = 1 AND S.created_at >= ?
                GROUP BY V.id_vehicle
                ORDER BY V.unit_number';

        $result = $this->db->query($sql, [$firstDay])->getResultArray();
        return !empty($result) ? $result : false;
    }

    /**
     * Expenses Info by Equipment
     * @author BMOTTAG
     * @since  22/7/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function get_expenses_by_equipment(array $arrDatos): array|false
    {
        $firstDay = date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));

        $sql = '
            SELECT
                S.id_service_order,
                S.maintenace_type,
                S.fk_id_equipment,

                IFNULL(T.time_expenses, 0) AS time_expenses,
                IFNULL(P.parts_expenses, 0) AS parts_expenses,

                CASE
                    WHEN S.maintenace_type = "preventive"
                        THEN PM.maintenance_description
                    WHEN S.maintenace_type = "corrective"
                        THEN CM.description_failure
                END AS main_description

            FROM service_order S

            LEFT JOIN (
                SELECT fk_id_service_order, ROUND(SUM(time), 2) AS time_expenses
                FROM service_order_time
                GROUP BY fk_id_service_order
            ) T ON T.fk_id_service_order = S.id_service_order

            LEFT JOIN (
                SELECT fk_id_service_order, ROUND(SUM(value), 2) AS parts_expenses
                FROM service_order_parts
                GROUP BY fk_id_service_order
            ) P ON P.fk_id_service_order = S.id_service_order

            LEFT JOIN preventive_maintenance PM
                ON S.maintenace_type = "preventive"
                AND PM.id_preventive_maintenance = S.fk_id_maintenace

            LEFT JOIN corrective_maintenance CM
                ON S.maintenace_type = "corrective"
                AND CM.id_corrective_maintenance = S.fk_id_maintenace

            WHERE S.created_at >= ?
            AND S.fk_id_equipment = ?

            ORDER BY S.id_service_order DESC
        ';

        $result = $this->db
            ->query($sql, [$firstDay, $arrDatos['idVehicle']])
            ->getResultArray();

        return !empty($result) ? $result : false;
    }

    /**
     * Add/Edit Shop Parts
     * @since 30/10/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function saveShopParts(array $post): int|false
    {
        $idPartShop = $post['hddId'] ?? '';
        $idShop     = $post['id_shop'] ?? '';

        if ($idShop === '') {
            $this->db->table('param_shop')->insert([
                'shop_name'    => $post['shop_name'] ?? '',
                'shop_contact' => $post['shop_contact'] ?? '',
                'shop_address' => $post['shop_address'] ?? '',
                'mobile_number' => $post['mobile_number'] ?? '',
                'shop_email'   => $post['shop_email'] ?? '',
            ]);
            $idShop = $this->db->insertID();
        }

        $data = [
            'part_description'   => $post['part_description'],
            'fk_id_shop'         => $idShop,
            'fk_inspection_type' => $post['type'] ?? null,
        ];

        if ($idPartShop === '') {
            $this->db->table('param_parts_shop')->insert($data);
            return $this->db->insertID();
        }

        $result = $this->db->table('param_parts_shop')
            ->where('id_part_shop', $idPartShop)
            ->update($data);

        return $result ? (int) $idPartShop : false;
    }

    /**
     * Get parts list by store
     * @since 30/10/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function get_parts_by_store(array $arrDatos): array|false
    {
        $builder = $this->db->table('param_parts_shop P');
        $builder->select('P.*, S.*,
            (SELECT GROUP_CONCAT(V.unit_number, " -----> ", V.description SEPARATOR "<br>")
             FROM param_equipment_parts E
             JOIN param_vehicle V ON V.id_vehicle = E.fk_id_equipment
             WHERE E.fk_id_part_shop = P.id_part_shop
             GROUP BY P.id_part_shop) AS equipments');
        $builder->join('param_shop S', 'S.id_shop = P.fk_id_shop', 'INNER');

        if (array_key_exists('idPartShop', $arrDatos)) {
            $builder->where('P.id_part_shop', $arrDatos['idPartShop']);
        }

        $builder->orderBy('P.part_description', 'ASC');
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

    /**
     * Get parts list by store by equipment
     * @since 3/01/2024
     * @review 13/05/2026 - new CI4 version
     */
    public function get_parts_store_by_equipment(array $arrDatos): array|false
    {
        $builder = $this->db->table('param_parts_shop P');
        $builder->join('param_shop S', 'S.id_shop = P.fk_id_shop', 'INNER');
        $builder->join('param_equipment_parts E', 'E.fk_id_part_shop = P.id_part_shop', 'INNER');

        if (array_key_exists('idVehicle', $arrDatos)) {
            $builder->where('E.fk_id_equipment', $arrDatos['idVehicle']);
        }

        $builder->orderBy('P.part_description', 'ASC');
        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }

    /**
     * Add Equipment to Shop Parts
     * @since 13/12/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function add_equipment_shop_parts(int $idPartShop, array $equipment): bool
    {
        $this->db->table('param_equipment_parts')->where('fk_id_part_shop', $idPartShop)->delete();

        foreach ($equipment as $idEquipment) {
            $this->db->table('param_equipment_parts')->insert([
                'fk_id_part_shop' => $idPartShop,
                'fk_id_equipment' => $idEquipment,
            ]);
        }
        return true;
    }

    /**
     * Equipment list (parts relation)
     * @since 16/12/2023
     * @review 13/05/2026 - new CI4 version
     */
    public function get_parts_equipment(array $arrDatos): array|false
    {
        $builder = $this->db->table('param_equipment_parts P');

        if (array_key_exists('relation', $arrDatos)) {
            $builder->select('P.fk_id_equipment');
        } else {
            $builder->select('P.*, T.inspection_type');
            $builder->join('param_vehicle V', 'V.id_vehicle = P.fk_id_equipment', 'INNER');
            $builder->join('param_vehicle_type_2 T', 'T.id_type_2 = V.type_level_2', 'INNER');
        }

        if (array_key_exists('idEquipmentPart', $arrDatos)) {
            $builder->where('P.fk_id_part_shop', $arrDatos['idEquipmentPart']);
        }

        $result = $builder->get()->getResultArray();
        return !empty($result) ? $result : false;
    }
}
