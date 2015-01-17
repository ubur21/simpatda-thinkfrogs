<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_pendataan_listrik");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);


?>
<script>

var lastsel,lastsel2,lastsel3;

$().ready(function(){
	
	jQuery("#htmlTable").jqGrid(
	{
		 url:'request.php?mod=pendaftaran&func=list&sender=list-npwp',	
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Nama','NPWPD/RD'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{name:'nama',index:'nama',width:250},
			{ name:'npwp' ,index:'npwp',width:150},
		 ],
		pager: jQuery('#htmlPager'),
		height:150,
		width:600,
		rowNum:5,
		rowList:[10,15,20],
		rownumbers: true, 
		shrinkToFit:false,
		sortname: 'nama',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'Daftar NPWPD/RW ',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel2){ 
				jQuery("#htmlTable").restoreRow(lastsel2); 
                jQuery("#htmlTable").editRow(id,true); 
                lastsel2=id; 
				ajax_do('<?=$expath?>setForm.php?type=npwp&val='+id);
				$('#dialog').dialog('close');
            }
        },
        gridComplete: function(){
			jQuery("#htmlTable").setGridWidth(450);
            return true;
        }
    }).navGrid('#htmlPager'
        ,{add:false,edit:false,del:false}
        ,{} // edit
        ,{height:120, width:300} // add
        ,{} // del
        ,{} // search        
    ).hideCol(['id']);  /*end of on ready event */

	jQuery("#htmlTable2").jqGrid(
	{
		 url:'request.php?mod=rekening&func=pilih&sender=pilih-rekening',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Kode Rekening','Nama Rekening'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{name:'kode',index:'kode_rekening',width:150},
			{ name:'nama' ,index:'nama_rekening',width:250},
		 ],
		pager: jQuery('#htmlPager2'),
		height:150,
		width:600,
		rowNum:5,
		rowList:[10,15,20],
		rownumbers: true, 
		shrinkToFit:false,
		sortname: 'id',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'Data NPWP',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel2){ 
				jQuery("#htmlTable2").restoreRow(lastsel2); 
                jQuery("#htmlTable2").editRow(id,true); 
                lastsel2=id; 
				ajax_do('<?=$expath?>setForm.php?type=rekening&val='+id);
				$('#dialog2').dialog('close');
            }
        },
        gridComplete: function(){
			jQuery("#htmlTable2").setGridWidth(450);
            return true;
        }
    }).navGrid('#htmlPager2'
        ,{add:false,edit:false,del:false}
        ,{} // edit
        ,{height:120, width:300} // add
        ,{} // del
        ,{} // search        
    ).hideCol(['id']);  /*end of on ready event */ 
	//,'NO. SPT'
	/*			{name:'spt_no',index:'spt_no',width:180,editable:false,edittype:'text',
			 editoptions: {size:50, maxlength: 50} ,
			 editrules: {required:true} },
			 */
	jQuery("#htmlTable3").jqGrid({
		url:'request.php?mod=pendataan_listrik&func=list&sender=pendataan_spt',
		editurl:'request.php?mod=pendataan_listrik&func=list&sender=pendataan_spt&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','NO. PENDATAAN','TANGGAL ENTRI','NPWPD/RD','NAMA','JENIS PUNGUTAN','KODE REKENING','NOMINAL PAJAK','MEMO'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'pendataan_no',index:'pendataan_no',width:150,editable:false},
			 
			{name:'tgl_entry',align:'center',index:'tgl_entry',align:'center',width:100,formatter:'date', sorttype:"date"},
			 
			{name:'npwp',index:'npwp',width:150,editable:false,edittype:'text',
			 editoptions: {size:50, maxlength: 50},
			 editrules: {required:true} },

			{name:'nama',index:'nama',width:200,editable:false,edittype:'text',
			 editoptions: {size:50, maxlength: 50},
			 editrules: {required:true} },

			{name:'jenis_pungutan',index:'jenis_pungutan',width:120,editable:false,edittype:'text',
			 editoptions: {size:50, maxlength: 50} ,
			 editrules: {required:true} },

			{name:'kode_rekening',index:'kode_rekening',width:200,editable:false },			 
			 
			{name:'nominal_pajak',align:'right',index:'nominal_pajak',width:120,formatter:'currency',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, prefix: "Rp. "} },
			 
			{name:'memo',index:'memo',width:150,editable:false,edittype:'text',
			 editoptions: {size:50, maxlength: 50},
			 editrules: {required:true} }
			
		],
		pager: jQuery('#htmlPager3'),
		height:150,
		rowNum:5,
		rowList:[5,10,15],
		rownumbers: true, 
		shrinkToFit:false,
		sortname: 'pendataan_no',
		sortorder: 'asc',
		viewrecords: true,
		caption: '&nbsp;',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel3){
				jQuery.post("request.php?mod=pendataan_listrik&func=list&sender=get-listrik&val="+id, {},
					function(data){
						
						jQuery('#proses').val('Ubah');
						jQuery('#batal').attr('disabled',false);
						jQuery('#caction').val('edit');
						jQuery('#idmasters').val(id);

						jQuery('#nomor').val(data.nomor);
						jQuery('#jenis_pungutan').val(data.jenis_pungutan);
						jQuery('#pendaftaran_id').val(data.pendaftaran);
						jQuery('#nomor_spt').val(data.spt_no);
						jQuery('#spt').val(data.spt);
						jQuery('#memo').val(data.memo);
						
						jQuery('#tgl_proses').val(data.tgl_proses);
						jQuery('#tgl_entry').val(data.tgl_entry);
						jQuery('#periode_awal').val(data.periode_awal);
						jQuery('#periode_akhir').val(data.periode_akhir);
						
						jQuery('#wp_wr_jenis').val(data.wp_wr_jenis);
						jQuery('#wp_wr_gol').val(data.wp_wr_gol);
						jQuery('#wp_wr_no_urut').val(data.wp_wr_no_urut);
						jQuery('#wp_wr_kd_camat').val(data.wp_wr_kd_camat);
						jQuery('#wp_wr_kd_lurah').val(data.wp_wr_kd_lurah);
						
						jQuery('#nama_pemohon').val(data.nama);
						jQuery('#alamat').val(data.alamat);
						jQuery('#kecamatan').val(data.kecamatan);
						jQuery('#kelurahan').val(data.kelurahan);
						
						jQuery('#rekening').val(data.rekening);
						jQuery('#kode_rekening').val(data.kode_rekening);
						jQuery('#nama_rekening').val(data.nama_rekening);
						
						jQuery('#kva').val(data.kva);
						jQuery('#jam').val(data.jam);
						jQuery('#tarif_dasar').val(data.tarif_dasar);
						jQuery('#diskon').val(data.diskon);
						jQuery('#dasar_pengenaan').val(data.dasar_pengenaan);
						jQuery('#persen_tarif').val(data.persen_tarif);
						jQuery('#nominal').val(data.nominal);
						
						jQuery("#tabs").tabs('select', 0);
						
				}, "json");
				
                //lastsel=id; 
            }
        },
		gridComplete: function(){
			// 
            jQuery("#htmlTable3").setGridWidth(document.width - 500 < 100 ? 100 : document.width - 250);
            return true;
        }
    }).navGrid('#htmlPager3'
        ,{add:false,edit:false,del:true,deltitle: "Delete selected row"}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{multipleSearch:true} // search
    ).hideCol('id');  //end of on ready event
	
	$('#trigger_npw').click(function(){
		var x = document.getElementById('wp_wr_jenis').value;
		if(x=='P'){
			$("#htmlTable").setGridParam({url:'request.php?mod=pendaftaran&func=list&sender=list-npwp', datatype:'json'}).setCaption("Daftar NPWPD/RW ").trigger("reloadGrid"); 
		}else{
			$("#htmlTable").setGridParam({url:'request.php?mod=pendaftaran&func=list&sender=list-npwr', datatype:'json'}).setCaption("Daftar NPWPD/RW ").trigger("reloadGrid"); 		
		}
	
		$('#dialog').dialog('open');
	});
	
	$('#trigger_rekening').click(function(){
		$('#dialog2').dialog('open');
	});
	
	$("#dialog").dialog({
		bgiframe: true,
		resizable: false,
		height:350,
		width:500,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
	});

	$("#dialog2").dialog({
		bgiframe: true,
		resizable: false,
		height:350,
		width:500,
		modal: true,
		autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
	});	

	jQuery('#proses').click(function(){	
		objForm = document.getElementById('entri_pendataan_listrik');
		if(saveEntry(objForm)){
			jQuery(objForm).ajaxSubmit({
				success: function(response){
					act = document.getElementById('caction').value;
					alert(response);
					if(act!=''){
						jQuery('#proses').val('Simpan');
						jQuery('#batal').attr('disabled',true);
					}
					if(response.indexOf('Terima Kasih') > -1){
						jQuery(objForm).clearForm();
					}else{										
						jQuery(objForm).clearForm();
						//objForm.reset();
					}
					setNomor();
				}	
			});
		}
	});
	
	jQuery('#batal').click(function(){	
		objForm = document.getElementById('entri_pendataan_listrik');
		objForm.reset();
		jQuery('#proses').val('Simpan');
		jQuery('#batal').attr('disabled',true);
	});
	
	$("#kva").keyup(function(){ $(this).value=cnumber(this); });
	$("#kva").blur(function(){ $(this).value=blur_uang(this);hitungPajak(); });
	$("#jam").blur(function(){ $(this).value=blur_uang(this); });
	$("#jam").keyup(function(){ $(this).value=cnumber(this);hitungPajak(); });
	$("#tarif_dasar").blur(function(){ $(this).value=blur_uang(this); });
	$("#tarif_dasar").keyup(function(){ $(this).value=cnumber(this);hitungPajak(); });
	$("#diskon").blur(function(){ $(this).value=blur_uang(this); });
	$("#diskon").keyup(function(){ $(this).value=cnumber(this);hitungPajak(); });
	$("#nominal").blur(function(){ $(this).value=blur_uang(this); });
	$("#nominal").keyup(function(){ $(this).value=cnumber(this); });
	$("#persen_tarif").blur(function(){ $(this).value=blur_uang(this); });
	$("#persen_tarif").keyup(function(){ $(this).value=cnumber(this);hitungPajak(); });	

	$('#tgl_proses').datepicker({changeMonth: true, changeYear: true});
	$('#periode_awal').datepicker({changeMonth: true, changeYear: true});
	$('#tgl_entry').datepicker({changeMonth: true, changeYear: true});
	$('#periode_akhir').datepicker({changeMonth: true, changeYear: true});	
	$("#tabs").tabs();
});

function hitungPajak(){
	ikva = document.getElementById('kva').value!='' ? removeCommas(document.getElementById('kva').value) : 1;
	ijam = document.getElementById('jam').value!='' ? removeCommas(document.getElementById('jam').value) : 1;
	idasar = document.getElementById('tarif_dasar').value!='' ? removeCommas(document.getElementById('tarif_dasar').value) : 1;
	idiskon = document.getElementById('diskon').value!='' ? removeCommas(document.getElementById('diskon').value) : 0;
	itarif = document.getElementById('persen_tarif').value!='' ? removeCommas(document.getElementById('persen_tarif').value) : 0;
	nominal = (ikva*ijam*idasar)-idiskon;
	pajak = nominal*itarif/100;
	document.getElementById('dasar_pengenaan').value=formatCurrency(nominal);
	document.getElementById('nominal').value=formatCurrency(pajak);
}

function setNomor(){
	ajax_do('<?=$expath?>setNomor.php');
}
</script>
<!--   
 onkeypress="submitSignup('chosen_npw');" onchange="submitSignup('chosen_npw');"  
 onclick="openChildGB('Daftar Wajib Pajak/Retribusi','index.php?action=v.dsp_ref_npw_GB','win2')"
-->
<?
function setNomor(){
	$no = b_fetch('select max(pendataan_no) from pendataan_spt');
	$no++;
	return sprintf('%05d',$no);
}
?>
<div class='demo'>

<div id="dialog" title="Dialog">
	<div style='padding:5px;'>
		<div>
			<div style='padding:5px'>
				<table id="htmlTable" class="scroll"></table>
				<div id="htmlPager" class="scroll"></div>
			</div>
		</div>		
	</div>
</div>

<div id="dialog2" title="Basic dialog">
	<div style='padding:5px;'>
		<div>
			<div style='padding:5px'>
				<table id="htmlTable2" class="scroll"></table>
				<div id="htmlPager2" class="scroll"></div>
			</div>
		</div>		
	</div>
</div>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Input</a></li>
			<li><a href="#tabs-2">Data</a></li>
		</ul>
		<div id="tabs-1">
			<input type='hidden' name='objek_pdrd' id='objek_pdrd' value='PRIBADI'>
			<div style='padding:5px;'>
				<fieldset>
					<legend>SPTPD/SPTRD YANG DIKIRIM</legend>
					<div>
						<div class="form_master">
							<fieldset class="form_frame">
								<div><label>No. Reg Form</label><input type="text" name="nomor" id='nomor' size='25' value="<?=setNomor()?>" readonly/></div>
								<div><label>Tanggal Proses <b class="wajib">*</b></label><input type="text" name="tgl_proses" id="tgl_proses" title='Tgl. Proses' onchange="" size="10"/></div>
								<div><label>Tanggal Entry <b class="wajib">*</b></label><input type="text" name="tgl_entry" title='Tgl. Entry' id="tgl_entry" onchange="" size="10"/></div>
								<div><label>NPWPD / NPWRD<b class="wajib">*</b></label> 
								<select name="wp_wr_jenis" id="wp_wr_jenis" tabindex="2">
								<option value="P" selected>P</option>
								<option value="R" >R</option>
								</select>
								<!--<input type="text" name="wp_wr_jenis" id="wp_wr_jenis" class="inputbox" size="1" maxlength="1" value=""  onkeypress="submitSignup('chosen_npw');" onchange="submitSignup('chosen_npw');" autocomplete="off" />-->
								<input type="text" name="wp_wr_gol" id="wp_wr_gol" class="inputbox" size="1" maxlength="1" value="" autocomplete="off" tabindex="3"/>
								<input type="text" name="wp_wr_no_urut" id="wp_wr_no_urut" class="inputbox" size="7" maxlength="7" value="" autocomplete="off" tabindex="4"/>
								<input type="text" name="wp_wr_kd_camat" id="wp_wr_kd_camat" class="inputbox" size="2" maxlength="2" value="" autocomplete="off" tabindex="5"/>
								<input type="text" name="wp_wr_kd_lurah" id="wp_wr_kd_lurah" class="inputbox" size="3" maxlength="3" value="" autocomplete="off" tabindex="6"/>
								<input type="button" id="trigger_npw" size="2" value=" * ">
								</div>
								<div><label>Memo</label><textarea name='memo' id='memo' col="6" row="3"></textarea></div>
								<div><label>Nomor SPT </label>
								<input type="text" name="nomor_spt" id='nomor_spt' value="" size="25" readonly />
								<input type="hidden" name="spt" id='spt' value="" size="25" readonly />
								<input type="hidden" name="pendaftaran_id" id='pendaftaran_id' size="25" readonly />
								</div>													
								<div><label>Nama WP/WR </label><input type="text" name="nama_pemohon" id='nama_pemohon' value="" size="25" readonly /></div>
								<div><label>Alamat</label><textarea name="alamat" id='alamat' col="4" row="3" readonly></textarea></div>
								<div><label>Kecamatan</label><input type="text" name="kecamatan" id='kecamatan' value="" size="25" readonly /></div>
								<div><label>Kelurahan</label><input type="text" name="kelurahan" id='kelurahan' value="" size="25" readonly /></div>
								<div><label>Kabupaten</label><input type="text" name="kabupaten" id='kabupaten' value="<?=b_fetch('select pemda_kabupaten from info_pemda')?>" size="25" readonly /></div>
							</fieldset>
							<fieldset class="form_frame">
								<div><label>Sistem Pemungutan <b class="wajib">*</b></label><select name="jenis_pungutan" title='Sistem Pemungutan' id='jenis_pungutan'></option><option value="SELF">Selft Assesment</option><option value="OFFICE">Office Assesment</option></select></div>
								<div><label>Periode Pemakaian <b class="wajib">*</b></label><input type="text" name='periode_awal' title='Periode Awal' id='periode_awal' size="10"/> S/D <input type="text" name='periode_akhir' title='Periode Akhir' id='periode_akhir' size="10"/></div>
								<div><label>Kode Rekening <b class="wajib">*</b></label>
								<input type="text" name="kode_rekening" title='Kode Rekening' id="kode_rekening" onchange="" size="10" readonly />
								<input type="hidden" name="rekening" id="rekening" size="10" />
								<input type="button" id="trigger_rekening" size="2" value=" * "></div>
								<div><label>Nama Rekening </label><input type="text" name="nama_rekening" title='Nama Rekening' id="nama_rekening" onchange="" size="35" readonly /></div>
								<div><label>Pemakaian (Kva)  <b class="wajib">*</b></label><input type="text" name="kva" title='Pemakaian (Kva)' id="kva" style='text-align:right' onchange=""  size="10"/></div>							
								<div><label>Jam <b class="wajib">*</b></label><input type="text" name="jam" title='Jam' id="jam" style='text-align:right' onchange="" size="10"/></div>
								<div><label>Tarif Dasar <b class="wajib">*</b></label><input type="text" name="tarif_dasar" title='Tarif Dasar' id="tarif_dasar" style='text-align:right' onchange="" size="10"/></div>							
								<div><label>Faktor Diskon <b class="wajib">*</b></label><input type="text" name="diskon" title='Faktor Diskon' id="diskon"  style='text-align:right' onchange="" size="10"/></div>	
								<div><label>Dasar Pengenaan </label><input type="text" name="dasar_pengenaan" title='Dasar Pengenaan' id="dasar_pengenaan" style='text-align:right' onchange="" size="10" readonly /></div>
								<div><label>Persen Tarif <b class="wajib">*</b></label><input type="text" name="persen_tarif" title='Persen Tarif' id="persen_tarif" style='text-align:right' onchange="" size="10"/>%</div>
								<div><label>Pajak</label>
								<input type="text" style="font-weight: bold; font-size: 25px; color: rgb(24, 245, 24); background-color: black; text-align: right;" readonly="true" value="" size="13" class="inputbox" id="nominal" name="nominal">
								</div>
								<div><label><b class="wajib">*</b>&nbsp;Wajib Diisi</label>
								<input class="btn" type="button" name="proses" id='proses' value="Simpan">
								<input class="btn" id='batal' type="button" name="batal" value="Batal" disabled >
								</div>
							</fieldset>							
						</div>
						<div id="confirmDialog" class="hidden">
							<fieldset class="mainForm">
							<label class="leftField"><span>Nama User</span><input type="text" name="namaUser" value="" size="10" maxlength="20" /></label>
							<label class="leftField"><span>Password</span><input type="password" name="password" value="" size="10" maxlength="20" />
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
				<legend>Daftar</legend>
					<div id='asb_simulasi_form'>
						<div style='padding:5px'>
							<table id="htmlTable3" class="scroll"></table>
							<div id="htmlPager3" class="scroll"></div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>
