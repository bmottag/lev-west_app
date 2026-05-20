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

	if (empty($info[0]['signature_wo'])) {
		$html.= '<div style="
					color: red;
					font-weight: bold;
					background-color: #ffe6e6;
					border: 2px dashed red;
					padding: 10px;
					border-radius: 8px;
					text-align: center;
				">
					This document is not valid until it has been signed by the client representative.
				</div><br>';
	}


$html.= '<table border="0" cellspacing="0" cellpadding="5">';
$html.= '<tr>
		<th width="20%"><b>Project: </b></th><th width="35%">' . $info[0]['job_description'] . '</th>
		<th width="15%"><b>Bill To: </b></th><th width="30%">' . $info[0]['company_name'] . '</th>
		</tr>';

$html.= '<tr>
		<th><b>Description of Work: </b></th><th>' . $info[0]['observation'] . '</th>
		</tr>';
$html.= '</table>';
$html.= '<br><br>';

$item = 1;
// INICIO PERSONAL
$subTotalPersonal = 0;
if($forceaccountPersonal)
{
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="3" width="70%"  bgcolor="#ff6b33" style="color:white;"><strong>Labour </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>HOURS </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>LABOUR RATE </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="18%" bgcolor="#ff6b33" style="color:white;"><strong>Name </strong></th>
				<th align="center" width="18%" bgcolor="#ff6b33" style="color:white;"><strong>Occupation </strong></th>
				<th align="center" width="34%" bgcolor="#ff6b33" style="color:white;"><strong>Description </strong></th>
			</tr>';
	foreach ($forceaccountPersonal as $data):
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
if($forceaccountEquipment)
{ 
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="4" width="70%"  bgcolor="#ff6b33" style="color:white;"><strong>Equipment </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>HOURS </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>RENTAL RATE</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="15%" bgcolor="#ff6b33" style="color:white;"><strong>Unit # </strong></th>
				<th align="center" width="15%" bgcolor="#ff6b33" style="color:white;"><strong>Make </strong></th>
				<th align="center" width="15%" bgcolor="#ff6b33" style="color:white;"><strong>Model </strong></th>
				<th align="center" width="25%" bgcolor="#ff6b33" style="color:white;"><strong>Attachments </strong></th>
			</tr>';

	foreach ($forceaccountEquipment as $data):
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
if($forceaccountMaterials)
{
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="2" width="60%"  bgcolor="#ff6b33" style="color:white;"><strong>Materials/Supplies </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>QUANTITY </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>UNIT PRICE</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>MARKUP </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="25%" bgcolor="#ff6b33" style="color:white;"><strong>Materials and Supplies</strong></th>
				<th align="center" width="35%" bgcolor="#ff6b33" style="color:white;"><strong>Description </strong></th>
			</tr>';
	foreach ($forceaccountMaterials as $data):
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
					<th align="right">' . $data['markup'] . '</th>
					<th align="right">$ ' . number_format($data['value'], 2) . '</th>';
		$html.= '</tr>';
	endforeach;
	$html.= '<tr>
				<th colspan="5" align="right">Subtotal ' . $item  . '</th>
				<th align="right">$ ' . number_format($subTotalMaterial, 2) . '</th>
			</tr>';

	$html.= '</table><br><br>';	
	$item++;
}

// INICIO SUBCONTRATISTAS OCASIONALES
$subTotalOcasional = 0;
if($forceaccountOcasional)
{
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="2" width="50%"  bgcolor="#ff6b33" style="color:white;"><strong>Occasional Subcontractor </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>QUANTITY </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>HOURS </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>UNIT PRICE</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>MARKUP </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="20%" bgcolor="#ff6b33" style="color:white;"><strong>Subcontractor/Rentals</strong></th>
				<th align="center" width="30%" bgcolor="#ff6b33" style="color:white;"><strong>Description </strong></th>
			</tr>';
	foreach ($forceaccountOcasional as $data):
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
					<th align="right">' . $data['markup'] . '</th>
					<th align="right">$ ' . number_format($data['value'], 2) . '</th>';
		$html.= '</tr>';
	endforeach;
	$html.= '<tr>
				<th colspan="6" align="right">Subtotal ' . $item  . '</th>
				<th align="right">$ ' . number_format($subTotalOcasional, 2) . '</th>
			</tr>';

	$html.= '</table><br><br>';	
	$item++;
}

// INICIO RECEIPT
$subTotalReceipt = 0;
if($forceaccountReceipt)
{
	$html.= '<table border="0" cellspacing="0" cellpadding="4">';

	$html.= '<tr>
				<th align="center" colspan="2" width="70%"  bgcolor="#ff6b33" style="color:white;"><strong>Receipt</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>Price with GST </strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>MARKUP</strong></th>
				<th align="center" rowspan="2" width="10%" bgcolor="#ff6b33" style="color:white;"><strong>AMOUNT </strong></th>
			</tr>
			<tr>
				<th align="center" width="30%" bgcolor="#ff6b33" style="color:white;"><strong>Place</strong></th>
				<th align="center" width="40%" bgcolor="#ff6b33" style="color:white;"><strong>Description </strong></th>
			</tr>';
	foreach ($forceaccountReceipt as $data):
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


// Calculamos el total
$subtotal = $subTotalPersonal + $subTotalEquipment + $subTotalMaterial + $subTotalOcasional + $subTotalReceipt;
// Normalizamos el profit
$profit = $info[0]['profit'] ?? 0;   // Si no existe, por defecto 0
$profit = is_numeric($profit) ? floatval($profit) : 0; // Aseguramos número
$profit = max(0, min(100, $profit)); // Forzamos rango entre 0 y 100
$profitValue = $subtotal * $profit / 100;
// Calculamos el total con profit
$total = $subtotal + $profitValue;

// Armamos la tabla de firma (izquierda)
$signature = '';
if (!empty($info[0]['signature_wo'])) {
	$signature = '<img src="' . $info[0]['signature_wo'] . '" border="0" width="70" height="70" />';
} else {
	$signature = '<br>
	<div style="
		color: red;
		font-weight: bold;
		background-color: #ffe6e6;
		border: 2px dashed red;
		padding: 10px;
		border-radius: 8px;
		text-align: center;
	">
		SIGNATURE NOT PROVIDED
	</div>';

}

$html .= '<table border="0" cellspacing="0" cellpadding="4" width="100%">
	<tr>
		<!-- Firma a la izquierda -->
		<td width="40%" valign="top">
			<table border="1" cellspacing="0" cellpadding="5" width="100%">
				<tr>
					<th align="center" style="background-color:#f0f0f0;"><strong>Client Representative</strong></th>
				</tr>
				<tr>
					<td align="center">' . $signature . '</td>
				</tr>
				<tr>
					<th align="center" style="background-color:#ff6b33; color:white;">' . $info[0]['foreman_name_wo'] . '</th>
				</tr>
			</table>
		</td>

		<!-- Resumen a la derecha -->
		<td width="60%" valign="top">
			<table border="0" cellspacing="0" cellpadding="4" width="100%">
				<tr>
					<th align="right" colspan="2" width="60%" bgcolor="#ff6b33" style="color:white;"><strong>SUMMARY</strong></th>
					<th align="center" width="40%" bgcolor="#ff6b33" style="color:white;"><strong>AMOUNT</strong></th>
				</tr>';

$items = 0;
if ($forceaccountPersonal) {
	$items++;
	$html .= '<tr>
		<th width="30%">Labour</th>
		<th width="30%" align="right">Subtotal ' . $items . '</th>
		<th width="40%" align="right">$ ' . number_format($subTotalPersonal, 2) . '</th>
	</tr>';
}
if ($forceaccountEquipment) {
	$items++;
	$html .= '<tr>
		<th>Equipment</th>
		<th align="right">Subtotal ' . $items . '</th>
		<th align="right">$ ' . number_format($subTotalEquipment, 2) . '</th>
	</tr>';
}
if ($forceaccountMaterials) {
	$items++;
	$html .= '<tr>
		<th>Materials/Supplies</th>
		<th align="right">Subtotal ' . $items . '</th>
		<th align="right">$ ' . number_format($subTotalMaterial, 2) . '</th>
	</tr>';
}
if ($forceaccountOcasional) {
	$items++;
	$html .= '<tr>
		<th>Subcontractor</th>
		<th align="right">Subtotal ' . $items . '</th>
		<th align="right">$ ' . number_format($subTotalOcasional, 2) . '</th>
	</tr>';
}
if ($forceaccountReceipt) {
	$items++;
	$html .= '<tr>
		<th>Receipt</th>
		<th align="right">Subtotal ' . $items . '</th>
		<th align="right">$ ' . number_format($subTotalReceipt, 2) . '</th>
	</tr>';
}

$html .= '<tr>
	<th colspan="2" align="right" bgcolor="#ff6b33" style="color:white;"><strong>SUBTOTAL AMOUNT</strong></th>
	<th align="right" bgcolor="#ff6b33" style="color:white;"><strong>$ ' . number_format($subtotal, 2) . '</strong></th>
</tr>

		<tr>
	<th colspan="2" align="right" bgcolor="#ff6b33" style="color:white;"><strong>PROFIT ( ' . $profit .  '% )</strong></th>
	<th align="right" bgcolor="#ff6b33" style="color:white;"><strong>$ ' . number_format($profitValue, 2) . '</strong></th>
</tr>

		<tr>
	<th colspan="2" align="right" bgcolor="#ff6b33" style="color:white;"><strong>TOTAL AMOUNT</strong></th>
	<th align="right" bgcolor="#ff6b33" style="color:white;"><strong>$ ' . number_format($total, 2) . '</strong></th>
</tr>

		</table>
	</td>
</tr>
</table>';
	
echo $html;

?>