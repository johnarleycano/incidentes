<?php
///////////////////////////////////////////////////////
//
//		CLASE clscesoatcups.php
//		Desarrollada por: DEPARTAMENTO DE DESARROLLO
//		Empresa: VENUS INGENIERIA DE SOFTWARE LTDA
//		Proyecto: EMPRESARIAL EN SALUD
//		Fecha: 22 DE FEBRERO DE 2007
//		Comentarios:
//			Clase que permite buscar una cadena entre la tabla vcodigo_procedimiento para sacar un listado de procedimientos y seleccionar al que se le quieran cambiar los datos y almacenar estos cambios en la base de datos
//
///////////////////////////////////////////////////////	

class clscesoatcups
{
	
	var $avcus_str_codigo_soat			= null; // Codigo a Buscar
	var $avcus_str_nombre_soat			= null; // Nombre a Buscar
	
		
	//Constructor de la clase
	function clscesoatcups()
	{
		//$this->fcancelar();
	}
	
	///////////MÉTODOS DE INTERFASE///////////
			
	//Descripción:  Función para realizar los reemplazos de los tokens por los controles 
	//instanciados de la clase campo
	
	function fmostrar_interfaz()
	{
		$ruta = "../";
		$lArr_validaciones = null;
			
		$lbloqueado = true;
			
		if($this->amodo == 0 ||  $this->amodo == 3)
		{
			$lbloqueado = false;
		}
		$lplantilla .= $_SESSION["caemp"]->fgetplantilla("frm_cesoatcups.html", "../");
		//vtic_pky_codigo_tipo v.vtic_str_tipo_consulta, v.vgrc_for_grupo, v.vtic_str_subgrupo, v.vtic_boo_activar, v.vtic_mon_costo 
			
		//--Buscar por Código
		$ob0 = new clscampo();
		$ob0->finicializar("vcus_str_codigo_soat");
		$ob0->aetiqueta = $_SESSION["caemp"]->atraductor->ffrase(142);
		$ob0->arequerido = true;
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "cadena", $ob0->fgetetiqueta());	
		$ob0->atamano = 10;
		$ob0->alongitud = 20;
		$ob0->aactivo = true;
		$ob0->avalor = $this->avcus_str_codigo_soat;
		$ob0->ruta_a_raiz = "../";
		$ob0->ajs = "onkeydown='validar_enter(event,this);'";	
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "ccadena_codigo", $ob0->fgetcampo());	
		
		//--Buscar Por Descripcion
		$ob1 = new clscampo();
		$ob1->finicializar("vcus_str_nombre_soat");
		$ob1->atamano = 120;
		$ob1->alongitud = 100;
		$ob1->aactivo = true;
		$ob1->aayuda = 880;
		$ob1->avalor = $this->avcus_str_nombre_soat;
		$ob1->ruta_a_raiz = '../';			
		$ob1->ajs = "onkeydown='validar_enter(event,this);'";											
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "ccadena_descripcion", $ob1->fgetcampo());
		$lArr_validaciones[] = $ob1->fgetvalidacion();
		
		//Otros Objetos	
		//$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "cargar", getBoton(1, $_SESSION["caemp"]->atraductor->ffrase(50), 'onclick="verificar_procedimientos()"', "../",'','','',738));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "buscar", getBoton(1, $_SESSION["caemp"]->atraductor->ffrase(8), 'onclick="validar_longitud()"', "../",'','','',162));
		//document.formulario.vtic_pky_codigo_tipo.value,document.formulario.vtic_mon_costo.value,document.formulario.vtic_boo_activar.checked
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "cerrar", getBoton(1, $_SESSION["caemp"]->atraductor->ffrase(17), 'onclick="cerrar()"', "../",'','','',161));
		
		//Tokens
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "activo", strtoupper($_SESSION["caemp"]->atraductor->ffrase(351)));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "inactivo", strtoupper($_SESSION["caemp"]->atraductor->ffrase(923)));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "frase_cerrar", $_SESSION["caemp"]->atraductor->ffrase(15));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "frase_digitar", $_SESSION["caemp"]->atraductor->ffrase(941));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "frase_buscar", $_SESSION["caemp"]->atraductor->ffrase(705));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "soat", $_SESSION["caemp"]->atraductor->ffrase(522));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "cups", $_SESSION["caemp"]->atraductor->ffrase(1214));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "error",  $_SESSION["caemp"]->atraductor->ffrase(614));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "criterio",  $_SESSION["caemp"]->atraductor->ffrase(1249));
		
		//--Titulos tabla
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "tcodigo", $_SESSION["caemp"]->atraductor->ffrase(14));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "tgrupo", $_SESSION["caemp"]->atraductor->ffrase(563));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "tdescripcion", $_SESSION["caemp"]->atraductor->ffrase(105));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "ttarifa", $_SESSION["caemp"]->atraductor->ffrase(32));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "tvalor", $_SESSION["caemp"]->atraductor->ffrase(255));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "tsubgrupo", $_SESSION["caemp"]->atraductor->ffrase(218));
		$lplantilla  = $_SESSION["caemp"]->ftoken($lplantilla, "tcuenta", $_SESSION["caemp"]->atraductor->ffrase(175));
		
		return $lplantilla;
			
	}// fin de: fmostrar_interfaz()
}
?>