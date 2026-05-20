<script type="text/javascript" src="<?php echo base_url("assets/js/validate/inspection/generator_inspection.js"); ?>"></script>

<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var idRecord = $('#hddId').val();
			var table = "inspection_generator";
			var backURL = "inspection/add_generator_inspection/";
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
	<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_inspection_generator"]:""; ?>"/>
	<input type="hidden" id="hddIdVehicle" name="hddIdVehicle" value="<?php echo $vehicleInfo[0]["id_vehicle"]; ?>"/>
	<input type="hidden" id="oilChange" name="oilChange" value="<?php echo $vehicleInfo[0]["oil_change"]; ?>"/>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-purpura">
				<div class="panel-heading">
					<i class="fa fa-search"></i> <strong> GENERATOR & LIGHT TOWERS INSPECTION - V</strong><!-- #5 -->
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
						<label class="col-sm-4 control-label" for="hours">Current Hours <small class="text-primary">(Horas Actual)</small></label>
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
						<label class="col-sm-4 control-label" for="fuelFilter">Fuel Filter <small class="text-primary">(Filtro combustible)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="fuelFilter" id="fuelFilter1" value=0 <?php if($information && $information[0]["fuel_filter"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="fuelFilter" id="fuelFilter2" value=1 <?php if($information && $information[0]["fuel_filter"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="fuelFilter" id="fuelFilter3" value=99 <?php if($information && $information[0]["fuel_filter"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="turnSignal">Turn signal lights <small class="text-primary">(Direccionales)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="turnSignal" id="turnSignal1" value=0 <?php if($information && $information[0]["turn_signal"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="turnSignal" id="turnSignal2" value=1 <?php if($information && $information[0]["turn_signal"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="turnSignal" id="turnSignal3" value=99 <?php if($information && $information[0]["turn_signal"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="tailLights">Tail lights <small class="text-primary">(Luces traseras)</small></label>
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
						<label class="col-sm-4 control-label" for="floodLights">Flood Lights <small class="text-primary">(Luces principales)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="floodLights" id="floodLights1" value=0 <?php if($information && $information[0]["flood_lights"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="floodLights" id="floodLights2" value=1 <?php if($information && $information[0]["flood_lights"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="floodLights" id="floodLights3" value=99 <?php if($information && $information[0]["flood_lights"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="boom">Boom <small class="text-primary">(Boom)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="boom" id="boom1" value=0 <?php if($information && $information[0]["boom"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="boom" id="boom2" value=1 <?php if($information && $information[0]["boom"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="boom" id="boom3" value=99 <?php if($information && $information[0]["boom"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>
									
					<div class="form-group">
						<label class="col-sm-4 control-label" for="gears">Landing Gears <small class="text-primary">(Tren de anclaje)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="gears" id="gears1" value=0 <?php if($information && $information[0]["gears"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="gears" id="gears2" value=1 <?php if($information && $information[0]["gears"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="gears" id="gears3" value=99 <?php if($information && $information[0]["gears"] == 99) { echo "checked"; }  ?>>N/A
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
						<label class="col-sm-4 control-label" for="pulley">Retractive Pulley <small class="text-primary">(Polea)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="pulley" id="pulley1" value=0 <?php if($information && $information[0]["pulley"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="pulley" id="pulley2" value=1 <?php if($information && $information[0]["pulley"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="pulley" id="pulley3" value=99 <?php if($information && $information[0]["pulley"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4 control-label" for="electrical">Electrical Outlets <small class="text-primary">(Toma corrientes)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="electrical" id="electrical1" value=0 <?php if($information && $information[0]["electrical"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="electrical" id="electrical2" value=1 <?php if($information && $information[0]["electrical"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="electrical" id="electrical3" value=99 <?php if($information && $information[0]["electrical"] == 99) { echo "checked"; }  ?>>N/A
							</label>
						</div>
					</div>					
					
					<div class="form-group">
						<label class="col-sm-4 control-label" for="brackers">Brackers <small class="text-primary">(Tacos)</small></label>
						<div class="col-sm-5">
							<label class="radio-inline">
								<input type="radio" name="brackers" id="brackers1" value=0 <?php if($information && $information[0]["brackers"] == 0) { echo "checked"; }  ?>>Fail
							</label>
							<label class="radio-inline">
								<input type="radio" name="brackers" id="brackers2" value=1 <?php if($information && $information[0]["brackers"] == 1) { echo "checked"; }  ?>>Pass
							</label>
							<label class="radio-inline">
								<input type="radio" name="brackers" id="brackers3" value=99 <?php if($information && $information[0]["brackers"] == 99) { echo "checked"; }  ?>>N/A
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