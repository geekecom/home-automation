<?php

if($_GET['function'] == 'getClimateIndoor'){
	getClimateIndoor();
}else if ($_GET['function'] == 'getClimateOutdoor'){
	getClimateOutdoor();
}else{
	echo "climate-control.php Error. Unknow function.";
}

//Ejecuta el script en Python y devuelve los datos formateados
function getClimateIndoor() {
	$climate = shell_exec('sudo python ../scripts/climate.py');
	$climateArray = explode("\n", $climate);
	$temperature = "Temperatura: " . round($climateArray[0], 1) . " ºC";
	$humidity = "Humedad: " . round($climateArray[1]) . ' %';
	echo $temperature . '/' . $humidity;
}

function getClimateOutdoor(){
	$url = 'http://api.openweathermap.org/data/2.5/weather?q=Sevilla';
	echo getHTML($url, 10);
}

function getHTML($url,$timeout)
{
       $ch = curl_init($url); // initialize curl with given url
       curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // max. seconds to execute
       curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
       return @curl_exec($ch);
} 
?>
