<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>penyetoran/proses">
  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: id_skpd" >
      <label class="control-label" for="kode_skpd">SKPD</label>
      <input type="text" class="span2" id="kd_skpd" readonly="1" data-bind="value: kd_skpd, executeOnEnter: pilih_skpd" required />
      <div class="controls span8 input-append">
        <input type="text" class="span8" id="nm_skpd" readonly="1" data-bind="value: nm_skpd, executeOnEnter: pilih_skpd" />
        <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_skpd" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row" >
    <div class="control-group pull-left" data-bind="validationElement: nosts" >
      <label class="control-label" for="no">Nomor STS</label>
      <input type="text" id="nosts" class="span3" data-bind="value: nosts" required />
    </div>
    <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tgl" >
      <label class="control-label" for="tgl">Tanggal</label>
      <input type="text" class="span2 datepicker" id="tgl" data-bind="value: tgl" required />
    </div>
  </div>

  <table id="grid"></table>
  <div id="pager"></div>

</form>

<div class="controls-row pull-right">
  <input type="button" value="Sebelumnya" class="btn btn-primary" data-bind="click: prev" />
  <input type="button" value="Berikutnya" class="btn btn-primary" data-bind="click: next" />
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canSave, click: function(data, event){save(false, data, event) }" />Simpan</button>
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canSave">
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" data-bind="enable: canSave, click: function(data, event){save(true, data, event) }" >Simpan & Buat Baru</a></li>
    </ul>
  </div>
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
  <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
</div>

<script>
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();
});

  var purge = [];

  $("#grid").jqGrid({
    url:'',
    datatype:'local',
    mtype:'POST',
    colNames:['', 'Kode Rekening', 'Nama Rekening', 'Jumlah'],
    colModel:[
        {name:'idrek', hidden:true},
        {name:'kdrek', width:100, sortable:false},
        {name:'nmrek', width:300, sortable:false},
        {name:'nom', width:150, sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
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
    var $list = $(this),
        option = {multi:1, mode:'penyetoran', id_skpd:App.id_skpd()};
        
    Dialog.pilihRekening(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        addRowSorted($list, {'id':'idrek', 'sortName':['kdrek']}, {'idrek':rs.idrek, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek, 'nom':rs.nom});
      }
    });
   
  }

  function del_row(id){
    var rs = {}, answer, kode;
    rs = $(this).jqGrid('getRowData', id);
    kode = rs.kdrek;
    answer = confirm('Hapus ' + kode + ' dari daftar?');

    if(answer == true){
      purge.push(rs.idrek);
      $(this).jqGrid('delRowData', id);
      jumlahAll();
    }
  }  
  
  function jumlahAll()
  {
		var totalData = $('#grid').jqGrid("getDataIDs"),
			totalRows = totalData.length,
			totalNom = 0;
		for (i=0; i<totalRows; i++)
		{
			sd = $('#grid').jqGrid('getRowData', totalData[i]);
      totalNom += parseFloat(sd.nom);
		}
    $('#grid').jqGrid('footerData','set',{nmrek:'Jml Seluruhnya',nom:totalNom});
    App.jml_nom(totalNom);
  }
  

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelPenyetoran = function (){
    var self = this;
    self.modul = 'penyetoran';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_PENYETORAN']) ? $data['ID_PENYETORAN'] : 0 ?>');
    self.id_skpd = ko.observable('<?php echo isset($data['ID_SKPD']) ? $data['ID_SKPD'] : 0 ?>');
    self.kd_skpd = ko.observable('<?php echo isset($data['KODE_SKPD']) ? $data['KODE_SKPD'] : '' ?>')
      .extend({
        required: {params: true, message: 'Kode SKPD tidak boleh kosong'},
      });
    self.nm_skpd = ko.observable('<?php echo isset($data['NAMA_SKPD']) ? $data['NAMA_SKPD'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nama SKPD tidak boleh kosong'},
      });
    self.nosts = ko.observable('<?php echo isset($data['NO_STS']) ? $data['NO_STS'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nomor STS tidak boleh kosong'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y'); ?>')
      .extend({
        required: {params: true, message: 'Tanggal tidak boleh kosong'},
      });
    self.jml_nom = ko.observable(0);

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

  var App = new ModelPenyetoran();

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
    var grid = $('#grid'), errmsg = [];
    
    if (grid.jqGrid('getDataIDs').length === 0) {
      errmsg.push('Detail Rekening tidak boleh kosong.');
    }

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
        data['rincian'] = JSON.stringify($('#grid').jqGrid('getRowData'));
        data['purge'] = purge;

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
  
  App.init_grid = function(){
    var grid = $('#grid');

    if (App.id() > 0){
      grid.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/rinci/' + App.id(), 'datatype': 'json'});
      grid.trigger('reloadGrid');
    }
    else {
      grid.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }
  }

  App.pilih_skpd = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'penyetoran'};
    Dialog.pilihSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_skpd(rs.id);
      App.kd_skpd(rs.kode);
      App.nm_skpd(rs.nama);
    });
  }

  ko.applyBindings(App);
  setTimeout(function(){
    App.init_grid();
  }, 500)
</script>