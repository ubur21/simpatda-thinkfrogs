<?php if(b_logged()){ ?>
<script src="<?php echo base_url()?>assets/script/mootools.js" type="text/javascript"></script>
<link href="<?php echo base_url().$this->config->item('theme_dir').'/'.$this->config->item('theme_menu')?>/menu.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url().$this->config->item('theme_dir').'/'.$this->config->item('theme_menu')?>/menu.js" type="text/javascript"></script>
<script src="<?php echo base_url().$this->config->item('theme_dir').'/'.$this->config->item('theme_menu')?>/index.js" type="text/javascript"></script>
<?php }?>