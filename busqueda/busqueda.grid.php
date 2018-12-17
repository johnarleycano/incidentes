<?php
ini_set("memory_limit","256M");
set_time_limit(0);

include_once("../clases/capp.php");
include_once("../libs/php/JSON.php");
session_start();

if( !isset($_POST["buscar"]) )
	exit;

if( isset($_POST["buscar"]) and $_POST["buscar"]=="NO" )
	exit;

$json = new Services_JSON();
$completo = $_POST['completo'];

$select = "
	i.id,
	i.codigo,
	i.periodo,
	i.fechaincidente,
	i.horaincidente,
	i.hora_llegada,
	i.tiempo,
	case when i.abscisa_real!='' then i.abscisa_real else r.abscisa end abscisa,
	r.referencia referencia,
	v.nombre via,
	inf.nombre nomInf,
	ta.nombre tipo_atencion,
	i.nro_muertos,
	i.nro_heridos,
	i.hora_llegada_sitio,
	i.hora_salida_base,
	i.hora_llegada_base,
	inf.nombre,
	i.observaciones,
	sv.descripcion dessenvia,
	i.fecha, 
	case when i.pesv = 0 then 'NO' else 'SI' end pesv";
$group = "group by i.id,i.codigo,i.periodo,i.fecha,i.horaincidente,i.hora_llegada,i.tiempo,r.abscisa,r.referencia,
			v.nombre,inf.nombre,ta.nombre,i.nro_muertos,i.nro_heridos,i.hora_llegada_sitio,i.hora_salida_base,
			i.hora_llegada_base,inf.nombre,i.observaciones,sv.descripcion,i.fecha";

if( $_POST['completo']==1 )
{
	$select =
		"i.id,
		i.codigo,
		i.periodo,
		i.fechaincidente,
		horaincidente,
		i.hora_llegada,
		i.tiempo,
		case when i.abscisa_real!='' then i.abscisa_real else r.abscisa end abscisa,
		r.referencia referencia,
		v.nombre via, 
		i.informado_por,
		ta.nombre tipo_atencion, 
		i.nro_muertos, 
		i.nro_heridos, 
		tv.nombre vehiculo_involucrado, 
		vi.placa_vehiculo,  
		lv.nombre nomLv, 
		lv.cedula, 
		par.nombre nomPar, 
		tra.nombre nomTra, 
		tal.nombre nomTal,
		otv.nombre nomOtv, 
		hos.nombre nomHos, 
		csa.nombre nomCsa, 
		cli.nombre nomCli, 
		otl.nombre nomCtl, 
		lv.observaciones,
		i.hora_llegada_sitio, 
		i.hora_salida_base,
		i.hora_llegada_base, 
		inf.nombre nomInf, 
		amb.nombre nomAmb, 
		gr.nombre nomGr, 
		lv.edad edadlv, 
		lv.conducia, 
		lv.lesionado, 
		lv.muerto, 
		lv.tipo_lesion,
		sv.descripcion dessenvia,
		i.fecha,
		i.condiciones_climaticas,
		vi.cilindraje_vehiculo, 
		case when i.pesv = 0 then 'NO' else 'SI' end pesv";
	$group = "";
}

$from = $_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente as vi on (vi.id_incidente=i.id) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado as inf on  (inf.id=i.informado_por) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_parqueadero as par on (par.id=vi.id_parqueadero) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_transito as tra on (tra.id=vi.id_transito) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_taller as tal on (tal.id=vi.id_taller) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_vehiculo as otv on (otv.id=vi.id_otro_vehiculo) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado  as tv on (tv.id=vi.id_tipo_vehiculo) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_grua  as gr on (gr.id=vi.id_grua) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo as lv on (vi.id_vehiculo=lv.id_vehiculo) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_hospital as hos on (hos.id =lv.id_hospital) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_centro_salud as csa on  (csa.id=lv.id_centro_salud) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_clinica as cli on (cli.id=lv.id_clinica) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia as amb on (amb.id=lv.id_trasladado_por) left outer join ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_lesionado as otl on (otl.id=lv.id_otro_lesionado) left outer join ".
		
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_sentido as sv on (sv.id=i.sentido_via), ".
		
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia as r, ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion as ta, ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_via v, ".
		$_SESSION[APL]->bd->nombre_bd[0].".dvm_estado as e";

$where = " r.id=i.referencia and ta.id=i.tipo_atencion and v.id=i.via and e.id=i.estado ";

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

// Armar Where
if( isset($_POST['tipo_atencion']) && $_POST['tipo_atencion']!='' )
	$where .= " and i.tipo_atencion=".$_POST['tipo_atencion']." ";

if( isset($_POST['caracteristicas']) && $_POST['caracteristicas']!='' )
{
	if( $_POST['estado_sitio']!='' )
	{
		if( $_POST['caracteristicas']=='H' )
			$where .= " and señalizacion_horizontal='".$_POST['estado_sitio']."' ";
		else if($_POST['caracteristicas']=='V')
			$where .= " and señalizacion_vertical='".$_POST['estado_sitio']."' ";
		else if($_POST['caracteristicas']=='G')
			$where .= " and mantenimiento_gral='".$_POST['estado_sitio']."' ";
		else if($_POST['caracteristicas']=='R')
			$where .= " and rodadura='".$_POST['estado_sitio']."' ";
	}
}

if(isset($_POST['estado_sos']) && $_POST['estado_sos']!='')
{
	if($_POST['estado_sos']=='P')
		$where .= " and guardado_sos=0 and finalizado_sos=0";
	else if($_POST['estado_sos']=='G')
		$where .= " and guardado_sos=1 ";
	else
		$where .= " and finalizado_sos=1 ";
}

if(isset($_POST['estado_adm_vial']) && $_POST['estado_adm_vial']!='')
{
	if($_POST['estado_adm_vial']=='P')
		$where .= " and guardado_adm_vial=0 and  finalizado_adm_vial=0";
	else if($_POST['estado_adm_vial']=='G')
		$where .= " and guardado_adm_vial=1 ";
	else
		$where .= " and finalizado_adm_vial=1 ";
}

if(isset($_POST['autoridad']) && $_POST['autoridad']!='')
{
	if($_POST['autoridad']=='P')
		$where.=" and policia='SI' ";
	if($_POST['autoridad']=='T')
		$where.=" and transito='SI' ";
	else
		$where.=" and inspector='SI' ";
}

if( isset($_POST['placa_a']) && $_POST['placa_a']!='' )
	$where .= " and (upper(policia_placa)='".strtoupper($_POST['placa_a'])."' or upper(transito_placa)='".strtoupper($_POST['placa_a'])."' or upper(inspector_placa)='".strtoupper($_POST['placa_a'])."')";

if( isset($_POST['apellido_a']) && $_POST['apellido_a']!='' )
	$where .= " and (upper(policia_apellido)='".strtoupper($_POST['apellido_a'])."' or upper(transito_apellido)='".strtoupper($_POST['apellido_a'])."' or upper(inspector_apellido)='".strtoupper($_POST['apellido_a'])."')";

if( isset($_POST['id_tipo_vehiculo']) && $_POST['id_tipo_vehiculo']!='' )
	$where .= " and vi.id_tipo_vehiculo=".$_POST['id_tipo_vehiculo']." ";

if( isset($_POST['placa']) && $_POST['placa']!='' )
	$where .= " and upper(vi.placa_vehiculo)='".strtoupper($_POST['placa'])."' ";

if(isset($_POST['id_aseguradora']) && $_POST['id_aseguradora']!='')
	$where.=" and vi.id_aseguradora=".$_POST['id_aseguradora']." ";

if(isset($_POST['id_parqueadero']) && $_POST['id_parqueadero']!='')
	$where.=" and vi.id_parqueadero=".$_POST['id_parqueadero']." ";

if(isset($_POST['id_transito']) && $_POST['id_transito']!='')
	$where .= " and vi.id_transito=".$_POST['id_transito']." ";

if(isset($_POST['id_taller']) && $_POST['id_taller']!='')
	$where .= " and vi.id_taller=".$_POST['id_taller']." ";

if(isset($_POST['id_otro_vehiculo']) && $_POST['id_otro_vehiculo']!='')
	$where .= " and vi.id_otro_vehiculo=".$_POST['id_otro_vehiculo']." ";

if( isset($_POST['cedula']) && $_POST['cedula']!='' )
	$where .= " and lv.cedula='".$_POST['cedula']."' ";

if(isset($_POST['id_hospital']) && $_POST['id_hospital']!='')
	$where .= " and lv.id_hospital=".$_POST['id_hospital']." ";

if(isset($_POST['id_clinica']) && $_POST['id_clinica']!='')
	$where .= " and lv.id_clinica=".$_POST['id_clinica']." ";

if(isset($_POST['id_centro_salud']) && $_POST['id_centro_salud']!='')
	$where .= " and lv.id_centro_salud=".$_POST['id_centro_salud']." ";

if(isset($_POST['conductor']) and $_POST['conductor']=="SI")
	$where .= " and lv.conducia='SI' ";

if(isset($_POST['muerto']) and $_POST['muerto']=="SI")
	$where .= " and lv.muerto='SI' ";

if(isset($_POST['lesionado']) and $_POST['lesionado']=="SI")
	$where .= " and lv.lesionado='SI' ";

if(isset($_POST['tipLesL']) and $_POST['tipLesL']=="SI")
	$where .= " and lv.tipo_lesion='L' ";

if(isset($_POST['tipLesG']) and $_POST['tipLesG']=="SI")
	$where .= " and lv.tipo_lesion='G' ";

if(isset($_POST['id_ambulancia']) && $_POST['id_ambulancia']!='')
	$where .= " and lv.id_trasladado_por=".$_POST['id_ambulancia']." ";

if(isset($_POST['edad_d']) && $_POST['edad_d']!='')
	$where .= " and LPAD(lv.edad,3,'0')>='".str_pad($_POST['edad_d'], 3, '0', STR_PAD_LEFT)."'";

if(isset($_POST['edad_h']) && $_POST['edad_h']!='')
	$where .= " and LPAD(lv.edad,3,'0')<='".str_pad($_POST['edad_h'], 3, '0', STR_PAD_LEFT)."'";

if(isset($_POST['id_grua']) && $_POST['id_grua']!='')
	$where .= " and vi.id_grua=".$_POST['id_grua']." ";

if(isset($_POST['id_otro_lesionado']) && $_POST['id_otro_lesionado']!='')
	$where .= " and lv.id_otro_lesionado=".$_POST['id_otro_lesionado']." ";

if(isset($_POST['entidad']) && $_POST['entidad']!='')
	$where .= " and i.id in (select id_incidente from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo_entidad where id_entidad=".$_POST['entidad']." ) ";

if(isset($_POST['usuario_registra']) && $_POST['usuario_registra']!='')
	$where .= " and i.id in (select id_incidente from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente WHERE estado=1 and usuario=".$_POST['usuario_registra'].") ";

if(isset($_POST['usuario_sos']) && $_POST['usuario_sos']!='')
	$where .= " and i.id in (select id_incidente from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente WHERE estado in (2,3) and usuario=".$_POST['usuario_sos'].") ";

if(isset($_POST['usuario_adm_vial']) && $_POST['usuario_adm_vial']!='')
	$where .= " and i.id in (select id_incidente from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente WHERE estado in (4,5) and usuario=".$_POST['usuario_adm_vial'].") ";

if(isset($_POST['fecha_inicio']) && $_POST['fecha_inicio']!='' && $_POST['fecha_fin']!='')
	$where .= " and fechaincidente between  '".$_POST['fecha_inicio']."' and '".$_POST['fecha_fin']."' ";

if(isset($_POST['codigo']) && $_POST['codigo']!='' )
	$where .= " and concat(i.periodo,'.',LPAD(i.codigo,5,'0')) like '".$_POST['codigo']."'";

if(isset($_POST['referencia']) && $_POST['referencia']!='' )
{
	$refe=explode("|",$_POST['referencia']);
	$where .= " and i.referencia=".$refe[0]." ";
}

if(isset($_POST['sentido_via']) && $_POST['sentido_via']!='' )
	$where .= " and i.sentido_via=".$_POST['sentido_via']." ";

if(isset($_POST['via']) && $_POST['via']!='' )
	$where .= " and i.via=".$_POST['via']." ";
	
if(isset($_POST['informado_por']) && $_POST['informado_por']!='')
	$where .= " and informado_por='".$_POST['informado_por']."' ";

if(isset($_POST['condiciones']) && $_POST['condiciones']!='')
	$where .= " and i.condiciones_climaticas='".$_POST['condiciones']."' ";

if(isset($_POST['cilindraje']) && $_POST['cilindraje']!='')
	$where .= " and vi.cilindraje_vehiculo='".$_POST['cilindraje']."' ";

if(isset($_POST['pesv']) && $_POST['pesv']!='')
	$where .= " and i.pesv='".$_POST['pesv']."' ";

// FIn where

// Ordenamiento
$order = " i.id asc ";
if( $sidx!="" )
{
	if( $sidx=="codigo" )
		$order = "2 $sord, 3 $sord";
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
		FROM (select $select FROM $from where $where $group) t";
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

$sql = "SELECT $select
		FROM $from
		WHERE $where
		$group
		ORDER BY i.id asc
		";//LIMIT $start , $limit
$rs = $_SESSION[APL]->bd->getRs($sql);

$i			= 0;
$id_inci	= 0;
$datos_linea= 0;
$totMue		= 0;
$totHer		= 0;
$vehi		= "";
$lesi		= "";
$dato		= '';
$numEdad	= 0;
$sumEdad	= 0;
$totInc		= 0;
$totAmb		= 0;
$totGru		= 0;
$totSitVeh	= 0;
$totSitUsu	= 0;
$totMan		= 0;
$totTar		= 0;
$totNoc		= 0;
$totMad		= 0;
$totTieMin	= 0;
$conTie		= 0;
$proEda		= 0;
$arrDiaSem	= array();
$arrDiaSem["Domingo"]	= 0;
$arrDiaSem["Lunes"]		= 0;
$arrDiaSem["Martes"]	= 0;
$arrDiaSem["Miercoles"]	= 0;
$arrDiaSem["Jueves"]	= 0;
$arrDiaSem["Viernes"]	= 0;
$arrDiaSem["Sabado"]	= 0;

if( $completo==1 )
	$dato		= 'ID;FECHA;DIA;HORA REPORTE;HORA LLEGADA;TIEMPO;DURACION EVENTO;ABSCISA;REFERENCIA;VIA;CONDICIONES CLIMATICAS;SENTIDO;INFORMADO POR;TIPO ATENCION;NRO MUERTOS;'.
				  'NRO HERIDOS;AMBULANCIA;GRUA;VEHICULO INVOLUCRADO;PLACAS;CILINDRAJE;NOMBRE USUARIO;IDENTIFICACION USUARIO;TIPO LESIONADO;EDAD;'.
				  'SITIO TRASLADO VEHICULO;SITIO TRASLADO USUARIO;PESV;OBSERVACIONES'.chr(10);
else
	$dato		= 'ID;FECHA;DIA;HORA REPORTE;HORA LLEGADA;TIEMPO;DURACION EVENTO;ABSCISA;REFERENCIA;VIA;SENTIDO;INFORMADO POR;TIPO ATENCION;NRO MUERTOS;'.
				  'NRO HERIDOS;PESV;OBSERVACIONES'.chr(10);

while( !$rs->EOF )
{
	$codigo = $rs->fields[1];
	$period = $rs->fields[2];
	
	/*$colId  = '';
	$colDoc = '';
	$colFec = '';
	$colDia = '';*/
	$colHorRep = '';
	$colHorLle = '';
	$colTiempo = '';
	/*$colDurEve = '';
	$colAbs = '';
	$colRef = '';
	$colVia = '';
	$colInfPor = '';
	$colTipAte = '';*/
	$colNumMue = '';
	$colNumHer = '';
	$colAmb = '';
	$colGru = '';
	$colVehInv = '';
	$colPla = '';
	$colNomUsu = '';
	$colIdeUsu = '';
	$colTraVeh = '';
	$colTraUsu = '';
	$colObs = '';
	$colEdad = '';
	$colTipLes = '';
	$colSenVia = '';
	$colFecR = '';
	$colCond = '';
	$colCili = '';	
	$colPesv = '';	

	$hora_salida_base  = "";
	$hora_llegada_base = "";
	if( $completo==1 )
	{
		$hora_salida_base  = explode(":",$rs->fields[28]);
		$hora_llegada_base = explode(":",$rs->fields[29]);
	}
	else
	{
		$hora_salida_base  = explode(":",$rs->fields[15]);
		$hora_llegada_base = explode(":",$rs->fields[16]);
	}

	$hora_salida_base_h="";
	$hora_salida_base_m="";
	if( count($hora_salida_base)>1 )
	{
		$hora_salida_base_h = $hora_salida_base[0];
		$hora_salida_base_m = $hora_salida_base[1];
	}

	$hora_llegada_base_h = "";
	$hora_llegada_base_m = "";
	if( count($hora_llegada_base)>1 )
	{	
		$hora_llegada_base_h = $hora_llegada_base[0];
		$hora_llegada_base_m = $hora_llegada_base[1];
	}
	
	$tiempo_total = "";
	if($hora_llegada_base_h!='' && $hora_llegada_base_m!='' && $hora_salida_base_h!='' && $hora_salida_base_m!='')
	{
		$hora_s = $hora_llegada_base_h;
		$hora_r = $hora_salida_base_h;
		
		$minu_s = $hora_llegada_base_m;
		$minu_r = $hora_salida_base_m;
		if( $minu_r>$minu_s )
		{
			$minu_s = $minu_s+60;	
			$hora_s = $hora_s-1;
		}

		$horas = $hora_s-$hora_r;
		$minus = $minu_s-$minu_r;
		if( $horas<0 )
		{
			$tiempo_total='00:00';	
			$horas = ($hora_s+24)-$hora_r;

		}
		//else
			$tiempo_total=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
		
	}
	else
		$tiempo_total='00:00';

	$datos_linea = 0;
	if( $id_inci==0 || $id_inci!=$period.$codigo )
	{
		$totMue += intval($rs->fields[12]);
		$totHer += intval($rs->fields[13]);
		$id_inci = $period.$codigo;
		$datos_linea=1;
	}
	
	if($datos_linea==1)
	{
		$titRep = utf8_encode('REPORTE ACCIDENTE');
		$titInf = utf8_encode('INFORME ADMINISTRADOR VIAL DE  EVENTUALIDADES SOBRE LA VÍA');
		
		$colId  = $period.'.'.str_pad($codigo,5,"0",STR_PAD_LEFT);
		$colDoc = '<img src="../img/popup.png" style="cursor:pointer" title="'.$titRep.'" alt="'.$titRep.'" onclick="window.open(\'../reporte_1.php?id_buscar='.$rs->fields[0].'\',\'_blank\')"/>'.
				  '<img src="../img/popup.png" style="cursor:pointer" title="'.$titInf.'" alt="'.$titInf.'" onclick="window.open(\'../reporte_2.php?id_buscar='.$rs->fields[0].'\',\'_blank\')"/>';
	}
	
	if( $completo==1 )
	{
		if($datos_linea==1)
		{
			$colFec = $rs->fields[3];
			$colDia = $_SESSION[APL]->getDiaSemana($rs->fields[3],2);
			$colHorRep = $rs->fields[4];
			$colHorLle = $rs->fields[27];
			$colDurEve = $tiempo_total;
			$colAbs = $rs->fields[7];
			$colRef = $rs->fields[8];
			$colVia = $rs->fields[9];
			$colInfPor = $rs->fields[30];
			$colTipAte = $rs->fields[11];
			$colNumMue = $rs->fields[12];
			$colNumHer = $rs->fields[13];
		}

		$colAmb = trim($rs->fields[31]);
		$colGru = $rs->fields[32];
		$colVehInv = $rs->fields[14];
		$colPla = $rs->fields[15];
		$colNomUsu = $rs->fields[16];
		$colIdeUsu = $rs->fields[17];
		$colEdad = intval($rs->fields[33]);
		$colObs = $rs->fields[26];
		$conducia  = $rs->fields[34];
		$lesionado = $rs->fields[35];
		$muerto    = $rs->fields[36];
		$tipoLes   = $rs->fields[37];
		$colSenVia = $rs->fields[38];
		$colFecR = $rs->fields[39];
		$colCond = $rs->fields[40];
		$colCili = $rs->fields[41];
		$colPesv = $rs->fields[42];

		//$colTipLes = 
		if( $conducia=='SI' )
			$colTipLes = 'Conductor - ';
		if( $lesionado=='SI' )
		{
			if( $tipoLes=='L' ) 
				$colTipLes .= 'Lesionado Leve - ';
			else if( $tipoLes=='G' ) 
				$colTipLes .= 'Lesionado Grave - ';
			else 
				$colTipLes .= 'Lesionado - ';
		}
		if( $muerto=='SI' )
			$colTipLes .= 'Muerto - ';
		
		if( $colTipLes!='' )
			$colTipLes = substr($colTipLes,0,-3);
		
		if($rs->fields[18]!='')
			$colTraVeh = $rs->fields[18];
		else if($rs->fields[19]!='')
			$colTraVeh = $rs->fields[19];
		else if($rs->fields[20]!='')
			$colTraVeh = $rs->fields[20];
		else if($rs->fields[21]!='')
			$colTraVeh = $rs->fields[21];
		
		if($rs->fields[22]!='')
			$colTraUsu = $rs->fields[22];
		else if($rs->fields[23]!='')
			$colTraUsu= $rs->fields[23];
		else if($rs->fields[24]!='')
			$colTraUsu= $rs->fields[24];
		else if($rs->fields[25]!='')
			$colTraUsu= $rs->fields[25];
	}
	else
	{
		$colFec = $rs->fields[3];
		$colDia = $_SESSION[APL]->getDiaSemana($rs->fields[3],2);
		$colHorRep = $rs->fields[4];
		$colHorLle = $rs->fields[14];
		$colDurEve = $tiempo_total;
		$colAbs = $rs->fields[7];
		$colRef = $rs->fields[8];
		$colVia = $rs->fields[9];
		$colInfPor = $rs->fields[10];
		$colTipAte = $rs->fields[11];
		$colNumMue = $rs->fields[12];
		$colNumHer = $rs->fields[13];
		$colObs = $rs->fields[18];
		$colSenVia = $rs->fields[19];
		$colFecR = $rs->fields[20];
		$colCond = $rs->fields[21];
		$colPesv = $rs->fields[22];
	}
	
	$arrDatos  = array();
	$arrDatExc = array();
	if( $completo==1 )
	{
		if( $colEdad==0 or $colEdad=="0" )
			$colEdad = "";

		if( $datos_linea==1 )
		{
			$datetime1 = strtotime($colFec." ".$colHorLle);
			$datetime2 = strtotime($colFecR." ".$colHorRep);
			$interval = $datetime1-$datetime2;
			if( $interval<0 )
				$interval = $datetime2-$datetime1;

			$colTiempo=$_SESSION[APL]->convertirMinutosEnHoras($interval/60);//->format("H:i");
		}	
		// totales
		if( trim($colAmb)!='' )		$totAmb++;
		if( trim($colGru)!='' )		$totGru++;
		if( trim($colTraVeh)!='' )	$totSitVeh++;
		if( trim($colTraUsu)!='' )	$totSitUsu++;
		
		if( $colEdad!="" and $colEdad>=1 )
		{
			$numEdad++;
			$sumEdad += $colEdad;
		}
		
		$arrDatos  = array($colId,$colDoc,$colFec,$colDia,$colHorRep,$colHorLle,$colTiempo,$colDurEve,$colAbs,$colRef,$colVia,$colCond,$colSenVia,$colInfPor,$colTipAte,
						   $colNumMue,$colNumHer,$colAmb,$colGru,$colVehInv,$colPla,$colCili,$colNomUsu,$colIdeUsu,$colTipLes,$colEdad,utf8_encode($colTraVeh),$colTraUsu,$colPesv,$colObs);
		$arrDatExc = array($colId,$colFec,$colDia,$colHorRep,$colHorLle,$colTiempo,$colDurEve,$colAbs,$colRef,$colVia,$colCond,$colSenVia,$colInfPor,$colTipAte,
						   $colNumMue,$colNumHer,$colAmb,$colGru,$colVehInv,$colPla,$colCili,$colNomUsu,$colIdeUsu,$colTipLes,$colEdad,$colTraVeh,$colTraUsu,$colPesv,$colObs);
	}
	else
	{
	
		//correcion del tiempo transcurrido entre el reporte y la hora de llegada a la base
		
		$datetime1 = strtotime($colFec." ".$colHorLle);
		$datetime2 = strtotime($colFecR." ".$colHorRep);
		$interval = $datetime1-$datetime2;
		if( $interval<0 )
			$interval = $datetime2-$datetime1;

		$colTiempo=$_SESSION[APL]->convertirMinutosEnHoras($interval/60);
		
		///////////////////////////////////////////////////////////////////////////////////
	
		
		$arrDatos = array($colId,$colDoc,$colFec,$colDia,$colHorRep,$colHorLle,$colTiempo,$colDurEve,$colAbs,$colRef,
						  $colVia,$colCond,$colSenVia,$colInfPor,$colTipAte,$colNumMue,$colNumHer,"","","","","","","","","",$colPesv,$colObs);
		$arrDatExc = array($colId,$colFec,$colDia,$colHorRep,$colHorLle,$colTiempo,$colDurEve,$colAbs,$colRef,
						   $colVia,$colSenVia,$colInfPor,$colTipAte,$colNumMue,$colNumHer,$colPesv,$colObs);
	}
	
	if( ($completo==1 and $datos_linea==1) or $completo!=1 )
	{
		$totInc++;
		
		if( isset($arrDiaSem[$colDia]) )
			$arrDiaSem[$colDia]++;
		
		if( $colTiempo!="" )
		{
			$totTieMin = $totTieMin + $_SESSION[APL]->convertirHorasEnMinutos($colTiempo);
			$conTie++;
		}
		
		$horLleRep = substr($colHorRep,0,2);
			
		if( $horLleRep>="06" and $horLleRep<="11" )	$totMan++;
		if( $horLleRep>="12" and $horLleRep<="17" )	$totTar++;
		if( $horLleRep>="18" and $horLleRep<="23" )	$totNoc++;
		if( $horLleRep>="00" and $horLleRep<="05" )	$totMad++;
	}
	
	$linea = implode(';', $arrDatExc);
	$dato .= utf8_decode($linea).chr(10);
	
	$responce->rows[$i]['id'] = $i;
	$responce->rows[$i]['cell']=$arrDatos; 
	$i++;
	$rs->MoveNext();

}// Fin recorrido registros

$proMin = $totTieMin;
if( $conTie>0 )
	$proMin = ceil($totTieMin/$conTie);

$proTie = $_SESSION[APL]->convertirMinutosEnHoras($proMin);

$proEda = "N/A";
if( $numEdad>=1 ){
	$proEda = $sumEdad/$numEdad;
	$proEda = round($proEda,2);
}

if( $dato!="" )
{
	$arrDatExc = array();
	if( $completo==1 )
		$arrDatExc = array($totInc,$i,"","","",$proTie,"","","","","","","","",$totMue,$totHer,$totAmb,$totGru,"","","","","",$proEda,$totSitVeh,$totSitUsu,"");
	else
		$arrDatExc = array($totInc,$i,"","","",$proTie,"","","","","","","",$totMue,$totHer,"");
	
	$linea = implode(';', $arrDatExc);
	$dato .= $linea.chr(10).chr(10);
	
	$linea = 'Total Manana: '.$totMan.';'.'Total Tarde: '.$totTar.';'.'Total Noche: '.$totNoc.';'.'Total Madrugada: '.$totMad;
	$dato .= $linea.chr(10).chr(10);
	
	$linea = 'Total Incidentes por dia de la semana';
	$dato .= $linea.chr(10);
	$linea = 'Lunes: '.$arrDiaSem["Lunes"].';Martes: '.$arrDiaSem["Martes"].';Miercoles: '.$arrDiaSem["Miercoles"].';Jueves: '.$arrDiaSem["Jueves"].
			 ';Viernes: '.$arrDiaSem["Viernes"].';Sabado: '.$arrDiaSem["Sabado"].';Domingo: '.$arrDiaSem["Domingo"];
	$dato .= $linea;
	
	if( file_exists("../adjuntos/reporte.csv") )
		unlink("../adjuntos/reporte.csv");
	
	$fp = fopen("../adjuntos/reporte.csv", "w");
	fwrite($fp,$dato);
	fclose($fp);
	
}

$responce->userdata["id"]				= "Incidentes: ".$totInc;
$responce->userdata["docs"]				= "Registros: ".$i;
$responce->userdata["nro_muertos"]		= $totMue;
$responce->userdata["nro_heridos"]		= $totHer;
$responce->userdata["ambulancia"]		= $totAmb;
$responce->userdata["grua"]				= $totGru;
$responce->userdata["sit_tras_vehi"]	= $totSitVeh;
$responce->userdata["sit_tras_usua"]	= $totSitUsu;
$responce->userdata["tiempo"]			= $proTie;
$responce->userdata["edad"]				= $proEda;

$responce->userdata["totMan"] = $totMan;
$responce->userdata["totTar"] = $totTar;
$responce->userdata["totNoc"] = $totNoc;
$responce->userdata["totMad"] = $totMad;

$responce->userdata["totLun"] = $arrDiaSem["Lunes"];
$responce->userdata["totMar"] = $arrDiaSem["Martes"];
$responce->userdata["totMie"] = $arrDiaSem["Miercoles"];
$responce->userdata["totJue"] = $arrDiaSem["Jueves"];
$responce->userdata["totVie"] = $arrDiaSem["Viernes"];
$responce->userdata["totSab"] = $arrDiaSem["Sabado"];
$responce->userdata["totDom"] = $arrDiaSem["Domingo"];

$responce->userdata["totReg"] = $i;

echo json_encode($responce);

?>