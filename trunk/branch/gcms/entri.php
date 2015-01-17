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
  
  global $extra_style_entri;
  $extra = str_replace("xxxx",$daftarid,$extra_style_entri);
	
  gcms_add_to_head($extra);
  
  if (!is_array($gcms_entri_list)) $gcms_entri_list[0] = $entriid;
  else array_push($gcms_entri_list, $entriid);

  if (!$action) $action = $_SERVER['PHP_SELF'];

  /* pas onload di-resize dulu biar pas */
  gcms_add_on_load($entriid."_resize_entri()");

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
        '<form id="'.$entriid.'" name="'.$entriid.'" action="'.$action.'" method="'.$method.'"> '."\n".
        '<input type="hidden" name="page" value="'.$_REQUEST['page'].'"> '."\n".
        '<input type="hidden" name="action" value="'.$_REQUEST['action'].'"> '."\n".
        '<input type="hidden" name="sender" value="'.$entriid.'"> '."\n".
        '<input id="daftars_'.$entriid.'" type="hidden" name="daftars" value=""> '."\n".
        '<input id="save_'.$entriid.'" type="hidden" name="save" value=""> '."\n".
        '<table id="tbl_'.$entriid.'" style="width: 100%;"><tr height="100%"><td valign="top">'."\n".
        '<div id="div'.$entriid.'" class="entri" style="height: 100%;"> '."\n";
		
	echo
        '<script type="text/javascript"> '."\n".

        /* simpan data - form disubmit */
        'function '.$entriid.'_save() { '."\n".
		'   var objForm = document.getElementById("'.$entriid.'");'."\n".
		'   if ('.($confirmexp ? $confirmexp : "saveEntry(objForm)").') { '."\n".
		'	   $j(objForm).ajaxSubmit({'."\n".
		'	      success: function(response){'."\n";
					
	if($trigger){
		echo
		'		     if(response.indexOf("'.$trigger.'") > -1){'."\n".
		($message ? 
		'		     document.getElementById("'.$message.'").innerHTML = response;' : 'alert(response)')."\n".
		'			 	$j(objForm).clearForm();'."\n".
		'			 }else{'."\n".
		'				document.getElementById("'.$message.'").innerHTML = response;'."\n".
		'				$j(objForm).clearForm();'."\n".
		'			 }'."\n";
	}else{
		($message ? 
		'		    document.getElementById("'.$message.'").innerHTML = response;' : 'alert(response)')."\n";
		echo
		'			$j(objForm).clearForm();'."\n";
	}
	echo	
		'		  }'."\n".
		'	   });'."\n".
  		'	   return false;'."\n".
		'  }'."\n".
		'}'."\n".
		
        'function '.$entriid.'_tutup() { '."\n".
        '  window.close(); '."\n".          
        '} '."\n".

        'gcms_yui_button("btn_'.$entriid.'_save", '.$entriid.'_save); '."\n".
        'gcms_yui_button("btn_'.$entriid.'_tutup", '.$entriid.'_tutup); '."\n".

        '</script> '."\n";	
}

/*definis from Entri dengan jQuery */
function jBeginEntry($entriid, $messageResult="", $action="", $method="POST"){
	global $gcms_entri_list;
  
  	global $extra_style_entri;
  	$extra = str_replace("xxxx",$daftarid,$extra_style_entri);
	
  	gcms_add_to_head($extra);
  
  	$preventConflic='<script type="text/javascript" src="./extensions/erp/js/jquery.js"></script>'."\n".
					'<script type="text/javascript" src="./extensions/erp/js/jquery.form.js"></script>'."\n".
  					'<script type="text/javascript" src="./extensions/erp/js/jquery.validate.js"></script>'."\n".
  					'<script type="text/javascript" src="./extensions/erp/js/ui.datepicker.js"></script>'."\n".
					//'<script type="text/javascript" src="yui/build/json/json-min.js"></script>'."\n".
					'<script type="text/javascript">'."\n".
					'   var $j = jQuery.noConflict();'."\n".
					'</script>'."\n";
					
	gcms_add_to_head($preventConflic);				
  
  	if (!is_array($gcms_entri_list)) $gcms_entri_list[0] = $entriid;
  	else array_push($gcms_entri_list, $entriid);

  	if (!$action) $action = "request.php";

  	/* pas onload di-resize dulu biar pas */
  	gcms_add_on_load($entriid."_resize_entri()");
	
	echo
        '<script type="text/javascript"> '."\n".

        /* array daftar yang ada di dalam entrian ini */
        'var '.$entriid.'_daftars = new Array(); '."\n".
		'var '.$entriid.'_pattern = new Array(); '."\n".

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
		
		'function setIdNavigation(p,n){'."\n".
		'	document.getElementById("prev_id").value=p;'."\n".
		'	document.getElementById("next_id").value=n;'."\n".
		'}'."\n".
		
		/*'function view(){'."\n".
		'	var a = document.getElementById("prev_id").value;'."\n".
		'	var b = document.getElementById("next_id").value;'."\n".
		'	alert("p:"+a+"- n:"+b);'."\n".
		'}'."\n".*/
		
		'function setJSONPattern(arr){'."\n".
		'	'.$entriid.'_pattern=arr;'."\n".
		'}'."\n".
		
		/*'function viewPattern(){'."\n".
		'	alert('.$entriid.'_pattern.length);'."\n".
		'}'."\n".*/
		
        '</script> '."\n";
    
	if(count($_REQUEST['id'])>1){
        $status_edit = ($_REQUEST['id'][0]) ? '1' : '0';
    }else{
        $status_edit = ($_REQUEST['id']) ? '1' : '0';
    }
	
		
	echo
        /* inisiasi form beserta data-data yang mendasar */
        '<form id="'.$entriid.'" action="'.$action.'" method="'.$method.'"> '."\n".
        '<input type="hidden" name="page" value="'.$_REQUEST['page'].'"> '."\n".
		'<input type="hidden" name="mode" value="asyc"> '."\n".
        '<input type="hidden" name="action" value="'.$_REQUEST['action'].'"> '."\n".
        '<input type="hidden" name="sender" value="'.$entriid.'"> '."\n".
		'<input type="hidden" name="status_edit" id="status_edit" value="'.$status_edit.'"> '."\n".
		'<input type="hidden" name="idmasters" id="idmasters" value="'.$_REQUEST['id'].'"> '."\n".
		'<input type="hidden" id="navrec" name="navrec"><input type="hidden" id="prev_id" name="prev_id"><input type="hidden" id="next_id" name="next_id">'."\n".
		'<table id="tbl_'.$entriid.'" style="width: 100%;"><tr height="100%"><td valign="top">'."\n".
		($messageResult ? '<div id="'.$messageResult.'"></div>'."\n" : '').
        '<div id="div'.$entriid.'" class="entri" style="height: 100%;"> '."\n";
}

function yuiBeginEntry($entriid){
    global $extra_style_entri;
  	$extra = str_replace("xxxx",$daftarid,$extra_style_entri);

  	//gcms_add_to_head($extra);

    $action = "request.php";
    $method="POST";

    echo
        /* inisiasi form beserta data-data yang mendasar */
        '<form id="'.$entriid.'" action="'.$action.'" method="'.$method.'" class="niceform"> '."\n".
        '<input type="hidden" name="page" value="'.$_REQUEST['page'].'"> '."\n".
		'<input type="hidden" name="mode" value="asyc"> '."\n".
        '<input type="hidden" name="action" id="caction" value="'.$_REQUEST['action'].'"> '."\n".
        '<input type="hidden" name="sender" value="'.$entriid.'"> '."\n".
        '<input type="hidden" name="idmasters" id="idmasters" value="'.$_REQUEST['id'].'"> '."\n".
        '<div id="extra_element_form"></div>'."\n".
        '<table id="tbl_'.$entriid.'" style="width: 100%;"><tr height="100%"><td valign="top">'."\n".
		($messageResult ? '<div id="'.$messageResult.'"></div>'."\n" : '').
        '<div id="div'.$entriid.'" class="entri" style="height: 100%;"> '."\n";
}

function yuiEndEntry(){
    echo "</div></td></tr></table></form>";
    /*echo
        '</div>'."\n".
        '</td></tr>'."\n".
        '<tr><td valign="bottom">'."\n".
        '<div id="buttons_'.$entriid.'" class="entri_buttons"><table style="width: 100%">'."\n".
        '<tr><td align="left">'."\n";*/
    
}

function jEndEntry($entriid, $new="", $prev="", $next="", $message="", $trigger="", $confirmexp="", $okbtn = false){
	global $gcms_entri_list;
	
	echo
        '</div>'."\n".
        '</td></tr>'."\n".
        '<tr><td valign="bottom">'."\n".
        '<div id="buttons_'.$entriid.'" class="entri_buttons"><table style="width: 100%">'."\n".
        '<tr><td align="left">'."\n";
		
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
        '<input id="btn_'.$entriid.'_tutup" type="button" value="Tutup">';

  	echo
        '</td></tr></table></div>'."\n".
        '</td></tr>'."\n".
        '</table></form>'."\n";
		
	echo
        '<script type="text/javascript"> '."\n".

        /* simpan data - form disubmit */
        'function '.$entriid.'_save() { '."\n".
		'   var objForm = document.getElementById("'.$entriid.'");'."\n".
		'   if ('.($confirmexp ? $confirmexp : "saveEntry(objForm)").') { '."\n".
		'	   $j(objForm).ajaxSubmit({'."\n".
		'	      success: function(response){'."\n";
					
	if($trigger){
		echo
		'		     if(response.indexOf("'.$trigger.'") > -1){'."\n".
		($message ? 
		'		     document.getElementById("'.$message.'").innerHTML = response;' : 'alert(response)')."\n".
		'			 	'."\n".
		'			 }else{'."\n".
		'				document.getElementById("'.$message.'").innerHTML = response;'."\n".
		'				'."\n".
		'			 }'."\n";
	}else{
		($message ? 
		'		    document.getElementById("'.$message.'").innerHTML = response;' : 'alert(response)')."\n";
		echo
		'			$j(objForm).clearForm();'."\n";
	}
	echo	
		'		  }'."\n".
		'	   });'."\n".
  		'	   return false;'."\n".
		'  }'."\n".
		'}'."\n".
		
        'function '.$entriid.'_tutup() { '."\n".
        '  window.close(); '."\n".          
        '} '."\n".

        'gcms_yui_button("btn_'.$entriid.'_save", '.$entriid.'_save); '."\n".
        'gcms_yui_button("btn_'.$entriid.'_tutup", '.$entriid.'_tutup); '."\n".

        '</script> '."\n";
	
	setCSSEntriButton($entriid);
	
	if (is_array($gcms_entri_list)) array_pop($gcms_entri_list);
}

function jEndEntryNav($entriid, $new="", $prev="", $next="", $message="", $trigger="", $confirmexp="", $okbtn = false){
	
	global $gcms_entri_list;
	
	echo
        '</div>'."\n".
        '</td></tr>'."\n".
        '<tr><td valign="bottom">'."\n".
        '<div id="buttons_'.$entriid.'" class="entri_buttons"><table style="width: 100%">'."\n".
        '<tr><td align="left">'."\n";
		
	if ($prev || $next) {
		echo 
        '<input id="btn_'.$entriid.'_prev" type="button" value="Mundur"> '.
        '<input id="btn_'.$entriid.'_next" type="button" value="Maju"> &nbsp;&nbsp;&nbsp; '.

        '<script type="text/javascript"> '."\n".
		' setIdNavigation("'.$prev.'","'.$next.'");'."\n".
		'function getOtherRecord(nav){'."\n".
		'      document.getElementById("navrec").value=nav;'."\n".
		//'    alert(document.getElementById("navrec").value);'."\n".
		'      var objForm = document.getElementById("'.$entriid.'");'."\n".
		'	   $j.ajax({'."\n".
		'		  type:"POST",'."\n".
		'		  url: "request.php",'."\n".
		'		  data: $j(objForm).serialize(),'."\n".
		'		  dataType: "json",'."\n".
		'	      success: function(response){'."\n".
        //'         alert(response.length)'."\n".
		//'			alert(response[0].message);'."\n".
		'           if(response.length=='.$entriid.'_pattern.length){'."\n".
		'			   for (var i = 0, len = response.length; i < len; ++i) {'."\n".
        '                 var m = response[i];'."\n".
        '				  document.getElementById('.$entriid.'_pattern[i]).value=m.fieldValue;'."\n".
		//'               alert(document.getElementById('.$entriid.'_pattern[i]).id+"-"+m.fieldName+" - "+m.fieldValue);'."\n".
		'	           }'."\n".
		'	           document.getElementById("status_edit").value=1'."\n".
		'	           document.getElementById("navrec").value=""'."\n".
		'           }else{'."\n".
		'               alert("Data Schema Failed !");'."\n".
		'           }'."\n".
		'         }      '."\n".
		'	   });'."\n".
  		'	   return false;'."\n".
		'}'."\n".
		
		//'function btn_'.$entriid.'_dummy_click() {} '."\n".

        'function btn_'.$entriid.'_prev_click() {'."\n".
        //'  window.location = '."'".$prev."'".'; '."\n".
		'	 if(document.getElementById("prev_id").value!=""){'."\n".
		'    	getOtherRecord("prev");'."\n".
		'	 } '."\n".
        '} '."\n".
        //'gcms_yui_button("btn_'.$entriid.'_prev", '.($prev ? 'btn_'.$entriid.'_prev_click' : 'btn_'.$entriid.'_dummy_click').'); '."\n".
		
		'gcms_yui_button("btn_'.$entriid.'_prev", btn_'.$entriid.'_prev_click);'."\n".
		
        'function btn_'.$entriid.'_next_click() {'."\n".
        //'  window.location = '."'".$next."'".'; '."\n".
		'	 if(document.getElementById("next_id").value!=""){'."\n".
		'    	getOtherRecord("next");'."\n".
		'	 } '."\n".
        '} '."\n".
        //'gcms_yui_button("btn_'.$entriid.'_next", '.($next ? 'btn_'.$entriid.'_next_click' : 'btn_'.$entriid.'_dummy_click').'); '."\n".
		'gcms_yui_button("btn_'.$entriid.'_next", btn_'.$entriid.'_next_click);'."\n".

        '</script> '."\n";
	}
	if ($new) {
    	echo 
        '<input id="btn_'.$entriid.'_new" type="button" value="Baru"> ';
    	echo
        '<script type="text/javascript"> '."\n".

        'function btn_'.$entriid.'_new_click() {'."\n".
		'   var objForm = document.getElementById("'.$entriid.'");'."\n".
		'	$j(objForm).clearForm();'."\n".
		'	document.getElementById("status_edit").value="0";'."\n".
		'	document.getElementById("navrec").value="";'."\n".
        //'   window.location = '."'".$new."'".'; '."\n".
        '} '."\n".
        'gcms_yui_button("btn_'.$entriid.'_new", btn_'.$entriid.'_new_click); '."\n".

        '</script> '."\n";
  	}
	echo
        '</td><td align="right">'."\n";

  	/* tombol-tombol sebelah kanan */
  	echo 
        '<input id="btn_'.$entriid.'_save" type="button" value="'.($okbtn ? 'Oke' : 'Simpan').'">'.
        '<input id="btn_'.$entriid.'_tutup" type="button" value="Tutup">';
		//'<input type="button" value="check" onclick="view();">';

  	echo
        '</td></tr></table></div>'."\n".
        '</td></tr>'."\n".
        '</table></form>'."\n";
		
	echo
        '<script type="text/javascript"> '."\n".

        /* simpan data - form disubmit */
        'function '.$entriid.'_save() { '."\n".
		'   var objForm = document.getElementById("'.$entriid.'");'."\n".
		'   if ('.($confirmexp ? $confirmexp : "saveEntry(objForm)").') { '."\n".
		'   YAHOO.progres.container.wait.show();'."\n".
		'	   $j(objForm).ajaxSubmit({'."\n".
		'	      success: function(response){'."\n";
					
	if($trigger){
		echo
		'		     if(response.indexOf("'.$trigger.'") > -1){'."\n".
		($message ? 
		'		     document.getElementById("'.$message.'").innerHTML = response;' : 'alert(response)')."\n".
		'				YAHOO.progres.container.wait.hide();'."\n".
		'			 }else{'."\n".
		'				document.getElementById("'.$message.'").innerHTML = response;'."\n".
		'				YAHOO.progres.container.wait.hide();'."\n".
		'			 }'."\n";
	}else{
        echo
        '          YAHOO.progres.container.wait.hide();'."\n".
		($message ? 
		'		    document.getElementById("'.$message.'").innerHTML = response;' : 'alert(response)')."\n";
		
	}
	echo	
		'		  }'."\n".
		'	   });'."\n".
  		'	   return false;'."\n".
		'  }'."\n".
		'}'."\n".
		
        'function '.$entriid.'_tutup() { '."\n".
        '  window.close(); '."\n".          
        '} '."\n".

        'gcms_yui_button("btn_'.$entriid.'_save", '.$entriid.'_save); '."\n".
        'gcms_yui_button("btn_'.$entriid.'_tutup", '.$entriid.'_tutup); '."\n".

        '</script> '."\n";
	
	setCSSEntriButton($entriid);
	
	if (is_array($gcms_entri_list)) array_pop($gcms_entri_list);
}

function jEndEntryAsycWHN($entriid, $new="", $prev="", $next="", $message="", $trigger="", $confirmexp="", $okbtn = false){

	global $gcms_entri_list;

	echo
        '</div>'."\n".
        '</td></tr>'."\n".
        '<tr><td valign="bottom">'."\n".
        '<div id="buttons_'.$entriid.'" class="entri_buttons"><table style="width: 100%">'."\n".
        '<tr><td align="left">'."\n";

	if ($prev || $next) {
		echo
        '<input id="btn_'.$entriid.'_prev" type="button" value="Mundur"> '.
        '<input id="btn_'.$entriid.'_next" type="button" value="Maju"> &nbsp;&nbsp;&nbsp; '.

        '<script type="text/javascript"> '."\n".
		' setIdNavigation("'.$prev.'","'.$next.'");'."\n".
		'function getOtherRecord(nav){'."\n".
		'      document.getElementById("navrec").value=nav;'."\n".
		//'    alert(document.getElementById("navrec").value);'."\n".
		'      var objForm = document.getElementById("'.$entriid.'");'."\n".
		'	   $j.ajax({'."\n".
		'		  type:"POST",'."\n".
		'		  url: "request.php",'."\n".
		'		  data: $j(objForm).serialize(),'."\n".
		'		  dataType: "json",'."\n".
		'	      success: function(response){'."\n".
		//'			alert(response[0].message);'."\n".
		'           if(response.length=='.$entriid.'_pattern.length){'."\n".
		'			   for (var i = 0, len = response.length; i < len; ++i) {'."\n".
        '                 var m = response[i];'."\n".
		'				  document.getElementById('.$entriid.'_pattern[i]).value=m.fieldValue;'."\n".
		//'               alert(document.getElementById('.$entriid.'_pattern[i]).id+"-"+m.fieldName+" - "+m.fieldValue);'."\n".
		'	           }'."\n".
		'	           document.getElementById("status_edit").value=1'."\n".
		'	           document.getElementById("navrec").value=""'."\n".
		'           }else{'."\n".
		'               alert("Data Schema Failed !");'."\n".
		'           }'."\n".
		'         }      '."\n".
		'	   });'."\n".
  		'	   return false;'."\n".
		'}'."\n".

		//'function btn_'.$entriid.'_dummy_click() {} '."\n".

        'function btn_'.$entriid.'_prev_click() {'."\n".
        //'  window.location = '."'".$prev."'".'; '."\n".
		'	 if(document.getElementById("prev_id").value!=""){'."\n".
		'    	getOtherRecord("prev");'."\n".
		'	 } '."\n".
        '} '."\n".
        //'gcms_yui_button("btn_'.$entriid.'_prev", '.($prev ? 'btn_'.$entriid.'_prev_click' : 'btn_'.$entriid.'_dummy_click').'); '."\n".

		'gcms_yui_button("btn_'.$entriid.'_prev", btn_'.$entriid.'_prev_click);'."\n".

        'function btn_'.$entriid.'_next_click() {'."\n".
        //'  window.location = '."'".$next."'".'; '."\n".
		'	 if(document.getElementById("next_id").value!=""){'."\n".
		'    	getOtherRecord("next");'."\n".
		'	 } '."\n".
        '} '."\n".
        //'gcms_yui_button("btn_'.$entriid.'_next", '.($next ? 'btn_'.$entriid.'_next_click' : 'btn_'.$entriid.'_dummy_click').'); '."\n".
		'gcms_yui_button("btn_'.$entriid.'_next", btn_'.$entriid.'_next_click);'."\n".

        '</script> '."\n";
	}
	if ($new) {
    	echo
        '<input id="btn_'.$entriid.'_new" type="button" value="Baru"> ';
    	echo
        '<script type="text/javascript"> '."\n".

        'function btn_'.$entriid.'_new_click() {'."\n".
		'   var objForm = document.getElementById("'.$entriid.'");'."\n".
		'	$j(objForm).clearForm();'."\n".
		'	document.getElementById("status_edit").value="0";'."\n".
		'	document.getElementById("navrec").value="";'."\n".
        //'   window.location = '."'".$new."'".'; '."\n".
        '} '."\n".
        'gcms_yui_button("btn_'.$entriid.'_new", btn_'.$entriid.'_new_click); '."\n".

        '</script> '."\n";
  	}
	echo
        '</td><td align="right">'."\n";

  	/* tombol-tombol sebelah kanan */
  	echo
        '<input id="btn_'.$entriid.'_save" type="button" value="'.($okbtn ? 'Oke' : 'Simpan').'">'.
        '<input id="btn_'.$entriid.'_tutup" type="button" value="Tutup">';
		//'<input type="button" value="check" onclick="view();">';

  	echo
        '</td></tr></table></div>'."\n".
        '</td></tr>'."\n".
        '</table></form>'."\n";

	echo
        '<script type="text/javascript"> '."\n".

        /* simpan data - form disubmit */
        'function '.$entriid.'_save() { '."\n".
		'   var objForm = document.getElementById("'.$entriid.'");'."\n".
		'   if ('.($confirmexp ? $confirmexp : "saveEntry(objForm)").') { '."\n".
		'	   $j(objForm).ajaxSubmit({'."\n".
		'	      success: function(response){'."\n";

	if($trigger){
		echo
		'		     if(response.indexOf("'.$trigger.'") > -1){'."\n".
		($message ?
		'		     document.getElementById("'.$message.'").innerHTML = response;' : 'alert(response)')."\n".
		'				//$j(objForm).clearForm();'."\n".
		'			 }else{'."\n".
		'				document.getElementById("'.$message.'").innerHTML = response;'."\n".
		'				//$j(objForm).clearForm();'."\n".
		'			 }'."\n";
	}else{
        echo
        '           $j(objForm).clearForm();'."\n".
		($message ?
		'		    document.getElementById("'.$message.'").innerHTML = response;' : 'alert(response)')."\n";

	}
	echo
		'		  }'."\n".
		'	   });'."\n".
  		'	   return false;'."\n".
		'  }'."\n".
		'}'."\n".

        'function '.$entriid.'_tutup() { '."\n".
        '  window.close(); '."\n".
        '} '."\n".

        'gcms_yui_button("btn_'.$entriid.'_save", '.$entriid.'_save); '."\n".
        'gcms_yui_button("btn_'.$entriid.'_tutup", '.$entriid.'_tutup); '."\n".

        '</script> '."\n";

	setCSSEntriButton($entriid);

	if (is_array($gcms_entri_list)) array_pop($gcms_entri_list);
}

function jEndEntryDefault($entriid, $table, $idfield, $currentid, $message="", $trigger="", $confirmexp="") {
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
  
  jEndEntry($entriid, $new, $prev, $next, $message, $trigger, $confirmexp);
}

function jEndEntryAsyc($entriid, $table, $idfield, $currentid, $message="", $trigger="", $confirmexp="") {
  $new = 'form.php?page='.$_REQUEST['page'].'&action=edit';

  $csql = "select max(".$idfield.") as id from ".$table." where ".$idfield." < ".$currentid;
  $nresult=gcms_query($csql);
  $id = gcms_fetch_object($nresult)->id;
  //if ($id) $prev = 'request.php?page='.$_REQUEST['page'].'&action=edit&id='.$id;
  if ($id) $prev = $id;
  else $prev = "";
  //if($id) echo 'document.getElementById("prev_id").value="'.$id.'"';

  $csql = "select min(".$idfield.") as id from ".$table." where ".$idfield." > ".$currentid;
  $nresult=gcms_query($csql);
  $id = gcms_fetch_object($nresult)->id;
  if ($id) $next=$id;
  //$next = 'request.php?page='.$_REQUEST['page'].'&action=edit&id='.$id;
  else $next = "";
  //if($id) echo 'document.getElementById("next_id").value="'.$id.'"';
  
  jEndEntryNav($entriid, $new, $prev, $next, $message, $trigger, $confirmexp);
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
	
	setCSSEntriButton($entriid);
    
  if (is_array($gcms_entri_list)) array_pop($gcms_entri_list);
}

function setCSSEntriButton($entriid){
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