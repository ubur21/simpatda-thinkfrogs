<?php

?>
<script>
jQuery(document).ready(function(){
 
	$('#tes').click(function(){
		$('#dialog1').dialog('open');
		//alert('xxx');
	});
	
	$("#dialog1").dialog({
		bgiframe: true,
		resizable: false,
		height:350,
		width:500,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
	});	

});
	
</script>
<h1>Judul</h1>
<div id='dialog1' title='tes'><p>coba coba</p></div>
<input type='button' value='tes' id='tes'>