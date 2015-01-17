<?php
class Tarif_air_model extends CI_Model {

  var $data;
  var $fieldmap = array();

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->_table = 'TARIF_AIR';
    $this->_pk = 'ID_TARIF_AIR';

    $this->fieldmap = array(
      'id' => 'ID_TARIF_AIR',
      'kode' => 'KODE',
      'nama' => 'URAIAN',
      'nom' => 'NOMINAL'
    );
  }

  function fill_data()
  {
    foreach($this->fieldmap as $key => $value){
      $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      if(isset($$key))
        $this->data[$value] = $$key;
    }
  }

  function get_data($param)
  {
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
				$param['search_str'] = strtoupper($param['search_str']);
				switch ($param['search_operator']) {
					case "bw": // begin with
						$wh .= " LIKE '".$param['search_str']."%'";
						break;
					case "cn": // contain %param%
						$wh .= " LIKE '%".$param['search_str']."%'";
						break;
					default :
						$wh = "";
				}
				$this->db->where($wh);
			}
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
    ($param['sort_by'] != null) ? $this->db->order_by($this->fieldmap[$param['sort_by']], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select('ID_TARIF_AIR, KODE, URAIAN, NOMINAL');
		$this->db->order_by ('KODE');		
		$result = $this->db->get($this->_table)->result_array();
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}
  }

  function check_duplication($kode, $id)
  {
    if ($id) $this->db->where('ID_TARIF_AIR <>', $id);
    $rs = $this->db
        ->where('KODE', $kode)
        ->select('COUNT(ID_TARIF_AIR) DUP')
        ->get('TARIF_AIR')->row_array();

    return (integer)$rs['DUP'] === 0;
  }

  function insert_data()
  {
    $this->fill_data();
    $this->data['ID_TARIF_AIR'] = NULL;
    $this->db->trans_start();
    $this->db->insert($this->_table, $this->data);
    $newid = $this->db->query('select max(ID_TARIF_AIR) as ID from TARIF_AIR')->row_array();
    $this->db->trans_complete();
    return $newid['ID'];
  }

  function update_data($id)
  {
    $this->fill_data();
    $this->db->trans_start();
    $this->db->where($this->_pk, $id)->update($this->_table, $this->data);
    $this->db->trans_complete();

    return $this->db->trans_status();
  }

  function delete_data($id){
    $this->db->trans_start();
    $this->db->where($this->_pk, $id)->delete($this->_table);
    $this->db->trans_complete();

    return $this->db->trans_status();
  }

  function check_dependency($id){
    return TRUE;
  }

}
?>