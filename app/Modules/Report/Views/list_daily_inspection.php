        <div id="page-wrapper">

			<br>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4 class="list-group-item-heading">
								<i class="fa fa-bar-chart-o fa-fw"></i> REPORT CENTER
							</h4>
						</div>
					</div>
				</div>
				<!-- /.col-lg-12 -->				
			</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<a class="btn btn-success btn-xs" href=" <?php echo base_url().'report/searchByDateRange/dailyInspection'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
                            <i class="fa fa-search fa-fw"></i> PICKUPS & TRUCKS INSPECTION REPORT
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						
					<div class="row">							
						<div class="col-lg-6">
							<div class="alert alert-info">
								<strong>From Date: </strong><?php echo $from; ?> 
								<strong>To Date: </strong><?php echo $to; ?> 
	<?php if($info){ ?>
								<br><strong>Download to: </strong>
								
	<a href='<?php echo base_url('report/generaInsectionDailyPDF/' . $employee . '/' . $vehicleId . '/' . $trailerId . '/' . $from . '/' . $to ); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
					 
	<?php } ?>
							</div>
						</div>
							
						<div class="col-lg-6">
							<div class="alert alert-danger">
								If the record is red, it is because the inspection has comments or has some fails.
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="alert alert-warning">
								If the record is yellow, it is because the inspection has some fails.
							</div>
						</div>
					</div>
							
						<?php
							if(!$info){
						?>
                            <div class="alert alert-danger">
                                No data was found matching your criteria. 
                            </div>
						<?php
							}else{
						?>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                                <thead>
                                    <tr>
                                        <th class='text-center'>Date of Issue</th>
										<th class='text-center'>Driver Name</th>
										<th class='text-center'>Type</th>
										<th class='text-center'>Make</th>
										<th class='text-center'>Model</th>
										<th class='text-center'>Unit Number</th>
										<th class='text-center'>Description</th>
										
										<th class='text-center'>Comments</th>
										
										<th class='text-center'>Belts/Hoses</th>
										<th class='text-center'>Power Steering Fluid</th>
										<th class='text-center'>Oil Level</th>
										<th class='text-center'>Coolant Level</th>
										<th class='text-center'>Coolant/Oil Leaks</th>
										
										
										<th class='text-center'>Head Lamps</th>
										<th class='text-center'>Hazard Lights</th>
										<th class='text-center'>Tail Lights</th>
										<th class='text-center'>Work Lights</th>
										<th class='text-center'>Turn Signals</th>
										<th class='text-center'>Beacon Light</th>
										<th class='text-center'>Clearance Lights</th>
										
										
										<th class='text-center'>Brake Pedal</th>
										<th class='text-center'>Emergency Brake</th>
										<th class='text-center'>Gauges: Volt/Fuel/Temp/Oil</th>
										<th class='text-center'>Electrical & Air Horn</th>
										<th class='text-center'>Seatbelts</th>
										<th class='text-center'>Driver & Passenger seat </th>
										<th class='text-center'>Insurance Information</th>
										<th class='text-center'>Registration</th>
										<th class='text-center'>Clean Interior</th>
										

										<th class='text-center'>Tires/Lug Nuts/Pressure</th>
										<th class='text-center'>Glass (All) & Mirror</th>
										<th class='text-center'>Clean Exterior</th>
										<th class='text-center'>Wipers/Washers</th>
										<th class='text-center'>Backup Beeper</th>
										<th class='text-center'>Driver and Passenger door </th>
										<th class='text-center'>Decals</th>


										<th class='text-center'>Fire Extinguisher</th>
										<th class='text-center'>First Aid</th>
										<th class='text-center'>Emergency kit</th>
										<th class='text-center'>Spill Kit</th>
										
										
										<th class='text-center'>Steering Axle</th>
										<th class='text-center'>Drives Axles</th>
										<th class='text-center'>Front drive shaft</th>
										<th class='text-center'>Back drive shaft</th>
										<th class='text-center'>Grease 5th Wheel</th>
										<th class='text-center'>Box hoist & hinge</th>
										
                                    </tr>
                                </thead>
                                <tbody>							
								<?php
									$total = 0;
									foreach ($info as $lista):
													
											switch ($lista['type_level_1']) {
												case 1:
													$type1 = 'Fleet';
													break;
												case 2:
													$type1 = 'Rental';
													break;
												case 99:
													$type1 = 'Other';
													break;
											}
											
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
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['date_issue'] . "<br>";
						?>
<a href='<?php echo base_url('report/generaInsectionDailyPDF/x/x/x/x/x/' . $lista['id_inspection_daily'] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php
											echo "</p></td>";
											echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
											
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $type1 . ' - ' . $lista['type_2'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
											
											echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
											
											echo "<td class='text-center'>"; 
											switch ($lista['belt']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td >";
											switch ($lista['power_steering']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											
											echo "<td class='text-center'>";
											switch ($lista['oil_level']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['coolant_level']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['water_leaks']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['head_lamps']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											
											echo "<td class='text-center'>"; 
											switch ($lista['hazard_lights']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['bake_lights']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['work_lights']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>"; 
											switch ($lista['turn_signals']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>"; 
											switch ($lista['beacon_light']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['clearance_lights']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['brake_pedal']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['emergency_brake']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['gauges']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['horn']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['seatbelts']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['driver_seat']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";

											echo "<td class='text-center'>";
											switch ($lista['insurance']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";

											echo "<td class='text-center'>";
											switch ($lista['registration']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['clean_interior']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											
											echo "<td class='text-center'>";
											switch ($lista['nuts']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											
											echo "<td class='text-center'>";
											switch ($lista['glass']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['clean_exterior']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";	

											echo "<td class='text-center'>";
											switch ($lista['wipers']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['backup_beeper']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";	
											echo "<td class='text-center'>";
											switch ($lista['passenger_door']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['proper_decals']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											
											echo "<td class='text-center'>"; 
											switch ($lista['fire_extinguisher']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['first_aid']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['emergency_reflectors']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											echo "<td class='text-center'>";
											switch ($lista['spill_kit']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											
											echo "<td class='text-center'>";
											switch ($lista['steering_axle']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['drives_axle']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>"; 
											switch ($lista['grease_front']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['grease_end']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['grease']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";											
											echo "<td class='text-center'>";
											switch ($lista['hoist']) {
												case 0:
													echo "Fail";
													break;
												case 1:
													echo "Pass";
													break;
												case 99:
													echo "N/A";
													break;
											}
											echo "</td>";
											
											echo "</tr>";
									endforeach;
								?>
                                </tbody>
                            </table>
						<?php }	?>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
			

        </div>
        <!-- /#page-wrapper -->

    <!-- Tables -->
    <script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
            responsive: true,
			"pageLength": 100
        });
    });
    </script>