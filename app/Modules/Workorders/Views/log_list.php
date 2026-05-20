<script type="text/javascript" src="<?php echo base_url("assets/js/validate/workorder/update_state.js"); ?>"></script>
<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-info btn-xs" href=" <?php echo base_url('workorders/log'); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Back to Search W.O.</a>
					<i class="fa fa-money"></i> <strong>WORK ORDERS - AUDIT LOG</strong>
				</div>
				<div class="panel-body">

					<?php if (session()->getFlashdata('retornoExito')): ?>
						<div class="alert alert-success">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoExito') ?>
						</div>
					<?php endif; ?>

					<?php if (session()->getFlashdata('retornoError')): ?>
						<div class="alert alert-danger">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoError') ?>
						</div>
					<?php endif; ?>

					<?php if (!$workOrderInfo): ?>
						<a href='#' class='btn btn-danger btn-block'>No data was found matching your criteria</a>
					<?php else: ?>
						<table width="100%" class="table table-striped table-bordered table-hover small" id="dataTables">
							<thead>
								<tr>
									<th class='text-center'>W.O. #</th>
									<th class='text-center'>Job Name</th>
									<th class='text-center'>Responsible</th>
									<th class='text-center'>Date</th>
									<th class='text-center'>Action</th>
									<th class='text-center'>Table</th>
									<th class='text-center'>Description</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($workOrderInfo as $lista): ?>
									<tr>
										<td class='text-center'>
											<a href='<?= base_url('workorders/add_workorder/' . esc($lista['type_id'])) ?>'><?= esc($lista['type_id']) ?></a>
										</td>
										<td><?= esc($lista['job_description']) ?></td>
										<td><?= esc($lista['name']) ?></td>
										<td><?= esc($lista['created_on']) ?></td>
										<td class='text-center'><?= esc($lista['token']) ?></td>
										<td class='text-center'><?= esc($lista['type']) ?></td>
										<td><b>Before: </b><?= esc($lista['textOld']) ?><br><br><b>After:</b> <?= esc($lista['textNew']) ?></td>
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
<!-- /#page-wrapper -->

<!-- Tables -->
<script>
	$(document).ready(function() {
		$('#dataTables').DataTable({
			responsive: true,
			"ordering": false,
			paging: false,
			"info": false,
			"searching": false
		});
	});
</script>
