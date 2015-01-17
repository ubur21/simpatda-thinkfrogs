<?php

$js = 'onBlur="validateDate(this)"';

$js2 = 'onBlur="validateDate(this)"';
$js3 = 'onBlur="validateDate(this)"';

//$data['spt_no']['value']=$user_data['nomor_spt'];

$title_form = explode('-',$title);
$title_form = trim($title_form['1']);

 
echo form_open("sppd/entri_spt","id='form_spt' AUTOCOMPLETE='off'")."\n";

?>

<table class="layout">
<tr>
<td><?php $this->load->view('sppd/menu'); ?></td>
<td>
<div>

	 <div style='float:left;margin-left:5px;margin-top:5px;'>
		<div id="tabs" class="form">
			<h1><?php echo $title_form?></h1>
			<ul>
				<li><a href="#tabs-spt">Formulir SPT</a></li>
				<li><a href="#tabs-pengikut">Formulir Peserta</a></li>
			</ul>
			<div id="tabs-spt">
				<div id='error' class='error-message'></div>
				<fieldset id='sppd' class='form'>
						
<?php 
					//<css
					$csslabel=$this->css->grid();
					//>
					
					$data['spt_no']['name']    = $data['spt_no']['id'] = 'spt_no';
					$data['spt_no']['title']   = 'Kolom Nomor harus terisi';
					$data['spt_no']['readonly']= 'readonly';
					$data['spt_no']['value']   = isset($user_data['SPT_NO']) ? $user_data['SPT_NO'] : $user_data['nomor_spt'];
					
					echo 
					 '<div class="'.$csslabel.'">'.form_label('No. SPT','spt_no').form_input($data['spt_no']).'</div>'."\n";
					 
					$data['tanggal_spt']['name'] = $data['tanggal_spt']['id'] = 'tanggal_spt';
					$data['tanggal_spt']['title']= 'Kolom Tanggal harus terisi';
					$data['tanggal_spt']['size'] = '11';
					$data['tanggal_spt']['value']= isset($user_data['TANGGAL']) ? date('d/m/Y',strtotime($user_data['TANGGAL'])) : date('d/m/Y');
					echo
					 '<div class="'.$csslabel.' multi">'.form_label('Tanggal SPT','tanggal_spt').form_input($data['tanggal_spt']).'</div>'."\n";

					$data['pejabat_pemberi']['name'] = $data['pejabat_pemberi']['id'] = 'pejabat_pemberi';
					$data['pejabat_pemberi']['title']= 'Pejabat Pemberi Perintah harus terisi';
					$data['pejabat_pemberi']['size'] = '66';
					$data['pejabat_pemberi']['value']= isset($user_data['NAMA_PEGAWAI_PEMBERI']) ? $user_data['NAMA_PEGAWAI_PEMBERI'] : '';

					$data['id_pejabat_pemberi']['name']= $data['id_pejabat_pemberi']['id'] = 'id_pejabat_pemberi';
					$data['id_pejabat_pemberi']['type']='hidden';
					$data['id_pejabat_pemberi']['value']= isset($user_data['ID_PEGAWAI_PEMBERI']) ? $user_data['ID_PEGAWAI_PEMBERI'] : '';
					echo		
					 '<div class="'.$csslabel.'">'.form_label('Pejabat Pemberi Perintah','pejabat_pemberi').form_input($data['id_pejabat_pemberi']).form_input($data['pejabat_pemberi']).'</div>'."\n";
					 
					$data['jabatan_pejabat']['name'] = $data['jabatan_pejabat']['id'] = 'jabatan_pejabat';
					$data['jabatan_pejabat']['size'] = '66'; $data['jabatan_pejabat']['readonly'] = 'readonly';
					$data['jabatan_pejabat']['value']= isset($user_data['JABATAN_STRUKTURAL_PEMBERI']) ? $user_data['JABATAN_STRUKTURAL_PEMBERI'] : (isset($user_data['JABATAN_FUNGSIONAL_PEMBERI']) ? $user_data['JABATAN_FUNGSIONAL_PEMBERI'] : '') ;
					
					echo
					'<div class="'.$csslabel.'">'.form_label('Jabatan','jabatan').form_input($data['jabatan_pejabat']).'</div>'."\n";
					
					$data['pegawai_penerima']['name'] = $data['pegawai_penerima']['id'] = 'pegawai_penerima';
					$data['pegawai_penerima']['title']= 'Pegawai Penerima Perintah harus terisi';
					$data['pegawai_penerima']['size'] ='66';
					$data['pegawai_penerima']['value']= isset($user_data['NAMA_PEGAWAI_PENERIMA']) ? $user_data['NAMA_PEGAWAI_PENERIMA'] : '';					
					
					$data['id_pegawai_penerima']['name']= $data['id_pegawai_penerima']['id'] = 'id_pegawai_penerima';
					$data['id_pegawai_penerima']['type']='hidden';
					$data['id_pegawai_penerima']['value']= isset($user_data['ID_PEGAWAI_PENERIMA']) ? $user_data['ID_PEGAWAI_PENERIMA'] : '';
					
					echo
					 '<div class="'.$csslabel.'">'.form_label('Pegawai Penerima Perintah','pegawai_penerima').form_input($data['id_pegawai_penerima']).form_input($data['pegawai_penerima']).'</div>'."\n";
					 
					$data['jabatan_pegawai']['name'] = $data['jabatan_pegawai']['id'] = 'jabatan_pegawai';
					$data['jabatan_pegawai']['size'] = '66'; $data['jabatan_pegawai']['readonly'] = 'readonly';
					$data['jabatan_pegawai']['value']= isset($user_data['JABATAN_STRUKTURAL_PENERIMA']) ? $user_data['JABATAN_STRUKTURAL_PENERIMA'] : (isset($user_data['JABATAN_FUNGSIONAL_PENERIMA']) ? $user_data['JABATAN_FUNGSIONAL_PENERIMA'] : '');
					
					echo
					'<div class="'.$csslabel.'">'.form_label('Jabatan','jabatan').form_input($data['jabatan_pegawai']).'</div>'."\n";					

					$data['dari']['name'] = $data['dari']['id'] = 'dari';
					$data['dari']['title']= 'Kolom Dari harus terisi';
					$data['dari']['size'] = '66';
					$data['dari']['value']= isset($user_data['DARI']) ? $user_data['DARI'] : '';
					
					 echo
					 '<div class="'.$csslabel.'">'.form_label('Dari','dari').form_input($data['dari']).'</div>'."\n";

					$data['tujuan']['name'] = $data['tujuan']['id'] = 'tujuan';
					$data['tujuan']['title']= 'Kolom Tujuan harus terisi';
					$data['tujuan']['size'] = '66';
					$data['tujuan']['value']= isset($user_data['TUJUAN']) ? $user_data['TUJUAN'] : '';
					
					 echo
					 '<div class="'.$csslabel.'">'.form_label('Tujuan','tujuan').form_input($data['tujuan']).'</div>'."\n";
					 
					$data['dari_tanggal']['name'] = $data['dari_tanggal']['id'] = 'dari_tanggal';
					$data['dari_tanggal']['title']= 'Kolom Tanggal Berangkat harus terisi';
					$data['dari_tanggal']['size'] = '11';
					$data['dari_tanggal']['value']= isset($user_data['TANGGAL_BERANGKAT']) ? date('d/m/Y',strtotime($user_data['TANGGAL_BERANGKAT'])) : '';
					
					 echo
					 '<div class="'.$csslabel.'">'.form_label('dari tanggal ','dari_tanggal').form_input($data['dari_tanggal']).'</div>'."\n";
					 
					$data['sd_tanggal']['name'] = $data['sd_tanggal']['id'] = 'sd_tanggal';
					$data['sd_tanggal']['title']= 'Kolom Tanggal Pulang harus terisi';
					$data['sd_tanggal']['size'] = '11';
					$data['sd_tanggal']['value']= isset($user_data['TANGGAL_PULANG']) ? date('d/m/Y',strtotime($user_data['TANGGAL_PULANG'])) : '';
					
					 echo
					 '<div class="'.$csslabel.' multi">'.form_label('s/d tanggal','sd_tanggal').form_input($data['sd_tanggal']).'</div>'."\n";
					 
					$data['kendaraan']['name'] = $data['kendaraan']['id'] = 'kendaraan';
					$data['kendaraan']['title']= 'Kolom Kendaraan harus terisi';
					$data['kendaraan']['size'] ='66';
					$data['kendaraan']['value']= isset($user_data['KENDARAAN']) ? $user_data['KENDARAAN'] : '';
					 echo
					 '<div class="'.$csslabel.'">'.form_label('Kendaraan','kendaraan').form_input($data['kendaraan']).'</div>';
					 
					$data['maksud']['name'] = $data['maksud']['id'] = 'maksud';
					$data['maksud']['title']= 'Kolom Maksud Perjalanan harus terisi';
					$data['maksud']['rows'] = '3';
					$data['maksud']['cols'] = '63';
					$data['maksud']['value']= isset($user_data['MAKSUD']) ? $this->db->getBlob($user_data['MAKSUD']) : '';
					
					 echo
					 '<div class="'.$csslabel.'">'.form_label('Maksud Mengadakan Perjalanan','maksud').form_textarea($data['maksud']).'</div>';
					 											 
					 //'<div>'.form_label('Pasal Anggaran','pasal').form_input($data['pasal']).'</div>';
?>
					<input type='hidden' name='parm' id='parm' value='<?php echo $value_parm?>'>
					<input type='hidden' name='edit' id='edit' value='<?php echo $status_edit?>'>
				</fieldset>

			</div>
			<div id="tabs-pengikut">				
				<table id='grid_pengikut'></table>
				<div id='pager_pengikut'></div>
			</div>
			
				<div class="buttons">
					<a class="<?php echo $this->css->button();?>" name='simpan' id='simpan' title='Simpan'>Simpan
					<span class="<?php echo $this->css->iconsave();?>"></span></a>
					<a class="<?php echo $this->css->button();?>" name='batal' id='batal' title='<?php echo $title_batal?>'><?php echo $label_batal?>
					<span class="<?php echo $this->css->iconclose();?>"></span></a>
<?php 
/* revision for new ui
$data = array(
		  'name'        => 'simpan',
		  'id'          => 'simpan',
		  'type'		=> 'button',
		  'value'       => 'Simpan',
		  'size'        => '80',
         );
echo form_input($data);
*/

$data['submit']['name'] = 'submit';
$data['submit']['id'] = 'button-login';
$data['submit']['type'] = 'submit';
$data['submit']['content'] = 'Login';

//echo form_button($data['submit']);
/* revision for new ui
$data = array(
    'name' => 'batal',
    'id' => 'batal',
    'value' => 'batal',
    'type' => 'reset',
    'content' => 'Batal'
);

echo form_button($data);
*/
?>					
					</div>
		</div>
	</div>
	<!--<input type='button' id='seek_pejabat' value='*'>-->
<?php form_close(); ?>

</div>
</td>
</tr>
</table>

<div id="dialog" title="Pilih Pegawai" style='display:none'>
	<table id='grid_pegawai'></table>
	<div id='pager_pegawai'></div>
	<div style='float:right;padding:5px 0px;'>
		<input type='button' id='btn_pilih' value='Pilih'>
		<input type='button' id='btn_tutup' value='Tutup'>
	</div>
</div>

<script>

var lastsel,lastsel2,lastsel3;

function setPeserta(iurl)
{
	jQuery.get(iurl,function(result){
		jQuery('input#pegawai').attr('value',result.id);
		jQuery('input#nip_pegawai').attr('value',result.nip);
		jQuery('input#nama_pegawai').attr('value',result.nama);		
	},'json');
}

function start_submit()
{
	var id   = jQuery('#id_pegawai_penerima').val();
	var tgl1 = jQuery('#dari_tanggal').val();
	var tgl2 = jQuery('#sd_tanggal').val();
	
	if(id!='' && tgl1!='' && tgl2!=''){
		var data = {}; data.tgl1=tgl1; data.tgl2=tgl2; data.penerima=id;
		jQuery.post('<?php echo site_url('sppd/idle'); ?>',data,start_callback,'json');		
	}else{
		return false;
	}
}

function start_callback(result){
	if(!result.idle){
		$.gritter.add({ title: result.title, text: result.message });
		return false;
	}else{
		func_submit();
	}
}

var validation = function(){
	//$('#form_sppd').validate({ errorLabelContainer: "#error", wrapper: "li", rules: { tanggal_spt: "required", pejabat_pemberi: "required" } });		
	//alert('before submit');
}

function func_submit()
{
	var frmObj = document.getElementById('form_spt');
	jQuery(frmObj).ajaxSubmit({
		dataType:'json', 
		beforeSubmit:validation,
		success: function(data){
			if(data.errors=='' && data.parm!=''){
				$('#parm').val(data.parm);
				var old_url = $('#grid_pengikut').getGridParam('url');
				var new_url = old_url+'/'+$('#parm').val();
				$('#grid_pengikut').setGridParam({url:new_url}).trigger('reloadGrid');
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

jQuery(document).ready(function()
{

	$("#tabs").tabs();
	
	$('#tanggal_spt').datepicker({changeMonth: true, changeYear: true});
	$('#dari_tanggal').datepicker({changeMonth: true, changeYear: true});
	$('#sd_tanggal').datepicker({changeMonth: true, changeYear: true});
		
	$('#seek_pejabat').click(function(){ $('#dialog').dialog('open'); });
	
	$("#dialog").dialog({
		bgiframe: true, resizable: false, height:385, width:550, modal: true, autoOpen: false,
		//buttons: { 'Tutup': function() { $(this).dialog('close'); } }
	});
	
	$('#simpan').hover(
		function() { $(this).addClass("<?php echo $this->css->hover();?>"); },
		function() { $(this).removeClass("<?php echo $this->css->hover();?>"); }
	);
	
	$('#simpan').click(function(){
		var frmObj = document.getElementById('form_spt');
		if($(this).attr('title')=='Selesai'){
			if($('#edit').val()!=''){
				location.href = "<?php echo site_url('sppd/daftar_spt'); ?>";
			}else{
				$(frmObj).resetForm();
				$.get('<?php echo site_url('sppd/set_no_spt')?>',function(result){$('#spt_no').val(result)});
				$('#simpan').html('Simpan<span class="<?php echo $this->css->iconsave();?>"></span>');
				jQuery('#simpan').attr('title','Simpan');
				$('#grid_pengikut').setGridParam({url:'<?php echo site_url('sppd/daftar_pengikut')?>'}).trigger('reloadGrid');						
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
			location.href = "<?php echo site_url('sppd/daftar_spt'); ?>";
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
	
	$('#form_spt').ajaxForm(function() {
		alert("Thank you for your comment!");
    });
	
	$('#form_spt').submit(function() {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
				alert(data);
			}
		})
		return false;
	});
	
	$("#pejabat_pemberi").autocomplete("<?php echo site_url('pegawai/get_nama')?>", {
		width: 340, scrollHeight: 220, scroll: true, scrollHeight: 300,	selectFirst: false
	});
	
	$("#pejabat_pemberi").result(function(event, data, formatted) {	
		if(data){
			jQuery('#pejabat_pemberi').val(data[0]);
			tmp = data[1].split('#');
			jQuery('#id_pejabat_pemberi').val(tmp[0]);
			jQuery('#jabatan_pejabat').val(tmp[1]);
		}
	});	
	
	$("#pegawai_penerima").autocomplete("<?php echo site_url('pegawai/get_nama')?>", {
		width: 340, scrollHeight: 220, scroll: true, scrollHeight: 300,	selectFirst: false
	});	
	
	$("#pegawai_penerima").result(function(event, data, formatted) {	
		if(data){
			jQuery('#pegawai_penerima').val(data[0]);
			tmp = data[1].split('#');
			jQuery('#id_pegawai_penerima').val(tmp[0]);
			jQuery('#jabatan_pegawai').val(tmp[1]);
		}
	});
	
	$("#dari").autocomplete("<?php echo site_url('master/lokasi/get_lokasi')?>", {
		width: 340, scrollHeight: 220, scroll: true, scrollHeight: 300,	selectFirst: false
	});
	
	$("#dari").result(function(event, data, formatted) {
		if(data){
			jQuery('#dari').val(data[0]);
		}
	});	
	
	$("#tujuan").autocomplete("<?php echo site_url('master/lokasi/get_lokasi')?>", {
		width: 340, scrollHeight: 220, scroll: true, scrollHeight: 300,	selectFirst: false
	});
	
	$("#tujuan").result(function(event, data, formatted) {
		if(data){ jQuery('#tujuan').val(data[0]); }
	});		
		
	$('#btn_tutup').click(function(){ $('#dialog').dialog('close'); });
	
	jQuery("#grid_pegawai").jqGrid({ 
		url:'<?php echo site_url('pegawai/get_daftar')?>', 
		editurl:'<?php echo site_url('pegawai')?>', 
		datatype: "json", 
		mtype: 'POST',
		colNames:['ID', 'NIP', 'Nama'], 
		colModel:[ 
			{name:'id',index:'id_pegawai', search:false, hidden:true}, 
			{name:'nip',index:'nip', width:25, align:"left", editable:true, edittype:'text'},
			{name:'nama',index:'nama_pegawai', width:60, align:"left"},
		], 
		rowNum:10, 
		rowList:[10,20,30], 
		rownumbers: true,
		pager: '#pager_pegawai', 
		sortname: 'a.nip', 
		sortorder: "asc", 
		viewrecords: true, 
		gridview: true,
		multiselect: true,
		multiboxonly: true,
		width:530,
		height:230,
		ondblClickRow: function(id){ 
			location.href = "<?php echo site_url('pegawai/edit/')."/"; ?>" + id;
		}
	}).navGrid('#pager_pegawai',{edit:false,add:false,del:false,add:false});
		
	jQuery("#grid_pengikut").jqGrid({
		 url:'<?php echo site_url('sppd/daftar_pengikut')?>',
		 editurl:'<?php echo site_url('sppd/daftar_pengikut')?>',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','idfk','pegawai','NIP','Nama'],
		 colModel :[
			{ name:'id' ,index:'id',search:false },
			{ name:'idfk',index:'idfk',hidden:true,editable:true },
			{ name:'pegawai',index:'pegawai',hidden:true,editable:true },
			{ name:'nip_pegawai',index:'nip_pegawai',width:150,editable:true,edittype:'text', editoptions: {size:40} },
			{ name:'nama_pegawai',index:'nama_pegawai',width:300,editable:true,edittype:'text',editrules:{required:true}, editoptions: {size:40} },
			//{ name:'jabatan',index:'jabatan',width:80,editable:true,edittype:'text',editrules:{required:true}, editoptions: {size:40},readonly:true },
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
        gridComplete: function(){ jQuery("#grid_pengikut").setGridWidth(530); return true; }	
		
	}).navGrid(
		'#pager_pengikut',
		{ add:true,edit:true,del:true},
		{ width:600,beforeSubmit:func_before,
			afterShowForm:function(){
				jQuery('input#nip_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nip')?>/'+this.value); } );
				jQuery('input#nama_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nama/')?>/'+this.value); } );
				return [true]
			},				
		}, // edit,afterSubmit:processEdit,bSubmit:"Ubah",bCancel:"Tutup",
		
		{ width:600,beforeSubmit:func_before,
			afterShowForm:function(){
				jQuery('input#nip_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nip')?>/'+this.value); } );
				jQuery('input#nama_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nama/')?>/'+this.value); } );
				return [true]
			},		
		}, // add,afterSubmit:processAdd,bSubmit:"Tambah",bCancel:"Tutup",,reloadAfterSubmit:false
		
		{ reloadAfterSubmit:false}, // del,afterSubmit:processDelete
		{}
	).hideCol(['id']);
	
	function func_before(a,b){
		a.idfk = $('#parm').val();
		return ['true','true'];
	}
			
});
</script>



<?php //$this->output->enable_profiler(TRUE); ?>