<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='setFormOffice'){

	if($_REQUEST['kohir']!=''){

		$qy = 'select
				a.no_penetapan,
				a.nominal_penetapan,
				c.pendaftaran_id,
				c.pendataan_id,
				c.pemohon_id,
				c.npwp,
				c.nama,
				c.alamat,
				c.kelurahan,
				c.kecamatan
				from penetapan_pr a
				join penetapan_pr_content b on a.penetapan_pr_id=b.penetapan_pr_id
				join v_pendataan c on c.pendataan_id=b.pendataan_id
				where a.penetapan_pr_id='.quote_smart($_REQUEST['kohir']).' and c.pendaftaran_id='.quote_smart($_REQUEST['id']);
			
	}else{
	
		$qy_cek = 
				'select
				count(*)
				from penetapan_pr a
				join penetapan_pr_content b on a.penetapan_pr_id=b.penetapan_pr_id
				join v_pendataan c on c.pendataan_id=b.pendataan_id
				where a.penetapan_pr_id='.quote_smart($_REQUEST['id']);
				
		$n = b_fetch($qy_cek);
		
		if($n>1){
		
			$qy = 'select
					a.no_penetapan
					from penetapan_pr a
					where a.penetapan_pr_id='.quote_smart($_REQUEST['id']);
		
		}else{

			$qy = 'select
					a.no_penetapan,
					a.nominal_penetapan,
					c.pendaftaran_id,
					c.pendataan_id,
					c.pemohon_id,
					c.npwp,
					c.nama,
					c.alamat,
					c.kelurahan,
					c.kecamatan
					from penetapan_pr a
					join penetapan_pr_content b on a.penetapan_pr_id=b.penetapan_pr_id
					join v_pendataan c on c.pendataan_id=b.pendataan_id
					where a.penetapan_pr_id='.quote_smart($_REQUEST['id']);
					
		}
	}
							
	$result = gcms_query($qy);
	$rs = gcms_fetch_object($result);
	$arr = array(
			'no_kohir'=>$rs->no_penetapan,
			'nominal_pajak'=>$rs->nominal_penetapan,
			'no_pokok'=>$rs->npwp,
			'id_npwp'=>$rs->pendaftaran_id,
			'id_spt'=>$rs->pendataan_id,
			'nama_pemohon'=>$rs->nama,
			'id_pemohon'=>$rs->pemohon_id,
			'alamat'=>$rs->alamat,
			'kecamatan'=>$rs->kecamatan,
			'kelurahan'=>$rs->kelurahan			
		);
		
	echo json_encode($arr);
	
}
elseif($_REQUEST['sender']=='setFormSelf'){

	$qy = 'select
			a.pendataan_no as no_kohir,
			a.nominal as nominal_pajak,
			a.pendaftaran_id,
			a.pendataan_id,
			a.pemohon_id,
			a.npwp,
			a.nama,
			a.alamat,
			a.kelurahan,
			a.kecamatan
			from v_pendataan a
			where a.pendataan_id='.quote_smart($_REQUEST['id']);
			
	$result = gcms_query($qy);
	$rs = gcms_fetch_object($result);
	$arr = array(
			'no_kohir'=>$rs->no_kohir,
			'nominal_pajak'=>$rs->nominal_pajak,
			'no_pokok'=>$rs->npwp,
			'id_npwp'=>$rs->pendaftaran_id,
			'id_spt'=>$rs->pendataan_id,
			'nama_pemohon'=>$rs->nama,
			'id_pemohon'=>$rs->pemohon_id,
			'alamat'=>$rs->alamat,
			'kecamatan'=>$rs->kecamatan,
			'kelurahan'=>$rs->kelurahan			
		);
		
	echo json_encode($arr);
	
}
elseif($_REQUEST['sender']=='list_npwp'){

	$crudColumns =  array(
						'id'=>'id',
						'nama'=>'nama',
						'npwp'=>'npwp'
					);
	
	$crudTableName = 'penetapan_pr';
	
	include $expath.'handler_list_npwp.php';
				
}
elseif($_REQUEST['sender']=='get_tgl_setor'){

	$tempo = b_fetch('select jatem_bayar from ref_jatuh_tempo');
	$tgl_setor = getExpired($_REQUEST['tgl'],$tempo);
	echo $tgl_setor;
	
}elseif($_REQUEST['sender']=='set_nomor_office'){
	
	$no_bayar = getNoPenerimaan('OFFICE',$_REQUEST['tgl']);
	
	echo sprintf('%05d',$no_bayar);
	
}elseif($_REQUEST['sender']=='set_nomor_self'){
	
	$no_bayar = getNoPenerimaan('SELF',$_REQUEST['tgl']);
	
	echo sprintf('%05d',$no_bayar);
	
}
elseif($_REQUEST['sender']=='daftar_office'){

	$crudColumns = array(
		'id'=>'penerimaan_pr_id',
		'no_bukti'=>'penerimaan_pr_no',
		'nominal'=>'nominal',
		'tgl_bayar'=>'tgl_penerimaan',
		'no_kohir'=>'no_penetapan',
		'jenis'=>'jenis',
		'keterangan'=>'keterangan',
		'nama_skpd'=>'nama_skpd'
	);
	
	include $expath.'handler_daftar_penerimaan_office.php';
}
elseif($_REQUEST['sender']=='daftar_self'){

	$crudColumns = array(
		'id'=>'penerimaan_pr_id',
		'no_bukti'=>'penerimaan_pr_no',
		'nominal'=>'nominal',
		'tgl_bayar'=>'tgl_penerimaan',
		'no_kohir'=>'no_penetapan',
		'jenis'=>'jenis',
		'keterangan'=>'keterangan',
		'nama_skpd'=>'nama_skpd'
	);
	
	include $expath.'handler_daftar_penerimaan_self.php';
}
elseif($_REQUEST['sender']=='default'){

	$crudColumns =  array(
		'pendataan_id'=>'pendataan_id'
		,'pendataan_no'=>'pendataan_no'
		,'tgl_entry'=>'tgl_entry'
		,'npwp'=>'npwp'
		,'nama'=>'nama'
		,'jenis_pendataan'=>'jenis_pendataan'
		,'jenis_pungutan'=>'jenis_pungutan'
		,'jenis_pendaftaran'=>'jenis_pendaftaran'
		,'spt_no'=>'spt_no'
		,'spt_tgl'=>'spt_tgl'
		,'nominal'=>'nominal'
		);
		
	$ID='pendataan_';
	
	$crudTableName = 'v_pendataan';
		
	include $expath.'handler_penetapan_pr.php';
		
}

?>