<?php
error_reporting(0);

//Se define zona horaria
date_default_timezone_set('America/Bogota');

//Se carga el archivo de funciones
include '../../funciones.php';

//Se carga la libreria
include 'tmhOAuth.php';

/*
 * Aqui se reciben todos los datos enviados del formulario, sean de los combos o del check
 */
$via = $_POST['via'];
$estado = $_POST['estado'];
$ubicacion = $_POST['ubicacion'];
$causa = $_POST['causa'];
$predefinido = $_POST['tweet'];
$twitter_obras = " @obrasantioquia";

//Valida que se hayan seleccionado los dos selects obligatorios o un mensaje predefinido
if($predefinido == "" && ($via == "" ||  $estado == "")){ ?>
	<script type="text/javascript">alert("Debe seleccionar una via y un estado o un mensaje");</script>
	<!--Se redirecciona la pagina-->
    <meta http-equiv="Refresh" content="0; url=../../twitter"/><?php
}else{
	//Se verifica si el mensaje es predefinido o es armado con los selects
	if($predefinido){
		//El mensaje sera el predefinido
    	$mensaje = $predefinido.". ";
    	// $mensaje = $predefinido." (".date('H:i').")";
	}else{
		//Se verifica si tiene causa
	    if(strlen($causa) > 1){$causa = ", por ".$causa;}else{$causa = NULL;}

	    // Se verifica que tenga ubicación
	    if(strlen($ubicacion) > 1){$ubicacion = " a la altura ".$ubicacion." ";}else{$ubicacion = NULL;}
	    
	    //El mensaje sera el que traiga en los selects
	    $mensaje = $via." ".$ubicacion.", ".$estado.$causa.". ";
	    // $mensaje = $via." ".$ubicacion.$estado.$causa.". (".date('H:i').")";
	}
}

$mensaje .= "(".date('H:i').")";

//Se hace el conteo de los caracteres
$caracteres = strlen($mensaje)." ";
 
if($caracteres <= 50){
	$mensaje .= $twitter_obras;
}

//Se muestra un mensaje con la cantidad de caracteres
// echo "Este mensaje contiene ".$caracteres." caracteres. El mensaje es ".$mensaje;

//Se almacena un arreglo con las llaves de la cuenta @johnarleycano para pruebas
$twitter_johnarleycano = array(
	'consumer_key' => 'Jxyg7ccE6J2LQIE3LHRcg',
	'consumer_secret' => 'v08GUFGQlbYXY5fHWOx9V2rFceFx4tGm38G7P4eG4',
	'token' => '126341387-A7tP6DPna0rjmv5KJqvluyZm57vTGHpGl6pjBryA',
	'secret' => 'lzAm164eIf43W1dUWILPnZxM3zLrZJHkUm181H4w'
);

//Se almacena un arreglo con las llaves de la cuenta @cafa1525 para pruebas
$twitter_carlosflorez = array(
	'consumer_key' => 'INUntQQpkJfhxb23S8CBEYyyN',
	'consumer_secret' => 's7X2a0klsZPaRaHaYav0FB2YRFnTBp6haiVzfKGbb1MWm5wqtd',
	'token' => '2709539160-ICz0aXAbTfH23Tu9X0Yp9P7XHrPAQ8GmKbpPSa7',
	'secret' => 'TdclxKuzRchXoLPmNTWQynYZMZRMKOOdtxS0GmaUDsxlE'
);

//Se almacena un arreglo con las llaves de la cuenta @hatovial
$twitter_hatovial = array(
	'consumer_key' => 'eOAZ92g1uL5XLVWK2vcR1g',
	'consumer_secret' => 'lzfXC9ePiubbEwFVvv3SE5aAsMDHUrEs6EVamh3MQ4',
	'token' => '1074834686-CvChgwZJ0freJeZw3EgH6f3XMiD0lqSuJybJspi',
	'secret' => 'YtVBzWkWL4qpGErg5mkBxXLVvYWbHVywhjaI0LeMMtk'
);

//Se inicializa
$tmhOAuth = new tmhOAuth($twitter_carlosflorez);
// $tmhOAuth = new tmhOAuth($twitter_johnarleycano);

// Se envia el mensaje
$enviar = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
	'status' => $mensaje
));
// echo $enviar;

// Si no se envía
if ($enviar != 200) {
	?>
	<script type="text/javascript">alert("El mensaje no se puede enviar. Contiene " + <?php echo $caracteres; ?> + " caracteres. Por favor pase este reporte al área de desarrollo.");</script>

	<!--Se redirecciona la pagina-->
	<meta http-equiv="Refresh" content="0; url=../../querys.php"/>
	<?php

	die();
} else {
	//Se envio bien el twit
    echo 'Mensaje enviado correctamente con '.$caracteres.' caracteres<br/>';

    //Se inserta el registro en la bitacora
	$bitacora_twitter = "insert into tbl_bitacora values ('', '', '".date('Y-m-d H:i:s')."', '', 'Twitter', '', '', '', '', '', '', 'Mensaje publicado en Twitter: ".$mensaje."','".$_SESSION["ced"]."')";
	mysql_query($bitacora_twitter,Conectarse());

	?>
	<!--Se redirecciona la pagina-->
	<meta http-equiv="Refresh" content="0; url=../../querys.php"/>
	<?php
}
?>