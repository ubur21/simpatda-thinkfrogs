<?php

include "./../../config.php";
include "./../../lib.php";

if(!empty($_GET['Npwp'])){
 
 	$cr="select 
		   tp.TEGURAN_PR_ID as id,
					a.NO_PENETAPAN,
					p.npwp,
					bu.nama,bu.id as id_badan,
					a.TGL_PENETAPAN,
					a.TGL_SETOR,
					b.NOMINAL,
					bu.badan_id_desa,k.lurah_kecamatan,
					p.no_pendaftaran,p.jenis_pendaftaran,
					s.spt_no,s.spt_id,s.tgl_kembali
					from teguran_pr tp 
					left join penetapan_pr a on tp.penetapan_pr_id = a.penetapan_pr_id
					left join penetapan_pr_content b on a.PENETAPAN_PR_ID=b.PENETAPAN_PR_ID
					left join pendataan_spt c on c.PENDATAAN_ID=b.PENDATAAN_ID
					left join spt s on c.spt_id = s.spt_id
					left join pendaftaran p on c.pendaftaran_id = p.pendaftaran_id
					left join badan_usaha bu on p.id_pemohon = bu.id
					
					left join kelurahan k on bu.badan_id_desa = k.lurah_id
					left join kecamatan kc on k.lurah_kecamatan = kc.camat_id
					join REF_JENIS_PAJAK_RETRIBUSI d on d.KODE=c.JENIS_PENDATAAN
			where tp.TEGURAN_PR_ID ='".$_GET['Npwp']."'";
	$xx=gcms_query($cr);
	$row=gcms_fetch_object($xx);
	if($row->tgl_kembali=="" || $row->tgl_kembali==NULL){
		$TglAkhir = "";
	}else{
		$TglAkhir = date("Y",strtotime($row->tgl_kembali));
	}
	if($row->jenis_pendaftaran =="PAJAK"){
		$jenis = "PAJAK";
	}else{
		$jenis = "RETRIBUSI";
	}
	if($_GET['Kode']==1){
	?>
	
		document.getElementById('kode_npwp2').value='<?=$jenis?>';
		document.getElementById('npwpd_npwrd21').value='<?=$row->npwp?>';
		document.getElementById('npwpd_npwrd22').value='<?=$row->no_pendaftaran?>';
		document.getElementById('npwpd_npwrd23').value='<?=sprintf("%02d",$row->lurah_kecamatan)?>';
		document.getElementById('npwpd_npwrd24').value='<?=sprintf("%02d",$row->badan_id_desa)?>';
		
	<?
	}else{
	?>
		document.getElementById('kode_npwp').value='<?=$jenis?>';
		document.getElementById('npwpd_npwrd1').value='<?=$row->npwp?>';
		document.getElementById('npwpd_npwrd2').value='<?=$row->no_pendaftaran?>';
		document.getElementById('npwpd_npwrd3').value='<?=sprintf("%02d",$row->lurah_kecamatan)?>';
		document.getElementById('npwpd_npwrd4').value='<?=sprintf("%02d",$row->badan_id_desa)?>';
		
	<?
	}
	
}
if(!empty($_GET['Petugas'])){
	$sql ="select p.pejabat_id,p.nip,p.nama,g.nama AS golongan,pk.nama AS pangkat,j.nama AS jabatan
				from pejabat p
				left join pangkat pk on p.pangkat_id = pk.id
				left join golongan g on p.golongan_id = g.id
				left join jabatan j on p.jabatan_id = j.id
				where p.pejabat_id ='".$_GET['Petugas']."'
				";
	$xx=gcms_query($sql);
	$row=gcms_fetch_object($xx);
	?>
	document.getElementById('petugas_id').value='<?=sprintf("%03d",$row->pejabat_id)?>';
	document.getElementById('nama_petugas').value='<?=$row->nama?>';
	document.getElementById('jabatan').value='<?=$row->jabatan?>';
	document.getElementById('nip').value='<?=$row->nip?>';
	<?
}
if(!empty($_REQUEST['Rekening'])){
}