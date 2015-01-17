<?php
session_start();
include('./config.php');
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

function searchRow($table, $idfield,$idvalue,$pwalet_id) {
	$sql='select count(*) as jumlah from '.$table.' where '.$idfield.' = '.$idvalue.' and pwalet_id = '.$pwalet_id;
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

if($_REQUEST['sender']=="entri_penetapan_PajakWalet"){
	if($_REQUEST['action']=='edit') { //edit
		if(isset($_SESSION['delete_box'])) { //delete process if box is not empty
			foreach($_SESSION['delete_box']['pwalet_id'] as $key => $id ) {
				$params=array('pwalet_id'=>$id);
				deleteRow('pendataan_pwalet',$params);
			}
			unset($_SESSION['delete_box']);
			//print_r($_SESSION['delete_box']);
		}
		if(isset($_REQUEST['detail'])) { //data detail
			$id_rekening=getIdRekening($_REQUEST['rekening']);
			
			if($x=searchRow('pendataan_pwalet','pendataan_id',$_REQUEST['FK'],$_REQUEST['id']) ) {
				//ada id row, update
				//echo $x;
				$exception = array(/*'nominal',*/'pendataan_id','pwalet_id');
						
				$other_request = array( 'dasar_pengenaan'=>$_REQUEST['pengenaan']
							,'persen_tarif'=>$_REQUEST['persen']
							,'id_rekening'=>$id_rekening
							,'dasar_tarif'=>$_REQUEST['tarif']
							,'nominal'=>$_REQUEST['pajak']
							//,'jumlah'=>$_REQUEST['jumlah']
							);
							
				ibase_trans();
				$str_where = " where pwalet_id =".$_REQUEST['id'];
				$b = $fbird->FBUpdate('pendataan_pwalet',$other_request, $exception, $str_where);
				if($b) {
					echo $_REQUEST['FK'];
				}
				unset($exception);
				unset($other_request);
			}
			else {
				//echo "Tidak Ada Row";
				//tambah baru
				$ID_WALET = $fbird->setGenerator('GEN_PENDATAAN_PWALET');
				$id_rekening=getIdRekening($_REQUEST['rekening']);
				$exception = array(/*'nominal'*/);
				$other_request = array('pwalet_id'=>$ID_WALET
						       ,'pendataan_id'=>$_REQUEST['FK']
						       ,'dasar_pengenaan'=>$_REQUEST['pengenaan']
						       ,'persen_tarif'=>$_REQUEST['persen']
						       ,'id_rekening'=>$id_rekening
						       ,'dasar_tarif'=>$_REQUEST['tarif']
						       ,'nominal'=>$_REQUEST['pajak']
						       //,'jumlah'=>$_REQUEST['jumlah']
						       );
				
				ibase_trans();
				$b = $fbird->FBInsert('pendataan_pwalet',$other_request, $exception);
				if($b) {
					echo $_REQUEST['FK'];
				}
				unset($exception);
				unset($other_request);
			}
			
			//print_r($_POST);
			
		}
		else { //data umum
			$exception = array('pendataan_id','pendaftaran_id','pendataan_no','memo','spt_id',/*'nominal',*/'nama_kegiatan');
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
				$ID_WALET = $fbird->setGenerator('GEN_PENDATAAN_PWALET');
				$id_rekening=getIdRekening($_REQUEST['rekening']);
				$exception = array(/*'nominal'*/);
				$other_request = array('pwalet_id'=>$ID_WALET
						       ,'pendataan_id'=>$_REQUEST['FK']
						       ,'dasar_pengenaan'=>$_REQUEST['pengenaan']
						       ,'persen_tarif'=>$_REQUEST['persen']
						       ,'id_rekening'=>$id_rekening
						       ,'dasar_tarif'=>$_REQUEST['tarif']
						       ,'nominal'=>$_REQUEST['pajak']
						       //,'jumlah'=>$_REQUEST['jumlah']
						       );
				
				ibase_trans();
				$b = $fbird->FBInsert('pendataan_pwalet',$other_request, $exception);
				unset($exception);
				unset($other_request);
			}
		}
		else { //data umum
			$ID=$fbird->setGenerator('GEN_PENDATAAN_SPT');
			$exception = array('spt_id','memo',/*'nominal',*/'nama_kegiatan');
			$other_request = array(
				'pendataan_no'	=>$_REQUEST['nomor'],
				'pendataan_id'	=> $ID
				);
			ibase_trans();
			$a = $fbird->FBInsert('pendataan_spt',$other_request,$exception);
			if($a) {
				echo $ID;
			}
			else {
				
			}
			unset($exception);
			unset($other_request);
		}
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