<!--<script src="<?php echo base_url()?>assets/script/tinymce_compressor_php/tiny_mce_gzip.js" type="text/javascript"></script>-->
<script src="<?php echo base_url()?>assets/script/tinymce/jscripts/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<?php
$id=isset($id)?$id:'';
$attrib=array('id'=>'form_berita');
echo form_open("master/berita/proses/".$id,$attrib);
?>
<div id="tabs" class="form" style="min-height:300px;min-width:500px;">
	<ul>
		<li><a href="#tab-form">Form</a></li>
		<li><a href="<?php echo site_url();?>/master/berita/tab/berita_daftar">Daftar</a></li>
	</ul>
	<div id="tab-form">
		<div id="output" style="height:20px;font-weight:bold;"></div>
			<fieldset class="form">
			<div>
			<?php
				echo form_label('Judul','judul');
			?>
			</div>
			<div class="multi">
			<?php
				$judul=isset($user_data['JUDUL'])?$user_data['JUDUL']:'';
				$attrib=array('id'=>'judul','name'=>'judul','value'=>$judul,'size'=>50,'maxlength'=>100);
				echo form_input($attrib);
			?>
			</div>
			<div>
			<?php
				echo form_label('Sumber','sumber');
			?>
			</div>
			<div class="multi">
			<?php
				$sumber=isset($user_data['SUMBER'])?$user_data['SUMBER']:'';
				$attrib=array('name'=>'sumber','value'=>$sumber,'size'=>50,'maxlength'=>100);
				echo form_input($attrib);
			?>
			</div>
			<div>
			<?php
				echo form_label('Deskripsi','deskripsi');
			?>
			</div>
			<div class="multi">
			<?php
				
				
				$desk=isset($user_data['DESKRIPSI'])?$user_data['DESKRIPSI']:'';
				$attrib=array('name'=>'deskripsi','value'=>$desk,'class'=>'text-editor','rows'=>15,'cols'=>80);
				echo form_textarea($attrib);
			?>
			</div>
			<!--
			<div>
			<?php
				
				echo form_label('Deskripsi','deskripsi');
				
				$desk=isset($user_data['DESKRIPSI'])?$user_data['DESKRIPSI']:'';
				$attrib=array('name'=>'deskripsi','value'=>$desk,'rows'=>5,'cols'=>100);
				echo form_textarea($attrib);
			?>
			</div>
			-->
			</fieldset>
			<div class="<?php echo $this->css->pager();?> buttons">
				<fieldset class="form">
					<div><?php echo form_label('','');?></div>
						<div class="multi">
						<a id="simpan" class="<?php echo $this->css->button();?>" value="simpan">Simpan<span class="<?php echo $this->css->iconsave();?>"></span></a>
						&nbsp;&nbsp;&nbsp;
						<a id="batal" class="<?php echo $this->css->button();?>" value="batal">Batal<span class="<?php echo $this->css->iconclose();?>"></span></a>
					</div>
				</fieldset>
				<div style="clear:both;">
			</div>
		<?php echo form_close();?>

		<script type="text/javascript">
		jQuery(document).ready(function() {
			$("#simpan").hover(function(){ $(this).addClass('<?php echo $this->css->hover()?>'); },function(){ $(this).removeClass('<?php echo $this->css->hover()?>'); });
			$("#batal").hover(function(){ $(this).addClass('<?php echo $this->css->hover()?>'); },function(){ $(this).removeClass('<?php echo $this->css->hover()?>'); });
			
			var options = {
				dataType:'json',
				beforeSubmit: showRequest,
				success: showResponse
			};
			$("#form_berita").ajaxForm(options);

			$("#simpan").click(function() {
				$("#form_berita").submit();
			});
			
		});
		function showRequest(formData, jqForm, options) {
			
			var ed = tinyMCE.get('deskripsi');
			
			for(var key in formData) {
				if(formData[key]['name']=='deskripsi') {
					formData[key]['value']=ed.getContent();
				}
			}
			//alert(ed.getContent());
			// alert(ed.getContent());
			var queryString = $.param(formData);
			//alert(queryString);
			//return false;
			return true;
		}
		function showResponse(responseText, statusText, xhr, $form) {
			//alert('status:'+statusText+'response:'+responseText);
			alert(responseText.message);
			//$("#form_berita").resetForm();
		}
		</script>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		window.onbeforeunload = null;

		$("#tabs").tabs();
		
		tinyMCE.init({
			editor_selector:"text-editor",
			mode:"textareas",
			theme:"advanced",
			plugins:"pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist" ,
			theme_advanced_buttons1:"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect" ,
			theme_advanced_buttons2:"bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,|,insertdate,inserttime,preview,|,forecolor,backcolor" ,
			theme_advanced_buttons3:"tablecontrols,|,hr,|,sub,sup,|,visualaid,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen" ,
			theme_advanced_toolbar_location:"top" ,
			theme_advanced_toolbar_align: "left" ,
			theme_advanced_statusbar_location:"bottom" ,
			theme_advanced_resizing:true ,
			content_css: "<?php echo site_url()?>/assets/css/tinymce/content.css",
			template_external_list_url:"<?php echo site_url()?>/assets/css/tinymce/lists/template_list.js" ,
			external_link_list_url:"<?php echo site_url()?>/assets/css/tinymce/lists/link_list.js" ,
			external_image_list_url:"<?php echo site_url()?>/assets/css/tinymce/lists/image_list.js",
			media_external_list_url:"<?php echo site_url()?>/assets/css/tinymce/lists/media_list.js" ,
			style_formats: [
				{title:'Bold text',inline:'b'},
				{title:'Red text', inline:'span',styles:{color:'#ff0000'}},
				{title:'Red header',block:'h1',styles:{color:'#ff0000'}},
				{title:'Example 1',inline:'span',classes:'example1'},
				{title:'Example 2',inline:'span',classes:'example2'},
				{title:'Table styles'},
				{title:'Table row 1',selector:'tr',classes:'tablerow1'}
			],
			template_replace_values:{
				username:"Some user",
				staffid:"991234"
			},
			 relative_urls:false,
			 remove_script_host:false
			
		});
		
		
	});
	
</script>
</div>