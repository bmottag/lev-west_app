<script type="text/javascript" src="<?php echo base_url("assets/js/validate/claims/claims.js?v=2"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Claims
	<br><small>Add/Edit Claim</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_claim"]:""; ?>"/>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="id_job">Job Code/Name: *</label>
					<select name="id_job" id="id_job" class="form-control" disabled>
						<?php for ($i = 0; $i < count($jobs); $i++) { ?>
							<option value="<?php echo $jobs[$i]["id_job"]; ?>" <?php if($information && $information[0]["fk_id_job"] == $jobs[$i]["id_job"]) { echo "selected"; }  ?>><?php echo $jobs[$i]["job_description"]; ?></option>	
						<?php } ?>
					</select>	
				</div>
			</div>
		</div>
			
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="claimNumber">Claim Number: *</label>
					<input type="text" id="claimNumber" name="claimNumber" class="form-control" value="<?php echo $information?$information[0]["claim_number"]:$nextClaimNumber; ?>" placeholder="Claim Number" disabled >
				</div>
			</div>
		</div>


		<?php if($lastObservation){ ?>
			<div class="row">
				<div class="col-sm-12">
					<div id="div_observation" class="alert alert-info">
						<b>Last claim observation:</b><br>
						<span id="span_observation"><?php echo $lastObservation; ?></span>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="row">	
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="observation">Observation: </label>
					<textarea id="observation" name="observation" placeholder="Observation" class="form-control" rows="3"><?php echo $information?$information[0]["observation_claim"]:""; ?></textarea>
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