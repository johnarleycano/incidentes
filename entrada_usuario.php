<?php
include_once "clases/capp.php";
session_start();

$_SESSION[APL] =& new capp();

echo $_SESSION[APL]->cabeceras("",false);
echo $_SESSION[APL]->usuario->mostrar_login();

if(isset($_GET["msg"]))
	echo $_SESSION[APL]->msg($_GET["msg"]);
?>

<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px;
	font-family: "<?php echo $_SESSION[APL]->fuente?>";
}
-->
</style>
</body>
</html>