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
$_SESSION[APL]->pagina_menu='parametrizacion.php';
echo $_SESSION[APL]->interfas->pestana(7);
?>
<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
<script type="text/javascript" src="libs/js/vista.js"></script>
<script>
function editar_tabla(tabla)
{
	var h=430;
	var w=720
	var iz=(screen.width/2)-(w/2);
    var de=(screen.height/2)-(h/2);
	
	var direccion = "editar_tabla.php?tabla="+tabla;
	if( tabla=="dvm_usuarios" )
		direccion = "usuario/usuario.php";
	
	//window.open(direccion,"","width="+w+",height="+h+",left="+iz+",top="+de+", scrollbars=yes,menubars=no,statusbar=yes, status=yes, resizable=NO,location=NO")
	cambiarTitulo('_');
	$('#ifrPar').attr('src', direccion)
	//$("#venPar").dialog({ position:'center' });
	$("#venPar").dialog("open");
}

function iniciarForma()
{
	var pAlto = $(window).height()-40;
	var pAncho = $(window).width()-50;

	vis_PonVentana('venPar','hola',pAlto,pAncho);
	$('#ifrPar').css('height',pAlto-80);
	$('#ifrPar').css('width',pAncho-50);
}

function cambiarTitulo(pTitulo)
{
	$("#venPar").dialog("option", "title", pTitulo);
}

function cerrarIframe()
{
	$("#venPar").dialog("close");
}
</script>
<style>
	.ui-widget-header
	{
		background-color: #CDD2CD;
		background-image: none;
		color: #000;
	}
</style>
<br />
	<form name="incidente" method="post" action="parametrizacion.php" >
		<center>
			<br/><br/>
			<table width="85%">
				<tr class="cab_grid"><th  class="style2" colspan="6">PARAMETRIZACION</th></tr>
				<tr><th   colspan="6" height="20px">&nbsp;</th></tr>
				<tr>
					<td align="center">
						<?php
							$size=185;
							echo $_SESSION[APL]->getButtom('.','Via', $size, 'onclick=editar_tabla("dvm_via")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Referencia', $size, 'onclick=editar_tabla("dvm_referencia")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Admin Vial Polca', $size, 'onclick=editar_tabla("dvm_adm_vial_polca")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Ambulancia', $size, 'onclick=editar_tabla("dvm_ambulancia")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Aseguradora', $size, 'onclick=editar_tabla("dvm_aseguradora")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Tipo Vehiculo', $size, 'onclick=editar_tabla("dvm_vehiculo_involucrado")');
						?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Centro de Salud', $size, 'onclick=editar_tabla("dvm_centro_salud")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Clinica', $size, 'onclick=editar_tabla("dvm_clinica")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Hospital', $size, 'onclick=editar_tabla("dvm_hospital")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Municipio', $size, 'onclick=editar_tabla("dvm_municipio")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Otro Traslado Lesionado', $size, 'onclick=editar_tabla("dvm_otro_traslado_lesionado")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Sitio Traslado Usuario', $size, 'onclick=editar_tabla("dvm_sitio_traslado_usuario")');
						?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Otro Traslado Vehiculo', $size, 'onclick=editar_tabla("dvm_otro_traslado_vehiculo")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Sitio Traslado Vehiculo', $size, 'onclick=editar_tabla("dvm_sitio_traslado_vehiculo")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Parqueadero', $size, 'onclick=editar_tabla("dvm_parqueadero")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Taller', $size, 'onclick=editar_tabla("dvm_taller")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Tipo Atencion', $size, 'onclick=editar_tabla("dvm_tipo_atencion")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Transito', $size, 'onclick=editar_tabla("dvm_transito")');
						?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Constantes', $size, 'onclick=editar_tabla("dvm_constante")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Apoyo en Atencion', $size, 'onclick=editar_tabla("dvm_apoyo")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Informado por', $size, 'onclick=editar_tabla("dvm_informado")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Usuarios y Perfiles', $size, 'onclick=editar_tabla("dvm_usuarios")');
						?>
					</td>
					<td align="center">
						<?php
						echo $_SESSION[APL]->getButtom('.','Sentido', $size, 'onclick=editar_tabla("dvm_sentido")');
						?>
					</td>
				</tr>
			</table>
		</center>
	</form>
	<div id="venPar" style="display:none">
		<center>
			<iframe id="ifrPar" style="border:0px"></iframe>
		</center>
	</div>
</body>
<script>
	iniciarForma();
</script>
</html>