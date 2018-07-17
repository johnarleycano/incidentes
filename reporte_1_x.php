<?php
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}

require_once('html2pdf_v3.31/html2pdf.class.php');

//require_once("dompdf/dompdf_config.inc.php");


$html2pdf = new HTML2PDF('P', 'A4', 'en');

$tam=600;
$dummy='
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<title>Reporte Accidentalidad</title>
</head>

<link href="css/pdf.css" rel="stylesheet" type="text/css">

<body leftmargin="0" topmargin="0">
';

if(isset($_GET['id_buscar']))
	$id_buscar=$_GET['id_buscar'];
else
	$id_buscar=$_POST['id_buscar'];
	$parametro=array('id'=>$id_buscar);
	$sql="SELECT 
	i.id,
	date_format(i.fecha,'%e-%c-%Y'),
	hora_reporte,
	i.referencia,
	v.nombre,
	ta.nombre,
	i.estado,
	r.abscisa,
	r.tramo_ruta,
	i.periodo,
	nombre_usuario,
	identificacion_usuario,
	r.referencia,
	informado_por,
	informado_por_nombre,
	hora_salida_base,
	hora_llegada_sitio,
	hora_salida_sitio,
	hora_llegada_base,
	sentido,
	absicsa_salida,
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	'',
	nro_muertos,
	nro_heridos,
	transito,
	transito_placa,
	transito_apellido,
	policia,
	policia_placa,
	policia_apellido,
	inspector,
	inspector_placa,
	inspector_apellido,
	visualizar_web,
	'',
	'',
	'',
	'',
	periodo,
	codigo,
	firma,
	nombres,
	apellidos,
	abscisa_real,
	date_format(i.fechaincidente,'%e-%c-%Y')
	-- horaincidente
	FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia  as r,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via as v,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion as ta,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente as h,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u
	
	 WHERE 
	 i.id=h.id_incidente and
	 h.estado=1 and 
	 h.usuario=u.id and
	 i.tipo_atencion=ta.id and
	 i.via=v.id and
	 i.referencia=r.id and
	 i.id=?";
	$inci=$_SESSION[APL]->bd->getRsO($sql,$parametro);
	$id_buscar=$inci->fields[0];
	$fecha=$inci->fields[1];
	$fecInc=$inci->fields[51];
	// print_r($inci);

	if( $fecInc=="" )
		$fecInc = $fecha;

	if($fecInc!='')
	{
		$fecha_reporte=explode("-",$fecInc);
		$ano_rep=$fecha_reporte[2];
		$mes_rep=$fecha_reporte[1];
		$dia_rep=$fecha_reporte[0];
	}
	else
	{
		$ano_rep="";
		$mes_rep="";
		$dia_rep="";
	}
	
	
	if($inci->fields[2]!='')
	{
		// $hora_reporte=explode(":",$inci->fields[2]);
		$hora_reporte=explode(":",$inci->fields[2]);
		// print_r($hora_reporte);
		$hora_rep=$hora_reporte[0];
		$minu_rep=$hora_reporte[1];
	}
	else
	{
		$hora_rep="";
		$minu_rep="";
	}
	$referencia=$inci->fields[3];
	$via=$inci->fields[4];
	
	$tipo_atencion=$inci->fields[5];	
	$estado=$inci->fields[6];
	
	if($inci->fields[50]!='')
		$abscisap=$inci->fields[50];
	else
		$abscisap=$inci->fields[7];
	
	if($inci->fields[7]!='')
	{	
		$abscisa=explode("+",$abscisap);
		$absicsa_evento_p1=trim(str_replace("K","",$abscisa[0]));
		$absicsa_evento_p2=trim($abscisa[1]);
	}
	else
	{
		$absicsa_evento_p1="";
		$absicsa_evento_p2="";
	}
	
	$tramo_ruta=$inci->fields[8];
	$periodo=$inci->fields[9];
	$nombre_usuario=$inci->fields[10];
	$identificacion_usuario=$inci->fields[11];
	$nombre_referencia=$inci->fields[12];
	$informado_por=$inci->fields[13];
	$informado_por_nombre=$inci->fields[14];
	$hora_salida_base=explode(":",$inci->fields[15]);
	if(count($hora_salida_base)>1)
	{
		$hora_salida_base_h=$hora_salida_base[0];
		$hora_salida_base_m=$hora_salida_base[1];
	}
	else
	{
		$hora_salida_base_h="";
		$hora_salida_base_m="";
	}
	
	$hora_llegada_sitio=explode(":",$inci->fields[16]);
	if(count($hora_llegada_sitio)>1)
	{
		$hora_llegada_sitio_h=$hora_llegada_sitio[0];
		$hora_llegada_sitio_m=$hora_llegada_sitio[1];
	}
	else
	{
		$hora_llegada_sitio_h="";
		$hora_llegada_sitio_m="";
	}
	$hora_salida_sitio=explode(":",$inci->fields[17]);
	if(count($hora_salida_sitio)>1)
	{
		$hora_salida_sitio_h=$hora_salida_sitio[0];
		$hora_salida_sitio_m=$hora_salida_sitio[1];
	}
	else
	{
		$hora_salida_sitio_h="";
		$hora_salida_sitio_m="";
	}
	$hora_llegada_base=explode(":",$inci->fields[18]);
	if(count($hora_llegada_base)>1)
	{	
		$hora_llegada_base_h=$hora_llegada_base[0];
		$hora_llegada_base_m=$hora_llegada_base[1];
	}
	else
	{
		$hora_llegada_base_h="";
		$hora_llegada_base_m="";
	}
	$sentido=$inci->fields[19];
	
	
	$absicsa_salida=explode("+",$inci->fields[20]);
	if(count($absicsa_salida)>1)
	{
		$absicsa_salida_p1=trim(str_replace("K","",$absicsa_salida[0]));
		$absicsa_salida_p2=trim($absicsa_salida[1]);
	}
	else
	{
		$absicsa_salida_p1="";
		$absicsa_salida_p2="";
	}
	$entidad_1=$inci->fields[21];
	$funcionario_entidad_1=$inci->fields[22];
	$entidad_2=$inci->fields[23];
	$funcionario_entidad_2=$inci->fields[24];
	$entidad_3=$inci->fields[25];
	$funcionario_entidad_3=$inci->fields[26];
	$entidad_4=$inci->fields[27];
	$funcionario_entidad_4=$inci->fields[28];

	$nro_muertos=$inci->fields[29];
	$nro_muertos_total=$inci->fields[29];
	$nro_heridos=$inci->fields[30];
	$nro_heridos_total=$inci->fields[30];
	
	$transito=$inci->fields[31]==''?'NO':$inci->fields[31];
	$transito_placa=$inci->fields[32];
	$transito_apellido=$inci->fields[33];
	$policia=$inci->fields[34]==''?'NO':$inci->fields[34];
	$policia_placa=$inci->fields[35];
	$policia_apellido=$inci->fields[36];
	$inspector=$inci->fields[37]==''?'NO':$inci->fields[37];
	$inspector_placa=$inci->fields[38];
	$inspector_apellido=$inci->fields[39];
	
	$visualizar_web=$inci->fields[40];
	$archivo1=$inci->fields[41];
	$archivo2=$inci->fields[42];
	$archivo3=$inci->fields[43];
	$archivo4=$inci->fields[44];
	$periodo=$inci->fields[45];
	$codigo=$inci->fields[46];
	$firma=$inci->fields[47];
	$nombres=$inci->fields[48];
	$apellidos=$inci->fields[49];


if(isset($id_buscar) && $informado_por=='P') 
$reportado_por='Policía de tránsito y transporte';
else
if(isset($id_buscar) && $informado_por=='U')
$reportado_por='Usuario';
else
if(isset($id_buscar) && $informado_por=='O')
$reportado_por='Operario';
else
if(isset($id_buscar) && $informado_por=='V')
$reportado_por='Administrador Vial';
else
if(isset($id_buscar) && $informado_por=='M')
$reportado_por='Personal Mantenimiento Vial';
else
if(isset($id_buscar) && $informado_por=='E')
$reportado_por='Ejercito Nacional';
else
if(isset($id_buscar) && $informado_por=='N')
$reportado_por='Policía Nacional';
else
$reportado_por='&nbsp;';
	
	

$dummy.='<table  border="0" cellpadding="1" cellspacing="3">';
$dummy.='<tr height="22">
		<th colspan="16" height="22" width="'.($tam*1).'" class="TituloReporte" align="center">REPORTE GENERAL DE ACCIDENTE - CENTRAL S.O.S</th>
  </tr>';
$dummy.='<tr class="TituloReporte">
<th  colspan="16" width="'.round($tam*1).'">INFORMACION BASICA</th>
</tr>';
$dummy.='<tr>
<th rowspan="2" width="'.round($tam*0.0625).'" class="TituloReporte">FECHA</th>
<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">DIA</th>
<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">MES</th>
<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">AÑO</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">HORA</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$hora_rep.':'.$minu_rep.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">IDENTIFICADOR EVENTO</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2" align="center">'.$periodo.".".str_pad($codigo,5,"0",STR_PAD_LEFT).'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">OPERADOR SOS</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.wordwrap($nombre_usuario,20,'<br>').'</td>
</tr>';
$dummy.='<tr>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$dia_rep.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$mes_rep.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$ano_rep.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">TIPO DE ATENCION</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$tipo_atencion.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">SENTIDO</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$sentido.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">ABSCISA</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">K '.$absicsa_evento_p1.'+'.$absicsa_evento_p2.'</td>
</tr>
';
$dummy.='<tr>
<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">TRAMO</th>
<td width="'.round($tam*0.1875).'" class="ContenidoReporte" colspan="3">'.$via.' '.$tramo_ruta.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">SITIO REFERENCIA</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$nombre_referencia.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">REPORTADO POR</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$reportado_por.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">NOMBRE</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$informado_por_nombre.'</td>
</tr>';
$dummy.='<tr >
<th  colspan="16" height="10" width="'.round($tam*1).'">&nbsp;</th>
</tr>';
$dummy.='<tr class="TituloReporte">
<th  colspan="16" width="'.round($tam*1).'" align="center">ATENCION EMERGENCIA AMBULANCIA</th>
</tr>';

$dummy.='<tr>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">HORA REPORTE</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">HORA SALIDA DE BASE</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">HORA LLEGADA AL SITIO</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">HORA SALIDA DEL SITIO</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">HORA FIN DE ATENCION</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">DURACION TOTAL DEL EVENTO</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">ABSCISA DEL EVENTO</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">ABSCISA DE SALIDA</th>
</tr>';
$dummy.='<tr>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">K</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">M</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">K</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">M</th>
</tr>';

	if(isset($id_buscar) && $hora_llegada_base_h!='' && $hora_llegada_base_m!='' && $hora_salida_base_h!='' && $hora_salida_base_m!='')
	{
		$hora_s=$hora_llegada_base_h;
		$hora_r=$hora_salida_base_h;
		
		$minu_s=$hora_llegada_base_m;
		$minu_r=$hora_salida_base_m;
		if($minu_r>$minu_s)
		{
			$minu_s=$minu_s+60;	
			$hora_s=$hora_s-1;
		}

		$horas=$hora_s-$hora_r;
		$minus=$minu_s-$minu_r;
		if($horas<0)
		{
			$tiempo_total='';	
		}
		else
			$tiempo_total=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
		$duracion_tota_evento.= $tiempo_total;
	}
else
	$duracion_tota_evento.='&nbsp;';

$duracion_tota_evento_a=explode(":",$duracion_tota_evento);
		$duracion_tota_evento_h=$duracion_tota_evento_a[0];
		$duracion_tota_evento_m=$duracion_tota_evento_a[1];




$dummy.='<tr>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$hora_rep.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$minu_rep.'</td>

<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$hora_salida_base_h.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$hora_salida_base_m.'</td>

<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$hora_llegada_sitio_h.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$hora_llegada_sitio_m.'</td>

<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$hora_salida_sitio_h.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$hora_salida_sitio_m.'</td>

<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$hora_llegada_base_h.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$hora_llegada_base_m.'</td>

<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$duracion_tota_evento_h.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$duracion_tota_evento_m.'</td>

<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$absicsa_evento_p1.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$absicsa_evento_p2.'</td>

<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$absicsa_salida_p1.'</td>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$absicsa_salida_p2.'</td>

</tr>';

$dummy.='<tr >
<th  colspan="16" height="10" width="'.round($tam*1).'">&nbsp;</th>
</tr>';


$dummy.='<tr class="TituloReporte">
<th  colspan="16" width="'.round($tam*1).'">APOYO EN ATENCION</th>
</tr>';
$dummy.='<tr>
<th rowspan="2" width="'.round($tam*0.1875).'" class="TituloReporte" colspan="3" align="center">ENTIDAD</th>
<th rowspan="2" width="'.round($tam*0.1875).'" class="TituloReporte" colspan="3" align="center">NOMBRE</th>
<th rowspan="2" width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">CARGO</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">HORA SALIDA DE BASE</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">HORA LLEGADA AL SITIO</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">HORA SALIDA DEL SITIO</th>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">DURACION TOTAL DEL EVENTO</th>
</tr>';
$dummy.='<tr>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">HH</th>
<th width="'.round($tam*0.065).'" class="TituloReporte" align="center">MI</th>
</tr>';
$sql="SELECT ae.id, a.nombre,funcionario, hora_salida_base,
hora_llegada_sitio,
hora_salida_sitio,
hora_llegada_base
FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo_entidad as ae,
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo as a
WHERE
a.id=id_entidad and
id_incidente=".$id_buscar."
ORDER BY ae.id ASC";
$apo_enti=$_SESSION[APL]->bd->getRs($sql);

while(!$apo_enti->EOF)
{
	$hora_salida_base_a=explode(":",$apo_enti->fields[3]);
	$hora_salida_base_a_h=$hora_salida_base_a[0];
	$hora_salida_base_a_m=$hora_salida_base_a[1];
	$hora_llegada_sitio_a=explode(":",$apo_enti->fields[4]);
	$hora_llegada_sitio_a_h=$hora_llegada_sitio_a[0];
	$hora_llegada_sitio_a_m=$hora_llegada_sitio_a[1];
	$hora_salida_sitio_a=explode(":",$apo_enti->fields[5]);
	$hora_salida_sitio_a_h=$hora_salida_sitio_a[0];
	$hora_salida_sitio_a_m=$hora_salida_sitio_a[1];
	$sb= $apo_enti->fields[3];
	$lb= $apo_enti->fields[6];
	
	
	if($sb!='' && $lb!='')		
			{
				$sb=explode(":",$apo_enti->fields[3]);
				$lb=explode(":",$apo_enti->fields[6]);
				$hora_s=$lb[0]+0;
				$hora_r=$sb[0]+0;
				
				$minu_s=$lb[1]+0;
				$minu_r=$sb[1]+0;
				if($minu_r>$minu_s)
				{
					$minu_s=$minu_s+60;	
					$hora_s=$hora_s-1;
				}
		
				$horas=$hora_s-$hora_r;
				$minus=$minu_s-$minu_r;
				if($horas<0)
				{
					$tiempo_a='';	
				}
				else
					$tiempo_a=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
				
			}
			else
				$tiempo_a='';	
	$duracion_total_evento_a=explode(":",$tiempo_a);
	$duracion_total_evento_a_h=$duracion_total_evento_a[0];
	$duracion_total_evento_a_m=$duracion_total_evento_a[1];
	
	
	$dummy.='<tr>
				<td width="'.round($tam*0.1875).'" class="ContenidoReporte" align="center" colspan="3">'.$apo_enti->fields[1].'</td>
				<td width="'.round($tam*0.1875).'" class="ContenidoReporte" align="center" colspan="3">'.$apo_enti->fields[2].'</td>
				<td width="'.round($tam*0.125).'" class="ContenidoReporte" align="center" colspan="2">&nbsp;</td>
				<td width="'.round($tam*0.065).'" class="ContenidoReporte" align="center">'.$hora_salida_base_a_h.'</td>
				<td width="'.round($tam*0.065).'" class="ContenidoReporte" align="center">'.$hora_salida_base_a_m.'</td>
				<td width="'.round($tam*0.065).'" class="ContenidoReporte" align="center">'.$hora_llegada_sitio_a_h.'</td>
				<td width="'.round($tam*0.065).'" class="ContenidoReporte" align="center">'.$hora_llegada_sitio_a_m.'</td>
				<td width="'.round($tam*0.065).'" class="ContenidoReporte" align="center">'.$hora_salida_sitio_a_h.'</td>
				<td width="'.round($tam*0.065).'" class="ContenidoReporte" align="center">'.$hora_salida_sitio_a_m.'</td>
				<td width="'.round($tam*0.065).'" class="ContenidoReporte" align="center">'.$duracion_total_evento_a_h.'</td>
				<td width="'.round($tam*0.065).'" class="ContenidoReporte" align="center">'.$duracion_total_evento_a_m.'</td>
				</tr>';
				
		$apo_enti->MoveNext();
	
}
$dummy.='<tr >
<th  colspan="16" height="10" width="'.round($tam*1).'">&nbsp;</th>
</tr>';

$dummy.='<tr class="TituloReporte">
<th  colspan="16" width="'.round($tam*1).'" align="center">AUTORIDAD COMPETENTE</th>
</tr>';
if($policia!='NO')
{
	$entidad_autoridad='Policía';
	$funcionario_autoridad=$policia_apellido;
	$cargo_autoridad='&nbsp;';
	$placa_autoridad=$policia_placa;
}
else
if($transito!='NO')
{
	$entidad_autoridad='Transito';
	$funcionario_autoridad=$transito_apellido;
	$cargo_autoridad='&nbsp;';
	$placa_autoridad=$transito_placa;
}	
else
if($transito!='NO')
{
	$entidad_autoridad='Inspector';
	$funcionario_autoridad=$inspector_apellido;
	$cargo_autoridad='&nbsp;';
	$placa_autoridad=$inspector_placa;
}	


$dummy.='<tr>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">ENTIDAD</th>
<td width="'.round($tam*0.1875).'" class="ContenidoReporte" align="center" colspan="3">'.$entidad_autoridad.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">FUNCIONARIO</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" align="center" colspan="2">'.$funcionario_autoridad.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">CARGO</th>
<td width="'.round($tam*0.125).'" class="ContenidoReporte" align="center" colspan="2">'.$cargo_autoridad.'</td>
<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">PLACA</th>
<td width="'.round($tam*0.0625).'" class="ContenidoReporte" align="center">'.$placa_autoridad.'</td>
</tr>';


$dummy.='<tr >
<th  colspan="16" height="10" width="'.round($tam*1).'">&nbsp;</th>
</tr>';

$dummy.='<tr class="TituloReporte">
<th  colspan="16" width="'.round($tam*1).'" align="center">VEHICULOS INVOLUCRADOS</th>
</tr>';

$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente WHERE id_incidente=".$id_buscar." ORDER BY id_vehiculo";
$veh=$_SESSION[APL]->bd->getRs($sql);
if($veh->NumRows()==0)
$dummy.='
<tr >
<td  colspan="16" align="center" class="TituloReporte" width="'.round($tam*1).'" align="center">Sin Vehiculos</td></tr>';
else
{
	$v=1;
	
	while(!$veh->EOF)
	{
		$id_vehiculo=$veh->fields[0];
		$id_incidente=$veh->fields[1];
		$id_tipo_vehiculo=$veh->fields[2];
		$referencia_vehiculo=$veh->fields[3];
		$modelo_vehiculo=$veh->fields[4];
		$placa_vehiculo=$veh->fields[5];
		$color_vehiculo=$veh->fields[6];
		$soat_vehiculo=$veh->fields[7];
		$id_aseguradora=$veh->fields[8];
		$nro_heridos=$veh->fields[9];
		$nro_muertos=$veh->fields[10];
		$ocupantes=$veh->fields[11];
		$id_parqueadero=$veh->fields[12];
		$id_transito=$veh->fields[13];
		$id_taller=$veh->fields[14];
		$id_otro_vehiculo=$veh->fields[15];
		$observaciones=$veh->fields[16];
		$id_grua=$veh->fields[17];
		
		if($id_tipo_vehiculo!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado WHERE id=".$id_tipo_vehiculo."";
			$tveh=$_SESSION[APL]->bd->getRs($sql);
			$tveh_nombre=$tveh->fields[1];
		}
		else
			$tveh_nombre="&nbsp;";
		if($id_aseguradora!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_aseguradora WHERE id=".$id_aseguradora."";
			$aseg=$_SESSION[APL]->bd->getRs($sql);
			$c_seguro=$aseg->fields[1];
		}
		else
			$c_seguro="&nbsp;";
		
		
		$dummy.='<tr>
		<th rowspan="5" width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">VEHICULO '.$v.'</th>
		<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">TIPO</th>
		<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$tveh_nombre.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">LINEA</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$referencia_vehiculo.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">PLACA</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$placa_vehiculo.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">SOAT</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte" colspan="2">'.$soat_vehiculo.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" colspan="2" align="center">HERIDOS</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$nro_heridos.'</td>
		</tr>';
		$dummy.='
		<tr>
		<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">MARCA</th>
		<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$referencia_vehiculo.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">MODELO</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$modelo_vehiculo.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">COLOR</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$color_vehiculo.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">C.SEGUROS</th>
		<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$c_seguro.'</td>
		<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">MUERTOS</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$nro_muertos.'</td>
		</tr>';
		
		$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo WHERE id_vehiculo=".$id_vehiculo." AND conducia='SI'";
		$les_v=$_SESSION[APL]->bd->getRs($sql);
		if($les_v->NumRows()>0)
		{
			$nombre=$les_v->fields[5];
			$cedula=$les_v->fields[6];
			$telefono=$les_v->fields[7];
			$direccion=$les_v->fields[8];
			$ciudad='&nbsp;';
		}
		else
		{
			$nombre='No Disponible';
			$cedula='No Disponible';
			$telefono='No Disponible';
			$direccion='No Disponible';
			$ciudad='&nbsp;';
		}
		
		$dummy.='
		<tr>
		<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">CONDUCTOR</th>
		<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$nombre.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">CEDULA</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte" style="font-size=6px">'.$cedula.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">TELEFONO</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte" style="font-size=6px">'.$telefono.'</td>
		<th width="'.round($tam*0.0625).'" class="TituloReporte" align="center">DIRECCION</th>
		<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$direccion.'</td>
		<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">CIUDAD</th>
		<td width="'.round($tam*0.0625).'" class="ContenidoReporte">'.$ciudad.'</td>
		</tr>
		';
		$dummy.='
		<tr>
		<th rowspan="2" width="'.round($tam*0.1875).'" class="TituloReporte" colspan="3" align="center">TRASLADO POR GRUA DE LA CONCESION</th>
		<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">PARQUEADERO</th>
		<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">TRANSITO</th>
		<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">TALLER</th>
		<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">OTRO</th>
		<th width="'.round($tam*0.1875).'" class="TituloReporte" colspan="3" align="center">OBSERVACIONES</th>
		</tr>
		';
		
		if($id_parqueadero!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_parqueadero WHERE id=".$id_parqueadero."";
			$par=$_SESSION[APL]->bd->getRs($sql);
			$parqueadero=$par->fields[1];
		}
		else
			$parqueadero="&nbsp;";
		
		
		if($id_taller!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_taller WHERE id=".$id_taller."";
			$tal=$_SESSION[APL]->bd->getRs($sql);
			$taller=$tal->fields[1];
		}
		else
			$taller="&nbsp;";
		
		if($id_transito!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_transito WHERE id=".$id_transito."";
			$tra=$_SESSION[APL]->bd->getRs($sql);
			$transito=$tra->fields[1];
		}
		else
			$transito="&nbsp;";
		if($id_otro_vehiculo!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_vehiculo WHERE id=".$id_otro_vehiculo."";
			$otr=$_SESSION[APL]->bd->getRs($sql);
			$otro_vehiculo=$otr->fields[1];
		}
		else
			$otro_vehiculo="&nbsp;";
	
		$dummy.='
		<tr>
		<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$parqueadero.'</td>
		<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$transito.'</td>
		<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$taller.'</td>
		<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$otro_vehiculo.'</td>
		<td width="'.round($tam*0.1875).'" class="ContenidoReporte" colspan="3">'.wordwrap($observaciones,28,'<br>').'</td>
		</tr>
		';
		
		
		
		$v++;
		$veh->MoveNext();
	}
$dummy.='<tr >
<th  colspan="16" height="10" width="'.round($tam*1).'">&nbsp;</th>
</tr>';
	$veh->MoveFirst();
	$v=1;
	
	while(!$veh->EOF)
	{
		$dummy.='<tr class="TituloReporte">
		<th  colspan="16" width="'.round($tam*1).'" align="center">LESIONADOS VEHICULO '.$v.'</th>
		</tr>';
		$id_vehiculo=$veh->fields[0];
		$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo WHERE id_vehiculo=".$id_vehiculo;
		$les_v=$_SESSION[APL]->bd->getRs($sql);
		$l=1;
		if($les_v->NumRows()>0)
		{
			while(!$les_v->EOF)
			{
				$id_lesionado=$les_v->fields[0];
				$id_vehiculo=$les_v->fields[1];
				$id_trasladado_por=$les_v->fields[2];
				$conductor=$les_v->fields[3];
				$auxiliar_enfermeria=$les_v->fields[4];
				$nombre=$les_v->fields[5];
				$cedula=$les_v->fields[6];
				$telefono=$les_v->fields[7];
				$direccion=$les_v->fields[8];
				$diagnostico=$les_v->fields[9];
				$id_hospital=$les_v->fields[10];
				$id_clinica=$les_v->fields[11];
				$id_centro_salud=$les_v->fields[12];
				$id_otro_lesionado=$les_v->fields[13];
				$observaciones_les=$les_v->fields[14];
				$conducia_les=$les_v->fields[15];
				
			if($id_trasladado_por!='')
			{
				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia WHERE id=".$id_trasladado_por."";
				$tra_p=$_SESSION[APL]->bd->getRs($sql);
				$trasladado_por=wordwrap($tra_p->fields[1],28,'<br>');
			}
			else
				$trasladado_por="&nbsp;";	
				
			if($id_hospital!='')
			{
				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_hospital WHERE id=".$id_hospital."";
				$hos=$_SESSION[APL]->bd->getRs($sql);
				$hospital=wordwrap($hos->fields[1],28,'<br>');
			}
			else
				$hospital="&nbsp;";
			if($id_centro_salud!='')
			{
				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_centro_salud WHERE id=".$id_centro_salud."";
				$censa=$_SESSION[APL]->bd->getRs($sql);
				$centro_salud=wordwrap($censa->fields[1],28,'<br>');
			}
			else
				$centro_salud="&nbsp;";
			if($id_clinica!='')
			{
				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_clinica WHERE id=".$id_clinica."";
				$cli=$_SESSION[APL]->bd->getRs($sql);
				$clinica=wordwrap($cli->fields[1],28,'<br>');
			}
			else
				$clinica="&nbsp;";
			
			$dummy.='<tr>
			<th rowspan="3" width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center">LESIONADO '.$l.'</th>
			<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center" style="font-size=8px">NOMBRE</th>
			<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$nombre.'</td>
			<th width="'.round($tam*0.0625).'" class="TituloReporte"  align="center" style="font-size=8px">CEDULA</th>
			<td width="'.round($tam*0.0625).'" class="ContenidoReporte" style="font-size=6px">'.$cedula.'</td>
			<th width="'.round($tam*0.0625).'" class="TituloReporte"  align="center" style="font-size=8px">TELEFONO</th>
			<td width="'.round($tam*0.0625).'" class="ContenidoReporte" style="font-size=6px">'.$telefono.'</td>
			<th width="'.round($tam*0.0625).'" class="TituloReporte"  align="center" style="font-size=8px">DIRECCION</th>
			<td width="'.round($tam*0.125).'" class="ContenidoReporte" colspan="2">'.$direccion.'</td>
			<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2"  align="center" style="font-size=8px">CIUDAD</th>
			<td width="'.round($tam*0.0625).'" class="ContenidoReporte" >&nbsp;</td>
			</tr>';
			$dummy.='<tr>
			<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center" style="font-size=8px">TRASLADADO POR</th>
			<td width="'.round($tam*0.1875).'" class="ContenidoReporte" colspan="3">'.$trasladado_por.'</td>
			<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center" style="font-size=8px">HOSPITAL/CLINICA</th>
			<td width="'.round($tam*0.25).'" class="ContenidoReporte"  colspan="4">'.$hospital.' '.$centro_salud.' '.$clinica.'</td>
			<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2"  align="center" style="font-size=8px">CIUDAD</th>
			<td width="'.round($tam*0.0625).'" class="ContenidoReporte" >&nbsp;</td>
			</tr>';
			$dummy.='<tr>
			<th width="'.round($tam*0.125).'" class="TituloReporte" colspan="2" align="center"	style="font-size=8px">OBSERVACIONES</th>
			<td width="'.round($tam*0.75).'" class="ContenidoReporte" colspan="12">'.wordwrap($observaciones_les,28,'<br>').'&nbsp;</td>
			</tr>';
			
			
			$l++;
			$les_v->MoveNext();
			}
		}
		else
			$dummy.='<tr >
			<td  colspan="16" align="center" class="TituloReporte" width="'.round($tam*1).'">Sin Lesionados</td></tr>';
		
		$dummy.='<tr >
<th  colspan="16" height="10" width="'.round($tam*1).'">&nbsp;</th>
</tr>';
		$v++;
		$veh->MoveNext();
	}
	
	
}









$dummy.='</table>';
if($firma!='')
	$dummy.='<table><tr><td align="left"><br><br>Firma Funcionario Registra<br><img src="firmas/'.$firma.'" height="40"><br><hr></td></tr></table>';
else
	$dummy.='<table><tr><td align="center">USUARIO '.strtoupper($nombres).' '.strtoupper($apellidos).' SIN FIRMA REGISTRADA</td></tr></table>';
$dummy.='</body></html>';

$html2pdf->WriteHTML($dummy);
$html2pdf->Output('reporte_accidente.pdf','D');



?>