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

// Consulta
$where = '';
if($_SESSION[APL]->usuario->id_perfil!=0 && $_SESSION[APL]->usuario->id_perfil!=3)
	$where = "AND i.id_usuario='".$_SESSION[APL]->usuario->id."'";

// filtros
if( isset($_POST['codigo']) )
	$where .= " AND concat(i.periodo,'.',LPAD(i.codigo,5,'0')) like '%".$_POST['codigo']."%' ";

if( isset($_POST['fecha']) )
	$where .= " AND i.fecha like '%".$_POST['fecha']."%' ";

if( isset($_POST['via']) )
	$where .= " AND upper(v.nombre) like upper('%".$_POST['via']."%') ";

if( isset($_POST['referencia']) )
	$where .= " AND upper(r.referencia) like upper('%".$_POST['referencia']."%') ";

if( isset($_POST['tipoaten']) )
	$where .= " AND upper(ta.nombre) like upper('%".$_POST['tipoaten']."%') ";

if( isset($_POST['usuario']) )
	$where .= " AND concat(u.nombres,' ',u.apellidos) like upper('%".$_POST['usuario']."%') ";

// Ordenamiento
$order = "i.periodo desc, i.codigo desc";
if( $sidx!="" )
{
	if( $sidx=="codigo" )
		$order = "i.periodo $sord, i.codigo $sord";
	else if( $sidx=="fecha" )
		$order = "i.fecha $sord";
	else if( $sidx=="via" )
		$order = "v.nombre $sord";
	else if( $sidx=="referencia" )
		$order = "r.referencia $sord";
	else if( $sidx=="tipoaten" )
		$order = "ta.nombre $sord";
	else if( $sidx=="usuario" )
		$order = "12 $sord, 13 $sord";
}	

// Consulta para traer el numero de registros
$_SESSION[APL]->bd->ejecutar("SET NAMES utf8");
$sql = "SELECT count(*)
		FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via as v,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia  as r,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion as ta,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u
		WHERE i.finalizado_adm_vial=".$finalizado_v." AND v.id=i.via AND r.id=i.referencia AND ta.id=i.tipo_atencion AND u.id=i.id_usuario $where";
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

$sql = "SELECT i.id,i.periodo,i.codigo,i.guardado_sos,i.finalizado_sos,i.guardado_adm_vial,i.finalizado_adm_vial,
			v.nombre,r.referencia,ta.nombre,i.fecha,u.nombres,u.apellidos,i.fechaincidente
		FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via as v,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia  as r,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion as ta,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u
		WHERE i.finalizado_adm_vial=".$finalizado_v." AND v.id=i.via AND r.id=i.referencia AND ta.id=i.tipo_atencion AND u.id=i.id_usuario
			$where
		ORDER BY $order
		LIMIT $start , $limit";
$rs = $_SESSION[APL]->bd->getRs($sql);

$i=0;
while( !$rs->EOF )
{
	$id  = $rs->fields[0];
	$cod = $rs->fields[1].".".str_pad($rs->fields[2],5,"0",STR_PAD_LEFT);
	$fec = $rs->fields[10];
	$fecInc = $rs->fields[13];
	$via = $rs->fields[7];
	$ref = $rs->fields[8];
	$tip = $rs->fields[9];
	$usu = $rs->fields[11]." ".$rs->fields[12];
	$est = '<img src="../img/verde.png" title="Registro Inicial Completo"/>&nbsp;&nbsp;';
	$ver = '<input type="button" class="vbotones" value="Ver" onClick="Editar('.$id.')">';
	$des = '<img src="../img/popup.png"style="cursor:pointer" title="REPORTE ACCIDENTE" alt="REPORTE ACCIDENTE" onclick="window.open(\'../reporte_1.php?id_buscar='.$id.'\',\'_blank\')" />'.
		   '<img src="../img/popup.png"style="cursor:pointer" title="INFORME ADMINISTRADOR VIAL DE  EVENTUALIDADES SOBRE LA VIA" alt="INFORME ADMINISTRADOR VIAL DE  EVENTUALIDADES SOBRE LA VIA" onclick="window.open(\'../reporte_2.php?id_buscar='.$id.'\',\'_blank\')" />';
	
	// Si finalizado_sos es igual a 1
	if( $rs->fields[4]==1 )
		$est .= '<img src="../img/verde.png" title="Finalizado por SOS"/>';
	else if($rs->fields[3]==1)
		$est .= '<img src="../img/amarillo.png" title="Guardado por SOS"/>';
	else
		$est .= '<img src="../img/gris.png" title="Pendiente por SOS"/>';
	
	$est .= '&nbsp;&nbsp;';
	
	if($rs->fields[6]==1)
		$est .= '<img src="../img/verde.png" title="Finalizado por Adm Vial"/>';
	else if($rs->fields[5]==1)
		$est .= '<img src="../img/amarillo.png" title="Guardado por Adm Vial"/>';
	else
		$est .= '<img src="../img/gris.png" title="Pendiente por Adm Vial"/>';

	// si la fecha del incidente es vacia mostrar la fecha de creacion
	if( $fecInc=="" )
		$fecInc = $fec;

	$responce->rows[$i]['id'] = $i;
	$responce->rows[$i]['cell']=array($id,$cod,$fecInc,$via,$ref,$tip,$usu,$est,$ver,$des);
	$i++;
	$rs->MoveNext();
}

echo json_encode($responce);

?>