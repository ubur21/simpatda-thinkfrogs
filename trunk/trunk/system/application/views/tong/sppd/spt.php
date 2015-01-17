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

jQuery(document).ready(function()
{

	$("#tabs").tabs();
	
	$('#tanggal_spt').datepicker({changeMonth: true, changeYear: true});
	$('#dari_tanggal').datepicker({changeMonth: true, changeYear: true});
	$('#sd_tanggal').datepicker({changeMonth: true, changeYear: true});
	
	var validation = function(){
		//$('#form_sppd').validate({ errorLabelContainer: "#error", wrapper: "li", rules: { tanggal_spt: "required", pejabat_pemberi: "required" } });		
		//alert('before submit');
	}
	
	$('#seek_pejabat').click(function(){
		$('#dialog').dialog('open');
	});
	
	$("#dialog").dialog({
		bgiframe: true,
		resizable: false,
		height:385,
		width:550,
		modal: true,
		autoOpen: false,
		//buttons: { 'Tutup': function() { $(this).dialog('close'); } }
	});
	
	$('#simpan').hover(
		function() {
			$(this).addClass("<?php echo $this->css->hover();?>");
		},
		function() {
			$(this).removeClass("<?php echo $this->css->hover();?>");
		}
	);
	
	$('#simpan').click(function(){
		var frmObj = document.getElementById('form_spt');
		if($(this).val()=='Selesai'){			
			$(frmObj).resetForm(); 
			$.get('<?php echo site_url('sppd/set_no_spt')?>',function(result){$('#spt_no').val(result)});
			$('#simpan').html('Simpan<span class="<?php echo $this->css->iconsave();?>"></span>');
			$('#grid_pengikut').setGridParam({url:'<?php echo site_url('sppd/daftar_pengikut')?>'}).trigger('reloadGrid');
		}else{
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
						$('#simpan').html('Selesai<span class="<?php echo $this->css->iconsave();?>"></span>');
					}
				}
			});
		}
	});
	
	$('#batal').hover(
		function() {
			$(this).addClass("<?php echo $this->css->hover();?>");
		},
		function() {
			$(this).removeClass("<?php echo $this->css->hover();?>");
		}
	);
	
	$('#batal').click(function(){
		var parm = $('#parm').val(); var frmObj = document.getElementById('form_spt');		
		if(parm!='' && $('#edit').val()==''){
			//$.get("<?php echo site_url('sppd/batal_spt')?>/"+parm);
			var data = {}; data.id=parm;
			$.post("<?php echo site_url('sppd/batal_spt')?>",data);
			$('#simpan').val('Simpan');
			$('#grid_pengikut').setGridParam({url:'<?php echo site_url('sppd/daftar_pengikut')?>'}).trigger('reloadGrid');
			$.get('<?php echo site_url('sppd/set_no_spt')?>',function(result){$('#spt_no').val(result)});
		}else{
			$(frmObj).resetForm();
		}
		$('#edit').val('');
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
		width: 110, scrollHeight: 220, scroll: true, scrollHeight: 300,	selectFirst: false
	});
	
	$("#pejabat_pemberi").result(function(event, data, formatted) {	
		if(data){
			jQuery('#pejabat_pemberi').val(data[0]);
			jQuery('#id_pejabat_pemberi').val(data[1]);
		}
	});	
	
	$("#pegawai_penerima").autocomplete("<?php echo site_url('pegawai/get_nama')?>", {
		width: 110, scrollHeight: 220, scroll: true, scrollHeight: 300,	selectFirst: false
	});	
	
	$("#pegawai_penerima").result(function(event, data, formatted) {	
		if(data){
			jQuery('#pegawai_penerima').val(data[0]);
			jQuery('#id_pegawai_penerima').val(data[1]);
		}
	});		

	
	/*jQuery('#form_spt').ajaxSubmit({
		success: function(response){
			alert(response);
		}
	});*/
	
	$('#btn_tutup').click(function(){
		$('#dialog').dialog('close');
	});
	
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
		width: 530,
		height: 230,
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
			{ name:'nama_pegawai',index:'nama_pegawai',width:200,editable:true,edittype:'text',editrules:{required:true}, editoptions: {size:40} },
			//{ name:'jabatan',index:'jabatan',width:80,editable:true,edittype:'text',editrules:{required:true}, editoptions: {size:40},readonly:true },
		 ],
		pager: jQuery('#pager_pengikut'),
		height:110,
		rowNum:5,
		rowList:[5,10,15],
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
		
		}, // edit,afterSubmit:processEdit,bSubmit:"Ubah",bCancel:"Tutup",
		
		{ width:600,beforeSubmit:func_before,
			afterShowForm:function(){
				jQuery('input#nip_pegawai').blur(
					function(){
						setPeserta('<?php echo site_url('sppd/info_pengikut/nip')?>/'+this.value);
					}
				);
				jQuery('input#nama_pegawai').blur(
					function(){
						setPeserta('<?php echo site_url('sppd/info_pengikut/nama/')?>/'+this.value);
					}
				);
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
		
	jQuery("#grid_daftar").jqGrid({
		url:'<?php echo site_url('sppd/daftar_spt')?>',
		editurl:'<?php echo site_url('sppd/daftar_spt')?>',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','No. SPT','Tanggal','Maksud Penugasan','Dari','Tujuan','Tgl. Berangkat','Tgl. Pulang','Kendaraan','Nama Pemberi','nb','Nama Penerima','np'],
		colModel :[
			{ name:'id' ,index:'id',search:false},
			{ name:'nomor',index:'nomor',width:80},
			{ name:'tanggal',index:'tanggal',width:80},
			{ name:'maksud',index:'maksud',width:110},
			{ name:'dari',index:'dari',width:100},
			{ name:'tujuan',index:'tujuan',width:100},
			{ name:'berangkat',index:'berangkat',width:85},
			{ name:'pulang',index:'pulang',width:70},
			{ name:'kendaraan',index:'kendaraan',width:100},
			{ name:'pemberi',index:'pemberi',width:135},
			{ name:'idpemberi',index:'idpemberi',hidden:true},
			{ name:'penerima',index:'penerima',width:135},
			{ name:'idpenerima',index:'idpenerima',hidden:true},
		],
		pager: jQuery('#pager_daftar'),
		height:200,
		width:600,
		rowNum:10,
		rownumbers:true,
		rowList:[10,15,30],
		shrinkToFit:false,
		sortname: 'a.nurut',
		sortorder: 'asc',
		viewrecords: true,
		onSelectRow:function(id){ 
			if(id && id!==lastsel3){
				var row = jQuery("#grid_daftar").getRowData(id);				
				$('#spt_no').val(row.nomor); $('#tanggal_spt').val(row.tanggal); $('#parm').val(row.id);
				$('#maksud').val(row.maksud); $('#dari').val(row.dari); $('#tujuan').val(row.tujuan);
				$('#dari_tanggal').val(row.berangkat); $('#sd_tanggal').val(row.pulang); $('#kendaraan').val(row.kendaraan);
				$('#pejabat_pemberi').val(row.pemberi); $('#pegawai_penerima').val(row.penerima); $('#edit').val('true');
				$('#id_pejabat_pemberi').val(row.idpemberi); $('#id_pegawai_penerima').val(row.idpenerima);
				//jQuery("#tabs").tabs('select', 0);
				lastsel3=id;
			}
		},
		gridComplete: function(){ jQuery("#grid_daftar").setGridWidth(980); return true; }
	}).navGrid('#pager_daftar'
		,{add:false,edit:false,view:false}
		,{height:180, width:450} // edit
		,{height:180, width:450} // add
		,{} // del
		,{} // search        
	).hideCol(['id']);
	
});
</script>

<?php

$js = 'onBlur="validateDate(this)"';

$js2 = 'onBlur="validateDate(this)"';
$js3 = 'onBlur="validateDate(this)"';

$data['spt_no']['value']=$user_data['nomor_spt'];

 
echo form_open("sppd/entri_spt","id='form_spt' AUTOCOMPLETE='off'")."\n";

?>
	 <div style='float:left;margin-left:5px;margin-top:5px;'>
		<div id="tabs" class="form">
			<ul>
				<li><a href="#tabs-spt">SPT</a></li>
				<li><a href="#tabs-pengikut">Pengikut</a></li>
				<li><a href="#tabs-daftar">Daftar</a></li>
			</ul>
			<div id="tabs-spt">
				<div id='error' class='error-message'></div>
				<fieldset id='sppd' class='form'>
						
<?php 
					//<css
					$csslabel=$this->css->grid();
					//>
					
					$data['spt_no']['name'] = $data['spt_no']['id'] = 'spt_no';
					$data['spt_no']['title']= 'Kolom Nomor harus terisi';
					$data['spt_no']['readonly']='readonly';
					
					echo 
					 '<div class='.$csslabel.'>'.form_label('No. SPT','spt_no').form_input($data['spt_no']).'</div>'."\n";
					 
					$data['tanggal_spt']['name'] = $data['tanggal_spt']['id'] = 'tanggal_spt';
					$data['tanggal_spt']['title']= 'Kolom Tanggal harus terisi';
					echo
					 '<div class="'.$csslabel.' multi">'.form_label('Tanggal SPT','tanggal_spt').form_input($data['tanggal_spt']).'</div>'."\n";

					$data['pejabat_pemberi']['name'] = $data['pejabat_pemberi']['id'] = 'pejabat_pemberi';
					$data['pejabat_pemberi']['title']= 'Pejabat Pemberi Perintah harus terisi';

					$data['id_pejabat_pemberi']['name']= $data['id_pejabat_pemberi']['id'] = 'id_pejabat_pemberi';
					$data['id_pejabat_pemberi']['type']='hidden';
					echo		
					 '<div class='.$csslabel.'>'.form_label('Pejabat Pemberi Perintah','pejabat_pemberi').form_input($data['id_pejabat_pemberi']).form_input($data['pejabat_pemberi']).'</div>'."\n";

					$data['pegawai_penerima']['name'] = $data['pegawai_penerima']['id'] = 'pegawai_penerima';
					$data['pegawai_penerima']['title']= 'Pegawai Penerima Perintah harus terisi';
					
					$data['id_pegawai_penerima']['name']= $data['id_pegawai_penerima']['id'] = 'id_pegawai_penerima';
					$data['id_pegawai_penerima']['type']='hidden';
					
					echo
					 '<div class="'.$csslabel.' multi">'.form_label('Pegawai Penerima Perintah','pegawai_penerima').form_input($data['id_pegawai_penerima']).form_input($data['pegawai_penerima']).'</div>'."\n";

					$data['dari']['name'] = $data['dari']['id'] = 'dari';
					$data['dari']['title']= 'Kolom Dari harus terisi';
					 echo
					 '<div class='.$csslabel.'>'.form_label('Dari','dari').form_input($data['dari']).'</div>'."\n";

					$data['tujuan']['name'] = $data['tujuan']['id'] = 'tujuan';
					$data['tujuan']['title']= 'Kolom Tujuan harus terisi';
					 echo
					 '<div class="'.$csslabel.' multi">'.form_label('Ke','tujuan').form_input($data['tujuan']).'</div>'."\n";
					 
					$data['dari_tanggal']['name'] = $data['dari_tanggal']['id'] = 'dari_tanggal';
					$data['dari_tanggal']['title']= 'Kolom Tanggal Berangkat harus terisi';
					 echo
					 '<div class='.$csslabel.'>'.form_label('dari tanggal ','dari_tanggal').form_input($data['dari_tanggal']).'</div>'."\n";
					 
					$data['sd_tanggal']['name'] = $data['sd_tanggal']['id'] = 'sd_tanggal';
					$data['sd_tanggal']['title']= 'Kolom Tanggal Pulang harus terisi';
					 echo
					 '<div class="'.$csslabel.' multi">'.form_label('s/d tanggal','sd_tanggal').form_input($data['sd_tanggal']).'</div>'."\n";
					 
					$data['kendaraan']['name'] = $data['kendaraan']['id'] = 'kendaraan';
					$data['kendaraan']['title']= 'Kolom Kendaraan harus terisi';
					 echo
					 '<div class='.$csslabel.'>'.form_label('Kendaraan','kendaraan').form_input($data['kendaraan']).'</div>';

					$data['maksud']['name'] = $data['maksud']['id'] = 'maksud';
					$data['maksud']['title']= 'Kolom Maksud Perjalanan harus terisi';
					 echo
					 '<div class='.$csslabel.'>'.form_label('Maksud Mengadakan Perjalanan','maksud').form_input($data['maksud']).'</div>';
					 											 
					 //'<div>'.form_label('Pasal Anggaran','pasal').form_input($data['pasal']).'</div>';
?>
					<input type='hidden' name='parm' id='parm'>
					<input type='hidden' name='edit' id='edit'>
				</fieldset>
				<div class="buttons">
					<a class="<?php echo $this->css->button();?>" name='simpan' id='simpan' value='simpan'>Simpan
					<span class="<?php echo $this->css->iconsave();?>"></span></a>
					<a class="<?php echo $this->css->button();?>" name='batal' id='batal' value='batal'>Batal
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
			<div id="tabs-pengikut">				
				<table id='grid_pengikut'></table>
				<div id='pager_pengikut'></div>
			</div>
			<div id="tabs-daftar">
				<table id='grid_daftar'></table>
				<div id='pager_daftar'></div>
			</div>
		</div>
	</div>
	<!--<input type='button' id='seek_pejabat' value='*'>-->
<?php form_close(); ?>
<div id="dialog" title="Pilih Pegawai" style='display:none'>
	<table id='grid_pegawai'></table>
	<div id='pager_pegawai'></div>
	<div style='float:right;padding:5px 0px;'>
		<input type='button' id='btn_pilih' value='Pilih'>
		<input type='button' id='btn_tutup' value='Tutup'>
	</div>
</div>
<script>
jQuery(document).ready(function(){
	//$("#tanggal_spt").mask("9999/99/99");
});
</script>
<?php //$this->output->enable_profiler(TRUE); ?>