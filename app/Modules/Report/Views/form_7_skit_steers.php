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
				$html = '<h1 align="center" style="color:#337ab7;">SKIT STEERS & LOADERS INSPECTION REPORT</h1>
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
								<th><strong>Type: </strong><br>' . $type1 . ' - Construction Equipment</th>
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
								<th align="center" colspan="3"><strong>ENGINE</strong></th>
								<th align="center" colspan="3"><strong>ATTACHMENTS/TRACKS </strong></th>
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
					
						$html.='<th align="center"><strong>Bucket</strong></th>';
							if($info[$consecutivo]["bucket"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["bucket"] == 0){
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
					
						$html.='<th align="center"><strong>Cutting edges </strong></th>';
							if($info[$consecutivo]["cutting_edges"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["cutting_edges"] == 0){
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
					
						$html.='<th align="center"><strong>Tires / Pressure</strong></th>';
							if($info[$consecutivo]["tire_presurre"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["tire_presurre"] == 0){
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
					
						$html.='<th align="center"><strong>Rubber Tracks</strong></th>';
							if($info[$consecutivo]["rubber_trucks"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["rubber_trucks"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Transmission Fluid</strong></th>';
							if($info[$consecutivo]["transmission"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["transmission"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Forks </strong></th>';
							if($info[$consecutivo]["thamper"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["thamper"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Hydrolic fluids</strong></th>';
							if($info[$consecutivo]["hydrolic"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["hydrolic"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"></th>
								<th align="center"></th>
								<th align="center"></th>
								</tr>';
						
					$html.='<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" colspan="3"><strong>LIGHTS</strong></th>
								<th align="center" colspan="3"><strong>GREASING </strong></th>
							 </tr>';
						
						$html.='<tr>
								<th align="center"><strong>Work Lights </strong></th>';
							if($info[$consecutivo]["working_lamps"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["working_lamps"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Bucket Pins</strong></th>';
							if($info[$consecutivo]["bucket_pins_skit"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["bucket_pins_skit"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Beacon Lights</strong></th>';
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
					
						$html.='<th align="center"><strong>Side Arms</strong></th>';
							if($info[$consecutivo]["side_arms"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["side_arms"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"></th>
								<th align="center"></th>
								<th align="center"></th>';
					
						$html.='<th align="center"><strong>Articulated points </strong></th>';
							if($info[$consecutivo]["pivin_points"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["pivin_points"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='</tr>';
						
					$html.='<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" colspan="3"><strong>SERVICE</strong></th>
								<th align="center" colspan="3"><strong>EXTERIOR </strong></th>
							 </tr>';
						
						$html.='<tr>
								<th align="center"><strong>A/C & Heater</strong></th>';
							if($info[$consecutivo]["heater"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["heater"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Glass (All) & Mirror(s)</strong></th>';
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
								<th align="center"><strong>Operator Seat</strong></th>';
							if($info[$consecutivo]["operator_seat"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["operator_seat"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
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
					
						$html.='<th align="center"><strong>Wipers</strong></th>';
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
								<th align="center"><strong>Electrical Horn</strong></th>';
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
					
						$html.='<th align="center"><strong>Backup Beeper </strong></th>';
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
								<th align="center"><strong>Seatbelt </strong></th>';
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
					
						$html.='<th align="center"><strong>Access Door</strong></th>';
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
								<th align="center"><strong>Clean Interior  </strong></th>';
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
					
						$html.='<th align="center"><strong>Decals </strong></th>';
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
						
					$html.='<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" colspan="3"><strong>SAFETY </strong></th>
								<th align="center" colspan="3"><strong>EXTRAS</strong></th>
							 </tr>';
						
						$html.='<tr>
								<th align="center"><strong>Fire Extinguisher</strong></th>';
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

						$html.='<th align="center"><strong>Turn Signals </strong></th>';
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
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>First Aid - Accident Kit</strong></th>';
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
						
						$html.='<th align="center"><strong>Rims </strong></th>';
							if($info[$consecutivo]["rims"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["rims"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Spill kit</strong></th>';
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

						$html.='<th align="center"><strong>Emergency Brake </strong></th>';
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