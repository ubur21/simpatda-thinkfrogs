<script src="<?php echo base_url()?>assets/script/pilih.js" type="text/javascript" ></script>
<script src="ajaxdo.js" type="text/javascript" ></script>
<?php
$attributes = array('id' => 'form_aset');
echo form_open_multipart('aset/entri_aset', $attributes)."";
echo "<div id='error' class='error-message'>";
echo "</div>";

class Kib{
var $_arr_Asset_Owner  = array( 'Kementerian/Lembaga'=>'00',
                            'Pemerintah/Provinsi'=>'11',
                            'Pemerintah Kabupaten/Kota'=>'12',
                            'Yayasan/Masyarakat'=>'99' );
							
function Kib(){}
	function x(){
		return $this->_arr_Asset_Owner['Pemerintah Kabupaten/Kota'];
	}
	function y(){
		$xy = array_flip($this->_arr_Asset_Owner);
		return $xy['12'];
	}
}
$Kib = new Kib();
$csslabel=$this->css->grid();
?>
<table class="layout">
<tr>
	<td><?php $this->load->view('aset/menu');?></td>
	<td>
		<div>
		
			<div class="<?php echo $this->css->panel();?>">
			</div>
		</div>

<div class="center">
	<div class="center-left">
		<div class="center-right">
			<div class="form">
				<div class="trigger"><div class="<?php echo $this->css->bar();?>" style="padding:3px;"><?php echo form_label('Informasi Umum','Informasi Umum'); ?></div></div>
					<div class="toggle_aset_default">
						<fieldset>
							<legend></legend>
							<?php 	
							echo '<table><tr><td colspan="5"></td></tr>';
									echo '<tr><td class="'.$csslabel.'"><input type="hidden" name="id_aset" id="id_aset" value="'.$user_data['ASET_ID'].'">';
									echo form_label('Pemilik','Pemilik').'</td>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Satker','Satker').'</td>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Kode Aset','Kode Aset').'</td>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('No Register','No Register').'</td>';
									echo '<td></td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'">';
									echo '<input type="hidden" name="detailpemilik" value="'.$Kib->y().'" />';
									echo '<div class="'.$csslabel.'">';
									
									$data['name'] = $data['id'] = 'namakelompok';
									$data['value'] = isset($user_data['NAMA_KELOMPOK'])?$user_data['NAMA_KELOMPOK']:''; 
									echo form_hidden($data);
									
									$data['name'] = $data['id'] = 'pemilik'; $data['readonly'] = 'true';
									$data['value'] = isset($user_data['PEMILIK'])?$user_data['PEMILIK']:$Kib->x(); 
									echo form_input($data);
									
									echo '</td>';
									echo '<td class="'.$csslabel.'">';
									
									$data['name'] = 'satker'; $data['id'] = 'kode_satker'; $data['readonly']='true'; 
									$data['value'] = isset($user_data['KODE_SATKER'])?$user_data['KODE_SATKER']:'';
									echo form_input($data);
									
									echo '</td>';
									echo '<td class="'.$csslabel.'">';
									
									$data['name'] = $data['id'] = 'kodekelompok'; $data['readonly']='true'; 
									$data['value'] = isset($user_data['KODE'])?$user_data['KODE']:'';
									echo form_input($data);
									
									echo '</td>';
									echo '<td class="'.$csslabel.'">';
									
									$data['name'] = $data['id'] = 'no_register'; $data['readonly']='true'; 
									$data['value'] = isset($user_data['NOMORREG']) ? sprintf("%03d",$user_data['NOMORREG']):'';
									echo form_input($data);
									echo '</td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Nama Aset','Nama Aset').'</td>';
									echo '<td class="'.$csslabel.'" colspan="3"> </td>';	
								echo '</tr><tr>';
									echo '<td colspan="4" class="ui-state-default" style="padding-left:1px;">';
									$data_aset['name'] = $data_aset['id'] = 'nama_aset'; $data_aset['style']='width:99%;';
									$data_aset['value'] = isset($user_data['NAMAASET'])?$user_data['NAMAASET']:''; 
									echo form_input($data_aset);
									echo '</td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Nama Satker','Nama Satker').'</td>';
									echo '<td class="'.$csslabel.'" colspan="3"></td>';
								echo '</tr><tr>';
									echo '<td colspan="4" class="ui-state-default">';
									echo '<input type="hidden" name="idsatker" id="idsatker" value="'.$user_data['ID_SATKER'].'" />';
									
									$sat_ker['name'] = $sat_ker['id'] = 'nama_satker'; $sat_ker['style']='width:85%;';
									$sat_ker['value'] = isset($user_data['NAMA_SATKER'])?$user_data['NAMA_SATKER']:''; 
									echo form_input($sat_ker);
								
									echo '&nbsp;&nbsp;<a id="pilih_satker" class="'.$this->css->button().'">Pilih<span class="'.$this->css->iconplus().'"></span></a></td>';
							echo '</tr></table>';
							?>
						</fieldset>
					</div>
					<?
					$data = $user_data['KELOMPOK_ID']."|".$user_data['ASET_ID'];
					?>
				<div class="trigger"><div class="<?php echo $this->css->bar();?>" style="padding:3px;" <? if($user_data['KELOMPOK_ID']!=""){?> onclick="GetForm('<?=$data?>')" <? }?> ><?php echo form_label('Perolehan Aset','Perolehan Aset'); ?></div></div>
					<div class="toggle_aset">
						<fieldset>
							<legend></legend>
							<?php
							echo '<table><tr>';
									echo '<td colspan="5" class="'.$csslabel.'"></td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Cara Perolehan','Cara Perolehan').'</td>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Tanggal Perolehan','Tanggal Perolehan').'</td>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Nilai Perolehan','Nilai Perolehan').'</td>';
									echo '<td class="'.$csslabel.'"  width="146">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
									
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'" width="147px">';
									$options = array(
										'' => '-Pilih-',
										'Off-Budget'  => 'Off-Budget',
										'Pengadaan'  => 'Pengadaan',
										'Hibah' => 'Hibah'
									);
									$data_d['name'] = $data_d['id'] = 'cara'; $data_d['value'] = isset($user_data['SUMBERASET']) ? $user_data['SUMBERASET'] : '';
									echo form_dropdown($data_d['name'], $options, $data_d['value'] );
									
									echo '</td><td class="'.$csslabel.'">';
									$tanggal_peroleh=array('name'=>'tanggal_peroleh','id'=>'date_1','class'=>'desc','value'=>isset($user_data['TGLPEROLEHAN'])?date("d/m/Y",strtotime($user_data['TGLPEROLEHAN'])):'');
									echo form_input($tanggal_peroleh);
									echo '</td><td class="'.$csslabel.'">';
									$nilai['name'] = $nilai['id'] = 'nilai';
									$nilai['value'] = isset($user_data['NILAIPEROLEHAN'])?$user_data['NILAIPEROLEHAN']:'';
									echo form_input($nilai);
									echo '</td>';
									echo '<td class="'.$csslabel.'"  width="146">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Kelompok Aset','Kelompok Aset').'</td>';
									echo '<td class="'.$csslabel.'" colspan="3"></td>';
								echo '</tr><tr>';
									echo '<td colspan="4" class="ui-state-default">';
									echo '<input type="hidden" id="idkelompok" name="idkelompok" value="'.$user_data['KELOMPOK_ID'].'" />';
									//$form = array();
									$form['name'] = 'namakelompok';
									$form['id'] = 'namakelompok';
									$form['style'] = 'width:85%';
									$form['value'] = isset($user_data['URAIAN']) ? $user_data['URAIAN'] : '';
									echo form_input($form);
									echo '&nbsp;&nbsp;<a id="pilih_kelompok" class="'.$this->css->button().'">Pilih<span class="'.$this->css->iconsearch().'"></span></a>';
									echo '</td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'" >';
									echo form_label('Jenis Aset','Jenis Aset');
									echo '</td><td class="'.$csslabel.'" >';
									$options = array(
										'' => 'Pilih Aset',
										'P'  => 'Program',
										'O'  => 'Operasional'
									);
									$data_p['name'] = $data_p['id'] = 'jenis_aset'; $data_p['value'] = isset($user_data['TIPEASET']) ? $user_data['TIPEASET'] : '';
									echo form_dropdown($data_p['name'], $options, $data_p['value'] );
									
									echo '</td><td class="'.$csslabel.'">';
									echo form_label('Bersejarah','Bersejarah');
									echo '</td><td class="'.$csslabel.'" >';
									$options = array(
										'1' => 'Bersejarah',
										'0'  => 'Tidak Bersejarah'
									);
									$data_s['name'] = $data_s['id'] = 'sejarah'; $data_s['value'] = isset($user_data['BERSEJARAH']) ? $user_data['BERSEJARAH'] : '';
									echo form_dropdown($data_s['name'], $options, $data_s['value'] );
									echo '</td>';
								echo '</tr><tr>';
									echo '<td colspan="5" class="'.$csslabel.'">';
									echo '<table id="t_detail_aset" width="100%"></table>';
									//echo '<div id="t_detail_aset" width="100%"></div>';
									echo '</td>';
								
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'" >';
									echo form_label('Alamat','Alamat').'</td>';
									echo '<td class="'.$csslabel.'" colspan="2"></td>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('RT/RW','RT/RW').'</td>';
								echo '</tr><tr>';
									echo'<td colspan="3">';
									$alamat['name'] = $alamat['id'] = 'alamat';
									$alamat['style'] = 'width:99%;';
									$alamat['value'] = isset($user_data['ALAMAT'])?$user_data['ALAMAT']:'';
									echo form_input($alamat);
									echo '</td>	<td class="'.$csslabel.'">';
									$rt_rw['name'] = $rt_rw['id'] = 'rt_rw';
									$rt_rw['style'] = 'width:60px;';
									$rt_rw['value'] = isset($user_data['RTRW'])?$user_data['RTRW']:'';
									echo form_input($rt_rw);
									echo '</td>';
								echo'</tr><tr>';
									echo '<td colspan="2" class="'.$csslabel.'">';
									echo form_label('Desa','Desa').'</td>';
									echo '<td colspan="2" class="'.$csslabel.'">';
									echo form_label('Kecamatan','Kecamatan').'</td>';
								echo '</tr><tr>';
									echo '<td colspan="2" class="'.$csslabel.'">';
									echo '<input type="hidden" name="idlokasi" id="idlokasi" value="'.$user_data['LOKASI_ID'].'" />';
								 	$desa['name'] = $desa['id'] = 'desa';
									$desa['style'] = 'width:80%;';
									$desa['value'] = isset($addres[3]) ? $addres[3] : '';
									echo form_input($desa);
									echo '</td><td colspan="2" class="'.$csslabel.'">';
									$kecamatan['name'] = $kecamatan['id'] = 'kec';
									$kecamatan['style'] = 'width:65%';
									$kecamatan['value'] = isset($addres[2]) ? $addres[2] : '';
									echo form_input($kecamatan);
									echo '&nbsp;&nbsp;';
									echo '<a id="pilih_lokasi" class="'.$this->css->button().'">Pilih<span class="'.$this->css->iconsearch().'"></span></a>';
									echo '</td>';
								echo '</tr><tr>';
									echo '<td colspan="2" class="'.$csslabel.'">';
									echo form_label('Kabupaten','Kabupaten').'</td>';
									echo '<td colspan="2" class="'.$csslabel.'">';
									echo form_label('Propinsi','Propinsi').'</td>';
								echo '</tr><tr>';
									echo '<td colspan="2" class="'.$csslabel.'">';
									$kab['name'] = $kab['id'] = 'kab';
									$kab['style'] = 'width:80%;';
									$kab['value'] = isset($addres[1]) ? $addres[1] : '';
									echo form_input($kab);
									echo '</td><td colspan="2" class="'.$csslabel.'">';
									$prov['name'] = $prov['id'] = 'prop';
									$prov['style'] = 'width:80%;';
									$prov['value'] = isset($addres[0]) ? $addres[0] : '';
									echo form_input($prov);
									
									echo '</td>';
							echo '</tr></table>';
						?>
						</fieldset>
					</div>
				<div class="trigger"><div class="<?php echo $this->css->bar();?>" style="padding:3px;"><?php echo form_label('Detail Perolehan Aset','Detail Perolehan Aset'); ?></div></div>
					<div class="toggle_aset">
						<fieldset>
							<legend></legend>
							<?php
							echo '<table width="100%"><tr>';
									echo '<td colspan="2" class="'.$csslabel.'">';
									echo form_label('Koordinat','Koordinat').'</td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Bujur','Bujur').'</td>';
									echo '<td class="'.$csslabel.'">';
									echo form_label('Lintang','Lintang').'</td>';
									
								echo '</tr><tr>';
									echo '<td colspan="2" class="'.$csslabel.'"><input type="hidden" name="index_koordinat" id="index_koordinat" value="'.$num.'" />';
									echo '<table id="tbl_koordinat" width="87%"></table>';
									echo '</td>';
									
								echo '</tr><tr>';
									echo '<td colspan="3" align="left" class="'.$csslabel.'">';
									echo '<div align="right"><a id="btn_koordinat" class="'.$this->css->button().'">Koordinat<span class="'.$this->css->iconplus().'"></span></a></div>';
									echo '</td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'"></td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'"></td>';
								echo '</tr><tr>';
									echo '<td class="'.$csslabel.'" colspan="2">';
									echo '<input type="hidden" name="cindex" id="cindex" value="0" />';
										echo '<table id="detail"><tr>';
											echo '<th align="left" style="padding-left:10px;">Foto Aset</th>';
										echo '</tr>';
										for($i=0;$i<$num_foto;$i++){
										echo '<tr><td valign="top"><input type="radio" name="rd'.$i.'" id="Rd'.$i.'" /></td><td colspan="2" align="left" style="padding-left:10px;" >';
										echo "<img src='".site_url("aset/get_foto").(isset($id_foto[$i]) ? "/".$id_foto[$i]['FOTO_ID'] : '')."' width='90' height='90' style='border:1px solid #000'>";
										echo '</td></tr>'; 
										 }//: '';
										echo '</table>';
										echo '<div align="left"><a id="tambah_detail" class="'.$this->css->button().'">Tambah Foto<span class="'.$this->css->iconplus().'"></span></a></div>';
									echo '</td>';
							echo '</tr></table>';
							?>
						</fieldset>
					</div>
					<div class="<?php echo $this->css->pager();?> buttons">
					<?php
						isset($num)? tes($num,$bujur,$lintang,$id_koordinat) : '';
						if($act!='view')
						{ 
						?>
						<a id="button-save" class="<?php echo $this->css->button();?>">Simpan<span class="<?php echo $this->css->iconsave();?>"></span></a>
						<a id="button-reset" class="<?php echo $this->css->button();?>">Reset<span class="<?php echo $this->css->iconclose();?>"></span></a>
						<?php
						}
						else
						{
							echo form_button(array('id' => 'button-cancel', 'content' => 'Kembali', 'onClick' => "location.href='".site_url()."user'" ));
						}
						//echo isset($bujur)?$bujur[0][0]:'xx';
						function tes($num,$bujur,$lintang,$id_koordinat){
							//global $bujur;
							$i=0;
							$str = " ";
							for($i=0;$i<$num;$i++){
							$no = $i+1;
								$str .= "<tr id='".$no."'><td>".$no."&nbsp;<input type='hidden' name='id_koordinat_hid".$no."' value='".$id_koordinat[$i]."'><input id='bd".$no."' type='text' name='bd".$no."' value='".$bujur[$i][0]."' size='2'>&deg;&nbsp;<input  id='bm".$no."' type='text' name='bm".$no."' value='".$bujur[$i][1]."' size='2'>&prime;&nbsp;<input id='bs".$no."' type='text' name='bs".$no."' value='".$bujur[$i][2]."' size='2'>&Prime;&nbsp;<input id='bms".$no."' type='text' name='bms".$no."' value='".number_format($bujur[$i][3],2,'.',',')."' size='2'></td><td >&nbsp;&nbsp;&nbsp;&nbsp;".$no."&nbsp;<input  id='ld".$no."' type='text' name='ld".$no."' value='".$lintang[$i][0]."' size='2'>&deg;&nbsp;<input  id='lm".$no."' type='text' name='lm".$no."' value='".$lintang[$i][1]."' size='2'>&prime;&nbsp;<input  id='ls".$no."' type='text' name='ls".$no."' value='".$lintang[$i][2]."' size='2'>&Prime;&nbsp;<input  id='lms".$no."' type='text' name='lms".$no."' value='".number_format($lintang[$i][3],2,'.',',')."' size='2'></td></tr>";
							}
							?>
							<script>
							$("#tbl_koordinat").append("<?php echo $str; ?>");
							</script>
							<?
						}
					?>
				</div>
			</div>
		</div>			
	</div>
</div>
<div id="searchformkelompok"></div>
<div id="searchformlokasi"></div>
<div id="searchformsatker"></div>
<?php echo form_close(); 

?>

<script src="<?php echo base_url()?>assets/script/jquery.maskedinput.min.js" type="text/javascript" ></script>
<script src="<?php echo base_url()?>assets/script/jquery.defaultvalue.js" type="text/javascript" ></script>
<script>

$().ready(function() {
	
	$("#date_2").mask("99/99/9999");
	//$("#date_aset").mask("99/99/9999");
	$("#date_3").mask("99/99/9999");
	//var tabs = $("#tabs").tabs();
	$('#pilih_kelompok').bind('click', function() {
		$('#searchformkelompok').dialog({
			height:350,
			width:600,
			modal:true,
			closeOnEscape:true,
			buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
		 }).load('<?php echo site_url(); ?>/aset/pilih_kelompok', 
			{	
				'radio':1, 
				'prefix':'kelompok', 
				'idtrigger':'pilih_kelompok', 
				'idinput':'idkelompok',
				'idhidden[]':['iddata_|idkelompok|0', 'idcode_|kodekelompok|1', 'idname_|namakelompok|1']
			});
			//alert('xxxxx');
	});

	$('#pilih_lokasi').bind('click', function() {
		$('#searchformlokasi').dialog({
			height:350,
			width:600,
			modal:true,
			closeOnEscape:true,
			buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
		 }).load('<?php echo site_url(); ?>/master/lokasi/pilih_lokasi', 
		 {
			'radio':1, 
			'prefix':'lokasi', 
			'idtrigger':'pilih_aset', 
			'idinput':'idlokasi', 
			'idhidden[]':['iddata_|idlokasi|0','iddesa_|desa|0', 'idkec_|kec|0', 'idkab_|kab|0', 'idprop_|prop|0']
		});
	});
	
	$('#pilih_satker').bind('click', function() {
		$('#searchformsatker').dialog({
			height:350,
			width:600,
			modal:true,
			closeOnEscape:true,
			buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
		 }).load('<?php echo site_url(); ?>/master/satker/pilih_satker', 
		 {
			'radio':1, 
			'prefix':'satker', 
			'idtrigger':'pilih_satker', 
			'idinput':'idsatker', 
			'idhidden[]':['iddata_|idsatker|0','idname_|nama_satker|1']
		});
	});
	
	$("#btn_kelompok_aset").hover(
		function() { $(this).addClass('<?php echo $this->css->hover();?>'); },
		function() { $(this).removeClass('<?php echo $this->css->hover();?>'); }
	);
	$("#pilih_provinsi").hover(
		function() { $(this).addClass('<?php echo $this->css->hover();?>'); },
		function() { $(this).removeClass('<?php echo $this->css->hover();?>'); }
	);
	$("#btn_koordinat").hover(
		function() { $(this).addClass('<?php echo $this->css->hover();?>'); },
		function() { $(this).removeClass('<?php echo $this->css->hover();?>'); }
	);
	$("#tambah_detail").hover(
		function() { $(this).addClass('<?php echo $this->css->hover();?>'); },
		function() { $(this).removeClass('<?php echo $this->css->hover();?>'); }
	);
	$("#button-save").hover(
		function() { $(this).addClass('<?php echo $this->css->hover();?>'); },
		function() { $(this).removeClass('<?php echo $this->css->hover();?>'); }
	);
	$("#button-reset").hover(
		function() { $(this).addClass('<?php echo $this->css->hover();?>'); },
		function() { $(this).removeClass('<?php echo $this->css->hover();?>'); }
	);
	
	var options = {
		dataType:'jason',
		succes:showResponse,
		beforeSubmit:function(){}
	};
	$("#form_aset").ajaxForm(options);
	$("#button-save").click (function(){
		$("#form_aset").submit();
	});
	
	 function showResponse(responseText,statusText, xhr, $form) { 
	 	alert(responseText.message);
		
	 }
	
	$("#data_aset").dialog({
		bgiframe: true,
		resizable: false,
		height:500,
		width:700,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
	});
	
	$("#btn_golongan_aset").click( function() {
		$('#golongan_aset').dialog('open');
	});
	
	$("#golongan_aset").dialog({
		bgiframe: true,
		resizable: false,
		height:350,
		width:700,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
	});
	
	$("#pilih_provinsi").click( function() {
		$('#data_provinsi').dialog('open');
	});
	
	$("#data_provinsi").dialog({
		bgiframe: true,
		resizable: false,
		height:350,
		width:700,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
	});
	
	$("#btn_sat_ker").click( function() {
		$('#data_sat_ker').dialog('open');
	});
	
	$("#data_sat_ker").dialog({
		bgiframe: true,
		resizable: false,
		height:400,
		width:700,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
	});
	
	$(".toggle_aset").hide();

	$(".trigger").click(function(){
		$(this).toggleClass("active").next().slideToggle("high");
	});
	
	$(".toggle_kelompok").hide();

	$("h2.kelompok").click(function(){
		$(this).toggleClass("active").next().slideToggle("high");
	});
	
});

$("#tambah_detail").click( function() {
	var id = document.getElementById('cindex').value; 
		id=parseInt( id ) + 1; 
		document.getElementById('cindex').value = id;
	var str = " <tr id='" + id + "' "
			 +" class='isi_detail'> ";			
		str += "<td colspan='4'>"+id+"&nbsp;&nbsp;<input type='radio' name='rd' value=\""+id+"\" id=\"Rd"+id+"\" />&nbsp;<input type='hidden' name=\"id_hidden"+id+"\" id=\"id_hidden"+id+"\"><input  id=\"foto"+id+"\" type='file' name=\"foto"+id+"\" size='20'></td>";
	
	$("#detail").append( str );
});

$("#btn_koordinat").click( function() {
	var id = document.getElementById('index_koordinat').value; 
		id=parseInt( id ) + 1; 
		document.getElementById('index_koordinat').value = id;
	var str = " <tr id='" + id + "' ";			
		str += "<td class='<?php echo $csslabel;?>'>"+id+"&nbsp;<input  id=\"bd"+id+"\" type='text' name=\"bd"+id+"\" size='2'>&deg;&nbsp;<input  id=\"bm"+id+"\" type='text' name=\"bm"+id+"\" size='2'>&prime;&nbsp;<input  id=\"bs"+id+"\" type='text' name=\"bs"+id+"\" size='2'>&Prime;&nbsp;<input  id=\"bms"+id+"\" type='text' name=\"bms"+id+"\" size='2'></td><td class='<?php echo $csslabel;?>'>&nbsp;&nbsp;&nbsp;&nbsp;"+id+"&nbsp;<input  id=\"ld"+id+"\" type='text' name=\"ld"+id+"\" size='2'>&deg;&nbsp;<input  id=\"lm"+id+"\" type='text' name=\"lm"+id+"\" size='2'>&prime;&nbsp;<input  id=\"ls"+id+"\" type='text' name=\"ls"+id+"\" size='2'>&Prime;&nbsp;<input  id=\"lms"+id+"\" type='text' name=\"lms"+id+"\" size='2'></td>";
	$("#tbl_koordinat").append( str );
});

function SetJenisAset(x){
	$.get('<?php echo site_url(); ?>/aset/sub_kelompok/'+x,function(result){
		document.getElementById('t_detail_aset').innerHTML = result;
	});
}


function GetForm(data){
	var id = data.split('|');
		id_aset = jQuery("#id_aset").val();
	$.get('<?php echo site_url(); ?>/aset/view_kelompok/'+id[0]+'/'+id_aset,function(result){
		data = result.split("|");
		document.getElementById('t_detail_aset').innerHTML=data[0];
		jQuery('#tAset').val(data[1]);
		jQuery('#no_register').val(data[2]);
	});
}
//$('#date_aset').datepicker({changeMonth: true, changeYear: true});
for(i=1;i<=4;i++){
	$('#date_'+i).datepicker({changeMonth: true, changeYear: true});
}
function test(){
	//$(this).datepicker({changeMonth: true, changeYear: true});
	$('#date_aset').datepicker({changeMonth: true, changeYear: true});
	$('#date_aset2').datepicker({changeMonth: true, changeYear: true});
	$('#date_aset3').datepicker({changeMonth: true, changeYear: true});
}
</script>

<?
include "data_grid.php";
?>
<style>
.trigger {
	//background:url(<?php echo base_url();?>/themes/orange/pictures/icon/toggle.gif) no-repeat;
	//height: 18px;
	//width: 450px;
	font-size:12px;
	//padding-left:5px;
	//padding-top:5px;
	min-width:300px;
}
.trigger :hover {
	//background:url(<?php echo base_url();?>/themes/orange/pictures/icon/toggle.gif) no-repeat;
	cursor:pointer;	
}
.toggle_aset {
//background-color:#CCCCCC;
}
</style>
</td>
</tr>
</table>