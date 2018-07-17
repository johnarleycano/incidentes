function iniciarForma()
{
	IniciarGrid();
	var pAlto = $(document).height()-30;
	var pAncho = $(document).width()-50;
	
	vis_PonVentana('venEdiRegSos','Editando Registro SOS',pAlto,pAncho);
	vis_PonVentana("venVerSOSVehInv","VEHICULOS INVOLUCRADOS, AFECTADOS/ LESIONADOS Y/O MUERTOS",pAlto,pAncho);
	vis_PonBoton(".clsBtn");
	
	$('#ifrEdiSOS').css('height',pAlto-80);
	$('#ifrEdiSOS').css('width',pAncho-50);
	
	$('#ifrVerSOSVehInv').css('height',pAlto-80);
	$('#ifrVerSOSVehInv').css('width',pAncho-50);
}

function IniciarGrid()
{
	var pAlto = $(document).height()-250;
	var vIdGri = 'griRegSos';
	var vUrl   = 'registro_sos.grid.php';
	var vTitulo = 'Listado Registros SOS';
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
	var posData = { finalizado: function() { return $('#finalizado').val(); } };
	var vBarrTop = false;
	
	vis_PonGrilla(vIdGri,'pagRegSos',vUrl,vTitulo,vColNam,vColMod,100,pAlto,1238,'',vFunGridCom,vBarrTop,posData);
}

function Editar(pId)
{
	$('#ifrEdiSOS').attr('src', "../registro_sos_edi.php?id_buscar="+pId)
	$("#venEdiRegSos").dialog({ position:'center' });
	$("#venEdiRegSos").dialog("open");
}

function filtrar()
{
	$("#griRegSos").trigger("reloadGrid");
}

function ver_SOSVehInv(idSOS)
{
	$('#ifrVerSOSVehInv').attr('src', "../vehiculo_incidente.php?id_buscar="+idSOS)
	$("#venVerSOSVehInv").dialog({ position:'center' });
	$("#venVerSOSVehInv").dialog("open");
}

function regargarSOS(pId)
{
	$('#ifrEdiSOS').attr('src', "../registro_sos_edi.php?id_buscar="+pId)
}

function cerrarSOSVehInv()
{
	$("#venVerSOSVehInv").dialog("close");
}

function quitarEspere()
{
	$("#espere").css("display","none");
}