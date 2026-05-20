<div class="panel panel-primary">
	<div class="panel-heading"> 
		<i class="fa fa-money fa-fw"></i> <strong>EXPENSES</strong> 
	</div>
	<div class="panel-body small">

		<table width="100%" class="table table-striped table-bordered table-hover" id="dataExpensesList">
			<thead>
				<tr>
					<th class="text-center">Equipment</th>
					<th class="text-center">Number of S.O.</th>
					<th class="text-center">Invested Time</th>
					<th class="text-center">Parts or Additional Cost</th>
				</tr>
			</thead>
			<tbody>							
			<?php
				foreach ($information as $data):
					echo "<tr>";
					echo "<td><strong>" . $data['unit_number'] . " </strong>" . $data['description']  . "<br>";
			?>
					<a class="btn btn-primary btn-xs" onclick="loadExpensesByEquipment( <?php echo $data['id_vehicle']; ?>)" title="Expenses Detail">
						<i class="fa fa-eye"></i> Expenses Detail
					</a>
			<?php 
					echo "</td>";
					echo "<td class='text-right'>" . $data['so_number'] . "</td>";
					echo "<td class='text-right'>" . number_format($data["time_expenses"]) . "</td>";								
					echo "<td class='text-right'>$ " . number_format($data["parts_expenses"]) . "</td>";
					echo "</tr>";
				endforeach;
			?>
			</tbody>
		</table>
	</div>

</div>

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataExpensesList').DataTable({
		responsive: true,
		paging: false
	});
});
</script>