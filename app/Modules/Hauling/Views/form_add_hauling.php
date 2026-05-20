<script type="text/javascript" src="<?php echo base_url("assets/js/validate/hauling/ajaxTruck.js?v=1.0.0"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/hauling/hauling.js?v=1.0.0"); ?>"></script>

<script>
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});
</script>

<?php
/**
 * If it is an ADMIN user, show date 
 * @author BMOTTAG
 * @since  23/12/2017
 */
$userRol = session()->rol;
if ($userRol == 99) {
?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php } ?>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						<a class="btn btn-primary btn-xs" href=" <?php echo base_url('dashboard/hauling'); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
						<i class="fa fa-truck fa-fw"></i> <b>RECORD TASK(S) - HAULING</b>
					</h4>
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

					<?php
					if ($HaulingClose) {
					?>
						<div class="col-lg-12">
							<div class="alert alert-success ">
								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
								This hauling form is close.
							</div>
						</div>
					<?php
					} else {
					?>

						<?php
						if ($information && $information["state"] != 1) {
						?>
							<div class="col-lg-12">
								<div class="alert alert-danger ">
									<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									This hauling form is close.
								</div>
							</div>
						<?php
						}
						?>

						<?php if ($information) { ?>

							<div class="col-lg-6">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<i class="fa fa-edit fa-fw"></i> V-Contracting
									</div>

									<div class="panel-body">

										<?= view('App\Views\template\signature_component', [
											'imageUrl'        => $information["vci_signature"] ?? null,
											'formAction'      => base_url('hauling/save_signature'),
											'height'          => 200,
											'signButtonText'  => ' VCI Signature ',
											'id' 			  => 'vci',
											'extraValue' 	  => $information["id_hauling"]
										]) ?>

									</div>

								</div>
							</div>

							<div class="col-lg-6">
								<div class="panel panel-info">
									<div class="panel-heading">
										<i class="fa fa-edit fa-fw"></i> Subcontractor
										<a href="<?php echo base_url("hauling/email/" . $information["id_hauling"]); ?>" class="btn btn-danger btn-xs class_disabled"> <span class="glyphicon glyphicon-send" aria-hidden="true"></span> Send Email</a>
									</div>

									<div class="panel-body">

										<?= view('App\Views\template\signature_component', [
											'imageUrl'        => $information["contractor_signature"] ?? null,
											'formAction'      => base_url('hauling/save_signature'),
											'height'          => 200,
											'signButtonText'  => ' Contractor Signature ',
											'id' 			  => 'contractor',
											'extraValue' 	  => $information["id_hauling"]
										]) ?>

									</div>
								</div>
							</div>
						<?php } ?>


						<form name="form" id="form" class="form-horizontal" method="post">
							<input type="hidden" id="hddId" name="hddId" value="<?php echo $information ? $information["id_hauling"] : ""; ?>" />
							<input type="hidden" id="state" value="<?php echo $information ? $information["state"] : ""; ?>" />

							<?php
							/**
							 * If it is an ADMIN user, show date 
							 * @author BMOTTAG
							 * @since  23/12/2017
							 */
							if ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_MANAGER) {
							?>
								<script>
									$(function() {
										$("#date").datepicker({
											changeMonth: true,
											changeYear: true,
											dateFormat: 'yy-mm-dd'
										});
									});
								</script>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="date">Date of Issue</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="date" name="date" value="<?php echo $information ? $information["date_issue"] : ""; ?>" placeholder="Date of Issue" />
									</div>
								</div>
							<?php } ?>


							<div class="form-group">
								<label class="col-sm-4 control-label" for="company">VCI or Subcontractor</label>
								<div class="col-sm-5">
									<select name="CompanyType" id="CompanyType" class="form-control" required <?php if ($information) { ?>
										disabled
										<?php } ?>>

										<option value="">Select...</option>
										<option value=1 <?php if ($information && $information["company_type"] == 1) {
															echo "selected";
														}  ?>>VCI</option>
										<option value=2 <?php if ($information && $information["company_type"] == 2) {
															echo "selected";
														}  ?>>Subcontractor</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="company">Hauling done by</label>
								<div class="col-sm-5">
									<select name="company" id="company" class="form-control js-example-basic-single">
										<option value=''>Select...</option>
										<?php for ($i = 0; $i < count($companyList); $i++) { ?>
											<option value="<?php echo $companyList[$i]["id_company"]; ?>" <?php if ($information && $information["fk_id_company"] == $companyList[$i]["id_company"]) {
																												echo "selected";
																											}  ?>><?php echo $companyList[$i]["company_name"]; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<?php
							$mostrar = "none";
							$mostrarTruck = "inline";
							$plateRequired = "";
							$truckRequired = "";
							if ($information && $information["company_type"] == 2) {
								$mostrar = "inline";
								$mostrarTruck = "none";
								$plateRequired = "required";
								$truckRequired = "";
							} elseif ($information && $information["company_type"] == 1) {
								$truckRequired = "required";
							}
							?>
							<div class="form-group" id="div_truck" style="display:<?php echo $mostrarTruck; ?>">
								<label class="col-sm-4 control-label" for="truck">Truck</label>
								<div class="col-sm-5">
									<select name="truck" id="truck" class="form-control js-example-basic-single" <?php echo $truckRequired; ?>>

										<?php if ($information) { ?>
											<option value=''>Select...</option>
											<?php for ($i = 0; $i < count($truckList); $i++) { ?>
												<option value="<?php echo $truckList[$i]["id_vehicle"]; ?>" <?php if ($information && $information["fk_id_truck"] == $truckList[$i]["id_vehicle"]) {
																												echo "selected";
																											}  ?>><?php echo $truckList[$i]["unit_number"]; ?></option>
										<?php }
										} ?>

									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="truckType">Truck Type</label>
								<div class="col-sm-5">
									<select name="truckType" id="truckType" class="form-control js-example-basic-single">
										<option value=''>Select...</option>
										<?php for ($i = 0; $i < count($truckTypeList); $i++) { ?>
											<option value="<?php echo $truckTypeList[$i]["id_truck_type"]; ?>" <?php if ($information && $information["fk_id_truck_type"] == $truckTypeList[$i]["id_truck_type"]) {
																													echo "selected";
																												}  ?>><?php echo $truckTypeList[$i]["truck_type"]; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group" id="div_plate" style="display:<?php echo $mostrar; ?>">
								<label class="col-sm-4 control-label" for="plate">Plate Number </label>
								<div class="col-sm-5">
									<input type="text" id="plate" name="plate" class="form-control" value="<?php echo $information ? $information["plate"] : ""; ?>" placeholder="Plate number" <?php echo $plateRequired; ?>>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="materialType">Material Type</label>
								<div class="col-sm-5">
									<select name="materialType" id="materialType" class="form-control js-example-basic-single">
										<option value=''>Select...</option>
										<?php for ($i = 0; $i < count($materialTypeList); $i++) { ?>
											<option value="<?php echo $materialTypeList[$i]["id_material"]; ?>" <?php if ($information && $information["fk_id_material"] == $materialTypeList[$i]["id_material"]) {
																													echo "selected";
																												}  ?>><?php echo $materialTypeList[$i]["material"]; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

<?php
$deshabilitar = '';
if ($information && $information["fk_id_workorder"] != null) { 
	$deshabilitar = 'disabled';
}	
?>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="fromSite">Job Code/Name</label>
								<div class="col-sm-5">
									<select name="fromSite" id="fromSite" class="form-control js-example-basic-single" <?php echo $deshabilitar; ?>>
										<option value=''>Select...</option>
										<?php for ($i = 0; $i < count($jobs); $i++) { ?>
											<option value="<?php echo $jobs[$i]["id_job"]; ?>" <?php if ($information && $information["fk_id_site_from"] == $jobs[$i]["id_job"]) {
																									echo "selected";
																								}  ?>><?php echo $jobs[$i]["job_description"]; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="toSite">To Site</label>
								<div class="col-sm-5">
									<select name="toSite" id="toSite" class="form-control js-example-basic-single">
										<option value=''>Select...</option>
										<?php for ($i = 0; $i < count($jobs); $i++) { ?>
											<option value="<?php echo $jobs[$i]["id_job"]; ?>" <?php if ($information && $information["fk_id_site_to"] == $jobs[$i]["id_job"]) {
																									echo "selected";
																								}  ?>><?php echo $jobs[$i]["job_description"]; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="timeIn">Time In</label>
								<div class="col-sm-2">
									<?php
									$hourIn = $minIn = 0;
									if ($information && $information["time_in"]) {
										$timeIn = explode(":", $information["time_in"]);
										$hourIn = $timeIn[0];
										$minIn = $timeIn[1];
									}
									?>
									<select name="hourIn" id="hourIn" class="form-control js-example-basic-single" required>
										<option value=''>Select...</option>
										<?php
										for ($i = 0; $i < 24; $i++) {
										?>
											<option value='<?php echo $i; ?>' <?php
																				if ($information && $i == $hourIn) {
																					echo 'selected="selected"';
																				}
																				?>><?php echo $i; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-2">
									<select name="minIn" id="minIn" class="form-control js-example-basic-single" required>
										<option value="00" <?php if ($information && $minIn == "00") {
																echo "selected";
															}  ?>>00</option>
										<option value="15" <?php if ($information && $minIn == "15") {
																echo "selected";
															}  ?>>15</option>
										<option value="30" <?php if ($information && $minIn == "30") {
																echo "selected";
															}  ?>>30</option>
										<option value="45" <?php if ($information && $minIn == "45") {
																echo "selected";
															}  ?>>45</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="fromSite">Time Out</label>
								<div class="col-sm-2">
									<?php
									$hourOut = $minOut = 0;
									if ($information && $information["time_out"]) {
										$timeOut = explode(":", $information["time_out"]);
										$hourOut = $timeOut[0];
										$minOut = $timeOut[1];
									}
									?>
									<select name="hourOut" id="hourOut" class="form-control js-example-basic-single" required>
										<option value=''>Select...</option>
										<?php
										for ($i = 0; $i < 24; $i++) {
										?>
											<option value='<?php echo $i; ?>' <?php
																				if ($information && $i == $hourOut) {
																					echo 'selected="selected"';
																				}
																				?>><?php echo $i; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-2">
									<select name="minOut" id="minOut" class="form-control js-example-basic-single" required>
										<option value="00" <?php if ($information && $minOut == "00") {
																echo "selected";
															}  ?>>00</option>
										<option value="15" <?php if ($information && $minOut == "15") {
																echo "selected";
															}  ?>>15</option>
										<option value="30" <?php if ($information && $minOut == "30") {
																echo "selected";
															}  ?>>30</option>
										<option value="45" <?php if ($information && $minOut == "45") {
																echo "selected";
															}  ?>>45</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="payment">Payment</label>
								<div class="col-sm-5">
									<select name="payment" id="payment" class="form-control js-example-basic-single">
										<option value=''>Select...</option>
										<?php for ($i = 0; $i < count($paymentList); $i++) { ?>
											<option value="<?php echo $paymentList[$i]["id_payment"]; ?>" <?php if ($information && $information["fk_id_payment"] == $paymentList[$i]["id_payment"]) {
																												echo "selected";
																											}  ?>><?php echo $paymentList[$i]["payment"]; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<?php if ($information && $information["fk_id_workorder"] != null) { ?>
								<div class="form-group">
									<input type="hidden" name="id_work_order" id="id_work_order" value="">
									<input type="hidden" name="list_work_order" id="list_work_order" value="<?php echo ($information["fk_id_workorder"]) ?>">
									<label class="col-sm-4 control-label" for="work_order">Work Order</label>
									<div class="col-sm-5">
										<select class="form-control" disabled>
											<option value="<?php echo ($information["fk_id_workorder"]) ?>"><?php echo ($workorder) ?></option>
										</select>
									</div>
								</div>
							<?php } else {   ?>
								<div class="form-group" id="div_work_order">
									<label class="col-sm-4 control-label" for="work_order">Work Order</label>
									<div class="col-sm-5">
										<select name="id_work_order" id="id_work_order" class="form-control">
											<option value="">Select...</option>
											<option value=1 <?php if ($information && $information["id_work_order"] == 1) {
																echo "selected";
															}  ?>>New WO</option>
											<option value=2 <?php if ($information && $information["id_work_order"] == 2) {
																echo "selected";
															}  ?>>Assign WO</option>
										</select>
									</div>
								</div>

								<div class="form-group" id="div_list_work_order">
									<label class="col-sm-4 control-label" for="work_order_div">Select Work Order</label>
									<div class="col-sm-5">
										<select name="list_work_order" id="list_work_order" class="form-control">
											<option value="">Select...</option>

										</select>
									</div>
								</div>
							<?php }  ?>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="comments">Comments</label>
								<div class="col-sm-5">
									<textarea id="comments" name="comments" placeholder="Comments" class="form-control" rows="3"><?php echo $information ? $information["comments"] : ""; ?></textarea>
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
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>