<script>
$(function(){ 
	$(".btn-outline").click(function () {	
			var idRecord = $('#hddIdSafety').val();
			var table = "safety";
			var backURL = "safety/review_flha/";
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'safety/cargar-modal-employee-verification',
                data: {"idRecord": idRecord, "table": table, "backURL": backURL, 'information': oID },
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
});
</script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
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

						<?php 
						if(session()->rol && $information){ 	
						?>
						<a href='<?php echo base_url('report/generaSafetyPDF/x/x/x/' . $information[0]["id_safety"] ); ?>' target="_blank"> <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>	
						<?php 
						}
						?>
					</div>
				<?php 
					if(session()->rol && $information){
				?>
					<ul class="nav nav-tabs">
						<li><a href="<?php echo base_url('safety/add_safety/' . $information[0]['fk_id_job'] . '/' . $information[0]['id_safety']); ?>">Main Form</a>
						</li>
						<li><a href="<?php echo base_url('safety/upload_info_safety/' . $information[0]['id_safety']); ?>">Hazards</a>
						</li>
						<li><a href="<?php echo base_url('safety/upload_workers/' . $information[0]['id_safety']); ?>">Workers</a>
						</li>						
						<li class='active'><a href="<?php echo base_url("admin/vehicle/1/x/2"); ?>">Review and Sign</a>
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

	<div class="row">
		<div class="col-lg-6">
			<div class="panel panel-primary">
				<div class="panel-body">
					<strong>Primary muster point: </strong><?php echo $information[0]["muster_point"]; ?><br>
					<?php if($information[0]["muster_point_2"]){ ?>
					<strong>Secondary muster point: </strong><?php echo $information?$information[0]["muster_point_2"]:""; ?><br>
					<?php }
						if($information[0]["primary_head_counter"]){ ?>
							<strong>Primary head counter: </strong><?php echo $information?$information[0]["primary_head_counter"]:""; ?><br>
					<?php } 
						if($information[0]["secondary_head_counter"]){ ?>
							<strong>Secondary head counter: </strong><?php echo $information?$information[0]["secondary_head_counter"]:""; ?><br>
					<?php } 
					$ppe=$information[0]['ppe']==1?"Yes":"No";
					?>
					<strong>PPE (Basic): </strong><?php echo $ppe; ?><br>
					<?php if($information[0]["specify_ppe"]){ ?>
					<strong>Specialized PPE: </strong><?php echo $information?$information[0]["specify_ppe"]:""; ?><br>
					<?php } ?>
					<strong>Date & Time: </strong><?php echo $information?$information[0]["date"]:""; ?>
				</div>
			</div>

	        <div class="panel panel-primary">
	            <div class="panel-heading">
	                <i class="fa fa-edit fa-fw"></i> Meeting conducted by - Signature
	            </div>

                <div class="panel-body">						
					<div class="form-group">
						<div class="row" align="center">
							<div style="width:80%;" align="center">
							<?php 								
								$class = "btn-primary";						
								if($information[0]["signature"])
								{ 
									$class = "btn-default";
							?>
									<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" >
										<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Signature
									</button>

									<div id="myModal" class="modal fade" role="dialog">  
										<div class="modal-dialog">
											<div class="modal-content">      
												<div class="modal-header">        
													<button type="button" class="close" data-dismiss="modal">×</button>        
													<h4 class="modal-title">Meeting conducted by Signature</h4>      </div>      
												<div class="modal-body text-center"><img src="<?php echo base_url($information[0]["signature"]); ?>" class="img-rounded" alt="Meeting conducted by Signature" width="304" height="236" />   </div>      
												<div class="modal-footer">        
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
												</div>  
											</div>  
										</div>
									</div>
							<?php
								}
							?>
								<!--
								<a class="btn <?php echo $class; ?>" href="<?php echo base_url("safety/add_signature/advisor/" . $information[0]["id_safety"] . "/x"); ?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Signature </a>
								-->

								<!-- campo para enviar el ID de SAFETY al MODAL -->
								<input type="hidden" id="hddIdSafety" name="hddIdSafety" value="<?php echo $information[0]['id_safety']; ?>"/>
							<?php if(!$information[0]["signature"]){ ?> 
								<button type="button" class="btn btn-outline btn-primary" data-toggle="modal" data-target="#modal" id="<?php echo "advisor-" . $information[0]['fk_id_user'] . "-x"; ?>" title="System Signature" >
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Use User Profile Signature
								</button>
							<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<!--
		<div class="col-lg-6">
			<div class="alert alert-info">
				<strong>COVID-19 Mitigation Strategies: </strong>
				<p>
					You must not start work until you have:
					<ul>
						<li>Splitting crew sizes</li>
						<li>Providing respirators and full-faceshields when distance cannot be maintained</li>
						<li>Utilizing additional equipment to maintain distancing</li>
						<li>Providing shielding to provide a barrier between workers</li>
						<li>Staggering breaks to prevent exposure</li>
						<li>Disinfecting tools that must be shared</li>
						<li>Cleaning offices lunch rooms and other common areas as per COVID-19 Cleaning schedule</li>
						<li>Social distancing of 6 feet required</li>
					</ul>
				</p>
			</div>
		</div>
		-->
	</div>

<!--INICIO HAZARDS -->
<?php 
	if($safetyHazard){
?>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-life-saver"></i> <strong>HAZARDS</strong>
				</div>
				<div class="panel-body">
				
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Activity</th>
								<th>Hazard</th>
								<th>Solution</th>
							</tr>
						</thead>
						<?php
							foreach ($safetyHazard as $data):
								$priority = $data['priority_description'];
								
								if($priority == 1 || $priority == 2) {
									$class = "success";
								}elseif($priority == 3 || $priority == 4) {
									$class = "info";
								}elseif($priority == 5 || $priority == 6) {
									$class = "warning";
								}elseif($priority == 7 || $priority == 8) {
									$class = "danger";
								}

								echo "<tr class='" . $class . "'>";					
								echo "<td class='text-" . $class . "'>" . $data['hazard_activity'] . "</td>";
								echo "<td class='text-" . $class . "'>" . $data['hazard_description'] . "</td>";
								echo "<td class='text-" . $class . "'>" . $data['solution']  . "</td>";
								echo "</tr>";
							endforeach;
						?>
					</table>
			
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN HAZARDS -->

<!--INICIO WORKERS -->
<?php 
	if($safetyWorkers){
?>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-user"></i> <strong>Lev West WORKERS</strong>
				</div>
				<div class="panel-body">

					<table class="table table-hover">
						<thead>
							<tr>
								<th>Name / Signature</th>
							</tr>
						</thead>

					<?php
						foreach ($safetyWorkers as $data):
							echo "<tr>";					

					?>
						<form name="personal_<?php echo $data['id_safety_worker']  ?>" id="personal_<?php echo $data['id_safety_worker'] ?>" method="post" action="<?php echo base_url("safety/save_worker_undestanding"); ?>">
							<input type="hidden" id="hddId" name="hddId" value="<?php echo $information[0]["id_safety"]; ?>" />
							<input type="hidden" id="hddIdSafetyWorker" name="hddIdSafetyWorker" value="<?php echo $data['id_safety_worker'] ; ?>" />
							
				
							<td >
								<?php echo $data['name']; ?>
								<br>
								<textarea id="description" name="description" class="form-control" rows="6" minlength="200" required placeholder="Worker's Understanding of Hazards Discussed"><?php echo $data['understanding']; ?></textarea>
								<br>
					<?php
							$class = "btn-primary";

							if($data['understanding'] == "") { 

					?>
								<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-xs" title="Save">
									Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
								</button>

					<?php
							}elseif($data['signature']){ 
								$class = "btn-default";
					?>
								<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#<?php echo $data['id_safety_worker'] . "wModal"; ?>" id="<?php echo $data['id_safety_worker']; ?>">
									<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Signature
								</button>

								<div id="<?php echo $data['id_safety_worker'] . "wModal"; ?>" class="modal fade" role="dialog">  
									<div class="modal-dialog">
										<div class="modal-content">      
											<div class="modal-header">        
												<button type="button" class="close" data-dismiss="modal">×</button>        
												<h4 class="modal-title">Worker Signature</h4>      </div>      
											<div class="modal-body text-center"><img src="<?php echo base_url($data['signature']); ?>" class="img-rounded" alt="Meeting conducted by Signature" width="304" height="236" />   </div>      
											<div class="modal-footer">    
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
											</div>  
										</div>  
									</div>
								</div>
					<?php
							}else{
					?>
							<!--
							<a class='btn <?php echo $class; ?> btn-sm' href='<?php echo base_url('safety/add_signature/worker/' . $data['fk_id_safety'] . '/' . $data['id_safety_worker']) ?>' id="btn-delete">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"> </span>  Signature
							</a>
							-->

							<button type="button" class="btn btn-outline btn-primary btn-sm" data-toggle="modal" data-target="#modal" id="<?php echo "worker-" . $data['fk_id_user'] . "-". $data['id_safety_worker']; ?>" title="System Signature" >
								<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Use User Profile Signature
							</button>

					<?php
							}
							echo "</small></td>"; 
					?>

							</td>

							</form>
							</tr>
						<?php endforeach; ?>
					</table>

				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN WORKERS -->

<!--INICIO SUBCONTRACTOR -->
<?php 
	if($safetySubcontractorsWorkers){
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
						foreach ($safetySubcontractorsWorkers as $data):
							echo "<tr>";					
							echo "<td >" . $data['worker_name'] . "</td>";
							echo "<td >" . $data['company_name'] . "</td>";
							echo "<td class='text-center'>";			
						?>

						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $data["signature"] ?? null,
							'formAction'      => base_url('safety/save_signature'),
							'height'          => 200,
							'signButtonText'  => ' Signature ',
							'id' 			  => $data['id_safety_subcontractor']
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
<!--FIN SUBCONTRACTOR -->
				</div>
			</div>
		</div>
	</div>	
</div>

<!--INICIO Modal -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal -->