<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/workorder/search_income.js"); ?>"></script>

<div id="page-wrapper">

	<br>

	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-success">
				<div class="panel-heading">
					<i class="fa fa-money"></i> <strong>WORK ORDERS - Job Code/Name income for a period of time </strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-success">
						<strong>Note:</strong>
						You have to select all fields.
					</div>
					<form name="form" id="form" role="form" method="post" class="form-horizontal">

						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-1">
								<label for="from">Job Code/Name </label>
								<select name="jobName" id="jobName" class="form-control js-example-basic-single">
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($jobList); $i++) { ?>
										<option value="<?php echo $jobList[$i]["id_job"]; ?>"><?php echo $jobList[$i]["job_description"]; ?></option>
									<?php } ?>
								</select>
							</div>

						</div>


						<script>
							$(function() {
								var dateFormat = "yy-mm-dd",
									from = $("#from")
									.datepicker({
										dateFormat: dateFormat,
										changeMonth: true,
										numberOfMonths: 2
									})
									.on("change", function() {
										to.datepicker("option", "minDate", getDate(this));
									}),
									to = $("#to").datepicker({
										dateFormat: dateFormat,
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
						<div class="form-group">
							<div class="col-sm-5 col-sm-offset-1">
								<label for="from">From Date</label>
								<input type="text" id="from" name="from" class="form-control" placeholder="From">
							</div>
							<div class="col-sm-5">
								<label for="to">To Date</label>
								<input type="text" id="to" name="to" class="form-control" placeholder="To">
							</div>
						</div>

						<div class="row"></div><br>
						<div class="form-group">
							<div class="row" align="center">
								<div style="width80%;" align="center">

									<button type="submit" class="btn btn-success" id='btnSubmit' name='btnSubmit'><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search </button>

								</div>
							</div>
						</div>

					</form>

				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<i class="fa fa-bell fa-fw"></i> Notifications Panel - Word Orders <?php echo date("Y"); ?>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<a href="<?php echo base_url("workorders/wo_by_state/0/" . date("Y")); ?>" class="list-group-item">
							<p class="text-danger"><i class="fa fa-thumb-tack fa-fw"></i><strong> On Field</strong>
								<span class="pull-right text-muted small"><em><?php echo $noOnfield; ?></em>
								</span>
							</p>
						</a>
						<a href="<?php echo base_url("workorders/wo_by_state/1/" . date("Y")); ?>" class="list-group-item">
							<p class="text-warning"><i class="fa fa-refresh fa-fw"></i><strong> In Progress</strong>
								<span class="pull-right text-muted small"><em><?php echo $noProgress; ?></em>
								</span>
							</p>
						</a>
						<a href="<?php echo base_url("workorders/wo_by_state/2/" . date("Y")); ?>" class="list-group-item">
							<p class="text-primary"><i class="fa fa-check fa-fw"></i><strong> Revised</strong>
								<span class="pull-right text-muted small"><em><?php echo $noRevised; ?></em>
								</span>
							</p>
						</a>
						<a href="<?php echo base_url("workorders/wo_by_state/5/" . date("Y")); ?>" class="list-group-item">
							<p class="text-warning"><i class="fa fa-list-alt fa-fw"></i><strong> Accounting</strong>
								<span class="pull-right text-muted small"><em><?php echo $noAccounting; ?></em>
								</span>
							</p>
						</a>
						<a href="<?php echo base_url("workorders/wo_by_state/3/" . date("Y")); ?>" class="list-group-item">
							<p class="text-success"><i class="fa fa-envelope-o  fa-fw"></i><strong> Send to the Client</strong>
								<span class="pull-right text-muted small"><em><?php echo $noSend; ?></em>
								</span>
							</p>
						</a>
						<a href="<?php echo base_url("workorders/wo_by_state/4/" . date("Y")); ?>" class="list-group-item">
							<p class="text-danger"><i class="fa fa-power-off fa-fw"></i><strong> Closed</strong>
								<span class="pull-right text-muted small"><em><?php echo $noClosed; ?></em>
								</span>
							</p>
						</a>

					</div>

				</div>
			</div>

		</div>

	</div>

	<?php if (!empty($idJob)){ ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-success">
					<div class="panel-heading">
						<i class="fa fa-briefcase"></i> JOBS INFO - INCOME<br>
						<strong>Job Code/Name: </strong><?php echo $jobListSearch[0]['job_description']; ?>
						<br><strong> From Date: </strong> <?php echo $from; ?>
						<strong>To Date: </strong> <?php echo $to; ?>
					</div>
					<div class="panel-body small">

						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTablesIncome">
							<thead>
								<tr>
									<th class="text-center">Job Code/Name</th>
									<th class="text-center">Numbers of W.O.</th>
									<th class="text-center">Numbers of Personal Hours</th>
									<th class="text-center">Personal Income</th>
									<th class="text-center">Material Income</th>
									<th class="text-center">Receipt Income</th>
									<th class="text-center">Equipment Income</th>
									<th class="text-center">Subcontractor Income</th>
									<th class="text-center">Total Income</th>
									<th class="text-center">Download</th>
								</tr>
							</thead>
							<tbody>
								<?php
								echo "<tr>";
								echo "<td>" . $jobListSearch[0]['job_description'] . "</td>";
								echo "<td class='text-center'>" . $noWO . "</td>";
								echo "<td class='text-center'>" . $hoursPersonal . "</td>";

								echo "<td class='text-right'>";
								echo '$' . number_format($incomePersonal, 2);
								echo "</td>";

								echo "<td class='text-right'>";
								echo '$' . number_format($incomeMaterial, 2);
								echo "</td>";

								echo "<td class='text-right'>";
								echo '$' . number_format($incomeReceipt, 2);
								echo "</td>";

								echo "<td class='text-right'>";
								echo '$' . number_format($incomeEquipment, 2);
								echo "</td>";

								echo "<td class='text-right'>";
								echo '$' . number_format($incomeSubcontractor, 2);
								echo "</td>";

								echo "<td class='text-right'>";
								echo '$' . number_format($total, 2);
								echo "</td>";

								echo "<td class='text-center'>";
								?>

								<a href='<?php echo base_url('workorders/generaWorkOrderXLS/' . $idJob . '/' . $fromFormat . '/' . $toFormat); ?>' target="_blank"> <img src='<?php echo base_url('images/xls.png'); ?>'></a>


								<?php
								echo "</td>";
								echo "</tr>";
								?>
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-briefcase"></i> JOBS INFO - Income for all Job Codes/Names
				</div>
				<div class="panel-body small">

					<?php
					if ($jobList) {
					?>
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th class="text-center">Job Code/Name</th>
									<th class="text-center">Numbers of W.O.</th>
									<th class="text-center">Numbers of Personal Hours</th>
									<th class="text-center">Personal Income</th>
									<th class="text-center">Material Income</th>
									<th class="text-center">Receipt Income</th>
									<th class="text-center">Equipment Income</th>
									<th class="text-center">Subcontractor Income</th>
									<th class="text-center">Total Income</th>
									<th class="text-center">Download</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($jobList as $lista): ?>

									<tr>
										<td><?= $lista['job_description'] ?></td>
										<td class="text-center"><?= $lista['noWO'] ?></td>
										<td class="text-center"><?= $lista['hoursPersonal'] ?></td>

										<td class="text-right">$<?= number_format($lista['incomePersonal'], 2) ?></td>
										<td class="text-right">$<?= number_format($lista['incomeMaterial'], 2) ?></td>
										<td class="text-right">$<?= number_format($lista['incomeReceipt'], 2) ?></td>
										<td class="text-right">$<?= number_format($lista['incomeEquipment'], 2) ?></td>
										<td class="text-right">$<?= number_format($lista['incomeSubcontractor'], 2) ?></td>

										<td class="text-right">
											$<?= number_format($lista['totalIncome'], 2) ?>
										</td>

										<td class="text-center">
											<a href="<?= base_url('workorders/generaWorkOrderXLS/' . $lista['id_job']); ?>" target="_blank">
												<img src="<?= base_url('images/xls.png'); ?>">
											</a>
										</td>
									</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
					<?php } ?>
				</div>
			</div>
		</div>
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
</script>