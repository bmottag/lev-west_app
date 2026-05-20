<?php

				switch ($info[$consecutivo]['type_level_1']) {
					case 1:
						$type1 = 'Fleet';
						break;
					case 2:
						$type1 = 'Rental';
						break;
					case 99:
						$type1 = 'Other';
						break;
				}

				// create some HTML content
				$html = '<h1 align="center" style="color:#337ab7;">STREET SWEEPER INSPECTION REPORT</h1>
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
						<table border="1" cellspacing="0" cellpadding="5">
							<tr bgcolor="#337ab7" style="color:white;">
								<th><strong>Type: </strong><br>' . $type1 . ' - Special Equipment</th>
								<th><strong>Make: </strong><br>' . $info[$consecutivo]['make'] . '</th>
								<th><strong>Model: </strong><br>' . $info[$consecutivo]['model'] . '</th>
								<th><strong>Unit Number: </strong><br>' . $info[$consecutivo]['unit_number'] . '</th>
								<th><strong>Hrs/Km: </strong><br>' . $info[$consecutivo]['hours'] . '</th>
								<th><strong>Date: </strong><br>' . $info[$consecutivo]['date_issue'] . '</th>
							</tr>
						</table>
						<br><br>';
						
				$html.= '<table border="0" cellspacing="0" cellpadding="5">
							<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" width="30%"><strong>Items to Check</strong></th>
								<th align="center" width="10%"><strong>Pass</strong></th>
								<th align="center" width="10%"><strong>Fail</strong></th>
								<th align="center" width="30%"><strong>Items to Check</strong></th>
								<th align="center" width="10%"><strong>Pass</strong></th>
								<th align="center" width="10%"><strong>Fail</strong></th>
							</tr>';
						
					$html.='<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" colspan="3"><strong>ENGINE VEHICLE</strong></th>
								<th align="center" colspan="3"><strong>GREASING </strong></th>
							 </tr>';

						$html.='<tr>
								<th align="center"><strong>Belts/Hoses</strong></th>';
							if($info[$consecutivo]["belt"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["belt"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Stering Wheels</strong></th>';
							if($info[$consecutivo]["stering_wheels"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["stering_wheels"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Power Steering Fluid</strong></th>';
							if($info[$consecutivo]["power_steering"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["power_steering"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Drives</strong></th>';
							if($info[$consecutivo]["drives"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["drives"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
													
						$html.='<tr>
								<th align="center"><strong>Oil Level</strong></th>';
							if($info[$consecutivo]["oil_level"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["oil_level"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}

						$html.='<th align="center"><strong>Front drive shaft</strong></th>';
							if($info[$consecutivo]["front_drive"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["front_drive"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}			
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Coolant Level</strong></th>';
							if($info[$consecutivo]["coolant_level"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["coolant_level"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Elevator</strong></th>';
							if($info[$consecutivo]["elevator"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["elevator"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';

						$html.='<tr>
								<th align="center"><strong>Coolant/Oil Leaks</strong></th>';
							if($info[$consecutivo]["coolant_leaks"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["coolant_leaks"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Back Main Rotor </strong></th>';
							if($info[$consecutivo]["rotor"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["rotor"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Hydraulic Fluids</strong></th>';
							if($info[$consecutivo]["hydraulic"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["hydraulic"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Scissors Mixture Box  </strong></th>';
							if($info[$consecutivo]["mixture_box"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["mixture_box"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>';
						$html .= '<th align="center"><strong>DEF Level:</strong></th>';
						$html .= '<th colspan="2" align="center"><strong>' . $info[$consecutivo]["def"] . ' %</strong></th>';

						$html.='<th align="center"><strong>Left and Right Rotors</strong></th>';
							if($info[$consecutivo]["lf_rotor"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["lf_rotor"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
												
						$html.='<tr>
								<th bgcolor="#337ab7" style="color:white;" align="center" colspan="3"><strong>ENGINE SWEEPER  </strong></th>';
					
						$html.='<th bgcolor="#337ab7" style="color:white;" align="center" colspan="3"><strong>SWEEPER  </strong></th>';
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Belts/Hoses</strong></th>';
							if($info[$consecutivo]["belt_sweeper"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["belt_sweeper"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Elevator</strong></th>';
							if($info[$consecutivo]["elevator_sweeper"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["elevator_sweeper"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
												
						$html.='<tr>
								<th align="center"><strong>Oil Level</strong></th>';
							if($info[$consecutivo]["oil_level_sweeper"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["oil_level_sweeper"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Mixture Container </strong></th>';
							if($info[$consecutivo]["mixture_container"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["mixture_container"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
											
						$html.='<tr>
								<th align="center"><strong>Coolant Level</strong></th>';
							if($info[$consecutivo]["coolant_level_sweeper"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["coolant_level_sweeper"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Main broom</strong></th>';
							if($info[$consecutivo]["broom"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["broom"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Coolant/Oil Leaks</strong></th>';
							if($info[$consecutivo]["coolant_leaks_sweeper"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["coolant_leaks_sweeper"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
								
						$html.='<th align="center"><strong>Right broom</strong></th>';
							if($info[$consecutivo]["right_broom"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["right_broom"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';						
						
						$html.='<tr>
								<th bgcolor="#337ab7" style="color:white;" align="center" colspan="3"><strong>LIGHTS </strong></th>';
					
						$html.='<th align="center"><strong>Left broom</strong></th>';
							if($info[$consecutivo]["left_broom"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["left_broom"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Head Lamps</strong></th>';
							if($info[$consecutivo]["head_lamps"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["head_lamps"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Sprinkerls</strong></th>';
							if($info[$consecutivo]["sprinkerls"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["sprinkerls"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Hazard lights</strong></th>';
							if($info[$consecutivo]["hazard_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["hazard_lights"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Water Tank </strong></th>';
							if($info[$consecutivo]["water_tank"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["water_tank"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
																		
						$html.='<tr>
								<th align="center"><strong>Clearance lights </strong></th>';
							if($info[$consecutivo]["clearance_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["clearance_lights"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Water Tank Hose</strong></th>';
							if($info[$consecutivo]["hose"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["hose"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Tail Lights </strong></th>';
							if($info[$consecutivo]["tail_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["tail_lights"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Cam Viewer</strong></th>';
							if($info[$consecutivo]["cam"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["cam"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Work Lights </strong></th>';
							if($info[$consecutivo]["work_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["work_lights"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th bgcolor="#337ab7" style="color:white;" align="center" colspan="3"><strong>EXTERIOR</strong></th>';
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Turn signal lights</strong></th>';
							if($info[$consecutivo]["turn_signals"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["turn_signals"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Tires/Lug Nuts/Pressure </strong></th>';
							if($info[$consecutivo]["tires"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["tires"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Beacon Light</strong></th>';
							if($info[$consecutivo]["beacon_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["beacon_lights"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Glass (All) & Mirror(s) </strong></th>';
							if($info[$consecutivo]["windows"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["windows"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}

						$html.='</tr>';
						
						$html.='<tr>
								<th bgcolor="#337ab7" style="color:white;" align="center" colspan="3"><strong>SERVICE  </strong></th>';
					
						$html.='<th align="center"><strong>Clean Exterior </strong></th>';
							if($info[$consecutivo]["clean_exterior"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["clean_exterior"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
													 
						$html.='<tr>
								<th align="center"><strong>Brake Pedal</strong></th>';
							if($info[$consecutivo]["brake"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["brake"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Wipers / Washers </strong></th>';
							if($info[$consecutivo]["wipers"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["wipers"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Emergency Brake</strong></th>';
							if($info[$consecutivo]["emergency_brake"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["emergency_brake"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Backup Beeper</strong></th>';
							if($info[$consecutivo]["backup_beeper"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["backup_beeper"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Gauges: Volt / Fuel / Temp / Oil</strong></th>';
							if($info[$consecutivo]["gauges"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["gauges"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Driver and Passenger Door</strong></th>';
							if($info[$consecutivo]["door"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["door"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Electrical & Air Horn </strong></th>';
							if($info[$consecutivo]["horn"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["horn"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Decals</strong></th>';
							if($info[$consecutivo]["decals"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["decals"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Seatbelts</strong></th>';
							if($info[$consecutivo]["seatbelt"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["seatbelt"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th bgcolor="#337ab7" style="color:white;" align="center" colspan="3"><strong>SAFETY </strong></th>';

						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Driver & Passenger Seat</strong></th>';
							if($info[$consecutivo]["seat"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["seat"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Fire Extinguisher</strong></th>';
							if($info[$consecutivo]["fire_extinguisher"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["fire_extinguisher"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Insurance information</strong></th>';
							if($info[$consecutivo]["insurance"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["insurance"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>First Aid </strong></th>';
							if($info[$consecutivo]["first_aid"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["first_aid"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Registration</strong></th>';
							if($info[$consecutivo]["registration"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["registration"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Emergency kit</strong></th>';
							if($info[$consecutivo]["emergency_kit"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["emergency_kit"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Clean Interior</strong></th>';
							if($info[$consecutivo]["clean_interior"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["clean_interior"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Spill Kit </strong></th>';
							if($info[$consecutivo]["spill_kit"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["spill_kit"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						

											
						$html.='<tr>';
						$html.= '<th colspan="6"><strong>Comments</strong><br>' . $info[$consecutivo]["comments"] . '</th>';
						$html.='</tr>';
							
						$html.='</table>';
						
				if($info[$consecutivo]['signature']){
					//$urlSignature = base_url($info[$consecutivo]['signature']);
					$signature = '<img src="'.$info[$consecutivo]['signature'].'" border="0" width="70" height="70" />';
				}else{
					$signature = '-- There is no signature --';
				}
				$html.= '<br><br>';
				$html.= '<table border="1" cellspacing="0" cellpadding="5" width="40%">
						<tr bgcolor="#337ab7" style="color:white;">
							<th align="center"><strong>Driver Name</strong></th>
							<th align="center"><strong>Signature</strong></th>
						</tr>
						<tr>
							<th align="center"><br><br><br><br><br><strong>' . $info[$consecutivo]['name']. '</strong></th>
							<th align="center">' . $signature . '</th>
						</tr>
						</table>';
						
echo $html;
						
?>