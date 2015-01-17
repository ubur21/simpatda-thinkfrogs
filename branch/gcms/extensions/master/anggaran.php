<?
include "./entri.php";
include "./daftar.php";
includeYUIDatatable();
include './config.php';
//navigatorMenus();

yuiBeginEntry("entri_anggaran");
?>
<script type="text/javascript" src="./lib.js"></script>
<script type="text/javascript" src="./ajaxdo.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>
<script>
var navConfig = {
        strings : {month: "Month",year: "Year",submit: "OK",cancel: "Cancel",invalidYear: "Please enter a valid year"},initialFocus: "year"
    };
    YAHOO.namespace("hendra");
    
    function getResultDate(objCal){
        var selDate = objCal.getSelectedDates()[0];
        var dStr = (selDate.getDate() > 9) ? selDate.getDate() : "0"+selDate.getDate();
        var mStr = ((selDate.getMonth()+1) > 9) ? selDate.getMonth()+1 : "0"+parseInt(selDate.getMonth()+1);
        var yStr = selDate.getFullYear();
        return dStr+"/"+mStr+"/"+yStr;
    }

    YAHOO.util.Event.onDOMReady(function(){
        var dialog_anggaran, calendar_anggaran;
        calendar_anggaran = new YAHOO.widget.Calendar("cal_anggaran", {iframe:false,hide_blank_weeks:true,navigator:navConfig,close:false});

        function okHandler_anggaran() {
            if (calendar_anggaran.getSelectedDates().length > 0) YAHOO.util.Dom.get("date_anggaran").value = getResultDate(calendar_anggaran);
            else YAHOO.util.Dom.get("date_anggaran").value = "";
            this.hide();
			CekNomor();
        }

        function cancelHandler_anggaran() { this.hide(); CekNomor(); }

        dialog_anggaran = new YAHOO.widget.Dialog("container_anggaran", {
            context:["show", "tl", "bl"],
            buttons:[ {text:"Select", isDefault:true, handler: okHandler_anggaran},
                  {text:"Cancel", handler: cancelHandler_anggaran}],
            width:"16em",draggable:false,close:true
        });
        calendar_anggaran.render();
        dialog_anggaran.render();

        dialog_anggaran.hide();

        calendar_anggaran.renderEvent.subscribe(function() { dialog_anggaran.fireEvent("changeContent"); });
        YAHOO.util.Event.on("show_anggaran", "click", dialog_anggaran.show, dialog_anggaran, true);
    });
</script>
<div class="leftSide">
<div align="center" style="font-size:14px;"><span><b>FORM MASTER ANGGARAN</b></span></div>
<fieldset class="mainForm">
<legend>Detail Anggaran</legend>
<label class="leftField"><span>Tahun Anggaran</span>
	<input type='text' maxlength="10" size="12" name="tahun1" id="date_anggaran"> s/d
               <!-- <?=html_calendar("anggaran")?> -->
	<input type='text' maxlength="10" size="12" name="tahun2" id="date_anggaran2"> format ('YYYY')
</label>
<label class="leftField"><span>Status Anggaran</span><input type="text" name="StatusAnggaran" id="StatusAnggaran" style="width:200px;"></label>
</fieldset>
</div>

<script>
gcms_yui_button("exit", exit);
function func_Baru(){
alert('baru');
}
function func_Simpan(){
	var formObject= document.getElementById("entri_anggaran");
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