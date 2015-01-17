<?php
class Umum_model extends CI_Model {

 
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }
	
   function get_nomor_spt()
  {
    $this->db->select('coalesce(max(id_spt),0) +1 as nomor');
    $result = $this->db->get('spt')->row_array();
    
    if (count($result) > 0)
      return $result['NOMOR'];
    else
      return '0';
  }

}
