<div class="panel panel-primary">
	<div class="panel-heading"> 
		<i class="fa fa-money fa-fw"></i> <strong>EXPENSES - Equipment Unit Number: <?php echo $vehicleInfo[0]["unit_number"] ?></strong> 
	</div>
	<div class="panel-body small">

		<table width="100%" class="table table-striped table-bordered table-hover" id="dataExpensesList">
			<thead>
				<tr>
					<th class="text-center">S.O. #</th>
					<th class="text-center">Maintenance Type</th>
					<th class="text-center">Description</th>
					<th class="text-center">Invested Time</th>
					<th class="text-center">Parts or Additional Cost</th>
				</tr>
			</thead>
			<tbody>							
			<?php
				foreach ($information as $data):
					$maintenanceType = $data['maintenace_type'] == "corrective" ? "Corrective Maintenance" : "Preventive Maintenance";
					echo "<tr>";
					echo "<td class='text-center'>";
					echo $data['id_service_order'];
		?>
					<br><a class="btn btn-primary btn-xs" onclick="loadEquipmentDetail( <?php echo $data['fk_id_equipment']; ?>, 'tab_service_order_detail', <?php echo $data['id_service_order']; ?>)" title="View">
						<i class="fa fa-eye"></i> View
					</a>
		<?php			
					echo "</td>";
					echo "<td class='text-center'>" . $maintenanceType  . "</td>";
					echo "<td>" . $data['main_description'] . "</td>";
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
		paging: false,
		"ordering": false
	});
});
</script>