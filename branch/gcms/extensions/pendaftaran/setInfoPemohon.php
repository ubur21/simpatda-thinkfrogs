<?php

//if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();

include "./../../config.php";
include "./../../lib.php";

$qy = 'select 
	    a.alamat, a.no_telp,no_ktp, nama,
		b.lurah_nama,
		c.camat_nama
		from pemohon a
		join kelurahan b on b.lurah_id=a.id_desa
		join kecamatan c on c.camat_id=b.lurah_kecamatan where pemohon_id='.quote_smart($_REQUEST['val']);
$data = gcms_query($qy);

$rs = gcms_fetch_object($data);

?>
document.getElementById('no_ktp').value='<?=$rs->no_ktp?>';
document.getElementById('nama_pemohon').value='<?=$rs->nama?>';
document.getElementById('alamat').value='<?=showMultiLine($rs->alamat)?>';
document.getElementById('kecamatan').value='<?=$rs->camat_nama?>';
document.getElementById('kelurahan').value='<?=$rs->lurah_nama?>';
document.getElementById('telp').value='<?=$rs->no_telp?>';
