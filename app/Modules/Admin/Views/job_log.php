<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/workorder/search_log.js"); ?>"></script>

<div id="page-wrapper">

	<br>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-money"></i> <strong>JOB CODE/NAME LOG - SEARCH</strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-info">
						<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
						Select at least one of the following fields
					</div>
					<form name="form" id="form" role="form" method="post" class="form-horizontal">

						<div class="form-group">
							<div class="col-sm-3">
								<label for="from">Job Code/Name </label>
								<select name="jobName" id="jobName" class="form-control js-example-basic-single">
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($jobList); $i++) { ?>
										<option value="<?php echo $jobList[$i]["id_job"]; ?>"><?php echo $jobList[$i]["job_description"]; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="col-sm-3">
								<label for="from">User </label>
								<select name="user" id="user" class="form-control js-example-basic-single">
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($user); $i++) { ?>
										<option value="<?php echo $user[$i]["id_user"]; ?>"><?php echo $user[$i]["first_name"]; ?> <?php echo $user[$i]["last_name"]; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-sm-3">
								<label for="from">From Date</label>
								<input type="text" id="from" name="from" class="form-control" placeholder="From">
							</div>
							<div class="col-sm-3">
								<label for="to">To Date</label>
								<input type="text" id="to" name="to" class="form-control" placeholder="To">
							</div>
						</div>

						<div class="row"></div><br>
						<div class="form-group">
							<div class="row" align="center">
								<div style="width80%;" align="center">

									<button type="submit" class="btn btn-primary" id='btnSubmit' name='btnSubmit'><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Go </button>

								</div>
							</div>
						</div>

					</form>

				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
</div>

<!-- Tables -->
<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"ordering": true,
			paging: false,
			"info": false
		});

		$('.js-example-basic-single').select2({
			width: '100%'
		});
	});

	$(function() {
		var dateFormat = "mm/dd/yy",
			from = $("#from")
			.datepicker({
				changeMonth: true,
				numberOfMonths: 1
			})
			.on("change", function() {
				to.datepicker("option", "minDate", getDate(this));
			}),
			to = $("#to").datepicker({
				changeMonth: true,
				numberOfMonths: 1
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