<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function m_reklame_lokasi()
	{
		
		include("reklame_lokasi.php" );
		
		//echo 'asdfasdf';
	}
	
	function m_reklame_jenis()
	{
		include("reklame_jenis.php" );
		//echo 'asdfasdf';
	}	
?>