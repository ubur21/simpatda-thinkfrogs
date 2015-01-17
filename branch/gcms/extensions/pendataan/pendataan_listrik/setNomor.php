<?php

include "./../../../config.php";
include "./../../../lib.php";

$nomax = b_fetch('select max(pendataan_no) from pendataan_spt ');
$nomax++;

?>
document.getElementById('nomor').value='<?=sprintf('%05d',$nomax)?>';