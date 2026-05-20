<script type="text/javascript" src="<?php echo base_url("assets/js/validate/workorder/ajaxTrucks.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/forceaccount/equipment.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Machinery
	<br><small>
				Equipment / Rentals for the Force Account
	</small>
	</h4>
</div>

<div class="modal-body">
	<form  name="formEquipment" id="formEquipment" role="form" method="post" >
		<input type="hidden" id="hddidForceaccount" name="hddidForceaccount" value="<?php echo $idForceaccount; ?>"/>
				
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="type">Type: *</label>
					<select name="type" id="type" class="form-control" required>
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($equipmentType); $i++) { ?>
							<option value="<?php echo $equipmentType[$i]["id_type_2"]; ?>"><?php echo $equipmentType[$i]["type_2"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
					
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<div id="div_truck">
						<label class="control-label" for="truck">Machinery: *</label>
						<select name="truck" id="truck" class="form-control" >

						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<div id="div_attachment" style="display:none">
						<label for="standby">Attachment: </label> <button class="btn btn-danger btn-xs" disabled >New </button>
						<select name="attachment" id="attachment" class="form-control" >

						</select>
					</div>
				</div>
			</div>
		</div>

		<!-- Para indicar si el equipo esta con condutor o sin conductor -->
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<div id="div_standby" style="display:none">
						<label for="standby">Standby? *</label>
						<select name="standby" id="standby" class="form-control" required>
							<option value="">Select...</option>
							<option value=1 >Yes</option>
							<option value=2 selected>No</option>
						</select>
					</div>
				</div>
			</div>
		</div>		

				<div class="form-group text-left">
					<div id="div_other" style="display:none">
						<label class="control-label" for="otherEquipment">Tools: *</label>
						<input type="text" id="otherEquipment" name="otherEquipment" class="form-control" placeholder="Tools" >
					</div>
				</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<div id="div_operated">
						<label class="control-label" for="operatedby">Unit Operated by: *</label>
						<select name="operatedby" id="operatedby" class="form-control" >
							<option value=''>Select...</option>
							<?php for ($i = 0; $i < count($workersList); $i++) { ?>
								<option value="<?php echo $workersList[$i]["id_user"]; ?>" ><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="hour">Hours Worked: *</label>
					<input type="text" id="hour" name="hour" class="form-control" placeholder="Hours" required >
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="quantity">Quantity: </label>
					<input type="text" id="quantity" name="quantity" class="form-control" placeholder="Quantity" >
				</div>
			</div>
		</div>
		
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="description">Description: *</label>
					<textarea id="description" name="description" class="form-control" rows="3"></textarea>
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
					<button type="button" id="btnSubmitEquipment" name="btnSubmitEquipment" class="btn btn-primary" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
		
	</form>
</div>