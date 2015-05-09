	
	<fieldset>
    <legend>Tanggal</legend>
      
      <input type="text" name="string" id="string" class="datepicker span2">
      <a class="btn btn-primary" href="#" id="filter"><i class="icon-search icon-white"></i> Filter</a>
  </fieldset>
	<table id="grid"></table>
	<div id="pager"></div>
	<form id="frm" method="post" target="_blank" action="<?php echo base_url(); ?>feature/cetak_teguran">
		<div class="controls-row">
			<input type="submit" value="Cetak Surat Teguran" name="btn_cetak" class="btn btn-primary" onClick="window.location.reload()" />
		</div>
	</form>
	<script type="text/javascript">
	$(document).ready(function() {
		
		
		$("#grid").jqGrid({
			url:'<?php echo base_url()?>feature/daftar_reminder',
			editurl:'<?php echo base_url()?>feature/daftar_reminder',
			datatype:'json',
			mtype:'POST',
			colNames:['NPWPD','NAMA_WP','NAMA_REKENING','JUMLAH_PAJAK'],
			colModel:[
				{name:'npwpd',index:'npwpd',width:180,editable:true,editoptions:{size:30,class:'autocomplete span3'},editrules:{required:true}},
				{name:'nama_wp',index:'nama_wp',width:180,editable:true,editoptions:{size:30,class:"span3"},editrules:{required:true}},
				{name:'nama_rekening', index:'nama_rekening',width:115,editable:true,edittype:'text',editoptions:{size:10,class:"span2"}},
				{name:'jumlah_pajak', index:'jumlah_pajak',width:65,editable:true,edittype:'text',editoptions:{size:10,class:"span1"}},
				// {name:'cetak',search:false,index:'npwpd',width:60,sortable: false,formatter: cetakLink},
			],
			rowNum:10,
			rowList:[10,20,30],
			rownumbers:true,
			pager:'#pager',
			viewrecords:true,
			gridview:true,
			width:930,
			height:250,
			onSelectRow: restore_row//,
			//ondblClickRow:edit_row
		});
		
		function cetakLink(id)
        {
            return "<a href='<?php echo base_url(); ?>feature/cetak_teguran/"+id+"' class='btn btn-primary' >Cetak</a>";
        }
		
		/*
		 $("#grid").jqGrid('bindKeys', {
    'onEnter':edit_row
  });*/
		
		$("#grid").jqGrid( 'navGrid', '#pager', { 
		refresh: true,
		refreshtext: 'Refresh',
		//editfunc:edit_row,
		});
		
		 function edit_row(id){
    location.href = root+modul+'<?php echo $link_form;?>/'+id;
  }
		
		$("#add_grid").hide();
		$("#edit_grid").hide();
		$("#del_grid").hide();
		$("#search_grid").hide();
		
		function restore_row(id){
			if(id && id !== last){
				$('#grid').jqGrid('restoreRow', last);
				last = null;
			}
		}

	
		
		function errorfunc(id, resp){
			var msg = $.parseJSON(resp.responseText);
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});

			$('#grid').jqGrid('restoreRow', last);
			$('#grid').trigger("reloadGrid");
		}
	
		$('#filter').click(function(){
			var field 	= $("#field").val();
			var oper 	= $("#oper").val();
			var string 	= $("#string").val();
			
			var grid = $("#grid");
			var postdata = grid.jqGrid('getGridParam','postData');
			$.extend (postdata,
						   {filters:'',
							searchField: field,
							searchOper: oper,
							searchString: string});
			grid.jqGrid('setGridParam', { search: true, postData: postdata });
			grid.trigger("reloadGrid",[{page:1}]);
		}); 
	
		$('#string').keypress(function (e) {
			if (e.which == 13) {
				$('#filter').click();
			}
		}); 
	
	});
	</script>