<script type="text/javascript" src="<?php echo base_url("assets/js/validate/safety/safety.js?v=1.0.0"); ?>"></script>

<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a class="btn btn-default btn-xs" href=" <?php echo base_url().'jobs/safety/' . $jobInfo[0]["id_job"]; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-life-saver"></i> <strong>FLHA - FIELD LEVEL HAZARD ASSESSMENT</strong>
				</div>
				<div class="panel-body">
				
					<div class="alert alert-info">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
					</div>
				<?php 
					if($information){
				?>
					<ul class="nav nav-tabs">
						<li class='active'><a href="<?php echo base_url('safety/add_safety/' . $information[0]['fk_id_job'] . '/' . $information[0]['id_safety']); ?>">Main Form</a>
						</li>
						<li><a href="<?php echo base_url('safety/upload_info_safety/' . $information[0]['id_safety']); ?>">Hazards</a>
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
//verificar si el JOB CODE tiene asignados hazards
if(!$hazards){
?>
	<div class="row">
		<div class="alert alert-danger ">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			This Job Code/Name does not have hazards.
		</div>
	</div>
<?php	
}else{
?>

					<form  name="form" id="form" class="form-horizontal" method="post" action="<?php echo base_url("payroll/savePayroll"); ?>" >
						<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]["id_job"]; ?>"/>
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_safety"]:""; ?>"/>
						<!-- 2: id_task FILED LEVEL HAZARD Assessment -->
						<input type="hidden" id="hddTask" name="hddTask" value="2"/>
														
						<div class="form-group">
							<label class="col-sm-4 control-label" for="work">Task(s) to be done:</label>
							<div class="col-sm-5">
							<textarea id="work" name="work" class="form-control" placeholder="Task(s) to be done" rows="3"><?php echo $information?$information[0]["work"]:""; ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="musterPoint">Primary muster point:</label>
							<div class="col-sm-5">
							<input type="text" id="musterPoint" name="musterPoint" class="form-control" value="<?php echo $information?$information[0]["muster_point"]:""; ?>" placeholder="Primary muster point" required >
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="musterPoint">Secondary muster point:</label>
							<div class="col-sm-5">
							<input type="text" id="musterPoint2" name="musterPoint2" class="form-control" value="<?php echo $information?$information[0]["muster_point_2"]:""; ?>" placeholder="Secondary muster point" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="primaryHeadCounter">Primary head counter:</label>
							<div class="col-sm-5">
							<input type="text" id="primaryHeadCounter" name="primaryHeadCounter" class="form-control" value="<?php echo $information?$information[0]["primary_head_counter"]:""; ?>" placeholder="Primary head counter" required>
							</div>
						</div>	

						<div class="form-group">
							<label class="col-sm-4 control-label" for="secondaryHeadCounter">Secondary head counter:</label>
							<div class="col-sm-5">
							<input type="text" id="secondaryHeadCounter" name="secondaryHeadCounter" class="form-control" value="<?php echo $information?$information[0]["secondary_head_counter"]:""; ?>" placeholder="Secondary head counter" required>
							</div>
						</div>	

						<div class="form-group">
							<label class="col-sm-4 control-label" for="ppe">PPE (Basic)</label>
							<div class="col-sm-5">
							   <div class="checkbox">
									<label>
										<input type="checkbox" id="ppe" name="ppe" <?php if($information && $information[0]["ppe"] == 1) { echo "checked"; }  ?> />
									</label>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="management">Specialized PPE</label>
							<div class="col-sm-5">
								<textarea id="specify" name="specify" class="form-control" placeholder="Specialized PPE" rows="3"><?php echo $information?$information[0]["specify_ppe"]:""; ?></textarea>
							</div>
						</div>
					
						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">
									<button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-primary'>
											Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>						
								</div>
							</div>
						</div>
						
					</form>
					
<?php
}
?>

				</div>
			</div>
		</div>
	</div>
</div>