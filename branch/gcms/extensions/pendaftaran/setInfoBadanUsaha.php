<?php

//if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();

include "./../../config.php";
include "./../../lib.php";

$qy = 'select 
		
	    a.badan_alamat, a.badan_telp, a.badan_fax,
		a.pemilik_nama, a.pemilik_alamat, a.pemilik_telp, a.pemilik_hp,
		c.camat_nama as camat_bu,
		b.lurah_nama as lurah_bu,
		(select b.lurah_nama from kelurahan b where b.lurah_id=a.pemilik_id_desa) as lurah_pemilik,
		(select c.camat_nama from kecamatan c join kelurahan b on c.camat_id=b.lurah_id where b.lurah_id=a.pemilik_id_desa) as camat_pemilik
		
		from badan_usaha a
		join kelurahan b on b.lurah_id=a.badan_id_desa
		join kecamatan c on c.camat_id=b.lurah_kecamatan where id='.quote_smart($_REQUEST['val']);
		
$data = gcms_query($qy);

$rs = gcms_fetch_object($data);

?>
document.getElementById('alamat_bu').value='<?=showMultiLine($rs->badan_alamat)?>';
document.getElementById('kecamatan_bu').value='<?=$rs->camat_bu?>';
document.getElementById('kelurahan_bu').value='<?=$rs->lurah_bu?>';
document.getElementById('telp_bu').value='<?=$rs->badan_telp?>';
document.getElementById('fax_bu').value='<?=$rs->badan_fax?>';
document.getElementById('nama_pk').value='<?=$rs->pemilik_nama?>';
document.getElementById('alamat_pk').value='<?=showMultiLine($rs->pemilik_alamat)?>';
document.getElementById('kecamatan_pk').value='<?=$rs->camat_pemilik?>';
document.getElementById('kelurahan_pk').value='<?=$rs->lurah_pemilik?>';
document.getElementById('telp_pk').value='<?=$rs->pemilik_telp?>';
document.getElementById('hp_pk').value='<?=$rs->pemilik_hp?>';
