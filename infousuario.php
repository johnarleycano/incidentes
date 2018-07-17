<?php
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}


if(isset($_GET["msg"]))
	echo $_SESSION[APL]->msg($_GET["msg"]);

echo $_SESSION[APL]->cabeceras();
$_SESSION[APL]->pagina_menu='infousuario.php';
echo $_SESSION[APL]->interfas->pestana(1);


if(isset($_POST['nombres']) && isset($_POST['apellidos']))
{

	

	$sql="UPDATE
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
		SET
		nombres='".$_POST['nombres']."',
		apellidos='".$_POST['apellidos']."',
		celular='".$_POST['celular']."',
		cedula='".$_POST['cedula']."',
		correo='".$_POST['correo']."'";
	if($_FILES['firma']['name']!='')
	{	
		$extension=end(explode(".", $_FILES['firma']['name']));
		$sql.=",firma='firma_".$_SESSION[APL]->usuario->id.".".$extension."'";
	}
	
	if(($_POST['clave_new1']!='' && $_POST['clave_new2']!='') && $_POST['clave_new1']==$_POST['clave_new2'])
	$sql.=",fecha_clave='".date('Y-m-d')."',
			clave='".$_POST['clave_new1']."'";
	$sql.="WHERE id=".$_SESSION[APL]->usuario->id."";
	if(!$_SESSION[APL]->bd->ejecutar($sql))
		echo "<script>alert('Error al Actualizar Usuario')</script>";
	else
	{
		if($_FILES['firma']['name']!='')
			{
				$extension=end(explode(".", $_FILES['firma']['name']));
				if (!move_uploaded_file($_FILES['firma']['tmp_name'],'firmas/firma_'.$_SESSION[APL]->usuario->id.".".$extension))
						echo "<script>alert('Error al Cargar Firma')</script>";	
			}
	
		$_SESSION[APL]->usuario->validar_usuario_reg($_SESSION[APL]->usuario->id);
		echo "<script>alert('Usuario Actualizado')</script>";
	}
}


?>



<script>
<?php if($_SESSION[APL]->usuario->fecha_clave=='')
echo "alert('Esta es la primera vez que ingresa al sistema, debe cambiar la clave');
";
else
{
$sql="SELECT round((".date('Y-m-d')."-fecha_clave))
		from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
		where id=".$_SESSION[APL]->usuario->id."";
		
		$rs=$_SESSION[APL]->bd->getRs($sql);
		$dias=$rs->fields[0];
		if($dias==-1 || $dias>1000)
			echo "alert('La contraseña actual ha superado su tiempo de vigencia de 1000 dias, debe cambiarla ');
			";
}
?>
function guardar()
{
	if(document.infousuario.nombres.value=='')
	{
		alert('Ingrese el o los nombre');
		document.infousuario.nombres.focus();
	}
	else
	if(document.infousuario.apellidos.value=='')
	{
		alert('Ingrese el o los apellidos');
		document.infousuario.apellidos.focus();
	}
	else
	if(document.infousuario.correo.value=='')
	{
		alert('Ingrese el correo');
		document.infousuario.correo.focus();
	}
	else
	if(document.infousuario.clave_old2.value=='')
	{
		alert('Ingrese la clave anterior para guardar los cambios');
		document.infousuario.clave_old2.focus();
	}
	else
	if(document.infousuario.clave_old2.value!=document.infousuario.clave_old1.value)
	{
		alert('La contraseña actual digitada no corresponde con la almacenada');
		document.infousuario.clave_old2.focus();
	}
	else
	if(
	(document.infousuario.clave_new1.value!='' && document.infousuario.clave_new2.value!='')
		&& document.infousuario.clave_new1.value!=document.infousuario.clave_new2.value)
	{
		alert('La nueva contraseña no corresponde con la segunda digitada');
		document.infousuario.clave_new2.focus();
	}
	else
	if(
	(document.infousuario.clave_new1.value!='' && document.infousuario.clave_new2.value!='')
		&& document.infousuario.clave_new1.value==document.infousuario.clave_old1.value)
	{
		alert('La nueva contraseña no puede ser igual a la actual');
		document.infousuario.clave_new2.focus();
	}
	else
	{
		espere.style.display="";
		document.infousuario.submit();
	}
}
</script>
<form name="infousuario" action="infousuario.php" method="post" enctype="multipart/form-data">
<center>
<table  cellspacing='1' cellpadding='3' border='0' >
<tr><th colspan="2" class="venTitulo"  height="30">Editar Informacion Usuario</th></tr>
<tr>
<th class="LegendSt">Login</th>
<td><?php echo $_SESSION[APL]->usuario->login?></td>
</tr>
<tr>
<th class="LegendSt">Fecha Creacion</th>
<td ><?php echo $_SESSION[APL]->usuario->fecha_creacion?></td>
</tr>
<tr>
<th class="LegendSt">Estado</th>
<td><?php 
if($_SESSION[APL]->usuario->estado=='A')
echo "Activo";
else
echo "Inactivo";
?>
</tr>
<tr>
<th class="LegendSt">Perfil</th>
<td ><?php 
if($_SESSION[APL]->usuario->id_perfil==0)
	echo "Administrador";
elseif($_SESSION[APL]->usuario->id_perfil==1)
	echo "Generador Basico Incidentes";
elseif($_SESSION[APL]->usuario->id_perfil==2)
	echo "Funcionario SOS";
elseif($_SESSION[APL]->usuario->id_perfil==3)
	echo "Admninistrador Vial";


?></td>
</tr>
<tr>
<th class="LegendSt">Cedula</th>
<td ><input type="text" name="cedula" value="<?php echo $_SESSION[APL]->usuario->cedula?>" class="campos"/></td>
</tr>

<tr>
<th class="LegendSt">Nombres</th>
<td ><input type="text" name="nombres" value="<?php echo $_SESSION[APL]->usuario->nombres?>" class="campos"/></td>
</tr>
<tr>
<th class="LegendSt">Apellidos</th>
<td ><input type="text" name="apellidos" value="<?php echo $_SESSION[APL]->usuario->apellidos?>" class="campos"/></td>
</tr>
<tr>
<th class="LegendSt">Correo</th>
<td ><input type="text" name="correo" value="<?php echo $_SESSION[APL]->usuario->correo?>" class="campos"/></td>
</tr>
<tr>
<th class="LegendSt">Celular</th>
<td ><input type="text" name="celular" value="<?php echo $_SESSION[APL]->usuario->celular?>" class="campos"/></td>
</tr>
<tr>
<th class="LegendSt">Firma</th>
<td >
<input type="file" name="firma" /><br />

<?php
	if($_SESSION[APL]->usuario->firma!='')
		echo "<img src='firmas/".$_SESSION[APL]->usuario->firma."' width='220'>";
	else
		echo "Sin Firma";
?>
 </td>
</tr>

<tr>
<th class="LegendSt">Contraseña Actual</th>
<td >
<input type="hidden" name="clave_old1" value="<?php echo $_SESSION[APL]->usuario->clave?>" />
<input type="password" name="clave_old2" value="" class="campos"/></td>
</tr>
<tr>
<th class="LegendSt">Fecha Ultimo Cambio Clave</th>
<td ><?php echo $_SESSION[APL]->usuario->fecha_clave?></td>
</tr>

<tr>
<th class="LegendSt">Nueva Contraseña</th>
<td ><input type="password" name="clave_new1" value="" class="campos"/></td>
</tr>
<tr>
<th class="LegendSt">Repetir Nueva Contraseña</th>
<td ><input type="password" name="clave_new2" value="" class="campos"/></td>
</tr>
<tr>
<th colspan="2" class="LegendSt" align="center">
<?php 
echo $_SESSION[APL]->getButtom('.','Guardar', '100', 'onclick="guardar()"');
?>
</th>
</tr>
</table>
</center>


</body>
</html>