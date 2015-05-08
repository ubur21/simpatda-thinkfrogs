<?php
class Pendaftaran_model extends CI_Model {

  var $id;
  var $fieldmap;
  var $fieldmap_wp;
  var $data;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

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
      'namap' => 'NAMA_PEMILIK',
      'alamatp' => 'ALAMAT_PEMILIK',
      'kecamatanp' => 'ID_KECAMATAN_PEMILIK',
      'kelurahanp' => 'ID_KELURAHAN_PEMILIK',
      'telpp' => 'NO_TELP_PEMILIK',
      'npwpd' => 'NPWPD',
      'tgl' => 'TANGGAL_NPWPD',
      'tglkirim' => 'TANGGAL_DIKIRIM',
      'tglkembali' => 'TANGGAL_KEMBALI',
    );
  }

  function fill_data()
  {
    $kode_kec = $this->input->post('kecamatan');
    $id_kec = $this->db->query("select id_kecamatan from kecamatan where kode_kecamatan = '$kode_kec'")->row_array();
    $kec = $id_kec['ID_KECAMATAN'];
    
    $kode_kecp = $this->input->post('kecamatanp');
    $id_kecp = $this->db->query("select id_kecamatan from kecamatan where kode_kecamatan = '$kode_kecp'")->row_array();
    $kecp = $id_kecp['ID_KECAMATAN'];

    $kode_kel = $this->input->post('kelurahan');
    $id_kel = $this->db->query("select id_kelurahan from kelurahan where kode_kelurahan = '$kode_kel'")->row_array();
    $kel = $id_kel['ID_KELURAHAN'];

    $kode_kelp = $this->input->post('kelurahanp');
    $id_kelp = $this->db->query("select id_kelurahan from kelurahan where kode_kelurahan = '$kode_kelp'")->row_array();
    $kelp = $id_kelp['ID_KELURAHAN'];

    foreach($this->fieldmap_wp as $key => $value){
      switch ($key)
      {
        case 'tgl'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tglkirim'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'tglkembali'   : $$key = $this->input->post($key) ? prepare_date($this->input->post($key)) : NULL; break;
        case 'kecamatan' : $$key = isset($kec) ? $kec : NULL; break;
        case 'kelurahan' : $$key = isset($kel) ? $kel : NULL; break;
        case 'kecamatanp' : $$key = isset($kecp) ? $kecp : NULL; break;
        case 'kelurahanp' : $$key = isset($kelp) ? $kelp : NULL; break;
        default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
      }
      if(isset($$key))
        $this->data[$value] = $$key;
    }
  }

  function cek_duplikasi_nomor($nomor)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : NULL;

    if ($id) $this->db->where('ID_WAJIB_PAJAK <>', $id);
    $this->db->where('NOMOR', $nomor);
    $this->db->select('COUNT(ID_WAJIB_PAJAK) DUP');
    $rs = $this->db->get('WAJIB_PAJAK')->row_array();

    return (integer)$rs['DUP'] === 0;
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
        a.id_wajib_pajak,
        case a.jenis when 'P' then 'Pajak' when 'R' then 'Retribusi' else '-' end jenis,
        case a.golongan when '1' then 'Golongan 1' when '2' then 'Golongan 2' else '-' end golongan,
        a.nomor,
        a.nomor_reg,
        a.nama_wp,
        a.alamat_wp,
        a.npwpd,
        b.uraian jenis_usaha
    ");
    $this->db->from('wajib_pajak a');
    $this->db->join('jenis_usaha b', 'b.id_jenis_usaha = a.id_jenis_usaha');

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
        a.id_jenis_usaha,
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
        kcp.nama_kecamatan nama_kecamatan_pemilik,
        a.id_kelurahan_pemilik,
        klp.kode_kelurahan kode_kelurahan_pemilik,
        klp.nama_kelurahan nama_kelurahan_pemilik,
        a.no_telp_pemilik,
        a.tanggal_npwpd,
        a.tanggal_dikirim,
        a.tanggal_kembali
    ');
    $this->db->from('wajib_pajak a');
    $this->db->join('jenis_usaha b', 'a.id_jenis_usaha = b.id_jenis_usaha');
    $this->db->join('kecamatan kc', 'a.id_kecamatan = kc.id_kecamatan', 'left');
    $this->db->join('kelurahan kl', 'a.id_kelurahan = kl.id_kelurahan', 'left');
    $this->db->join('kecamatan kcp', 'a.id_kecamatan_pemilik = kcp.id_kecamatan', 'left');
    $this->db->join('kelurahan klp', 'a.id_kelurahan_pemilik = klp.id_kelurahan', 'left');
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
  
  function get_nomor()
  {
    $this->db->select('nomor');
    $this->db->order_by('id_wajib_pajak','desc');
    $this->db->limit(1);
    $result = $this->db->get('wajib_pajak')->row_array();
    
    if (count($result) > 0)
      return $result['NOMOR'];
    else
      return '0';
  }
  
  function get_jenis_usaha()
  {
    $this->db->select('id_jenis_usaha, kode, uraian');
    $this->db->order_by('uraian','asc');
    $result = $this->db->get('jenis_usaha');
    
    return $result;
  }

  function get_daftar_kecamatan()
  {
    $this->db->select('kode_kecamatan, nama_kecamatan');
    $this->db->order_by('nama_kecamatan','asc');
    $result = $this->db->get('kecamatan');
    
    return $result;
  }

  function get_daftar_kelurahan($kode)
  {
    $this->db->select('id_kecamatan');
    $this->db->where('kode_kecamatan',$kode);
    $id_kecamatan = $this->db->get('kecamatan')->row_array();
    $id = isset($id_kecamatan['ID_KECAMATAN']) ? $id_kecamatan['ID_KECAMATAN'] : 0;
    
    $this->db->select('kode_kelurahan, nama_kelurahan');
    $this->db->where('id_kecamatan',$id);
    $this->db->order_by('nama_kelurahan','asc');
    $result = $this->db->get('kelurahan');
    
    return $result;
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
    $this->db->select('count(id_wajib_pajak) pajak_pakai');
    $this->db->from('spt');
    $this->db->where('id_wajib_pajak', $id);
    $result = $this->db->get()->row_array();

    if ($result && $result['PAJAK_PAKAI'] > 0 )
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
  
  function search_nama($nama)
  {
	  $result = $this->db->query(" SELECT * FROM WAJIB_PAJAK WHERE NAMA_WP LIKE '$nama%' ")->result();
	
	return $result;
  }


}
