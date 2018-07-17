function iniciarForma()
{
	IniciarGrid();
	vis_PonVentana('venEdiUsu','Editando Usuario y Perfiles',400,800);
	vis_PonVentana('venFirImg','Firma',200,300);
	vis_PonBoton(".clsBtn");
}

function IniciarGrid()
{
	var pAlto = $(document).height()-150;
	var pAncho = $(document).width()-30;
	var vIdGri = 'griUsu';
	var vUrl   = 'usuario.grid.php';
	var vTitulo = 'Listado Usuarios y Perfiles';
	var vColNam = ['Uid','Login','Fecha<br/>Creacion','Cedula','Nombre(s)','Apellido(s)','Correo','Celular','Estado','Perfil','Firma','Enviar Correo','Acciones'];
	var vColMod = [	
					{name:'id',				index:'id',	hidden:true },
					{name:'login',			index:'login', width:90 }, 
					{name:'fecha_creacion',	index:'fecha_creacion', width:80 },
					{name:'cedula',			index:'cedula', width:80},
					{name:'nombres',		index:'nombres', width:150},
					{name:'apellidos',		index:'apellidos', width:150},
					{name:'correo',			index:'correo', width:200},
					{name:'celular',		index:'celular', width:100 },
					{name:'estado',			index:'estado', width:80, edittype:"select", editoptions:{value:':--Todos--;A:Activo;I:Inactivo'}, stype:'select', search:true, sortable:false},
					{name:'nomPer',			index:'nomPer', width:160, edittype:"select", editoptions:{value:':--Todos--;0:Administrador;1:Generador Basico Incidentes;2:Funcionario SOS;3:Admninistrador Vial'}, stype:'select', search:true, sortable:false},
					{name:'firma',			index:'fima', width:60, align:'center', sortable:false, search:false },
					{name:'enviar_correo',	index:'enviar_correo', width:80, edittype:"select", editoptions:{value:':--Todos--;SI:Si;NO:No'}, stype:'select', search:true, sortable:false},
					{name:'btns',			index:'btns', width:80, align:'center', sortable:false, search:false }
				 ];

	var vFunGridCom = function(){  };
	var posData = { };
	var vBarrTop = false;
	
	vis_PonGrilla(vIdGri,'pagUsu',vUrl,vTitulo,vColNam,vColMod,100,pAlto,pAncho,'login',vFunGridCom,vBarrTop,posData);
}

function Editar(pId)
{
	$("#firma").val("");
	
	if( pId=="-1" || pId==-1 )
	{
		document.getElementById("frmUsu").reset();
		$("#venEdiUsu").dialog("open");
		return;
	}
	
	$.ajax(
	{
		data : "comando=VER&id="+pId,
		url  :  "usuario.srv.php",
		type : 'post',
		cache: false,
		dataType: "json",
		success:  function (data)
		{
			$("#id").val(data.id);
			$("#login").val(data.login);
			$("#cedula").val(data.cedula);
			$("#nombre").val(data.nombre);
			$("#apellido").val(data.apellido);
			$("#correo").val(data.correo);
			$("#celular").val(data.celular);
			$("#perfil").val(data.perfil);
			$("#estado").val(data.estado);
			$("#enviar").val(data.enviar);
			$("#clave").val(data.clave);
			$("#fechacre").val(data.fechacre);
			$('#imgFirma').attr('src',data.rutaFirma);
			
			$("#venEdiUsu").dialog("open");
		}
	});
}

function Grabar()
{
	$.ajax(
	{
		data : "comando=GRA&"+$("#frmUsu").serialize(),
		url  :  "usuario.srv.php",
		type : 'post',
		cache: false,
		dataType: "json",
		success:  function (data)
		{
			alert(data.msg);
			if( data.res=='OK' )
			{
				if( $("#firma").val()!="" )
					vis_SubirArchivo("firma","USU","firma_"+data.id,"griUsu",data.id);
				else
					$("#griUsu").trigger("reloadGrid");
				
				$("#venEdiUsu").dialog("close");
			}
		}
	});
}

function Cerrar()
{
	
	$("#venEdiUsu").dialog("close");
}

function CerrarIframe()
{
	window.parent.cerrarIframe();
}

function abrirFirma(pRutFir)
{
	$('#imgFir').attr('src','');
	var fecha = new Date();

	$('#imgFir').attr('src',pRutFir+"?"+fecha.getTime());
	$("#venFirImg").dialog("open");
}