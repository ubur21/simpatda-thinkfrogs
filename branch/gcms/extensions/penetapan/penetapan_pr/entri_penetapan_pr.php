<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_penetapan_pr");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);

?>
<script>

var lastsel2;
var lastsel;
var timeoutHnd; var flAuto = false; 

$().ready(function(){
	
	jQuery("#htmlTable3").jqGrid({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=default',
		editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=default&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','No.Pendataan','Tgl. Pendataan','No. NPWPD/RW','Nama NPWPD/RW','Jenis Pendataan','Jenis Pungutan','Jenis Pendaftaran','No. SPT','Tgl. SPT','Nominal'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'pendataan_no',index:'pendataan_no',width:80,editable:true},
			{name:'tgl_entry',index:'tgl_entry',width:90,editable:true,align:'center',formatter:'date', sorttype:"date",formatoptions:{newformat:'d/m/Y'}},
			{name:'npwp',index:'npwp',width:100,editable:true,align:'center'},
			{name:'nama',index:'nama',width:220,editable:true},
			{name:'jenis_pendataan',index:'jenis_pendataan',width:150,editable:true},			 
			{name:'jenis_pungutan',index:'jenis_pungutan',width:100,editable:true},
			{name:'jenis_pendaftaran',index:'jenis_pendaftaran',width:110,editable:true},
			{name:'spt_no',index:'spt_no',width:80,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'spt_tgl',index:'spt_tgl',width:180,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'nominal',index:'nominal',width:140,editable:true,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}}
		],
		pager: jQuery('#htmlPager3'),
		height:150,
		rowNum:5,
		rowList:[5,10,15],
		mtype:"POST",
		shrinkToFit:false,
		sortname: 'pendataan_no',
		sortorder: 'desc',
		rownumbers: true,
		multiselect:true,
		multiboxonly: true,
		viewrecords: true,
		caption: '&nbsp;',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel){ 				
                //lastsel=id; 
				var arr = jQuery("#htmlTable3").getRowData(id);
				//alert(YAHOO.lang.dump(arr));
            }
        },
		onHeaderClick:function(stat){ 
			if(stat == 'visible' ){ 
				jQuery("#filter").css("display","none"); 
			} 
		}, 
		gridComplete: function(){
			/*$('.cbox').click(function(){
				var arr_check = jQuery("#htmlTable3").getGridParam('selarrrow');
				alert(YAHOO.lang.dump(arr_check));
				jQuery.each(arr_check,function(idx,value){
					var data = jQuery("#htmlTable3").getRowData(value);
					alert(YAHOO.lang.dump(data));
				});
			});*/
            jQuery("#htmlTable3").setGridWidth( document.width - 500 < 100 ? 100:document.width - 180);
            return true;
        }
    }).navGrid('#htmlPager3'
        ,{edit:false,add:false,del:false,search:false,refresh:true,view:true}
        ,{} // edit
        ,{height:200, width:500,reloadAfterSubmit:false} // add
        ,{} // del
        ,{} // search
		,{height:250,width:500,jqModal:false,closeOnEscape:true}
    ).hideCol('id');
	
	jQuery("#htmlTable2").jqGrid({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar',
		editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','No. Kohir','Tgl. Penetapan','Tgl. Setor','Nominal','No. Pendataan','Periode Awal','Periode Akhir','Jenis Pendataan'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'no_kohir',index:'no_kohir',width:60},
			{name:'tgl_kohir',index:'tgl_kohir',align:'center',width:100,formatter:'date', sorttype:"date"},
			{name:'tgl_setor',index:'tgl_setor',align:'center',width:100,formatter:'date', sorttype:"date"},
			{name:'nominal',align:'right',index:'nominal',width:150,formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}},
			{name:'pendataan_no',index:'pendataan_no',width:110},
			{name:'periode_awal',index:'periode_awal',align:'center',width:100,formatter:'date', sorttype:"date"},
			{name:'periode_akhir',index:'periode_akhir',align:'center',width:100,formatter:'date', sorttype:"date"},
			{name:'jenis',index:'jenis',width:200}
		],
		pager: jQuery('#htmlPager2'),
		height:250,
		rowNum:10,
		rowList:[10,15,20],
		rownumbers: true, 
		shrinkToFit:false,
		sortname: 'pendataan_no',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'Data Penetapan Pajak',
		multiselect:true,
		multiboxonly:true,
		/*toolbar: [true,"top"],
		footerrow:true,userDataOnFooter:true,
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) {
			var subgrid_table_id, pager_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			pager_id = "p_"+subgrid_table_id;
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			jQuery("#"+subgrid_table_id).jqGrid({ 
				url:"request.php?mod=pendataan_airBawahTanah&func=list&sender=default&action=get_list&id="+row_id, 
				datatype: "json", 
				rownumbers: true,
				colNames: ['id','Kode Rekening','Lokasi','Jumlah','Tarif Dasar','Dasar Pengenaan','%','Pajak'], 
				colModel: [
					{ name:'id' ,index:'id'	,width:20,key:true ,search:false},
					{name:"rekening",index:"rekening",width:120,search:false,align:"left"}, 
					{name:"lokasi",index:"lokasi",width:130,search:false}, 
					{name:"jumlah",index:"jumlah",width:60,align:"center",formatter:'number'}, 
					{name:"tarif",index:"tarif",width:90,align:"right",formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}}, 
					{name:"pengenaan",index:"pengenaan",width:110,align:"right",sortable:false,formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}},
					{name:"persen",index:"persen",width:60,align:"right"}, 
					{name:"pajak",index:"pajak",width:110,align:"right",sortable:false,formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}},
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
		},*/
        ondblClickRow: function(id){ 
            if(id && id!==lastsel2){
				jQuery("#htmlTable2").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=default&action=get_list&id="+id}).trigger("reloadGrid");
				jQuery.post("request.php?page=<?=$_REQUEST['page']?>&sender=get_air_tanah&val="+id, {},
					function(data){						
						jQuery('#proses').val('Ubah');
						jQuery('#batal').attr('disabled',false);
						jQuery('#caction').val('edit');
						jQuery('#idmasters').val(id);

						jQuery('#nomor_reg').val(data.nomor);
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
						jQuery('input#nominal').val(formatCurrency(data.nominal));
											
						jQuery("#tabs").tabs('select', 0);
						
				}, "json");
				jQuery("#htmlTable2").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=default'});
                //lastsel=id; 
            }
        },
		gridComplete: function(){
            jQuery("#htmlTable2").setGridWidth(document.width - 500 < 100 ? 100 : document.width - 250);
            return true;
        }
    }).navGrid('#htmlPager2'
        ,{add:false,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{multipleSearch:true} // search
    ).hideCol('id'); 
	
	function doSearch(ev){ 
		if(!flAuto) return; 
		// var elem = ev.target||ev.srcElement; 
		if(timeoutHnd) clearTimeout(timeoutHnd);
		timeoutHnd = setTimeout(gridReload,500) 
	} 
			
	function enableAutosubmit(state){ 
		flAuto = state; 
		jQuery("#submitButton").attr("disabled",state); 
	} 	
	
	jQuery("#htmlTable3").navButtonAdd(
		"#htmlPager3",
		{
			caption:"Search",title:"Toggle Search", 
			onClickButton:function(){ 
				if(jQuery("#filter").css("display")=="none") { 
					jQuery(".HeaderButton","#gbox_htmlTable3").trigger("click"); 
					jQuery("#filter").show(); 
				} 
			} 
		}
	);
	jQuery("#htmlTable3").navButtonAdd(
		"#htmlPager3",{
			caption:"Clear",title:"Clear Search",buttonicon:'ui-icon-refresh', 
			onClickButton:function(){ 
				var stat = jQuery("#htmlTable3").getGridParam('search'); 
				if(stat) { 
					var cs = jQuery("#filter")[0]; 
					cs.clearSearch(); 
				} 
			} 
		}
	);

	jQuery("#filter").filterGrid(
		"htmlTable3", 
		{ 
			gridModel:true, gridNames:true, formtype:"vertical", enableSearch:true, enableClear:false, autosearch: false, 
			afterSearch : function() { 
				jQuery(".HeaderButton","#gbox_htmlTable3").trigger("click"); 
				jQuery("#filter").css("display","none"); 
			} 
		} 
	); 	
	
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
	
	$('#tgl_penetapan').datepicker({changeMonth: true, changeYear: true});
	$('#tgl_setor').datepicker({
		flat: true,
		date: ['2008-07-28','2008-07-31'],
		current: '2008-07-31',
		calendars: 3,
		mode: 'range',
		starts: 1

	});
	$('#tgl_kirim').datepicker({changeMonth: true, changeYear: true});
	$('#tgl_kembali').datepicker({changeMonth: true, changeYear: true});
	$("#tabs").tabs();
	
	jQuery('#proses').click(function(){	
		objForm = document.getElementById('entri_penetapan_pr'); 
		tmp={};
		var arr_check = jQuery("#htmlTable3").getGridParam('selarrrow');
		var rows = arr_check.length; var mode = document.getElementById('cinput');
		var no1 = trim(document.getElementById('no_data_awal').value); var no2 = trim(document.getElementById('no_data_akhir').value);
		if(!arr_check.length && !mode.checked){
			alert('Data Pendataan Belum Dipilih !');
		}else if((mode.checked && no1=='') || (mode.checked && no2=='')){
			alert('Range No. Pendataan Belum Diisi !');
		}else{
			if(saveEntry(objForm)){
				jQuery(objForm).ajaxSubmit({
					success: function(response){
						act = document.getElementById('caction').value;
						chk = document.getElementById('cinput');
						count=0; sv=0;
						if(response!='!'){
							if(!chk.checked){
								//alert('3. '+response);
								jQuery.each(arr_check,function(idx,value){
									var data = jQuery("#htmlTable3").getRowData(value);
									data.FK=response;
									data.page=<?=$_REQUEST['page']?>;
									data.mode='asyc';							
									if(act!='') data.action='edit';
									//if(document.getElementById('cnomor').checked) data.cnomor='on';
									data.tgl_penetapan=document.getElementById('tgl_penetapan').value;
									data.memo=document.getElementById('memo').value;
									//data.nominal_pajak=document.getElementById('nominal_pajak').value;
									data.tgl_setor=document.getElementById('tgl_setor').value;
									data.rows=rows;
									data.count=count;
									data.detail=true;
									data.sender='entri_penetapan_pr';
									//alert(YAHOO.lang.dump(data));
									$.post("request.php", data,
										function(data){
											//alert('4. '+data);
											count++;
											if(data=='!') sv=0;
											else sv=1;
											if(rows==count){
												if(sv){
													if(act!='') document.getElementById('caction').value='';
													alert('Data telah tersimpan..');
													setDefaultForm(objForm);
												}else alert('cek lagi !');									
											}
										}
									);
								});
							}else{
								//alert('2. '+response);
								if(act!='') document.getElementById('caction').value='';
								alert('Data telah tersimpan..');
								setDefaultForm(objForm);
							}
						}else{
							alert('1. '+response);
						}						
						if(act!=''){
							jQuery('#proses').val('Simpan');
							jQuery('#batal').attr('disabled',true);
						}						
					}	
				});
			}
		}
	});
			
	jQuery('#coba1').click(function(){
		var val = document.getElementById('cinput');
		var arr_check = jQuery("#htmlTable3").getGridParam('selarrrow');
		alert(arr_check.length);
		/*tmp = {};

		//alert(YAHOO.lang.dump(arr_check));
		//alert(YAHOO.lang.dump(tmp));
		var response=99; rows=1; count=3;
		jQuery.each(arr_check,function(idx,value){
			var data = jQuery("#htmlTable3").getRowData(value);
			data.FK=response;
			data.page=<?=$_REQUEST['page']?>;
			data.mode='asyc';							
			//if(act!='') data.action='edit';							
			data.rows=rows;
			data.count=count;
			data.detail=true;
			data.sender='entri_penetapan_pr';
			alert(YAHOO.lang.dump(data));
			$.post("request.php", data,
				function(data){
					alert("Data Loaded: " + data);
				}
			);
		});*/

		//
		//alert(YAHOO.lang.dump(ar));
		/*jQuery("#htmlTable3 > tbody > tr").each(function (){
			tmp = {};
			tmp[this.id] = jQuery("#htmlTable3").getRowData(this.id);
			alert(YAHOO.lang.dump(tmp));
			$.post("request.php", tmp[this.id],
				function(data){
					alert("Data Loaded: " + data);
				}
			);
		});*/
	});	
	
	jQuery('#batal').click(function(){	
		objForm = document.getElementById('entri_penetapan_pr');
		objForm.reset();
		jQuery('#proses').val('Simpan');
		jQuery('#batal').attr('disabled',true);
	});
	jQuery('#cinput').click(function(){
		if(this.checked){
			document.getElementById('no_data_awal').readOnly=false;
			document.getElementById('no_data_akhir').readOnly=false;
		}else{
			document.getElementById('no_data_awal').readOnly=true;
			document.getElementById('no_data_akhir').readOnly=true;
		}
	});	
	jQuery('#cari').click(function(){
		var jenis_pendaftaran = jQuery("#jenis_pendaftaran").val();
		var jenis_pungutan = jQuery("#pungutan").val(); 
		var tgl1 = jQuery("#tgl_kirim").val(); 
		var tgl2 = jQuery("#tgl_kembali").val();
		
		jQuery("#htmlTable3").setGridParam({
			//url:"bigset.php?nm_mask="+nm_mask+"&cd_mask="+cd_mask,
			url:"request.php?mod=penetapan_pr&func=list&sender=default&a="+jenis_pendaftaran+'&b='+jenis_pungutan+'&tgl1='+tgl1+'&tgl2='+tgl2,
			page:1
		}).trigger("reloadGrid");
		jQuery("#htmlTable3").setGridParam({url:"request.php?mod=penetapan_pr&func=list&sender=default"});	
	});
	jQuery('#tgl_penetapan').change(function(){
		setTglSetor(this.value);
		setNomor(this.value);
	});
});

function setDefaultForm(objForm){
	objForm.reset();
	jQuery("#htmlTable3").trigger("reloadGrid");
	//jQuery(objForm).clearForm();
	setNomor(currentDate());
	setTglSetor(currentDate());
	document.getElementById('tgl_penetapan').value=currentDate();
	/*document.getElementById('cnomor').checked=true;
	document.getElementById('cinput').checked=false;
	document.getElementById('no_data_awal').readOnly=true;
	document.getElementById('no_data_akhir').readOnly=true;*/
	
}

function setTglSetor(tgl){
	jQuery.ajax({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=get_tgl_setor&tgl='+tgl,
		dataType:'text',
		success: function(result){
			jQuery('#tgl_setor').val(result);
		}
	});
}
function setNomor(tgl){ 
	jQuery.ajax({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=set_nomor&tgl='+tgl,
		dataType:'text',
		success: function(result){
			jQuery('#nomor_reg').val(result);
		}
	});
}
</script>

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

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Input</a></li>
			<li><a href="#tabs-2">Data</a></li>
		</ul>
		<div id="tabs-1">
			<div style='padding:5px;'>
				<fieldset>
				<legend>FORM PENETAPAN</legend>
					<div>
						<div class="form_master">
							<fieldset class="form_frame">
								<!--<div><label>Duplikasi Nomor</label><input type='checkbox' name='cnomor' id='cnomor' checked /></div>-->
								<div><label>No. Penetapan</label><input type="text" name="nomor_reg" id='nomor_reg' size='10' value="<?=sprintf('%05d',getNoKohir())?>" readonly/></div>
								<div><label>Tgl. Penetapan<b class="wajib">*</b></label><input type="text" name="tgl_penetapan" title='Tgl. Penetapan' id="tgl_penetapan" value='<?=date('d/m/Y')?>' onchange="" size="10"/></div>
								<div><label>Tgl. Batas Setor<b class="wajib">*</b></label><input type="text" name="tgl_setor" title='Tgl. Batas Setor' id="tgl_setor" value='<?=getExpired(date('d/m/Y'),b_fetch('select jatem_bayar from ref_jatuh_tempo'));?>' onchange="" size="10"/></div>
								<!--<div><label>Jenis Penetapan</label><select name='jenis_penetapan' id='jenis_penetapan'><?=getKeteranganSPT();?></select></div>-->
								<div><label>No. Pendataan<input type='checkbox' name='cinput' id='cinput' ></label><input type='text' name='no_data_awal' id='no_data_awal' size='5' readonly /> S/D <input type='text' name='no_data_akhir' id='no_data_akhir' size='5' readonly /></div>
								<div><label>Keterangan</label><textarea name='memo' id='memo'></textarea></div>
								<div><label>&nbsp;</b></label><input type='button' id='proses' class='btn' value='Simpan'></input></div>
							</fieldset>
							<fieldset class="form_frame">
								<div><label>Jenis Pendaftaran </label><select name="jenis_pendaftaran" id='jenis_pendaftaran'><option value=''></option><option value="PAJAK">Pajak</option><option value="RETRIBUSI">Retribusi</option></select></div>
								<div><label>Sistem Pemungutan </label><select name="pungutan"  id='pungutan'><option value=''></option><option value="SELF">Selft Assesment</option><option value="OFFICE">Office Assesment</option></select></div>
								<div><label>Tanggal Pendataan </label><input type="text" name="tgl_kirim" id="tgl_kirim" onchange="" size="10"/> S/D <input type="text" name="tgl_kembali" id="tgl_kembali" onchange="" size="10"/></div>
								<div><label>&nbsp;</label><input type='button' class='btn' id='cari' name='cari' value='Cari'></input></input></div>
								<!--<div><label><span style='font-weight:bold;font-size:22px'>Total</span></label><span style="font-weight:bold;font-size:25px;color:rgb(24,245,24);background-color:black;text-align:center;padding-right:0;margin-right:0;">Rp.</span><input type="text" style="font-weight: bold; font-size:25px; color: rgb(24, 245, 24); background-color: black; text-align: right; padding-left:0;margin-left:0" readonly="true" value='0.00' size="13" class="inputbox" id="nominal_pajak" name="nominal_pajak"></div>-->
								<!--<a href="javascript:void(0)" id="coba1">Coba 1</a>-->
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
		<div id="tabs-2">
			<div style='padding:5px;'>
				<fieldset>
				<legend>Daftar</legend>
					<div id='asb_simulasi_form'>
						<div style='padding:5px'>
							<table id="htmlTable2" class="scroll"></table>
							<div id="htmlPager2" class="scroll"></div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>		
	</div>
</div>
<script>
/*
	function processAddEdit(response, postdata) {
		var success = true;
		var message = "";
		//alert(postdata.rekening);
		var su=jQuery("#htmlTable2").jqGrid('setRowData',1,{rekening:postdata.rekening}); 
		var json = eval('(' + response.responseText + ')');
		if(json.errors) {
			success = false;
			for(i=0; i < json.errors.length; i++) {
				message += json.errors[i] + '<br/>';
			}
		}
		var new_id = "1";
		jQuery('input#rekening').val(99);
		return [success,message,new_id];		
	}
*/
</script>