  <fieldset>
    <legend>Daftar Kecamatan</legend>
      <select name="field" id="field" class="span2">
		<option value='nama'>Nama Kecamatan</option>
		<option value='kode'>Kode Kecamatan</option>
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
  
  jQuery("#grid").jqGrid({
    url:'<?php echo $daftar_url?>',
    editurl:'<?php echo $ubah_url?>',
    datatype:'json',
    mtype:'POST',
    colNames:['', 'Kode', 'Nama Kecamatan'],
    colModel:[
        {name:'id', index:'id', edittype:'text', hidden:true},
        {name:'kode', index:'kode', width:150, editable:true, edittype:'text', editoptions:{size:3,class:'span2'}, editrules:{required:true}},
        {name:'nama', index:'nama', width:700, editable:true, edittype:'text', editoptions:{size:100,class:'span9'}, editrules:{required:true}}
    ],
    rowNum:10,
    rowList:[10,20,30],
    rownumbers:true,
    pager:'#pager',
    sortorder:'asc',
    sortname:'kode',
    viewrecords:true,
    gridview:true,
    width:930,
    height:250,
    
    /************************************start kelurahan**************************************************/
    subGrid:true,
    subGridRowExpanded: function(subgrid_id,row_id){
      var ret = jQuery("#grid").jqGrid('getRowData',row_id);
      var tableKelurahan,pagerKelurahan;
      tableKelurahan = subgrid_id+"_t";
      pagerKelurahan = "p_"+tableKelurahan;
      jQuery("#"+subgrid_id).html("<table id='"+tableKelurahan+"' class='scroll'></table><div id='"+pagerKelurahan+"' class='scroll'></div>");
      jQuery("#"+tableKelurahan).jqGrid({
        url:'<?php echo base_url()?>kecamatan/get_daftar_kelurahan'+'/'+ret.id,
        editurl:'<?php echo base_url()?>kecamatan/proses_form_kelurahan',
        datatype:'json',
        mtype:'POST',
        colNames:['ID Kelurahan', 'Kode', 'Nama Kelurahan'],
        colModel:[
            {name:'id_kel', index:'id_kel', edittype:'text', hidden:true},
            {name:'kode_kel', index:'kode_kel', width:110, editable:true, edittype:'text', editoptions:{size:3,class:'span2'}, editrules:{required:true}},
            {name:'nama_kel', index:'nama_kel', width:500, editable:true, edittype:'text', editoptions:{size:100,class:'span8'}, editrules:{required:true}}
        ],
        rowNum:10,
        rowList:[10,20,30],
        rownumbers:true,
        pager:"#"+pagerKelurahan,
        sortorder:'asc',
        viewrecords:true,
        gridview:true,
        height:'100%',
        autowidth:true,
        ondblClickRow:edit_row_kelurahan,
        onSelectRow:restore_row_kelurahan,
      });
      jQuery("#"+tableKelurahan).jqGrid( 'navGrid', "#"+pagerKelurahan, { 
        add: true,
        addtext: 'Tambah',
        addfunc: append_row_kelurahan,
        edit: true,
        edittext: 'Ubah',
        editfunc: edit_row_kelurahan,
        del: true,
        deltext: 'Hapus',
        delfunc: del_row_kelurahan,
        search: false,
        searchtext: 'Cari',
        refresh: true,
        refreshtext: 'Refresh'
      });
      function append_row_kelurahan(){
        if(data_dasar=='3'){
          var ret = jQuery("#grid").jqGrid('getRowData',row_id);
          var data = {id_kec:row_id};
          if(row_id != 'new')
          {
            jml = jQuery("#"+tableKelurahan).jqGrid('getDataIDs');
            pos = jml.length - 1;
            if(jml[pos] == "new"){
              alert('Input Kelurahan belum tersimpan..!!');
            }
            else{
              jQuery("#"+tableKelurahan).jqGrid('restoreRow', last);
              jQuery("#"+tableKelurahan).jqGrid('addRowData', "new", data);
              jQuery("#"+tableKelurahan).jqGrid('editRow', "new", true, null, null, null, data, aftersavefunc_kelurahan, errorfunc_kelurahan, null);
            }
            last=null;
          }
          else
          {
            alert('Silahkan input kelurahan terlebih dahulu.');
          }
        }else{
          alert('Tidak bisa tambah data');
        }
      }

      function edit_row_kelurahan(id){
        if(data_dasar=='3'){
          jQuery("#"+tableKelurahan).jqGrid('restoreRow', last);
          jQuery("#"+tableKelurahan).jqGrid('editRow', id, true, null, null, null, null, aftersavefunc_kelurahan, errorfunc_kelurahan, null);
          last = id;
        }else{
          alert('Tidak bisa ubah data');
        }
      }
      
      function del_row_kelurahan(id){
        if(data_dasar=='3'){
          var answer = confirm('Hapus Kelurahan dari daftar?');
          if(answer == true)
          {							
            jQuery.ajax({
              url: '<?php echo base_url()?>kecamatan/hapus_kelurahan', 
              data: { id: id},
              success: function(response){
                var msg = jQuery.parseJSON(response);
                $.pnotify({
                  title: msg.isSuccess ? 'Sukses' : 'Gagal',
                  text: msg.message,
                  type: msg.isSuccess ? 'info' : 'error'
                });
                if(msg.isSuccess == true) {
                  jQuery("#"+tableKelurahan).jqGrid('delRowData', id);
                }
                jQuery("#"+tableKelurahan).trigger('reloadGrid');
              },
              type: "post", 
              dataType: "html"
            });
          }
        }else{
          alert('Tidak bisa hapus data');
        }
      }

      function restore_row_kelurahan(id){
        if(id && id !== last){
//						jQuery("#"+tableKelurahan).jqGrid('restoreRow', last);
          jQuery("#"+tableKelurahan).jqGrid('saveRow', last, aftersavefunc, null, null, null, errorfunc, null);
          last = null;
        }
        
        var id = jQuery("#"+tableKelurahan).jqGrid('getGridParam','selrow');
        var ret = jQuery("#"+tableKelurahan).jqGrid('getRowData',id);
        var ID_KECAMATAN = ret.id_kec;
        jQuery.post("<?php echo base_url()?>kecamatan/session_id",{
          'ID_KECAMATAN':ID_KECAMATAN
          },function(data){
        });					
			}

      function aftersavefunc_kelurahan(id, resp){
        console.log('aftersavefunc');
        var msg = jQuery.parseJSON(resp.responseText);
        $.pnotify({
          title: msg.isSuccess ? 'Sukses' : 'Gagal',
          text: msg.message,
          type: msg.isSuccess ? 'info' : 'error'
        });
        if(msg.id &&  msg.id != id)
        jQuery("#"+id).attr("id", msg.id);
        jQuery('#'+tableKelurahan).trigger('reloadGrid');					
      }

      function errorfunc_kelurahan(id, resp){
        var msg = jQuery.parseJSON(resp.responseText);
        if(msg.error)
          $.pnotify({
            title: 'Gagal',
            text: msg.error,
            type: 'error'
          });
        jQuery('#'+tableKelurahan).trigger('reloadGrid');
      }
    },
        
    /************************************end sub kelurahan**************************************************/				
    ondblClickRow:edit_row,
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
      
      jml = jQuery("#grid").jqGrid('getDataIDs');
      pos = jml.length - 1;
      if(jml[pos] == "new"){
        alert('Input Tipe belum tersimpan..!!');
      }
      else{
        jQuery('#grid').jqGrid('restoreRow', last);
        jQuery("#grid").jqGrid('addRowData', "new", true);
        jQuery('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
      }
      last=null;
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
      var answer = confirm('Hapus Kecamatan dari daftar?');
      if(answer == true)
      {					
        jQuery.ajax({
          url: '<?php echo base_url()?>kecamatan/hapus', 
          data: { id: id},
          success: function(response){
            var msg = jQuery.parseJSON(response);
            $.pnotify({
              title: msg.isSuccess ? 'Sukses' : 'Gagal',
              text: msg.message,
              type: msg.isSuccess ? 'info' : 'error'
            });
            if (msg.isSuccess == true) {
              jQuery('#grid').jqGrid('delRowData', id);
            }
            jQuery('#grid').trigger('reloadGrid');
          },
          type: "post", 
          dataType: "html"
        });
      }
    }else{
      alert('Tidak bisa delete data');
    }	
  }
  
  function restore_row(id){
    if(id && id !== last){
      jQuery('#grid').jqGrid('restoreRow', last);
      last = null;
    }
    
    var id = jQuery('#grid').jqGrid('getGridParam','selrow');
    var ret = jQuery('#grid').jqGrid('getRowData',id);
    var ID_KECAMATAN = ret.id;
    
    jQuery.post("<?php echo base_url()?>kecamatan/session_id",{
      'ID_KECAMATAN':ID_KECAMATAN
      },function(data){
    });
    
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
    jQuery('#grid').trigger("reloadGrid");
  }

  function errorfunc(id, resp){
    var msg = jQuery.parseJSON(resp.responseText);
    if(msg.error)
      $.pnotify({
        title: 'Gagal',
        text: msg.error,
        type: 'error'
      });	
      jQuery('#message').addClass('red');
      jQuery('#grid').jqGrid('restoreRow', id);
      jQuery('#grid').trigger("reloadGrid");
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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