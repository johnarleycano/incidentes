<?php
include_once("../clases/capp.php");
include_once("../libs/php/JSON.php");
session_start();

$json = new Services_JSON();

$page = 0;
if( isset($_POST['page']) ) 	$page = $_POST['page'];
else if( isset($_GET['page']) )	$page = $_GET['page'];

$limit = 100;
if( isset($_POST['rows']) )
	$limit = $_POST['rows'];

$sidx = "codigo";
if( isset($_POST['sidx']) )
	$sidx = $_POST['sidx'];

$sord = "desc";
if( isset($_POST['sord']) )
	$sord = $_POST['sord'];

// Parametros de filtros
$finalizado_v=0;
if( isset($_POST['finalizado']) and $_POST['finalizado']!="" )
	$finalizado_v=$_POST['finalizado'];

// Ordenamiento
$order = 1;
if( $sidx!="" )
	$order = "$sidx $sord";

$where = "";
if( isset($_POST['login']) )
	$where = " and upper(login) like upper('%".$_POST['login']."%') ";
if( isset($_POST['fecha_creacion']) )
	$where = " and fecha_creacion like '".$_POST['fecha_creacion']."%' ";
if( isset($_POST['cedula']) )
	$where = " and upper(cedula) like upper('%".$_POST['cedula']."%') ";
if( isset($_POST['nombres']) )
	$where = " and upper(nombres) like upper('%".$_POST['nombres']."%') ";
if( isset($_POST['apellidos']) )
	$where = " and upper(apellidos) like upper('%".$_POST['apellidos']."%') ";
if( isset($_POST['correo']) )
	$where = " and upper(correo) like upper('%".$_POST['correo']."%') ";
if( isset($_POST['celular']) )
	$where = " and upper(celular) like upper('%".$_POST['celular']."%') ";
if( isset($_POST['estado']) and $_POST['estado']!="" )
	$where = " and estado='".$_POST['estado']."' ";
if( isset($_POST['nomPer']) and $_POST['nomPer']!="" )
	$where = " and id_perfil=".$_POST['nomPer']."";
if( isset($_POST['enviar_correo']) and $_POST['enviar_correo']!="" )
	$where = " and enviar_correo='".$_POST['enviar_correo']."' ";

// Consulta para traer el numero de registros
$sql = "SELECT count(*)
		FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
		WHERE id=id $where";
$rs = $_SESSION[APL]->bd->getRs($sql);
$count = $rs->fields[0];

if( $count>0 ) 
	$totPag = ceil($count/$limit); 
else 
	$totPag = 0; 

if ($page > $totPag) 
	$page = $totPag;
	
if( $page==0 )
	$page = 1;

$start = $limit*$page-$limit; // do not put $limit*($page - 1) 

$responce->page = $page; 
$responce->total = $totPag; 
$responce->records = $count;

$sql = "SELECT id,login,estado,id_perfil,celular,nombres,apellidos,clave,fecha_creacion,correo,firma,cedula,enviar_correo,
			CASE id_perfil
				WHEN 0 THEN 'Administrador'
				WHEN 1 THEN 'Generador Basico Incidentes'
				WHEN 2 THEN 'Funcionario SOS'
				WHEN 3 THEN 'Admninistrador Vial'
				ELSE 'NA'				
			END nomPer
		FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
		WHERE id=id $where
		ORDER BY $order
		LIMIT $start , $limit";
$rs = $_SESSION[APL]->bd->getRs($sql);

$i=0;
while( !$rs->EOF )
{
	$id  = $rs->fields[0];
	$log = $rs->fields[1];
	$est = $rs->fields[2];
	$per = $rs->fields[13];
	$cel = $rs->fields[4];
	$nom = utf8_encode($rs->fields[5]);
	$ape = utf8_encode($rs->fields[6]);
	$cla = $rs->fields[7];
	$fec = $rs->fields[8];
	$cor = utf8_encode($rs->fields[9]);
	$fir = $rs->fields[10];
	$ced = $rs->fields[11];
	$env = $rs->fields[12];
	
	$desEst = 'Activo';
	if( $est=="I" )
		$desEst = 'Inactivo';
	
	$imgSinFir = 'Sin Firma';
	if( $fir!="" )
		$imgSinFir = '<img src="../img/blockbuttom.png" onclick="abrirFirma(\'../firmas/'.$fir.'\')" style="cursor:pointer"/>';
	
	$ver = '<input type="button" class="vbotones" value="Ver" onClick="Editar('.$id.')">';
	
	$responce->rows[$i]['id'] = $i;
	$responce->rows[$i]['cell']=array($id,$log,$fec,$ced,$nom,$ape,$cor,$cel,$desEst,$per,$imgSinFir,$env,$ver); 
	$i++;
	$rs->MoveNext();
}

echo json_encode($responce);

?>