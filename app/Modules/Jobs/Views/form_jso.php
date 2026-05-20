<script type="text/javascript" src="<?php echo base_url("assets/js/validate/jobs/jso.js?v=1.0.0"); ?>"></script>

<script>
$(function(){ 
	
	$(".btn-amarello").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'jobs/cargarModalWorker',
				data: {'idJobJso': oID, 'idJobJsoWorker': 'x'},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
		
	$(".btn-info").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + 'jobs/cargarModalWorker',
                data: {'idJobJso': '', 'idJobJsoWorker': oID},
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
			<div class="panel panel-info">
				<div class="panel-heading">
					<a class="btn btn-info btn-xs" href=" <?php echo base_url('jobs/jso/' . $jobInfo[0]["id_job"]); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-fire-extinguisher "></i> <strong>JSO - JOB SITE ORIENTATION</strong>
				</div>
				<div class="panel-body">
									
					<div class="alert alert-info">
						<h2>
							<span class="fa fa-briefcase" aria-hidden="true"></span>
							<strong>Job Code/Name: </strong><?php echo $jobInfo[0]['job_description']; ?>
						</h2>
						<?php 
						if($information){
								echo "<br><span class='fa fa-clock-o' aria-hidden='true'></span> <strong>Date: </strong>";
								echo $information[0]["date_issue_jso"]; 
								
								echo "<br><span class='fa fa-cloud-download' aria-hidden='true'></span> <strong>Download JSO: </strong>";
						?>
							<a href='<?php echo base_url('jobs/generaJSOPDF/' . $information[0]["id_job_jso"] ); ?>' target="_blank">PDF <img src='<?php echo base_url('images/pdf.png'); ?>' ></a>
						<?php 
						}
						?>						
						<br><br>
						This form is to be completed before a sub-contractor’s employee(s), visitor(s) and worker(s) commences work on any VCI site. 
						<br>
						Please complete all sections that are applicable to these worksite activities.
						<br>
						This form must be signed and dated by the individual facilitating the orientation and site oriented personnel.

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
	
	<?php if($information){ ?>
		<div class="row">

			<div class="col-lg-6">				
				<div class="panel panel-info">
					<div class="panel-heading">
						<strong><?php echo $information[0]["supervisor"] . ' Signature '; ?> *</strong>
					</div>
					<div class="panel-body">	
						
						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $information[0]["supervisor"] ?? null,
							'formAction'      => base_url('jobs/save_signature_jso'),
							'height'          => 200,
							'signButtonText'  => ' VCI Supervisor Signature ',
							'id' 			  => 'supervisor',
							'extraValue' 	  => $information[0]["id_job_jso"],
							'otherValue' 	  => 'supervisor'
						])?>
					
					</div>
				</div>
			</div>
		
			<div class="col-lg-6">				
				<div class="panel panel-info">
					<div class="panel-heading">
						<strong><?php echo $information[0]["manager"] . ' Signature '; ?> *</strong>
					</div>
					<div class="panel-body">	
						
						<?= view('App\Views\template\signature_component', [
							'imageUrl'        => $information[0]["manager_signature"] ?? null,
							'formAction'      => base_url('jobs/save_signature_jso'),
							'height'          => 200,
							'signButtonText'  => ' VCI Manager Signature ',
							'id' 			  => 'manager',
							'extraValue' 	  => $information[0]["id_job_jso"],
							'otherValue' 	  => 'manager'
						])?>
					
					</div>
				</div>
			</div>
		
		</div>
	<?php } ?> 
	
<?php if($information){ //solo se muestran las firmas cuando hay informacion ?> 
<!-- TRABAJADORES -->
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-amarello">
				<div class="panel-heading">
					<strong>WORKER(S)</strong>
				</div>
				<div class="panel-body">
<p class="text-left">By signing below the worker(s) agreed to comply with all VCI’s policies as well as with all safe operations required on
this specific site, also is aware of all potential hazards, keeping in mind that all equipment has the right of way. Do not
walk behind any piece of equipment before making EYE CONTACT with the operator.</p>	

					<div class="col-lg-12">	
												
					<button type="button" class="btn btn-amarello btn-block" data-toggle="modal" data-target="#modal" id="<?php echo $information[0]["id_job_jso"]; ?>">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Worker
					</button><br>
					
					</div>


<?php 
	if($infoWorkers){
?>
			<table class="table table-striped table-hover table-condensed">
				<thead>
					<tr class="default">
						<th>Name</th>
						<th>Position</th>
						<th>Worker mobile number</th>
						<th>Emergency contact</th>
						<th>Company Name</th>
						<th class='text-center'>Edit</th>
						<th class='text-center'>Signature</th>
					</tr>
				</thead>
				<?php
					foreach ($infoWorkers as $data):
						echo "<tr>";					
						echo "<td >" . $data['name'] . "</small></td>";
						echo "<td >" . $data['position'] . "</small></td>";
						echo "<td >" . $data['works_phone_number'] . "</small></td>";
						echo "<td >" . $data['emergency_contact'] . "</small></td>";
						echo "<td >" . $data['works_for'] . "</small></td>";
						echo "<td class='text-center'>";									
				?>
						<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal" id="<?php echo $data['id_job_jso_worker']; ?>" >
							<span class="glyphicon glyphicon-edit" aria-hidden="true">
						</button>									
				<?php
						echo "</td>";
						
						echo "<td class='text-center'>";
				?>

				<!-- enlace para enviar mensaje de texto al foreman -->
				<?php if($data['works_phone_number'] && !$data['signature']){ ?>
				<a href="<?php echo base_url("jobs/sendSMSworkerJSO/" . $data['id_job_jso_worker']); ?>" class="btn btn-default btn-sm"> 
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Send SMS to Worker
				</a>
				<?php } ?>

					<?= view('App\Views\template\signature_component', [
						'imageUrl'        => $data["signature"] ?? null,
						'formAction'      => base_url('jobs/save_signature_jso'),
						'height'          => 200,
						'signButtonText'  => ' Signature ',
						'id' 			  => 'worker_' . $data['id_job_jso_worker'],
						'extraValue' 	  => $data['id_job_jso_worker'],
						'otherValue' 	  => 'worker'
					]) ?>

				<?php
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
<!-- TRABAJADORES -->
	
<?php } ?>		

<form  name="form" id="form" class="form-horizontal" method="post"  >
	<input type="hidden" id="hddIdJob" name="hddIdJob" value="<?php echo $jobInfo[0]["id_job"]; ?>"/>
	<input type="hidden" id="hddIdentificador" name="hddIdentificador" value="<?php echo $information?$information[0]["id_job_jso"]:""; ?>"/>

							
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>IN CHARGE</strong>
				</div>
				<div class="panel-body">								
						<div class="form-group">
							<label class="col-sm-4 control-label" for="manager">Person in charge of conducting the site orientation: *</label>
							<div class="col-sm-5">
								<select name="supervisor" id="supervisor" class="form-control">
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information && $information[0]["fk_id_user_supervisor"] == $workersList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>								
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="coordinator">Manager: *</label>
							<div class="col-sm-5">
								<select name="manager" id="manager" class="form-control">
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>" <?php if($information && $information[0]["fk_id_user_manager"] == $workersList[$i]["id_user"]) { echo "selected"; }  ?>><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>							
							</div>
						</div>

				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>COMPANY EMPLOYEE ORIENTATION</strong>
				</div>
				<div class="panel-body">								
					<p class="text-info text-left">This section is to be completed during the corporate orientation session for this specific site.</p>						

					<div class="col-lg-3">
						<div class="form-group">
					
							<input type="checkbox" id="health_safety" name="health_safety" value=1 <?php if($information && $information[0]["health_safety"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Health and Safety Policies<br> 	
							<input type="checkbox" id="rights_responsibilities" name="rights_responsibilities" value=1 <?php if($information && $information[0]["rights_responsibilities"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Rights & Responsibilities<br>  	
							<input type="checkbox" id="company_safety_rules" name="company_safety_rules" value=1 <?php if($information && $information[0]["company_safety_rules"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Company Safety Rules<br>   		
							<input type="checkbox" id="hazard_awareness" name="hazard_awareness" value=1 <?php if($information && $information[0]["hazard_awareness"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Hazard Awareness<br>
							<input type="checkbox" id="reporting_procedures" name="reporting_procedures" value=1 <?php if($information && $information[0]["reporting_procedures"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Reporting Procedures							

						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">
					
							<input type="checkbox" id="personal_equipment" name="personal_equipment" value=1 <?php if($information && $information[0]["personal_equipment"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Personal Protective Equipment<br>		
							<input type="checkbox" id="drug_alcohol" name="drug_alcohol" value=1 <?php if($information && $information[0]["drug_alcohol"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Drug & Alcohol Policy<br>
							<input type="checkbox" id="environmental_reporting" name="environmental_reporting" value=1 <?php if($information && $information[0]["environmental_reporting"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Environmental Management & Reporting<br>
							<input type="checkbox" id="violence_workplace" name="violence_workplace" value=1 <?php if($information && $information[0]["violence_workplace"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Violence in the Workplace
							
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">
					
							<input type="checkbox" id="whmis" name="whmis" value=1 <?php if($information && $information[0]["whmis"]){echo "checked";}elseif(!$information){echo "checked";} ?> > WHMIS<br>					
							<input type="checkbox" id="equipment_operation" name="equipment_operation" value=1 <?php if($information && $information[0]["equipment_operation"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Equipment Operation<br>		
							<input type="checkbox" id="workplace_inspections" name="workplace_inspections" value=1 <?php if($information && $information[0]["workplace_inspections"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Workplace Inspections<br>		
							<input type="checkbox" id="accident_forms" name="accident_forms" value=1 <?php if($information && $information[0]["accident_forms"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Accident forms				
							
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">

							<input type="checkbox" id="first_aid" name="first_aid" value=1 <?php if($information && $information[0]["first_aid"]){echo "checked";}elseif(!$information){echo "checked";} ?> > First Aid<br>				
							<input type="checkbox" id="erp" name="erp" value=1 <?php if($information && $information[0]["erp"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Emergency Respond Plan<br>			
							<input type="checkbox" id="flha" name="flha" value=1 <?php if($information && $information[0]["flha"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Field Level Hazard Assessment<br>	
							<input type="checkbox" id="near_miss" name="near_miss" value=1 <?php if($information && $information[0]["near_miss"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Near Miss Report					
														
						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>SUBCONTRACTOR – VISITOR SITE ORIENTATION</strong>
				</div>
				<div class="panel-body">	
					<p class="text-info text-left">The following items are related to the safety, procedures, rights, requirements and obligation required by the person(s) been oriented and instructed. Check all topics that are applicable for this particular site.</p>						

					<div class="col-lg-3">
						<div class="form-group">
							<input type="checkbox" id="erp_subcontractor" name="erp_subcontractor" value=1 <?php if($information && $information[0]["erp_subcontractor"]){echo "checked";}elseif(!$information){echo "checked";} ?> > ERP<br>									
							<input type="checkbox" id="accident_incident" name="accident_incident" value=1 <?php if($information && $information[0]["accident_incident"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Accident/Incident<br>                   
							<input type="checkbox" id="preventive_maintenance" name="preventive_maintenance" value=1 <?php if($information && $information[0]["preventive_maintenance"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Preventive Maintenance<br>              
							<input type="checkbox" id="msds" name="msds" value=1 <?php if($information && $information[0]["msds"]){echo "checked";}elseif(!$information){echo "checked";} ?> > MSDS location (if applicable)<br>             
							<input type="checkbox" id="notification_hazards" name="notification_hazards" value=1 <?php if($information && $information[0]["notification_hazards"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Notification of Hazards<br>             
							<input type="checkbox" id="first_aid_subcontractor" name="first_aid_subcontractor" value=1 <?php if($information && $information[0]["first_aid_subcontractor"]){echo "checked";}elseif(!$information){echo "checked";} ?> > First Aid location(s)					
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">
							<input type="checkbox" id="smoking_drug" name="smoking_drug" value=1 <?php if($information && $information[0]["smoking_drug"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Smoking & drug Policy<br>			
							<input type="checkbox" id="flha_subcontractor" name="flha_subcontractor" value=1 <?php if($information && $information[0]["flha_subcontractor"]){echo "checked";}elseif(!$information){echo "checked";} ?> > FLHA’s<br>                          
							<input type="checkbox" id="environmental_management" name="environmental_management" value=1 <?php if($information && $information[0]["environmental_management"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Environmental Management<br>        
							<input type="checkbox" id="working_alone" name="working_alone" value=1 <?php if($information && $information[0]["working_alone"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Working Alone<br>                   
							<input type="checkbox" id="muster_point" name="muster_point" value=1 <?php if($information && $information[0]["muster_point"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Muster Point<br>                    
							<input type="checkbox" id="fire_extinguishers" name="fire_extinguishers" value=1 <?php if($information && $information[0]["fire_extinguishers"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Fire Extinguishers								
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">
							<input type="checkbox" id="personal_equipment_subcontractor" name="personal_equipment_subcontractor" value=1 <?php if($information && $information[0]["personal_equipment_subcontractor"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Personal Protective Equipment<br>			
							<input type="checkbox" id="equipment_inspections" name="equipment_inspections" value=1 <?php if($information && $information[0]["equipment_inspections"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Equipment/Vehicle Inspections<br>           
							<input type="checkbox" id="housekeeping" name="housekeeping" value=1 <?php if($information && $information[0]["housekeeping"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Housekeeping<br>                            
							<input type="checkbox" id="hazard_identification" name="hazard_identification" value=1 <?php if($information && $information[0]["hazard_identification"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Hazard Identification<br>                   
							<input type="checkbox" id="site_safe_work" name="site_safe_work" value=1 <?php if($information && $information[0]["site_safe_work"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Site Safe work Practices<br>                
							<input type="checkbox" id="site_safe_job" name="site_safe_job" value=1 <?php if($information && $information[0]["site_safe_job"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Site Safe Job Practices                     
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">
					
								<input type="checkbox" id="reporting" name="reporting" value=1 <?php if($information && $information[0]["reporting"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Reporting<br>			
								<input type="checkbox" id="attendance" name="attendance" value=1 <?php if($information && $information[0]["attendance"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Attendance<br>          
								<input type="checkbox" id="site_rules" name="site_rules" value=1 <?php if($information && $information[0]["site_rules"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Site Rules<br>          

							
						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>SITE EQUIPMENT IDENTIFICATION FOR THIS PROJECT</strong>
				</div>
				<div class="panel-body">
					<p class="text-info text-left">The following section is to assist all the workers in identifying the required experience / training prior to work on this site, as well as by operating any equipment. All equipment orientation must be recorded and maintained as documentation.</p>						

					<div class="col-lg-3">
						<div class="form-group">
							<input type="checkbox" id="low_boys" name="low_boys" value=1 <?php if($information && $information[0]["low_boys"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Low-boys<br>	
							<input type="checkbox" id="scaffolds" name="scaffolds" value=1 <?php if($information && $information[0]["scaffolds"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Scaffolds<br>       
							<input type="checkbox" id="light_towers" name="light_towers" value=1 <?php if($information && $information[0]["light_towers"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Light towers<br>                   
							<input type="checkbox" id="generators" name="generators" value=1 <?php if($information && $information[0]["generators"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Generators<br>             
							<input type="checkbox" id="hydrovacs" name="hydrovacs" value=1 <?php if($information && $information[0]["hydrovacs"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Hydrovacs							
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">					
							<input type="checkbox" id="hydroseeds" name="hydroseeds" value=1 <?php if($information && $information[0]["hydroseeds"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Hydroseeds<br>			
							<input type="checkbox" id="backhoe" name="backhoe" value=1 <?php if($information && $information[0]["backhoe"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Backhoes<br>                 
							<input type="checkbox" id="excavator" name="excavator" value=1 <?php if($information && $information[0]["excavator"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Excavators<br>               
							<input type="checkbox" id="forklift" name="forklift" value=1 <?php if($information && $information[0]["forklift"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Forklifts<br>                
							<input type="checkbox" id="cranes" name="cranes" value=1 <?php if($information && $information[0]["cranes"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Cranes								
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">
				
							<input type="checkbox" id="trailer_towing" name="trailer_towing" value=1 <?php if($information && $information[0]["trailer_towing"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Trailer Towing<br>					
							<input type="checkbox" id="power_tools" name="power_tools" value=1 <?php if($information && $information[0]["power_tools"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Power Tools<br>                     
							<input type="checkbox" id="dump_truck" name="dump_truck" value=1 <?php if($information && $information[0]["dump_truck"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Dump Truck<br>                      
							<input type="checkbox" id="hoists" name="hoists" value=1 <?php if($information && $information[0]["hoists"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Hoists / Lifting Devices<br>        
							<input type="checkbox" id="loader" name="loader" value=1 <?php if($information && $information[0]["loader"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Loader                              
						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">
					
							<input type="checkbox" id="light_vehicles" name="light_vehicles" value=1 <?php if($information && $information[0]["light_vehicles"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Light Vehicles<br>
							<input type="checkbox" id="conveyors" name="conveyors" value=1 <?php if($information && $information[0]["conveyors"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Conveyors<br>            
							<input type="checkbox" id="compressor" name="compressor" value=1 <?php if($information && $information[0]["compressor"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Compressor<br>

							<input type="checkbox" id="street_sweeper" name="street_sweeper" value=1 <?php if($information && $information[0]["street_sweeper"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Street Sweeper<br>
							<input type="checkbox" id="skid_steer" name="skid_steer" value=1 <?php if($information && $information[0]["skid_steer"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Skid steer<br>            
							<input type="checkbox" id="dozers" name="dozers" value=1 <?php if($information && $information[0]["dozers"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Dozers

							
						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>CERTIFICATIONS REQUIRED ON THIS SITE</strong>
				</div>
				<div class="panel-body">								
					<p class="text-info text-left">Identify all required training</p>						

					<div class="col-lg-3">
						<div class="form-group">
					
							<input type="checkbox" id="confined_space" name="confined_space" value=1 <?php if($information && $information[0]["confined_space"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Confined Space<br>	
							<input type="checkbox" id="fall_protection" name="fall_protection" value=1 <?php if($information && $information[0]["fall_protection"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Fall Protection<br>   
							<input type="checkbox" id="ground_disturbance" name="ground_disturbance" value=1 <?php if($information && $information[0]["ground_disturbance"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Ground disturbance

						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">

							<input type="checkbox" id="load_securement" name="load_securement" value=1 <?php if($information && $information[0]["load_securement"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Load Securement<br>   
							<input type="checkbox" id="tdg" name="tdg" value=1 <?php if($information && $information[0]["tdg"]){echo "checked";}elseif(!$information){echo "checked";} ?> > TDG<br>                   
							<input type="checkbox" id="first_aid_site" name="first_aid_site" value=1 <?php if($information && $information[0]["first_aid_site"]){echo "checked";}elseif(!$information){echo "checked";} ?> > First Aid

						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">

							<input type="checkbox" id="whmis_site" name="whmis_site" value=1 <?php if($information && $information[0]["whmis_site"]){echo "checked";}elseif(!$information){echo "checked";} ?> > WHMIS<br>						
							<input type="checkbox" id="traffic_control" name="traffic_control" value=1 <?php if($information && $information[0]["traffic_control"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Traffic Control<br>			
							<input type="checkbox" id="traffic_accommodation" name="traffic_accommodation" value=1 <?php if($information && $information[0]["traffic_accommodation"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Traffic accommodation

						</div>
					</div>
					
					<div class="col-lg-3">
						<div class="form-group">

							<input type="checkbox" id="safety_advisor" name="safety_advisor" value=1 <?php if($information && $information[0]["safety_advisor"]){echo "checked";}elseif(!$information){echo "checked";} ?> > NCSO / Safety Advisor<br>               
							<input type="checkbox" id="wib" name="wib" value=1 <?php if($information && $information[0]["wib"]){echo "checked";}elseif(!$information){echo "checked";} ?> > WIB<br>                
							<input type="checkbox" id="safe_trenching" name="safe_trenching" value=1 <?php if($information && $information[0]["safe_trenching"]){echo "checked";}elseif(!$information){echo "checked";} ?> > Safe Trenching								

						</div>
					</div>
										
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>Potential hazards: (Refer to Job hazard analysis)</strong>
				</div>
				<div class="panel-body">													
						
						<div class="form-group">
							<div class="col-sm-12">
							<textarea id="potential_hazards" name="potential_hazards" placeholder="Potential hazards"  class="form-control" rows="7"><?php echo $information?$information[0]["potential_hazards"]:""; ?></textarea>
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
				</div>
			</div>
		</div>
	</div>

	


						<div class="form-group">
							<div class="row" align="center">
								<div style="width:100%;" align="center">
									<button type="button" id="btnSubmit" name="btnSubmit" class='btn btn-primary'>
											Save <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button>
								</div>
							</div>
						</div>

								
						<div class="form-group">
							<div class="row" align="center">
								<div style="width:80%;" align="center">
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
							</div>
						</div>								

	
</form>




</div>


<!--INICIO Modal para OCASIONAL-->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal para OCASIONAL -->