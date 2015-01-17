<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='pendaftaran_pemohon'){

	$ID = 'PEMOHON_';

	$crudColumns =  array(
		'id'=>'pemohon_id'
		,'nama'=>'nama'
		,'ktp'=>'no_ktp'
		,'tempat'=>'tempat_lahir'
		,'tanggal'=>'tanggal_lahir'
		,'alamat'=>'alamat'
		,'rt'=>'rt'
		,'rw'=>'rw'
		,'desa'=>'id_desa'
		,'no_telp'=>'no_telp'
		,'no_hp'=>'no_hp'
		);
	
	$crudTableName = 'pemohon';
	
	//include 'jqGridCrud.php';
	include $expath.'handler_pendaftaran_pemohon.php';


}elseif($_REQUEST['sender']=='pendataan_spt'){

	echo 
		'{
			"page":"1",
			"total":0,
			"records":0,
			"rows":[
				{"id":0,"cell":[0,"","","","","","",""]}
			]
		}';


}elseif($_REQUEST['sender']=='set_form_reklame'){
	switch($_REQUEST['version']){
		case '1':
			include 'form_reklame_ver1.php';
		break;
		case '2':
			include 'form_reklame_ver2.php';
		break;
		case '3':
			include 'form_reklame_ver3.php';
		break;	
		default:
			include 'form_reklame_ver1.php';
	}
}
else{
	
	switch($postConfig['action']){
		case $crudConfig['read']:
			echo '{"page":"1","total":1,"records":null}';
				 
		break;
		case $crudConfig['create']:
			echo 'create';
		break;
		case $crudConfig['update']:
			echo 'update';
		break;
		case $crudConfig['delete']:
			echo 'delete';
		break;
	}

}
?>