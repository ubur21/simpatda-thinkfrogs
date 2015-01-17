<?php

include "./../../config.php";
include "./../../lib.php";

if($_REQUEST['old_val']!=''){
	if($_REQUEST['action']==''){
		$nomor = setNomorPendaftaran($_REQUEST['type'],$_REQUEST['obj']);
	}else{
		$nomor = $_REQUEST['old_val'];
	}
}else{
	$nomor = '000000';
}
?>
document.getElementById('nomor').value='<?=$nomor?>';