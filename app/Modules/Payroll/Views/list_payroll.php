<div id="page-wrapper">
	<br>

	<?php if (session()->getFlashdata('retornoExito')): ?>
		<div class="alert alert-success">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<?= session()->getFlashdata('retornoExito') ?>
		</div>
	<?php endif; ?>

	<?php if (session()->getFlashdata('retornoError')): ?>
		<div class="alert alert-danger">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<?= session()->getFlashdata('retornoError') ?>
		</div>
	<?php endif; ?>

	<?php
		if(!$info){
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-violeta">
				<div class="panel-heading">
					<a class="btn btn-violeta btn-xs" href=" <?php echo base_url().'payroll/payrollSearchForm'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-clock-o fa-fw"></i> <b>PAYROLL REPORT </b>
					<br><b>Period Beginning: </b> <?php echo $infoPeriod[0]["date_start"]; ?>
					<br><b>Period Ending: </b> <?php echo $infoPeriod[0]["date_finish"]; ?>
				</div>

				<div class="panel-body">
					<div class="alert alert-danger">
						No data was found matching your criteria. 
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
		}else{

			function timeToSeconds($time) {
				$parts = explode(':', $time);
				return ($parts[0] * 3600) + ($parts[1] * 60);
			}
			
			function secondsToTime($seconds) {
				$hours = floor($seconds / 3600);
				$minutes = floor(($seconds / 60) % 60);
				return sprintf('%02d:%02d', $hours, $minutes);
			}
			
			function secondsToFractionHours($seconds) {
				$hours = floor($seconds / 3600);
				$minutes = floor(($seconds / 60) % 60);
				return $hours + ($minutes / 60);
			}

			foreach ($info as $lista):
				$arrParam = array("idUser" => $lista['fk_id_user']);
				$infoUser = $this->general_model->get_user($arrParam);
				$employeeName = $infoUser[0]["first_name"] . ' ' . $infoUser[0]["last_name"];
				$employeeHourRate = $infoUser[0]["employee_rate"];
				$employeeType = $infoUser[0]["employee_type"];
				$bankTime = $infoUser[0]["bank_time"];
				$idPeriod = $infoPeriod[0]["id_period"];
				//buscar por usuario y por periodo las horas de cada semana
				$arrParam = array(
					"idUser" => $lista['fk_id_user'],
					"idPeriod" => $idPeriod,
					"weakNumber" => 1
				);
				$infoPayrollUser1 = $this->general_model->get_task_by_period($arrParam);

				$arrParam = array(
					"idUser" => $lista['fk_id_user'],
					"idPeriod" => $idPeriod,
					"weakNumber" => 2
				);
				$infoPayrollUser2 = $this->general_model->get_task_by_period($arrParam);

				//buscar por perido y por usuario si ya tiene guardado el paystub
				$arrParam = array(
					"idEmployee" => $lista['fk_id_user'],
					"idPeriod" => $idPeriod
				);
				$infoPaystub = $this->general_model->get_paystub_by_period($arrParam);

				//si ya se guardo el paystub entonces tomo esos valores 
				if($infoPaystub){
					$employeeHourRate = $infoPaystub[0]["employee_rate_paystub"];
					$employeeType = $infoPaystub[0]["employee_type_paystub"];
				}

				//buscar el total de valores por año y usuario
				$arrParam = array(
					"idUser" => $lista['fk_id_user'],
					"year" => $infoPeriod[0]['year_period']
				);
				$infoTotalYear = $this->general_model->get_total_yearly($arrParam);

				//si hay registro envio el id del registro
				$idTotalYear = $infoTotalYear?$infoTotalYear[0]['id_total_yearly']:'';

	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-violeta">
				<div class="panel-heading">
					<a class="btn btn-violeta btn-xs" href=" <?php echo base_url().'payroll/payrollSearchForm'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-clock-o fa-fw"></i> <b>PAYROLL REPORT </b>
					<br><b>Period Beginning: </b> <?php echo $infoPeriod[0]["date_start"]; ?>
					<br><b>Period Ending: </b> <?php echo $infoPeriod[0]["date_finish"]; ?>
				</div>

				<div class="panel-body">
					<div class="alert alert-default">
						<h2><i class="fa fa-user"></i> <b>Employee: </b> <?php echo $employeeName; ?>
							<br><small><b>Hour rate: </b>$ <?php echo $employeeHourRate; ?></small>
							<br><small><b>Type: </b><?php echo $type = $employeeType==1?'Field':'Admin'; ?></small>
							<br><small><b>Is using Bank Time: </b><?php echo $bankTime==1?'Active':'Inactive'; ?></small>
						</h2>
					</div>
					<table width="100%" class="table table-hover" id="dataTables">
						<thead>
							<tr>
								<th class='text-center'>Date & Time - Start</th>
								<th class='text-center'>Date & Time - Finish</th>
								<th class='text-right'>Time Worked <small>(HH:MM)<small></th>
								<th class='text-right'>Time Regular <small>(HH:MM)<small></th>
								<th class='text-right'>Daily Overtime <small>(HH:MM)<small></th>
							</tr>
						</thead>
						<tbody>							
						<?php
							$totalHours1 = 0;
							$totalHours2 = 0;
							$actualRegularHours1 = 0;
							$actualOvertimeHours1 = 0;
							$actualRegularHours2 = 0;
							$actualOvertimeHours2 = 0;
							$hoursTotalFraction1 = 0;
							$hoursTotalFraction2 = 0;
							if($infoPayrollUser1)
							{
								echo "<tr><td class='text-left' colspan='6'>";
								echo "<p class='text-danger'><b>First Week: " . $infoPayrollUser1[0]["period_weak"] . "</b></p>";
								echo "</td></tr>";
								$totalRegular1 = 0;
								$totalOvertime1 = 0;
								$totalRegularWeek = 0;
								foreach ($infoPayrollUser1 as $lista):
									echo "<tr>";							
									echo "<td class='text-center'>" . $lista['start'] . "</td>";
									echo "<td class='text-center'>" . $lista['finish'] . "</td>";
									echo "<td class='text-right'>" . substr($lista['working_hours_new'], 0, 5) . "</td>";
									echo "<td class='text-right'>" . substr($lista['regular_hours_new'], 0, 5) . "</td>";
									echo "<td class='text-right'>" . substr($lista['overtime_hours_new'], 0, 5) . "</td>";
									echo "</tr>";
									if (!empty($lista['working_hours_new'])) {
										$totalHours1 += timeToSeconds($lista['working_hours_new']);
									}
									if (!empty($lista['regular_hours_new'])) {
										$totalRegular1 += timeToSeconds($lista['regular_hours_new']);
									}
									if (!empty($lista['overtime_hours_new'])) {
										$totalOvertime1 += timeToSeconds($lista['overtime_hours_new']);
									}
								endforeach;
								
								$hoursTotal1 = floor($totalHours1 / 3600);
								$minsTotal1 = floor(($totalHours1 / 60) % 60);

								$hoursRegular1 = floor($totalRegular1 / 3600);
								$minsRegular1 = floor(($totalRegular1 / 60) % 60);

								$hoursOvertime1 = floor($totalOvertime1 / 3600);
								$minsOvertime1 = floor(($totalOvertime1 / 60) % 60);								

								//los paso a fraccion de horas
								$hoursTotalFraction1 = secondsToFractionHours($totalHours1);
								$hoursRegularFraction1 = secondsToFractionHours($totalRegular1);
								$hoursOvertimeFraction1 = secondsToFractionHours($totalOvertime1);
								echo "<tr><td></td><td></td>";
								echo "<td class='text-right'><strong>" . sprintf('%02d:%02d', $hoursTotal1, $minsTotal1) . "</strong>(hh:mm)<br><strong>Total Weekly Hours:<br>" . round($hoursTotalFraction1, 3) . " hours</strong></td>";
								echo "<td class='text-right'><strong>" . sprintf('%02d:%02d', $hoursRegular1, $minsRegular1) . "</strong>(hh:mm)<br><strong>Total Daily Regular Hours Per Week:<br>" . round($hoursRegularFraction1, 3) . " hours</strong></td>";
								echo "<td class='text-right'><strong>" . sprintf('%02d:%02d', $hoursOvertime1, $minsOvertime1) . "</strong>(hh:mm)<br><strong>Total Daily Overtime Per Week:<br>" . round($hoursOvertimeFraction1, 3) . " hours</strong></td>";
								echo "</tr>";

								/** 
								 ** Reviso si se paso de 44 horas para la semana
								 **/
								$totalRegularWeek = $hoursTotalFraction1 > 44 ? 44 : $hoursTotalFraction1;
								$weeklyOvertime = $hoursTotalFraction1 > 44 ? $hoursTotalFraction1 - 44 : 0;

								echo "<tr><td></td><td></td><td></td>";
								echo "<td class='text-right'><strong>Total regular hours for the week:<br>" . round($totalRegularWeek, 3) . "</strong></td>";
								echo "<td class='text-right'><strong>Weekly Overtime Hours:<br>" . round($weeklyOvertime, 3) . "</strong></td>";
								echo "</tr>";

								$actualRegularHours1 = $weeklyOvertime > $hoursOvertimeFraction1 ? $totalRegularWeek : $hoursRegularFraction1;
								$actualOvertimeHours1 = $weeklyOvertime > $hoursOvertimeFraction1 ? $weeklyOvertime : $hoursOvertimeFraction1;
							
								echo "<tr><td></td><td></td><td></td>";
								echo "<td class='text-right'>";
								echo "<p class='text-primary'><b>Actual Regular Hours:<br> " . round($actualRegularHours1, 3) . "</b></p>";
								echo "</td>";
								echo "<td class='text-right'>";
								echo "<p class='text-primary'><b>Actual Overtime Hours:<br> " . round($actualOvertimeHours1, 3) . "</b></p>";
								echo "</td>";
								echo "</tr>";
								echo "<tr><td colspan='5'><br></td></tr>";
							}
							if($infoPayrollUser2)
							{
								echo "<tr><td class='text-left' colspan='6'>";
								echo "<p class='text-danger'><b>Second Week: " . $infoPayrollUser2[0]["period_weak"] . "</b></p>";
								echo "</td></tr>";
								$totalRegular2 = 0;
								$totalOvertime2 = 0;
								$totalRegularWeek = 0;
								$weeklyOvertime = 0;
								foreach ($infoPayrollUser2 as $lista):
									echo "<tr>";							
									echo "<td class='text-center'>" . $lista['start'] . "</td>";
									echo "<td class='text-center'>" . $lista['finish'] . "</td>";
									echo "<td class='text-right'>" . substr($lista['working_hours_new'], 0, 5) . "</td>";
									echo "<td class='text-right'>" . substr($lista['regular_hours_new'], 0, 5) . "</td>";
									echo "<td class='text-right'>" . substr($lista['overtime_hours_new'], 0, 5) . "</td>";
									echo "</tr>";
									if (!empty($lista['working_hours_new'])) {
										$totalHours2 += timeToSeconds($lista['working_hours_new']);
									}
									if (!empty($lista['regular_hours_new'])) {
										$totalRegular2 += timeToSeconds($lista['regular_hours_new']);
									}
									if (!empty($lista['overtime_hours_new'])) {
										$totalOvertime2 += timeToSeconds($lista['overtime_hours_new']);
									}
								endforeach;

								$hoursTotal2 = floor($totalHours2 / 3600);
								$minsTotal2 = floor(($totalHours2 / 60) % 60);

								$hoursRegular2 = floor($totalRegular2 / 3600);
								$minsRegular2 = floor(($totalRegular2 / 60) % 60);

								$hoursOvertime2 = floor($totalOvertime2 / 3600);
								$minsOvertime2 = floor(($totalOvertime2 / 60) % 60);								

								//los paso a fraccion de horas
								$hoursTotalFraction2 = secondsToFractionHours($totalHours2);
								$hoursRegularFraction2 = secondsToFractionHours($totalRegular2);
								$hoursOvertimeFraction2 = secondsToFractionHours($totalOvertime2);
								echo "<tr><td></td><td></td>";
								echo "<td class='text-right'><strong>" . sprintf('%02d:%02d', $hoursTotal2, $minsTotal2) . "</strong>(hh:mm)<br><strong>Total Weekly Hours:<br>" . round($hoursTotalFraction2, 3) . " hours</strong></td>";
								echo "<td class='text-right'><strong>" . sprintf('%02d:%02d', $hoursRegular2, $minsRegular2) . "</strong>(hh:mm)<br><strong>Total Daily Regular Hours Per Week:<br>" . round($hoursRegularFraction2, 3) . " hours</strong></td>";
								echo "<td class='text-right'><strong>" . sprintf('%02d:%02d', $hoursOvertime2, $minsOvertime2) . "</strong>(hh:mm)<br><strong>Total Daily Overtime Per Week:<br>" . round($hoursOvertimeFraction2, 3) . " hours</strong></td>";
								echo "</tr>";

								/** 
								 ** Reviso si se paso de 44 horas para la semana
								 **/
								$totalRegularWeek = $hoursTotalFraction2 > 44 ? 44 : $hoursTotalFraction2;
								$weeklyOvertime = $hoursTotalFraction2 > 44 ? $hoursTotalFraction2 - 44 : 0;

								echo "<tr><td></td><td></td><td></td>";
								echo "<td class='text-right'><strong>Total regular hours for the week:<br>" . round($totalRegularWeek, 3) . "</strong></td>";
								echo "<td class='text-right'><strong>Weekly Overtime Hours:<br>" . round($weeklyOvertime, 3) . "</strong></td>";
								echo "</tr>";

								$actualRegularHours2 = $weeklyOvertime > $hoursOvertimeFraction2 ? $totalRegularWeek : $hoursRegularFraction2;
								$actualOvertimeHours2 = $weeklyOvertime > $hoursOvertimeFraction2 ? $weeklyOvertime : $hoursOvertimeFraction2;
							
								echo "<tr><td></td><td></td><td></td>";
								echo "<td class='text-right'>";
								echo "<p class='text-primary'><b>Actual Regular Hours:<br> " . round($actualRegularHours2, 3) . "</b></p>";
								echo "</td>";
								echo "<td class='text-right'>";
								echo "<p class='text-primary'><b>Actual Overtime Hours:<br> " . round($actualOvertimeHours2, 3) . "</b></p>";
								echo "</td>";
								echo "</tr>";
								echo "<tr><td colspan='5'><br></td></tr>";
							}
									$totalWorked = $hoursTotalFraction1 + $hoursTotalFraction2;
									$totalRegularHours = $actualRegularHours1 + $actualRegularHours2;
									$totalOvertimeHours = $actualOvertimeHours1 + $actualOvertimeHours2;
									$bankTimeBalance = 0;
									$bankTimeFlag = "";
									$toBankTime = 0;
									$takeFromBanktime = 0;
									$newBankTimeBalance = 0;

									if($bankTime == 2){
										echo "<tr class='danger text-danger'><td></td>";
										echo "<td class='text-right'><strong>CUT-OFF:</strong></td>";
										echo "<td class='text-right'><strong>Total worked hours:<br>" . round($totalWorked, 3) . "</strong></td>";
										echo "<td class='text-right'><strong>Total regular hours:<br>" . round($totalRegularHours, 3) . "</strong></td>";
										echo "<td class='text-right'><strong>Total overtime hours:<br>" . round($totalOvertimeHours, 3) . "</strong></td>";
										echo "</tr>";
									}elseif($bankTime == 1){

										//bank time info
										$arrParam = array(
											"idUser" => $lista['fk_id_user'],
											"limit" => 1
										);
										$infoBankTime = $this->general_model->get_bank_time($arrParam);

										
										if($infoBankTime){
											$bankTimeBalance = $infoBankTime[0]["balance"];
										}
										//si ya se guardo el paystub entonces tomo esos valores 
										if($infoPaystub){
											$bankTimeBalance = $infoPaystub[0]["actual_bank_time_balance"];
										}

										if($totalWorked >= 88){
											$payHours = 88;
											$toBankTime = $totalWorked - 88;
											$newBankTimeBalance = $bankTimeBalance + $toBankTime;
											$bankTimeFlag = $totalWorked == 88 ? "":"add";
										}else{
											$payHours = $totalWorked;
											if($bankTimeBalance > 0){
												$timeNeeded = 88 - $payHours;
												$bankTimeFlag = "subtract";
												if($bankTimeBalance >= $timeNeeded){
													$newBankTimeBalance = $bankTimeBalance - $timeNeeded;
													$takeFromBanktime = $bankTimeBalance - $newBankTimeBalance;
													$payHours = $payHours + $takeFromBanktime;
												}else{
													$takeFromBanktime = $bankTimeBalance;
													$payHours = $payHours + $bankTimeBalance;
												}
											}
										}

										$totalRegularHours = $payHours;
										$totalOvertimeHours = 0;
										
										echo "<tr class='danger text-danger'><td></td>";
										echo "<td class='text-right'></td>";
										echo "<td class='text-right'></td>";
										echo "<td class='text-right'>Actual Bank Time Balance:</td>";
										echo "<td class='text-right'><strong>" . $bankTimeBalance . "</strong></td>";
										echo "</tr>";

										echo "<tr class='danger text-danger'><td></td>";
										echo "<td class='text-right'><strong>CUT-OFF with BANK TIME:</strong></td>";
										echo "<td class='text-right'><strong>Total worked hours:<br>" . $totalWorked . "</strong></td>";
										echo "<td class='text-right'><strong>Total regular hours:<br>" . $payHours . "</strong></td>";
										if($bankTimeFlag == "add"){
											echo "<td class='text-right'><strong>Add to Bank Time:<br>" . $toBankTime . "</strong></td>";
										}elseif($bankTimeFlag == "subtract"){
											echo "<td class='text-right'><strong>Subtract from Bank Time:<br>" . $takeFromBanktime . "</strong></td>";
										}else{
											echo "<td class='text-right'></td>";
										}
										
										echo "</tr>";

										echo "<tr class='danger text-danger'><td></td>";
										echo "<td class='text-right'></td>";
										echo "<td class='text-right'></td>";
										echo "<td class='text-right'>New Bank Time Balance:</td>";
										echo "<td class='text-right'><strong>" . $newBankTimeBalance . "</strong></td>";
										echo "</tr>";
									}

						?>
						</tbody>
					</table>

					<div class="row">
					<?php 
						$idUser = $lista['fk_id_user'];
						//Cost regular salary 
						$cost_regular_salary = $totalRegularHours * $employeeHourRate;
						//Cost overtime 
						$cost_overtime = $totalOvertimeHours * $employeeHourRate * 1.5;
						//Cost Vacation Regular Salary
						if($employeeType==1){
							$vacation_rate = 0.096;
						}else{
							$vacation_rate = 0.04;
						}
						$cost_vacation = $cost_regular_salary * $vacation_rate;
						//Gross salary
						$gross_salary = $cost_regular_salary + $cost_overtime + $cost_vacation;

					?>
						<div class="col-lg-2">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<i class="fa fa-bell fa-fw"></i> Form
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">

									<form  name="paystub_<?php echo $idUser ?>" id="paystub_<?php echo $idUser; ?>" method="post" action="<?php echo base_url("payroll/save_paystub"); ?>">

										<input type="hidden" id="hddIdPaytsub" name="hddIdPaytsub" value="<?php echo $infoPaystub?$infoPaystub[0]["id_paystub"]:"" ?>" />
										<input type="hidden" id="hddIdTotalYearly" name="hddIdTotalYearly" value="<?php echo $idTotalYear; ?>" />
										<input type="hidden" id="hddIdPeriod" name="hddIdPeriod" value="<?php echo $idPeriod; ?>" />
										<input type="hidden" id="hddYear" name="hddYear" value="<?php echo $infoPeriod[0]['year_period']; ?>" />
										<input type="hidden" id="hddIdWeakPeriod1" name="hddIdWeakPeriod1" value="<?php echo $infoWeakPeriod[0]['id_period_weak']; ?>" />
										<input type="hidden" id="hddIdWeakPeriod2" name="hddIdWeakPeriod2" value="<?php echo $infoWeakPeriod[1]['id_period_weak']; ?>" />
										<input type="hidden" id="hddIdUser" name="hddIdUser" value="<?php echo $idUser; ?>" />

										<input type="hidden" id="hddBankTime" name="hddBankTime" value="<?php echo $bankTime; ?>" />
										<input type="hidden" id="hddBankTimeFlag" name="hddBankTimeFlag" value="<?php echo $bankTimeFlag; ?>" />
										<input type="hidden" id="hddBankTimeBalance" name="hddBankTimeBalance" value="<?php echo $bankTimeBalance; ?>" />
										<input type="hidden" id="hddBankTimeAdd" name="hddBankTimeAdd" value="<?php echo $toBankTime; ?>" />
										<input type="hidden" id="hddBankTimeSubtract" name="hddBankTimeSubtract" value="<?php echo $takeFromBanktime; ?>" />
										<input type="hidden" id="hddBankTimeNewBalance" name="hddBankTimeNewBalance" value="<?php echo $newBankTimeBalance; ?>" />

										<input type="hidden" id="hddEmployeeRate" name="hddEmployeeRate" value="<?php echo $employeeHourRate; ?>" />
										<input type="hidden" id="hddEmployeeType" name="hddEmployeeType" value="<?php echo $employeeType; ?>" />
										
										<input type="hidden" id="hddTotalWorkedHours" name="hddTotalWorkedHours" value="<?php echo $totalWorked; ?>" />
										<input type="hidden" id="hddRegularHours" name="hddRegularHours" value="<?php echo $totalRegularHours; ?>" />
										<input type="hidden" id="hddOvertimeHours" name="hddOvertimeHours" value="<?php echo $totalOvertimeHours; ?>" />
										<input type="hidden" id="hddCostRegularSalary" name="hddCostRegularSalary" value="<?php echo $cost_regular_salary; ?>" />
										<input type="hidden" id="hddCostOvertime" name="hddCostOvertime" value="<?php echo $cost_overtime; ?>" />
										<input type="hidden" id="hddCostVacation" name="hddCostVacation" value="<?php echo $cost_vacation; ?>" />
										<input type="hidden" id="hddFGrossSalary" name="hddFGrossSalary" value="<?php echo $gross_salary; ?>" />

										<!-- campos ocultos para redireccionar al listado inicial buscado -->
										<input type="hidden" id="contractType" name="contractType" value="<?php echo $contractType; ?>" />
										<input type="hidden" id="period" name="period" value="<?php echo $idPeriod; ?>" />
										<input type="hidden" id="employee" name="employee" value="<?php echo $idEmployee; ?>" />

										<div class="form-group text-left">
											<label class="control-label" for="firstName">EE CPP: *</label>
											<input type="text" id="ee_cpp" name="ee_cpp" value="<?php echo $infoPaystub?$infoPaystub[0]['ee_cpp']:""; ?>" class="form-control" placeholder="EE CPP" required >
										</div>

										<div class="form-group text-left">
											<label class="control-label" for="firstName">EE EI: *</label>
											<input type="text" id="ee_ei" name="ee_ei" value="<?php echo $infoPaystub?$infoPaystub[0]['ee_ei']:""; ?>" class="form-control" placeholder="EE EI" required >
										</div>

										<div class="form-group text-left">
											<label class="control-label" for="firstName">TAX: *</label>
											<input type="text" id="tax" name="tax" value="<?php echo $infoPaystub?$infoPaystub[0]['tax']:""; ?>" class="form-control" placeholder="TAX" required >
										</div>

										<div class="form-group text-left">
											<label class="control-label" for="firstName">GWL Deductions: *</label>
											<input type="text" id="gwl_deductions" value="<?php echo $infoPaystub?$infoPaystub[0]['gwl_deductions']:""; ?>" name="gwl_deductions" class="form-control" placeholder="GWL Deductions" required >
										</div>

									<?php 
										$deshabilitar = "";
										if($infoPaystub && $infoPaystub[0]["commit"] == 1){
											$deshabilitar = "disabled";
										}
									?>
										<div class="form-group">
											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-xs btn-primary" value="apply" <?php echo $deshabilitar; ?> >
												Apply <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
											</button> 
											<?php if($infoPaystub){ ?>
												<button type="submit" id="btnGenerate" name="btnGenerate" class="btn btn-xs btn-primary" value="generate" <?php echo $deshabilitar; ?> >
													Generate Paystub <span class="fa fa-check-square-o" aria-hidden="true"></span>
												</button> 
											<?php } ?>
											
											<?php if($infoPaystub && $infoPaystub[0]["commit"] == 1){ ?>
												<a class="btn btn-xs btn-primary" href='<?php echo base_url('payroll/generaPaystubPDF/' . $infoPaystub[0]["id_paystub"] ); ?>' title="Download Paystub" target="_blank"> 
													<span class="fa fa-cloud-download" aria-hidden="true"></span>
												</a>
											<?php } ?>
										</div>

									</form>
								</div>
							</div>
						</div>

					<?php 
						if($infoPaystub){
					?>
						<div class="col-lg-3">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<i class="fa fa-bell fa-fw"></i> More info
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									<div class="list-group">

										<p class="text-default">
											<span class="text-muted small"><em><strong> Reviewed by: </strong> <?php echo $infoPaystub[0]['name']; ?></em>
											</span><br>
											<span class="text-muted small"><em><strong> Revised date: </strong> <?php echo $infoPaystub[0]['paystub_date_issue']; ?></em>
											</span>
										</p>

										<table width="100%" class="table table-hover" id="dataTables">
											<thead>
												<tr>
													<th><small>TAXES</small></th>
													<th class='text-right'><small>Current</small></th>
												</tr>
											</thead>
											<tbody>
											<?php
												$ee_cpp = $infoPaystub[0]['ee_cpp']?$infoPaystub[0]['ee_cpp']:0;
												$er_cpp = $infoPaystub[0]['er_cpp']?$infoPaystub[0]['er_cpp']:0;
												$ee_ei = $infoPaystub[0]['ee_ei']?$infoPaystub[0]['ee_ei']:0;
												$er_ei = $infoPaystub[0]['er_ei']?$infoPaystub[0]['er_ei']:0;
												$tax = $infoPaystub[0]['tax']?$infoPaystub[0]['tax']:0;
												$gwl_deductions = $infoPaystub[0]['gwl_deductions']?$infoPaystub[0]['gwl_deductions']:0;
												$remittance = $infoPaystub[0]['remittance']?$infoPaystub[0]['remittance']:0;
												$net_pay = $infoPaystub[0]['net_pay']?$infoPaystub[0]['net_pay']:0;

												echo "<tr>";
												echo "<td><small>EE CPP</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['ee_cpp'], 2) . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>ER CPP</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['er_cpp'], 2) . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>EE EI</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['ee_ei'], 2) . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>* ER EI</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['er_ei'], 2) . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>TAX</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['tax'], 2) . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>GWL Deductions</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['gwl_deductions'], 2) . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>** Remittance</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['remittance'], 2) . "</small></td>";
												echo "</tr>";
											?>
											</tbody>
										</table>

										<p class="text-default">
											<span class="text-muted small"><em>* ER EI = EE EI x 1.4</em></span><br>
											<span class="text-muted small"><em>** Remitttance = (EE CPP + ER CPP + EE EI + ER EI + TAX)</em></span>
										</p>
									</div>
								</div>
							</div>
						</div>
				<?php
					}
				?>

						<div class="col-lg-4">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<i class="fa fa-bell fa-fw"></i> Pay Stub Detail
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									<div class="list-group">
										<p class="text-default">
											<span class="text-muted small"><em><strong> Employee: </strong> <?php echo $employeeName; ?></em>
											</span><br>
											<span class="text-muted small"><em><strong> Period Beginning: </strong> <?php echo $infoPeriod[0]["date_start"]; ?></em>
											</span><br>
											<span class="text-muted small"><em><strong> Period Ending: </strong> <?php echo $infoPeriod[0]["date_finish"]; ?></em>
											</span>
										</p>
										<table width="100%" class="table table-hover" id="dataTables">
											<thead>
												<tr>
													<th><small>PAY</small></th>
													<th class='text-center'><small>Hours</small></th>
													<th class='text-center'><small>Rate</small></th>
													<th class='text-right'><small>Current</small></th>
													<th class='text-right'><small>YTD</small></th>
												</tr>
											</thead>
											<tbody>
											<?php
												$year_cost_regular_salary = 0;
												$year_cost_overtime = 0;
												$year_cost_vacation = 0;
												if(!$infoPaystub){
													if($infoTotalYear){
														$year_cost_regular_salary = $cost_regular_salary + $infoTotalYear[0]['total_year_cost_regular_salary'];
														$year_cost_overtime = $cost_overtime + $infoTotalYear[0]['total_year_cost_over_time'];
														$year_cost_vacation = $cost_vacation + $infoTotalYear[0]['total_year_cost_vacation_regular_salary'];
													}else{
														$year_cost_regular_salary = $cost_regular_salary;
														$year_cost_overtime = $cost_overtime;
														$year_cost_vacation = $cost_vacation;
													}
												}else{
													if($infoTotalYear){
														$year_cost_regular_salary = $infoTotalYear[0]['total_year_cost_regular_salary'];
														$year_cost_overtime = $infoTotalYear[0]['total_year_cost_over_time'];
														$year_cost_vacation = $infoTotalYear[0]['total_year_cost_vacation_regular_salary'];
													}
												}
												echo "<tr>";
												echo "<td><small>Regular Pay</small></td>";
												echo "<td class='text-center'><small>" . round($totalRegularHours, 3) . "</small></td>";
												echo "<td class='text-center'><small>" . $employeeHourRate . "</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($cost_regular_salary, 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($year_cost_regular_salary, 2) . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>Overtime Pay</small></td>";
												echo "<td class='text-center'><small>" . round($totalOvertimeHours, 3) . "</small></td>";
												echo "<td class='text-center'><small>" . $employeeHourRate * 1.5 . "</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($cost_overtime, 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($year_cost_overtime, 2) . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>Vacation Pay</small></td>";
												echo "<td class='text-center'></td>";
												echo "<td class='text-center'></td>";
												echo "<td class='text-right'><small>$ " . number_format($cost_vacation, 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($year_cost_vacation, 2) . "</small></td>";
												echo "</tr>";
											?>
											</tbody>
										</table>
				<?php
					if($infoPaystub){
				?>
										<table width="100%" class="table table-hover" id="dataTables">
											<thead>
												<tr>
													<th><small>DEDUCTIONS</small></th>
													<th class='text-right'><small>Current</small></th>
													<th class='text-right'><small>YTD</small></th>
												</tr>
											</thead>
											<tbody>
											<?php
												$totalYearGWL = $infoTotalYear ? number_format($infoTotalYear[0]['total_year_gwl_deductions'], 2) : 0;
												echo "<tr>";
												echo "<td><small>GWL Deductions</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['gwl_deductions'], 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . $totalYearGWL . "</small></td>";
												echo "</tr>";
											?>
											</tbody>
										</table>

										<table width="100%" class="table table-hover" id="dataTables">
											<thead>
												<tr>
													<th><small>TAXES</small></th>
													<th class='text-right'><small>Current</small></th>
													<th class='text-right'><small>YTD</small></th>
												</tr>
											</thead>
											<tbody>
											<?php
												$totalYearTAX = $infoTotalYear ? number_format($infoTotalYear[0]['total_year_tax'], 2) : 0;
												$totalYearEI = $infoTotalYear ? number_format($infoTotalYear[0]['total_year_ee_ei'], 2) : 0;
												$totalYearCPP = $infoTotalYear ? number_format($infoTotalYear[0]['total_year_ee_cpp'], 2) : 0;
												echo "<tr>";
												echo "<td><small>Income Tax</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['tax'], 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . $totalYearTAX . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>Employment Insurance</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['ee_ei'], 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . $totalYearEI . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>Canada Pension Plan</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['ee_cpp'], 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . $totalYearCPP . "</small></td>";
												echo "</tr>";
											?>
											</tbody>
										</table>
				<?php
					}
				?>
									</div>
								</div>
							</div>
						</div>

				<?php
					if($infoPaystub){
				?>
						<div class="col-lg-3">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<i class="fa fa-bell fa-fw"></i> Summary
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									<div class="list-group">

										<table width="100%" class="table table-hover" id="dataTables">
											<thead>
												<tr>
													<th><small>SUMMARY</small></th>
													<th class='text-right'><small>Current</small></th>
													<th class='text-right'><small>YTD</small></th>
												</tr>
											</thead>
											<tbody>
											<?php
												$totalYearGrossSalary = $infoTotalYear ? number_format($infoTotalYear[0]['total_year_gross_salary'], 2) : 0;
												$totalYearTotalTaxes = $infoTotalYear ? number_format($infoTotalYear[0]['total_year_ee_total_taxes'], 2) : 0;
												echo "<tr>";
												echo "<td><small>Total Pay</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['gross_salary'], 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . $totalYearGrossSalary . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>Taxes</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['ee_total_taxes'], 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . $totalYearTotalTaxes . "</small></td>";
												echo "</tr>";

												echo "<tr>";
												echo "<td><small>Deductions</small></td>";
												echo "<td class='text-right'><small>$ " . number_format($infoPaystub[0]['gwl_deductions'], 2) . "</small></td>";
												echo "<td class='text-right'><small>$ " . $totalYearGWL . "</small></td>";
												echo "</tr>";


												echo "<tr>";
												echo "<td><strong>Net Pay</strong></td>";
												echo "<td class='text-right'><strong>$ " . number_format($infoPaystub[0]['net_pay'], 2) . "</strong></td>";
												echo "<td class='text-right'></td>";
												echo "</tr>";
											?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
				<?php
					}
				?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
			endforeach;
	 	}	
	?>
</div>

<!--INICIO Modal cambio de hora-->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal-->

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"ordering": false,
		"pageLength": 50
	});
});
</script>