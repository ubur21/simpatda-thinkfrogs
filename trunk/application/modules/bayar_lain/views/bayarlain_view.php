<fieldset>
  <legend><?php echo $breadcrumbs;?></legend>
</fieldset>

<table id="grid"></table>
<div id="pager"></div>

<script>
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();

  $("#grid").jqGrid({
    url:root+modul+'/get_daftar',
    datatype:'json',
    mtype:'POST',
    colNames:['Tanggal Bayar', 'Kode SKPD', 'Nama SKPD', 'Kode Rekening', 'Nama Rekening', 'Nama Penyetor', 'Alamat', 'Jumlah Bayar'],
    colModel:[
        {name:'tgl', width:110, formatter:'date', align:'center'},
        {name:'kd_skpd', width:110},
        {name:'nm_skpd', width:200},
        {name:'kd_rek', width:110},
        {name:'nm_rek', width:200},
        {name:'nama', width:150},
        {name:'alamat', width:200},
        {name:'jml_bayar', width:150, formatter:'currency', align:'right'},
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
    sortname:'tgl',
    sortorder:'desc',
    ondblClickRow:edit_row
  });

  $("#grid").jqGrid('bindKeys', {
    
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:true,
    addtext: 'Tambah',
    addfunc:function(){
      location.href = root+modul+'/form';
    },
    edit:true,
    edittext: 'Ubah',
    editfunc: edit_row,
    del:true,
    deltext: 'Hapus',
    delfunc: del_row,
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