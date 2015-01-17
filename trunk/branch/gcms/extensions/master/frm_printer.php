<?
include "./entri.php";
include "./daftar.php";
includeYUIDatatable();
include './config.php';
//navigatorMenus();

yuiBeginEntry("entri_DataPrinter");
?>
<script type="text/javascript" src="./lib.js"></script>
<script type="text/javascript" src="./ajaxdo.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>
<div class="leftSide">
<fieldset class="mainForm">
<legend>Master Printer</legend>
<label class="leftField"><span>Nama Printer*</span><input type="text" name="Nama" value="" size="2"/></label>
<label class="leftField"><span>TTY*</span><input type="text" name="TTY" value="" size="40"/></label>
<label class="leftField"><span>Alamat Printer*</span><input type="text" name="Alamat" value="" size="30"/></label>
<label class="leftField"><span>Keterangan</span><input type="text" name="Ket" value="" size="30"/></label>
<label class="leftField"><span>Default Printer*</span>
	<select name="Default">
		<option value="0" selected="selected">Tidak</option>
		<option value="1">Ya</option>
	</select>
</label>
<label class="leftField"><span><font color="#FF0000">*Wajid di isi</font></span>
</fieldset>
</div>
<script>
function func_Baru(){
alert('baru');
}
function func_Edit(){
alert('Edit');
}
function func_Hapus(){
alert('Hapus');
}
function func_Cetak(){
alert('Cetak');
}
function func_Simpan(){
	
	 var formObject= document.getElementById("entri_DataPrinter");
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
</script>
<? yuiEndEntry()?>