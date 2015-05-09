<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>penetapan_sa/proses">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: kd_rek" >
      <label class="control-label" for="pajak">Pilih Pajak/Retribusi</label>
      <input type="text" class="span2" id="kd_rek" readonly="1" data-bind="value: kd_rek, executeOnEnter: pilih_rekening" required />
      <div class="controls span8 input-append">
        <input type="text" class="span8" id="pajak" readonly="1" data-bind="value: pajak, executeOnEnter: pilih_rekening" required />
        <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_rekening" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row" >
    <div class="control-group pull-left" data-bind="validationElement: tgl">
      <label class="control-label" for="tgl">Tanggal Penetapan</label>
      <input type="text" class="datepicker span2" id="tgl" data-bind="value: tgl" required />
    </div>
    <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: batas" >
      <label class="control-label" for="tglkirim">Tanggal Batas Bayar</label>
      <input type="text" class="datepicker span2" id="batas" data-bind="value: batas" required />
    </div>
  </div>

  <table id="grid"></table>
  <div id="pager"></div>

</form>

<div class="controls-row pull-right">
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canSave, click: function(data, event){save(false, data, event) }" />Proses</button>
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

  $('.datepicker#tgl').datepicker({
    onSelect:function(selectedDate) {
      App.tgl(selectedDate);
      $('.datepicker#batas').datepicker("option", "minDate", selectedDate);
      var aDate = selectedDate.split('/'),
          bDate = new Date(aDate[2], aDate[1]-1, aDate[0]),
          cDate = new Date(bDate.getTime() + 30*24*60*60*1000);
      var dd = cDate.getDate(), mm = cDate.getMonth()+1, yy = cDate.getFullYear();
      var dDate = dd + '/' + mm + '/' + yy;
      App.batas(dDate);
    }
  });
  
  $('.datepicker#batas').datepicker({
    onSelect:function(selectedDate) {
      App.batas(selectedDate);
    },
    minDate:$('.datepicker#tgl').val(),
  });
});
  var last = 0;
  $("#grid").jqGrid({
    url:'',
    datatype:'local',
    mtype:'POST',
    colNames:['', 'NPWPD', 'Nama Wajib Pajak', 'Rekening', 'Jumlah', 'Masa Awal', 'Masa Akhir'],
    colModel:[
        {name:'idspt', hidden:true},
        {name:'npwpd', width:150},
        {name:'nama', width:200},
        {name:'rek', width:150},
        {name:'jml', width:100, formatter:'currency', align:'right', editable:true},
        {name:'awal', width:100, formatter:'date', align:'center'},
        {name:'akhir', width:100, formatter:'date', align:'center'},
    ],
    pager:'#pager',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
		multiselect:true,
    gridview:true,
    shrinkToFit:false,
    recordtext:'{2} baris',
    width:935,
    height:250,
	onSelectRow: function(id){
      if(id && id!==last){
         $(this).restoreRow(last);
         last=id;
      }
    },
   // onSelectRow: select_row,
    onSelectAll: select_row,
	ondblClickRow: edit_row,
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
   // hitungTotal();
    //hitungSisa();
    //jumlahAll();
  }
  
  function select_row(id)
  {
    var idnya = $("#grid").jqGrid('getGridParam', 'selarrrow');
    App.idspt(idnya);
  }

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelPendataan = function (){
    var self = this;
    self.modul = 'Penetapan_sa';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable();
    self.tgl = ko.observable('<?php echo date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal penetapan tidak boleh kosong'}
      });
    self.batas = ko.observable('<?php echo date('d/m/Y', strtotime(' + 30 days')) ?>')
      .extend({
        required: {params: true, message: 'Tanggal batas bayar tidak boleh kosong'}
      });
    self.idrek = ko.observable(0);
    self.kd_rek = ko.observable()
      .extend({
        required: {params: true, message: 'Pajak/Retribusi tidak boleh kosong'}
      });
    self.pajak = ko.observable();
    self.idspt = ko.observableArray();

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
    });

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

  var App = new ModelPendataan();

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
    
    if (grid.jqGrid('getGridParam', 'selarrrow').length === 0) {
      errmsg.push('Belum ada SPT yang dipilih');
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

  App.save = function(){
    if (!App.formValidation()){ return }
	

    var $frm = $('#frm'),
    data = JSON.parse(ko.toJSON(App));
		data['rincian'] = [];
	var select_jq = $('#grid').jqGrid('getGridParam', 'selarrrow');
   
	//nana
	for (i = 0; i < select_jq.length; i++){
		data['rincian'].push(JSON.stringify($('#grid').jqGrid('getRowData',select_jq[i])));
	}
	
	
    $.ajax({
      url: $frm.attr('action'),
      type: 'post',
      dataType: 'json',
      data: data,
      success: function(res, xhr){
        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });
        
        setTimeout(function() { window.location = root+modul}, 2000);
      }
    });
  }

  App.pilih_rekening = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'penetapan_sa'};
    var $list = $('#grid');
    
    // hapus dulu isi grid
    var rowIds = $list.jqGrid('getDataIDs');
    for(var i=0,len=rowIds.length;i<len;i++){
      var currRow = rowIds[i];
      $list.jqGrid('delRowData', currRow);
    }
    $list.trigger('reloadGrid');

    Dialog.pilihRekening(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.idrek(rs.idrek);
      App.kd_rek(rs.kdrek);
      App.pajak(rs.nmrek);
      
      $.ajax({
        url: root+modul+'/getnpwpd',
        type: 'post',
        dataType: 'json',
        data: {idrek: rs.idrek},
        success: function(response){
          var result = response.rows;
          if (result !== undefined) {
            // add grid dengan data npwpd sesuai pajak/retribusi
            for (i = 0; i < result.length; i++){
              $list.jqGrid('addRowData', 'idspt', [{'idspt':result[i]['id_spt'], 'npwpd':result[i]['npwpd'], 'nama':result[i]['nama_wp'], 'rek':result[i]['nama_rek'], 'jml':result[i]['jumlah'], 'awal':result[i]['awal'], 'akhir':result[i]['akhir']}]);
            }
          }
        }
      });
    });
  }

  ko.applyBindings(App);
</script>