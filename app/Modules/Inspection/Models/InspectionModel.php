<?php
namespace App\Modules\Inspection\Models;

use CodeIgniter\Model;

class InspectionModel extends Model
{
    protected $protectFields = false;

    /**
     * Add/Edit HeavyInspection
     * @since 27/12/2016
     * @review 29/04/2026 - new CI4 version
     */
    public function saveHeavyInspection(array $post, int $idUser, int $userRol): int|false
    {
        $idInspection = $post['hddId'] ?? '';

        $data = [
            'fk_id_vehicle'    => $post['hddIdVehicle'] ?? null,
            'belt'             => $post['belt'] ?? null,
            'oil_level'        => $post['oil'] ?? null,
            'coolant_level'    => $post['coolantLevel'] ?? null,
            'coolant_leaks'    => $post['coolantLeaks'] ?? null,
            'working_lamps'    => $post['workingLamps'] ?? null,
            'beacon_lights'    => $post['beaconLights'] ?? null,
            'heater'           => $post['heater'] ?? null,
            'operator_seat'    => $post['operatorSeat'] ?? null,
            'gauges'           => $post['gauges'] ?? null,
            'horn'             => $post['horn'] ?? null,
            'seatbelt'         => $post['seatbelt'] ?? null,
            'clean_interior'   => $post['cleanInterior'] ?? null,
            'windows'          => $post['windows'] ?? null,
            'clean_exterior'   => $post['cleanExterior'] ?? null,
            'wipers'           => $post['wipers'] ?? null,
            'backup_beeper'    => $post['backupBeeper'] ?? null,
            'door'             => $post['door'] ?? null,
            'decals'           => $post['decals'] ?? null,
            'boom_grease'      => $post['boom'] ?? null,
            'table_excavator'  => $post['tableExcavator'] ?? null,
            'bucket_pins'      => $post['bucketPins'] ?? null,
            'blade_pins'       => $post['bladePins'] ?? null,
            'ripper'           => $post['ripper'] ?? null,
            'front_axle'       => $post['frontAxle'] ?? null,
            'rear_axle'        => $post['rearAxle'] ?? null,
            'table_dozer'      => $post['tableDozer'] ?? null,
            'pivin_points'     => $post['pivinPoints'] ?? null,
            'bucket_pins_skit' => $post['bucketPinsSkit'] ?? null,
            'side_arms'        => $post['sideArms'] ?? null,
            'bucket'           => $post['bucket'] ?? null,
            'cutting_edges'    => $post['cutting'] ?? null,
            'blades'           => $post['blades'] ?? null,
            'tracks'           => $post['tracks'] ?? null,
            'rubber_trucks'    => $post['rubberTrucks'] ?? null,
            'rollers'          => $post['rollers'] ?? null,
            'thamper'          => $post['thamper'] ?? null,
            'drill'            => $post['drill'] ?? null,
            'fire_extinguisher'=> $post['fire'] ?? null,
            'first_aid'        => $post['aid'] ?? null,
            'spill_kit'        => $post['spillKit'] ?? null,
            'tire_presurre'    => $post['tire'] ?? null,
            'turn_signals'     => $post['turn'] ?? null,
            'rims'             => $post['rims'] ?? null,
            'emergency_brake'  => $post['brake'] ?? null,
            'transmission'     => $post['transmission'] ?? null,
            'hydrolic'         => $post['hydrolic'] ?? null,
            'comments'         => $post['comments'] ?? null,
            'def'              => $post['def'] ?? null,
        ];

        $dateIssue = $post['date'] ?? '';
        $data['date_issue'] = ($userRol == 99 && $dateIssue !== '')
            ? $dateIssue . ' ' . date('G:i:s')
            : date('Y-m-d G:i:s');

        $builder = $this->db->table('inspection_heavy');
        if ($idInspection == '') {
            $data['fk_id_user'] = $idUser;
            $result = $builder->insert($data);
            return $result ? $this->db->insertID() : false;
        }
        $result = $builder->where('id_inspection_heavy', $idInspection)->update($data);
        return $result ? (int) $idInspection : false;
    }

    /**
     * Add/Edit DailyInspection
     * @since 27/12/2016
     * @review 29/04/2026 - new CI4 version
     */
    public function saveDailyInspection(array $post, int $idUser, int $userRol): int|false
    {
        $idInspection  = $post['hddId'] ?? '';
        $trailer       = $post['trailer'] ?? '';
        $with_trailer  = $trailer == '' ? 2 : 1;

        $data = [
            'fk_id_vehicle'        => $post['hddIdVehicle'] ?? null,
            'belt'                 => $post['belt'] ?? null,
            'power_steering'       => $post['powerSteering'] ?? null,
            'oil_level'            => $post['oil'] ?? null,
            'coolant_level'        => $post['coolantLevel'] ?? null,
            'water_leaks'          => $post['waterLeaks'] ?? null,
            'nuts'                 => $post['nuts'] ?? null,
            'head_lamps'           => $post['headLamps'] ?? null,
            'hazard_lights'        => $post['hazardLights'] ?? null,
            'clearance_lights'     => $post['clearanceLights'] ?? null,
            'bake_lights'          => $post['bakeLights'] ?? null,
            'work_lights'          => $post['workLights'] ?? null,
            'glass'                => $post['glass'] ?? null,
            'clean_exterior'       => $post['cleanExterior'] ?? null,
            'proper_decals'        => $post['properDecals'] ?? null,
            'brake_pedal'          => $post['brakePedal'] ?? null,
            'emergency_brake'      => $post['emergencyBrake'] ?? null,
            'backup_beeper'        => $post['backupBeeper'] ?? null,
            'beacon_light'         => $post['beaconLight'] ?? null,
            'gauges'               => $post['gauges'] ?? null,
            'horn'                 => $post['horn'] ?? null,
            'hoist'                => $post['hoist'] ?? null,
            'passenger_door'       => $post['passengerDoor'] ?? null,
            'seatbelts'            => $post['seatbelts'] ?? null,
            'fire_extinguisher'    => $post['fireExtinguisher'] ?? null,
            'emergency_reflectors' => $post['emergencyReflectors'] ?? null,
            'first_aid'            => $post['firstAid'] ?? null,
            'wipers'               => $post['wipers'] ?? null,
            'drives_axle'          => $post['drivesAxle'] ?? null,
            'grease_front'         => $post['greaseFront'] ?? null,
            'grease_end'           => $post['greaseEnd'] ?? null,
            'spill_kit'            => $post['spillKit'] ?? null,
            'grease'               => $post['grease'] ?? null,
            'steering_axle'        => $post['steeringAxle'] ?? null,
            'turn_signals'         => $post['turnSignals'] ?? null,
            'clean_interior'       => $post['cleanInterior'] ?? null,
            'insurance'            => $post['insurance'] ?? null,
            'driver_seat'          => $post['driverSeat'] ?? null,
            'registration'         => $post['registration'] ?? null,
            'heater'               => $post['heater'] ?? null,
            'steering_wheel'       => $post['steering_wheel'] ?? null,
            'suspension_system'    => $post['suspension_system'] ?? null,
            'air_brake'            => $post['air_brake'] ?? null,
            'fuel_system'          => $post['fuel_system'] ?? null,
            'comments'             => $post['comments'] ?? null,
            'with_trailer'         => $with_trailer,
            'fk_id_trailer'        => $post['trailer'] ?? null,
            'trailer_lights'       => $post['trailerLights'] ?? null,
            'trailer_tires'        => $post['trailerTires'] ?? null,
            'trailer_slings'       => $post['trailerSlings'] ?? null,
            'trailer_clean'        => $post['trailerClean'] ?? null,
            'trailer_chains'       => $post['trailerChains'] ?? null,
            'trailer_ratchet'      => $post['trailerRatchet'] ?? null,
            'trailer_comments'     => $post['trailerComments'] ?? null,
            'def'                  => $post['def'] ?? null,
        ];

        $dateIssue = $post['date'] ?? '';
        $data['date_issue'] = ($userRol == 99 && $dateIssue !== '')
            ? $dateIssue . ' ' . date('G:i:s')
            : date('Y-m-d G:i:s');

        $builder = $this->db->table('inspection_daily');
        if ($idInspection == '') {
            $data['fk_id_user'] = $idUser;
            $result = $builder->insert($data);
            return $result ? $this->db->insertID() : false;
        }
        $result = $builder->where('id_inspection_daily', $idInspection)->update($data);
        return $result ? (int) $idInspection : false;
    }

    /**
     * Add vehicle next oil change record
     * @since 18/1/2017
     * @review 29/04/2026 - new CI4 version
     */
    public function saveVehicleNextOilChange(int $idVehicle, int $state, int $idInspection, array $post, int $idUser): bool
    {
        $result = $this->db->table('vehicle_oil_change')->insert([
            'fk_id_vehicle'    => $idVehicle,
            'fk_id_user'       => $idUser,
            'current_hours'    => $post['hours'] ?? null,
            'date_issue'       => date('Y-m-d G:i:s'),
            'state'            => $state,
            'current_hours_2'  => $post['hours2'] ?? null,
            'current_hours_3'  => $post['hours3'] ?? null,
            'fk_id_inspection' => $idInspection,
        ]);

        if (!$result) return false;

        return (bool) $this->db->table('param_vehicle')
            ->where('id_vehicle', $idVehicle)
            ->update([
                'hours'   => $post['hours'] ?? null,
                'hours_2' => $post['hours2'] ?? null,
                'hours_3' => $post['hours3'] ?? null,
            ]);
    }

    /**
     * Add/Edit Generator Inspection
     * @since 17/3/2017
     * @review 29/04/2026 - new CI4 version
     */
    public function saveGeneratorInspection(array $post, int $idUser, int $userRol): int|false
    {
        $idInspection = $post['hddId'] ?? '';

        $data = [
            'fk_id_vehicle'  => $post['hddIdVehicle'] ?? null,
            'belt'           => $post['belt'] ?? null,
            'fuel_filter'    => $post['fuelFilter'] ?? null,
            'oil_level'      => $post['oil'] ?? null,
            'coolant_level'  => $post['coolantLevel'] ?? null,
            'coolant_leaks'  => $post['coolantLeaks'] ?? null,
            'turn_signal'    => $post['turnSignal'] ?? null,
            'hazard_lights'  => $post['hazardLights'] ?? null,
            'tail_lights'    => $post['tailLights'] ?? null,
            'flood_lights'   => $post['floodLights'] ?? null,
            'boom'           => $post['boom'] ?? null,
            'gears'          => $post['gears'] ?? null,
            'gauges'         => $post['gauges'] ?? null,
            'pulley'         => $post['pulley'] ?? null,
            'electrical'     => $post['electrical'] ?? null,
            'brackers'       => $post['brackers'] ?? null,
            'tires'          => $post['tires'] ?? null,
            'clean_exterior' => $post['cleanExterior'] ?? null,
            'decals'         => $post['decals'] ?? null,
            'comments'       => $post['comments'] ?? null,
        ];

        $dateIssue = $post['date'] ?? '';
        $data['date_issue'] = ($userRol == 99 && $dateIssue !== '')
            ? $dateIssue . ' ' . date('G:i:s')
            : date('Y-m-d G:i:s');

        $builder = $this->db->table('inspection_generator');
        if ($idInspection == '') {
            $data['fk_id_user'] = $idUser;
            $result = $builder->insert($data);
            return $result ? $this->db->insertID() : false;
        }
        $result = $builder->where('id_inspection_generator', $idInspection)->update($data);
        return $result ? (int) $idInspection : false;
    }

    /**
     * Add/Edit Sweeper Inspection
     * @since 23/4/2017
     * @review 29/04/2026 - new CI4 version
     */
    public function saveSweeperInspection(array $post, int $idUser, int $userRol): int|false
    {
        $idInspection = $post['hddId'] ?? '';

        $data = [
            'fk_id_vehicle'         => $post['hddIdVehicle'] ?? null,
            'belt'                  => $post['belt'] ?? null,
            'power_steering'        => $post['powerSteering'] ?? null,
            'oil_level'             => $post['oil'] ?? null,
            'coolant_level'         => $post['coolantLevel'] ?? null,
            'coolant_leaks'         => $post['coolantLeaks'] ?? null,
            'hydraulic'             => $post['hydraulic'] ?? null,
            'belt_sweeper'          => $post['beltSweeper'] ?? null,
            'oil_level_sweeper'     => $post['oilSweeper'] ?? null,
            'coolant_level_sweeper' => $post['coolantLevelSweeper'] ?? null,
            'coolant_leaks_sweeper' => $post['coolantLeaksSweeper'] ?? null,
            'head_lamps'            => $post['headLamps'] ?? null,
            'hazard_lights'         => $post['hazardLights'] ?? null,
            'clearance_lights'      => $post['clearanceLights'] ?? null,
            'tail_lights'           => $post['tailLights'] ?? null,
            'work_lights'           => $post['workLights'] ?? null,
            'turn_signals'          => $post['turnSignals'] ?? null,
            'beacon_lights'         => $post['beaconLights'] ?? null,
            'tires'                 => $post['tires'] ?? null,
            'windows'               => $post['windows'] ?? null,
            'clean_exterior'        => $post['cleanExterior'] ?? null,
            'wipers'                => $post['wipers'] ?? null,
            'backup_beeper'         => $post['backupBeeper'] ?? null,
            'door'                  => $post['door'] ?? null,
            'decals'                => $post['decals'] ?? null,
            'stering_wheels'        => $post['SteringWheels'] ?? null,
            'drives'                => $post['drives'] ?? null,
            'front_drive'           => $post['frontDrive'] ?? null,
            'elevator'              => $post['elevator'] ?? null,
            'rotor'                 => $post['rotor'] ?? null,
            'mixture_box'           => $post['mixtureBox'] ?? null,
            'lf_rotor'              => $post['lfRotor'] ?? null,
            'elevator_sweeper'      => $post['elevatorSweeper'] ?? null,
            'mixture_container'     => $post['mixtureContainer'] ?? null,
            'broom'                 => $post['broom'] ?? null,
            'right_broom'           => $post['rightBroom'] ?? null,
            'left_broom'            => $post['leftBroom'] ?? null,
            'sprinkerls'            => $post['sprinkerls'] ?? null,
            'water_tank'            => $post['waterTank'] ?? null,
            'hose'                  => $post['hose'] ?? null,
            'cam'                   => $post['cam'] ?? null,
            'brake'                 => $post['brake'] ?? null,
            'emergency_brake'       => $post['emergencyBrake'] ?? null,
            'gauges'                => $post['gauges'] ?? null,
            'horn'                  => $post['horn'] ?? null,
            'seatbelt'              => $post['seatbelt'] ?? null,
            'seat'                  => $post['seat'] ?? null,
            'insurance'             => $post['insurance'] ?? null,
            'registration'          => $post['registration'] ?? null,
            'clean_interior'        => $post['cleanInterior'] ?? null,
            'fire_extinguisher'     => $post['fire'] ?? null,
            'first_aid'             => $post['aid'] ?? null,
            'emergency_kit'         => $post['emergencyKit'] ?? null,
            'spill_kit'             => $post['spillKit'] ?? null,
            'comments'              => $post['comments'] ?? null,
        ];

        $dateIssue = $post['date'] ?? '';
        $data['date_issue'] = ($userRol == 99 && $dateIssue !== '')
            ? $dateIssue . ' ' . date('G:i:s')
            : date('Y-m-d G:i:s');

        $builder = $this->db->table('inspection_sweeper');
        if ($idInspection == '') {
            $data['fk_id_user'] = $idUser;
            $result = $builder->insert($data);
            return $result ? $this->db->insertID() : false;
        }
        $result = $builder->where('id_inspection_sweeper', $idInspection)->update($data);
        return $result ? (int) $idInspection : false;
    }

    /**
     * Add/Edit Hydrovac Inspection
     * @since 23/4/2017
     * @review 29/04/2026 - new CI4 version
     */
    public function saveHydrovacInspection(array $post, int $idUser, int $userRol): int|false
    {
        $idInspection = $post['hddId'] ?? '';

        $data = [
            'fk_id_vehicle'     => $post['hddIdVehicle'] ?? null,
            'belt'              => $post['belt'] ?? null,
            'power_steering'    => $post['powerSteering'] ?? null,
            'oil_level'         => $post['oil'] ?? null,
            'coolant_level'     => $post['coolantLevel'] ?? null,
            'coolant_leaks'     => $post['coolantLeaks'] ?? null,
            'head_lamps'        => $post['headLamps'] ?? null,
            'hazard_lights'     => $post['hazardLights'] ?? null,
            'clearance_lights'  => $post['clearanceLights'] ?? null,
            'tail_lights'       => $post['tailLights'] ?? null,
            'work_lights'       => $post['workLights'] ?? null,
            'turn_signals'      => $post['turnSignals'] ?? null,
            'beacon_lights'     => $post['beaconLights'] ?? null,
            'tires'             => $post['tires'] ?? null,
            'windows'           => $post['windows'] ?? null,
            'clean_exterior'    => $post['cleanExterior'] ?? null,
            'wipers'            => $post['wipers'] ?? null,
            'backup_beeper'     => $post['backupBeeper'] ?? null,
            'door'              => $post['door'] ?? null,
            'decals'            => $post['decals'] ?? null,
            'stering_wheels'    => $post['SteringWheels'] ?? null,
            'drives'            => $post['drives'] ?? null,
            'front_drive'       => $post['frontDrive'] ?? null,
            'middle_drive'      => $post['middleDrive'] ?? null,
            'back_drive'        => $post['backDrive'] ?? null,
            'transfer'          => $post['transfer'] ?? null,
            'tail_gate'         => $post['tailGate'] ?? null,
            'boom'              => $post['boom'] ?? null,
            'lock_bar'          => $post['lockBar'] ?? null,
            'brake'             => $post['brake'] ?? null,
            'emergency_brake'   => $post['emergencyBrake'] ?? null,
            'gauges'            => $post['gauges'] ?? null,
            'horn'              => $post['horn'] ?? null,
            'seatbelt'          => $post['seatbelt'] ?? null,
            'seat'              => $post['seat'] ?? null,
            'insurance'         => $post['insurance'] ?? null,
            'registration'      => $post['registration'] ?? null,
            'clean_interior'    => $post['cleanInterior'] ?? null,
            'fire_extinguisher' => $post['fire'] ?? null,
            'first_aid'         => $post['aid'] ?? null,
            'emergency_kit'     => $post['emergencyKit'] ?? null,
            'spill_kit'         => $post['spillKit'] ?? null,
            'cartige'           => $post['cartige'] ?? null,
            'pump'              => $post['pump'] ?? null,
            'wash_hose'         => $post['washHose'] ?? null,
            'pressure_hose'     => $post['pressureHose'] ?? null,
            'pump_oil'          => $post['pumpOil'] ?? null,
            'hydraulic_oil'     => $post['hydraulicOil'] ?? null,
            'gear_case'         => $post['gearCase'] ?? null,
            'hydraulic'         => $post['hydraulic'] ?? null,
            'control'           => $post['control'] ?? null,
            'panel'             => $post['panel'] ?? null,
            'foam'              => $post['foam'] ?? null,
            'heater'            => $post['heater'] ?? null,
            'steering_wheel'    => $post['steering_wheel'] ?? null,
            'suspension_system' => $post['suspension_system'] ?? null,
            'air_brake'         => $post['air_brake'] ?? null,
            'fuel_system'       => $post['fuel_system'] ?? null,
            'comments'          => $post['comments'] ?? null,
            'def'               => $post['def'] ?? null,
        ];

        $dateIssue = $post['date'] ?? '';
        $data['date_issue'] = ($userRol == 99 && $dateIssue !== '')
            ? $dateIssue . ' ' . date('G:i:s')
            : date('Y-m-d G:i:s');

        $builder = $this->db->table('inspection_hydrovac');
        if ($idInspection == '') {
            $data['fk_id_user'] = $idUser;
            $result = $builder->insert($data);
            return $result ? $this->db->insertID() : false;
        }
        $result = $builder->where('id_inspection_hydrovac', $idInspection)->update($data);
        return $result ? (int) $idInspection : false;
    }

    /**
     * Add/Edit Water Truck Inspection
     * @since 12/6/2017
     * @review 29/04/2026 - new CI4 version
     */
    public function saveWatertruckInspection(array $post, int $idUser, int $userRol): int|false
    {
        $idInspection = $post['hddId'] ?? '';

        $data = [
            'fk_id_vehicle'     => $post['hddIdVehicle'] ?? null,
            'belt'              => $post['belt'] ?? null,
            'power_steering'    => $post['powerSteering'] ?? null,
            'oil_level'         => $post['oil'] ?? null,
            'coolant_level'     => $post['coolantLevel'] ?? null,
            'coolant_leaks'     => $post['coolantLeaks'] ?? null,
            'head_lamps'        => $post['headLamps'] ?? null,
            'hazard_lights'     => $post['hazardLights'] ?? null,
            'clearance_lights'  => $post['clearanceLights'] ?? null,
            'tail_lights'       => $post['tailLights'] ?? null,
            'work_lights'       => $post['workLights'] ?? null,
            'turn_signals'      => $post['turnSignals'] ?? null,
            'beacon_lights'     => $post['beaconLights'] ?? null,
            'tires'             => $post['tires'] ?? null,
            'mirrors'           => $post['mirrors'] ?? null,
            'clean_exterior'    => $post['cleanExterior'] ?? null,
            'wipers'            => $post['wipers'] ?? null,
            'backup_beeper'     => $post['backupBeeper'] ?? null,
            'door'              => $post['door'] ?? null,
            'decals'            => $post['decals'] ?? null,
            'sprinkelrs'        => $post['sprinkelrs'] ?? null,
            'stering_axle'      => $post['steringAxle'] ?? null,
            'drives_axles'      => $post['drives'] ?? null,
            'front_drive'       => $post['frontDrive'] ?? null,
            'back_drive'        => $post['backDrive'] ?? null,
            'water_pump'        => $post['waterPump'] ?? null,
            'brake'             => $post['brake'] ?? null,
            'emergency_brake'   => $post['emergencyBrake'] ?? null,
            'gauges'            => $post['gauges'] ?? null,
            'horn'              => $post['horn'] ?? null,
            'seatbelt'          => $post['seatbelt'] ?? null,
            'seat'              => $post['seat'] ?? null,
            'insurance'         => $post['insurance'] ?? null,
            'registration'      => $post['registration'] ?? null,
            'clean_interior'    => $post['cleanInterior'] ?? null,
            'fire_extinguisher' => $post['fire'] ?? null,
            'first_aid'         => $post['aid'] ?? null,
            'emergency_kit'     => $post['emergencyKit'] ?? null,
            'spill_kit'         => $post['spillKit'] ?? null,
            'heater'            => $post['heater'] ?? null,
            'steering_wheel'    => $post['steering_wheel'] ?? null,
            'suspension_system' => $post['suspension_system'] ?? null,
            'air_brake'         => $post['air_brake'] ?? null,
            'fuel_system'       => $post['fuel_system'] ?? null,
            'comments'          => $post['comments'] ?? null,
        ];

        $dateIssue = $post['date'] ?? '';
        $data['date_issue'] = ($userRol == 99 && $dateIssue !== '')
            ? $dateIssue . ' ' . date('G:i:s')
            : date('Y-m-d G:i:s');

        $builder = $this->db->table('inspection_watertruck');
        if ($idInspection == '') {
            $data['fk_id_user'] = $idUser;
            $result = $builder->insert($data);
            return $result ? $this->db->insertID() : false;
        }
        $result = $builder->where('id_inspection_watertruck', $idInspection)->update($data);
        return $result ? (int) $idInspection : false;
    }

    /**
     * Save inspection summary record
     * @since 17/1/2019
     * @review 29/04/2026 - new CI4 version
     */
    public function saveInspectionTotal(int $idMachine): bool
    {
        return (bool) $this->db->table('inspection_total')->insert([
            'fk_id_machine'   => $idMachine,
            'date_inspection' => date('Y-m-d'),
        ]);
    }

    /**
     * Update vehicle safety flags from daily inspection
     * @since 26/1/2019
     * @review 29/04/2026 - new CI4 version
     */
    public function saveSeguimiento(array $post, int $idVehicle): bool
    {
        $lights = (
            ($post['headLamps'] ?? 1) == 0 || ($post['hazardLights'] ?? 1) == 0 ||
            ($post['bakeLights'] ?? 1) == 0 || ($post['workLights'] ?? 1) == 0 ||
            ($post['turnSignals'] ?? 1) == 0 || ($post['beaconLight'] ?? 1) == 0 ||
            ($post['clearanceLights'] ?? 1) == 0
        ) ? 0 : 1;

        return (bool) $this->db->table('param_vehicle')
            ->where('id_vehicle', $idVehicle)
            ->update([
                'heater_check'            => $post['heater'] ?? null,
                'brakes_check'            => $post['brakePedal'] ?? null,
                'lights_check'            => $lights,
                'steering_wheel_check'    => $post['steering_wheel'] ?? null,
                'suspension_system_check' => $post['suspension_system'] ?? null,
                'tires_check'             => $post['nuts'] ?? null,
                'wipers_check'            => $post['wipers'] ?? null,
                'air_brake_check'         => $post['air_brake'] ?? null,
                'driver_seat_check'       => $post['passengerDoor'] ?? null,
                'fuel_system_check'       => $post['fuel_system'] ?? null,
            ]);
    }

    /**
     * Update vehicle safety flags from hydrovac inspection
     * @since 26/1/2019
     * @review 29/04/2026 - new CI4 version
     */
    public function saveSeguimientoHydrovac(array $post, int $idVehicle): bool
    {
        $lights = (
            ($post['headLamps'] ?? 1) == 0 || ($post['hazardLights'] ?? 1) == 0 ||
            ($post['tailLights'] ?? 1) == 0 || ($post['workLights'] ?? 1) == 0 ||
            ($post['turnSignals'] ?? 1) == 0 || ($post['beaconLights'] ?? 1) == 0 ||
            ($post['clearanceLights'] ?? 1) == 0
        ) ? 0 : 1;

        return (bool) $this->db->table('param_vehicle')
            ->where('id_vehicle', $idVehicle)
            ->update([
                'heater_check'            => $post['heater'] ?? null,
                'brakes_check'            => $post['brake'] ?? null,
                'lights_check'            => $lights,
                'steering_wheel_check'    => $post['steering_wheel'] ?? null,
                'suspension_system_check' => $post['suspension_system'] ?? null,
                'tires_check'             => $post['tires'] ?? null,
                'wipers_check'            => $post['wipers'] ?? null,
                'air_brake_check'         => $post['air_brake'] ?? null,
                'driver_seat_check'       => $post['door'] ?? null,
                'fuel_system_check'       => $post['fuel_system'] ?? null,
            ]);
    }

    /**
     * Update vehicle safety flags from water truck inspection
     * @since 26/1/2019
     * @review 29/04/2026 - new CI4 version
     */
    public function saveSeguimientoWatertruck(array $post, int $idVehicle): bool
    {
        $lights = (
            ($post['headLamps'] ?? 1) == 0 || ($post['hazardLights'] ?? 1) == 0 ||
            ($post['tailLights'] ?? 1) == 0 || ($post['workLights'] ?? 1) == 0 ||
            ($post['turnSignals'] ?? 1) == 0 || ($post['beaconLights'] ?? 1) == 0 ||
            ($post['clearanceLights'] ?? 1) == 0
        ) ? 0 : 1;

        return (bool) $this->db->table('param_vehicle')
            ->where('id_vehicle', $idVehicle)
            ->update([
                'heater_check'            => $post['heater'] ?? null,
                'brakes_check'            => $post['brake'] ?? null,
                'lights_check'            => $lights,
                'steering_wheel_check'    => $post['steering_wheel'] ?? null,
                'suspension_system_check' => $post['suspension_system'] ?? null,
                'tires_check'             => $post['tires'] ?? null,
                'wipers_check'            => $post['wipers'] ?? null,
                'air_brake_check'         => $post['air_brake'] ?? null,
                'driver_seat_check'       => $post['door'] ?? null,
                'fuel_system_check'       => $post['fuel_system'] ?? null,
            ]);
    }
}
