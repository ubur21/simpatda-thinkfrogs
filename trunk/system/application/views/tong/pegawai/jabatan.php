<table id="grid_jabatan"></table>
<div id="pager_jabatan"></div>	
<script type="text/javascript">
jQuery("#grid_jabatan").jqGrid({ 
	url:'<?php echo site_url('pegawai/get_jabatan').'/'.$id?>', 
	editurl:'<?php echo site_url('pegawai/jabatan')?>', 
	datatype: "json", 
	mtype: 'POST',
	colNames:['ID', 'ID MASTER', 'Nama Jabatan', 'Tgl Mulai', 'Tgl Berhenti', 'Gol/Ruang', 'Eselon', 'Gaji Pokok', 'No SK', 'Tgl SK', 'Pejabat SK'], 
	colModel:[ 
		{	name:'id',
			index:'id_h_jabatan', 
			width:10, 
			hidden:true
		}, 
		{	name:'id_master8',
			index:'id_pegawai', 
			width:10, 
			hidden:true,
			editable:true,
			editoptions: {defaultValue:<?php echo $id; ?>}
		}, 
		{	name:'jabatan',
			index:'jabatan', 
			width:200, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength:50},
			editrules: {required:true}
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
		{	name:'berhenti',
			index:'tgl_berhenti', 
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
		{	name:'pangkat',
			index:'pangkat', 
			width:120, 
			align:"left", 
			editable:true, 
			edittype:'select', 
			editoptions: {
				dataUrl:'<?php echo site_url('master/pangkat/getselectv')?>', 
				style:'width:200px;'
			}
		},
		{	name:'eselon',
			index:'eselon', 
			width:120, 
			align:"left", 
			editable:true,
			edittype:'select', 
			editoptions: {
				dataUrl:'<?php echo site_url('master/pangkat/geteselon')?>', 
				style:'width:70px;'
			}
		},
		{	name:'gaji_pokok',
			index:'gaji_pokok', 
			width:120, 
			align:"right", 
			editable:true,
			edittype:'text',
			editrules: {number:true},
			formatter: 'currency'
		},
		{	name:'no_sk',
			index:'no_sk', 
			width:120, 
			align:"left", 
			editable:true,
			edittype:'text',
			editoptions: {size:50, maxlenth:50}
		},
		{	name:'tgl_sk',
			index:'tgl_sk', 
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
		{	name:'pejabat_sk',
			index:'pejabat_sk', 
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
	pager: '#pager_jabatan', 
	sortname: 'tgl_mulai', 
	sortorder: "asc", 
	viewrecords: true,
	forceFit : false, 
	width: 750,
	height: 150
}); 
jQuery("#grid_jabatan")
.jqGrid('navGrid', '#pager_jabatan',
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