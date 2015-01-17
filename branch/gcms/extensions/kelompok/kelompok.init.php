<?php
/*
GROUP::Entri Data
NAME:: Kelompok
TYPE:: frontend
LEVEL:: 1
DEPENDENCY:: 
INFO:: -
VERSION:: 0.1
AUTHOR::  umi
URL:: 
SOURCE:: 
*/
$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";
?> 
<link rel="stylesheet" type="text/css" media="screen" href="script/jquery/themes/ui.jqgrid.css" />
<script src="script/jquery/jquery.js" type="text/javascript"></script>
<script src="script/jquery/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="script/jquery/jquery.layout.js" type="text/javascript"></script>
<script src="script/jquery/grid.locale-id.js" type="text/javascript"></script>
<script src="script/jquery/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="script/jquery/jquery.tablednd.js" type="text/javascript"></script>
<script type="text/javascript" src="./extensions/fastreport/fastreport.js"></script>
<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    url:'<?php echo $expath ?>kelompok_request.php',
	editurl:'<?php echo $expath ?>kelompok_request.php?oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','Kode', 'Nama','Keterangan','Nama Indikator 1','Nama Indikator 2', 'Nama Indikator 3','Nama Indikator 4'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false
        ,editoptions:{readonly:true}
	},{
		name:'kode'
		,index:'kode_kelompok'
		,width:50
		,editable:true
        ,editoptions:{size:20, maxlength:10 }
        ,editrules: {required:true, number:true}
	},{
		name:'nama'
		,index:'nama_kelompok'
		,width:250
		,align:'left'
		,editable:true
        ,edittype:"textarea"
        ,editoptions:{size:80, maxlength:200}
        ,editrules: {required:true}
	},{
		name:'keterangan'
		,index:'keterangan'
		,width:300
		,align:'left'
		,editable:true
        ,edittype:"textarea"
        ,editoptions:{rows:"2", cols:"50", size:80, maxlength:200} 
	},{
	  	name:'indikator1'
		,index:'nama_indikator_1'
		,width:120
		,align:'left'
		,editable:true
        ,editoptions:{size:15, maxlength:200}
	},{
	  	name:'indikator2'
		,index:'nama_indikator_2'
		,width:120
		,align:'left'
		,editable:true
        ,editoptions:{size:15, maxlength:200}
	},{
	  	name:'indikator3'
		,index:'nama_indikator_3'
		,width:120
		,align:'left'
		,editable:true
        ,editoptions:{size:15, maxlength:200}
	},{
	  	name:'indikator4'
		,index:'nama_indikator_4'
		,width:120
		,align:'left'
		,editable:true
        ,editoptions:{size:15, maxlength:200}
	}],
    pager: jQuery('#htmlPager'),
    height:350,
    rowNum:15,
    rowList:[15,30,45],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Kelompok Kegiatan',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
                jQuery("#htmlTable").restoreRow(lastsel); 
                jQuery("#htmlTable").editRow(id,true); 
                lastsel=id; 
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth( document.width - 160 < 300?300:document.width - 160);
                return true;
            },
        afterSaveCell : function(){
                alert('saving');
            }
	}).navGrid('#htmlPager'
        ,{add:true,edit:false, del :true}
        ,{} //edit
        ,{height:300,width:450} // add options 
        ,{} // delete
        ).hideCol('id'); /* end of on ready event */ 
}
);




function printReport(){
		var nameFile,template;
				nameFile="LIST_KEGIATAN";
				template="LIST_KEGIATAN.fr3";
		var key = "id=1";
        var att = 1;
			fastReportStart(nameFile, template, 'pdf', key, att);

	}	

</script> 