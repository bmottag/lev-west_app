<?php
$style = '<style>table{font-family:arial,sans-serif;border-collapse:collapse;width:100%;}td,th{border:1px solid #dddddd;text-align:left;padding:8px;}</style>';

$html = '<h2 align="center" style="color:#337ab7;">CONFINED SPACE ENTRY PERMIT<br><br></h2>' . $style;

$html .= '<table border="0" cellspacing="0" cellpadding="5">
    <tr>
        <th bgcolor="#337ab7" style="color:white;"><strong>Job Code/Name: </strong></th>
        <th>' . strtoupper(esc($info[0]['job_description'])) . '</th>
        <th bgcolor="#337ab7" style="color:white;"><strong>Permit No.: </strong></th>
        <th>' . esc($info[0]['id_job_confined']) . '</th>
    </tr>
    <tr>
        <th bgcolor="#337ab7" style="color:white;"><strong>Done by: </strong></th>
        <th>' . esc($info[0]['name']) . '</th>
        <th bgcolor="#337ab7" style="color:white;"><strong>Date: </strong></th>
        <th>' . esc($info[0]['date_confined']) . '</th>
    </tr>
    <tr>
        <th bgcolor="#337ab7" style="color:white;"><strong>Location: </strong></th>
        <th colspan="3">' . esc($info[0]['location']) . '</th>
    </tr>
    <tr>
        <th bgcolor="#337ab7" style="color:white;"><strong>Purpose of Entry: </strong></th>
        <th colspan="3">' . esc($info[0]['purpose']) . '</th>
    </tr>
    <tr>
        <th bgcolor="#337ab7" style="color:white;"><strong>Scheduled start: </strong></th>
        <th>' . esc($info[0]['scheduled_start']) . '</th>
        <th bgcolor="#337ab7" style="color:white;"><strong>Scheduled finish: </strong></th>
        <th>' . esc($info[0]['scheduled_finish']) . '</th>
    </tr>
</table><br><br>';

$html .= '<h2 align="center" style="color:#337ab7;">Entrant(s)<br></h2>';
if (!$confinedWorkers) {
    $html .= 'No data was found for workers';
} else {
    $html .= '<table border="0" cellspacing="0" cellpadding="5">
        <tr>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Worker</strong></th>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Time In</strong></th>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Time Out</strong></th>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Task</strong></th>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Signature</strong></th>
        </tr>';
    foreach ($confinedWorkers as $data) {
        $html .= '<tr>';
        $html .= '<th>' . esc($data['name']) . '</th>';
        $html .= '<th align="center">' . (($data['signature'] && $data['date_time_in']) ? esc($data['date_time_in']) : '') . '</th>';
        $html .= '<th align="center">' . (($data['signature_out'] && $data['date_time_out']) ? esc($data['date_time_out']) : '') . '</th>';
        $html .= '<th>' . esc($data['task']) . '</th>';
        $html .= '<th align="center">';
        if (!empty($data['signature'])) {
            $html .= '<img src="' . $data['signature'] . '" border="0" width="70" height="70"/>';
        }
        $html .= '</th></tr>';
    }
    $html .= '</table>';
}

$html .= '<br><br><h2 align="center" style="color:#337ab7;">WORKERS ON SITE<br></h2>';
if (!$WorkersOnSite) {
    $html .= 'No data was found for workers';
} else {
    $html .= '<table border="0" cellspacing="0" cellpadding="5">
        <tr><th align="center" bgcolor="#337ab7" style="color:white;"><strong>Worker</strong></th></tr>';
    foreach ($WorkersOnSite as $data) {
        $html .= '<tr><th>' . esc($data['name']) . '</th></tr>';
    }
    $html .= '</table>';
}

$html .= '<br><br><table border="0" cellspacing="0" cellpadding="5"><tr><th>';
$html .= ' <b>Oxygen (Acceptable Level)</b> 19.5 % - 22 %<br>';
$html .= ' <b>Carbon Monoxide (Ocupational Exposure Limit)</b> 25 ppm<br>';
$html .= ' <b>Hydrogen Sulphide (Ocupational Exposure Limit)</b> 10 ppm';
$html .= '</th></tr></table><br><br>';

$html .= '<h2 align="center" style="color:#337ab7;">Pre-Entry Authorization<br></h2>';

$cb = fn($v) => $v == 1 ? '[X]' : '[ ]';
$checkboxItems = [
    'oxygen_deficient'    => 'Oxygen-Deficient Atmosphere',
    'oxygen_enriched'     => 'Oxygen-Enriched Atmosphere',
    'welding'             => 'Welding/cutting',
    'engulfment'          => 'Engulfment',
    'toxic_atmosphere'    => 'Toxic Atmosphere',
    'flammable_atmosphere'=> 'Flammable Atmosphere',
    'energized_equipment' => 'Energized Electric Equipment',
    'entrapment'          => 'Entrapment',
    'hazardous_chemical'  => 'Hazardous Chemical',
];
$html .= '<table border="0" cellspacing="0" cellpadding="5"><tr>';
$i = 0;
foreach ($checkboxItems as $field => $label) {
    if ($i % 3 == 0 && $i > 0) $html .= '</tr><tr>';
    if ($i % 3 == 0) $html .= '<th>';
    $html .= $cb($info[0][$field]) . ' ' . $label . '<br>';
    if ($i % 3 == 2) $html .= '</th>';
    $i++;
}
if ($i % 3 != 0) $html .= '</th>';
$html .= '</tr></table><br><br>';

$html .= '<h2 align="center" style="color:#337ab7;">Safety precautions<br></h2>';
$safetyItems = [
    'breathing_apparatus' => 'Self-Contained Breathing Apparatus',
    'line_respirator'     => 'Air-Line Respirator',
    'resistant_clothing'  => 'Flame Resistant Clothing',
    'ventilation'         => 'Ventilation',
    'protective_gloves'   => 'Protective Gloves',
    'linelines'           => 'Linelines',
    'respirators'         => 'Respirators',
    'lockout'             => 'Lockout/Tagout',
    'fire_extinguishers'  => 'Fire Extinguishers',
    'barricade'           => 'Barricade Job Area',
    'signs_posted'        => 'Signs Posted',
    'clearance_secured'   => 'Clearance Secured',
    'lighting'            => 'Lighting',
    'interrupter'         => 'Ground Fault Interrupter',
];
$html .= '<table border="0" cellspacing="0" cellpadding="5"><tr><th>';
foreach ($safetyItems as $field => $label) {
    $html .= $cb($info[0][$field]) . ' ' . $label . '<br>';
}
$html .= '</th></tr></table><br><br>';

$html .= '<h2 align="center" style="color:#337ab7;">Environmental conditions - Test to be taken<br></h2>';
$html .= '<table border="0" cellspacing="0" cellpadding="5">
    <tr>
        <th bgcolor="#337ab7" style="color:white;"><strong>Oxygen: </strong></th>
        <th>' . esc($info[0]['oxygen']) . ' %</th>
        <th bgcolor="#337ab7" style="color:white;"><strong>Date/Time: </strong></th>
        <th>' . esc($info[0]['oxygen_time']) . '</th>
    </tr>
    <tr>
        <th bgcolor="#337ab7" style="color:white;"><strong>Lower Explosive Limit: </strong></th>
        <th>' . esc($info[0]['explosive_limit']) . ' %</th>
        <th bgcolor="#337ab7" style="color:white;"><strong>Date/Time: </strong></th>
        <th>' . esc($info[0]['explosive_limit_time']) . '</th>
    </tr>
    <tr>
        <th bgcolor="#337ab7" style="color:white;"><strong>Toxic Atmosphere: </strong></th>
        <th colspan="3">' . esc($info[0]['toxic_atmosphere_cond']) . '</th>
    </tr>
    <tr>
        <th bgcolor="#337ab7" style="color:white;"><strong>Instruments Used: </strong></th>
        <th colspan="3">' . esc($info[0]['instruments_used']) . '</th>
    </tr>
</table><br><br>';

$html .= '<table border="0" cellspacing="0" cellpadding="5">
    <tr><th bgcolor="#337ab7" style="color:white;"><strong>Remarks on the overall condition of the confined space: </strong></th></tr>
    <tr><th>' . esc($info[0]['remarks']) . '</th></tr>
</table><br><br>';

if ($retesting) {
    $html .= '<h2 align="center" style="color:#337ab7;">Environmental conditions - Re-Testing<br></h2>';
    $html .= '<table border="0" cellspacing="0" cellpadding="5">
        <tr>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Oxygen</strong></th>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Date/Time</strong></th>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Lower Explosive Limit</strong></th>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Date/Time</strong></th>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Toxic Atmosphere</strong></th>
            <th align="center" bgcolor="#337ab7" style="color:white;"><strong>Instruments Used</strong></th>
        </tr>';
    foreach ($retesting as $data) {
        $html .= '<tr>
            <th align="center">' . esc($data['re_oxygen']) . ' %</th>
            <th align="center">' . esc($data['re_oxygen_time']) . '</th>
            <th align="center">' . esc($data['re_explosive_limit']) . ' %</th>
            <th align="center">' . esc($data['re_explosive_limit_time']) . '</th>
            <th align="center">' . esc($data['re_toxic_atmosphere']) . '</th>
            <th>' . esc($data['re_instruments_used']) . '</th>
        </tr>';
    }
    $html .= '</table><br><br>';
}

$html .= '<h2 align="center" style="color:#337ab7;">Post-entry Inspection<br></h2>';
$postFields = [
    'personnel_out'    => 'Are all personnel out of the confined space and accounted for?',
    'isolation'        => 'Have isolation devices been removed and pipes been restored to their original positions?',
    'lockouts_removed' => 'Have all lockouts been removed?',
    'tags_removed'     => 'Have all safe entry tags and sings been removed?',
    'equipment_removed'=> 'Have all equipment and waste been removed from the work area?',
    'ppe_cleaned'      => 'Has all specialized PPE been cleaned, post-inspected and put away?',
    'rescue_equipment' => 'Has all rescue equipment been post-inspected, cleaned and stored (If Applicable)?',
    'permits_signed'   => 'Have all permits been signed out and filed properly?',
    'areas_notified'   => 'Have other applicable areas of the facility been notified that the work in the confined space is complete?',
];
$yesNoNA = fn($v) => $v == 1 ? 'Yes' : ($v == 2 ? 'No' : 'N/A');

$html .= '<table border="0" cellspacing="0" cellpadding="5">';
foreach ($postFields as $field => $label) {
    $html .= '<tr><th>' . $label . '</th><th align="center">' . $yesNoNA($info[0][$field] ?? 0) . '</th></tr>';
}
$html .= '</table><br><br>';

$html .= '<table border="0" cellspacing="0" cellpadding="5"><tr>';
$html .= '<th align="center" width="30%">';
if (!empty($info[0]['authorization_signature'])) {
    $html .= '<img src="' . $info[0]['authorization_signature'] . '" border="0" width="70" height="70"/>';
}
$html .= '</th>';
$html .= '<th align="center" width="40%"></th>';
$html .= '<th align="center" width="30%">';
if (!empty($info[0]['cancellation_signature'])) {
    $html .= '<img src="' . $info[0]['cancellation_signature'] . '" border="0" width="70" height="70"/>';
}
$html .= '</th></tr>';
$html .= '<tr bgcolor="#337ab7" style="color:white;">
    <th align="center"><strong>' . esc($info[0]['user_authorization'] ?? '') . '</strong></th>
    <th align="center" width="40%"></th>
    <th align="center"><strong>' . esc($info[0]['user_cancellation'] ?? '') . '</strong></th>
</tr>
<tr bgcolor="#337ab7" style="color:white;">
    <th align="center"><strong>Entry Authorization Signature</strong></th>
    <th align="center" width="40%"></th>
    <th align="center"><strong>Entry Cancellation Signature</strong></th>
</tr></table>';

echo $html;
