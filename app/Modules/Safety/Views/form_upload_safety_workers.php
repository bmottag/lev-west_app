<script type="text/javascript" src="<?php echo base_url("assets/js/validate/safety/safety.js?v=1.0.0"); ?>"></script>

<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-life-saver"></i> <strong>FLHA - FIELD LEVEL HAZARD ASSESSMENT</strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-info">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $information?$information[0]["job_description"]:""; ?><br>
						</h2>
						<strong>Task(s) to be done: </strong><br><?php echo $information?$information[0]["work"]:""; ?>
					</div>
				<?php 
					if($information){
				?>
					<ul class="nav nav-tabs">
						<li><a href="<?php echo base_url('safety/add_safety/' . $information[0]['fk_id_job'] . '/' . $information[0]['id_safety']); ?>">Main Form</a>
						</li>
						<li><a href="<?php echo base_url('safety/upload_info_safety/' . $information[0]['id_safety']); ?>">Hazards</a>
						</li>
						<!--
						<li><a href="<?php echo base_url('safety/upload_covid/' . $information[0]['id_safety']); ?>">COVID Form</a>
						</li>
						-->
						<li class='active'><a href="<?php echo base_url('safety/upload_workers/' . $information[0]['id_safety']); ?>">Workers</a>
						</li>
						<li><a href="<?php echo base_url('safety/review_flha/' . $information[0]['id_safety']); ?>">Review and Sign</a>
						</li>
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

<!--INICIO WORKERS -->
					<div class="col-lg-12">	
						<?php if($safetyWorkers){ ?>
							<button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#modalWorker" id="x">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Lev West Workers
							</button>
						<?php }else { ?>
							<a href="<?php echo base_url("safety/add_workers/" . $information[0]["id_safety"]); ?>" class="btn btn-warning btn-block"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Lev West Workers</a>
						<?php } ?>
						<br>
					</div>

				<?php 
					if($safetyWorkers){
				?>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Name</th>
								<th class='text-center'>Delete</th>
							</tr>
						</thead>
					<?php
						foreach ($safetyWorkers as $data):
							echo "<tr>";					
							echo "<td >" . $data['name'] . "</td>";
							echo "<td class='text-center'>";
					?>
							<a class='btn btn-danger btn-xs' href='<?php echo base_url('safety/deleteSafetyWorker/' . $data['id_safety_worker'] . '/' . $data['fk_id_safety']) ?>' id="btn-delete">
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
					if($safetySubcontractorsWorkers){
				?>
<a href="<?php echo base_url("external/send_sms_flha/" . $information[0]["id_safety"]); ?>" class="btn btn-default btn-xs"> 
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
						foreach ($safetySubcontractorsWorkers as $data):
							echo "<tr>";					
							echo "<td >" . $data['worker_name'] . "</td>";
							echo "<td >" . $data['company_name'] . "</td>";
							echo "<td class='text-center'>" . $data['worker_movil_number'];
							if($data['worker_movil_number']){
?>
	<a href='<?php echo base_url("external/send_sms_flha/" . $information[0]["id_safety"] . '/' . $data['id_safety_subcontractor']); ?>' class='btn btn-info btn-xs' title="Send SMS"><i class='glyphicon glyphicon-send'></i></a>
<?php
							}
							echo "</td>";
							echo "<td class='text-center'>";
						?>
							<a class='btn btn-danger btn-xs' href='<?php echo base_url('safety/deleteSafetySubcontractor/' . $data['id_safety_subcontractor'] . '/' . $data['fk_id_safety']) ?>' id="btn-delete">
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
				<h4 class="modal-title" id="exampleModalLabel">ADD WORKER</h4>
			</div>

			<div class="modal-body">
				<form name="formHazard" id="formHazard" role="form" method="post" action="<?php echo base_url("safety/safet_One_Worker") ?>" >
					<input type="hidden" id="hddId" name="hddId" value="<?php echo $information[0]["id_safety"]; ?>"/>
					
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

<!--INICIO Modal para adicionar subcontractor WORKER -->
<div class="modal fade text-center" id="modalSubcontractorWorker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">ADD SUBCONTRACTOR WORKER</h4>
			</div>

			<div class="modal-body">
				<form name="formSubcontractor" id="formSubcontractor" role="form" method="post" action="<?php echo base_url("safety/safet_subcontractor_Worker") ?>" >
					<input type="hidden" id="hddId" name="hddId" value="<?php echo $information[0]["id_safety"]; ?>"/>
					
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
						<div class="row" align="center">
							<div style="width:50%;" align="center">
								<input type="submit" id="btnSubmitSubcontractor" name="btnSubmitSubcontractor" value="Save" class="btn btn-primary"/>
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