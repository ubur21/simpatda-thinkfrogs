<?php $this->load->view($this->config->item('layout_dir').'/header'); ?>

	<div id="content-box">
		<div class='border'>
			<div class="padding">
			
<?php if(isset($main_content)) echo $main_content ; ?>

			</div>
			<div class="clr"></div>
		</div>
		<div class="b">
			<div class="b">
				<div class="b"></div>
			</div>
		</div>
			
	</div>
	
<?php $this->load->view($this->config->item('layout_dir').'/footer'); ?>






