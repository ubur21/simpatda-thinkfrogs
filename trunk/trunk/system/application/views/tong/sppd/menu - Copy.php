<?php
$current_link=isset($current_link) ? $current_link:'index';
$menus=array('SPT','SPPD','Pembayaran');
$link=array('spt','index','pembayaran');
?>
<div class="west" style='margin-top:10px;margin-left:5px'>
	<ul class="nav">
		<div class="nav-bottom-left">
			<div class="nav-bottom-right">
			<h1>Navigasi</h1>
				<?php
				$i=0;
					foreach( $menus as $key => $menu ) {?>
						<li>
						<span>
						<img src="<?php echo base_url();?>/themes/orange/pictures/icon/nav-arrow-right-green.png" />
						<a 
						<?php
						if(isset($current_link) && $current_link == $link[$i] )
						{
						?>
						class="current"
						<?php
						}
						else {?>
						href="<?php echo site_url('sppd/'.$link[$i]);?>" 
						<?php
						}
						?>
						/><?php echo $menu;?></a></span></li>
					<?php
					$i++;
					}
				?>
			</div>
		</div>
	</ul>
</div>
