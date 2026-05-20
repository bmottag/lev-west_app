<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					<i class="fa fa-money"></i> <strong> SUBCONTRACTORS INVOICES</strong>
				</div>
				<div class="panel-body">

					<a class='btn btn-success btn-block' href='<?php echo base_url('workorders/add_invoice/') ?>'>
						<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span> Add Subcontractor Invoice
					</a>

					<br>
					<?php
					if ($information) {
					?>
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
							<thead>
								<tr>
									<th class='text-center' width="10%">Date of Issue</th>
									<th class='text-center' width="20%">Subcontractor</th>
									<th class='text-center' width="10%">Invoice Number</th>
									<th class='text-center' width="20%">Amount</th>
									<th class='text-center' width="10%">Invoice</th>
									<th class='text-center' width="10%">Action</th>
									<th class='text-center' width="10%">Related WO</th>
									<th class='text-center' width="10%">Total WO Value</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($information as $lista) :
									$rowClass = '';
									if($lista['invoice_amount'] != $lista['total_workorder_value']){
										$rowClass = 'text-danger';
									} 

									echo "<tr class='$rowClass'>";
									echo "<td class='text-center'>" . $lista['date_issue'] . "</td>";
									echo "<td>" . $lista['company'] . "</td>";
									echo "<td class='text-center'>" . $lista['invoice_number'] . "</td>";
									echo "<td class='text-right'>$ " . number_format($lista['invoice_amount'], 2) . "</td>";
									echo "<td class='text-right'>";
									if($lista["file"]){ ?>
										<a href="<?php echo base_url('files/sub_invoices/' .$lista["file"]) ?>" target="_blank">View Invoice</a>
									<?php }
									echo "</td>";
									echo "<td class='text-center'>";
									echo "<a class='btn btn-success btn-xs' href='" . base_url('workorders/add_invoice/' . $lista['id_subcontractor_invoice']) . "' title='Edit'> <span class='glyphicon glyphicon-edit' aria-hidden='true'></a>";
									echo "</td>";
									echo "<td class='text-center'>";
									if (!empty($lista['related_workorders'])) {
										$ids = explode(',', $lista['related_workorders']);
										foreach ($ids as $id) {
											echo "<a href='" . base_url("workorders/subcontractor_invoices/" . $id) . "' target='_blank'>$id</a><br>";
										}
									} else {
										echo "—";
									}
									echo "</td>";
									echo "<td class='text-right'>$ " . number_format($lista['total_workorder_value'], 2) . "</td>";
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