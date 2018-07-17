<?php
ini_set("memory_limit","256M");
set_time_limit(0);
include_once("../clases/capp.php");
session_start();

$fecIni = $_GET["fecIni"];
$fecFin = $_GET["fecFin"];

$sql = "select substring(fecha,1,7) fecha, count(*) contador
		from dvm_incidente
		where fecha>='$fecIni' and fecha<='$fecFin'
		group by substring(fecha,1,7)";
$rs = $_SESSION[APL]->bd->getRs($sql);

$jsCadArr = "";
while( !$rs->EOF )
{
	$fec = $rs->fields["fecha"];
	$con = $rs->fields["contador"];
	
	if( $jsCadArr!="" )
		$jsCadArr .= ",";
	
	$jsCadArr .= "['$fec',$con]";
	
	$rs->MoveNext();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<script type="text/javascript" src="../libs/jq/jscharts.js"></script>
</head>
<body>
	<center>
		<div id="chartcontainer" style="width:100%"></div>
	</center>
</body>
<script type="text/javascript">
	var myData = new Array(<?php echo $jsCadArr; ?>);
	var myChart = new JSChart('chartcontainer', 'bar');
	myChart.setDataArray(myData);
	myChart.setSize(900, 400);
	myChart.setTitle('NUMERO DE ACCIDENTES POR RANGO DE FECHA');
	myChart.setTitleFontSize(10);
	myChart.setBarSpacingRatio(50);
	myChart.setAxisNameX('');
	myChart.setAxisNameY('');
	myChart.draw();
</script>
</html>
