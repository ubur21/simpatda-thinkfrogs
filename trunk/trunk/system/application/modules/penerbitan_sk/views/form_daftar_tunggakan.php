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
		<div><label>Tanggal Cetak <span class='wajib'>*</span> :</label><input type='text' name='tgl_cetak' id='tgl_cetak' value='<?php echo date('d/m/Y')?>' class="tanggal {required:true,messages:{required:'Tgl Cetak belum diisi'}}"></div>
		<!--<div><label>Dari NPDPW/RD <span class='wajib'>*</span> :</label><input type='text' name='npw_awal' id='npw_awal' value='' class="{required:true,messages:{required:'NPWP/RD Aawal belum diisi'}}"></div>
		<div><label>NPDPW/RD <span class='wajib'>*</span> :</label><input type='text' name='npw_awal' id='npw_awal' value='' class="{required:true,messages:{required:'NPWP/RD Aawal belum diisi'}}"></div>
		<div><label>Kode Rekening <span class='wajib'>*</span> :</label><input type='text' name='kode_rekening' id='kode_rekening' class="{required:true,messages:{required:'Kode Rekening belum dipilih'}}" disabled ><input type="button" id="trigger_rekening" size="2" value="..." disabled ></div>
		<div><label>Nama Rekening :</label><input type='text' name='nama_rekening' id='nama_rekening' size='40' readonly ></div>-->
		<div><label>Tgl Penetapan <span class='wajib'>*</span> :</label><input type='text' name='periode_awal' id='periode_awal' class="tanggal {required:true,messages:{required:'Tgl Masa Pajak Awal belum diisi'}}"> s/d 
		<input type='text' name='periode_akhir' id='periode_akhir' class="tanggal {required:true,messages:{required:'Tgl Masa Pajak Akhir belum diisi'}}">
		</div>
		<div><label>Nama Petugas</label><input type='hidden' name='iduser' id='iduser' value='<?php echo $this->session->userdata('SESS_USER_ID')?>'><input type='text' name='nama_ptgs' id='nama_ptgs' value='<?php echo $nama_ptgs ?>' readonly></div>
		<div><label>Jabatan</label><input type='text' name='jabatan' id='jabatan' size='30' value='<?php echo $jabatan?>' readonly></div>
		<div><label>NIP</label><input type='text' name='nip' id='nip' value='<?php echo $nip?>' readonly></div>
		
	</div>
	<div class='fd_right'>
		
	</div>
</fieldset>

<div id="npwprdDialog" title="Pilih NPWPD/RD">
	<table id="npwprdTable" class="scroll"></table>
	<div id="npwprdPager" class="scroll"></div>
</div>

<div id="rekeningDialog" title="Pilih Rekening">
	<table id="rekeningTable" class="scroll"></table>
	<div id="rekeningPager" class="scroll"></div>
</div>

<script> 

jQuery(document).ready(function(){

	jQuery('#btn_print').click(function(){ 

		var tgl_awal = jQuery('#periode_awal').val();
		var tgl_akhir= jQuery('#periode_akhir').val();
		var tgl_cetak= jQuery('#tgl_cetak').val();		
		var user     = jQuery('#iduser').val();
		
		if(tgl_awal!='' && tgl_akhir!='' && tgl_cetak!=''){
			fastReportStart('Daftar Tunggakan', 'rpt_tunggakan', 'pdf', 'user='+user+'&TanggalAwal='+tgl_awal+'&TanggalAkhir='+tgl_akhir+'&tglcetak='+tgl_cetak, 1);
		}
	});
});

</script>

<script type="text/javascript" src="<?=base_url().$mod?>/js"></script>

<?php
 if(isset($button_form)) echo $button_form;
 form_close();
?>