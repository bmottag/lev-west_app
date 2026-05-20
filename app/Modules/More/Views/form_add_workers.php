<script type="text/javascript" src="<?php echo base_url('assets/js/validate/more/workers.js?v=2.0.0'); ?>"></script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-life-saver"></i> PPE INSPECTION - ADD WORKERS
				</div>
				<div class="panel-body">
					<form name="form" id="form" class="form-horizontal" method="post">
						<input type="hidden" id="hddIdPPEInpection" name="hddIdPPEInpection" value="<?php echo esc($idPPEInspection); ?>"/>

						<table class="table table-striped table-hover table-condensed table-bordered">
							<tr class="info">
								<td><p class="text-center"><strong>Check</strong></p></td>
								<td><p class="text-center"><strong>Worker</strong></p></td>
							</tr>
							<?php foreach ($workersList as $lista): ?>
							<tr>
								<td>
									<input type="checkbox" name="workers[]" value="<?php echo $lista['id_user']; ?>" style="margin:10px">
								</td>
								<td><?php echo esc($lista['first_name'] . ' ' . $lista['last_name']); ?></td>
							</tr>
							<?php endforeach; ?>
						</table>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:50%;" align="center">
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
				</div>
			</div>
		</div>
	</div>
</div>
