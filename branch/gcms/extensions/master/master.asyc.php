<?
/*
Bagian Ini untuk memproses data

*/
if($_REQUEST['sender']=="entri_SatuanKerja"){
echo $_REQUEST['sender']."---".$_REQUEST['KodeUnitKerja'];
}

if($_REQUEST['sender']=="entri_Pemda"){
//echo $_REQUEST['sender']."xxx".$_REQUEST['kabKota'];
	$tabel="PEMDA";
	$tabel_id=$tabel."_ID";
	$max="select max(PEMDA_ID) AS NMAX
				   from PEMDA";
	$Nmax=gcms_query($max);
	
	$new = gcms_fetch_object($Nmax);
	//echo $new->Nmax;
	$new_id = number_format($new->NMAX)+1;
	$insert="insert into ".$tabel." values('".$new_id."','".$_REQUEST['namaKab']."',
											'".$_REQUEST['alamat']."','".$_REQUEST['logo']."',
											'".$_REQUEST['kodeLokasi']."','".$_REQUEST['kabKota']."',
											'".$_REQUEST['ibukotaKab']."','".$_REQUEST['telp']."',
											'".$_REQUEST['pejabat']."','".$_REQUEST['namaBank']."',
											'".$_REQUEST['noRek']."')";
	/*$insert	= "INSERT INTO ".$tabel." VALUES (";		
	$sql = "DESCRIBE ".$tabel."";
	$qry = gcms_query($sql);
	$max="select max(".$tabel_id.") AS Nmax
				   from ".$tabel."";
	$Nmax=gcms_query($max);
	$new_id = gcms_fetch_object($Nmax)->Nmax+1;
		while($data = gcms_fetch_array($qryDesc)) {
			if($data[0] == $tabel_id) $value =  $new_id ;
			elseif($data[0] == $tabel."_NAMA") $value = $_REQUEST['namaKab'] ;
			elseif($data[0] == $tabel."_LOKASI") $value = $_REQUEST['alamat'];
			elseif($data[0] == $tabel."_LOGO_PATH") $value = $_REQUEST['logo'];
			elseif($data[0] == $tabel."_KODE") $value = $_REQUEST['kodeLokasi'];
			elseif($data[0] == $tabel."_DATI") $value = $_REQUEST['kabKota'];
			elseif($data[0] == $tabel."_IBUKOTA") $value = $_REQUEST['ibukotaKab'];
			elseif($data[0] == $tabel."_TELP") $value = $_REQUEST['telp'] ;
			elseif($data[0] == $tabel."_PEJABAT") $value = $_REQUEST['pejabat'];
			elseif($data[0] == $tabel."_BANK") $value = $_REQUEST['namaBank'];
			elseif($data[0] == $tabel."_BANK_NOREK") $value = $_REQUEST[''];
			else $value = $_REQUEST[$data[0]];
			if ( $value != ''){
				$record = $value ;
			}else{
				$record = $data[4] ;
			}
			$insert .= quote_smart($record).",";
		}
		$insert .= ")";		
		$insert = str_replace(",)",")",$insert);*/
		gcms_query($insert);
		//echo $new_id."---".$insert."--".$max;
		echo "Data berhasil disimpan";
	
}

if($_REQUEST['sender']=="entri_kecamatan"){
$tabel = "kecamatan";
echo $_REQUEST['sender']."xxx".$_REQUEST['KodeKecamatan'];
	
}

if($_REQUEST['sender']=="entri_kelurahan"){
echo $_REQUEST['sender']."xxx".$_REQUEST['kecamatan'];
}

if($_REQUEST['sender']=="entri_anggaran"){
echo $_REQUEST['sender']."xxx";
}

if ($_REQUEST['sender']=="entri_keterangan_spt"){
echo $_REQUEST['sender']."xxx";
}

if ($_REQUEST['sender']=="entri_DataPrinter"){
echo $_REQUEST['sender']."xxx";
}
?>