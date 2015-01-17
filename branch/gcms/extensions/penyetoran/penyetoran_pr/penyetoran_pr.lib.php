<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function m_penyetoran_pr_entri()
	{
		include("entri_penyetoran_pr.php");
		
	}
	
	function m_penyetoran_pr_daftar()
	{
		include("daftar_bu.php");
		
	}
	
	function m_penyetoran_pr_list(){ }

?>