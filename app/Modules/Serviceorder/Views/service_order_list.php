<div class="panel panel-primary">
	<div class="panel-heading"> 
		<i class="fa fa-briefcase"></i> <b>Service Orders</b>
	</div>
	<div class="panel-body small">
		<?php 										
			if(!$information){ 
				echo '<div class="col-lg-12">
						<p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> There are no records in the system.</p>
					</div>';
			} else {
		?>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataTablesSObyStatus">
			<thead>
				<tr>
					<th>S.O. #</th>
					<th>Priority</th>
					<th>Unit Number</th>
					<th>VIN Number</th>
					<th>Description</th>
					<th>Assigned To</th>
					<th>Request Date</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>							
			<?php
				foreach ($information as $lista):
						echo "<tr>";
						echo "<td class='text-center'>";
						echo $lista['id_service_order'] . "<br>";
			?>
						<a class="btn btn-primary btn-xs" onclick="loadEquipmentDetail( <?php echo $lista['fk_id_equipment']; ?>, 'tab_service_order_detail', <?php echo $lista['id_service_order']; ?>)" title="View">
							<i class="fa fa-eye"></i> View
						</a>
			<?php			
						echo "</td>";
						echo "<td class='text-center'>";
			?>
						<p class="text-<?php echo $lista['priority_style']; ?>"><i class="fa <?php echo $lista['priority_icon']; ?> fa-fw"></i><?php echo $lista['priority_name']; ?> </p>
			<?php			
						echo "</td>";
						echo "<td>" . $lista['unit_description'] . "</td>";
						echo "<td>" . $lista['vin_number'] . "</td>";
						echo "<td>" . $lista['main_description'] . "</td>";
						echo "<td>" . $lista['assigned_to'] . "</td>";
						echo "<td>" . date('F j, Y - G:i:s', strtotime($lista['created_at'])) . "</td>";
						echo "<td class='text-center'>";
						echo '<p class="text-' . $lista['status_style'] . '"><i class="fa ' . $lista['status_icon'] . ' fa-fw"></i><b>' . $lista['status_name'] . '</b></p>';
						echo "</td>";
						echo "</tr>";
				endforeach;
			?>
			</tbody>
		</table>
		<?php 
			}
		?>
	</div>

</div>

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTablesSObyStatus').DataTable({
		"order": [[ 0, "desc" ]],
		paging: false,
        "columnDefs": [
            {
                "targets": [ 3 ],
                "visible": false,
                "searchable": true
            },
			{ "orderable": false, "targets": [4, 6] }
        ]
	});
});
</script>