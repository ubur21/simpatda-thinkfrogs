<?php
include('../../global.php');

$crudColumns =  array(
	'id'=>'id'
	,'kode'=>'kode_kelompok'
	,'nama'=>'nama_kelompok'
	,'keterangan'=>'keterangan'
    ,'indikator1'=>'nama_indikator_1'
    ,'indikator2'=>'nama_indikator_2'
    ,'indikator3'=>'nama_indikator_3'
    ,'indikator4'=>'nama_indikator_4'
);
$crudTableName = 'kelompok_kegiatan';

include '../../jqGridCrud.php';
?>
