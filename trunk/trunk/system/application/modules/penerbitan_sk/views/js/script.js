
jQuery(document).ready(function(){ 

	/*jQuery("#rekeningTable").jqGrid({
		url: root+'master/rekening/get_rekening',
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
var id_trigger_kohir='';

jQuery(document).ready(function(){ 

	jQuery("#kohirTable").jqGrid({
		url: root+'penetapan_pr/daftar',
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
			if(id_trigger_kohir=='trigger_kohir1') jQuery('#kohir_awal').val(rs.nomor);
			else jQuery('#kohir_akhir').val(rs.nomor);
			
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
		v.resetForm();setNomor();
	}

	jQuery('#btn_save').click(function(){ jQuery("#form").submit(); });

	jQuery('#btn_cancel').click(function(){ 
		v.resetForm();
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
	
	jQuery("#rekeningDialog").dialog({
		bgiframe: true,	resizable: false, height:350, width:525, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() {
				jQuery(this).dialog('close');
			}
		}
	});	
		
	jQuery('#trigger_kohir1,#trigger_kohir2').click(function(){
		//jQuery("#kohirTable").setGridParam({url:'master/rekening/get_rekening', datatype:'json'}).trigger("reloadGrid");
		id_trigger_kohir = this.id;
		jQuery('#kohirDialog').dialog('open');
	});	

});