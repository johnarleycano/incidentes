<?php 
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}


if(isset($_GET["msg"]))
	echo $_SESSION[APL]->msg($_GET["msg"]);

header('Content-type: application/vnd.ms-excel');
header("Pragma: no-cache");
header("Expires: 0");
header("Content-disposition: attachment; filename=traslado_heridos_".$_POST['fecha_inicio']."-".$_POST['fecha_final']."-".$_POST['id_ambulancia'].".xls");
?>

<html>
<body>


<center>

<table width="900px">
<tr>
<td colspan="5" align="left" style='HEIGHT: 130pt'>
<img src="http://<?php echo $_SERVER['HTTP_HOST']?>/img/logo_reporte.jpg" />
</td>
<th colspan="8" style="font-size:16" align="center">TRASLADO DE HERIDOS<br />
<?php 
if(isset($_POST['id_ambulancia']) && $_POST['id_ambulancia']!='')
{

$sql="SELECT nombre FROM 
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia
WHERE
id=".$_POST['id_ambulancia'];
echo $_SESSION[APL]->bd->dato($sql);
}
else
echo "TODAS";

 ?>

</th>
<td colspan="5" align="right" style='HEIGHT: 130pt'>
<img src="http://<?php echo $_SERVER['HTTP_HOST']?>/img/logo_reporte_ani.png" />
</td>
</tr>
<tr>

<th class="LegendSt">Ambulacia</th>
<th class="LegendSt">No</th>
<th class="LegendSt">FECHA</th>
<th class="LegendSt">HORA REPORTE</th>
<th class="LegendSt">HORA LLEGADA</th>
<th class="LegendSt">TIEMPO</th>
<th class="LegendSt">ABSCISA</th>
<th class="LegendSt">REFERENCIA</th>
<th class="LegendSt">VIA</th>
<th class="LegendSt">INFORMADO POR</th>
<th class="LegendSt">TIPO DE ATENCIÓN</th>
<th class="LegendSt">No MUERTOS</th>
<th class="LegendSt">No HERIDOS</th>
<th class="LegendSt">VEHÍCULOS INVOLUCRADOS</th>
<th class="LegendSt">PLACA</th>
<th class="LegendSt">NOMBRE USUARIO</th>
<th class="LegendSt">IDENTIFICACIÓN USUARIO</th>
<th class="LegendSt">SITIO DE TRASLADO USUARIO</th>
<th class="LegendSt">OBSERVACIONES</th>
</tr>




<?php



$sql = "SELECT i.codigo as 'Codigo',i.periodo as 'Periodo',	date_format(i.fecha,'%e-%c-%Y') as 'FECHA',	i.hora_reporte as 'HORA REPORTE',	
			i.hora_llegada_sitio as 'HORA LLEGADA',tiempo as 'TIEMPO',
			case 
				when abscisa_real!='' then abscisa_real
				else r.abscisa 
			end as 'ABSCISA',	
			r.referencia as 'REFERENCIA',v.nombre as 'VIA',inf.nombre as 'INFORMADO POR',ta.nombre as 'TIPO DE ATENCIÓN',
			i.nro_muertos as 'No MUERTOS',i.nro_heridos as 'No HERIDOS',tv.nombre as 'VEHÍCULOS INVOLUCRADOS',lv.nombre as 'NOMBRE USUARIO',	
			lv.cedula as 'IDENTIFICACIÓN USUARIO',hos.nombre as 'SITIO DE TRASLADO USUARIO HOS',csa.nombre as 'SITIO DE TRASLADO USUARIO CSA',
			cli.nombre as 'SITIO DE TRASLADO USUARIO CLI',otl.nombre as 'SITIO DE TRASLADO USUARIO OTL',lv.observaciones as 'OBSERVACIONES',
			i.hora_salida_base,i.hora_llegada_base,am.nombre, vi.placa_vehiculo
		FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ambulancia as am,".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia as r,".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via as v,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion as ta,".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado as inf,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente as vi, ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado  as tv,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo as lv
			left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_hospital as hos on (hos.id =lv.id_hospital) 
			left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_centro_salud as csa on  (csa.id=lv.id_centro_salud) 
			left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_clinica as cli on (cli.id=lv.id_clinica) 
			left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_lesionado as otl on  (otl.id=lv.id_otro_lesionado)
		WHERE am.id=lv.id_trasladado_por and i.referencia=r.id and i.via=v.id and i.tipo_atencion=ta.id and
			i.informado_por=inf.id and i.id=vi.id_incidente and tv.id=vi.id_tipo_vehiculo and vi.id_vehiculo=lv.id_vehiculo";

if(isset($_POST['fecha_inicio']) && $_POST['fecha_inicio']!='')
{
	$sql.=" and i.fecha between '".$_POST['fecha_inicio']." ".$_POST['horai'].":".$_POST['minui'].":00' and '".$_POST['fecha_final']." ".$_POST['horaf'].":".$_POST['minuf'].":00' ";
}
if(isset($_POST['id_ambulancia']) && $_POST['id_ambulancia']!='')
{
		$sql.=" and lv.id_trasladado_por=".$_POST['id_ambulancia']." ";

}


//$sql.=" ORDER BY am.nombre, i.id desc";
$sql.=" ORDER BY i.id asc, am.nombre";



$rs=$_SESSION[APL]->bd->getRs($sql);
$i=0;

$total_muertos=0;
$total_heridos=0;
$id_inci=0;

if($rs->NumRows()>0)
{
	$tmp=$rs->fields[0];
	$id_inci=0;
	$datos_linea=0;
	$totInc = 0;
	$totTieMin = 0;
	$conTie = 0;
	$totMue = 0;
	$totHer = 0;
	
	while (!$rs->EOF){
		/*******************CALCULAR TIEMPO EVENTO****************/
		$hora_salida_base=explode(":",$rs->fields[21]);
		$hora_llegada_base=explode(":",$rs->fields[22]);
	
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
	
		if($id_inci==0 || $id_inci!=$rs->fields[1].$rs->fields[0])
		{
			$id_inci=$rs->fields[1].$rs->fields[0];
			$totMue += intval($rs->fields[11]);
			$totHer += intval($rs->fields[12]);
			$datos_linea=1;
		}
		else
			$datos_linea=0;

		if($datos_linea==1)
		{
			$totInc++;
			$tiempo_total = $_SESSION[APL]->restarHoras($rs->fields[3],$rs->fields[4]);
			
			if( $tiempo_total!="" )
			{
				$totTieMin = $totTieMin + $_SESSION[APL]->convertirHorasEnMinutos($tiempo_total);
				$conTie++;
			}
			
			echo "<td class='style1'>".$rs->fields[23]."</td>";
			echo "<td class='style1'>'".$rs->fields[1].".".str_pad($rs->fields[0],5,"0",STR_PAD_LEFT)."'</td>";
			echo "<td class='style1'>".$rs->fields[2]."</td>";
			echo "<td class='style1'>".$rs->fields[3]."</td>";
			echo "<td class='style1'>".$rs->fields[4]."</td>";
			echo "<td class='style1'>".$tiempo_total."</td>";
			echo "<td class='style1'>".$rs->fields[6]."</td>";
			echo "<td class='style1'>".$rs->fields[7]."</td>";
			echo "<td class='style1'>".$rs->fields[8]."</td>";
			echo "<td class='style1'>".$rs->fields[9]."</td>";
			echo "<td class='style1'>".$rs->fields[10]."</td>";	
			echo "<td class='style1'>".$rs->fields[11]."</td>";
			echo "<td class='style1'>".$rs->fields[12]."</td>";
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
			echo "<td class='style1'>&nbsp;</td>";
			echo "<td class='style1'>&nbsp;</td>";
		}
		
		
		echo "<td class='style1'>".$rs->fields[13]."</td>";
		echo "<td class='style1'>".$rs->fields[24]."</td>";
		echo "<td class='style1'>".$rs->fields[14]."</td>";
		echo "<td class='style1'>".$rs->fields[15]."</td>";
		echo "<td class='style1'>";
		
		if($rs->fields[16]!='')
			echo $rs->fields[16];
		else
		if($rs->fields[17]!='')
			echo $rs->fields[17];
		else
		if($rs->fields[18]!='')
			echo $rs->fields[18];
		else
		if($rs->fields[19]!='')
			echo $rs->fields[19];
		
		echo "</td><td class='style1'>".$rs->fields[20]."</td>";
		echo "</tr>";

		$i++;
    	$rs->MoveNext();
		if(!$rs->EOF && $tmp!=$rs->fields[0])
		{
			$tmp=$rs->fields[0];
		}
	}
	
	$proMin = $totTieMin;
	if( $conTie>0 )
		$proMin = ceil($totTieMin/$conTie);

	$proTie = $_SESSION[APL]->convertirMinutosEnHoras($proMin);
	
	echo '<tr><td colspan="18"></td></tr>';
	echo '<tr>
			<td></td>
			<td align="center"><b>'.$totInc.'</b></td>
			<td align="center"><b>'.$i.'</b></td>
			<td></td>
			<td></td>
			<td align="center"><b>'.$proTie.'</b></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>'.$totMue.'</td>
			<td>'.$totHer.'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		  </tr>';
	
}
$rs->close();
	
?>
</table>
</center>
</form>
</body>
</html>
