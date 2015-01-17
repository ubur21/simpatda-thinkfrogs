<script src="<?php echo base_url()?>assets/script/tinymce/jscripts/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<link href="<?php echo base_url()?>assets/swfupload/default.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url()?>assets/swfupload/swfupload.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/swfupload/handlers.js" type="text/javascript"></script>
<?php

$title_form = explode('-',$title);
$title_form = trim($title_form['1']);

$data['id']='form_rapat';
$data['AUTOCOMPLETE']='off';
echo form_open_multipart('rapat/entri_rapat',$data);

$csslabel=$this->css->grid();

?>
<style>
.trigger {
	font-size:12px;
	min-width:300px;
}
.trigger :hover {
	cursor:pointer;	
}
</style>
<script type="text/javascript">
	var swfu;
	window.onload = function () {
		swfu = new SWFUpload({
			// Backend Settings
			upload_url: "go_upload",
			upload_url_ori: "go_upload",
			post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
			// File Upload Settings
			file_size_limit : "2 MB",
			//file_types : "*.jpg;*.gif;*.png;*.pdf",
			file_types : "*.*",
			file_upload_limit : "0",

			// Event Handler Settings - these functions as defined in Handlers.js
			//  The handlers are not part of SWFUpload but are part of my website and control how
			//  my website reacts to the SWFUpload events.
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_progress_handler : uploadProgress,
			upload_start_handler : uploadStart,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,

			// Button Settings
			/*button_image_url : "images/SmallSpyGlassWithTransperancy_17x18.png",
			button_placeholder_id : "spanButtonPlaceholder",
			button_width: 180,
			button_height: 18,
			button_text : '<span class="button">Select Images <span class="buttonSmall">(2 MB Max)</span></span>',
			button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
			button_text_top_padding: 0,
			button_text_left_padding: 18,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,*/
			
			button_image_url : "<?php echo base_url()?>assets/swfupload/XPButtonUploadText_61x22.png",
			//button_text : '<span class="button">Select Images <span class="buttonSmall">(2 MB Max)</span></span>',
			//button_text : 'Pilihx',
			
			//button_text : '<?php echo '<a id="tambah_detail" class="'.$this->css->button().'">Pilih File<span class="'.$this->css->iconsearch().'"></span></a>';?>',
			button_placeholder_id : "spanButtonPlaceholder",
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES, 
			button_cursor : SWFUpload.CURSOR.HAND, 
			button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_width: 61,
			button_height: 22,
			
			// Flash Settings
			flash_url : "<?php echo base_url()?>assets/swfupload/swfupload.swf",

			custom_settings : {
				upload_target : "divFileProgressContainer"
			},
			
			// Debug Settings
			debug:false
		});
	};
</script>
<?php
$rapat_no = isset($user_data['RAPAT_NO'])?$user_data['RAPAT_NO']:'';
$tema     = isset($user_data['TEMA'])?$user_data['TEMA']:'';
$lokasi   = isset($user_data['LOKASI'])?$user_data['LOKASI']:'';
$resume   = isset($user_data['RESUME'])?$user_data['RESUME']:'';
$tanggal  = isset($user_data['STAMP'])?date('d/m/Y',strtotime($user_data['STAMP'])):'';
$jam      = isset($user_data['STAMP'])?date('H:i',strtotime($user_data['STAMP'])):'';

?>
<table class="layout">
<tr>
<td><?php $this->load->view('rapat/menu'); ?></td>
<td>
	<div id="tabs" class="form">
		<ul>
			<li><a href="#tabs-spt">Pencatatan Rapat</a></li>
			<li><a href="#tabs-pengikut">Pencatatan Peserta & Upload</a></li>
		</ul>
		<div id="tabs-spt">
			<div id='error' class='error-message'></div>
			<fieldset id='sppd' class='form'>
				<div class="<?php echo $csslabel?>"><label for="norapat">No. Rapat</label><input type="text" name="rapat_no" value="<?php echo $rapat_no?>" id="rapat_no"  /><!-- readonly="readonly"--></div>
				<div class="<?php echo $csslabel?> multi"><label for="tgl_rapat">Tanggal & jam Rapat</label><input type="text" name="tgl_rapat" value="<?php echo $tanggal?>" id="tgl_rapat" size="11" /><input type='text' id='jam_rapat' value='<?php echo $jam?>' name='jam_rapat' size='4'></div>
				<div class="<?php echo $csslabel?>"><label for="subject">Tema</label><input type="text" name="subject" value="<?php echo $tema?>" id="subject" size="73" /></div>
				<div class="<?php echo $csslabel?>"><label for="lokasi">Lokasi</label><input type="text" name="lokasi" value="<?php echo $lokasi?>" id="lokasi" size="73" /></div>
				<div class="<?php echo $csslabel?>">
					<label for="resume">Notulen</label>
					<textarea name="deskripsi" cols="145" rows="15" class="text-editor" id="deskripsi"><?php echo $resume?></textarea>
				</div>
			</fieldset>
		</div>
		<div id='tabs-pengikut'>
			<div style='padding:10px'>
				<div class="trigger"><div class="<?php echo $this->css->bar();?>" style="padding:3px;width:760px"><?php echo form_label('Peserta','Peserta'); ?></div></div>
				<div class="toggle_aset_default">
					<fieldset>
						<legend></legend>
						<table id='grid_pengikut'></table>
						<div id='pager_pengikut'></div>
					</fieldset>
				</div>
				<div class="trigger"><div class="<?php echo $this->css->bar();?>" style="padding:3px;"><?php echo form_label('Upload','Upload'); ?></div></div>
				<div class="toggle_aset">
					<fieldset>
						<legend></legend>
						<!--<div style='padding:5px;'>
							<?php echo '<a id="tambah_detail" class="'.$this->css->button().'">Pilih File<span class="'.$this->css->iconsearch().'"></span></a>';?>
						</div>-->
						<span id="spanButtonPlaceholder"></span>
						<div id="divFileProgressContainer"></div>
						<table id='grid_upload'></table>
						<div id='pager_upload'></div>
					</fieldset>
				</div>
			</div>
		</div>
		
		<input type='hidden' name='parm' id='parm' value='<?php echo $value_parm?>'>
		<input type='hidden' name='edit' id='edit' value='<?php echo $status_edit?>'>			
		
		<div class="buttons">				
			<a class="<?php echo $this->css->button();?>" name='simpan' id='simpan' title='Simpan'>Simpan
			<span class="<?php echo $this->css->iconsave();?>"></span></a>
			<a class="<?php echo $this->css->button();?>" name='batal' id='batal' title='<?php echo $title_batal?>'><?php echo $label_batal?>
			<span class="<?php echo $this->css->iconclose();?>"></span></a>
		</div>
	</div>
</td>
</tr>
</table>
<script>

var validation = function(){
	//$('#form_sppd').validate({ errorLabelContainer: "#error", wrapper: "li", rules: { tanggal_spt: "required", pejabat_pemberi: "required" } });		
	//alert('before submit');
}

function start_submit()
{
	func_submit();
}

function func_submit()
{
	var frmObj = document.getElementById('form_rapat');
	jQuery(frmObj).ajaxSubmit({
		dataType:'json', 
		beforeSubmit:showRequest,
		success: function(data){
			if(data.errors=='' && data.parm!=''){
				$('#parm').val(data.parm);
				var old_url = $('#grid_pengikut').getGridParam('url');
				var new_url = old_url+'/'+$('#parm').val();
				$('#grid_pengikut').setGridParam({url:new_url,editurl:new_url}).trigger('reloadGrid');
				
				var old_url = $('#grid_upload').getGridParam('url');
				var new_url = old_url+'/'+$('#parm').val();
				$('#grid_upload').setGridParam({url:new_url}).trigger('reloadGrid');
			}else{
				alert(data.errors);
			}
			if(!data.state){
				$("#tabs").tabs('select',1);
				$('#simpan').html('Selesai<span class="<?php echo $this->css->iconsave();?>"></span>');
				jQuery('#simpan').attr('title','Selesai');
			}
		}
	});
}

function showRequest(formData, jqForm, options) {	
	var ed = tinyMCE.get('deskripsi');	
	for(var key in formData) {
		if(formData[key]['name']=='deskripsi') {
			formData[key]['value']=ed.getContent();
		}
	}
	var queryString = $.param(formData);
	return true;
}

var refresh_upload = function(){
	jQuery('#grid_upload').trigger('reloadGrid');
}

jQuery(document).ready(function()
{
	$("#tabs").tabs();
	
	$('#tgl_rapat').datepicker({changeMonth: true, changeYear: true});
	
	$(".toggle_aset").hide();

	$(".trigger").click(function(){ $(this).toggleClass("active").next().slideToggle("high"); });
			
	$('#simpan').hover(
		function() { $(this).addClass("<?php echo $this->css->hover();?>"); },
		function() { $(this).removeClass("<?php echo $this->css->hover();?>"); }
	);
	
	$('#simpan').click(function(){
		var frmObj = document.getElementById('form_rapat');
		if($(this).attr('title')=='Selesai'){
			if($('#edit').val()!=''){
				location.href = "<?php echo site_url('rapat/daftar_rapat'); ?>";
			}else{
				$(frmObj).resetForm();
				//$.get('<?php echo site_url('rapat/set_no_rapat')?>',function(result){$('#rapat_no').val(result)});
				$('#simpan').html('Simpan<span class="<?php echo $this->css->iconsave();?>"></span>');
				jQuery('#simpan').attr('title','Simpan');
				$('#grid_pengikut').setGridParam({url:'<?php echo site_url('rapat/daftar_peserta')?>',editurl:'<?php echo site_url('rapat/daftar_peserta')?>'}).trigger('reloadGrid');
				$('#grid_upload').setGridParam({url:'<?php echo site_url('rapat/daftar_file')?>'}).trigger('reloadGrid');
				$("#tabs").tabs('select',0);
			}			
		}else{
			start_submit();
		}
	});	

	$('#batal').hover(
		function() { $(this).addClass("<?php echo $this->css->hover();?>"); },
		function() { $(this).removeClass("<?php echo $this->css->hover();?>"); }
	);
	
	$('#batal').click(function(){		
		if($(this).attr('title')=='Kembali'){
			location.href = "<?php echo site_url('rapat'); ?>";
		}else{		
			var parm = $('#parm').val(); var frmObj = document.getElementById('form_spt');		
			if(parm!='' && $('#edit').val()==''){
				//$.get("<?php echo site_url('sppd/batal_spt')?>/"+parm);
				var data = {}; data.id=parm;
				$.post("<?php echo site_url('sppd/batal_spt')?>",data);
				$('#simpan').html('Simpan<span class="<?php echo $this->css->iconsave();?>"></span>');				
				$('#grid_pengikut').setGridParam({url:'<?php echo site_url('sppd/daftar_pengikut')?>'}).trigger('reloadGrid');
				$.get('<?php echo site_url('sppd/set_no_spt')?>',function(result){$('#spt_no').val(result)});
			}
			$(frmObj).resetForm();
			$('#edit').val('');
			$("#tabs").tabs('select',0);
			jQuery('#simpan').attr('title','Simpan');
		}				
	});
	
	jQuery("#grid_pengikut").jqGrid({
		 url:'<?php echo site_url('rapat/daftar_peserta')?>',
		 editurl:'<?php echo site_url('rapat/daftar_peserta')?>',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Rapat','Nama','Jabatan','Divisi','Instansi','Email','HP'],
		 colModel :[
			{ name:'id' ,index:'id',search:false },
			{ name:'idfk',index:'idfk',hidden:true },
			{ name:'nama',index:'nama',width:150,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'jabatan',index:'jabatan',width:100,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'divisi',index:'divisi',width:100,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'instansi',index:'instansi',width:100,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'email',index:'email',width:100,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'hp',index:'hp',width:100,editable:true,edittype:'text', editoptions: {size:40} },
		 ],
		pager: jQuery('#pager_pengikut'),
		height: 230,
		rowNum:10, 
		rowList:[10,20,30], 
		rownumbers: true,
		multiselect:true,
		multiboxonly: true,
		altRows:true,
		shrinkToFit:false,
		sortname: 'nama_pegawai',
		sortorder: 'asc',
		viewrecords: true,
		caption: '',
		onSelectRow: function(id){ },
        gridComplete: function(){ jQuery("#grid_pengikut").setGridWidth(760); return true; }			
	}).navGrid(
		'#pager_pengikut',
		{ add:true,edit:true,del:true},
		{ width:600}, // edit,afterSubmit:processEdit,bSubmit:"Ubah",bCancel:"Tutup",		
		{ width:600}, // add,afterSubmit:processAdd,bSubmit:"Tambah",bCancel:"Tutup",,reloadAfterSubmit:false		
		{ reloadAfterSubmit:false}, // del,afterSubmit:processDelete
		{}
	).hideCol(['id']);
	
	jQuery("#grid_upload").jqGrid({
		 url:'<?php echo site_url('rapat/daftar_file')?>',
		 editurl:'<?php echo site_url('rapat/daftar_file')?>',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Rapat','Nama File'],
		 colModel :[
			{ name:'id' ,index:'id',search:false },
			{ name:'idfk',index:'idfk',hidden:true },
			{ name:'nama',index:'nama',width:350}
		 ],
		pager: jQuery('#pager_upload'),
		height: 230,
		rowNum:5, 
		rowList:[5,10,15], 
		rownumbers: true,
		multiselect:true,
		multiboxonly: true,
		altRows:true,
		shrinkToFit:false,
		sortname: 'nama_file',
		sortorder: 'asc',
		viewrecords: true,
		caption: '',
		onSelectRow: function(id){ },
        gridComplete: function(){ jQuery("#grid_upload").setGridWidth(760); return true; }
	}).navGrid(
		'#pager_upload',
		{ add:false,edit:false,del:true},
		{ reloadAfterSubmit:false}, // del,afterSubmit:processDelete
		{}
	).hideCol(['id']);
			
	window.onbeforeunload = null;
	
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