<table id="grid_penghargaan"></table>
<div id="pager_penghargaan"></div>	

<script type="text/javascript">
jQuery("#grid_penghargaan").jqGrid({ 
	url:'<?php echo site_url('pegawai/get_penghargaan').'/'.$id?>', 
	editurl:'<?php echo site_url('pegawai/penghargaan')?>', 
	datatype: "json", 
	mtype: 'POST',
	colNames:['ID', 'ID MASTER', 'Nama Penghargaan', 'Tahun', 'Instansi Pemberi', 'Keterangan'], 
	colModel:[ 
		{	name:'id',
			index:'id_h_penghargaan', 
			width:10, 
			hidden:true
		}, 
		{	name:'id_master7',
			index:'id_pegawai', 
			width:10, 
			hidden:true,
			editable:true,
			editoptions: {defaultValue:<?php echo $id; ?>}
		}, 
		{	name:'nama',
			index:'nama_penghargaan', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50},
			editrules: {required:true}
		},
		{	name:'tahun',
			index:'tahun', 
			width:120, 
			align:"left", 
			editable:true, 
			edittype:'text',
			editoptions: {size:50, maxlength:50},
			editrules: {integer:true}
		},
		{	name:'pemberi',
			index:'pemberi', 
			width:120, 
			align:"left", 
			editable:true,
			edittype:'text',
			editoptions: {size:50, maxlenth:50}
		},
		{	name:'keterangan',
			index:'keterangan', 
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
	pager: '#pager_penghargaan', 
	sortname: 'tgl_mulai', 
	sortorder: "asc", 
	viewrecords: true,
	forceFit : false, 
	width: 750,
	height: 150
}); 
jQuery("#grid_penghargaan")
.jqGrid('navGrid', '#pager_penghargaan',
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
});
</script>