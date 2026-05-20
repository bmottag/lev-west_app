<script type="text/javascript" src="<?php echo base_url("assets/js/validate/invoice/form_invoice.js?v=10.0.0"); ?>"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="<?php echo base_url('assets/css/cards.css'); ?>">

<style>
.ui-datepicker {
    z-index: 9999 !important;
}
</style>

<script>
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});
</script>

<script>
	$(function() {
		$("#date, #due_date").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
</script>

<script>
	$(function() {

		$(".btn-info").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + 'invoices/cargarModalItems',
				data: {
					'idInvoice': oID
				},
				cache: false,
				success: function(data) {
					$('#tablaDatos').html(data);
				}
			});
		});

	});
</script>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<div class="pull-left">
						<i class="fa fa-money"></i>
						<strong><?php echo $information ? 'EDIT' : 'NEW'; ?> INVOICE</strong>
					</div>

					<?php if ($information) { ?>
						<div class="pull-right">
							Actual status: <strong><?php echo $information[0]["status_name"]; ?></strong>
						</div>
					<?php }else{ ?> 
						<div class="pull-right">
							<a class="btn btn-default btn-xs" href="<?php echo base_url('invoices'); ?>">
							<i class="fa fa-arrow-left"></i> Back
							</a>
						</div>
					<?php } ?>
				</div>

				<div class="panel-body">

					<?php
					/**
					 * If it is:
					 * SUPER ADMIN, MANAGEMENT, ACCOUNTING ROLES and WORK ORDER USER
					 * They have acces to asign rate and dowloadinvoice
					 */
					if ($information) {
					?>

							<div class="btn-group" style="margin-bottom:15px">

								<a class="btn btn-default btn-sm"
								href="<?php echo base_url('invoices'); ?>">
								<i class="fa fa-arrow-left"></i> Back
								</a>

								<button class="btn btn-info btn-sm"
								onclick="previewInvoice(<?php echo $information[0]['id_invoice']; ?>)">
								<i class="fa fa-eye"></i> Preview
								</button>

								<a class="btn btn-success btn-sm"
								href="<?php echo base_url('invoices/genera_invoice_pdf/'.$idInvoice); ?>"
								target="_blank">
								<i class="fa fa-download"></i> Download
								</a>

								<a class="btn btn-primary btn-sm"
								href="<?php echo base_url('invoices/send_invoice_email/'.$idInvoice); ?>">
								<i class="fa fa-envelope"></i> Send Invoice
								</a>

							</div>
						<?php 
						echo "<br>";
					}
					?>

					<?php
					$retornoExito = session()->getFlashdata('retornoExito');
					if ($retornoExito) {
					?>
						<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-success ">
									<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
									<?php echo $retornoExito ?>
								</div>
							</div>
						</div>
					<?php
					}

					$retornoError = session()->getFlashdata('retornoError');
					if ($retornoError) {
					?>
						<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-danger ">
									<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									<?php echo $retornoError ?>
								</div>
							</div>
						</div>
					<?php
					}
					?>

					<form name="form" id="form" class="form-horizontal" method="post">
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information ? $idInvoice : ""; ?>" />

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="taskDescription">Job Code/Name :</label>
									<div class="col-sm-5">
										<select name="jobName" id="jobName" class="form-control js-example-basic-single" <?php echo $deshabilitar; ?>>
											<option value=''>Select...</option>
											<?php for ($i = 0; $i < count($jobs); $i++) { ?>
												<option value="<?php echo $jobs[$i]["id_job"]; ?>" <?php if ($information && $information[0]["fk_id_job"] == $jobs[$i]["id_job"]) {
																										echo "selected";
																									}  ?>><?php echo $jobs[$i]["job_description"]; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-6">

							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="hddTask">Date :</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="date" name="date"
										value="<?php echo $information ? $information[0]['date_issue'] : date('Y-m-d'); ?>"
										placeholder="Date" required <?php echo $deshabilitar; ?> />
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="hddTask">Due Date :</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="due_date" name="due_date" value="<?php echo $information ? $information[0]["due_date"] : ""; ?>" placeholder="Due Date" required <?php echo $deshabilitar; ?> />
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="company">Company:</label>
									<div class="col-sm-5">
										<input type="hidden" id="company" name="company" class="form-control" placeholder="Company" value="<?php echo $information ? $information[0]["id_company"] : ""; ?>" <?php echo $deshabilitar; ?>>
										<input type="text" id="companyName" name="companyName" class="form-control" placeholder="Company" value="<?php echo $information ? $information[0]["company_name"] : ""; ?>" readonly>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="company">Email:</label>
									<div class="col-sm-5">
										<input type="text" id="companyEmail" name="companyEmail" class="form-control" placeholder="Email" value="<?php echo $information ? $information[0]["email"] : ""; ?>" readonly>
									</div>
								</div>
							</div>
						</div>

						<?php 
						$year = date("Y");
						$numberValue = $nextInvoiceNumber;

						if($information){
							$parts = explode("-", $information[0]["number"]);
							if(count($parts) == 2){
								$year = $parts[0];
								$numberValue = $parts[1];
							}
						}
						?>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group text-danger">
									<label class="col-sm-4 control-label" for="number">Invoice #:</label>
									<div class="col-sm-5">
										<div class="input-group">
											<span class="input-group-addon"><?php echo $year; ?>-</span>
											<input type="text" 
												id="number" 
												name="number" 
												class="form-control"
												value="<?php echo $numberValue; ?>"
												<?php echo $deshabilitar; ?>>
										</div>
									</div>
								</div>

								<input type="hidden" id="year" name="year" value="<?php echo $year; ?>">
							</div>

							<div class="col-md-6">
								<?php
								if ($information) {
								?>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="taskDescription">Status :</label>
										<div class="col-sm-5">
											<select name="status" id="status" class="form-control" <?php echo $deshabilitar; ?>>
												<option value=''>Select...</option>
												<?php
												if($statusList) {
													foreach ($statusList as $status) {
												?>
													<option value="<?php echo $status["status_slug"]; ?>" <?php if($information && $information[0]["invoice_status"] == $status["status_slug"]) { echo "selected"; }  ?> ><?php echo $status["status_name"]; ?> </option>
												<?php
													}
												}
												?>
											</select>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="taskDescription">Link to :</label>
									<div class="col-sm-5">
										<select name="link_to" id="link_to" class="form-control">
											<option value=''>Select...</option>
											<option value='wo' <?php if($information && $information[0]["is_wo_or_claim"] == 'wo') { echo "selected"; }  ?>>W.O.</option>
											<option value='claim' <?php if($information && $information[0]["is_wo_or_claim"] == 'claim') { echo "selected"; }  ?>>Claim</option>
										</select>
									</div>
								</div>

								<input type="hidden" id="selected_link_id" value="<?php echo $information ? $information[0]['fk_id_wo_or_claim'] : ''; ?>">
							</div>

							<div class="col-md-6">
								<div class="form-group" id="div_list_work_order">
									<label class="col-sm-4 control-label" id="label_list" for="work_order_div">Select Work Order</label>
									<div class="col-sm-5">
										<select name="list_work_order" id="list_work_order" class="form-control">
											<option value="">Select...</option>
										</select>
									</div>
								</div>
							</div>
						</div>




						<?php if (!$deshabilitar) { ?>
							<div class="form-group">
								<div class="col-sm-12 text-right">

									<button type="button" id="btnSubmit" class="btn btn-primary btn-lg">
										<i class="fa fa-save"></i> Save Invoice
									</button>

								</div>
							</div>
						<?php } ?>

					</form>
				</div>
			</div>
		</div>
	</div>

	<!--INICIO FORMULARIOS -->
	<?php
	if ($information) {
	?>

		<!--ITEMS -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<b>ITEMS</b>
					</div>
					<div class="panel-body">

						<?php if (!$deshabilitar) { ?>
							<div class="col-lg-12">
								<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modal" id="<?php echo $idInvoice; ?>">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add an Item
								</button><br>
							</div>
						<?php } ?>

						<?php
						if ($items) {
						?>
							<form method="post" action="<?php echo base_url('invoices/save_all'); ?>">
								<input type="hidden" name="hddIdInvoice" value="<?php echo $idInvoice; ?>">

								<table class="table table-bordered table-striped table-hover table-condensed table-mobile">
									<thead>
										<tr>
											<th width='40%' class="text-center">Description</th>
											<th width='10%' class="text-right">Quantity</th>
											<th width='10%' class="text-center">Unit</th>
											<th width='10%' class="text-right">Rate</th>
											<th width='10%' class="text-right">Markup</th>
											<th width='10%' class="text-right">Value</th>
											<th width='10%' class="text-center">Actions</th>
										</tr>
									</thead>

									<?php 
										$total = 0;
										foreach ($items as $data): 
											$total += $data['value'];
									?>
											<tr>
												<td>

													<label class="td-label">Description</label>
													<textarea 
													name="description[]" 
													class="form-control" 
													rows="3"
													required
													<?php echo $deshabilitar; ?>><?php echo htmlspecialchars(trim($data['description'])); ?></textarea>

													<input type="hidden" name="id_item[]" value="<?php echo $data['id_invoices_items']; ?>">

												</td>

												<td class="table-desktop-numeric">
													<label class="td-label">Quantity</label>
													<input type="number" step="0.5" name="quantity[]" class="form-control quantity-field"
													value="<?php echo $data['quantity']; ?>" required>
												</td>

												<td>
													<label class="td-label">Unit</label>
													<input type="text" name="unit[]" class="form-control"
													value="<?php echo $data['unit']; ?>" required>
												</td>

												<td class="table-desktop-numeric">
													<label class="td-label">Rate</label>

													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="number" step="any" name="rate[]" class="form-control rate-field"
														value="<?php echo $data['rate']; ?>" required>
													</div>

												</td>

												<td class="table-desktop-numeric">
													<label class="td-label">Markup</label>
													<input type="number" step="0.5" name="markup[]" class="form-control markup-field"
													value="<?php echo $data['markup']; ?>" required>
												</td>

												<td class="table-desktop-numeric">
													<label class="td-label">Value</label>

													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" class="form-control total-field" value="<?php echo number_format($data['value'],2); ?>" readonly>
													</div>
												</td>

												<td class="text-center action-col">
													<?php if (!$deshabilitar) { ?>
													<a class="btn btn-danger btn-xs"
													href="<?php echo base_url('invoices/delete_item/'.$data['id_invoices_items'].'/'.$idInvoice); ?>">
													<i class="fa fa-trash"></i>
													</a>
													<?php } ?>
												</td>
											</tr>
									<?php endforeach; ?>
								</table>


								<?php
									$total_paid = 0;
									if(!empty($payments)){
										foreach($payments as $p){
											$total_paid += $p['amount'];
										}
									}
								?>

								<div class="row" style="margin-top:20px">
									<div class="col-md-4 col-md-offset-8">
										<div class="panel panel-default">
												<div class="panel-body invoice-summary">
													<div class="form-group">
														<label>Subtotal</label>
														<input type="text" id="subtotal"
															class="form-control money total-field"
															readonly>
													</div>

													<div class="form-group">
														<label>GST</label>
														<input type="text" id="gst"
															class="form-control money total-field"
															readonly>
													</div>

													<div class="form-group">
														<label>Total</label>
														<input type="text" id="invoice_total"
															class="form-control money invoice-total"
															readonly>
													</div>

													<div class="form-group">
														<label>Total Paid</label>
														<input type="text"
															id="total_paid"
															class="form-control money total-field"
															value="<?php echo number_format($total_paid,2,'.',''); ?>"
															readonly>
													</div>

													<div class="form-group">
														<label>Balance Due</label>
														<input type="text" id="balance_due"
															class="form-control money balance-due"
															readonly>
													</div>

											</div>
										</div>
									</div>
								</div>





								<?php if(!$deshabilitar){ ?>
									<div class="text-right" style="margin-top:20px;">
										<button type="submit" class="btn btn-primary btn-lg">
											<span class="glyphicon glyphicon-floppy-disk"></span> Save All Items
										</button>
									</div>
								<?php } ?>

							</form>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<!--FIN ITEM -->

		<!--IMAGES -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<b>FILES</b>
					</div>

					<div class="panel-body">
						<form action="<?php echo site_url('invoices/upload_file/'.$idInvoice); ?>" 
							method="post" 
							enctype="multipart/form-data">

							<div class="row">
								<div class="col-md-6">
									<input type="file" name="file" class="form-control" required>
								</div>

								<div class="col-md-2">
									<button class="btn btn-success">
										Upload
									</button>
								</div>
							</div>
						</form>

						<hr>
						<div class="row">
							<?php if(!empty($files)){ ?>
								<?php foreach($files as $f){ ?>
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body text-center">
												<i class="fa fa-file fa-3x"></i>
												<br><br>
												<a href="<?php echo base_url('files/invoices/'.$f['file_name']); ?>" target="_blank">
													<?php echo esc($f['file_name']); ?>
												</a>
											</div>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--FIN IMAGES -->

		<!--PAYMENTS -->
		<?php
		if ($items) {
		?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-primary">

						<div class="panel-heading">
							<b>PAYMENTS RECEIVED</b>
						</div>

						<div class="panel-body">

							<form action="<?php echo site_url('invoices/add_payment/'.$idInvoice); ?>" method="post">

								<div class="row">

									<div class="col-md-3">
										<label>Amount Paid</label>
										<input type="number" step="any" id="amount" name="amount" class="form-control" required>
									</div>

									<div class="col-md-3">
										<label>Date Paid</label>
										<input type="date" name="date_paid" class="form-control" required>
									</div>

									<div class="col-md-3">
										<label>Reference</label>
										<input type="text" id="reference" name="reference" class="form-control">
									</div>

									<div class="col-md-2" style="margin-top:25px;">
										<button class="btn btn-primary">
											Add Payment
										</button>
									</div>

								</div>

							</form>

							<hr>

							<h4>Payments History</h4>

							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Date</th>
										<th>Amount</th>
										<th>Reference</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>

								<tbody>

								<?php 
								if(!empty($payments)){
									foreach($payments as $p){
								?>

									<tr>
										<td><?php echo esc($p['date_paid']); ?></td>
										<td>$<?php echo number_format($p['amount'],2); ?></td>
										<td><?php echo esc($p['reference']); ?></td>
										<td class="text-center action-col">
											<a class="btn btn-danger btn-xs"
											onclick="return confirm('Delete this payment?')"
											href="<?php echo base_url('invoices/delete_payment/'.$p['id_invoice_payment'].'/'.$idInvoice); ?>">
												<i class="fa fa-trash"></i>
											</a>
										</td>
									</tr>

								<?php }} ?>

								</tbody>

								<tfoot>
									<tr>
										<th>Total Paid</th>
										<th>$<?php echo number_format($total_paid,2); ?></th>
									</tr>
								</tfoot>

							</table>

						</div>
					</div>
				</div>
			</div>
		<?php
		}
		 ?>
		<!--FIN PAYMENTS -->

	<?php } ?>

</div>

<!--INICIO Modal para ITEM -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>
<!--FIN Modal para ITEM -->

<div class="modal fade" id="modalPreview" tabindex="-1">
    <div class="modal-dialog modal-lg" style="width:90%">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice Preview</h4>
            </div>

            <div class="modal-body" style="height:80vh">

                <iframe id="iframePreview"
                        style="width:100%; height:100%; border:none;">
                </iframe>

            </div>

        </div>
    </div>
</div>