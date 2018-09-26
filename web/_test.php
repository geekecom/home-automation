<?php
/*include 'db-query.php';

 //Demonio que hace sonar el timbre en caso de detección de personas/objetos
 main();

 function main() {

 $umbral = 10;
 //distancia en cm
 $commandRing = 'sudo python /home/pi/domotica/scripts/presenceRing.py';
 $commandProximity = 'sudo /home/pi/domotica/scripts/proximity';
 $sqlQuery = "SELECT state FROM controls WHERE id = 'presenc';";
 //comprobación on/off
 $state;
 //cerrojo para que no pite sucesivas veces
 $block = false;

 while (1) {
 $state = dbQuery($sqlQuery);
 if ($state == 1) {//si está activado
 $response = exec($commandProximity);
 $distance = filter_var($response, FILTER_SANITIZE_NUMBER_INT);

 echo "Distancia = " . $distance . "\n";

 if ($distance < $umbral) {
 //evita que suene dos veces seguidas
 if ($block == FALSE)
 //si está dentro del umbral y no está bloqueado
 exec($commandRing);
 $block = true;
 } else
 //si no hay objeto en medio se desbloquea
 $block = false;
 }
 }
 }*/

// include the config
require_once ('config/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once ('translations/en.php');

// include the PHPMailer library
require_once ('libraries/PHPMailer.php');

// load the login class
require_once ('classes/Login.php');

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.

session_start();

// if user tried to log out
if (isset($_GET["logout"])) {
    $this -> doLogout();

    // if user has an active session on the server
} elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)) {

    echo "string";
    video();

} else {

    echo "no video";
}

function video() {
    header('content-type: multipart/x-mixed-replace; boundary=--boundarydonotcross');
    //while (@ob_end_clean());
    $username = "domo";
    $password = "tica";
    $url = "localhost:8080/?action=stream";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //curl_setopt($ch, CURLOPT_VERBOSE, 1);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);

    $video = curl_exec($ch);
    return $video;
    curl_close($ch);
}
?>