/**/
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

/**/
function gcms_open_report(fr3, format, param) {
	window.open('http://'+document.location.hostname+':8094/result?report='+ fr3 + '&format=' + format + param ,'_blank');
}

/* tampilkan dialog */
function gcms_open_dialog(page, modul ,wname, width, height , fr3 ) {
    /*for($idx=1;$idx <= 4 ;$idx++){
		document.getElementById("filter"+$idx).style.display="none";
	}*/
	modul = modul.split('_');
	modul = modul[1];
	filter = fr3.replace("fr3","php");
    ajax_do("./extensions/"+modul+"/"+filter);
	
	// Define various event handlers for Dialog
	// Instantiate the Dialog
	var handleSubmit = function() {
		YAHOO.form_dialog.container.dialog1.cancel();
	};
	var handleCancel = function() {
		YAHOO.form_dialog.container.dialog1.cancel();
	};
	YAHOO.form_dialog.container.dialog1 = new YAHOO.widget.Dialog("form_dialog", 
							{ 
							  width : width,
							  height : height,
							  effect:{effect:YAHOO.widget.ContainerEffect.SLIDE,duration:0.25} ,
							  draggable:true,
							  scrollable :true,
							  fixedcenter :false,
							  constraintoviewport : true
							});
	
	/*var button = new YAHOO.widget.Button("submit_filter");
  	button.on("click", handleSubmit);*/
	var button = new YAHOO.widget.Button("cancel_filter");
  	button.on("click", handleCancel);
	
	// Wire up the success and failure handlers
	YAHOO.form_dialog.container.dialog1.callback = { 
													
													};
													
	
	// Render the Dialog
	YAHOO.form_dialog.container.dialog1.render();

	//wname = wname.replace("_"," ");	
	//wname = wname.replace("_"," ");
	//wname = wname.replace("_"," ")
	document.getElementById('title').innerHTML = "Filter "+wname;
	document.getElementById('file_name').value = fr3 ;
	YAHOO.form_dialog.container.dialog1.show();
}

/* tampilkan form dialog - internal use only */
function gcms_open_dialog_int(url, wname, width, height) {
	document.getElementById("panel1").style.display="";
	
	//YAHOO.form_dialog.container.form.show();
}

/* tampilkan form - internal use only */
function gcms_open_form_int(url, wname, width, height) {
	//half the screen width minus half the new window width (plus 5 pixel borders).
	iMyWidth = (window.screen.width/2) - (100 + 75);
	//half the screen height minus half the new window height (plus title and status bars).
	iMyHeight = (window.screen.height/2) - (100 + 50);	
	if (window.opener) owner = window.opener; 
	else owner = window;
	wnd = owner.open(url, wname, 'width=' + width + ',height=' + height + 'left=' + iMyWidth + ',top=' + iMyHeight + ',screenX=' + iMyWidth + ',screenY='+ iMyHeight + ',toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,copyhistory=0,resizable=0');
	wnd.opener = owner;
	wnd.focus();
	return wnd;
}

function checkClossing(tgl){
	//gcms_ajax_request('request.php?mod=akunting&func=clossing&cek='+tgl,ok_func,err_func);
	gcms_ajax_request('request.php?mod=akunting&action=set_clossing&cek='+tgl,ok_func,err_func);
	
}

/* === MEMBUAT BUTTON ========================================================================== */

function gcms_yui_button(button_id, on_click , attr ) {
  	var button = new YAHOO.widget.Button(button_id);
  	button.on("click", on_click);
	if(attr){
		button.set('disabled', true);
	}else{
		button.set('disabled', false);
	}
	
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
	//alert(url);
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
var formatCurrency = function (value) {
    value = isNaN(value) ? 0 : value;
	return YAHOO.util.Number.format(value,{decimalPlaces:2,thousandsSeparator:',',decimalSeparator:'.' });
};
var formatCurrencyPref = function (value) {
    value = isNaN(value) ? 0 : value;
	return YAHOO.util.Number.format(value,{prefix:'Rp.',decimalPlaces:2,thousandsSeparator:',',decimalSeparator:'.' });
};

function currentDate(){
	var x= new Date();
    var tgl = x.getDate();
	var bln = x.getMonth()+1;
	var thn = x.getFullYear();
	return tgl+'/0'+bln+'/'+thn;
}

//ajax_do 
function ajaxdo (url) {
	// Does URL begin with http?
    if (url.substring(0, 4) != 'http') {
     url = url;
	}
	// Create new JS element
	var jsel = document.createElement('SCRIPT');
	jsel.type = 'text/javascript';
	jsel.src = url;

	// Append JS element (therefore executing the 'AJAX' call)
	document.body.appendChild (jsel);
}

/*
function progres_bar (){
	YAHOO.namespace("progres.container");
	YAHOO.progres.container.wait = new YAHOO.widget.Panel("wait",  
																	{ width: "350px",
																	  fixedcenter: true, 
																	  close: false, 
																	  draggable: false, 
																	  zindex:4,
																	  modal: true,
																	  visible: false
																	} 
																);
	
	YAHOO.progres.container.wait.setHeader("Tunggu Processing Data Sedang Berlangsung...");
	YAHOO.progres.container.wait.setBody("<img src='./extensions/administrator/images/progressbar.gif'/>");
	YAHOO.progres.container.wait.render(document.body);	
	
}

function meseg_box(){
	YAHOO.namespace("msg_respon.container");
	var handleCancel = function() {
		YAHOO.msg_respon.container.msg_dialog.cancel();
	};
	
	// Instantiate the Dialog
	YAHOO.msg_respon.container.msg_dialog = new YAHOO.widget.Dialog("msg_dialog", 
							{ 
							  width : "20em",
							  fixedcenter : true,
							  modal:false,
							  visible : false, 
							  constraintoviewport : true,
							  resizeable:true,
							});
	var button = new YAHOO.widget.Button("cancel");
  	button.on("click", handleCancel);

	// Render the Dialog
	YAHOO.msg_respon.container.msg_dialog.setHeader("Respon");
	YAHOO.msg_respon.container.msg_dialog.render();	
}
*/
