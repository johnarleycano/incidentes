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
			'id_grua'=>$_POST['id_grua_'.$i]==''?NULL:$_POST['id_grua_'.$i],
			'cilindraje_vehiculo'=>$_POST['cilindraje_vehiculo_'.$i]);
			
			
			$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente
			(
				id_vehiculo,id_incidente,id_tipo_vehiculo,referencia_vehiculo,modelo_vehiculo,
				placa_vehiculo,color_vehiculo,soat_vehiculo,id_aseguradora,nro_heridos,nro_muertos,
				id_parqueadero,id_taller,id_otro_vehiculo,observaciones,id_grua,cilindraje_vehiculo)
			VALUES
			(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
				echo "<script>alert('Error al crear Vehiculo ".$i."')</script>";
			else//lesionados
			{
				for($l=0;$l<$cant_les;$l++)
				{
					$conducia_v='NO';
					if(isset($_POST['conducia_'.$i.'_'.$l]))
						$conducia_v='SI';

					$lesionado_v='NO';
					if(isset($_POST['lesionado_'.$i.'_'.$l]))
						$lesionado_v='SI';

					$muerto_v='NO';
					if(isset($_POST['muerto_'.$i.'_'.$l]))
						$muerto_v='SI';
					
					$tipo_lesion='';
					if( $lesionado_v=='SI' and isset($_POST['radTipLes_'.$i.'_'.$l]) )
						$tipo_lesion = $_POST['radTipLes_'.$i.'_'.$l];
					
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
							'edad'=>$_POST['edad_'.$i.'_'.$l],
							'tipo_lesion'=>$tipo_lesion
						);
						$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
						(
							id_lesionado,id_vehiculo,id_trasladado_por,conductor,auxiliar_enfermeria,nombre,
							cedula,telefono,direccion,diagnostico,id_hospital,id_clinica,id_centro_salud,
							id_otro_lesionado,observaciones,conducia,lesionado,muerto,edad,tipo_lesion)
						VALUES
						(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
							'edad'=>$_POST['edad_'.$i.'_'.$l],
							'tipo_lesion'=>$tipo_lesion,
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
						muerto=?,
						edad=?,
						tipo_lesion=?
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
		else if($_POST['id_vehiculo_'.$i]!='' && $_POST['borrar_'.$i]==0 && $_POST['id_tipo_vehiculo_'.$i]!='')//actualizar
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
			'cilindraje_vehiculo'=>$_POST['cilindraje_vehiculo_'.$i],
			'id_vehiculo'=>$_POST['id_vehiculo_'.$i],
			'id_incidente'=>$_POST['id_buscar'],
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
			id_grua=?,
			cilindraje_vehiculo=?
			WHERE
			id_vehiculo=? AND id_incidente=?";
			if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
				echo "<script>alert('Error al Actualizar Vehiculo ".$i."')</script>";
			else//lesionados
			{
				$id_vehiculo=$_POST['id_vehiculo_'.$i];
				for($l=0;$l<$cant_les;$l++)
				{
					$conducia_v='NO';
					if(isset($_POST['conducia_'.$i.'_'.$l]))
						$conducia_v='SI';
					
					$lesionado_v='NO';
					if(isset($_POST['lesionado_'.$i.'_'.$l]))
						$lesionado_v='SI';
						
					$muerto_v='NO';
					if(isset($_POST['muerto_'.$i.'_'.$l]))
						$muerto_v='SI';
						
					$tipo_lesion='';
					if( $lesionado_v=='SI' and isset($_POST['radTipLes_'.$i.'_'.$l]) )
						$tipo_lesion = $_POST['radTipLes_'.$i.'_'.$l];
					
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
							'muerto'=>$muerto_v,
							'edad'=>$_POST['edad_'.$i.'_'.$l],
							'tipo_lesion'=>$tipo_lesion
							
						);
						$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
						(
							id_lesionado,id_vehiculo,id_trasladado_por,conductor,auxiliar_enfermeria,nombre,
							cedula,telefono,direccion,diagnostico,id_hospital,id_clinica,id_centro_salud,
							id_otro_lesionado,observaciones,conducia,lesionado,muerto,edad,tipo_lesion)
						VALUES
						(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
							'edad'=>$_POST['edad_'.$i.'_'.$l],
							'tipo_lesion'=>$tipo_lesion,
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
						muerto=?,
						edad=?,
						tipo_lesion=?
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
		else if($_POST['id_vehiculo_'.$i]!='' && ($_POST['borrar_'.$i]=="1" or $_POST['borrar_'.$i]==1) )//borrar
		{
			$parametros=array(
				'id_vehiculo'=>$_POST['id_vehiculo_'.$i]
			);
			$sql = "DELETE FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
					WHERE id_vehiculo=?";
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
			window.parent.regargarSOS(".$_POST['id_buscar'].");
			window.parent.cerrarSOSVehInv();
	  </script>";
}
?>
<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<script type="text/javascript" src="libs/js/vista.js"></script>
<script>
function nuevo_vehiculo()
{
	var v = document.incidente.visibles.value;

	if(v<10)
	{
		document.getElementById('tr_'+v+'_0').style.display='';
		document.getElementById('tr_'+v+'_1').style.display='';
		document.getElementById('tr_'+v+'_2').style.display='';
		document.getElementById('tr_'+v+'_3').style.display='';
		document.getElementById('borrar_'+v).value=0;
		document.incidente.visibles.value++;
	}
}

function nuevo_lesionado(h)
{
	var v = document.getElementById('visibles_'+h).value;

	if( v < <?php echo $cant_les; ?> )
	{
		document.getElementById('tr_'+h+'_'+v+'_0').style.display='';
		document.getElementById('tr_'+h+'_'+v+'_1').style.display='';
		document.getElementById('tr_'+h+'_'+v+'_2').style.display='';
		document.getElementById('tr_'+h+'_'+v+'_3').style.display='';
		document.getElementById('tr_'+h+'_'+v+'_4').style.display='';
		document.getElementById('tr_'+h+'_'+v+'_5').style.display='';
		document.getElementById('borrar_'+h+'_'+v).value=0;
		document.getElementById('visibles_'+h).value++;
	}
	else
		alert('Limite de <?php echo $cant_les;?> superado')
}

function eliminar_lesionado(h)
{
	var v = document.getElementById('visibles_'+h).value;

	if( v>=1 )
	{
		document.getElementById('tr_'+h+'_'+(parseFloat(v)-1)+'_0').style.display='none';
		document.getElementById('tr_'+h+'_'+(parseFloat(v)-1)+'_1').style.display='none';
		document.getElementById('tr_'+h+'_'+(parseFloat(v)-1)+'_2').style.display='none';
		document.getElementById('tr_'+h+'_'+(parseFloat(v)-1)+'_3').style.display='none';
		document.getElementById('tr_'+h+'_'+(parseFloat(v)-1)+'_4').style.display='none';
		document.getElementById('tr_'+h+'_'+(parseFloat(v)-1)+'_5').style.display='none';
		document.getElementById('borrar_'+h+'_'+(parseFloat(v)-1)).value=1;
		document.getElementById('visibles_'+h).value--;
	}
}

function eliminar_vehiculo()
{
	var v = document.incidente.visibles.value;
	if(v>=1)
	{
		/*document.getElementById('tr_'+(parseFloat(v)-1)+'_0').style.display='none';
		document.getElementById('tr_'+(parseFloat(v)-1)+'_1').style.display='none';
		document.getElementById('tr_'+(parseFloat(v)-1)+'_2').style.display='none';
		document.getElementById('tr_'+(parseFloat(v)-1)+'_3').style.display='none';
		document.getElementById('tr_'+(parseFloat(v)-1)+'_4').style.display='none';
		document.getElementById('lesionados_vehiculo_'+(parseFloat(v)-1)).style.display='none';*/
		var vNumFil = parseInt(v)-1;
		$("#tr_"+vNumFil+"_0").hide();
		$("#tr_"+vNumFil+"_1").hide();
		$("#tr_"+vNumFil+"_2").hide();
		$("#tr_"+vNumFil+"_3").hide();
		$("#tr_"+vNumFil+"_4").hide();
		$("#lesionados_vehiculo_"+vNumFil).hide();
		document.getElementById('borrar_'+(parseFloat(v)-1)).value=1;
		document.incidente.visibles.value--;
		//lesionados_vehiculo_
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
		else if(listo)
		{
			for(l=0;l<10;l++)
			{
				if(document.getElementById('borrar_'+i+'_'+l).value==0 && document.getElementById('nombre_'+i+'_'+l).value=='' && listo)
				{
					var nomLabLes = 'Lesionado';
					if( $("#conducia_"+i+"_"+l).attr('checked') )
						nomLabLes = 'Conductor';

					alert('Ingrese el Nombre del '+nomLabLes+' '+(parseFloat(l)+1)+' ubicado en el Vehiculo '+(parseFloat(i)+1));
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
	//window.opener.recargar(document.incidente.id_buscar.value);
	window.parent.regargarSOS(document.incidente.id_buscar.value);
	window.parent.cerrarSOSVehInv();
}

function calcularLesMue()
{
	for( var i=0; i<10; i++ )
	{
		var canLes = 0;
		var canMue = 0;

		$(".cssCheLes_"+i).each(function()
		{
			if( this.checked==true )
				canLes++;
		});

		$(".cssCheMue_"+i).each(function()
		{
			if( this.checked==true )
				canMue++;
		});
		
		$("#nro_heridos_"+i).val(canLes);
		$("#nro_muertos_"+i).val(canMue);
	}
}

function prenderApagarFilTipLes(pId,pOpc,pNumVeh,pNumLes)
{
	// Si esta chequedado y es apagar
	// Oculta la fila de grave y leve
	if( $("#"+pId).attr('checked') )
	{
		$("#trTipLesG_"+pNumVeh+"_"+pNumLes).show();
		$("#trTipLesL_"+pNumVeh+"_"+pNumLes).show();
	}
	else
	{
		$("#trTipLesG_"+pNumVeh+"_"+pNumLes).hide();
		$("#trTipLesL_"+pNumVeh+"_"+pNumLes).hide();
	}
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
$visi = 0;
?>

<style>
	.input
	{
		width:40px !important;
	}
	.input2
	{
		width:20px !important;
	}
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
	.cssBus input[type="text"], select
	{
		width:200px;
		border: 1px solid #72B42D;
	}
	.cssBus select cmpGra
	{
		width:280px;
	}
	.tabEdi
	{
		border: #72B42D 1px solid;
	}
	.estVeh_1
	{
		text-transform: uppercase !important;
		background-color: #DDFFDD !important;
	}
	.estVeh_2
	{
		text-transform: uppercase !important;
		background-color: #ECECC3 !important;
	}
	.estLes_1
	{
		background-color: #E1E2CA !important;
	}
	.estLes_2
	{
		background-color: #D6D6BB !important;
	}
</style>
<form name="incidente" method="post" action="vehiculo_incidente.php" enctype="multipart/form-data">
<center>
	<table class="cssBus" cellpadding="3" border="0">
		<tr><th class="LegendSt" style="background-color:#4CB877">VEHICULOS INVOLUCRADOS</th></tr>
		<tr>
			<td>
				<?php 
				$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente WHERE id_incidente=".$id_buscar." ORDER BY id_vehiculo";
				$veh=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado ORDER BY nombre";
				$tveh=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_aseguradora ORDER BY nombre";
				$aseg=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_parqueadero ORDER BY nombre";
				$par=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_transito ORDER BY nombre";
				$tra=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_taller ORDER BY nombre";
				$tal=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_grua ORDER BY nombre";
				$gru=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_vehiculo ORDER BY nombre";
				$otr=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia ORDER BY nombre";
				$tra_p=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_hospital ORDER BY nombre";
				$hos=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_centro_salud ORDER BY nombre";
				$censa=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_clinica ORDER BY nombre";
				$cli=$_SESSION[APL]->bd->getRs($sql);

				$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_lesionado ORDER BY nombre";
				$otrl=$_SESSION[APL]->bd->getRs($sql);
				
				
				?>
				<table width="100%" cellpadding="3" class="tabEdi">
				<?php
				$visi=0;
				
				for($i=0;$i<10;$i++)
				{// Ini for 1
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
					$cilindraje_vehiculo='';
					$estilo='';
					
					$styVeh = 'estVeh_1';
					if( $i%2!=0 )
						$styVeh = 'estVeh_2';
					
					if(!$veh->EOF)
					{
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
						$cilindraje_vehiculo=$veh->fields[18];
						$veh->MoveNext();
					}
					else
					{
						if($i!=0)
							$estilo='style="display:none"';
						else
							$visi++;
					}
					?>
					<input type="hidden" name="id_vehiculo_<?php echo $i?>" value="<?php echo $id_vehiculo?>"/>
					<input type="hidden" name="borrar_<?php echo $i?>" id="borrar_<?php echo $i?>" value="<?php if($estilo=='') echo "0";else echo "1"; ?>"/>
					<tr id="tr_<?php echo $i?>_0" <?php echo $estilo; ?> class="<?php echo $styVeh; ?>">
						<th class="resaltar <?php echo $styVeh; ?>" rowspan="4" valign="top" align="center" >Pos<br/><?php echo $i+1; ?></th>
						<th class="resaltar <?php echo $styVeh; ?>">Tipo</th>
						<td>
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
						<th class="resaltar <?php echo $styVeh; ?>">Modelo</th>
						<td><input name="modelo_vehiculo_<?php echo $i?>" type="text" class="campos cmpPeq" value="<?php echo $modelo_vehiculo?>" size="15" maxlength="100" /></td>
						<th class="resaltar <?php echo $styVeh; ?>">Cilindraje</th>
						<td><input name="cilindraje_vehiculo_<?php echo $i?>" type="text" class="input" value="<?php echo $cilindraje_vehiculo?>" size="15" maxlength="100" /></td>
						<td class="input2">c.c</td>
						<th class="resaltar <?php echo $styVeh; ?>">Taller</th>
						<td>
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
						<th class="resaltar <?php echo $styVeh; ?>">Heridos</th>
						<td><input type="text" id="nro_heridos_<?php echo $i?>" name="nro_heridos_<?php echo $i?>" value="<?php echo $nro_heridos?>" size="3" class="campos cmpPeq"/></td>
					</tr>
					<tr id="tr_<?php echo $i?>_1" <?php echo $estilo; ?> class="<?php echo $styVeh; ?>">
						<th class="resaltar <?php echo $styVeh; ?>">SOAT Vehiculo</th>
						<td><input name="soat_vehiculo_<?php echo $i?>" type="text" class="campos cmpGra" value="<?php echo $soat_vehiculo?>" size="15" maxlength="100" /></td>
						<th class="resaltar <?php echo $styVeh; ?>">Placa</th>
						<td><input name="placa_vehiculo_<?php echo $i?>" type="text" class="campos cmpPeq" value="<?php echo $placa_vehiculo?>" size="10" maxlength="20" /></td>
						<th class="resaltar <?php echo $styVeh; ?>"></th>
						<td></td>
						<td class="input2"></td>
						<th class="resaltar <?php echo $styVeh; ?>">Otro</th>
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
						<th class="resaltar <?php echo $styVeh; ?>">Muertos</th>
						<td><input type="text" id="nro_muertos_<?php echo $i?>" name="nro_muertos_<?php echo $i?>" value="<?php echo $nro_muertos?>" size="3" class="campos cmpPeq"/></td>
					</tr>
					<tr id="tr_<?php echo $i?>_2" <?php echo $estilo; ?> class="<?php echo $styVeh; ?>">
						<th class="resaltar <?php echo $styVeh; ?>">Aseguradora</th>
						<td>
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
							</select>
						</td>
						<th class="resaltar <?php echo $styVeh; ?>">Color</th>
						<td><input name="color_vehiculo_<?php echo $i?>" type="text" class="campos cmpPeq" value="<?php echo $color_vehiculo?>" size="10" maxlength="20" /></td>
						<th class="resaltar <?php echo $styVeh; ?>"></th>
						<td></td>
						<td class="input2"></td>
						<th class="resaltar <?php echo $styVeh; ?>">Sitio de Traslado de<br/>Vehiculos Parqueadero</th>
						<td>
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
							</select>
						</td>
						<th class="resaltar <?php echo $styVeh; ?>">Observaciones</th>
						<td><textarea name="observaciones_<?php echo $i?>" class="campos" style="height:40px;width:200px"><?php echo $observaciones?></textarea></td>
					</tr>
					<tr id="tr_<?php echo $i?>_3" <?php echo $estilo; ?> class="<?php echo $styVeh; ?>">
						<th class="resaltar <?php echo $styVeh; ?>">Marca</th>
						<td><input name="referencia_vehiculo_<?php echo $i?>" type="text" class="campos cmpGra" value="<?php echo $referencia_vehiculo?>" size="15" maxlength="100"/></td>
						<th class="resaltar <?php echo $styVeh; ?>">Grua</th>
						<td>
							<select name="id_grua_<?php echo $i?>" class="campos cmpPeq">
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
						<th class="resaltar <?php echo $styVeh; ?>"></th>
						<td></td>
						<td class="input2"></td>
						<th class="resaltar <?php echo $styVeh; ?>">Transito</th>
						<td>
							<select name="id_transito_<?php echo $i?>" class="campos">
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
							</select>
						</td>
						<th class="resaltar <?php echo $styVeh; ?>">Acciones</th>
						<td align="center"><?php echo $_SESSION[APL]->getButtom('.','Lesionados/Muertos', '50', 'onclick="ver_lesionados('.$i.')"');?></td>
					</tr>

					<?php
					if( $id_vehiculo!='' )
					{
						$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo WHERE id_vehiculo=".$id_vehiculo;
						$les_v=$_SESSION[APL]->bd->getRs($sql);
					}
					
					$verLes = 'style="display:none"';
					$numReg = '';
					if( $id_vehiculo!='' && $les_v->NumRows()>0 )
					{
						$verLes = '';
						$numReg = $les_v->NumRows();
					}
					?>
					<!-- Ini Fila Lesionados -->
					<tr id="lesionados_vehiculo_<?php echo $i?>" <?php echo $verLes; ?> tam="<?php $numReg; ?>" class="<?php echo $styVeh; ?>">
						<td></td>
						<td colspan="11">
							<table width="100%" class="cssBus tabEdi" cellpadding="3" border="0">
							<?php 
							$visi_l=0;
							for($l=0;$l<$cant_les;$l++)
							{// Ini for 2
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
								$edad='';
								$tipo_lesion = '';
								$estilo_l='';
								
								$styLes = 'estLes_1';
								if( $l%2!=0 )
									$styLes = 'estLes_2';
								
								if( isset($les_v) && !$les_v->EOF )
								{
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
									$edad = $les_v->fields[18];
									$tipo_lesion = $les_v->fields[19];

									$les_v->MoveNext();
								}
								else
								{
									if($l!=0)
										$estilo_l='style="display:none"';
									else
										$visi_l++;
								}
								
								$mosTipLes = 'style="display:none"';
								if($lesionado_les=='SI')
									$mosTipLes = '';
								
								?>
								<input type="hidden" name="id_lesionado_<?php echo $i."_".$l?>" value="<?php echo $id_lesionado?>"/>
								<input type="hidden" name="borrar_<?php echo $i."_".$l?>" id="borrar_<?php echo $i."_".$l?>" value="<?php if($id_lesionado!='') echo "0";else echo "1"; ?>" />
								
								<tr id="tr_<?php echo $i."_".$l?>_0" <?php echo $estilo_l; ?> class="<?php echo $styLes; ?>">
									
									<th class="resaltar <?php echo $styLes; ?>" rowspan="6" valign="top">Pos<br/><?php echo ($i+1).".".($l+1)?></th>
									<th colspan="2" class="resaltar <?php echo $styLes; ?>">
										<table cellpadding="0" cellspacing="0">
											<tr valign="middle">
												<th class="resaltar <?php echo $styLes; ?>">Tipo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
												<td>Conductor</td><td><input type="checkbox" name="conducia_<?php echo $i."_".$l?>" id="conducia_<?php echo $i."_".$l?>"   class="campos" <?php if($conducia_les=='SI') echo "checked"?>/></td>
												<td>Lesionado</td><td><input type="checkbox" name="lesionado_<?php echo $i."_".$l?>" id="lesionado_<?php echo $i."_".$l?>" class="campos cssCheLes_<?php echo $i; ?>" <?php if($lesionado_les=='SI') echo "checked" ?> onclick="calcularLesMue();prenderApagarFilTipLes(this.id,'PRE',<?php echo $i;?>,<?php echo $l; ?>);"/></td>
												<td>Muerto</td><td><input type="checkbox" name="muerto_<?php echo $i."_".$l?>" id="muerto_<?php echo $i."_".$l?>"          class="campos cssCheMue_<?php echo $i; ?>" <?php if($muerto_les=='SI') echo "checked" ?> onclick="calcularLesMue();"/></td>
											</tr>
											<tr valign="middle" id="trTipLesL_<?php echo $i."_".$l?>" <?php echo $mosTipLes; ?>>
												<td colspan="3"></td>
												<td align="right">Leve</td><td><input type="radio" id="radTipLesL_<?php echo $i."_".$l?>" name="radTipLes_<?php echo $i."_".$l?>" value="L" <?php if($tipo_lesion=='L') echo "checked" ?>></td>
												<td colspan="2"></td>
											</tr>
											<tr valign="middle" id="trTipLesG_<?php echo $i."_".$l?>" <?php echo $mosTipLes; ?>>
												<td colspan="3"></td>
												<td align="right">Grave</td><td><input type="radio" id="radTipLesG_<?php echo $i."_".$l?>" name="radTipLes_<?php echo $i."_".$l?>" value="G" <?php if($tipo_lesion=='G') echo "checked" ?>></td>
												<td colspan="2"></td>
											</tr>
										</table>
									</th>
									<th class="resaltar <?php echo $styLes; ?>">Trasladado Por</th>
									<td>
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
									<th class="resaltar <?php echo $styLes; ?>">Diagnostico</th>
									<td><input type="text" name="diagnostico_<?php echo $i."_".$l?>" class="campos" value="<?php echo $diagnostico?>" maxlength="200"/></td>
								</tr>
								<tr id="tr_<?php echo $i."_".$l?>_1" <?php echo $estilo_l; ?> class="<?php echo $styLes; ?>">
									<th class="resaltar <?php echo $styLes; ?>">Auxiliar Enfermeria</th>
									<td><input type="text" name="auxiliar_enfermeria_<?php echo $i."_".$l?>" class="campos" value="<?php echo $auxiliar_enfermeria?>"/></td>
									<th class="resaltar <?php echo $styLes; ?>">Conductor amb.</th>
									<td><input type="text" name="conductor_<?php echo $i."_".$l?>" class="campos" value="<?php  echo $conductor?>" maxlength="50"/></td>
									<th class="resaltar <?php echo $styLes; ?>">Nombre</th>
									<td><input type="text" name="nombre_<?php echo $i."_".$l?>" id="nombre_<?php echo $i."_".$l?>" class="campos" value="<?php echo $nombre?>" /></td>
								</tr>
								<tr id="tr_<?php echo $i."_".$l?>_2" <?php echo $estilo_l; ?> class="<?php echo $styLes; ?>">
									<th class="resaltar <?php echo $styLes; ?>">Cedula</th>
									<td><input type="text" name="cedula_<?php echo $i."_".$l?>" class="campos" value="<?php if(isset($id_buscar) and $cedula!="" ) echo number_format(str_replace('.','',str_replace(' ','',$cedula)),0,'.','.') ?>" /></td>
									<th class="resaltar <?php echo $styLes; ?>">Edad</th>
									<td><input type="text" name="edad_<?php echo $i."_".$l?>" class="campos" value="<?php if(isset($id_buscar) and $edad!="" ) echo $edad; ?>" /></td>
									<th class="resaltar <?php echo $styLes; ?>">Direccion</th>
									<td><input type="text" name="direccion_<?php echo $i."_".$l?>" class="campos" value="<?php echo $direccion?>" maxlength="50"/></td>
								</tr>
								<tr id="tr_<?php echo $i."_".$l?>_3" <?php echo $estilo_l; ?> class="<?php echo $styLes; ?>">
									<th class="resaltar <?php echo $styLes; ?>">Telefono</th>
									<td><input type="text" name="telefono_<?php echo $i."_".$l?>" class="campos" value="<?php echo $telefono?>" maxlength="20"/></td>
									<th class="resaltar <?php echo $styLes; ?>">Hospital</th>
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
									<th class="resaltar <?php echo $styLes; ?>">Clinica</th>
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
								</tr>
								<tr id="tr_<?php echo $i."_".$l?>_4" <?php echo $estilo_l; ?> class="<?php echo $styLes; ?>">
									<th class="resaltar <?php echo $styLes; ?>">Centro Salud</th>
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
									<th class="resaltar <?php echo $styLes; ?>" rowspan="2">Observaciones</th>
									<td colspan="3" rowspan="2"><textarea name="observaciones_<?php echo $i."_".$l?>" class="campos" style="height:35px;width:500px"><?php echo $observaciones_les?></textarea></td>
								</tr>
								<tr id="tr_<?php echo $i."_".$l?>_5" <?php echo $estilo_l; ?> class="<?php echo $styLes; ?>">
									<th class="resaltar <?php echo $styLes; ?>">Estado</th>
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
							}// fin for 2
							?>
							</table>
							<center>
								<table cellpadding="3">
									<tr>
										<td><?php echo $_SESSION[APL]->getButtom('.','Agregar Nuevo Lesionado Vehiculo '.($i+1), '50', 'onclick="nuevo_lesionado('.$i.')"'); ?></td>
										<td><?php echo $_SESSION[APL]->getButtom('.','Eliminar Ultimo Lesionado Vehiculo '.($i+1), '50', 'onclick="eliminar_lesionado('.$i.')"','','middlered'); ?></td>
									</tr>
								</table>
							</center>
						</td>
					</tr>
					<!-- Fin Fila Lesionados -->
					<input type="hidden" name="visibles_<?php echo $i?>" id="visibles_<?php echo $i?>" value="<?php echo $visi_l?>"/>
				<?php
				}// fin for 1
				?>
				</table>
				<br/>
				<center>
					<table cellpadding="3">
						<tr>
							<td><?php echo $_SESSION[APL]->getButtom('.','Agregar Nuevo Vehiculo', '50', 'onclick="nuevo_vehiculo()"'); ?></td>
							<td><?php echo $_SESSION[APL]->getButtom('.','Eliminar Ultimo Vehiculo', '50', 'onclick="eliminar_vehiculo()"','','middlered'); ?></td>
						</tr>
					</table>
				</center>
			</td>
		</tr>
		<tr>
			<td align="center">
				<table cellpadding="3">
					<tr>
						<td><?php echo $_SESSION[APL]->getButtom('.','Guardar', '50', 'onclick="guardar()"'); ?></td>
						<td><?php echo $_SESSION[APL]->getButtom('.','Cerrar', '50', 'onclick="cerrar()"');   ?></td>
					</tr>
				</table>
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
	calcularLesMue();
</script>
</html>