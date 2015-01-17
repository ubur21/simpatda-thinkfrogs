<?php
include('../../config.php');
include('../../global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";



$crudColumns =  array(
	'id'=>'id'
	,'kode'=>'kode_skpd'
	,'nama'=>'nama_skpd'
	);
    
$crudTableName = 'skpd';

include '../../jqGridCrud.php';
?>
