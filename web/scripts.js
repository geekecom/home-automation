/**
 * @author Lorenzo Lerate
 */

$(document).ready(function() {

    $('#lastChange').hide();

    //=========================TEMPERATURE & HUMIDITY====================================

    function getExternalTempHum() {//obtiene la temperatura y humedad de un servidor externo
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var msg;
        var temp;
        var hum;
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                msg = xmlhttp.responseText;
                tempIndex = msg.indexOf("temp");
                temp = msg.substring(tempIndex + 6, tempIndex + 11);
                humIndex = msg.indexOf("humidity");
                hum = msg.substring(humIndex + 10, humIndex + 12);
                temp = temp - 273.15;
                temp = Math.round(temp * 10) / 10;
                document.getElementById('temperatureOut').innerHTML += temp + " ºC";
                document.getElementById("humidityOut").innerHTML += hum + " %";
            }
        };

        xmlhttp.open("GET", "climate-control.php?function=getClimateOutdoor", false);
        xmlhttp.send();
    }

//Llama a la función PHP y muestra los datos de la forma deseada
    function getInternalTempHum() {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            InternalTempHumRequest = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            InternalTempHumRequest = new ActiveXObject("Microsoft.XMLHTTP");
        }

        InternalTempHumRequest.onreadystatechange = function() {
            if (InternalTempHumRequest.readyState == 4 && InternalTempHumRequest.status == 200) {
                var response = InternalTempHumRequest.responseText;
                document.getElementById("lastChange").innerHTML = response;
                response = response.split('/');
                $('#temperatureIn').text(response[0]);
                $('#humidityIn').text(response[1]);

            } else {
                document.getElementById("lastChange").innerHTML = "Error getInternalTempHum";
            }
        };
        InternalTempHumRequest.open("GET", "climate-control.php?function=getClimateIndoor", false);
        InternalTempHumRequest.send();
    }

    getExternalTempHum();
    getInternalTempHum();

    //====================BUTTONS======================================

    //conmuta entre ON & OFF buttons
    function switchButtonStatus(buttonId) {

        var button = document.getElementById(buttonId);

        if (button.className == "buttonOff") {//si botón es OFF -> ON
            button.innerHTML = "ON";
            button.className = "buttonOn";

        } else {//si botón es ON -> OFF
            button.innerHTML = "OFF";
            button.className = "buttonOff";
        }
    }

    //=====================================LIGHTS=================================

    //Llama a una función mediante AJAX que conmuta la luz
    function switchLight(idLight) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("lastChange").innerHTML = xmlhttp.responseText;
            } else {
                document.getElementById("lastChange").innerHTML = "Error switchlight";
            }
        };
        var url = "lights-control.php?function=switchLight&input=" + idLight;
        xmlhttp.open("GET", url, false);
        xmlhttp.send();
    }
    //Llama a una función mediante AJAX que conmuta todas las luces con el valor deseado
    function setAllLights(state) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("lastChange").innerHTML = xmlhttp.responseText;
            } else {
                document.getElementById("lastChange").innerHTML = "Error setAllLights";
            }
        };
        var url = "lights-control.php?function=setAllLights&input=" + state;
        xmlhttp.open("GET", url, false);
        xmlhttp.send();
    }


    $("#light0Button").click(function() {//botón de la luz ha sido pulsado
        //conmuta el estado de la luz
        switchLight('light0');
        //conmuta el estado del botón
        switchButtonStatus('light0Button');
        return false;
    });

    $("#light1Button").click(function() {//botón de la luz ha sido pulsado
        //conmuta el estado de la luz
        switchLight('light1');
        //conmuta el estado del botón
        switchButtonStatus('light1Button');
        return false;
    });

    //Turn off all the lights
    $("#lightsOnButton").click(function() {
        setAllLights(1);
        switchButtonStatus('light0Button');
        switchButtonStatus('light1Button');
        return false;
    });

    //Turn on all the lights
    $("#lightsOffButton").click(function() {
        setAllLights(0);
        switchButtonStatus('light0Button');
        switchButtonStatus('light1Button'); 
        return false;
    });

    //=========================CAMERA===============================
    //Pide mediante AJAX que se conmute el estado del control de la BDD asociado a la cámara 
    function switchCameraStatus() {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $('#camera').text(xmlhttp.responseText);
            } else {
                document.getElementById("lastChange").innerHTML = "Error switchCameraDiv";
            }
        };
        var url = "camera-control.php?function=switchCamera";
        xmlhttp.open("GET", url, false);
        xmlhttp.send();

    }
    
//muestra u oculta el visor de la cámara según su estado
    function showHideCamera() {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var ret;

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("lastChange").innerHTML = xmlhttp.responseText;
                
                response = xmlhttp.responseText.replace(/\s/g, '');

                if (response == 'on') {
                    $('#cameraImg').show();
                    $("#cameraImg").attr("src", "https://nerol.ddns.net:9090/?action=stream");
                    ret = 1;

                } else if (response == 'off') {
                    $('#cameraImg').hide();
                    $("#cameraImg").attr("src", "");
                    ret = 0;

                } else {
                    document.getElementById("lastChange").innerHTML = "Error showHideCamera. Response: " + xmlhttp.responseText;
                }
            }
        };

        xmlhttp.open("GET", "camera-control.php?function=getCameraStatus", false);
        xmlhttp.send();

        return ret;
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
    
    //Ejecuta la función al cargar la página para mostrarla si hiciera falta
    showHideCamera();

    //activa/desactiva la visualización de vídeo
    $("#cameraButton").click(function() {
        //conmuta el estado de la cámara
        //La siguiente función no enciende y apaga el video solo cambia el estado el la BD
        switchCameraStatus();
        //conmuta el estado del botón
        switchButtonStatus('cameraButton');
        //conmuta el estado del visor
        showHideCamera();
        return false;
    });

    //Aumenta el tamaño del video
    $('#cameraMoreButton').click(function() {
        $('#cameraImg').width($('#cameraImg').width() + (320 / 4));
        $('#cameraImg').height($('#cameraImg').height() + (240 / 4));
        return false;
    });

    //Reduce el tamaño del video
    $('#cameraLessButton').click(function() {
        $('#cameraImg').width($('#cameraImg').width() - (320 / 4));
        $('#cameraImg').height($('#cameraImg').height() - (240 / 4));
        return false;
    });

    //se pulsa el botón de captura
    $('#snapshotButton').click(function() {
        takeSnapshot();
        return false;
    });


    //================FAN====================

    function fanControl(func, idFan, value) {//llama a funciones de ventilación

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("lastChange").innerHTML = xmlhttp.responseText;
            } else {
                document.getElementById("lastChange").innerHTML = "Error fanControl. Response error";
            }
        };
        var msg = "fan-control.php?function=" + func + "&idFan=" + idFan + "&value=" + value;
        xmlhttp.open("GET", msg, false);
        xmlhttp.send();

    }

    //se pulsa el botón de la ventilación
    $('#fanButton0').click(function() {
        switchButtonStatus('fanButton0');
        var clase = $(this).attr("class");
        if (clase == 'buttonOn')
            fanControl('switchFanDb', 'fan0', 1);
        else if (clase == 'buttonOff')
            fanControl('switchFanDb', 'fan0', 0);
        else
            alert("Clase no reconocida : " + clase);
        return false;
    });

    $("#fanTemp0").change(function() {
        var tempWished = $('#fanTemp0').prop('value');
        fanControl('setFanWishedTemperatureDb', 'fan0', tempWished);
    });


    //=================RING======================================
    
    function ringControl(func, idRing, value) {//llama a funciones de ventilación
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("lastChange").innerHTML = xmlhttp.responseText;
            } else {
                document.getElementById("lastChange").innerHTML = "Error fanControl. Response error";
            }
        };
        var msg = "ring-control.php?function=" + func + "&idRing=" + idRing + "&value=" + value;
        xmlhttp.open("GET", msg, false);
        xmlhttp.send();
    }

    $("#doorRingButton").click(function() {
        switchButtonStatus('doorRingButton');
        var buttonClass = $(this).attr('class');
        if (buttonClass == 'buttonOn')
            ringControl('setRingDb', 'door_ring', 1);
        else if (buttonClass == 'buttonOff')
            ringControl('setRingDb', 'door_ring', 0);
        else
            alert("Clase desconocida: " + buttonClass);
        return false;
    });

    $("#presenceRingButton").click(function() {
        switchButtonStatus('presenceRingButton');
        var buttonClass = $(this).attr('class');
        if (buttonClass == 'buttonOn')
            ringControl('setRingDb', 'presence_ring', 1);
        else if (buttonClass == 'buttonOff')
            ringControl('setRingDb', 'presence_ring', 0);
        else
            alert("Clase desconocida: " + buttonClass);
        return false;
    });
    
    $("#alarmRingButton").click(function() {
        switchButtonStatus('alarmRingButton');
        var buttonClass = $(this).attr('class');
        if (buttonClass == 'buttonOn')
            ringControl('setRingDb', 'alarm_ring', 1);
        else if (buttonClass == 'buttonOff')
            ringControl('setRingDb', 'alarm_ring', 0);
        else
            alert("Clase desconocida: " + buttonClass);
        return false;
    });

    //================funciones que se ejecutan al inicio=======================

    setInterval(function() {
        getInternalTempHum();
    }, 100000);

    document.getElementById('connectionDbStatusP').innerHTML = 'Listo';

});
