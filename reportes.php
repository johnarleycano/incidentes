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
$_SESSION[APL]->pagina_menu='reportes.php';
if($_SESSION[APL]->usuario->id_perfil==3)
	echo $_SESSION[APL]->interfas->pestana(3);
else
	echo $_SESSION[APL]->interfas->pestana(5);

?>

<script>
function filtrar(tipo)
{
	if(document.incidente.fecha_inicio.value=='' || document.incidente.fecha_final.value=='')
		alert('Seleccione el Periodo')
	else
	/*if(tipo==1 &&   document.incidente.id_ambulancia.value=='')
	{
		alert('Seleccione la Ambulancia para generar el Reporte');
		document.incidente.id_ambulancia.focus();
	}
	else
	if(tipo==2 &&   document.incidente.id_grua.value=='')
	{
		alert('Seleccione la Grua para generar el Reporte');
		document.incidente.id_grua.focus();
	}
	else*/
	{
		if(tipo==1)
			document.incidente.action='reportes_excel.php';
		else
			document.incidente.action='reportes_excel_2.php';
	
		document.incidente.submit();
	}
	
}

function generar(){
	
	if(document.incidente.fecha_inicio.value=='' || document.incidente.fecha_final.value==''){
		alert('Seleccione el Periodo')
	
	}else{
	
	
		document.incidente.action='reportes_excel_3.php';
		
	
		document.incidente.submit();
	}
	
}
</script>
<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
<script type="text/javascript" src="libs/js/vista.js"></script>
<form name="incidente" method="post" action="reportes_excel.php" target="_blank">
<center>

<table width="100%">
<tr><th class="cab_grid" colspan="3">
Reporte por Ambulancia
 </th>
</tr>
<tr>

<th class="resaltar">Periodo Inicial</th>
<td  align="left" >

<input class="campos cmpFec" type="text" id="fecha_inicio" name="fecha_inicio" maxlength="10" size="12" value="<?php if(isset($_POST['fecha_inicio'])) echo $_POST['fecha_inicio']; ?>"/>
	<select name="horai">
	<?php for ($h=0;$h<24;$h++)
	{
	
		echo "<option value='".str_pad($h,2,"0",STR_PAD_LEFT)."' ";
		if(isset($_POST['horai']) && str_pad($h,2,"0",STR_PAD_LEFT)==$_POST['horai'])
			echo "selected";
		echo ">".str_pad($h,2,"0",STR_PAD_LEFT)."</option>"; 
	}
	?>
	</select> :
				<select name="minui">
	<?php for ($m=0;$m<60;$m++)
	{
	
		echo "<option value='".str_pad($m,2,"0",STR_PAD_LEFT)."' ";
		if(isset($_POST['minui']) && str_pad($m,2,"0",STR_PAD_LEFT)==$_POST['minui'])
			echo "selected";
		echo ">".str_pad($m,2,"0",STR_PAD_LEFT)."</option>"; 
	}
	?>
	</select>
</td>
<td>&nbsp;</td>
<tr>
<th class="resaltar">Fecha Final</th>
<td>
<input class="campos cmpFec" type="text" id="fecha_final" name="fecha_final" maxlength="10" size="12" value="<?php 
if(isset($_POST['fecha_final']))
{
		echo $_POST['fecha_final'];
		
	}
	?>"/>
	<select name="horaf">
	<?php for ($h=0;$h<24;$h++)
	{
	
		echo "<option value='".str_pad($h,2,"0",STR_PAD_LEFT)."' ";
		if(isset($_POST['horaf']) && str_pad($h,2,"0",STR_PAD_LEFT)==$_POST['horaf'])
			echo "selected";
		echo ">".str_pad($h,2,"0",STR_PAD_LEFT)."</option>"; 
	}
	?>
	</select> :
				<select name="minuf">
	<?php for ($m=0;$m<60;$m++)
	{
	
		echo "<option value='".str_pad($m,2,"0",STR_PAD_LEFT)."' ";
		if(isset($_POST['minuf']) && str_pad($m,2,"0",STR_PAD_LEFT)==$_POST['minuf'])
			echo "selected";
		echo ">".str_pad($m,2,"0",STR_PAD_LEFT)."</option>"; 
	}
	?>
	</select>
</td>
<td>&nbsp;</td>
</tr>
<tr>
<th class="resaltar" >Ambulancia</th>
<td>
<select name="id_ambulancia" class="campos">
<option value="">Todas</option>

<?php

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia ORDER BY id";
$amb=$_SESSION[APL]->bd->getRs($sql);
while (!$amb->EOF) {
   	echo "<option value='".$amb->fields[0]."' ";
	if(isset($_POST['id_ambulancia']) && $_POST['id_ambulancia']==$amb->fields[0])
	{
	
	
		echo "selected";
		 $ambulancia_sel=strtoupper($amb->fields[1]);
	}
	else
		$ambulancia_sel="NO SE HA SELECCIONADO AMBULANCIA";
	echo ">".$amb->fields[1]."</option>";
    $amb->MoveNext();
}

?>
</select></td>
<td><?php 
echo $_SESSION[APL]->getButtom('.','Generar Reporte por Ambulancia', '200', 'onclick="filtrar(1)"');
?>
</td>
</tr>
<tr>
<th class="resaltar" >Grua</th>
<td>
<select name="id_grua" class="campos">
<option value="">Todas</option>
<?php

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_grua ORDER BY id";
$amb=$_SESSION[APL]->bd->getRs($sql);
while (!$amb->EOF) {
   	echo "<option value='".$amb->fields[0]."' ";
	if(isset($_POST['id_grua']) && $_POST['id_grua']==$amb->fields[0])
	{
	
	
		echo "selected";
		
	}

	echo ">".$amb->fields[1]."</option>";
    $amb->MoveNext();
}

?>
</select></td>
<td><?php 
echo $_SESSION[APL]->getButtom('.','Generar Reporte por Grua', '200', 'onclick="filtrar(2)"');
?>
</td>
</tr>

<tr>
<th class="resaltar" ></th>
<td>

<?php

$sql="SELECT id, codigo FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente ORDER BY id";
$amb=$_SESSION[APL]->bd->getRs($sql);

?>

</td>
<td><?php 
echo $_SESSION[APL]->getButtom('.','Generar Reporte Global', '200', 'onclick="generar()"');
?>
</td>
</tr>
<tr>
<th colspan="3" height="10">&nbsp;</th>
</tr>
</table>

	<table>
		<tr>
			<td><?php echo $_SESSION[APL]->getButtom('.','Grafico Numero de Accidentes por Fecha', '200', 'onclick="verGrafico(1)"'); ?></td>
			<td><?php echo $_SESSION[APL]->getButtom('.','Grafico Numero de Accidentes por Tramo', '200', 'onclick="verGrafico(2)"'); ?></td>
		</tr>
	</table>
</center>
</form>
<div id="venVerGrafico" style="display:none">
	<center>
		<iframe id="ifrVerGra" style="border:0px"></iframe>
	</center>
</div>
</body>
<script>
	vis_ponCampoFecha(".cmpFec");
	vis_PonVentana('venVerGrafico','VER GRAFICO',700,1000);
	$('#ifrVerGra').css('height',670);
	$('#ifrVerGra').css('width', 970);
	
	function verGrafico(pCual)
	{
		var fecIni = $("#fecha_inicio").val();
		var fecFin = $("#fecha_final").val();
		
		if( fecIni=="" )
		{
			alert("Seleccione la fecha de inicio");
			return;
		}
		if( fecFin=="" )
		{
			alert("Seleccione la fecha final");
			return;
		}
		
		if( pCual==1 )
		{
			$('#ifrVerGra').attr('src', "graficos/ejemplo.php?fecIni="+fecIni+"&fecFin="+fecFin);
			$("#venVerGrafico").dialog("open");
		}
		else if( pCual==2 )
		{
			$('#ifrVerGra').attr('src', "graficos/ejemplo2.php?fecIni="+fecIni+"&fecFin="+fecFin);
			$("#venVerGrafico").dialog("open");
		}
	}
</script>
</html>