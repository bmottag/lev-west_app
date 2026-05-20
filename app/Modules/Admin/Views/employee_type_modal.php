<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/employee_type.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Employee Type 
	<br><small>Add/Edit Employee Type to use in the Work Order Module.</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_employee_type"]:""; ?>"/>
		
		
		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
						<label for="employeeType" class="control-label">Employee Type : *</label>
						<input type="text" id="employeeType" name="employeeType" class="form-control" value="<?php echo $information?$information[0]["employee_type"]:""; ?>" placeholder="Employee Type" required >
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="unit_price">Unit Price : *</label>
					<input type="text" id="unit_price" name="unit_price" class="form-control" value="<?php echo $information?$information[0]["employee_type_unit_price"]:""; ?>" placeholder="Unit Price" required >
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
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
 					
	</form>
</div>