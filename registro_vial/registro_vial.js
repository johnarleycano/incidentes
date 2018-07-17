function iniciarForma()
{
	IniciarGrid();
	var pAlto = $(document).height()-30;
	var pAncho = $(document).width()-50;
	
	vis_PonVentana('venEdiRegVia','Editando Registro ADM Vial',pAlto,pAncho);
	vis_PonVentana("venVerSOS","Ver Registro SOS",pAlto,pAncho);
	vis_PonVentana("venVerSOSVehInv","VEHICULOS INVOLUCRADOS, AFECTADOS/ LESIONADOS Y/O MUERTOS",pAlto,pAncho);
	vis_PonBoton(".clsBtn");
	
	$('#ifrEdiVia').css('height',pAlto-80);
	$('#ifrEdiVia').css('width',pAncho-50);
	
	$('#ifrVerSOS').css('height',pAlto-80);
	$('#ifrVerSOS').css('width',pAncho-50);
	
	$('#ifrVerSOSVehInv').css('height',pAlto-80);
	$('#ifrVerSOSVehInv').css('width',pAncho-50);
}

function IniciarGrid()
{
	var pAlto = $(document).height()-250;
	var vIdGri = 'griRegVia';
	var vUrl   = 'registro_vial.grid.php';
	var vTitulo = 'Listado Registros ADM Vial';
	var vColNam = ['Uid','Codigo','Fecha','Via','Referencia','Tipo Atencion','Usuario','Estado','Ver','Descargar'];
	var vColMod = [	
					{name:'id',			index:'id',	hidden:true },
					{name:'codigo',		index:'codigo', width:80 }, 
					{name:'fecha',		index:'fecha', width:80 },
					{name:'via',		index:'via', width:220},
					{name:'referencia',	index:'referencia', width:220},
					{name:'tipoaten',	index:'tipoaten', width:130},
					{name:'usuario',	index:'usuario', width:180},
					{name:'estado',		index:'estado', width:100, align:'center', search:false},
					{name:'ver',		index:'ver', width:80, align:'center', search:false},
					{name:'descargar',	index:'descargar', width:80, align:'center', search:false}
				 ];

	var vFunGridCom = function(){  };
	var vBarrTop = false;
	var posData = { finalizado: function() { return $('#finalizado').val(); } };

	vis_PonGrilla(vIdGri,'pagRegVia',vUrl,vTitulo,vColNam,vColMod,100,pAlto,1238,'',vFunGridCom,vBarrTop,posData);
}

function Editar(pId)
{
	$('#ifrEdiVia').attr('src', "../registro_adm_vial_edi.php?id_buscar="+pId)
	$("#venEdiRegVia").dialog("open");
}

function ver_SOS(idSOS)
{
	$('#ifrVerSOS').attr('src', "../registro_sos_edi.php?id_buscar="+idSOS+"&esEdi=NO");
	$("#venVerSOS").dialog({ position:'center top' });
	$("#venVerSOS").dialog("open");
}

function ver_SOSVehInv(idSOS)
{
	$('#ifrVerSOSVehInv').attr('src', "../vehiculo_incidente.php?id_buscar="+idSOS)
	$("#venVerSOSVehInv").dialog({ position:'center top' });
	$("#venVerSOSVehInv").dialog("open");
}

function regargarSOS(pId)
{
	$('#ifrVerSOS').attr('src', "../registro_sos_edi.php?id_buscar="+pId+"&esEdi=SI");
}

function cerrarSOSVehInv()
{
	$("#venVerSOSVehInv").dialog("close");
}

function filtrar()
{
	$("#griRegVia").trigger("reloadGrid");
}