<div style="padding:10px;">
	<table id="grid"></table>
	<div id="pager"></div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#grid").jqGrid({ 
			url:'<?php echo site_url('master/berita/get_daftar')?>', 
			editurl:'<?php echo site_url('master/berita')?>', 
			datatype: "json", 
			mtype: 'POST',
			//colNames:['ID', 'Judul', 'Deskripsi', 'Sumber'], 
			colNames:['ID', 'Judul', 'Tanggal', 'Sumber'], 
			colModel:[ 
				{name:'id',index:'berita_id', width:20, search:false, hidden:true}, 
				{name:'judul',index:'judul', width:100, align:"left", editable:true, edittype:'text', editoptions: {size:50, maxlength: 100},editrules: {required:true}},
				// {name:'deskripsi',index:'deskripsi', width:400, align:"left", editable:true, edittype:'text', editoptions: {size:50, maxlength: 100},editrules: {required:true}},
				{name:'tanggal',index:'tanggal', width:100,align:"left",editable:true,edittype:'text',editoptions:{size:50,maxlength:100},editrules:{required:true}},
				{name:'sumber',index:'sumber', width:100, align:"left", editable:true, edittype:'text', editoptions: {size:50, maxlength: 100},editrules: {required:true}}
			], 
			rowNum:10, 
			rowList:[10,20,30], 
			rownumbers: true,
			pager: '#pager', 
			sortname: 'berita_id', 
			sortorder: "asc", 
			viewrecords: true, 
			gridview: true,
			multiselect: true,
			multiboxonly: true,
			width: 600,
			height: 230,
			ondblClickRow: function(id){ 
				location.href = "<?php echo site_url('master/berita/form')."/"; ?>" + id;
			}
		}); 
		jQuery("#grid").jqGrid('navGrid','#pager',{edit:false,add:false,del:true});
	})
</script>