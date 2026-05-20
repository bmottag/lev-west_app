<div id="page-wrapper">
	<br>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<a class="btn btn-danger btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-search"></i> <strong>Most Recent Construction Equipment Inspection Records</strong>
				</div>
				<div class="panel-body">
							
				<?php
					if($infoHeavy){
				?>
					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-danger">
								<strong>Attention: </strong>
								The unit inspection a comment and failed item. Please review it as soon as Posible.
							</div>
						</div>
						
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<strong>Attention: </strong>
								Today's unit inspection includes a failed item.
							</div>
						</div>
					</div>
				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class='text-center'>Date & Time</th>
								<th class='text-center'>Download</th>
								<th class='text-center'>Driver Name</th>
								<th class='text-center'>Vehicle Make</th>
								<th class='text-center'>Vehicle Model</th>
								<th class='text-center'>Unit Number</th>
								<th class='text-center'>VIN Number</th>
								<th class='text-center'>Description</th>
								<th class='text-center'>Comments</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($infoHeavy as $lista):
$class = "";

//si hay errores se coloca en amarillo
if(
['belt'] == 0
 || ['hydrolic'] == 0
 || ['oil_level'] == 0
 || ['coolant_level'] == 0
 || ['coolant_leaks'] == 0
 || ['working_lamps'] == 0
 || ['beacon_lights'] == 0
 || ['horn'] == 0
 || ['windows'] == 0
 || ['clean_exterior'] == 0
 || ['clean_interior'] == 0
 || ['boom_grease'] == 0
 || ['bucket'] == 0
 || ['blades'] == 0
 || ['cutting_edges'] == 0
 || ['tracks'] == 0
 || ['heater'] == 0
 || ['fire_extinguisher'] == 0
 || ['first_aid'] == 0
 || ['spill_kit'] == 0
 || ['tire_presurre'] == 0
 || ['turn_signals'] == 0
 || ['rims'] == 0
 || ['emergency_brake'] == 0
 || ['operator_seat'] == 0
 || ['gauges'] == 0
 || ['seatbelt'] == 0
 || ['wipers'] == 0
 || ['backup_beeper'] == 0
 || ['door'] == 0
 || ['decals'] == 0
 || ['table_excavator'] == 0
 || ['bucket_pins'] == 0
 || ['blade_pins'] == 0
 || ['front_axle'] == 0
 || ['rear_axle'] == 0
 || ['table_dozer'] == 0
 || ['pivin_points'] == 0
 || ['bucket_pins_skit'] == 0
 || ['side_arms'] == 0
 || ['rubber_trucks'] == 0
 || ['rollers'] == 0
 || ['thamper'] == 0
 || ['drill'] == 0
 || ['transmission'] == 0
 || ['ripper'] == 0
){
	$class = "warning";
}

//si hay comentarios se coloca en rojo
if($lista['comments'] != ''){
	$class = "danger";
}
							
								echo "<tr class='" . $class . "'>";
								echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['date_issue'] . "</p></td>";
								echo "<td class='text-center'>";
						?>
<a href='<?php echo base_url('report/generaInsectionHeavyPDF/x/x/x/x/' . $lista['id_inspection_heavy'] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php
								echo "</td>";
								echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
								echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
								echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
								echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
								echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['vin_number'] . "</p></td>";
								echo "<td ><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
								echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
								echo "</tr>";
							endforeach;
						?>
						</tbody>
					</table>
				<?php } ?>

				</div>

					
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->

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