<?php
echo validation_errors();
echo form_open($mod."/form","id='form' class='form' AUTOCOMPLETE='off'")."\n";
?>
<input type='hidden' name='action' id='action' value='save'>
<input type='hidden' name='method' value='/post'>
<input type='hidden' name='module' value='<?php echo $mod?>'>
<input type='text' name='item' style='display:none;'>
			
<div class='result_msg'></div>
<!--<h1 class='title'>SPTPD/SPTRD Yang Dikirim</h1>-->
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<div><label>Jenis Report <span class='wajib'>*</span> :</label>
		<select name="jenis_report" title='Jenis Report' id='jenis_report' class="{required:true,messages:{required:'Jenis Report belum dipilih'}}">
			<option value="ALL" selected >Semua Rekening</option>
			<!--<option value="REKENING">Per Jenis Rekening</option>-->
		</select>
		</div>
		<div>
			<label>Tgl Penetapan <span class='wajib'>*</span> :</label>
			<input type='text' name='periode_awal' id='periode_awal' class="tanggal {required:true,messages:{required:'Tgl Awal belum dipilih'}}"> s/d
			<input type='text' name='periode_akhir' id='periode_akhir' class="tanggal {required:true,messages:{required:'Tgl Akhir belum dipilih'}}">
		</div>
		<div><label>Kode Rekening <span class='wajib'>*</span> :</label><input type='text' name='kode_rekening' id='kode_rekening' class="{required:true,messages:{required:'Kode Rekening belum dipilih'}}" disabled ><input type="button" id="trigger_rekening" size="2" value="..." disabled ></div>
		<div><label>Nama Rekening :</label><input type='text' name='nama_rekening' id='nama_rekening' size='40' readonly ></div>
		<div><label>Tgl Cetak <span class='wajib'>*</span> :</label><input type='text' name='tgl_cetak' id='tgl_cetak' value='<?php echo date('d/m/Y')?>' class="tanggal {required:true,messages:{required:'Tgl Cetak belum dipilih'}}" ></div>
	</div>
	<div class='fd_right'>

	</div>
</fieldset>

<div id="rekeningDialog" title="Pilih Rekening">
	<table id="rekeningTable" class="scroll"></table>
	<div id="rekeningPager" class="scroll"></div>
</div>

<script> 

jQuery(document).ready(function(){ 

	jQuery('#btn_print').click(function(){ 
		var tgl_awal = jQuery('#periode_awal').val();
		var tgl_akhir= jQuery('#periode_akhir').val();
		var jenis    = jQuery('#jenis_report').val();
		if(tgl_awal!='' && tgl_akhir!='') 
		fastReportStart('Daftar Penetapan Pajak-Retribusi', 'rpt_monitoring_penetapan', 'pdf', 'TanggalAwal='+tgl_awal+'&TanggalAkhir='+tgl_akhir+'&jenis='+jenis, 1);
	});

});

</script>

<script type="text/javascript" src="<?=base_url().$mod?>/js"></script>

<?php
 if(isset($button_form)) echo $button_form;
 form_close();
?>