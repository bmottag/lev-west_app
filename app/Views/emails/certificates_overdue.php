<p>Dear <?= esc($name) ?>:</p>

<p>The following employees have a certificate that is about to expire:</p>

<table style="border-collapse:collapse;width:100%">
    <thead>
        <tr style="background-color:#f2f2f2">
            <th style="border:1px solid #ddd;padding:8px;text-align:left">Employee</th>
            <th style="border:1px solid #ddd;padding:8px;text-align:left">Certificate</th>
            <th style="border:1px solid #ddd;padding:8px;text-align:left">Date Through</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($certificates as $cert) : ?>
        <tr>
            <td style="border:1px solid #ddd;padding:8px"><?= esc($cert['first_name'] . ' ' . $cert['last_name']) ?></td>
            <td style="border:1px solid #ddd;padding:8px"><?= esc($cert['certificate']) ?></td>
            <td style="border:1px solid #ddd;padding:8px"><?= esc($cert['date_through']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>Regards,<br><?= $companyName ?></p>
