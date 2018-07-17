<?php
///////////////////////////////////////////////////////
//
//		Clase Usuario
//		Desarrollada por: Jhon Fredy García
//		Empresa: Algoritmo Software S.A
//		Fecha: 14 Junio 2005
//		Comentarios:
//			Clase para el manejo de los usuarios validados en el sistema.
//		Cambios:
//			Autor:
//			Fecha:
//			Descripcion:
//
///////////////////////////////////////////////////////


class cinterfas{

    function cinterfas(){		
    }
	
	function pestana($id_actual){
		$enlace = null;
		
		
		$sql="SELECT round((".date('Y-m-d')."-fecha_clave))
		from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios
		where id=".$_SESSION[APL]->usuario->id."";
		
		$rs=$_SESSION[APL]->bd->getRs($sql);
		$dias=$rs->fields[0];
		
		$rutImg = "";
		if( !file_exists($rutImg.'img/logo.jpg') )
			$rutImg = "../";
		if($dias==-1 || $dias>1000)
		{
			
			$enlace[0] = $rutImg."infousuario.php";
			$enlace[1] = $rutImg."infousuario.php";
			
			$titulo[0]= "Busqueda";
			$titulo[1]= "Editar Usuario";
			
			if($_SESSION[APL]->usuario->id_perfil==2)
			{
				$titulo[2] = "Registro SOS";
				$enlace[2] = $rutImg."infousuario.php";				
				$enlace[3] = $rutImg."infousuario.php";
				$titulo[3] = "Registro Inicial";
				$enlace[4] = $rutImg."infousuario.php";
				$titulo[4]= "Reportes";

			}
			if($_SESSION[APL]->usuario->id_perfil==3)
			{
				
				$titulo[2] = "Registro Adm Vial";
				$enlace[2] = $rutImg."infousuario.php";
				$titulo[3] = "Registro SOS";
				$enlace[3] = $rutImg."infousuario.php";
				$enlace[4] = $rutImg."infousuario.php";
				$titulo[4] = "Reportes";
			}
			
			if($_SESSION[APL]->usuario->id_perfil==0)
			{
				$enlace[2] = $rutImg."infousuario.php";
				$titulo[2] = "Registro Inicial";
				$titulo[3] = "Registro SOS";
				$enlace[3] = $rutImg."infousuario.php";
				$titulo[4] = "Registro Adm Vial";
				$enlace[4] = $rutImg."infousuario.php";
				$enlace[5] = $rutImg."infousuario.php";
				$titulo[5] = "Reportes";
				$titulo[6] = "Parametros";
				$enlace[6] = $rutImg."infousuario.php";
			}
		}
		else
		{
			
			$enlace[0] = $rutImg."busqueda/busqueda.php";//busqueda
			$enlace[1] = $rutImg."infousuario.php";//informacion usuario
			
			$titulo[0]= "Busqueda";
			$titulo[1]= "Editar Usuario";
			
			if($_SESSION[APL]->usuario->id_perfil==2)
			{
				$enlace[2] = $rutImg."registro_inicial.php";//radicado
				$titulo[2] = "Registro Inicial";
				$titulo[3] = "Registro SOS";
				$enlace[3] = $rutImg."registro_sos/registro_sos.php";
				$enlace[4] = $rutImg."reportes.php";
				$titulo[4] = "Reportes";
			}
			if($_SESSION[APL]->usuario->id_perfil==3)
			{
				$titulo[2] = "Registro SOS";
				$enlace[2] = $rutImg."registro_sos/registro_sos.php";
				$titulo[3] = "Registro Adm Vial";
				$enlace[3] = $rutImg."registro_vial/registro_vial.php";
				$enlace[4] = $rutImg."reportes.php";
				$titulo[4] = "Reportes";

				
			}
			
			if($_SESSION[APL]->usuario->id_perfil==0)
			{
				$enlace[2] = $rutImg."registro_inicial.php";//radicado
				$titulo[2] = "Registro Inicial";
				$titulo[3] = "Registro SOS";
				$enlace[3] = $rutImg."registro_sos/registro_sos.php";
				$titulo[4] = "Registro Adm Vial";
				$enlace[4] = $rutImg."registro_vial/registro_vial.php";
				$enlace[5] = $rutImg."reportes.php";
				$titulo[5] = "Reportes";
				$titulo[6] = "Parametros";
				$enlace[6] = $rutImg."parametrizacion.php";
			}
		}
		
		$dumy  = '<center><table  cellpadding="5" cellspacing="3" ><tr height="20"><th><img src="'.$rutImg.'img/logo.jpg"></th>';
		$colsp=1;
		for($i=0; $i < 7 ; $i++)
		{
			if(isset($enlace[$i]))
			{
			
				$b = 0;
				$clase="resaltar";
				$eventos = 'onmouseout="cambio_bgcolor(this,\'resaltar\')" onmouseover="cambio_bgcolor(this, \'LegendSt\')"';
				if($id_actual == $i)
				{
					$b = 1;
					$clase="LegendSt";
					$eventos = '';
				}
				
				$dumy .= '<th '.$eventos.' valign="middle"
				onClick="espere.style.display=\'\';window.open(\''.$enlace[$i].'\',\'_self\')"
				style="cursor:pointer;" class="'.$clase.'" height="20">
				&nbsp;&nbsp;&nbsp;&nbsp;'.$titulo[$i].'&nbsp;&nbsp;&nbsp;&nbsp;<img height="19" src="'.$rutImg.'img/control_play.png">
				</th>';
				
			//	<img '.$eventos.' height="25"  src="img/p'.$i.$b.'.jpg" border="0" >
			$colsp++;
			}
		}
		$eventos = 'onmouseout="cambio_bgcolor(this,\'resaltar\')" onmouseover="cambio_bgcolor(this,\'LegendSt\')"';
		
		$dumy .= '<th '.$eventos.' valign="middle"
		onClick="espere.style.display=\'\';window.open(\''.$rutImg.'entrada_usuario.php\',\'_self\')"
		style="cursor:pointer" class="resaltar"  height="20">
		&nbsp;&nbsp;&nbsp;&nbsp;Salir&nbsp;&nbsp;&nbsp;&nbsp;<img height="19" src="'.$rutImg.'img/control_play.png"></th>';
		//<img height="25" '.$eventos.' src="img/p40.jpg" border="0" >
		$dumy .= '</tr>
		<tr><td colspan="'.($colsp+1).'"  align="right"><b>Usuario Actual:</b> '.$_SESSION[APL]->usuario->nombres.' '.$_SESSION[APL]->usuario->apellidos.'</td></tr>
		</table></center>';
		//$dumy .= '<a href="javascript:ayuda()"><font class="normalAO"><b>Ayuda</b></font><img src="img/help.png" border="0" /></a>';
		$dumy .= '
		<script language="javascript">
			
			function cambio_bgcolor(obj,clase){
				obj.className=clase;	
			}
			
		</script>		

		';
		return $dumy;
	}
	
	function lanzador_pendientes(){
		return "Pendientes";
	}
}
?>