  <fieldset>
    <legend>Daftar Tarif Air Tanah</legend>
      <select name="field" id="field" class="span2">
		<option value='nama'>Uraian</option>
		<option value='kode'>Kode</option>
		<option value='nom'>Tarif</option>
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
  $("#grid").jqGrid({
    url:'<?php echo $daftar_url?>',
    editurl:'<?php echo $ubah_url?>',
    datatype:'json',
    mtype:'POST',
    colNames:['Kode', 'Uraian', 'Tarif'],
    colModel:[
        {name:'kode', width:150, editable:true, edittype:'text', editoptions:{size:5,class:'span2'}, editrules:{required:true}},
        {name:'nama', width:550, editable:true, edittype:'text', editoptions:{size:100,class:'span7'}, editrules:{required:true}},
        {name:'nom', width:150, editable:true, edittype:'text', editoptions:{size:50,class:'span2'}, editrules:{required:true, number:true}},
    ],
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    pager:'#pager',
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    width:930,
    height:250,
  });

  Grid.init({
    grid: '#grid',
    pager: '#pager',
    akses: <?php echo isset($akses) ? $akses : 0; ?>
  });
});

Grid = (function(){
  var options = {
    grid: '#grid',
    pager: '#pager',
    akses: 0
  };

  var self = this, grd, $grd, pgr, last, akses, edit_url;

  initialize = function(opt){
    for(var prop in opt){
      if(opt.hasOwnProperty(prop)){
          options[prop] = opt[prop];
      }
    }

    grd = options.grid;
    $grd = $(grd);
    pager = options.pager;
    akses = options.akses;

    $grd.jqGrid('setGridParam', {
      onSelectRow: self.restore_row,
      ondblClickRow: self.edit_row,
    });
    $grd.jqGrid('bindKeys', { 'onEnter': self.edit_row});
    $grd.jqGrid('navGrid', pager,{
      add: akses === 3,
      addtext: 'Tambah',
      addfunc: self.add_row,
      edit: akses === 3,
      edittext: 'Ubah',
      editfunc: self.edit_row,
      del: akses === 3,
      deltext:'Hapus',
      delfunc: self.del_row,
      search: false,
      refresh: true,
      refreshtext:'Refresh',
    },{},{},{},{});

    edit_url = $grd.jqGrid('getGridParam', 'editurl');
  }

  aftersavefunc = function(id, resp){
    var o = $.parseJSON(resp.responseText),
        newid = o.id,
        $t = $grd[0],
        ind = $grd.jqGrid("getInd", id,true);

    $.pnotify({
      title: o.isSuccess ? 'Sukses' : 'Gagal',
      text: o.message,
      type: o.isSuccess ? 'info' : 'error'
    });

    $(ind).attr("id", newid);
    if ($t.p.selrow === id) {
      $t.p.selrow = newid;
    }
    if ($.isArray($t.p.selarrrow)) {
      var i = $.inArray(id, $t.p.selarrrow);
      if (i>=0) {
        $t.p.selarrrow[i] = newid;
      }
    }
    if ($t.p.multiselect) {
      var newCboxId = "jqg_" + $t.p.id + "_" + newid;
      $("input.cbox",ind)
        .attr("id", newCboxId)
        .attr("name", newCboxId);
    }

    $grd.jqGrid('setRowData', newid, {'id':newid});
    $('#' + newid).removeClass('jqgrid-new-row');
    last = newid;
    $grd.focus();
    $grd.setSelection(newid);
  }

  successfunc = function(resp){
    var o = $.parseJSON(resp.responseText);

    return o.isSuccess;
  }

  errorfunc = function(id, resp){
    var o = $.parseJSON(resp.responseText);
    $.pnotify({
        title: 'Gagal',
        text: o.message ? o.message : 'Server tidak bisa diakses',
        type: resp.status = 200 ? 'warn' : 'error'
      });
    return false;
  }

  add_row = function(){
    /* hanya boleh jika punya hak akses */
    if (akses !== 3) return;

    restore_row(last);
    $grd.jqGrid('addRow', {
      'rowID': 'new',
      'position': 'last',
      'addRowParams':{
        'keys':true,
        'successfunc':successfunc,
        'aftersavefunc':aftersavefunc,
        'errorfunc':errorfunc,
        'restoreAfterError':false,
      }
    });

    last = 'new';
  }

  edit_row = function(id){
    /* hanya boleh jika punya hak akses */
    if (akses !== 3) return;

    restore_row(last);
    $grd.jqGrid('editRow', id, true, null, successfunc, null, null, aftersavefunc, errorfunc, null);
    last = id;
  }

  del_row = function(id){
    /* hanya boleh jika punya hak akses */
    if (akses !== 3) return;

    var ans = confirm('Hapus dari daftar?');

    if (ans === true){
      $.ajax({
        url: edit_url,
        data: {id:id, oper:'del'},
        type: 'post',
        dataType: 'json',
        success: function(res, xhr){
          if (res.isSuccess){
            $grd.jqGrid('delRowData', id);
          }

          $.pnotify({
            title: res.isSuccess ? 'Sukses' : 'Gagal',
            text: res.message,
            type: res.isSuccess ? 'info' : 'error'
          });
        }
      });
    }
  }

  restore_row = function(id){
    if(id && id !== last){
      $grd.jqGrid('restoreRow', last);
      last = null;
    }
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
  
  return {
    init: initialize,
    add: add_row,
    edit: edit_row,
    del: del_row,
    restore: restore_row,
  }
}());

</script>