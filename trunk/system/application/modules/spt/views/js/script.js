jQuery(document).ready(function(){ 

	jQuery("#npwprdTable").jqGrid({
		url: root+'/pendaftaran/seeknpw/PAJAK',
		datatype: 'json',
		mtype: 'POST',
		 colNames:['id','Pemohon','NPWPD/RD','Jenis Usaha','Alamat','Desa','Kecamatan','Periode','TglSPT','NoSPT','idspt'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{name:'pemohon',index:'pemohon',width:200},
			{ name:'npwp' ,index:'npwp',width:80},
			{ name:'jenis_usaha' ,index:'jenis_usaha',width:200},
			{ name:'alamat',index:'alamat',hidden:true ,search:false},
			{ name:'desa',index:'desa',hidden:true ,search:false},
			{ name:'kecamatan',index:'kecamatan',hidden:true ,search:false},
			{ name:'periode_spt',index:'periode_spt',hidden:true ,search:false},
			{ name:'tgl_spt',index:'tgl_spt',hidden:true ,search:false},
			{ name:'no_spt',index:'no_spt',hidden:true ,search:false},
			{ name:'idspt',index:'idspt',hidden:true ,search:false}
		 ],
		pager: jQuery('#npwprdPager'),
		height:150,
		width:500,
		rowNum:5,
		rowList:[10,15,20],
		//sortname: 'pemohon',
		sortorder: 'asc',
		rownumbers: true, 
		//caption: 'NPWP/RD',
		ondblClickRow: function(id){ 
			jQuery('#pendaftaran_id').val(id);
			var rs = jQuery('#npwprdTable').getRowData(id);
			jQuery('#npwprd').val(rs.npwp);
			jQuery('#pemohon').val(rs.pemohon);
			jQuery('#alamat').val(rs.alamat);
			jQuery('#kelurahan').val(rs.desa);
			jQuery('#kecamatan').val(rs.desa);
			jQuery('#npwprdDialog').dialog('close');
		}
		}).navGrid('#npwprdPager'
			,{add:false,edit:false,del:false}
			,{} // edit
			,{height:120, width:300} // add
			,{} // del
			,{} // search        
    ).hideCol(['id']);  /*end of on ready event */
	
});

jQuery(document).ready(function(){ 

	jQuery("#rekeningTable").jqGrid({
		url: root+'/master/rekening/get_rekening',
		datatype: 'json',
		mtype: 'POST',
		 colNames:['id','Kode Rekening','Nama Rekening','Tarif Dasar','% Tarif'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{ name:'kode',index:'kode',width:100},
			{ name:'nama' ,index:'nama',width:250},
			{ name:'tdasar',index:'tdasar',width:100,align:'right',hidden:true,search:false},
			{ name:'persen',index:'persen',width:100,align:'right',hidden:true,search:false},
		 ],
		pager: jQuery('#rekeningPager'),
		height:150,
		width:650,
		rowNum:5,
		rowList:[10,15,20],
		//sortname: 'pemohon',
		sortorder: 'asc',
		rownumbers: true, 
		//caption: 'NPWP/RD',
		ondblClickRow: function(id){ 
			var rs = jQuery('#rekeningTable').getRowData(id);
			jQuery('#idrekening').val(id);
			jQuery('#kode_rekening').val(rs.kode);
			jQuery('#nama_rekening').val(rs.nama);
			jQuery('#dasar_tarif').val(rs.tdasar);
			jQuery('#rekeningDialog').dialog('close');
		}
		}).navGrid('#rekeningPager'
			,{add:false,edit:false,del:false}
			,{} // edit
			,{height:120, width:300} // add
			,{} // del
			,{} // search        
    ).hideCol(['id']);  /*end of on ready event */	
	
});

jQuery(document).ready(function(){ 
	
	jQuery("#daftarTable").jqGrid({
		url: root+mod+'/daftar',		
		editurl:root+mod+'/daftar',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','NO. SPT','Tgl. SPT','Sistem Pemungutan','NO. NPWPD/RW','NAMA NPWPD/RW','','','','','','','','','','',''],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'spt_no',index:'spt_no',width:80 },			
			{name:'tgl',index:'tgl',width:80 },			
			{name:'pemungutan',index:'pemungutan',width:110},			 
			{name:'npw_no',index:'npw_no',width:100},			 
			{name:'npw_nama',index:'npw_nama',width:200 },
			{name:'jenis',index:'jenis',hidden:true },
			{name:'alamat',index:'alamat',hidden:true },
			{name:'kecamatan',index:'kecamatan',hidden:true },
			{name:'kelurahan',index:'kelurahan',hidden:true },
			{name:'tgl_kembali',index:'tgl_kembali',hidden:true },
			{name:'kode_rekening',index:'kode_rekening',hidden:true },
			{name:'nama_rekening',index:'nama_rekening',hidden:true },
			{name:'penerima_nama',index:'penerima_nama',hidden:true },
			{name:'penerima_alamat',index:'penerima_alamat',hidden:true },
			{name:'memo',index:'memo',hidden:true },
			{name:'pendaftaran_id',index:'pendaftaran_id',hidden:true },
		],
		pager: jQuery('#daftarPager'),
		height:150,
		width:700,
		rowNum:5,
		rowList:[5,10,15],
		//sortname: 'spt_no',
		sortorder: 'asc',
		multiselect:true,
		multiboxonly: true,
		viewrecords: true,
		caption: '&nbsp;',
        ondblClickRow: function(id){ 
			var data = jQuery('#daftarTable').getRowData(id);
			jQuery('#pendaftaran_id').val(data.pendaftaran_id);
			jQuery('#action').val('edit');
			jQuery('#idmasters').val(id);
			
			jQuery('#pendaftaran_id').val(data.pemohon);
			jQuery('#nomor').val(data.spt_no);
			jQuery('#jenis_pungutan').val(data.jenis);
			jQuery('#npwprd').val(data.npw_no);
			jQuery('#pemohon').val(data.npw_nama);
			jQuery('#alamat').val(data.alamat);
			jQuery('#kelurahan').val(data.kelurahan);
			jQuery('#kecamatan').val(data.kecamatan);
			
			jQuery('#kode_rekening').val(data.kode_rekening);
			jQuery('#nama_rekening').val(data.nama_rekening);
			
			jQuery('#tgl_spt').val(data.tgl);
			jQuery('#tgl_kembali').val(data.tgl_kembali);
			
			jQuery('#memo').html('').html(data.memo);
			
			jQuery('#nama_penerima').val(data.penerima_nama);
			jQuery('#alamat_penerima').val(data.penerima_nama);			
			
			jQuery("#tabs").tabs('select', 0);
							
        }
    }).navGrid('#daftarPager'
        ,{add:false,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
    ).hideCol('id');  //end of on ready event	

	var v = jQuery("#form").validate();	
		
	var options = {
		dataType:'text',
		beforeSubmit: function() {
			if(jQuery('#form').validate().form()) jQuery('#lock_screen').html('<div class="mask_layer"><div>Sedang proses...<br><img src="http://'+location.host+'/simpatda_ci/themes/brown/images/ajax-loader.gif"></div></div>');
			return jQuery('#form').validate().form() 
		},
		success: showResponse
	};

	jQuery("#form").ajaxForm(options);

	function showResponse(responseText, statusText, xhr, form)
	{
		jQuery('#lock_screen').html('');
		jQuery(".result_msg").html(responseText).fadeIn("slow");
		var t = setTimeout('jQuery(".result_msg").fadeOut("slow")',3000);
		v.resetForm();setNomor();
	}

	jQuery('#btn_save').click(function(){ jQuery("#form").submit(); });

	jQuery('#btn_cancel').click(function(){ 
		v.resetForm();
	});
	
	jQuery('#tgl_spt').datepicker({changeMonth: true, changeYear: true});
	jQuery('#tgl_kembali').datepicker({changeMonth: true, changeYear: true});
	
	jQuery("#npwprdDialog").dialog({
		bgiframe: true,	resizable: false, height:350, width:525, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				jQuery(this).dialog('close');
			}
		}
	});
	
	jQuery("#rekeningDialog").dialog({
		bgiframe: true,	resizable: false, height:350, width:525, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				jQuery(this).dialog('close');
			}
		}
	});	
	
	jQuery('#trigger_npw').click(function(){
		var x = jQuery('#wp_wr_jenis').val();
		jQuery("#npwprdTable").setGridParam({url:root+'/pendaftaran/seeknpw/'+x, datatype:'json'}).trigger("reloadGrid"); 	
		jQuery('#npwprdDialog').dialog('open');
	});
	
	jQuery('#trigger_rekening').click(function(){
		jQuery("#rekeningTable").setGridParam({url:root+'/master/rekening/get_rekening', datatype:'json'}).trigger("reloadGrid");
		jQuery('#rekeningDialog').dialog('open');
	});	

});

function setNomor()
{
	jQuery.get('spt/getno',function(result){
		jQuery('#nomor').val(result);
	});
}

setNomor();