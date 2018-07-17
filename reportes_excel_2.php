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
header("Content-disposition: attachment; filename=traslado_vehiculos_".$_POST['fecha_inicio']."-".$_POST['fecha_final']."-".$_POST['id_grua'].".xls");






?>

<html>
<body>


<center>

<table width="900px">
<tr>
<td colspan="4" align="left" style='HEIGHT: 130pt'>
<img src="http://<?php echo $_SERVER['HTTP_HOST']?>/img/logo_reporte.jpg" />
</td>
<th colspan="7" style="font-size:16" align="center">TRASLADO DE VEHICULOS<br />
GRUA <?php 
if(isset($_POST['id_grua']) && $_POST['id_grua']!='')
{

$sql="SELECT nombre FROM 
".$_SESSION[APL]->bd->nombre_bd[0].".dvm_grua
WHERE
id=".$_POST['id_grua'];
echo $_SESSION[APL]->bd->dato($sql);
}
else
echo "TODAS";
 ?>

</th>
<td colspan="4" align="right" style='HEIGHT: 130pt'>
<img src="http://<?php echo $_SERVER['HTTP_HOST']?>/img/logo_reporte_ani.png" />
</td>
</tr>


<tr>
<th class="LegendSt">Grua</th>
<th class="LegendSt">No</th>
<th class="LegendSt">FECHA</th>
<th class="LegendSt">HORA REPORTE</th>
<th class="LegendSt">HORA LLEGADA</th>
<th class="LegendSt">ABSCISA</th>
<th class="LegendSt">REFERENCIA</th>
<th class="LegendSt">VIA</th>
<th class="LegendSt">INFORMADO POR</th>
<th class="LegendSt">TIPO DE ATENCIÓN</th>
<th class="LegendSt">VEHÍCULOS INVOLUCRADOS</th>
<th class="LegendSt">PLACAS</th>
<th class="LegendSt">NOMBRE USUARIO</th>
<th class="LegendSt">IDENTIFICACIÓN USUARIO</th>
<th class="LegendSt">SITIO DE TRASLADO VEHÍCULO</th>
</tr>

<?php

$sql = "SELECT i.codigo as 'Codigo',i.periodo as 'Periodo',date_format(i.fecha,'%e-%c-%Y') as 'FECHA',i.hora_reporte as 'HORA REPORTE',
			i.hora_llegada_sitio as 'HORA LLEGADA',
			case 
				when abscisa_real!='' then abscisa_real
				else 
				r.abscisa 
			end as 'ABSCISA',
			r.referencia as 'REFERENCIA',v.nombre as 'VIA',inf.nombre as 'INFORMADO POR',ta.nombre as 'TIPO DE ATENCIÓN',tv.nombre as 'VEHÍCULOS INVOLUCRADOS',	
			vi.placa_vehiculo as 'PLACAS',lv.nombre as 'NOMBRE USUARIO',lv.cedula as 'IDENTIFICACIÓN USUARIO',par.nombre as 'SITIO DE TRASLADO VEHÍCULO PAR',
			tra.nombre as 'SITIO DE TRASLADO VEHÍCULO TRA',tal.nombre as 'SITIO DE TRASLADO VEHÍCULO TAL',otv.nombre as 'SITIO DE TRASLADO VEHÍCULO OTV',
			i.hora_salida_base,i.hora_llegada_base,gr.nombre
        FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_grua as gr,".$_SESSION[APL]->bd->nombre_bd[0].".dvm_incidente as i,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_referencia as r,".$_SESSION[APL]->bd->nombre_bd[0].".dvm_via as v,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_tipo_atencion as ta,".$_SESSION[APL]->bd->nombre_bd[0].".dvm_informado as inf,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_incidente as vi 
			left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_parqueadero as par on (par.id=vi.id_parqueadero) 
			left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_transito as tra on (tra.id=vi.id_transito) 
			left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_taller as tal on (tal.id=vi.id_taller)
			left outer join ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_otro_traslado_vehiculo as otv on (otv.id=vi.id_otro_vehiculo),
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_vehiculo_involucrado  as tv,
			".$_SESSION[APL]->bd->nombre_bd[0].".dvm_lesionado_vehiculo as lv
        WHERE vi.id_grua=gr.id and lv.conducia='SI' and i.referencia=r.id and i.via=v.id and i.tipo_atencion=ta.id and i.informado_por=inf.id and
			i.id=vi.id_incidente and tv.id=vi.id_tipo_vehiculo and vi.id_vehiculo=lv.id_vehiculo ";


if(isset($_POST['fecha_inicio']) && $_POST['fecha_inicio']!='')
	$sql.=" and i.fecha between '".$_POST['fecha_inicio']." ".$_POST['horai'].":".$_POST['minui'].":00' and '".$_POST['fecha_final']." ".$_POST['horaf'].":".$_POST['minuf'].":00' ";

if(isset($_POST['id_grua']) && $_POST['id_grua']!='')
		$sql.=" and vi.id_grua=".$_POST['id_grua']." ";

//$sql.=" ORDER BY gr.nombre,i.id desc";
$sql.=" ORDER BY i.id asc, gr.nombre";

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
	
	while (!$rs->EOF){
		/*******************CALCULAR TIEMPO EVENTO****************/
	
		$hora_salida_base=explode(":",$rs->fields[18]);
		$hora_llegada_base=explode(":",$rs->fields[19]);
	
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
			$datos_linea=1;
		}
		else
			$datos_linea=0;

	if($datos_linea==1)
	{
		$totInc++;
		echo "<td class='style1'>".$rs->fields[20]."</td>";//Grua
		echo "<td class='style1'>'".$rs->fields[1].".".str_pad($rs->fields[0],5,"0",STR_PAD_LEFT)."'</td>";
	
		echo "<td class='style1'>".$rs->fields[2]."</td>";//Hora Reporte
		echo "<td class='style1'>".$rs->fields[3]."</td>";//Hora Llegada
		echo "<td class='style1'>".$rs->fields[4]."</td>";//ABSCISA
		echo "<td class='style1'>".$rs->fields[5]."</td>";//REFERENCIA		
		echo "<td class='style1'>".$rs->fields[6]."</td>";//VIA
		echo "<td class='style1'>".$rs->fields[7]."</td>";//INFORMADO POR
		echo "<td class='style1'>".$rs->fields[8]."</td>";
		echo "<td class='style1'>".$rs->fields[9]."</td>";	
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
	}
		
		echo "<td class='style1'>".$rs->fields[10]."</td>";
		echo "<td class='style1'>".$rs->fields[11]."</td>";
		echo "<td class='style1'>".$rs->fields[12]."</td>";
		echo "<td class='style1'>".$rs->fields[13]."</td>";
		echo "<td class='style1'>";
		
		if($rs->fields[14]!='')
			echo $rs->fields[14];
		else
		if($rs->fields[15]!='')
			echo $rs->fields[15];
		else
		if($rs->fields[16]!='')
			echo $rs->fields[16];
		else
		if($rs->fields[17]!='')
			echo $rs->fields[17];
		else
			echo "&nbsp;";
		echo "</td>";
		echo "</tr>";

		$i++;
    	$rs->MoveNext();
		if(!$rs->EOF && $tmp!=$rs->fields[0])
		{
			$tmp=$rs->fields[0];
		}
	}// Fin while
	
	echo '<tr><td colspan="15"></td></tr>';
	echo '<tr><td></td><td align="center"><b>'.$totInc.'</b></td><td align="center"><b>'.$i.'</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
	
}// Fin if
$rs->close();
?>
</table>
</center>
</form>
</body>
</html>
