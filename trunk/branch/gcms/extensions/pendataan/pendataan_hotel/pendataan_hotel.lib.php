<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function m_pendataan_hotel_PajakHotel()
	{
		if($_REQUEST['action'] == "detail_hotel"){
		include $_REQUEST['action'].".php";
		}else{
		include("pendataan_pajak_hotel.php");
		}
	}
	
	function m_penetapan_PajakRestoran()
	{
		
		echo 'asdfasdf';
	}

	function m_penetapan_PajakHiburan(){
		echo "xxxxx";
	}
	
	function open_detail_hotel(){
		echo "open_detail_hotel";
	}
	
?>