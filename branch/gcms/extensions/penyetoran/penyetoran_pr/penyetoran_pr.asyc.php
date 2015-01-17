<?php
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='entri_penyetoran_pr'){

	if($_REQUEST['action']=='edit'){
	
		if(isset($_REQUEST['detail'])){
		
		
		}else{
		
		
		}
	
	}else{
	
		if(isset($_REQUEST['detail'])){
			//echo 'insert detail'."\n";
			//print_r($_REQUEST)."\n";
						
			
			$exception = array('sts_content_id');
			$other_request = array(
								'sts_id'=>$_REQUEST['FK'],
								'id_rekening'=>$_REQUEST['id']
							);			
			$a = $fbird->FBInsert('sts_content',$other_request,$exception);
			unset($exception); unset($other_request);
			
			if($a && $_REQUEST['rows']==$_REQUEST['count']){			
				ibase_commit();
			}
			if(!$a){
				ibase_rollback();
			}
		
		}else{
			
			//echo 'insert header'."\n";
			//print_r($_REQUEST);
					
			list($day,$month,$year) = explode('/',$_REQUEST['tgl_setor']);
			
			$ID_HEADER = $fbird->setGenerator('GEN_STS');
			$exception = array();
			$sts_no = getNoPenyetoran();
			$other_request = array(
								'sts_id'=>$ID_HEADER,
								'sts_no'=>(int)$sts_no,
								'sts_thn'=>$year,
								'sts_tgl'=>$_REQUEST['tgl_setor'],
								'nominal'=>$_REQUEST['nominal_pajak']								
							);							
			ibase_trans();
			$a = $fbird->FBInsert('sts',$other_request,$exception);
			unset($exception); unset($other_request);
			
			if($a){
				updateNoPenyetoran($_REQUEST['tgl_setor']);
				echo $ID_HEADER;
			}else{
				echo '!';
			}
		}
	}

}


?>