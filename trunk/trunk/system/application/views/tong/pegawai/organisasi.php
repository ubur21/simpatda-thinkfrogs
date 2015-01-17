<table id="grid_org"></table>
<div id="pager_org"></div>	

<script type="text/javascript">
jQuery("#grid_org").jqGrid({ 
	url:'<?php echo site_url('pegawai/get_organisasi').'/'.$id?>', 
	editurl:'<?php echo site_url('pegawai/organisasi')?>', 
	datatype: "json", 
	mtype: 'POST',
	colNames:['ID', 'ID MASTER', 'Organisasi', 'Jabatan', 'Tahun Mulai', 'Tahun Berhenti', 'Tempat', 'Pimpinan'], 
	colModel:[ 
		{	name:'id',
			index:'id_h_organisasi', 
			width:10, 
			hidden:true
		}, 
		{	name:'id_master4',
			index:'id_pegawai', 
			width:10, 
			hidden:true, 
			editable:true,
			editoptions: {defaultValue:<?php echo $id; ?>}
		}, 
		{	name:'nama',
			index:'nama_organisasi', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50},
			editrules: {required:true}
		},
		{	name:'jabatan',
			index:'jabatan', 
			width:120, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50}
		},
		{	name:'mulai',
			index:'tahun_mulai', 
			width:120, 
			align:"right",
			editable:true, 
			edittype:'text',
			editoptions: {size:10, maxlength:4},
			editrules: {integer: true}
		},
		{	name:'berhenti',
			index:'tahun_selesai', 
			width:120, 
			align:"right",
			editable:true, 
			edittype:'text',
			editoptions: {size:10, maxlength:4},
			editrules: {integer: true}
		},
		{	name:'tempat',
			index:'tempat', 
			width:120, 
			align:"left", 
			editable:true, 
			edittype:'text',
			editoptions: {size:50, maxlength:50}
		},
		{	name:'pimpinan',
			index:'pimpinan', 
			width:120, 
			align:"left", 
			editable:true,
			edittype:'text',
			editoptions: {size:50, maxlenth:50}
		}
	], 
	rowNum:10, 
	rowList:[10,20,30], 
	rownumbers: true,
	pager: '#pager_org', 
	sortname: 'tahun_mulai', 
	sortorder: "asc", 
	viewrecords: true,
	forceFit : false, 
	width: 750,
	height: 150
}); 
jQuery("#grid_org")
.jqGrid('navGrid', '#pager_org',
	{edit:true,add:true,del:true, search:false},
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
	}
);
</script>