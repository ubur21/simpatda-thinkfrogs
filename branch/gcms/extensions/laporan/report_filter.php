<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
$include='<script type="text/javascript" src="./extensions/fastreport/fastreport.js"></script>'."\n";
		
gcms_add_to_head($include);


?>
<script>
$().ready(function() {
	
	var $tabs = $("#tabs").tabs();
	
});
var lastsel1;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable21").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=daftarnpwp2',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=daftarnpwp2&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','No Kohir','NPWP','Nama', 'Alamat','Tanggal Penetapan','Tanggal Setor', 'Nominal','Jumlah'],
    colModel :[{name:'id',index:'id',width:20,search:false},
			{name:'NoPenetapan',index:'NoPenetapan',width:100,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50}},
			{name:'NPWP',index:'NPWP',width:80,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			{name:'Nama',index:'Nama',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			{name:'ALamat',index:'Alamat',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			{name:'TglPenetapan',index:'TglPenetapan',width:180,editable:false,edittype:'text',formatter:'date', sorttype:"date"},
			{name:'TglSetor',index:'TglSetor',width:100,editable:false,edittype:'text',formatter:'date', sorttype:"date"},
			//{name:'NoKohir',index:'NoKohir',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			{name:'Nominal',index:'Nominal',width:100,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},	
			{name:'jumlah',index:'jumlah',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
	],
    pager: jQuery('#htmlPager21'),
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
            if(id && id!==lastsel1){ 
			//alert(id);
                jQuery("#htmlTable21").restoreRow(lastsel1); 
                jQuery("#htmlTable21").editRow(id,true); 
				ajax_do('<?=$expath?>SetData.php?Npwp='+id);
				document.getElementById('IdHiddeNpwp').value=id;
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable21").setGridWidth( document.width - 500 < 100?100:document.width - 500);
                return true;
            }
    }).navGrid('#htmlPager21'
        ,{add:false,edit:false,del:false}
        ,{} // edit
        ,{height:120,  width:300} // add
        ,{} // del
        ,{} // search
        
        ).hideCol(['id']);  /*end of on ready event */ 
});
</script>
<div class='demo'>
	<div id="tabs">
		
	<!--<div class="yui-content">-->
		
			
			<div style='padding:5px;'>
					
				<div >
					<fieldset>
					<legend>Detail Tunggakan</legend>
					
					<table id="htmlTable21" class="scroll"></table>
							<div id="htmlPager21" class="scroll"></div>
					<!--<input type="BUTTON" id="bedata" value="Edit Selected" />-->
					<input type="hidden" id="row_cek" name="row_cek" value="0" /> 
					
					
				</fieldset>
						<div class="form_master">
							<fieldset class="form_frame" style="width:700px;">
							<legend>CETAK DAFTAR TUNGGAKAN</legend>
							<div>
								<label class="leftField">Dari N P W P D</label>
								<select name="kode_npwp" id="kode_npwp">
									<option value="PAJAK">P</option>
									<option value="RETRIBUSI">R</option>
								</select>
								<input type="hidden" name="npwpd_npwrd1" id='npwpd_npwrd1' title="NPWP" value=""/>
								<input type="hidden" name="IdHiddeNpwp" id='IdHiddeNpwp' />
								<input type="text" name="npwpd_npwrd2" id='npwpd_npwrd2' value="" size="7" />
								<input type="text" name="npwpd_npwrd3" id='npwpd_npwrd3' value="" size="2" />
								<input type="text" name="npwpd_npwrd4" id='npwpd_npwrd4' value="" size="2" />
								<input type="button" value="*" id="cari_npwp"/>  
							
							</div>
							<div>
								<label class="leftField">N P W P D</label>
								<select name="kode_npwp2" id="kode_npwp2">
									<option value="PAJAK2">P</option>
									<option value="RETRIBUSI2">R</option>
								</select>
								<input type="hidden" id="x_npwp" />
								<input type="hidden" name="npwpd_npwrd21" id='npwpd_npwrd21' title="NPWP" value=""/>
								<input type="hidden" name="IdHiddeNpwp2" id='IdHiddeNpwp2' />
								<input type="text" name="npwpd_npwrd22" id='npwpd_npwrd22' value="" size="7" />
								<input type="text" name="npwpd_npwrd23" id='npwpd_npwrd23' value="" size="2" />
								<input type="text" name="npwpd_npwrd24" id='npwpd_npwrd24' value="" size="2" />
								<input type="button" value="*" id="cari_npwp2"/>  
							
							</div>
							<div>
								<label class="leftField">Masa Pajak</label>
								<input type="text" name="Periode" id='date_1'  value="" size="10"/>
								<input type="text" id="date_2" name="Periode2" size="10"/>
								
							</div>
							<?
							//for($i=1;$i<=3;$i++){
							//	$ccc = 'sssss'.$i;
								$pejabat = gcms_query("select pj.pejabat_id,pj.nama AS nama_pejabat,pj.nip,jb.nama AS jabatan,pk.nama AS pangkat,g.nama AS golongan
										from pejabat pj    
										left join jabatan jb on pj.jabatan_id = jb.id
										left join pangkat pk on pj.pangkat_id = pk.id
										left join golongan g on pj.golongan_id = g.id
										");
								///$xxx.''.$i = gcms_query($pejabat.''.$i]);
								//echo $pejabat.''.$i;
							//}
							
							?>
							<div>
								<label class="leftField">Menyutujui</label>
								<select name="menyutujui" id="menyutujui">
									<? while($row=gcms_fetch_object($pejabat)){?>
									<option value="<?=$row->pejabat_id?>"><?=$row->nama_pejabat?></option>
									<? }?>
								</select>
							</div>
							<?
								$pejabat2 = gcms_query("select pj.pejabat_id,pj.nama AS nama_pejabat,pj.nip,jb.nama AS jabatan,pk.nama AS pangkat,g.nama AS golongan
										from pejabat pj    
										left join jabatan jb on pj.jabatan_id = jb.id
										left join pangkat pk on pj.pangkat_id = pk.id
										left join golongan g on pj.golongan_id = g.id
										");
							?>
							<div>
								<label class="leftField">Mengetahui</label>
								<select name="mengetahui" id="mengetahui">
									<? while($row=gcms_fetch_object($pejabat2)){?>
									<option value="<?=$row->pejabat_id?>"><?=$row->nama_pejabat?></option>
									<? }?>
								</select>
							</div>
							<?
								$pejabat3 = gcms_query("select pj.pejabat_id,pj.nama AS nama_pejabat,pj.nip,jb.nama AS jabatan,pk.nama AS pangkat,g.nama AS golongan
										from pejabat pj    
										left join jabatan jb on pj.jabatan_id = jb.id
										left join pangkat pk on pj.pangkat_id = pk.id
										left join golongan g on pj.golongan_id = g.id
										");
							?>						
							<div>
								<label class="leftField">Diperiksa Oleh</label>
								<select name="diperiksa" id="diperiksa">
									<? while($row=gcms_fetch_object($pejabat3)){?>
									<option value="<?=$row->pejabat_id?>"><?=$row->nama_pejabat?></option>
									<? }?>
								</select>
							</div>
							<!--<div>
								<label class="leftField">NIP</label>
								<input type="text" name="nip" id='nip' readonly="" value="" size="10"/>
								
							</div>-->
							<div>
								<label class="leftField"></label>
								<input class="btn" type="button" value="Cetak" id="cetak"/>
								<input class="btn" type="button" value="Bersih" id="clear"/>
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
					
				
			</div>
		
		
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
											<div id="Rekening">
											<table id="htmlTable3" class="scroll"></table>
											<div id="htmlPager3" class="scroll"></div>
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
                var x = document.getElementById('x_npwp').value;
				  if(x==1){
					var npwp2 = 1;
				  }else{
					var npwp2 ='';
				  }
				ajax_do('<?=$expath?>SetData.php?Npwp='+id+'&Kode='+npwp2);
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
						//jQuery("#htmlTable3").trigger("reloadGrid");
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

$('#open_rekening').click(function(){
		
		//$("#htmlTable2").setGridParam({url:'request.php?mod=pendataan&func=list&sender=daftarnpwp', datatype:'json'}).setCaption("Daftar NPWPD/RW ").trigger("reloadGrid");
		$('#Rekening').dialog('open');
//alert('xxxx');
});
$("#Rekening").dialog({

		bgiframe: true,
		resizable: false,
		height:350,
		width:700,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
});
 
$('#cari_npwp').click(function(){
	document.getElementById('x_npwp').value='';
		$('#Npwp').dialog('open');

});
$('#cari_npwp2').click(function(){
	document.getElementById('x_npwp').value=1;
		$('#Npwp').dialog('open');

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

var lastsel3;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable3").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_rekening',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_rekening&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','Kode Rekening','Nama Rekening','Tarif Dasar','Tarif Persen'],
    colModel :[{name:'id',index:'id',width:20,search:false},
			   {name:'kode_rekening',index:'kode_rekening',width:100,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50}},
			   {name:'nama_rekening',index:'nama_rekening',width:200,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			   {name:'tarif_dasar',index:'tarif_dasar',width:120,align:"right",sorttype:"float",editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			   {name:'persen_tarif',index:'persen_tarif',width:60,align:"right",sorttype:"float",editable:false,edittype:'text'}
	],
    pager: jQuery('#htmlPager3'),
    height:100,
	width:600,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:true,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data Rekening',
        onSelectRow: function(id){ 
            if(id && id!==lastsel3){ 
			//alert(id);
                jQuery("#htmlTable3").restoreRow(lastsel3); 
                jQuery("#htmlTable3").editRow(id,true); 
                //lastsel3=id; 
				ajax_do('<?=$expath?>SetData.php?Rekening='+id);
				
				$('#Rekening').dialog('close');
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable3").setGridWidth( document.width - 500 < 100?100:document.width - 500);
                return true;
            }
    }).navGrid('#htmlPager3'
        ,{add:false,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:300} // add
        ,{} // del
        ,{} // search
        
        ).hideCol(['id']);  /*end of on ready event */ 
}
);

for(i=1;i<=3;i++){
$('#date_'+i).datepicker({changeMonth: true, changeYear: true});
}

$('#cetak').click(function(){
	var x = document.getElementById('npwpd_npwrd1').value;
		//y = 
	if(x=='' || x==0){
		alert('xxxxxx');
	}else{
		var nameFile,template;
                nameFile="Surat_Teguran";
                template="report_surat_teguran_per_npwp.fr3";
        var key = "no_npwp="+x;
			key2 = "filter=NPWP";
        var att = 1;
            fastReportStart(nameFile, template, 'pdf', key, key2, att);
	}
});
$('#clear').click(function(){
	for(i=1;i<=4;i++){
		//jQuery('#npwpd_npwrd'+i).val();
	document.getElementById('npwpd_npwrd'+i).value='';
	//alert(i);
	}
	document.getElementById('IdHiddeNpwp').value='';
	jQuery("#htmlTable21").trigger("reloadGrid");
});
</script>
<? yuiEndEntry() ?>