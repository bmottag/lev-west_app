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
				$html = '<h1 align="center" style="color:#337ab7;">GENERATOR & LIGHT TOWERS INSPECTION REPORT</h1>
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
								<th align="center" colspan="3"><strong>ENGINE</strong></th>
								<th align="center" colspan="3"><strong>LIGHTS </strong></th>
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
					
						$html.='<th align="center"><strong>Turn signal lights</strong></th>';
							if($info[$consecutivo]["turn_signal"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["turn_signal"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Fuel Filter</strong></th>';
							if($info[$consecutivo]["fuel_filter"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["fuel_filter"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Hazard lights</strong></th>';
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

						$html.='<th align="center"><strong>Tail lights</strong></th>';
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
					
						$html.='<th align="center"><strong>Flood Lights </strong></th>';
							if($info[$consecutivo]["flood_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["flood_lights"] == 0){
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
					
						$html.='<th align="center" colspan="3"><strong></strong></th>';

						$html.='</tr>';
						
					$html.='<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" colspan="3"><strong>SERVICE</strong></th>
								<th align="center" colspan="3"><strong>EXTERIOR </strong></th>
							 </tr>';
						
						$html.='<tr>
								<th align="center"><strong>Boom</strong></th>';
							if($info[$consecutivo]["boom"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["boom"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Tires/Lug Nuts/Pressure</strong></th>';
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
								<th align="center"><strong>Landing Gears</strong></th>';
							if($info[$consecutivo]["gears"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["gears"] == 0){
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
								<th align="center"><strong>Retractive Pulley</strong></th>';
							if($info[$consecutivo]["pulley"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["pulley"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center" colspan="3" rowspan="3"><strong></strong></th>';

						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Electrical Outlets  </strong></th>';
							if($info[$consecutivo]["electrical"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["electrical"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Brackers </strong></th>';
							if($info[$consecutivo]["brackers"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($info[$consecutivo]["brackers"] == 0){
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