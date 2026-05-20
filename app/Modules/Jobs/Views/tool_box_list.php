<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<a class="btn btn-warning btn-xs" href=" <?php echo base_url().'jobs'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-cube"></i> <strong>IHSR</strong>
				</div>
				<div class="panel-body">
				
					<div class="alert alert-warning">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
					</div>					
				
					<a class='btn btn-outline btn-warning btn-block' href='<?php echo base_url('jobs/add_tool_box/' . $jobInfo[0]['id_job']) ?>'>
							<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span>  Add IHSR
					</a>
					
					<br>
				<?php
					if($information){
				?>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th>#</th>
								<th>Reported by</th>
								<th>Date</th>
								<th>Activities of the Day</th>
								<th>Employee Suggestions</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($information as $lista):
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['id_tool_box'] . "</td>";
									echo "<td>" . $lista['name'] . "</td>";
									echo "<td class='text-center'>" . $lista['date_tool_box'] . "</td>";
									echo "<td>" . $lista['activities'] . "</td>";
									echo "<td>" . $lista['suggestions'] . "</td>";
									
									echo "<td class='text-center'>";									
						?>
									<a class='btn btn-success btn-xs' href='<?php echo base_url('jobs/add_tool_box/' . $lista['fk_id_job'] . '/' . $lista['id_tool_box'] ) ?>'>
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