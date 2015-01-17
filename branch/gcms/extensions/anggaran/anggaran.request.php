<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='status_anggaran'){

	$crudColumns =  array(
		'id'=>'id'
		,'label'=>'status'
		);
		
	$crudTableName = 'anggaran_status';

	include 'jqGridCrud.php';
	
}elseif($_REQUEST['sender']=='tahun_anggaran'){

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