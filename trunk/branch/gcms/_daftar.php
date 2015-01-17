<?

/* === MANAJEMEN DAFTAR ================================================================================================== */

/* membuat sebuah daftar

  daftarid = id daftar, harus unik

  coldefs = definisi kolom menurut aturan YUI -->'{key:"Field1",label:"Label1"}, {key:"Field2",label:"Label2"}, ... '
  
  schema = skema data JSON menurut aturan YUI --> 'resultsList: "XXX", fields: ["Field1","Field2",...]'
  
  idfield = field yang jadi id (untuk delete, etc)
  rowselect = pilih baris (true) atau sel (false)
  filter = filter yang dipakai --- KONSEPNYA PERLU DIPERBAIKI !!!
  addproc = perintah JS yang dipanggil kalo tombol add dipencet, kosong maka tombol add nggak tampil
  editproc = perintah JS yang dipanggil kalo tombol edit dipencet, kosong maka tombol edit nggak tampil
  candel = bisa hapus (true) atau tidak (false)

  fungsi-fungsi javaskrip daftar:

  DAFTAR_refresh() = refresh daftar
  DAFTAR_get_selected(field) = ambil nilai field untuk baris yang di-select

  semua request untuk isi daftar, hapus dll akan diambil dari "extensions/MODUL/MODUL.request.php"
  dengan parameter request berikut:

  $_REQUEST['sender'] = daftarid >> id daftar
  $_REQUEST['action'] = "refresh" >> refresh - harus mengembalikan data dalam bentuk JSON
                        "delete" >> hapus data - kembalikan teks warning/error kalau ada error 
                                                  tidak mengembalikan apa-apa kalau sukses

  $_REQUEST['id'] = isi dari ID-FIELD yang terpilih, hanya ada isinya untuk action "hapus"

  -----------------------------------------------------------------------------------------

  Format JSON untuk ambil data / refresh
  
  { "XXX": [ { "Field1":"Data11", "Field2":"Data12", ... }, 
             { "Field1":"Data21", "Field2":"Data22", ... }, 
             ... 
           ] }

*/

/* membuat daftar standar */
function gcms_create_daftar($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, $addproc="", $editproc="", $candel=true, $extraparam="") {  
  gcms_create_daftar_int1($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, $extraparam);
  gcms_create_daftar_int2($daftarid, $idfield, $addproc, $editproc, $candel);
}

/* membuat daftar standar dengan fungsi membuka form entri secara default */
function gcms_create_daftar_default($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, $canadd=true, $canedit=true, $candel=true, $extraparam="") {  
  if ($canadd || $canedit) {
    echo
        '<script type="text/javascript"> '."\n".
        'function '.$daftarid.'OpenEntriWindow(id) { '."\n".
        '  if (id) gcms_open_form("form.php?page='.$_REQUEST['page'].'&action=edit&id="+id, "", 700, 500); '."\n".
        '} '."\n".
        '</script> '."\n";
  }
  if ($canadd) $addproc = $daftarid."OpenEntriWindow('0')";
  else $addproc = "";
  if ($canedit) $editproc = $daftarid."OpenEntriWindow(".$daftarid."_get_selected('".$idfield."'))";
  else $editproc = "";

  gcms_create_daftar_int1($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, $extraparam);
  gcms_create_daftar_int2($daftarid, $idfield, $addproc, $editproc, $candel);

}

/* membuat daftar standar default - hanya beda parameter dengan versi sebelumnya */
function gcms_create_daftar_default_2($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, $extraparam="") {
  gcms_create_daftar_default($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, true, true, true, $extraparam);
}

/* membuat daftar untuk form pilihan */
function gcms_create_daftar_pilih($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, $extraparam="") {  
  gcms_create_daftar_int1($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, $extraparam);
  gcms_create_daftar_int3($daftarid, $idfield);
}


/* membuat daftar yang bisa diedit */
function gcms_create_daftar_edit($daftarid, $coldefs, $schema, $idfield, $canadd=true, $candel=true, $width="", $height="", $extraparam="") {  
  global $gcms_entri_list;

  if (is_array($gcms_entri_list) && (count($gcms_entri_list) > 0)) {
    $entriid = array_pop($gcms_entri_list);
    array_push($gcms_entri_list, $entriid);

    /* inisialisasi -- konek-kan ke entrianya */
    echo
        '<input type="hidden" id="'.$daftarid.'_data" name="'.$daftarid.'_data" value=""> '."\n".
        '<input type="hidden" id="'.$daftarid.'_deleted" name="'.$daftarid.'_deleted" value=""> '."\n".

        '<script type="text/javascript"> '."\n".

        /* register ke entriannya */
        ''.$entriid.'_daftars['.$entriid.'_daftars.length] = "'.$daftarid.'"; '."\n".

        /* mekanisme kalau entriannya mau disubmit - pindahin isi daftar ke dalam field data */
        'function '.$daftarid.'Submit() { '."\n". 
        '  var data = document.getElementById("'.$daftarid.'_data"); '."\n".
        '  if (data) { '."\n".
        '    data.value = ""; '."\n".
        '    cols = '.$daftarid.'.getDataSource().responseSchema.fields; '."\n".
        '    for (j = 0; j < cols.length; j ++) { '."\n".
        '      data.value += cols[j] + ","; '."\n".
        '    } '."\n".
        '    data.value += "|"; '."\n".
        '    rows = '.$daftarid.'.getRecordSet(); '."\n".
        '    for (i = 0; i < rows.getLength(); i ++) { '."\n".
        '      for (j = 0; j < cols.length; j ++) { '."\n".
        '        s = rows._records[i].getData(cols[j]); '."\n".
        '        if (!s) s = ""; '."\n".
        '        s = s.replace(".", ".dot."); '."\n".
        '        s = s.replace(",", ".comma."); '."\n".
        '        s = s.replace("|", ".bar."); '."\n".
        '        data.value += s + ","; '."\n".
        '      } '."\n".
        '      data.value += "|"; '."\n".
        '    } '."\n".
        '  } '."\n".
	      '} '."\n".

        '</script> '."\n";
  }
  else $entriid = "";

  gcms_create_daftar_full($daftarid, $coldefs, $schema, $idfield, false, "", "", true, $width, $height, $extraparam);

  if ($canadd) {
    echo
        '<script type="text/javascript"> '."\n".
        'function '.$daftarid.'AddRow() { '."\n". 
        '  var data = {}; '."\n".
	      '  var record = YAHOO.widget.DataTable._cloneObject(data); '."\n". 
	      '  record.row = '.$daftarid.'.getRecordSet().getLength(); '."\n". 
	      '  '.$daftarid.'.addRow(record); '."\n". 
        '} '."\n".
        '</script> '."\n";
    $addproc = $daftarid."AddRow()";
  }
  else $addproc = "";

  if ($candel) {
    echo
        '<script type="text/javascript"> '."\n";

    echo
        'function '.$daftarid.'DelRow() { '."\n". 
        '  var cells = '.$daftarid.'.getSelectedCells(); '."\n".
        '  record = '.$daftarid.'.getRecord(cells[0].recordId); '."\n".
        '  if (!record) return ""; '."\n".
        '  id = record.getData('."'".$idfield."'".'); '."\n";

    if ($entriid) {
      echo
        '  var deleted = document.getElementById("'.$daftarid.'_deleted"); '."\n".
        '  if (deleted) { '."\n".
        '    if (deleted.value) deleted.value += ","; '."\n".
        '    deleted.value += id; '."\n".
        '  } '."\n";
    }

    echo
        '  '.$daftarid.'.deleteRow(record); '."\n".
	      '} '."\n".

        '</script> '."\n";
    $delproc = $daftarid."DelRow()";
  }
  else $delproc = "";
  gcms_create_daftar_int2($daftarid, $idfield, $addproc, "", $candel, $delproc, false, true);
}

/* membuat daftar yang bisa diedit - cuma beda susunan parameter dengan versi sebelumnya */
function gcms_create_daftar_edit_2($daftarid, $coldefs, $schema, $idfield, $extraparam="") {  
  gcms_create_daftar_edit($daftarid, $coldefs, $schema, $idfield, true, true, "", "", $extraparam);
}

/* teks filter standar */
function gcms_daftar_text_filter($daftarid) { 
  return
  '<table style="width: 100%">'."\n".
  '<tr><td width="1">'."\n".
  '<select id="'.$daftarid.'_text_filter_mode"> '."\n".
  '<option value="1" selected>Diawali</option> '."\n".
  '<option value="2">Memuat</option> '."\n".
  '</select></td>'."\n".
  '<td><input id="'.$daftarid.'_text_filter" style="width: 100%"></td>'."\n".
  '</tr>'."\n".
  '</table>'."\n";
}

/* ---------- INTERNAL --------------------------------------------------------------------------------------------------- */

/* internal - bikin bagian atasnya */
function gcms_create_daftar_int1($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, $extraparam="") {
  gcms_create_daftar_full($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, "", false, "", "", $extraparam);
}

/* fully customized daftar */
function gcms_create_daftar_full($daftarid, $coldefs, $schema, $idfield, $rowselect, $filter, $selproc="", $editable=false, $width="", $height="", $extraparam="") {  
  global $daftar_head_added, $gcms_entri_list;

  /* menambahkan head daftar dari yui */
  if (!$daftar_head_added) {
    $head = '<link rel="stylesheet" type="text/css" href="yui/build/datatable/assets/skins/sam/datatable.css" />'.
            '<script type="text/javascript" src="yui/build/datatable/datatable-beta-min.js"></script>';
    gcms_add_to_head($head);
    $daftar_head_added = true;
  }

  /* menggenerate java skrip yang dipake */
  echo
        '<script type="text/javascript">'."\n";

  if ($editable) {
    echo
        'function '.$daftarid.'SelectCell(oArgs) { '."\n".
        '  var elTarget = oArgs.target; '."\n".
        '  var cells = '.$daftarid.'.getSelectedCells(); '."\n".
        '  var elTargetCell = '.$daftarid.'.getTdEl(elTarget); '."\n".
        '  if(elTargetCell) { '."\n".
        '    if (('.$daftarid.'.getRecord('.$daftarid.'.getTrEl(elTargetCell)) == '.$daftarid.'.getRecord(cells[0].recordId)) && '."\n".
        '        ('.$daftarid.'.getColumn(elTargetCell)._sId == cells[0].columnId)) { '."\n".
        '      '.$daftarid.'.onEventShowCellEditor(oArgs); '."\n".
        '    } '."\n".
        '  } '."\n".
        '} '."\n";
  }

  echo
        'YAHOO.util.Event.addListener(window, "load", function() {'."\n";

  if (is_array($gcms_entri_list) && (count($gcms_entri_list) > 0)) {
    $entriid = array_pop($gcms_entri_list);
    array_push($gcms_entri_list, $entriid);
    echo
        '        var ytrim = document.getElementById("buttons_'.$entriid.'").offsetHeight; '."\n";
  }
  else {
    echo
        '        var ytrim = 0;'."\n";
  }

  echo
        /* fungsi untuk daftar... */
        '    func_'.$daftarid.' = new function() {'."\n".
        '        var col_'.$daftarid.' = ['.$coldefs.'];'."\n".

        '        this.ds_'.$daftarid.' = new YAHOO.util.DataSource("request.php?");'."\n".
        '        this.ds_'.$daftarid.'.responseType = YAHOO.util.DataSource.TYPE_JSON;'."\n".
        '        this.ds_'.$daftarid.'.connXhrMode = "queueRequests";'."\n".
        '        this.ds_'.$daftarid.'.responseSchema = {'.$schema.'};'."\n".

        '        var form = document.getElementById("form_'.$daftarid.'");'."\n".
        '        var cnt = form.parentNode; '."\n".
        '        var w = form.offsetWidth - 2; '."\n";
  if ($height) {
    echo
        '    var h = '.$height.';'."\n";
  }
  else
  {
    echo
        '        var h = cnt.offsetHeight - form.offsetTop '.($filter ? '- document.getElementById("filter_'.$daftarid.'").offsetHeight' : '').
        '                - document.getElementById("buttons_'.$daftarid.'").offsetHeight - ytrim; '."\n";
  }
  echo
        '        var oConfigs = { '."\n".
        '          scrollable: true, '."\n".
        '          height: h.toString(), '."\n".
        '          width: w.toString(), '."\n".
        '          initialRequest: "'.
          ($_REQUEST['page'] ? 'page='.$_REQUEST['page'] : ($_REQUEST['mod'] ? 'mod='.$_REQUEST['mod'] : '')).
          '&sender='.$daftarid.'&action=refresh'.($extraparam ? '&'.$extraparam : '').'" '."\n".
        '        }; '."\n".
  
        '        '.$daftarid.' = new YAHOO.widget.DataTable("div_'.$daftarid.'", col_'.$daftarid.', this.ds_'.$daftarid.','."\n".
        '                oConfigs); '."\n".
        '        '.$daftarid.'.subscribe("widthChange", '.$daftarid.'_size_change);'."\n".
        '        '.$daftarid.'.subscribe("heightChange", '.$daftarid.'_size_change);'."\n".
        '        '.$daftarid.'_size_change();'."\n";
  
  if ($rowselect) {
    echo
        '        // Subscribe to events for row selection'."\n".
        '        '.$daftarid.'.subscribe("rowMouseoverEvent", '.$daftarid.'.onEventHighlightRow);'."\n".
        '        '.$daftarid.'.subscribe("rowMouseoutEvent", '.$daftarid.'.onEventUnhighlightRow);'."\n";
    if ($selproc) echo
        '        '.$daftarid.'.subscribe("rowClickEvent", '.$selproc.');'."\n";
    echo
        '        '.$daftarid.'.subscribe("rowClickEvent", '.$daftarid.'.onEventSelectRow);'."\n";
  }
  else {
    echo
        '        // Subscribe to events for cell selection'."\n".  
        '        '.$daftarid.'.subscribe("cellMouseoverEvent", '.$daftarid.'.onEventHighlightCell);'."\n". 
        '        '.$daftarid.'.subscribe("cellMouseoutEvent", '.$daftarid.'.onEventUnhighlightCell);'."\n".
        '        '.$daftarid.'.subscribe("cellSelectEvent", '.$daftarid.'.clearTextSelection);'."\n";
    if ($selproc) echo
        '        '.$daftarid.'.subscribe("cellClickEvent", '.$selproc.');'."\n";
    if ($editable) {
      echo
        '        '.$daftarid.'.subscribe("cellClickEvent", '.$daftarid.'SelectCell); '."\n";

      echo
        '        '.$daftarid.'.subscribe("editorUpdateEvent", function(oArgs) { '."\n".
        '            if(oArgs.editor.column.key === "active") { '."\n".
        '                this.saveCellEditor(); '."\n".
        '            } '."\n".
        '        }); '."\n".
        '        '.$daftarid.'.subscribe("editorBlurEvent", function(oArgs) { '."\n".
        '            this.cancelCellEditor(); '."\n".
        '        }); '."\n";
    }
    echo
        '        '.$daftarid.'.subscribe("cellClickEvent", '.$daftarid.'.onEventSelectCell);'."\n";
  }

  echo
        '        // Programmatically bring focus to the instance so arrow selection works immediately'."\n".
        '        '.$daftarid.'.focus();'."\n".

        '    };'."\n".

        '});'."\n".

        /* cek size dan sesuaikan ukuran grid - BELUM BERHASIL */
        'var '.$daftarid.'_oldw = 0; '."\n".
        'var '.$daftarid.'_oldh = 0; '."\n".

        'function form_'.$daftarid.'_check_size() { '."\n".
        '  var form = document.getElementById("form_'.$daftarid.'"); '."\n".
        '  var cnt = form.parentNode; '."\n".
        '  if (('.$daftarid.'_oldw != cnt.offsetWidth) || ('.$daftarid.'_oldh != cnt.offsetHeight)) { '."\n";
        '    var w = form.offsetWidth - 2; '."\n";
  if ($height) {
    echo
        '    var h = '.$height.';'."\n";
  }
  else
  {
    echo
        '    var h = cnt.offsetHeight - form.offsetTop '.($filter ? '- document.getElementById("filter_'.$daftarid.'").offsetHeight' : '').
        '                - document.getElementById("buttons_'.$daftarid.'").offsetHeight - ytrim; '."\n";
  }
  echo
//        '    '.$daftarid.'.height = h.toString(); '."\n".
//        '    '.$daftarid.'.width = w.toString(); '."\n".
//        '    '.$daftarid.'.render(); '."\n".
        '    '.$daftarid.'_oldw = cnt.offsetWidth; '."\n".
        '    '.$daftarid.'_oldh = cnt.offsetHeight; '."\n".
        '    '.$daftarid.'_size_change();'."\n".
        '  } '."\n".
        '} '."\n".

        /* tangkap perubahan ukuran grid */
        'function '.$daftarid.'_size_change(eventInfo) { '."\n".
        '        var tbl = document.getElementById("div_'.$daftarid.'");'."\n".
        '        var tblin = tbl.firstChild;'."\n".
        '        var fakehead = document.getElementById("fakehead_'.$daftarid.'");'."\n".
        '        fakehead.style.left = tbl.offsetLeft;'."\n".
        '        fakehead.style.width = tbl.offsetWidth;'."\n".
        '        fakehead.style.top = tblin.offsetTop + 1;'."\n".
        '        fakehead.style.height = tblin.offsetHeight - 1;'."\n".
        '} '."\n".

        /* kalau grid di-refresh... */
        'function '.$daftarid.'_refresh() { '."\n".
        '  '.$daftarid.'.showTableMessage('.$daftarid.'.MSG_LOADING); '."\n".
        '  var callback1 = { '."\n".
        '    success : '.$daftarid.'.onDataReturnInitializeTable, '."\n".
        '    failure : '.$daftarid.'.onDataReturnInitializeTable, '."\n".
        '    scope : '.$daftarid.' '."\n".
        '  }; '."\n".
        '  '.$daftarid.'.getDataSource().sendRequest("page='.$_REQUEST['page'].'&sender='.$daftarid.'&action=refresh" + '.$daftarid.'_get_filter(), callback1); '."\n".
        '} '."\n".

        /* ambil isi filter - MASIH BELUM CLEAN KODENYA */
        'function '.$daftarid.'_get_filter() { '."\n".
        '  var text_filter = document.getElementById("'.$daftarid.'_text_filter").value '."\n".
        '  var text_filter_mode = document.getElementById("'.$daftarid.'_text_filter_mode").value '."\n".
        '  var filter = "" '."\n".
        '  if (text_filter) { '."\n".
        '    filter = "&text_filter=" + text_filter + "&text_filter_mode=" + text_filter_mode '."\n".
        '  }; '."\n".
        '  return filter; '."\n".
        '} '."\n".

        /* ambil data yang dipilih di grid */
        'function '.$daftarid.'_get_selected(field) { '."\n".
        '  var selected = '.$daftarid.'.getSelectedRows(); '."\n".
        '  if (!selected) return ""; '."\n".
        '  var record = '.$daftarid.'.getRecord(selected[0]); '."\n".
        '  if (!record) return ""; '."\n".
        '  return record.getData(field); '."\n".
        '} '."\n".

        '</script>'."\n";

        /* bikin tampilannya */
  echo 
        '<div id="form_'.$daftarid.'" style="'.($width ? 'width: '.$width.';' : '').($height ? 'height: '.$height.';' : '').'">'."\n";

  if ($filter) {
    echo
        '<div id="filter_'.$daftarid.'" class="daftar_filter"><table style="width: 100%"><tr><td>'.$filter.'</td>'."\n".
        '<td width="1" align="right" valign="top"><input id="btn_'.$daftarid.'_refresh" type="button" value="Refresh">'."\n".
        '</td></tr></table></div>'."\n";
    echo
        '<script type="text/javascript"> '."\n".
        'gcms_yui_button("btn_'.$daftarid.'_refresh", '.$daftarid.'_refresh); '."\n".
        '</script> '."\n";
    echo
        '<style type="text/css"> '."\n".
        '.yui-button#btn_'.$daftarid.'_refresh button { '."\n".
      	'background: url(images/reload.png) 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '</style> '."\n";
  }

  echo
        '<div id="fakehead_'.$daftarid.'" class="fake_head"></div>'."\n".
        '<div id="div_'.$daftarid.'" class="daftar"></div>'."\n";

}

/* internal - bikin bagian bawahnya untuk daftar biasa */
function gcms_create_daftar_int2($daftarid, $idfield, $addproc, $editproc, $candel, $delproc="", $tutup=true, $alticon=false) {  

 echo
        '<div id="buttons_'.$daftarid.'" class="daftar_buttons"><table style="width: 100%"><tr><td align="left">'."\n";

  if ($addproc) {
    echo 
        '<input id="btn_'.$daftarid.'_add" type="button" value="Tambah"> ';
    echo
        '<script type="text/javascript"> '."\n".

        'function btn_'.$daftarid.'_add_click() {'."\n".
        ''.$addproc.'; '."\n".
        '} '."\n".
        'gcms_yui_button("btn_'.$daftarid.'_add", btn_'.$daftarid.'_add_click); '."\n".

        '</script> '."\n";
  }
  if ($editproc) {
    echo 
        '<input id="btn_'.$daftarid.'_edit" type="button" value="Ubah"> ';
    echo
        '<script type="text/javascript"> '."\n".

        'function btn_'.$daftarid.'_edit_click() {'."\n".
        ''.$editproc.'; '."\n".
        '} '."\n".
        'gcms_yui_button("btn_'.$daftarid.'_edit", btn_'.$daftarid.'_edit_click); '."\n".

        '</script> '."\n";
  }
  if ($candel) {
    echo 
        '<input id="btn_'.$daftarid.'_del" type="button" value="Hapus"> ';
    if (!$delproc) {
      echo
        '<script type="text/javascript">'."\n".

        /* kalau grid di-delete... */
        'function '.$daftarid.'_delete() { '."\n".
        '  id = '.$daftarid.'_get_selected('."'".$idfield."'".'); '."\n".
        '  if (id) { '."\n".
        '    if (confirm("Hapus data tersebut?")) { '."\n".
        '      gcms_ajax_request("request.php?'.
          ($_REQUEST['page'] ? 'page='.$_REQUEST['page'] : ($_REQUEST['mod'] ? 'mod='.$_REQUEST['mod'] : '')).
          '&sender='.$daftarid.'&action=delete'.($extraparam ? '&'.$extraparam : '').'&id=" + id, '.$daftarid.'_delete_ok, '.$daftarid.'_delete_fail); '."\n".
        '    } '."\n".
        '  } '."\n".
        '} '."\n".

        /* delete request ok, kalo kembaliannya kosong brarti sukses, kalau tidak tampilkan itu pesan error */
        'function '.$daftarid.'_delete_ok(retval) { '."\n".
        '  if (trim(retval) != "") window.alert(trim(retval)); '."\n".
        '  else '.$daftarid.'_refresh(); '."\n".
        '} '."\n".

        /* delete request failed, error jaringan? */
        'function '.$daftarid.'_delete_fail(retval) { '."\n".
        '  window.alert("Request Failed!"); '."\n".
        '} '."\n".
        '</script> '."\n";
    }
    else {
      echo
        '<script type="text/javascript"> '."\n".
        'function '.$daftarid.'_delete() {'."\n".
        '  if (confirm("Hapus data tersebut?")) { '."\n".
        '    '.$delproc.'; '."\n".
        '  } '."\n".
        '} '."\n".
        '</script> '."\n";
    }
    echo
        '<script type="text/javascript">'."\n".
        'gcms_yui_button("btn_'.$daftarid.'_del", '.$daftarid.'_delete); '."\n".
        '</script> '."\n";
  }
  echo
        '</td><td align="right">'."\n";

  if ($tutup) {
    echo
        '<input id="btn_'.$daftarid.'_tutup" type="button" value="Tutup"> ';
    echo
        '<script type="text/javascript">'."\n".

        'function '.$daftarid.'_tutup() { '."\n".
        '  window.close(); '."\n".          
        '} '."\n".

        'gcms_yui_button("btn_'.$daftarid.'_tutup", '.$daftarid.'_tutup); '."\n".

        '</script>'."\n";
  }
  echo
        '</td></tr></table></div>'."\n".
        '</div>'."\n";

  echo
        '<style type="text/css"> '."\n".
        '.yui-button#btn_'.$daftarid.'_add button { '."\n".
      	'background: url(images/'.($alticon ? 'plus.png' : 'new.png').') 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '.yui-button#btn_'.$daftarid.'_edit button { '."\n".
      	'background: url(images/edit.png) 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '.yui-button#btn_'.$daftarid.'_del button { '."\n".
      	'background: url(images/'.($alticon ? 'minus.png' : 'trash.png').') 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '.yui-button#btn_'.$daftarid.'_tutup button { '."\n".
      	'background: url(images/cancel.png) 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '</style> '."\n";
}

/* internal - bikin bagian bawahnya untuk daftar di pilihan */
function gcms_create_daftar_int3($daftarid, $idfield) {  

  echo
        '<div id="buttons_'.$daftarid.'" class="daftar_buttons"><table style="width: 100%"><tr><td align="left">'."\n";

  echo
        '</td><td align="right">'."\n".
        '<input id="btn_'.$daftarid.'_pilih" type="button" value="Pilih"> '.
        '<input id="btn_'.$daftarid.'_tutup" type="button" value="Tutup"> ';

  echo
        '</td></tr></table></div>'."\n".
        '</div>'."\n";

  echo
        '<script type="text/javascript">'."\n".

        /* kalau dipilih */
        'function '.$daftarid.'_pilih() { '."\n".
        '  doc = window.gcmsPilihResultDoc; '."\n".
        '  data = window.gcmsPilihResultData; '."\n".
        '  for (i = 0; i < data.length; i += 2) { '."\n".
        '    id = '.$daftarid.'_get_selected('."'".$idfield."'".'); '."\n".
        '    if (id) { '."\n".
        '      s = '.$daftarid.'_get_selected(data[i + 1]); '."\n".
        '      if(!s) s = ""; '."\n".
        '      doc.getElementById(data[i]).value = s; '."\n".
        '    } '."\n".
        '  } '."\n".
        '  window.close(); '."\n".          
        '} '."\n".
        'function '.$daftarid.'_tutup() { '."\n".
        '  window.close(); '."\n".          
        '} '."\n".

        'gcms_yui_button("btn_'.$daftarid.'_pilih", '.$daftarid.'_pilih); '."\n".
        'gcms_yui_button("btn_'.$daftarid.'_tutup", '.$daftarid.'_tutup); '."\n".

        '</script>'."\n";

    echo
        '<style type="text/css"> '."\n".
        '.yui-button#btn_'.$daftarid.'_pilih button { '."\n".
      	'background: url(images/accept.png) 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '.yui-button#btn_'.$daftarid.'_tutup button { '."\n".
      	'background: url(images/cancel.png) 10% 50% no-repeat; '."\n".
        'padding-left: 2em; '."\n".
        '} '."\n".
        '</style> '."\n";
}

?>