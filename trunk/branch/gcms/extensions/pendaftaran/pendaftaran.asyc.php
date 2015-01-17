<?php
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";


if($_REQUEST['sender']=="entri_pendaftaran_pribadi"){

	if($_REQUEST['action']=='edit'){
	
		$nmax = substr($_REQUEST['nomor'],1,strlen($_REQUEST['nomor'])-1);
	
		$npwp = setNoNPWP($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['pemohon']);
		$nokartu = setNoKartu($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['tanggal_kartu']);
			
		$exception = array('pendaftaran_id','nurut','status','no_pendaftaran');
		$other_request = array(
							'no_kartu'=>$nokartu,
							'npwp'=>$npwp,
							'id_pemohon'=>$_REQUEST['pemohon'],
						);
		$str_where = ' where pendaftaran_id='.quote_smart($_REQUEST['idmasters']);
		
		ibase_trans();
		$a = $fbird->FBUpdate('pendaftaran',$other_request,$exception,$str_where);
		
		if($a){
			echo 'Data Telah diupdate';
			ibase_commit();
		}else{
			echo 'Error';
			ibase_rollback();
		}
		
	
	}else{
		
		$cek = b_fetch('select count(*) from pendaftaran where no_pendaftaran='.quote_smart($_REQUEST['nomor']));
		
		if(!$cek){
			$nomor = $_REQUEST['nomor'];
			$nmax = (int)substr($_REQUEST['nomor'],1);
		}else{
			
			$nomor = setNomorPendaftaran($_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd']);

			$message = $_REQUEST['nomor'].' sudah terdaftar, '."\n".
					   'diganti dengan '.$nomor."\n";
			
			$nmax = b_fetch('select max(nurut) from pendaftaran where jenis_pendaftaran='.quote_smart($_REQUEST['jenis_pendaftaran']).' and objek_pdrd='.quote_smart($_REQUEST['objek_pdrd']));
			//$nmax = b_fetch('select max(nurut) from pendaftaran where jenis_pendaftaran='.quote_smart($_REQUEST['jenis_pendaftaran']).' ');
			$nmax++;
			
		}
		
		$npwp = setNoNPWP($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['pemohon']);
		$nokartu = setNoKartu($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['tanggal_kartu']);
		
		$ID = $fbird->setGenerator('GEN_PENDAFTARAN');
		
		$exception = array('status');
		
		$other_request = array(
							'pendaftaran_id'=>$ID,
							'id_pemohon'=>$_REQUEST['pemohon'],
							'nurut'=>$nmax,
							'no_pendaftaran'=>$nomor,
							'no_kartu'=>$nokartu,
							'npwp'=>$npwp
						);
		ibase_trans();
		$a = $fbird->FBInsert('pendaftaran',$other_request,$exception);
		
		if($a){
			ibase_commit();
			echo 'Data Telah Tersimpan ';
		}else{
			ibase_rollback();
			echo 'Error !';
		}
	}
	
}

if($_REQUEST['sender']=="entri_pendaftaran_bu"){

	if($_REQUEST['action']=='edit'){
	
		$nmax = substr($_REQUEST['nomor'],1,strlen($_REQUEST['nomor'])-1);
	
		$npwp = setNoNPWP($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['pemohon']);
		$nokartu = setNoKartu($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['tanggal_kartu']);
			
		$exception = array('pendaftaran_id','nurut','status','no_pendaftaran');
		$other_request = array(
							'no_kartu'=>$nokartu,
							'npwp'=>$npwp,
							'id_pemohon'=>$_REQUEST['pemohon'],
						);
		$str_where = ' where pendaftaran_id='.quote_smart($_REQUEST['idmasters']);
		
		ibase_trans();
		$a = $fbird->FBUpdate('pendaftaran',$other_request,$exception,$str_where);
		
		if($a){
			echo 'Data Telah diupdate';
			ibase_commit();
		}else{
			echo 'Error';
			ibase_rollback();
		}
	
	}else{

		$cek = b_fetch('select count(*) from pendaftaran where no_pendaftaran='.quote_smart($_REQUEST['nomor']));
		
		if(!$cek){
			$nomor = $_REQUEST['nomor'];
			$nmax = (int)substr($_REQUEST['nomor'],1);
		}else{
			
			$nomor = setNomorPendaftaran($_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd']);

			$message = $_REQUEST['nomor'].' sudah terdaftar, '."\n".
					   'diganti dengan '.$nomor."\n";
			
			$nmax = b_fetch('select max(nurut) from pendaftaran where jenis_pendaftaran='.quote_smart($_REQUEST['jenis_pendaftaran']).' and objek_pdrd='.quote_smart($_REQUEST['objek_pdrd']));
			//$nmax = b_fetch('select max(nurut) from pendaftaran where jenis_pendaftaran='.quote_smart($_REQUEST['jenis_pendaftaran']).' ');
			$nmax++;
			
		}
		
		$ID = $fbird->setGenerator('GEN_PENDAFTARAN');
		$npwp = setNoNPWP($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['pemohon']);
		$nokartu = setNoKartu($nmax,$_REQUEST['jenis_pendaftaran'],$_REQUEST['objek_pdrd'],$_REQUEST['tanggal_kartu']);
		
		$exception = array('status');
		
		$other_request = array(
							'pendaftaran_id'=>$ID,
							'id_pemohon'=>$_REQUEST['pemohon'],
							'nurut'=>$nmax,
							'no_pendaftaran'=>$nomor,
							'no_kartu'=>$nokartu,
							'npwp'=>$npwp
						);
						
		ibase_trans();
		$a = $fbird->FBInsert('pendaftaran',$other_request,$exception);
		
		if($a){
			ibase_commit();
			echo 'Data Telah Tersimpan ';
		}else{
			ibase_rollback();
			echo 'Error !';
		}

	}
}

?>