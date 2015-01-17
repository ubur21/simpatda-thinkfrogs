<?php
echo validation_errors();
echo form_open($mod."/form_office","id='form' class='form' AUTOCOMPLETE='off'")."\n";
?>
<input type='hidden' name='action' id='action' value='save'>
<input type='hidden' name='method' value='/post'>
<input type='hidden' name='module' value='<?php echo $mod?>'>
<input type='text' name='item' style='display:none;'>
			
<div class='result_msg'></div>
<h1 class='title'>Rekam Setoran Pajak/Retribusi</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<input type='hidden' name='jenis_pungutan' id='jenis_pungutan' value='SELF'>
		<!--<div><label>Duplikasi Nomor</label><input type='checkbox' name='cnomor' id='cnomor' checked /></div>-->
		<div><label>Tgl. Penerimaan <b class="wajib">*</b> :</label><input type="text" name="tgl_penerimaan" title='Tgl. Penerimaan' id="tgl_penerimaan" value='<?=date('d/m/Y')?>' onchange="" size="10"/></div>
		<!--<div><label>Thn. SPT</label><input type='text' name='thn_spt' id='thn_spt' size='3' value='<?=date('Y')?>'></div>-->
		<div>
			<label>No. Pendataan <b class="wajib">*</b> :</label>
			<input type='text' name='no_spt' id='no_spt' title='No. Pendataan' readonly >
			<input type='hidden' name='id_spt' id='id_spt'>
			<input type="button" id="trigger_spt" size="2" value="...">
		</div>
		<div>
			<label>NPWP/RD :</label>
			<input type='text' name='no_pokok' id='no_pokok' readonly >
			<input type='hidden' name='id_npwp' id='id_npwp'>
			
			<!--<input type="button" id="trigger_npw" size="2" value="...">-->
		</div>								
		<div>
			<label>Kode Dinas <b class="wajib">*</b> :</label>
			<input type='text' name='kode_dinas' id='kode_dinas'  class="{required:true,messages:{required:'Kode Dinas belum dipilih'}}" readonly>
			<input type='hidden' name='id_dinas' id='id_dinas' class="{required:true,messages:{required:'Kode Dinas belum dipilih'}}">
			<input type="button" id="trigger_dinas" size="2" value="...">
		</div>
		<div>
			<label>Nama Dinas :</label>
			<input type='text' name='nama_dinas' id='nama_dinas' title='Nama Dinas' size='38' readonly >
		</div>
		<div>
			<label>Cara Penyetoran <b class="wajib">*</b> :</label>
			<select name='cpenyetoran' id='cpenyetoran' title='Cara Penyetoran' class="{required:true,messages:{required:'Cara Penyetoran belum dipilih'}}">
				<?=getViaPembayaran()?>	
			</select>
		</div>
		<div>
			<label>No. Bukti Bank :</label>
			<input type='text' name='bank_no' id='bank_no'>
		</div>
		<div><label>Keterangan :</label><textarea name='keterangan' id='keterangan' cols='35'></textarea></div>
		
	</div>
	<div class='fd_right'>
		<div><label>Total :</label><input type='text' name='total' id='total' class='currency total' value='0.00' readonly ></div>
		<div><label>Nama WP/WR :</label><input type="text" name="nama_pemohon" id='nama_pemohon' value="" size="25" readonly />
		<input type='hidden' name='id_pemohon' id='id_pemohon'>
		<input type='hidden' name='nominal_pajak' id='nominal_pajak'>
		</div>
		<div><label>Alamat :</label><textarea name="alamat" id='alamat' cols='35' readonly></textarea></div>
		<div><label>Kelurahan :</label><input type="text" name="kelurahan" id='kelurahan' value="" size="25" readonly /></div>
		<div><label>Kecamatan :</label><input type="text" name="kecamatan" id='kecamatan' value="" size="25" readonly /></div>
		<!--<div><label>Kabupaten :</label><input type="text" name="kabupaten" id='kabupaten' value="<?=$kabupaten?>" size="25" readonly /></div>-->		
	</div>
</fieldset>

<div id="sptDialog" title="Pilih Pendataan">
	<table id="sptTable" class="scroll"></table>
	<div id="sptPager" class="scroll"></div>
</div>

<div id="satkerDialog" title="Pilih Satker">
	<table id="satkerTable" class="scroll"></table>
	<div id="satkerPager" class="scroll"></div>
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
 form_close();
?>