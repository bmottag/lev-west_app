<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/excavation_de_watering.js?v=1.0.0"); ?>"></script>

<script>
$(document).ready(function () {
    $('#dewatering_needed').change(function () {
        $('#dewatering_needed option:selected').each(function () {
            var dewatering_needed = $('#dewatering_needed').val();

            if ((dewatering_needed > 0 || dewatering_needed != '') ) {
				$("#div_explain_equipment").hide();
                $('#explain_equipment').val("");
				if(dewatering_needed==1){
					$("#div_explain_equipment").show();
				}
            }
        });
    });    
});
</script>

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
						<li><a href="<?php echo base_url('jobs/upload_affected_zone/' . $information[0]['id_job_excavation']); ?>">Affected Zone </a></li>
						<li class='active'><a href="<?php echo base_url('jobs/upload_de_watering/' . $information[0]['id_job_excavation']); ?>">De-Watering </a></li>
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

					<form  name="form" id="form" class="form-horizontal" method="post" >
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_job_excavation"]:""; ?>"/>
														
						<div class="form-group">
							<label class="col-sm-4 control-label" for="dewatering_needed">Is it anticipated that de-watering will be needed / implemented? *
							</label>
							<div class="col-sm-5">									
								<select name="dewatering_needed" id="dewatering_needed" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["dewatering_needed"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["dewatering_needed"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>

<?php 
	$fildDewatering = "none";
	if($information && $information[0]["dewatering_needed"]==1){
		$fildDewatering = "inline";
	}
?>
												
						<div class="form-group" id="div_explain_equipment" style="display:<?php echo $fildDewatering; ?>">
							<label class="col-sm-4 control-label" for="explain_equipment">If yes, explain equipment and procedures: </label>
							<div class="col-sm-5">
								<textarea id="explain_equipment" name="explain_equipment" class="form-control" placeholder="If yes, explain equipment and procedures" rows="3"><?php echo $information?$information[0]["explain_equipment"]:""; ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="body_water">Is the excavation located next to a body of water (ocean, lake, stream, etc.)? *
							</label>
							<div class="col-sm-5">									
								<select name="body_water" id="body_water" class="form-control" required>
									<option value="">Select...</option>
									<option value=1 <?php if($information[0]["body_water"] == 1) { echo "selected"; }  ?>>Yes</option>
									<option value=2 <?php if($information[0]["body_water"] == 2) { echo "selected"; }  ?>>No</option>
								</select>
							</div>
						</div>
												
						<div class="form-group">
							<label class="col-sm-4 control-label" for="water_conducted">If de-watering is implemented, how will water discharge be conducted: </label>
							<div class="col-sm-5">
								<textarea id="water_conducted" name="water_conducted" class="form-control" placeholder="If yes, explain equipment and procedures below" rows="3"><?php echo $information?$information[0]["water_conducted"]:""; ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="additional_notes">Additional Notes: </label>
							<div class="col-sm-5">
								<textarea id="additional_notes" name="additional_notes" class="form-control" placeholder="Additional Notes" rows="3"><?php echo $information?$information[0]["additional_notes"]:""; ?></textarea>
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
					
				</div>
			</div>
		</div>
	</div>
</div>