<table width=100% height=98% align=center>
	<tr width=100% align=center>
		<td valign=middle align=center width=100%>

			<table cellspacing=0 cellpadding=0 border=0>
			
				<tr>
					<td width=7><img src='<?php echo base_url()?>themes/orange/pictures/login/white_01.gif' width=7 height=7 alt=''></td>
					<td background='<?php echo base_url()?>themes/orange/pictures/login/white_02.gif'>
						<img src='<?php echo base_url()?>themes/orange/pictures/login/white_02.gif' width=7 height=7 alt=''>
					</td>
					<td width=7><img src='<?php echo base_url()?>themes/orange/pictures/login/white_03.gif' width=7 height=7 alt=''></td>
				</tr>
				<tr>
					<td background='<?php echo base_url()?>themes/orange/pictures/login/white_04.gif'>
						<img src='<?php echo base_url()?>themes/orange/pictures/login/white_04.gif' width=7 height=7 alt=''>
					</td>
					<td bgcolor='white'>

						<table border=0 cellspacing=4 cellpadding=2>
						<tr>
							<td colspan=2>
								<img src='<?php echo base_url()?>themes/orange/pictures/login/AlfrescoLogo200.png' width=200 height=58 alt="Alfresco" title="Alfresco">
							</td>
						</tr>
						<tr>
							<td colspan=2>
								<span class='mainSubTitle'>Enter Login details:</span>
							</td>
						</tr>
						<tr>
							<td>User Name:</td>
							<td>
								<input id="loginForm:user-name" name="loginForm:user-name" type="text" value="" style="width:150px" />
							</td>
						</tr>
						<tr>
							<td>Password:</td>
							<td>
								<input type="password" id="loginForm:user-password" name="loginForm:user-password" style="width:150px" />
							</td>
						</tr>
						<tr>
							<td>Language:</td>
							<td>
								<select id="loginForm:language" name="loginForm:language" size="1" style="width:150px" onchange="document.forms['loginForm'].submit(); return true;">	<option value="en_US" selected="selected">English</option></select>
							</td>
						</tr>
						<tr>
							<td colspan=2 align=right>
								<input id="loginForm:submit" name="loginForm:submit" type="submit" value="Login" onclick="if(typeof window.clearFormHiddenParams_loginForm!='undefined'){clearFormHiddenParams_loginForm('loginForm');}" />
							</td>
						</tr>
						<tr>
							<td colspan=2></td>
						</tr>
						</table>
					</td>
					<td background='<?php echo base_url()?>themes/orange/pictures/login/white_06.gif'>
						<img src='<?php echo base_url()?>themes/orange/pictures/login/white_06.gif' width=7 height=7 alt=''>
					</td>
				</tr>
				<tr>
					<td width=7>
						<img src='<?php echo base_url()?>themes/orange/pictures/login/white_07.gif' width=7 height=7 alt=''>
					</td>
					<td background='<?php echo base_url()?>themes/orange/pictures/login/white_08.gif'>
						<img src='<?php echo base_url()?>themes/orange/pictures/login/white_08.gif' width=7 height=7 alt=''>
					</td>
					<td width=7>
						<img src='<?php echo base_url()?>themes/orange/pictures/login/white_09.gif' width=7 height=7 alt=''>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
