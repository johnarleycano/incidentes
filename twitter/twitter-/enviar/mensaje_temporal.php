<?php
// Envío de correo electrónico de prueba
//Se define zona horaria
date_default_timezone_set('America/Bogota');

//Se carga el archivo de funciones
include '../../funciones.php';

//Se carga la libreria
include 'tmhOAuth.php';

// Mensaje
$mensaje = "La vía Glorieta Niquía - La Frutera (S-N)-(N-s), presenta cierre total, por accidente de tránsito. #Cierre "." (".date('H:i').")";
// $mensaje = "Hoy hasta las 2:00 pm: cierre total por tramos, Santa Rosa de Osos hacia Bello por la Vuelta a Colombia. #VColombia "." (".date('H:i').")";
// $mensaje = "Hasta la 1:00 pm de hoy, cierre total por tramos, por la Vuelta Marco Fidel Suárez, desde Barbosa a Bello. #VueltaMFS"." (".date('H:i').")";

//Se almacena un arreglo con las llaves de la cuenta @johnarleycano para pruebas
$twitter_johnarleycano = array(
	'consumer_key' => 'Jxyg7ccE6J2LQIE3LHRcg',
	'consumer_secret' => 'v08GUFGQlbYXY5fHWOx9V2rFceFx4tGm38G7P4eG4',
	'token' => '126341387-A7tP6DPna0rjmv5KJqvluyZm57vTGHpGl6pjBryA',
	'secret' => 'lzAm164eIf43W1dUWILPnZxM3zLrZJHkUm181H4w'
);

//Se almacena un arreglo con las llaves de la cuenta @hatovial
$twitter_hatovial = array(
	'consumer_key' => 'eOAZ92g1uL5XLVWK2vcR1g',
	'consumer_secret' => 'lzfXC9ePiubbEwFVvv3SE5aAsMDHUrEs6EVamh3MQ4',
	'token' => '1074834686-CvChgwZJ0freJeZw3EgH6f3XMiD0lqSuJybJspi',
	'secret' => 'YtVBzWkWL4qpGErg5mkBxXLVvYWbHVywhjaI0LeMMtk'
);

//Se inicializa
$tmhOAuth = new tmhOAuth($twitter_hatovial);
// $tmhOAuth = new tmhOAuth($twitter_johnarleycano);

//Se envia el mensaje
echo $enviar = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
	'status' => $mensaje
));

?>