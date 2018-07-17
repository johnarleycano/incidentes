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
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<script type="text/javascript" src="../libs/jq/jquery.min.js"></script>
	
	<!-- UI -->
	<script type="text/javascript" src="../libs/jq/ui/js/jquery-ui-1.10.3.custom.min.js"></script>
	<link type="text/css" href="../libs/jq/ui/css/custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
	
	<!-- JQGrid -->
	<link type="text/css" rel="stylesheet" media="screen" href="../libs/jq/jqGrid/src/css/ui.jqgrid.css">
	<link type="text/css" rel="stylesheet" media="screen" href="../libs/jq/jqGrid/src/css/ui.multiselect.css">
	<script type="text/javascript" src="../libs/jq/jqGrid/js/i18n/grid.locale-es.js"></script>
	<script type="text/javascript" src="../libs/jq/jqGrid/plugins/ui.multiselect.js"></script>
	<script type="text/javascript" src="../libs/jq/jqGrid/js/jquery.jqGrid.min.js"></script>
	<script type="text/javascript" src="../libs/jq/jqGrid/plugins/jquery.tablednd.js"></script>
	<script type="text/javascript" src="../libs/jq/jqGrid/plugins/jquery.contextmenu.js"></script>
	
	<link href="../css/ventana.css" rel="stylesheet" type="text/css">	
	<link href="../css/campo.css" rel="stylesheet" type="text/css">
	<link href="../css/tabla.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="'.$ruta.'../imagenes/logo.ico" type="image/x-icon">
		
	<link type="text/css" rel="stylesheet" href="../calendario/dhtmlgoodies_calendar.css" media="screen"></link>
	<script type="text/javascript" src="../calendario/dhtmlgoodies_calendar.js"></script>
	
	<script type="text/javascript" src="../libs/js/vista.js"></script>
	<script type="text/javascript" src="busqueda.js"></script>

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
		
		
		#edad_d, #edad_h
		{
			width:52px !important;
		}
		#fecha_inicio, #fecha_fin
		{
			width:100px !important;
		}
		select
		{
			text-transform: uppercase !important;
		}
	</style>
	<script>
		function cargar_referencias(via)
		{
			document.frmBus.referencia.length=0;
			document.frmBus.abcisa.value='';
			document.frmBus.tramo_ruta.value='';
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
							echo "document.frmBus.referencia.options[0]=new Option(' ','');
							";
							$refe_tmp=$refe->fields[1];
							$refe_i=1;
						}	
						echo "document.frmBus.referencia.options[".$refe_i."]=new Option('".$refe->fields[4]."','".$refe->fields[0]."|".$refe->fields[2]."|".$refe->fields[5]."');
						";
						$refe_i++;
						$refe->MoveNext();
					}
				?>
				break;
				default:
					alert('Via no encontrada');
				break;
			}
			
			cargar_sentidos(via);
		}
		
		function colocar_datos_referencia(valor)
		{
			texto=valor.split('|');
			document.frmBus.abcisa.value=texto[1];
			document.frmBus.tramo_ruta.value=texto[2];
		}
		
		function cargar_sentidos(via)
		{
			document.frmBus.sentido_via.length=0;

			switch(via)
			{
				<?php
					$sql = "SELECT * 
							FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_sentido
							order by via, id";
					$rsSen = $_SESSION[APL]->bd->getRs($sql);

					$sent_tmp=0;
					$sent_i=-1;
					$entro = false;

					while (!$rsSen->EOF) 
					{
						$entro = true;
						if($sent_tmp!=$rsSen->fields[1])
						{
							if($sent_i!=-1)
								echo "break;";

							echo "case '".$rsSen->fields[1]."':";
							echo "document.frmBus.sentido_via.options[0]=new Option(' ','');";

							$sent_tmp=$rsSen->fields[1];
							$sent_i=1;
						}	
						echo "document.frmBus.sentido_via.options[".$sent_i."]=new Option('".$rsSen->fields[2]."','".$rsSen->fields[0]."');";
						$sent_i++;
						$rsSen->MoveNext();
					}
					
					if( $entro==true )
						echo 'break;';
				?>
				default:
					//alert('Sentido no encontrado');
				break;
			}
		}
	</script>
</head>
<body onLoad="espere.style.display='none';AbrirBus();" topmargin="0">
	<div id="espere" style="position:absolute; width:100%;height:100%;background-color:#FFFFFF;left:0px;top:0px;font-family:Verdana;filter:alpha(opacity=70); opacity:0.7">
		<center>
			<h2 class="LegendSt" style="height:30">Un Momento por Favor...</h2>
			<br/><img src="../img/wait.gif">
		</center>
	</div>
	
	<?php
		$_SESSION[APL]->pagina_menu='busqueda.php';
		echo $_SESSION[APL]->interfas->pestana(0);
	?>
	<center>
		<table id="griBuscar"></table>
		<div id="pagBuscar"></div>
		<table>
			<tr>
				<!--<td><?php echo $_SESSION[APL]->getButtom('..','+/- Columnas', '100', 'onclick="abrirMasMenosCols()"'); ?></td>-->
				<td><?php echo $_SESSION[APL]->getButtom('..','Buscar', '100', 'onclick="AbrirBus()"'); ?></td>
				<td><?php echo $_SESSION[APL]->getButtom('..','Vers Promedios', '100', 'onclick="AbrirProm()"'); ?></td>
				<td>
					<span id="spnDes" style="display:none">
						<?php echo $_SESSION[APL]->getButtom('..','Descargar Archivo', '100', 'onclick="abrirDescarga()"'); ?>
					</span>
				</td>
				<td>
					<span id="spnDes" style="display:none">
						<?php echo $_SESSION[APL]->getButtom('..','Descargar Archivo', '100', 'onclick="abrirDescarga()"'); ?>
					</span>
				</td>
				
			</tr>
		</table>
	</center>
	
	<div id="venBuscar" style="display:none" class="cssBus">
		<form id="frmBus" name="frmBus">
			<input type="hidden" id="buscar" name="buscar" value="NO"/>
			<table width="100%" class="tabEdi" cellpadding="3" border="0">
				<tr><th colspan="6" style="height:2px"></th></tr>
				<tr>
					<th class="resaltar">Codigo</th>
					<td><input type="text" name="codigo" value="" class="campos"/></td>
					<th class="resaltar">Fecha Reporte Inicial</th>
					<td>
						<input class="campos cmpFec" type="text" name="fecha_inicio" id="fecha_inicio" maxlength="10" size="12" value=""/>
						(yyyy-mm-dd)
					</td>
					<th class="resaltar">Fecha Reporte Final</th>
					<td>
						<input class="campos cmpFec" type="text" name="fecha_fin" id="fecha_fin" maxlength="10" size="12" value=""/>
						(yyyy-mm-dd)
					</td>
				</tr>
				<tr>
					<th class="resaltar">Cedula Lesionado</th>
					<td><input type="text" name="cedula" value="" class="campos"/></td>
					<th class="resaltar">Usuario Registra</th>
					<td>
						<select name="usuario_registra" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombres,apellidos 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios 
										where id_perfil in (0,1)ORDER BY id";
								$usu_reg = $_SESSION[APL]->bd->getRs($sql);

								$usu_reg->MoveFirst();
								while(!$usu_reg->EOF)
								{
									echo "<option value ='".$usu_reg->fields[0]."'>".$usu_reg->fields[1]." ".$usu_reg->fields[2]."</option>";
									$usu_reg->MoveNext();
								}
							?>
						</select>
					</td>
					<th class="resaltar">Usuario SOS</th>
					<td>
						<select name="usuario_sos" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombres,apellidos 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios 
										where id_perfil in (0,2)ORDER BY id";
								$usu_sos=$_SESSION[APL]->bd->getRs($sql);

								$usu_sos->MoveFirst();
								while(!$usu_sos->EOF)
								{
									echo "<option value ='".$usu_sos->fields[0]."'>".$usu_sos->fields[1]." ".$usu_sos->fields[2]."</option>";
									$usu_sos->MoveNext();
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th class="resaltar">Usuario Adm Vial</th>
					<td>
						<select id="usuario_adm_vial" name="usuario_adm_vial" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombres,apellidos 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios 
										where id_perfil in (0,3)ORDER BY id";
								$usu_adm=$_SESSION[APL]->bd->getRs($sql);

								$usu_adm->MoveFirst();
								while(!$usu_adm->EOF)
								{
									echo "<option value='".$usu_adm->fields[0]."'>".$usu_adm->fields[1]." ".$usu_adm->fields[2]."</option>";
									$usu_adm->MoveNext();
								}
							?>
						</select>
					</td>
					<th class="resaltar">Rango Edad</th>
					<td>
						Desde: 
						<input type="text" id="edad_d" name="edad_d" value="" class="campos" style="width:40px"/>
						Hasta: 
						<input type="text" id="edad_h" name="edad_h" value="" class="campos" style="width:40px"/>
					</td>
					<th class="resaltar">Entidad</th>
					<td>
						<select id="entidad" name="entidad" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo 
										ORDER BY id";
								$apo=$_SESSION[APL]->bd->getRs($sql);

								while (!$apo->EOF)
								{
									echo "<option value='".$apo->fields[0]."'>".$apo->fields[1]."</option>";
									$apo->MoveNext();
								}
							?>
						</select>
					</td>
				</tr>
			<tr>
					<th class="resaltar">Tipo Vehiculo</th>
					<td>
						<select id="id_tipo_vehiculo" name="id_tipo_vehiculo" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado 
										ORDER BY id";
								$tveh=$_SESSION[APL]->bd->getRs($sql);

								while (!$tveh->EOF)
								{
									echo "<option value='".$tveh->fields[0]."'>".$tveh->fields[1]."</option>";
									$tveh->MoveNext();
								}
							?>
						</select>
					</td>
					<th class="resaltar">Placa</th>
					<td><input type="text" id="placa" name="placa" value="" class="campos"/></td>
					<th class="resaltar">Cilindraje</th>
					<td><input type="text" id="cilindraje" name="cilindraje" value="" class="campos"/>&nbsp;C.C.</td>
					<td></td>
				</tr>
				<tr>
					<th class="resaltar">Aseguradora</th>
					<td>
						<select name="id_aseguradora" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_aseguradora 
										ORDER BY id";
								$aseg=$_SESSION[APL]->bd->getRs($sql);

								while (!$aseg->EOF)
								{
									echo "<option value='".$aseg->fields[0]."'>".$aseg->fields[1]."</option>";
									$aseg->MoveNext();
								}

							?>
						</select>
					</td>
					<th class="resaltar">Via</th>
					<td>
						<select id="via" name="via" class="campos" onchange="cargar_referencias(this.value)">
							<option value=""></option>
							<?php
								$sql = "SELECT * 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via 
										ORDER BY id";
								$rs=$_SESSION[APL]->bd->getRs($sql);

								while (!$rs->EOF)
								{
									echo "<option value='".$rs->fields[0]."'>".$rs->fields[1]."</option>";
									$rs->MoveNext();
								}
								$rs->close();
							?>
						</select>
					</td>
					<th class="resaltar">Referencia</th>
					<td>
						<select id="referencia" name="referencia" class="campos" onchange="colocar_datos_referencia(this.value)">
							<option value=""></option>
							<?php
								// Pendiente usar ajax por jquery
								if( isset($_POST['referencia']) && $_POST['via']!='' )
								{
									$sql = "SELECT * 
											FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia 
											WHERE id_via=".$_POST['via']." 
											ORDER BY referencia";
									$refe=$_SESSION[APL]->bd->getRs($sql);

									while (!$refe->EOF) 
									{
										if(isset($_POST['referencia']) && $_POST['referencia'])
										{
											$refe_r=explode("|",$_POST['referencia']);
											$abs=$refe_r[1];
											$tramo=$refe_r[2];
											$refe_r=$refe_r[0];
										}
										else
										{
											$refe_r='';
											$abs='';
											$tramo='';
										}

										echo "<option value='".$refe->fields[0]."|".$refe->fields[2]."|".$refe->fields[5]."'>".$refe->fields[4]."</option>";
										$refe->MoveNext();
									}
									$rs->close();
								}// Fin if referencia
							?>
						</select>
					</td>
					
				</tr>
				<tr>
					<th class="resaltar">Sentido</th>
					<td>
						<select id="sentido_via" name="sentido_via" class="campos">
							<option value=""></option>
						</select>
					</td>
					<th class="resaltar">Abscisa</th>
					<td><input type="text" id="abcisa" name="abcisa" class="campos" value="" disabled="false"/></td>
					<th class="resaltar">Tramo Ruta</th>
					<td><input type="text" name="tramo_ruta" class="campos" value="" disabled="false"/></td>
					<th colspan="2">&nbsp;</th>
				</tr>
				<tr>
					<th class="resaltar">Conndiciones Climaticas</th>
					<td>
						<select name="condiciones" class="campos" id="condiciones" style="width:200px">

							<option value=""></option>
							<option value="GRANIZADA">GRANIZADA</option>
							<option value="LLUVIA">LLUVIA</option>
							<option value="NIEBLA">NIEBLA</option>
							<option value="VIENTO">VIENTO</option>
							<option value="NORMALES">NORMALES</option>
							</select>
					</td>
				</tr>
				<tr><th colspan="6" height="10"></th></tr>
				<tr><th class="LegendSt" colspan="6" style="background-color:#4CB877">Sitio Traslado Vehiculo</th></tr>
				<tr>
					<th class="resaltar">Parqueadero</th>
					<td>
						<select id="id_parqueadero" name="id_parqueadero" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_parqueadero 
										ORDER BY id";
								$par=$_SESSION[APL]->bd->getRs($sql);
								while (!$par->EOF)
								{
									echo "<option value='".$par->fields[0]."'>".$par->fields[1]."</option>";
									$par->MoveNext();
								}
							?>
						</select>
					</td>
					<th class="resaltar">Transito</th>
					<td>
						<select id="id_transito" name="id_transito" class="campos">
							<option value=""></option>
							<?php
							$sql = "SELECT id,nombre 
									FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_transito 
									ORDER BY id";
							$tra=$_SESSION[APL]->bd->getRs($sql);

							while (!$tra->EOF)
							{
								echo "<option value='".$tra->fields[0]."'>".$tra->fields[1]."</option>";
								$tra->MoveNext();
							}
							?>
						</select>
					</td>
					<th class="resaltar">Taller</th>
					<td>
						<select id="id_taller" name="id_taller" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_taller 
										ORDER BY id";
								$tal=$_SESSION[APL]->bd->getRs($sql);

								while (!$tal->EOF)
								{
									echo "<option value='".$tal->fields[0]."'>".$tal->fields[1]."</option>";
									$tal->MoveNext();
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th class="resaltar">Otro Tras Veh</th>
					<td>
						<select id="id_otro_vehiculo" name="id_otro_vehiculo" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_vehiculo 
										ORDER BY id";
								$otr=$_SESSION[APL]->bd->getRs($sql);

								while (!$otr->EOF)
								{
									echo "<option value='".$otr->fields[0]."'>".$otr->fields[1]."</option>";
									$otr->MoveNext();
								}
							?>
						</select>
					</td>
					<th colspan="4"></th>
				</tr>
				<tr><th colspan="6" height="10"></th></tr>
				<tr><th class="LegendSt" colspan="6" style="background-color:#4CB877">Sitio Traslado Lesionado</th></tr>
				<tr>
					<th class="resaltar">Hospital</th>
					<td>
						<select name="id_hospital" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_hospital 
										ORDER BY id";
								$hos=$_SESSION[APL]->bd->getRs($sql);

								while (!$hos->EOF)
								{
									echo "<option value='".$hos->fields[0]."'>".$hos->fields[1]."</option>";
									$hos->MoveNext();
								}
							?>
						</select>
					</td>
					<th class="resaltar">Clinica</th>
					<td>
						<select name="id_clinica" class="campos">
							<option value=""></option>
							<?php
								$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_clinica ORDER BY id";
								$cli=$_SESSION[APL]->bd->getRs($sql);

								while (!$cli->EOF)
								{
									echo "<option value='".$cli->fields[0]."'>".$cli->fields[1]."</option>";
									$cli->MoveNext();
								}
							?>
						</select>
					</td>
					<th class="resaltar">Centro de Salud</th>
					<td>
						<select name="id_centro_salud" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_centro_salud 
										ORDER BY id";
								$censa=$_SESSION[APL]->bd->getRs($sql);
								while (!$censa->EOF)
								{
									echo "<option value='".$censa->fields[0]."'>".$censa->fields[1]."</option>";
									$censa->MoveNext();
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th class="resaltar">Otro Sitio</th>
					<td>
						<select id="id_otro_lesionado" name="id_otro_lesionado" class="campos">
							<option value=""></option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_lesionado 
										ORDER BY id";
								$otrl=$_SESSION[APL]->bd->getRs($sql);

								while (!$otrl->EOF)
								{
									echo "<option value='".$otrl->fields[0]."'>".$otrl->fields[1]."</option>";
									$otrl->MoveNext();
								}
							?>
						</select>
					</td>
					<th class="resaltar">Autoridad Interviene</th>
					<td>
						<select id="autoridad" name="autoridad" class="campos">
							<option value=""></option>
							<option value="P">Policia Transito y Transporte</option>
							<option value="T">Transito</option>
							<option value="I">Inspector</option>
						</select>
					</td>
					<th class="resaltar">Placa Aut</th>
					<td><input type="text" id="placa_a" name="placa_a" value=""/></td>
				</tr>
				<tr>
					<th class="resaltar">Apellido Aut</th>
					<td><input type="text" name="apellido_a" value="<?php if(isset($_POST['apellido_a'])) echo $_POST['apellido_a']?>" /></td>
					<th class="resaltar">Caracteristicas Sitio</th>
					<td>
						<select name="caracteristicas" class="campos">
							<option value=""></option>
							<option value="H">Señalizacion Hotizontal</option>
							<option value="V">Señalizacion Vertical</option>
							<option value="G">Mantenimiento General</option>
							<option value="R">Rodadura</option>
						</select>
					</td>
					<th class="resaltar">Estado Sitio</th>
					<td colspan="3">
						<table>
							<tr>
								<td>Bueno</td>
								<td><input name="estado_sitio" type="radio" class="campos" value="B"/></td>
								<td>Regular</td>
								<td><input name="estado_sitio" type="radio" class="campos" value="R"/></td>
								<td>Malo</td>
								<td><input name="estado_sitio" type="radio" class="campos" value="M"/></td>
								<td>Todos</td>
								<td><input name="estado_sitio" type="radio" class="campos" value=""/></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th class="resaltar">Tipo de Atencion</th>
					<td>
						<select id="tipo_atencion" name="tipo_atencion" class="campos">
							<option value="">*</option>
							<?php
								$sql = "SELECT * 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion 
										ORDER BY id";
								$rs=$_SESSION[APL]->bd->getRs($sql);

								while (!$rs->EOF)
								{
									echo "<option value='".$rs->fields[0]."'>".$rs->fields[1]."</option>";
									$rs->MoveNext();
								}
								$rs->close();
							?>
						</select>
					</td>
					<th class="resaltar">Informado por:</th>
					<td>
						<select id="informado_por" name="informado_por" class="campos"> 
							<option value="">*</option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado 
										ORDER BY id";
								$aseg = $_SESSION[APL]->bd->getRs($sql);

								while (!$aseg->EOF)
								{
									echo "<option value='".$aseg->fields[0]."'>".$aseg->fields[1]."</option>";
									$aseg->MoveNext();
								}
							?>
						</select>
					</td>
					<th class="resaltar">Estado SOS</th>
					<td>
						<select id="estado_sos" name="estado_sos" class="campos">
							<option value="">Todos</option>
							<option value="P">Pendiente</option>
							<option value="G">Guardado</option>
							<option value="F">Finalizado</option>
						</select>
					</td>
				</tr>
				<tr>
					<th class="resaltar">Estado Adm Vial</th>
					<td>
						<select id="estado_adm_vial" name="estado_adm_vial" class="campos">
							<option value="">Todos</option>
							<option value="P">Pendiente</option>
							<option value="G">Guardado</option>
							<option value="F">Finalizado</option>
						</select>
					</td>
					<th colspan="4"></th>
				</tr>
				<tr><th colspan="6" height="10"></th></tr>
				<tr><th class="LegendSt" colspan="6" style="background-color:#4CB877">Ambulancia, Grua, Tipo Lesionado</th></tr>
				<tr>
					<th>Ambulancia</th>
					<td>
						<select id="id_ambulancia" name="id_ambulancia" class="campos">
							<option value="">Todas</option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia 
										ORDER BY id";
								$amb=$_SESSION[APL]->bd->getRs($sql);

								while (!$amb->EOF)
								{
									echo "<option value='".$amb->fields[0]."'>".$amb->fields[1]."</option>";
									$amb->MoveNext();
								}
							?>
						</select>
					</td>
					<th>Grua</th>
					<td>
						<select id="id_grua" name="id_grua" class="campos">
							<option value="">Todas</option>
							<?php
								$sql = "SELECT id,nombre 
										FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_grua 
										ORDER BY id";
								$amb=$_SESSION[APL]->bd->getRs($sql);

								while (!$amb->EOF)
								{
									echo "<option value='".$amb->fields[0]."'>".$amb->fields[1]."</option>";
									$amb->MoveNext();
								}
							?>
						</select>
					</td>
					<th>Tipo Lesionado</th>
					<td colspan="3">
						<table>
							<tr>
								<td>Conductor</td>
								<td><input type='checkbox' id='conductor' name='conductor' value='SI'/></td>
								<td>Lesionado</td>
								<td><input type='checkbox' id='lesionado' name='lesionado' value='SI'/></td>
								<td>Muerto</td>
								<td><input type='checkbox' id='muerto' name='muerto' value='SI'/></td>
							</tr>
							<tr id="trTipLes">
								<td colspan="2"></td>
								<td>Les. Leve</td>
								<td><input type='checkbox' id='tipLesL' name='tipLesL' value='SI'/></td>
								<td>Les. Grave</td>
								<td><input type='checkbox' id='tipLesG' name='tipLesG' value='SI'/></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th colspan="6" align="center">
						<center>
							<table>
								<tr>
									<td><?php echo $_SESSION[APL]->getButtom('..','Buscar Completo', '100', 'onclick="Buscar(1)"'); ?></td>
									<td><?php echo $_SESSION[APL]->getButtom('..','Buscar Resumido', '100', 'onclick="Buscar(0)"'); ?></td>
								</tr>
							</table>
						</center>
					</th>
				</tr>
				<tr><th colspan="6" style="height:2px"></th></tr>
			</table>
			<input type="hidden" id="completo" name="completo" value="1" />
		</form>
	</div> <!-- Div Fin Ventana -->

	<!-- Ventana de Promedios -->
	<div id="venProm" style="display:none" class="cssBus">
		<center>
			<table width="100%" class="tabEdi" cellpadding="3" border="0">
				<tr><th colspan="4" class="resaltar">&nbsp;</th></tr>
				<tr>
					<th class="resaltar">Total Mañana:</th>
					<td id="tdTotMan" align="right">0</td>
					<th class="resaltar">Total Tarde:</th>
					<td id="tdTotTar" align="right">0</td>
				</tr>
				<tr>
					<th class="resaltar">Total Noche:</th>
					<td id="tdTotNoc" align="right">0</td>
					<th class="resaltar">Total Madrugada:</th>
					<td id="tdTotMad" align="right">0</td>
				</tr>
				<tr><th colspan="4" class="resaltar">&nbsp;</th></tr>
				<tr><th class="LegendSt" colspan="4" style="background-color:#4CB877">PROMEDIOS POR DIA DE LA SEMANA</th></tr>
				<tr>
					<th class="resaltar">Lunes:</th>
					<td id="tdTotLun" align="right">0</td>
					<th class="resaltar">Martes:</th>
					<td id="tdTotMar" align="right">0</td>
				</tr>
				<tr>
					<th class="resaltar">Miercoles:</th>
					<td id="tdTotMie" align="right">0</td>
					<th class="resaltar">Jueves:</th>
					<td id="tdTotJue" align="right">0</td>
				</tr>
				<tr>
					<th class="resaltar">Viernes:</th>
					<td id="tdTotVie" align="right">0</td>
					<th class="resaltar">Sabado:</th>
					<td id="tdTotSab" align="right">0</td>
				</tr>
				<tr>
					<th class="resaltar">Domingo:</th>
					<td id="tdTotDom" align="right">0</td>
					<th class="resaltar"></th>
					<td></td>
				</tr>
			</table>
			<?php echo $_SESSION[APL]->getButtom('..','Cerrar', '100', 'onclick="CerrarProm()"'); ?>
		</center>
	</div>
</body>
<script>
	iniciarForma();
</script>
</html>