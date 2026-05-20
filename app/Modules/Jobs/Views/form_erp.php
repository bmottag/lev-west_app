<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/erp.js?v=1.0.0"); ?>"></script>

<script>
$(document).ready(function () {
		
    $("#other").on("click", function() {
        var condiciones = $("#other").is(":checked");
        if (condiciones) {
            $("#div_specify").css("display", "inline");
			$('#specify').val("");
        } else {
			$("#div_specify").css("display", "none");
			$('#specify').val("");
        }
    });
	
});


function valid_field() 
{
	if(document.getElementById('voice').checked || document.getElementById('radio').checked || document.getElementById('phone').checked || document.getElementById('other').checked ){
		document.getElementById('hddField').value = 1;
	}else{
		document.getElementById('hddField').value = "";
	}
}

</script>

<div id="page-wrapper">
	<br>

<form  name="form" id="form" class="form-horizontal" method="post"  >
	<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]["id_job"]; ?>"/>
	<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_erp"]:""; ?>"/>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					<a class="btn btn-success btn-xs" href=" <?php echo base_url().'jobs'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-fire-extinguisher "></i> <strong>ERP - EMERGENCY RESPONSE PLAN</strong>
				</div>
				<div class="panel-body">
				
					<ul class="nav nav-pills">
						<li class='active'><a href="<?php echo base_url("jobs/erp/" . $jobInfo[0]["id_job"]); ?>">ERP - INFO</a>
						</li>
						<li ><a href="<?php echo base_url("jobs/erp_personnel/" . $jobInfo[0]["id_job"]); ?>">ERP - EVACUATION PERSONNEL</a>
						</li>
						<li ><a href="<?php echo base_url("jobs/erp_map/" . $jobInfo[0]["id_job"]); ?>">ERP - EVACUATION MAP</a>
						</li>
					</ul>
				
					<div class="alert alert-success">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
						<br><span class='fa fa-clock-o' aria-hidden='true'></span> <strong>Date: </strong>
						<?php 
						if($information){
								echo $information[0]["date_erp"]; 
								
								echo "<br><span class='fa fa-cloud-download' aria-hidden='true'></span></span> <strong>Download ERP: </strong>";
						?>
<a href='<?php echo base_url('jobs/generaERPPDF/' . $information[0]["id_erp"] ); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php 
						}else{
								echo date("Y-m-d");
						}
						?>
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

<p class="text-danger text-left">Fields with * are required.</p>
								
						<div class="form-group">
							<label class="col-sm-4 control-label" for="address">Facility address: *</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="address" name="address" value="<?php echo $information?$information[0]["address"]:""; ?>" placeholder="Facility address" required <?php echo $deshabilitar; ?> />
							</div>
						</div>
														
				</div>
			</div>
		</div>
	</div>		

	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>EMERGENCY PERSONNEL NAMES AND PHONE NUMBERS</strong>
				</div>
				<div class="panel-body">								
						<div class="form-group">
							<label class="col-sm-4 control-label" for="responsible">Site supervisor: *</label>
							<div class="col-sm-5">
								<select name="responsible" id="responsible" class="form-control" <?php echo $deshabilitar; ?>>
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information && $information[0]["responsible_user"] == $workersList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>								
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="coordinator">Emergency coordinator: *</label>
							<div class="col-sm-5">
								<select name="coordinator" id="coordinator" class="form-control" <?php echo $deshabilitar; ?>>
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information && $information[0]["coordinator_user"] == $workersList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>								
							</div>
						</div>

				</div>
			</div>
		</div>
	</div>		
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>EMERGENCY PHONE NUMBERS</strong>
				</div>
				<div class="panel-body">								
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="fire_department">Fire department: *</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="fire_department" name="fire_department" value="<?php echo $information?$information[0]["fire_department"]:"403 268 2489 / 911"; ?>" placeholder="Fire department" required <?php echo $deshabilitar; ?> />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="paramedics">Paramedics: *</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="paramedics" name="paramedics" value="<?php echo $information?$information[0]["paramedics"]:"403 261 4000 / 911"; ?>" placeholder="Paramedics" required <?php echo $deshabilitar; ?> />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="ambulance">Ambulance: *</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="ambulance" name="ambulance" value="<?php echo $information?$information[0]["ambulance"]:"403 261 4000 / 911"; ?>" placeholder="Ambulance" required <?php echo $deshabilitar; ?> />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="police">Police: *</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="police" name="police" value="<?php echo $information?$information[0]["police"]:"403266 1234 / 911"; ?>" placeholder="Police" required <?php echo $deshabilitar; ?> />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="federal_protective">Federal protective service: *</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="federal_protective" name="federal_protective" value="<?php echo $information?$information[0]["federal_protective"]:"1 800 328 6189"; ?>" placeholder="Federal protective service" required <?php echo $deshabilitar; ?> />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="security">Security (If applicable): </label>
							<div class="col-sm-5">
							<textarea id="security" name="security" placeholder="Security"  class="form-control" rows="2"><?php echo $information?$information[0]["security"]:""; ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="manager">Project manager (If applicable): </label>
							<div class="col-sm-5">
							<textarea id="manager" name="manager" placeholder="Project manager"  class="form-control" rows="2"><?php echo $information?$information[0]["manager"]:""; ?></textarea>
							</div>
						</div>

				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>UTILITY COMPANY EMERGENCY CONTACTS</strong>
				</div>
				<div class="panel-body">								
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="electric">Electric: *</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="electric" name="electric" value="<?php echo $information?$information[0]["electric"]:"Enmax 403 310 2010 / 311"; ?>" placeholder="Electric" required <?php echo $deshabilitar; ?> />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="water">Water: *</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="water" name="water" value="<?php echo $information?$information[0]["water"]:"Enmax 403 310 2010 / 311"; ?>" placeholder="Water" required <?php echo $deshabilitar; ?> />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="gas">Gas (if applicable):</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="gas" name="gas" value="<?php echo $information?$information[0]["police"]:"Atco 403 245 7444"; ?>" placeholder="Gas" required <?php echo $deshabilitar; ?> />
							</div>
						</div>

				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>MEDICAL EMERGENCY</strong>
				</div>
				<div class="panel-body">								
<p class="text-success text-left">Call the following personnel trained in CPR and First Aid to provide the
required assistance prior to the arrival of the professional medical help:</p>						

						<div class="form-group">
							<label class="col-sm-4 control-label" for="contact1">Name: *</label>
							<div class="col-sm-5">
								<select name="contact1" id="contact1" class="form-control" <?php echo $deshabilitar; ?>>
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information && $information[0]["emergency_user_1"] == $workersList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>								
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="contact2">Name: *</label>
							<div class="col-sm-5">
								<select name="contact2" id="contact2" class="form-control" <?php echo $deshabilitar; ?>>
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information && $information[0]["emergency_user_2"] == $workersList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>								
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="directions">Directions to get to the hospital: </label>
							<div class="col-sm-5">
							<textarea id="directions" name="directions" placeholder="Directions to get to the hospital"  class="form-control" rows="2"><?php echo $information?$information[0]["directions"]:""; ?></textarea>
							</div>
						</div>						

				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>FIRE EMERGENCY</strong>
				</div>
				<div class="panel-body">								
<p class="text-success text-left">If the fire alarm is not available, notify the site personnel about the fire
emergency by the following means (check applicable):</p>						

						<div class="form-group">
							<label class="col-sm-4 control-label" for="contact1"></label>
							<div class="col-sm-5">
<input type="checkbox" id="voice" name="voice" value=1 <?php if($information && $information[0]["voice"]){echo "checked";} ?> onclick="valid_field()"> Voice Communication<br>
<input type="checkbox" id="radio" name="radio" value=1 <?php if($information && $information[0]["radio"]){echo "checked";} ?> onclick="valid_field()"> Radio<br>
<input type="checkbox" id="phone" name="phone" value=1 <?php if($information && $information[0]["phone"]){echo "checked";} ?> onclick="valid_field()"> Phone Paging<br>
<input type="checkbox" id="other" name="other" value=1 <?php if($information && $information[0]["other"]){echo "checked";} ?> onclick="valid_field()"> Other<br>

<?php 
$valorCampo = "";
if($information)
{
	if($information[0]["voice"] || $information[0]["radio"] || $information[0]["phone"] || $information[0]["other"])
	{
		$valorCampo = 1;
	}
}
?>
<input type="hidden" id="hddField" name="hddField" value="<?php echo $valorCampo; ?>"/>
							
							</div>
						</div>
						
<?php 
	$mostrar = "none";
	if($information && $information[0]["other"]==1){
		$mostrar = "inline";
	}
?>
						
						<div class="form-group" id="div_specify" style="display: <?php echo $mostrar; ?>">
							<label class="col-sm-4 control-label" for="specify">Specify : </label>
							<div class="col-sm-5">
								<input type="text" id="specify" name="specify" class="form-control" value="<?php echo $information?$information[0]["specify"]:""; ?>" placeholder="Specify" >
							</div>
						</div>	
						
<p class="text-success text-left">Upon being notified about the fire emergency, occupants must assemble in the designated area :</p>
						
						<div class="form-group">
							<label class="col-sm-4 control-label" for="location">Specify location 1: </label>
							<div class="col-sm-5">
								<input type="text" id="location" name="location" class="form-control" value="<?php echo $information?$information[0]["location"]:""; ?>" placeholder="Specify location" >
							</div>
						</div>	

						<div class="form-group">
							<label class="col-sm-4 control-label" for="location2">Specify location 2: </label>
							<div class="col-sm-5">
								<input type="text" id="location2" name="location2" class="form-control" value="<?php echo $information?$information[0]["location2"]:""; ?>" placeholder="Specify location" >
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="location3">Specify location 3: </label>
							<div class="col-sm-5">
								<input type="text" id="location3" name="location3" class="form-control" value="<?php echo $information?$information[0]["location3"]:""; ?>" placeholder="Specify location" >
							</div>
						</div>
						

				</div>
			</div>
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

								
						<div class="form-group">
							<div class="row" align="center">
								<div style="width:80%;" align="center">
									<div id="div_load" style="display:none">		
										<div class="progress progress-striped active">
											<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
												<span class="sr-only">45% completado</span>
											</div>
										</div>
									</div>
									<div id="div_error" style="display:none">			
										<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
									</div>
								</div>
							</div>
						</div>								

	
</form>

</div>