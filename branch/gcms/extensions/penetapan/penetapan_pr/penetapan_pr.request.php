<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='get_tgl_setor'){

	$tempo = b_fetch('select jatem_bayar from ref_jatuh_tempo');
	$tgl_setor = getExpired($_REQUEST['tgl'],$tempo);
	echo $tgl_setor;
	
}elseif($_REQUEST['sender']=='set_nomor'){
	
	$no_kohir = getNoKohir($_REQUEST['tgl']);
	
	echo sprintf('%05d',$no_kohir);
	
}elseif($_REQUEST['sender']=='pilih_kohir'){

	$ID='penetapan_pr_';

	$crudColumns =  array(
		'id'=>'penetapan_pr_id'
		,'nomor'=>'no_penetapan'
		,'tgl'=>'tgl_penetapan'
		,'setor'=>'tgl_setor'
		,'nominal'=>'nama'
	);
	
	$crudTableName = 'penetapan_pr';
	
	include $expath.'handler_pilih_kohir.php';

}
elseif($_REQUEST['sender']=='pilih_spt'){

	$ID='penetapan_pr_';

	$crudColumns =  array(
		'id'=>'penetapan_pr_id'
		,'nomor'=>'no_penetapan'
		,'tgl'=>'tgl_penetapan'
		,'setor'=>'tgl_setor'
		,'nominal'=>'nama'
	);
	
	$crudTableName = 'penetapan_pr';
	
	include $expath.'handler_pilih_no_pendataan.php';

}
elseif($_REQUEST['sender']=='daftar'){

	$crudColumns = array(
		'id'=>'penetapan_pr_id',
		'no_kohir'=>'no_penetapan',
		'tgl_kohir'=>'tgl_penetapan',
		'tgl_setor'=>'tgl_setor',
		'nominal'=>'nominal',
		'pendataan_no'=>'pendataan_no',
		'periode_awal'=>'periode_awal',
		'periode_akhir'=>'periode_akhir',
		'jenis'=>'jenis'
	);
	
	include $expath.'handler_daftar_kohir.php';	

}
elseif($_REQUEST['sender']=='default'){

	$crudColumns =  array(
		'pendataan_id'=>'pendataan_id'
		,'pendataan_no'=>'pendataan_no'
		,'tgl_entry'=>'tgl_entry'
		,'npwp'=>'npwp'
		,'nama'=>'nama'
		,'jenis_pendataan'=>'jenis_pendataan'
		,'jenis_pungutan'=>'jenis_pungutan'
		,'jenis_pendaftaran'=>'jenis_pendaftaran'
		,'spt_no'=>'spt_no'
		,'spt_tgl'=>'spt_tgl'
		,'nominal'=>'nominal'
	);
		
	$ID='pendataan_';
	
	$crudTableName = 'v_pendataan';
		
	include $expath.'handler_penetapan_pr.php';
		
}

?>