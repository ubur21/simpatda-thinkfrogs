<?php
class Sts_model extends CI_Model {
	function get_daftarsts(){
		$sql = " SELECT * FROM STS ";
		$result = $this->db->query($sql)->result();
		return $result;
	}
	
	function getlistTBP(){
		$sql = " SELECT A.ID,A.TOTAL_BAYAR AS NOMINAL,  B.KODE_REKENING AS IDAKUN, B.NAMA_REKENING AS NAMA,0 AS SISA
FROM TBP A
INNER JOIN REKENING B ON A.ID_REKENING = B.ID_REKENING ";
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
}