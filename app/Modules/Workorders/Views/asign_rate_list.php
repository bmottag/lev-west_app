<script type="text/javascript" src="<?php echo base_url("assets/js/validate/workorder/update_state.js?v=1.0.0"); ?>"></script>

<script>
	function seleccionar_todo() {
		for (i = 0; i < document.formState.elements.length; i++)
			if (document.formState.elements[i].type == "checkbox")
				document.formState.elements[i].checked = 1
	}


	function deseleccionar_todo() {
		for (i = 0; i < document.formState.elements.length; i++)
			if (document.formState.elements[i].type == "checkbox")
				document.formState.elements[i].checked = 0
	}
</script>

<div id="page-wrapper">
	<br>

	<div class="row">

		<form name="formState" id="formState" class="form-horizontal" method="post">

			<div class="col-lg-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						ADDITIONAL INFORMATION
					</div>
					<div class="panel-body">

						<div class="col-lg-12">

							<small>
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
									Change the status of multiple work orders at the same time, select the W.O. you want to change status.
								</p>
							</small>

							<?php
							/**
							 * Estado work order
							 * Solo se puede editar por los siguientes ROLES
							 * SUPER ADMIN, MANAGEMENT, ACCOUNTING
							 */
							$userRol = session()->get("rol");
							$deshabilitar = 'disabled';
							if ($userRol == ID_ROL_SUPER_ADMIN || $userRol == 2 || $userRol == 3) {
								$deshabilitar = '';
							}
							?>

							<div class="form-group">
								<label for="state">State :</label>
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

							<div class="form-group">
								<label for="information">Additional information :</label>
								<textarea id="information" name="information" class="form-control" rows="3" placeholder="Additional information" required <?php echo $deshabilitar; ?>></textarea>
							</div>

							<div class="form-group">

								<?php if (!$deshabilitar) { ?>
									<a href="javascript:seleccionar_todo()">Check all</a> |
									<a href="javascript:deseleccionar_todo()">Uncheck all</a>
								<?php } ?>


								<div class="form-group">
									<div id="div_load" style="display:none">
										<div class="progress progress-striped active">
											<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
												<span class="sr-only">45% completado</span>
											</div>
										</div>
									</div>
									<div id="div_error" style="display:none">	
										<div class="alert alert-danger">
											<span class="glyphicon glyphicon-remove"></span>
											<span id="span_msj"></span>
										</div>		
									</div>	
								</div>
								<?php if (!$deshabilitar) { ?>
									<div class="row" align="center">
										<div style="width:100%;" align="center">

											<button type="button" class="btn btn-primary btn-xs" id="btnState" name="btnState">
												Update <span class="glyphicon glyphicon-edit" aria-hidden="true">
											</button>

										</div>
									</div>
								<?php } ?>
							</div>

							<small>
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
									Make sure to fill up all necessary information.
								</p>
							</small>

						</div>

					</div>
				</div>
			</div>

			<div class="col-lg-8">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<a class="btn btn-info btn-xs" href=" <?php echo base_url('workorders/search'); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Back to Search W.O.</a>
						<i class="fa fa-money"></i> <strong>WORK ORDERS</strong>
					</div>
					<div class="panel-body">

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
						if (!$workOrderInfo) {
							echo "<a href='#' class='btn btn-danger btn-block'>No data was found matching your criteria</a>";
						} else {
						?>

							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
								<thead>
									<tr>
										<th class='text-center'>W.O. #</th>
										<th class='text-center'>Ventures Code</th>
										<th class='text-center'>Supervisor</th>
										<th class='text-center'>Date of Issue</th>
										<th class='text-center'>Date W.O.</th>
										<th class='text-center'>More Information</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($workOrderInfo as $lista) :

										switch ($lista['state']) {
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

										echo "<tr>";

										echo "<td class='text-center'>";

										if (!$deshabilitar) {
											$data = array(
												'name' => 'wo[]',
												'id' => 'wo',
												'value' => $lista['id_workorder'],
												'checked' => False,
												'style' => 'margin:10px'
											);
											echo form_checkbox($data);
										}

										echo "<a href='" . base_url('workorders/add_workorder/' . $lista['id_workorder']) . "'>" . $lista['id_workorder'] . "</a>";
										echo '<p class="' . $clase . '"><i class="fa ' . $icono . ' fa-fw"></i>' . $valor . '</p>';
										if (($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_WORKORDER) && $lista['expenses_flag'] == 1) {
											echo '<p class="text-dark"><i class="fa fa-flag fa-fw"></i> With Expenses</p>';
										}
										echo "<a class='btn btn-success btn-xs' href='" . base_url('workorders/add_workorder/' . $lista['id_workorder']) . "'> Edit <span class='glyphicon glyphicon-edit' aria-hidden='true'></a>";
										echo "</td>";
										echo "<td>" . $lista['job_description'] . "</td>";
										echo "<td>" . $lista['name'] . "</td>";
										echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
										echo "<td class='text-center'>" . $lista['date'] . "</td>";
										echo '<td>';
										echo '<strong>Work Done:</strong><br>' . $lista['observation'];
										echo '<p class="text-info"><strong>Last message:</strong><br>' . $lista['last_message'] . '</p>';
										echo '</td>';
										echo '</tr>';
									endforeach;
									?>
								</tbody>
							</table>

						<?php } ?>

					</div>


					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</form>

	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->

<!-- Tables -->
<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"info": false,
			"searching": false
		});
	});
</script>