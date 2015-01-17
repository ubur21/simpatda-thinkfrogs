<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function m_anggaran_tahun()
	{
		include("anggaran_tahun.php" );
		//echo 'asdfasdf';
	}
	
	function m_anggaran_status()
	{
		include("anggaran_status.php" );
		//echo 'asdfasdf';
	}	
?>