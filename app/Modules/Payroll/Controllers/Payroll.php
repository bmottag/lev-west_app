<?php
namespace App\Modules\Payroll\Controllers;

use App\Controllers\BaseController;
use App\Modules\Payroll\Models\PayrollModel;
use App\Models\GeneralModel;
use App\Libraries\PdfBuilder;

class Payroll extends BaseController
{
	protected $payrollModel;
	protected $generalModel;

	public function __construct()
	{
		$this->payrollModel = new PayrollModel();
		$this->generalModel = new GeneralModel();
	}

	/**
	 * Form Add Payroll
	 * @since 09/11/2016
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	public function add_payroll($id = 'x')
	{
		$idUser = $this->session->get('id');

		$sql = "SELECT p.fk_id_job, p.id_programming
				FROM programming_worker pw
				JOIN programming p ON pw.fk_id_programming = p.id_programming
				WHERE pw.fk_id_programming_user = $idUser
				AND DATE(p.date_programming) = CURDATE()";

		$query = db_connect()->query($sql);

		$data['job_programming'] = null;
		$data['programming']     = null;

		if ($row = $query->getRowArray()) {
			$data['job_programming'] = $row['fk_id_job'];
			$data['programming']     = $row['id_programming'];
		}

		$data['jobs'] = $this->generalModel->get_basic_search([
			'table'  => 'param_jobs',
			'order'  => 'job_description',
			'column' => 'state',
			'id'     => 1,
		]);

		$data['record'] = $this->generalModel->get_task([
			'idEmployee' => $idUser,
			'limit'      => 1,
		]);

		$view = 'App\Modules\Payroll\Views\form_add_payroll';

		if ($data['record'] && (empty($data['record'][0]['finish']) || $data['record'][0]['finish'] == '0000-00-00 00:00:00')) {
			$data['programming'] = $data['record'][0]['fk_id_programming'];
			$data['start']       = $data['record'][0]['start'];
			$view = 'App\Modules\Payroll\Views\form_end_payroll';
		}

		return $this->render($view, $data);
	}

	/**
	 * Save payroll
	 * @since 09/11/2016
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	public function savePayroll()
	{
		$hour = date('G:i');
		$post = $this->request->getPost();

		if ($this->payrollModel->savePayroll($post)) {
			$infoServiceOrder = $this->payrollModel->get_in_progress_service_order();
			if ($infoServiceOrder) {
				$this->payrollModel->updateServiceOrderTime($infoServiceOrder, 'start');
			}
			session()->setFlashdata('retornoExito', 'have a nice shift, you started at ' . $hour . '.');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url($this->session->get('dashboardURL')));
	}

	/**
	 * Update finish time payroll
	 * @since 11/11/2016
	 * @review 2/02/2022
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	public function updatePayroll()
	{
		$post    = $this->request->getPost();
		$idTask  = $post['hddIdentificador'];
		$start   = $post['hddStart'];
		$finish  = date('Y-m-d G:i:s');
		$hour    = date('G:i');

		if ($this->payrollModel->updateWorkingTimePayroll($start, $finish, $post)) {
			$this->save_period($idTask);

			$infoServiceOrder = $this->payrollModel->get_in_progress_service_order();
			if ($infoServiceOrder) {
				$this->payrollModel->updateServiceOrderTime($infoServiceOrder, 'finish');
			}
			session()->setFlashdata('retornoExito', 'have a good night, you finished at ' . $hour . '.');
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> bad at math.');
		}

		return redirect()->to(base_url($this->session->get('dashboardURL')));
	}

	/**
	 * Cargo modal - formulario para editar las horas de los empleados
	 * @since 2/2/2018
	 * @review 05/05/2026 - new CI4 version
	 */
	public function cargarModalHours()
	{
		$idTask = $this->request->getPost('idTask');
		$data   = [
			'information' => $this->payrollModel->get_taskbyid((int)$idTask),
		];

		return $this->response
			->setContentType('text/html')
			->setBody(view('App\Modules\Payroll\Views\modal_hours_worker', $data));
	}

	/**
	 * Save payroll hours - used when admin is updating employee hours
	 * @since 2/2/2018
	 * @author BMOTTAG
	 * @review 05/05/2026 - new CI4 version
	 */
	public function savePayrollHour()
	{
		$post          = $this->request->getPost();
		$idTask        = $post['hddIdentificador'];
		$fechaAnterior = $post['hddfechaInicio'];
		$fechaStart    = $post['start_date'];

		$data = [
			'idRecord'    => $idTask,
			'datePayroll' => $fechaStart,
		];

		if ($this->payrollModel->savePayrollHour($post)) {
			$infoTask = $this->payrollModel->get_taskbyid((int)$idTask);
			$start    = $infoTask['start'];
			$finish   = $infoTask['finish'];

			if ($this->payrollModel->updateWorkingTimePayroll($start, $finish, $post, 1)) {
				if ($fechaAnterior != $fechaStart) {
					$this->save_period($idTask);
				}
				session()->setFlashdata('retornoExito', 'You have updated the payroll hour');
			} else {
				session()->setFlashdata('retornoError', '<strong>Error!!!</strong> bad at math.');
			}

			$data['status'] = 'success';
		} else {
			$data['status'] = 'error';
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}

	/**
	 * Busco a que periodo pertenece y guardo el id en la tabla task
	 * @since 9/2/2022
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	protected function save_period($idTask)
	{
		$idWeakPeriod = false;

		while (!$idWeakPeriod) {
			$idWeakPeriod = $this->search_period($idTask);

			if (!$idWeakPeriod) {
				$this->generate_period();
			}
		}

		return $this->generalModel->updateRecord([
			'table'      => 'task',
			'primaryKey' => 'id_task',
			'id'         => $idTask,
			'column'     => 'fk_id_weak_period',
			'value'      => $idWeakPeriod,
		]);
	}

	/**
	 * Busco a que periodo pertenece
	 * @since 9/2/2022
	 * @review 01/05/2026 - new CI4 version
	 */
	protected function search_period($idTask)
	{
		$infoPayroll = $this->generalModel->get_task(['idTask' => $idTask]);
		$fechaStart  = date_create(date('Y-m-j', strtotime($infoPayroll[0]['start'])));

		$infoPeriod = $this->generalModel->get_weak_period(['limit' => 10]);

		$idWeakPeriod = false;
		foreach ($infoPeriod as $row) {
			$periodoIni = date_create($row['date_weak_start']);
			$periodoFin = date_create($row['date_weak_finish']);

			if ($fechaStart >= $periodoIni && $fechaStart <= $periodoFin) {
				$idWeakPeriod = $row['id_period_weak'];
				break;
			}
		}

		return $idWeakPeriod;
	}

	/**
	 * Genera 2 secuencias de periodos
	 * @since 9/2/2022
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	public function generate_period()
	{
		for ($i = 0; $i < 2; $i++) {
			$infoPeriod = $this->generalModel->get_period(['limit' => 1]);
			$lastFinish = $infoPeriod[0]['date_finish'];

			$periodoIniNew = date('Y-m-d', strtotime('+1 day', strtotime($lastFinish)));
			$periodoFinNew = date('Y-m-d', strtotime('+14 day', strtotime($lastFinish)));
			$yearPeriodo   = date('Y', strtotime($periodoIniNew));

			$idPeriod = $this->payrollModel->savePeriod([
				'periodoIniNew' => $periodoIniNew,
				'periodoFinNew' => $periodoFinNew,
				'yearPeriodo'   => $yearPeriodo,
			]);

			if ($idPeriod) {
				$semana1IniNew = date('Y-m-d', strtotime('+1 day', strtotime($lastFinish)));
				$semana1FinNew = date('Y-m-d', strtotime('+7 day', strtotime($lastFinish)));
				$semana2IniNew = date('Y-m-d', strtotime('+1 day', strtotime($semana1FinNew)));
				$semana2FinNew = date('Y-m-d', strtotime('+7 day', strtotime($semana1FinNew)));

				$this->payrollModel->saveWeakPeriod([
					'idPeriod'      => $idPeriod,
					'semana1IniNew' => $semana1IniNew,
					'semana1FinNew' => $semana1FinNew,
					'semana2IniNew' => $semana2IniNew,
					'semana2FinNew' => $semana2FinNew,
				]);
			}
		}

		return true;
	}

	/**
	 * Payroll form search
	 * @since 10/02/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function payrollSearchForm($contractType = 'x', $idPeriod = 'x', $idEmployee = '')
	{
		$actualYear       = date('Y');
		$data['infoPeriod'] = $this->generalModel->get_period(['year_period' => $actualYear]);

		$view = 'App\Modules\Payroll\Views\form_search';

		if (($contractType !== 'x' && $idPeriod !== 'x') || $this->request->is('post') ) {
			if ($idPeriod !== 'x') {
				$data['contractType'] = $contractType;
				$data['idPeriod']     = $idPeriod;
				$data['idEmployee']   = $idEmployee;
			}

			if ($this->request->getPost('period')) {
				$data['contractType'] = $this->request->getPost('contractType');
				$data['idPeriod']     = $this->request->getPost('period');
				$data['idEmployee']   = $this->request->getPost('employee');
			}

			$data['infoPeriod']     = $this->generalModel->get_period(['idPeriod' => $data['idPeriod']]);
			$data['infoWeakPeriod'] = $this->generalModel->get_weak_period(['idPeriod' => $data['idPeriod']]);

			$data['info'] = $this->generalModel->get_users_by_period([
				'idPeriod'              => $data['idPeriod'],
				'idEmployee'            => $data['idEmployee'],
				'employee_subcontractor' => $data['contractType'],
			]);

			$view = $data['contractType'] == 2
				? 'App\Modules\Payroll\Views\list_payroll'
				: 'App\Modules\Payroll\Views\list_payroll_subcontractor';
		}

		$data['dashboardURL'] = $this->session->get('dashboardURL');

		return $this->renderTopOnly($view, $data);
	}

	/**
	 * Save paystub
	 * @since 21/2/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function save_paystub()
	{
		$post         = $this->request->getPost();
		$contractType = $post['contractType'];
		$idPeriod     = $post['period'];
		$idEmployee   = $post['employee'];
		$userBankTime = $post['hddBankTime'];
		$bankTimeFlag = $post['hddBankTimeFlag'];
		$btnGenerate  = $post['btnGenerate'] ?? null;

		if ($this->payrollModel->savePaystub($post)) {
			session()->setFlashdata('retornoExito', 'You have saved the Paystub!!');

			if (isset($btnGenerate)) {
				if ($userBankTime == 1 && $bankTimeFlag != '') {
					$this->generalModel->saveBankTimeBalance([
						'idPeriod'            => $idPeriod,
						'idEmployee'          => $idEmployee,
						'bankTimeAdd'         => $post['hddBankTimeAdd'],
						'bankTimeSubtract'    => $post['hddBankTimeSubtract'],
						'bankNewBalance'      => $post['hddBankTimeNewBalance'],
						'observation'         => 'New Paystub',
					]);
				}

				$this->payrollModel->updateTaskStatus($post);

				$infoTotalYear = $this->generalModel->get_total_yearly([
					'idUser' => $post['hddIdUser'],
					'year'   => $post['hddYear'],
				]);
				$this->payrollModel->updatePayrollTotalYearly($post, $infoTotalYear);
			}
		} else {
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return redirect()->to(base_url('payroll/payrollSearchForm/' . $contractType . '/' . $idPeriod . '/' . $idEmployee));
	}

	/**
	 * Payroll form search - review paystubs
	 * @since 28/02/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function reviewPaystubs()
	{
		$data['workersList'] = $this->generalModel->get_user(['state' => 1]);

		if ($this->request->getMethod() === 'post') {
			$data['year']       = $this->request->getPost('year');
			$data['idEmployee'] = $this->request->getPost('employee');

			$data['info'] = $this->generalModel->get_paystub_by_period([
				'year'       => $data['year'],
				'idEmployee' => $data['idEmployee'],
			]);
		}

		$data['dashboardURL'] = $this->session->get('dashboardURL');

		return $this->renderTopOnly('App\Modules\Payroll\Views\form_search_paystubs', $data);
	}

	/**
	 * Payroll form search - review total yearly
	 * @since 28/02/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function reviewYearly()
	{
		$data['workersList'] = $this->generalModel->get_user(['state' => 1]);

		if ($this->request->getMethod() === 'post') {
			$data['year']       = $this->request->getPost('year');
			$data['idEmployee'] = $this->request->getPost('employee');

			$data['info'] = $this->generalModel->get_total_yearly([
				'year'   => $data['year'],
				'idUser' => $data['idEmployee'],
			]);
		}

		$data['dashboardURL'] = $this->session->get('dashboardURL');

		return $this->renderTopOnly('App\Modules\Payroll\Views\form_search_total_yearly', $data);
	}

	/**
	 * Payroll form search - time sheet
	 * @since 10/04/2022
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	public function payrollSearchTimeSheet($idPeriod = 'x', $idEmployee = '')
	{
		$data['workersList'] = $this->generalModel->get_user(['state' => 1]);

		$view = 'App\Modules\Payroll\Views\form_search_time_sheet';

		if ($this->request->getPost('from')) {
			$data['idEmployee'] = $this->request->getPost('employee');

			$from         = $this->request->getPost('from');
			$to           = $this->request->getPost('to');
			$data['from'] = formatear_fecha($from);
			$data['to']   = formatear_fecha($to);

			$data['to'] = date('Y-m-d', strtotime('+1 day', strtotime($data['to'])));

			$allTasks = $this->generalModel->get_payroll_full([
				'from' => $data['from'],
				'to' => $data['to'],
				'idEmployee' => $data['idEmployee'],
			]);

			$grouped = [];
			foreach ($allTasks as $task) {
				$uid = $task['fk_id_user'];
				if (!isset($grouped[$uid])) {
					$grouped[$uid] = [
						'fk_id_user'    => $uid,
						'employee_name' => $task['employee_name'],
						'tasks'         => [],
					];
				}
				$grouped[$uid]['tasks'][] = $task;
			}
			$data['info'] = array_values($grouped);

			$view = 'App\Modules\Payroll\Views\list_payroll_time_sheet';
		}

		$data['dashboardURL'] = $this->session->get('dashboardURL');

		return $this->renderTopOnly($view, $data);
	}

	/**
	 * Form Payroll Check - used by cron
	 * @since 12/04/2022
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	public function payroll_check()
	{
		$records = $this->generalModel->get_payroll_check();

		foreach ($records as $data) {
			$fechaStart   = date('Y-m-d G:i:s', strtotime($data['start']));
			$fechaActual  = date('Y-m-d G:i:s');
			$hours        = abs((strtotime($fechaStart) - strtotime($fechaActual)) / 3600);
			$hours        = round($hours);

			if ($hours > 18) {
				$this->updatePayrollAutomatically($data['id_task'], $data['start']);
			} elseif ($hours > 14) {
				$this->sendSMSWorkerTask($data['fk_id_user']);
			}
		}
	}

	/**
	 * Close finish time payroll - automatically
	 * @since 13/04/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function updatePayrollAutomatically($idTask, $start)
	{
		$finish = date('Y-m-d G:i:s');
		$this->payrollModel->updateWorkingTimePayrollCheck($start, $finish, (int)$idTask);
		$this->save_period($idTask);

		return true;
	}

	/**
	 * Send text message to employee when they haven't checked out
	 * @since 13/4/2022
	 * @author BMOTTAG
	 * @review 15/05/2026 - new CI4 version
	 */
	public function sendSMSWorkerTask($idUser)
	{
		$userInfo = $this->generalModel->get_user(['idUser' => $idUser]);

		if (!$userInfo || empty($userInfo[0]['movil'])) {
			return false;
		}

		$mensaje  = "VCI TIME SHEET";
		$mensaje .= "\n" . $userInfo[0]['first_name'] . ' ' . $userInfo[0]['last_name'];
		$mensaje .= "\nThis message is to remind you that you have been working more than 14 hours, it is possible that you forgot to check out, if it is the case please login the system and check out.";

		$smsService = new \App\Libraries\SmsService();

		try {
			$smsService->send('+1' . $userInfo[0]['movil'], $mensaje);
		} catch (\Exception $e) {
			log_message('error', 'payroll_check SMS error for user ' . $idUser . ': ' . $e->getMessage());
		}

		return true;
	}

	/**
	 * Generate PAYSTUB PDF
	 * @param int $idPaytsub
	 * @since 24/12/2022
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function generaPaystubPDF($idPaytsub)
	{
		$data['infoPaystub'] = $this->generalModel->get_paystub_by_period(['idPaytsub' => $idPaytsub]);

		$builder = new PdfBuilder();
		$pdf     = $builder->create('Paystub');

		$html = view('App\Modules\Payroll\Views\report_paystub', $data);
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->lastPage();

		if (ob_get_length()) {
			ob_end_clean();
		}

		return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setBody($pdf->Output('paystub_' . $idPaytsub . '.pdf', 'I'));
	}

	/**
	 * Employee list by contract type (AJAX)
	 * @since 11/9/2022
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	public function employeeList()
	{
		$identificador = $this->request->getPost('identificador');
		$lista         = $this->generalModel->get_user([
			'state'                  => 1,
			'employee_subcontractor' => $identificador,
		]);

		$html = "<option value=''>Select...</option>";
		if ($lista) {
			foreach ($lista as $fila) {
				$html .= "<option value='" . esc($fila['id_user']) . "'>" . esc($fila['first_name']) . ' ' . esc($fila['last_name']) . '</option>';
			}
		}

		return $this->response
			->setContentType('text/html; charset=utf-8')
			->setBody($html);
	}

	/**
	 * Period list by year (AJAX)
	 * @since 23/12/2022
	 * @author BMOTTAG
	 * @review 01/05/2026 - new CI4 version
	 */
	public function periodList()
	{
		$identificador = $this->request->getPost('identificador');
		$lista         = $this->generalModel->get_period(['year_period' => $identificador]);

		$html = "<option value=''>Select...</option>";
		if ($lista) {
			foreach ($lista as $fila) {
				$html .= "<option value='" . esc($fila['id_period']) . "'>" . esc($fila['period']) . '</option>';
			}
		}

		return $this->response
			->setContentType('text/html; charset=utf-8')
			->setBody($html);
	}

	/**
	 * Cargo modal - formulario para editar las horas por job code
	 * @since 2/2/2018
	 * @review 30/04/2026 - new CI4 version
	 */
	public function cargarModalJobCode()
	{
		$idTask = $this->request->getPost('idTask');

		$jobs        = $this->generalModel->get_basic_search([
			'table'  => 'param_jobs',
			'order'  => 'job_description',
			'column' => 'state',
			'id'     => 1,
		]);
		$information = $this->payrollModel->get_taskbyid((int)$idTask);

		$data = [
			'jobs'        => $jobs,
			'information' => $information,
			'hours_start' => $this->separarHorasMinutosEntero($information['hours_start_project']),
			'hours_end'   => $this->separarHorasMinutosEntero($information['hours_end_project']),
		];

		return $this->response
			->setContentType('text/html')
			->setBody(view('App\Modules\Payroll\Views\modal_job_code', $data));
	}

	/**
	 * Separa las horas y los minutos de un valor decimal
	 * @since 2/2/2018
	 * @review 30/04/2026 - new CI4 version
	 */
	protected function separarHorasMinutosEntero($horas_decimal): array
	{
		$horas           = intval($horas_decimal);
		$minutos_decimal = $horas_decimal - $horas;
		$minutos         = round($minutos_decimal * 60);

		return ['horas' => $horas, 'minutos' => $minutos];
	}

	/**
	 * Update task with WO job codes and hours
	 * @since 2/2/2018
	 * @author BMOTTAG
	 * @review 30/04/2026 - new CI4 version
	 */
	public function updateTaskWithWO()
	{
		$post   = $this->request->getPost();
		$idTask = $post['hddIdentificador'];

		$data = ['idRecord' => $idTask];

		if ($this->payrollModel->updateTaskWithWO($post)) {
			$data['result'] = true;
			session()->setFlashdata('retornoExito', 'You have updated the payroll hour');
		} else {
			$data['result'] = 'error';
			session()->setFlashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
		}

		return $this->response->setJSON($data);
	}
}
