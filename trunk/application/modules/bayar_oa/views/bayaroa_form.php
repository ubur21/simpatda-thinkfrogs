<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>bayar_oa/proses">
  <div class="controls-row">
    <div class="control-group input-append" data-bind="validationElement: namawp" >
      <label class="control-label" for="namawp">Wajib Pajak/Retribusi</label>
      <input type="text" id="namawp" readonly="1" class="span10" data-bind="value: namawp" required />
      <span class="add-on" data-bind="click: pilih_kohir" ><i class="icon-folder-open"></i></span>
    </div>
  </div>

  <div class="controls-row" >
    <div class="control-group input-append" data-bind="validationElement: pajak" >
      <label class="control-label" for="pajak">Jenis Pajak/Retribusi</label>
      <input type="text" id="pajak" readonly="1" class="span10" data-bind="value: pajak" required />
      <span class="add-on" data-bind="click: pilih_pajak" ><i class="icon-folder-open"></i></span>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group"  data-bind="validationElement: tgl" >
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

  var last = 0;
  var purge = [];

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
        {name:'setor', width:100, formatter:'currency', align:'right', editable:true, 
          editoptions:{size:50,class:'span2'}},
        {name:'denda', width:100, formatter:'currency', align:'right', editable:true},
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
    footerrow:true,
    onSelectRow: function(id){
      if(id && id!==last){
         $(this).restoreRow(last);
         last=id;
      }
    },
    ondblClickRow: edit_row,
    gridComplete:function(){
      hitungTotal();
      hitungSisa();
			jumlahAll();
		}
  });

  $("#grid").jqGrid('bindKeys', {
    "onEnter": edit_row
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:false,
    edit:true,
    del:false,
		edittext: 'Ubah',
		editfunc: edit_row,
    search:false,
    refresh:false,
  },{},{},{},{});

  function edit_row(id){
    $(this).jqGrid('saveRow', last, null, 'clientArray', null, after_save);
    $(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);
    last = id;
  }

  function after_save(){
    $(this).focus();
    hitungTotal();
    hitungSisa();
    jumlahAll();
  }
  
  function hitungTotal()
  {
    var selRowId = $('#grid').jqGrid('getGridParam', 'selrow'),
        setor = $('#grid').jqGrid('getCell', selRowId, 'setor'),
        denda = $('#grid').jqGrid('getCell', selRowId, 'denda'),
        total = parseFloat(setor);
		$("#grid").jqGrid('setRowData', selRowId, {total:total});
  }
    
  function hitungSisa()
  {
    var selRowId = $('#grid').jqGrid ('getGridParam', 'selrow'),
        jml = $('#grid').jqGrid('getCell', selRowId, 'jml'),
        setor = $('#grid').jqGrid('getCell', selRowId, 'setor'),
        denda = $('#grid').jqGrid('getCell', selRowId, 'denda'),
        sisa = (parseFloat(jml) + parseFloat(denda)) - parseFloat(setor);
		$("#grid").jqGrid('setRowData', selRowId, {sisa:sisa});	
  }

  function jumlahAll()
	{
		var totalData = $("#grid").jqGrid("getDataIDs"),
			totalRows = totalData.length,
			totalPajak = 0,
			totalSetor = 0,
      totalDenda = 0,
      totalan = 0,
      totalSisa = 0;
		for (i=0; i<totalRows; i++)
		{
			sd = $("#grid").jqGrid('getRowData', totalData[i]);
			totalPajak += parseFloat(sd.jml);
			totalSetor += parseFloat(sd.setor);
			totalDenda += parseFloat(sd.denda);
			totalan += parseFloat(sd.total);
      totalSisa += parseFloat(sd.sisa);
		}
		$("#grid").jqGrid('footerData','set',{nospt:'Jml Seluruhnya',jml:totalPajak,setor:totalSetor,denda:totalDenda,total:totalan,sisa:totalSisa});
	}
 
  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelPembayaran = function (){
    var self = this;
    self.modul = 'bayar_oa';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable(0);
    self.id_spt = ko.observable(0);
    self.tgl = ko.observable('<?php echo date('d/m/Y'); ?>')
      .extend({
        required: {params: true, message: 'Tanggal Pembayaran tidak boleh kosong'},
      });
    self.idpjk = ko.observable(0);
    self.pajak = ko.observable('')
      .extend({
        required: {params: true, message: 'Jenis Pajak/Retribusi tidak boleh kosong'},
      });
    self.namawp = ko.observable('')
      .extend({
        required: {params: true, message: 'Wajib Pajak/Retribusi tidak boleh kosong'},
      });

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
    var grid = $('#grid'), errmsg = [];

    if (grid.jqGrid('getDataIDs').length === 0) {
      errmsg.push('SPT tidak boleh kosong.');
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
  
  App.pilih_kohir = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'bayar_oa'};
    var $list = $('#grid'), errmsg = [];
    
    // hapus dulu isi grid
    var rowIds = $list.jqGrid('getDataIDs');
    for(var i=0,len=rowIds.length;i<len;i++){
      var currRow = rowIds[i];
      $list.jqGrid('delRowData', currRow);
    }
    $list.trigger('reloadGrid');

    Dialog.pilihKohir(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_spt(rs.id);
      App.namawp(rs.nama_wp);
      
      $.ajax({
        url: root+modul+'/getspt',
        type: 'post',
        dataType: 'json',
        data: {id_spt: rs.id, idpjk: App.idpjk()},
        success: function(response){
          var result = response.rows,
              len = response.len;
          if (len > 0) {
            // add grid dengan data spt sesuai pajak/retribusi
            for (i = 0; i < len; i++){
              $list.jqGrid('addRowData', 'idspt', [{'idspt':result[i]['id_spt'], 'kohir':result[i]['kohir'], 'rek':result[i]['rek'], 'awal':result[i]['awal'], 'akhir':result[i]['akhir'], 'jml':result[i]['jml'], 'sisa':result[i]['sisa']}]);
            }
          }
          else {
            if (App.id_spt() !== 0 && App.idpjk() !== 0) {
              errmsg.push('SPT dari WP/WR dan Pajak tsb tidak ada yang belum lunas.');
              App.errors.showAllMessages();
              if (errmsg.length > 0) {
                $.pnotify({
                  title: 'Perhatian',
                  text: errmsg.join('</br>'),
                  type: 'warning'
                });
                return false;
              }
            }
          }
        }
      });
    });
  }

  App.pilih_pajak = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'bayar_oa'};
    var $list = $('#grid'), errmsg = [];
    
    // hapus dulu isi grid
    var rowIds = $list.jqGrid('getDataIDs');
    for(var i=0,len=rowIds.length;i<len;i++){
      var currRow = rowIds[i];
      $list.jqGrid('delRowData', currRow);
    }
    $list.trigger('reloadGrid');

    Dialog.pilihRekening(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.idpjk(rs.idrek);
      App.pajak(rs.nmrek);
            
      $.ajax({
        url: root+modul+'/getspt',
        type: 'post',
        dataType: 'json',
        data: {id_spt: App.id_spt(), idpjk: rs.idrek},
        success: function(response){
          var result = response.rows,
              len = response.len;
          if (len > 0) {
            // add grid dengan data spt sesuai pajak/retribusi
            for (i = 0; i < len; i++){
              $list.jqGrid('addRowData', 'idspt', [{'idspt':result[i]['id_spt'], 'kohir':result[i]['kohir'], 'rek':result[i]['rek'], 'awal':result[i]['awal'], 'akhir':result[i]['akhir'], 'jml':result[i]['jml'], 'sisa':result[i]['sisa']}]);
            }
          }
          else {
            if (App.id_spt() !== 0 && App.idpjk() !== 0) {
              errmsg.push('SPT dari WP/WR dan Pajak tsb tidak ada yang belum lunas.');
              App.errors.showAllMessages();
              if (errmsg.length > 0) {
                $.pnotify({
                  title: 'Perhatian',
                  text: errmsg.join('</br>'),
                  type: 'warning'
                });
                return false;
              }
            }
          }
        }
      });
    });
  }

  ko.applyBindings(App);
</script>