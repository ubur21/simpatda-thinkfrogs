<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_pendaftaran_pribadi");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);


?>
<script>

/*jQuery('.ui-dialog .ui-widget .ui-widget-content .ui-corner-all .ui-draggable').css({'zIndex':'100'});
jQuery('.ui-dialog').css({'zIndex':'100'});
jQuery('#edithdhtmlTable2').css({'zIndex':'5000'});
jQuery('#editmodhtmlTable2').css({'zIndex':'5000'});
jQuery("#htmlTable2").setGridWidth(400);*/

$().ready(function() {

	$('#tanggal_kartu').datepicker({changeMonth: true, changeYear: true});
	$('#tanggal_terima').datepicker({changeMonth: true, changeYear: true});
	$('#tanggal_kirim').datepicker({changeMonth: true, changeYear: true});
	$('#tanggal_kembali').datepicker({changeMonth: true, changeYear: true});
	$('#tanggal_tutup').datepicker({changeMonth: true, changeYear: true});
	
	var $tabs = $("#tabs").tabs();
	
});

function getListPemohon(){
	gcms_open_form('form.php?mod=pendaftaran&func=pemohon','xx',1200,500);
}

function setInfoPemohon(val){
	ajax_do('<?=$expath?>setInfoPemohon.php?val='+val);
}

function setNomor(){
	var action = document.getElementById('caction').value;
	var old_val = document.getElementById('nomor').value;
	var typ = document.getElementById('jenis_pendaftaran').value;
	var kd  = document.getElementById('kode_usaha').value;
	var obj  = document.getElementById('objek_pdrd').value;
	ajax_do('<?=$expath?>setNomor.php?old_val='+old_val+'&type='+typ+'&kod='+kd+'&obj='+obj+'&action='+action);
}

var lastsel,lastsel2;
jQuery(document).ready(function(){ 
	jQuery("#htmlTable").jqGrid({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=pendaftaran_pribadi',
		editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=pendaftaran_pribadi&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','NO. PENDAFTARAN','TANGGAL','NAMA PEMOHON','JENIS PENDAFTARAN','NPWP/R'],
		colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false },
			{ name:'nomor' ,index:'no_pendaftaran' ,width:180 ,editable:false ,edittype:'text',
			  editoptions: {size:50, maxlength: 50}	,editrules: {required:true}
			},
			{ name:'tanggal', index:'tanggal_kartu',align:'center',width:80 ,editable:false ,edittype:'text',formatter:'date', sorttype:"date",
			  editoptions: {size:50, maxlength: 50} ,editrules: {required:true}
			},
			{ name:'nama',index:'nama',width:180,editable:false,edittype:'text' ,
			  editoptions: {size:50, maxlength: 50},editrules: {required:true}
			},
			{ name:'jenis',index:'jenis_pendaftaran',width:180,editable:false,edittype:'text',
			  editoptions: {size:50, maxlength: 50},editrules: {required:true}
			},
			{ name:'npwp',index:'npwp',width:180,editable:false,edittype:'text' ,
			  editoptions: {size:50, maxlength: 50},editrules: {required:true}
			}
		],
		pager: jQuery('#htmlPager'),
		height:150,
		rowNum:5,
		rowList:[5,10,15],
		shrinkToFit:false,
		sortname: 'no_pendaftaran',
		sortorder: 'asc',
		multiselect:true,
		multiboxonly: true,
		viewrecords: true,
		caption: 'DATA PENDAFTARAN GOL. 1 (PRIBADI) ',
		ondblClickRow: function(id){ 
            if(id && id!==lastsel){ 
				jQuery.post("request.php?mod=pendaftaran&func=list&sender=get-pendaftaran-pribadi&val="+id, {},
					function(data){
						jQuery('#proses').val('Ubah');
						jQuery('#batal').attr('disabled',false);
						jQuery('#caction').val('edit');
						jQuery('#idmasters').val(id);
						jQuery('#nomor').val(data.nomor);
						jQuery('#jenis_pendaftaran').val(data.jenis);
						jQuery('#pemohon').val(data.pemohon);
						jQuery('#no_ktp').val(data.no_ktp);
						jQuery('#nama_pemohon').val(data.nama_pemohon);
						jQuery('#alamat').val(data.alamat);
						jQuery('#kecamatan').val(data.kecamatan);
						jQuery('#kelurahan').val(data.kelurahan);
						jQuery('#telp').val(data.telp);
						jQuery('#kode_usaha').val(data.kode_usaha);
						jQuery('#tanggal_kartu').val(data.tanggal_kartu);
						jQuery('#tanggal_terima').val(data.tanggal_terima);
						jQuery('#tanggal_kirim').val(data.tanggal_kirim);
						jQuery('#tanggal_kembali').val(data.tanggal_kembali);
						jQuery('#tanggal_tutup').val(data.tanggal_tutup);
						jQuery('#memo').val(data.memo);
						jQuery("#tabs").tabs('select', 0);
				}, "json");			
			
                jQuery("#htmlTable").restoreRow(lastsel); 
                jQuery("#htmlTable").editRow(id,true); 
                lastsel=id; 			
			}
		},
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth( document.width - 160 < 300?300:document.width - 160);
                return true;
		}
    }).navGrid('#htmlPager'
        ,{add:false,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
    ).hideCol('id');  /*end of on ready event */ 

	jQuery("#htmlTable2").jqGrid({
		url:'request.php?mod=pendaftaran&func=list&sender=list-pribadi',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','NO. KPT','NAMA PEMOHON'],
		colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{ name:'nomor' ,index:'no_ktp' ,width:180,editable:true,edittype:'text',editoptions: {size:50,maxlength:50},editrules:{required:true} },
			{ name:'nama' ,index:'nama_pemohon',width:180,editable:true,edittype:'text',editoptions: {size:50,maxlength:50},editrules:{required:true} }
		],
		pager: jQuery('#htmlPager2'),
		height:150,
		rowNum:5,
		rowList:[5,10,15],
		rownumbers:true,
		shrinkToFit:false,
		sortname: 'id',
		sortorder: 'asc',
		viewrecords: true,
		caption: '&nbsp;',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel2){ 
				jQuery.post("request.php?mod=pendaftaran&func=list&sender=get-pemohon&val="+id, {},
					function(data){
						jQuery('#pemohon').val(id);
						jQuery('#no_ktp').val(data.no_ktp);
						jQuery('#nama_pemohon').val(data.nama);
						jQuery('#alamat').val(data.alamat);
						jQuery('#kecamatan').val(data.kecamatan);
						jQuery('#kelurahan').val(data.kelurahan);
						jQuery('#telp').val(data.telp);
						jQuery('#dialog').dialog('close');
						//console.log(data.time);
				}, "json");
                //jQuery("#htmlTable2").restoreRow(lastsel2); 
                //jQuery("#htmlTable2").editRow(id,true); 
                lastsel2=id; 
            }
        },
        gridComplete: function(){
			jQuery('.ui-dialog .ui-widget .ui-widget-content .ui-corner-all .ui-draggable').css({'zIndex':'100'});
			jQuery('.ui-dialog').css({'zIndex':'100'});
			jQuery('#edithdhtmlTable2').css({'zIndex':'5000'});
			jQuery('#editmodhtmlTable2').css({'zIndex':'5000'});
			jQuery("#htmlTable2").setGridWidth(400);
			return true;
		}
    }).navGrid('#htmlPager2'
        ,{add:true,edit:false,del:false}
        ,{} // edit
        ,{height:120, width:300} // add
        ,{} // del
        ,{} // search
        ).hideCol('id');  /*end of on ready event */
	$('#htmlPager2_right').remove();	
		
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
		
	jQuery('#trigger_pemohon').click(function(){
		$('#dialog').dialog('open');
	});

	jQuery('#proses').click(function(){	
		objForm = document.getElementById('entri_pendaftaran_pribadi');
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
					jQuery('#nomor').val('000000');
					
				}	
			});
		}		
	});
	
	jQuery('#batal').click(function(){	
		objForm = document.getElementById('entri_pendaftaran_pribadi');
		objForm.reset();
		jQuery('#proses').val('Simpan');
		jQuery('#batal').attr('disabled',true);
	});
	
	/*jQuery('.ui-dialog .ui-widget .ui-widget-content .ui-corner-all .ui-draggable').css({'zIndex':'100'});
	jQuery('.ui-dialog').css({'zIndex':'100'});
	jQuery('#edithdhtmlTable2').css({'zIndex':'5000'});
	jQuery('#editmodhtmlTable2').css({'zIndex':'5000'});
	jQuery("#htmlTable2").setGridWidth(400);*/

});

	/*jQuery('.ui-dialog .ui-widget .ui-widget-content .ui-corner-all .ui-draggable').css({'zIndex':'100'});
	jQuery('.ui-dialog').css({'zIndex':'100'});
	jQuery('#edithdhtmlTable2').css({'zIndex':'5000'});
	jQuery('#editmodhtmlTable2').css({'zIndex':'5000'});
	jQuery("#htmlTable2").setGridWidth(400);*/
	
</script>

<div id="dialog" title="Pilih WP/WR Pribadi">
	<div style='padding:5px;'>
		<div>
			<div style='padding:5px'>
				<table id="htmlTable2" class="scroll"></table>
				<div id="htmlPager2" class="scroll"></div>
			</div>
		</div>		
	</div>
</div>

<div class='demo'>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Input</a></li>
			<li><a href="#tabs-2">Data</a></li>
		</ul>
		<div id="tabs-1">
			<input type='hidden' name='objek_pdrd' id='objek_pdrd' value='PRIBADI'>
			<div style='padding:5px;'>
				<fieldset>
				<legend>PENDAFTARAN WAJIB PAJAK/RETRIBUSI</legend>
					<div id='asb_simulasi_form'>
						<div class="singleSide">
							<fieldset class="mainForm">
							<label class="leftField"><span>No. Pendaftaran</span><input type="text" name="nomor" id='nomor' size='25' value="000000" readonly/></label>
							<label class="leftField"><span>Jenis <b class="wajib">*</b></span><select name="jenis_pendaftaran" title='Jenis Pendaftaran' id='jenis_pendaftaran' onchange='setNomor()'><option value=''></option><option value="PAJAK">PAJAK</option><option value="RETRIBUSI">RETRIBUSI</option></select></label>
							<label class="leftField"><span>No. KTP<b class="wajib">*</b></span> 
							<input type='text' id='no_ktp' name='no_ktp' size="25" readonly >
							<input type="button" id="trigger_pemohon" size="2" value=" * ">
							<input id='pemohon' name='pemohon' type='hidden' /></label>
							<label class="leftField"><span>Nama WP/WR </span><input type="text" name="nama_pemohon" id='nama_pemohon' value="" size="25" readonly /></label>
							<label class="leftField"><span>Alamat</span><textarea name="alamat" id='alamat' col="4" row="3" width='100' readonly></textarea></label>
							<label class="leftField"><span>Kecamatan</span><input type="text" name="kecamatan" id='kecamatan' value="" size="25" readonly /></label>
							<label class="leftField"><span>Kelurahan</span><input type="text" name="kelurahan" id='kelurahan' value="" size="25" readonly /></label>
							<label class="leftField"><span>Kabupaten</span><input type="text" name="kabupaten" id='kabupaten' value="<?=b_fetch('select pemda_kabupaten from info_pemda')?>" size="25" readonly /></label>
							<label class="leftField"><span>Telp</span><input type="text" name="telp" id='telp' value="" size="20" readonly /></label>
							</fieldset>
							</div>
							<div>
							<fieldset class="mainForm">
							<label class="leftField"><span>Kode Usaha/Objek <b class="wajib">*</b></span>
							<select name='kode_usaha' id='kode_usaha' title='Kode USaha/Objek' onchange='setNomor()'>
							<option value=''></option>
							<?=getLisKodeUsaha()?>
							</select>
							</label>
							<label class="leftField"><span>Tgl Kartu NPWP/RD <b class="wajib">*</b></span>
								<input type="text" name="tanggal_kartu" title='Tgl Kartu NPWP/RD' id="tanggal_kartu" onchange="" size="10"/>
							</label>
							<label class="leftField"><span>Tgl Form Diterima WP <b class="wajib">*</b></span>
								<input type="text" name="tanggal_terima" title='Tgl Form Diterima WP' id="tanggal_terima" onchange="" size="10" value="<?= '' ?>" <?php echo $dis; ?>/>
							</label>
							<label class="leftField"><span>Tgl Batas Kirim <b class="wajib">*</b></span>
								<input type="text" name="tanggal_kirim" title='Tgl Batas Kirim' id="tanggal_kirim" onchange="" size="10" value="<?= '' ?>" <?php echo $dis; ?>/>
							</label>
							<label class="leftField"><span>Tgl Form Kembali</span>
								<input type="text" name="tanggal_kembali" id="tanggal_kembali" onchange="" size="10" value="<?= '' ?>" <?php echo $dis; ?>/>
							</label>
							<label class="leftField"><span>Tgl Tutup</span>
								<input type="text" name="tanggal_tutup" id="tanggal_tutup" onchange="" size="10" value="<?= '' ?>" <?php echo $dis; ?>/>
							</label>
							<label class="leftField"><span>Memo</span><textarea name="memo" id='memo' col="4" row="3" width='100'></textarea></label>
							<label class="leftField"><span><b class="wajib">*</b>&nbsp;Wajib Diisi</span>
							<input class="btn" id='proses' type="button" name="proses" value="Simpan">
							<input class="btn" id='batal' type="button" name="batal" value="Batal" disabled >
							</label>
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
							<table id="htmlTable" class="scroll"></table>
							<div id="htmlPager" class="scroll"></div>
						</div>
					</div>		
				</fieldset>
			</div>
		</div>
	</div>
</div>
<script>
	/*(function(){
		var tabView = new YAHOO.widget.TabView('demo');   
	})();*/
</script>
<? yuiEndEntry() ?>