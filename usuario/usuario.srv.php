<?php
include_once("../clases/capp.php");
include_once("../libs/php/JSON.php");
session_start();

$json = new Services_JSON();

$pComando = $_POST["comando"];

if( $pComando=="VER" )
{
	$pId = $_POST["id"];
	$sql = "SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios WHERE id=".$pId;
	$rs = $_SESSION[APL]->bd->getRs($sql);
	
	$arrUsu->id			= $rs->fields["id"];
	$arrUsu->login		= $rs->fields["login"];
	$arrUsu->cedula		= $rs->fields["cedula"];
	$arrUsu->nombre		= utf8_encode($rs->fields["nombres"]);
	$arrUsu->apellido	= utf8_encode($rs->fields["apellidos"]);
	$arrUsu->correo		= utf8_encode($rs->fields["correo"]);
	$arrUsu->celular	= $rs->fields["celular"];
	$arrUsu->perfil		= $rs->fields["id_perfil"];
	$arrUsu->estado		= $rs->fields["estado"];
	$arrUsu->enviar		= $rs->fields["enviar_correo"];
	$arrUsu->fechacre	= $rs->fields["fecha_creacion"];
	$arrUsu->clave		= $rs->fields["clave"];
	$arrUsu->rutaFirma	= '../firmas/'.$rs->fields["firma"].'?'.date("YmdHis");
	
	echo json_encode($arrUsu);
}
else if( $pComando=="GRA" )
{
	$pId  = $_POST["id"];
	$pLog = $_POST["login"];
	$pCed = $_POST["cedula"];
	$pNom = $_POST["nombre"];
	$pApe = $_POST["apellido"];
	$pCor = $_POST["correo"];
	$pCel = $_POST["celular"];
	$pPer = $_POST["perfil"];
	$pEst = $_POST["estado"];
	$pEnv = $_POST["enviar"];
	$pCla = $_POST["clave"];
	$pOld = $_POST["clave_old"];
	
	$arrRes->res = 'OK';
	
	if( $pId=="" or $pId=="-1" )
	{
		$arrRes->msg = 'Usuario Adicionado con exito.';
		
		$sql = "INSERT INTO ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
				( login,estado,id_perfil,celular,nombres,apellidos,fecha_creacion,fecha_clave,clave,correo,enviar_correo,cedula )
				VALUES('$pLog','$pEst',$pPer,'$pCel','$pNom','$pApe','".date("Y-m-d")."','".date("Y-m-d")."','$pCla','$pCor','$pEnv','$pCed');";
		if( !$_SESSION[APL]->bd->ejecutar($sql) )
		{
			$arrRes->res = 'ERR';
			$arrRes->msg = 'Se presentaron problemas al adicionar el usuario.';
		}
		else
		{
			$sql = "SELECT max(id) FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios";
			$pId = $_SESSION[APL]->bd->dato($sql);
			$arrRes->id = $pId;
		}
	}
	else
	{
		$arrRes->msg = 'Usuario Actualizado con exito.';
		
		$sql = "UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
				SET login='$pLog',estado='".$pEst."',id_perfil=".$pPer.",celular='".$pCel."',cedula='$pCed',
					nombres='".$pNom."',apellidos='".$pApe."',correo='".$pCor."',enviar_correo='$pEnv'
				WHERE id=".$pId;
		if( $_SESSION[APL]->bd->ejecutar($sql) )
		{
			if( $pCla!=$pOld )
			{
				$sql = "UPDATE ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
						SET clave='$pCla', fecha_clave='".date("Y-m-d")."'
						WHERE id=".$pId;
				if( !$_SESSION[APL]->bd->ejecutar($sql) )
				{
					$arrRes->res = 'ERR';
					$arrRes->msg = 'Se presentaron problemas al actualizar la clave al usuario.';
				}
			}
			
			$arrRes->id = $pId;
		}
		else
		{
			$arrRes->res = 'ERR';
			$arrRes->msg = 'Se presentaron problemas al actualizar el usuario.';
		}
	}
	
	echo json_encode($arrRes);
}

//echo json_encode($responce);

?>