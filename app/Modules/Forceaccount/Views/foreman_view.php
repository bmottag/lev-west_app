<div id="page-wrapper">
	<br>

	<div class="row">

		<div class="col-lg-6">
			<div class="panel panel-info">
				<div class="panel-heading">
				<?php
					if(session()->get('id')) {
				?>
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url() . 'forceaccount/add_forceaccount/' . $information[0]["id_forceaccount"]; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
				<?php } ?>
					<i class="fa fa-edit"></i> <strong>FORCE ACCOUNT - GENERAL INFORMATION</strong>
				</div>
				<div class="panel-body">

					<strong>Force Account #: </strong><?php echo $information[0]["id_forceaccount"]; ?><br>
					<strong>Force Account Date: </strong><?php echo $information[0]["date"]; ?><br>
					<strong>Job Code/Name: </strong><br><?php echo $information[0]["job_description"]; ?><br>
					<strong>Foreman: </strong><?php echo $information[0]["foreman_name_wo"]; ?><br>
					<strong>Work Done: </strong><br><?php echo $information[0]["observation"]; ?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-edit fa-fw"></i> <b>Foreman Signature</b>
				</div>
				<div class="panel-body">

					<div class="form-group">
						<?= view('App\Views\template\signature_component', [
							'imageUrl'   => $information[0]['signature_wo'] ?? null,
							'formAction' => base_url('forceaccount/save_signature'),
							'hiddenName' => 'image',
							'height'     => 200,
							'id' 		 => $information[0]['id_forceaccount']
						]) ?>
					</div>
					
				</div>
			</div>
		</div>

	</div>

	<!--INICIO PERSONNEL -->
	<?php
	if ($forceaccountPersonal) {
	?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<b>PERSONNEL</b>
					</div>
					<div class="panel-body">

						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="warning">
								<td>
									<p class="text-center"><strong>Employee Name</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Employee Type</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Work Done</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Hours</strong></p>
								</td>
							</tr>
							<?php
							foreach ($forceaccountPersonal as $data):
								echo "<tr>";
								echo "<td ><small>" . $data['name'] . "</small></td>";
								echo "<td ><small>" . $data['employee_type'] . "</small></td>";
								echo "<td ><small>" . $data['description'] . "</small></td>";
								echo "<td class='text-center'><small>" . $data['hours'] . "</small></td>";
								echo "</tr>";
							endforeach;
							?>
						</table>

					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<!--FIN PERSONNEL -->

	<!--INICIO MATERIALS -->
	<?php
	if ($forceaccountMaterials) {
	?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-success">
					<div class="panel-heading">
						<b>MATERIALS</b>
					</div>
					<div class="panel-body">

						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="success">
								<td>
									<p class="text-center"><strong>Info. Material</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Description</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Quantity</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Unit</strong></p>
								</td>
							</tr>
							<?php
							foreach ($forceaccountMaterials as $data):
								echo "<tr>";
								echo "<td ><small><strong>Material</strong><br>" . $data['material'] . "</small></td>";
								echo "<td ><small>" . $data['description'] . "</small></td>";
								echo "<td class='text-center'><small>" . $data['quantity'] . "</small></td>";
								echo "<td><small>" . $data['unit'] . "</small></td>";
								echo "</tr>";
							endforeach;
							?>
						</table>

					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<!--FIN MATERIALS -->

	<!--INICIO EQUIPMENT -->
	<?php
	if ($forceaccountEquipment) {
	?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<b>EQUIPMENT</b>
					</div>
					<div class="panel-body">


						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="info">
								<td>
									<p class="text-center"><strong>Info. Equipment</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Description</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Hours</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Quantity</strong></p>
								</td>
							</tr>
							<?php
							foreach ($forceaccountEquipment as $data):
								echo "<tr>";
								echo "<td ><small><strong>Type</strong><br>" . $data['type_2'] . "</small>";
								//si es tipo miscellaneous -> 8, entonces la description es diferente
								if ($data['fk_id_type_2'] == 8) {
									$equipment = $data['miscellaneous'] . " - " . $data['other'];
								} else {
									$equipment = $data['unit_number'] . " - " . $data['make'] . " - " . $data['model'];
								}

								echo "<br><small><strong>Equipment</strong><br>" . $equipment . "</small>";
								echo "<br><small><strong>Operated by</strong><br>" . $data['operatedby'] . "</small>";
								echo "</td>";
								echo "<td ><small>" . $data['description'] . "</small></td>";
								echo "<td class='text-center'><small>" . $data['hours'] . "</small></td>";
								echo "<td class='text-center'><small>" . $data['quantity'] . "</small></td>";
								echo "</tr>";
							endforeach;
							?>
						</table>

					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<!--FIN EQUIPMENT -->


	<!--INICIO SUBCONTRACTOR -->
	<?php
	if ($forceaccountOcasional) {
	?>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<b>SUb-Contractor</b>
					</div>
					<div class="panel-body">

						<table class="table table-bordered table-striped table-hover table-condensed">
							<tr class="primary">
								<td>
									<p class="text-center"><strong>Info. Subcontractor</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Description</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Quantity</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Unit</strong></p>
								</td>
								<td>
									<p class="text-center"><strong>Hours</strong></p>
								</td>
							</tr>
							<?php
							foreach ($forceaccountOcasional as $data):
								echo "<tr>";
								echo "<td ><small><strong>Company</strong><br>" . $data['company_name'] . "</small>";
								echo "<br><small><strong>Equipment</strong><br>" . $data['equipment'] . "</small>";
								echo "<br><small><strong>Contact</strong><br>" . $data['contact'] . "</small></td>";
								echo "<td ><small>" . $data['description'] . "</small></td>";
								echo "<td class='text-center'><small>" . $data['quantity'] . "</small></td>";
								echo "<td ><small>" . $data['unit'] . "</small></td>";
								echo "<td class='text-center'><small>" . $data['hours'] . "</small></td>";
								echo "</tr>";
							endforeach;
							?>
						</table>

					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<!--FIN SUBCONTRACTOR -->

</div>