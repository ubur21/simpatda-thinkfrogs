<?php
require_once('global.php');

/* request dipanggil pake 'page' atau pake 'mod' ? */
if ($_REQUEST['page']) menu_get_request($_REQUEST['page']);
else if ($_REQUEST['mod']) menu_get_request_by_mod($_REQUEST['mod']);
?>