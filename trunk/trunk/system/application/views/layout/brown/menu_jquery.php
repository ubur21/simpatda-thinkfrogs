<?php if(b_logged()){ 

$data = load_menus();
$menu = format_menu($data);

?>			
<script>
var arrowimages = {
	down: ['downarrowclass', '<?php echo $theme_dir?>/images/arrow-down.gif',25],
	right: ['rightarrowclass', '<?php echo $theme_dir?>/images/arrow-right.gif']
}

var jquerycssmenu = {
	fadesettings: {
		overduration:350,
		outduration:100
	},
	buildmenu: function(menuid, arrowsvar) {
		jQuery(document).ready(function($) {
			var $mainmenu = $("#"+menuid+">ul")
			var $headers = $mainmenu.find("ul").parent();
			
			$headers.each(function(i) {
				var $curobj = $(this)
				var $subul = $(this).find('ul:eq(0)')
				this._dimensions = {
					w:this.offsetWidth,
					h:this.offsetHeight,
					subulw:$subul.outerWidth(),
					subulh:$subul.outerHeight()
				}
				this.istopheader = $curobj.parents("ul").length == 1 ? true : false
				$subul.css({top:this.istopheader ? this._dimensions.h + "px" : 0})
				$curobj.children("a:eq(0)")
					.css(this.istopheader ? {paddingRight:arrowsvar.down[2]} : {})
					.append('<img src="' + (this.istopheader?arrowsvar.down[1]:arrowsvar.right[1])
						+'" class="'+(this.istopheader ? arrowsvar.down[0] : arrowsvar.right[0])
						+'" style="border:0;" />')
				$curobj.hover(function(e) {
					var $targetul = $(this).children("ul:eq(0)")
					this._offsets = {left:$(this).offset().left,top:$(this).offset().top}
					var menuleft = this.istopheader ? 0 : this._dimensions.w
					menuleft = (this._offsets.left+menuleft + this._dimensions.subulw>$(window).width())?(this.istopheader?-this._dimensions.subulw+this._dimensions.w:-this._dimensions.w):menuleft
					$targetul.css({left:menuleft+"px"})
					.show()
				},function(e) {
					$(this).children("ul:eq(0)").hide()
				})
			})
			$mainmenu.find("ul").css({display:'none',visibility:'visible'})
		})
	}
}
jquerycssmenu.buildmenu("myjquerymenu",arrowimages);

</script>
<div id="header-box">
		<div id="module-status">
			<span class="loggedin-users"><? echo $this->session->userdata('SESS_USER_NAME')?></span>
			<a href='<? echo base_url().'/auth/logout'?>'><span class="logout">logout</span></a>
		</div>
		<div id="module-menu">
			<div id="myjquerymenu" class="jquerycssmenu">
<?php 			echo $menu; ?>
				<br style="clear: left" />
			</div>
		</div>
		<div class='clr'></div>
	</div>
<?php } ?>