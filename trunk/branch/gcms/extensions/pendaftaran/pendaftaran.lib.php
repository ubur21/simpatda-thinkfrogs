<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function m_pendaftaran_pribadi()
	{
		include("pendaftaran_pribadi.php" );
		//echo 'asdfasdf';
	}
	
	function m_pendaftaran_bu()
	{
		include("pendaftaran_bu.php" );
		//echo 'asdfasdf';
	}
	
	function m_pendaftaran_list(){
		//include('list_npwp.php');
	}

	function m_pendaftaran_badan(){
		include("pendaftaran_perusahaan.php");
	}
	
	function m_pendaftaran_pemohon(){
		include("pendaftaran_pemohon.php");
	}
?>