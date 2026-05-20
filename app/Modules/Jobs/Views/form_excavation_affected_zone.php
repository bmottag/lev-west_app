<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/ajaxExcavationAffectedZone.js?v=1.0.0"); ?>"></script>

<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<a class="btn btn-danger btn-xs" href=" <?php echo base_url('jobs/excavation/' . $information[0]["id_job"]); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-pied-piper-alt"></i> <strong>EXCAVATION AND TRENCHING PLAN</strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-danger">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $information?$information[0]["job_description"]:""; ?>
						</h2>
					</div>
				<?php 
					if($information){
				?>
					<ul class="nav nav-tabs">
						<li><a href="<?php echo base_url('jobs/add_excavation/' . $information[0]['fk_id_job'] . '/' . $information[0]['id_job_excavation']); ?>">Main Form</a></li>
						<li><a href="<?php echo base_url('jobs/upload_excavation_personnel/' . $information[0]['id_job_excavation']); ?>">Personnel</a></li>
						<li><a href="<?php echo base_url('jobs/upload_protection_methods/' . $information[0]['id_job_excavation']); ?>">Protection Methods & Systems</a></li>
						<li><a href="<?php echo base_url('jobs/upload_access_egress/' . $information[0]['id_job_excavation']); ?>">Access & Egress </a></li>
						<li class='active'><a href="<?php echo base_url('jobs/upload_affected_zone/' . $information[0]['id_job_excavation']); ?>">Affected Zone </a></li>
						<li><a href="<?php echo base_url('jobs/upload_de_watering/' . $information[0]['id_job_excavation']); ?>">De-Watering </a></li>
						<li><a href="<?php echo base_url('jobs/upload_sketch/' . $information[0]['id_job_excavation']); ?>">Excavation / Trench Sketch </a></li>
						<li><a href="<?php echo base_url('jobs/review_excavation/' . $information[0]['id_job_excavation']); ?>">Approvals / Review</a></li>
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

					<form  name="form" id="form" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url("jobs/save_affected_zone"); ?>">
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_job_excavation"]:""; ?>"/>
														
						<div class="form-group">
							<label class="col-sm-4 control-label" for="located">Have utilities been located by a utility locate company? *
								<br><small class="text-danger">If no, STOP. Utility locates must be performed before digging is initiated.</small>
							</label>
							<div class="col-sm-5">									
								<select name="located" id="located" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["located"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["located"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="permit_required">Is an excavation permit required in this area or on this project? *
							</label>
							<div class="col-sm-5">									
								<select name="permit_required" id="permit_required" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["permit_required"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["permit_required"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

						<div class="form-group">					
							<label class="col-sm-4 control-label text-danger" for="hddTask">If yes, please attach a copy of the permit to this plan.
								<br><small class="text-danger">Allowed format: pdf
								<br>Maximum size: 3000 KB </small>
							</label>
							<div class="col-sm-5">
								 <input type="file" name="userfile" class="form-control" />
								 <br>
								 <?php if($information[0]["permit_required_doc"]){ ?>
									<a href="<?php echo base_url('files/excavation/' . $information[0]["permit_required_doc"]) ?>" target="_blank">Copy of the permit: <?php echo $information[0]["permit_required_doc"]; ?></a>
								<?php } ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="utility_lines">Will utility lines (overhead or underground electrical / water / steam / sewer / storm / etc.) be present? *
							</label>
							<div class="col-sm-5">									
								<select name="utility_lines" id="utility_lines" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["utility_lines"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["utility_lines"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

<?php 
	$fildUtilityLines = "none";
	if($information && $information[0]["utility_lines"]==1){
		$fildUtilityLines = "inline";
	}
?>
					
						<div class="form-group" id="div_utility_lines_explain" style="display:<?php echo $fildUtilityLines; ?>">
							<label class="col-sm-4 control-label" for="utility_lines_explain">If yes, explain: </label>
							<div class="col-sm-5">
								<textarea id="utility_lines_explain" name="utility_lines_explain" class="form-control" placeholder="If yes, explain" rows="3"><?php echo $information?$information[0]["utility_lines_explain"]:""; ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="encumbrances">Will any surface encumbrances be located within the affected zone of the trench? *
							</label>
							<div class="col-sm-5">									
								<select name="encumbrances" id="encumbrances" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["encumbrances"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["encumbrances"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

<?php 
	$fildMethodSupport = "none";
	if($information && $information[0]["encumbrances"]==1){
		$fildMethodSupport = "inline";
	}
?>

						<div class="form-group" id="div_method_support" style="display:<?php echo $fildMethodSupport; ?>">
							<label class="col-sm-4 control-label" for="method_support">If yes, explain method of support / protection: </label>
							<div class="col-sm-5">
								<textarea id="method_support" name="method_support" class="form-control" placeholder="If yes, explain method of support / protection" rows="3"><?php echo $information?$information[0]["method_support"]:""; ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="utility_shutdown">Will utility shutdown / shut off / or lock out tag out be required? *
								<br><small class="text-danger">If yes, reference the separate Hazardous Energy Control Plan </small>
							</label>
							<div class="col-sm-5">									
								<select name="utility_shutdown" id="utility_shutdown" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["utility_shutdown"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["utility_shutdown"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="spoil_piles">Will spoil piles remain a minimum 1 meter from the excavation / trench edge? *
							</label>
							<div class="col-sm-5">									
								<select name="spoil_piles" id="spoil_piles" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["spoil_piles"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["spoil_piles"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

<?php 
	$fildSpoilsTransported = "none";
	$fildEnvironmentalControls = "none";
	if($information && $information[0]["spoil_piles"]==2){
		$fildSpoilsTransported = "inline";
		$fildEnvironmentalControls = "none";
	}elseif($information && $information[0]["spoil_piles"]==1){
		$fildSpoilsTransported = "none";
		$fildEnvironmentalControls = "inline";
	}
?>

						<div class="form-group" id="div_spoils_transported" style="display:<?php echo $fildSpoilsTransported; ?>">
							<label class="col-sm-4 control-label" for="spoils_transported">If no, will spoils be transported off site? 
							</label>
							<div class="col-sm-5">									
								<select name="spoils_transported" id="spoils_transported" class="form-control" >
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["spoils_transported"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["spoils_transported"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

						<div class="form-group" id="div_environmental_controls" style="display:<?php echo $fildEnvironmentalControls; ?>">
							<label class="col-sm-4 control-label" for="environmental_controls">If yes, are environmental controls in place to reduce runoff?
							</label>
							<div class="col-sm-5">									
								<select name="environmental_controls" id="environmental_controls" class="form-control" >
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["environmental_controls"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["environmental_controls"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="open_overnight">Will the excavation / trench be left open overnight? *
							</label>
							<div class="col-sm-5">									
								<select name="open_overnight" id="open_overnight" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["open_overnight"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["open_overnight"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

<?php 
	$fildMethodsSecure = "none";
	if($information && $information[0]["open_overnight"]==1){
		$fildMethodsSecure = "inline";
	}
?>

						<div class="form-group" id="div_methods_secure" style="display:<?php echo $fildMethodsSecure; ?>">
							<label class="col-sm-4 control-label" for="methods_secure">If yes, describe methods to secure the excavation area from the public or bystanders: 
							<br><small class="text-danger">As a recomendation you can use construction fence to cover an open excavation and/or concrete barricades to secure the excavation. </small>
							</label>
							<div class="col-sm-5">
								<textarea id="methods_secure" name="methods_secure" class="form-control" placeholder="If yes, describe methods to secure the excavation area from the public or bystanders" rows="3"><?php echo $information?$information[0]["methods_secure"]:""; ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="vehicle_traffic">Will worker(s) accessing or working from the trench be exposed to vehicle traffic? *
								<br><small class="text-danger">If yes, please reference separate Traffic Control Plan. </small>
							</label>
							<div class="col-sm-5">									
								<select name="vehicle_traffic" id="vehicle_traffic" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["vehicle_traffic"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["vehicle_traffic"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">
									<button type="submit" id="btnSubmit" name="btnSubmit" class='btn btn-danger'>
											Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>						
								</div>
							</div>
						</div>
						
					</form>
					
				</div>
			</div>
		</div>
	</div>
</div>