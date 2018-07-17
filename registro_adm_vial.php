<?php 
//Cambio Septiembre 2011
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}


if(isset($_GET["msg"]))
	echo $_SESSION[APL]->msg($_GET["msg"]);

echo $_SESSION[APL]->cabeceras();
?>
<!-- ************************************************************************************************************************** -->
<!-- INICIO CCS JQUERY YFV --> 
<!-- ************************************************************************************************************************** -->
<!-- ************************************************************************************************************************** -->
	<!-- INICIO para los TABLAS jquery  
	se ha probado con los estilos demo_table.css";  demo_table_jui.css;  jquery.dataTables_themeroller.css -->
			<style type="text/css" title="currentStyle">
				@import "jquery/demo_page.css";  
				@import "jquery/jquery.dataTables_themeroller.css";
				@import "jquery/jquery-ui-1.8.4.custom.css";  
			</style>
	<!-- demo_page.css controla el ancho de la tabla  -->
	<!-- jquery-ui-1.8.4.custom.css pone el color a la cabecera y al pie de tabla con las flechas de ordenar -->			
	<!-- FIN para los TABLAS jquery -->
<!-- ************************************************************************************************************************** -->
<!-- FIN CCS  JQUERY --> 
<!-- ************************************************************************************************************************** -->
<!-- ************************************************************************************************************************** -->
<script type="text/javascript" src="js/mootools.js"></script>
<script type="text/javascript" src="js/slimbox.js"></script>
<link rel="stylesheet" href="css/slimbox.css" type="text/css" media="screen" />
<!-- link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" / -->
<!-- ************************************************************************************************************************** -->
<!-- ************************************************************************************************************************** -->
<!-- ************************************************************************************************************************** -->
<!-- INICIO SCRIPT JQUERY YFV --> 
<!-- ************************************************************************************************************************** -->
<!-- ************************************************************************************************************************** -->
<!-- Inicio JQUERY libreria jquery-1.9.1.min.js --> 
	<script type="text/javascript" src="jquery/jquery-1.9.1.min.js"></script>
	<!-- INICIO script para las tablas jquery-->
		<script type="text/javascript" language="javascript" src="jquery/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="jquery/jquery.dataTables.js"></script>
	<!-- FIN script para las tablas  jquery -->	
		<script type="text/javascript" charset="utf-8">
			// DOM SE EJECUTA TODO EL COD PARA EL JQUERY			
			$(document).ready(function(){
		   //código a ejecutar cuando el DOM está listo para recibir instrucciones.

			// INICIO query para las tablas
				$('#ptablas').dataTable( {
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				} );
			//FIN query para las tablas
			});
	</script>

<!--script type="text/javascript" src="libs/jq/jquery.min.js"></script -->
<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="libs/js/vista.js"></script>

<!-- ************************************************************************************************************************** -->
<!-- FIN SCRIPT  JQUERY --> 
<!-- ************************************************************************************************************************** -->
<!-- ************************************************************************************************************************** -->
<?php

$_SESSION[APL]->pagina_menu='registro_adm_vial.php';
if($_SESSION[APL]->usuario->id_perfil==3)
	echo $_SESSION[APL]->interfas->pestana(3);
else
	echo $_SESSION[APL]->interfas->pestana(4);


	$sql = "select valor from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_constante WHERE id=4";
	$cant_img = $_SESSION[APL]->bd->dato($sql);

if(isset($_POST['descripcion_evento']) && isset($_POST['otras_caracteristicas']))
{
	
	if(!isset($_POST['señalizacion_horizontal']))
		$señalizacion_horizontal='';
	else
		$señalizacion_horizontal=$_POST['señalizacion_horizontal'];
	
	if(!isset($_POST['señalizacion_vertical']))
		$señalizacion_vertical='';
	else
		$señalizacion_vertical=$_POST['señalizacion_vertical'];
		
		
	if(!isset($_POST['mantenimiento_gral']))
		$mantenimiento_gral='';
	else
		$mantenimiento_gral=$_POST['mantenimiento_gral'];
		
		
	if(!isset($_POST['rodadura']))
		$rodadura='';
	else
		$rodadura=$_POST['rodadura'];
		
	if(!isset($_POST['daños_terceros']))
		$daños_terceros='';
	else
		$daños_terceros=$_POST['daños_terceros'];
		
	if($_POST['accion']=='G')
			$estado=4;
		else
			$estado=5;

	
	$parametros=array(
	'señalizacion_horizontal'=>$señalizacion_horizontal,
	'señalizacion_horizontal_obs'=>$_POST['señalizacion_horizontal_obs'],
	'señalizacion_vertical'=>$señalizacion_vertical,
	'señalizacion_vertical_obs'=>$_POST['señalizacion_vertical_obs'],
	'mantenimiento_gral'=>$mantenimiento_gral,
	'mantenimiento_gral_obs'=>$_POST['mantenimiento_gral_obs'],
	'rodadura'=>$rodadura,
	'rodadura_obs'=>$_POST['rodadura_obs'],
	'otras_caracteristicas'=>$_POST['otras_caracteristicas'],
	'nro_heridos'=>$_POST['nro_heridos'],
	'nro_muertos'=>$_POST['nro_muertos'],
	'lesiones_personales_obs'=>$_POST['lesiones_personales_obs'],
	'daños_terceros'=>$daños_terceros,
	'daños_terceros_obs'=>$_POST['daños_terceros_obs'],
	'descripcion_evento'=>$_POST['descripcion_evento'],
	'estado'=>$estado
	);


	$sql="SELECT codigo,periodo 
	FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente 
	WHERE id=".$_POST['id_buscar'];
	$dat=$_SESSION[APL]->bd->getRs($sql);
	$cod_inc=$dat->fields[0];
	$per_inc=$dat->fields[1];
	$c_i=$per_inc."_".$cod_inc;
	
	
	$sql="UPDATE
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente
	SET
	señalizacion_horizontal=?,
	señalizacion_horizontal_obs=?,
	señalizacion_vertical=?,
	señalizacion_vertical_obs=?,
	mantenimiento_gral=?,
	mantenimiento_gral_obs=?,
	rodadura=?,
	rodadura_obs=?,
	otras_caracteristicas=?,
	nro_heridos=?,
	nro_muertos=?,
	lesiones_personales_obs=?,
	daños_terceros=?,
	daños_terceros_obs=?,
	";
	
	
	if($_POST['accion']=='G')
		$sql.="guardado_adm_vial=1,";
	else
		$sql.="finalizado_adm_vial=1,";
	
	$sql.="
	descripcion_evento=?,
	estado=?
	WHERE
	id=".$_POST['id_buscar']."";
	
	if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
		echo "<script>alert('Error al Actualizar Incidente')</script>";
	else
	{
		/*if($_FILES['imagen1']['name']!='')
			{
				$ext=substr($_FILES['imagen1']['name'],-3);
				$name1=$c_i."_imagen_1.".$ext;
			
				if (!move_uploaded_file($_FILES['imagen1']['tmp_name'],'adjuntos/'.$name1))
						echo "<script>alert('Error al Cargar Imagen 1')</script>";	
			}
		if($_FILES['imagen2']['name']!='')
			{
				$ext=substr($_FILES['imagen2']['name'],-3);
				$name2=$c_i."_imagen_2.".$ext;
				if (!move_uploaded_file($_FILES['imagen2']['tmp_name'],'adjuntos/'.$name2))
						echo "<script>alert('Error al Cargar Imagen 2')</script>";	
			}
		if($_FILES['imagen3']['name']!='')
			{
				$ext=substr($_FILES['imagen3']['name'],-3);
				$name3=$c_i."_imagen_3.".$ext;
				if (!move_uploaded_file($_FILES['imagen3']['tmp_name'],'adjuntos/'.$name3))
						echo "<script>alert('Error al Cargar Imagen 3')</script>";	
			}
		if($_FILES['imagen4']['name']!='')
			{
				$ext=substr($_FILES['imagen4']['name'],-3);
				$name4=$c_i."_imagen_4.".$ext;
				if (!move_uploaded_file($_FILES['imagen4']['tmp_name'],'adjuntos/'.$name4))
						echo "<script>alert('Error al Cargar Imagen 4')</script>";	
			}
		*/
		
		
		///Archivosss
		$pos_r=1;
		for($l=1;$l<=$cant_img;$l++)
		{
			if($_POST['id_a_'.$l]=='' && $_POST['borrar_a_'.$l]==0 && $_FILES['archivo_'.$l]['name']!='')
			{
				$id_a=$_SESSION[APL]->getSecuencia('dvm_archivo','id');
				
				$ext=substr($_FILES['archivo_'.$l]['name'],-3);
				$name=$c_i."_imagen_id_".$id_a.".".$ext;
				
				$parametros=array
						(
							'id'=>$id_a,
							'id_incidente'=>$_POST['id_buscar'],
							'nombre'=>$name,
							'tipo'=>'IMG'
							);
				
				
				$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo (id,id_incidente,nombre,tipo)
				VALUEs
				(?,?,?,?);";
				if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
					echo "<script>alert('Error al al Crear Imagen')</script>";
				else
				{
					if (!move_uploaded_file($_FILES['archivo_'.$l]['tmp_name'],'adjuntos/'.$name))
						echo "<script>alert('Error al Cargar Imagen ".$l."')</script>";	
				}
				$pos_r++;
			}
			else
			if($_POST['id_a_'.$l]!='' && $_POST['borrar_a_'.$l]==0 && $_FILES['archivo_'.$l]['name']!='')//nuevo lesionado
			{
				
				$ext=substr($_FILES['archivo_'.$l]['name'],-3);
				$name=$c_i."_imagen_id_".$_POST['id_a_'.$l].".".$ext;
				
				$parametros=array
						(
							'nombre'=>$name,	
							'id'=>$_POST['id_a_'.$l]
							);
				
				$sql="SELECT nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo  WHERE id=".$_POST['id_a_'.$l];
				$nombre_e = $_SESSION[APL]->bd->dato($sql);
				
				
				$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo 
				SET 
				nombre=?
				WHERE
				id=?";
				if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
					echo "<script>alert('Error al Actualizar Imagen')</script>";
				else
				{
					
					if(!unlink('adjuntos/'.$nombre_e))
						echo "<script>alert('Error al Eliminar Adjunto Anterior')</script>";
					if (!move_uploaded_file($_FILES['archivo_'.$l]['tmp_name'],'adjuntos/'.$name))
						echo "<script>alert('Error al Cargar Imagen ".$l."')</script>";	
				}
				$pos_r++;
			}
			else
			if($_POST['id_a_'.$l]!='' && $_POST['borrar_a_'.$l]==1)
			{
				
					$parametros=array
						(
							'id'=>$_POST['id_a_'.$l]
							);
				
				$sql="SELECT nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo  WHERE id=".$_POST['id_a_'.$l];
				$nombre_e = $_SESSION[APL]->bd->dato($sql);
				
				$sql="DELETE FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo 
				WHERE
				id=?";
				if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
				{
					echo "<script>alert('Error al Eliminar Imagen')</script>";
				}
				else
				{
					
					if(!unlink('adjuntos/'.$nombre_e))
						echo "<script>alert('Error al Eliminar Adjunto')</script>";
				}
			}
		}
		
		
		
		
		
		
		
		
		
		$parametros=array(
		'id_incidente'=>$_POST['id_buscar'],
		'estado'=>$estado,
		'observaciones'=>'Generado por el Sistema',
		'usuario'=>$_SESSION[APL]->usuario->id
		);
		
		$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente
		VALUES
		(?,?,?,CURRENT_TIMESTAMP,?)";
		if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
			echo "<script>alert('Error al crear Historial Incidente')</script>";
		else
		{
			if($_POST['accion']=='F')
				echo "<script>alert('Se Actualizo el incidente ".$_POST['periodo'].".".str_pad($cod_inc,5,"0",STR_PAD_LEFT)." y se elimino de la lista de pendientes');window.open('registro_adm_vial.php','_self')</script>";
			else
				echo "<script>alert('Se Actualizo el incidente ".$_POST['periodo'].".".str_pad($cod_inc,5,"0",STR_PAD_LEFT)."')</script>";
		}
	}
	

}
?>

<script>
function filtrar()
{

//	if(Math.ceil(parseFloat(document.incidente.cantidad_reg.value/document.incidente.cantidad.value))<document.incidente.pagina.value)
//		alert('La pagina seleccionada para la cantidad de registros a mostrar, no existe, seleccione una pagina inferior')
//	else
		document.incidente.submit();
}

function cargar_referencias(via)
{

	document.incidente.referencia.length=0;
	document.incidente.abcisa.value='';
	document.incidente.tramo_ruta.value='';
	switch(via)
	{
		<?php
			$sql="SELECT * FROM 
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia
			order by id_via,referencia";
			$refe=$_SESSION[APL]->bd->getRs($sql);

			$refe_tmp=0;
			$refe_i=-1;
			while (!$refe->EOF) 
			{
				if($refe_tmp!=$refe->fields[1])
				{
					if($refe_i!=-1)
						echo "break;
						";
					echo "case '".$refe->fields[1]."':
					";
					echo "document.incidente.referencia.options[0]=new Option(' ','');
					";
					$refe_tmp=$refe->fields[1];
					$refe_i=1;
				}	
				echo "document.incidente.referencia.options[".$refe_i."]=new Option('".$refe->fields[4]."','".$refe->fields[0]."|".$refe->fields[2]."|".$refe->fields[5]."');
				";
				$refe_i++;
				$refe->MoveNext();
			}
		?>break;
		default:
		alert('Via no encontrada');
		break;
	}
}
function colocar_datos_referencia(valor)
{
	texto=valor.split('|');
	document.incidente.abcisa.value=texto[1];
	document.incidente.tramo_ruta.value=texto[2];
}
function nuevo_incidente()
{
	
	
	var w=800;
	var h=300;
	var iz=(screen.width/2)-(w/2);
    var de=(screen.height/2)-(h/2); 
   window.open("registro_inicial.php?emergente=1","","width="+w+",height="+h+",left="+iz+",top="+de+", scrollbars=yes,menubars=no,statusbar=yes, status=yes, resizable=NO,location=NO") 

}

function guardar(tipo)
{

	if(document.incidente.descripcion_evento.value=='')
	{
		alert('Ingrese la descripcion del evento')
		document.incidente.descripcion_evento.focus();
	}
	else
	{
		if(tipo==1)
		document.incidente.accion.value='G';
		else
		document.incidente.accion.value='F';
		if(document.incidente.accion.value=='G' || (document.incidente.accion.value=='F' && confirm('Esta seguro de FINALIZAR el incidente?, esto lo eliminara de la lista de pendientes')))	
			document.incidente.submit();
	}
	
	
	
}
function ver_incidente(id)
{
	if(id=='')
	{
		alert('Seleccione el Incidente a Visualizar');
	}
	else
		window.open('registro_adm_vial.php?id_buscar='+id,'_self');
}

function ver_SOS(idSOS)
{
	$('#ifrVerSOS').attr('src', "registro_sos_ver.php?id_buscar="+idSOS)
	$("#venVerSOS").dialog("open");
}

function ver_SOSVehInv(idSOS)
{
	$('#ifrVerSOSVehInv').attr('src', "vehiculo_incidente_ver.php?id_buscar="+idSOS)
	$("#venVerSOSVehInv").dialog("open");
}

function nuevo_archivo(posi)
{
	
	if(posi<<?php echo $cant_img?>)
	{
		document.getElementById('tr_a_'+(parseFloat(posi)+1)).style.display='';
		document.getElementById('borrar_a_'+(parseFloat(posi)+1)).value=0;

	}
	else
		alert('Limite de <?php echo $cant_img?> superado')
}

function eliminar_archivo(posi)
{
		document.getElementById('tr_a_'+posi).style.display='none';
		document.getElementById('borrar_a_'+posi).value=1;
	
}


</script>
<?php
if(isset($_GET['id_buscar']) || isset($_POST['id_buscar']))
{
	if(isset($_GET['id_buscar']))
		$id_buscar=$_GET['id_buscar'];
	else
		$id_buscar=$_POST['id_buscar'];
	$parametro=array('id'=>$id_buscar);
	$sql="SELECT 
	i.id,
	fecha,
	hora_reporte,
	i.referencia,
	v.nombre,
	i.tipo_atencion,
	ta.nombre,
	estado,
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
	abscisa_real

	
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
	if($fecha!='')
	{
		$fecha_reporte=explode("-",$fecha);		
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
	$tipo_atencion_id=$inci->fields[5];	
	$tipo_atencion=$inci->fields[6];	
	$estado=$inci->fields[7];
	if($inci->fields[8]!='')
		$abscisa=$inci->fields[44];
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
}
?>


<form name="incidente" method="post" action="registro_adm_vial.php" enctype="multipart/form-data">
<center>
<?php 
if(!isset($id_buscar))
{
	if(isset($_GET['finalizado']))
		$finalizado_v=$_GET['finalizado'];
	else
	if(isset($_POST['finalizado']))
		$finalizado_v=$_POST['finalizado'];
	else
		$finalizado_v=0;

$sql_c="SELECT 
	count(*)
 FROM 
 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i
	 WHERE 
	 finalizado_adm_vial=".$finalizado_v." ";
if($_SESSION[APL]->usuario->id_perfil!=0 && $_SESSION[APL]->usuario->id_perfil!=3)
	$sql.="AND i.id_usuario='".$_SESSION[APL]->usuario->id."'";
$rs_c=$_SESSION[APL]->bd->getRs($sql_c);
$cantidad_reg=$rs_c->fields[0];

$base_c=50;

if(isset($_POST['cantidad']))
	$cantidad_mostrar=$_POST['cantidad'];
else
	$cantidad_mostrar=$base_c;
	
	
if(isset($_POST['pagina']))	
	$pagina_mostrar=$_POST['pagina'];
else
	$pagina_mostrar=1;

$pagina_pag=(($pagina_mostrar-1)*$cantidad_mostrar)+1;
if($pagina_pag==1)
	$pagina_pag=0;



if(isset($_POST['ordenar']))
	$ordenar_v=$_POST['ordenar'];
else
	$ordenar_v="concat(periodo,LPAD(codigo,4,'0'))";
	

if(isset($_POST['sentido']))
	$sentido_v=$_POST['sentido'];
else
	$sentido_v="DESC";

$sql="SELECT 
	i.id,
	periodo,
	codigo,
	guardado_sos,
	finalizado_sos,
	guardado_adm_vial,
	finalizado_adm_vial,
	v.nombre,
	r.referencia,
	ta.nombre,
i.fecha,
u.nombres,
u.apellidos
 FROM 
 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i
left outer join
 ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u on (i.id_usuario=u.id),

 	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia  as r,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via as v,
	".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion as ta
	
	 WHERE 
	 i.tipo_atencion=ta.id and
	 i.via=v.id and
	 i.referencia=r.id and
	 finalizado_adm_vial=".$finalizado_v." ";
if($_SESSION[APL]->usuario->id_perfil!=0 && $_SESSION[APL]->usuario->id_perfil!=3)
	$sql.="AND i.id_usuario='".$_SESSION[APL]->usuario->id."'";

$sql.=" ORDER BY ".$ordenar_v." ".$sentido_v."";
//$sql.=" limit ".$pagina_pag.",".$cantidad_mostrar."";
$rs=$_SESSION[APL]->bd->getRs($sql);

?>
<!-- ******************************************************************************************** -->
<!-- ******************************************************************************************** -->
<!-- INICIO TABLA DEL LISTADO DE REGISTRO DE SOS YFV-->
<!-- ******************************************************************************************** -->

<div id="container">
<table cellpadding="4" align="center">
	<tr><td colspan="9" >
			<select name="finalizado" class="campos">
				<option value="0" <?php if($finalizado_v=='0') echo "selected"?>>Pendientes</option>
				<option value="1" <?php if($finalizado_v=='1') echo "selected"?>>Finalizados</option>
			</select>
		</TD>
		<td>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		<TD>	
			<?php echo $_SESSION[APL]->getButtom('.','Filtrar', '50', 'onclick=filtrar()');?>
		</td></tr>
		<tr><th height="10" colspan="2">&nbsp;</th></tr>		
</table>

	<div id="demo">
	<table border="0" align="center" cellspacing="0" class="display" id="ptablas">
	<thead>
		<tr>
			<th class="LegendSt">Codigo</th>
			<th class="LegendSt">Fecha</th>
			<th class="LegendSt">Via</th>
			<th class="LegendSt">Referencia</th>
			<th class="LegendSt">Tipo Atencion</th>
			<th class="LegendSt">Usuario</th>
			<th class="LegendSt">Estado</th>
			<th class="LegendSt">Ver</th>
			<th class="LegendSt">Descargar</th>
		</tr>
	</thead>
	<tbody>  
<?php
	$i=0;
	while(!$rs->EOF)
	{
	if($i%2==0)
			echo "<tr bgcolor='#CCCCCC'>";
		else
			echo "<tr bgcolor='#FFFFFF'>";
	
	echo "
		<td>".$rs->fields[1].".".str_pad($rs->fields[2],5,"0",STR_PAD_LEFT)."</td>
		<td>".$rs->fields[10]."</td>
		<td>".$rs->fields[7]."</td>
		<td>".$rs->fields[8]."</td>
		<td>".$rs->fields[9]."</td>
		<td>".$rs->fields[11]." ".$rs->fields[12]."</td>
		<td>";
	
	echo '<table>
			<tr>
			<td><img src="img/verde.png"  title="Registro Inicial Completo"/></td>
			<td>';
				if($rs->fields[4]==1)
					echo '<img src="img/verde.png" title="Finalizado por SOS"/>';
				else
				if($rs->fields[3]==1)
					echo '<img src="img/amarillo.png" title="Guardado por SOS"/>';
				else
					echo '<img src="img/gris.png" title="Pendiente por SOS"/>';
			echo '
			</td>
			<td>
				';
				if($rs->fields[6]==1)
					echo '<img src="img/verde.png" title="Finalizado por Adm Vial"/>';
				else
				if($rs->fields[5]==1)
					echo '<img src="img/amarillo.png" title="Guardado por Adm Vial"/>';
				else
					echo '<img src="img/gris.png" title="Pendiente por Adm Vial"/>';
		echo '	</td>
		</tr>
		</table>';
	
	echo"</td>
	<td><input type='button' class='vbotones' value='Ver' onClick='ver_incidente(".$rs->fields[0].")'></td>
	<td align='center'>".
	'<img src="img/popup.png"style="cursor:pointer" title="REPORTE ACCIDENTE" alt="REPORTE ACCIDENTE" onclick="window.open('."'reporte_1.php?id_buscar=".$rs->fields[0]."','_blank'".')" />
	<img src="img/popup.png"style="cursor:pointer" title="INFORME ADMINISTRADOR VIAL DE  EVENTUALIDADES SOBRE LA VÍA" alt="INFORME ADMINISTRADOR VIAL DE  EVENTUALIDADES SOBRE LA VÍA" onclick="window.open('."'reporte_2.php?id_buscar=".$rs->fields[0]."','_blank'".')" />'.
	"</td>
	</tr>";
	$rs->MoveNext();
	$i++;
	}
?>
		</tbody>
	</table>
</div>

<!-- ******************************************************************************************** -->
<!-- FIN TABLA DEL LISTADO DE REGISTRO DE SOS YFV-->
<!-- ******************************************************************************************** -->
<!-- ******************************************************************************************** -->
</div>
	
<?php 
}
if(isset($id_buscar))
{
?>



<table>
<tr class="cab_grid"><th colspan="6" >Datos Generales Admin Vial</th>
</tr>
<tr><th class="LegendSt">Estado</th>
<td colspan="10">

	<table>
	<tr>
	<td><img src="img/verde.png"  title="Registro Inicial Completo"/></td>
	<td>&nbsp;&nbsp;</td>
	<td>
	<?php
	if($finalizado_sos==1)
		echo '<img src="img/verde.png" title="Finalizado por SOS"/>';
	else
	if($guardado_sos==1)
	echo '<img src="img/amarillo.png" />';
	else
	echo '<img src="img/gris.png" />';
	?>
	</td>
	<td>&nbsp;&nbsp;</td>
	<td>
	<?php
	if($finalizado_adm_vial==1)
		echo '<img src="img/verde.png" title="Finalizado por Adm Vial"/>';
	else
	if($guardado_adm_vial==1)
		echo '<img src="img/amarillo.png" title="Guardado por Adm Vial"/>';
	else
		echo '<img src="img/gris.png" title="Pendiente por Adm Vial"/>';
	?>
	</td>
	</tr>
	</table>

</td>

</tr>

<tr><th  class="LegendSt" bgcolor="#CCCCCC">
INCIDENTES EXISTENTES </th>
<td align="left">
<input type="hidden" name="periodo" value="<?php if(isset($id_buscar)) echo $periodo?>" />
<select name="id_buscar"  class="campos">
<option value=""></option>
<?php
$sql="SELECT id,periodo,codigo,finalizado_adm_vial FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente ";

if($_SESSION[APL]->usuario->id_perfil!=0 && $_SESSION[APL]->usuario->id_perfil!=3)
	$sql.="WHERE id_usuario='".$_SESSION[APL]->usuario->id."'";

$sql.=" ORDER BY id";
$rs=$_SESSION[APL]->bd->getRs($sql);

while (!$rs->EOF) {
   	echo "<option value='".$rs->fields[0]."' ";
	if(isset($id_buscar) && $id_buscar==$rs->fields[0])
		echo "selected";
	echo ">".$rs->fields[1].".".str_pad($rs->fields[2],5,"0",STR_PAD_LEFT)."";
	if($rs->fields[3]==1)
		echo "*";
	echo "</option>";
    $rs->MoveNext();
}
$rs->close();
?>
</select>
<input type="button" value="Ver" class="vbotones" onclick="ver_incidente(document.incidente.id_buscar.value)"/>
<input type="button" value="Ver SOS" class="vbotones" onclick="ver_SOS(document.incidente.id_buscar.value)" />
<?php echo $_SESSION[APL]->getButtom('.','Nuevo Incidente', '50', 'onclick=nuevo_incidente()','','middlered'); ?>
</tr>
<tr>
<th colspan="6" height="20">&nbsp;</th>
</tr>
<tr class="cab_grid">
<th colspan="6"><span class="style1">INFORME ADMINISTRADOR VIAL DE EVENTUALIDADES SOBRE LA VIA</span></th>
</tr>
<tr>
<th colspan="6" height="20">&nbsp;</th>
</tr>
<tr class="cab_grid">
<th  colspan="6"><span class="style1">DETALLES DEL EVENTO</span></th>
</tr>
<tr>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Hora</span></th>
<td class="style1" align="center"><?php if(isset($id_buscar)) echo $hora_rep.":".$minu_rep?></td>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Fecha</span></th>
<td class="style1" align="center">Dia <?php if(isset($id_buscar)) echo $dia_rep;?></td>
<td class="style1" align="center">Mes <?php if(isset($id_buscar)) echo $mes_rep;?></td>
<td class="style1" align="center">Año <?php if(isset($id_buscar)) echo $ano_rep;?></td>
</tr>
<tr>
<th colspan="6" height="20">&nbsp;</th>
</tr>
<tr class="cab_grid">
<th colspan="6"><span class="style1">CARACTERISTICAS DEL EVENTO</span></th>
</tr>

<tr>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Tramo Vial</span></th>
<td class="style1" align="center" colspan="2"><?php if(isset($id_buscar)) echo $via?></td>
<th bgcolor="#CCCCCC" class="LegendSt">Abscisa y Sitio</th>
<td class="style1" align="center" colspan="2"><?php if(isset($id_buscar)) echo $nombre_referencia." ".$abscisa." ".$tramo_ruta?></td>
</tr>
<tr>
<th bgcolor="#CCCCCC" class="LegendSt" ><span class="style1">Tipo</span></th>
<td class="style1" align="center" colspan="5"><?php if(isset($id_buscar)) echo $tipo_atencion?></td>
</tr>

<tr>
<th colspan="6" height="20">&nbsp;</th>
</tr>
<tr>
<td colspan="6" align="center">
<table width="100%">
<tr class="cab_grid">
<th  colspan="6"><span class="style1">CARACTERISTICAS GENERALES DEL SITIO DEL EVENTO</span></th>
</tr>
<tr>
<th  rowspan="2" bgcolor="#99CC00" class="LegendSt"><span class="style1">Señalizacion Hotizontal</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Bueno</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Regular</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Malo</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Observaciones</span></th>
</tr>
<tr>
<td class="style1" align="left" ><input name="señalizacion_horizontal" type="radio" class="campos" value="B" <?php if(isset($id_buscar) && $señalizacion_horizontal=='B') echo "checked"?>/></td>
<td class="style1" align="left" ><input name="señalizacion_horizontal" type="radio" class="campos" value="R" <?php if(isset($id_buscar) && $señalizacion_horizontal=='R') echo "checked"?>/></td>
<td class="style1" align="left" ><input name="señalizacion_horizontal" type="radio" class="campos" value="M" <?php if(isset($id_buscar) && $señalizacion_horizontal=='M') echo "checked"?>/></td>
<td class="style1" align="left" ><textarea name="señalizacion_horizontal_obs" cols="30"  class="campos"><?php if(isset($id_buscar)) echo $señalizacion_horizontal_obs?></textarea></td>
</tr>
<tr>
<th  bgcolor="#99CC00" class="LegendSt"><span class="style1">Señalizacion Vertical</span></th>
<td class="style1" align="left" ><input name="señalizacion_vertical" type="radio" class="campos" value="B" <?php if(isset($id_buscar) && $señalizacion_vertical=='B') echo "checked"?>/></td>
<td class="style1" align="left" ><input name="señalizacion_vertical" type="radio" class="campos" value="R" <?php if(isset($id_buscar) && $señalizacion_vertical=='R') echo "checked"?>/></td>
<td class="style1" align="left" ><input name="señalizacion_vertical" type="radio" class="campos" value="M" <?php if(isset($id_buscar) && $señalizacion_vertical=='M') echo "checked"?>/></td>
<td class="style1" align="left" ><textarea name="señalizacion_vertical_obs" cols="30"  class="campos"><?php if(isset($id_buscar)) echo $señalizacion_vertical_obs?></textarea></td>
</tr>
<tr>
<th  bgcolor="#99CC00" class="LegendSt"><span class="style1">Mantenimiento General</span></th>
<td class="style1" align="left" ><input name="mantenimiento_gral" type="radio" class="campos" value="B" <?php if(isset($id_buscar) && $mantenimiento_gral=='B') echo "checked"?>/></td>
<td class="style1" align="left" ><input name="mantenimiento_gral" type="radio" class="campos" value="R" <?php if(isset($id_buscar) && $mantenimiento_gral=='R') echo "checked"?>/></td>
<td class="style1" align="left" ><input name="mantenimiento_gral" type="radio" class="campos" value="M" <?php if(isset($id_buscar) && $mantenimiento_gral=='M') echo "checked"?>/></td>
<td class="style1" align="left" ><textarea name="mantenimiento_gral_obs" cols="30"  class="campos"><?php if(isset($id_buscar)) echo $mantenimiento_gral_obs?></textarea></td>
</tr>
<tr>
<th  bgcolor="#99CC00" class="LegendSt"><span class="style1">Rodadura</span></th>
<td class="style1" align="left" ><input name="rodadura" type="radio" class="campos" value="B" <?php if(isset($id_buscar) && $rodadura=='B') echo "checked"?>/></td>
<td class="style1" align="left" ><input name="rodadura" type="radio" class="campos" value="R" <?php if(isset($id_buscar) && $rodadura=='R') echo "checked"?>/></td>
<td class="style1" align="left" ><input name="rodadura" type="radio" class="campos" value="M" <?php if(isset($id_buscar) && $rodadura=='M') echo "checked"?>/></td>
<td class="style1" align="left" ><textarea name="rodadura_obs" cols="30"  class="campos"><?php if(isset($id_buscar)) echo $rodadura_obs?></textarea></td>
</tr>
<tr>
<th  bgcolor="#99CC00" class="LegendSt"><span class="style1">Otras Caracteristicas</span></th>
<td class="style1" align="center"  colspan="4"><textarea name="otras_caracteristicas" cols="85" rows="4"  class="campos"><?php if(isset($id_buscar)) echo $otras_caracteristicas?></textarea></td>
</tr>
<tr>
<th colspan="5" height="20">&nbsp;</th>
</tr>
<tr class="cab_grid">
<th  colspan="5"><span class="style1">DAÑOS SUFRIDOS</span></th>
</tr>
<tr>
<th rowspan="2" bgcolor="#99CC00" class="LegendSt"><span class="style1">Lesiones Personales</span></th>
<th bgcolor="#99CC00" class="LegendSt" colspan="2"><span class="style1">Heridos</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Muertos</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Otros</span></th>
</tr>
<tr>
<td class="style1" align="center"  colspan="2"><input type="text" name="nro_heridos" value="<?php if(isset($id_buscar) && $nro_heridos!='') echo $nro_heridos; else echo "0"?>" size="3" class="resaltar" onkeypress="return false" style="text-align:center"/></td>
<td class="style1" align="center" ><input type="text" name="nro_muertos" value="<?php if(isset($id_buscar) && $nro_muertos!='') echo $nro_muertos; else echo "0"?>" size="3" class="resaltar" onkeypress="return false" style="text-align:center"/></td>
<td class="style1" align="center" ><textarea name="lesiones_personales_obs" cols="30"  class="campos"><?php if(isset($id_buscar)) echo $lesiones_personales_obs?></textarea></td>
</tr>
<tr>
<th  rowspan="2" bgcolor="#99CC00" class="LegendSt"><span class="style1">Daños a Terceros</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Vehiculos</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Infraestructura Vial</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Otros</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Especificar</span></th>
</tr>
<tr>
<td class="style1" align="center" ><input type="radio" class="campos" value="V" name="daños_terceros" <?php if(isset($id_buscar) && $daños_terceros=='V') echo "checked";?> /></td>
<td class="style1" align="center" ><input type="radio" class="campos" value="I" name="daños_terceros" <?php if(isset($id_buscar) && $daños_terceros=='I') echo "checked";?> /></td>
<td class="style1" align="center" ><input type="radio" class="campos" value="O" name="daños_terceros" <?php if(isset($id_buscar) && $daños_terceros=='O') echo "checked";?> /></td>
<td class="style1" align="center" ><textarea name="daños_terceros_obs" cols="30"  class="campos"><?php if(isset($id_buscar)) echo $daños_terceros_obs?></textarea></td>
</tr>
<tr>
<th colspan="5" height="20">&nbsp;</th>
</tr>
<tr class="cab_grid">
<th  colspan="5"><span class="style1">PERSONAL QUE INTERVIENE EN EL EVENTO</span></th>
</tr>
<tr>
<th  rowspan="2" bgcolor="#99CC00" class="LegendSt"><span class="style1">Policia de Carreteras</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Marcar con una X</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Placa Nro</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Cargo</span></th>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Apellido</span></th>
</tr>
<tr>
<td class="style1" align="center" ><?php if(isset($id_buscar) && $policia=='SI') echo "X"; else echo "-";?></td>
<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $policia_placa?></td>
<td class="style1" align="center" >&nbsp;</td>
<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $policia_apellido?></td>
</tr>
<tr>
<th  bgcolor="#99CC00" class="LegendSt" ><span class="style1">Transito</span></th>
<td class="style1" align="center" ><?php if(isset($id_buscar) && $transito=='SI') echo "X"; else echo "-";?></td>
<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $transito_placa?></td>
<td class="style1" align="center" >&nbsp;</td>
<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $transito_apellido?></td>
</tr>
<tr>
<th bgcolor="#99CC00" class="LegendSt" ><span class="style1">Inspector</span></th>
<td class="style1" align="center" ><?php if(isset($id_buscar) && $inspector=='SI') echo "X"; else echo "-";?></td>
<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $inspector_placa?></td>
<td class="style1" align="center" >&nbsp;</td>
<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $inspector_apellido?></td>
</tr>
<tr>
<th colspan="5" height="20">&nbsp;</th>
</tr>
<tr class="cab_grid">
<th  colspan="5"><span class="style1">DESCRIPCION DEL EVENTO</span></th>
</tr>
<tr>
<td class="style1" align="center"  colspan="6"><textarea name="descripcion_evento" cols="120" rows="5"  class="campos"><?php if(isset($id_buscar)) echo $descripcion_evento?></textarea></td>
</tr>
<tr>
<th colspan="5" height="20">&nbsp;</th>
</tr>
<tr>
<td colspan="5">
<table width="100%">
<tr class="cab_grid">
<th colspan="3"><span class="style1">REGISTRO FOTOGRAFICO</span></th>
</tr>
<tr>
<th colspan="3" class="LegendSt" >
EL Reporte Final, se genera de forma ideal con imagenes de un ancho de 300 pixeles
</th>
</tr>


<?php



$sql="SELECT id,nombre FROM
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo
WHERE tipo='IMG' AND id_incidente=".$id_buscar."";

$archi=$_SESSION[APL]->bd->getRs($sql);



$visi_l=0;
for($l=1;$l<=$cant_img;$l++)
{

	if(isset($archi) && !$archi->EOF)
	{
		$estilo_l='';
		$visi_l++;
		$posi=$l;
		$id_a=$archi->fields[0];
		$nomb=$archi->fields[1];
		$archi->MoveNext();
	}
	else
	{
	
		if($l!=1)
			$estilo_l="style='display:none'";
		else
		{
			$estilo_l=""; 
			$visi_l++;
		}
		
		$posi=$l;
		$id_a='';
		$nomb='';
	}
?>




<tr id="tr_a_<?php echo $posi?>" <?php echo $estilo_l;?>>
<td class="style1" align="center" ><?php echo $posi?></td>

<td class="style1" align="center"  >
<input type="file" name="archivo_<?php echo $posi?>" id="archivo_<?php echo $posi?>" class="campos"/>


<?php 
if(isset($id_buscar) && $nomb!='')
{
?>

<a href="adjuntos/<?php echo $nomb?>" rel="lightbox" title="Ver Imagen <?php echo $posi?>" alt="Ver Imagen <?php echo $posi?>">
<img border="1" src="img/popup.png" ></a><p align="center">
<?php echo  $nomb;
}
?>


<input type="hidden" name="id_a_<?php echo $posi?>" id="id_a_<?php echo $posi?>" value="<?php echo $id_a;?>"/>

<input type="hidden" name="borrar_a_<?php echo $posi?>" id="borrar_a_<?php echo $posi?>" value="<?php
if($id_a!='' || $posi==1)
echo "0";
else
	echo "1";
?>"/>
</td>
<td >
   <?php 
echo $_SESSION[APL]->getButtom('.','Agregar Siguiente Imagen ', '50', 'onclick="nuevo_archivo('.$posi.')"');
echo $_SESSION[APL]->getButtom('.','Eliminar Imagen', '50', 'onclick="eliminar_archivo('.$posi.')"','','middlered');
?>
</td>
</tr>
<?php
}?>





<tr>
<td  align="right" height="40px" valign="middle">
<?php if(isset($id_buscar) && $finalizado_adm_vial!=1)
{
echo $_SESSION[APL]->getButtom('.','Guardar', '50', 'onclick="guardar(1)"');
 }
?>
</td>
<td  align="left" height="40px" valign="middle">
<?php if(isset($id_buscar))
{
echo $_SESSION[APL]->getButtom('.','Finalizar', '50', 'onclick="guardar(0)"','','middlered');
 }
?>
</td>
</tr>
</table>
</td>
</tr>



</table>
</td>
</tr>
</table>
	<?php
}
?>
</center>
<input type="hidden" name="accion" value="" />
</form>
<div id="venVerSOS" style="display:none">
	<center>
		<iframe id="ifrVerSOS" height="570px" width="970px" style="border:0px"></iframe>
	</center>
</div>
<div id="venVerSOSVehInv" style="display:none">
	<center>
		<iframe id="ifrVerSOSVehInv" height="570px" width="1170px" style="border:0px"></iframe>
	</center>
</div>
</body>
<script>
	vis_PonVentana("venVerSOS","Registro SOS",640,1000);
	vis_PonVentana("venVerSOSVehInv","VEHICULOS INVOLUCRADOS, AFECTADOS/ LESIONADOS Y/O MUERTOS",640,1200);
</script>
</html>
