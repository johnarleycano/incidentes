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


class cusuario{
	//Usuario generador
    var $id					= NULL;	//nit
	var $nombres			= NULL;
	var $apellidos			= NULL;
	var $correo				= NULL;
	var $id_perfil			= NULL;
	var $celular			= NULL;
	var $cedula			= NULL;
	var $login				= NULL;
    var $clave				= NULL;	//Nombre
	var $estado				= NULL;
	var $fecha_creacion		= NULL;
	var $fecha_clave		= NULL;
	var $firma				= NULL;
	var $enviar_correo		= NULL;

	
	
	
	var $valido				= 0;	//Validado en el sistema


    function cusuario(){		
    }
	
	function set_usuario($p_login, $p_clave){
		$this->login = $p_login;
		$this->clave = $p_clave;		
	}

	function validar_usuario(){
		$sql = "select u.id as id, nombres,apellidos,id_perfil,celular,estado,fecha_creacion,fecha_clave,clave,correo,cedula,firma,enviar_correo from 
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u
		where 
		BINARY clave = BINARY '".$this->clave."' and 
		BINARY login = BINARY '".$this->login."'";
$Rs =  $_SESSION[APL]->bd->getRs($sql);
		if($Rs->RecordCount() == 0)
		{
			//echo "destroy";
			$this->destroyUsuario();
		}
		else
		{
			if( $Rs->fields[5] =='A')
			{
				$this->id = $Rs->fields[0];
				$this->nombres = $Rs->fields[1];
				$this->apellidos = $Rs->fields[2];
				$this->id_perfil = $Rs->fields[3];
				$this->celular = $Rs->fields[4];
				$this->estado = $Rs->fields[5];
				$this->fecha_creacion = $Rs->fields[6];
				$this->fecha_clave = $Rs->fields[7];
				$this->clave = $Rs->fields[8];
				$this->correo= $Rs->fields[9];
				$this->cedula= $Rs->fields[10];
				$this->firma= $Rs->fields[11];
				$this->enviar_correo= $Rs->fields[12];

				$this->valido = 1;
			}
			else
				$this->valido = -1;
		}
	}
	
	function validar_usuario_reg($id){
		$sql = "select u.id as id, nombres,apellidos,id_perfil,celular,estado,fecha_creacion,fecha_clave,clave,correo,cedula,firma,enviar_correo from 
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u
		where 
		u.id = '".$id."' ";
		
		$Rs =  $_SESSION[APL]->bd->getRs($sql);
		if($Rs->RecordCount() == 0)
		{
			echo "destroy";
			$this->destroyUsuario();
		}
		elseif($Rs->fields[5] =='A')
		{
			
				$this->id = $Rs->fields[0];
				$this->nombres = $Rs->fields[1];
				$this->apellidos = $Rs->fields[2];
				$this->id_perfil = $Rs->fields[3];
				$this->celular = $Rs->fields[4];
				$this->estado = $Rs->fields[5];
				$this->fecha_creacion = $Rs->fields[6];
				$this->fecha_clave = $Rs->fields[7];
				$this->clave = $Rs->fields[8];
				$this->correo= $Rs->fields[9];
				$this->cedula= $Rs->fields[10];
				$this->firma= $Rs->fields[11];
				$this->enviar_correo= $Rs->fields[12];
				$this->valido = 1;
		}
		else
			$this->valido = -1;
	}

	function destroyUsuario(){
		$this->id					= NULL;	//nit
		$this->nombres			= NULL;
		$this->apellidos			= NULL;
		$this->correo				= NULL;
		$this->id_perfil			= NULL;
		$this->celular				= NULL;
		$this->login				= NULL;
    	$this->clave				= NULL;	//Nombre
		$this->estado				= NULL;
		$this->fecha_creacion		= NULL;
		$this->fecha_clave			= NULL;
		$this->cedula			= NULL;
		$this->firma			= NULL;
		$this->enviar_correo		= NULL;
		
		
		$this->valido				= 0;	
	}	
	
	function mostrar_login(){		
	
		$dumy =	$_SESSION[APL]->cabeceras(".");
		$dumy = $_SESSION[APL]->get_plantilla("entrada_usuario.html") ;
		
	
		
		return $dumy;
	}
}
?>