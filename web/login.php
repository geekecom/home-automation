<!DOCTYPE html>
<html>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<style>
		.error {
			color: #FF0000;
		}
	</style>
	<body style="background-image: url('http://www.reformesuhogar.es/wp-content/uploads/2014/07/14427000976_7d4252d5c2_o.jpg');background-size:cover">
		<?php
		$loginErr = "";
		$name = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if ($_POST["name"] != "domo" || $_POST["password"] != "tica") {
				$loginErr = "Login incorrecto";
			} else {
				$name = test_input($_POST["name"]);
				ob_start();
				$url = 'panel.php';
				// this can be set based on whatever
				// clear out the output buffer
				/*while (ob_get_status()) {
				 ob_end_clean();
				 }*/
				// Check if session is not registered, redirect back to main page.
				// Put this code in first line of web page.
				session_start();
				$_SESSION['name'] = $_POST["name"];
				if (isset($_SESSION['name'])) {
					header("Location: $url");
				}
			}
		}
		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		};
		?>
		<div id="all" style="text-align: center;color:white;position:absolute; top:35%; margin-top:0;left:35%;background-color:rgba(192,192,192,0.8);">
		<h2>Bienvenido al sistema domótico de Lo</h2>
		<br>
		Autenticación obligatoria
		<br>
		<br>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<table style="margin-left: auto;margin-right: auto;color:white">
		<tr>
		<td>
		<input type="text" name="name"placeholder="usuario">
		</td>
		</tr>
		<tr>
		<td>
		<input type="password" name="password"placeholder="contraseña">
		</td>
		</tr>
		</table>
		<br>
		<input type="submit" value="Entrar">
		</form>
		<span class="error"> <?php
		echo "<br>";
		echo $loginErr;
		echo "</span>";
		if ($loginErr == "") {
		}
		?></div>
	</body>
</html>
