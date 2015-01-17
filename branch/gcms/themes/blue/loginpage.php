		<div align="center">
		<TABLE WIDTH=503 BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
		<TD>
			<IMG SRC="<?=THEME_DIR?>/images/form2_01.png" WIDTH=20 HEIGHT=73 ALT=""></TD>
		<TD>
			<IMG SRC="<?=THEME_DIR?>/images/form2_02.png" WIDTH=14 HEIGHT=73 ALT=""></TD>
		<TD WIDTH=434 HEIGHT=73 style="color:#FDA102;background-image:url(<?=THEME_DIR?>/images/form2_03.png); text-align:right; padding-top:10; font-family:Arial; font-size:28; font-weight:bold;">Login</TD>
		<TD>
			<IMG SRC="<?=THEME_DIR?>/images/form2_04.png" WIDTH=14 HEIGHT=73 ALT=""></TD>
		<TD>
			<IMG SRC="<?=THEME_DIR?>/images/form2_05.png" WIDTH=21 HEIGHT=73 ALT=""></TD>
		</TR>
		<TR>
			<TD WIDTH=20 HEIGHT=149 style="background-image:url(<?=THEME_DIR?>/images/form2_06.png)">&nbsp;</TD>
			<TD colspan="3" bgcolor="#F2F1E6">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td align="center">
						<?
						$logo = b_fetch('select logo from info_pemda');
						if(!empty($logo)){
							echo "<img src=\"showimage.php?id=1\" height='157'>";
						}else{
							echo '<img src="images/garuda.gif" width="139" height="133"  style="margin-left:10%; margin-top:5%">';
						}
						?>
						</td>
						<td><div style="border:1px solid #CCCCCC; padding:10; background-color:#E9ECEF">
							<form action="login.php" method="POST">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td>
											Username<br>
											<input class="login_input" type="text" name="cuser" style="width:100%"><br><br>
											Password<br>
											<input class="login_input" type="password" name="cpass" style="width:100%"><br><br>
										</td>
										<td align="center"><img src="<?=THEME_DIR?>/images/security.gif" /></td>
									<tr>
										<td colspan="2">
											<input id="login_submit" type="submit" name="psubmit" value="Login">
										</td>
									</tr>
								</table>	
							</form>
							</div>
						</td>
					</tr>
				</table>
			</TD>
			<TD WIDTH=21 HEIGHT=149 style="background-image:url(<?=THEME_DIR?>/images/form2_10.png)">&nbsp;</TD>
		</TR>
		<TR>
			<TD WIDTH=20 HEIGHT=28 style="background-image:url(<?=THEME_DIR?>/images/form2_11.png)">&nbsp;</TD>
			<TD WIDTH=14 HEIGHT=28 style="background-image:url(<?=THEME_DIR?>/images/form2_12.png)">&nbsp;</TD>
			<TD HEIGHT=28 style="background-image:url(<?=THEME_DIR?>/images/form2_13.png)">&nbsp;</TD>
			<TD WIDTH=14 HEIGHT=28 style="background-image:url(<?=THEME_DIR?>/images/form2_14.png)">&nbsp;</TD>
			<TD WIDTH=21 HEIGHT=28 style="background-image:url(<?=THEME_DIR?>/images/form2_15.png)">&nbsp;</TD>
		</TR>
		</TABLE>
		</div>
<!-- end table login-->
<script type="text/javascript">
  var btn_login_submit = new YAHOO.widget.Button("login_submit");
</script>

<style type="text/css">
  .yui-button#login_submit button{
    background: url(<?= THEME_DIR ?>/images/password.gif) 10% 50% no-repeat;
    padding-left:2em;
  }
  
  .yui-button#login_submit:hover button{
  	background-color:#FFFF00;
  }
</style>
