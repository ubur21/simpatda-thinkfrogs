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
    colNames:['Tanggal Bayar', 'Jenis Pajak/Retribusi', 'Nama WP', 'Nomor Kohir', 'Jumlah Pajak', 'Jumlah Setor', 'Sisa'],
    colModel:[
        {name:'tgl', width:100, formatter:'date', align:'center'},
        {name:'jenis', width:150},
        {name:'nama_wp', width:200},
        {name:'kohir', width:100},
        {name:'pajak', width:100, formatter:'currency', align:'right'},
        {name:'setor', width:100, formatter:'currency', align:'right'},
        {name:'sisa', width:100, formatter:'currency', align:'right'},
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
    sortorder:'desc'
  });

  $("#grid").jqGrid('bindKeys', {

  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:true,
    addtext: 'Tambah',
    addfunc:function(){
      location.href = root+modul+'/form';
    },
    edit:false,
    edittext: 'Ubah',
    del:false,
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

  function print_list(doc){
    preview({"tipe":"daftar", "format":doc});
  }

});
</script>