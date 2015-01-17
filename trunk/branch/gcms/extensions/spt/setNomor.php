<?php

include "./../../config.php";
include "./../../lib.php";

$nomax = b_fetch('select max(spt_no) from spt ');
$nomax++;

?>
document.getElementById('nomor').value='<?=sprintf('%05d',$nomax)?>';