<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-dark">
				<div class="panel-heading">
					<i class="fa fa-briefcase"></i> <strong>Accounting Control Sheet (ACS) - Income for all Job Codes/Names</strong>
				</div>
				<div class="panel-body small">

					<?php if ($incomeData) : ?>
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
								<?php foreach ($incomeData as $row) : ?>
								<tr>
									<td><?php echo esc($row['job_description']); ?></td>
									<td class="text-center"><?php echo esc($row['noACS']); ?></td>
									<td class="text-center"><?php echo esc($row['hoursPersonal']); ?></td>
									<td class="text-right">$<?php echo number_format($row['incomePersonal'], 2); ?></td>
									<td class="text-right">$<?php echo number_format($row['incomeMaterial'], 2); ?></td>
									<td class="text-right">$<?php echo number_format($row['incomeReceipt'], 2); ?></td>
									<td class="text-right">$<?php echo number_format($row['incomeEquipment'], 2); ?></td>
									<td class="text-right">$<?php echo number_format($row['incomeSubcontractor'], 2); ?></td>
									<td class="text-right">$<?php echo number_format($row['total'], 2); ?></td>
									<td class="text-center">
										<a href="<?php echo base_url('acs/generaACSXLS/' . $row['id_job']); ?>" target="_blank">
											<img src="<?php echo base_url('images/xls.png'); ?>">
										</a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>

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
