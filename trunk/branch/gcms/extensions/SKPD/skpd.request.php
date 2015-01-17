<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='pilih'){

	$crudColumns = array(
		'id'=>'id'
		,'kode'=>'kode_skpd'
		,'nama'=>'nama_skpd'		
	);
		
	$ID='';
	
	$crudTableName = 'skpd';
	
	include $expath.'handler_pilih_skpd.php';

}else{

	$crudColumns =  array(
		'id'=>'id'
		,'kode'=>'kode_skpd'
		,'nama'=>'nama_skpd'
	);
    
	$crudTableName = 'skpd';

	include '../../jqGridCrud.php';
}
?>
