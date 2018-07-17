<?php
include_once "../clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" )
	header("location:../entrada_usuario.php");

if(isset($_GET["msg"]))
	echo $_SESSION[APL]->msg($_GET["msg"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="../libs/jq/jquery.min.js"></script>
	
	<script type="text/javascript" src="../libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
	<link type="text/css" href="../libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
	
	<!-- JQGrid -->
	<link type="text/css" rel="stylesheet" media="screen" href="../libs/jq/jqGrid/src/css/ui.jqgrid.css">
	<link type="text/css" rel="stylesheet" media="screen" href="../libs/jq/jqGrid/src/css/ui.multiselect.css">
	<script type="text/javascript" src="../libs/jq/jqGrid/js/jquery.jqGrid.min.js"></script>
	<script type="text/javascript" src="../libs/jq/jqGrid/js/i18n/grid.locale-es.js"></script>
	<script type="text/javascript" src="../libs/jq/jqGrid/plugins/ui.multiselect.js"></script>
	<script type="text/javascript" src="../libs/jq/jqGrid/plugins/jquery.tablednd.js"></script>
	<script type="text/javascript" src="../libs/jq/jqGrid/plugins/jquery.contextmenu.js"></script>
	
	<link href="../css/ventana.css" rel="stylesheet" type="text/css">	
	<link href="../css/campo.css" rel="stylesheet" type="text/css">
	<link href="../css/tabla.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="'.$ruta.'../imagenes/logo.ico" type="image/x-icon">
	
	<!-- Cargar Archivos -->
	<script type="text/javascript" src="../libs/jq/ajaxupload/ajaxfileupload.js"></script>
		
	<script type="text/javascript" src="../libs/js/vista.js"></script>
	<script type="text/javascript" src="usuario.js"></script>

	<style>
		.myAltRowClass{ background-color:#DDFFDD;background-image:none; }
		.ui-widget-header
		{
			background-color: #CDD2CD;
			background-image: none;
			color: #000;
		}
		.punt { cursor:pointer; }
		th.ui-th-column div
		{
			white-space:normal !important;
			height:auto !important;
			padding:2px;
		}
		#estado
		{
			width:200px !important;
		}
	</style>
</head>
<body onLoad="" topmargin="0">
	<center>
		<table id="griUsu"></table>
		<div id="pagUsu"></div>
		<input type="button" class="vbotones" value="Nuevo" onclick="Editar(-1)">
		<input type="button" class="vbotones" value="Cerrar" onclick="CerrarIframe()">
	</center>
	
	<div id="venEdiUsu" style="display:none">
		<center>
			<form id="frmUsu" name="frmUsu">
				<input type="hidden" id="id" name="id" value="">
				<table width="100%" class="tabEdi" cellpadding="3" border="0">
					<tr>
						<th class="resaltar">Login</th>
						<td><input type="text" id="login" name="login" value="" class="campos"/></td>
						<th class="resaltar">Cedula</th>
						<td><input type="text" id="cedula" name="cedula" value="" class="campos"/></td>
					</tr>
					<tr>
						<th class="resaltar">Nombre(s)</th>
						<td><input type="text" id="nombre" name="nombre" value="" class="campos"/></td>
						<th class="resaltar">Apellido(s)</th>
						<td><input type="text" id="apellido" name="apellido" value="" class="campos"/></td>
					</tr>
					<tr>
						<th class="resaltar">Correo</th>
						<td><input type="text" id="correo" name="correo" value="" class="campos"/></td>
						<th class="resaltar">Celular</th>
						<td><input type="text" id="celular" name="celular" value="" class="campos"/></td>
					</tr>
					<tr>
						<th class="resaltar">Perfil</th>
						<td>
							<select id="perfil" name="perfil" class="campos">
								<option value="0" >Administrador</option>
								<option value="1" >Generador Basico Incidentes</option>
								<option value="2" >Funcionario SOS</option>
								<option value="3" >Admninistrador Vial</option>
							</select>
						</td>
						<th class="resaltar">Estado</th>
						<td>
							<select id="estado" name="estado" class="campos">
								<option value="A">Activo</option>
								<option value="I">Inactivo</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="resaltar">Enviar Correo</th>
						<td>
							<select id="enviar" name="enviar" class="campos">
								<option value="SI">SI</option>
								<option value="NO">NO</option>
							</select>
						</td>
						<th class="resaltar">Fecha Creacion</th>
						<td><input type="text" id="fechacre" name="fechacre" value="" class="campos" readonly="true"/></td>
					</tr>
					<tr>
						<th class="resaltar">Clave</th>
						<td>
							<input type="hidden" id="clave_old" name="clave_old" value="" class="campos"/>
							<input type="password" id="clave" name="clave" value="" class="campos"/>
						</td>
						<th class="resaltar">Confirmar Clave</th>
						<td><input type="password" id="cclave" name="cclave" value="" class="campos"/></td>
					</tr>
					<tr>
						<th class="resaltar">Firma</th>
						<td><input type="file" id="firma" name="firma" value="" class="campos"/></td>
						<td colspan="2"><img id="imgFirma" src="" style="width:220px"></td>
					</tr>
					<tr>
						<th class="resaltar" align="center" colspan="4" style="height:10px"></th>
					</tr>
					<tr>
						<th class="resaltar" align="center" colspan="4">
							<input type="button" class="vbotones" value="Grabar" onclick="Grabar()">
							<input type="button" class="vbotones" value="Cerrar" onclick="Cerrar()">
						</th>
					</tr>
				</table>
			</form>
		</center>
	</div>
	
	<div id="venFirImg" style="display:none">
		<br/>
		<center>
			<img id="imgFir" src="" style="width:220px"/>
		</center>
	</div>
</body>
<script>
	//window.parent.cambiarTitulo("USUARIOS Y PERFILES");
	iniciarForma();
</script>
</html>