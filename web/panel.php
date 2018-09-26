<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="panel-style.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="scripts.js"></script>
        
        <title>Sistema domótico de Lo</title>

        <?php
        if (version_compare(PHP_VERSION, '5.3.7', '<')) {
            exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
        } else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
            // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
            // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
            require_once ('login/libraries/password_compatibility_library.php');
        }
        // include the config
        require_once ('login/config/config.php');

        // include the to-be-used language, english by default. feel free to translate your project and include something else
        require_once ('login/translations/es_ES.php');

        // include the PHPMailer library
        require_once ('login/libraries/PHPMailer.php');

        // load the login class
        require_once ('login/classes/Login.php');

        // create a login object. when this object is created, it will do all login/logout stuff automatically
        // so this single line handles the entire login process.
        $login = new Login();
        // ... ask if we are logged in here:
        if ($login -> isUserLoggedIn() != true) {
            // the user is logged in
            header("location:logIn");
        }

        include ("db-query.php");
        ?>
    </head>

    <body>
        <header id="topBar">
            <div id='date'>
                <?php
                // Establecer la zona horaria predeterminada
                date_default_timezone_set('CET');
                setlocale(LC_TIME, 'es_ES.UTF-8');

                // Imprime la fecha y la hora
                echo strftime("%A, %e de %B de %G");
                ?>
                <br>
                <br>
                <?php
                include ("login/views/logged_in.php");
                ?>
            </div>

            <div id='panel_title'>
                <h1 >Panel de control</h1>
                <p id='connectionDbStatusP'>
                    Error
                </p>
            </div>
        </header>

        <div id="leftDiv">

            <!-- indoor climate-->
            <table width="100%">
                <div id='indoorClimate' class="leftSide">
                    <h3>Clima interior</h3>
                    <div id='temperatureIn' class='climate'></div>
                    <br>
                    <div id='humidityIn' class='climate'></div>
                </div>

                <div id='outdoorClimate' class='leftSide'>
                    <h3>Clima exterior</h3>
                    <div id='temperatureOut' class='climate'>
                        Temperatura:
                    </div>
                    <br />
                    <div id='humidityOut' class='climate'>
                        Humedad:
                    </div>
                </div>
            </table>

            <div id='lastChange' class="leftSide"></div>
        </div>

        <div id = 'centerDiv'>

            <div id='cameraDiv'>
                <img id='cameraImg' src=''/>
            </div>
        </div>

        <div id='controlsDiv'>
            <h1 align="center">Controles</h1>

            <h3 align="center">Luces</h3>

            <div id='controlLight'>
                <table width="100%">

                    <tr>
                        <td width="50%">Control</td>
                        <td>Estado</td>
                    </tr>
                    <tr>
                        <td align="center">Luz entrada</td>
                        <td><a id='light0Button' href="#"
                        <?php //muestra un botón u otro si está encendida
                            //lee el estado del elemento
                            $state = dbQuery("SELECT state FROM controls WHERE control_id = 'light0'");
                            if ($state == "1")//elemento en ON -> botón ON
                                echo "class='buttonOn'>ON";
                            else if ($state == "0") {//elemento en ON -> botón ON
                                echo "class='buttonOff'>OFF";
                            } else {//error
                                echo ">Error aquí";
                            }
                        ?> </a></td>
                    </tr>
                    <tr>
                        <td align="center">Luz interior
                        <br />
                        </td>
                        <td><a id='light1Button' href="#"
                        <?php //muestra un botón u otro si está encendida
                            //lee el estado del elemento
                            $state = dbQuery("SELECT state FROM controls WHERE control_id = 'light1'");
                            if ($state == "1")//elemento en ON -> botón ON
                                echo "class='buttonOn'>ON";
                            else if ($state == "0") {//elemento en ON -> botón ON
                                echo "class='buttonOff'>OFF";
                            } else {//error
                                echo ">Error aquí";
                            }
                        ?> </a>
                        <br/>
                        </td>
                    </tr>
                    <!--<tr>
                        <td align="center" width="50%">Todas</td>
                        <td><a id='lightsOnButton' href="#" class="buttonOn buttonAllOn">ON</a><a id='lightsOffButton' href="#" class="buttonOff buttonAllOff">OFF</a> </th> </td>-->
                    </tr> 
                 
                </table>
            </div>

            <div id='controlCamera' align="center">
                <h3 align="center">Cámara</h3>
                <form action="camera-site.php">
                    <input type="submit" value="Menú cámara">
                </form>
                <table width="100%">
                    <tr>
                        <td width="50%">Control</td>
                        <td>Estado</td>
                    </tr>
                    <tr>
                        <br>
                    </tr>
                    <tr>
                        <td align="center">Cámara</td>
                        <td><a id='cameraButton' href="#"
                        <?php //muestra un botón u otro si está encendida
                            //lee el estado del elemento
                            $state = dbQuery("SELECT state FROM controls WHERE control_id = 'camera'");
                            if ($state == "1") {//elemento en ON -> botón ON
                                echo "class='buttonOn'>ON";
                            } else if ($state == "0") {//elemento en ON -> botón ON
                                echo "class='buttonOff'>OFF";
                            } else {//error
                                echo ">Error aquí";
                            }
                        ?> </a></td>
                    </tr>
                    <tr>
                        <td align="center">Tomar instantánea</td>
                        <td><a id='snapshotButton' href="#" class="buttonCamera">Captura</a></td>
                    </tr>
                    <tr>
                        <td align="center">Tamaño video</td>
                        <td><a id='cameraMoreButton' href="#" class="buttonOn">+</a><a id='cameraLessButton' href="#" class="buttonLess">-</a></th>
                    </tr>
                </table>
            </div>

            <div id='controlFan'>
                <h3 align="center">Ventilación</h3>
                <table width="100%">
                    <tr>
                        <td width="50%">Control</td>
                        <td>Estado</td>
                    </tr>
                    <tr>
                        <td align="center">Ventilación
                        <br>
                        principal</td>
                        <td><a id='fanButton0' href="#"
                        <?php //muestra un botón u otro si está encendida
                            //lee el estado del elemento
                            $state = dbQuery("SELECT state FROM controls WHERE control_id = 'fan0'");
                            if ($state == "1") {//elemento en ON -> botón ON
                                echo "class='buttonOn'>ON";
                            } else if ($state == "0") {//elemento en ON -> botón ON
                                echo "class='buttonOff'>OFF";
                            } else {//error
                                echo ">Error aquí";
                            }
                        ?> </a>
                    </tr>
                    <tr>
                        <td align="center">Temperatura</td>
                        <td><?php

                        $sqlQuery = "SELECT wished_temperature FROM climate WHERE fan_id = 'fan0';";
                        $wishedTemperature = dbQuery2($sqlQuery, 'wished_temperature');

                        echo "<select id='fanTemp0' style='padding: 9px'>";

                        if ($wishedTemperature != 0)
                            echo "<option value='0'>N/A</option>";
                        else
                            echo "<option selected='selected' value='0'>N/A</option>";

                        for ($x = 18; $x <= 34; $x++) {
                            if ($wishedTemperature != $x)
                                echo "<option value='$x'>$x</option>";
                            else
                                echo "<option selected='selected' value='$x'>$x</option>";
                        }
                        ?>
                        </select></td>
                    </tr>
                </table>
            </div>
            <div id='controlSound'>
                <h3 align="center">Sonido</h3>
                <table width="100%">
                    <tr>
                        <td width="50%">Control</td>
                        <td>Estado</td>
                    </tr>
                    <tr>
                        <td align="center">Timbre</td>
                        <td><a id='doorRingButton' href="#"
                        <?php //muestra un botón u otro si está encendida
                            //lee el estado del elemento
                            $state = dbQuery("SELECT state FROM controls WHERE control_id = 'door_ring'");
                            if ($state == "1") {//elemento en ON -> botón ON
                                echo "class='buttonOn'>ON";
                            } else if ($state == "0") {//elemento en ON -> botón ON
                                echo "class='buttonOff'>OFF";
                            } else {//error
                                echo ">Error aquí";
                            }
                        ?> </a> </th>
                    </tr>
                    <tr>
                        <td align="center">Sonido entrada</td>
                        <td><a id='presenceRingButton' href="#"
                        <?php //muestra un botón u otro si está encendida
                            //lee el estado del elemento
                            $state = dbQuery("SELECT state FROM controls WHERE control_id = 'presence_ring'");
                            if ($state == "1") {//elemento en ON -> botón ON
                                echo "class='buttonOn'>ON";
                            } else if ($state == "0") {//elemento en ON -> botón ON
                                echo "class='buttonOff'>OFF";
                            } else {//error
                                echo ">Error aquí";
                            }
                        ?> </a> </td>
                        </tr>
                        <tr>
                        <td align="center">Alarma</td>
                        <td><a id='alarmRingButton' href="#"
                        <?php //muestra un botón u otro si está encendida
                            //lee el estado del elemento
                            $state = dbQuery("SELECT state FROM controls WHERE control_id = 'alarm_ring'");
                            if ($state == "1") {//elemento en ON -> botón ON
                                echo "class='buttonOn'>ON";
                            } else if ($state == "0") {//elemento en ON -> botón ON
                                echo "class='buttonOff'>OFF";
                            } else {//error
                                echo ">Error aquí";
                            }
                        ?> </a> </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
    <!--
    <div id="wait" style="display:inline;width:79px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;text-align:center;color: red">
    <img src='ajax-loader.gif' width="64" height="64" align="center"/>
    <br>
    Cargando...
    </div>-->
</html>
