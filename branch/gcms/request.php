<?

require_once('global.php');
//print_r($_REQUEST);
extract($_REQUEST);

if($mode=="asyc"){
	
	ajax_request($page);
	 
}else{

	//request dipanggil pake 'page' atau pake 'mod' ? 
	if ($_REQUEST['mod']) menu_get_request_by_mod($_REQUEST['mod']);
	else if ($_GET['page']) menu_get_request($_GET['page']);
	//menu_get_request($_REQUEST['page']);
	//else 
}

?>
