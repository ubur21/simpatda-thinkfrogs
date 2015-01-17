<table id="grid_peserta"></table>
<div id="pager_peserta"></div>	

<script type="text/javascript">
	jQuery("#grid_peserta").jqGrid({
		 url:'<?php echo site_url('rapat/daftar_peserta')?>',
		 editurl:'<?php echo site_url('rapat/daftar_peserta')?>',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Rapat','Nama','Jabatan','Divisi','Instansi','Email','HP'],
		 colModel :[
			{ name:'id' ,index:'id',search:false },
			{ name:'idfk',index:'idfk',hidden:true },
			{ name:'nama',index:'nama',width:150,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'jabatan',index:'jabatan',width:100,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'divisi',index:'divisi',width:100,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'instansi',index:'instansi',width:100,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'email',index:'email',width:100,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'hp',index:'hp',width:100,editable:true,edittype:'text', editoptions: {size:40} },
		 ],
		pager: jQuery('#pager_peserta'),
		height: 230,
		rowNum:10, 
		rowList:[10,20,30], 
		rownumbers: true,
		multiselect:true,
		multiboxonly: true,
		altRows:true,
		shrinkToFit:false,
		sortname: 'nama_pegawai',
		sortorder: 'asc',
		viewrecords: true,
		caption: '',
		onSelectRow: function(id){ },
        gridComplete: function(){ jQuery("#grid_peserta").setGridWidth(760); return true; }	
		
	}).navGrid(
		'#pager_peserta',
		{ add:true,edit:true,del:true},
		{ width:600,beforeSubmit:func_before,
			/*afterShowForm:function(){
				jQuery('input#nip_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nip')?>/'+this.value); } );
				jQuery('input#nama_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nama/')?>/'+this.value); } );
				return [true]
			},*/				
		}, // edit,afterSubmit:processEdit,bSubmit:"Ubah",bCancel:"Tutup",
		
		{ width:600,beforeSubmit:func_before,
			/*afterShowForm:function(){
				jQuery('input#nip_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nip')?>/'+this.value); } );
				jQuery('input#nama_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nama/')?>/'+this.value); } );
				return [true]
			},*/		
		}, // add,afterSubmit:processAdd,bSubmit:"Tambah",bCancel:"Tutup",,reloadAfterSubmit:false
		
		{ reloadAfterSubmit:false}, // del,afterSubmit:processDelete
		{}
	).hideCol(['id']);
	
	function func_before(a,b){
		a.idfk = $('#parm').val();
		return ['true','true'];
	}
	
</script>