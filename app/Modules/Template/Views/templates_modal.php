<script type="text/javascript" src="<?php echo base_url("assets/js/validate/template/template.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Template
	<br><small>Add/Edit Template.</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_template"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label for="type" class="control-label">Template name : *</label>
					<input type="text" id="templateName" name="templateName" class="form-control" value="<?php echo $information?$information[0]["template_name"]:""; ?>" placeholder="Template name" required >
				</div> 
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label for="type" class="control-label">Location : *</label>
					<input type="text" id="location" name="location" class="form-control" value="<?php echo $information?$information[0]["location"]:""; ?>" placeholder="Location" required >
				</div> 
			</div>
		</div>
		

		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label for="type" class="control-label">Template description : *</label>
					<textarea id="description" name="description" class="form-control" rows="3"><?php echo $information?$information[0]["template_description"]:""; ?></textarea>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<input type="button" id="btnSubmit" name="btnSubmit" value="Save" class="btn btn-primary"/>
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