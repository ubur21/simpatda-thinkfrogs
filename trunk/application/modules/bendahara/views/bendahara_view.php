	<fieldset>
    <legend>Daftar Pejabat Daerah</legend>
      <select name="field" id="field" class="span2">
		<option value='nama'>Nama</option>
		<option value='kode'>Kode</option>
      </select>
      <select name='oper' id='oper' class="span2">
        <option value="cn">Memuat</option>
        <option value="bw">Diawali</option>
      </select>
      <input type="text" name="string" id="string" class="span7">
      <a class="btn btn-primary" href="#" id="filter"><i class="icon-search icon-white"></i> Filter</a>
  </fieldset>
	<table id="grid"></table>
	<div id="pager"></div>

	<script type="text/javascript">
	$(document).ready(function() {
		var last;
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
		$("#string").focus();
		
		$("#grid").jqGrid({
			url:'<?php echo base_url()?>bendahara/get_daftar',
			editurl:'<?php echo base_url()?>bendahara/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['NAMA','AKUN'],
			colModel:[
				{name:'nama',index:'nama',width:180,editable:true,editoptions:{size:30,class:'autocomplete span3'},editrules:{required:true}},
				{name:'akun',index:'akun',width:180,editable:true,editoptions:{size:30,class:"span3"},editrules:{required:true}},
				
			],
			rowNum:10,
			rowList:[10,20,30],
			rownumbers:true,
			pager:'#pager',
			viewrecords:true,
			gridview:true,
			width:930,
			height:250,
			ondblClickRow:edit_row,
			onSelectRow: restore_row
		});
		
		$("#grid").jqGrid( 'navGrid', '#pager', { 
		add: true,
		addtext: 'Tambah',
		addfunc: append_row,
		edit: true,
		edittext: 'Ubah',
		editfunc: edit_row,
		del: true,
		deltext: 'Hapus',
		delfunc: del_row,
		search: false,
		searchtext: 'Cari',
		refresh: true,
		refreshtext: 'Refresh',
		},{},{},{},{});
		
		
		function append_row(){
			if(data_dasar=='3'){
				var id = $("#grid").jqGrid('getGridParam','selrow');
				if(id)
				{
					jml = $("#grid").jqGrid('getDataIDs');
					var hasil = "";
					for(var u=0;u<jml.length;u++)
					{
						if(jml[u] == "new"){
							hasil = hasil + jml[u];
						}
					}
					
					var ada = hasil.search('new');
					if(ada != -1){
						alert('Input Bendahara  belum tersimpan..!!');
					}
					else{
						$('#grid').jqGrid('restoreRow', last);
						$("#grid").jqGrid('addRowData', "new", true, 'after', id);
						$('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
					}
				}
				else
				{
					jml = $("#grid").jqGrid('getDataIDs');
					pos = jml.length - 1;
					if(jml[pos] == "new"){
						alert('Input Pejabat Daerah belum tersimpan..!!');
					}
					else{
						$("#grid").jqGrid('addRowData', "new", true);
						$('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
						$(".autocomplete").autocomplete('<?php echo base_url()?>pejabat_daerah/get_jabatan');
					}
						
				}
				last = null;
			}else{
				alert('Tidak bisa tambah data');
			}
		}
		
		function edit_row(id){
			if(data_dasar=='3'){
				$('#grid').jqGrid('restoreRow', last);
				$('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
				$(".autocomplete").autocomplete('<?php echo base_url()?>pejabat_daerah/get_jabatan');
				last = id;
			}else{
				alert('Tidak bisa ubah data');
			}
		}
		
		function del_row(id){
			if(data_dasar=='3'){
				var rt = $("#grid").jqGrid('getRowData', id); 
				var answer = confirm('Hapus dari daftar?');
				if(answer == true)
				{
					$.ajax({
						url: '<?php echo base_url()?>bendahara/hapus', 
						data: { id: id},
						success: function(response){
							var msg = $.parseJSON(response);
							$.pnotify({
							  title: msg.isSuccess ? 'Sukses' : 'Gagal',
							  text: msg.message,
							  type: msg.isSuccess ? 'info' : 'error'
							});
							if(msg.isSuccess == true) {
								$("#grid").jqGrid('delRowData', id);
							}
							$('#grid').trigger('reloadGrid');
						},
						type: "post", 
						dataType: "html"
					});
				}
			}else{
				alert('Tidak bisa hapus data');
			}
		}
		
		function restore_row(id){
			if(id && id !== last){
				$('#grid').jqGrid('restoreRow', last);
				last = null;
			}
		}

		function aftersavefunc(id, resp){
			console.log('aftersavefunc');
			var msg = $.parseJSON(resp.responseText);
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});

			if(msg.id &&  msg.id != id)
			$("#"+id).attr("id", msg.id);
			$('#grid').trigger('reloadGrid');
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