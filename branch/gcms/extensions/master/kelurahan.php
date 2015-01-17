<?
include "./entri.php";
include "./daftar.php";
includeYUIDatatable();
include './config.php';
//navigatorMenus();

yuiBeginEntry("entri_kelurahan");
?>
<script type="text/javascript" src="./lib.js"></script>
<script type="text/javascript" src="./ajaxdo.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>

<div class="leftSide" style="padding-top:20px;">
<div align="center" style="font-size:14px;"><span><b>FORM MASTER KELURAHAN</b></span></div>
<fieldset class="mainForm">
<fieldset>
<legend>Detail Kelurahan</legend>
<label class="leftField"><span>Kode Kecamatan</span>
	<select name="kecamatan" id="kecamatan">
	<option value="" selected="selected">--</option>
	<?
	for($i=0;$i<3;$i++){
	?>
	<option value="kecamatan<?=$i?>" selected="selected">kecamatan<?=$i?></option>
	<? }
	?>
	</select>
</label>
<label class="leftField"><span>Kode Kelurahan</span>
	<input type="text" name="KodeKelurahan" id="KodeKelurahan" style="width:30px;">
</label>
<label class="leftField"><span>Nama Kelurahan</span><input type="text" name="NamaKelurahan" id="NamaKelurahan" style="width:200px;"></label>
</fieldset>
</fieldset>
</div>

<script>

function func_Simpan(){
	var formObject= document.getElementById("entri_kelurahan");
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
	alert('Keluarxxx');
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
<style>
.yui-button#exit button { background: ../../default_theme/templates/images/toolbar/icon-32-save.png;}
#save {background-image:url(../../default_theme/templates/images/toolbar/icon-32-save.png);
	width:50px;
	background-color:#FF0000;
}
</style>
<? yuiEndEntry()?>