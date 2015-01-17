<?php
echo validation_errors();
echo form_open("np_pribadi/form","id='form' class='form' AUTOCOMPLETE='off'")."\n";
?>
<div class='result_msg'></div>
<input type='hidden' name='objek_pdrd' id='objek_pdrd' value='PRIBADI'>
<h1 class='title_form'><?php echo $title_form?></h1>
<h1 class='title'>Data Pendaftaran</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<div><label>No. Pendaftaran <span class='wajib'>*</span> :</label><input type='text' name='nomor' id='nomor' size='35' value='000000' class="{required:true,messages:{required:'No. Pedaftaran belum diisi'}}" readonly>
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
		<div><label>Tgl Batas Kirim :</label><input type='text' name='tgl_kirim' id='tgl_kirim' class='tanggal'></div>
		<div><label>Tgl Form Diterima WP :</label><input type='text' name='tgl_terima' id='tgl_terima' class='tanggal'></div>
		<div><label>Tgl Form Kembali :</label><input type='text' name='tgl_kembali' id='tgl_kembali' class='tanggal'></div>
		<div><label>Tgl Tutup :</label><input type='text' name='tgl_tutup' id='tgl_tutup' class='tanggal'></div>
	</div>
</fieldset>
<h1 class='title'>Data Pemohon Pribadi</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<div><label>Nama Pemohon <span class='wajib'>*</span> :</label><input type='hidden' name='pemohon' id='pemohon' size='3'><input type='text' id='nama' name='nama' size='35' class="{required:true,messages:{required:'Nama Pemohon belum diisi'}}"></div>
		<div><label>No. KTP <span class='wajib'>*</span> :</label><input type='text' name='no_ktp' id='no_ktp' size='35' class="{required:true,messages:{required:'No. KTP belum diisi'}}"></div>
		<div><label>Tempat/Tgl Lahir <span class='wajib'>*</span> :</label><input type='text' name='tempat_lahir' id='tempat_lahir' class="{required:true,messages:{required:'Tempat Lahir belum diisi'}}"><input type='text' name='tgl_lahir' id='tgl_lahir' class="tanggal {required:true,messages:{required:'Tgl Lahir belum diisi'}}"></div>
		<div><label>Pekerjaan <span class='wajib'>*</span> :</label><input type='text' name='pekerjaan' id='pekerjaan' size='35' class="{required:true,messages:{required:'Pekerjaan belum diisi'}}"></div>
		<div><label>No. Telp <span class='wajib'>*</span> :</label><input type='text' name='telp' id='telp' class="{required:true,messages:{required:'No. Telp belum diisi'}}"></div>
		<div><label>No. HP :</label><input type='text' name='no_hp' id='no_hp'></div>
	</div>
	<div class='fd_right' style='width:450px'>
		<div><label>Alamat <span class='wajib'>*</span> :</label><textarea name='alamat' id='alamat' cols='35' class="{required:true,messages:{required:'Alamat belum diisi'}}"></textarea></div>
		<div><label>Rt/Rw <span class='wajib'>*</span> :</label><input type='text' name='rt' id='rt' size='2' class="{required:true,messages:{required:'Rt belum diisi'}}"> <input type='text' name='rw' id='rw' size='2' class="{required:true,messages:{required:'Rw belum diisi'}}"></div>
		<div><label>Kodepos <span class='wajib'>*</span> :</label><input type='text' name='kodepos' id='kodepos' class="{required:true,messages:{required:'Kodepos belum diisi'}}"></div>
		<div><label>Desa/Kelurahan <span class='wajib'>*</span> :</label>
		<input type='hidden' size='3' id='iddesa' name='iddesa' class="{required:true,messages:{required:'Desa/Kelurahan tidak ada disistem'}}">
		<input type='text' name='desa' id='desa' size='35' class="{required:true,messages:{required:'Desa/Kelurahan belum diisi'}}"></div>
	</div>
</fieldset>

<?php
 if(isset($button_form)) echo $button_form;
 
 form_close();
?>