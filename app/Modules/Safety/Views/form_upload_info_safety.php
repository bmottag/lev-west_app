<script type="text/javascript" src="<?php echo base_url("assets/js/validate/safety/safety.js"); ?>"></script>

<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-life-saver"></i> <strong>FLHA - FIELD LEVEL HAZARD ASSESSMENT</strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-info">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $information?$information[0]["job_description"]:""; ?><br>
						</h2>
						<strong>Task(s) to be done: </strong><br><?php echo $information?$information[0]["work"]:""; ?>
					</div>
				<?php 
					if($information){
				?>
					<ul class="nav nav-tabs">
						<li><a href="<?php echo base_url('safety/add_safety/' . $information[0]['fk_id_job'] . '/' . $information[0]['id_safety']); ?>">Main Form</a>
						</li>
						<li class='active'><a href="<?php echo base_url('safety/upload_info_safety/' . $information[0]['id_safety']); ?>">Hazards</a>
						</li>
						<!--
						<li><a href="<?php echo base_url('safety/upload_covid/' . $information[0]['id_safety']); ?>">COVID Form</a>
						</li>
						-->
						<li><a href="<?php echo base_url('safety/upload_workers/' . $information[0]['id_safety']); ?>">Workers</a>
						</li>						
						<li><a href="<?php echo base_url('safety/review_flha/' . $information[0]['id_safety']); ?>">Review and Sign</a>
						</li>
					</ul>
					<br>
				<?php
					}
				?>

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

<?php 
	if($safetyClose){
?>
	<div class="row">
		<div class="col-lg-12">	
			<div class="alert alert-success ">
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
				This safety form is close.
			</div>
		</div>
	</div>
<?php		
	}
?>
<!--INICIO HAZARDS -->
					<div class="col-lg-12">	
						<a href="<?php echo base_url("safety/add_hazards_flha/" . $information[0]["fk_id_job"] . "/" . $information[0]["id_safety"]); ?>" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Hazards</a>
						<br>
					</div>

				<?php 
					if($safetyHazard){
				?>
					<table class="table table-bordered table-striped table-hover table-condensed">
						<tr class="dafault">
							<th class='text-center'>Activity</th>
							<th class='text-center'>Hazard</th>
							<th class='text-center'>Solution</th>
							<th class='text-center'>Priority</th>
						</tr>
						<?php
							foreach ($safetyHazard as $data):
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

								echo "<tr>";					
								echo "<td class='text-" . $class . " " . $class . "'><small>" . $data['hazard_activity'] . "</small></td>";
								echo "<td class='text-" . $class . " " . $class . "'><small>" . $data['hazard_description'] . "</small></td>";
								echo "<td class='text-" . $class . " " . $class . "'><small>" . $data['solution']  . "</small></td>";
								echo "<td class='text-center " . $class . "'><p class='text-" . $class . "'><strong>" . $data['priority_description'] . "</strong></p></td>";
								                    
								echo "</tr>";
							endforeach;
						?>
					</table>
				<?php } ?>
<!--FIN HAZARDS -->
				</div>
			</div>
		</div>
	</div>	
</div>