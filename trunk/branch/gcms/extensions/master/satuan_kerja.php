<?
include "./entri.php";
include "./daftar.php";
includeYUIDatatable();
include './config.php';
//navigatorMenus();

yuiBeginEntry("entri_SatuanKerja");
?>
<script type="text/javascript" src="./lib.js"></script>
<script type="text/javascript" src="./ajaxdo.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>
<div class="leftSide">
<table border="1" bordercolor="#333333" cellspacing="1">

<tr>
<td>
<div align="center"><b>MASTER SATUAN KERJA</b></div>
<fieldset class="mainForm">

<label class="leftField"><span>Bidang</span>
	<select name="Bidang" id="Bidang">
	<option value="" selected="selected">---</option>
	<?
	
	for($i=0;$i<3;$i++){
	?>
	<option value="" selected="selected">Tes Bidang<?=$i?></option>
	<? }?>
	</select>
</label>
<label class="leftField"><span>Kode Unit Kerja</span><input type="text" name="KodeUnitKerja" id="KodeUnitKerja" style="width:20px;"></label>
<label class="leftField"><span>Nama Unit Kerja 1</span><input type="text" name="NamaUnitKerja1" id="NamaUnitKerja1" style="width:200px;"></label>
<label class="leftField"><span>Nama Unit Kerja 2</span><input type="text" name="NamaUnitKerja2" id="NamaUnitKerja2" style="width:200px;"></label>
<label class="leftField"><span>Singkatan</span><input type="text" name="Singkatan" id="Singkatan" value="" size="10" maxlength="20" style="width:150px;" /></label>

</fieldset>
<!--
<div  align="right">
	<fieldset class="mainForm">
	<label class="rightField">
		<span id="btn_entri" class="yui-button yui-push-button">
			<span class="first-child">
				  <button type="button" style="width:70px;height:25px" onclick="func_submit()">Simpan</button>
			</span>
		</span>&nbsp;&nbsp;
		<!--<span id="btn_exit" class="yui-button yui-push-button">
			<span class="first-child">
			  <button type="button" style="width:70px;height:25px" onclick="func_exit()">Keluar</button>
			</span>-->
		
		<!--</span>
	</label>
	<span id="btn_exit" class="yui-button yui-push-button">
			<span class="first-child">
			  <button type="button" style="width:70px;height:25px" onclick="func_exit()">Keluar</button>
			</span>
	</span>
	</fieldset>
	
</div>
-->
</td>
</tr>
</table>
</div>

<script>
function exit(){
}
function func_Baru(){
alert('baru');
}
function func_Simpan(){
	//var cdo=document.getElementById('tes');
		//alert('xxxx');
       //ajax_do('./extensions/master/master.php?simpan=SimpanSatuanKerja&id='+cdo);
	 //var transaction = YAHOO.util.Connect.asyncRequest('POST', "request.php", callback, null);
	 var formObject= document.getElementById("entri_SatuanKerja");
			if(saveEntry(formObject)){
				
				YAHOO.util.Connect.setForm(formObject);
				
				//YAHOO.progres.container.wait.show();
				var transaction = YAHOO.util.Connect.asyncRequest('POST',"request.php",callback,null);
				//alert('d');
			}
}
var callback = {
//alert('callback');
    //    success: function(o) {window.close()},
     //   failure: function(o) {alert("!")}
    
	success: function(o) {
	var temp  = o.responseText;
	alert(temp);
	
	},
    failure: function(o) {alert("Failure")}
}
function func_Keluar(){
	//alert('Keluarxxx');
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
<style>
.yui-button#exit button { background: ../../default_theme/templates/images/toolbar/icon-32-save.png;}
#save {background-image:url(../../default_theme/templates/images/toolbar/icon-32-save.png);
	width:50px;
	background-color:#FF0000;
}
</style>
<? yuiEndEntry()?>