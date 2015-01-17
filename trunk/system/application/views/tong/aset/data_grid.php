<script>
var kib_bujur;
var kib_lintang;
var prov;
var gol_aset;
var SatKer;
jQuery(document).ready(function(){ 
  
	jQuery("#htmlTable7").jqGrid({
		url:'<?php echo site_url('aset/get_kab')?>',
		editurl:'<?php echo site_url('aset')?>',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','Kode','Nama'],
		colModel :[
		{name:'id',index:'id',width:20,search:false},
		{
			name:'kode',index:'kode',width:40,editable:true,edittype:'text',editoptions: {size:10, maxlength: 10},editrules: {required:true}
		},{
			name:'nama',index:'nama',width:140,editable:true,edittype:'text',editoptions: {size:30, maxlength: 30},editrules: {required:true}
		}
		
		],
		pager: jQuery('#htmlPager7'),
		height:200,
		rownumbers: true, 
		rowNum:10,
		rowList:[5,10,20,30],
		shrinkToFit:false,
		sortname: 'id',
		sortorder: 'asc',
		altRows:true,
		viewrecords: true,
		caption: 'Kabupaten',
		
		gridComplete: function(){
			//alert(document.width);
		  jQuery("#htmlTable7").setGridWidth( document.width - 750 < 200?200:document.width - 750);
		  return true;
		},onSelectRow: function(id){ 
			tmp = jQuery("#htmlTable7").getRowData(id);
			jQuery("#desa").val(tmp.nama);
			jQuery("#kecamatan").val(tmp.nama);
			jQuery("#prov").val(tmp.nama);
			jQuery("#kab").val(tmp.nama);
			jQuery("#idlokasi").val(tmp.id);
			$('#data_provinsi').dialog('close');
			
		}
    }).hideCol('id');  /*end of on ready event */ 
	
	jQuery("#htmlTable8").jqGrid({
		url:'<?php echo site_url('/aset')?>',
		editurl:'<?php echo site_url('/aset')?>',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','Kode Aset','Aset'],
		colModel :[
			{name:'id',index:'id',width:20,search:false},
			{
				name:'kode',index:'kode',width:90,editable:true,edittype:'text',editoptions: {size:10, maxlength: 6},editrules: {required:true}
			},{
				name:'aset',index:'aset',width:150,editable:true,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
			}
		], 
		pager: jQuery('#htmlPager8'),
		height:200,
		rownumbers: true, 
		rowNum:10,
		rowList:[5,10,20,30],
		shrinkToFit:false,
		sortname: 'id',
		sortorder: 'asc',
		altRows:true,
		viewrecords: true,
		caption: 'Golongan Aset',
		
		gridComplete: function(){
		  jQuery("#htmlTable8").setGridWidth( document.width - 750 < 200?200:document.width - 750);
		  return true;
		},onSelectRow: function(id){ }
    }).hideCol('id');  /*end of on ready event */ 
	
	jQuery("#TableSatker").jqGrid({
		url:'<?php echo site_url('aset/get_sat_ker')?>',
		editurl:'<?php echo site_url('aset')?>',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id_satker','Kode Satker','Nama Satker'],
		colModel :[
			{name:'id_satker',index:'id_satker',width:20,search:false},
			{
				name:'kode',index:'kode',width:90,editable:true,edittype:'text',editoptions: {size:10, maxlength: 6},editrules: {required:true}
			},{
				name:'nama',index:'nama',width:150,editable:true,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}
			}
		], 
		pager: jQuery('#PagerSatker'),
		height:200,
		rownumbers: true, 
		rowNum:5,
		rowList:[5,10,20,30],
		shrinkToFit:false,
		sortname: 'id_satker',
		sortorder: 'asc',
		altRows:true,
		viewrecords: true,
		caption: 'Satuan Kerja',
		
		gridComplete: function(){
		  jQuery("#TableSatker").setGridWidth( document.width - 750 < 200?200:document.width - 750);
		  return true;
		},onSelectRow: function(id){ 
			tmp = jQuery("#TableSatker").getRowData(id);
			jQuery("#idgetsatker").val(tmp.id_satker);
			jQuery('#satker').val(tmp.kode);
			jQuery('#nama_sat_ker').val(tmp.nama);
			$('#data_sat_ker').dialog('close');
		}
  	}).hideCol('id_satker');  /*end of on ready event */ 
  	jQuery("#TableSatker").jqGrid('navGrid','#PagerSatker',{edit:false,add:false,del:false});
	
	$("#data_sat_ker").dialog({
		bgiframe: true, resizable: false, height:400,width:750, modal: false, autoOpen: false,
		buttons: {
			'Tutup': function() { 
				$(this).dialog('close');
			},
			'Pilih':function(){
			var x =document.getElementById('tes').value;
				$.get('<?php echo site_url('aset/get_data')?>', function(x) {
				  $('.result').html(x);
				});
			}
		}
	});
	
});

</script>
<div class="form">
<input type="hidden" id="tes" />
	<div id="data_provinsi">
		<table id="htmlTable7" class="scroll"></table>
		<div id="htmlPager7" class="scroll"></div>
	</div>
	<div id="data_sat_ker">
		<table id="TableSatker" class="scroll"></table>
		<div id="PagerSatker" class="scroll"></div>
	</div>
	
</div>