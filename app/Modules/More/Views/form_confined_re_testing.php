<script>
$(function() {
	$('.btn-info').click(function() {
		var oID = $(this).attr('id');
		$.ajax({
			type: 'POST',
			url: base_url + 'more/cargarModalRetesting',
			data: { 'idConfined': oID, 'idRetesting': 'x' },
			cache: false,
			success: function(data) { $('#tablaDatos').html(data); }
		});
	});

	$('.btn-success').click(function() {
		var oID = $(this).attr('id');
		$.ajax({
			type: 'POST',
			url: base_url + 'more/cargarModalRetesting',
			data: { 'idConfined': '', 'idRetesting': oID },
			cache: false,
			success: function(data) { $('#tablaDatos').html(data); }
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
					<a class="btn btn-warning btn-xs" href="<?php echo base_url('more/confined/' . $jobInfo[0]['id_job']); ?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back</a>
					<i class="fa fa-cube"></i> <strong>CONFINED SPACE ENTRY PERMIT FORM</strong>
				</div>
				<div class="panel-body">
					<?php if ($information): ?>
					<ul class="nav nav-pills">
						<li><a href="<?php echo base_url('more/add_confined/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">FORM</a></li>
						<li><a href="<?php echo base_url('more/confined_workers/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">ENTRANT(S)</a></li>
						<li><a href="<?php echo base_url('more/workers_site/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">WORKERS ON SITE</a></li>
						<li class="active"><a href="<?php echo base_url('more/re_testing/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">ENVIRONMENTAL CONDITIONS - RE-TESTING</a></li>
						<li><a href="<?php echo base_url('more/post_entry/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">POST ENTRY INSPECTION</a></li>
					</ul>
					<br>
					<?php endif; ?>

					<div class="alert alert-warning">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo esc($jobInfo[0]['job_description']); ?>
						</h2>
						<br><span class="fa fa-clock-o" aria-hidden="true"></span> <strong>Date: </strong>
						<?php if ($information): ?>
							<?php echo esc($information[0]['date_confined']); ?>
							<br><span class="fa fa-cloud-download" aria-hidden="true"></span> <strong>Download Confined Entry Permit Form: </strong>
							<a href="<?php echo base_url('more/generaConfinedPDF/' . $information[0]['id_job_confined']); ?>" target="_blank">PDF <img src="<?php echo base_url('images/pdf.png'); ?>"></a>
						<?php else: ?>
							<?php echo date('Y-m-d'); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-building"></i> <strong>Environmental conditions - Re-testing</strong>
				</div>
				<div class="panel-body">
					<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modal" id="<?php echo esc($information[0]['id_job_confined']); ?>">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Re-testing
					</button><br>

					<?php if (session()->getFlashdata('retornoExito')): ?>
					<div class="col-lg-12">
						<div class="alert alert-success">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							<?php echo session()->getFlashdata('retornoExito'); ?>
						</div>
					</div>
					<?php endif; ?>
					<?php if (session()->getFlashdata('retornoError')): ?>
					<div class="col-lg-12">
						<div class="alert alert-danger">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<?php echo session()->getFlashdata('retornoError'); ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ($info): ?>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">Oxygen</th>
								<th class="text-center">Date/Time</th>
								<th class="text-center">Lower Explosive Limit</th>
								<th class="text-center">Date/Time</th>
								<th class="text-center">Toxic Atmosphere</th>
								<th class="text-center">Instruments Used</th>
								<th class="text-center">Edit</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($info as $lista): ?>
							<tr>
								<td class="text-center"><?php echo esc($lista['re_oxygen']); ?> %</td>
								<td class="text-center"><?php echo esc($lista['re_oxygen_time']); ?></td>
								<td class="text-center"><?php echo esc($lista['re_explosive_limit']); ?> %</td>
								<td class="text-center"><?php echo esc($lista['re_explosive_limit_time']); ?></td>
								<td><?php echo esc($lista['re_toxic_atmosphere']); ?></td>
								<td><?php echo esc($lista['re_instruments_used']); ?></td>
								<td class="text-center">
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_job_confined_re_testing']; ?>">
										Edit <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
									</button>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos"></div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#dataTables').DataTable({ responsive: true, pageLength: 100 });
});
</script>
