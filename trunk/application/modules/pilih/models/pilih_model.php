<?php
class Pilih_model extends CI_Model {

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();

  }

  function checkSearch($param, $fieldmap)
  {
    $wh = '';
    if(isset($param['search']) && $param['search'] != null && $param['search'] === 'true')
    {
      // cek apakah search_field ada dalam fieldmap ?
      if (array_key_exists($param['search_field'], $fieldmap))
      {
        $wh = "UPPER(".$fieldmap[$param['search_field']].")";
        $param['search_str'] = strtoupper($param['search_str']);
        switch ($param['search_operator'])
        {
          case "bw": // begin with
            $wh .= " LIKE '".$param['search_str']."%'";
            break;
          case "cn": // contain %param%
            $wh .= " LIKE '%".$param['search_str']."%'";
            break;
          default :
            $wh = "";
        }
      }
    }
    return $wh;
  }

  function getSKPD($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 's.ID_SKPD',
      'kode' => 's.KODE_SKPD',
      'nama' => 's.NAMA_SKPD'
    );

    $wh = $this->checkSearch($param, $fieldmap);
    if ($wh) $this->db->where($wh);
    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    $this->db->select('
      s.id_skpd,
      s.kode_skpd,
      s.nama_skpd
    ');
    $this->db->from('skpd s');

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
  }

  function getRekening($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'r.ID_REKENING',
      'kdrek' => 'r.KODE_REKENING',
      'nmrek' => 'r.NAMA_REKENING',
      'nom' => 'NOMINAL'
    );

    $wh = $this->checkSearch($param, $fieldmap);
    if ($wh) $this->db->where($wh);
    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    if ($param['mode'] === 'anggaran' || $param['mode'] === 'pendataan' || $param['mode'] === 'penetapan' 
        || $param['mode'] === 'bayar_sa' || $param['mode'] === 'bayar_oa' || $param['mode'] === 'angsuran' 
        || $param['mode'] === 'bayar_lain' || $param['mode'] === 'penagihan'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
    }
    else if ($param['mode'] === 'pendataan_air'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', PAJAK_AIR);
    }
    else if ($param['mode'] === 'pendataan_reklame'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', PAJAK_REKLAME);
    }
	else if ($param['mode'] === 'penetapan_oa'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', PAJAK_REKLAME);
      $this->db->or_where('pr.kode_pr', PAJAK_AIR);
    }
	else if ($param['mode'] === 'penetapan_sa'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr !=', PAJAK_REKLAME);
      $this->db->or_where('pr.kode_pr !=', PAJAK_AIR);
    }
    else if ($param['mode'] === 'pendataan_mineral'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', PAJAK_MINERAL);
    }
    else if ($param['mode'] === 'pendataan_hotel'){ //nana
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', PAJAK_HOTEL);
    }
    else if ($param['mode'] === 'pendataan_restoran'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', PAJAK_RESTAURAN);
    }
    else if ($param['mode'] === 'pendataan_hiburan'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', PAJAK_HIBURAN);
    }
    else if ($param['mode'] === 'pendataan_parkir'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', PAJAK_PARKIR);
    }
    else if ($param['mode'] === 'pendataan_peneranganjalan'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', PAJAK_PENERANGAN);
    }
	else if ($param['mode'] === 'master_pajak'){
		
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
	  $this->db->where('r.id_rekening not in (select p.id_rekening from rekening_pr p)');
    }
    else if ($param['mode'] === 'penyetoran'){
      $this->db->select('
        l.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        sum(l.jumlah_bayar) nominal
      ');
      $this->db->distinct();
      $this->db->from('pembayaran_lain l');
      $this->db->join('rekening r', 'r.id_rekening = l.id_rekening');
      $this->db->where('l.id_skpd', $param['id_skpd']);
      $this->db->where('r.id_rekening not in (select id_rekening from rincian_penyetoran)');
      $this->db->group_by('l.id_rekening, r.kode_rekening, r.nama_rekening');
    }
    else if ($param['mode'] === 'bayar_pajak_sa'){				
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->from('rekening r');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->join('spt s','r.id_rekening=s.id_rekening');
      $this->db->where('s.tipe', 'SA');
      $this->db->where('s.id_wajib_pajak', $param['id_spt']);
    }
    else if ($param['mode'] === 'retribusi_pasar'){
      $this->db->select('
        r.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        t.tarif_rp,
        t.tarif_persen
      ');
      $this->db->distinct();
      $this->db->from('rekening r');
      $this->db->join('rekening_pr pr','r.id_rekening=pr.id_rekening');
      $this->db->join('tarif_pajak t', 't.id_rekening=r.id_rekening');
      $this->db->where('pr.kode_pr', RETRIBUSI_PASAR);
    }

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
    return $result;
  }

	function getjenispajak($param, $isCount=FALSE)
	{
		$this->db->select('ID_REKENING');
		$this->db->from('spt');
		$this->db->where('id_wajib_pajak', $param['id_spt']);
		$this->db->where('tipe', 'SA');
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_REKENING'];
			}
		}
		
		$fieldmap = array(
			'kode_pr' => 'r.KODE_PR',
			'nama_pr' => 'r.NAMA_PR',
		);

		$wh = $this->checkSearch($param, $fieldmap);
		if ($wh) $this->db->where($wh);
		//($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

		if ($param['mode'] === 'bayar_pajak_sa' || $param['mode'] === 'angsuran'){
			$this->db->select('
				distinct(mp.kode_pr),
				mp.nama_pr
			');
			$this->db->from('rekening_pr r');
			$this->db->join('modul_pr mp', 'mp.kode_pr=r.kode_pr');
			$this->db->where_in('mp.sa','1');
			$this->db->where_in('r.id_rekening',$hasil);
		}
		
		if ($param['mode'] === 'bayar_pajak_oa' ){
			$this->db->select('
				distinct(mp.kode_pr),
				mp.nama_pr
			');
			$this->db->from('rekening_pr r');
			$this->db->join('modul_pr mp', 'mp.kode_pr=r.kode_pr');
			$this->db->where_in('mp.oa','1');
			$this->db->where_in('r.id_rekening',$hasil);
		}

		if ($isCount) {
			$result = $this->db->count_all_results();
			return $result;
		}
		else {
			$result = $this->db->get()->result_array();
			return $result;
		}
		return $result;
	}
  
  function getNPWPD($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'r.ID_WAJIB_PAJAK',
      'npwpd' => 'r.NPWPD',
      'nama_wp' => 'r.NAMA_WP',
    );

    $wh = $this->checkSearch($param, $fieldmap);
    if ($wh) $this->db->where($wh);
    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    if ($param['mode'] === 'pendataan' || $param['mode'] === 'angsuran' || $param['mode'] === 'pendataan_hotel_npwpd' || $param['mode'] === 'pendataan_restoran_npwpd'|| $param['mode'] === 'pendataan_hiburan_npwpd'|| $param['mode'] === 'pendataan_reklame_npwpd'){ //add by nana
      $this->db->select('
        r.id_wajib_pajak,
        r.npwpd,
        r.nama_wp,
        r.alamat_wp,
        r.id_kecamatan,
        r.id_kelurahan,
        kec.nama_kecamatan,
        kel.nama_kelurahan,
      ');
      $this->db->distinct();
      $this->db->from('wajib_pajak r');
      $this->db->join('kecamatan kec','kec.id_kecamatan=r.id_kecamatan');
      $this->db->join('kelurahan kel','kel.id_kelurahan=r.id_kelurahan');
	  
    /*
	  if ($param['mode'] === 'pendataan_hotel_npwpd'){ //add by nana
		$this->db->where('substring(r.npwpd from 1 for 2)=','02');
	  }
	  if ($param['mode'] === 'pendataan_restoran_npwpd'){ //add by nana
		$this->db->where('substring(r.npwpd from 1 for 2)=','03');
	  }
	  if ($param['mode'] === 'pendataan_hiburan_npwpd'){ //add by nana
		$this->db->where('substring(r.npwpd from 1 for 2)=','04');
	  }
	  if ($param['mode'] === 'pendataan_reklame_npwpd'){ //add by nana
		$this->db->where('substring(r.npwpd from 1 for 2)=','06');
	  }
    */

    }
	
	
    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
    return $result;
  }
  
  function getJURNAL(){
	$sql = " SELECT * FROM MST_JURNAL ";
		$result = $this->db->query($sql)->result_array();
		return $result;
  }
  
  function getPAJAKOA(){
	$sql = " SELECT ID_REKENING ,NAMA_REKENING  FROM REKENING  WHERE OBJEK='04' OR OBJEK = '08' ORDER BY KODE_REKENING ";
		$result = $this->db->query($sql)->result_array();
		return $result;
  }
  
  function getPAJAKMANUAL(){
	$sql = " SELECT ID_REKENING as ID, KODE_REKENING as NO_AKUN ,NAMA_REKENING as NAMA FROM REKENING   ORDER BY KODE_REKENING ";
		$result = $this->db->query($sql)->result_array();
		return $result;
  }
  
  function getBENDAHARA(){
	$sql = " SELECT * FROM MST_BENDAHARA ";
		$result = $this->db->query($sql)->result_array();
		return $result;
  }
  
  function getKASDAERAH(){
	$sql = " SELECT * FROM MST_KASDAERAH ";
		$result = $this->db->query($sql)->result_array();
		return $result;
  }
  
	function getSPT($param, $isCount=FALSE)
	{
		$fieldmap = array(
			'id' => 'r.ID_SPT',
			'id_wp' => 'r.ID_WAJIB_PAJAK',
			'nospt' => 'r.NOMOR_SPT',
			'nama_wp' => 'r.NAMA_WP',
		);

		$wh = $this->checkSearch($param, $fieldmap);
		if ($wh) $this->db->where($wh);
		($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

		if ($param['mode'] === 'bayar_sa'){
			$this->db->select('
				r.id_wajib_pajak,
				r.nama_wp,
			');
		$this->db->distinct();
		$this->db->from('spt r');
		$this->db->where('r.tipe', 'SA');
		$this->db->where('r.id_spt not in (select p.id_spt from pembayaran p)');
		//$this->db->group_by('r.id_wajib_pajak');
    }
	
	if ($param['mode'] === 'bayar_oa'){
			$this->db->select('
				r.id_wajib_pajak,
				r.nama_wp,
			');
		$this->db->distinct();
		$this->db->from('spt r');
		$this->db->where('r.tipe', 'OA');
		$this->db->where('r.id_spt not in (select p.id_spt from pembayaran p)');
		//$this->db->group_by('r.id_wajib_pajak');
    }

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
    return $result;
  }
  
  function getKohir($param, $isCount=FALSE)
  {
    $fieldmap = array(
      'id' => 'r.ID_SPT',
      'id_wp' => 'r.ID_WAJIB_PAJAK',
      'kohir' => 'r.NOMOR_KOHIR',
      'nama_wp' => 'r.NAMA_WP',
    );

    $wh = $this->checkSearch($param, $fieldmap);
    if ($wh) $this->db->where($wh);
    ($param['sort_by'] != null && !$isCount) ? $this->db->order_by( $fieldmap[$param['sort_by']], $param['sort_direction']) :'';

    if ($param['mode'] === 'bayar_oa'){
      $this->db->select('
        r.id_spt,
        r.id_wajib_pajak,
        r.nomor_kohir nomor,
        r.nama_wp,
      ');
      $this->db->distinct();
      $this->db->from('spt r');
      $this->db->where('r.tipe', 'OA');
      $this->db->where('r.id_spt not in (select p.id_spt from pembayaran p)');
    }

    if ($isCount) {
      $result = $this->db->count_all_results();
      return $result;
    }
    else {
      $result = $this->db->get()->result_array();
      return $result;
    }
    return $result;
  }
}