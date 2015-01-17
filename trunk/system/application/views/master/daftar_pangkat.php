<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
	jQuery("#htmlTable").jqGrid({
		url:'<?php echo site_url('master/pangkat/get_daftar')?>', 
		editurl:'<?php echo site_url('master/pangkat')?>', 
		datatype: 'json',
		mtype: 'POST',
       colNames:['ID', 'Golongan', 'Ruang', 'Kode', 'Nama'], 
       colModel:[ 
            {name:'id',index:'id_pangkat', width:20, search:false, hidden:true}, 
			{name:'golongan',index:'golongan', width:100, align:"left", hidden:true, editable:true, edittype:'select', editoptions: {value:"1:I;2:II;3:III;4:IV", size:2, maxlength: 1},editrules: {edithidden:true, required:true}},
			{name:'ruang',index:'ruang', width:100, align:"left", hidden:true, editable:true, edittype:'select', editoptions: {value:"A:A;B:B;C:C;D:D;E:E", size:2, maxlength: 1},editrules: {edithidden:true, required:true}},
			{name:'kode',index:'kode_pangkat', width:100, align:"left"},
            {name:'nama',index:'nama_pangkat', width:400, align:"left", editable:true, edittype:'text', editoptions: {size:50, maxlength: 100},editrules: {required:true}}
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
		caption:"Daftar Pangkat",
        onSelectRow: function(id){ }
    }).navGrid('#htmlPager'
        ,{view:true,add:true,edit:true,del:true,viewtext:"Lihat",searchtext:"Cari",addtext:"Tambah",deltext:'Hapus',edittext:'Ubah',refreshtext:'Refresh'}
        ,{closeOnEscape:true, width:600,afterSubmit:checkError} // edit
        ,{closeOnEscape:true, width:600,afterSubmit:checkError} // add
        ,{closeOnEscape:true,reloadAfterSubmit:false,afterSubmit:checkError} // del 
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