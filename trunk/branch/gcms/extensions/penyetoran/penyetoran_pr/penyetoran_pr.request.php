<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='set_nomor'){

	$no_sts = getNoPenyetoran($_REQUEST['tgl']);
	
	echo sprintf('%05d',$no_sts);

}
elseif($_REQUEST['sender']=='pilih'){

	$ID='penerimaan_pr_';

	$crudColumns =  array(
		'id'=>'penetapan_pr_id'
		,'nomor'=>'no_penetapan'
		,'tgl'=>'tgl_penetapan'
		,'setor'=>'tgl_setor'
		,'nominal'=>'nama'
	);
	
	$crudTableName = 'penerimaan_pr';
	
	include $expath.'handler_pilih_penerimaan.php';

}
elseif($_REQUEST['sender']=='daftar_sts'){
	$crudColumns =  array(
		'id'=>'sts_id'
		,'tanggal'=>'sts_tgl'
		,'nominal'=>'nama'
		,'keterangan'=>'keterangan'
	);
	
	include $expath.'handler_daftar_sts.php';

}
elseif($_REQUEST['sender']=='rincian_sts'){

	$crudColumns =  array(
		'id'=>'sts_content_id'
		,'kode'=>'kode_rekening'
		,'nama'=>'nama_rekening'
		,'nominal'=>'nominal'
	);
	
	include $expath.'handler_rincian_sts.php';

}
elseif($_REQUEST['sender']=='default'){

	echo '{"page":"1","total":0,"records":null}';
	
}

?>