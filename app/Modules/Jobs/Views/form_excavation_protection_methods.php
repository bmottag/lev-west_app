<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/ajaxExcavationProtection.js?v=1.0.0"); ?>"></script>

<script>
function valid_field() 
{
	if(document.getElementById('sloping').checked || document.getElementById('benching').checked || document.getElementById('shoring').checked || document.getElementById('shielding').checked ){
		document.getElementById('hddField').value = 1;
	}else{
		document.getElementById('hddField').value = "";
	}

	if(document.getElementById('sloping').checked){
		if(document.getElementById('type_a').checked || document.getElementById('type_b').checked || document.getElementById('type_c').checked ){
			document.getElementById('hddField2').value = 1;
		}else{
			document.getElementById('hddField2').value = "";
		}
	}else{
		document.getElementById('hddField2').value = 1;
	}
}
</script>

<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
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
						<li class='active'><a href="<?php echo base_url('jobs/upload_protection_methods/' . $information[0]['id_job_excavation']); ?>">Protection Methods & Systems</a></li>
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

					<form  name="form" id="form" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url("jobs/save_protection_methods"); ?>">
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_job_excavation"]:""; ?>"/>
														
						<div class="form-group">
							<label class="col-sm-4 control-label" for="project_location">Choose the method of protection below that will be implemented: *
								<br><small class="text-danger">(may choose more than one) </small></label>
							<div class="col-sm-8">
<input type="checkbox" id="sloping" name="sloping" value=1 <?php if($information && $information[0]["protection_sloping"]){echo "checked";} ?> onclick="valid_field()"> Sloping<br>

<?php 
	$fildTested = "none";
	if($information && $information[0]["protection_sloping"]){
		$fildTested = "inline";
	}
?>
						
						<div class="form-group" id="div_sloping" style="display:<?php echo $fildTested; ?>">
							<label class="col-sm-1 control-label" for="tested_daily_explanation"></label>
							<div class="col-sm-5">
								<input type="checkbox" id="type_a" name="type_a" value=1 <?php if($information && $information[0]["protection_type_a"]){echo "checked";} ?> onclick="valid_field()"> ¾ to 1- Type A Soil<br>
								<input type="checkbox" id="type_b" name="type_b" value=1 <?php if($information && $information[0]["protection_type_b"]){echo "checked";} ?> onclick="valid_field()"> 1 to 1 - Type B Soil<br>
								<input type="checkbox" id="type_c" name="type_c" value=1 <?php if($information && $information[0]["protection_type_c"]){echo "checked";} ?> onclick="valid_field()"> 1 ½ to 1- Type C Soil<br>

							</div>
						</div>
<?php 
$valorCampo2 = "";
if($information)
{
	if($information[0]["protection_sloping"])
	{
		if($information[0]["protection_type_a"] || $information[0]["protection_type_b"] || $information[0]["protection_type_c"])
		{
			$valorCampo2 = 1;
		}
	}else{
		$valorCampo2 = 1;
	}
}
?>
								<input type="hidden" id="hddField2" name="hddField2" value="<?php echo $valorCampo2; ?>" required />


<p>
<img src="<?php echo base_url('images/sloping.jpg'); ?>">
</p>
<input type="checkbox" id="benching" name="benching" value=1 <?php if($information && $information[0]["protection_benching"]){echo "checked";} ?> onclick="valid_field()"> Benching <small class="text-danger">Note: Benching in class C soil is prohibited. </small><br>
<input type="checkbox" id="shoring" name="shoring" value=1 <?php if($information && $information[0]["protection_shoring"]){echo "checked";} ?> onclick="valid_field()"> Shoring<br>
<input type="checkbox" id="shielding" name="shielding" value=1 <?php if($information && $information[0]["protection_shielding"]){echo "checked";} ?> onclick="valid_field()"> Shielding<br>

<?php 
$valorCampo = "";
if($information)
{
	if($information[0]["protection_sloping"] || $information[0]["protection_type_a"] || $information[0]["protection_type_b"] || $information[0]["protection_type_c"] || $information[0]["protection_benching"] || $information[0]["protection_shoring"] || $information[0]["protection_shielding"])
	{
		$valorCampo = 1;
	}
}
?>
<input type="hidden" id="hddField" name="hddField" value="<?php echo $valorCampo; ?>" required/>
							
							</div>
						</div>
			
						<div class="form-group">					
							<label class="col-sm-4 control-label" for="hddTask">Attach document if necessary
								<br><small class="text-danger">Allowed format: pdf
								<br>Maximum size: 3000 KB </small>
							</label>
							<div class="col-sm-5">
								 <input type="file" name="userfile" class="form-control" />
								 <br>
								 <?php if($information[0]["method_system_doc"]){ ?>
									<a href="<?php echo base_url('files/excavation/' . $information[0]["method_system_doc"]) ?>" target="_blank">Attached document: <?php echo $information[0]["method_system_doc"]; ?></a>
								<?php } ?>
							</div>
						</div>
			
						<div class="form-group">
							<label class="col-sm-4 control-label" for="additional_comments">Additional Comments: </label>
							<div class="col-sm-5">
								<textarea id="additional_comments" name="additional_comments" class="form-control" placeholder="Additional Comments" rows="3"><?php echo $information?$information[0]["additional_comments"]:""; ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:50%;" align="center">
									<p class="text-danger">Note: If excavation / trench exceeds 7 meters in depth, please attach a copy of the engineered excavation / trench design and protective systems.</p>						
								</div>
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