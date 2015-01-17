<?
//print_r($menus);
?>

<div id="gcms_main_menu" class="yuimenubar yuimenubarnav"> 
    <div class="bd"> 
        <ul class="first-of-type"> 
        <li class="yuimenubaritem first-of-type"><a class="yuimenubaritemlabel">Do</a></li> 
        <?php if ($menus) { foreach($menus as $m) { ?><li class="yuimenubaritem first-of-type"><a class="yuimenubaritemlabel"><?php echo $m[0]; ?></a></li>
        <?php } } ?>
        </ul> 
    </div> 
</div> 

<script type="text/javascript">
YAHOO.util.Event.onContentReady("gcms_main_menu", function () { 
    var oMenuBar = new YAHOO.widget.MenuBar("gcms_main_menu", { autosubmenudisplay: true, hidedelay: 750, lazyload: true }); 
    var aSubmenuData = [ 
    { 
        id: "menu0",  
        itemdata: [ 
<?php 
    if(!b_logged()) { ?>{ text: "Login", url: "." }, <?php }
    else { ?>{ text: "Logout", url: "logout.php" }, <?php } ?>
            { text: "Administration", url: "./admin/" },
			{ text: "Main Menu", url: "./index.php" }
        ] 
    } 
<?php 
    if ($menus) { $n = 1; foreach($menus as $m) { ?>
    , {
        id: "menu<?php echo $n ?>",
        itemdata: [ 
<?php 
    for ($i = 1; $i < count($m); $i ++) { 
        if($i > 1) echo ","; 
		if(is_array($m[$i][1])){
		?>
			{text:"<?=$i.'. '.$m[$i][0]?>",
				submenu:{
					id:"<?=$m[$i][0].$xx?>",
					itemdata:[
<?                  	$b=1; $suburut=0;					
						while(is_array($m[$i][$b])){ 
							$suburut++;
?>
							{text:"<?=$suburut.'. '.$m[$i][$b][1]?>",url:"<?=$m[$i][$b][0]?>"},
<?   	    				$b++;						
						}  
						$xx++;	
?>
					]
				}
			}
<?		}else{ ?>
			{text: "<?= $i.'. '.$m[$i][1] ?>", url: "<?= $m[$i][0] ?>"}
<?		}   ?>
		
            
<?	}; $n ++; ?>
        ] 
    } 
<?php 
    } } ?>
    ]; 

	oMenuBar.subscribe("beforeRender", function () { 
	      if (this.getRoot() == this) { 
	          this.getItem(0).cfg.setProperty("submenu", aSubmenuData[0]); 
            <? for($i = 1; $i <= count($menus); $i ++) { ?>
					this.getItem(<?= $i ?>).cfg.setProperty("submenu", aSubmenuData[<?= $i ?>]);
			<? } ?>
  	      } 
    }); 

    oMenuBar.render();
  	if (YAHOO.env.ua.gecko && YAHOO.env.ua.gecko < 1.9 && YAHOO.widget.Module.prototype.platform == "mac") {
		YAHOO.util.Dom.addClass(oMenuBar.element, "hide-submenu-shadow");
   	} 	
}); 
</script>
