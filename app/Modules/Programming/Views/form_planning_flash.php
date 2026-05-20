<script type="text/javascript" src="<?php echo base_url("assets/js/validate/programming/planning_flash.js?v=1.0.0"); ?>"></script>


<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-flash"></i> <b>FLASH PLANNING</b>
				</div>
				<div class="panel-body">

					<form  name="form" id="form" class="form-horizontal" method="post" >
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_programming"]:""; ?>"/>
						<input type="hidden" id="date" name="date" value="<?php echo date("Y-m-d"); ?>"/>
																					
						<div class="alert alert-info">
							<strong>Info:</strong> Form to add or update a Planning.
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="jobName">Job Code/Name: *</label>
							<div class="col-sm-3">
                                <select name="jobName" id="jobName" class="form-control js-example-basic-single" required >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($jobs); $i++) { ?>
										<option value="<?php echo $jobs[$i]["id_job"]; ?>" <?php if($information && $information[0]["fk_id_job"] == $jobs[$i]["id_job"]) { echo "selected"; }  ?>><?php echo $jobs[$i]["job_description"]; ?></option>	
									<?php } ?>
								</select>
							</div>

							<label class="col-sm-2 control-label" for="worker">Worker: *</label>
							<div class="col-sm-3">
                                <select name="worker" id="worker" class="form-control js-example-basic-single" required>
                                    <option value=''>Select...</option>
                                    <?php for ($i = 0; $i < count($workersList); $i++) { ?>
                                        <option value="<?php echo $workersList[$i]["id_user"]; ?>" ><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
                                    <?php } ?>
                                </select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="jobName">Equipment: *</label>
							<div class="col-sm-3">
								<select name="machine" id="machine" class="form-control" >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($informationVehicles); $i++) { ?>
										<option value="<?php echo $informationVehicles[$i]["id_truck"]; ?>" ><?php echo $informationVehicles[$i]["unit_number"]; ?></option>	
									<?php } ?>
								</select>
							</div>

							<label class="col-sm-2 control-label" for="worker">Observation: </label>
							<div class="col-sm-3">
								<textarea id="observation" name="observation" class="form-control" rows="3"><?php echo $information?$information[0]["observation"]:""; ?></textarea>
							</div>
						</div>
								
						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">
                                    <button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-primary'>
                                            Submit <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
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
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('.js-example-basic-single').select2({
		width: '100%'
	});
});
</script>