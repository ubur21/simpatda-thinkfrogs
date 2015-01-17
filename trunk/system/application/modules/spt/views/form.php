<?php
echo validation_errors();
echo form_open($mod."/form","id='form' class='form' AUTOCOMPLETE='off'")."\n";
?>
<input type='hidden' name='method' value='/post'>
<input type='hidden' name='module' value='<?php echo $mod?>'>
<input type='text' name='item' style='display:none;'>
			
<div class='result_msg'></div>
<h1 class='title'>SPTPD/SPTRD Yang Dikirim</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<div><label>No. SPT <span class='wajib'>*</span> :</label><input type='text' name='nomor' id='nomor' size='35' value='000000' class="{required:true,messages:{required:'No. SPT belum diisi'}}" readonly>
		</div>
		<div><label>Sistem Pemungutan <span class='wajib'>*</span> :</label>
		<select name="jenis_pungutan" title='Sistem Pemungutan' id='jenis_pungutan' class="{required:true,messages:{required:'Sistem Pemungutan belum dipilih'}}">
			<option value=''></option>
			<option value="SELF">Selft Assesment</option>
			<option value="OFFICE">Office Assesment</option>
		</select>
		</div>
		<div><label>NPWPD/RD <span class='wajib'>*</span> :</label>
		<select name='wp_wr_jenis' id='wp_wr_jenis'>
			<option value=''></option>
			<option value="PAJAK" selected>P</option>
			<option value="RETRIBUSI" >R</option>
		</select>
		<!--<input type="text" name="wp_wr_gol" id="wp_wr_gol" class="inputbox" size="1" style='width:10px' maxlength="1" value="" autocomplete="off" tabindex="3"/>
		<input type="text" name="wp_wr_no_urut" id="wp_wr_no_urut" class="inputbox" size="7" maxlength="7" value="" autocomplete="off" tabindex="4"/>
		<input type="text" name="wp_wr_kd_camat" id="wp_wr_kd_camat" class="inputbox" size="2" style='width:20px' maxlength="2" value="" autocomplete="off" tabindex="5"/>
		<input type="text" name="wp_wr_kd_lurah" id="wp_wr_kd_lurah" class="inputbox" size="3" maxlength="3" style='width:25px' value="" autocomplete="off" tabindex="6"/>
		-->
		<input type='text' name='npwprd' id='npwprd' class="{required:true,messages:{required:'NPWP/RD belum dipilih'}}" readonly >
		<input type="hidden" name="pendaftaran_id" id='pendaftaran_id' size="25" readonly />
		<input type="button" id="trigger_npw" size="2" value="...">
		</div>
		<div><label>Nama NPWPD/RD :</label><input type='text' name='pemohon' id='pemohon' readonly></div>
		<div><label>Alamat :</label><input type='text' name='alamat' id='alamat' readonly></div>
		<div><label>Kecamatan :</label><input type='text' name='kecamatan' id='kecamatan' readonly></div>
		<div><label>Kelurahan :</label><input type='text' name='kelurahan' id='kelurahan' readonly></div>
		
	</div>
	<div class='fd_right'>
		<div><label>Tanggal SPT <span class='wajib'>*</span> :</label><input type='text' name='tgl_spt' id='tgl_spt' value='<?php echo date('d/m/Y')?>' class="tanggal {required:true,messages:{required:'Tgl SPT belum diisi'}}"></div>
		<div><label>Tanggal Kembali <span class='wajib'>*</span> :</label><input type='text' name='tgl_kembali' id='tgl_kembali' class="tanggal {required:true,messages:{required:'Tgl Kembali belum diisi'}}"></div>
		<div><label>Kode Rekening <span class='wajib'>*</span> :</label><input type='text' name='kode_rekening' id='kode_rekening' class="{required:true,messages:{required:'Kode Rekening belum dipilih'}}"><input type="button" id="trigger_rekening" size="2" value="..."></div>
		<div><label>Nama Rekening :</label><input type='text' name='nama_rekening' id='nama_rekening' size='40' readonly ></div>
		<div><label>Nama Penerima <span class='wajib'>*</span> :</label><input type='text' name='nama_penerima' id='nama_penerima' class="{required:true,messages:{required:'Nama Penerima belum diisi'}}"></div>
		<div><label>Alamat Penerima <span class='wajib'>*</span> :</label><input type='text' name='alamat_penerima' id='alamat_penerima' class="{required:true,messages:{required:'Alamat Penerima belum diisi'}}"></div>
		<div><label>Memo :</label><textarea cols='35' name='memo' id='memo'></textarea></div>
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

<script type="text/javascript" src="<?=base_url().$mod?>/js"></script>

<?php
 form_close();
?>