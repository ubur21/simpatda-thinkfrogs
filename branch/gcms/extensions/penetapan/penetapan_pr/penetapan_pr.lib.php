<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function m_penetapan_pr_entri()
	{
		include("entri_penetapan_pr.php");		
		
	}
	
	function m_penetapan_pr_daftar()
	{
		include("daftar_bu.php");
		
	}
	
	function m_penetapan_pr_list(){ }

?>