<?
include "./entri.php";
include "./daftar.php";
includeYUIDatatable();
include './config.php';
//navigatorMenus();

yuiBeginEntry("entri_keterangan_spt");
?>
<script type="text/javascript" src="./lib.js"></script>
<script type="text/javascript" src="./ajaxdo.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>
<div class="leftSide">
<fieldset class="mainForm">
<legend>Keterangan SPT</legend>
<label class="leftField"><span>Kode</span><input type="text" name="kode" value="" size="2"/></label>
<label class="leftField"><span>Keterangan</span><input type="text" name="Keterangan" value="" size="40"/></label>
<label class="leftField"><span>Singkatan</span><input type="text" name="Singkatan" value="" size="30"/></label>
<label class="leftField"><span>Jenis Pemungutan</span>
<input type="checkbox" name="check1" id="check1" value="Self" />Self Assesment&nbsp; 
<input type="checkbox" name="check2" id="check2" value="Official" />Official Assesment 
</label>

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
	
	 var formObject= document.getElementById("entri_keterangan_spt");
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