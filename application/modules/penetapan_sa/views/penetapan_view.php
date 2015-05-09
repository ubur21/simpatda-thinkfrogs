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
    multiselect:true,
    mtype:'POST',
    colNames:['', 'No Kohir', 'NPWPD', 'Nama WP', 'Rekening', 'Jumlah', 'Tgl Penetapan', 'Batas Bayar'],
    colModel:[
        {name:'idspt', hidden: true},
        {name:'kohir', width:50, align:'center'},
        {name:'npwpd', width:150},
        {name:'nama_wp', width:200},
        {name:'rek', width:150},
        {name:'jumlah', width:100, formatter:'currency', align:'right'},
        {name:'tgl', width:100, formatter:'date', align:'center'},
        {name:'batas', width:100, formatter:'date', align:'center'},
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
    sortorder:'desc'
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:true,
    addtext: 'Tambah',
    addfunc:function(){
      location.href = root+modul+'/form';
    },
    edit:false,
    del:false,
    search:false,
    refresh:true,
    refreshtext:'Refresh',
  },{},{},{},{})
  .navSeparatorAdd('#pager')
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ cetak_skpd() },
    title:'Cetak SKPD',
    buttonicon:'ui-icon-pdf',
    position:'last'
  });

  /*
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ print_list('xls') },
    title:'Cetak Daftar (XLS)',
    buttonicon:'ui-icon-xls',
    position:'last'
  });
*/

  function cetak_skpd(doc){
    var select_jq = $('#grid').jqGrid('getGridParam', 'selarrrow');
    location.href = root+modul+'/generateReport?id='+select_jq;
  }

});
</script>