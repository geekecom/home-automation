<?php

require ('login/config/config.php');
require ('login/translations/es_ES.php');
require ('login/libraries/PHPMailer.php');
require ('login/classes/Login.php');

include 'db-query.php';

$request = $_GET['function'];

if ($request == 'switchCamera') {
    switchCamera();
} else if ($request == 'getCameraStatus') {
    getCameraStatus();
} else if ($request == 'getCameraSrc') {
    getCameraSrc();
} else if ($request == 'snapshot') {
    snapshot();
} else if ($request == 'getPicturesList') {
    getPicturesList(); 
} else {
    echo 'Error. Función no declarada? Request: ' . $request;
}

function switchCamera() {//conmuta el estado de la base de datos

    $sqlQuery = "SELECT state FROM controls WHERE control_id = 'camera';";
    $state = dbQuery($sqlQuery);
    $command;

    echo $state . "</br>";

    if ($state == 0) {
        $sqlQuery = "UPDATE controls SET state = 1 WHERE control_id = 'camera';";
        dbQuery($sqlQuery);
        //$command = '../scripts/videoOn.sh &';
        //$command = 'nohup mjpg_streamer -i "/usr/local/lib/input_uvc.so -f 15 -r 320x240" -o "/usr/local/lib/output_http.so -w /home/pi/domotica/video" &';
        //exec($command);
        //(sleep(2));
        //exec('bash -c "exec nohup setsid '.$command.' > /dev/null 2>&1 &"');
        echo " Cámara encendida";
    } else if ($state == 1) {
        $sqlQuery = "UPDATE controls SET state = 0 WHERE control_id = 'camera';";
        dbQuery($sqlQuery);
        //$command = '../scripts/videoOff.sh';
        //exec($command);
        echo " Cámara apagada";
        echo "";
    } else {
        echo "switchCamera() error. Unknow state";
    }
}

function getCameraStatus() {
    $sqlQuery = "SELECT state FROM controls WHERE control_id = 'camera';";
    $state = dbQuery($sqlQuery);
    if ($state == 0) {
        echo 'off';
    } else if ($state == 1) {
        echo 'on';
    } else {
        echo "getCameraStatus() Error. Unknow state";
    }
}

function snapshot() {
    $cmd = 'sudo /home/pi/domotica/scripts/snapshot.sh';
    $out = exec($cmd);
    echo "<pre>$out</pre>";
    echo "snapshot taked";
}

function getCameraSrc() {
    echo "https://nerol.ddns.net:9090/?action=stream";
}

function getPicturesList() {
    $output = shell_exec('ls -t /home/pi/domotica/web/media/snapshots');

    $array = preg_split('/[\r\n]+/', $output);

    foreach ($array as $fileName) {
        //echo $valor. '          ';
        echo "<tr>";
        echo "<a class='snapshotFile' href='#" . $fileName . "'>" . $fileName . '</a><br>';
        echo "</tr>";
    }
}
?>