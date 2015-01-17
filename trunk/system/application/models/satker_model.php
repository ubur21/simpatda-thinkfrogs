<?php

class Satker_model extends Model {

	var $data;

	function Satker_model()
	{
		parent::Model();
		$this->_table = 'satker';
		$this->_pk = 'id_satker';
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
        
		//returns the query string
		$this->db->trans_start();
		$this->db->select('id_satker, id_satker_parent, kode_satker, nama_satker');
		$this->db->order_by('kode_satker');
		return $this->db->get($this->_table);
		$this->db->trans_complete();
	}

	function get_all_data()
	{
		$this->db->trans_start();
		$this->db->from($this->_table);
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
		$this->db->trans_start();
		$this->db->where_in($this->_pk, $id);
		$this->db->select('id_satker, kode_satker, nama_satker');
		$query = $this->db->get($this->_table);
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();
	}

	function get_id_by_name($name)
	{
		$this->db->trans_start();
		$this->db->where('nama_satker', $name);
		$this->db->select('id_satker, kode_satker, nama_satker');
		$query = $this->db->get($this->_table);

		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			return $result->id;
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();
	}	

	function fill_data()
	{
		$induk = $this->input->post('induk') == '' ? null : $this->input->post('induk');
		$this->data = array(
			'id_satker_parent' => $induk,
			'kode_satker' => $this->input->post('kode'),
			'nama_satker' => $this->input->post('nama')
		);
	}
	
	//Check for duplicate kode
	function check_code($id = '')
	{
		$this->db->trans_start();
		$this->db->where('kode_satker', $this->data['kode_satker']);
		if($id != '') $this->db->where($this->_pk.' !=', $id);
		$query = $this->db->get($this->_table);

		if ($query->num_rows() > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
		$this->db->trans_complete();
	}	

	function insert_data()
	{
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data);
		return $insert;
		$this->db->trans_complete();
	}

	function update_data($id)
	{
		$this->db->trans_start();
		$this->db->where($this->_pk, $id);
		$update = $this->db->update($this->_table, $this->data);
		return $update;
		$this->db->trans_complete();
	}

	function delete_data($id)
	{
		$id = explode(',',$id);
		$this->db->trans_start();
		$this->db->where_in($this->_pk, $id);
		$delete = $this->db->delete($this->_table);
		return $delete;
		$this->db->trans_complete();
	}

}
/* End of file satker_model.php */
/* Location: ./application/models/satker_model.php */
