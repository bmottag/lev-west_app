<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-purpura">
				<div class="panel-heading">
					<a class="btn btn-purpura btn-xs" href="<?php echo base_url('jobs'); ?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back</a>
					<i class="glyphicon glyphicon-screenshot"></i> <strong>ESI - ENVIROMENTAL SITE INSPECTION</strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-purpura">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo esc($jobInfo[0]['job_description']); ?>
						</h2>
					</div>

					<a class='btn btn-outline btn-purpura btn-block' href='<?php echo base_url('more/add_environmental/' . $jobInfo[0]['id_job']); ?>'>
						<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span> Add an Enviromental Site Inspection
					</a>
					<br>

					<?php if ($information): ?>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th>Reported by</th>
								<th>Date review</th>
								<th>Site inspector</th>
								<th>Manager</th>
								<th>Download</th>
								<th>Review</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($information as $lista): ?>
							<tr>
								<td><?php echo esc($lista['name']); ?></td>
								<td class='text-center'><?php echo esc($lista['date_environmental']); ?></td>
								<td><?php echo $lista['inspector'] ? esc($lista['inspector']) : "<p class='text-danger'>This field is missing.</p>"; ?></td>
								<td><?php echo $lista['manager'] ? esc($lista['manager']) : "<p class='text-danger'>This field is missing.</p>"; ?></td>
								<td class='text-center'>
									<?php if ($lista['manager']): ?>
									<a href='<?php echo base_url('more/generaEnvironmentalPDF/' . $lista['fk_id_job']); ?>' target="_blank"><img src='<?php echo base_url('images/pdf.png'); ?>'></a>
									<?php else: ?>-<?php endif; ?>
								</td>
								<td class='text-center'>
									<a class='btn btn-success btn-xs' href='<?php echo base_url('more/add_environmental/' . $lista['fk_id_job'] . '/' . $lista['id_job_environmental']); ?>'>
										Review <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
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
