<?php
	// create some HTML content	
	$html= '<h2>Main Information</h2>';
	$maintenanceType = $info[0]['maintenace_type']=='corrective' ? "Corrective Maintenance" : "Preventive Maintenance";
	$html.= '<table border="0" cellspacing="0" cellpadding="5">';
	$html.= '<tr>
				<th width="60%">
					<b>Description:</b> ' . $info[0]['main_description'] . '<br>
					<b>Comments:</b> ' . $info[0]['comments'] . '<br>
					<b>Maintenance Type:</b> ' . $maintenanceType . '<br>
					<b>Priority:</b> ' . $info[0]['priority_name'] . '<br>
					<b>Status:</b> ' . $info[0]['status_name'] . '
				</th>';

	$html.= '<th width="40%">
				<b>Invested Time: </b>' . $info[0]['time'] . '<br>
				<b>Assigned By: </b>' . $info[0]['assigned_by'] . '<br>
				<b>Assigned To: </b>' . $info[0]['assigned_to'] . '<br>
				<b>Created Date: </b>' . date('F j, Y - G:i:s', strtotime($info[0]['created_at'])) . '<br>
				<b>Last update: </b>' . date('F j, Y - G:i:s', strtotime($info[0]['updated_at'])) . '
			</th></tr>';

	$html.= '</table>';

	$html.= '<br><br>
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
		</style>';
				
	if($infoParts){
		$html.= '<h2>Parts or Additional Cost </h2>';
		$html.= '<table border="0" cellspacing="0" cellpadding="4">';
		$html.= '<tr>
					<th width="50%" bgcolor="#337ab7" style="color:white;"><strong>Description</strong></th>
					<th width="30%" bgcolor="#337ab7" style="color:white;"><strong>Supplier </strong></th>
					<th width="10%" bgcolor="#337ab7" style="color:white;"><strong>Quantity </strong></th>
					<th width="10%" bgcolor="#337ab7" style="color:white;"><strong>Value </strong></th>
				</tr>';
		$total = 0;
		foreach ($infoParts as $data):
			$total = $data['value'] + $total;
			
			$html.=	'<tr>
						<th>' . $data['part_description'] . '</th>
						<th>' . $data['supplier'] . '</th>
						<th align="center">' . $data['quantity'] . '</th>
						<th align="right">$ ' . number_format($data['value'], 2) . '</th>';
			$html.= '</tr>';
		endforeach;
		$html.=	'<tr>
					<th colspan="3" align="right"><b>Subtotal :</b></th>
					<th align="right">$ ' . number_format($total, 2) . '</th>';
		$html.= '</tr>';				
		$html.= '</table><br><br>';
	}

	if($chatInfo){
		$html.= '<h2>Internal Messages </h2>';
		$html.= '<table border="0" cellspacing="0" cellpadding="4">';
		$html.= '<tr>
					<th width="20%" bgcolor="#337ab7" style="color:white;"><strong>Date & Time </strong></th>			
					<th width="20%" bgcolor="#337ab7" style="color:white;"><strong>User</strong></th>		
					<th width="60%" bgcolor="#337ab7" style="color:white;"><strong>Message </strong></th>
				</tr>';
		foreach ($chatInfo as $data):
			$html.=	'<tr>
						<th align="center">' . date('F j, Y - G:i:s', strtotime($info[0]['created_at'])) . '</th>
						<th>' . $data['user_from'] . '</th>
						<th>' . $data['message'] . '</th>';
			$html.= '</tr>';
		endforeach;					
		$html.= '</table><br><br>';
	}	

echo $html;					
?>