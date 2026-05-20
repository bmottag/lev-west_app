<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<a class="btn btn-info btn-xs" href=" <?php echo base_url().'jobs'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-fire-extinguisher"></i> <strong>JSO - JOB SITE ORIENTATION</strong>
				</div>
				<div class="panel-body">
				
					<div class="alert alert-info">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
					</div>					
				
					<a class='btn btn-outline btn-info btn-block' href='<?php echo base_url('jobs/add_jso/' . $jobInfo[0]['id_job']) ?>'>
							<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span>  Add a JSO
					</a>
					
					<br>
				<?php
					if($information){
				?>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class='text-center'>#</th>
								<th class='text-center'>Manager</th>
								<th class='text-center'>Date</th>
								<th class='text-center'>Edit</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($information as $lista):
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['id_job_jso'] . "</td>";
									echo "<td>" . $lista['manager'] . "</td>";
									echo "<td class='text-center'>" . $lista['date_issue_jso'] . "</td>";							
									echo "<td class='text-center'>";									
						?>
									<a class='btn btn-info btn-xs' href='<?php echo base_url('jobs/add_jso/' . $lista['fk_id_job'] . '/' . $lista['id_job_jso'] ) ?>'>
											Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</a>								
						<?php
									echo "</td>";
									echo "</tr>";
							endforeach;
						?>
						</tbody>
					</table>
				<?php } ?>

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