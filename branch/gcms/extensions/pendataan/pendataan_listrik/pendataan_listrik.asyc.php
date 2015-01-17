<?php
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

ibase_trans();

if($_REQUEST['sender']=="entri_pendataan_listrik"){

	if($_REQUEST['action']=='edit'){
	
		if($_REQUEST['spt']==''){
			$exception = array('spt_id','pendataan_id','jenis_pendataan','nama_kegiatan');
			$other_request = array('pendataan_no'=>(int)$_REQUEST['nomor'],'id_rekening'=>$_REQUEST['rekening']);
		}else{
			$exception = array('pendataan_id','jenis_pendataan','nama_kegiatan');
			$other_request = array('pendataan_no'=>(int)$_REQUEST['nomor'],'spt_id'=>$_REQUEST['spt'],'id_rekening'=>$_REQUEST['rekening']);
		}	
			
		$str_where = ' where pendataan_id='.quote_smart($_REQUEST['idmasters']);
		ibase_trans();
		$a = $fbird->FBUpdate('pendataan_spt',$other_request,$exception,$str_where); unset($exception); unset($other_request);

		if($a){
	
			$exception = array('listrik_id','pendataan_id');
			$other_request = array('id_rekening'=>$_REQUEST['rekening']);
			
			$str_where = ' where pendataan_id='.quote_smart($_REQUEST['idmasters']);
			$b = $fbird->FBUpdate('pendataan_listrik',$other_request,$exception,$str_where); unset($exception); unset($other_request);

			if($b){
				ibase_commit();
				echo 'Data Telah Diupdate..';
			}else{
				ibase_rollback();
				echo 'Gagal Di Content';
			}
			
		}else{
			ibase_rollback();
			echo 'Gagal d Header ';
		}
	
	}else{
	
		$cek = b_fetch('select count(*) from pendataan_spt where pendataan_no='.quote_smart((int)$_REQUEST['nomor']));
		
		if(!$cek){
			$next_no = b_fetch('select max(pendataan_no) from pendataan_spt ');
			$next_no++;
			$no_spt=$next_no;
		}else{
			$message='Nomor '.$_REQUEST['nomor'].'Sudah Ada !'."\n".
					 'Diganti dengan '.sprintf('%05d',$next_no);
			$no_spt=(int)$_REQUEST['nomor'];
		}
		
		$ID_HEADER = $fbird->setGenerator('GEN_PENDATAAN_SPT');

		if($_REQUEST['spt']==''){
			$exception = array('spt_id','nama_kegiatan');
			$other_request = array('pendataan_id'=>$ID_HEADER,'pendataan_no'=>$no_spt,'jenis_pendataan'=>'LISTRIK');
		}else{
			$exception = array('nama_kegiatan');
			$other_request = array('pendataan_id'=>$ID_HEADER,'pendataan_no'=>$no_spt,'jenis_pendataan'=>'LISTRIK','spt_id'=>$_REQUEST['spt']);
		}
		
		ibase_trans();
		$a = $fbird->FBInsert('pendataan_spt',$other_request,$exception);unset($exception); unset($other_request);

		if($a){
			
			$ID_CONTENT = $fbird->setGenerator('GEN_PENDATAAN_LISTRIK');
			$exception = array();
			$other_request = array('listrik_id'=>$ID_CONTENT,'pendataan_id'=>$ID_HEADER,'id_rekening'=>$_REQUEST['rekening']);
			
			$b = $fbird->FBInsert('pendataan_listrik',$other_request,$exception);unset($exception); unset($other_request);

			if($b){
				ibase_commit();
				echo 'Data Telah Tersimpan';
			}else{
				ibase_rollback();
				echo 'Gagal Di Content';
			}
			
		}else{
			ibase_rollback();
			echo 'Gagal d Header ';
		}
	}
}
?>