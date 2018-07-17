<?php

$_SESSION['host']="localhost";
$_SESSION['user']="root";
$_SESSION['pass']="";
$_SESSION['bname']="incidentes";
function conectarse ($sql)
{
  include("adodb/adodb.inc.php");
  $db = &ADONewConnection("mysql");
  $db->Connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['bname']);
  $rs = $db->Execute($sql);
  if($rs===false)
		print 'ERROR Consultando: '.$db->ErrorMsg().'<BR>'.$sql.'<br>';
  //$rs->Close(); # optional
  $db->Close(); # optional
  return $rs;
}

function conectarse_bind ($sql,$arreglo)
{
  include("adodb/adodb.inc.php");
  $db = &ADONewConnection("mysql");
  $db->Connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['bname']);
  $rs = $db->Execute($sql,$arreglo);
  if($rs===false)
		print 'ERROR Consultando: '.$db->ErrorMsg().'<BR>'.$sql.'<br>';
  //$rs->Close(); # optional
  $db->Close(); # optional
  return $rs;
}
function actualizar ($sql,$parametros)
{
  include("adodb/adodb.inc.php");
  $db = &ADONewConnection("mysql");
  $db->Connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['bname']);
  if($db->Execute($sql,$parametros)===false)
  {
  	print 'Error Actualizando: '.$db->ErrorMsg().'<BR>'.$sql.'<br>';
	return false;
  }
  else
	return true; 
	$db->Close(); # optional
}

function preparar($variable)
{
   if (!get_magic_quotes_gpc()) 
      return addslashes($variable);
   else
      return $variable;
}

function mostrarfecha($fecha)
{
	$fecha2=split("-",$fecha);
	if(count($fecha2)==3)
	$fecharetornar=str_pad($fecha2[2],2,'0',STR_PAD_LEFT)."-".str_pad($fecha2[1],2,'0',STR_PAD_LEFT)."-".str_pad($fecha2[0],2,'0',STR_PAD_LEFT);
	else
	$fecharetornar=$fecha;
	return str_replace(chr(32),'',$fecharetornar);
}

function quitarsaltolinea($texto)
{
  $texto2=str_replace(chr(13),'',$texto);
  $texto2=str_replace(chr(10),'',$texto2);
  $texto2=strtoupper($texto2);
  return $texto2;
}


?>
