<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='pendaftaran_pemohon'){

	$ID = 'PEMOHON_';

	$crudColumns =  array(
		'no'=>'nomor'
		,'id'=>'pemohon_id'
		,'nama'=>'nama'
		,'ktp'=>'no_ktp'
		,'tempat'=>'tempat_lahir'
		,'tanggal'=>'tanggal_lahir'
		,'pekerjaan'=>'pekerjaan'
		,'alamat'=>'alamat'
		,'rt'=>'rt'
		,'rw'=>'rw'
		,'kodepos'=>'kodepos'
		,'desa'=>'id_desa'
		,'no_telp'=>'no_telp'
		,'no_hp'=>'no_hp'
		);
	
	$crudTableName = 'pemohon';
	
	//include 'jqGridCrud.php';
	include $expath.'handler_pendaftaran_pemohon.php';


}elseif($_REQUEST['sender']=='pendaftaran_badan'){

	//$ID = 'PEMOHON_';
	$crudColumns =  array(
		'id'=>'id'
		,'nama'=>'nama'
		,'badan_tipe'=>'badan_tipe'
		,'alamat'=>'alamat'
		,'rt'=>'rt'
		,'rw'=>'rw'
		,'kodepos'=>'kodepos'
		
		,'badan_id_desa'=>'badan_id_desa'
		,'badan_telp'=>'badan_telp'
		,'badan_fax'=>'badan_fax'
		,'badan_npwp'=>'badan_npwp'

		,'pemilik_nama'=>'pemilik_nama'
		,'pemilik_alamat'=>'pemilik_alamat'
		,'pemilik_rt'=>'pemilik_rt'
		,'pemilik_rw'=>'pemilik_rw'
		,'pemilik_kodepos'=>'pemilik_kodepos'

		,'pemilik_id_desa'=>'pemilik_id_desa'
		,'pemilik_telp'=>'pemilik_telp'
		,'pemilik_hp'=>'pemilik_hp'
		,'pemilik_npwp'=>'pemilik_npwp'

		);
	
	$crudTableName = 'badan_usaha';
	
	//include 'jqGridCrud.php';
	include $expath.'handler_pendaftaran_badan.php';


}elseif($_REQUEST['sender']=='pendaftaran_pribadi'){

	$crudColumns =  array(
		'id'=>'id'
		,'nomor'=>'no_pendaftaran'
		,'tanggal'=>'tanggal_kartu'
		,'nama'=>'nama'
		,'jenis'=>'jenis_pendaftaran'
		,'npwp'=>'npwp'
		);
	
	$crudTableName = 'pendaftaran';
	$ID='pendaftaran_';
	//include 'jqGridCrud.php';
	include $expath.'handler_pendaftaran_pribadi.php';


}elseif($_REQUEST['sender']=='pendaftaran_bu'){

	$crudColumns =  array(
		'id'=>'id'
		,'no_pendaftaran'=>'no_pendaftaran'
		,'tanggal'=>'tanggal_kartu'
		,'nama'=>'nama'
		,'jenis'=>'jenis_pendaftaran'
		,'npwp'=>'npwp'
		);
		
	$ID='pendaftaran_';
	
	$crudTableName = 'pendaftaran';
	
	//include 'jqGridCrud.php';
	include $expath.'handler_pendaftaran_bu.php';
	
}

elseif($_REQUEST['sender']=='status_anggaran'){

	$crudColumns =  array(
		'id'=>'id'
		,'label'=>'status'
		);
		
	$crudTableName = 'anggaran_status';

	include 'jqGridCrud.php';
	include $expath.'handler_pendaftaran_pemohon.php';
	
}elseif($_REQUEST['sender']=='tahun_anggaran'){

	$crudColumns =  array(
		'id'=>'id'
		,'status'=>'id_status'
		,'tahun'=>'tahun1'
	);
		
	$crudFK='id';
	
	$crudTableName = 'anggaran_tahun';
	
	$table1 = 'anggaran_tahun';
	$pk_table1 = 'id';
	
	$table2 = 'anggaran_status';
	$pk_table2 = 'id';

	include $expath.'handler_anggaran_tahun.php';


}
elseif($_REQUEST['sender']=='list-pribadi'){
	$crudColumns = array(
		'id'=>'id'
		,'nomor'=>'nomor'
		,'nama'=>'nama'
		);
		
	$ID='pemohon_';
	
	$crudTableName = 'pemohon';
	
	//include 'jqGridCrud.php';
	include $expath.'handler_pilih_pribadi.php';
}
elseif($_REQUEST['sender']=='list-bu'){
	$crudColumns = array(
		'id'=>'id'
		,'nama'=>'nama'
		);
		
	$ID='pemohon_';
	
	$crudTableName = 'badan_usaha';
	
	//include 'jqGridCrud.php';
	include $expath.'handler_pilih_bu.php';
}

elseif($_REQUEST['sender']=='get-pemohon'){

	$qy = 'select a.no_ktp, a.nama,a.no_telp ,a.alamat, b.lurah_nama, c.camat_nama
	       from 
		   pemohon a 
		   join kelurahan b on b.lurah_id=a.id_desa
		   join kecamatan c on c.camat_id=b.lurah_kecamatan
		   where a.pemohon_id='.quote_smart($_REQUEST['val']);
	$data = gcms_query($qy);
	$rs = gcms_fetch_object($data);

	echo json_encode(array("no_ktp"=>$rs->no_ktp,"nama"=>$rs->nama,"alamat"=>$rs->alamat,"kelurahan"=>$rs->lurah_nama,"kecamatan"=>$rs->camat_nama,"telp"=>$rs->no_telp));
	
}
elseif($_REQUEST['sender']=='get-bu'){

	$qy = 'select 
		   a.nama as badan_nama , 
		   a.alamat as alamat_bu,
		   b.lurah_nama as kelurahan_bu, c.camat_nama as kecamatan_bu,
		   a.badan_telp as telp_bu,
		   a.badan_fax as fax_bu,
		   a.pemilik_nama as nama_pk,
		   a.pemilik_alamat as alamat_pk,
		   (select x.lurah_nama from kelurahan x where x.lurah_id=a.pemilik_id_desa) as kelurahan_pk,
		   (select y.camat_nama from kelurahan x join kecamatan y on x.lurah_kecamatan=y.camat_id where x.lurah_id=a.pemilik_id_desa) as kecamatan_pk,
		   a.pemilik_telp as telp_pk,
		   a.pemilik_hp as hp_pk
	       from 
		   badan_usaha a 
		   join kelurahan b on b.lurah_id=a.badan_id_desa
		   join kecamatan c on c.camat_id=b.lurah_kecamatan
		   where a.id='.quote_smart($_REQUEST['val']);
		   
	$data = gcms_query($qy);
	$rs = gcms_fetch_object($data);

	echo json_encode(
		array(
			"badan_nama"=>$rs->badan_nama,
			"alamat_bu"=>$rs->alamat_bu,
			"kelurahan_bu"=>$rs->kelurahan_bu,
			"kecamatan_bu"=>$rs->kecamatan_bu,
			"telp_bu"=>$rs->telp_bu,
			"fax_bu"=>$rs->fax_bu,
			"nama_pk"=>$rs->nama_pk,
			"alamat_pk"=>$rs->alamat_pk,
			"kelurahan_pk"=>$rs->kelurahan_pk,
			"kecamatan_pk"=>$rs->kecamatan_pk,
			"telp_pk"=>$rs->telp_pk,
			"hp_pk"=>$rs->hp_pk,
		)
	);
	
}

elseif($_REQUEST['sender']=='get-pendaftaran-pribadi'){

	$qy = 'SELECT 
			a.jenis_pendaftaran,
			a.no_pendaftaran, 
			a.no_kartu, 
			a.npwp, 
			a.memo, 
			a.objek_pdrd, 
			a.tanggal_kartu, 
			a.tanggal_terima, 
			a.tanggal_kembali, 
			a.tanggal_kirim, 
			a.tanggal_tutup, 
			b.pemohon_id,
			b.nama,
			b.no_ktp,
			b.alamat,
			b.no_telp,
			c.lurah_nama,
			c.lurah_id,
			d.camat_nama,
			e.id as kode_usaha
			FROM pendaftaran a
			JOIN pemohon b on b.pemohon_id=a.id_pemohon
			JOIN kelurahan c on c.lurah_id=b.id_desa
			JOIN kecamatan d on d.camat_id=c.lurah_id
			JOIN kode_usaha e on e.id=a.kode_usaha
			WHERE a.pendaftaran_id='.quote_smart($_REQUEST['val']);
		   
	$data = gcms_query($qy);
	$rs = gcms_fetch_object($data);
	
	$arr = array(
			"nomor"=>$rs->no_pendaftaran,
			"jenis"=>$rs->jenis_pendaftaran,
			"pemohon"=>$rs->pemohon_id,
			"no_ktp"=>$rs->no_ktp,
			"nama_pemohon"=>$rs->nama,
			"alamat"=>$rs->alamat,
			"kelurahan"=>$rs->lurah_nama,
			"kecamatan"=>$rs->camat_nama,
			"telp"=>$rs->no_telp,
			"kode_usaha"=>$rs->kode_usaha,
			"tanggal_kartu"=>formatDate($rs->tanggal_kartu),
			"tanggal_terima"=>formatDate($rs->tanggal_terima),
			"tanggal_kirim"=>formatDate($rs->tanggal_kirim),
			"tanggal_kembali"=>formatDate($rs->tanggal_kembali),
			"tanggal_tutup"=>formatDate($rs->tanggal_tutup),
			"memo"=>$rs->memo
		);
	echo json_encode($arr);
	
}
elseif($_REQUEST['sender']=='get-pendaftaran-bu'){

	$qy = 'SELECT 
			a.no_pendaftaran as nomor, 
			a.jenis_pendaftaran as jenis,
			a.memo, 
			a.tanggal_kartu, 
			a.tanggal_terima, 
			a.tanggal_kirim, 
			a.tanggal_kembali, 
			a.tanggal_tutup, 
		   (select x.lurah_nama from kelurahan x where x.lurah_id=b.pemilik_id_desa) as kelurahan_pk,
		   (select y.camat_nama from kelurahan x join kecamatan y on x.lurah_kecamatan=y.camat_id where x.lurah_id=b.pemilik_id_desa) as kecamatan_pk,
		    b.pemilik_telp as telp_pk,
			b.pemilik_hp as hp_pk,
			b.id as pemohon,
			b.nama as badan_nama,
			b.alamat as alamat_bu,
			b.badan_telp as telp_bu,
			b.badan_fax as fax_bu,
            b.pemilik_nama as nama_pk,
			b.pemilik_alamat as alamat_pk,
			c.lurah_nama as kecamatan_bu,
			d.camat_nama as kelurahan_bu,
			e.id as kode_usaha
			FROM pendaftaran a
			JOIN badan_usaha b on b.id=a.id_pemohon
			JOIN kelurahan c on c.lurah_id=b.badan_id_desa
			JOIN kecamatan d on d.camat_id=c.lurah_id
			JOIN kode_usaha e on e.id=a.kode_usaha			
			WHERE a.pendaftaran_id='.quote_smart($_REQUEST['val']);
		   
	$data = gcms_query($qy);
	$rs = gcms_fetch_object($data);
	
	$arr = array(
			"nomor"=>$rs->nomor,
			"jenis"=>$rs->jenis,
			"memo"=>$rs->memo,
			"tanggal_kartu"=>formatDate($rs->tanggal_kartu),
			"tanggal_terima"=>formatDate($rs->tanggal_terima),
			"tanggal_kirim"=>formatDate($rs->tanggal_kirim),
			"tanggal_kembali"=>formatDate($rs->tanggal_kembali),
			"tanggal_tutup"=>formatDate($rs->tanggal_tutup),
			"kelurahan_pk"=>$rs->kelurahan_pk,
			"kecamatan_pk"=>$rs->kecamatan_pk,
			"telp_pk"=>$rs->telp_pk,
			"hp_pk"=>$rs->hp_pk,
			"pemohon"=>$rs->pemohon,
			"badan_nama"=>$rs->badan_nama,
			"alamat_bu"=>$rs->alamat_bu,
			"telp_bu"=>$rs->telp_bu,
			"fax_bu"=>$rs->fax_bu,
			"nama_pk"=>$rs->nama_pk,
			"alamat_pk"=>$rs->alamat_pk,
			"kecamatan_bu"=>$rs->kecamatan_bu,
			"kelurahan_bu"=>$rs->kelurahan_bu,
			"kode_usaha"=>$rs->kode_usaha,
		);
	echo json_encode($arr);
	
}
elseif($_REQUEST['sender']=='list-npwp'){

	$crudColumns = array(
		'id'=>'id'
		,'nama'=>'nama'
		,'npwp'=>'npwp'		
		);
		
	$ID='pendaftaran_';
	
	$crudTableName = 'v_daftar_npwpd';
	
	include $expath.'handler_pilih_npwp.php';
	
}
elseif($_REQUEST['sender']=='list-npwr'){
	$crudColumns = array(
		'id'=>'id'
		,'nama'=>'nama'
		,'npwp'=>'npwp'		
		);
		
	$ID='pendaftaran_';
	
	$crudTableName = 'v_daftar_npwrd';
	
	include $expath.'handler_pilih_npwr.php';	
}
else{

	$crudColumns =  array(
		'id'=>'id'
		,'kode'=>'kode_skpd'
		,'nama'=>'nama_skpd'
	);
		
	$crudTableName = 'skpd';

	include 'jqGridCrud.php';

}
?>