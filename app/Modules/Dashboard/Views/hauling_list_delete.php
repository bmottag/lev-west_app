<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@latest/dist/sweetalert2.min.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@latest/dist/sweetalert2.all.min.js"></script>

<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<a class="btn btn-warning btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-truck"></i> <strong>Most Recent Haul Ticket Report </strong>
				</div>
				<br>
				<ul class="nav nav-pills">
					<li <?php if ($active == 1) {
							echo "class='active'";
						} ?>><a class="class" href="<?php echo base_url("dashboard/hauling"); ?>">List of active Haul Tickets</a>
					</li>
					<?php
					$userRol = session()->get('rol');
					if ($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ENGINEER) {
					?>
						<li <?php if ($active == 2) {
								echo "class='active'";
							} ?>><a class="class" href="<?php echo base_url("dashboard/hauling_delete"); ?>">List of deleted Haul Tickets</a>
						</li><?php } ?>
				</ul>
				<div class="panel-body">

					<a class='btn btn-outline btn-warning btn-block' href='<?php echo base_url('hauling/add_hauling'); ?>'>
						<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span> Create a New Haul Ticket
					</a>

					<br>
					<?php
					if ($infoHauling) {
					?>
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th class='text-center' width="10%">#</th>
									<th class='text-center'>Report Created by</th>
									<th class='text-center'>Date</th>
									<th class='text-center'>Haul Ticket Created by</th>
									<th class='text-center'>Truck - Unit Number</th>
									<th class='text-center'>Truck Type</th>
									<th class='text-center'>Material Type</th>
									<th class='text-center'>Ventures Code</th>
									<th class='text-center'>To Site</th>
									<th class='text-center'>Method of Payment</th>
									<th class='text-center'>Clock In</th>
									<th class='text-center'>Clock Out</th>
									<th class='text-center'>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($infoHauling as $lista):
									echo "<tr>";
									echo "<td class='text-center'>" . $lista['id_hauling'] . "
									<br>"
								?><?php
									if ($lista['fk_id_workorder'] != null) {
									?>
								<a href='<?php echo base_url('workorders/add_workorder/' . $lista['fk_id_workorder']); ?>' target="_blank"> W.O. # <?php echo $lista['fk_id_workorder']; ?></a>
								<?php } ?><?php
											"</td>";
											echo "<td>" . $lista['name'] . "</td>";
											echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
											echo "<td>" . $lista['company_name'] . "</td>";
											echo "<td>" . $lista['unit_number'] . "</td>";
											echo "<td>" . $lista['truck_type'] . "</td>";
											echo "<td >" . $lista['material'] . "</td>";
											echo "<td >" . $lista['site_from'] . "</td>";
											echo "<td >" . $lista['site_to'] . "</td>";
											echo "<td >" . $lista['payment'] . "</td>";
											echo "<td class='text-center'>" . $lista['time_in'] . "</td>";
											echo "<td class='text-center'>" . $lista['time_out'] . "</td>";
											echo "<td class='text-center'>";
											?>
								<a href='<?php echo base_url('report/generaHaulingPDF/x/x/x/x/' . $lista['id_hauling']); ?>' target="_blank" title="Download"> <img src='<?php echo base_url('images/pdf.png'); ?>'></a>
								<a href='<?php echo base_url('hauling/add_hauling/' . $lista['id_hauling']); ?>' class='btn btn-success btn-xs' title="View"><i class='fa fa-eye'></i></a>
							<?php
									echo "</td>";
									echo "</tr>";
								endforeach;
							?>
							</tbody>
						</table>
					<?php } ?>

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

<!-- Tables -->
<style>
	.nav-pills>li.active>a,
	.nav-pills>li.active>a:focus,
	.nav-pills>li.active>a:hover {
		color: #fff;
		background-color: #f0ad4e;
	}

	.class {
		color: #f0ad4e;
		text-decoration: none;
	}
</style>
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

	$('.btn-delete-hauling').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('id');

		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, delete it!"
		}).then((result) => {
			if (result.isConfirmed) {

				$.ajax({
					type: 'POST',
					url: base_url + 'hauling/update_hauling_state',
					data: {
						'hddId': id
					},
					cache: false,
					success: function(response) {

						if (response.result == "error") {
							Swal.fire({
								title: "Error!",
								text: response.mensaje,
								icon: "error",
							})
							$(".btn-delete-hauling").removeAttr('disabled');
							return false;
						}

						if (response.result) //true
						{
							Swal.fire({
								title: "Deleted!",
								text: "Your file has been deleted.",
								icon: "success",
								timer: 2000,
							}).then(() => {
								var url = base_url + "dashboard/hauling";
								$(location).attr("href", url);
							});;


						} else {
							Swal.fire({
								title: "Error!",
								text: "Error. Reload the web page.",
								icon: "error",
							})
							$(".btn-delete-hauling").removeAttr('disabled');
						}
						console.log(response);

					}
				});
			}
		});

	});
</script>