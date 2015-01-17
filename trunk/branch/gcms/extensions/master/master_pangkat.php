<? $expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";?>

<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    //url:'<?php echo $expath ?>anggaran_request.php',
	url:'request.php?page=<?=$_REQUEST['page']?>&sender=pangkat',
	//editurl:'<?php echo $expath ?>anggaran_request.php?oper=edit',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=pangkat&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','NAMA'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false

	},{
		name:'nama'
		,index:'nama'
		,width:200
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	}
	],
    pager: jQuery('#htmlPager'),
    height:150,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
	multiselect:true,
	multiboxonly: true,
	altRows:true,	
    viewrecords: true,
    caption: 'PANGKAT',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
                //jQuery("#htmlTable").restoreRow(lastsel); 
                //jQuery("#htmlTable").editRow(id,true); 
                lastsel=id; 
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth( document.width - 160 < 300?300:document.width - 160);
                return true;
            }
    }).navGrid('#htmlPager'
        ,{add:true,edit:true,del:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
        
        ).hideCol('id');  /*end of on ready event */ 
}
);
</script>
<div style='padding:5px;'>
	<fieldset>
	<legend>Daftar</legend>
		<div id='asb_simulasi_form'>
			<div style='padding:5px'>
				<table id="htmlTable" class="scroll"></table>
				<div id="htmlPager" class="scroll"></div>
			</div>
		</div>		
	</fieldset>
</div>				
				