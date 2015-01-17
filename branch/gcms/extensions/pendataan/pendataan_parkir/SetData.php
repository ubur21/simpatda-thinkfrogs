<?php

include "./../../../config.php";
include "./../../../lib.php";

if(!empty($_GET['Npwp'])){
	/*
 	$cr="select p.pendaftaran_id,
					p.no_pendaftaran,p.npwp,
					ph.nama,ph.alamat,ps.periode_awal,
					ps.periode_akhir,ps.pendataan_no,ps.pendataan_id,ph.id_desa
					from pendaftaran p left join pemohon ph
						on p.id_pemohon = ph.pemohon_id
						left join pendataan_spt ps on p.pendaftaran_id = ps.pendaftaran_id
					where p.pendaftaran_id ='".$_GET['Npwp']."'";
	*/
	$cr	=	"select p.pendaftaran_id, p.no_pendaftaran,p.npwp, ph.nama,ph.alamat,ps.periode_awal,
			 ps.periode_akhir,ps.pendataan_no,ps.pendataan_id,ph.id_desa,kl.lurah_nama,kc.camat_nama
			from pendaftaran p
			left join pemohon ph on p.id_pemohon = ph.pemohon_id
			left join pendataan_spt ps on p.pendaftaran_id = ps.pendaftaran_id
			left join kelurahan kl on kl.lurah_id = ph.id_desa
			left join kecamatan kc on kc.camat_id = kl.lurah_kecamatan
			where p.pendaftaran_id ='".$_GET['Npwp']."'";
			
	$xx=gcms_query($cr);
	$row=gcms_fetch_object($xx);
	if($row->periode_akhir=="" || $row->periode_akhir==NULL){
	$TglAkhir = "";
	}else{
	$TglAkhir = date("Y",strtotime($row->periode_akhir));
	}
	?>
	$('#npwpd_npwrd1').val('<?=$row->npwp?>');
	$('#nama_wp_wr').val('<?=$row->nama?>');
	$('#alamat').val('<?=$row->alamat?>');
	$('#id_desa').val('<?=$row->id_desa?>');
	$('#kel').val('<?=$row->lurah_nama?>');
	$('#kec').val('<?=$row->camat_nama?>')
	$('#pendaftaran_id').val('<?=$row->pendaftaran_id?>');
	$('#periode_spt').val('<?=$TglAkhir?>');
	$('#nomor_spt').val('<?=$row->pendataan_no?>');
	
	document.getElementById('pendataan_id_hid').value='<?=$row->pendataan_id?>';
	<?
}
if( $_GET['parkir'] ) {
	$query = "SELECT a.id, a.kode_rekening, a.nama_rekening,  a.persen_tarif from rekening_kode a";
	$result = gcms_query( $query );
	?>
	[
	<?php 
	while( $rows = gcms_fetch_object( $result ) ) {
		//membatasi string
		if( strlen( $rows->nama_rekening ) > 25 ) {
			$rows->nama_rekening = substr( $rows->nama_rekening, 0, 24 );
		}
		//convert to huruf kecil
		$nama_rek = strtolower( $rows->nama_rekening );
		?>
		{
		  "idrek" : "<?php echo $rows->id;?>",
		  "koderek" : "<?php echo $rows->kode_rekening." - ".$nama_rek;?>",
		  "persen"  : "<?php echo $rows->persen_tarif;?>" },
		<?php
	}
	?>
	]
	<?php
}
if( $_GET['persentarif'] ) {
	$sql = "SELECT a.PERSEN_TARIF from rekening_kode a where a.ID = ".$_GET['persentarif'];
	$persentarif = b_fetch( $sql );
	
	echo "[{ 'tarif' : '".$persentarif."' }]";
}
if( $_GET['setnomor'] ) {
	$query = "select max(pendataan_no) from pendataan_spt where jenis_pendataan = 'PARKIR';";
	$result = gcms_query( $query );
	$str = "[";
	while( $rows = gcms_fetch_object( $result ) ) {
		$nomor = $rows->max;
		$nomor+=1;
		
		$str .= "{ \"nomor\" : \"".sprintf("%06d",$nomor)."\"},";
	}
	$str .="]";
	
	echo $str;
}
//if( $_GET['edit'] && $_GET['edit'] == 'umum' ) {
//	$sql = "select * from pendataan_spt p
//		left join pendaftaran x on x.pendaftaran_id = p.pendaftaran_id
//		left join pemohon ph on ph.pemohon_id = x.id_pemohon
//		left join kelurahan k on k.lurah_id = ph.id_desa
//		left join kecamatan kc on kc.camat_id = k.lurah_kecamatan
//		where p.pendataan_id = ".$_REQUEST['id'];
//	
//	$result = gcms_query( $sql );
//	
//	$str="[";
//	//$str.="{ 'umum': [";
//	while( $rows = gcms_fetch_object( $result ) ) {
//		$str .="{ ";
//		$str .= "\"pendataanid\" : \"".$rows->pendataan_id."\",";
//		$str .= "\"noreg\" : \"".sprintf("%06d", $rows->pendataan_no)."\",";
//		$str .= "\"tglproses\" : \"".formatDate($rows->tgl_proses,'d-m-Y')."\",";
//		$str .= "\"tglentry\" : \"".formatDate($rows->tgl_entry,'d-m-Y')."\",";
//		$str .= "\"pungutan\" : \"".$rows->jenis_pungutan."\",";
//		$str .= "\"pawal\" : \"".formatDate($rows->periode_awal,'d-m-Y')."\",";
//		$str .= "\"pakhir\" : \"".formatDate($rows->periode_akhir,'d-m-Y')."\",";
//		$str .= "\"pspt\" : \"".formatDate($rows->periode_akhir,'Y')."\",";
//		$str .= "\"nospt\" : \"".$rows->pendataan_no."\",";
//		$str .= "\"npwp\" : \"".$rows->npwp."\",";
//		$str .= "\"nama\" : \"".$rows->nama."\",";
//		$str .= "\"alamat\" : \"".$rows->alamat."\",";
//		$str .= "\"kelurahan\" : \"".$rows->lurah_nama."\",";
//		$str .= "\"kecamatan\" : \"".$rows->camat_nama."\",";
//		
//		$str .="},";
//	}
//	//$str.="] }";
//	$str.="]";
//	
//	echo $str;
//}
//
//if( $_GET['edit'] && $_GET['edit'] == 'detail' ) {
//	$sql = "select * from pendataan_pparkir p
//		where p.pendataan_id = ".$_REQUEST['id'];
//	
//	$result = gcms_query( $sql );
//	
//	$str="[";
//	//$str.="{ 'umum': [";
//	if( !$result ) {
//		$str.="{}";
//	}
//	else {
//		while( $rows = gcms_fetch_object( $result ) ) {
//			$str .="{ ";
//			$str .= "\"idparkir\" : \"".$rows->pparkir_id."\",";
//			$str .= "\"idrek\" : \"".$rows->id_rekening."\",";
//			$str .= "\"ptarif\" : \"".$rows->persen_tarif."\",";
//			$str .= "\"dtarif\" : \"".$rows->dasar_pengenaan."\",";
//			//$str .= "\"pawal\" : \"".formatDate($rows->periode_awal,'d-m-Y')."\",";
//			//$str .= "\"pakhir\" : \"".formatDate($rows->periode_akhir,'d-m-Y')."\",";
//			//$str .= "\"pspt\" : \"".formatDate($rows->periode_akhir,'Y')."\",";
//			//$str .= "\"nospt\" : \"".$rows->pendataan_no."\",";
//			//$str .= "\"npwp\" : \"".$rows->npwp."\",";
//			//$str .= "\"nama\" : \"".$rows->nama."\",";
//			//$str .= "\"alamat\" : \"".$rows->alamat."\",";
//			//$str .= "\"kelurahan\" : \"".$rows->lurah_nama."\",";
//			//$str .= "\"kecamatan\" : \"".$rows->camat_nama."\",";
//			
//			$str .="},";
//		}
//	}
//	
//	//$str.="] }";
//	$str.="]";
//	
//	echo $str;
//}
if( $_GET['tes'] ) {
?>
[
	{
		"nama" : "tes",
		"alamat" : "tes"
	},
	{
		"nama" : "tes2",
		"alamat" : "tes"
	},
]
<?php
}
?>


