<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->config->item('aplication_name')?></title>
<link href="<?php echo base_url().$this->config->item('theme_dir')?>/css/style.css" rel="stylesheet" type="text/css"/>
<?php $this->load->view($this->config->item('layout_dir').'/cssjs_'.$this->config->item('theme_menu')); ?>
<script src="<?php echo base_url()?>assets/script/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url()?>assets/script/lib.js" type="text/javascript" charset="utf-8"></script>
<!--<link href="<?php echo base_url()?>assets/css/ui-lightness/jquery-ui.custom.css" rel="stylesheet" type="text/css"/>-->
<link href="<?php echo base_url()?>assets/css/redmond/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url()?>assets/css/jqgrid/ui.jqgrid.css" rel="stylesheet" type="text/css" media="all" />
<script src="<?php echo base_url()?>assets/script/jqgrid/grid.locale-id.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url()?>assets/script/jqgrid/jquery.jqGrid.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url()?>assets/script/jquery/jquery-ui.custom.min.js" type="text/javascript" ></script>
<script src="<?php echo base_url()?>assets/script/jquery/ui.datepicker-id.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url()?>assets/script/jquery/jquery.form.js" type="text/javascript" charset="utf-8"></script>
<?php $this->external->run(); ?>
<script>
jQuery.noConflict()
jQuery(document).ready(function(){ 
	jQuery('.result_msg').click(function(){
		jQuery(this).fadeOut("slow");
	});
});
</script>
<script type="text/javascript">
	//prepare appearance
	var root = '<?php echo base_url()?>';
	var mod = '<?php if (isset($mod)) echo $mod; else if (isset($current_tab)) echo $current_tab; ?>';
</script>
</head>
<body>
<div id='lock_screen'></div>
<div class="container">
	<div id="header">
		<div id="header-center">
			<div id="header-left">
				<div id="header-right"><a href='<?php echo base_url()?>'><? echo $this->config->item('aplication_name')?></a></div>
			</div>
		</div>
	</div>
	
<?php $this->load->view($this->config->item('layout_dir').'/'.$this->config->item('theme_menu')); ?>
