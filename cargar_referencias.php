<?php
include_once "clases/capp.php";
session_start();

// Variables recibidas por Get
$via = $_GET["via"];
$abscisa = $_GET["abscisa"];

// Consulta
$sql=
"SELECT
	*
FROM
	dvm_referencia AS r 
WHERE
	r.id_via = $via 
	AND r.abscisa_numerica >= ( $abscisa - 1000 ) 
	AND r.abscisa_numerica <= ( $abscisa + 1000 ) 
ORDER BY
	r.id_via ASC,
	r.referencia ASC";
		
// Resultado de la consulta
$resultado = $_SESSION[APL]->bd->getRs($sql);

// Arreglo que almacenará las referncias
$referencias = array();

// Se recorren los resultados
while (!$resultado->EOF)
{
	// Se recorren los resultados
	foreach ($resultado->fields as $key => $value) {
		// Si la llave es númérica, se elimina para que sólo queden llaves con nombre
		if(is_int($key)){
			unset($resultado->fields[$key]);
		}
	}

	// Se formatea en UTF8 el nombre del sitio de referencia
	$resultado->fields["referencia"] = utf8_encode($resultado->fields["referencia"]);
	
	// Se almacena el resultado en el arreglo de referencias
	array_push($referencias, $resultado->fields);
	
	// Se avanza
	$resultado->MoveNext();
}

// Se retorna el arreglo de referencias en JSON
print json_encode($referencias);
?>