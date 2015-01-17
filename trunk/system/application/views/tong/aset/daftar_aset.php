<script src="<?php echo base_url()?>assets/fr/fastreport.js" type="text/javascript" ></script>
<table class="layout">
<tr>
	<td><?php $this->load->view('aset/menu'); ?></td>
	<td>
		<div>
		<table id="grid"></table> 
		<div id="pager"></div>
			<div class="<?php echo $this->css->panel();?>">
				
			</div>
		</div>
	</td>
</tr>
</table>

<script type="text/javascript">

jQuery(document).ready(function(){ 

	$('#show_dialog').click(function(){
		$('#tes_dialog').dialog('open');		
	});
	
	jQuery("#grid").jqGrid({ 
		url:'<?=site_url('aset/get_daftar')?>', 
		editurl:'<?php echo site_url('aset')?>', 
		datatype: "json", 
		mtype: 'POST',
		colNames:['ID', 'Kelompok ID', 'Nama Kelompok Aset', 'Nama Aset', 'Tanggal Perolehan', 'Nilai Perolehan'], 
		colModel:[ 
			{name:'Aset_ID',index:'Aset_ID', width:20, search:false, hidden:true}, 
			{name:'Kelompok_ID',index:'Kelompok_ID', width:100, hidden:true},
			{name:'uraian',index:'uraian', width:300, align:"left"},
			{name:'NamaAset',index:'NamaAset', width:180, align:"left"},
			{name:'TglPerolehan',index:'TglPerolehan', width:120, editable:true, edittype:'text', editoptions: {size:50, maxlength: 100},editrules: {required:true}},
			{name:'NilaiPerolehan',index:'NilaiPerolehan', width:100, align:"left", editable:false}
		], 
		rowNum:10, 
		rowList:[10,20,30], 
		rownumbers: true,
		pager: '#pager', 
		sortname: 'Aset_ID', 
		sortorder: "asc", 
		viewrecords: true, 
		multiselect: true,
		multiboxonly: true,
		width: 600,
		height: 230,
		caption:"Daftar Aset",
		ondblClickRow: function(id){ 
			var tmp = jQuery("#grid").getRowData(id);
			location.href = "<?php echo site_url('aset/edit/')."/"; ?>" + id;
		}
	}); 
	
    jQuery("#grid").jqGrid('navGrid','#pager',{edit:true,add:false,del:true});
	jQuery("#grid").navButtonAdd(
			"#pager",
			{
				caption:"Tambah",title:"Tambah", 
				onClickButton:function(){
					//alert('xxxxxx');
					$('#dialog1').dialog('open');
				} 
			}
		);
		
	$("#dialog1").dialog({
		bgiframe: true, resizable: false, height:450,width:750, modal: true, autoOpen: false,
		buttons: {
			'Tutup': function() { $(this).dialog('close');},
			'Tambah':function(){
				
			}
		}
	});
});	

</script>