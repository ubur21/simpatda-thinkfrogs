<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_pendataan_PajakHiburan");

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
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=penetapan_PajakHiburan',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=penetapan_PajakHiburan&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','Tanggal Entri','NPWPD/NPWRD','Nama WP/WR', 'Alamat','Sistem Pemungutan','Periode Penjualan Awal', 'Periode Penjualan Akhir','Dasar Pengenaan'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false

	},{
		name:'TglEntri',index:'TglEntri',width:100,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'NpwpdNpwrd',index:'NNpwpdNpwrd',width:120,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'Nama',index:'Nama',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'Alamat',index:'Alamat',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'pemungutan',index:'pemungutan',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'periodeAwal',index:'periodeAwal',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'periodeAkhir',index:'periodeAkhir',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},/*{
		name:'NamaRekening',index:'NamaRekening',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'TarifDasar',index:'TarifDasar',width:180, align:"right",sorttype:"float",editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},{
		name:'Persen',index:'Persen',width:60,editable:false, align:"right",sorttype:"float",edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
	},*/{
		name:'Pajak',index:'Pajak',width:100,editable:false,edittype:'text', align:"right",sorttype:"float",editoptions: {size:50, maxlength: 50},editrules: {required:true}
	}
	
	],
    pager: jQuery('#htmlPager'),
    height:150,
    rowNum:5,
    rowList:[5,10,15,100],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data Penetapan Pajak Hiburan',
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
				url:"request.php?mod=pendataan_hiburan&func=list&sender=DataHiburan&action=DetailHiburanList&val="+id, 
				datatype: "json", 
				rownumbers: true,
				colNames: ['id','Kode Rekening','Dasar Pengenaan','Persentase','Pajak'], 
				colModel: [
					{ name:'id' ,index:'id'	,width:20,key:true ,search:false},
					{name:"kode_rekening",index:"kode_rekening",width:120,search:false,align:"left"}, 
					{name:"dasar_pengenaan",index:"dasar_pengenaan",width:70,search:false}, 
					{name:"persen_tarif",index:"persen_tarif",width:120,align:"center",formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}},
					{name:"nominal",index:"nominal",width:120,align:"center",formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}},
				], 
				rowNum:20,pager:pager_id,sortname:'num',sortorder:"asc",height:'100%',footerrow:true,userDataOnFooter:true
			});
			jQuery("#"+subgrid_table_id)
				.navGrid("#"+pager_id,{edit:false,add:false,del:false,search:false})
				.hideCol(['id','TarifDasar','Persen']);
		},
		subGridRowColapsed: function(subgrid_id, row_id){ 
			// this function is called before removing the data 
			//var subgrid_table_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			jQuery("#"+subgrid_table_id).remove();
		},
	//End Sub
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
                //jQuery("#htmlTable").restoreRow(lastsel); 
                //jQuery("#htmlTable").editRow(id,true); 
                //lastsel=id; 
				//ajax_do('<?=$expath?>SetData.php?DataForm='+id);
                }
             },
		
		ondblClickRow: function(id){ 
            if(id && id!==lastsel){
				jQuery("#htmlTable5").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=DataHiburan&action=DetailHiburan&val="+id}).trigger("reloadGrid");
				jQuery("#htmlTable6").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=DataHiburan&action=DetailRincianHiburan&val="+id}).trigger("reloadGrid");
				jQuery.post("request.php?page=<?=$_REQUEST['page']?>&sender=get_DataFormHiburan&val="+id, {},
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
						jQuery('#Tarif').val(data.tarif);
						jQuery('#id_desa').val(data.id_desa);
						/*jQuery('#KodeRekening2').val(data.kode_rekening2);
						jQuery('#KodeRekening3').val(data.kode_rekening3);
						jQuery('#NamaRekening').val(data.nama_rekening);
						jQuery('input#Tarif').val(formatCurrency(data.tarif));
						jQuery('#persen').val(data.persen);*/
						jQuery('#pendataan_id_hid').val(data.pendataan_id);
						//jQuery('#IdRekening').val(data.rekening_id);
						jQuery('#nama_wp_wr').val(data.nama);
						jQuery('#Alamat').val(data.alamat);
						jQuery('#Kec').val(data.kecamatan);
						jQuery('#Kel').val(data.kelurahan);
						//jQuery('input#Pajak').val(formatCurrency(data.nominal));
						jQuery('#row_cek').val(data.row);
						jQuery("#tabs").tabs('select', 0);
						//alert(id);
				}, "json");
				jQuery("#htmlTable5").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=DataHiburan&action=DetailHiburan&val='+id});
				jQuery("#htmlTable6").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=DataHiburan&action=DetailRincianHiburan&val='+id});
                //lastsel=id; 
            }
        },
		
        gridComplete: function(){
		//alert(document.width);
                jQuery("#htmlTable").setGridWidth( document.width - 560 < 300?300:document.width - 220);
                return true;
            }
    }).navGrid('#htmlPager'
        ,{add:false,edit:false,del:true,view:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
        
        ).hideCol(['id','TarifDasar','Persen']);  /*end of on ready event */ 
}
);

</script>
<div class='demo'>
	<div id="tabs">
		<ul>
			<li class="selected"><a href="#tabs-1"><em>Form</em></a></li>
			<li><a href="#tabs-2"><em>Entri Rekening</em></a></li>
			<li><a href="#tabs-3"><em>Detail Info</em></a></li>
			<li><a href="#tabs-4"><em>Data</em></a></li>
		</ul>
		<div id="tabs-1">
			<input type="hidden" id="IdEdit" name="IdEdit" />
			<input type='hidden' name='NamaPendataan' id='NamaPendataan' value='HIBURAN'>
			<input type="hidden" name="total_tarif_rekeningHid" id="total_tarif_rekeningHid" /> 
			<div style='padding:5px;'>
				<fieldset>
				<legend>PENETAPAN PAJAK HIBURAN</legend>
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
							<input type="hidden" id="idHid" value="1" /> 
							</label>
							<label class="leftField"><span>Tgl Proses <b class="wajib">*</b></span>
							<input id='date_1' name='TglProses' type='text' title='Tanggal Proses' /><!--<input type="button" id="reset_tgl" size="2" value=".." />-->
								
							</label>
							<label class="leftField"><span>Tgl Entri <b class="wajib">*</b></span> 
							<input type='text' id='date_2' name='TglEntri' size="25">
							<!--<input type="button" id="reset_tgl2" size="2" value=".." />-->
								
							</label>
							
							<label class="leftField"><span>NPWPD/NPWRD</span>
							<select name="kode_npwp" id="kode_npwp">
								<option value="PAJAK">P</option>
								<option value="RETRIBUSI">R</option>
							</select>
							<input type="hidden" name="npwpd_npwrd1" id='npwpd_npwrd1' title="NPWP" value=""/>
							<input type="hidden" name="pendaftaran_id" id='pendaftaran_id' />
							<input type="text" name="npwpd_npwrd2" id='npwpd_npwrd2' value="" size="7" />
							<input type="text" name="npwpd_npwrd3" id='npwpd_npwrd3' value="" size="2" />
							<input type="text" name="npwpd_npwrd4" id='npwpd_npwrd4' value="" size="2" />
							<input type="button" value="*" id="cari_npwp"/>
							</label>
							<label class="leftField"><span>Periode SPT</span>
							<input type="text" name="Periode" id='Periode' value="" size="10"/>
							<input type="hidden" name="SptIdHid" id="SptIdHid" />
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
							<select name='SystemPemungutan' id='SystemPemungutan'>
							<option value=''>---</option>
							<option value='SELF'>Self Assesment</option>
							<option value='OFFICE'>Official Assesment</option>
							</select>
							</label>
							<label class="leftField"><span>Periode Transaksi <b class="wajib">*</b></span>
								&nbsp;Dari :&nbsp;<input type="text" name="TglJualMulai" id="date_3" /><!--<input type="button" id="reset_tgl3" size="2" value=".." />-->
								
							</label>
							<label class="leftField"><span></span>
								&nbsp;S/D&nbsp;&nbsp;:&nbsp;<input type="text" name="TglJualSampai" id="date_4" /><!--<input type="button" id="reset_tgl4" size="2" value=".." />-->
								
							</label>
							<label class="leftField"><span>Memo</span>
							<textarea name="memo" id="memo" col="4" row="3"></textarea>
							</label>
							<input type="hidden" name="pendataan_id_hid" id="pendataan_id_hid" />
							<input type="hidden" name="Tarif" id="Tarif" />
							<!--<label class="leftField"><span>Kode Rekening <b class="wajib">*</b></span>
								<input type="text" name="KodeRekening1" id="KodeRekening1" size="5" />
								<input type="text" name="KodeRekening2" id="KodeRekening2" size="5" /> 
								<input type="text" name="KodeRekening3" id="KodeRekening3" size="5" />
								<input type="hidden" name="IdRekening" id="IdRekening" />
								<input type="hidden" name="pendataan_id_hid" id="pendataan_id_hid" />
								<input type="button" value="*" onclick="ShowDialogRekening()" />  
							</label>
							<label class="leftField"><span>Nama Rekening <b class="wajib">*</b></span>
								<input type="text" name="NamaRekening" id="NamaRekening" size="45" />
							</label>
							<label class="leftField"><span>Dasar Pengenaan</span>
								<input type="text" name="Tarif" id="Tarif" onBlur="cnumber(this)" />&nbsp;Persen tarif<input type="text" name="persen" id="persen" onChange="cnumber()" style="width:50px;" />%
							</label>
							<label class="leftField"><span>Pajak</span>
								<input type="text" name="Pajak" id="Pajak" style="width:180px; height:30px;" readonly="" />
							</label>-->
							<label class="leftField"><span><b class="wajib">*</b>&nbsp;Wajib Diisi</span>
							<input class="btn" type="button" name="proses" onclick='Simpan()' value="Simpan">
							
							</label>
							
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
				</fieldset>
			</div>
		</div>
		<div id="tabs-2">
			<div style='padding:5px;'>
				<fieldset>
					<legend>Detail Hiburan</legend>
					<div id='asb_simulasi_form'>
						<input type="hidden" id="pajak23" name="pajak23" />
						<input type='hidden' id='cindex_rekening' name='cindex_rekening' value='0'/>
						<table id="htmlTable5" class="scroll"></table>
							<div id="htmlPager5" class="scroll"></div>
					<!--<input type="BUTTON" id="bedata" value="Edit Selected" />-->
					<input type="hidden" id="row_cek" name="row_cek" value="0" /> 
						<!--<table id="detail_rekening" bordercolor="#999999">
						<tr bgcolor="#CC6633"><th rowspan="2"><div align="center">Kode Rekening</div></th>
						  <th><div align="center">Dasar Pengenaan </div></th>
						  <th><div align="center">Persen Tarif (%) </div></th>
						  <th><div align="center">Pajak</div></th>
						  <th rowspan="2"><div align="center">
						    <input id="hapus_detail_all_rekening" type="button" name="hapus_detail_all_rekening" value="Hapus Semua" />						  
					      </div></th></tr>
						<tr bgcolor="#CC6633">
						  <th><div align="center">(a)</div></th>
						  <th><div align="center">(b)</div></th>
						  <th><div align="center">(a*b)/100</div></th>
						  </tr>	
						</table>
						
						<input id="tambah_detail_rekening" type="button" name="tambah_detail_rekening" value="Tambah Detail" />
						<span>Total</span>
						<input type="hidden" id="total_row_rekening" name="total_row_rekening" />
						<input type="hidden" id="total_hid_rekening" name="total_hid_rekening" />
						<input class="total" id="total_tarif_rekening" type="text" name="total_tarif_rekening" value="0.00" disabled />
						<input id="id_isi_detail_rekening" type="hidden" name="id_isi_detail_rekening" value="0" />-->
					</div>
					
				</fieldset>
			</div>
		</div>
		<div id="tabs-3">
			<div style='padding:5px;'>
				<fieldset>
					<legend>Detail Info Hiburan</legend>
					<div id='asb_simulasi_form'>
						<input type='hidden' id='cindex' name='cindex' value='0'/>
						<input type="hidden" id="row_cek_x" />
						<table id="htmlTable6" class="scroll"></table>
							<div id="htmlPager6" class="scroll"></div>
						<!--<table id="detail" bordercolor="#999999" >
						<tr bgcolor="#CC6633"><th>Jumlah Meja/Mesin</th><th>Rata-rata jam/hari</th><th>Tarif</th>
						<th><input id="hapus_detail_all" type="button" name="hapus_detail_all" value="Hapus Semua" />
						</th></tr>	
						</table>
						
						<input id="tambah_detail" type="button" name="tambah_detail" value="Tambah Detail" />
						
						<input type="hidden" id="total_row" name="total_row" />
						<input type="hidden" id="total_hid" name="total_hid" />
						
						<input id="id_isi_detail" type="hidden" name="id_isi_detail" value="0" />-->
					</div>
					
				</fieldset>
			</div>
		</div>
		<div id="tabs-4">

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
    colModel :[
		{name:'id',index:'id',width:20,search:false},
		{name:'NoPendaftaran',index:'NoPendaftaran',width:100,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50}},
		{name:'NPWP',index:'NPWP',width:80,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
		{name:'Nama',index:'Nama',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
		{name:'Alamat',index:'Alamat',width:180,editable:false,edittype:'text'}
	
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
    shrinkToFit:false,
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
				ajax_do('<?=$expath?>SetData.php?RekeningRestoran='+id);
				tutup();
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
	 /*var formObject= document.getElementById("entri_pendataan_PajakHiburan");
			if(saveEntry(formObject)){
				
				YAHOO.util.Connect.setForm(formObject);
				
				var transaction = YAHOO.util.Connect.asyncRequest('POST',"request.php",callback,null);
				
			}*/
	var formObject= document.getElementById("entri_pendataan_PajakHiburan");
		if(saveEntry(formObject)){
			//YAHOO.util.Connect.setForm(formObject);
			var rows = jQuery('#htmlTable5').getGridParam('records');
			var abc  = jQuery('#htmlTable6').getGridParam('records');
			//alert(rows+' # '+abc);
			
			jQuery(formObject).ajaxSubmit({
				success: function(response){
					act = document.getElementById('caction').value;
					//alert(response);
					var vForeign = response.split("xxx");
					var FK =vForeign[0];
					tmp = {}; count=0; sv=0;
					if(response!='!'){
						var xx = rows; 
							if( xx > 0 || abc > 0){}else{alert("Data Berhasil Disimpan");formObject.reset();} 
						//if( xx > 0){
							//alert("#htmlTable5 = "+rows);
							jQuery("#htmlTable5 > tbody > tr").each(function (){
								tmp = {};
								tmp[this.id] = jQuery("#htmlTable5").getRowData(this.id);
								tmp[this.id].FK=vForeign[0];
								
								tmp[this.id].FK1=vForeign[1];
								tmp[this.id].FK2=vForeign[2];
								tmp[this.id].FK3=vForeign[3];
								tmp[this.id].FK4=vForeign[4];
								tmp[this.id].FK5=vForeign[5];
								tmp[this.id].page=<?=$_REQUEST['page']?>;
								tmp[this.id].mode='asyc';							
								if(act!='') tmp[this.id].action='edit';
								tmp[this.id].rows=xx;//rows;
								tmp[this.id].count=count;
								tmp[this.id].detail=true;
								tmp[this.id].sender='entri_pendataan_PajakHiburan';
						//		alert(YAHOO.lang.dump(tmp));
								$.post("request.php", tmp[this.id],
									function(data){
							//			alert(data);
										count++;;
										if(data=='!') {sv=0;}
										else {sv=1;}
										if(xx==count){
											if(sv){
											formObject.reset();
											}
										}
									}
								)
							});
							
							detail = jQuery('input#jumlah_meja').val();
							xxx = abc;
							if(xxx > 0){
								//alert("#htmlTable6 = "+abc);
								tmp1 = {}; count1=0; sv1=0;
								jQuery("#htmlTable6 > tbody > tr").each(function (){
									//	tmp1 = {};
									tmp1[this.id] = jQuery("#htmlTable6").getRowData(this.id);
									tmp1[this.id].FK=FK;
									tmp1[this.id].page=<?=$_REQUEST['page']?>;
									tmp1[this.id].mode='asyc';							
									if(act!='') tmp1[this.id].action='edit';
									tmp1[this.id].rows=xxx;//abc;
									tmp1[this.id].count=count1;
									tmp1[this.id].detail=true;
									tmp1[this.id].sender='entri_pendataan_PajakHiburan';
									//alert(YAHOO.lang.dump(tmp));
									$.post("request.php", tmp1[this.id],
										function(datax){
											//alert(datax);
											count1++;;
											if(datax=='!') {sv1=0;}
											else {sv1=1;}
											if(xxx==count1){
												if(sv1){
													alert('Data telah tersimpan');
													formObject.reset();
												}else alert('cek lagi 6!');
											}
										}
									);
								});
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
	var formObject = document.getElementById("entri_pendataan_PajakHiburan");
	var temp  = o.responseText;
	alert(temp);
	 formObject.reset();
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

function ReloadData(x){
//alert(x);
		//var id = document.getElementById('id_isi_detail').value; 
	for(id=1; id <= x; id++){
		//id=parseInt( id ) + 1; 
		//document.getElementById('id_isi_detail').value = id;
		alert(id);
		var str = " <tr id='" + id + "' "
				 +" class='isi_detail'> ";			
		//var data = kodeRek;
		str += "<td><input  id=\"nama_kamar"+id+"\" type='text' name=\"nama_kamar"+id+"\" value=\""+id+"\" size='30'></td><td><input class='field_angka' id=\"jumlah_kamar"+id+"\" type='text' name=\"jumlah_kamar"+id+"\" size='10' onblur=\"hitung_tarif("+id+");\" onkeyup=\"hitung_tarif("+id+");\" /></td><td><input class='field_angka' id=\"tarif_kamar"+id+"\" type='text' name=\"tarif_kamar"+id+"\" size='20' onblur=\"hitung_tarif("+id+");\" /></td>";
		
		str += "<td><input type='button' onclick=\"hapus_data('"+id+"');\" name='hapus' value='hapus' />";
		
		$("#detail").append( str );
	}
}

$('#cari_npwp').click(function(){
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