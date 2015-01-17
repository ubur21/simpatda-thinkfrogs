<?php
class desa_model extends Model {

	var $data;
	var $select;

	function desa_model()
	{
		parent::Model();
		$this->_table = 'desa';
		$this->_pk = 'id';	
		
		$this->select = '*';
				
		$this->fill_data();
	}
	
	function fill_data()
	{
		$this->data = array(
			'desa_kode'=>$this->input->post('lurah_kode'),
			'desa_nama'=>$this->input->post('lurah_nama'),
			'id_kecamatan'=>$this->input->post('lurah_kecamatan')
		);
	}
	
	function insert_data()
	{
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data);
		$this->db->trans_complete();
		return $insert;
	}	
	
	function update_data($id)
	{
		$this->db->trans_start();
		$this->db->where($this->_pk,$id);
		$update = $this->db->update($this->_table, $this->data);
		return $update;
		$this->db->trans_complete();
	}	

	function delete_data($id)
	{
		$this->db->trans_start();
		$id = explode(',',$id);
		$this->db->where_in($this->_pk,$id);
		$delete = $this->db->delete($this->_table);
		$this->db->trans_complete();
		return $delete;
	}
	
	function delete_all_data($id)
	{
		$this->db->where($this->_pk,$id);
		$delete = $this->db->delete($this->_table);
	}
		
	function get_data($param)
	{			
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
			
		$this->db->trans_start();
		$this->db->select($this->select);
		return $this->db->get($this->_table);
		$this->db->trans_complete();
	}

	function get_all_data()
	{
		$this->db->trans_start();
		$this->db->from($this->_table);
		$this->db->order_by('desa_nama');
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();
	}

	function get_data_by_id($id)
	{
		$this->db->where_in('a.'.$this->_pk, $id);
		$this->db->select($this->select);
		$query = $this->db->get($this->_table.' a');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}	
	
	function seekname($name)
	{
		$nama = strtolower($name);
	
		$this->db->trans_start();
		$this->db->select($this->_pk.',desa_nama');
		$this->db->from($this->_table);
		$this->db->like('lower(desa_nama)',$name);
		$this->db->order_by('desa_nama');
		$query = $this->db->get();
		return $query;
		$this->db->trans_complete();
	}	
	
	function get_no(){
		$this->db->select_max('nurut');
		$this->db->from($this->_table);
		
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return 1;
		}
	}
}

?>