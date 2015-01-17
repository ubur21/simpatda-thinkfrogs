<table id="grid_pendidikan"></table>
<div id="pager_pendidikan"></div>	

<script type="text/javascript">
jQuery("#grid_pendidikan").jqGrid({ 
	url:'<?php echo site_url('pegawai/get_pendidikan').'/'.$id?>', 
	editurl:'<?php echo site_url('pegawai/pendidikan')?>', 
	datatype: "json", 
	mtype: 'POST',
	colNames:['ID', 'ID MASTER', 'Jenjang', 'Jurusan', 'Institusi', 'Tahun Lulus', 'Bukti Kelulusan', 'Lokasi', 'Pimpinan'], 
	colModel:[ 
		{	name:'id',
			index:'id_h_pendidikan', 
			width:10, 
			hidden:true
		}, 
		{	name:'id_master9',
			index:'id_pegawai', 
			width:10, 
			hidden:true,
			editable:true,
			editoptions: {defaultValue:<?php echo $id; ?>}
		}, 
		{	name:'tingkat',
			index:'tingkat', 
			width:200, 
			align:"left", 
			editable:true, 
			editrules: {required:true},
			edittype:'select', 
			editoptions: {
				dataUrl:'<?php echo site_url('master/pendidikan/getselect')?>', 
				style:'width:200px;'
			}
		},
		{	name:'jurusan',
			index:'jurusan', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50}
		},
		{	name:'institusi',
			index:'institusi', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50},
		},
		{	name:'tahun_lulus',
			index:'tahun_lulus', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50},
			editrules: {integer:true}
		},
		{	name:'tanda_lulus',
			index:'tanda_lulus', 
			width:200, 
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
	pager: '#pager_pendidikan', 
	viewrecords: true,
	forceFit : false, 
	width: 750,
	height: 150
}); 
jQuery("#grid_pendidikan")
.jqGrid('navGrid', '#pager_pendidikan',
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