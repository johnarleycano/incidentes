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

$sql = "select valor from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_constante WHERE id=3";
$cant_arc = $_SESSION[APL]->bd->dato($sql);

if(isset($_POST['informado_por']) && isset($_POST['informado_por_nombre']))
{
	if(isset($_POST['transito']))
		$transito='SI';
	else
		$transito='NO';

	if(isset($_POST['policia']))
		$policia='SI';
	else
		$policia='NO';

	if(isset($_POST['inspector']))
		$inspector='SI';
	else
		$inspector='NO';

	if($_POST['accion']=='G')
			$estado=2;
		else
			$estado=3;

	$abscisa_real="K".str_pad($_POST['absicsa_evento_p1'],2,'0',STR_PAD_LEFT)."+".str_pad($_POST['absicsa_evento_p2'],2,'0',STR_PAD_LEFT);

	$refe=explode("|",$_POST['referencia']);
	if($refe[0]=='')
		$refe_in=null;
	else
		$refe_in=$refe[0];

	$sentidoVia = $_POST['sentido_via'];
	if( trim($_POST['sentido_via'])=="" )
		$sentidoVia = "NULL";

	$parametros=array(
		'informado_por'=>$_POST['informado_por'],
		'informado_por_nombre'=>$_POST['informado_por_nombre'],
		'hora_salida_base'=>str_pad($_POST['hora_salida_base_h'],2,'0',STR_PAD_LEFT).":".str_pad($_POST['hora_salida_base_m'],2,'0',STR_PAD_LEFT),
		'hora_llegada_sitio'=>str_pad($_POST['hora_llegada_sitio_h'],2,'0',STR_PAD_LEFT).":".str_pad($_POST['hora_llegada_sitio_m'],2,'0',STR_PAD_LEFT),
		'hora_salida_sitio'=>str_pad($_POST['hora_salida_sitio_h'],2,'0',STR_PAD_LEFT).":".str_pad($_POST['hora_salida_sitio_m'],2,'0',STR_PAD_LEFT),
		'hora_llegada_base'=>str_pad($_POST['hora_llegada_base_h'],2,'0',STR_PAD_LEFT).":".str_pad($_POST['hora_llegada_base_m'],2,'0',STR_PAD_LEFT),
		//'sentido'=>$_POST['sentido'],
		'absicsa_salida'=>"K".$_POST['absicsa_salida_p1']."+".$_POST['absicsa_salida_p2'],
		'nro_muertos'=>$_POST['nro_muertos']==''?0:$_POST['nro_muertos'],
		'nro_heridos'=>$_POST['nro_heridos']==''?0:$_POST['nro_heridos'],
		'transito'=>$transito,
		'transito_placa'=>$_POST['transito_placa'],
		'transito_apellido'=>$_POST['transito_apellido'],
		'policia'=>$policia,
		'policia_placa'=>$_POST['policia_placa'],
		'policia_apellido'=>$_POST['policia_apellido'],
		'inspector'=>$inspector,
		'inspector_placa'=>$_POST['inspector_placa'],
		'inspector_apellido'=>$_POST['inspector_apellido'],
		'estado'=>$estado,
		'visualizar_web'=>$_POST['visualizar_web'],
		'tipo_incidente'=>$_POST['tipo_incidente'],
		'referencia'=>$refe_in,
		'via'=>$_POST['via'],
		'tipo_atencion'=>$_POST['tipo_atencion'],
		'observaciones'=>$_POST['observaciones'],
		'condiciones_climaticas'=>$_POST['condiciones'],
		'abscisa_real'=>$abscisa_real,
		'tiempo_apertura'=>$_POST['tiempo_apertura'],
		'municipio1'=>$_POST['municipio1'],
		'municipio2'=>$_POST['municipio2'],
		'municipio_ocurrencia'=>$_POST['municipio_ocurrencia'],
		'abscisa' => $_POST['absicsa_evento_p1'].$_POST['absicsa_evento_p2']
	);

	$sql="SELECT codigo,periodo
	FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente
	WHERE id=".$_POST['id_buscar'];
	$dat=$_SESSION[APL]->bd->getRs($sql);
	$cod_inc=$dat->fields[0];
	$per_inc=$dat->fields[1];
	$c_i=$per_inc."_".$cod_inc;

	$finTipAte='NO';
	if( isset($_POST['finTipAte']) and $_POST['finTipAte']=="SI" )
		$finTipAte='SI';

	$updFecRep = "";
	$updHorRep = "";
	if( $_SESSION[APL]->usuario->id_perfil==0 )
	{
		if( isset($_POST['fecha_reporte']) and $_POST['fecha_reporte']!='' )
			$updFecRep = "fechaincidente='".$_POST['fecha_reporte']."',";

		if( isset($_POST['hora_rep']) and $_POST['hora_rep']!='' )
			$updHorRep = "horaincidente='".$_POST['hora_rep'].":".$_POST['minu_rep']."',";
	}

	$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente
	SET
	informado_por=?,
	informado_por_nombre=?,
	hora_salida_base=?,
	hora_llegada_sitio=?,
	hora_salida_sitio=?,
	hora_llegada_base=?,
	absicsa_salida=?,
	nro_muertos=?,
	nro_heridos=?,
	transito=?,
	transito_placa=?,
	transito_apellido=?,
	policia=?,
	policia_placa=?,
	policia_apellido=?,
	inspector=?,
	inspector_placa=?,
	inspector_apellido=?,";

	if($_POST['accion']=='G')
		$sql.="guardado_sos=1,";
	else
	if($_POST['accion']=='F')
	{
		$sql.="finalizado_sos=1,";
		if( $finTipAte=='SI' )
			$sql.="finalizado_adm_vial=1,";
	}

	$sql.="
	estado=?,
	visualizar_web=?,
	tipo_incidente=?,
	referencia=?,
	via=?,
	tipo_atencion=?,
	observaciones=?	,
	condiciones_climaticas=?,
	abscisa_real=?,
	tiempo_apertura=?,
	sentido_via=$sentidoVia,
	fintipate='$finTipAte',
	$updFecRep
	$updHorRep
	municipio1=?,
	municipio2=?,
	municipio_ocurrencia=?,
	abscisa=?,
	coordenadas=POINT(".$_POST['longitud'].", ".$_POST['latitud'].")
	WHERE
	id=".$_POST['id_buscar']."";

	if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
		echo "<script>alert('Error al crear Incidente')</script>";
	else
	{

		for($l=1;$l<=10;$l++)
		{
			if($_POST['id_'.$l]=='' && $_POST['borrar_'.$l]==0 && $_POST['entidad_'.$l]!='')
			{
				$id_e=$_SESSION[APL]->getSecuencia('dvm_apoyo_entidad','id');
				$hsb=$_POST['hora_salida_base_'.$l];
				$msb=$_POST['minu_salida_base_'.$l];
				$hls=$_POST['hora_llegada_sitio_'.$l];
				$mls=$_POST['minu_llegada_sitio_'.$l];
				$hss=$_POST['hora_salida_sitio_'.$l];
				$mss=$_POST['minu_salida_sitio_'.$l];
				$hlb=$_POST['hora_llegada_base_'.$l];
				$mlb=$_POST['minu_llegada_base_'.$l];

				$parametros=array
						(
							'id'=>$id_e,
							'id_incidente'=>$_POST['id_buscar'],
							'id_entidad'=>$_POST['entidad_'.$l],
							'funcionario'=>$_POST['funcionario_entidad_'.$l],
							'hora_salida_base'=>$hsb.$msb==''?'':str_pad($hsb,2,'0',STR_PAD_LEFT).":".str_pad($msb,2,'0',STR_PAD_LEFT),
							'hora_llegada_sitio'=>$hls.$mls==''?'':str_pad($hls,2,'0',STR_PAD_LEFT).":".str_pad($mls,2,'0',STR_PAD_LEFT),
							'hora_salida_sitio'=>$hss.$mss==''?'':str_pad($hss,2,'0',STR_PAD_LEFT).":".str_pad($mss,2,'0',STR_PAD_LEFT),
							'hora_llegada_base'=>$hlb.$mlb==''?'':str_pad($hlb,2,'0',STR_PAD_LEFT).":".str_pad($mlb,2,'0',STR_PAD_LEFT)

							);


				$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo_entidad (id,id_incidente,id_entidad,funcionario,hora_salida_base,hora_llegada_sitio,hora_salida_sitio,hora_llegada_base)
				VALUEs
				(?,?,?,?,?,?,?,?);";
				if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
					echo "<script>alert('Error al al Crear Apoyo Entidad')</script>";

			}
			else
			if($_POST['id_'.$l]!='' && $_POST['borrar_'.$l]==0 && $_POST['entidad_'.$l]!='')//nuevo lesionado
			{

					$hsb=$_POST['hora_salida_base_'.$l];
					$msb=$_POST['minu_salida_base_'.$l];
					$hls=$_POST['hora_llegada_sitio_'.$l];
					$mls=$_POST['minu_llegada_sitio_'.$l];
					$hss=$_POST['hora_salida_sitio_'.$l];
					$mss=$_POST['minu_salida_sitio_'.$l];
					$hlb=$_POST['hora_llegada_base_'.$l];
					$mlb=$_POST['minu_llegada_base_'.$l];

				$parametros=array
						(
							'id_entidad'=>$_POST['entidad_'.$l],
							'funcionario'=>$_POST['funcionario_entidad_'.$l],
							'hora_salida_base'=>$hsb.$msb==''?'':str_pad($hsb,2,'0',STR_PAD_LEFT).":".str_pad($msb,2,'0',STR_PAD_LEFT),
							'hora_llegada_sitio'=>$hls.$mls==''?'':str_pad($hls,2,'0',STR_PAD_LEFT).":".str_pad($mls,2,'0',STR_PAD_LEFT),
							'hora_salida_sitio'=>$hss.$mss==''?'':str_pad($hss,2,'0',STR_PAD_LEFT).":".str_pad($mss,2,'0',STR_PAD_LEFT),
							'hora_llegada_base'=>$hlb.$mlb==''?'':str_pad($hlb,2,'0',STR_PAD_LEFT).":".str_pad($mlb,2,'0',STR_PAD_LEFT),
							'id'=>$_POST['id_'.$l]
							);


				$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo_entidad
				SET
				id_entidad=?,
				funcionario=?,
				hora_salida_base=?,
				hora_llegada_sitio=?,
				hora_salida_sitio=?,
				hora_llegada_base=?
				WHERE
				id=?";
				if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
					echo "<script>alert('Error al Actulizar Apoyo Entidad')</script>";


			}
			else
			if($_POST['id_'.$l]!='' && $_POST['borrar_'.$l]==1)
			{

					$parametros=array
						(
							'id'=>$_POST['id_'.$l]
							);


				$sql="DELETE FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo_entidad
				WHERE
				id=?";
				if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
					echo "<script>alert('Error al Eliminar Apoyo Entidad')</script>";
			}
		}



		///Archivosss
		$pos_r=1;
		for($l=1;$l<=$cant_arc;$l++)
		{

			if($_POST['id_a_'.$l]=='' && $_POST['borrar_a_'.$l]==0 && $_FILES['archivo_'.$l]['name']!='')
			{
				$id_a=$_SESSION[APL]->getSecuencia('dvm_archivo','id');

				$ext=substr($_FILES['archivo_'.$l]['name'],-3);
				$name=$c_i."_archivo_id_".$id_a.".".$ext;

				$parametros=array
						(
							'id'=>$id_a,
							'id_incidente'=>$_POST['id_buscar'],
							'nombre'=>$name,
							'tipo'=>'ARC'
							);


				$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo (id,id_incidente,nombre,tipo)
				VALUEs
				(?,?,?,?);";
				if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
					echo "<script>alert('Error al al Crear Archivo')</script>";
				else
				{
					if (!move_uploaded_file($_FILES['archivo_'.$l]['tmp_name'],'adjuntos/'.$name))
						echo "<script>alert('Error al Cargar Archivo ".$l."')</script>";
				}
			$pos_r++;

			}
			else
			if($_POST['id_a_'.$l]!='' && $_POST['borrar_a_'.$l]==0 && $_FILES['archivo_'.$l]['name']!='')//nuevo lesionado
			{

				$ext=substr($_FILES['archivo_'.$l]['name'],-3);
				$name=$c_i."_archivo_id_".$_POST['id_a_'.$l].".".$ext;

				$parametros=array
						(
							'nombre'=>$name,
							'id'=>$_POST['id_a_'.$l]
							);

				$sql = "select nombre from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo WHERE id=".$_POST['id_a_'.$l];
				$nombre_e = $_SESSION[APL]->bd->dato($sql);


				$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo
				SET
				nombre=?
				WHERE
				id=?";
				if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
					echo "<script>alert('Error al Actualizar Archivo')</script>";
				else
				{
					if(!unlink('adjuntos/'.$nombre_e))
						echo "<script>alert('Error al Eliminar Adjunto Anterior')</script>";

					if (!move_uploaded_file($_FILES['archivo_'.$l]['tmp_name'],'adjuntos/'.$name))
						echo "<script>alert('Error al Cargar Archivo ".$l."')</script>";
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

				$sql = "select nombre from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo WHERE id=".$_POST['id_a_'.$l];
				$nombre_e = $_SESSION[APL]->bd->dato($sql);


				$sql="DELETE FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo
				WHERE
				id=?";
				if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
					echo "<script>alert('Error al Eliminar Archivo')</script>";
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
			echo "<script>alert('Se Actualizo el incidente ".$_POST['periodo'].".".str_pad($cod_inc,5,"0",STR_PAD_LEFT)." y se elimino de la lista de pendientes');window.parent.filtrar();recargarSOS(".$_POST['id_buscar'].")</script>";
			else
			if($_POST['accion']=='G')
			echo "<script>alert('Se Actualizo el incidente ".$_POST['periodo'].".".str_pad($cod_inc,5,"0",STR_PAD_LEFT)."');window.parent.filtrar();</script>";


		}
	}


}
?>
<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
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
	.cmpPeq2
	{
		width:50px !important;
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

function nueva_entidad(posi)
{

	if(posi<10)
	{
		document.getElementById('tr_'+(parseFloat(posi)+1)).style.display='';
		document.getElementById('borrar_'+(parseFloat(posi)+1)).value=0;

	}
	else
		alert('Limite de 10 superado');
}

function eliminar_entidad(posi)
{


		document.getElementById('tr_'+posi).style.display='none';
		document.getElementById('borrar_'+posi).value=1;


}

function nuevo_archivo(posi)
{

	if(posi<<?php echo $cant_arc?>)
	{
		document.getElementById('tr_a_'+(parseFloat(posi)+1)).style.display='';
		document.getElementById('borrar_a_'+(parseFloat(posi)+1)).value=0;


	}
	else
		alert('Limite de <?php echo $cant_arc?> superado')
}

function eliminar_archivo(posi)
{
		document.getElementById('tr_a_'+posi).style.display='none';
		document.getElementById('borrar_a_'+posi).value=1;


}

function recargar(pos)
{
	window.open('registro_sos_edi.php?id_buscar='+pos,'_self')
}

function cargar_referencias(via)
{

	document.incidente.referencia.length=0;
	document.incidente.absicsa_evento_p1.value='';
	document.incidente.absicsa_evento_p2.value='';
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

	cargar_sentidos(via);
}

function cargar_sentidos(via)
{
	document.incidente.sentido_via.length=0;

	switch(via)
	{
		<?php
			$sql = "SELECT *
					FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_sentido
					order by via, id";
			$rsSen = $_SESSION[APL]->bd->getRs($sql);

			$sent_tmp=0;
			$sent_i=-1;
			$entro = false;

			while (!$rsSen->EOF)
			{
				$entro = true;
				if($sent_tmp!=$rsSen->fields[1])
				{
					if($sent_i!=-1)
						echo "break;";

					echo "case '".$rsSen->fields[1]."':";
					echo "document.incidente.sentido_via.options[0]=new Option(' ','');";

					$sent_tmp=$rsSen->fields[1];
					$sent_i=1;
				}
				echo "document.incidente.sentido_via.options[".$sent_i."]=new Option('".$rsSen->fields[2]."','".$rsSen->fields[0]."');";
				$sent_i++;
				$rsSen->MoveNext();
			}

			if( $entro==true )
				echo 'break;';
		?>
		default:
			alert('Sentido no encontrado');
		break;
	}
}

function colocar_datos_referencia(valor)
{
	texto=valor.split('|');

	texto2=texto[1].split('+');

	document.incidente.absicsa_evento_p1.value=texto2[0].replace('K','');
	document.incidente.absicsa_evento_p2.value=texto2[1];
}

function guardar(tipo)
{
	var finTipAte = "NO";
	if( $("#finTipAte").attr('checked') )
		finTipAte = "SI";

	if( document.incidente.informado_por.value=='' && finTipAte=="NO" )
	{
		alert('Seleccione por quien fue informado el evento')
		document.incidente.informado_por.focus();
	}
	else if(document.incidente.informado_por_nombre.value=='' && finTipAte=="NO" )
	{
		alert('Ingrese el nombre de quien ingreso el evento')
		document.incidente.informado_por_nombre.focus();
	}
	else if(document.incidente.municipio1.value=='')
	{
		alert('Seleccione el municipio 1')
		document.incidente.municipio1.focus();
	}
	else if(document.incidente.municipio2.value=='')
	{
		alert('Seleccione el municipio 2')
		document.incidente.municipio2.focus();
	}
	else if(document.incidente.municipio_ocurrencia.value=='')
	{
		alert('Seleccione el municipio de ocurrencia')
		document.incidente.municipio_ocurrencia.focus();
	}
	else if(document.incidente.latitud.value=='')
	{
		alert('Seleccione la latitud')
		document.incidente.latitud.focus();
	}
	else if(document.incidente.longitud.value=='')
	{
		alert('Seleccione la longitud')
		document.incidente.longitud.focus();
	}
	else if(document.incidente.tipo_atencion.value=='')
	{
		alert('Seleccione el Tipo de Atencion')
		document.incidente.tipo_atencion.focus();
	}
	else if(document.incidente.via.value=='')
	{
		alert('Seleccione la Via')
		document.incidente.via.focus();
	}
	else if(document.incidente.referencia.value=='')
	{
		alert('Seleccione la Referencia')
		document.incidente.referencia.focus();
	}
	else if(tipo==0 && finTipAte=="NO" && calcular_tiempo_evento()==false )
	{
		return false;
	}
	else
	/*if(calcular_hora_estimada_llegada()==false)
	{
		return false;
	}
	else*/
	if(tipo==0 && finTipAte=="NO" && calcular_tiempo_respuesta()==false)
	{
		return false;
	}
	else
	{
		if(tipo==1)
			document.incidente.accion.value='G';
		else if(tipo==0)
			document.incidente.accion.value='F';
		else
			document.incidente.accion.value='V';

		if( document.incidente.accion.value=='V' || document.incidente.accion.value=='G' ||
			( document.incidente.accion.value=='F' && confirm('Esta seguro de FINALIZAR el incidente?, esto lo eliminara de la lista de pendientes'))
		  )
		{
			document.incidente.submit();
		}
	}
}

function ver_incidente(id)
{
	if(id=='')
	{
		alert('Seleccione el Incidente a Visualizar');
	}
	else

		window.open('registro_sos_edi.php?id_buscar='+id,'_self');
}

function calcular_totales()
{
	vi=document.getElementById('visibles').value;

	var lbh = '';
	var lbm = '';
	var sbh = '';
	var sbm = '';
	var ssh = '';
	var ssm = '';
	var lsh = '';
	var lsm = '';


	for(i=1;i<=vi;i++)
	{
		lbh=document.getElementById('hora_llegada_base_'+i).value;
		lbm=document.getElementById('minu_llegada_base_'+i).value;
		sbh=document.getElementById('hora_salida_base_'+i).value;
		sbm=document.getElementById('minu_salida_base_'+i).value;
		ssh=document.getElementById('hora_salida_sitio_'+i).value;
		ssm=document.getElementById('minu_salida_sitio_'+i).value;
		lsh=document.getElementById('hora_llegada_sitio_'+i).value;
		lsm=document.getElementById('minu_llegada_sitio_'+i).value;

		if( lbh=='S/N' ) lbh=='';
		if( lbm=='S/N' ) lbm=='';
		if( sbh=='S/N' ) sbh=='';
		if( sbm=='S/N' ) sbm=='';
		if( ssh=='S/N' ) ssh=='';
		if( ssm=='S/N' ) ssm=='';
		if( lsh=='S/N' ) lsh=='';
		if( lsm=='S/N' ) lsm=='';

		if(lbh!='' || lbm!='' || sbh!='' || sbm!='' || ssh!='' || ssm!='' || lsh!='' || lsm!='')
			calcular_tiempo_evento_base(lbh,lbm,sbh,sbm,ssh,ssm,i,'tiempo_total_'+i)

	}


}

function calcular_tiempo_evento_base(lbh,lbm,sbh,sbm,ssh,ssm,pos,campo)
{



	if(lbh=='00' && lbm=='00')
	{
		lbh='';
		lbm='';
	}
	if(sbh=='00' && sbm=='00')
	{
		sbh='';
		sbm='';
	}

	if(lbh!='' && lbm!='' && sbh!='' && sbm!='')
	{
		var hora_i=ssh;
		var hora_s=lbh;
		var hora_r=sbh;

		var minu_i=ssm;
		var minu_s=lbm;
		var minu_r=sbm;




		if(parseFloat(minu_i)>parseFloat(minu_s))
		{
			minu_s_2=parseFloat(minu_s)+parseFloat(60);
			hora_s_2=parseFloat(hora_s)-parseFloat(1);
		}
		else
		{
			minu_s_2=minu_s;
			hora_s_2=hora_s;
		}

		var horas_2=parseFloat(hora_i)-parseFloat(hora_s_2);
		var minus_2=parseFloat(minu_i)-parseFloat(minu_s_2);

		if(horas_2>0 || (horas_2==0 && minus_2==0))
		{
			alert('La Hora de LLegada a la Base para ['+pos+'] debe debe ser mayor que la de Salida del Sitio')
			return false;
		}







		if(parseFloat(minu_r)>parseFloat(minu_s))
		{
			minu_s=parseFloat(minu_s)+parseFloat(60);
			hora_s=parseFloat(hora_s)-parseFloat(1);
		}

		var horas=parseFloat(hora_s)-parseFloat(hora_r);
		var minus=parseFloat(minu_s)-parseFloat(minu_r);
		if(horas<0 || (horas==0 && minus==0))
		{
			alert('La Hora de LLegada Base para ['+pos+'] debe debe ser mayor que la de Salida')

			return false;
		}
		else
		{
			if(horas<10)
				horas='0'+horas;
			if(minus<10)
				minus='0'+minus;
			document.getElementById(campo).value=horas+":"+minus;

		}
	}
	else
	{

		if(lbh=='' || lbm=='')
		{
			alert('Registre La Hora Llegada a la Base para ['+pos+'] ')
			return false;
		}
		else
		if(sbh=='' || sbm=='')
		{
			alert('Registre la Hora Salida de la Base para ['+pos+'] ');
			return false;
		}
	}
return true;


}

function calcular_tiempo_evento()
{
	var lbh = document.incidente.hora_llegada_base_h.value;
	var lbm = document.incidente.hora_llegada_base_m.value;
	var sbh = document.incidente.hora_salida_base_h.value;
	var sbm = document.incidente.hora_salida_base_m.value;

	if(lbh=='00' && lbm=='00')
	{
		lbh='';
		lbm='';
	}
	if(sbh=='00' && sbm=='00')
	{
		sbh='';
		sbm='';
	}

	if(lbh!='' && lbm!='' && sbh!='' && sbm!='')
	{
		var hora_i=document.incidente.hora_salida_sitio_h.value;
		var hora_s=document.incidente.hora_llegada_base_h.value;
		var hora_r=document.incidente.hora_salida_base_h.value;

		var minu_i=document.incidente.hora_salida_sitio_m.value;
		var minu_s=document.incidente.hora_llegada_base_m.value;
		var minu_r=document.incidente.hora_salida_base_m.value;




		if(parseFloat(minu_i)>parseFloat(minu_s))
		{
			minu_s_2=parseFloat(minu_s)+parseFloat(60);
			hora_s_2=parseFloat(hora_s)-parseFloat(1);
		}
		else
		{
			minu_s_2=minu_s;
			hora_s_2=hora_s;
		}

		var horas_2=parseFloat(hora_i)-parseFloat(hora_s_2);
		var minus_2=parseFloat(minu_i)-parseFloat(minu_s_2);

		if(horas_2>0 || (horas_2==0 && minus_2==0))
		{
			alert('La Hora de LLegada a la Base debe debe ser mayor que la de Salida del Sitio')

			document.incidente.tiempo_respuesta.value='';
			document.incidente.hora_llegada_base_h.focus();
			return false;
		}







		if(parseFloat(minu_r)>parseFloat(minu_s))
		{
			minu_s=parseFloat(minu_s)+parseFloat(60);
			hora_s=parseFloat(hora_s)-parseFloat(1);
		}

		var horas = parseFloat(hora_s)-parseFloat(hora_r);
		var minus = parseFloat(minu_s)-parseFloat(minu_r);

		//if( horas<0 || (horas==0 && minus==0))
		if( horas==0 && minus==0 )
		{
			alert('La Hora de LLegada Base debe debe ser mayor que la de Salida')
			document.incidente.tiempo_total.value='';
			document.incidente.hora_llegada_base_h.focus();
			return false;
		}

		if( horas<0 )
			horas = (parseFloat(hora_s)+24) - parseFloat(hora_r);

		/*else*/
		{
			if(horas<10)
				horas='0'+horas;
			if(minus<10)
				minus='0'+minus;

			document.incidente.tiempo_total.value=horas+":"+minus;
		}
	}
	else
	{

		if(lbh=='' || lbm=='')
		{
			alert('Registre La Hora Llegada a la Base')
			document.incidente.hora_llegada_base_h.focus();
			return false;
		}
		else
		if(sbh=='' || sbm=='')
		{
			alert('Registre la Hora Salida de la Base');
			document.incidente.hora_salida_base_h.focus();
			return false;
		}
	}

	return true;
}

function calcular_tiempo_respuesta()
{
	var lsh = document.incidente.hora_llegada_sitio_h.value;
	var lsm = document.incidente.hora_llegada_sitio_m.value
	var ssh = document.incidente.hora_salida_sitio_h.value;
	var ssm = document.incidente.hora_salida_sitio_m.value;

	if(lsh=='00' && lsm=='00')
	{
		lsh='';
		lsm='';
	}
	if(ssh=='00' && ssm=='00')
	{
		ssh='';
		ssm='';
	}




	if(lsh!='' && lsm!='' && ssh!='' && ssm!='')
	{

		var hora_b=document.incidente.hora_salida_base_h.value;
		var hora_r=document.incidente.hora_llegada_sitio_h.value;
		var hora_s=document.incidente.hora_salida_sitio_h.value;

		var minu_b=document.incidente.hora_salida_base_m.value;
		var minu_r=document.incidente.hora_llegada_sitio_m.value;
		var minu_s=document.incidente.hora_salida_sitio_m.value;


		if(parseFloat(minu_b)>parseFloat(minu_r))
		{
			minu_r_2=parseFloat(minu_r)+parseFloat(60);
			hora_r_2=parseFloat(hora_r)-parseFloat(1);
		}
		else
		{
			minu_r_2=minu_r;
			hora_r_2=hora_r;
		}

		var horas_2=parseFloat(hora_b)-parseFloat(hora_r_2);
		var minus_2=parseFloat(minu_b)-parseFloat(minu_r_2);

		if(horas_2>0 || (horas_2==0 && minus_2==0))
		{
//			alert('La Hora de LLegada al Sitio debe debe ser mayor que la de Salida de la Base')
//
//			document.incidente.tiempo_respuesta.value='';
//			document.incidente.hora_llegada_sitio_h.focus();
//			return false;
		}




		if(parseFloat(minu_r)>parseFloat(minu_s))
		{
			minu_s=parseFloat(minu_s)+parseFloat(60);
			hora_s=parseFloat(hora_s)-parseFloat(1);
		}

		var horas=parseFloat(hora_s)-parseFloat(hora_r);
		var minus=parseFloat(minu_s)-parseFloat(minu_r);

		if(horas<0 || (horas==0 && minus==0))
		{
//			alert('La Hora de Salida del Sitio debe debe ser mayor que la de Llegada')
//
//			document.incidente.tiempo_respuesta.value='';
//			document.incidente.hora_llegada_sitio_h.focus();
//			return false;
		}
		else
		{
			if(horas<10)
				horas='0'+horas;
			if(minus<10)
				minus='0'+minus;
			document.incidente.tiempo_respuesta.value=horas+":"+minus;

		}
	}
	else
	{
		if(lsh=='' || lsm=='')
		{
			alert('Registre La Hora Llegada al Sitio')
			document.incidente.hora_llegada_sitio_h.focus();
			return false;
		}
		else
		if(ssh=='' || ssm=='')
		{
			alert('Registre la Hora Salida del Sitio');
			document.incidente.hora_salida_sitio_h.focus();
			return false;
		}




	}
	return true;
}

function ventana_vehiculos(id)
{
	var w=1224;
	var h=550;
	var iz=(screen.width/2)-(w/2);
    var de=(screen.height/2)-(h/2);
    //window.open("vehiculo_incidente.php?id_buscar="+id,"","width="+w+",height="+h+",left="+iz+",top="+de+", scrollbars=yes,menubars=no,statusbar=yes, status=yes, resizable=NO,location=NO")
	//ver_SOSVehInv(id);
	window.parent.ver_SOSVehInv(id);

}

function nuevo_incidente()
{


	var w=800;
	var h=300;
	var iz=(screen.width/2)-(w/2);
    var de=(screen.height/2)-(h/2);
   window.open("registro_inicial.php?emergente=1","","width="+w+",height="+h+",left="+iz+",top="+de+", scrollbars=yes,menubars=no,statusbar=yes, status=yes, resizable=NO,location=NO")

}

function guardar_vehiculos()
{
	ventana_vehiculos(<?php echo isset($_GET['id_buscar'])?$_GET['id_buscar']:$_POST['id_buscar']; ?>);
	<?php
	if( !isset($_GET["esEdi"]) )
	{
	?>
		guardar(3);
	<?php
	}
	?>
}

function ver_SOSVehInv(idSOS)
{
	$('#ifrVerSOSVehInv').attr('src', "vehiculo_incidente.php?id_buscar="+idSOS)
	$("#venVerSOSVehInv").dialog({ position:'center top' });
	$("#venVerSOSVehInv").dialog("open");
	//alert("Hola");
}

function recargarGrilla()
{

}

<?php
$onLoad = '';
if(isset($_POST['accion']) && $_POST['accion']=='V')
	;//echo "ventana_vehiculos(".$_POST['id_buscar'].");";
?>
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
		via,
		tipo_atencion,
		estado,
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
		tipo_incidente,
		guardado_sos,
		finalizado_sos,
		guardado_adm_vial,
		finalizado_adm_vial,
		nro_heridos,
		periodo,
		observaciones,
		tiempo_apertura,
		abscisa_real,
		sentido_via,
		fechaincidente,
		horaincidente,
		condiciones_climaticas,
		fintipate,
		i.municipio1 m1,
		i.municipio2 m2,
		i.municipio_ocurrencia m3,
		i.municipio1 m4,
		i.municipio1,
		i.municipio2,
		i.municipio_ocurrencia,
		i.coordenadas c1,
		X ( i.coordenadas ) c2,
		Y ( i.coordenadas ) c3,
		i.coordenadas c4,
		i.coordenadas,
		X ( i.coordenadas ) longitud,
		Y ( i.coordenadas ) latitud
	FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i
		left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia  as r on (i.referencia=r.id)
	WHERE
		i.id=?";

	$inci=$_SESSION[APL]->bd->getRsO($sql,$parametro);
	$id_buscar=$inci->fields[0];
	$fecha=$inci->fields[1];
	$fecInc =$inci->fields[48];
	$horInc =$inci->fields[49];


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

	if( $tmpHorRep!='')
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

	$tipo_atencion=$inci->fields[5];
	$estado=$inci->fields[6];
	if($inci->fields[46]!='')
		$abscisap=$inci->fields[46];
	else
		$abscisap=$inci->fields[7];

	if($abscisap!='')
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
	$condicion=$inci->fields[50];
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

	$nro_muertos=$inci->fields[21];
	$nro_heridos=$inci->fields[22];

	$transito=$inci->fields[23];
	$transito_placa=$inci->fields[24];
	$transito_apellido=$inci->fields[25];
	$policia=$inci->fields[26];
	$policia_placa=$inci->fields[27];
	$policia_apellido=$inci->fields[28];
	$inspector=$inci->fields[29];
	$inspector_placa=$inci->fields[30];
	$inspector_apellido=$inci->fields[31];

	$visualizar_web=$inci->fields[32];
	$archivo1=$inci->fields[33];
	$archivo2=$inci->fields[34];
	$archivo3=$inci->fields[35];
	$archivo4=$inci->fields[36];
	$tipo_incidente=$inci->fields[37];

	$guardado_sos=$inci->fields[38];
	$finalizado_sos=$inci->fields[39];
	$guardado_adm_vial=$inci->fields[40];
	$finalizado_adm_vial=$inci->fields[41];
	$nro_heridos=$inci->fields[42];
	$periodo=$inci->fields[43];
	$observaciones=$inci->fields[44];
	$tiempo_apertura=$inci->fields[45];
	$sentido_via =$inci->fields[47];
	$finTipAte =$inci->fields[50];
	$municipio1 =$inci->fields[56];
	$municipio2 =$inci->fields[57];
	$municipio_ocurrencia =$inci->fields[58];
	$longitud =$inci->fields[65];
	$latitud =$inci->fields[64];
}// fin si hay busqueda

?>


<!-- form name="listar_incidente" method="post" action="registro_sos_edi.php" -->
<form name="incidente" method="post" action="registro_sos_edi.php" enctype="multipart/form-data">
<?php
// Si hay busqueda
if( isset($id_buscar) )
{
?>
	<table class="cssBus tabEdi" cellpadding="3" border="0">
		<tr><th class="LegendSt" style="background-color:#4CB877">Datos Generales Central SOS</th></tr>
		<tr>
			<td>
				<table width="100%" cellpadding="3">

					<tr>
						<th class="resaltar">Estado</th>
						<td colspan="5">
							<img src="img/verde.png"  title="Registro Inicial Completo"/>&nbsp;&nbsp;
							<?php
								if($finalizado_sos==1)
									echo '<img src="img/verde.png" title="Finalizado por SOS"/>';
								else if($guardado_sos==1)
									echo '<img src="img/amarillo.png" title="Guardado por SOS"/>';
								else
									echo '<img src="img/gris.png" title="Pendiente por SOS"/>';
							?>
							&nbsp;&nbsp;
							<?php
								if($finalizado_adm_vial==1)
									echo '<img src="img/verde.png" />';
								else if($guardado_adm_vial==1)
									echo '<img src="img/amarillo.png" />';
								else
									echo '<img src="img/gris.png" />';
							?>
						</td>
					</tr>
					<tr>
						<th class="resaltar" bgcolor="#CCCCCC">INCIDENTES PENDIENTES</th>
						<td align="left">
							<input type="hidden" name="periodo" value="<?php if(isset($id_buscar)) echo $periodo;?>"/>
							<table>
								<tr>
									<td>
										<select name="id_buscar" class="selPeq campos">
											<option value=""></option>
											<?php
												$sql = "SELECT id,periodo,codigo,finalizado_sos
														FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente ";
												if($_SESSION[APL]->usuario->id_perfil!=0 && $_SESSION[APL]->usuario->id_perfil!=3)
													$sql.="WHERE id_usuario='".$_SESSION[APL]->usuario->id."'";

												$sql.=" ORDER BY id DESC";
												$rs=$_SESSION[APL]->bd->getRs($sql);

												while (!$rs->EOF)
												{
													echo "<option value='".$rs->fields[0]."' ";
													if(isset($id_buscar) && $id_buscar==$rs->fields[0])
														echo "selected";
													echo ">".$rs->fields[1].".".str_pad($rs->fields[2],5,"0",STR_PAD_LEFT)." ";
													if($rs->fields[3]==1)
														echo "*";
													echo "</option>";
													$rs->MoveNext();
												}
												$rs->close();
											?>
										</select>
									</td>
									<td>
										<span class="cssOcultar">
											<input type="button" value="Ver" class="vbotones" onclick="ver_incidente(document.incidente.id_buscar.value)" />
										</span>
									</td>
									<td>
										<span class="cssOcultar">
											<?php echo $_SESSION[APL]->getButtom('.','Nuevo Incidente', '50', 'onclick=nuevo_incidente()','','middlered'); ?>
										</span>
									</td>
								</tr>
							</table>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">MOSTRAR EN WEB</span></th>
						<td class="style1" align="left">
							<select  name="visualizar_web" class="campos cmpPeq2">
								<option  value="NO" <?php if(isset($id_buscar) && $visualizar_web=='NO') echo "selected"?>>NO</option>
								<option  value="SI" <?php if(isset($id_buscar) && $visualizar_web=='SI') echo "selected"?>>SI</option>
							</select>
						</td>
						<th class="resaltar">Tipo Incidente</th>
						<td align="left" colspan="5">
							<select  name="tipo_incidente" class="campos">
								<option value="v" <?php if(isset($id_buscar) && $tipo_incidente=='NO') echo "selected"?>>Via sin ning&uacute;n tipo de problema en su recorrido</option>
								<option value="a" <?php if(isset($id_buscar) && $tipo_incidente=='SI') echo "selected"?>>Via con alguna restricci&oacute;n en su recorrido</option>
								<option value="r" <?php if(isset($id_buscar) && $tipo_incidente=='SI') echo "selected"?>>Via que presenta problemas en su recorrido</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">INFORMACI&Oacute;N B&Aacute;SICA</th></tr>
		<tr>
			<td>
				<table width="100%" cellpadding="3">
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Fecha</span></th>
						<td>
							<?php
							if( $_SESSION[APL]->usuario->id_perfil==0 )
							{
								$fecReporte = "";
								if(isset($id_buscar))
									$fecReporte = $ano_rep."-".$mes_rep."-".$dia_rep;

							?>
								<!-- UI -->
								<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
								<link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
								<input type="text" name="fecha_reporte" id="fecha_reporte" maxlength="10" value="<?php echo $fecReporte; ?>" style="width:100px"/>
								(yyyy-mm-dd)
								<script>vis_ponCampoFecha2("#fecha_reporte");</script>
							<?php
							}
							else
							{
							?>
								<span class="style1"><b>A&ntilde;o</b> <?php if(isset($id_buscar)) echo $ano_rep?></span>
								<span class="style1"><b>Mes</b> <?php if(isset($id_buscar)) echo $mes_rep?></span>
								<span class="style1"><b>Dia</b> <?php if(isset($id_buscar)) echo $dia_rep?></span>
							<?php
							}
							?>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Reportado por</span></th>
						<td class="style1" align="center">
							<select name="informado_por" class="campos">
								<option value=""></option>
								<?php
									$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado ORDER BY nombre";
									$rs=$_SESSION[APL]->bd->getRs($sql);

									while( !$rs->EOF )
									{
										echo "<option value='".$rs->fields[0]."' ";
										if(isset($id_buscar) && $informado_por==$rs->fields[0])
												echo "selected";
										echo ">".$rs->fields[1]."</option>";
										$rs->MoveNext();
									}
									$rs->close();
								?>
							</select>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Nombre</span></th>
						<td class="style1"><input type="text" class="campos" name="informado_por_nombre"  value="<?php if(isset($id_buscar)) echo $informado_por_nombre;?>" maxlength="100" size="30"/></td>
					</tr>
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Operador SOS</span></th>
						<td class="style1"><?php if(isset($id_buscar)) echo $nombre_usuario?></td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Cedula</span></th>
						<td class="style1"><?php  if(isset($id_buscar)) echo $identificacion_usuario?></td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Hora Reporte</span></th>
						<td class="style1">
							<?php
								$vHorRep = '';
								$vMinRep = '';
								if( isset($id_buscar) )
								{
									$vHorRep = $hora_rep;
									$vMinRep = $minu_rep;
								}

								$disFecRep = "disabled";
								if( $_SESSION[APL]->usuario->id_perfil==0 )
									$disFecRep = "";


								echo $_SESSION[APL]->getComboHora("hora_rep",$disFecRep,$vHorRep);
								echo ":";
								echo $_SESSION[APL]->getComboMinu("minu_rep",$disFecRep,$vMinRep);
							?>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Hora Salida de Base</span></th>
						<td class="style1">
							<?php
								$vHora = '';
								$vMinu = '';
								if( isset($id_buscar) )
								{
									$vHora = $hora_salida_base_h;
									$vMinu = $hora_salida_base_m;
								}

								echo $_SESSION[APL]->getComboHora("hora_salida_base_h","",$vHora);
								echo ":";
								echo $_SESSION[APL]->getComboMinu("hora_salida_base_m","",$vMinu);
							?>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Hora Llegada al Sitio</span></th>
						<td class="style1">
							<?php
								$vHora = '';
								$vMinu = '';
								if( isset($id_buscar) )
								{
									$vHora = $hora_llegada_sitio_h;
									$vMinu = $hora_llegada_sitio_m;
								}

								echo $_SESSION[APL]->getComboHora("hora_llegada_sitio_h","",$vHora);
								echo ":";
								echo $_SESSION[APL]->getComboMinu("hora_llegada_sitio_m","",$vMinu);
							?>
						</td>
					</tr>
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Hora Salida del Sitio</span></th>
						<td class="style1">
							<?php
								$vHora = '';
								$vMinu = '';
								if( isset($id_buscar) )
								{
									$vHora = $hora_salida_sitio_h;
									$vMinu = $hora_salida_sitio_m;
								}

								echo $_SESSION[APL]->getComboHora("hora_salida_sitio_h","",$vHora);
								echo ":";
								echo $_SESSION[APL]->getComboMinu("hora_salida_sitio_m","",$vMinu);
							?>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Hora LLegada a Base</span></th>
						<td class="style1">
							<?php
								$vHora = '';
								$vMinu = '';
								if( isset($id_buscar) )
								{
									$vHora = $hora_llegada_base_h;
									$vMinu = $hora_llegada_base_m;
								}

								echo $_SESSION[APL]->getComboHora("hora_llegada_base_h","",$vHora);
								echo ":";
								echo $_SESSION[APL]->getComboMinu("hora_llegada_base_m","",$vMinu);
							?>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Tiempo Apertura</span></th>
						<td class="style1">
							<input type="text" name="tiempo_apertura" value="<?php if(isset($id_buscar)) echo $tiempo_apertura?>" class="campos cmpPeq" size="10"/>
						</td>
					</tr>
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Tiempo Reaccion</span></th>
						<td class="style1">
							<input type="text" size="5" name="tiempo_reaccion" style="text-align:center" class="resaltar cmpPeq" onkeypress="return false" bgcolor="#CCFF66"
							value="<?php
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
											$horas = ($hora_s+24)-$hora_r;
										}
										//else
											$tiempo_reaccion=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);

										echo $tiempo_reaccion;
									}
								?>">
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Tiempo Respuesta</span></th>
						<td class="style1">
							<input type="text" size="5" name="tiempo_respuesta" id="tiempo_respuesta" style="text-align:center" class="resaltar cmpPeq" onkeypress="return false" bgcolor="#CCFF66"
							value="<?php
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

									$horas = $hora_s-$hora_r;
									$minus = $minu_s-$minu_r;
									if($horas<0)
									{
										$tiempo_respuesta='';
										$horas = ($hora_s+24)-$hora_r;
									}
									//else
										$tiempo_respuesta=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);

									echo $tiempo_respuesta;
								}

								?>">
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Duracion Total del Evento</span></th>
						<td class="style1">
							<input type="text" size="5" name="tiempo_total" id="tiempo_total" class="resaltar cmpPeq" style="text-align:center" onkeypress="return false" bgcolor="#CCFF66"
							value="<?php
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

									$horas = $hora_s-$hora_r;
									$minus = $minu_s-$minu_r;
									if($horas<0)
									{
										$tiempo_total='';
										$horas = ($hora_s+24) - $hora_r;
									}
									//else
										$tiempo_total=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);

									echo $tiempo_total;
								}
							?>">
						</td>
					</tr>
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Tramo</span></th>
						<td class="style1">
							<select name="via" class="campos" onchange="cargar_referencias(this.value);">
								<option value=""></option>
								<?php
									$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via ORDER BY nombre";
									$rs=$_SESSION[APL]->bd->getRs($sql);

									while (!$rs->EOF) {
										echo "<option value='".$rs->fields[0]."' ";
										if(isset($id_buscar) && $via==$rs->fields[0])
												echo "selected";
										echo ">".$rs->fields[1]."</option>";
										$rs->MoveNext();
									}
									$rs->close();
								?>
							</select>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Sitio de Referencia</span></th>
						<td class="style1">
							<select name="referencia" class="campos" onchange="colocar_datos_referencia(this.value)">
								<option value=""></option>
								<?php
									if(isset($id_buscar))
									{
										$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia WHERE id_via=".$via." ORDER BY referencia";
										$refe2=$_SESSION[APL]->bd->getRs($sql);
										while (!$refe2->EOF)
										{

											echo "<option value='".$refe2->fields[0]."|".$refe2->fields[2]."|".$refe2->fields[5]."' ";
											if(isset($id_buscar) && $referencia==$refe2->fields[0])
													echo "selected";
											echo ">".$refe2->fields[4]."</option>";
											$refe2->MoveNext();
										}
										$refe2->close();
									}
								?>
							</select>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Sentido</span></th>
						<td class="style1">
							<select name="sentido_via" class="campos">
								<option value=""></option>
								<?php
									if(isset($id_buscar))
									{
										$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_sentido WHERE via=".$via." ORDER BY id";
										$sen=$_SESSION[APL]->bd->getRs($sql);
										while (!$sen->EOF)
										{

											echo "<option value='".$sen->fields[0]."' ";
											if(isset($id_buscar) && $sentido_via==$sen->fields[0])
													echo "selected";
											echo ">".$sen->fields[2]."</option>";
											$sen->MoveNext();
										}
										$refe2->close();
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Tipo de Atencion (Comentario)</span></th>
						<td class="style1">
							<table>
								<tr>
									<td>
										<select name="tipo_atencion" class="campos" style="width:190px">
											<option value=""></option>
											<?php
												$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion ORDER BY nombre";
												$rs=$_SESSION[APL]->bd->getRs($sql);

												while (!$rs->EOF) {
													echo "<option value='".$rs->fields[0]."' ";
													if(isset($id_buscar) && $tipo_atencion==$rs->fields[0])
															echo "selected";
													echo ">".$rs->fields[1]."</option>";
													$rs->MoveNext();
												}
												$rs->close();
											?>
										</select>
									</td>
									<?php
									$chkFinTipAte = '';
									if( isset($finTipAte) and $finTipAte=="SI" )
										$chkFinTipAte = 'checked';
									?>
									<td><input type="checkbox" id="finTipAte" name="finTipAte" value="SI" <?php echo $chkFinTipAte; ?>></td>
									<td>Finaliza</td>
								</tr>
							</table>


						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Abscisa de Salida</span></th>
						<td class="style1">
							<input type="text" name="absicsa_salida_p1" value="<?php if(isset($id_buscar)) echo $absicsa_salida_p1?>" class="campos cmpPeq" size="3" /><!--onblur="calcular_hora_estimada_llegada()"-->
							<input type="text" name="absicsa_salida_p2" value="<?php if(isset($id_buscar)) echo $absicsa_salida_p2?>" class="campos cmpPeq" size="3"/>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Abscisa del Evento</span></th>
						<td class="style1">
							<input type="text" name="absicsa_evento_p1" value="<?php if(isset($id_buscar)) echo $absicsa_evento_p1?>" class="campos cmpPeq" size="3" /><!--onblur="calcular_hora_estimada_llegada()"-->
							<input type="text" name="absicsa_evento_p2" value="<?php if(isset($id_buscar)) echo $absicsa_evento_p2?>" class="campos cmpPeq" size="3"/>
						</td>
					</tr>
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Observaciones</span></th>
						<td colspan="3">
							<textarea name="observaciones" style="height:40px;width:700px" class="campos"><?php echo $observaciones?></textarea>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">CONDICIONES CLIMATICAS</span></th>

							<td class="style1">

							<select name="condiciones" class="campos" style="width:200px" value="<?php echo $condicion; ?>">
									<option value=""></option>

									<option value="GRANIZADA" <?php if(isset($id_buscar) && $condicion=='GRANIZADA') echo "selected"?>>GRANIZADA</option>
									<option value="LLUVIA" <?php if(isset($id_buscar) && $condicion=='LLUVIA') echo "selected"?>>LLUVIA</option>
									<option value="NIEBLA" <?php if(isset($id_buscar) && $condicion=='NIEBLA') echo "selected"?>>NIEBLA</option>
									<option value="NORMALES" <?php if(isset($id_buscar) && $condicion=='NORMALES') echo "selected"?>>NORMALES</option>
									<option value="VIENTO" <?php if(isset($id_buscar) && $condicion=='VIENTO') echo "selected"?>>VIENTO</option>

							</select>
							</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">DATOS DE UBICACI&Oacute;N Y COORDENADAS</th></tr>
		<tr>
			<td>
				<table width="100%" cellpadding="3">
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Municipio 1</span></th>
						<td class="style1">
							<select name="municipio1" class="campos">
								<option value=""></option>
								<?php
									$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_municipio ORDER BY nombre";
									$rs=$_SESSION[APL]->bd->getRs($sql);

									while (!$rs->EOF) {
										echo "<option value='".$rs->fields[0]."' ";
										if(isset($id_buscar) && $municipio1==$rs->fields[0])
												echo "selected";
										echo ">".$rs->fields[1]."</option>";
										$rs->MoveNext();
									}
									$rs->close();
								?>
							</select>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Municipio 2</span></th>
						<td class="style1">
							<select name="municipio2" class="campos">
								<option value=""></option>
								<?php
									$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_municipio ORDER BY nombre";
									$rs=$_SESSION[APL]->bd->getRs($sql);

									while (!$rs->EOF) {
										echo "<option value='".$rs->fields[0]."' ";
										if(isset($id_buscar) && $municipio2==$rs->fields[0])
												echo "selected";
										echo ">".$rs->fields[1]."</option>";
										$rs->MoveNext();
									}
									$rs->close();
								?>
							</select>
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Municipio de ocurrencia</span></th>
						<td class="style1">
							<select name="municipio_ocurrencia" class="campos">
								<option value=""></option>
								<?php
									$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_municipio ORDER BY nombre";
									$rs=$_SESSION[APL]->bd->getRs($sql);

									while (!$rs->EOF) {
										echo "<option value='".$rs->fields[0]."' ";
										if(isset($id_buscar) && $municipio_ocurrencia==$rs->fields[0])
												echo "selected";
										echo ">".$rs->fields[1]."</option>";
										$rs->MoveNext();
									}
									$rs->close();
								?>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td>
				<table width="100%" cellpadding="3">
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Latitud</span></th>
						<td class="style1">
							<input type="number" name="latitud" value="<?php echo (isset($id_buscar) && $latitud != "") ? $latitud : 0; ?>" class="campos" />
						</td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Longitud</span></th>
						<td class="style1">
							<input type="number" name="longitud" value="<?php echo (isset($id_buscar) && $longitud != "") ? $longitud : 0; ?>" class="campos" />
						</td>
						<!-- <th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Latitud</span></th> -->
						<!-- <td class="style1">
							<input type="text" name="" value="<?php // if(isset($id_buscar)) echo $absicsa_salida_p1?>" class="campos" />
						</td> -->
					</tr>
				</table>
			</td>
		</tr>

		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">APOYO EN ATENCI&Oacute;N</th></tr>
		<tr>
			<td align="center">
				<table cellpadding="3">
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar" rowspan="2" align="center"><span class="style1">Posicion</span></th>
						<th bgcolor="#CCCCCC" class="resaltar" rowspan="2" align="center"><span class="style1">Entidad</span></th>
						<th bgcolor="#CCCCCC" class="resaltar" rowspan="2" align="center"><span class="style1">Nombre Funcioario</span></th>
						<th bgcolor="#CCCCCC" class="resaltar" colspan="5" align="center"><span class="style1">Duracion del Evento</span></th>
						<th bgcolor="#CCCCCC" class="resaltar" rowspan="2"><span class="style1">&nbsp;</span></th>
					</tr>
					<tr>
						<th bgcolor="#CCCCCC" class="resaltar">Hora Salida Base</th>
						<th bgcolor="#CCCCCC" class="resaltar">Hora Llegada Sitio</th>
						<th bgcolor="#CCCCCC" class="resaltar">Hora Salida Sitio</th>
						<th bgcolor="#CCCCCC" class="resaltar">Hora Llegada Base</th>
						<th bgcolor="#CCCCCC" class="resaltar">Duracion Total</th>
					</tr>
					<?php
						$sql = "SELECT id,nombre
								FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo
								ORDER BY nombre";
						$apo=$_SESSION[APL]->bd->getRs($sql);

						$sql = "SELECT id, id_entidad,funcionario,hora_salida_base,hora_llegada_sitio,hora_salida_sitio,hora_llegada_base
								FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo_entidad
								WHERE id_incidente=".$id_buscar."
								ORDER BY funcionario ASC";
						$apo_enti=$_SESSION[APL]->bd->getRs($sql);

					$visi_l=0;
					for($l=1;$l<=10;$l++)
					{
						if(isset($apo_enti) && !$apo_enti->EOF)
						{
							$estilo_l='';
							$visi_l++;
							$posi=$l;
							$id_a=$apo_enti->fields[0];
							$id_e=$apo_enti->fields[1];
							$funci=$apo_enti->fields[2];

							if($apo_enti->fields[3]!='')
							{
								$sb=explode(":",$apo_enti->fields[3]);
								$hora_salida_base=$sb[0];
								$minu_salida_base=$sb[1];
							}
							else
							{
								$hora_salida_base='';
								$minu_salida_base='';
							}
							if($apo_enti->fields[4]!='')
							{
								$ls=explode(":",$apo_enti->fields[4]);
								$hora_llegada_sitio=$ls[0];
								$minu_llegada_sitio=$ls[1];
							}
							else
							{
								$hora_llegada_sitio='';
								$minu_llegada_sitio='';
							}


							if($apo_enti->fields[5]!='')
							{
								$ss=explode(":",$apo_enti->fields[5]);
								$hora_salida_sitio=$ss[0];
								$minu_salida_sitio=$ss[1];
							}
							else
							{
								$hora_salida_sitio='';
								$minu_salida_sitio='';
							}

							if($apo_enti->fields[6]!='')
							{
								$lb=explode(":",$apo_enti->fields[6]);
								$hora_llegada_base=$lb[0];
								$minu_llegada_base=$lb[1];
							}
							else
							{
								$hora_llegada_base='';
								$minu_llegada_base='';
							}

							$apo_enti->MoveNext();
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
							$id_e='';
							$funci='';
							$hora_salida_base='';
							$minu_salida_base='';
							$hora_llegada_sitio='';
							$minu_llegada_sitio='';
							$hora_salida_sitio='';
							$minu_salida_sitio='';
							$hora_llegada_base='';
							$minu_llegada_base='';
						}
					?>
					<tr id="tr_<?php echo $posi?>" <?php echo $estilo_l;?> style="height:30px !important">
						<td class="style1" align="center"><?php echo $posi?></td>
						<td class="style1" align="center" >
							<select name="entidad_<?php echo $posi?>" id="entidad_<?php echo $posi?>" class="campos">
								<option value=""></option>
								<?php
									$apo->MoveFirst();
									while (!$apo->EOF) {
										echo "<option value='".$apo->fields[0]."' ";
										if(isset($id_buscar) && $id_e==$apo->fields[0])
											echo "selected";
										echo ">".$apo->fields[1]."</option>";
										$apo->MoveNext();
								}
								?>
							</select>
						</td>
						<td class="style1" align="center">
							<input type="text" name="funcionario_entidad_<?php echo $posi?>" id="funcionario_entidad_<?php echo $posi?>" value="<?php if(isset($id_buscar)) echo $funci;?>" class="campos" />
							<input type="hidden" name="id_<?php echo $posi?>" id="id_<?php echo $posi?>" value="<?php echo $id_a;?>"/>
							<input type="hidden" name="borrar_<?php echo $posi?>" id="borrar_<?php echo $posi?>" value="<?php
								if($id_a!='' || $posi==1)
									echo "0";
								else
									echo "1";
							?>"/>
						</td>
						<td class="style1" align="center">
							<?php
								$vHora = '';
								$vMinu = '';
								if( isset($id_buscar) )
								{
									$vHora = $hora_salida_base;
									$vMinu = $minu_salida_base;
								}

								echo '<table><tr><td>';
								echo $_SESSION[APL]->getComboHora("hora_salida_base_".$posi,"",$vHora);
								echo "</td><td>:</td><td>";
								echo $_SESSION[APL]->getComboMinu("minu_salida_base_".$posi,"",$vMinu);
								echo '</td></tr></table>';
							?>
						</td>
						<td class="style1" align="center">
							<?php
								$vHora = '';
								$vMinu = '';
								if( isset($id_buscar) )
								{
									$vHora = $hora_llegada_sitio;
									$vMinu = $minu_llegada_sitio;
								}

								echo '<table><tr><td>';
								echo $_SESSION[APL]->getComboHora("hora_llegada_sitio_".$posi,"",$vHora);
								echo "</td><td>:</td><td>";
								echo $_SESSION[APL]->getComboMinu("minu_llegada_sitio_".$posi,"",$vMinu);
								echo '</td></tr></table>';
							?>
						</td>
						<td class="style1" align="center">
							<?php
								$vHora = '';
								$vMinu = '';
								if( isset($id_buscar) )
								{
									$vHora = $hora_salida_sitio;
									$vMinu = $minu_salida_sitio;
								}

								echo '<table><tr><td>';
								echo $_SESSION[APL]->getComboHora("hora_salida_sitio_".$posi,"",$vHora);
								echo "</td><td>:</td><td>";
								echo $_SESSION[APL]->getComboMinu("minu_salida_sitio_".$posi,"",$vMinu);
								echo '</td></tr></table>';
							?>
						</td>
						<td class="style1" align="center">
							<?php
								$vHora = '';
								$vMinu = '';
								if( isset($id_buscar) )
								{
									$vHora = $hora_llegada_base;
									$vMinu = $minu_llegada_base;
								}

								echo '<table><tr><td>';
								echo $_SESSION[APL]->getComboHora("hora_llegada_base_".$posi,"",$vHora);
								echo "</td><td>:</td><td>";
								echo $_SESSION[APL]->getComboMinu("minu_llegada_base_".$posi,"",$vMinu);
								echo '</td></tr></table>';
							?>
						</td>
						<td class="style1" align="center">
							<?php
								if(isset($id_buscar) && $hora_llegada_base!='' && $hora_llegada_sitio!='' && $hora_salida_sitio!='' && $hora_llegada_base!='')
								{
									$hora_s=$hora_llegada_base;
									$hora_r=$hora_salida_base;

									$minu_s=$minu_llegada_base;
									$minu_r=$minu_salida_base;
									if($minu_r>$minu_s)
									{
										$minu_s=$minu_s+60;
										$hora_s=$hora_s-1;
									}

									$horas=$hora_s-$hora_r;
									$minus=$minu_s-$minu_r;
									if($horas<0)
										$tiempo_total='';
									else
										$tiempo_total=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
								}
								else
									$tiempo_total='';
							?>
							<input type="text" name="tiempo_total_<?php echo $posi?>" id="tiempo_total_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $tiempo_total?>" class="campos cmpPeq" size="5" disabled/>
						</td>
						<td>
							<table>
								<tr>
									<td><?php echo $_SESSION[APL]->getButtom2('.','[+]', '40', 'onclick="nueva_entidad('.$posi.')"','Agregar Siguiente Entidad'); ?></td>
									<td><?php echo $_SESSION[APL]->getButtom2('.','[-]', '40', 'onclick="eliminar_entidad('.$posi.')"','Eliminar Entidad','','middlered'); ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<?php
					}// Fin For
					?>
					<input type="hidden" name="visibles" id="visibles" value="<?php echo $visi_l?>" />
					<tr style="height:30px !important">
						<th colspan="9" align="center">
							<center>
								<span class="cssOcultar">
									<?php echo $_SESSION[APL]->getButtom('.','Calcular Tiempos', '50', 'onclick=calcular_totales()'); ?>
								</span>
							</center>
						</th>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">VEHICULOS INVOLUCRADOS, AFECTADOS/ LESIONADOS Y/O MUERTOS</th></tr>
		<tr>
			<td align="center">
				<table cellpadding="3">
					<tr>
						<td colspan="4" align="center" height="50px" valign="middle">
						<?php
							echo $_SESSION[APL]->getButtom('.','Vehiculos Involucrados, Afectados / Lesionados y/o Muertos', '50', 'onclick="guardar_vehiculos()"');

							if(isset($id_buscar))
							{
								$sql = "SELECT COUNT(*)
										FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
										WHERE id_incidente=".$id_buscar;
								$vehi = $_SESSION[APL]->bd->dato($sql);

								$sql = "SELECT COUNT(*)
										FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
										WHERE lesionado='SI' and id_vehiculo IN
										( SELECT id_vehiculo
										  FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
										  WHERE id_incidente=".$id_buscar.")";
								$les = $_SESSION[APL]->bd->dato($sql);

								$sql = "SELECT COUNT(*)
										FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
										WHERE muerto='SI' and id_vehiculo IN
										( SELECT id_vehiculo
										  FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
										  WHERE id_incidente=".$id_buscar.") ";
								$mue = $_SESSION[APL]->bd->dato($sql);

								echo $vehi." Vehiculo(s) y ".$les." Lesionado(s) y ".$mue." Muerto (s) Registrado(s)";
							}
						?>
						</td>
					</tr>
					<tr>
						<th  bgcolor="#CCCCCC" class="resaltar"><span class="style1">Heridos</span></th>
						<td class="style1"><input type="text" name="nro_heridos" value="<?php if(isset($id_buscar)) echo $nro_heridos?>" size="3" class="resaltar cmpPeq" onkeypress="return false" style="text-align:center"/></td>
						<th  bgcolor="#CCCCCC" class="resaltar"><span class="style1">Muertos</span></th>
						<td class="style1"><input type="text" name="nro_muertos" value="<?php if(isset($id_buscar)) echo $nro_muertos?>" size="3" class="resaltar cmpPeq" onkeypress="return false" style="text-align:center"/></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">AUTORIDAD COMPETENTE</th></tr>
		<tr>
			<td align="center">
				<table cellpadding="3">
					<tr>
						<th width="220"  bgcolor="#CCCCCC" class="resaltar"><span class="style1">Policia Transito y Transporte</span></th>
						<td class="style1" align="center"><input name="policia" type="checkbox" class="campos" <?php if(isset($id_buscar) && $policia=='SI') echo "checked"?>/></td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Placa</span></th>
						<td class="style1" align="center" ><input type="text" name="policia_placa" class="campos cmpPeq" value="<?php if(isset($id_buscar)) echo $policia_placa?>"></td>
						<th  bgcolor="#CCCCCC" class="resaltar"><span class="style1">Nombre Completo</span></th>
						<td class="style1" align="center" ><input type="text" name="policia_apellido" class="campos" value="<?php if(isset($id_buscar)) echo $policia_apellido?>"></td>
					</tr>
					<tr>
						<th width="220"  bgcolor="#CCCCCC" class="resaltar"><span class="style1">Transito</span></th>
						<td class="style1" align="center"><input name="transito" type="checkbox" class="campos" <?php if(isset($id_buscar) && $transito=='SI') echo "checked"?>/></td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Placa</span></th>
						<td class="style1" align="center" ><input type="text" name="transito_placa" class="campos cmpPeq" value="<?php if(isset($id_buscar)) echo $transito_placa?>"></td>
						<th  bgcolor="#CCCCCC" class="resaltar"><span class="style1">Nombre Completo</span></th>
						<td class="style1" align="center" ><input type="text" name="transito_apellido" class="campos" value="<?php if(isset($id_buscar)) echo $transito_apellido?>"></td>
					</tr>
					<tr>
						<th width="220"  bgcolor="#CCCCCC" class="resaltar"><span class="style1">Inspector</span></th>
						<td class="style1" align="center"><input name="inspector" type="checkbox" class="campos" <?php if(isset($id_buscar) && $inspector=='SI') echo "checked"?>/></td>
						<th bgcolor="#CCCCCC" class="resaltar"><span class="style1">Placa</span></th>
						<td class="style1" align="center" ><input type="text" name="inspector_placa" class="campos cmpPeq" value="<?php if(isset($id_buscar)) echo $inspector_placa?>"></td>
						<th  bgcolor="#CCCCCC" class="resaltar"><span class="style1">Nombre Completo</span></th>
						<td class="style1" align="center"><input type="text" name="inspector_apellido" class="campos" value="<?php if(isset($id_buscar)) echo $inspector_apellido?>"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><th style="height:2px"></th></tr>
		<tr><th class="LegendSt" style="background-color:#4CB877">ARCHIVOS</th></tr>
		<tr>
			<td align="center">
				<table cellpadding="3">
					<?php
						$sql = "SELECT id,nombre
								FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo
								WHERE tipo='ARC' AND id_incidente=".$id_buscar."";
						$archi=$_SESSION[APL]->bd->getRs($sql);

						$visi_l=0;
						for($l=1;$l<=$cant_arc;$l++)
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
							<tr id="tr_a_<?php echo $posi?>" <?php echo $estilo_l;?> style="height:30px !important">
								<td class="style1" align="center"><?php echo $posi?></td>
								<td class="style1" align="center">
									<span class="cssOcultar">
										<input type="file" name="archivo_<?php echo $posi?>" id="archivo_<?php echo $posi?>" class="campos"/>
									</span>
									<?php
										if(isset($id_buscar) && $nomb!='')
										{
										?>
										<img src="img/popup.png"  style="cursor:pointer" alt="Ver Archivo" title="Ver Archivo" onclick="window.open('descargar.php?adjunto=adjuntos/<?php echo $nomb?>','_blank')"/>
										<?php
											echo $nomb;
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
								<td>
									<span class="cssOcultar">
										<table>
											<tr>
												<td><?php echo $_SESSION[APL]->getButtom2('.','[+]', '40', 'onclick="nuevo_archivo('.$posi.')"','Agregar Siguiente Archivo'); ?></td>
												<td><?php echo $_SESSION[APL]->getButtom2('.','[-]', '40', 'onclick="eliminar_archivo('.$posi.')"','Eliminar Archivo','','middlered'); ?></td>
											</tr>
										</table>
									</span>
								</td>
							</tr>
						<?php
						}// Fin For
						?>
						<input type="hidden" name="visibles_a" id="visibles_a" value="<?php echo $visi_l?>" />
				</table>
			</td>
		</tr>
		<tr>
			<td align="center">
				<span class="cssOcultar">
					<table cellpadding="3">
						<tr>
							<td align="right" height="40px" valign="middle">
								<?php
									if(isset($id_buscar) && $finalizado_sos!=1)
										echo $_SESSION[APL]->getButtom('.','Guardar', '50', 'onclick="guardar(1)"');
								?>
								</td>
								<td align="left" height="40px" valign="middle">
								<?php
									if(isset($id_buscar))
										echo $_SESSION[APL]->getButtom('.','Finalizar', '50', 'onclick="guardar(0)"','','middlered');
								?>
							</td>
						</tr>
					</table>
				</span>
			</td>
		</tr>
	</table>
<?php
}// fin if si buscar
?>
</center>
<input type="hidden" name="accion" value="" />
<input type="hidden" name="mostrar_vehiculos" value="" />
</form>
<div id="venVerSOSVehInv" style="display:none">
	<center>
		<iframe id="ifrVerSOSVehInv" height="490px" width="1170px" style="border:0px"></iframe>
	</center>
</div>
</body>
<script>
	<?php
	if( isset($_GET["esEdi"]) and $_GET["esEdi"]=="SI" )
	{
	?>
		$('input:text').attr('disabled','disabled');
		$('select').attr('disabled','disabled');
		$('textarea').attr('disabled','disabled');
		$('input:checkbox').attr('disabled','disabled');
		$('.cssOcultar').hide();
	<?php
	}
	?>
</script>
</html>
