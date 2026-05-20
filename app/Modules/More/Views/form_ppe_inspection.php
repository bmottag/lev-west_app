<script type="text/javascript" src="<?php echo base_url('assets/js/validate/more/ppe_inspection.js?v=2.0.0'); ?>"></script>
<?php $userRol = session()->get('rol'); ?>
<?php if ($userRol == 99): ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php endif; ?>

<div id="page-wrapper">
	<br>


	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<a class="btn btn-info btn-xs" href="<?php echo base_url('more/ppe_inspection'); ?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back</a>
					<i class="fa fa-cube"></i> <strong>PPE INSPECTION FORM</strong>
				</div>
				<div class="panel-body">

					<form name="form" id="form" class="form-horizontal" method="post">
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $information ? esc($information[0]['id_ppe_inspection']) : ''; ?>"/>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="col-sm-4 control-label">Observation:</label>
								<div class="col-sm-8">
									<textarea id="observation" name="observation" class="form-control" rows="2"><?php echo $information ? esc($information[0]['observation']) : ''; ?></textarea>
								</div>
							</div>

							<?php if ($userRol == 99): ?>
							<script>
								$(function() {
									$('#date').datepicker({ changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' });
								});
							</script>
							<div class="form-group">
								<label class="col-sm-4 control-label">Date of Issue:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="date" name="date" value="<?php echo $information ? esc($information[0]['date_ppe_inspection']) : ''; ?>" placeholder="Date of Issue"/>
								</div>
							</div>
							<?php endif; ?>

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
						</div>
						
					</form>

					<?php if ($information): ?>
					<div class="col-lg-6">
						<div class="panel panel-primary">
							<div class="panel-heading"><i class="fa fa-edit fa-fw"></i> Inspector</div>
							<div class="panel-body">
								<div class="form-group">

									<?= view('App\Views\template\signature_component', [
										'imageUrl'        => $information[0]['inspector_signature'] ?? null,
										'formAction'      => base_url('more/save_signature_ppe'),
										'height'          => 200,
										'signButtonText'  => esc($information[0]['name']) . ' Signature ',
										'id' 			  => 'inspector',
										'extraValue' 	  => $information[0]['id_ppe_inspection'],
										'otherValue' 	  => 'inspector'
									])?>

								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>


<?php if ($information): ?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading">
				<a name="anclaWorker"></a><strong>Lev West WORKERS</strong>
			</div>
			<div class="panel-body">
				<div class="col-lg-12">
					<?php if ($ppeInspectionWorkers): ?>
					<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modalWorker" id="x">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Lev West Workers
					</button>
					<?php else: ?>
					<a href="<?php echo base_url('more/add_workers_ppe_inspection/' . $information[0]['id_ppe_inspection']); ?>" class="btn btn-info btn-block">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Lev West Workers
					</a>
					<?php endif; ?>
					<br>
				</div>

				<?php if ($ppeInspectionWorkers): ?>
				<table class="table table-bordered table-striped table-hover table-condensed">
					<tr>
						<th class="text-center"><small>Name</small></th>
						<th class="text-center"><small>Signature</small></th>
						<th class="text-center"><small>Steel toe boots</small></th>
						<th class="text-center"><small>Hard hat</small></th>
						<th class="text-center"><small>Reflective vest</small></th>
						<th class="text-center"><small>Safety glasses</small></th>
						<th class="text-center"><small>Gloves</small></th>
						<th class="text-center"><small>Save</small></th>
						<th class="text-center"><small>Delete</small></th>
					</tr>
					<?php foreach ($ppeInspectionWorkers as $worker): ?>
					<?php $wid = $worker['id_ppe_inspection_worker']; ?>
					<tr>
						<td><small><?php echo esc($worker['name']); ?></small></td>
						<td class="text-center">

							<?= view('App\Views\template\signature_component', [
								'imageUrl'        => $worker["signature"] ?? null,
								'formAction'      => base_url('more/save_signature_ppe'),
								'height'          => 200,
								'signButtonText'  => ' Signature ',
								'id' 			  => 'worker_' . $worker["id_ppe_inspection_worker"],
								'extraValue' 	  => $worker["id_ppe_inspection_worker"],
								'otherValue' 	  => 'worker'
							])?>
						
						</td>

						<form name="datos_<?php echo $wid; ?>" id="datos_<?php echo $wid; ?>" method="post" action="<?php echo base_url('more/updateInspection'); ?>">
						<input type="hidden" name="hddIdPPEInspectionWorker" value="<?php echo $wid; ?>"/>
						<input type="hidden" name="hddIdPPEInspection" value="<?php echo esc($worker['fk_id_ppe_inspection']); ?>"/>

						<?php
						$selects = [
							'safety_boots'    => 'Steel toe boots',
							'hart_hat'        => 'Hard hat',
							'reflective_vest' => 'Reflective vest',
							'safety_glasses'  => 'Safety glasses',
							'gloves'          => 'Gloves',
						];
						foreach ($selects as $fname => $flabel):
						?>
						<td>
							<select name="<?php echo $fname; ?>" id="<?php echo $fname; ?>" class="form-control" required>
								<option value="">Select...</option>
								<option value="1" <?php echo $worker[$fname] == 1 ? 'selected' : ''; ?>>Good</option>
								<option value="2" <?php echo $worker[$fname] == 2 ? 'selected' : ''; ?>>Bad</option>
							</select>
						</td>
						<?php endforeach; ?>

						<td class="text-center">
							<input type="submit" value="Save" class="btn btn-primary btn-xs"/>
						</td>
						</form>

						<td class="text-center">
							<button type="button" class="btn btn-danger btn-xs" id="<?php echo $worker['fk_id_ppe_inspection'] . '-' . $wid; ?>">Delete</button>
						</td>
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
				<form name="formAddWorker" id="formAddWorker" role="form" method="post" action="<?php echo base_url('more/add_one_worker'); ?>">
					<input type="hidden" name="hddIdPPEInspection" value="<?php echo $information ? esc($information[0]['id_ppe_inspection']) : ''; ?>"/>
					<div class="form-group text-left">
						<label class="control-label">Worker</label>
						<select name="worker" id="worker" class="form-control" required>
							<option value=''>Select...</option>
							<?php foreach ($workersList ?? [] as $w): ?>
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
