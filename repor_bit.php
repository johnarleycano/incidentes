<?php
header('Content-Type: text/html; charset=UTF-8');

date_default_timezone_set('America/Bogota');



if(strlen($_GET['desde'])>0 and strlen($_GET['hasta'])>0 and strlen($_GET['select'])>0 and strlen($_GET['nombre'])>0 and strlen($_GET['apellido'])>0 ){
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];
    $login = $_GET['select'];
    $nombres = $_GET['nombre'];
    $apellidos = $_GET['apellido'];

	$verDesde = date('d/m/Y', strtotime($desde));
	$verHasta = date('d/m/Y', strtotime($hasta));
}else{
	$desde = '1111-01-01';
	$hasta = '9999-12-30';

	$verDesde = '__/__/____';
	$verHasta = '__/__/____';
}

include_once "conexion.php";

$registro = mysql_query("SET i.observaciones 'utf8'");


$registro = mysql_query("SELECT u.id, b.id as id, b.fecha, b.hora, b.referencia, i.nombre as emisor, b.motivo, v.nombre as ubicacion, b.anotaciones
		FROM dvm_bitacoras as b,
			 dvm_informado as i,
			 dvm_via as v,
			 dvm_usuarios as u
		WHERE  i.id=b.info_por AND v.id=b.ubicacion AND u.id=b.id_usuario and 
        b.fecha BETWEEN '$desde' AND '$hasta' and u.id = '$login' ORDER BY b.id ASC");
		

/*$registro = mysql_query("SELECT u.id, i.id, concat(i.periodo, i.codigo) as codigo, i.fecha, ta.nombre, r.referencia, i.observaciones 
	FROM dvm_incidente i
	INNER JOIN dvm_usuarios u
	ON u.id = i.id_usuario
	INNER JOIN dvm_tipo_atencion ta
	ON i.tipo_atencion = ta.id
	INNER JOIN dvm_referencia r
	ON i.referencia = r.id
	
	WHERE i.fecha BETWEEN '$desde' AND '$hasta'  AND '$login' = u.id ORDER BY i.id DESC");
	

*/

require('fpdf/fpdf.php');

class PDF extends FPDF
{
	

	//Cabecera de página
   function Header()
   {
      //Logo
      $this->Image("img/logo_bitacora.jpg" , 10 ,8, 30 , 30 , "JPG" );
      //Arial bold 15
      $this->SetFont('Arial','B',15);
      //Movernos a la derecha
      $this->Cell(65);
      //Título
      $this->Cell(50,10,'DEVIMED S.A.',1,0,'C');
      //Salto de línea
      $this->Ln(20);
   }

   //Pie de página
   function Footer()
   {
      //Posición: a 1,5 cm del final
      $this->SetY(-15);
      //Arial italic 8
      $this->SetFont('Arial','I',8);
      //Número de página
      $this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
   }
	
	var $widths;
	var $aligns;

	function SetWidths($w)
	{
	//Set the array of column widths
	$this->widths=$w;
	}

	function SetAligns($a)
	{
	//Set the array of column alignments
	$this->aligns=$a;
	}

	function Row($data)
	{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
	$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
	$w=$this->widths[$i];
	$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
	//Save the current position
	$x=$this->GetX();
	$y=$this->GetY();
	//Draw the border
	$this->Rect($x,$y,$w,$h);
	//Print the text
	$this->MultiCell($w,5,$data[$i],0,$a);
	//Put the position to the right of the cell
	$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
	$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
		while($i<$nb){
		$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
			$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
					$i++;
				}
			else
				$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
			}
				return $nl;
		}
		
		
	
}

$pdf = new PDF();
$pdf->AddPage();

$pdf->SetMargins(10, 25 ,15); 

$y_axis_initial = 5;

$pdf->SetFont('Arial', '', 10);
//$pdf->Image('img/logo.jpg' , 20 ,20, 20 , 13,'JPG');
$pdf->Cell(18, 10, '', 0);
$pdf->Cell(120, 10, '', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(50, 10, 'Hoy: '.date('d-m-Y').'', 0);
$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(70, 8, '', 0);
$pdf->Cell(100, 8, 'LISTADO DE BITACORAS', 0);
$pdf->Ln(10);
$pdf->Cell(60, 8, '', 0);
$pdf->Cell(100, 8, 'Desde: '.$verDesde.' hasta: '.$verHasta, 0);
$pdf->ln(10);
$pdf->Cell(70, 8, '', 0);
$pdf->Cell(100, 8, $nombres.' '.$apellidos, 0);
$pdf->Ln(23);


$pdf->SetFont('Arial', 'B', 8);

$pdf->Cell(10, 8, 'ID', 1);
$pdf->Cell(20, 8, 'FECHA', 1);
$pdf->Cell(10, 8, 'HORA', 1);
$pdf->Cell(30, 8, 'REPORTADO POR', 1);
$pdf->Cell(30, 8, 'MOTIVO', 1);
$pdf->Cell(35, 8, 'UBICACION', 1);
$pdf->Cell(40, 8, 'ANOTACIONES', 1);

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 8);

$pdf->SetWidths(array(10,20,10,30,30,35,40));




	
while($dato = mysql_fetch_assoc($registro)){
	
		
	
		$pdf->Row(array($dato['id'],$dato['fecha'], $dato['hora'],$dato['emisor'],$dato['motivo'],$dato['ubicacion'],$dato['anotaciones']));
			
}


$pdf->Ln(30);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 8, '_______________________________________', 0);
$pdf->ln(5);
$pdf->Cell(20, 8, $nombres.' '.$apellidos, 0);



$pdf->Output('bitacora.pdf','I');


?>