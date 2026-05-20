<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/excavation.js?v=1.0.0"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/ajaxExcavation.js?v=1.0.0"); ?>"></script>

<?php
/**
 * If it is an ADMIN user, show date 
 * @author BMOTTAG
 * @since  20/01/2022
 */
$userRol = session()->get('rol');
if($userRol==ID_ROL_SUPER_ADMIN){
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php } ?>

<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<a class="btn btn-danger btn-xs" href=" <?php echo base_url('jobs/excavation/' . $jobInfo[0]["id_job"]); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-pied-piper-alt"></i> <strong>EXCAVATION AND TRENCHING PLAN</strong>
				</div>
				<div class="panel-body">
				
					<div class="alert alert-danger">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
					</div>
				<?php 
					if($information){
				?>
					<ul class="nav nav-tabs">
						<li class='active'><a href="<?php echo base_url('jobs/add_excavation/' . $information[0]['fk_id_job'] . '/' . $information[0]['id_job_excavation']); ?>">Main Form</a>
						</li>
						<li><a href="<?php echo base_url('jobs/upload_excavation_personnel/' . $information[0]['id_job_excavation']); ?>">Personnel</a></li>
						<li><a href="<?php echo base_url('jobs/upload_protection_methods/' . $information[0]['id_job_excavation']); ?>">Protection Methods & Systems</a></li>
						<li><a href="<?php echo base_url('jobs/upload_access_egress/' . $information[0]['id_job_excavation']); ?>">Access & Egress </a></li>
						<li><a href="<?php echo base_url('jobs/upload_affected_zone/' . $information[0]['id_job_excavation']); ?>">Affected Zone </a></li>
						<li><a href="<?php echo base_url('jobs/upload_de_watering/' . $information[0]['id_job_excavation']); ?>">De-Watering </a></li>
						<li><a href="<?php echo base_url('jobs/upload_sketch/' . $information[0]['id_job_excavation']); ?>">Excavation / Trench Sketch </a></li>
						<li><a href="<?php echo base_url('jobs/review_excavation/' . $information[0]['id_job_excavation']); ?>">Approvals / Review </a></li>
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

					<form  name="form" id="form" class="form-horizontal" method="post" >
						<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]["id_job"]; ?>"/>
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_job_excavation"]:""; ?>"/>
<?php
/**
 * If it is an ADMIN user, show date 
 * @author BMOTTAG
 * @since  11/5/2017
 */
if($userRol==99){
?>				
<script>
	$( function() {
		$( "#date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
</script>
						<div class="form-group">
															
								<label class="col-sm-4 control-label" for="date">Date of Issue:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="date" name="date" value="<?php echo $information?$information[0]["date_excavation"]:""; ?>" placeholder="Date of Issue" />
								</div>
							
						</div>
<?php } ?>							
														
						<div class="form-group">
							<label class="col-sm-4 control-label" for="project_location">Project Location (be specific): *</label>
							<div class="col-sm-5">
							<textarea id="project_location" name="project_location" class="form-control" placeholder="Project Location" rows="3"><?php echo $information?$information[0]["project_location"]:""; ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="depth">Anticipated depth of excavation / trench: *
								<br><small class="text-danger">Depth in meters</small>
							</label>
							<div class="col-sm-5">
							<input type="number" id="depth" name="depth" max="15" class="form-control" value="<?php echo $information?$information[0]["depth"]:""; ?>" placeholder="Anticipated depth of excavation / trench" required >
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="width">Excavation / Trench dimensions:</label>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="width">Width: *
								<br><small class="text-danger">Meters</small>
							</label>
							<div class="col-sm-5">
							<input type="number" id="width" name="width" max="15" class="form-control" value="<?php echo $information?$information[0]["width"]:""; ?>" placeholder="Width" >
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="length">Length: *
								<br><small class="text-danger">Meters</small>
							</label>
							<div class="col-sm-5">
							<input type="number" id="length" name="length" max="200" class="form-control" value="<?php echo $information?$information[0]["length"]:""; ?>" placeholder="Length" >
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="confined_space">Will or could this excavation / trench be considered a confined space? *
								<br><small class="text-danger">If yes, please reference the separate confined space plan.</small>
							</label>
							<div class="col-sm-5">									
								<select name="confined_space" id="confined_space" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information && $information[0]["confined_space"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information && $information[0]["confined_space"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

<?php 
	$fildConfined = "none";
	if($information && $information[0]["confined_space"]==1){
		$fildConfined = "inline";
	}
?>
						
						<div class="form-group" id="div_confined" style="display:<?php echo $fildConfined; ?>">
							<label class="col-sm-4 control-label" for="ventilation_explanation">If yes, please select the Confined Space Entry Permit:</label>
							<div class="col-sm-5">
								<select name="idConfined" id="idConfined" class="form-control" >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($confinedList); $i++) { ?>
										<option value="<?php echo $confinedList[$i]["id_job_confined"]; ?>" <?php if($information && $information[0]["fk_id_confined"] == $confinedList[$i]["id_job_confined"]) { echo "selected"; }  ?>><?php echo 'No.' . $confinedList[$i]["id_job_confined"] . ' *** ' . $confinedList[$i]["date_confined"]  ; ?></option>	
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="tested_daily">Will the excavation / trench atmospheric conditions be tested daily? *
								<br><small class="text-danger">Be mindful of atmospheric conditions such as high concentration levels of radon (radiation), H2S, and any other harmful gases.</small>
							</label>
							<div class="col-sm-5">									
								<select name="tested_daily" id="tested_daily" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information && $information[0]["tested_daily"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information && $information[0]["tested_daily"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

<?php 
	$fildTested = "none";
	if($information && $information[0]["tested_daily"]==1){
		$fildTested = "inline";
	}
?>
						
						<div class="form-group" id="div_tested_daily_explanation" style="display:<?php echo $fildTested; ?>">
							<label class="col-sm-4 control-label" for="tested_daily_explanation">If yes, please explain:</label>
							<div class="col-sm-5">
								<textarea id="tested_daily_explanation" name="tested_daily_explanation" class="form-control" placeholder="If yes, please explain" rows="3" ><?php echo $information?$information[0]["tested_daily_explanation"]:""; ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="ventilation">Will ventilation be supplied inside the excavation / trench? *
								<br><small class="text-danger">Adequate ventilation needs to be used when elevated levels of harmful gases are found in the air.</small>
							</label>
							<div class="col-sm-5">									
								<select name="ventilation" id="ventilation" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information && $information[0]["ventilation"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information && $information[0]["ventilation"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

<?php 
	$fildVentilation = "none";
	if($information && $information[0]["ventilation"]==1){
		$fildVentilation = "inline";
	}
?>
						
						<div class="form-group" id="div_ventilation_explanation" style="display:<?php echo $fildVentilation; ?>">
							<label class="col-sm-4 control-label" for="ventilation_explanation">If yes, please explain:</label>
							<div class="col-sm-5">
								<textarea id="ventilation_explanation" name="ventilation_explanation" class="form-control" placeholder="If yes, please explain" rows="3"><?php echo $information?$information[0]["ventilation_explanation"]:""; ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="soil_classification">Has a soil classification been conducted to determine soil type? *</label>
							<div class="col-sm-5">									
								<select name="soil_classification" id="soil_classification" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information && $information[0]["soil_classification"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information && $information[0]["soil_classification"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>
					
						<div class="form-group">
							<label class="col-sm-4 control-label" for="soil_type">As a result of the selected soil classification tests listed above, soil is considered: *</label>
							<div class="col-sm-5">									
								<select name="soil_type" id="soil_type" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information && $information[0]["soil_type"] == 1) { echo "selected"; }  ?>>Stable rock</option>
									<option value=2 <?php if($information && $information[0]["soil_type"] == 2) { echo "selected"; }  ?>>"Type A" - unconfined comprehensive strength of 1.5 tsf or greater</option>
									<option value=3 <?php if($information && $information[0]["soil_type"] == 3) { echo "selected"; }  ?>>"Type B" - unconfined comprehensive strength of 0.5 -1.5 tsf</option>
									<option value=4 <?php if($information && $information[0]["soil_type"] == 4) { echo "selected"; }  ?>>"Type C" - unconfined comprehensive strength of 0.5 tsf or less</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="description_safe_work">Description of safe work practices and anticipated work inside the excavation / trench:
							</label>
							<div class="col-sm-5">
								<textarea id="description_safe_work" name="description_safe_work" class="form-control" rows="3"><?php echo $information?$information[0]["description_safe_work"]:""; ?></textarea>

<input type="checkbox" id="practice_work_alone" name="practice_work_alone" value=1 <?php if($information && $information[0]["practice_work_alone"]){echo "checked";} ?> ><small> Do not work alone</small><br>
<input type="checkbox" id="practice_eye_contact" name="practice_eye_contact" value=1 <?php if($information && $information[0]["practice_eye_contact"]){echo "checked";} ?> > <small>Keep eye contact with Heavy Equipment operators</small><br>
<input type="checkbox" id="practice_communication" name="practice_communication" value=1 <?php if($information && $information[0]["practice_communication"]){echo "checked";} ?> > <small>Maintain good communication with the crew</small><br>
<input type="checkbox" id="practice_walls" name="practice_walls" value=1 <?php if($information && $information[0]["practice_walls"]){echo "checked";} ?> > <small>No worker shall enter any trench or excavation until the walls have been adequately cut back</small><br>
<input type="checkbox" id="practice_protective_structures" name="practice_protective_structures" value=1 <?php if($information && $information[0]["practice_protective_structures"]){echo "checked";} ?> > <small>No worker shall enter any trench or excavation before temporary protective structures have been installed (shorings, trench jacks, sheet piling, cage)</small><br>
<input type="checkbox" id="practice_identify_underground" name="practice_identify_underground" value=1 <?php if($information && $information[0]["practice_identify_underground"]){echo "checked";} ?> > <small>Identify all underground utilities and/or overhead powerlines</small><br>
<input type="checkbox" id="practice_scope" name="practice_scope" value=1 <?php if($information && $information[0]["practice_scope"]){echo "checked";} ?> > <small>Understand the scope of work</small><br>
<input type="checkbox" id="practice_site_locates" name="practice_site_locates" value=1 <?php if($information && $information[0]["practice_site_locates"]){echo "checked";} ?> > <small>Have a copy of the site Locates</small><br>
<input type="checkbox" id="practice_provided_safe" name="practice_provided_safe" value=1 <?php if($information && $information[0]["practice_provided_safe"]){echo "checked";} ?> > <small>Use the provided safe means of entering and exiting the excavation (ladder, scaffold, mechanical devices, appropriate slopping of the ground)</small><br>
<input type="checkbox" id="practice_traffic_control" name="practice_traffic_control" value=1 <?php if($information && $information[0]["practice_traffic_control"]){echo "checked";} ?> > <small>Use traffic control methods near roads or busy access ways</small><br>
<input type="checkbox" id="practice_flaggers" name="practice_flaggers" value=1 <?php if($information && $information[0]["practice_flaggers"]){echo "checked";} ?> > <small>Use traffic controllers/flaggers</small><br>
<input type="checkbox" id="practice_barricades" name="practice_barricades" value=1 <?php if($information && $information[0]["practice_barricades"]){echo "checked";} ?> > <small>Set up barricades</small>
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">
									<button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-danger'>
											Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>						
								</div>
							</div>
						</div>
						
					</form>
					
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