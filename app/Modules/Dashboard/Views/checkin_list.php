<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">
	<br>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-search"></i> <strong>SIGN-IN LIST</strong>
				</div>
				<div class="panel-body">

<script>
	$( function() {
		$( "#date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
</script>

					<form name="formCheckin" id="formCheckin" method="post">
						<div class="panel panel-default">
							<div class="panel-footer">
								<div class="row">
									<div class="col-lg-3">
										<div class="form-group input-group-sm">									
											<input type="text" class="form-control" id="date" name="date" placeholder="Search by date" required />
										</div>
									</div>

									<div class="col-lg-4">
										<div class="form-group">
											<button type="submit" id="btnSearch" name="btnSearch" class="btn btn-primary btn-sm" >
												Search <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
											</button> 
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
							
				<?php
					if($checkinList){
				?>
					<div class="row">
						<div class="col-lg-6">
							<h3><strong>Date: </strong><?php echo $requestDate; ?></h3>
						</div>
						<div class="col-lg-6" align="right">
							<br>
							<div class="btn-group" >
								<a class='btn btn-purpura btn-xs' href='<?php echo base_url('admin/checkin_check') ?>' target="_blank">
									Send SMS to Workers that haven't Sign Out <i class='glyphicon glyphicon-send'></i>
								</a>
								<a href='<?php echo base_url("report/checkinPDF/" . $requestDate); ?>' class='btn btn-info btn-xs' title="Download Report" target="_blank"> Download Report <i class='fa fa-file-pdf-o'></i></a>
							</div>
						</div>
					</div>
				
					<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">Date</th>
								<th >Worker</th>
								<th class="text-center">Phone Number</th>
								<th class="text-center">Sign-In</th>
								<th class="text-center">Sign-Out</th>
								<th class="text-center">Job Code/Name</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($checkinList as $lista):
								$checkOut = $lista['checkout_time'];
								if($lista['checkout_time']=="0000-00-00 00:00:00"){
									$checkOut = "<p class='text-primary'><i class='fa fa-refresh fa-fw'></i><b>Still working</b><p>";
								}
								echo "<tr>";
								echo "<td class='text-center'>" . ucfirst(strftime("%b %d, %G",strtotime($lista['checkin_date']))) . "</td>";
								echo "<td>" . $lista['worker_name'] . "</td>";
								echo "<td class='text-center'>" . $lista['worker_movil'] . "</td>";
								echo "<td class='text-center'>" . $lista['checkin_time'] . "</td>";
								echo "<td class='text-center'>" . $checkOut . "</td>";
								echo "<td class='text-center'>" . $lista['job_description'] . "</td>";
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

<!-- Tables -->
<script>
$(document).ready(function() {
    $('#dataTables').DataTable({
        responsive: true,
		 "ordering": false,
		 paging: false,
		"searching": false
    });
});
</script>