<?php

?>
<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){
	jQuery("#tabs").tabs();
});
</script>
<h1 class='title_form'><?php echo $title_form?></h1>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Formulir Isian</a></li>
		<!--<li><a href="#tabs-2"></a></li>-->
	</ul>
	<div id="tabs-1">
<?php if(isset($form)) echo $form ?>
	</div>
	<!--<div id="tabs-2">
		<div class='form'>
			<table id="daftarTable" class="scroll"></table>
			<div id="daftarPager" class="scroll"></div>
		</div>
	</div>-->
</div>
			
<table id="htmlTable" class="scroll"></table>
<div id="htmlPager" class="scroll"></div>