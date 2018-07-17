<?php
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}

require_once('html2pdf_v3.31/html2pdf.class.php');

//require_once("dompdf/dompdf_config.inc.php");


$html2pdf = new HTML2PDF('P','A4','en');
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
	i.fecha,
	hora_reporte,
	i.referencia,
	v.nombre,
	i.tipo_atencion,
	ta.nombre,
	i.estado,
	r.abscisa,
	r.tramo_ruta,
	r.referencia,
	transito,
	transito_placa,
	transito_apellido,
	policia,
	policia_placa,
	policia_apellido,
	inspector,
	inspector_placa,
	inspector_apellido,
	señalizacion_horizontal,
	señalizacion_horizontal_obs,
	señalizacion_vertical,
	señalizacion_vertical_obs,
	mantenimiento_gral,
	mantenimiento_gral_obs,
	rodadura,
	rodadura_obs,
	otras_caracteristicas,
	nro_heridos,
	lesiones_personales_obs,
	daños_terceros,
	daños_terceros_obs,
	nro_muertos,
	descripcion_evento,
	'',
	'',
	'',
	'',
	guardado_sos,
	finalizado_sos,
	guardado_adm_vial,
	finalizado_adm_vial,
	periodo,
	codigo,
	abscisa_real,
	i.fechaincidente,
	horaincidente
	FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia  as r,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via as v,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion as ta
	
	
	 WHERE 
	 
	 i.tipo_atencion=ta.id and
	 i.via=v.id and
	 i.referencia=r.id and
	 i.id=?";
	$inci=$_SESSION[APL]->bd->getRsO($sql,$parametro);
	$id_buscar=$inci->fields[0];
	$fecha=$inci->fields[1];
	$fecInc=$inci->fields[46];

	if( $fecInc=="" )
		$fecInc = $fecha;

	if($fecInc!='')
	{
		$fecha_reporte=explode("-",$fecInc);
		$ano_rep=$fecha_reporte[0];
		$mes_rep=$fecha_reporte[1];
		$dia_rep=$fecha_reporte[2];
	}
	else
	{
		$ano_rep="";
		$mes_rep="";
		$dia_rep="";
	}
	
	
	// if($inci->fields[2]!='')//original
	if($inci->fields[47]!='')
	{
		// $hora_reporte=explode(":",$inci->fields[2]);//original
		$horaincidente=explode(":",$inci->fields[47]);
		$hora_rep=$horaincidente[0];
		$minu_rep=$horaincidente[1];
	}
	else
	{
		$hora_rep="";
		$minu_rep="";
	}
	$referencia=$inci->fields[3];
	$via=$inci->fields[4];
	$tipo_atencion_id=$inci->fields[5];	
	$tipo_atencion=$inci->fields[6];	
	$estado=$inci->fields[7];
	if($inci->fields[45]!='')
		$abscisa=$inci->fields[45];
	else
		$abscisa=$inci->fields[8];	
	$tramo_ruta=$inci->fields[9];
	$nombre_referencia=$inci->fields[10];
	
	$transito=$inci->fields[11];
	$transito_placa=$inci->fields[12];
	$transito_apellido=$inci->fields[13];
	$policia=$inci->fields[14];
	$policia_placa=$inci->fields[15];
	$policia_apellido=$inci->fields[16];
	$inspector=$inci->fields[17];
	$inspector_placa=$inci->fields[18];
	$inspector_apellido=$inci->fields[19];
	$señalizacion_horizontal=$inci->fields[20];
	$señalizacion_horizontal_obs=$inci->fields[21];
	$señalizacion_vertical=$inci->fields[22];
	$señalizacion_vertical_obs=$inci->fields[23];
	$mantenimiento_gral=$inci->fields[24];
	$mantenimiento_gral_obs=$inci->fields[25];
	$rodadura=$inci->fields[26];
	$rodadura_obs=$inci->fields[27];
	$otras_caracteristicas=$inci->fields[28];
	$nro_heridos=$inci->fields[29];
	$lesiones_personales_obs=$inci->fields[30];
	$daños_terceros=$inci->fields[31];
	$daños_terceros_obs=$inci->fields[32];
	$nro_muertos=$inci->fields[33];
	$descripcion_evento=$inci->fields[34];
	$imagen1=$inci->fields[35];
	$imagen2=$inci->fields[36];
	$imagen3=$inci->fields[37];
	$imagen4=$inci->fields[38];
	$guardado_sos=$inci->fields[39];
	$finalizado_sos=$inci->fields[40];
	$guardado_adm_vial=$inci->fields[41];
	$finalizado_adm_vial=$inci->fields[42];
	$periodo=$inci->fields[43];
	$codigo=$inci->fields[44];
	
	$parametro=array('id'=>$id_buscar);
	$sql="SELECT
	firma,
	nombres,
	apellidos
	FROM
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente as h,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u
	WHERE
	
	h.id_incidente=? and
	 h.estado=5 and 
	 h.usuario=u.id";
	 $adm_vial=$_SESSION[APL]->bd->getRsO($sql,$parametro);
	if($adm_vial->NumRows()==0)
	{
		$firma='';
		$nombres='';
		$apellidos='';
	}
	else
	{
		$firma=$adm_vial->fields[0];
		$nombres=$adm_vial->fields[1];
		$apellidos=$adm_vial->fields[2];
	}
$tam=710;

$dummy.='
<table  border="0" cellpadding="1" cellspacing="3">

  <tr height="22">
    <th colspan="6" height="22" width="'.($tam*1).'" class="TituloReporte">INFORME ADMINISTRADOR VIAL DE EVENTUALIDADES SOBRE LA VIA</th>
  </tr>
  <tr class="TituloReporte">
<th  colspan="3" width="'.round($tam*0.318442153).'">INFORMACION BASICA</th>
<th  colspan="3" align="rigth" width="'.round($tam*0.681557847).'">'.$periodo.".".str_pad($codigo,5,"0",STR_PAD_LEFT).'</th>
</tr>
  <tr height="18">
    <th colspan="6" height="18" class="TituloReporte2" width="'.($tam*1).'">DETALLES DEL EVENTO</th>
  </tr>
  <tr height="16">
    <th height="16" class="ContenidoReporte2" width="'.round($tam*0.09163803).'">HORA:</th>
    <td class="ContenidoReporte" width="'.round($tam*0.022909507).'">'.$hora_rep.':'.$minu_rep.'</td>
    <th class="ContenidoReporte2" width="'.round($tam*0.203894616).'">Fecha:&nbsp;&nbsp;</th>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">Día&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$dia_rep.'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">Mes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$mes_rep.'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">Año&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$ano_rep.'</td>
  </tr>
  <tr height="18">
    <th colspan="6" height="18" width="'.($tam*1).'" class="TituloReporte2">CARACTERISTICAS DEL EVENTO</th>
  </tr>
  <tr height="28">
    <th height="28" width="'.round($tam*0.09163803).'" class="ContenidoReporte2">Tramo Vial</th>
    <td colspan="2" width="'.round($tam*0.226804124).'" class="ContenidoReporte">'.$via.'</td>
    <th width="'.round($tam*0.1534937).'" class="ContenidoReporte2">Abscisa y Sitio Conocido</th>
    <td colspan="2" width="'.round($tam*0.528064147).'" class="ContenidoReporte">'.$nombre_referencia.' '.$abscisa.' '.$tramo_ruta.'</td>
  </tr>
  <tr height="16">
    <th height="32" width="'.round($tam*0.09163803).'" class="ContenidoReporte2">Tipo</th>
    <td width="'.round($tam*0.90836197).'" class="ContenidoReporte" colspan="5">'.$tipo_atencion.'</td>

  </tr>
  <tr height="18">
    <th colspan="6" height="18" class="TituloReporte2" width="'.($tam*1).'">CARACTERISTICAS GENERALES DEL SITIO DEL EVENTO</th>
  </tr>
  <tr height="16">
    <th colspan="2" rowspan="2" height="32" class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Señalización Horizontal</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.203894616).'">Bueno</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.1534937).'">Regular</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.18327606).'">Malo</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.344788087).'">Observaciones</th>
  </tr>
  <tr height="16">
    <td height="16" class="ContenidoReporte" width="'.round($tam*0.203894616).'">'.($señalizacion_horizontal=='B'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">'.($señalizacion_horizontal=='R'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">'.($señalizacion_horizontal=='M'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">'.$señalizacion_horizontal_obs.'&nbsp;</td>
  </tr>
  <tr height="16">
    <th colspan="2" height="16" class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Señalización Vertical</th>
    <td class="ContenidoReporte" width="'.round($tam*0.203894616).'">'.($señalizacion_vertical=='B'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">'.($señalizacion_vertical=='R'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">'.($señalizacion_vertical=='M'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">'.$señalizacion_vertical_obs.'&nbsp;</td>
  </tr>
  <tr height="16">
    <th colspan="2" height="16" class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Mantenimiento General</th>
    <td class="ContenidoReporte" width="'.round($tam*0.203894616).'">'.($mantenimiento_gral=='B'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">'.($mantenimiento_gral=='R'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">'.($mantenimiento_gral=='M'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">'.$mantenimiento_gral_obs.'&nbsp;</td>
  </tr>
  <tr height="16">
    <th colspan="2" height="16" class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Rodadura</th>
    <td class="ContenidoReporte" width="'.round($tam*0.203894616).'">'.($rodadura=='B'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">'.($rodadura=='R'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">'.($rodadura=='M'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">'.$rodadura_obs.'&nbsp;</td>
  </tr>
  <tr height="16">
    <th colspan="2" height="16"class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Otras    caracter&iacute;sticas</th>
    <td class="ContenidoReporte" colspan="4" width="'.round($tam*0.885452463).'">'.$otras_caracteristicas.'&nbsp;</td>
  </tr>
  <tr height="18">
    <th colspan="6" height="18" class="TituloReporte2" width="'.($tam*1).'">DAÑOS SUFRIDOS</th>
  </tr>
  <tr height="16">
    <th colspan="2" rowspan="2" height="32" class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Lesiones    personales</th>
    <th class="ContenidoReporte2" colspan="2" width="'.round($tam*0.357388316).'">Heridos</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.18327606).'">Muertos</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.344788087).'">Otros</th>
  </tr>
  <tr height="16">
    <td height="16" class="ContenidoReporte" colspan="2" width="'.round($tam*0.357388316).'">'.$nro_heridos.'&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">'.$nro_muertos.'&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">'.$lesiones_personales_obs.'&nbsp;</td>
  </tr>
  <tr height="16">
    <th colspan="2" rowspan="2" height="37" class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Daños a    terceros</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.203894616).'">Vehículos</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.1534937).'">Infraestuctura Vial</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.18327606).'">Otros</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.344788087).'">Especificar</th>
  </tr>
  <tr height="21">
    <td height="21" class="ContenidoReporte" width="'.round($tam*0.203894616).'">'.($daños_terceros=='V'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">'.($daños_terceros=='I'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">'.($daños_terceros=='O'?'X':'&nbsp;').'</td>
    <td width="'.round($tam*0.344788087).'" class="ContenidoReporte">'.$daños_terceros_obs.'&nbsp;</td>
  </tr>
  <tr height="18">
    <th colspan="6" height="18" class="TituloReporte2" width="'.($tam*1).'">PERSONAL QUE INTERVIENE EN EL EVENTO</th>
  </tr>
  <tr height="16">
    <th colspan="2" rowspan="2" height="32" class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Policía    Carreteras</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.203894616).'">Marcar con X</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.1534937).'">Placa No</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.18327606).'">Cargo</th>
    <th class="ContenidoReporte2" width="'.round($tam*0.344788087).'">Apellido</th>
  </tr>
  <tr height="16">
    <td height="16" class="ContenidoReporte" width="'.round($tam*0.203894616).'">'.($policia=='SI'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">'.$policia_placa.'&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">'.$policia_apellido.'</td>
  </tr>
  <tr height="16">
    <th colspan="2" height="16" class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Tránsito</th>
    <td class="ContenidoReporte" width="'.round($tam*0.203894616).'">'.($transito=='SI'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">'.$transito_placa.'</td>
     <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">&nbsp;</td>
	  <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">'. $transito_apellido.'</td>
  </tr>
  <tr height="16">
    <th colspan="2" height="16" class="ContenidoReporte2" width="'.round($tam*0.114547537).'">Inspector</th>
    <td class="ContenidoReporte" width="'.round($tam*0.203894616).'">'.($inspector=='SI'?'X':'&nbsp;').'</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">'.$inspector_placa.'</td>
     <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">&nbsp;</td>
	  <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">'. $inspector_apellido.'</td>
  </tr>
  <tr height="16">
    <th height="16" colspan="6" align="center" class="TituloReporte2" width="'.($tam*1).'">DESCRIPCIÓN DEL EVENTO</th>
  </tr>
  <tr height="20">
    <td height="20" colspan="6" class="ContenidoReporte" width="'.($tam*1).'">'. $descripcion_evento.'</td>
   
  </tr>
  <tr height="20">
    <td height="20" class="ContenidoReporte" width="'.round($tam*0.09163803).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.022909507).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.203894616).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">&nbsp;</td>
  </tr>
  <tr height="20">
    <td height="20" class="ContenidoReporte" width="'.round($tam*0.09163803).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.022909507).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.203894616).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.1534937).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.18327606).'">&nbsp;</td>
    <td class="ContenidoReporte" width="'.round($tam*0.344788087).'">&nbsp;</td>
  </tr>
  <tr height="17">
    <th colspan="6" height="17" class="TituloReporte2" width="'.($tam*1).'">REGISTRO FOTOGRÁFICO</th>
  </tr>
  <tr>
  <td colspan="6" width="'.($tam*1).'">
  <table>';

$parametro=array('id'=>$id_buscar);
	$sql="SELECT
	nombre
	FROM
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo
	WHERE
	
	id_incidente=? and tipo='IMG'
	order by id";
	 $img=$_SESSION[APL]->bd->getRsO($sql,$parametro);
	$i=1;

	while(!$img->EOF)
	{
		$nomImg = "adjuntos/".$img->fields[0]."";
		if(file_exists($nomImg) )
		{
			if($i%2!=0)
				$dummy.='<tr>';

			$dummy.='<td><img src="adjuntos/'.$img->fields[0].'" width="'.round($tam*0.458190149).'" ></td>';
			if($i%2==0)
			{
				$dummy.='</tr>';
				$dummy.='
				</table>
				</td>
				</tr>
				<tr>
				  <td colspan="6" width="'.($tam*1).'">
				  <table>
				';
			}

			$i++;
		}

		$img->MoveNext();
	}


if($i%2==0)
{
$dummy.='<td>&nbsp;</td></tr>';
 

$dumy.=' </table>
</td>
</tr>';
	
}


  
  $dummy.='
  </table>
  </td>
  </tr>
</table>
';
if($firma!='')
	$dummy.='<table><tr><td align="left"><br><br>Firma Administrador Vial<br><img src="firmas/'.$firma.'" width="'.round($tam*0.458190149).'"><br><hr></td></tr></table>';
else
if($nombres!='')
	$dummy.='<table><tr><td align="center">USUARIO '.strtoupper($nombres).' '.strtoupper($apellidos).' SIN FIRMA REGISTRADA</td></tr></table>';
else
	$dummy.='<table><tr><td align="center">REPORTE NO FINALIZADO POR ADMINISTRADOR VIAL</td></tr></table>';		



$dummy.='</body></html>';
//echo $dummy;
$html2pdf->WriteHTML($dummy);
$html2pdf->Output('reporte_adm_vial.pdf','D');




?>

