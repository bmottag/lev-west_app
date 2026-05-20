<script type="text/javascript" src="<?php echo base_url('assets/js/validate/more/confined.js?v=2.0.0'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/timepicker/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/timepicker/bootstrap-datetimepicker.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/timepicker/bootstrap-datetimepicker.min.css'); ?>"/>
<?php $userRol = session()->get('rol'); ?>
<?php if ($userRol == 99): ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php endif; ?>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<a class="btn btn-warning btn-xs" href="<?php echo base_url('more/confined/' . $jobInfo[0]['id_job']); ?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back</a>
					<i class="fa fa-cube"></i> <strong>CONFINED SPACE ENTRY PERMIT FORM</strong>
				</div>
				<div class="panel-body">
					<?php if ($information): ?>
					<ul class="nav nav-pills">
						<li class="active"><a href="<?php echo base_url('more/add_confined/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">FORM</a></li>
						<li><a href="<?php echo base_url('more/confined_workers/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">ENTRANT(S)</a></li>
						<li><a href="<?php echo base_url('more/workers_site/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">WORKERS ON SITE</a></li>
						<li><a href="<?php echo base_url('more/rescue_plan/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">ON-SITE RESCUE PLAN</a></li>
						<li><a href="<?php echo base_url('more/re_testing/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">ENVIRONMENTAL CONDITIONS - Re-Testing</a></li>
						<li><a href="<?php echo base_url('more/post_entry/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">POST ENTRY INSPECTION</a></li>
					</ul>
					<br>
					<?php endif; ?>

					<div class="alert alert-warning">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo esc($jobInfo[0]['job_description']); ?>
						</h2>
						<br><span class="fa fa-clock-o" aria-hidden="true"></span> <strong>Date: </strong>
						<?php if ($information): ?>
							<?php echo esc($information[0]['date_confined']); ?>
							<br><span class="fa fa-cloud-download" aria-hidden="true"></span> <strong>Download Confined Entry Permit Form: </strong>
							<a href="<?php echo base_url('more/generaConfinedPDF/' . $information[0]['id_job_confined']); ?>" target="_blank">PDF <img src="<?php echo base_url('images/pdf.png'); ?>"></a>
						<?php else: ?>
							<?php echo date('Y-m-d'); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if (session()->getFlashdata('retornoExito')): ?>
	<div class="col-lg-12">
		<div class="alert alert-success">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<?php echo session()->getFlashdata('retornoExito'); ?>
		</div>
	</div>
	<?php endif; ?>
	<?php if (session()->getFlashdata('retornoError')): ?>
	<div class="col-lg-12">
		<div class="alert alert-danger">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<?php echo session()->getFlashdata('retornoError'); ?>
		</div>
	</div>
	<?php endif; ?>

	<p class="text-danger text-left">Fields with * are required.</p>

	<form name="form" id="form" class="form-horizontal" method="post">
		<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information ? esc($information[0]['id_job_confined']) : ''; ?>"/>
		<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo esc($jobInfo[0]['id_job']); ?>"/>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<div class="row">
							<div class="col-sm-6">
								<input type="checkbox" id="completed_flha" name="completed_flha" value="1" <?php echo ($information && $information[0]['completed_flha']) ? 'checked' : ''; ?>> I Have completed a Field Level Hazard Assessment.
								<br><br>
								<strong>Location: *</strong>
							</div>
							<?php if ($userRol == 99): ?>
							<script>$(function() { $('#date').datepicker({ changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' }); });</script>
							<div class="col-sm-6">
								<br><br>
								<label class="col-sm-4 control-label">Date of Issue:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="date" name="date" value="<?php echo $information ? esc($information[0]['date_confined']) : ''; ?>" placeholder="Date of Issue"/>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-sm-12">
								<textarea id="location" name="location" class="form-control" rows="2"><?php echo $information ? esc($information[0]['location']) : ''; ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading"><strong>Purpose of entry: *</strong></div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-sm-12">
								<textarea id="purpose" name="purpose" class="form-control" rows="2"><?php echo $information ? esc($information[0]['purpose']) : ''; ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		if ($information) {
			$inicio = $information[0]['scheduled_start'];
			$fechaInicio   = substr($inicio, 0, 10);
			$horaInicio    = substr($inicio, 11, 2);
			$minutosInicio = substr($inicio, 14, 2);
			$fin = $information[0]['scheduled_finish'];
			$fechaFin   = substr($fin, 0, 10);
			$horaFin    = substr($fin, 11, 2);
			$minutosFin = substr($fin, 14, 2);
		} else {
			$fechaInicio = $horaInicio = $minutosInicio = $fechaFin = $horaFin = $minutosFin = '';
		}
		?>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading"><strong>Scheduled: *</strong></div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-sm-4">
								<script>$(function() { $('#start_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' }); });</script>
								<label class="control-label">Start date: *</label>
								<input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo $fechaInicio; ?>" placeholder="Start date" required/>
							</div>
							<div class="col-sm-4">
								<label class="control-label">Start hour: *</label>
								<select name="start_hour" id="start_hour" class="form-control" required>
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < 24; $i++): $h = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
									<option value="<?php echo $h; ?>" <?php echo ($information && $h == $horaInicio) ? 'selected' : ''; ?>><?php echo $h; ?></option>
									<?php endfor; ?>
								</select>
							</div>
							<div class="col-sm-4">
								<label class="control-label">Start minutes: *</label>
								<select name="start_min" id="start_min" class="form-control" required>
									<?php for ($i = 0; $i < 60; $i++): $m = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
									<option value="<?php echo $m; ?>" <?php echo ($information && $m == $minutosInicio) ? 'selected' : ''; ?>><?php echo $m; ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4">
								<script>$(function() { $('#finish_date').datepicker({ changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' }); });</script>
								<label class="control-label">Finish date: *</label>
								<input type="text" class="form-control" id="finish_date" name="finish_date" value="<?php echo $fechaFin; ?>" placeholder="Finish date" required/>
							</div>
							<div class="col-sm-4">
								<label class="control-label">Finish hour: *</label>
								<select name="finish_hour" id="finish_hour" class="form-control" required>
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < 24; $i++): $h = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
									<option value="<?php echo $h; ?>" <?php echo ($information && $h == $horaFin) ? 'selected' : ''; ?>><?php echo $h; ?></option>
									<?php endfor; ?>
								</select>
							</div>
							<div class="col-sm-4">
								<label class="control-label">Finish minutes: *</label>
								<select name="finish_min" id="finish_min" class="form-control" required>
									<?php for ($i = 0; $i < 60; $i++): $m = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
									<option value="<?php echo $m; ?>" <?php echo ($information && $m == $minutosFin) ? 'selected' : ''; ?>><?php echo $m; ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-danger">
					<span class="glyphicon glyphicon-exclamation-sign"></span> <b>Oxygen (Acceptable Level)</b> 19.5 % - 22 %<br>
					<span class="glyphicon glyphicon-exclamation-sign"></span> <b>Carbon Monoxide (Ocupational Exposure Limit)</b> 25 ppm<br>
					<span class="glyphicon glyphicon-exclamation-sign"></span> <b>Hydrogen Sulphide (Ocupational Exposure Limit)</b> 10 ppm
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading"><strong>Pre-Entry Authorization</strong></div>
					<div class="panel-body">
						<p class="text-info text-left">Check those items below which are applicable to your confined space entry permit</p>
						<?php
						$checkboxGroups = [
							[
								['id' => 'oxygen_deficient',    'label' => 'Oxygen-Deficient Atmosphere'],
								['id' => 'oxygen_enriched',     'label' => 'Oxygen-Enriched Atmosphere'],
								['id' => 'welding',             'label' => 'Welding/cutting'],
							],
							[
								['id' => 'engulfment',          'label' => 'Engulfment'],
								['id' => 'toxic_atmosphere',    'label' => 'Toxic Atmosphere'],
								['id' => 'flammable_atmosphere','label' => 'Flammable Atmosphere'],
							],
							[
								['id' => 'energized_equipment', 'label' => 'Energized Electric Equipment'],
								['id' => 'entrapment',          'label' => 'Entrapment'],
								['id' => 'hazardous_chemical',  'label' => 'Hazardous Chemical'],
							],
						];
						foreach ($checkboxGroups as $group):
						?>
						<div class="col-lg-4">
							<div class="form-group">
								<?php foreach ($group as $cb): ?>
								<input type="checkbox" id="<?php echo $cb['id']; ?>" name="<?php echo $cb['id']; ?>" value="1" <?php echo ($information && $information[0][$cb['id']]) ? 'checked' : ''; ?>>
								<?php echo $cb['label']; ?><br>
								<?php endforeach; ?>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading"><strong>SAFETY PRECAUTIONS</strong></div>
					<div class="panel-body">
						<p class="text-info text-left">Check those items below which are applicable to your confined space entry permit</p>
						<?php
						$safetyGroups = [
							[
								['id' => 'breathing_apparatus', 'label' => 'Self-Contained Breathing Apparatus'],
								['id' => 'line_respirator',     'label' => 'Air-Line Respirator'],
								['id' => 'resistant_clothing',  'label' => 'Flame Resistant Clothing'],
								['id' => 'ventilation',         'label' => 'Ventilation'],
								['id' => 'protective_gloves',   'label' => 'Protective Gloves'],
							],
							[
								['id' => 'linelines',           'label' => 'Linelines'],
								['id' => 'respirators',         'label' => 'Respirators'],
								['id' => 'lockout',             'label' => 'Lockout/Tagout'],
								['id' => 'fire_extinguishers',  'label' => 'Fire Extinguishers'],
								['id' => 'barricade',           'label' => 'Barricade Job Area'],
							],
							[
								['id' => 'signs_posted',        'label' => 'Signs Posted'],
								['id' => 'clearance_secured',   'label' => 'Clearance Secured'],
								['id' => 'lighting',            'label' => 'Lighting'],
								['id' => 'interrupter',         'label' => 'Ground Fault Interrupter'],
							],
						];
						foreach ($safetyGroups as $group):
						?>
						<div class="col-lg-4">
							<div class="form-group">
								<?php foreach ($group as $cb): ?>
								<input type="checkbox" id="<?php echo $cb['id']; ?>" name="<?php echo $cb['id']; ?>" value="1" <?php echo ($information && $information[0][$cb['id']]) ? 'checked' : ''; ?>>
								<?php echo $cb['label']; ?><br>
								<?php endforeach; ?>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading"><strong>ENVIRONMENTAL CONDITIONS - Test to be taken: *</strong></div>
					<div class="panel-body">
						<div class="col-sm-6">
							<label class="control-label">Oxygen (%):</label>
							<input type="number" step="any" id="oxygen" name="oxygen" class="form-control" value="<?php echo $information ? esc($information[0]['oxygen']) : ''; ?>" placeholder="Oxygen">
						</div>
						<div class="col-sm-6">
							<label class="control-label">Date/Time:</label>
							<div class="input-group date" id="datetimepicker1">
								<input type="text" id="oxygen_time" name="oxygen_time" class="form-control" value="<?php echo $information ? esc($information[0]['oxygen_time']) : ''; ?>" placeholder="YYYY-MM-DD HH:mm:ss"/>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
						<script>$(function() { $('#datetimepicker1').datetimepicker({ format: 'YYYY-MM-DD HH:mm:ss' }); });</script>
						<div class="col-sm-6">
							<label class="control-label">Lower Explosive Limit (%):</label>
							<input type="number" step="any" id="explosive_limit" name="explosive_limit" class="form-control" value="<?php echo $information ? esc($information[0]['explosive_limit']) : ''; ?>" placeholder="Lower Explosive Limit">
						</div>
						<div class="col-sm-6">
							<label class="control-label">Date/Time:</label>
							<div class="input-group date" id="datetimepicker2">
								<input type="text" id="explosive_limit_time" name="explosive_limit_time" class="form-control" value="<?php echo $information ? esc($information[0]['explosive_limit_time']) : ''; ?>" placeholder="YYYY-MM-DD HH:mm:ss"/>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						</div>
						<script>$(function() { $('#datetimepicker2').datetimepicker({ format: 'YYYY-MM-DD HH:mm:ss' }); });</script>
						<div class="col-sm-6">
							<label class="control-label">Toxic Atmosphere:</label>
							<input type="text" id="toxic_atmosphere_cond" name="toxic_atmosphere_cond" class="form-control" value="<?php echo $information ? esc($information[0]['toxic_atmosphere_cond']) : ''; ?>" placeholder="Toxic Atmosphere">
						</div>
						<div class="col-sm-6">
							<label class="control-label">Instruments Used:</label>
							<input type="text" id="instruments_used" name="instruments_used" class="form-control" value="<?php echo $information ? esc($information[0]['instruments_used']) : ''; ?>" placeholder="Instruments Used">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-warning">
					<div class="panel-heading"><strong>Remarks on the overall condition of the confined space:</strong></div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-sm-12">
								<textarea id="remarks" name="remarks" class="form-control" rows="2"><?php echo $information ? esc($information[0]['remarks']) : ''; ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<div class="panel panel-info">
					<div class="panel-heading">
						<a name="anclaSignature"></a>
						<strong>ENTRY AUTHORIZATION: *</strong>
						<br>All actions and/or conditions for safety entry have been performed.
						<br>Person in charge of entry:
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-sm-12">
								<select name="authorization" id="authorization" class="form-control">
									<option value=''>Select...</option>
									<?php foreach ($workersList as $w): ?>
									<option value="<?php echo $w['id_user']; ?>" <?php echo ($information && $information[0]['fk_id_user_authorization'] == $w['id_user']) ? 'selected' : ''; ?>><?php echo esc($w['first_name'] . ' ' . $w['last_name']); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="panel panel-info">
					<div class="panel-heading">
						<strong>ENTRY CANCELLATION: *</strong>
						<br>Entry has been completed and all entrants have left the space.
						<br>Person in charge of entry:
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-sm-12">
								<select name="cancellation" id="cancellation" class="form-control">
									<option value=''>Select...</option>
									<?php foreach ($workersList as $w): ?>
									<option value="<?php echo $w['id_user']; ?>" <?php echo ($information && $information[0]['fk_id_user_cancellation'] == $w['id_user']) ? 'selected' : ''; ?>><?php echo esc($w['first_name'] . ' ' . $w['last_name']); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:100%;" align="center">
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-primary">
						Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:80%;" align="center">
					<div id="div_load" style="display:none">
						<div class="progress progress-striped active">
							<div class="progress-bar" role="progressbar" style="width: 45%"><span class="sr-only">45%</span></div>
						</div>
					</div>
					<div id="div_error" style="display:none">
						<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="row">

		<?php if ($information && $information[0]['fk_id_user_authorization']): ?>
			<div class="col-lg-6">
				<div class="panel panel-info">
					<div class="panel-heading">
						<a name="anclaSignature"></a>
						<strong>ENTRY AUTHORIZATION: *</strong>
						<br>All actions and/or conditions for safety entry have been performed.
						<br>Person in charge of entry:
					</div>
					<div class="panel-body">
						<div class="form-group">

							<?= view('App\Views\template\signature_component', [
								'imageUrl'        => $information[0]['authorization_signature'] ?? null,
								'formAction'      => base_url('more/save_signature_confined'),
								'height'          => 200,
								'signButtonText'  => $information[0]['user_authorization'] . ' Signature',
								'id' 			  => 'authorization',
								'extraValue' 	  => $information[0]["id_job_confined"],
								'otherValue' 	  => 'authorization'
							])?>

						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($information && $information[0]['fk_id_user_cancellation']): ?>
			<div class="col-lg-6">
				<div class="panel panel-info">
					<div class="panel-heading">
						<strong>ENTRY CANCELLATION: *</strong>
						<br>Entry has been completed and all entrants have left the space.
						<br>Person in charge of entry:
					</div>
					<div class="panel-body">
						<div class="form-group">

							<?= view('App\Views\template\signature_component', [
								'imageUrl'        => $information[0]['cancellation_signature'] ?? null,
								'formAction'      => base_url('more/save_signature_confined'),
								'height'          => 200,
								'signButtonText'  => $information[0]['user_cancellation'] . ' Signature',
								'id' 			  => 'cancellation',
								'extraValue' 	  => $information[0]["id_job_confined"],
								'otherValue' 	  => 'cancellation'
							])?>

						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>

</div>
