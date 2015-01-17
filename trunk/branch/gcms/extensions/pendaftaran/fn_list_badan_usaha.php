<?php

include "./../../config.php";
include "./../../lib.php";

$q = strtolower($_GET["q"]);
if (!$q) return;

$qy="select id,badan_nama from badan_usaha where lower(badan_nama) like ".quote_smart($_GET["q"].'%')." order by badan_nama ";

$data = gcms_query($qy);

if(!gcms_is_empty($data)){
	while($rs = gcms_fetch_object($data)){
		$items[$rs->badan_nama]=$rs->id;
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