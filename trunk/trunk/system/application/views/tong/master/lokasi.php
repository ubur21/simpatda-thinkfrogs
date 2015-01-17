<br />
<table id="grid"></table> 
<div id="pager"></div>

<script type="text/javascript">
	jQuery("#grid").jqGrid(
	{ 
		url:'<?php echo site_url('master/lokasi/get_daftar')?>', 
		editurl:'<?php echo site_url('master/lokasi')?>', 
		mtype: "post",
		datatype: "json", 
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
				index:'induk_lokasi',
				hidden:true,
				editable:true,
				editrules: {edithidden:true},
				edittype:'select', 
				editoptions: {
					dataUrl:'<?php echo site_url('master/lokasi/getselectv')?>', 
					style:'width:200px;'
				}
			},
			{
				name:'kode',
				index:'kode_lokasi', 
				width:20, 
				align:"center", 
				editable:true, 
				edittype:'text', 
				editoptions: {size:20, maxlength: 20},
				editrules: {required:true}
			}, 
			{
				name:'nama',
				index:'nama_lokasi', 
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
		pager: '#pager', 
		sortname: 'kode_lokasi', 
		viewrecords: true, 
		multiselect: true,
		altrow: true,
		sortorder: "asc", 
		width: 600,
		height: 230,
		caption:"Daftar Lokasi" }
	); 
	jQuery("#grid").jqGrid('navGrid','#pager',{edit:true,add:true,del:true},
	{
		width:600,
		recreateForm:true,
		afterSubmit:checkError
	},
	{
		width:600,
		recreateForm:true,
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