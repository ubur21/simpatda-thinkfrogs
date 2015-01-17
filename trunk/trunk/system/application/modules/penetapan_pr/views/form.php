<?php
echo validation_errors();
echo form_open($mod."/form","id='form' class='form' AUTOCOMPLETE='off'")."\n";
?>
<input type='hidden' name='action' id='action' value='save'>
<input type='hidden' name='method' value='/post'>
<input type='hidden' name='module' value='<?php echo $mod?>'>
<input type='text' name='item' style='display:none;'>
			
<div class='result_msg'></div>
<h1 class='title'>Form Penetapan SPTPD/RD</h1>
<fieldset class='fieldset' style='width:900px'>
	<div class='fd_left'>
		<!--<div><label>Duplikasi Nomor :</label><input type='checkbox' name='cnomor' id='cnomor' checked /></div>
		<div><label>No. Penetapan :</label><input type="text" name="nomor_reg" id='nomor_reg' value="" readonly/></div>-->
		<div><label>Tgl. Penetapan <span class="wajib">*</span> :</label><input type="text" name="tgl_penetapan" title='Tgl. Penetapan' id="tgl_penetapan" value='<?php echo $tgl_penetapan?>' class="tanggal {required:true,messages:{required:'Tgl Penetapan belum diisi'}}"/></div>
		<div><label>Tgl. Setor <span class="wajib">*</span> :</label><input type="text" name="tgl_setor" title='Tgl. Batas Setor' id="tgl_setor" value='<?php echo $tgl_setor?>' class="tanggal {required:true,messages:{required:'Tgl Penetapan belum diisi'}}"/></div>
		<!--<div><label>Jenis Penetapan</label><select name='jenis_penetapan' id='jenis_penetapan'></select></div>-->
		<!--<div><label>No. Pendataan</label><input type='checkbox' name='cinput' id='cinput' > <input type='text' name='no_data_awal' id='no_data_awal' size='5' readonly /> S/D <input type='text' name='no_data_akhir' id='no_data_akhir' size='5' readonly /></div>-->
		<div><label>Keterangan :</label><textarea name='memo' id='memo' cols='35'></textarea></div>	
	</div>
	<div class='fd_right'>
		<!--<div><label>Total :</label><input type='text' name='total' id='total' class='currency total' value='0.00' readonly ></div>	
		<div><label>Filter Jenis Pendaftaran :</label><select name="jenis_pendaftaran" id='jenis_pendaftaran'><option value=''></option><option value="PAJAK">Pajak</option><option value="RETRIBUSI">Retribusi</option></select></div>
		<div><label>Filter Sistem Pemungutan :</label><select name="pungutan"  id='pungutan'><option value=''></option><option value="SELF">Selft Assesment</option><option value="OFFICE">Office Assesment</option></select></div>
		<div><label>Filter Tanggal Pendataan :</label><input type="text" name="periode_awal" id="periode_awal" onchange="" size="10"/> S/D <input type="text" name="periode_akhir" id="periode_akhir" onchange="" size="10"/></div>		
		-->
	</div>
</fieldset>
<h1 class='title'>Data SPTPD/RD</h1>
<fieldset class='fieldset' style='width:900px'>
	<table id="dataTable" class="scroll"></table>
	<div id="dataPager" class="scroll"></div>
</fieldset>

<div id="npwprdDialog" title="Pilih NPWPD/RD">
	<table id="npwprdTable" class="scroll"></table>
	<div id="npwprdPager" class="scroll"></div>
</div>

<div id="rekeningDialog" title="Pilih Rekening">
	<table id="rekeningTable" class="scroll"></table>
	<div id="rekeningPager" class="scroll"></div>
</div>

<?php if(isset($button_form)) echo $button_form; ?>

<script type="text/javascript" src="<?php echo base_url().$mod?>/js"></script>

<?php
 form_close();
?>