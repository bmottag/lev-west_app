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
							<a class="btn btn-success btn-xs" href=" <?php echo base_url().'report/searchByDateRange/heavyInspection'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
                            <i class="fa fa-search fa-fw"></i> CONTRUCTION EQUIPMENT INSPECTION REPORT
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
								
	<a href='<?php echo base_url('report/generaInsectionHeavyPDF/' . $employee . '/' . $vehicleId . '/' . $from . '/' . $to ); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
					 
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
											echo "<td class='text-center'><p class='text-" . $class . "'>" . $lista['date_issue'] . "<br>";
						?>
<a href='<?php echo base_url('report/generaInsectionHeavyPDF/x/x/x/x/' . $lista['id_inspection_heavy'] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
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