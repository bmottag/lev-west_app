<?php
	// create some HTML content
	$html = '<h3 align="right" style="color:#337ab7;">Project code: ' . $info[0]['job_description'] . '<br></h3>';
	
	$html .= '<br><h2 align="center" style="color:#337ab7;">SUB CONTRACTOR, VISITOR, & WORKER SITE ORIENTATION<br></h2>';
	
	$html .= 'This form is to be completed before a sub-contractor’s employee(s), visitor(s) and worker(s) commences work on our site. Please
complete all sections that are applicable to these worksite activities.<br>
This form must be signed and dated by the individual facilitating the orientation and site oriented personnel.';

	$html .= '<br><h2 align="center" style="color:#337ab7;">COMPANY EMPLOYEE ORIENTATION<br></h2>';	
	
	$html .= '<style>
				table {
					font-family: arial, sans-serif;
					border-collapse: collapse;
					width: 100%;
				}

				td, th {
					border: 1px solid #dddddd;
					text-align: left;
					padding: 8px;
				}
				</style>
			<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th colspan="4"><strong>This section is to be completed during the corporate orientation session for this specific site. </strong></th>
				</tr>';
			
			$html.='<tr>
						<th>';
						if($info[0]['health_safety'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Health and Safety Policies<br>';
					
						if($info[0]['rights_responsibilities'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
						$html.= ' Rights & Responsibilities<br>';
					
						if($info[0]['company_safety_rules'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Company Safety Rules<br>';
					
						if($info[0]['hazard_awareness'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Hazard Awareness<br>';
					
						if($info[0]['reporting_procedures'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Reporting Procedures';

					$html .= '</th>';
					
					
					$html.='<th>';
					
						if($info[0]['personal_equipment'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' PPE<br>';
					
						if($info[0]['drug_alcohol'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Drug & Alcohol Policy<br>';
					
						if($info[0]['environmental_reporting'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Environmental Management & Reporting<br>';
					
						if($info[0]['violence_workplace'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Violence in the Workplace ';
					
					$html .= '</th>';
					
					$html.='<th>';
						if($info[0]['whmis'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' WHMIS<br>';
					
						if($info[0]['equipment_operation'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
						$html.= ' Equipment Operation<br>';
					
						if($info[0]['workplace_inspections'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Workplace Inspections<br>';
					
						if($info[0]['accident_forms'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Accident forms';

					$html .= '</th>';
					
					$html.='<th>';
						if($info[0]['first_aid'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' First Aid<br>';
					
						if($info[0]['erp'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
						$html.= ' Emergency Respond Plan<br>';
					
						if($info[0]['flha'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' FLHA<br>';
					
						if($info[0]['near_miss'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Near Miss Report';

					$html .= '</th>';
					
				$html.= '</tr>';
				
			$html.='</table><br>';
			
	$html .= '<h2 align="center" style="color:#337ab7;">SUBCONTRACTOR – VISITOR SITE ORIENTATION<br></h2>';
	
	
	$html .= '<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th colspan="4"><strong>The following items are related to the safety, procedures, rights, requirements and obligation required by the person(s) been oriented
and instructed. Check all topics that are applicable for this particular site.</strong></th>
				</tr>';
			
			$html.='<tr>
						<th>';
						if($info[0]['erp_subcontractor'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' ERP<br>';
					
						if($info[0]['accident_incident'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Accident/Incident<br>';
					
						if($info[0]['preventive_maintenance'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Preventive Maintenance<br>';
					
						if($info[0]['msds'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' MSDS location<br>';
					
						if($info[0]['notification_hazards'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Notification of Hazards<br>';
					
						if($info[0]['first_aid_subcontractor'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' First Aid location(s)';

					$html .= '</th>';
					
					$html.='<th>';
						if($info[0]['smoking_drug'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Smoking & drug Policy<br>';
					
						if($info[0]['flha_subcontractor'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' FLHA’s<br>';
					
						if($info[0]['environmental_management'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Environmental Management<br>';
					
						if($info[0]['working_alone'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Working Alone<br>';
					
						if($info[0]['muster_point'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Muster Point<br>';
					
						if($info[0]['fire_extinguishers'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Fire Extinguishers';

					$html .= '</th>';
					
					$html.='<th>';
						if($info[0]['personal_equipment_subcontractor'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' PPE<br>';
					
						if($info[0]['equipment_inspections'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Equipment/Vehicle Inspections<br>';
					
						if($info[0]['housekeeping'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Housekeeping<br>';
					
						if($info[0]['hazard_identification'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Hazard Identification<br>';
					
						if($info[0]['site_safe_work'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Site Safe work Practices<br>';
					
						if($info[0]['site_safe_job'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Site Safe Job Practices';

					$html .= '</th>';
					
					$html.='<th>';
						if($info[0]['first_aid'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Reporting<br>';
					
						if($info[0]['attendance'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Attendance<br>';
					
						if($info[0]['site_rules'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Site Rules<br>';

					$html .= '</th>';
					
				$html.= '</tr>';
				
			$html.='</table><br><br>';
			
$html .= '<h2 align="center" style="color:#337ab7;">SITE EQUIPMENT IDENTIFICATION FOR THIS PROJECT<br></h2>';
			
	$html .= '<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th colspan="4"><strong>The following section is to assist all the workers in identifying the required experience / training prior to work on this site,
as well as by operating any equipment. All equipment orientation must be recorded and maintained as documentation.</strong></th>
				</tr>';
			
			$html.='<tr>
						<th>';
						if($info[0]['low_boys'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Low-boys<br>';
					
						if($info[0]['scaffolds'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Scaffolds<br>';
					
						if($info[0]['light_towers'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Light towers<br>';
					
						if($info[0]['generators'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Generators<br>';
					
						if($info[0]['hydrovacs'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Hydrovacs';

					$html .= '</th>';
					
					$html.='<th>';
						if($info[0]['hydroseeds'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Hydroseeds<br>';
					
						if($info[0]['backhoe'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Backhoes<br>';
					
						if($info[0]['excavator'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Excavators<br>';
					
						if($info[0]['forklift'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Forklifts<br>';
					
						if($info[0]['cranes'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Cranes';

					$html .= '</th>';
					
					$html.='<th>';
						if($info[0]['trailer_towing'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Trailer Towing<br>';
					
						if($info[0]['power_tools'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Power Tools<br>';
					
						if($info[0]['dump_truck'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Dump Truck<br>';
					
						if($info[0]['hoists'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Hoists / Lifting Devices<br>';
					
						if($info[0]['loader'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Loader';

					$html .= '</th>';
					
					$html.='<th>';
						if($info[0]['light_vehicles'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Light Vehicles<br>';
					
						if($info[0]['conveyors'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Conveyors<br>';
					
						if($info[0]['compressor'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Compressor<br>';

						if($info[0]['street_sweeper'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Street Sweeper<br>';
					
						if($info[0]['skid_steer'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Skid steer<br>';
					
						if($info[0]['dozers'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Dozers';
					
					$html .= '</th>';
					
				$html.= '</tr>';
				
			$html.='</table><br><br>';
			
$html .= '<h2 align="center" style="color:#337ab7;">CERTIFICATIONS REQUIRED ON THIS SITE<br></h2>';
			
	$html .= '<table border="0" cellspacing="0" cellpadding="5">';
			
			$html.='<tr>
						<th>';
						if($info[0]['confined_space'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Confined Space<br>';
					
						if($info[0]['fall_protection'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Fall Protection<br>';
					
						if($info[0]['ground_disturbance'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Ground disturbance';

					$html .= '</th>';
					
					$html.='<th>';
						if($info[0]['load_securement'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Load Securement<br>';
					
						if($info[0]['tdg'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' TDG<br>';
					
						if($info[0]['first_aid_site'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' First Aid';
					
					$html .= '</th>';
					
					$html.='<th>';
					
						if($info[0]['whmis_site'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' WHMIS<br>';
					
						if($info[0]['traffic_control'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Traffic Control<br>';

						if($info[0]['traffic_accommodation'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Traffic accommodation';
					
					$html .= '</th>';
					
					$html.='<th>';
					
						if($info[0]['safety_advisor'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' NCSO / Safety Advisor<br>';
					
						if($info[0]['wib'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' WIB<br>';
					
						if($info[0]['safe_trenching'] == 1){
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
						}else{
							$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
						}
					$html.= ' Safe Trenching ';
					
					$html .= '</th>';
					
				$html.= '</tr>';
				
			$html.='</table><br><br>';
			
			
		if($info[0]["potential_hazards"] != '')
		{
			$html .= '<h3 align="left" style="color:#337ab7;">Potential hazards: (Refer to Job hazard analysis)</h3>';
				
			$html .= $info[0]["potential_hazards"];

			$html.='<br>';
		}
		
			//INICIO COVID
			$html .= '<h3 align="left" style="color:#337ab7;">Additional hazards related to COVID-19</h3>';
				
			$html .= 'If you answer "YES" to any of the following questions, you are not permitted to attend work at this time and you must self-isolate.';
			
			$html .= '<ul>';
			$html .= '<li>Do you have any of the following symptoms which are new or worsened if associated with
allergies, chronic or pre-existing conditions: fever, cough, shortness of breath, difficulty
breathing, sore throat, and/or runny nose?</li>';
			$html .= '<li>Have you returned to Canada from outside the country (including USA) in the past 14 days?</li>';
			$html .= '<li>Did you have close contact* with a person who has a probable** or confirmed case of COVID-19?</li>';
			$html .= '<li>Did you have close contact* with a person who had an acute respiratory illness that started within
14 days of their close contact* to someone with a probable** or confirmed case of COVID-19?</li>';
			$html .= '<li>Did you have close contact* with a person who had an acute respiratory illness who returned from
travel outside of Canada in the 14 days before they became sick?</li>';
			$html .= '<li>Did you have a laboratory exposure to biological material (i.e. primary clinical specimens, virus
culture isolates) known to contain COVID-19?</li>';
			$html .= '</ul>';

			$html.='<br><br><br><br>';
			//FIN COVID
		

//FIRMAS SUPERVISOR Y MANAGER
			$html.= '<table border="0" cellspacing="0" cellpadding="5">';

			$html.= '<tr>';
			
			$html.= '<th align="center" width="30%">';
				if($info[0]['supervisor_signature']){
					$html.= '<img src="'.$info[0]['supervisor_signature'] . '" border="0" width="70" height="70" />';
				}
			$html.= '</th>';
			
			$html.= '<th align="center" width="40%"></th>';
			
			$html.= '<th align="center" width="30%">';
				if($info[0]['manager_signature']){
					$html.= '<img src="' . $info[0]['manager_signature'] . '" border="0" width="70" height="70" />';
				}
			$html.= '</th>';
		
			$html.= '</tr>';
			
			$html.= '<tr bgcolor="#337ab7" style="color:white;">
						<th align="center"><strong>' . $info[0]['supervisor'] . '</strong></th>
						<th align="center" width="40%"></th>
						<th align="center"><strong>' . $info[0]['manager'] . '</strong></th>';
			$html.= '</tr>';
			
			$html.= '<tr bgcolor="#337ab7" style="color:white;">
						<th align="center"><strong>Supervisor Signature</strong></th>
						<th align="center" width="40%"></th>
						<th align="center"><strong>Manager Signature</strong></th>';
			$html.= '</tr>';
			
			$html.= '</table>';

echo $html;
						
?>