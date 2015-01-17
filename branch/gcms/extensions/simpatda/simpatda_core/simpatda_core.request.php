<?php
include('config.php');
include('global.php');

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

if($_REQUEST['sender']=='kategori'){

	$crudColumns =  array(
		'id'=>'id'
		,'kode'=>'tipe_kategori'
		,'nama'=>'nama_kategori'
		,'saldo'=>'saldo_normal'
		);
		
	$crudTableName = 'rekening_kategori';

	include 'jqGridCrud.php';
	
}
elseif($_REQUEST['sender']=='getiperekening'){

	if($_REQUEST['val']!=''){
		$kode = b_fetch('select id from rekening_kategori where tipe_kategori='.quote_smart($_REQUEST['val']));	
	}else{
		$kode = b_fetch('select tipe_kategori from rekening_kategori where id='.quote_smart($_REQUEST['id']));		
	}

	echo $kode;
	
}
elseif($_REQUEST['sender']=='getkoderekening'){

	/*if($_REQUEST['val']!=''){
		$kode = b_fetch('select id from rekening_kategori where tipe_kategori='.quote_smart($_REQUEST['val']));	
	}else{
		$kode = b_fetch('select tipe_kategori from rekening_kategori where id='.quote_smart($_REQUEST['id']));		
	}*/

	echo $_REQUEST['id'];
	
}
elseif($_REQUEST['sender']=='rekening'){

	$crudColumns =  array(
		'id'=>'id'
		,'tipe'=>'tipe'
		,'kelompok'=>'kelompok'
		,'jenis'=>'jenis'
		,'objek'=>'objek'
		,'rincian'=>'rincian'
		,'sub1'=>'sub1'
		,'sub2'=>'sub2'
		,'sub3'=>'sub3'
		,'kode'=>'kode_rekening'
		,'nama'=>'nama_rekening'
		,'kategori'=>'id_kategori'
	);
		
	$crudFK='id';
	
	$crudTableName = 'rekening_kode';
	
	$table1 = 'rekening_kode';
	$pk_table1 = 'id';
	
	$table2 = 'rekening_kategori';
	$pk_table2 = 'id';

	/*		echo 
				'{
					"page":"1",
					"total":0,
					"records":0,
					"rows":[
						{"id":0,"cell":[0,"","","","","","","","","",""]},
					]
				}';
	*/			
	include $expath.'handler_rekening_kode.php';
	

}
elseif($_REQUEST['sender']=='set_list'){
	$qy = 'select * from rekening_kategori ';
	
	$data = gcms_query($qy); $val='';
	
	while($rs = gcms_fetch_object($data)){
		$val.="'$rs->id':'$rs->nama_kategori',";
	}
	echo $val;
	
}
elseif($_REQUEST['sender']=='pilih-rekening'){

	$crudColumns =  array(
		'id'=>'id'
		,'kode'=>'kode_rekening'
		,'nama'=>'nama_rekening'
	);
	
	$crudTableName = 'rekening_kode';
	include 'jqGridCrud.php';
	
}elseif($_REQUEST['sender']=='set_form'){

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