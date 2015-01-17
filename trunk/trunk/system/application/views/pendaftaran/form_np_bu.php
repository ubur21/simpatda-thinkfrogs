<?php
echo validation_errors();
echo form_open("np_bu/form","id='form' name='form' class='form' AUTOCOMPLETE='off'")."\n";
?>
<div class='result_msg'></div>
<input type='hidden' name='objek_pdrd' id='objek_pdrd' value='BU'>
<h1 class='title_form'><?php echo $title_form?></h1>
<h1 class='title'>Data Pendaftaran</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<div><label>No. Pendaftaran <span class='wajib'>*</span> :</label><input type='text' name='nomor' id='nomor' value='000000' size='35' class="{required:true,messages:{required:'No. Pendaftaran belum terisi'}}" readonly>
		</div>
		<div><label>Jenis Pendaftaran <span class='wajib'>*</span> :</label>
		<!--<select name='jenis_daftar' id='jenis_daftar' onchange='setNomor()' class="{required:true,messages:{required:'Jenis Pendaftaran belum dipilih'}}"><option value=''></option><option value='PAJAK'>PAJAK</option><option value='RETRIBUSI'>RETRIBUSI</option></select>-->
		<!--<input type='text' name='jenis_daftar' id='jenis_daftar' onblur='setNomor()' class="{required:true,messages:{required:'Jenis Pendaftaran belum dipilih'}}">-->
		<input type='radio' name='jenis_daftar' id='ck_pajak' value='PAJAK' >PAJAK <input type='radio' name='jenis_daftar' id='ck_retribusi' value='RETRIBUSI'>RETRIBUSI
		</div>
		<div><label>Kode Usaha <span class='wajib'>*</span> :</label>
		<select name='kode_usaha' id='kode_usaha' class="{required:true,messages:{required:'Jenis Usaha belum dipilih'}}">
		<option value=''></option>
		<?php echo $data_form['opt_kode_usaha']?>
		</select>
		</div>
		<div><label>Memo :</label><textarea name='memo' id='memo' cols='35'></textarea></div>
	</div>
	<div class='fd_right'>
		<div><label>Tgl Kartu NPWP/RD <span class='wajib'>*</span> :</label><input type='text' name='tgl_kartu' id='tgl_kartu' value='<?php echo date('d/m/Y')?>' class="tanggal {required:true,messages:{required:'Tgl Kartu belum diisi'}}"></div>
		<div><label>Tgl Batas Kirim :</label><input type='text' name='tgl_kirim' id='tgl_kirim' class="tanggal"></div>
		<div><label>Tgl Form Diterima WP :</label><input type='text' name='tgl_terima' id='tgl_terima' class="tanggal"></div>
		<div><label>Tgl Form Kembali :</label><input type='text' name='tgl_kembali' id='tgl_kembali' class="tanggal"></div>
		<div><label>Tgl Tutup :</label><input type='text' name='tgl_tutup' id='tgl_tutup' class="tanggal"></div>
	</div>
</fieldset>
<h1 class='title'>Data Badan Usaha</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<div><label>Nama Badan Usaha <span class='wajib'>*</span> :</label>
		<input type='hidden' name='pemohon' id='pemohon' size='3'>
		<input class='{required:true,messages:{required:"Nama Badan Usaha belum diisi"}}' type='text' id='nama_bu' name='nama_bu' size='35'></div>
		<div><label>Tipe <span class='wajib'>*</span> :</label>
		<!--<input class="{required:true,messages:{required:'Tipe belum dipilih'}}" type='text' name='tipe_bu' id='tipe_bu' size='35'>-->
		<select name='tipe_bu' id='tipe_bu' class="{required:true,messages:{required:'Tipe belum dipilih'}}">
			<option value=''></option>
			<option value='PT'>PT</option>
			<option value='CV'>CV</option>
			<option value='KOPERASI'>KOPERASI</option>
		</select>
		</div>
		<div><label>No. Telp <span class='wajib'>*</span> :</label><input type='text' name='telp_bu' id='telp_bu' class='{required:true,messages:{required:"No. Telp belum diisi"}}'></div>
		<div><label>No. Fax :</label><input type='text' name='fax_bu' id='fax_bu'></div>
		<!--<div><label>No. NPWP :</label><input type='text' name='npwp_bu' id='npwp_bu'></div>-->
	</div>
	<div class='fd_right' style='width:450px'>
		<div><label>Alamat <span class='wajib'>*</span> :</label><textarea class="{required:true,messages:{required:'Alamat belum diisi'}}" name='alamat_bu' id='alamat_bu' cols='35'></textarea></div>
		<div><label>Kodepos <span class='wajib'>*</span> :</label><input  class="{required:true,messages:{required:'Kodepos belum diisi'}}" type='text' name='kodepos_bu' id='kodepos_bu'></div>
		<!--<div><label>Desa/Kelurahan <span class='wajib'>*</span> :</label><input class="{required:true,messages:{required:'Desa/Kelurahan belum diisi'}}"  type='text' name='desa_bu' id='desa_bu'></div>-->
	</div>
</fieldset>
<h1 class='title'>Data Pemilik Badan Usaha</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<div><label>Nama Pemilik <span class='wajib'>*</span> :</label><input class="{required:true,messages:{required:'Nama Pemilik belum diisi'}}" type='text' id='pemilik_nama' name='pemilik_nama' size='35'></div>
		<div><label>Tempat/Tgl Lahir <span class='wajib'>*</span> :</label><input class="{required:true,messages:{required:'Tempat Lahir belum diisi'}}" type='text' name='pemilik_tmp_lahir' id='pemilik_tmp_lahir'>
		<input type='text' class="{required:true,messages:{required:'Tanggal Lahir belum diisi'}} tanggal" name='pemilik_tgl_lahir' id='pemilik_tgl_lahir'></div>
		<div><label>No. KTP <span class='wajib'>*</span> :</label><input class="{required:true,messages:{required:'No. KTP belum diisi'}}" type='text' name='pemilik_no_ktp' id='pemilik_no_ktp' size='35'></div>
		<div><label>No. NPWP <span class='wajib'>*</span> :</label><input class="{required:true,messages:{required:'No. NPWP belum diisi'}}" type='text' name='pemilik_npwp' id='pemilik_npwp' size='35'></div>		
		<div><label>No. Telp :</label><input type='text' name='pemilik_telp' id='pemilik_telp'></div>
		<div><label>No. HP :</label><input type='text' name='pemilik_hp' id='pemilik_hp'></div>
	</div>
	<div class='fd_right' style='width:450px'>
		<div><label>Alamat <span class='wajib'>*</span> :</label><textarea class="{required:true,messages:{required:'Alamat belum diisi'}}" name='pemilik_alamat' id='pemilik_alamat' cols='35'></textarea></div>
		<div><label>Rt/Rw <span class='wajib'>*</span> :</label><input class="{required:true,messages:{required:'Rt belum diisi'}}" type='text' name='pemilik_rt' id='pemilik_rt' size='2'> <input class="{required:true,messages:{required:'Rw belum diisi'}}" type='text' name='pemilik_rw' id='pemilik_rw' size='2'></div>		
		<div><label>Kodepos <span class='wajib'>*</span> :</label><input class="{required:true,messages:{required:'Kodepos belum diisi'}}" type='text' name='pemilik_kodepos' id='pemilik_kodepos'></div>
		<div><label>Desa/Kelurahan <span class='wajib'>*</span> :</label>
		<input type='hidden' name='pemilik_id_desa' id='pemilik_id_desa' size='3'>
		<input class="{required:true,messages:{required:'Desa/Kelurahan belum diisi'}}" type='text' name='desa_pemilik' id='desa_pemilik'></div>
	</div>
</fieldset>

<?php 
	if(isset($button_form)) echo $button_form;
	form_close(); 
?>
