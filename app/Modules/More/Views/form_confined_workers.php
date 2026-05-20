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
						<li class="active"><a href="<?php echo base_url('more/confined_workers/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">ENTRANT(S)</a></li>
						<li><a href="<?php echo base_url('more/workers_site/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">WORKERS ON SITE</a></li>
						<li><a href="<?php echo base_url('more/re_testing/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined']); ?>">ENVIRONMENTAL CONDITIONS - RE-TESTING</a></li>
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

	<?php if ($information): ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<a name="anclaWorker"></a><strong>Worker(s) in charge of entry:</strong>
				</div>
				<div class="panel-body">
					<div class="col-lg-12">
						<?php if ($confinedWorkers): ?>
						<button type="button" class="btn btn-info btn-lg btn-block" data-toggle="modal" data-target="#modalWorker" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Lev West Workers
						</button>
						<?php else: ?>
						<a href="<?php echo base_url('more/add_workers_confined/' . $jobInfo[0]['id_job'] . '/' . $information[0]['id_job_confined'] . '/2'); ?>" class="btn btn-info btn-lg btn-block">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Lev West Workers
						</a>
						<?php endif; ?>
						<br>
					</div>

					<?php if ($confinedWorkers): ?>
					<table class="table table-bordered table-striped table-hover table-condensed">
						<tr>
							<th class="text-center"><small>Name</small></th>
							<th class="text-center"><small>Signature In</small></th>
							<th class="text-center"><small>Signature Out</small></th>
							<th class="text-center"><small>Task</small></th>
							<th class="text-center"><small>Safety Watch</small></th>
							<th class="text-center"><small>Save / Delete</small></th>
						</tr>
						<?php foreach ($confinedWorkers as $worker): ?>
						<?php $wid = $worker['id_job_confined_worker']; ?>
						<tr>
							<td><small><?php echo esc($worker['name']); ?></small></td>
							<td class="text-center">
								
								<?= view('App\Views\template\signature_component', [
									'imageUrl'        => $worker["signature"] ?? null,
									'formAction'      => base_url('more/save_signature_confined'),
									'height'          => 200,
									'signButtonText'  => ' Signature In ',
									'id' 			  => 'worker_' . $worker["id_job_confined_worker"],
									'extraValue' 	  => $worker["id_job_confined_worker"],
									'otherValue' 	  => 'worker'
								])?>
							
								<small>
									<?php if ($worker['signature'] && $worker['date_time_in']): ?>
									<br><?php echo esc($worker['date_time_in']); ?>
									<?php endif; ?>
								</small>
							</td>

							<td class="text-center">
								
								<?= view('App\Views\template\signature_component', [
									'imageUrl'        => $worker["signature_out"] ?? null,
									'formAction'      => base_url('more/save_signature_confined'),
									'height'          => 200,
									'signButtonText'  => ' Signature Out ',
									'id' 			  => 'worker_out_' . $worker["id_job_confined_worker"],
									'extraValue' 	  => $worker["id_job_confined_worker"],
									'otherValue' 	  => 'worker_out'
								])?>
														
								<small>
									<?php if ($worker['signature_out'] && $worker['date_time_out']): ?>
									<br><?php echo esc($worker['date_time_out']); ?>
									<?php endif; ?>
								</small>
							</td>

							<form name="worker_<?php echo $wid; ?>" id="worker_<?php echo $wid; ?>" method="post" action="<?php echo base_url('more/update_confined_worker'); ?>">
								<input type="hidden" name="hddIdConfined" value="<?php echo esc($worker['fk_id_job_confined']); ?>"/>
								<input type="hidden" name="hddIdJob" value="<?php echo esc($jobInfo[0]['id_job']); ?>"/>
								<input type="hidden" name="hddId" value="<?php echo $wid; ?>"/>
								<td>
									<input type="text" name="task" class="form-control" placeholder="Task" value="<?php echo esc($worker['task']); ?>" required>
								</td>
								<td>
									<select name="safety_watch" class="form-control" required>
										<option value=''>Select...</option>
										<?php foreach ($workersList as $w): ?>
										<option value="<?php echo $w['id_user']; ?>" <?php echo $worker['fk_id_safety_watch_user'] == $w['id_user'] ? 'selected' : ''; ?>><?php echo esc($w['first_name'] . ' ' . $w['last_name']); ?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<td class="text-center"><small>
									<input type="submit" value="Save" class="btn btn-primary btn-xs"/>
									<a class="btn btn-danger btn-xs" href="<?php echo base_url('more/deleteConfinedWorker/' . $jobInfo[0]['id_job'] . '/' . $worker['fk_id_job_confined'] . '/' . $wid); ?>">
										<span class="glyphicon glyphicon-remove"></span> Delete
									</a>
								</small></td>
							</form>
						</tr>
						<?php endforeach; ?>
					</table>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

<div class="modal fade text-center" id="modalWorker" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">ADD WORKER</h4>
			</div>
			<div class="modal-body">
				<form name="formWorker" id="formWorker" role="form" method="post" action="<?php echo base_url('more/confined_One_Worker'); ?>">
					<input type="hidden" name="hddIdJob" value="<?php echo esc($jobInfo[0]['id_job']); ?>"/>
					<input type="hidden" name="hddIdConfined" value="<?php echo $information ? esc($information[0]['id_job_confined']) : ''; ?>"/>
					<div class="form-group text-left">
						<label class="control-label">Worker</label>
						<select name="worker" class="form-control" required>
							<option value=''>Select...</option>
							<?php foreach ($workersList as $w): ?>
							<option value="<?php echo $w['id_user']; ?>"><?php echo esc($w['first_name'] . ' ' . $w['last_name']); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<div class="row" align="center">
							<div style="width:50%;" align="center">
								<input type="submit" value="Save" class="btn btn-primary"/>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
