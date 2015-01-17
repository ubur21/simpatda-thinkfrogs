<?php
class Master_pajak_model extends CI_Model {

  var $data;
  var $data_menu;
  var $fieldmap = array();

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->_table = 'MODUL_PR';
    $this->_pk = 'KODE_PR';

    $this->fieldmap = array(
      'id' => 'KODE_PR',
      'oa' => 'OA',
      'sa' => 'SA'
    );
  }

	function fill_data()
	{
		foreach($this->fieldmap as $key => $value){
			$$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			// if(isset($$key))
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
		
		$this->db->select('*');
		$this->db->order_by('KODE_PR');		
		$result = $this->db->get('MODUL_PR')->result_array();
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}
	}
	
	function update_data($id)
	{
		$this->fill_data();
		$this->db->trans_start();
		$this->db->where('KODE_PR', $id);
		$update = $this->db->update($this->_table, $this->data);
		//$update2 = $this->db->update($this->_table, $this->data);
		for ($i=0; $i <= count($this->data) - 1; $i++) {
			$this->db->select('*');
			$this->db->from('MODUL_PR a');			
			$this->db->where('a.KODE_PR',$this->data['KODE_PR']);	
			$result = $this->db->get()->result_array();
		} 
		for ($i=0; $i <= count($result) - 1; $i++) {
			if($result[$i]['OA']==1){	
				$new_satuan = array('AKTIF'=>1);
				$this->db->where('TITLE',$result[$i]['NAMA_PR']);	
				$this->db->where('LINK',$result[$i]['URL_DAFTAR_OA']);	
				$this->db->update('MENU',$new_satuan);	
			}
			else{	
				$new_satuan = array('AKTIF'=>0);
				$this->db->where('TITLE',$result[$i]['NAMA_PR']);	
				$this->db->where('LINK',$result[$i]['URL_DAFTAR_OA']);	
				$this->db->update('MENU',$new_satuan);	
			}
			
			if($result[$i]['SA']==1){	
				$new_satuan = array('AKTIF'=>1);
				$this->db->where('TITLE',$result[$i]['NAMA_PR']);	
				$this->db->where('LINK',$result[$i]['URL_DAFTAR_SA']);	
				$this->db->update('MENU',$new_satuan);	
			}
			else{	
				$new_satuan = array('AKTIF'=>0);
				$this->db->where('TITLE',$result[$i]['NAMA_PR']);	
				$this->db->where('LINK',$result[$i]['URL_DAFTAR_SA']);	
				$this->db->update('MENU',$new_satuan);	
			}
			
		}
		$this->db->trans_complete();
		return $update;
	}
		
	function simpan_rek()
	{
		$this->db->trans_start();	
		$data = array(
                'KODE_PR' => $this->input->post('id'),
                'ID_REKENING' => $this->input->post('idrek')
				);

		$this->db->insert('REKENING_PR',$data);
		$this->db->trans_complete();
	}	
	
	function get_data_rek($param, $isCount=FALSE, $CompileOnly=False)
	{
		$this->db->trans_start();

		$this->db->select('a.*,b.NAMA_REKENING,b.KODE_REKENING');
		$this->db->from('REKENING_PR a');
		$this->db->where('a.KODE_PR',$param['id']);
		$this->db->join('REKENING b','a.ID_REKENING=b.ID_REKENING');
		$this->db->order_by('b.KODE_REKENING');		
		$result = $this->db->get()->result_array();
		$this->db->trans_complete();
		if(count($result) > 0) {
			return $result;
		} 
		else {
			return FALSE;
		}
	}
	
	function check_isi_rek()
	{
		$this->db->select('count(a.KODE_PR) as JUMPR');
		$this->db->from('REKENING_PR a');
		$this->db->where('a.KODE_PR', $this->input->post('id'));
		$this->db->where('a.ID_REKENING', $this->input->post('idrek'));
		$result = $this->db->get()->row_array();

		if ($result && $result['JUMPR'] > 0 )
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function hapus_rek($id,$idrek)
	{
		$this->db->trans_start();
		$this->db->where('KODE_PR', $id);
		$this->db->where('ID_REKENING', $idrek);
		$this->db->delete('REKENING_PR');
		$this->db->trans_complete();
	}

}
?>