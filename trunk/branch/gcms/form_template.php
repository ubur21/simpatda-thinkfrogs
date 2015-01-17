<!-- PEMDA 
<div class="leftSide">
<fieldset class="mainForm">
<label class="leftField"><span>Kode Lokasi</span><input type="text" name="kodeLokasi" value="" size="2" maxlength="2" /></label>
<label class="leftField"><span>Kabupaten/Kota</span><select name="kabKota"><option value="Kabupaten">Kabupaten</option></select></label>
<label class="leftField"><span>Pejabat Kab/Kota</span><select name="pejabat"><option value="Bupati">Bupati</option></select></label>
<label class="leftField"><span>Alamat Kantor</span><textarea name="alamat" col="4" row="3"></textarea></label>
<label class="leftField"><span>Nama Kabupaten/Kota</span><input type="text" name="namaKab" value="" size="10" maxlength="20" /></label>
<label class="leftField"><span>Ibukota Kab/Kota</span><input type="text" name="ibukotaKab" value="" size="10" maxlength="20" /></label>
<label class="leftField"><span>Nomor Telp.Kantor</span><input type="text" name="telp" value="" size="10" maxlength="20" /></label>
</fieldset>
</div>
<div>
<fieldset class="mainForm">
<label class="leftField"><span>Nama Bank</span><input type="text" name="namaBank" value="" size="20" maxlength="20" /></label>
<label class="leftField"><span>Nomor Rekening Bank</span><input type="text" name="noRek" value="" size="20" maxlength="20" /></label>
<label class="leftField"><span>Logo Pemerintah Daerah</span><input type="file" name="logo" /></label>
</fieldset>
</div>
<div id="confirm"><input type="button" name="simpan" value="Simpan" onclick="showForm('confirmDialog');" /></div>
<div id="confirmDialog" class="hidden">
<fieldset class="mainForm">
<label class="leftField"><span>Nama User</span><input type="text" name="namaUser" value="" size="10" maxlength="20" /></label>
<label class="leftField"><span>Password</span><input type="password" name="password" value="" size="10" maxlength="20" />
<input class="closeForm" type="button" name="close" value="Batal" onclick="closeForm('confirmDialog');" /></label>
</fieldset>
</div>
-->
<!--  BIDANG
<div class="leftSide">
<fieldset class="mainForm">
<legend>Detail Bidang</legend>
<label class="leftField"><span>Urusan</span><b class="wajib">*</b><select name="urusan"><option value="urusan">Urusan</option></select>
<label class="leftField"><span>Kode Bidang</span><b class="wajib">*</b><input type="text" name="kdBidang" value="" size="2" maxlength="2" /></label>
<label class="leftField"><span>Nama Bidang</span><b class="wajib">*</b><input type="text" name="nmBidang" value="" size="20" maxlength="100" /></label>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</fieldset>
</div>
-->
<!--  URUSAN
<div class="leftSide">
<fieldset class="mainForm">
<legend>Detail Unit Kerja</legend>
<label class="leftField"><span>Bidang</span><b class="wajib">*</b><select name="bidang"><option value="bidang">Bidang</option></select>
<label class="leftField"><span>Kode Unit Kerja</span><b class="wajib">*</b><input type="text" name="kdUnitKerja" value="" size="2" maxlength="2" /></label>
<label class="leftField"><span>Nama Unit Kerja 1</span><b class="wajib">*</b><input type="text" name="nmUnitKerja[]" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Nama Unit Kerja 2</span><b class="wajib">*</b><input type="text" name="nmUnitKerja[]" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Singkatan</span><b class="wajib">*</b><input type="text" name="singkatan" value="" size="10" maxlength="100" /></label>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</fieldset>
</div>
-->
<!-- KECAMATAN
<div class="leftSide">
<fieldset class="mainForm">
<legend>Detail Kecamatan</legend>
<label class="leftField"><span>Kode Kecamatan</span><b class="wajib">*</b><input type="text" name="kdKecamatan" value="" size="2" maxlength="2" /></label>
<label class="leftField"><span>Nama Kecamatan</span><b class="wajib">*</b><input type="text" name="nmKecamatan" value="" size="20" maxlength="100" /></label>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</fieldset>
</div>
-->
<!-- KELURAHAN
<div class="leftSide">
<fieldset class="mainForm">
<legend>Detail Kelurahan</legend>
<label class="leftField"><span>Kecamatan</span><b class="wajib">*</b><select name="kecamatan"><option value="kecamatan">Kecamatan</option></select>
<label class="leftField"><span>Kode Kelurahan</span><b class="wajib">*</b><input type="text" name="kdKelurahan" value="" size="2" maxlength="2" /></label>
<label class="leftField"><span>Nama Kelurahan</span><b class="wajib">*</b><input type="text" name="nmKelurahan" value="" size="20" maxlength="100" /></label>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</fieldset>
</div>
-->
<!-- TAHUN ANGGARAN
<div class="leftSide">
<fieldset class="mainForm">
<legend>Detail Anggaran</legend>
<label class="leftField"><span>Tahun Anggaran</span><b class="wajib">*</b><input type="text" name="thnAnggaran[]" value="" size="4" maxlength="4" />&nbsp;s/d&nbsp;<input type="text" name="thnAnggaran[]" value="" size="4" maxlength="4" /></label>
<label class="leftField"><span>Status Anggaran</span><select name="statusAnggaran"><option value="Status Anggaran">Status Anggaran</option></select>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</fieldset>
</div>
-->
<!-- KODE REKENING 
<div class="singleSide">
<fieldset class="mainForm">
<legend>Detail Rekening</legend>
<label class="leftField"><span>Kode Rekening</span>Tipe:<b class="wajib">*</b><input type="text" name="noRek[]" value="" size="3" maxlength="3" />&nbsp;Kelompok:<input type="text" name="noRek[]" value="" size="3" maxlength="3" />&nbsp;Jenis:<input type="text" name="noRek[]" value="" size="3" maxlength="3" />&nbsp;Objek:<input type="text" name="noRek[]" value="" size="3" maxlength="3" />&nbsp;Rincian:<input type="text" name="noRek[]" value="" size="3" maxlength="3" /></label>
<label class="leftField"><span>Sub</span>1:<input type="text" name="sub[]" value="" size="3" maxlength="3" />&nbsp;2:<input type="text" name="sub[]" value="" size="3" maxlength="3" />&nbsp;3:<input type="text" name="sub[]" value="" size="3" maxlength="3" /></label>
<label class="leftField"><span>Nama Rekening</span><b class="wajib">*</b><input type="text" name="nmRekening" value="" size="40" maxlength="100" /></label>
<label class="leftField"><span>Kategori Rekening</span><b class="wajib">*</b><select name="katRekening"><option value="Kategori Rekening">Kategori Rekening</option></select></label>
<label class="leftField"><span>% Tarif</span><input type="text" name="tarif" value="" size="5" maxlength="10" /></label>
<label class="leftField"><span>Tarif Dasar</span><input type="text" name="tarifDasar" value="" size="5" maxlength="10" /></label>
<label class="leftField"><span>Volume Dasar</span><input type="text" name="volDasar" value="" size="5" maxlength="10" /></label>
<label class="leftField"><span>Tarif Tambahan</span><input type="text" name="tarifTambahan" value="" size="5" maxlength="10" /></label>
<label class="leftField"><span>Nomor Perda</span><select name="noPerda"><option value="Nomor Perda">Nomor Perda</option></select>
<label class="leftField"><span>Tanggal Perda</span><select name="tglPerda"><option value="Tanggal Perda">Tanggal Perda</option></select>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</fieldset>
</div>
 -->

<div class="leftSide">
<fieldset class="mainForm">
<legend>Detail Pos Anggaran</legend>
<label class="leftField"><span>Kode Posisi</span><b class="wajib">*</b><input type="text" name="kdPosisi" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Nama Posisi</span><b class="wajib">*</b><input type="text" name="nmPosisi" value="" size="20" maxlength="100" /></label>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</fieldset>
</div>

<!-- PEJABAT
<div class="leftSide">
<fieldset class="mainForm">
<legend>Detail Pejabat Daerah</legend>
<label class="leftField"><span>Kode Pejabat&nbsp;<b class="wajib">*</b></span><input type="text" name="kdPejabat" value="" size="2" maxlength="2" /></label>
<label class="leftField"><span>Nama Pejabat&nbsp;<b class="wajib">*</b></span><input type="text" name="nmPejabat" value="" size="40" maxlength="100" /></label>
<label class="leftField"><span>Jabatan&nbsp;<b class="wajib">*</b></span><select name="jabatan"><option value="jabatan">Jabatan</option></select></label>
<label class="leftField"><span>Golongan</span><select name="golongan"><option value="golongan">Golongan</option></select></label>
<label class="leftField"><span>NIP</span><input type="text" name="nip" value="" size="40" maxlength="100" /></label>
<label class="leftField"><span>Pangkat</span><select name="pangkat"><option value="Pangkat">Pangkat</option></select></label>
<label class="leftField"><span>Status&nbsp;<b class="wajib">*</b></span><select name="status"><option value="status">Status</option></select></label>
<label class="leftField"><span>Tanda Tangan</span><select name="tandaTangan"><option value="tandaTangan">TandaTangan</option></select></label>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</fieldset>
</div>
-->
<!-- OPERATOR
<div class="leftSide">
<fieldset class="mainForm">
<label class="leftField"><span>Nama Login&nbsp;<b class="wajib">*</b></span><input type="text" name="nmLogin" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Password&nbsp;<b class="wajib">*</b></span><input type="password" name="passNew[]" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Password Konfirmasi&nbsp;<b class="wajib">*</b></span><input type="password" name="passNew[]" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Password Lama&nbsp;<b class="wajib">*</b></span><input type="password" name="passOld" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Kode&nbsp;<b class="wajib">*</b></span><input type="text" name="kdOperator" value="" size="3" maxlength="5" /></label>
<label class="leftField"><span>Nama Lengkap&nbsp;<b class="wajib">*</b></span><input type="text" name="nmLengkap" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Jabatan&nbsp;<b class="wajib">*</b></span><select name="jabatan"><option value="jabatan">jabatan</option></select></label>
<label class="leftField"><span>Status Aktif&nbsp;<b class="wajib">*</b></span><select name="statusAktif"><option value="statusAktif">Status Aktif</option></select></label>
<label class="leftField"><span>Status Login&nbsp;<b class="wajib">*</b></span><select name="statusLogin"><option value="statusLogin">Status Login</option></select></label>
<label class="leftField"><span>Status Admin&nbsp;<b class="wajib">*</b></span><select name="statusAdmin"><option value="statusAdmin">Status Admin</option></select></label>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</div>
-->
<div class="leftSide">
<fieldset class="mainForm">
<label class="leftField"><span>Nama Printer&nbsp;<b class="wajib">*</b></span><input type="text" name="nmPrinter" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>TTY&nbsp;<b class="wajib">*</b></span><input type="text" name="tty" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Alamat Printer&nbsp;<b class="wajib">*</b></span><input type="text" name="alamatPrinter" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Keterangan&nbsp;<b class="wajib">*</b></span><input type="text" name="keterangan" value="" size="20" maxlength="100" /></label>
<label class="leftField"><span>Default Printer&nbsp;<b class="wajib">*</b></span><select name="defaultPrinter"><option value="printer">Printer</option></select></label>
<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
</div>
