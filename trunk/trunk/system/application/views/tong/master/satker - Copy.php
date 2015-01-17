<br />
<table id="grid"></table> 
<div id="pager"></div>

<script type="text/javascript">
	jQuery("#grid").jqGrid(
	{ 
		url:'<?php echo site_url('master/satker/get_daftar')?>', 
		editurl:'<?php echo site_url('master/satker')?>', 
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
				index:'induk',
				hidden:true,
				editable:true,
				editrules: {edithidden:true},
				edittype:'select', 
				editoptions: {
					dataUrl:'<?php echo site_url('master/satker/getselect')?>', 
					style:'width:200px;',
					multiple:true, size:3
				}
			},
			{
				name:'kode',
				index:'kode_satker', 
				width:20, 
				align:"center", 
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
		pager: '#pager', 
		sortname: 'kode_satker', 
		viewrecords: true, 
		multiselect: true,
		altrow: true,
		sortorder: "asc", 
		width: 600,
		height: 230,
		caption:"Daftar Satker" }
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