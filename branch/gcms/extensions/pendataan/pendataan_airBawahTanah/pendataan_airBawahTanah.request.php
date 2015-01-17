<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='pendataan_spt'){

	$crudColumns =  array(
		'id'=>'id'
		,'pendataan_no'=>'pendataan_no'
		,'tgl_entry'=>'tgl_entry'
		,'npwp'=>'npwp'
		,'nama'=>'nama'
		,'jenis_pungutan'=>'jenis_pungutan'
		,'kode_rekening'=>'kode_rekening'
		,'nominal_pajak'=>'nominal_pajak'
		,'memo'=>'memo'
		);
		
	$ID='pendataan_';
	
	$tblHeader = 'pendataan_spt';
	$tblContent = 'pendataan_air';
	$tblDetail = '';
	include $expath.'handler_pendaftaran_bu.php';	
	
}
elseif($_REQUEST['sender']=='get_air_tanah'){

	$sql = 
	   'SELECT
		  a.pendataan_id as id,
		  a.pendaftaran_id,
		  a.pendataan_no,
		  a.tgl_entry,
		  a.tgl_proses,
		  a.periode_awal,
		  a.periode_akhir,
		  a.jenis_pungutan,
		  a.memo,
		  a.nominal as nominal_pajak,
		  c.npwp,
		  d.nama,
		  d.alamat,
		  e.spt_no,
		  e.spt_id as spt,
		  f.lurah_nama,
		  g.camat_nama
		FROM
		  pendataan_spt a
		  JOIN pendaftaran c on c.pendaftaran_id=a.pendaftaran_id
		  JOIN pemohon d on d.pemohon_id=c.id_pemohon
		  JOIN kelurahan f on f.lurah_id=d.id_desa
		  JOIN kecamatan g on g.camat_id=f.lurah_kecamatan		  
		  LEFT JOIN spt e on e.spt_id=a.spt_id
		WHERE c.objek_pdrd=\'PRIBADI\' and a.jenis_pendataan=\'AIR\' and a.pendataan_id='.quote_smart($_REQUEST['val']).'
		union
		SELECT
		  a.pendataan_id as id,
		  a.pendaftaran_id,
		  a.pendataan_no,
		  a.tgl_entry,
		  a.tgl_proses,
		  a.periode_awal,
		  a.periode_akhir,
		  a.jenis_pungutan,
		  a.memo,
		  a.nominal as nominal_pajak,
		  c.npwp,
		  d.nama,
		  d.alamat,
		  e.spt_no,
		  e.spt_id as spt,
		  f.lurah_nama,
		  g.camat_nama
		FROM
		  pendataan_spt a
		  JOIN pendaftaran c on c.pendaftaran_id=a.pendaftaran_id
		  JOIN badan_usaha d on d.id=c.id_pemohon
		  JOIN kelurahan f on f.lurah_id=d.badan_id_desa
		  JOIN kecamatan g on g.camat_id=f.lurah_kecamatan
		  LEFT JOIN spt e on e.spt_id=a.spt_id
		WHERE c.objek_pdrd=\'BU\' and a.jenis_pendataan=\'AIR\' and a.pendataan_id='.quote_smart($_REQUEST['val']);
		   
	$data = gcms_query($sql);
	$rs = gcms_fetch_object($data);
		
	$wp_wr_jenis = substr($rs->npwp,0,1);
	$wp_wr_gol = substr($rs->npwp,1,1);
	$wp_wr_no_urut = substr($rs->npwp,2,5);
	$wp_wr_kd_camat = substr($rs->npwp,7,2);
	$wp_wr_kd_lurah = substr($rs->npwp,9,2);
	
	$pendataan_no = sprintf('%05d',$rs->pendataan_no);
	$spt_no = ($rs->spt_no!='') ? sprintf('%05d',$rs->spt_no) : '';
	
	$arr = array(
			"nomor"=>$pendataan_no,
			"spt_no"=>$spt_no,
			"spt"=>$rs->spt,
			"jenis_pungutan"=>$rs->jenis_pungutan,
			"pendaftaran"=>$rs->pendaftaran_id,
			"memo"=>$rs->memo,
	
			"tgl_proses"=>formatDate($rs->tgl_proses),
			"tgl_entry"=>formatDate($rs->tgl_entry),
			"periode_awal"=>formatDate($rs->periode_awal),
			"periode_akhir"=>formatDate($rs->periode_akhir),			
			
			"wp_wr_jenis"=>$wp_wr_jenis,
			"wp_wr_gol"=>$wp_wr_gol,
			"wp_wr_no_urut"=>$wp_wr_no_urut,
			"wp_wr_kd_camat"=>$wp_wr_kd_camat,
			"wp_wr_kd_lurah"=>$wp_wr_kd_lurah,

			"rekening"=>$rs->kode_rekening.' - '.$rs->nama_rekening,	
			"kode_rekening"=>$rs->kode_rekening,
			"nama_rekening"=>$rs->nama_rekening,			
			
			"nama"=>$rs->nama,
			"alamat"=>$rs->alamat,
			"kecamatan"=>$rs->camat_nama,
			"kelurahan"=>$rs->lurah_nama,
			
			"nominal"=>$rs->nominal_pajak,
				
		);
	echo json_encode($arr);	
}
elseif($_REQUEST['sender']=='detail_air_tanahs'){
	$sql = '';
}
else{
	
	switch($_REQUEST['action']){
		case 'read':
			echo 
				'{
					"page":"1",
					"total":0,
					"records":0,
					"rows":[
						{"id":1,"cell":[1,"'.$_REQUEST['rekening'].'","","","","","",""]},
					]
				}';
		break;
		case 'get_list':
			$o=null;
			$sql = 'select 
			
				   b.air_id as id,
				   c.kode_rekening || \' - \' || c.nama_rekening as rekening,
				   b.lokasi,
				   b.jumlah,
				   b.dasar_tarif as tarif,
				   b.dasar_pengenaan as pengenaan,
				   b.persen_tarif as persen,
				   b.nominal as pajak

				   from pendataan_spt a 
				   join pendataan_air b on a.pendataan_id=b.pendataan_id
				   join rekening_kode c on c.id=b.id_rekening
				   
				   where a.pendataan_id='.quote_smart($_REQUEST['id']).' order by b.air_id ';
				   
			$o->page = 1; 
			$o->total = 1;
			$o->records = 1;				   
			$i=0; $jumlah=0; $pajak=0;
			$result = gcms_query($sql);
			
			while($row = gcms_fetch_row($result)){ 
				$o->rows[$i]['id']=$row[0];
				$o->rows[$i]['cell']=$row;
				$i++; $jumlah+=$row['3']; $pajak+=$row['7'];
			}
			$o->userdata['lokasi'] = 'Total'; 
			$o->userdata['jumlah'] = $jumlah;
			$o->userdata['pajak'] = $pajak;
			print json_encode($o);
			
		break;
		case 'update':
			echo 'update';
		break;
		case 'delete':
			echo 'delete';
		break;
		default:
			echo '{"page":"1","total":0,"records":null}';
		
	}

}
?>