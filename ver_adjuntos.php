<?php include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}
if(isset($_GET["msg"]))
	echo $_SESSION[APL]->msg($_GET["msg"]);

echo $_SESSION[APL]->cabeceras();
?>

<script>
function cerrar()
{
	if(confirm('Desea Cancelar y Cerrar la Ventana'))
		window.close();
}
function guardar()
{
	if(document.hijo.item.value=='')
	{
		alert('Ingrese el nombre del item');
		document.hijo.item.focus();
	}
	else
		document.hijo.submit();
	
}
</script>
<body>
<?php if(isset($_POST['descripcion']))
{
		
	$sql="UPDATE calsuper_calsuperior.nodo 
	SET 
	item='".$_POST['item']."',
	descripcion='".$_POST['descripcion']."',
	sociedad='".$_POST['sociedad']."',
	original=".$_POST['original'].",
	naturaleza_doc='".$_POST['naturaleza_doc']."',
	fecha_creacion='".$_POST['fecha_creacion']."',
	ubicacion='".$_POST['ubicacion']."',
	observaciones='".$_POST['observaciones']."'
	WHERE id=".$_POST['id']."";
	if(!$_SESSION[APL]->bd->ejecutar($sql))
		$msg="Error al Actualizar Hijo. ";
	else
	{
		$msg="Hijo Actualizado. ";	
		for($i=1;$i<=5;$i++)
		{
			if($HTTP_POST_FILES['adjunto_'.$i]['name']!='')
			{
								
				$nombre_archivo = $HTTP_POST_FILES['adjunto_'.$i]['name']; 
				$tipo_archivo = $HTTP_POST_FILES['adjunto_'.$i]['type']; 
				$tamano_archivo = $HTTP_POST_FILES['adjunto_'.$i]['size']; 
				
				//$_SESSION[APL]->subirArchivo(RUTA_ADJUNTOS_TMP,$_SESSION[APL]->usuario->id.'_'.$temp,$arc[$i]);
				
				$gestor = fopen($nombre_archivo, "r");
				$contenido = fread($gestor, filesize($nombre_archivo));
				$sql="INSERT INTO calsuper_calsuperior.adjunto_nodo (id_nodo,adjunto,campo_blob)
				values (".$_POST['id'].",'".$nombre_archivo."',null)";
				if(!$_SESSION[APL]->bd->ejecutar($sql))
					$msg.="Error al Insertar Registro Adjunto. ";
				else
				{
					$sql="SELECT MAX(id) from calsuper_calsuperior.adjunto_nodo";
					$max_id=$_SESSION[APL]->bd->dato($sql);
					
					$nombre_archivo2=$_POST['id'].'_'.$max_id.'_'.$nombre_archivo;
					
					if (move_uploaded_file($HTTP_POST_FILES['adjunto_'.$i]['tmp_name'],'adjuntos/'.$nombre_archivo2))
					{
							$nombre_archivo = 'adjuntos/'.$_POST['id'].'_'.$max_id.'_'.$nombre_archivo;
							$gestor = fopen($nombre_archivo, "r");
							$contenido = fread($gestor, filesize($nombre_archivo));
							fclose($gestor);
							$cargar_blob=$_SESSION[APL]->bd->cargarblob('calsuper_calsuperior.adjunto_nodo','campo_blob',$contenido,'id='.$max_id);	
						if($cargar_blob)
						   $msg.="El archivo ha sido cargado correctamente. "; 
						else
							$msg.="Ocurrió algún error al cargar como blob. No pudo guardarse.".$HTTP_POST_FILES['adjunto_'.$i]['name'].". "; 
						$_SESSION[APL]->eliminarArchivo("adjuntos/",$nombre_archivo2);
					}
					else
					   $msg.="Ocurrió algún error al subir el fichero. No pudo guardarse.".$HTTP_POST_FILES['adjunto_'.$i]['name'].". "; 
					   				   
					
					
					
					
					
				} 
			}
			
		}
	}
	
	?>
	<script>
	alert('<?php echo $msg?>');
	window.opener.location.replace('lanzador.php');
	window.close();
	</script>
	<?php }
else
{

$sql="SELECT * FROM 
calsuper_calsuperior.nodo 
WHERE id=".$_GET['id']."";
$hijo=$_SESSION[APL]->bd->getRs($sql);


$size_td=$_SESSION[APL]->tam_normal;
$font_color_td=$_SESSION[APL]->color_normal;
$size_h1=$_SESSION[APL]->tam_titulo;
$font_color_h1=$_SESSION[APL]->color_titulo;

$css_td='style="font-size: '.$size_td.'; color:'.$font_color_td.'"';
$css_h1='style="font-size: '.$size_h1.'; color:'.$font_color_h1.'"';


?>

<table>
<tr><th colspan="2" <?php echo $css_h1?>>Ver y/o Editar</th></tr>

<form name="hijo" method="post" action="../Copy of pruebas/ver_adjuntos.php" enctype="multipart/form-data">
<tr>
	<th <?php echo $css_td?>>Id</th>
	<td <?php echo $css_td?>><?php echo $hijo->fields('id_archivo')?></td>
</tr>
<tr>
	<th <?php echo $css_td?>>Item</th>
	<td><input type="text"  size="30" name="item" value="<?php echo $hijo->fields('item')?>" <?php echo $css_td?>/></td>
</tr>
<tr>
	<th <?php echo $css_td?>>Descripcion</th>
	<td><input type="text"  size="30" name="descripcion" value="<?php echo $hijo->fields('descripcion')?>" <?php echo $css_td?>/></td>
</tr>
<tr>
	<th <?php echo $css_td?>>Sociedad</th>
	<td><input type="text"  size="30" name="sociedad" value="<?php echo $hijo->fields('sociedad')?>" <?php echo $css_td?>/></td>
</tr>
<tr>
	<th <?php echo $css_td?>>Original</th>
	<td <?php echo $css_td?>>
	SI
	<input type="radio" name="original" value="1" <?php if($hijo->fields('original')==1) echo "checked"?> <?php echo $css_td?>>
	<input type="radio" name="original" value="0" <?php if($hijo->fields('original')==0) echo "checked"?> <?php echo $css_td?>>
	</td>
</tr>
<tr>
	<th <?php echo $css_td?>>Naturaleza Documento</th>
	<td><input type="text"  size="30" name="naturaleza_doc" value="<?php echo $hijo->fields('naturaleza_doc')?>" <?php echo $css_td?>/></td>
</tr>
<tr>
	<th <?php echo $css_td?>>Fecha Creacion (yyyy-mm-dd)</th>
	<td><input type="text"  size="30" name="fecha_creacion" value="<?php echo $hijo->fields('fecha_creacion')?>" <?php echo $css_td?>/></td>
</tr>
<tr>
	<th <?php echo $css_td?>>Ubicacion</th>
	<td><input type="text"  size="30" name="ubicacion" value="<?php echo $hijo->fields('ubicacion')?>" <?php echo $css_td?>/></td>
</tr>
<tr>
	<th <?php echo $css_td?>>Observaciones</th>
	<td>
	<textarea name="observaciones" rows="3" cols="15" <?php echo $css_td?>><?php echo $hijo->fields('observaciones')?></textarea>
	</td>
</tr>
<input  type="hidden" name="id" value="<?php echo $_GET['id']?>" />
<tr><th colspan="2" <?php echo $css_h1?>>Adjuntos Actuales</th></tr>
<?
$sql="SELECT * FROM 
calsuper_calsuperior.adjunto_nodo 
WHERE id_nodo=".$_GET['id']."";
$adjuntos=$_SESSION[APL]->bd->getRs($sql);
$j=1;
if($adjuntos->NumRows()==0)
	echo "<tr><th colspan='2' ".$css_td.">Sin Adjuntos</th></tr>";
else
{
	
	while(!$adjuntos->EOF)
	{
		echo "<tr><th ".$css_td.">Adjunto ".$j."</th>
		<td ".$css_td."> 
		<a href='descargar_adjunto.php?id_adjunto=".$adjuntos->fields('id')."' border='0' target='_blank'>
		<img src='img/attachment.gif'></a> ".$adjuntos->fields('adjunto')."</td></tr>";
		$j++;
		$adjuntos->MoveNext();
	}
}


?>


<tr><th colspan="2" <?php echo $css_td?>>Adjuntos</th></tr>


<?php for ($i=$j;$i<=20;$i++)
{?>
<tr><th <?php echo $css_td?>>Adjunto <?php echo $i?></th><td><input type="file" maxlength="100" size="50" name="adjunto_<?php echo $i?>" <?php echo $css_td?>/></td></tr>
<?
}
?>

<tr><th colspan="2">
<img src="../Copy of pruebas/img/cross.png" onClick="cerrar()" style="cursor:pointer"/>
<img src="../Copy of pruebas/img/tick.png" onClick="guardar()" style="cursor:pointer"/>
</th></tr>
</form>
</table>
<?
}
?>
</body>
</html>
