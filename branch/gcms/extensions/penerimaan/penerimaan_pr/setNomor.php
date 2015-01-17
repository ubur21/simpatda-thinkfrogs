<?php

include "./../../../config.php";
include "./../../../lib.php";

$nomax = getNoKohir();

?>
document.getElementById('nomor_reg').value='<?=sprintf('%05d',$nomax)?>';