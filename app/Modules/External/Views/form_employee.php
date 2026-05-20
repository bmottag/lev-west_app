<script type="text/javascript" src="<?php echo base_url("assets/js/validate/external/new_employee.js?v=4.0.0"); ?>"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
	$( function() {
		$( "#birth" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			yearRange: "-60:-15", // last 60 years
		});
	});
</script>
<div id="page-wrapper">

	<br>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-child fa-fw"></i> <b>NEW EMPLOYEE</b>
				</div>
				<div class="panel-body">
					
					<?php if (session()->getFlashdata('retornoExito')){ ?>
						<div class="alert alert-success">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoExito') ?>
						</div>
					<?php
						}else{
					?>
					<p class="text-danger text-left">Fields with * are required.</p>
					<form  name="form" id="form" class="form-horizontal" method="post" >
					
						<div class="form-group">
							<label class="col-sm-3 control-label" for="firstName">First name: *</label>
							<div class="col-sm-2">
								<input type="text" id="firstName" name="firstName" class="form-control" placeholder="First Name" required />
							</div>

							<label class="col-sm-2 control-label" for="lastName">Last name: *</label>
							<div class="col-sm-2">
								<input type="text" id="lastName" name="lastName" class="form-control" placeholder="Last Name" required />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="firstName">Email: *</label>
							<div class="col-sm-2">
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
							</div>

							<label class="col-sm-2 control-label" for="lastName">Confirm email: *</label>
							<div class="col-sm-2">
								<input type="email" class="form-control" id="confirmEmail" name="confirmEmail" placeholder="Email" required />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label" for="username">User name: *</label>
							<div class="col-sm-2">
								<input type="text" id="user" name="user" class="form-control" placeholder="User Name" required />
							</div>

							<label class="col-sm-2 control-label" for="birth">Date of birth: *</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="birth" name="birth" placeholder="Date of birth" required />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="inputPassword">Password: *</label>
							<div class="col-sm-2">
								<input type="text" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required />
							</div>

							<label class="col-sm-2 control-label" for="inputConfirm">Confirm Password: *</label>
							<div class="col-sm-2">
								<input type="text" id="inputConfirm" name="inputConfirm" class="form-control" placeholder="Password" required />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="insuranceNumber">Social Insurance Number: *</label>
							<div class="col-sm-2">
								<input type="text" id="insuranceNumber" name="insuranceNumber" class="form-control" placeholder="Social Insurance Number" required >
							</div>

							<label class="col-sm-2 control-label" for="healthNumber">Health number: *</label>
							<div class="col-sm-2">
								<input type="text" id="healthNumber" name="healthNumber" class="form-control" placeholder="Health Number" required />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="movilNumber">Mobile number: *</label>
							<div class="col-sm-2">
								<input type="text" id="movilNumber" name="movilNumber" class="form-control" placeholder="Mobile Number" required />
							</div>

							<label class="col-sm-2 control-label" for="address">Address: </label>
							<div class="col-sm-2">
								<input type="text" id="address" name="address" class="form-control" placeholder="Address" />
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:80%;" align="center">
									<div id="div_cargando" style="display:none">		
										<div class="progress progress-striped active">
											<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
												<span class="sr-only">45% completado</span>
											</div>
										</div>
									</div>
									<div id="div_error" style="display:none">			
										<div class="alert alert-danger">
											<span class="glyphicon glyphicon-remove"></span>
											<span id="span_msj"></span>
										</div>	
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

					</form>
<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>