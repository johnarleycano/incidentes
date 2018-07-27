<?php
include_once "clases/capp.php";
include_once "libs/PHPExcel.php";
session_start();

$fecha_inicio = $_POST['fecha_inicio'];
$fecha_final = $_POST['fecha_final'];

//Se crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

//Se establece la configuracion general
$objPHPExcel->getProperties()
	->setCreator("John Arley Cano Salinas - Devimed S.A.")
	->setLastModifiedBy("John Arley Cano Salinas")
	//->setTitle("Sistema de Incidentes - Generado el ".$this->auditoria_model->formato_fecha(date('Y-m-d')).' - '.date('h:i A'))
	->setSubject("Listado de accidentes por sectores críticos")
	->setDescription("En este listado se muestran los accidentes de acuerdo al fotmado entregado por la ANSV")
	->setKeywords("reporte accidentes accidentalidad devimed sectores criticos ansv")
    ->setCategory("Reporte");

//Definicion de las configuraciones por defecto en todo el libro
$objPHPExcel->getDefaultStyle()->getFont()->setName('Helvetica'); //Tipo de letra
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9); //Tamanio
$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);//Ajuste de texto
$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);// Alineacion centrada


//Se establece la configuracion de la pagina
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); // Orientación horizontal
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER); // Tamaño carta
$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(74); // Escala

//Se indica el rango de filas que se van a repetir en el momento de imprimir. (Encabezado del reporte)
$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1);

//Centrar página
$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered();

// Ocultar la cuadrícula: 
$objPHPExcel->getActiveSheet()->setShowGridlines(true);

//Celdas a combinar
// $objPHPExcel->getActiveSheet()->mergeCells("B1:D2");

// Columna inicial
$columna = "A";

/*
 * Definicion de la anchura de las columnas
 */
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(4); $columna++; // Número
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(12); $columna++; // Fecha de evento
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(8); $columna++; // Hora de evento
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(25); $columna++; // Tramo

// Espacio de datos de tráfico diario
$columna++;
$columna++;
$columna++;
$columna++;
$columna++;
$columna++;
$columna++;

$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(15); $columna++; // Municipio 1
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(15); $columna++; // Municipio 2
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(25); $columna++; // Sentido de circulación
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(10); $columna++; // Código de vía
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(12); $columna++; // Abscisa PR
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(12); $columna++; // Abscisa KM
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(12); $columna++; // Latitud
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(12); $columna++; // Longitud
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(15); $columna++; // Departamento
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(15); $columna++; // Municipio
$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(20); $columna++; // Punto de referencia 1



/**
 * Definición de altura de las filas
 */
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);

// Columna inicial
$columna = "A";

/*
 * Encabezado
 */
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "6. Nro."); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "7. Fecha evento"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "8. Hora"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "9. Tramo contractual o unidad funcional"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "10.1 C1 Automóviles, camperos y camionetas"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "10.2 C2 Buses, busetas y microbuses con eje trasero de doble llanta"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "10.3 C3 Camiones pequeños de dos ejes"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "10.4 C4 Camiones grandes de dos ejes"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "10.5 C5 Camiones de tres y cuatro ejes"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "10.6 C6 Camiones de cinco ejes"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "10.7 C7 Camiones de seis ejes"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "11.1 Municipio 1"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "11.2 Municipio 2"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "12. Sentido de circulación del tráfico"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "13. Ruta nacional RN(código de vía)"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "14. Abscisa_PR"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "15. Abscisa_KM"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "16.1 Latitud"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "16.2 Longitud"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "17.1 Departamento"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "17.2 Municipio"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "18. Denominación del punto común"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "19. Zona"); $columna++;
$objPHPExcel->getActiveSheet()->setCellValue("{$columna}1", "20. Tipo de calzada"); $columna++;

// Consulta
$sql = 
"SELECT
	i.fechaincidente,
	i.horaincidente,
	v.nombre AS tramo,
	m1.nombre AS municipio1,
	m2.nombre AS municipio2,
	v.codigo_via,
	i.abscisa_real,
	m3.nombre AS municipio_ocurrencia,
	d.nombre AS departamento,
	X(i.coordenadas) longitud,
	Y(i.coordenadas) latitud,
	r.referencia
FROM
	dvm_incidente AS i
	LEFT JOIN dvm_via AS v ON i.via = v.id
	LEFT JOIN dvm_municipio AS m1 ON i.municipio1 = m1.id
	LEFT JOIN dvm_municipio AS m2 ON i.municipio2 = m2.id   
	LEFT JOIN dvm_municipio AS m3 ON i.municipio_ocurrencia = m3.id
	LEFT JOIN dvm_departamento AS d ON m3.id_departamento = d.id
	LEFT JOIN dvm_referencia AS r ON i.referencia = r.id
WHERE
	i.fechaincidente BETWEEN '{$fecha_inicio}' 
	AND '$fecha_final'
ORDER BY
	i.fechaincidente ASC,
	i.horaincidente ASC";

// Se ejecuta la consulta
$resultado = $_SESSION[APL]->bd->getRs($sql);

// Fila
$fila = 2;

// Contador
$cont = 1;

// Se recorren los resultados
while (!$resultado->EOF){
	// Columna inicial
	$columna = "A";

	$arreglo = $resultado->fields;

	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", $cont++); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["fechaincidente"])); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["horaincidente"])); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["tramo"])); $columna++;

	// Espacio de datos de tráfico diario
	$columna++;
	$columna++;
	$columna++;
	$columna++;
	$columna++;
	$columna++;
	$columna++;

	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["municipio1"])); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["municipio2"])); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode("Municipio 1 a Municipio 2")); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["codigo_via"])); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["abscisa_real"])); $columna++;
	
	$abscisa = explode('+', $arreglo["abscisa_real"]);
	$kilometro = substr($abscisa["0"], 1, 3);
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($kilometro)); $columna++;
	
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["latitud"])); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["longitud"])); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["departamento"])); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["municipio_ocurrencia"])); $columna++;
	$objPHPExcel->getActiveSheet()->setCellValue("{$columna}{$fila}", utf8_encode($arreglo["referencia"])); $columna++;

	$resultado->MoveNext();
	$fila++;
}
// Pie de página
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' .$objPHPExcel->getProperties()->getTitle() . '&RPágina &P de &N');

//Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Cache-Control: max-age=0');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Accidentalidad_Sectores_Criticos.xlsx"');

//Se genera el excel
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>