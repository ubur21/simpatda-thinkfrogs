<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_penyetoran_pr");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);

?>
<script>

var lastsel2;
var lastsel;
var timeoutHnd; var flAuto = false; 

$().ready(function(){

	jQuery("#htmlTable2").jqGrid(
	{
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=pilih',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','Kode Rekening','Nama Rekening','Tahun Penerimaan','Nominal'],
		colModel :[
			{name:'id',index:'id',search:false},
			{name:'kode_rekening',index:'kode_rekening',width:120},
			{name:'nama_rekening',index:'nama_rekening',width:220},
			{name:'thn_penerimaan',index:'thn_penerimaan',width:120,align:'center'},
			{name:'nominal',index:'nominal',width:220,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}}
		],
		pager: jQuery('#htmlPager2'),
		height:150,
		rowNum:5,
		rownumbers:true,
		rowList:[10,15,20],
		shrinkToFit:false,
		sortname: 'nama',
		sortorder: 'asc',
		multiselect:true,
		viewrecords: true,
		caption: 'Daftar Penerimaan ',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel2){ 
				var arr = jQuery("#htmlTable2").getRowData(id);
				jQuery('input#id_dinas').val(id);
				jQuery('input#nama_dinas').val(arr.nama);			
				$('#dialog2').dialog('close');
            }
        },
        gridComplete: function(){
			jQuery("#htmlTable2").setGridWidth(750);
            return true;
        }
    }).navGrid('#htmlPager2'
        ,{add:false,edit:false,del:false}
        ,{} // edit
        ,{height:120, width:300} // add
        ,{} // del
        ,{} // search        
    ).hideCol(['id']);
	
	jQuery("#htmlTable3").jqGrid({
		url:'request.php?mod=penyetoran_pr&func=list&sender=default',
		editurl:'request.php?mod=penyetoran_pr&func=list&sender=default&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','Kode Rekening','Nama Rekening','Tahun Penerimaan','Nominal'],
		colModel :[
			{name:'id',index:'id',search:false},
			{name:'kode_rekening',index:'kode_rekening',width:120},
			{name:'nama_rekening',index:'nama_rekening',width:220},
			{name:'thn_penerimaan',index:'thn_penerimaan',width:120,align:'center'},
			{name:'nominal',index:'nominal',width:220,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}}
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
            }
        },
		gridComplete: function(){
            jQuery("#htmlTable3").setGridWidth( document.width - 500 < 100 ? 100:document.width - 500);
            return true;
        }
    }).navGrid('#htmlPager3'
        ,{edit:false,add:false,del:true,search:false,refresh:true}
        ,{reloadAfterSubmit:false} // edit
        ,{height:200, width:500,reloadAfterSubmit:false} // add
        ,{reloadAfterSubmit:false} // del
        ,{} // search
		,{height:250,width:500,jqModal:false,closeOnEscape:true}
    ).hideCol('id');

	jQuery("#htmlTable4").jqGrid({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_sts',
		editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_sts&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','No. STS','Tanggal','Nominal','Keterangan'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'no_bukti',index:'no_bukti',width:100},
			{name:'tgl_bayar',index:'tgl_bayar',align:'center',width:100,formatter:'date', sorttype:"date"},
			{name:'nominal',align:'right',index:'nominal',width:150,formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}},
			{name:'keterangan',index:'keterangan',width:100}
		],
		pager: jQuery('#htmlPager4'),
		height:250,
		rowNum:10,
		rowList:[10,15,20],
		rownumbers: true, 
		shrinkToFit:false,
		sortname: 'pendataan_no',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'Data Penyetoran Pajak',
		multiselect:true,
		multiboxonly:true,
		//toolbar: [true,"top"],
		footerrow:true,userDataOnFooter:true,
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) {
			var subgrid_table_id, pager_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			pager_id = "p_"+subgrid_table_id;
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
			jQuery("#"+subgrid_table_id).jqGrid({ 
				url:"request.php?mod=penyetoran_pr&func=list&sender=rincian_sts&id="+row_id, 
				datatype: "json", 
				rownumbers: true,
				colNames: ['id','Kode Rekening','Nama Rekening','Nominal'], 
				colModel: [
					{ name:'id' ,index:'id'	,width:20,key:true ,search:false},
					{name:"kode",index:"kode",width:120,search:false,align:"left"},
					{name:"nama",index:"nama",width:130,search:false},
					{name:"nominal",index:"nominal",width:110,align:"right",sortable:false,formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}},
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
            if(id && id!==lastsel2){
				jQuery("#htmlTable4").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=default&action=get_list&id="+id}).trigger("reloadGrid");
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
				jQuery("#htmlTable4").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=default'});
                //lastsel=id; 
            }
        },
		gridComplete: function(){
            jQuery("#htmlTable4").setGridWidth(document.width - 500 < 100 ? 100 : document.width - 250);
            return true;
        }
    }).navGrid('#htmlPager4'
        ,{add:false,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{multipleSearch:true} // search
    ).hideCol('id');	
	
	jQuery("#htmlTable3").navButtonAdd(
		"#htmlPager3",
		{
			caption:"Tambah",title:"Tambah", 
			onClickButton:function(){
				var counter=0; var str_id='';
				jQuery("#htmlTable3 > tbody > tr").each(function(){
					str_id+=this.id+'|';
					counter++;
				});
				if(!counter){
					jQuery("#htmlTable2").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=pilih"}).trigger('reloadGrid');
				}else{
					jQuery("#htmlTable2").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=pilih&str_id="+str_id}).trigger("reloadGrid");
				}
				jQuery("#htmlTable2").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=pilih"});
				$('#dialog2').dialog('open');
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
		jQuery("#htmlTable2").reloadGrid();
		$('#dialog2').dialog('open');
	});
	
	$("#dialog").dialog({
		bgiframe: true, resizable: false, height:350, width:500, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			}
		}
	});

	$("#dialog2").dialog({
		bgiframe: true, resizable: false, height:350, width:800, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				$(this).dialog('close');
			},
			'Tambah': function() {				
				var str_id=''; var nom=0;
				var arr_check = jQuery("#htmlTable2").getGridParam('selarrrow');
				jQuery.each(arr_check,function(idx,value){
					var data = jQuery("#htmlTable2").getRowData(value);
					var su = jQuery("#htmlTable3").addRowData(data.id,data);
					//alert(new_val+'#'+data.nominal);
				});
				//alert('F'+new_val);
				jQuery("#htmlTable3 > tbody > tr").each(function(){
					str_id+=this.id+'|';
					var data = jQuery("#htmlTable3").getRowData(this.id);
					nom+=Number(data.nominal);
				});
				jQuery("#htmlTable2").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=pilih&str_id="+str_id}).trigger("reloadGrid");
				jQuery("#htmlTable2").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=pilih"});
				jQuery('input#nominal_pajak').val(formatCurrency(nom));
			}			
			
		}
	});	
	
	$('#tgl_setor').datepicker({changeMonth: true, changeYear: true});
	
	$("#tabs").tabs();
	
	jQuery('#proses').click(function(){	
		objForm = document.getElementById('entri_penyetoran_pr'); 
		tmp={};
		if(saveEntry(objForm)){
			jQuery(objForm).ajaxSubmit({
				success: function(response){
					act = document.getElementById('caction').value;
					count=0; sv=0;
					if(response!='!'){
						//alert('3. '+response);
						var arr_check = jQuery("#htmlTable3").getGridParam('selarrrow');
						//var rows = arr_check.length;
						var rows = jQuery("#htmlTable3").getGridParam('records');
						act = document.getElementById('caction').value;
						count=0; sv=0;
						//jQuery.each(arr_check,function(idx,value){
						jQuery("#htmlTable3 > tbody > tr").each(function(){
							var data = jQuery("#htmlTable3").getRowData(this.id);
							data.FK=response;
							data.page=<?=$_REQUEST['page']?>;
							data.mode='asyc';
							if(act!='') data.action='edit';
							data.rows=rows;
							data.count=count;
							data.detail=true;
							data.sender='entri_penyetoran_pr';
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
						alert('1. '+response);
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
		objForm = document.getElementById('entri_penyetoran_pr');
		objForm.reset();
		jQuery('#proses').val('Simpan');
		jQuery('#batal').attr('disabled',true);
	});
	
	jQuery('#coba1').click(function(){
		var rows = jQuery('#htmlTable3').getGridParam('records');
		var arr_check = jQuery("#htmlTable3").getGridParam('selarrrow');
		
		tmp = {};

		/*jQuery.each(arr_check,function(idx,value){
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

		jQuery("#htmlTable3 > tbody > tr").each(function (){
			tmp = {};
			tmp[this.id] = jQuery("#htmlTable3").getRowData(this.id);
			alert(YAHOO.lang.dump(tmp[this.id]));
			/*$.post("request.php", tmp[this.id],
				function(data){
					alert("Data Loaded: " + data);
				}
			);*/
		});
	});		
	
});

function setDefaultForm(objForm){
	objForm.reset();
	jQuery("#htmlTable3").trigger("reloadGrid");
	setNomor(currentDate());
	document.getElementById('tgl_setor').value=currentDate();
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
			<div style='padding:5px;'>
				<fieldset>
				<legend>Form Penyetoran</legend>
					<div>
						<div class="form_master">
							<fieldset class="form_frame">
								<div><label>No. Penyetoran</label><input type="text" name="nomor_reg" id='nomor_reg' size='10' value="<?=sprintf('%05d',getNoPenyetoran())?>" readonly/></div>
								<div><label>Tgl. Penyetoran<b class="wajib">*</b></label><input type="text" name="tgl_setor" title='Tgl. Penetapan' id="tgl_setor" value='<?=date('d/m/Y')?>' onchange="" size="10"/></div>
								<div><label>Keterangan</label><textarea name='keterangan' id='keterangan'></textarea></div>
								<div><label>&nbsp;</b></label><input type='button' id='proses' class='btn' value='Simpan'></input></div>
							</fieldset>
							<fieldset class="form_frame">
								<div><label><span style='font-weight:bold;font-size:22px'>Total</span></label><span style="font-weight:bold;font-size:25px;color:rgb(24,245,24);background-color:black;text-align:center;padding-right:0;margin-right:0;">Rp.</span><input type="text" style="font-weight: bold; font-size:25px; color: rgb(24, 245, 24); background-color: black; text-align: right; padding-left:0;margin-left:0" readonly="true" value='0.00' size="13" class="inputbox" id="nominal_pajak" name="nominal_pajak"></div>
							</fieldset>							
						</div>
						<div class="footer_space">&nbsp;</div>
					</div>
					<div id='asb_simulasi_form'>
						<div style='padding:5px'>
							<table id="htmlTable3" class="scroll"></table>
							<div id="htmlPager3" class="scroll"></div>
						</div>
						<!--<a href="javascript:void(0)" id="coba1">Coba 1</a>-->
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
							<table id="htmlTable4" class="scroll"></table>
							<div id="htmlPager4" class="scroll"></div>
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