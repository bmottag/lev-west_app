<script type="text/javascript" src="<?php echo base_url("assets/js/validate/inspection/watertruck_inspection.js?v=1.0.0"); ?>"></script>

<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var idRecord = $('#hddId').val();
			var table = "inspection_watertruck";
			var backURL = "inspection/add_watertruck_inspection/";
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
if($userRol==99){
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php } ?>

<?php 	 
	$idVehicle = $vehicleInfo[0]["id_vehicle"];
?>

<div id="page-wrapper">
	<br>
	
<form  name="form" id="form" class="form-horizontal" method="post" >
	<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_inspection_watertruck"]:""; ?>"/>
	<input type="hidden" id="hddIdVehicle" name="hddIdVehicle" value="<?php echo $idVehicle; ?>"/>
	<input type="hidden" id="oilChange" name="oilChange" value="<?php echo $vehicleInfo[0]["oil_change"]; ?>"/>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-purpura">
				<div class="panel-heading">
					<i class="fa fa-search"></i> <strong> WATER TRUCK INSPECTION - X</strong><!-- #10 -->
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

<?php
$heater_check = $vehicleInfo[0]["heater_check"];
$brakes_check = $vehicleInfo[0]["brakes_check"];
$lights_check = $vehicleInfo[0]["lights_check"];
$steering_wheel_check = $vehicleInfo[0]["steering_wheel_check"];
$suspension_system_check = $vehicleInfo[0]["suspension_system_check"];
$tires_check = $vehicleInfo[0]["tires_check"];
$wipers_check = $vehicleInfo[0]["wipers_check"];
$air_brake_check = $vehicleInfo[0]["air_brake_check"];
$driver_seat_check = $vehicleInfo[0]["driver_seat_check"];
$fuel_system_check = $vehicleInfo[0]["fuel_system_check"];


//preguntar especiales para HYDROVAC para que muestre mensaje si es inseguro sacar el camion
if ($heater_check == 0 || $brakes_check == 0 || $lights_check == 0 || $steering_wheel_check == 0 || $suspension_system_check == 0 || $tires_check == 0 || $wipers_check == 0 || $air_brake_check == 0 || $driver_seat_check == 0 || $fuel_system_check == 0) {
    ?>
	<div class="row">
		<div class="col-lg-12">	
			<div class="alert alert-danger ">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					A major defect has beed identified in the last inspecton, a driver is not legally permitted
					to operate the vehicle until that defect is prepared.
			
					<?php 
						if ($heater_check == 0) {
							echo "<br>Heater - Fail"; 
						}
						if ($brakes_check == 0) {
							echo "<br>Brake pedal - Fail"; 
						}
						if ($lights_check == 0) {
							echo "<br>Lamps and reflectors - Fail"; 
						}
						if ($steering_wheel_check == 0) {
							echo "<br>Steering wheel - Fail"; 
						}
						if ($suspension_system_check == 0) {
							echo "<br>Suspension system - Fail"; 
						}
						if ($tires_check == 0) {
							echo "<br>Tires/Lug Nuts/Pressure - Fail"; 
						}
						if ($wipers_check == 0) {
							echo "<br>Wipers/Washers - Fail"; 
						}
						if ($air_brake_check == 0) {
							echo "<br>Air brake system - Fail"; 
						}
						if ($driver_seat_check == 0) {
							echo "<br>Driver and Passenger door - Fail"; 
						}
						if ($fuel_system_check == 0) {
							echo "<br>Fuel system - Fail"; 
						}		
					?>
			</div>
		</div>
	</div>
<?php
}
?>

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
						<strong>Equipment Hours: </strong><?php echo number_format($vehicleInfo[0]["hours"]); ?> hours
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
			<div class="modal-body text-center"><img src="<?php echo base_url($information[0]["signature"]); ?>" class="img-rounded" alt="Daily Inspection Signature" width="304" height="236" />   </div>      
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
						<label class="col-sm-4 control-label" for="hours">Current Hours <small class="text-primary">(Horas actuales)</small></label>
						<div class="col-sm-5">
							<input type="text" id="hours" name="hours" class="form-control" value="<?php if($information){ echo $vehicleInfo[0]["hours"]; }?>" placeholder="Hours" required >
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
						<label class="col-sm-4 control-label" for="hazardLights">Hazard Lights <small class="text-primary">(Luces intermitentes)</small></label>
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
						<label class="col-sm-4 control-label" for="clearanceLights">Clearance Lights <small class="text-primary">(Luces de posición)</small></label>
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
						<label class="col-sm-4 control-label" for="mirrors">Glass (All) & Mirror(s) <small class="text-primary">(Ventanas y espejos)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="mirrors" id="mirrors1" value=0 <?php if($information && $information[0]["mirrors"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="mirrors" id="mirrors2" value=1 <?php if($information && $information[0]["mirrors"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="mirrors" id="mirrors3" value=99 <?php if($information && $information[0]["mirrors"] == 99) { echo "checked"; }  ?>>N/A
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
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="sprinkelrs">Sprinkelrs <small class="text-primary">(Rociadores)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="sprinkelrs" id="sprinkelrs1" value=0 <?php if($information && $information[0]["sprinkelrs"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="sprinkelrs" id="sprinkelrs2" value=1 <?php if($information && $information[0]["sprinkelrs"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="sprinkelrs" id="sprinkelrs3" value=99 <?php if($information && $information[0]["sprinkelrs"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="steringAxle">Stering Axle <small class="text-primary">(Eje de dirección)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="steringAxle" id="steringAxle1" value=0 <?php if($information && $information[0]["stering_axle"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="steringAxle" id="steringAxle2" value=1 <?php if($information && $information[0]["stering_axle"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="steringAxle" id="steringAxle3" value=99 <?php if($information && $information[0]["stering_axle"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="drives">Drives Axles <small class="text-primary">(Ejes)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="drives" id="drives1" value=0 <?php if($information && $information[0]["drives_axles"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="drives" id="drives2" value=1 <?php if($information && $information[0]["drives_axles"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="drives" id="drives3" value=99 <?php if($information && $information[0]["drives_axles"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="backDrive">Back drive shaft <small class="text-primary">(Cardan trasero)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="backDrive" id="backDrive1" value=0 <?php if($information && $information[0]["back_drive"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="backDrive" id="backDrive2" value=1 <?php if($information && $information[0]["back_drive"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="backDrive" id="backDrive3" value=99 <?php if($information && $information[0]["back_drive"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="waterPump">Water pump greasers <small class="text-primary">(Lubricantes de la bomba de agua)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="waterPump" id="waterPump1" value=0 <?php if($information && $information[0]["water_pump"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="waterPump" id="waterPump2" value=1 <?php if($information && $information[0]["water_pump"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="waterPump" id="waterPump3" value=99 <?php if($information && $information[0]["water_pump"] == 99) { echo "checked"; }  ?>>N/A
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
					<strong>OTHER</strong>
				</div>
				<div class="panel-body">
									
					<div class="form-group">
						<label class="col-sm-4 control-label" for="heater">Heater/Defroster <small class="text-primary">(Calefacción)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="heater" id="heater1" value=0 <?php if($information && $information[0]["heater"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="heater" id="heater2" value=1 <?php if($information && $information[0]["heater"] == 1) { echo "checked"; }  ?>>Pass
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="steering_wheel">Steering wheel <small class="text-primary">(Volante)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="steering_wheel" id="steering_wheel1" value=0 <?php if($information && $information[0]["steering_wheel"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="steering_wheel" id="steering_wheel2" value=1 <?php if($information && $information[0]["steering_wheel"] == 1) { echo "checked"; }  ?>>Pass
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="suspension_system">Suspension system <small class="text-primary">(Sistema de suspensión)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="suspension_system" id="suspension_system1" value=0 <?php if($information && $information[0]["suspension_system"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="suspension_system" id="suspension_system2" value=1 <?php if($information && $information[0]["suspension_system"] == 1) { echo "checked"; }  ?>>Pass
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="air_brake">Air brake system <small class="text-primary">(Sistema de freno de aire)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="air_brake" id="air_brake1" value=0 <?php if($information && $information[0]["air_brake"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="air_brake" id="air_brake2" value=1 <?php if($information && $information[0]["air_brake"] == 1) { echo "checked"; }  ?>>Pass
							</label>
						</div>
					</div>
										
					<div class="form-group">
						<label class="col-sm-4 control-label" for="fuel_system">Fuel system <small class="text-primary">(Sistema de combustible)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="fuel_system" id="fuel_system1" value=0 <?php if($information && $information[0]["fuel_system"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="fuel_system" id="fuel_system2" value=1 <?php if($information && $information[0]["fuel_system"] == 1) { echo "checked"; }  ?>>Pass
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