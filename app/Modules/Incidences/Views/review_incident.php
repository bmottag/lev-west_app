<div id="page-wrapper">
	<br>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-life-saver"></i> <strong>INCIDENCES </strong>- INCIDENT/ACCIDENT REPORT
				</div>
				<div class="panel-body">

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
						<div class="col-lg-12">
							<div class="panel panel-primary">
								<div class="panel-body">
									<strong>Incident/Accident type: </strong><?php echo $information?$information[0]["incident_type"]:""; ?><br>
									<strong>What happened? </strong><br><?php echo $information?$information[0]["what_happened"]:""; ?><br>
									<strong>Date of Incident: </strong><br><?php echo $information?$information[0]["date_incident"]:""; ?><br>
									<strong>Time of Incident: </strong><br><?php echo $information?$information[0]["time"]:""; ?><br>
									<strong>What was the immediate cause? </strong><br><?php echo $information?$information[0]["immediate_cause"]:""; ?><br>
									<strong>What were the contributting factors? </strong><br><?php echo $information?$information[0]["uderlying_causes"]:""; ?><br>
									<strong>Corrective Actions:</strong><br><?php echo $information?$information[0]["corrective_actions"]:""; ?>
								</div>
							</div>
						</div>
					</div>

					<!--INICIO WORKERS -->
					<?php 
						if($personsInvolved){
					?>

						<div class="row">
							<div class="col-lg-12">				
								<div class="panel panel-primary">
									<div class="panel-heading">
										<i class="fa fa-user"></i> <strong>Person(s) Involved</strong>
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
											foreach ($personsInvolved as $data):
												echo "<tr>";					
												echo "<td >" . $data['person_name'] . "</td>";
												echo "<td class='text-center'>";
										?>


												<?= view('App\Views\template\signature_component', [
													'imageUrl'        => $data["person_signature"] ?? null,
													'formAction'      => base_url('incidences/save_signature_person_involved'),
													'height'          => 200,
													'signButtonText'  => ' Signature ',
													'id' 			  => $data['id_incident_person']
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

				</div>
			</div>
		</div>
	</div>	
</div>