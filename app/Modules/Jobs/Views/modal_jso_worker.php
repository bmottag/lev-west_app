<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/jso_workers.js?v=1.0.0"); ?>"></script>

<script>
$(document).ready(function () {
		
    $("#license1").on("click", function() {
		$("#div_licencia").css("display", "inline");
		$('#license_number').val("");
    });
	
    $("#license2").on("click", function() {
		$("#div_licencia").css("display", "none");
		$('#license_number').val("");
    });
	
});
</script>


<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">WORKER</h4>
</div>

<div class="modal-body">
	<form  name="formWorker" id="formWorker" role="form" method="post" >
		<input type="hidden" id="hddidJobJso" name="hddidJobJso" value="<?php echo $idJobJso; ?>"/>
		<input type="hidden" id="hddidJobJsoWorker" name="hddidJobJsoWorker" value="<?php echo $information?$information[0]["id_job_jso_worker"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label for="name">Name: *</label>
					<input type="text" id="name" name="name" class="form-control" value="<?php echo $information?$information[0]["name"]:""; ?>" placeholder="Name" required >
				</div>
			</div>
		
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label for="city">City:</label>
					<input type="text" id="city" name="city" class="form-control" value="<?php echo $information?$information[0]["city"]:""; ?>" placeholder="City" >
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label for="worksfor">Company Name:</label>
					<input type="text" id="worksfor" name="worksfor" class="form-control" value="<?php echo $information?$information[0]["works_for"]:""; ?>" placeholder="Company Name" >
				</div>
			</div>
		
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label for="position">Position:</label>
					<input type="text" id="position" name="position" class="form-control" value="<?php echo $information?$information[0]["position"]:""; ?>" placeholder="Position" >
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label for="phone_number">Worker mobile number: *</label>
					<input type="number" id="phone_number" name="phone_number" class="form-control" value="<?php echo $information?$information[0]["works_phone_number"]:""; ?>" placeholder="Worker mobile number" required >
				</div>
			</div>
		
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label for="emergency_contact">Emergency Contact/Phone number:</label>
					<input type="number" id="emergency_contact" name="emergency_contact" class="form-control" value="<?php echo $information?$information[0]["emergency_contact"]:""; ?>" placeholder="Emergency Contact" >
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label for="license">Driver’s License Required:</label>
					<br>
					<label class="radio-inline">
						<input type="radio" name="license" id="license1" value=1 <?php if($information && $information[0]["driver_license_required"] == 1) { echo "checked"; }  ?>>Yes
					</label>
					<label class="radio-inline">
						<input type="radio" name="license" id="license2" value=2 <?php if($information && $information[0]["driver_license_required"] == 2) { echo "checked"; }  ?>>No
					</label>
				</div>
			</div>
			
<?php 
//si pide licencia entonces mostrar el campo de numero de licencia
	$mostrar = "none";
	if($information && $information[0]["driver_license_required"]==1){
		$mostrar = "inline";
	}
?>
		
			<div class="col-sm-6" id="div_licencia" style="display: <?php echo $mostrar; ?>">
				<div class="form-group text-left">
					<label for="license_number">Driver license number : *</label>
					<input type="text" id="license_number" name="license_number" class="form-control" value="<?php echo $information?$information[0]["license_number"]:""; ?>" placeholder="Driver license number" >
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<button type="button" id="btnSubmitWorker" name="btnSubmitWorker" class="btn btn-primary" >
				Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
			</button> 
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
		
	</form>
</div>