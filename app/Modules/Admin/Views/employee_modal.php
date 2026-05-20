<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/employee.js"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Employee Form
	<br><small>Add/Edit Employee</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_user"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="firstName">First Name: *</label>
					<input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $information?$information[0]["first_name"]:""; ?>" placeholder="First Name" required >
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="lastName">Last Name: *</label>
					<input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $information?$information[0]["last_name"]:""; ?>" placeholder="Last Name" required >
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="user">User Name: *</label>
					<input type="text" id="user" name="user" class="form-control" value="<?php echo $information?$information[0]["log_user"]:""; ?>" placeholder="User Name" required >
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="email">Email: *</label>
					<input type="email" class="form-control" id="email" name="email" value="<?php echo $information?$information[0]["email"]:""; ?>" placeholder="Email" />
				</div>
			</div>
		</div>

<script>
	$( function() {
		$( "#birth" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			yearRange: "-60:-15", // last 60 years
		});
	});
</script>		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="birth">Date of birth: *</label>
					<input type="text" class="form-control" id="birth" name="birth" value="<?php echo $information?$information[0]["birthdate"]:""; ?>" placeholder="Date of birth" required />
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="movilNumber">Mobile Number: *</label>
					<input type="text" id="movilNumber" name="movilNumber" class="form-control" value="<?php echo $information?$information[0]["movil"]:""; ?>" placeholder="Mobile Number" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="insuranceNumber">Social Insurance Number: *</label>
					<input type="text" id="insuranceNumber" name="insuranceNumber" class="form-control" value="<?php echo $information?$information[0]["social_insurance"]:""; ?>" placeholder="Social Insurance Number" required >
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="healthNumber">Health Number: *</label>
					<input type="text" id="healthNumber" name="healthNumber" class="form-control" value="<?php echo $information?$information[0]["health_number"]:""; ?>" placeholder="Health Number" required >
				</div>
			</div>
		</div>
				
		<div class="row">			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="address">Address:</label>
					<input type="text" id="address" name="address" class="form-control" value="<?php echo $information?$information[0]["address"]:""; ?>" placeholder="Address" >
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="postalCode">Postal Code:</label>
					<input type="text" id="postalCode" name="postalCode" class="form-control" value="<?php echo $information?$information[0]["postal_code"]:""; ?>" placeholder="Postal Code" >
				</div>
			</div>
		</div>
				
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="perfil">User Role: *</label>					
					<select name="perfil" id="perfil" class="form-control" required>
						<option value="">Select...</option>
						<?php for ($i = 0; $i < count($roles); $i++) { ?>
							<option value="<?php echo $roles[$i]["id_rol"]; ?>" <?php if($information && $information[0]["perfil"] == $roles[$i]["id_rol"]) { echo "selected"; }  ?>><?php echo $roles[$i]["rol_name"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
			
	<?php if($information){ ?>
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="state">Status: *</label>
					<select name="state" id="state" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information[0]["state"] == 1) { echo "selected"; }  ?>>Active</option>
						<option value=2 <?php if($information[0]["state"] == 2) { echo "selected"; }  ?>>Inactive</option>
					</select>
				</div>
			</div>
	<?php } ?>
		
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="perfil">Employee Hour Rate:</label>	
					<?php
						if($information){
							$unitPrice = $information[0]['employee_rate'];
							$unitPrice = $unitPrice?$unitPrice:0;
						}
					?>				
					<input type="text" id="employee_rate" name="employee_rate" class="form-control" value="<?php echo $information?($information[0]['employee_rate']?$information[0]['employee_rate']:0):0; ?>" placeholder="Employee Hour Rate" >
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="perfil">Employee Type: *</label>				
					<select name="employee_type" id="employee_type" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information && $information[0]["employee_type"] == 1) { echo "selected"; }  ?>>Field</option>
						<option value=2 <?php if($information && $information[0]["employee_type"] == 2) { echo "selected"; }  ?>>Admin</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="perfil">Is Subcontractor?: *</label>				
					<select name="employee_subcontractor" id="employee_subcontractor" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information && $information[0]["employee_subcontractor"] == 1) { echo "selected"; }  ?>>Yes</option>
						<option value=2 <?php if($information && $information[0]["employee_subcontractor"] == 2) { echo "selected"; }  ?>>No</option>
					</select>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="perfil">Is using bank time?:</label>				
					<select name="bank_time" id="bank_time" class="form-control">
						<option value=''>Select...</option>
						<option value=1 <?php if($information && $information[0]["bank_time"] == 1) { echo "selected"; }  ?>>Yes</option>
						<option value=2 <?php if($information && $information[0]["bank_time"] == 2) { echo "selected"; }  ?>>No</option>
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
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>