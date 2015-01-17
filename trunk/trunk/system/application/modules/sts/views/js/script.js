jQuery(document).ready(function(){

	jQuery("#dataTable").jqGrid({
		url: root+mod+'/grid_form',
		editurl: root+mod+'/grid_form',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','Kode Rekening','Nama Rekening','Thn Penerimaan','Nominal'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'kode',index:'kode',width:150,editable:true,align:'center'},
			{name:'nama',index:'nama',width:225,editable:true,align:'center'},
			{name:'tahun',index:'tahun',width:100,editable:true,align:'center'},
			{name:'nominal',index:'nominal',width:225,editable:true,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}}
		],
		pager: jQuery('#dataPager'),
		height:150,
		width:750,
		rowNum:5,
		rownumbers:true,
		rowList:[10,15,20],
		//sortname: 'pemohon',
		sortorder: 'asc',
		rownumbers: true,
		multiselect:true,
		multiboxonly: true,		
		//caption: 'NPWP/RD',
		}).navGrid('#dataPager'
			,{add:false,edit:false,del:true}
			,{} // edit
			,{} // add
			,{} // del
			,{} // search        
    ).hideCol(['id']);  /*end of on ready event */	
	
	jQuery("#dataTable").navButtonAdd(
		"#dataPager",
		{
			caption:"Tambah",title:"Tambah", 
			onClickButton:function(){
				var counter=0; var str_id='';
				jQuery("#dataTable > tbody > tr").each(function(){
					str_id+=this.id+'_';
					counter++;
				});
				if(!counter){
					jQuery("#pilihTable").setGridParam({url:root+'penerimaan_pr/sts'}).trigger('reloadGrid');
				}else{
					jQuery("#pilihTable").setGridParam({url:root+'penerimaan_pr/sts/'+str_id}).trigger("reloadGrid");
				}
				jQuery("#pilihTable").setGridParam({url:root+'penerimaan_pr/sts'});
				jQuery('#pilihDialog').dialog('open');
			} 
		}
	);		

	jQuery("#pilihDialog").dialog({
		bgiframe: true, resizable: false, height:350, width:800, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() { jQuery(this).dialog('close'); },
			'Tambah': function() {				
				var str_id=''; var nom=0;
				var arr_check = jQuery("#pilihTable").getGridParam('selarrrow');
				jQuery.each(arr_check,function(idx,value){
					var data = jQuery("#pilihTable").getRowData(value);
					var su = jQuery("#dataTable").addRowData(data.id,data);
					//alert(new_val+'#'+data.nominal);
				});
				//alert('F'+new_val);
				jQuery("#dataTable > tbody > tr").each(function(){
					str_id+=this.id+'_';
					var data = jQuery("#dataTable").getRowData(this.id);
					nom+=Number(data.nominal);
				});
				jQuery("#pilihTable").setGridParam({url:root+'penerimaan_pr/sts/'+str_id}).trigger("reloadGrid");
				jQuery("#pilihTable").setGridParam({url:root+'penerimaan_pr/sts'});
				jQuery('input#total').val(nom).formatCurrency({decimalSymbol:'.',digitGroupSymbol:',',roundToDecimalPlace:'2',symbol:'Rp. ' });
				jQuery('input#nominal_pajak').val(nom);
			}			
		}
	});
	
	jQuery("#pilihTable").jqGrid({
		url: root+'penerimaan_pr/sts',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','Kode Rekening','Nama Rekening','Thn Penerimaan','Nominal'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'kode',index:'kode',width:150,editable:true,align:'center'},
			{name:'nama',index:'nama',width:225,editable:true,align:'center'},
			{name:'tahun',index:'tahun',width:100,editable:true,align:'center'},
			{name:'nominal',index:'nominal',width:225,editable:true,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}}
		],
		pager: jQuery('#pilihPager'),
		height:150,
		width:750,
		rowNum:5,
		rownumbers:true,
		rowList:[10,15,20],
		//sortname: 'pemohon',
		sortorder: 'asc',
		rownumbers: true,
		multiselect:true,
		multiboxonly: true,		
		//caption: 'NPWP/RD',
		}).navGrid('#pilihPager'
			,{add:false,edit:false,del:false}
			,{} // edit
			,{} // add
			,{} // del
			,{} // search        
    ).hideCol(['id']);  /*end of on ready event */		

	/*jQuery("#rekeningTable").jqGrid({
		url: 'master/rekening/get_rekening',
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

	/*jQuery("#npwprdTable").jqGrid({
		url: 'pendaftaran/seeknpw/RETRIBUSI',
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
		sortname: 'pemohon',
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
		
	/*jQuery("#daftarTable").jqGrid({
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
		sortname: 'pendataan_no',
		sortorder: 'asc',
		multiselect:true,
		multiboxonly: true,
		viewrecords: true,
		//caption: '&nbsp;',
		rownumbers: true, 
        ondblClickRow: function(id){ 
			var data = jQuery('#daftarTable').getRowData(id);
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
		jQuery('#dataTable').trigger("reloadGrid");
		var t = setTimeout('jQuery(".result_msg").fadeOut("slow")',3000);
		v.resetForm(); jQuery('#extra_element_form').html('');
		
	}

	jQuery('#btn_save').click(function(){ 

		var idrows = jQuery('#dataTable').getGridParam('selarrrow'); jQuery('#extra_element_form').html('');
		
		if(idrows!=''){
			jQuery.each(idrows,function(idx,nilai){
				var cob = document.createElement('input');
				cob.type="hidden"; cob.name="idrows[]"; cob.value=nilai;
				document.getElementById("extra_element_form").appendChild(cob);	
				
				var data = jQuery('#dataTable').getRowData(nilai);
				
				var cob = document.createElement('input');
				cob.type="hidden"; cob.name="nominal_rek[]"; cob.value=data.nominal;
				document.getElementById("extra_element_form").appendChild(cob);
			});
			jQuery("#form").submit(); 
		}else{
			jQuery(".result_msg").html('Pilih Rekening yang akan disetorkan !').fadeIn("slow");
			var t = setTimeout('jQuery(".result_msg").fadeOut("slow")',3000);
		}
	});

	jQuery('#btn_cancel').click(function(){ 
		v.resetForm(); jQuery('#extra_element_form').html('');
		jQuery('#dataTable').trigger('reloadGrid');
	});
		
	jQuery('#tgl_setor').datepicker({changeMonth: true, changeYear: true});

		
});

