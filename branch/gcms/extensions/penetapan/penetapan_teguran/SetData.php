<?php

include "./../../../config.php";
include "./../../../lib.php";

if(!empty($_GET['Npwp'])){
 
 	$cr="select 
		   a.PENETAPAN_PR_ID as id,
					a.NO_PENETAPAN,
					p.npwp,
					bu.nama,bu.id as id_badan,
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
			where a.PENETAPAN_PR_ID ='".$_GET['Npwp']."'";
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
	?>
	document.getElementById('kode_npwp').value='<?=$jenis?>';
	document.getElementById('npwpd_npwrd1').value='<?=$row->npwp?>';
	document.getElementById('npwpd_npwrd2').value='<?=$row->no_pendaftaran?>';
	document.getElementById('npwpd_npwrd3').value='<?=sprintf("%02d",$row->lurah_kecamatan)?>';
	document.getElementById('npwpd_npwrd4').value='<?=sprintf("%02d",$row->badan_id_desa)?>';
	document.getElementById('nama_wp_wr').value='<?=$row->nama?>';
	document.getElementById('SptIdHid').value='<?=$row->spt_id?>';
	
	document.getElementById('Periode').value='<?=$TglAkhir?>';
	document.getElementById('NomorSpt').value='<?=$row->spt_no?>';
	document.getElementById('date_1').value='<?=date("d/m/Y",strtotime($row->tgl_penetapan))?>';
	document.getElementById('date_2').value='<?=date("d/m/Y",strtotime($row->TGL_SETOR))?>';
	document.getElementById('nominal').value='<?=$row->nominal?>';
	document.getElementById('penetapan_pr_id').value='<?=$row->id?>';
	document.getElementById('badan_id').value='<?=$row->id_badan?>';
<?	
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
	document.getElementById('petugas_id').value='<?=$row->pejabat_id?>';
	document.getElementById('nama_petugas').value='<?=$row->nama?>';
	<?
}