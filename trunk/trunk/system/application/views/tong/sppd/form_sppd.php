<?php

$data['sppd_no']['value']=$user_data['nomor_sppd'];

$list_no_spt = $user_data['list_no_spt'];

echo form_open("sppd/entri_sppd","id='form_sppd' AUTOCOMPLETE='off'")."\n";

$title_form = explode('-',$title);
$title_form = trim($title_form['1']);

?>

<table class="layout">
<tr>
<td><?php $this->load->view('sppd/menu'); ?></td>
<td>
<div>

	 <div style='float:left;width:1024px;margin-left:5px;margin-top:5px;'>
		<div id="tabs" class="form">
			<h1><?php echo $title_form?></h1>
			<ul>
				<li><a href="#tabs-spt">SPPD</a></li>
				<li><a href="#tabs-biaya">Biaya</a></li>
			</ul>
			<div id="tabs-spt">
					<fieldset id='sppd' class='form'>				
<?php 
						//<css
						$csslabel=$this->css->grid();
						//>
						
						$data['sppd_no']['name']    = $data['sppd_no']['id'] = 'sppd_no';
						$data['sppd_no']['title']   = 'Kolom Nomor harus terisi';
						$data['sppd_no']['readonly']='readonly';
						$data['sppd_no']['size']    = '20';
						$data['sppd_no']['value']   = isset($user_data['SPPD_NO']) ? $user_data['SPPD_NO'] : $user_data['nomor_sppd'];
						echo
						 '<div class="'.$csslabel.'">'.form_label('No. SPPD','sppd_no').form_input($data['sppd_no']).'</div>'."\n";

						$data['tanggal_sppd']['name']    = $data['tanggal_sppd']['id'] = 'tanggal_sppd';
						$data['tanggal_sppd']['title']   = 'Kolom Tanggal harus terisi';
						$data['tanggal_sppd']['readonly']= 'readonly';
						$data['tanggal_sppd']['size']    = '11';
						$data['tanggal_sppd']['value']   = isset($user_data['TANGGAL']) ? date('d/m/Y',strtotime($user_data['TANGGAL'])) : date('d/m/Y');
						echo 
						 '<div class="'.$csslabel.' multi">'.form_label('Tanggal SPPD','tanggal_sppd').form_input($data['tanggal_sppd']).'</div>'."\n";
						 
						$data['spt_no']['name'] = $data['spt_no']['id'] = 'spt_no';
						$data['spt_no']['title']= 'Kolom Nomor harus terisi';
						$data['spt_no']['style']= 'width:113px';
						//$data['spt_no']['value']= isset($user_data['SPT_NO']) ? $user_data['SPT_NO'] : '';
						
						/*echo 
						 '<div>'.form_label('No. SPT','spt_no').form_input($data['spt_no']).'</div>'."\n".*/
?>
						<div class="<?php echo $csslabel;?>"><label for='spt_no'>No. SPT</label>
						<select name='spt_no' id='spt_no' style='width:113px'>
						<option value=''></option>
<?php
						if ($list_no_spt->num_rows() > 0)
						{
						   foreach ($list_no_spt->result() as $row)
						   {
								$current_id_spt = isset($user_data['ID_SPT']) ? $user_data['ID_SPT'] : '';
								$selected = ($row->ID_SPT==$current_id_spt) ? 'selected' : '';
						   
								echo "<option value='$row->ID_SPT' $selected>$row->SPT_NO</option>";
						   }
						}
?>
						</select>
						</div>
<?						 

						$data['tanggal_spt']['name'] = $data['tanggal_spt']['id'] = 'tanggal_spt';
						$data['tanggal_spt']['title']= 'Kolom Tanggal harus terisi';
						$data['tanggal_spt']['size'] = '11';
						$data['tanggal_spt']['value']= isset($user_data['TANGGAL_SPT']) ? date('d/m/Y',strtotime($user_data['TANGGAL_SPT'])) : '';
						
						echo
						 '<div class="'.$csslabel.' multi">'.form_label('Tanggal SPT','tanggal_spt').form_input($data['tanggal_spt']).'</div>'."\n";
						 
						$data['pejabat_pemberi']['name']  = $data['pejabat_pemberi']['id'] = 'pejabat_pemberi';
						$data['pejabat_pemberi']['title'] = 'Pejabat Pemberi Perintah harus terisi';
						$data['pejabat_pemberi']['size']  = '66'; $data['pejabat_pemberi']['readonly']='readonly';
						$data['pejabat_pemberi']['value'] = isset($user_data['NAMA_PEGAWAI_PEMBERI']) ? $user_data['NAMA_PEGAWAI_PEMBERI'] : '';
						
						echo
						 '<div class="'.$csslabel.'">'.form_label('Pejabat Pemberi Perintah','pejabat_pemberi').form_input($data['pejabat_pemberi']).'</div>'."\n";
						 
						$data['jabatan_pejabat']['name'] = $data['jabatan_pejabat']['id'] = 'jabatan_pejabat';
						$data['jabatan_pejabat']['size'] = '66'; $data['jabatan_pejabat']['readonly'] = 'readonly';
						$data['jabatan_pejabat']['value']= isset($user_data['JABATAN_STRUKTURAL_PEMBERI']) ? $user_data['JABATAN_STRUKTURAL_PEMBERI'] : (isset($user_data['JABATAN_FUNGSIONAL_PEMBERI']) ? $user_data['JABATAN_FUNGSIONAL_PEMBERI'] : '') ;
						
						echo
						'<div class="'.$csslabel.'">'.form_label('Jabatan','jabatan').form_input($data['jabatan_pejabat']).'</div>'."\n";

						$data['pegawai_penerima']['name']  = $data['pegawai_penerima']['id'] = 'pegawai_penerima';
						$data['pegawai_penerima']['title'] = 'Pegawai Penerima Perintah harus terisi';
						$data['pegawai_penerima']['size']  = '66'; $data['pegawai_penerima']['readonly']='readonly';
						$data['pegawai_penerima']['value'] = isset($user_data['NAMA_PEGAWAI_PENERIMA']) ? $user_data['NAMA_PEGAWAI_PENERIMA'] : '';
												
						echo 
						 '<div class="'.$csslabel.'">'.form_label('Pegawai Penerima Perintah','pegawai_penerima').form_input($data['pegawai_penerima']).'</div>'."\n";

						$data['jabatan_pegawai']['name'] = $data['jabatan_pegawai']['id'] = 'jabatan_pegawai';
						$data['jabatan_pegawai']['size'] = '66'; $data['jabatan_pegawai']['readonly'] = 'readonly';
						$data['jabatan_pegawai']['value']= isset($user_data['JABATAN_STRUKTURAL_PENERIMA']) ? $user_data['JABATAN_STRUKTURAL_PENERIMA'] : (isset($user_data['JABATAN_FUNGSIONAL_PENERIMA']) ? $user_data['JABATAN_FUNGSIONAL_PENERIMA'] : '');
						
						echo
						'<div class="'.$csslabel.'">'.form_label('Jabatan','jabatan').form_input($data['jabatan_pegawai']).'</div>'."\n";

						$data['dari']['name'] = $data['dari']['id'] = 'dari';
						$data['dari']['title']= 'Kolom Dari harus terisi';
						$data['dari']['readonly']='readonly'; $data['dari']['size']='66';
						$data['dari']['value'] = isset($user_data['DARI']) ? $user_data['DARI'] : '';
						
						echo 
						 '<div class="'.$csslabel.'">'.form_label('Dari','dari').form_input($data['dari']).'</div>'."\n";

						$data['tujuan']['name'] = $data['tujuan']['id'] = 'tujuan';
						$data['tujuan']['title']= 'Kolom Tujuan harus terisi';
						$data['tujuan']['readonly']='readonly'; $data['tujuan']['size']='66';
						$data['tujuan']['value'] = isset($user_data['TUJUAN']) ? $user_data['TUJUAN'] : '';
						echo 
						 '<div class="'.$csslabel.'">'.form_label('Ke','tujuan').form_input($data['tujuan']).'</div>'."\n";

						$data['dari_tanggal']['name'] = $data['dari_tanggal']['id'] = 'dari_tanggal';
						$data['dari_tanggal']['title']= 'Kolom Tanggal Berangkat harus terisi';
						$data['dari_tanggal']['readonly']='readonly'; $data['dari_tanggal']['size']='11';
						$data['dari_tanggal']['value'] = isset($user_data['TANGGAL_BERANGKAT']) ? date('d/m/Y',strtotime($user_data['TANGGAL_BERANGKAT'])) : '';
						
						echo
						 '<div class="'.$csslabel.'">'.form_label('dari tanggal ','dari_tanggal').form_input($data['dari_tanggal']).'</div>'."\n";

						$data['sd_tanggal']['name'] = $data['sd_tanggal']['id'] = 'sd_tanggal';
						$data['sd_tanggal']['title']= 'Kolom Tanggal Pulang harus terisi';						 
						$data['sd_tanggal']['readonly']='readonly'; $data['sd_tanggal']['size']='11';
						$data['sd_tanggal']['value'] = isset($user_data['TANGGAL_PULANG']) ? date('d/m/Y',strtotime($user_data['TANGGAL_PULANG'])) : '';
						echo
						 '<div class="'.$csslabel.' multi">'.form_label('s/d tanggal','sd_tanggal').form_input($data['sd_tanggal']).'</div>'."\n";

						$data['kendaraan']['name'] = $data['kendaraan']['id'] = 'kendaraan';
						$data['kendaraan']['title']= 'Kolom Kendaraan harus terisi';						 
						$data['kendaraan']['readonly']='readonly'; $data['kendaraan']['size']='66';
						$data['kendaraan']['value'] = isset($user_data['KENDARAAN']) ? $user_data['KENDARAAN'] : '';
						echo
						 '<div class="'.$csslabel.'">'.form_label('Kendaraan','kendaraan').form_input($data['kendaraan']).'</div>';
						 						 
						$data['maksud']['name'] = $data['maksud']['id'] = 'maksud';
						$data['maksud']['title']= 'Kolom Maksud Perjalanan harus terisi';
						$data['maksud']['rows'] = '3'; $data['maksud']['cols'] = '63';
						$data['maksud']['readonly'] = 'readonly';
						$data['maksud']['value']= isset($user_data['MAKSUD']) ? $this->db->getBlob($user_data['MAKSUD']) : '';
					
						echo
						 '<div class="'.$csslabel.'">'.form_label('Maksud Mengadakan Perjalanan','maksud').form_textarea($data['maksud']).'</div>';
						 
						$data['beban_anggaran']['name'] = $data['beban_anggaran']['id'] = 'beban_anggaran';
						$data['beban_anggaran']['size'] = '66'; $data['beban_anggaran']['readonly'] = 'readonly';
						$data['beban_anggaran']['value'] = isset($user_data['NAMA_SATKER']) ? $user_data['NAMA_SATKER'] : '';
						
						echo
						 '<div class="'.$csslabel.'">'.form_label('Beban Anggaran','beban_anggaran').form_input($data['beban_anggaran']).'</div>';						 
						 
						$data['kode_rekening']['name'] = $data['kode_rekening']['id'] = 'kode_rekening';
						$data['kode_rekening']['size'] = '66';
						$data['kode_rekening']['value'] = isset($user_data['KODE_REKENING']) ? $user_data['KODE_REKENING'] : '';
						
						echo
						 '<div class="'.$csslabel.'">'.form_label('Kode Rekening','kode_rekening').form_input($data['kode_rekening']).'</div>';
						 						 
						$data['total_biaya']['name'] = $data['total_biaya']['id'] = 'total_biaya';
						$data['total_biaya']['size'] = '55'; $data['total_biaya']['readonly']='readonly';
						$data['total_biaya']['style']='text-align:right;font-size:16px';
						$data['total_biaya']['value']= isset($user_data['NOMINAL']) ? number_format($user_data['NOMINAL'],2,',','.') : '0';
						//$data['total_biaya']['readonly']='readonly';
						/*echo
						 '<div class="'.$csslabel.'">'.form_label('Total Biaya','total_biaya').form_input($data['total_biaya']).'</div>';*/
						 
						 $data['pasal_anggaran']['name'] = $data['pasal_anggaran']['id'] = 'pasal_anggaran';
						 $data['pasal_anggaran']['title']= 'Kolom Pasal Anggaran';
						 $data['pasal_anggaran']['rows'] = '3'; 
						 $data['pasal_anggaran']['cols'] = '63';
						 $data['pasal_anggaran']['value']= isset($user_data['PASAL_ANGGARAN']) ? $this->db->getBlob($user_data['PASAL_ANGGARAN']) : '';
						
						 echo
						 '<div class="'.$csslabel.'">'.form_label('Pasal Anggaran','pasal_anggaran').form_textarea($data['pasal_anggaran']).'</div>';
?>
						<input type='hidden' name='parm' id='parm' value='<?php echo $value_parm?>'>
						<input type='hidden' name='edit' id='edit' value='<?php echo $status_edit?>'>
					</fieldset>
			</div>
			<div id="tabs-biaya">
<?php
	echo
		'<div class="'.$csslabel.'" style="padding:5px"><label style="margin-right:5px">Total </label>'.form_input($data['total_biaya']).'</div>';
?>			
			
				<table id='grid_biaya'></table>
				<div id='pager_biaya'></div>
			</div>
			
					<div class="buttons">
						<a class="<?php echo $this->css->button();?>" name='simpan' id='simpan' title='Simpan'>Simpan
						<span class="<?php echo $this->css->iconsave();?>"></span></a>
						<a class="<?php echo $this->css->button();?>" name='batal' id='batal' title='<?php echo $title_batal?>'><?php echo $label_batal?>
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
	</div>
<?php form_close(); ?>

</div>
</td>
</tr>
</table>
<script>

var lastsel,lastsel2,lastsel3;

jQuery(document).ready(function(){
	$("#tabs").tabs();
		
	$('#tanggal_sppd').datepicker({changeMonth: true, changeYear: true});
	
	$('input#total_biaya').formatCurrency({ roundToDecimalPlace: 0,region: 'id-ID',decimalSymbol:',',digitGroupSymbol:'.',roundToDecimalPlace:'2',symbol:'Rp. ' });
	
	$('#spt_no').change(function(){
		
		$.get('<?php echo site_url('sppd/data_spt')?>/'+$(this).val(), function(rs){
			$('#tanggal_spt').val(rs.TANGGAL);
			$('#pejabat_pemberi').val(rs.NAMA_PEGAWAI_PEMBERI);
			$('#pegawai_penerima').val(rs.NAMA_PEGAWAI_PENERIMA);
			$('#jabatan_pejabat').val(rs.JABATAN_PEMBERI);
			$('#jabatan_pegawai').val(rs.JABATAN_PENERIMA);
			$('#dari').val(rs.DARI);
			$('#tujuan').val(rs.TUJUAN);
			$('#dari_tanggal').val(rs.TANGGAL_BERANGKAT);
			$('#sd_tanggal').val(rs.TANGGAL_PULANG);
			$('#kendaraan').val(rs.KENDARAAN);
			$('#maksud').val(rs.MAKSUD);
			$('#beban_anggaran').val(rs.NAMA_SATKER);
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
	
	function setListNoSPT()
	{		
		$.get('<?php echo site_url('sppd/get_json_no_spt')?>',function(result){
			$('select#spt_no').empty();
			$.each(result, function(val, text){				
				$('select#spt_no').append( new Option(text.label,text.id,text.selected) );
			});	
		},'json');
	}
	
	$('#simpan').click(function(){
		var frmObj = document.getElementById('form_sppd');
		if($(this).attr('title')=='Selesai'){
			if($('#edit').val()!=''){
				location.href = "<?php echo site_url('sppd/daftar_sppd'); ?>";
			}else{
				$(frmObj).resetForm(); 
				$.get('<?php echo site_url('sppd/set_no_sppd')?>',function(result){$('#sppd_no').val(result)}); setListNoSPT();
				$('#simpan').html('Simpan<span class="<?php echo $this->css->iconsave();?>"></span>');
				jQuery('#simpan').attr('title','Simpan');
				$('#grid_biaya').setGridParam({url:'<?php echo site_url('sppd/daftar_biaya')?>'}).trigger('reloadGrid');
				$("#tabs").tabs('select',0);
			}		
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
						$("#tabs").tabs('select',1);
						$('#simpan').html('Selesai<span class="<?php echo $this->css->iconsave();?>"></span>');
						jQuery('#simpan').attr('title','Selesai');
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
		if($(this).attr('title')=='Kembali'){
			location.href = "<?php echo site_url('sppd/daftar_sppd'); ?>";
		}else{
			var parm = $('#parm').val(); var frmObj = document.getElementById('form_sppd');		
			if(parm!='' && $('#edit').val()==''){
				//$.get("<?php echo site_url('sppd/batal_spt')?>/"+parm);
				var data = {}; data.id=parm;
				$.post("<?php echo site_url('sppd/batal_sppd')?>",data);
				$('#simpan').html('Simpan<span class="<?php echo $this->css->iconsave();?>"></span>');
				$('#grid_biaya').setGridParam({url:'<?php echo site_url('sppd/daftar_biaya')?>'}).trigger('reloadGrid');
				$.get('<?php echo site_url('sppd/set_no_sppd')?>',function(result){$('#sppd_no').val(result)});
			}			
			$(frmObj).resetForm(); setListNoSPT();
			$('#edit').val(''); $("#tabs").tabs('select',0);
			jQuery('#simpan').attr('title','Simpan');
		}		
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
			{ name:'keperluan',index:'keperluan',width:350,editable:true,edittype:'text',editrules:{required:true}, editoptions: {size:90} },
			{ name:'peserta',index:'peserta',width:60,editable:true,align:'right',edittype:'select',editrules:{required:true},
			  editoptions: {
					//dataUrl:'<?php echo site_url('sppd/get_peserta')?>',
					value:{'':''},
					style:'width:300px;',
					multiple:true, size:3
				}
			},
			{ name:'hari',index:'hari',width:40,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},
			  editoptions: {size:10,maxlength:10 },
			},
			{ name:'nominal',index:'nominal',width:200,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},
			  editoptions:{size:35},formatter:'currency',
			  formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}
			}
		 ],
		pager: jQuery('#pager_biaya'),
		height:230,
		rowNum:10, 
		rowList:[10,20,30], 
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
        gridComplete: function(){ jQuery("#grid_biaya").setGridWidth(730); return true; },
		loadComplete: function() {
			//$('select#Country').setColProp('Country', { editoptions: { value: "FE:FedEx; IN:InTime; TN:TNT" } });
		}
		
	}).navGrid(
		'#pager_biaya',
		{ add:true,edit:true,del:true},
		{ width:600,beforeSubmit:func_before,
		  beforeShowForm:setFormDialog,
		  afterSubmit:setTotal
		}, // edit,afterSubmit:setTotal,bSubmit:"Ubah",bCancel:"Tutup",
		
		{ width:600,beforeSubmit:func_before,
		  beforeShowForm:setFormDialog,
		  afterSubmit:setTotal			  
		}, // add,afterSubmit:processAdd,bSubmit:"Tambah",bCancel:"Tutup",,reloadAfterSubmit:false
		{ reloadAfterSubmit:false}, // del,afterSubmit:processDelete
		{}
	).hideCol(['id']);
	
	function setPeserta(){
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
	
	function setJumlahHari()
	{
		var one_day=1000*60*60*24;
		var tgl1=$('#sd_tanggal').val();
		var tgl2=$('#dari_tanggal').val();
		var arr1=tgl1.split('/');
		var arr2=tgl2.split('/');
		var date1=new Date(arr1[2],arr1[1]-1,arr1[0]);
		var date2=new Date(arr2[2],arr2[1]-1,arr2[0]);
		var beda_hari=Math.ceil((date1.getTime()-date2.getTime())/(one_day));
		$('#hari').val(beda_hari);
	}
	
	function setFormDialog()
	{
		setPeserta(); setJumlahHari();
	}

	function setTotal(response, postdata){
		var total=0;
		var peserta = jQuery('#peserta').val();
		var hari    = jQuery('input#hari').val();
		var biaya   = jQuery('input#nominal').val();		
		total = peserta.length*hari*biaya;
		jQuery("#grid_biaya > tbody > tr").each(function(){		
			if(this.id!=postdata.id){
				tmp = jQuery("#grid_biaya").getRowData(this.id);
				//alert(tmp.peserta+' '+tmp.hari+' '+tmp.nominal);
				total+= (tmp.peserta*tmp.hari*tmp.nominal);	
			}
		});
		jQuery('input#total_biaya').val(total).formatCurrency({ roundToDecimalPlace: 0,region: 'id-ID',decimalSymbol:',',digitGroupSymbol:'.',roundToDecimalPlace:'2',symbol:'Rp. ' });
		var success = true; var message = ""; var new_id = "";
		return [success,message,new_id];
	}	

	function func_before(a,b){
		a.idfk = $('#parm').val();
		return ['true','true'];
	}
		
});
</script>