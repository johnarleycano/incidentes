function iniciarForma()
{
	IniciarGrid();
	var pAlto = $(document).height()-20;
	var pAncho = $(document).width()-50;
	
	vis_PonVentana('venBuscar','INFORME GENERAL DE ATENCIONES',pAlto,pAncho);
	vis_PonVentana('venProm','INFORME GENERAL DE ATENCIONES',280,380);
	vis_PonBoton(".clsBtn");
	
	vis_ponCampoFecha2(".cmpFec");
}

function IniciarGrid()
{
	var pAlto = $(document).height()-240;
	var vIdGri = 'griBuscar';
	var vUrl   = 'busqueda.grid.php';
	var vTitulo = 'Motor de Busquedas SOS1';
	var vColNam = [ 'ID','DOCS','FECHA','DIA','HORA<br/>REPORTE','HORA<br/>LLEGADA','TIEMPO<br/>(HH:MM)','DURACION<br/>EVENTO','ABSCISA','REFERENCIA','VIA','CONDICIONES','SENTIDO','INFORMADO POR',
					'TIPO ATENCION','NRO MUERTOS','NRO HERIDOS','AMBULANCIA','GRUA','VEHICULO INVOLUCRADO','PLACAS','CILINDRAJE','NOMBRE USUARIO',
					'IDENTIFICACION USUARIO','TIPO LESIONADO','EDAD','SITIO TRASLADO VEHICULO','SITIO TRASLADO USUARIO', 'PESV', 'OBSERVACIONES' ];
	var vColMod = [	
					{name:'id',				index:'id',				width:80,	sortable:false},
					{name:'docs',			index:'docs',			width:65,	sortable:false, align:"center"}, 
					{name:'fecha',			index:'fecha',			width:80,	sortable:false},
					{name:'dia',			index:'dia',			width:70,	sortable:false},
					{name:'hora_reporte',	index:'hora_reporte',	width:60,	sortable:false},
					{name:'hora_llegada',	index:'hora_llegada',	width:60,	sortable:false},
					{name:'tiempo',			index:'tiempo',			width:55,	sortable:false},
					{name:'duracion',		index:'duracion',		width:60,	sortable:false},
					{name:'abscisa',		index:'abscisa',		width:70,	sortable:false},
					{name:'referencia',		index:'referencia',		width:160,	sortable:false},
					{name:'via',			index:'via',			width:180,	sortable:false},
					{name:'condiciones',	index:'condiciones',	width:90,	sortable:false},
					{name:'sentido_via',	index:'sentido_via',	width:180,	sortable:false},
					{name:'infor_por',		index:'infor_por',		width:180,	sortable:false},
					{name:'tipo_atencio',	index:'tipo_atencio',	width:70,	sortable:false},
					{name:'nro_muertos',	index:'nro_muertos',	width:70,	sortable:false},
					{name:'nro_heridos',	index:'nro_heridos',	width:70,	sortable:false},
					{name:'ambulancia',		index:'ambulancia',		width:120,	sortable:false},
					{name:'grua',			index:'grua',			width:120,	sortable:false},
					{name:'vehi_involu',	index:'vehi_involu',	width:120,	sortable:false},
					{name:'placas',			index:'placas',			width:70,   sortable:false},
					{name:'cilindraje',		index:'cilindraje',		width:70,	sortable:false},
					{name:'nom_usuario',	index:'nom_usuario',	width:180,	sortable:false},
					{name:'ide_usuario',	index:'ide_usuario',	width:100,	sortable:false},
					{name:'tip_lesiona',	index:'tip_lesiona',	width:200,	sortable:false},
					{name:'edad',			index:'edad',			width:50,	sortable:false},
					{name:'sit_tras_vehi',	index:'sit_tras_vehi',	width:180,	sortable:false},
					{name:'sit_tras_usua',	index:'sit_tras_usua',	width:180,	sortable:false},
					{name:'pesv',			index:'pesv',			width:50,	sortable:false},
					{name:'observaciones',	index:'observaciones',	width:250,	sortable:false},
				 ];

	var vFunGridCom = function(){ gridCompleto($("#griBuscar").getGridParam('userData')); };
	var vPosDat = $("#frmBus").serialize();
	vis_PonGrillaBusqueda(vIdGri,'pagBuscar',vUrl,vTitulo,vColNam,vColMod,100,pAlto,1238,'',vFunGridCom,vPosDat);
	$.extend(true, $.ui.multiselect, {
    locale: {
        addAll: 'Mostrar Todos',
        removeAll: 'Ocultar Todos',
        itemsCount: 'Columnas Disponibles'
    }
});
}

function AbrirBus()
{
	$("#venBuscar").dialog("open");
}

function CerrarBus()
{
	$("#venBuscar").dialog("close");
}

function AbrirProm()
{
	$("#venProm").dialog("open");
}

function CerrarProm()
{
	$("#venProm").dialog("close");
}

function Buscar(pCompleto)
{
	$('#spnMedAri').html('');
	$("#griBuscar").showCol("ambulancia");
	$("#griBuscar").showCol("grua");
	$("#griBuscar").showCol("vehi_involu");
	$("#griBuscar").showCol("placas");
	$("#griBuscar").showCol("cilindraje");
	$("#griBuscar").showCol("nom_usuario");
	$("#griBuscar").showCol("ide_usuario");
	$("#griBuscar").showCol("tip_lesiona");
	$("#griBuscar").showCol("edad");
	$("#griBuscar").showCol("sit_tras_vehi");
	$("#griBuscar").showCol("sit_tras_usua");
	$("#griBuscar").showCol("pesv");
	
	if( pCompleto=="0" || pCompleto==0 )
	{
		$("#griBuscar").hideCol("ambulancia");
		$("#griBuscar").hideCol("grua");
		$("#griBuscar").hideCol("vehi_involu");
		$("#griBuscar").hideCol("placas");
		$("#griBuscar").hideCol("nom_usuario");
		$("#griBuscar").hideCol("ide_usuario");
		$("#griBuscar").hideCol("tip_lesiona");
		$("#griBuscar").hideCol("edad");
		$("#griBuscar").hideCol("sit_tras_vehi");
		$("#griBuscar").hideCol("sit_tras_usua");
	}
	
	$('#spnDes').hide ();
	$('#buscar').val("SI");
	$('#completo').val(pCompleto);
	var x = $("#frmBus").serializeArray();

	var cadArr = '';
	$.each(x, function(i, field)
	{
		if( cadArr!='' )
			cadArr += ',';

		cadArr += field.name+':"'+field.value+'"';
	});

	$('input[type=checkbox]').each(function()
	{
		if( cadArr!='' )
			cadArr += ',';
		if( this.checked )
			cadArr += this.name+':"SI"';
		else
			cadArr += this.name+':"NO"';
	});
	
	cadArr = 'var arrPost = {'+cadArr+'}';
	eval(cadArr);
	
	$("#griBuscar").jqGrid('setGridParam', 
	{
		postData: arrPost
		
	});

	$("#griBuscar").jqGrid('setGridParam',{datatype:'json',page:1}).trigger('reloadGrid');

	CerrarBus();
}

function gridCompleto(pDato)
{
	var totReg = pDato.totReg;
	
	if( totReg>0 )
		$('#spnDes').show();
	
	$('#tdTotMan').html(pDato.totMan);
	$('#tdTotTar').html(pDato.totTar);
	$('#tdTotNoc').html(pDato.totNoc);
	$('#tdTotMad').html(pDato.totMad);
	
	$('#tdTotLun').html(pDato.totLun);
	$('#tdTotMar').html(pDato.totMar);
	$('#tdTotMie').html(pDato.totMie);
	$('#tdTotJue').html(pDato.totJue);
	$('#tdTotVie').html(pDato.totVie);
	$('#tdTotSab').html(pDato.totSab);
	$('#tdTotDom').html(pDato.totDom);
}

function abrirMasMenosCols()
{
	$("#griBuscar").jqGrid('columnChooser', {modal: true});
}

function abrirDescarga()
{
	window.open('../descargar.php?adjunto=adjuntos/reporte.csv','_blank');
}

function abrirDescarga1()
{
	window.open('../descargar.php?adjunto=adjuntos/reporte1.csv','_blank');
}


