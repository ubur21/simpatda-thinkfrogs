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
			{ name:'tdasar',index:'tdasar',width:100,align:'right'},
			{ name:'persen',index:'persen',width:100,align:'right'},
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
			hitungPajak();
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

	jQuery("#npwprdTable").jqGrid({
		url: root+'/pendaftaran/seeknpw/RETRIBUSI/11',
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
			jQuery('#idspt').val(rs.idspt);
			jQuery('#periode_spt').val(rs.periode_spt);
			jQuery('#tgl_spt').val(rs.tgl_spt);
			jQuery('#no_spt').val(rs.no_spt);
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

	function hitungPajak()
	{
		var ijumlah = jQuery('#jumlah').val()!='' ? removeCommas(jQuery('#jumlah').val()) : 1;
		var idasar  = removeCommas(jQuery('#dasar_tarif').val());
		var total   = ijumlah*idasar;
		jQuery('#total').val(total).formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:'Rp. ' });
		jQuery('#dasar_pengenaan').val(total).formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:''});
		
	}

jQuery(document).ready(function(){ 
		
	jQuery("#daftarTable").jqGrid({
		url: root+mod+'/daftar',		
		editurl:root+mod+'/daftar',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','Pemungutan','JP','Nomor','Periode','NPWPD/NPWRD','Nama WP/WR','Tgl Penetapan','No. Kohir','Tgl. Setor'
		,'TglProses','TglEntry','npwpf','memo','periodespt','tglspt','nospt','idspt','pendaftaran','alamat','kecamatan','kelurahan','pawal','pakhir',
		'krekening','nrekening','irekening','jumlah','dtarif','dpengenaan','nominal'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'pemungutan',index:'pemungutan',width:150},
			{name:'jenis_pungutan',index:'jenis_pungutan',hidden:true,search:false},
			{name:'pendataan_no',index:'pendataan_no',width:90,align:'center'},
			{name:'periode',index:'periode',width:90,align:'center'},
			{name:'npwprd',index:'npwprd',width:150,align:'center'},
			{name:'pemohon',index:'pemohon' },
			{name:'tgl_penetapan',index:'tgl_penetapan',align:'center',width:120 },
			{name:'kohir',index:'kohir',align:'center'},
			{name:'tgl_setor',index:'tgl_setor',align:'center',width:120},
			{name:'TglProses',index:'TglProses',hidden:true,search:false},
			{name:'TglEntry',index:'TglEntry',hidden:true,search:false},
			{name:'npwpf',index:'npwpf',hidden:true,search:false},
			{name:'memo',index:'memo',hidden:true,search:false},
			{name:'periodespt',index:'periodespt',hidden:true,search:false},
			{name:'tglspt',index:'tglspt',hidden:true,search:false},
			{name:'nospt',index:'nospt',hidden:true,search:false},
			{name:'idspt',index:'idspt',hidden:true,search:false},
			{name:'pendaftaran_id',index:'pendaftaran_id',hidden:true,search:false},
			{name:'alamat',index:'alamat',hidden:true,search:false},
			{name:'kecamatan',index:'kecamatan',hidden:true,search:false},
			{name:'kelurahan',index:'kelurahan',hidden:true,search:false},
			{name:'pawal',index:'pawal',hidden:true,search:false},
			{name:'pakhir',index:'pakhir',hidden:true,search:false},
			{name:'krekening',index:'krekening',hidden:true,search:false},
			{name:'nrekening',index:'nrekening',hidden:true,search:false},
			{name:'irekening',index:'irekening',hidden:true,search:false},
			{name:'jumlah',index:'jumlah',hidden:true,search:false},
			{name:'dtarif',index:'dtarif',hidden:true,search:false},
			{name:'dpengenaan',index:'dpengenaan',hidden:true,search:false},
			{name:'total',index:'total',hidden:true,search:false},
		],
		pager: jQuery('#daftarPager'),
		height:300,
		width:900,
		rowNum:5,
		rowList:[10,15,20],
		//sortname: 'pendataan_no',
		sortorder: 'asc',
		multiselect:true,
		multiboxonly: true,
		viewrecords: true,
		//caption: '&nbsp;',
		rownumbers: true, 
        ondblClickRow: function(id){ 
			var data = jQuery('#daftarTable').getRowData(id);
			
			jQuery.get(root+'penetapan_pr/ed_spt/'+id+'_'+data.jenis_pungutan,function(dt){

				if(dt<1)
				{
					jQuery('#pendaftaran_id').val(data.pendaftaran_id);
					jQuery('#action').val('edit');
					jQuery('#idmasters').val(id);
					jQuery('#tgl_proses').val(data.TglProses);
					jQuery('#tgl_entry').val(data.TglEntry);
					jQuery('#npwprd').val(data.npwpf);
					jQuery('#memo').val(data.memo);
					jQuery('#periode_spt').val(data.periodespt);
					jQuery('#no_spt').val(data.nospt);
					jQuery('#tgl_spt').val(data.tglspt);
					jQuery('#idspt').val(data.idspt);
					jQuery('#pemohon').val(data.pemohon);
					jQuery('#alamat').val(data.alamat);
					jQuery('#kecamatan').val(data.kecamatan);
					jQuery('#kelurahan').val(data.kelurahan);
					jQuery('#jenis_pungutan').val(data.jenis_pungutan);
					jQuery('#periode_awal').val(data.pawal);
					jQuery('#periode_akhir').val(data.pakhir);
					jQuery('#periode_akhir').val(data.pakhir);
					jQuery('#kode_rekening').val(data.krekening);
					jQuery('#nama_rekening').val(data.nrekening);
					jQuery('#idrekening').val(data.irekening);
					jQuery('#jumlah').val(data.jumlah).formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:''});
					jQuery('#dasar_tarif').val(data.dtarif).formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:''});
					jQuery('#dasar_pengenaan').val(data.dpengenaan).formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:''});
					jQuery('#total').val(data.total).formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:'Rp. ' });				
					
					jQuery("#tabs").tabs('select', 0);
								
				}else{
					alert('Maaf, karena sudah ditetapkan/disetorkan data tidak bisa diedit');
				}
				
			});				
								
			
							
        }
    }).navGrid('#daftarPager'
        ,{add:false,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
    ).hideCol('id');  //end of on ready event	*/
	
	jQuery('input#total').formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:'Rp. ' });
	jQuery('input#dasar_pengenaan').formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:''});
	
	jQuery("#jumlah").keyup(function(){ jQuery(this).value=cnumber(this); });
	jQuery("#jumlah").focus(function(){ jQuery(this).value=focus_uang(this); });
	jQuery("#jumlah").blur(function(){ jQuery(this).value=blur_uang(this);hitungPajak(); });
	
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
		v.resetForm();
		//setNomor();
	}

	jQuery('#btn_save').click(function(){ jQuery("#form").submit(); });

	jQuery('#btn_cancel').click(function(){ 
		v.resetForm();
	});
	
	jQuery('#tgl_proses').datepicker({changeMonth: true, changeYear: true});
	jQuery('#tgl_entry').datepicker({changeMonth: true, changeYear: true});
	jQuery('#periode_awal').datepicker({changeMonth: true, changeYear: true});
	jQuery('#periode_akhir').datepicker({changeMonth: true, changeYear: true});
	
	jQuery("#npwprdDialog").dialog({
		bgiframe: true,	resizable: false, height:350, width:525, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				jQuery(this).dialog('close');
			}
		}
	});
	
	jQuery("#rekeningDialog").dialog({
		bgiframe: true,	resizable: false, height:350, width:700, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				jQuery(this).dialog('close');
			}
		}
	});	
	
	jQuery('#trigger_npw').click(function(){
		var x = jQuery('#wp_wr_jenis').val();
		jQuery("#npwprdTable").setGridParam({url:root+'/pendaftaran/seeknpw/'+x+'/11', datatype:'json'}).trigger("reloadGrid"); 	
		jQuery('#npwprdDialog').dialog('open');
	});
	
	jQuery('#trigger_rekening').click(function(){
		jQuery("#rekeningTable").setGridParam({url:root+'/master/rekening/get_rekening', datatype:'json'}).trigger("reloadGrid");
		jQuery('#rekeningDialog').dialog('open');
	});	

});