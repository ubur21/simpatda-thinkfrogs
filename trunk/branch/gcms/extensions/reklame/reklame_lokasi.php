<? $expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";?>

<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
	url:'request.php?page=<?=$_REQUEST['page']?>&sender=lokasi_reklame',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=lokasi_reklame&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','KODE','LOKASI','BOBOT','SEMBUNYIKAN'],
    colModel :[{
		name:'id'
		,index:'lokasi_id'
		,width:20
        ,search:false

	},{
		name:'kode'
		,index:'lokasi_kode'
		,width:80
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:20, maxlength: 20}
        ,editrules: {required:true}
	},{
		name:'lokasi'
		,index:'lokasi_nama'
		,width:250
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'bobot'
		,index:'lokasi_bobot'
		,width:80
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'bhide'
		,index:'bhide'
		,width:90
		,editable:true
        ,edittype:'checkbox'
		,formatter:'checkbox'
        ,editoptions: { value:"1:0" }
        ,editrules: {required:true}
	}
	],
    pager: jQuery('#htmlPager'),
    height:250,
    rowNum:10,
    rowList:[10,15,20],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'LOKASI REKLAME',
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
				