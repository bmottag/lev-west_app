<?php

$fecha = date('F j, Y', strtotime($info[0]['date_issue']));

$html = '

<style>
body{
    font-family: helvetica;
    font-size:10px;
}

.header-title{
    font-size:22px;
    font-weight:bold;
    color:#2c3e50;
}

.invoice-info{
    font-size:11px;
}

.section-title{
    background-color:#2c3e50;
    color:white;
    font-weight:bold;
    padding:6px;
}

.table-items th{
    background-color:#f4f6f7;
    font-weight:bold;
    border-bottom:1px solid #ddd;
}

.table-items td{
    border-bottom:1px solid #eee;
}

.total-table td{
    font-size:11px;
}

.total-final{
    background-color:#2c3e50;
    color:white;
    font-weight:bold;
}
</style>';

$html .= '

<!-- HEADER -->
<table width="100%" cellpadding="4">

    <tr>
        <td width="60%"></td>

        <td width="40%" align="right">
            <img src="'.$logo.'" height="60"><br>
            <b>Lev-West</b><br>
            Phone: (403) 399-0160<br>
            www.lev-west.com<br><br>
        </td>
    </tr>

</table>

<table width="100%">
    <tr>
        <td width="60%">
            <b>Client information</b><br>
            <b>Company name:</b> '.$info[0]['company_name'].'<br>
            <b>Job-site Location:</b> '.$info[0]['job_description'].'
        </td>

        <td width="40%" align="right">
            <b>Invoice #:</b> ' . $info[0]['number'] . '<br>
            <b>Date:</b> ' . $fecha . '
        </td>
    </tr>
</table>

<br><br><br><br>

<!-- ITEMS TABLE -->
<table width="100%" cellpadding="6" class="table-items">
    <thead>
        <tr>
            <th width="6%" align="center">#</th>
            <th width="49%">Description</th>
            <th width="10%" align="center">Unit</th>
            <th width="10%" align="center">Qty</th>
            <th width="12%" align="right">Unit Price</th>
            <th width="13%" align="right">Total</th>
        </tr>
    </thead>

    <tbody>
';


$records = 0;
$total = 0;

if($items){
    foreach ($items as $data){

        $records++;
        $total += $data['value'];

        $html .= '

        <tr>
            <td width="6%" align="center">'.$records.'</td>
            <td width="49%">'.$data['description'].'</td>
            <td width="10%" align="center">'.$data['unit'].'</td>
            <td width="10%" align="center">'.$data['quantity'].'</td>
            <td width="12%" align="right">$ '.number_format($data['rate'],2).'</td>
            <td width="13%" align="right">$ '.number_format($data['value'],2).'</td>
        </tr>
        ';
    }
}

$gst = $total * 0.05;

$html .= '
    </tbody>
</table>

<br><br>

<table width="100%">
    <tr>
        <td width="60%"></td>
        <td width="40%">
            <table width="100%" cellpadding="6" class="total-table">
                <tr>
                    <td width="60%" align="right"><b>Subtotal</b></td>
                    <td width="40%" align="right">$ '.number_format($total,2).'</td>
                </tr>

                <tr>
                    <td width="60%" align="right"><b>GST</b></td>
                    <td width="40%" align="right">$ '.number_format($gst,2).'</td>
                </tr>

                <tr class="total-final">
                    <td align="right">TOTAL</td>
                    <td align="right">$ '.number_format($total + $gst,2).'</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
';
		
echo $html;
						
?>