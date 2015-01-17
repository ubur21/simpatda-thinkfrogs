<? $expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";?>

<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
	url:'request.php?page=<?=$_REQUEST['page']?>&sender=kategori',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=kategori&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','KODE KATEGORI','NAMA KATEGORI','SALDO NORMAL'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false

	},{
		name:'kode'
		,index:'tipe_kategori'
		,width:130
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:10, maxlength: 10}
        ,editrules: {required:true}
	},{
		name:'nama'
		,index:'nama_kategori'
		,width:300
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'saldo'
		,index:'saldo_normal'
		,width:170
		,editable:true
        ,edittype:'select'
		,formatter:'select'
        ,editoptions: {value:{'-1':'Debet','1':'Kredit'}}
        ,editrules: {required:true}
	}
	],
    pager: jQuery('#htmlPager'),
    height:300,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
	multiselect:true,
	multiboxonly: true,
	altRows:true,		
    viewrecords: true,
    caption: 'KATEGORI REKENING',
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
        ,{height:120, width:500} // add
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
