<?php 

include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){

	header("location:entrada_usuario.php");
}

if(isset($_GET["msg"]))

	echo $_SESSION[APL]->msg($_GET["msg"]);

if(isset($_GET["emergente"]))

	$emergente=$_GET["emergente"];
else

if(isset($_POST["emergente"]))

	$emergente=$_POST["emergente"];

else

	$emergente=0;

echo $_SESSION[APL]->cabeceras();

$_SESSION[APL]->pagina_menu='registro_bitacoras.php';

if($emergente==0)

echo $_SESSION[APL]->interfas->pestana(2);

if(isset($_POST['heridos']) && isset($_POST['asunto']))

{
	$horInc = $_POST['horaincidente_h'].':'.$_POST['horaincidente_m'];
	
	
	$fecInc = date('Y-m-d');
	$horInc = date('H:i');

	// Si es administrador

	
	$parametros=array(
	'id'=>$id,
	'fecha'=>date('Y-m-d'),
	'hora'=>date('H-i'),
	'heridos'=>$_POST['heridos'],
	'motivo'=>$_POST['motivo'],
	'anotaciones'=>$_POST['anotaciones'],
	'info_por'=>$_POST['info_por'],
	'asunto'=>$_POST['asunto'],
	'ubicacion'=>$_POST['ubicacion'],
	'referencia'=>$_POST['referencia'],
	'abcisa' =>$_POST['abcisa'],
	'nombre_usuario'=>$_SESSION[APL]->usuario->nombres." ".$_SESSION[APL]->usuario->apellidos,
	'identificacion_usuario'=>$_SESSION[APL]->usuario->cedula,
	'id_usuario'=>$_SESSION[APL]->usuario->id
	);

	$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_bitacoras
	(
	id,
	fecha,
	hora,
	heridos,
	motivo,
	anotaciones,
	info_por,
	asunto,
	ubicacion,
	referencia,
	abcisa,
	nombre_usuario,
	identificacion_usuario,
	id_usuario
	)
	VALUES
	(
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?,
	?
	)";
	
	if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
		
	
		echo "<script>alert('Error al crear Incidente')</script>";
	else
	{
		/*	$cabeceras = "From: Devimed<incidentes@devimed.com.co>\r\n";
			$cabeceras .= "MIME-Version: 1.0\r\n";
			$cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$asunto="Nuevo Incidente con Codigo: ".date('Y').".".str_pad($codigo,5,"0",STR_PAD_LEFT);

			echo "<script>alert('Se Creo el incidente ".$periodo.".".str_pad($codigo,5,"0",STR_PAD_LEFT)." y se enviaron ".$nro_enviados." de ".$nro_mails." ');";
		if($emergente==1)
		echo "window.close();";
		echo "</script>";*/
		}

	}
?>


<script>

function cargar_referencias(via)

{
	document.incidente.referencia.length=0;
	document.incidente.abcisa.value='';
	document.incidente.tramo_ruta.value='';

	switch(via)

	{
		<?php
			$sql="SELECT * FROM 
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia
			order by id_via,referencia";
			$refe=$_SESSION[APL]->bd->getRs($sql);
			$refe_tmp=0;
			$refe_i=-1;
			while (!$refe->EOF) 
			{
				if($refe_tmp!=$refe->fields[1])
				{
					if($refe_i!=-1)
						echo "break;
						";
					echo "case '".$refe->fields[1]."':
					";
					echo "document.incidente.referencia.options[0]=new Option(' ','');
					";
					$refe_tmp=$refe->fields[1];
					$refe_i=1;
				}	
				echo "document.incidente.referencia.options[".$refe_i."]=new Option('".$refe->fields[4]."','".$refe->fields[0]."|".$refe->fields[2]."|".$refe->fields[5]."');
				";
				$refe_i++;
				$refe->MoveNext();
			}
		?>break;
		default:
		alert('Via no encontrada');
		break;
	}
}

function colocar_datos_referencia(valor)
{
	texto=valor.split('|');
	document.incidente.abcisa.value=texto[1];
	document.incidente.tramo_ruta.value=texto[2];
}

function regresar(){
	
	window.location.href = 'registro_inicial.php';
	
}
function guardar()

{
	if(document.incidente.motivo.value=='')
	{
		alert('Seleccione el Asunto')
		document.incidente.motivo.focus();
	}

	else

	if(document.incidente.anotaciones.value=='')
	{
		alert('Escriba la Anotacion')
		document.incidente.anotaciones.focus();
	}

	else
			document.incidente.submit();
}



</script>


<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<script type="text/javascript" src="libs/js/vista.js"></script>
<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

	<link href="css/tabla.css" rel="stylesheet" type="text/css">


<form name="incidente" method="post" action="registro_bitacoras.php" enctype="multipart/form-data">

<center>
	<table class="tabEdi" cellpadding="3" border="0"  width="70%">
		<tr><th colspan="8" style="background-color:#4CB877" class="LegendSt">Registro de Bitacoras</th>
		<tr><th colspan="8"  height="20">&nbsp;</th></tr>
		<tr>
			<th>FECHA</th>
			<td  align="left"><?php echo date('Y-m-d : H:i:s') ?></td>
		</tr>
		<tr><th colspan="8"  height="20">&nbsp;</th></tr>
		<tr><th colspan="8" style="background-color:#4CB877" class="LegendSt">Datos Iniciales</th>
		<tr><th colspan="8"  height="20">&nbsp;</th></tr>
		<tr>
			<th>ASUNTO</th>
			<td class="style1">
				<input type="text" name="motivo" style="width:325px">
			</td>
			<th>TIPO DE ATENCION</th>
			<td align="left" colspan="3">
				<select name="asunto" class="campos" style="width:325px">
					<option value=""></option>
						<?php
							$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion ORDER BY id";
							$rs=$_SESSION[APL]->bd->getRs($sql);
							while (!$rs->EOF) {
								echo "<option value='".$rs->fields[0]."' ";
								if(isset($_GET['id_buscar']) && $tipo_atencion==$rs->fields[0])

										echo "selected";

								echo ">".$rs->fields[1]."</option>";
								$rs->MoveNext();
							}
							$rs->close();
						?>
				</select>
			</td>
		</tr>
		<tr><th colspan="8"  height="20">&nbsp;</th></tr>
		<tr>
			<th>VIA</th>
			<td class="style1">

				<select name="ubicacion" class="campos" onchange="cargar_referencias(this.value)" style="width:325px">
					<option value=""></option>
						<?php
							$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via ORDER BY nombre";
							$rs=$_SESSION[APL]->bd->getRs($sql);
							while (!$rs->EOF) {
								echo "<option value='".$rs->fields[0]."' ";
								if(isset($_GET['id_buscar']) && $via==$rs->fields[0])
										echo "selected";
								echo ">".$rs->fields[1]."</option>";
								$rs->MoveNext();
							}
							$rs->close();
						?>
				</select>
			</td>
		
			<th>REFERENCIA</th>
			<td align="left" colspan="3">
				<select name="referencia" class="campos" onchange="colocar_datos_referencia(this.value)" style="width:325px">
					<option value=""></option>
						<?php
						if(isset($_GET['id_buscar']))
						{
							$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia WHERE id_via=".$via." ORDER BY referencia";
							$refe=$_SESSION[APL]->bd->getRs($sql);
							while (!$refe->EOF) 
							{
								echo "<option value='".$refe->fields[4]."' ";
								if(isset($_GET['id_buscar']) && $referencia==$refe->fields[0])

										echo "selected";
								echo ">".$refe->fields[4]."</option>";
								$refe->MoveNext();
							}

							$rs->close();
						}
						?>
				</select>
			</td>
		</tr>
		<tr><th colspan="8"  height="20">&nbsp;</th></tr>
		<tr>
			<th>ABSCISA ESTIMADA</th>
			<td align="left">
				<input type="text" name="abcisa" class="campos" value="<?php if(isset($_GET['id_buscar'])) echo $abscisa?>" disabled="false"/>
			</td>
			<th>TRAMO-RUTA</th>
			<td align="left">
				<input type="text" name="tramo_ruta" class="campos" value="<?php if(isset($_GET['id_buscar'])) echo $tramo_ruta?>" disabled="false"/>
			</td>
		</tr>
		<tr><th colspan="8"  height="20">&nbsp;</th></tr>
		<tr>
			<th>EMISOR</th>
				<td class="style1">
					<select name="info_por" class="campos"  style="width:325px">
						<option value=""></option>
							<?php
								$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado ORDER BY id";

									$rs=$_SESSION[APL]->bd->getRs($sql);

									while (!$rs->EOF) {

										echo "<option value='".$rs->fields[0]."' ";

										if(isset($_GET['id_buscar']) && $tipo_atencion==$rs->fields[0])

												echo "selected";

										echo ">".$rs->fields[1]."</option>";

										$rs->MoveNext();

									}

								$rs->close();

							?>
					</select>
				</td>
		
				<th>HERIDOS</th>
				<td align="left" colspan="3">
					<select name="heridos" style="width:325px">
						<option></option>
						<option value="SI">SI</option>
						<option value="NO">NO</option>
					</select>
				
				</td>
			</tr>
			<tr><th colspan="8"  height="20">&nbsp;</th></tr>
			<tr>
				<th>ANOTACIONES</th>
				<td align="left" colspan="3">
					<textarea name="anotaciones" class="campos"  style="width:795px; height:80px;"></textarea>
				</td>
			</tr>


<tr><td colspan="8" align="center" height="40px" valign="middle">
<tr><td colspan="8" align="center" height="40px" valign="middle">

<?php if(!isset($_GET['id_buscar']))

{?>

<input type="button"  value="Guardar" onclick="guardar()" class="vbotones" />   
						<input type="button" value="Regresar" onclick="regresar()" class="vbotones" />
						<a href="#"><input type="button" style="width:100px" class="vbotones" value="Generar Twitter"></a>


<?php }

echo "<br><br>";

echo date_default_timezone_get()." ".date('d-m-Y H:i');



?>


</td></tr>

</table>

</center>

</form>

</body>

<script>

	vis_ponCampoFecha("#fechaincidente");

</script>

</html>