<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/excavation_personnel.js?v=1.0.0"); ?>"></script>

<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<a class="btn btn-danger btn-xs" href=" <?php echo base_url('jobs/excavation/' . $information[0]["id_job"]); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-pied-piper-alt"></i> <strong>EXCAVATION AND TRENCHING PLAN</strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-danger">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $information?$information[0]["job_description"]:""; ?>
						</h2>
					</div>
				<?php 
					if($information){
				?>
					<ul class="nav nav-tabs">
						<li ><a href="<?php echo base_url('jobs/add_excavation/' . $information[0]['fk_id_job'] . '/' . $information[0]['id_job_excavation']); ?>">Main Form</a></li>
						<li class='active'><a href="<?php echo base_url('jobs/upload_excavation_personnel/' . $information[0]['id_job_excavation']); ?>">Personnel</a></li>
						<li><a href="<?php echo base_url('jobs/upload_protection_methods/' . $information[0]['id_job_excavation']); ?>">Protection Methods & Systems</a></li>
						<li><a href="<?php echo base_url('jobs/upload_access_egress/' . $information[0]['id_job_excavation']); ?>">Access & Egress </a></li>
						<li><a href="<?php echo base_url('jobs/upload_affected_zone/' . $information[0]['id_job_excavation']); ?>">Affected Zone </a></li>
						<li><a href="<?php echo base_url('jobs/upload_de_watering/' . $information[0]['id_job_excavation']); ?>">De-Watering </a></li>
						<li><a href="<?php echo base_url('jobs/upload_sketch/' . $information[0]['id_job_excavation']); ?>">Excavation / Trench Sketch </a></li>
						<li><a href="<?php echo base_url('jobs/review_excavation/' . $information[0]['id_job_excavation']); ?>">Approvals / Review</a></li>
					</ul>
					<br>
				<?php
					}
				?>

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

					<form  name="form" id="form" class="form-horizontal" method="post" >
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_job_excavation"]:""; ?>"/>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="manager">Project Manager *</label>
							<div class="col-sm-5">									
								<select name="manager" id="manager" class="form-control" required >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($adminList); $i++) { ?>
										<option value="<?php echo $adminList[$i]["id_user"]; ?>" <?php if($information && $information[0]["fk_id_user_manager"] == $adminList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $adminList[$i]["first_name"] . ' ' . $adminList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="operator">Operator performing excavation *</label>
							<div class="col-sm-5">									
								<select name="operator" id="operator" class="form-control" required >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information && $information[0]["fk_id_user_operator"] == $workersList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>
							</div>
						</div>


						<div class="form-group">
							<label class="col-sm-4 control-label" for="supervisor">Person supervising excavation *</label>
							<div class="col-sm-5">									
								<select name="supervisor" id="supervisor" class="form-control" required >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information && $information[0]["fk_id_user_supervisor"] == $workersList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">
									<button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-danger'>
											Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>						
								</div>
							</div>
						</div>

					</form>

						<hr>

						<div class="alert alert-info">
							<span class="fa fa-info-circle" aria-hidden="true"></span>
							List of employees involved during the excavation process (spotter, worker, pile layer, surveyor).
						</div>


<!--INICIO WORKERS -->
					<div class="col-lg-12">	
						<?php if($excavationWorkers){ ?>
							<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modalWorker" id="x">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Lev West Workers
							</button>
						<?php }else { ?>
							<a href="<?php echo base_url("jobs/add_workers_excavation/" . $information[0]["id_job_excavation"]); ?>" class="btn btn-info btn-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Lev West Workers</a>
						<?php } ?>
						<br>
					</div>

				<?php 
					if($excavationWorkers){
				?>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Name</th>
								<th class='text-center'>Delete</th>
							</tr>
						</thead>
					<?php
						foreach ($excavationWorkers as $data):
							echo "<tr>";					
							echo "<td >" . $data['name'] . "</td>";
							echo "<td class='text-center'>";
					?>
							<a class='btn btn-danger btn-xs' href='<?php echo base_url('jobs/deleteExcavationWorker/' . $data['fk_id_job_excavation'] . '/' . $data['id_excavation_worker']) ?>' id="btn-delete">
									<span class="glyphicon glyphicon-trash" aria-hidden="true"> </span> 
							</a>
					<?php
							echo "</small></td>";                     
							echo "</tr>";
						endforeach;
					?>
					</table>
				<?php } ?>
<!--FIN WORKERS -->

<!--INICICO SUBCONTRATISTAS -->
					<div class="col-lg-12">													
						<button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#modalSubcontractorWorker" id="x">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Subcontractor Worker
						</button>
						<br>
					</div>


				<?php 
					if($excavationSubcontractors){
				?>
<a href="<?php echo base_url("external/send_sms_excavation/" . $information[0]["id_job_excavation"]); ?>" class="btn btn-default btn-xs"> 
	<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Send SMS to All Subcontractor Workers
</a>
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Name</th>
									<th>Company</th>
									<th class='text-center'>Mobile number</th>
									<th class='text-center'>Delete</th>
								</tr>
							</thead>
					<?php
						foreach ($excavationSubcontractors as $data):
							echo "<tr>";					
							echo "<td >" . $data['worker_name'] . "</td>";
							echo "<td >" . $data['company_name'] . "</td>";
							echo "<td class='text-center'>" . $data['worker_movil_number'];
							if($data['worker_movil_number']){
?>
	<a href='<?php echo base_url("external/send_sms_excavation/" . $data["fk_id_job_excavation"] . '/' . $data['id_excavation_subcontractor']); ?>' class='btn btn-info btn-xs' title="Send SMS"><i class='glyphicon glyphicon-send'></i></a>
<?php
							}
							echo "</td>";
							echo "<td class='text-center'>";
						?>
							<a class='btn btn-danger btn-xs' href='<?php echo base_url('jobs/deleteExcavationSubcontractorWorker/' . $data['fk_id_job_excavation'] . '/' . $data['id_excavation_subcontractor']) ?>' id="btn-delete">
									<span class="glyphicon glyphicon-trash" aria-hidden="true"> </span>
							</a>
						<?php
							echo "</td>";
							echo "</tr>";
						endforeach;
					?>
						</table>
				<?php } ?>
<!--FIN SUBCONTRATISTAS -->
				</div>
			</div>
		</div>
	</div>	
</div>


<!--INICIO Modal para adicionar WORKER -->
<div class="modal fade text-center" id="modalWorker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">Add Worker</h4>
			</div>

			<div class="modal-body">
				<form name="formWorker" id="formWorker" role="form" method="post" action="<?php echo base_url("jobs/excavation_One_Worker") ?>" >
					<input type="hidden" id="hddIdExcavation" name="hddIdExcavation" value="<?php echo $information[0]["id_job_excavation"]; ?>"/>
					
					<div class="form-group text-left">
						<label class="control-label" for="worker">Worker: *</label>
						<select name="worker" id="worker" class="form-control" required >
							<option value=''>Select...</option>
							<?php for ($i = 0; $i < count($workersList); $i++) { ?>
								<option value="<?php echo $workersList[$i]["id_user"]; ?>" ><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
							<?php } ?>
						</select>
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
						
					<div class="form-group">
						<div class="row" align="center">
							<div style="width:100%;" align="center">
								<button type="submit" id="btnSubmitWorker" name="btnSubmitWorker" class='btn btn-info'>
										Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
								</button>						
							</div>
						</div>
					</div>

				</form>
			</div>

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar WORKER -->

<!--INICIO Modal para adicionar subcontractor WORKER -->
<div class="modal fade text-center" id="modalSubcontractorWorker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">Add Subcontractor Worker</h4>
			</div>

			<div class="modal-body">
				<form name="formSubcontractor" id="formSubcontractor" role="form" method="post" action="<?php echo base_url("jobs/excavation_subcontractor_Worker") ?>" >
					<input type="hidden" id="hddIdExcavation" name="hddIdExcavation" value="<?php echo $information[0]["id_job_excavation"]; ?>"/>
					
					<div class="row">
						<div class="col-sm-6">	
							<div class="form-group text-left">
								<label class="control-label" for="company">Company: *</label>
								<select name="company" id="company" class="form-control" required>
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($companyList); $i++) { ?>
										<option value="<?php echo $companyList[$i]["id_company"]; ?>" ><?php echo $companyList[$i]["company_name"]; ?></option>	
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group text-left">
								<label class="control-label" for="workerName">Worker Name: *</label>
								<input type="text" id="workerName" name="workerName" class="form-control" placeholder="Worker Name" required >
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">	
							<div class="form-group text-left">
								<label for="phone_number">Worker mobile number:</label>
								<input type="number" id="phone_number" name="phone_number" class="form-control" placeholder="Worker mobile number" maxlength="12">
							</div>
						</div>
						<div class="col-sm-6">

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

					<div class="form-group">
						<div class="row" align="center">
							<div style="width:100%;" align="center">
								<button type="submit" id="btnSubmitWorker" name="btnSubmitWorker" class='btn btn-primary'>
										Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
								</button>						
							</div>
						</div>
					</div>
						
				</form>
			</div>

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar WORKER -->