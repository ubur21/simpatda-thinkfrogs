<div class="<?php echo $this->css->box();?>">
	<div class="<?php echo $this->css->bar();?>">Daftar Berita</div>
	<ul>
	<?php
	if(isset($daftar)) {
		if( $this->b->set_daftar($daftar) )
		 {
			$this->b->get_daftar();
		 }
	}
	?>
	</ul>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("a.link").click(function() {
		var id = $(this).attr('id');
		
		$("#cnt-berita").load('<?php echo site_url();?>/main/show_berita/'+id);
	});
});
</script>