<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_pendaftaran_bu");

$include= '<script type="text/javascript" src="lib.js"></script>'."\n";

gcms_add_to_head($include);


$page = b_fetch("select nid from g_frontmenus where cfunction='m_pendaftaran_badan' ");

?>

<script>

$().ready(function() {

	$('#tanggal_kartu').datepicker({changeMonth: true, changeYear: true});
	$('#tanggal_terima').datepicker({changeMonth: true, changeYear: true});
	$('#tanggal_kirim').datepicker({changeMonth: true, changeYear: true});
	$('#tanggal_kembali').datepicker({changeMonth: true, changeYear: true});
	$('#tanggal_tutup').datepicker({changeMonth: true, changeYear: true});	
	var $tabs = $("#tabs").tabs();
});

function setNomor(){
	var action = document.getElementById('caction').value;
	var old_val = document.getElementById('nomor').value;
	var typ = document.getElementById('jenis_pendaftaran').value;
	var kd  = document.getElementById('kode_usaha').value;
	var obj  = document.getElementById('objek_pdrd').value;
	ajax_do('<?=$expath?>setNomor.php?old_val='+old_val+'&type='+typ+'&kod='+kd+'&obj='+obj+'&action='+action);
}

function getListBadanUsaha(){
	gcms_open_form('form.php?page=<?=$page?>','xx',1200,500);
}

function setInfoBadanUsaha(val){
	ajax_do('<?=$expath?>setInfoBadanUsaha.php?val='+val);
}

var lastsel,lastsel2;

$.fn.extend({
	/*
	*  
	* The toolbar has the following properties
	*	id of top toolbar: t_<tablename>
	*	id of bottom toolbar: tb_<tablename>
	*	class of toolbar: ui-userdata
	* elem is the toolbar name to which button needs to be added. This can be 
	*		#t_tablename - if button needs to be added to the top toolbar
	*		#tb_tablename - if button needs to be added to the bottom toolbar
	*/
	toolbarButtonAdd: function(elem,p){
		p = $.extend({
		caption : "newButton",
		title: '',
		buttonicon : 'ui-icon-newwin',
		onClickButton: null,
		position : "last"
	}, p ||{});
	var $elem = $(elem);
	var tableString="<table style='float:left;table-layout:auto;' cellspacing=\"0\" cellpadding=\"0\" border=\"0\" class='ui-toolbar-table'>";
	tableString+="<tbody> <tr></tr></table>";
	//console.log("In toolbar button add method");
		/* 
		* Step 1: check whether a table is already added. If not add
		* Step 2: If there is no table already added then add a table
		* Step 3: Make the element ready for addition to the table 
		* Step 4: Check the position and corresponding add the element
		* Step 5: Add other properties 
		*/
		//step 1 
		return this.each(function() {
			if( !this.grid)  { return; }
			if(elem.indexOf("#") != 0) { 
				elem = "#"+elem; 
			}
			//step 2
			if($(elem).children('table').length === 0){
				$(elem).append(tableString);
			}	
			//step 3
			var tbd = $("<td style=\"padding-left:1px;padding-right:1px\"></td>");
			$(tbd).addClass('ui-toolbar-button ui-corner-all').append("<div class='ui-toolbar-div'><span class='ui-icon "+p.buttonicon+"'></span>"+"<span>"+p.caption+"</span>"+"</div>").attr("title",p.title  || "")
			.click(function(e){
				if ($.isFunction(p.onClickButton) ) { p.onClickButton(); }
				return false;
			})
			.hover(
				function () {$(this).addClass("ui-state-hover");},
				function () {$(this).removeClass("ui-state-hover");}
			);
			if(p.id) {$(tbd).attr("id",p.id);}
			if(p.align) {$(elem).attr("align",p.align);}
			var findnav=$(elem).children('table');
			if(p.position ==='first'){
				if($(findnav).find('td').length === 0 ) {
					$("tr",findnav).append(tbd);
				} else {
					$("tr td:eq(0)",findnav).before(tbd);
				}
			} else {
				//console.log("not first");
				$("tr",findnav).append(tbd);
			}
		});
	}
});


jQuery(document).ready(function(){ 
	jQuery("#htmlTable").jqGrid({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=pendaftaran_bu',
		editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=pendaftaran_bu&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		toolbar: [true, "top"],
		colNames:['id','NO. PENDAFTARAN','TANGGAL','NAMA PEMOHON','JENIS PENDAFTARAN','NPWP/R'],
		colModel :[
			{ name:'id'	,index:'id'	,width:20 ,search:false	}, 
			{ name:'no_pendaftaran' ,index:'no_pendaftaran',width:180,editable:false  ,edittype:'text',
			  editoptions: {size:50, maxlength: 50}	,editrules: {required:true}
			},
			{ name:'tanggal',index:'tanggal_kartu',width:80	,editable:false  ,edittype:'text' ,
			  editoptions: {size:50, maxlength: 50} ,editrules: {required:true}
			},
			{ name:'nama' ,index:'nama' ,width:180,editable:false ,edittype:'text',
			  editoptions: {size:50, maxlength: 50},editrules: {required:true}
			},
			{ name:'jenis',index:'jenis_pendaftaran',width:180,editable:false ,edittype:'text' ,
			  editoptions: {size:50, maxlength: 50},editrules: {required:true}
			},
			{ name:'npwp',index:'npwp',width:180,editable:false ,edittype:'text',
			  editoptions: {size:50, maxlength: 50} ,editrules: {required:true}
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
		caption: 'DATA PENDAFTARAN GOL. 2 (BADAN USAHA/GOLONGAN) ',
		ondblClickRow: function(id){ 
            if(id && id!==lastsel){ 
				jQuery.post("request.php?mod=pendaftaran&func=list&sender=get-pendaftaran-bu&val="+id, {},
					function(data){
						jQuery('#proses').val('Ubah');
						jQuery('#batal').attr('disabled',false);
						jQuery('#caction').val('edit');
						jQuery('#idmasters').val(id);
						jQuery('#nomor').val(data.nomor);
						jQuery('#jenis_pendaftaran').val(data.jenis);
						jQuery('#pemohon').val(data.pemohon);						
						jQuery('#badan_nama').val(data.badan_nama);
						jQuery('#alamat_bu').val(data.alamat_bu);
						jQuery('#kecamatan_bu').val(data.kecamatan_bu);
						jQuery('#kelurahan_bu').val(data.kelurahan_bu);
						jQuery('#telp_bu').val(data.telp_bu);				
						jQuery('#fax_bu').val(data.fax_bu);
						jQuery('#nama_pk').val(data.nama_pk);
						jQuery('#alamat_pk').val(data.alamat_pk);
						jQuery('#kecamatan_pk').val(data.kecamatan_pk);
						jQuery('#kelurahan_pk').val(data.kelurahan_pk);
						jQuery('#telp_pk').val(data.telp_pk);
						jQuery('#hp_pk').val(data.hp_pk);						
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

	$('#htmlTable').toolbarButtonAdd("#t_htmlTable",
		{caption:"Toggle",position:"first",title:"Toggle Search Toolbar", align:"right", buttonicon :'ui-icon-search', 
		onClickButton:function(){ $('#dialog').dialog('open'); } }
	);
	
	// mygrid[0].toggleToolbar();
	//$('.ui-jqgrid-titlebar-close','#htmlTable').remove();
	$('#htmlPager_right').remove();
	$('#htmlPager_center').remove();
	
	
	jQuery("#htmlTable2").jqGrid({
		url:'request.php?mod=pendaftaran&func=list&sender=list-bu',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','NAMA BADAN USAHA'],
		colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{ name:'nama' ,index:'nama_pemohon'	,width:300 }
		],
		pager: jQuery('#htmlPager2'),
		height:150,
		rowNum:5,
		rowList:[5,10,15],
		rownumbers: true,
		shrinkToFit:false,
		sortname: 'id',
		sortorder: 'asc',
		viewrecords: true,
		caption: '&nbsp;',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel2){ 
				jQuery.post("request.php?mod=pendaftaran&func=list&sender=get-bu&val="+id, {},
					function(data){
						
						jQuery('#pemohon').val(id);
						jQuery('#badan_nama').val(data.badan_nama);
						jQuery('#alamat_bu').val(data.alamat_bu);
						jQuery('#kecamatan_bu').val(data.kecamatan_bu);
						jQuery('#kelurahan_bu').val(data.kelurahan_bu);
						jQuery('#telp_bu').val(data.telp_bu);				
						jQuery('#fax_bu').val(data.fax_bu);
						jQuery('#nama_pk').val(data.nama_pk);
						jQuery('#alamat_pk').val(data.alamat_pk);
						jQuery('#kecamatan_pk').val(data.kecamatan_pk);
						jQuery('#kelurahan_pk').val(data.kelurahan_pk);
						jQuery('#telp_pk').val(data.telp_pk);
						jQuery('#hp_pk').val(data.hp_pk);
						jQuery('#dialog').dialog('close');
						//console.log(data.time);
						
				}, "json");
                jQuery("#htmlTable2").restoreRow(lastsel2); 
                jQuery("#htmlTable2").editRow(id,true); 
                lastsel2=id; 
            }
        },
        gridComplete: function(){
			jQuery("#htmlTable2").setGridWidth(400);
			return true;
		}
    }).navGrid('#htmlPager2'
        ,{add:false,edit:false,del:false}
        ,{} // edit
        ,{height:120, width:300} // add
        ,{} // del
        ,{} // search
    ).hideCol('id');  /*end of on ready event */

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
		objForm = document.getElementById('entri_pendaftaran_bu');
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
		objForm = document.getElementById('entri_pendaftaran_bu');
		objForm.reset();
		jQuery('#proses').val('Simpan');
		jQuery('#batal').attr('disabled',true);
	});		
});
</script>

<div id="dialog" title="Pilih WP/WR Badan Usaha">
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
			<input type='hidden' name='objek_pdrd' id='objek_pdrd' value='BU'>
			<div style='padding:5px;'>
				<fieldset>
				<legend>PENDAFTARAN WAJIB PAJAK/RETRIBUSI</legend>
					<div id='asb_simulasi_form'>
						<div class="singleSide">
							<fieldset class="mainForm">							
							<label class="leftField"><span>No. Pendaftaran</span><input type="text" name="nomor" id='nomor' value="000000" readonly/></label>
							<label class="leftField"><span>Jenis <b class="wajib">*</b></span><select name="jenis_pendaftaran" title='Jenis Pendaftaran' id='jenis_pendaftaran' onchange='setNomor()'><option value=''></option><option value="PAJAK">PAJAK</option><option value="RETRIBUSI">RETRIBUSI</option></select></label>
							<label class="leftField"><span>Nama Badan Usaha<b class="wajib">*</b></span> 
							<input type='text' id='badan_nama' title='Nama Badan Usaha' name='badan_nama' size="25" readonly >
							<input type="button" id="trigger_pemohon" size="2" value=" * ">
							<input id='pemohon' name='pemohon' type='hidden' /></label>
							<label class="leftField"><span>Alamat Badan Usaha</span><textarea title='Alamat Pemohon' name="alamat_bu" id='alamat_bu' col="4" row="3" width='100' readonly></textarea></label>
							<label class="leftField"><span>Kecamatan</span><input type="text" name="kecamatan_bu" id='kecamatan_bu' value="" size="25" readonly /></label>
							<label class="leftField"><span>Kelurahan</span><input type="text" name="kelurahan_bu" id='kelurahan_bu' value="" size="25" readonly /></label>
							<label class="leftField"><span>Kabupaten</span><input type="text" name="kabupaten_bu" id='kabupaten_bu' value="<?=b_fetch('select pemda_kabupaten from info_pemda')?>" size="25" readonly /></label>
							<label class="leftField"><span>Telp</span><input type="text" name="telp_bu" id='telp_bu' value="" size="20" readonly /></label>
							<label class="leftField"><span>Fax</span><input type="text" name="fax_bu" id='fax_bu' value="" size="20" readonly /></label>
							<br>
							<label class="leftField"><span>Nama Pemilik</span><input type="text" name="nama_pk" id='nama_pk' value="" size="25" readonly /></label>
							<label class="leftField"><span>Alamat Pemilik</span><textarea name="alamat_pk" id='alamat_pk' col="4" row="3" width='100' readonly></textarea></label>
							<label class="leftField"><span>Kecamatan</span><input type="text" name="kecamatan_pk" id='kecamatan_pk' value="" size="25" readonly /></label>
							<label class="leftField"><span>Kelurahan</span><input type="text" name="kelurahan_pk" id='kelurahan_pk' value="" size="25" readonly /></label>
							<label class="leftField"><span>Kabupaten</span><input type="text" name="kabupaten_pk" id='kabupaten_pk' value="<?=b_fetch('select pemda_kabupaten from info_pemda')?>" size="25" readonly /></label>
							<label class="leftField"><span>Telp</span><input type="text" name="telp_pk" id='telp_pk' value="" size="20" readonly /></label>
							<label class="leftField"><span>Handphone</span><input type="text" name="hp_pk" id='hp_pk' value="" size="20" readonly /></label>				
							</fieldset>
							</div>
							<div>
							<fieldset class="mainForm">
							<label class="leftField"><span>Kode Usaha/Objek <b class="wajib">*</b></span>
							<select name='kode_usaha' title='Kode USaha/Objek' id='kode_usaha'>
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

</script>		
<? yuiEndEntry() ?>