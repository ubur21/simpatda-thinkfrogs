<?php
include('./config.php');
//include "./../../../config.php";
//include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";


if($_REQUEST['sender']=="entri_pendataan_PajakRestoran"){

//echo $_REQUEST['sender'];
$tabel = "pendataan_restoran";
$tabel_detail ="pendataan_restoran_detail";
$tabel_spt = "pendataan_spt";
$tabel_id = "restoran_id";
$tabel_detail_id = "restoran_detail_id";
$tabel_spt_id = "pendataan_id";
$tabel_spt_no = "pendataan_no";
$TglAwal = quote_smart(blkDate($_REQUEST['TglJualMulai']));
$TglAkhir = quote_smart(blkDate($_REQUEST['TglJualSampai']));
$TglEntri = quote_smart(blkDate($_REQUEST['TglEntri']));
$TglProses = quote_smart(blkDate($_REQUEST['TglProses']));
$RekeningKode = $_REQUEST['KodeRekening1'].".".$_REQUEST['KodeRekening2'].".".$_REQUEST['KodeRekening3'];
	
	if($_REQUEST['action']=='edit'){
		
		if(isset($_REQUEST['detail'])){
		
			if(!empty($_REQUEST['pendataan_idx'])){
				
				unset($exception); unset($other_request);
				$exception = array('restoran_detail_id','pendataan_id');
				$other_request = array();
				$str_where = ' where restoran_detail_id='.quote_smart($_REQUEST['id']);
				//ibase_trans();	
				$a = $fbird->FBUpdate('pendataan_restoran_detail',$other_request,$exception,$str_where);
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
			}elseif(empty($_REQUEST['pendataan_idx']) && !empty($_REQUEST['jumlah_meja'])){
					
					//echo "FK->".$_REQUEST['FK'];
					unset($exception); unset($other_request);
					//$ID_DETAIL = $fbird->setGenerator('GEN_PENDATAAN_RESTORAN_DETAIL');
					$exception = array('restoran_detail_id');

					$other_request = array('pendataan_id'=>$_REQUEST['FK']);

					$a = $fbird->FBInsert('pendataan_restoran_detail',$other_request,$exception);unset($exception); unset($other_request);
					
					
					//echo $a;
					if($a && $_REQUEST['rows']==$_REQUEST['count']){
						//echo "xxx";
						ibase_commit();
					}
					if(!$a){
						echo "Errorr";
						ibase_rollback();
					}
			}
		}else{
			// nilai nominal belum bisa diambil dari grid
			//echo "header"."\n";
			if($_REQUEST['SptIdHid']==''){
				$exception = array('pendataan_id','spt_id','nama_kegiatan');
				$other_request = array('pendataan_no'=>$_REQUEST['NoHid'],
									   'tgl_proses'=>$_REQUEST['TglProses'],
									   'tgl_entry'=>$_REQUEST['TglEntri'],
									   'jenis_pendataan'=>$_REQUEST['NamaPendataan'],
									   'jenis_pungutan'=>$_REQUEST['SystemPemungutan'],
									   'periode_awal'=>$_REQUEST['TglJualMulai'],
									   'periode_akhir'=>$_REQUEST['TglJualSampai'],
									   'nominal'=>str_replace(",","",$_REQUEST['Pajak']));
			}else{
				$exception = array('nama_kegiatan','pendataan_id');
				$other_request = array('pendataan_no'=>$_REQUEST['NoHid'],
									   'tgl_proses'=>$_REQUEST['TglProses'],
									   'tgl_entry'=>$_REQUEST['TglEntri'],
									   'jenis_pendataan'=>$_REQUEST['NamaPendataan'],
									   'jenis_pungutan'=>$_REQUEST['SystemPemungutan'],
									   'periode_awal'=>$_REQUEST['TglJualMulai'],
									   'periode_akhir'=>$_REQUEST['TglJualSampai'],
									   'nominal'=>str_replace(",","",$_REQUEST['Pajak']),
									   'spt_id'=>$_REQUEST['SptIdHid']);
			}
				
			$str_where = ' where pendataan_id='.quote_smart($_REQUEST['pendataan_id_hid']);
		
			$a = $fbird->FBUpdate('pendataan_spt',$other_request,$exception,$str_where); unset($exception); unset($other_request);
			if($a) {
				ibase_commit();
			}else{echo '!';}
			
			//echo 'update header';
			//$ID_HEADER=88;
			echo $_REQUEST['pendataan_id_hid'];
		}
	}else{ //ENd Edit Begin Insert

					
			if(isset($_REQUEST['detail'])){
						
					unset($exception); unset($other_request);
					$exception = array('restoran_detail_id');
					//echo "FK->".$_REQUEST['FK'];
					$other_request = array('pendataan_id'=>$_REQUEST['FK']);

					$a = $fbird->FBInsert('pendataan_restoran_detail',$other_request,$exception);
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
										   'nominal'=>str_replace(",","",$_REQUEST['Pajak']));
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
										   'nominal'=>str_replace(",","",$_REQUEST['Pajak']),
										   'spt_id'=>$_REQUEST['SptIdHid']);
						}
					
						ibase_trans();
						$a = $fbird->FBInsert('pendataan_spt',$other_request,$exception); 
						//echo "aaaaa";
						unset($exception); unset($other_request);
						
						$exceptionxx = array('restoran_id');
										
						$other_requestxxx = array('pendataan_id'=>$ID_HEADER,
											   'restoran_nama'=>$_REQUEST['nama_wp_wr'],
											   'restoran_alamat'=>$_REQUEST['Alamat'],
											   'restoran_id_desa'=>$_REQUEST['id_desa'],
											   'nominal'=>str_replace(",","",$_REQUEST['Tarif']),
											   'persen_tarif'=>$_REQUEST['persen'],
											   'id_rekening'=>$_REQUEST['IdRekening'],
											   'dasar_pengenaan'=>str_replace(",","",$_REQUEST['Pajak']));
											
						$ab = $fbird->FBInsert('pendataan_restoran',$other_requestxxx,$exceptionxx);
						unset($exceptionxx); unset($other_requestxxx);						
						
						if($a && $ab){
							//echo "xxxxxxyyyxxxx";
							echo $ID_HEADER;
						}else{
							echo '!';
						}	
					}
					$update_tabel_rekening ="update rekening_kode set tarif_dasar='".str_replace(",","",$_REQUEST['Tarif'])."',persen_tarif='".$_REQUEST['persen']."' where id='".$_REQUEST['IdRekening']."'";
			
		//$ID_HEADER=99;
		//echo $ID_HEADER;	
	}
}
?>