<?php

function Terbilang($x)
{
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
    return " " . $abil[$x];
  elseif ($x < 20)
    return Terbilang($x - 10) . "belas";
  elseif ($x < 100)
    return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
  elseif ($x < 200)
    return " seratus" . Terbilang($x - 100);
  elseif ($x < 1000)
    return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
  elseif ($x < 2000)
    return " seribu" . Terbilang($x - 1000);
  elseif ($x < 1000000)
    return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
  elseif ($x < 1000000000)
    return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}


function GetRekening($idRekening){
  switch ($idRekening) {
    case '01':$rekening='Pajak Hotel';break;
    case '02':$rekening='Pajak Restoran';break;
    case '03':$rekening='Pajak Hiburan';break;
    case '04':$rekening='Pajak Reklame';break;
    case '05':$rekening='Pajak Penerangan Jalan';break;
    case '06':$rekening='Pajak Mineral Logam dan Batuan';break;
    case '07':$rekening='Pajak Parkir';break;
    case '08':$rekening='Pajak Air Tanah';break;
    default:break;

  }
  return strtoupper($rekening);

}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1. Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
table.myTable { border-collapse:collapse; }
table.myTable td, table.myTable th { border:1px solid black;padding:5px; }
.judul1{font:arial;font-size:10px;}
.judul2{font:arial;font-size:14px;font-weight:bold;}
</style>
</head>

<body>

<?php
$id = $_REQUEST['id'];
$arrData = explode(',', $id);

for($i=0;$i<count($arrData);$i++){
 $data['data'] = $this->data_model->get_data_by_id($arrData[$i]);
 $rekening = substr($data['data']['KODE_REKENING'], 6,2);
 $namaPajak = GetRekening($rekening);
?>


<div style="page-break-after:always;">
<table class="myTable" width="100%" cellpadding="0">
<tr>
<td width="42%" height="50" align="center" valign="top">

    <?php
	$skpd_id = $this->Group_model->get_one('SKPD_ID','USERS','ID = '.$this->session->userdata('id_user'));
	$namaSkpd = $this->Group_model->get_one_skpd('NAMA_SKPD','SKPD','ID_SKPD = '.$skpd_id);
	$alamatSkpd = $this->Group_model->get_one_skpd('ALAMAT_SKPD','SKPD','ID_SKPD = '.$skpd_id);
	$telpSkpd = $this->Group_model->get_one_skpd('TELP_SKPD','SKPD','ID_SKPD = '.$skpd_id);
?>
    <span class="judul1">
        PEMERINTAH KOTA SALATIGA<br />
        <?php echo strtoupper($namaSkpd); ?><br />
        <?php echo strtoupper($alamatSkpd)." Telp. ".$telpSkpd; ?><br />
        </span>

</td>
<td width="40%" align="center" valign="top">

<span class="judul1">
        SURAT KETETAPAN PAJAK DAERAH<br />
      </span>
        <span class="judul2">
        (SKPD)<br />
        </span>
        <SPAN class="judul1">
    Periode : <?php echo date('d-m-Y',strtotime($data['data']['PERIODE_AWAL']))." s/d ".date('d-m-Y',strtotime($data['data']['PERIODE_AKHIR'])); ?><br />
    Tahun : <?php echo date('Y',strtotime($data['data']['PERIODE_AWAL'])); ?><br />
        </span>

</td>
<td width="18%" align="center">

<span class="judul1">
        SKPD NOMOR<br />
        <?php echo $data['data']['NOMOR_KOHIR']; ?>
      </span>

</td>
</tr>
</table>

<table class="myTable" width="100%" cellpadding="0">
<tr>
<td width="36%" height="50" valign="top">
  <br />

             <span class="judul1">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?php echo $data['data']['NAMA_WP']; ?><br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Alamat &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $data['data']['ALAMAT_WP']; ?><br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        NPWPD &nbsp;&nbsp;: <?php echo $data['data']['NPWPD']; ?><br /><br /><br />
        
        &nbsp;&nbsp;&nbsp;&nbsp;
        Tanggal Jatuh Tempo &nbsp;&nbsp;: <?php echo date('d-m-Y',strtotime($data['data']['TANGGAL_SPT'])); ?></span>

</td>
</tr>
</table>
<table class="myTable" width="100%" cellpadding="0">
  <tr>
    <td width="5%" align="center" valign="middle">
    <span class="judul1">
    No
    </span>
    </td>
  
     <td width="17%" align="center" valign="middle">
    <span class="judul1">
    Kode Rekening
    </span>
    </td>
    
     <td width="54%" align="center" valign="middle">
    <span class="judul1">
    Rincian Objek Pajak
    </span>
    </td>
    
     <td width="24%" align="center" valign="middle">
    <span class="judul1">
    Jumlah (Rp)
    </span>
    </td>
    
  </tr>
</table>
<table class="myTable" width="100%" cellpadding="0">
  <tr>
    <td width="5%" height="248" align="center" valign="middle"><span class="judul1">&nbsp;</span></td>
    <td width="17%" align="center" valign="middle"><span class="judul1"><?php echo $data['data']['KODE_REKENING']; ?></span></td>
    <td width="54%" valign="top"><span class="judul1">&nbsp;&nbsp;KETETAPAN <?php echo $namaPajak; ?>
     <br />
     <br /> 
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JENIS OBYEK PAJAK &nbsp; : &nbsp; <?php echo strtoupper($data['data']['NAMA_REKENING']); ?>
     <br />
     <br /> 


     <?php
     if($rekening=='04'){
     ?>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UKURAN &nbsp; : &nbsp; <?php echo $data['data']['PANJANG']." m  X  ".$data['data']['LEBAR']." m"; ?>
     <br />
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JUMLAH &nbsp; : &nbsp; <?php echo $data['data']['JUMLAH']." BUAH"; ?>
     <br />
     <br />
     <?php
     }
     ?>


     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PERIODE &nbsp; : &nbsp; <?php echo date('d-m-Y',strtotime($data['data']['PERIODE_AWAL']))." s/d ".date('d-m-Y',strtotime($data['data']['PERIODE_AWAL'])); ?>
     <br />
     <br /> 
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0.1 x <?php echo number_format($data['data']['JUMLAH'],0,',','.'); ?>
    </span></td>
    <td width="24%" valign="top" align="right">
      <br />
      <br />
      <br />
      Rp. <?php echo number_format($data['data']['JUMLAH_PAJAK'],0,',','.'); ?>
    </td>
  </tr>
</table>
<table class="myTable" width="100%" cellpadding="0">
  <tr>
    <td rowspan="2" align="center" valign="middle">
    </td>
  
     <td width="54%" valign="middle">&nbsp;
    <span class="judul1">
        &nbsp;&nbsp; Jumlah Ketetapan Pokok Pajak<br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Jumlah Sanksi : &nbsp;&nbsp;&nbsp;&nbsp; a. Bunga<br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         b. Kenaikan<br />
        </span>
    </td>
    
     <td width="24%" align="center" valign="middle">&nbsp;

    </td>
    
  </tr>
  <tr>
    <td height="31" valign="middle">
      <span class="judul1">
        &nbsp;&nbsp;&nbsp;&nbsp; JUMLAH<br />
        </span>
    </td>
    <td width="24%" valign="middle" align="right">Rp. <?php echo number_format($data['data']['JUMLAH_PAJAK'],0,',','.'); ?></td>
  </tr>
</table>


<table class="myTable" width="100%" cellpadding="0">
  <tr>
    <td width="5%"valign="middle" height="25">
    <span class="judul1">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dengan Huruf 
    &nbsp;&nbsp;&nbsp;<strong><?php echo strtoupper(Terbilang($data['data']['JUMLAH_PAJAK'])); ?>&nbsp; RUPIAH<strong> 
    </span>
    </td>
  
  </tr>
</table>


<table width="100%" height="80" cellpadding="0" class="myTable">
  <tr>
    <td width="5%"valign="middle" height="30">
    <span class="judul1"><br />
    &nbsp;&nbsp;<strong>P E R H A T I A N </strong><br />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    1. HarappenyetorandilakukanpadaKas Daerah atautempat yang lain ditunjukdenganmenggunakanSuratSetoranPajak Daerah<br />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    2. Apabila SKPD initidakataukurangdibayarlewattanggaljatuh tempo, makaWajibPajakakandikenakansanksiAdministrasi
  berupabungasebesar 2 % per bulan
  <br /><br />
    </span>
    </td>
  
  </tr>
</table>
<table width="100%" height="80" cellpadding="0" class="myTable">
  <tr>
    <td width="5%"valign="middle" align="right" height="30"><br />
      <span class="judul1">
      SALATIGA, __________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
a.n KA. DPPKAD KOTA SALATIGA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
KEPALA BIDANG PENETAPAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br /><br /><br /><br /><br /><br />




____________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
NIP. ___________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />

      </span>
      <br /><br />
      </td>
  </tr>
</table>


</div>

<?php
}
?>


</body>
</html>


