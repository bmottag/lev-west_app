<script>
	$(document).ready(function() {

		$(".btn-confirm").click(function() {
			var oID = $(this).attr("id");
			var date = $(this).attr("msnDate");
			var time = $(this).attr("msnTime");

			//Activa icono guardando
			if (window.confirm('I confirm the scheduling for: ' + date + " at " + time + ".")) {
				$(".btn-confirm").attr('disabled', '-1');
				$.ajax({
					type: 'POST',
					url: base_url + 'dashboard/confirmPlanning',
					data: {
						'identificador': oID
					},
					cache: false,
					success: function(data) {

						if (data.status === "success") {
								window.location.href = base_url + data.dashboardURL;
						} else {
							alert('Error. Reload the web page.');
							$(".btn-confirm").removeAttr('disabled');
						}
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$(".btn-confirm").removeAttr('disabled');
					}

				});
			}
		});
	});
</script>

<a name="anclaUp"></a>

<div id="page-wrapper">
	<div class="row"><br>
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						DASHBOARD
					</h4>
				</div>
			</div>
		</div>
	</div>

	<?php
	if ($infoMaintenance) {
	?>
		<div class="row">
			<div class="col-lg-12">
				<a class="btn btn-block btn-social btn-pinterest" href="<?php echo base_url('dashboard/maintenance'); ?>">
					<i class="fa fa-wrench"></i> There are <b>Preventive Maintenance</b> that must be attended to as soon as possible.
				</a>
				<br>
			</div>
		</div>
	<?php
	}
	?>

	<div class="row">
		<!-- INICIO MENSAJE DEL SISTEMA si aprobaron un dayoff en los ultimos 7 dias -->
		<?php if ($dayoff) { ?>
			<div class="col-lg-12">
				<?php
				switch ($dayoff['state']) {
					case 2:
						$noteDayoff = ' was <strong>Approved.</strong>';
						$classDayoff = "alert-success";
						break;
					case 3:
						$noteDayoff = ' was <strong>Denied.</strong>';
						$classDayoff = "alert-danger";
						break;
				}
				?>
				<div class="alert <?php echo $classDayoff; ?> alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>DAY OFF: </strong>
					<?php
					echo "The day off you requested for the ";
					echo "<strong>" . $dayoff['date_dayoff'] . "</strong>";
					echo $noteDayoff;
					echo " You can check your request in the Day Off link."
					?>
				</div>
			</div>
		<?php } ?>
		<!-- FIN MENSAJE DEL SISTEMA-->
	</div>

    <?php
    $session = session();

    // Mensaje de éxito
    if ($session->getFlashdata('retornoExito')): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    <strong><?= $session->get('firstname') ?></strong>
                    <?= $session->getFlashdata('retornoExito') ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Mensaje de error -->
    <?php if ($session->getFlashdata('retornoError')): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <?= $session->getFlashdata('retornoError') ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

	<!-- Enlace internos -->
	<div class="row">
		<div class="col-lg-3 col-md-6">
			<a class="btn btn-block btn-social btn-primary" href="#anclaPayroll">
				<i class="fa fa-book"></i> <b> Last Payroll Records </b>
			</a>
		</div>

		<?php if ($infoSafety) {  ?>
			<div class="col-lg-3 col-md-6">
				<a class="btn btn-block btn-social btn-info" href="#anclaSafety">
					<i class="fa fa-life-saver"></i> <b> Last FLHA Records </b>
				</a>
			</div>
		<?php } ?>

		<?php if ($noJobs) {  ?>
			<div class="col-lg-3 col-md-6">
				<a class="btn btn-block btn-social btn-info" href="<?php echo base_url('jobs'); ?>">
					<i class="fa fa-life-saver"></i> <b> Jobs - Safety</b>
				</a>
			</div>
		<?php } ?>

		<?php if ($noHauling) {  ?>
			<div class="col-lg-3 col-md-6">
				<a class="btn btn-block btn-social btn-warning" href="<?php echo base_url('dashboard/hauling'); ?>">
					<i class="fa fa-truck"></i> <b> Last Hauling Records </b>
				</a>
			</div>
		<?php } ?>

	</div>
	<br>
	<div class="row">
		<?php if ($noDailyInspection) {  ?>
			<div class="col-lg-3 col-md-6">
				<a class="btn btn-block btn-social btn-success" href="<?php echo base_url('dashboard/pickups_inspection'); ?>">
					<i class="fa fa-search"></i> <b> Last Pickups & Trucks Inspections</b>
				</a>
			</div>
		<?php } ?>

		<?php if ($noHeavyInspection) {  ?>
			<div class="col-lg-3 col-md-6">
				<a class="btn btn-block btn-social btn-danger" href="<?php echo base_url('dashboard/construction_equipment_inspection'); ?>">
					<i class="fa fa-search"></i> <b> Last Construction Equipment Inspections</b>
				</a>
			</div>
		<?php } ?>

		<div class="col-lg-3 col-md-6">
			<a class="btn btn-block btn-social btn-purpura" href="#anclaWatertruck">
				<i class="fa fa-search"></i> <b> Last Special Equipment Inspections</b>
			</a>
		</div>

		<div class="col-lg-3 col-md-6">
			<a class="btn btn-block btn-social btn-warning" href="<?php echo base_url('dashboard/trailers'); ?>">
				<i class="fa fa-car"></i> <b> Trailers Inspections </b>
			</a>
			<br>
		</div>
	</div>

	<!-- PLANNING -->
	<?php if ($infoNextPlanning) { ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-violeta">
					<div class="panel-heading">
						<i class="fa fa-list fa-fw"></i> <strong>Planning Records</strong>
					</div>
					<div class="panel-body">
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTablesPlanning">
							<thead>
								<tr>
									<th>Date</th>
									<th>Job Code/Name</th>
									<th>Observation</th>
									<th>Message</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($infoNextPlanning as $data): ?>
									<tr>
										<td class="text-center" width="15%">
											<?= date('l, M j, Y', strtotime($data['date_programming'])) ?>
										</td>

										<td width="25%">
											<?= esc($data['job_description']) ?>
										</td>

										<td width="35%">
											<?= esc($data['observation']) ?>
										</td>

										<td width="25%">
											<?php
											$mensaje = "";

											if (!empty($data['workers'])) {
												foreach ($data['workers'] as $worker):

													switch ($worker['site']) {
														case 1: $mensaje .= "At the yard - "; break;
														case 2: $mensaje .= "At the site - "; break;
														case 3: $mensaje .= "At Terminal - "; break;
														case 4: $mensaje .= "On-line training - "; break;
														case 5: $mensaje .= "At training facility - "; break;
														case 6: $mensaje .= "At client's office - "; break;
														default: $mensaje .= "At the yard - "; break;
													}

													$mensaje .= $worker['hora'];

													$mensaje .= "<br><b>" . esc($worker['name']) . "</b>";

													if (!empty($worker['description'])) {
														$mensaje .= "<br>" . esc($worker['description']);
													}

													// 👇 ahora usas lo que ya viene del controlador
													if (!empty($worker['vehicles']['unit_description'])) {
														$mensaje .= "<br>" . $worker['vehicles']['unit_description'];
													}

											if ($worker['safety'] == 1) {
												$mensaje .= "<br>FLHA has being assigned to you.";
											} elseif ($worker['safety'] == 2) {
												$mensaje .= "<br>IHSR has being assigned to you.";
											} elseif ($worker['safety'] == 3) {
												$mensaje .= "<br>JSO has being assigned to you.";
											}
											if ($worker['creat_wo'] == 1) {
												$mensaje .= "<br>You are in charge of the <a href='" . base_url('workorders/add_workorder/' . $data['fk_id_workorder']) . "'>W.O. # " . $data['fk_id_workorder'] . "</a>";
											}
											$mensaje .= $worker['confirmation'] == 1 ? "<p class='text-success'><b>Confirmed?</b> Yes</p>" : "<p class='text-danger'><b>Confirmed?</b> No</p>";
										endforeach;
									}

									echo $mensaje;

									echo "</td>";
									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<!-- FIN PLANNING-->

	<!-- OWN PLANNING -->
	<?php if ($infoPlanning) { ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-violeta">
					<div class="panel-heading">
						<i class="fa fa-book fa-fw"></i> <b>Your work schedule is as follows:</b>
					</div>
					<div class="panel-body">
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTablesPlanning">
							<thead>
								<tr>
									<th>Date</th>
									<th>Job Code/Name</th>
									<th>Observation</th>
									<th>Time In</th>
									<th>Site</th>
									<th>Description</th>
									<th>FLHA/IHSR</th>
									<th>Equipment</th>
									<th>Confirmed?</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($infoPlanning as $data) :
									$site = "";
									switch ($data['site']) {
										case 1:
											$site = "At the yard";
											break;
										case 2:
											$site = "At the site";
											break;
										case 3:
											$site = "At Terminal";
											break;
										case 4:
											$site = "On-line training";
											break;
										case 5:
											$site = "At training facility";
											break;
										case 6:
											$site = "At client's office";
											break;
										default:
											$site = "At the yard";
											break;
									}

									$safety = $data['safety'] == 1 ? "FLHA has being assigned to you" : ($data['safety'] == 2 ? "IHSR has being assigned to you" : "");

									if ($data['confirmation'] == 1) {
										$confirmation = "<p class='text-success'><b>Confirmed?</b> Yes</p>";
									} else {
										$confirmation = '<button type="button" id="' . $data['id_programming_worker'] . '" class="btn btn-danger btn-confirm btn-xs" msnDate="' . $data['date_programming'] . '" msnTime="' . $data['hora'] . '">
																<b>Confirmed?</b> No
														</button>';
									}

									echo "<tr>";
									echo "<td>" . date('M j, Y', strtotime($data['date_programming'])) . "</td>";
									echo "<td>" . $data['job_description'] . "</td>";
									echo "<td >" . $data['observation'] . "</td>";
									echo "<td class='text-center'>" . $data['hora'] . "</td>";
									echo "<td class='text-center'>" . $site . "</td>";
									echo "<td >" . $data['description'] . "</td>";
									echo "<td >" . $safety . "</td>";
									echo "<td class='text-right'>" . $data['unit_description'] . "</td>";
									echo "<td class='text-center'>" . $confirmation . "</td>";
									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<!-- FIN PLANNING-->

	<div class="row">

		<a name="anclaPayroll"></a>
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-book fa-fw"></i> <b>Last Payroll Records</b>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">


					<a class="btn btn-default btn-circle" href="#anclaUp"><i class="fa fa-arrow-up"></i> </a>


					<?php
					if (!$info) {
						echo "<a href='#' class='btn btn-danger btn-block'>No data was found matching your criteria</a>";
					} else {
					?>

						<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
							<thead>
								<tr>
									<th>Employee</th>
									<th>Start</th>
									<th>Finish</th>
									<th>Working Hours <small>(HH:MM)</small></th>
									<th>Job Start</th>
									<th>Address Start</th>
									<th>Job Finish</th>
									<th>Address Finish</th>
									<th>Task Description</th>
									<th>Observation</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($info as $lista) :
									$finish = $lista['finish'] == "0000-00-00 00:00:00"?"":date('M j, Y - G:i', strtotime($lista['finish']));
									$workingHours = $lista['finish'] == "0000-00-00 00:00:00"?"": substr($lista['working_hours_new'], 0, 5);
									echo "<tr>";
									echo "<td>" . $lista['first_name'] . " " . $lista['last_name'] . "</td>";
									echo "<td class='text-center'>" . date('M j, Y - G:i', strtotime($lista['start'])) . "</td>";
									echo "<td class='text-center'>" . $finish . "</td>";
									echo "<td class='text-right'>" . $workingHours . "</td>";
									echo "<td>" . $lista['job_start'] . "</td>";
									echo "<td>" . $lista['address_start'] . "</td>";
									echo "<td>" . $lista['job_finish'] . "</td>";
									echo "<td>" . $lista['address_finish'] . "</td>";
									echo "<td>" . $lista['task_description'] . "</td>";
									echo "<td>" . $lista['observation'] . "</td>";
									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>

						<a href="<?php echo base_url("report/searchByDateRange/payroll"); ?>" class="btn btn-outline btn-default btn-block">
							View more own records <span class="fa fa-book" aria-hidden="true">
						</a>
						<?php
						$userBankTime = $session->get('bankTime');
						if ($userBankTime == 1) {
						?>
							<a href="<?php echo base_url("report/employeBankTime"); ?>" class="btn btn-outline btn-info btn-block">
								View Bank Time records <span class="fa fa-flag" aria-hidden="true">
							</a>
						<?php   } ?>
					<?php	} ?>
				</div>
				<!-- /.panel-body -->
			</div>

		</div>

	</div>






	<div class="row">
		<div class="col-lg-6">

			<!-- INICIO TABLA DE SPECIAL INSPECTION --WATER TRUCK -->
			<?php if ($infoWaterTruck) {  ?>
				<a name="anclaWatertruck"></a>
				<div class="panel panel-purpura">
					<div class="panel-heading">
						<i class="fa fa-search fa-fw"></i> Last Water Truck Inspection Records
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">

						<a class="btn btn-default btn-circle" href="#anclaUp"><i class="fa fa-arrow-up"></i> </a>

						<table width="100%" class="table table-striped table-bordered table-hover" id="dataWatertruck">
							<thead>
								<tr>
									<th>Date & Time</th>
									<th>Driver Name</th>
									<th>Vehicle Make</th>
									<th>Vehicle Model</th>
									<th>Unit Number</th>
									<th>Download</th>
									<th>Description</th>
									<th>Comments</th>
								</tr>
							</thead>
							<tbody>
								<?php

								if ($infoWaterTruck) {
									foreach ($infoWaterTruck as $lista) :

										$class = "";

										//si hay errores se coloca en amarillo
										if (
											['belt'] == 0
											|| ['power_steering'] == 0
											|| ['oil_level'] == 0
											|| ['coolant_level'] == 0
											|| ['coolant_leaks'] == 0
											|| ['head_lamps'] == 0
											|| ['hazard_lights'] == 0
											|| ['clearance_lights'] == 0
											|| ['tail_lights'] == 0
											|| ['work_lights'] == 0
											|| ['turn_signals'] == 0
											|| ['beacon_lights'] == 0
											|| ['tires'] == 0
											|| ['mirrors'] == 0
											|| ['clean_exterior'] == 0
											|| ['wipers'] == 0
											|| ['backup_beeper'] == 0
											|| ['door'] == 0
											|| ['decals'] == 0
											|| ['sprinkelrs'] == 0
											|| ['stering_axle'] == 0
											|| ['drives_axles'] == 0
											|| ['front_drive'] == 0
											|| ['back_drive'] == 0
											|| ['water_pump'] == 0
											|| ['brake'] == 0
											|| ['emergency_brake'] == 0
											|| ['gauges'] == 0
											|| ['horn'] == 0
											|| ['seatbelt'] == 0
											|| ['seat'] == 0
											|| ['insurance'] == 0
											|| ['registration'] == 0
											|| ['clean_interior'] == 0
											|| ['fire_extinguisher'] == 0
											|| ['first_aid'] == 0
											|| ['emergency_kit'] == 0
											|| ['spill_kit'] == 0
											|| ['heater'] == 0
											|| ['steering_wheel'] == 0
											|| ['suspension_system'] == 0
											|| ['air_brake'] == 0
											|| ['fuel_system'] == 0
										) {
											$class = "warning";
										}

										//si hay comentarios se coloca en rojo
										if ($lista['comments'] != '') {
											$class = "danger";
										}

										echo "<tr class='" . $class . "'>";
										echo "<td><p class='text-" . $class . "'>" . date('M j, Y - G:i:s', strtotime($lista['date_issue'])) . "</p></td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
										echo "<td class='text-center'>";
								?>
										<a href='<?php echo base_url('report/generaInsectionSpecialPDF/x/x/x/x/watertruck/' . $lista['id_inspection_watertruck']); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>'></a>
								<?php
										echo "</td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
										echo "</tr>";
									endforeach;
								}

								?>
							</tbody>
						</table>
					</div>
					<!-- /.panel-body -->
				</div>
			<?php	} ?>
			<!-- FIN TABLA DE SPECIAL INSPECTION -->

			<!-- INICIO TABLA DE SPECIAL INSPECTION -- HYDRO-VAC -->
			<?php if ($infoHydrovac) {  ?>
				<a name="anclaHydrovac"></a>
				<div class="panel panel-purpura">
					<div class="panel-heading">
						<i class="fa fa-search fa-fw"></i> Last Hydro-Vac Inspection Records
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">

						<a class="btn btn-default btn-circle" href="#anclaUp"><i class="fa fa-arrow-up"></i> </a>

						<table width="100%" class="table table-striped table-bordered table-hover" id="dataHydrovac">
							<thead>
								<tr>
									<th>Date & Time</th>
									<th>Driver Name</th>
									<th>Vehicle Make</th>
									<th>Vehicle Model</th>
									<th>Unit Number</th>
									<th>Download</th>
									<th>Description</th>
									<th>Comments</th>
								</tr>
							</thead>
							<tbody>
								<?php

								if ($infoHydrovac) {
									foreach ($infoHydrovac as $lista) :

										$class = "";

										//si hay errores se coloca en amarillo
										if (
											['belt'] == 0
											|| ['power_steering'] == 0
											|| ['oil_level'] == 0
											|| ['coolant_level'] == 0
											|| ['coolant_leaks'] == 0
											|| ['head_lamps'] == 0
											|| ['hazard_lights'] == 0
											|| ['clearance_lights'] == 0
											|| ['tail_lights'] == 0
											|| ['work_lights'] == 0
											|| ['turn_signals'] == 0
											|| ['beacon_lights'] == 0
											|| ['tires'] == 0
											|| ['windows'] == 0
											|| ['clean_exterior'] == 0
											|| ['wipers'] == 0
											|| ['backup_beeper'] == 0
											|| ['door'] == 0
											|| ['decals'] == 0
											|| ['stering_wheels'] == 0
											|| ['drives'] == 0
											|| ['front_drive'] == 0
											|| ['middle_drive'] == 0
											|| ['back_drive'] == 0
											|| ['transfer'] == 0
											|| ['tail_gate'] == 0
											|| ['boom'] == 0
											|| ['lock_bar'] == 0
											|| ['brake'] == 0
											|| ['emergency_brake'] == 0
											|| ['gauges'] == 0
											|| ['horn'] == 0
											|| ['seatbelt'] == 0
											|| ['seat'] == 0
											|| ['insurance'] == 0
											|| ['registration'] == 0
											|| ['clean_interior'] == 0
											|| ['fire_extinguisher'] == 0
											|| ['first_aid'] == 0
											|| ['emergency_kit'] == 0
											|| ['spill_kit'] == 0
											|| ['cartige'] == 0
											|| ['pump'] == 0
											|| ['wash_hose'] == 0
											|| ['pressure_hose'] == 0
											|| ['pump_oil'] == 0
											|| ['hydraulic_oil'] == 0
											|| ['gear_case'] == 0
											|| ['hydraulic'] == 0
											|| ['control'] == 0
											|| ['panel'] == 0
											|| ['foam'] == 0
											|| ['heater'] == 0
											|| ['steering_wheel'] == 0
											|| ['suspension_system'] == 0
											|| ['air_brake'] == 0
											|| ['fuel_system'] == 0
										) {
											$class = "warning";
										}

										//si hay comentarios se coloca en rojo
										if ($lista['comments'] != '') {
											$class = "danger";
										}

										echo "<tr class='" . $class . "'>";
										echo "<td><p class='text-" . $class . "'>" . date('M j, Y - G:i:s', strtotime($lista['date_issue'])) . "</p></td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
										echo "<td class='text-center'>";
								?>
										<a href='<?php echo base_url('report/generaInsectionSpecialPDF/x/x/x/x/hydrovac/' . $lista['id_inspection_hydrovac']); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>'></a>
								<?php
										echo "</td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
										echo "</tr>";
									endforeach;
								}

								?>
							</tbody>
						</table>
					</div>
					<!-- /.panel-body -->
				</div>
			<?php	} ?>
			<!-- FIN TABLA DE SPECIAL INSPECTION -->
		</div>




		<div class="col-lg-6">
			<!-- INICIO TABLA DE SPECIAL INSPECTION -- SWEEPER -->
			<?php if ($infoSweeper) {  ?>
				<a name="anclaSweeper"></a>
				<div class="panel panel-purpura">
					<div class="panel-heading">
						<i class="fa fa-search fa-fw"></i> Last Sweeper Inspection Records
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">

						<a class="btn btn-default btn-circle" href="#anclaUp"><i class="fa fa-arrow-up"></i> </a>

						<table width="100%" class="table table-striped table-bordered table-hover" id="dataSweeper">
							<thead>
								<tr>
									<th>Date & Time</th>
									<th>Driver Name</th>
									<th>Vehicle Make</th>
									<th>Vehicle Model</th>
									<th>Unit Number</th>
									<th>Download</th>
									<th>Description</th>
									<th>Comments</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if ($infoSweeper) {
									foreach ($infoSweeper as $lista) :
										$class = "";

										//si hay errores se coloca en amarillo
										if (
											['belt'] == 0
											|| ['power_steering'] == 0
											|| ['oil_level'] == 0
											|| ['coolant_level'] == 0
											|| ['coolant_leaks'] == 0
											|| ['hydraulic'] == 0
											|| ['belt_sweeper'] == 0
											|| ['oil_level_sweeper'] == 0
											|| ['coolant_level_sweeper'] == 0
											|| ['coolant_leaks_sweeper'] == 0
											|| ['head_lamps'] == 0
											|| ['hazard_lights'] == 0
											|| ['clearance_lights'] == 0
											|| ['tail_lights'] == 0
											|| ['work_lights'] == 0
											|| ['turn_signals'] == 0
											|| ['beacon_lights'] == 0
											|| ['tires'] == 0
											|| ['windows'] == 0
											|| ['clean_exterior'] == 0
											|| ['wipers'] == 0
											|| ['backup_beeper'] == 0
											|| ['door'] == 0
											|| ['decals'] == 0
											|| ['stering_wheels'] == 0
											|| ['drives'] == 0
											|| ['front_drive'] == 0
											|| ['elevator'] == 0
											|| ['rotor'] == 0
											|| ['mixture_box'] == 0
											|| ['lf_rotor'] == 0
											|| ['elevator_sweeper'] == 0
											|| ['mixture_container'] == 0
											|| ['broom'] == 0
											|| ['right_broom'] == 0
											|| ['left_broom'] == 0
											|| ['sprinkerls'] == 0
											|| ['water_tank'] == 0
											|| ['hose'] == 0
											|| ['cam'] == 0
											|| ['brake'] == 0
											|| ['emergency_brake'] == 0
											|| ['gauges'] == 0
											|| ['horn'] == 0
											|| ['seatbelt'] == 0
											|| ['seat'] == 0
											|| ['insurance'] == 0
											|| ['registration'] == 0
											|| ['clean_interior'] == 0
											|| ['fire_extinguisher'] == 0
											|| ['first_aid'] == 0
											|| ['emergency_kit'] == 0
											|| ['spill_kit'] == 0
										) {
											$class = "warning";
										}

										//si hay comentarios se coloca en rojo
										if ($lista['comments'] != '') {
											$class = "danger";
										}

										echo "<tr class='" . $class . "'>";
										echo "<td><p class='text-" . $class . "'>" . date('M j, Y - G:i:s', strtotime($lista['date_issue'])) . "</p></td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
										echo "<td class='text-center'>";
								?>
										<a href='<?php echo base_url('report/generaInsectionSpecialPDF/x/x/x/x/sweeper/' . $lista['id_inspection_sweeper']); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>'></a>
								<?php
										echo "</td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
										echo "</tr>";
									endforeach;
								}

								?>
							</tbody>
						</table>
					</div>
					<!-- /.panel-body -->
				</div>
			<?php	} ?>
			<!-- FIN TABLA DE SPECIAL INSPECTION -->

			<!-- INICIO TABLA DE SPECIAL INSPECTION -- GENERATORS -->
			<?php if ($infoGenerator) {  ?>
				<a name="anclaGenerator"></a>
				<div class="panel panel-purpura">
					<div class="panel-heading">
						<i class="fa fa-search fa-fw"></i> Last Generators Inspection Records
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">

						<a class="btn btn-default btn-circle" href="#anclaUp"><i class="fa fa-arrow-up"></i> </a>

						<table width="100%" class="table table-striped table-bordered table-hover" id="dataGenerator">
							<thead>
								<tr>
									<th>Date & Time</th>
									<th>Driver Name</th>
									<th>Vehicle Make</th>
									<th>Vehicle Model</th>
									<th>Unit Number</th>
									<th>Download</th>
									<th>Description</th>
									<th>Comments</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if ($infoGenerator) {
									foreach ($infoGenerator as $lista) :
										$class = "";

										//si hay errores se coloca en amarillo
										if (
											['belt'] == 0
											|| ['fuel_filter'] == 0
											|| ['oil_level'] == 0
											|| ['coolant_level'] == 0
											|| ['coolant_leaks'] == 0
											|| ['turn_signal'] == 0
											|| ['hazard_lights'] == 0
											|| ['tail_lights'] == 0
											|| ['flood_lights'] == 0
											|| ['boom'] == 0
											|| ['gears'] == 0
											|| ['gauges'] == 0
											|| ['pulley'] == 0
											|| ['electrical'] == 0
											|| ['brackers'] == 0
											|| ['tires'] == 0
											|| ['clean_exterior'] == 0
											|| ['decals'] == 0
										) {
											$class = "warning";
										}

										//si hay comentarios se coloca en rojo
										if ($lista['comments'] != '') {
											$class = "danger";
										}

										echo "<tr class='" . $class . "'>";
										echo "<td><p class='text-" . $class . "'>" . date('M j, Y - G:i:s', strtotime($lista['date_issue'])) . "</p></td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
										echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
										echo "<td class='text-center'>";
								?>
										<a href='<?php echo base_url('report/generaInsectionSpecialPDF/x/x/x/x/generator/' . $lista['id_inspection_generator']); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>'></a>
								<?php
										echo "</td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
										echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
										echo "</tr>";
									endforeach;
								}

								?>
							</tbody>
						</table>
					</div>
					<!-- /.panel-body -->
				</div>
			<?php	} ?>
			<!-- FIN TABLA DE SPECIAL INSPECTION -->
		</div>

	</div>






</div>
<!-- /#page-wrapper -->


<?php
if ($infoSafety) {
?>

	<a name="anclaSafety"></a>
	<div id="page-wrapper">

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-info">

					<div class="panel-heading">
						<i class="fa fa-life-saver fa-fw"></i> <strong>LAST FLHA - FIELD LEVEL HAZARD ASSESSMENT RECORDS</strong>
					</div>

					<div class="panel-body">

						<a class="btn btn-default btn-circle" href="#anclaUp"><i class="fa fa-arrow-up"></i> </a>

						<table width="100%" class="table table-striped table-bordered table-hover" id="dataSafety">
							<thead>
								<tr>
									<th>Meeting conducted by</th>
									<th>Date & Time</th>
									<th>Task(s) To Be Done</th>
									<th>Job Code/Name</th>
									<th>View</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($infoSafety as $lista) :
									echo "<tr>";
									echo "<td>" . $lista['name'] . "</td>";
									echo "<td>" . date('M j, Y - G:i:s', strtotime($lista['date'])) . "</td>";
									echo "<td>" . $lista['work'] . "</td>";
									echo "<td >" . $lista['job_description'] . "</td>";
									echo "<td class='text-center'>";
								?>
									<a class='btn btn-info btn-xs' href='<?php echo base_url('safety/review_flha/' . $lista['id_safety']) ?>'>
										View <span class="fa fa-life-saver" aria-hidden="true">
									</a>
								<?php
									echo "</td>";
									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>

					</div>
				</div>
				<?php
				echo  date("Y-m-d G:i:s");
				?>
			</div>
		</div>
	</div>
<?php	} ?>

<!-- Tables -->
<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"pageLength": 25,
			"info": false
		});

		$('#dataHauling').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});

		$('#dataDaily').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});

		$('#dataHeavy').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});

		$('#dataWatertruck').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});

		$('#dataHydrovac').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});

		$('#dataSweeper').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});

		$('#dataGenerator').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});

		$('#dataSafety').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});

		$('#dataTablesPlanning').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});


	});
</script>