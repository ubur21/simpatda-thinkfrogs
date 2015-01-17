<?php
class Bayarlain_model extends CI_Model {

  var $fieldmap;
  var $fieldmap_bayar;
  var $data;
  var $id;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

    $this->fieldmap =  array(
      'kd_skpd' => 'b.KODE_SKPD',
      'nm_skpd' => 'b.NAMA_SKPD',
      'kd_rek' => 'c.KODE_REKENING',
      'nm_rek' => 'c.NAMA_REKENING',
      'nama' => 'a.NAMA_PENYETOR',
      'alamat' => 'a.ALAMAT',
      'tgl' => 'a.TANGGAL',
      'jml_bayar' => 'a.JUMLAH_BAYAR'
    );

    $this->fieldmap_bayar =  array(
      'id' => 'ID_PEMBAYARAN_LAIN',
      'id_skpd' => 'ID_SKPD',
      'idrek' => 'ID_REKENING',
      'nama' => 'NAMA_PENYETOR',
      'alamat' => 'ALAMAT',
      'tgl' => 'TANGGAL',
      'jml_bayar' => 'JUMLAH_BAYAR',
      'keterangan' => 'KETERANGAN'
    );

  }

  function fill_data()
  {
    foreach($this->fieldmap_bayar as $key => $value){
      switch ($key)
      {
        case 'tgl' : $$key = $this->input->post('tgl') ? prepare_date($this->input->post('tgl')) : NULL; break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data[$value] = $$key;
    }
  }

  function insert_data()
  {
    if (isset($this->data['ID_PEMBAYARAN_LAIN']))
    {
      $this->db->where('ID_PEMBAYARAN_LAIN', $this->data['ID_PEMBAYARAN_LAIN']);
      $this->db->update('PEMBAYARAN_LAIN', $this->data);
      return $this->data['ID_PEMBAYARAN_LAIN'];
    }
    else
    {
      $this->db->insert('PEMBAYARAN_LAIN', $this->data);
      $this->db->select_max('ID_PEMBAYARAN_LAIN')->from('PEMBAYARAN_LAIN');
      $rs = $this->db->get()->row_array();
      return $rs['ID_PEMBAYARAN_LAIN'];
    }
  }

  function save_data()
  {
    $this->db->trans_start();
    $this->id = $this->insert_data();
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
            a.id_pembayaran_lain,
            a.nama_penyetor,
            a.alamat,
            a.tanggal,
            a.jumlah_bayar,
            b.kode_skpd,
            b.nama_skpd,
            c.kode_rekening,
            c.nama_rekening
    ");
    $this->db->from('pembayaran_lain a');
    $this->db->join('skpd b', 'b.id_skpd = a.id_skpd');
    $this->db->join('rekening c', 'c.id_rekening = a.id_rekening');

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
    $this->db->select("
            a.id_pembayaran_lain,
            a.nama_penyetor,
            a.alamat,
            a.tanggal,
            a.jumlah_bayar,
            a.keterangan,
            b.id_skpd,
            b.kode_skpd,
            b.nama_skpd,
            c.id_rekening,
            c.kode_rekening,
            c.nama_rekening
    ");
    $this->db->from('pembayaran_lain a');
    $this->db->join('skpd b', 'b.id_skpd = a.id_skpd');
    $this->db->join('rekening c', 'c.id_rekening = a.id_rekening');
    $this->db->where('a.id_pembayaran_lain', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }
  
  function get_prev_id($id)
  {
    $this->db->select('coalesce(max(a.id_pembayaran_lain), 0) id_pembayaran_lain');
    $this->db->from('pembayaran_lain a');
    $this->db->where('id_pembayaran_lain < ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_PEMBAYARAN_LAIN'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(min(a.id_pembayaran_lain), 0) id_pembayaran_lain');
    $this->db->from('pembayaran_lain a');
    $this->db->where('id_pembayaran_lain > ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_PEMBAYARAN_LAIN'];
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->db->where('id_pembayaran_lain', $id);
    $this->db->delete('pembayaran_lain');    
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }
  
}
