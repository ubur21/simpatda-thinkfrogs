<?php
echo validation_errors();
echo form_open($mod."/form","id='form' class='form' AUTOCOMPLETE='off'")."\n";
?>
<input type='hidden' name='method' value='/post'>
<input type='hidden' name='module' value='<?php echo $mod?>'>
<input type='text' name='item' style='display:none;'>
			
<div class='result_msg'></div>
<h1 class='title'>Isian SPTPD Retribusi</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<!--<div><label>No. Reg Form <span class='wajib'>*</span> :</label><input type='text' name='nomor' id='nomor' size='35' value='000000' class="{required:true,messages:{required:'No. SPT belum diisi'}}" readonly>
		</div>-->
		<div><label>Tanggal Proses <span class='wajib'>*</span> :</label><input type='text' name='tgl_proses' id='tgl_proses' value='<?php echo date('d/m/Y')?>' class="tanggal {required:true,messages:{required:'Tgl Proses belum diisi'}}"></div>
		<div><label>Tanggal Entri <span class='wajib'>*</span> :</label><input type='text' name='tgl_entry' id='tgl_entry' value='<?php echo date('d/m/Y')?>' class="tanggal {required:true,messages:{required:'Tgl Entri belum diisi'}}"></div>
		<div><label>NPWPD/RD <span class='wajib'>*</span> :</label>
		<select name='wp_wr_jenis' id='wp_wr_jenis'>
			<!--<option value=''></option>
			<option value="RETRIBUSI" >R</option>-->
			<option value="PAJAK" selected>P</option>
		</select>
		<!--<input type="text" name="wp_wr_gol" id="wp_wr_gol" class="inputbox" size="1" style='width:10px' maxlength="1" value="" autocomplete="off" tabindex="3"/>
		<input type="text" name="wp_wr_no_urut" id="wp_wr_no_urut" class="inputbox" size="7" maxlength="7" value="" autocomplete="off" tabindex="4"/>
		<input type="text" name="wp_wr_kd_camat" id="wp_wr_kd_camat" class="inputbox" size="2" style='width:20px' maxlength="2" value="" autocomplete="off" tabindex="5"/>
		<input type="text" name="wp_wr_kd_lurah" id="wp_wr_kd_lurah" class="inputbox" size="3" maxlength="3" style='width:25px' value="" autocomplete="off" tabindex="6"/>
		-->
		<input type='text' name='npwprd' id='npwprd' class="{required:true,messages:{required:'NPWP/RD belum dipilih'}}" readonly >
		<input type="button" id="trigger_npw" size="2" value="...">
		<input type="hidden" name="pendaftaran_id" id='pendaftaran_id' size="25" readonly />
		</div>		
		<div><label>Memo :</label><textarea cols='35' name='memo' id='memo'></textarea></div>
		<div><label>Nama Restoran :</label><input type='text' name='nama_restoran' id='nama_restoran' ></div>
		<div><label>Alamat Restoran :</label><textarea cols='35' name='alamat_restoran' id='alamat_restoran'></textarea></div>
	</div>
	<div class='fd_right'>
		<div><label>Periode SPT :</label><input type='hidden' name='idspt' id='idspt'><input type='text' name='periode_spt' id='periode_spt' readonly></div>
		<div><label>Tanggal Kirim SPT :</label><input type='text' name='tgl_spt' id='tgl_spt' readonly></div>
		<div><label>No. SPT :</label><input type='text' name='no_spt' id='no_spt' readonly></div>		
		<div><label>Nama NPWPD/RD :</label><input type='text' name='pemohon' id='pemohon' readonly></div>
		<div><label>Alamat :</label><input type='text' name='alamat' id='alamat' readonly></div>
		<div><label>Kecamatan :</label><input type='text' name='kecamatan' id='kecamatan' readonly></div>
		<div><label>Kelurahan :</label><input type='text' name='kelurahan' id='kelurahan' readonly></div>
	</div>
</fieldset>
<h1 class='title'>Perhitungan Retribusi</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<div><label>Sistem Pemungutan <span class='wajib'>*</span> :</label>
			<select name='jenis_pungutan' id='jenis_pungutan' class="{required:true,messages:{required:'Sistem Pemungutan belum dipilih'}}">
				<option value=''></option>
				<option value="SELF" selected>Self Assesment</option>
				<option value="OFFICE" >Office Assesment</option>
			</select>	
		</div>
		<div><label>Periode Penjualan <span class='wajib'>*</span> :</label><input title="periode_awal" type="text"  id="periode_awal" name="periode_awal" value="" class="tanggal {required:true,messages:{required:'Tgl Awal belum dipilih'}}"/><span> s/d </span><input title="periode_akhir" type="text"  id="periode_akhir" name="periode_akhir" value="" class="tanggal {required:true,messages:{required:'Tgl Akhir belum dipilih'}}"/ /></div>
		<div><label>Kode Rekening <span class='wajib'>*</span> :</label><input type='hidden' id='idrekening' name='idrekening'><input type='text' name='kode_rekening' id='kode_rekening' class="{required:true,messages:{required:'Kode Rekening belum dipilih'}}" readonly ><input type="button" id="trigger_rekening" size="2" value="..."></div>
		<div><label>Nama Rekening :</label><input type='text' name='nama_rekening' id='nama_rekening' size='40' readonly ></div>
	</div>
	<div class='fd_right'>
		<div><label>Total :</label><input type='text' name='total' id='total' class='currency total' value='0.00' readonly ></div>
		<div><label>Dasar Pengenaan :</label><input type="text" name="dasar_pengenaan" title='Dasar Pengenaan' id="dasar_pengenaan" style='text-align:right' /></div>
		<div><label>Persen Tarif <b class="wajib">*</b> :</label><input type="text" name="persen_tarif" title='Persen Tarif' id="persen_tarif" style='text-align:right'/>%</div>
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

<script type="text/javascript" src="<?php echo base_url().$mod?>/js"></script>

<?php
 form_close();
?>