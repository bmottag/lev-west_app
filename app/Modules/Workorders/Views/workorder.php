<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					<i class="fa fa-money"></i> <strong>WORK ORDERS</strong>
				</div>
				<div class="panel-body">

					<a class='btn btn-success btn-block' href='<?php echo base_url('workorders/add_workorder/') ?>'>
						<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span> Add a Work Order
					</a>

					<br>
					<?php
					if ($workOrderInfo) {
					?>
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th class='text-center'>W.O. #</th>
									<th class='text-center'>Job Code/Name</th>
									<th class='text-center'>In Charge</th>
									<th class='text-center'>Date of Issue</th>
									<th class='text-center'>Date W.O.</th>
									<th class='text-center'>More Information</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($workOrderInfo as $lista) :

									switch ($lista['state']) {
										case 0:
											$valor = 'On Field';
											$clase = "text-danger";
											$icono = "fa-thumb-tack";
											break;
										case 1:
											$valor = 'In Progress';
											$clase = "text-warning";
											$icono = "fa-refresh";
											break;
										case 2:
											$valor = 'Revised';
											$clase = "text-primary";
											$icono = "fa-check";
											break;
										case 3:
											$valor = 'Send to the Client';
											$clase = "text-success";
											$icono = "fa-envelope-o";
											break;
										case 4:
											$valor = 'Closed';
											$clase = "text-danger";
											$icono = "fa-power-off";
											break;
										case 5:
											$valor = 'Accounting';
											$clase = "text-warning";
											$icono = "fa-list-alt";
											break;
									}

									echo "<tr>";
									echo "<td class='text-center'>";
									echo "<a href='" . base_url('workorders/add_workorder/' . $lista['id_workorder']) . "'>" . $lista['id_workorder'] . "</a>";
									echo '<p class="' . $clase . '"><i class="fa ' . $icono . ' fa-fw"></i>' . $valor . '</p>';

									$userRol = session()->get("rol");
									if (($userRol == ID_ROL_SUPER_ADMIN || $userRol == ID_ROL_ENGINEER || $userRol == ID_ROL_WORKORDER) && $lista['expenses_flag'] == 1) {
										echo '<p class="text-dark"><i class="fa fa-flag fa-fw"></i> With Expenses</p>';
									}

									echo "<a class='btn btn-success btn-xs' href='" . base_url('workorders/add_workorder/' . $lista['id_workorder']) . "'> Edit <span class='glyphicon glyphicon-edit' aria-hidden='true'></a>";
									echo "</td>";
									echo "<td>" . $lista['job_description'] . "</td>";
									echo "<td>" . $lista['name'] . "</td>";
									echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
									echo "<td class='text-center'>" . $lista['date'] . "</td>";
									echo '<td>';
									echo '<strong>Work Done:</strong><br>' . $lista['observation'];
									echo '<p class="text-info"><strong>Last message:</strong><br>' . $lista['last_message'] . '</p>';
									echo '</td>';
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
			"searching": false,
			"info": false
		});
	});
</script>