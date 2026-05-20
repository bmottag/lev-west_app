<script type="text/javascript" src="<?php echo base_url("assets/js/validate/workorder/workorder.js?v=1.0.0"); ?>"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});
</script>

<script>
	$(function() {

		$(".btn-warning").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'workorders/cargarModalPersonal',
				data: {
					'idWorkorder': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatos').html(data);
				}
			});
		});

		$(".btn-material").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'workorders/cargarModalMaterials',
				data: {
					'idWorkorder': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatosMaterial').html(data);
				}
			});
		});

		$(".btn-info").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'workorders/cargarModalEquipment',
				data: {
					'idWorkorder': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatosEquipment').html(data);
				}
			});
		});

		$(".btn-ocasional").click(function() {
			var oID = $(this).attr("id");
			//verificar que se este enviando el 
			if (oID != 'btnSubmit') {
				$.ajax({
					type: 'POST',
					url: base_url + 'workorders/cargarModalOcasional',
					data: {
						'idWorkorder': oID
					},
					cache: false,
					success: function(data) {
						$('#tablaDatosOcasional').html(data);
					}
				});
			}
		});

		$(".btn-violeta").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'workorders/cargarModalReceipts',
				data: {
					'idWorkorder': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatosReceipt').html(data);
				}
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
					$userRol = session()->get('rol');
					if ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER || $userRol == ID_ROL_ACCOUNTING || $userRol == ID_ROL_WORKORDER || $userRol == ID_ROL_ENGINEER) { //If it is a SUPER ADMIN user, show GO BACK MENU
					?>
						<a class="btn btn-gris btn-xs" href=" <?php echo base_url() . 'workorders/search/y'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<?php } ?>

					<i class="fa fa-money"></i> <strong>WORK ORDERS</strong>
				</div>
				<div class="panel-body">

					<?php
					/**
					 * If it is:
					 * SUPER ADMIN, MANAGEMENT, ACCOUNTING ROLES and WORK ORDER USER
					 * They have acces to asign rate and dowloadinvoice
					 */
					if ($information) {

						if ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER || $userRol == ID_ROL_ACCOUNTING || $userRol == ID_ROL_WORKORDER || $userRol == ID_ROL_ENGINEER) {
					?>
							<ul class="nav nav-pills">
								<li class='active'><a href="<?php echo base_url('workorders/add_workorder/' . $information[0]["id_workorder"]) ?>">Edit</a>
								</li>
								<li><a href="<?php echo base_url('workorders/view_workorder/' . $information[0]["id_workorder"]) ?>">Asign rate</a>
								</li>
								<li><a href="<?php echo base_url('workorders/generaWorkOrderPDF/' . $information[0]["id_workorder"]) ?>" target="_blank">Download invoice</a>
								</li>
								<?php
								if (($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_WORKORDER) && $information[0]['state'] != 4) {
								?>
									<li><a href="<?php echo base_url('workorders/workorder_expenses/' . $information[0]["id_workorder"]) ?>">Workorder Expenses</a>
									</li>
								<?php } ?>
								<li><a href="<?php echo base_url('workorders/foreman_view/' . $information[0]["id_workorder"]) ?>">Foreman View</a>
								</li>
								<?php if($workorderOcasional): ?>
								<li><a href="<?php echo base_url('workorders/subcontractor_invoices/' . $information[0]["id_workorder"]) ?>">Subcontractors Invoices</a>
								</li>
								<?php endif; ?>
							</ul>
						<?php } else { ?>
							<ul class="nav nav-pills">
								<li class='active'><a href="<?php echo base_url('workorders/add_workorder/' . $information[0]["id_workorder"]) ?>">Edit</a>
								</li>
								<li><a href="<?php echo base_url('workorders/foreman_view/' . $information[0]["id_workorder"]) ?>">Foreman View</a>
								</li>
							</ul>
					<?php }
						echo "<br>";
					}
					?>

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
						switch ($information[0]['state']) {
							case 0:
								$valor = 'On Field';
								$clase = "alert-danger";
								break;
							case 1:
								$valor = 'In Progress';
								$clase = "alert-warning";
								break;
							case 2:
								$valor = 'Revised';
								$clase = "alert-info";
								break;
							case 3:
								$valor = 'Send to the Client';
								$clase = "alert-success";
								break;
							case 4:
								$valor = 'Closed';
								$clase = "alert-danger";
								break;
							case 5:
								$valor = 'Accounting';
								$clase = "text-warning";
								break;
						}
					?>
						<div class="row">
							<div class="col-lg-12">
								<div class="alert <?php echo $clase; ?>">
									<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									Actual status: <strong><?php echo $valor; ?></strong>
								</div>
							</div>
						</div>
					<?php } ?>


					<form name="form" id="form" class="form-horizontal" method="post">
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information ? $information[0]["id_workorder"] : ""; ?>" />

						<div class="form-group">
							<script>
								$(function() {
									$("#date").datepicker({
										changeMonth: true,
										dateFormat: 'yy-mm-dd'
									});
								});
							</script>
							<label class="col-sm-4 control-label" for="hddTask">Work Order Date :</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="date" name="date" value="<?php echo $information ? $information[0]["date"] : ""; ?>" placeholder="Date" required <?php echo $deshabilitar; ?> />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="taskDescription">Job Code/Name :</label>
							<div class="col-sm-5">
								<select name="jobName" id="jobName" class="form-control js-example-basic-single" <?php echo $deshabilitar; ?>>
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($jobs); $i++) { ?>
										<option value="<?php echo $jobs[$i]["id_job"]; ?>" <?php if ($information && $information[0]["fk_id_job"] == $jobs[$i]["id_job"]) {
																								echo "selected";
																							}  ?>><?php echo $jobs[$i]["job_description"]; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="company">Company:</label>
							<div class="col-sm-5">
								<input type="hidden" id="company" name="company" class="form-control" placeholder="Company" value="<?php echo $information ? $information[0]["id_company"] : ""; ?>" <?php echo $deshabilitar; ?>>
								<input type="text" id="companyName" name="companyName" class="form-control" placeholder="Company" value="<?php echo $information ? $information[0]["company"] : ""; ?>" disabled>
							</div>
						</div>

						<div class="form-group text-danger" id="div_stockQuantity">
							<label class="col-sm-4 control-label" for="foreman">Foreman's name : </label>
							<div class="col-sm-5">
								<input type="text" id="foreman" name="foreman" class="form-control" placeholder="Foreman's name" value="<?php echo $information ? $information[0]["foreman_name_wo"] : ""; ?>" <?php echo $deshabilitar; ?>>
							</div>
						</div>

						<div class="form-group text-danger">
							<label class="col-sm-4 control-label" for="email">Foreman's mobile number : </label>
							<div class="col-sm-5">
								<input type="text" id="movilNumber" name="movilNumber" class="form-control" placeholder="Foreman's mobile number" value="<?php echo $information ? $information[0]["foreman_movil_number_wo"] : ""; ?>" <?php echo $deshabilitar; ?>>
							</div>
						</div>

						<div class="form-group text-danger">
							<label class="col-sm-4 control-label" for="email">Foreman's email : </label>
							<div class="col-sm-5">
								<input type="text" id="email" name="email" class="form-control" placeholder="Foreman's email" value="<?php echo $information ? $information[0]["foreman_email_wo"] : ""; ?>" <?php echo $deshabilitar; ?>>
							</div>
						</div>

						<?php if ($information) { ?>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="taskDescription">Work Done :</label>
								<div class="col-sm-5">
									<textarea id="observation" name="observation" class="form-control" rows="3" <?php echo $deshabilitar; ?> placeholder="Task description"><?php echo $information ? $information[0]["observation"] : ""; ?></textarea>
								</div>
							</div>

							<?php if ($information[0]['id_acs']) { ?>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="taskDescription">ACS :</label>
									<div class="col-sm-5">
									<?php
										echo "<a href='" . base_url('acs/view_acs/' . $information[0]['id_acs']) . "'>View Accounting Control Sheet (ACS)</a>";
									?>
									</div>
								</div>
							<?php } ?>
						<?php } ?>

						<?php if (!$deshabilitar) { ?>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-12" style="display:flex; align-items:flex-start; justify-content:center; gap:10px; flex-wrap:wrap;">

										<div>
											<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-sm">
												Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
											</button>
										</div>

		

									</div>
								</div>
							</div>
						<?php } ?>

					</form>


						<?php if (!$deshabilitar) { ?>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-12" style="display:flex; align-items:flex-start; justify-content:center; gap:10px; flex-wrap:wrap;">

										<?php if ($information) { ?>

											<div>
												<?= view('App\Views\template\signature_component', [
													'imageUrl'   => $information[0]['signature_wo'] ?? null,
													'formAction' => base_url('workorders/save_signature'),
													'hiddenName' => 'image',
													'height'     => 200,
													'id' 		 => $information[0]['id_workorder']
												]) ?>
											</div>

											<!-- enlace para enviar mensaje de texto al foreman -->
											<?php if ($information[0]['foreman_movil_number_wo']) { ?>
												<div>
													<a href="<?php echo base_url("workorders/sendSMSForeman/" . $information[0]['id_workorder']); ?>" class="btn btn-default btn-sm">
														<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Send SMS to foreman
													</a>
												</div>
											<?php } ?>

										<?php } ?>

									</div>
								</div>
							</div>
						<?php } ?>





				</div>
			</div>
		</div>
	</div>

	<!--INICIO FORMULARIOS -->
	<?php
	if ($information) {
	?>

		<!--INICIO ADDITIONAL INFORMATION -->
		<div class="row">
			<div class="col-lg-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						ADDITIONAL INFORMATION <br>
						This field is only additional information for the office.
					</div>
					<div class="panel-body">

						<div class="col-lg-12">
							<form name="formState" id="formState" class="form-horizontal" method="post">
								<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $information ? $information[0]["id_workorder"] : ""; ?>" />
								<input type="hidden" id="hddIdAcs" name="hddIdAcs" value="<?php echo $information ? $information[0]["id_acs"] : ""; ?>" />

								<?php
								/**
								 * Estado work order
								 * Solo se puede editar por los siguientes ROLES
								 * SUPER ADMIN, MANAGEMENT, ACCOUNTING, WORK ORDER, ENGINEER ROLES
								 */
								if ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER || $userRol == ID_ROL_ACCOUNTING || $userRol == ID_ROL_WORKORDER || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_ACCOUNTING_ASSISTANT) {
								?>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="state">Status :</label>
										<div class="col-sm-8">
											<select name="state" id="state" class="form-control" required <?php echo $deshabilitar; ?>>
												<option value="">Select...</option>
												<option value=0>On Field</option>
												<option value=1>In Progress</option>
												<option value=2>Revised</option>
												<option value=5>Accounting</option>
												<option value=3>Send to the Client</option>
												<option value=4>Closed</option>
											</select>
										</div>
									</div>
								<?php } else {  ?>
									<input type="hidden" id="state" name="state" value=0 />
								<?php }  ?>

								<div class="form-group">
									<label class="col-sm-4 control-label" for="information">Additional information :</label>
									<div class="col-sm-8">
										<textarea id="information" name="information" class="form-control" rows="3" placeholder="Additional information" required <?php echo $deshabilitar; ?>></textarea>
									</div>
								</div>

								<?php if (!$deshabilitar) { ?>
									<div class="form-group">
										<div class="row" align="center">
											<div style="width:100%;" align="center">

												<button type="button" id="btnState" name="btnState" class="btn btn-primary">
													Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true" />
												</button>

											</div>
										</div>
									</div>
								<?php } ?>

							</form>

							<div class="alert alert-danger ">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
								Make sure to fill up all necessary information.
							</div>

						</div>

					</div>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="chat-panel panel panel-primary">
					<div class="panel-heading">
						<i class="fa fa-comments fa-fw"></i> Status history
					</div>

					<div class="panel-body">
						<ul class="chat">
							<?php
							if ($workorderState) {
								foreach ($workorderState as $data) :

									switch ($data['state']) {
										case 0:
											$valor = 'On Field';
											$clase = "text-danger";
											$icono = "fa-thumb-tack";
											break;
										case 1:
											$valor = 'In Progress';
											$clase = "text-warning";
											$icono = "fa-refresh";
											break;
										case 2:
											$valor = 'Revised';
											$clase = "text-primary";
											$icono = "fa-check";
											break;
										case 3:
											$valor = 'Send to the Client';
											$clase = "text-success";
											$icono = "fa-envelope-o";
											break;
										case 4:
											$valor = 'Closed';
											$clase = "text-danger";
											$icono = "fa-power-off";
											break;
										case 5:
											$valor = 'Accounting';
											$clase = "text-warning";
											$icono = "fa-list-alt";
											break;
									}
							?>
									<li class="right clearfix">
										<span class="chat-img pull-right">
											<small class="pull-right text-muted">
												<i class="fa fa-clock-o fa-fw"></i> <?php echo $data['date_issue']; ?>
											</small>
										</span>
										<div class="chat-body clearfix">
											<div class="header">
												<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
												<strong class="primary-font"><?php echo $data['first_name']; ?></strong>
											</div>
											<p>
												<?php echo $data['observation']; ?>
											</p>
											<?php echo '<p class="' . $clase . '"><strong><i class="fa ' . $icono . ' fa-fw"></i>' . $valor . '</strong></p>'; ?>
										</div>
									</li>
							<?php
								endforeach;
							}
							?>
						</ul>

					</div>
				</div>
			</div>
		</div>
		<!--FIN ADDITIONAL INFORMATION -->

		<!--INICIO PERSONAL -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<b>PERSONNEL</b>
					</div>
					<div class="panel-body">

						<?php if (!$deshabilitar) { ?>
							<div class="col-lg-12">

								<button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#modal" id="<?php echo $information[0]["id_workorder"]; ?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Personnel
								</button><br>
							</div>
						<?php } ?>

						<?php
						if ($workorderPersonal) {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="warning">
									<th class="text-center">Employee Name</th>
									<th class="text-center">Employee Type</th>
									<th class="text-center">Work Done</th>
									<th class="text-center">Hours</th>
									<th class="text-center">Links</th>
								</tr>
								<?php
								foreach ($workorderPersonal as $data) :
									echo "<tr>";
									echo "<td ><small>" . $data['name'] . "</small></td>";

									$idRecord = $data['id_workorder_personal'];
								?>
									<form name="personal_<?php echo $idRecord ?>" id="personal_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("workorders/save_hour"); ?>">
										<input type="hidden" id="formType" name="formType" value="personal" />
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $data['fk_id_workorder']; ?>" />
										<input type="hidden" id="rate" name="rate" value="<?php echo $data['rate']; ?>" />
										<input type="hidden" id="check_pdf" name="check_pdf" value="<?php echo $data['view_pdf']; ?>" />
										<input type="hidden" id="quantity" name="quantity" value=1>

										<td>
											<select name="type_personal" id="type_personal" class="form-control">
												<option value=''>Select...</option>
												<?php for ($i = 0; $i < count($employeeTypeList); $i++) { ?>
													<option value="<?php echo $employeeTypeList[$i]["id_employee_type"]; ?>" <?php if ($data["fk_id_employee_type"] == $employeeTypeList[$i]["id_employee_type"]) {
																																	echo "selected";
																																}  ?>><?php echo $employeeTypeList[$i]["employee_type"]; ?></option>
												<?php } ?>
											</select>
										</td>

										<td>
											<textarea id="description" name="description" class="form-control" rows="3" required <?php echo $deshabilitar; ?>><?php echo $data['description']; ?></textarea>
										</td>

										<td>
											<input type="text" id="hours" name="hours" class="form-control" placeholder="Hours" value="<?php echo $data['hours']; ?>" required <?php echo $deshabilitar; ?>>
										</td>

										<td class='text-center'>
											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Save" <?php echo $deshabilitar; ?>>
												Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
									</form>

									<br><br>
									<?php if (!$deshabilitar) { ?>
										<a class='btn btn-danger btn-xs' href='<?php echo base_url('workorders/deleteRecord/personal/' . $data['id_workorder_personal'] . '/' . $data['fk_id_workorder'] . '/add_workorder') ?>' id="btn-delete">
											Delete <i class="fa fa-trash-o"></i>
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
		<!--FIN PERSONAL -->

		<!--INICIO MATERIALS -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-success">
					<div class="panel-heading">
						<b>MATERIALS AND SUPPLIES</b>
					</div>
					<div class="panel-body">
						<?php if (!$deshabilitar) { ?>
							<div class="col-lg-12">

								<button type="button" class="btn btn-success btn-block btn-material" data-toggle="modal" data-target="#modalMaterials" id="<?php echo 'material-' . $information[0]["id_workorder"]; //se coloca un ID diferente para que no entre en conflicto con los otros modales 
																																				?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Materials and Supplies
								</button><br>
							</div>
						<?php } ?>

						<?php
						if ($workorderMaterials) {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="success">
									<th class="text-center">Info. Material</th>
									<th class="text-center">Description</th>
									<th class="text-center">Quantity</th>
									<th class="text-center">Unit</th>
									<th class="text-center">Links</th>
								</tr>
								<?php
								foreach ($workorderMaterials as $data) :
									echo "<tr>";
									echo "<td ><small><strong>Material</strong><br>" . $data['material'] . "</small></td>";

									$idRecord = $data['id_workorder_materials'];
								?>
									<form name="material_<?php echo $idRecord ?>" id="material_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("workorders/save_hour"); ?>">
										<input type="hidden" id="formType" name="formType" value="materials" />
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $data['fk_id_workorder']; ?>" />
										<input type="hidden" id="rate" name="rate" value="<?php echo $data['rate']; ?>" />
										<input type="hidden" id="markup" name="markup" value="<?php echo $data['markup']; ?>" />
										<input type="hidden" id="check_pdf" name="check_pdf" value="<?php echo $data['view_pdf']; ?>" />
										<input type="hidden" id="hours" name="hours" value=1>

										<td>
											<textarea id="description" name="description" class="form-control" rows="3" required <?php echo $deshabilitar; ?>><?php echo $data['description']; ?></textarea>
										</td>

										<td>
											<input type="text" id="quantity" name="quantity" class="form-control" placeholder="Quantity" value="<?php echo $data['quantity']; ?>" required <?php echo $deshabilitar; ?>>
										</td>

										<td>
											<input type="text" id="unit" name="unit" class="form-control" placeholder="Unit" value="<?php echo $data['unit']; ?>" required <?php echo $deshabilitar; ?>>
										</td>

										<td class='text-center'>
											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Save" <?php echo $deshabilitar; ?>>
												Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
									</form>

									<br><br>
									<?php if (!$deshabilitar) { ?>
										<a class='btn btn-danger btn-xs' href='<?php echo base_url('workorders/deleteRecord/materials/' . $data['id_workorder_materials'] . '/' . $data['fk_id_workorder'] . '/add_workorder') ?>' id="btn-delete">
											Delete <i class="fa fa-trash-o"></i>
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

		<!--INICIO Receipt -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-violeta">
					<div class="panel-heading">
						<b>RECEIPTS</b>
					</div>
					<div class="panel-body">

						<?php if (!$deshabilitar) { ?>
							<div class="col-lg-12">

								<button type="button" class="btn btn-violeta btn-block" data-toggle="modal" data-target="#modalReceipt" id="<?php echo 'receipt-' . $information[0]["id_workorder"]; //se coloca un ID diferente para que no entre en conflicto con los otros modales 
																																			?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Receipts
								</button><br>
							</div>
						<?php } ?>

						<?php
						if ($workorderReceipt) {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="danger">
									<th class="text-center">Place</th>
									<th class="text-center">Price</th>
									<th class="text-center">Description</th>
									<th class="text-center">Links</th>
								</tr>
								<?php
								foreach ($workorderReceipt as $data) :
									echo "<tr>";
									$idRecord = $data['id_workorder_receipt'];
								?>
									<form name="invoice_<?php echo $idRecord ?>" id="invoice_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("workorders/update_receipt"); ?>">
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddidWorkorder" name="hddidWorkorder" value="<?php echo $data['fk_id_workorder']; ?>" />
										<input type="hidden" id="markup" name="markup" value="<?php echo $data['markup']; ?>" />
										<input type="hidden" id="check_pdf" name="check_pdf" value="<?php echo $data['view_pdf']; ?>" />
										<input type="hidden" id="view" name="view" value="add_workorder" />

										<td>
											<input type="text" id="place" name="place" class="form-control" placeholder="Place" value="<?php echo $data['place']; ?>" required <?php echo $deshabilitar; ?>>
										</td>

										<td>
											<input type="text" id="price" name="price" class="form-control" placeholder="Price" value="<?php echo $data['price']; ?>" required <?php echo $deshabilitar; ?>>
										</td>

										<td>
											<textarea id="description" name="description" class="form-control" rows="3" required <?php echo $deshabilitar; ?>><?php echo $data['description']; ?></textarea>
										</td>

										<td class='text-center'>
											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Save" <?php echo $deshabilitar; ?>>
												Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
									</form>

									<br><br>
									<?php if (!$deshabilitar) { ?>
										<a class='btn btn-danger btn-xs' href='<?php echo base_url('workorders/deleteRecord/receipt/' . $data['id_workorder_receipt'] . '/' . $data['fk_id_workorder'] . '/add_workorder') ?>' id="btn-delete">
											Delete <i class="fa fa-trash-o"></i>
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
		<!--FIN Receipt -->

		<!--INICIO EQUIPMENT -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<b>EQUIPMENT / RENTALS</b>
					</div>
					<div class="panel-body">
						<?php if (!$deshabilitar) { ?>
							<div class="col-lg-12">

								<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modalEquipment" id="<?php echo 'equipment-' . $information[0]["id_workorder"]; //se coloca un ID diferente para que no entre en conflicto con los otros modales 
																																			?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Equipment / Rentals
								</button><br>
							</div>
						<?php } ?>

						<?php
						if ($workorderEquipment) {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="info">
									<th class="text-center">Info. Equipment</th>
									<th class="text-center">Description</th>
									<th class="text-center">Hours</th>
									<th class="text-center">Quantity</th>
									<th class="text-center">Links</th>
								</tr>
								<?php
								foreach ($workorderEquipment as $data) :
									echo "<tr>";
									echo "<td ><small><strong>Type</strong><br>" . $data['type_2'] . "</small>";
									if ($data['fk_id_attachment'] != "" && $data['fk_id_attachment'] != 0) {
										echo "<p class='text-danger text-left'><small><strong>ATTACHMENT: </strong>" . $data["attachment_number"] . " - " . $data["attachment_description"] . "</small></p>";
									} else {
										echo "<br>";
									}
									//si es tipo miscellaneous -> 8, entonces la description es diferente
									if ($data['fk_id_type_2'] == 8) {
										$equipment = $data['miscellaneous'] . " - " . $data['other'];
										$description = $data['description'];
									} else {
										$equipment = "<em><b>Unit #: </b>" . $data['unit_number'] . "<br><b>Make: </b>" . $data['make'] . "<br><b>Model: </b>" . $data['model'] . "</em>";
										$description = $data['v_description'] . "<br>" . $data['description'];
									}
									echo "<small><strong>Equipment</strong><br>" . $equipment . "</small>";
									echo "<br><small><strong>Description</strong><br>" . $description . "</small>";
									if ($data['standby'] == 1) {
										echo "<br><small><strong>Standby?</strong> Yes</small>";
									} else {
										echo "<br><small><strong>Operated by</strong><br>" . $data['operatedby'] . "</small>";
									}

									echo "</td>";

									$idRecord = $data['id_workorder_equipment'];
								?>
									<form name="equipment_<?php echo $idRecord ?>" id="equipment_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("workorders/save_hour"); ?>">
										<input type="hidden" id="formType" name="formType" value="equipment" />
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $data['fk_id_workorder']; ?>" />
										<input type="hidden" id="check_pdf" name="check_pdf" value="<?php echo $data['view_pdf']; ?>" />
										<input type="hidden" id="rate" name="rate" value="<?php echo $data['rate']; ?>" />

										<td>
											<textarea id="description" name="description" class="form-control" rows="3" required <?php echo $deshabilitar; ?>><?php echo $data['description']; ?></textarea>
										</td>

										<td>
											<input type="text" id="hours" name="hours" class="form-control" placeholder="Hours" value="<?php echo $data['hours']; ?>" required <?php echo $deshabilitar; ?>>
										</td>
										<td>
											<input type="text" id="quantity" name="quantity" class="form-control" placeholder="Quantity" value="<?php echo $data['quantity']; ?>" required <?php echo $deshabilitar; ?>>
										</td>

										<td class='text-center'>
											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Save" <?php echo $deshabilitar; ?>>
												Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
									</form>

									<br><br>
									<?php if (!$deshabilitar) { ?>
										<a class='btn btn-danger btn-xs' href='<?php echo base_url('workorders/deleteRecord/equipment/' . $data['id_workorder_equipment'] . '/' . $data['fk_id_workorder'] . '/add_workorder') ?>' id="btn-delete">
											Delete <i class="fa fa-trash-o"></i>
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
		<!--FIN EQUIPMENT -->


		<!--INICIO SUBCONTRACTOR -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<b>SUb-Contractor</b>
					</div>
					<div class="panel-body">
						<?php if (!$deshabilitar) { ?>
							<div class="col-lg-12">

								<button type="button" class="btn btn-primary btn-block btn-ocasional" data-toggle="modal" data-target="#modalOcasional" id="<?php echo 'ocasional-' . $information[0]["id_workorder"]; //se coloca un ID diferente para que no entre en conflicto con los otros modales 
																																				?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Sub-Contractor
								</button><br>

							</div>
						<?php } ?>

						<?php
						if ($workorderOcasional) {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="primary">
									<th class="text-center">Info. Subcontractor</th>
									<th class="text-center">Description</th>
									<th class="text-center">Quantity</th>
									<th class="text-center">Unit</th>
									<th class="text-center">Hours</th>
									<th class="text-center">Links</th>
								</tr>
								<?php
								foreach ($workorderOcasional as $data) :
									echo "<tr>";
									echo "<td ><small><strong>Company</strong><br>" . $data['company_name'] . "</small>";
									echo "<br><small><strong>Equipment</strong><br>" . $data['equipment'] . "</small>";
									if($data['contact']){
									echo "<br><small><strong>Contact</strong><br>" . $data['contact'] . "</small>";
									}
									if($data['id_hauling']){
										echo "<br><br><a href='" . base_url('hauling/add_hauling/' . $data['id_hauling']) . "'>Hauling Card: " . $data['id_hauling'] . "</a>";
									}
									echo "</td>";

									$idRecord = $data['id_workorder_ocasional'];
								?>
									<form name="ocasional_<?php echo $idRecord ?>" id="ocasional_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("workorders/save_hour"); ?>">
										<input type="hidden" id="formType" name="formType" value="ocasional" />
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $data['fk_id_workorder']; ?>" />
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
												Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
									</form>

									<br><br>
									<?php if (!$deshabilitar) { ?>
										<a class='btn btn-danger btn-xs' href='<?php echo base_url('workorders/deleteRecord/ocasional/' . $data['id_workorder_ocasional'] . '/' . $data['fk_id_workorder'] . '/add_workorder') ?>' id="btn-delete">
											Delete <i class="fa fa-trash-o"></i>
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

	<?php } ?>

</div>

<!--INICIO Modal para PERSONAL -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>
<!--FIN Modal para PERSONAL -->


<!--INICIO Modal para MATERIAL -->
<div class="modal fade text-center" id="modalMaterials" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatosMaterial">

		</div>
	</div>
</div>
<!--FIN Modal para MATERIAL -->

<!--INICIO Modal para EQUIPMENT -->
<div class="modal fade text-center" id="modalEquipment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatosEquipment">

		</div>
	</div>
</div>
<!--FIN Modal para EQUIPMENT -->

<!--INICIO Modal para OCASIONAL-->
<div class="modal fade text-center" id="modalOcasional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatosOcasional">

		</div>
	</div>
</div>
<!--FIN Modal para OCASIONAL -->

<!--INICIO Modal para RECEIPT-->
<div class="modal fade text-center" id="modalReceipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatosReceipt">

		</div>
	</div>
</div>
<!--FIN Modal para RECEIPT -->