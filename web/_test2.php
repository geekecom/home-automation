<?php
/*set_time_limit (0);
 $command = 'nohup php /home/pi/domotica/web/_test.php &';
 exec($command);
 * while(){
 * 	int connection_status ();
 * }*/
?>

<html>
	<body>
		<script>
			document.write("<img src='_test.php?dummy="+(new Date().valueOf()) +"'/>");
			//document.write("<img src='camera-control.php?function=getCameraSrc()'/>");
		</script>
	</body>
</html>