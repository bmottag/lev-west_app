<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/job.js?v=1.0.0"); ?>"></script>
<style>
    .select2-container .select2-dropdown .select2-results__option {
        text-align: left;
    }
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Job Code/Name Form </h4>
</div>

<div class="modal-body">
	<p class="text-danger text-left">Fields with * are required.</p>
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_job"]:""; ?>"/>
		<input type="hidden" id="hddIdForeman" name="hddIdForeman" value="<?php echo $information?$information[0]["id_company_foreman"]:""; ?>"/>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="jobCode">Job Code: *</label>
					<input type="text" id="jobCode" name="jobCode" class="form-control" value="<?php echo $information?$information[0]["job_code"]:""; ?>" placeholder="Job Code" required >
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="jobName">Job Name: *</label>
					<input type="text" id="jobName" name="jobName" class="form-control" value="<?php echo $information?$information[0]["job_name"]:""; ?>" placeholder="Job Name" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="Company">Company: *</label>
					<select name="company" id="company" class="form-control js-example-basic-single">
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($companyList); $i++) { ?>
							<option value="<?php echo $companyList[$i]["id_company"]; ?>" 
							<?php 
								if ($information && $information[0]["fk_id_company"] == $companyList[$i]["id_company"]) {
									echo "selected";
								}  ?>>
							<?php echo $companyList[$i]["company_name"]; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="markup">Markup:</label>
					<input type="text" id="markup" name="markup" class="form-control" value="<?php echo $information?$information[0]["markup"]:"0"; ?>" placeholder="Markup" required >
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="profit">Profit Percentage:</label>
					<input type="text" id="profit" name="profit" class="form-control" value="<?php echo $information?$information[0]["profit"]:"0"; ?>" placeholder="Profit percentage" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="stateJob">Status: *</label>
					<select name="stateJob" id="stateJob" class="form-control" >
						<option value=''>Select...</option>
						<option value='1' <?php if($information && $information[0]["state"] == '1') { echo "selected"; }  ?>>Active</option>
						<option value='2' <?php if($information && $information[0]["state"] == '2') { echo "selected"; }  ?>>Inactive</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="notes">Notes: </label>
					<textarea id="notes" name="notes" class="form-control" rows="3" required><?php echo $information?$information[0]["notes"]:""; ?></textarea>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="planning_message">Automatic Planning Message: *</label>
					<select name="planning_message" id="planning_message" class="form-control" >
						<option value='2' <?php if($information && $information[0]["planning_message"] == '2') { echo "selected"; }  ?>>No</option>
						<option value='1' <?php if($information && $information[0]["planning_message"] == '1') { echo "selected"; }  ?>>Yes</option>
					</select>
				</div>
			</div>
		</div>

		<hr>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="jobCode">Foreman's name: </label>
					<input type="text" id="foreman" name="foreman" class="form-control" value="<?php echo $information?$information[0]["foreman_name"]:""; ?>" placeholder="Foreman's name" required>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="jobName">Foreman's mobile number:</label>
					<input type="text" id="movilNumber" name="movilNumber" class="form-control" value="<?php echo $information?$information[0]["foreman_movil_number"]:""; ?>" placeholder="Foreman's mobile number" required>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="jobCode">Foreman's email: </label>
					<input type="text" id="email" name="email" class="form-control" value="<?php echo $information?$information[0]["foreman_email"]:""; ?>" placeholder="Foreman's email" required>
				</div>
			</div>

			<div class="col-sm-6">

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
				<div class="alert alert-danger">
					<span class="glyphicon glyphicon-remove"></span>
					<span id="span_msj"></span>
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
			
	</form>
</div>