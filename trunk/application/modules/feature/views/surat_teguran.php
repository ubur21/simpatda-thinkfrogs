<?php

function bulan($x)
{
  $bulan = array("01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember");
  return $bulan[$x];
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
$i = 0;
    foreach($data as $row)
    {
?>

<div style="page-break-after: always;">
<table class="myTable" width="100%" cellpadding="0" border="0" >
<tr>
	<td rowspan="6"><img src="../../../../../assets/img/logo.png" height="40" width="20"> </td>
</tr>
<tr>
	<td width="20px"></td>
	<td align="center">PEMERINTAH KOTA SALATIGA</td>
</tr>
<tr>
	<td width="20px"></td>
	<td align="center">DINAS PENDAPATAN PENGELOLAAN KEUANGAN DAN ASET DAERAH</td>
</tr>
<tr>
	<td width="20px"></td>
	<td align="center">Jalan Let.Jend Sukowati No. 51 Salatiga Kode Pos 50724 Telp. (0298) 327097</td>
</tr>
<tr>
	<td width="20px"></td>
	<td align="center">Faks.(0298)327097 Website www.salatigakota.go.id</td>
</tr>
<tr>
	<td width="20px"></td>
	<td align="center"><u>SALATIGA</u></td>
</tr>
</table>
<hr>
<table class="myTable" width="100%" cellpadding="0" border="0">
	<tr>
	<td align="center">SURAT TEGURAN</td>
</tr>
<tr>
	<td align="center">PELAPORAN SPTPD</td>
</tr>
<tr>
	<td align="center">Nomor : </td>
</tr>
<tr>
	<td align="left">Kepada Yth, </td>
</tr>
<tr>
	<td align="left">Nama WP : <?=$row->NAMA_WP?></td>
</tr>
<tr>
	<td align="left">Alamat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?=$row->ALAMAT_WP?></td>
</tr>
</table>

<br/>
<br/>

<p style="width:100%;text-align:justify">
 &nbsp;&nbsp;&nbsp;&nbsp; Menurut catatan administrasi kami, sampai dengan tanggal <?php echo date('d')." ".bulan(date('m'))." ".date('Y');  ?>. Saudara belum menyampaikan Surat Pemberitahuan Pajak Daerah(SPTPD) kepada Dinas Pendapatan Pengelolaan Keuangan dan Aset Daerah (DPPKAD) Kota Salatiga sebagai bukti pelaporan pajak daerah, untuk masa pajak : <?php echo "01"." ".bulan(date('m'))." ".date('Y')." s/d ".date('t')." ".bulan(date('m'))." ".date('Y');  ?>
</p>
<p style="width:100%;text-align:justify">
 &nbsp;&nbsp;&nbsp;&nbsp; Untuk itu, kami beritahukan agar Saudara segera melaporkan Pajak dengan mengisi dan menyampaikan SPTPD <b> paling lambat Tanggal 20 <?php echo bulan(date('m'))." ".date('Y') ?></b>. Apabila sampai dengan batas waktu tersebut dipenuhi, maka sesuai dengan Undang-Undang No. 28 Tahun 2009 tentang Pajak Daerah dan Retribusi dan Perda Kota Salatiga No.11 Tahun 2011 tentang Pajak daerah, maka SPTPD saudara <b><u>tidak dapat</u></b> kami terima dan selanjutnya akan <b><u>ditetapkan oleh petugas</u></b> sesuai dengan Peraturan Perundangan yang berlaku.
</p>
<p style="width:100%;text-align:justify">
 &nbsp;&nbsp;&nbsp;&nbsp; Demikian atas perhatian dan kerjasamanya, kami mengucapkan terima kasih.
 </p>

 <br/>
 <br/>
 <div style="width:100%;margin-left:400px;">
	Salatiga,&nbsp;<?php echo date('d')." ".bulan(date('m'))." ".date('Y');  ?>
 </div>
  <div style="width:100%;margin-left:400px;">
	a.n Ka. DPPKAD Kota Salatiga
 </div>


 <table class="myTable" width="100%" cellpadding="0" border="0" >
<tr>
	<td align="center" width="70px">Penerima</td><td align="center" width="100px">Kabid. Pendaftaran dan Pendataan</td>
</tr>
 </table>
 <br/>
 <br/>
 <br/>
 <br/>
  <table class="myTable" width="100%" cellpadding="0" border="0" >
<tr>
	<td align="center" width="70px">(<?=$row->NAMA_WP?>)</td><td align="center" width="100px">(Drs. DANUS KUSTIANTO, MM)</td>
</tr>
 </table>
   <table class="myTable" width="100%" cellpadding="0" border="0" >
<tr>
	<td align="center" width="70px">&nbsp;</td><td align="center" width="100px">NIP. 19671213 199503 1 001</td>
</tr>
 </table>
   <table class="myTable" width="100%" cellpadding="0" border="0" >
<tr>
	<td align="center" width="70px"><font size="6pt"><b><u>NB :apabila sudah melaporkan SPTPD, mohon abaikan surat ini</u></b></font></td><td align="center" width="100px">Pembina</td>
</tr>
 </table>
</div>

<?php
  $i++;
    }
?>


</body>
</html>


