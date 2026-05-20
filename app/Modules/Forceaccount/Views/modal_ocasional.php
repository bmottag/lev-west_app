<script type="text/javascript" src="<?php echo base_url("assets/js/validate/forceaccount/ocasional.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Sub-contractor
	<br><small>
				Add an Occasional Subcontractor for the Force Account
	</small>
	</h4>
</div>

<div class="modal-body">
	<form  name="formOcasional" id="formOcasional" role="form" method="post" >
		<input type="hidden" id="hddidForceaccount" name="hddidForceaccount" value="<?php echo $idForceaccount; ?>"/>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="company">Company : *</label>
					<select name="company" id="company" class="form-control" >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($companyList); $i++) { ?>
							<option value="<?php echo $companyList[$i]["id_company"]; ?>" ><?php echo $companyList[$i]["company_name"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="equipment">Equipment : *</label>
					<textarea id="equipment" name="equipment" class="form-control" rows="2"></textarea>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="quantity">Quantity : *</label>
					<input type="text" id="quantity" name="quantity" class="form-control" placeholder="Quantity" required >
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="unit">Unit : *</label>
					<input type="text" id="unit" name="unit" class="form-control" placeholder="Unit" required >
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="hour">Hours : </label>
					<input type="text" id="hour" name="hour" class="form-control" placeholder="Hours" >
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="contact">Field Contact : *</label>
					<input type="text" id="contact" name="contact" class="form-control" placeholder="Field Contact" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="markup">Markup : </label>
					<input type="text" id="markup" name="markup" class="form-control" placeholder="Markup" >
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="description">Description : </label>
					<textarea id="description" name="description" class="form-control" rows="2"></textarea>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmitOcasional" name="btnSubmitOcasional" class="btn btn-primary" >
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