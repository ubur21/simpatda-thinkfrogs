<?
include "./entri.php";
include "./daftar.php";
includeYUIDatatable();
include './config.php';
//navigatorMenus();

yuiBeginEntry("entri_Pemda");
?>
<script type="text/javascript" src="./lib.js"></script>
<script type="text/javascript" src="./ajaxdo.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>
<div class="leftSide">
<fieldset class="mainForm">
<label class="leftField"><span>Kode Lokasi</span><input type="text" name="kodeLokasi" value="" size="2" maxlength="2" /></label>
<label class="leftField"><span>Kabupaten/Kota</span>
	<select name="kabKota">
		<option value="Kabupaten">Kabupaten</option>
		<option value="Kota">Kota</option>
	</select>
</label>
<label class="leftField"><span>Pejabat Kab/Kota</span>
	<select name="pejabat">
		<option value="Bupati">Bupati</option>
		<option value="Wali Kota">Wali Kota</option>
	</select>
</label>
<label class="leftField"><span>Alamat Kantor</span><textarea name="alamat" col="4" row="3"></textarea></label>
<label class="leftField"><span>Nama Kabupaten/Kota</span><input type="text" name="namaKab" value="" size="10" maxlength="20" /></label>
<label class="leftField"><span>Ibukota Kab/Kota</span><input type="text" name="ibukotaKab" value="" size="10" maxlength="20" /></label>
<label class="leftField"><span>Nomor Telp.Kantor</span><input type="text" name="telp" value="" size="10" maxlength="20" /></label>
</fieldset>
</div>
<div>
<fieldset class="mainForm">
<label class="leftField"><span>Nama Bank</span><input type="text" name="namaBank" value="" size="20" maxlength="20" /></label>
<label class="leftField"><span>Nomor Rekening Bank</span><input type="text" name="noRek" value="" size="20" maxlength="20" /></label>
<label class="leftField"><span>Logo Pemerintah Daerah</span><input type="file" name="logo" /></label>

</fieldset>
</div>
<!--<div id="confirm"><input type="button" name="simpan" value="Simpan" onclick="showForm('confirmDialog');" /></div>-->
<div id="confirmDialog" class="hidden">
<fieldset class="mainForm">
<label class="leftField"><span>Nama User</span><input type="text" name="namaUser" value="" size="10" maxlength="20" /></label>
<label class="leftField"><span>Password</span><input type="password" name="password" value="" size="10" maxlength="20" />
<input class="closeForm" type="button" name="close" value="Batal" onclick="closeForm('confirmDialog');" /></label>
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
	jQuery("#Alamat").click( 
		function(){ 
			var su=jQuery("#id").jqGrid('delRowData',12); 
				if(su) alert("Succes. Write custom code to delete row from server"+su); 
				else alert("xxxxxx"); 
		}
	); 
}
function func_Cetak(){
alert('Cetak');
}
function func_Simpan(){
	
	 var formObject= document.getElementById("entri_Pemda");
			if(saveEntry(formObject)){
				
				YAHOO.util.Connect.setForm(formObject);
				
				var transaction = YAHOO.util.Connect.asyncRequest('POST',"request.php",callback,null);
				
			}
}
var callback = {
	success: function(o) {
	var temp  = o.responseText;
	var formObject= document.getElementById("entri_Pemda");
	alert(temp);
	 formObject.reset();
	 document.getElementById("extra_element_form").innerHTML="";
	},
    failure: function(o) {alert("Failure")}
}
function func_Keluar(){
window.close();
}
</script>
<? yuiEndEntry()?>