<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/fire_watch_setup.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Fire Watch Information
	<br><small>Add/Edit Fire Watch</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $idJob; ?>"/>
		<input type="hidden" id="hddMetodo" name="hddMetodo" value="<?php echo $metodo; ?>"/>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="address">Facility/Building Address: *</label>
					<input type="text" id="address" name="address" class="form-control" value="<?php echo $information?$information[0]["building_address"]:""; ?>" placeholder="Facility/Building Address" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="supervisor">Supervisor: *</label>
					<select name="supervisor" id="supervisor" class="form-control" >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($workersList); $i++) { ?>
							<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information && $information[0]["fk_id_supervisor"] == $workersList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="systemsShutdown">Systems Shutdown: </label><br>
					<input type="checkbox" id="fire_alarm" name="fire_alarm" value=1 <?php if($information && $information[0]["fire_alarm"]){echo "checked";} ?> > Fire Alarm System<br> 	
					<input type="checkbox" id="fire_sprinkler" name="fire_sprinkler" value=1 <?php if($information && $information[0]["fire_sprinkler"]){echo "checked";} ?> > Fire Sprinkler System<br>  	
					<input type="checkbox" id="standpipe" name="standpipe" value=1 <?php if($information && $information[0]["standpipe"]){echo "checked";} ?> > Standpipe System<br>   		
					<input type="checkbox" id="fire_pump" name="fire_pump" value=1 <?php if($information && $information[0]["fire_pump"]){echo "checked";} ?> > Fire Pump System<br>
					<input type="checkbox" id="fire_suppression" name="fire_suppression" value=1 <?php if($information && $information[0]["fire_suppression"]){echo "checked";} ?> > Special Fire Suppression System
					<input type="text" id="other" name="other" class="form-control" value="<?php echo $information?$information[0]["other"]:""; ?>" placeholder="Other" >
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
					<label class="control-label" for="systemsShutdown">System(s) Out of Service </label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<script>
						$( function() {
							$( "#date" ).datepicker({
								minDate: '1',
								dateFormat: 'yy-mm-dd'
							});
						});
					</script>
					<label class="control-label" for="date">Date: *</label>
					<input type="text" class="form-control" id="date" name="date" value="<?php echo $date; ?>" placeholder="Date" required />
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label for="type" class="control-label">Time: *</label>
					<select name="time" id="time" class="form-control" required>
						<option value='' >Select...</option>
						<?php
							for ($i = 0; $i < 24; $i++) {
								$i = $i<10?"0".$i:$i;
						?>
								<option value='<?php echo $i; ?>' <?php if($time == $i) { echo "selected"; }  ?> ><?php echo $i; ?></option>
						<?php } ?>									
					</select>
				</div>
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

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="systemsShutdown">System(s) Restored Online </label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<script>
						$( function() {
							$( "#dateRestored" ).datepicker({
								minDate: '1',
								dateFormat: 'yy-mm-dd'
							});
						});
					</script>
					<label class="control-label" for="dateRestored">Date: *</label>
					<input type="text" class="form-control" id="dateRestored" name="dateRestored" value="<?php echo $date2; ?>" placeholder="Date" />
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label for="type" class="control-label">Time: *</label>
					<select name="timeRestored" id="timeRestored" class="form-control" >
						<option value='' >Select...</option>
						<?php
							for ($i = 0; $i < 24; $i++) {
								$i = $i<10?"0".$i:$i;
						?>
								<option value='<?php echo $i; ?>' <?php if($time2 == $i) { echo "selected"; }  ?> ><?php echo $i; ?></option>
						<?php } ?>									
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="areas">Areas/Zones Requiring Fire Watch Patrols: </label>
					<textarea id="areas" name="areas" placeholder="Areas/Zones Requiring Fire Watch Patrols" class="form-control" rows="3"><?php echo $information?$information[0]["areas"]:""; ?></textarea>
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