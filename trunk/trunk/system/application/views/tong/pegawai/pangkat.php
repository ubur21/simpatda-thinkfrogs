<table id="grid_gol"></table>
<div id="pager_gol"></div>	

<script type="text/javascript">
jQuery("#grid_gol").jqGrid({ 
	url:'<?php echo site_url('pegawai/get_pangkat').'/'.$id?>', 
	editurl:'<?php echo site_url('pegawai/pangkat')?>', 
	datatype: "json", 
	mtype: 'POST',
	colNames:['ID', 'ID MASTER', 'Gol/Ruang', 'TMT', 'Gaji Pokok', 'MK Tahun', 'MK Bulan', 'Masa Kerja', 'No SK', 'Tgl SK', 'Pejabat SK'], 
	colModel:[ 
		{	name:'id',
			index:'id_h_pangkat', 
			width:10, 
			hidden:true
		}, 
		{	name:'id_master2',
			index:'id_pegawai', 
			width:10, 
			hidden:true,
			editable:true,
			editoptions: {defaultValue:<?php echo $id; ?>}
		}, 
		{	name:'gol',
			index:'pangkat', 
			width:100, 
			align:"left", 
			editable:true, 
			edittype:'select', 
			editoptions: {
				dataUrl:'<?php echo site_url('master/pangkat/getselectv')?>', 
				style:'width:200px;'
			}
		},
		{	name:'tmt',
			index:'tmt', 
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
		{	name:'gapok',
			index:'gaji_pokok', 
			width:120, 
			align:"right",
			editable:true, 
			edittype:'text',
			editrules: {number:true},
			formatter: 'currency'
		},
		{	name:'mk_tahun',
			index:'mk_tahun', 
			width:120, 
			align:"left",
			hidden:true,
			editable:true, 
			edittype:'text',
			editoptions: {},
			editrules: {edithidden:true, integer:true}
		},
		{	name:'mk_bulan',
			index:'mk_bulan', 
			width:120, 
			hidden:true,
			editable:true, 
			edittype:'text',
			editoptions: {},
			editrules: {edithidden:true, integer:true}
		},
		{	name:'masa_kerja',
			index:'masa_kerja', 
			width:120, 
			align:"left", 
			editable:false, 
			edittype:'text'
		},
		{	name:'no_sk',
			index:'no_sk', 
			width:120, 
			align:"left", 
			editable:true,
			edittype:'text',
			editoptions: {
				size:50, maxlenth:50
			}
		},
		{	name:'tgl_sk',
			index:'tanggal_sk', 
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
		{	name:'pejabat_sk',
			index:'pejabat_sk', 
			width:200, 
			align:"left", 
			editable:true,
			edittype:'text',
			editoptions: {
				size:50, maxlenth:50
			}
		},
	], 
	rowNum:10, 
	rowList:[10,20,30], 
	rownumbers: true,
	pager: '#pager_gol', 
	sortname: 'urutan', 
	sortorder: "asc", 
	viewrecords: true,
	forceFit : false, 
	width: 750,
	height: 150
}); 
jQuery("#grid_gol")
.jqGrid('navGrid', '#pager_gol',
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