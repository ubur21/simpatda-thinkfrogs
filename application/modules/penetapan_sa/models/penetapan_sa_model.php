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
	$rinci = $this->input->post('rincian') ? $this->input->post('rincian') : NULL;
	
    if ($rinci)
    {
	  
      

	  
	  $rinci_json = "[" ;
      for($i=0;$i<count($rinci);$i++){ //foreach element in $arr
			if($i == count($rinci)-1){
				$rinci_json .= $rinci[$i]; 
			}else{
				$rinci_json .= $rinci[$i].','; //etc
			}
		}
	  $rinci_json .= "]" ;
	  $arr = json_decode($rinci_json,true);

		
		$data = array(
		   'SET URAIAN' => 'PT'
		);
		/*
		$this->db->set('URAIAN','Perseroan Terbatas');
		$this->db->set('KODE','001');
		$this->db->where('ID_JENIS_USAHA', '1');
      $this->db->update('JENIS_USAHA');
	  echo $arr[0]['nama']; */
		for($i=0;$i<count($arr);$i++){
			$this->db->set('ID_SPT', $arr[$i]['idspt']);
			$this->db->set('TANGGAL', prepare_date($this->input->post('tgl')));
			$this->db->set('BATAS_BAYAR', prepare_date($this->input->post('batas')));
			$this->db->insert('PENETAPAN');

			//echo ."<br/>";
			$this->db->set('TANGGAL_SPT',prepare_date($this->input->post('tgl')));
			$this->db->set('PERIODE_AWAL',prepare_date($this->input->post('tgl')));
			$this->db->set('PERIODE_AKHIR',prepare_date($this->input->post('batas')));
			$this->db->set('JUMLAH_PAJAK',$arr[$i]['jml']);
			$this->db->set('JUMLAH',$arr[$i]['jml']);
			$this->db->where('ID_SPT', $arr[$i]['idspt']);
			$this->db->update('SPT');
			//echo ""
		}
		//echo "Berhasil";
	  //exit;
    }
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
	
	//$hasil2 = $this->getJabatan($this->data[$i]['ID_SPT']);
	//if($hasil2[0]['JML'] > 2){
	//$this->db->insert('PENETAPAN', $this->data[$i]);
	//$hasil=$this->getDSpt($this->data[$i]['ID_SPT']);
	//$this->db->insert('SPT', array("ID_WAJIB_PAJAK"=>$hasil[0]['ID_WAJIB_PAJAK'],"ID_REKENING"=>$hasil[0]['ID_REKENING'],"TANGGAL_SPT"=>$hasil[0]['TANGGAL_SPT'],"NOMOR_SPT"=>$hasil[0]['NOMOR_SPT'],"TIPE"=>$hasil[0]['TIPE'],"STATUS_SPT"=>$hasil[0]['STATUS_SPT'],"NOMOR_KOHIR"=>$this->get_kohir(),"NAMA_WP"=>$hasil[0]['NAMA_WP'],"ALAMAT_WP"=>$hasil[0]['ALAMAT_WP'], "LOKASI"=>$hasil[0]['LOKASI'],"URAIAN"=>$hasil[0]['URAIAN'],"PERIODE_AWAL"=>$hasil[0]['PERIODE_AWAL'],"PERIODE_AKHIR"=>$hasil[0]['PERIODE_AKHIR'],"TARIF_RP"=>$hasil[0]['TARIF_RP'] ,"TARIF_PERSEN"=>$hasil[0]['TARIF_PERSEN'],"JUMLAH"=>$hasil[0]['JUMLAH'],"JUMLAH_PAJAK"=>(int)$hasil[0]['JUMLAH_PAJAK'] * (int)$hasil2[0]['JML'],"TANGGAL_LUNAS"=>$hasil[0]['TANGGAL_LUNAS'],"NPWPD"=>$hasil[0]['NPWPD']));
	// tarif persen, tarif rp
	//}else{
      $this->db->insert('PENETAPAN', $this->data[$i]);

      $this->data_spt = array('NOMOR_KOHIR'=>$this->get_kohir());
      $this->db->where('ID_SPT', $this->data[$i]['ID_SPT']);
      $this->db->update('SPT', $this->data_spt);
	//}
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
      r.periode_awal,
      r.periode_akhir,
	  r.JUMLAH_PAJAK
    ');
    $this->db->distinct();
    $this->db->from('spt r');
    $this->db->join('rekening s', 'r.id_rekening = s.id_rekening');
	$this->db->join('penetapan t', 'r.id_spt = t.id_spt','left');
    $this->db->where("r.id_rekening = '".$idrek."'");
    $this->db->where("r.tipe = 'SA'");
    $this->db->where('t.id_spt is null'); //nana
    $this->db->where('DATEDIFF(month,r.PERIODE_AKHIR,current_date) > 4');
    $this->db->or_where("(DATEDIFF(month,r.PERIODE_AKHIR,current_date) < 5 and r.tipe = 'SA' and t.id_spt is null and r.id_rekening = '".$idrek."'  )");
    $result = $this->db->get()->result_array();

    return $result;
  }
  
   function getJabatan($idSpt)
	  {
		$this->db->select('
		  DATEDIFF(month,r.PERIODE_AKHIR,current_date) as jml
		');
		$this->db->from('spt r');
		$this->db->where("r.id_spt = '".$idSpt."'");
		$result = $this->db->get()->result_array();

		return $result;
	  }
	function getDSpt($idSpt)
	  {
		$this->db->select('*');
		$this->db->from('spt r');
		$this->db->where("r.id_spt = '".$idSpt."'");
		$result = $this->db->get()->result_array();

		return $result;
	  }

}
