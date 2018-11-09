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

if(isset($_GET["emergente"]))
	$emergente=$_GET["emergente"];
else
	if(isset($_POST["emergente"]))
		$emergente=$_POST["emergente"];
	else
		$emergente=0;

echo $_SESSION[APL]->cabeceras();

// URL de la aplicación de Mapas que cargará el punto espacializado
if(strpos($_SERVER["REQUEST_URI"], "desarrollo")){
	// Desarrollo
	$url = strtolower("http://localhost/devimed/mapas/index.php/operaciones/dibujar_punto/inicial");
} else {
	// Producción
	$url = strtolower("https://mapas.devimed.com.co/index.php/operaciones/dibujar_punto/inicial");
}

$_SESSION[APL]->pagina_menu='registro_inicial.php';

if($emergente==0)

echo $_SESSION[APL]->interfas->pestana(3);

$id=$_SESSION[APL]->getSecuencia('dvm_incidente','id');

if(isset($_POST['via']) && isset($_POST['tipo_atencion']))
{
	$sql = "select valor from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_constante WHERE id=1";

	$periodo = $_SESSION[APL]->bd->dato($sql);

	$codigo=$_SESSION[APL]->getSecuencia('dvm_incidente','codigo','where periodo='.$periodo);

	$refe=explode("|",$_POST['referencia']);

	$fecInc = date('Y-m-d');
	$horInc = date('H:i');

	// Si es administrador
	if( $_SESSION[APL]->usuario->id_perfil==0 )
	{
		if( isset($_POST['fechaincidente']) and $_POST['fechaincidente']!="" )
			$fecInc = $_POST['fechaincidente'];
		if( isset($_POST['horaincidente_h']) and $_POST['horaincidente_h']!="" )
			$horInc = $_POST['horaincidente_h'].':'.$_POST['horaincidente_m'];
	}

	if( $_SESSION[APL]->usuario->id_perfil==2 )
	{
		if( isset($_POST['fechaincidente']) and $_POST['fechaincidente']!="" )
			$fecInc = $_POST['fechaincidente'];
		if( isset($_POST['horaincidente_h']) and $_POST['horaincidente_h']!="" )
			$horInc = $_POST['horaincidente_h'].':'.$_POST['horaincidente_m'];
	}

	// Obtener en tipo atencion si es finalizacion automatico para admin vial
	$sql = "SELECT finauto
			FROM  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion
			WHERE id=".$_POST['tipo_atencion'];
	$rsTipAte=$_SESSION[APL]->bd->getRs($sql);
	$esTipAteFinAut = $rsTipAte->fields[0];

	$finAdmVial = 0;
	if( $esTipAteFinAut=="SI" )
		$finAdmVial = 1;

	// Formatear abscisa
	$km = floor($_POST["abscisa_real"] / 1000);
	$ms = substr($_POST["abscisa_real"], -3);

	$sql_coordenadas = "SELECT X ( c.coordenadas ) AS latitud, Y ( c.coordenadas ) AS longitud  FROM tmp_coordenadas AS c WHERE c.id_via = {$_POST['id_via_configuracion']} AND c.abscisa = {$_POST['abscisa_real']}  ORDER BY c.fecha_creacion DESC  LIMIT 0, 1";
	$resultado_coordenadas = $_SESSION[APL]->bd->getRs($sql_coordenadas);
	
	// Almacenamiento de coordenadas, si fueron detectadas
	$coordenadas = ($resultado_coordenadas->fields[0]) ? "coordenadas = POINT({$resultado_coordenadas->fields[0]}, {$resultado_coordenadas->fields[1]})," : "" ;
	
	// echo $_POST['via']."<br>";
	// echo $_POST['abscisa_real']."<br>";

	$parametros=array(
		'id'=>$id,
		'codigo'=>$codigo,
		'fecha'=>date('Y-m-d'),
		'hora_reporte'=>date('H:i'),
		'referencia'=>$refe[0],
		'via'=>$_POST['via'],
		'tipo_atencion'=>$_POST['tipo_atencion'],
	  	'estado'=>1,
		'periodo'=>$periodo,
		'visualizar_web'=>$_POST['visualizar_web'],
		'observaciones'=>$_POST['observaciones'],
		'tipo_incidente'=>$_POST['tipo_incidente'],
		'nombre_usuario'=>$_SESSION[APL]->usuario->nombres." ".$_SESSION[APL]->usuario->apellidos,
		'identificacion_usuario'=>$_SESSION[APL]->usuario->cedula,
		'id_usuario'=>$_SESSION[APL]->usuario->id,
		'abscisa_real'=>"K$km+$ms",
		'fechaincidente'=>$fecInc,
		'horaincidente'=>$horInc,
		'abscisa'=> $_POST['abscisa_real'],
	);

	$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente 
		SET
		id = ?,
		codigo = ?,
		fecha = ?,
		hora_reporte = ?,
		referencia = ?,
		via = ?,
		tipo_atencion = ?,
		estado = ?,
		fecha_creacion = CURRENT_TIMESTAMP,
		periodo = ?,
		visualizar_web = ?,
		observaciones = ?,
		tipo_incidente = ?,
		nombre_usuario = ?,
		identificacion_usuario = ?,
		id_usuario = ?,
		abscisa_real = ?,
		fechaincidente = ?,
		horaincidente = ?,
		$coordenadas
		abscisa = ?
	";
	
	if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
		echo "<script>alert('Error al crear Incidente')</script>";
	else
	{
		$parametros=array(
			'id_incidente'=>$id,
			'estado'=>1,
			'observaciones'=>$_SESSION[APL]->observaciones,
			'usuario'=>$_SESSION[APL]->usuario->id
		);

		$sql="INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente
		VALUES
		(?,?,?,CURRENT_TIMESTAMP,?)";

		if(!$_SESSION[APL]->bd->ejecutarO($sql,$parametros))
			echo "<script>alert('Error al crear Historial Incidente')</script>";
		else
		{
			$mensaje="Se ha generado un nuevo incidente con los siguientes datos :<br>";
			$mensaje.="Usuario Generador: ".$_SESSION[APL]->usuario->nombres." ".$_SESSION[APL]->usuario->apellidos."<br>";
			$mensaje.="Codigo: ".date('Y').".".str_pad($codigo,5,"0",STR_PAD_LEFT)."<br>";
			$mensaje.="Fecha: ".date('Y-m-d')."<br>";
			$mensaje.="Hora: ".date('H:i')."<br>";

			if($_POST['tipo_incidente']=='v')
				$t_i="Via sin ningún tipo de problema en su recorrido";
			else
				if($_POST['tipo_incidente']=='a')
					$t_i="Via con alguna restricción en su recorrido";
			else

			if($_POST['tipo_incidente']=='r')
				$t_i="Via que presenta problemas en su recorrido";
			$mensaje.="Tipo de Incidente: ".$t_i."<br>";

			$sql="SELECT nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via WHERE id=".$_POST['via'];

			$via_rs=$_SESSION[APL]->bd->getRs($sql);

			$mensaje.="Via: ".$via_rs->fields[0]."<br>";

			$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia WHERE id=".$refe[0];

			$ref_rs=$_SESSION[APL]->bd->getRs($sql);

			$abs=$ref_rs->fields[1];

			$tramo=$ref_rs->fields[2];

			$refe_n=$ref_rs->fields[0];

			$mensaje.="Referencia: ".$refe_n."<br>";
			$mensaje.="Abscisa: ".$abs."<br>";
			$mensaje.="Tramo: ".$tramo."<br>";
			$cabeceras = "From: Devimed<incidentes@devimed.com.co>\r\n";
			$cabeceras .= "MIME-Version: 1.0\r\n";
			$cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n";

			$asunto="Nuevo Incidente con Codigo: ".date('Y').".".str_pad($codigo,5,"0",STR_PAD_LEFT);

			$sql="SELECT correo FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios WHERE enviar_correo='SI'";

			$envi_c=$_SESSION[APL]->bd->getRs($sql);

			$nro_enviados=0;
			$nro_mails=0;

			while(!$envi_c->EOF)
			{
				if(mail($envi_c->fields[0], $asunto,$mensaje,$cabeceras))
					$nro_enviados++;
				$nro_mails++;

				$envi_c->MoveNext();
			}
			echo "<script>alert('Se Creo el incidente ".$periodo.".".str_pad($codigo,5,"0",STR_PAD_LEFT)." y se enviaron ".$nro_enviados." de ".$nro_mails." ');";

		if($emergente==1)
			echo "window.close();";
			echo "</script>";
		}
	}
}
?>

<script type="text/javascript" src="js/funciones.js?<?php echo date('Ymdhis'); ?>"></script>
<script type="text/javascript" src="libs/jq/jquery.min.js"></script>
<script type="text/javascript" src="libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<link type="text/css" href="libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

<script type="text/javascript">
	function cargar_referencias()
	{
		// Variables
		var via = parseInt(document.getElementById('via').value)
		var via_configuracion = parseInt($("#via option:selected").attr("data-id-via-configuracion"))
		var	abscisa = parseInt(document.getElementById('abscisa').value)

		// Si no se ha seleccionado la vía
		if(via == "" || !via){
			alert('Seleccione la vía')
			document.incidente.via.focus()

			return false;
		}

		// Si tiene abscisa, dibuja el punto en el mapa
		dibujar_punto(via_configuracion, abscisa)

		// Se limpia la lista
		$("select[name='referencia']").html('')

		// Mediante Ajax se consultan los sitios de referencia
		var registros = ajax(`cargar_referencias.php?via=${via}&abscisa=${abscisa}`, null, 'JSON')

		// Si no trae registros
		if(registros.length == 0){
			$("select[name='referencia']").append(`<option value="">Ning&uacute;n sitio encontrado</option>`)

			return false			
		}

		// Se recorren los resultados
		$.each(registros, function(key, val){
			// Se agrega al select el sitio de referencia
			$("select[name='referencia']").append(`<option data-abscisa="${val.abscisa_numerica}" value="${val.id}">${val.abscisa} - ${val.referencia}</option>`)
		})

	 	// Selecciona por defecto el valor de la abscisa que encontró
	 	$(`select[name='referencia'] option[data-abscisa="${abscisa}"]`).attr("selected", true)
	}

	function dibujar_punto(via, abscisa)
	{
		$("input[name='id_via_configuracion']").val(via)

		$("iframe").attr('src', `<?php echo $url; ?>/${via}/${abscisa}/${"<?php echo $id; ?>"}`)
	}

	function guardar()
	{
		if(document.incidente.tipo_atencion.value=='')
		{
			alert('Seleccione el Tipo de Atencion')
			document.incidente.tipo_atencion.focus();
		}
		else
		if(document.incidente.via.value=='')
		{
			alert('Seleccione la Via para el Evento')
			document.incidente.via.focus();
		}
		else
		if(document.incidente.referencia.value=='')
		{
			alert('Seleccione la Referencia para el Evento')
			document.incidente.referencia.focus();
		}
		else
			document.incidente.submit();

	}

	function ver_incidente()
	{
		if(document.incidente.id_buscar.value=='')
		{
			alert('Seleccione el Incidente a Visualizar');
		}
		else
			window.open('registro_inicial.php?id_buscar='+document.incidente.id_buscar.value,'_self');

	}

	function mueveReloj(){ 
	   	momentoActual = new Date() 
	   	hora = momentoActual.getHours() 
	   	minuto = momentoActual.getMinutes() 
	   	segundo = momentoActual.getSeconds() 
	   	str_segundo = new String (segundo) 

	   	if (str_segundo.length == 1) 
	      	 segundo = "0" + segundo 

	   	str_minuto = new String (minuto) 
	   	if (str_minuto.length == 1) 

	      	 minuto = "0" + minuto 

	   	str_hora = new String (hora) 

	   	if (str_hora.length == 1) 

	      	 hora = "0" + hora 

	   	horaImprimible = hora + " : " + minuto + " : " + segundo 

	   	document.incidente.reloj.value = horaImprimible 

	   	setTimeout("mueveReloj()",1000) 
	} 

</script>
	<?php
	if(isset($_GET['id_buscar']))
	{
		$parametro=array('id'=>$_GET['id_buscar']);
		$sql="SELECT i.id,fecha,hora_reporte,i.referencia,via,tipo_atencion,estado,r.abscisa,r.tramo_ruta,i.periodo,i.visualizar_web,tipo_incidente
		FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i,
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia  as r

		 WHERE 
		 i.referencia=r.id and
		 i.id=?";
		$inci=$_SESSION[APL]->bd->getRsO($sql,$parametro);
		$id_buscar=$inci->fields[0];
		$fecha=$inci->fields[1];
		$hora_reporte=explode(":",$inci->fields[2]);
		$hora_rep=$hora_reporte[0];
		$minu_rep=$hora_reporte[1];
		$referencia=$inci->fields[3];
		$via=$inci->fields[4];
		$tipo_atencion=$inci->fields[5];	
		$estado=$inci->fields[6];
		$abscisa=$inci->fields[7];
		$tramo_ruta=$inci->fields[8];
		$periodo=$inci->fields[9];
		$visualizar_web=$inci->fields[10];
		$tipo_incidente=$inci->fields[11];
	}
?>

<script type="text/javascript" src="libs/js/vista.js"></script>
<link href="css/tabla.css" rel="stylesheet" type="text/css">
<form name="incidente" method="post" action="registro_inicial.php" enctype="multipart/form-data">
	<center>
		<table class="tabEdi" cellpadding="3" border="0"  width="70%">
			<tr>
				<th colspan="8" style="background-color:#4CB877" class="LegendSt">Datos Basicos Nuevo Incidente</th>
				<tr>
					<th colspan="8"  height="20">&nbsp;</th>
				</tr>
				<tr>
					<th >FECHA DEL INCIDENTE</th>
					<td align="left">
						<input type='text' id='fechaincidente' name='fechaincidente' class="campos" readonly="true" value="<?php echo date("Y-m-d"); ?>">
					</td>

					<th>HORA</th>
					<td align="left">
						<select id="horaincidente_h" name='horaincidente_h' class="campos" style="width:45px">
							<option value="">--</option>
							<?php
							for($i=0; $i<=23; $i++){
								$seleccionado = ($i == date("H")) ? "selected" : "" ;

								echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'"'.$seleccionado.'>'.str_pad($i,2,'0',STR_PAD_LEFT).'</option>';
							}
							?>
						</select>:

						<select id="horaincidente_m" name='horaincidente_m' class="campos" style="width:45px">
							<option value="">--</option>
							<?php
							for($i=0; $i<=59; $i++){
								$seleccionado = ($i == date("i")) ? "selected" : "" ;
								echo '<option value="'.str_pad($i,2,'0',STR_PAD_LEFT).'" '.$seleccionado.'>'.str_pad($i,2,'0',STR_PAD_LEFT).'</option>';
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>TIPO DE ATENCION</th>
					<td align="left" colspan="3">
						<select name="tipo_atencion" class="campos" style="width:400px">
							<option value=""></option>

							<?php
							$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion ORDER BY nombre";
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
				<tr>
					<th>VIA</th>
					<td align="left" colspan="3">
						<select name="via" id="via"class="campos" style="width:400px" onchange="cargar_referencias()">
							<option value=""></option>
							<?php
							$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via ORDER BY nombre";
							$rs=$_SESSION[APL]->bd->getRs($sql);
							while (!$rs->EOF) {
							   	echo "<option value='".$rs->fields[0]."' data-id-via-configuracion='".$rs->fields[3]."' ";
								if(isset($_GET['id_buscar']) && $via==$rs->fields[0])
										echo "selected";

								echo ">".$rs->fields[1]."</option>";
							    $rs->MoveNext();
							}

							$rs->close();
							?>
						</select>
						<input type="hidden" name="id_via_configuracion">
					</td>
				</tr>
				<tr>
					<th>ABSCISA REAL</th>
					<td align="left">
						<input type='number' id="abscisa" name='abscisa_real' class="campos" onfocusout="cargar_referencias()" autocomplete="off" value="0">
						<input type='hidden' name='emergente' value='<?php echo $emergente?>'>
					</td>
				</tr>
				<tr>
					<th>REFERENCIA</th>
					<td align="left" colspan="3">
						<select name="referencia" class="campos" style="width:400px">
							<option value=""></option>
							<?php
							
							if(isset($_GET['id_buscar']))
							{
								$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia WHERE id_via=".$via." ORDER BY referencia";
								$refe=$_SESSION[APL]->bd->getRs($sql);

								while (!$refe->EOF) 
								{
									echo "<option value='".$refe->fields[0]."|".$refe->fields[2]."|".$refe->fields[5]."' ";
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
				<tr>
					<td colspan="8" align="center" height="40px" valign="middle">
						<?php if(!isset($_GET['id_buscar']))
						{?>
							<input type="button" value="Guardar" onclick="guardar()" class="vbotones" />
						<?php }
						echo "<br><br>";
						?>
					</td>
				</tr>
			</tr>
			<tr>
				<td colspan="8" align="center" height="40px" valign="middle">
					<iframe src="<?php echo $url; ?>" width="100%" height="360"></iframe>
						
					<?php echo date_default_timezone_get()." ".date('d-m-Y H:i'); ?>
				</td>
			</tr>
		</table>
	</center>
</form>
</body>
<script>
	vis_ponCampoFecha("#fechaincidente");
</script>
</html>