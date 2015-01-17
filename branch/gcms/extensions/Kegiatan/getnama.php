<?php
include('./../../global.php');

$bid = isset($_REQUEST['bid'])?$_REQUEST['bid']:1;

        $sql = 'select first 1 nama_indikator_1, nama_indikator_2, nama_indikator_3, nama_indikator_4 '.
               'from kelompok_kegiatan where id = '.$bid;
        $result = gcms_query($sql);
        while ($row = gcms_fetch_object($result)){
            $nama .= "indikator_1:".$row->nama_indikator_1.";";
            $nama .= "indikator_2:".$row->nama_indikator_2.";";
            $nama .= "indikator_3:".$row->nama_indikator_3.";";
            $nama .= "indikator_4:".$row->nama_indikator_4;
        }
        echo $nama;
?>