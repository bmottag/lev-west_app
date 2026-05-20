<script type="text/javascript" src="<?php echo base_url("assets/js/validate/external/dayoff.js?v=1.0.0"); ?>"></script>
<script>
$(document).ready(function () {
    $('#status').change(function () {
        $('#status option:selected').each(function () {
            var status = $('#status').val();

            if ( status == 3 ) {
				$("#div_observation").css("display", "inline");
            } else {
				$("#div_observation").css("display", "none");
				$('#observation').val("");
			}
        });
    });    
});
</script>

<div id="page-wrapper">

	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-calendar fa-fw"></i> DAY OFF
				</div>
				<div class="panel-body">
					
					<?php if (session()->getFlashdata('retornoExito')){ ?>
						<div class="alert alert-success">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoExito') ?>
						</div>
    				<?php }else{ ?>
					<p class="text-danger text-left">Fields with * are required.</p>
					<form  name="form" id="form" class="form-horizontal" method="post" >
						<input type="hidden" id="hddIdDayOff" name="hddIdDayOff" value="<?php echo $idDayoff; ?>"/>
						<input type="hidden" id="hddIdUser" name="hddIdUser" value="<?php echo $idUser; ?>"/>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="firstName">Employee:</label>
							<div class="col-sm-2">
								<?php echo $dayOffInfo[0]["name"]; ?>
							</div>

							<label class="col-sm-2 control-label" for="lastName">Date of Issue:</label>
							<div class="col-sm-2">
								<?php echo $dayOffInfo[0]["date_issue"]; ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="firstName">Type:</label>
							<div class="col-sm-2">
								<?php 
									switch ($dayOffInfo[0]['id_type_dayoff']) {
										case 1:
											echo 'Family/medical appointment';
											break;
										case 2:
											echo 'Regular';
											break;
									}
								?>
							</div>

							<label class="col-sm-2 control-label" for="lastName">Date of day off: </label>
							<div class="col-sm-2">
								<?php echo $dayOffInfo[0]["date_dayoff"]; ?>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label" for="username">Observation:</label>
							<div class="col-sm-2">
								<?php echo $dayOffInfo[0]["observation"]; ?>
							</div>

							<label class="col-sm-2 control-label" for="birth">Actual Status:</label>
							<div class="col-sm-2">
							<?php
								switch ($dayOffInfo[0]['state']) {
									case 1:
											$valor = 'New Request';
											$clase = "text-primary";
											break;
									case 2:
											$valor = 'Approved';
											$clase = "text-success";
											break;
									case 3:
											$valor = 'Denied';
											$clase = "text-danger";
											break;
								}
								echo '<p class="' . $clase . '"><strong>' . $valor . '</strong></p>';
							?>
							</div>
						</div>

						<?php
							if ($dayOffInfo[0]['state'] == 1) {
						?>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="movilNumber">Status: *</label>
							<div class="col-sm-2">
								<select name="status" id="status" class="form-control" required >
									<option value="">Select...</option>
									<option value=2 >Approved</option>
									<option value=3 >Denied</option>
								</select>
							</div>

							<label class="col-sm-2 control-label" for="address">Observation: </label>
							<div class="col-sm-2" id="div_observation" style="display: none" >
								<textarea id="observation" name="observation" class="form-control" rows="3"></textarea>
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:60%;" align="center">
									<div id="div_cargando" style="display:none">		
										<div class="progress progress-striped active">
											<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
												<span class="sr-only">45% completado</span>
											</div>
										</div>
									</div>
									<div id="div_msj" style="display:none">			
										<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
									</div>
								</div>
							</div>
						</div>	
						
						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">							
									<button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-primary'>
											Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>
								</div>
							</div>
						</div>
						<?php } ?>

					</form>
<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>