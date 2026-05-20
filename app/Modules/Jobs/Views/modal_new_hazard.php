<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/new_hazard.js"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">NEW HAZARD
	<br><small>
				Add new hazard NOT consider on the FLHA or JHA
	</small>
	</h4>
</div>

<div class="modal-body">
	<form  name="formNewHazard" id="formNewHazard" role="form" method="post" >
		<input type="hidden" id="hddidToolBox" name="hddidToolBox" value="<?php echo $idToolBox; ?>"/>
				
		<div class="form-group text-left">
			<label for="hour">Identify specific hazard : *</label>
			<textarea id="hazard" name="hazard" class="form-control" rows="2"></textarea>
		</div>
		
		<div class="form-group text-left">
			<label for="unit">Type of hazard : *</label>
			<select name="hazardType" id="hazardType" class="form-control" required>
				<option value=''>Select...</option>
				<option value=1>Chemical</option>
				<option value=2>Physical</option>
				<option value=3>Biological</option>
			</select>
		</div>
		
		<div class="form-group text-left">
			<label for="description">Recommended actions : *</label>
			<textarea id="actions" name="actions" class="form-control" rows="3"></textarea>
		</div>
		
		<div class="form-group">
			<button type="button" id="btnSubmitHazard" name="btnSubmitHazard" class="btn btn-primary" >
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