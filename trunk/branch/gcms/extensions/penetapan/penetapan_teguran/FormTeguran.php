<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_TeguranWpWr");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);


?>
<script>
$().ready(function() {
	
	var $tabs = $("#tabs").tabs();
	
});
var lastse3;
var timeoutHnd; var flAuto = false; 

$().ready(function(){
	
	jQuery("#htmlTable3").jqGrid({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=default',
		editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=default&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','No. Kohir','No. NPWPD/RW','Nama NPWPD/RW','Tanggal Penetapan','Tanggal Jatuh Tempo','Nominal'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'no_kohir',index:'no_kohir',width:100,editable:true,align:'center'},
			{name:'npwp',index:'npwp',width:100,editable:true,align:'center'},
			{name:'nama',index:'nama',width:220,editable:true},
			{name:'tanggal_penetapan',index:'tanggal_penetapan',width:150,editable:true,formatter:'date', sorttype:"date"},
			{name:'tanggal_jatuh_tempo',index:'tanggal_jatuh_tempo',width:150,editable:true,formatter:'date', sorttype:"date"},
			//{name:'tunggakan',index:'tunggakan',width:110,editable:true},
			{name:'nominal',index:'nominal',width:80,editable:true}
		],
		pager: jQuery('#htmlPager3'),
		height:150,
		rowNum:15,
		rowList:[5,10,15],
		mtype:"POST",
		shrinkToFit:false,
		sortname: 'pendataan_no',
		sortorder: 'desc',
		rownumbers: true,
		//multiselect:true,
		//multiboxonly: true,
		viewrecords: true,
		caption: '&nbsp;',
		
		//
        ondblClickRow: function(id){ 
		//alert(id);
            if(id && id!==lastse3){
				jQuery.post("request.php?page=<?=$_REQUEST['page']?>&sender=get_DataFormTeguran&val="+id, {},
					function(data){
			//		alert(data);						
						/*jQuery('#proses').val('Ubah');
						jQuery('#batal').attr('disabled',false);
						jQuery('#caction').val('edit');
						jQuery('#IdEdit').val('edit');
						jQuery('#idmasters').val(id);
						jQuery('#NoRegForm').val(data.nomor);*/
						jQuery('#nama_wp_wr').val(data.nama);
						jQuery('#SptIdHid').val(data.spt_id);
						jQuery('#Periode').val(data.periode_spt);
						jQuery('#NomorSpt').val(data.spt_no);
						jQuery('#kode_npwp').val(data.kode_npwp);
						jQuery('#npwpd_npwrd1').val(data.npwp1);
						jQuery('#npwpd_npwrd2').val(data.npwp4);
						jQuery('#npwpd_npwrd3').val(data.npwp3);
						jQuery('#npwpd_npwrd4').val(data.npwp2);
						jQuery('#date_1').val(data.tanggal_penetapan);
						jQuery('#date_2').val(data.tanggal_jatuh_tempo);
						jQuery('#nominal').val(data.nominal);
						jQuery('#penetapan_pr_id').val(id);
						jQuery('#badan_id').val(data.id_badan);
						//alert(id);
				}, "json");
				
            }
        },
		onHeaderClick:function(stat){ 
			if(stat == 'visible' ){ 
				jQuery("#filter").css("display","none"); 
			} 
		}, 
		gridComplete: function(){
			//alert(document.width);
            jQuery("#htmlTable3").setGridWidth( document.width - 500 < 100 ? 100:document.width - 180);
            return true;
        }
    }).navGrid('#htmlPager3'
        ,{edit:false,add:false,del:false,search:false,refresh:true,view:true}
        //,{} // edit
        //,{height:200, width:500,reloadAfterSubmit:false} // add
        //,{} // del
        ,{} // search
		,{height:250,width:500,jqModal:false,closeOnEscape:true}
    ).hideCol('id');
});

</script>
<div class='demo'>
	<div id="tabs">
		<ul>
			<li><a href="#tab1"><em>Form</em></a></li>
			
			<li><a href="#tab3"><em>Daftar</em></a></li>
		</ul>
	<!--<div class="yui-content">-->
		<div id="tab1">
			<input type="hidden" name="id_cetak" id="id_cetak" />
			<input type="hidden" name="badan_id" id="badan_id" />
			<input type="hidden" name="penetapan_pr_id" id="penetapan_pr_id" />
			<input type="hidden" id="IdTeguranHid" name="IdTeguranHid" />
			<input type="hidden" id="IdEdit" name="IdEdit" />
			<input type='hidden' name='NamaPendataan' id='NamaPendataan' value='HOTEL'>
			<div style='padding:5px;'>
				<fieldset>
				<legend></legend>
					<div >
						<div class="form_master">
							<fieldset class="form_frame" style="width:700px;">
							<div>
								<label class="leftField">No. Reg Form</label>
								<? 
								$cr = gcms_query("select max(teguran_pr_no) AS IdMax from teguran_pr");
								$max = gcms_fetch_object($cr);
								$new_max = $max->IDMAX+1;
								$new_no = sprintf("%06d",$new_max);
								?>
								<input type="text" name="no_pendaftaran" id='NoRegForm' size='25' value="<?=$new_no?>" readonly/>
								<input type="hidden" id="idHid" name="NoHid" value="<?=$new_max?>" /> 
								&nbsp;&nbsp;Tanggal Tagih&nbsp;&nbsp;
								<input type='text' id='date_3' name='tanggal_teguran' title="Tanggal Penagihan" size="15">
							</div>
							<div>
								<label class="leftField">Nama Petugas<b class="wajib">*</b></label>
								<input type="hidden" name="petugas_id" id='petugas_id'  value=""/>
								<input type="text" name="nama_petugas" id='nama_petugas' title="Nama Petugas" />
								<input type="button" value="*" id="cari_petugas"/>
							</div>						
							<div>
								<label class="leftField">NPWPD/NPWRD<b class="wajib">*</b></label>
								<select name="kode_npwp" id="kode_npwp">
									<option value="PAJAK">P</option>
									<option value="RETRIBUSI">R</option>
								</select>
								<input type="hidden" name="npwpd_npwrd1" id='npwpd_npwrd1' title="NPWP" value=""/>
								<input type="hidden" name="pendaftaran_id" id='pendaftaran_id' />
								<input type="text" name="npwpd_npwrd2" id='npwpd_npwrd2' readonly="" value="" size="7" />
								<input type="text" name="npwpd_npwrd3" id='npwpd_npwrd3' readonly="" value="" size="2" />
								<input type="text" name="npwpd_npwrd4" id='npwpd_npwrd4' readonly="" value="" size="2" />
								
								<input type="button" value="*" id="cari_npwp"/>
							</div>
							<div>
								<label class="leftField">Periode SPT</label>
								<input type="text" name="Periode" id='Periode' readonly="" value="" size="10"/>
								<input type="hidden" id="SptIdHid" name="SptIdHid" />
								&nbsp;Nomor SPT&nbsp;
								<input type="text" name="NomorSpt" id='NomorSpt' readonly="" value="" size="10"/>
							</div>
							<div>
								<label class="leftField">Nama WP/WR</label>
								<input type="text" name="nama_wp_wr" id='nama_wp_wr' readonly="" value="" size="45"  />
							</div>
							<div>
								<label class="leftField">Tanggal Penetapan</label>
								<input type='text' id='date_1' readonly="" name='TglPenetapan' title="Tanggal Penetapan" size="15">
							</div>
							<div>
								<label class="leftField">Tanggal Jatuh Tempo</label>
								<input type='text' id='date_2' readonly="" name='TglJatuhTempo' title="Tanggal Jatuh Tempo" size="15">
							</div>
							<!--<div>
								<label class="leftField">Lama Tunggakan</label>
								<input type="text" name="Tunggakan" id='Tunggakan' value=""  /> /Hari
							</div>-->
							<div>
								<label class="leftField">Keterangan</label>
								<textarea name="keterangan" id="keterangan" col="4" row="3"></textarea>
							</div>
							<div>
								<label class="leftField">&nbsp;Total Tunggakan</label>
								<input type="text" style="height:30px; font-size:18px; text-align:right;" readonly="" value="" size="20" class="inputbox" id="nominal" name="nominal">
							</div>
							<div>
								<label class="leftField"><b class="wajib">*</b>&nbsp;Wajib Diisi</label>
								<input class="btn" type="button" name="proses" id="proses" value="Simpan">
							</div>
							</fieldset>
							</div>
							
							<div id="confirmDialog" class="hidden">
							<fieldset class="mainForm">
							<label>
							<input class="closeForm" type="button" name="close" value="Batal" onclick="closeForm('confirmDialog');" /></label>
							</fieldset>
							
						</div>
						<div class="footer_space">&nbsp;</div>
					</div>
					<div id='asb_simulasi_form'>
						<div style='padding:5px'>
							<table id="htmlTable3" class="scroll"></table>
							<div id="htmlPager3" class="scroll"></div>
							<div id="filter" style="margin-left:30%;display:none">Search Invoices</div> 
						</div>
					</div>		
				</fieldset>
			</div>
		</div>
		<!--<div id="tab2">
			<div style='padding:5px;'>
				<fieldset>
					<legend>Detail Hotel</legend>
					<div id='asb_simulasi_form'>
					<table id="htmlTable5" class="scroll"></table>
							<div id="htmlPager5" class="scroll"></div>
					
					<input type="hidden" id="row_cek" name="row_cek" value="0" /> 
					
						</div>
					
				</fieldset>
			</div>
		</div>-->
		<div id="tab3">
			<div style='padding:5px;'>
				<fieldset>
				<legend>Daftar</legend>
					<div id='asb_simulasi_form'>
						<div style='padding:5px'>
							<table id="htmlTable1" class="scroll"></table>
							<div id="htmlPager1" class="scroll"></div>
						</div>
							<input type="button" id="button_cetak" disabled="disabled" class="btn" value="Cetak" />
					</div>
					<div id="div_daftar_npwp" align="center"></div>
				</fieldset>
			</div>
		<div>
	</div>
</div>

<div id="dialog1">
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
											<div id="Npwp">
											<table id="htmlTable2" class="scroll"></table>
											<div id="htmlPager2" class="scroll"></div>
											</div>
											<div id="Petugas">
											<table id="htmlTable4" class="scroll"></table>
											<div id="htmlPager4" class="scroll"></div>
											</div>
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
<script>
	/*(function(){
		var tabView = new YAHOO.widget.TabView('demo');   
	})();*/
YAHOO.namespace("hendra");
    

function OpenSpt(){
	gcms_open_form('form.php?mod=penetapan&func=open_spt','detail',"availWidth", "availHeight");
}


function showDialogNpwp(){
	
		YAHOO.hendra.dialog1.show();
		document.getElementById("Npwp").style.display="inherit";
  		document.getElementById("Rekening").style.display="none";
			
}
function ShowDialogRekening(){
		YAHOO.hendra.dialog1.show();
		document.getElementById("Npwp").style.display="none";
  		document.getElementById("Rekening").style.display="inherit";
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
	YAHOO.hendra.dialog1.setHeader("Daftar");
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
    colNames:['id','No Penetapan','NPWP','Nama ','Tanggal Penetapan','Tanggal Setor', 'Nominal'],
    colModel :[{name:'id',index:'id',width:20,search:false},
			{name:'NoPenetapan',index:'NoPenetapan',width:100,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50}},
			{name:'NPWP',index:'NPWP',width:80,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			{name:'Nama',index:'Nama',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			{name:'TglPenetapan',index:'TglPenetapan',width:180,editable:false,edittype:'text',formatter:'date', sorttype:"date"},
			{name:'TglSetor',index:'TglSetor',width:100,editable:false,edittype:'text',formatter:'date', sorttype:"date"},
			{name:'Nominal',index:'Nominal',width:100,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}}	
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
                //lastsel2=id; 
				ajax_do('<?=$expath?>SetData.php?Npwp='+id);
				//var isi  = document.getElementById('id_isi_detail').value;
				//ReloadData(isi);
				//YAHOO.hendra.dialog1.hide();
				$('#Npwp').dialog('close');
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

var lastsel4;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable4").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_petugas',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_petugas&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','NIP','Nama','Golongan','Pangkat','Jabatan'],
    colModel :[{name:'id',index:'id',width:20,search:false},
			   {name:'nip',index:'nip',width:70,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50}},
			   {name:'nama',index:'nama',width:150,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50}},
			   {name:'golongan',index:'golongan',width:50,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			   {name:'pangkat',index:'pangkat',width:120,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			   {name:'jabatan',index:'jabatan',width:120,editable:false,edittype:'text'}
	],
    pager: jQuery('#htmlPager4'),
    height:100,
	width:600,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:true,
    sortname: 'id',
	rownumbers: true,
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data Petugas',
        onSelectRow: function(id){ 
            if(id && id!==lastsel4){ 
			//alert(id);
                jQuery("#htmlTable4").restoreRow(lastsel4); 
                jQuery("#htmlTable4").editRow(id,true); 
                
				ajax_do('<?=$expath?>SetData.php?Petugas='+id);
				
				$('#Petugas').dialog('close');
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable4").setGridWidth( document.width - 500 < 100?100:document.width - 500);
                return true;
            }
    }).navGrid('#htmlPager4'
        ,{add:false,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:300} // add
        ,{} // del
        ,{} // search
        
        ).hideCol(['id']);  /*end of on ready event */ 
});

$('#proses').click(function(){
		var formObject= document.getElementById("entri_TeguranWpWr");
		if(saveEntry(formObject)){
			
			jQuery(formObject).ajaxSubmit({
				success: function(response){
					act = document.getElementById('caction').value;
					tmp = {}; count=0; sv=0;
					if(response!='!'){
						alert(response);
						jQuery("#htmlTable3").trigger("reloadGrid");
						formObject.reset();
					}else{
						alert('error '+response);
					}
				}
			})
			//		
			//var transaction = YAHOO.util.Connect.asyncRequest('POST',"request.php",callback,null);
		}
});

function setNomor(){
	<? $cr = gcms_query("select max(teguran_pr_no) AS IdMax from teguran_pr");
									$max = gcms_fetch_object($cr);
									$new_max = $max->IDMAX+1;
									$new_no = sprintf("%06d",$new_max);
	?>
	document.getElementById('NoRegForm').value = <?=$new_no?>;
} 
$('#cari_npwp').click(function(){
		
		//$("#htmlTable2").setGridParam({url:'request.php?mod=pendataan&func=list&sender=daftarnpwp', datatype:'json'}).setCaption("Daftar NPWPD/RW ").trigger("reloadGrid");
		$('#Npwp').dialog('open');
//alert('xxxx');
});
$("#Npwp").dialog({

		bgiframe: true,
		resizable: false,
		height:300,
		width:800,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
});
$('#cari_petugas').click(function(){
		
		//$("#htmlTable2").setGridParam({url:'request.php?mod=pendataan&func=list&sender=daftarnpwp', datatype:'json'}).setCaption("Daftar NPWPD/RW ").trigger("reloadGrid");
		$('#Petugas').dialog('open');
//alert('xxxx');
});
$("#Petugas").dialog({

		bgiframe: true,
		resizable: false,
		height:300,
		width:800,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
});

for(i=1;i<=3;i++){
$('#date_'+i).datepicker({changeMonth: true, changeYear: true});
}
</script>
<? yuiEndEntry() ?>