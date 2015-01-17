<?php
echo validation_errors();
echo form_open($mod."/form_office","id='form' class='form' AUTOCOMPLETE='off'")."\n";
?>
<input type='hidden' name='action' id='action' value='save'>
<input type='hidden' name='method' value='/post'>
<input type='hidden' name='module' value='<?php echo $mod?>'>
<input type='text' name='item' style='display:none;'>
<input type='hidden' name='jenis_pungutan' id='jenis_pungutan' value='OFFICE'>
			
<div class='result_msg'></div>
<h1 class='title'>Rekam Setoran Pajak/Retribusi</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<div><label>Tgl. Penerimaan <b class="wajib">*</b> :</label><input type="text" name="tgl_penerimaan" title='Tgl. Penerimaan' id="tgl_penerimaan" value='<?=date('d/m/Y')?>' class="tanggal {required:true,messages:{required:'Tanggal Penerimaan belum dipilih'}}"/></div>
		<div>
			<label>No. Kohir <b class="wajib">*</b> :</label>
			<input type='text' name='no_kohir' id='no_kohir' title='No. Kohir' readonly >
			<input type='hidden' name='id_kohir' id='id_kohir'>
			<input type="button" id="trigger_kohir" size="2" value="...">
		</div>
		<div>
			<label>NPWP/RD :</label>
			<input type='text' name='no_pokok' id='no_pokok' readonly >
			<input type='hidden' name='id_npwp' id='id_npwp'>
			<input type='hidden' name='id_spt' id='id_spt'>
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

<div id="kohirDialog" title="Pilih Kohir">
	<table id="kohirTable" class="scroll"></table>
	<div id="kohirPager" class="scroll"></div>
</div>

<div id="satkerDialog" title="Pilih Satker">
	<table id="satkerTable" class="scroll"></table>
	<div id="satkerPager" class="scroll"></div>
</div>

<script type="text/javascript" src="<?=base_url().$mod?>/js"></script>

<?php
 form_close();
?>