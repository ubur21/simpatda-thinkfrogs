<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_spt");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);

?>
<script>

var lastsel2;
var lastsel;
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
		shrinkToFit:false,
		sortname: 'nama',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'NPWP/RD',
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
	$('#htmlPager_right').remove();

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
		shrinkToFit:false,
		sortname: 'id',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'Kode Rekening',
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
			jQuery("#htmlTable2").setGridWidth( document.width - 500 < 100?100:document.width - 500);
            return true;
        }
    }).navGrid('#htmlPager2'
        ,{add:false,edit:false,del:false}
        ,{} // edit
        ,{height:120, width:300} // add
        ,{} // del
        ,{} // search        
    ).hideCol(['id']);  //end of on ready event

	jQuery("#htmlTable3").jqGrid({
		url:'request.php?mod=spt&func=daftar&sender=pendaftaran_bu',
		editurl:'request.php?mod=spt&func=daftar&sender=pendaftaran_bu&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','PEMUNGUTAN','NO. SPT','NO. NPWPD/RW','NAMA NPWPD/RW'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'pemungutan',index:'pemungutan',width:180,editable:false ,edittype:'text',
			 editoptions: {size:50, maxlength: 50}
			 ,editrules: {required:true}},
			 
			{name:'spt_no',index:'spt_no',width:80,editable:false,edittype:'text',
			 editoptions: {size:50, maxlength: 50},
			 editrules: {required:true} },
			 
			{name:'npw_no',index:'npw_no',width:180,editable:false,edittype:'text',
			 editoptions: {size:50, maxlength: 50},
			 editrules: {required:true} },
			 
			{name:'npw_nama',index:'npw_nama',width:180,editable:false,edittype:'text',
			 editoptions: {size:50, maxlength: 50} ,
			 editrules: {required:true} }
		],
		pager: jQuery('#htmlPager3'),
		height:150,
		rowNum:5,
		rowList:[5,10,15],
		shrinkToFit:false,
		sortname: 'id',
		sortorder: 'asc',
		multiselect:true,
		multiboxonly: true,
		viewrecords: true,
		caption: '&nbsp;',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel){ 
				jQuery.post("request.php?mod=spt&func=list&sender=get-spt&val="+id, {},
					function(data){
						
						jQuery('#proses').val('Ubah');
						jQuery('#batal').attr('disabled',false);
						jQuery('#caction').val('edit');
						jQuery('#idmasters').val(id);

						jQuery('#nomor').val(data.nomor);
						jQuery('#jenis_pungutan').val(data.jenis_pungutan);
						
						jQuery('#pendaftaran_id').val(data.pemohon);
						
						jQuery('#wp_wr_jenis').val(data.wp_wr_jenis);
						jQuery('#wp_wr_gol').val(data.wp_wr_gol);
						jQuery('#wp_wr_no_urut').val(data.wp_wr_no_urut);
						jQuery('#wp_wr_kd_camat').val(data.wp_wr_kd_camat);
						jQuery('#wp_wr_kd_lurah').val(data.wp_wr_kd_lurah);
						
						jQuery('#nama_pemohon').val(data.nama_pemohon);
						jQuery('#alamat').val(data.alamat);
						
						jQuery('#kecamatan').val(data.kecamatan);
						jQuery('#kelurahan').val(data.kelurahan);
						
						jQuery('#tgl_kirim').val(data.tgl_kirim);
						jQuery('#tgl_kembali').val(data.tgl_kembali);
						
						jQuery('#kode_rekening').val(data.kode_rekening);
						jQuery('#nama_rekening').val(data.nama_rekening);

						jQuery('#memo').val(data.memo);
						
						jQuery("#tabs").tabs('select', 0);
							
				}, "json");
				
                //lastsel=id; 
            }
        },
		gridComplete: function(){
            jQuery("#htmlTable3").setGridWidth( document.width - 500 < 100?100:document.width - 500);
            return true;
        }
    }).navGrid('#htmlPager3'
        ,{add:false,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
    ).hideCol('id');  //end of on ready event
	
	$('#trigger_npw').click(function(){
		var x = document.getElementById('wp_wr_jenis').value;
		if(x=='P'){
			$("#htmlTable").setGridParam({url:'request.php?mod=pendaftaran&func=list&sender=list-npwp', datatype:'json'}).setCaption("Details: ").trigger("reloadGrid"); 
		}else{
			$("#htmlTable").setGridParam({url:'request.php?mod=pendaftaran&func=list&sender=list-npwr', datatype:'json'}).setCaption("Details: ").trigger("reloadGrid"); 		
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
	
	$('#tgl_kirim').datepicker({changeMonth: true, changeYear: true});
	$('#tgl_kembali').datepicker({changeMonth: true, changeYear: true});
	$("#tabs").tabs();
	
	jQuery('#proses').click(function(){	
		objForm = document.getElementById('entri_spt');
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
		objForm = document.getElementById('entri_spt');
		objForm.reset();
		jQuery('#proses').val('Simpan');
		jQuery('#batal').attr('disabled',true);
	});		
});

function setNomor(){
	ajax_do('<?=$expath?>setNomor.php');
}
</script>

<?
function setNomor(){
	$no = b_fetch('select max(spt_no) from spt');
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

<div id="dialog2" title="Dialog">
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
					<div id='asb_simulasi_form'>
						<div class="singleSide">
							<fieldset class="mainForm">
							<label class="leftField"><span>Nomor SPT</span><input type="text" name="nomor" id='nomor' size='25' value="<?=setNomor()?>" readonly/></label>
							<label class="leftField"><span>Sistem Pemungutan <b class="wajib">*</b></span><select name="jenis_pungutan" title='Sistem Pemungutan' id='jenis_pungutan'></option><option value="SELF">Selft Assesment</option><option value="OFFICE">Office Assesment</option></select></label>
							<label class="leftField"><span>NPWPD / NPWRD<b class="wajib">*</b></span> 
							<select name="wp_wr_jenis" id="wp_wr_jenis" tabindex="2">
							<option value="P" selected>P</option>
							<option value="R" >R</option>
							</select>
							<!--<input type="text" name="wp_wr_jenis" id="wp_wr_jenis" class="inputbox" size="1" maxlength="1" value=""  onkeypress="submitSignup('chosen_npw');" onchange="submitSignup('chosen_npw');" autocomplete="off" />-->
							<input type="text" name="wp_wr_gol" id="wp_wr_gol" class="inputbox" size="1" maxlength="1" value="" autocomplete="off" tabindex="3"/>
							<input type="text" name="wp_wr_no_urut" id="wp_wr_no_urut" class="inputbox" size="7" maxlength="7" value="" autocomplete="off" tabindex="4"/>
							<input type="text" name="wp_wr_kd_camat" id="wp_wr_kd_camat" class="inputbox" size="2" maxlength="2" value="" autocomplete="off" tabindex="5"/>
							<input type="text" name="wp_wr_kd_lurah" id="wp_wr_kd_lurah" class="inputbox" size="3" maxlength="3" value="" autocomplete="off" tabindex="6"/>
							<input type="hidden" name="pendaftaran_id" id='pendaftaran_id' size="25" readonly />
							<input type="button" id="trigger_npw" size="2" value="...">
							</label>							
							<label class="leftField"><span>Nama WP/WR </span><input type="text" name="nama_pemohon" id='nama_pemohon' value="" size="25" readonly /></label>					
							<label class="leftField"><span>Alamat</span><textarea name="alamat" id='alamat' col="4" row="3" width='100' readonly></textarea></label>
							<label class="leftField"><span>Kecamatan</span><input type="text" name="kecamatan" id='kecamatan' value="" size="25" readonly /></label>
							<label class="leftField"><span>Kelurahan</span><input type="text" name="kelurahan" id='kelurahan' value="" size="25" readonly /></label>
							<label class="leftField"><span>Kabupaten</span><input type="text" name="kabupaten" id='kabupaten' value="<?=b_fetch('select pemda_kabupaten from info_pemda')?>" size="25" readonly /></label>
							</fieldset>
							</div>
							<div>
							<fieldset class="mainForm">
							<label class="leftField"><span>Tanggal SPT <b class="wajib">*</b></span><input type="text" name="tgl_kirim" title='Tgl. SPT' id="tgl_kirim" onchange="" size="10"/><label>
							<label class="leftField"><span>Tanggal Kembali <b class="wajib">*</b></span><input type="text" name="tgl_kembali" title='Tgl. Kembali' id="tgl_kembali" onchange="" size="10"/><label>
							<label class="leftField"><span>Kode Rekening <b class="wajib">*</b></span>
							<input type="text" name="kode_rekening" title='Kode Rekening' id="kode_rekening" onchange="" size="10" readonly />
							<input type="hidden" name="rekening_id" id="rekening_id" size="10"/>
							<input type="button" id="trigger_rekening" size="2" value="..."><label>
							<label class="leftField"><span>Nama Rekening </span><input type="text" name="nama_rekening" title='Nama Rekening' id="nama_rekening" onchange="" size="30" readonly /><label>
							<label class="leftField"><span>Memo</span><textarea name="memo" title='Memo' id='memo' col="10" row=""></textarea></label>
							<label class="leftField"><span><b class="wajib">*</b>&nbsp;Wajib Diisi</span>
							<input class="btn" id='proses' type="button" name="proses" id='proses' value="Simpan">
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
							<table id="htmlTable3" class="scroll"></table>
							<div id="htmlPager3" class="scroll"></div>
						</div>
					</div>		
				</fieldset>
			</div>
		</div>
	</div>
</div>