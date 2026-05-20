<?php
	// create some HTML content
	$html = '<p><h1 align="center" style="color:#337ab7;">EVACUATION ROUTES</h1></p>';

	// create some HTML content
	$html .= 'Evacuation route maps have been posted in each work area. The
	following information is marked on evacuation maps

	<ol>
		<li>Emergency exits</li>
		<li>Primary and secondary evacuation routes</li>
		<li>Locations of fire extinguishers</li>
		<li>Fire alarm pull stations’ location</li>
		<li>Assembly points</li>
		<li>Medical center</li>
		<li>First Aid locations</li>
	</ol>

	<p>Site personnel should know at least two evacuation routes.</p>';
	
	if($info[0]["evacuation_map"]){ 
		//$html .= '<img src="http://v-contracting.ca/app/' . $info[0]["evacuation_map"] . '" />';
		$html .= '<a href="http://v-contracting.ca/app/' . $info[0]["evacuation_map"] . '" target="_blank" /><strong>Link to the ERP map</strong></a>';
	}
	
			// create some HTML content
	$html .= '<p><h1 align="center" style="color:#337ab7;">EMERGENCY PHONE NUMBERS</h1></p>
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
					<th bgcolor="#337ab7" style="color:white;" width="50%"><strong>Fire department: </strong></th>
					<th width="50%">' . $info[0]['fire_department']. '</th>
				</tr>
				
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Paramedics: </strong></th>
					<th>' . $info[0]['paramedics']. '</th>
				</tr>
				
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Ambulance: </strong></th>
					<th>' . $info[0]['ambulance']. '</th>
				</tr>
				
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Police: </strong></th>
					<th>' . $info[0]['police']. '</th>
				</tr>
				
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Federal protective service: </strong></th>
					<th>' . $info[0]['federal_protective']. '</th>
				</tr>
				
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Security (If applicable): </strong></th>
					<th>' . $info[0]['security']. '</th>
				</tr>
				
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Project manager (If applicable): </strong></th>
					<th>' . $info[0]['manager']. '</th>
				</tr>
			
			</table>';

			// create some HTML content
	$html .= '<p></p><p><h1 align="center" style="color:#337ab7;">UTILITY COMPANY EMERGENCY CONTACTS</h1></p>
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
					<th bgcolor="#337ab7" style="color:white;" width="50%"><strong>Electric: </strong></th>
					<th width="50%">' . $info[0]['electric']. '</th>
				</tr>
				
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Water: </strong></th>
					<th>' . $info[0]['water']. '</th>
				</tr>
				
				<tr>
					<th bgcolor="#337ab7" style="color:white;"><strong>Gas (if applicable): </strong></th>
					<th>' . $info[0]['gas']. '</th>
				</tr>
							
			</table>';
		

echo $html;
						
?>