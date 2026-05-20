<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/vehicle.js?v=1.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Vehicle Form
	<br><small>Add/Edit Vehicle</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_vehicle"]:""; ?>"/>
		
<?php
if($companyType == 2){ //si es subcontractor me deja seleccionar un sucontratista
	$labelFecha = "Arrival date";
?>
		<input type="hidden" id="type1" name="type1" value=2 /><!-- Si es subcontractor entonces es RENTAL -->
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="company">Company : *</label>
					<select name="company" id="company" class="form-control" >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($company); $i++) { ?>
							<option value="<?php echo $company[$i]["id_company"]; ?>" <?php if($information && $information[0]["fk_id_company"] == $company[$i]["id_company"]) { echo "selected"; }  ?>><?php echo $company[$i]["company_name"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
				
				<!-- //si es subcontractor deja activarlo o inactivarlo -->
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="state">State : *</label>
					<select name="state" id="state" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information && $information[0]["state"] == 1) { echo "selected"; }  ?>>Active</option>
						<option value=2 <?php if($information && $information[0]["state"] == 2) { echo "selected"; }  ?>>Inactive</option>
					</select>
				</div>
			</div>
		</div>
<?php
}else{ //si es vci carga el campo con el id de VCI
	$labelFecha = "Manufacturer date";
?>
		<input type="hidden" id="company" name="company" value=1 />
		<input type="hidden" id="type1" name="type1" value=1 /><!-- Si es VCI entonces el FLEET -->
<?php }  ?>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="type2">Type : *</label>	
					<select name="type2" id="type2" class="form-control" >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($vehicleType); $i++) { ?>
							<option value="<?php echo $vehicleType[$i]["id_type_2"]; ?>" <?php if($information && $information[0]["type_level_2"] == $vehicleType[$i]["id_type_2"]) { echo "selected"; }  ?>><?php echo $vehicleType[$i]["type_2"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="make">Make  : *</label>
					<input type="text" id="make" name="make" class="form-control" value="<?php echo $information?$information[0]["make"]:""; ?>" placeholder="Make" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="model">Model : *</label>
					<input type="text" id="model" name="model" class="form-control" value="<?php echo $information?$information[0]["model"]:""; ?>" placeholder="Model" required >
				</div>
			</div>
		
			<div class="col-sm-6">
				<div class="form-group text-left">
<script>
	$( function() {
		$( "#manufacturer" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			yearRange: "-40:-0", // last 40 years
		});
	});
</script>
					<label class="control-label" for="manufacturer"><?php echo $labelFecha; ?> : *</label>
					<input type="text" class="form-control" id="manufacturer" name="manufacturer" value="<?php echo $information?$information[0]["manufacturer_date"]:""; ?>" placeholder="<?php echo $labelFecha; ?>" required />
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">	
				<div class="form-group text-left">
					<label class="control-label" for="description">Description : *</label>
					<textarea id="description" name="description" placeholder="Description" class="form-control" rows="3"><?php echo $information?$information[0]["description"]:""; ?></textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="unitNumber">Unit number: *</label>
					<input type="text" id="unitNumber" name="unitNumber" class="form-control" value="<?php echo $information?$information[0]["unit_number"]:""; ?>" placeholder="Unit number" required >
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="vinNumber">VIN number: *</label>
					<input type="text" id="vinNumber" name="vinNumber" class="form-control" value="<?php echo $information?$information[0]["vin_number"]:""; ?>" placeholder="VIN number" required >
				</div>
			</div>
		</div>
		

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="hours">Hours/Kilometers : *</label>
					<input type="text" id="hours" name="hours" class="form-control" value="<?php echo $information?$information[0]["hours"]:""; ?>" placeholder="Hours/Kilometers" required >
				</div>
			</div>
<?php if(!$information){ ?>		
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="oilChange">Next Oil change : *</label>
					<input type="text" id="oilChange" name="oilChange" class="form-control" value="<?php echo $information?$information[0]["oil_change"]:""; ?>" placeholder="Hours/Kilometers" required >
				</div>
			</div>
<?php } ?>
		</div>

<?php
if($companyType == 1){ //si es VCI
?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="state">State : *</label>
					<select name="state" id="state" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information && $information[0]["state"] == 1) { echo "selected"; }  ?>>Active</option>
						<option value=2 <?php if($information && $information[0]["state"] == 2) { echo "selected"; }  ?>>Inactive</option>
					</select>
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
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>