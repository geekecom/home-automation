<html>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    
    <title>Sistema domótico de Lo</title>


    <link rel="stylesheet" href="panel-style.css"/>
    
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
        ?>

    <script>
        $(document).ready(function() {
            //Camera site scripts

            function getPicturesList() {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        var response = xmlhttp.responseText;
                        document.getElementById("capturas").innerHTML = response;
                    } else {
                        document.getElementById("capturas").innerHTML = "Error";
                    }
                };
                xmlhttp.open("GET", "camera-control.php?function=getPicturesList", false);
                xmlhttp.send();
            }

            function takeSnapshot() {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        var response = xmlhttp.responseText;
                        document.getElementById("lastChange").innerHTML = response;
                    } else {
                        document.getElementById("lastChange").innerHTML = "Error takeSnapshot";
                    }
                };
                xmlhttp.open("GET", "camera-control.php?function=snapshot", false);
                xmlhttp.send();
            }

            //snapshotButton clicked
            // This will work for dynamically created element
            $('body').on('click', '.snapshotFile', function(e) {
                $('#snapshotImg').show();
                var fileName = $(this).text();
                $('#snapshotImg').attr("src", 'https://nerol.ddns.net/media/snapshots/' + fileName);
            });
            /*        $(".snapshotFile").live("click", function() {
             var fileName = $(this).text();
             $('#snapshotImg').attr("src", 'https://nerol.ddns.net/media/snapshots/' + fileName);
             });*/

            $('#snapshotButton2').click(function() {
                takeSnapshot();
                getPicturesList();
            });

            //Aumenta el tamaño de la imagen
            $('#cameraMoreButton').click(function() {
                $('#snapshotImg').width($('#snapshotImg').width() + (320 / 4));
                $('#snapshotImg').height($('#snapshotImg').height() + (240 / 4));
                return false;
            });

            //Reduce el tamaño de la imagen
            $('#cameraLessButton').click(function() {
                $('#snapshotImg').width($('#snapshotImg').width() - (320 / 4));
                $('#snapshotImg').height($('#snapshotImg').height() - (240 / 4));
                return false;
            });

            $('#snapshotImg').hide();
            getPicturesList();

            document.getElementById("lastChange").innerHTML = 'Listo';
        });
    </script>
    <body>
        <div id="topBar" style:"clear:both">
            <div id='panel_title'>
                <h1 >Panel de control de la cámara</h1>
                <div id='lastChange'>
                    Error
                </div>
            </div>
        </div>

        <div align="center" id='controlsDiv2' style="background-color:rgba(192,192,192,0.7);padding-top: 3%;">
            <table style="">
                <form action="panel">
                    <input type="submit" value="Panel principal">
                </form>
                <br>
                <br>
                <br>

                Tomar captura:
                <a id='snapshotButton2' href="#" class="buttonCamera">Captura</a>
                <br />
                <br />
                <br />
                Tamaño imagen:
                <a id='cameraMoreButton' href="#" class="buttonOn">+</a>
                <a id='cameraLessButton' href="#" class="buttonLess">-</a>

                <tr>
                    <h2>Capturas</h2>
                </tr>
                <tr id='capturas'></tr>
            </table>
        </div>

        <div id='cameraDiv2'>
            <img id='snapshotImg'></img>
            <img id='snapshotImg' src='https://nerol.ddns.net:9090/?action=stream'></img>
        </div>

    </body>
</html>
