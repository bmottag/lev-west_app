<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/fire_watch.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Fire Watch Form
	<br><small>Add/Edit Fire Watch</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $idJob; ?>"/>
		<input type="hidden" id="hddIdFireWatch" name="hddIdFireWatch" value="<?php echo ($information && isset($information[0]["id_job_fire_watch"]))?$information[0]["id_job_fire_watch"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="address">Facility/Building Address: *</label><br>
					<?php echo $information?$information[0]["building_address"]:""; ?>
					<input type="hidden" id="address" name="address" class="form-control" value="<?php echo $information?$information[0]["building_address"]:""; ?>" placeholder="Facility/Building Address" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="supervisor">Supervisor: *</label><br>
					<?php echo $information?$information[0]["supervisor"]:""; ?>
					<input type="hidden" id="supervisor" name="supervisor" class="form-control" value="<?php echo $information?$information[0]["fk_id_supervisor"]:""; ?>" placeholder="Facility/Building Address" required >
				</div>
			</div>

			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="systemsShutdown">Systems Shutdown: </label><br>
					<?php 
						echo $information[0]['fire_alarm']?"Fire Alarm System<br>":"";
						echo $information[0]['fire_sprinkler']?"Fire Sprinkler System<br>":"";
						echo $information[0]['standpipe']?"Standpipe System<br>":"";
						echo $information[0]['fire_pump']?"Fire Pump System<br>":"";
						echo $information[0]['fire_suppression']?"Special Fire Suppression System<br>":"";
						echo $information[0]['other']?$information[0]['other']:"";
					?>
					<input type="hidden" id="fire_alarm" name="fire_alarm" value="<?php echo $information?$information[0]["fire_alarm"]:""; ?>" > 
					<input type="hidden" id="fire_sprinkler" name="fire_sprinkler" value="<?php echo $information?$information[0]["fire_sprinkler"]:""; ?>" > 
					<input type="hidden" id="standpipe" name="standpipe" value="<?php echo $information?$information[0]["standpipe"]:""; ?>" > 
					<input type="hidden" id="fire_pump" name="fire_pump" value="<?php echo $information?$information[0]["fire_pump"]:""; ?>" > 
					<input type="hidden" id="fire_suppression" name="fire_suppression" value="<?php echo $information?$information[0]["fire_suppression"]:""; ?>" >
					<input type="hidden" id="other" name="other" class="form-control" value="<?php echo $information?$information[0]["other"]:""; ?>" placeholder="Other" >
				</div>
			</div>
		</div>
<?php
	$date = "";
	$time = "";
	if($information) { 
		$CompleteDateOut = $information[0]['date_out'];
		$date = substr($CompleteDateOut, 0, 10); 
		$time = substr($CompleteDateOut, 11, 2);
	}
?>
		<br>
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="systemsShutdown">System(s) Out of Service </label><br>
					<?php echo $information[0]['date_out']; ?>
					<input type="hidden" class="form-control" id="date" name="date" value="<?php echo $date; ?>" />
					<input type="hidden" class="form-control" id="time" name="time" value="<?php echo $time; ?>" />
				</div>
			</div>

<?php
	$date2 = "";
	$time2 = "";
	if($information) { 
		$CompleteDateRestored = $information[0]['date_restored'];
		$date2 = substr($CompleteDateRestored, 0, 10); 
		$time2 = substr($CompleteDateRestored, 11, 2);
	}
?>

			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="systemsShutdown">System(s) Restored Online </label><br>
					<?php echo $information[0]['date_restored']; ?>
					<input type="hidden" class="form-control" id="dateRestored" name="dateRestored" value="<?php echo $date2; ?>" />
					<input type="hidden" class="form-control" id="timeRestored" name="timeRestored" value="<?php echo $time2; ?>" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="areas">Areas/Zones Requiring Fire Watch Patrols: </label><br>
					<?php echo $information[0]["areas"]; ?>
					<input type="hidden" class="form-control" id="areas" name="areas" value="<?php echo $information?$information[0]["areas"]:""; ?>" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="training">Fire Watch Training Completed? *</label>
					<select name="training" id="training" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 >Yes</option>
						<option value=2 >No</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="training">Mandatory PPE Required, select those that apply *</label><br>
					<input type="checkbox" id="safety_shoes" name="safety_shoes" value=1 > Safety Boots  <br>
					<input type="checkbox" id="safety_vest" name="safety_vest" value=1 > Safety Vest  <br>
					<input type="checkbox" id="safety_glasses" name="safety_glasses" value=1 > Safety Glasses <br>
					<input type="checkbox" id="hearing_protection" name="hearing_protection" value=1 >  Hearing Protection<br>
					<input type="checkbox" id="snow_cleets" name="snow_cleets" value=1 > Snow Cleets<br>
					<input type="checkbox" id="dust_proof_mask" name="dust_proof_mask" value=1 > Dust Proof Mask  <br>
					<input type="checkbox" id="hard_hat" name="hard_hat" value=1 > Hard Hat <br>
					<input type="checkbox" id="gloves" name="gloves" value=1 > Gloves  <br>
					<input type="text" id="other_ppe" name="other_ppe" class="form-control" placeholder="Other, specify" >
				</div>
			</div>

			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="documents">Documents, Directives, and Equipment *</label><br>
					<input type="checkbox" id="operational_impacts" name="operational_impacts" value=1 > Operational Impacts    <br>
					<input type="checkbox" id="map_routing" name="map_routing" value=1 > Map's Routing  <br>
					<input type="checkbox" id="raic_access" name="raic_access" value=1 > RAIC Access  <br>
					<input type="checkbox" id="radio" name="radio" value=1 >  Radio/Cell Phone<br>
					<input type="checkbox" id="emergency_contacts" name="emergency_contacts" value=1 > Emergency Contacts<br>
					<input type="checkbox" id="keys_access" name="keys_access" value=1 > Keys and Access
				</div>
			</div>
		</div>

		<div class="form-group">
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

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>