<?php
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='entri_penerimaan_pr'){

	//print_r($_REQUEST);
	
	if($_REQUEST['action']=='edit'){
	
	
	
	}else{

		list($day,$month,$year) = explode('/',$_REQUEST['tgl_penerimaan']);

		$ID_HEADER = $fbird->setGenerator('GEN_PENERIMAAN_PR');
		$no_bayar = getNoPenerimaan($_REQUEST['jenis_pungutan'],$_REQUEST['tgl_penerimaan']);
		$exception = array();
		$other_request = array(
							'penerimaan_pr_id'=>$ID_HEADER,
							'penetapan_pr_id'=>$_REQUEST['id_kohir'],
							'pendataan_id'=>$_REQUEST['id_spt'],
							'pendaftaran_id'=>$_REQUEST['id_npwp'],
							'pemohon_id'=>$_REQUEST['id_pemohon'],
							'skpd_id'=>$_REQUEST['id_dinas'],
							'penerimaan_pr_no'=>$no_bayar,
							'thn_penerimaan'=>$year,
							'id_ref_pembayaran'=>$_REQUEST['cpenyetoran'],
						);
		ibase_trans();				
		$a = $fbird->FBInsert('penerimaan_pr',$other_request,$exception);
		unset($exception); unset($other_request);
		$err=0;
		if($a){
		
			$qy = 'select id_rekening, nominal from v_pendataan_content where pendataan_id='.quote_smart($_REQUEST['id_spt']);
			
			$result = gcms_query($qy);
			while($rs = gcms_fetch_object($result)){
				$exception = array('penerimaan_pr_content_id');
				$other_request = array(
									'penerimaan_pr_id'=>$ID_HEADER,
									'id_rekening'=>$rs->id_rekening,
									'nominal'=>$rs->nominal
								);
	
				$b = $fbird->FBInsert('penerimaan_pr_content',$other_request,$exception);
				
				if(!$b) $err=1;
				
			}
			if($b){
				//updateNoPenerimaan('OFFICE',$_REQUEST['thn_spt']);
				updateNoPenerimaan($_REQUEST['jenis_pungutan'],$_REQUEST['tgl_penerimaan']);
				echo 'Data telah tersimpan...';
			}else{
				echo '!C';
			}
		}else{
			echo '!H';
		}
	}
}
elseif($_REQUEST['sender']=='entri_penetapan_pr'){

	if($_REQUEST['action']=='edit'){
	
		if(isset($_REQUEST['detail'])){
		
		
		}else{
		
		
		}
	
	}else{
	
		if(isset($_REQUEST['detail'])){
			//echo 'insert detail'."\n";
			//print_r($_REQUEST)."\n";
			
			if($_REQUEST['cnomor']==''){ // tdk duplikasi, contreng
				//echo 'Tidak duplikasi, conterng'."\n";
				list($day,$month,$year) = explode('/',$_REQUEST['tgl_penerimaan']);
				//print_r($_REQUEST);
				$ID_HEADER = $fbird->setGenerator('GEN_PENERIMAAN_PR');
				$no_kohir = getNoKohir();
				$exception = array();
				$other_request = array(
									'penerimaan_pr_id'=>$ID_HEADER,
									'no_penerimaan'=>$no_kohir,
									'thn_penerimaan'=>$year,
									'nominal_penerimaan'=>$_REQUEST['nominal_pajak']
								);
								
				updateNoKohir($_REQUEST['tgl_penerimaan']);
				$a = $fbird->FBInsert('penerimaan_pr',$other_request,$exception);
				unset($exception); unset($other_request);
				//echo 'insert header x';
			}else{
				//echo 'FK 1'.$_REQUEST['FK']."\n";
			}
			
			if($_REQUEST['cnomor']!='') $ID_HEADER = $_REQUEST['FK'];
			
			//echo print_r($_REQUEST);
			
			unset($exception); unset($other_request);
			
			$exception = array('penerimaan_pr_content_id');
			$other_request = array(
								'penerimaan_pr_id'=>$ID_HEADER,
								'pendataan_id'=>$_REQUEST['id'],
							);			
			$a = $fbird->FBInsert('penerimaan_pr_content',$other_request,$exception);
			
			if($a && $_REQUEST['rows']==$_REQUEST['count']){			
				ibase_commit();
			}
			if(!$a){
				ibase_rollback();
			}
			unset($exception); unset($other_request);
			
			$jenis_pendataan = b_fetch('select jenis_pendataan from pendataan_spt where pendataan_id='.quote_smart($_REQUEST['id']));
			//echo $jenis_pendataan;
			/*switch $jenis_pendataan {
				case 'LISTRIK': 
					$table_name='pendataan_listrik';
				break;
				case 'GALIAN C': 
					$table_name='pendataan_galianc';
				break;
				case 'AIR':
					$table_name='pendataan_air';
				break;
			}*/
			
		
			/*$qy = 'select * from '.$table_name;
			$result = gcms_query($qy);
			while($rs = gcms_fetch_object($result)){
				$exception = array('penerimaan_pr_content_id','tgl_jatuh_tempo');
				$other_request = array(
									'penerimaan_pr_id'=>$_REQUEST['FK'],
									'pendataan_id'=>$_REQUEST['id'],
								);			
				$a = $fbird->FBInsert('penerimaan_pr_content',$other_request,$exception);
				unset($exception); unset($other_request);
			}*/
		
		
		}else{
			
			//echo 'insert header'."\n";
			//print_r($_REQUEST);

			$ID_HEADER=0;
			$qy_cek ='select count(*) 
						from v_penerimaan_pendataan a
						left join penerimaan_pr_content b on b.pendataan_id=a.pendataan_id
						where b.pendataan_id is null and a.pendataan_no between '.
						quote_smart($_REQUEST['no_data_awal']).' and '.
						quote_smart($_REQUEST['no_data_akhir']).' ';
						
			if($_REQUEST['cnomor']!=''){ // duplikasi
			
				//echo 'Duplikasi '."\n";
				
				list($day,$month,$year) = explode('/',$_REQUEST['tgl_penerimaan']);
				
				$ID_HEADER = $fbird->setGenerator('GEN_PENERIMAAN_PR');
				$exception = array();
				$no_kohir = getNoKohir();
				$other_request = array(
									'penerimaan_pr_id'=>$ID_HEADER,
									'no_penerimaan'=>(int)$no_kohir,
									'thn_penerimaan'=>$year,
									'nominal_penerimaan'=>$_REQUEST['nominal_pajak']
								);
				updateNoKohir($_REQUEST['tgl_penerimaan']);
				ibase_trans();
				$a = $fbird->FBInsert('penerimaan_pr',$other_request,$exception);
					
			}else{
				ibase_trans();
				$a=1;
			}
			$cdetail=1;

			if($_REQUEST['cinput']!=''){ // range no. data
			
				$qy = 'select a.* 
						from v_penerimaan_pendataan a
						left join penerimaan_pr_content b on b.pendataan_id=a.pendataan_id
						where b.pendataan_id is null and a.pendataan_no between '.
						quote_smart($_REQUEST['no_data_awal']).' and '.
						quote_smart($_REQUEST['no_data_akhir']).' order by a.pendataan_no ';
						
				$result = gcms_query($qy);
				while($rs = gcms_fetch_object($result)){
				
					if($_REQUEST['cnomor']==''){ // tdk duplikasi
					
						//echo 'Tdk Duplikasi, Range'."\n";
						list($day,$month,$year) = explode('/',$_REQUEST['tgl_penerimaan']);
					
						$ID_HEADER = $fbird->setGenerator('GEN_PENERIMAAN_PR');
						$no_kohir = getNoKohir();
						$exception = array();
						$other_request = array(
											'penerimaan_pr_id'=>$ID_HEADER,
											'no_penerimaan'=>$no_kohir,
											'thn_penerimaan'=>$year,
											'nominal_penerimaan'=>$_REQUEST['nominal_pajak']
										);
						updateNoKohir($_REQUEST['tgl_penerimaan']);
						$a = $fbird->FBInsert('penerimaan_pr',$other_request,$exception);
						unset($exception); unset($other_request);
					}
					
					$exception = array('penerimaan_pr_content_id');
					$other_request = array(
										'penerimaan_pr_id'=>$ID_HEADER,
										'pendataan_id'=>$rs->pendataan_id,
										'nominal'=>$rs->nominal,
										'jenis_pendataan'=>$rs->jenis_pendataan,
										'jenis_pungutan'=>$rs->jenis_pungutan
									);
									
					$b = $fbird->FBInsert('penerimaan_pr_content',$other_request,$exception);
					if(!$b) $cdetail=0;

				}
			}
			
			if($a && ($_REQUEST['cinput']!='' && $cdetail)){
				ibase_commit();
			}elseif($a){
				echo $ID_HEADER;
			}else{
				echo '!';
			}
		}
	}

}


?>