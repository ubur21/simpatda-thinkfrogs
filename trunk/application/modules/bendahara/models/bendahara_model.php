<?php
class Bendahara_model extends CI_Model {
	
	var $data;
	var $fieldmap = array();
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->_table='MST_BENDAHARA';
		$this->_pk='ID';
		
		$this->fieldmap = array(
			'id' => 'ID',
			'nama' => 'NAMA',
			'akun' => 'KODE'
		);
	}
	
	function fill_data()
	{
		$nama 	= $this->input->post('nama');$nama=$nama ? $nama : null;
		$akun		= $this->input->post('akun');	$akun = $akun ? $akun:null; 
		
		foreach($this->fieldmap as $key => $value){
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
        
		if($param['sort_by']=='nama'){
			$param['sort_by'] = 'nama_pejabat';
		}
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		$this->db->select('ID,NAMA,KODE');
		$this->db->order_by('ID');
		$result = $this->db->get($this->_table)->result_array();
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}
	}
	
	function get_data_by_id($id)
	{
		$this->db->trans_start();
		$this->db->where_in($this->_pk, $id);
		$result = $this->db->get($this->_table)->row_array();
		$this->db->trans_complete();
		if(count($result) > 0)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
	function check_duplication($nama, $id)
	{
		$this->db->trans_start();
		$this->db->where_in('NAMA', $nama);
		if ($id) $this->db->where('ID !=', $id);
		$result = $this->db->get($this->_table)->row_array();
		if(count($result) > 0)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
		
	function insert_data()
	{
		$this->fill_data();
		$this->db->trans_start();
		unset($this->data['ID']);
		$result = $this->db->insert($this->_table, $this->data);
		$newid = $this->db->query('select max(ID) as ID from MST_BENDAHARA')->row_array();
		$this->db->trans_complete();
		return $newid['ID'];
	}

	function update_data($id)
	{
		$this->fill_data();
		$data = $this->fill_data();
		$this->db->trans_start();
		$this->db->where($this->_pk, $id);
		$update = $this->db->update($this->_table, $this->data);
		$this->db->trans_complete();
		return $update;
	}

	function delete_data($id){
		$this->db->trans_start();
		$this->db->where($this->_pk, $id);
		$delete = $this->db->delete($this->_table);
		$this->db->trans_complete();
		return $delete;
	}
	
/*	function check_dependency($id){
		$this->db->trans_start();
		$this->db->select('a.ID_PEJABAT_DAERAH');
		$this->db->select('(select count(b.ID_PEJABAT_DAERAH) from TIM_ANGGARAN b where b.ID_PEJABAT_DAERAH = a.ID_PEJABAT_DAERAH) TIM_ANGGARAN_PAKAI');
		$this->db->select('(select count(b.ID_PEJABAT_DAERAH) from PEMBAHAS_ANGGARAN b where b.ID_PEJABAT_DAERAH = a.ID_PEJABAT_DAERAH) PEMBAHAS_ANGGARAN_PAKAI');
		$this->db->select('(select count(b.ID_BUD) from SPD b where b.ID_BUD = a.ID_PEJABAT_DAERAH) SPD_PAKAI');
		$this->db->select('(select count(b.ID_PEJABAT_DAERAH) from FORM_ANGGARAN b where b.ID_PEJABAT_DAERAH = a.ID_PEJABAT_DAERAH) FORM_ANGGARAN_PAKAI');
		$this->db->where('a.ID_PEJABAT_DAERAH', $id);
		$result = $this->db->get('PEJABAT_DAERAH a')->row_array();
		$this->db->trans_complete();
		if ($result['TIM_ANGGARAN_PAKAI'] > 0 || $result['PEMBAHAS_ANGGARAN_PAKAI'] > 0 || $result['SPD_PAKAI'] > 0 ) {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
*/	
	function check_isi()
	{
		$AKUN = $this->input->post('akun');$AKUN=($AKUN)?$AKUN:null;
		
		$this->db->trans_start();
		if ($AKUN != null){
			$query = "SELECT * FROM MST_BENDAHARA WHERE KODE='$AKUN'";
			$result = $this->db->query($query);
			$result = count($result->result_array());
			
			if($result > 0){
				return TRUE;
			}
			else{
				return FALSE;
			}		
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();	
	}
	
	function check_isi2($id) // cek NIP sebelum update
	{
		$AKUN = $this->input->post('akun');$AKUN=($AKUN)?$AKUN:null;
		
		$this->db->trans_start();
		if ($AKUN != null){
			$query = "SELECT * FROM MST_BENDAHARA WHERE KODE='$AKUN' and ID!='$id'";
			$result = $this->db->query($query);
			$result = count($result->result_array());
			
			if($result > 0){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();	
	}
	
}
?>