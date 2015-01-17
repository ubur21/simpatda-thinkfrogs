<?php
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	function m_kelompok_kegiatan()
	{
		include("kelompok.php" );
	}
    
    function getKegiatan($id){
        $sql = 'select first 1 k.id '.
               'from KELOMPOK_KEGIATAN kk '.
               'join kegiatan k on k.ID_KELOMPOK = kk.ID '.
               'where id = '.$id;
        $result = gcms_query($sql);
        while ($row = gcms_fetch_object($result)){
            $id_kel[] = $row->id;
            }
        return $id_kel;
    }
?>