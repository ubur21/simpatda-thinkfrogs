<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=="daftar_petugas"){
	$o=null;
		$sql ="select p.pejabat_id,p.nip,p.nama,g.nama AS golongan,pk.nama AS pangkat,j.nama AS jabatan
				from pejabat p
				left join pangkat pk on p.pangkat_id = pk.id
				left join golongan g on p.golongan_id = g.id
				left join jabatan j on p.jabatan_id = j.id
				";
	$o->page = 1; 
	$o->total = 1;
	$o->records = 1;				   
	$i=0;
	$result = gcms_query($sql);
	
	while($row = gcms_fetch_row($result)){ 
		$o->rows[$i]['id']=$row[0];
		$data = $row;
		$o->rows[$i]['cell']=$data;
		$i++;
	
	}
	print json_encode($o);
}
if($_REQUEST['sender']=="daftar_rekening"){
	$o=null;
	$sql ="select rk.id,rk.kode_rekening,rk.nama_rekening,rk.tarif_dasar,rk.persen_tarif
			from rekening_kode rk
			where rk.objek !=''
			";
	$o->page = 1; 
	$o->total = 1;
	$o->records = 1;				   
	$i=0;
	$result = gcms_query($sql);
	
	while($row = gcms_fetch_row($result)){ 
		$o->rows[$i]['id']=$row[0];
		$data = $row;
		$o->rows[$i]['cell']=$data;
		$i++;
	
	}
	print json_encode($o);
}
if($_REQUEST['sender']=='daftarnpwp'){
	$o=null;
	$sql = 'select
		   tp.TEGURAN_PR_ID as id,
					a.NO_PENETAPAN,
					p.npwp,
					bu.nama,
					a.TGL_PENETAPAN,
					a.TGL_SETOR,
					b.NOMINAL
					
					from teguran_pr tp
					left join penetapan_pr a on tp.penetapan_pr_id = a.penetapan_pr_id
					left join penetapan_pr_content b on a.PENETAPAN_PR_ID=b.PENETAPAN_PR_ID
					left join pendataan_spt c on c.PENDATAAN_ID=b.PENDATAAN_ID
					left join pendaftaran p on c.pendaftaran_id = p.pendaftaran_id
					left join badan_usaha bu on p.id_pemohon = bu.id
					left join pemohon ph on p.id_pemohon = ph.pemohon_id
					left join REF_JENIS_PAJAK_RETRIBUSI d on d.KODE=c.JENIS_PENDATAAN
					
		   ORDER BY a.no_penetapan desc';
		   
	$o->page = 1; 
	$o->total = 1;
	$o->records = 1;				   
	$i=0;
	$result = gcms_query($sql);
	
	while($row = gcms_fetch_row($result)){ 
		$o->rows[$i]['id']=$row[0];
		$data = $row;
		$o->rows[$i]['cell']=$data;
		$i++;
	
	}
	print json_encode($o);
}
if($_REQUEST['sender']=='daftarnpwp2'){
	$o=null;
	$sql = 'select
		   tp.TEGURAN_PR_ID as id,
					a.NO_PENETAPAN,
					p.npwp,
					bu.nama,
					bu.alamat,
					a.TGL_PENETAPAN,
					a.TGL_SETOR,
					
					tp.NOMINAL,b.NOMINAL
					
					from teguran_pr tp
					left join penetapan_pr a on tp.penetapan_pr_id = a.penetapan_pr_id
					left join penetapan_pr_content b on a.PENETAPAN_PR_ID=b.PENETAPAN_PR_ID
					left join pendataan_spt c on c.PENDATAAN_ID=b.PENDATAAN_ID
					left join pendaftaran p on c.pendaftaran_id = p.pendaftaran_id
					left join badan_usaha bu on p.id_pemohon = bu.id
					left join pemohon ph on p.id_pemohon = ph.pemohon_id
					left join REF_JENIS_PAJAK_RETRIBUSI d on d.KODE=c.JENIS_PENDATAAN
					
		   ORDER BY a.no_penetapan desc';
		   
	$o->page = 1; 
	$o->total = 1;
	$o->records = 1;				   
	$i=0;
	$result = gcms_query($sql);
	
	while($row = gcms_fetch_row($result)){ 
		$o->rows[$i]['id']=$row[0];
		$data = $row;
		//$data[7] = $row[1];
		//$data[8] = $row[8];
		$o->rows[$i]['cell']=$data;
		$i++;
	
	}
	print json_encode($o);
}
?>