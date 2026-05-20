<script type="text/javascript" src="<?php echo base_url("assets/js/validate/verify.js?v=7.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">WORKER VERIFICATION
	<br><small>
		<?php 
			echo "<strong>Name: </strong>" . $information[0]["first_name"] . " " . $information[0]["last_name"]; 
		?>
	</small>
	</h4>
</div>

<div class="modal-body">
	<form  name="formVerify" id="formVerify" role="form" method="post" >
		<input type="hidden" id="hddIdUser" name="hddIdUser" value="<?php echo $idUser; ?>"/>
		<input type="hidden" id="hddTable" name="hddTable" value="<?php echo $table; ?>"/>
		<input type="hidden" id="backURL" name="backURL" value="<?php echo $backURL; ?>"/>
		<input type="hidden" id="hddIdSafetyWorker" name="hddIdSafetyWorker" value="<?php echo $idSafetyWorker; ?>"/>
		<input type="hidden" id="hddIdRecord" name="hddIdRecord" value="<?php echo $idRecord; ?>"/>
		<input type="hidden" id="hddUserType" name="hddUserType" value="<?php echo $userType; ?>"/>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="login">User: *</label>
					<input type="text" id="login" name="login" class="form-control" placeholder="User" value="" required autofocus >
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="password">Password: *</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="Password" value="" >
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmitVerification" name="btnSubmitVerification" class="btn btn-primary" >
						Verify and save your Signature <span class="glyphicon glyphicon-check" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>

		<div class="form-group">
			<div id="div_load_message" style="display:none">		
				<div class="progress progress-striped active">
					<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
						<span class="sr-only">45% completado</span>
					</div>
				</div>
			</div>
			<div id="div_error_message" style="display:none">	
				<div class="alert alert-danger">
					<span class="glyphicon glyphicon-remove"></span>
					<span id="span_msj_error"></span>
				</div>		
			</div>	
		</div>
		
	</form>
</div>