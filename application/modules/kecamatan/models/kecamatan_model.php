<?php
class Kecamatan_model extends CI_Model {

  var $data;
  var $data_kelurahan;
  var $fieldmap = array();
  var $fieldmap_kelurahan = array();

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->_table = 'KECAMATAN';
    $this->_pk = 'ID_KECAMATAN';

    $this->_table2 = 'KELURAHAN';
    $this->_pk2 = 'ID_KELURAHAN';

    $this->fieldmap = array(
      'id' => 'ID_KECAMATAN',
      'kode' => 'KODE_KECAMATAN',
      'nama' => 'NAMA_KECAMATAN'
    );

    $this->fieldmap_kelurahan = array(
      'id_kec' => 'ID_KECAMATAN',
      'id_kel' => 'ID_KELURAHAN',
      'kode_kel' => 'KODE_KELURAHAN',
      'nama_kel' => 'NAMA_KELURAHAN'
    );
  }

  function fill_data()
  {
    foreach($this->fieldmap as $key => $value){
      $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      if(isset($$key))
        $this->data[$value] = $$key;
    }
    
    foreach($this->fieldmap_kelurahan as $key => $value){
      $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      if(isset($$key))
        $this->data_kelurahan[$value] = $$key;
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
        
    if (isset($param['sort_by']) && $param['sort_by'] != null && !$isCount && $ob = get_order_by_str($param['sort_by'], $this->fieldmap))
    {
      $this->db->order_by($ob, $param['sort_direction']);
    }
        
		//returns the query string
		
		$this->db->select('ID_KECAMATAN, KODE_KECAMATAN, NAMA_KECAMATAN');
    $this->db->from($this->_table);

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else
    {
      if ($CompileOnly)
      {
        return $this->db->get_compiled_select();
      }
      else
      {
        $result = $this->db->get()->result_array();
        return $result;
      }
    }
  }

	function check_data()
	{
		$kode	= $this->input->post('kode');	$kode=($kode)?$kode:null;
		
		$this->db->trans_start();		
		$result = $this->db->query("SELECT * FROM KECAMATAN WHERE kode_kecamatan = '$kode'")->result_array();		
		if(!empty($result)){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function check_data2()
	{
		$kode	= $this->input->post('kode');	$kode=($kode)?$kode:null;
		
		$id = $this->db->query("select KODE_KECAMATAN as KODE from KECAMATAN WHERE ID_KECAMATAN='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_KECAMATAN');
		$this->db->from('KECAMATAN');
		$this->db->where('KODE_KECAMATAN <>', $id['KODE']);
		$ada = $this->db->get()->result_array();
		
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_KECAMATAN'];
			}
		}
		
		$query = "SELECT * FROM KECAMATAN WHERE kode_kecamatan = '$kode'";
		$result = $this->db->query($query);
		
		if($kode == $id['KODE']){
			return TRUE;
		}
		else if (in_array($kode, $hasil)) {
			return FALSE;
		}	
		else if(count($result->result_array()) > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}

  function insert_data()
  {
    $this->fill_data();
    $this->data['ID_KECAMATAN'] = NULL;
    $this->db->trans_start();
    $this->db->insert($this->_table, $this->data);
    $newid = $this->db->query('select max(ID_KECAMATAN) as ID from KECAMATAN')->row_array();
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
        ->select('a.ID_KECAMATAN')
        ->select('(select count(b.ID_KECAMATAN) from KELURAHAN b where b.ID_KECAMATAN = a.ID_KECAMATAN) KEL_PAKAI')
        ->where('a.ID_KECAMATAN', $id)
        ->get('KECAMATAN a')->row_array();
    
    if ($result && $result['KEL_PAKAI'] > 0)
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }

  }

  function get_data_kelurahan($param,$ID_KECAMATAN)
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
        
    ($param['sort_by'] != null) ? $this->db->order_by($this->fieldmap_kelurahan[$param['sort_by']], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select('ID_KECAMATAN, ID_KELURAHAN, KODE_KELURAHAN, NAMA_KELURAHAN');
    $this->db->where('ID_KECAMATAN',$ID_KECAMATAN);
		$this->db->order_by ('KODE_KELURAHAN');		
		$result = $this->db->get($this->_table2)->result_array();
		$this->db->trans_complete();
    
		return $result;
  }
  
	function check_data_kelurahan()
	{
		$id_kec	= $this->input->post('id_kec');	$id_kec=($id_kec)?$id_kec:null;
		$kode	= $this->input->post('kode_kel');	$kode=($kode)?$kode:null;
		
		$this->db->trans_start();		
		$result = $this->db->query("SELECT * FROM KELURAHAN WHERE KODE_KELURAHAN = '$kode' AND ID_KECAMATAN = '$id_kec'")->result_array();		
		if(!empty($result)){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function check_data_kelurahan2()
	{
		$kode	= $this->input->post('kode_kel');	$kode=($kode)?$kode:null;
		
		$id = $this->db->query("SELECT ID_KECAMATAN as KEC, KODE_KELURAHAN as KODE from KELURAHAN WHERE ID_KELURAHAN='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_KELURAHAN');
		$this->db->from('KELURAHAN');
		$this->db->where('KODE_KELURAHAN <>', $id['KODE']);
    $this->db->where('ID_KECAMATAN', $id['KEC']);
		$ada = $this->db->get()->result_array();
		
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_KELURAHAN'];
			}
		}
		
		$query = "SELECT * FROM KELURAHAN WHERE KODE_KELURAHAN = '$kode' AND ID_KECAMATAN = '".$id['KEC']."'";
		$result = $this->db->query($query);
		
		if($kode == $id['KODE']){
			return TRUE;
		}
		else if (in_array($kode, $hasil)) {
			return FALSE;
		}	
		else if(count($result->result_array()) > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}

  function insert_data_kelurahan()
  {
    $this->fill_data();
    $this->data['ID_KELURAHAN'] = NULL;
    $this->db->trans_start();
    $this->db->insert($this->_table2, $this->data_kelurahan);
    $newid = $this->db->query('select max(ID_KELURAHAN) as ID from KELURAHAN')->row_array();
    $this->db->trans_complete();
    return $newid['ID'];
  }

  function update_data_kelurahan($id)
  {
    $this->fill_data();
    $this->db->trans_start();
    $this->db->where($this->_pk2, $id)->update($this->_table2, $this->data_kelurahan);
    $this->db->trans_complete();

    return $this->db->trans_status();
  }

  function delete_data_kelurahan($id){
    $this->db->trans_start();
    $this->db->where($this->_pk2, $id)->delete($this->_table2);
    $this->db->trans_complete();

    return $this->db->trans_status();
  }

  function check_dependency_kelurahan($id){
    $result = $this->db
        ->select('a.ID_KELURAHAN')
        ->select('(select count(b.ID_KELURAHAN) from WAJIB_PAJAK b where b.ID_KELURAHAN = a.ID_KELURAHAN) WP_PAKAI')
        ->where('a.ID_KELURAHAN', $id)
        ->get('KELURAHAN a')->row_array();

    if ($result && $result['WP_PAKAI'] > 0)
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }
}
?>