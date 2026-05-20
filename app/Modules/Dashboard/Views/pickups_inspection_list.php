<div id="page-wrapper">
	<br>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					<a class="btn btn-success btn-xs" href=" <?php echo base_url($dashboardURL); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-search"></i> <strong>Most Recent P/u's & Trucks Reports</strong>
				</div>
				<div class="panel-body">
											
				<?php
					if($infoDaily){
				?>
					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-danger">
								<strong>Attention: </strong>
								Heads up! 🚨 The unit inspection has a comment and a failed item—please take a look ASAP.
							</div>
						</div>
						
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<strong>Attention: </strong>
								Uh-oh! 🚧 Today’s unit inspection includes a failed item.
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
								<th class='text-center'>Description</th>
								<th class='text-center'>Comments</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($infoDaily as $lista):
							
$class = "";
								
//si hay errores se coloca en amarillo
if(
$lista['belt'] == 0
 || $lista['power_steering'] == 0
 || $lista['oil_level'] == 0
 || $lista['coolant_level'] == 0
 || $lista['water_leaks'] == 0
 || $lista['nuts'] == 0
 || $lista['head_lamps'] == 0
 || $lista['hazard_lights'] == 0
 || $lista['clearance_lights'] == 0
 || $lista['bake_lights'] == 0
 || $lista['work_lights'] == 0
 || $lista['glass'] == 0
 || $lista['clean_exterior'] == 0
 || $lista['proper_decals'] == 0
 || $lista['brake_pedal'] == 0
 || $lista['emergency_brake'] == 0
 || $lista['backup_beeper'] == 0
 || $lista['beacon_light'] == 0
 || $lista['gauges'] == 0
 || $lista['horn'] == 0
 || $lista['hoist'] == 0
 || $lista['passenger_door'] == 0
 || $lista['seatbelts'] == 0
 || $lista['fire_extinguisher'] == 0
 || $lista['emergency_reflectors'] == 0
 || $lista['first_aid'] == 0
 || $lista['wipers'] == 0
 || $lista['drives_axle'] == 0
 || $lista['grease_front'] == 0
 || $lista['grease_end'] == 0
 || $lista['spill_kit'] == 0
 || $lista['grease'] == 0
 || $lista['steering_axle'] == 0
 || $lista['turn_signals'] == 0
 || $lista['clean_interior'] == 0
 || $lista['insurance'] == 0
 || $lista['driver_seat'] == 0
 || $lista['registration'] == 0){
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
<a href='<?php echo base_url('report/generaInsectionDailyPDF/x/x/x/x/x/' . $lista['id_inspection_daily'] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php
								echo "</td>";
								echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
								echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
								echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
								echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
								echo "<td><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
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