<script type="text/javascript" src="<?php echo base_url("assets/js/validate/external/checkout.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Sign-Out Form</h4>
</div>

<div class="modal-body">
	<form name="formCheckout" id="formCheckout" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_checkin"]:""; ?>"/>
		<input type="hidden" id="idProject" name="idProject" value="<?php echo $information?$information[0]["fk_id_job"]:""; ?>"/>
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="company">Full Name:</label>
					<input type="text" id="company" name="company" class="form-control" value="<?php echo $information?$information[0]["worker_name"]:""; ?>" placeholder="Worker name" disabled>
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="contact">Time Sign-In:</label>
					<input type="text" id="contact" name="contact" class="form-control" value="<?php echo $information?$information[0]["checkin_time"]:""; ?>" placeholder="Contact" disabled >
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<label class="control-label" for="contact">Time Sign-Out:</label><br>
					<?php echo date("Y-m-d G:i:s"); ?>
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
					<button type="button" id="btnSubmitCheckOut" name="btnSubmitCheckOut" class="btn btn-primary" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>