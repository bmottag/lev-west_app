<script type="text/javascript" src="<?php echo base_url("assets/js/validate/programming/programming.js?v=2.0.0"); ?>"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">
	<br>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-book"></i> PLANNING
				</div>
				<div class="panel-body">

					<form name="form" id="form" class="form-horizontal" method="post">
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $information ? $information[0]["id_programming"] : ""; ?>" />
						<input type="hidden" id="hddIdParent" name="hddIdParent" value="<?php echo $information ? $information[0]["parent_id"] : ""; ?>" />
						<input type="hidden" id="job_planning" name="job_planning" />

						<div class="alert alert-info">
							<strong>Info:</strong> Form to add or update a Planning.
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="jobName">Job Code/Name:</label>
							<div class="col-sm-5">
								<select name="jobName" id="jobName" class="form-control" disabled>
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($jobs); $i++) { ?>
										<option value="<?php echo $jobs[$i]["id_job"]; ?>" <?php if ($jobInfo[0]['id_job'] == $jobs[$i]["id_job"]) {
																								echo "selected";
																							}  ?> data-planning="<?php echo $jobs[$i]["planning_message"]; ?>"><?php echo $jobs[$i]["job_description"]; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group date_range">
							<label class="col-sm-4 control-label" for="date">Date or Range:</label>
							<div class="col-sm-2">
								<select name="flag_date" id="flag_date" class="form-control" required>
									<option value=1 <?php if (!$information) {
														echo "selected";
													} elseif ($information[0]["flag_date"] == 1) {
														echo "selected";
													}  ?>>Specific Day</option>
									<option value=2 <?php if ($information && $information[0]["flag_date"] == 2) {
														echo "selected";
													}  ?>>Time Frame</option>
								</select>
							</div>
						</div>

						<?php
						$mostrar = "block";
						$mostrarPeriod = "none";
						if ($information && $information[0]["flag_date"] == 2 && ($information[0]["parent_id"] == null || $information[0]["parent_id"] == '')) {
							$mostrar = "none";
							$mostrarPeriod = "block";
						}
						?>
						<script>
							$(function() {
								$("#date").datepicker({
									changeMonth: true,
									changeYear: true,
									dateFormat: 'yy-mm-dd',
									minDate: '0'
								});
							});
						</script>
						<div class="form-group date-fields" style="display:<?php echo $mostrar; ?>">
							<label class="col-sm-4 control-label" for="date">Date:</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="date" name="date" value="<?php echo $information ? $information[0]["date_programming"] : ""; ?>" placeholder="Date" />
							</div>
						</div>

						<script>
							$(function() {
								var dateFormat = "mm/dd/yy",
									from = $("#from")
									.datepicker({
										changeMonth: true,
										numberOfMonths: 2
									})
									.on("change", function() {
										to.datepicker("option", "minDate", getDate(this));
									}),
									to = $("#to").datepicker({
										changeMonth: true,
										numberOfMonths: 2
									})
									.on("change", function() {
										from.datepicker("option", "maxDate", getDate(this));
									});

								function getDate(element) {
									var date;
									try {
										date = $.datepicker.parseDate(dateFormat, element.value);
									} catch (error) {
										date = null;
									}

									return date;
								}
							});
						</script>

						<div class="form-group period-fields" style="display:<?php echo $mostrarPeriod; ?>">
							<label class="col-sm-4 control-label" for="from">From Date:</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="from" name="from" value="<?php echo $information ? $information[0]["date_programming"] : ""; ?>" placeholder="From Date" />
							</div>

							<label class="col-sm-1 control-label" for="to">To Date:</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="to" name="to" value="<?php echo $information ? $information[0]["date_programming"] : ""; ?>" placeholder="To Date" />
							</div>
						</div>

						<div class="form-group period-fields" style="display:<?php echo $mostrarPeriod; ?>">
							<label class="col-sm-4 control-label" for="date">Apply for:</label>
							<div class="col-sm-2">
								<select name="apply_for" id="apply_for" class="form-control" required>
									<option value=1 <?php if ($information && $information[0]["apply_for"] == 1) {
														echo "selected";
													}  ?>>All Days</option>
									<option value=2 <?php if ($information && $information[0]["apply_for"] == 2) {
														echo "selected";
													}  ?>>Only During The Week</option>
									<option value=3 <?php if ($information && $information[0]["apply_for"] == 2) {
														echo "selected";
													}  ?>>Only Weekends</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="observation">Observation:</label>
							<div class="col-sm-5">
								<textarea id="observation" name="observation" class="form-control" rows="3"><?php echo $information ? $information[0]["observation"] : ""; ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">
									<button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-primary'>
										Submit <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
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
										<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
									</div>
								</div>
							</div>
						</div>




					</form>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->