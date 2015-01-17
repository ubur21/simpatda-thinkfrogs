<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function m_penerimaan_pr_office()
	{
		include("entri_penerimaan_pr.php");
		
	}
	
	function m_penerimaan_pr_self()
	{
		include("entri_penerimaan_pr_self.php");
		
	}	
	
	function m_penerimaan_pr_daftar()
	{
		include("daftar_bu.php");
		
	}
	
	function m_penerimaan_pr_list(){ }

?>