<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<i class="fa fa-pied-piper-alt"></i> <strong>EXCAVATION AND TRENCHING PLAN</strong>
				</div>
				<div class="panel-body">
					<div class="alert alert-danger">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $information?$information[0]["job_description"]:""; ?>
						</h2>
						<br><span class='fa fa-cloud-download' aria-hidden='true'></span> <strong>Download Excavation and Trenching Plan: </strong>
						<a href='<?php echo base_url('jobs/generaExcavationPDF/' . $information[0]["id_job_excavation"] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						
						<?php if($information[0]["fk_id_confined"]){ ?>
						 	<br><span class='fa fa-cloud-download' aria-hidden='true'></span> <strong>Download Confined Space Entry Permit: </strong>
							<a href='<?php echo base_url('more/generaConfinedPDF/' . $information[0]["fk_id_confined"] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php } ?>
						<?php if($information[0]["method_system_doc"]){ ?>
							<br><a href="<?php echo base_url('files/excavation/' . $information[0]["method_system_doc"]) ?>" target="_blank">Attached document: <?php echo $information[0]["method_system_doc"]; ?></a>
						<?php } ?>
						<?php if($information[0]["permit_required_doc"]){ ?>
							<br><a href="<?php echo base_url('files/excavation/' . $information[0]["permit_required_doc"]) ?>" target="_blank">Attached document: <?php echo $information[0]["permit_required_doc"]; ?></a>
						<?php } ?>
						<?php if($information[0]["excavation_sketch_doc"]){ ?>
							<br><a href="<?php echo base_url('files/excavation/' . $information[0]["excavation_sketch_doc"]) ?>" target="_blank">Attached document: <?php echo $information[0]["excavation_sketch_doc"]; ?></a>
						<?php } ?>
					</div>
					<?php 
						if(session()->get('rol') && $information){
					?>
						<ul class="nav nav-tabs">
							<li><a href="<?php echo base_url('jobs/add_excavation/' . $information[0]['fk_id_job'] . '/' . $information[0]['id_job_excavation']); ?>">Main Form</a>
							</li>
							<li><a href="<?php echo base_url('jobs/upload_excavation_personnel/' . $information[0]['id_job_excavation']); ?>">Personnel</a></li>
							<li><a href="<?php echo base_url('jobs/upload_protection_methods/' . $information[0]['id_job_excavation']); ?>">Protection Methods & Systems</a></li>
							<li><a href="<?php echo base_url('jobs/upload_access_egress/' . $information[0]['id_job_excavation']); ?>">Access & Egress </a></li>
							<li><a href="<?php echo base_url('jobs/upload_affected_zone/' . $information[0]['id_job_excavation']); ?>">Affected Zone </a></li>
							<li><a href="<?php echo base_url('jobs/upload_de_watering/' . $information[0]['id_job_excavation']); ?>">De-Watering </a></li>
							<li><a href="<?php echo base_url('jobs/upload_sketch/' . $information[0]['id_job_excavation']); ?>">Excavation / Trench Sketch </a></li>
							<li class='active'><a href="<?php echo base_url('jobs/review_excavation/' . $information[0]['id_job_excavation']); ?>">Approvals / Review </a></li>
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

	<div class="row">
		<div class="col-lg-6">
			<div class="panel panel-primary">
				<div class="panel-body">
					<strong>Job Code/Name: </strong><?php echo $information[0]["job_description"]; ?><br>
					<strong>Date: </strong><?php echo $information[0]["date_excavation"]; ?><br>
					<strong>Project Location: </strong><?php echo $information[0]["project_location"]; ?><br>
					<strong>Anticipated Depth of excavation / trench: </strong><?php echo $information[0]["depth"]; ?> meters<br>
					<strong>Width: </strong><?php echo $information[0]["width"]; ?> meters<br>
					<strong>Length: </strong><?php echo $information[0]["length"]; ?> meters<br>
					Will or could this excavation / trench be considered a confined space?
					<?php echo $information[0]["confined_space"]==1?"Si":"No"; ?><br>
					Will the excavation / trench atmospheric conditions be tested daily? 
					<?php echo $information[0]["tested_daily"]==1?"Si":"No"; ?><br>
					<?php 
						if($information[0]["tested_daily_explanation"]){
							echo $information[0]["tested_daily_explanation"] . "<br>";
						}
					?>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
	        <div class="panel panel-primary">
	            <div class="panel-heading">
	                <i class="fa fa-edit fa-fw"></i> Project Manager - Signature
	            </div>

                <div class="panel-body">				
					<div class="form-group">

						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $information[0]["manager_signature"] ?? null,
							'formAction'      => base_url('jobs/add_signature_excavation'),
							'height'          => 200,
							'id' 			  => 'manager',
							'extraValue' 	  => $information[0]["id_job_excavation"],
							'otherValue' 	  => 'manager'
						])?>

						<br>
						<div class="row" align="center">		
							<strong><?php echo $information[0]["manager"]; ?></strong>
						</div>
					</div>
				</div>
			</div>

	        <div class="panel panel-primary">
	            <div class="panel-heading">
	                <i class="fa fa-edit fa-fw"></i> Operator performing excavation - Signature
	            </div>

                <div class="panel-body">		
					<div class="form-group">

						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $information[0]["operator_signature"] ?? null,
							'formAction'      => base_url('jobs/add_signature_excavation'),
							'height'          => 200,
							'id' 			  => 'operator',
							'extraValue' 	  => $information[0]["id_job_excavation"],
							'otherValue' 	  => 'operator'
						])?>

						<br>
						<div class="row" align="center">		
							<strong><?php echo $information[0]["operator"]; ?></strong>
						</div>
					</div>			
				</div>
			</div>

	        <div class="panel panel-primary">
	            <div class="panel-heading">
	                <i class="fa fa-edit fa-fw"></i> Person supervising excavation - Signature
	            </div>

                <div class="panel-body">
					<div class="form-group">

						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $information[0]["supervisor_signature"] ?? null,
							'formAction'      => base_url('jobs/add_signature_excavation'),
							'height'          => 200,
							'id' 			  => 'supervisor',
							'extraValue' 	  => $information[0]["id_job_excavation"],
							'otherValue' 	  => 'supervisor'
						])?>

						<br>
						<div class="row" align="center">		
							<strong><?php echo $information[0]["supervisor"]; ?></strong>
						</div>
					</div>

				</div>
			</div>
		</div>	
	
	</div>

<!--INICIO WORKERS -->
<?php 
	if($excavationWorkers){
?>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-user"></i> <strong>VCI WORKERS</strong>
				</div>
				<div class="panel-body">

					<table class="table table-hover">
						<thead>
							<tr>
								<th>Name</th>
								<th class='text-center'>Signature</th>
							</tr>
						</thead>
					<?php
						foreach ($excavationWorkers as $data):
							echo "<tr>";					
							echo "<td >" . $data['name'] . "</td>";
							echo "<td class='text-center'>";
					?>

					<?= view('App\Views\template\signature_component', [
						'imageUrl'        => $data["signature"] ?? null,
						'formAction'      => base_url('jobs/add_signature_excavation'),
						'height'          => 200,
						'id' 			  => 'worker_' . $data['id_excavation_worker'],
						'extraValue' 	  => $data['id_excavation_worker'],
						'otherValue' 	  => 'worker'
					]) ?>

					<?php
							echo "</td>"; 
							echo "</tr>";
						endforeach;
					?>
					</table>

				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN WORKERS -->

<!--INICIO OCCASIONAL SUBCONTRACTOR -->
<?php 
	if($excavationSubcontractors){
?>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-user"></i> <strong>SUBCONTRACTOR WORKERS</strong>
				</div>
				<div class="panel-body">

					<table class="table table-hover">
						<thead>
							<tr>
								<th>Name</th>
								<th>Company</th>
								<th class='text-center'>Signature</th>
							</tr>
						</thead>
					<?php
						foreach ($excavationSubcontractors as $data):
							echo "<tr>";					
							echo "<td >" . $data['worker_name'] . "</td>";
							echo "<td >" . $data['company_name'] . "</td>";
							echo "<td class='text-center'>";
						?>

						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $data["signature"] ?? null,
							'formAction'      => base_url('jobs/add_signature_excavation'),
							'height'          => 200,
							'id' 			  => 'subcontractor_' . $data['id_excavation_subcontractor'],
							'extraValue' 	  => $data['id_excavation_subcontractor'],
							'otherValue' 	  => 'subcontractor'
						]) ?>

						<?php
							echo "</td>";                     
							echo "</tr>";
						endforeach;
					?>
						</table>

				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN OCCASIONAL SUBCONTRACTOR -->
				</div>
			</div>
		</div>
	</div>	
</div>