<?php

include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}

/*if($_SESSION[APL]->proveedor == null){
	$_SESSION[APL]->proveedor = getObjpProveedor($_SESSION[APL]->usuario->nit);
}*/

if(isset($_GET["msg"]))
	echo $_SESSION[APL]->msg($_GET["msg"]);

echo $_SESSION[APL]->cabeceras();
echo $_SESSION[APL]->interfas->pestana(0);

//echo $_SESSION[APL]->interfas->lanzador_pendientes(0);
//echo $_SESSION[APL]->orden->entrada_pendientes();

?>

<script>
<?php if($_SESSION[APL]->usuario->fecha_clave=='')
echo "alert('Esta es la primera vez que ingresa al sistema, debe cambiar la clave')";
else
echo "alert('La contraseña actual ha superado su tiempo de vigencia, debe cambiarla')";
?>

</script>
<center>

<table border="0" width="400px" style="border: 1 solid; border-color:#0070B3" class="normalAC">
<form action="" name="frm" method="post">
<input type="hidden"  name="nit"  value="<?php echo $_SESSION[APL]->usuario->id ?>" > 

<tr>
		<th colspan="2" bgcolor="#0070B3" align="center">
			<font class="tituloGC">Actualizar Contraseña</font>
		</th>
	</tr>
<tr>
		<td width="30%">
			Contraseña Anterior
		</td>
		<td>
			<input type="hidden" class="campos2" name="clave_old1" size="8" maxlength="5" value="<?php echo $_SESSION[APL]->usuario->clave?>" onKeyPress="return solonumeros(event,1,this,5)">
			<input type="password" class="campos2" name="clave_old2" size="8" maxlength="5" value="">
		</td>
	</tr>
	<tr>
		<td width="30%">
			Nueva Contraseña *
		</td>
		<td>
			<input type="password" class="campos2" name="clave_new1" size="8" maxlength="5" value="">
		</td>
	</tr>
	<tr>
		<td width="30%">
			Repita Nueva Contraseña *
		</td>
		<td>
			<input type="password" class="campos2" name="clave_new2" size="8" maxlength="5" value="">
		</td>
	</tr>
	<tr >
		<td colspan="2" align="right">
			<input type="button" class="botones" value="Enviar Actualización de Clave" onclick="enviar()" />&nbsp;
		</td>
	</tr>
</form>
</table>
</center>
<script>
function validar()
{
	if(document.frm.clave_old2.value == '')
		{
			alert('Debe ingresar la contraseña anterior');
			document.frm.clave_old2.focus();
			return false;
		}
		if(document.frm.clave_new1.value == '')
		{
			alert('Debe ingresar la nueva contraseña');
			document.frm.clave_new1.focus();
			return false;
		}
		if(document.frm.clave_new2.value == '')
		{
			alert('Debe repetir la nueva contraseña');
			document.frm.clave_new2.focus();
			return false;
		}
		
		
		if(document.frm.clave_old1.value != document.frm.clave_old2.value)
		{
			alert('La contraseña anterior digitada no corresponde con la actual');
			document.frm.clave_old2.focus();
			return false;
		}
		if(document.frm.clave_new1.value != document.frm.clave_new2.value)
		{
			alert('La nueva contraseña no corresponde con la contraseña que se digito en el campo de Repetir Nueva Contraseña');
			document.frm.clave_new1.focus();
			return false;
		}
		if(document.frm.clave_new2.value == document.frm.clave_old2.value)
		{
			alert('La nueva contraseña debe ser diferente a la anterior');
			document.frm.clave_new2.focus();
			return false;
		}
		return true;
}

function enviar(){		
		if(validar()){
			document.frm.action = 'guardar_clave_proveedor.php';
			document.frm.submit();
		}
	}
</script>
</body>
</html>