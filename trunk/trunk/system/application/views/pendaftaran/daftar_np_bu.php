<script type="text/javascript"> 
var lastsel;
	
jQuery(document).ready(function(){
	
	jQuery("#htmlTable").jqGrid({	
		url:'<?php echo site_url('np_bu/daftar')?>',
		editurl:'<?php echo site_url('np_bu/daftar')?>',
		datatype: 'json',
		mtype: 'POST',												
		colNames:['id',"Tanggal","No. Pendaftaran","NPWP","Jenis Pendaftaran","Pemohon","Jenis Usaha"],
		colModel :[
			{ name:'id', index:'id',width:20  ,search:false },
			{ name:'tgl',index:'tgl',width:100},
			{ name:'nodaftar',index:'nodaftar',width:100},
			{ name:'npwp',index:'npwp',width:100},
			{ name:'jsdaftar',index:'jsdaftar',width:100},
			{ name:'pemohon',index:'pemohon',width:200},
			{ name:'jsusaha',index:'jsusaha',width:250}
		],
		pager: jQuery('#htmlPager'),
		height:300,
		rownumbers: true, 
		rowNum:10,
		rowList:[5,10,20,30],
		shrinkToFit:false,
		//sortname: 'nama',
		sortorder: 'asc',
		multiselect:true,
		multiboxonly: true,
		altRows:true,
		viewrecords: true,
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
				//jQuery("#grid_id").getGridParam('selarrrow'); // get id from multiselect
                //jQuery("#htmlTable").restoreRow(lastsel);
                //jQuery("#htmlTable").editRow(id,true);
                //lastsel=id;
            }
        },
		ondblClickRow: function(id){ 
			jQuery.post('<?php echo site_url('np_bu/view')?>/'+id, {},
				function(rs){
				if(rs.result=='ok'){
					jQuery('#action').val('edit');
					jQuery('#idmasters').val(id);
					jQuery('#nomor').val(rs.nomor);
					jQuery('input:radio[value="'+rs.jenis+'"]').attr('checked','true');
					
					jQuery('#pemohon').val(rs.pemohon);
					jQuery('#nama_bu').val(rs.nama_bu);
					jQuery('#tipe_bu').val(rs.tipe_bu);
					jQuery('#telp_bu').val(rs.telp_bu);
					jQuery('#fax_bu').val(rs.fax_bu);
					jQuery('#alamat_bu').val(rs.alamat_bu);
					jQuery('#kodepos_bu').val(rs.kodepos_bu);
					
					jQuery('#pemilik_nama').val(rs.pemilik_nama);
					jQuery('#pemilik_tmp_lahir').val(rs.pemilik_tmp_lahir);
					jQuery('#pemilik_tgl_lahir').val(rs.pemilik_tgl_lahir);
					jQuery('#pemilik_no_ktp').val(rs.pemilik_no_ktp);						
					jQuery('#pemilik_npwp').val(rs.pemilik_npwp);					
					jQuery('#pemilik_telp').val(rs.pemilik_telp);
					jQuery('#pemilik_hp').val(rs.pemilik_hp);
					
					jQuery('#pemilik_alamat').val(rs.pemilik_alamat);
					jQuery('#pemilik_rt').val(rs.pemilik_rt);
					jQuery('#pemilik_rw').val(rs.pemilik_rw);
					jQuery('#pemilik_kodepos').val(rs.pemilik_kodepos);
					jQuery('#pemilik_id_desa').val(rs.pemilik_id_desa);
					jQuery('#desa_pemilik').val(rs.desa_pemilik);
					
					jQuery('#kode_usaha').val(rs.kode_usaha);
					jQuery('#tgl_kartu').val(rs.tanggal_kartu);					
					jQuery('#tgl_kirim').val(rs.tanggal_kirim);
					jQuery('#tgl_terima').val(rs.tanggal_terima);
					jQuery('#tgl_kembali').val(rs.tanggal_kembali);
					jQuery('#tgl_tutup').val(rs.tanggal_tutup);
					jQuery('#memo').val(rs.memo);
					jQuery("#tabs").tabs('select', 0);
				}
			},'json');
		},
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth(1000);
                return true;
        }
    }).navGrid('#htmlPager'
        ,{view:true,add:true,edit:true,del:true,viewtext:"Lihat",searchtext:"Cari",addtext:"Tambah",deltext:'Hapus',edittext:'Ubah',refreshtext:'Refresh'}
        ,{closeOnEscape:true,height:350, width:500} // edit ,beforeShowForm:setDesa
        ,{closeOnEscape:true,height:350, width:500} // add
        ,{closeOnEscape:true,reloadAfterSubmit:false} // del 
        ,{closeOnEscape:true,closeAfterSearch: true} // multipleSearch:true,		
    ).hideCol('id');

	function setDesa(){
		var data={};jQuery('select#desa').empty();
		jQuery.post('<?php echo site_url('master/desa/getselect')?>', data ,function(result){
			jQuery.each(result, function(val, text){
				jQuery('select#desa').append( new Option(text.nama,text.id,text.selected) );
			});			
		},'json');
	}		
	jQuery("#tabs").tabs();
	
	jQuery("input#nama_bu").autocomplete("<?php echo site_url('np_bu/seekname')?>", {
		width: 190, scrollHeight: 220, scroll: true, scrollHeight: 300,	selectFirst: false
	});	
	jQuery("input#nama_bu").result(function(event, data, formatted) {	
		if(data){ 
			jQuery('#nama_bu').val(data[0]);  jQuery('#pemohon').val(data[1]);
			jQuery.get('<?php echo base_url().'pemohon_bu/getinfo/'?>'+data[1],function(rs){
				jQuery('#nama_bu').val(rs.NAMA);
				jQuery('#tipe_bu').val(rs.BADAN_TIPE);
				jQuery('#alamat_bu').val(rs.ALAMAT);
				jQuery('#telp_bu').val(rs.BADAN_TELP);
				jQuery('#fax_bu').val(rs.BADAN_FAX);
				jQuery('#kodepos_bu').val(rs.KODEPOS);
				jQuery('#pemilik_nama').val(rs.PEMILIK_NAMA);
				jQuery('#pemilik_no_ktp').val(rs.PEMILIK_NO_KTP);
				jQuery('#pemilik_npwp').val(rs.PEMILIK_NPWP);
				jQuery('#pemilik_telp').val(rs.PEMILIK_TELP);
				jQuery('#pemilik_hp').val(rs.PEMILIK_HP);
				jQuery('#pemilik_tmp_lahir').val(rs.PEMILIK_TMP_LAHIR);
				jQuery('#pemilik_tgl_lahir').val(rs.PEMILIK_TGL_LAHIR);
				jQuery('#pemilik_alamat').val(rs.PEMILIK_ALAMAT);
				jQuery('#pemilik_rt').val(rs.PEMILIK_RT);
				jQuery('#pemilik_rw').val(rs.PEMILIK_RW);
				jQuery('#pemilik_kodepos').val(rs.PEMILIK_KODEPOS);
				jQuery('#pemilik_id_desa').val(rs.PEMILIK_ID_DESA);
				jQuery('#desa_pemilik').val(rs.PEMILIK_ID_DESA);
			},'json');
		}else{
			alert('gak ada');
			jQuery('#nama_bu').val('');
		}		
	});
	
	jQuery("#desa_pemilik").autocomplete("<?php echo site_url('master/desa/seekname')?>",{width: 190, scrollHeight: 220, scroll: true, scrollHeight: 300, selectFirst: false });
	jQuery("#desa_pemilik").result(function(event, data, formatted) {
		if(data){ jQuery('#pemilik_id_desa').val(data[1]); jQuery('#desa_pemilik').val(data[0]) }
	});	
		
	jQuery('#ck_pajak,#ck_retribusi').click(function(){
		setNomor();
	});
	
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
	}
	
	jQuery('#btn_save').click(function(){ jQuery("#form").submit(); });
	
	jQuery('#btn_cancel').click(function(){ 
		v.resetForm();
	});
		
	jQuery("#tgl_kartu").mask("99/99/9999");//jQuery('#tgl_kartu').datepicker({changeMonth: true, changeYear: true});
	jQuery("#tgl_kirim").mask("99/99/9999");//jQuery('#tgl_kirim').datepicker({changeMonth: true, changeYear: true});
	jQuery("#tgl_terima").mask("99/99/9999");//jQuery('#tgl_terima').datepicker({changeMonth: true, changeYear: true});
	jQuery("#tgl_kembali").mask("99/99/9999");//jQuery('#tgl_kembali').datepicker({changeMonth: true, changeYear: true});
	jQuery("#tgl_tutup").mask("99/99/9999");//jQuery('#tgl_tutup').datepicker({changeMonth: true, changeYear: true});
	jQuery("#pemilik_tgl_lahir").mask("99/99/9999");//jQuery('#pemilik_tgl_lahir').datepicker({changeMonth: true, changeYear: true}); 
		
});

function setNomor()
{
	var data = {};	
	data.action = document.getElementById('action').value;
	//data.typ = document.getElementById('jenis_daftar').value;
	data.typ = jQuery('#ck_pajak').attr('checked') ? jQuery('#ck_pajak').val() : jQuery('#ck_retribusi').val();
	data.kd  = document.getElementById('kode_usaha').value;
	data.obj  = document.getElementById('objek_pdrd').value;
	jQuery.post('<?php echo base_url().'np_bu/getno'?>',data,function(result){
		if(result!='') jQuery('#nomor').val(result);
	});
}

/*jQuery.validator.setDefaults({
	submitHandler: function() {
		alert("submitted! (skipping validation for cancel button)");
	}
});*/
</script>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Formulir Isian</a></li>
		<li><a href="#tabs-2">Daftar NP Badan Usaha</a></li>
	</ul>
	<div id="tabs-1">

<?php //$this->load->view($form) 
	  if(isset($form)) echo $form;
?>
	</div>
	<div id="tabs-2">
		<div class='form'>
			<table id="htmlTable" class="scroll"></table>
			<div id="htmlPager" class="scroll"></div>
		</div>
	</div>
</div>
			
<table id="htmlTable" class="scroll"></table>
<div id="htmlPager" class="scroll"></div>