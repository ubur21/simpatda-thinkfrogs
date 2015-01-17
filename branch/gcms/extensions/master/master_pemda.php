<?php

if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();

if($_REQUEST['proses']=='Simpan'){
	$n = b_fetch("select count(*) from info_pemda");
	
	/*
	Cek Nilai n, 
	Jika kosong berarti Buat Baru, 
	else Update
	*/
	if(!$n){
		$id=1; // inisialisasi, bahwa hanya ada satu data yang tersimpan
		$img_path = './images/pemda'; //target penyimpanan file logo
		$csql='insert into info_pemda(id,pemda_nama,kode_lokasi,opt_kab,pejabat,pemda_alamat,pemda_kabupaten,
				ibu_kota,pemda_telp,pemda_fax,nama_bank,no_rekening,logo,tipe_gambar) 
				values('.
				quote_smart($id).','."'',".
				quote_smart($_REQUEST['kodeLokasi']).','.
				quote_smart($_REQUEST['kabKota']).','.
				quote_smart($_REQUEST['pejabat']).','.
				quote_smart($_REQUEST['alamat']).','.
				quote_smart($_REQUEST['namaKab']).','.
				quote_smart($_REQUEST['ibukotaKab']).','.
				quote_smart($_REQUEST['telp']).','.
				quote_smart($_REQUEST['fax']).','.
				quote_smart($_REQUEST['namaBank']).','.
				quote_smart($_REQUEST['noRek']);

		if(!empty($_FILES['logo']['tmp_name'])){

			$tmpName = $_FILES['logo']['tmp_name'];
			$fileType = $_FILES['logo']['type'];
			global $fbdb;
			$blh = ibase_blob_create($fbdb);
			if($_FILES['logo']['tmp_name']!=''){
				ibase_blob_add($blh, file_get_contents($_FILES['logo']['tmp_name']));
			}
			$blobid = ibase_blob_close($blh);
				
			$csql.=',?,'.sqlvalue($fileType,true).')';
				
			$target_file = $img_path.basename($_FILES['logo']['name']);	
				
			if(!move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)){
			
				echo 'File Gambar Gagal Di upload';
				
			}		
			ibase_query($fbdb, $csql, $blobid);
		}else{
			$csql.=',null,null)';
			gcms_query($csql);
		}
	}else{

		$csql = 'update info_pemda set '.				
				'kode_lokasi='.quote_smart($_REQUEST['kodeLokasi']).','.
				'opt_kab='.quote_smart($_REQUEST['kabKota']).','.
				'pejabat='.quote_smart($_REQUEST['pejabat']).','.
				'pemda_alamat='.quote_smart($_REQUEST['alamat']).','.
				'pemda_kabupaten='.quote_smart($_REQUEST['namaKab']).','.
				'ibu_kota='.quote_smart($_REQUEST['ibukotaKab']).','.
				'pemda_telp='.quote_smart($_REQUEST['telp']).','.
				'pemda_fax='.quote_smart($_REQUEST['fax']).','.
				'nama_bank='.quote_smart($_REQUEST['namaBank']).','.
				'no_rekening='.quote_smart($_REQUEST['noRek']);	
				
		if(!empty($_FILES['logo']['tmp_name'])){

			$tmpName = $_FILES['logo']['tmp_name'];
			//$fileSize = filesize($tmpName);
			$fileType = $_FILES['logo']['type'];
				
			global $fbdb;
			$blh = ibase_blob_create($fbdb);
			if($_FILES['logo']['tmp_name']!=''){
				ibase_blob_add($blh, file_get_contents($_FILES['logo']['tmp_name']));
			}
			$blobid = ibase_blob_close($blh);

			$csql.=",logo=?,tipe_gambar='".$fileType."'";
						
			$target_file = './images/pemda/'.basename($_FILES['logo']['name']);	
			
			if(!move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)){
				echo "File Gambar Gagal Di upload";
			}
			
			ibase_query($fbdb, $csql, $blobid);
				
		}else{
			$csql.=',logo=null,tipe_gambar=null';
			gcms_query($sql);
		}
	
	}	

}
unset($_FILES['logo']);
$csql="select * from info_pemda";
$nresult=gcms_query($csql);
if(!gcms_is_empty($nresult))
{
	$rs = gcms_fetch_object($nresult);
}
?>
<script>
//gcms_yui_button("btn_entri_save", func_submit);

function func_submit(){

}
</script>
<style>
.yui-button#btn_entri_save button { background: url(images/save.png) 10% 50% no-repeat; padding-left: 2em; }
</style>
<form enctype="multipart/form-data" name='xform' id='xform' action="form.php?page=<?=$_REQUEST['page']?>" method="POST" AUTOCOMPLETE="off">
<input type="hidden" name="MAX_FILE_SIZE" value="5000000000" />
<div style='padding:5px;'>
	<fieldset>
	<legend>Form</legend>
		<div id='asb_simulasi_form'>
			<div class="singleSide">
				<fieldset class="mainForm">
				<label class="leftField"><span>Kode Lokasi <b class="wajib">*</b></span><input type="text" name="kodeLokasi" value="<?=$rs->kode_lokasi?>" size="2" maxlength="2" /></label>
				<label class="leftField"><span>Kabupaten/Kota <b class="wajib">*</b></span><select name="kabKota"><option value="Kabupaten" <?=$rs->opt_kab=='Kabupaten' ? 'selected' :''?>>Kabupaten</option><option value="Kotamadya" <?=$rs->opt_kab=='Kotamadya' ? 'selected' :''?>>Kotamadya</option></select></label>
				<label class="leftField"><span>Pejabat Kab/Kota <b class="wajib">*</b></span><select name="pejabat"><option value="Bupati" <?=$rs->opt_kab=='Bupati' ? 'selected' :''?>>Bupati</option><option value="Walikota" <?=$rs->opt_kab=='Walikota' ? 'selected' :''?>>Walikota</option></select></label>
				<label class="leftField"><span>Alamat Kantor <b class="wajib">*</b></span><textarea name="alamat" col="4" rows="3" style='width:300'><?=$rs->pemda_alamat?></textarea></label>
				<label class="leftField"><span>Nama Kabupaten/Kota</span><input type="text" name="namaKab" value="<?=$rs->pemda_kabupaten?>" size="32" maxlength="20" /></label>
				<label class="leftField"><span>Ibukota Kab/Kota</span><input type="text" name="ibukotaKab" value="<?=$rs->ibu_kota?>" size="32" maxlength="20" /></label>
				<label class="leftField"><span>Nomor Telp. Kantor</span><input type="text" name="telp" value="<?=$rs->pemda_telp?>" size="32" maxlength="20" /></label>
				<label class="leftField"><span>Nomor Fax Kantor</span><input type="text" name="fax" value="<?=$rs->pemda_fax?>" size="32" maxlength="20" /></label>
				</fieldset>
				</div>
				<div>
				<fieldset class="mainForm">
				<label class="leftField"><span>Nama Bank</span><input type="text" name="namaBank" value="<?=$rs->nama_bank?>" size="38" maxlength="20" /></label>
				<label class="leftField"><span>Nomor Rekening Bank</span><input type="text" name="noRek" value="<?=$rs->no_rekening?>" size="38" maxlength="20" /></label>
				<label class="leftField"><span>Logo Pemerintah Daerah</span><input type="file" name="logo" id="logo" /></label>
				<label class="leftField"><span><b class="wajib">*</b>&nbsp;Wajib Diisi</span><input id="btn" type="submit" name="proses" value="Simpan"></label>
				<div style='border:1px solid gray;background-color:#fff;padding:3px;margin-top:2px' class="leftField">
				<? 	if(!empty($rs->logo)){
						echo "<img src=\"showimage.php?id=1\" height=\"133\">"; 
						//echo '<img src="images/garuda.gif" width="120" height="120">';
					}else{ ?>
						<img src="images/garuda.gif" width="120" height="120">
				<?	} ?>	
				<div>

				</fieldset>
				</div>
				<div id="confirmDialog" class="hidden">
				<fieldset class="mainForm">
				<label class="leftField"><span>Nama User</span><input type="text" name="namaUser" value="" size="10" maxlength="20" /></label>
				<label class="leftField"><span>Password</span><input type="password" name="password" value="" size="10" maxlength="20" />
				<input class="closeForm" type="button" name="close" value="Batal" onclick="closeForm('confirmDialog');" /></label>
				</fieldset>
			</div>
			<div class="footer_space">&nbsp;</div>
		</div>		
	</fieldset>
</div>
</form>