<script type="text/javascript" src="<?php echo base_url("assets/js/validate/serviceorder/service_order.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Service Order Form
		<br><small>
				<?php 
					echo "<b>" . $maintenanceTypeDescription . "</b>"; 
					echo "<br><b>Description: </b><br>" . $maintenanceDescription; 
					echo $currentMaintenance;
					echo $nextMaintenance;
				?>
			</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddIdServiceOrder" name="hddIdServiceOrder" value="<?php echo ($information && isset($information[0]["id_service_order"]))?$information[0]["id_service_order"]:""; ?>"/>
		<input type="hidden" id="hddIdEquipment" name="hddIdEquipment" value="<?php echo $information?$information[0]["fk_id_equipment"]:$idEquipment; ?>"/>
		<input type="hidden" id="hddIdTime" name="hddIdTime" value="<?php echo ($information && isset($information[0]["id_time"]))?$information[0]["id_time"]:""; ?>"/>
		<input type="hidden" id="hddTimeDate" name="hddTimeDate" value="<?php echo ($information && isset($information[0]["time_date"]))?$information[0]["time_date"]:""; ?>"/>
		<input type="hidden" id="hddTime" name="hddTime" value="<?php echo ($information && isset($information[0]["time"]))?$information[0]["time"]:""; ?>"/>
		<input type="hidden" id="hddStatus" name="hddStatus" value="<?php echo ($information && isset($information[0]["service_status"]))?$information[0]["service_status"]:""; ?>"/>
		<input type="hidden" id="hddIdMaintenance" name="hddIdMaintenance" value="<?php echo $information?$information[0]["fk_id_maintenace"]:$idMaintenance; ?>"/>
		<input type="hidden" id="hddMaintenanceType" name="hddMaintenanceType" value="<?php echo $maintenanceType; ?>"/>
		<input type="hidden" id="hddIdCanBeUsed" name="hddIdCanBeUsed" value="<?php echo $information?$information[0]["can_be_used"]:1; ?>"/>
		<input type="hidden" id="hddIdAssignedBy" name="hddIdAssignedBy" value="<?php echo $information?$information[0]["fk_id_assign_by"]:""; ?>"/>
		<input type="hidden" id="hddIdAssignedTo" name="hddIdAssignedTo" value="<?php echo $information?$information[0]["fk_id_assign_to"]:""; ?>"/>
		<input type="hidden" id="hddMaintenanceDescription" name="hddMaintenanceDescription" value="<?php echo $maintenanceDescriptionSMS; ?>"/>
		<input type="hidden" id="hour" name="hour" value="<?php echo $information?$information[0]["hours"]:""; ?>" >
<?php
	//Disabled fields
	$deshabilitar = '';
	$userRol = session()->get('rol');
	if($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_SAFETY){
		$deshabilitar = '';
	}
?>	
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="assign_to">Assign to: *</label>
					<select name="assign_to" id="assign_to" class="form-control" required >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($workersList); $i++) { ?>
							<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information){ if($information[0]["fk_id_assign_to"] == $workersList[$i]["id_user"]) { echo "selected"; }}  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
	
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="priority">Priority: *</label>
					<select name="priority" id="priority" class="form-control" required <?php echo $deshabilitar; ?> >
						<option value=''>Select...</option>
						<?php
						if($priorityList) {
							foreach ($priorityList as $priority) {
						?>
							<option value="<?php echo $priority["status_slug"]; ?>" <?php if($information && $information[0]["priority"] == $priority["status_slug"]) { echo "selected"; }  ?> ><?php echo $priority["status_name"]; ?> </option>
						<?php
							}
						}
						?>
					</select>
				</div>
			</div>
		</div>

		<?php if ($information) { ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="damages">Damages: *</label>
					<select name="damages" id="damages" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information && $information[0]["damages"] == 1 ) { echo "selected"; }  ?> >Yes</option>
						<option value=2 <?php if($information && $information[0]["damages"] == 2 ) { echo "selected"; }  ?> >No</option>
					</select>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="can_be_used">Equipment can be used?: *</label>
					<select name="can_be_used" id="can_be_used" class="form-control" required>
						<option value=1 <?php if($information && $information[0]["can_be_used"] == 1 ) { echo "selected"; }  ?> >Yes</option>
						<option value=2 <?php if($information && $information[0]["can_be_used"] == 2 ) { echo "selected"; }  ?> >No</option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="services">Services: *</label><br>
					<input type="checkbox" id="purchasing_staff" name="purchasing_staff" value=1 <?php if($information && $information[0]["purchasing_staff"]){echo "checked";} ?> > Purchasing Staff <br>
					<input type="checkbox" id="mechanic" name="mechanic" value=1 <?php if($information && $information[0]["mechanic"]){echo "checked";} ?> > In-house Mechanic <br>
					<input type="checkbox" id="engine_oil" name="engine_oil" value=1 <?php if($information && $information[0]["engine_oil"]){echo "checked";} ?> > Engine Oil <br>
					<input type="checkbox" id="transmission_oil" name="transmission_oil" value=1 <?php if($information && $information[0]["transmission_oil"]){echo "checked";} ?> >  Transmission Oil<br>
					<input type="checkbox" id="hydraulic_oil" name="hydraulic_oil" value=1 <?php if($information && $information[0]["hydraulic_oil"]){echo "checked";} ?> > Hydraulic Oil<br>
					<input type="text" id="other" name="other" class="form-control" placeholder="Other, specify" value="<?php echo $information?$information[0]["other"]:""; ?>" >
				</div>
			</div>

			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="documents"></label><br>
					<input type="checkbox" id="fuel" name="fuel" value=1 <?php if($information && $information[0]["fuel"]){echo "checked";} ?> > Fuel<br>
					<input type="checkbox" id="filters" name="filters" value=1 <?php if($information && $information[0]["filters"]){echo "checked";} ?> > Filters<br>
					<input type="checkbox" id="parts" name="parts" value=1 <?php if($information && $information[0]["parts"]){echo "checked";} ?> > Parts  <br>
					<input type="checkbox" id="blade" name="blade" value=1 <?php if($information && $information[0]["blade"]){echo "checked";} ?> > Blade  <br>
					<input type="checkbox" id="ripper" name="ripper" value=1 <?php if($information && $information[0]["ripper"]){echo "checked";} ?> > Ripper  <br>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ($information) { ?>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group text-left">
						<label class="control-label" for="status">Status: *</label>
						<select name="status" id="status" class="form-control" required >
							<option value=''>Select...</option>
							<?php
							if($statusList) {
								foreach ($statusList as $status) {
							?>
								<option value="<?php echo $status["status_slug"]; ?>" <?php if($information && $information[0]["service_status"] == $status["status_slug"]) { echo "selected"; }  ?> ><?php echo $status["status_name"]; ?> </option>
							<?php
								}
							}
							?>
						</select>
					</div>
				</div>

				<input type="hidden" id="hddVerificationBy" name="hddVerificationBy" value="<?php echo $information[0]["verification_by"]; ?>"/>
				<div class="col-sm-6" id="next_hours" style="display: none">
					<label class="control-label" for="type">Next Hours/Kilometers Maintenance: *</label>
					<input type="text" id="next_hours_maintenance" name="next_hours_maintenance" class="form-control" placeholder="<?php echo $nextMaintenanceValue; ?>" value="<?php echo $information?$information[0]["next_hours"]:""; ?>" >
				</div>
				<div class="col-sm-6" id="next_date" style="display: none">
					<label class="control-label" for="type">Next Date Maintenance <small>(YYYY-MM-DD)</small>: *</label>
					<input type="text" id="next_date_maintenance" name="next_date_maintenance" class="form-control" placeholder="<?php echo $nextMaintenanceValue; ?>" value="<?php echo $information?$information[0]["next_date"]:""; ?>" >
				</div>
			</div>

			<div class="row" id="div_comments" style="display: none">
				<div class="col-sm-12">
					<div class="form-group text-left">
						<label class="control-label" for="type">Comments: *</label>
						<textarea id="comments" name="comments" placeholder="Notes/Observations" class="form-control" rows="3"><?php echo $information?$information[0]["comments"]:""; ?></textarea>
					</div>
				</div>
			</div>
		<?php } ?>
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
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-violeta" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>