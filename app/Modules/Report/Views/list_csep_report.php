<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						<i class="fa fa-bar-chart-o fa-fw"></i> REPORT CENTER
					</h4>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->
	</div>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url() . 'report/searchByDateRange/csep_report'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-life-saver fa-fw"></i> CSEP REPORT
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="alert alert-info">
						<strong>From Date: </strong><?php echo $from; ?>
						<strong>To Date: </strong><?php echo $to; ?>
					</div>
					<?php
					if (!$info) {
					?>
						<div class="alert alert-danger">
							No data was found matching your criteria.
						</div>
					<?php
					} else {
					?>
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th>#</th>
									<th>Job Code/Name</th>
									<th>Reported by</th>
									<th>Date</th>
									<th>Location</th>
									<th>Purpose of entry</th>
									<th>PDF</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($info as $lista) :
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['id_job_confined'] . "</td>";
									echo "<td>" . $lista['job_description'] . "</td>";
									echo "<td>" . $lista['name'] . "</td>";
									echo "<td class='text-center'>" . $lista['date_confined'] . "</td>";
									echo "<td>" . $lista['location'] . "</td>";
									echo "<td>" . $lista['purpose'] . "</td>";
									echo "<td class='text-center'>";
								?>
									<a class='btn btn-success btn-xs' href='<?php echo base_url('more/add_confined/' . $lista['fk_id_job'] . '/' . $lista['id_job_confined'] ) ?>' title="Edit">
										<span class="glyphicon glyphicon-edit" aria-hidden="true">
									</a>	

									<a href='<?php echo base_url('more/generaConfinedPDF/' . $lista['id_job_confined']) ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>'></a>
								<?php
									echo "</td>";
									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>
					<?php }	?>
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
			"pageLength": 25,
		});
	});
</script>