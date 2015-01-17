<?php
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='entri_spt'){

	if($_REQUEST['action']=='edit'){
	
		$exception = array('spt_id','penerima_nama','penerima_alamat','spt_no');
		//$other_request = array('spt_no'=>$no_spt);
		$other_request = array();
		$str_where = ' where spt_id='.quote_smart($_REQUEST['idmasters']);
		$a = $fbird->FBUpdate('spt',$other_request,$exception,$str_where);

		if($a){
			echo 'Data Telah diupdate';
		}else{
			echo 'Error';
		}
	
	
	}else{

		$cek = b_fetch('select count(*) from spt where spt_no='.quote_smart((int)$_REQUEST['nomor']));
		
		if(!$cek){
			$next_no = b_fetch('select max(spt_no) from spt ');
			$next_no++;
			$no_spt=$next_no;
		}else{
			$message='Nomor '.$_REQUEST['nomor'].'Sudah Ada !'."\n".
					 'Diganti dengan '.sprintf('%05d',$next_no);
			$no_spt=(int)$_REQUEST['nomor'];
		}

		$exception = array('spt_id','penerima_nama','penerima_alamat');
		$other_request = array('spt_no'=>$no_spt);
		$a = $fbird->FBInsert('spt',$other_request,$exception);

		if($a){
			echo 'Data telah tersimpan';
		}else{
			echo 'Error';
		}
		
	}
}

?>