<br />
<table id="grid"></table> 
<div id="pager"></div>

<script type="text/javascript">
	jQuery("#grid").jqGrid({ 
	   url:'<?php echo site_url('master/jab_struktural/get_daftar')?>', 
	   editurl:'<?php echo site_url('master/jab_struktural')?>', 
       datatype: "json", 
	   mtype: 'POST',
       colNames:['ID', 'Nama'], 
       colModel:[ 
            {name:'id',index:'id_jabatan_struktural', width:20, search:false, hidden:true}, 
            {name:'nama',index:'nama_jabatan_struktural', width:100, align:"left", editable:true, edittype:'text', editoptions: {size:50, maxlength: 200},editrules: {required:true}}
		], 
       rowNum:10, 
       rowList:[10,20,30], 
	   rownumbers: true,
       pager: '#pager', 
       sortname: 'id_jabatan_struktural', 
       sortorder: "asc", 
	   viewrecords: true, 
	   multiselect: true,
	   multiboxonly: true,
       width: 600,
       height: 230,
       caption:"Daftar Jabatan Struktural" }); 
    jQuery("#grid").jqGrid('navGrid','#pager',{edit:true,add:true,del:true},
	{
		width:400,
		afterSubmit:checkError
	},
	{
		width:400,
		afterSubmit:checkError
	},
	{
		afterSubmit:checkError
	}); 
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
	
</script>