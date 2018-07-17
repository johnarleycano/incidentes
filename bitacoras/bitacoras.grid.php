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

$where = '';
if($_SESSION[APL]->usuario->id_perfil!=0 && $_SESSION[APL]->usuario->id_perfil!=2)
	$where = "AND i.id_usuario='".$_SESSION[APL]->usuario->id."'";

// filtros
if( isset($_POST['fecha']) )
	$where .= " AND b.fecha like '%".$_POST['fecha']."%' ";

if( isset($_POST['hora']) )
	$where .= " AND b.hora like '%".$_POST['hora']."%' ";

if( isset($_POST['heridos']) )
	$where .= " AND b.heridos like '%".$_POST['heridos']."%' ";

if( isset($_POST['info_por']) )
	$where .= " AND i.nombre like '%".$_POST['info_por']."%' ";

if( isset($_POST['motivo']) )
	$where .= " AND b.motivo like '%".$_POST['motivo']."%' ";

if( isset($_POST['ubicacion']))
	$where .= " AND v.nombre like '%".$_POST['ubicacion']."%' ";

if( isset($_POST['anotaciones']) )
	$where .= " AND b.anotaciones like '%".$_POST['anotaciones']."%' ";

if( isset($_POST['usuario']) )
	$where .= " AND concat(u.nombres,' ',u.apellidos) like upper('%".$_POST['usuario']."%') ";

// Consulta para traer el numero de registros
$_SESSION[APL]->bd->ejecutar("SET NAMES utf8");
$sql = " SELECT b.id, b.fecha, b.hora, b.heridos, u.nombres, u.apellidos, i.nombre, v.nombre,  b.anotaciones, b.motivo
		FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_bitacoras as b,
			 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado as i,
			 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via as v,
			 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u
		WHERE  i.id=b.info_por AND v.id=b.ubicacion AND u.id=b.id_usuario $where";
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

$order = "b.fecha desc";

$sql = "SELECT b.id, b.fecha, b.hora, b.heridos, u.nombres, u.apellidos, i.nombre, v.nombre,b.anotaciones,b.motivo
		FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_bitacoras as b,
			 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado as i,
			 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via as v,
			 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u
		WHERE  i.id=b.info_por AND v.id=b.ubicacion AND u.id=b.id_usuario
				$where
		ORDER BY $order
		LIMIT $start , $limit";
$rs = $_SESSION[APL]->bd->getRs($sql);
//print_r($sql);
//print_r($rs);
$i=0;
while( !$rs->EOF )
{
	$id  = $rs->fields[0];
	$fec = $rs->fields[1];
	$hora = $rs->fields[2];
	$her  = $rs->fields[3];
	$usu = $rs->fields[4]." ".$rs->fields[5];
	$info = $rs->fields[6];
	$via = $rs->fields[7];
	$anota = $rs->fields[8];
	$mot = $rs->fields[9];
	


	$responce->rows[$i]['id'] = $i;
	$responce->rows[$i]['cell']=array($id,$fec,$hora,$her,$info,$mot,$via,$anota,$usu);
	$i++;
	$rs->MoveNext();
}

echo json_encode($responce);

print_r($response);
?>