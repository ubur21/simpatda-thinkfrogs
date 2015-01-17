<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>anggaran/proses">
  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: tahun" >
      <label class="control-label" for="tahun">Tahun</label>
      <input type="text" id="tahun" class="span2" data-bind="value: tahun" required />
    </div>
  </div>

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

  <ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px">
    <li class="active"><a href="#home">Rincian Anggaran</a></li>
  </ul>
  
  <div class="tab-content" style="height:295px">
    <div class="tab-pane active" id="home">
      <table id="list"></table>
      <div id="pager"></div>
    </div>
  </div>

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
</form>

<script>
var last = 0;
var purge = [];

$(document).ready(function() {
  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });

  $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  })

  $("#list").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', 'Kode Rekening','Nama Rekening', 'Pagu Murni', 'Pagu PAK'],
    colModel:[
        {name:'idrek', hidden:true},
        {name:'kdrek', index:'kdrek', width:100, sortable:false},
        {name:'nmrek', width:480, sortable:false},
        {name:'pagu_murni', index:'pagu_murni', width:150, sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right', editoptions:{size:10,class:'span2'}},
        {name:'pagu_pak', index:'pagu_pak', width:150, sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right', editoptions:{size:10,class:'span2'}}
       ],
    pager:'#pager',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'210',
    footerrow:true,
    recordtext:'{2} baris',
    onSelectRow: function(id){
      if(id && id!==last){
         $(this).restoreRow(last);
         last=id;
      }
    },
    ondblClickRow: edit_row,
    gridComplete:function(){
			jum_nom();
		}
  });

  $("#list").jqGrid('bindKeys', { "onEnter": edit_row});
  $("#list").jqGrid('navGrid', '#pager', {
      add:true,
      addtext: 'Tambah',
      addfunc:add_row,
      edit:true,
      edittext: 'Ubah',
      editfunc:edit_row,
      del:true,
      deltext: 'Hapus',
      delfunc:del_row,
      search:false,
      refresh:false,
      refreshtext:'Refresh',
    },{},{},{},{});

});

  function add_row(){
    var id_skpd = App.id_skpd(),
        $list = $(this),
        option = {multi:1, id_skpd:id_skpd, mode:'anggaran'},
        i = 0,
        rs = [];

    Dialog.pilihRekening(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        addRowSorted($list, {'id':'idrek', 'sortName':['kdrek']}, {'idrek':rs.idrek, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek, 'nom':0});
      }
    });
  };

  function edit_row(id){
    $(this).jqGrid('saveRow', last, null, 'clientArray', null, after_save);
    $(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);
    last = id;
  };

  function after_save(){
    $(this).focus();
    jum_nom();
  }
  
  jum_nom = function()
	{
		var totalData = jQuery("#list").jqGrid("getDataIDs"),
			totalRows = totalData.length;
			totalMurni = 0;
			totalPak = 0;
		for (i=0; i<totalRows; i++)
		{
			sd = jQuery("#list").jqGrid('getRowData', totalData[i]);
			totalMurni += parseFloat(sd.pagu_murni);
			totalPak += parseFloat(sd.pagu_pak);
		}
		jQuery("#list").jqGrid('footerData','set',{kdrek:'TOTAL',pagu_murni:totalMurni,pagu_pak:totalPak});	
	}

  function del_row(id){
    var rs = {},
        answer = false,
        len = id.length;

    rs = $(this).jqGrid('getRowData', id);
    answer = confirm('Hapus ' + rs.kdrek + ' dari daftar?');

    if(answer == true){
      purge.push(id);
      $(this).jqGrid('delRowData', id);
    }
  };

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelAnggaran = function (){
    var self = this;
    self.modul = 'Anggaran';
    self.akses_level = ko.observable(<?php echo isset($akses)?$akses:0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_ANGGARAN'])?$data['ID_ANGGARAN']:0 ?>');
    self.tahun = ko.observable('<?php echo isset($data['TAHUN'])?$data['TAHUN']: '' ?>')
      .extend({
        required: {params: true, message: 'Tahun tidak boleh kosong'}
      });
    self.pagu_murni = ko.observable(<?php echo isset($data['PAGU_MURNI'])?$data['PAGU_MURNI']:0; ?>);
    self.pagu_pak = ko.observable(<?php echo isset($data['PAGU_PAK'])?$data['PAGU_PAK']:0; ?>);
    self.id_skpd = ko.observable(<?php echo isset($data['ID_SKPD'])?$data['ID_SKPD']:'' ?>)
      .extend({
        required: {params: true, message: 'SKPD belum dipilih'}
      });
    self.kd_skpd = ko.observable('<?php echo isset($data['KODE_SKPD'])?$data['KODE_SKPD']:'' ?>');
    self.nm_skpd = ko.observable('<?php echo isset($data['NAMA_SKPD'])?$data['NAMA_SKPD']:'' ?>');

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

  var App = new ModelAnggaran();

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
    var grdrek = $('#list'), errmsg = [];
    // cek jika ada baris di grid belum disimpan
    checkGridRow(grdrek, 'idrek', after_save);
    // cek jika grid belum diisi
    if (grdrek.jqGrid('getGridParam', 'reccount') === 0) {
      errmsg.push('Belum ada Rekening yang di entri.');
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
        data['rincian'] = JSON.stringify($('#list').jqGrid('getRowData'));
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
    var grd_rinci = $('#list');

    if (App.id() > 0){
      grd_rinci.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/rinci/' + App.id(), 'datatype': 'json'});
      grd_rinci.trigger('reloadGrid');
    }
    else {
      grd_rinci.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }
  }

  App.pilih_skpd = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0};
    Dialog.pilihSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_skpd(rs.id);
      App.kd_skpd(rs.kode);
      App.nm_skpd(rs.nama);
    });
  }

  App.init_select = function(element, callback){
    var data = {'text': $(element).attr('data-init')};
    callback(data);
  }

  ko.applyBindings(App);
  setTimeout(function(){
    App.init_grid();
  }, 500)
</script>
