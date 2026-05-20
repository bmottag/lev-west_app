<script type="text/javascript" src="<?php echo base_url("assets/js/validate/inspection/vehicle_inspection.js"); ?>"></script>

<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var idRecord = $('#hddId').val();
			var table = "inspection_daily";
			var backURL = "inspection/add_daily_inspection/";
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
if ($userRol == ID_ROL_SUPER_ADMIN) {
?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php } ?>

<?php
$idVehicle = $vehicleInfo[0]["id_vehicle"];

$inspectionType = $vehicleInfo[0]["inspection_type"];
$truck = FALSE; //cargo bandera para utilizarla en los campos que son para TRUCK -> inpection type 3
$tituloHorn = "Electrical Horn";
$tituloHours = "KILOMETERS";
$tituloSmallHours = "Kilometers";
$tituloShort = " km";
if ($inspectionType == 3) {
	$truck = TRUE;
	$tituloHorn = "Electrical & Air Horn";
	$tituloHours = "HOURS";
	$tituloSmallHours = "Hours";
	$tituloShort = " hours";
}
?>

<div id="page-wrapper">
	<br>

	<form name="form" id="form" class="form-horizontal" method="post">
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information ? $information[0]["id_inspection_daily"] : ""; ?>" />
		<input type="hidden" id="hddIdVehicle" name="hddIdVehicle" value="<?php echo $idVehicle; ?>" />
		<input type="hidden" id="oilChange" name="oilChange" value="<?php echo $vehicleInfo[0]["oil_change"]; ?>" />

		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-success">
					<div class="panel-heading">
						<i class="fa fa-search"></i><strong>
							<?php
							if ($truck) {
								echo " DUMP & HIGHWAY TRUCKS INSPECTION - II"; //#2
							} else {
								echo " PICKUP INSPECTION - I"; //#1
							}
							?>
						</strong>
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


						//preguntar especiales para T001, T002, T003, para que muestre mensaje si es inseguro sacar el camion
						if ($idVehicle == 5 || $idVehicle == 11 || $idVehicle == 12) {


							if ($heater_check == 0 || $brakes_check == 0 || $lights_check == 0 || $steering_wheel_check == 0 || $suspension_system_check == 0 || $tires_check == 0 || $wipers_check == 0 || $air_brake_check == 0 || $driver_seat_check == 0 || $fuel_system_check == 0) {
						?>
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
						<?php
							}
						}
						?>

						<?php if ($vehicleInfo[0]["photo"]) { ?>
							<div class="form-group">
								<div class="row" align="center">
									<img src="<?php echo base_url($vehicleInfo[0]["photo"]); ?>" class="img-rounded" alt="Vehicle Photo" />
								</div>
							</div>
						<?php } ?>

						<strong>Description: </strong><?php echo $vehicleInfo[0]['description']; ?><br>
						<strong>Unit Number: </strong><?php echo $vehicleInfo[0]['unit_number']; ?><br>
						<strong>VIN Number: </strong><?php echo $vehicleInfo[0]['vin_number']; ?><br>

						<?php
						$tipo = $vehicleInfo[0]['type_level_2'];

						echo "<p class='text-danger'>";
						//si es sweeper
						if ($tipo == 15) {
							echo "<strong>Truck Engine " . $tituloSmallHours . ":</strong>";
							echo number_format($vehicleInfo[0]["hours"]);

							echo "<br><strong>Sweeper Engine " . $tituloSmallHours . ":</strong>";
							echo number_format($vehicleInfo[0]["hours_2"]) . $tituloShort;
							//si es hydrovac
						} elseif ($tipo == 16) {
							echo "<strong>Engine " . $tituloSmallHours . ":</strong>";
							echo number_format($vehicleInfo[0]["hours"]) . $tituloShort;

							echo "<br><strong>Hydraulic Pump " . $tituloSmallHours . ":</strong>";
							echo number_format($vehicleInfo[0]["hours_2"]) . $tituloShort;

							echo "<br><strong>Blower " . $tituloSmallHours . ":</strong>";
							echo number_format($vehicleInfo[0]["hours_3"]) . $tituloShort;
						} else {
							echo "<strong>Equipment " . $tituloSmallHours . ": </strong>";
							echo number_format($vehicleInfo[0]["hours"]) . $tituloShort;
						}
						echo "</p>";

						?>

						<!-- INICIO Firma del conductor -->
						<?php if ($information) {

							//si ya esta la firma entonces se muestra mensaje que ya termino el reporte
							if ($information[0]["signature"]) {
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
													if ($information[0]["signature"]) {
														$class = "btn-default";
													?>

														<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
															<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Signature
														</button>

														<div id="myModal" class="modal fade" role="dialog">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-header">
																		<button type="button" class="close" data-dismiss="modal">×</button>
																		<h4 class="modal-title">Daily Inspection Signature</h4>
																	</div>
																	<div class="modal-body text-center"><img src="<?php echo base_url($information[0]["signature"]); ?>" class="img-rounded" alt="Management/Safety Advisor Signature" width="304" height="236" /> </div>
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
						<strong><?php echo $tituloHours; ?></strong>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="hours"><?php echo $tituloSmallHours; ?> <small class="text-primary"> </small></label>
							<div class="col-sm-5">
								<input type="text" id="hours" name="hours" class="form-control" value="<?php if ($information) {
																											echo $vehicleInfo[0]["hours"];
																										} ?>" placeholder="<?php echo $tituloSmallHours; ?>" required>
							</div>
						</div>

						<?php
						/**
						 * If it is an ADMIN user, show date 
						 * @author BMOTTAG
						 * @since  11/5/2017
						 */
						if ($userRol == 99) {
						?>
							<script>
								$(function() {
									$("#date").datepicker({
										changeMonth: true,
										changeYear: true,
										dateFormat: 'yy-mm-dd'
									});
								});
							</script>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="date">Issued Date</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="date" name="date" value="<?php echo $information ? $information[0]["date_issue"] : ""; ?>" placeholder="Date of Issue" />
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
							<label class="col-sm-4 control-label" for="belt">Belts/Hoses <small class="text-primary">(Correas/Mangueras)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="belt" id="belt1" value=0 <?php if ($information && $information[0]["belt"] == 0) {
																							echo "checked";
																						}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="belt" id="belt2" value=1 <?php if ($information && $information[0]["belt"] == 1) {
																							echo "checked";
																						}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="belt" id="belt3" value=99 <?php if ($information && $information[0]["belt"] == 99) {
																							echo "checked";
																						}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="powerSteering">Power steering fluid <small class="text-primary">(Líquido de dirección asistida)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="powerSteering" id="powerSteering1" value=0 <?php if ($information && $information[0]["power_steering"] == 0) {
																												echo "checked";
																											}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="powerSteering" id="powerSteering2" value=1 <?php if ($information && $information[0]["power_steering"] == 1) {
																												echo "checked";
																											}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="powerSteering" id="powerSteering3" value=99 <?php if ($information && $information[0]["power_steering"] == 99) {
																												echo "checked";
																											}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="oil">Oil level <small class="text-primary">(Nivel de aceite)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="oil" id="oil1" value=0 <?php if ($information && $information[0]["oil_level"] == 0) {
																							echo "checked";
																						}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="oil" id="oil2" value=1 <?php if ($information && $information[0]["oil_level"] == 1) {
																							echo "checked";
																						}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="oil" id="oil3" value=99 <?php if ($information && $information[0]["oil_level"] == 99) {
																							echo "checked";
																						}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="coolantLevel">Coolant level <small class="text-primary">(Nivel de liquido refrigerante )</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="coolantLevel" id="coolantLevel1" value=0 <?php if ($information && $information[0]["coolant_level"] == 0) {
																											echo "checked";
																										}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="coolantLevel" id="coolantLevel2" value=1 <?php if ($information && $information[0]["coolant_level"] == 1) {
																											echo "checked";
																										}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="coolantLevel" id="coolantLevel3" value=99 <?php if ($information && $information[0]["coolant_level"] == 99) {
																											echo "checked";
																										}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="waterLeaks">Coolant/Oil Leaks <small class="text-primary">(Fugas de Refrigerante/Aceite)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="waterLeaks" id="waterLeaks1" value=0 <?php if ($information && $information[0]["water_leaks"] == 0) {
																										echo "checked";
																									}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="waterLeaks" id="waterLeaks2" value=1 <?php if ($information && $information[0]["water_leaks"] == 1) {
																										echo "checked";
																									}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="waterLeaks" id="waterLeaks3" value=99 <?php if ($information && $information[0]["water_leaks"] == 99) {
																										echo "checked";
																									}  ?>>N/A
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

		<?php if ($truck) { ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<strong>GREASING</strong>
						</div>
						<div class="panel-body">

							<div class="form-group">
								<label class="col-sm-4 control-label" for="steeringAxle">Steering Axle <small class="text-primary">(Eje de dirección)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="steeringAxle" id="steeringAxle1" value=0 <?php if ($information && $information[0]["steering_axle"] == 0) {
																												echo "checked";
																											}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="steeringAxle" id="steeringAxle2" value=1 <?php if ($information && $information[0]["steering_axle"] == 1) {
																												echo "checked";
																											}  ?>>Pass
									</label>
									<label class="radio-inline">
										<input type="radio" name="steeringAxle" id="steeringAxle3" value=99 <?php if ($information && $information[0]["steering_axle"] == 99) {
																												echo "checked";
																											}  ?>>N/A
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="drivesAxle">Drives Axles <small class="text-primary">(Ejes traseros)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="drivesAxle" id="drivesAxle1" value=0 <?php if ($information && $information[0]["drives_axle"] == 0) {
																											echo "checked";
																										}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="drivesAxle" id="drivesAxle2" value=1 <?php if ($information && $information[0]["drives_axle"] == 1) {
																											echo "checked";
																										}  ?>>Pass
									</label>
									<label class="radio-inline">
										<input type="radio" name="drivesAxle" id="drivesAxle3" value=99 <?php if ($information && $information[0]["drives_axle"] == 99) {
																											echo "checked";
																										}  ?>>N/A
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="greaseFront">Front drive shaft <small class="text-primary">(Cardan delantero)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="greaseFront" id="greaseFront1" value=0 <?php if ($information && $information[0]["grease_front"] == 0) {
																												echo "checked";
																											}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="greaseFront" id="greaseFront2" value=1 <?php if ($information && $information[0]["grease_front"] == 1) {
																												echo "checked";
																											}  ?>>Pass
									</label>
									<label class="radio-inline">
										<input type="radio" name="greaseFront" id="greaseFront3" value=99 <?php if ($information && $information[0]["grease_front"] == 99) {
																												echo "checked";
																											}  ?>>N/A
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="greaseEnd">Back drive shaft <small class="text-primary">(Cardan trasero)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="greaseEnd" id="greaseEnd1" value=0 <?php if ($information && $information[0]["grease_end"] == 0) {
																											echo "checked";
																										}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="greaseEnd" id="greaseEnd2" value=1 <?php if ($information && $information[0]["grease_end"] == 1) {
																											echo "checked";
																										}  ?>>Pass
									</label>
									<label class="radio-inline">
										<input type="radio" name="greaseEnd" id="greaseEnd3" value=99 <?php if ($information && $information[0]["grease_end"] == 99) {
																											echo "checked";
																										}  ?>>N/A
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="grease">Grease 5th wheel <small class="text-primary">(Engrase la quinta rueda)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="grease" id="grease1" value=0 <?php if ($information && $information[0]["grease"] == 0) {
																									echo "checked";
																								}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="grease" id="grease2" value=1 <?php if ($information && $information[0]["grease"] == 1) {
																									echo "checked";
																								}  ?>>Pass
									</label>
									<label class="radio-inline">
										<input type="radio" name="grease" id="grease3" value=99 <?php if ($information && $information[0]["grease"] == 99) {
																									echo "checked";
																								}  ?>>N/A
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="hoist">Box hoist & hinge <small class="text-primary">(Piston y bisagras del volco)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="hoist" id="hoist1" value=0 <?php if ($information && $information[0]["hoist"] == 0) {
																									echo "checked";
																								}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="hoist" id="hoist2" value=1 <?php if ($information && $information[0]["hoist"] == 1) {
																									echo "checked";
																								}  ?>>Pass
									</label>
									<label class="radio-inline">
										<input type="radio" name="hoist" id="hoist3" value=99 <?php if ($information && $information[0]["hoist"] == 99) {
																									echo "checked";
																								}  ?>>N/A
									</label>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		<?php
		} else {
			echo '<input type="hidden" id="steeringAxle" name="steeringAxle" value=99 >';
			echo '<input type="hidden" id="drivesAxle" name="drivesAxle" value=99 >';
			echo '<input type="hidden" id="greaseFront" name="greaseFront" value=99 >';
			echo '<input type="hidden" id="greaseEnd" name="greaseEnd" value=99 >';
			echo '<input type="hidden" id="grease" name="grease" value=99 >';
			echo '<input type="hidden" id="hoist" name="hoist" value=99 >';
		}
		?>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong>LIGHTS</strong>
					</div>
					<div class="panel-body">

						<div class="form-group">
							<label class="col-sm-4 control-label" for="headLamps">Head lamps <small class="text-primary">(Luces delanteras)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="headLamps" id="headLamps1" value=0 <?php if ($information && $information[0]["head_lamps"] == 0) {
																										echo "checked";
																									}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="headLamps" id="headLamps2" value=1 <?php if ($information && $information[0]["head_lamps"] == 1) {
																										echo "checked";
																									}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="headLamps" id="headLamps3" value=99 <?php if ($information && $information[0]["head_lamps"] == 99) {
																										echo "checked";
																									}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="hazardLights">Hazard lights <small class="text-primary">(Luces intermitentes)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="hazardLights" id="hazardLights1" value=0 <?php if ($information && $information[0]["hazard_lights"] == 0) {
																											echo "checked";
																										}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="hazardLights" id="hazardLights2" value=1 <?php if ($information && $information[0]["hazard_lights"] == 1) {
																											echo "checked";
																										}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="hazardLights" id="hazardLights3" value=99 <?php if ($information && $information[0]["hazard_lights"] == 99) {
																											echo "checked";
																										}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="bakeLights">Tail lights <small class="text-primary">(Luces traseras)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="bakeLights" id="bakeLights1" value=0 <?php if ($information && $information[0]["bake_lights"] == 0) {
																										echo "checked";
																									}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="bakeLights" id="bakeLights2" value=1 <?php if ($information && $information[0]["bake_lights"] == 1) {
																										echo "checked";
																									}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="bakeLights" id="bakeLights3" value=99 <?php if ($information && $information[0]["bake_lights"] == 99) {
																										echo "checked";
																									}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="workLights">Work lights <small class="text-primary">(Luces de reversa)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="workLights" id="workLights1" value=0 <?php if ($information && $information[0]["work_lights"] == 0) {
																										echo "checked";
																									}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="workLights" id="workLights2" value=1 <?php if ($information && $information[0]["work_lights"] == 1) {
																										echo "checked";
																									}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="workLights" id="workLights3" value=99 <?php if ($information && $information[0]["work_lights"] == 99) {
																										echo "checked";
																									}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="turnSignals">Turn signal lights <small class="text-primary">(Direccionales)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="turnSignals" id="turnSignals1" value=0 <?php if ($information && $information[0]["turn_signals"] == 0) {
																											echo "checked";
																										}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="turnSignals" id="turnSignals2" value=1 <?php if ($information && $information[0]["turn_signals"] == 1) {
																											echo "checked";
																										}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="turnSignals" id="turnSignals3" value=99 <?php if ($information && $information[0]["turn_signals"] == 99) {
																											echo "checked";
																										}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="beaconLight">Beacon Light <small class="text-primary">(Faro de luz)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="beaconLight" id="beaconLight1" value=0 <?php if ($information && $information[0]["beacon_light"] == 0) {
																											echo "checked";
																										}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="beaconLight" id="beaconLight2" value=1 <?php if ($information && $information[0]["beacon_light"] == 1) {
																											echo "checked";
																										}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="beaconLight" id="beaconLight3" value=99 <?php if ($information && $information[0]["beacon_light"] == 99) {
																											echo "checked";
																										}  ?>>N/A
								</label>
							</div>
						</div>

						<?php if ($truck) { ?>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="clearanceLights">Clearance lights <small class="text-primary">(Luces de posición)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="clearanceLights" id="clearanceLights1" value=0 <?php if ($information && $information[0]["clearance_lights"] == 0) {
																														echo "checked";
																													}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="clearanceLights" id="clearanceLights2" value=1 <?php if ($information && $information[0]["clearance_lights"] == 1) {
																														echo "checked";
																													}  ?>>Pass
									</label>
									<label class="radio-inline">
										<input type="radio" name="clearanceLights" id="clearanceLights3" value=99 <?php if ($information && $information[0]["clearance_lights"] == 99) {
																														echo "checked";
																													}  ?>>N/A
									</label>
								</div>
							</div>
						<?php
						} else {
							echo '<input type="hidden" id="clearanceLights" name="clearanceLights" value=99 >';
						}
						?>

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
							<label class="col-sm-4 control-label" for="nuts">Tires/Lug Nuts/Pressure <small class="text-primary">(Llantas/Tuercas/Presion)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="nuts" id="nuts1" value=0 <?php if ($information && $information[0]["nuts"] == 0) {
																							echo "checked";
																						}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="nuts" id="nuts2" value=1 <?php if ($information && $information[0]["nuts"] == 1) {
																							echo "checked";
																						}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="nuts" id="nuts3" value=99 <?php if ($information && $information[0]["nuts"] == 99) {
																							echo "checked";
																						}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="glass">Glass (All) & Mirror <small class="text-primary">(Vidrios y espejos)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="glass" id="glass1" value=0 <?php if ($information && $information[0]["glass"] == 0) {
																								echo "checked";
																							}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="glass" id="glass2" value=1 <?php if ($information && $information[0]["glass"] == 1) {
																								echo "checked";
																							}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="glass" id="glass3" value=99 <?php if ($information && $information[0]["glass"] == 99) {
																								echo "checked";
																							}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="cleanExterior">Clean exterior <small class="text-primary">(Limpieza exterior)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="cleanExterior" id="cleanExterior1" value=0 <?php if ($information && $information[0]["clean_exterior"] == 0) {
																												echo "checked";
																											}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="cleanExterior" id="cleanExterior2" value=1 <?php if ($information && $information[0]["clean_exterior"] == 1) {
																												echo "checked";
																											}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="cleanExterior" id="cleanExterior3" value=99 <?php if ($information && $information[0]["clean_exterior"] == 99) {
																												echo "checked";
																											}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="wipers">Wipers/Washers <small class="text-primary">(Limpiaparabrisas )</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="wipers" id="wipers1" value=0 <?php if ($information && $information[0]["wipers"] == 0) {
																								echo "checked";
																							}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="wipers" id="wipers2" value=1 <?php if ($information && $information[0]["wipers"] == 1) {
																								echo "checked";
																							}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="wipers" id="wipers3" value=99 <?php if ($information && $information[0]["wipers"] == 99) {
																								echo "checked";
																							}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="backupBeeper">Backup Beeper <small class="text-primary">(Pito de reversa)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="backupBeeper" id="backupBeeper1" value=0 <?php if ($information && $information[0]["backup_beeper"] == 0) {
																											echo "checked";
																										}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="backupBeeper" id="backupBeeper2" value=1 <?php if ($information && $information[0]["backup_beeper"] == 1) {
																											echo "checked";
																										}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="backupBeeper" id="backupBeeper3" value=99 <?php if ($information && $information[0]["backup_beeper"] == 99) {
																											echo "checked";
																										}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="passengerDoor">Driver and Passenger door <small class="text-primary">(Puerta del pasajero)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="passengerDoor" id="passengerDoor1" value=0 <?php if ($information && $information[0]["passenger_door"] == 0) {
																												echo "checked";
																											}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="passengerDoor" id="passengerDoor2" value=1 <?php if ($information && $information[0]["passenger_door"] == 1) {
																												echo "checked";
																											}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="passengerDoor" id="passengerDoor3" value=99 <?php if ($information && $information[0]["passenger_door"] == 99) {
																												echo "checked";
																											}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="properDecals">Decals <small class="text-primary">(Calcomanías)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="properDecals" id="properDecals1" value=0 <?php if ($information && $information[0]["proper_decals"] == 0) {
																											echo "checked";
																										}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="properDecals" id="properDecals2" value=1 <?php if ($information && $information[0]["proper_decals"] == 1) {
																											echo "checked";
																										}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="properDecals" id="properDecals3" value=99 <?php if ($information && $information[0]["proper_decals"] == 99) {
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
						<strong>SERVICE</strong>
					</div>
					<div class="panel-body">

						<div class="form-group">
							<label class="col-sm-4 control-label" for="brakePedal">Brake pedal <small class="text-primary">(Pedal de freno)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="brakePedal" id="brakePedal1" value=0 <?php if ($information && $information[0]["brake_pedal"] == 0) {
																										echo "checked";
																									}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="brakePedal" id="brakePedal2" value=1 <?php if ($information && $information[0]["brake_pedal"] == 1) {
																										echo "checked";
																									}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="brakePedal" id="brakePedal3" value=99 <?php if ($information && $information[0]["brake_pedal"] == 99) {
																										echo "checked";
																									}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="emergencyBrake">Emergency brake <small class="text-primary">(Freno de emergencia)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="emergencyBrake" id="emergencyBrake1" value=0 <?php if ($information && $information[0]["emergency_brake"] == 0) {
																												echo "checked";
																											}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="emergencyBrake" id="emergencyBrake2" value=1 <?php if ($information && $information[0]["emergency_brake"] == 1) {
																												echo "checked";
																											}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="emergencyBrake" id="emergencyBrake3" value=99 <?php if ($information && $information[0]["emergency_brake"] == 99) {
																												echo "checked";
																											}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="gauges">Gauges: Volt/Fuel/Temp/Oil <small class="text-primary">(Indicadores: Voltios / Combustible / Temperatura / Aceite)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="gauges" id="gauges1" value=0 <?php if ($information && $information[0]["gauges"] == 0) {
																								echo "checked";
																							}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="gauges" id="gauges2" value=1 <?php if ($information && $information[0]["gauges"] == 1) {
																								echo "checked";
																							}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="gauges" id="gauges3" value=99 <?php if ($information && $information[0]["gauges"] == 99) {
																								echo "checked";
																							}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="horn"><?php echo $tituloHorn; ?> <small class="text-primary">(Pito)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="horn" id="horn1" value=0 <?php if ($information && $information[0]["horn"] == 0) {
																							echo "checked";
																						}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="horn" id="horn2" value=1 <?php if ($information && $information[0]["horn"] == 1) {
																							echo "checked";
																						}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="horn" id="horn3" value=99 <?php if ($information && $information[0]["horn"] == 99) {
																							echo "checked";
																						}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="seatbelts">Seatbelts <small class="text-primary">(Cinturon de seguridad)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="seatbelts" id="seatbelts1" value=0 <?php if ($information && $information[0]["seatbelts"] == 0) {
																										echo "checked";
																									}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="seatbelts" id="seatbelts2" value=1 <?php if ($information && $information[0]["seatbelts"] == 1) {
																										echo "checked";
																									}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="seatbelts" id="seatbelts3" value=99 <?php if ($information && $information[0]["seatbelts"] == 99) {
																										echo "checked";
																									}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="driverSeat">Driver & Passenger seat <small class="text-primary">(Asiento del conductor)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="driverSeat" id="driverSeat1" value=0 <?php if ($information && $information[0]["driver_seat"] == 0) {
																										echo "checked";
																									}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="driverSeat" id="driverSeat2" value=1 <?php if ($information && $information[0]["driver_seat"] == 1) {
																										echo "checked";
																									}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="driverSeat" id="driverSeat3" value=99 <?php if ($information && $information[0]["driver_seat"] == 99) {
																										echo "checked";
																									}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="insurance">Insurance information <small class="text-primary">(Información del seguro)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="insurance" id="insurance1" value=0 <?php if ($information && $information[0]["insurance"] == 0) {
																										echo "checked";
																									}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="insurance" id="insurance2" value=1 <?php if ($information && $information[0]["insurance"] == 1) {
																										echo "checked";
																									}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="insurance" id="insurance3" value=99 <?php if ($information && $information[0]["insurance"] == 99) {
																										echo "checked";
																									}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="registration">Registration <small class="text-primary">(Registro)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="registration" id="registration1" value=0 <?php if ($information && $information[0]["registration"] == 0) {
																											echo "checked";
																										}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="registration" id="registration2" value=1 <?php if ($information && $information[0]["registration"] == 1) {
																											echo "checked";
																										}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="registration" id="registration3" value=99 <?php if ($information && $information[0]["registration"] == 99) {
																											echo "checked";
																										}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="cleanInterior">Clean interior <small class="text-primary">(Limpieza interior)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="cleanInterior" id="cleanInterior1" value=0 <?php if ($information && $information[0]["clean_interior"] == 0) {
																												echo "checked";
																											}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="cleanInterior" id="cleanInterior2" value=1 <?php if ($information && $information[0]["clean_interior"] == 1) {
																												echo "checked";
																											}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="cleanInterior" id="cleanInterior3" value=99 <?php if ($information && $information[0]["clean_interior"] == 99) {
																												echo "checked";
																											}  ?>>N/A
								</label>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<?php
		//preguntar especiales para T001, T002, T003, para que muestre mensaje si es inseguro sacar el camion
		if ($idVehicle == 5 || $idVehicle == 11 || $idVehicle == 12) {
		?>

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
										<input type="radio" name="heater" id="heater1" value=0 <?php if ($information && $information[0]["heater"] == 0) {
																									echo "checked";
																								}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="heater" id="heater2" value=1 <?php if ($information && $information[0]["heater"] == 1) {
																									echo "checked";
																								}  ?>>Pass
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="steering_wheel">Steering wheel <small class="text-primary">(Volante)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="steering_wheel" id="steering_wheel1" value=0 <?php if ($information && $information[0]["steering_wheel"] == 0) {
																													echo "checked";
																												}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="steering_wheel" id="steering_wheel2" value=1 <?php if ($information && $information[0]["steering_wheel"] == 1) {
																													echo "checked";
																												}  ?>>Pass
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="suspension_system">Suspension system <small class="text-primary">(Sistema de suspensión)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="suspension_system" id="suspension_system1" value=0 <?php if ($information && $information[0]["suspension_system"] == 0) {
																															echo "checked";
																														}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="suspension_system" id="suspension_system2" value=1 <?php if ($information && $information[0]["suspension_system"] == 1) {
																															echo "checked";
																														}  ?>>Pass
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="air_brake">Air brake system <small class="text-primary">(Sistema de freno de aire)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="air_brake" id="air_brake1" value=0 <?php if ($information && $information[0]["air_brake"] == 0) {
																											echo "checked";
																										}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="air_brake" id="air_brake2" value=1 <?php if ($information && $information[0]["air_brake"] == 1) {
																											echo "checked";
																										}  ?>>Pass
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="fuel_system">Fuel system <small class="text-primary">(Sistema de combustible)</small></label>
								<div class="col-sm-5">
									<label class="radio-inline">
										<input type="radio" name="fuel_system" id="fuel_system1" value=0 <?php if ($information && $information[0]["fuel_system"] == 0) {
																												echo "checked";
																											}  ?>>Fail
									</label>
									<label class="radio-inline">
										<input type="radio" name="fuel_system" id="fuel_system2" value=1 <?php if ($information && $information[0]["fuel_system"] == 1) {
																												echo "checked";
																											}  ?>>Pass
									</label>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		<?php
		} else {
			echo '<input type="hidden" id="heater" name="heater" value=1 >';
			echo '<input type="hidden" id="steering_wheel" name="steering_wheel" value=1 >';
			echo '<input type="hidden" id="suspension_system" name="suspension_system" value=1 >';
			echo '<input type="hidden" id="air_brake" name="air_brake" value=1 >';
			echo '<input type="hidden" id="fuel_system" name="fuel_system" value=1 >';
		}
		?>


		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong>SAFETY</strong>
					</div>
					<div class="panel-body">

						<div class="form-group">
							<label class="col-sm-4 control-label" for="fireExtinguisher">Fire extinguisher <small class="text-primary">(Extintor de incendios)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="fireExtinguisher" id="fireExtinguisher1" value=0 <?php if ($information && $information[0]["fire_extinguisher"] == 0) {
																													echo "checked";
																												}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="fireExtinguisher" id="fireExtinguisher2" value=1 <?php if ($information && $information[0]["fire_extinguisher"] == 1) {
																													echo "checked";
																												}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="fireExtinguisher" id="fireExtinguisher3" value=99 <?php if ($information && $information[0]["fire_extinguisher"] == 99) {
																													echo "checked";
																												}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="firstAid">First Aid <small class="text-primary">(Kit de primeros auxilios)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="firstAid" id="firstAid1" value=0 <?php if ($information && $information[0]["first_aid"] == 0) {
																									echo "checked";
																								}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="firstAid" id="firstAid2" value=1 <?php if ($information && $information[0]["first_aid"] == 1) {
																									echo "checked";
																								}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="firstAid" id="firstAid3" value=99 <?php if ($information && $information[0]["first_aid"] == 99) {
																									echo "checked";
																								}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="emergencyReflectors">Emergency kit <small class="text-primary">(Kit de emergencia)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="emergencyReflectors" id="emergencyReflectors1" value=0 <?php if ($information && $information[0]["emergency_reflectors"] == 0) {
																															echo "checked";
																														}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="emergencyReflectors" id="emergencyReflectors2" value=1 <?php if ($information && $information[0]["emergency_reflectors"] == 1) {
																															echo "checked";
																														}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="emergencyReflectors" id="emergencyReflectors3" value=99 <?php if ($information && $information[0]["emergency_reflectors"] == 99) {
																															echo "checked";
																														}  ?>>N/A
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="spillKit">Spill Kit <small class="text-primary">(Kit de emergencia para derrames)</small></label>
							<div class="col-sm-5">
								<label class="radio-inline">
									<input type="radio" name="spillKit" id="spillKit1" value=0 <?php if ($information && $information[0]["spill_kit"] == 0) {
																									echo "checked";
																								}  ?>>Fail
								</label>
								<label class="radio-inline">
									<input type="radio" name="spillKit" id="spillKit2" value=1 <?php if ($information && $information[0]["spill_kit"] == 1) {
																									echo "checked";
																								}  ?>>Pass
								</label>
								<label class="radio-inline">
									<input type="radio" name="spillKit" id="spillKit3" value=99 <?php if ($information && $information[0]["spill_kit"] == 99) {
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
						<strong>COMMENTS</strong>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="comments">Comments <small class="text-primary">(Comentarios)</small></label>
							<div class="col-sm-5">
								<textarea id="comments" name="comments" placeholder="Comments" class="form-control" rows="3"><?php echo $information ? $information[0]["comments"] : ""; ?></textarea>
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
						<strong>TRAILER</strong>
					</div>
					<div class="panel-body">

						<div class="alert alert-danger ">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							If you are using a Tralier, you must fill out the following form.<br>
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							Si esta usando un Trailer, debe diligenciar el siguiente formulario.
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="trailer">Trailer </label>
							<div class="col-sm-5">
								<select name="trailer" id="trailer" class="form-control">
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($trailerList); $i++) { ?>
										<option value="<?php echo $trailerList[$i]["id_vehicle"]; ?>" <?php if ($information && $information[0]["fk_id_trailer"] == $trailerList[$i]["id_vehicle"]) {
																											echo "selected";
																										}  ?>><?php echo $trailerList[$i]["unit_number"] . ' -----> ' . $trailerList[$i]["description"]; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="trailerLights">Lights <small class="text-primary">(Luces)</small></label>
							<div class="col-sm-5">
								<select name="trailerLights" id="trailerLights" class="form-control">
									<option value="">Select...</option>
									<option value=2 <?php if ($information && $information[0]["trailer_lights"] == 2) {
														echo "selected";
													}  ?>>Fail</option>
									<option value=1 <?php if ($information && $information[0]["trailer_lights"] == 1) {
														echo "selected";
													}  ?>>Pass</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="trailerTires">Tires <small class="text-primary">(LLantas)</small></label>
							<div class="col-sm-5">
								<select name="trailerTires" id="trailerTires" class="form-control">
									<option value="">Select...</option>
									<option value=2 <?php if ($information && $information[0]["trailer_tires"] == 2) {
														echo "selected";
													}  ?>>Fail</option>
									<option value=1 <?php if ($information && $information[0]["trailer_tires"] == 1) {
														echo "selected";
													}  ?>>Pass</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="trailerClean">Clean <small class="text-primary">(Limpio)</small></label>
							<div class="col-sm-5">
								<select name="trailerClean" id="trailerClean" class="form-control">
									<option value="">Select...</option>
									<option value=2 <?php if ($information && $information[0]["trailer_clean"] == 2) {
														echo "selected";
													}  ?>>Fail</option>
									<option value=1 <?php if ($information && $information[0]["trailer_clean"] == 1) {
														echo "selected";
													}  ?>>Pass</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="trailerSlings">Slings <small class="text-primary">(Eslingas)</small></label>
							<div class="col-sm-5">
								<select name="trailerSlings" id="trailerSlings" class="form-control">
									<option value=''>Select...</option>
									<?php
									for ($i = 0; $i <= 10; $i++) {
									?>
										<option value='<?php echo $i; ?>' <?php
																			if ($information && $i == $information[0]["trailer_slings"]) {
																				echo 'selected="selected"';
																			}
																			?>><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="trailerChains">Chains <small class="text-primary">(Cadenas)</small></label>
							<div class="col-sm-5">
								<select name="trailerChains" id="trailerChains" class="form-control">
									<option value=''>Select...</option>
									<?php
									for ($i = 0; $i <= 10; $i++) {
									?>
										<option value='<?php echo $i; ?>' <?php
																			if ($information && $i == $information[0]["trailer_chains"]) {
																				echo 'selected="selected"';
																			}
																			?>><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="trailerRatchet">Ratchet <small class="text-primary">(Ratchet)</small></label>
							<div class="col-sm-5">
								<select name="trailerRatchet" id="trailerRatchet" class="form-control">
									<option value=''>Select...</option>
									<?php
									for ($i = 0; $i <= 10; $i++) {
									?>
										<option value='<?php echo $i; ?>' <?php
																			if ($information && $i == $information[0]["trailer_ratchet"]) {
																				echo 'selected="selected"';
																			}
																			?>><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="trailerComments">Comments <small class="text-primary">(Comentarios)</small></label>
							<div class="col-sm-5">
								<textarea id="trailerComments" name="trailerComments" placeholder="Comments" class="form-control" rows="3"><?php echo $information ? $information[0]["trailer_comments"] : ""; ?></textarea>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary">
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