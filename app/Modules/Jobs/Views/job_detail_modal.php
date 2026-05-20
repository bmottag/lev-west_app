<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/job_detail.js?v=2.0.0"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Job Detail 
	<br><small>Add/Edit Job Detail</small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_job_detail"]:""; ?>"/>
		<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $idJob; ?>"/>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="chapter" >Chapter : *</label>
					<input type="text" id="chapter" name="chapter" class="form-control" value="<?php echo $information?$information[0]["chapter_name"]:($chapterName == "x"?"":$chapterName); ?>" placeholder="Chapter name" <?php echo $information?"disabled":($chapterName == "x"?"required":"disabled"); ?> >
				</div>
			</div>
			
		</div>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="chapter_number" >Chapter number: *</label>
					<input type="text" id="chapter_number" name="chapter_number" class="form-control" value="<?php echo $information?$information[0]["chapter_number"]:($chapterNumber == "x"?"":$chapterNumber); ?>" placeholder="Chapter number" <?php echo $information?"disabled":($chapterNumber == "x"?"required":"disabled"); ?> >
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="item">Item : *</label>
					<input type="text" id="item" name="item" class="form-control" value="<?php echo $information?$information[0]["item"]:""; ?>" placeholder="Item" <?php echo $information? "disabled":"required"; ?> >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">		
				<div class="form-group text-left">
					<label for="description" class="control-label">Description : *</label>
					<textarea id="description" name="description" placeholder="Description" class="form-control" rows="3"><?php echo $information?$information[0]["description"]:""; ?></textarea>
				</div>
			</div>
			
		</div>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label for="unit" class="control-label">Unit : *</label>
					<input type="text" id="unit" name="unit" class="form-control" value="<?php echo $information?$information[0]["unit"]:""; ?>" placeholder="Unit" required >
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="quantity">Quantity : *</label>
					<input type="text" id="quantity" name="quantity" class="form-control" value="<?php echo $information?$information[0]["quantity"]:""; ?>" placeholder="Quantity" required >
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">		
				<div class="form-group text-left">
					<label class="control-label" for="unit_price">Unit Price  : *</label>
					<input type="text" id="unit_price" name="unit_price" class="form-control" value="<?php echo $information?$information[0]["unit_price"]:""; ?>" placeholder="Unit Price" required >
				</div>
			</div>	
			
			<?php if($information){ ?>
			<div class="col-sm-6">
				<div class="form-group text-left">
					<label class="control-label" for="status">Status: *</label>
					<select name="status" id="status" class="form-control" required>
						<option value=''>Select...</option>
						<option value=1 <?php if($information[0]["status"] == 1) { echo "selected"; }  ?>>Active</option>
						<option value=2 <?php if($information[0]["status"] == 2) { echo "selected"; }  ?>>Executed: <?php echo $claimPercentage . "% (Select this option to hide)"; ?> </option>
						<option value=3 <?php if($information[0]["status"] == 3) { echo "selected"; }  ?>>Closed</option>
					</select>
				</div>
			</div>
			<?php } ?>
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
					<button type="button" id="btnSave" name="btnSave" class="btn btn-primary" >
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>