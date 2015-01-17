<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_pendataan_PajakHotel");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);


?>
<script>
$().ready(function() {
	
	var $tabs = $("#tabs").tabs();
	
});
var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=penetapan_PajakHotel',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=penetapan_PajakHotel&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['No.','id','Tanggal Entri','NPWPD/NPWRD','Nama WP/WR', 'Alamat','Sistem Pemungutan','Periode Penjualan Awal', 'Periode Penjualan Akhir','Nama Rekening','Dasar Pengenaan','Persen (%)','Pajak'],
    colModel :[
	{ name:'no', index:'nomor',width:20  ,search:false },{name:'id',index:'id',width:20,search:false},
	{name:'TglEntri',index:'TglEntri',width:80,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'NpwpdNpwrd',index:'NNpwpdNpwrd',width:80,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'Nama',index:'Nama',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'Alamat',index:'Alamat',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'pemungutan',index:'pemungutan',width:120,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'periodeAwal',index:'periodeAwal',width:140,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'periodeAkhir',index:'periodeAkhir',width:140,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'NamaRekening',index:'NamaRekening',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'TarifDasar',index:'TarifDasar',width:100, align:"right",sorttype:"float",editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'Persen',index:'Persen',width:60,editable:false, align:"right",sorttype:"float",edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'Pajak',index:'Pajak',width:100,editable:false,edittype:'text', align:"right",sorttype:"float",editoptions: {size:50, maxlength: 50},editrules: {required:true}
	}
	
	],
    pager: jQuery('#htmlPager'),
    height:150,
    rowNum:10,
    rowList:[5,10,15],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data Penetapan Pajak Hotel',
    //SUb Grid
		subGrid: true,
		multiselect:true,
		multiboxonly:true,
		toolbar: [true,"top"],
		footerrow:true,userDataOnFooter:true,
		subGridRowExpanded: function(subgrid_id, id) {
			//alert(id);
			var subgrid_table_id, pager_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			pager_id = "p_"+subgrid_table_id;
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			jQuery("#"+subgrid_table_id).jqGrid({ 
				url:"request.php?mod=pendataan_hotel&func=list&sender=DataHotel&action=DetailHotelList&val="+id, 
				datatype: "json", 
				rownumbers: true,
				colNames: ['id','Nama Kamar','Jumlah Kamar','Tarif Kamar'], 
				colModel: [
					{ name:'id' ,index:'id'	,width:20,key:true ,search:false},
					{name:"nama_kamar",index:"nama_kamar",width:120,search:false,align:"left"}, 
					{name:"jumlah_kamar",index:"jumlah_kamar",width:70,search:false}, 
					{name:"tarif_kamar",index:"tarif_kamar",width:120,align:"center",formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}},
					
				], 
				rowNum:20,pager:pager_id,sortname:'num',sortorder:"asc",height:'100%',footerrow:true,userDataOnFooter:true
			});
			jQuery("#"+subgrid_table_id)
				.navGrid("#"+pager_id,{edit:false,add:false,del:false,search:false})
				.hideCol(['id']);
		},
		subGridRowColapsed: function(subgrid_id, row_id){ 
			// this function is called before removing the data 
			//var subgrid_table_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			jQuery("#"+subgrid_table_id).remove();
		},
	//End Sub
		
		ondblClickRow: function(id){ 
            if(id && id!==lastsel){
				jQuery("#htmlTable5").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=DataHotel&action=DetailHotel&val="+id}).trigger("reloadGrid");
				//jQuery("#htmlTable5").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=default+row='+cek,			
				jQuery.post("request.php?page=<?=$_REQUEST['page']?>&sender=get_DataFormHotel&val="+id, {},
					function(data){						
						jQuery('#proses').val('Ubah');
						jQuery('#batal').attr('disabled',false);
						jQuery('#caction').val('edit');
						jQuery('#IdEdit').val('edit');
						jQuery('#idmasters').val(id);
						jQuery('#NoRegForm').val(data.nomor);
						jQuery('#SystemPemungutan').val(data.jenis_pungutan);
						jQuery('#pendaftaran_id').val(data.pendaftaran);
						jQuery('#kode_npwp').val(data.kode_npwp);
						jQuery('#npwpd_npwrd1').val(data.npwp);
						jQuery('#npwpd_npwrd2').val(data.no_pendaftaran);
						jQuery('#npwpd_npwrd3').val(data.lurah);
						jQuery('#npwpd_npwrd4').val(data.camat);
						jQuery('#SptIdHid').val(data.spt_id);
						jQuery('#Periode').val(data.periode_spt);
						jQuery('#NomorSpt').val(data.spt_no);
						jQuery('#memo').val(data.memo);
						jQuery('#date_1').val(data.tgl_proses);
						jQuery('#date_2').val(data.tgl_entry);
						jQuery('#date_3').val(data.periode_awal);
						jQuery('#date_4').val(data.periode_akhir);
						jQuery('#KodeRekening1').val(data.kode_rekening1);
						jQuery('#KodeRekening2').val(data.kode_rekening2);
						jQuery('#KodeRekening3').val(data.kode_rekening3);
						jQuery('#NamaRekening').val(data.nama_rekening);
						jQuery('#id_desa').val(data.id_desa);
						jQuery('input#Tarif').val(formatCurrency(data.tarif));
						jQuery('#persen').val(data.persen);
						jQuery('#pendataan_id_hid').val(data.pendataan_id);
						jQuery('#IdRekening').val(data.rekening_id);
						jQuery('#nama_wp_wr').val(data.nama);
						jQuery('#Alamat').val(data.alamat);
						jQuery('#Kec').val(data.kecamatan);
						jQuery('#Kel').val(data.kelurahan);
						jQuery('input#Pajak').val(formatCurrency(data.nominal));
						jQuery('#row_cek').val(data.row);
						jQuery("#tabs").tabs('select', 0);
						//alert(id);
				}, "json");
				jQuery("#htmlTable5").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=DataRestoran&action=DetailRestoran&val='+id});
                //lastsel=id; 
            }
        },
		
        gridComplete: function(){
		//alert(document.width);
                jQuery("#htmlTable").setGridWidth( document.width - 200 < 400?400:document.width - 200);
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
<div class='demo'>
	<div id="tabs">
		<ul>
			<li><a href="#tab1"><em>Form</em></a></li>
			<li><a href="#tab2"><em>Detail</em></a></li>
			<li><a href="#tab3"><em>Daftar</em></a></li>
		</ul>
	<!--<div class="yui-content">-->
		<div id="tab1">
			
			<input type="hidden" id="IdEdit" name="IdEdit" />
			<input type='hidden' name='NamaPendataan' id='NamaPendataan' value='HOTEL'>
			<div style='padding:5px;'>
				<fieldset>
				<legend>PENETAPAN PAJAK HOTEL</legend>
					<div id='asb_simulasi_form'>
						<div class="singleSide">
							<fieldset class="mainForm">
							<label class="leftField"><span>No. Reg Form</span>
							<? $cr = gcms_query("select max(pendataan_no) AS IdMax from pendataan_spt");
								$max = gcms_fetch_object($cr);
								$new_max = $max->IDMAX+1;
								$new_no = sprintf("%06d",$new_max);
							?>
							<input type="text" name="no_pendaftaran" id='NoRegForm' size='25' value="<?=$new_no?>" readonly/>
							<input type="hidden" id="idHid" name="NoHid" value="<?=$new_max?>" /> 
							</label>
							<label class="leftField"><span>Tgl Proses <b class="wajib">*</b></span>
							<input id='date_1' name='TglProses' type='text' title='Tanggal Proses' /><!--<input type="button" id="reset_tgl" size="2" value=".." />-->
								
							</label>
							<label class="leftField"><span>Tgl Entri <b class="wajib">*</b></span> 
							<input type='text' id='date_2' name='TglEntri' title="Tanggal Entri" size="25">
							<!--<input type="button" id="reset_tgl2" size="2" value=".." />-->
								
							</label>
							
							<label class="leftField"><span>NPWPD/NPWRD<b class="wajib">*</b></span></span>
							<select name="kode_npwp" id="kode_npwp">
								<option value="PAJAK">P</option>
								<option value="RETRIBUSI">R</option>
							</select>
							<input type="hidden" name="npwpd_npwrd1" id='npwpd_npwrd1' title="NPWP" value=""/>
							<input type="hidden" name="pendaftaran_id" id='pendaftaran_id' />
							<input type="text" name="npwpd_npwrd2" id='npwpd_npwrd2' value="" size="7" />
							<input type="text" name="npwpd_npwrd3" id='npwpd_npwrd3' value="" size="2" />
							<input type="text" name="npwpd_npwrd4" id='npwpd_npwrd4' value="" size="2" />
							<!--<input type="text" name="npwpd_npwrd5" id='npwpd_npwrd5' value="" size="2" />-->
							<!--<input type="button" value="*" onclick="showDialogNpwp()" />-->
							<input type="button" value="*" id="cari_npwp"/>
							</label>
							<label class="leftField"><span>Periode SPT</span>
							<input type="text" name="Periode" id='Periode' value="" size="10"/>
							<input type="hidden" id="SptIdHid" name="SptIdHid" />
							&nbsp;Nomor SPT&nbsp;
							<input type="text" name="NomorSpt" id='NomorSpt' value="" size="10"/>
							</label>
							<label class="leftField"><span>Nama WP/WR</span><input type="text" name="nama_wp_wr" id='nama_wp_wr' value="" size="25"  /></label>
							<label class="leftField"><span>Alamat</span><textarea name="Alamat" id="Alamat" col="4" row="3"></textarea></label>
							<label class="leftField"><span>Kelurahan</span>
							<input type="text" name="Kel" id='Kel' value=""  />
							<input type="hidden" name="id_desa" id='id_desa' value=""  />
							</label>
							<label class="leftField"><span>Kecamatan</span><input type="text" name="Kec" id='Kec' value=""  /></label>
							<label class="leftField"><span>Kabupaten</span><input type="text" name="Kab" id='Kab' value="" /></label>
							</fieldset>
							</div>
							<div>
							<fieldset class="mainForm">
							<label class="leftField"><span>Sistem Pemungutan <b class="wajib">*</b></span>
							<select name='SystemPemungutan' id='SystemPemungutan' title="Sistem Pemungutan">
							<option value=''>---</option>
							<option value='SELF'>Self Assesment</option>
							<option value='OFFICE'>Official Assesment</option>
							</select>
							</label>
							<label class="leftField"><span>Periode Transaksi <b class="wajib">*</b></span>
								&nbsp;Dari :&nbsp;<input type="text" name="TglJualMulai" title="Periode Transaksi Awal" id="date_3" /><!--<input type="button" id="reset_tgl3" size="2" value=".." />-->
								
							</label>
							<label class="leftField"><span></span>
								&nbsp;S/D&nbsp;&nbsp;:&nbsp;<input type="text" title="Periode Transaksi Akhir" name="TglJualSampai" id="date_4" /><!--<input type="button" id="reset_tgl4" size="2" value=".." />-->
								
							</label>
							<label class="leftField"><span>Memo</span>
							<textarea name="memo" id="memo" col="4" row="3"></textarea>
							</label>
							<label class="leftField"><span>Kode Rekening <b class="wajib">*</b></span>
								<input type="text" name="KodeRekening1" title="Kode Rekening" id="KodeRekening1" size="5" />
								<input type="text" name="KodeRekening2" id="KodeRekening2" size="5" /> 
								<input type="text" name="KodeRekening3" id="KodeRekening3" size="5" />
								<input type="hidden" name="IdRekening" id="IdRekening" />
								<input type="hidden" name="pendataan_id_hid" id="pendataan_id_hid" />
								<!--<input type="button" value="*" onclick="ShowDialogRekening()" />  -->
								<input type="button" value="*" id="open_rekening"/>  
							</label>
							<label class="leftField"><span>Nama Rekening <b class="wajib">*</b></span>
								<input type="text" name="NamaRekening" id="NamaRekening" size="45" />
							</label>
							<label class="leftField"><span>Dasar Pengenaan</span>
								<input type="text" name="Tarif" style="text-align:right;" id="Tarif" onBlur="cnumber(this)" />&nbsp;Persen tarif<input type="text" name="persen" id="persen" onChange="cnumber()" style="width:50px;" />%
							</label>
							<label class="leftField"><span>Pajak</span>
								<input type="text" name="Pajak" id="Pajak" style="width:180px; text-align:right; font-size:22px; color:#00FF33; height:30px;" readonly="" />
							</label>
							<label class="leftField"><span><b class="wajib">*</b>&nbsp;Wajib Diisi</span>
							<input class="btn" type="button" name="proses" onclick='Simpan()' value="Simpan">
							
							</label>
							
							</fieldset>
							
							<!--<label style="float:right; padding-right:5px;"> 				
							<input id="btn" type="button" name="Detail" onclick='DetailHotel()' value="Deatil Info">
							</label>-->
							</div>
							<div id="confirmDialog" class="hidden">
							<fieldset class="mainForm">
							<label>
							<input class="closeForm" type="button" name="close" value="Batal" onclick="closeForm('confirmDialog');" /></label>
							</fieldset>
						</div>
						<div class="footer_space">&nbsp;</div>
					</div>		
				</fieldset>
			</div>
		</div>
		<div id="tab2">
			<div style='padding:5px;'>
				<fieldset>
					<legend>Detail Hotel</legend>
					<div id='asb_simulasi_form'>
					<table id="htmlTable5" class="scroll"></table>
							<div id="htmlPager5" class="scroll"></div>
					<!--<input type="BUTTON" id="bedata" value="Edit Selected" />-->
					<input type="hidden" id="row_cek" name="row_cek" value="0" /> 
					<!--<input type='hidden' id='cindex' name='cindex' value='0'/>
					
					<table id="detail">
					<tr><th>Nama Kamar</th><th align="center">Jumlah Kamar</th><th align="center">Tarif</th>
					<th><input id="hapus_detail_all" type="button" name="hapus_detail_all" value="Hapus Semua" />
					</th></tr>	
					</table>
					<input id="tambah_detail" type="button" name="tambah_detail" value="Tambah Detail" />
					<span>Total</span>
					<input type="hidden" id="total_row" name="total_row" />
					<input type="hidden" id="total_hid" name="total_hid" />
					<input class="total" id="total_tarif" type="text" name="total" value="0.00" disabled />
					<input id="id_isi_detail" type="hidden" name="id_isi_detail" value="0" />-->
						</div>
					
				</fieldset>
			</div>
		</div>
		<div id="tab3">
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
				ajax_do('<?=$expath?>SetData.php?RekeningHotel='+id);
				//tutup();
				proses();
				//YAHOO.hendra.dialog1.hide();
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

function Simpan(){
		var formObject= document.getElementById("entri_pendataan_PajakHotel");
		if(saveEntry(formObject)){
			//YAHOO.util.Connect.setForm(formObject);
			var rows = jQuery('#htmlTable5').getGridParam('records');
			//alert(rows);
			jQuery(formObject).ajaxSubmit({
				success: function(response){
					act = document.getElementById('caction').value;
					//alert(response);
					tmp = {}; count=0; sv=0;
					if(response!='!'){
						// proses grid,  - delete
						//alert('Response'+response);
						if(rows > 0){
							jQuery("#htmlTable5 > tbody > tr").each(function (){
								tmp = {};
								tmp[this.id] = jQuery("#htmlTable5").getRowData(this.id);
								tmp[this.id].FK=response;
								tmp[this.id].page=<?=$_REQUEST['page']?>;
								tmp[this.id].mode='asyc';							
								if(act!='') tmp[this.id].action='edit';
								tmp[this.id].rows=rows;
								tmp[this.id].count=count;
								tmp[this.id].detail=true;
								tmp[this.id].sender='entri_pendataan_PajakHotel';
						//		alert(YAHOO.lang.dump(tmp));
								$.post("request.php", tmp[this.id],
									function(data){
							//			alert(data);
										count++;;
										if(data=='!') sv=0;
										else sv=1;
										if(rows==count){
											if(sv){
												alert('Data telah tersimpan');
												jQuery("#htmlTable5").trigger("reloadGrid");
																formObject.reset();
												//				if(act!='') document.getElementById('caction').value='';
												//				setNomor();
											}else alert('cek lagi !');
										}		
									}
								);
							});
						}else{
							alert('Data telah tersimpan');
							formObject.reset();
						}
					}else{
						alert('error '+response);
					}
				}
			})
			//		
			//var transaction = YAHOO.util.Connect.asyncRequest('POST',"request.php",callback,null);
		}
}
var callback = {
	success: function(o) {
	var formObject = document.getElementById("entri_pendataan_PajakHotel");
	var temp  = o.responseText;
	alert(temp);
	 //formObject.reset();
	},
    failure: function(o) {alert("Failure")}
}
function cnumber(x){
var tarif = removeCommas(document.getElementById('Tarif').value);
	persen = removeCommas(document.getElementById('persen').value);
	
	total_tarif = tarif *(persen/100);
	document.getElementById('Pajak').value = formatCurrency(total_tarif);
}
function tutup(){
//window.close();
}
function proses(){
var tarif = document.getElementById('Tarif').value;
	persen = document.getElementById('persen').value;
	
	total_tarif = tarif *(persen/100);
	document.getElementById('Pajak').value = formatCurrency(total_tarif);
}
function DetailHotel(){
	var a = document.getElementById('idHid').value;
	gcms_open_form('form.php?page='+<?=$_REQUEST['page']?>+'&action=detail_hotel&detail='+a,'detail',"availWidth", "availHeight");
}

function setNomor(){
	<? $cr = gcms_query("select max(hotel_id) AS IdMax from pendataan_hotel");
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

for(i=1;i<=4;i++){
$('#date_'+i).datepicker({changeMonth: true, changeYear: true});
}
</script>
<? yuiEndEntry() ?>