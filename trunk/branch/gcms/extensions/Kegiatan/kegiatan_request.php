<?php
include('../../global.php');
  
          
$crudColumns =  array(
	'id'=>'id'
	,'id_kelompok' => 'id_kelompok'
    ,'id_skpd'=>'id_skpd'
	,'kode'=>'kode_kegiatan'
	,'nama'=>'nama_kegiatan'
	,'pegawai'=>'belanja_pegawai'
	,'barang_jasa'=>'belanja_barang_jasa'
	,'modal'=>'belanja_modal'
    ,'total'=>'total'
    ,'indikator_1'=>'indikator_1'
	,'indikator_2'=>'indikator_2'
	,'indikator_3'=>'indikator_3'
    ,'indikator_4'=>'indikator_4'
);
$crudTableName = 'kegiatan';
$crudFK = 'id_kelompok';
$crudAutoField = 'total';

include '../../jqGridCrud.php';
?>
