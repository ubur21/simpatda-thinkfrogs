<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";


if($_REQUEST['sender']=='default'){
	$o=null;
	//$cr = gcms_query("select penetapan_pr_id from teguran_pr");
	//while($x=gcms_fetch_object($cr)){
		$sql = "select
			   a.PENETAPAN_PR_ID as id,
						a.NO_PENETAPAN,
						p.npwp,
						bu.nama,
						a.TGL_PENETAPAN,
						a.TGL_SETOR,
						b.NOMINAL
						
						from penetapan_pr a
						join penetapan_pr_content b on a.PENETAPAN_PR_ID=b.PENETAPAN_PR_ID
						join pendataan_spt c on c.PENDATAAN_ID=b.PENDATAAN_ID
						join pendaftaran p on c.pendaftaran_id = p.pendaftaran_id
						left join badan_usaha bu on p.id_pemohon = bu.id
						
						join REF_JENIS_PAJAK_RETRIBUSI d on d.KODE=c.JENIS_PENDATAAN
				where a.nominal_penetapan > 0
			   ORDER BY a.no_penetapan desc";
			   
		$o->page = 1; 
		$o->total = 1;
		$o->records = 1;				   
		$i=0; //$jumlah=0; $pajak=0;
		$result = gcms_query($sql);
		//echo $sql;
		while($row = gcms_fetch_row($result)){ 
			$o->rows[$i]['id']=$row[0];
			$data = $row;
			$o->rows[$i]['cell']=$data;
			$i++; //$jumlah+=$row['3']; $pajak+=$row['7'];
		
		}
		//"row"=>$i;
		/*$o->userdata['lokasi'] = 'Total'; 
		$o->userdata['jumlah'] = $jumlah;
		$o->userdata['pajak'] = $pajak;*/
		
		print json_encode($o);
	//}	
}
if($_REQUEST['sender']=="get_DataFormTeguran"){
	if(!empty($_REQUEST['val'])){
		$sql = "select 
		   a.PENETAPAN_PR_ID as id,
					a.NO_PENETAPAN,
					p.npwp,
					bu.nama,
					a.TGL_PENETAPAN,
					a.TGL_SETOR,
					b.NOMINAL,
					bu.badan_id_desa,k.lurah_kecamatan,
					p.no_pendaftaran,p.jenis_pendaftaran,
					s.spt_no,s.spt_id,s.tgl_kembali
					from penetapan_pr a
					left join penetapan_pr_content b on a.PENETAPAN_PR_ID=b.PENETAPAN_PR_ID
					left join pendataan_spt c on c.PENDATAAN_ID=b.PENDATAAN_ID
					left join spt s on c.spt_id = s.spt_id
					left join pendaftaran p on c.pendaftaran_id = p.pendaftaran_id
					left join badan_usaha bu on p.id_pemohon = bu.id
					
					left join kelurahan k on bu.badan_id_desa = k.lurah_id
					left join kecamatan kc on k.lurah_kecamatan = kc.camat_id
					join REF_JENIS_PAJAK_RETRIBUSI d on d.KODE=c.JENIS_PENDATAAN
			where a.PENETAPAN_PR_ID ='".$_REQUEST['val']."'
		   ORDER BY a.no_penetapan desc";
		
		$data = gcms_query($sql);
		$x=1;
		$rs = gcms_fetch_object($data);	
		$x++;
		if($rs->jenis_pendaftaran=="PAJAK"){
			$Kode = "PAJAK";
		}else{
			$Kode = "RETRIBUSI";
		}
		if($rs->tgl_kembali ==NULL || $rs->tgl_kembali =="" || $rs->tgl_kembali =="00.00.0000"){
			$Tgl = "";
		}else{
			$Tgl =$rs->tgl_kembali; 
		}
		$arr = array(
			"kode_npwp"=>$Kode,
			"npwp1"=>$rs->npwp,
			"npwp2"=>sprintf("%02d",$rs->lurah_kecamatan),
			"npwp3"=>sprintf("%02d",$rs->badan_id_desa),
			"npwp4"=>$rs->no_pendaftaran,
			"periode_spt"=>date("Y",strtotime($Tgl)),
			"spt_no"=>$rs->spt_no,
			"spt_id"=>$rs->spt_id,
			"tanggal_penetapan"=>date("d/m/Y",strtotime($rs->tgl_penetapan)),
			"tanggal_jatuh_tempo"=>date("d/m/Y",strtotime($rs->tgl_setor)),
			"nominal"=>$rs->nominal,
			"nama"=>$rs->nama,
			"id_pr"=>$rs->id
		);
		//echo json_encode($arr);	
	}elseif(!empty($_REQUEST['edit'])){
		$sql="select t.teguran_pr_id as id,t.teguran_pr_no as nomor,t.tanggal_teguran,
			t.keterangan,pp.tgl_penetapan,pp.tgl_setor,p.npwp,kl.lurah_kecamatan,bu.badan_id_desa,
			p.no_pendaftaran,p.jenis_pendaftaran,s.spt_no,s.spt_id,s.tgl_kembali,pp.penetapan_pr_id,
			t.badan_id,
			bu.nama AS nama_badan_usaha,pj.nama as nama_petugas,pj.pejabat_id AS petugas_id,t.nominal AS total_tunggakan
			from teguran_pr t 
			left join penetapan_pr pp on t.penetapan_pr_id = pp.penetapan_pr_id
			left join pejabat pj on t.fk_teguran_id = pj.pejabat_id
			left join penetapan_pr_content pc on pp.penetapan_pr_id = pc.penetapan_pr_id
			left join pendataan_spt ps on pc.pendataan_id = ps.pendataan_id
			left join pendaftaran p on ps.pendaftaran_id = p.pendaftaran_id
			left join badan_usaha bu on t.badan_id = bu.id
			left join pemohon ph on p.id_pemohon = ph.pemohon_id
			left join kelurahan kl on ph.id_desa = kl.lurah_id
			left join spt s on p.pendaftaran_id = s.pendaftaran_id
			where t.teguran_pr_id ='".$_REQUEST['edit']."'
			";
			$data = gcms_query($sql);
		$x=1;
		$rs = gcms_fetch_object($data);	
		$x++;
		if($rs->jenis_pendaftaran=="PAJAK"){
			$Kode = "PAJAK";
		}else{
			$Kode = "RETRIBUSI";
		}
		if($rs->tgl_kembali ==NULL || $rs->tgl_kembali =="" || $rs->tgl_kembali =="00.00.0000"){
			$Tgl = "";
		}else{
			$Tgl =$rs->tgl_kembali; 
		}
		$arr = array(
			"kode_npwp"=>$Kode,
			"nomor"=>sprintf("%06d",$rs->nomor),
			"tgl_tagih"=>date("d/m/Y",strtotime($rs->tanggal_teguran)),
			"keterangan"=>showMultiLine($rs->keterangan),
			"npwp1"=>$rs->npwp,
			"npwp2"=>sprintf("%02d",$rs->lurah_kecamatan),
			"npwp3"=>sprintf("%02d",$rs->badan_id_desa),
			"npwp4"=>$rs->no_pendaftaran,
			"periode_spt"=>date("Y",strtotime($Tgl)),
			"spt_no"=>$rs->spt_no,
			"spt_id"=>$rs->spt_id,
			"tanggal_penetapan"=>date("d/m/Y",strtotime($rs->tgl_penetapan)),
			"tanggal_jatuh_tempo"=>date("d/m/Y",strtotime($rs->tgl_setor)),
			"nominal"=>$rs->total_tunggakan,
			"nama"=>$rs->nama_badan_usaha,
			"nama_petugas"=>$rs->nama_petugas,
			"petugas_id"=>$rs->petugas_id,
			"badan_id"=>$rs->badan_id,
			"id_pr"=>$rs->penetapan_pr_id
		);
	}
	echo json_encode($arr);	
}
if($_REQUEST['sender']=='daftarnpwp'){
	$o=null;
	$sql = 'select
		   a.PENETAPAN_PR_ID as id,
					a.NO_PENETAPAN,
					p.npwp,
					bu.nama,
					a.TGL_PENETAPAN,
					a.TGL_SETOR,
					b.NOMINAL
					
					from penetapan_pr a
					left join penetapan_pr_content b on a.PENETAPAN_PR_ID=b.PENETAPAN_PR_ID
					left join pendataan_spt c on c.PENDATAAN_ID=b.PENDATAAN_ID
					left join pendaftaran p on c.pendaftaran_id = p.pendaftaran_id
					left join badan_usaha bu on p.id_pemohon = bu.id
					left join pemohon ph on p.id_pemohon = ph.pemohon_id
					left join REF_JENIS_PAJAK_RETRIBUSI d on d.KODE=c.JENIS_PENDATAAN
					where a.nominal_penetapan >0
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
if($_REQUEST['sender']=="daftar_teguran"){
	$o=null;
	$sql="select t.teguran_pr_id as id,
			p.npwp,bu.nama,pj.nama as nama_petugas,t.nominal
			from teguran_pr t 
			left join penetapan_pr pp on t.penetapan_pr_id = pp.penetapan_pr_id
			left join pejabat pj on t.fk_teguran_id = pj.pejabat_id
			left join penetapan_pr_content pc on pp.penetapan_pr_id = pc.penetapan_pr_id
			left join pendataan_spt ps on pc.pendataan_id = ps.pendataan_id
			left join pendaftaran p on ps.pendaftaran_id = p.pendaftaran_id
			left join badan_usaha bu on t.badan_id = bu.id
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
?>