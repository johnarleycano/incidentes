function iniciarForma()
{
	IniciarGrid();
	var pAlto = $(document).height()-150;
	var pAncho = $(document).width()-350;
	
	vis_PonVentana('venEdiRegSos','BITACORAS | DEVIMED S.A.',pAlto,pAncho);
	vis_PonVentana("venVerSOS","Ver Registro SOS",pAlto,pAncho);
	vis_PonVentana("venVerSOSVehInv","VEHICULOS INVOLUCRADOS, AFECTADOS/ LESIONADOS Y/O MUERTOS",pAlto,pAncho);
	vis_PonBoton(".clsBtn");
	
	$('#ifrEdiSOS').css('height',pAlto-80);
	$('#ifrEdiSOS').css('width',pAncho-10);
	
	$('#ifrVerSOS').css('height',pAlto-80);
	$('#ifrVerSOS').css('width',pAncho-50);
	
	$('#ifrVerSOSVehInv').css('height',pAlto-80);
	$('#ifrVerSOSVehInv').css('width',pAncho-50);
}

function IniciarGrid()
{
	var pAlto = $(document).height()-250;
	var vIdGri = 'griBitacoras';
	var vUrl   = 'bitacoras.grid.php';
	var vTitulo = 'Listado Registro de Bit&aacute;coras';
	var vColNam = ['ID','Fecha','Hora','Heridos','Reportado por','Motivo','Ubicacion',  'Anotaciones', 'Usuario'];
	var vColMod = [	
					{name:'id',			index:'id',	width:50 },
					{name:'fecha',		index:'fecha', width:80 }, 
					{name:'hora',		index:'hora', width:70 },
					{name:'heridos',	index:'heridos', width:60},
					{name:'info_por',	index:'info_por', width:160},
					{name:'motivo',	    index:'motivo', width:210},
					{name:'ubicacion',	index:'ubicacion', width:240},
					{name:'anotaciones',	index:'anotaciones', width:245},
					{name:'usuario',	index:'usuario', width:130},
					
				 ];

	var vFunGridCom = function(){  };
	var vBarrTop = false;
	var posData = { finalizado: function() { return $('#finalizado').val(); } };

	vis_PonGrilla(vIdGri,'pagRegVia',vUrl,vTitulo,vColNam,vColMod,100,pAlto,1238,'',vFunGridCom,vBarrTop,posData);
}

function Editar()
{
	$('#ifrEdiSOS').attr('src', "../bitacora_modal.php")
	$("#venEdiRegSos").dialog({ position:'center' });
	$("#venEdiRegSos").dialog("open");
}

function filtrar()
{
	$("#griRegSos").trigger("reloadGrid");
}

function redirect()
{
	
	window.location.href='../registro_bitacoras.php';
}

function quitarEspere()
{
	$("#espere").css("display","none");
}