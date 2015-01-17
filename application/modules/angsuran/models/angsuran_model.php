<?php
class Angsuran_model extends CI_Model {

  var $id_spt;
  var $lunas;
  var $fieldmap;
  var $fieldmap_bayar;
  var $data;
  var $purge;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

		$this->_table='PEMBAYARAN';
		$this->_pk='ID_PEMBAYARAN';

    $this->fieldmap = array(
      'id' => 'a.ID_PEMBAYARAN',
      'tgl' => 'a.TANGGAL',
      'jenis' => 'c.NAMA_REKENING',
      'nama_wp' => 'b.NAMA_WP',
      'spt' => 'b.NOMOR_SPT',
      'pajak' => 'b.JUMLAH_PAJAK',
      'setor' => 'a.TELAH_DIBAYAR',
      'sisa' => 'SISA'
    );
    
    $this->fieldmap_bayar =  array(
      'id_spt' => 'ID_SPT',
      'tgl' => 'TANGGAL',
      'angsuran' => 'TELAH_DIBAYAR',
      'ketetapan' => 'JUMLAH_BAYAR',
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
    $this->db->select_sum('TELAH_DIBAYAR');
    $this->db->from('PEMBAYARAN');
    $this->db->where('ID_SPT', $this->data['ID_SPT']);
    $res = $this->db->get()->row_array();
    
    $sisa = $this->data['JUMLAH_BAYAR'] - ($this->data['TELAH_DIBAYAR'] + $res['TELAH_DIBAYAR']);
    if ($sisa == 0)
    {
      $tgl_lunas = prepare_date($this->input->post('tgl'));
      $this->data_spt = array('TANGGAL_LUNAS' => $tgl_lunas);
      $this->db->where('ID_SPT', $this->data['ID_SPT']);
      $this->db->update('SPT', $this->data_spt);
    }

    $this->db->insert('PEMBAYARAN', $this->data);
  }

  function save_data()
  {
    $this->db->trans_start();
    $this->insert_data();
    $this->id_spt = $this->data['ID_SPT'];
    $this->lunas = $this->cek_lunas();
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
            c.nama_rekening,
            b.nama_wp,
            b.nomor_kohir,
            b.jumlah_pajak,
            sum(a.telah_dibayar) angsuran
    ");
    $this->db->from('pembayaran a');
    $this->db->join('spt b', 'b.id_spt = a.id_spt');
    $this->db->join('rekening c', 'c.id_rekening = b.id_rekening');
    $this->db->where('b.tipe', 'OA');
    $this->db->where('a.id_spt in (select a.id_spt from pembayaran a group by a.id_spt having count(a.id_spt) >= 1)');
    $this->db->where('a.id_spt not in (select a.id_spt from pembayaran a join spt b on b.id_spt = a.id_spt
          where b.tanggal_lunas is not null group by a.id_spt having count(a.id_spt) = 1)');

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
        $this->db->group_by('a.id_spt, c.nama_rekening, b.nama_wp, b.nomor_kohir, b.jumlah_pajak');
        $result = $this->db->get()->result_array();
        return $result;
      }
    }
  }
  
  function get_data_by_id($id)
  {
    $this->db->select('a.id_spt, 
              b.id_wajib_pajak, 
              b.nama_wp,
              b.nomor_kohir,
              c.id_rekening, 
              c.nama_rekening,
            ');
    $this->db->distinct();
    $this->db->from('pembayaran a');
    $this->db->join('spt b', 'b.id_spt = a.id_spt');
    $this->db->join('rekening c', 'c.id_rekening = b.id_rekening');
    $this->db->where('a.id_spt', $id);
    $result = $this->db->get()->row_array();
    
    return $result;
  }
  
  function get_jml_ketetapan()
  {
    $id_spt = $this->input->post('id_spt') ? $this->input->post('id_spt') : 0;
    
    $this->db->select('s.jumlah_pajak');
    $this->db->from('spt s');
    $this->db->where('s.id_spt', $id_spt);
    $result = $this->db->get()->result_array();

    return $result;
  }

  function get_data_angsuran()
  {
    $id_spt = $this->input->post('id_spt') ? $this->input->post('id_spt') : 0;
    
    $this->db->select('
      p.id_pembayaran,
      p.tanggal,
      p.telah_dibayar,
      p.jumlah_bayar
    ');
    $this->db->distinct();
    $this->db->from('pembayaran p');
    $this->db->where('p.id_spt', $id_spt);
    $result = $this->db->get()->result_array();

    return $result;
  }
  
  function get_nomor_kohir()
  {
    $id_wp = $this->input->post('id_wp') ? $this->input->post('id_wp') : 0;
    $idpjk = $this->input->post('idpjk') ? $this->input->post('idpjk') : 0;
    
    $this->db->select('s.id_spt, s.nomor_kohir');
    $this->db->from('spt s');
    $this->db->join('penetapan p', 'p.id_spt = s.id_spt');
    $this->db->where('s.id_wajib_pajak',$id_wp);
    $this->db->where('s.id_rekening',$idpjk);
    $this->db->where('s.tanggal_lunas is null');
    $result = $this->db->get()->result_array();
    
    return $result;
  }
  
  function is_lunas($id)
  {
    $this->db->select_sum('TELAH_DIBAYAR');
    $this->db->from('PEMBAYARAN');
    $this->db->where('ID_SPT', $id);
    $bayar = $this->db->get()->row_array();
    
    $this->db->select('JUMLAH_PAJAK');
    $this->db->from('SPT');
    $this->db->where('ID_SPT', $id);
    $pajak = $this->db->get()->row_array();

    $sisa = $pajak['JUMLAH_PAJAK'] - $bayar['TELAH_DIBAYAR'];
    if ($sisa == 0)
    {
      return 1;
    }
    else
    {
      return '';
    }
  }

  function cek_lunas()
  {
    $this->db->select_sum('TELAH_DIBAYAR');
    $this->db->from('PEMBAYARAN');
    $this->db->where('ID_SPT', $this->data['ID_SPT']);
    $bayar = $this->db->get()->row_array();
    
    $this->db->select('JUMLAH_PAJAK');
    $this->db->from('SPT');
    $this->db->where('ID_SPT', $this->data['ID_SPT']);
    $pajak = $this->db->get()->row_array();

    $sisa = $pajak['JUMLAH_PAJAK'] - $bayar['TELAH_DIBAYAR'];
    if ($sisa == 0)
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }
  
  function cek_lebih()
  {
    $this->db->select_sum('TELAH_DIBAYAR');
    $this->db->from('PEMBAYARAN');
    $this->db->where('ID_SPT', $this->data['ID_SPT']);
    $bayar = $this->db->get()->row_array();
    
    $this->db->select('JUMLAH_PAJAK');
    $this->db->from('SPT');
    $this->db->where('ID_SPT', $this->data['ID_SPT']);
    $pajak = $this->db->get()->row_array();

    $sisa = $pajak['JUMLAH_PAJAK'] - ($bayar['TELAH_DIBAYAR'] + $this->data['TELAH_DIBAYAR']);
    if ($sisa < 0)
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }

}
