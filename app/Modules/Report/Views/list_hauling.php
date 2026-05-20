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
					<a class="btn btn-success" href=" <?php echo base_url() . 'report/searchByDateRange/hauling'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-life-saver fa-fw"></i> HAULING REPORT
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="alert alert-info">
						<strong>From Date: </strong><?php echo $from; ?>
						<strong>To Date: </strong><?php echo $to; ?>
						<?php if ($info) { ?>
							<br><strong>Download to: </strong>
							<a href='<?php echo base_url('report/generaHaulingXLS/' . $company . '/' . $material . '/' . $from . '/' . $to); ?>'>Excel <img src='<?php echo base_url('images/xls.png'); ?>'></a>

							<a href='<?php echo base_url('report/generaHaulingPDF/' . $company . '/' . $material . '/' . $from . '/' . $to); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>'></a>

						<?php } ?>
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
									<th>Number</th>
									<th>Hauling done by</th>
									<th>Employee</th>
									<th>Truck - Unit Number</th>
									<th>Truck Type</th>
									<th>Plate</th>
									<th>Material Type</th>
									<th>Job Code/Name</th>
									<th>To Site</th>
									<th>Payment</th>
									<th>Date of Issue</th>
									<th>Time In</th>
									<th>Time Out</th>
									<th>Comments</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$total = 0;
								foreach ($info as $lista):
									echo "<tr>";
									echo "<td>" . $lista['id_hauling'] . "</td>";
									echo "<td>" . $lista['company_name'] . "</td>";
									echo "<td>" . $lista['name'] . "</td>";
									echo "<td>" . $lista['unit_number'] . "</td>";
									echo "<td>" . $lista['truck_type'] . "</td>";
									echo "<td>" . $lista['plate'] . "</td>";
									echo "<td >" . $lista['material'] . "</td>";
									echo "<td >" . $lista['site_from'] . "</td>";
									echo "<td >" . $lista['site_to'] . "</td>";
									echo "<td >" . $lista['payment'] . "</td>";
									echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
									echo "<td class='text-center'>" . $lista['time_in'] . "</td>";
									echo "<td class='text-center'>" . $lista['time_out'] . "</td>";
									echo "<td >" . $lista['comments'] . "</td>";
									echo "</tr>";
								endforeach;
								?>
							</tbody>
						</table>
					<?php }	?>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>


</div>
<!-- /#page-wrapper -->

<!-- Tables -->
<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"ordering": false
		});
	});
</script>