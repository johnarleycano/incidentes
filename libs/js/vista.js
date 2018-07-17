function vis_PonVentana(pId,pTitulo,pAlto,pAncho)
{
	$(function()
	{
		$( "#"+pId ).dialog(
		{
			title			: pTitulo,
			height			: pAlto,
			width			: pAncho,
			modal			: true,
			closeOnEscape	: false,
			show			: 'slide',
			hide			: 'slide',
			autoOpen		: false,
			resizable		: false,
			draggable		: false
			//open			: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); }
		});
	});
}

function vis_PonBoton(pId)
{
	$("#"+pId).button({text: true, icons:{primary: "ui-icon-plus"}});
}

function vis_PonGrilla(pIdTab,pIdPag,pUrl,pTitulo,pTitulos,pCampos,pNumRegs,pAlto,pAncho,pCmpOrd,pFunGridCom,pBarrTop,pPosDat)
{
	//alert(pUrl + pTitulo + pTitulos + pCampos + pNumRegs + pAlto +pAncho);
	$(document).ready(function()
	{
		var fixPositionsOfFrozenDivs = vis_ArreglarFrozen();
		
		if( pAlto==-1 )
			pAlto = $(document).height()-150;
		
		if( pAncho==-1 )
			pAncho = $(document).width()-50;
		
		$("#"+pIdTab).jqGrid(
		{
			datatype:	"json",
			contentType:'application/json; charset=ISO-8859-1',
			url:		pUrl,
			caption:	pTitulo,
			colNames:	pTitulos,
			colModel:	pCampos,
			rowNum:		pNumRegs,
			height:		pAlto,
			width:		pAncho,
			sortname:	pCmpOrd,
			pager:		"#"+pIdPag,
			viewrecords:true,
			mtype:		"POST",
			hidegrid:	false,
			scrolling:	true,
			shrinkToFit:false,
			toppager:	pBarrTop,
			cloneToTop:	true,
			gridComplete:pFunGridCom,
			altRows:true,
			altclass:'myAltRowClass',
			pgtext: "Pagina {0} de {1}",
			resizeStop: function()
			{
				fixPositionsOfFrozenDivs.call(this);
			},
			loadComplete: function()
			{
				fixPositionsOfFrozenDivs.call(this);
			},
			postData: pPosDat
		}).navGrid("#"+pIdPag,{add:false,edit:false,del:false,search:false,view:false,refresh:false,cloneToTop:true});

		$("#"+pIdTab).jqGrid('filterToolbar');
		$("#"+pIdTab).jqGrid('setFrozenColumns');
	});
}

function vis_PonGrillaSencilla(pIdTab,pIdPag,pUrl,pTitulo,pTitulos,pCampos,pNumRegs,pAlto,pAncho,pCmpOrd,pFunGridCom)
{
	$(document).ready(function()
	{
		var fixPositionsOfFrozenDivs = vis_ArreglarFrozen();
		
		if( pAlto==-1 )
			pAlto = $(document).height()-200;
		
		if( pAncho==-1 )
			pAncho = $(document).width()-50;
		
		$("#"+pIdTab).jqGrid(
		{
			datatype:	"json",
			contentType:'application/json; charset=ISO-8859-1',
			url:		pUrl,
			caption:	pTitulo,
			colNames:	pTitulos,
			colModel:	pCampos,
			rowNum:		pNumRegs,
			height:		pAlto,
			width:		pAncho,
			sortname:	pCmpOrd,
			pager:		"#"+pIdPag,
			viewrecords:true,
			mtype:		"POST",
			hidegrid:	false,
			scrolling:	true,
			shrinkToFit:false,
			gridComplete:pFunGridCom,
			altRows:true,
			altclass:'myAltRowClass',
			pgtext: "Pagina",
			resizeStop: function()
			{
				fixPositionsOfFrozenDivs.call(this);
			},
			loadComplete: function()
			{
				fixPositionsOfFrozenDivs.call(this);
			}
		}).navGrid("#"+pIdPag,{add:false,edit:false,del:false,search:false,view:false,refresh:false,cloneToTop:true});

		$("#"+pIdTab).jqGrid('setFrozenColumns');
	});
}

function vis_PonGrillaBusqueda(pIdTab,pIdPag,pUrl,pTitulo,pTitulos,pCampos,pNumRegs,pAlto,pAncho,pCmpOrd,pFunGridCom,pPosDat)
{
	$(document).ready(function()
	{
		var fixPositionsOfFrozenDivs = vis_ArreglarFrozen();
		
		if( pAlto==-1 )
			pAlto = $(document).height()-200;
		
		if( pAncho==-1 )
			pAncho = $(document).width()-50;
		
		$("#"+pIdTab).jqGrid(
		{
			datatype:	"json",
			contentType:'application/json; charset=ISO-8859-1',
			url:		pUrl,
			caption:	pTitulo,
			colNames:	pTitulos,
			colModel:	pCampos,
			rowNum:		pNumRegs,
			height:		pAlto,
			width:		pAncho,
			sortname:	pCmpOrd,
			pager:		"#"+pIdPag,
			//loadui:		'block',
			viewrecords:true,
			mtype:		"POST",
			hidegrid:	false,
			scrolling:	true,
			shrinkToFit:false,
			gridComplete:pFunGridCom,
			altRows:true,
			altclass:'myAltRowClass',
			loadonce:true,
			footerrow : true,
			userDataOnFooter : true,
			resizeStop: function()
			{
				fixPositionsOfFrozenDivs.call(this);
			},
			loadComplete: function()
			{
				fixPositionsOfFrozenDivs.call(this);
			}
		}).navGrid("#"+pIdPag,{add:false,edit:false,del:false,search:false,view:false,refresh:false,cloneToTop:true});

		$("#"+pIdTab).jqGrid('setFrozenColumns');
	});
}

function vis_ArreglarFrozen()
{
	var fixPositionsOfFrozenDivs;
	
	fixPositionsOfFrozenDivs = function()
	{
		var $rows;
		if (typeof this.grid.fbDiv !== "undefined") {
			$rows = $('>div>table.ui-jqgrid-btable>tbody>tr', this.grid.bDiv);
			$('>table.ui-jqgrid-btable>tbody>tr', this.grid.fbDiv).each(function (i) {
				var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
				if ($(this).hasClass("jqgrow")) {
					$(this).height(rowHight);
					rowHightFrozen = $(this).height();
					if (rowHight !== rowHightFrozen) {
						$(this).height(rowHight + (rowHight - rowHightFrozen));
					}
				}
			});
			$(this.grid.fbDiv).height(this.grid.bDiv.clientHeight);
			$(this.grid.fbDiv).css($(this.grid.bDiv).position());
		}
		if (typeof this.grid.fhDiv !== "undefined") {
			$rows = $('>div>table.ui-jqgrid-htable>thead>tr', this.grid.hDiv);
			$('>table.ui-jqgrid-htable>thead>tr', this.grid.fhDiv).each(function (i) {
				var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
				$(this).height(rowHight);
				rowHightFrozen = $(this).height();
				if (rowHight !== rowHightFrozen) {
					$(this).height(rowHight + (rowHight - rowHightFrozen));
				}
			});
			$(this.grid.fhDiv).height(this.grid.hDiv.clientHeight);
			$(this.grid.fhDiv).css($(this.grid.hDiv).position());
		}
	};
	
	return fixPositionsOfFrozenDivs;
}

function vis_ponCampoFecha(pSelector)
{
	$(function()
	{
		$(pSelector).datepicker({
			showOn: "button",
			buttonImage: "img/calendar.gif",
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
			dayNamesMin:["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
		});
	});
}

function vis_ponCampoFecha2(pSelector)
{
	$(function()
	{
		$(pSelector).datepicker({
			showOn: "button",
			buttonImage: "../img/calendar.gif",
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd',
			monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
			dayNamesMin:["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
		});
	});
}

function vis_SubirArchivo(pNomCmp,pCual,pNomArc,pIdGrilla,pId)
{
	$.ajaxFileUpload
	({
		url:'../cargar.php',
		secureuri:false,
		fileElementId:pNomCmp,
		dataType: 'json',
		data:{nomCmp:pNomCmp, cual:pCual, nomArc:pNomArc, id:pId},
		success: function (data, status)
		{
			if( typeof(data.error)!='undefined' )
			{
				if(data.error!='')
					alert("Se presentaron problemas al cargar el archivo. Intente de nuevo.");//alert(data.error);
				else
				{
					$("#"+pIdGrilla).trigger("reloadGrid");
				}
			}
		},
		error: function (data, status, e)
		{ 
			//alert(e); 
			alert("Se presentaron problemas al cargar el acrhivo. Intente de nuevo.");
		}
	});
}