<?php
// ── Totals ───────────────────────────────────────────────────────────────────
$totalEquipment = 0;
$totalPersonal  = 0;
$totalOcasional = 0;
$totalMaterials = 0;
$totalReceipts  = 0;

if ($forceaccountEquipment) foreach ($forceaccountEquipment as $r) $totalEquipment += $r['value'];
if ($forceaccountPersonal)  foreach ($forceaccountPersonal  as $r) $totalPersonal  += $r['value'];
if ($forceaccountOcasional) foreach ($forceaccountOcasional as $r) $totalOcasional += $r['value'];
if ($forceaccountMaterials) foreach ($forceaccountMaterials as $r) $totalMaterials += $r['value'];
if ($forceaccountReceipt)   foreach ($forceaccountReceipt   as $r) $totalReceipts  += $r['value'];

$ticketTotal = $totalEquipment + $totalPersonal + $totalOcasional + $totalMaterials + $totalReceipts;
$fecha       = date('m/d/Y', strtotime($info[0]['date']));
$movil       = !empty($info[0]['foreman_movil_number_wo'])
               ? $info[0]['foreman_movil_number_wo']
               : ($info[0]['movil_number'] ?? '');

$eqArr  = $forceaccountEquipment ?: [];
$mpArr  = $forceaccountPersonal  ?: [];
$subArr = $forceaccountOcasional ?: [];
$matArr = $forceaccountMaterials ?: [];
$recArr = $forceaccountReceipt   ?: [];

$maxBodyRows = max(count($eqArr), count($mpArr), count($subArr), 3);
$maxMatRows  = max(count($matArr), 2);
$maxRecRows  = max(count($recArr), 2);

// ── Color tokens ─────────────────────────────────────────────────────────────
$yellow = '#FFFF99';
$lgray  = '#F0F0F0';
$border = '#333333';

// ── Cell style helpers ───────────────────────────────────────────────────────
$base  = "border:0px solid $border; padding:3px 5px; font-size:7pt;";
$mid   = "$base vertical-align:middle;";
$ctr   = "$base text-align:center; vertical-align:middle;";
$rgt   = "$base text-align:right;  vertical-align:middle;";
$bold  = "$base font-weight:bold;  vertical-align:middle;";
$boldC = "$base font-weight:bold; text-align:center; vertical-align:middle;";
$boldR = "$base font-weight:bold; text-align:right;  vertical-align:middle;";
$yh    = "$base background-color:$yellow; font-weight:bold; text-align:center; vertical-align:middle;";
$tot   = "$base background-color:$lgray;  font-weight:bold; text-align:right;  vertical-align:middle;";
$totE  = "$base background-color:$lgray;  vertical-align:middle;";
$dRow = 'line-height:16px; height:16px;';

$html = '<style>
table { border-collapse:collapse; width:100%; }
td, th { font-family:helvetica; font-size:7pt; border-spacing:0; }
</style>';

// ── Header with logo ─────────────────────────────────────────────────────────
$html .= '<table border="0" width="100%" cellpadding="4">';
$html .= '<tr>';
$html .= '<td width="60%"></td>';
$html .= '<td width="40%" align="right">';
$html .= '<img src="' . $logo . '" height="60"><br>';
$html .= '<b>Lev-West</b><br>';
$html .= 'Phone: (403) 399-0160<br>';
$html .= 'www.lev-west.com<br><br>';
$html .= '</td>';
$html .= '</tr>';
$html .= '</table>';

// ════════════════════════════════════════════════════════════════════════════
// OUTER WRAPPER – narrow "Work Ticket" rotated label on the left
// ════════════════════════════════════════════════════════════════════════════
$html .= '<table border="0" style="width:100%; border-collapse:collapse;">';
$html .= '<tr>';

$html .= '<td style="width:3%; border:1px solid ' . $border . '; text-align:center; font-weight:bold;'
       . ' font-size:9pt; vertical-align:middle; letter-spacing:1px;">'
       . 'F<br>o<br>r<br>c<br>e<br>&nbsp;<br>A<br>c<br>c<br>o<br>u<br>n<br>t</td>';

$html .= '<td style="
    width:97%;
    padding:0;
    vertical-align:top;
    border-left:1px solid '.$border.';
    border-right:1px solid '.$border.';
    border-bottom:1px solid '.$border.';
">';

// ════════════════════════════════════════════════════════════════════════════
// TITLE ROW  –  Ticket# | "Lev West Work Ticket" | Date
// ════════════════════════════════════════════════════════════════════════════
// Columns: 7% | 73% | 9% | 11%
$html .= '<table style="border-collapse:collapse; width:100%;">';
$html .= '<tr>';
$html .= '<td style="width:7%;  ' . $boldC . ' font-size:7pt;">Ticket#</td>';
$html .= '<td style="width:73%; ' . $yh    . ' font-size:12pt;" rowspan="2">Lev West Force Account</td>';
$html .= '<td style="width:9%;  ' . $boldC . '">Date</td>';
$html .= '<td style="width:11%; ' . $ctr   . '">' . esc($fecha) . '</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="' . $boldC . ' font-size:11pt;">' . esc($info[0]['id_forceaccount']) . '</td>';
$html .= '<td colspan="2" style="' . $mid . '">&nbsp;</td>';
$html .= '</tr>';
$html .= '</table>';

// ════════════════════════════════════════════════════════════════════════════
// CLIENT INFO – 2 rows, 4 columns each
// Columns: 32% | 13% | 28% | 27%
// ════════════════════════════════════════════════════════════════════════════
$html .= '<table style="border-collapse:collapse; width:100%;">';
$html .= '<tr style="' . $dRow . '">';
$html .= '<td style="width:32%; ' . $mid . '"><b>Company:</b> '       . esc($info[0]['company_name']) . '</td>';
$html .= '<td style="width:13%; ' . $mid . '"><b>Po #:</b> '          . esc($info[0]['purchase_order'] ?? '') . '</td>';
$html .= '<td style="width:28%; ' . $mid . '"><b>Venture Code:</b> '  . esc($info[0]['job_description']) . '</td>';
$html .= '<td style="width:27%; ' . $mid . '"><b>Email:</b> '         . esc($info[0]['foreman_email_wo']) . '</td>';
$html .= '</tr>';
$html .= '<tr style="' . $dRow . '">';
$html .= '<td colspan="2" style="' . $mid . '"><b>Job-site location:</b> </td>';
$html .= '<td style="' . $mid . '"><b>Foreman\'s Name:</b> '          . esc($info[0]['foreman_name_wo']) . '</td>';
$html .= '<td style="' . $mid . '"><b>Foreman\'s Contact:</b> '       . esc($movil) . '</td>';
$html .= '</tr>';
$html .= '</table>';

// ── Description of work performed ────────────────────────────────────────────
$html .= '<table style="border-collapse:collapse; width:100%;">';
$html .= '<tr>';
$html .= '<td style="' . $mid . ' height:20px;"><b>Description of Work Performed:</b> '
       . esc($info[0]['observation']) . '</td>';
$html .= '</tr>';
$html .= '</table>';

// ════════════════════════════════════════════════════════════════════════════
// WORK DATA – Equipment (4 cols) | ManPower (4 cols) | Sub's (2 cols)
// Col widths: 12 + 5 + 6 + 8 | 11 + 5 + 6 + 8 | 26 + 13 = 100%
// ════════════════════════════════════════════════════════════════════════════
$html .= '<table style="border-collapse:collapse; width:100%;">';

// Section headers
$html .= '<tr>';
$html .= '<td colspan="4" style="' . $yh . '">Type of Equipment</td>';
$html .= '<td colspan="4" style="' . $yh . '">ManPower</td>';
$html .= '<td colspan="2" style="' . $yh . '">Sub\'s</td>';
$html .= '</tr>';

// Column sub-headers
$html .= '<tr>';
$html .= '<td style="width:12%; ' . $boldC . '">Unit</td>';
$html .= '<td style="width:5%;  ' . $boldC . '">Hours</td>';
$html .= '<td style="width:6%;  ' . $boldC . '">Rate</td>';
$html .= '<td style="width:8%;  ' . $boldC . '">Sub-total</td>';
$html .= '<td style="width:11%; ' . $boldC . '">MP-Name</td>';
$html .= '<td style="width:5%;  ' . $boldC . '">Hours</td>';
$html .= '<td style="width:6%;  ' . $boldC . '">Rate</td>';
$html .= '<td style="width:8%;  ' . $boldC . '">Sub-total</td>';
$html .= '<td style="width:26%; ' . $boldC . '">Task Performed</td>';
$html .= '<td style="width:13%; ' . $boldC . '">Sub-total</td>';
$html .= '</tr>';

// Data rows
for ($i = 0; $i < $maxBodyRows; $i++) {
    $eq  = $eqArr[$i]  ?? null;
    $mp  = $mpArr[$i]  ?? null;
    $sub = $subArr[$i] ?? null;

    $eqUnit = '';
    if ($eq) {
        $eqUnit = ($eq['fk_id_type_2'] == 8)
            ? trim($eq['miscellaneous'] . ' ' . $eq['other'])
            : '#' . $eq['unit_number'] . ' ' . $eq['make'];
    }

    $html .= '<tr style="' . $dRow . '">';
    $html .= '<td style="' . $mid  . '">' . ($eq  ? esc($eqUnit)                          : '&nbsp;') . '</td>';
    $html .= '<td style="' . $ctr  . '">' . ($eq  ? esc($eq['hours'])                     : '&nbsp;') . '</td>';
    $html .= '<td style="' . $rgt  . '">' . ($eq  ? '$ ' . number_format($eq['rate'],  2) : '&nbsp;') . '</td>';
    $html .= '<td style="' . $rgt  . '">' . ($eq  ? '$ ' . number_format($eq['value'], 2) : '&nbsp;') . '</td>';
    $html .= '<td style="' . $mid  . '">' . ($mp  ? esc($mp['name'])                      : '&nbsp;') . '</td>';
    $html .= '<td style="' . $ctr  . '">' . ($mp  ? esc($mp['hours'])                     : '&nbsp;') . '</td>';
    $html .= '<td style="' . $rgt  . '">' . ($mp  ? '$ ' . number_format($mp['rate'],  2) : '&nbsp;') . '</td>';
    $html .= '<td style="' . $rgt  . '">' . ($mp  ? '$ ' . number_format($mp['value'], 2) : '&nbsp;') . '</td>';
    $html .= '<td style="' . $mid  . '">' . ($sub ? esc($sub['description'])               : '&nbsp;') . '</td>';
    $html .= '<td style="' . $rgt  . '">' . ($sub ? '$ ' . number_format($sub['value'], 2) : '&nbsp;') . '</td>';
    $html .= '</tr>';
}

// Section sub-totals row
$html .= '<tr>';
$html .= '<td colspan="3" style="' . $totE . '">&nbsp;</td>';
$html .= '<td style="' . $tot . '">$ ' . number_format($totalEquipment, 2) . '</td>';
$html .= '<td colspan="3" style="' . $totE . '">&nbsp;</td>';
$html .= '<td style="' . $tot . '">$ ' . number_format($totalPersonal, 2) . '</td>';
$html .= '<td style="' . $totE . '">&nbsp;</td>';
$html .= '<td style="' . $tot . '">$ ' . number_format($totalOcasional, 2) . '</td>';
$html .= '</tr>';
$html .= '</table>';

// ════════════════════════════════════════════════════════════════════════════
// MATERIALS – 5 cols: 20% + 35% + 12% + 13% + 20% = 100%
// ════════════════════════════════════════════════════════════════════════════
$html .= '<table style="border-collapse:collapse; width:100%;">';
$html .= '<tr>';
$html .= '<td colspan="5" style="' . $yh . '">Materials</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="width:20%; ' . $boldC . '">Type of Material</td>';
$html .= '<td style="width:35%; ' . $boldC . '">Description</td>';
$html .= '<td style="width:12%; ' . $boldC . '">Unit</td>';
$html .= '<td style="width:13%; ' . $boldC . '">Quantity</td>';
$html .= '<td style="width:20%; ' . $boldC . '">Sub-Total</td>';
$html .= '</tr>';

for ($i = 0; $i < $maxMatRows; $i++) {
    $mat = $matArr[$i] ?? null;
    $html .= '<tr style="' . $dRow . '">';
    $html .= '<td style="' . $mid . '">' . ($mat ? esc($mat['material'])                   : '&nbsp;') . '</td>';
    $html .= '<td style="' . $mid . '">' . ($mat ? esc($mat['description'])                : '&nbsp;') . '</td>';
    $html .= '<td style="' . $ctr . '">' . ($mat ? esc($mat['unit'])                       : '&nbsp;') . '</td>';
    $html .= '<td style="' . $ctr . '">' . ($mat ? esc($mat['quantity'])                   : '&nbsp;') . '</td>';
    $html .= '<td style="' . $rgt . '">' . ($mat ? '$ ' . number_format($mat['value'], 2) : '&nbsp;') . '</td>';
    $html .= '</tr>';
}
$html .= '<tr>';
$html .= '<td colspan="4" style="' . $totE . '">&nbsp;</td>';
$html .= '<td style="' . $tot . '">$ ' . number_format($totalMaterials, 2) . '</td>';
$html .= '</tr>';
$html .= '</table>';

// ════════════════════════════════════════════════════════════════════════════
// RECEIPTS – 3 cols: 28% + 52% + 20% = 100%
// ════════════════════════════════════════════════════════════════════════════
$html .= '<table style="border-collapse:collapse; width:100%;">';
$html .= '<tr>';
$html .= '<td colspan="3" style="' . $yh . '">Receipts</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="width:28%; ' . $boldC . '">Store</td>';
$html .= '<td style="width:52%; ' . $boldC . '">Description</td>';
$html .= '<td style="width:20%; ' . $boldC . '">Price</td>';
$html .= '</tr>';

for ($i = 0; $i < $maxRecRows; $i++) {
    $rec = $recArr[$i] ?? null;
    $html .= '<tr style="' . $dRow . '">';
    $html .= '<td style="' . $mid . '">' . ($rec ? esc($rec['place'])                      : '&nbsp;') . '</td>';
    $html .= '<td style="' . $mid . '">' . ($rec ? esc($rec['description'])                : '&nbsp;') . '</td>';
    $html .= '<td style="' . $rgt . '">' . ($rec ? '$ ' . number_format($rec['value'], 2) : '&nbsp;') . '</td>';
    $html .= '</tr>';
}
$html .= '<tr>';
$html .= '<td colspan="2" style="' . $totE . '">&nbsp;</td>';
$html .= '<td style="' . $tot . '">$ ' . number_format($totalReceipts, 2) . '</td>';
$html .= '</tr>';
$html .= '</table>';

// ════════════════════════════════════════════════════════════════════════════
// SIGNATURE  +  SUB-TOTALS SUMMARY
// Left 42%: signature area     Right 58%: 5-line sub-total breakdown
// ════════════════════════════════════════════════════════════════════════════
$html .= '<table style="border-collapse:collapse; width:100%;">';
$html .= '<tr>';

// Signature block
$html .= '<td style="width:42%; ' . $yh . ' vertical-align:top; height:55px;">'
       . 'On-site Client\'s Rep signature</td>';

// Sub-totals block (nested table)
$html .= '<td style="width:58%; border:0px solid ' . $border . '; padding:0; vertical-align:top;">';
$html .= '<table border="0" style="width:100%;">';
$html .= '<tr><td colspan="2" style="' . $yh . '">Sub-Totals</td></tr>';
foreach ([
    'Equipment' => $totalEquipment,
    'ManPower'  => $totalPersonal,
    "Sub's"     => $totalOcasional,
    'Materials' => $totalMaterials,
    'Receipts'  => $totalReceipts,
] as $label => $amount) {
    $html .= '<tr style="' . $dRow . '">';
    $html .= '<td style="width:65%; ' . $mid  . '">' . $label . '</td>';
    $html .= '<td style="width:35%; ' . $rgt  . '">$ ' . number_format($amount, 2) . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';
$html .= '</td>';
$html .= '</tr>';
$html .= '</table>';

// ════════════════════════════════════════════════════════════════════════════
// TICKET TOTAL  – aligns left edge with signature block above
// ════════════════════════════════════════════════════════════════════════════
$html .= '<table style="border-collapse:collapse; width:100%;">';
$html .= '<tr style="background-color:' . $yellow . ';">';
$html .= '<td style="width:42%; ' . $boldR . ' background-color:' . $yellow . ';">Ticket Total:</td>';
$html .= '<td style="width:18%; ' . $boldR . ' background-color:' . $yellow . '; font-size:9pt;">$ ' . number_format($ticketTotal, 2) . '</td>';
$html .= '<td style="width:40%; ' . $mid   . ' background-color:' . $yellow . ';">&nbsp;</td>';
$html .= '</tr>';
$html .= '</table>';

// ════════════════════════════════════════════════════════════════════════════
// REMARKS
// ════════════════════════════════════════════════════════════════════════════
$html .= '<table style="border-collapse:collapse; width:100%;">';
$html .= '<tr>';
$html .= '<td style="width:12%; ' . $yh   . '">Remarks</td>';
$html .= '<td style="width:88%; ' . $mid  . ' height:20px;">&nbsp;</td>';
$html .= '</tr>';
$html .= '</table>';

// ════════════════════════════════════════════════════════════════════════════
// LEGAL TEXT
// ════════════════════════════════════════════════════════════════════════════
$legal = [
    'By signing this Work Ticket, the Client and the Client\'s on-site representative confirm that the work performed has been reviewed and is satisfactory.',
    'This Work Ticket serves as proof of work completed and may act as an invoice on its own or as supporting documentation for a separate invoice.',
    'All information recorded on this Work Ticket has been reviewed by Lev West and the Client\'s representative.',
];
$html .= '<table style="border-collapse:collapse; width:100%;">';
foreach ($legal as $line) {
    $html .= '<tr><td style="' . $base . ' color:#0000CC; font-size:6.5pt;">' . $line . '</td></tr>';
}
$html .= '</table>';

// Close outer wrapper
$html .= '</td></tr></table>';

echo $html;
