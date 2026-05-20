<?php
	// create some HTML content
	$html = '<br><h2 align="center" style="color:#337ab7;">SITE ORIENTATION MUST BE COMPLETED PRIOR TO COMMENCING WORK<br></h2>';
	
	$html .= 'By signing below the worker(s) agreed to comply with all VCI’s policies as well as with all safe operations required on
this specific site, also is aware of all potential hazards, keeping in mind that all equipment has the right of way. Do not
walk behind any piece of equipment before making EYE CONTACT with the operator.<br><br>';

//TRABAJADORES
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
				</style>';

	foreach ($workers as $data):
			$html.= '<table border="1" cellspacing="0" cellpadding="5">';
			
			$html.= '<tr>
						<th width="25%" bgcolor="#337ab7" style="color:white;" ><strong>Date Oriented:</strong></th>
						<th width="25%"><strong>' . $data['date_oriented'] . '</strong></th>
						<th width="25%" bgcolor="#337ab7" style="color:white;"><strong>Signature:</strong></th>';
			$html.= '<th align="center" width="25%">';
				if($data['signature'] && file_exists($data['signature'])){
					if(filesize($data['signature']) < 200000){
						$html.= '<img src="' . $data['signature'] . '" border="0" width="60" height="60" />';
					}
				}
			$html.= '</th>';
			
			$html.= '</tr>';
			
			$html.= '<tr>
						<th bgcolor="#337ab7" style="color:white;"><strong>Name:</strong></th>
						<th><strong>' . $data['name'] . '</strong></th>
						<th bgcolor="#337ab7" style="color:white;"><strong>City:</strong></th>
						<th><strong>' . $data['city'] . '</strong></th>';
			$html.= '</tr>';
			
			$html.= '<tr>
						<th bgcolor="#337ab7" style="color:white;"><strong>Company Name:</strong></th>
						<th><strong>' . $data['works_for'] . '</strong></th>
						<th bgcolor="#337ab7" style="color:white;"><strong>Position: </strong></th>
						<th><strong>' . $data['position'] . '</strong></th>';
			$html.= '</tr>';
			
			$html.= '<tr>
						<th bgcolor="#337ab7" style="color:white;"><strong>Works phone number:</strong></th>
						<th><strong>' . $data['works_phone_number'] . '</strong></th>
						<th bgcolor="#337ab7" style="color:white;"><strong>Emergency Contact:</strong></th>
						<th><strong>' . $data['emergency_contact'] . '</strong></th>';
			$html.= '</tr>';
			

			
			$driver = "No";
			if($data['driver_license_required']==1){
				$driver = "Yes";
			}
			
			$html.= '<tr>
						<th bgcolor="#337ab7" style="color:white;"><strong>Driver’s License Required:</strong></th>
						<th><strong>' . $driver . '</strong></th>
						<th bgcolor="#337ab7" style="color:white;"><strong>Driver license number:</strong></th>
						<th><strong>' . $data['license_number'] . '</strong></th>';
			$html.= '</tr>';
			
			$html.= '</table><br><br>';
	endforeach;			

echo $html;
						
?>