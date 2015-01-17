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
    url:root+modul+'<?php echo $link_daftar;?>',
    datatype:'json',
    mtype:'POST',
    colNames:['Nomor SPTD', 'Tanggal', 'Periode Awal', 'Periode Akhir', 'Pajak/Retribusi', 'Wajib Pajak', 'Lokasi', 'Jumlah Pajak'],
    colModel:[
        {name:'no', width:150},
        {name:'tgl', width:80, formatter:'date', align:'center'},
        {name:'awal', width:80, formatter:'date', align:'center'},
        {name:'akhir', width:80, formatter:'date', align:'center'},
        {name:'pajak', width:150},
        {name:'wp', width:200},
        {name:'lokasi', width:100},
        {name:'nom', width:100, formatter:'currency', align:'right'},
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
      location.href = root+modul+'<?php echo $link_form;?>';
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
    location.href = root+modul+'<?php echo $link_form;?>/'+id;
  }

  function del_row(id){
    var answer = confirm('Hapus dari daftar?');
    if(answer == true){
      $.ajax({
        type: "post",
        dataType: "json",
        url: root+modul+'/hapus',
        data: {id: id, tipe: '<?php echo $tipe;?>'},
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