<?php
 /* memasukkan isi dari main page - harus ada */
login_page_content();
 /* --------------------------------------- */
?>
<!--embedded jquery on login page-->
<!--<script src="script/jquery/jquery.js" type="text/javascript"></script>-->
<!--add some effect-->
<!--<script type="text/javascript">
		$(document).ready(function() {
			$(".error_msg").show().fadeOut(3500);
		});
</script>-->
<!--add some effect-->
<!--embedded jquery on login page-->

<!--login start-->
<div id="problem_align_login_page" align="center">
<form action="login.php" method="POST">
<div id="container_l">

	<!--error_message start-->
	<?php if(isset($_SESSION['error_login'])) {?>

	<div class="error_msg">
		<span id="error_login"><?php echo $_SESSION['error_login'];?></span>
	</div><?php
	}
	?>
	<!--error_message end-->

	<div id="login_box">
		<div class="rata_kiri"><span  class="top_1"><input class="field" type="text" id="cuser" name="cuser" /></span></div>
		<div class="rata_kiri"><span  class="top_2"><input class="field" type="password" id="cpass" name="cpass" /></span></div>
		<div class="rata_kiri">
			<span class="top_3"><input type="submit" class="button-login" name="psubmit" value="" /></span>
			<span class="top_3"><!--<input type="button"  id="btn" name="dbselect" value="DB" />--></span>
		</div>
	</div>
</div>
</form>
</center>

<!--login end-->
<script type="text/javascript">
    var btn_login_submit = new YAHOO.widget.Button("submit");
</script>

<style type="text/css">
yui-button#login_submit button {
    background: url(<?php echo THEME_DIR ?>/images/password.png) 10% 50% no-repeat;
    padding-left: 2em;
}
</style>

<script type="text/javascript">
try{document.getElementById('cuser').focus();}catch(e){}
</script>
