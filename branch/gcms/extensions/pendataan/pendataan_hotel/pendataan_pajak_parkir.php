<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_penetapan_PajakParkir");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);


?>
<script>

var callback = {
    success: function(o) {
		//YAHOO.example.container.wait.hide();
		var formObject = document.getElementById("entri_penetapan_PajakParkir");
		var mesg = o.responseText.split('|');
        alert(mesg[0]);
        formObject.reset();
        //if(document.getElementById('caction').value=='' || document.getElementById('idmasters').value==0) setNomor('');    
		//YAHOO.example.container.wait.hide();
	},
    failure: function(o) {alert("!")}
}

function setSubmit(){
	var formObject = document.getElementById("entri_penetapan_PajakParkir");
	if(saveEntry(formObject)){
		YAHOO.util.Connect.setForm(formObject);
		//YAHOO.example.container.wait.show();
		var transaction = YAHOO.util.Connect.asyncRequest('POST', "request.php", callback, null);
	}
}

var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=penetapan_PajakParkir',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=penetapan_PajakParkir&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','Pemungutan','Nomor','Periode','NPWPD/NPWRD','Nama WP/WR','Tgl Penetapan','No. KOHIR','Tgl. Setoran','Tgl Diterima WP/WR','Tgl. Batas Kembali'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false

	},{
		name:'pemungutan'
		,index:'pemungutan'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'Nomor'
		,index:'Nomor'
		,width:80
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'periode'
		,index:'periode'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'NpwpdNpwrd'
		,index:'NpwpdNpwrd'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'NamaWPWR'
		,index:'NamaWPWR'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'TglPenetapan'
		,index:'TglPenetapan'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'NoKohir'
		,index:'NoKohir'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'TglSetor'
		,index:'TglSetor'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'TglDiterima'
		,index:'TglDiterima'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'TglBtsKembali'
		,index:'TglBtsKembali'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	}
	
	],
    pager: jQuery('#htmlPager'),
    height:150,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data Penetapan Pajak Hotel',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
                jQuery("#htmlTable").restoreRow(lastsel); 
                jQuery("#htmlTable").editRow(id,true); 
                lastsel=id; 
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth( document.width - 160 < 300?300:document.width - 120);
                return true;
            }
    }).navGrid('#htmlPager'
        ,{add:false,edit:false,del:true,view:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
        
        ).hideCol('id');  /*end of on ready event */ 
}
);



</script>

<div id="demo" class="yui-navset">
		<ul class="yui-nav">
 		<li id="Form" class="selected"><a href="#tab1"><em>Form</em></a></li>
		<li id="Data"><a href="#tab2"><em>Daftar</em></a></li>
    </ul>
	<div class="yui-content">
		<div id="tab1">
		<!--CONTENT-->
			<div class="form_master">
				<div class="title"><h1>Form Pajak</h1></div>
				<fieldset class="form_frame">
				<legend>Isian SPTPD Pajak Parkir</legend>
				<div><label>No. Reg Form</label><input type="text"  name="pendataan_no" value="" size="30" maxlength="30" /></div>
				<div><label>Tanggal Proses</label><input id="tgl_proses" type="text"  name="tgl_proses" value="" size="10" maxlength="10" /><input type="button" id="btn_tgl_proses" name="btn_tgl_proses" value="..."/></div>
				<div><label>Tanggal Entri</label><input id="tgl_entry" type="text"  name="tgl_entry" value="" size="10" maxlength="10" /><input type="button" id="btn_tgl_entry" name="btn_tgl_entry" value="..."/></div>
				<div><label>Periode SPT</label><input id="periode_spt" type="text" name="periode_spt" value="" size="4" maxlength="4" /></div>
				<div><label>Nomor SPT</label><input id="nomor_spt" type="text" name="nomor_spt" value="" size="10" maxlength="10" /><input type="button" id="btn_nomor_spt" name="btn_nomor_spt" value="..." /><input id="reset_nomor_spt" type="button" name="reset_nomor_spt" value="kosongkan" /></div>
				<div><label>NPWPD/NPWRD</label>
				<input type="text" name="npwpd_npwrd1" id='npwpd_npwrd1' value="" size="20" maxlength="50" />
				<!--<input type="text" name="npwpd_npwrd2" id='npwpd_npwrd2' value="" size="2" />
				<input type="text" name="npwpd_npwrd3" id='npwpd_npwrd3' value="" size="7" />
				<input type="text" name="npwpd_npwrd4" id='npwpd_npwrd4' value="" size="2" />
				<input type="text" name="npwpd_npwrd5" id='npwpd_npwrd5' value="" size="2" />-->
				<input type="button" name="btn_npwp_dialog" value="..." onclick="showDialogProduk();" /></div>
				<div><label>Sistem Pemungutan</label><select name="jenis_pungutan" size="1"><option value="OFFICIAL">Official Assesment</option><option value="SELF">Self Assesment</option></select></div>
				<div><label>Periode Penjualan</label><input type="text"  name="periode_awal" value="" size="10" maxlength="10" /><input type="button" id="btn_periode_awal" name="btn_periode_awal" value="..."/><span>s/d</span><input type="text"  name="periode_akhir" value="" size="10" maxlength="10" /><input type="button" id="btn_periode_akhir" name="btn_periode_akhir" value="..."/><div>

				</fieldset>
				<fieldset class="form_frame">
				<legend>Detail Parkir</legend>
				<div>
				<table id="detail">
				<tr><th>Kode Rekening</th><th>Dasar Pengenaan</th><th>Persen Tarip</th><th>Pajak</th><th><input id="hapus_detail_all" type="button" name="hapus_detail_all" value="Hapus Semua" /></th></tr>	
				</table>
				</div>
				<div><input id="tambah_detail" type="button" name="tambah_detail" value="Tambah Detail" /><span>Total</span><input class="total" id="pajak_view" type="text" name="total" value="0.00" disabled /><input id="id_isi_detail" type="hidden" name="id_isi_detail" value="0" /></div>
				</fieldset>
			</div>
			<div class="footer_space">&nbsp;</div>
		<!--CONTENT-->
		</div>
		<div id="tab2">
			<div style='padding:5px;'>
				<fieldset>
				<legend>Daftar</legend>
					<div id='asb_simulasi_form'>
						<div style='padding:5px'>
							<table id="htmlTable" class="scroll"></table>
							<div id="htmlPager" class="scroll"></div>
						</div>
					</div>
					<div id="div_daftar_npwp" align="center"></div>
				</fieldset>
			</div>
		<div>
	</div>
</div>

<div class="hidden" id="dialog1">
	<div class="hd"></div>
	<div class="bd">
		<div id="container-page"> 
			
			<div id="content">
				
						<table style="width: 10%">
							<tr><td>
							
								<fieldset>
								<legend>Daftar</legend>
									<div id='asb_simulasi_form'>
										<div style='padding:5px'>
											<table id="htmlTable2" class="scroll"></table>
											<div id="htmlPager2" class="scroll"></div>
										</div>
									</div>
									
								</fieldset>
							
							</td>
							<td>&nbsp;
							<input type="hidden" id="ConvertId" />
							</td><td width="1" align="right" valign="top"><!--<input id="btn_pilihCoa_refresh" type="button" value="Refresh">-->
							</td></tr>
						</table>
					
					<div id="fakehead_pilihCoa" class="fake_head"></div>
					
					<div id="buttons_pilihCoa" class="daftar_buttons">
						<table style="width: 100%"><tr><td align="left"></td><td align="right"></td></tr></table>
					</div>
				
			</div>
	</div>
</div>
<script type="text/javascript">
var el = document.getElementById( 'dialog1' );
el.className='';
</script>
<script>
	(function(){
		var tabView = new YAHOO.widget.TabView('demo');   
	})();

</script>
<script>

YAHOO.namespace("hendra");
    

function OpenSpt(){
	gcms_open_form('form.php?mod=penetapan&func=open_spt','detail',"availWidth", "availHeight");
}


function showDialogProduk(){
		YAHOO.hendra.dialog1.show();
	
}
/*function showDialogProduk(){
	
	YAHOO.hendra.dialog1.show();
}

*/function init() {
	var handleSubmit = function() {
		this.cancel();
		//window.close();
        
	};
	var handleCancel = function() {
		this.cancel();
	};
	
	// Instantiate the Dialog
	YAHOO.hendra.dialog1 = new YAHOO.widget.Dialog(
		"dialog1", 
		{ width : "870px",
		  fixedcenter : true,
		  visible : false, 
		  constraintoviewport : true,
		  buttons : [ { text:"Tutup", handler:handleCancel } ]
		});

	// Validate the entries in the form to require that both first and last name are entered
	YAHOO.hendra.dialog1.validate = function() {
		var data = this.getData();
		if (data.firstname == "" || data.lastname == "") {
			alert("Please enter your first and last names.");
			return false;
		} else {
			return true;
		}
	};

	// Wire up the success and failure handlers
	//YAHOO.hendra.dialog1.callback = { success: handleSuccess, failure: handleFailure };
	
	// Render the Dialog
	YAHOO.hendra.dialog1.setHeader("Daftar NPWP");
	YAHOO.hendra.dialog1.render();

	YAHOO.util.Event.addListener("open", "click", YAHOO.hendra.dialog1.show, YAHOO.hendra.dialog1, true);
	YAHOO.util.Event.addListener("hide", "click", YAHOO.hendra.dialog1.hide, YAHOO.hendra.dialog1, true);
}

YAHOO.util.Event.onDOMReady(init);


var lastsel2;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable2").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=daftarnpwp',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=daftarnpwp&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','No Pendaftaran','NPWP','Nama ','Alamat'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false

	},{
		name:'NoPendaftaran'
		,index:'NoPendaftaran'
		,width:100
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        //,editrules: {required:true}
	},{
		name:'NPWP'
		,index:'NPWP'
		,width:80
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'Nama'
		,index:'Nama'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'Alamat'
		,index:'Alamat'
		,width:180
		,editable:false
        ,edittype:'text'
        
	}/*,{
		name:'Kelurahan'
		,index:'Kelurahan'
		,width:100
		,editable:false
        ,edittype:'text'
        
	},{
		name:'Kecamatan'
		,index:'Kecamatan'
		,width:100
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'Kabupaten'
		,index:'Kabupaten'
		,width:100
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	}*/
	
	],
    pager: jQuery('#htmlPager2'),
    height:100,
	width:600,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data NPWP',
        onSelectRow: function(id){ 
            if(id && id!==lastsel2){ 
			//alert(id);
                jQuery("#htmlTable2").restoreRow(lastsel2); 
                jQuery("#htmlTable2").editRow(id,true); 
                lastsel2=id; 
				ajax_do('<?=$expath?>SetData.php?Npwp='+id);
				tutup();
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable2").setGridWidth( document.width - 500 < 100?100:document.width - 500);
                return true;
            }
    }).navGrid('#htmlPager2'
        ,{add:true,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:300} // add
        ,{} // del
        ,{} // search
        
        ).hideCol(['id']);  /*end of on ready event */ 
}
);
function tutup(){
//window.close();
}
</script>
<? yuiEndEntry() ?>