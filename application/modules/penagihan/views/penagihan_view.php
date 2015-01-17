<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="">
  <div class="controls-row" >
    <div class="control-group pull-left">
      <label class="control-label" for="tgl1">Periode Batas Bayar</label>
      <input type="text" class="datepicker span2" id="tgl1" data-bind="value: tgl1" required />
    </div>
    <div class="control-group pull-left" style="margin-left:20px">
      <label class="control-label" for="tgl2">Sampai dengan</label>
      <input type="text" class="datepicker span2" id="tgl2" data-bind="value: tgl2" required />
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-left" >
      <label class="control-label" for="kd_rek">Pilih Pajak/Retribusi</label>
      <input type="text" class="span2" id="kd_rek" readonly="1" data-bind="value: kd_rek, executeOnEnter: pilih_rekening" required />
      <div class="controls span8 input-append">
        <input type="text" class="span8" id="nm_rek" readonly="1" data-bind="value: nm_rek, executeOnEnter: pilih_rekening" required />
        <span class="add-on" data-bind="click: pilih_rekening" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <table id="grid"></table>
  <div id="pager"></div>

</form>

<div class="controls-row pull-right">
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canPrint, click: print" >Cetak</button>
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canPrint">
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#">PDF</a></li>
      <li><a href="#">XLS</a></li>
    </ul>
  </div>
</div>

<script>
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);

  $('.datepicker#tgl1').datepicker({
    onSelect: function(selected,evnt) {
			$('.datepicker#tgl2').datepicker("option", "minDate", selected);
      App.tgl1(selected);
      getnpwpd();
    }
  });

  $('.datepicker#tgl2').datepicker({
    onSelect: function(selected,evnt) {
      App.tgl2(selected);
      getnpwpd();
    },
		minDate:$('.datepicker#tgl1').val(),
  });
});

  $("#grid").jqGrid({
    url:'',
    datatype:'local',
    mtype:'POST',
    colNames:['ID', 'Tgl Penetapan', 'Tgl Batas Bayar', 'Nama Wajib Pajak', 'Alamat', 'Periode Awal', 'Periode Akhir', 'Jumlah'],
    colModel:[
        {name:'idspt', hidden:true},
        {name:'tgl_penetapan', width:100, formatter:'date', align:'center'},
        {name:'tgl_batas', width:100, formatter:'date', align:'center'},
        {name:'nama_wp', width:150},
        {name:'alamat', width:200},
        {name:'awal', width:100, formatter:'date', align:'center'},
        {name:'akhir', width:100, formatter:'date', align:'center'},
        {name:'jml', width:120, formatter:'currency', align:'right'},
    ],
    pager:'#pager',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    recordtext:'{2} baris',
    width:935,
    height:250,
    footerrow:true,
    gridComplete:function(){
      setTimeout(function(){ jumlahAll(); }, 500);
    }
  });

  $("#grid").jqGrid('bindKeys', {
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:false,
    edit:false,
    del:false,
    search:false,
    refresh:false,
  },{},{},{},{});
  
  function getnpwpd()
  {
    var $list = $('#grid');
    
    $.ajax({
      url: root+modul+'/getnpwpd',
      type: 'post',
      dataType: 'json',
      data: {idrek: App.idrek, tgl1: App.tgl1(), tgl2: App.tgl2()},
      success: function(response){
        var result = response.rows;

        // hapus dulu isi grid
         var rowIds = $list.jqGrid('getDataIDs');
        for(var i=0,len=rowIds.length;i<len;i++){
          var currRow = rowIds[i];
          $list.jqGrid('delRowData', currRow);
        }
        $list.trigger('reloadGrid');
        
        if (result !== undefined) {
          for (i = 0; i < result.length; i++){
            $list.jqGrid('addRowData', 'idspt', [{'idspt':result[i]['id_spt'], 'tgl_penetapan':result[i]['tgl_penetapan'], 'tgl_batas':result[i]['tgl_batas'], 'nama_wp':result[i]['nama_wp'],  'alamat':result[i]['alamat_wp'], 'awal':result[i]['awal'], 'akhir':result[i]['akhir'], 'jml':result[i]['jumlah']}]);
          }
        }
      }
    });
  }
  
  function jumlahAll()
  {
    var $list = $("#grid"),
        sum = $list.jqGrid('getCol', 'jml', false, 'sum');
    $list.jqGrid('footerData','set',{akhir:'Jml Seluruhnya',jml:sum});
  }

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelPenagihan = function (){
    var self = this;
    self.modul = 'Penagihan';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable();
    self.tgl1 = ko.observable('<?php echo date('d/m/Y') ?>');
    self.tgl2 = ko.observable('<?php echo date('d/m/Y') ?>');
    self.idrek = ko.observable(0);
    self.kd_rek = ko.observable('');
    self.nm_rek = ko.observable('');

    self.title = ko.observable('<?php echo $breadcrumbs;?>');

    self.canPrint = ko.computed(function(){
      return self.akses_level() >= 2;
    });

    self.errors = ko.validation.group(self);
 
  }

  var App = new ModelPenagihan();

  App.print = function(){
    preview({"tipe":"form", "id": App.id()});
  }

  App.pilih_rekening = function(){
    var option = {multi:0, mode:'penagihan'};
    
    Dialog.pilihRekening(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.idrek(rs.idrek);
      App.kd_rek(rs.kdrek);
      App.nm_rek(rs.nmrek);
      
      getnpwpd();
      
    });
  }

  ko.applyBindings(App);
</script>