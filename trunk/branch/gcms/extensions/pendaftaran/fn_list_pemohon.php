<?php

include "./../../config.php";
include "./../../lib.php";

$q = strtolower($_GET["q"]);
if (!$q) return;

if($_GET['val']=='ktp'){
	$qy="select pemohon_id,no_ktp as xxx from pemohon where lower(no_ktp) like ".quote_smart($_GET["q"].'%')." order by no_ktp ";
}else{
	$qy="select pemohon_id,nama as xxx from pemohon where lower(nama) like ".quote_smart($_GET["q"].'%')." order by nama ";
}

$data = gcms_query($qy);

if(!gcms_is_empty($data)){
	while($rs = gcms_fetch_object($data)){
		$items[$rs->xxx]=$rs->pemohon_id;
	}
	if(count($items)>0){
		foreach ($items as $key=>$value) {
			if (strpos(strtolower($key), $q) !== false) {
				echo "$key|$value\n";
			}
		}
	}
}
?>