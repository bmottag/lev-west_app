<script type="text/javascript" src="<?php echo base_url("assets/js/validate/inspection/heavy_inspection.js?v=1.0.0"); ?>"></script>

<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var idRecord = $('#hddId').val();
			var table = "inspection_heavy";
			var backURL = "inspection/add_heavy_inspection/";
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

<div id="page-wrapper">
	<br>
	
<form  name="form" id="form" class="form-horizontal" method="post" >
	<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_inspection_heavy"]:""; ?>"/>
	<input type="hidden" id="hddIdVehicle" name="hddIdVehicle" value="<?php echo $vehicleInfo[0]["id_vehicle"]; ?>"/>
	<input type="hidden" id="oilChange" name="oilChange" value="<?php echo $vehicleInfo[0]["oil_change"]; ?>"/>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<i class="fa fa-search"></i> <strong> GRADERS, PACKERS, ROCK TRUCKS INSPECTION - VIII</strong><!-- #8 -->
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
						<label class="col-sm-4 control-label" for="date">Issued Date</label>
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
					<strong>ENGINE</strong>
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
						<label class="col-sm-4 control-label" for="transmission">Transmission Fluid <small class="text-primary">(Aceite de transmisión)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="transmission" id="transmission1" value=0 <?php if($information && $information[0]["transmission"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="transmission" id="transmission2" value=1 <?php if($information && $information[0]["transmission"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="transmission" id="transmission3" value=99 <?php if($information && $information[0]["transmission"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="hydrolic">Hydraulic Fluids <small class="text-primary">(Aceite hidráulico)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="hydrolic" id="hydrolic1" value=0 <?php if($information && $information[0]["hydrolic"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="hydrolic" id="hydrolic2" value=1 <?php if($information && $information[0]["hydrolic"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="hydrolic" id="hydrolic3" value=99 <?php if($information && $information[0]["hydrolic"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="bladePins">Blade Pins <small class="text-primary">(Bujes de la cuchilla)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="bladePins" id="bladePins1" value=0 <?php if($information && $information[0]["blade_pins"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="bladePins" id="bladePins2" value=1 <?php if($information && $information[0]["blade_pins"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="bladePins" id="bladePins3" value=99 <?php if($information && $information[0]["blade_pins"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="ripper">Ripper <small class="text-primary">(Ripper)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="ripper" id="ripper1" value=0 <?php if($information && $information[0]["ripper"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="ripper" id="ripper2" value=1 <?php if($information && $information[0]["ripper"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="ripper" id="ripper3" value=99 <?php if($information && $information[0]["ripper"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="frontAxle">Front Axle <small class="text-primary">(Eje delantero)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="frontAxle" id="frontAxle1" value=0 <?php if($information && $information[0]["front_axle"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="frontAxle" id="frontAxle2" value=1 <?php if($information && $information[0]["front_axle"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="frontAxle" id="frontAxle3" value=99 <?php if($information && $information[0]["front_axle"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="rearAxle">Rear Axle <small class="text-primary">(Eje posterior)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="rearAxle" id="rearAxle1" value=0 <?php if($information && $information[0]["rear_axle"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="rearAxle" id="rearAxle2" value=1 <?php if($information && $information[0]["rear_axle"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="rearAxle" id="rearAxle3" value=99 <?php if($information && $information[0]["rear_axle"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="tableDozer">Rotatory Table <small class="text-primary">(Plancha de rotación)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="tableDozer" id="tableDozer1" value=0 <?php if($information && $information[0]["table_dozer"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="tableDozer" id="tableDozer2" value=1 <?php if($information && $information[0]["table_dozer"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="tableDozer" id="tableDozer3" value=99 <?php if($information && $information[0]["table_dozer"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="pivinPoints">Excualizer <small class="text-primary"></small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="pivinPoints" id="pivinPoints1" value=0 <?php if($information && $information[0]["pivin_points"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="pivinPoints" id="pivinPoints2" value=1 <?php if($information && $information[0]["pivin_points"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="pivinPoints" id="pivinPoints3" value=99 <?php if($information && $information[0]["pivin_points"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="bucketPinsSkit">Box Hoist & Hinges <small class="text-primary"></small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="bucketPinsSkit" id="bucketPinsSkit1" value=0 <?php if($information && $information[0]["bucket_pins_skit"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="bucketPinsSkit" id="bucketPinsSkit2" value=1 <?php if($information && $information[0]["bucket_pins_skit"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="bucketPinsSkit" id="bucketPinsSkit3" value=99 <?php if($information && $information[0]["bucket_pins_skit"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="workingLamps">Work Lights <small class="text-primary">(Luces principales)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="workingLamps" id="workingLamps1" value=0 <?php if($information && $information[0]["working_lamps"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="workingLamps" id="workingLamps2" value=1 <?php if($information && $information[0]["working_lamps"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="workingLamps" id="workingLamps3" value=99 <?php if($information && $information[0]["working_lamps"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="door">Access Door <small class="text-primary">(Puerta)</small></label>
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

<!-- CAMPOS QUE NO USA ESTE FORMULARIO -->	
<input type="hidden" id="bucket" name="bucket" value=99 >
<input type="hidden" id="tracks" name="tracks" value=99 >
<input type="hidden" id="rubberTrucks" name="rubberTrucks" value=99 >
<input type="hidden" id="thamper" name="thamper" value=99 >
<input type="hidden" id="boom" name="boom" value=99 >
<input type="hidden" id="tableExcavator" name="tableExcavator" value=99 >
<input type="hidden" id="bucketPins" name="bucketPins" value=99 >
<input type="hidden" id="sideArms" name="sideArms" value=99 >
<input type="hidden" id="rollers" name="rollers" value=99 >

	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>ATTACHMENTS</strong>
				</div>
				<div class="panel-body">

					<div class="form-group">
						<label class="col-sm-4 control-label" for="blades">Blade <small class="text-primary">(Cuchillas)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="blades" id="blades1" value=0 <?php if($information && $information[0]["blades"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="blades" id="blades2" value=1 <?php if($information && $information[0]["blades"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="blades" id="blades3" value=99 <?php if($information && $information[0]["blades"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
				
					<div class="form-group">
						<label class="col-sm-4 control-label" for="cutting">Cutting edges <small class="text-primary">(Cuchillas)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="cutting" id="cutting1" value=0 <?php if($information && $information[0]["cutting_edges"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="cutting" id="cutting2" value=1 <?php if($information && $information[0]["cutting_edges"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="cutting" id="cutting3" value=99 <?php if($information && $information[0]["cutting_edges"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="drill">Drums (Packers only) <small class="text-primary"></small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="drill" id="drill1" value=0 <?php if($information && $information[0]["drill"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="drill" id="drill2" value=1 <?php if($information && $information[0]["drill"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="drill" id="drill3" value=99 <?php if($information && $information[0]["drill"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="heater">A/C & Heater <small class="text-primary">(Aire acondicionado / Calefaccion)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="heater" id="heater1" value=0 <?php if($information && $information[0]["heater"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="heater" id="heater2" value=1 <?php if($information && $information[0]["heater"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="heater" id="heater3" value=99 <?php if($information && $information[0]["heater"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="operatorSeat">Operator Seat <small class="text-primary">(Silla del operador)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="operatorSeat" id="operatorSeat1" value=0 <?php if($information && $information[0]["operator_seat"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="operatorSeat" id="operatorSeat2" value=1 <?php if($information && $information[0]["operator_seat"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="operatorSeat" id="operatorSeat3" value=99 <?php if($information && $information[0]["operator_seat"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="horn">Electrical Horn <small class="text-primary">(Pito)</small></label>
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
						<label class="col-sm-4 control-label" for="seatbelt">Seatbelt <small class="text-primary">(Cinturón de seguridad)</small></label>
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
					<strong>EQUIPMENT WITH TIRES</strong>
				</div>
				<div class="panel-body">
									
					<div class="form-group">
						<label class="col-sm-4 control-label" for="tire">Tire / Lug Nuts / Pressure <small class="text-primary">(Presión de llantas)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="tire" id="tire1" value=0 <?php if($information && $information[0]["tire_presurre"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="tire" id="tire2" value=1 <?php if($information && $information[0]["tire_presurre"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="tire" id="tire3" value=99 <?php if($information && $information[0]["tire_presurre"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
						
					<div class="form-group">
						<label class="col-sm-4 control-label" for="turn">Turn Signals <small class="text-primary">(Direccionales)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="turn" id="turn1" value=0 <?php if($information && $information[0]["turn_signals"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="turn" id="turn2" value=1 <?php if($information && $information[0]["turn_signals"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="turn" id="turn3" value=99 <?php if($information && $information[0]["turn_signals"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="rims">Rims <small class="text-primary">(Rines)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="rims" id="rims1" value=0 <?php if($information && $information[0]["rims"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="rims" id="rims2" value=1 <?php if($information && $information[0]["rims"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="rims" id="rims3" value=99 <?php if($information && $information[0]["rims"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="brake">Emergency Brake <small class="text-primary">(Freno de emergencia)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="brake" id="brake1" value=0 <?php if($information && $information[0]["emergency_brake"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="brake" id="brake2" value=1 <?php if($information && $information[0]["emergency_brake"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="brake" id="brake3" value=99 <?php if($information && $information[0]["emergency_brake"] == 99) { echo "checked"; }  ?>>N/A
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