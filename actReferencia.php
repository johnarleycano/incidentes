<?php
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}

echo $_SESSION[APL]->cabeceras();

$sql = "SELECT id, abscisa
		from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia";
$rs=$_SESSION[APL]->bd->getRs($sql);

$arrLen = array();
while (!$rs->EOF)
{
	$id =  $rs->fields[0];
	$abs = trim($rs->fields[1]);
	$lon = strlen($abs);
	
	if( $lon>8 )
		$arrLen[$id] = $abs;
	
	$posMas = strpos($abs, "+");
	
	if( $posMas!==false )
	{
		$priKm = str_pad(trim(substr($abs,1,$posMas-1)),3,"0",STR_PAD_LEFT);
		$segKm = str_pad(trim(substr($abs,$posMas+1)),3,"0",STR_PAD_LEFT);
		
		$nueAbs = "K".$priKm."+".$segKm;
		
		$sql = "update ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia
				set abscisa='".$nueAbs."'
				where id=".$id;
		$_SESSION[APL]->bd->ejecutar($sql);
		echo $sql."<br/>";
	}
	
	$rs->MoveNext();
}

print_r($arrLen);

?>