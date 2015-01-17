<?
include "./entri.php";
include "./daftar.php";
includeYUIDatatable();
include './config.php';
//navigatorMenus();

yuiBeginEntry("entri_kecamatan");
?>
<script type="text/javascript" src="./lib.js"></script>
<script type="text/javascript" src="./ajaxdo.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>
<div class="leftSide" style="padding-top:20px;">
<div align="center" style="font-size:14px;"><span><b>FORM MASTER KECAMATAN</b></span></div>
<fieldset class="mainForm">

<label class="leftField"><span>Kode Kecamatan</span>
	<input type="text" name="KodeKecamatan" id="KodeKecamatan" style="width:30px;">
</label>
<label class="leftField"><span>Nama Kecamatan</span><input type="text" name="NamaKecamatan" id="NamaKecamatan" style="width:200px;"></label>
</fieldset>
</div>

<script>
function func_Baru(){
alert('baru');
}
function func_Simpan(){
	var formObject= document.getElementById("entri_kecamatan");
			if(saveEntry(formObject)){
				
				YAHOO.util.Connect.setForm(formObject);
				
				var transaction = YAHOO.util.Connect.asyncRequest('POST',"request.php",callback,null);
				
			}
}
var callback = {
	success: function(o) {
	var temp  = o.responseText;
	alert(temp);
	
	},
    failure: function(o) {alert("Failure")}
}
function func_Keluar(){
	window.close();
}
function func_Cetak(){
	alert('cetak');
}
function func_Edit(){
	alert('Edit');
}
function func_Hapus(){
	alert('Hapus');
}
</script>
<? yuiEndEntry()?>