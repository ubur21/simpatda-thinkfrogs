<?php
class Penyetoran_model extends CI_Model {

  var $tahun;
  var $id;
  var $fieldmap;
  var $fieldmap_sts;
  var $fieldmap_rincian;
  var $data_sts;
  var $data_rincian;
  var $purge_rincian;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    
    $this->tahun = $this->session->userdata['tahun'];

    $this->fieldmap = array(
      'id' => 'a.ID_PENYETORAN',
      'nosts' => 'a.NO_STS',
      'tgl' => 'a.TANGGAL',
      'kd_skpd' => 'b.KODE_SKPD',
      'nm_skpd' => 'b.NAMA_SKPD',
      'nom' => 'a.NOMINAL'
    );

    $this->fieldmap_sts = array(
      'id' => 'ID_PENYETORAN',
      'tahun' => 'TAHUN',
      'tgl' => 'TANGGAL',
      'id_skpd' => 'ID_SKPD',
      'nosts' => 'NO_STS',
      'jml_nom' => 'NOMINAL'
    );
    
    $this->fieldmap_rincian = array(
      'id' => 'ID_PENYETORAN',
      'idrek' => 'ID_REKENING',
      'nom' => 'NOMINAL'
    );
    
  }

  function fill_data()
  {
    foreach($this->fieldmap_sts as $key => $value){
      switch ($key)
      {
        case 'tgl'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tahun' : $$key = $this->tahun ? $this->tahun : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data_sts[$value] = $$key;
    }
    
    $this->purge_rincian = $this->input->post('purge'); $this->purge_rincian = $this->purge_rincian ? $this->purge_rincian : NULL;
    $rinci = $this->input->post('rincian') ? $this->input->post('rincian') : NULL;
    if ($rinci)
    {
      $rinci = json_decode($rinci);
      for ($i=0; $i <= count($rinci) - 1; $i++) {
        foreach($this->fieldmap_rincian as $key => $value){
          switch ($key)
          {
            default : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_rincian[$i][$value] = $$key;
        }
      }
    }
  }

  function insert_sts()
  {
    if (isset($this->data_sts['ID_PENYETORAN']))
    {
      $this->db->where('ID_PENYETORAN', $this->data_sts['ID_PENYETORAN']);
      $this->db->update('PENYETORAN', $this->data_sts);
      return $this->data_sts['ID_PENYETORAN'];
    }
    else
    {
      $this->db->insert('PENYETORAN', $this->data_sts);
      $this->db->select_max('ID_PENYETORAN')->from('PENYETORAN');
      $rs = $this->db->get()->row_array();
      return $rs['ID_PENYETORAN'];
    }
  }

  function insert_rincian()
  {
    if($this->purge_rincian)
    {
      $this->db->where_in('ID_REKENING', $this->purge_rincian);
      $this->db->delete('RINCIAN_PENYETORAN');
    }

    $jml = count($this->data_rincian);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $idrek = $this->data_rincian[$i]['ID_REKENING'];
      $this->db->select('1')->from('RINCIAN_PENYETORAN')->where('ID_PENYETORAN', $this->id)->where('ID_REKENING', $idrek);;
      $rs = $this->db->get()->row_array();

      if ($rs)
      {
        $this->db->where('ID_PENYETORAN', $this->id);
        $this->db->where('ID_REKENING', $idrek);
        $this->db->update('RINCIAN_PENYETORAN', $this->data_rincian[$i]);
      }
      else
      {
        $this->data_rincian[$i]['ID_PENYETORAN'] = $this->id;
        $this->db->insert('RINCIAN_PENYETORAN', $this->data_rincian[$i]);
      }
    }
  }

  function save_data()
  {
    $this->db->trans_start();
    $this->id = $this->insert_sts();
    $this->insert_rincian();
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function get_data($param, $isCount=FALSE, $CompileOnly=False)
  {
    isset($param['limit']) && $param['limit'] ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '';

    if (isset($param['search']) && $param['search'] && $wh = get_where_str(array($param['search_field'] => $param['search_str']), $this->fieldmap))
    {
      $this->db->where($wh);
    }

    if (isset($param['sort_by']) && $param['sort_by'] != null && !$isCount && $ob = get_order_by_str($param['sort_by'], $this->fieldmap))
    {
      $this->db->order_by($ob, $param['sort_direction']);
    }

    $this->db->select("
        a.id_penyetoran,
        a.no_sts,
        a.tanggal,
        s.kode_skpd,
        s.nama_skpd,
        a.nominal
    ");
    $this->db->from('penyetoran a');
    $this->db->join('skpd s', 's.id_skpd = a.id_skpd');

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

  function get_data_by_id($id)
  {
    $this->db->select('
        a.id_penyetoran,
        a.tanggal,
        a.id_skpd,
        a.no_sts,
        b.kode_skpd,
        b.nama_skpd
    ');
    $this->db->from('penyetoran a');
    $this->db->join('skpd b', 'a.id_skpd = b.id_skpd');
    $this->db->where('a.id_penyetoran', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_rinci_by_id($id)
  {
    $this->db->select('
       rs.id_rekening,
       r.kode_rekening,
       r.nama_rekening,
       rs.nominal
    ')
      ->from('rincian_penyetoran rs')
      ->join('rekening r', 'r.id_rekening = rs.id_rekening')
      ->where('rs.id_penyetoran', $id)
      ->order_by('r.kode_rekening');
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_prev_id($id)
  {
    $this->db->select('coalesce(max(a.id_penyetoran), 0) id_penyetoran');
    $this->db->from('penyetoran a');
    $this->db->where('id_penyetoran < ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_PENYETORAN'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(min(a.id_penyetoran), 0) id_penyetoran');
    $this->db->from('penyetoran a');
    $this->db->where('id_penyetoran > ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_PENYETORAN'];
  }

  function cek_duplikasi_nomor($nomor)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : NULL;

    if ($id) $this->db->where('ID_PENYETORAN <>', $id);
    $this->db->where('NO_STS', $nomor);
    $this->db->select('COUNT(ID_PENYETORAN) DUP');
    $rs = $this->db->get('PENYETORAN')->row_array();

    return (integer)$rs['DUP'] === 0;
  }
}
