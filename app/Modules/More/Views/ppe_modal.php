<script type="text/javascript" src="<?php echo base_url('assets/js/validate/more/ppe_inspection.js?v=2.0.0'); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">PPE INSPECTION PROGRAM</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post">
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information ? esc($information[0]['id_ppe_inspection']) : ''; ?>"/>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label for="observation" class="control-label">Observation: *</label>
					<textarea id="observation" name="observation" class="form-control" rows="2"><?php echo $information ? esc($information[0]['template_description']) : ''; ?></textarea>
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
