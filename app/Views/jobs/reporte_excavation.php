<?php
	// create some HTML content
	$html = '<br><h2 align="center" style="color:#337ab7;">EXCAVATION AND TRENCHING PLAN<br><br></h2>
				<style>
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
					<th bgcolor="#337ab7" style="color:white;"><strong>Job Code/Name: </strong></th>
					<th colspan="3">' . strtoupper($info[0]['job_description']) . '</th>
				</tr>
			
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Name of the person creating the plan: </strong></th>
					<th>' . $info[0]['name']. '</th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Date & Time: </strong></th>
					<th>' . $info[0]['date_excavation'] . '</th>
				</tr>
			
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Project Location: </strong></th>
					<th colspan="3">' . $info[0]['project_location']. '</th>
				</tr>
			</table>';
		
		$html.= '<p><h1 align="center" style="color:#337ab7;">General Conditions</h1></p>';

		$html.= '<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Anticipated depth of excavation / trench </strong></th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Width </strong></th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Length </strong></th>
				</tr>
				<tr>
					<th>' . $info[0]['depth']. ' meters</th>
					<th>' . $info[0]['width'] . ' meters</th>
					<th>' . $info[0]['length'] . ' meters</th>
				</tr>
			</table>';

		$html.= '<br><br>';

		$html.= '<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th bgcolor="#337ab7" style="color:white;" width="60%"><strong>Question</strong></th>
					<th bgcolor="#337ab7" style="color:white;" width="20%" align="center"><strong>Yes </strong></th>
					<th bgcolor="#337ab7" style="color:white;" width="20%" align="center"><strong>No </strong></th>
				</tr>
				<tr>
					<th>Will or could this excavation / trench be considered a confined space?</th>';
		if($info[0]['confined_space'] == 1){
			$confined = 'X';
			$confined2 = '';
		}else{
			$confined = '';
			$confined2 = 'X';
		}

		$html.= '	<th align="center">' . $confined . '</th>
					<th align="center">' . $confined2 . '</th>
				</tr>

				<tr>
					<th>Will the excavation / trench atmospheric conditions be tested daily?</th>';
		if($info[0]['tested_daily'] == 1){
			$tested = 'X';
			$tested2 = '';
		}else{
			$tested = '';
			$tested2 = 'X';
		}

		$html.= '	<th align="center">' . $tested . '</th>
					<th align="center">' . $tested2 . '</th>
				</tr>
				<tr>
					<th>If yes, please explain</th>
					<th colspan="2">' . $info[0]['tested_daily_explanation'] . '</th>
				</tr>
				<tr>
					<th>Will ventilation be supplied inside the excavation / trench?</th>';
		if($info[0]['ventilation'] == 1){
			$ventilation = 'X';
			$ventilation2 = '';
		}else{
			$ventilation = '';
			$ventilation2 = 'X';
		}

		$html.= '	<th align="center">' . $ventilation . '</th>
					<th align="center">' . $ventilation2 . '</th>
				</tr>
				<tr>
					<th>If yes, please explain</th>
					<th colspan="2">' . $info[0]['ventilation_explanation'] . '</th>
				</tr>
				<tr>
					<th>Has a soil classification been conducted to determine soil type?</th>';
		if($info[0]['soil_classification'] == 1){
			$soil = 'X';
			$soil2 = '';
		}else{
			$soil = '';
			$soil2 = 'X';
		}

		$html.= '	<th align="center">' . $soil . '</th>
					<th align="center">' . $soil2 . '</th>
				</tr>
				<tr>
					<th>As a result of the selected soil classification tests listed above, soil is considered:</th>
					<th colspan="2">';
					switch ($info[0]['soil_type']) {
						case 1:
							$html.= 'Stable rock';
							break;
						case 2:
							$html.= 'Type A - unconfined comprehensive strength of 1.5 tsf or greater';
							break;
						case 3:
							$html.= 'Type B - unconfined comprehensive strength of 0.5 -1.5 tsf';
							break;
						case 4:
							$html.= 'Type C - unconfined comprehensive strength of 0.5 tsf or less';
							break;
					}


					$html.= '</th>
				</tr>
				<tr>
					<th>Description of safe work practices and anticipated work inside the excavation / trench</th>
					<th colspan="2">' . $info[0]['description_safe_work'];
				$html.= '	<ul>';
				if($info[0]['practice_work_alone']){
					$html.= '<li>Do not work alone</li>';
				}
				if($info[0]['practice_eye_contact']){
					$html.= '<li>Keep eye contact with Heavy Equipment operators</li>';
				}
				if($info[0]['practice_communication']){
					$html.= '<li>Maintain good communication with the crew</li>';
				}
				if($info[0]['practice_walls']){
					$html.= '<li>No worker shall enter any trench or excavation until the walls have been adequately cut back</li>';
				}
				if($info[0]['practice_protective_structures']){
					$html.= '<li>No worker shall enter any trench or excavation before temporary protective structures have been installed (shorings, trench jacks, sheet piling, cage)</li>';
				}
				if($info[0]['practice_identify_underground']){
					$html.= '<li>Identify all underground utilities and/or overhead powerlines</li>';
				}
				if($info[0]['practice_scope']){
					$html.= '<li>Understand the scope of work</li>';
				}
				if($info[0]['practice_site_locates']){
					$html.= '<li>Have a copy of the site Locates</li>';
				}
				if($info[0]['practice_provided_safe']){
					$html.= '<li>Use the provided safe means of entering and exiting the excavation (ladder, scaffold, mechanical devices, appropriate slopping of the ground)</li>';
				}
				if($info[0]['practice_traffic_control']){
					$html.= '<li>Use traffic control methods near roads or busy access ways</li>';
				}
				if($info[0]['practice_flaggers']){
					$html.= '<li>Use traffic controllers/flaggers</li>';
				}
				if($info[0]['practice_barricades']){
					$html.= '<li>Set up barricades</li>	';
				}
				$html.= '</ul>';
					$html.= '</th>
				</tr>
			</table>';

		$html.= '<p><h1 align="center" style="color:#337ab7;">Protection Methods & Systems</h1></p>';

		$html.= 'Choose the method of protection below that will be implemented:
					<ul>';
		if($info[0]['protection_sloping']){
			$html.= '<li>Sloping</li>';
		}
		if($info[0]['protection_type_a']){
			$html.= '<li>¾ to 1- Type A Soil</li>';
		}
		if($info[0]['protection_type_b']){
			$html.= '<li>1 to 1 - Type B Soil</li>';
		}
		if($info[0]['protection_type_c']){
			$html.= '<li>1 ½ to 1- Type C Soil</li>';
		}
		$html.= '</ul>';

		if($info[0]['protection_sloping']){
			$html.= '<p><img src="https://v-contracting.ca/app/images/sloping.jpg"></p>';
		}
		$html.= '<ul>';
		if($info[0]['protection_benching']){
			$html.= '<li>Benching - (Note: Benching in class C soil is prohibited.)</li>';
		}
		if($info[0]['protection_shoring']){
			$html.= '<li>Shoring</li>';
		}
		if($info[0]['protection_shielding']){
			$html.= '<li>Shielding</li>';
		}
		$html.= '</ul>';

		if($info[0]['additional_comments']){
			$html.= '<strong>';
			$html.= $info[0]['additional_comments'];
			$html.= '</strong>';			
		}

		$html.= '<p><h1 align="center" style="color:#337ab7;">Access & Egress</h1></p>';

		$html.= 'Choose the method of access / egress below that will be implemented:
					<ul>';
		if($info[0]['access_ladder']){
			$html.= '<li>Portable ladder(s) placed within 7 m of lateral travel</li>';
		}
		if($info[0]['access_ramp']){
			$html.= '<li>Ramp(s) placed within 15 m of lateral travel</li>';
		}
		if($info[0]['access_other']){
			$html.= '<li>Other means of access / egress: </li>';
		}
		$html.= '</ul>';

		if($info[0]['access_explain']){
			$html.= '<strong>';
			$html.= $info[0]['access_explain'];
			$html.= '</strong>';			
		}

		$html.= '<br><br>';		
		$html.= '<p><h1 align="center" style="color:#337ab7;">Affected Zone, Traffic & Utilities</h1></p>';

		$html.= '<br><br>';

		$html.= '<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th bgcolor="#337ab7" style="color:white;" width="60%"><strong>Question</strong></th>
					<th bgcolor="#337ab7" style="color:white;" width="20%" align="center"><strong>Yes </strong></th>
					<th bgcolor="#337ab7" style="color:white;" width="20%" align="center"><strong>No </strong></th>
				</tr>

				<tr>
					<th>Have utilities been located by a utility locate company?</th>';
		if($info[0]['confined_space'] == 1){
			$located = 'X';
			$located2 = '';
		}else{
			$located = '';
			$located2 = 'X';
		}

		$html.= '	<th align="center">' . $located . '</th>
					<th align="center">' . $located2 . '</th>
				</tr>

				<tr>
					<th colspan="3">If no, STOP. Utility locates must be performed before digging is initiated.</th>
				</tr>

				<tr>
					<th>Is an excavation permit required in this area or on this project?</th>';
		if($info[0]['permit_required'] == 1){
			$permit = 'X';
			$permit2 = '';
		}else{
			$permit = '';
			$permit2 = 'X';
		}

		$html.= '	<th align="center">' . $permit . '</th>
					<th align="center">' . $permit2 . '</th>
				</tr>

				<tr>
					<th>Will utility lines (overhead or underground electrical / water / steam / sewer / storm / etc.) be present?</th>';
		if($info[0]['utility_lines'] == 1){
			$utility = 'X';
			$utility2 = '';
		}else{
			$utility = '';
			$utility2 = 'X';
		}

		$html.= '	<th align="center">' . $utility . '</th>
					<th align="center">' . $utility2 . '</th>
				</tr>
				<tr>
					<th>If yes, explain:</th>
					<th colspan="2">' . $info[0]['utility_lines_explain'] . '</th>
				</tr>

				<tr>
					<th>Will any surface encumbrances be located within the affected zone of the trench?</th>';
		if($info[0]['encumbrances'] == 1){
			$encumbrances = 'X';
			$encumbrances2 = '';
		}else{
			$encumbrances = '';
			$encumbrances2 = 'X';
		}

		$html.= '	<th align="center">' . $encumbrances . '</th>
					<th align="center">' . $encumbrances2 . '</th>
				</tr>
				<tr>
					<th>If yes, explain method of support / protection:</th>
					<th colspan="2">' . $info[0]['method_support'] . '</th>
				</tr>

				<tr>
					<th>Will utility shutdown / shut off / or lock out tag out be required?</th>';
		if($info[0]['utility_shutdown'] == 1){
			$shutdown = 'X';
			$shutdown2 = '';
		}else{
			$shutdown = '';
			$shutdown2 = 'X';
		}

		$html.= '	<th align="center">' . $shutdown . '</th>
					<th align="center">' . $shutdown2 . '</th>
				</tr>
				<tr>
					<th colspan="3">If yes, reference the separate Hazardous Energy Control Plan</th>
				</tr>

				<tr>
					<th>Will spoil piles remain a minimum 60 cm from the excavation / trench edge?</th>';
		if($info[0]['spoil_piles'] == 1){
			$piles = 'X';
			$piles2 = '';
		}else{
			$piles = '';
			$piles2 = 'X';
		}

		$html.= '	<th align="center">' . $piles . '</th>
					<th align="center">' . $piles2 . '</th>
				</tr>';
		if($info[0]['spoil_piles'] == 1){
				$html.= '<tr>
							<th>If yes, are environmental controls in place to reduce runoff?</th>';
				if($info[0]['environmental_controls'] == 1){
					$environmental = 'X';
					$environmental2 = '';
				}else{
					$environmental = '';
					$environmental2 = 'X';
				}

				$html.= '	<th align="center">' . $environmental . '</th>
							<th align="center">' . $environmental2 . '</th>
						</tr>';
		}else{
				$html.= '<tr>
							<th>If no, will spoils be transported off site?</th>';
				if($info[0]['spoils_transported'] == 1){
					$transported = 'X';
					$transported2 = '';
				}else{
					$transported = '';
					$transported2 = 'X';
				}

				$html.= '	<th align="center">' . $transported . '</th>
							<th align="center">' . $transported2 . '</th>
						</tr>';
		}

		$html.= '<tr>
					<th>Will the excavation / trench be left open overnight?</th>';
		if($info[0]['open_overnight'] == 1){
			$overnight = 'X';
			$overnight2 = '';
		}else{
			$overnight = '';
			$overnight2 = 'X';
		}

		$html.= '	<th align="center">' . $overnight . '</th>
					<th align="center">' . $overnight2 . '</th>
				</tr>
				<tr>
					<th>If yes, describe methods to secure the excavation area from the public or bystanders:</th>
					<th colspan="2">' . $info[0]['methods_secure'] . '</th>
				</tr>

				<tr>
					<th>Will worker(s) accessing or working from the trench be exposed to vehicle traffic?</th>';
		if($info[0]['vehicle_traffic'] == 1){
			$traffic = 'X';
			$traffic2 = '';
		}else{
			$traffic = '';
			$traffic2 = 'X';
		}

		$html.= '	<th align="center">' . $traffic . '</th>
					<th align="center">' . $traffic2 . '</th>
				</tr>
				</table>';

		if($info[0]['excavation_sketch']){
			$html.= '<p><h1 align="center" style="color:#337ab7;">Excavation / Trench Sketch</h1></p>';
			$html.= 'Sketch or diagram of the excavation / trench.';
			
			$html.= '<h3 align="center"><img src="'. $info[0]['excavation_sketch'] .'" border="0" width="400" height="270" /></h3>';
		}

		$html.= '<br><br>';

		$html.= '<p><h1 align="center" style="color:#337ab7;">De-Watering</h1></p>';

		$html.= '<br><br>';

		$html.= '<table border="0" cellspacing="0" cellpadding="5">
				<tr>
					<th bgcolor="#337ab7" style="color:white;" width="60%"><strong>Question</strong></th>
					<th bgcolor="#337ab7" style="color:white;" width="20%" align="center"><strong>Yes </strong></th>
					<th bgcolor="#337ab7" style="color:white;" width="20%" align="center"><strong>No </strong></th>
				</tr>

				<tr>
					<th>Is it anticipated that de-watering will be needed / implemented?</th>';
		if($info[0]['dewatering_needed'] == 1){
			$dewatering = 'X';
			$dewatering2 = '';
		}else{
			$dewatering = '';
			$dewatering2 = 'X';
		}

		$html.= '	<th align="center">' . $dewatering . '</th>
					<th align="center">' . $dewatering2 . '</th>
				</tr>
				<tr>
					<th>If yes, explain equipment and procedures:</th>
					<th colspan="2">' . $info[0]['explain_equipment'] . '</th>
				</tr>

				<tr>
					<th>Is the excavation located next to a body of water (ocean, lake, stream, etc.)?</th>';
		if($info[0]['body_water'] == 1){
			$water = 'X';
			$water2 = '';
		}else{
			$water = '';
			$water2 = 'X';
		}

		$html.= '	<th align="center">' . $water . '</th>
					<th align="center">' . $water2 . '</th>
				</tr>
				<tr>
					<th>If de-watering is implemented, how will water discharge be conducted: </th>
					<th colspan="2">' . $info[0]['water_conducted'] . '</th>
				</tr>
				<tr>
					<th>Additional Notes:  </th>
					<th colspan="2">' . $info[0]['additional_notes'] . '</th>
				</tr>
				</table>';

		$html.= '<p><h1 align="center" style="color:#337ab7;">Approvals / Review </h1></p>';
		$html.= '<br><br>';

		$html.= '<table border="1" cellspacing="0" cellpadding="5">';
		$html.= '<tr>';
		$html.= '<th align="center" width="25%">';
				if($info[0]['operator_signature']){
					$html.= '<img src="'. $info[0]['operator_signature'] .'" border="0" width="70" height="70" />';
				}
		$html.= '</th>';
		$html.= '<th align="center" width="10%"></th>';
		$html.= '<th align="center" width="25%">';
				if($info[0]['supervisor_signature']){
					$html.= '<img src="'. $info[0]['supervisor_signature'] .'" border="0" width="70" height="70" />';
				}
		$html.= '</th>';
		$html.= '<th align="center" width="10%"></th>';
		$html.= '<th align="center" width="25%">';
				if($info[0]['manager_signature']){
					$html.= '<img src="'. $info[0]['manager_signature'] .'" border="0" width="70" height="70" />';
				}
		$html.= '</th>';
		$html.= '</tr>';
		$html.= '<tr bgcolor="#337ab7" style="color:white;">';
		$html.= '<th align="center"><strong>' . $info[0]['operator'] . '<br>Operator performing excavation</strong></th>';
		$html.= '<th></th>';
		$html.= '<th align="center"><strong>' . $info[0]['supervisor'] . '<br>Person supervising excavation</strong></th>';
		$html.= '<th></th>';
		$html.= '<th align="center"><strong>' . $info[0]['manager'] . '<br>Project Manager</strong></th>';
		$html.= '</tr>';
		$html.= '</table>';
		
		$html.= '<br><br>';

				if(!$excavationWorkers){			
						$html.= 'No data was found for workers';
				}else{				
				
						$html.= '<table border="1" cellspacing="0" cellpadding="5">';
								
						//pintar las firmas de a 4 por fila
						$total = count($excavationWorkers);//contar numero de trabajadores
						$totalFilas  = 1;
						if($total>=4)
						{//si es mayor 4 entonces calcular cuantas filas deben ser
							$div = $total / 4;
							$totalFilas = ceil($div); //redondeo hace arriba
						}

						$n = 1;
						for($i=0;$i<$totalFilas;$i++){
							$html.= '<tr>';	
							
									$finish = $n * 4;
									$star = $finish - 4;
									if($finish > $total){
										$finish = $total;
									}
									$n++;			
																							
									for ($j = $star; $j < $finish; $j++) {
				
										$html.= '<th align="center" width="20%">';
										
										if($excavationWorkers[$j]['signature']){
											$html.= '<img src="'.$excavationWorkers[$j]['signature'].'" border="0" width="70" height="70" />';
										}
										$html.= '</th>';
									}

							$html.= '</tr>';
							
							$html.= '<tr bgcolor="#337ab7" style="color:white;">';
										for ($j = $star; $j < $finish; $j++) {	
											$html.= '<th align="center"><strong>' . $excavationWorkers[$j]['name'] . '<br>VCI</strong></th>';
										}
							$html.= '</tr>';
						}
						
						$html.= '</table>';
				}
				
				$html.= '<br><br>';
				
				if($excavationSubcontractors){			
		
						$html.= '<table border="1" cellspacing="0" cellpadding="5">';
								
						//pintar las firmas de a 4 por fila
						$total = count($excavationSubcontractors);//contar numero de trabajadores
						$totalFilas  = 1;
						if($total>=5)
						{//si es mayor 4 entonces calcular cuantas filas deben ser
							$div = $total / 5;
							$totalFilas = ceil($div); //redondeo hace arriba
						}

						$n = 1;
						for($i=0;$i<$totalFilas;$i++){
							$html.= '<tr>';	
							
									$finish = $n * 5;
									$star = $finish - 5;
									if($finish > $total){
										$finish = $total;
									}
									$n++;			
																							
									for ($j = $star; $j < $finish; $j++) {
				
										$html.= '<th align="center" width="20%">';
										
										if($excavationSubcontractors[$j]['signature']){
											$html.= '<img src="'.$excavationSubcontractors[$j]['signature'].'" border="0" width="70" height="70" />';
										}
										$html.= '</th>';
									}

							$html.= '</tr>';
							
							$html.= '<tr bgcolor="#337ab7" style="color:white;">';
										for ($j = $star; $j < $finish; $j++) {	
											$html.= '<th align="center"><strong>'  . $excavationSubcontractors[$j]['worker_name'] . '<br>' . $excavationSubcontractors[$j]['company_name'] . '</strong></th>';
										}
							$html.= '</tr>';
							
						}
						
						$html.= '</table>';
				}

				$html.= '<br><br>';
			

echo $html;
						
?>