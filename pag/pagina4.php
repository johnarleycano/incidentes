<?php 
include("../adodb.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<script>

function filtrar()
{
	document.estado_vias.submit();
	//window.open('pagina4.php?abscisa='+document.estado_vias.abscisa.value+'&referencia='+document.estado_vias.referencia.value+'&via='+document.estado_vias.via.value,'_self');
}
</script>
</head>

<body>
<center>
<form name="estado_vias" method="post">
<table width="1000px" cellspacing="0" height="400px" cellpadding="0">
<tr>
<td  align="left" valign="top" >
<table>
<tr><td colspan="11">
<center>
<table>
<tr><th colspan="6">Leyendas e Indicadores</th></tr>
<tr>
<td><img src="img/se_v.png" /></td><td align="left">Via sin ningún tipo de problema en su recorrido.</td>
<td><img src="img/se_a.png" /></td><td align="left">Via con alguna restricción en su recorrido.</td>
<td><img src="img/se_r.png" /></td><td align="left">Via que presenta problemas en su recorrido.</td></tr>
<td colspan="6" align="center">Cualquier información comunicarse con su línea amiga<br />
<b>
01 8000 51 8427</b></td>

</table>
</center>
</td></tr>
<tr><td colspan="11">
Filtrar: &nbsp; 
Via:
<select name="via" class="campos" >
<option value=""></option>
<?php
$sql="SELECT * FROM ".$_SESSION['bname'].".dvm_via ORDER BY nombre";
$rs=conectarse($sql);

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


<input type="button" value="Filtrar" onclick="filtrar()"/>

</td></tr>
<tr>
<th >Indicador</th>
<th >id</th>
<th >Fecha</th>
<th >Hora Reporte</th>
<th >Abscisa</th>
<th >Referencia</th>
<th >Via</th>
<th >Tipo Atencion</th>
<th >Tiempo Estimado Apertura</th>

</tr>
<?php
$sql="SELECT 
i.id,
date_format(i.fecha,'%d-%m-%Y'),
i.hora_reporte,
r.referencia referencia,
v.nombre via,
ta.nombre tipo_atencion,
i.tiempo_apertura,
i.tipo_incidente,
r.abscisa,
i.abscisa_real
FROM
".$_SESSION['bname'].".dvm_incidente i,
".$_SESSION['bname'].".dvm_referencia r,
".$_SESSION['bname'].".dvm_tipo_atencion ta,
".$_SESSION['bname'].".dvm_via v
WHERE
i.visualizar_web ='SI' and

i.referencia=r.id and
i.via=v.id and
i.tipo_atencion=ta.id  ";
if(isset($_POST['via']) && $_POST['via']!='' )
	$sql.=" and i.via=".$_POST['via']." ";
	

$sql.=" ORDER BY id DESC" ;


$rs=conectarse($sql);
$i=0;
if($rs->NumRows()==0)
	echo "<tr bgcolor='#CCCCCC'>
	<td ><img src='img/se_v.png'></td>
	<td >&nbsp;</td>
	<td >".date('d-m-Y')."</td>
	<td >".date('H:i')."</td>
	<td >&nbsp;</td>
	<td >&nbsp;</td>
	<td >&nbsp;</td>
	
	<td colspan=2>Sin Novedad en la Via</td></tr>";




else
while (!$rs->EOF) {
	if($i%2==0)
   		echo "<tr bgcolor='#CCCCCC'>";
	else
		echo "<tr bgcolor='#FFFFFF'>";
	echo "<td ><img src='img/se_".$rs->fields[7].".png'></td>";	
	echo "<td >".$rs->fields[0]."</td>";
	echo "<td >".$rs->fields[1]."</td>";
	echo "<td >".$rs->fields[2]."</td>";
	if($rs->fields[9]!='')
	echo "<td >".$rs->fields[9]."</td>";
	else
	echo "<td >".$rs->fields[8]."</td>";

	echo "<td >".$rs->fields[3]."</td>";
	echo "<td >".$rs->fields[4]."</td>";
	
	echo "<td >".$rs->fields[5]."</td>";
	echo "<td >".$rs->fields[6]."</td>";
	
	echo "</tr>";
	$i++;
    $rs->MoveNext();
}
$rs->close();
?>

</table>


</td>
</tr>
</table>
</form>
<script>
setInterval('filtrar()',30000);
</script>
</center>
</body>
</html> 