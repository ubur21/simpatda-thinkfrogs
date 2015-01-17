<div align='center'>
<?php echo form_open("auth/login", "id='login_form' name='login_form' AUTOCOMPLETE='off'")."\n"; ?>
<table id="Table_01" width="481" height="404" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="37" height="111" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_01.png')">
			</td>
		<td width="188" height="111" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_02.png')">
			</td>
		<td colspan="4" width="256" height="111" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_03.png')">
			</td>
	</tr>
	<tr>
		<td rowspan="4" width="37" height="197" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_04.png')">
			</td>
		<td rowspan="4" width="188" height="197" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_05.png')">
			</td>
		<td rowspan="4" width="29" height="197" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_06.png')">
			</td>
		<td colspan="3" width="227" height="45" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_07.png')">
			</td>
	</tr>
	<tr>
		<td colspan="3" width="227" height="86" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_08.png')">
<?php
	$data['username']['name']  = $data['username']['id'] = 'username';
	$data['username']['title'] = "";
	$data['username']['size']  = "25";
	
	$data['password']['name']  = $data['password']['id'] = 'password';
	$data['password']['title'] = "";
	$data['password']['size']  = "25";
	
?>		
			<div class='login_form'>
				<div><?php echo form_label('Username','username').form_input($data['username']); ?></div>
				<div><?php echo form_label('Password','password').form_password($data['password']);?></div>
			</div>
		</td>
	</tr>
	<tr>
		<td width="98" height="31" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_09.png')">
			<a href='javascript:{}' onclick='login_form.submit();return false;' id='login'><img src="<?php echo base_url().$this->config->item('theme_dir')?>/images/login/btn_login.png"></a></td>
		<td width="55" height="31" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_10.png')">
			<img src="<?php echo base_url().$this->config->item('theme_dir')?>/images/login/btn_db.png"></td>
		<td width="74" height="31" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_11.png')">
			</td>
	</tr>
	<tr>
		<td colspan="3" width="227" height="35" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_12.png')">
			</td>
	</tr>
	<tr>
		<td width="37" height="96" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_13.png')">
			</td>
		<td colspan="2" width="217" height="96" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_14.png')">
			</td>
		<td colspan="3" width="227" height="96" style="background-image:url('<?php echo base_url().$this->config->item('theme_dir')?>/images/login/login_15.png')">
			</td>
	</tr>
</table>
<?php echo form_close(); ?>
</div>