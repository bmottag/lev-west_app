<div class="panel panel-primary">
	<div class="panel-heading"> 
		<i class="fa fa-car fa-fw"></i> <strong>EQUIPMENT LIST - <?php echo $subTitle; ?></strong> 
	</div>
	<div class="panel-body small">
	
	<?php
		if($vehicleInfo){
	?>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataEquipmentList">
			<thead>
				<tr>
					<th class="text-center">Photo</th>
					<th class="text-center">Make</th>
					<th class="text-center">Model</th>
					<th class="text-center">Description</th>
					<th class="text-center">Unit Number</th>
					<th class="text-center">VIN Number</th>
					<th class="text-center">Hours/Kilometers</th>
					<th class="text-center">Actions</th>
				</tr>
			</thead>
			<tbody>							
			<?php
				foreach ($vehicleInfo as $data):
				
					echo "<tr>";
					echo "<td class='text-center'>";
						//si hay una foto la muestro
						if($data["photo"]){
						?>
							<img src="<?php echo base_url($data["photo"]); ?>" class="img-rounded" width="42" height="42" />
						<?php 
							} 
					echo "</td>";
					echo "<td>" . $data['make'] . "</td>";
					echo "<td>" . $data['model'] . "</td>";
					echo "<td>";
					echo $data['description'];
					if($data['so_blocked'] == 2){
						echo '<p class="text-danger"><i class="fa fa-flag fa-fw"></i><b> Blocked by SO</b></p>';
					}
					echo "</td>";
					echo "<td class='text-center'><p class='text-danger'><strong>" . $data['unit_number'] . "</strong></p></td>";
					echo "<td class='text-center'><p class='text-danger'><strong>" . $data['vin_number'] . "</strong></p></td>";									
					echo "<td class='text-right'><p><strong>" . number_format($data["hours"]) . "</strong></p></td>";
					echo "<td class='text-center'>";
				?>
					<a class="btn btn-primary btn-xs" onclick="loadEquipmentDetail( <?php echo $data['id_vehicle']; ?>, 'tab_service_order' )">
						Details <span class="glyphicon glyphicon-edit" aria-hidden="true">
					</a>
				<?php
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
	$('#dataEquipmentList').DataTable({
		responsive: true,
		"ordering": false,
		paging: false,
		"searching": true,
		"info": false
	});
});
</script>