<?php
include 'db-query.php';

$GLOBALS['fanPin'] = '13';

$function = $_GET['function'];
$idFan = $_GET['idFan'];
$value = $_GET['value'];

if ($function == 'switchFanDb') {
	switchFanDb($idFan);
} else if ($function == 'setFanWishedTemperatureDb') {
	setFanWishedTemperatureDb($idFan, $value);
} else {
	echo "fan-control.php Unknow function: " . $function;
}

function switchFanDb($idFan) {//conmuta el estado de la base de datos de la entrada ventilador

	$sqlQuery = "SELECT state FROM controls WHERE control_id = '$idFan';";
	$state = dbQuery($sqlQuery);
	$command;
	if ($state == 0) {
		$sqlQuery = "UPDATE controls SET state = 1 WHERE control_id = '$idFan';";
		dbQuery($sqlQuery);
		echo "Ventilación habilitada";
	} else if ($state == 1) {
		$sqlQuery = "UPDATE controls SET state = 0 WHERE control_id = '$idFan';";
		dbQuery($sqlQuery);
		echo "Ventilación deshabilitada";
	} else {
		echo "Error. Estado desconocido";
	}
}

function setFanWishedTemperatureDb($idFan, $value) {
	$sqlQuery = "UPDATE climate SET wished_temperature = $value WHERE fan_id = '$idFan';";
	dbQuery($sqlQuery);
	echo "Temperatura deseada de ventilador $idFan cambiada a $value \n";
}

/*function setFan($value) {//modifica el estado del pin que controla el ventilador

 if ($value == 'on') {
 $command = 'sudo python ../scripts/pinOn.py ' . $GLOBALS['fanPin'] ;
 exec($command);
 echo "Ventilación encendida";
 } else if ($value == 'off') {
 $command = 'sudo python ../scripts/pinOff.py ' . $GLOBALS['fanPin'] ;
 exec($command);
 echo "Ventilación apagada";
 } else {
 echo "fan-control.php setFan() unknow state. State: " . $value;
 }
 }*/
?>