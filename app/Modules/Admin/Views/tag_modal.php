<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/tag.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Tag Form	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post">
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information ? $information[0]["id_tag"] : ""; ?>" />

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="movilNumber">Job Code/Name: </label>
					<select name="idJob" id="idJob" class="form-control js-example-basic-single">
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($jobList); $i++) { ?>
							<option value="<?php echo $jobList[$i]["id_job"]; ?>" <?php if($information && $information[0]['id_job'] == $jobList[$i]["id_job"]) { echo "selected"; }  ?>><?php echo $jobList[$i]["job_description"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="number">Number:</label>
					<input type="text" id="number" name="number" class="form-control" value="<?php echo $information ? $information[0]["number"] : ""; ?>" placeholder="Number" required>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="name">Point Name:</label>
					<input type="text" id="name" name="name" class="form-control" value="<?php echo $information ? $information[0]["name"] : ""; ?>" placeholder="Point name" required>
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
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary">
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button>
				</div>
			</div>
		</div>

	</form>
</div>