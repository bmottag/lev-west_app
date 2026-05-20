<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<a class="btn btn-warning btn-xs" href="<?php echo base_url('jobs'); ?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back</a>
					<i class="fa fa-database"></i> <strong>CONFINED SPACE ENTRY PERMIT</strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-warning">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo esc($jobInfo[0]['job_description']); ?>
						</h2>
					</div>

					<a class='btn btn-outline btn-warning btn-block' href='<?php echo base_url('more/add_confined/' . $jobInfo[0]['id_job']); ?>'>
						<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span> Add a Confined Space Entry Permit
					</a>
					<br>

					<?php if (!empty($information)): ?>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th>#</th>
								<th>Reported by</th>
								<th>Date</th>
								<th>Location</th>
								<th>Purpose of entry</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($information as $lista): ?>
							<tr>
								<td class='text-center'><?php echo esc($lista['id_job_confined']); ?></td>
								<td><?php echo esc($lista['name']); ?></td>
								<td class='text-center'><?php echo esc($lista['date_confined']); ?></td>
								<td><?php echo esc($lista['location']); ?></td>
								<td><?php echo esc($lista['purpose']); ?></td>
								<td class='text-center'>
									<a class='btn btn-success btn-xs' href='<?php echo base_url('more/add_confined/' . $lista['fk_id_job'] . '/' . $lista['id_job_confined']); ?>'>
										Edit <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
									</a>
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

<script>
$(document).ready(function() {
	$('#dataTables').DataTable({ responsive: true, ordering: false, paging: false, searching: false, info: false });
});
</script>
