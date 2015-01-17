<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function m_rekening_kategori()
	{
		include("rekening_kategori.php" );
	}
	
	function m_rekening_kode()
	{
		include("rekening_kode.php" );
	}	
	
	function m_rekening_pilih(){}
?>