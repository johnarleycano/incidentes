<?
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


class carbol
{

	var	$nodo_raiz		= NULL;
	var $id_raiz		= NULL;
	
	function carbol()
	{		
    }
		
	function cargar()
	{
		$sql="SELECT id FROM calsuper_calsuperior.nodo
		WHERE id_padre is NULL";
		$id_raiz = $_SESSION[APL]->bd->dato($sql);	 
		$this->id_raiz=$id_raiz;
		$this->nodo_raiz= new cnodo();
		$this->nodo_raiz->cargar($id_raiz);

	}
	
	
	function retornar_nodo($nodo,$id)
	{
		$encontrado=false;
		
		if($nodo->id==$id)
		{
			$encontrado=true;
			
			return $nodo;
		}
		else
		{
			for($i=0;$i<count($nodo->hijos);$i++)
			{
				
				$nodo_tmp=$this->retornar_nodo($nodo->hijos[$i],$id);
				if($nodo_tmp!=false)
				{
					$encontrado=true;
					return $nodo_tmp;
				}	
				
			}
			return $encontrado;
		}
		
	}
	function nivel_maximo($nodo)
	{
		
		if(count($nodo->hijos)==0)
		{
			
			return $nodo->nivel;
		}
		else
		{
			$max=0;
			for($i=0;$i<count($nodo->hijos);$i++)
			{
				
				$tmp=$this->nivel_maximo($nodo->hijos[$i]);
				if($max<$tmp)
				{
					
					$max=$tmp;
				}
			}
			
			return $max;
		}
	}
	function renumerar_nodos($nodo)
	{
		
		
	}
	
	function mostrar()
	{
		$this->nodo_raiz=NULL;
		$this->cargar();
		$texto1.="<script>
		function imprimirfisico(soloEsto)
		{ 

		var contenido = document.getElementById(soloEsto).outerHTML; 
		ventana=window.open('about :blank','ventana','width=680,height=600,top=0,left=3000'); 
		ventana.document.open(); 
		ventana.document.write('<html>');
		ventana.document.write('<head><title>Imprimiendo...</title></head><body onprint=\"self.close()\" leftmargin=\"0\" topmargin=\"0\"><center>'); 
		ventana.document.write(contenido); 
		ventana.document.write('</center></body></html>'); 
		ventana.document.close(); 
		ventana.print(); 
		ventana.focus(); 
		ventana.close(); 

		}
		
		
		
		function mostrar_todos()
		{
		";
		$sql="SELECT min(id),max(id) FROM calsuper_calsuperior.nodo";
		$todos=$_SESSION[APL]->bd->getRs($sql);
		$min=$todos->fields[0];
		$max=$todos->fields[1];
		$texto1.="
			for(i=".$min.";i<=".$max.";i++)
			{
				if(document.getElementById('ocultar_'+i))
					document.getElementById('ocultar_'+i).style.display = '';
			}
		}
		
		function ocultar_todos()
		{
		";
		$sql="SELECT min(id),max(id) FROM calsuper_calsuperior.nodo";
		$todos=$_SESSION[APL]->bd->getRs($sql);
		$min=$todos->fields[0];
		$max=$todos->fields[1];
		$texto1.="
			for(i=".$min.";i<=".$max.";i++)
			{
				if(document.getElementById('ocultar_'+i))
					document.getElementById('ocultar_'+i).style.display = 'none';
			}
		}

		
		
		
		function mostrar(id)
		{
		
		document.getElementById(id).style.display = '';
		
		}
		
		function ocultar(id)
		{
		
		document.getElementById(id).style.display = 'none';
		
		}</script>";
		$fuente=$_SESSION[APL]->fuente;
		$color_campos=$_SESSION[APL]->color_campos;
		$size_td=$_SESSION[APL]->tam_normal;
		$css_font="style='font-family:".$fuente.";font-size: ".$size_td."; color:".$color_campos."'";
		$texto1.="<center><input type='button' value='Imprimir' ".$css_font." onClick=\"imprimirfisico('arbol')\"></center><br>";
		$texto1.= "<table  cellpadding='0' cellspacing='10' class='pequenaAO' id='arbol'><tr>
		<td align='right' valign='top' >";
		$texto1.="<table cellspacing='1' cellpadding='3' border='1' bordercolor='#".$_SESSION[APL]->bgcolor_titulo."' bgcolor='#".$_SESSION[APL]->bgcolor_titulo."'>
		<tr>
   		<td bgcolor='#".$_SESSION[APL]->bgcolor_titulo."'>
		<font size=1 face='".$_SESSION[APL]->fuente."' color='#".$_SESSION[APL]->color_titulo."'>";
		//<b>".$this->nodo_raiz->id_archivo."</b><br>
		$texto1.="
		".$this->nodo_raiz->item."";
		$texto1.="<img src='img/ayuda.png' style='cursor:pointer' onclick='window.open(\"ayuda.php?id=1\",\"_blank\",\"menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=450,height=300\")'/>";
		$texto1.="</font>
		   </td>
		</tr>
		<tr>
			<td bgcolor='#".$_SESSION[APL]->bgcolor_normal."'>
		   <font face='".$_SESSION[APL]->fuente."' size=1 color='#".$_SESSION[APL]->color_normal."'>"; 
		$texto1.="<table><tr>";
		//$texto1.="<td><span style='cursor:pointer' onclick=window.open('nuevo_hijo.php?id=".$this->nodo_raiz->id."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=400')><img width='20' src='img/folder_explore.png' title='Ver y/o Editar' alt='Ver y/o Editar'></span></td>";
		if($_SESSION[APL]->usuario->id_perfil==0)//solo el administrador
			$texto1.="<td><span style='cursor:pointer' onclick=window.open('nuevo_hijo.php?id_padre=".$this->nodo_raiz->id."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=400')><img width='20' src='img/folder_add.png' title='Nuevo Nodo' alt='Nuevo Nodo'></span></td>";
		$texto1.="<td><span style='cursor:pointer;text-align:right;width:100%' onclick=mostrar_todos()><img height='19' src='img/exp_all.png' title='Expandir Todos' alt='Expandir Todos'></span></td>";
		$texto1.="<td><span style='cursor:pointer;text-align:right;width:100%' onclick=mostrar('ocultar_".$this->nodo_raiz->id."')><img height='19' src='img/exp_one.png' title='Expandir' alt='Expandir'></span></td>";
		$texto1.="</tr></table>";
		$texto1.="</font>
		   </td>
		</tr>
		</table>";
		$texto1.="</td>";
		$texto1.="<td>".$this->nodo_raiz->mostrar_hijos()."</td>";
		$texto1.="</tr>
		</table>";
		return $texto1;
	}
	
	
				
}

class cnodo
{
	var	$id				= NULL;
	var	$id_archivo		= NULL;
	var $posicion		= NULL;
	var	$item			= NULL;
	var	$descripcion	= NULL;
	var	$fecha_creacion	= NULL;
	var	$id_padre		= NULL;
	var	$nivel			= NULL;
	var	$tipo_acceso	= NULL;
	var $adjuntos		= NULL;
	var	$hijos			= NULL;
	
	function cnodo(){		
    }
	
	function mostrar_hijos()
	{
	
	$texto="";
	
	$texto.= "<table cellpadding='0' cellspacing='10' id='ocultar_".$this->id."' style='display:none' class='pequenaAO'>";
	$num=0;
	$nro_hijos=count($this->hijos);
	for($h=0;$h<$nro_hijos;$h++)
	{
		
		$texto.= "<tr>";
		//<img src='img/arrow_in.png' style='cursor:pointer' onclick=ocultar('ocultar_".$id."') alt='Contraer Hijos'>
		if($num==0)
		{
			$texto.="<td rowspan='".$nro_hijos."' valign='top' width='15px'>";
			$texto.="<table><tr>";
			if($this->id==$_SESSION[APL]->arbol->nodo_raiz->id)
			$texto.="<td><span class='Estilo3' style='cursor:pointer;width=15px' onclick=ocultar_todos()><img height='19' src='img/con_all.png' title='Contraer Todo' alt='Contraer Todo'></span></td>";
			
			$texto.="<td><span class='Estilo3' style='cursor:pointer;width=15px' onclick=ocultar('ocultar_".$this->id."')><img height='19' src='img/con_one.png' title='Contraer' alt='Contraer'></span></td>
			</tr></table>
			</td>
			<td rowspan='".$nro_hijos."' bgcolor='#".$_SESSION[APL]->bgcolor_titulo."' width='3px'>&nbsp;</td>";
			
			
		}
			
		$texto.="<td align='right'  valign='top'>";
		
		$texto.="<table cellspacing='1' cellpadding='3' border='1' bordercolor='#".$_SESSION[APL]->bgcolor_titulo."' bgcolor='#".$_SESSION[APL]->bgcolor_titulo."' width='100%'>
		<tr>
   		<td bgcolor='#".$_SESSION[APL]->bgcolor_titulo."'>
		<font size=1 face='".$_SESSION[APL]->fuente."' color='#".$_SESSION[APL]->color_titulo."'>";
		
		$texto.="
		<b>".$_SESSION[APL]->mostrar_id($this->hijos[$h]->id_archivo)."</b><br>".$this->hijos[$h]->item."";
		$texto.="</font>
	   </td>
		</tr>
		<tr>
		<td bgcolor='#".$_SESSION[APL]->bgcolor_normal."'>
	   	<font face='".$_SESSION[APL]->fuente."' size=1 color='#".$_SESSION[APL]->color_normal."'>"; 
		$texto.="<table><tr>";
		$texto.="<td><span style='cursor:pointer' onclick=window.open('nuevo_hijo.php?id=".$this->hijos[$h]->id."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=400')><img width='20' src='img/folder_explore.png' title='Ver y/o Editar' alt='Ver y/o Editar'></span></td>";
		if($_SESSION[APL]->usuario->id_perfil==0)//solo el administrador
		$texto.="<td><span style='cursor:pointer' onclick=window.open('cambiar_posicion.php?id=".$this->hijos[$h]->id."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=500,height=300')><img width='20' src='img/arrow_switch.png' title='Cambiar Posicion' alt='Cambiar Posicion'></span></td>";
		
		if($this->hijos[$h]->nivel<$_SESSION[APL]->max_niveles)
		{
			if($_SESSION[APL]->usuario->id_perfil==0)//solo el administrador
				$texto.="<td><span style='cursor:pointer' onclick=window.open('nuevo_hijo.php?id_padre=".$this->hijos[$h]->id."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=400')><img width='20' src='img/folder_add.png' title='Nuevo Nodo' alt='Nuevo Nodo'></span></td>";
			if(count($this->hijos[$h]->hijos)>0)
			{
				$texto.="<td><span style='cursor:pointer;text-align:right;width:100%' onclick=mostrar('ocultar_".$this->hijos[$h]->id."')><img height='19' src='img/exp_one.png' title='Expandir' alt='Expandir'></span></td>";
			}
		}
		
		$texto.="</tr></table>";
		$texto.="</font>
		   </td>
		</tr>
		</table>";
		
		
		
		
		$texto.="
		</td>
		<td>".$this->hijos[$h]->mostrar_hijos()."</td>
		</tr>";
		$num++;
	}
	$texto.= "</table>";
	return $texto;
	}
	
	
	
	//OPERACIONES BD
	function generar_bd($P_COT_SECUENCIA)
	{
		$this->COT_SECUENCIA = $P_COT_SECUENCIA;
		$this->DCO_SECUENCIA = $_SESSION[APL]->getSecuencia("COMPRA_DET_COTIZACION","DCO_SECUENCIA");
		$sql = "insert into COMPRA_DET_COTIZACION 
				( DCO_SECUENCIA, COT_SECUENCIA, UEL_SECUENCIA, DCO_CANTIDAD, DCO_VALOR_UNIDAD, DCO_MARCA, DCO_IVA, DCO_TIEMPO_ENTREGA, DCO_OBSERVACIONES, DCO_CANTIDAD_GARANTIA, DCO_UNIDAD_GARANTIA, DCO_ALTERNATIVO, PCS_SECUENCIA)
				values(".$this->DCO_SECUENCIA.", ".$this->COT_SECUENCIA.", ".$this->UEL_SECUENCIA.", ".$this->DCO_CANTIDAD.", ".$this->DCO_VALOR_UNIDAD.", '".$this->DCO_MARCA."', '".$this->DCO_IVA."', ".$this->DCO_TIEMPO_ENTREGA.", '".$this->DCO_OBSERVACIONES."', ".$this->DCO_CANTIDAD_GARANTIA.", ".$this->DCO_UNIDAD_GARANTIA.", ".$this->TMP_NUEVO.", ".$this->PCS_SECUENCIA.")";
  		$_SESSION[APL]->bd->ejecutar($sql);
  	}
	
	
	
	
	function cargar($id){
		$sql="SELECT * FROM calsuper_calsuperior.nodo
		WHERE
		id=".$id;
		$Rs =  $_SESSION[APL]->bd->getRs($sql);
		$this->id				= $Rs->fields[0];
		$this->id_archivo		= $Rs->fields[1];
		$this->posicion			= $Rs->fields[2];
		$this->item				= $Rs->fields[3];
		$this->descripcion		= $Rs->fields[4];
		$this->fecha_creacion	= $Rs->fields[5];
		$this->id_padre			= $Rs->fields[6];
		$this->nivel			= $Rs->fields[7];
		$this->tipo_acceso		= $Rs->fields[8];
		
		$sql = "select id from calsuper_calsuperior.adjunto_nodo where id_nodo = ".$id;		
		$Rs =  $_SESSION[APL]->bd->getRs($sql);
		$i = 0;
		While(!$Rs->EOF){
			$this->adjuntos[$i] =& new cadjunto();
			$this->adjuntos[$i]->cargar($Rs->fields[0]);
			$i++;
			$Rs->MoveNext();
		}											
		$Rs->Close();
		
		$sql = "select id from calsuper_calsuperior.nodo where id_padre = ".$id." order by posicion";		
		$Rs =  $_SESSION[APL]->bd->getRs($sql);
		$i = 0;
		While(!$Rs->EOF){
			$this->hijos[$i] =& new cnodo();
			$this->hijos[$i]->cargar($Rs->fields[0]);
			$i++;
			$Rs->MoveNext();
		}											
		$Rs->Close();
	}
}
class cadjunto
{
	var	$id  				= NULL;
	var	$id_nodo 			= NULL;
	var	$adjunto 			= NULL;
	var	$blob				= NULL;
	var $id_sociedad		= NULL;
  	var $id_naturaleza		= NULL;
	var $fecha_creacion		= NULL;
	var $id_ubicacion		= NULL;
	var $observaciones		= NULL;
	var $original			= NULL;
	var $id_usuario			= NULL;
	var $id_archivo			= NULL;
	
	function cadjunto(){		
    }
	
	function cargar($id)
	{
		$sql="SELECT * FROM calsuper_calsuperior.adjunto_nodo
		WHERE
		id=".$id;
		$Rs =  $_SESSION[APL]->bd->getRs($sql);
		$this->id					= $Rs->fields[0];
		$this->id_nodo				= $Rs->fields[1];
		$this->adjunto				= $Rs->fields[2];
  		$this->id_sociedad				= $Rs->fields[3];
  		$this->id_naturaleza		= $Rs->fields[4];
  		$this->fecha_creacion		= $Rs->fields[5];
  		$this->id_ubicacion			= $Rs->fields[6];
  		$this->observaciones		= $Rs->fields[7];
  		$this->original				= $Rs->fields[8];
  		$this->id_usuario			= $Rs->fields[9];
  		$this->id_archivo			= $Rs->fields[10];
		
		
		
	}
}



?>