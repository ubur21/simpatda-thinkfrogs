<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_penerimaan_pr");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
$kabupaten = b_fetch('select pemda_kabupaten from info_pemda');
		
gcms_add_to_head($include);

?>
<script>

var lastsel,lastsel2,lastsel3;
var timeoutHnd; var flAuto = false; 

$().ready(function(){
	
	jQuery("#htmlTable").jqGrid(
	{
		 url:'request.php?mod=penetapan_pr&func=list&sender=pilih_kohir',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','No. Kohir','Tgl. Penetapan','Tgl. Jatuh Tempo','Nominal Penetapan'],
		 colModel :[
			{ name:'id' ,index:'id',search:false},
			{ name:'kohir',index:'kohir',width:80},
			{ name:'tgl' ,index:'tgl',width:120,align:'center',formatter:'date', sorttype:"date"},
			{ name:'setor' ,index:'setor',width:120,align:'center',formatter:'date', sorttype:"date"},
			{ name:'nominal' ,index:'nominal',width:150,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}},
		 ],
		pager: jQuery('#htmlPager'),
		height:150,
		width:600,
		rowNum:5,
		rowList:[10,15,20],
		rownumbers:true,
		shrinkToFit:false,
		sortname: 'nama',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'Daftar No. Kohir',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel2){ 
				jQuery('#id_kohir').val(id);
                jQuery.ajax({
					url:'request.php?page=<?=$_REQUEST['page']?>&sender=setFormOffice&id='+id
					,dataType:'json'
					,success:function(json){
						jQuery('input#no_kohir').val(json.no_kohir);
						jQuery('input#nominal_pajak').val(formatCurrency(json.nominal_pajak));
						jQuery('input#no_pokok').val(json.no_pokok);
						jQuery('input#id_npwp').val(json.id_npwp);
						jQuery('input#id_spt').val(json.id_spt);
						jQuery('input#nama_pemohon').val(json.nama_pemohon);
						jQuery('input#id_pemohon').val(json.id_pemohon);
						jQuery('#alamat').val(json.alamat);
						jQuery('input#kecamatan').val(json.kecamatan);
						jQuery('input#kelurahan').val(json.kelurahan);
					}
				});
				$('#dialog').dialog('close');
            }
        },
        gridComplete: function(){
			jQuery("#htmlTable").setGridWidth(550);
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
		 url:'request.php?mod=skpd&sender=pilih',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Kode Dinas','Nama Dinas'],
		 colModel :[
			{ name:'id' ,index:'id',search:false},
			{name:'kode',index:'kode',width:100},
			{ name:'nama' ,index:'nama',width:400}
		 ],
		pager: jQuery('#htmlPager2'),
		height:150,
		width:600,
		rowNum:5,
		rownumbers:true,
		rowList:[10,15,20],
		shrinkToFit:false,
		sortname: 'nama',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'Daftar SKPD ',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel2){ 
				var arr = jQuery("#htmlTable2").getRowData(id);
				jQuery('input#id_dinas').val(id);
				jQuery('input#nama_dinas').val(arr.nama);			
				$('#dialog2').dialog('close');
            }
        },
        gridComplete: function(){
			jQuery("#htmlTable2").setGridWidth(500);
            return true;
        }
    }).navGrid('#htmlPager2'
        ,{add:false,edit:false,del:false}
        ,{} // edit
        ,{height:120, width:300} // add
        ,{} // del
        ,{} // search        
    ).hideCol(['id']);
	
	jQuery("#htmlTable3").jqGrid(
	{
		 url:'request.php?page=<?=$_REQUEST['page']?>&sender=list_npwp',
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Nama','NPWPD/RD'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{name:'nama',index:'nama',width:250},
			{ name:'npwp' ,index:'npwp',width:150},
		 ],
		pager: jQuery('#htmlPager3'),
		height:150,
		width:600,
		rowNum:5,
		rownumbers:true,
		rowList:[10,15,20],
		shrinkToFit:false,
		sortname: 'nama',
		sortorder: 'asc',
		viewrecords: true,
		caption: 'Daftar NPWPD/RW ',
        ondblClickRow: function(id){ 
            if(id && id!==lastsel2){ 
                //lastsel2=id; 
				var arr = jQuery("#htmlTable3").getRowData(id);
				jQuery('input#id_npwp').val(id);
				jQuery('input#no_pokok').val(arr.npwp);
				kohir = jQuery('input#id_kohir').val();
				jQuery.ajax({
					url:'request.php?page=<?=$_REQUEST['page']?>&sender=setForm&id='+id+'&kohir='+kohir
					,dataType:'json'
					,success:function(json){
						jQuery('input#no_kohir').val(json.no_kohir);
						jQuery('input#nominal_pajak').val(formatCurrency(json.nominal_pajak));
						jQuery('input#no_pokok').val(json.no_pokok);
						jQuery('input#id_npwp').val(json.id_npwp);
						jQuery('input#id_spt').val(json.id_spt);
						jQuery('input#nama_pemohon').val(json.nama_pemohon);
						jQuery('input#id_pemohon').val(json.id_pemohon);
						jQuery('#alamat').val(json.alamat);
						jQuery('input#kecamatan').val(json.kecamatan);
						jQuery('input#kelurahan').val(json.kelurahan);
					}
				});
				$('#dialog').dialog('close');
            }
        },
        gridComplete: function(){
			jQuery("#htmlTable3").setGridWidth(450);
            return true;
        }
    }).navGrid('#htmlPager3'
        ,{add:false,edit:false,del:false}
        ,{} // edit
        ,{height:120, width:300} // add
        ,{} // del
        ,{} // search        
    ).hideCol(['id']);
	
	jQuery("#htmlTable4").jqGrid({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_office',
		editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_office&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','No. Penerimaan','Nominal','Tgl. Penerimaan','No. Kohir','Jenis Pendataan','Via Bayar','Dinas Terkait'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'no_bukti',index:'no_bukti',width:100},
			{name:'nominal',align:'right',index:'nominal',width:150,formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}},			
			{name:'tgl_bayar',index:'tgl_bayar',align:'center',width:100,formatter:'date', sorttype:"date"},
			{name:'no_kohir',index:'no_kohir',width:60},
			{name:'jenis',index:'jenis',width:170},
			{name:'keterangan',index:'keterangan',width:170},
			{name:'nama_skpd',index:'nama_skpd',width:170}
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
		caption: 'Data Penerimaan Pajak',
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
	
	$('#trigger_kohir').click(function(){
		$("#htmlTable").trigger("reloadGrid");
		$('#dialog').dialog('open');
	});
	
	$('#trigger_dinas').click(function(){
		$("#htmlTable2").trigger("reloadGrid");
		$('#dialog2').dialog('open');
	});
	
	$('#trigger_npw').click(function(){
		$("#htmlTable3").trigger("reloadGrid");
		$('#dialog3').dialog('open');
	});	
	
	$("#dialog").dialog({
		bgiframe: true, resizable: false, height:350, width:600, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() { $(this).dialog('close'); }
		}
	});

	$("#dialog2").dialog({
		bgiframe: true, resizable: false, height:350, width:550, modal: true, autoOpen: false,
		buttons: { 
			'Tutup': function(){ $(this).dialog('close'); }
		}
	});
	
	$("#dialog3").dialog({
		bgiframe: true, resizable: false, height:350, width:550, modal: true, autoOpen: false,
		buttons: { 
			'Tutup': function(){ $(this).dialog('close'); }
		}
	});		
	
	$('#tgl_penerimaan').datepicker({changeMonth: true, changeYear: true});

	$("#tabs").tabs();
	
	jQuery('#proses').click(function(){	
		objForm = document.getElementById('entri_penerimaan_pr');
		if(saveEntry(objForm)){
			jQuery(objForm).ajaxSubmit({
				success: function(response){
					act = document.getElementById('caction').value;
					count=0; sv=0;
					alert(response);
					setDefaultForm(objForm);
					if(act!=''){
						jQuery('#proses').val('Simpan');
						jQuery('#batal').attr('disabled',true);
					}						
				}	
			});
		}
	});
				
	jQuery('#batal').click(function(){	
		objForm = document.getElementById('entri_penerimaan_pr');
		objForm.reset();
		jQuery('#proses').val('Simpan');
		jQuery('#batal').attr('disabled',true);
	});
	
});

function setDefaultForm(objForm){
	objForm.reset();
	setNomor(currentDate());
	document.getElementById('tgl_penerimaan').value=currentDate();
	document.getElementById('kabupaten').value='<?=$kabupaten?>';
}

function setNomor(tgl){ 
	jQuery.ajax({
		url:'request.php?page=<?=$_REQUEST['page']?>&sender=set_nomor_office&tgl='+tgl,
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
	
	<div id="dialog3" title="Dialog">
		<div style='padding:5px;'>
			<div>
				<div style='padding:5px'>
					<table id="htmlTable3" class="scroll"></table>
					<div id="htmlPager3" class="scroll"></div>
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
				<legend>Form Penerimaan Pajak/Retribusi Office Assesment</legend>
					<div>
						<div class="form_master">
							<fieldset class="form_frame">
								<input type='hidden' name='jenis_pungutan' id='jenis_pungutan' value='OFFICE'>
								<!--<div><label>Duplikasi Nomor</label><input type='checkbox' name='cnomor' id='cnomor' checked /></div>-->
								<div><label>No. Penerimaan</label><input type="text" name="nomor_reg" id='nomor_reg' size='10' value="<?=sprintf('%05d',getNoPenerimaan('OFFICE'))?>" readonly/></div>
								<div><label>Tgl. Penerimaan <b class="wajib">*</b></label><input type="text" name="tgl_penerimaan" title='Tgl. Penerimaan' id="tgl_penerimaan" value='<?=date('d/m/Y')?>' onchange="" size="10"/></div>
								<!--<div><label>Thn. SPT</label><input type='text' name='thn_spt' id='thn_spt' size='3' value='<?=date('Y')?>'></div>-->
								<div>
									<label>No. Kohir <b class="wajib">*</b></label>
									<input type='text' name='no_kohir' id='no_kohir' title='No. Kohir' readonly >
									<input type='hidden' name='id_kohir' id='id_kohir'>
									<input type="button" id="trigger_kohir" size="2" value=" * ">
								</div>
								<div>
									<label>NPWP/RD</label>
									<input type='text' name='no_pokok' id='no_pokok' readonly >
									<input type='hidden' name='id_npwp' id='id_npwp'>
									<input type='hidden' name='id_spt' id='id_spt'>
									<!--<input type="button" id="trigger_npw" size="2" value="...">-->
								</div>								
								<div>
									<label>Kode Dinas <b class="wajib">*</b></label>
									<input type='text' name='nama_dinas' id='nama_dinas' title='Kode Dinas' readonly >
									<input type='hidden' name='id_dinas' id='id_dinas'>
									<input type="button" id="trigger_dinas" size="2" value=" * ">
								</div>
								<div>
									<label>Cara Penyetoran <b class="wajib">*</b></label>
									<select name='cpenyetoran' id='cpenyetoran' title='Cara Penyetoran'>
										<?=getViaPembayaran()?>	
									</select>
								</div>
								<div>
									<label>No. Bukti Bank</label>
									<input type='text' name='bank_no' id='bank_no'>
								</div>
								<div><label>Keterangan</label><textarea name='keterangan' id='keterangan'></textarea></div>
							</fieldset>
							<fieldset class="form_frame">
								<div><label><span style='font-weight:bold;font-size:22px'>Total</span></label><span style="font-weight:bold;font-size:25px;color:rgb(24,245,24);background-color:black;text-align:center;padding-right:0;margin-right:0;">Rp.</span><input type="text" style="font-weight: bold; font-size:25px; color: rgb(24, 245, 24); background-color: black; text-align: right; padding-left:0;margin-left:0" readonly="true" value='0.00' size="13" class="inputbox" id="nominal_pajak" name="nominal_pajak"></div>
								<div><label>Nama WP/WR</label><input type="text" name="nama_pemohon" id='nama_pemohon' value="" size="25" readonly />
								<input type='hidden' name='id_pemohon' id='id_pemohon'>
								</div>
								<div><label>Alamat</label><textarea name="alamat" id='alamat' col="4" row="3" readonly></textarea></div>
								<div><label>Kelurahan</label><input type="text" name="kelurahan" id='kelurahan' value="" size="25" readonly /></div>
								<div><label>Kecamatan</label><input type="text" name="kecamatan" id='kecamatan' value="" size="25" readonly /></div>
								<div><label>Kabupaten</label><input type="text" name="kabupaten" id='kabupaten' value="<?=$kabupaten?>" size="25" readonly /></div>
								<div><label><b class="wajib">*</b>&nbsp;Wajib Diisi</label>
								<input class="btn" type="button" name="proses" id='proses' value="Simpan">
								<input class="btn" id='batal' type="button" name="batal" value="Batal" disabled >
								</div>
								<!--<a href="javascript:void(0)" id="coba1">Coba 1</a>-->
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