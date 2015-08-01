numeral.language('id');
var fmtCurrency = {colorize:true, symbol: '', decimalSymbol: ',', digitGroupSymbol:'.'};

$(function($){
  $.datepicker.regional['id'] = {
    closeText: 'Tutup',
    prevText: '&#x3c;mundur',
    nextText: 'maju&#x3e;',
    currentText: 'hari ini',
    monthNames: ['Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','Nopember','Desember'],
    monthNamesShort: ['Jan','Feb','Mar','Apr','Mei','Jun',
    'Jul','Agus','Sep','Okt','Nop','Des'],
    dayNames: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
    dayNamesShort: ['Min','Sen','Sel','Rab','kam','Jum','Sab'],
    dayNamesMin: ['Mg','Sn','Sl','Rb','Km','jm','Sb'],
    dateFormat: 'dd/mm/yy', firstDay: 0,
    isRTL: false
  };
});

(function( $ ) {
  $.fn.terbilang = function( options ) {
    var opt = {
      style  : 4, //style 1=UPPER CASE,2=lower case,3=Title Case,4=Sentence case
      input_type  : "form", //input type (form or text)
      output : "output",  //element's id to show the output
      awalan  : "",  //prefix output
      akhiran : "rupiah",  //postfix output
      on_error  : function( msg ) {
        alert( "Error: " + msg );
      }
    };
    if( options ) {
      $.extend( opt, options );
    }
    this.each( function( ) {
      var self = this;
      if (opt.input_type=="form"){
        $( this ).bind( "keyup", function( e ) {
          _tobilang( this );
        } );
      }
    } );
    var _tobilang = function( self ) {
      var hasil ="";
      if (opt.input_type=="form"){
        var angka=$(self).val();
      }else{
        var angka=$(self).text();
      }
      if(self<0) {
        hasil = opt.awalan + " minus "+ _bilang(angka) + " " + opt.akhiran;
      } else {
        hasil = opt.awalan + " " + _bilang(angka) + " " + opt.akhiran;
      }
      switch (opt.style) {
        case 1:
          hasil = hasil.toUpperCase();
          break;
        case 2:
          hasil = hasil.toLowerCase();
          break;
        case 3:
          hasil = _ucwords(hasil);
          break;
        default:
          hasil = _ucfirst(_ltrim(hasil));
          break;
      }
      $('#'+opt.output).val(hasil);
    }
    var _bilang = function( self ) {
      var x = Math.abs(self);
      angka = new Array("nol", "satu", "dua", "tiga", "empat", "lima","enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
      var temp = "";
      if (x <12) {
        temp = " "+angka[Math.floor(x)];
      } else if (x <20) {
        temp = _bilang(x - 10)+ " belas";
      } else if (x <100) {
        temp = _bilang(x/10)+" puluh"+ _bilang(x % 10);
      } else if (x <200) {
        temp = " seratus" + _bilang(x - 100);
      } else if (x <1000) {
        temp = _bilang(x/100) + " ratus" + _bilang(x % 100);
      } else if (x <2000) {
        temp = " seribu" + _bilang(x - 1000);
      } else if (x <1000000) {
        temp = _bilang(x/1000) +" ribu" + _bilang(x % 1000);
      } else if (x <1000000000) {
        temp = _bilang(x/1000000)+ " juta" + _bilang(x % 1000000);
      } else if (x <1000000000000) {
        temp = _bilang(x/1000000000) + " milyar" + _bilang(x % 1000000000);
      } else if (x <1000000000000000) {
        temp = _bilang(x/1000000000000) +" trilyun" + _bilang(x % 1000000000000);
      }
      return temp;
    }
    var _ltrim = function (str, chars) {
      chars = chars || "\\s";
      return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
    }
    var _ucwords = function( str ) {
      return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
      });
    }
    var _ucfirst = function( str ) {
      var f = str.charAt(0).toUpperCase();
      return f + str.substr(1).toLowerCase();
    }
    _tobilang( this );
  };
})( jQuery );

function preview(param){
  $.ajax({
    type: "post",
    dataType: "json",
    data: param,
    url: root+'laporan/'+modul,
    success: function(res, stat){
      if (stat == 'success'){
        if (res.isSuccessful) {
          url = root+'laporan/view/'+res.id+'/'+res.nama;
          window.open( url ,'_blank');
        }
        else {
          // TODO: show information error
          $.pnotify({
            text: res.message,
            type: 'error'
          });
        }
      }
    },
    error: function(res, stat, error){
      // TODO: show information error
    }
  })
};

function show_prev(modul, id) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: root+modul+'/prev/'+id,
    data: {id: id},
    success: function(data) {
      if (true == data.isSuccessful){
        location.href = root+modul+'/form/'+data.id;
      }
      else{
        $.pnotify({
          text: 'Anda telah berada di baris pertama.',
          type: 'warning'
        });
      }
    },
  });
}

function show_next(modul, id) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: root+modul+'/next/'+id,
    data: {id: id},
    success: function(data) {
      if (true == data.isSuccessful){
        location.href = root+modul+'/form/'+data.id;
      }
      else{
        $.pnotify({
          text: 'Anda telah berada di baris terakhir.',
          type: 'warning'
        });
      }
    },
  });
}

function show_prev_sa(modul, id, tipe) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: root+modul+'/prev/'+id+'/'+tipe,
    data: {id: id},
    success: function(data) {
      if (true == data.isSuccessful){
        location.href = root+modul+'/form_sa/'+data.id;
      }
      else{
        $.pnotify({
          text: 'Anda telah berada di baris pertama.',
          type: 'warning'
        });
      }
    },
  });
}

function show_next_sa(modul, id, tipe) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: root+modul+'/next/'+id+'/'+tipe,
    data: {id: id},
    success: function(data) {
      if (true == data.isSuccessful){
        location.href = root+modul+'/form_sa/'+data.id;
      }
      else{
        $.pnotify({
          text: 'Anda telah berada di baris terakhir.',
          type: 'warning'
        });
      }
    },
  });
}

function show_prev_oa(modul, id, tipe) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: root+modul+'/prev/'+id+'/'+tipe,
    data: {id: id},
    success: function(data) {
      if (true == data.isSuccessful){
        location.href = root+modul+'/form_oa/'+data.id;
      }
      else{
        $.pnotify({
          text: 'Anda telah berada di baris pertama.',
          type: 'warning'
        });
      }
    },
  });
}

function show_next_oa(modul, id, tipe) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: root+modul+'/next/'+id+'/'+tipe,
    data: {id: id},
    success: function(data) {
      if (true == data.isSuccessful){
        location.href = root+modul+'/form_oa/'+data.id;
      }
      else{
        $.pnotify({
          text: 'Anda telah berada di baris terakhir.',
          type: 'warning'
        });
      }
    },
  });
}

var Dialog = (function () {
  var nama, dlg, grid, pgr, el,
      PILIH = [],
      opt = {
        'datatype':'json',
        'mtype':'post',
        'rowNum':'0',
        'scroll':true,
        'rownumbers':true,
        'viewrecords':true,
        'gridview':true,
        'autowidth':true,
        'height':'250',
        'recordtext':'{2} baris',
        'shrinkToFit':false,
      };

  function initDialog(opt, callback, param){
    nama = param.name;
    dlg = 'dlg' + param.name;
    grid = '#grdDialog' + param.name;
    pgr = '#pgrDialog' + param.name;
    el = '#' + dlg;

    if(!$(el).length){ $('body').append('<div id="' + dlg + '"></div') }
    $(el).dialog({
      title: param.title,
      height: 450,
      width: 780,
      modal: true,
      resizable: false,
      autoOpen: true,
      closeOnEscape: true,
      open:dialogOpen,
      buttons: {
        'Pilih' : {
          text: 'Pilih',
          click: function(){
                    callback(grid, PILIH);
                    $(this).dialog('close');
                 },
          class: 'btn btn-primary',
        },
        'Tutup' : {
          text: 'Tutup',
          click: function(){
                    $(this).dialog('close');
                 },
          class: 'btn',
        }
      }
    });
    $.post(root + 'pilih/' + param.name, opt, loadSuccess, 'json');;
  }

  function loadSuccess(data, status){
    if (data.html){
      $(el).html(data.html);
      $('#bfilter'+nama).click(function(){
        gridFilter();
      });

      $('#bclear'+nama).click(function(){
        $('#svalue'+nama).val('');
        $(grid).jqGrid('setGridParam',{search: false});
        $(grid).trigger("reloadGrid",[{page:1}]);
      });

      $('#svalue'+nama).keypress(function(e){
        c = e.which ? e.which : e.keyCode;
        if(c == 13){
          gridFilter();
        }
      });
    }
    if (data.grid){
      $.extend(opt, data.grid);
      $(grid).jqGrid(opt);
      $(grid).jqGrid('setGridParam', {
        beforeRequest:gridBeforeRequest,
        onSelectRow: gridSelectRow,
        onSelectAll: gridSelectAll,
        ondblClickRow: gridDoubleClick,
      });
      $(grid).jqGrid('navGrid', pgr, {
        add:false,
        edit:false,
        del:false,
        search:false,
        refresh:true,
        refreshText:'Refresh',
      })
      .jqGrid('bindKeys', {
        'onEnter': function(id){
          buttons = $(el).dialog('option', 'buttons');
          buttons['Pilih'].click.apply($(el));
        }
      });
    }
  }

  function dialogOpen(){
    // reset
    PILIH = [];
  }

  function gridBeforeRequest(){
    // reset
    PILIH = [];
  }

  function gridSelectRow(id, status){
    var multi = $(this).jqGrid('getGridParam', 'multiselect');
    if (multi == 1) {
      // Did they select a row or de-select a row?
      if (status == true){
        var data = {id:id};
        PILIH.push(data);
      }
      else {
        // Filter through the array returning every value EXCEPT the id I want removed.
        PILIH = $.grep(PILIH, function(value) {
          return value.id != id;
        });
      }
     }
     else {
       PILIH = [{id:id}];
     }
  }

  function gridSelectAll(id, status){
    if (status == true){
      for (single_id in id){
        // If the value is NOT in the array, then add it to the array.
        if ($.inArray(id[single_id], PILIH) == -1){
          var data = {id:id[single_id]};
          PILIH.push(data);
        }
      }
    }
    else {
      for (single_id in id){
        // If the value is in the array, then take it out.
        if ($.inArray(id[single_id], PILIH) != -1){
          PILIH = $.grep(PILIH, function (value){
            return value != id[single_id];
          });
        }
      }
    }
  }

  function gridDoubleClick(){
    var multi = $(this).jqGrid('getGridParam', 'multiselect');
    if (multi != 1) {
      buttons = $(el).dialog('option', 'buttons');
      buttons['Pilih'].click.apply($(el));
    }
  }

  function gridFilter(){
    var field = $('#sfield'+nama).val();
    var oper = $('#soper'+nama).val();
    var string = $('#svalue'+nama).val();
    var postdata = $(grid).jqGrid('getGridParam', 'postData');
    $.extend(postdata,{filters: '',
                searchField: field,
                searchOper: oper,
                searchString: string});
    $(grid).jqGrid('setGridParam',{search: true, postData: postdata});
    $(grid).trigger("reloadGrid",[{page:1}]);
  }

  function pilihRekening(opt, callback){
    // param : multi [0, 1]
    //         id_skpd
    //         keperluan
    //         jenis
    //         beban
    //         id_aktivitas
    //         id_kegiatan
    //         tanggal
    //         mode
    var param = {
      name: 'rekening',
      title: 'Pilih Rekening'
    }
    initDialog(opt, callback, param);
  }

  function pilihSKPD(opt, callback)
  {
    // opt : multi [0, 1]
    var param = {
      name: 'skpd',
      title: 'Pilih SKPD'
    }
    initDialog(opt, callback, param);
  }

  function pilihKegiatan(opt, callback)
  {
    // opt : multi [0, 1]
    //       id_skpd
    //       mode
    var param = {
      name: 'kegiatan',
      title: 'Pilih Kegiatan'
    }
    initDialog(opt, callback, param);
  }

  function pilihKegiatanAktivitas(opt, callback)
  {
    // param : multi [0, 1]
    //         id_skpd
    //         tanggal
    //         mode
    var param = {
      name: 'kegiatanAktivitas',
      title: 'Pilih Kegiatan'
    }
    initDialog(opt, callback, param);
  }

  function pilihSumberdana(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'sumberdana',
      title: 'Pilih Sumber Dana'
    }
    initDialog(opt, callback, param);
  }

  function pilihSumberdanaSKPD(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'sumberdanaskpd',
      title: 'Pilih Rekening Bendahara'
    }
    initDialog(opt, callback, param);
  }

  function pilihPajak(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'pajak',
      title: 'Pilih Pajak'
    }
    initDialog(opt, callback, param);
  }

  function pilihPotongan(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'potongan',
      title: 'Pilih Potongan'
    }
    initDialog(opt, callback, param);
  }

  function pilihKontrak(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'kontrak',
      title: 'Pilih Kontrak'
    }
    initDialog(opt, callback, param);
  }

  function pilihLokasi(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'lokasi',
      title: 'Pilih Lokasi'
    }
    initDialog(opt, callback, param);
  }

  function pilihSP2D(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'sp2d',
      title: 'Pilih SP2D'
    }
    initDialog(opt, callback, param);
  }

  function pilihSPM(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spm',
      title: 'Pilih SPM'
    }
    initDialog(opt, callback, param);
  }

  function pilihSPP(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spp',
      title: 'Pilih SPP'
    }
    initDialog(opt, callback, param);
  }

  function pilihSPJ(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spj',
      title: 'Pilih SPJ'
    }
    initDialog(opt, callback, param);
  }

  function pilihSPJK(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spjk',
      title: 'Pilih Setoran Pajak'
    }
    initDialog(opt, callback, param);
  }

  function pilihSPFK(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spfk',
      title: 'Pilih Setoran PFK'
    }
    initDialog(opt, callback, param);
  }

  function pilihSSU(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'ssu',
      title: 'Pilih Setoran Sisa'
    }
    initDialog(opt, callback, param);
  }

  function pilihSTS(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'sts',
      title: 'Pilih STS'
    }
    initDialog(opt, callback, param);
  }

  function pilihCP(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'cp',
      title: 'Pilih Kontra Pos'
    }
    initDialog(opt, callback, param);
  }

  function pilihSPD(opt, callback)
  {
    // param : multi [0, 1]
    //   id
    //   tanggal
    //   keperluan
    //   beban
    var param = {
      name: 'spd',
      title: 'Pilih SPD'
    }
    initDialog(opt, callback, param);
  }
  
  function pilihNPWPD(opt, callback)
  {
    var param = {
      name: 'npwpd',
      title: 'Pilih NPWPD'
    }
    initDialog(opt, callback, param);
  }
  
  function pilihJURNAL(opt, callback)
  {
    var param = {
      name: 'jurnal',
      title: 'Pilih Jurnal'
    }
    initDialog(opt, callback, param);
  }
  
  function pilihPAJAKOA(opt, callback)
  {
    var param = {
      name: 'pajakoa',
      title: 'Pilih PAJAK OA'
    }
    initDialog(opt, callback, param);
  }
  
  function pilihBENDAHARA(opt, callback)
  {
    var param = {
      name: 'bendahara',
      title: 'Pilih Bendahara'
    }
    initDialog(opt, callback, param);
  }
  
  function pilihKASDAERAH(opt, callback)
  {
    var param = {
      name: 'kasdaerah',
      title: 'Pilih Kas Daerah'
    }
    initDialog(opt, callback, param);
  }

  function pilihSPT(opt, callback)
  {
    var param = {
      name: 'spt',
      title: 'Pilih SPT'
    }
    initDialog(opt, callback, param);
  }
  
  function pilihJenisPajak(opt, callback)
  {
    var param = {
      title: 'Pilih Jenis Pajak',
      name: 'jenispajak'
    }
    initDialog(opt, callback, param);
  }
  return {
    pilihRekening:pilihRekening,
    pilihKegiatan:pilihKegiatan,
    pilihKegiatanAktivitas:pilihKegiatanAktivitas,
    pilihSKPD:pilihSKPD,
    pilihSumberdana:pilihSumberdana,
    pilihSumberdanaSKPD:pilihSumberdanaSKPD,
    pilihKontrak:pilihKontrak,
    pilihPajak:pilihPajak,
    pilihPotongan:pilihPotongan,
    pilihLokasi:pilihLokasi,
    pilihSP2D:pilihSP2D,
    pilihSPM:pilihSPM,
    pilihSPP:pilihSPP,
    pilihSPJ:pilihSPJ,
    pilihSPJK:pilihSPJK,
    pilihSPFK:pilihSPFK,
    pilihSSU:pilihSSU,
    pilihSTS:pilihSTS,
    pilihCP:pilihCP,
    pilihSPD:pilihSPD,
    pilihNPWPD:pilihNPWPD,
	pilihJURNAL:pilihJURNAL,
	pilihPAJAKOA:pilihPAJAKOA,
	pilihBENDAHARA:pilihBENDAHARA,
	pilihKASDAERAH:pilihKASDAERAH,
    pilihSPT:pilihSPT,
    pilihJenisPajak:pilihJenisPajak,
  }
}());

// param :
//  grid = grid jquery object
//  col = object contains id, sortName, sortOrder
//  toAdd = data to add
function addRowSorted(grid, col, toAdd) {
  var data = grid.jqGrid("getRowData"), // ambil data yang ada di grid
      datalen = data.length,
      sortlen = col.sortName.length,
      sortorder = typeof(col.sortOrder) === "undefined" ? "asc" : col.sortOrder, // default order : asc
      id = new_id = 0,
      src = dst = '',
      i = j = 0;

  new_id = toAdd[col.id];
  for (i = 0; i < datalen; i++) {
    id = data[i][col.id];
    if (sortlen > 0) {
      src = dst = '';
      for (var j = 0; j < sortlen; j++) {
        src = src + data[i][col.sortName[j]].toLowerCase();
        dst = dst + toAdd[col.sortName[j]].toLowerCase();
      }
      if (sortorder == "desc" && src < dst) {
        grid.jqGrid('addRowData', new_id, toAdd, 'before', id);
        return;
      }
      else if (sortorder == "asc" && src > dst) {
        grid.jqGrid('addRowData', new_id, toAdd, 'before', id);
        return;
      }
      else if (src === dst) { return; }
    }
  }
  //The data is empty or it should be last, add it at the end.
  grid.jqGrid('addRowData', new_id, toAdd, 'last');
}

// cek apakah di grid ada yang masih di edit, simpan jika ada
// grd : objek jqgrid 
// idcol : kolom yang digunakan sebagai primary key
// aftersave : callback function 
function checkGridRow(grd, idcol, aftersave){
  var row = grd.jqGrid('getGridParam', 'savedRow');
  if (row.length > 0) {
    for (i = 0; i < row.length; i++){
      grd.jqGrid('saveRow', row[i][idcol], null, 'clientArray', null, aftersave);
    }
  }
}

// cek apakah di grid ada yang nilainya minus
// grd : objek jqgrid 
// cekcol : kolom yang hendak dicek nilainya
// return true jika ada yang minus
// return false jika tidak ada yang minus
function checkGridMinus(grd, cekcol){
  var row = grd.jqGrid('getRowData');
  if (row.length > 0) {
    for (i = 0; i < row.length; i++){
      if  (row[i][cekcol] < 0) return true;
    }
  }
  return false;
}
    
// Knockout binding handler
ko.bindingHandlers.select2 = {
  init: function(element, valueAccessor, allBindingsAccessor) {
      var obj = valueAccessor();
      $(element).select2(obj);

      ko.utils.domNodeDisposal.addDisposeCallback(element, function() {
          $(element).select2('destroy');
      });
  },
  update: function(element) {
      $(element).trigger('change');
  }
};

var formatNumber = function (element, valueAccessor, allBindingsAccessor, format) {
    // Provide a custom text value
    var value = valueAccessor(), allBindings = allBindingsAccessor();
    var numeralFormat = allBindingsAccessor.numeralFormat || format;
    var strNumber = ko.utils.unwrapObservable(value);
    if (strNumber !== '') {
        return numeral(strNumber).format(numeralFormat);
    }
    return '';
};

ko.bindingHandlers.numeraltext = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        $(element).text(formatNumber(element, valueAccessor, allBindingsAccessor, "(0,0.00)"));
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        $(element).text(formatNumber(element, valueAccessor, allBindingsAccessor, "(0,0.00)"));
    }
};

ko.bindingHandlers.numeralvalue = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        $(element).val(formatNumber(element, valueAccessor, allBindingsAccessor, "(0,0.00)"));

        //handle the field changing
        ko.utils.registerEventHandler(element, "change", function () {
            var observable = valueAccessor(),
                val = $(element).val(),
                nom = numeral().unformat(val);
            observable(nom);
        });
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        $(element).val(formatNumber(element, valueAccessor, allBindingsAccessor, "(0,0.00)"));
    }
};

ko.bindingHandlers.percenttext = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        $(element).text(formatNumber(element, valueAccessor, allBindingsAccessor, "(0.000 %)"));
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        $(element).text(formatNumber(element, valueAccessor, allBindingsAccessor, "(0.000 %)"));
    }
};

ko.bindingHandlers.percentvalue = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        $(element).val(formatNumber(element, valueAccessor, allBindingsAccessor, "(0.000 %)"));

        //handle the field changing
        ko.utils.registerEventHandler(element, "change", function () {
            var observable = valueAccessor();
            observable($(element).val());
        });
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        $(element).val(formatNumber(element, valueAccessor, allBindingsAccessor, "(0.000 %)"));
    }
};

  
ko.bindingHandlers.executeOnEnter = {
  init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
    var allBindings = allBindingsAccessor();
    $(element).keypress(function (event) {
      var keyCode = (event.which ? event.which : event.keyCode);
      if (keyCode === 13) {
        allBindings.executeOnEnter.call(viewModel);
        return false;
      }
      return true;
    });
  }
};

ko.validation.rules['integer'] = {
    validator: function (val, validate) {
        if (!validate) {return true; }
        return val === null || val === "" || (validate && /^-?\d*$/.test(val));
    },
    message: 'Must be an integer value'
};

ko.validation.rules['mustGreater'] = {
    validator: function (val, otherVal) {
        return val > otherVal;
    },
    message: 'The field must greater than {0}'
};
