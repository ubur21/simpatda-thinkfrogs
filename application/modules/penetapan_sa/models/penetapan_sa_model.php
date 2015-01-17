<?php
class Penetapan_sa_model extends CI_Model {

  var $spt;
  var $tgl;
  var $batas;
  var $jmlspt;
  var $data;
  var $data_spt;
  var $fieldmap;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    
    $this->fieldmap = array(
      'idspt' => 'a.ID_SPT',
      'kohir' => 'a.NOMOR_KOHIR',
      'npwpd' => 'a.NPWPD',
      'nama_wp' => 'a.NAMA_WP',
      'rek' => 'r.NAMA_REKENING',
      'jumlah' => 'a.JUMLAH',
      'tgl' => 'p.TANGGAL',
      'batas' => 'p.BATAS_BAYAR',
    );
    
    $this->spt = $this->input->post('idspt') ? $this->input->post('idspt') : NULL;
    $this->tgl = $this->input->post('tgl') ? prepare_date($this->input->post('tgl')) : NULL;
    $this->batas = $this->input->post('batas') ? prepare_date($this->input->post('batas')) : NULL;
    $this->jmlspt = count($this->spt);

  }

  function fill_data()
  {
    if ($this->spt)
    {
      foreach ($this->spt as $key) 
      {
        $this->data[] = array('ID_SPT'=>$key, 'TANGGAL'=>$this->tgl, 'BATAS_BAYAR'=>$this->batas);
      }
    }
    
  }
  
  function get_kohir()
  {
    $this->db->select_max('NOMOR_KOHIR')->from('SPT');
    $res = $this->db->get()->row_array();
    $kohir = (int)$res['NOMOR_KOHIR'] + 1;
    
    $len = strlen($kohir);
    $nol = '';
    while($len < 3)
    {
      $nol .= '0';
      $len++;
    }
    $nomor = $nol;
    $nomor .= $kohir;
    
    return $nomor;
  }

  function insert_data()
  {
    for ($i=0; $i<$this->jmlspt; $i++)
    {
      $this->db->insert('PENETAPAN', $this->data[$i]);

      $this->data_spt = array('NOMOR_KOHIR'=>$this->get_kohir());
      $this->db->where('ID_SPT', $this->data[$i]['ID_SPT']);
      $this->db->update('SPT', $this->data_spt);
    }
  }
  
  function save_data()
  {
    $this->db->trans_start();
    $this->insert_data();
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
        a.id_spt,
        a.nomor_kohir,
        a.npwpd,
        a.nama_wp,
        r.nama_rekening,
        a.jumlah_pajak,
        p.tanggal,
        p.batas_bayar
    ");
    $this->db->from('spt a');
    $this->db->join('rekening r', 'r.id_rekening = a.id_rekening');
    $this->db->join('penetapan p', 'p.id_spt = a.id_spt');
	$this->db->where("a.tipe = 'SA'");

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

  function getNPWPD($idrek)
  {
    $this->db->select('
      r.id_spt,
      r.id_wajib_pajak,
      r.npwpd,
      r.nama_wp,
      s.nama_rekening,
      r.jumlah_pajak,
      r.periode_awal,
      r.periode_akhir,
    ');
    $this->db->distinct();
    $this->db->from('spt r');
    $this->db->join('rekening s', 'r.id_rekening = s.id_rekening');
    $this->db->where("r.id_rekening = '".$idrek."'");
    $this->db->where("r.tipe = 'SA'");
    $this->db->where('r.id_spt not in (select id_spt from penetapan)');
    $result = $this->db->get()->result_array();

    return $result;
  }

}
