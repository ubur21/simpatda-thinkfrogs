<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_DetailHotel");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);
?>
<script>

var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=DetailHotel',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=DetailHotel&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','','Nama Kamar','Jumlah Kamar','Tarif Kamar'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false

	},{
		name:'hotel_id',index:'hotel_id',width:1,editable:true,edittype:'text',editoptions:{value :<?=$_REQUEST['detail']?>}
	},{
		name:'nama_kamar',index:'nama_kamar',width:180,editable:true,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'jumlah_kamar',index:'jumlah_kamar',width:80,editable:true,edittype:'text',editoptions: {size:15, maxlength: 5},editrules: {required:true}
	},{
		name:'tarif_kamar',index:'tarif_kamar',width:80,editable:true,edittype:'text',editoptions: {size:20, maxlength: 12},editrules: {required:true}
	}
	
	],
    pager: jQuery('#htmlPager'),
    height:150,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data Detail Hotel',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
                jQuery("#htmlTable").restoreRow(lastsel); 
                jQuery("#htmlTable").editRow(id,true); 
                lastsel=id; 
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth( document.width - 160 < 300?300:document.width - 120);
                return true;
            }
    }).navGrid('#htmlPager'
        ,{add:true,edit:true,del:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
        
        ).hideCol(['id']);  /*end of on ready event */ 
}
);

</script>

<div style='padding:5px;'>
				<fieldset>
				
					<div id='asb_simulasi_form'>
						<div class="singleSide">
							<fieldset class="mainForm" style="background:#999999;">
							<table id="htmlTable" class="scroll"></table>
							<div id="htmlPager" class="scroll"></div>
							</fieldset>
							
						</div>
						
						<div class="footer_space">&nbsp;</div>
					</div>		
				</fieldset>
			</div>