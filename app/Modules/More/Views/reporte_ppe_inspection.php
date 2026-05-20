<?php
$style = '<style>table{font-family:arial,sans-serif;border-collapse:collapse;width:100%;}td,th{border:1px solid #dddddd;text-align:left;padding:8px;}</style>';

$html = '<h2 align="center" style="color:#337ab7;">PPE INSPECTION PROGRAM</h2>' . $style;

$html .= '<table border="0" cellspacing="0" cellpadding="5">';
$html .= '<tr>
    <th width="20%" bgcolor="#337ab7" style="color:white;"><strong>Observation: </strong></th>
    <th width="50%">' . esc($info[0]['observation']) . '</th>
    <th width="10%" bgcolor="#337ab7" style="color:white;"><strong>Date: </strong></th>
    <th align="center" width="20%">' . esc($info[0]['date_ppe_inspection']) . '</th>
</tr></table><br><br>';

if ($ppeInspectionWorkers) {
    $html .= '<table border="0" cellspacing="0" cellpadding="5">';
    $html .= '<tr bgcolor="#337ab7" style="color:white;">
        <th align="center" width="18%">Employee name</th>
        <th align="center" width="13%">Steel toe boots</th>
        <th align="center" width="13%">Hard hat</th>
        <th align="center" width="13%">Reflective vest</th>
        <th align="center" width="13%">Safety glasses</th>
        <th align="center" width="13%">Gloves</th>
        <th align="center" width="18%">Signature</th>
    </tr>';

    $goodBad = fn($v) => $v == 1 ? 'Good' : ($v == 2 ? 'Bad' : '');

    foreach ($ppeInspectionWorkers as $data) {
        $html .= '<tr>';
        $html .= '<th>' . esc($data['name']) . '</th>';
        $html .= '<th align="center">' . $goodBad($data['safety_boots']) . '</th>';
        $html .= '<th align="center">' . $goodBad($data['hart_hat']) . '</th>';
        $html .= '<th align="center">' . $goodBad($data['reflective_vest']) . '</th>';
        $html .= '<th align="center">' . $goodBad($data['safety_glasses']) . '</th>';
        $html .= '<th align="center">' . $goodBad($data['gloves']) . '</th>';
        $html .= '<th align="center">';
        if (!empty($data['signature'])) {
            $html .= '<img src="' . $data['signature'] . '" border="0" width="50" height="50"/>';
        }
        $html .= '</th>';
        $html .= '</tr>';
    }
    $html .= '</table>';
}

$html .= '<br><br><table border="0" cellspacing="0" cellpadding="5">';
$html .= '<tr>';
$html .= '<th align="center" width="30%">';
if (!empty($info[0]['inspector_signature'])) {
    $html .= '<img src="' . $info[0]['inspector_signature'] . '" border="0" width="50" height="50"/>';
}
$html .= '</th></tr>';
$html .= '<tr bgcolor="#337ab7" style="color:white;">';
$html .= '<th align="center"><strong>Inspector: </strong>' . esc($info[0]['name']) . '</th>';
$html .= '</tr></table>';

echo $html;
