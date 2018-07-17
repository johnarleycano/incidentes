<?php ?><?php
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}
date_default_timezone_set('UTC');

if(isset($_GET["msg"]))
	echo $_SESSION[APL]->msg($_GET["msg"]);

header('Content-type: application/vnd.ms-excel');
header("Pragma: no-cache");
header("Expires: 0");
header("Content-disposition: attachment; filename=informe_general_".$_POST['fecha_inicio']."-".$_POST['fecha_final'].".xls");
?>

<html>

<style>

.borde{
	border-top:1px solid; border-bottom:1px solid; border-left:1px solid; border-right:1px solid;
}

</style>
<body>
<center>

<table>
<?php

$sql = " SELECT  i.id,
			i.fecha as 'FECHA',
            r.tramo_ruta as 'CODIGO',
			lv.nombre as 'NOMBRE DE USUARIO',
            lv.cedula as 'IDENTIFICACION DE USUARIO',
			case when i.abscisa_real!='' then i.abscisa_real else r.abscisa end AS 'ABSCISA',
			sv.descripcion as 'SENTIDO',
			if(tv.nombre = 'Motocicleta', '1', '0') as 'MOTOCICLETA',
			if( tv.nombre = 'Automovil', '1', '0') as 'AUTOMOVIL',
			if( tv.nombre = 'Camioneta', '1', '0') as 'CAMIONETA',
			if(tv.nombre = 'Bicicleta', '1', '0') as 'BICICLETA',
			if(tv.nombre = 'Tractocamion', '1', '0') as 'TRACTOCAMION',
			if(tv.nombre = 'Buseta', '1', '0') as 'BUSETA',
			i.nro_muertos AS 'MUERTOS',
      i.nro_heridos AS 'HERIDOS',
      IF(ta.nombre = 'Colision solo latas','1','0' ) AS 'SOLO DAÑOS',
			i.observaciones as 'DESCRIPCION',
        case
					when a.nombre = 'Ambulancia Copacabana' then 1
					when a.nombre = 'Ambulancia Palmas' then 1
					when a.nombre = 'Ambulancia Puerto Triunfo' then 1
					when a.nombre = 'Ambulancia El Santuario' then 1
					when a.nombre = 'Ambulancia Particular' then 1
					when a.nombre = 'Ambulancia Defesa Civil'then 1
					when a.nombre = 'Ambulancia Bomberos'then 1
					when a.nombre = 'Ambulancia Ejercito'then 1
					when a.nombre = 'ambulancia Apoyo'then 1
					when a.nombre = 'Ambulancia Hospital' then 1
        else 0
			end as 'AMBULANCIA',

          case 
				when a.nombre = 'Grua Las Palmas' then 1
				when a.nombre = 'Grua Copacabana' then 1
				when a.nombre = 'Grua El Santuario' then 1 
				when a.nombre = 'Grua Puerto Triunfo' then 1 
				when a.nombre = 'Grua Particular' then 1
				when a.nombre = 'Grua Aseguradora'then 1
				when a.nombre = 'Grua Planchon'then 1
				when a.nombre = 'Grua de Apoyo' then 1 
				
                else 0
			end as 'GRUA',

			sum(distinct a.nombre = 'Policia de Transito y Transporte') as 'DITRA',
			sum(distinct a.nombre = 'Inspector Vial') as 'INSPECTOR VIAL',
			sum(distinct a.nombre = 'Empresa Mantenimiento Vial') as 'MANTENIMIENTO VIAL',
			i.condiciones_climaticas as 'CONDICIONES CLIMATICAS'

			from
			dvm_apoyo_entidad as ea
			inner join dvm_apoyo as a
			on a.id = ea.id_entidad
			inner join dvm_incidente as i
			on i.id = ea.id_incidente
            left outer join dvm_vehiculo_incidente as vi on (vi.id_incidente=i.id)
						left outer join  dvm_transito as tra on (tra.id=vi.id_transito)
						left outer join dvm_vehiculo_involucrado  as tv on (tv.id=vi.id_tipo_vehiculo)
            left outer join dvm_grua  as gr on (gr.id=vi.id_grua)
            left outer join dvm_lesionado_vehiculo as lv on (vi.id_vehiculo=lv.id_vehiculo)
            left outer join dvm_ambulancia as amb on (amb.id=lv.id_trasladado_por)
            left outer join dvm_otro_traslado_lesionado as otl on (otl.id=lv.id_otro_lesionado)
            left outer join dvm_sentido as sv on (sv.id=i.sentido_via),

			dvm_referencia as r,
			dvm_tipo_atencion as ta,
			dvm_via v
			where r.id=i.referencia and  ta.id=i.tipo_atencion and v.id=i.via ";

if(isset($_POST['fecha_inicio']) && $_POST['fecha_inicio']!='')
{
	$sql.=" and i.fecha between '".$_POST['fecha_inicio']." ".$_POST['horai'].":".$_POST['minui'].":00' and '".$_POST['fecha_final']." ".$_POST['horaf'].":".$_POST['minuf'].":00' ";
}

$sql.="GROUP BY i.id";

$sql.=" ORDER BY i.id desc";

$rs=$_SESSION[APL]->bd->getRs($sql);

?>

<tr>

		<tr>
			<th colspan="7" align="center" >GOBERNACI&Oacute;N DE ANTIOQUIA</th>
				<td colspan="9" align="center" style='HEIGHT: 10pt'>
					<img src="http://localhost/inci_pruebas/img/grande.jpg" />
				</td>
		</tr>
		<tr>
			<td colspan="7" align="center" >AGENCIA DE SEGURIDAD VIAL DEPARTAMENTAL</td>
		</tr>
		<tr>
			<td colspan="7" align="center">DIRECCI&Oacute;N  XXXXXXXXX</td>
		</tr>
		<tr>
			<th colspan="2" align="left" >PROYECTO</th>
			<td colspan="3" align="left">Reporte de Accidentalidad en el Departamento de Antioquia</td>
		</tr>
		<tr>
			<th colspan="2" align="left" >RESPONSABLES</th>
			<td colspan="2"></td>
		</tr>
		<tr>
			<th colspan="2" align="left">FECHA ELABORACI&Oacute;N</th>
			<td><?php echo date('Y-m-d') ?></td>
		</tr>
		<tr>
			<th colspan="2" align="left" >MES REPORTE</th>
			<td colspan="1"><?php echo date('m', strtotime("-1 month")) ?></td>
		</tr>
		<tr>
			<th colspan="2" align="left" >A&Ntilde;O REPORTANTE</th>
			<td colspan="1">2017</td>
		</tr>

		<tr>
			<td colspan="23" align="left" >FICHA PARA EL REPORTE DE INFORMACI&Oacute;N SOBRE ACCIDENTALIDAD EN LAS V&Iacute;AS</td>
		</tr>
	<tr >
		<td colspan="7" class="borde"></td>
			<th colspan="6" bgcolor='#CCCCCC' class="borde">TIPO DE VEHICULO</th>
			<th colspan="3" bgcolor='#CCCCCC' class="borde">ATENCI&Oacute;N</th>
			<td class="borde"></td>
			<th colspan="5" bgcolor='#CCCCCC' class="borde">UNIDADES QUE ASISTEN</th>
		<td colspan="1" class="borde"></td>
	</tr>
	<tr >
		<th class="borde">FECHA</th>
		<th class="borde">COMPETENCIA</th>
		<th class="borde">CODIGO</th>
		<th class="borde">NOMBRE USUARIO</th>
		<th class="borde">DOCUMENTO</th>
		<th class="borde">ABSCISA</th>
		<th class="borde">SENTIDO</th>
		<th class="borde">MOTOCICLETA</th>
		<th class="borde">AUTOMOVIL</th>
		<th class="borde">CAMIONETA</th>
		<th class="borde">BICICLETA</th>
		<th class="borde">TRACTOCAMION</th>
		<th class="borde">BUSETA</th>
		<th class="borde">MUERTOS</th>
		<th class="borde">HERIDOS</th>
		<th class="borde">SOLO DA&Ntilde;OS</th>
		<th class="borde">DESCRIPCI&Oacute;N</th>
		<th class="borde">AMBULANCIA</th>
		<th class="borde">GRUA</th>
		<th class="borde">DITRA</th>
		<th class="borde">INSPECTOR VIAL</th>
		<th class="borde">MATENIMIENTO VIAL</th>
		<th class="borde">CONDICIONES CLIMATICAS</th>
	</tr>




<?php

$rs=$_SESSION[APL]->bd->getRs($sql);
$i=0;

$muertos = $rs->fields[13];
$heridos = $rs->fields[14];
$solo = $rs->fields[15];
$moto = $rs->fields[7];
$auto = $rs->fields[8];
$camioneta = $rs->fields[9];
$bici = $rs->fields[10];
$bus = $rs->fields[11];
$volqueta = $rs->fields[12];
$ambulancia = $rs->fields[17];
$grua = $rs->fields[18];
$transito = $rs->fields[19];
$mantenimiento = $rs->fields[20];
$policia = $rs->fields[21];

$total_muertos=0;
$total_heridos=0;
$id_inci=0;

if($rs->NumRows()>0)
{



	$tmp=$rs->fields[0];
	$id_inci=0;
	$datos_linea=0;
	$totInc = 0;
	$totMue = 0;
	$totHer = 0;
	$moto=0;



	while (!$rs->EOF){


		if($id_inci==0 || $id_inci!=$rs->fields[1].$rs->fields[0])
		{
			$id_inci=$rs->fields[1].$rs->fields[0];
			$totMue += intval($rs->fields[13]);
			$totHer += intval($rs->fields[14]);
			$solo += intval($rs->fields[15]);
			$moto += intval($rs->fields[7]);
			$auto += intval($rs->fields[8]);
			$camioneta += intval($rs->fields[9]);
			$bici += intval ($rs->fields[10]);
			$bus += intval ($rs->fields[11]);
			$volqueta += intval($rs->fields[12]);
			$ambulancia += intval($rs->fields[17]);
			$grua += intval ($rs->fields[18]);
			$transito += intval ($rs->fields[19]);
			$mantenimiento += intval($rs->fields[20]);
			$policia += intval($rs->fields[21]);



			$datos_linea=1;
		}
		else
			$datos_linea=0;



		if($datos_linea==1)
		{

			$totInc++;

			echo "<td class='borde'>".$rs->fields[1]."</td>";
			echo "<td class='borde' align='center'>ANI</td>";
			echo "<td class='borde' align='center'>".$rs->fields[2]."</td>";
			echo "<td class='borde'>".$rs->fields[3]."</td>";
			echo "<td class='borde'>".$rs->fields[4]."</td>";
			echo "<td class='borde'>".$rs->fields[5]."</td>";//abscisa
			echo "<td class='borde'>".$rs->fields[6]."</td>";//sentido
			echo "<td class='borde' align='center'>".$rs->fields[7]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[8]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[9]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[10]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[11]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[12]."</td>";
			//echo "<td class='style1'></td>";
			echo "<td class='borde' align='center'>".$rs->fields[13]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[14]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[15]."</td>";
			echo "<td class='borde'>".$rs->fields[16]."</td>";//observaciones
			echo "<td class='borde' align='center'>".$rs->fields[17]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[18]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[19]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[20]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[21]."</td>";
			echo "<td class='borde' align='center'>".$rs->fields[22]."</td>";
		}
			echo "</tr>";

		$i++;

    	$rs->MoveNext();
		if(!$rs->EOF && $tmp!=$rs->fields[0])
		{
			$tmp=$rs->fields[0];
		}
	}


	//echo '<tr><td colspan="18"></td></tr>';
	echo '<tr>
			<th colspan="7" class="borde" align="left" bgcolor="#CCCCCC" >
				TOTAL DE * TIPOS DE VEHICULOS INVOLUCRADOS EN ACCIDENTES
			</th>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$moto.'</td>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$auto.'</td>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$camioneta.'</td>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$bici.'</td>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$bus.'</td>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$volqueta.'</td>
			<td bgcolor="#CC589e" class="borde"></td>
			<td bgcolor="#CC589e" class="borde"></td>
			<td bgcolor="#CC589e" class="borde"></td>
			<th colspan="1" align="left" bgcolor="#CCCCCC" class="borde">
				TOTAL RECURSOS REQUERIDOS
			</th>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$ambulancia.'</td>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$grua.'</td>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$transito.'</td>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$mantenimiento.'</td>
			<td bgcolor="#A2D9CE" align="center" class="borde">'.$policia.'</td>
			<td colspan="1" bgcolor="#CCCCCC" class="borde"></td>
		</tr>';
	//echo '<tr><td colspan="18"></td></tr>';
	echo '<tr>
			<th colspan="13" align="left" bgcolor="#CCCCCC" class="borde">
				TOTAL HERIDOS Y/O DESCESOS * MES
			</th>
			<td align="center" bgcolor="#CC589e" class="borde">'.$totMue.'</td>
			<td align="center" bgcolor="#CC589e" class="borde">'.$totHer.'</td>
			<td align="center" bgcolor="#CC589e" class="borde">'.$solo.'</td>
			<td colspan="7" bgcolor="#CCCCCC" class="borde"></td>
		</tr>';
	//echo '<tr><td colspan="18"></td></tr>';
	echo '<tr>
			<th colspan="2" align="left" bgcolor="#CCCCCC" class="borde">
				TOTAL ACCIDENTES * MES
			</th>
			<td align="center" bgcolor="#CC0000" class="borde">'.$totInc.'</td>
			<td colspan="20" bgcolor="#CCCCCC" class="borde"></td>
		</tr>';
}
$rs->close();

?>
</table>
</center>
</form>
</body>
</html>