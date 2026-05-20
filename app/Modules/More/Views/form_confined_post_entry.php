<script type="text/javascript" src="<?php echo base_url('assets/js/validate/more/post_entry.js?v=1.0.0'); ?>"></script>

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
						<li><a href="<?php echo base_url('more/add_confined/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">FORM</a></li>
						<li><a href="<?php echo base_url('more/confined_workers/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">ENTRANT(S)</a></li>
						<li><a href="<?php echo base_url('more/workers_site/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">WORKERS ON SITE</a></li>
						<li><a href="<?php echo base_url('more/re_testing/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">ENVIRONMENTAL CONDITIONS - RE-TESTING</a></li>
						<li class="active"><a href="<?php echo base_url('more/post_entry/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">POST ENTRY INSPECTION</a></li>
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
		<input type="hidden" id="hddConfined" name="hddConfined" value="<?php echo $information ? esc($information[0]['id_job_confined']) : ''; ?>"/>
		<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo esc($jobInfo[0]['id_job']); ?>"/>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-info">
					<div class="panel-heading"><strong>Post-entry Inspection</strong></div>
					<div class="panel-body">
						<?php
						$postFields = [
							['name' => 'personnel_out',     'label' => 'Are all personnel out of the confined space and accounted for?',                                                                     'options' => [1 => 'Yes', 2 => 'No']],
							['name' => 'isolation',         'label' => 'Have isolation devices been removed and pipes been restored to their original positions?',                                            'options' => [1 => 'Yes', 2 => 'No', 3 => 'N/A']],
							['name' => 'lockouts_removed',  'label' => 'Have all lockouts been removed?',                                                                                                    'options' => [1 => 'Yes', 2 => 'No', 3 => 'N/A']],
							['name' => 'tags_removed',      'label' => 'Have all safe entry tags and sings been removed?',                                                                                   'options' => [1 => 'Yes', 2 => 'No', 3 => 'N/A']],
							['name' => 'equipment_removed', 'label' => 'Have all equipment and waste been removed from the work area?',                                                                      'options' => [1 => 'Yes', 2 => 'No']],
							['name' => 'ppe_cleaned',       'label' => 'Has all specialized PPE been cleaned, post-inspected and put away?',                                                                 'options' => [1 => 'Yes', 2 => 'No']],
							['name' => 'rescue_equipment',  'label' => 'Has all rescue equipment been post-inspected, cleaned and stored (If Applicable)?',                                                  'options' => [1 => 'Yes', 2 => 'No', 3 => 'N/A']],
							['name' => 'permits_signed',    'label' => 'Have all permits been signed out and filed properly?',                                                                               'options' => [1 => 'Yes', 2 => 'No']],
							['name' => 'areas_notified',    'label' => 'Have other applicable areas of the facility been notified that the work in the confined space is complete and operations are ready to be resumed?', 'options' => [1 => 'Yes', 2 => 'No', 3 => 'N/A']],
						];
						foreach ($postFields as $field):
						?>
						<div class="form-group">
							<label class="col-sm-7 control-label"><?php echo $field['label']; ?></label>
							<div class="col-sm-2">
								<select name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" class="form-control" required>
									<option value="">Select...</option>
									<?php foreach ($field['options'] as $val => $lbl): ?>
									<option value="<?php echo $val; ?>" <?php echo ($information && $information[0][$field['name']] == $val) ? 'selected' : ''; ?>><?php echo $lbl; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<a name="anclaSignature"></a>
						<strong>Post-entry Check done By: *</strong>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-sm-12">
								<select name="post_entry" id="post_entry" class="form-control">
									<option value=''>Select...</option>
									<?php foreach ($workersList as $w): ?>
									<option value="<?php echo $w['id_user']; ?>" <?php echo ($information && $information[0]['fk_id_post_entry_user'] == $w['id_user']) ? 'selected' : ''; ?>><?php echo esc($w['first_name'] . ' ' . $w['last_name']); ?></option>
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

	<?php if ($information && $information[0]['fk_id_post_entry_user']): ?>
		<div class="form-group">

			<?= view('App\Views\template\signature_component', [
				'imageUrl'        => $information[0]['post_entry_signature'] ?? null,
				'formAction'      => base_url('more/save_signature_confined'),
				'height'          => 200,
				'signButtonText'  => $information[0]['name_post_entry'] . ' Signature',
				'id' 			  => 'post_entry',
				'extraValue' 	  => $information[0]["id_job_confined"],
				'otherValue' 	  => 'post_entry'
			])?>
			
		</div>
	<?php endif; ?>

</div>
