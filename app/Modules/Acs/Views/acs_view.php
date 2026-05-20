<script>
	$(function() {
		$(".personal_modal").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'acs/cargarModalPersonalACS',
				data: {
					'idACS': oID
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
				url: base_url + 'acs/cargarModalMaterialsACS',
				data: {
					'idACS': oID
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
				url: base_url + 'acs/cargarModalEquipmentACS',
				data: {
					'idACS': oID
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
				url: base_url + 'acs/cargarModalOcasionalACS',
				data: {
					'idACS': oID
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
				url: base_url + 'acs/cargarModalReceiptsACS',
				data: {
					'idACS': oID
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
		<div class="col-lg-6">
			<div class="panel panel-dark">
				<div class="panel-heading">
					<i class="fa fa-edit"></i> <strong>WT's Expense Control - General Information</strong>
				</div>
				<div class="panel-body">
					<a href='<?php echo base_url('workorders/add_workorder/' . $acs_info[0]["fk_id_workorder"]); ?>"'>W.O. # <?php echo $acs_info[0]["fk_id_workorder"]; ?> </a><br>
					<strong>WEC Date: </strong><?php echo $acs_info[0]["date"]; ?><br>
					<strong>Venture Code: </strong><br><?php echo $acs_info[0]["job_description"]; ?><br>
					<strong>Foreman: </strong><?php echo $acs_info[0]["foreman_name_wo"]; ?><br>
					<strong>Work Completed: </strong><br><?php echo $acs_info[0]["observation"]; ?>
					<br><strong>Download to: </strong>
					<a href='<?php echo base_url('acs/reportPDF/' . $acs_info[0]["id_acs"]); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>'></a>
				</div>
			</div>
		</div>	
		
		<div class="col-lg-6">
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
		</div>
	</div>

<!--INICIO PERSONNEL -->
<?php 
	if($acsPersonal){
?>
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-dark">
				<div class="panel-heading">
					<strong>Manpower</strong>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
						<button type="button" class="btn btn-dark btn-block personal_modal" data-toggle="modal" data-target="#modal" id="<?php echo $acs_info[0]["id_acs"]; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Manpower
						</button><br>
					</div>
					<form id="form_acs_personal" method="post" action="<?php echo base_url("acs/save_info_acs_personal"); ?>">
						<input type="hidden" id="hddIdACS" name="hddIdACS" value="<?php echo $acs_info[0]["id_acs"]; ?>" />
						<input type="hidden" id="formType" name="formType" value="personal" />
						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="dark">
								<th class="text-center" style="width: 5%;">PDF</th>
								<th class="text-center" style="width: 15%;">Employee Name</th>
								<th class="text-center" style="width: 15%;">Manpower Type</th>
								<th class="text-center" style="width: 36%;">Work Complted</th>
								<th class="text-center" style="width: 8%;">Hours</th>
								<th class="text-center" style="width: 8%;">Rate</th>
								<th class="text-center" style="width: 8%;">Value</th>
								<th class="text-center" style="width: 5%;">Delete</th>
							</tr>
								<?php foreach ($acsPersonal as $data): ?>
									<?php $idRecord = $data['id_acs_personal']; ?>
									<tr>
										<td class="text-center">
											<input type="checkbox" name="records[<?php echo $idRecord; ?>][check_pdf]" <?php echo $data['view_pdf'] == 1 ? 'checked' : ''; ?>>
										</td>
										<td>
											<small><?php echo $data['name']; ?></small>
										</td>
										<td>
											<small><?php echo $data['employee_type']; ?></small>
										</td>
										<td>
											<small><?php echo $data['description']; ?></small>
										</td>
										<td class="text-center">
											<input type="text" name="records[<?php echo $idRecord; ?>][hours]" class="form-control" placeholder="Hours" value="<?php echo $data['hours']; ?>" required>
										</td>
										<td>
											<input type="text" name="records[<?php echo $idRecord; ?>][rate]" class="form-control" placeholder="Rate" value="<?php echo $data['rate']; ?>" required>
										</td>
										<td class="text-right">
											<small>$ <?php echo $data['value']; ?></small>
										</td>

										<td class='text-center'>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('acs/deleteACSRecord/personal/' . $idRecord . '/' . $acs_info[0]["id_acs"] . '/view_acs') ?>' id="btn-delete">
												<i class="fa fa-trash-o"></i>
											</a>
										</td>
										<input type="hidden" name="records[<?php echo $idRecord; ?>][hddId]" value="<?php echo $idRecord; ?>">
									</tr>
								<?php endforeach; ?>
						</table>
						<div class="col-lg-12">
							<small>
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <strong>Value = </strong>
									Hours X Rate
								</p>
							</small>
						</div>
						<div class="text-center">
							<button type="submit" id="btnSubmitPersonalEdit" name="btnSubmitPersonalEdit" class="btn btn-dark">
								Save All Personal Information <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN PERSONNEL -->

<!--INICIO MATERIALS -->
<?php 
	if($acsMaterials){
?>
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-dark">
				<div class="panel-heading">
					<b>MATERIALS</b>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
						<button type="button" class="btn btn-dark btn-block material_modal" data-toggle="modal" data-target="#modalMaterials" id="<?php echo 'material-' . $acs_info[0]["id_acs"]; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Material(s)
						</button><br>
					</div>
					<form id="form_acs_material" method="post" action="<?php echo base_url("acs/save_info_acs_materials"); ?>">
						<input type="hidden" id="hddIdACS" name="hddIdACS" value="<?php echo $acs_info[0]["id_acs"]; ?>" />
						<input type="hidden" id="formType" name="formType" value="materials" />
						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="dark">
								<th class="text-center" style="width: 5%;">PDF</th>
								<th class="text-center" style="width: 50%;">Info. Material</th>
								<th class="text-center" style="width: 8%;">Quantity</th>
								<th class="text-center" style="width: 8%;">Unit</th>
								<th class="text-center" style="width: 8%;">Rate</th>
								<th class="text-center" style="width: 8%;">Markup</th>
								<th class="text-center" style="width: 8%;">Value</th>
								<th class="text-center" style="width: 5%;">Delete</th>
							</tr>
								<?php foreach ($acsMaterials as $data): ?>
									<?php $idRecord = $data['id_acs_materials']; ?>
									<tr>
										<td class="text-center">
											<input type="checkbox" name="records[<?php echo $idRecord; ?>][check_pdf]" <?php echo $data['view_pdf'] == 1 ? 'checked' : ''; ?>>
										</td>
										<td>
											<small><strong>Material</strong><br><?php echo $data['material']; ?></small>
											<br><small><strong>Description</strong><br><?php echo $data['description']; ?></small>
										</td>
										<td>
											<input type="text" name="records[<?php echo $idRecord; ?>][quantity]" class="form-control" placeholder="Quantity" value="<?php echo $data['quantity']; ?>" required>
										</td>
										<td>
											<small><?php echo $data['unit']; ?></small>
										</td>
										<td>
											<input type="text" name="records[<?php echo $idRecord; ?>][rate]" class="form-control" placeholder="Rate" value="<?php echo $data['rate']; ?>" required>
										</td>
										<td class="text-center">
											<input type="text" name="records[<?php echo $idRecord; ?>][markup]" class="form-control" placeholder="Markup" value="<?php echo $data['markup']; ?>" required>
										</td>
										<td class="text-right">
											<small>$ <?php echo $data['value']; ?></small>
										</td>

										<td class='text-center'>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('acs/deleteACSRecord/materials/' . $idRecord . '/' . $acs_info[0]["id_acs"] . '/view_acs') ?>' id="btn-delete">
												<i class="fa fa-trash-o"></i>
											</a>
										</td>
										<input type="hidden" name="records[<?php echo $idRecord; ?>][hddId]" value="<?php echo $idRecord; ?>">
									</tr>
								<?php endforeach; ?>
						</table>
						<div class="col-lg-12">
							<small>
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <strong>Value = </strong>
								Quantity X Rate X (Markup + 100)/100
								</p>
							</small>
						</div>
						<div class="text-center">
							<button type="submit" id="btnSubmitMaterialEdit" name="btnSubmitMaterialEdit" class="btn btn-dark">
								Save All Material Information <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN MATERIALS -->

<!--INICIO RECEIPT -->
<?php 
	if($acsReceipt){
?>
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-dark">
				<div class="panel-heading">
					<b>Purchase Slip</b>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
						<button type="button" class="btn btn-dark btn-block receipt_modal" data-toggle="modal" data-target="#modalReceipt" id="<?php echo 'receipt-' . $acs_info[0]["id_acs"]; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Purchase Slip
						</button><br>
					</div>
					<form id="form_acs_receipt" method="post" action="<?php echo base_url("acs/save_info_acs_receipt"); ?>">
						<input type="hidden" id="hddIdACS" name="hddIdACS" value="<?php echo $acs_info[0]["id_acs"]; ?>" />
						<input type="hidden" id="formType" name="formType" value="receipt" />
						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="dark">
								<th class="text-center" style="width: 5%;">PDF</th>
								<th class="text-center" style="width: 30%;">Place</th>
								<th class="text-center" style="width: 36%;">Item's Description</th>
								<th class="text-center" style="width: 8%;">Price with GST</th>
								<th class="text-center" style="width: 8%;">Markup</th>
								<th class="text-center" style="width: 8%;">Value</th>
								<th class="text-center" style="width: 5%;">Delete</th>
							</tr>
								<?php foreach ($acsReceipt as $data): ?>
									<?php $idRecord = $data['id_acs_receipt']; ?>
									<tr>
										<td class="text-center">
											<input type="checkbox" name="records[<?php echo $idRecord; ?>][check_pdf]" <?php echo $data['view_pdf'] == 1 ? 'checked' : ''; ?>>
										</td>
										<td>
											<small><?php echo $data['place']; ?></small>
										</td>
										<td>
											<small><?php echo $data['description']; ?></small>
										</td>
										<td class="text-center">
											<input type="text" name="records[<?php echo $idRecord; ?>][price]" class="form-control" placeholder="Price" value="<?php echo $data['price']; ?>" required>
										</td>
										<td>
											<input type="text" name="records[<?php echo $idRecord; ?>][markup]" class="form-control" placeholder="Markup" value="<?php echo $data['markup']; ?>" required>
										</td>
										<td class="text-right">
											<small>$ <?php echo $data['value']; ?></small>
										</td>

										<td class='text-center'>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('acs/deleteACSRecord/receipt/' . $idRecord . '/' . $acs_info[0]["id_acs"] . '/view_acs') ?>' id="btn-delete">
												<i class="fa fa-trash-o"></i>
											</a>
										</td>
										<input type="hidden" name="records[<?php echo $idRecord; ?>][hddId]" value="<?php echo $idRecord; ?>">
									</tr>
								<?php endforeach; ?>
						</table>
						<div class="col-lg-12">
							<small>
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <strong>Value = </strong>
									Price/1.05 X (Markup + 100)/100
								</p>
							</small>
						</div>
						<div class="text-center">
							<button type="submit" id="btnSubmitReceiptEdit" name="btnSubmitReceiptEdit" class="btn btn-dark">
								Save All Receipt Information <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN MATERIALS -->

<!--INICIO EQUIPMENT -->
<?php 
	if($acsEquipment){
?>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-dark">
				<div class="panel-heading">
					<b>Machinery</b>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
						<button type="button" class="btn btn-dark btn-block equipment_modal" data-toggle="modal" data-target="#modalEquipment" id="<?php echo 'equipment-' . $acs_info[0]["id_acs"]; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Machinery & Rentals
						</button><br>
					</div>
					<form id="form_acs_equipment" method="post" action="<?php echo base_url("acs/save_info_acs_equipment"); ?>">
						<input type="hidden" id="hddIdACS" name="hddIdACS" value="<?php echo $acs_info[0]["id_acs"]; ?>" />
						<input type="hidden" id="formType" name="formType" value="equipment" />
						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="dark">
								<th class="text-center" style="width: 5%;">PDF</th>
								<th class="text-center" style="width: 28%;">Machinery's Info</th>
								<th class="text-center" style="width: 30%;">Description</th>
								<th class="text-center" style="width: 8%;">Hours</th>
								<th class="text-center" style="width: 8%;">Quantity</th>
								<th class="text-center" style="width: 8%;">Rate</th>
								<th class="text-center" style="width: 8%;">Value</th>
								<th class="text-center" style="width: 5%;">Delete</th>
							</tr>
								<?php foreach ($acsEquipment as $data): ?>
									<?php $idRecord = $data['id_acs_equipment']; ?>
									<?php $quantity = $data['quantity'] == 0 ? 1 : $data['quantity']; ?>
									<tr>
										<td class="text-center">
											<input type="checkbox" name="records[<?php echo $idRecord; ?>][check_pdf]" <?php echo $data['view_pdf'] == 1 ? 'checked' : ''; ?>>
										</td>
										<td>
										<?php
										echo "<small><strong>Type</strong><br>" . $data['type_2'] . "</small>";
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
										if ($data['standby'] == 1) {
											echo "<br><small><strong>Standby?</strong> Yes</small>";
										} else {
											echo "<br><small><strong>Operated by</strong><br>" . $data['operatedby'] . "</small>";
										}

										if ($data['company_name']) {
											echo "<br><small><strong>Client</strong><br>" . $data['company_name'] . "</small> ";
										}
										?>
										</td>
										<td>
											<small><?php echo $description; ?></small>
										</td>
										<td class="text-center">
											<input type="text" name="records[<?php echo $idRecord; ?>][hours]" class="form-control" placeholder="Hours" value="<?php echo $data['hours']; ?>" required>
										</td>
										<td class="text-center">
											<input type="text" name="records[<?php echo $idRecord; ?>][quantity]" class="form-control" placeholder="Quantity" value="<?php echo $quantity; ?>" required>
										</td>
										<td>
											<input type="text" name="records[<?php echo $idRecord; ?>][rate]" class="form-control" placeholder="Rate" value="<?php echo $data['rate']; ?>" required>
										</td>
										<td class="text-right">
											<small>$ <?php echo $data['value']; ?></small>
										</td>

										<td class='text-center'>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('acs/deleteACSRecord/equipment/' . $idRecord . '/' . $acs_info[0]["id_acs"] . '/view_acs') ?>' id="btn-delete">
												<i class="fa fa-trash-o"></i>
											</a>
										</td>
										<input type="hidden" name="records[<?php echo $idRecord; ?>][hddId]" value="<?php echo $idRecord; ?>">
									</tr>
								<?php endforeach; ?>
						</table>
						<div class="col-lg-12">
							<small>
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <strong>Value = </strong>
									Hours X Quantity X Rate
								</p>
							</small>
						</div>
						<div class="text-center">
							<button type="submit" id="btnSubmitEquipmentEdit" name="btnSubmitEquipmentEdit" class="btn btn-dark">
								Save All Equipment Information <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN EQUIPMENT -->

<!--INICIO SUBCONTRACTOR -->
<?php 
	if($acsOcasional){
?>
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-dark">
				<div class="panel-heading">
					<b>SUb-Contractor</b>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
						<button type="button" class="btn btn-dark btn-block ocasional_modal" data-toggle="modal" data-target="#modalOcasional" id="<?php echo 'ocasional-' . $acs_info[0]["id_acs"]; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Sub-Contractor
						</button><br>
					</div>
					<form id="form_acs_subcontractor" method="post" action="<?php echo base_url("acs/save_info_acs_ocasional"); ?>">
						<input type="hidden" id="hddIdACS" name="hddIdACS" value="<?php echo $acs_info[0]["id_acs"]; ?>" />
						<input type="hidden" id="formType" name="formType" value="ocasional" />
						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="dark">
								<th class="text-center" style="width: 5%;">PDF</th>
								<th class="text-center" style="width: 42%;">Sub-contractor's Info</th>
								<th class="text-center" style="width: 8%;">Quantity</th>
								<th class="text-center" style="width: 8%;">Unit</th>
								<th class="text-center" style="width: 8%;">Hours</th>
								<th class="text-center" style="width: 8%;">Rate</th>
								<th class="text-center" style="width: 8%;">Markup</th>
								<th class="text-center" style="width: 8%;">Value</th>
								<th class="text-center"style="width: 5%;">Delete</th>
							</tr>
								<?php foreach ($acsOcasional as $data): ?>
									<?php $idRecord = $data['id_acs_ocasional']; ?>
									<?php $hours = $data['hours'] == 0 ? 1 : $data['hours']; ?>
									<tr>
										<td class="text-center">
											<input type="checkbox" name="records[<?php echo $idRecord; ?>][check_pdf]" <?php echo $data['view_pdf'] == 1 ? 'checked' : ''; ?>>
										</td>
										<td>
											<small><strong>Subs Company Name</strong><br><?php echo $data['company_name']; ?></small>
											<br><small><strong>Machinery or Unit</strong><br><?php echo $data['equipment']; ?></small>
											<br><small><strong>On-site Contact or Operator</strong><br><?php echo $data['contact']; ?></small>
											<br><small><strong>Job or Task Perfomed</strong><br><?php echo $data['description']; ?></small>
										</td>
										<td>
											<input type="text" name="records[<?php echo $idRecord; ?>][quantity]" class="form-control" placeholder="Quantity" value="<?php echo $data['quantity']; ?>" required>
										</td>
										<td>
											<small><?php echo $data['unit']; ?></small>
										</td>
										<td class="text-center">
											<input type="text" name="records[<?php echo $idRecord; ?>][hours]" class="form-control" placeholder="Hours" value="<?php echo $hours; ?>" required>
										</td>
										<td>
											<input type="text" name="records[<?php echo $idRecord; ?>][rate]" class="form-control" placeholder="Rate" value="<?php echo $data['rate']; ?>" required>
										</td>
										<td>
											<input type="text" name="records[<?php echo $idRecord; ?>][markup]" class="form-control" placeholder="Markup" value="<?php echo $data['markup']; ?>" required>
										</td>
										<td class="text-right">
											<small>$ <?php echo $data['value']; ?></small>
										</td>

										<td class='text-center'>
											<a class='btn btn-danger btn-xs' href='<?php echo base_url('acs/deleteACSRecord/ocasional/' . $idRecord . '/' . $acs_info[0]["id_acs"] . '/view_acs') ?>' id="btn-delete">
												<i class="fa fa-trash-o"></i>
											</a>
										</td>
										<input type="hidden" name="records[<?php echo $idRecord; ?>][hddId]" value="<?php echo $idRecord; ?>">
									</tr>
								<?php endforeach; ?>
						</table>
						<div class="col-lg-12">
							<small>
								<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> <strong>Value = </strong>
									Quantity X Hours X Rate X (Markup + 100)/100
								</p>
							</small>
						</div>
						<div class="text-center">
							<button type="submit" id="btnSubmitSubcontractorEdit" name="btnSubmitSubcontractorEdit" class="btn btn-dark">
								Save All Occasional Subcontractor Information <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN SUBCONTRACTOR -->
</div>


<!--INICIO Modal para PERSONNEL -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>
<!--FIN Modal para PERSONNEL -->

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