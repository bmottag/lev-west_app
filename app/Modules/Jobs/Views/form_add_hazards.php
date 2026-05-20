<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/hazards.js?v=1.0.0"); ?>"></script>

<div id="page-wrapper">
	<br>

	<form  name="form" id="form" class="form-horizontal" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $idJob; ?>"/>
		
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<a class="btn btn-danger btn-xs" href=" <?php echo base_url().'jobs/hazards/' . $idJob; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
						<i class="fa fa-life-saver"></i> <strong>HAZARDS</strong>
					</div>
					<div class="panel-body">
						<div class="panel-group" id="accordion">	

							<div class="alert alert-danger">
								<strong>Select </strong> all the hazards that apply.

							</div>
							
							<div class="form-group">
								<label class="col-sm-4 control-label" for="observation">Observation: </label>
								<div class="col-sm-5">
								<textarea id="observation" name="observation" placeholder="Observation"  class="form-control" rows="2"></textarea>
								</div>
							</div>						

							<p class="text-right text-danger">
								<small>* For line break use: 
								<button type="button" class="btn btn-danger btn-xs"><strong>&lt;br&gt;</strong></i></button>
								, if you want to use bold use 
								<button type="button" class="btn btn-danger btn-xs"><strong>&lt;strong&gt;</strong></i></button>
								at the beginning and 
								<button type="button" class="btn btn-danger btn-xs"><strong>&lt;/strong&gt;</strong></i></button>
								at the end. </small>
							</p>
						<?php foreach ($activityList as $activity): ?>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion"
										href="#collapse<?= $activity['id_hazard_activity']; ?>">
											<?= $activity['hazard_activity']; ?>
										</a>
									</h4>
								</div>

								<div id="collapse<?= $activity['id_hazard_activity']; ?>" class="panel-collapse collapse">
									<div class="panel-body">

										<table class="table table-striped table-hover table-condensed table-bordered">
											<tr class="info">
												<td class="text-center"><strong>Check</strong></td>
												<td class="text-center"><strong>Activity</strong></td>
												<td class="text-center"><strong>Hazard</strong></td>
												<td class="text-center"><strong>Solution</strong></td>
											</tr>

											<?php if (!empty($hazardsByActivity[$activity['id_hazard_activity']])): ?>
												<?php foreach ($hazardsByActivity[$activity['id_hazard_activity']] as $hazard): ?>

													<tr>
														<td>
															<input type="checkbox"
																name="hazards[]"
																value="<?= $hazard['id_hazard']; ?>"
																<?= $hazard['id_job_hazard'] ? 'checked' : '' ?>>
														</td>
														<td><?= $hazard['hazard_activity']; ?></td>
														<td><?= $hazard['hazard_description']; ?></td>
														<td><?= $hazard['solution']; ?></td>
													</tr>

												<?php endforeach; ?>
											<?php endif; ?>

										</table>

									</div>
								</div>
							</div>
						<?php endforeach; ?>
						<br>
							<div class="form-group">
								<div class="row" align="center">
									<div style="width:50%;" align="center">
										<input type="button" id="btnSubmit" name="btnSubmit" value="Save" class="btn btn-primary"/>
									</div>
								</div>
							</div>
						
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>