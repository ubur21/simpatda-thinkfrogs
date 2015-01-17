<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if( $_GET['edit'] && $_GET['edit'] == 'umum' ) {
	$sql = "select * from pendataan_spt p
		left join pendaftaran x on x.pendaftaran_id = p.pendaftaran_id
		left join pemohon ph on ph.pemohon_id = x.id_pemohon
		left join kelurahan k on k.lurah_id = ph.id_desa
		left join kecamatan kc on kc.camat_id = k.lurah_kecamatan
		where p.pendataan_id = ".$_REQUEST['id'];
	
	$result = gcms_query( $sql );
	
	$str="[";
	//$str.="{ 'umum': [";
	while( $rows = gcms_fetch_object( $result ) ) {
		$str .="{ ";
		$str .= "\"pendataanid\" : \"".$rows->pendataan_id."\",";
		$str .= "\"noreg\" : \"".sprintf("%06d", $rows->pendataan_no)."\",";
		$str .= "\"tglproses\" : \"".formatDate($rows->tgl_proses,'d-m-Y')."\",";
		$str .= "\"tglentry\" : \"".formatDate($rows->tgl_entry,'d-m-Y')."\",";
		$str .= "\"pungutan\" : \"".$rows->jenis_pungutan."\",";
		$str .= "\"pawal\" : \"".formatDate($rows->periode_awal,'d-m-Y')."\",";
		$str .= "\"pakhir\" : \"".formatDate($rows->periode_akhir,'d-m-Y')."\",";
		$str .= "\"pspt\" : \"".formatDate($rows->periode_akhir,'Y')."\",";
		$str .= "\"nospt\" : \"".$rows->pendataan_no."\",";
		$str .= "\"npwp\" : \"".$rows->npwp."\",";
		$str .= "\"nama\" : \"".$rows->nama."\",";
		$str .= "\"alamat\" : \"".$rows->alamat."\",";
		$str .= "\"kelurahan\" : \"".$rows->lurah_nama."\",";
		$str .= "\"kecamatan\" : \"".$rows->camat_nama."\",";
		
		$str .="},";
	}
	$sql='select a.nominal as total'.
	     ' from PENDATAAN_SPT a'. 
	     ' join pendataan_retrubusi b on b.PENDATAAN_ID = a.PENDATAAN_ID'.
	     ' join REKENING_KODE c on c.ID = b.ID_REKENING'.
	     ' where a.pendataan_id='.$_REQUEST['id'];
	     
	$result=gcms_query($sql);
				
	while($row=gcms_fetch_row($result)) {
		$o->total=$row[0];
	}
	$str.="{'total':'".$o->total."'},";
	//$str.="] }";
	$str.="]";
	
	echo $str;
}
elseif( $_GET['edit'] && $_GET['edit'] == 'detail' ) {
	$sql = "select * from pendataan_retrubusi p
		where p.pendataan_id = ".$_REQUEST['id'];
	
	$result = gcms_query( $sql );
	
	$str="[";
	//$str.="{ 'umum': [";
	if( !$result ) {
		$str.="{}";
	}
	else {
		while( $rows = gcms_fetch_object( $result ) ) {
			$str .="{ ";
			$str .= "\"idretribusi\" : \"".$rows->retribusi_id."\",";
			$str .= "\"idrek\" : \"".$rows->id_rekening."\",";
			$str .= "\"ptarif\" : \"".$rows->persen_tarif."\",";
			$str .= "\"dtarif\" : \"".$rows->dasar_pengenaan."\",";
			//$str .= "\"pawal\" : \"".formatDate($rows->periode_awal,'d-m-Y')."\",";
			//$str .= "\"pakhir\" : \"".formatDate($rows->periode_akhir,'d-m-Y')."\",";
			//$str .= "\"pspt\" : \"".formatDate($rows->periode_akhir,'Y')."\",";
			//$str .= "\"nospt\" : \"".$rows->pendataan_no."\",";
			//$str .= "\"npwp\" : \"".$rows->npwp."\",";
			//$str .= "\"nama\" : \"".$rows->nama."\",";
			$str .= "\"tdasar\" : \"".$rows->dasar_tarif."\",";
			$str .= "\"jumlah\" : \"".$rows->jumlah."\",";
			$str .= "\"lokasi\" : \"".$rows->lokasi."\",";
			
			$str .="},";
		}
	}
	
	//$str.="] }";
	$str.="]";
	
	echo $str;
}
elseif ($_REQUEST['sender']=='daftarnpwp'){
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
elseif($_REQUEST['sender']=='penetapan_PajakRetribusi') {
	$crudColumns =  array(
		//'no'=>''
		'id'=>'pendataan_id'
		,'pendataan_no'=>2
		,'tanggal_proses'=>3
		,'tanggal_entry'=>4
		,'npwp'=>''
		,'nama'=>''
		,'Alamat'=>''
		,'pungutan'=>'jenis_pungutan'
		,'periode_awal'=>'periode_awal'
		,'periode-akhir'=>'periode_akhir'
		,'pajak'=>''
		
	);
		
	$crudTableName = 'PENDATAAN_SPT';
	$crudTableJoin = 'PENDATAAN_RETRUBUSI';
	$ID ="PENDATAAN_";
	$crudTableJoin1 = "PENDAFTARAN";
	
	$crudIdJoin1 = "pendaftaran_id";
	
	include $expath.'jqGridCrudPendataanRetribusi.php';
}
elseif($_REQUEST['sender']=='DataRetribusi') {
	switch($_REQUEST['action']) {
		case 'DetailRetribusiList' :
			$o=null;
			$sql='select \''.quote_smart($_REQUEST['val']).'\' as id,'.
				'c.kode_rekening || \' - \' || c.NAMA_REKENING as rekening,'.
				'b.JUMLAH as jumlah,b.DASAR_TARIF as tarif,'.
				'b.DASAR_PENGENAAN as pengenaan,'.
				'b.nominal as retribusi'.
				' from PENDATAAN_SPT a'. 
				' join PENDATAAN_RETRUBUSI b on b.PENDATAAN_ID = a.PENDATAAN_ID'.
				' join REKENING_KODE c on c.ID = b.ID_REKENING'.
				' where a.pendataan_id='.quote_smart($_REQUEST['val']).' order by b.retribusi_id';
			$o->page=1;
			$o->total=1;
			$o->records=1;
			$i=0;$jumlah=0;$retribusi=0;
			$result=gcms_query($sql);
			while($row=gcms_fetch_row($result)) {
				$o->rows[$i]['id']=$row[0];
				$data=$row;
				$o->rows[$i]['cell']=$data;
				$i++;$jumlah+=$row['2'];$retribusi+=$row['5'];
			}
			$o->userdata['rekening']='Total';
			$o->userdata['jumlah']=$jumlah;
			$o->userdata['pajak']=$retribusi;
			
			print json_encode($o);
		break;
		//default: //do nothing
	}
}
else {
	switch($_REQUEST['action']) {
		case'read':break;
		case 'get_list':
			$o=null;
			
			if($_REQUEST['cari']=='total') {
				$sql='select '.
				//'SUM( (b.PERSEN_TARIF * b.DASAR_PENGENAAN * 0.01) ) as total'.
				' a.nominal as total'.
				' from PENDATAAN_SPT a'. 
				' join pendataan_retrubusi b on b.PENDATAAN_ID = a.PENDATAAN_ID'.
				' join REKENING_KODE c on c.ID = b.ID_REKENING'.
				' where a.pendataan_id='.quote_smart($_REQUEST['id']);
				
				$result=gcms_query($sql);
				
				while($row=gcms_fetch_row($result)) {
					$o->total=$row[0];
				}
				
			}
			else {
				$sql='select b.retribusi_id as id,'.
				'c.kode_rekening || \' - \' || c.NAMA_REKENING as rekening,'.
				'b.JUMLAH as jumlah,b.DASAR_TARIF as tarif,'.
				//'b.DASAR_PENGENAAN as pengenaan,b.PERSEN_TARIF as persen,'.
				'b.DASAR_PENGENAAN as pengenaan,'.
				//'(b.PERSEN_TARIF * b.DASAR_PENGENAAN * 0.01) as pajak'.
				//'(b.JUMLAH * b.DASAR_TARIF * b.DASAR_PENGENAAN) as retribusi'.
				'(b.nominal) as retribusi'.
				' from PENDATAAN_SPT a'. 
				' join pendataan_retrubusi b on b.PENDATAAN_ID = a.PENDATAAN_ID'.
				' join REKENING_KODE c on c.ID = b.ID_REKENING'.
				' where a.pendataan_id='.quote_smart($_REQUEST['id']).' order by b.retribusi_id';
				
				//echo $sql;
				$o->page=1;
				$o->total=1;
				$o->records=1;
				$i=0;$jumlah=0;$pajak=0;
				
				$result=gcms_query($sql);
				
				while($row=gcms_fetch_row($result)) {
					$o->rows[$i]['id']=$row[0];
					$o->rows[$i]['cell']=$row;
					$i++;$jumlah+=$row['2'];$pajak+=$row['6'];
				}
				
				$o->userdata['rekening']='Total';
				$o->userdata['jumlah']=$jumlah;
				$o->userdata['pajak']=$pajak;
			}
			
			print json_encode($o);
			
			break;
		case 'update': break;
		case 'delete': break;
		default:
			//echo 
			//	'{
			//		"page":"1",
			//		"total":0,
			//		"records":0,
			//		"rows":[
			//			{"id":1,"cell":[0,"","","",""]},
			//		]
			//	}';	
	}
	if($_GET['oper']=='edit') {
		if($_POST['oper']=='del') { //store deleted id
			//unset($_SESSION['delete_box']);
			$arr_id = explode(',',$_POST['id']);//
			foreach( $arr_id as $key => $val ) {
				$_SESSION['delete_box']['retribusi_id'][]=$val;
			}
		}
	}
}
?>
