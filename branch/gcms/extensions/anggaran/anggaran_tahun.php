<? $expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";
   $qy = 'select id,status from anggaran_status ';
   $data = gcms_query($qy); $value='';
   while($rs = gcms_fetch_object($data)){
		$value.="'$rs->id':'$rs->status',";
   }
?>

<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=tahun_anggaran',
	//editurl:'<?php echo $expath ?>anggaran_request.php?oper=edit',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=tahun_anggaran&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','TAHUN',"STATUS"],
    colModel :[
		{ name:'id',index:'id',width:20 ,search:false },
		{ name:'tahun',index:'tahun1',width:80,editable:true  ,edittype:'text' ,editoptions: {size:10, maxlength: 10} ,editrules: {required:true} },
		{ name:'status' ,index:'status',width:400,align:'left',editable:true ,edittype:'select',formatter:'select' ,editoptions: {value:{<?=$value?>}} ,editrules: {required:true} }
	],
    pager: jQuery('#htmlPager'),
    height:350,
    rowNum:15,
    rowList:[15,30,45],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
	multiselect:true,
	multiboxonly: true,
	altRows:true,
    viewrecords: true,
    caption: 'TAHUN ANGGARAN',
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