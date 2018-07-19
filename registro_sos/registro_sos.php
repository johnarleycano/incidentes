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
		<!-- <script type="text/javascript" src="../libs/jq/jqGrid/js/jquery-1.11.0.min"></script> -->
		<script type="text/javascript" src="../libs/jq/jqGrid/js/i18n/grid.locale-es.js"></script>
		<script type="text/javascript" src="../libs/jq/jqGrid/plugins/ui.multiselect.js"></script>
		<script type="text/javascript" src="../libs/jq/jqGrid/plugins/jquery.tablednd.js"></script>
		<script type="text/javascript" src="../libs/jq/jqGrid/plugins/jquery.contextmenu.js"></script>
		
		<link href="../css/ventana.css" rel="stylesheet" type="text/css">	
		<link href="../css/campo.css" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="'.$ruta.'../imagenes/logo.ico" type="image/x-icon">
		
		<script type="text/javascript" src="../libs/js/vista.js"></script>
		<script type="text/javascript" src="registro_sos.js"></script>

		<style>
			.myAltRowClass{ background-color:#DDFFDD;background-image:none; }
			.ui-widget-header
			{
				background-color: #CDD2CD;
				background-image: none;
				color: #000;
			}
		</style>
	</head>
	<body onLoad="quitarEspere()" topmargin="0">
		<div id="espere" style="position:absolute; width:100%;height:100%;background-color:#FFFFFF;left:0px;top:0px;font-family:Verdana;filter:alpha(opacity=70); opacity:0.7">
			<center>
				<h2 class="LegendSt" style="height:30">Un Momento por Favor...</h2>
				<br/><img src="../img/wait.gif">
			</center>
		</div>
		
		<?php
		print_r($_SESSION[APL]);
		$_SESSION[APL]->pagina_menu='registro_sos.php';
		if($_SESSION[APL]->usuario->id_perfil==3)
			echo $_SESSION[APL]->interfas->pestana(2);
		else
			echo $_SESSION[APL]->interfas->pestana(4);
		?>
		<center>
			<select id="finalizado" name="finalizado" class="campos" onchange="filtrar()">
				<option value="0">Pendientes</option>
				<option value="1">Finalizados</option>
			</select><br/><br/>
			
			<table id="griRegSos"></table>
			<div id="pagRegSos"></div>
		</center>
		
		<div id="venEdiRegSos" style="display:none">
			<center>
				<iframe id="ifrEdiSOS" style="border:0px"></iframe>
			</center>
		</div>
		
		<div id="venVerSOSVehInv" style="display:none">
			<center>
				<iframe id="ifrVerSOSVehInv" style="border:0px"></iframe>
			</center>
		</div>

		<script type="text/javascript">
			iniciarForma();
		</script>
	</body>
</html>