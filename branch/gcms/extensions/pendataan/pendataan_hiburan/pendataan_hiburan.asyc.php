<?php
include('./config.php');
//include "./../../../config.php";
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";


if($_REQUEST['sender']=="entri_pendataan_PajakHiburan"){

//echo $_REQUEST['sender'];
$tabel = "pendataan_phiburan";
$tabel_detail ="pendataan_phiburan_detail";
$tabel_spt = "pendataan_spt";
$tabel_id = "phiburan_id";
$tabel_detail_id = "phiburan_detail_id";
$tabel_spt_id = "pendataan_id";
$tabel_spt_no = "pendataan_no";
$TglAwal = quote_smart(blkDate($_REQUEST['TglJualMulai']));
$TglAkhir = quote_smart(blkDate($_REQUEST['TglJualSampai']));
$TglEntri = quote_smart(blkDate($_REQUEST['TglEntri']));
$TglProses = quote_smart(blkDate($_REQUEST['TglProses']));
$RekeningKode = $_REQUEST['KodeRekening1'].".".$_REQUEST['KodeRekening2'].".".$_REQUEST['KodeRekening3'];
	
	if($_REQUEST['action']=='edit'){
		
		if(isset($_REQUEST['detail'])){
			//echo "rrrr".$_REQUEST['persen_tarif'];
			if(!empty($_REQUEST['pendataan_idx'])){
				//echo "xxxx";
				unset($exception); unset($other_request);
				$exception = array('phiburan_id','pendataan_id');
				$other_request = array(
								  'phiburan_nama'=>$_REQUEST['FK1'],
								  'phiburan_alamat'=>$_REQUEST['FK2'],
								  'phiburan_id_desa'=>$_REQUEST['FK3']
				);
				$str_where = ' where phiburan_id='.quote_smart($_REQUEST['id']);
				//ibase_trans();	
				$a = $fbird->FBUpdate('pendataan_phiburan',$other_request,$exception,$str_where);
				if($a){
					//echo 'OK';
					ibase_commit();
				}else{
					echo 'Failed';
				}
				
				if($a && $_REQUEST['rows']==$_REQUEST['count']){
				
					ibase_commit();
				}
				if(!$a){
					echo 'gak comit';
					ibase_rollback();
				}
			}elseif(empty($_REQUEST['pendataan_idx']) && !empty($_REQUEST['id_rekening'])){
					
				$exception = array('phiburan_id');
				$other_request = array('pendataan_id'=>$_REQUEST['FK'],
								  'phiburan_nama'=>$_REQUEST['FK1'],
								  'phiburan_alamat'=>$_REQUEST['FK2'],
								  'phiburan_id_desa'=>$_REQUEST['FK3']
				);

				$a = $fbird->FBInsert('pendataan_phiburan',$other_request,$exception);unset($exception); unset($other_request);
					
					//echo $a;
				if($a && $_REQUEST['rows']==$_REQUEST['count']){
					//echo "xxx";
					ibase_commit();
				}
				if(!$a){
					echo "Errorr";
					ibase_rollback();
				}
			}elseif(!empty($_REQUEST['pendataan_idxx']) && !empty($_REQUEST['jumlah_meja'])){
			
				unset($exception); unset($other_request);
				$exception = array('phiburan_detail_id');
				//echo "FK->".$_REQUEST['FK'];
				$other_request = array('pendataan_id'=>$_REQUEST['pendataan_idxx']);
				$str_where = ' where phiburan_detail_id='.quote_smart($_REQUEST['id']);
			
				$a = $fbird->FBUpdate('pendataan_phiburan_detail',$other_request,$exception,$str_where);
				//$a = $fbird->FBInsert('pendataan_phiburan_detail',$other_request,$exception);
				if($a){
					ibase_commit();
				}else{
					ibase_rollback();
				}
			}elseif(empty($_REQUEST['pendataan_idxx']) && !empty($_REQUEST['jumlah_meja'])){
				unset($exception); unset($other_request);
				$exception = array('phiburan_detail_id');
				//echo "FK->".$_REQUEST['FK'];
				$other_request = array('pendataan_id'=>$_REQUEST['FK']);

				$a = $fbird->FBInsert('pendataan_phiburan_detail',$other_request,$exception);
				if($a){
					ibase_commit();
				}else{
					ibase_rollback();
				}
			}
		}else{
			// nilai nominal belum bisa diambil dari grid
			//echo "header"."\n";
			if($_REQUEST['SptIdHid']==''){
				$exception = array('pendataan_id','spt_id','nama_kegiatan','pendataan_no');
				$other_request = array('tgl_proses'=>$_REQUEST['TglProses'],
									   'tgl_entry'=>$_REQUEST['TglEntri'],
									   'jenis_pendataan'=>$_REQUEST['NamaPendataan'],
									   'jenis_pungutan'=>$_REQUEST['SystemPemungutan'],
									   'periode_awal'=>$_REQUEST['TglJualMulai'],
									   'periode_akhir'=>$_REQUEST['TglJualSampai'],
									   'nominal'=>str_replace(",","",$_REQUEST['Tarif']));
			}else{
				$exception = array('nama_kegiatan','pendataan_no','pendataan_id');
				$other_request = array('tgl_proses'=>$_REQUEST['TglProses'],
									   'tgl_entry'=>$_REQUEST['TglEntri'],
									   'jenis_pendataan'=>$_REQUEST['NamaPendataan'],
									   'jenis_pungutan'=>$_REQUEST['SystemPemungutan'],
									   'periode_awal'=>$_REQUEST['TglJualMulai'],
									   'periode_akhir'=>$_REQUEST['TglJualSampai'],
									   'nominal'=>str_replace(",","",$_REQUEST['Tarif']),
									   'spt_id'=>$_REQUEST['SptIdHid']);
			}
				
			$str_where = ' where pendataan_id='.quote_smart($_REQUEST['pendataan_id_hid']);
		
			$a = $fbird->FBUpdate('pendataan_spt',$other_request,$exception,$str_where); unset($exception); unset($other_request);
			if($a) {
			echo $_REQUEST['pendataan_id_hid']."xxx".$_REQUEST['nama_wp_wr']."xxx".$_REQUEST['Alamat']."xxx".$_REQUEST['id_desa'];
				ibase_commit();
			}else{echo '!';}
			
			//echo 'update header';
			//$ID_HEADER=88;
			//echo $_REQUEST['pendataan_id_hid'];
		}
	}else{ //ENd Edit Begin Insert

					
			if(isset($_REQUEST['detail'])){
					$ID_HEADER = $fbird->setGenerator('GEN_PENDATAAN_PHIBURAN');
					//echo "Nama->".$_REQUEST['FK1'];
					//unset($exception); unset($other_request);
					
					$exceptionxx = array('phiburan_id');
									
					$other_requestxxx = array(
										   'pendataan_id'=>$_REQUEST['FK'],
										   'phiburan_nama'=>$_REQUEST['FK1'],
										   'phiburan_alamat'=>$_REQUEST['FK2'],
										   'phiburan_id_desa'=>$_REQUEST['FK3']
										   );
										
					$ab = $fbird->FBInsert('pendataan_phiburan',$other_requestxxx,$exceptionxx);
					unset($exceptionxx); unset($other_requestxxx);						
					//End Insert phiburan
					//Begin Insert phiburan Detail
					
					unset($exception); unset($other_request);
					$exception = array('phiburan_detail_id');
					//echo "FK->".$_REQUEST['FK'];
					$other_request = array('pendataan_id'=>$_REQUEST['FK']);

					$a = $fbird->FBInsert('pendataan_phiburan_detail',$other_request,$exception);
					if($a) //echo 'Insert Detail OK';
					
					//echo $a;
					if($a && $_REQUEST['rows']==$_REQUEST['count']){
						
						ibase_commit();
						//echo "xxx";
					}
					if(!$a){
						//	echo "no";
						ibase_rollback();
					}
						
			}else{
						
				unset($exception); unset($other_request);
				$ID_HEADER = $fbird->setGenerator('GEN_PENDATAAN_SPT');
				
				$cr_spt_no = gcms_query("select max(".$tabel_spt_no.") AS IdMax from ".$tabel_spt." ");
								$max_spt = gcms_fetch_object($cr_spt_no);
								$new_no_spt = $max_spt->IDMAX+1;
										
				if($_REQUEST['SptIdHid']==''){
					$exception = array('spt_id','nama_kegiatan');
					$other_request = array('pendataan_id'=>$ID_HEADER,
										   'pendataan_no'=>$new_no_spt,
										   'tgl_proses'=>$_REQUEST['TglProses'],
										   'tgl_entry'=>$_REQUEST['TglEntri'],
										   'jenis_pendataan'=>$_REQUEST['NamaPendataan'],
										   'jenis_pungutan'=>$_REQUEST['SystemPemungutan'],
										   'periode_awal'=>$_REQUEST['TglJualMulai'],
										   'periode_akhir'=>$_REQUEST['TglJualSampai'],
										   'nominal'=>str_replace(",","",$_REQUEST['pajak23']));
				}else{
					$exception = array('nama_kegiatan');
					$other_request = array('pendataan_id'=>$ID_HEADER,
										   'pendataan_no'=>$new_no_spt,
										   'tgl_proses'=>$_REQUEST['TglProses'],
										   'tgl_entry'=>$_REQUEST['TglEntri'],
										   'jenis_pendataan'=>$_REQUEST['NamaPendataan'],
										   'jenis_pungutan'=>$_REQUEST['SystemPemungutan'],
										   'periode_awal'=>$_REQUEST['TglJualMulai'],
										   'periode_akhir'=>$_REQUEST['TglJualSampai'],
										   'nominal'=>str_replace(",","",$_REQUEST['pajak23']),
										   'spt_id'=>$_REQUEST['SptIdHid']);
				}
					
						ibase_trans();
						$a = $fbird->FBInsert('pendataan_spt',$other_request,$exception); 
						if($a){
							//echo "xxxxxxyyyxxxx";
							echo $ID_HEADER."xxx".$_REQUEST['nama_wp_wr']."xxx".$_REQUEST['Alamat']."xxx".$_REQUEST['id_desa'];
						}else{
							echo '!';
						}//echo "aaaaa";
						
			}
					$update_tabel_rekening ="update rekening_kode set tarif_dasar='".str_replace(",","",$_REQUEST['Tarif'])."',persen_tarif='".$_REQUEST['persen']."' where id='".$_REQUEST['IdRekening']."'";
			
		//$ID_HEADER=99;
		
	}
	
}
?>