<?php

class User_model extends Model {

	var $data;
	
	function User_model()
	{
		parent::Model();
		$this->_table  = 'G_USERS';
		$this->_uname  = 'cuser';
		$this->_passwd = 'cpass';
		$this->_pk     = 'nid';
		$this->level   = 'nstatus';
	}
	
	function get_status($id)
	{
		$this->db->select($this->level);
		$this->db->where($this->_pk,$id);
		$rs = $this->db->get($this->_table);
		
		if ($rs->num_rows() > 0)
		{	
			$return = ($rs->row()->NSTATUS==4) ? TRUE : FALSE;
			
		}else $return  = FALSE;
		
		return $return;
		
	}
	
	function validate_login($username, $password)
	{
		$this->db->where($this->_uname, $username);
		$this->db->where($this->_passwd, md5($password));
		$query = $this->db->get($this->_table);
		
		return $query;
	}
	
	function get_all_data()
	{
		$this->db->where('aktif', 1);
		$query = $this->db->get($this->_table);
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
		$this->db->where($this->_pk, $id);
		$query = $this->db->get($this->_table);
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}	
	
	function fill_data()
	{
		$this->data = array(
			'nama_depan' => $this->input->post('fname'),
			'nama_belakang' => $this->input->post('lname'),
			'username' => $this->input->post('username'),
			'administrator' => $this->input->post('administrator'),
			'aktif' => 1
		);
		if($this->input->post('password')) $this->data['password'] = md5($this->input->post('password'));
	}
	
	//Check for duplicate login ID
	function check_username($id = '')
	{
		$this->db->where('username', $this->data['username']);
		$this->db->where('aktif', 1);
		if($id != '') $this->db->where('id !=', $id);
		$query = $this->db->get($this->_table);

		if ($query->num_rows() > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function insert_data()
	{
		$insert = $this->db->insert($this->_table, $this->data);
		return $insert;
	}

	function update_data($id)
	{
		$this->db->where($this->_pk, $id);
		$update = $this->db->update($this->_table, $this->data);
		return $update;
	}

	function delete_data($id){
		$this->db->where($this->_pk, $id);
		$delete = $this->db->update($this->_table, array('aktif' => 0));
		return $delete;
	}	
}

?>