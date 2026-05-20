<?php
	// create some HTML content
	$html = '<h3 align="right" style="color:#337ab7;">Project code: ' . $info[0]['job_description'] . '</h3><br>';
	
	$html .= '<br><h2 align="center" style="color:#337ab7;">Fire Watch Log Sheet<br><br></h2>
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
					<th bgcolor="#337ab7" style="color:white;"><strong>Facility/Building Address: </strong></th>
					<th colspan="3">' . strtoupper($info[0]['building_address']) . '</th>
				</tr>

				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Fire Watch Conducted by: </strong></th>
					<th>' . $info[0]['conductedby']. '</th>
					<th bgcolor="#337ab7" style="color:white;"><strong>Phone Number: </strong></th>
					<th>' . mobile_adjustment($checkinList_log[0]['movil']). '</th>
				</tr>
			</table>';

	$CompleteDateOut = $info[0]['date_commenced'];
	$date = substr($CompleteDateOut, 0, 10); 
	$time = substr($CompleteDateOut, 11, 2) . ':00';

	$html .= '<table border="0" cellspacing="0" cellpadding="5">
				<tbody>
					<tr>
						<th bgcolor="#337ab7" style="color:white;"><strong>Fire Watch Commenced: </strong></th>
						<th><strong>Date: </strong>' . $date   . '</th>
						<th><strong>Time: </strong>' . $time . '</th>
					</tr>
				</tbody>
			</table>';

	$html.= '<br><br>';

	$html .= '<table cellspacing="0" cellpadding="5">
				<tr>
					<th bgcolor="#337ab7" style="color:white;" width="10%"><strong>Patrol # </strong></th>
					<th bgcolor="#337ab7" style="color:white;" width="20%"><strong>Date & Time </strong></th>
					<th bgcolor="#337ab7" style="color:white;" width="40%"><strong>Station </strong></th>
					<th bgcolor="#337ab7" style="color:white;" width="30%"><strong>Notes/Observations</strong></th>
				</tr>';

	if($checkinList_log){
		$i=1;
		foreach ($checkinList_log as $lista):
			$html .=  "<tr>";
			$html .=  "<td>" . $i. "</td>";
			$html .=  "<td class='text-center'>" . $lista['checkin_time'] . "</td>";

			$html .=  "<td class='text-center'>" . $lista['site'];
			$html .= "<br><b>Address:</b><br>" . $lista['address_start'];
			$html .= "<br><b>Latitud</b> " . $lista['latitude_start'];
			$html .= "<br><b>Longitud</b> " . $lista['longitude_start'];
			$html .=  "</td>";

			$html .=  "<td >" . $lista['notes'] . "</td>";
			$html .=  "</tr>";
			$i++;
		endforeach;
	}
	$html.='</table>';


echo $html;
						
?>