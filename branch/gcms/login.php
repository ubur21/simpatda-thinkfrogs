<?php
require_once('global.php');

/* eksekusi prosedur login */
b_login($_POST['cuser'],$_POST['cpass']);

header("Location: ."); 
?>