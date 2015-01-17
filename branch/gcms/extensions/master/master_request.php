<?php
include('../../config.php');
include('../../global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=="list_pemda"){
	$crudColumns =  array(
		'id'=>'PEMDA_ID'
		,'KabKota'=>'PEMDA_DATI'
		,'Pejabat'=>'PEMDA_PEJABAT'
		,'NamaKabKota'=>'PEMDA_NAMA'
		,'Alamat'=>'PEMDA_LOKASI'
		,'IbuKotaKabKota'=>'PEMDA_IBUKOTA'
		,'NoTlp'=>'PEMDA_TELP'
		,'NamaBank'=>'PEMDA_BANK'
		,'NoRek'=>'PEMDA_BANK_NOREK'
		);
		
	$crudTableName = 'PEMDA';
	$ID="PEMDA_";
	//include '../../jqGridCrud.php';
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
	
	//include '../../jqGridCrud.php';
}
if($_REQUEST['sender']=="list_kecamatan"){

	$crudColumns =  array(
		'id'=>'id'
		,'No'=>'kode_skpd'
		,'Kode'=>'1'
		,'Nama'=>'nama_skpd'
		
		);
		
	$crudTableName = 'skpd';
}
if($_REQUEST['sender']=="list_kelurahan"){
	
	$crudColumns =  array(
		'id'=>'id'
		,'KodeKecamatan'=>'kode_skpd'
		,'KodeKelurahan'=>'23'
		,'NamaKelurahan'=>'nama_skpd'
		,'NamaKecamatan'=>'nama_skpd'
		);
		
	$crudTableName = 'kelurahan';
}

if($_REQUEST['sender']=="list_keterangan_spt"){
	$crudColumns =  array(
		'id'=>'id'
		,'Kode'=>'kode_skpd'
		,'Nama'=>'nama_skpd'
		,'Singkatan'=>'1'
		,'JenisPemungutan'=>'2'
		);
		
	$crudTableName = 'skpd';
	
	//include '../../jqGridCrud.php';
}

if($_REQUEST['sender']=="list_printer"){
	$crudColumns =  array(
		'id'=>'id'
		,'Default'=>'kode_skpd'
		,'Nama'=>'nama_skpd'
		,'TTY'=>'1'
		,'Alamat'=>'2'
		,'Keterangan'=>'2'
		);
		
	$crudTableName = 'skpd';
}

include '../../jqGridCrud.php';
?>