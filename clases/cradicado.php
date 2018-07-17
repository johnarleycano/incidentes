<?php
//Cambio Septiembre 2011
///////////////////////////////////////////////////////
//
//		Clase Usuario
//		Desarrollada por: Diego Fernando Vargas
//		Empresa: Algoritmo Software S.A
//		Fecha: 14 Junio 2010
//		Comentarios:
//			Clase para el manejo de los usuarios validados en el sistema.
//		Cambios:
//			Autor:
//			Fecha:
//			Descripcion:
//
///////////////////////////////////////////////////////


class cradicado
{

	var $id					= NULL;
  	var $consecutivo		= NULL;
  	var $fecha_creacion		= NULL;
	var $fecha_recepcion	= NULL;
	var $remitente			= NULL;
	var $asunto				= NULL;
	var $observaciones		= NULL;
	var $id_estado			= NULL;
	var $nombre_documento	= NULL;
	var $documento			= NULL;
	var $id_usuario_radicador	= NULL;
	var $id_departamento_radicador	= NULL;
	var $recibido					= NULL;
	var $id_ubicacion			= NULL;
	var $respuesta			= NULL;

	
	function cradicado()
	{		
    }
		
	function cargar($id)
	{
		$sql="SELECT * FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado 
		WHERE id=".$id;
		$Rs = $_SESSION[APL]->bd->getRs($sql);	 
		$this->id					= $Rs->fields[0];
		$this->consecutivo			= $Rs->fields[1];
		$this->fecha_creacion		= $Rs->fields[2];
		$this->fecha_recepcion		= $Rs->fields[3];
		$this->remitente			= $Rs->fields[4];
		$this->asunto				= $Rs->fields[5];
		$this->observaciones		= $Rs->fields[6];
		$this->id_estado			= $Rs->fields[7];
		$this->documento			= $Rs->fields[8];
		$this->nombre_documento		= $Rs->fields[9];
		$this->id_usuario_radicador	= $Rs->fields[10];
		$this->id_departamento_radicador	= $Rs->fields[11];
		$this->recibido					= $Rs->fields[12];
		$this->id_ubicacion			= $Rs->fields[13];
		$this->respuesta			= $Rs->fields[14];
		

	}
	
	function pendientes()
	{
		$dumy = $_SESSION[APL]->get_plantilla("entrada_pendientes.html");
		
		$size_td=$_SESSION[APL]->tam_normal;
		$font_color_td=$_SESSION[APL]->color_normal;
		$size_h1=$_SESSION[APL]->tam_titulo;
		$font_color_h1=$_SESSION[APL]->color_titulo;
		$fuente=$_SESSION[APL]->fuente;
		$bgcolor_normal=$_SESSION[APL]->bgcolor_normal;
		$bgcolor_titulo=$_SESSION[APL]->bgcolor_titulo;
		$color_campos=$_SESSION[APL]->color_campos;
		$css_td='style="font-family:'.$fuente.';font-size: '.$size_td.'; color:'.$font_color_td.'" bgcolor="#'.$bgcolor_normal.'"';
		$css_color='style="font-family:'.$fuente.';font-size: '.$size_td.'; color:'.$font_color_td.'"';
		$css_font='style="font-family:'.$fuente.';font-size: '.$size_td.'; color:'.$color_campos.'"';
		$css_th='style="font-family:'.$fuente.';font-size: '.$size_td.'; color:'.$font_color_h1.'" bgcolor="#'.$bgcolor_titulo.'"';
		$css_h1='style="font-family:'.$fuente.';font-size: '.$size_h1.'; color:'.$font_color_h1.'" bgcolor="#'.$bgcolor_titulo.'"';
				
		$dumy = $_SESSION[APL]->token($dumy,"css_td",$css_td);
		$dumy = $_SESSION[APL]->token($dumy,"css_font",$css_font);
		$dumy = $_SESSION[APL]->token($dumy,"css_th",$css_th);
		$dumy = $_SESSION[APL]->token($dumy,"css_h1",$css_h1);
		
		$manual="<input type='button' value='Descargar Manual' onClick=\"window.open('".$_SESSION[APL]->manual."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=100,height=100')\" ".$css_font.">";
		$manual.="<input type='button' value='Manuales en Video' onClick=\"window.open('manuales/manuales.html','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no')\" ".$css_font.">";
		$dumy = $_SESSION[APL]->token($dumy,"manual",$manual);
		
		
		
		$lista = "";
		$sql="SELECT 
		r.id,
		consecutivo,
		e.descripcion,
		fecha_recepcion,
		remitente,
		asunto,
		recibido,
		r.fecha_creacion as fecha_creacion,
		nombre_documento,
		nombre_documento2,
		observaciones_gerencia,
		id_estado,
		ura.nombres nombres,
		ura.apellidos apellidos,
		renumerado_departamento,
		r.id_usuario_radicador
		FROM 
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado as r left outer join
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as ura on (r.id_usuario_radicador=ura.id),
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_estado as e
		WHERE
		
		r.id_estado=e.id and
		r.id_estado not in (8,9) and ";
		// gerencia, asistente gerencia o administracion gerencia podran ver como propios los radicados generads por un radicado basico
		if($_SESSION[APL]->usuario->id_departamento==1 || $_SESSION[APL]->usuario->id_departamento==2 || $_SESSION[APL]->usuario->id_departamento==11)
			$sql.=" (id_usuario_radicador=".$_SESSION[APL]->usuario->id." or id_usuario_radicador in (select id from ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios where id_perfil=3) )";
		else
			$sql.=" id_usuario_radicador=".$_SESSION[APL]->usuario->id;
		$sql.=" order by periodo ASC,recibido DESC,posicion ASC";
		$Rs =  $_SESSION[APL]->bd->getRs($sql);
		
		if($Rs->NumRows()==0)
		{
			$lista.="<tr><th colspan='11' ".$css_td." height='100px'>No Existen Radicados Generados</th></tr>";
		}
		else
		{
			while(!$Rs->EOF)
			{
				$lista.="<tr>
				<td ".$css_td." width='140px' align='right'>";
				if($Rs->fields[11]==11)
					$lista.="<img src='img/page_error.png' alt='Radicado Pendiente de Recepcion en Gerencia' title='Radicado Pendiente de Recepcion en Gerencia' style='cursor:help'>";
				
				if($Rs->fields[14]=='SI')
					$lista.="<img src='img/alert.png' alt='Radicado con Numeracion Indipendiente por Departamento' title='Radicado con Numeracion Indipendiente por Departamento' style='cursor:help'>";
		  		$lista.=$Rs->fields[1]."</td>
				<td ".$css_td.">
		  		".$Rs->fields[2]."
				</td>
				<td ".$css_td." align='center'>";
				if($Rs->fields[6]=='SI')
		  			$lista.=$_SESSION[APL]->quitar_hora($Rs->fields[3]);
				else
					$lista.=$_SESSION[APL]->quitar_hora($Rs->fields[7]);
					
				if($Rs->fields[15]!=$_SESSION[APL]->usuario->id)	
					$usuario="otro";
				else
					$usuario="propio";
					
				$lista.="&nbsp;<img src='img/".$usuario.".png' alt='Generado por: ".$Rs->fields[12]." ".$Rs->fields[13]."' title='Generado por: ".$Rs->fields[12]." ".$Rs->fields[13]."' style='cursor:help'></td>
				<td ".$css_td.">";
				if($Rs->fields[6]=='SI')
					$lista.=$Rs->fields[4];
				else
					$lista.="No Aplica";
				$lista.="</td>
				<td ".$css_td.">
		  		".$Rs->fields[5]."
				</td>
				<td ".$css_td." align='center'>";
				if($Rs->fields[6]=='SI')
				$lista.="Recibido";
				else
				$lista.="Generado";
				$lista.="
				</td>
				<td ".$css_td." align='center'>";
				if($Rs->fields('observaciones_gerencia')!='')
					$lista.="<img src='img/user_comment.png' alt='".$Rs->fields('observaciones_gerencia')."' title='".$Rs->fields('observaciones_gerencia')."' border='0' style='cursor:help'>";
				else
					$lista.="SIN";
				$lista.="</td>
				<td ".$css_td." colspan='2'>";
				
				//ADJUNTOS
				
				$sql="SELECT posicion,nombre_documento
					FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_documento_radicado
					WHERE id_radicado=".$Rs->fields[0]." AND tipo='RA'
					order by posicion ASC";
				$adj = $_SESSION[APL]->bd->getRs($sql);
				if($adj->NumRows()==0)
					$lista.= "&nbsp;";
				else
				{
					$lista.= "<table><tr>";
					while(!$adj->EOF)
					{
						$lista.= "<td ".$css_td.">";					
						$lista.= "<a href='descargar_adjunto.php?id_adjunto=".$Rs->fields[0]."&pos=".$adj->fields[0]."&tipo=RA' border='0' target='_blank'><img src='img/attach.png' alt='".$adj->fields[0]."' title='".$adj->fields[0]."' border='0'></a>";
						$lista.= "</td>";
						$adj->MoveNext();
					}
					$lista.= "</tr></table>";
				}
				
				
				
			
				$lista.="</td><td ".$css_td.">";	
				$lista.="<img src='img/find.png' style='cursor:pointer' onClick=\"window.open('nuevo_radicado.php?id=".$Rs->fields[0]."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=400')\" alt='Abrir' title='Abrir'>";
				$lista.="</td><td ".$css_td.">";	
				$lista.="<img src='img/printer.png' style='cursor:pointer' onClick=\"window.open('sticker.php?id=".$Rs->fields[0]."','_blank','menubar=no,location=no,resizable=yes,scrollbars=auto,status=no,width=310,height=250')\" alt='Imprimir Sticker' title='Imprimir Sticker'>";
				$lista.="</td>
				</tr>";
				$Rs->MoveNext();
			}
		}
		
		$dumy = $_SESSION[APL]->token($dumy,"lista_generadas",$lista);
		if($_SESSION[APL]->usuario->id_perfil == 0 || $_SESSION[APL]->usuario->id_perfil == 1 || $_SESSION[APL]->usuario->id_perfil == 3 || $_SESSION[APL]->usuario->id_perfil == 4)
		{
			$boton="<input type='button' value='Nuevo Radicado Recibido' onClick=\"window.open('nuevo_radicado.php?recibido=SI','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=400')\" ".$css_font.">";
			$boton.="<input type='button' value='Nuevo Radicado Generado' onClick=\"window.open('nuevo_radicado.php?recibido=NO','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=400')\" ".$css_font.">";
			
		}
			
		else
			$boton="";
		$dumy = $_SESSION[APL]->token($dumy,"generar_radicado",$boton);
	
		
		$lista = "";
		$sql="SELECT 
		r.id as id,
		consecutivo,
		e.descripcion,
		fecha_recepcion,
		remitente,
		asunto,
		recibido,
		nombre_documento,
		nombre_documento2,
		r.fecha_creacion,
		observaciones_gerencia,
		id_estado as estado,
		ura.nombres nombres,
		ura.apellidos apellidos,
		fecha_respuesta,
		renumerado_departamento,
		r.id_usuario_radicador
		FROM 
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado as r left outer join
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as ura on (r.id_usuario_radicador=ura.id),
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_estado as e
		WHERE
		r.id_estado=e.id and
		id_estado in (1,2,7,3,12,13) and
		r.id_usuario_recibe =".$_SESSION[APL]->usuario->id." 
		order by periodo ASC,recibido DESC,posicion ASC";
		//order by ISNULL(fecha_respuesta), fecha_respuesta ASC, periodo ASC,recibido DESC,posicion ASC";
		$Rs =  $_SESSION[APL]->bd->getRs($sql);
		
		if($Rs->NumRows()==0)
		{
			$lista.="<tr><th colspan='11' ".$css_td." height='115px'>No Existen Radicados Asignados</th></tr>";
		}
		else
		{
			while(!$Rs->EOF)
			{
				if($Rs->fields('fecha_respuesta')!='')
				{
					$fechat=split(' ',$Rs->fields('fecha_respuesta'));
					$fechap=split('-',$fechat[0]);
					$tiempor=split(":",$fechat[1]);
					$horar=$tiempor[0];
					$minur=$tiempor[1];
					
					
					$tiempo=mktime($horar,$minur,0,$fechap[1],$fechap[2],$fechap[0]);
					$seg=$tiempo-time();
					$dias=ceil((($seg/60)/60)/24);
					
					$texto_resp="<br><b>".$dias." dia(s)</b>";
					if($dias>$_SESSION[APL]->limite_dias)//Mayor a 15 dias
					{
						$color=$css_td;
						$texto_resp="";	
					}
					elseif($dias>($_SESSION[APL]->limite_dias-5))//Entre 11 y 15
						$color=$css_color.' bgcolor="#'.$_SESSION[APL]->verde.'"';//verde
					elseif($dias>($_SESSION[APL]->limite_dias-10))//Entre 6 y 10 dias
						$color=$css_color.' bgcolor="#'.$_SESSION[APL]->amarillo.'"';
					elseif($dias>($_SESSION[APL]->limite_dias-15))//Entre 0 y 5 dias
						$color=$css_color.' bgcolor="#'.$_SESSION[APL]->naranja.'"';
					else
						$color=$css_color.' bgcolor="#'.$_SESSION[APL]->rojo.'"';
				}
				else
				{
					$color=$css_td;
					$texto_resp="";	
				}
		
			
			
			
			
				$lista.="<tr>
				<td ".$color." width='140px' align='right'>";
				if($Rs->fields[11]==11)
					$lista.="<img src='img/page_error.png' alt='Radicado Pendiente de Recepcion en Gerencia' title='Radicado Pendiente de Recepcion en Gerencia' style='cursor:help'>";
				if($Rs->fields[15]=='SI')
					$lista.="<img src='img/alert.png' alt='Radicado con Numeracion Indipendiente por Departamento' title='Radicado con Numeracion Indipendiente por Departamento' style='cursor:help'>";
				
				$lista.=$Rs->fields[1]."
				</td>
				<td ".$color.">
		  		".$Rs->fields[2]."
				</td>
				<td ".$color." align='center'>";
				if($Rs->fields[6]=='SI')
					$lista.=$_SESSION[APL]->quitar_hora($Rs->fields[3]);
				else
					$lista.=$_SESSION[APL]->quitar_hora($Rs->fields[9]);
					
				if($Rs->fields[15]!=$_SESSION[APL]->usuario->id)	
					$usuario="otro";
				else
					$usuario="propio";
				$lista.="&nbsp;<img src='img/".$usuario.".png' alt='Generado por: ".$Rs->fields[12]." ".$Rs->fields[13]."' title='Generado por: ".$Rs->fields[12]." ".$Rs->fields[13]."' style='cursor:help'></td>
				<td ".$color.">";
				if($Rs->fields[6]=='SI')
					$lista.=$Rs->fields[4];
				else
					$lista.="No Aplica";
		  		$lista.="</td>
				<td ".$color.">
		  		".$Rs->fields[5]."
				</td>
				<td ".$color." align='center'>";
				if($Rs->fields[6]=='SI')
				$lista.="Recibido";
				else
				$lista.="Generado";
				$lista.="
				</td>
				<td ".$color." align='center'>";
				if($Rs->fields('observaciones_gerencia')!='')
					$lista.="<img src='img/user_comment.png' alt='".$Rs->fields('observaciones_gerencia')."' title='".$Rs->fields('observaciones_gerencia')."' border='0' style='cursor:help'>";
				else
					$lista.="SIN";
				$lista.="
				</td>
				<td ".$color." colspan='2'>";
				
				//ADJUNTOS
				
				$sql="SELECT posicion,nombre_documento
					FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_documento_radicado
					WHERE id_radicado=".$Rs->fields[0]." AND tipo='RA'
					order by posicion ASC";
				$adj = $_SESSION[APL]->bd->getRs($sql);
				if($adj->NumRows()==0)
					$lista.= "&nbsp;";
				else
				{
					$lista.= "<table><tr>";
					while(!$adj->EOF)
					{
						$lista.= "<td ".$color.">";					
						$lista.= "<a href='descargar_adjunto.php?id_adjunto=".$Rs->fields[0]."&pos=".$adj->fields[0]."&tipo=RA' border='0' target='_blank'><img src='img/attach.png' alt='".$adj->fields[0]."' title='".$adj->fields[0]."' border='0'></a>";
						$lista.= "</td>";
						$adj->MoveNext();
					}
					$lista.= "</tr></table>";
				}
				
				$lista.="</td><td ".$color." align='center'>";		
				if($Rs->fields('estado')==7 || $Rs->fields('estado')==2)
					$lista.="<img src='img/find.png' style='cursor:pointer' onClick=\"window.open('responder_radicado.php?id=".$Rs->fields[0]."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=470')\" alt='Visualizar' title='Visualizar'>".$texto_resp;
				else
				{
					if($Rs->fields('fecha_respuesta')!='')
					{
						$resp="Responder antes de ".$Rs->fields('fecha_respuesta');
					}
					else
						$resp="Responder";
					
					$lista.="<img src='img/edit.png' style='cursor:pointer' onClick=\"window.open('responder_radicado.php?id=".$Rs->fields[0]."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=470')\" alt='".$resp."' title='".$resp."'>".$texto_resp;
					
				}
					
				$lista.="
				</td>
				</tr>";
				$Rs->MoveNext();
			}
		}
		
		$dumy = $_SESSION[APL]->token($dumy,"lista_asignadas",$lista);
		
		$lista = "";
		$sql="SELECT 
		t.id,
		consecutivo,
		e.descripcion,
		fecha_recepcion,
		t.fecha_asignacion,
		u.nombres,
		u.apellidos,
		remitente,
		asunto,
		recibido,
		r.fecha_creacion,
		nombre_documento,
		nombre_documento2,
		r.id as id_r,
		observaciones_gerencia,
		r.fecha_respuesta as fecha_respuesta,
		renumerado_departamento,
		r.id_estado,
		t.fecha_requerimiento fecha_requerimiento,
		t.id_tecnico_padre id_tecnico_padre
		FROM 
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado as r,
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado_tecnico as t,
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_estado as e,
		".$_SESSION[APL]->bd->nombre_bd[0].".dvm_usuarios as u
		WHERE
		r.id_usuario_radicador=u.id and
		r.id_estado=e.id and
		t.id_radicado=r.id and
		r.id_estado in (2,12,13) and
		(
		(t.id_tecnico_padre is not null and (t.respuesta is null and t.aprobada=0)) or
		(t.id_tecnico_padre is NULL and 
		
			(
				(
				select count(*) from 
				".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado_tecnico as t2
				where
				t2.id_tecnico_padre=t.id and t2.id_radicado=t.id_radicado and t2.aprobada=1
				)
				!=
				(
				select count(*) from 
				".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado_tecnico as t2
				where
				t2.id_tecnico_padre=t.id and t2.id_radicado=t.id_radicado
				) 
				or
				(
					(
						select count(*) from 
						".$_SESSION[APL]->bd->nombre_bd[0].".dvm_radicado_tecnico as t2
						where
						t2.id_tecnico_padre=t.id and t2.id_radicado=t.id_radicado
					)=0
					and
					t.respuesta is null
				)
			)
			
		)
		)
		and
		t.id_usuario=".$_SESSION[APL]->usuario->id." 
		order by periodo ASC,recibido DESC,posicion ASC";
//order by ISNULL(r.fecha_respuesta), r.fecha_respuesta ASC,periodo ASC,recibido DESC,posicion ASC";
		
		$Rs =  $_SESSION[APL]->bd->getRs($sql);
		
		if($Rs->NumRows()==0)
		{
			$lista.="<tr><th colspan='12' ".$css_td." height='85px'>No Existen Radicados Pendientes de Analisis Tecnico</th></tr>";
		}
		else
		{
			while(!$Rs->EOF)
			{
				if($Rs->fields('fecha_requerimiento')!='')
				{
					$fechat=split(' ',$Rs->fields('fecha_requerimiento'));
					$fechap=split('-',$fechat[0]);
					$tiempor=split(":",$fechat[1]);
					$horar=$tiempor[0];
					$minur=$tiempor[1];
					
					
					$tiempo=mktime($horar,$minur,0,$fechap[1],$fechap[2],$fechap[0]);
					$seg=$tiempo-time();
					$dias=ceil((($seg/60)/60)/24);
					
					$texto_resp="<br><b>".$dias." dias </b>";
					if($dias>$_SESSION[APL]->limite_dias)//Mayor a 15 dias
					{
						$color=$css_td;
						$texto_resp="";	
					}
					else
					if($dias>($_SESSION[APL]->limite_dias-5))//Entre 11 y 15
						$color=$css_color.' bgcolor="#'.$_SESSION[APL]->verde.'"';//verde
					elseif($dias>($_SESSION[APL]->limite_dias-10))//Entre 6 y 10 dias
						$color=$css_color.' bgcolor="#'.$_SESSION[APL]->amarillo.'"';
					elseif($dias>($_SESSION[APL]->limite_dias-15))//Entre 0 y 5 dias
						$color=$css_color.' bgcolor="#'.$_SESSION[APL]->naranja.'"';
					else
						$color=$css_color.' bgcolor="#'.$_SESSION[APL]->rojo.'"';
				}
				else
				{
					$color=$css_td;
					$texto_resp="";	
				}
			
			
				$lista.="<tr>
				<td ".$color." width='140px' align='right'>";
				if($Rs->fields[17]==11)
					$lista.="<img src='img/page_error.png' alt='Radicado Pendiente de Recepcion en Gerencia' title='Radicado Pendiente de Recepcion en Gerencia' style='cursor:help'>";
				if($Rs->fields[16]=='SI')
					$lista.="<img src='img/alert.png' alt='Radicado con Numeracion Indipendiente por Departamento' title='Radicado con Numeracion Indipendiente por Departamento' style='cursor:help'>";
				$lista.=$Rs->fields[1]."
				</td>
				<td ".$color.">
		  		".$Rs->fields[2]."
				</td>
				<td ".$color.">".$_SESSION[APL]->quitar_hora($Rs->fields[3])."</td>
				<td ".$color.">".$_SESSION[APL]->quitar_hora($Rs->fields[4])."</td>
				<td ".$color.">
		  		".$Rs->fields[5]." ".$Rs->fields[6]."
				</td>
				<td ".$color.">";
				if($Rs->fields[9]=='SI')
					$lista.=$Rs->fields[7];
				else
					$lista.="No Aplica";
				
		  		$lista.="</td>
				<td ".$color.">
		  		".$Rs->fields[8]."
				</td>
				<td ".$color." align='center'>";
				if($Rs->fields[9]=='SI')
				$lista.="Recibido";
				else
				$lista.="Generado";
				$lista.="
				</td>
				<td ".$color." align='center'>";
				if($Rs->fields('observaciones_gerencia')!='')
					$lista.="<img src='img/user_comment.png' alt='".$Rs->fields('observaciones_gerencia')."' title='".$Rs->fields('observaciones_gerencia')."' border='0' style='cursor:help'>";
				else
					$lista.="SIN";
				$lista.="
				</td>
				<td ".$color." colspan='2'>";
				//ADJUNTOS
				
				$sql="SELECT posicion,nombre_documento
					FROM ".$_SESSION[APL]->bd->nombre_bd[0].".dvm_documento_radicado
					WHERE id_radicado=".$Rs->fields('id_r')." AND tipo='RA'
					order by posicion ASC";
				$adj = $_SESSION[APL]->bd->getRs($sql);
				if($adj->NumRows()==0)
					$lista.= "&nbsp;";
				else
				{
					$lista.= "<table><tr>";
					while(!$adj->EOF)
					{
						$lista.= "<td ".$color.">";					
						$lista.= "<a href='descargar_adjunto.php?id_adjunto=".$Rs->fields('id_r')."&pos=".$adj->fields[0]."&tipo=RA' border='0' target='_blank'><img src='img/attach.png' alt='".$adj->fields[0]."' title='".$adj->fields[0]."' border='0'></a>";
						$lista.= "</td>";
						$adj->MoveNext();
					}
					$lista.= "</tr></table>";
				}
				
				
				
				$lista.="</td><td ".$color." align='center'>";	
				
				if($Rs->fields('id_tecnico_padre')=='')
				{
					$txt_img="<img src='img/bullet_black.png'>";
					$txt_msg="Responder Analisis Tecnico de Primer Nivel";
				}
				else
				{
					$txt_img="<img src='img/bullet_black.png'><img src='img/bullet_black.png'>";
					$txt_msg="Responder Analisis Tecnico de Segundo Nivel";
				}
				
				
				
				$lista.="<img src='img/edit.png' style='cursor:pointer' onClick=\"window.open('responder_tecnico.php?id=".$Rs->fields[0]."','_blank','menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=800,height=400')\" alt='Responder' title='".$txt_msg."'> ".$txt_img."".$texto_resp."
				</td>
				</tr>";
				$Rs->MoveNext();
			}
		}
		
		$dumy = $_SESSION[APL]->token($dumy,"lista_tecnico",$lista);
		
		
		return $dumy;
	
	

		
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
		$sql="SELECT min(id),max(id) FROM nodo";
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
		$sql="SELECT min(id),max(id) FROM nodo";
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



?>