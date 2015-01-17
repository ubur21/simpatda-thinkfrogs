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
elseif($_REQUEST['sender']=='daftar_rekening'){

	$crudColumns =  array(
		'id'=>'id'
		,'kode_rekening'=>'kode_rekening'
		,'nama_rekening'=>'nama_rekening'
		,'tarif_dasar'=>'tarif_dasar'
		,'persen_tarif'=>'persen_tarif'
		
	);
		
	$crudTableName = 'REKENING_KODE';
	$Filter = 'objek';
	$NilaiFilter ='01';
	include $expath.'jqGridCrudALL.php';


}
elseif($_REQUEST['sender']=='penetapan_PajakHotel'){
	
	$crudColumns =  array(
		'no'=>''
		,'id'=>'hotel_id'
		,'TglEntri'=>'tgl_entry'
		,'NpwpdNpwrd'=>'npwp'
		,'Nama'=>'hotel_nama'
		,'Alamat'=>'hotel_alamat'
		,'pemungutan'=>'jenis_pungutan'
		,'periodeAwal'=>'periode_awal'
		,'periodeAkhir'=>'periode_akhir'
		,'NamaRekening'=>'hotel_alamat'
		,'TarifDasar'=>'nominal'
		,'Persen'=>'persen_tarif'
		,'Pajak'=>''
		
	);
		
	$crudTableName = 'PENDATAAN_HOTEL';
	$ID ="HOTEL_";
	$crudTableJoin1 = "PENDATAAN_SPT";
	$crudTableJoin2 = "PENDAFTARAN";
	$crudTableJoin3 = "REKENING_KODE";
	$crudIdJoin1 = "pendataan_id";
	$crudIdJoin2 = "pendaftaran_id";
	$crudIdJoin3 = "id";
	
	include $expath.'jqGridCrudPendataanHotel.php';
}
/*elseif($_REQUEST['sender']=='DetailHotel'){
	
	$crudColumns =  array(
		'id'=>'hotel_detail_id'
		,'hotel_id'=>'hotel_id'
		,'nama_kamar'=>'nama_kamar'
		,'jumlah_kamar'=>'jumlah_kamar'
		,'tarif_kamar'=>'tarif_kamar'
			
	);
		
	$crudTableName = 'PENDATAAN_HOTEL_DETAIL';
	$ID = 'HOTEL_DETAIL_';
	//$query1
	include $expath.'jqGridCrudALL.php';
	
}*/elseif($_REQUEST['sender']=='get_DataFormHotel'){

	$sql = 
	   'select 
	   	ph.hotel_id,ps.tgl_proses,
		ps.tgl_entry,ph.hotel_id_desa,
		p.pendaftaran_id,ph.id_rekening,
		ps.pendataan_id,p.npwp,
		ps.pendataan_no,p.tanggal_kembali,
		ph.hotel_nama,ph.hotel_alamat,
		k.lurah_nama,kc.camat_nama,
		ps.memo,ps.jenis_pungutan,
		rk.nama_rekening,ph.nominal,
		ph.persen_tarif,ps.periode_awal,
		ps.periode_akhir,ph.dasar_pengenaan,
		rk.kode_rekening,s.spt_id,
		pm.id_desa,k.lurah_kecamatan,
		p.no_pendaftaran,p.jenis_pendaftaran,
		s.spt_no
	    from pendataan_hotel ph
							left join pendataan_spt ps on ph.pendataan_id = ps.pendataan_id
							left join pendataan_hotel_detail pd on ps.pendataan_id = pd.pendataan_id
							left join pendaftaran p on ps.pendaftaran_id = p.pendaftaran_id
							left join rekening_kode rk on ph.id_rekening = rk.id
							left join spt s on p.pendaftaran_id = s.pendaftaran_id
							left join pemohon pm on p.id_pemohon = pm.pemohon_id 
							left join kelurahan k on pm.id_desa = k.lurah_id
							left join kecamatan kc on k.lurah_kecamatan = kc.camat_id
							where ph.hotel_id='.quote_smart($_REQUEST['val']);
		   
	$data = gcms_query($sql);
	$x=1;
	$rs = gcms_fetch_object($data);	
	$pendataan_no = sprintf('%06d',$rs->pendataan_no);
	//$spt_no = ($rs->spt_no!='') ? sprintf('%05d',$rs->spt_no) : '';
	if($row->tanggal_kembali=="" || $rs->tanggal_kembali ==NULL || $rs->tanggal_kembali =="00.00.0000"){
		$Tgl = "";
	}else{
		$Tgl = date("Y",strtotime($rs->tanggal_kembali));
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
			"kode_rekening1"=>$KodeRek1,
			"kode_rekening2"=>$KodeRek2,
			"kode_rekening3"=>$KodeRek3,
			"tgl_proses"=>formatDate($rs->tgl_proses),
			"tgl_entry"=>formatDate($rs->tgl_entry),
			"periode_awal"=>formatDate($rs->periode_awal),
			"periode_akhir"=>formatDate($rs->periode_akhir),			
			"nama_rekening"=>$rs->nama_rekening,
			"nama"=>$rs->hotel_nama,
			"alamat"=>$rs->hotel_alamat,
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
}elseif($_REQUEST['sender']=="ActDetailHotel"){
	echo 
				'{
					"page":"1",
					"total":2,
					"records":2,
					"rows":[
						{"id":2,"cell":[2,"","","",""]},
					]
				}';
}
elseif($_REQUEST['sender']=="DataHotel"){
			
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
		case 'DetailHotel':
			$o=null;
			$sql = 'select
				   d.hotel_detail_id,
				   d.nama_kamar,
				   d.jumlah_kamar,
				   d.tarif_kamar,
				   d.pendataan_id
				   from pendataan_hotel_detail d
				   join pendataan_hotel b on d.pendataan_id=b.pendataan_id
				   join pendataan_spt a on d.pendataan_id = a.pendataan_id
				   where b.hotel_id='.$_REQUEST['val'].' ';
				   
			$o->page = 1; 
			$o->total = 1;
			$o->records = 1;				   
			$i=0; //$jumlah=0; $pajak=0;
			$result = gcms_query($sql);
			//echo $sql;
			while($row = gcms_fetch_row($result)){ 
				$o->rows[$i]['id']=$row[0];
				$data = $row;
				$data[4] = $row[2]*$row[3];
				$data[5] = $row[4];
				$o->rows[$i]['cell']=$data;
				$i++; //$jumlah+=$row['3']; $pajak+=$row['7'];
			
			}
			//"row"=>$i;
			/*$o->userdata['lokasi'] = 'Total'; 
			$o->userdata['jumlah'] = $jumlah;
			$o->userdata['pajak'] = $pajak;*/
			
			print json_encode($o);
		break;
		case 'delete':
			echo 
				'{
					"page":"1",
					"total":2,
					"records":2,
					"rows":[
						{"id":2,"cell":[2,"","","",""]},
					]
				}';
		break;
		case 'DetailHotelList' :
			$o=null;
			$sql = 'select
				   d.hotel_detail_id,
				   d.nama_kamar,
				   d.jumlah_kamar,
				   d.tarif_kamar
				  
				   from pendataan_hotel_detail d
				   join pendataan_hotel b on d.pendataan_id=b.pendataan_id
				   join pendataan_spt a on d.pendataan_id = a.pendataan_id
				   where b.hotel_id='.$_REQUEST['val'].' ';
				   
			$o->page = 1; 
			$o->total = 1;
			$o->records = 1;				   
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

}/*else{
			echo 
				'{
					"page":"1",
					"total":0,
					"records":0,
					"rows":[
						{"id":0,"cell":[0,"","","",""]},
					]
				}';
}*/
?>