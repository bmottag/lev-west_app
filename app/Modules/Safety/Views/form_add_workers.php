<script type="text/javascript" src="<?php echo base_url("assets/js/validate/safety/workers.js?v=1.0.0"); ?>"></script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-edit fa-fw"></i>	RECORD TASK(S)
					</h4>
				</div>
			</div>
		</div>			
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-life-saver"></i> SAFETY - ADD WORKERS
				</div>
				<div class="panel-body">

					<form name="form" id="form" class="form-horizontal" method="post">

						<input type="hidden" name="hddId" value="<?= $idSafety; ?>"/>

						<table class="table table-striped table-hover table-condensed table-bordered">
							<tr class="info">
								<td class="text-center"><strong>Check</strong></td>
								<td class="text-center"><strong>Worker</strong></td>
							</tr>

							<?php foreach ($workersList as $worker): ?>
								<tr>
									<td>
										<?= form_checkbox([
											'name' => 'workers[]',
											'value' => $worker['id_user'],
											'checked' => $worker['found'] ?? false,
											'style' => 'margin:10px'
										]); ?>
									</td>

									<td>
										<?= $worker["first_name"] . ' ' . $worker["last_name"]; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>

						<div class="form-group">
							<div class="row text-center">
								<input type="button" id="btnSubmit" value="Save" class="btn btn-primary"/>
							</div>
						</div>

					</form>

				</div>
			</div>
		</div>
	</div>
</div>