<fieldset>
  <legend>Data Anggaran</legend>
</fieldset>

<table id="grid"></table>
<div id="pager"></div>

<script>
$(document).ready(function() {

  $("#grid").jqGrid({
    url:root+modul+'/get_daftar',
    datatype:'json',
    mtype:'POST',
    colNames:['Id', 'ID SKPD', 'Tahun', 'Kode SKPD', 'Nama SKPD', 'Pagu Murni', 'Pagu PAK'],
    colModel:[
        {name:'', hidden:true},
        {name:'id_skpd', hidden:true},
        {name:'tahun', width:100},
        {name:'kode_skpd', width:115},
        {name:'nama_skpd', width:400},
        {name:'pagu_murni', width:130, align:'right'},
        {name:'pagu_pak', width:130, align:'right'},
    ],
    pager:'#pager',
    rowNum:10,
    rowList:[10,20,30],
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    width:935,
    height:250,
    ondblClickRow:edit_row
  });

  $("#grid").jqGrid('bindKeys', {
    'onEnter':edit_row
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:true,
    addtext: 'Tambah',
    addfunc:function(){
      location.href = root+modul+'/form';
    },
    edit:true,
    edittext: 'Ubah',
    editfunc:edit_row,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    search:false,
    refresh:true,
    refreshtext:'Refresh',
  },{},{},{},{})
  .navSeparatorAdd('#pager')
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ print_list('pdf') },
    title:'Cetak Daftar (PDF)',
    buttonicon:'ui-icon-pdf',
    position:'last'
  })
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ print_list('xls') },
    title:'Cetak Daftar (XLS)',
    buttonicon:'ui-icon-xls',
    position:'last'
  });
  
  function edit_row(id){
    location.href = root+modul+'/form/'+id;
  }

  function del_row(id){
    var answer = confirm('Hapus dari daftar?');
    rs = $(this).jqGrid('getRowData', id);

    if(answer == true){
      $.ajax({
        type: "post",
        dataType: "json",
        url: root+modul+'/hapus',
        data: {id: id},
        success: function(res) {
          $.pnotify({
            title: res.isSuccess ? 'Sukses' : 'Gagal',
            text: res.message,
            type: res.isSuccess ? 'info' : 'error'
          });
          if (true == res.isSuccess){
            jQuery('#grid').jqGrid('delRowData', id);
            jQuery('#grid').trigger("reloadGrid");
          };
        },
      });
    }
  }

  function print_list(doc){
    preview({"tipe":"daftar", "format":doc});
  }

});
</script>