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
		<!--<div>
			<label>Tgl Penetapan <span class='wajib'>*</span> :</label>
			<input type='text' name='periode_awal' id='periode_awal' class="tanggal {required:true,messages:{required:'Tgl Awal belum dipilih'}}"> s/d
			<input type='text' name='periode_akhir' id='periode_akhir' class="tanggal {required:true,messages:{required:'Tgl Akhir belum dipilih'}}">
		</div>-->
		<div><label>No. Kohir <span class='wajib'>*</span> :</label>
		<input type='text' name='kohir_awal' id='kohir_awal' class="tanggal {required:true,messages:{required:'Kohir Awal belum dipilih'}}" ><input type="button" id="trigger_kohir1" size="2" value="..."> s/d 
		<input type='text' name='kohir_akhir' id='kohir_akhir' class="tanggal {required:true,messages:{required:'Kohir Akhir belum dipilih'}}" ><input type="button" id="trigger_kohir2" size="2" value="...">
		</div>
	</div>
	<div class='fd_right'>

	</div>
</fieldset>

<div id="rekeningDialog" title="Pilih Rekening">
	<table id="rekeningTable" class="scroll"></table>
	<div id="rekeningPager" class="scroll"></div>
</div>

<div id="kohirDialog" title="Pilih Kohir">
	<table id="kohirTable" class="scroll"></table>
	<div id="kohirPager" class="scroll"></div>
</div>

<script> 

jQuery(document).ready(function(){
	jQuery('#btn_print').click(function(){ 
		var kohir1 = jQuery('#kohir_awal').val();
		var kohir2 = jQuery('#kohir_akhir').val();
		if(kohir1!='' && kohir2!=''){
			jQuery('#btn_print').click(function(){ 
				fastReportStart('Surat Ketetapan', 'rpt_penetapan', 'pdf', 'kohir1='+kohir1+'&kohir2='+kohir2, 1);
			});
		}
	});
});

</script>

<script type="text/javascript" src="<?=base_url().$mod?>/js"></script>

<?php
 if(isset($button_form)) echo $button_form;
 form_close();
?>