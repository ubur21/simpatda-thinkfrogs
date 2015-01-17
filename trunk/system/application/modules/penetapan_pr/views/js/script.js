jQuery(document).ready(function(){

	jQuery("#dataTable").jqGrid({
		url: root+mod+'/sptprd',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','No.Pendataan','Tgl. Pendataan','NPWPD/NPWRD','Nama WP/WR','Jenis Pendataan','Jenis Pemungutan','Jenis Pendaftaran','No. SPT','Tgl. SPT','Nominal'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'pendataan_no',index:'pendataan_no',width:100,editable:true},
			{name:'tgl_entry',index:'tgl_entry',width:90,editable:true,align:'center',formatter:'date', sorttype:"date",formatoptions:{newformat:'d/m/Y'}},
			{name:'npwp',index:'npwp',width:100,editable:true,align:'center'},
			{name:'nama',index:'nama',width:220,editable:true},
			{name:'jenis_pendataan',index:'jenis_pendataan',width:150,editable:true},			 
			{name:'jenis_pungutan',index:'jenis_pungutan',width:110,editable:true},			
			{name:'jenis_pendaftaran',index:'jenis_pendaftaran',width:110,editable:true},
			{name:'spt_no',index:'spt_no',width:100,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'spt_tgl',index:'spt_tgl',width:90,editable:true,hidden:true,editrules:{edithidden:true}},
			{name:'nominal',index:'nominal',width:180,editable:true,align:'right',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2,prefix:"Rp. "}}
		],
		pager: jQuery('#dataPager'),
		height:150,
		rowNum:5,
		rownumbers:true,
		rowList:[10,15,20],
		//sortname: 'pemohon',
		sortorder: 'asc',
		viewrecords: true,
		rownumbers: true,
		multiselect:true,
		multiboxonly: true,		
		//caption: 'NPWP/RD',
		ondblClickRow: function(id){ 
			var rs = jQuery('#dataTable').getRowData(id);
			jQuery('#idrekening').val(id);
			jQuery('#kode_rekening').val(rs.kode);
			jQuery('#nama_rekening').val(rs.nama);
			jQuery('#dasar_tarif').val(rs.tdasar);
			jQuery('#rekeningDialog').dialog('close');
			hitungPajak();
		}
		}).navGrid('#dataPager'
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
	jQuery("#daftarTable").jqGrid({
		url: root+mod+'/daftar',		
		editurl:root+mod+'/daftar',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','Tgl Penetapan','No. Kohir','Nominal','No. Pendataan','Tgl Pendataan','Tgl Setor'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{name:'tgl_penetapan',index:'tgl_penetapan',align:'center',width:90},
			{name:'kohir',index:'kohir',align:'center'},
			{name:'nominal',index:'nominal',align:'right'},			
			{name:'pendataan_no',index:'pendataan_no',width:90,align:'center'},
			{name:'periode',index:'periode',width:90,align:'center'},			
			{name:'tgl_setor',index:'tgl_setor',align:'center',width:120}
		],
		pager: jQuery('#daftarPager'),
		height:300,
		width:900,
		rowNum:10,
		rowList:[10,15,20],
		//sortname: 'pendataan_no',
		sortorder: 'asc',
		viewrecords:true,
		multiselect:true,
		multiboxonly: true,
		viewrecords: true,
		//caption: '&nbsp;',
		rownumbers: true, 
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

		var idrows = jQuery('#dataTable').getGridParam('selarrrow');
		
		if(idrows!=''){
			jQuery.each(idrows,function(idx,nilai){
				var cob = document.createElement('input');
				cob.type="text"; cob.name="idrows[]"; cob.value=nilai;
				document.getElementById("extra_element_form").appendChild(cob);	
			});
			jQuery("#form").submit(); 
		}else{
			jQuery(".result_msg").html('Pilih SPTPD/RD yang akan ditetapkan !').fadeIn("slow");
			var t = setTimeout('jQuery(".result_msg").fadeOut("slow")',3000);
		}
	});

	jQuery('#btn_cancel').click(function(){ 
		v.resetForm(); jQuery('#extra_element_form').html('');
	});
	
	jQuery('#tgl_penetapan').change(function(){
		setTglSetor(this.value);		
	});
	
	jQuery('#tgl_penetapan').datepicker({changeMonth: true, changeYear: true});
	jQuery('#tgl_setor').datepicker({changeMonth: true, changeYear: true});
	jQuery('#periode_awal').datepicker({changeMonth: true, changeYear: true});
	jQuery('#periode_akhir').datepicker({changeMonth: true, changeYear: true});
	
	function setTglSetor(tgl){
		data = {}; data.tanggal = tgl;
		jQuery.post(root+mod+'/get_expired/',data,function(result){
			jQuery('#tgl_setor').val(result);
		});
	}
		
});

