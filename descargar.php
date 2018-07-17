<?php

	//Este archivo es para forzar la descarga de los archivos adjuntos y evitar que se abran
	//en el navegador. (Tomado de la ventana virtual)
	$men="";
	if(!isset($_GET['adjunto'])){
		$men='No llego el Adjunto';
	}
	else{
		$filename=$_GET['adjunto'];
		//"Limpiamos" el nombre para no "confundir" al usuario
		$nombre_limpio=$_GET['adjunto'];
		//$nombre_limpio=substr($nombre_limpio,(strlen($proyecto->getProyecto_numero())+strlen($_GET['adjunto'])+2),strlen($nombre_limpio));
		if(!$_GET['adjunto'] || !file_exists($filename)){ 
			$men="Error, el archivo '$filename' no existe en el sistema";
		}
		else{
			$file_extension = substr( $filename,-3 );
			switch($file_extension){
			  case "pdf": $ctype="application/pdf"; break;
			  case "exe": $ctype="application/octet-stream"; break;
			  case "zip": $ctype="application/zip"; break;
			  case "doc": $ctype="application/msword"; break;
			  case "xls": $ctype="application/vnd.ms-excel"; break;
			  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			  case "gif": $ctype="image/gif"; break;
			  case "png": $ctype="image/png"; break;
			  case "jpg": $ctype="image/jpg"; break;
			  default: $ctype="application/force-download";
			}
		
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public"); 
			header("Content-Type: $ctype");
			$user_agent = strtolower ($_SERVER["HTTP_USER_AGENT"]);
			
			/*if ((is_integer (strpos($user_agent, "msie"))) && (is_integer (strpos($user_agent, "win")))) 
			{
			  header( "Content-Disposition: filename=".basename($filename).";" );
			} 
			else{*/
				 header( "Content-Disposition: attachment; filename=".basename($nombre_limpio).";" );
			//}
			
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($filename));
			readfile("$filename");
			exit();
		}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Title</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="estilos.css" type="text/css"/>
</head> 
<body topmargin="0" leftmargin="0" class="back">
<table width="100%" height="100%" cellpadding="0" cellspacing="0">
<tr>
<td height="110" align="center"><img src="imagenes/seguridad.gif"><br>
	<h1>Error al descargar archivo adjunto</h1></td>
</tr>
<tr>
<td align="center"  height="100%"><?php echo $men?></td></tr>
<tr><td height="100%">&nbsp;</td></tr>
</table>
</body>