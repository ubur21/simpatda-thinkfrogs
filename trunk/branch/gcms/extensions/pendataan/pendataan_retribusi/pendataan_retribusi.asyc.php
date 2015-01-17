<?php
session_start();
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

function searchRow($table, $idfield,$idvalue,$retribusi_id) {
	$sql='select count(*) as jumlah from '.$table.' where '.$idfield.' = '.$idvalue.' and retribusi_id = '.$retribusi_id;
	$result=ibase_query($sql);
	$row=ibase_fetch_row($result);
	if($row[0]) {
		return true;
	}
	else {
		return false;
	}
}
function deleteRow($table,$params) {
	$sql='delete from ';
	if(is_array($params)) {
		foreach($params as $fieldname => $id) {
			$sql.=' '.$table;
			$sql.=' where '.$fieldname.' = '.$id;
		}
	}
	//return $sql;
	$result=ibase_query($sql);
	if($result) {
		return true;
	}else return false;
}

if($_REQUEST['sender']=="entri_penetapan_PajakRetribusi"){
	if($_REQUEST['action']=='edit') { //edit
		if(isset($_SESSION['delete_box'])&&is_array($_SESSION['delete_box'])) { //delete process if box is not empty
			foreach($_SESSION['delete_box']['retribusi_id'] as $key => $id ) {
				$params=array('retribusi_id'=>$id);
				deleteRow('pendataan_retrubusi',$params);
			}
			//print_r($_SESSION['delete_box']);
			unset($_SESSION['delete_box']);
			
		}
		if(isset($_REQUEST['detail'])) { //data detail
			$id_rekening=getIdRekening($_REQUEST['rekening']);
			
			if($x=searchRow('pendataan_retrubusi','pendataan_id',$_REQUEST['FK'],$_REQUEST['id']) ) {
				//ada id row, update
				//echo $x;
				//$exception = array('nominal','pendataan_id','retribusi_id');
				$exception = array('pendataan_id','retribusi_id');
						
				$other_request = array( 'dasar_pengenaan'=>$_REQUEST['pengenaan']
							//,'persen_tarif'=>$_REQUEST['persen']
							,'id_rekening'=>$id_rekening
							,'dasar_tarif'=>$_REQUEST['tarif']
							//,'jumlah'=>$_REQUEST['jumlah']
							,'kode_rekening'=>$_REQUEST['rekening']
							,'nominal'=>$_REQUEST['retribusi']
							);
							
				ibase_trans();
				$str_where = " where retribusi_id =".$_REQUEST['id'];
				$b = $fbird->FBUpdate('pendataan_retrubusi',$other_request, $exception, $str_where);
				if($b) {
					echo $_REQUEST['FK'];
				}
				unset($exception);
				unset($other_request);
			}
			else {
				//echo "Tidak Ada Row";
				//tambah baru
				$ID_RETRIBUSI = $fbird->setGenerator('GEN_PENDATAAN_RETRIBUSI');
				$id_rekening=getIdRekening($_REQUEST['rekening']);
				//$exception = array('nominal');
				$exception = array();
				$other_request = array('retribusi_id'=>$ID_RETRIBUSI
						       ,'pendataan_id'=>$_REQUEST['FK']
						       ,'dasar_pengenaan'=>$_REQUEST['pengenaan']
						       //,'persen_tarif'=>$_REQUEST['persen']
						       ,'id_rekening'=>$id_rekening
						       ,'dasar_tarif'=>$_REQUEST['tarif']
						       //,'jumlah'=>$_REQUEST['jumlah']
						       ,'kode_rekening'=>$_REQUEST['rekening']
						       ,'nominal'=>$_REQUEST['retribusi']
						       );
				
				ibase_trans();
				$b = $fbird->FBInsert('pendataan_retrubusi',$other_request, $exception);
				if($b) {
					echo $_REQUEST['FK'];
				}
				unset($exception);
				unset($other_request);
			}
			
			//print_r($_POST);
			
		}
		else { //data umum
			//$exception = array('pendataan_id','pendaftaran_id','pendataan_no','memo','spt_id','nominal','nama_kegiatan');
			$exception = array('pendataan_id','pendaftaran_id','pendataan_no','memo','spt_id','nama_kegiatan');
			//$other_request = array();
			$other_request = array();
			ibase_trans();
			$str_where = " where pendataan_id =".$_REQUEST['idmasters'];
			$a = $fbird->FBUpdate('pendataan_spt',$other_request,$exception, $str_where);
			if($a) {
			    //echo 'success';
			    if($_REQUEST['idmasters']!='') echo $_REQUEST['idmasters'];
			}
			else {
				echo 'error';
			}
			unset($exception);
			unset($other_request);
		}
	}else { //tambah
		if(isset($_REQUEST['detail'])) { //data detail
			if($_REQUEST['rekening']=='') { //catch if empty
				//do nothing
			} else {
				$ID_RETRIBUSI = $fbird->setGenerator('GEN_PENDATAAN_RETRIBUSI');
				$id_rekening=getIdRekening($_REQUEST['rekening']);
				//$exception = array('nominal');
				$exception = array();
				$other_request = array('retribusi_id'=>$ID_RETRIBUSI
						       ,'pendataan_id'=>$_REQUEST['FK']
						       ,'dasar_pengenaan'=>$_REQUEST['pengenaan']
						       //,'persen_tarif'=>$_REQUEST['persen']
						       ,'id_rekening'=>$id_rekening
						       ,'dasar_tarif'=>$_REQUEST['tarif']
						       //,'jumlah'=>$_REQUEST['jumlah']
						       ,'kode_rekening'=>$_REQUEST['rekening']
						       ,'nominal'=>$_REQUEST['retribusi']
						       );
				
				ibase_trans();
				$b = $fbird->FBInsert('pendataan_retrubusi',$other_request, $exception);
				unset($exception);
				unset($other_request);
			}
		}
		else { //data umum
			$ID=$fbird->setGenerator('GEN_PENDATAAN_SPT');
			//$exception = array('spt_id','memo','nominal','nama_kegiatan');
			$exception = array('spt_id','memo','nama_kegiatan');
			$other_request = array(
				'pendataan_no'	=>$_REQUEST['nomor'],
				'pendataan_id'	=> $ID,
				);
			ibase_trans();
			$a = $fbird->FBInsert('pendataan_spt',$other_request,$exception);
			if($a) {
				echo $ID;
			}
			else {
				echo "error tambah umum";
			}
			unset($exception);
			unset($other_request);
		}
	}
}
//if($_REQUEST['sender']=="entri_penetapan_PajakRetribusi"){
//	//pendataan_spt
//	if( $_REQUEST['jenis_pungutan'] == 'Pilih' ) $_REQUEST['jenis_pungutan'] == 'OFFICIAL';
//	
//	//if( $_REQUEST['statusForm'] == 'edit') {
//	//	$exception = array('spt_id','memo','pendataan_id','pendaftaran_id','pendataan_no');
//	//	$other_request = array(
//	//			
//	//			);
//	//	ibase_trans();
//	//	$str_where = " where pendataan_id =".$_REQUEST['pendataanid'];
//	//	$a = $fbird->FBUpdate('pendataan_spt',$other_request,$exception, $str_where);	
//	//} else {
//	//	$ID=$fbird->setGenerator('GEN_PENDATAAN_SPT');
//	//	$exception = array('spt_id','memo');
//	//	$other_request = array(
//	//			'pendataan_no'	=>$_REQUEST['nomor'],
//	//			'pendataan_id'	=> $ID
//	//			);
//	//	ibase_trans();
//	//	$a = $fbird->FBInsert('pendataan_spt',$other_request,$exception);	
//	//}
//	//
//	//	
//	//unset($other_request);
//	//unset($exception);
//	//
//	//pendataan_retribusi
//	if($_REQUEST['statusForm'] == 'edit') {
//		echo "edit\n";
//		
//		if( isset($_REQUEST['ptarif']) && is_array($_REQUEST['ptarif']) ) {
//			foreach( $_REQUEST['ptarif'] as $key => $val ) {
//				if( isset($_REQUEST['retribusiid'][$key]) ) {
//					echo "update\n";
//					$exception = array('nominal','pendataan_id','retribusi_id');
//						
//					$other_request = array( 'dasar_pengenaan'=>$_REQUEST['dtarif'][$key]
//								,'persen_tarif'=>$_REQUEST['ptarif'][$key]
//								,'dasar_tarif'=>$_REQUEST['tdasar'][$key]
//								,'jumlah'=>$_REQUEST['jumlahx'][$key]
//								,'id_rekening'=>$_REQUEST['kode_rekening'][$key]
//								);
//					
//					ibase_trans();
//					$str_where = " where retribusi_id =".$_REQUEST['retribusiid'][$key];
//					$b = $fbird->FBUpdate('pendataan_retribusi',$other_request, $exception, $str_where);	
//				}
//				else {
//					echo "tambah\n";
//					$ID_RETRIBUSI= $fbird->setGenerator('GEN_PENDATAAN_RETRIBUSI');
//			
//					$exception = array('nominal');
//					
//					$other_request = array('retribusi_id'=>$ID_RETRIBUSI
//							       ,'pendataan_id'=>$_REQUEST['pendataanid']
//							       ,'dasar_pengenaan'=>$_REQUEST['dtarif'][$key]
//							       ,'persen_tarif'=>$_REQUEST['ptarif'][$key]
//							       ,'dasar_tarif'=>$_REQUEST['tdasar'][$key]
//							       ,'jumlah'=>$_REQUEST['jumlahx'][$key]
//							       ,'id_rekening'=>$_REQUEST['kode_rekening'][$key]
//							       );
//				
//					ibase_trans();
//					$b = $fbird->FBInsert('pendataan_retribusi',$other_request, $exception);
//				}
//			}
//			
//			if( isset($_REQUEST['retribusiid']) && is_array($_REQUEST['retribusiid']) ) {
//				foreach( $_REQUEST['retribusiid'] as $key => $val) {
//					if( !$_REQUEST['ptarif'][$key] ) {
//						$del = "delete from pendataan_retribusi p where p.retribusi_id =".$val;
//						$result = gcms_query($del);
//						if( !$result ) { echo "detail ".$val." gagal dihapus"; }
//						else { echo "delete"; }
//					}
//				}
//			}
//		}
//		else {
//			if(isset($_REQUEST['retribusiid'])) {
//				foreach($_REQUEST['retribusiid'] as $key => $val) {
//					$del = "delete from pendataan_retribusi p where p.retribusi_id =".$val;
//					$result = gcms_query($del);
//					if( !$result ) { echo "detail ".$val." gagal dihapus"; }
//					else { echo "delete"; }
//				}
//			}
//		}
//	}
//	else {
//		if( isset($_REQUEST['ptarif']) && is_array($_REQUEST['ptarif']) ) {
//			echo "new\n";
//			foreach( $_REQUEST['ptarif'] as $key => $val ) {
//				$ID_RETRIBUSI = $fbird->setGenerator('GEN_PENDATAAN_RETRIBUSI');
//			
//				$exception = array('nominal');
//				
//				$other_request = array( 'retribusi_id'=>$ID_RETRIBUSI
//						       ,'pendataan_id'=>$ID
//						       ,'dasar_pengenaan'=>$_REQUEST['dtarif'][$key]
//						       ,'persen_tarif'=>$_REQUEST['ptarif'][$key]
//						       ,'dasar_tarif'=>$_REQUEST['tdasar'][$key]
//						       ,'jumlah'=>$_REQUEST['jumlahx'][$key]
//						       ,'id_rekening'=>$_REQUEST['kode_rekening'][$key]
//						       );
//			
//				ibase_trans();
//				$b = $fbird->FBInsert('pendataan_retribusi',$other_request, $exception);
//			}
//		}
//	}
//	
//	if($a){
//		ibase_commit();
//		echo 'Data Umum Telah Tersimpan ';
//		if( $b ) {
//			echo "\n Data Detail Telah Tersimpan";
//		}
//		else {
//			echo "\n Data Detail Tidak Tersimpan";
//		}
//	}else{
//		ibase_rollback();
//		echo 'Error !';
//	}
//	
//}

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