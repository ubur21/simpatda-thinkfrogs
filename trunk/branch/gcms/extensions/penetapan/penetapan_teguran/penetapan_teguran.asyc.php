<?php
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='entri_TeguranWpWr'){

	if($_REQUEST['action']=='edit'){
	
		if(isset($_REQUEST['detail'])){
		
		
		}else{
			$exception = array('teguran_pr_id','teguran_pr_no');
			$other_request = array('fk_teguran_id'=>$_REQUEST['petugas_id'],
									'tingkat'=>1);
			$str_where = ' where teguran_pr_id='.quote_smart($_REQUEST['IdTeguranHid']);
			$a = $fbird->FBUpdate('teguran_pr',$other_request,$exception,$str_where);unset($exception); unset($other_request);	
			if($a) {
				echo "Data Berhasil di Ubah";	
				ibase_commit();
			}else{echo '!';ibase_rollback();}
		}
	
	}else{
		
		$exception = array('teguran_pr_id');
		$other_request = array('fk_teguran_id'=>$_REQUEST['petugas_id'],
								'teguran_pr_no'=>$_REQUEST['NoHid'],
								'tingkat'=>1);
		$a = $fbird->FBInsert('teguran_pr',$other_request,$exception);unset($exception); unset($other_request);
			 
		if($a ){
			
			$update = "update penetapan_pr set nominal_penetapan = 0 where penetapan_pr_id ='".$_REQUEST['penetapan_pr_id']."'";
			if(gcms_query($update)){
			echo "Data Berhasil Disimpan";
			ibase_commit();
			}else{
				echo $update;
			}
		}
		if(!$a){
			echo "!";
			ibase_rollback();
		}
	
	}

}


?>