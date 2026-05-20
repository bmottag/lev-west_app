<div id="page-wrapper">
	<br>
	<?php
	if (!$info) {
	?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<a class="btn btn-warning btn-xs" href=" <?php echo base_url() . 'payroll/payrollSearchTimeSheet'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
						<i class="fa fa-clock-o fa-fw"></i> <b>PAYROLL REPORT - DAILY TIME SHEET </b>
						<br><b>Daterange: </b> <?php echo $from . '-' . $to; ?>
					</div>

					<div class="panel-body">
						<div class="alert alert-warning">
							No data was found matching your criteria.
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	} else {

		foreach ($info as $lista) :
			$employeeName = $lista['employee_name'];
			$infoPayrollUser = $lista['tasks'];
		?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-warning">
						<div class="panel-heading">
							<div class="row">
								<div class="col-lg-12">
									<a class="btn btn-warning btn-xs" href=" <?php echo base_url() . 'payroll/payrollSearchTimeSheet'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
									<i class="fa fa-clock-o fa-fw"></i> <b>PAYROLL REPORT - DAILY TIME SHEET</b>
									<br><b>Daterange: </b> <?php echo $from . '-' . $to; ?>
								</div>
							</div>
						</div>

						<div class="panel-body">
							<div class="alert alert-default">
								<h2><i class="fa fa-user"></i> <b>Employee: </b> <?php echo $employeeName; ?></h2>
							</div>
							<table width="100%" class="table table-hover" id="dataTables">
								<thead>
									<tr>
										<th class='text-left'><small>Date & Time</small></th>
										<th class='text-left'><small>Job</small></th>
										<th class='text-left'><small>Address</small></th>
										<th class='text-left'><small>Task Description</small></th>
										<th class='text-left'><small>Observation</small></th>
										<th class='text-right'><small>Working Hours <small>(HH:MM)<small></small></th>
										<th class='text-right'><small>Regular Hours <small>(HH:MM)<small></small></th>
										<th class='text-right'><small>Overtime Hours <small>(HH:MM)<small></small></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$totalHours = 0;
									$totalHoursNew = 0;
									$totalRegularNew = 0;
									$totalOvertimeNew = 0;
									if ($infoPayrollUser) {
										$totalRegular = 0;
										$totalOvertime = 0;
										$totalRegularWeek = 0;
										$weeklyOvertime = 0;
										foreach ($infoPayrollUser as $lista) :
											echo "<tr>";
											echo "<td><small><strong>Start:</strong><br>" . date('M j, Y - G:i', strtotime($lista['start'])) . "<br><strong>Finish:</strong><br>" . date('M j, Y - G:i', strtotime($lista['finish'])) . "</small><br>";
											/*
											SE ELIMINO ESTA OPCION PORQUE SE PASO PARA EL CALENDARIO DESDE FEB 25
									?>
											<a href="<?php echo base_url("report/botonEditHour/" . $lista['id_task']); ?>" class="btn btn-info btn-xs">
												Edit Hours <span class="glyphicon glyphicon-edit" aria-hidden="true">
											</a>
									<?php
											*/
											echo "</td>";
											echo "<td><small><strong>Start:</strong><br>" . $lista['job_start'] . "<br><strong>Finish:</strong><br>" . $lista['job_finish'] . "</small></td>";
											echo "<td><small><strong>Start:</strong><br>" . $lista['address_start'] . "<br><strong>Finish:</strong><br>" . $lista['address_finish'] . "</small></td>";
											echo "<td class='text-left'><small>" . $lista['task_description'] . "</small></td>";
											echo "<td class='text-left'><small>" . $lista['observation'] . "</small></td>";
											echo "<td class='text-right'><small>" . substr($lista['working_hours_new'], 0, 5) . "</small></td>";
											echo "<td class='text-right'><small>" . substr($lista['regular_hours_new'], 0, 5) . "</small></td>";
											echo "<td class='text-right'><small>" . substr($lista['overtime_hours_new'], 0, 5) . "</small></td>";
											echo "</tr>";
											$totalHours = $lista['working_hours'] + $totalHours;
											$totalRegular = $lista['regular_hours'] + $totalRegular;
											$totalOvertime = $lista['overtime_hours'] + $totalOvertime;

											if($lista['working_hours_new']){
												$partsTotal = explode(':', $lista['working_hours_new']);
												$totalHoursNew += ($partsTotal[0] * 3600) + ($partsTotal[1] * 60);
											}

											if($lista['regular_hours_new']){
												$partsRegular = explode(':', $lista['regular_hours_new']);
												$totalRegularNew += ($partsRegular[0] * 3600) + ($partsRegular[1] * 60);
											}

											if($lista['overtime_hours_new']){
												$partsOvertime = explode(':', $lista['overtime_hours_new']);
												$totalOvertimeNew += ($partsOvertime[0] * 3600) + ($partsOvertime[1] * 60);
											}

$userRol = session()->get('rol');
if ($userRol == ID_ROL_SUPER_ADMIN) {


											$hoursTotalTEST = floor($totalHoursNew / 3600);
											$minsTotalTEST = floor(($totalHoursNew / 60) % 60);
	
											$hoursRegularTEST = floor($totalRegularNew / 3600);
											$minsRegularTEST = floor(($totalRegularNew / 60) % 60);
	
											$hoursOvertimeTEST = floor($totalOvertimeNew / 3600);
											$minsOvertimeTEST = floor(($totalOvertimeNew / 60) % 60);
											echo "<tr>";
											echo "<td class='text-right' colspan='6'><small><strong>Total weekly hours:<br>" . sprintf('%02d:%02d', $hoursTotalTEST, $minsTotalTEST)  . "</strong></small></td>";
											echo "<td class='text-right'><small><strong>Total daily regular hours per week:<br>" . sprintf('%02d:%02d', $hoursRegularTEST, $minsRegularTEST) . "</strong></small></td>";
											echo "<td class='text-right'><small><strong>Total daily overtime per week:<br>" . sprintf('%02d:%02d', $hoursOvertimeTEST, $minsOvertimeTEST) . "</strong></small></td>";
											echo "</tr>";
}


										endforeach;
										$hoursTotal = floor($totalHoursNew / 3600);
										$minsTotal = floor(($totalHoursNew / 60) % 60);

										$hoursRegular = floor($totalRegularNew / 3600);
										$minsRegular = floor(($totalRegularNew / 60) % 60);

										$hoursOvertime = floor($totalOvertimeNew / 3600);
										$minsOvertime = floor(($totalOvertimeNew / 60) % 60);
										echo "<tr>";
										echo "<td class='text-right' colspan='6'><small><strong>Total weekly hours:<br>" . sprintf('%02d:%02d', $hoursTotal, $minsTotal)  . "</strong></small></td>";
										echo "<td class='text-right'><small><strong>Total daily  regular hours per week:<br>" . sprintf('%02d:%02d', $hoursRegular, $minsRegular) . "</strong></small></td>";
										echo "<td class='text-right'><small><strong>Total daily overtime per week:<br>" . sprintf('%02d:%02d', $hoursOvertime, $minsOvertime) . "</strong></small></td>";
										echo "</tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	<?php
		endforeach;
	}
	?>
</div>

<!-- Tables -->
<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false
		});
	});
</script>