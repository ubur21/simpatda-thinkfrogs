	<fieldset>
    <legend>Kode Rekening</legend>
  </fieldset>
	<table id="grid"></table>
	<div id="pager"></div>

	<script type="text/javascript">
	$(document).ready(function() {
		var last = newid = 0;	
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;

		// var myelem;
/********************************* REKENING *****************************************************/
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>rekening/get_daftar',
			editurl:'<?php echo base_url()?>rekening/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['ID','T','K','J','O','R','S1','S2','S3','Kode Rekening','Uraian','Tarif Rp.', 'Tarif %'],
			colModel:[
				{name:'id',index:'id',width:50,editable:false,search:false,hidden:true},
				{name:'tipe',index:'tipe',width:50,editable:true,edittype:'text',editoptions:{size:20,class:'span1',value:'4',readonly:true}, editrules:{number:false},sortable:false},
				{name:'kel',index:'kel',width:50,editable:true,edittype:'text',editoptions:{size:20,class:'span1',onchange:'writeKode(id);'}, editrules:{number:false},sortable:false},
				{name:'jenis',index:'jenis',width:50,editable:true,edittype:'text',editoptions:{size:20,class:'span1',onchange:'writeKode(id);'}, editrules:{number:false},sortable:false},
				{name:'objek',index:'objek',width:50,editable:true,edittype:'text',editoptions:{size:20,class:'span1',onchange:'writeKode(id);'}, editrules:{number:false},sortable:false},
				{name:'rinci',index:'rinci',width:50,editable:true,edittype:'text',editoptions:{size:20,class:'span1',onchange:'writeKode(id);'}, editrules:{number:false},sortable:false},
				{name:'sub1',index:'sub1',width:50,editable:true,edittype:'text',editoptions:{size:20,class:'span1',onchange:'writeKode(id);'}, editrules:{number:false},sortable:false},
				{name:'sub2',index:'sub2',width:50,editable:true,edittype:'text',editoptions:{size:20,class:'span1',onchange:'writeKode(id);'}, editrules:{number:false},sortable:false},
				{name:'sub3',index:'sub3',width:50,editable:true,edittype:'text',editoptions:{size:20,class:'span1',onchange:'writeKode(id);'}, editrules:{number:false},sortable:false},
				{name:'kode',index:'kode',width:200,editable:true,edittype:'text',editoptions:{size:40,class:'span3',readonly:true},sortable:false},
				{name:'nama',index:'nama',width:200,editable:true,edittype:'text',editoptions:{size:40,class:'span2'},sortable:false},
				{name:'tarif_rp',index:'tarif_rp',width:80,editable:true,edittype:'text',editoptions:{size:40,class:'span1'},sortable:false},
				{name:'tarif_persen',index:'tarif_persen',width:80,editable:true,edittype:'text',editoptions:{size:40,class:'span1'},sortable:false}
			],
      rowNum:10,
      rowList:[10,20,30],
      rownumbers:true,
			pager:'#pager',
      sortname:'kode',
			sortorder:'asc',
			viewrecords:true,
      rownumbers:true,
			gridview:true,
			width:930,
			height:'240',
			ondblClickRow:edit_row,
			onSelectRow:restore_row,
      gridComplete:RowColor

		});

    $("#grid").jqGrid('bindKeys', { "onEnter": edit_row});
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
						alert('Input Rekening belum tersimpan..!!');
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
						alert('Input Rekening belum tersimpan..!!');
					}
					else{
						jQuery('#grid').jqGrid('restoreRow', last);
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
						url: '<?php echo base_url()?>rekening/hapus', 
						data: { id: id},
						success: function(response){
              var msg = jQuery.parseJSON(response);
              $.pnotify({
                title: msg.isSuccess ? 'Sukses' : 'Gagal',
                text: msg.message,
                type: msg.isSuccess ? 'info' : 'error'
              });
              if(msg.isSuccess == true) {
                jQuery("#grid").jqGrid('delRowData', id);
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
      jQuery.post("<?php echo base_url()?>rekening/session_id",{
        'ID_REKENING':id
        },function(data){
      });
		}

		function aftersavefunc(id, resp){
			console.log('aftersavefunc');
      jQuery('#grid').jqGrid('restoreRow', last);
      $("#grid").jqGrid('bindKeys', { "onEnter": edit_row});
			var msg = jQuery.parseJSON(resp.responseText);
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});

			if(msg.id &&  msg.id != id){
        jQuery("#"+id).attr("id", msg.id);
        setfocusrow(msg.id);
      } else {
        setfocusrow(id);
      }

			jQuery('#grid').trigger('reloadGrid');

      last = null;
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
							
    function setfocusrow(id) {
      setTimeout(function(){
        jQuery('#grid').jqGrid('setSelection',id);
      },500);
    }
    
		function RowColor()
		{
			var idcolor = jQuery('#grid').jqGrid('getDataIDs');
			for(var i=0; i<=idcolor.length; i++)
			{
				var rowcolor = jQuery('#grid').jqGrid('getRowData', idcolor[i]);
				if(rowcolor['rinci'] === '' && idcolor[i] !== 'new')
				{
					var rows = jQuery('#'+idcolor[i], jQuery('#grid')).find('td');
					rows.css("background-color", "#fafad2");
					rows.filter('.ui-sgcollapsed').html('');
				}
			}
		}
    
	});
  

  function writeKode(id) {
    var res = id.split('_'), sel = res[0];
    var new_tipe = jQuery("#"+sel+"_tipe").val(), new_kel = jQuery("#"+sel+"_kel").val(),
        new_jenis = jQuery("#"+sel+"_jenis").val(), new_objek = jQuery("#"+sel+"_objek").val(),
        new_rinci = jQuery("#"+sel+"_rinci").val(), new_sub1 = jQuery("#"+sel+"_sub1").val(),
        new_sub2 = jQuery("#"+sel+"_sub2").val(), new_sub3 = jQuery("#"+sel+"_sub3").val();
    var new_kode = new_tipe;
    if(jQuery.trim(new_kel) != '') {
      new_kode += '.'+new_kel;
    }
    if(jQuery.trim(new_jenis) != '') {
      new_kode += '.'+new_jenis;
    }
    if(jQuery.trim(new_objek) != '') {
      new_kode += '.'+new_objek;
    }
    if(jQuery.trim(new_rinci) != '') {
      new_kode += '.'+new_rinci;
    }
    if(jQuery.trim(new_sub1) != '') {
      new_kode += '.'+new_sub1;
    }
    if(jQuery.trim(new_sub2) != '') {
      new_kode += '.'+new_sub2;
    }
    if(jQuery.trim(new_sub3) != '') {
      new_kode += '.'+new_sub3;
    }
    
    jQuery("#"+sel+"_kode").attr("value","");
    jQuery("#"+sel+"_kode").attr("value",new_kode);
  };

	</script>