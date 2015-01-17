<?php
class Jenis_usaha_model extends CI_Model {

  var $data;
  var $fieldmap = array();

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->_table = 'JENIS_USAHA';
    $this->_pk = 'ID_JENIS_USAHA';

    $this->fieldmap = array(
      'id' => 'ID_JENIS_USAHA',
      'kode' => 'KODE',
      'nama' => 'URAIAN'
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

  function get_data($param, $isCount=FALSE, $CompileOnly=False)
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
		
		$this->db->select('ID_JENIS_USAHA, KODE, URAIAN');
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
    if ($id) $this->db->where('ID_JENIS_USAHA <>', $id);
    $rs = $this->db
        ->where('KODE', $kode)
        ->select('COUNT(ID_JENIS_USAHA) DUP')
        ->get('JENIS_USAHA')->row_array();

    return (integer)$rs['DUP'] === 0;
  }

  function insert_data()
  {
    $this->fill_data();
    $this->data['ID_JENIS_USAHA'] = NULL;
    $this->db->trans_start();
    $this->db->insert($this->_table, $this->data);
    $newid = $this->db->query('select max(ID_JENIS_USAHA) as ID from JENIS_USAHA')->row_array();
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
    $result = $this->db
        ->select('a.ID_JENIS_USAHA')
        ->select('(select count(b.ID_JENIS_USAHA) from WAJIB_PAJAK b where b.ID_JENIS_USAHA = a.ID_JENIS_USAHA) WP_PAKAI')
        ->where('a.ID_JENIS_USAHA', $id)
        ->get('JENIS_USAHA a')->row_array();

    return isset($result['WP_PAKAI']) && $result['WP_PAKAI'] > 1 ? FALSE : TRUE;
  }

}
?>