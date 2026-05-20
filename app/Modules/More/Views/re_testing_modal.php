<script type="text/javascript" src="<?php echo base_url('assets/js/validate/more/re_testing.js?v=2.0.0'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/timepicker/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/timepicker/bootstrap-datetimepicker.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/timepicker/bootstrap-datetimepicker.min.css'); ?>"/>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
	<h4 class="modal-title">ENVIRONMENTAL CONDITIONS - Re-Testing<br><small>Add/Edit</small></h4>
</div>

<div class="modal-body">
	<p class="text-danger text-left">Fields with * are required.</p>
	<form name="form" id="form" role="form" method="post">
		<input type="hidden" id="hddIdConfined" name="hddIdConfined" value="<?php echo esc($idConfined); ?>"/>
		<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo esc($idJob); ?>"/>
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information ? esc($information[0]['id_job_confined_re_testing']) : ''; ?>"/>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label">Oxygen (%): *</label>
					<input type="number" step="any" id="re_oxygen" name="re_oxygen" class="form-control" value="<?php echo $information ? esc($information[0]['re_oxygen']) : ''; ?>" placeholder="Oxygen" required>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label">Date/Time:</label>
					<div class="input-group date" id="datetimepicker3">
						<input type="text" id="re_oxygen_time" name="re_oxygen_time" class="form-control" value="<?php echo $information ? esc($information[0]['re_oxygen_time']) : ''; ?>" placeholder="YYYY-MM-DD HH:mm:ss"/>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
					<script>$(function() { $('#datetimepicker3').datetimepicker({ format: 'YYYY-MM-DD HH:mm:ss' }); });</script>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label">Lower Explosive Limit (%): *</label>
					<input type="number" step="any" id="re_explosive_limit" name="re_explosive_limit" class="form-control" value="<?php echo $information ? esc($information[0]['re_explosive_limit']) : ''; ?>" placeholder="Lower Explosive Limit" required>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label">Date/Time:</label>
					<div class="input-group date" id="datetimepicker4">
						<input type="text" id="re_explosive_limit_time" name="re_explosive_limit_time" class="form-control" value="<?php echo $information ? esc($information[0]['re_explosive_limit_time']) : ''; ?>" placeholder="YYYY-MM-DD HH:mm:ss"/>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
					<script>$(function() { $('#datetimepicker4').datetimepicker({ format: 'YYYY-MM-DD HH:mm:ss' }); });</script>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label">Toxic Atmosphere:</label>
					<input type="text" id="re_toxic_atmosphere" name="re_toxic_atmosphere" class="form-control" value="<?php echo $information ? esc($information[0]['re_toxic_atmosphere']) : ''; ?>" placeholder="Toxic Atmosphere">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label">Instruments Used:</label>
					<input type="text" id="re_instruments_used" name="re_instruments_used" class="form-control" value="<?php echo $information ? esc($information[0]['re_instruments_used']) : ''; ?>" placeholder="Instruments Used">
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary">
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div id="div_load" style="display:none">
				<div class="progress progress-striped active">
					<div class="progress-bar" role="progressbar" style="width: 45%"><span class="sr-only">45%</span></div>
				</div>
			</div>
			<div id="div_error" style="display:none">
				<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
			</div>
		</div>
	</form>
</div>
