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
$_SESSION[APL]->pagina_menu='vehiculo_incidente.php';

	$sql = "select valor from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_constante WHERE id=2";
	$cant_les = $_SESSION[APL]->bd->dato($sql);

if(isset($_POST['accion']) && $_POST['accion']=='guardar')
{
	
	
	for($i=0;$i<10;$i++)
	{
		if($_POST['id_vehiculo_'.$i]=='' && $_POST['borrar_'.$i]==0 && $_POST['id_tipo_vehiculo_'.$i]!='')//nuevo vehiculo
		{
			$id_vehiculo=$_SESSION[APL]->getSecuencia('dvm_vehiculo_incidente','id_vehiculo');
			
			$parametros=array(
			'id_vehiculo'=>$id_vehiculo,
			'id_incidente'=>$_POST['id_buscar'],
			'id_tipo_vehiculo'=>$_POST['id_tipo_vehiculo_'.$i]==''?NULL:$_POST['id_tipo_vehiculo_'.$i],
			'referencia_vehiculo'=>$_POST['referencia_vehiculo_'.$i],
			'modelo_vehiculo'=>$_POST['modelo_vehiculo_'.$i],
			'placa_vehiculo'=>$_POST['placa_vehiculo_'.$i],
			'color_vehiculo'=>$_POST['color_vehiculo_'.$i],
			'soat_vehiculo'=>$_POST['soat_vehiculo_'.$i],
			'id_aseguradora'=>$_POST['id_aseguradora_'.$i]==''?NULL:$_POST['id_aseguradora_'.$i],
			'nro_heridos'=>$_POST['nro_heridos_'.$i]==''?0:$_POST['nro_heridos_'.$i],
			'nro_muertos'=>$_POST['nro_muertos_'.$i]==''?0:$_POST['nro_muertos_'.$i],
			
			'id_parqueadero'=>$_POST['id_parqueadero_'.$i]==''?NULL:$_POST['id_parqueadero_'.$i],
			'id_taller'=>$_POST['id_taller_'.$i]==''?NULL:$_POST['id_taller_'.$i],
			'id_otro_vehiculo'=>$_POST['id_otro_vehiculo_'.$i]==''?NULL:$_POST['id_otro_vehiculo_'.$i],
			'observaciones'=>$_POST['observaciones_'.$i],
			'id_grua'=>$_POST['id_grua_'.$i]==''?NULL:$_POST['id_grua_'.$i]);
			
			
			$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
			(
				id_vehiculo,id_incidente,id_tipo_vehiculo,referencia_vehiculo,modelo_vehiculo,
				placa_vehiculo,color_vehiculo,soat_vehiculo,id_aseguradora,nro_heridos,nro_muertos,
				id_parqueadero,id_taller,id_otro_vehiculo,observaciones,id_grua)
			VALUES
			(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
				echo "<script>alert('Error al crear Vehiculo ".$i."')</script>";
			else//lesionados
			{
				for($l=0;$l<$cant_les;$l++)
				{
					
					if(isset($_POST['conducia_'.$i.'_'.$l]))
						$conducia_v='SI';
					else
						$conducia_v='NO';
						
					if(isset($_POST['lesionado_'.$i.'_'.$l]))
						$lesionado_v='SI';
					else
						$lesionado_v='NO';
						
					if(isset($_POST['muerto_'.$i.'_'.$l]))
						$muerto_v='SI';
					else
						$muerto_v='NO';
				
					if($_POST['id_lesionado_'.$i.'_'.$l]=='' && $_POST['borrar_'.$i.'_'.$l]==0 && $_POST['nombre_'.$i.'_'.$l]!='')//nuevo lesionado
					{
						$id_lesionado=$_SESSION[APL]->getSecuencia('dvm_lesionado_vehiculo','id_lesionado');
						$parametros=array
						(
							'id_lesionado'=>$id_lesionado,
							'id_vehiculo'=>$id_vehiculo,
							'id_trasladado_por'=>$_POST['id_trasladado_por_'.$i.'_'.$l]==''?NULL:$_POST['id_trasladado_por_'.$i.'_'.$l],
							'conductor'=>$_POST['conductor_'.$i.'_'.$l],
							'auxiliar_enfermeria'=>$_POST['auxiliar_enfermeria_'.$i.'_'.$l],
							'nombre'=>$_POST['nombre_'.$i.'_'.$l],
							'cedula'=>$_POST['cedula_'.$i.'_'.$l],
							'telefono'=>$_POST['telefono_'.$i.'_'.$l],
							'direccion'=>$_POST['direccion_'.$i.'_'.$l],
							'diagnostico'=>$_POST['diagnostico_'.$i.'_'.$l],
							'id_hospital'=>$_POST['id_hospital_'.$i.'_'.$l]==''?NULL:$_POST['id_hospital_'.$i.'_'.$l],
							'id_clinica'=>$_POST['id_clinica_'.$i.'_'.$l]==''?NULL:$_POST['id_clinica_'.$i.'_'.$l],
							'id_centro_salud'=>$_POST['id_centro_salud_'.$i.'_'.$l]==''?NULL:$_POST['id_centro_salud_'.$i.'_'.$l],
							'id_otro_lesionado'=>$_POST['id_otro_lesionado_'.$i.'_'.$l]==''?NULL:$_POST['id_otro_lesionado_'.$i.'_'.$l],
							'observaciones'=>$_POST['observaciones_'.$i.'_'.$l],
							'conducia'=>$conducia_v,
							'lesionado'=>$lesionado_v,
							'muerto'=>$muerto_v,
						);
						$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
						(
							id_lesionado,id_vehiculo,id_trasladado_por,conductor,auxiliar_enfermeria,nombre,
							cedula,telefono,direccion,diagnostico,id_hospital,id_clinica,id_centro_salud,
							id_otro_lesionado,observaciones,conducia,lesionado,muerto)
						VALUES
						(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
						if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
							echo "<script>alert('Error al crear Lesionado ".$l." para el Vehiculo ".$i."')</script>";
						
						
					}
					else
					if($_POST['id_lesionado_'.$i.'_'.$l]!='' && $_POST['borrar_'.$i.'_'.$l]==0 && $_POST['nombre_'.$i.'_'.$l]!='')//nuevo lesionado
					{
						$parametros=array
						(
							
							'id_trasladado_por'=>$_POST['id_trasladado_por_'.$i.'_'.$l]==''?NULL:$_POST['id_trasladado_por_'.$i.'_'.$l],
							'conductor'=>$_POST['conductor_'.$i.'_'.$l],
							'auxiliar_enfermeria'=>$_POST['auxiliar_enfermeria_'.$i.'_'.$l],
							'nombre'=>$_POST['nombre_'.$i.'_'.$l],
							'cedula'=>$_POST['cedula_'.$i.'_'.$l],
							'telefono'=>$_POST['telefono_'.$i.'_'.$l],
							'direccion'=>$_POST['direccion_'.$i.'_'.$l],
							'diagnostico'=>$_POST['diagnostico_'.$i.'_'.$l],
							'id_hospital'=>$_POST['id_hospital_'.$i.'_'.$l]==''?NULL:$_POST['id_hospital_'.$i.'_'.$l],
							'id_clinica'=>$_POST['id_clinica_'.$i.'_'.$l]==''?NULL:$_POST['id_clinica_'.$i.'_'.$l],
							'id_centro_salud'=>$_POST['id_centro_salud_'.$i.'_'.$l]==''?NULL:$_POST['id_centro_salud_'.$i.'_'.$l],
							'id_otro_lesionado'=>$_POST['id_otro_lesionado_'.$i.'_'.$l]==''?NULL:$_POST['id_otro_lesionado_'.$i.'_'.$l],
							'observaciones'=>$_POST['observaciones_'.$i.'_'.$l],
							'conducia'=>$conducia_v,
							'lesionado'=>$lesionado_v,
							'muerto'=>$muerto_v,
							'id_lesionado'=>$_POST['id_lesionado_'.$i.'_'.$l],
							'id_vehiculo'=>$id_vehiculo
						);
						$sql="UPDATE
						".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
						SET
						id_trasladado_por=?,
						conductor=?,
						auxiliar_enfermeria=?,
						nombre=?,
						cedula=?,
						telefono=?,
						direccion=?,
						diagnostico=?,
						id_hospital=?,
						id_clinica=?,
						id_centro_salud=?,
						id_otro_lesionado=?,
						observaciones=?,
						conducia=?,
						lesionado=?,
						muerto=?
						WHERE
						id_lesionado=? AND id_vehiculo=? ";
						if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
							echo "<script>alert('Error al actualizar el Lesionado ".$l." para el Vehiculo ".$i."')</script>";
						
					}
					else
					if($_POST['id_lesionado_'.$i.'_'.$l]!='' && $_POST['borrar_'.$i.'_'.$l]==1)//nuevo lesionado
					{
						$parametros=array(
						'id_lesionado'=>$_POST['id_lesionado_'.$i.'_'.$l],
						'id_vehiculo'=>$id_vehiculo
						);
						$sql="DELETE FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
						WHERE id_lesionado=? AND id_vehiculo=?";
						
						if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
							echo "<script>alert('Error al eliminar el Lesionado ".$l." para el Vehiculo ".$i."')</script>";
					}
				}
			
			}
	
		}
		else
		if($_POST['id_vehiculo_'.$i]!='' && $_POST['borrar_'.$i]==0 && $_POST['id_tipo_vehiculo_'.$i]!='')//actualizar
		{
			$parametros=array(
			'id_tipo_vehiculo'=>$_POST['id_tipo_vehiculo_'.$i]==''?NULL:$_POST['id_tipo_vehiculo_'.$i],
			'referencia_vehiculo'=>$_POST['referencia_vehiculo_'.$i],
			'modelo_vehiculo'=>$_POST['modelo_vehiculo_'.$i],
			'placa_vehiculo'=>$_POST['placa_vehiculo_'.$i],
			'color_vehiculo'=>$_POST['color_vehiculo_'.$i],
			'soat_vehiculo'=>$_POST['soat_vehiculo_'.$i],
			'id_aseguradora'=>$_POST['id_aseguradora_'.$i]==''?NULL:$_POST['id_aseguradora_'.$i],
			'nro_heridos'=>$_POST['nro_heridos_'.$i]==''?0:$_POST['nro_heridos_'.$i],
			'nro_muertos'=>$_POST['nro_muertos_'.$i]==''?0:$_POST['nro_muertos_'.$i],
			'id_parqueadero'=>$_POST['id_parqueadero_'.$i]==''?NULL:$_POST['id_parqueadero_'.$i],
			'id_taller'=>$_POST['id_taller_'.$i]==''?NULL:$_POST['id_taller_'.$i],
			'id_otro_vehiculo'=>$_POST['id_otro_vehiculo_'.$i]==''?NULL:$_POST['id_otro_vehiculo_'.$i],
			'observaciones'=>$_POST['observaciones_'.$i],
			'id_grua'=>$_POST['id_grua_'.$i]==''?NULL:$_POST['id_grua_'.$i],
			'id_vehiculo'=>$_POST['id_vehiculo_'.$i],
			'id_incidente'=>$_POST['id_buscar']
			);
			
			$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
			SET
			id_tipo_vehiculo=?,
			referencia_vehiculo=?,
			modelo_vehiculo=?,
			placa_vehiculo=?,
			color_vehiculo=?,
			soat_vehiculo=?,
			id_aseguradora=?,
			nro_heridos=?,
			nro_muertos=?,
			id_parqueadero=?,
			id_taller=?,
			id_otro_vehiculo=?,
			observaciones=?,
			id_grua=?
			WHERE
			id_vehiculo=? AND id_incidente=?";
			if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
				echo "<script>alert('Error al Actualizar Vehiculo ".$i."')</script>";
			else//lesionados
			{
				$id_vehiculo=$_POST['id_vehiculo_'.$i];
				for($l=0;$l<$cant_les;$l++)
				{
					if(isset($_POST['conducia_'.$i.'_'.$l]))
						$conducia_v='SI';
					else
						$conducia_v='NO';
					if(isset($_POST['lesionado_'.$i.'_'.$l]))
						$lesionado_v='SI';
					else
						$lesionado_v='NO';
					if(isset($_POST['muerto_'.$i.'_'.$l]))
						$muerto_v='SI';
					else
						$muerto_v='NO';	
						
						
					
					if($_POST['id_lesionado_'.$i.'_'.$l]=='' && $_POST['borrar_'.$i.'_'.$l]==0 && $_POST['nombre_'.$i.'_'.$l]!='')//nuevo lesionado
					{
						$id_lesionado=$_SESSION[APL]->getSecuencia('dvm_lesionado_vehiculo','id_lesionado');
						$parametros=array
						(
							'id_lesionado'=>$id_lesionado,
							'id_vehiculo'=>$id_vehiculo,
							'id_trasladado_por'=>$_POST['id_trasladado_por_'.$i.'_'.$l]==''?NULL:$_POST['id_trasladado_por_'.$i.'_'.$l],
							'conductor'=>$_POST['conductor_'.$i.'_'.$l],
							'auxiliar_enfermeria'=>$_POST['auxiliar_enfermeria_'.$i.'_'.$l],
							'nombre'=>$_POST['nombre_'.$i.'_'.$l],
							'cedula'=>$_POST['cedula_'.$i.'_'.$l],
							'telefono'=>$_POST['telefono_'.$i.'_'.$l],
							'direccion'=>$_POST['direccion_'.$i.'_'.$l],
							'diagnostico'=>$_POST['diagnostico_'.$i.'_'.$l]==''?NULL:$_POST['diagnostico_'.$i.'_'.$l],
							'id_hospital'=>$_POST['id_hospital_'.$i.'_'.$l]==''?NULL:$_POST['id_hospital_'.$i.'_'.$l],
							'id_clinica'=>$_POST['id_clinica_'.$i.'_'.$l]==''?NULL:$_POST['id_clinica_'.$i.'_'.$l],
							'id_centro_salud'=>$_POST['id_centro_salud_'.$i.'_'.$l]==''?NULL:$_POST['id_centro_salud_'.$i.'_'.$l],
							'id_otro_lesionado'=>$_POST['id_otro_lesionado_'.$i.'_'.$l]==''?NULL:$_POST['id_otro_lesionado_'.$i.'_'.$l],
							'observaciones'=>$_POST['observaciones_'.$i.'_'.$l],
							'conducia'=>$conducia_v,
							'lesionado'=>$lesionado_v,
							'muerto'=>$muerto_v
							
						);
						$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
						(
							id_lesionado,id_vehiculo,id_trasladado_por,conductor,auxiliar_enfermeria,nombre,
							cedula,telefono,direccion,diagnostico,id_hospital,id_clinica,id_centro_salud,
							id_otro_lesionado,observaciones,conducia,lesionado,muerto)
						VALUES
						(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
						if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
							echo "<script>alert('Error al crear Lesionado ".$l." para el Vehiculo ".$i."')</script>";
						
						
					}
					else
					if($_POST['id_lesionado_'.$i.'_'.$l]!='' && $_POST['borrar_'.$i.'_'.$l]==0 && $_POST['nombre_'.$i.'_'.$l]!='')//nuevo lesionado
					{
						$parametros=array
						(
							
							'id_trasladado_por'=>$_POST['id_trasladado_por_'.$i.'_'.$l]==''?NULL:$_POST['id_trasladado_por_'.$i.'_'.$l],
							'conductor'=>$_POST['conductor_'.$i.'_'.$l],
							'auxiliar_enfermeria'=>$_POST['auxiliar_enfermeria_'.$i.'_'.$l],
							'nombre'=>$_POST['nombre_'.$i.'_'.$l],
							'cedula'=>$_POST['cedula_'.$i.'_'.$l],
							'telefono'=>$_POST['telefono_'.$i.'_'.$l],
							'direccion'=>$_POST['direccion_'.$i.'_'.$l],
							'diagnostico'=>$_POST['diagnostico_'.$i.'_'.$l],
							'id_hospital'=>$_POST['id_hospital_'.$i.'_'.$l]==''?NULL:$_POST['id_hospital_'.$i.'_'.$l],
							'id_clinica'=>$_POST['id_clinica_'.$i.'_'.$l]==''?NULL:$_POST['id_clinica_'.$i.'_'.$l],
							'id_centro_salud'=>$_POST['id_centro_salud_'.$i.'_'.$l]==''?NULL:$_POST['id_centro_salud_'.$i.'_'.$l],
							'id_otro_lesionado'=>$_POST['id_otro_lesionado_'.$i.'_'.$l]==''?NULL:$_POST['id_otro_lesionado_'.$i.'_'.$l],
							'observaciones'=>$_POST['observaciones_'.$i.'_'.$l],
							'conducia'=>$conducia_v,
							'lesionado'=>$lesionado_v,
							'muerto'=>$muerto_v,
							'id_lesionado'=>$_POST['id_lesionado_'.$i.'_'.$l],
							'id_vehiculo'=>$id_vehiculo
						);
						$sql="UPDATE
						".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
						SET
						id_trasladado_por=?,
						conductor=?,
						auxiliar_enfermeria=?,
						nombre=?,
						cedula=?,
						telefono=?,
						direccion=?,
						diagnostico=?,
						id_hospital=?,
						id_clinica=?,
						id_centro_salud=?,
						id_otro_lesionado=?,
						observaciones=?,
						conducia=?,
						lesionado=?,
						muerto=?
						WHERE
						id_lesionado=? AND id_vehiculo=? ";
						if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
							echo "<script>alert('Error al actualizar el Lesionado ".$l." para el Vehiculo ".$i."')</script>";
						
					}
					else
					if($_POST['id_lesionado_'.$i.'_'.$l]!='' && $_POST['borrar_'.$i.'_'.$l]==1)//nuevo lesionado
					{
						$parametros=array(
						'id_lesionado'=>$_POST['id_lesionado_'.$i.'_'.$l],
						'id_vehiculo'=>$id_vehiculo
						);
						$sql="DELETE FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
						WHERE id_lesionado=? AND id_vehiculo=?";
						
						if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
							echo "<script>alert('Error al eliminar el Lesionado ".$l." para el Vehiculo ".$i."')</script>";
					}
				}
			}
			
		}
		else
		if($_POST['id_vehiculo_'.$i]!='' && $_POST['borrar_'.$i]==1)//borrar
		{
			$parametros=array(
			'id_vehiculo'=>$_POST['id_vehiculo_'.$i]
			);
			$sql="DELETE FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo 
			WHERE
			id_vehiculo=?";
			if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
				echo "<script>alert('Error al Borrar Lesionados del Vehiculo ".$i."')</script>";
			$parametros=array(
			'id_vehiculo'=>$_POST['id_vehiculo_'.$i],
			'id_incidente'=>$_POST['id_buscar']
			);
			$sql="DELETE FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
			WHERE
			id_vehiculo=? AND id_incidente=?";
			if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
				echo "<script>alert('Error al Borrar Vehiculo ".$i."')</script>";
		}
	}
	
	$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
	SET
	nro_heridos=(SELECT count(1) FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo WHERE lesionado='SI' and 
	id_vehiculo=".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente.id_vehiculo)
	WHERE
	id_incidente=".$_POST['id_buscar']."";
	if(!$_SESSION[APL]->bd->ejecutar($sql))
		echo "<script>alert('Error al Actualizar Nro Heridos')</script>";
	
	
	$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
	SET
	nro_muertos=(SELECT count(1) FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo WHERE muerto='SI' and 
	id_vehiculo=".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente.id_vehiculo)
	WHERE
	id_incidente=".$_POST['id_buscar']."";
	if(!$_SESSION[APL]->bd->ejecutar($sql))
		echo "<script>alert('Error al Actualizar Nro Muertos')</script>";

	$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente
	SET
	nro_muertos=(SELECT SUM(nro_muertos) FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente WHERE id_incidente=".$_POST['id_buscar']."),
	nro_heridos=(SELECT SUM(nro_heridos) FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente WHERE id_incidente=".$_POST['id_buscar'].")	WHERe
	id=".$_POST['id_buscar']."";
	if(!$_SESSION[APL]->bd->ejecutar($sql))
				echo "<script>alert('Error al Actualizar Incidente')</script>";
	
	
echo "<script>
	window.opener.recargar(".$_POST['id_buscar'].");
	window.close();
</script>";
}
?>

<script>
function nuevo_vehiculo()
{
	var v;
	v=document.incidente.visibles.value;
	if(v<10)
	{
		document.getElementById('tr_'+v+'_0').style.display='';
		document.getElementById('tr_'+v+'_1').style.display='';
		document.getElementById('tr_'+v+'_2').style.display='';
		document.getElementById('borrar_'+v).value=0;
		document.incidente.visibles.value++;
		
		
	}
}
function nuevo_lesionado(h)
{
	var v;
	v=document.getElementById('visibles_'+h).value;
	if(v < <?php echo $cant_les; ?> )
	{
		document.getElementById('tr_'+h+'_'+v+'_0').style.display='';
		document.getElementById('tr_'+h+'_'+v+'_1').style.display='';
		document.getElementById('borrar_'+h+'_'+v).value=0;
		document.getElementById('visibles_'+h).value++;
		
		
	}
	else
	alert('Limite de <?php echo $cant_les;?> superado')
}

function eliminar_lesionado(h)
{
	var v;
	v=document.getElementById('visibles_'+h).value;
	if(v>=1)
	{
		document.getElementById('tr_'+h+'_'+(parseFloat(v)-1)+'_0').style.display='none';
		document.getElementById('tr_'+h+'_'+(parseFloat(v)-1)+'_1').style.display='none';
		document.getElementById('borrar_'+h+'_'+(parseFloat(v)-1)).value=1;
		document.getElementById('visibles_'+h).value--;
	}
}

function eliminar_vehiculo()
{
	var v;
	v=document.incidente.visibles.value;
	if(v>=1)
	{
		document.getElementById('tr_'+(parseFloat(v)-1)+'_0').style.display='none';
		document.getElementById('tr_'+(parseFloat(v)-1)+'_1').style.display='none';
		document.getElementById('tr_'+(parseFloat(v)-1)+'_2').style.display='none';
		document.getElementById('lesionados_vehiculo_'+(parseFloat(v)-1)).style.display='none';
		document.getElementById('borrar_'+(parseFloat(v)-1)).value=1;
		document.incidente.visibles.value--;
	}
}

function guardar()
{
	var listo=true;
	for(i=0;i<10;i++)
	{
		if(document.getElementById('borrar_'+i).value==0 && document.getElementById('id_tipo_vehiculo_'+i).value=='' && listo)
		{
			alert('Seleccione el Tipo de Vehiculo para la posicion '+(parseFloat(i)+1));
			 document.getElementById('id_tipo_vehiculo_'+i).focus();
			 listo=false;
		}
		else
		if(listo)
		{
			for(l=0;l<10;l++)
			{
				if(document.getElementById('borrar_'+i+'_'+l).value==0 && document.getElementById('nombre_'+i+'_'+l).value=='' && listo)
				{
					alert('Ingrese el Nombre del Lesionado '+(parseFloat(l)+1)+' ubicado en el Vehiculo '+(parseFloat(i)+1));
					 document.getElementById('nombre_'+i+'_'+l).focus();
					 listo=false;
				}
			}
		}
	}
	
	if(listo)
	{
		document.incidente.accion.value='guardar'
		document.incidente.submit();	
	}
	
	
	
}

function ver_lesionados(pos)
{
	if(document.getElementById('lesionados_vehiculo_'+pos).style.display=='')
	{
		document.getElementById('lesionados_vehiculo_'+pos).style.display='none';
		if(document.getElementById('visibles_'+pos).value==1)
			document.getElementById('borrar_'+pos+'_0').value=1;
		
	}
	else
	{
		document.getElementById('lesionados_vehiculo_'+pos).style.display='';
		document.getElementById('borrar_'+pos+'_0').value=0;
	}
}
function mostrar_campos(i,l,valor)
{
var ver='';
	if(valor==10)
	{
		ver='none';	
	}
	

	document.getElementById('id_hospital_'+i+'_'+l).style.display=ver;
	document.getElementById('id_centro_salud_'+i+'_'+l).style.display=ver;
	document.getElementById('id_clinica_'+i+'_'+l).style.display=ver;
	document.getElementById('id_otro_lesionado_'+i+'_'+l).style.display=ver;



}

function cerrar()
{
	window.opener.recargar(document.incidente.id_buscar.value);
//location.reload();
	window.close();
}

</script>
<?php
if(isset($_GET['id_buscar']) || isset($_POST['id_buscar']))
{
	if(isset($_GET['id_buscar']))
		$id_buscar=$_GET['id_buscar'];
	else
		$id_buscar=$_POST['id_buscar'];
}
?>
<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<form name="incidente" method="post" action="vehiculo_incidente.php" enctype="multipart/form-data">
<center>

<table>
<tr><th colspan="17" height="20">&nbsp;</th></tr>
<tr class="cab_grid">
<th  colspan="17"><span class="style1">VEHICULOS INVOLUCRADOS</span></th>
</tr>
<tr>
<th class="LegendSt" rowspan="2">Pos</th>
<th class="LegendSt" rowspan="2">Tipo</th>
<th class="LegendSt" rowspan="2">Marca<hr/>Modelo</th>
<th class="LegendSt" rowspan="2">Placa<hr />Color</th>
<th class="LegendSt" rowspan="2">SOAT Vehiculo <hr />Aseguradora</th>
<th class="LegendSt" >Heridos</th>
<th class="LegendSt" >Muertos</th>
<th class="LegendSt" colspan="2">Sitio de Traslado de Vehiculos</th>
<th class="LegendSt" rowspan="2">Observaciones</th>
<th class="LegendSt" rowspan="2">Acciones</th>
</tr>
<tr>
<th class="LegendSt" colspan='2'>Grua</th>
<th class="LegendSt">Parqueadero<hr />Transito</th>
<th class="LegendSt">Taller<hr />Otro</th>

</tr>
<?php 
$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente WHERE id_incidente=".$id_buscar." ORDER BY id_vehiculo";
$veh=$_SESSION[APL]->bd->getRs($sql);


$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado ORDER BY id";
$tveh=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_aseguradora ORDER BY id";
$aseg=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_parqueadero ORDER BY id";
$par=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_transito ORDER BY id";
$tra=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_taller ORDER BY id";
$tal=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_grua ORDER BY id";
$gru=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_vehiculo ORDER BY id";
$otr=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia ORDER BY id";
$tra_p=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_hospital ORDER BY id";
$hos=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_centro_salud ORDER BY id";
$censa=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_clinica ORDER BY id";
$cli=$_SESSION[APL]->bd->getRs($sql);
$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_lesionado ORDER BY id";
$otrl=$_SESSION[APL]->bd->getRs($sql);

$visi=0;
for($i=0;$i<10;$i++)
{
if(!$veh->EOF)
{
	$estilo="";
	$visi++;
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
	$veh->MoveNext();
}
else
{
	if($i!=0)
	$estilo="style='display:none'";
	else
	{
	$estilo="";
	$visi++;
	}
	$id_vehiculo='';
	$id_incidente='';
	$id_tipo_vehiculo='';
	$referencia_vehiculo='';
	$modelo_vehiculo='';
	$placa_vehiculo='';
	$color_vehiculo='';
	$soat_vehiculo='';
	$id_aseguradora='';
	$nro_heridos='';
	$nro_muertos='';
	$ocupantes='';
	$id_parqueadero='';
	$id_transito='';
	$id_taller='';
	$id_otro_vehiculo='';
	$observaciones='';
	$id_grua='';
}
if($i%2==0)
   		$bg="bgcolor='#EEEEEE'";
	else
		$bg="bgcolor='#DDDDDD'";

?>
<tr <?php echo $bg?> id="tr_<?php echo $i?>_0" <?php echo $estilo?> bgcolor="">
<td rowspan="2" align="center">
<?php echo $i+1?>
</td>
<td  align="center"  rowspan="2">
<input type="hidden" name="id_vehiculo_<?php echo $i?>" value="<?php echo $id_vehiculo?>"/>
<input type="hidden" name="borrar_<?php echo $i?>" id="borrar_<?php echo $i?>" value="
<?php
if($estilo=='')
echo "0";
else
	echo "1";
?>

" />
<select name="id_tipo_vehiculo_<?php echo $i?>" id="id_tipo_vehiculo_<?php echo $i?>" class="campos">
<option value=""></option>
<?php
$tveh->MoveFirst();
while (!$tveh->EOF) {
   	echo "<option value='".$tveh->fields[0]."' ";
	if($id_tipo_vehiculo==$tveh->fields[0])
		echo "selected";
	echo ">".$tveh->fields[1]."</option>";
    $tveh->MoveNext();
}
?>
</select>
</td>
<td  align="center">
<input name="referencia_vehiculo_<?php echo $i?>" type="text" class="campos" value="<?php echo $referencia_vehiculo?>" size="15" maxlength="100" />
</td>

<td  align="center">
<input name="placa_vehiculo_<?php echo $i?>" type="text" class="campos" value="<?php echo $placa_vehiculo?>" size="10" maxlength="20" />
</td>

<td align="center"  >
<input name="soat_vehiculo_<?php echo $i?>" type="text" class="campos" value="<?php echo $soat_vehiculo?>" size="15" maxlength="100" />
</td>


<td align="center">
<input type="text" name="nro_heridos_<?php echo $i?>" value="<?php echo $nro_heridos?>" size="3" class="campos"/>
</td>

<td align="center">
<input type="text" name="nro_muertos_<?php echo $i?>" value="<?php echo $nro_muertos?>" size="3" class="campos"/>
</td>

<td class="style1" align="center" >
<select name="id_parqueadero_<?php echo $i?>" class="campos">
<option value=""></option>
<?php

$par->MoveFirst();
while (!$par->EOF) {
   	echo "<option value='".$par->fields[0]."' ";
	if($id_parqueadero==$par->fields[0])
		echo "selected";
	echo ">".$par->fields[1]."</option>";
    $par->MoveNext();
}

?>
</select></td>

<td  align="center" >
<select name="id_taller_<?php echo $i?>" class="campos">
<option value=""></option>
<?php

$tal->MoveFirst();
while (!$tal->EOF) {
   	echo "<option value='".$tal->fields[0]."' ";
	if($id_taller==$tal->fields[0])
		echo "selected";
	echo ">".$tal->fields[1]."</option>";
    $tal->MoveNext();
}
?>
</select>
</td>


<td  align="center"  rowspan="2">
<textarea name="observaciones_<?php echo $i?>" class="campos" cols="20" rows="2"><?php echo $observaciones?></textarea>
</td>
<td rowspan="2">
   <?php 
echo $_SESSION[APL]->getButtom('.','Lesionados/Muertos', '50', 'onclick="ver_lesionados('.$i.')"');
?>

</td>
</tr>
<tr <?php echo $bg?> id="tr_<?php echo $i?>_1" <?php echo $estilo?>>



<td  align="center">
<input name="modelo_vehiculo_<?php echo $i?>" type="text" class="campos" value="<?php echo $modelo_vehiculo?>" size="15" maxlength="100" />
</td>
<td align="center"  >
<input name="color_vehiculo_<?php echo $i?>" type="text" class="campos" value="<?php echo $color_vehiculo?>" size="10" maxlength="20" />
</td>
<td align="center"  >
<select name="id_aseguradora_<?php echo $i?>" class="campos">
<option value=""></option>
<?php

$aseg->MoveFirst();
while (!$aseg->EOF) {
   	echo "<option value='".$aseg->fields[0]."' ";
	if($id_aseguradora==$aseg->fields[0])
		echo "selected";
	echo ">".$aseg->fields[1]."</option>";
    $aseg->MoveNext();
}

?>
</select></td>
<td  align="center" colspan='2'>



<select name="id_grua_<?php echo $i?>" class="campos">
<option value=""></option>
<?php

$gru->MoveFirst();
while (!$gru->EOF) {
   	echo "<option value='".$gru->fields[0]."' ";
	if($id_grua==$gru->fields[0])
		echo "selected";
	echo ">".$gru->fields[1]."</option>";
    $gru->MoveNext();
}
?>
</select>


</td>

<td  align="center" ><select name="id_transito_<?php echo $i?>" class="campos">
<option value=""></option>
<?php

$tra->MoveFirst();
while (!$tra->EOF) {
   	echo "<option value='".$tra->fields[0]."' ";
	if($id_transito==$tra->fields[0])
		echo "selected";
	echo ">".$tra->fields[1]."</option>";
    $tra->MoveNext();
}
?>
</select></td>
<td align="center">
<select name="id_otro_vehiculo_<?php echo $i?>" class="campos">
<option value=""></option>
<?php

$otr->MoveFirst();
while (!$otr->EOF) {
   	echo "<option value='".$otr->fields[0]."' ";
	if($id_otro_vehiculo==$otr->fields[0])
		echo "selected";
	echo ">".$otr->fields[1]."</option>";
    $otr->MoveNext();
}

?>
</select>
</td>
</tr>
<!---LESIONADOS-->
<?php
if($id_vehiculo!='')
{
	$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo WHERE id_vehiculo=".$id_vehiculo;
	$les_v=$_SESSION[APL]->bd->getRs($sql);
}
?>
<tr>
<td colspan="17" >
<table id="lesionados_vehiculo_<?php echo $i?>"  <?php echo $bg?> width="100%" 
<?php 
if($id_vehiculo!='' && $les_v->NumRows()>0)
	echo "";
else
	echo 'style="display:none"';

?>

>
<tr>
<td rowspan="2" width="30px">&nbsp;</td>
<th class="LegendSt" rowspan="2">Pos</th>
<th class="LegendSt" rowspan="2">Tipo</th>
<th class="LegendSt">Trasladador Por</th>
<th class="LegendSt">Auxiliar Enfermeria</th>

<th class="LegendSt">Nombre</th>
<th class="LegendSt">Cedula</th>

<th class="LegendSt">Hospital</th>
<th class="LegendSt">Centro Salud</th>
<th class="LegendSt" rowspan="2">Observaciones</th>
</tr>
<tr >
<th class="LegendSt">Diagnostico</th>
<th class="LegendSt">Conductor</th>
<th class="LegendSt">Direccion</th>
<th class="LegendSt">Telefono</th>
<th class="LegendSt">Clinica</th>
<th class="LegendSt">Estado</th>
</tr>
<?php 

$visi_l=0;
for($l=0;$l<$cant_les;$l++)
{
	if($l%2==0)
   		$bgl="bgcolor='#D9DFF0'";
	else
		$bgl="bgcolor='#C4C8F2'";
	if(isset($les_v) && !$les_v->EOF)
	{
		$estilo_l='';
		$visi_l++;
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
		$lesionado_les=$les_v->fields[16];
		$muerto_les=$les_v->fields[17];
		
		
		$les_v->MoveNext();
		
	}
	else
	{
		if($l!=0)
			$estilo_l="style='display:none'";
		else
		{
			$estilo_l=""; 
			$visi_l++;
		}
		$id_lesionado='';
		$id_vehiculo='';
		$id_trasladado_por='';
		$conductor='';
		$auxiliar_enfermeria='';
		$nombre='';
		$cedula='';
		$telefono='';
		$direccion='';
		$diagnostico='';
		$id_hospital='';
		$id_clinica='';
		$id_centro_salud='';
		$id_otro_lesionado='';
		$observaciones_les='';
		$conducia_les='';
		$lesionado_les='';
		$muerto_les='';
	}
	
	?>
	<tr id="tr_<?php echo $i."_".$l?>_0" <?php echo $estilo_l." ".$bgl?> >
	<td rowspan="2" <?php echo $bg?> width="30px">&nbsp;</td>
	<td align="left" rowspan="2">
	<?php echo ($i+1).".".($l+1)?>
	</td>
	<td rowspan="2" align="left">
	Conductor <input type="checkbox" name="conducia_<?php echo $i."_".$l?>" id="conducia_<?php echo $i."_".$l?>" class="campos" <?php if($conducia_les=='SI') echo "checked"?> /><br>
	Lesionado <input type="checkbox" name="lesionado_<?php echo $i."_".$l?>" id="lesionado_<?php echo $i."_".$l?>" class="campos" <?php if($lesionado_les=='SI') echo "checked" ?>/><br />
	Muerto <input type="checkbox" name="muerto_<?php echo $i."_".$l?>" id="muerto_<?php echo $i."_".$l?>" class="campos" <?php if($muerto_les=='SI') echo "checked" ?>/>
	
	</td>
	<td>
	<input type="hidden" name="id_lesionado_<?php echo $i."_".$l?>" value="<?php echo $id_lesionado?>" />
	<input type="hidden" name="borrar_<?php echo $i."_".$l?>" id="borrar_<?php echo $i."_".$l?>" value="
	<?php
if($id_lesionado!='')
echo "0";
else
	echo "1";
?>
	
	" />
	
		<select name="id_trasladado_por_<?php echo $i."_".$l?>" class="campos" onChange="mostrar_campos(<?php echo $i?>,<?php echo $l?>,this.value)">
		<option value=""></option>
		<?php
		$tra_p->MoveFirst();
		while (!$tra_p->EOF) {
			echo "<option value='".$tra_p->fields[0]."' ";
			if($id_trasladado_por==$tra_p->fields[0])
				echo "selected";
			echo ">".$tra_p->fields[1]."</option>";
			$tra_p->MoveNext();
		}
		?>
		</select>
	</td>
	<td>
	<input type="text" name="auxiliar_enfermeria_<?php echo $i."_".$l?>" class="campos" value="<?php echo $auxiliar_enfermeria?>"/>
	</td>
	<td>
	<input type="text" name="nombre_<?php echo $i."_".$l?>" id="nombre_<?php echo $i."_".$l?>" class="campos" value="<?php echo $nombre?>" />
	
	
	</td>
	<td>
	<input type="text" name="cedula_<?php echo $i."_".$l?>" class="campos" value="<?php if(isset($id_buscar)) echo number_format(str_replace('.','',str_replace(' ','',$cedula)),0,'.','.')?>" />
	</td>
	
	
	<td>
	<select name="id_hospital_<?php echo $i."_".$l?>" id="id_hospital_<?php echo $i."_".$l?>" class="campos" <?php if($id_trasladado_por==10) echo "style='display:none'"?>>
	<option value=""></option>
	<?php
	$hos->MoveFirst();
	while (!$hos->EOF) {
		echo "<option value='".$hos->fields[0]."' ";
		if($id_hospital==$hos->fields[0])
			echo "selected";
		echo ">".$hos->fields[1]."</option>";
		$hos->MoveNext();
	}
	?>
	</select>
	</td>
	<td>
	<select name="id_centro_salud_<?php echo $i."_".$l?>" id="id_centro_salud_<?php echo $i."_".$l?>" class="campos" <?php if($id_trasladado_por==10) echo "style='display:none'"?>>
	<option value=""></option>
	<?php
	$censa->MoveFirst();
	while (!$censa->EOF) {
		echo "<option value='".$censa->fields[0]."' ";
		if($id_centro_salud==$censa->fields[0])
			echo "selected";
		echo ">".$censa->fields[1]."</option>";
		$censa->MoveNext();
	}
	?>
	</select>
	</td>
	<td rowspan="2">
	<textarea name="observaciones_<?php echo $i."_".$l?>" class="campos" cols="20" rows="2"><?php echo $observaciones_les?></textarea>
	</td>
</tr>
<tr id="tr_<?php echo $i."_".$l?>_1" <?php echo $estilo_l." ".$bgl?>>
<td>
	<input type="text" name="diagnostico_<?php echo $i."_".$l?>" class="campos" value="<?php echo $diagnostico?>" maxlength="200"/>
	</td>
	<td>
	<input type="text" name="conductor_<?php echo $i."_".$l?>" class="campos" value="<?php  echo $conductor?>" maxlength="50"/>
	</td>
	
	<td>
	<input type="text" name="direccion_<?php echo $i."_".$l?>" class="campos" value="<?php echo $direccion?>" maxlength="50"/>
	</td>
	<td>
	<input type="text" name="telefono_<?php echo $i."_".$l?>" class="campos" value="<?php echo $telefono?>" maxlength="20"/>
	
	</td>
	<td>
	<select name="id_clinica_<?php echo $i."_".$l?>" id="id_clinica_<?php echo $i."_".$l?>" class="campos" <?php if($id_trasladado_por==10) echo "style='display:none'"?>>
<option value=""></option>
<?php
$cli->MoveFirst();
while (!$cli->EOF) {
   	echo "<option value='".$cli->fields[0]."' ";
	if($id_clinica==$cli->fields[0])
		echo "selected";
	echo ">".$cli->fields[1]."</option>";
    $cli->MoveNext();
}
?>
</select>
	</td>
	<td>
	<select name="id_otro_lesionado_<?php echo $i."_".$l?>" id="id_otro_lesionado_<?php echo $i."_".$l?>" class="campos" <?php if($id_trasladado_por==10) echo "style='display:none'"?>>
<option value=""></option>
<?php

$otrl->MoveFirst();
while (!$otrl->EOF) {
   	echo "<option value='".$otrl->fields[0]."' ";
	if($id_otro_lesionado==$otrl->fields[0])
		echo "selected";
	echo ">".$otrl->fields[1]."</option>";
    $otrl->MoveNext();
}
?>
</select>
	</td>
	</tr>
	
	
	<?php
}
?>
<input type="hidden" name="visibles_<?php echo $i?>" id="visibles_<?php echo $i?>" value="<?php echo $visi_l?>" />
<tr>
<td colspan="7" align="center" height="40px" valign="middle">
<table>
<tr>
<td>
   <?php 

?>
</td>
<td>
   <?php 

?>
</td>
</tr></table>

</td>
</tr>


</table>
</td></tr>
<!---LESIONADOS-->

<tr>
<td colspan="17" id="tr_<?php echo $i?>_2" <?php echo $estilo?> <?php echo $bg?>><hr /></td>
</tr>


<?php
}
?>


<tr>
<td colspan="17" align="center" height="40px" valign="middle">
<table>
<tr>
<td align="center">
   <?php 

?>
</td>
<td>
   <?php 

?>
</td>
<td>
<?php 

?>
</td>
<td>
<?php 

?>
</td>
</tr></table>


</td>
</tr>
</table>
</center>
<input type="hidden" name="visibles" value="<?php echo $visi?>" />
<input type="hidden" name="accion" value="" />
<input type="hidden" name="id_buscar" value="<?php echo $id_buscar?>" />

</form>
</body>
<script>
	$('input:text').attr('disabled','disabled');
	$('select').attr('disabled','disabled');
	$('textarea').attr('disabled','disabled');
	$('input:checkbox').attr('disabled','disabled');
</script>
</html>