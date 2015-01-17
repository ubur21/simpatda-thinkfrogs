<?php
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";
//print_r($_REQUEST);

if($_REQUEST['sender']=="entri_reklame"){

	if($_REQUEST['action']=='edit'){
		
		if(isset($_REQUEST['detail'])){
			
			//echo 'update detail';
			
			// - minus delete grid;
			
			$id_rekening=getIdRekening($_REQUEST['rekening']);
			
			$exception = array('air_id','pendataan_id');
			
			$other_request = array(
								'id_rekening'=>$id_rekening,
								'dasar_tarif'=>$_REQUEST['tarif'],
								'dasar_pengenaan'=>$_REQUEST['pengenaan'],
								'persen_tarif'=>$_REQUEST['persen'],
								'nominal'=>$_REQUEST['pajak']
							);			
			
			$str_where = ' where air_id='.quote_smart($_REQUEST['id']);
			//ibase_trans();
			$a = $fbird->FBUpdate('pendataan_air',$other_request,$exception,$str_where); unset($exception); unset($other_request);
						
			if($a && $_REQUEST['rows']==$_REQUEST['count']){		
				ibase_commit();
			}
			if(!$a){
				ibase_rollback();
			}
			

		}else{
			// nilai nominal belum bisa diambil dari grid
		
			if($_REQUEST['spt']==''){
				$exception = array('spt_id','pendataan_id','jenis_pendataan','nama_kegiatan');
				$other_request = array('pendataan_no'=>(int)$_REQUEST['nomor_reg']);
			}else{
				$exception = array('pendataan_id','jenis_pendataan','nama_kegiatan');
				$other_request = array('pendataan_no'=>(int)$_REQUEST['nomor_reg'],'spt_id'=>$_REQUEST['spt']);
			}	
				
			$str_where = ' where pendataan_id='.quote_smart($_REQUEST['idmasters']);
			ibase_trans();
			
			$a = $fbird->FBUpdate('pendataan_spt',$other_request,$exception,$str_where); unset($exception); unset($other_request);
			if($a) echo 'OK Header';
			if(!$a) echo '!';
			//echo 'update header';
			
		}
		
	
	}else{
	
		if(isset($_REQUEST['detail'])){
									
			$id_rekening=getIdRekening($_REQUEST['rekening']);
			
			$exception = array('air_id');
			$other_request = array(
								'pendataan_id'=>$_REQUEST['FK'],
								'id_rekening'=>$id_rekening,
								'dasar_tarif'=>$_REQUEST['tarif'],
								'dasar_pengenaan'=>$_REQUEST['pengenaan'],
								'persen_tarif'=>$_REQUEST['persen'],
								'nominal'=>$_REQUEST['pajak']
							);
			
			$a = $fbird->FBInsert('pendataan_air',$other_request,$exception);unset($exception); unset($other_request);
			
			if($a && $_REQUEST['rows']==$_REQUEST['count']){			
				echo 'selesai commit';
				ibase_commit();
			}
			if(!$a){
				ibase_rollback();
			}
			//echo 'insert detail';
			
		}else{

			$cek = b_fetch('select count(*) from pendataan_spt where pendataan_no='.quote_smart((int)$_REQUEST['nomor_reg']));
		
			if(!$cek){
				$next_no = b_fetch('select max(pendataan_no) from pendataan_spt ');
				$next_no++;
				$no_spt=$next_no;
			}else{
				$message='Nomor '.$_REQUEST['nomor_reg'].'Sudah Ada !'."\n".
						 'Diganti dengan '.sprintf('%05d',$next_no);
				$no_spt=(int)$_REQUEST['nomor_reg'];
			}
		
			$ID_HEADER = $fbird->setGenerator('GEN_PENDATAAN_SPT');

			if($_REQUEST['spt']==''){
				$exception = array('spt_id','nama_kegiatan');
				$other_request = array('pendataan_id'=>$ID_HEADER,'pendataan_no'=>$no_spt,'jenis_pendataan'=>'AIR');
			}else{
				$exception = array('nama_kegiatan');
				$other_request = array('pendataan_id'=>$ID_HEADER,'pendataan_no'=>$no_spt,'jenis_pendataan'=>'AIR','spt_id'=>$_REQUEST['spt']);
			}
		
			ibase_trans();

			$a = $fbird->FBInsert('pendataan_spt',$other_request,$exception); 
			unset($exception); unset($other_request);
			
			if($a){
				echo $ID_HEADER;
			}else{
				echo '!';
			}
			//echo 'insert header';
		}
	}
}

?>