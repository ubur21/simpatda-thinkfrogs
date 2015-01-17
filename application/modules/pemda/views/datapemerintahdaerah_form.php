	<div class='cntr_form'>
		<div class="title_form">Data Pemerintah Daerah</div>
		<form class='form' action="<?php echo base_url()?>pemda/proses_form" method="POST">
			<fieldset class='fieldset' id='logo'>
				<legend class='legend'>Logo Pemerintah Daerah</legend>
					<div class='fd_left'>
						<ul class="gallery" id='foto_realisasi'>
							<?php 
								
								echo '<img src="pemda/logo" alt="image" style="width:170px;height:170px" />';
								
							?>		
						</ul>
					</div>
					<div class='fd_right'>
						<div>
							<label>Nama Pemerintah Daerah :</label>
							<input type='text' size='40' id='NAMA_PEMDA' name='NAMA_PEMDA' value='<?php echo isset($user_data[0]["NAMA_PEMDA"]) ? $user_data[0]["NAMA_PEMDA"] : ""; ?>'>
						</div>
						<div>
							<label>Lokasi :</label>
							<input type='text' size='40' id='LOKASI' name='LOKASI' value='<?php echo isset($user_data[0]["LOKASI"]) ? $user_data[0]["LOKASI"] : ""; ?>'>
						</div>
					</div>
			</fieldset>

			<div id='cntr_button'>
				<input type='hidden' value='<?php echo isset($user_data['update']) ? $user_data['update'] : ""; ?>' name='update' />
				<?php
					if ($SYS_ADMIN_LOGIN=='1') //Hak Akses == Ubah
					{
						echo "<input type='button' name='pemda_submit' id='btn_submit' value='Simpan' class='button1' style='margin-right:0.3em'/>";
						echo "<input type='reset' name='pemda_reset' id='btn_reset' value='Batal' class='button1' style='margin-right:0.3em'/>";
					}
				?>
				<script src="<?php echo base_url()?>assets/script/form.js" type="text/javascript" charset="utf-8"></script>
			</div>
			<div class='clear'></div>
		</form>			
	
	<br>
	
	
		<fieldset class='fieldset' id='logo2'>
			<div class='fd_left'>
				<div class='fd_left'>
					<div style="float:left">
						<form class='form' action="<?php echo base_url()?>pemda/simpan_logo" enctype="multipart/form-data" method="POST">
							<input name="image" size="30" id="image"  type="file">
							
							<?php
								$extra_attribut4 = 'id="upload_btn2" class="button2" onclick="upload_btn2"';
								echo form_submit('upload_button','Upload Logo',$extra_attribut4);
							?>
						</form>
					</div>	
					<input type="submit" name="hapus_logo" id="hapus_logo" class="button2" onclick="hapus_logo" value="Hapus Logo" style="float:left; margin-top:7px">	
				</div>
				
			</div>
			
	    </fieldset>
	
	
	<!--
	<form class='form' action="<?php echo base_url()?>pemda/simpan_logo" enctype="multipart/form-data" method="POST">
		<fieldset class='fieldset' id='logo3'>
			<div class='fd_left'>
				<div class='fd_left' id='list_upload'>
					<div id="divinputfile">
						<input name="filepc" size="30" id="filepc" onchange="document.getElementById('fakefilepc').value = this.value;" type="file">
						<div id="fakeinputfile"><input name="fakefilepc" id="fakefilepc" type="text"></div>
						</div>
							<?php
								//$extra_attribut1 = 'id="upload_btn" class="button2"';
								//echo form_submit('upload_button','Upload Logo',$extra_attribut1);
							?>
						
				</div>
			</div>
		</fieldset>
	</form>
	-->
	
	</div>

	<script type="text/javaScript">	
		jQuery(document).ready(function(){
			jQuery("#hapus_logo").click(function(){			
				jQuery("#loading").show();
					jQuery.ajax({
							type: "POST",
							url : "<?php echo base_url() ?>pemda/hapus_logo",							
							success: function(msg){
								jQuery("#loading").hide();
								showmessage( "Logo Berhasil Terhapus");
								location.reload(true); 
							}
					});
			});
			
			jQuery("#btn_submit").click(function(){				
				var NAMA_PEMDA = jQuery('#NAMA_PEMDA').val();
				var LOKASI = jQuery('#LOKASI').val();
				jQuery.post('<?php echo base_url();?>pemda/proses_form', {'NAMA_PEMDA':NAMA_PEMDA,'LOKASI':LOKASI},function(resp){
					var msg = jQuery.parseJSON(resp);
					if(msg.sukses)
					{
						showmessage(msg.sukses);
						jQuery('#grid').trigger('reloadGrid');
					}
					else
					{
						showmessage(msg.error);
					}
				});
			});
			
			jQuery('#upload_btn2').click(function(){
				jQuery("#loading").show();
			});
			
			function showmessage(msg){
				jQuery('#message').html(msg).show(100).delay(5000).hide(500);
			}
			
			jQuery("#message").ajaxError(function(event, request, settings){
			var msg = jQuery.parseJSON(request.responseText);
			if(msg.error){
				showmessage(msg.error);
			}
		});

		});
	</script>