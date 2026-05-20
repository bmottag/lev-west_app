<script type="text/javascript" src="<?php echo base_url("assets/js/validate/safety/hazards.js?v=1.0.0"); ?>"></script>

<div id="page-wrapper">
	<br>

<form  name="form" id="form" class="form-horizontal" method="post" >
	<input type="hidden" id="hddId" name="hddId" value="<?php echo $idSafety; ?>"/>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<a class="btn btn-danger btn-xs" href=" <?php echo base_url().'safety/upload_info_safety/' . $idSafety; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a>
					<i class="fa fa-life-saver"></i> <strong>HAZARDS</strong>
				</div>
				<!-- .panel-heading -->
				<div class="panel-body">
					<div class="panel-group" id="accordion">	

						<div class="alert alert-danger">
							<strong>Select </strong> all the hazards that apply.

						</div>
						
<?php foreach ($activityList as $lista): ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $lista['id_hazard_activity']; ?>">
                <?= $lista['hazard_activity']; ?>
            </a>
        </h4>
    </div>

    <div id="collapse<?= $lista['id_hazard_activity']; ?>" class="panel-collapse collapse">
        <div class="panel-body">

            <?php $hazardList = $lista['hazards']; ?>

            <table class="table table-striped table-hover table-condensed table-bordered">
                <tr class="info">
                    <td class="text-center"><strong>Check</strong></td>
                    <td class="text-center"><strong>Activity</strong></td>
                    <td class="text-center"><strong>Hazard</strong></td>
                    <td class="text-center"><strong>Solution</strong></td>
                </tr>

                <?php if (!empty($hazardList)): ?>
                    <?php foreach ($hazardList as $hazard): ?>
                        <tr>
                            <td>
                                <?php
                                echo form_checkbox([
                                    'name' => 'hazards[]',
                                    'value' => $hazard['id_hazard'],
                                    'checked' => $hazard['found'] ?? false,
                                    'style' => 'margin:10px'
                                ]);
                                ?>
                            </td>
                            <td><?= $hazard["hazard_activity"] ?></td>
                            <td><?= $hazard["hazard_description"] ?></td>
                            <td><?= $hazard["solution"] ?></td>
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