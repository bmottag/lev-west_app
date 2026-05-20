<div>
	<div class="panel-body">

		<?php if ($claims): ?>

			<table width="100%" class="table table-hover dataTable no-footer" id="dataTables">
				<thead>
					<tr>
						<th>Claim #</th>
						<th class="text-right">Quantity</th>
						<th class="text-right">Cost</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($claims as $c): ?>
						<tr>
							<td><?php echo esc($c['claim_number']); ?></td>
							<td class="text-right"><?php echo esc($c['quantity']); ?></td>
							<td class="text-right">$ <?php echo number_format($c['cost'], 2); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<div class="alert alert-warning">No hay claims asociados a este ítem.</div>
		<?php endif; ?>

	</div>
</div>
