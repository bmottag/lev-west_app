<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<a class="btn btn-info btn-xs" href=" <?php echo base_url() . 'jobs'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-ambulance"></i> <strong>INCIDENCES</strong> - NEAR MISS REPORT
				</div>
				<div class="panel-body">

					<div class="alert alert-info">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
					</div>	

					<a class='btn btn-outline btn-info btn-block' href='<?php echo base_url('incidences/add_near_miss/' . $jobInfo[0]['id_job']) ?>'>
							<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span>  Add a Near Miss Report
					</a>
					
					<br>
				<?php
					if($nearMissInfo){
				?>
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th>#</th>
								<th>Job Code/Name</th>
								<th>Reported by</th>
								<th>Near miss type</th>
								<th>Near miss date </th>
								<th>What happened?</th>
								<th>Edit</th>
								<th>Download</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($nearMissInfo as $lista):
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['id_near_miss'] . "</td>";
									echo "<td>" . $lista['job_description'] . "</td>";
									echo "<td>" . $lista['name'] . "</td>";
									echo "<td>" . $lista['incident_type'] . "</td>";
									echo "<td class='text-center'>" . $lista['date_near_miss'] . "</td>";
									echo "<td>" . $lista['what_happened'] . "</td>";
									
									echo "<td class='text-center'>";								
									
									if($lista['state_incidence']==1){
						?>
									<a class='btn btn-success btn-xs' href='<?php echo base_url('incidences/add_near_miss/' . $lista['fk_id_job'] . '/' . $lista['id_near_miss']) ?>'>
											Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</a>								
						<?php
									}else{
						?>
									<button type="button" class="btn btn-danger btn-xs" >
										Close 
									</button>
						<?php
									}
									echo "</td>";
									echo "<td class='text-center'>";
						?>
<a href='<?php echo base_url('incidences/generaPDF/' . $lista['id_near_miss'] . '/' . 1 ); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
						<?php
									echo "</td>";
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