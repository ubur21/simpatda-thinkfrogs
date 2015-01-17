<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>pembayaran/proses">
  <div class="controls-row">
    <div class="control-group input-append" >
      <label class="control-label" for="namawp">Wajib Pajak/Retribusi</label>
      <input type="text" id="namawp" class="span10" data-bind="value: namawp" required />
      <span class="add-on" data-bind="click: pilih_spt" ><i class="icon-folder-open"></i></span>
    </div>
  </div>

  <div class="controls-row" >
    <div class="control-group input-append" >
      <label class="control-label" for="pajak">Jenis Pajak/Retribusi</label>
      <input type="text" id="pajak" class="span10" data-bind="value: pajak" required />
      <span class="add-on" data-bind="click: pilih_pajak" ><i class="icon-folder-open"></i></span>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group" >
      <label class="control-label" for="tgl">Tanggal Pembayaran</label>
      <input type="text" class="span2 datepicker" id="tgl" data-bind="value: tgl" required />
    </div>
  </div>

  <table id="grid"></table>
  <div id="pager"></div>

</form>

<div class="controls-row pull-right">
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canSave, click: function(data, event){save(false, data, event) }" />Simpan</button>
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canSave">
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" data-bind="enable: canSave, click: function(data, event){save(true, data, event) }" >Simpan & Buat Baru</a></li>
    </ul>
  </div>
  <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
</div>

<script>
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();
});


  $("#grid").jqGrid({
    url:'',
    datatype:'local',
    mtype:'POST',
    colNames:['', 'No. Kohir', 'Rekening', 'Masa Awal', 'Masa Akhir', 'Jumlah', 'Jumlah Setor', 'Denda', 'Total', 'Sisa'],
    colModel:[
        {name:'idspt', hidden:true},
        {name:'kohir', width:100},
        {name:'rek', width:150},
        {name:'awal', width:100, formatter:'date', align:'center'},
        {name:'akhir', width:100, formatter:'date', align:'center'},
        {name:'jml', width:100, formatter:'currency', align:'right'},
        {name:'setor', width:100, formatter:'currency', align:'right'},
        {name:'denda', width:100, formatter:'currency', align:'right'},
        {name:'total', width:100, formatter:'currency', align:'right'},
        {name:'sisa', width:100, formatter:'currency', align:'right'},
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
  });

  $("#grid").jqGrid('bindKeys', {
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:true,
    addtext: 'Tambah',
    addfunc:add_row,
    edit:false,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    search:false,
    refresh:false,
  },{},{},{},{});

  function add_row(){

  }

  function del_row(){

  }

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelPembayaran = function (){
    var self = this;
    self.modul = 'pembayaran';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_WAJIB_PAJAK']) ? $data['ID_WAJIB_PAJAK'] : 0 ?>');
    self.namawp = ko.observable('<?php echo isset($data['NAMA_WP']) ? $data['NAMA_WP'] : '' ?>');
    self.idrek = ko.observable('<?php echo isset($data['ID_REKENING']) ? $data['ID_REKENING'] : '' ?>');
    self.pajak = ko.observable('<?php echo isset($data['NAMA_REKENING']) ? $data['NAMA_REKENING'] : '' ?>');
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? $data['TANGGAL'] : '' ?>');

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = '<?php echo isset($header) ? $header : '' ?>';

    self.isEdit = ko.computed(function(){
      return self.mode() === 'edit';
    });

    self.canPrint = ko.computed(function(){
      return self.akses_level() >= 2 && self.mode() === 'edit';
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);

  }

  var App = new ModelPembayaran();

  App.prev = function(){
    show_prev(modul, App.id());
  }

  App.next = function(){
    show_next(modul, App.id());
  }

  App.print = function(){
    preview({"tipe":"form", "id": App.id()});
  }

  App.back = function(){
    location.href = root+modul;
  }

  App.formValidation = function(){
    if (!App.isValid()){
      errmsg.push('Ada kolom yang belum diisi dengan benar. Silakan diperbaiki.');
      App.errors.showAllMessages();
    }

    if (errmsg.length > 0) {
      $.pnotify({
        title: 'Perhatian',
        text: errmsg.join('</br>'),
        type: 'warning'
      });
      return false;
    }
    return true;
  }

  App.save = function(createNew){
    if (!App.formValidation()){ return }

    var $frm = $('#frm'),
        data = JSON.parse(ko.toJSON(App));

    $.ajax({
      url: $frm.attr('action'),
      type: 'post',
      dataType: 'json',
      data: data,
      success: function(res, xhr){
        if (res.isSuccess){
          if (res.id) App.id(res.id);
          App.init_grid();
        }

        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });

        if (createNew) location.href = root+modul+'/form/';
      }
    });
  }
  
  App.pilih_spt = function(){;
  }

  App.pilih_pajak = function(){;
  }

  ko.applyBindings(App);
</script>