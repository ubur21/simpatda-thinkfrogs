<script>

var lastsel,lastsel2,lastsel3;

jQuery(document).ready(function(){
	$("#tabs").tabs();
	
	$('#tanggal_sppd').datepicker({changeMonth: true, changeYear: true});
	
	$('#spt_no').change(function(){
		
		$.get('<?php echo site_url('sppd/data_spt')?>/'+$(this).val(), function(rs){
			$('#tanggal_spt').val(rs.TANGGAL);
			$('#pejabat_pemberi').val(rs.NAMA_PEMBERI);
			$('#pegawai_penerima').val(rs.NAMA_PENERIMA);
			$('#dari').val(rs.DARI);
			$('#tujuan').val(rs.TUJUAN);
			$('#dari_tanggal').val(rs.TANGGAL_BERANGKAT);
			$('#sd_tanggal').val(rs.TANGGAL_PULANG);
			$('#kendaraan').val(rs.KENDARAAN);
			$('#maksud').val(rs.MAKSUD);
		},'json');
		
		//var old_url = $('#grid_pengikut').getGridParam('url');
		//var new_url = old_url+'/'+$('#parm').val();
		//$('#grid_pengikut').setGridParam({url:new_url}).trigger('reloadGrid');
		
	});

	var validation = function(){
		//$('#form_sppd').validate({ errorLabelContainer: "#error", wrapper: "li", rules: { tanggal_spt: "required", pejabat_pemberi: "required" } });		
		//alert('before submit');
	}	
	
	$('#simpan').hover(
		function() {
			$(this).addClass("<?php echo $this->css->hover();?>");
		},
		function() {
			$(this).removeClass("<?php echo $this->css->hover();?>");
		}
	);
	$('#simpan').click(function(){
		var frmObj = document.getElementById('form_sppd');
		if($(this).val()=='Selesai'){			
			$(frmObj).resetForm(); 
			$.get('<?php echo site_url('sppd/set_no_sppd')?>',function(result){$('#sppd_no').val(result)});
			$('#simpan').val('Simpan');
			$('#grid_biaya').setGridParam({url:'<?php echo site_url('sppd/daftar_biaya')?>'}).trigger('reloadGrid');
		}else{
			jQuery(frmObj).ajaxSubmit({
				dataType:'json', 
				beforeSubmit:validation,
				success: function(data){
					if(data.errors=='' && data.parm!=''){
						$('#parm').val(data.parm);
						var old_url = $('#grid_biaya').getGridParam('url');
						var new_url = old_url+'/'+$('#parm').val();
						$('#grid_biaya').setGridParam({url:new_url}).trigger('reloadGrid');
					}else{
						alert(data.errors);
					}
					if(!data.state){
						$('#simpan').val('Selesai');
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
		var parm = $('#parm').val(); var frmObj = document.getElementById('form_sppd');		
		if(parm!='' && $('#edit').val()==''){
			//$.get("<?php echo site_url('sppd/batal_spt')?>/"+parm);
			var data = {}; data.id=parm;
			$.post("<?php echo site_url('sppd/batal_sppd')?>",data);
			$('#simpan').val('Simpan');
			$('#grid_biaya').setGridParam({url:'<?php echo site_url('sppd/daftar_biaya')?>'}).trigger('reloadGrid');
			$.get('<?php echo site_url('sppd/set_no_sppd')?>',function(result){$('#sppd_no').val(result)});
		}else{
			$(frmObj).resetForm();
		}
		$('#edit').val('');
	});	
	
	jQuery('#trace').click(function(){
		var id = document.getElementById('Country');		
		alert(jQuery('#peserta').attr('id'));
	});
	
	jQuery("#grid_biaya").jqGrid({
		 url:'<?php echo site_url('sppd/daftar_biaya')?>', 
		 editurl:'<?php echo site_url('sppd/daftar_biaya')?>',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','idfk','Keperluan','Peserta','Hari','Nominal'],//
		 colModel :[
			{ name:'id' ,index:'id',search:false },
			{ name:'idfk',index:'idfk',hidden:true,editable:true },
			{ name:'keperluan',index:'keperluan',width:80,editable:true,edittype:'text',editrules:{required:true}, editoptions: {size:90} },
			{ name:'peserta',index:'peserta',width:80,editable:true,edittype:'select',editrules:{required:true},
			  editoptions: {
					//dataUrl:'<?php echo site_url('sppd/get_peserta')?>',
					value:{'':''},
					style:'width:300px;',
					multiple:true, size:3
				}
			},
			{ name:'hari',index:'hari',width:80,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},
			  editoptions: {size:10,maxlength:10 },
			},
			{ name:'nominal',index:'nominal',width:80,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},
			  editoptions:{size:35},formatter:'currency',
			  formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}
			}
		 ],
		pager: jQuery('#pager_biaya'),
		height:110,
		rowNum:5,
		rowList:[5,10,15],
		rownumbers: true,
		multiselect:true,
		multiboxonly: true,
		altRows:true,
		shrinkToFit:false,
		sortname: 'keperluan',
		sortorder: 'asc',
		viewrecords: true,
		caption: '',
        onSelectRow: function(id){ },
        gridComplete: function(){ jQuery("#grid_biaya").setGridWidth(530); return true; },
		loadComplete: function() {
			//$('select#Country').setColProp('Country', { editoptions: { value: "FE:FedEx; IN:InTime; TN:TNT" } });
		}
		
	}).navGrid(
		'#pager_biaya',
		{ add:true,edit:true,del:true},
		{ width:600,beforeSubmit:func_before,
		  beforeShowForm: function(a) {
			var data = {};
			data.keperluan = jQuery('#grid_biaya').getGridParam('selrow');
			data.spt = $('#spt_no').val();
			
			$('select#peserta').empty();
			
			$.post('<?php echo site_url('sppd/get_peserta')?>', data ,function(result){				
				$.each(result, function(val, text){
					$('select#peserta').append( new Option(text.peserta,text.id,text.selected) );
				});			
			},'json');
			
		  }
		}, // edit,afterSubmit:processEdit,bSubmit:"Ubah",bCancel:"Tutup",
		
		{ width:600,beforeSubmit:func_before,
		  beforeShowForm: function() { 		  
			var data = {};
			data.keperluan = '';
			data.spt = $('#spt_no').val();
			
			$('select#peserta').empty();
			
			$.post('<?php echo site_url('sppd/get_peserta')?>',data,function(result){
				
				$.each(result, function(val, text) {
					selected='';
					$('select#peserta').append( new Option(text.peserta,text.id,selected) );
				});
			
			},'json');
			
		  }
		}, // add,afterSubmit:processAdd,bSubmit:"Tambah",bCancel:"Tutup",,reloadAfterSubmit:false
		{ reloadAfterSubmit:false}, // del,afterSubmit:processDelete
		{}
	).hideCol(['id']);

	function func_before(a,b){
		a.idfk = $('#parm').val();
		return ['true','true'];
	}
	
	jQuery("#grid_daftar").jqGrid({
		url:'<?php echo site_url('sppd/daftar_sppd')?>',
		editurl:'<?php echo site_url('sppd/daftar_sppd')?>',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','SPT','No. SPPD','Tanggal','Biaya','Kode Rekening','Maksud Penugasan','Dari','Tujuan','Tgl. Berangkat','Tgl. Pulang','Kendaraan','Nama Pemberi','Nama Penerima','Pasal','No. SPT','Tgl. SPT'],
		colModel :[
			{ name:'id' ,index:'id',search:false},
			{ name:'spt' ,index:'spt',hidden:true},
			{ name:'nomor',index:'nomor',width:100},
			{ name:'tanggal',index:'tanggal',width:60},
			{ name:'biaya',index:'biaya',width:110,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}},
			{ name:'kode_rekening',index:'kode_rekening',width:110},
			{ name:'maksud',index:'maksud',width:110},
			{ name:'dari',index:'dari',width:100},
			{ name:'tujuan',index:'tujuan',width:100},
			{ name:'berangkat',index:'berangkat',width:85},
			{ name:'pulang',index:'pulang',width:70},
			{ name:'kendaraan',index:'kendaraan',width:100},
			{ name:'pemberi',index:'pemberi',width:135},
			{ name:'penerima',index:'penerima',width:135},
			{ name:'pasal',index:'pasal',hidden:true},
			{ name:'spt_no',index:'spt_no',hidden:true},
			{ name:'tgl_spt',index:'tgl_spt',hidden:true}
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
				$('#spt_no').val(row.spt);
				$('#sppd_no').val(row.nomor); $('#tanggal_sppd').val(row.tanggal); $('#parm').val(row.id);
				$('#tanggal_spt').val(row.tgl_spt);
				$('#maksud').val(row.maksud); $('#dari').val(row.dari); $('#tujuan').val(row.tujuan);
				$('#dari_tanggal').val(row.berangkat); $('#sd_tanggal').val(row.pulang); $('#kendaraan').val(row.kendaraan);
				$('#pejabat_pemberi').val(row.pemberi); $('#pegawai_penerima').val(row.penerima); $('#edit').val('true');
				$('#kode_rekening').val(row.kode_rekening); $('#pasal_anggaran').val(row.pasal);
				$('#total_biaya').val(row.biaya);
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

$data['sppd_no']['value']=$user_data['nomor_sppd'];

$list_no_spt = $user_data['list_no_spt'];

echo form_open("sppd/entri_sppd","id='form_sppd' AUTOCOMPLETE='off'")."\n";

?>
	 <div style='float:left;width:1024px;margin-left:5px;margin-top:5px;'>
		<div id="tabs" class="form">
			<ul>
				<li><a href="#tabs-spt">SPPD</a></li>
				<li><a href="#tabs-biaya">Biaya</a></li>
				<li><a href="#tabs-daftar">Daftar</a></li>
			</ul>
			<div id="tabs-spt">
					<fieldset id='sppd' class='form'>				
<?php 
						//<css
						$csslabel=$this->css->grid();
						//>
						
						$data['sppd_no']['name'] = $data['sppd_no']['id'] = 'sppd_no';
						$data['sppd_no']['title']= 'Kolom Nomor harus terisi';
						$data['sppd_no']['readonly']='readonly';
						echo
						 '<div class='.$csslabel.'>'.form_label('No. SPPD','sppd_no').form_input($data['sppd_no']).'</div>'."\n";

						$data['tanggal_sppd']['name'] = $data['tanggal_sppd']['id'] = 'tanggal_sppd';
						$data['tanggal_sppd']['title']= 'Kolom Tanggal harus terisi';
						$data['tanggal_sppd']['readonly']='readonly';
						echo 
						 '<div class="'.$csslabel.' multi">'.form_label('Tanggal SPPD','tanggal_sppd').form_input($data['tanggal_sppd']).'</div>'."\n";
						 
						$data['spt_no']['name'] = $data['spt_no']['id'] = 'spt_no';
						$data['spt_no']['title']= 'Kolom Nomor harus terisi';
						/*echo 
						 '<div>'.form_label('No. SPT','spt_no').form_input($data['spt_no']).'</div>'."\n".*/
?>
						<div class="<?php echo $csslabel;?>"><label for='spt_no'>No. SPT</label>
						<select name='spt_no' id='spt_no'>
						<option value=''></option>
<?php
						if ($list_no_spt->num_rows() > 0)
						{
						   foreach ($list_no_spt->result() as $row)
						   {
								echo "<option value='$row->ID_SPT'>$row->SPT_NO</option>";
						   }
						} 
?>
						</select>
						</div>
<?						 

						$data['tanggal_spt']['name'] = $data['tanggal_spt']['id'] = 'tanggal_spt';
						$data['tanggal_spt']['title']= 'Kolom Tanggal harus terisi';
																	
						echo
						 '<div class="'.$csslabel.' multi">'.form_label('Tanggal SPT','tanggal_spt').form_input($data['tanggal_spt']).'</div>'."\n";
						 
						$data['pejabat_pemberi']['name'] = $data['pejabat_pemberi']['id'] = 'pejabat_pemberi';
						$data['pejabat_pemberi']['title']= 'Pejabat Pemberi Perintah harus terisi';
						$data['pejabat_pemberi']['readonly']='readonly';
						echo
						 '<div class='.$csslabel.'>'.form_label('Pejabat Pemberi Perintah','pejabat_pemberi').form_input($data['pejabat_pemberi']).'</div>'."\n";

						$data['pegawai_penerima']['name'] = $data['pegawai_penerima']['id'] = 'pegawai_penerima';
						$data['pegawai_penerima']['title']= 'Pegawai Penerima Perintah harus terisi';
						$data['pegawai_penerima']['readonly']='readonly';
						echo 
						 '<div class="'.$csslabel.' multi">'.form_label('Pegawai Penerima Perintah','pegawai_penerima').form_input($data['pegawai_penerima']).'</div>'."\n";

						$data['dari']['name'] = $data['dari']['id'] = 'dari';
						$data['dari']['title']= 'Kolom Dari harus terisi';
						$data['dari']['readonly']='readonly';
						echo 
						 '<div class='.$csslabel.'>'.form_label('Dari','dari').form_input($data['dari']).'</div>'."\n";

						$data['tujuan']['name'] = $data['tujuan']['id'] = 'tujuan';
						$data['tujuan']['title']= 'Kolom Tujuan harus terisi';
						$data['tujuan']['readonly']='readonly';
						echo 
						 '<div class="'.$csslabel.' multi">'.form_label('Ke','tujuan').form_input($data['tujuan']).'</div>'."\n";

						$data['dari_tanggal']['name'] = $data['dari_tanggal']['id'] = 'dari_tanggal';
						$data['dari_tanggal']['title']= 'Kolom Tanggal Berangkat harus terisi';
						$data['dari_tanggal']['readonly']='readonly';
						echo
						 '<div class='.$csslabel.'>'.form_label('dari tanggal ','dari_tanggal').form_input($data['dari_tanggal']).'</div>'."\n";

						$data['sd_tanggal']['name'] = $data['sd_tanggal']['id'] = 'sd_tanggal';
						$data['sd_tanggal']['title']= 'Kolom Tanggal Pulang harus terisi';						 
						$data['sd_tanggal']['readonly']='readonly';
						echo
						 '<div class="'.$csslabel.' multi">'.form_label('s/d tanggal','sd_tanggal').form_input($data['sd_tanggal']).'</div>'."\n";

						$data['kendaraan']['name'] = $data['kendaraan']['id'] = 'kendaraan';
						$data['kendaraan']['title']= 'Kolom Kendaraan harus terisi';						 
						$data['kendaraan']['readonly']='readonly';
						echo
						 '<div class='.$csslabel.'>'.form_label('Kendaraan','kendaraan').form_input($data['kendaraan']).'</div>';
						 
						$data['kode_rekening']['name'] = $data['kode_rekening']['id'] = 'kode_rekening';
						echo
						 '<div class="'.$csslabel.' multi">'.form_label('Kode Rekening','kode_rekening').form_input($data['kode_rekening']).'</div>';
						 
						$data['maksud']['name'] = $data['maksud']['id'] = 'maksud';
						$data['maksud']['title']= 'Kolom Maksud Perjalanan harus terisi';
						$data['maksud']['readonly']='readonly';
						echo
						 '<div class='.$csslabel.'>'.form_label('Maksud Mengadakan Perjalanan','maksud').form_input($data['maksud']).'</div>';
						 
						$data['total_biaya']['name'] = $data['total_biaya']['id'] = 'total_biaya';
						//$data['total_biaya']['readonly']='readonly';
						echo
						 '<div class="'.$csslabel.' multi">'.form_label('Total Biaya','total_biaya').form_input($data['total_biaya']).'</div>';						 
						 
						 $data['pasal_anggaran']['name'] = $data['pasal_anggaran']['id'] = 'pasal_anggaran';
						 $data['pasal_anggaran']['title']= 'Kolom Pasal Anggaran';
						 echo
						 '<div class='.$csslabel.'>'.form_label('Pasal Anggaran','pasal_anggaran').form_input($data['pasal_anggaran']).'</div>';
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
/*revision for new ui
$data = array(
		  'name'        => 'simpan',
		  'id'          => 'simpan',
		  'type'		=> 'button',
		  'value'       => 'Simpan',
		  'size'        => '80',
         );
echo form_input($data);

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
			<div id="tabs-biaya">
				<table id='grid_biaya'></table>
				<div id='pager_biaya'></div>
			</div>
			<div id="tabs-daftar">
				<table id='grid_daftar'></table>
				<div id='pager_daftar'></div>
			</div>
		</div>
	</div>
<?php form_close(); ?>