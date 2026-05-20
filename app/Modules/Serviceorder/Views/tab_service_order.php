<div class="panel panel-violeta">
	<div class="panel-heading"> 
		<i class="fa fa-briefcase"></i> <strong>Service Order</strong>
	</div>
	<div class="panel-body small">

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
		
        <?php 										
            if(!$information){ 
                echo '<div class="col-lg-12">
                        <p class="text-danger"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> There are no records in the system.</p>
                    </div>';
            } else {
        ?>
		<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
			<thead>
				<tr>
					<th>S.O. #</th>
					<th>Description</th>
					<th>Maintenance Type</th>
					<th>Assigned To</th>
					<th>Request Date</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>							
			<?php
				foreach ($information as $lista):
						$maintenanceType = $lista['maintenace_type']=='corrective' ? "Corrective" : "Preventive";
						echo "<tr>";
						echo "<td class='text-center'>";
						echo $lista['id_service_order'];
			?>
						<p class="text-<?php echo $lista['priority_style']; ?>"><i class="fa <?php echo $lista['priority_icon']; ?> fa-fw"></i><?php echo $lista['priority_name']; ?> </p>

						<a class="btn btn-violeta btn-xs" onclick="loadEquipmentDetail( <?php echo $lista['fk_id_equipment']; ?>, 'tab_service_order_detail', <?php echo $lista['id_service_order']; ?>)" title="View">
							<i class="fa fa-eye"></i> View
						</a>
			<?php			
						echo "</td>";
						echo "<td>" . $lista['main_description'] . "</td>";
						echo "<td><p class='text-danger'><b>" . $maintenanceType  . "</b></p>";
						echo "</td>";
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
	$('#dataTables').DataTable({
		responsive: true,
			"ordering": false,
			paging: false,
		"searching": false,
		"info": false
	});
});
</script>