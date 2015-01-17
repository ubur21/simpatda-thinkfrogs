<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>angsuran/proses">
  <fieldset>
    <div class="controls-row">
      <div class="control-group input-append" data-bind="validationElement: namawp" >
        <label class="control-label" for="namawp">Wajib Pajak/Retribusi</label>
        <input type="text" id="namawp" style="margin-bottom:10px" readonly="1" class="span10" data-bind="value: namawp" required />
        <span class="add-on" data-bind="visible: !isEdit() && canSave(), click: pilih_npwpd" ><i class="icon-folder-open"></i></span>
      </div>
    </div>

    <div class="controls-row" >
      <div class="control-group input-append" data-bind="validationElement: pajak" >
        <label class="control-label" for="pajak">Jenis Pajak/Retribusi</label>
        <input type="text" id="pajak" style="margin-bottom:10px" readonly="1" class="span10" data-bind="value: pajak" required />
        <span class="add-on" data-bind="visible: !isEdit() && canSave(), click: pilih_pajak" ><i class="icon-folder-open"></i></span>
      </div>
    </div>

    <div class="controls-row">
      <div class="control-group"  data-bind="validationElement: tgl" >
        <label class="control-label" for="tgl">Tanggal Pembayaran</label>
        <input type="text" class="span2 datepicker" id="tgl" data-bind="value: tgl" required />
      </div>
    </div>

    <div class="controls-row" >
      <div class="control-group"  data-bind="validationElement: kohir" >
        <label class="control-label" for="kohir">Nomor Kohir</label>
        <input type="text" id="kohir" readonly="1" class="span2" data-bind="value: kohir" required />
      </div>
    </div>
  </fieldset>

  <fieldset class="form-inline">
    <div class="controls-row">
      <legend>Angsuran</legend>
    </div>
    
    <div class="controls-row">
      <label class="control-label span2" for="ketetapan">Jumlah Ketetapan</label>
      <div class="control-group pull-left" data-bind="validationElement: ketetapan" >
        <input type="text" class="span2 currency" readonly="1" id="ketetapan" data-bind="numeralvalue: ketetapan" />
      </div>
      <label class="control-label span2" for="angsuranke">Angsuran ke</label>
      <div class="control-group pull-left" data-bind="validationElement: angsuranke" >
        <input type="text" class="span2" readonly="1" id="angsuranke" data-bind="value: angsuranke" />
      </div>
    </div>

    <div class="controls-row">
      <label class="control-label span2" for="terbayar">Jumlah yang Terbayar</label>
      <div class="control-group pull-left" data-bind="validationElement: terbayar" >
        <input type="text" class="span2 currency" readonly="1" id="terbayar" data-bind="numeralvalue: terbayar" />
      </div>
      <label class="control-label span2" for="sisa_sebelum">Sisa Sebelumnya</label>
      <div class="control-group pull-left" data-bind="validationElement: sisa_sebelum" >
        <input type="text" class="span2 currency" readonly="1" id="sisa_sebelum" data-bind="numeralvalue: sisa_sebelum" />
      </div>
    </div>

    <div class="controls-row">
      <label class="control-label span2" for="angsuran">Jumlah Angsuran</label>
      <div class="control-group pull-left" data-bind="validationElement: angsuran" >
        <input type="text" class="span2 currency" id="angsuran" data-bind="numeralvalue: angsuran, valueUpdate: 'keyup', attr:{readonly : read}" />
      </div>
      <label class="control-label span2" for="sisa_terutang">Sisa Terutang</label>
      <div class="control-group pull-left" data-bind="validationElement: sisa_terutang" >
        <input type="text" class="span2 currency" readonly="1" id="sisa_terutang" data-bind="numeralvalue: sisa_terutang" />
      </div>
    </div>
  </fieldset>

  <fieldset class="form-inline">
    <div class="controls-row">
      <legend>Tabel Angsuran</legend>
    </div>
    <table id="grid"></table>
    <div id="pager"></div>
  </fieldset>

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

  var last = 0;
  var purge = [];

  $("#grid").jqGrid({
    url:'',
    datatype:'local',
    mtype:'POST',
    colNames:['', 'Angsuran ke', 'Tanggal Bayar', 'Jumlah Terbayar', 'Sisa Sebelumnya', 'Angsuran', 'Sisa Terutang'],
    colModel:[
        {name:'id_bayar', hidden:true},
        {name:'angsuranke', width:100},
        {name:'tgl', width:100, formatter:'date', align:'center'},
        {name:'terbayar', width:150, formatter:'currency', align:'right'},
        {name:'sisasebelum', width:150, formatter:'currency', align:'right', editable:true},
        {name:'angsuran', width:150, formatter:'currency', align:'right'},
        {name:'sisaterutang', width:150, formatter:'currency', align:'right'},
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

  $("#grid").jqGrid('bindKeys', {  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:false,
    edit:false,
    del:false,
    search:false,
    refresh:false,
  },{},{},{},{});
  
  function getKohir(){
    $.ajax({
      url: root+modul+'/get_kohir',
      type: 'post',
      dataType: 'json',
      data: {id_wp: App.id_wp(), idpjk : App.idpjk()},
      success: function(response){
        if (response['len'] > 0) {
          App.id_spt(response['id_spt']);
          App.kohir(response['kohir']);
        }else{
          App.id_spt(0);
          App.kohir(0);
        }
        getKetetapan();
        setTimeout(function(){ getAngsuran(); },100);
      }
    });
  }
  
  function getKetetapan(){
    $.ajax({
      url: root+modul+'/get_ketetapan',
      type: 'post',
      dataType: 'json',
      data: {id_spt: App.id_spt()},
      success: function(response){
        if (response['len'] > 0) {
          return App.ketetapan(response['ketetapan']);
        }else{
          return App.ketetapan(0);
        }
      }
    });
  }

  function getAngsuran(){
    var $list = $('#grid');
    
    // hapus dulu isi grid
    var rowIds = $list.jqGrid('getDataIDs');
    for(var i=0,len=rowIds.length;i<len;i++){
      var currRow = rowIds[i];
      $list.jqGrid('delRowData', currRow);
    }
    $list.trigger('reloadGrid');

    $.ajax({
      url: root+modul+'/get_angsuran',
      type: 'post',
      dataType: 'json',
      data: {id_spt: App.id_spt()},
      success: function(response){
        var result = response.rows,
            len = response.len;
        if (len > 0) {
          // add grid dengan data spt sesuai pajak/retribusi
          var jml_terbayar = 0, jml_angsuranke = 1, jml_sisaterutang = App.ketetapan(), sisasebelum = App.ketetapan(), 
              sisaterutang = App.ketetapan();
          for (i = 0; i < len; i++){
            var sisaterutang = parseFloat(sisaterutang) - parseFloat(result[i]['angsuran']);
            $list.jqGrid('addRowData', 'id_bayar', [{'id_bayar':result[i]['id_bayar'], 'angsuranke':result[i]['angsuranke'], 'tgl':result[i]['tgl'],
                'terbayar':result[i]['terbayar'], 'sisasebelum':sisasebelum, 'angsuran':result[i]['angsuran'], 'sisaterutang':sisaterutang}]);
            var sisasebelum = sisasebelum - parseFloat(result[i]['angsuran']),
                jml_terbayar = parseFloat(jml_terbayar) + parseFloat(result[i]['angsuran']),
                jml_angsuranke = jml_angsuranke + 1,
                jml_sisaterutang = (parseFloat(jml_sisaterutang) - parseFloat(result[i]['angsuran']));
          }

          App.terbayar(jml_terbayar);
          App.angsuranke(jml_angsuranke);
          App.sisa_sebelum(sisasebelum);
          App.sisa_terutang(jml_sisaterutang);
        }
        else {
          App.terbayar(0);
          App.angsuranke(1);
          App.sisa_sebelum(0);
          App.sisa_terutang(0);
        }
      }
    });
  }
    
  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelAngsuran = function (){
    var self = this;
    self.modul = 'angsuran';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable(<?php echo isset($data['ID_SPT']) ? $data['ID_SPT'] : 0; ?>);
    self.id_spt = ko.observable(<?php echo isset($data['ID_SPT']) ? $data['ID_SPT'] : 0; ?>);
    self.id_wp = ko.observable(<?php echo isset($data['ID_WAJIB_PAJAK']) ? $data['ID_WAJIB_PAJAK'] : 0; ?>);
    self.namawp = ko.observable('<?php echo isset($data['NAMA_WP']) ? $data['NAMA_WP'] : ''; ?>')
      .extend({
        required: {params: true, message: 'Wajib Pajak/Retribusi tidak boleh kosong'},
      });
    self.tgl = ko.observable('<?php echo date('d/m/Y'); ?>')
      .extend({
        required: {params: true, message: 'Tanggal Pembayaran tidak boleh kosong'},
      });
    self.idpjk = ko.observable(<?php echo isset($data['ID_REKENING']) ? $data['ID_REKENING'] : 0; ?>);
    self.pajak = ko.observable('<?php echo isset($data['NAMA_REKENING']) ? $data['NAMA_REKENING'] : ''; ?>')
      .extend({
        required: {params: true, message: 'Jenis Pajak/Retribusi tidak boleh kosong'},
      });
    self.kohir = ko.observable('<?php echo isset($data['NOMOR_KOHIR']) ? $data['NOMOR_KOHIR'] : 0; ?>')
      .extend({
        required: {params: true, message: 'Nomor Kohir tidak boleh kosong'},
      });
    self.ketetapan = ko.observable(0);
    self.terbayar = ko.observable(0);
    self.angsuran = ko.observable(0);
    self.angsuranke = ko.observable(1);
    self.sisa_sebelum = ko.observable(0);
    self.sisa_terutang = ko.observable(0);
    self.read = ko.observable(<?php echo isset($lunas) ? $lunas : ''; ?>);
          
    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = '<?php echo isset($header) ? $header : '' ?>';

     self.isEdit = ko.computed(function(){
      return self.mode() === 'edit';
    });

/*    self.canPrint = ko.computed(function(){
      return self.akses_level() >= 2 && self.mode() === 'edit';
    }); */

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);
    
  }

  var App = new ModelAngsuran();

/*   App.prev = function(){
    show_prev(modul, App.id());
  }

  App.next = function(){
    show_next(modul, App.id());
  }

  App.print = function(){
    preview({"tipe":"form", "id": App.id()});
  } */

  App.back = function(){
    location.href = root+modul;
  }

  App.formValidation = function(){
    var grid = $('#grid'), errmsg = [];

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
        if (res.isSuccess == true){
          if (res.id_spt) App.id(res.id_spt);
          if (res.lunas == true) App.read(1);
          getKetetapan();
          getAngsuran();
          App.angsuran(0);
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
  
  App.pilih_npwpd = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'angsuran'};
    
    Dialog.pilihNPWPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_wp(rs.id_wp);
      App.namawp(rs.nama_wp);
      
      getKohir();
    });
  }

  App.pilih_pajak = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'angsuran'};
    
    Dialog.pilihRekening(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.idpjk(rs.idrek);
      App.pajak(rs.nmrek);
      
      getKohir();
    });
  }
        
  App.angsuran.subscribe(function(newValue){
    sisaterutang = parseFloat(App.ketetapan()) - (parseFloat(App.terbayar()) + parseFloat(App.angsuran()));
    App.sisa_terutang(sisaterutang);
  });

  ko.applyBindings(App);
  
  getKetetapan();
  setTimeout(function(){ 
    getAngsuran();
  },200);

</script>