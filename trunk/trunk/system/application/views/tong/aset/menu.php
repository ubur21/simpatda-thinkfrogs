<?php
$current_link=isset($current_link)?$current_link:'daftar';
$menus=array('Daftar','Tambah','Ubah');
$link=array('daftar','tambah','edit');
?>
<div class="west">
<div class="<?php echo $this->css->box();?>">
<div class="<?php echo $this->css->tab();?>">Navigasi</div>
	<ul class="nav">
		<div class="nav-bottom-left">
			<div class="nav-bottom-right">
				<?php
				$i=0;
					foreach( $menus as $key => $menu ) {?>
						<li>
						<span>
						<img src="<?php echo base_url();?>/themes/orange/pictures/icon/<?php echo $link[$i];?>.gif" />
						<a 
						<?php
						if(isset($current_link) && $current_link == $link[$i] )
						{
						?>
						class="current"
						<?php
						}
						else {?>
						href="<?php echo site_url('aset/'.$link[$i]);?>" 
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
</div>