<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dayoff/approvedModal.js?v=2.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">DAY OFF
	<br><small>Approved or denied. If it is denied pleas write an observation. </small>
	</h4>
</div>

<div class="modal-body">
	<form  name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddIdParam" name="hddIdParam" value="<?php echo $idDayoff; ?>"/>

		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="date">Status : *</label>
					<select name="state" id="state" class="form-control" required>
						<option value="">Select...</option>
						<option value=2 >Approved</option>
						<option value=3 >Denied</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="observation">Observation : *</label>
					<textarea id="observation" name="observation" class="form-control" rows="3"></textarea>
				</div>
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