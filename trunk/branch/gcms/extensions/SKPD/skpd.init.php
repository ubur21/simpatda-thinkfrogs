<?php
/*
GROUP::Entri Data
NAME:: SKPD
TYPE:: frontend
LEVEL:: 1
DEPENDENCY:: 
INFO:: -
VERSION:: 0.1
AUTHOR::  umi
URL:: 
SOURCE::
COMMENT 
*/
$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";
?> 
<link rel="stylesheet" type="text/css" media="screen" href="script/jquery/themes/ui.jqgrid.css" />
<script src="script/jquery/jquery.js" type="text/javascript"></script>
<script src="script/jquery/grid.locale-id.js" type="text/javascript"></script>
<script src="script/jquery/jquery.jqGrid.min.js" type="text/javascript"></script>
<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','Kode SKPD', 'Nama SKPD'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false
	},{
		name:'kode'
		,index:'kode_skpd'
		,width:80
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:10, maxlength: 10}
        ,editrules: {required:true}
	},{
		name:'nama'
		,index:'nama_skpd'
		,width:600
		,align:'left'
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:80, maxlength: 200}
        ,editrules: {required:true}
    }],
    pager: jQuery('#htmlPager'),
    height:350,
    rowNum:15,
    rowList:[15,30,45],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'SKPD',
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
            }
    }).navGrid('#htmlPager'
        ,{add:true,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
        
        ).hideCol('id');  /*end of on ready event */ 
}
);

</script> 