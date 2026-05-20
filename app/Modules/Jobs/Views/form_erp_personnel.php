<div id="page-wrapper">
	<br>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					<a class="btn btn-success btn-xs" href=" <?php echo base_url().'jobs'; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-fire-extinguisher "></i> <strong>ERP - EMERGENCY RESPONSE PLAN</strong>
				</div>
				<div class="panel-body">
				
					<ul class="nav nav-pills">
						<li ><a href="<?php echo base_url("jobs/erp/" . $jobInfo[0]["id_job"]); ?>">ERP - INFO</a>
						</li>
						<li class='active'><a href="<?php echo base_url("jobs/erp_personnel/" . $jobInfo[0]["id_job"]); ?>">ERP - EVACUATION PERSONNEL </a>
						</li>
						<li ><a href="<?php echo base_url("jobs/erp_map/" . $jobInfo[0]["id_job"]); ?>">ERP - EVACUATION MAP</a>
						</li>
					</ul>
					
					<div class="alert alert-success">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
						<br><span class='fa fa-clock-o' aria-hidden='true'></span> <strong>Date: </strong>
						<?php 
						if($information){
								echo $information[0]["date_erp"]; 
								
								echo "<br><span class='fa fa-cloud-download' aria-hidden='true'></span> <strong>Download ERP: </strong>";
						?>
<a href='<?php echo base_url('jobs/generaERPPDF/' . $information[0]["id_erp"] ); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php 
						}else{
								echo date("Y-m-d");
						}
						?>
					</div>
					
					<?php if (session()->getFlashdata('retornoExito')): ?>
						<div class="alert alert-success">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoExito') ?>
						</div>
					<?php endif; ?>

					<?php if (session()->getFlashdata('retornoError')): ?>
						<div class="alert alert-danger">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							<?= session()->getFlashdata('retornoError') ?>
						</div>
					<?php endif; ?>
														
				</div>
			</div>
		</div>
	</div>		
		
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>EVACUATION PERSONNEL / TRAINING CERTIFICATES</strong>
				</div>
				<div class="panel-body">								
<p class="text-success text-left">The personnel mention below had being train in order to conduct and oversee an emergency evacuation for this site:</p>						

											<div class="col-lg-12">	
<?php if($trainingWorkers){ ?>
												
					<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modalWorker" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add VCI Workers
					</button>
<?php }else { ?>
					<a href="<?php echo base_url("jobs/add_workers_training/" . $jobInfo[0]["id_job"]); ?>" class="btn btn-success btn-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add VCI Workers</a>
												
<?php } ?>
											
												<br>
											</div>
											
<?php 
	if($trainingWorkers){
?>
			<table class="table table-bordered table-striped table-hover table-condensed">
				<tr>
					<th class='text-center'>Name</th>
					<th class='text-center'>Title</th>
					<th class='text-center'>Responsability</th>
					<th class='text-center'>Save</th>
					<th class='text-center'>Delete</th>
				</tr>
				<?php
					foreach ($trainingWorkers as $data):
						echo "<tr>";					
						echo "<td ><small>" . $data['name'] . "</small></td>";

						$idRecord = $data['id_erp_training_worker'];
				?>
						<form  name="workers_<?php echo $idRecord ?>" id="workers_<?php echo $idRecord ?>" method="post" action="<?php echo base_url("jobs/update_erp_personnel"); ?>">
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $idRecord; ?>"/>
						<input type="hidden" id="hddIdERP" name="hddIdERP" value="<?php echo $jobInfo[0]["id_job"]; ?>"/>
						<td>
						<input type="text" id="title" name="title" class="form-control" placeholder="Title" value="<?php echo $data['title']; ?>" maxlength="50" required >
						</td>
						<td>
						<textarea id="responsability" name="responsability" class="form-control" rows="3" required><?php echo $data['responsability']; ?></textarea>
						</td>
						<td class='text-center'>
							<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Update">
								 <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
							</button> 
						</td>
						</form>	

						<td class='text-center'><small>
						<center>
						<a class='btn btn-danger btn-xs' href='<?php echo base_url('jobs/deleteERPTRAINGINWorker/' . $jobInfo[0]["id_job"] . '/' . $data['id_erp_training_worker']) ?>' id="btn-delete">
								<span class="glyphicon glyphicon-trash" aria-hidden="true"> </span>  
						</a>
						</center>
						</small></td>
						</tr>
				<?php
					endforeach;
				?>
			</table>
	<?php } ?>

				</div>
			</div>
		</div>
	</div>

</div>
<!-- /#page-wrapper -->


<!--INICIO Modal para adicionar WORKER -->
<div class="modal fade text-center" id="modalWorker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">ADD WORKER</h4>
			</div>

			<div class="modal-body">
				<form name="formHazard" id="formHazard" role="form" method="post" action="<?php echo base_url("jobs/save_one_erp_training_worker") ?>" >
					<input type="hidden" id="hddId" name="hddId" value="<?php echo $jobInfo[0]["id_job"]; ?>"/>
					
					<div class="form-group text-left">
						<label class="control-label" for="worker">Worker</label>
						<select name="worker" id="worker" class="form-control" required>
							<option value=''>Select...</option>
							<?php for ($i = 0; $i < count($workersList); $i++) { ?>
								<option value="<?php echo $workersList[$i]["id_user"]; ?>" ><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
							<?php } ?>
						</select>
					</div>
					
					<div class="form-group">
						<div class="row" align="center">
							<div style="width:50%;" align="center">
								<input type="submit" id="btnSubmitWorker" name="btnSubmitWorker" value="Save" class="btn btn-primary"/>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div id="div_load" style="display:none">		
							<div class="progress progress-striped active">
								<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
									<span class="sr-only">45% completado</span>
								</div>
							</div>
						</div>
						<div id="div_error" style="display:none">			
							<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
						</div>	
					</div>
						
				</form>
			</div>

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar WORKER -->