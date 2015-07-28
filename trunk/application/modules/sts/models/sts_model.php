<?php
class Sts_model extends CI_Model {
	function get_daftarsts(){
		$sql = " SELECT * FROM STS ";
		$result = $this->db->query($sql)->result();
		return $result;
	}
}