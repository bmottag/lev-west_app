<script type="text/javascript" src="<?php echo base_url("assets/js/validate/acs/receipt_acs.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">RECEIPT
	<br><small>
		Add a Receipt to the Accounting Control Sheet (ACS)
	</small>
	</h4>
</div>

<div class="modal-body">
	<form  name="formReceipt" id="formReceipt" role="form" method="post" >
		<input type="hidden" id="hddIdACS" name="hddIdACS" value="<?php echo $idACS; ?>"/>
		<input type="hidden" id="hddId" name="hddId" value=""/>
				
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="place">Place: *</label>
					<input type="text" id="place" name="place" class="form-control" placeholder="Place" required >
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="price">Price with GST: </label>
					<input type="text" id="price" name="price" class="form-control" placeholder="Price with GST" >
				</div>
			</div>
		</div>
			
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="description">Description: *</label>
					<textarea id="description" name="description" class="form-control" rows="2"></textarea>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmitReceipt" name="btnSubmitReceipt" class="btn btn-primary" >
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