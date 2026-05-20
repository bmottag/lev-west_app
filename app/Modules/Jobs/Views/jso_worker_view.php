<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/jso_workers_external.js"); ?>"></script>

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

<div id="page-wrapper">
	<br>
	
	<div class="row">

		<div class="col-lg-6">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-edit"></i> <strong>JSO - GENERAL INFORMATION</strong>
				</div>
				<div class="panel-body">

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

					<strong>Date JSO: </strong><?php echo $JSOInfo[0]['date_issue_jso']; ?><br>
					<strong>Job Code/Name: </strong><br><?php echo $JSOInfo[0]['job_description']; ?><br>
					<strong>Potential hazards: </strong><br><?php echo $JSOInfo[0]['potential_hazards']; ?>

					
				</div>
			</div>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-edit fa-fw"></i> Worker Signature
				</div>

				<div class="panel-body">
				
					<div class="form-group">

						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $information[0]['signature'] ?? null,
							'formAction'      => base_url('jobs/save_signature_jso'),
							'height'          => 200,
							'id' 			  => 'worker_' . $information[0]['id_job_jso_worker'],
							'extraValue' 	  => $information[0]['id_job_jso_worker'],
							'otherValue' 	  => 'worker'
						]) ?>

					</div>
			
				</div>
			</div>
		</div>	

		<div class="col-lg-6">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-edit"></i> <strong>Worker Information</strong>
				</div>
				<div class="panel-body">
					<form  name="formWorker" id="formWorker" class="form-horizontal" method="post"  >
						<input type="hidden" id="hddidJobJso" name="hddidJobJso" value="<?php echo $information?$information[0]['fk_id_job_jso']:''; ?>"/>
						<input type="hidden" id="hddidJobJsoWorker" name="hddidJobJsoWorker" value="<?php echo $information?$information[0]["id_job_jso_worker"]:""; ?>"/>

						<div class="form-group">
							<div class="col-sm-6">
								<label for="placa">Name: *</label>
								<input type="text" id="name" name="name" class="form-control" value="<?php echo $information?$information[0]["name"]:""; ?>" placeholder="Name" required >
							</div>

							<div class="col-sm-6">
								<label for="color">City: *</label>
								<input type="text" id="city" name="city" class="form-control" value="<?php echo $information?$information[0]["city"]:""; ?>" placeholder="City" required>
							</div>						
						</div>

						<div class="form-group">
							<div class="col-sm-6">
								<label for="color">Company Name: *</label>
								<input type="text" id="worksfor" name="worksfor" class="form-control" value="<?php echo $information?$information[0]["works_for"]:""; ?>" placeholder="Company Name" required>
							</div>
							
							<div class="col-sm-6">
								<label for="from">Position: *</label>
								<input type="text" id="position" name="position" class="form-control" value="<?php echo $information?$information[0]["position"]:""; ?>" placeholder="Position" required>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-6">
								<label for="color">Works phone number: *</label>
								<input type="text" id="phone_number" name="phone_number" class="form-control" value="<?php echo $information?$information[0]["works_phone_number"]:""; ?>" placeholder="Works phone number" required >
							</div>
							
							<div class="col-sm-6">
								<label for="from">Emergency Contact/Phone number: *</label>
								<input type="text" id="emergency_contact" name="emergency_contact" class="form-control" value="<?php echo $information?$information[0]["emergency_contact"]:""; ?>" placeholder="Emergency Contact" required>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-6">
								<label for="color">Driver's License Required: *</label>
								<label class="radio-inline">
									<input type="radio" name="license" id="license1" value=1 <?php if($information && $information[0]["driver_license_required"] == 1) { echo "checked"; }  ?>>Yes
								</label>
								<label class="radio-inline">
									<input type="radio" name="license" id="license2" value=2 <?php if($information && $information[0]["driver_license_required"] == 2) { echo "checked"; }  ?>>No
								</label>
							</div>
							<?php 
							//si pide licencia entonces mostrar el campo de numero de licencia
								$mostrar = "none";
								if($information && $information[0]["driver_license_required"]==1){
									$mostrar = "inline";
								}
							?>
							<div class="col-sm-6" id="div_licencia" style="display: <?php echo $mostrar; ?>">
								<label for="from">Driver License Number: *</label>
								<input type="text" id="license_number" name="license_number" class="form-control" value="<?php echo $information?$information[0]["license_number"]:""; ?>" placeholder="Driver license number" >
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

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">							
									<button type="button" id="btnSubmitWorker" name="btnSubmitWorker" class='btn btn-info'>
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