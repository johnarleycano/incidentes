<?php 
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}

if(isset($_GET['tabla']))
	$tabla_rs=$_GET['tabla'];
elseif(isset($_POST['tabla']))
	$tabla_rs=$_POST['tabla'];
else
	$tabla_rs='';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/ventana.css" rel="stylesheet" type="text/css">	
<link href="css/campo.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" />
<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="libs/js/vista.js"></script>
	
<title>:: Editar Tabla <?php echo $tabla_rs?>::</title>

<?php
if(isset($_POST['accion']) && $_POST['accion']=='E')
{
	$parametro=array('id'=>$_POST['id_editar']);
		$sql="DELETE FROM 
			".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." 
			 WHERE
			id=?";
	if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametro))
		echo "<script>alert('Error al Eliminar el registro con id ".$_POST['id_editar']."')</script>";
	else
		echo "<script>alert('Registro Eliminado Correctamente')</script>";
		
}
else
if(isset($_POST['accion']) && $_POST['accion']=='A')
{
	if($tabla_rs=='dvm_referencia')
	{
		$parametro=array(
			'via'=>$_POST['via_'.$_POST['id_editar']],
			'abscisa'=>ucfirst($_POST['abscisa_'.$_POST['id_editar']]),
			'margen'=>$_POST['margen_'.$_POST['id_editar']],
			'referencia'=>$_POST['referencia_'.$_POST['id_editar']],
			'tramo_ruta'=>$_POST['tramo_ruta_'.$_POST['id_editar']],
			'adm'=>$_POST['adm_'.$_POST['id_editar']],
			'mun'=>$_POST['mun_'.$_POST['id_editar']],
			'velocidad_senalizacion'=>$_POST['velocidad_'.$_POST['id_editar']],
			'tipo_calzada'=>$_POST['tipo_calzada_'.$_POST['id_editar']],
			'id'=>$_POST['id_editar'],
		);
		
		$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." SET
			id_via=?,
			abscisa=?,
			margen=?,
			referencia=?,
			tramo_ruta=?,
			id_adm_vial_polca=?,
			id_municipio=?,
			velocidad_senalizacion=?,
			id_tipo_calzada=?
			 WHERE
			id=?"; 
	}
	else if($tabla_rs=='dvm_sentido')
	{
		$parametro=array('via'=>$_POST['via_'.$_POST['id_editar']],'descripcion'=>$_POST['descripcion_'.$_POST['id_editar']],'id'=>$_POST['id_editar']);
		$sql = "UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." 
				SET via=?, descripcion=? 
				WHERE id=?";
	}
	else if($tabla_rs=='dvm_constante')
	{
		$parametro=array('descripcion'=>$_POST['descripcion_'.$_POST['id_editar']],'valor'=>$_POST['valor_'.$_POST['id_editar']],'id'=>$_POST['id_editar']);
		$sql="UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." SET
			descripcion=?,
			valor=? WHERE
			id=?";
	}
	else if($tabla_rs=='dvm_tipo_atencion')
	{
		$finAuto = "NO";
		if( isset($_POST['finauto_'.$_POST['id_editar']]) and $_POST['finauto_'.$_POST['id_editar']]=="SI" )
			$finAuto = "SI";

		$parametro=array('nombre'=>$_POST['nombre_'.$_POST['id_editar']],'finauto'=>$finAuto,'id'=>$_POST['id_editar']);
		$sql = "UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." SET
				nombre=?, finauto=?
				WHERE id=?";
	}
	else
	{
		$parametro=array('nombre'=>$_POST['nombre_'.$_POST['id_editar']],'id'=>$_POST['id_editar']);
		$sql = "UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." 
				SET nombre=?
				WHERE id=?";
	}

	if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametro))
		echo "<script>alert('Error al Actualizar')</script>";
	else
		echo "<script>alert('Registro Actualizado Correctamente')</script>";
}
else
if(isset($_POST['accion']) && $_POST['accion']=='N')
{

	$max_id=$_SESSION[APL]->getSecuencia($tabla_rs,'id');

	if( $tabla_rs==='dvm_referencia')
	{
		$parametro=array(
			'id'=>$max_id,
			'via'=>$_POST['via_nuevo'],
			'abscisa'=>ucfirst($_POST['abscisa_nuevo']),
			'margen'=>$_POST['margen_nuevo'],
			'referencia'=>$_POST['referencia_nuevo'],
			'tramo_ruta'=>$_POST['tramo_ruta_nuevo'],
			'adm'=>$_POST['adm_nuevo'],
			'mun'=>$_POST['mun_nuevo'],
			'velocidad'=>$_POST['velocidad_nuevo'],
			'id_tipo_calzada'=>$_POST['tipo_calzada_nuevo'],
			'abscisa_numerica'=>null,
		);
		
		$sql="INSERT INTO  ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." VALUES (?,?,?,?,?,?,?,?,?,?,?)";
	}
	else if($tabla_rs==='dvm_sentido')
	{
		$parametro=array('id'=>$max_id,'via'=>$_POST['via_nuevo'],'descripcion'=>$_POST['descripcion_nuevo']);
		$sql="INSERT INTO  ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." VALUES
		(?,?,?)";
	}
	else if($tabla_rs==='dvm_constante')
	{
	
		$parametro=array('id'=>$max_id,'descripcion'=>$_POST['descripcion_nuevo'],'valor'=>$_POST['valor_nuevo']);
		$sql="INSERT INTO  ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." VALUES
		(?,?,?)";
	}
	else if($tabla_rs==='dvm_tipo_atencion')
	{
		$finAuto = "NO";
		if( isset($_POST['finauto_nuevo']) and $_POST['finauto_nuevo']=="SI" )
			$finAuto = "SI";

		$parametro=array('id'=>$max_id,'nombre'=>$_POST['nombre_nuevo'],'finauto'=>$finAuto);
		$sql="INSERT INTO  ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." VALUES
		(?,?,?)";
	}
	else
	{
		$parametro=array('id'=>$max_id,'nombre'=>$_POST['nombre_nuevo']);
		$sql="INSERT INTO  ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." VALUES
		(?,?)";
	}

	if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametro))
		echo "<script>alert('Error al Crear')</script>";
	else
		echo "<script>alert('Registro Creado Correctamente')</script>";
}
else
if(isset($_POST['accion']) && $_POST['accion']=='ACTMAS')
{
	if($tabla_rs=='dvm_referencia')
	{
		$viaActMas = $_POST['hdnvia_actmas'];
		$absIniActMas = $_POST['hdnabscini_actmas'];
		$absFinActMas = $_POST['hdnabscfin_actmas'];
		$admViaActMas = $_POST['hdnadm_actmas'];
		
		$sql = "update ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia
				set id_adm_vial_polca=$admViaActMas
				where id_via=$viaActMas and abscisa>='$absIniActMas' and abscisa<='$absFinActMas'";
		if(!$_SESSION[APL]->bd->ejecutar($sql))
			echo "<script>alert('Error al realizar la actualizacion masiva.')</script>";
		else
			echo "<script>alert('Se realizo la actualizacon masiva con exito.')</script>";
	}
}
?>
</head>
<LINK href="./calendar/calendar.css" type=text/css rel=STYLESHEET>
<script>
function eliminar(id)
{
	if(confirm('Esta seguro de eliminar el registro con id '+id+'?, esto podria generar inconsistencias en datos que dependan de esta tabla.'))
	{
		document.tabla.id_editar.value=id;
		document.tabla.accion.value='E';
		document.tabla.submit();
	}
}
<?php if($tabla_rs=='dvm_referencia')
{
?>
	function editar(id)
	{
		if(document.getElementById('via_'+id).value=='')
		{
			alert('Seleccione la via para el id '+id);
			document.getElementById('via_'+id).focus();
		}
		else
		if(document.getElementById('abscisa_'+id).value=='')
		{
			alert('Ingrese la Abscisa  para el id '+id);
			document.getElementById('abscisa_'+id).focus();
		}
		else
		if(document.getElementById('margen_'+id).value=='')
		{
			alert('Ingrese la Margen  para el id '+id);
			document.getElementById('margen_'+id).focus();
		}
		else
		if(document.getElementById('referencia_'+id).value=='')
		{
			alert('Ingrese la Referencia  para el id '+id);
			document.getElementById('referencia_'+id).focus();
		}
		else
		if(document.getElementById('tramo_ruta_'+id).value=='')
		{
			alert('Ingrese el Tramo de Ruta para el id '+id);
			document.getElementById('tramo_ruta_'+id).focus();
		}
		else
		if(document.getElementById('adm_'+id).value=='')
		{
			alert('Seleccione el Adm Vial Polca para el id '+id);
			document.getElementById('adm_'+id).focus();
		}
		else
		if(document.getElementById('mun_'+id).value=='')
		{
			alert('Seleccione el Municipio para el id '+id);
			document.getElementById('mun_'+id).focus();
		}
		else
		if(document.getElementById('velocidad_'+id).value=='')
		{
			alert('Seleccione la velocidad de señalización para el id '+id);
			document.getElementById('velocidad_'+id).focus();
		}
		else
		if(document.getElementById('tipo_calzada_'+id).value=='')
		{
			alert('Seleccione el tipo de calzada para el id '+id);
			document.getElementById('tipo_calzada_'+id).focus();
		}
		else
		{
			if( validarAbscisa('abscisa_'+id)==true )
			{
				document.tabla.id_editar.value=id;
				document.tabla.accion.value='A';
				document.tabla.submit();
			}
		}
	}
	
	function nuevo(id)
	{
		if(document.tabla.via_nuevo.value=='')
		{
			alert('Seleccione la via');
			document.tabla.via_nuevo.focus();
		}
		else
		if(document.tabla.abscisa_nuevo.value=='')
		{
			alert('Ingrese la Abscisa');
			document.tabla.abscisa_nuevo.focus();
		}
		else
		if(document.tabla.margen_nuevo.value=='')
		{
			alert('Ingrese el  Margen la via');
			document.tabla.margen_nuevo.focus();
		}
		else
		if(document.tabla.referencia_nuevo.value=='')
		{
			alert('Ingrese la  Referencia');
			document.tabla.referencia_nuevo.focus();
		}
		else
		if(document.tabla.tramo_ruta_nuevo.value=='')
		{
			alert('Ingrese el Tramo de Ruta');
			document.tabla.tramo_ruta_nuevo.focus();
		}
		else
		if(document.tabla.tramo_ruta_nuevo.value=='')
		{
			alert('Ingrese el Tramo de Ruta');
			document.tabla.tramo_ruta_nuevo.focus();
		}
		else
		if(document.tabla.adm_nuevo.value=='')
		{
			alert('Seleccione el Adm Vial Polca');
			document.tabla.adm_nuevo.focus();
		}
		else
		if(document.tabla.mun_nuevo.value=='')
		{
			alert('Seleccione el Municipio');
			document.tabla.mun_nuevo.focus();
		}
		else
		if(document.tabla.velocidad_nuevo.value=='')
		{
			alert('Seleccione la velocidad señalizada');
			document.tabla.velocidad_nuevo.focus();
		}
		else
		if(document.tabla.tipo_calzada_nuevo.value=='')
		{
			alert('Seleccione el tipo de calzada');
			document.tabla.tipo_calzada_nuevo.focus();
		}
		else
		{
			if( validarAbscisa('abscisa_nuevo')==true )
			{
				document.tabla.accion.value='N';
				document.tabla.submit();
			}
		}
	}

	function validarAbscisa(nomAbs)
	{
		var mensaje = "El formato correto para la abscisa es K###+###.\nEjemplo 1: K000+K010\nEjemplo 2: K080+K120";
		var ref = document.getElementById(nomAbs).value;
		var vKa = ref.charAt(0);
		var vMa = ref.charAt(4);
		var vKi = ref.substring(1,4);
		var vKf = ref.substring(5,8);
		
		// Validar el tamaño
		if( ref.length!=8 )
		{
			alert(mensaje);
			return false;
		}
		
		// Si es diferente a K
		if( vKa!="K" && vKa!="k" )
		{
			alert(mensaje);
			return false;
		}
		// Si es diferente a +
		if( vMa!="+" )
		{
			alert(mensaje);
			return false;
		}
		// Si Km ini no es numeros
		if( isNaN(vKi) )
		{
			alert(mensaje);
			return false;
		}
		// Si Km fin no es numeros
		if( isNaN(vKf) )
		{
			alert(mensaje);
			return false;
		}
		
		return true;
	}

	function abrirActMas()
	{
		$("#venActMas").dialog("open");
	}
	
	function grabarActMas()
	{
		// validar que hayan seleccionado via
		if( document.getElementById("via_actmas").value=="" )
		{
			alert("Seleccione la via a actualizar.");
			return;
		}
		
		// validar la abscisa inicial
		if( validarAbscisa("abscini_actmas")==false )
			return;
		
		// validar la abscisa final
		if( validarAbscisa("abscfin_actmas")==false )
			return;
		
		// validar que hayan seleccionado el administrador vial
		if( document.getElementById("adm_actmas").value=="" )
		{
			alert("Seleccione el administrador vial.");
			return;
		}
		
		document.getElementById("hdnvia_actmas").value = document.getElementById("via_actmas").value;
		document.getElementById("hdnabscini_actmas").value = document.getElementById("abscini_actmas").value;
		document.getElementById("hdnabscfin_actmas").value = document.getElementById("abscfin_actmas").value;
		document.getElementById("hdnadm_actmas").value = document.getElementById("adm_actmas").value;
		
		document.tabla.accion.value='ACTMAS';
		document.tabla.submit();
	}
	
	function cerrarActMas()
	{
		$("#venActMas").dialog("close");
	}

	vis_PonVentana("venActMas","Actualizacion Masiva",220,470);
	vis_PonBoton("btnGraActMas");
	vis_PonBoton("btnCanActMas");
<?php 
}// Vin tabla referencia
else
if($tabla_rs=='dvm_sentido')
{?>
function editar(id)
{
	if(document.getElementById('via_'+id).value=='')
	{
		alert('Seleccione la via para el id '+id);
		document.getElementById('via_'+id).focus();
	}
	else
	if(document.getElementById('descripcion_'+id).value=='')
	{
		alert('Ingrese la Descripcion del id '+id);
		document.getElementById('descripcion_'+id).focus();
	}
	else
	{
		document.tabla.id_editar.value=id;
		document.tabla.accion.value='A';
		document.tabla.submit();
	}
}
function nuevo(id)
{
	if(document.tabla.via_nuevo.value=='')
	{
		alert('Seleccione la via para el id '+id);
		document.tabla.via_nuevo.focus();
	}
	else
	if(document.tabla.descripcion_nuevo.value=='')
	{
		alert('Ingrese la Nueva Descripcion');
		document.tabla.descripcion_nuevo.focus();
	}
	else
	{
		document.tabla.accion.value='N';
		document.tabla.submit();
	}
}
<?php 
}// Fin sentido
else
if($tabla_rs=='dvm_constante')
{?>
function editar(id)
{
if(document.getElementById('descripcion_'+id).value=='')
	{
		alert('Ingrese la Descripcion del id '+id);
		document.getElementById('nombre_'+id).focus();
	}
	else
	if(document.getElementById('valor_'+id).value=='')
	{
		alert('Ingrese el Valor del id '+id);
		document.getElementById('valor_'+id).focus();
	}
	else
	{
	document.tabla.id_editar.value=id;
	document.tabla.accion.value='A';
	document.tabla.submit();
	}
}
function nuevo(id)
{

	if(document.tabla.descripcion_nuevo.value=='')
	{
		alert('Ingres la Nueva Descripcion');
		document.tabla.descripcion_nuevo.focus();
	}
	else
	if(document.tabla.valor_nuevo.value=='')
	{
		alert('Ingres el Nuevo Valor');
		document.tabla.valor_nuevo.focus();
	}
	else
	{
		document.tabla.accion.value='N';
		document.tabla.submit();
	}
}
<?php 
}// Fin constante
else

{?>
function editar(id)
{
if(document.getElementById('nombre_'+id).value=='')
	{
		alert('Ingres el Nombre del id '+id);
		document.getElementById('nombre_'+id).focus();
	}
	else
	{
	document.tabla.id_editar.value=id;
	document.tabla.accion.value='A';
	document.tabla.submit();
	}
}
function nuevo(id)
{

	if(document.tabla.nombre_nuevo.value=='')
	{
		alert('Ingres el Nuevo Nombre');
		document.tabla.nombre_nuevo.focus();
	}
	else
	{
		document.tabla.accion.value='N';
		document.tabla.submit();
	}
}
<?php 
}?>
window.parent.cambiarTitulo("<?php echo strtoupper(str_replace("_"," ",str_replace("dvm_","",$tabla_rs)))?>");
</script>

<body background="img/fondo.jpg" style="background-repeat:repeat-x">
<form name="tabla" method="post" action="editar_tabla.php" >
<center>
<table width="100%">

<tr>
<td align="center" colspan="2">

<?php 
if($tabla_rs=='dvm_referencia')
{
?>
	<table>
	<tr class="cab_grid">
	<th colspan="10">Registros</th></tr>
	<tr>

	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Id</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Via</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Abscisa</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Margen</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Referencia</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Tramo Ruta</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Adm Vial Polca</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Municipio</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Velocidad señalizada</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Tipo de calzada</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Accion</span></th>
	</tr>
	<?php 
	$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." ORDER BY id";
	$rs=$_SESSION[APL]->bd->getRs($sql);
	$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via ORDER by id";
	$rs_via=$_SESSION[APL]->bd->getRs($sql);
	$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_adm_vial_polca  ORDER by id";
	$rs_adm=$_SESSION[APL]->bd->getRs($sql);
	$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_municipio   ORDER by id";
	$rs_mun=$_SESSION[APL]->bd->getRs($sql);
	$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipos_calzadas ORDER by nombre";
	$rs_tipos_calzadas=$_SESSION[APL]->bd->getRs($sql);

	while (!$rs->EOF) {

	$rs_via->MoveFirst();
	$rs_adm->MoveFirst();
	$rs_mun->MoveFirst();
	$rs_tipos_calzadas->MoveFirst();

	?>
	   <tr  >
	   <td class="normalR"><?php echo $rs->fields[0]?></td>
	   <td >
	   <select name="via_<?php echo $rs->fields[0]?>" id="via_<?php echo $rs->fields[0]?>" class="campos">
	   <?php
	   while(!$rs_via->EOF)
	   {
			echo "<option value='".$rs_via->fields[0]."'";
			if($rs_via->fields[0]==$rs->fields[1])
				echo "selected";
			echo ">".$rs_via->fields[1]."</option>";
		   $rs_via->MoveNext();
	   }
	   ?>
	   </select>

	   </td>

	   <td ><input name="abscisa_<?php echo $rs->fields[0]?>" id="abscisa_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php echo $rs->fields[2]?>" size="50" /></td>
	   <td ><input name="margen_<?php echo $rs->fields[0]?>" id="margen_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php echo $rs->fields[3]?>" size="50" /></td>
	   <td ><input name="referencia_<?php echo $rs->fields[0]?>" id="referencia_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php echo $rs->fields[4]?>" size="50" /></td>
	   <td ><input name="tramo_ruta_<?php echo $rs->fields[0]?>" id="tramo_ruta_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php echo $rs->fields[5]?>" size="50" /></td>
	   <td >
	   <select name="adm_<?php echo $rs->fields[0]?>" id="adm_<?php echo $rs->fields[0]?>" class="campos">
	   <?php
	   while(!$rs_adm->EOF)
	   {
			echo "<option value='".$rs_adm->fields[0]."'";
			if($rs_adm->fields[0]==$rs->fields[6])
				echo "selected";
			echo ">".$rs_adm->fields[1]."</option>";
		   $rs_adm->MoveNext();
	   }
	   ?>
	   </select>

	   </td>
	   <td >
	   <select name="mun_<?php echo $rs->fields[0]?>" id="mun_<?php echo $rs->fields[0]?>" class="campos">
	   <?php
	   while(!$rs_mun->EOF)
	   {
			echo "<option value='".$rs_mun->fields[0]."'";
			if($rs_mun->fields[0]==$rs->fields[7])
				echo "selected";
			echo ">".$rs_mun->fields[1]."</option>";
		   $rs_mun->MoveNext();
	   }
	   ?>
	   </select>

	   </td>

	   	<!-- Velocidad señalizada -->
		<td>
			<input name="velocidad_<?php echo $rs->fields[0]?>" id="velocidad_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php  echo $rs->fields[8]?>" size="10" />
		</td>

	   	<!-- Tipo de calzada -->
		<td>
			<select name="tipo_calzada_<?php echo $rs->fields[0]?>" id="tipo_calzada_<?php echo $rs->fields[0]?>" class="campos">
				<option value=""></option>
			   <?php
			   while(!$rs_tipos_calzadas->EOF)
			   {
					echo "<option value='".$rs_tipos_calzadas->fields[0]."'";
					if($rs_tipos_calzadas->fields[0]==$rs->fields[9])
						echo "selected";
					echo ">".$rs_tipos_calzadas->fields[1]."</option>";
				   $rs_tipos_calzadas->MoveNext();
			   }
			   ?>
		   </select>
		</td>
	   	<td>
		  <?php 
	echo $_SESSION[APL]->getButtom('.','Modificar', '100', 'onclick="editar('.$rs->fields[0].')"');
	echo $_SESSION[APL]->getButtom('.','Eliminar', '100', 'onclick="eliminar('.$rs->fields[0].')"','','middlered');
	?>
	</td>



	   </tr>
	  <?php 
		$rs->MoveNext();

	}
	$rs->close();
	$rs_via->MoveFirst();
	$rs_adm->MoveFirst();
	$rs_mun->MoveFirst();
	$rs_tipos_calzadas->MoveFirst();
	?>
	<tr>
	<td class="normalR">Automatico</td>
	<td >
	   <select name="via_nuevo" class="campos">
	   <option value=""></option>
	   <?php
	   while(!$rs_via->EOF)
	   {
			echo "<option value='".$rs_via->fields[0]."'";
			echo ">".$rs_via->fields[1]."</option>";
		   $rs_via->MoveNext();
	   }
	   ?>
	   </select>

	   </td>

	   <td ><input name="abscisa_nuevo" id="abscisa_nuevo" type="text" class="campos" value="" size="50" /></td>
	   <td ><input name="margen_nuevo" type="text" class="campos" value="" size="50" /></td>
	   <td ><input name="referencia_nuevo" type="text" class="campos" value="" size="50" /></td>
	   <td ><input name="tramo_ruta_nuevo" type="text" class="campos" value="" size="50" /></td>
	   <td >
	   <select name="adm_nuevo" class="campos">
	   <option value=""></option>
	   <?php
	   while(!$rs_adm->EOF)
	   {
			echo "<option value='".$rs_adm->fields[0]."'";
			echo ">".$rs_adm->fields[1]."</option>";
		   $rs_adm->MoveNext();
	   }
	   ?>
	   </select>

	   </td>
	   <td >
	   <select name="mun_nuevo" class="campos">
	   <option value=""></option>
	   <?php
	   while(!$rs_mun->EOF)
	   {
			echo "<option value='".$rs_mun->fields[0]."'";
			echo ">".$rs_mun->fields[1]."</option>";
		   $rs_mun->MoveNext();
	   }
	   ?>
	   </select>

	   </td>
	   
	   	<!-- Velocidad señalizada -->
	   	<td>
			<input name="velocidad_nuevo" id="velocidad_nuevo" type="text" class="campos" size="10" />
		</td>

		<!-- Tipos de calzadas -->
	   	<td>
			<select name="tipo_calzada_nuevo" class="campos">
			   <option value=""></option>
			   <?php
			   while(!$rs_tipos_calzadas->EOF)
			   {
					echo "<option value='".$rs_tipos_calzadas->fields[0]."'";
					echo ">".$rs_tipos_calzadas->fields[1]."</option>";
				   $rs_tipos_calzadas->MoveNext();
			   }
			   ?>
		   </select>
		</td>

	<td class="style2">
	   <?php 
	echo $_SESSION[APL]->getButtom('.','Nuevo', '100', 'onclick="nuevo()"','','middlered');
	echo $_SESSION[APL]->getButtom('.','Act. Masiva', '120', 'onclick="abrirActMas()"','');
	?>
	</tr>
	</table>

	<div id="venActMas" style="display:none">
		<center>
			<table>
				<tr>
					<th class="normalR">Via:</th>
					<td>
						<select name="via_actmas" id="via_actmas" class="campos">
							<option value=""></option>
							<?php
							$rs_via->MoveFirst();
							while(!$rs_via->EOF)
							{
								 echo "<option value='".$rs_via->fields[0]."'";
								 echo ">".$rs_via->fields[1]."</option>";
								$rs_via->MoveNext();
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th class="normalR">Abscisa Inicial:</th>
					<td class="normalN"><input name="abscini_actmas" id="abscini_actmas" type="text" class="campos" value="" size="20"/>K###+###</td>
				</tr>
				<tr>
					<th class="normalR">Abscisa Final:</th>
					<td class="normalN"><input name="abscfin_actmas" id="abscfin_actmas" type="text" class="campos" value="" size="20"/>K###+###</td>
				</tr>
				<tr>
					<th class="normalR">Administrador Vial:</th>
					<td>
						<select name="adm_actmas" id="adm_actmas" class="campos">
							<option value=""></option>
							<?php
							$rs_adm->MoveFirst();
							while(!$rs_adm->EOF)
							{
								 echo "<option value='".$rs_adm->fields[0]."'";
								 echo ">".$rs_adm->fields[1]."</option>";
								$rs_adm->MoveNext();
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th colspan="2">
						<br/>
						<table>
							<tr>
								<td><?php echo $_SESSION[APL]->getButtom('.','Actualizar Masivamente', '200', 'onclick="grabarActMas()"',''); ?></td>
								<td><?php echo $_SESSION[APL]->getButtom('.','Cancelar', '200', 'onclick="cerrarActMas()"','','middlered'); ?></td>
							</tr>
						</table>
					</th>
				</tr>
			</table>
		</center>
	</div>
	
	<input type="hidden" id="hdnvia_actmas" name="hdnvia_actmas" value="">
	<input type="hidden" id="hdnabscini_actmas" name="hdnabscini_actmas" value="">
	<input type="hidden" id="hdnabscfin_actmas" name="hdnabscfin_actmas" value="">
	<input type="hidden" id="hdnadm_actmas" name="hdnadm_actmas" value="">
<?php
}// Fin referencia
else if($tabla_rs=='dvm_sentido')
{
?>
	<table>
	<tr class="cab_grid">
	<th colspan="4">Sentido</th></tr>
	<tr>

	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Id</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Via</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Descripcion</span></th>
	<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Accion</span></th>
	</tr>
	<?php 
	$sql="SELECT id,via,descripcion FROM ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." ORDER BY id";
	$rs=$_SESSION[APL]->bd->getRs($sql);
	
	$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via ORDER by id";
	$rs_via=$_SESSION[APL]->bd->getRs($sql);

	while (!$rs->EOF)
	{
		$rs_via->MoveFirst();
		?>
	   <tr  >
	   <td class="normalR"><?php echo $rs->fields[0]?></td>
	   <td class="style1">
		   <select name="via_<?php echo $rs->fields[0]?>" id="via_<?php echo $rs->fields[0]?>" class="campos">
			<?php
			while(!$rs_via->EOF)
			{
				 echo "<option value='".$rs_via->fields[0]."'";
				 if($rs_via->fields[0]==$rs->fields[1])
					 echo "selected";
				 echo ">".$rs_via->fields[1]."</option>";
				$rs_via->MoveNext();
			}
			?>
			</select>
	   </td>
	   <td class="style1"><input name="descripcion_<?php echo $rs->fields[0]?>" id="descripcion_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php echo $rs->fields[2]?>" size="25" maxlength="200" /></td>
	   <td class="style1">
	   <?php 
		echo $_SESSION[APL]->getButtom('.','Modificar', '100', 'onclick="editar('.$rs->fields[0].')"');
		echo $_SESSION[APL]->getButtom('.','Eliminar', '100', 'onclick="eliminar('.$rs->fields[0].')"','','middlered');
		?>
	   </tr>
		<?php 
		$rs->MoveNext();
	}// Fin while sentido
	$rs->close();

	$rs_via->MoveFirst();
	?>
	<tr>
		<td class="normalR">Automatico</td>
		<td class="style2">
			<select name="via_nuevo" class="campos">
				<option value=""></option>
				<?php
				while(!$rs_via->EOF)
				{
					 echo "<option value='".$rs_via->fields[0]."'";
					 echo ">".$rs_via->fields[1]."</option>";
					$rs_via->MoveNext();
				}
				?>
			</select>
		</td>
		<td class="style2"><input name="descripcion_nuevo" type="text" class="campos" value="" size="25" /></td>
		<td class="style2">
		<?php echo $_SESSION[APL]->getButtom('.','Nuevo', '100', 'onclick="nuevo()"','','middlered');?>
	</tr>


	</table>

<?php
}// Fin sentido
else if($tabla_rs=='dvm_constante')
{
?>
<table>
<tr class="cab_grid">
<th colspan="4"   >Constantes</th></tr>
<tr>

<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Id</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Descripcion</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Valor</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Accion</span></th>
</tr>
<?php 
$sql="SELECT id,descripcion,valor FROM ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." ORDER BY id";
$rs=$_SESSION[APL]->bd->getRs($sql);

while (!$rs->EOF) {

?>
   <tr  >
   <td class="normalR"><?php echo $rs->fields[0]?></td>
   <td class="style1"><input name="descripcion_<?php echo $rs->fields[0]?>" id="descripcion_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php echo $rs->fields[1]?>" size="25" maxlength="50" /></td>
   <td class="style1"><input name="valor_<?php echo $rs->fields[0]?>" id="valor_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php echo $rs->fields[2]?>" size="25" maxlength="100" /></td>
   
   <td class="style1">
   <?php 
echo $_SESSION[APL]->getButtom('.','Modificar', '100', 'onclick="editar('.$rs->fields[0].')"');
echo $_SESSION[APL]->getButtom('.','Eliminar', '100', 'onclick="eliminar('.$rs->fields[0].')"','','middlered');
?>
   
   
   
   </tr>
  <?php 
    $rs->MoveNext();

}
$rs->close();

?>
<tr>
<td class="normalR">Automatico</td>
<td class="style2"><input name="descripcion_nuevo" type="text" class="campos" value="" size="25" /></td>
<td class="style2"><input name="valor_nuevo" type="text" class="campos" value="" size="25" /></td>
<td class="style2">
   <?php 
echo $_SESSION[APL]->getButtom('.','Nuevo', '100', 'onclick="nuevo()"','','middlered');
?>
</tr>


</table>

<?php
}
else if($tabla_rs=='dvm_tipo_atencion')
{
?>
	<table>
	<tr class="cab_grid"><th colspan="4">Registros</th></tr>
	<tr>
		<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Id</span></th>
		<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Nombre</span></th>
		<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Fin. Automat.</span></th>
		<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Accion</span></th>
	</tr>
	<?php
		$sql="SELECT id,nombre,finauto FROM ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." ORDER BY id";
		$rs=$_SESSION[APL]->bd->getRs($sql);
	while (!$rs->EOF)
	{
		$vFinAuto = '';
		if( $rs->fields[2]=="SI" )
			$vFinAuto = 'checked';
	?>
	   <tr>
			<td class="normalR"><?php echo $rs->fields[0]?></td>
			<td class="style1"><input name="nombre_<?php echo $rs->fields[0]?>" id="nombre_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php echo $rs->fields[1]?>" size="50" maxlength="100" /></td>
			<td class="style1"><input name="finauto_<?php echo $rs->fields[0]?>" id="finauto_<?php echo $rs->fields[0]?>" type="checkbox" class="campos" value="SI" <?php echo $vFinAuto; ?>/></td>
			<td class="style1">
			<?php
				echo $_SESSION[APL]->getButtom('.','Modificar', '100', 'onclick="editar('.$rs->fields[0].')"');
				echo $_SESSION[APL]->getButtom('.','Eliminar', '100', 'onclick="eliminar('.$rs->fields[0].')"','','middlered');
			?>
	   </tr>
	   <?php
		$rs->MoveNext();
	}
	$rs->close();
	?>
	<tr>
	<td class="normalR">Automatico</td>
	<td class="style2"><input name="nombre_nuevo"  type="text" class="campos" value="" size="50" /></td>
	<td class="style2"><input name="finauto_nuevo" type="checkbox" class="campos" value="SI" size="50"/></td>
	<td class="style2">
	   <?php
	echo $_SESSION[APL]->getButtom('.','Nuevo', '100', 'onclick="nuevo()"','','middlered');
	?>
	</tr>


	</table>
<?php
}
else
{
?>
<table>
<tr class="cab_grid">
<th colspan="3"   >Registros</th></tr>
<tr>

<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Id</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Nombre</span></th>
<th bgcolor="#CCCCCC" class="LegendSt"><span class="style1">Accion</span></th>
</tr>
<?php 
$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".".$tabla_rs." ORDER BY id";
$rs=$_SESSION[APL]->bd->getRs($sql);

while (!$rs->EOF) {

?>
   <tr  >
   <td class="normalR"><?php echo $rs->fields[0]?></td>
   <td class="style1"><input name="nombre_<?php echo $rs->fields[0]?>" id="nombre_<?php echo $rs->fields[0]?>" type="text" class="campos" value="<?php echo $rs->fields[1]?>" size="50" maxlength="100" /></td>
   <td class="style1">
   <?php 
echo $_SESSION[APL]->getButtom('.','Modificar', '100', 'onclick="editar('.$rs->fields[0].')"');
echo $_SESSION[APL]->getButtom('.','Eliminar', '100', 'onclick="eliminar('.$rs->fields[0].')"','','middlered');
?>
   
   
   
   </tr>
  <?php 
    $rs->MoveNext();

}
$rs->close();

?>
<tr>
<td class="normalR">Automatico</td>
<td class="style2"><input name="nombre_nuevo" type="text" class="campos" value="" size="50" /></td>
<td class="style2">
   <?php 
echo $_SESSION[APL]->getButtom('.','Nuevo', '100', 'onclick="nuevo()"','','middlered');
?>
</tr>


</table>
<?php
}

?>
</td></tr></table>

</center>
<input type="hidden" name="id_editar" value="" />
<input type="hidden" name="accion" value="" />
<input type="hidden" name="tabla" value="<?php echo $tabla_rs?>" />
</form>
</body>
</html>
