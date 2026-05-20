<?php
$style = '<style>table{font-family:arial,sans-serif;border-collapse:collapse;width:100%;}td,th{border:1px solid #dddddd;text-align:left;padding:8px;}</style>';

$html = '<h3 align="right" style="color:#337ab7;">Project code: ' . esc($info[0]['job_description']) . '</h3><br>';
$html .= '<h2 align="center" style="color:#337ab7;">Environmental site inspection</h2><br>';
$html .= $style;

$html .= '<table border="0" cellspacing="0" cellpadding="5" bgcolor="#337ab7" style="color:white;">';
$html .= '<tr>
    <th align="center" rowspan="2" width="41%"><br><br>Inspection Items</th>
    <th align="center" width="16%">Implemented ?</th>
    <th align="center" rowspan="2" width="8%"><br><br><br>N/A</th>
    <th rowspan="2" width="35%"><br><br>Remarks</th>
</tr>
<tr>
    <th align="center" width="8%">Yes</th>
    <th align="center" width="8%">No</th>
</tr></table>';

$sections = [
    '1. Air pollution Control' => [
        ['id' => 'sites_watered',        'label' => 'Are the construction sites watered to minimize dust?'],
        ['id' => 'being_swept',          'label' => 'Are the main entrance and surrounding roads being swept?'],
        ['id' => 'dusty_covered',        'label' => 'Are all vehicles carrying dusty loads covered?'],
        ['id' => 'speed_control',        'label' => 'Are speed control measures applied?'],
    ],
    '2. Noise Control' => [
        ['id' => 'noise_permit',         'label' => 'Is the construction noise permit valid?'],
        ['id' => 'air_compressors',      'label' => 'Do air compressors operate with doors closed?'],
        ['id' => 'noise_mitigation',     'label' => 'Any noise mitigation measures adopted'],
        ['id' => 'idle_plan',            'label' => 'Is idle plan/equipment turned off or throttled down?'],
    ],
    '3. Site Management' => [
        ['id' => 'garbage_bin',          'label' => 'Is there enough garbage bins on site?'],
        ['id' => 'disposed_periodically','label' => 'Are garbage bins collected and disposed periodically?'],
        ['id' => 'recycling_being',      'label' => 'Is recycling being followed and placed accordingly?'],
        ['id' => 'spill_containment',    'label' => 'Is the spill containment workstation being implemented?'],
        ['id' => 'spillage_happen',      'label' => 'Did we have any spillage happen on site?'],
    ],
    '4. Storage of chemicals and Dangerous goods' => [
        ['id' => 'chemicals_stored',     'label' => 'Are chemicals, fuel, oils, coolant, and hydraulic stored and labelled property?'],
        ['id' => 'absorbing_chemical',   'label' => 'Are spill kits / sand / saw dust used for absorbing chemical spillage readily accessible?'],
        ['id' => 'spill_kits',           'label' => 'Do all equipment, & trucks have spill kits?'],
    ],
    '5. Resource Conservation' => [
        ['id' => 'excessive_use',        'label' => 'Are Diesel-powered plant and equipment shut off while not in use?'],
        ['id' => 'materials_stored',     'label' => 'Are materials stored in good condition to prevent deterioration and wastage?'],
    ],
    '6. Emergency Preparedness and Response' => [
        ['id' => 'fire_extinguishers',   'label' => 'Are fire extinguishers / fighting facilities properly maintained and not expired?'],
        ['id' => 'preventive_actions',   'label' => 'Are accidents and incidents reported, reviewed, and corrective & preventive actions recorded?'],
    ],
];

$html .= '<table border="0" cellspacing="0" cellpadding="5">';
foreach ($sections as $sectionTitle => $items) {
    $html .= '<tr bgcolor="#337ab7" style="color:white;"><th colspan="5">' . $sectionTitle . '</th></tr>';
    foreach ($items as $item) {
        $val = $info[0][$item['id']] ?? 0;
        $yes = $val == 1 ? 'Yes' : '';
        $no  = $val == 2 ? 'No'  : '';
        $na  = $val == 99 ? 'N/A' : '';
        $html .= '<tr>';
        $html .= '<th width="41%">' . $item['label'] . '</th>';
        $html .= '<th align="center" width="8%">' . $yes . '</th>';
        $html .= '<th align="center" width="8%">' . $no  . '</th>';
        $html .= '<th align="center" width="8%">' . $na  . '</th>';
        $html .= '<th align="center" width="35%">' . esc($info[0][$item['id'] . '_remarks'] ?? '') . '</th>';
        $html .= '</tr>';
    }
}
$html .= '</table>';

$html .= '<br><br><table border="0" cellspacing="0" cellpadding="5">';
$html .= '<tr>';
$html .= '<th align="center" width="30%">';
if (!empty($info[0]['inspector_signature'])) {
    $html .= '<img src="' . $info[0]['inspector_signature'] . '" border="0" width="50" height="50"/>';
}
$html .= '</th>';
$html .= '<th align="center" width="40%"></th>';
$html .= '<th align="center" width="30%">';
if (!empty($info[0]['manager_signature'])) {
    $html .= '<img src="' . $info[0]['manager_signature'] . '" border="0" width="50" height="50"/>';
}
$html .= '</th>';
$html .= '</tr>';
$html .= '<tr bgcolor="#337ab7" style="color:white;">';
$html .= '<th align="center"><strong>Inspector: </strong>' . esc($info[0]['inspector'] ?? '') . '</th>';
$html .= '<th align="center"></th>';
$html .= '<th align="center"><strong>Manager: </strong>' . esc($info[0]['manager'] ?? '') . '</th>';
$html .= '</tr></table>';

echo $html;
