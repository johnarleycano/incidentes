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
				echo "<script>alert('Se Actualizo el incidente ".$_POST['periodo'].".".str_pad($cod_inc,5,"0",STR_PAD_LEFT)." y se elimino de la lista de pendientes');window.parent.filtrar();window.open('registro_adm_vial_edi.php','_self');</script>";
			else
				echo "<script>alert('Se Actualizo el incidente ".$_POST['periodo'].".".str_pad($cod_inc,5,"0",STR_PAD_LEFT)."');window.parent.filtrar();</script>";
		}
	}
	

}
?>
<script type="text/javascript" src="jquery/jquery-1.9.1.min.js"></script>
<!-- UI -->
<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

<script type="text/javascript" src="libs/js/vista.js"></script>
<link href="css/tabla.css" rel="stylesheet" type="text/css">
<style>
	.selPeq
	{
		width:110px !important;
	}
	.cmpPeq
	{
		width:80px !important;
	}
	.cmpPeqPeq
	{
		width:40px !important;
	}
</style>
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
		?>
		break;
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
	var senHor = $("#senalizacion_horizontal_obs").val()+"";
	if( senHor.length>=500 ){
		alert("La señalizacion horizontal no debe de contener mas de 500 caracteres.");
		return;
	}

	var senVer = $("#senalizacion_vertical_obs").val()+"";
	if( senVer.length>=500 ){
		alert("La señalizacion vertical no debe de contener mas de 500 caracteres.");
		return;
	}

	var manGen = $("#mantenimiento_gral_obs").val()+"";
	if( manGen.length>=500 ){
		alert("El mantenimiento general no debe de contener mas de 500 caracteres.");
		return;
	}

	var rodadu = $("#rodadura_obs").val()+"";
	if( rodadu.length>=500 ){
		alert("La rodadura no debe de contener mas de 500 caracteres.");
		return;
	}

	var otrCar = $("#otras_caracteristicas").val()+"";
	if( otrCar.length>=500 ){
		alert("Otras caracteristicas no debe de contener mas de 500 caracteres.");
		return;
	}

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
		alert('Seleccione el Incidente a Visualizar');
	else
		window.open('registro_adm_vial_edi.php?id_buscar='+id,'_self');
}

function ver_SOS(idSOS)
{
	window.parent.ver_SOS(idSOS);
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

function recargarGrilla()
{
	window.parent.filtrar();
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
	abscisa_real,
	fechaincidente,
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
	$fecInc =$inci->fields[45];
	$horInc =$inci->fields[46];

	// Si existe la fecha del incidente, se quita la fecha de creacion
	if( $fecInc!="" )
		$fecha = $fecInc;

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

	$tmpHorRep = $inci->fields[2];
	// Si existe la hora del incidente, se quita la hora de creacion
	if( $horInc!="" )
		$tmpHorRep = $horInc;
	
	if($inci->fields[2]!='')
	{
		//$hora_reporte=explode(":",$inci->fields[2]);
		$hora_reporte=explode(":",$tmpHorRep);
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

<form name="incidente" method="post" action="registro_adm_vial_edi.php" enctype="multipart/form-data">
<center>
<?php 
if(isset($id_buscar))
{
?>
	<table class="cssBus tabEdi" cellpadding="3" border="0">
		<tr><th class="LegendSt" style="background-color:#4CB877">Datos Generales Admin Vial</th></tr>
		<tr>
			<td>
				<table cellpadding="3">
					<tr>
						<th class="resaltar">Estado</th>
						<td colspan="4">
							<img src="img/verde.png"  title="Registro Inicial Completo"/>&nbsp;&nbsp;
							<?php
								if($finalizado_sos==1)
									echo '<img src="img/verde.png" title="Finalizado por SOS"/>';
								else if($guardado_sos==1)
									echo '<img src="img/amarillo.png" />';
								else
									echo '<img src="img/gris.png" />';
							?>
							&nbsp;&nbsp;
							<?php
								if($finalizado_adm_vial==1)
									echo '<img src="img/verde.png" title="Finalizado por Adm Vial"/>';
								else if($guardado_adm_vial==1)
									echo '<img src="img/amarillo.png" title="Guardado por Adm Vial"/>';
								else
									echo '<img src="img/gris.png" title="Pendiente por Adm Vial"/>';
							?>
						</td>
					</tr>
					<tr>
						<th class="resaltar">INCIDENTES EXISTENTES</th>
						<td>
							<input type="hidden" name="periodo" value="<?php if(isset($id_buscar)) echo $periodo?>" />
							<select name="id_buscar"  class="campos selPeq">
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
						</td>
						<td><input type="button" value="Ver" class="vbotones" onclick="ver_incidente(document.incidente.id_buscar.value)"/></td>
						<td><input type="button" value="Ver SOS" class="vbotones" onclick="ver_SOS(document.incidente.id_buscar.value)" /></td>
						<td><?php echo $_SESSION[APL]->getButtom('.','Nuevo Incidente', '50', 'onclick=nuevo_incidente()','','middlered'); ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">INFORME ADMINISTRADOR VIAL DE EVENTUALIDADES SOBRE LA VIA</th></tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">DETALLES DEL EVENTO</th></tr>
		<tr>
			<td>
				<table width="100%" cellpadding="3">
					<tr>
						<th class="resaltar">Hora</th>
						<td class="style1" align="center"><?php if(isset($id_buscar)) echo $hora_rep.":".$minu_rep?></td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Fecha</span></th>
						<td class="style1" align="center"><b>Dia</b> <?php if(isset($id_buscar)) echo $dia_rep;?></td>
						<td class="style1" align="center"><b>Mes</b> <?php if(isset($id_buscar)) echo $mes_rep;?></td>
						<td class="style1" align="center"><b>Año</b> <?php if(isset($id_buscar)) echo $ano_rep;?></td>
					</tr>
					<tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">CARACTERISTICAS DEL EVENTO</th></tr>
		<tr>
			<td>
				<table width="100%" cellpadding="3">
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Tramo Vial</span></th>
						<td class="style1" colspan="2"><?php if(isset($id_buscar)) echo $via?></td>
						<th bgcolor="#CCCCCC" class="resaltar">Abscisa y Sitio</th>
						<td class="style1" colspan="2"><?php if(isset($id_buscar)) echo $nombre_referencia." ".$abscisa." ".$tramo_ruta?></td>
					</tr>
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar" ><span class="style1">Tipo</span></th>
						<td class="style1" colspan="5"><?php if(isset($id_buscar)) echo $tipo_atencion?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">CARACTERISTICAS GENERALES DEL SITIO DEL EVENTO</th></tr>
		<tr>
			<td>
				<table width="100%" cellpadding="3">
					<tr>
						<th bgcolor="#99CC00" class="resaltar"><span class="style1"></span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center" style="width:60px"><span class="style1">Bueno</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center" style="width:60px"><span class="style1">Regular</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center" style="width:60px"><span class="style1">Malo</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Observaciones</span></th>
					</tr>
					<tr>
						<th bgcolor="#99CC00" class="resaltar"><span class="style1">Señalizacion Hotizontal</span></th>
						<td class="style1" align="center" ><input name="señalizacion_horizontal" type="radio" class="campos" value="B" <?php if(isset($id_buscar) && $señalizacion_horizontal=='B') echo "checked"?>/></td>
						<td class="style1" align="center" ><input name="señalizacion_horizontal" type="radio" class="campos" value="R" <?php if(isset($id_buscar) && $señalizacion_horizontal=='R') echo "checked"?>/></td>
						<td class="style1" align="center" ><input name="señalizacion_horizontal" type="radio" class="campos" value="M" <?php if(isset($id_buscar) && $señalizacion_horizontal=='M') echo "checked"?>/></td>
						<td class="style1" align="left" ><textarea id="senalizacion_horizontal_obs" name="señalizacion_horizontal_obs" style="height:70px;width:600px" class="campos"><?php if(isset($id_buscar)) echo $señalizacion_horizontal_obs?></textarea></td>
					</tr>
					<tr>
						<th  bgcolor="#99CC00" class="resaltar"><span class="style1">Señalizacion Vertical</span></th>
						<td class="style1" align="center" ><input name="señalizacion_vertical" type="radio" class="campos" value="B" <?php if(isset($id_buscar) && $señalizacion_vertical=='B') echo "checked"?>/></td>
						<td class="style1" align="center" ><input name="señalizacion_vertical" type="radio" class="campos" value="R" <?php if(isset($id_buscar) && $señalizacion_vertical=='R') echo "checked"?>/></td>
						<td class="style1" align="center" ><input name="señalizacion_vertical" type="radio" class="campos" value="M" <?php if(isset($id_buscar) && $señalizacion_vertical=='M') echo "checked"?>/></td>
						<td class="style1" align="left" ><textarea id="senalizacion_vertical_obs" name="señalizacion_vertical_obs" style="height:70px;width:600px" class="campos"><?php if(isset($id_buscar)) echo $señalizacion_vertical_obs?></textarea></td>
					</tr>
					<tr>
						<th  bgcolor="#99CC00" class="resaltar"><span class="style1">Mantenimiento General</span></th>
						<td class="style1" align="center" ><input name="mantenimiento_gral" type="radio" class="campos" value="B" <?php if(isset($id_buscar) && $mantenimiento_gral=='B') echo "checked"?>/></td>
						<td class="style1" align="center" ><input name="mantenimiento_gral" type="radio" class="campos" value="R" <?php if(isset($id_buscar) && $mantenimiento_gral=='R') echo "checked"?>/></td>
						<td class="style1" align="center" ><input name="mantenimiento_gral" type="radio" class="campos" value="M" <?php if(isset($id_buscar) && $mantenimiento_gral=='M') echo "checked"?>/></td>
						<td class="style1" align="left" ><textarea id="mantenimiento_gral_obs" name="mantenimiento_gral_obs" style="height:70px;width:600px" class="campos"><?php if(isset($id_buscar)) echo $mantenimiento_gral_obs?></textarea></td>
					</tr>
					<tr>
						<th  bgcolor="#99CC00" class="resaltar"><span class="style1">Rodadura</span></th>
						<td class="style1" align="center" ><input name="rodadura" type="radio" class="campos" value="B" <?php if(isset($id_buscar) && $rodadura=='B') echo "checked"?>/></td>
						<td class="style1" align="center" ><input name="rodadura" type="radio" class="campos" value="R" <?php if(isset($id_buscar) && $rodadura=='R') echo "checked"?>/></td>
						<td class="style1" align="center" ><input name="rodadura" type="radio" class="campos" value="M" <?php if(isset($id_buscar) && $rodadura=='M') echo "checked"?>/></td>
						<td class="style1" align="left" ><textarea id="rodadura_obs" name="rodadura_obs" style="height:70px;width:600px" class="campos"><?php if(isset($id_buscar)) echo $rodadura_obs?></textarea></td>
					</tr>
					<tr>
						<th bgcolor="#99CC00" class="resaltar"><span class="style1">Otras Caracteristicas</span></th>
						<td class="style1" colspan="4"><textarea id="otras_caracteristicas" name="otras_caracteristicas" style="height:80px;width:800px" class="campos"><?php if(isset($id_buscar)) echo $otras_caracteristicas?></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">DAÑOS SUFRIDOS</th></tr>
		<tr>
			<td align="center">
				<table width="100%" cellpadding="3">
					<tr>
						<th bgcolor="#99CC00" class="resaltar"><span class="style1"></span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Heridos</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Muertos</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Otros</span></th>
					</tr>
					<tr>
						<th bgcolor="#99CC00" class="resaltar"><span class="style1">Lesiones Personales</span></th>
						<td class="style1" align="center"><input type="text" name="nro_heridos" value="<?php if(isset($id_buscar) && $nro_heridos!='') echo $nro_heridos; else echo "0"?>" size="3" class="campos cmpPeq" onkeypress="return false" style="text-align:center"/></td>
						<td class="style1" align="center" ><input type="text" name="nro_muertos" value="<?php if(isset($id_buscar) && $nro_muertos!='') echo $nro_muertos; else echo "0"?>" size="3" class="campos cmpPeq" onkeypress="return false" style="text-align:center"/></td>
						<td class="style1" align="center" ><textarea name="lesiones_personales_obs" style="height:35px;width:600px" class="campos"><?php if(isset($id_buscar)) echo $lesiones_personales_obs?></textarea></td>
					</tr>
				</table>
				<hr/>
				<table width="100%" cellpadding="3">
					<tr>
						<th bgcolor="#99CC00" class="resaltar" ><span class="style1"></span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Vehiculos</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Infraestructura Vial</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Otros</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Especificar</span></th>
					</tr>
					<tr>
						<th bgcolor="#99CC00" class="resaltar"><span class="style1">Daños a Terceros</span></th>
						<td class="style1" align="center" ><input type="radio" class="campos" value="V" name="daños_terceros" <?php if(isset($id_buscar) && $daños_terceros=='V') echo "checked";?> /></td>
						<td class="style1" align="center" ><input type="radio" class="campos" value="I" name="daños_terceros" <?php if(isset($id_buscar) && $daños_terceros=='I') echo "checked";?> /></td>
						<td class="style1" align="center" ><input type="radio" class="campos" value="O" name="daños_terceros" <?php if(isset($id_buscar) && $daños_terceros=='O') echo "checked";?> /></td>
						<td class="style1" align="center" ><textarea name="daños_terceros_obs" style="height:35px;width:550px"  class="campos"><?php if(isset($id_buscar)) echo $daños_terceros_obs?></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">PERSONAL QUE INTERVIENE EN EL EVENTO</th></tr>
		<tr>
			<td>
				<table width="100%" cellpadding="3">
					<tr>
						<th bgcolor="#99CC00" class="resaltar"><span class="style1"></span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Marcar con una X</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Placa Nro</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Cargo</span></th>
						<th bgcolor="#99CC00" class="resaltar" align="center"><span class="style1">Apellido</span></th>
					</tr>
					<tr>
						<th bgcolor="#99CC00" class="resaltar"><span class="style1">Policia de Carreteras</span></th>
						<td class="style1" align="center" ><?php if(isset($id_buscar) && $policia=='SI') echo "X"; else echo "-";?></td>
						<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $policia_placa?></td>
						<td class="style1" align="center" >&nbsp;</td>
						<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $policia_apellido?></td>
					</tr>
					<tr>
						<th  bgcolor="#99CC00" class="resaltar" ><span class="style1">Transito</span></th>
						<td class="style1" align="center" ><?php if(isset($id_buscar) && $transito=='SI') echo "X"; else echo "-";?></td>
						<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $transito_placa?></td>
						<td class="style1" align="center" >&nbsp;</td>
						<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $transito_apellido?></td>
					</tr>
					<tr>
						<th bgcolor="#99CC00" class="resaltar" ><span class="style1">Inspector</span></th>
						<td class="style1" align="center" ><?php if(isset($id_buscar) && $inspector=='SI') echo "X"; else echo "-";?></td>
						<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $inspector_placa?></td>
						<td class="style1" align="center" >&nbsp;</td>
						<td class="style1" align="center" ><?php if(isset($id_buscar)) echo $inspector_apellido?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">DESCRIPCION DEL EVENTO</th></tr>
		<tr>
			<td>
				<table width="100%" cellpadding="3">
					<tr>
						<td class="style1" align="center" colspan="6"><textarea name="descripcion_evento" style="height:60px;width:900px"  class="campos"><?php if(isset($id_buscar)) echo $descripcion_evento?></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">REGISTRO FOTOGRAFICO</th></tr>
		<tr>
			<td align="center">
				<table cellpadding="3">
					<tr><th colspan="3" class="resaltar">EL Reporte Final, se genera de forma ideal con imagenes de un ancho de 300 pixeles</th></tr>
					<?php
					$sql = "SELECT id,nombre 
							FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo
							WHERE tipo='IMG' AND id_incidente=".$id_buscar."";
					$archi=$_SESSION[APL]->bd->getRs($sql);

					$visi_l=0;
					for($l=1;$l<=$cant_img;$l++)
					{
						$estilo_l='';
						if(isset($archi) && !$archi->EOF)
						{
							$visi_l++;
							$posi=$l;
							$id_a=$archi->fields[0];
							$nomb=$archi->fields[1];
							$archi->MoveNext();
						}
						else
						{
							if($l!=1)
								$estilo_l='style="display:none"';
							else
								$visi_l++;

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
							<input type="hidden" name="borrar_a_<?php echo $posi?>" id="borrar_a_<?php echo $posi?>" value="<?php if($id_a!='' || $posi==1) echo "0";else echo "1";?>"/>
						</td>
						<td>
							<table>
								<tr>
									<td><?php 
										echo $_SESSION[APL]->getButtom2('.','[+]', '50', 'onclick="nuevo_archivo('.$posi.')"','Agregar Siguiente Imagen');
									?></td>
									<td><?php 
										echo $_SESSION[APL]->getButtom2('.','[-]', '50', 'onclick="eliminar_archivo('.$posi.')"','Eliminar Imagen','','middlered');
									?></td>
								</tr>
							</table>
						</td>
					</tr>
					<?php
					}
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" cellpadding="3">
					<tr>
						<td align="right" height="40px" valign="middle">
						<?php 
							if(isset($id_buscar) && $finalizado_adm_vial!=1)
								echo $_SESSION[APL]->getButtom('.','Guardar', '50', 'onclick="guardar(1)"');
						?>
						</td>
						<td  align="left" height="40px" valign="middle">
						<?php 
							if(isset($id_buscar))
								echo $_SESSION[APL]->getButtom('.','Finalizar', '50', 'onclick="guardar(0)"','','middlered');
						?>
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

</body>
<script>
	
</script>
</html>
