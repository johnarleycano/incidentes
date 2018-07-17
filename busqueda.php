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
$_SESSION[APL]->pagina_menu='busqueda.php';
echo $_SESSION[APL]->interfas->pestana(0);
?>

<script>

function filtrar(completo)
{
if(document.incidente.estado_sitio[3].checked==false && document.incidente.caracteristicas.value=='')
{
	alert('Para definir el estado del sitio, seleccione las carecteristicas del sitio')
	document.incidente.caracteristicas.focus();

}
else
{

	document.incidente.completo.value=completo;
	document.incidente.submit();
	//window.open('pagina4.php?abscisa='+document.estado_vias.abscisa.value+'&referencia='+document.estado_vias.referencia.value+'&via='+document.estado_vias.via.value,'_self');
}
}

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
		?>
		break;
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

</script>

<form name="incidente" method="post" action="busqueda.php" >
<center>
<?php
$sql="SELECT id,nombres,apellidos FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios 
where
id_perfil in (0,1)ORDER BY id";
$usu_reg=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombres,apellidos FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios 
where
id_perfil in (0,2)ORDER BY id";
$usu_sos=$_SESSION[APL]->bd->getRs($sql);

$sql="SELECT id,nombres,apellidos FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios 
where
id_perfil in (0,3)ORDER BY id";
$usu_adm=$_SESSION[APL]->bd->getRs($sql);
?>
<table>
<tr><th class="cab_grid" colspan="26">
INFORME GENERAL DE ATENCIONES
 </th>
</tr>
<tr><th  colspan="26" valign="middle">
<table width="100%">
<tr>
<th class="resaltar">
Codigo
</th>
<td>
<input type="text" name="codigo" value="<?php if(isset($_POST['codigo'])) echo $_POST['codigo']?>" class="campos"/>
</td>
<th class="resaltar">Fecha Reporte<br>
  (yyyy-mm-dd)</th>
<td  align="left" colspan="3">
Inicial <input class="campos" type="text" name="fecha_inicio" maxlength="10" size="12" value="<?php 
if(isset($_POST['fecha_inicio']))
	{
		echo $_POST['fecha_inicio'];
	}
	?>"/>

	<IMG align="absmiddle" width="34" alt="Seleccione la fecha" src="calendar/calendar.gif" border="0" name="imgCalendar" onClick="displayCalendar(document.incidente.fecha_inicio,'yyyy-mm-dd',this,true)" style="cursor:pointer" >
 Final <input class="campos" type="text" name="fecha_fin" maxlength="10" size="12" value="<?php 
if(isset($_POST['fecha_fin']))
{
		echo $_POST['fecha_fin'];	
	}
	?>"/>
	


	<IMG align="absmiddle" width="34" alt="Seleccione la fecha" src="calendar/calendar.gif" border="0" name="imgCalendar" onClick="displayCalendar(document.incidente.fecha_fin,'yyyy-mm-dd',this,true)" style="cursor:pointer"></td>
<th class="resaltar">
Cedula Lesionado
</th>
<td>
<input type="text" name="cedula" value="<?php if(isset($_POST['cedula'])) echo $_POST['cedula']?>" class="campos"/>
</td>	
	
</tr>
<tr>
<th class="resaltar">
Usuario Registra
</th>
<td>
<select name="usuario_registra" class="campos">
<option value=""></option>
<?php
$usu_reg->MoveFirst();
while(!$usu_reg->EOF)
{
	echo "<option value ='".$usu_reg->fields[0]."' ";
	if(isset($_POST['usuario_registra']) && $usu_reg->fields[0]==$_POST['usuario_registra'])
		echo "selected";
	echo ">".$usu_reg->fields[1]." ".$usu_reg->fields[2]."</option>";
	$usu_reg->MoveNext();
}

?>
</select>
</td>
<th class="resaltar">
Usuario SOS
</th>
<td>
<select name="usuario_sos" class="campos">
<option value=""></option>
<?php
$usu_sos->MoveFirst();
while(!$usu_sos->EOF)
{
	echo "<option value ='".$usu_sos->fields[0]."' ";
	if(isset($_POST['usuario_sos']) && $usu_sos->fields[0]==$_POST['usuario_sos'])
		echo "selected";
	echo ">".$usu_sos->fields[1]." ".$usu_sos->fields[2]."</option>";
	$usu_sos->MoveNext();
}

?>
</select>
</td>
<th class="resaltar">
Usuario Adm Vial
</th>
<td>
<select name="usuario_adm_vial" class="campos">
<option value=""></option>
<?php
$usu_adm->MoveFirst();
while(!$usu_adm->EOF)
{
	echo "<option value ='".$usu_adm->fields[0]."' ";
	if(isset($_POST['usuario_adm_vial']) && $usu_adm->fields[0]==$_POST['usuario_adm_vial'])
		echo "selected";
	echo ">".$usu_adm->fields[1]." ".$usu_adm->fields[2]."</option>";
	$usu_adm->MoveNext();
}

?>
</select>
</td>
<th class="resaltar">
Rango Edad
</th>
<td>
	Desde: 
	<input type="text" name="edad_d" value="<?php if(isset($_POST['edad_d'])) echo $_POST['edad_d']?>" class="campos" style="width:40px"/>
	Hasta: 
	<input type="text" name="edad_h" value="<?php if(isset($_POST['edad_h'])) echo $_POST['edad_h']?>" class="campos" style="width:40px"/>
<td>
</tr>
<tr>
<th class="resaltar">Entidad</th>
<td>
<select name="entidad" class="campos">
<option value=""></option>
<?php
$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo ORDER BY id";
$apo=$_SESSION[APL]->bd->getRs($sql);

while (!$apo->EOF) {
   	echo "<option value='".$apo->fields[0]."' ";
	if(isset($_POST['entidad']) && $_POST['entidad']==$apo->fields[0])
		echo "selected";
	echo ">".$apo->fields[1]."</option>";
    $apo->MoveNext();
}

?>
</select>
</td>
<th class="resaltar">Tipo Vehiculo</th>
<td>
<select name="id_tipo_vehiculo" id="id_tipo_vehiculo" class="campos">
<option value=""></option>
<?php
$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado ORDER BY id";
$tveh=$_SESSION[APL]->bd->getRs($sql);
while (!$tveh->EOF) {
   	echo "<option value='".$tveh->fields[0]."' ";
	if(isset($_POST['id_tipo_vehiculo']) && $_POST['id_tipo_vehiculo']==$tveh->fields[0])
		echo "selected";
	echo ">".$tveh->fields[1]."</option>";
    $tveh->MoveNext();
}
?>
</select>
</td>
<th class="resaltar">
Placa
</th>
<td>
<input type="text" name="placa" value="<?php if(isset($_POST['placa'])) echo $_POST['placa']?>" class="campos"/>
</td>
<th class="resaltar">
Aseguradora
</th>
<td>
<select name="id_aseguradora" class="campos">
<option value=""></option>
<?php

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_aseguradora ORDER BY id";
$aseg=$_SESSION[APL]->bd->getRs($sql);

while (!$aseg->EOF) {
   	echo "<option value='".$aseg->fields[0]."' ";
	if(isset($_POST['id_aseguradora']) && $_POST['id_aseguradora']==$aseg->fields[0])
		echo "selected";
	echo ">".$aseg->fields[1]."</option>";
    $aseg->MoveNext();
}

?>
</select>
</td>

</tr>

<tr>
<th class="resaltar">Via 
</th>
<td> <select name="via" class="campos" onchange="cargar_referencias(this.value)">
<option value=""></option>
<?php
$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via ORDER BY id";
$rs=$_SESSION[APL]->bd->getRs($sql);

while (!$rs->EOF) {
   	echo "<option value='".$rs->fields[0]."' ";
	if(isset($_POST['via']) && $_POST['via']==$rs->fields[0])
			echo "selected";
	echo ">".$rs->fields[1]."</option>";
    $rs->MoveNext();
}
$rs->close();
?>
</select>
</td>
<th class="resaltar">Referencia
</th>
<td><select name="referencia" class="campos" onchange="colocar_datos_referencia(this.value)">
<option value=""></option>
<?php
if(isset($_POST['referencia']) && $_POST['via']!='')
{
	$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia WHERE id_via=".$_POST['via']." ORDER BY referencia";
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
			
	
		echo "<option value='".$refe->fields[0]."|".$refe->fields[2]."|".$refe->fields[5]."' ";
		if($refe_r==$refe->fields[0])
				echo "selected";
		echo ">".$refe->fields[4]."</option>";
		$refe->MoveNext();
	}
	$rs->close();
	
}
?>

</select>

</td>
<th class="resaltar">Abscisa
</th>
<td><input type="text" name="abcisa" class="campos" value="<?php if(isset($_POST['abcisa'])) echo $abcisa?>" disabled="false"/>
</td>
<th class="resaltar">Tramo Ruta
</th>
<td><input type="text" name="tramo_ruta" class="campos" value="<?php if(isset($_POST['tramo_ruta'])) echo $tramo_ruta?>" disabled="false"/>
</td>
</tr>
<tr>
<th colspan="8" height="10">&nbsp;</th>
</tr>
<tr>
<th class="LegendSt" colspan="8">
Sitio Traslado Vehiculo
</th>
</tr>
<tr>
<th class="resaltar">
Parqueadero
</th>
<td>
<select name="id_parqueadero" class="campos">
<option value=""></option>
<?php

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_parqueadero ORDER BY id";
$par=$_SESSION[APL]->bd->getRs($sql);
while (!$par->EOF) {
   	echo "<option value='".$par->fields[0]."' ";
	if(isset($_POST['id_parqueadero']) && $_POST['id_parqueadero']==$par->fields[0])
		echo "selected";
	echo ">".$par->fields[1]."</option>";
    $par->MoveNext();
}

?>
</select>
</td>
<th class="resaltar">
Transito
</th>
<td>
<select name="id_transito" class="campos">
<option value=""></option>
<?php

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_transito ORDER BY id";
$tra=$_SESSION[APL]->bd->getRs($sql);
while (!$tra->EOF) {
   	echo "<option value='".$tra->fields[0]."' ";
	if(isset($_POST['id_transito']) && $_POST['id_transito']==$tra->fields[0])
		echo "selected";
	echo ">".$tra->fields[1]."</option>";
    $tra->MoveNext();
}
?>
</select>

</td>
<th class="resaltar">Taller</th>
<td>

<select name="id_taller" class="campos">
<option value=""></option>
<?php

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_taller ORDER BY id";
$tal=$_SESSION[APL]->bd->getRs($sql);
while (!$tal->EOF) {
   	echo "<option value='".$tal->fields[0]."' ";
	if(isset($_POST['id_taller']) && $_POST['id_taller']==$tal->fields[0])
		echo "selected";
	echo ">".$tal->fields[1]."</option>";
    $tal->MoveNext();
}
?>
</select>




</td>
<th class="resaltar">Otro Tras Veh</th>
<td>


<select name="id_otro_vehiculo" class="campos">
<option value=""></option>
<?php

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_vehiculo ORDER BY id";
$otr=$_SESSION[APL]->bd->getRs($sql);
while (!$otr->EOF) {
   	echo "<option value='".$otr->fields[0]."' ";
	if(isset($_POST['id_otro_vehiculo']) && $_POST['id_otro_vehiculo']==$otr->fields[0])
		echo "selected";
	echo ">".$otr->fields[1]."</option>";
    $otr->MoveNext();
}

?>
</select>


</td>

</tr>
<tr>
<th colspan="8" height="10">&nbsp;</th>
</tr>
<tr>
<th class="LegendSt" colspan="8">
Sitio Traslado Lesionado
</th>
</tr>

<tr>
<th class="resaltar">
Hospital
</th>
<td>
<select name="id_hospital" class="campos">
	<option value=""></option>
	<?php
	$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_hospital ORDER BY id";
	$hos=$_SESSION[APL]->bd->getRs($sql);
	while (!$hos->EOF) {
		echo "<option value='".$hos->fields[0]."' ";
		if(isset($_POST['id_hospital']) && $_POST['id_hospital']==$hos->fields[0])
			echo "selected";
		echo ">".$hos->fields[1]."</option>";
		$hos->MoveNext();
	}
	?>
	</select>

</td>
<th class="resaltar">
Clinica
</th>
<td>
<select name="id_clinica" class="campos">
<option value=""></option>
<?php
$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_clinica ORDER BY id";
$cli=$_SESSION[APL]->bd->getRs($sql);
while (!$cli->EOF) {
   	echo "<option value='".$cli->fields[0]."' ";
	if(isset($_POST['id_clinica']) && $_POST['id_clinica']==$cli->fields[0])
		echo "selected";
	echo ">".$cli->fields[1]."</option>";
    $cli->MoveNext();
}
?>
</select>
</td>
<th class="resaltar">
Centro de Salud
</th>
<td>
<select name="id_centro_salud" class="campos">
	<option value=""></option>
	<?php
	$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_centro_salud ORDER BY id";
	$censa=$_SESSION[APL]->bd->getRs($sql);
	while (!$censa->EOF) {
		echo "<option value='".$censa->fields[0]."' ";
		if(isset($_POST['id_centro_salud']) && $_POST['id_centro_salud']==$censa->fields[0])
			echo "selected";
		echo ">".$censa->fields[1]."</option>";
		$censa->MoveNext();
	}
	?>
	</select>
</td>
<th class="resaltar">
Otro Sitio
</th>
<td>
<select name="id_otro_lesionado" class="campos">
<option value=""></option>
<?php

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_lesionado ORDER BY id";
$otrl=$_SESSION[APL]->bd->getRs($sql);
while (!$otrl->EOF) {
   	echo "<option value='".$otrl->fields[0]."' ";
	if(isset($_POST['id_otro_lesionado']) && $_POST['id_otro_lesionado']==$otrl->fields[0])
		echo "selected";
	echo ">".$otrl->fields[1]."</option>";
    $otrl->MoveNext();
}
?>
</select>
</td>


</tr>
<tr>
<th class="resaltar">
Autoridad Interviene
</th>
<td>
<select name="autoridad" class="campos">
<option value=""></option>
<option value="P" <?php if(isset($_POST['autoridad']) && $_POST['autoridad']=='P') echo "selected"?> >Policia Transito y Transporte</option>
<option value="T" <?php if(isset($_POST['autoridad']) && $_POST['autoridad']=='T') echo "selected"?> >Transito</option>
<option value="I" <?php if(isset($_POST['autoridad']) && $_POST['autoridad']=='I') echo "selected"?> >Inspector</option>
</select>
</td> 
<th class="resaltar">
Placa Aut
</th>
<td>
<input type="text" name="placa_a" value="<?php if(isset($_POST['placa_a'])) echo $_POST['placa_a']?>" />
</td>
<th class="resaltar">
Apellido Aut
</th>
<td>
<input type="text" name="apellido_a" value="<?php if(isset($_POST['apellido_a'])) echo $_POST['apellido_a']?>" />
</td>
</tr>
<tr>
<th class="resaltar">Caracteristicas Sitio
</th>
<td>
<select name="caracteristicas" class="campos">
<option value=""></option>
<option value="H" <?php if(isset($_POST['caracteristicas']) && $_POST['caracteristicas']=='H') echo "selected"?>>Señalizacion Hotizontal</option>
<option value="V" <?php if(isset($_POST['caracteristicas']) && $_POST['caracteristicas']=='V') echo "selected"?>>Señalizacion Vertical</option>
<option value="G" <?php if(isset($_POST['caracteristicas']) && $_POST['caracteristicas']=='G') echo "selected"?>>Mantenimiento General</option>
<option value="R" <?php if(isset($_POST['caracteristicas']) && $_POST['caracteristicas']=='R') echo "selected"?>>Rodadura</option>
</select>
</td>
<th class="resaltar">Estado Sitio
</th>
<td colspan="3">
Bueno
<input name="estado_sitio" type="radio" class="campos" value="B" <?php if(isset($_POST['estado_sitio']) && $_POST['estado_sitio']=='B') echo "checked"?>/>
Regular
<input name="estado_sitio" type="radio" class="campos" value="R" <?php if(isset($_POST['estado_sitio']) && $_POST['estado_sitio']=='R') echo "checked"?>/>
Malo
<input name="estado_sitio" type="radio" class="campos" value="M" <?php if(isset($_POST['estado_sitio']) && $_POST['estado_sitio']=='M') echo "checked"?>/>
Todos
<input name="estado_sitio" type="radio" class="campos" value="" <?php if(!isset($_POST['estado_sitio']) || (isset($_POST['estado_sitio']) && $_POST['estado_sitio']=='')) echo "checked"?>/>
</td>
</tr>

<tr>
<th class="resaltar">Tipo de Atencion
</th>
<td><select name="tipo_atencion" class="campos">
<option value="">*</option>
<?php
$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion ORDER BY id";
$rs=$_SESSION[APL]->bd->getRs($sql);

while (!$rs->EOF) {
   	echo "<option value='".$rs->fields[0]."' ";
	if(isset($_POST['tipo_atencion']) && $_POST['tipo_atencion']==$rs->fields[0])
			echo "selected";
	echo ">".$rs->fields[1]."</option>";
    $rs->MoveNext();
}
$rs->close();
?>
</select></td>
<th class="resaltar">Informado por:
</th>
<td>
<select name="informado_por" class="campos"> 
<option value="">*</option>



<?php

$sql="SELECT id,nombre FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado ORDER BY id";
$aseg=$_SESSION[APL]->bd->getRs($sql);

while (!$aseg->EOF) {
   	echo "<option value='".$aseg->fields[0]."' ";
	if(isset($_POST['informado_por']) && $_POST['informado_por']==$aseg->fields[0])
		echo "selected";
	echo ">".$aseg->fields[1]."</option>";
    $aseg->MoveNext();
}

?>


</select>
</td>
</tr>
<tr>
<th class="resaltar">
Estado SOS
</th>
<td>

<select name="estado_sos" class="campos">
<option value="">Todos</option>
<option value="P" <?php if(isset($_POST['estado_sos']) && $_POST['estado_sos']=='P') echo "selected"?>>Pendiente</option>
<option value="G" <?php if(isset($_POST['estado_sos']) && $_POST['estado_sos']=='G') echo "selected"?>>Guardado</option>
<option value="F" <?php if(isset($_POST['estado_sos']) && $_POST['estado_sos']=='F') echo "selected"?>>Finalizado</option>
</select>
</td>
<th class="resaltar">
Estado Adm Vial
</th>
<td>
<select name="estado_adm_vial" class="campos">
<option value="">Todos</option>
<option value="P" <?php if(isset($_POST['estado_adm_vial']) && $_POST['estado_adm_vial']=='P') echo "selected"?>>Pendiente</option>
<option value="G" <?php if(isset($_POST['estado_adm_vial']) && $_POST['estado_adm_vial']=='G') echo "selected"?>>Guardado</option>
<option value="F" <?php if(isset($_POST['estado_adm_vial']) && $_POST['estado_adm_vial']=='F') echo "selected"?>>Finalizado</option>
</select>
</td>
</tr>
<tr>
<th class="LegendSt" colspan="8">
Ambulancia, Grua, Tipo Lesionado
</th>
</tr>
<tr>
<th>
Ambulancia
</th>
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
		
	}

	echo ">".$amb->fields[1]."</option>";
    $amb->MoveNext();
}

?>
</select>
</td>
<th>
Grua
</th>
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
</select>


</td>
<th>
Tipo Lesionado</th>
<td colspan="3">
Conductor
<input type='checkbox' name='conductor' value='SI' <?php if(isset($_POST['conductor'])) echo "checked"?>>
Lesionado
<input type='checkbox' name='lesionado' value='SI' <?php if(isset($_POST['lesionado'])) echo "checked"?>>
Muerto
<input type='checkbox' name='muerto' value='SI' <?php if(isset($_POST['muerto'])) echo "checked"?>>
</td>
</tr>


<tr>
<th colspan="8" height="10">&nbsp;</th>
</tr>
<tr><td colspan="8" align="center">
<?php 
echo $_SESSION[APL]->getButtom('.','Buscar Completo', '100', 'onclick="filtrar(1)"');
echo $_SESSION[APL]->getButtom('.','Buscar Resumido', '100', 'onclick="filtrar(0)"');
?>
<input type="hidden" name="completo" value="1" />
</td></tr>
<tr>
<th colspan="8" height="10">&nbsp;</th>
</tr>



</table>
</th>
</tr>

<?php if(!isset($_POST['completo']) || (isset($_POST['completo']) && $_POST['completo']==1))
{?>
<tr>
<!--<th class="style4">Indicador</th>-->
<th class="LegendSt">id</th>
<th class="LegendSt">Docs</th>
<th class="LegendSt">Fecha</th>
<th class="LegendSt">Dia</th>
<th class="LegendSt">Hora Reporte</th>
<th class="LegendSt">Hora Llegada</th>
<th class="LegendSt">Duracion Evento</th>
<th class="LegendSt">Abscisa</th>
<th class="LegendSt">Referencia</th>
<th class="LegendSt">Via</th>
<th class="LegendSt">Informado por</th>
<th class="LegendSt">Tipo Atencion</th>
<th class="LegendSt">Nro Muertos</th>
<th class="LegendSt">Nro Heridos</th>

<th class="LegendSt">Ambulancia</th>
<th class="LegendSt">Grua</th>

<th class="LegendSt" >Vehiculo Involucrado</th>
<th class="LegendSt">Placth>
<th class="LegendSt">Nombre Usuarias</o</th>
<th class="LegendSt">Identificacion Usuario</th>

<th class="LegendSt">Sitio Traslado Vehiculo</th>
<th class="LegendSt">Sitio Traslado Usuario</th>

<th class="LegendSt">Observaciones</th>



</tr>


<?php
}
else
{
?>

<tr>
<!--<th class="style4">Indicador</th>-->
<th class="LegendSt">id</th>
<th class="LegendSt">Docs</th>
<th class="LegendSt" >Fecha</th>
<th class="LegendSt">Dia</th>
<th class="LegendSt">Hora Reporte</th>
<th class="LegendSt">Hora Llegada</th>
<th class="LegendSt" colspan="2">Duracion Evento</th>
<th class="LegendSt" colspan="2">Abscisa</th>
<th class="LegendSt" colspan="2">Referencia</th>
<th class="LegendSt" colspan="2">Via</th>
<th class="LegendSt"  colspan="2">Informado por</th>
<th class="LegendSt" colspan="2">Tipo Atencion</th>
<th class="LegendSt">Nro Muertos</th>
<th class="LegendSt">Nro Heridos</th>
<th class="LegendSt">Observaciones</th>
</tr>

<?php
	
}

if(!isset($_POST['completo']) || (isset($_POST['completo']) && $_POST['completo']==1))
{
	$campos="i.id,
	i.codigo,
	i.periodo,
	date_format(i.fecha,'%e-%c-%Y'),
	i.hora_reporte,
	i.hora_llegada,
	i.tiempo,
	case 
	when abscisa_real!='' then abscisa_real
	else 
	r.abscisa 
	end abscisa,
	r.referencia referencia,
	v.nombre via,
	i.informado_por,
	ta.nombre tipo_atencion,
	i.nro_muertos,
	i.nro_heridos,
	tv.nombre vehiculo_involucrado,
	vi.placa_vehiculo,
	lv.nombre,
	lv.cedula,
	par.nombre,
	tra.nombre,
	tal.nombre,
	otv.nombre,
	hos.nombre,
	csa.nombre,
	cli.nombre,
	otl.nombre,
	lv.observaciones,
	hora_llegada_sitio,
	hora_salida_base,
	hora_llegada_base,
	inf.nombre,
	amb.nombre,
	gr.nombre";
	$group="";
}
else
{
		$campos="i.id,
	i.codigo,
	i.periodo,
	date_format(i.fecha,'%e-%c-%Y'),
	i.hora_reporte,
	i.hora_llegada,
	i.tiempo,
	case 
	when abscisa_real!='' then abscisa_real
	else 
	r.abscisa 
	end abscisa,
	r.referencia referencia,
	v.nombre via,
	i.informado_por,
	ta.nombre tipo_atencion,
	i.nro_muertos,
	i.nro_heridos,
	hora_llegada_sitio,
	hora_salida_base,
	hora_llegada_base,
	inf.nombre,
	i.observaciones";
	$group=" group by i.id,
	i.codigo,
	i.periodo,
	i.fecha,
	i.hora_reporte,
	i.hora_llegada,
	i.tiempo,
	r.abscisa,
	r.referencia,
	v.nombre,
	i.informado_por,
	ta.nombre,
	i.nro_muertos,
	i.nro_heridos,
	hora_llegada_sitio,
	hora_salida_base,
	hora_llegada_base,
	inf.nombre,
	i.observaciones";
}


if(isset($_POST['codigo']))
{

$sql="SELECT 
".$campos."

FROM
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente as vi on (vi.id_incidente=i.id) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado as inf on  (inf.id=i.informado_por) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_parqueadero as par on (par.id=vi.id_parqueadero) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_transito as tra on (tra.id=vi.id_transito) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_taller as tal on (tal.id=vi.id_taller) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_vehiculo as otv on (otv.id=vi.id_otro_vehiculo) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado  as tv on (tv.id=vi.id_tipo_vehiculo) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_grua  as gr on (gr.id=vi.id_grua) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo as lv on (vi.id_vehiculo=lv.id_vehiculo) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_hospital as hos on (hos.id =lv.id_hospital) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_centro_salud as csa on  (csa.id=lv.id_centro_salud) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_clinica as cli on (cli.id=lv.id_clinica) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia as amb on (amb.id=lv.id_trasladado_por) left outer join
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_lesionado as otl on  (otl.id=lv.id_otro_lesionado),
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia as r,
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion as ta,
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via v,
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_estado as e
WHERE
i.tipo_atencion=ta.id and
i.referencia=r.id and
i.via=v.id and
i.tipo_atencion=ta.id and
i.estado=e.id ";

if(isset($_POST['tipo_atencion']) && $_POST['tipo_atencion']!='')
{
	$sql.=" and i.tipo_atencion=".$_POST['tipo_atencion']." ";
}
if(isset($_POST['caracteristicas']) && $_POST['caracteristicas']!='')
{
	if($_POST['estado_sitio']!='')
	{
		if($_POST['caracteristicas']=='H')
			$sql.=" and señalizacion_horizontal='".$_POST['estado_sitio']."' ";
		else
		if($_POST['caracteristicas']=='V')
			$sql.=" and señalizacion_vertical='".$_POST['estado_sitio']."' ";
		else
		if($_POST['caracteristicas']=='G')
			$sql.=" and mantenimiento_gral='".$_POST['estado_sitio']."' ";
		else
		if($_POST['caracteristicas']=='R')
			$sql.=" and rodadura='".$_POST['estado_sitio']."' ";
	
	}
	
}
if(isset($_POST['estado_sos']) && $_POST['estado_sos']!='')
{
	if($_POST['estado_sos']=='P')
		$sql.=" and guardado_sos=0 and finalizado_sos=0";
	else
	if($_POST['estado_sos']=='G')
		$sql.=" and guardado_sos=1 ";
	else
		$sql.=" and finalizado_sos=1 ";
}
if(isset($_POST['estado_adm_vial']) && $_POST['estado_adm_vial']!='')
{
	if($_POST['estado_adm_vial']=='P')
		$sql.=" and guardado_adm_vial=0 and  finalizado_adm_vial=0";
	else
	if($_POST['estado_adm_vial']=='G')
		$sql.=" and guardado_adm_vial=1 ";
	else
		$sql.=" and finalizado_adm_vial=1 ";
}
if(isset($_POST['autoridad']) && $_POST['autoridad']!='')
{
	if($_POST['autoridad']=='P')
		$sql.=" and policia='SI' ";
	if($_POST['autoridad']=='T')
		$sql.=" and transito='SI' ";
	else
		$sql.=" and inspector='SI' ";
}
if(isset($_POST['placa_a']) && $_POST['placa_a']!='')
{
	$sql.=" and (upper(policia_placa)='".strtoupper($_POST['placa_a'])."' or upper(transito_placa)='".strtoupper($_POST['placa_a'])."' or upper(inspector_placa)='".strtoupper($_POST['placa_a'])."')";
}
if(isset($_POST['apellido_a']) && $_POST['apellido_a']!='')
{
	$sql.=" and (upper(policia_apellido)='".strtoupper($_POST['apellido_a'])."' or upper(transito_apellido)='".strtoupper($_POST['apellido_a'])."' or upper(inspector_apellido)='".strtoupper($_POST['apellido_a'])."')";
}
if(isset($_POST['id_tipo_vehiculo']) && $_POST['id_tipo_vehiculo']!='')
{
	//$sql.=" and i.id in (select id_incidente from  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente  where   id_tipo_vehiculo=".$_POST['id_tipo_vehiculo'].") ";
	$sql.=" and vi.id_tipo_vehiculo=".$_POST['id_tipo_vehiculo']." ";
}
if(isset($_POST['placa']) && $_POST['placa']!='')
{
	//$sql.=" and i.id in (select id_incidente from  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente  where   upper(placa_vehiculo)='".strtoupper($_POST['placa'])."') ";
	$sql.=" and upper(vi.placa_vehiculo)='".strtoupper($_POST['placa'])."' ";
}
if(isset($_POST['id_aseguradora']) && $_POST['id_aseguradora']!='')
{
	//$sql.=" and i.id in (select id_incidente from  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente  where   id_aseguradora=".$_POST['id_aseguradora'].") ";
	$sql.=" and vi.id_aseguradora=".$_POST['id_aseguradora']." ";
}
if(isset($_POST['id_parqueadero']) && $_POST['id_parqueadero']!='')
{
	//$sql.=" and i.id in (select id_incidente from  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente  where   id_parqueadero=".$_POST['id_parqueadero'].") ";
	$sql.=" and vi.id_parqueadero=".$_POST['id_parqueadero']." ";
}
if(isset($_POST['id_transito']) && $_POST['id_transito']!='')
{
	$sql.=" and vi.id_transito=".$_POST['id_transito']." ";
}
if(isset($_POST['id_taller']) && $_POST['id_taller']!='')
{
	$sql.=" and vi.id_taller=".$_POST['id_taller']." ";
}
if(isset($_POST['id_otro_vehiculo']) && $_POST['id_otro_vehiculo']!='')
{
	$sql.=" and vi.id_otro_vehiculo=".$_POST['id_otro_vehiculo']." ";
}
if(isset($_POST['cedula']) && $_POST['cedula']!='')
{
	/*$sql.=" and i.id in 
			(
				select id_incidente from  ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente  
				where id_vehiculo
				in
				(
					select id_vehiculo
					from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo
					where cedula='".$_POST['cedula']."'
				)
			) ";*/
	$sql.=" and lv.cedula='".$_POST['cedula']."' ";
}
if(isset($_POST['id_hospital']) && $_POST['id_hospital']!='')
{
	$sql.=" and lv.id_hospital=".$_POST['id_hospital']." ";
}
if(isset($_POST['id_clinica']) && $_POST['id_clinica']!='')
{
	$sql.=" and lv.id_clinica=".$_POST['id_clinica']." ";
}
if(isset($_POST['id_centro_salud']) && $_POST['id_centro_salud']!='')
{
	$sql.=" and lv.id_centro_salud=".$_POST['id_centro_salud']." ";
}
if(isset($_POST['conductor']))
{
	$sql.=" and lv.conducia='SI' ";
}
if(isset($_POST['muerto']))
{
	$sql.=" and lv.muerto='SI' ";
}
if(isset($_POST['lesionado']))
{
	$sql.=" and lv.lesionado='SI' ";
}
if(isset($_POST['id_ambulancia']) && $_POST['id_ambulancia']!='')
{
	$sql.=" and lv.id_trasladado_por=".$_POST['id_ambulancia']." ";
}
if(isset($_POST['edad_d']) && $_POST['edad_d']!='')
{
	$sql.=" and LPAD(lv.edad,3,'0')>='".str_pad($_POST['edad_d'], 3, '0', STR_PAD_LEFT)."'";
}
if(isset($_POST['edad_h']) && $_POST['edad_h']!='')
{
	$sql.=" and LPAD(lv.edad,3,'0')<='".str_pad($_POST['edad_h'], 3, '0', STR_PAD_LEFT)."'";
}
if(isset($_POST['id_grua']) && $_POST['id_grua']!='')
{
	$sql.=" and vi.id_grua=".$_POST['id_grua']." ";
}
if(isset($_POST['id_otro_lesionado']) && $_POST['id_otro_lesionado']!='')
{
	$sql.=" and lv.id_otro_lesionado=".$_POST['id_otro_lesionado']." ";
}
if(isset($_POST['entidad']) && $_POST['entidad']!='')
{
	$sql.=" and i.id in (select id_incidente from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_apoyo_entidad where id_entidad=".$_POST['entidad']." ) ";
}
if(isset($_POST['usuario_registra']) && $_POST['usuario_registra']!='')
{
	$sql.=" and i.id in (select id_incidente from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente WHERE estado=1 and usuario=".$_POST['usuario_registra'].") ";
}
if(isset($_POST['usuario_sos']) && $_POST['usuario_sos']!='')
{
	$sql.=" and i.id in (select id_incidente from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente WHERE estado in (2,3) and usuario=".$_POST['usuario_sos'].") ";
}
if(isset($_POST['usuario_adm_vial']) && $_POST['usuario_adm_vial']!='')
{
	$sql.=" and i.id in (select id_incidente from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_historial_incidente WHERE estado in (4,5) and usuario=".$_POST['usuario_adm_vial'].") ";
}
if(isset($_POST['fecha_inicio']) && $_POST['fecha_inicio']!='' && $_POST['fecha_fin']!='')
{
		$sql.=" and fecha between  '".$_POST['fecha_inicio']."' and '".$_POST['fecha_fin']."' ";	
}
if(isset($_POST['codigo']) && $_POST['codigo']!='' )
{
	//$refe=explode(".",$_POST['codigo']);
	
	
	$sql.=" and concat(i.periodo,'.',LPAD(i.codigo,5,'0')) like '".$_POST['codigo']."'";
}
if(isset($_POST['referencia']) && $_POST['referencia']!='' )
{
	$refe=explode("|",$_POST['referencia']);
	$sql.=" and i.referencia=".$refe[0]." ";
}
if(isset($_POST['via']) && $_POST['via']!='' )
	$sql.=" and i.via=".$_POST['via']." ";
	
if(isset($_POST['informado_por']) && $_POST['informado_por']!='')
	$sql.=" and informado_por='".$_POST['informado_por']."' ";

$sql.=" ".$group." ORDER BY i.id asc";
 //echo $sql;

$rs=$_SESSION[APL]->bd->getRs($sql);
$cant_e=$rs->NumRows();
//$cant_e=0;
}
else
$cant_e=0;
$i=0;

	echo "<tr><th colspan='20' height='20px'><center>Encontrados ".$cant_e." Registros</center></th></tr>";
$total_muertos=0;
$total_heridos=0;
$id_inci=0;

if(isset($rs) && $rs->NumRows()>0)
{
$tmp=$rs->fields[0];


if(!isset($_POST['completo']) || (isset($_POST['completo']) && $_POST['completo']==1))
	$dato='Codigo;'.
	'Fecha;'.
	'Hora Reporte;'.
	'Hora Llegada;'.
	'Duracion Evento;'.
	'Abscisa;'.
	'Referencia;'.
	'Via;'.
	'Informado por;'.
	'Tipo Atencion;'.
	'Nro Muertos;'.
	'Nro_heridos;'.	
	'Ambulancia;'.	
	'Grua;'.	
	'Vehiculo Involucrado;'.
	'Placas;'.
	'Nombre Usuario;'.
	'Identificacion Usuario;'.
	'Sitio Traslado Vehiculo;'.
	'Sitio Traslado Usuario;'.
	'Observaciones'."\r\n";
else
	$dato='Codigo;'.
	'Fecha;'.
	'Hora Reporte;'.
	'Hora Llegada;'.
	'Duracion Evento;'.
	'Abscisa;'.
	'Referencia;'.
	'Via;'.
	'Informado por;'.
	'Tipo Atencion;'.
	'Nro Muertos;'.
	'Nro_heridos;'.
	'Observaciones'."\r\n";
	
	$id_inci=0;
	$datos_linea=0;
while (!$rs->EOF) {
/*******************CALCULAR TIEMPO EVENTO****************/
	if(!isset($_POST['completo']) || (isset($_POST['completo']) && $_POST['completo']==1))
	{
		$hora_salida_base=explode(":",$rs->fields[28]);
		$hora_llegada_base=explode(":",$rs->fields[29]);
	}
	else
	{
		$hora_salida_base=explode(":",$rs->fields[15]);
		$hora_llegada_base=explode(":",$rs->fields[16]);
	}
		
		
	if(count($hora_salida_base)>1)
	{
		$hora_salida_base_h=$hora_salida_base[0];
		$hora_salida_base_m=$hora_salida_base[1];
	}
	else
	{
		$hora_salida_base_h="";
		$hora_salida_base_m="";
	}



	if(count($hora_llegada_base)>1)
	{	
		$hora_llegada_base_h=$hora_llegada_base[0];
		$hora_llegada_base_m=$hora_llegada_base[1];
	}
	else
	{
		$hora_llegada_base_h="";
		$hora_llegada_base_m="";
	}
	
	

if($hora_llegada_base_h!='' && $hora_llegada_base_m!='' && $hora_salida_base_h!='' && $hora_salida_base_m!='')
	{
		$hora_s=$hora_llegada_base_h;
		$hora_r=$hora_salida_base_h;
		
		$minu_s=$hora_llegada_base_m;
		$minu_r=$hora_salida_base_m;
		if($minu_r>$minu_s)
		{
			$minu_s=$minu_s+60;	
			$hora_s=$hora_s-1;
		}

		$horas=$hora_s-$hora_r;
		$minus=$minu_s-$minu_r;
		if($horas<0)
		{
			$tiempo_total='00:00';	
		}
		else
			$tiempo_total=str_pad($horas,2,'0',STR_PAD_LEFT).":".str_pad($minus,2,'0',STR_PAD_LEFT);
		
}
else
	$tiempo_total='00:00';	






	if($i%2==0)
   		echo "<tr bgcolor='#CCCCCC'>";
	else
		echo "<tr bgcolor='#FFFFFF'>";
	/*if($rs->fields[19]!='')
		echo "<td class='style1'><img src='pag/img/se_".$rs->fields[19].".png'></td>";
	else
		echo "<td class='style1'>N/A</td>";*/
	
	if($id_inci==0 || $id_inci!=$rs->fields[2].$rs->fields[1])
	{
		$total_muertos+=$rs->fields[12];
		$total_heridos+=$rs->fields[13];
		$id_inci=$rs->fields[2].$rs->fields[1];
		$datos_linea=1;
	}
	else
		$datos_linea=0;
		
	if($datos_linea==1)
	{	
	echo "<td class='style1'>".$rs->fields[2].".".str_pad($rs->fields[1],5,"0",STR_PAD_LEFT)."</td>";
	echo "<td class='style1'>";
	?>
	<img src="img/popup.png"style="cursor:pointer" title="REPORTE ACCIDENTE" alt="REPORTE ACCIDENTE" onclick="window.open('reporte_1.php?id_buscar=<?php echo $rs->fields[0]?>','_blank')" />
	<img src="img/popup.png"style="cursor:pointer" title="INFORME ADMINISTRADOR VIAL DE  EVENTUALIDADES SOBRE LA VÍA" alt="INFORME ADMINISTRADOR VIAL DE  EVENTUALIDADES SOBRE LA VÍA" onclick="window.open('reporte_2.php?id_buscar=<?php echo $rs->fields[0]?>','_blank')" />
	<?php
	echo "</td>";
	}
	else
	{
		echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
	}
	





	
	if(!isset($_POST['completo']) || (isset($_POST['completo']) && $_POST['completo']==1))
	{
	
		if($datos_linea==1)
		{
		echo "<td class='style1'>".$rs->fields[3]."</td>";
		echo "<td class='style1'>".$_SESSION[APL]->getDiaSemana($rs->fields[3])."</td>";
		echo "<td class='style1'>".$rs->fields[4]."</td>";
		echo "<td class='style1'>".$rs->fields[27]."</td>";
		echo "<td class='style1'>".$tiempo_total."</td>";
		echo "<td class='style1'>".$rs->fields[7]."</td>";
		
		echo "<td class='style1'>".$rs->fields[8]."</td>";
		echo "<td class='style1'>".$rs->fields[9]."</td>";
		echo "<td class='style1'>".$rs->fields[30]."</td>";
	
		echo "<td class='style1'>".$rs->fields[11]."</td>";
		echo "<td class='style1'>".$rs->fields[12]."</td>";
		echo "<td class='style1'>".$rs->fields[13]."</td>";
		}
		else
		{
			echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
		
			echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
	
			echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
		}
			
			
		echo "<td class='style1'>".$rs->fields[31]."</td>";
		echo "<td class='style1'>".$rs->fields[32]."</td>";
	
		echo "<td class='style1'>".$rs->fields[14]."</td>";
		echo "<td class='style1'>".$rs->fields[15]."</td>";
		echo "<td class='style1'>".$rs->fields[16]."</td>";
		echo "<td class='style1'>".$rs->fields[17]."</td>";
		

		
		echo "<td class='style1'>";
		if($rs->fields[18]!='')
			$vehi= $rs->fields[18];
		else
		if($rs->fields[19]!='')
			$vehi= $rs->fields[19];
		else
		if($rs->fields[20]!='')
			$vehi= $rs->fields[20];
		else
		if($rs->fields[21]!='')
			$vehi= $rs->fields[21];
		else
			$vehi= "";
		echo $vehi."</td>";
		
		
		echo "<td class='style1'>";
		if($rs->fields[22]!='')
			$lesi= $rs->fields[22];
		else
		if($rs->fields[23]!='')
			$lesi= $rs->fields[23];
		else
		if($rs->fields[24]!='')
			$lesi= $rs->fields[24];
		else
		if($rs->fields[25]!='')
			$lesi= $rs->fields[25];
		else
			$lesi= "";
		echo $lesi."</td>";
		echo "<td class='style1'>".$rs->fields[26]."</td>";
	}
	else
	{
		echo "<td class='style1'>".$rs->fields[3]."</td>";
		echo "<td class='style1'>".$_SESSION[APL]->getDiaSemana($rs->fields[3])."</td>";
		echo "<td class='style1'>".$rs->fields[4]."</td>";
		echo "<td class='style1'>".$rs->fields[14]."</td>";
		echo "<td class='style1' colspan='2'>".$tiempo_total."</td>";
		echo "<td class='style1' colspan='2'>".$rs->fields[7]."</td>";
		
		echo "<td class='style1' colspan='2'>".$rs->fields[8]."</td>";
		echo "<td class='style1' colspan='2'>".$rs->fields[9]."</td>";
		echo "<td class='style1' colspan='2'>".$rs->fields[16]."</td>";
	
		echo "<td class='style1' colspan='2'>".$rs->fields[11]."</td>";
		echo "<td class='style1'>".$rs->fields[12]."</td>";
		echo "<td class='style1'>".$rs->fields[13]."</td>";
		echo "<td class='style1'>".$rs->fields[18]."</td>";
	}
	echo "</tr>";


	if(!isset($_POST['completo']) || (isset($_POST['completo']) && $_POST['completo']==1))
	{
	
		if($datos_linea==1)
		{
			$linea="'".$rs->fields[2].".".str_pad($rs->fields[1],5,"0",STR_PAD_LEFT)."';".
			$rs->fields[3].";".
			$rs->fields[4].";".
			$rs->fields[27].";".
			$tiempo_total.";".
			$rs->fields[7].";".
			$rs->fields[8].";".
			$rs->fields[9].";".
			$rs->fields[30].";".
			$rs->fields[11].";".
			$rs->fields[12].";".
			$rs->fields[13].";";
		}
		else
		{
			$linea=";;;;;;;;;;;;";
		}	
		$linea.=$rs->fields[31].";".
		$rs->fields[32].";".
		$rs->fields[14].";".
		$rs->fields[15].";".
		$rs->fields[16].";".
		$rs->fields[17].";".
		

		$vehi.";".
		$lesi.";".
		$rs->fields[26];
		$dato.=$linea."\r\n";
	}	
	else
	{
		$linea="'".$rs->fields[2].".".str_pad($rs->fields[1],5,"0",STR_PAD_LEFT)."';".
		$rs->fields[3].";".
		$rs->fields[4].";".
		
		$rs->fields[14].";".
		$tiempo_total.";".
		$rs->fields[7].";".
		$rs->fields[8].";".
		$rs->fields[9].";".
		$rs->fields[17].";".
		$rs->fields[11].";".
		$rs->fields[12].";".
		$rs->fields[13].";".
		$rs->fields[18];
		$dato.=$linea."\r\n";
	}
	
	
	
	$i++;
    $rs->MoveNext();
	if(!$rs->EOF && $tmp!=$rs->fields[0])
	{
		echo "<tr><td colspan='24'><hr></td></tr>";
		$tmp=$rs->fields[0];
	}
	
	
}// Fin while

}
if(isset($rs))
	$rs->close();
	if($cant_e>0)
	{
	if(substr($dato,-4,4)=='\r\n')
		$dato=substr($dato,0,-4);
	if(file_exists("../adjuntos/reporte.csv") )
	{
		$fp = fopen("../adjuntos/reporte.csv", "w");
		fwrite($fp,$dato);
		fclose($fp);
	}
echo "<tr><td colspan='24'><hr></td></tr>";

if(!isset($_POST['completo']) || (isset($_POST['completo']) && $_POST['completo']==1))
	echo "<tr><td colspan='11' heigth='20px'></td><th>TOTAL MUERTOS<br>".$total_muertos."</th><th>TOTAL HERIDOS<br>".$total_heridos."</th><td colspan='7'></td></tr>";
else
	echo "<tr><td colspan='17' heigth='20px'></td><th>TOTAL MUERTOS<br>".$total_muertos."</th><th>TOTAL HERIDOS<br>".$total_heridos."</th><td ></td></tr>";

?>


<tr>
<th colspan="24" align="center">
<img src="img/excel.png"  style="cursor:pointer" alt="Ver Archivo" title="Ver Archivo" onclick="window.open('descargar.php?adjunto=adjuntos/reporte.csv','_blank')"/>
</th>
</tr>
<?php }?>
</table>
</center>
</form>
</body>
</html>
