<?php
$html= '
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

$html.= '<table border="0" cellspacing="0" cellpadding="5">';
$html.= '<tr>
		<th width="20%"><b>Project: </b></th><th width="35%">' . $info[0]['job_description'] . '</th>
		<th width="15%"><b>Bill To: </b></th><th width="30%">' . $info[0]['company'] . '</th>
		</tr>';

$html.= '<tr>
		<th><b>Description of Work: </b></th><th>' . $info[0]['observation'] . '</th>
		</tr>';
$html.= '</table>';
$html.= '<br><br>';

$item = 1;
// INICIO PERSONAL
$subTotalPersonal = 0;
if($acsPersonal)
{
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="3" width="70%"  bgcolor="#337ab7" style="color:white;"><strong>Labour </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>HOURS </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>LABOUR RATE </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="18%" bgcolor="#337ab7" style="color:white;"><strong>Name </strong></th>
				<th align="center" width="18%" bgcolor="#337ab7" style="color:white;"><strong>Occupation </strong></th>
				<th align="center" width="34%" bgcolor="#337ab7" style="color:white;"><strong>Description </strong></th>
			</tr>';
	foreach ($acsPersonal as $data):
		$subTotalPersonal += $data['value'];
		
		$html.=		'<tr>
					<th>' . $data['name']  . '</th>
					<th>' . $data['employee_type'] . '</th>
					<th>' . $data['description'] . '</th>
					<th align="center">' . $data['hours'] . '</th>
					<th align="right">$ ' . number_format($data['rate'], 2) . '</th>
					<th align="right">$ ' . number_format($data['value'], 2) . '</th>';
		$html.= '</tr>';
	endforeach;

	$html.= '<tr>
				<th colspan="5" align="right">Subtotal ' . $item  . '</th>
				<th align="right">$ ' . number_format($subTotalPersonal, 2) . '</th>
			</tr>';

	$html.= '</table><br><br>';	
	$item++;
}


// INICIO EQUIPMENT
$subTotalEquipment = 0;
if($acsEquipment)
{ 
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="4" width="70%"  bgcolor="#337ab7" style="color:white;"><strong>Equipment </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>HOURS </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>RENTAL RATE</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="15%" bgcolor="#337ab7" style="color:white;"><strong>Unit # </strong></th>
				<th align="center" width="15%" bgcolor="#337ab7" style="color:white;"><strong>Make </strong></th>
				<th align="center" width="15%" bgcolor="#337ab7" style="color:white;"><strong>Model </strong></th>
				<th align="center" width="25%" bgcolor="#337ab7" style="color:white;"><strong>Attachments </strong></th>
			</tr>';

	foreach ($acsEquipment as $data):
		$subTotalEquipment += $data['value'];

		//si es tipo miscellaneous -> 8, entonces la description es diferente
		if($data['fk_id_type_2'] == 8){
			$equipment = $data['miscellaneous'] . " - " . $data['other'];
		}else{
			$equipment = $data['unit_number'];
		}

		$html.=	'<tr>
					<th>' . $equipment . '</th>
					<th align="center">' . $data['make'] . '</th>
					<th align="center">' . $data['model'] . '</th>
					<th align="center">';
					if($data['fk_id_attachment'] != "" && $data['fk_id_attachment'] != 0){
						$html.=	'<strong>ATTACHMENT: </strong>' . $data["attachment_number"] . " - " . $data["attachment_description"] . ' ';
					}	
		$html.='</th>
					<th align="center">' . $data['hours'] . '</th>
					<th align="right">$ ' . number_format($data['rate'], 2) . '</th>
					<th align="right">$ ' . number_format($data['value'], 2) . '</th>';
		$html.= '</tr>';
	endforeach;
	$html.= '<tr>
				<th colspan="6" align="right">Subtotal ' . $item  . '</th>
				<th align="right">$ ' . number_format($subTotalEquipment, 2) . '</th>
			</tr>';

	$html.= '</table><br><br>';	
	$item++;
}

// INICIO MATERIAL
$subTotalMaterial = 0;
if($acsMaterials)
{
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="2" width="70%"  bgcolor="#337ab7" style="color:white;"><strong>Materials/Supplies </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>QUANTITY </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>UNIT PRICE</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="30%" bgcolor="#337ab7" style="color:white;"><strong>Materials and Supplies</strong></th>
				<th align="center" width="40%" bgcolor="#337ab7" style="color:white;"><strong>Description </strong></th>
			</tr>';
	foreach ($acsMaterials as $data):
		$subTotalMaterial += $data['value'];

		$description = $data['description'];
		if($data['markup'] > 0){
			$description = $description . ' - Plus M.U.';
		}

		$html.=	'<tr>
					<th>' . $data['material'] . '</th>
					<th>' . $description . '</th>
					<th align="center">' . $data['quantity'] . '</th>
					<th align="right">$ ' . number_format($data['rate'], 2) . '</th>
					<th align="right">$ ' . number_format($data['value'], 2) . '</th>';
		$html.= '</tr>';
	endforeach;
	$html.= '<tr>
				<th colspan="4" align="right">Subtotal ' . $item  . '</th>
				<th align="right">$ ' . number_format($subTotalMaterial, 2) . '</th>
			</tr>';

	$html.= '</table><br><br>';	
	$item++;
}

// INICIO SUBCONTRATISTAS OCASIONALES
$subTotalOcasional = 0;
if($acsOcasional)
{
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="2" width="60%"  bgcolor="#337ab7" style="color:white;"><strong>Occasional Subcontractor </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>QUANTITY </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>HOURS </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>UNIT PRICE</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="25%" bgcolor="#337ab7" style="color:white;"><strong>Subcontractor/Rentals</strong></th>
				<th align="center" width="35%" bgcolor="#337ab7" style="color:white;"><strong>Description </strong></th>
			</tr>';
	foreach ($acsOcasional as $data):
		$subTotalOcasional += $data['value'];

		$description = $data['description'];
		if($data['markup'] > 0){
			$description = $description . ' - Plus M.U.';
		}

		$html.=		'<tr>
					<th>' . $data['company_name'] . '</th>
					<th>' . $description . '</th>
					<th align="center">' . $data['quantity'] . '</th>
					<th align="center">' . $data['hours'] . '</th>
					<th align="right">$ ' . number_format($data['rate'], 2) . '</th>
					<th align="right">$ ' . number_format($data['value'], 2) . '</th>';
		$html.= '</tr>';
	endforeach;
	$html.= '<tr>
				<th colspan="5" align="right">Subtotal ' . $item  . '</th>
				<th align="right">$ ' . number_format($subTotalOcasional, 2) . '</th>
			</tr>';

	$html.= '</table><br><br>';	
	$item++;
}

// INICIO RECEIPT
$subTotalReceipt = 0;
if($acsReceipt)
{
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="2" width="70%"  bgcolor="#337ab7" style="color:white;"><strong>Receipt</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>Price with GST </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>MARKUP</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#337ab7" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="30%" bgcolor="#337ab7" style="color:white;"><strong>Place</strong></th>
				<th align="center" width="40%" bgcolor="#337ab7" style="color:white;"><strong>Description </strong></th>
			</tr>';
	foreach ($acsReceipt as $data):
		$subTotalReceipt += $data['value'];

		$description = $data['description'];
		if($data['markup'] > 0){
			$description = $description . ' - Plus M.U.';
		}

		$html.=		'<tr>
					<th>' . $data['place'] . '</th>
					<th>' . $description . '</th>
					<th align="right">$ ' . number_format($data['price'], 2) . '</th>
					<th align="right">' . $data['markup'] . '</th>
					<th align="right">$ ' . number_format($data['value'], 2) . '</th>';
		$html.= '</tr>';
	endforeach;
	$html.= '<tr>
				<th colspan="4" align="right">Subtotal ' . $item  . '</th>
				<th align="right">$ ' . number_format($subTotalReceipt, 2) . '</th>
			</tr>';

	$html.= '</table><br><br>';	
	$item++;
}


$html.= '<table border="0" cellspacing="0" cellpadding="4">';


$items=0;
$total = $subTotalPersonal + $subTotalEquipment + $subTotalMaterial + $subTotalOcasional + $subTotalReceipt;
$html.= '<tr>
			<th align="right" colspan="2" width="30%"  bgcolor="#337ab7" style="color:white;"><strong>SUMMARY</strong></th>
			<th align="center" width="15%" bgcolor="#337ab7" style="color:white;"><strong>AMOUNT </strong></th>
		</tr>';
if($acsPersonal)
{
	$items++;
	$html.= '<tr>
				<th width="15%">Labour</th>
				<th width="15%" align="right">Subtotal ' . $items  . '</th>
				<th width="15%" align="right">$ ' . number_format($subTotalPersonal, 2) . '</th>
			</tr>';
}
if($acsEquipment)
{
	$items++;
	$html.= '<tr>
				<th >Equipment</th>
				<th align="right">Subtotal ' . $items  . '</th>
				<th align="right">$ ' . number_format($subTotalEquipment, 2) . '</th>
			</tr>';
}
if($acsMaterials)
{
	$items++;
	$html.= '<tr>
				<th >Materials/Supplies</th>
				<th align="right">Subtotal ' . $items  . '</th>
				<th align="right">$ ' . number_format($subTotalMaterial, 2) . '</th>
			</tr>';
}
if($acsOcasional)
{
	$items++;
	$html.= '<tr>
				<th >Subcontractor</th>
				<th align="right">Subtotal ' . $items  . '</th>
				<th align="right">$ ' . number_format($subTotalOcasional, 2) . '</th>
			</tr>';
}
if($acsReceipt)
{
	$items++;
	$html.= '<tr>
				<th >Receipt</th>
				<th align="right">Subtotal ' . $items  . '</th>
				<th align="right">$ ' . number_format($subTotalReceipt, 2) . '</th>
			</tr>';
}
$html.= '<tr>
			<th colspand="2" width="30%" align="right" bgcolor="#337ab7" style="color:white;"><strong>TOTAL AMOUNT</strong></th>
			<th align="right" bgcolor="#337ab7" style="color:white;"><strong>$ ' . number_format($total, 2) . ' </strong></th>
		</tr>
		</table>';	

	
echo $html;

?>