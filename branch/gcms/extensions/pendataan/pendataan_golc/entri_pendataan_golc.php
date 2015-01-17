<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_pajak_golc");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);

   $qy = "select id,kode_rekening, nama_rekening from rekening_kode where tipe!='' and kelompok!='' and jenis!='' and objek!='' and rincian!='' ";
   $data = gcms_query($qy); $value="'':'',";
   while($rs = gcms_fetch_object($data)){
		$value.="'$rs->kode_rekening - $rs->nama_rekening':'$rs->kode_rekening - $rs->nama_rekening',";
   }

function setNomor(){
	$no = b_fetch('select max(pendataan_no) from pendataan_spt');
	$no++;
	return sprintf('%05d',$no);
}

?>
<script>

var lastsel,lastsel2,lastsel3,idxform=0;

function setNomor(){
	ajax_do('<?=$expath?>setNomor.php');
}

function ajaxFieldGeneral(theData,theUrl,successContainer,fieldTarget,fieldVal) {
  jQuery("#imgLoading").replaceWith('');
  jQuery.ajax({
    type : "POST",
    url:theUrl, //buat action data ke url tujuan
    data : theData,
    beforeSend: function(){
      //jQuery(successContainer).after(img);
	},
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(XMLHttpRequest.responseText);
      jQuery("#imgLoading").replaceWith('');
    },
    success: function(msg){
      if (msg.length > 0) {		
        jQuery(successContainer).html(msg);
        jQuery(successContainer).attr('value',msg);
        if(fieldTarget!='') jQuery(fieldTarget).attr('value',fieldVal);               
        jQuery("#imgLoading").replaceWith('');
      }
    }
  });
}

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

	function hitungPajak(){
		dasar = jQuery('input#jumlah').val()*jQuery('input#tarif').val();
		pajak  = dasar*jQuery('input#persen').val()/100; id = jQuery('input#id').val();
		jQuery('input#pengenaan').val(dasar);
		jQuery('input#pajak').val(pajak);
	}
	
	jQuery("#htmlTable2").jqGrid(
	{
		 url:'request.php?page=<?=$_REQUEST['page']?>&sender=default',
		 editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=default&oper=edit',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Kode Rekening','Jumlah','Tarif Dasar','Dasar Pengenaan','Persentase','Pajak'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{ name:'rekening',index:'rekening', editable:true,edittype:'select',formatter:'select',
			  editoptions:{
				value:{<?=$value?>},
				dataEvents:[
					{ type: 'change',
					  fn:function(e){
						jQuery.ajax({url:'request.php?mod=rekening&func=kode&sender=set_form&id='+this.value,dataType:'json',
							success: function(json){
								jQuery('input#tarif').val(json.tarif);
								jQuery('input#persen').val(json.persen);
							}
						});
					  }
					}
				]
			  } 
			},
			{ name:'jumlah',index:'jumlah',width:80,editable:true,edittype:'text',editrules:{required:true,integer:true},
			  editoptions: {size:20,maxlength:10,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]
			  },
			},
			{ name:'tarif',index:'tarif',width:80,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2},
			  editoptions: {size:20,maxlength:10,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]}
			},
			{ name:'pengenaan',index:'pengenaan',width:120,editable:true,edittype:'text',align:'right',editrules: {required:true,number:true},formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2},
			  editoptions: {size:20,maxlength:10,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]}
			},
			{ name:'persen',index:'persen',width:80,editable:true,edittype:'text',align:'center',editrules: {required:true,number:true},
			  editoptions:{size:10,maxlength:3,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]}
			},
			{ name:'pajak',index:'pajak',width:80,editable:true,edittype:'text',align:'right',editoptions:{size:20,maxlength:10,readonly:'readonly'},editrules:{required:true,number:true},formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}}
		 ],
		pager: jQuery('#htmlPager2'),
		height:110,
		rowNum:5,
		rowList:[5,10,15],
		rownumbers: true,
		multiselect:true,
		multiboxonly: true,
		altRows:true,
		shrinkToFit:false,
		sortname: 'id',
		sortorder: 'asc',
		viewrecords: true,
		caption: '',
        onSelectRow: function(id){ 
            if(id && id!==lastsel2){ 
				//jQuery("#htmlTable2").restoreRow(lastsel2); 
                //jQuery("#htmlTable2").editRow(id,true); 
                //lastsel2=id; 
            }
        },
        gridComplete: function(){
			jQuery("#htmlTable2").setGridWidth( document.width - 500 < 100 ? 300 :document.width - 500);
            return true;
        }
    }).navGrid(
		'#htmlPager2',
		{ add:true,edit:true,del:true},
		{ bSubmit:"Ubah",bCancel:"Tutup",width:600,reloadAfterSubmit:false,afterSubmit:processEdit}, // edit
		{ bSubmit:"Tambah",bCancel:"Tutup",width:600,reloadAfterSubmit:false,afterSubmit:processAdd}, // add
		{ reloadAfterSubmit:false,afterSubmit:processDelete}, // del
		{}
	).hideCol(['id']);

	function processDelete(response, postdata){
		var total=0;
		jQuery("#htmlTable2 > tbody > tr").each(function (){
			if(this.id!=postdata.id){
				tmp = jQuery("#htmlTable2").getRowData(this.id);
				total+=Number(tmp.pajak);
			}
		});		
		jQuery('input#nominal').val(formatCurrency(total));
		return [true,'',null];
	}
	
	function processAdd(response, postdata){
		var old_val = jQuery('input#nominal').val();
		old_val = Number(removeCommas(old_val));
		var new_val = old_val+Number(postdata.pajak);
		jQuery('input#nominal').val(formatCurrency(new_val));
		var success = true; var message = ""; var new_id = "";
		return [success,message,new_id];
	}	
	
	function processEdit(response, postdata){
		var total=0;
		jQuery("#htmlTable2 > tbody > tr").each(function (){
			if(this.id!=postdata.id){
				tmp = jQuery("#htmlTable2").getRowData(this.id);
				total+=Number(tmp.pajak);
			}
		});
		total+=Number(postdata.pajak);
		jQuery('input#nominal').val(formatCurrency(total));
		var success = true; var message = ""; var new_id = "";
		return [success,message,new_id];
	}
		
	jQuery("#htmlTable3").jqGrid({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=pendataan_spt',
		editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=pendataan_spt&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','NO. PENDATAAN','TANGGAL ENTRI','NPWPD/RD','NAMA','JENIS PUNGUTAN','NOMINAL PAJAK','MEMO'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'pendataan_no',index:'pendataan_no',width:120,editable:false},
			{name:'tgl_entry',align:'center',index:'tgl_entry',align:'center',width:100,formatter:'date', sorttype:"date"},			 
			{name:'npwp',index:'npwp',width:110,editable:false,align:'center',edittype:'text',editoptions:{size:50,maxlength:50},editrules:{required:true}},
			{name:'nama',index:'nama',width:200,editable:false,edittype:'text',editoptions:{size:50,maxlength:50},editrules:{required:true}},
			{name:'jenis_pungutan',index:'jenis_pungutan',width:120,editable:false,edittype:'text',editoptions:{size:50,maxlength:50},editrules:{required:true}},			 
			{name:'nominal_pajak',align:'right',index:'nominal_pajak',width:120,formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}},
			{name:'memo',index:'memo',width:150,editable:false,edittype:'text',editoptions:{size:50,maxlength:50},editrules:{required:true}}
		],
		pager: jQuery('#htmlPager3'),
		height:250,
		rowNum:5,
		rowList:[5,10,15],
		rownumbers: true,
		shrinkToFit:false,
		sortname: 'pendataan_no',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'Data Pajak Galian C.',
		subGrid: true,
		multiselect:true,
		multiboxonly:true,			
		footerrow:true,userDataOnFooter:true,
		subGridRowExpanded: function(subgrid_id, row_id) {
			var subgrid_table_id, pager_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			pager_id = "p_"+subgrid_table_id;
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			jQuery("#"+subgrid_table_id).jqGrid({ 
				url:"request.php?mod=pendataan_golc&func=list&sender=default&action=get_list&id="+row_id, 
				datatype: "json",
				rownumbers: true,
				colNames: ['id','Kode Rekening','Jumlah','Tarif Dasar','Dasar Pengenaan','%','Pajak'], 
				colModel: [
					{ name:'id' ,index:'id'	,width:20,key:true ,search:false},
					{name:"rekening",index:"rekening",width:120,search:false,align:"left"}, 
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
		},		
        ondblClickRow: function(id){ 
            if(id && id!==lastsel3){
				jQuery("#htmlTable2").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=default&action=get_list&id="+id}).trigger("reloadGrid");
				$.post("request.php?page=<?=$_REQUEST['page']?>&sender=get_galianc&val="+id, {},
					function(data){						
						jQuery('#proses').val('Ubah');
						jQuery('#batal').attr('disabled',false);
						jQuery('#caction').val('edit');
						jQuery('#idmasters').val(id);

						jQuery('#nomor_reg').val(data.nomor);
						jQuery('#jenis_pungutan').val(data.jenis_pungutan);
						jQuery('#pendaftaran_id').val(data.pendaftaran);
						jQuery('#nomor_spt').val(data.spt_no);
						jQuery('#nama_kegiatan').val(data.nama_kegiatan);
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
	
	jQuery('#coba1').click(function(){
		tmp = {};    
		jQuery("#htmlTable2 > tbody > tr").each(function (){
			tmp[this.id] = jQuery("#htmlTable2").getRowData(this.id);
			/*tmp[this.id].pk=99;
			tmp[this.id].page=<?=$_REQUEST['page']?>;
			tmp[this.id].mode='asyc';
			tmp[this.id].detail=true;
			tmp[this.id].sender='entri_pajak_golc';*/
			//$.post("test.php",  tmp[this.id],
		});
			$.post("request.php", tmp,
				function(data){
					alert("Data Loaded: " + data);
				}
			);		
	});	

	jQuery('#proses').click(function(){	
		objForm = document.getElementById('entri_pajak_golc');
		var rows = jQuery('#htmlTable2').getGridParam('records');
		if(saveEntry(objForm)){
			jQuery(objForm).ajaxSubmit({
				success: function(response){
					act = document.getElementById('caction').value;
					tmp = {}; count=0; sv=0;
					if(response!='!'){
						// proses grid,  - delete
						jQuery("#htmlTable2 > tbody > tr").each(function (){
							tmp = {};
							tmp[this.id] = jQuery("#htmlTable2").getRowData(this.id);
							tmp[this.id].FK=response;
							tmp[this.id].page=<?=$_REQUEST['page']?>;
							tmp[this.id].mode='asyc';							
							if(act!='') tmp[this.id].action='edit';							
							tmp[this.id].rows=rows;
							tmp[this.id].count=count;
							tmp[this.id].detail=true;
							tmp[this.id].sender='entri_pajak_golc';
							$.post("request.php", tmp[this.id],
								function(data){
									count++;
									if(data=='!') sv=0;
									else sv=1;
									if(rows==count){
										if(sv){
											if(act!='') document.getElementById('caction').value='';
											alert('Data telah tersimpan..');
											jQuery("#htmlTable2").trigger("reloadGrid");
											objForm.reset();
											setNomor();
										}else alert('cek lagi !');
									}
								}
							);
						});
					}else{
						alert(response);
					}
					if(act!=''){
						jQuery('#proses').val('Simpan');
						jQuery('#batal').attr('disabled',true);
					}
				}	
			});
		}
	});	

	jQuery('#batal').click(function(){	
		objForm = document.getElementById('entri_pajak_golc');
		objForm.reset();
		document.getElementById('caction').value='';
		setNomor();
		jQuery('#proses').val('Simpan');
		jQuery('#batal').attr('disabled',true);
		jQuery("#htmlTable2").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=default'}).trigger("reloadGrid");
	});	
	
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
	
	$('#tgl_proses').datepicker({changeMonth: true, changeYear: true});
	$('#periode_awal').datepicker({changeMonth: true, changeYear: true});
	$('#tgl_entry').datepicker({changeMonth: true, changeYear: true});
	$('#periode_akhir').datepicker({changeMonth: true, changeYear: true});
	
	$("#tabs").tabs();

});

 
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
					<legend>ISIAN SPTPD - BAHAN GALIAN GOL. C</legend>
					<div>
						<div class="form_master">
							<fieldset class="form_frame">
								<div><label>No. Reg Form</label><input type='text' id='nomor_reg' name='nomor_reg' size='25' value='<?=setNomor()?>' readonly/></div>
								<div><label>Tanggal Proses <b class="wajib">*</b></label><input type="text" name="tgl_proses" id="tgl_proses" title='Tgl. Proses' onchange="" size="10"/></div>
								<div><label>Tanggal Entry <b class="wajib">*</b></label><input type="text" name="tgl_entry" title='Tgl. Entry' id="tgl_entry" onchange="" size="10"/></div>
								<div><label>Masa Pajak <b class="wajib">*</b></label><input type="text" name='periode_awal' title='Periode Awal' id='periode_awal' size="10"/> S/D <input type="text" name='periode_akhir' title='Periode Akhir' id='periode_akhir' size="10"/></div>
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
								<div><label>Sistem Pemungutan <b class="wajib">*</b></label><select name="jenis_pungutan" title='Sistem Pemungutan' id='jenis_pungutan'></option><option value="SELF">Selft Assesment</option><option value="OFFICE">Office Assesment</option></select></div>
							</fieldset>
							<fieldset class="form_frame">
								<div><label>Nama Kegiatan</label><input type="text" name="nama_kegiatan" id='nama_kegiatan' value="" size="25"/></div>
								<div><label>Nomor SPT</label>
								<input type="text" name="nomor_spt" id='nomor_spt' value="" size="25" readonly />
								<input type="hidden" name="spt" id='spt' value="" size="25" readonly />
								<input type="hidden" name="pendaftaran_id" id='pendaftaran_id' size="25" readonly />
								</div>
								<div><label>Nama WP/WR</label><input type="text" name="nama_pemohon" id='nama_pemohon' value="" size="25" readonly /></div>
								<div><label>Alamat</label><textarea name="alamat" id='alamat' col="4" row="3" readonly></textarea></div>
								<div><label>Kecamatan</label><input type="text" name="kecamatan" id='kecamatan' value="" size="25" readonly /></div>
								<div><label>Kelurahan</label><input type="text" name="kelurahan" id='kelurahan' value="" size="25" readonly /></div>
								<div><label>Kabupaten</label><input type="text" name="kabupaten" id='kabupaten' value="<?=b_fetch('select pemda_kabupaten from info_pemda')?>" size="25" readonly /></div>
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
				<fieldset>
					<legend>Detail Kegiatan</legend>
					<div id='asb_simulasi_form'>
						<div style='padding:5px'>
							<table>
							<tr align='right'><td>
								<span style='font-weight:bold;font-size:25px'>Total</span><input type='text' name='rp' id='rp' value='Rp.' style="font-weight: bold; font-size: 25px; color: rgb(24, 245, 24); background-color: black; text-align:center;padding-right:0;margin-right:0" size='1'><input type="text" style="font-weight: bold; font-size: 25px; color: rgb(24, 245, 24); background-color: black; text-align: right; padding-left:0;margin-left:0" readonly="true" value="" size="13" class="inputbox" id="nominal" name="nominal">
							</td></tr>
							<tr><td>
							<table id="htmlTable2" class="scroll"></table>
							<div id="htmlPager2" class="scroll"></div>
							</td></tr></table>
							<!--<a href="javascript:void(0)" id="coba1">Coba 1</a>-->
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
							<table id="htmlTable3" class="scroll"></table>
							<div id="htmlPager3" class="scroll"></div>
						</div>
					</div>			
				</fieldset>
			</div>
		</div>
	</div>
</div>

