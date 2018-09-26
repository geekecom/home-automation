<?php
//función para hacer peticiones a la BD
function dbQuery($sqlQuery) {
	$servername = 'localhost';
	$user = 'root';
	$pass = 'tfg2015';
	$db = 'domo_tfg';
	//Crea una conexión
	$conn = new mysqli($servername, $user, $pass, $db);
	$result = mysqli_query($conn, $sqlQuery);

	//Verifica conexión
	if ($conn -> connect_error) {
		die("Connection failed: " . $conn -> connect_error);
	}
	//devuelve solo el primer resultado
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$state = $row["state"];
			return $state;
		}
	} else {
		return "0_Results";
	}
	mysqli_close($conn);
}

//función para hacer peticiones a la BD
function dbQuery2($sqlQuery,$column) {
	$servername = 'localhost';
	$user = 'root';
	$pass = 'tfg2015';
	$db = 'domo_tfg';
	//Crea una conexión
	$conn = new mysqli($servername, $user, $pass, $db);
	$result = mysqli_query($conn, $sqlQuery);

	//Verifica conexión
	if ($conn -> connect_error) {
		die("Connection failed: " . $conn -> connect_error);
	}
	//devuelve solo el primer resultado
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$result = $row[$column];
			return $result;
		}
	} else {
		return "0_Results";
	}
	mysqli_close($conn);
}


?>