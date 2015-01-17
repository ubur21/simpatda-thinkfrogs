<?

/* === MANAJEMEN ENTRIAN ======================================================================================= */

/*
konsepnya adalah sebuah halaman entrian diawali dengan "begin_entri" dan ditutup dengan "end_entri"

action = target pengiriman (default diri sendiri)
method = GET/POST (default POST)

hasil entrian :
$_REQUEST['sender'] = entriid >> id entrian
$_REQUEST['save'] = 1 >> tombol save yang ditekan

*/

/* pembuka form entrian */
function begin_entri($entriid, $action="", $method="POST") {
  global $gcms_entri_list;
  if (!is_array($gcms_entri_list)) $gcms_entri_list[0] = $entriid;
  else array_push($gcms_entri_list, $entriid);

  if (!$action) $action = $_SERVER['PHP_SELF'];

  /* pas onload di-resize dulu biar pas */
  gcms_add_on_load($entriid."_resize_entri();");

  echo
        '<script type="text/javascript"> '."\n".

        /* array daftar yang ada di dalam entrian ini */
        'var '.$entriid.'_daftars = new Array(); '."\n".

        /* resize form - agar bentuknya tidak wagu kalau ukuran window diubah */
        'function '.$entriid.'_resize_entri() { '."\n".
        '  var cnt = document.getElementById("content"); '."\n".
        '  var tbl = document.getElementById("tbl_'.$entriid.'"); '."\n".

        '  tbl.style.height = cnt.offsetHeight; '."\n".
        '} '."\n".

        /* cek ukuran form dan auto resize jika ukurannya berubah */
        /* TERPAKSA PAKAI TIMER karena onresize tidak jalan --> coba CARI PENDEKATAN LAIN */
        'setInterval("'.$entriid.'_check_size()", 500); '."\n".

        'var '.$entriid.'_oldw = 0; '."\n".
        'var '.$entriid.'_oldh = 0; '."\n".

        'function '.$entriid.'_check_size() { '."\n".
        '  var form = document.getElementById("'.$entriid.'"); '."\n".
        '  if (('.$entriid.'_oldw != form.offsetWidth) || ('.$entriid.'_oldh != form.offsetHeight)) { '."\n".
        '    '.$entriid.'_resize_entri(); '."\n".
        '    '.$entriid.'_oldw = form.offsetWidth; '."\n".
        '    '.$entriid.'_oldh = form.offsetHeight; '."\n".
        '  } '."\n".
        '} '."\n".

        '</script> '."\n";

  echo
        /* inisiasi form beserta data-data yang mendasar */
        '<form id="'.$entriid.'" action="'.$action.'" method="'.$method.'"> '."\n".
        '<input type="hidden" name="page" value="'.$_REQUEST['page'].'"> '."\n".
        '<input type="hidden" name="action" value="'.$_REQUEST['action'].'"> '."\n".
        '<input type="hidden" name="sender" value="'.$entriid.'"> '."\n".
        '<input id="daftars_'.$entriid.'" type="hidden" name="daftars" value=""> '."\n".
        '<input id="save_'.$entriid.'" type="hidden" name="save" value=""> '."\n".
        '<table id="tbl_'.$entriid.'" style="width: 100%;"><tr><td valign="top">'."\n".
        '<div id="div'.$entriid.'" class="entri" style="height: 100%;"> '."\n";

}

/*
new = url kalo tombol "baru" ditekan, default tombol "baru" invisible
prev = url kalo tombol "mundur" ditekan, default tombol "mundur" invisible
nect = url kalo tombol "maju" ditekan, default tombol "maju" invisible
confirmexp = ekspresi/fungsi yang mengembalikan TRUE kalau lanjut simpan, FALSE  batal simpan
             dialog warning dll bisa dibikin dalam fungsi tersebut
*/

/* penutup form entrian */
function end_entri($entriid, $new="", $prev="", $next="", $confirmexp="", $okbtn = false) {
  global $gcms_entri_list;

  echo
        '</div>'."\n".
        '</td></tr>'."\n".
        '<tr><td valign="bottom">'."\n".
        '<div id="buttons_'.$entriid.'" class="entri_buttons"><table style="width: 100%">'."\n".
        '<tr><td align="left">'."\n";

  /* tombol-tombol sebelah kiri */
  if ($prev || $next) {
    echo 
        '<input id="btn_'.$entriid.'_prev" type="button" value="Mundur"> '.
        '<input id="btn_'.$entriid.'_next" type="button" value="Maju"> &nbsp;&nbsp;&nbsp; '.

        '<script type="text/javascript"> '."\n".

        'function btn_'.$entriid.'_dummy_click() {} '."\n".

        'function btn_'.$entriid.'_prev_click() {'."\n".
        '  window.location = '."'".$prev."'".'; '."\n".
        '} '."\n".
        'gcms_yui_button("btn_'.$entriid.'_prev", '.($prev ? 'btn_'.$entriid.'_prev_click' : 'btn_'.$entriid.'_dummy_click').'); '."\n".

        'function btn_'.$entriid.'_next_click() {'."\n".
        '  window.location = '."'".$next."'".'; '."\n".
        '} '."\n".
        'gcms_yui_button("btn_'.$entriid.'_next", '.($next ? 'btn_'.$entriid.'_next_click' : 'btn_'.$entriid.'_dummy_click').'); '."\n".

        '</script> '."\n";
  }
  if ($new) {
    echo 
        '<input id="btn_'.$entriid.'_new" type="button" value="Baru"> ';
    echo
        '<script type="text/javascript"> '."\n".

        'function btn_'.$entriid.'_new_click() {'."\n".
        '  window.location = '."'".$new."'".'; '."\n".
        '} '."\n".
        'gcms_yui_button("btn_'.$entriid.'_new", btn_'.$entriid.'_new_click); '."\n".

        '</script> '."\n";
  }

  echo
        '</td><td align="right">'."\n";

  /* tombol-tombol sebelah kanan */
  echo 
        '<input id="btn_'.$entriid.'_save" type="button" value="'.($okbtn ? 'Oke' : 'Simpan').'"> '.
        '<input id="btn_'.$entriid.'_tutup" type="button" value="Tutup"> ';

  echo
        '</td></tr></table></div>'."\n".
        '</td></tr>'."\n".
        '</table></form>'."\n";

  echo
        '<script type="text/javascript"> '."\n".

        /* simpan data - form disubmit */
        'function '.$entriid.'_save() { '."\n".
        '  if ('.($confirmexp ? $confirmexp : "true").') { '."\n".
        '    save = document.getElementById('."'save_".$entriid."'".'); '."\n".
        '    save.value = "1"; '."\n".
        '    daftars = document.getElementById('."'daftars_".$entriid."'".'); '."\n".
        '    daftars.value = ""; '."\n".
        '    for (i = 0; i < '.$entriid.'_daftars.length; i ++) { '."\n".
        '      daftars.value += '.$entriid.'_daftars[i] + ","; '."\n".
        '      eval('.$entriid.'_daftars[i] + "Submit()"); '."\n".
        '    } '."\n".
        '    document.getElementById('."'".$entriid."'".').submit(); '."\n".
        '    save.value = ""; '."\n".
        '  } '."\n".
        '} '."\n".

        'function '.$entriid.'_tutup() { '."\n".
        '  window.close(); '."\n".          
        '} '."\n".

        'gcms_yui_button("btn_'.$entriid.'_save", '.$entriid.'_save); '."\n".
        'gcms_yui_button("btn_'.$entriid.'_tutup", '.$entriid.'_tutup); '."\n".

        '</script> '."\n";

    echo
        '<style type="text/css"> '."\n".
        '.yui-button#btn_'.$entriid.'_prev button { '."\n".
      	'background: url(images/previous.png) 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '.yui-button#btn_'.$entriid.'_next button { '."\n".
      	'background: url(images/next.png) 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '.yui-button#btn_'.$entriid.'_new button { '."\n".
      	'background: url(images/new.png) 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '.yui-button#btn_'.$entriid.'_save button { '."\n".
      	'background: url(images/'.($okbtn ? 'accept.png' : 'save.png').') 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '.yui-button#btn_'.$entriid.'_tutup button { '."\n".
      	'background: url(images/cancel.png) 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '</style> '."\n";

  if (is_array($gcms_entri_list)) array_pop($gcms_entri_list);
}

/**/
function end_entri_default($entriid, $table, $idfield, $currentid, $confirmexp="") {
  $new = 'form.php?page='.$_REQUEST['page'].'&action=edit';

  $csql = "select max(".$idfield.") as id from ".$table." where ".$idfield." < ".$currentid;
  $nresult=gcms_query($csql);
  $id = gcms_fetch_object($nresult)->id;
  if ($id) $prev = 'form.php?page='.$_REQUEST['page'].'&action=edit&id='.$id;
  else $prev = "";

  $csql = "select min(".$idfield.") as id from ".$table." where ".$idfield." > ".$currentid;
  $nresult=gcms_query($csql);
  $id = gcms_fetch_object($nresult)->id;
  if ($id) $next = 'form.php?page='.$_REQUEST['page'].'&action=edit&id='.$id;
  else $next = "";

  end_entri($entriid, $new, $prev, $next, $confirmexp);

}

/* penutup form entrian */
function end_entri_ok($entriid, $confirmexp="") {
  end_entri($entriid, "", "", "", $confirmexp, true);
}

function create_pilih_button($button_id, $onclick) {
  echo 
        '<input id="'.$button_id.'" type="button">';
  echo
        '<script type="text/javascript"> '."\n".

        'function '.$button_id.'_click() {'."\n".
        '  '.$onclick.'; '."\n".
        '} '."\n".
        'gcms_yui_button("'.$button_id.'", '.$button_id.'_click); '."\n".

        '</script> '."\n";
    echo
        '<style type="text/css"> '."\n".
        '.yui-button#'.$button_id.' button { '."\n".
        'background: url(images/picker.png) center center no-repeat; '."\n".
        'text-indent: -4em; '."\n".
        'overflow: hidden; '."\n".
        'padding: 0 .75em; '."\n".
        'width: 2em; '."\n".
        '*margin-left: 4em; '."\n".
        '*padding: 0 1.75em; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '</style> '."\n";
}


?>