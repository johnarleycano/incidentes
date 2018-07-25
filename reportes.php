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
	echo $_SESSION[APL]->interfas->pestana(6);
?>

<script type="text/javascript">
	function filtrar(tipo)
	{
		if(document.incidente.fecha_inicio.value=='' || document.incidente.fecha_final.value==''){
			alert('Seleccione el Periodo')

			return false
		}

		document.incidente.action = (tipo == 1) ? "reportes_excel.php" : "reportes_excel_2.php"
		document.incidente.submit();
	}

	function generar(){
		if(document.incidente.fecha_inicio.value=='' || document.incidente.fecha_final.value==''){
			alert('Seleccione el Periodo')

			return false
		}		
		
		document.incidente.action='reportes_excel_3.php';
		document.incidente.submit();
	}
</script>

<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
<script type="text/javascript" src="libs/js/vista.js"></script>

<style type="text/css">
	.zona1 {
		/*border: 1px solid black;*/
		display: inline-block;
		font-family: Tahoma;
		margin: 0;
		padding: 0;
		width: 45%;
	}
</style>

<form name="incidente" method="post" action="reportes_excel.php" target="_blank">
	<h1 class="cab_grid">REPORTES</h1>

	<div class="zona1">
		<!-- Período inicial -->
		<div class="zona1 resaltar">
			<strong>PER&Iacute;ODO INICIAL</strong>
		</div>
		<div class="zona1">
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
		</div>

		<!-- Fecha final -->
		<div class="zona1 resaltar">
			<strong>FECHA FINAL</strong>
		</div>
		<div class="zona1">
			<input class="campos cmpFec" type="text" id="fecha_final" name="fecha_final" maxlength="10" size="12" value="<?php if(isset($_POST['fecha_final'])){ echo $_POST['fecha_final']; } ?>"/>

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
		</div>
		
		<!-- Ambulancia -->
		<div class="zona1 resaltar">
			<strong>AMBULANCIA</strong>
		</div>
		<div class="zona1">
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
			</select>
		</div>

		<!-- Grúa -->
		<div class="zona1 resaltar">
			<strong>GR&Uacute;A</strong>
		</div>
		<div class="zona1">
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
			</select>
		</div>

	</div>

	<div class="zona1">
		<div class="zona1">
			<?php 
			echo $_SESSION[APL]->getButtom('.','Generar Reporte por Ambulancia', '200', 'onclick="filtrar(1)"');
			echo $_SESSION[APL]->getButtom('.','Generar Reporte por Grua', '200', 'onclick="filtrar(2)"');

			$sql="SELECT id, codigo FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente ORDER BY id";
			$amb=$_SESSION[APL]->bd->getRs($sql);

			echo $_SESSION[APL]->getButtom('.','Generar Reporte Global', '200', 'onclick="generar()"');
			?>
		</div>
		<div class="zona1">
			<?php echo $_SESSION[APL]->getButtom('.','Grafico Numero de Accidentes por Fecha', '200', 'onclick="verGrafico(1)"'); ?>
			<?php echo $_SESSION[APL]->getButtom('.','Grafico Numero de Accidentes por Tramo', '200', 'onclick="verGrafico(2)"'); ?>
		</div>
	</div>
</form>

<div id="venVerGrafico" style="display:none">
	<center>
		<iframe id="ifrVerGra" style="border:0px"></iframe>
	</center>
</div>
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