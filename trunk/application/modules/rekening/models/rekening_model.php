<?php
class Rekening_model extends CI_Model {

  var $id;
  var $tahun;
	var $fieldmap;
	var $fieldmap_rekening = array();
	var $fieldmap_tarif = array();
	var $data_rekening;
	var $data_tarif;
	
	function __construct()
  {
    // Call the Model constructor
    parent::__construct();
		
    $this->tahun = $this->session->userdata('tahun');
    $this->kode = '';
    if (trim($this->input->post('tipe')) != '') {
      $this->kode .= trim($this->input->post('tipe'));
    }
    if (trim($this->input->post('kel')) != '') {
      $this->kode .= '.'.trim($this->input->post('kel'));
    }
    if (trim($this->input->post('jenis')) != '') {
      $this->kode .= '.'.trim($this->input->post('jenis'));
    }
    if (trim($this->input->post('objek')) != '') {
      $this->kode .= '.'.trim($this->input->post('objek'));
    }
    if (trim($this->input->post('rinci')) != '') {
      $this->kode .= '.'.trim($this->input->post('rinci'));
    }
    if (trim($this->input->post('sub1')) != '') {
      $this->kode .= '.'.trim($this->input->post('sub1'));
    }
    if (trim($this->input->post('sub2')) != '') {
      $this->kode .= '.'.trim($this->input->post('sub2'));
    }
    if (trim($this->input->post('sub3')) != '') {
      $this->kode .= '.'.trim($this->input->post('sub3'));
    }
    
		$this->fieldmap = array (
		'id' => 'ID_REKENING',
    'tipe' => 'r.TIPE',
    'kel' => 'r.KELOMPOK',
    'jenis' => 'r.JENIS',
    'objek' => 'r.OBJEK',
    'rinci' => 'r.RINCIAN',
    'sub1' => 'r.SUB1',
    'sub2' => 'r.SUB2',
    'sub3' => 'r.SUB3',
		'kode' => 'r.KODE_REKENING',
		'nama' => 'r.NAMA_REKENING',
    'tarif_rp' => 't.TARIF_RP',
    'tarif_persen' => 't.TARIF_PERSEN',
		);

		$this->fieldmap_rekening = array (
		'id' => 'ID_REKENING',
    'tipe' => 'TIPE',
    'kel' => 'KELOMPOK',
    'jenis' => 'JENIS',
    'objek' => 'OBJEK',
    'rinci' => 'RINCIAN',
    'sub1' => 'SUB1',
    'sub2' => 'SUB2',
    'sub3' => 'SUB3',
		'kode' => 'KODE_REKENING',
		'nama' => 'NAMA_REKENING'
		);
    
		$this->fieldmap_tarif = array (
		'id' => 'ID_REKENING',
    'tahun' => 'TAHUN',
    'tarif_rp' => 'TARIF_RP',
    'tarif_persen' => 'TARIF_PERSEN',
		);
    
	}
	
	function fill_data()
	{
		foreach($this->fieldmap_rekening as $key => $value){
			switch ($key){
				case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;
        case 'kode' : $$key = $this->kode; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : '';
			}
			if(isset($$key))
				$this->data_rekening[$value] = $$key;
		}

		foreach($this->fieldmap_tarif as $key => $value){
			switch ($key){
				case 'tahun' : $$key = $this->tahun; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
				$this->data_tarif[$value] = $$key;
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
        
		$this->db->select('r.ID_REKENING, r.TIPE, r.KELOMPOK, r.JENIS, r.OBJEK, r.RINCIAN, r.SUB1, 
                  r.SUB2, r.SUB3, r.KODE_REKENING, r.NAMA_REKENING, t.TARIF_RP, t.TARIF_PERSEN');
    $this->db->from('REKENING r');
    $this->db->join('TARIF_PAJAK t', 'r.ID_REKENING = t.ID_REKENING');
    $this->db->where('t.TAHUN = '.$this->tahun.'');

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
		$kode	= $this->kode;	$kode=($kode)?$kode:null;
		
		$this->db->trans_start();		
		$result = $this->db->query("SELECT * FROM REKENING WHERE kode_rekening = '$kode'")->result_array();		
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
		$kode	= $this->kode;	$kode=($kode)?$kode:null;
		
		$id = $this->db->query("select KODE_REKENING as KODE from REKENING WHERE ID_REKENING='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_REKENING');
		$this->db->from('REKENING');
		$this->db->where('KODE_REKENING <>', $id['KODE']);
		$ada = $this->db->get()->result_array();
		
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_REKENING'];
			}
		}
		
		$query = "SELECT * FROM REKENING WHERE kode_rekening = '$kode'";
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
  
	function insert_data_rekening()
	{
		$this->db->insert('REKENING', $this->data_rekening);
		$this->db->select_max('ID_REKENING', 'ID');
		$id = $this->db->get('REKENING')->row_array();
		return $id['ID'];		
	}
	
	function insert_data_tarif()
	{
    $this->data_tarif['ID_REKENING'] = $this->id;
		$this->db->insert('TARIF_PAJAK', $this->data_tarif);
	}

	function insert_data()
	{
		$this->db->trans_start();
    $this->id = $this->insert_data_rekening();
    $this->insert_data_tarif();
		$this->db->trans_complete();
    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
	}

	function update_data($id)
	{
		$this->db->trans_start();
		$this->db->where('ID_REKENING', $id);
		$this->db->update('REKENING', $this->data_rekening);
		$this->db->where('ID_REKENING', $this->data_rekening['ID_REKENING']);
		$this->db->update('TARIF_PAJAK', $this->data_tarif);
		$this->db->trans_complete();
    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
	}

	function check_dependency($id)
	{
		$this->db->trans_start();
		$this->db->select('count(ID_REKENING) ANGGARAN_PAKAI');
		$this->db->where('ID_REKENING', $id);         
		$result = $this->db->get('ANGGARAN')->row_array();
		$this->db->trans_complete();

		if ( $result['ANGGARAN_PAKAI'] > 0) 
    {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
  
	function delete_data($id){
		$this->db->trans_start();
    $tables = array('REKENING', 'TARIF_PAJAK');
		$this->db->where('ID_REKENING', $id);
		$delete = $this->db->delete($tables);
		$this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
      return FALSE;
    else
      return TRUE;
	}

}
?>