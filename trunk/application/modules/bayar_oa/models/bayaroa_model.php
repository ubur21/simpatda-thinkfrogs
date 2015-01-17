<?php
class Bayaroa_model extends CI_Model {

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
      'id' => 'ID_PEMBAYARAN',
      'idspt' => 'ID_SPT',
      'tgl' => 'TANGGAL',
      'setor' => 'TELAH_DIBAYAR',
      'jml' => 'JUMLAH_BAYAR',
      'denda' => 'DENDA'
    );

  }

  function fill_data()
  {
    $this->purge = $this->input->post('purge'); $this->purge = $this->purge ? $this->purge : NULL;
    $rinci = $this->input->post('rincian') ? $this->input->post('rincian') : NULL;
    if ($rinci)
    {
      $rinci = json_decode($rinci);
      for ($i=0; $i <= count($rinci) - 1; $i++) {
        foreach($this->fieldmap_bayar as $key => $value){
          switch ($key)
          {
            case 'tgl' : $$key = $this->input->post('tgl') ? prepare_date($this->input->post('tgl')) : NULL; break;
            default : $$key = isset($rinci[$i]->$key) && $rinci[$i]->$key ? $rinci[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data[$i][$value] = $$key;
        }
      }
    }
  }

  function insert_data()
  {
    $jml = count($this->data);
    for ($i=0; $i <= $jml - 1; $i++)
    {
      $sisa = ($this->data[$i]['JUMLAH_BAYAR'] + $this->data[$i]['DENDA']) - $this->data[$i]['TELAH_DIBAYAR'];
      if ($sisa == 0)
      {
        $tgl_lunas = prepare_date($this->input->post('tgl'));
        $this->data_spt = array('TANGGAL_LUNAS' => $tgl_lunas);
        $this->db->where('ID_SPT', $this->data[$i]['ID_SPT']);
        $this->db->update('SPT', $this->data_spt);
      }

      $this->db->insert('PEMBAYARAN', $this->data[$i]);
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
            a.id_pembayaran,
            a.tanggal,
            c.nama_rekening,
            b.nama_wp,
            b.nomor_kohir,
            b.jumlah_pajak,
            a.telah_dibayar,
            sum(a.jumlah_bayar + a.denda - a.telah_dibayar) sisa
    ");
    $this->db->from('pembayaran a');
    $this->db->join('spt b', 'b.id_spt = a.id_spt');
    $this->db->join('rekening c', 'c.id_rekening = b.id_rekening');
    $this->db->where('b.tipe', 'OA');
    $this->db->where('a.id_spt in (select a.id_spt from pembayaran a join spt b on b.id_spt = a.id_spt
          where b.tanggal_lunas is not null group by a.id_spt having count(a.id_spt) = 1)');
    $this->db->group_by('a.id_pembayaran, a.tanggal, c.nama_rekening, b.nama_wp, b.nomor_kohir,
            b.jumlah_pajak, a.telah_dibayar');

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
  
  function getSPT($id_spt, $idpjk)
  {
    $this->db->select('
      r.id_spt,
      r.nomor_kohir,
      s.nama_rekening,
      r.periode_awal,
      r.periode_akhir,
      r.jumlah_pajak
    ');
    $this->db->distinct();
    $this->db->from('spt r');
    $this->db->join('penetapan p', 'p.id_spt = r.id_spt');
    $this->db->join('rekening s', 'r.id_rekening = s.id_rekening');
    $this->db->where('r.id_spt', $id_spt);
    $this->db->where('r.id_rekening', $idpjk);
    $this->db->where('r.tipe', 'OA');
    $this->db->where('r.tanggal_lunas is null');
    $result = $this->db->get()->result_array();

    return $result;
  }

}
