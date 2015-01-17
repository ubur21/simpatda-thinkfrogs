<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='lokasi_reklame'){

	$crudColumns =  array(
		'id'=>'lokasi_id'
		,'kode'=>'lokasi_kode'
		,'lokasi'=>'lokasi_nama'
		,'bobot'=>'lokasi_bobot'
		,'bhide'=>'bhide'
		);
	
	$ID='lokasi_';	
	$crudTableName = 'reklame_lokasi';

	include 'jqGridCrud.php';
	
}
elseif($_REQUEST['sender']=='jenis_reklame'){

	$crudColumns =  array(
		'id'=>'jenis_id'
		,'kode'=>'jenis_kode'
		,'lokasi'=>'jenis_nama'
		,'bobot'=>'jenis_bobot'
		,'bhide'=>'bhide'
		);
	
	$ID='jenis_';	
	$crudTableName = 'reklame_jenis';

	include 'jqGridCrud.php';
	
}
elseif($_REQUEST['sender']=='tahun_anggaran'){

	$crudColumns =  array(
		'id'=>'id'
		,'status'=>'id_status'
		,'tahun'=>'tahun1'
	);
		
	$crudFK='id';
	
	$crudTableName = 'anggaran_tahun';
	
	$table1 = 'anggaran_tahun';
	$pk_table1 = 'id';
	
	$table2 = 'anggaran_status';
	$pk_table2 = 'id';

	include $expath.'handler_anggaran_tahun.php';


}else{

	$crudColumns =  array(
		'id'=>'id'
		,'kode'=>'kode_skpd'
		,'nama'=>'nama_skpd'
	);
		
	$crudTableName = 'skpd';

	include 'jqGridCrud.php';

}
?>