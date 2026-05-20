<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-gris btn-xs" href=" <?php echo base_url() . 'workorders/search/y'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-money"></i> <strong>WORK ORDERS</strong>
				</div>
				<div class="panel-body">

					<ul class="nav nav-pills">
						<li><a href="<?php echo base_url('workorders/add_workorder/' . $information[0]["id_workorder"]) ?>">Edit</a>
						</li>
						<li><a href="<?php echo base_url('workorders/view_workorder/' . $information[0]["id_workorder"]) ?>">Asign rate</a>
						</li>
						<li><a href="<?php echo base_url('workorders/generaWorkOrderPDF/' . $information[0]["id_workorder"]) ?>" target="_blank">Download invoice</a>
						</li>
						<?php
						$userRol = session()->get("rol");
						if (($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_WORKORDER) && $information[0]['state'] != 4) {
						?>
							<li><a href="<?php echo base_url('workorders/workorder_expenses/' . $information[0]["id_workorder"]) ?>">Workorder Expenses</a>
							</li>
						<?php } ?>
						<li class='active'><a href="<?php echo base_url('workorders/subcontractor_invoices/' . $information[0]["id_workorder"]) ?>">Subcontractors Invoices</a></li>

					</ul>
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
								$icono = "fa-list-alt";
								break;
						}
					?>
						<div class="row">
							<div class="col-lg-12">
								<div class="alert <?php echo $clase; ?>">
									<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									This work order is <strong><?php echo $valor; ?></strong>
								</div>
							</div>
						</div>

					<?php } ?>

					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-info">
								<span class='fa fa-money' aria-hidden='true'></span>
								<strong>Work Order #: </strong><?php echo $information[0]["id_workorder"]; ?>
								<br><span class='fa fa-clock-o' aria-hidden='true'></span> <strong>Work Order Date: </strong><?php echo $information[0]["date"]; ?>
								<br><span class="fa fa-briefcase" aria-hidden="true"></span> <strong>Job Code/Name: </strong><?php echo $information[0]["job_description"]; ?>
								<?php if ($information[0]["notes"]) { ?>
									<br><strong>Job Code/Name - Notes: </strong><?php echo $information[0]["notes"]; ?>
								<?php } ?>
								<br><strong>Markup: </strong><?php echo $information[0]["markup"] . '%'; ?>
								<br><strong>Supervisor: </strong><?php echo $information[0]["name"]; ?>
								<br><strong>Observation: </strong><?php echo $information[0]["observation"]; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--INICIO SUBCONTRACTOR -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<b>SUBCONTRACTOR </b>
				</div>
				<div class="panel-body">
					<table class="table table-bordered table-striped table-hover table-condensed">
						<tr class="primary">
							<th class="text-center">Info. Subcontractor</th>
							<th class="text-center">Value</th>
							<th class="text-center">Invoice</th>
							<th class="text-center">Save</th>
						</tr>
						<?php
						foreach ($workorderOcasional as $data) :
							$invoicesList = $invoicesMap[$data['fk_id_company']] ?? [];

							echo "<tr>";
							$hours = $data['hours'] == 0 ? 1 : $data['hours'];
							$idRecord = $data['id_workorder_ocasional'];
						?>
							<form name="ocasional_<?php echo $idRecord ?>" id="ocasional_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("workorders/save_subcontractor_invoices"); ?>">
								<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
								<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $data['fk_id_workorder']; ?>" />
								<?php
								echo "<td ><small><strong>Company</strong><br>" . $data['company_name'] . "</small>";
								echo "<br><small><strong>Equipment</strong><br>" . $data['equipment'] . "</small>";
								echo "<br><small><strong>Contact</strong><br>" . $data['contact'] . "</small>";
								echo "<br><small><strong>Description</strong><br>" . $data['description'] . "</small></td>";
								?>
								<td class='text-right'><small>$ <?php echo $data['value']; ?></small></td>
								<td>
									<?php if($invoicesList): ?>
									<select name="idInvoices" id="idInvoices" class="form-control" required>
										<option value=''>Select...</option>
										<?php for ($i = 0; $i < count($invoicesList); $i++) { ?>
											<option value="<?php echo $invoicesList[$i]["id_subcontractor_invoice"]; ?>" 
												<?php if ($data['fk_id_subcontractor_invoice'] == $invoicesList[$i]["id_subcontractor_invoice"]) { echo "selected"; }  ?>
												><?php echo "Invoice # " . $invoicesList[$i]["invoice_number"] . " Amount: $ " . $invoicesList[$i]["invoice_amount"]; ?>
											</option>
										<?php } ?>
									</select>
									<?php endif; ?>
								</td>
								<td class='text-center'>

									<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Update" <?php echo $deshabilitar; ?>>
										<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>

								</td>
							</form>
							</tr>
						<?php
						endforeach;
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!--FIN SUBCONTRACTOR -->

</div>