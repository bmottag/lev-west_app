<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<a class="btn btn-danger btn-xs" href=" <?php echo base_url().'jobs'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-life-saver"></i> <strong>JHA - JOB HAZARDS ANALYSIS</strong>
				</div>
				<div class="panel-body">

					<div class="alert alert-danger">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?><br>
						</h2>
						<a href="<?php echo base_url("jobs/hazards_logs/" . $jobInfo[0]["id_job"]); ?>">LOGS</a>
					</div>

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
				
					<!--INICIO HAZARDS -->													
					<a class='btn btn-danger btn-block' href='<?php echo base_url('jobs/add_hazards/' . $jobInfo[0]['id_job']) ?>'>
							<span class="glyphicon glyphicon-plus" aria-hidden="true"> </span>  Add Hazards
					</a>
					<br>
					
					<?php 
						if($hazards){
					?>
					<table class="table table-bordered table-striped table-hover table-condensed">
						<tr class="dafault">
							<th class="text-center">Activity</th>
							<th class="text-center">Hazard</th>
							<th class="text-center">Solution</th>
							<th class="text-center">Priority</th>
							<th class="text-center">Delete</th>
						</tr>
						<?php
							foreach ($hazards as $data):
								echo "<tr>";					
								echo "<td ><small>" . $data['hazard_activity'] . "</small></td>";
								echo "<td ><small>" . $data['hazard_description'] . "</small></td>";
								echo "<td ><small>" . $data['solution']  . "</small></td>";
								
								$priority = $data['priority_description'];
								
								if($priority == 1 || $priority == 2) {
									$class = "success";
								}elseif($priority == 3 || $priority == 4) {
									$class = "info";
								}elseif($priority == 5 || $priority == 6) {
									$class = "warning";
								}elseif($priority == 7 || $priority == 8) {
									$class = "danger";
								}
												
		echo "<td class='text-center " . $class . "'><p class='text-" . $class . "'><strong>" . $data['priority_description'] . "</strong></p></td>";
								
								echo "<td class='text-center'><small>";
						?>
							<center>
							<a class='btn btn-danger btn-xs' href='<?php echo base_url('jobs/deleteJobHazard/' . $data['id_job_hazard'] . '/' . $data['fk_id_job']) ?>' id="btn-delete">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"> </span>  Delete
							</a>
							</center>
						<?php
								echo "</small></td>";                     
								echo "</tr>";
							endforeach;
						?>
					</table>
					<?php } ?>
					<!--FIN HAZARDS -->
					
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->