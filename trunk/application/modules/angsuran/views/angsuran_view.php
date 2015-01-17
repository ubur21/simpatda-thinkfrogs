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
    colNames:['Nomor Kohir', 'Jenis Pajak/Retribusi', 'Nama WP', 'Jumlah Pajak', 'Angsuran', 'Sisa'],
    colModel:[
        {name:'kohir', width:100, align:'center'},
        {name:'jenis', width:150},
        {name:'nama_wp', width:200},
        {name:'pajak', width:150, formatter:'currency', align:'right'},
        {name:'angsuran', width:150, formatter:'currency', align:'right'},
        {name:'sisa', width:150, formatter:'currency', align:'right'},
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
    sortname:'kohir',
    sortorder:'desc',
    ondblClickRow:rincian_angsuran
  });

  $("#grid").jqGrid('bindKeys', { });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:true,
    addtext: 'Tambah',
    addfunc:function(){
      location.href = root+modul+'/form';
    },
    edit:true,
    editfunc: rincian_angsuran,
    edittext: 'Rincian',
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

  function rincian_angsuran(id){
    location.href = root+modul+'/form/'+id;
  }

  function print_list(doc){
    preview({"tipe":"daftar", "format":doc});
  }

});
</script>