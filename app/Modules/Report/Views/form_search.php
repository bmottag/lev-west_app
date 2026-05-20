<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/report.js"); ?>"></script>

<script>
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});
</script>

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
			</div>
			
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $titulo; ?>
                        </div>
                        <div class="panel-body">
									<form  name="form" id="form" role="form" method="post" class="form-horizontal" >

<!-- INICIO FILTRO POR EMPLEADO PARA PAYROLL CON USUARIO DE ADMINISTRADOR -->
									<?php if($workersList){ ?>
										<div class="form-group">
											<div class="col-sm-5 col-sm-offset-1">
												<label for="from">Employee <small>(This field is NOT required.)</small></label>
												<select name="employee" id="employee" class="form-control js-example-basic-single" >
													<option value=''>Select...</option>
													<?php for ($i = 0; $i < count($workersList); $i++) { ?>
														<option value="<?php echo $workersList[$i]["id_user"]; ?>"><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
													<?php } ?>
												</select>
											</div>
										</div>
									<?php }else{
										//solo se usa para los reportes de payroll cuando los busca el mismo empleado
										$idUser = session()->get('id');
									?>
										<input type="hidden" id="employee" name="employee" value="<?php echo $idUser; ?>"/>
									<?php } ?>
<!-- FIN FILTRO POR EMPLEADO PARA PAYROLL CON USUARIO DE ADMINISTRADOR -->

<!-- INICIO FILTRO POR VEHICULO PARA INSPECTION -->
									<?php if($vehicleList){
											$note = '<small class="danger">(This field is NOT required.)</small>';
											if($vehicleRequired){
												$note = '*';
											}
									?>
										<div class="form-group">
											<div class="col-sm-5 col-sm-offset-1">
												<label for="vehicleId">Vehicle <?php echo $note; ?></label>
												<select name="vehicleId" id="vehicleId" class="form-control" <?php echo $vehicleRequired; ?>>
													<option value=''>Select...</option>
													<?php for ($i = 0; $i < count($vehicleList); $i++) { ?>
														<option value="<?php echo $vehicleList[$i]["id_vehicle"]; ?>" ><?php echo $vehicleList[$i]["unit_number"] . ' -----> ' . $vehicleList[$i]["description"]; ?></option>	
													<?php } ?>
												</select>
											</div>
									<?php if($trailerList){ ?>		
											<div class="col-sm-5">
												<label for="trailerId">Trailer <small class="danger">(This field is NOT required.)</small></label>
												<select name="trailerId" id="trailerId" class="form-control" >
													<option value=''>Select...</option>
													<?php for ($i = 0; $i < count($trailerList); $i++) { ?>
														<option value="<?php echo $trailerList[$i]["id_vehicle"]; ?>" ><?php echo $trailerList[$i]["unit_number"] . ' -----> ' . $trailerList[$i]["description"]; ?></option>
													<?php } ?>
												</select>
											</div>
									<?php } ?>
										</div>
									<?php } ?>
<!-- FIN FILTRO POR  VEHICULO PARA INSPECTION -->

<!-- INICIO FILTRO POR COMPANY PARA HAULING -->
									<?php if($companyList){ ?>
										<div class="form-group">
											<div class="col-sm-5 col-sm-offset-1">
												<label for="from">Hauling done by <small>(This field is NOT required.)</small></label>
												<select name="company" id="company" class="form-control js-example-basic-single" >
													<option value=''>Select...</option>
													<?php for ($i = 0; $i < count($companyList); $i++) { ?>
														<option value="<?php echo $companyList[$i]["id_company"]; ?>" ><?php echo $companyList[$i]["company_name"]; ?></option>	
													<?php } ?>
												</select>
											</div>
									<?php if($materialList){ ?>		
											<div class="col-sm-5">
												<label for="material">Material <small class="danger">(This field is NOT required.)</small></label>
												<select name="material" id="material" class="form-control js-example-basic-single" >
													<option value=''>Select...</option>
													<?php for ($i = 0; $i < count($materialList); $i++) { ?>
														<option value="<?php echo $materialList[$i]["id_material"]; ?>" ><?php echo $materialList[$i]["material"]; ?></option>	
													<?php } ?>
												</select>
											</div>
									<?php } ?>
										</div>
									<?php } ?>
<!-- FIN FILTRO POR COMPANY PARA HAULING -->

<!-- INICIO FILTRO POR COMPANY PARA WORK ORDER -->
									<?php if($jobList){ ?>
										<div class="form-group">
											<div class="col-sm-5 col-sm-offset-1">
												<label for="from">Job Code/Name <small>(This field is NOT required.)</small></label>
												<select name="jobName" id="jobName" class="form-control js-example-basic-single" >
													<option value=''>Select...</option>
													<?php for ($i = 0; $i < count($jobList); $i++) { ?>
														<option value="<?php echo $jobList[$i]["id_job"]; ?>" ><?php echo $jobList[$i]["job_description"]; ?></option>	
													<?php } ?>
												</select>
											</div>
											
									<?php if($truckList){ ?>		
											<div class="col-sm-5">
												<label for="truck">Truck <small class="danger">(This field is NOT required.)</small></label>
												<select name="truck" id="truck" class="form-control" >
													<option value=''>Select...</option>
													<?php for ($i = 0; $i < count($truckList); $i++) { ?>
														<option value="<?php echo $truckList[$i]["id_vehicle"]; ?>" ><?php echo $truckList[$i]["unit_description"]; ?></option>	
													<?php } ?>
												</select>
											</div>
									<?php } ?>
											
										</div>
									<?php } ?>
<!-- FIN FILTRO POR COMPANY PARA WORK ORDER -->

                                        <div class="form-group">
<script>
	$( function() {
		var dateFormat = "mm/dd/yy",
		from = $( "#from" )
		.datepicker({
			changeMonth: true,
			numberOfMonths: 2
		})
		.on( "change", function() {
			to.datepicker( "option", "minDate", getDate( this ) );
		}),
		to = $( "#to" ).datepicker({
			changeMonth: true,
			numberOfMonths: 2
		})
		.on( "change", function() {
			from.datepicker( "option", "maxDate", getDate( this ) );
		});

		function getDate( element ) {
			var date;
			try {
				date = $.datepicker.parseDate( dateFormat, element.value );
			} catch( error ) {
				date = null;
			}

			return date;
		}
	});
</script>

											<div class="col-sm-5 col-sm-offset-1">
												<label for="from">From Date</label>
												<input type="text" id="from" name="from" class="form-control" placeholder="From" required >
											</div>
											<div class="col-sm-5">
												<label for="to">To Date</label>
												<input type="text" id="to" name="to" class="form-control" placeholder="To" required >
											</div>
                                        </div>

										<div class="row">
											<div class="col-lg-12">
												<div class="alert alert-info">
													<strong>Note:</strong> 
													Select the date range to search your records.
												</div>
											</div>
										</div>

<div class="row"></div><br>
										<div class="form-group">
											<div class="row" align="center">
												<div style="width80%;" align="center">
													
												 <button type="submit" class="btn btn-primary" id='btnSubmit' name='btnSubmit'><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Go </button>
													
												</div>
											</div>
										</div>
										
                                    </form>

								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>