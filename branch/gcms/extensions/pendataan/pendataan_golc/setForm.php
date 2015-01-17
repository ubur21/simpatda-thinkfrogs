<?php

include "./../../../config.php";
include "./../../../lib.php";

$id_pemohon = b_fetch('select id_pemohon from pendaftaran where pendaftaran_id='.quote_smart($_REQUEST['val']));
$npwp = b_fetch('select npwp from pendaftaran where pendaftaran_id='.quote_smart($_REQUEST['val']));
$jenis = b_fetch('select objek_pdrd from pendaftaran where pendaftaran_id='.quote_smart($_REQUEST['val']));
$spt_no = b_fetch('select b.spt_no from pendaftaran a join spt b on b.pendaftaran_id=a.pendaftaran_id where a.pendaftaran_id='.quote_smart($_REQUEST['val']));
$spt_id = b_fetch('select b.spt_id from pendaftaran a join spt b on b.pendaftaran_id=a.pendaftaran_id where a.pendaftaran_id='.quote_smart($_REQUEST['val']));

$spt_no = ($spt_no!='') ? sprintf('%05d',$spt_no) : '';
$wp_wr_gol = substr($npwp,1,1);
$wp_wr_no_urut = substr($npwp,2,5);
$wp_wr_kd_camat = substr($npwp,7,2);
$wp_wr_kd_lurah = substr($npwp,9,2);

if($jenis=='PRIBADI'){
	$qy = 'select a.nama, a.alamat, b.lurah_nama, c.camat_nama 
		   from pemohon a 
		   join kelurahan b on a.id_desa=b.lurah_id
		   join kecamatan c on c.camat_id=b.lurah_kecamatan
		   where a.pemohon_id='.quote_smart($id_pemohon);

	$data = gcms_query($qy);
	$rs = gcms_fetch_object($data);
}else{
	$qy = 'select a.nama, a.alamat, b.lurah_nama, c.camat_nama 
		   from badan_usaha a 
		   join kelurahan b on a.badan_id_desa=b.lurah_id
		   join kecamatan c on c.camat_id=b.lurah_kecamatan		   
		   where a.id='.quote_smart($id_pemohon);
	$data = gcms_query($qy);
	$rs = gcms_fetch_object($data);
}

?>
document.getElementById('pendaftaran_id').value='<?=$_REQUEST['val']?>';
document.getElementById('nomor_spt').value='<?=$spt_no?>';
document.getElementById('spt').value='<?=$spt_id?>';
document.getElementById('nama_pemohon').value='<?=$rs->nama?>';
document.getElementById('alamat').value='<?=showMultiLine($rs->alamat)?>';
document.getElementById('kecamatan').value='<?=$rs->camat_nama?>';
document.getElementById('kelurahan').value='<?=$rs->lurah_nama?>';
document.getElementById('wp_wr_gol').value='<?=$wp_wr_gol?>';
document.getElementById('wp_wr_no_urut').value='<?=$wp_wr_no_urut?>';
document.getElementById('wp_wr_kd_camat').value='<?=$wp_wr_kd_camat?>';
document.getElementById('wp_wr_kd_lurah').value='<?=$wp_wr_kd_lurah?>';