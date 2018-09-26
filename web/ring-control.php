<?php
include 'db-query.php';

$function = $_GET['function'];
$idRing = $_GET['idRing'];
$value = $_GET['value'];

if ($function == 'setRingDb') {
    setRingDb($idRing,$value);
} else {
    echo "ring-control.php Unknow function: " . $function;
}

function setRingDb($idRing, $value) {//conmuta el estado de la base de datos de la entrada ventilador

    $sqlQuery = "UPDATE controls SET state = $value WHERE control_id = '$idRing';";
    dbQuery($sqlQuery);
    echo "Timbre $idRing puesto a $value";
}
?>