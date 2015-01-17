<?php

class Laporan extends CI_Controller{

  var $path2engine;
  var $path2fr3;
  var $path2cache;
  var $path2output;
  var $attachment;

  public function __construct()
  {
    parent:: __construct();
    $this->load->helper('path');
    $this->path2engine = dirname( realpath(SELF) )."/assets/fr/";
    $this->path2fr3 = $this->path2engine . 'fr3/';
    $this->path2cache = $this->path2engine . 'cache/';
    $this->path2output = $this->path2engine . 'output/';
    $this->attachment = 1; // 0 : show in the browser;  1 : download file
  }

  function index()
  {

  }

  private function start($param)
  {
    $this->load->helper('file');

    $data = 'ReportName='. $param['fr3'] ."\n";
    $data .= 'OutputType='. $param['format'] ."\n";
    $data .= 'DriverName='.$this->db->dbdriver."\n";
    $data .= 'Connection=DB Express'."\n";
    $data .= 'DBPort='.$this->db->port."\n";
    $data .= 'DBServer='.$this->db->hostname."\n";
    $data .= 'DBName='.$this->db->database."\n";
    $data .= 'DBUser='. $this->db->username."\n";
    $data .= 'DBPassword='. $this->db->password."\n";

    if (isset($param['opt'])) {
      foreach($param['opt'] as $value){
        $data.=$value."\n";
      }
    }

    $in = tempnam($this->path2cache, "in");
    write_file($in, $data);
    $out = basename( $in, '.tmp');
    $outfile = $this->path2output.$out;

    if( PHP_OS == 'WIN32' || PHP_OS == 'WINNT')
    {
      exec($this->path2engine.'RepEngine.exe "'.$in.'" "'.$outfile.'"');
    }
    else
    {
      exec('DISPLAY=:100 wine '.$this->path2engine.'RepEngine.exe "'.$in.'" "'.$outfile.'" 2>'.$this->path2engine.'RepEngine.log');
    }

    $response = (object) NULL;
    if (file_exists($outfile.".ok"))
    {
      $response->isSuccessful = TRUE;
      $response->id = $out;
      $response->nama = rawurlencode($param['nama']);
    }
    else
    {
      $response->isSuccessful = FALSE;
      $response->message = 'Laporan gagal dibuat.';
    }
    echo json_encode($response);
  }

  public function view($key = '', $reportname = '')
  {
    $this->load->helper('file');

    $ext = "";
    if( PHP_OS == 'WIN32' || PHP_OS == 'WINNT') $ext = ".tmp";

    if (!file_exists($this->path2cache.$key.$ext)) {
      $this->output->set_status_header(500);
      return;
    }

    $config = file($this->path2cache.$key.$ext);
    $filetype = explode('=', $config[1]);
    $filetype = preg_replace("/[\n\r]/","", $filetype[1]);

    $filename = $key. "." .$filetype;
    $fileerr = $key.".err";

    if (file_exists($this->path2output.$filename)) {
      $outfile = $reportname. "." .$filetype;

      header('Content-Disposition: ' . ($this->attachment == 1 ? 'attachment; ' : '') .'filename="'.$outfile.'"');
      if ($filetype == 'pdf'){
      header('Content-Type: application/pdf');
      } else {
      header('Content-Type: application/xls');
      }
      header('Content-Length: '.filesize($this->path2output.$filename));
      header('Cache-Control: no-store');
      readfile($this->path2output.$filename);
    }
    elseif (file_exists($this->path2output.$fileerr)) {
      $this->output->set_status_header(500);
    }
  }

  function pendaftaran()
  {
    $tipe = $this->input->post('tipe') ? $this->input->post('tipe') : '';
    $id = $this->input->post('id') ? $this->input->post('id') : '0';
    $format = $this->input->post('format') ? $this->input->post('format') : 'pdf';

    if ($tipe == '') return;

    switch ($tipe){
      case 'daftar' :
          $param['fr3'] = 'pendaftaran.fr3';
          $param['nama'] = 'DaftarWajibPajak';
          $param['format'] = $format;
          // ambil query daftar
          $this->load->model('pendaftaran/pendaftaran_model','data_model');
          $query_str = $this->data_model->get_data($param, FALSE, TRUE);
          $query_str = preg_replace("/[\n\r]/"," ", $query_str);
          $param['opt'] = array(
            "SQLFilter=".$query_str
          );
          break;
      case 'form' :
          $param['fr3'] = 'form_daftar.fr3';
          $param['nama'] = 'FormWajibPajak';
          $param['format'] = $format;
          $param['opt'] = array(
            "id=".$id
          );
          break;
    }
    $this->start($param);
  }

}