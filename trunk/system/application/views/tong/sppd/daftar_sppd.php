<script src="<?php echo base_url()?>assets/fr/fastreport.js" type="text/javascript" ></script>
<table class="layout">
<tr>
	<td><?php $this->load->view('sppd/menu'); ?></td>
	<td>
		<div>
			<table id='grid_daftar'></table>
			<div id='pager_daftar'></div>
			<div class="<?php echo $this->css->panel();?>">
				<a id="cetak" class="<?php echo $this->css->button();?>" onclick="print_sppd()">Cetak SPPD
					<span class="<?php echo $this->css->iconprint();?>"></span>
				</a>
				<a id="cetak" class="<?php echo $this->css->button();?>" onclick="print_kwitansi()">Cetak Kuitansi
					<span class="<?php echo $this->css->iconprint();?>"></span>
				</a>				
			</div>
		</div>
	</td>
</tr>
</table>
<div id="dialog" title="Pilih Peserta" style='display:none'>
	<select size="5" multiple='true' style='width:100%' id='peserta'>
		<option value='all' selected>Pilih Semua</option>
		<option value=''>Bambang</option>
		<option value=''>Anto</option>
		<option value=''>Banu Araidi</option>
		<option value=''>Bambang</option>
		<option value=''>Anto</option>		
	</select>
	<div style='float:right;padding:5px 0px;'>
		<input type='button' id='btn_pilih' value='Cetak'>
		<input type='button' id='btn_tutup' value='Tutup'>
	</div>
</div>

<script type="text/javascript">
	function print_sppd()
	{
		var id = jQuery('#grid_daftar').getGridParam('selrow');
		if(id){ fastReportStart('SPPD', 'SPPD', 'pdf', 'id='+id, 1); }else{ alert('Pilih data terlebih dahulu !'); }
	}
	
	function print_kwitansi()
	{
		var id  = jQuery('#grid_daftar').getGridParam('selrow')
		if(id){
			var row = jQuery("#grid_daftar").getRowData(id);			
			var data = {}; data.spt = row.spt;
			$('select#peserta').empty("");
			$.post('<?php echo site_url('sppd/get_peserta')?>', data ,function(result){
				$.each(result, function(val, text){
					$('select#peserta').append( new Option(text.peserta,text.id,text.selected) );
				});
			},'json');	
			$('#dialog').dialog('open');
		}
	}

	jQuery(document).ready(function(){
	
		$("#dialog").dialog({
			bgiframe: true, resizable: false, height:180, width:550, modal: true, autoOpen: false,
			//buttons: { 'Tutup': function() { $(this).dialog('close'); } }
		});
		
		$('#btn_tutup').click(function(){ $('#dialog').dialog('close') });
		
		$('#btn_pilih').click(function(){
			var ids = $('select#peserta').val();
			var id  = jQuery('#grid_daftar').getGridParam('selrow');
			if(ids){ fastReportStart('Kwitansi', 'BuktiKas', 'pdf', 'id='+id+'&id_pengikut='+ids, 1); }else{ alert('Pilih data terlebih dahulu !') };
			
		});
	
		jQuery("#grid_daftar").jqGrid({
			url:'<?php echo site_url('sppd/daftar_data_sppd')?>',
			editurl:'<?php echo site_url('sppd/daftar_data_sppd')?>',
			datatype: 'json',
			mtype: 'POST',
			colNames:['id','SPT','No. SPPD','Tgl. SPPD','No. SPT','Tgl. SPT','Biaya','Kode Rekening','Maksud Penugasan','Dari - Tujuan','Tgl. Perjalanan','Nama Pemberi','Nama Penerima','Pasal'], //,'Kendaraan'
			colModel :[
				{ name:'id' ,index:'id',search:false},
				{ name:'spt' ,index:'spt',hidden:true},
				{ name:'nomor',index:'nomor',width:110},
				{ name:'tanggal',index:'tanggal',width:70},
				{ name:'spt_no',index:'spt_no',width:100},
				{ name:'tgl_spt',index:'tgl_spt',width:70},
				{ name:'biaya',index:'biaya',width:150,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}},
				{ name:'kode_rekening',index:'kode_rekening',width:110},
				{ name:'maksud',index:'maksud',width:120},
				{ name:'tujuan',index:'tujuan',width:150},
				{ name:'pulang',index:'pulang',width:140},
				//{ name:'kendaraan',index:'kendaraan',width:100},
				{ name:'pemberi',index:'pemberi',width:135},
				{ name:'penerima',index:'penerima',width:135},
				{ name:'pasal',index:'pasal',hidden:true}

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
			caption:"Daftar Surat Perintah Perjalanan Dinas",
			onSelectRow:function(id){ 
				/*if(id && id!==lastsel3){
					var row = jQuery("#grid_daftar").getRowData(id);
					$('#spt_no').val(row.spt);
					$('#sppd_no').val(row.nomor); $('#tanggal_sppd').val(row.tanggal); $('#parm').val(row.id);
					$('#tanggal_spt').val(row.tgl_spt);
					$('#maksud').val(row.maksud); $('#dari').val(row.dari); $('#tujuan').val(row.tujuan);
					$('#dari_tanggal').val(row.berangkat); $('#sd_tanggal').val(row.pulang); $('#kendaraan').val(row.kendaraan);
					$('#pejabat_pemberi').val(row.pemberi); $('#pegawai_penerima').val(row.penerima); $('#edit').val('true');
					$('#kode_rekening').val(row.kode_rekening); $('#pasal_anggaran').val(row.pasal);
					$('#total_biaya').val(row.biaya);
					//jQuery("#tabs").tabs('select', 0);
					lastsel3=id;
				}*/
			},
			ondblClickRow: function(id){ 
				location.href = "<?php echo site_url('sppd/edit_sppd/')."/"; ?>" + id;
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
			function() {
				$(this).addClass("<?php echo $this->css->hover();?>");
			},
			function() {
				$(this).removeClass("<?php echo $this->css->hover();?>");
			}
		);
	})
</script>