<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-violeta">
				<div class="panel-heading">
					<a class="btn btn-violeta btn-xs" href=" <?php echo base_url() . 'jobs'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-umbrella"></i> <strong>BITACORA</strong>
				</div>
				<div class="panel-body">

					<div class="alert alert-violeta">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
					</div>

					<div>
						<button type="button" class="btn btn-violeta btn-block btn-notification" data-toggle="modal" data-target="#modal" id="<?php echo $jobInfo[0]['id_job']; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Notification
						</button>
					</div>

					<br>
					<?php
					if ($bitacora) {
					?>
						<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
							<thead>
								<tr class="headings">
									<th class="column-title" colspan="3">-- Notifications --</th>
								</tr>
								<tr>
									<th>User</th>
									<th class='text-center'>Date Issue</th>
									<th>Notification</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($bitacora as $list) :
									echo "<tr>";
									echo "<td>" . $list['first_name'] . " " .  $list['last_name']  . "</td>";
									echo "<td class='text-center'>" . $list['date_bitacora'] . "</td>";
									echo "<td>" . $list['notification'] . "</td>";
									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>
					<?php } ?>

					<br>
					<?php
					if ($workOrderInfo) {
					?>
						<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
							<thead>
								<tr class="headings">
									<th class="column-title" colspan="3">-- Work Orders Descriptions --</th>
								</tr>
								<tr>
									<th class='text-center'>W.O. #</th>
									<th class='text-center'>Date W.O.</th>
									<th class='text-center'>Task Description</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($workOrderInfo as $lista) :
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['id_workorder'] . "</td>";
									echo "<td class='text-center'>" . $lista['date'] . "</td>";
									echo "<td>" . $lista['observation'] . "</td>";
									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!--INICIO Modal para adicionar HAZARDS -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tableData">

		</div>
	</div>
</div>
<!--FIN Modal para adicionar HAZARDS -->

<!-- Tables -->
<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"searching": false,
			"info": false
		});
	});

	$(function() {
		$(".btn-notification").click(function() {
			var oID = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: base_url + '/jobs/loadModalBitacora',
				data: {
					'idBitacora': oID
				},
				cache: false,
				success: function(data) {
					$('#tableData').html(data);
				}
			});
		});
	});
</script>