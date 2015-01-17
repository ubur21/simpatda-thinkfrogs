
jQuery(document).ready(function(){ 

	jQuery("#satkerTable").jqGrid({
		url:root+'master/satker/get_daftar',
		datatype: 'json',
		mtype: 'POST',
		colNames:['ID', 'Induk', 'Kode', 'Nama'], 
		colModel:[ 
			{ name:'id', index:'id_satker',  width:20, search:false, hidden:true }, 
			{ name:'induk',
				index:'induk',
				hidden:true,
				editable:true,
				editrules: {edithidden:true},
				edittype:'select', 
				editoptions: {value:{<?=$list_satker?>}}
			},
			{
				name:'kode',
				index:'kode_satker', 
				width:20, 
				editable:true, 
				edittype:'text', 
				editoptions: {size:20, maxlength: 20},
				editrules: {required:true}
			}, 
			{
				name:'nama',
				index:'nama_satker', 
				width:100, 
				align:"left", 
				editable:true, 
				edittype:'text', 
				editoptions: {size:100, maxlength: 200},
				editrules: {required:true}
			}
		], 
		rowNum:10, 
		rowList:[10,20,30], 		
		pager: jQuery('#satkerPager'),
		viewrecords: true, 
		rownumbers: true, 
		sortorder: "asc", 
		width:600,
		height: 230,
		ondblClickRow: function(id){ 
			var rs = jQuery('#satkerTable').getRowData(id);
			jQuery('#id_dinas').val(id);
			jQuery('#kode_dinas').val(rs.kode);
			jQuery('#nama_dinas').val(rs.nama);
			jQuery('#satkerDialog').dialog('close');
		}
    }).navGrid('#satkerPager'
        ,{add:false,edit:false,del:false}
        ,{} // edit
        ,{} // add
        ,{} // del 
        ,{} // multipleSearch:true,		
    ).hideCol('id');
	
});
var id_trigger_kohir='';

jQuery(document).ready(function(){ 

	jQuery("#daftarTable").jqGrid({
		url: root+mod+'/daftar',
		datatype: 'json',
		mtype: 'POST',
		 colNames:['id','Nomor','Tgl Penerimaan','Sistem Pemungutan','Nominal','Dinas','NPWP','Nama WP/WR'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{ name:'nomor',index:'nomor',width:50,align:'right'},
			{ name:'tgl' ,index:'tgl',width:100,align:'center'},
			{ name:'sistem' ,index:'sistem',width:125},
			{ name:'nominal',index:'nominal',width:150,editable:true,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}},
			{ name:'dinas',index:'dinas',width:225},
			{ name:'npwp',index:'npwp',width:100,align:'center'},
			{ name:'pemohon',index:'pemohon',width:180},
			
		 ],
		pager: jQuery('#daftarPager'),
		height:150,
		width:900,
		rowNum:5,
		rowList:[10,15,20],
		//sortname: 'pemohon',
		sortorder: 'asc',
		rownumbers: true, 
		//caption: 'NPWP/RD',
		}).navGrid('#daftarPager'
			,{add:false,edit:false,del:true}
			,{} // edit
			,{} // add
			,{} // del
			,{} // search        
    ).hideCol(['id']);

});

jQuery(document).ready(function(){ 

	jQuery("#kohirTable").jqGrid({
		url: root+'penetapan_pr/pilih_kohir',
		datatype: 'json',
		mtype: 'POST',
		 colNames:['id','No. Kohir','Tgl Penetapan','Tgl Jatuh Tempo','Nominal'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{ name:'nomor',index:'nomor',width:90,align:'right'},
			{ name:'tgl_kohir' ,index:'nama',width:100,align:'center'},
			{ name:'tgl_tempo',index:'tdasar',width:100,align:'center'},
			{ name:'nominal',index:'persen',width:100,align:'right'},
		 ],
		pager: jQuery('#kohirPager'),
		height:150,
		width:525,
		rowNum:5,
		rowList:[10,15,20],
		//sortname: 'pemohon',
		sortorder: 'asc',
		rownumbers: true, 
		//caption: 'NPWP/RD',
		ondblClickRow: function(id){ 
			var rs = jQuery('#kohirTable').getRowData(id);
			jQuery('#id_kohir').val(id);
			jQuery('#no_kohir').val(rs.nomor);
			jQuery.ajax({			
				url:root+'penerimaan_pr/set_form_office_assesment/'+id
				,dataType:'json'
				,success:function(json){
					jQuery('input#no_kohir').val(json.no_kohir);
					jQuery('input#nominal_pajak').val(json.nominal_pajak)
					jQuery('input#total').val(json.nominal_pajak).formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:'Rp. ' });					
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
			
			jQuery('#kohirDialog').dialog('close');
		}
		}).navGrid('#kohirPager'
			,{add:false,edit:false,del:false}
			,{} // edit
			,{} // add
			,{} // del
			,{} // search        
    ).hideCol(['id']);
	
});

jQuery(document).ready(function(){ 

	jQuery("#sptTable").jqGrid({
		url: root+'spt/pilih_spt',
		datatype: 'json',
		mtype: 'POST',
		 colNames:['id','No. Pendataan','Tgl Pendataan','Nominal','npwp','pemohon','alamat','kelurahan','kecamatan','spt','inpwp','idpemohon'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{ name:'nomor',index:'nomor',width:90,align:'right'},
			{ name:'tgl' ,index:'tgl',width:100,align:'center'},
			{ name:'nominal',index:'nominal',width:100,align:'right'},
			{ name:'npwp',index:'npwp',hidden:true,search:false},
			{ name:'pemohon',index:'pemohon',hidden:true,search:false},
			{ name:'alamat',index:'alamat',hidden:true,search:false},
			{ name:'kelurahan',index:'kelurahan',hidden:true,search:false},
			{ name:'kecamatan',index:'kecamatan',hidden:true,search:false},
			{ name:'spt',index:'spt',hidden:true,search:false},
			{ name:'inpwp',index:'inpwp',hidden:true,search:false},
			{ name:'idpemohon',index:'idpemohon',hidden:true,search:false},
		 ],
		pager: jQuery('#sptPager'),
		height:150,
		width:525,
		rowNum:5,
		rowList:[10,15,20],
		//sortname: 'pemohon',
		sortorder: 'asc',
		rownumbers: true, 
		//caption: 'NPWP/RD',
		ondblClickRow: function(id){ 
			var rs = jQuery('#sptTable').getRowData(id);
			jQuery('input#nominal_pajak').val(rs.nominal);
			jQuery('input#total').val(rs.nominal).formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:'Rp. '});			
			jQuery('#id_spt').val(id);
			jQuery('#no_spt').val(rs.nomor);
			jQuery('#no_pokok').val(rs.npwp);
			jQuery('#id_npwp').val(rs.inpwp);
			jQuery('#nama_pemohon').val(rs.pemohon);
			jQuery('#id_pemohon').val(rs.idpemohon);
			jQuery('#alamat').val(rs.alamat);
			jQuery('#kecamatan').val(rs.kecamatan);
			jQuery('#kelurahan').val(rs.kelurahan);
			jQuery('#sptDialog').dialog('close');
		}
		}).navGrid('#sptPager'
			,{add:false,edit:false,del:false}
			,{} // edit
			,{} // add
			,{} // del
			,{} // search        
    ).hideCol(['id']);

});

jQuery(document).ready(function(){ 
	
	var v = jQuery("#form").validate();	
		
	var options = {
		dataType:'text',
		beforeSubmit: function() {
			if(jQuery('#form').validate().form()) jQuery('#lock_screen').html('<div class="mask_layer"><div>Sedang proses...<br><img src="http://'+location.host+'/simpatda_ci/themes/brown/images/ajax-loader.gif"></div></div>');
			return jQuery('#form').validate().form() 
		},
		success: showResponse
	};
	
	jQuery('')

	jQuery("#form").ajaxForm(options);

	function showResponse(responseText, statusText, xhr, form)
	{
		jQuery('#lock_screen').html('');
		jQuery(".result_msg").html(responseText).fadeIn("slow");
		var t = setTimeout('jQuery(".result_msg").fadeOut("slow")',3000);
		v.resetForm();
	}

	jQuery('#btn_save').click(function(){ jQuery("#form").submit(); });

	jQuery('#btn_cancel').click(function(){ 
		v.resetForm();
	});
	
	jQuery('#btn_print').click(function(){
		var id = jQuery("#daftarTable").getGridParam('selrow');
		if(id){
			fastReportStart('Bukti Pembayaran Pajak-Retribusi', 'rpt_pembayaran', 'pdf', 'id='+id, 1);
		}
		else alert('Pilih Dari Daftar data yang akan dicetak');
	});
		
	jQuery('#periode_awal').datepicker({changeMonth: true, changeYear: true});
	jQuery('#periode_akhir').datepicker({changeMonth: true, changeYear: true});
	jQuery('#tgl_cetak').datepicker({changeMonth: true, changeYear: true});
	
	jQuery("#kohirDialog").dialog({
		bgiframe: true,	resizable: false, height:350, width:625, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				jQuery(this).dialog('close');
			}
		}
	});
	
	jQuery("#sptDialog").dialog({
		bgiframe: true,	resizable: false, height:350, width:625, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				jQuery(this).dialog('close');
			}
		}
	});
	
	jQuery("#satkerDialog").dialog({
		bgiframe: true,	resizable: false, height:400, width:625, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				jQuery(this).dialog('close');
			}
		}
	});	
		
	jQuery('#trigger_kohir').click(function(){
		jQuery('#kohirTable').trigger('reloadGrid');
		jQuery('#kohirDialog').dialog('open');
	});	
	
	jQuery('#trigger_spt').click(function(){
		jQuery('#sptTable').trigger('reloadGrid');
		jQuery('#sptDialog').dialog('open');
	});		
	
	jQuery('#trigger_dinas').click(function(){
		jQuery('#satkerTable').trigger('reloadGrid');
		jQuery('#satkerDialog').dialog('open');
	});		

});