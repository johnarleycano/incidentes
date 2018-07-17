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
$_SESSION[APL]->pagina_menu='registro_sos.php';

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
	$parametros=array(
	'informado_por'=>$_POST['informado_por'],
	'informado_por_nombre'=>$_POST['informado_por_nombre'],
	'hora_salida_base'=>str_pad($_POST['hora_salida_base_h'],2,'0',STR_PAD_LEFT).":".str_pad($_POST['hora_salida_base_m'],2,'0',STR_PAD_LEFT),
	'hora_llegada_sitio'=>str_pad($_POST['hora_llegada_sitio_h'],2,'0',STR_PAD_LEFT).":".str_pad($_POST['hora_llegada_sitio_m'],2,'0',STR_PAD_LEFT),
	'hora_salida_sitio'=>str_pad($_POST['hora_salida_sitio_h'],2,'0',STR_PAD_LEFT).":".str_pad($_POST['hora_salida_sitio_m'],2,'0',STR_PAD_LEFT),
	'hora_llegada_base'=>str_pad($_POST['hora_llegada_base_h'],2,'0',STR_PAD_LEFT).":".str_pad($_POST['hora_llegada_base_m'],2,'0',STR_PAD_LEFT),
	'sentido'=>$_POST['sentido'],
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
	'tiempo_apertura'=>$_POST['tiempo_apertura'],
	'abscisa_real'=>$abscisa_real
	);


	$sql="SELECT codigo,periodo 
	FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente 
	WHERE id=".$_POST['id_buscar'];
	$dat=$_SESSION[APL]->bd->getRs($sql);
	$cod_inc=$dat->fields[0];
	$per_inc=$dat->fields[1];
	$c_i=$per_inc."_".$cod_inc;


	$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente
	SET
	informado_por=?,
	informado_por_nombre=?,
	hora_salida_base=?,
	hora_llegada_sitio=?,
	hora_salida_sitio=?,
	hora_llegada_base=?,
	sentido=?,
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
		$sql.="finalizado_sos=1,";
	
	
	$sql.="
	estado=?,
	visualizar_web=?,
	tipo_incidente=?,
	referencia=?,
	via=?,
	tipo_atencion=?,
	observaciones=?	,
	tiempo_apertura=?,
	abscisa_real=?
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
			echo "<script>alert('Se Actualizo el incidente ".$_POST['periodo'].".".str_pad($cod_inc,5,"0",STR_PAD_LEFT)." y se elimino de la lista de pendientes');window.open('registro_sos.php','_self')</script>";
			else
			if($_POST['accion']=='G')
			echo "<script>alert('Se Actualizo el incidente ".$_POST['periodo'].".".str_pad($cod_inc,5,"0",STR_PAD_LEFT)."')</script>";
			
			
		}
	}
	

}
?>

<script>

function filtrar()
{

	if(Math.ceil(parseFloat(document.incidente.cantidad_reg.value/document.incidente.cantidad.value))<document.incidente.pagina.value)
		alert('La pagina seleccionada para la cantidad de registros a mostrar, no existe, seleccione una pagina inferior')
	else
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
		alert('Limite de 10 superado')
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
	window.open('registro_sos.php?id_buscar='+pos,'_self')
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
		?>break;
		default:
		alert('Via no encontrada');
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

	if(document.incidente.informado_por.value=='')
	{
		alert('Seleccione por quien fue informado el evento')
		document.incidente.informado_por.focus();
	}
	else
	if(document.incidente.informado_por_nombre.value=='')
	{
		alert('Ingrese el nombre de quien ingreso el evento')
		document.incidente.informado_por_nombre.focus();
	}
	else
	if(document.incidente.tipo_atencion.value=='')
	{
		alert('Seleccione el Tipo de Atencion')
		document.incidente.tipo_atencion.focus();
	}
	else
	if(document.incidente.via.value=='')
	{
		alert('Seleccione la Via')
		document.incidente.via.focus();
	}
	else

if(document.incidente.referencia.value=='')
	{
		alert('Seleccione la Referencia')
		document.incidente.referencia.focus();
	}
	else



	if(tipo==0  && calcular_tiempo_evento()==false)
	{
		return false;
	}
	else
	/*if(calcular_hora_estimada_llegada()==false)
	{
		return false;
	}
	else*/
	if(tipo==0 && calcular_tiempo_respuesta()==false)
	{
		return false;
	}
	else
	{
		if(tipo==1)
			document.incidente.accion.value='G';
		else
		if(tipo==0)
		
			document.incidente.accion.value='F';
		
		else
			document.incidente.accion.value='V';
		
	
		if(
			document.incidente.accion.value=='V' || document.incidente.accion.value=='G' || 
			( 
			document.incidente.accion.value=='F' && confirm('Esta seguro de FINALIZAR el incidente?, esto lo eliminara de la lista de pendientes') 
			)
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
		
		window.open('registro_sos.php?id_buscar='+id,'_self');
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

		var horas=parseFloat(hora_s)-parseFloat(hora_r);
		var minus=parseFloat(minu_s)-parseFloat(minu_r);
		if(horas<0 || (horas==0 && minus==0))
		{
			alert('La Hora de LLegada Base debe debe ser mayor que la de Salida')
			
			document.incidente.tiempo_total.value='';	
			document.incidente.hora_llegada_base_h.focus();
			return false;
		}
		else
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
			alert('La Hora de LLegada al Sitio debe debe ser mayor que la de Salida de la Base')
			
			document.incidente.tiempo_respuesta.value='';	
			document.incidente.hora_llegada_sitio_h.focus();
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
			alert('La Hora de Salida del Sitio debe debe ser mayor que la de Llegada')
			
			document.incidente.tiempo_respuesta.value='';	
			document.incidente.hora_llegada_sitio_h.focus();
			return false;
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
	//guardar(3);// mostrando ventana vehiculos al final
	ventana_vehiculos(<?php echo $_GET['id_buscar']; ?>);
}

<?php 
if(isset($_POST['accion']) && $_POST['accion']=='V')
	;//echo "ventana_vehiculos(".$_POST['id_buscar'].")"; 
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
	abscisa_real
	
	FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i
	left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia  as r on (i.referencia=r.id)
	
	
	 WHERE 
	 
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
}
?>
<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<form name="incidente" method="post" action="registro_sos.php" enctype="multipart/form-data">
<center>
<?php 
if(isset($id_buscar))
{
?>

<table>
<tr class="cab_grid"><th colspan="12"  >Datos Generales Central SOS</th>
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
	echo '<img src="img/amarillo.png" title="Guardado por SOS"/>';
	else
	echo '<img src="img/gris.png" title="Pendiente por SOS"/>';
	?>
	</td>
	<td>&nbsp;&nbsp;</td>
	<td>
	<?php
	if($finalizado_adm_vial==1)
		echo '<img src="img/verde.png" />';
	else
	if($guardado_adm_vial==1)
		echo '<img src="img/amarillo.png" />';
	else
		echo '<img src="img/gris.png" />';
	?>
	</td>
	</tr>
	</table>

</td>

</tr>
<tr>
<td colspan="12">
<table>
<tr><th  class="LegendSt" bgcolor="#CCCCCC">
INCIDENTES PENDIENTES</th>
<td align="left" colspan="3">
<input type="hidden" name="periodo" value="<?php if(isset($id_buscar)) echo $periodo;?>" />
<?php
$sql="SELECT id,periodo,codigo,finalizado_sos FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente where id=".$id_buscar;
$rs=$_SESSION[APL]->bd->getRs($sql);

echo $rs->fields[1].".".str_pad($rs->fields[2],5,"0",STR_PAD_LEFT)." ";
if($rs->fields[3]==1)
	echo "*";
$rs->close();
?>
</td>
<th  bgcolor="#CCCCCC" class="LegendSt"><span class="style1">MOSTRAR EN WEB</span></th>
<td class="style1" align="left">&nbsp;&nbsp;<?php echo $visualizar_web; ?>&nbsp;&nbsp;</td>
<th   class="LegendSt">Tipo Incidente</th>
<td align="left" colspan="5">&nbsp;&nbsp;
<?php
if( $tipo_incidente=='SI' )
	echo "Via con alguna restricción en su recorrido";
else if( $tipo_incidente=='SI' )
	echo "Via que presenta problemas en su recorrido";
else
	echo "Via sin ningún tipo de problema en su recorrido";
?>&nbsp;&nbsp;
</td>
</tr>
</table>
</td>
</tr>
<tr><th colspan="12" height="20">&nbsp;</th></tr>
<tr class="cab_grid">
<th  colspan="12"><span class="style1">INFORMACION BASICA</span></th>
</tr>
<tr>
<th colspan="3" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Fecha</span></th>
<th colspan="5" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Reportado por</span></th>
<th colspan="4" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Operador SOS</span></th>
</tr>
<tr>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Año</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Mes</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Dia</span></th>

<td class="style1" colspan="5" align="center">
<select name="informado_por" class="campos"> 
<option value=""></option>
<?php
$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado ORDER BY nombre";
$rs=$_SESSION[APL]->bd->getRs($sql);

while (!$rs->EOF) {
   	echo "<option value='".$rs->fields[0]."' ";
	if(isset($id_buscar) && $informado_por==$rs->fields[0])
			echo "selected";
	echo ">".$rs->fields[1]."</option>";
    $rs->MoveNext();
}
$rs->close();
?>

</select></td>
<td class="style1" colspan="4" align="center"><?php if(isset($id_buscar)) echo $nombre_usuario?></td>
</tr>
<tr>
<td class="style1" align="center"><?php if(isset($id_buscar)) echo $ano_rep?></td>
<td class="style1" align="center"><?php if(isset($id_buscar)) echo $mes_rep?></td>
<td class="style1" align="center"><?php if(isset($id_buscar)) echo $dia_rep?></td>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Nombre</span></th>
<td class="style1" colspan="4" align="center"><input type="text" class="campos" name="informado_por_nombre"  value="<?php if(isset($id_buscar)) echo $informado_por_nombre;?>" maxlength="100" size="30"/></td>
<th bgcolor="#CCCCCC" class="LegendSt" colspan="2"><span class="style1">Cedula</span></th>
<td class="style1" colspan="2" align="center"><?php  if(isset($id_buscar)) echo $identificacion_usuario?></td>
</tr><tr>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Hora Reporte</span></th>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Hora Salida de Base</span></th>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Hora Llegada al Sitio</span></th>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Hora Salida del Sitio</span></th>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Hora LLegada a Base</span></th>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">&nbsp;<!--Hora Estimada Llegada--></span></th>
</tr>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">HH</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">MI</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">HH</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">MI</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">HH</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">MI</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">HH</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">MI</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">HH</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">MI</span></th>
<td colspan="2" rowspan="2" class="style1" align="center" >&nbsp;

<!--<input type="text" size="5" name="hora_estimada_llegada" style="text-align:center" class="resaltar" onkeypress="return false" bgcolor="#CCFF66" 
value="<?php

/*if(isset($id_buscar) && $absicsa_evento_p1!='' && $absicsa_salida_p1!='' && $hora_salida_base_h!='' && $hora_salida_base_m!='')
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
	echo $hora_estimada_llegada;
}*/
?>">--></td></tr>
<tr>
<td class="style1" align="center"><input type="text" name="hora_rep" value="<?php  if(isset($id_buscar)) echo $hora_rep?>" class="campos" size="3" disabled/></td>
<td class="style1" align="center"><input type="text" name="minu_rep" value="<?php  if(isset($id_buscar)) echo $minu_rep?>" class="campos" size="3" disabled/></td>
<td class="style1" align="center"><input type="text" name="hora_salida_base_h" value="<?php if(isset($id_buscar)) echo $hora_salida_base_h?>" class="campos" size="3"  /><!--onblur="calcular_hora_estimada_llegada()"--></td>
<td class="style1" align="center"><input type="text" name="hora_salida_base_m" value="<?php if(isset($id_buscar)) echo $hora_salida_base_m?>" class="campos" size="3" /><!--onblur="calcular_hora_estimada_llegada()"--></td>
<td class="style1" align="center"><input type="text" name="hora_llegada_sitio_h" value="<?php if(isset($id_buscar)) echo $hora_llegada_sitio_h?>" class="campos" size="3" /><!--onblur="calcular_tiempo_respuesta()"--></td>
<td class="style1" align="center"><input type="text" name="hora_llegada_sitio_m" value="<?php if(isset($id_buscar)) echo $hora_llegada_sitio_m?>" class="campos" size="3" /><!--onblur="calcular_tiempo_respuesta()"--></td>
<td class="style1" align="center"><input type="text" name="hora_salida_sitio_h" value="<?php if(isset($id_buscar)) echo $hora_salida_sitio_h?>" class="campos" size="3" /><!--onblur="calcular_tiempo_respuesta()"--></td>
<td class="style1" align="center"><input type="text" name="hora_salida_sitio_m" value="<?php if(isset($id_buscar)) echo $hora_salida_sitio_m?>" class="campos" size="3" /><!--onblur="calcular_tiempo_respuesta()"--></td>
<td class="style1" align="center"><input type="text" name="hora_llegada_base_h" value="<?php if(isset($id_buscar)) echo $hora_llegada_base_h?>" class="campos" size="3" /><!--onblur="calcular_tiempo_evento()"--></td>
<td class="style1" align="center"><input type="text" name="hora_llegada_base_m" value="<?php if(isset($id_buscar)) echo $hora_llegada_base_m?>" class="campos" size="3" /><!--onblur="calcular_tiempo_evento()"--></td>
</tr>
<tr>
<th height="55" colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Tiempo Reaccion</span></th>
<td class="style1" align="center" colspan="2">
<input type="text" size="5" name="tiempo_reaccion" style="text-align:center" class="resaltar" onkeypress="return false" bgcolor="#CCFF66" 
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

		}
		else
		{
			$tiempo_reaccion=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);

		}
		echo $tiempo_reaccion;
	}


?>"></td>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Tiempo Respuesta</span></th>
<td class="style1" align="center" colspan="2">
<input type="text" size="5" name="tiempo_respuesta" id="tiempo_respuesta" style="text-align:center" class="resaltar" onkeypress="return false" bgcolor="#CCFF66" 
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

	$horas=$hora_s-$hora_r;
	$minus=$minu_s-$minu_r;
	if($horas<0)
	{		
		$tiempo_respuesta='';	
	}
	else
		$tiempo_respuesta=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
	echo $tiempo_respuesta;
}

?>"></td>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Duracion Total del Evento</span></th>
<td class="style1" align="center" colspan="2">
<input type="text" size="5" name="tiempo_total" id="tiempo_total" class="resaltar" style="text-align:center" onkeypress="return false" bgcolor="#CCFF66" 
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

		$horas=$hora_s-$hora_r;
		$minus=$minu_s-$minu_r;
		if($horas<0)
		{
			$tiempo_total='';	
		}
		else
			$tiempo_total=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
		echo $tiempo_total;
	}


?>"></td>
</tr>
<tr>
<th colspan="3" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Tramo</span></th>
<td class="style1" align="left" colspan="8">
<select name="via" class="campos" onchange="cargar_referencias(this.value)">
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

</tr>
<tr>
<th colspan="3" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Tipo de Atencion (Comentario)</span></th>
<td class="style1" align="center" colspan="3">
<select name="tipo_atencion" class="campos">
<option value=""></option>
<?php
$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion ORDER BY id";
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
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Sentido</span></th>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Abscisa de Salida</span></th>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Abscisa del Evento</span></th>

</tr>
<tr>
<th colspan="3" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Sitio de Referencia</span></th>
<td class="style1" align="center" colspan="3">
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
<td class="style1" align="center" colspan="2" ><input type="text" name="sentido" size="20" value="<?php if(isset($id_buscar)) echo $sentido?>" class="campos" maxlength="20"/></td>
<td class="style1" align="center"  ><input type="text" name="absicsa_salida_p1" value="<?php if(isset($id_buscar)) echo $absicsa_salida_p1?>" class="campos" size="3" /><!--onblur="calcular_hora_estimada_llegada()"--></td>
<td class="style1" align="center"  ><input type="text" name="absicsa_salida_p2" value="<?php if(isset($id_buscar)) echo $absicsa_salida_p2?>" class="campos" size="3"/></td>
<td class="style1" align="center" ><input type="text" name="absicsa_evento_p1" value="<?php if(isset($id_buscar)) echo $absicsa_evento_p1?>" class="campos" size="3" /><!--onblur="calcular_hora_estimada_llegada()"--></td>
<td class="style1" align="center"  ><input type="text" name="absicsa_evento_p2" value="<?php if(isset($id_buscar)) echo $absicsa_evento_p2?>" class="campos" size="3"/></td>
</tr>
<tr>
<th colspan="3" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Observaciones</span></th>
<td colspan="5">
<textarea name="observaciones" rows="3" cols="60" class="campos"><?php echo $observaciones?></textarea>
</td>
<th colspan="2" bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Tiempo Apertura</span></th>
<td colspan="2" align="center"><input type="text" name="tiempo_apertura" value="<?php if(isset($id_buscar)) echo $tiempo_apertura?>" class="campos" size="10"/></td>



</tr>
<tr><th colspan="12" height="20">&nbsp;</th></tr>

<tr>
<td colspan="12" align="center">
<table width="100%">
<tr class="cab_grid">
<th  colspan="9"><span class="style1">APOYO EN ATENCION</span></th>
</tr>
<?php
$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo ORDER BY id";
$apo=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id, id_entidad,funcionario,
		hora_salida_base,
		hora_llegada_sitio,
		hora_salida_sitio,
		hora_llegada_base
FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo_entidad 
WHERE
id_incidente=".$id_buscar."
ORDER BY id ASC";
$apo_enti=$_SESSION[APL]->bd->getRs($sql);

?>
<tr>
<th bgcolor="#CCCCCC" class="LegendSt" rowspan="2"><span class="style1">Posicion</span></th>
<th   bgcolor="#CCCCCC" class="LegendSt" rowspan="2"><span class="style1">Entidad</span></th>
<th   bgcolor="#CCCCCC" class="LegendSt" rowspan="2"><span class="style1">Nombre Funcioario</span></th>
<th   bgcolor="#CCCCCC" class="LegendSt" colspan="5"><span class="style1">Duracion del Evento</span></th>
<th   bgcolor="#CCCCCC" class="LegendSt" rowspan="2"><span class="style1">&nbsp;</span></th>
</tr>
<tr>
<th bgcolor="#CCCCCC" class="LegendSt">Hora Salida Base</th>
<th bgcolor="#CCCCCC" class="LegendSt">Hora Llegada Sitio</th>
<th bgcolor="#CCCCCC" class="LegendSt">Hora Salida Sitio</th>
<th bgcolor="#CCCCCC" class="LegendSt">Hora Llegada Base</th>
<th bgcolor="#CCCCCC" class="LegendSt">Duracion Total</th>
</tr>


<?php
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




<tr id="tr_<?php echo $posi?>" <?php echo $estilo_l;?>>
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
<td class="style1" align="center" >

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
<input type="text" name="hora_salida_base_<?php echo $posi?>" id="hora_salida_base_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $hora_salida_base?>" class="campos" size="3" />
<input type="text" name="minu_salida_base_<?php echo $posi?>" id="minu_salida_base_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $minu_salida_base;?>" class="campos" size="3" />
</td>
<td class="style1" align="center">
<input type="text" name="hora_llegada_sitio_<?php echo $posi?>" id="hora_llegada_sitio_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $hora_llegada_sitio;?>" class="campos" size="3" />
<input type="text" name="minu_llegada_sitio_<?php echo $posi?>" id="minu_llegada_sitio_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $minu_llegada_sitio;?>" class="campos" size="3" />
</td>
<td class="style1" align="center">
<input type="text" name="hora_salida_sitio_<?php echo $posi?>" id="hora_salida_sitio_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $hora_salida_sitio?>" class="campos" size="3" />
<input type="text" name="minu_salida_sitio_<?php echo $posi?>" id="minu_salida_sitio_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $minu_salida_sitio?>" class="campos" size="3" />
</td>
<td class="style1" align="center">
<input type="text" name="hora_llegada_base_<?php echo $posi?>" id="hora_llegada_base_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $hora_llegada_base?>" class="campos" size="3" />
<input type="text" name="minu_llegada_base_<?php echo $posi?>" id="minu_llegada_base_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $minu_llegada_base?>" class="campos" size="3" />
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
		{
			$tiempo_total='';	
		}
		else
			$tiempo_total=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
		
	}
	else
		$tiempo_total='';	


?>



<input type="text" name="tiempo_total_<?php echo $posi?>" id="tiempo_total_<?php echo $posi?>" value="<?php  if(isset($id_buscar)) echo $tiempo_total?>" class="campos" size="5" disabled/>
</td>
<td>
</td>
</tr>
<?php
}?>
<input type="hidden" name="visibles" id="visibles" value="<?php echo $visi_l?>" />
<tr><th colspan="9" height="20">
<center>
</center>
</th></tr>

</table>
<table width="100%">
<tr class="cab_grid">
<th  colspan="6"><span class="style1">VEHICULOS INVOLUCRADOS, AFECTADOS/ LESIONADOS Y/O MUERTOS</span></th>
</tr>
<tr>
<td colspan="6" align="center" height="50px" valign="middle" class="resaltar">
<?php
//ventana_vehiculos('.$id_buscar.')
echo $_SESSION[APL]->getButtom('.','Vehiculos Involucrados, Afectados / Lesionados y/o Muertos', '50', 'onclick=guardar_vehiculos()');

if(isset($id_buscar))
{
	$sql="SELECT COUNT(*) FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente WHERE id_incidente=".$id_buscar;
	$vehi = $_SESSION[APL]->bd->dato($sql);
	
	$sql="SELECT COUNT(*) FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo WHERE lesionado='SI' and id_vehiculo IN
	(SELECT id_vehiculo FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
	WHERE
	
	id_incidente=".$id_buscar.")";
	$les = $_SESSION[APL]->bd->dato($sql);
	
	$sql="SELECT COUNT(*) FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo WHERE muerto='SI' and id_vehiculo IN
	(SELECT id_vehiculo FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
	WHERE
	id_incidente=".$id_buscar.")";
	$mue = $_SESSION[APL]->bd->dato($sql);
	
	echo $vehi." Vehiculo(s) y ".$les." Lesionado(s) y ".$mue." Muerto (s) Registrado(s)";
	
	
	
}

?>
</td></tr>


<tr>
<th  bgcolor="#CCCCCC" class="LegendSt" colspan="2"><span class="style1">Heridos</span></th>
<td class="style1" align="center"><input type="text" name="nro_heridos" value="<?php if(isset($id_buscar)) echo $nro_heridos?>" size="3" class="resaltar" onkeypress="return false" style="text-align:center"/></td>
</tr>
<tr>

<th  bgcolor="#CCCCCC" class="LegendSt" colspan="2"><span class="style1">Muertos</span></th>
<td class="style1" align="center"><input type="text" name="nro_muertos" value="<?php if(isset($id_buscar)) echo $nro_muertos?>" size="3" class="resaltar" onkeypress="return false" style="text-align:center"/></td>


</tr>

<tr><th colspan="6" height="20">&nbsp;</th></tr>
<tr class="cab_grid">
<th  colspan="6"><span class="style1">AUTORIDAD COMPETENTE</span></th>
</tr>


<tr>
<tr>
<th width="220"  bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Policia Transito y Transporte</span></th>
<td class="style1" align="center"><input name="policia" type="checkbox" class="campos" <?php if(isset($id_buscar) && $policia=='SI') echo "checked"?>/></td>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Placa</span></th>
<td class="style1" align="center" ><input type="text" name="policia_placa" class="campos" value="<?php if(isset($id_buscar)) echo $policia_placa?>"></td>
<th  bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Nombre Completo</span></th>
<td class="style1" align="center" ><input type="text" name="policia_apellido" class="campos" value="<?php if(isset($id_buscar)) echo $policia_apellido?>"></td>
</tr>
<tr>
<th width="220"  bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Transito</span></th>
<td class="style1" align="center"><input name="transito" type="checkbox" class="campos" <?php if(isset($id_buscar) && $transito=='SI') echo "checked"?>/></td>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Placa</span></th>
<td class="style1" align="center" ><input type="text" name="transito_placa" class="campos" value="<?php if(isset($id_buscar)) echo $transito_placa?>"></td>
<th  bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Nombre Completo</span></th>
<td class="style1" align="center" ><input type="text" name="transito_apellido" class="campos" value="<?php if(isset($id_buscar)) echo $transito_apellido?>"></td>
</tr>
<tr>
<th width="220"  bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Inspector</span></th>
<td class="style1" align="center"><input name="inspector" type="checkbox" class="campos" <?php if(isset($id_buscar) && $inspector=='SI') echo "checked"?>/></td>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Placa</span></th>
<td class="style1" align="center" ><input type="text" name="inspector_placa" class="campos" value="<?php if(isset($id_buscar)) echo $inspector_placa?>"></td>
<th  bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Nombre Completo</span></th>
<td class="style1" align="center"><input type="text" name="inspector_apellido" class="campos" value="<?php if(isset($id_buscar)) echo $inspector_apellido?>"></td>
</tr>
<tr><th colspan="6" height="20">&nbsp;</th></tr>







</table>
</td>
</tr>




<tr>
<td colspan="12">

</td>
</tr>
<tr><th colspan="12" height="20">&nbsp;</th></tr>
<tr class="cab_grid">
<th  colspan="12"><span class="style1">ARCHIVOS</span></th>
</tr>




<?php



$sql="SELECT id,nombre FROM
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_archivo
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




<tr id="tr_a_<?php echo $posi?>" <?php echo $estilo_l;?>>
<td class="style1" align="center" colspan="2"><?php echo $posi?></td>

<td class="style1" align="center"  colspan="8">

<?php 
if(isset($id_buscar) && $nomb!='')
{
?>
<img src="img/popup.png"  style="cursor:pointer" alt="Ver Archivo" title="Ver Archivo" onclick="window.open('descargar.php?adjunto=adjuntos/<?php echo $nomb?>','_blank')"/>

<?php echo $nomb; }
?>


<input type="hidden" name="id_a_<?php echo $posi?>" id="id_a_<?php echo $posi?>" value="<?php echo $id_a;?>"/>

<input type="hidden" name="borrar_a_<?php echo $posi?>" id="borrar_a_<?php echo $posi?>" value="<?php
if($id_a!='' || $posi==1)
echo "0";
else
	echo "1";
?>"/>
</td>
<td colspan="2">
</td>
</tr>
<?php
}?>
<input type="hidden" name="visibles_a" id="visibles_a" value="<?php echo $visi_l?>" />
<tr>
<td colspan="6" align="right" height="40px" valign="middle">
</td>
<td colspan="6" align="left" height="40px" valign="middle">
</td>
</tr>
</table>
<?php }?>
</center>
<input type="hidden" name="accion" value="" />
<input type="hidden" name="mostrar_vehiculos" value="" />
</form>
</body>
<script>
	$('input:text').attr('disabled','disabled');
	$('select').attr('disabled','disabled');
	$('textarea').attr('disabled','disabled');
	$('input:checkbox').attr('disabled','disabled');
</script>
</html>