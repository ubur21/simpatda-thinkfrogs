<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='daftarnpwp'){

	$crudColumns =  array(
		'id'=>'pendaftaran_id'
		,'NoPendaftaran'=>'no_pendaftaran'
		,'NPWP'=>'npwp'
		,'Nama'=>'nama'
		,'Alamat'=>'alamat'
		//,'Kelurahan'=>'wp_wr_lurah'
		//,'Kecamatan'=>'wp_wr_camat'
		//,'Kabupaten'=>'wp_wr_kabupaten'
		
	);
		
	$crudTableName = 'PENDAFTARAN';
	$ID = 'PENDAFTARAN_';
	include $expath.'jqGridCrud.php';


}
?>