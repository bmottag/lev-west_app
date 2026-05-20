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
		<!-- /.col-lg-12 -->				
	</div>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-life-saver"></i> SAFETY - CLOSE
				</div>
				<div class="panel-body">
							
					<div class="row">
						<div class="col-lg-6">								
							<div class="alert alert-info">
							<?php
								$ppe=$information[0]['ppe']==1?"Yes":"No";
							?>
							<strong>Task: </strong>Field Level Hazard Assessment
							<br><strong>Work To Be Done: </strong><?php echo $information?$information[0]["work"]:""; ?>
							<br><strong>Job Code/Name: </strong><?php echo $information?$information[0]["job_description"]:""; ?>
							<br><strong>Muster Point: </strong><?php echo $information?$information[0]["muster_point"]:""; ?>
							<br><strong>PPE: </strong><?php echo $ppe; ?>
							<?php if($information[0]["specify_ppe"]){ ?>
							<br><strong>Specialized PPE: </strong><?php echo $information?$information[0]["specify_ppe"]:""; ?>
							<?php } ?>
							</div>
						</div>
								
						<div class="col-lg-6">
							<div class="form-group">
								<div class="row" align="center">
									<div style="width:80%;" align="center">
									<?php 								
										$class = "btn-primary";						
										if($information[0]["signature"]){ 
											$class = "btn-default";
									?>
<img src="<?php echo base_url($information[0]["signature"]); ?>" class="img-rounded" alt="Meeting conducted by Signature" width="204" height="136" /> 

									<br><strong>Meeting conducted by Signature</strong>
									<?php
										}else{
											echo "<br>No Signature";
										}
									?>
									</div>
								</div>
							</div>
						</div>

					</div>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	
	
	
<!--INICIO HAZARDS -->								
<?php if($information){ ?>
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>HAZARDS</strong>
				</div>
				<div class="panel-body">								
				<?php 
					if($safetyHazard){
				?>
				<table class="table table-bordered table-striped table-hover table-condensed">
					<tr>
						<td><p class="text-center"><strong>Hazard</strong></p></td>
						<td><p class="text-center"><strong>Solution</strong></p></td>
						<td><p class="text-center"><strong>Priority</strong></p></td>
					</tr>
					<?php
						foreach ($safetyHazard as $data):
							echo "<tr>";					
							echo "<td ><small>" . $data['hazard_description'] . "</small></td>";
							echo "<td ><small>" . $data['solution']  . "</small></td>";
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
											
							echo "<td class='text-center " . $class . "'><p class='text-" . $class . "'><strong>" . $data['priority_description'] . "</strong></p></td>";
							
							echo "</tr>";
						endforeach;
					?>
				</table>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN HAZARDS -->


								
<!--INICIO WORKERS -->								
<?php if($information){ ?>
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-warning">
				<div class="panel-heading">
					<strong>WORKERS</strong>
				</div>
				<div class="panel-body">										
				<?php 
					if($safetyWorkers){
				?>
					<table class="table table-bordered table-striped table-hover table-condensed">
						<tr>
							<td><p class="text-center"><strong>Name</strong></p></td>
							<td><p class="text-center"><strong>Signature</strong></p></td>
						</tr>
						<?php
							foreach ($safetyWorkers as $data):
								echo "<tr>";					
								echo "<td ><small>" . $data['name'] . "</small></td>";
								echo "<td class='text-center'>";
								
								if($data["signature"]){
						?>
									<img src="<?php echo base_url($data["signature"]); ?>" class="img-rounded" alt="Worker Signature" width="104" height="36" />				
						<?php
								}else{
									echo "No Signature";
								}
								echo "</td>";
								echo "</tr>";
							endforeach;
						?>
					</table>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN WORKERS -->
				

				
<!--INICIO SUBCONTRACTOR WORKERS -->								
<?php if($information){ ?>
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-danger">
				<div class="panel-heading">
					<strong>SUBCONTRACTOR WORKERS</strong>
				</div>
				<div class="panel-body">										
				<?php 
					if($safetySubcontractorsWorkers){
				?>
					<table class="table table-bordered table-striped table-hover table-condensed">
						<tr>
							<td><p class="text-center"><strong>Name</strong></p></td>
							<td><p class="text-center"><strong>Company</strong></p></td>
							<td><p class="text-center"><strong>Signature</strong></p></td>
						</tr>
						<?php
							foreach ($safetySubcontractorsWorkers as $data):
								echo "<tr>";					
								echo "<td ><small>" . $data['worker_name'] . "</small></td>";
								echo "<td ><small>" . $data['company_name'] . "</small></td>";
								echo "<td class='text-center'>";
								
								if($data["signature"]){
						?>
									<img src="<?php echo base_url($data["signature"]); ?>" class="img-rounded" alt="Worker Signature" width="104" height="36" />				
						<?php
								}else{
									echo "No Signature";
								}
								echo "</td>";
								echo "</tr>";
							endforeach;
						?>
					</table>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!--FIN SUBCONTRACTOR WORKERS -->

	
</div>
<!-- /#page-wrapper -->