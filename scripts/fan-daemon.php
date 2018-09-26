<?php
include '/home/pi/domotica/web/db-query.php';

$idFan;
$fanPin;
$fanStateDB;

if (isset($argv[1])) {
	global $idFan;
	$idFan = $argv[1];
	main();
} else {
	echo "Debe de indicar el id de la zona de ventilación que desea activar. \n";
}

function main() {

	global $idFan;
	global $fanPin;
	global $fanStateDB;

	$fanPin = getFanPin();
	$fanStateDb = getFanStateDb();

	echo "idFan = " . $idFan . " \n";
	echo "fanPin = " . $fanPin . " \n";
	echo "fanStateDB = " . $fanStateDb . " \n";

    $currentStatus = true;
    
	//primera comprobación
	if ($fanStateDb == 1) {
		$currentTemperature = getTemperature();
		echo "Temperatura actual: " . $currentTemperature;
		echo "\n";
		$wishedTemperature = getWishedTemperatureDb();
		echo "Temperature deseada: " . $wishedTemperature;
		echo "\n";
		//si la temperatura deseada actual es mayor que la deseada enciende el ventilador
		if ($currentTemperature > $wishedTemperature) {
			echo "Encendido";
			setFanStatus(1);
			$currentStatus = true;
		}	//en caso contrario lo apaga si no está ya apagado
	} else {
		echo "Apagado";
		setFanStatus(0);
		$currentStatus = false;
	}
	echo "\n\n";
	sleep(5);

	//bucle que comprueba si debe conmutar la ventilación
	while (1) {
		$fanStateDb = getFanStateDb();
		if ($fanStateDb == true) {
			$currentTemperature = getTemperature();
			echo "Temperatura actual: " . $currentTemperature;
			echo "\n";
			$wishedTemperature = getWishedTemperatureDb();
			echo "Temperature deseada: " . $wishedTemperature;
			echo "\n";
			//si la temperatura deseada actual es mayor que la deseada enciende el ventilador
			if ($currentTemperature > $wishedTemperature) {
				if ($currentStatus == FALSE) {
					echo "Encendido";
					setFanStatus(1);
					$currentStatus = true;
				}
				//en caso contrario lo apaga si no está ya apagado
			} else {
				if ($currentStatus == TRUE) {
					echo "Apagado";
					setFanStatus(0);
					$currentStatus = false;
				}
			}
		}else{
		    if ($currentStatus == TRUE) {
                    setFanStatus(0);
                    $currentStatus = false;
                    echo "El ventilador $idFan está desactivado";
            }
		}
		echo "\n\n";
		sleep(5);
	}
}

function getFanStateDb() {
	global $idFan;
	$sqlQuery = "SELECT state FROM controls WHERE control_id = '$idFan';\n";
	$state = dbQuery($sqlQuery);
	return $state;
}

function getFanPin() {
	global $idFan;
	$sqlQuery = "SELECT pin_id FROM pins WHERE control_id = '$idFan';";
	$pin = dbQuery2($sqlQuery, 'pin_id');
	return $pin;
}

function getWishedTemperatureDb() {
	global $idFan;
	$sqlQuery = "SELECT wished_temperature FROM climate WHERE fan_id = '$idFan';";
	$wishedTemperature = dbQuery2($sqlQuery, 'wished_temperature');
	return $wishedTemperature;
}

//enciende o apaga la ventilacion. Modifica el estado del pin que controla el ventilador
function setFanStatus($value) {
	global $fanPin;
	if ($value == 1) {
		$command = 'sudo python /home/pi/domotica/scripts/pinOn.py ' . $fanPin;
		exec($command);
		//"Ventilación encendida";
	} else if ($value == 0) {
		$command = 'sudo python /home/pi/domotica/scripts/pinOff.py ' . $fanPin;
		exec($command);
		//"Ventilación apagada";
	} else {
		echo "fan-control.php setFan() unknow state. State: " . $value;
	}
}

//toma la temperatura del sensor
function getTemperature() {
	global $fanPin;
	$command = 'sudo python /home/pi/domotica/scripts/temperature.py ' . $fanPin;
	$temperature = exec($command);
	return $temperature;
}
?>