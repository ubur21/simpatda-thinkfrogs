<script src="<?php echo base_url()?>assets/fr/fastreport.js" type="text/javascript" ></script>
<table class="layout">
<tr>
	<td><?php $this->load->view('sppd/menu'); ?></td>
	<td>
		<div>
			<table id='grid_daftar'></table>
			<div id='pager_daftar'></div>
			<div class="<?php echo $this->css->panel();?>">
				<a id="cetak" class="<?php echo $this->css->button();?>" onclick="func_report()">Cetak Surat Perintah Tugas
					<span class="<?php echo $this->css->iconprint();?>"></span>
				</a>
			</div>
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

	jQuery(document).ready(function(){

		jQuery("#grid_daftar").jqGrid({
			url:'<?php echo site_url('sppd/daftar_data_spt')?>',
			editurl:'<?php echo site_url('sppd/daftar_data_spt')?>',
			datatype: 'json',
			mtype: 'POST',
			colNames:['id','No. SPT','Tanggal','Maksud Penugasan','Dari - Tujuan','Tgl. Perjalanan','Kendaraan','Nama Pemberi','nb','Nama Penerima','np'],
			colModel :[
				{ name:'id' ,index:'id',search:false},
				{ name:'nomor',index:'nomor',width:80},
				{ name:'tanggal',index:'tanggal',width:70},
				{ name:'maksud',index:'maksud',width:120},
				{ name:'tujuan',index:'tujuan',width:150},
				{ name:'pulang',index:'pulang',width:140},
				{ name:'kendaraan',index:'kendaraan',width:100},
				{ name:'pemberi',index:'pemberi',width:135},
				{ name:'idpemberi',index:'idpemberi',hidden:true},
				{ name:'penerima',index:'penerima',width:135},
				{ name:'idpenerima',index:'idpenerima',hidden:true},
			],
			pager: jQuery('#pager_daftar'),
			height:230,
			width:600,
			rowNum:10,
			rownumbers:true,
			rowList:[10,15,30],
			shrinkToFit:false,
			sortname: 'a.nurut',
			sortorder: 'asc',
			viewrecords: true,
			caption:"Daftar Surat Perintah Tugas",
			onSelectRow:function(id){ },
			ondblClickRow: function(id){ 
				var row = jQuery("#grid_daftar").getRowData(id);
				$.get('<?php echo site_url('sppd/spt_editable')?>/'+id,function(result){
					if(result=='0') location.href = "<?php echo site_url('sppd/edit_spt/')."/"; ?>" + id;
					else $.gritter.add({title:'SPT No. '+row.nomor, text:'Tidak bisa diedit'});
				});	
			},
			gridComplete: function(){ jQuery("#grid_daftar").setGridWidth(980); return true; }
		}).navGrid('#pager_daftar'
			,{add:false,edit:false,view:false}
			,{height:180, width:450} // edit
			,{height:180, width:450} // add
			,{} // del
			,{} // search        
		).hideCol(['id']);
		
		$("#cetak").hover(
			function() { $(this).addClass("<?php echo $this->css->hover();?>"); },
			function() { $(this).removeClass("<?php echo $this->css->hover();?>"); }
		);
	})
</script>