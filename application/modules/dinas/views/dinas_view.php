	<fieldset>
    <legend>Data Dinas</legend>
      <select name="field" id="field" class="span2">
		<option value='namaskpd'>Nama SKPD</option>
		<option value='skpd'>Kode SKPD</option>
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
		var last = newid = 0;	
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
		
		// var myelem;
/********************************* SKPD *****************************************************/
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>dinas/get_daftar',
			editurl:'<?php echo base_url()?>dinas/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['ID','KODE SKPD','NAMA SKPD', 'ALAMAT_SKPD', 'TELP_SKPD'],
			colModel:[
				{name:'id',index:'id',width:25,editable:false,search:false,hidden:true},
				{name:'skpd',index:'skpd',width:110,editable:true,edittype:'text',editoptions:{size:20,class:'span3'}, editrules:{number:false},sortable:false},
				{name:'namaskpd',index:'namaskpd',width:300,editable:true,edittype:'text',editoptions:{size:100,class:'span7'},sortable:false},
				{name:'alamatskpd',index:'alamatskpd',width:400,editable:true,edittype:'text',editoptions:{size:255,class:'span7'},sortable:false},
				{name:'telpskpd',index:'telpskpd',width:300,editable:true,edittype:'text',editoptions:{size:20,class:'span7'},sortable:false}
			
			],
			rowNum:-1,
			scroll:1,			
			rownumbers:false,
			pager:'#pager',
			sortorder:'asc',
			viewrecords:true,
			multiselect:true,
			multiboxonly:true,
			gridview:true,
			width:930,
			height:'240',			
			
/*********************************************** Sub Grid Pejabat SKPD *****************************************************************/
			subGrid:true,
			subGridRowExpanded: function(subgrid_id,row_id){
				var ret = jQuery("#grid").jqGrid('getRowData',row_id);
				var tablePejabat,pagerPejabat;
				tablePejabat = subgrid_id+"_t";
				pagerPejabat = "p_"+tablePejabat;
				jQuery("#"+subgrid_id).html("<table id='"+tablePejabat+"' class='scroll'></table><div id='"+pagerPejabat+"' class='scroll'></div>");
				jQuery("#"+tablePejabat).jqGrid({
					url:'<?php echo base_url()?>dinas/get_daftar_pejabat'+'/'+ret.id,
					editurl:'<?php echo base_url()?>dinas/proses_form_pejabat',
					datatype:'json',
					mtype:'POST',
					colNames:['ID','JABATAN','NAMA PEJABAT','NIP','GOLONGAN','PANGKAT','STATUS'],
					colModel:[
					{name:'ID_PEJABAT_SKPD',editable:false,index:'ID_PEJABAT_SKPD',width:5,search:false,hidden:true},					
					{name:'JABATAN',index:'JABATAN',width:200,editable:true,editoptions:{size:50},editrules:{required:true}},
					{name:'NAMA_PEJABAT', index:'NAMA_PEJABAT',width:280,editable:true,edittype:'text',editoptions:{size:20}},
					{name:'NIP',index:'NIP',width:200,editable:true,edittype:'text',editoptions:{size:20}},
					{name:'GOLONGAN',index:'GOLONGAN',width:85,editable:true,edittype:'text',editoptions:{size:10}},
					{name:'PANGKAT',index:'PANGKAT',width:200,editable:true,edittype:'text',editoptions:{size:20}},
					{name:'AKTIF',index:'AKTIF',width:100,editable:true,edittype:'select',editoptions:{value:"1:Aktif;0:Tidak Aktif",class:"span1"}},
					],					
					rownumbers:true,
					pager:"#"+pagerPejabat,
					sortorder:'asc',
					viewrecords:true,
					gridview:true,
					width:900,
					height:'100%',
					ondblClickRow:edit_row2,
					onSelectRow:restore_row2
					});
					
					jQuery("#"+tablePejabat).jqGrid( 'navGrid', "#"+pagerPejabat, { 
					add: true,
					addtext: 'Tambah',
					addfunc: append_row2,
					edit: true,
					edittext: 'Ubah',
					editfunc: edit_row2,
					del: true,
					deltext: 'Hapus',
					delfunc: del_row2,
					search: false,
					searchtext: 'Cari',
					refresh: true,
					refreshtext: 'Refresh',
					},{},{},{},{});
										
					function append_row2(){
            if(data_dasar=='3'){
              var id = jQuery("#"+tablePejabat).jqGrid('getGridParam','selrow');
              if(id)
              {
                jml = jQuery("#"+tablePejabat).jqGrid('getDataIDs');
                var hasil = "";
                for(var u=0;u<jml.length;u++)
                {
                  if(jml[u] == "new"){
                    hasil = hasil + jml[u];
                  }
                }
                
                var ada = hasil.search('new');
                if(ada != -1){
                  alert('Input Pejabat SKPD belum tersimpan..!!');
                }
                else{
                  jQuery("#"+tablePejabat).jqGrid('restoreRow', last);
                  jQuery("#"+tablePejabat).jqGrid('addRowData', ret.id, true, 'after', id);
                  jQuery("#"+tablePejabat).jqGrid('editRow', ret.id, true, null, null, null, null, aftersavefunc2, errorfunc, null);
                }
              }
              else
              {
                jml = jQuery("#"+tablePejabat).jqGrid('getDataIDs');
                pos = jml.length - 1;
                if(jml[pos] == "new"){
                  alert('Input Pejabat SKPD belum tersimpan..!!');
                }
                else{
                  jQuery("#"+tablePejabat).jqGrid('addRowData', ret.id, true);
                  jQuery("#"+tablePejabat).jqGrid('editRow', ret.id, true, null, null, null, null, aftersavefunc2, errorfunc, null);
                }
                  
              }
              last = null;
            }else{
              alert('Tidak bisa tambah data');
            }
					}
	
					function edit_row2(id){
            if(data_dasar=='3'){
              jQuery("#"+tablePejabat).jqGrid('restoreRow', last);
              jQuery("#"+tablePejabat).jqGrid('editRow', id, true, null, null, null, null, aftersavefunc2, errorfunc, null);
              last = id;
            }else{
              alert('Tidak bisa ubah data');
            }
					}
					
					function del_row2(id){
						
						if(data_dasar=='3')
						{
							var answer = confirm('Hapus pejabat dari daftar?');
							if(answer == true)
							{						
								  $.ajax({
								  type: "post",
								  dataType: "json",
								  url: root+'dinas/hapus_pejabat',
								  data: {id: id},
									success: function(res) {
									  $.pnotify({
										title: res.isSuccess ? 'Sukses' : 'Gagal',
										text: res.message,
										type: res.isSuccess ? 'info' : 'error'
									  });
									  if (true == res.isSuccess){
										jQuery("#"+tablePejabat).jqGrid('delRowData', id);
									  };
									},
								  });
							}
						}
						else
						{
							alert('Tidak bisa hapus data Pejabat SKPD');
						}
					}
	
					function restore_row2(id){
						if(id && id !== last){
						jQuery("#"+tablePejabat).jqGrid('restoreRow', last);
						last = null;
						}
					}					
	
          function aftersavefunc2(id, resp){
            console.log('aftersavefunc');
            var msg = jQuery.parseJSON(resp.responseText);
            $.pnotify({
              title: msg.isSuccess ? 'Sukses' : 'Gagal',
              text: msg.message,
              type: msg.isSuccess ? 'info' : 'error'
            });

            if(msg.id &&  msg.id != id)
            jQuery("#"+tablePejabat+id).attr("id", msg.id);
            jQuery("#"+tablePejabat).trigger('reloadGrid');
          }
          
					function errorfunc(id, resp){
						var o = $.parseJSON(resp.responseText);
						$.pnotify({
							text: o.message ? o.message : 'Server tidak bisa diakses',
							type: resp.status = 200 ? 'info' : 'error'
						  });
						jQuery("#"+tablePejabat).trigger("reloadGrid");
					}
	
			},
/************************************************************** end Sub grid pejabat SKPD **************************************************/

			ondblClickRow:function(id){
				var id = jQuery("#grid").jqGrid('getGridParam','selrow');
				var ret = jQuery('#grid').jqGrid('getRowData', id);
				
				if(ret.skpd != '')
				 {
					edit_row(id);
				 }
				 else
				 {
					return false;
				 }
				 
				 jQuery.post("<?php echo base_url()?>dinas/session_id",{
					'ID_SKPD':id
					},function(data){
				});
			},
			onSelectRow:restore_row

		});
		
		jQuery("#grid").jqGrid( 'navGrid', '#pager', { 
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
				var id = jQuery("#grid").jqGrid('getGridParam','selrow');
				if(id)
				{
					jml = jQuery("#grid").jqGrid('getDataIDs');
					var hasil = "";
					for(var u=0;u<jml.length;u++)
					{
						if(jml[u] == "new"){
							hasil = hasil + jml[u];
						}
					}
					
					var ada = hasil.search('new');
					if(ada != -1){
						alert('Input SKPD belum tersimpan..!!');
					}
					else{
						jQuery('#grid').jqGrid('restoreRow', last);
						jQuery("#grid").jqGrid('addRowData', "new", true, 'after', id);
						jQuery('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
					}
				}
				else
				{
					jml = jQuery("#grid").jqGrid('getDataIDs');
					pos = jml.length - 1;
					if(jml[pos] == "new"){
						alert('Input SKPD belum tersimpan..!!');
					}
					else{
						jQuery("#grid").jqGrid('addRowData', "new", true);
						jQuery('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
					}
						
				}
				last = null;
			}else{
				alert('Tidak bisa tambah data');
			}
		}
		
		function edit_row(id){
			if(data_dasar=='3'){
				jQuery('#grid').jqGrid('restoreRow', last);
				jQuery('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
				last = id;
			}else{
				alert('Tidak bisa ubah data');
			}
		}
		
		function del_row(id){
			if(data_dasar=='3'){
				var rt = jQuery("#grid").jqGrid('getRowData', id); 
				var answer = confirm('Hapus dari daftar?');
				if(answer == true)
				{
					jQuery.ajax({
						url: '<?php echo base_url()?>dinas/hapus', 
						data: { id: id},
						success: function(response){
              var msg = jQuery.parseJSON(response);
              var len = msg.message.length;
              for (var i=0; i<len; i++) {
                $.pnotify({
                  title: msg.isSuccess[i] ? 'Sukses' : 'Gagal',
                  text: msg.message[i],
                  type: msg.isSuccess[i] ? 'info' : 'error'
                });
                if(msg.isSuccess[i] == true) {
                  jQuery("#grid").jqGrid('delRowData', id);
                }
              }
							jQuery('#grid').trigger('reloadGrid');
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
				jQuery('#grid').jqGrid('restoreRow', last);
				last = null;
			}
		}

		function aftersavefunc(id, resp){
			console.log('aftersavefunc');
			var msg = jQuery.parseJSON(resp.responseText);
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});

			if(msg.id &&  msg.id != id)
			jQuery("#"+id).attr("id", msg.id);
			jQuery('#grid').trigger('reloadGrid');
		}
		
		function errorfunc(id, resp){
			var msg = jQuery.parseJSON(resp.responseText);
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});

			jQuery('#grid').jqGrid('restoreRow', last);
			jQuery('#grid').trigger("reloadGrid");
		}
		
		jQuery('#filter').click(function(){
			var field 	= jQuery("#field").val();
			var oper 	= jQuery("#oper").val();
			var string 	= jQuery("#string").val();
			
			var grid = jQuery("#grid");
			var postdata = grid.jqGrid('getGridParam','postData');
			jQuery.extend (postdata,
						   {filters:'',
							searchField: field,
							searchOper: oper,
							searchString: string});
			grid.jqGrid('setGridParam', { search: true, postData: postdata });
			grid.trigger("reloadGrid",[{page:1}]);
		}); 
		
		jQuery('#string').keypress(function (e) {
			if (e.which == 13) {
				jQuery('#filter').click();
			}
		});
					
	});
	</script>