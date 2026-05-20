<script type="text/javascript" src="<?php echo base_url("assets/js/validate/template/valve.js"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Valve</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_valve"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label for="type" class="control-label">Valve number: *</label>
					<input type="text" id="valve_number" name="valve_number" class="form-control" value="<?php echo $information?$information[0]["valve_number"]:""; ?>" placeholder="Valve number" required >
				</div> 
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label for="type" class="control-label"># of Turns: *</label>
					<select name="number_of_turns" id="number_of_turns" class="form-control" required>
						<option value=''>Select...</option>
						<?php
						for ($i = 0; $i <= 40; $i++) {
						?>
							<option value='<?php echo $i; ?>' <?php
																if ($information && $i == $information[0]["number_of_turns"]) {
																	echo 'selected="selected"';
																}
																?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div> 
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label for="type" class="control-label">Position that valve was found: *</label>
					<select name="position" id="position" class="form-control" required >
						<option value="">Select...</option>
						<option value="Open" <?php if ($information && $information[0]["position"] == "Open") {
											echo "selected";
										}  ?>>Open</option>
						<option value="Close" <?php if ($information && $information[0]["position"] == "Close") {
											echo "selected";
										}  ?>>Close</option>
						<option value="NA" <?php if ($information && $information[0]["position"] == "NA") {
											echo "selected";
										}  ?>>NA</option>
					</select>
				</div> 
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label for="type" class="control-label">Condition: *</label>
					<textarea id="status" name="status" class="form-control" rows="3"><?php echo $information?$information[0]["status"]:""; ?></textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label for="type" class="control-label">The direction of the turn for operation: *</label>
					<select name="direction" id="direction" class="form-control" required >
						<option value="">Select...</option>
						<option value="Left" <?php if ($information && $information[0]["direction"] == "Left") {
											echo "selected";
										}  ?>>Left</option>
						<option value="Right" <?php if ($information && $information[0]["direction"] == "Right") {
											echo "selected";
										}  ?>>Right</option>
						<option value="NA" <?php if ($information && $information[0]["direction"] == "NA") {
											echo "selected";
										}  ?>>NA</option>
					</select>
				</div> 
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label for="type" class="control-label">Rewarks: *</label>
					<textarea id="rewarks" name="rewarks" class="form-control" rows="3"><?php echo $information?$information[0]["rewarks"]:""; ?></textarea>
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