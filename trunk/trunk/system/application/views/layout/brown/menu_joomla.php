<?php if(b_logged()){ 

$data = load_menus();
$menu = format_menu($data);

?>			
	<div id="header-box">
		<div id="module-status">
			<span class="loggedin-users">Login as <? echo $this->session->userdata('SESS_USER_NAME')?></span>
			<a href='<? echo base_url()?>'><span class="panel">Panel</span></a>
			<a href='<? echo base_url().'/auth/logout'?>'><span class="logout">logout</span></a>
		</div>
		<div id="module-menu">
<?php 		echo $menu;?>
		</div>
		<div class='clr'></div>
	</div>
<?php } ?>