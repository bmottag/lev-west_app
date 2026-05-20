<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url().'enlaces/manuals'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-image"></i> <strong>SETTINGS - MANUAL LINKS</strong>
				</div>
				<div class="panel-body">
				
					<?php if (session()->getFlashdata('retornoExito')): ?>
						<div class="alert alert-success">
							<?= session()->getFlashdata('retornoExito') ?>
						</div>
					<?php endif; ?>

					<?php if (session()->getFlashdata('retornoError')): ?>
						<div class="alert alert-danger">
							<?= session()->getFlashdata('retornoError') ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($error)): ?>
						<div class="alert alert-danger">
							<strong>Error:</strong><br>
							<?php if (is_array($error)): ?>
								<?php foreach ($error as $err): ?>
									<?= esc($err) ?><br>
								<?php endforeach; ?>
							<?php else: ?>
								<?= esc($error) ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<form id="form_map" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?= base_url('enlaces/do_upload_manual') ?>">

						<input type="hidden" name="hddId" value="<?= $information[0]['id_link'] ?? '' ?>"/>

						<div class="form-group">
							<label class="col-sm-4 control-label">Link name: *</label>
							<div class="col-sm-5">
								<input type="text"
									name="link_name"
									class="form-control"
									value="<?= old('link_name', $information[0]['link_name'] ?? '') ?>"
									placeholder="Link name"
									required>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label">Order: *</label>
							<div class="col-sm-5">
								<select name="order" class="form-control" required>
									<option value="">Select...</option>
									<?php for ($i = 1; $i <= 20; $i++): ?>
										<option value="<?= $i ?>"
											<?= old('order', $information[0]['order'] ?? '') == $i ? 'selected' : '' ?>>
											<?= $i ?>
										</option>
									<?php endfor; ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label">State: *</label>
							<div class="col-sm-5">
								<select name="link_state" class="form-control" required>
									<option value="">Select...</option>
									<option value="1"
										<?= old('link_state', $information[0]['link_state'] ?? '') == 1 ? 'selected' : '' ?>>
										Active
									</option>
									<option value="2"
										<?= old('link_state', $information[0]['link_state'] ?? '') == 2 ? 'selected' : '' ?>>
										Inactive
									</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label">Attach manual</label>
							<div class="col-sm-5">
								<input type="file" name="userfile" class="form-control">
							</div>
						</div>

						<div class="form-group text-center">
							<button type="submit" class="btn btn-primary">
								Save <span class="glyphicon glyphicon-floppy-disk"></span>
							</button>
						</div>

					</form>

					<br>
					<div class="form-group">
						<div class="alert alert-danger">
							<strong>Note:</strong><br>
							Allowed format: pdf<br>
							Maximum size: 3000 KB
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>