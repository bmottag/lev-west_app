<script type="text/javascript" src="<?php echo base_url("assets/js/validate/serviceorder/preventive_maintenance.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Preventive Maintenance
		<br><small>Add/Edit Preventive Maintenance</small>
	</h4>
</div>

<div class="modal-body">
	<form name="formMaintenance" id="formMaintenance" role="form" method="post" >
		<input type="hidden" id="hddIdEquipment" name="hddIdEquipment" value="<?php echo $idEquipment; ?>"/>
		<input type="hidden" id="hddIdMaintenance" name="hddIdMaintenance" value="<?php echo $information?$information[0]["id_preventive_maintenance"]:""; ?>"/>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="maintenance_type"> Maintenance Type: * </label>
					<select name="maintenance_type" id="maintenance_type" class="form-control" required >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($infoTypeMaintenance); $i++) { ?>
							<option value="<?php echo $infoTypeMaintenance[$i]["id_maintenance_type"]; ?>" <?php if($information){ if($information[0]["fk_id_maintenance_type"] == $infoTypeMaintenance[$i]["id_maintenance_type"]) { echo "selected"; }}  ?>><?php echo $infoTypeMaintenance[$i]["maintenance_type"]; ?></option>		
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="type">Maintenance Description: *</label>
					<textarea id="description" name="description" placeholder="Maintenance Description" class="form-control" rows="3" required><?php echo $information?$information[0]["maintenance_description"]:""; ?></textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="verification">System verification by?: *</label>
					<select name="verification" id="verification" class="form-control" required >
						<option value=''>Select...</option>
						<option value=1 <?php if($information){if($information[0]["verification_by"] == 1) { echo "selected"; }} ?> > Hours/Kilometers</option>
						<option value=2 <?php if($information){if($information[0]["verification_by"] == 2) { echo "selected"; }} ?> > Date</option>
					</select>
				</div>
			</div>

<?php 
	$viewHours = "none";
	$viewDate = "none";
	if($information){
		if($information[0]["verification_by"]==1){
			$viewHours = "block";
		}else{
			$viewDate = "block";
		}
	}
?>

			<div class="col-sm-6" id="next_hours" style="display: <?php echo $viewHours; ?>">
				<label class="control-label" for="type">Next Hours/Kilometers Maintenance: *</label>
				<input type="text" id="next_hours_maintenance" name="next_hours_maintenance" class="form-control" placeholder="Next Hours/Kilometers Maintenance" value="<?php echo $information?$information[0]["next_hours_maintenance"]:""; ?>" >
			</div>
			<div class="col-sm-6" id="next_date" style="display: <?php echo $viewDate; ?>">
				<label class="control-label" for="type">Next Date Maintenance <small>(YYYY-MM-DD)</small>: *</label>
				<input type="text" id="next_date_maintenance" name="next_date_maintenance" class="form-control" placeholder="Next Date Maintenance (YYYY-MM-DD)" value="<?php echo $information?$information[0]["next_date_maintenance"]:""; ?>" >
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="maintenance_status">Status: *</label>
					<select name="maintenance_status" id="maintenance_status" class="form-control" required >
						<option value=''>Select...</option>
						<option value=1 <?php if($information){if($information[0]["maintenance_status"] == 1) { echo "selected"; }} ?>  > Active</option>
						<option value=2 <?php if($information){if($information[0]["maintenance_status"] == 2) { echo "selected"; }} ?>  > Inactive</option>
					</select>
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
					<button type="button" id="btnSubmitMaintenance" name="btnSubmitMaintenance" class="btn btn-violeta" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>