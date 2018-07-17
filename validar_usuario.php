<?php
include_once "clases/capp.php";
session_start();
/*
if (!isset($_SESSION[APL])  || $_SESSION[APL]->usuario->id == "" ){
   $_SESSION[APL] =& new capp();
	header("location:entrada_usuario.php?msg=".$_POST["login"]);
}
*/
$p_login = $_SESSION[APL]->verificar_cadena($_POST["login"]);
$p_clave = $_SESSION[APL]->verificar_cadena($_POST["clave"]);

$_SESSION[APL]->usuario->set_usuario($p_login, $p_clave);
$_SESSION[APL]->usuario->validar_usuario();


if($_SESSION[APL]->usuario->valido == 1)
{

		$sql="SELECT round((".date('Y-m-d')."-fecha_clave))
		from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
		where id=".$_SESSION[APL]->usuario->id."";
		
		$rs=$_SESSION[APL]->bd->getRs($sql);
		$dias=$rs->fields[0];
		if($dias==-1 || $dias>1000)
			$url="infousuario.php";
		else
		{
			if($_SESSION[APL]->usuario->id_perfil==3)
				$url = "registro_vial/registro_vial.php";//$url = "registro_adm_vial.php";
			else
				$url = "registro_inicial.php";
		}
}
else if($_SESSION[APL]->usuario->valido == -1 || $_SESSION[APL]->usuario->valido == "-1")
{
	$_SESSION[APL]->usuario->destroyUsuario();
	$url = "entrada_usuario.php?msg=Usuario que se encuentra inactivo...";
}
else
{
	$url = "entrada_usuario.php?msg=Login y/o Clave no validos";
}


header("location:".$url);
?>