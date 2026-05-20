<script type="text/javascript" src="<?php echo base_url("assets/js/validate/serviceorder/parts_shop.js"); ?>"></script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Equipment Parts Form
	<br><small>Add/Edit Equipment Parts</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_part_shop"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label class="control-label" for="part_description">Part Description: *</label>
					<input type="text" id="part_description" name="part_description" class="form-control" value="<?php echo $information?$information[0]["part_description"]:""; ?>" placeholder="Description" required >
				</div>
			</div>
		</div>


		<?php if($shopList){ ?>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="shop">Shop: *</label>
					<select name="id_shop" id="id_shop" class="form-control" >
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($shopList); $i++) { ?>
							<option value="<?php echo $shopList[$i]["id_shop"]; ?>" <?php if($information && $information[0]["fk_id_shop"] == $shopList[$i]["id_shop"]) { echo "selected"; }  ?>><?php echo $shopList[$i]["shop_name"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<?php } ?>

<?php 
	$mostrar = "inline";
	if($information){
		$mostrar = "none";
	}
?>

		<div id="div_shop_detail" style="display:<?php echo $mostrar; ?>">
			<hr>
			<div class="row">
				<div class="col-sm-6">		
					<div class="form-group text-left">
						<label class="control-label" for="shop_name">Shop Name: *</label>
						<input type="text" id="shop_name" name="shop_name" class="form-control" value="<?php echo $information?$information[0]["shop_name"]:""; ?>" placeholder="Shop Name" >
					</div>
				</div>

				<div class="col-sm-6">		
					<div class="form-group text-left">
						<label class="control-label" for="shop_contact">Shop Contact: *</label>
						<input type="text" id="shop_contact" name="shop_contact" class="form-control" value="<?php echo $information?$information[0]["shop_contact"]:""; ?>" placeholder="Shop Contact" >
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6">		
					<div class="form-group text-left">
						<label class="control-label" for="shop_address">Shop Address: *</label>
						<input type="text" id="shop_address" name="shop_address" class="form-control" value="<?php echo $information?$information[0]["shop_address"]:""; ?>" placeholder="Shop Address" >
					</div>
				</div>

				<div class="col-sm-6">		
					<div class="form-group text-left">
						<label class="control-label" for="mobile_number">Shop Mobile Number: *</label>
						<input type="text" id="mobile_number" name="mobile_number" class="form-control" value="<?php echo $information?$information[0]["mobile_number"]:""; ?>" placeholder="Shop Mobile Number" >
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6">		
					<div class="form-group text-left">
						<label class="control-label" for="shop_email">Shop Email: *</label>
						<input type="email" id="shop_email" name="shop_email" class="form-control" value="<?php echo $information?$information[0]["shop_email"]:""; ?>" placeholder="Shop Email" >
					</div>
				</div>
			</div>
			<hr>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="type">Equipment Type: *</label>
					<select name="type" id="type" class="form-control" required>
						<option value=''>Select...</option>
						<?php for ($i = 0; $i < count($equipmentType); $i++) { ?>
							<option value="<?php echo $equipmentType[$i]["inspection_type"]; ?>" <?php if($information && $information[0]["fk_inspection_type"] == $equipmentType[$i]["inspection_type"]) { echo "selected"; }  ?>><?php echo $equipmentType[$i]["header_inspection_type"]; ?></option>	
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<div id="div_equipment">
						<label class="control-label" for="equipment">Equipment: *</label>
						<select name="equipment[]" id="equipment" class="form-control" multiple="multiple">

						</select>
					</div>
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