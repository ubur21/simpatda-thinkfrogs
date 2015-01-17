<!--menu-->
<TABLE border="0" cellpadding="0" cellspacing="0" width="1013">
<TR>
	<TD WIDTH=19 HEIGHT=11 style="background-image:url(<?=THEME_DIR?>/images/model1a_07.png)">&nbsp;
	</TD>
	<TD HEIGHT=11 style="background-image:url(<?=THEME_DIR?>/images/model1a_08.png)">&nbsp;
	</TD>
	<TD WIDTH=19 HEIGHT=11 style="background-image:url(<?=THEME_DIR?>/images/model1a_09.png)">&nbsp;
	</TD>
</TR>
<TR>
	<TD WIDTH=19 HEIGHT=28 style="background-image:url(<?=THEME_DIR?>/images/model1a_10.png)">&nbsp;
	</TD>
	<TD HEIGHT=28 bgcolor="#EFEFEF">
		<div id="gcms_main_menu" class="yuimenubar yuimenubarnav" style="margin:0px;padding:0px">
		<div class="bd">
		<ul class="first-of-type">
		<li class="yuimenuitem"><a class="yuimenuitemlabel" href="<?= $m[$i][0] ?>">System</a>
			<div id="<?= $m[0]?>" class="yuimenu">
					<div class="bd">
						<ul>
							<? if(!b_logged()) { ?> 
								 <li class="yuimenuitem"><a class="yuimenuitemlabel" href="#">Public Menu</a></li>
							<? }else { ?>
									<li class="yuimenuitem"><a class="yuimenuitemlabel" href="logout.php"  style="font-size:12px; font-family:Arial;">Logout</a></li> 
							<? } 
							 if(b_logged()){
								$ainfouser = b_userinfo();
								if( $ainfouser['name'] == "Administrator"){
							 ?>	
							 <!--  <li class="yuimenuitem"><a class="yuimenuitemlabel" href="./admin">Administrator</a></li>  -->
							 <? 
								}
							 }
							 ?>
							
						</ul>
					</div>
			</div>
		</li>
		<!-- <li class="yuimenuitem"><a class="yuimenuitemlabel" href="#">PIM</a>
				<div id="pim" class="yuimenu">
					<div class="bd">
						<ul class="first-of-type">
							<li class="yuimenuitem"><a class="yuimenuitemlabel" href="http://mail.yahoo.com">Yahoo! Mail</a></li>
							<li class="yuimenuitem"><a class="yuimenuitemlabel" href="http://addressbook.yahoo.com">Yahoo! Address Book</a></li>
							<li class="yuimenuitem"><a class="yuimenuitemlabel" href="http://calendar.yahoo.com">Yahoo! Calendar</a></li>
							<li class="yuimenuitem"><a class="yuimenuitemlabel" href="http://notepad.yahoo.com">Yahoo! Notepad</a></li>
						</ul>            
					</div>
				</div>                    
		</li> -->			
		<? if ($menus) { 
			$n = 1; 
			foreach($menus as $m) { 
		?>	
			<li class="yuimenubaritem first-of-type"><a class="yuimenubaritemlabel" href="#communication"><?= $m[0]?></a>
				<div id="<?= $m[0]?>" class="yuimenu" >
					<div class="bd" >
						<ul style="padding:0 0 0 0 ; border:1; border-color:#FF0000; ">
							<?
								for ($i = 1; $i < count($m); $i ++) { 
										if($i > 1);
							?>
									<li class="yuimenuitem" ><a class="yuimenuitemlabel" href="<?= $m[$i][0] ?>"  style="padding-Left:0px;padding-bottom:2px;font-size:12px; font-family:Arial;cursor:pointer;"><img src="<?= THEME_DIR ?>/images/icon/install.png" style="padding-left:5px;padding-right:5px;; vertical-align:bottom;" /><?= $m[$i][1]?></a></li>

							<?
								}
							?>
							<!-- Kalau ada sub menu taruhnya di sini yaaaa -->
						</ul>
					</div>
				</div>    
			</li>
			<? }
			}
			?>
		</ul>            
	</div>
	</div>
	</TD>
	<TD WIDTH=19 HEIGHT=28 style="background-image:url(<?=THEME_DIR?>/images/model1a_12.png)">&nbsp;
	</TD>
	</TR>
	<TR>
		<TD WIDTH=19 HEIGHT=16 style="background-image:url(<?=THEME_DIR?>/images/model1a_13.png)">&nbsp;
		</TD>
		<TD HEIGHT=16 style="background-image:url(<?=THEME_DIR?>/images/model1a_14.png)">&nbsp;</TD>
		<TD WIDTH=19 HEIGHT=16 style="background-image:url(<?=THEME_DIR?>/images/model1a_15.png)">&nbsp;
		</TD>
	</TR>
	
</TABLE>
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

		<style type="text/css">
		.yui-button#idsubmitPlus<?= $m[$i][1] ?> button {
			background: url(<?= THEME_DIR ?>/images/icon/add_section.png) 50% 50% no-repeat;padding-left: 0.8sem;
			
		  }
		</style>
		<script type="text/javascript">
		  var btn_submit_plus<?= $m[$i][1] ?> = new YAHOO.widget.Button("idsubmitPlus<?= $m[$i][1] ?>");
		</script>
		<!-- end menu-->