<?php
class Anggaran_model extends CI_Model {

	var $fieldmap;
  var $id;
  var $purge_rincian;
	var $data;
	
	function __construct()
  {
    // Call the Model constructor
    parent::__construct();
		
		$this->fieldmap = array (
      'id' => 'ID_ANGGARAN',
      'skpd' => 'ID_SKPD',
      'tahun' => 'TAHUN',
      'idrek' => 'ID_REKENING',
      'pagu_murni' => 'PAGU_MURNI',
      'pagu_pak' => 'PAGU_PAK'
		);
    
	}
	
	function fill_data()
	{    

    $this->purge_rincian = $this->input->post('purge'); $this->purge_rincian = $this->purge_rincian ? $this->purge_rincian : NULL;
    $rinci = $this->input->post('rincian') ? $this->input->post('rincian') : NULL;
    if ($rinci)
    {
      $rinci = json_decode($rinci);
      for ($i=0; $i <= count($rinci) - 1; $i++) {
        foreach($this->fieldmap as $key => $value){
          switch ($key)
          {
            case 'skpd' : $$key = $this->input->post('id_skpd') ? ($this->input->post('id_skpd')) : NULL; break;
            case 'tahun' : $$key = $this->input->post('tahun') ? ($this->input->post('tahun')) : NULL; break;
            case 'pagu_murni' : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : 0; break;
            case 'pagu_pak' : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : 0; break;
            default : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data[$i][$value] = $$key;
        }
      }
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
        
    ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select('A.ID_ANGGARAN, A.ID_SKPD, A.TAHUN, SUM(A.PAGU_MURNI) MURNI, 
               SUM(A.PAGU_PAK) PAK, S.KODE_SKPD, S.NAMA_SKPD');
    $this->db->from('ANGGARAN A');
    $this->db->join('SKPD S', 'S.ID_SKPD = A.ID_SKPD');
    $this->db->group_by('A.ID_ANGGARAN, A.ID_SKPD, A.TAHUN, S.KODE_SKPD, S.NAMA_SKPD');
		$this->db->order_by('S.NAMA_SKPD');
		$result = $this->db->get()->result_array();
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}
	}
  
  function get_data_by_id($id)
  {
    $this->db->select('
        a.id_anggaran,
        a.id_skpd,
        a.id_rekening,
        a.tahun,
        a.pagu_murni,
        a.pagu_pak,
        s.kode_skpd,
        s.nama_skpd,
        r.kode_rekening,
        r.nama_rekening,
    ');
    $this->db->from('anggaran a');
    $this->db->join('skpd s', 'a.id_skpd = s.id_skpd');
    $this->db->join('rekening r', 'a.id_rekening = r.id_rekening');
    $this->db->where('a.id_anggaran', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_rinci_by_id($id)
  {
    $this->db->select('
       r.id_rekening,
       r.kode_rekening,
       r.nama_rekening,
       a.pagu_murni,
       a.pagu_pak
    ');
    $this->db->from('anggaran a');
    $this->db->join('rekening r', 'r.id_rekening = a.id_rekening');
    $this->db->where('a.id_anggaran', $id);
    $this->db->order_by('r.kode_rekening');
    $result = $this->db->get()->result_array();

    return $result;
  }
  
  function get_prev_id($id)
  {
    $this->db->select('coalesce(max(a.id_anggaran), 0) id_anggaran');
    $this->db->from('anggaran a');
    $this->db->where('id_anggaran < ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_ANGGARAN'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(min(a.id_anggaran), 0) id_anggaran');
    $this->db->from('anggaran a');
    $this->db->where('id_anggaran > ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_ANGGARAN'];
  }

  function insert()
  {
    $id = $this->input->post('id');

		if($this->purge_rincian)
		{
      $this->db->where_in('ID_REKENING', $this->purge_rincian);
      $this->db->where('ID_ANGGARAN', $id);
			$this->db->delete('ANGGARAN');
		}
    
    $mode = $this->input->post('mode');

    $this->db->select_max('ID_ANGGARAN')->from('ANGGARAN');
    $rs = $this->db->get()->row_array();
    $newid = $rs['ID_ANGGARAN'] + 1;

    $jml = count($this->data);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      if ($mode === 'new')
      {
        $this->id = $newid;
        $this->data[$i]['ID_ANGGARAN'] = $this->id;
        $this->db->insert('ANGGARAN', $this->data[$i]);
      }
      else
      {
        $this->id = $id;

        $idrek = $this->data[$i]['ID_REKENING'];
        $this->db->select('1')->from('ANGGARAN')->where('ID_ANGGARAN', $this->id)->where('ID_REKENING', $idrek);;
        $rs = $this->db->get()->row_array();

        if ($rs)
        {
          $this->db->where('ID_ANGGARAN', $this->id);
          $this->db->where('ID_REKENING', $idrek);
          $this->db->update('ANGGARAN', $this->data[$i]);
        }
        else
        {
          $this->data[$i]['ID_ANGGARAN'] = $this->id;
          $this->db->insert('ANGGARAN', $this->data[$i]);
        }
      }
    }
  }
  
  function save_data()
  {
    $this->db->trans_start();
    $this->insert();
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }
  
	function delete_data($id){
    $this->db->trans_start();
    $this->db->where('id_anggaran', $id);
    $this->db->delete('anggaran');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
	}

}
?>