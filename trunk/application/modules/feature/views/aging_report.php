
<?php
 header("Content-type: application/octet-stream");
 header("Content-Disposition: attachment; filename=laporan.xls");
 header("Pragma: no-cache");
 header("Expires: 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Pembuatan Laporan</title>
</head>
<style>

</style>
<body>
 
 <div>
  <table border="1" class="table">
  <thead>
	<tr>
		<td></td><td></td><td></td><td></td><td></td>
	</td>
    <tr style="background-color:#D4D8D9;color:black;text-align:center;">
		<td rowspan="3"><b>No </b></td><td rowspan="3"><b>NPWPD</b></td><td rowspan="3"><b>Jenis Pajak</b></td><td rowspan="3"><b>Wajib Pajak</b></td><td rowspan="3"><b>No. SKPD</b></td><td rowspan="3"><b>Tgl. Terbit</b></td><td rowspan="3"><b>Nominal Belum Terbayar</b></td><td colspan="4"><b>KATEGORI PIUTANG DAERAH</b></td><td rowspan="3"><b>TOTAL</b></td>
	</tr>
	<tr style="background-color:#D4D8D9;color:black;text-align:center;">
		<td><b>Lancar</b></td><td><b>Tidak Lancar</b></td><td><b>Diragukan</b></td><td><b>Macet</b></td>
	</tr>
	<tr style="background-color:#D4D8D9;color:black;text-align:center;">
		<td><b>0-1 Tahun</b></td><td><b>1-3 Tahun</b></td><td><b>3-5 Tahun</b></td><td><b>>5 Tahun</b></td>
	</tr>
   </thead>
   
   <?php 
   $i = 1;
   $jml_sa = 0;
   foreach($aging as $row){ 
		if($row->TIPE == 'SA'){
   ?>
		   <tr >
			<td><?=$i?></td>
			<td><?php echo $row->NPWPD?></td>
			<td><?php echo $row->NAMA_REKENING?></td>
			<td><?php echo $row->NAMA_WP?></td>
			<td>-</td>
			<td><?php echo $row->TGL_TERBIT?></td>
			<td><?php echo $row->TOTAL_YG_HARUS_BAYAR?></td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td><?php echo $row->TOTAL_YG_HARUS_BAYAR?></td>
		   </tr>
   <?php 
		$jml_sa += $row->TOTAL_YG_HARUS_BAYAR;
		$i++;
		}
   } 
   ?>
   
    <tr style="background-color:#99FEF1;color:black;text-align:center;">
			<td colspan="6"><b>Jumlah SA</b></td>
			<td><b><?php echo $jml_sa?></b></td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td><b><?php echo $jml_sa?></b></td>
	</tr>
   
   <?php 
   $i = 1;
   $jml_oa = 0;
   foreach($aging as $row){ 
		if($row->TIPE == 'OA'){
   ?>
		   <tr>
			<td><?=$i?></td>
			<td><?php echo $row->NPWPD?></td>
			<td><?php echo $row->NAMA_REKENING?></td>
			<td><?php echo $row->NAMA_WP?></td>
			<td>-</td>
			<td><?php echo $row->TGL_TERBIT?></td>
			<td><?php echo $row->TOTAL_YG_HARUS_BAYAR?></td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td><?php echo $row->TOTAL_YG_HARUS_BAYAR?></td>
		   </tr>
   <?php 
		$jml_oa += $row->TOTAL_YG_HARUS_BAYAR;
		$i++;
		}
   } 
   ?>
   
    <tr style="background-color:#99FEF1;color:black;text-align:center;">
			<td colspan="6"><b>Jumlah SA</b></td>
			<td><b><?php echo $jml_oa?></b></td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td><b><?php echo $jml_oa?></b></td>
	</tr>
   
  </table> 
 </div> 
</body>
</html>