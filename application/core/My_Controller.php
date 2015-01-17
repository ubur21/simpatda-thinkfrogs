<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
  Base class digunakan untuk semua modul
*/

class Base_Controller extends CI_Controller
{
  public $app;
  public $user;
  public $access;
  public $pemda;

  public function __construct()
  {
    parent::__construct();

    $this->app = array(
      'app_name' => 'SIMPATDA',
      'app_fullname' => 'Sistem Informasi Pendapatan Daerah',
      'app_copyright' => 'solusiti',
    );

    $this->user = array(
      'user_name' => 'admin',
      'user_fullname' => 'Administrator',
    );
    
    $this->pemda = array(
      'pemda_name' => 'Kabupaten Nusantara',
      'pemda_location' => 'Khatulistiwa',
    );
    
    $this->access = 3;
  }

}
?>
