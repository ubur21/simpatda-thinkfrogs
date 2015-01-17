<?php
class Pembayaran_model extends CI_Model {

  var $tahun;
  var $username;
  var $id;
  var $fieldmap;
  var $data;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

    $this->tahun = $this->session->userdata('tahun');
    $this->username = $this->session->userdata('username');

    $this->fieldmap = array(
      'id' => 'a.ID_WAJIB_PAJAK',
      'jenis' => 'a.JENIS',
      'gol' => 'a.GOLONGAN',
      'no' => 'a.NOMOR',
      'noreg' => 'a.NOMOR_REG',
      'nama' => 'a.NAMA',
      'alamat' => 'a.ALAMAT',
      'usaha' => 'b.JENIS_USAHA',
      'npwpd' => 'a.NPWPD',
    );

    $this->fieldmap_wp = array(
      'id' => 'ID_WAJIB_PAJAK',
      'jenis' => 'JENIS',
      'gol' => 'GOLONGAN',
      'no' => 'NOMOR',
      'noreg' => 'NOMOR_REG',
      'nama' => 'NAMA_WP',
      'alamat' => 'ALAMAT_WP',
      'kecamatan' => 'ID_KECAMATAN',
      'kelurahan' => 'ID_KELURAHAN',
      'telp' => 'NO_TELP',
      'usaha' => 'ID_JENIS_USAHA',
      'nama_p' => 'NAMA_PEMILIK',
      'alamat_p' => 'ALAMAT_PEMILIK',
      'kecamatan_p' => 'ID_KECAMATAN_PEMILIK',
      'kelurahan_p' => 'ID_KELURAHAN_PEMILIK',
      'telp_p' => 'NO_TELP_PEMILIK',
      'npwpd' => 'NPWPD',
      'tgl' => 'TANGGAL_NPWPD',
      'tglkirim' => 'TANGGAL_KIRIM',
      'tglbalik' => 'TANGGAL_KEMBALI',
    );
  }

  function fill_data()
  {
    foreach($this->fieldmap as $key => $value){
      switch ($key)
      {
        case 'tgl'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tglkirim'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tglbalik'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data[$value] = $$key;
    }
  }

  function insert_data()
  {
    if (isset($this->data['ID_WAJIB_PAJAK']))
    {
      $this->db->where('ID_WAJIB_PAJAK', $this->data['ID_WAJIB_PAJAK']);
      $this->db->update('WAJIB_PAJAK', $this->data);
      return $this->data['ID_WAJIB_PAJAK'];
    }
    else
    {
      $this->db->insert('WAJIB_PAJAK', $this->data);
      $this->db->select_max('ID_WAJIB_PAJAK')->from('WAJIB_PAJAK');
      $rs = $this->db->get()->row_array();
      return $rs['ID_WAJIB_PAJAK'];
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
          a.id_pembayaran,
          b.nomor_spt,
          b.nomor_kohir,
          b.npwpd,
          b.tanggal_spt,
          a.tanggal tanggal_bayar,
          a.jumlah_bayar
    ");
    $this->db->from('pembayaran a');
    $this->db->join('spt b', 'b.id_spt = a.id_spt');
    $this->db->where('b.tipe', 'OA');

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
        a.id_wajib_pajak,
        a.jenis,
        a.golongan,
        a.nomor,
        a.nomor_reg,
        a.npwpd,
        b.uraian jenis_usaha,
        a.nama_wp,
        a.alamat_wp,
        a.id_kecamatan,
        kc.kode_kecamatan,
        kc.nama_kecamatan,
        a.id_kelurahan,
        kl.kode_kelurahan,
        kl.nama_kelurahan,
        a.no_telp,
        a.nama_pemilik,
        a.alamat_pemilik,
        a.id_kecamatan_pemilik,
        kcp.kode_kecamatan kode_kecamatan_pemilik,
        kc.nama_kecamatan nama_kecamatan_pemilik,
        a.id_kelurahan_pemilik,
        kl.kode_kelurahan kode_kelurahan_pemilik,
        kl.nama_kelurahan nama_kelurahan_pemilik,
        a.no_telp_pemilik,
        a.tanggal_npwpd,
        a.tanggal_dikirim,
        a.tanggal_kembali
    ');
    $this->db->from('wajib_pajak a');
    $this->db->join('jenis_usaha b', 'a.id_jenis_usaha = b.id_jenis_usaha');
    $this->db->join('kecamatan kc', 'a.id_kecamatan = kc.id_kecamatan', 'left');
    $this->db->join('kelurahan kl', 'a.id_kelurahan = kl.id_kelurahan', 'left');
    $this->db->join('kecamatan kcp', 'a.id_kecamatan = kcp.id_kecamatan', 'left');
    $this->db->join('kelurahan klp', 'a.id_kelurahan = klp.id_kelurahan', 'left');
    $this->db->where('a.id_wajib_pajak', $id);
    $result = $this->db->get()->row_array();

    return $result;
  }

  function get_prev_id($id)
  {
    $this->db->select('coalesce(max(a.id_wajib_pajak), 0) id_wajib_pajak');
    $this->db->from('wajib_pajak a');
    $this->db->where('id_wajib_pajak < ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_WAJIB_PAJAK'];
  }

  function get_next_id($id)
  {
    $this->db->select('coalesce(min(a.id_wajib_pajak), 0) id_wajib_pajak');
    $this->db->from('wajib_pajak a');
    $this->db->where('id_wajib_pajak > ', $id);
    $result = $this->db->get()->row_array();

    return $result['ID_WAJIB_PAJAK'];
  }

  function delete_data($id)
  {
    $this->db->trans_start();
    $this->db->where('id_wajib_pajak', $id);
    $this->db->delete('wajib_pajak');
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }
  }

  function check_dependency($id)
  {
    $this->db->select('count(a.id_aktivitas) bku_pakai');
    $this->db->from('rincian_bku a');
    $this->db->where('a.id_aktivitas_sts', $id);
    $result = $this->db->get()->row_array();

    if ($result && $result['BKU_PAKAI'] > 0 )
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

  function cek_duplikasi_nomor($nomor)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : NULL;

    if ($id) $this->db->where('ID_AKTIVITAS <>', $id);
    $this->db->where('NOMOR', $nomor);
    $this->db->where('TIPE', $this->tipe);
    $this->db->select('COUNT(ID_AKTIVITAS) DUP');
    $rs = $this->db->get('AKTIVITAS')->row_array();

    return (integer)$rs['DUP'] === 0;
  }
}
