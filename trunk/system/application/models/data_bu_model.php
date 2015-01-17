<?php
class data_bu_model extends Model {

	var $data;
	var $select;

	function data_bu_model()
	{
		parent::Model();
		$this->_table = 'badan_usaha';
		$this->_pk = 'id';	
		
		$this->select = '*';
				
		$this->fill_data();
	}

	function fill_data()
	{
		$this->data = array(
			'nama'=>$this->input->post('nama_bu')
			,'badan_tipe'=>$this->input->post('tipe_bu')
			,'alamat'=>$this->input->post('alamat_bu')
			//'badan_id_desa'=>implode("-",array_reverse(explode("/",$this->input->post('tanggal_lahir')))),
			,'badan_telp'=>$this->input->post('telp_bu')
			,'badan_fax'=>$this->input->post('fax_bu')
			//,'badan_npwp'=>$this->input->post('badan_npwp')
			//,'rt'=>$this->input->post('desa')
			//,'rw'=>$this->input->post('no_telp')
			,'kodepos'=>$this->input->post('kodepos_bu')
			,'pemilik_nama'=>$this->input->post('pemilik_nama')
			,'pemilik_no_ktp'=>$this->input->post('pemilik_no_ktp')
			,'pemilik_npwp'=>$this->input->post('pemilik_npwp')
			,'pemilik_telp'=>$this->input->post('pemilik_telp')
			,'pemilik_hp'=>$this->input->post('pemilik_hp')
			,'pemilik_tmp_lahir'=>$this->input->post('pemilik_tmp_lahir')
			,'pemilik_tgl_lahir'=>prepare_date($this->input->post('pemilik_tgl_lahir'))
			,'pemilik_alamat'=>$this->input->post('pemilik_alamat')
			,'pemilik_rt'=>$this->input->post('pemilik_rt')
			,'pemilik_rw'=>$this->input->post('pemilik_rw')
			,'pemilik_kodepos'=>$this->input->post('pemilik_kodepos')
			,'pemilik_id_desa'=>$this->input->post('pemilik_id_desa')
		);
		//implode("-",array_reverse(explode("/",$this->input->post('tanggal_sppd'))))
		//prepare_date($this->input->post('pemilik_tgl_lahir'))
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
		$this->db->where($this->_pk,$id);
		$update = $this->db->update($this->_table, $this->data);
		return $update;
		$this->db->trans_complete();
	}	

	function delete_data($id)
	{
		$id = explode(',',$id);
		$this->db->trans_start();
		$this->db->where_in($this->_pk,$id);
		$delete = $this->db->delete($this->_table);
		return $delete;
		$this->db->trans_complete();
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
		$this->db->order_by('nama');
		return $this->db->get($this->_table);
		$this->db->trans_complete();
	}

	function get_all_data()
	{
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
	}

	function get_data_by_id($id)
	{
		$this->db->where_in('a.'.$this->_pk, $id);
		$this->db->select($this->select);
		$this->db->order_by('nama');
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

	function get_last_id()
	{
		$query = $this->db->query('select gen_id(GEN_BADAN_USAHA_ID,0) from rdb$database');
		return $query->row()->GEN_ID;
	}
	
	function seekname($name)
	{
		$nama = strtolower($name);
	
		$this->db->trans_start();
		$this->db->select($this->_pk.',nama');
		$this->db->from($this->_table);
		$this->db->like('lower(nama)',$name);
		$this->db->order_by('nama');
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