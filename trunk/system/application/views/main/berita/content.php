<div class="<?php echo $this->css->box();?>" style="padding:10px;">
	<?php
	if(isset($result)) {
		if($this->b->set_content( $result )) 
		{
			$this->b->get_content();
		}
	}
	else {?>
	<h1>Selamat Datang</h1>
	<p>Aplikasi SISTU atau Sistem informasi Tata Usaha adalah aplikasi berbasis web.</p>
	<?php
	}
	?>
</div>