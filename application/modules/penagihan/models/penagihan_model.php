<?php
class Penagihan_model extends CI_Model {

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    
  }

  function getNPWPD()
  {
    $idrek = $this->input->post('idrek') ? $this->input->post('idrek') : 0;
    $tgl1 = $this->input->post('tgl1') ? prepare_date($this->input->post('tgl1')) : date('Y-m-d');
    $tgl2 = $this->input->post('tgl2') ? prepare_date($this->input->post('tgl2')) : date('Y-m-d');

    $this->db->select('
      r.id_spt,
      r.nama_wp,
      r.alamat_wp,
      r.periode_awal,
      r.periode_akhir,
      r.jumlah_pajak,
      p.tanggal,
      p.batas_bayar
    ');
    $this->db->distinct();
    $this->db->from('spt r');
    $this->db->join('rekening s', 'r.id_rekening = s.id_rekening');
    $this->db->join('penetapan p', 'r.id_spt = p.id_spt');
    $this->db->where('r.id_rekening', $idrek);
    $this->db->where('r.tanggal_lunas', null);
    $this->db->where("p.batas_bayar >= '".$tgl1."'");
    $this->db->where("p.batas_bayar <= '".$tgl2."'");
    $result = $this->db->get()->result_array();

    return $result;
  }

}
