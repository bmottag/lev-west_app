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
						<li><a href="<?php echo base_url('jobs/add_excavation/' . $information[0]['fk_id_job'] . '/' . $information[0]['id_job_excavation']); ?>">Main Form</a>
						</li>
						<li><a href="<?php echo base_url('jobs/upload_excavation_personnel/' . $information[0]['id_job_excavation']); ?>">Personnel</a></li>
						<li><a href="<?php echo base_url('jobs/upload_protection_methods/' . $information[0]['id_job_excavation']); ?>">Protection Methods & Systems</a></li>
						<li><a href="<?php echo base_url('jobs/upload_access_egress/' . $information[0]['id_job_excavation']); ?>">Access & Egress </a></li>
						<li><a href="<?php echo base_url('jobs/upload_affected_zone/' . $information[0]['id_job_excavation']); ?>">Affected Zone </a></li>
						<li><a href="<?php echo base_url('jobs/upload_de_watering/' . $information[0]['id_job_excavation']); ?>">De-Watering </a></li>
						<li class='active'><a href="<?php echo base_url('jobs/upload_sketch/' . $information[0]['id_job_excavation']); ?>">Excavation / Trench Sketch </a></li>
						<li><a href="<?php echo base_url('jobs/review_excavation/' . $information[0]['id_job_excavation']); ?>">Approvals / Review </a></li>						
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
		<div class="col-lg-5">
			<div class="panel panel-primary">
				<div class="panel-body">
					<strong>Job Code/Name: </strong><?php echo $information[0]["job_description"]; ?><br>
					<strong>Date: </strong><?php echo $information[0]["date_excavation"]; ?><br>
					<strong>Project Location: </strong><?php echo $information[0]["project_location"]; ?><br>
					<strong>Anticipated Depth of excavation / trench: </strong><?php echo $information[0]["depth"]; ?> meters<br>
					<strong>Width: </strong><?php echo $information[0]["width"]; ?> meters<br>
					<strong>Length: </strong><?php echo $information[0]["length"]; ?> meters
				</div>
			</div>
		</div>

		<div class="col-lg-7">
	        <div class="panel panel-primary">
	            <div class="panel-heading">
	                <i class="fa fa-edit fa-fw"></i> Excavation / Trench Sketch
	            </div>

                <div class="panel-body">

					<div class="form-group">
						<p class="text-info">Please include a sketch or diagram of the excavation / trench. Be sure to include any surface encumbrances and perimeter protection.</p>						
					</div>
					<div class="form-group">

						<?= view('App\Views\template\draw_component', [
							'formAction' 		=> base_url('jobs/add_signature_excavation'),
							'buttonText'  		=> ' Sketch or Diagram  ',
							'id' 			  	=> $information[0]["id_job_excavation"],
							'extraValue' 	  => $information[0]["id_job_excavation"],
							'otherValue' 	  => 'sketch'
						]) ?>

						<div class="row" align="center">
							<div style="width:80%;" align="center">
							<?php 								
								if($information[0]["excavation_sketch"])
								{ 
							?>
								<img src="<?php echo base_url($information[0]["excavation_sketch"]); ?>" class="img-rounded" alt="Sketch or Diagram" width="400" height="336" />
							<?php
								}
							?>
							</div>
						</div>
					</div>
					<hr>

					<form  name="form" id="form" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url("jobs/save_upload_sketch"); ?>">
						<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_job_excavation"]:""; ?>"/>
						<div class="form-group">					
							<label class="col-sm-5 control-label text-danger" for="hddTask">Attach document if necessary
								<br><small class="text-danger">Allowed format: pdf
								<br>Maximum size: 3000 KB </small>
							</label>
						</div>

						<div class="form-group">
							<div class="col-sm-7">
								 <input type="file" name="userfile" class="form-control" />
								 <br>
								 <?php if($information[0]["excavation_sketch_doc"]){ ?>
									<a href="<?php echo base_url('files/excavation/' . $information[0]["excavation_sketch_doc"]) ?>" target="_blank">Attached document: <?php echo $information[0]["excavation_sketch_doc"]; ?></a>
								<?php } ?>
							</div>

							<div class="col-sm-5">
									<button type="submit" id="btnSubmit" name="btnSubmit" class='btn btn-danger'>
											Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>
							</div>
						</div>

					</form>
				</div>
			</div>


		</div>	
	</div>

				</div>
			</div>
		</div>
	</div>
</div>