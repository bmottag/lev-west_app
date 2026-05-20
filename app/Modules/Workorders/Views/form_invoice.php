<script type="text/javascript" src="<?php echo base_url("assets/js/validate/workorder/subcontractor_invoice.js?v=1.0.0"); ?>"></script>

<script>
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});
</script>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					<a class="btn btn-success btn-xs" href="<?php echo base_url() . 'workorders/subcontractor_invoice'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-money"></i> <strong>SUBCONTRACTORS INVOICES</strong>
				</div>
				<div class="panel-body">
					<?php if (session()->getFlashdata('retornoExito')): ?>
						<div class="alert alert-success">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoExito') ?>
						</div>
					<?php endif; ?>

					<?php if (session()->getFlashdata('retornoError')): ?>
						<div class="alert alert-danger">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoError') ?>
						</div>
					<?php endif; ?>

					<form name="form" id="form" class="form-horizontal" enctype="multipart/form-data" method="post">
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information ? $information[0]["id_subcontractor_invoice"] : ""; ?>" />
						
						<div class="form-group">
							<label class="col-sm-3 control-label" for="company">Subcontractor:</label>
							<div class="col-sm-4">
							<select name="company" id="company" class="form-control js-example-basic-single" <?php echo $deshabilitar; ?>>
										<option value=''>Select...</option>
										<?php for ($i = 0; $i < count($companyList); $i++) { ?>
											<option value="<?php echo $companyList[$i]["id_company"]; ?>" <?php if ($information && $information[0]["fk_id_company"] == $companyList[$i]["id_company"]) {
																												echo "selected";
																											}  ?>><?php echo $companyList[$i]["company_name"]; ?></option>
										<?php } ?>
									</select>
							</div>
						</div>

						<div class="form-group text-left">
							<label class="col-sm-4 control-label" for="company"><p class="help-block">If the subcontractor is not found in the list, then it must be added.</p></label>
						</div>

<?php 
	$mostrar = "inline";
	if($information){
		$mostrar = "none";
	}
?>
						<div id="div_subcontractor_detail" style="display:<?php echo $mostrar; ?>">
							<hr>
							<div class="row">

								<div class="form-group">
									<label class="col-sm-3 control-label" for="subcontractor_name">Subcontractor's name: *</label>
									<div class="col-sm-3">
										<input type="text" id="subcontractor_name" name="subcontractor_name" class="form-control" placeholder="Subcontractor's name" <?php echo $deshabilitar; ?>>
									</div>

									<label class="col-sm-2 control-label" for="subcontractor_contact">Subcontractor's Contact: </label>
									<div class="col-sm-3">
										<input type="text" id="subcontractor_contact" name="subcontractor_contact" class="form-control" placeholder="Subcontractor's Contact" <?php echo $deshabilitar; ?>>
									</div>
								</div>
							</div>

							<div class="row">

								<div class="form-group">
									<label class="col-sm-3 control-label" for="subcontractor_mobile_number">Subcontractor's Mobile Number: *</label>
									<div class="col-sm-3">
									<input type="text" id="subcontractor_mobile_number" name="subcontractor_mobile_number" class="form-control" placeholder="Subcontractor's Mobile Number" >
									</div>

									<label class="col-sm-2 control-label" for="subcontractor_email">Subcontractor's Email: </label>
									<div class="col-sm-3">
									<input type="email" id="subcontractor_email" name="subcontractor_email" class="form-control" placeholder="Subcontractor's Email" >
									</div>
								</div>
							</div>
							<hr>
						</div>

						<div class="row">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="invoice_number">Invoice Number: *</label>
								<div class="col-sm-3">
									<input type="text" id="invoice_number" name="invoice_number" class="form-control" placeholder="Invoice Number" value="<?php echo $information ? $information[0]["invoice_number"] : ""; ?>" <?php echo $deshabilitar; ?>>
								</div>

								<label class="col-sm-2 control-label" for="amount">Invoice Amount: *</label>
								<div class="col-sm-3">
									<input type="text" id="amount" name="amount" class="form-control" placeholder="Invoice Amount" value="<?php echo $information ? $information[0]["invoice_amount"] : ""; ?>" <?php echo $deshabilitar; ?>>
								</div>
							</div>
						</div>

						<div class="form-group">					
							<label class="col-sm-4 control-label" for="hddTask">Attach document if necessary
								<br><small class="text-danger">Allowed format: pdf
								<br>Maximum size: 3000 KB </small>
							</label>
							<div class="col-sm-5">
								 <input type="file" name="userfile" />
								 <br>
								 <?php if (!empty($information[0]["file"])) { ?>
									<a href="<?php echo base_url('files/sub_invoices/' . $information[0]["file"]) ?>" target="_blank">Attached document: <?php echo $information[0]["file"]; ?></a>
								<?php } ?>
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">
									<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary">
										Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:80%;" align="center">
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
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>