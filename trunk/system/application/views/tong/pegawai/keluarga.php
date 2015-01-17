<table id="grid_keluarga"></table>
<div id="pager_keluarga"></div>	

<script type="text/javascript">
jQuery("#grid_keluarga").jqGrid({ 
	url:'<?php echo site_url('pegawai/get_keluarga').'/'.$id?>', 
	editurl:'<?php echo site_url('pegawai/keluarga/')?>', 
	datatype: "json", 
	mtype: 'POST',
	colNames:['ID', 'ID MASTER', 'Urutan', 'Nama', 'Tempat Lahir', 'Tgl Lahir', 'Gender', 'Pendidikan', 'Pekerjaan', 'Status', 'Tunjangan'], 
	colModel:[ 
		{	name:'id',
			index:'id_keluarga', 
			width:10, 
			hidden:true
		}, 
		{	name:'id_master1',
			index:'id_pegawai', 
			width:10, 
			hidden:true,
			editable:true, 
			edittype:'text',
			editoptions: {defaultValue:<?php echo $id; ?>}
		}, 
		{	name:'urutan',
			index:'urut', 
			width:10, 
			hidden:true
		}, 
		{	name:'nama',
			index:'nama', 
			width:150, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength: 200},
			editrules: {required:true}
		},
		{	name:'tempat_lahir',
			index:'tempat_lahir', 
			width:180, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {size:50, maxlength: 50},
			editrules: {required:false}
		},
		{	name:'tgl_lahir',
			index:'tgl_lahir', 
			width:150, 
			align:"left", 
			editable:true, 
			edittype:'text', 
			editoptions: {
				size:15, maxlength:10,
				dataInit: function(el) {
					$(el).datepicker()
				}
			},
			datefmt: 'dd/mm/yyyy',
			formatter:'date',
			editrules: {required:false, date:true}
		},
		{	name:'jenis_kelamin',
			index:'jenis_kelamin', 
			width:120, 
			align:"left", 
			editable:true,
			edittype:'select',
			editoptions: {
				value:"P:Pria;W:Wanita"
			},
			formatter:'select'
		},
		{	name:'pendidikan',
			index:'pendidikan', 
			width:200, 
			align:"left", 
			editable:true,
			edittype:'select',
			editoptions: {
				dataUrl:'<?php echo site_url('master/pendidikan/getselect')?>', 
				style:'width:200px;'
			}
		},
		{	name:'pekerjaan',
			index:'pekerjaan', 
			width:200, 
			align:"left", 
			editable:true,
			edittype:'text',
			editoptions: {
				size:50, maxlength:200
			}
		},
		{	name:'status',
			index:'status', 
			width:120, 
			align:"center", 
			editable:true,
			edittype:'select',
			editoptions: {
				dataUrl:'<?php echo site_url('master/status_keluarga/getselectv')?>', 
				dataEvents: [
					{	type: 'change',
						fn: function(e) {
							if( $(this).val().toUpperCase() == 'ANAK KANDUNG' || $(this).val().toUpperCase() == 'ISTRI' ) { 
								$("#tr_tunjangan").show();
							}
							else {
								$("#tr_tunjangan").hide();
							}
                        }
					}
				] 
			}
		},
		{	name:'tunjangan',
			index:'tunjangan', 
			width:110, 
			align:"center", 
			editable:true,
			edittype:'checkbox',
			editoptions: {
				value:"1:0"
			},
			formatter:'checkbox'
		}
		
	], 
	rowNum:10, 
	rowList:[10,20,30], 
	rownumbers: true,
	pager: '#pager_keluarga', 
	sortname: 'urutan', 
	sortorder: "asc", 
	viewrecords: true,
	forceFit : false, 
	width: 750,
	height: 150
}); 
jQuery("#grid_keluarga")
.jqGrid('navGrid', '#pager_keluarga',
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