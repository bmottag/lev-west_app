<script type="text/javascript" src="<?php echo base_url("assets/js/validate/enlaces/links.js?v=1.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Links
	<br><small>Add/Edit Links</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_link"]:""; ?>"/>
		<input type="hidden" id="hddIdMenu" name="hddIdMenu" value="<?php echo $information?$information[0]["fk_id_menu"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="perfil">Menu name</label>
					<select name="id_menu" id="id_menu" class="form-control" required>
						<option value="">Select...</option>
						<?php for ($i = 0; $i < count($menuList); $i++) { ?>
							<option value="<?php echo $menuList[$i]["id_menu"]; ?>" <?php if($information && $information[0]["fk_id_menu"] == $menuList[$i]["id_menu"]) { echo "selected"; }  ?>><?php echo $menuList[$i]["menu_name"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="link_name">Link name : *</label>
					<input type="text" id="link_name" name="link_name" class="form-control" value="<?= !empty($information) ? $information[0]["link_name"] : '' ?>" placeholder="Link name" required >
				</div> 
			</div>
		
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="link_url">Link URL: *</label>
					<input type="text" id="link_url" name="link_url" class="form-control" value="<?php echo $information?$information[0]["link_url"]:""; ?>" placeholder="Link URL" required >
				</div> 
			</div>
		
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="order">Order: *</label>
					<select name="order" id="order" class="form-control" required>
						<option value='' >Select...</option>
						<?php for ($i = 1; $i <= 25; $i++) { ?>
							<option value='<?php echo $i; ?>' <?php if ($information && $i == $information[0]["order"]) { echo 'selected="selected"'; } ?> ><?php echo $i; ?></option>
						<?php } ?>									
					</select>
				</div> 
			</div>
		</div>
		
		<div class="row">
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
		
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="link_icon">Link icon: *</label>
					<input type="text" id="link_icon" name="link_icon" class="form-control" value="<?php echo $information?$information[0]["link_icon"]:""; ?>" placeholder="Link icon" >
				</div> 
			</div>
		</div>
		
		<div class="row">		
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="link_type">Link type : *</label>
					<select name="link_type" id="link_type" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information && $information[0]["link_type"] == 1) { echo "selected"; }  ?>>System URL</option>
						<option value=2 <?php if($information && $information[0]["link_type"] == 2) { echo "selected"; }  ?>>Complete URL</option>
						<option value=3 <?php if($information && $information[0]["link_type"] == 3) { echo "selected"; }  ?>>Divider</option>
						<option value=4 <?php if($information && $information[0]["link_type"] == 4) { echo "selected"; }  ?>>Complete URL; Video</option>
						<option value=5 <?php if($information && $information[0]["link_type"] == 5) { echo "selected"; }  ?>>Complete URL; Manual</option>
					</select>
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