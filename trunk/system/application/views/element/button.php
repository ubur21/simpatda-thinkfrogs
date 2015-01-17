<div class='div_button'>
	<?php //if(isset($is_filter)) echo '<div class="btn"><a href="#" id="btn_filter"><label class="filter"></label>filter</a><span></span></div>'; ?>
	<?php if(isset($is_save)) echo '<div class="btn"><a href="#" id="btn_save"><label class="save"></label>simpan</a><span></span></div>'; ?>
	<?php if(isset($is_cancel)) echo '<div class="btn"><a href="#" id="btn_cancel"><label class="cancel"></label>batal</a><span></span></div>'; ?>
	<?php if(isset($is_print)) echo '<div class="btn"><a href="#" id="btn_print"><label class="print"></label>cetak</a><span></span></div>'; ?>
	<input type='text' name='action' id='action' value='save' style='display:none'>
	<input type='hidden' id='idmasters' name='idmasters' value=''>
	<div id='extra_element_form'></div>
</div>
