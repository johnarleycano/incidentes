<?php
include_once "clases/capp.php";
session_start();

if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
    //$_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php");
}


if(isset($_GET["msg"]))
	echo $_SESSION[APL]->msg($_GET["msg"]);

echo $_SESSION[APL]->cabeceras();


$sql="SELECT titulo, texto FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_ayuda WHERE id=".$_GET['id'];
$rs=$_SESSION[APL]->bd->getRs($sql);

$size_td=$_SESSION[APL]->tam_normal;
$font_color_td=$_SESSION[APL]->color_normal;
$size_h1=$_SESSION[APL]->tam_titulo;
$font_color_h1=$_SESSION[APL]->color_titulo;
$color_campos=$_SESSION[APL]->color_campos;



$css_td='style="font-size: '.$size_td.'; color:'.$font_color_td.'" bgcolor="#'.$_SESSION[APL]->bgcolor_normal.'"';
$css_font='style="font-family:'.$_SESSION[APL]->fuente.';font-size: '.$size_td.'; color:'.$color_campos.'"';
$css_th='style="font-size: '.$size_td.'; color:'.$font_color_h1.'" bgcolor="#'.$_SESSION[APL]->bgcolor_titulo.'"';
$css_h1='style="font-size: '.$size_h1.'; color:'.$font_color_h1.'" bgcolor="#'.$_SESSION[APL]->bgcolor_titulo.'"';
?>
<center>
<table style="font-family:<?php echo $_SESSION[APL]->fuente?>" cellspacing='1' cellpadding='3' border='0' bgcolor='#444444'
width="400px">
<tr><th colspan="4" <?php echo $css_h1?>  height="30"><?php echo $rs->fields[0]?></th></tr>
<tr><td colspan="4" <?php echo $css_td?>  height="30"><?php echo $rs->fields[1]?></td></tr>
</table>
</center>
</body>
</html>