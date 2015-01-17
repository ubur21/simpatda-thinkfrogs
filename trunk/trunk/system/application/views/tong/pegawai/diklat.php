<table id="grid_diklat"></table>
<div id="pager_diklat"></div>	

<script type="text/javascript">
jQuery("#grid_diklat").jqGrid({ 
	url:'<?php echo site_url('pegawai/get_diklat'.'/'.$id)?>', 
	editurl:'<?php echo site_url('pegawai/diklat')?>', 
	datatype: "json", 
	mtype: 'POST',
	colNames:['ID', 'ID MASTER', 'Nama Diklat', 'Penyelenggara', 'Tgl Mulai', 'Tgl Selesai', 'Tempat', 'No. STTPP', 'Tgl. STTPP', 'Nilai'], 
	colModel:[ 
		{	name:'id',
			index:'id_h_diklat', 
			width:10, 
			hidden:true
		}, 
		{	name:'id_master6',
			index:'id_pegawai', 
			width:10, 
			hidden:true,
			editable:true,
			editoptions: {defaultValue:<?php echo $id; ?>}
		}, 
		{	name:'nama',
			index:'nama_diklat', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50},
			editrules: {required:true}
		},
		{	name:'penyelenggara',
			index:'penyelenggara', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50}
		},
		{	name:'mulai',
			index:'tgl_mulai', 
			width:120, 
			align:"left",
			editable:true, 
			edittype:'text',
			editoptions: {
				size:15, maxlength: 10,
				dataInit: function(el) {
					$(el).datepicker()
				}
			},
			datefmt: 'dd/mm/yyyy',
			formatter:'date',
			editrules: {required:false, date:true}
		},
		{	name:'selesai',
			index:'tgl_selesai', 
			width:120, 
			editable:true, 
			edittype:'text',
			editoptions: {
				size:15, maxlength: 10,
				dataInit: function(el) {
					$(el).datepicker()
				}
			},
			datefmt: 'dd/mm/yyyy',
			formatter:'date',
			editrules: {required:false, date:true}
		},
		{	name:'tempat',
			index:'tempat', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50}
		},
		{	name:'no_sttpp',
			index:'no_sttpp', 
			width:120, 
			align:"left", 
			editable:true, 
			edittype:'text',
			editoptions: {size:50, maxlength:50}
		},
		{	name:'tgl_sttpp',
			index:'tgl_sttpp', 
			width:120, 
			editable:true, 
			edittype:'text',
			editoptions: {
				size:15, maxlength: 10,
				dataInit: function(el) {
					$(el).datepicker()
				}
			},
			datefmt: 'dd/mm/yyyy',
			formatter:'date',
			editrules: {required:false, date:true}
		},
		{	name:'nilai',
			index:'nilai', 
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
	pager: '#pager_diklat', 
	viewrecords: true,
	forceFit : false, 
	width: 750,
	height: 150
}); 
jQuery("#grid_diklat")
.jqGrid('navGrid', '#pager_diklat',
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