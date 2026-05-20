<script type="text/javascript" src="<?php echo base_url("assets/js/validate/admin/company.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Company Form
		<br><small>Add New or Edit a Existing Hauling Company.</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post">
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information ? $information[0]["id_company"] : ""; ?>" />

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="company">Company name:</label>
					<input type="text" id="company" name="company" class="form-control" value="<?php echo $information ? $information[0]["company_name"] : ""; ?>" placeholder="Company name" required>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="contact">Contact:</label>
					<input type="text" id="contact" name="contact" class="form-control" value="<?php echo $information ? $information[0]["contact"] : ""; ?>" placeholder="Contact" required>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="movilNumber">Mobile number: </label>
					<input type="text" id="movilNumber" name="movilNumber" class="form-control" value="<?php echo $information ? $information[0]["movil_number"] : ""; ?>" placeholder="Mobile Number" required>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="email">Email:</label>
					<input type="email" class="form-control" id="email" name="email" value="<?php echo $information ? $information[0]["email"] : ""; ?>" placeholder="Email" />
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="email">Provides Haul Services?</label>
					<select name="does_hauling" id="does_hauling" class="form-control">
						<option value=2 <?php if ($information && $information[0]["does_hauling"] == 2) {
											echo "selected";
										}  ?>>No</option>
						<option value=1 <?php if ($information && $information[0]["does_hauling"] == 1) {
											echo "selected";
										}  ?>>Yes</option>
					</select>
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