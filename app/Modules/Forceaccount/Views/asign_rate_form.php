<script>
	$(function() {

		$(".load_rates").click(function() {
			var oID = $(this).attr("id");

			//Activa icono guardando
			if (window.confirm('Are you sure you want to load the general configuration data?')) {
				$(".load_rates").attr('disabled', '-1');
				$.ajax({
					type: 'POST',
					url: base_url + 'forceaccount/load_prices_wo',
					data: {
						'identificador': oID
					},
					cache: false,
					success: function(data) {

						if (data.status == "error") {
							alert(data.mensaje);
							$(".load_rates").removeAttr('disabled');
							return false;
						}

						if (data.status === "success") {
							$(".load_rates").removeAttr('disabled');
							window.location.href = base_url + "forceaccount/view_forceaccount/" + data.idWO;
						} else {
							alert('Error. Reload the web page.');
							$(".load_rates").removeAttr('disabled');
						}
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$(".load_rates").removeAttr('disabled');
					}

				});
			}
		});

		$(".btn-amarello").click(function() {
			var oID = $(this).attr("id");

			//Activa icono guardando
			if (window.confirm('Are you sure you want to load the Markup?')) {
				$(".btn-amarello").attr('disabled', '-1');
				$.ajax({
					type: 'POST',
					url: base_url + 'forceaccount/load_markup_wo',
					data: {
						'identificador': oID
					},
					cache: false,
					success: function(data) {

						if (data.status == "error") {
							alert(data.mensaje);
							$(".btn-amarello").removeAttr('disabled');
							return false;
						}

						if (data.status === "success") {
							$(".btn-amarello").removeAttr('disabled');

							window.location.href = base_url + "forceaccount/view_forceaccount/" + data.idWO;
						} else {
							alert('Error. Reload the web page.');
							$(".btn-amarello").removeAttr('disabled');
						}
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$(".btn-amarello").removeAttr('disabled');
					}

				});
			}
		});

		$(".personal_modal").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'forceaccount/cargarModalPersonal',
				data: {
					'idForceaccount': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatos').html(data);
				}
			});
		});

		$(".material_modal").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'forceaccount/cargarModalMaterials',
				data: {
					'idForceaccount': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatosMaterial').html(data);
				}
			});
		});

		$(".equipment_modal").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'forceaccount/cargarModalEquipment',
				data: {
					'idForceaccount': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatosEquipment').html(data);
				}
			});
		});

		$(".ocasional_modal").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'forceaccount/cargarModalOcasional',
				data: {
					'idForceaccount': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatosOcasional').html(data);
				}
			});
		});

		$(".receipt_modal").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'forceaccount/cargarModalReceipts',
				data: {
					'idForceaccount': oID
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
			<div class="panel panel-warning">
				<div class="panel-heading">
					<a class="btn btn-warning btn-xs" href=" <?php echo base_url() . 'forceaccount/search/y'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-money"></i> <strong>FORCE ACCOUNT</strong>
				</div>
				<div class="panel-body">

					<ul class="nav nav-pills">
						<li><a href="<?php echo base_url('forceaccount/add_forceaccount/' . $information[0]["id_forceaccount"]) ?>">Edit</a>
						</li>
						<li class='active'><a href="<?php echo base_url('forceaccount/view_forceaccount/' . $information[0]["id_forceaccount"]) ?>">Asign rate</a>
						</li>
						<li><a href="<?php echo base_url('forceaccount/generaForceAccountPDF/' . $information[0]["id_forceaccount"]) ?>" target="_blank">Download Force Account</a>
						</li>
					<?php
					$userRol = session()->get("rol");
					if (($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_WORKORDER) && $information[0]['state'] != 4) {
					?>
						<li><a href="<?php echo base_url('forceaccount/forceaccount_expenses/' . $information[0]["id_forceaccount"]) ?>">Workorder Expenses</a>
						</li>
					<?php } ?>
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
									This Force Account is <strong><?php echo $valor; ?></strong>
								</div>
							</div>
						</div>

					<?php } ?>

					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-info">
								<span class='fa fa-money' aria-hidden='true'></span>
								<strong>Force Account #: </strong><?php echo $information[0]["id_forceaccount"]; ?>
								<br><span class='fa fa-clock-o' aria-hidden='true'></span> <strong>Force Account Date: </strong><?php echo $information[0]["date"]; ?>
								<br><span class="fa fa-briefcase" aria-hidden="true"></span> <strong>Job Code/Name: </strong><?php echo $information[0]["job_description"]; ?>
								<?php if ($information[0]["notes"]) { ?>
									<br><strong>Job Code/Name - Notes: </strong><?php echo $information[0]["notes"]; ?>
								<?php } ?>
								<br><strong>Markup: </strong><?php echo $information[0]["markup"] . '%'; ?>
								<br><strong>Supervisor: </strong><?php echo $information[0]["name"]; ?>
								<br><strong>Observation: </strong><?php echo $information[0]["observation"]; ?>

								<br><br>
								<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
								Update Rates from the following button. <small>(Update the rate field on PERSONAL, MATERIALS and EQUIPMENT) </small>
								<button type="button" id="<?php echo $information[0]["id_forceaccount"]; ?>" class='btn btn-default load_rates btn-xs' title="Update" <?php echo $deshabilitar; ?>>
									Update Rates <i class="fa fa-refresh"></i>
								</button>

								<?php if ($information[0]["markup"] > 0) { ?>
									<br><br>
									<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
									Update Job Code/Name Markup button. <small>(Update the Markup field on MATERIALS, RECEIPT and OCASIONAL) </small>
									<button type="button" id="<?php echo $information[0]["id_forceaccount"]; ?>" class='btn btn-amarello btn-xs' title="Update" <?php echo $deshabilitar; ?>>
										Update Markup <i class="fa fa-refresh"></i>
									</button>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php if($information && empty($information[0]['signature_wo'])) { ?>
						<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-danger">
									<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									<strong>This Force Account is not valid until it has been signed by the client representative.</strong>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<!--INICIO FORMULARIOS -->
	<?php if ($information) { ?>

		<!--INICIO PERSONAL -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<b>PERSONNEL</b>
					</div>
					<div class="panel-body">
						<div class="col-lg-12">

							<?php if (!$deshabilitar) { ?>
								<button type="button" class="btn btn-warning personal_modal btn-block" data-toggle="modal" data-target="#modal" id="<?php echo $information[0]["id_forceaccount"]; ?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Personnel
								</button><br>
							<?php } ?>
						</div>
						<?php
						if (!$forceaccountPersonal) {
							echo '<div class="col-lg-12">
				<small>
				<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No data was found.</p>
				</small>
			</div>';
						} else {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="warning">
									<th class="text-center">PDF</th>
									<th class="text-center">Employee Name</th>
									<th class="text-center">Employee Type</th>
									<th class="text-center">Hours</th>
									<th class="text-center">Work Done</th>
									<th class="text-center">Rate</th>
									<th class="text-center">Value</th>
									<th class="text-center">Save</th>
									<th class="text-center">Delete</th>
								</tr>
								<?php
								foreach ($forceaccountPersonal as $data) :
									echo "<tr>";
									$idRecord = $data['id_forceaccount_personal'];
								?>
									<form name="personal_<?php echo $idRecord ?>" id="personal_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("forceaccount/save_rate"); ?>">
										<input type="hidden" id="formType" name="formType" value="personal" />
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $data['fk_id_forceaccount']; ?>" />
										<input type="hidden" id="hours" name="hours" value="<?php echo $data['hours']; ?>" />
										<input type="hidden" id="description" name="description" value="<?php echo $data['description']; ?>" />
										<input type="hidden" id="quantity" name="quantity" value=1>
										<input type="hidden" id="type_personal" name="type_personal" value="<?php echo $data['fk_id_employee_type']; ?>">
										
										<?php
										echo "<td class='text-center'>";
										$checkPerso = '';
										if ($data['view_pdf'] == 1) {
											$checkPerso = 'checked';
										}
										?>
										<input type="checkbox" id="check_pdf" name="check_pdf" <?php echo $checkPerso; ?>>
										<?php
										echo "</td>";
										echo "<td>";
										echo '<small>' . $data['name'] . '</small></td>';
										echo "<td ><small>" . $data['employee_type'] . "</small></td>";
										echo "<td class='text-center'><small>" . $data['hours'] . "</small></td>";
										echo "<td ><small>" . $data['description'] . "</small></td>";
										?>
										<td>
											<input type="text" id="rate" name="rate" class="form-control" placeholder="Rate" value="<?php echo $data['rate']; ?>" required>
										</td>
										<td class='text-right'><small>$ <?php echo $data['value']; ?></small></td>
										<td class='text-center'>
											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Update" <?php echo $deshabilitar; ?>>
												<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
										</td>
									</form>

									<td class='text-center'>
										<?php if (!$deshabilitar) { ?>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('forceaccount/deleteRecord/personal/' . $data['id_forceaccount_personal'] . '/' . $data['fk_id_forceaccount'] . '/view_forceaccount') ?>' id="btn-delete">
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
							<div class="col-lg-12">
								<small>
									<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <strong>Value = </strong>
										Hours X Rate
									</p>
								</small>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<!--FIN PERSONAL -->

		<!--INICIO MATERIALS -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<b>MATERIALS AND SUPPLIES</b>
					</div>
					<div class="panel-body">
						<div class="col-lg-12">

							<?php if (!$deshabilitar) { ?>
								<button type="button" class="btn btn-warning material_modal btn-block" data-toggle="modal" data-target="#modalMaterials" id="<?php echo 'material-' . $information[0]["id_forceaccount"]; //se coloca un ID diferente para que no entre en conflicto con los otros modales 
																																				?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Materials and Supplies
								</button><br>
							<?php } ?>
						</div>
						<?php
						if (!$forceaccountMaterials) {
							echo '<div class="col-lg-12">
				<small>
				<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No data was found.</p>
				</small>
			</div>';
						} else {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="warning">
									<th class="text-center">PDF</th>
									<th class="text-center">Info. Material</th>
									<th class="text-center">Quantity</th>
									<th class="text-center">Unit</th>
									<th class="text-center">Rate</th>
									<th class="text-center">Markup</th>
									<th class="text-center">Value</th>
									<th class="text-center">Save</th>
									<th class="text-center">Delete</th>
								</tr>
								<?php
								foreach ($forceaccountMaterials as $data) :
									echo "<tr>";
									$idRecord = $data['id_forceaccount_materials'];
								?>
									<form name="material_<?php echo $idRecord ?>" id="material_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("forceaccount/save_rate"); ?>">
										<input type="hidden" id="formType" name="formType" value="materials" />
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $data['fk_id_forceaccount']; ?>" />
										<input type="hidden" id="quantity" name="quantity" value="<?php echo $data['quantity']; ?>" />
										<input type="hidden" id="hours" name="hours" value=1 />
										<input type="hidden" id="description" name="description" value="<?php echo $data['description']; ?>" />
										<input type="hidden" id="unit" name="unit" value="<?php echo $data['unit']; ?>" />
										<?php
										echo "<td class='text-center'>";
										$checkMaterial = '';
										if ($data['view_pdf'] == 1) {
											$checkMaterial = 'checked';
										}
										?>
										<input type="checkbox" id="check_pdf" name="check_pdf" <?php echo $checkMaterial; ?>>
										<?php
										echo "</td>";
										echo "<td ><small><strong>Material</strong><br>" . $data['material'] . "</small>";
										echo "<br><small><strong>Description</strong><br>" . $data['description'] . "</small></td>";
										echo "<td class='text-right'><small>" . $data['quantity'] . "</small></td>";
										echo "<td ><small>" . $data['unit'] . "</small></td>";
										?>
										<td>
											<input type="text" id="rate" name="rate" class="form-control" placeholder="Rate" value="<?php echo $data['rate']; ?>" required>
										</td>

										<td>
											<input type="text" id="markup" name="markup" class="form-control" placeholder="Markup" value="<?php echo $data['markup']; ?>" required>
										</td>

										<td class='text-right'><small>$ <?php echo $data['value']; ?></small></td>
										<td class='text-center'>
											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Update" <?php echo $deshabilitar; ?>>
												<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
										</td>
									</form>
									<td class='text-center'>
										<?php if (!$deshabilitar) { ?>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('forceaccount/deleteRecord/materials/' . $data['id_forceaccount_materials'] . '/' . $data['fk_id_forceaccount'] . '/view_forceaccount') ?>' id="btn-delete">
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
							<div class="col-lg-12">
								<small>
									<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <strong>Value = </strong>
										Quantity X Rate X (Markup + 100)/100
									</p>
								</small>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<!--FIN MATERIALS -->

		<!--INICIO INVOICE -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<b>RECEIPTS</b>
					</div>
					<div class="panel-body">
						<div class="col-lg-12">

							<?php if (!$deshabilitar) { ?>
								<button type="button" class="btn btn-warning receipt_modal btn-block" data-toggle="modal" data-target="#modalReceipt" id="<?php echo 'receipt-' . $information[0]["id_forceaccount"]; //se coloca un ID diferente para que no entre en conflicto con los otros modales ?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Receipts
								</button><br>
							<?php } ?>
						</div>
						<?php
						if (!$forceaccountReceipt) {
							echo '<div class="col-lg-12">
				<small>
				<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No data was found.</p>
				</small>
			</div>';
						} else {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="warning">
									<th class="text-center">PDF</th>
									<th class="text-center">Place</th>
									<th class="text-center">Price with GST</th>
									<th class="text-center">Description</th>
									<th class="text-center">Markup</th>
									<th class="text-center">Value</th>
									<th class="text-center">Save</th>
									<th class="text-center">Delete</th>
								</tr>
								<?php
								foreach ($forceaccountReceipt as $data) :
									echo "<tr>";
									$idRecord = $data['id_forceaccount_receipt'];
								?>
									<form name="invoice_<?php echo $idRecord ?>" id="invoice_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("forceaccount/update_receipt"); ?>">
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddidForceaccount" name="hddidForceaccount" value="<?php echo $data['fk_id_forceaccount']; ?>" />
										<input type="hidden" id="place" name="place" value="<?php echo $data['place']; ?>" />
										<input type="hidden" id="description" name="description" value="<?php echo $data['description']; ?>" />
										<input type="hidden" id="view" name="view" value="view_forceaccount" />
										<?php
										echo "<td class='text-center'>";
										$checkReceipt = '';
										if ($data['view_pdf'] == 1) {
											$checkReceipt = 'checked';
										}
										?>
										<input type="checkbox" id="check_pdf" name="check_pdf" <?php echo $checkReceipt; ?>>
										<?php
										echo "</td>";
										echo "<td><small>" . $data['place'] . "</small></td>";
										?>
										<td>
											<input type="text" id="price" name="price" class="form-control" placeholder="Price" value="<?php echo $data['price']; ?>" required>
										</td>
										<?php
										echo "<td><small>" . $data['description'] . "</small></td>";
										?>
										<td>
											<input type="text" id="markup" name="markup" class="form-control" placeholder="Markup" value="<?php echo $data['markup']; ?>" required>

										</td>
										<td class='text-right'><small>$ <?php echo $data['value']; ?></small></td>
										<td class='text-center'>
											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Update" <?php echo $deshabilitar; ?>>
												<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>
										</td>
									</form>
									<td class='text-center'>
										<?php if (!$deshabilitar) { ?>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('forceaccount/deleteRecord/receipt/' . $data['id_forceaccount_receipt'] . '/' . $data['fk_id_forceaccount'] . '/view_forceaccount') ?>' id="btn-delete">
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

							<div class="col-lg-12">
								<small>
									<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <strong>Value = </strong>
										Price/1.05 X (Markup + 100)/100
									</p>
								</small>
							</div>

						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<!--FIN INVOICE -->

		<!--INICIO EQUIPMENT -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<b>EQUIPMENT / RENTALS</b>
					</div>
					<div class="panel-body">
						<div class="col-lg-12">

							<?php if (!$deshabilitar) { ?>
								<button type="button" class="btn btn-warning equipment_modal btn-block" data-toggle="modal" data-target="#modalEquipment" id="<?php echo 'equipment-' . $information[0]["id_forceaccount"]; //se coloca un ID diferente para que no entre en conflicto con los otros modales 
																																			?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Equipment / Rentals
								</button><br>
							<?php } ?>
						</div>
						<?php
						if (!$forceaccountEquipment) {
							echo '<div class="col-lg-12">
				<small>
				<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No data was found.</p>
				</small>
			</div>';
						} else {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="warning">
									<th class="text-center">PDF</th>
									<th class="text-center">Info. Equipment</th>
									<th class="text-center">Hours</th>
									<th class="text-center">Quantity</th>
									<th class="text-center">Rate</th>
									<th class="text-center">Value</th>
									<th class="text-center">Save</th>
									<th class="text-center">Delete</th>
								</tr>
								<?php
								foreach ($forceaccountEquipment as $data) :
									echo "<tr>";
									$idRecord = $data['id_forceaccount_equipment'];
									$quantity = $data['quantity'] == 0 ? 1 : $data['quantity'];
								?>
									<form name="equipment_<?php echo $idRecord ?>" id="equipment_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("forceaccount/save_rate"); ?>">
										<input type="hidden" id="formType" name="formType" value="equipment" />
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $data['fk_id_forceaccount']; ?>" />
										<input type="hidden" id="hours" name="hours" value="<?php echo $data['hours']; ?>" />
										<input type="hidden" id="quantity" name="quantity" value="<?php echo $quantity; ?>" />
										<input type="hidden" id="description" name="description" value="<?php echo $data['description']; ?>" />
										<?php
										echo "<td class='text-center'>";
										$checkReceipt = '';
										if ($data['view_pdf'] == 1) {
											$checkReceipt = 'checked';
										}
										?>
										<input type="checkbox" id="check_pdf" name="check_pdf" <?php echo $checkReceipt; ?>>
										<?php
										echo "</td>";

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

										echo "<br><small><strong>Equipment</strong><br>" . $equipment . "</small>";
										echo "<br><small><strong>Description</strong><br>" . $description . "</small>";
										if ($data['standby'] == 1) {
											echo "<br><small><strong>Standby?</strong> Yes</small>";
										} else {
											echo "<br><small><strong>Operated by</strong><br>" . $data['operatedby'] . "</small>";
										}

										if ($data['company_name']) {
											echo "<br><small><strong>Client</strong><br>" . $data['company_name'] . "</small> ";
										}

										if ($data['foreman_name']) {
											echo "<br><small><strong>Foreman's name</strong><br>" . $data['foreman_name'] . "</small> ";
										}

										if ($data['foreman_email']) {
											echo "<br><small><strong>Foreman's email</strong><br>" . $data['foreman_email'] . "</small> ";
										}
										echo "</td>";

										echo "<td class='text-right'><small>" . $data['hours'] . "</small></td>";
										echo "<td class='text-right'><small>" . $quantity . "</small></td>";
										?>
										<td>
											<input type="text" id="rate" name="rate" class="form-control" placeholder="Rate" value="<?php echo $data['rate']; ?>" required>
										</td>
										<td class='text-right'><small>$ <?php echo $data['value']; ?></small></td>
										<td class='text-center'>

											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Update" <?php echo $deshabilitar; ?>>
												<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>

										</td>
									</form>

									<td class='text-center'>
										<?php if (!$deshabilitar) { ?>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('forceaccount/deleteRecord/equipment/' . $data['id_forceaccount_equipment'] . '/' . $data['fk_id_forceaccount'] . '/view_forceaccount') ?>' id="btn-delete">
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

		<!--FIN EQUIPMENT -->

		<!--INICIO SUBCONTRACTOR -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<b>SUb-Contractor</b>
					</div>
					<div class="panel-body">
						<div class="col-lg-12">

							<?php if (!$deshabilitar) { ?>
								<button type="button" class="btn btn-warning ocasional_modal btn-block" data-toggle="modal" data-target="#modalOcasional" id="<?php echo 'ocasional-' . $information[0]["id_forceaccount"]; //se coloca un ID diferente para que no entre en conflicto con los otros modales 
																																				?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Sub-Contractor
								</button><br>
							<?php } ?>
						</div>
						<?php
						if (!$forceaccountOcasional) {
							echo '<div class="col-lg-12">
				<small>
				<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> No data was found.</p>
				</small>
			</div>';
						} else {
						?>
							<table class="table table-bordered table-striped table-hover table-condensed">
								<tr class="warning">
									<th class="text-center">PDF</th>
									<th class="text-center">Info. Subcontractor</th>
									<th class="text-center">Quantity</th>
									<th class="text-center">Unit</th>
									<th class="text-center">Hours</th>
									<th class="text-center">Rate</th>
									<th class="text-center">Markup</th>
									<th class="text-center">Value</th>
									<th class="text-center">Save</th>
									<th class="text-center">Delete</th>
								</tr>
								<?php
								foreach ($forceaccountOcasional as $data) :
									echo "<tr>";
									$hours = $data['hours'] == 0 ? 1 : $data['hours'];
									$idRecord = $data['id_forceaccount_ocasional'];
								?>
									<form name="ocasional_<?php echo $idRecord ?>" id="ocasional_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("forceaccount/save_rate"); ?>">
										<input type="hidden" id="formType" name="formType" value="ocasional" />
										<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>" />
										<input type="hidden" id="hddIdWorkOrder" name="hddIdWorkOrder" value="<?php echo $data['fk_id_forceaccount']; ?>" />
										<input type="hidden" id="quantity" name="quantity" value="<?php echo $data['quantity']; ?>" />
										<input type="hidden" id="hours" name="hours" value="<?php echo $hours; ?>" />
										<input type="hidden" id="description" name="description" value="<?php echo $data['description']; ?>" />
										<input type="hidden" id="unit" name="unit" value="<?php echo $data['unit']; ?>" />
										<?php
										echo "<td class='text-center'>";
										$checkReceipt = '';
										if ($data['view_pdf'] == 1) {
											$checkReceipt = 'checked';
										}
										?>
										<input type="checkbox" id="check_pdf" name="check_pdf" <?php echo $checkReceipt; ?>>
										<?php
										echo "</td>";
										echo "<td ><small><strong>Company</strong><br>" . $data['company_name'] . "</small>";
										echo "<br><small><strong>Equipment</strong><br>" . $data['equipment'] . "</small>";
										echo "<br><small><strong>Contact</strong><br>" . $data['contact'] . "</small>";
										echo "<br><small><strong>Description</strong><br>" . $data['description'] . "</small></td>";
										echo "<td class='text-right'><small>" . $data['quantity'] . "</small></td>";
										echo "<td ><small>" . $data['unit'] . "</small></td>";
										echo "<td class='text-right'><small>" . $hours . "</small></td>";
										?>
										<td>
											<input type="text" id="rate" name="rate" class="form-control" placeholder="Rate" value="<?php echo $data['rate']; ?>" required>
										</td>
										<td>
											<input type="text" id="markup" name="markup" class="form-control" placeholder="Markup" value="<?php echo $data['markup']; ?>" required>
										</td>
										<td class='text-right'><small>$ <?php echo $data['value']; ?></small></td>
										<td class='text-center'>

											<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Update" <?php echo $deshabilitar; ?>>
												<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
											</button>

										</td>
									</form>
									<td class='text-center'>
										<?php if (!$deshabilitar) { ?>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('forceaccount/deleteRecord/ocasional/' . $data['id_forceaccount_ocasional'] . '/' . $data['fk_id_forceaccount'] . '/view_forceaccount') ?>' id="btn-delete">
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
							<div class="col-lg-12">
								<small>
									<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <strong>Value = </strong>
										Quantity X Hours X Rate X (Markup + 100)/100
									</p>
								</small>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<!--FIN SUBCONTRACTOR -->

	<?php } ?>

</div>

<!--INICIO Modal para PERSONAL -->
<div class="modal fade text-center" id="modalExpense" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" id="tablaDatosExpense">

		</div>
	</div>
</div>
<!--FIN Modal para PERSONAL -->

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