<?php
include 'db-query.php';

$request = $_GET['function'];
$input = $_GET['input'];

if ($request == 'setAllLights()') {
    switchCamera();
} else if ($request == 'getCameraStatus()') {
switchLight($_GET['idLight']);

function switchLight($idLight) {//conmuta el estado de la base de datos y del pin del GPIO a partir del número de la luz

    if ($idLight != null) {//con esto evitamos casos extraños como que se llame con $number = 0 sin querer
        $sqlQuery = "SELECT state FROM controls WHERE control_id = '$idLight';";
        $state = dbQuery($sqlQuery);

        $sqlQuery = "SELECT pin_id FROM pins WHERE control_id = '$idLight';";
        $pin = dbQuery2($sqlQuery, 'pin_id');
        echo $sqlQuery;

        $command;
        if ($state == 0) {
            $sqlQuery = "UPDATE controls SET state = 1 WHERE control_id = '$idLight';";
            dbQuery($sqlQuery);
            $command = 'sudo python /home/pi/domotica/scripts/pinOn.py ' . $pin;
            exec($command);
            echo $command . "</br>";
            echo "Luz $idLight encendida";

        } else {
            $sqlQuery = "UPDATE controls SET state = 0 WHERE control_id = '$idLight';";
            dbQuery($sqlQuery);
            $command = 'sudo python /home/pi/domotica/scripts/pinOff.py ' . $pin;
            exec($command);
            echo "Luz $idLight apagada";
        }
    }
}

function setAllLights($state) {
    $servername = 'localhost';
    $user = 'root';
    $pass = 'tfg2015';
    $db = 'domo_tfg';
    //Crea una conexión
    $conn = new mysqli($servername, $user, $pass, $db);

    $sqlQuery = "SELECT pin_id from pins where control_id LIKE 'light%';";
    $result = mysqli_query($conn, $sqlQuery);

    //Verifica conexión
    if ($conn -> connect_error) {
        die("Connection failed: " . $conn -> connect_error);
    }
    echo "Resultado";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $pin = $row["pin_id"];
            if ($state == 1)
                $command = 'sudo python /home/pi/domotica/scripts/pinOn.py ' . $pin;
            else {
                $command = 'sudo python /home/pi/domotica/scripts/pinOff.py ' . $pin;
            }
            exec($command);
            echo $command . "</br>";
        }
    } else {
        echo "0_Results";
    }

    //cambia los estados en la BDD
    if ($state == 1) {
        $sqlQuery = "update controls set state = 1 where control_id LIKE 'light%'";
    } else {
        $sqlQuery = "update controls set state = 0 where control_id LIKE 'light%';";
    }
    $result = mysqli_query($conn, $sqlQuery);

    mysqli_close($conn);
}
	?>
