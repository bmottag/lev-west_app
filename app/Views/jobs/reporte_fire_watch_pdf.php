<?php
	// create some HTML content
	$html = '<h3 align="right" style="color:#337ab7;">Project code: ' . $info[0]['job_description'] . '</h3><br>';
	
	$html .= '<br><h2 align="center" style="color:#337ab7;">Fire Watch Record<br><br></h2>
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
					<th width="35%"  bgcolor="#337ab7" style="color:white;"><strong>Facility/Building Address: </strong></th>
					<th width="65%" >' . strtoupper($info[0]['building_address']) . '</th>
				</tr>
			</table>';

	$html .= '<table border="0" cellspacing="0" cellpadding="5">
			<tbody>
				<tr>
					<th width="35%" bgcolor="#337ab7" style="color:white;"><strong>Systems Shutdown: </strong></th>
					<th width="65%" >';
					if($info[0]['fire_alarm'] == 1){
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
					}else{
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
					}
					$html.= ' Fire Alarm System<br>';

					if($info[0]['fire_sprinkler'] == 1){
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
					}else{
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
					}
					$html.= ' Fire Sprinkler System<br>';

					if($info[0]['standpipe'] == 1){
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
					}else{
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
					}
					$html.= ' Standpipe System<br>';

					if($info[0]['fire_pump'] == 1){
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
					}else{
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
					}
					$html.= ' Fire Pump System<br>';


					if($info[0]['fire_suppression'] == 1){
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark.png" height="12" width="12"/>';
					}else{
						$html .= '<img src="http://v-contracting.ca/app/images/check_mark_whyte.png" height="12" width="12"/>';
					}
					$html.= ' Special Fire Suppression System<br>';

					$html.= ' Other: ' . $info[0]['other'];


					$html .= '</th>
				</tr>
			</tbody>
		</table>';

		$CompleteDateOut = $info[0]['date_out'];
		$date = substr($CompleteDateOut, 0, 10); 
		$time = substr($CompleteDateOut, 11, 2) . ':00';
	
		$date2 = "";
		$time2 = "";

		$CompleteDateRestored = $info[0]['date_restored'] ?? null;

		if (!empty($CompleteDateRestored) && $CompleteDateRestored !== "0000-00-00 00:00:00") {
			$date2 = substr($CompleteDateRestored, 0, 10);
			$time2 = substr($CompleteDateRestored, 11, 2) . ':00';
		}

		$html .= '<table border="0" cellspacing="0" cellpadding="5">
					<tbody>
						<tr>
							<th width="35%" bgcolor="#337ab7" style="color:white;"><strong>System(s) Out of Service: </strong></th>
							<th width="32%"><strong>Date: </strong>' . $date   . '</th>
							<th width="33%"><strong>Time: </strong>' . $time . '</th>
						</tr>

						<tr>
							<th bgcolor="#337ab7" style="color:white;"><strong>System(s) Restored Online: </strong></th>
							<th><strong>Date: </strong>' . $date2   . '</th>
							<th><strong>Time: </strong>' . $time2 . '</th>
						</tr>

						<tr>
							<th bgcolor="#337ab7" style="color:white;"><strong>Areas/Zones Requiring Fire Watch Patrols: </strong></th>
							<th colspan="2" width="65%">' . $info[0]['areas']   . '</th>
						</tr>
					</tbody>
				</table>';

	$html .= '<br><h2 style="color:#337ab7;">Persons Conducting Fire Watch Service</h2>';
	$html .= '<table id="persons" cellspacing="0" cellpadding="5">
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Name </strong></th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Company </strong></th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Contact Number </strong></th>
				</tr>';

	if($checkinList){
		foreach ($checkinList as $lista):
			$html .=  "<tr>";
			$html .=  "<td>" . $lista['first_name'] . " " . $lista['last_name'] . "</td>";
			$html .=  "<td class='text-center'>Lev West</td>";
			$html .=  "<td class='text-center'>" . $lista['movil'] . "</td>";
			$html .=  "</tr>";
		endforeach;
	}
	$html.='</table><br>';

	$mobile = $info[0]["super_number"];
	// Separa en grupos de tres 
	$count = strlen($mobile); 
		
	$num_tlf1 = substr($mobile, 0, 3); 
	$num_tlf2 = substr($mobile, 3, 3); 
	$num_tlf3 = substr($mobile, 6, 2); 
	$num_tlf4 = substr($mobile, -2); 
	
	if($count == 10){
		$resultado = "$num_tlf1 $num_tlf2 $num_tlf3 $num_tlf4";  
	}else{
		
		$resultado = chunk_split($mobile,3," "); 
	}

	$html.= '<p><strong>Supervisor’s Name:</strong> '. $info[0]["supervisor"] .'</p>';
	$html.= '<p><strong>Supervisor’s Company:</strong> Lev West</p>';
	$html.= '<p><strong>Supervisor’s Contact Number:</strong> '. $resultado .'</p>';

echo $html;
						
?>