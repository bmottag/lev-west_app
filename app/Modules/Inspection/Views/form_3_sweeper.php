<script type="text/javascript" src="<?php echo base_url("assets/js/validate/inspection/sweeper_inspection.js?v=1.0.0"); ?>"></script>

<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var idRecord = $('#hddId').val();
			var table = "inspection_sweeper";
			var backURL = "inspection/add_sweeper_inspection/";
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'safety/cargar-modal-employee-verification',
				data: {"idRecord": idRecord, "table": table, "backURL": backURL, 'information': oID },
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
});
</script>

<?php
/**
 * If it is an ADMIN user, show date 
 * @author BMOTTAG
 * @since  11/5/2017
 */
$userRol = session()->get('rol');
if($userRol==ID_ROL_SUPER_ADMIN){
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php } ?>

<div id="page-wrapper">
	<br>
	
<form  name="form" id="form" class="form-horizontal" method="post" >
	<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_inspection_sweeper"]:""; ?>"/>
	<input type="hidden" id="hddIdVehicle" name="hddIdVehicle" value="<?php echo $vehicleInfo[0]["id_vehicle"]; ?>"/>
	<input type="hidden" id="oilChange" name="oilChange" value="<?php echo $vehicleInfo[0]["oil_change"]; ?>"/>
	<input type="hidden" id="oilChange2" name="oilChange2" value="<?php echo $vehicleInfo[0]["oil_change_2"]; ?>"/>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<i class="fa fa-search"></i> <strong> STREET SWEEPER INSPECTION - III</strong><!-- #3 -->
				</div>
				<div class="panel-body">

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

					<?php if($vehicleInfo[0]["photo"]){ ?>
						<div class="form-group">
							<div class="row" align="center">
								<img src="<?php echo base_url($vehicleInfo[0]["photo"]); ?>" class="img-rounded" alt="Vehicle Photo" />
							</div>
						</div>
					<?php } ?>
				
					<strong>Description: </strong><?php echo $vehicleInfo[0]['description']; ?><br>
					<strong>Unit Number: </strong><?php echo $vehicleInfo[0]['unit_number']; ?><br>
					<strong>VIN Number: </strong><?php echo $vehicleInfo[0]['vin_number']; ?><br>
					<p class='text-danger'>
						<strong>Truck Engine Hours: </strong><?php echo number_format($vehicleInfo[0]["hours"]); ?> hours
						<br><strong>Sweeper Engine Hours: </strong><?php echo number_format($vehicleInfo[0]["hours_2"]); ?> hours
					</p>
					
<!-- INICIO Firma del conductor -->					
<?php if($information){ 

		//si ya esta la firma entonces se muestra mensaje que ya termino el reporte
		if($information[0]["signature"]){ 
?>
				<div class="col-lg-12">	
					<div class="alert alert-success ">
						<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						You have finished your Inspection Report.
					</div>
				</div>
<?php   }  ?>
				<div class="col-lg-6 col-md-offset-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-edit fa-fw"></i> Driver Signature
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						
							<div class="form-group">
								<div class="row" align="center">
									<div style="width:70%;" align="center">
										 
<?php 								
	$class = "btn-primary";						
	if($information[0]["signature"]){ 
		$class = "btn-default";
?>
		
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" >
	<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Signature
</button>

<div id="myModal" class="modal fade" role="dialog">  
	<div class="modal-dialog">
		<div class="modal-content">      
			<div class="modal-header">        
				<button type="button" class="close" data-dismiss="modal">×</button>        
				<h4 class="modal-title">Daily Inspection Signature</h4>      </div>      
			<div class="modal-body text-center"><img src="<?php echo base_url($information[0]["signature"]); ?>" class="img-rounded" alt="Management/Safety Advisor Signature" width="304" height="236" />   </div>      
			<div class="modal-footer">        
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
			</div>  
		</div>  
	</div>
</div>

<?php } ?>

										<button type="button" class="btn btn-outline btn-primary" data-toggle="modal" data-target="#modal" id="<?php echo "inspection-" . $information[0]['fk_id_user'] . "-x";; ?>" title="System Signature" >
											<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Use User Profile Signature
										</button>

									</div>
								</div>
							</div>
					
						</div>
					</div>
				</div>
<?php } ?>
<!-- FIN Firma del conductor -->

				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>HOURS</strong>
				</div>
				<div class="panel-body">
				
					<div class="form-group">									
						<label class="col-sm-4 control-label" for="hours">Truck Engine Current Hours <small class="text-primary">(Horas actuales)</small></label>
						<div class="col-sm-5">
							<input type="text" id="hours" name="hours" class="form-control" value="<?php if($information){ echo $vehicleInfo[0]["hours"]; }?>" placeholder="Hours" required >
						</div>
					</div>
					
					<div class="form-group">									
						<label class="col-sm-4 control-label" for="hours2">Sweeper Engine Current Hours <small class="text-primary">(Horas actuales)</small></label>
						<div class="col-sm-5">
							<input type="text" id="hours2" name="hours2" class="form-control" value="<?php if($information){ echo $vehicleInfo[0]["hours_2"]; }?>" placeholder="Hours" required >
						</div>
					</div>
					
<?php
/**
 * If it is an ADMIN user, show date 
 * @author BMOTTAG
 * @since  11/5/2017
 */
if($userRol==99){
?>				
<script>
	$( function() {
		$( "#date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
</script>
					<div class="form-group">									
						<label class="col-sm-4 control-label" for="date">Date of Issue</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="date" name="date" value="<?php echo $information?$information[0]["date_issue"]:""; ?>" placeholder="Date of Issue" />
						</div>
					</div>
<?php } ?>
					
				</div>
			</div>
		</div>
	</div>
		
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>ENGINE VEHICLE</strong>
				</div>
				<div class="panel-body">
									
					<div class="form-group">
						<label class="col-sm-4 control-label" for="belt">Belts/Hoses <small class="text-primary">(Correas/ Mangueras)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="belt" id="belt1" value=0 <?php if($information && $information[0]["belt"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="belt" id="belt2" value=1 <?php if($information && $information[0]["belt"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="belt" id="belt3" value=99 <?php if($information && $information[0]["belt"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="powerSteering">Power steering fluid <small class="text-primary">(Líquido de dirección asistida)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="powerSteering" id="powerSteering1" value=0 <?php if($information && $information[0]["power_steering"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="powerSteering" id="powerSteering2" value=1 <?php if($information && $information[0]["power_steering"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="powerSteering" id="powerSteering3" value=99 <?php if($information && $information[0]["power_steering"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="oil">Oil Level <small class="text-primary">(Nivel de Aceite )</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="oil" id="oil1" value=0 <?php if($information && $information[0]["oil_level"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="oil" id="oil2" value=1 <?php if($information && $information[0]["oil_level"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="oil" id="oil3" value=99 <?php if($information && $information[0]["oil_level"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="coolantLevel">Coolant Level <small class="text-primary">(Nivel de Liquido Refrigerante )</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="coolantLevel" id="coolantLevel1" value=0 <?php if($information && $information[0]["coolant_level"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="coolantLevel" id="coolantLevel2" value=1 <?php if($information && $information[0]["coolant_level"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="coolantLevel" id="coolantLevel3" value=99 <?php if($information && $information[0]["coolant_level"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="coolantLeaks">Coolant/Oil Leaks <small class="text-primary">(Fugas de Refrigerante / Aceite)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="coolantLeaks" id="coolantLeaks1" value=0 <?php if($information && $information[0]["coolant_leaks"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="coolantLeaks" id="coolantLeaks2" value=1 <?php if($information && $information[0]["coolant_leaks"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="coolantLeaks" id="coolantLeaks3" value=99 <?php if($information && $information[0]["coolant_leaks"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="hydraulic">Hydraulic Fluids <small class="text-primary">(Aceite hidráulico)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="hydraulic" id="hydraulic1" value=0 <?php if($information && $information[0]["hydraulic"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="hydraulic" id="hydraulic2" value=1 <?php if($information && $information[0]["hydraulic"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="hydraulic" id="hydraulic3" value=99 <?php if($information && $information[0]["hydraulic"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="def">
							DEF Level
							<small class="text-primary">(Diesel Exhaust Fluid) </small>
						</label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="def" id="def1" value=25 <?php if ($information && $information[0]["def"] == 25) {
																						echo "checked";
																					}  ?>>25%
							</label>
							<label class="radio-inline">
								<input type="radio" name="def" id="def2" value=50 <?php if ($information && $information[0]["def"] == 50) {
																						echo "checked";
																					}  ?>>50%
							</label>
							<label class="radio-inline">
								<input type="radio" name="def" id="def3" value=100 <?php if ($information && $information[0]["def"] == 100) {
																						echo "checked";
																					}  ?>>100%
							</label>
							<label class="radio-inline">
								<input type="radio" name="def" id="def4" value=0 <?php if ($information && $information[0]["def"] == 0) {
																						echo "checked";
																					}  ?>>N/A
							</label>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>ENGINE SWEEPER</strong>
				</div>
				<div class="panel-body">
									
					<div class="form-group">
						<label class="col-sm-4 control-label" for="beltSweeper">Belts/Hoses <small class="text-primary">(Correas/ Mangueras)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="beltSweeper" id="beltSweeper1" value=0 <?php if($information && $information[0]["belt_sweeper"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="beltSweeper" id="beltSweeper2" value=1 <?php if($information && $information[0]["belt_sweeper"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="beltSweeper" id="beltSweeper3" value=99 <?php if($information && $information[0]["belt_sweeper"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="oilSweeper">Oil Level <small class="text-primary">(Nivel de Aceite )</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="oilSweeper" id="oilSweeper1" value=0 <?php if($information && $information[0]["oil_level_sweeper"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="oilSweeper" id="oilSweeper2" value=1 <?php if($information && $information[0]["oil_level_sweeper"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="oilSweeper" id="oilSweeper3" value=99 <?php if($information && $information[0]["oil_level_sweeper"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="coolantLevelSweeper">Coolant Level <small class="text-primary">(Nivel de Liquido Refrigerante )</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="coolantLevelSweeper" id="coolantLevelSweeper1" value=0 <?php if($information && $information[0]["coolant_level_sweeper"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="coolantLevelSweeper" id="coolantLevelSweeper2" value=1 <?php if($information && $information[0]["coolant_level_sweeper"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="coolantLevelSweeper" id="coolantLevelSweeper3" value=99 <?php if($information && $information[0]["coolant_level_sweeper"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="coolantLeaksSweeper">Coolant/Oil Leaks <small class="text-primary">(Fugas de Refrigerante / Aceite)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="coolantLeaksSweeper" id="coolantLeaksSweeper1" value=0 <?php if($information && $information[0]["coolant_leaks_sweeper"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="coolantLeaksSweeper" id="coolantLeaksSweeper2" value=1 <?php if($information && $information[0]["coolant_leaks_sweeper"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="coolantLeaksSweeper" id="coolantLeaksSweeper3" value=99 <?php if($information && $information[0]["coolant_leaks_sweeper"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>LIGHTS</strong>
				</div>
				<div class="panel-body">
				
					<div class="form-group">
						<label class="col-sm-4 control-label" for="headLamps">Head Lamps <small class="text-primary">(Luces delanteras)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="headLamps" id="headLamps1" value=0 <?php if($information && $information[0]["head_lamps"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="headLamps" id="headLamps2" value=1 <?php if($information && $information[0]["head_lamps"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="headLamps" id="headLamps3" value=99 <?php if($information && $information[0]["head_lamps"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="hazardLights">Hazard lights <small class="text-primary">(Luces intermitentes)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="hazardLights" id="hazardLights1" value=0 <?php if($information && $information[0]["hazard_lights"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="hazardLights" id="hazardLights2" value=1 <?php if($information && $information[0]["hazard_lights"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="hazardLights" id="hazardLights3" value=99 <?php if($information && $information[0]["hazard_lights"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="clearanceLights">Clearance lights <small class="text-primary">(Luces de posición)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="clearanceLights" id="clearanceLights1" value=0 <?php if($information && $information[0]["clearance_lights"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="clearanceLights" id="clearanceLights2" value=1 <?php if($information && $information[0]["clearance_lights"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="clearanceLights" id="clearanceLights3" value=99 <?php if($information && $information[0]["clearance_lights"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="tailLights">Tail Lights <small class="text-primary">(Luces traseras)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="tailLights" id="tailLights1" value=0 <?php if($information && $information[0]["tail_lights"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="tailLights" id="tailLights2" value=1 <?php if($information && $information[0]["tail_lights"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="tailLights" id="tailLights3" value=99 <?php if($information && $information[0]["tail_lights"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="workLights">Work Lights <small class="text-primary">(Luces principales)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="workLights" id="workLights1" value=0 <?php if($information && $information[0]["work_lights"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="workLights" id="workLights2" value=1 <?php if($information && $information[0]["work_lights"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="workLights" id="workingLamps3" value=99 <?php if($information && $information[0]["work_lights"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
									
					<div class="form-group">
						<label class="col-sm-4 control-label" for="turnSignals">Turn signal lights <small class="text-primary">(Direccionales)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="turnSignals" id="turnSignals1" value=0 <?php if($information && $information[0]["turn_signals"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="turnSignals" id="turnSignals2" value=1 <?php if($information && $information[0]["turn_signals"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="turnSignals" id="turnSignals3" value=99 <?php if($information && $information[0]["turn_signals"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="beaconLights">Beacon Light <small class="text-primary">(Licuadora)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="beaconLights" id="beaconLights1" value=0 <?php if($information && $information[0]["beacon_lights"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="beaconLights" id="beaconLights2" value=1 <?php if($information && $information[0]["beacon_lights"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="beaconLights" id="beaconLights3" value=99 <?php if($information && $information[0]["beacon_lights"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
		
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">						
					<strong>EXTERIOR</strong>
				</div>
				<div class="panel-body">
				
					<div class="form-group">
						<label class="col-sm-4 control-label" for="tires">Tires/Lug Nuts/Pressure <small class="text-primary">(Llantas/Tuercas/Presion)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="tires" id="tires1" value=0 <?php if($information && $information[0]["tires"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="tires" id="tires2" value=1 <?php if($information && $information[0]["tires"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="tires" id="tires3" value=99 <?php if($information && $information[0]["tires"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
									
					<div class="form-group">
						<label class="col-sm-4 control-label" for="windows">Glass (All) & Mirror(s) <small class="text-primary">(Ventanas y espejos)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="windows" id="windows1" value=0 <?php if($information && $information[0]["windows"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="windows" id="windows2" value=1 <?php if($information && $information[0]["windows"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="windows" id="windows3" value=99 <?php if($information && $information[0]["windows"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="cleanExterior">Clean Exterior <small class="text-primary">(Limpieza exterior)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="cleanExterior" id="cleanExterior1" value=0 <?php if($information && $information[0]["clean_exterior"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="cleanExterior" id="cleanExterior2" value=1 <?php if($information && $information[0]["clean_exterior"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="cleanExterior" id="cleanExterior3" value=99 <?php if($information && $information[0]["clean_exterior"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="wipers">Wipers / Washers <small class="text-primary">(Limpiaparabrisas)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="wipers" id="wipers1" value=0 <?php if($information && $information[0]["wipers"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="wipers" id="wipers2" value=1 <?php if($information && $information[0]["wipers"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="wipers" id="wipers3" value=99 <?php if($information && $information[0]["wipers"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="backupBeeper">Backup Beeper <small class="text-primary">(Alarma de reversa)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="backupBeeper" id="backupBeeper1" value=0 <?php if($information && $information[0]["backup_beeper"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="backupBeeper" id="backupBeeper2" value=1 <?php if($information && $information[0]["backup_beeper"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="backupBeeper" id="backupBeeper3" value=99 <?php if($information && $information[0]["backup_beeper"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="door">Driver and Passenger Door <small class="text-primary">(Puerta)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="door" id="door1" value=0 <?php if($information && $information[0]["door"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="door" id="door2" value=1 <?php if($information && $information[0]["door"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="door" id="door3" value=99 <?php if($information && $information[0]["door"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="decals">Decals <small class="text-primary">(Calcomanías)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="decals" id="decals1" value=0 <?php if($information && $information[0]["decals"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="decals" id="decals2" value=1 <?php if($information && $information[0]["decals"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="decals" id="decals3" value=99 <?php if($information && $information[0]["decals"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>GREASING</strong>
				</div>
				<div class="panel-body">

					<div class="form-group">
						<label class="col-sm-4 control-label" for="SteringWheels">Stering Wheels <small class="text-primary"></small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="SteringWheels" id="SteringWheels1" value=0 <?php if($information && $information[0]["stering_wheels"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="SteringWheels" id="SteringWheels2" value=1 <?php if($information && $information[0]["stering_wheels"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="SteringWheels" id="SteringWheels3" value=99 <?php if($information && $information[0]["stering_wheels"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="drives">Drives <small class="text-primary">(Ejes)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="drives" id="drives1" value=0 <?php if($information && $information[0]["drives"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="drives" id="drives2" value=1 <?php if($information && $information[0]["drives"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="drives" id="drives3" value=99 <?php if($information && $information[0]["drives"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="frontDrive">Front drive shaft <small class="text-primary">(Cardan delantero)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="frontDrive" id="frontDrive1" value=0 <?php if($information && $information[0]["front_drive"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="frontDrive" id="frontDrive2" value=1 <?php if($information && $information[0]["front_drive"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="frontDrive" id="frontDrive3" value=99 <?php if($information && $information[0]["front_drive"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="elevator">Elevator <small class="text-primary">(Elevador)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="elevator" id="elevator1" value=0 <?php if($information && $information[0]["elevator"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="elevator" id="elevator2" value=1 <?php if($information && $information[0]["elevator"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="elevator" id="elevator3" value=99 <?php if($information && $information[0]["elevator"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
				
					<div class="form-group">
						<label class="col-sm-4 control-label" for="rotor">Back Main Rotor <small class="text-primary">(Rotor principal trasero)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="rotor" id="rotor1" value=0 <?php if($information && $information[0]["rotor"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="rotor" id="rotor2" value=1 <?php if($information && $information[0]["rotor"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="rotor" id="rotor3" value=99 <?php if($information && $information[0]["rotor"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
						
					<div class="form-group">
						<label class="col-sm-4 control-label" for="mixtureBox">Scissors Mixture Box <small class="text-primary">(Caja de la mezcla)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="mixtureBox" id="mixtureBox1" value=0 <?php if($information && $information[0]["mixture_box"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="mixtureBox" id="mixtureBox2" value=1 <?php if($information && $information[0]["mixture_box"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="mixtureBox" id="mixtureBox3" value=99 <?php if($information && $information[0]["mixture_box"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="lfRotor">Left and Right Rotors <small class="text-primary">(Rotores izquierdo y derecho)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="lfRotor" id="lfRotor1" value=0 <?php if($information && $information[0]["lf_rotor"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="lfRotor" id="lfRotor2" value=1 <?php if($information && $information[0]["lf_rotor"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="lfRotor" id="lfRotor3" value=99 <?php if($information && $information[0]["lf_rotor"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>SWEEPER</strong>
				</div>
				<div class="panel-body">
									
					<div class="form-group">
						<label class="col-sm-4 control-label" for="elevatorSweeper">Elevator <small class="text-primary">(Elevador)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="elevatorSweeper" id="elevatorSweeper1" value=0 <?php if($information && $information[0]["elevator_sweeper"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="elevatorSweeper" id="elevatorSweeper2" value=1 <?php if($information && $information[0]["elevator_sweeper"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="elevatorSweeper" id="elevatorSweeper3" value=99 <?php if($information && $information[0]["elevator_sweeper"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="mixtureContainer">Mixture Container <small class="text-primary">(Contenedor de Mezcla)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="mixtureContainer" id="mixtureContainer1" value=0 <?php if($information && $information[0]["mixture_container"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="mixtureContainer" id="mixtureContainer2" value=1 <?php if($information && $information[0]["mixture_container"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="mixtureContainer" id="mixtureContainer3" value=99 <?php if($information && $information[0]["mixture_container"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="broom">Main broom <small class="text-primary">(Escoba principal)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="broom" id="broom1" value=0 <?php if($information && $information[0]["broom"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="broom" id="broom2" value=1 <?php if($information && $information[0]["broom"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="broom" id="broom3" value=99 <?php if($information && $information[0]["broom"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="rightBroom">Right broom <small class="text-primary">(Escoba derecha)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="rightBroom" id="rightBroom1" value=0 <?php if($information && $information[0]["right_broom"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="rightBroom" id="rightBroom2" value=1 <?php if($information && $information[0]["right_broom"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="rightBroom" id="rightBroom3" value=99 <?php if($information && $information[0]["right_broom"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
				
					<div class="form-group">
						<label class="col-sm-4 control-label" for="leftBroom">Left broom <small class="text-primary">(Escoba izquierda)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="leftBroom" id="leftBroom1" value=0 <?php if($information && $information[0]["left_broom"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="leftBroom" id="leftBroom2" value=1 <?php if($information && $information[0]["left_broom"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="leftBroom" id="leftBroom3" value=99 <?php if($information && $information[0]["left_broom"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="sprinkerls">Sprinkerls <small class="text-primary">(Rociadores)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="sprinkerls" id="sprinkerls1" value=0 <?php if($information && $information[0]["sprinkerls"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="sprinkerls" id="sprinkerls2" value=1 <?php if($information && $information[0]["sprinkerls"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="sprinkerls" id="sprinkerls3" value=99 <?php if($information && $information[0]["sprinkerls"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="waterTank">Water Tank <small class="text-primary">(Tanque de agua)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="waterTank" id="waterTank1" value=0 <?php if($information && $information[0]["water_tank"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="waterTank" id="waterTank2" value=1 <?php if($information && $information[0]["water_tank"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="waterTank" id="waterTank3" value=99 <?php if($information && $information[0]["water_tank"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="hose">Water Tank Hose <small class="text-primary">(Manguera del tanque de agua)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="hose" id="hose1" value=0 <?php if($information && $information[0]["hose"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="hose" id="hose2" value=1 <?php if($information && $information[0]["hose"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="hose" id="hose3" value=99 <?php if($information && $information[0]["hose"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="cam">Cam Viewer <small class="text-primary">(Cámara)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="cam" id="cam1" value=0 <?php if($information && $information[0]["cam"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="cam" id="cam2" value=1 <?php if($information && $information[0]["cam"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="cam" id="cam3" value=99 <?php if($information && $information[0]["cam"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">						
					<strong>SERVICE</strong>
				</div>
				<div class="panel-body">

					<div class="form-group">
						<label class="col-sm-4 control-label" for="brake">Brake Pedal <small class="text-primary">(Pedal de freno)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="brake" id="brake1" value=0 <?php if($information && $information[0]["brake"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="brake" id="brake2" value=1 <?php if($information && $information[0]["brake"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="brake" id="brake3" value=99 <?php if($information && $information[0]["brake"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="emergencyBrake">Emergency Brake <small class="text-primary">(Freno de emergencia)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="emergencyBrake" id="emergencyBrake1" value=0 <?php if($information && $information[0]["emergency_brake"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="emergencyBrake" id="emergencyBrake2" value=1 <?php if($information && $information[0]["emergency_brake"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="emergencyBrake" id="emergencyBrake3" value=99 <?php if($information && $information[0]["emergency_brake"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="gauges">Gauges: Volt / Fuel / Temp / Oil <small class="text-primary">(Medidores)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="gauges" id="gauges1" value=0 <?php if($information && $information[0]["gauges"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="gauges" id="gauges2" value=1 <?php if($information && $information[0]["gauges"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="gauges" id="gauges3" value=99 <?php if($information && $information[0]["gauges"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="horn">Electrical & Air Horn <small class="text-primary">(Pito)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="horn" id="horn1" value=0 <?php if($information && $information[0]["horn"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="horn" id="horn2" value=1 <?php if($information && $information[0]["horn"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="horn" id="horn3" value=99 <?php if($information && $information[0]["horn"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="seatbelt">Seatbelts <small class="text-primary">(Cinturón de seguridad)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="seatbelt" id="seatbelt1" value=0 <?php if($information && $information[0]["seatbelt"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="seatbelt" id="seatbelt2" value=1 <?php if($information && $information[0]["seatbelt"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="seatbelt" id="seatbelt3" value=99 <?php if($information && $information[0]["seatbelt"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="seat">Driver & Passenger Seat <small class="text-primary">(Sillas)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="seat" id="seat1" value=0 <?php if($information && $information[0]["seat"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="seat" id="seat2" value=1 <?php if($information && $information[0]["seat"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="seat" id="seat3" value=99 <?php if($information && $information[0]["seat"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="insurance">Insurance information <small class="text-primary">(Información del seguro)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="insurance" id="insurance1" value=0 <?php if($information && $information[0]["insurance"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="insurance" id="insurance2" value=1 <?php if($information && $information[0]["insurance"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="insurance" id="insurance3" value=99 <?php if($information && $information[0]["insurance"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="registration">Registration <small class="text-primary">(Registro)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="registration" id="registration1" value=0 <?php if($information && $information[0]["registration"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="registration" id="registration2" value=1 <?php if($information && $information[0]["registration"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="registration" id="registration3" value=99 <?php if($information && $information[0]["registration"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="cleanInterior">Clean Interior <small class="text-primary">(Limpieza interior)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="cleanInterior" id="cleanInterior1" value=0 <?php if($information && $information[0]["clean_interior"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="cleanInterior" id="cleanInterior2" value=1 <?php if($information && $information[0]["clean_interior"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="cleanInterior" id="cleanInterior3" value=99 <?php if($information && $information[0]["clean_interior"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>SAFETY</strong>
				</div>
				<div class="panel-body">
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="fire">Fire Extinguisher <small class="text-primary">(Extintor de incendio)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="fire" id="fire1" value=0 <?php if($information && $information[0]["fire_extinguisher"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="fire" id="fire2" value=1 <?php if($information && $information[0]["fire_extinguisher"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="fire" id="fire3" value=99 <?php if($information && $information[0]["fire_extinguisher"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="aid">First Aid <small class="text-primary">(Kit de primeros auxilios)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="aid" id="aid1" value=0 <?php if($information && $information[0]["first_aid"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="aid" id="aid2" value=1 <?php if($information && $information[0]["first_aid"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="aid" id="aid3" value=99 <?php if($information && $information[0]["first_aid"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="emergencyKit">Emergency kit <small class="text-primary">(Kit de emergencia)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="emergencyKit" id="emergencyKit1" value=0 <?php if($information && $information[0]["emergency_kit"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="emergencyKit" id="emergencyKit2" value=1 <?php if($information && $information[0]["emergency_kit"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="emergencyKit" id="emergencyKit3" value=99 <?php if($information && $information[0]["emergency_kit"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="spillKit">Spill Kit <small class="text-primary">(Kit de emergencia para derrames)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="spillKit" id="spillKit1" value=0 <?php if($information && $information[0]["spill_kit"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="spillKit" id="spillKit2" value=1 <?php if($information && $information[0]["spill_kit"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="spillKit" id="spillKit3" value=99 <?php if($information && $information[0]["spill_kit"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>	

	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>COMMENTS</strong>
				</div>
				<div class="panel-body">
													
					<div class="form-group">
						<label class="col-sm-4 control-label" for="comments">Comments <small class="text-primary">(Comentarios)</small></label>
						<div class="col-sm-5">
						<textarea id="comments" name="comments" placeholder="Comments"  class="form-control" rows="3"><?php echo $information?$information[0]["comments"]:""; ?></textarea>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="row" align="center">
			<div style="width:50%;" align="center">
				<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
					Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
				</button> 
			</div>
		</div>
	</div>
						
	<div class="form-group">
		<div class="row" align="center">
			<div style="width:80%;" align="center">
				<div id="div_load" style="display:none">		
					<div class="progress progress-striped active">
						<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
							<span class="sr-only">45% completado</span>
						</div>
					</div>
				</div>
				<div id="div_error" style="display:none">			
					<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
				</div>
			</div>
		</div>
	</div>

</form>
	
</div>

<!--INICIO Modal -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal -->