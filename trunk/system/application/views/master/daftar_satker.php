<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
	jQuery("#htmlTable").jqGrid({
		url:'<?php echo site_url('master/satker/get_daftar')?>', 
		editurl:'<?php echo site_url('master/satker/get_daftar')?>', 
		datatype: 'json',
		mtype: 'POST',
		colNames:['ID', 'Induk', 'Kode', 'Nama'], 
		colModel:[ 
			{
				name:'id',
				index:'id_satker', 
				width:20, 
				search:false, 
				hidden:true
			}, 
			{
				name:'induk',
				index:'induk',
				hidden:true,
				editable:true,
				editrules: {edithidden:true},
				edittype:'select', 
				editoptions: {value:{<?=$list_satker?>}}
			},
			{
				name:'kode',
				index:'kode_satker', 
				width:20, 
				editable:true, 
				edittype:'text', 
				editoptions: {size:20, maxlength: 20},
				editrules: {required:true}
			}, 
			{
				name:'nama',
				index:'nama_satker', 
				width:100, 
				align:"left", 
				editable:true, 
				edittype:'text', 
				editoptions: {size:100, maxlength: 200},
				editrules: {required:true}
			}
		], 
		rowNum:10, 
		rowList:[10,20,30], 		
		pager: jQuery('#htmlPager'),
		height:300,
		viewrecords: true, 
		multiselect: true,
		altrow: true,
		rownumbers: true, 
		sortorder: "asc", 
		width: 800,
		height: 230,
		caption:"Daftar Satker",
        onSelectRow: function(id){ }
    }).navGrid('#htmlPager'
        ,{view:true,add:true,edit:true,del:true,viewtext:"Lihat",searchtext:"Cari",addtext:"Tambah",deltext:'Hapus',edittext:'Ubah',refreshtext:'Refresh'}
        ,{closeOnEscape:true, width:600,afterSubmit:checkError} // edit
        ,{closeOnEscape:true, width:600,afterSubmit:checkError} // add
        ,{closeOnEscape:true,reloadAfterSubmit:false} // del 
        ,{closeOnEscape:true,closeAfterSearch: true} // multipleSearch:true,		
    ).hideCol('id');
	
	function checkError(response, postdata) {
		var success = true;
		var message = ""
		var json = eval('(' + response.responseText + ')');
		if(json.errors) {
			success = false;
			for(i=0; i < json.errors.length; i++) {
				message += json.errors[i];
			}
		}
		var new_id = json.id;
		return [success,message,new_id];
	}	

});
</script>
<table id="htmlTable" class="scroll"></table>
<div id="htmlPager" class="scroll"></div>