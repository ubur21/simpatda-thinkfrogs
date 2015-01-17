<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='pendaftaran_bu'){

	$crudColumns =  array(
		'id'=>'spt_id'
		,'pemungutan'=>'pemungutan'
		,'spt_no'=>'spt_no'
		,'npw_no'=>'npw_no'
		,'npw_nama'=>'npw_nama'
		);
		
	$ID='spt_';
	
	$crudTableName = 'spt';
	
	//include 'jqGridCrud.php';
	include $expath.'handler_pendaftaran_bu.php';
	
}
elseif($_REQUEST['sender']=='get-spt'){
	
		$qy = 
		  ' SELECT 
			a.spt_no,
			a.pendaftaran_id,
			a.jenis_pungutan,
			a.kode_rekening,
			a.tgl_kirim,
			a.tgl_kembali,
			a.penerima_nama,
			a.penerima_alamat,
			a.memo,
			b.npwp,
			b.id_pemohon,
			c.kode_rekening,
			c.nama_rekening,
			d.nama as nama_pemohon,
			d.alamat,
			e.lurah_nama,
			f.camat_nama
			FROM spt a
			JOIN pendaftaran b on b.pendaftaran_id=a.pendaftaran_id and b.objek_pdrd=\'PRIBADI\'
			join rekening_kode c on c.kode_rekening=a.kode_rekening
			join pemohon d on d.pemohon_id=b.id_pemohon
			join kelurahan e on e.lurah_id=d.id_desa
			join kecamatan f on f.camat_id=e.lurah_kecamatan
			WHERE a.spt_id='.quote_smart($_REQUEST['val']).
		  ' union
		    SELECT 
			a.spt_no,
			a.pendaftaran_id,
			a.jenis_pungutan,
			a.kode_rekening,
			a.tgl_kirim,
			a.tgl_kembali,
			a.penerima_nama,
			a.penerima_alamat,
			a.memo,
			b.npwp,
			b.id_pemohon,
			c.kode_rekening,
			c.nama_rekening,
			d.nama as nama_pemohon,
			d.alamat,
			e.lurah_nama,
			f.camat_nama
			FROM spt a
			JOIN pendaftaran b on b.pendaftaran_id=a.pendaftaran_id and b.objek_pdrd=\'BU\'
			join rekening_kode c on c.kode_rekening=a.kode_rekening
			join badan_usaha d on d.id=b.id_pemohon
			join kelurahan e on e.lurah_id=d.badan_id_desa
			join kecamatan f on f.camat_id=e.lurah_kecamatan
			WHERE a.spt_id='.quote_smart($_REQUEST['val']);
		   
	$data = gcms_query($qy);
	$rs = gcms_fetch_object($data);
		
	$wp_wr_jenis = substr($rs->npwp,0,1);
	$wp_wr_gol = substr($rs->npwp,1,1);
	$wp_wr_no_urut = substr($rs->npwp,2,5);
	$wp_wr_kd_camat = substr($rs->npwp,7,2);
	$wp_wr_kd_lurah = substr($rs->npwp,9,2);
	
	$spt_no = sprintf('%05d',$rs->spt_no);
	
	$arr = array(
			"nomor"=>$spt_no,
			"jenis_pungutan"=>$rs->jenis_pungutan,
			"pemohon"=>$rs->pendaftaran_id,
			
			"wp_wr_jenis"=>$wp_wr_jenis,
			"wp_wr_gol"=>$wp_wr_gol,
			"wp_wr_no_urut"=>$wp_wr_no_urut,
			"wp_wr_kd_camat"=>$wp_wr_kd_camat,
			"wp_wr_kd_lurah"=>$wp_wr_kd_lurah,

			"nama_pemohon"=>$rs->nama_pemohon,
			"alamat"=>$rs->alamat,
			
			"kecamatan"=>$rs->camat_nama,
			"kelurahan"=>$rs->lurah_nama,
		
			"tgl_kirim"=>formatDate($rs->tgl_kirim),
			"tgl_kembali"=>formatDate($rs->tgl_kembali),

			"kode_rekening"=>$rs->kode_rekening,
			"nama_rekening"=>$rs->nama_rekening,
			"memo"=>$rs->memo
			
		);
	
	echo json_encode($arr);	

}

?>