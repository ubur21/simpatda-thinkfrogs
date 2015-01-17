<table class="layout">
<tr>
<td><?php $this->load->view('pegawai/menu');?></td>
<td>
<div>
<?php
echo form_open('pegawai/'.$form_act, 'id="pegawai_form"')."\n";
echo "<div id='error' class='error-message'> \n";
echo "</div> \n";
?>
<div class="<?php echo $this->css->box();?>">
<div class="<?php echo $this->css->bar();?>">Identitas</div>
<fieldset class="form">
<?php
	$data=array('id'=>isset($user_data['ID_PEGAWAI'])?$user_data['ID_PEGAWAI'] : '');
	echo form_hidden($data);
?>
	<div class="<?php echo $this->css->grid();?>">
<?php
	$label=array();
	echo form_label('NIP','nip',$label); 
	$data=array('name'=>'desc[]','class'=>'desc','value'=>isset($user_data['NIP'])?$user_data['NIP']:'-','readonly'=>'readonly');
	echo form_input($data);
?>  </div>
	<div class="<?php echo $this->css->grid();?> multi">
<?php 
	echo form_label('Nama','nama',$label); 
	$data=array(
		'name'=>'desc[]',
		'class'=>'desc',
		'value'=>isset($user_data['NAMA_PEGAWAI'])?
			($user_data['GELAR_DEPAN'] == '' ? '' : $user_data['GELAR_DEPAN'].' ')
			.$user_data['NAMA_PEGAWAI'].
			($user_data['GELAR_BELAKANG'] == '' ? '' : ', '.$user_data['GELAR_BELAKANG'])
			:'-','readonly'=>'readonly');
	echo form_input($data);
?>	</div>
	<div class="<?php echo $this->css->grid();?>">
<?php 
	echo form_label('Karpeg','karpeg',$label); 
	$data=array('name'=>'desc[]','class'=>'desc','value'=>isset($user_data['KARTU_PEGAWAI'])?$user_data['KARTU_PEGAWAI']:'-','readonly'=>'readonly');
	echo form_input($data);
?>
	<!--<span><?php echo isset($user_data['KARTU_PEGAWAI'])?$user_data['KARTU_PEGAWAI']:'-';?></span>-->
	</div>
	<div class="<?php echo $this->css->grid();?> multi" >
<?php 
	echo form_label('Askes','askes',$label); 
	$data=array('name'=>'desc[]','class'=>'desc','value'=>isset($user_data['KARTU_ASKES'])?$user_data['KARTU_ASKES']:'-','readonly'=>'readonly');
	echo form_input($data);
?>  </div>
</fieldset>
</div>
<div class="center">
	<div class="center-left">
		<div class="center-right">
			<div id="tabs" class="form">
				<ul>
					<li><a href="#tab-biodata">Data <br>Pegawai</a></li>
					<li><a href="<?php echo site_url('pegawai/tab/keluarga/'.$user_data['ID_PEGAWAI'])?>">Data <br>Keluarga</a></li>
					<li><a href="<?php echo site_url('/pegawai/tab/pangkat/'.$user_data['ID_PEGAWAI'])?>">Riwayat <br>Pangkat</a></li>
					<li><a href="<?php echo site_url('/pegawai/tab/gaji/'.$user_data['ID_PEGAWAI'])?>">Riwayat <br>Gaji</a></li>
					<li><a href="<?php echo site_url('/pegawai/tab/organisasi/'.$user_data['ID_PEGAWAI'])?>">Riwayat <br>Organisasi</a></li>
					<li><a href="<?php echo site_url('/pegawai/tab/kursus/'.$user_data['ID_PEGAWAI'])?>">Riwayat <br>Kursus</a></li>
					<li><a href="<?php echo site_url('/pegawai/tab/diklat/'.$user_data['ID_PEGAWAI'])?>">Riwayat <br>Diklat</a></li>
					<li><a href="<?php echo site_url('/pegawai/tab/penghargaan/'.$user_data['ID_PEGAWAI'])?>">Riwayat <br>Penghargaan</a></li>
					<li><a href="<?php echo site_url('/pegawai/tab/jabatan/'.$user_data['ID_PEGAWAI'])?>">Riwayat <br>Jabatan</a></li>
					<li><a href="<?php echo site_url('/pegawai/tab/pendidikan/'.$user_data['ID_PEGAWAI'])?>">Riwayat <br>Pendidikan</a></li>
				</ul>
				<div id="tab-biodata">
					<fieldset id="biodata" class="form">
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('NIP','nip'); 
							$data=array('name'=>'nip','id'=>'nip','value'=>isset($user_data['NIP'])?$user_data['NIP']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Nama','nama'); 
							$data=array('name'=>'nama','id'=>'nama','value'=>isset($user_data['NAMA_PEGAWAI'])?$user_data['NAMA_PEGAWAI']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Gelar Depan','gelar_depan'); 
							$data=array('name'=>'gelar_depan','id'=>'gelar_depan','value'=>isset($user_data['GELAR_DEPAN'])?$user_data['GELAR_DEPAN']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Gelar Belakang','gelar_belakang'); 
							$data=array('name'=>'gelar_belakang','id'=>'gelar_belakang','value'=>isset($user_data['GELAR_BELAKANG'])?$user_data['GELAR_BELAKANG']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 	
							echo form_label('Tempat Lahir','tempat_lahir');
							$data=array('name'=>'tempat_lahir','id'=>'tempat_lahir','value'=>isset($user_data['TEMPAT_LAHIR'])?$user_data['TEMPAT_LAHIR']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php
							echo form_label('Tanggal Lahir','tgl_lahir');
							$data=array('name'=>'tgl_lahir','id'=>'tgl_lahir','value'=> format_date( $user_data['TANGGAL_LAHIR'] ));
							echo form_input($data);
						?> 
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Jenis Kelamin','jenkel');
							$options = array(
								''  => 'Pilih',
								'P'    => 'Pria',
								'W'   => 'Wanita'
							);
							$data=array('name'=>'jenkel','id'=>'jenkel','value'=>isset($user_data['JENIS_KELAMIN'])?$user_data['JENIS_KELAMIN']:'');
							echo form_dropdown($data['name'], $options, $data['value'] );
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Status Kawin','status_kawin'); 
							$options = array(
								''  => 'Pilih',
								'TK' => 'Belum Kawin',
								'K'  => 'Kawin',
								'D'  => 'Duda',
								'J'  => 'Janda'
							);
							$data=array('name'=>'status_kawin','id'=>'status_kawin','value'=>isset($user_data['STATUS_KAWIN'])?$user_data['STATUS_KAWIN']:'');
							echo form_dropdown($data['name'], $options, $data['value'] );
						?> 
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Agama','agama');
							$options = array();
							if(isset($agama)) {
								$options['']='Pilih';
								foreach($agama as $row){ 
									$options[$row->ID_AGAMA]=$row->NAMA_AGAMA;
								} 
							}
							$data=array('name'=>'agama','id'=>'agama','value'=>isset($user_data['AGAMA'])?$user_data['AGAMA']:'');
							echo form_dropdown( $data['name'], $options, $data['value'] );
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 	
							echo form_label('Suku Bangsa','suku');
							$data=array('name'=>'suku','id'=>'suku','value'=>isset($user_data['SUKU_BANGSA'])?$user_data['SUKU_BANGSA']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 	
							echo form_label('Golongan Darah','gol_darah');
							$options = array(
								'Pilih','O','A','B','AB'
							);
							$data=array('name'=>'gol_darah','id'=>'gol_darah','value'=>isset($user_data['GOLONGAN_DARAH'])?$user_data['GOLONGAN_DARAH']:'');
							echo form_dropdown($data['name'], $options, $data['value']);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 	
							echo form_label('Pendidikan','pendidikan');
							$data=array('name'=>'pendidikan','id'=>'pendidikan','value'=>isset($user_data['NAMA_PENDIDIKAN'])?$user_data['NAMA_PENDIDIKAN']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Tunjangan Suami/Istri','suis');
							$data=array('name'=>'suis','id'=>'suis','value'=>(isset($user_data['SUAMI_ISTRI'])&&$user_data['SUAMI_ISTRI'])?TRUE:FALSE);
							echo form_checkbox($data['name'],1,$data['value']);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php
							echo form_label('Anak','anak');
							$data=array('name'=>'anak','id'=>'anak','value'=>isset($user_data['JUMLAH_ANAK'])?$user_data['JUMLAH_ANAK']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Alamat','alamat');
							$data=array('name'=>'alamat','id'=>'alamat','rows'=>3,'cols'=>30,'value'=>isset($user_data['ALAMAT'])?$user_data['ALAMAT']:'');
							echo form_textarea($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('RT','rt'); 
							$data=array('name'=>'rt','id'=>'rt','value'=>isset($user_data['RT'])?$user_data['RT']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php
							echo form_label('RW','rw');
							$data=array('name'=>'rw','id'=>'rw','value'=>isset($user_data['RW'])?$user_data['RW']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Kode Pos','kode_pos'); 
							$data=array('name'=>'kode_pos','id'=>'kode_pos','value'=>isset($user_data['KODE_POS'])?$user_data['KODE_POS']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('No. Telepon','telepon'); 
							$data=array('name'=>'telepon','id'=>'telepon','value'=>isset($user_data['TELEPON'])?$user_data['TELEPON']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Email','email'); 
							$data=array('name'=>'email','id'=>'email','value'=>isset($user_data['EMAIL'])?$user_data['EMAIL']:'');
							echo form_input($data);
						?>
						</div>
					</fieldset>
					<fieldset class="form">
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Gol. Ruang CPNS','pangkat_cpns');
							
							$options = array();
							if(isset($pangkat)) {
								$options['']='Pilih';
								foreach($pangkat as $row){ 
									$options[$row->ID_PANGKAT] = $row->KODE_PANGKAT. ' - ' .$row->NAMA_PANGKAT;
								} 
							}
							$data=array('name'=>'pangkat_cpns','id'=>'pangkat_cpns','value'=>isset($user_data['ID_PANGKAT_CPNS'])?$user_data['ID_PANGKAT_CPNS']:'');
							echo form_dropdown( $data['name'], $options, $data['value'] );
							
							//echo site_url('master/pangkat/getselectv');
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php
							echo form_label('TMT CPNS','tmt_cpns');
							$data=array('name'=>'tmt_cpns','id'=>'tmt_cpns','size'=>12,'maxlength'=>10,'value'=> format_date( $user_data['TMT_CPNS'] ));
							echo form_input($data);
						?> 
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php
							echo form_label('TMT PNS','tmt_pns');
							$data=array('name'=>'tmt_pns','id'=>'tmt_pns','size'=>12,'maxlength'=>10,'value'=> format_date( $user_data['TMT_PNS'] ));
							echo form_input($data);
						?> 
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Jenjang Pendidikan CPNS','pendidikan_cpns');
							$options = array();
							if(isset($jenjang)) {
								$options['']='Pilih';
								foreach($jenjang as $row){ 
									$options[$row->ID_PENDIDIKAN]=$row->NAMA_PENDIDIKAN;
								} 
							}
							$data=array('name'=>'pendidikan_cpns','id'=>'pendidikan_cpns','value'=>isset($user_data['JENJANG'])?$user_data['JENJANG']:'');
							echo form_dropdown( $data['name'], $options, $data['value'] );
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Nama Pendidikan CPNS','sekolah_cpns'); 
							$data=array('name'=>'sekolah','id'=>'sekolah_cpns','value'=>isset($user_data['PEND_CPNS'])?$user_data['PEND_CPNS']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Tahun Lulus','sekolah_cpns_lulus'); 
							$data=array('name'=>'sekolah_cpns_lulus','id'=>'sekolah_cpns_lulus','value'=>isset($user_data['THN_LULUS'])?$user_data['THN_LULUS']:'');
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Jenis Kepegawaian','status_pegawai');
							$options = array();
							if(isset($status)) {
								$options['']='Pilih';
								foreach($status as $row){ 
									$options[$row->ID_STATUS_PEGAWAI]=$row->NAMA_STATUS_PEGAWAI;
								} 
							}
							$data=array('name'=>'status_pegawai','id'=>'status_pegawai','value'=>isset($user_data['STATUS_PEGAWAI'])?$user_data['STATUS_PEGAWAI']:'');
							echo form_dropdown( $data['name'], $options, $data['value'] );
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Kedudukan Hukum','kedudukan');
							$options = array();
							if(isset($kedudukan)) {
								$options['']='Pilih';
								foreach($kedudukan as $row){ 
									$options[$row->ID_KEDUDUKAN]=$row->NAMA_KEDUDUKAN;
								} 
							}
							$data=array('name'=>'kedudukan','id'=>'kedudukan','value'=>isset($user_data['KEDUDUKAN'])?$user_data['KEDUDUKAN']:'');
							echo form_dropdown( $data['name'], $options, $data['value'] );
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Gol. Ruang Terakhir','pangkat');
							$options = array();
							if(isset($pangkat)) {
								$options['']='Pilih';
								foreach($pangkat as $row){ 
									$options[$row->ID_PANGKAT] = $row->KODE_PANGKAT. ' - ' .$row->NAMA_PANGKAT;
								} 
							}
							$data=array('name'=>'pangkat','id'=>'pangkat','value'=>isset($user_data['ID_PANGKAT'])?$user_data['ID_PANGKAT']:'');
							echo form_dropdown( $data['name'], $options, $data['value'] );
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php
							$attrib=array('style'=>'width:40px');
							echo form_label('TMT','tmt_gol',$attrib);
							$data=array('name'=>'tmt_gol','id'=>'tmt_gol','size'=>12,'maxlength'=>10,'value'=> format_date( $user_data['TMT_GOL'] ));
							echo form_input($data);
						?>
						</div>
						<div class="<?php echo $this->css->grid();?> multi">
						<?php
							$attrib=array('style'=>'width:60px;');
							echo form_label('Masa Kerja','masa_kerja',$attrib);
							$data=array('name'=>'mk_tahun','id'=>'mk_tahun','size'=>2,'maxlength'=>2,'value'=>isset($user_data['MK_TAHUN'])?$user_data['MK_TAHUN']:'');
							echo form_input($data);
						?>Tahun
						</div>
						<div class="<?php echo $this->css->grid();?> multi">
						<?php
							$data=array('name'=>'mk_bulan','id'=>'mk_bulan','size'=>2,'maxlength'=>2,'value'=>isset($user_data['MK_BULAN'])?$user_data['MK_BULAN']:'');
							echo form_input($data);
						?>Bulan
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Jenjang Pendidikan Terakhir','jp_akhir');
							$options = array();
							if(isset($jenjang)) {
								$options['']='Pilih';
								foreach($jenjang as $row){ 
									$options[$row->ID_PENDIDIKAN]=$row->NAMA_PENDIDIKAN;
								} 
							}
							$data=array('name'=>'jenjang_akhir','id'=>'jenjang_akhir','value'=>isset($user_data['ID_PENDIDIKAN'])?$user_data['ID_PENDIDIKAN']:'');
							echo form_dropdown( $data['name'], $options, $data['value'] );
						?>
						</div>
						<div class="<?php echo $this->css->grid();?>">
						<?php 
							echo form_label('Jenis Jabatan Saat Ini','jenis_jab');
							$options = array(
								'' =>'Pilih',
								'1' =>'Struktural',
								'2' =>'Fungsional'
							);
							$data=array('name'=>'jenis_jab','id'=>'jenis_jab','value'=>isset($user_data['JENIS_JAB'])?$user_data['JENIS_JAB']:'');
							echo form_dropdown( $data['name'], $options, $data['value'] );
						?>
						</div>
					</fieldset>
					<div class="<?php echo $this->css->panel();?> buttons">
						<?php 
							if($act!='view')
							{ 
								/*
								$attrib = array('name'=>'simpan','class'=>$this->css->button());
								echo form_submit($attrib, 'Simpan', "id = 'button-save'" );
								
								$attrib = array('name'=>'reset','class'=>$this->css->button());
								echo form_reset($attrib,'Reset', "id = 'button-reset'" );
								if($this->session->userdata('ADMIN'))
									echo form_button(array('id' => 'button-cancel', 'content' => 'Batal', 'onClick' => "location.href='".site_url()."user'" ));
								*/
								?>
								<a id="simpan" class="<?php echo $this->css->button();?>">Simpan<span class="<?php echo $this->css->iconsave();?>"></span></a>
								<a id="batal" class="<?php echo $this->css->button();?>">Batal<span class="<?php echo $this->css->iconclose();?>"></span></a>
								<?php
							}
							else
							{
								echo form_button(array('id' => 'button-cancel', 'content' => 'Kembali', 'onClick' => "location.href='".site_url()."user'" ));
							}
						?>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</td>
</tr>
</table>

<script type="text/javascript">
	function checkError(response, postdata) {
		var success = true;
		var message = ""
		var json = eval('(' + response.responseText + ')');
		if(json.errors) {
			success = false;
			for(i=0; i < json.errors.length; i++) {
				message += json.errors[i];
			}
		}
		var new_id = json.id;
		return [success,message,new_id];
	}
	
	jQuery(document).ready(function(){
		$("#tabs").tabs({
			ajaxOptions: {
				error: function(xhr, status, index, anchor) {
					$(anchor.hash).html("Couldn't load this tab. We'll try to fix this as soon as possible. If this wouldn't be a demo.");
				}
			},
			cache : true
		});

		$("#simpan").hover(
			function() {
				$(this).addClass("<?php echo $this->css->hover();?>");
			},
			function() {
				$(this).removeClass("<?php echo $this->css->hover();?>");
			}
		);
		
		$("#batal").hover(
			function() {
				$(this).addClass("<?php echo $this->css->hover();?>");
			},
			function() {
				$(this).removeClass("<?php echo $this->css->hover();?>");
			}
		);
	});
	
	jQuery('#simpan').click(function(){
		jQuery('#pegawai_form').ajaxSubmit(
		)
	});
</script>