<!--menu-->
<TABLE border="0" cellpadding="0" cellspacing="0" width="1013" style="background-color:trasparent;">
<TR>
	<TD WIDTH=19 HEIGHT=28 style="background-image:url(<?=THEME_DIR?>/images/model1a_10.png)">&nbsp;
	</TD>
	<TD HEIGHT=28 bgcolor="#EFEFEF">
		<!--<div id="gcms_main_menu" class="yuimenubar yuimenubarnav" style="margin:0px;padding:0px; margin-bottom:3px"> 
	  		<div class="bd"> 
    			<ul class="first-of-type"> 
      				<li class="yuimenubaritem first-of-type"><a class="yuimenubaritemlabel">Do</a></li> 
      			<? if ($menus) { foreach($menus as $m) { ?>
        			<li class="yuimenubaritem first-of-type"><a class="yuimenubaritemlabel"><?= $m[0]; ?></a></li>
      			<? } } ?>
    			</ul> 
  			</div> 
		</div>  -->
       <div id="gcms_main_menu" class="yuimenubar yuimenubarnav">
            <div class="bd">
                <ul class="first-of-type">
                <li class="yuimenuitem"><a class="yuimenuitemlabel" href="<?= $m[$i][0] ?>">System</a>
                    <div id="<?= $m[0]?>" class="yuimenu">
                            <div class="bd">
                                <ul>
                                    <? if(!b_logged()) { ?> 
                                    <!-- <li class="yuimenuitem"><a class="yuimenuitemlabel" href="logout.php">Logout</a></li> -->
                                    <? }else { ?>
                                    <li class="yuimenuitem"><a class="yuimenuitemlabel" href="logout.php"  style="font-size:11px; font-family:Arial; font-weight:bold;">Logout</a></li> 
                                    <? } 
                                     if(b_logged()){
                                        $ainfouser = b_userinfo();
                                        if( $ainfouser['name'] == "Administrator"){
                                     ?>	
                                    <!-- <li class="yuimenuitem"><a class="yuimenuitemlabel" href="./admin">Administrator</a></li> -->
                                     <? 
                                        }
                                     }
                                     ?>
                                    
                                </ul>
                            </div>
                    </div>
                </li>			
                <? if ($menus) { 
                    $n = 1; 
                    foreach($menus as $m) { 
                ?>	
                    <li class="yuimenubaritem first-of-type"><a class="yuimenubaritemlabel" ><?= $m[0]?></a>
                        <div id="<?= "sub_menu_".$m[0]?>" class="yuimenu" >
                            <div class="bd" >
                                <ul style="padding:0 0 0 0 ; border:1; border-color:#FF0000; ">
                                    <?
                                        for ($i = 1; $i < count($m); $i ++) { 
                                                if($i > 0);
                                                    if ( $m[$i][3] == '0' and $m[$i][4] !== 'parent' ){
                                                    ?>
                                                           <li class="yuimenuitem" ><a class="yuimenuitemlabel" href="<?= $m[$i][0] ?>"  style="padding-Left:0px; padding-bottom:2px;font-size:11px; font-family:Arial; font-weight:bold; cursor:pointer;"><img src="<?= THEME_DIR ?>/images/icon/install.png" style="padding-left:5px;padding-right:5px;/*background-color:#B3D4FF*/; vertical-align:bottom;" /><?= $m[$i][1]?></a></li>
                                                    <?
                                                    }else{
                                                        if($m[$i][4] == 'parent' and $m[$i][5] = $m[$i][6] and $m[$i][3] != 2 ){
														    $menu_ho=$m[$i][1];
															if($menu_ho=="HO" && $_SESSION['spbu_no']!=""){
															
															}else{
                                                    ?>
                                                            <li class="yuimenuitem"><a class="yuimenuitemlabel"  style="padding-Left:0px; padding-bottom:2px;font-size:11px; font-family:Arial; font-weight:bold;" ><img src="<?= THEME_DIR ?>/images/icon/install.png" style="padding-left:5px;padding-right:5px;/*background-color:#B3D4FF*/; vertical-align:bottom;" /><?=$menu_ho?></a>
                                                            <div id="<?= "sub_perent_menu_".str_replace(" ","_",$m[$i][0]);?>" class="yuimenu">
                                                                <div class="bd">
                                                                    <ul class="first-of-type">
                                                                        <? 
                                                                        //include(".../config.php");
                                                                        $csql = "select a.* from ".PREFIX."frontmenus as a
																				INNER JOIN ".PREFIX."grantedfrontmenus as b on a.nid=b.nid_frontmenus 
																				WHERE a.NID_GROUPFRONT_SUBMENUS = ".$m[$i][6]." 
																				AND bhide = '0' 
																				AND a.nid_groupfrontmenus='".$m[$i][2]."'
																				AND b.nid_users = '".$m[$i][7]."'
																				AND a.cparam !='Group'
																				AND b.nstatus = '1'
																				order By a.nurut";
                                                                        $nres_submenu=gcms_query($csql);
                                                                            while ($menu=gcms_fetch_object($nres_submenu)) {
                                                                                 $nm = str_replace(".", "_", str_replace("-", "_", str_replace(" ", "_", stripslashes($menu->cmenu))));
                                                                                 if (!$menu->width) {
                                                                                     $menu->width = 600;
                                                                                 }else if (!$menu->height){
                                                                                     $menu->height = 500;
                                                                                 }else {
                                                                                 }
                                                                                 if ( $menu->is_main == 1) {
                                                                                    $load_menu = "index.php?page=".$menu->nid;
                                                                                 }else if ( $menu->is_main == 2) {
                                                                                    $load_menu = "javascript:gcms_open_form('form_entri.php?page=".$menu->nid."','".$nm."',".$menu->width.",".$menu->height.")";
                                                                                 }else {
                                                                                    $load_menu = "javascript:gcms_open_form('form.php?page=".$menu->nid."','".$nm."',".$menu->width.",".$menu->height.")";
                                                                                 }	
                                                                                if( $menu->csubmenu == 1 ){
                                                                                    ?>
                                                                                    <li class="yuimenuitem"><a class="yuimenuitemlabel"  style="padding-Left:0px; padding-bottom:2px;font-size:11px; font-family:Arial; font-weight:bold; cursor:pointer;" href="<?= $load_menu ; ?>"><img src="<?= THEME_DIR ?>/images/icon/install.png" style="padding-left:5px;padding-right:5px;/*background-color:#B3D4FF*/; vertical-align:bottom;" /><?= $menu->cmenu?></a></li>
                                                                                    <?
                                                                                }else if ( $menu->csubmenu == 2){	
                                                                                    ?>
                                                                                    <li class="yuimenuitem"><a class="yuimenuitemlabel"  style="padding-Left:0px; padding-bottom:2px;font-size:11px; font-family:Arial; font-weight:bold;" ><img src="<?= THEME_DIR ?>/images/icon/install.png" style="padding-left:5px;padding-right:5px;/*background-color:#B3D4FF*/; vertical-align:bottom;" /><?= $menu->cmenu?></a>
                                                                                        <div id="<?= "sub_granted_menu_".str_replace(" ", "_", $menu->cmenu)."menu_4" ?>" class="yuimenu">
                                                                                            <div class="bd">
                                                                                                <ul class="first-of-type">
																								 <?
                                                                                                    $csql2 = "select a.* from ".PREFIX."frontmenus as a where a.NID_GROUPFRONT_SUBMENUS = ".$menu->nid." order By a.NID_GROUPFRONT_SUBMENUS,a.nurut";
                                                                                                    $nres_submenu_2=gcms_query($csql2);
                                                                                                        while ($menu2=gcms_fetch_object($nres_submenu_2)) {
                                                                                                             $nm = str_replace(".", "_", str_replace("-", "_", str_replace(" ", "_", stripslashes($menu2->cmenu))));
                                                                                                             if (!$menu2->width) {
                                                                                                                 $menu2->width = 600;
                                                                                                             }else if (!$menu2->height){
                                                                                                                 $menu2->height = 500;
                                                                                                             }else {
                                                                                                             }
                                                                                                             if ( $menu2->is_main == 1) {
                                                                                                                $load_menu2 = "index.php?page=".$menu2->nid;
                                                                                                             }else if ( $menu2->is_main == 2) {
                                                                                                                $load_menu2 = "javascript:gcms_open_form('form_entri.php?page=".$menu2->nid."','".$nm."',".$menu2->width.",".$menu2->height.")";
                                                                                                             }else {
                                                                                                                $load_menu2 = "javascript:gcms_open_form('form.php?page=".$menu2->nid."','".$nm."',".$menu2->width.",".$menu2->height.")";
                                                                                                             }
                                                                                                            ?>
                                                                                                  				<li class="yuimenuitem"><a class="yuimenuitemlabel" style="padding-Left:0px; padding-bottom:2px;font-size:11px; font-family:Arial; font-weight:bold; cursor:pointer;" href="<?= $load_menu2 ?>"><img src="<?= THEME_DIR ?>/images/icon/install.png" style="padding-left:5px;padding-right:5px;/*background-color:#B3D4FF*/; vertical-align:bottom;" /><?= $menu2->cmenu?></a></li>
                                                                                                            <?	
                                                                                                        }	 
                                                                                                 ?>  
                                                                                                 </ul>
                                                                                            </div> 
                                                                                         </div> 
                                                                                    </li>
                                                                                    <?
                                                                                }
                                                                            }
                                                                        //}
                                                                        ?>
                                                                    </ul>            
                                                                </div>
                                                            </div>
                                                          
                                                    <?	   }
                                                        }
                                                    }
                                        }
                                    ?>
                                    </li> 
                                </ul>
                            </div>
                        </div>    
                    </li>
                    <? 
                        }
                    
                    }
                    ?>
                </ul>            
            </div>
        </div> 
	</TD>
	<TD WIDTH=19 HEIGHT=28>
	<img src="<?=THEME_DIR?>/images/model1a_12.png" />
	</TD>
</TR>
<TR>
	<TD WIDTH=19 HEIGHT=16>
	<img src="<?=THEME_DIR?>/images/model1a_13.png" />
	</TD>
	<TD HEIGHT=16 style="background-image:url(<?=THEME_DIR?>/images/model1a_14.png)">&nbsp;</TD>
	<TD WIDTH=19 HEIGHT=16>
	<img src="<?=THEME_DIR?>/images/model1a_15.png" />
	</TD>
</TR>
</TABLE>
<!--<script type="text/javascript">

YAHOO.util.Event.onContentReady("gcms_main_menu", function () { 
    var oMenuBar = new YAHOO.widget.MenuBar("gcms_main_menu", { autosubmenudisplay: true,  
	                                                              hidedelay: 750,  
	                                                              lazyload: true }); 

  var aSubmenuData = [ 
    { 
      id: "menu0",  
      itemdata: [ 
                 <? if(!b_logged()) { ?>{ text: "Login", url: "." }, <? }
                 else { ?>{ text: "Logout", url: "logout.php" }, <? } ?>
	              { text: "Administration", url: "./admin/" }
                ] 
    } 
    <? if ($menus) { $n = 1; foreach($menus as $m) { ?>
    , {
      id: "menu<?= $n ?>",
      itemdata: [ 
      <? for ($i = 1; $i < count($m); $i ++) { 
                if($i > 1) echo ","; ?>
	              { text: "<?= $m[$i][1] ?>", url: "<?= $m[$i][0] ?>" }
      <? }; $n ++; ?>
                ] 
      } 
      <? } } ?>
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
}); 

</script>  --><!---->
<script type="text/javascript">

            YAHOO.util.Event.onContentReady("gcms_main_menu", function () {

                var ua = YAHOO.env.ua,
                    oAnim;  // Animation instance

                function onSubmenuBeforeShow(p_sType, p_sArgs) {

                    var oBody,
                        oElement,
                        oShadow,
                        oUL;
                

                    if (this.parent) {

                        oElement = this.element;

                        oShadow = oElement.lastChild;
                        oShadow.style.height = "0px";

                        if (oAnim && oAnim.isAnimated()) {
                        
                            oAnim.stop();
                            oAnim = null;
                        
                        }

                        oBody = this.body;


                        //  Check if the menu is a submenu of a submenu.

                        if (this.parent && 
                            !(this.parent instanceof YAHOO.widget.MenuBarItem)) {
                        

                            if (ua.gecko || ua.opera) {
                            
                                oBody.style.width = oBody.clientWidth + "px";
                            
                            }
                            if (ua.ie == 7) {

                                oElement.style.width = oElement.clientWidth + "px";

                            }
                        
                        }

    
                        oBody.style.overflow = "hidden";


                        /*
                            Set the <ul> element's "marginTop" property 
                            to a negative value so that the Menu's height
                            collapses.
                        */ 

                        oUL = oBody.getElementsByTagName("ul")[0];

                        oUL.style.marginTop = ("-" + oUL.offsetHeight + "px");
                    
                    }

                }



                function onTween(p_sType, p_aArgs, p_oShadow) {

                    if (this.cfg.getProperty("iframe")) {
                    
                        this.syncIframe();
                
                    }
                
                    if (p_oShadow) {
                
                        p_oShadow.style.height = this.element.offsetHeight + "px";
                    
                    }
                
                }


                /*
                    "complete" event handler for the Anim instance, used to 
                    remove style properties that were animated so that the 
                    Menu instance can be displayed at its final height.
                */

                function onAnimationComplete(p_sType, p_aArgs, p_oShadow) {

                    var oBody = this.body,
                        oUL = oBody.getElementsByTagName("ul")[0];

                    if (p_oShadow) {
                    
                        p_oShadow.style.height = this.element.offsetHeight + "px";
                    
                    }


                    oUL.style.marginTop = "";
                    oBody.style.overflow = "";
                    

                    //  Check if the menu is a submenu of a submenu.

                    if (this.parent && 
                        !(this.parent instanceof YAHOO.widget.MenuBarItem)) {


                        // Clear widths set by the "beforeshow" event handler

                        if (ua.gecko || ua.opera) {
                        
                            oBody.style.width = "";
                        
                        }
                        
                        if (ua.ie == 7) {

                            this.element.style.width = "";

                        }
                    
                    }
                    
                }


                function onSubmenuShow(p_sType, p_sArgs) {

                    var oElement,
                        oShadow,
                        oUL;
                
                    if (this.parent) {

                        oElement = this.element;
                        oShadow = oElement.lastChild;
                        oUL = this.body.getElementsByTagName("ul")[0];
                    

                        /*
                             Animate the <ul> element's "marginTop" style 
                             property to a value of 0.
                        */

                        oAnim = new YAHOO.util.Anim(oUL, 
                            { marginTop: { to: 0 } },
                            .5, YAHOO.util.Easing.easeOut);


                        oAnim.onStart.subscribe(function () {
        
                            oShadow.style.height = "100%";
                        
                        });
    

                        oAnim.animate();

    
                        /*
                            Subscribe to the Anim instance's "tween" event for 
                            IE to syncronize the size and position of a 
                            submenu's shadow and iframe shim (if it exists)  
                            with its changing height.
                        */
    
                        if (YAHOO.env.ua.ie) {
                            
                            oShadow.style.height = oElement.offsetHeight + "px";


                            /*
                                Subscribe to the Anim instance's "tween"
                                event, passing a reference Menu's shadow 
                                element and making the scope of the event 
                                listener the Menu instance.
                            */

                            oAnim.onTween.subscribe(onTween, oShadow, this);
    
                        }
    

                        /*
                            Subscribe to the Anim instance's "complete" event,
                            passing a reference Menu's shadow element and making 
                            the scope of the event listener the Menu instance.
                        */
    
                        oAnim.onComplete.subscribe(onAnimationComplete, oShadow, this);
                    
                    }
                
                }


                /*
                     Instantiate a MenuBar:  The first argument passed to the 
                     constructor is the id of the element in the page 
                     representing the MenuBar; the second is an object literal 
                     of configuration properties.
                */

                var oMenuBar = new YAHOO.widget.MenuBar("gcms_main_menu", { 
                                                            autosubmenudisplay: true, 
                                                            hidedelay: 750, 
                                                            lazyload: true });


                /*
                     Subscribe to the "beforeShow" and "show" events for 
                     each submenu of the MenuBar instance.
                */
                
                oMenuBar.subscribe("beforeShow", onSubmenuBeforeShow);
                oMenuBar.subscribe("show", onSubmenuShow);


                /*
                     Call the "render" method with no arguments since the 
                     markup for this MenuBar already exists in the page.
                */

                oMenuBar.render();          
            
            });

        </script>

