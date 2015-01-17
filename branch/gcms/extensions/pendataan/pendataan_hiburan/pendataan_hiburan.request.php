<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='daftarnpwp'){

	$crudColumns =  array(
		'id'=>'pendaftaran_id'
		,'NoPendaftaran'=>'no_pendaftaran'
		,'NPWP'=>'npwp'
		,'Nama'=>'nama'
		,'Alamat'=>'alamat'
		//,'Kelurahan'=>'wp_wr_lurah'
		//,'Kecamatan'=>'wp_wr_camat'
		//,'Kabupaten'=>'wp_wr_kabupaten'
		
	);
		
	$crudTableName = 'PENDAFTARAN';
	$ID = 'PENDAFTARAN_';
	include $expath.'jqGridCrud.php';


}
if($_REQUEST['sender']=='daftar_rekening'){

	$crudColumns =  array(
		'id'=>'id'
		,'kode_rekening'=>'kode_rekening'
		,'nama_rekening'=>'nama_rekening'
		,'tarif_dasar'=>'tarif_dasar'
		,'persen_tarif'=>'persen_tarif'
		
	);
		
	$crudTableName = 'REKENING_KODE';
	$Filter = 'objek';
	$NilaiFilter ='02';
	include $expath.'jqGridCrudALL.php';


}
if($_REQUEST['sender']=='penetapan_PajakHiburan'){
	
	$crudColumns =  array(
		'id'=>'phiburan_id'
		,'TglEntri'=>'tgl_entry'
		,'NpwpdNpwrd'=>'npwp'
		,'Nama'=>'nama'
		,'Alamat'=>'alamat'
		,'pemungutan'=>'jenis_pungutan'
		,'periodeAwal'=>'periode_awal'
		,'periodeAkhir'=>'periode_akhir'
		,'Pajak'=>'nominal'
		
	);
		
	$crudTableName = 'PENDATAAN_SPT';
	$ID ="PENDATAAN_";
	$Filter = 'pendataan_spt.jenis_pendataan';
	$NilaiFilter ='HIBURAN';
	include $expath.'jqGridCrudPendataanHiburan.php';
}
if($_REQUEST['sender']=='set_form'){
	$id = getIdRekening($_REQUEST['id']);
	$qry = 'select id,persen_tarif, tarif_dasar from rekening_kode where id='.quote_smart($id);
	$result = gcms_query($qry);
	$rs = gcms_fetch_object($result);
	$arr = array(
				'persen'=>$rs->persen_tarif,
				'tarif'=>$rs->tarif_dasar,
				'id_rekening'=>$rs->id
			);
			
	echo json_encode($arr);	
}
if($_REQUEST['sender']=="DataHiburan"){
	switch($_REQUEST['action']){
		case 'read':
			echo 
				'{
					"page":"0",
					"total":0,
					"records":0,
					"rows":[
						{"id":0,"cell":[0,"","","",""]},
					]
				}';
		break;
		case 'DetailHiburan':
			$o=null;
			$sql = 'select
				   ph.phiburan_id,
				   rk.kode_rekening,
				  
				   ph.dasar_pengenaan,
				   ph.persen_tarif,
				   ph.nominal,
				   ph.pendataan_id,
				   ph.id_rekening
				  
				   from pendataan_phiburan ph
				   join rekening_kode rk on ph.id_rekening = rk.id
				   where ph.pendataan_id='.$_REQUEST['val'].' ';
				   
			
			$i=0; //$jumlah=0; $pajak=0;
			$result = gcms_query($sql);
			//echo $sql;
			while($row = gcms_fetch_row($result)){ 
				$o->rows[$i]['id']=$row[0];
				$data1 = $row;
				//$data[1] = "aaaaa";//."-".$row[2];
				//$data1[2] = '1231';
				$o->rows[$i]['cell']=$data1;
				$i++; //$jumlah+=$row['3']; $pajak+=$row['7'];
			
			}
			
			if($DEBUGMODE == 1){$firephp->info('READ','action');}
			/*query to count rows*/
			$sql='select count(phiburan_id) as numRows from pendataan_phiburan';
			if($DEBUGMODE == 1){$firephp->info($sql,'query');}
			$result = gcms_query($sql);
            $row = gcms_fetch_assoc($result);
            $count = $row["NUMROWS"];
			if($DEBUGMODE == 1){$firephp->info($count,'rows');}
				$intLimit = $postConfig['limit'];
				/*set the page count*/
				
			if( $count > 0 && $intLimit > 0) { $total_pages = ceil($count/$intLimit); } 
			else { $total_pages = 1; } 
			
			if($DEBUGMODE == 1){$firephp->info($total_pages,'total_pages');}
			$intPage = $postConfig['page'];
			
			if ($intPage > $total_pages){$intPage=$total_pages;}
			$intStart = (($intPage-1) * $intLimit);
			
			$o->page = $intPage; 
			$o->total = $total_pages;
			$o->records = $count;
			print json_encode($o);
		break;
		case 'DetailHiburanList' :
			$o=null;
			$sql = 'select
				   rk.kode_rekening,
				   rk.nama_rekening,
				   ph.dasar_pengenaan,
				   ph.persen_tarif,
				   ph.nominal
				  
				   from pendataan_phiburan ph
				   join rekening_kode rk on ph.id_rekening = rk.id
				   where ph.pendataan_id='.$_REQUEST['val'].' ';
				   

			$i=0; //$jumlah=0; $pajak=0;
			$result = gcms_query($sql);
			//echo $sql;
			while($row = gcms_fetch_row($result)){ 
				$o->rows[$i]['id']=$row[0];
				$data = $row;
				//$data[4] = $row[2]*$row[3];
				//$data[5] = $row[4];
				$o->rows[$i]['cell']=$data;
				$i++; //$jumlah+=$row['3']; $pajak+=$row['7'];
			
			}
			if($DEBUGMODE == 1){$firephp->info('READ','action');}
			/*query to count rows*/
			$sql='select count(phiburan_id) as numRows from pendataan_phiburan';
			if($DEBUGMODE == 1){$firephp->info($sql,'query');}
			$result = gcms_query($sql);
            $row = gcms_fetch_assoc($result);
            $count = $row["NUMROWS"];
			if($DEBUGMODE == 1){$firephp->info($count,'rows');}
				$intLimit = $postConfig['limit'];
				/*set the page count*/
				
			if( $count > 0 && $intLimit > 0) { $total_pages = ceil($count/$intLimit); } 
			else { $total_pages = 1; } 
			
			if($DEBUGMODE == 1){$firephp->info($total_pages,'total_pages');}
			$intPage = $postConfig['page'];
			
			if ($intPage > $total_pages){$intPage=$total_pages;}
			$intStart = (($intPage-1) * $intLimit);
			$o->page = $intPage; 
			$o->total = $total_pages;
			$o->records = $count;
			print json_encode($o);
		break;
		case 'DetailRincianHiburan':
			$o=null;
			$sql = 'select
				   pd.phiburan_detail_id,
				   pd.jumlah_meja,
				   pd.jumlah_mesin,
				   pd.rata_jam,
				   pd.tarif,
				   pd.pendataan_id
				  
				   from pendataan_phiburan_detail pd
				   
				   where pd.pendataan_id='.$_REQUEST['val'].' ';
				   
			
			$i=0; //$jumlah=0; $pajak=0;
			$result = gcms_query($sql);
			//echo $sql;
			while($row = gcms_fetch_row($result)){ 
				$o->rows[$i]['id']=$row[0];
				$data = $row;
				//$data[4] = $row[2]*$row[3];
				//$data[5] = $row[4];
				$o->rows[$i]['cell']=$data;
				$i++; //$jumlah+=$row['3']; $pajak+=$row['7'];
			
			}
			if($DEBUGMODE == 1){$firephp->info('READ','action');}
			/*query to count rows*/
			$sql='select count(phiburan_detail_id) as numRows from pendataan_phiburan_detail';
			if($DEBUGMODE == 1){$firephp->info($sql,'query');}
			$result = gcms_query($sql);
            $row = gcms_fetch_assoc($result);
            $count = $row["NUMROWS"];
			if($DEBUGMODE == 1){$firephp->info($count,'rows');}
				$intLimit = $postConfig['limit'];
				/*set the page count*/
				
			if( $count > 0 && $intLimit > 0) { $total_pages = ceil($count/$intLimit); } 
			else { $total_pages = 1; } 
			
			if($DEBUGMODE == 1){$firephp->info($total_pages,'total_pages');}
			$intPage = $postConfig['page'];
			
			if ($intPage > $total_pages){$intPage=$total_pages;}
			$intStart = (($intPage-1) * $intLimit);
			
			$o->page = $intPage; 
			$o->total = $total_pages;
			$o->records = $count;
			print json_encode($o);
		break;
		default:
			echo 
				'{
					"page":"0",
					"total":0,
					"records":0,
					"rows":[
						{"id":0,"cell":[0,"","","",""]},
					]
				}';
		
	}
}
if($_REQUEST['sender']=="get_DataFormHiburan"){
	$sql = 
	   'select 
	   	ph.phiburan_id,ps.tgl_proses,
		ps.tgl_entry,ph.phiburan_id_desa,
		p.pendaftaran_id,ph.id_rekening,
		ps.pendataan_id,p.npwp,
		ps.pendataan_no,p.tanggal_kembali,
		ph.phiburan_nama,ph.phiburan_alamat,
		k.lurah_nama,kc.camat_nama,
		ps.memo,ps.jenis_pungutan,
		rk.nama_rekening,ps.nominal,
		ph.persen_tarif,ps.periode_awal,
		ps.periode_akhir,ph.dasar_pengenaan,
		rk.kode_rekening,s.spt_id,pm.id_desa,k.lurah_kecamatan,
		p.no_pendaftaran,p.jenis_pendaftaran,
		s.spt_no
		
	    from pendataan_phiburan ph
							left join pendataan_spt ps on ph.pendataan_id = ps.pendataan_id
							left join pendataan_phiburan_detail pd on ps.pendataan_id = pd.pendataan_id
							left join pendaftaran p on ps.pendaftaran_id = p.pendaftaran_id
							left join rekening_kode rk on ph.id_rekening = rk.id
							left join spt s on p.pendaftaran_id = s.pendaftaran_id
							left join pemohon pm on p.id_pemohon = pm.pemohon_id 
							left join kelurahan k on pm.id_desa = k.lurah_id
							left join kecamatan kc on k.lurah_kecamatan = kc.camat_id
							where ph.pendataan_id='.quote_smart($_REQUEST['val']);
		   
	$data = gcms_query($sql);
	$x=1;
	$rs = gcms_fetch_object($data);	
	$pendataan_no = sprintf('%06d',$rs->pendataan_no);
	//$spt_no = ($rs->spt_no!='') ? sprintf('%05d',$rs->spt_no) : '';
	if($row->tanggal_kembali=="" || $rs->tanggal_kembali ==NULL){
		$Tgl = "";
	}else{
		$Tgl = date("Y",strtotime($rs->periode_akhir));
	}
	$replace = explode(".",$rs->kode_rekening);
	$KodeRek1 = $replace[0].$replace[1].$replace[2];
	$KodeRek2 = $replace[3];
	$KodeRek3 = $replace[4];
	if($rs->jenis_pendaftaran=="PAJAK"){
	$kode = "PAJAK";
	}else{
	$kode = "RETRIBUSI";
	}
	$x++;
	
	$arr = array(
			"nomor"=>$pendataan_no,
			"spt_no"=>$rs->spt_no,
			"periode_spt"=>date("Y",strtotime($rs->tanggal_kembali)),
			"jenis_pungutan"=>$rs->jenis_pungutan,
			"pendaftaran"=>$rs->pendaftaran_id,
			"memo"=>$rs->memo,
			"id_desa"=>$rs->phiburan_id_desa,
			"kode_rekening1"=>$KodeRek1,
			"kode_rekening2"=>$KodeRek2,
			"kode_rekening3"=>$KodeRek3,
			"tgl_proses"=>formatDate($rs->tgl_proses),
			"tgl_entry"=>formatDate($rs->tgl_entry),
			"periode_awal"=>formatDate($rs->periode_awal),
			"periode_akhir"=>formatDate($rs->periode_akhir),			
			"nama_rekening"=>$rs->nama_rekening,
			"nama"=>$rs->phiburan_nama,
			"alamat"=>$rs->phiburan_alamat,
			"kecamatan"=>$rs->camat_nama,
			"kelurahan"=>$rs->lurah_nama,
			"spt_id"=>$rs->spt_id,
			"nominal"=>$rs->dasar_pengenaan,
			"pendataan_id"=>$rs->pendataan_id,
			"rekening_id"=>$rs->id_rekening,
			"tarif"=>$rs->nominal,
			"persen"=>$rs->persen_tarif,
			"npwp"=>$rs->npwp,
			"kode_npwp"=>$kode,
			"no_pendaftaran"=>$rs->no_pendaftaran,
			"id_desa"=>$rs->id_desa,
			"lurah"=>sprintf("%02d",$rs->id_desa),
			"camat"=>sprintf("%02d",$rs->lurah_kecamatan),
			"row"=>$x
		);
	echo json_encode($arr);	
}
?>