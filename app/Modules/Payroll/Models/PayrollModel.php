<?php
namespace App\Modules\Payroll\Models;

use CodeIgniter\Model;

class PayrollModel extends Model
{
	protected $protectFields = false;

	/**
	 * Add PAYROLL
	 * @since 9/11/2016
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function savePayroll(array $post): bool
	{
		$idUser          = session()->get('id');
		$idOperation     = $post['hddTask'] ?? null;
		$idJob           = $post['jobName'] ?? null;
		$task            = $post['taskDescription'] ?? '';
		$latitude        = $post['latitud'] ?? 0;
		$longitude       = $post['longitud'] ?? 0;
		$fk_id_programming = $post['programming'] ?? null;
		$address         = $post['address'] ?? '';
		$fecha           = date('Y-m-d G:i:s');

		if ($fk_id_programming) {
			$progRow = $this->db->table('programming')
				->select('fk_id_job, fk_id_workorder')
				->where('id_programming', $fk_id_programming)
				->get()->getRowArray();

			if (!$progRow || $progRow['fk_id_job'] != $idJob) {
				$fk_id_programming = null;
			}
		}

		$data = [
			'fk_id_user'       => $idUser,
			'fk_id_operation'  => $idOperation,
			'fk_id_job'        => $idJob,
			'task_description' => $task,
			'start'            => $fecha,
			'latitude_start'   => $latitude,
			'longitude_start'  => $longitude,
			'address_start'    => $address,
		];

		if ($fk_id_programming) {
			$data['fk_id_programming'] = $fk_id_programming;
		}

		return $this->db->table('task')->insert($data);
	}

	/**
	 * Update PAYROLL - working time and working hours
	 * param $adminUpdate: 'x' = employee closing shift, 1 = admin updating
	 * @since 17/11/2016
	 * @review 2/02/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function updateWorkingTimePayroll(string $fechaStart, string $fechaCierre, array $post, $adminUpdate = 'x'): bool
	{
		$dteStart    = new \DateTime($fechaStart);
		$dteEnd      = new \DateTime($fechaCierre);
		$dteDiff     = $dteStart->diff($dteEnd);
		$workingTime = $dteDiff->format('%R%a days %H:%I:%S');

		$workingHours  = calculate_time_difference_in_hours($fechaStart, $fechaCierre);
		$overtimeHours = 0;
		if ($workingHours > 8) {
			$regularHours  = 8;
			$overtimeHours = $workingHours - 8;
		} else {
			$regularHours = $workingHours;
		}

		$hours_first_project = $post['hours_first_project'] ?? 0;
		$idProgramming       = $post['programming'] ?? null;
		$idTask              = $post['hddIdentificador'] ?? null;

		$hours_first_project = $hours_first_project ?: 0;
		$hours_end_project   = $workingHours - $hours_first_project;

		$taskRow = $this->db->table('task')
			->select('fk_id_job')
			->where('id_task', $idTask)
			->get()->getRowArray();

		if (($post['jobName'] ?? null) == ($taskRow['fk_id_job'] ?? null)) {
			$hours_end_project = null;
		}

		$hoursNEW         = $dteDiff->h + ($dteDiff->days * 24);
		$minutesNEW       = $dteDiff->i;
		$secondsNEW       = $dteDiff->s;
		$formatNEW        = sprintf('%02d:%02d:%02d', $hoursNEW, $minutesNEW, $secondsNEW);
		$newOvertimeHours = 0;

		if ($hoursNEW >= 8) {
			$newRegularHours  = '08:00';
			$hoursNEW         = $hoursNEW - 8;
			$newOvertimeHours = sprintf('%02d:%02d:%02d', $hoursNEW, $minutesNEW, $secondsNEW);
		} else {
			$newRegularHours = $formatNEW;
		}

		if ($adminUpdate === 'x') {
			$idJob      = $post['jobName'] ?? null;
			$observation = $post['observation'] ?? '';
			$latitude   = $post['latitud'] ?? 0;
			$longitude  = $post['longitud'] ?? 0;
			$address    = $post['address'] ?? '';

			$data = [
				'observation'         => $observation,
				'finish'              => $fechaCierre,
				'fk_id_job_finish'    => $idJob,
				'latitude_finish'     => $latitude,
				'longitude_finish'    => $longitude,
				'address_finish'      => $address,
				'working_time'        => $workingTime,
				'working_hours'       => $workingHours,
				'working_hours_new'   => $formatNEW,
				'regular_hours'       => $regularHours,
				'regular_hours_new'   => $newRegularHours,
				'overtime_hours'      => $overtimeHours,
				'overtime_hours_new'  => $newOvertimeHours,
				'hours_end_project'   => $hours_end_project,
				'hours_start_project' => $hours_first_project,
			];
		} else {
			$data = [
				'working_time'        => $workingTime,
				'working_hours'       => $workingHours,
				'working_hours_new'   => $formatNEW,
				'regular_hours'       => $regularHours,
				'regular_hours_new'   => $newRegularHours,
				'overtime_hours'      => $overtimeHours,
				'overtime_hours_new'  => $newOvertimeHours,
				'hours_start_project' => $hours_first_project,
				'hours_end_project'   => $hours_end_project,
			];
		}

		if ($idProgramming) {
			$progRow = $this->db->table('programming')
				->select('fk_id_job, fk_id_workorder')
				->where('id_programming', $idProgramming)
				->get()->getRowArray();

			$job_programming = $progRow['fk_id_job'] ?? null;
			$id_workorder    = $progRow['fk_id_workorder'] ?? null;
			$idJob           = $post['jobName'] ?? null;
			$idUser          = session()->get('id');

			if ($hours_first_project == 0) {
				if ($idJob == $job_programming && $id_workorder != null) {
					$existing = $this->db->table('workorder_personal')
						->where('fk_id_workorder', $id_workorder)
						->where('fk_id_user', $idUser)
						->get()->getRowArray();

					if ($existing) {
						if ($existing['hours'] == 0) {
							$this->db->table('workorder_personal')
								->where('fk_id_workorder', $id_workorder)
								->where('fk_id_user', $idUser)
								->update(['hours' => $workingHours]);
						}
					} else {
						$this->db->table('workorder_personal')->insert([
							'fk_id_workorder'     => $id_workorder,
							'fk_id_user'          => $idUser,
							'fk_id_employee_type' => 1,
							'hours'               => $workingHours,
						]);
					}

					$this->db->table('task')->where('id_task', $idTask)->update([
						'wo_start_project' => $id_workorder,
						'wo_end_project'   => $id_workorder,
					]);
				}
			} else {
				if ($id_workorder != null) {
					$existing = $this->db->table('workorder_personal')
						->where('fk_id_workorder', $id_workorder)
						->where('fk_id_user', $idUser)
						->get()->getRowArray();

					if ($existing && $existing['hours'] == 0) {
						$this->db->table('workorder_personal')
							->where('fk_id_workorder', $id_workorder)
							->where('fk_id_user', $idUser)
							->update(['hours' => $hours_first_project]);
					}
				}

				$this->db->table('task')->where('id_task', $idTask)->update([
					'wo_start_project' => $id_workorder,
				]);
			}
		}

		return $this->db->table('task')->where('id_task', $idTask)->update($data);
	}

	/**
	 * Update PAYROLL - from payroll_check cron
	 * @since 19/08/2025
	 * @review 30/04/2026 - new CI4 version
	 */
	public function updateWorkingTimePayrollCheck(string $fechaStart, string $fechaCierre, int $idTask): bool
	{
		$dteStart    = new \DateTime($fechaStart);
		$dteEnd      = new \DateTime($fechaCierre);
		$dteDiff     = $dteStart->diff($dteEnd);
		$workingTime = $dteDiff->format('%R%a days %H:%I:%S');

		$workingHours  = calculate_time_difference_in_hours($fechaStart, $fechaCierre);
		$overtimeHours = 0;
		if ($workingHours > 8) {
			$regularHours  = 8;
			$overtimeHours = $workingHours - 8;
		} else {
			$regularHours = $workingHours;
		}

		$hoursNEW         = $dteDiff->h + ($dteDiff->days * 24);
		$minutesNEW       = $dteDiff->i;
		$secondsNEW       = $dteDiff->s;
		$formatNEW        = sprintf('%02d:%02d:%02d', $hoursNEW, $minutesNEW, $secondsNEW);
		$newOvertimeHours = 0;

		if ($hoursNEW >= 8) {
			$newRegularHours  = '08:00';
			$hoursNEW         = $hoursNEW - 8;
			$newOvertimeHours = sprintf('%02d:%02d:%02d', $hoursNEW, $minutesNEW, $secondsNEW);
		} else {
			$newRegularHours = $formatNEW;
		}

		$observation = '********************<br><strong>Changue hour by the system, automatically.</strong><br>********************';

		return $this->db->table('task')->where('id_task', $idTask)->update([
			'observation'        => $observation,
			'finish'             => $fechaCierre,
			'working_time'       => $workingTime,
			'working_hours'      => $workingHours,
			'working_hours_new'  => $formatNEW,
			'regular_hours'      => $regularHours,
			'regular_hours_new'  => $newRegularHours,
			'overtime_hours'     => $overtimeHours,
			'overtime_hours_new' => $newOvertimeHours,
		]);
	}

	/**
	 * Get task by ID
	 * @since 17/11/2016
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function get_taskbyid(int $id)
	{
		$result = $this->db->table('task')
			->where('id_task', $id)
			->get()->getRowArray();

		return $result ?: false;
	}

	/**
	 * Update payroll hour (admin edit)
	 * @since 2/2/2018
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function savePayrollHour(array $post): bool
	{
		$idTask           = $post['hddIdentificador'] ?? null;
		$inicio           = $post['hddInicio'] ?? '';
		$fin              = $post['hddFin'] ?? '';
		$firstObservation = $post['hddObservation'] ?? '';
		$observation      = $post['observation'] ?? '';

		$moreInfo = '<strong>Change hour by SUPER ADMIN.</strong> <br>Before -> Start: ' .
			date('M j, Y - G:i', strtotime($inicio)) .
			' <br>Before -> Finish: ' . date('M j, Y - G:i', strtotime($fin));

		$observation = $firstObservation . '<br>********************<br>' . $moreInfo . '<br>' . $observation . '<br>Date: ' . date('Y-m-d G:i:s') . '<br>********************';

		$fechaStart  = ($post['start_date'] ?? '') . ' ' . ($post['start_hour'] ?? '00') . ':' . ($post['start_min'] ?? '00') . ':00';
		$fechaFinish = ($post['finish_date'] ?? '') . ' ' . ($post['finish_hour'] ?? '00') . ':' . ($post['finish_min'] ?? '00') . ':00';

		return $this->db->table('task')->where('id_task', $idTask)->update([
			'observation' => $observation,
			'finish'      => $fechaFinish,
			'start'       => $fechaStart,
		]);
	}

	/**
	 * Add PERIOD
	 * @since 9/02/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function savePeriod(array $arrData)
	{
		$period = $arrData['periodoIniNew'] . ' - ' . $arrData['periodoFinNew'];

		$this->db->table('payroll_period')->insert([
			'date_start'  => $arrData['periodoIniNew'],
			'date_finish' => $arrData['periodoFinNew'],
			'period'      => $period,
			'year_period' => $arrData['yearPeriodo'],
		]);

		$idPeriod = $this->db->insertID();
		return $idPeriod ?: false;
	}

	/**
	 * Add Weak PERIOD
	 * @since 9/02/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function saveWeakPeriod(array $arrData): bool
	{
		$weak1 = $arrData['semana1IniNew'] . ' - ' . $arrData['semana1FinNew'];
		$weak2 = $arrData['semana2IniNew'] . ' - ' . $arrData['semana2FinNew'];

		$this->db->table('payroll_period_weaks')->insert([
			'fk_id_period'     => $arrData['idPeriod'],
			'date_weak_start'  => $arrData['semana1IniNew'],
			'date_weak_finish' => $arrData['semana1FinNew'],
			'period_weak'      => $weak1,
			'weak_number'      => 1,
		]);

		$this->db->table('payroll_period_weaks')->insert([
			'fk_id_period'     => $arrData['idPeriod'],
			'date_weak_start'  => $arrData['semana2IniNew'],
			'date_weak_finish' => $arrData['semana2FinNew'],
			'period_weak'      => $weak2,
			'weak_number'      => 2,
		]);

		return true;
	}

	/**
	 * Save paystub
	 * @since 21/2/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function savePaystub(array $post): bool
	{
		$idUser            = session()->get('id');
		$idPaytsub         = $post['hddIdPaytsub'] ?? '';
		$costRegularSalary = (float)($post['hddCostRegularSalary'] ?? 0);
		$costOvertime      = (float)($post['hddCostOvertime'] ?? 0);
		$costVacation      = (float)($post['hddCostVacation'] ?? 0);
		$totalIncome       = $costRegularSalary + $costOvertime;
		$gross_salary      = $totalIncome + $costVacation;
		$ee_cpp            = (float)($post['ee_cpp'] ?? 0);
		$er_cpp            = $ee_cpp;
		$ee_ei             = (float)($post['ee_ei'] ?? 0);
		$er_ei             = $ee_ei * 1.4;
		$tax               = (float)($post['tax'] ?? 0);
		$gwl_deductions    = (float)($post['gwl_deductions'] ?? 0);
		$ee_total_taxes    = $ee_cpp + $ee_ei + $tax;
		$remittance        = $ee_cpp + $er_cpp + $ee_ei + $er_ei + $tax;
		$net_pay           = $gross_salary - $ee_total_taxes - $gwl_deductions;
		$btnGenerate       = $post['btnGenerate'] ?? null;
		$valueCommit       = isset($btnGenerate) ? 1 : 2;

		$data = [
			'employee_rate_paystub'        => $post['hddEmployeeRate'] ?? null,
			'employee_type_paystub'        => $post['hddEmployeeType'] ?? null,
			'total_worked_hours'           => $post['hddTotalWorkedHours'] ?? 0,
			'total_regular_hours'          => $post['hddRegularHours'] ?? 0,
			'total_overtime_hours'         => $post['hddOvertimeHours'] ?? 0,
			'cost_regular_salary'          => $costRegularSalary,
			'cost_over_time'               => $costOvertime,
			'total_income'                 => $totalIncome,
			'cost_vacation_regular_salary' => $costVacation,
			'gross_salary'                 => $gross_salary,
			'ee_cpp'                       => $ee_cpp,
			'er_cpp'                       => $er_cpp,
			'ee_ei'                        => $ee_ei,
			'er_ei'                        => $er_ei,
			'tax'                          => $tax,
			'ee_total_taxes'               => $ee_total_taxes,
			'gwl_deductions'               => $gwl_deductions,
			'remittance'                   => $remittance,
			'net_pay'                      => $net_pay,
			'commit'                       => $valueCommit,
		];

		if (empty($idPaytsub)) {
			$data['paystub_date_issue']       = date('Y-m-d');
			$data['paystub_fk_id_user']       = $idUser;
			$data['fk_id_period']             = $post['hddIdPeriod'] ?? null;
			$data['fk_id_employee']           = $post['hddIdUser'] ?? null;
			$data['actual_bank_time_balance'] = $post['hddBankTimeBalance'] ?? null;
			return $this->db->table('payroll_paystub')->insert($data);
		} else {
			return $this->db->table('payroll_paystub')->where('id_paystub', $idPaytsub)->update($data);
		}
	}

	/**
	 * Update task status to paid
	 * @since 27/2/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function updateTaskStatus(array $post): bool
	{
		$idEmployee = $post['hddIdUser'] ?? null;
		$idWeak1    = $post['hddIdWeakPeriod1'] ?? null;
		$idWeak2    = $post['hddIdWeakPeriod2'] ?? null;

		return $this->db->table('task')
			->where('fk_id_user', $idEmployee)
			->whereIn('fk_id_weak_period', [$idWeak1, $idWeak2])
			->update(['period_status' => 2]);
	}

	/**
	 * Update payroll totals by year
	 * @since 27/2/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function updatePayrollTotalYearly(array $post, $arrData): bool
	{
		$idTotalYearly     = $post['hddIdTotalYearly'] ?? '';
		$workedHours       = (float)($post['hddTotalWorkedHours'] ?? 0);
		$regularHours      = (float)($post['hddRegularHours'] ?? 0);
		$overtimeHours     = (float)($post['hddOvertimeHours'] ?? 0);
		$costRegularSalary = (float)($post['hddCostRegularSalary'] ?? 0);
		$costOvertime      = (float)($post['hddCostOvertime'] ?? 0);
		$costVacation      = (float)($post['hddCostVacation'] ?? 0);
		$totalIncome       = $costRegularSalary + $costOvertime;
		$gross_salary      = $totalIncome + $costVacation;
		$ee_cpp            = (float)($post['ee_cpp'] ?? 0);
		$er_cpp            = $ee_cpp;
		$ee_ei             = (float)($post['ee_ei'] ?? 0);
		$er_ei             = $ee_ei * 1.4;
		$tax               = (float)($post['tax'] ?? 0);
		$gwl_deductions    = (float)($post['gwl_deductions'] ?? 0);
		$ee_total_taxes    = $ee_cpp + $ee_ei + $tax;
		$remittance        = $ee_cpp + $er_cpp + $ee_ei + $er_ei + $tax;
		$net_pay           = $gross_salary - $ee_total_taxes - $gwl_deductions;

		if ($arrData) {
			$row  = $arrData[0];
			$data = [
				'total_year_worked_hours'                 => $workedHours + $row['total_year_worked_hours'],
				'total_year_regular_hours'                => $regularHours + $row['total_year_regular_hours'],
				'total_year_overtime_hours'               => $overtimeHours + $row['total_year_overtime_hours'],
				'total_year_cost_regular_salary'          => $costRegularSalary + $row['total_year_cost_regular_salary'],
				'total_year_cost_over_time'               => $costOvertime + $row['total_year_cost_over_time'],
				'total_year_cost_vacation_regular_salary' => $costVacation + $row['total_year_cost_vacation_regular_salary'],
				'total_year_gross_salary'                 => $gross_salary + $row['total_year_gross_salary'],
				'total_year_ee_cpp'                       => $ee_cpp + $row['total_year_ee_cpp'],
				'total_year_er_cpp'                       => $er_cpp + $row['total_year_er_cpp'],
				'total_year_ee_ei'                        => $ee_ei + $row['total_year_ee_ei'],
				'total_year_er_ei'                        => $er_ei + $row['total_year_er_ei'],
				'total_year_tax'                          => $tax + $row['total_year_tax'],
				'total_year_ee_total_taxes'               => $ee_total_taxes + $row['total_year_ee_total_taxes'],
				'total_year_gwl_deductions'               => $gwl_deductions + $row['total_year_gwl_deductions'],
				'total_year_remittance'                   => $remittance + $row['total_year_remittance'],
				'total_year_net_pay'                      => $net_pay + $row['total_year_net_pay'],
			];
		} else {
			$data = [
				'total_year_worked_hours'                 => $workedHours,
				'total_year_regular_hours'                => $regularHours,
				'total_year_overtime_hours'               => $overtimeHours,
				'total_year_cost_regular_salary'          => $costRegularSalary,
				'total_year_cost_over_time'               => $costOvertime,
				'total_year_cost_vacation_regular_salary' => $costVacation,
				'total_year_gross_salary'                 => $gross_salary,
				'total_year_ee_cpp'                       => $ee_cpp,
				'total_year_er_cpp'                       => $er_cpp,
				'total_year_ee_ei'                        => $ee_ei,
				'total_year_er_ei'                        => $er_ei,
				'total_year_tax'                          => $tax,
				'total_year_ee_total_taxes'               => $ee_total_taxes,
				'total_year_gwl_deductions'               => $gwl_deductions,
				'total_year_remittance'                   => $remittance,
				'total_year_net_pay'                      => $net_pay,
			];
		}

		if (empty($idTotalYearly)) {
			$data['year']           = $post['hddYear'] ?? null;
			$data['fk_id_employee'] = $post['hddIdUser'] ?? null;
			return $this->db->table('payroll_total_yearly')->insert($data);
		} else {
			return $this->db->table('payroll_total_yearly')->where('id_total_yearly', $idTotalYearly)->update($data);
		}
	}

	/**
	 * In-progress service order
	 * @since 1/7/2023
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function get_in_progress_service_order()
	{
		$result = $this->db->table('service_order S')
			->select('T.*')
			->join('service_order_time T', 'T.fk_id_service_order = S.id_service_order', 'left')
			->where('S.service_status', 'in_progress_so')
			->where('S.fk_id_assign_to', 1)
			->get()->getRowArray();

		return $result ?: false;
	}

	/**
	 * Update SERVICE ORDER TIME
	 * @since 1/7/2023
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function updateServiceOrderTime(array $infoServiceOrder, string $path): bool
	{
		$date = date('Y-m-d G:i:s');

		if ($path === 'start') {
			$data = ['time_date' => $date];
		} else {
			$minutes = abs(strtotime($infoServiceOrder['time_date']) - strtotime($date)) / 60;
			$hours   = round($minutes / 60, 2) + $infoServiceOrder['time'];
			$data    = ['time' => $hours];
		}

		return $this->db->table('service_order_time')
			->where('id_time', $infoServiceOrder['id_time'])
			->update($data);
	}

	/**
	 * Update TASK with WO job codes and hours
	 * @since 17/02/2025
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function updateTaskWithWO(array $post): bool
	{
		$idTask = $post['hddIdentificador'] ?? null;

		$data = [
			'fk_id_job'           => $post['jobName'] ?? null,
			'hours_start_project' => $post['hours_first_project'] ?? 0,
			'fk_id_job_finish'    => $post['jobNameFinish'] ?? null,
			'hours_end_project'   => $post['hours_last_project'] ?? 0,
		];

		$this->db->table('task')->where('id_task', $idTask)->update($data);

		$task = $this->get_taskbyid((int)$idTask);

		if ($task && $task['wo_start_project']) {
			$this->db->table('workorder_personal')
				->where('fk_id_workorder', $task['wo_start_project'])
				->where('fk_id_user', $task['fk_id_user'])
				->update(['hours' => $data['hours_start_project']]);
		}

		if ($task && $task['wo_end_project']) {
			$this->db->table('workorder_personal')
				->where('fk_id_workorder', $task['wo_end_project'])
				->where('fk_id_user', $task['fk_id_user'])
				->update(['hours' => $data['hours_end_project']]);
		}

		return true;
	}
}
