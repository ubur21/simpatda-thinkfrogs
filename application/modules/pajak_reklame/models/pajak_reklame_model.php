<?php
class Pajak_reklame_model extends CI_Model {

  var $id;
  var $fieldmap;
  var $fieldmap_detil;
  var $data;
  var $data_detil;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

    $this->fieldmap = array(
      'id' => 'ID_SPT',
      'id_wp' => 'ID_WAJIB_PAJAK',
      'idrek' => 'ID_REKENING',
      'tgl' => 'TANGGAL_SPT',
      'nospt' => 'NOMOR_SPT',
      'tipe' => 'TIPE',
      'status' => 'STATUS_SPT',
      'nama' => 'NAMA_WP',
      'alamat' => 'ALAMAT_WP',
      'lokasi' => 'LOKASI',
      'uraian' => 'URAIAN',
      'awal' => 'PERIODE_AWAL',
      'akhir' => 'PERIODE_AKHIR',
      'tarif1' => 'TARIF_RP',
      'tarif2' => 'TARIF_PERSEN',
      'omset' => 'JUMLAH',
      'jml' => 'JUMLAH_PAJAK',
      'npwpd' => 'NPWPD',
    );
    
    $this->fieldmap_detil = array(
      'id' => 'ID_SPT',
      'jml_reklame' => 'JUMLAH',
      'sisi' => 'SISI',
      'hari' => 'HARI',
      'panjang' => 'PANJANG',
      'lebar' => 'LEBAR',
      'luas' => 'LUAS',
      'diskon' => 'DISKON',
	  'nilai_strategis' => 'NILAI_STRATEGIS',
    );
    

  }

  function fill_data()
  {
    foreach($this->fieldmap as $key => $value){
      switch ($key)
      {
        case 'tgl'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'awal'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'akhir'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data[$value] = $$key;
    }
    
    foreach($this->fieldmap_detil as $key => $value){
      switch ($key)
      {
        default : $$key = $this->input->post($key) ? $this->input->post($key) : 0;
      }
      if(isset($$key))
        $this->data_detil[$value] = $$key;
    }
    
  }
  
  function insert_data()
  {
    if (isset($this->data['ID_SPT']))
    {
      $this->db->where('ID_SPT', $this->data['ID_SPT']);
      $this->db->update('SPT', $this->data);
      return $this->data['ID_SPT'];
    }
    else
    {
      $this->db->insert('SPT', $this->data);
      $this->db->select_max('ID_SPT')->from('SPT');
      $rs = $this->db->get()->row_array();
      return $rs['ID_SPT'];
    }
  }

  function insert_detil()
  {
    $this->db->select('1')->from('DETIL_REKLAME')->where('ID_SPT', $this->id);
    $rs = $this->db->get()->row_array();

    if ($rs)
    {
      $this->db->where('ID_SPT', $this->data_detil['ID_SPT']);
      return $this->db->update('DETIL_REKLAME', $this->data_detil);
    }
    else
    {
      $this->data_detil['ID_SPT'] = $this->id;
      return $this->db->insert('DETIL_REKLAME', $this->data_detil);
    }
  }

  function save_data()
  {
    $this->db->trans_start();
    $this->id = $this->insert_data();
    $this->insert_detil();
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
        a.nomor_spt,
        a.tanggal_spt,
        a.periode_awal,
        a.periode_akhir,
        r.nama_rekening,
        a.nama_wp,
        a.lokasi,
        a.jumlah_pajak
    ");
    $this->db->from('spt a');
    $this->db->join('detil_reklame d', 'd.id_spt = a.id_spt');
    $this->db->join('rekening r', 'r.id_rekening = a.id_rekening');
	$this->db->join('rekening_pr pr', 'r.id_rekening = pr.id_rekening');
    $this->db->where('a.tipe', $param['tipe']);
	$this->db->where('pr.kode_pr', PAJAK_REKLAME);

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
        a.id_spt,
        a.nomor_spt,
        a.status_spt,
        a.tanggal_spt,
        a.periode_awal,
        a.periode_akhir,
        a.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        a.id_wajib_pajak,
        a.npwpd,
        a.nama_wp,
        a.alamat_wp,
        a.tarif_rp,
        a.tarif_persen,
        a.jumlah,
        a.jumlah_pajak,
        a.lokasi,
        a.uraian,
        w.id_kecamatan,
        w.id_kelurahan,
        kec.nama_kecamatan,
        kel.nama_kelurahan,
        d.jumlah jumlah_reklame,
        d.sisi,
        d.hari,
        d.panjang,
        d.lebar,
        d.luas,
        d.diskon,
        d.nilai_strategis
        
    ');
    $this->db->from('spt a');
    $this->db->join('rekening r', 'r.id_rekening = a.id_rekening');
    $this->db->join('wajib_pajak w', 'w.id_wajib_pajak = a.id_wajib_pajak');
    $this->db->join('kecamatan kec', 'kec.id_kecamatan = w.id_kecamatan');
    $this->db->join('kelurahan kel', 'kel.id_kelurahan = w.id_kelurahan', 'left');
    $this->db->join('detil_reklame d', 'd.id_spt = a.id_spt');
    $this->db->where('a.id_spt', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_prev_id($id, $tipe)
  {
    $this->db->select('coalesce(max(a.id_spt), 0) id_spt');
    $this->db->from('spt a');
    $this->db->join('detil_reklame d', 'd.id_spt = a.id_spt');
    $this->db->where('a.id_spt < ', $id);
    $this->db->where('a.tipe', $tipe);
    $result = $this->db->get()->row_array();

    return $result['ID_SPT'];
  }

  function get_next_id($id, $tipe)
  {
    $this->db->select('coalesce(min(a.id_spt), 0) id_spt');
    $this->db->from('spt a');
    $this->db->join('detil_reklame d', 'd.id_spt = a.id_spt');
    $this->db->where('a.id_spt > ', $id);
    $this->db->where('a.tipe', $tipe);
    $result = $this->db->get()->row_array();

    return $result['ID_SPT'];
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->db->where('id_spt', $id);
    $this->db->delete('spt');
    $this->db->where('id_spt', $id);
    $this->db->delete('detil_reklame');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function check_dependency($id)
  {
    $this->db->select('count(a.id_spt) bayar_pakai');
    $this->db->from('pembayaran a');
    $this->db->where('a.id_spt', $id);
    $result1 = $this->db->get()->row_array();

    $this->db->select('count(a.id_spt) tetap_pakai');
    $this->db->from('penetapan a');
    $this->db->where('a.id_spt', $id);
    $result2 = $this->db->get()->row_array();
    
    if ($result1 && $result1['BAYAR_PAKAI'] > 0 )
    {
      return FALSE;
    }
    else if ($result2 && $result2['TETAP_PAKAI'] > 0 )
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
  
}
