<?php

if (!function_exists('pr')) {

    function pr($objVar)
    {
        echo "<div style='text-align:left'>";
        
        if (is_array($objVar) || is_object($objVar)) {
            echo "<pre>";
            print_r($objVar);
            echo "</pre>";
        } else {
            echo nl2br($objVar);
        }

        echo "</div><hr>";
    }

}

/**
 * Formatear el numero del celular
 * @author bmottag
 * @param	String	$mobile	Numero de celular
 * @return	formatea valor del numero de celular
 */
if (!function_exists("mobile_adjustment")) {
    function mobile_adjustment($mobile = '') {
        $count = strlen($mobile); 
        $num_tlf1 = substr($mobile, 0, 3); 
        $num_tlf2 = substr($mobile, 3, 3); 
        $num_tlf3 = substr($mobile, 6, 2); 
        $num_tlf4 = substr($mobile, -2); 
        return $count == 10?"$num_tlf1 $num_tlf2 $num_tlf3 $num_tlf4":chunk_split($mobile,3," ");
    }
}


if (!function_exists('base64url_encode')) {
    function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

if (!function_exists('base64url_decode')) {
    function base64url_decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}

if (!function_exists('calculate_time_difference_in_hours')) {
    function calculate_time_difference_in_hours(string $start, string $end): float
    {
        $minutes = abs(strtotime($end) - strtotime($start)) / 60;
        return round($minutes / 60, 2);
    }
}

if (!function_exists('formatear_fecha')) {
    function formatear_fecha(string $fecha): string
    {
        $date = date_create($fecha);
        return $date ? date_format($date, 'Y-m-d') : $fecha;
    }
}

/**
 * convert_hours_minutes
 * @author bmottag
 * @param $horasDecimal
 */
if (!function_exists("convert_hours_minutes")) {
    function convert_hours_minutes($horasDecimal) {
        if (!is_numeric($horasDecimal) || $horasDecimal == 0) {
            return "-";
        }

        $horasDecimal = (float)$horasDecimal;

        $horas = floor($horasDecimal);
        $minutos = round(($horasDecimal - $horas) * 60);

        return sprintf("%d hrs %02d min", $horas, $minutos);
    }
}

if (!function_exists('working_hours_in_hours_format')) {
    function working_hours_in_hours_format($dateStart, $dateFinish) {
        // Calcular diferencia en minutos
        $minutos = abs(strtotime($dateFinish) - strtotime($dateStart)) / 60;
        $minutos = round($minutos);

        // Convertir a horas decimales
        $horas = $minutos / 60;
        $horas = round($horas, 2);

        // Separar parte entera y decimal
        $justHours = intval($horas);
        $decimals = $horas - $justHours;

        // Redondeo personalizado a bloques de 15/30/45 min o 1 hora
        if ($decimals < 0.12) {
            $transformation = 0;
        } elseif ($decimals >= 0.12 && $decimals < 0.37) {
            $transformation = 0.25;
        } elseif ($decimals >= 0.37 && $decimals < 0.62) {
            $transformation = 0.5;
        } elseif ($decimals >= 0.62 && $decimals < 0.87) {
            $transformation = 0.75;
        } else {
            $transformation = 1;
        }

        // Resultado final ajustado
        $workingHours = $justHours + $transformation;

        return $workingHours . " hrs";
    }
}

/**
 * Send email and/or SMS notification to a list of recipients.
 * @author bmottag
 * @review 13/05/2026 - new CI4 version
 * @param array  $configuracionAlertas  Result of get_notifications_access()
 * @param string $subject               Email subject
 * @param string $emailBody             HTML content body (without outer wrapper)
 * @param string $smsMessage            SMS text
 * @param string $approvalBaseUrl       Base URL path for per-recipient approval link (optional)
 * @param int    $recordId              Record ID appended to approval URL (optional)
 */
if (!function_exists("send_notification")) {

    function send_notification(array $configuracionAlertas, string $subject, string $emailBody, string $smsMessage, string $approvalBaseUrl = '', int $recordId = 0): void
    {
        $emailService = new \App\Libraries\EmailService();
        $smsService   = new \App\Libraries\SmsService();

        foreach ($configuracionAlertas as $envioAlerta) {
            if (!empty($envioAlerta['email'])) {
                $emailFinal = $emailBody;
                if ($approvalBaseUrl && $recordId) {
                    $emailFinal .= base_url($approvalBaseUrl . '/' . $recordId . '/' . $envioAlerta['id_user_email']);
                }

                $userName  = $envioAlerta['name_email'] ?? '';
                $fullEmail = "<html><head><title>{$subject}</title></head><body>"
                    . "<p>Dear {$userName}:<br/></p>"
                    . $emailFinal
                    . "<p>Cordially,</p><p><strong>V-CONTRACTING INC</strong></p>"
                    . "</body></html>";

                $result = $emailService->sendRaw($envioAlerta['email'], $subject, $fullEmail);
                if ($result !== true) {
                    log_message('error', 'send_notification email error: ' . print_r($result, true));
                }
            }

            if (!empty($envioAlerta['movil']) && !empty($smsMessage)) {
                $smsFinal = $smsMessage;
                if ($approvalBaseUrl && $recordId) {
                    $smsFinal .= base_url($approvalBaseUrl . '/' . $recordId . '/' . $envioAlerta['id_user_sms']);
                }
                $smsService->send('+1' . $envioAlerta['movil'], $smsFinal);
            }
        }
    }

}