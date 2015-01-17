<script src="<?php echo base_url()?>assets/fr/fastreport.js" type="text/javascript" ></script>
<style>
.trigger {
	font-size:12px;
	min-width:300px;
}
.trigger :hover {
	cursor:pointer;	
}
</style>
<table class="layout">
<tr>
	<td><?php $this->load->view('rapat/menu'); ?></td>
	<td>
		<div id="tabs" class="form">
			<ul>
				<li><a href="#tab-rapat">Daftar Data Rapat</a></li>
				<li><a href="#tab-peserta">Daftar Peserta & File Rapat</a></li>
			</ul>
			<div id="tab-rapat">
				<table id='grid_daftar'></table>
				<div id='pager_daftar'></div>
			</div>
			<div id="tab-peserta">
				<!--<table id='grid_peserta'></table>
				<div id='pager_peserta'></div>-->
				<div style='padding:10px'>
					<div class="trigger"><div class="<?php echo $this->css->bar();?>" style="padding:3px;width:760px"><?php echo form_label('Peserta','Peserta'); ?></div></div>
					<div class="toggle_aset_default">
						<fieldset>
							<legend></legend>
							<table id='grid_peserta'></table>
							<div id='pager_peserta'></div>
						</fieldset>
					</div>
					<div class="trigger"><div class="<?php echo $this->css->bar();?>" style="padding:3px;"><?php echo form_label('Upload','Upload'); ?></div></div>
					<div class="toggle_aset">
						<fieldset>
							<legend></legend>
							<table id='grid_upload'></table>
							<div id='pager_upload'></div>
						</fieldset>
					</div>
				</div>				
			</div>			
			<!--<div class="<?php echo $this->css->panel();?>">
				<a id="cetak" class="<?php echo $this->css->button();?>" onclick="func_report()">Cetak Surat Perintah Tugas
					<span class="<?php echo $this->css->iconprint();?>"></span>
				</a>
			</div>-->
		</div>
	</td>
</tr>
</table>
<script type="text/javascript">
	function func_report()
	{
		var id = jQuery('#grid_daftar').getGridParam('selrow');
		if(id){ fastReportStart('Surat Perintah Tugas', 'SPT', 'pdf', 'idspt='+id, 1); }else{ alert('Pilih data terlebih dahulu !'); }
	}

	jQuery(document).ready(function()
	{
	
		$(".toggle_aset").hide();
		$(".trigger").click(function(){ $(this).toggleClass("active").next().slideToggle("high"); });		
		
		$("#tabs").tabs();
		
		jQuery("#grid_daftar").jqGrid({
			url:'<?php echo site_url('rapat/daftar_data_rapat')?>',
			editurl:'<?php echo site_url('rapat/daftar_data_rapat')?>',
			datatype: 'json',
			mtype: 'POST',
			colNames:['id','No. Dokument','Tanggal','Jam','Tema','Lokasi'],
			colModel :[
				{ name:'id' ,index:'id',search:false},
				{ name:'nomor',index:'nomor',width:100},
				{ name:'tanggal',index:'tanggal',width:70,align:'center'},
				{ name:'jam',index:'jam',width:50,align:'center'},
				{ name:'tema',index:'tema',width:150},
				{ name:'lokasi',index:'lokasi',width:120},
			],
			pager: jQuery('#pager_daftar'),
			height:230,
			rowNum:10,
			rownumbers:true,
			rowList:[10,15,30],
			shrinkToFit:false,
			multiselect:true,
			multiboxonly:true,			
			sortname: 'a.nurut',
			sortorder: 'asc',
			viewrecords: true,
			onSelectRow:function(ids){ 
				jQuery("#grid_peserta").jqGrid('setGridParam',{url:"<?php echo site_url('rapat/daftar_peserta')?>/"+ids,page:1}).trigger('reloadGrid');
				jQuery("#grid_peserta").jqGrid('setGridParam',{editurl:"<?php echo site_url('rapat/daftar_peserta')?>/"+ids});
				jQuery("#grid_upload").jqGrid('setGridParam',{url:"<?php echo site_url('rapat/daftar_file')?>/"+ids,page:1}).trigger('reloadGrid');
				jQuery("#grid_upload").jqGrid('setGridParam',{editurl:"<?php echo site_url('rapat/daftar_file')?>/"+ids});
			},
			ondblClickRow: function(id){ 
				var row = jQuery("#grid_daftar").getRowData(id);
				location.href = "<?php echo site_url('rapat/edit_rapat/')."/"; ?>" + id;
			},
			gridComplete: function(){ jQuery("#grid_daftar").setGridWidth(600); return true; }
		}).navGrid('#pager_daftar'
			,{add:false,edit:false,view:false}
			,{height:180, width:450} // edit
			,{height:180, width:450} // add
			,{afterSubmit:afterDelete} // del
			,{} // search        
		).hideCol(['id']);
		
		function afterDelete(response, postdata){
			jQuery('#grid_peserta').trigger('reloadGrid');
			jQuery('#grid_upload').trigger('reloadGrid');
			return [true,'',''];
		}
		
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
			multiboxonly:true,
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
			{ width:600	}, // edit,afterSubmit:processEdit,bSubmit:"Ubah",bCancel:"Tutup",			
			{ width:600 }, // add,afterSubmit:processAdd,bSubmit:"Tambah",bCancel:"Tutup",,reloadAfterSubmit:false			
			{ reloadAfterSubmit:false}, // del,afterSubmit:processDelete
			{}
		).hideCol(['id']);
		
		jQuery("#grid_upload").jqGrid({
			 url:'<?php echo site_url('rapat/daftar_file')?>',
			 editurl:'<?php echo site_url('rapat/daftar_file')?>',
			 datatype: 'json',
			 mtype: 'POST',
			 colNames:['id','Rapat','Nama File'],
			 colModel :[
				{ name:'id' ,index:'id',search:false },
				{ name:'idfk',index:'idfk',hidden:true },
				{ name:'nama',index:'nama',width:450}
			 ],
			pager: jQuery('#pager_upload'),
			height: 230,
			rowNum:5, 
			rowList:[5,10,15], 
			rownumbers: true,
			multiselect:true,
			multiboxonly: true,
			altRows:true,
			shrinkToFit:false,
			sortname: 'nama_file',
			sortorder: 'asc',
			viewrecords: true,
			caption: '',
			onSelectRow: function(id){ },
			ondblClickRow: function(id){ 
				var url = base+'/<?php echo $user_data['module']?>/get_file/'+id;
				window.open(url);
			},
			gridComplete: function(){ jQuery("#grid_upload").setGridWidth(760); return true; }
		}).navGrid(
			'#pager_upload',
			{ add:false,edit:false,del:true},
			{ reloadAfterSubmit:false}, // del,afterSubmit:processDelete
			{}
		).hideCol(['id']);
		
		$("#cetak").hover(
			function() { $(this).addClass("<?php echo $this->css->hover();?>"); },
			function() { $(this).removeClass("<?php echo $this->css->hover();?>"); }
		);
		
		function func_before(){}
		
	})
</script>