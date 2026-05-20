<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/excavation_access.js?v=1.0.0"); ?>"></script>

<script>
function valid_field() 
{
	if(document.getElementById('ladder').checked || document.getElementById('ramp').checked || document.getElementById('other').checked ){
		document.getElementById('hddField').value = 1;
	}else{
		document.getElementById('hddField').value = "";
	}
}
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
						<li class='active'><a href="<?php echo base_url('jobs/upload_access_egress/' . $information[0]['id_job_excavation']); ?>">Access & Egress </a></li>
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
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_job_excavation"]:""; ?>"/>
														
						<div class="form-group">
							<label class="col-sm-5 control-label" for="project_location">Choose the method of access / egress below that will be implemented:  *
								<br><small class="text-danger">(may choose more than one) </small></label>
							<div class="col-sm-5">
<?php
$styleLadder = "";
$styleRamp = "";
$styleOther = "";
if($information && $information[0]["access_ladder"]){
	$styleLadder = "text-danger";
}
if($information && $information[0]["access_ramp"]){
	$styleRamp = "text-danger";
}
if($information && $information[0]["access_other"]){
	$styleOther = "text-danger";
}
?>
<p class="<?php echo $styleLadder; ?>">
<input type="checkbox" id="ladder" name="ladder" value=1 <?php if($information && $information[0]["access_ladder"]){echo "checked";} ?> onclick="valid_field()"> Portable ladder(s) placed within 7 m of lateral travel
</p>
<p class="<?php echo $styleRamp; ?>">
<input type="checkbox" id="ramp" name="ramp" value=1 <?php if($information && $information[0]["access_ramp"]){echo "checked";} ?> onclick="valid_field()"> Ramp(s) placed within 15 m of lateral travel
</p>
<p class="<?php echo $styleOther; ?>">
<input type="checkbox" id="other" name="other" value=1 <?php if($information && $information[0]["access_other"]){echo "checked";} ?> onclick="valid_field()"> Other means of access / egress: 
</p>

<?php 
$valorCampo = "";
if($information)
{
	if($information[0]["access_ladder"] || $information[0]["access_ramp"] || $information[0]["access_other"])
	{
		$valorCampo = 1;
	}
}
?>
<input type="hidden" id="hddField" name="hddField" value="<?php echo $valorCampo; ?>"/>
							
							</div>
						</div>
												
						<div class="form-group">
							<label class="col-sm-4 control-label" for="access_explain">Explain in detail: </label>
							<div class="col-sm-5">
								<textarea id="access_explain" name="access_explain" class="form-control" placeholder="Explain in detail" rows="3"><?php echo $information?$information[0]["access_explain"]:""; ?></textarea>
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