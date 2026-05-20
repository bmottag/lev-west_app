<script type="text/javascript" src="<?php echo base_url('assets/js/validate/jobs/environmental.js?v=1.0.0'); ?>"></script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-purpura">
				<div class="panel-heading">
					<a class="btn btn-purpura btn-xs" href="<?php echo base_url('more/environmental/' . $jobInfo[0]['id_job']); ?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back</a>
					<i class="glyphicon glyphicon-screenshot"></i> <strong>ESI - ENVIROMENTAL SITE INSPECTION</strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-purpura">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo esc($jobInfo[0]['job_description']); ?>
						</h2>
						<?php if ($information): ?>
							<br><strong>Date review: </strong><?php echo esc($information[0]['date_environmental']); ?>
							<br><span class='fa fa-cloud-download' aria-hidden='true'></span> <strong>Download ESI: </strong>
							<a href='<?php echo base_url('more/generaEnvironmentalPDF/' . $jobInfo[0]['id_job']); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>'></a>
						<?php endif; ?>
					</div>

					<?php if (session()->getFlashdata('retornoExito')): ?>
					<div class="alert alert-success">
						<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						<?php echo session()->getFlashdata('retornoExito'); ?>
					</div>
					<?php endif; ?>
					<?php if (session()->getFlashdata('retornoError')): ?>
					<div class="alert alert-danger">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<?php echo session()->getFlashdata('retornoError'); ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php if ($information): ?>
		<div class="row">
			<div class="col-lg-6">
				<div class="panel panel-info">
					<div class="panel-heading"><strong>Site inspector: *</strong></div>
					<div class="panel-body">

						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $information[0]["inspector_signature"] ?? null,
							'formAction'      => base_url('more/add_signature_esi'),
							'height'          => 200,
							'signButtonText'  => ' VCI Inspector Signature ',
							'id' 			  => 'inspector',
							'extraValue' 	  => $information[0]["id_job_environmental"],
							'otherValue' 	  => 'inspector'
						])?>

					</div>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="panel panel-info">
					<div class="panel-heading"><strong>Manager: *</strong></div>
					<div class="panel-body">

						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $information[0]["manager_signature"] ?? null,
							'formAction'      => base_url('more/add_signature_esi'),
							'height'          => 200,
							'signButtonText'  => ' VCI Manager Signature ',
							'id' 			  => 'manager',
							'extraValue' 	  => $information[0]["id_job_environmental"],
							'otherValue' 	  => 'manager'
						])?>

					</div>
				</div>
			</div>
		</div>
	<?php endif ?>

	<form name="form" id="form" class="form-horizontal" method="post">
		<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information ? $information[0]['id_job_environmental'] : ''; ?>"/>
		<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]['id_job']; ?>"/>

		<?php if ($information): ?>

			<div class="row">
				<div class="col-lg-12">				
					<div class="panel panel-info">
						<div class="panel-heading">
							<strong>IN CHARGE</strong>
						</div>
						<div class="panel-body">								
								<div class="form-group">
									<label class="col-sm-4 control-label" for="manager">Site inspector: *</label>
									<div class="col-sm-5">
										<select name="inspector" id="inspector" class="form-control" required>
											<option value=''>Select...</option>
											<?php foreach ($workersList as $w): ?>
												<option value="<?php echo $w['id_user']; ?>" <?php echo ($information[0]['fk_id_user_inspector'] == $w['id_user']) ? 'selected' : ''; ?>><?php echo esc($w['first_name'] . ' ' . $w['last_name']); ?></option>
											<?php endforeach; ?>
										</select>							
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label" for="coordinator">Manager: *</label>
									<div class="col-sm-5">
										<select name="manager" id="manager" class="form-control" required>
											<option value=''>Select...</option>
											<?php foreach ($workersList as $w): ?>
												<option value="<?php echo $w['id_user']; ?>" <?php echo ($information[0]['fk_id_user_manager'] == $w['id_user']) ? 'selected' : ''; ?>><?php echo esc($w['first_name'] . ' ' . $w['last_name']); ?></option>
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
						<button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-primary'>
							Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
						</button>
					</div>
				</div>
			</div>
		<?php endif; ?>



		<?php
		$fields = [
			['section' => '1. Air pollution Control', 'items' => [
				['id' => 'sites_watered', 'label' => 'Are the construction sites watered to minimize dust?'],
				['id' => 'being_swept', 'label' => 'Are the main entrance and surrounding roads being swept?'],
				['id' => 'dusty_covered', 'label' => 'Are all vehicles carrying dusty loads covered?'],
				['id' => 'speed_control', 'label' => 'Are speed control measures applied?'],
			]],
			['section' => '2. Noise Control', 'items' => [
				['id' => 'noise_permit', 'label' => 'Is the construction noise permit valid?'],
				['id' => 'air_compressors', 'label' => 'Do air compressors operate with doors closed?'],
				['id' => 'noise_mitigation', 'label' => 'Any noise mitigation measures adopted'],
				['id' => 'idle_plan', 'label' => 'Is idle plan/equipment turned off or throttled down?'],
			]],
			['section' => '3. Site Management', 'items' => [
				['id' => 'garbage_bin', 'label' => 'Is there enough garbage bins on site?'],
				['id' => 'disposed_periodically', 'label' => 'Are garbage bins collected and disposed periodically?'],
				['id' => 'recycling_being', 'label' => 'Is recycling being followed and placed accordingly?'],
				['id' => 'spill_containment', 'label' => 'Is the spill containment workstation being implemented? Is It in good conditions?'],
				['id' => 'spillage_happen', 'label' => 'Did we have any spillage happen on site?'],
			]],
			['section' => '4. Storage of chemicals and Dangerous goods', 'items' => [
				['id' => 'chemicals_stored', 'label' => 'Are chemicals, fuel, oils, coolant, and hydraulic stored and labelled property?'],
				['id' => 'absorbing_chemical', 'label' => 'Are spill kits / sand / saw dust used for absorbing chemical spillage readily accessible?'],
				['id' => 'spill_kits', 'label' => 'Do all equipment, & trucks have spill kits?'],
			]],
			['section' => '5. Resource Conservation', 'items' => [
				['id' => 'excessive_use', 'label' => 'Are Diesel-powered plant and equipment shut off while not in use to reduce excessive use?'],
				['id' => 'materials_stored', 'label' => 'Are materials stored in good condition to prevent deterioration and wastage?'],
			]],
			['section' => '6. Emergency Preparedness and Response', 'items' => [
				['id' => 'fire_extinguishers', 'label' => 'Are fire extinguishers / fighting facilities properly maintained and not expired?'],
				['id' => 'preventive_actions', 'label' => 'Are accidents and incidents reported and reviewed, and corrective & preventive actions identified and recorded?'],
			]],
		];
		foreach ($fields as $section):
		?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-purpura">
					<div class="panel-heading"><strong><?php echo $section['section']; ?></strong></div>
					<div class="panel-body">
						<?php foreach ($section['items'] as $item): ?>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?php echo $item['label']; ?></label>
							<div class="col-sm-3">
								<label class="radio-inline"><input type="radio" name="<?php echo $item['id']; ?>" value="1" <?php echo ($information && $information[0][$item['id']] == 1) ? 'checked' : ''; ?>>Yes</label>
								<label class="radio-inline"><input type="radio" name="<?php echo $item['id']; ?>" value="2" <?php echo ($information && $information[0][$item['id']] == 2) ? 'checked' : ''; ?>>No</label>
								<label class="radio-inline"><input type="radio" name="<?php echo $item['id']; ?>" value="99" <?php echo ($information && $information[0][$item['id']] == 99) ? 'checked' : ''; ?>>N/A</label>
							</div>
							<div class="col-sm-5">
								<textarea id="<?php echo $item['id']; ?>_remarks" name="<?php echo $item['id']; ?>_remarks" placeholder="Remarks" class="form-control" rows="1"><?php echo $information ? esc($information[0][$item['id'] . '_remarks']) : ''; ?></textarea>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>

		<?php if (!$information): ?>
			<div class="form-group">
				<div class="row" align="center">
					<div style="width:100%;" align="center">
						<button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-primary'>
							Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
						</button>
					</div>
				</div>
			</div>
		<?php endif; ?>

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
</div>
