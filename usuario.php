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

if(isset($_POST['nombres']) && isset($_POST['apellidos']) && isset($_POST['id']) && $_POST['id']!=-1)
{
	if($_POST['accion']=='editar')
	{
	
		$sql="UPDATE
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
			SET
			login='".$_POST['login']."',
			estado='".$_POST['estado']."',
			id_perfil=".$_POST['id_perfil'].",
			celular='".$_POST['celular']."',
			nombres='".$_POST['nombres']."',
			apellidos='".$_POST['apellidos']."',
			correo='".$_POST['correo']."'";
		if($_POST['clave']!=$_POST['clave_old'])	
			$sql.=",fecha_clave='0000-00-00',
			clave='".$_POST['clave']."'";
		
		if(isset($_POST['enviar_correo']))	
			$sql.=",enviar_correo='SI'";
		else
			$sql.=",enviar_correo='NO'";

		if($_FILES['firma']['name']!='')
		{	
			$extension=end(explode(".", $_FILES['firma']['name']));
			$sql.=",firma='firma_".$_POST['id'].".".$extension."'";
		}	
				
			
		$sql.="
			WHERE id=".$_POST['id']."";
		if(!$_SESSION[APL]->bd->ejecutar($sql))
			echo "<script>alert('Error al Actualizar Usuario con login ".$_POST['login']."')</script>";
		else
		{
			if($_FILES['firma']['name']!='')
			{
				$extension=end(explode(".", $_FILES['firma']['name']));
				if (!move_uploaded_file($_FILES['firma']['tmp_name'],'firmas/firma_'.$_POST['id'].".".$extension))
						echo "<script>alert('Error al Cargar Firma')</script>";	
			}
			
			
			$_SESSION[APL]->usuario->validar_usuario_reg($_SESSION[APL]->usuario->id);
			echo "<script>alert('Usuario Actualizado con login ".$_POST['login']."')</script>";
		}
	}
	else
		{
		$sql="DELETE FROM
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios WHERE id=".$_POST['id']."";
		if(!$_SESSION[APL]->bd->ejecutar($sql))
			echo "<script>alert('Error al Eliminar Usuario con login ".$_POST['login']."')</script>";
		else
		{
			$_SESSION[APL]->usuario->validar_usuario_reg($_SESSION[APL]->usuario->id);
			echo "<script>alert('Usuario Eliminado con login ".$_POST['login']."')</script>";
		}
	}
}
else if(isset($_POST['nombres']) && isset($_POST['apellidos']) && isset($_POST['id']) && $_POST['id']==-1)
{
	$sql="SELECT COUNT(*) FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios WHERE login='".$_POST['login']."'";
	$yaesta=$_SESSION[APL]->bd->dato($sql);
	if($yaesta==0)
	{
		if(!isset($_POST['id_impresion']) || $_POST['id_impresion']=='')
			$id_impresion2=0;
		else
			$id_impresion2=$_POST['id_impresion'];

		if(isset($_POST['enviar_correo']))
			$envi_c='SI';
		else
			$envi_c='NO';
		$sql="INSERT INTO 
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
			(login,estado,id_perfil,celular,nombres,apellidos,fecha_creacion,fecha_clave,clave,correo,enviar_correo)
			VALUES
			('".$_POST['login']."',
			'".$_POST['estado']."',
			".$_POST['id_perfil'].",
			'".$_POST['celular']."',
			'".$_POST['nombres']."',
			'".$_POST['apellidos']."',
			'".date('Y-m-d')."',
			'0000-00-00',
			'".$_POST['clave']."',
			'".$_POST['correo']."',
			'".$envi_c."');";
		if(!$_SESSION[APL]->bd->ejecutar($sql))
			echo "<script>alert('Error al Crear Usuario con login ".$_POST['login']."')</script>";
		else
		{
			
			if($_FILES['firma']['name']!='')
			{	
				$sql="SELECT max(id) FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios";
				$id_new=$_SESSION[APL]->bd->dato($sql);
				$extension=end(explode(".", $_FILES['firma']['name']));
				
				
				$sql="UPDATE
				".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
				SET
				firma='firma_".$id_new.".".$extension."'
				WHERE
				id=".$id_new."";
				if(!$_SESSION[APL]->bd->ejecutar($sql))
					echo "<script>alert('Error al Cargar Firma con login ".$_POST['login']."')</script>";
				else
				if (!move_uploaded_file($_FILES['firma']['tmp_name'],'firmas/firma_'.$id_new.".".$extension))
						echo "<script>alert('Error al Cargar Firma')</script>";	
		
			
				
			}
			
			
			
			
			
			
		
			$_SESSION[APL]->usuario->validar_usuario_reg($_SESSION[APL]->usuario->id);
			echo "<script>alert('Usuario Creado con el login ".$_POST['login']."')</script>";
		}
	}
	else
		echo "<script>alert('Ya existe un usuario con el login ".$_POST['login']."')</script>";
}
?>

<script>
function editar_tabla(tabla)
{
	var h=430;
	var w=720
	var iz=(screen.width/2)-(w/2);
    var de=(screen.height/2)-(h/2);
	window.open("editar_tabla.php?tabla="+tabla,"","width="+w+",height="+h+",left="+iz+",top="+de+", scrollbars=yes,menubars=no,statusbar=yes, status=yes, resizable=NO,location=NO")
}
function guardar(forma)
{
	if(forma.login.value=='')
	{
		alert('Ingrese el login');
		forma.nombres.focus();
	}
	else
	if(forma.estado.value=='')
	{
		alert('Ingrese el estado');
		forma.estado.focus();
	}
	else
	if(forma.id_perfil.value=='')
	{
		alert('Ingrese el perfil');
		forma.id_perfil.focus();
	}
	else
	
	if(forma.cedula.value=='')
	{
		alert('Ingrese la cedula');
		forma.cedula.focus();
	}
	else
	if(forma.nombres.value=='')
	{
		alert('Ingrese el o los nombre');
		forma.nombres.focus();
	}
	else
	if(forma.apellidos.value=='')
	{
		alert('Ingrese el o los apellidos');
		forma.apellidos.focus();
	}
	else
	if(forma.clave.value=='')
	{
		alert('Ingrese la clave');
		forma.clave.focus();
	}
	else
	if(forma.clave.value!=forma.clave_rep.value)
	{
		alert('La clave en el campo para repetir clave es diferente a la nueva');
		forma.clave.focus();
	}
	else
	{
		espere.style.display="";
		forma.submit();
	}
}
function eliminar(forma)
{
	if(confirm('Esta seguro de eliminar el usario con login '+forma.login.value))
	{
		espere.style.display="";
		forma.accion.value='eliminar';
		forma.submit();
	}
}

window.parent.cambiarTitulo("USUARIOS Y PERFILES");
</script>
<br />
<center>
<table  cellspacing='0'   cellpadding='3'  id="usuarios">
<thead>
<tr class="cab_grid"><th colspan="10"  >Editar Informacion de Usuarios y Perfiles</th></tr>
<tr><th colspan="10" height="10">&nbsp;</th></tr>
<tr>
<th class="LegendSt">Login</th>
<th class="LegendSt">Fecha Creacion</th>
<th class="LegendSt">Estado</th>
<th class="LegendSt">Cedula/Perfil</th>
<th class="LegendSt">Nombres / Apellidos</th>
<th class="LegendSt">Correo/Celular</th>
<th class="LegendSt">Contraseña Actual / Fecha Cambio</th>
<th class="LegendSt">Firma</th>
<th class="LegendSt">Enviar<br>Correo</th>
<th class="LegendSt">&nbsp;</th>
</tr>
</thead>
<tbody>
<?php
$sql="SELECT id,login,estado,id_perfil,celular,nombres,apellidos,clave,fecha_clave,correo,firma,cedula,enviar_correo FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios";
$rs=$_SESSION[APL]->bd->getRs($sql);
while(!$rs->EOF)
{
?>

<tr  height="30px">
<form name="usuario_<?php echo $rs->fields[0]?>" action="usuario.php" method="post" enctype="multipart/form-data">
<td >
<input class="campos" type="text" name="login" value="<?php echo $rs->fields('login')?>" size="8"/>
</td>
<td >
<?php echo $_SESSION[APL]->usuario->fecha_creacion?></td>
<td >
<select name="estado" class="campos">
<option value="A" <?php if($rs->fields('estado')=='A') echo "selected"?>>Activo</option>
<option value="I" <?php if($rs->fields('estado')=='I') echo "selected"?>>Inactivo</option>
</select>
</td>
<td >
<input class="campos" type="text" name="cedula" value="<?php echo $rs->fields('cedula')?>" maxlength="20"/>
<hr />
<select name="id_perfil" class="campos">
<option value="0" <?php if($rs->fields('id_perfil')==0) echo "selected"?>>Administrador</option>
<option value="1" <?php if($rs->fields('id_perfil')==1) echo "selected"?>>Generador Basico Incidentes</option>
<option value="2" <?php if($rs->fields('id_perfil')==2) echo "selected"?>>Funcionario SOS</option>
<option value="3" <?php if($rs->fields('id_perfil')==3) echo "selected"?>>Admninistrador Vial</option>
</select>
</td>
<td >
<input class="campos" type="text" name="nombres" value="<?php echo $rs->fields('nombres')?>" maxlength="100"/>
<hr />
<input class="campos" type="text" name="apellidos" value="<?php echo $rs->fields('apellidos')?>" maxlength="100"/></td>
<td  align="center">
<input class="campos" type="text" name="correo" value="<?php echo $rs->fields('correo')?>" maxlength="100"/>
<hr />
<input class="campos" type="text" name="celular" value="<?php echo $rs->fields('celular')?>" maxlength="50"/>
</td>

<td >
<input class="campos" type="password" name="clave" value="<?php echo $rs->fields('clave')?>" maxlength="15" size="15"/>
<input class="campos" type="password" name="clave_rep" value="<?php echo $rs->fields('clave')?>" maxlength="15" size="15"/>
<input class="campos" type="hidden" name="clave_old" value="<?php echo $rs->fields('clave')?>"/>
<hr />
<?php echo $rs->fields('fecha_clave')?></td>
<td>
<input type="file" name="firma" /><br />

<?php
	if($rs->fields('firma')!='')
		echo "<img src='firmas/".$rs->fields('firma')."' width='220'>";
	else
		echo "Sin Firma";
?>
</td>
<td>
<input type='checkbox' value='SI' name='enviar_correo' <?php if($rs->fields('enviar_correo')=='SI') echo "checked"?>>
</td>


<th colspan="2"  >
<?php 
echo $_SESSION[APL]->getButtom('.','Modificar', '100', 'onclick="guardar(document.usuario_'.$rs->fields[0].')"');
echo $_SESSION[APL]->getButtom('.','Eliminar', '100', 'onclick="eliminar(document.usuario_'.$rs->fields[0].')"','','middlered');

?>

</th>
<input type="hidden" name="id" value="<?php echo $rs->fields('id')?>" />
<input type="hidden" name="accion" value="editar" />
</form>
</tr>
<tr><th colspan="10">&nbsp;</th></tr>
<?php
$rs->MoveNext();
}
?>
</tbody>
<tfoot>
<tr><th colspan="10" height="20px">&nbsp;</th></tr>

<tr class="cab_grid">
<th colspan="10"   >Nuevo Usuario</th></tr>
<tr>
<tr><th colspan="10" height="20px">&nbsp;</th></tr>
<th class="LegendSt">Login</th>
<th class="LegendSt">Fecha Creacion</th>
<th class="LegendSt">Estado</th>
<th class="LegendSt">Cedula/Perfil</th>

<th class="LegendSt">Nombres / Apellidos</th>
<th class="LegendSt">Correo/Celular</th>
<th class="LegendSt">Contraseña Actual / Fecha Cambio</th>
<th class="LegendSt">Firma</th>
<th class="LegendSt">Enviar<br>Correo</th>
<th class="LegendSt">&nbsp;</th>
</tr>

<tr  height="30px">
<form name="usuario" action="usuario.php" method="post" enctype="multipart/form-data">
<td >
<input class="campos" type="text" name="login" value="" size="8"/>
</td>
<td >
N/A
<td >
<select name="estado" class="campos">
<option value="A">Activo</option>
<option value="I">Inactivo</option>
</select>
</td>
<td >
<input class="campos" type="text" name="cedula"  maxlength="20"/>
<hr />
<select name="id_perfil" class="campos">
<option value="0" >Administrador</option>
<option value="1" >Generador Basico Incidentes</option>
<option value="2" >Funcionario SOS</option>
<option value="3" >Admninistrador Vial</option>
</select>
</td>
<td >
<input class="campos" type="text" name="nombres" value="" maxlength="100"/>
<hr />
<input class="campos" type="text" name="apellidos" value="" maxlength="100"/></td>
<td >
<input class="campos" type="text" name="correo" value="" maxlength="100"/><br />
<input class="campos" type="text" name="celular" value="" maxlength="50"/>

</td>

<td >
<input class="campos" type="password" name="clave" value="" maxlength="15" size="15"/><br />
<input class="campos" type="password" name="clave_rep" value="" maxlength="15" size="15"/>
<hr />N/A</td>
<td>
<input type="file" name="firma" />
</td>

<td>
<input type='checkbox' value='SI' name='enviar_correo' <?php if($rs->fields('enviar_correo')=='SI') echo "checked"?>>
</td>


<th colspan="2"  >
<?php
echo $_SESSION[APL]->getButtom('.','Nuevo', '100', 'onclick="guardar(document.usuario)"');
?>



<!--<input type="button" value="Crear"  onclick="guardar(this.form)" <?php //echo $css_font?>/>--></th>
<input type="hidden" name="id" value="-1" />
<input type="hidden" name="accion" value="crear" />
</form>

</tr>
</tfoot>
</table>
</center>
</body>
</html>