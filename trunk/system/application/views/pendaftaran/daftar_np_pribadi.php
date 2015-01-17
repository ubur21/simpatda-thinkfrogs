<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){
	
	jQuery("#htmlTable").jqGrid({
		url:'<?php echo site_url('np_pribadi/daftar')?>',
		editurl:'<?php echo site_url('np_pribadi/daftar')?>',
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
			jQuery.post('<?php echo site_url('np_pribadi/view')?>/'+id, {},
				function(rs){
				if(rs.result=='ok'){
					jQuery('#action').val('edit');
					jQuery('#idmasters').val(id);
					jQuery('#nomor').val(rs.nomor);
					jQuery('input:radio[value="'+rs.jenis+'"]').attr('checked','true');
					
					jQuery('#pemohon').val(rs.pemohon);
					jQuery('#no_ktp').val(rs.no_ktp);
					jQuery('#nama').val(rs.nama_pemohon);
					jQuery('#tempat_lahir').val(rs.tempat_lahir);
					jQuery('#tgl_lahir').val(rs.tanggal_lahir);
					jQuery('#pekerjaan').val(rs.pekerjaan);
					jQuery('#no_hp').val(rs.no_hp);
					jQuery('#rt').val(rs.rt);
					jQuery('#rw').val(rs.rw);
					jQuery('#kodepos').val(rs.kodepos);						
					jQuery('#alamat').val(rs.alamat);					
					jQuery('#iddesa').val(rs.id_desa);
					jQuery('#desa').val(rs.kelurahan);
					jQuery('#telp').val(rs.telp);
					
					jQuery('#kode_usaha').val(rs.kode_usaha);
					jQuery('#tgl_kartu').val(rs.tanggal_kartu);
					jQuery('#tgl_terima').val(rs.tanggal_terima);
					jQuery('#tgl_kirim').val(rs.tanggal_kirim);
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
		
	jQuery("#nama").autocomplete("<?php echo site_url('np_pribadi/seekname')?>",{width: 190, scrollHeight: 220, scroll: true, scrollHeight: 300, selectFirst: false });	
	jQuery("#nama").result(function(event, data, formatted) {
		if(data){ 
			jQuery('#nama').val(data[0]);  jQuery('#pemohon').val(data[1]);
			jQuery.get('<?php echo base_url().'pemohon_pribadi/getinfo/'?>'+data[1],function(rs){
				jQuery('#no_ktp').val(rs.NO_KTP);
				jQuery('#tempat_lahir').val(rs.TEMPAT_LAHIR);
				jQuery('#tgl_lahir').val(rs.TANGGAL_LAHIR);
				jQuery('#alamat').val(rs.ALAMAT);
				jQuery('#rw').val(rs.RT);
				jQuery('#rt').val(rs.RW);
				jQuery('#iddesa').val(rs.ID_DESA);
				jQuery('#desa').val(rs.ID_DESA);
				jQuery('#telp').val(rs.NO_TELP);
				jQuery('#no_hp').val(rs.NO_HP);
				jQuery('#kodepos').val(rs.KODEPOS);
				jQuery('#pekerjaan').val(rs.PEKERJAAN);				
			},'json');
		}else{
			alert('gak ada');
			jQuery('#pemohon').val('');
		}		
	});
	jQuery("#desa").autocomplete("<?php echo site_url('master/desa/seekname')?>",{width: 190, scrollHeight: 220, scroll: true, scrollHeight: 300, selectFirst: false });
	jQuery("#desa").result(function(event, data, formatted) {
		if(data){ jQuery('#iddesa').val(data[1]); jQuery('#desa').val(data[0]) }
	});
	
	jQuery('#ck_pajak,#ck_retribusi').click(function(){
		setNomor();
	});
	
	var v = jQuery("#form").validate();	
	
	var options = {
		dataType:'text',
		beforeSubmit: function() {
			if(jQuery('#form').validate().form()) jQuery('#lock_screen').html('<div class="mask_layer"><div>Sedang proses...<br><img src="http://'+location.host+'/simpatda_ci/themes/brown/images/ajax-loader.gif"></div></div>');
			return jQuery('#form').validate().form();
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
	jQuery("#tgl_lahir").mask("99/99/9999");//jQuery('#tgl_lahir').datepicker({changeMonth: true, changeYear: true}); 
});

function setNomor()
{
	var data = {};	
	data.action = document.getElementById('action').value;
	//data.typ = document.getElementById('jenis_daftar').value;
	data.typ = jQuery('#ck_pajak').attr('checked') ? jQuery('#ck_pajak').val() : jQuery('#ck_retribusi').val();
	data.kd  = document.getElementById('kode_usaha').value;
	data.obj  = document.getElementById('objek_pdrd').value;
	jQuery.post('<?php echo base_url().'np_pribadi/getno'?>',data,function(result){
		if(result!='') jQuery('#nomor').val(result);
	});
}

/*jQuery.validator.setDefaults({
	submitHandler: function() {
		//alert("submitted! (skipping validation for cancel button)");
	}
});*/
</script>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Formulir Isian</a></li>
		<li><a href="#tabs-2">Daftar NP Pribadi</a></li>
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