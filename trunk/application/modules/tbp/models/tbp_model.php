<?php
class Tbp_model extends CI_Model {
	function get_daftartbp(){
		$sql = " SELECT * FROM TBP ";
		$result = $this->db->query($sql)->result();
		return $result;
	}
}