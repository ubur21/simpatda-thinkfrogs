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
		<!--<div><label>No. Penyetoran</label><input type="text" name="nomor_reg" id='nomor_reg' size='10' value="" readonly/></div>-->
		<div><label>Tgl. Penyetoran <b class="wajib">*</b> :</label><input type="text" name="tgl_setor" title='Tgl. Penetapan' id="tgl_setor" value='<?=date('d/m/Y')?>' onchange="" size="10"/></div>
		<div><label>No. Bukti Penyetoran <b class="wajib">*</b> :</label><input type='text' name='no_bukti' id='no_bukti' class="{required:true,messages:{required:'No. Bukti Penyerahan belum diisi'}}"></div>
		<div><label>Keterangan :</label><textarea name='keterangan' id='keterangan' cols='35'></textarea></div>
	</div>
	<div class='fd_right'>
		<div><label>Total :</label><input type='text' name='total' id='total' class='currency total' value='0.00' readonly ></div>
		<input type='hidden' name='nominal_pajak' id='nominal_pajak'>
	</div>
</fieldset>
<h1 class='title'>Data Penerimaan</h1>

<fieldset class='fieldset' style='width:900px'>
	<table id="dataTable" class="scroll"></table>
	<div id="dataPager" class="scroll"></div>
</fieldset>

<div id="pilihDialog" title="Pilih Data Penerimaan">
	<table id="pilihTable" class="scroll"></table>
	<div id="pilihPager" class="scroll"></div>
</div>

<div id="rekeningDialog" title="Pilih Rekening">
	<table id="rekeningTable" class="scroll"></table>
	<div id="rekeningPager" class="scroll"></div>
</div>

<script type="text/javascript" src="<?php echo base_url().$mod?>/js"></script>

<?php
 if(isset($button_form)) echo $button_form;
 form_close();
?>