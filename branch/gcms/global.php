<?
session_start(); 
session_regenerate_id();

/* ambil konfigurasi */
require_once('config.php');

/* kalau belum ada struktur database-nya, bikin dulu */
/*$nresult = gcms_list_tables("");
$o = gcms_fetch_row($nresult);
if (!$o) {
  $cfile = "gcms.sql";
  $fsql = @fopen($cfile, "r"); 
	$csql = fread($fsql, filesize($cfile)); 
	fclose($fsql); 
  gcms_run_query_block($csql);
}*/

/* ambil library berisi sekumpulan fungsi mendasar */
require_once('lib.php');

/* pre processing untuk data daftar yang di-pass lewat url */
$daftars = explode(",", $_REQUEST['daftars']);
foreach($daftars as $daftar) {
  if ($daftar) {
    global ${$daftar.'_data'}, ${$daftar.'_deleted'};

    /* ubah data daftar ke dalam bentuk array */
    ${$daftar.'_data'} = array();
    $rows = explode("|", $_REQUEST[$daftar.'_data']);
    $fields = explode(",", $rows[0]);
    for ($i = 1; $i < count($rows); $i ++) {
      if ($rows[$i]) {
        $data = explode(",", $rows[$i]);
        $d = array();
        for ($j = 0; $j < count($fields); $j ++) {
          if ($fields[$j]) {
            $s = $data[$j];
            $s = str_replace(".bar.", "|", $s);
            $s = str_replace(".comma.", ",", $s);
            $s = str_replace(".dot.", ".", $s);
            $d[$fields[$j]] = $s;
            $d[strtolower($fields[$j])] = $s;
            $d[strtoupper($fields[$j])] = $s;
          }
        }
        ${$daftar.'_data'}[] = $d;
      }
    }

    /* ubah id data yang dihapus ke dalam bentuk array */
    ${$daftar.'_deleted'} = array();
    $ds = explode(",", $_REQUEST[$daftar.'_deleted']);
    foreach ($ds as $d) 
      if ($d) ${$daftar.'_deleted'}[] = $d;
  }
}
  
/* pada awalnya, untuk konek ke ekstensi disini, yaitu melalui 'page' yang akan menuju ke fungsi terkait di ekstensi
 * ke depan perlu mekanisme baru yang memungkinkan suatu ekstensi diakses tanpa lewat menu seperti terjadi di request data, 
 * buka pilihan dari modul lain, dll
 * 
 * --------------- PERLU DIPIKIRKAN DAN DIBENAHI LAGI ------------------
 */

/* perlu reverse untuk pemanggilan menggunakan nama modul dan fungsi - backward compatibility */
/*if (!$_REQUEST['page'] && $_REQUEST['mod'] && $_REQUEST['func']) {
	if(b_antisqlinjection($_REQUEST['mod']) && b_antisqlinjection($_REQUEST['func'])) {
    $func = $_REQUEST['func'];
	  $csql="select * from g_frontmenus where cfunction = '".$func."'";
		$page = gcms_fetch_object(gcms_query($csql))->nid;
    if (!$page) {
      $func = "m_".$_REQUEST['mod']."_".$_REQUEST['func'];
	    $csql="select * from g_frontmenus where cfunction = '".$func."'";
		  $page = gcms_fetch_object(gcms_query($csql))->nid;
    }
    $_REQUEST['page'] = $page;
  }
}*/

?>