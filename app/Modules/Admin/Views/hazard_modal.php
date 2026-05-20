<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/hazard.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Hazard Form
	<br><small>Add/Edit Hazard to use in the Safety Module.</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_hazard"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="activity">Activity: *</label>
					<select name="activity" id="activity" class="form-control" >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($activityList); $i++) { ?>
							<option value="<?php echo $activityList[$i]["id_hazard_activity"]; ?>" <?php if($information && $information[0]["fk_id_hazard_activity"] == $activityList[$i]["id_hazard_activity"]) { echo "selected"; }  ?>><?php echo $activityList[$i]["hazard_activity"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="priority">Priority: *</label>
					<select name="priority" id="priority" class="form-control" >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($priorityList); $i++) { ?>
							<option value="<?php echo $priorityList[$i]["id_priority"]; ?>" <?php if($information && $information[0]["fk_id_priority"] == $priorityList[$i]["id_priority"]) { echo "selected"; }  ?>><?php echo $priorityList[$i]["priority_description"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
			
		<div class="row">
			<div class="col-sm-12">				
				<div class="form-group text-left">
					<label class="control-label" for="hazardName">Hazard: *</label>
					<input type="text" id="hazardName" name="hazardName" class="form-control" value="<?php echo $information?$information[0]["hazard_description"]:""; ?>" placeholder="Hazard" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="solution">Solution: *</label>
					<textarea id="solution" name="solution" class="form-control" rows="3"><?php echo $information?$information[0]["solution"]:""; ?></textarea>
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