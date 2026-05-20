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
							<a class="btn btn-success btn-xs" href=" <?php echo base_url().'report/searchByDateRange/specialInspection'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
                            <i class="fa fa-search fa-fw"></i> SPECIAL EQUIPMENT INSPECTION REPORT
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						
					<div class="row">							
						<div class="col-lg-6">
							<div class="alert alert-info">
								<strong>From Date: </strong><?php echo $from; ?> 
								<strong>To Date: </strong><?php echo $to; ?> 
	<?php if($infoWaterTruck){ ?>
								<br><strong>Download Water-Truck Report: </strong>
								
	<a href='<?php echo base_url('report/generaInsectionSpecialPDF/' . $employee . '/' . $vehicleId . '/' . $from . '/' . $to . '/watertruck'); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
					 
	<?php } ?>
	<?php if($infoHydrovac){ ?>
								<br><strong>Download Hydro-Vac Report: </strong>
								
	<a href='<?php echo base_url('report/generaInsectionSpecialPDF/' . $employee . '/' . $vehicleId . '/' . $from . '/' . $to . '/hydrovac'); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
					 
	<?php } ?>
	<?php if($infoSweeper){ ?>
								<br><strong>Download Sweeper Report: </strong>
								
	<a href='<?php echo base_url('report/generaInsectionSpecialPDF/' . $employee . '/' . $vehicleId . '/' . $from . '/' . $to . '/sweeper'); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
					 
	<?php } ?>
	<?php if($infoGenerator){ ?>
								<br><strong>Download Generator Report: </strong>
								
	<a href='<?php echo base_url('report/generaInsectionSpecialPDF/' . $employee . '/' . $vehicleId . '/' . $from . '/' . $to . '/generator'); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
					 
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
							if(!$infoWaterTruck && !$infoSweeper && !$infoHydrovac && !$infoGenerator){
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
										<th class='text-center'>Employee</th>
										<th class='text-center'>Type</th>
										<th class='text-center'>Make</th>
										<th class='text-center'>Model</th>
										<th class='text-center'>Unit Number</th>
										<th class='text-center'>Description</th>
										<th class='text-center'>Comments</th>
                                    </tr>
                                </thead>
                                <tbody>							
								<?php
/**
 * WATER-TRUCK
 */
								if($infoWaterTruck)
								{
									foreach ($infoWaterTruck as $lista):
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
 ['belt'] == 0
 || ['power_steering'] == 0
 || ['oil_level'] == 0
 || ['coolant_level'] == 0
 || ['coolant_leaks'] == 0
 || ['head_lamps'] == 0
 || ['hazard_lights'] == 0
 || ['clearance_lights'] == 0
 || ['tail_lights'] == 0
 || ['work_lights'] == 0
 || ['turn_signals'] == 0
 || ['beacon_lights'] == 0
 || ['tires'] == 0
 || ['mirrors'] == 0
 || ['clean_exterior'] == 0
 || ['wipers'] == 0
 || ['backup_beeper'] == 0
 || ['door'] == 0
 || ['decals'] == 0
 || ['sprinkelrs'] == 0
 || ['stering_axle'] == 0
 || ['drives_axles'] == 0
 || ['front_drive'] == 0
 || ['back_drive'] == 0
 || ['water_pump'] == 0
 || ['brake'] == 0
 || ['emergency_brake'] == 0
 || ['gauges'] == 0
 || ['horn'] == 0
 || ['seatbelt'] == 0
 || ['seat'] == 0
 || ['insurance'] == 0
 || ['registration'] == 0
 || ['clean_interior'] == 0
 || ['fire_extinguisher'] == 0
 || ['first_aid'] == 0
 || ['emergency_kit'] == 0
 || ['spill_kit'] == 0
 || ['heater'] == 0
 || ['steering_wheel'] == 0
 || ['suspension_system'] == 0
 || ['air_brake'] == 0
 || ['fuel_system'] == 0
){
	$class = "warning";
}

//si hay comentarios se coloca en rojo
if($lista['comments'] != ''){
	$class = "danger";
}
							
											echo "<tr class='" . $class . "'>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['date_issue'] . "<br>";
						?>
<a href='<?php echo base_url('report/generaInsectionSpecialPDF/x/x/x/x/watertruck/' . $lista['id_inspection_watertruck'] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php
											echo "</p></td>";
											echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $type1 . ' - ' . $lista['type_2'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
											echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
											echo "</tr>";
									endforeach;
								}
/**
 * HYDROVAC
 */
								if($infoHydrovac)
								{
									foreach ($infoHydrovac as $lista):
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
 ['belt'] == 0
 || ['power_steering'] == 0
 || ['oil_level'] == 0
 || ['coolant_level'] == 0
 || ['coolant_leaks'] == 0
 || ['head_lamps'] == 0
 || ['hazard_lights'] == 0
 || ['clearance_lights'] == 0
 || ['tail_lights'] == 0
 || ['work_lights'] == 0
 || ['turn_signals'] == 0
 || ['beacon_lights'] == 0
 || ['tires'] == 0
 || ['windows'] == 0
 || ['clean_exterior'] == 0
 || ['wipers'] == 0
 || ['backup_beeper'] == 0
 || ['door'] == 0
 || ['decals'] == 0
 || ['stering_wheels'] == 0
 || ['drives'] == 0
 || ['front_drive'] == 0
 || ['middle_drive'] == 0
 || ['back_drive'] == 0
 || ['transfer'] == 0
 || ['tail_gate'] == 0
 || ['boom'] == 0
 || ['lock_bar'] == 0
 || ['brake'] == 0
 || ['emergency_brake'] == 0
 || ['gauges'] == 0
 || ['horn'] == 0
 || ['seatbelt'] == 0
 || ['seat'] == 0
 || ['insurance'] == 0
 || ['registration'] == 0
 || ['clean_interior'] == 0
 || ['fire_extinguisher'] == 0
 || ['first_aid'] == 0
 || ['emergency_kit'] == 0
 || ['spill_kit'] == 0
 || ['cartige'] == 0
 || ['pump'] == 0
 || ['wash_hose'] == 0
 || ['pressure_hose'] == 0
 || ['pump_oil'] == 0
 || ['hydraulic_oil'] == 0
 || ['gear_case'] == 0
 || ['hydraulic'] == 0
 || ['control'] == 0
 || ['panel'] == 0
 || ['foam'] == 0
 || ['heater'] == 0
 || ['steering_wheel'] == 0
 || ['suspension_system'] == 0
 || ['air_brake'] == 0
 || ['fuel_system'] == 0
){
	$class = "warning";
}

//si hay comentarios se coloca en rojo
if($lista['comments'] != ''){
	$class = "danger";
}
							
											echo "<tr class='" . $class . "'>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['date_issue'] . "<br>";
						?>
<a href='<?php echo base_url('report/generaInsectionSpecialPDF/x/x/x/x/hydrovac/' . $lista['id_inspection_hydrovac'] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php
											echo "</p></td>";
											echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $type1 . ' - ' . $lista['type_2'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
											echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
											echo "</tr>";
									endforeach;
								}
/**
 * SWEEPER
 */
								if($infoSweeper)
								{
									foreach ($infoSweeper as $lista):
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
['belt'] == 0
 || ['power_steering'] == 0
 || ['oil_level'] == 0
 || ['coolant_level'] == 0
 || ['coolant_leaks'] == 0
 || ['hydraulic'] == 0
 || ['belt_sweeper'] == 0
 || ['oil_level_sweeper'] == 0
 || ['coolant_level_sweeper'] == 0
 || ['coolant_leaks_sweeper'] == 0
 || ['head_lamps'] == 0
 || ['hazard_lights'] == 0
 || ['clearance_lights'] == 0
 || ['tail_lights'] == 0
 || ['work_lights'] == 0
 || ['turn_signals'] == 0
 || ['beacon_lights'] == 0
 || ['tires'] == 0
 || ['windows'] == 0
 || ['clean_exterior'] == 0
 || ['wipers'] == 0
 || ['backup_beeper'] == 0
 || ['door'] == 0
 || ['decals'] == 0
 || ['stering_wheels'] == 0
 || ['drives'] == 0
 || ['front_drive'] == 0
 || ['elevator'] == 0
 || ['rotor'] == 0
 || ['mixture_box'] == 0
 || ['lf_rotor'] == 0
 || ['elevator_sweeper'] == 0
 || ['mixture_container'] == 0
 || ['broom'] == 0
 || ['right_broom'] == 0
 || ['left_broom'] == 0
 || ['sprinkerls'] == 0
 || ['water_tank'] == 0
 || ['hose'] == 0
 || ['cam'] == 0
 || ['brake'] == 0
 || ['emergency_brake'] == 0
 || ['gauges'] == 0
 || ['horn'] == 0
 || ['seatbelt'] == 0
 || ['seat'] == 0
 || ['insurance'] == 0
 || ['registration'] == 0
 || ['clean_interior'] == 0
 || ['fire_extinguisher'] == 0
 || ['first_aid'] == 0
 || ['emergency_kit'] == 0
 || ['spill_kit'] == 0
){
	$class = "warning";
}

//si hay comentarios se coloca en rojo
if($lista['comments'] != ''){
	$class = "danger";
}
							
											echo "<tr class='" . $class . "'>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['date_issue'] . "<br>";
						?>
<a href='<?php echo base_url('report/generaInsectionSpecialPDF/x/x/x/x/sweeper/' . $lista['id_inspection_sweeper'] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php
											echo "</p></td>";
											echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $type1 . ' - ' . $lista['type_2'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
											echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
											echo "</tr>";
									endforeach;
								}
/**
 * GENERATOR
 */
								if($infoGenerator)
								{
									foreach ($infoGenerator as $lista):
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
['belt'] == 0
 || ['fuel_filter'] == 0
 || ['oil_level'] == 0
 || ['coolant_level'] == 0
 || ['coolant_leaks'] == 0
 || ['turn_signal'] == 0
 || ['hazard_lights'] == 0
 || ['tail_lights'] == 0
 || ['flood_lights'] == 0
 || ['boom'] == 0
 || ['gears'] == 0
 || ['gauges'] == 0
 || ['pulley'] == 0
 || ['electrical'] == 0
 || ['brackers'] == 0
 || ['tires'] == 0
 || ['clean_exterior'] == 0
 || ['decals'] == 0
){
	$class = "warning";
}

//si hay comentarios se coloca en rojo
if($lista['comments'] != ''){
	$class = "danger";
}
							
											echo "<tr class='" . $class . "'>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['date_issue'] . "<br>";
						?>
<a href='<?php echo base_url('report/generaInsectionSpecialPDF/x/x/x/x/generator/' . $lista['id_inspection_generator'] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php
											echo "</p></td>";
											echo "<td><p class='text-" . $class . "'>" . $lista['name'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $type1 . ' - ' . $lista['type_2'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['make'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['model'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['unit_number'] . "</p></td>";
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['description'] . "</p></td>";
											echo "<td><p class='text-" . $class . "'>" . $lista['comments'] . "</p></td>";
											echo "</tr>";
									endforeach;
								}
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