<script type="text/javascript" src="<?php echo base_url('assets/js/validate/programming/programming.js?v=4.0.0'); ?>"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
	$(document).ready(function() {
		$('#modalOcasional').on('shown.bs.modal', function() {
			if ($.fn.select2 && $('#companySelect').hasClass("select2-hidden-accessible")) {
				$('#companySelect').select2('destroy');
			}
			$('#companySelect').select2({
				dropdownParent: $('#modalOcasional')
			});
		});
	});
</script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<?php
					if ($idProgramming != 'x') {
					?>
						<a class="btn btn-primary btn-xs" href=" <?php echo base_url('programming/index/' . $jobInfo[0]['id_job']); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<?php
					} else {
					?>
						<a class="btn btn-primary btn-xs" href=" <?php echo base_url('admin/job/1'); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<?php
						}
					?>
					<i class="fa fa-book"></i> <b>PLANNING LIST</b>
				</div>
				<div class="panel-body">

					<div class="alert alert-info">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
					</div>	

					<?php
						$deshabilitar = '';
					?>
					<?php if (!$deshabilitar) { ?>
						<a class='btn btn-primary btn-block' href='<?php echo base_url('programming/add_programming/' . $jobInfo[0]['id_job']); ?>'>
							<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span> New Planning
						</a>
						<br>
					<?php } ?>

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
					if ($information) {
					?>
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th class='text-center' width="5%">ID</th>
									<th class='text-center' width="10%">Date</th>
									<th class='text-center' width="20%">Job Code/Name</th>
									<th class='text-center' width="45%">Observation</th>
									<th class='text-center' width="10%">Action</th>
									<th class='text-center' width="10%">Done by</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($information as $lista) :
									$idParent = $lista["parent_id"];
									$flagDate = $lista["flag_date"];
									$flagText = '';
									if ($lista["flag_date"] == 2) {
										$flagText = "text-violeta";
									}
									echo "<tr class='" . $flagText . "'>";
									echo "<td class='text-center'>";
									echo ($flagDate == 2 && $idParent != null && $idParent != '') ? "<b>Child</b><br>" . $idParent : ($flagDate == 1 ? "" : "<b>Parent</b><br>" . $lista["id_programming"]);
									echo "</td>";
									echo "<td class='text-center'>" . date('F j, Y', strtotime($lista['date_programming'])) . "</td>";
									echo "<td class='text-center'>" . $lista['job_description'] . "</td>";
									echo "<td>" . $lista['observation'] . "</td>";
									echo "<td class='text-center'><small>";


									//consultar si la fecha de la programacion es mayor a la fecha actual
									$fechaProgramacion = $lista['date_programming'];

									$datetime1 = date_create($fechaProgramacion);
									$datetime2 = date_create(date("Y-m-d"));

									if ($datetime1 < $datetime2) {
										echo '<p class="text-danger"><strong>OVERDUE</strong></p>';
										$deshabilitar = 'disabled';
									} else {

										if ($lista['state'] == 2) {
											echo '<p class="text-success"><strong>COMPLETE</strong></p>';
										} elseif ($lista['state'] == 1) {
											echo '<p class="text-danger"><strong>INCOMPLETE</strong></p>';
										}
								?>

										<?php
										if (!$deshabilitar) {
										?>
											<a href='<?php echo base_url("programming/add_programming/" . $lista['fk_id_job'] . "/". $lista['id_programming']); ?>' class='btn btn-info btn-xs' title="Edit"><i class='fa fa-pencil'></i></a>

											<button type="button" id="<?php echo $lista['id_programming']; ?>" class='btn btn-danger btn-xs btn-delete-programming' title="Delete">
												<i class="fa fa-trash-o"></i>
											</button>
									<?php
										}
									}
									?>

									<a href='<?php echo base_url("programming/index/"  . $lista['fk_id_job'] . "/" . $lista['id_programming']); ?>' class='btn btn-success btn-xs' title="View"><i class='fa fa-eye'></i></a>


									<?php

									echo "</small></td>";
									echo "<td class='text-center'><p class='text-success'>" . $lista['name'] . "</p>";

									//enviar mensaje; 
									//revisar que la fecha sea mayor a la fecha y hora actual
									//revisar que exista al menos un trabajador
									//se actualiza el estado de la programacion
									//se envia mensaje
									if ($informationWorker && !$deshabilitar) {

									?>
										<a href='<?php echo base_url("programming/send/" . $lista['id_programming']); ?>' class='btn btn-info btn-xs' title="Send SMS"><i class='glyphicon glyphicon-send'></i></a>

								<?php
									}

									if ($lista['fk_id_workorder']) {
										echo "<br><br><a href='" . base_url('workorders/add_workorder/' . $lista['fk_id_workorder']) . "'>W.O. # " . $lista['fk_id_workorder'] . "</a>";
									}

									echo "</td>";

									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>
					<?php } ?>
					<!-- INICIO LISTA DE WORKER CON DAY OFF -->
					<?php
					if ($dayoffList) {
					?>
						<div class="table-responsive">
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
								<thead>
									<tr class="text-danger danger">
										<th colspan="2">Employees with day off coming up</th>
									</tr>
									<tr class="text-danger danger">
										<th>Employee</th>
										<th class="text-center">Date of day off</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($dayoffList as $dayoff) :
										echo "<tr>";
										echo "<td>" . $dayoff['name'] . "</td>";
										echo "<td class='text-center'>" . $dayoff['days_off'] . "</td>";
										echo "</tr>";
									endforeach;
									?>
								</tbody>
							</table>
						</div>
					<?php } ?>
					<!-- FIN LISTA DE WORKERS CON DAY OFF -->

					<?php
					if ($idProgramming != 'x' && $job_planning == 1) {
					?>
						<div class="row">
							<div class="col-lg-8"></div>
							<div class="col-lg-4">
								<div class="chat-panel panel panel-violeta">
									<div class="panel-heading">
										<i class="fa fa-copy"></i> <b>Clone this Planning for the Date</b>
									</div>

									<div class="panel-footer">
										<form name="clonePlanning" id="clonePlanning" method="post">
											<input type="hidden" id="hddIdProgramming" name="hddIdProgramming" value="<?php echo $idProgramming; ?>" />
											<script>
												$(function() {
													$("#date").datepicker({
														changeMonth: true,
														changeYear: true,
														dateFormat: 'yy-mm-dd',
														minDate: '0'
													});
												});
											</script>

											<div class="input-group">
												<input type="text" class="form-control" id="date" name="date" value="" placeholder="Date" required />
												<span class="input-group-btn">
													<button type="button" class="btn btn-violeta btn-sm btn-service-order-parts" id="btnSubmitClone" name="btnSubmitClone">
														<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Clone Planning
													</button>
												</span>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div id="div_error" style="display:none">
									<div class="alert alert-danger"> <strong>Error!!!</strong> Ask for help. </div>
								</div>

								<div id="div_guardado" style="display:none">
									<div class="alert alert-success"> <strong>Ok!</strong> You have cloned the Planning.</div>
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

	<?php
		if ($idProgramming != 'x') {
	?>

	<!--INICIO PERSONNEL -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-users"></i> <b>PERSONNEL</b>
				</div>
				<div class="panel-body">

					<div class="col-lg-12">
						<?php if ($informationWorker) { ?>
							<button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#modalWorker" id="x" <?php echo $deshabilitar; ?>>
								<i class="fa fa-user"></i> Add Personal
							</button><br>

						<?php } elseif ($lista['state'] == 1 && $idProgramming != 'x') { ?>
							<a href='<?php echo base_url("programming/add_programming_workers/" . $lista['id_programming']); ?>' class='btn btn-warning btn-block' title="Workers" <?php echo $deshabilitar; ?>>
								<i class='fa fa-users'></i> Add Personal
							</a><br>
						<?php } ?>
					</div>

					<!-- PERSONNEL LIST-->
					<?php
					if ($informationWorker) {
					?>
						<table class="table table-bordered table-striped table-hover table-condensed">
							<?php if ($flagDate == 2 && ($idParent == null || $idParent == '')) { ?>
							<tr class="warning">
								<th class="text-right" colspan="8">
										<form name="generateChildWorkers" id="generateChildWorkers" method="post" action="<?php echo base_url("programming/generate_child_workers"); ?>">
											<input type="hidden" id="hddIdProgramming" name="hddIdProgramming" value="<?php echo $idProgramming; ?>" />
											<input type="submit" id="btnSubmit" name="btnSubmit" value="Generate Child Workers" class="btn btn-violeta" />
										</form>
								</th>
							</tr>
							<?php } ?>

							<tr class="warning">
								<th style="width: 16%"><small>Name / Employee Type</small></th>
								<th class="text-center" width= "8%"><small>Time In</small></th>
								<th class="text-center" width= "13%"><small>Site</small></th>
								<th class="text-center" width= "13%"><small>FLHA/IHSR</small></th>
								<th class="text-center" width= "21%"><small>Description</small></th>
								<th class="text-center" width= "22%"><small>Equipment</small></th>
								<th class="text-center" width= "8%"><small>Creat WO</small></th>
								<th class="text-center" width= "8%"><small>Actions</small></th>
							</tr>

							<?php
							$mensaje = "";

							foreach ($informationWorker as $data) :

								$mensaje .= "<br>";
								switch ($data['site']) {
									case 1:
										$mensaje .= "At the yard - ";
										break;
									case 2:
										$mensaje .= "At the site - ";
										break;
									case 3:
										$mensaje .= "At Terminal - ";
										break;
									case 4:
										$mensaje .= "On-line training - ";
										break;
									case 5:
										$mensaje .= "At training facility - ";
										break;
									case 6:
										$mensaje .= "At client's office - ";
										break;
									default:
										$mensaje .= "At the yard - ";
										break;
								}
								$mensaje .= $data['hora'];

								$mensaje .= "<br>" . $data['name'];
								$mensaje .= $data['description'] ? "<br>" . $data['description'] : "";
								$mensaje .= !empty($data['fk_id_machine']) ? "<br>" . ($data['informationEquipments']['unit_description'] ?? '') : "";

								if ($data['safety'] == 1) {
									$mensaje .= "<br>Do FLHA";
								} elseif ($data['safety'] == 2) {
									$mensaje .= "<br>Do IHSR";
								} elseif ($data['safety'] == 3) {
									$mensaje .= "<br>Job site orientation";
								}

								if ($data['creat_wo'] == 1) {
									$mensaje .= "<br>You are in charge of the W.O.";
								}
								$mensaje .= "<br>";

								echo "<tr>";
								$idRecord = $data['id_programming_worker'];
								$fkIdProgramming = $data['fk_id_programming'];
							?>

								<form name="worker_<?php echo $idRecord ?>" id="worker_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("programming/update_worker"); ?>">

									<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
									<input type="hidden" id="hddIdProgramming" name="hddIdProgramming" value="<?php echo $fkIdProgramming; ?>" />

									<td>
									<small><?php echo $data["name"]; ?></small><br>
										<select name="type" class="form-control" <?php echo $deshabilitar; ?>>
											<?php for ($i = 0; $i < count($employeeTypeList); $i++) { ?>
												<option value="<?php echo $employeeTypeList[$i]["id_employee_type"]; ?>"  <?php if($data["fk_id_employee_type"] == $employeeTypeList[$i]["id_employee_type"]) { echo "selected"; }  ?>><?php echo $employeeTypeList[$i]["employee_type"]; ?></option>	
											<?php } ?>
										</select>
									</td>

									<td>
										<select name="hora_inicio" class="form-control js-example-basic-single" required <?php echo $deshabilitar; ?>>
											<option value=''>Select...</option>
											<?php for ($i = 0; $i < count($horas); $i++) { ?>
												<option value="<?php echo $horas[$i]["id_hora"]; ?>" <?php
																										if ($horas[$i]["id_hora"] == $data["fk_id_hour"]) {
																											echo 'selected="selected"';
																										}
																										?>><?php echo $horas[$i]["hora"]; ?>
												</option>
											<?php } ?>
										</select>
									</td>

									<td>
										<select name="site" class="form-control" required <?php echo $deshabilitar; ?>>
											<option value="">Select...</option>
											<option value=1 <?php if ($data["site"] == 1) {
																echo "selected";
															}  ?>>At the yard</option>
											<option value=2 <?php if ($data["site"] == 2) {
																echo "selected";
															}  ?>>At the site</option>
											<option value=3 <?php if ($data["site"] == 3) {
																echo "selected";
															}  ?>>At Terminal</option>
											<option value=4 <?php if ($data["site"] == 4) {
																echo "selected";
															}  ?>>On-line training</option>
											<option value=5 <?php if ($data["site"] == 5) {
																echo "selected";
															}  ?>>At training facility</option>
											<option value=6 <?php if ($data["site"] == 6) {
																echo "selected";
															}  ?>>At client's office</option>
										</select>
									</td>

									<td>
										<select name="safety" class="form-control" <?php echo $deshabilitar; ?>>
											<option value="">Select...</option>
											<option value=1 <?php if ($data["safety"] == 1) {
																echo "selected";
															}  ?>>FLHA</option>
											<option value=2 <?php if ($data["safety"] == 2) {
																echo "selected";
															}  ?>>IHSR</option>
											<option value=3 <?php if ($data["safety"] == 3) {
																echo "selected";
															}  ?>>Job Site Orientation</option>
										</select>
									</td>

									<td>
										<textarea id="description" name="description" class="form-control" rows="3" required <?php echo $deshabilitar; ?>><?php echo $data['description']; ?></textarea>
									</td>

									<td>
										<select multiple="multiple" name="machine[]" class="form-control js-example-basic-multiple">
											<option value=''>Select...</option>
											<?php for ($i = 0; $i < count($informationVehicles); $i++) { ?>
												<option value="<?php echo $informationVehicles[$i]["id_truck"]; ?>" <?php if ($data["fk_id_machine"] != "" && ($data["fk_id_machine"] == $informationVehicles[$i]["id_truck"] || in_array($informationVehicles[$i]["id_truck"], json_decode($data["fk_id_machine"])))) {
																														echo "selected";
																													}  ?>><?php echo $informationVehicles[$i]["unit_number"]; ?></option>
											<?php } ?>
										</select>
									</td>

									<td>
										<select name="creat_wo" class="form-control" <?php echo $deshabilitar; ?>>
											<option value="">Select...</option>
											<option value=1 <?php if ($data["creat_wo"] == 1) {
																echo "selected";
															}  ?>>Yes</option>
											<option value=2 <?php if ($data["creat_wo"] == 2) {
																echo "selected";
															}  ?>>No</option>
										</select>
									</td>
									<td class='text-center'>
										<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Save" <?php echo $deshabilitar; ?>>
											<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
										</button>
								</form>

								<br><br>
								<?php
								if (($datetime1 >= $datetime2) && $informationWorker && !$deshabilitar) {
								?>
									<a class='btn btn-purpura btn-xs' href='<?php echo base_url('programming/deleteWorker/' . $idProgramming . '/' . $idRecord) ?>' id="btn-delete" title="Delete">
										<span class="fa fa-trash-o" aria-hidden="true"> </span>
									</a>
								<?php
								}
								?>

								</td>
							</tr>
							<?php endforeach; ?>
						</table>

						<!-- INICIO MESSAGE -->
						<div class="table-responsive">
							<table id="dataTablesWorker" class="table table-striped jambo_table bulk_action" cellspacing="0" width="100%">

								<thead>
									<tr class="headings">
										<th class="column-title">-- MESSAGE --</th>
										<th class="column-title">-- INSPECTIONS --</th>
										<th class="column-title">-- FLHA / IHSR / JSO --</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td>
											<?php
											echo date('F j, Y', strtotime($information[0]['date_programming']));
											echo "<br>" . $information[0]['job_description'];
											echo "<br>" . $information[0]['observation'];
											echo "<br>";

											echo $mensaje;
											?>
										</td>
										<td>
											<?php echo $memo; ?>
										</td>
										<td>
											<?php
											echo $memo_flha;
											echo "<br><br>";
											echo $memo_tool_box;
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- FIN MESSAGE -->
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<!--FIN PERSONNEL -->
	
	<!--INICIO MATERIALS -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					<i class="fa fa-tint"></i> <b>MATERIALS</b>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
						<button type="button" class="btn btn-success btn-block btn-materials" data-toggle="modal" data-target="#modalMaterials" id="<?php echo 'material-' . $information[0]["id_programming"]; ?>" title="Add Materials" <?php echo $deshabilitar; ?>>
							<i class="fa fa-tint"></i> Add Materials
						</button><br>
					</div>

					<?php if ($programmingMaterials) { ?>
						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="success">
								<th>Info. Material</th>
								<th>Description</th>
								<th>Quantity</th>
								<th>Unit</th>
								<th class="text-center">Actions</th>
							</tr>
							<?php
							foreach ($programmingMaterials as $data) :
								echo "<tr>";
								echo "<td ><small><strong>Material</strong><br>" . $data['material'] . "</small></td>";

								$idRecord = $data['id_programming_material'];
							?>
								<form name="material_<?php echo $idRecord ?>" id="material_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("programming/updated_material"); ?>">
									<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
									<input type="hidden" id="hddidProgramming" name="hddidProgramming" value="<?php echo $data['fk_id_programming']; ?>" />
									<td>
										<textarea id="description" name="description" class="form-control" rows="3" required <?php echo $deshabilitar; ?> <?php echo $deshabilitar; ?>><?php echo $data['description']; ?></textarea>
									</td>

									<td>
										<input type="text" id="quantity" name="quantity" class="form-control" placeholder="Quantity" value="<?php echo $data['quantity']; ?>" required <?php echo $deshabilitar; ?>>
									</td>

									<td>
										<input type="text" id="unit" name="unit" class="form-control" placeholder="Unit" value="<?php echo $data['unit']; ?>" required <?php echo $deshabilitar; ?>>
									</td>

									<td class='text-center'>
										<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Save" <?php echo $deshabilitar; ?>>
											<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
										</button>
								</form>

								<br><br>
								<?php if (!$deshabilitar) { ?>
									<a class='btn btn-danger btn-xs' href='<?php echo base_url('programming/deleteMaterial/' . $data['id_programming_material'] . '/' . $data['fk_id_programming']) ?>' id="btn-delete" title="Delete">
										<i class="fa fa-trash-o"></i>
									</a>
								<?php } else {
									echo "---";
								} ?>

								</td>

								</tr>
							<?php
							endforeach;
							?>
						</table>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!--FIN MATERIALS -->

	<!--INICIO SUBCONTRACTOR -->	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-beer"></i> <b>SUBCONTRACTOR</b>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
						<button type="button" class="btn btn-primary btn-block btn-occasional" data-toggle="modal" data-target="#modalOcasional" id="<?php echo 'ocasional-' . $information[0]["id_programming"]; ?>" <?php echo $deshabilitar; ?>>
							<i class="fa fa-beer"></i> Add Occasional Subcontractor
						</button><br>
					</div>

					<?php
					if ($programmingOccasional) {
					?>
						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="primary">
								<th class="text-center">Info. Subcontractor</th>
								<th class="text-center">Description</th>
								<th class="text-center">Quantity</th>
								<th class="text-center">Unit</th>
								<th class="text-center">Hours</th>
								<th class="text-center">Actions</th>
							</tr>
							<?php
							foreach ($programmingOccasional as $data) :
								echo "<tr>";
								echo "<td ><small><strong>Company</strong><br>" . $data['company_name'] . "</small>";
								echo "<br><small><strong>Equipment</strong><br>" . $data['equipment'] . "</small>";
								echo "<br><small><strong>Contact</strong><br>" . $data['contact'] . "</small></td>";

								$idRecord = $data['id_programming_ocasional'];
							?>
								<form name="ocasional_<?php echo $idRecord ?>" id="ocasional_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("programming/save_hour"); ?>">
									<input type="hidden" id="formType" name="formType" value="ocasional" />
									<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
									<input type="hidden" id="hddIdProgramming" name="hddIdProgramming" value="<?php echo $data['fk_id_programming']; ?>" />
									<input type="hidden" id="rate" name="rate" value="<?php echo $data['rate']; ?>" />
									<input type="hidden" id="markup" name="markup" value="<?php echo $data['markup']; ?>" />
									<input type="hidden" id="check_pdf" name="check_pdf" value="<?php echo $data['view_pdf']; ?>" />

									<td>
										<textarea id="description" name="description" class="form-control" rows="3" required <?php echo $deshabilitar; ?>><?php echo $data['description']; ?></textarea>
									</td>

									<td>
										<input type="text" id="quantity" name="quantity" class="form-control" placeholder="Quantity" value="<?php echo $data['quantity']; ?>" required <?php echo $deshabilitar; ?>>
									</td>

									<td>
										<input type="text" id="unit" name="unit" class="form-control" placeholder="Unit" value="<?php echo $data['unit']; ?>" required <?php echo $deshabilitar; ?>>
									</td>

									<td>
										<input type="text" id="hours" name="hours" class="form-control" placeholder="Hours" value="<?php echo $data['hours']; ?>" required <?php echo $deshabilitar; ?>>
									</td>

									<td class='text-center'>
										<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Save" <?php echo $deshabilitar; ?>>
											<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
										</button>
								</form>

								<br><br>
								<?php if (!$deshabilitar) { ?>
									<a class='btn btn-danger btn-xs' href='<?php echo base_url('programming/deleteRecord/ocasional/' . $data['id_programming_ocasional'] . '/' . $data['fk_id_programming'] . '/index') ?>' id="btn-delete">
										<i class="fa fa-trash-o"></i>
									</a>
								<?php } else {
									echo "---";
								} ?>

								</td>
								</tr>
							<?php
							endforeach;
							?>
						</table>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!--FIN SUBCONTRACTOR -->

	<?php
		}
	?>
</div>

<!--INICIO Modal para adicionar WORKER -->
<?php if ($workersList) { ?>
	<div class="modal fade text-center" id="modalWorker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content" id="tablaDatos">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="exampleModalLabel">ADD PERSONNEL</h4>
				</div>

				<div class="modal-body">
					<form name="formWorkerProgramming" id="formWorkerProgramming" role="form" method="post" action="<?php echo base_url("programming/save_One_Worker_programming") ?>">
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $idProgramming; ?>" />

						<div class="form-group text-left">
							<label class="control-label" for="worker">Worker</label>
							<select name="worker" id="worker" class="form-control" required>
								<option value=''>Select...</option>
								<?php for ($i = 0; $i < count($workersList); $i++) { ?>
									<option value="<?php echo $workersList[$i]["id_user"]; ?>"><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:50%;" align="center">
									<button type="submit" id="btnSubmitWorker" name="btnSubmitWorker" class='btn btn-primary'>
									 	Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div id="div_load" style="display:none">
								<div class="progress progress-striped active">
									<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
										<span class="sr-only">45% completado</span>
									</div>
								</div>
							</div>
							<div id="div_error" style="display:none">
								<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
							</div>
						</div>

					</form>
				</div>

			</div>
		</div>
	</div>
<?php } ?>
<!--FIN Modal para adicionar WORKER -->

<!--INICIO Modal para EQUIPMENT -->
<div class="modal fade text-center" id="modalEquipment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatosEquipment">

		</div>
	</div>
</div>
<!--FIN Modal para EQUIPMENT -->

<!--INICIO Modal para MATERIAL -->
<div class="modal fade text-center" id="modalMaterials" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tableDataMaterial">

		</div>
	</div>
</div>
<!--FIN Modal para MATERIAL -->

<!--INICIO Modal para OCASIONAL-->
<div class="modal fade text-center" id="modalOcasional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatosOcasional">

		</div>
	</div>
</div>
<!--FIN Modal para OCASIONAL -->

<!-- Tables -->
<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"ordering": false,
			"pageLength": 100,
			"info": false
		});

		$('.js-example-basic-multiple').select2();
	});

	$(".btn-materials").click(function() {
		var oID = $(this).attr("id");
		$.ajax({
			type: 'POST',
			url: base_url + 'programming/loadModalMaterials',
			data: {
				'idProgramming': oID
			},
			cache: false,
			success: function(data) {
				$('#tableDataMaterial').html(data);
			}
		});
	});

	$(".btn-occasional").click(function() {
		var oID = $(this).attr("id");
		//verificar que se este enviando el
		if (oID != 'btnSubmit') {
			$.ajax({
				type: 'POST',
				url: base_url + 'programming/cargarModalOcasional',
				data: {
					'idProgramming': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatosOcasional').html(data);
				}
			});
		}
	});
</script>