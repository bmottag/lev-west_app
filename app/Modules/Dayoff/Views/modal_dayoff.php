<script type="text/javascript" src="<?php echo base_url("assets/js/validate/dayoff/dayoffModal.js?v=2.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Day's OFF
	<br><small>Quick reminder ⏰: you need 72 hours’ notice for a regular day off, or 24 hours’ notice for a family/medical request. 😊.</small>
	</h4>
</div>

<div class="modal-body">
	<form  name="form" id="form" role="form" method="post" >

		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="attachment_number">Type : *</label>
					<select name="type" id="type" class="form-control" required>
						<option value="">Select...</option>
						<option value=1 >Family/medical appointment</option>
						<option value=2 >Regular</option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="observation">Give us a quick Description: *</label>
					<textarea id="observation" name="observation" class="form-control" rows="3"></textarea>
				</div>
			</div>
		</div>

<script>
	$( function() {
		$( "#date" ).datepicker({
			minDate: '1',
			dateFormat: 'yy-mm-dd'
		});
	});
</script>
		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="date">Date : * <small>Please enter a valid date (YYYY-MM-DD)</small></label>
					<input type="text" class="form-control" id="date" name="date" value="" placeholder="Date" required />
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
				<div class="alert alert-danger">
					<span class="glyphicon glyphicon-remove"></span>
					<span id="span_msj"></span>
				</div>		
			</div>	
		</div>
			
	</form>
</div>