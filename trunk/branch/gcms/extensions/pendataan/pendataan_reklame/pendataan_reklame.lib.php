<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function m_pendataan_reklame_entri()
	{
		include("entri_pendataan_reklame.php");
	}
	
	function m_pendataan_reklame_detail(){}
	
	function m_pendataan_reklame_list(){}
	
	function m_reklame_daftar()
	{
		include("daftar_bu.php");
		
	}

?>