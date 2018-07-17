<?php
//Cambio Septiembre 2011
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}


if($_GET["tipo"]=='RA')
{
	$campo="nombre_documento";
	$tabla="".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado";
	$sql="select d.nombre_documento, adjunto , OCTET_LENGTH(adjunto)
	from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_adjunto_radicado as a,
	 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_documento_radicado as d
	where 
	a.id_radicado=d.id_radicado AND
	a.posicion=d.posicion AND
	a.tipo=d.tipo AND
	a.id_radicado = ".$_GET["id_adjunto"]." AND
	a.posicion=".$_GET["pos"]." AND 
	a.tipo='".$_GET["tipo"]."'";
}
else
if($_GET["tipo"]=='RE' || $_GET["tipo"]=='TE')
{
	if($_GET["tipo"]=='RE')
	{
		$campo="archivo_respuesta";
		$tabla="".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado";
	}
	else
	{
		$campo="documento";
		$tabla="".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado_tecnico";
	}
		
	$sql="select ".$campo.", adjunto , OCTET_LENGTH(adjunto)
	from ".$tabla." as r,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_adjunto_radicado as a 
	where 
	a.id_radicado=id and
	id = ".$_GET["id_adjunto"]." AND
	a.posicion=".$_GET["pos"]." AND 
	a.tipo='".$_GET["tipo"]."'";

}






$Rs = $_SESSION[APL]->bd->getRs($sql);
if($Rs->RecordCount() == 0){
	?>
	<script language="javascript">
	alert('Error al descargar');
	window.close();
</script>
	<?php	
}
else{

$nombrecompleto=split(".",$Rs->fields[0]);
$filesize=$Rs->fields[2];
$extension=$nombrecompleto[1];
switch( $extension ) 
{
     case "xls": $ctype="application/vnd.ms-excel"; 
	 break;
     default: $ctype="application/force-download";
 }

header("Cache-control: private");
header("Content-type: ".$ctype);
header("Content-Length: ".$filesize);
header("Content-Disposition: attachment; filename=".$Rs->fields[0]);
header("Pragma: no-cache");
header("Expires: 0");
print $Rs->fields[1];
exit;
}
?>