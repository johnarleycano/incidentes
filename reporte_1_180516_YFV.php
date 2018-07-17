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
//HTML2PDF();
//'P','A4','en'

// $html2pdf -> pdf->SetDisplayMode('fullpage'); 

//<link href="css/adminpdf.css" rel="stylesheet" type="text/css">
//<link href="css/plantillas.css" rel="stylesheet" type="text/css">
$dummy='
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<title>Reporte Accidente</title>
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
		$hora_reporte=explode(":",$inci->fields[2]);
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




$dummy.='
<table style="width:550"   border="0" cellpadding="1" cellspacing="3">
<tr class="TituloReporte">
<td colspan="12" align="center">Datos Generales Central SOS</td>
</tr>
<tr class="TituloReporte">
<th  colspan="6">INFORMACION BASICA</th>
<th  colspan="6" align="rigth">'.$periodo.".".str_pad($codigo,5,"0",STR_PAD_LEFT).'</th>
</tr>
<tr>
<th colspan="3"  class="TituloReporte"  style="width:70">Fecha</th>
<th colspan="5"  class="TituloReporte">Reportado por</th>
<th colspan="4"  class="TituloReporte">Operador SOS</th>
</tr>
<tr>
<th class="TituloReporte" >Dia</th>
<th class="TituloReporte" >Mes</th>
<th class="TituloReporte" >Año</th>
<td class="ContenidoReporte" colspan="5" align="center">';



if(isset($id_buscar) && $informado_por=='P') 
$dummy.='Policía de tránsito y transporte';
else
if(isset($id_buscar) && $informado_por=='U')
$dummy.='Usuario';
else
if(isset($id_buscar) && $informado_por=='O')
$dummy.='Operario';
else
if(isset($id_buscar) && $informado_por=='V')
$dummy.='Administrador Vial';
else
if(isset($id_buscar) && $informado_por=='M')
$dummy.='Personal Mantenimiento Vial';
else
if(isset($id_buscar) && $informado_por=='E')
$dummy.='Ejercito Nacional';
else
if(isset($id_buscar) && $informado_por=='N')
$dummy.='Policía Nacional';
else
$dummy.='&nbsp;';
$dummy.='</td>
<td class="ContenidoReporte" colspan="4" align="center">'.wordwrap($nombre_usuario,20,'<br>').'</td>
</tr>
<tr>
<td class="ContenidoReporte" align="center">'.$dia_rep.'</td>
<td class="ContenidoReporte" align="center">'.$mes_rep.'</td>
<td class="ContenidoReporte" align="center">'.$ano_rep.'</td>
<th class="TituloReporte">Nombre</th>
<td class="ContenidoReporte" colspan="4" align="center">'.$informado_por_nombre.'</td>
<th class="TituloReporte">Cedula</th>
<td class="ContenidoReporte" colspan="3" align="center">'.$identificacion_usuario.'</td>
</tr>
<tr>
<th colspan="2" class="TituloReporte" style="width:40">Hora Reporte</th>
<th colspan="2" class="TituloReporte">Hora Salida<br>de Base</th>
<th colspan="2" class="TituloReporte">Hora Llegada<br>al Sitio</th>
<th colspan="2" class="TituloReporte">Hora Salida<br>del Sitio</th>
<th colspan="2" class="TituloReporte">Hora LLegada<br>a Base</th>
<th colspan="2" class="TituloReporte">Hora Estimada<br>Llegada</th>
</tr>
<tr>
<th  class="TituloReporte"  >HH</th>
<th  class="TituloReporte" >MI</th>
<th  class="TituloReporte" >HH</th>
<th  class="TituloReporte" >MI</th>
<th  class="TituloReporte" >HH</th>
<th  class="TituloReporte" >MI</th>
<th  class="TituloReporte" >HH</th>
<th  class="TituloReporte" >MI</th>
<th  class="TituloReporte" >HH</th>
<th  class="TituloReporte" >MI</th>
<td colspan="2" rowspan="2" class="ContenidoReporte" align="center">';
if(isset($id_buscar) && $absicsa_evento_p1!='' && $absicsa_salida_p1!='' && $hora_salida_base_h!='' && $hora_salida_base_m!='')
{

	$tiempo=$absicsa_evento_p1-$absicsa_salida_p1;
	$minus=0;
	$horas=0;
	$horas_t=0;
	$minus_t=0;
	if($tiempo>60)
	{
		$horas=$tiempo/60-($tiempo%60);
		$tiempo=$tiempo-(60*$horas);
	}
	
	$horas_t=$hora_salida_base_h+$horas;
	$minus_t=$hora_salida_base_m+$tiempo;
	
	if($horas_t>24)
	{
		$horas_t=$horas_t-24;
	}
	
	if($minus_t>=60)
	{
		$minus_t=$minus_t-60;
		$horas_t=$horas_t+1;
	}
	$hora_estimada_llegada=str_pad($horas_t,2,'0',STR_PAD_LEFT).':'.str_pad($minus_t,2,'0',STR_PAD_LEFT);
	$dummy.= $hora_estimada_llegada;
}
else
$dummy.="&nbsp;";
$dummy.='</td></tr>
<tr>
<td class="ContenidoReporte" align="center">'.$hora_rep.'</td>
<td class="ContenidoReporte" align="center">'.$minu_rep.'</td>
<td class="ContenidoReporte" align="center">'.$hora_salida_base_h.'</td>
<td class="ContenidoReporte" align="center">'.$hora_salida_base_m.'</td>
<td class="ContenidoReporte" align="center">'.$hora_llegada_sitio_h.'</td>
<td class="ContenidoReporte" align="center">'.$hora_llegada_sitio_m.'</td>
<td class="ContenidoReporte" align="center">'.$hora_salida_sitio_h.'</td>
<td class="ContenidoReporte" align="center">'.$hora_salida_sitio_m.'</td>
<td class="ContenidoReporte" align="center">'.$hora_llegada_base_h.'</td>
<td class="ContenidoReporte" align="center">'.$hora_llegada_base_m.'</td>
</tr>
<tr>
<th colspan="2" class="TituloReporte" style="width:40;text-align:center">Tiempo Reaccion</th>
<td class="ContenidoReporte" align="center" colspan="2">';
if(isset($id_buscar) && $hora_salida_base_h!='' && $hora_salida_base_m!='')	
	{
		$hora_r=$hora_rep;
		$hora_s=$hora_salida_base_h;
		
		$minu_r=$minu_rep;
		$minu_s=$hora_salida_base_m;
		if($minu_r>$minu_s)
		{
			$minu_s=$minu_s+60;	
			$hora_s=$hora_s-1;
		}

		$horas=$hora_s-$hora_r;
		$minus=$minu_s-$minu_r;
		if($horas<0)
		{

			$tiempo_reaccion='';	
		}
		else

			$tiempo_reaccion=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
		$dummy.= $tiempo_reaccion;
	}
else
$dummy.='&nbsp;';

$dummy.='</td>
<th colspan="2"  class="TituloReporte">Tiempo<br>Respuesta</th>
<td class="ContenidoReporte" align="center" colspan="2">';
if(isset($id_buscar) && $hora_llegada_sitio_h!='' && $hora_llegada_sitio_m!='' && $hora_salida_sitio_h!='' && $hora_salida_sitio_m!='')
{
	$hora_r=$hora_llegada_sitio_h;
	$hora_s=$hora_salida_sitio_h;
	
	$minu_r=$hora_llegada_sitio_m;
	$minu_s=$hora_salida_sitio_m;
	if($minu_r>$minu_s)
	{
		$minu_s=$minu_s+60;	
		$hora_s=$hora_s-1;
	}

	$horas=$hora_s-$hora_r;
	$minus=$minu_s-$minu_r;
	if($horas<0)
	{		
		$tiempo_respuesta='';	
	}
	else
		$tiempo_respuesta=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
	$dummy.=$tiempo_respuesta;
}
else
$dummy.='&nbsp;';
$dummy.='</td>
<th colspan="2" class="TituloReporte">Duracion Total del Evento</th>
<td class="ContenidoReporte" align="center" colspan="2">';

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
		$dummy.= $tiempo_total;
	}
else
$dummy.='&nbsp;';

$dummy.='</td>
</tr>
<tr>
<th colspan="3" class="TituloReporte" style="width:70;text-align:center">Tramo</th>
<td class="ContenidoReporte" style="width:200;" colspan="3">'.wordwrap($via,30,'<br>').'</td>
<th colspan="2" class="TituloReporte">Sentido</th>
<th colspan="2" class="TituloReporte">Abscisa<br>de Salida</th>
<th colspan="2" class="TituloReporte">Abscisa<br>del Evento</th>
</tr>
<tr>
<th colspan="3" class="TituloReporte" style="text-align: center;width:70">Tipo de Atencion (Comentario)</th>
<td class="ContenidoReporte" align="center" colspan="3">'.$tipo_atencion.'</td>
<td class="ContenidoReporte" align="center" colspan="2" rowspan="2">'.$sentido.'</td>
<td class="ContenidoReporte" align="center"  rowspan="2">'.$absicsa_salida_p1.'</td>
<td class="ContenidoReporte" align="center"  rowspan="2">'.$absicsa_salida_p2.'</td>
<td class="ContenidoReporte" align="center"  rowspan="2">'.$absicsa_evento_p1.'</td>
<td class="ContenidoReporte" align="center"  rowspan="2">'.$absicsa_evento_p2.'</td>
</tr>
<tr>
<th colspan="3" class="TituloReporte" style="text-align: center;width:70">Sitio de Referencia</th>
<td class="ContenidoReporte" align="center" colspan="3">'.$nombre_referencia.'</td>
</tr>


<tr class="TituloReporte">
<th  colspan="12">APOYO EN ATENCION</th>
</tr>';
$dummy.='
<tr style="padding:0px">
<td colspan="12" style="padding:0px">
<table border="1">
<tbody>
<tr>
<td colspan="7"><img src="img/blanco.png" height="100"></td>
</tr>
<tr>
<th  class="TituloReporte" style="text-align: center;width:200;">Entidad</th>
<th  class="TituloReporte" style="text-align: center;width:200;">Funcionario</th>
<th  class="TituloReporte" style="text-align: center;;">Salida Base</th>
<th  class="TituloReporte" style="text-align: center;">Llegada Sitio</th>
<th  class="TituloReporte" style="text-align: center;">Salida Sitio</th>
<th  class="TituloReporte" style="text-align: center;">Llegada Base</th>
<th  class="TituloReporte" style="text-align: center;">Tiempo Total</th>
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
	
	$dummy.='<tr>
				<td class="ContenidoReporte" align="center"  >'.$apo_enti->fields[1].'</td>
				<td class="ContenidoReporte" align="center"  >'.$apo_enti->fields[2].'</td>
				<td class="ContenidoReporte" align="center"  >'.$apo_enti->fields[3].'</td>
				<td class="ContenidoReporte" align="center"  >'.$apo_enti->fields[4].'</td>
				<td class="ContenidoReporte" align="center"  >'.$apo_enti->fields[5].'</td>
				<td class="ContenidoReporte" align="center"  >'.$apo_enti->fields[6].'</td>';
				
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
				
				
				
				
			$dummy.='<td class="ContenidoReporte" align="center">'.$tiempo_a.'</td>
			</tr>';

	$apo_enti->MoveNext();
}

$dummy.='

</tbody></table></td></tr>

<tr class="TituloReporte">
<th  colspan="12">VEHICULOS INVOLUCRADOS</th>
</tr>';



$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente WHERE id_incidente=".$id_buscar." ORDER BY id_vehiculo";
$veh=$_SESSION[APL]->bd->getRs($sql);
if($veh->NumRows()==0)
$dummy.='
<tr >
<td  colspan="12" align="center" class="TituloReporte2">Sin Vehiculos
</td></tr>';

else
{
$dummy.='
<tr style="padding:0px">
<td  colspan="12" align="center" style="padding:0px">
<table  border="0" cellpadding="1" cellspacing="3" style="width:100%">
<tr>
<th class="TituloReporte2" rowspan="3" style="width=12">&nbsp;&nbsp;&nbsp;</th>
<th class="TituloReporte2" rowspan="3" style="width=65">Tipo</th>
<th class="TituloReporte2" rowspan="2" style="width=74">Referencia</th>
<th class="TituloReporte2" rowspan="2" style="width=54">Placa</th>
<th class="TituloReporte2" rowspan="2" style="width=86">SOAT Vehiculo</th>
<th class="TituloReporte2" rowspan="2" style="width=57" >Heridos</th>
<th class="TituloReporte2" rowspan="2" style="width=71">Muertos</th>
<th class="TituloReporte2" colspan="2">Sitio de Traslado de Vehiculos</th>
<th class="TituloReporte2" rowspan="3" style="width=99">Observaciones</th>
</tr>
<tr>

<th class="TituloReporte2" style="width=85">Parqueadero</th>
<th class="TituloReporte2" style="width=54">Taller</th>
</tr>
<tr>
<th class="TituloReporte2">Modelo</th>
<th class="TituloReporte2">Color</th>
<th class="TituloReporte2">Aseguradora</th>
<th class="TituloReporte2" colspan="2">Grua</th>

<th class="TituloReporte2">Transito</th>
<th class="TituloReporte2">Otro</th>
</tr>
';


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
	
	
	$dummy.='<tr>
	<th class="TituloReporte2" rowspan="4">&nbsp;&nbsp;&nbsp;</th>
	<td  align="center" class="ContenidoReporte2" rowspan="2" >';
	if($id_tipo_vehiculo!='')
	{
		$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado WHERE id=".$id_tipo_vehiculo."";
		$tveh=$_SESSION[APL]->bd->getRs($sql);
		$dummy.=$tveh->fields[1];
	}
	else
		$dummy.="&nbsp;";
		
	$dummy.='</td>
	<td class="ContenidoReporte2" align="center">'.$referencia_vehiculo.'</td>
	<td class="ContenidoReporte2" align="center">'.$placa_vehiculo.'</td>
	<td class="ContenidoReporte2" align="center">'.$soat_vehiculo.'</td>
	<td class="ContenidoReporte2" align="center" >'.$nro_heridos.'</td>
	<td class="ContenidoReporte2" align="center" >'.$nro_muertos.'</td>
	<td class="ContenidoReporte2" align="center" >';
	
	if($id_parqueadero!='')
	{
		$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_parqueadero WHERE id=".$id_parqueadero."";
		$par=$_SESSION[APL]->bd->getRs($sql);
		$dummy.=$par->fields[1];
	}
	else
		$dummy.="&nbsp;";
		
	$dummy.='</td>
	
	<td align="center"  class="ContenidoReporte2">';
	
	if($id_taller!='')
	{
		$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_taller WHERE id=".$id_taller."";
		$tal=$_SESSION[APL]->bd->getRs($sql);
		$dummy.=$tal->fields[1];
	}
	else
		$dummy.="&nbsp;";
	
	$dummy.='</td>
	<td  class="ContenidoReporte2"  align="center"  rowspan="2">'.wordwrap($observaciones,28,'<br>').'</td>
	</tr>
	<tr>
	<td  class="ContenidoReporte2"  align="center">'.$modelo_vehiculo.'</td>
	<td  class="ContenidoReporte2"  align="center"  >'.$color_vehiculo.'</td>
	<td  class="ContenidoReporte2"  align="center"  >';
		
	if($id_aseguradora!='')
	{
		$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_aseguradora WHERE id=".$id_aseguradora."";
		$aseg=$_SESSION[APL]->bd->getRs($sql);
		$dummy.=$aseg->fields[1];
	}
	else
		$dummy.="&nbsp;";
	
	$dummy.='</td>
	<td  class="ContenidoReporte2"  align="center" colspan="2" >';
		
	if($id_grua!='')
	{
		$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_grua WHERE id=".$id_grua."";
		$grua=$_SESSION[APL]->bd->getRs($sql);
		$dummy.=$grua->fields[1];
	}
	else
		$dummy.="&nbsp;";
	
	$dummy.='</td>		

	
	<td  class="ContenidoReporte2"  align="center" >';
	if($id_transito!='')
	{
		$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_transito WHERE id=".$id_transito."";
		$tra=$_SESSION[APL]->bd->getRs($sql);
		$dummy.=$tra->fields[1];
	}
	else
		$dummy.="&nbsp;";
		
	$dummy.='</td>
	<td  class="ContenidoReporte2"  align="center">';
	if($id_otro_vehiculo!='')
	{
		$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_vehiculo WHERE id=".$id_otro_vehiculo."";
		$otr=$_SESSION[APL]->bd->getRs($sql);
		$dummy.=$otr->fields[1];
	}
	else
		$dummy.="&nbsp;";

	$dummy.='</td></tr>';
	
	$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo WHERE id_vehiculo=".$id_vehiculo;
	$les_v=$_SESSION[APL]->bd->getRs($sql);
		
	if($les_v->NumRows()>0)
	{
	$dummy.='<tr>
	<td colspan="9" align="right" style="padding:0px">
	<table border="0" cellpadding="1" cellspacing="3" style="width:100%">
	<tr>
	
	<th class="TituloReporte3" rowspan="2" style="width=12">&nbsp;&nbsp;&nbsp;</th>
	<th class="TituloReporte3" style="width=109">Trasladador Por</th>
	<th class="TituloReporte3" style="width=133">Auxiliar Enfermeria</th>
	<th class="TituloReporte3" style="width=59">Nombre</th>
	<th class="TituloReporte3" style="width=64">Cedula</th>

	<th class="TituloReporte3" style="width=56">Hospital</th>
	<th class="TituloReporte3" style="width=105">Centro Salud</th>
	<th class="TituloReporte3" rowspan="2" style="width=104">Observaciones</th>
	</tr>
	<tr >
	<th class="TituloReporte3">Conductor</th>
	<th class="TituloReporte3">Diagnostico</th>
	<th class="TituloReporte3">Direccion</th>
	<th class="TituloReporte3">Telefono</th>
	<th class="TituloReporte3">Clinica</th>
	<th class="TituloReporte3">Estado</th>
	</tr>
	
	
	';

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
		
		$dummy.='<tr>
		<th class="TituloReporte3" rowspan="3">';
		if($conducia_les=='SI')
		$dummy.='Conductor<br>Vehiculo';
		else
		$dummy.='&nbsp;&nbsp;&nbsp;';
		
		$dummy.='</th><td  class="ContenidoReporte3" style="width=50px">';
		if($id_trasladado_por!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia WHERE id=".$id_trasladado_por."";
			$tra_p=$_SESSION[APL]->bd->getRs($sql);
			$dummy.=wordwrap($tra_p->fields[1],28,'<br>');
		}
		else
			$dummy.="&nbsp;";
		$dummy.='</td>
		<td  class="ContenidoReporte3" style="width=50px">'.wordwrap($auxiliar_enfermeria,28,'<br>').'&nbsp;</td>
		<td  class="ContenidoReporte3" style="width=50px">'.wordwrap($nombre,28,'<br>').'&nbsp;</td>
		<td  class="ContenidoReporte3" style="width=50px">'.wordwrap(number_format(str_replace('.','',str_replace(' ','',$cedula)),0,',','.'),28,'<br>').'&nbsp;</td>

		<td class="ContenidoReporte3" style="width=50px">';
		if($id_hospital!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_hospital WHERE id=".$id_hospital."";
			$hos=$_SESSION[APL]->bd->getRs($sql);
			$dummy.=wordwrap($hos->fields[1],28,'<br>');
		}
		else
			$dummy.="&nbsp;";
		$dummy.='</td>
		<td class="ContenidoReporte3">';
		if($id_centro_salud!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_centro_salud WHERE id=".$id_centro_salud."";
			$censa=$_SESSION[APL]->bd->getRs($sql);
			$dummy.=wordwrap($censa->fields[1],28,'<br>');
		}
		else
			$dummy.="&nbsp;";
		$dummy.='</td>
		<td rowspan="2" class="ContenidoReporte3">'.wordwrap($observaciones_les,28,'<br>').'&nbsp;</td>
		</tr>
		<tr>
		<td class="ContenidoReporte3" style="width=50px">'.wordwrap($conductor,28,'<br>').'&nbsp;</td>
		<td class="ContenidoReporte3" style="width=50px">'.wordwrap($diagnostico,28,'<br>').'&nbsp;</td>
		<td class="ContenidoReporte3" style="width=50px">'.wordwrap($direccion,28,'<br>').'&nbsp;</td>
		<td class="ContenidoReporte3" style="width=50px">'.wordwrap($telefono,28,'<br>').'&nbsp;</td>
		<td class="ContenidoReporte3" style="width=50px">';
		if($id_clinica!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_clinica WHERE id=".$id_clinica."";
			$cli=$_SESSION[APL]->bd->getRs($sql);
			$dummy.=wordwrap($cli->fields[1],28,'<br>');
		}
		else
			$dummy.="&nbsp;";
		$dummy.='
		</td>
		<td class="ContenidoReporte3" style="width=50px">';
		if($id_otro_lesionado!='')
		{
			$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_lesionado WHERE id=".$id_otro_lesionado."";
			$otrl=$_SESSION[APL]->bd->getRs($sql);
			$dummy.=wordwrap($otrl->fields[1],28,'<br>');
		}
		else
			$dummy.="&nbsp;";
		$dummy.='</td>
		</tr>
		<tr>
		<td colspan="7" class="TituloReporte3">&nbsp;
		</td>
		</tr>
		';
		
		
		$les_v->MoveNext();
	}
	$dummy.='
	
	
	</table></td></tr>
	
	';
	}
	else
	$dummy.='<tr>
	<th colspan="9" align="center"  class="ContenidoReporte3">Sin Lesionados</th></tr>';
	$dummy.='<tr>
		<td colspan="9" class="TituloReporte2">&nbsp;
		</td>
		</tr>';

	$veh->MoveNext();
}
$dummy.='
</table>

</td>
</tr>';


}



$dummy.='
<tr>
<th colspan="2" class="TituloReporte">Heridos</th>
<td class="ContenidoReporte" align="center">'.$nro_heridos_total.'</td>
<th colspan="2" class="TituloReporte">Muertos</th>
<td class="ContenidoReporte" align="center">'.$nro_muertos_total.'</td>

<td class="ContenidoReporte" align="center"  colspan="3">&nbsp;</td>
<td class="ContenidoReporte" align="center"  colspan="3">&nbsp;</td>
</tr>

<tr class="TituloReporte">
<th  colspan="12">AUTORIDAD COMPETENTE</th>
</tr>
<tr>
<th colspan="2" class="TituloReporte">Policia Transito y Transporte</th>
<td class="ContenidoReporte" align="center">'.$policia.'</td>
<th class="TituloReporte">Placa</th>
<td class="ContenidoReporte" align="center" colspan="2">'.$policia_placa.'</td>
<th colspan="2" class="TituloReporte">Apellido</th>
<td class="ContenidoReporte" align="center" colspan="4">'.$policia_apellido.'</td>
</tr>
<tr>
<th colspan="2" class="TituloReporte">Transito</th>
<td class="ContenidoReporte" align="center">'.$transito.'</td>
<th class="TituloReporte">Placa</th>
<td class="ContenidoReporte" align="center" colspan="2">'.$transito_placa.'</td>
<th colspan="2" class="TituloReporte">Apellido</th>
<td class="ContenidoReporte" align="center" colspan="4">'.$transito_apellido.'</td>
</tr>
<tr>
<th colspan="2" class="TituloReporte">Inspector</th>
<td class="ContenidoReporte" align="center">'.$inspector.'</td>
<th class="TituloReporte">Placa</th>
<td class="ContenidoReporte" align="center" colspan="2">'.$inspector_placa.'</td>
<th colspan="2" class="TituloReporte">Apellido</th>
<th class="ContenidoReporte" align="center" colspan="4">'.$inspector_apellido.'</th>
</tr>
</table>';
if($firma!='')
	$dummy.='<table><tr><td align="left"><br><br>Firma Funcionario Registra<br><img src="firmas/'.$firma.'" height="40"><br><hr></td></tr></table>';
else
	$dummy.='<table><tr><td align="center">USUARIO '.strtoupper($nombres).' '.strtoupper($apellidos).' SIN FIRMA REGISTRADA</td></tr></table>';

$dummy.='</body></html>';
echo $dummy;
$html2pdf->WriteHTML($dummy);
$html2pdf->Output('reporte_accidente.pdf','D');

?>
