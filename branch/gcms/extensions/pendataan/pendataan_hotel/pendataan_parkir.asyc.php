<?php
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";


if($_REQUEST['sender']=="entri_pendaftaran_pribadi"){

	$cek = b_fetch('select count(*) from pendaftaran where no_pendaftaran='.quote_smart($_REQUEST['no_pendaftaran']));
	
	if(!$cek){
		$nomor = $_REQUEST['no_pendaftaran'];
		$nmax = (int)substr($_REQUEST['no_pendaftaran'],1);
		
	}else{
		
		$nomor = setNomorPendaftaran($_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd']);

		$message = $_REQUEST['no_pendaftaran'].' sudah terdaftar, '."\n".
				   'diganti dengan '.$nomor."\n";
		
		$nmax = b_fetch('select max(nurut) from pendaftaran where jenis_pendaftaran='.quote_smart($_REQUEST['jenis_pendaftaran']).' and objek_pdrd='.quote_smart($_REQUEST['objek_pdrd']));
		//$nmax = b_fetch('select max(nurut) from pendaftaran where jenis_pendaftaran='.quote_smart($_REQUEST['jenis_pendaftaran']).' ');
		$nmax++;
		
	}
	
	$npwp = setNoNPWP($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['pemohon']);
	
	$nokartu = setNoKartu($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['tanggal_kartu']);
		
	$arr_field = array(
				'id_pemohon',
				'jenis_pendaftaran',
				'nurut',
				'no_pendaftaran',
				'no_kartu',
				'npwp',
				'objek_pdrd',
				'kode_usaha',
				'tanggal_kartu',
				'tanggal_terima',
				'tanggal_kembali',
				'tanggal_kirim',
				'tanggal_tutup',
			);
			
	$field = implode(',',$arr_field);
	
	$qy = 'insert into pendaftaran ('.$field.') values (';
	$qy.= quote_smart($_REQUEST['pemohon']).','.
		  quote_smart($_REQUEST['jenis_pendaftaran']).",'$nmax'".','.
		  quote_smart($nomor).','.quote_smart($nokartu).','.quote_smart($npwp).','.
		  quote_smart($_REQUEST['objek_pdrd']).','.quote_smart($_REQUEST['kode_usaha']).','.
		  quote_smart($_REQUEST['tanggal_kartu']).','.quote_smart(blkDate($_REQUEST['tanggal_terima'])).','.
		  quote_smart(blkDate($_REQUEST['tanggal_kembali'])).','.quote_smart(blkDate($_REQUEST['tanggal_kirim'])).','.
		  quote_smart(blkDate($_REQUEST['tanggal_tutup'])).')';
	
	
	if(gcms_query($qy)){
	//if(true){
		echo $message.'Data telah tersimpan ';
	}else{
		echo '!';
	}

}

if($_REQUEST['sender']=="entri_pendaftaran_bu"){

	$cek = b_fetch('select count(*) from pendaftaran where no_pendaftaran='.quote_smart($_REQUEST['no_pendaftaran']));
	
	if(!$cek){
		$nomor = $_REQUEST['no_pendaftaran'];
		$nmax = (int)substr($_REQUEST['no_pendaftaran'],1);
		
	}else{
		
		
		$nomor = setNomorPendaftaran($_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd']);
		
		$message = $_REQUEST['no_pendaftaran'].' sudah terdaftar, '."\n".
				   'diganti dengan '.$nomor."\n";
		
		$nmax = b_fetch('select max(nurut) from pendaftaran where jenis_pendaftaran='.quote_smart($_REQUEST['jenis_pendaftaran']).' and objek_pdrd='.quote_smart($_REQUEST['objek_pdrd']));
		//$nmax = b_fetch('select max(nurut) from pendaftaran where jenis_pendaftaran='.quote_smart($_REQUEST['jenis_pendaftaran']).' ');
		$nmax++;
		
	}
	
	$npwp = setNoNPWP($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['pemohon']);
	
	$nokartu = setNoKartu($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['tanggal_kartu']);
		
	$arr_field = array(
				'id_pemohon',
				'jenis_pendaftaran',
				'nurut',
				'no_pendaftaran',
				'no_kartu',
				'npwp',
				'objek_pdrd',
				'kode_usaha',
				'tanggal_kartu',
				'tanggal_terima',
				'tanggal_kembali',
				'tanggal_kirim',
				'tanggal_tutup',
			);
			
	$field = implode(',',$arr_field);
	
	$qy = 'insert into pendaftaran ('.$field.') values (';
	$qy.= quote_smart($_REQUEST['pemohon']).','.
		  quote_smart($_REQUEST['jenis_pendaftaran']).",'$nmax'".','.
		  quote_smart($nomor).','.quote_smart($nokartu).','.quote_smart($npwp).','.
		  quote_smart($_REQUEST['objek_pdrd']).','.quote_smart($_REQUEST['kode_usaha']).','.
		  quote_smart($_REQUEST['tanggal_kartu']).','.quote_smart(blkDate($_REQUEST['tanggal_terima'])).','.
		  quote_smart(blkDate($_REQUEST['tanggal_kembali'])).','.quote_smart(blkDate($_REQUEST['tanggal_kirim'])).','.
		  quote_smart(blkDate($_REQUEST['tanggal_tutup'])).')';
	
	
	if(gcms_query($qy)){
		echo $message.'Data telah tersimpan ';
	}else{
		echo '!';
	}

}

?>