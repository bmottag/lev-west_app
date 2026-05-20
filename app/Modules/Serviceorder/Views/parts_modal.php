<script type="text/javascript" src="<?php echo base_url("assets/js/validate/serviceorder/parts.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Parts or Additional Cost	</h4>
</div>

<div class="modal-body">
	<form name="formParts" id="formParts" role="form" method="post" >
		<input type="hidden" id="hddIdPart" name="hddIdPart" value="<?php echo $information?$information[0]["id_part"]:""; ?>"/>
		<input type="hidden" id="hddIdServiceOrder" name="hddIdServiceOrder" value="<?php echo $idServiceOrder; ?>"/>
		<input type="hidden" id="hddIdEquipment" name="hddIdEquipment" value="<?php echo $idEquipment; ?>" />

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="part_description">Description: *</label>
					<input type="text" id="part_description" name="part_description" class="form-control" placeholder="Description" value="<?php echo $information?$information[0]["part_description"]:""; ?>" >
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="supplier">Supplier: </label>
					<input type="text" id="supplier" name="supplier" class="form-control" placeholder="Supplier" value="<?php echo $information?$information[0]["supplier"]:""; ?>" >
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="quantity">Quantity: *</label>
					<input type="number" id="quantity" name="quantity" min=1 maxlength="3" class="form-control" placeholder="Quantity" value="<?php echo $information?$information[0]["quantity"]:""; ?>" >
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="value">Value: </label>
					<input type="text" id="value" name="value" class="form-control" placeholder="Value" value="<?php echo $information?$information[0]["value"]:""; ?>" >
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
					<button type="button" id="btnSubmitParts" name="btnSubmitParts" class="btn btn-violeta" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>