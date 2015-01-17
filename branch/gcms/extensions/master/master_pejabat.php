<? 
$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

	$qy = 'select id, nama from jabatan ';
	$data = gcms_query($qy); $value='';
	while($rs = gcms_fetch_object($data)){
		$value1.="'$rs->id':'$rs->nama',";
	}

	$qy = 'select id, nama from golongan ';
	$data = gcms_query($qy); $value='';
	while($rs = gcms_fetch_object($data)){
		$value2.="'$rs->id':'$rs->nama',";
	}	

	$qy = 'select id, nama from pangkat ';
	$data = gcms_query($qy); $value='';
	while($rs = gcms_fetch_object($data)){
		$value3.="'$rs->id':'$rs->nama',";
	}	
?>

<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    //url:'<?php echo $expath ?>anggaran_request.php',
	url:'request.php?page=<?=$_REQUEST['page']?>&sender=pejabat',
	//editurl:'<?php echo $expath ?>anggaran_request.php?oper=edit',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=pejabat&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','NAMA','JABATAN','GOLONGAN','PANGKAT','NIP','STATUS'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false
	},{
		name:'nama'
		,index:'nama'
		,width:130
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:30, maxlength: 100}
        ,editrules: {required:true}
	},{
		name:'jabatan'
		,index:'jabatan_id'
		,width:130
		,editable:true
        ,edittype:'select'
		,formatter:'select'
        ,editoptions: {value:{<?=$value1?>}}
        ,editrules: {required:true}
	},{
		name:'golongan'
		,index:'golongan_id'
		,width:130
		,editable:true
        ,edittype:'select'
		,formatter:'select'
        ,editoptions: {value:{<?=$value2?>}}
        ,editrules: {required:true}
	},{
		name:'pangkat'
		,index:'pangkat_id'
		,width:130
		,editable:true
        ,edittype:'select'
		,formatter:'select'
        ,editoptions: {value:{<?=$value3?>}}
        ,editrules: {required:true}
	},{
		name:'nip'
		,index:'nip'
		,width:130
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:30, maxlength: 100}
        ,editrules: {required:true}
	},{
		name:'status'
		,index:'status'
		,width:130
		,editable:true
        ,edittype:'select'
		,formatter:'select'
        ,editoptions: {value:{'Aktif':'Aktif'}}
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
    caption: 'PEJABAT',
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
        ,{height:200,  width:500} // add
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
				