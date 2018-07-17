<?php
include_once "clases/capp.php";
session_start();

$error = "";
$msg = "";
$pNomCmp = $_POST["nomCmp"];
$pCual   = $_POST["cual"];
$pNomArc = $_POST["nomArc"];
$pId     = $_POST["id"];

if(!empty($_FILES[$pNomCmp]['error']))
{
	switch($_FILES[$pNomCmp]['error'])
	{
		case '1':
			$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			break;
		case '3':
			$error = 'The uploaded file was only partially uploaded';
			break;
		case '4':
			$error = 'No file was uploaded.';
			break;
		case '6':
			$error = 'Missing a temporary folder';
			break;
		case '7':
			$error = 'Failed to write file to disk';
			break;
		case '8':
			$error = 'File upload stopped by extension';
			break;
		case '999':
		default:
			$error = 'No error code avaiable';
	}
}
elseif(empty($_FILES[$pNomCmp]['tmp_name']) || $_FILES[$pNomCmp]['tmp_name'] == 'none')
	$error = 'Archivo no cargado..';
else 
{
	$nomOri = $_FILES[$pNomCmp]['name'];
	$tipo   = $_FILES[$pNomCmp]["type"];
	$ext	= end(explode(".", $_FILES[$pNomCmp]['name']));
	
	// Si existe se borra
	
	if( $pCual=='USU' )
	{
		$archivo = strtolower("firmas/".$pNomArc.".".$ext);
		$vNomArc = strtolower($pNomArc.".".$ext);
		if( file_exists($archivo) )
			@unlink($archivo);
		
		@move_uploaded_file($_FILES[$pNomCmp]['tmp_name'],$archivo);
		$sql = "UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
				SET firma='$vNomArc' 
				WHERE id=".$pId."";
		$_SESSION[APL]->bd->ejecutar($sql);
	}
	else
	{
		
	}
	
	@unlink($_FILES[$pNomCmp]);
}

echo "{error:'".$error."', msg:'".$msg."'}";
?>