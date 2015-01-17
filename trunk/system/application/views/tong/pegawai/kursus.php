<table id="grid_kursus"></table>
<div id="pager_kursus"></div>	

<script type="text/javascript">
jQuery("#grid_kursus").jqGrid({ 
	url:'<?php echo site_url('pegawai/get_kursus'.'/'.$id)?>', 
	editurl:'<?php echo site_url('pegawai/kursus')?>', 
	datatype: "json", 
	mtype: 'POST',
	colNames:['ID', 'ID MASTER', 'Nama Kursus', 'Penyelenggara', 'Tgl Mulai', 'Tgl Selesai', 'Ijasah', 'Tempat', 'Nilai', 'Keterangan'], 
	colModel:[ 
		{	name:'id',
			index:'id_h_organisasi', 
			width:10, 
			hidden:true
		}, 
		{	name:'id_master5',
			index:'id_pegawai', 
			width:10, 
			hidden:true,
			editable:true,
			editoptions: {defaultValue:<?php echo $id; ?>}
		}, 
		{	name:'nama',
			index:'nama_kursus', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50},
			editrules: {required:true}
		},
		{	name:'penyelenggara',
			index:'institusi', 
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
		{	name:'ijasah',
			index:'tanda_lulus', 
			width:120, 
			align:"left", 
			editable:true, 
			edittype:'text',
			editoptions: {size:50, maxlength:50}
		},
		{	name:'tempat',
			index:'tempat', 
			width:120, 
			align:"left", 
			editable:true,
			edittype:'text',
			editoptions: {size:50, maxlenth:50}
		},
		{	name:'nilai',
			index:'pimpinan', 
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
	pager: '#pager_kursus', 
	sortname: 'tgl_mulai', 
	sortorder: "asc", 
	viewrecords: true,
	forceFit : false, 
	width: 750,
	height: 150
}); 
jQuery("#grid_kursus")
.jqGrid('navGrid', '#pager_kursus',
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