<script type="text/javascript" src="<?php echo base_url("assets/js/validate/enlaces/videos.js?v=3.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Video link asdfasdfasdf
	<br><small>Add/Edit Links</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_link"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="link_name">Link name : *</label>
					<input type="text" id="link_name" name="link_name" class="form-control" value="<?php echo $information?$information[0]["link_name"]:""; ?>" placeholder="Link name" required >
				</div> 
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="link_url">Link URL : *</label>
					<input type="text" id="link_url" name="link_url" class="form-control" value="<?php echo $information?$information[0]["link_url"]:""; ?>" placeholder="Link URL" required >
				</div> 
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="order">Order: *</label>
					<select name="order" id="order" class="form-control" required>
						<option value='' >Select...</option>
						<?php for ($i = 1; $i <= 50; $i++) { ?>
							<option value='<?php echo $i; ?>' <?php if ($information && $i == $information[0]["order"]) { echo 'selected="selected"'; } ?> ><?php echo $i; ?></option>
						<?php } ?>									
					</select>
				</div> 
			</div>
		
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="link_state">State : *</label>
					<select name="link_state" id="link_state" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information && $information[0]["link_state"] == 1) { echo "selected"; }  ?>>Active</option>
						<option value=2 <?php if($information && $information[0]["link_state"] == 2) { echo "selected"; }  ?>>Inactive</option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<input type="button" id="btnSubmit" name="btnSubmit" value="Save" class="btn btn-primary"/>
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