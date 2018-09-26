<?php

if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once ('/home/pi/domotica/web/login/libraries/password_compatibility_library.php');
}
// include the config
require_once ('/home/pi/domotica/web/login/config/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once ('/home/pi/domotica/web/login/translations/es_ES.php');
require_once ('/home/pi/domotica/web/login/libraries/PHPMailer.php');

include '/home/pi/domotica/web/db-query.php';

//Demonio que hace sonar el timbre en caso de detección de personas/objetos

//envía un correo al administrador indicandole que la alarma ha sonado
function sendAlarmEmail() {

    $mail = new PHPMailer;

    $mail -> CharSet = 'UTF-8';

    // please look into the config/config.php for much more info on how to use this!
    // use SMTP or use mail()
    if (EMAIL_USE_SMTP) {
        // Set mailer to use SMTP
        $mail -> IsSMTP();
        //useful for debugging, shows full SMTP errors
        //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        // Enable SMTP authentication
        $mail -> SMTPAuth = EMAIL_SMTP_AUTH;
        // Enable encryption, usually SSL/TLS
        if (defined(EMAIL_SMTP_ENCRYPTION)) {
            $mail -> SMTPSecure = EMAIL_SMTP_ENCRYPTION;
        }
        // Specify host server
        $mail -> Host = EMAIL_SMTP_HOST;
        $mail -> Username = EMAIL_SMTP_USERNAME;
        $mail -> Password = EMAIL_SMTP_PASSWORD;
        $mail -> Port = EMAIL_SMTP_PORT;
    } else {
        $mail -> IsMail();
    }

    $mail -> From = EMAIL_VERIFICATION_FROM;
    $mail -> FromName = EMAIL_VERIFICATION_FROM_NAME;
    $mail -> AddAddress('domotfg@gmail.com');
    $mail -> Subject = "TFG Domótica: Alarma";

    //$link = EMAIL_VERIFICATION_URL . '?id=' . urlencode($user_id) . '&verification_code=' . urlencode($user_activation_hash) . '&user_email=' . $user_email;

    // the link to your register.php, please set this value in config/email_verification.php
    $mail -> Body = "¡Alarma!";

    if (!$mail -> Send()) {
        $this -> errors[] = MESSAGE_VERIFICATION_MAIL_NOT_SENT . $mail -> ErrorInfo;
        return false;
    } else {
        return true;
    }
}

function main() {

    $umbral = 10;
    //distancia en cm
    $commandRing = 'sudo python /home/pi/domotica/scripts/presenceRing.py';
    $commandProximity = 'sudo /home/pi/domotica/scripts/proximity';

    $sqlQueryPresence = "SELECT state FROM controls WHERE control_id = 'presence_ring';";
    $sqlQueryAlarm = "SELECT state FROM controls WHERE control_id = 'alarm_ring';";
    $sqlQueryAlarmPin = "SELECT pin_id from pins WHERE control_id = 'alarm_ring'";
    //comprobación on/off
    $statePresence;
    //cerrojo para que no pite sucesivas veces
    $block = false;
    $alarmOn = false;

    $pinAlarm = dbQuery2($sqlQueryAlarmPin, 'pin_id');

    $commandAlarmOn = 'sudo python /home/pi/domotica/scripts/pinOn.py ' . $pinAlarm;
    $commandAlarmOff = 'sudo python /home/pi/domotica/scripts/pinOff.py ' . $pinAlarm;

    while (1) {
        $statePresence = dbQuery($sqlQueryPresence);
        $stateAlarm = dbQuery($sqlQueryAlarm);

        echo "Estado alarma: " . $stateAlarm . "\n";
        echo "Estado presencia: " . $statePresence . "\n";

        //si alguno de los timbres está activo se mide la distancia
        if ($stateAlarm == 1 || $statePresence == 1) {
            //calcula la distancia a la que se encuentra el objeto
            $responseProximity = exec($commandProximity);
            $distance = filter_var($responseProximity, FILTER_SANITIZE_NUMBER_INT);
            echo "Distancia = " . $distance . "\n";
        }

        //si está activada la alarma y detecta presencia suena
        if ($stateAlarm == 1 && $alarmOn == false && ($distance < $umbral)) {
            exec($commandAlarmOn);
            $alarmOn = true;
            sendAlarmEmail();
        } else if ($stateAlarm == 0 && $alarmOn == true) {
            $alarmOn = false;
            exec($commandAlarmOff);
        } else if ($statePresence == 1 && $stateAlarm == 0) {//si está activado el timbre de presencia
            if ($distance < $umbral) {
                //evita que suene dos veces seguidas
                if ($block == FALSE)
                    //si está dentro del umbral y no está bloqueado
                    exec($commandRing);
                $block = true;
            } else
                //si no hay objeto en medio se desbloquea
                $block = false;
        } else {
            sleep(1);
        }
    }

}

main();
?>