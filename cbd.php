<?php
///////////////////////////////////////////////////////
//
//		Clase Base de Datos
//		Desarrollada por: Jhon Fredy García
//		Empresa: Algoritmo Software S.A
//		Fecha: 14 Junio 2005
//		Comentarios:
//			Esta clase permite hacer conexiones con multiples bases de datos, además
//			es la capa encargada de enlazar base de datos con interfaz
//		Cambios:
//			Autor:
//			Fecha:
//			Descripcion:
//
///////////////////////////////////////////////////////



//Include para el ADODB
include_once("adodb/adodb.inc.php");

class cbd{

    //Variables de conexion
    var $cnx;
    var $cantidad_cnx;
    var $conexion_principal;
    
    //Arreglos de Variables de Base de Datos
    var $tipo_bd;      // Tipo de Base de Datos:
	var $host_bd;      // Host donde esta la base de datos
	var $usuario_bd;   // Usuario de la Base de datos
	var $clave_bd;     // Clave de Base de Datos
	var $nombre_bd;    // Nombre de la base de datos a Conectar
	var $estado_bd;    // Estado de la Conexion


    function cbd($cnx_ppal){
    //- Constuctor de la Clase
		$this->conexion_principal = $cnx_ppal;
		
		//Cantidad de Conexiones Acuales
        $this->cantidad_cnx = 1;

        //Indice de Conexion
        $ncnx = 0;
		
		//PRODUCCION
		//Conexion 0 con Sifi Beta (Desarollo)



        $this->tipo_bd[$ncnx]    = "mysql";
		$this->host_bd[$ncnx]    = "127.0.0.1";
        $this->usuario_bd[$ncnx] = "root";
        $this->clave_bd[$ncnx]   = "";
        // $this->nombre_bd[$ncnx]  = "incidentes";
		$this->nombre_bd[$ncnx]  = "incidentes";
        $this->estado_bd[$ncnx]  = 0;
        $this->cantidad_cnx++;
        //Siguiente Conexion
        $ncnx++;

		//Conexion 1 con SIEC Beta (Desarollo)
        $this->tipo_bd[$ncnx]    = "";
		$this->host_bd[$ncnx]    = "";
        $this->usuario_bd[$ncnx] = "";
        $this->clave_bd[$ncnx]   = "";
		$this->nombre_bd[$ncnx]  = "";
        $this->estado_bd[$ncnx]  = 0;
        $this->cantidad_cnx++;
        //Siguiente Conexion
        $ncnx++;

	
		
    }
    
    function validar_conexion($numero_conexion){
    //- Procedimiento para validar una conexion
    
        if(($numero_conexion >= 0 ) && ($numero_conexion <= count($this->tipo_bd))){
            return true;
        }
        else{
            die("Error. Trata de hacer acceder a la conexion '".$numero_conexion."' no valida");
        }

    }
    
    function conectar($numero_conexion = -1){

		if($numero_conexion == -1)
			$numero_conexion = $this->conexion_principal;

        $this->validar_conexion($numero_conexion);
        $this->cnx[$numero_conexion] = &ADONewConnection($this->tipo_bd[$numero_conexion]);
        if(!$this->cnx[$numero_conexion]->Connect($this->host_bd[$numero_conexion], $this->usuario_bd[$numero_conexion], $this->clave_bd[$numero_conexion], $this->nombre_bd[$numero_conexion]))
		{
            echo "Error de Conexion. No fue capaz de establecer la conexion";
            $this->estado_bd[$numero_conexion] = 0;
        }
        else
            $this->estado_bd[$numero_conexion] = 1;
        
    }//conectar
    
    function desconectar($numero_conexion = -1){
		
		if($numero_conexion == -1)
			$numero_conexion = $this->conexion_principal;

        $this->validar_conexion($numero_conexion);
        if($this->estado_bd[$numero_conexion] == 1){
            $this->cnx[$numero_conexion]->Close();
            $this->estado_bd[$numero_conexion] = 0;
        }
        
    }//desconectar
    
    function desconectar_todas(){

      for($i=0;$i < $this->cantidad_cnx ; $i++){
        $this->desconectar($i);
      }
      
    }//desconectar_todas
    
    function dato($sql, $numero_conexion = -1){

		if($numero_conexion == -1)
			$numero_conexion = $this->conexion_principal;
        
        $this->validar_conexion($numero_conexion);
        $this->conectar($numero_conexion);
        $RS = $this->cnx[$numero_conexion]->Execute($sql);
		if (!$RS ){
            DIE ($this->cnx[$numero_conexion]->ErrorMsg()."<br><b>Error al ejecutar consulta</b> ".$sql);
        }
		return $RS->fields[0];

    }//dato

	function datoO($sql, $parametros = "", $numero_conexion = -1){

		if($numero_conexion == -1)
			$numero_conexion = $this->conexion_principal;
        
        $this->validar_conexion($numero_conexion);
        $this->conectar($numero_conexion);
        
		if($parametros == "")
			$RS = $this->cnx[$numero_conexion]->Execute($sql);
		else
			$RS = $this->cnx[$numero_conexion]->Execute($sql, $parametros);

		if (!$RS ){
            DIE ($this->cnx[$numero_conexion]->ErrorMsg()."<br><b>Error al ejecutar consulta</b> ".$sql);
        }
		return $RS->fields[0];

    }//dato
    
    function ejecutar($sql, $numero_conexion = -1){

		if($numero_conexion == -1)
			$numero_conexion = $this->conexion_principal;

        $this->validar_conexion($numero_conexion);
        $this->conectar($numero_conexion);
        $RS = $this->cnx[$numero_conexion]->Execute($sql);
        if (!$RS)
            DIE ($this->cnx[$numero_conexion]->ErrorMsg()."<br><b>Error al ejecutar consulta</b> ".$sql);
        else
            return true;
        
    }//ejectutar

	function ejecutarO($sql, $parametros = "", $numero_conexion = -1){

		if($numero_conexion == -1)
			$numero_conexion = $this->conexion_principal;

        $this->validar_conexion($numero_conexion);
        $this->conectar($numero_conexion);
		
		if($parametros == "")
			$RS = $this->cnx[$numero_conexion]->Execute($sql);
		else
			$RS = $this->cnx[$numero_conexion]->Execute($sql,$parametros);
		
        if (!$RS)
            DIE ($this->cnx[$numero_conexion]->ErrorMsg()."<br><b>Error al ejecutar consulta</b> ".$sql);
        else
            return true;
        
    }//ejectutar
    
    function getRs($sql, $numero_conexion = -1){

		if($numero_conexion == -1)
			$numero_conexion = $this->conexion_principal;
		
        $this->validar_conexion($numero_conexion);
		$this->conectar($numero_conexion);
        $RS = $this->cnx[$numero_conexion]->Execute($sql);
		
        if (!$RS)
	      DIE ($this->cnx[$numero_conexion]->ErrorMsg()."<br><b>Error al ejecutar consulta</b> ".$sql);
        else
            return $RS;

    }//ejectutar

	function getRsO($sql, $parametros = "" , $numero_conexion = -1){

		if($numero_conexion == -1)
			$numero_conexion = $this->conexion_principal;

        $this->validar_conexion($numero_conexion);
        $this->conectar($numero_conexion);

        if($parametros == "")
			$RS = $this->cnx[$numero_conexion]->Execute($sql);
		else
			$RS = $this->cnx[$numero_conexion]->Execute($sql, $parametros);

        if (!$RS)
            DIE ($this->cnx[$numero_conexion]->ErrorMsg()."<br><b>Error al ejecutar consulta</b> ".$sql);
        else
            return $RS;

    }//ejectutar
	
	function abrir_archivo($archivo)
	{
		if(!file_exists($archivo))
		{
			$fp = fopen($archivo, "w");
			fputs($fp, "");
			fclose($fp);
		}
	}

	//funcion para insertar una linea en el archivo de texto.
	function grabar_linea($linea,$archivo)
	{
		$fp = fopen($archivo, "a+");
		fputs($fp, $linea."\r\n");
		fclose($fp);
	}
	
	function cargarblob($tabla,$campo,$dato,$donde,$numero_conexion = -1)
	{
		if($numero_conexion == -1)
			$numero_conexion = $this->conexion_principal;
		$exitoso=$this->cnx[$numero_conexion]->UpdateBlob($tabla,$campo,$dato,$donde);
		return $exitoso;
	}
    
}//cbd
?>
