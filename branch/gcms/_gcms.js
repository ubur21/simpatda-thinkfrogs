/* === MEMBUKA WINDOW BARU ========================================================================== */

/* tampilkan form pilih */
function gcms_open_form_pilih(url, wname, width, height, resdoc, resdata) {
  wnd = gcms_open_form_int(url, wname, width, height);
  wnd.gcmsPilihResultDoc = resdoc;
  wnd.gcmsPilihResultData = resdata;
}

/* tampilkan form */
function gcms_open_form(url, wname, width, height) {
  gcms_open_form_int(url, wname, width, height);
}

/* tampilkan form - internal use only */
function gcms_open_form_int(url, wname, width, height) {
  if (window.opener) owner = window.opener; 
  else owner = window;
  wnd = owner.open(url, wname, 'width=' + width + ',height=' + height + ',toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,copyhistory=0,resizable=1');
  wnd.opener = owner;
  wnd.focus();
  return wnd;
}


/* === MEMBUAT BUTTON ========================================================================== */

function gcms_yui_button(button_id, on_click) {
  var button = new YAHOO.widget.Button(button_id);
  button.on("click", on_click);
}

/* === AJAX ========================================================================================== */

/* init ajax request - internal use */
function gcms_ajax_init() {
  try {
    // Firefox, Opera 8.0+, Safari
    return new XMLHttpRequest();
  }
  catch (e) {
    // Internet Explorer
    try {
      return new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e)
    {
      try {
        return new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch (e) {
        return null;
      }
    }
  }
}

/* ambil sesuatu dengan request via ajax */
function gcms_ajax_request(url, okfunc, failfunc) {
  var xmlHttp = gcms_ajax_init();
  xmlHttp.onreadystatechange = function() {
    if (xmlHttp.readyState == 4) {
      if (xmlHttp.status == 200) okfunc(xmlHttp.responseText);
	  else failfunc(xmlHttp.responseText);
    }
  }
  xmlHttp.open("GET", url, true);
  xmlHttp.send(null);
}


/* === LAIN LAIN ============================================================================ */

/* trim string */
function trim(str) {
  return str.replace(/^\s+|\s+$/g, '');
}

