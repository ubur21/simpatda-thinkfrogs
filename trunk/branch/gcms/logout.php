<?php
/* === file ini hanya untuk logout dan redirect ke halaman depan ==================== */
require_once('global.php');

/* eksekusi logout procedure */
b_logout();

header("Location: ."); 
?>