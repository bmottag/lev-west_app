<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> SETTINGS - VEHICLE PHOTO
					</h4>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->				
	</div>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a class="btn btn-success" href=" <?php echo base_url().'admin/vehicle/' . $vehicleInfo[0]["type_level_1"] . '/' . $vehicleInfo[0]["inspection_type"]; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-automobile"></i> VEHICLE PHOTO
				</div>
				<div class="panel-body">
					<?php $vehicle = $vehicleInfo[0] ?? null; ?>

					<form 
						method="post" 
						enctype="multipart/form-data"
						action="<?= base_url('admin/do_upload/photo/' . ($vehicle['type_level_1'] ?? 0)) ?>"
					>

						<?= csrf_field() ?>

						<input type="hidden" name="hddId" value="<?= esc($idVehicle) ?>"/>

						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Make</th>
									<th>Model</th>
									<th>Description</th>
									<th>Unit Number</th>
									<th>VIN Number</th>
									<th>Hours/Kilometers</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($vehicle): ?>
									<tr>
										<td class="text-center"><?= esc($vehicle['make']) ?></td>
										<td class="text-center"><?= esc($vehicle['model']) ?></td>
										<td><?= esc($vehicle['description']) ?></td>
										<td class="text-center"><?= esc($vehicle['unit_number']) ?></td>
										<td class="text-center"><?= esc($vehicle['vin_number']) ?></td>
										<td class="text-right">
											<strong><?= number_format($vehicle['hours'] ?? 0) ?></strong>
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>

						<?php if (!empty($vehicle['photo'])): ?>
							<div class="text-center mb-3">
								<img src="<?= base_url($vehicle['photo']) ?>" 
									class="img-rounded" 
									alt="Vehicle Photo" 
									style="max-width:70%;">
							</div>
						<?php endif; ?>

						<div class="panel panel-info">
							<div class="panel-heading">
								<b>Upload Equipment Photo</b>
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

								<div class="form-group">
									<label class="col-sm-4 control-label">Photo</label>
									<div class="col-sm-5">
										<input type="file" name="userfile" class="form-control" accept="image/png, image/jpeg, image/gif" required>
									</div>
								</div>

								<div class="text-center mt-3">
									<input type="submit" 
										value="Submit" 
										class="btn btn-primary"/>
								</div>

								<?php if (!empty($error)): ?>
									<div class="alert alert-danger mt-3">
										<strong>Error:</strong><br>
										<?= is_array($error) ? implode('<br>', $error) : esc($error) ?>
									</div>
								<?php endif; ?>
								<br>
								<div class="form-group">
									<div class="alert alert-danger">
										<strong>Note:</strong><br>
										Allowed format: gif - jpg - png<br>
										Maximum size: 3000 KB<br>
										Maximum width: 2024 pixels<br>
										Maximum height: 2008 pixels<br>
									</div>
								</div>

							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
			"ordering": false,
			paging: false,
		"searching": false,
		"info": false
	});
});
</script>