<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=="list_kecamatan"){

	$ID = 'CAMAT_';
	
	$crudColumns =  array(
		'id'=>'camat_id'
		,'camat_kode'=>'camat_kode'
		,'camat_nama'=>'camat_nama'
		);

	$crudTableName = 'kecamatan';
	
	include 'jqGridCrud.php';
}


if($_REQUEST['sender']=="list_kelurahan"){

	$ID = 'LURAH_';
	
	$crudColumns =  array(
		'id'=>'lurah_id'
		,'lurah_kode'=>'lurah_kode'
		,'lurah_nama'=>'lurah_nama'
		,'lurah_kecamatan'=>'lurah_kecamatan'
		);
	
	$crudTableName = 'kelurahan';
	
	include $expath.'handler_kelurahan.php';
}


if($_REQUEST['sender']=="kode_usaha"){
	
	$crudColumns =  array(
		'id'=>'id'
		,'kode'=>'kode'
		,'nama'=>'nama'
		);

	$crudTableName = 'kode_usaha';
	
	include 'jqGridCrud.php';
}

if($_REQUEST['sender']=="pos_anggaran"){
	
	$crudColumns =  array(
		'id'=>'id'
		,'kode'=>'kode'
		,'nama'=>'nama'
		);

	$crudTableName = 'pos_anggaran';
	
	include 'jqGridCrud.php';
}

if($_REQUEST['sender']=="pangkat"){
	
	$crudColumns =  array(
		'id'=>'id'
		,'nama'=>'nama'
		);

	$crudTableName = 'pangkat';
	
	include 'jqGridCrud.php';
}

if($_REQUEST['sender']=="golongan"){
	
	$crudColumns =  array(
		'id'=>'id'
		,'nama'=>'nama'
		);

	$crudTableName = 'golongan';
	
	include 'jqGridCrud.php';
}

if($_REQUEST['sender']=="jabatan"){
	
	$crudColumns =  array(
		'id'=>'id'
		,'nama'=>'nama'
		);

	$crudTableName = 'jabatan';
	
	include 'jqGridCrud.php';
}

if($_REQUEST['sender']=="pejabat"){

	$ID = 'PEJABAT_';
	
	$crudColumns =  array(
		'id'=>'pejabat_id'
		,'nama'=>'nama'
		,'jabatan'=>'jabatan_id'
		,'golongan'=>'golongan_id'
		,'pangkat'=>'pangkat_id'
		,'nip'=>'nip'
		,'status'=>'status'		
		);
	
	$crudTableName = 'pejabat';
	
	include 'jqGridCrud.php'; 	
}


if($_REQUEST['sender']=="list_pemda"){
$crudColumns =  array(
	'id'=>'id'
	,'KabKota'=>'kode_skpd'
	,'Pejabat'=>'nama_skpd'
	,'NamaKabKota'=>'1'
	,'Alamat'=>'2'
	,'IbuKotaKabKota'=>'3'
	,'NoTlp'=>'4xx'
	,'NamaBank'=>'5'
	,'NoRek'=>'xxx1234'
	);
    
	$crudTableName = 'skpd';

	include '../../jqGridCrud.php';
}

if($_REQUEST['sender']=="list_satuan_kerja"){
$crudColumns =  array(
	'id'=>'id'
	,'Instansi'=>'kode_skpd'
	,'Urusan'=>'nama_skpd'
	,'Bidang'=>'1'
	,'Kode'=>'2'
	,'Nama'=>'3'
	,'NPWP'=>'4xx'
	,'BidangTambahan'=>'5'
	);
    
	$crudTableName = 'skpd';

	include '../../jqGridCrud.php';
}


?>