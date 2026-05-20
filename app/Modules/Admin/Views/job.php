<script>
	$(function() {
		$(".btn-outline").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + '/admin/cargar-modal-job',
				data: {
					'idJob': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatos').html(data);
				}
			});
		});
	});

	function seleccionar_todo() {
		for (i = 0; i < document.jobs_state.elements.length; i++)
			if (document.jobs_state.elements[i].type == "checkbox")
				document.jobs_state.elements[i].checked = 1
	}


	function deseleccionar_todo() {
		for (i = 0; i < document.jobs_state.elements.length; i++)
			if (document.jobs_state.elements[i].type == "checkbox")
				document.jobs_state.elements[i].checked = 0
	}
</script>

<script>
	$(document).ready(function() {
		$('#modal').on('shown.bs.modal', function() {
			if ($.fn.select2 && $('#company').hasClass("select2-hidden-accessible")) {
				$('#company').select2('destroy');
			}
			$('#company').select2({
				dropdownParent: $('#modal')
			});
		});
	});
</script>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
					<div>
						<a class="btn btn-primary btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> My Work Space </a> 
						<i class="fa fa-list fa-fw"></i> <b>PROJECTS</b>
					</div>
					<div>
						<?php
							$session = session();
							$userRol = $session->rol;
							if($userRol == ID_ROL_SUPER_ADMIN){ 
						?>
							<a href="<?php echo base_url("admin/job/log"); ?>" class="btn btn-outline btn-primary btn-block" style="background-color: #5067f0; color: #ffffff;">
								<i class="fa fa-briefcase"></i> LOGS
							</a>
						<?php } ?>
					</div>
				</div>

				<div class="panel-body">
					<?php 
						if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER || $userRol == ID_ROL_WORKORDER || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_ACCOUNTING){ 
					?>
						<ul class="nav nav-pills">
							<li <?php if ($state == 1) {
									echo "class='active'";
								} ?>><a href="<?php echo base_url("admin/job/1"); ?>">List of Active Job Code/Name</a>
							</li>
							<li <?php if ($state == 2) {
									echo "class='active'";
								} ?>><a href="<?php echo base_url("admin/job/2"); ?>">List of Inactive Job Code/Name</a>
							</li>
						</ul>
						<br>
						<button type="button" class="btn btn-outline btn-primary btn-block" data-toggle="modal" data-target="#modal" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Job Code/Name
						</button>
					<?php } ?>
					<br>
    <?php
    // Mensaje de éxito
    if ($session->getFlashdata('retornoExito')): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
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
					<?php
					if ($info) {
					?>

						<form name="jobs_state" id="jobs_state" method="post" action="<?php echo base_url("admin/jobs_state/$state"); ?>">

							<?php 
								$userRol = $session->rol;
								if($userRol == ID_ROL_SUPER_ADMIN){ 
							?>
							<!--
								<a href="javascript:seleccionar_todo()">Check all</a> |
								<a href="javascript:deseleccionar_todo()">Uncheck all</a>
							-->
							<?php } ?>

							<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
								<thead>
									<tr>
										<th class="text-center" width="20%">Job Code/Name</th>
										<th class="text-center" width="15%">Company</th>
										<th class="text-center" width="20%">Notes</th>
										<th class="text-center" width="5%">Automatic Planning Message</th>
										<!--
										<th class="text-center" width="5%">Status
											<button type="submit" class="btn btn-primary btn-xs" id="btnSubmit2" name="btnSubmit2">
												Update Status <span class="glyphicon glyphicon-edit" aria-hidden="true">
											</button>
										</th>
										-->
										<th class="text-center" width="40%">Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($info as $lista):
										echo "<tr>";
										echo "<td><h4>" . $lista['job_description'] . "</h4></td>";
										echo "<td>" . $lista['company_name'] . "</td>";
										echo "<td >" . $lista['notes'] . "</td>";
										echo "<td class='text-center'>";
										switch ($lista['planning_message']) {
											case 1:
												$valor = 'Yes';
												$clase = "text-success";
												break;
											case 2:
												$valor = 'No';
												$clase = "text-danger";
												break;
										}
										echo '<p class="' . $clase . '"><strong>' . $valor . '</strong></p>';
										echo "</td>";
										/***
										echo "<td class='text-center'>";
										switch ($lista['state']) {
											case 1:
												$valor = 'Active';
												$clase = "text-success";
												$estado = TRUE;
												break;
											case 2:
												$valor = 'Inactive';
												$clase = "text-danger";
												$estado = FALSE;
												break;
										}
										echo '<p class="' . $clase . '"><strong>' . $valor . '</strong>';

										$data = array(
											'name' => 'job[]',
											'id' => 'job',
											'value' => $lista['id_job'],
											'checked' => $estado,
											'style' => 'margin:10px'
										);
										echo form_checkbox($data);

										echo '</p>';
										echo "</td>";
										 */
										echo "<td class='text-center'>";
									?>

									<?php
										if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER || $userRol == ID_ROL_SAFETY || $userRol == ID_ROL_WORKORDER || $userRol == ID_ROL_SUPERVISOR || $userRol == ID_ROL_ENGINEER){ 
									?>
											<a class='btn btn-primary btn-xs' href='<?php echo base_url('programming/index/' . $lista['id_job']) ?>'>
												Planning <span class="fa fa-book" aria-hidden="true">
											</a>
									<?php
										}
									?>

									<?php
										if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_ACCOUNTING){ 
									?>
											<a class='btn btn-violeta btn-xs' href='<?php echo base_url('claims/' . $lista['id_job']) ?>'>
												Claims <span class="fa fa-book" aria-hidden="true">
											</a>
									<?php
										}
									?>

									<?php
										if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ENGINEER){ 
									?>
											<a class='btn btn-dark btn-xs' href='<?php echo base_url('jobs/job_detail/' . $lista['id_job']) ?>'>
												LIC <span class="fa fa-gears fa-fw" aria-hidden="true">
											</a>
									<?php
										}
									?>

									<?php
										if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER || $userRol == ID_ROL_WORKORDER || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_ACCOUNTING){ 
									?>
										<button type="button" class="btn btn-outline btn-primary btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_job']; ?>">
											Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
										</button>
									<?php 
										}
									?>

									<?php
										if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ENGINEER){ 
									?>
											<a class='btn btn-success btn-xs' href='<?php echo base_url('prices/employeeTypeUnitPrice/' . $lista['id_job']) ?>'>
												Employee Type Unit Price <span class="fa fa-flag fa-fw" aria-hidden="true">
											</a>

											<a class='btn btn-success btn-xs' href='<?php echo base_url('prices/equipmentUnitPrice/' . $lista['id_job'] . '/1') ?>'>
												Equipment Unit Price <span class="fa fa-flag fa-fw" aria-hidden="true">
											</a>

											<a class='btn btn-success btn-xs' href='<?php echo base_url('admin/job_qr_code/' . $lista['id_job'] . '/1') ?>'>
												Timesheet QR CODE <span class="fa fa-qrcode fa-fw" aria-hidden="true">
											</a>
									<?php
										}
									?>

									<?php
										echo "</td>";
									endforeach;
									?>
								</tbody>
							</table>

						</form>

					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!--INICIO Modal para adicionar -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>
<!--FIN Modal para adicionar -->

<!-- Tables -->
<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"pageLength": 100
		});
	});
</script>