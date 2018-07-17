<?php
date_default_timezone_set('America/Bogota');
//Cambio Septiembre 2011
///////////////////////////////////////////////////////
//
//		Clase Aplicacion
//		Desarrollada por: Jhon Fredy García
//		Empresa: Algoritmo Software S.A
//		Fecha: 14 Junio 2005
//		Comentarios:
//			Clase instaciada al ingresar al sistema que contiene todas las demás clases del sistema
//			entre las principales tenemos: Base de Datos, Usuario, etc.
//		Cambios:
//			Autor:
//			Fecha:
//			Descripcion:
//
///////////////////////////////////////////////////////
include_once "con_app.php";
include_once "cbd.php";
include_once "cusuario.php";
//include_once "cradicado.php";
include_once "cinterfas.php";

class capp{

    var $bd				= null;		//Clase manejadora de base de datos
    var $usuario		= null;		//clase ususario
    var $nombre			= null;		//Nombre de la aplicacion
    var $html			= null;		// Html para retornar
	var $cnx_ppal		= 0;		//Indice de la conexion principal
	var $interfas 		= null;
	
	


	
    function capp(){
        //Instanciación de Clases
		$this->bd		   =& new cbd($this->cnx_ppal);
		$this->interfas	   =& new cinterfas();
		$this->usuario	   =& new cusuario();
		
		//Instanciacion de Variables
		$this->nombre = "Registro de Incidentes";		
		

    }

	    
    function get_nombre(){
		//Retornar el nombre de la aplicacion
        return $this->nombre;
    }
    
	function impresoras($seleccionar='')
	{
		
		$texto="";
		$sql="SELECT id,host,nombre_recurso FROM
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_impresion  
		order by id";
		$rs=$_SESSION[APL]->bd->getRs($sql);
		while(!$rs->EOF)
		{
			$texto.= "<option value='".$rs->fields[0]."'";
			if($rs->fields[0]==$seleccionar)
				$texto.=" selected"; 
			$texto.=">".$rs->fields[1]." ".$rs->fields[1]."</option>";
			$rs->MoveNext();
		}
		return $texto;
	}
	
    function cabeceras($ruta="",$desde_home = true){
		//Cabeceras de las paginas
        $dumy = '
        <html>
        <head>
			<title>'.$this->nombre.'</title>
			<meta http-equiv="X-UA-Compatible" content="IE=edge" />
			<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
			<meta name="generator" content="HAPedit 2.6">
			<link href="'.$ruta.'css/ventana.css" rel="stylesheet" type="text/css">	
			<link href="'.$ruta.'css/campo.css" rel="stylesheet" type="text/css">
			
			';
//				
		//if($desde_home){
		//	$dumy .= '<META HTTP-EQUIV="Refresh" CONTENT="9000; URL=entrada_usuario.php?msg=Se ha detectado inactividad por trienta minutos. Debe ingresar nuevamente">';
		//}
		$dumy .= '<link rel="shortcut icon" href="'.$ruta.'imagenes/logo.ico" type="image/x-icon">
			</head>
		<link type="text/css" rel="stylesheet" href="'.$ruta.'calendario/dhtmlgoodies_calendar.css?random='.date("Ymd").'" media="screen"></LINK>
		<SCRIPT type="text/javascript" src="'.$ruta.'calendario/dhtmlgoodies_calendar.js?random='.date("Ymd").'"></script>	
		<script type="text/javascript" src="'.$ruta.'clases/scroll_table.js"></script>
			
			
			
		
		<body onLoad=espere.style.display="none"  topmargin="0">
		

		<div id="espere" style="position:absolute; width:100%; height:100%; background-color:#FFFFFF; left: 0px; top: 0px;font-family:Verdana;filter:alpha(opacity=70); opacity:0.7">
	
		<center><h2 class="LegendSt" style="height:30">Un Momento por Favor...</h2>
		<br><img src="'.$ruta.'img/wait.gif"></center></div>
	

        ';
		
		//<LINK href="calendar/calendar.css" type=text/css rel=STYLESHEET>
		/*
		<SCRIPT language=JavaScript src="calendar/simplecalendar.js" type=text/javascript></SCRIPT>
		<SCRIPT language=JavaScript>
			new Calendar(new Date());
		</SCRIPT>
		*/
		
		return $dumy;
    }

	function token($html, $variable, $valor){
		//Remplazo de tags para plantillas
		$var = '¬'.$variable.'¬';
		$dumy = str_replace($var,$valor,$html);
		return ($dumy);
	}

	function get_plantilla($nombre_plantilla,$ruta=''){
		//Levantamiento de la plantilla
		$dumy = file_get_contents($ruta."plantillas/".$nombre_plantilla);
		return $dumy;
	}

	function esta_en_array($array, $valor){
		$cantidad = count($array);
		for($i = 0; $i < $cantidad ; $i++){
			if($array[$i] === $valor)
				return $i;
		}
		return -1;
	}

	function getSecuencia($tabla,$campo, $where="",$numero_conexion = -1){
		if($numero_conexion == -1)
			$numero_conexion = $this->bd->conexion_principal;

		$sql = "select count(*) from ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla." ".$where;
		$cant = $this->bd->dato($sql,$numero_conexion);
		if($cant==0)
			$maximo=1;
		else
		{
			$sql = "select max(".$campo.") from ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla." ".$where;
			$maximo = $this->bd->dato($sql,$numero_conexion);
			$maximo++;
		}

		return $maximo;
	}

	function formatearSecuencia($secuencia,$longitud){
		$largo = strlen($secuencia);
		$dumy = str_repeat("0", $longitud - $largo).$secuencia;
		return $dumy;
	}
	
	function msg($msg){
		$dumy = '';
		if($msg != ''){
			$dumy .= "
			<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			alert('".$msg."');
			//-->
			</SCRIPT>
			";			
		}				
		return $dumy;		
	}
	
	function getButtom($raiz,$value,$size,$events,$id="",$clas='middle')
	{
		$htm = '<table  border="0" cellpadding="0" cellspacing="0">
				  <tr>
					<td><img src="'.$raiz.'/img/left.png" /></td>
					<td width="'.$size.'" class="'.$clas.'" align="center"><input id="'.$id.'" type="button" size="'.($size-8).'" class="'.$clas.'" value="'.$value.'"  '.$events.'></td>
					<td><img src="'.$raiz.'/img/right.png" /></td>
				  </tr>
				</table>';		
		return $htm;
	}
	
	function getButtom2($raiz,$value,$size,$events,$title="",$id="",$clas='middle')
	{
		$htm = '<table  border="0" cellpadding="0" cellspacing="0">
				  <tr>
					<td><img src="'.$raiz.'/img/left.png" /></td>
					<td width="'.$size.'" class="'.$clas.'" align="center"><input id="'.$id.'" type="button" size="'.($size-8).'" class="'.$clas.'" value="'.$value.'" '.$events.' title="'.$title.'" alt="'.$title.'"></td>
					<td><img src="'.$raiz.'/img/right.png" /></td>
				  </tr>
				</table>';		
		return $htm;
	}
	
	function fecha_a_slas($fecha){
	   if($fecha == "")
	   	return "";
	   $partes = explode('-', $fecha);
	   $dia = $partes[2];
	   $mes = $partes[1];
	   $ano = $partes[0];	   
	   return ($dia."/".$mes."/".$ano);
	}
	
	function quitar_hora($fecha)
	{
		$partes=explode(" ",$fecha);
		return $partes[0];
	}
	function fecha_a_mes($fecha){
	   if($fecha == "")
	   	return "";
	   $partes = explode('-', $fecha);
	   $dia = $partes[0];
	   $mes = $partes[1];
	   $ano = $partes[2];	   
	   return ($ano."-".$mes."-".$dia);
	} 
	
	function abrir_archivo($cod) 
				{ 
					$fp = fopen($cod, "w");  
					fputs($fp, ""); 
					fclose($fp); 
				} 
	function grabar_linea($linea,$cod) 
	{ 
		$fp = fopen($cod, "a+"); 
		fputs($fp, $linea."\r\n"); 
		fclose($fp); 
	} 
	 
	function subirArchivo($destDir,$pre,$fieldName,$maxFileSize = false)
	{
	   	if(!isset($_FILES[$fieldName]) ||!isset($_FILES)||!is_array($_FILES[$fieldName]) ||!$_FILES[$fieldName]['name']){
    		return array(false,'Error en el formulario de carga de archivos adjuntos');
		}
 		$file = $_FILES[$fieldName];
		if (!isset($file['type']))      $file['type']      = '';
		if (!isset($file['size']))      $file['size']      = '';
   		if (!isset($file['tmp_name']))  $file['tmp_name']  = '';
   		$file['name'] = preg_replace(
             '/[^a-zA-Z0-9\.\$\%\'\`\-\@\{\}\~\!\#\(\)\&\_\^]/'
             ,'',str_replace(array(' ','%20'),array('_','_'),$file['name']));

 		$file['name']= str_replace("'","",$file['name']);	   
   		if($maxFileSize && ($file['size'] > $maxFileSize))
	      	return array(false,'El archivo adjunto "'.$file['name'].'" es demasiado grande');
		if($file['size']==0)
			return array(false,'El archivo adjunto "'.$file['name'].'" es inválido');
			
		$nombre2 = $pre."_".$file['name'];

  		if(file_exists($destDir.$nombre2)){
			return array(false," ya hay almacenado un archivo con ese nombre");
		}
  
   		if(!move_uploaded_file($file['tmp_name'], $destDir.$nombre2))
       		return array(false,'El archivo "'.$file['name'].'" no pudo ser guardado en "'.$destDir.'". No se tienen permisos suficientes');
   		else
       		return array(true,$nombre2);
	} 
	function eliminarArchivo($ruta,$archivo)
	{
		if(!file_exists($ruta.$archivo))
			return array(false,'No Existe el Archivo Temporal '.$archivo);
		elseif(unlink($ruta.$archivo))
			return array(true,'');
		else
			return array(false,'No se pudo Eliminar el Archivo Temporal '.$archivo );
	}
	function moverArchivo($rutaoriginal,$nombreoriginal,$rutanueva,$nombrenuevo)
	{
		if(!file_exists($rutaoriginal.$nombreoriginal))
			return array(false,'No Existe el Archivo Temporal '.$nombreoriginal);
		elseif(rename($rutaoriginal.$nombreoriginal,$rutanueva.$nombrenuevo))
			return array(true,'');
		else
			return array(false,'No se pudo mover el Archivo Temporal '.$nombreoriginal);
	}
	
	function verificar_cadena($p_cadena){
		$p_cadena = str_replace('"','',$p_cadena);
		$p_cadena = str_replace("'","",$p_cadena);
		$p_cadena = str_replace("<","",$p_cadena);
		$p_cadena = str_replace(">","",$p_cadena);
		$p_cadena = str_replace(" insert ","",$p_cadena);
		$p_cadena = str_replace(" select ","",$p_cadena);
		$p_cadena = str_replace(" delete ","",$p_cadena);
		$p_cadena = str_replace(" update ","",$p_cadena);
		$p_cadena = str_replace(" and ","",$p_cadena);
		$p_cadena = str_replace(" or ","",$p_cadena);
		
		return $p_cadena;
		
	}
	
	function cortar($texto,$tamano){
		$dumy = substr($texto,0,$tamano);
		for($i=strlen($texto);$i < $tamano; $i++)
			$dumy .= "&nbsp;";
		return $dumy;
	}
	
	function getDiaSemana($pFecha,$pFormato=1)
	{
		if($pFecha=="")
			return "";
		
		$arrDia = array();
		$arrDia[0] = "Domingo";
		$arrDia[1] = "Lunes";
		$arrDia[2] = "Martes";
		$arrDia[3] = "Miercoles";
		$arrDia[4] = "Jueves";
		$arrDia[5] = "Viernes";
		$arrDia[6] = "Sabado";
		
		$arrFec = explode('-', $pFecha);
		$dia = date("w",mktime(0, 0, 0, $arrFec[1], $arrFec[0], $arrFec[2]));
		if( $pFormato==2 )
			$dia = date("w",mktime(0, 0, 0, $arrFec[1], $arrFec[2], $arrFec[0]));
		
		return $arrDia[$dia];
	}
	
	function getComboHora($pIdCmp,$pDisa,$pPorDef)
	{
		if( $pPorDef=="" )
			$pPorDef = "";
		else if( strtoupper($pPorDef)=="S/N" )
			$pPorDef = "";
		else
			$pPorDef = str_pad($pPorDef, 2, "0", STR_PAD_LEFT);
		
		$opts = '<option value="">--</option>';
		for($i=0; $i<=23; $i++)
		{
			$hora = str_pad($i, 2, "0", STR_PAD_LEFT);
			$selected = '';
			if( $pPorDef==$hora )
				$selected = 'selected';
			
			$opts .= '<option value="'.$hora.'" '.$selected.'>'.$hora.'</option>';
		}
		
		return '<select id="'.$pIdCmp.'" name="'.$pIdCmp.'" class="campos cmpPeq2" '.$pDisa.'>'.$opts.'</select>';
	}
	
	function getComboMinu($pIdCmp,$pDisa,$pPorDef)
	{
		if( $pPorDef=="" )
			$pPorDef = "";
		else if( strtoupper($pPorDef)=="S/N" )
			$pPorDef = "";
		else
			$pPorDef = str_pad($pPorDef, 2, "0", STR_PAD_LEFT);
		
		$opts = '<option value="">--</option>';
		for($i=0; $i<=59; $i++)
		{
			$hora = str_pad($i, 2, "0", STR_PAD_LEFT);
			$selected = '';
			if( $pPorDef==$hora )
				$selected = 'selected';
			
			$opts .= '<option value="'.$hora.'" '.$selected.'>'.$hora.'</option>';
		}
		
		return '<select id="'.$pIdCmp.'" name="'.$pIdCmp.'" class="campos cmpPeq2" '.$pDisa.'>'.$opts.'</select>';
	}
	
	function restarHoras($pHorIni,$pHorFin)
	{
		$posI = strpos(strtoupper($pHorIni), "S");
		$posF = strpos(strtoupper($pHorFin), "S");
		
		if( $posI!==false or $posF!==false or trim($pHorIni)=="" or trim($pHorFin)=="" )
			return "";
		
		$horMen = intval(substr($pHorIni, 0, 2));
		$horMay = intval(substr($pHorFin, 0, 2));

		$difHor = 0;
		
		// Si la hora mayor se pasa de las 23:59, toca restar las horas para poder obtener las horas debidas
		if( $horMay>=24 )
		{
			$difHor = $horMay - 23;
			$pHorIni = str_pad(($horMen-$difHor), 2, "0", STR_PAD_LEFT).':'.substr($pHorIni,-2);
			$pHorFin = str_pad(($horMay-$difHor), 2, "0", STR_PAD_LEFT).':'.substr($pHorFin,-2);
		}
		
		return (date("H:i", strtotime("00:00") + strtotime($pHorFin) - strtotime($pHorIni) ));
	}
	
	function convertirHorasEnMinutos($pHorMin)
	{
		if( trim($pHorMin)=="" )
			return 0;
		
		// Obtener las horas
		$vHor = intval(substr($pHorMin, 0, 2));
		
		// Obtener los Minutos
		$vMin = intval(substr($pHorMin, 3, 2));
		
		// Convertir las horas en minutos
		$vTotMinXHor = $vHor*60;
		
		return ($vTotMinXHor + $vMin);// Retorna la suma de los minutos de las horas mas los minutos
	}
	
	function convertirMinutosEnHoras($pMin)
	{
		if( $pMin=="" or $pMin==0 )
			return "00:00";
		
		// convertir los minutos en horas
		$vHor = floor($pMin/60);
		
		// Obtener el restante de minutos
		$vMin = $pMin%60;
		
		return str_pad($vHor, 2, "0", STR_PAD_LEFT).':'.str_pad($vMin, 2, "0", STR_PAD_LEFT);
	}

	function obtenerVersion()
	{
		foreach(array_reverse(glob('.git/refs/tags/*')) as $archivo) {
	        $contents = file_get_contents($archivo);

	        return basename($archivo);
	        exit();
	    }
    }
}
?>