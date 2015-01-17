// JavaScript Document
// * Author : Banu Araidi
// Description : Fungsi untuk validasi form yg kosong,
// Penggunaan : Pd field tambahkan atribut title
// Contoh : <input type=text title="nama">

function saveEntry(obj,exfunc){
	var flag=null;
	var req = new Object();
	var n=0;
	for(var i=0;i<obj.length;i++){
		if(obj[i].title!=""){req[n]=obj[i];	n++; } 
	}
	for(var i=0;i<n;i++){
		var temp = trim(req[i].value);
		switch(req[i].type){
			case "text": 
			case "textarea": 
			case "select-one": flag = (temp=="") ? false : true ;break;
			case "radio":
			case "checkbox": flag = (req[i].checked==false) ? false : true ;break;
			case "hidden": flag=true;
		}
		if(!flag) break;
	}
	if(!flag){
		var str = req[i].title;
		var cob = str.split("-");
		var message = (cob.length>1) ?  " Pada Tab "+cob[1].toUpperCase() : "Field "+cob[0].toUpperCase();
		message+= (req[i].type=='checkbox') ? " Belum Di Centang" : " Tidak Boleh Kosong";
		alert(message);
		req[i].focus();
	}else{
		if(exfunc){
			if(exfunc()){;
				return true;
			}
		}else{
			return true;
		}
	}
}
// *--------------------------------------------

// Jumlah Hari Dalam Bulan : Format Tanggal dd/mm/YY
// Author : Banu
function dayNum (tgl) {
	var arr = tgl.split('/');
	return(new Date(arr[2],arr[1],0).getDate());
} 

function terbilang(bilangan) {

if(isNaN(bilangan)){
    return "";
}else{
    bilangan = String(bilangan);
    var angka = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
    var kata = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
    var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

    var panjang_bilangan = bilangan.length;

/* pengujian panjang bilangan */
if (panjang_bilangan > 15) {
kaLimat = "Diluar Batas";
return kaLimat;
}

/* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
for (i = 1; i <= panjang_bilangan; i++) {
angka[i] = bilangan.substr(-(i),1);
}
i = 1;
j = 0;
kaLimat = "";


/* mulai proses iterasi terhadap array angka */
while (i <= panjang_bilangan) {
	subkaLimat = "";
	kata1 = "";
	kata2 = "";
	kata3 = "";

	/* untuk Ratusan */
	if (angka[i+2] != "0") {
		if (angka[i+2] == "1") {
			kata1 = "Seratus";
		}else{ kata1 = kata[angka[i+2]] + " Ratus";}
	}

/* untuk Puluhan atau Belasan */
if (angka[i+1] != "0") {
if (angka[i+1] == "1") {
if (angka[i] == "0") {
kata2 = "Sepuluh";
}else if(angka[i] == "1") {kata2 = "Sebelas";}
else {kata2 = kata[angka[i]] + " Belas";}
} else {kata2 = kata[angka[i+1]] + " Puluh";}
}


/* untuk Satuan */
if (angka[i] != "0") {
if (angka[i+1] != "1") {
kata3 = kata[angka[i]];
}
}


/* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
}

/* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
kaLimat = subkaLimat + kaLimat;
i = i + 3;
j = j + 1;
}


/* mengganti Satu Ribu jadi Seribu jika diperlukan */
if ((angka[5] == "0") && (angka[6] == "0")) {
kaLimat = kaLimat.replace("Satu Ribu","Seribu");
}


    

    return kaLimat + "Rupiah";


}
}

function kalender(id){

        this.cal_name = "cal_"+id;
        this.date_name = "date_"+id;
        this.container_name = "container_"+id;
        this.show_name = "show_"+id;
        this.dialog_obj;
        this.calendar_obj;

        this.navConfig={
            strings : {month: "Month",year: "Year",submit: "OK",cancel: "Cancel",invalidYear: "Please enter a valid year"},initialFocus: "year"
        };

        this.show_pesan = function(){
            alert(YAHOO.lang.dump(this.calendar_obj));
        }

        this.getResultCalendar = function(objCal){
            var selDate = objCal.getSelectedDates()[0];
            var dStr = (selDate.getDate() > 9) ? selDate.getDate() : "0"+selDate.getDate();
            var mStr = ((selDate.getMonth()+1) > 9) ? selDate.getMonth()+1 : "0"+parseInt(selDate.getMonth()+1);
            var yStr = selDate.getFullYear();

            return dStr+"/"+mStr+"/"+yStr;
        }

        this.okHandler = function(){
            if (this.calendar_obj.getSelectedDates().length > 0) YAHOO.util.Dom.get(this.date_name).value = this.getResultDate(this.calendar_obj);
            else YAHOO.util.Dom.get(this.date_name).value = "";
            this.hide();
        }

        this.cancelHandler = function(){
            this.hide();
        }

        //this.calendar_obj = new YAHOO.widget.Calendar(this.cal_name, {iframe:false,hide_blank_weeks:true,navigator:this.navConfig,close:false});

        this.initCalendar = function(){
            this.calendar_obj = new YAHOO.widget.Calendar(this.cal_name, {iframe:false,hide_blank_weeks:true,navigator:this.navConfig,close:false});
        };

        this.initDialog = function(){
            this.dialog_obj = new YAHOO.widget.Dialog(this.container_name, {
                context:["show", "tl", "bl"],
                buttons:[ {text:"Select", isDefault:true, handler: this.okHandler},
                  {text:"Cancel", handler: this.cancelHandler}],
                width:"16em",draggable:false,close:true
            });
        }

        this.createCalendar = function(){
            this.initCalendar();
            this.initDialog();
            this.calendar_obj.render();
            this.dialog_obj.render();
            this.dialog_obj.hide();

            this.calendar_obj.renderEvent.subscribe(function() { this.dialog_obj.fireEvent("changeContent"); });

            YAHOO.util.Event.on(this.show_name, "click", this.dialog_obj.show, this.dialog_obj, true);

        }
    }

    function validateDate(fld) {
	  if(fld.value!=""){
    	//var RegExPattern = /^((((0?[1-9]|[12]\d|3[01])[\.\-\/](0?[13578]|1[02])[\.\-\/]((1[6-9]|[2-9]\d)?\d{2}))|((0?[1-9]|[12]\d|30)[\.\-\/](0?[13456789]|1[012])[\.\-\/]((1[6-9]|[2-9]\d)?\d{2}))|((0?[1-9]|1\d|2[0-8])[\.\-\/]0?2[\.\-\/]((1[6-9]|[2-9]\d)?\d{2}))|(29[\.\-\/]0?2[\.\-\/]((1[6-9]|[2-9]\d)?(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)|00)))|(((0[1-9]|[12]\d|3[01])(0[13578]|1[02])((1[6-9]|[2-9]\d)?\d{2}))|((0[1-9]|[12]\d|30)(0[13456789]|1[012])((1[6-9]|[2-9]\d)?\d{2}))|((0[1-9]|1\d|2[0-8])02((1[6-9]|[2-9]\d)?\d{2}))|(2902((1[6-9]|[2-9]\d)?(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)|00))))$/;
    	var RegExPattern = /^((((0?[1-9]|[12]\d|3[01])[\/](0?[13578]|1[02])[\/]((1[6-9]|[2-9]\d)?\d{2}))|((0?[1-9]|[12]\d|30)[\/](0?[13456789]|1[012])[\/]((1[6-9]|[2-9]\d)?\d{2}))|((0?[1-9]|1\d|2[0-8])[\/]0?2[\/]((1[6-9]|[2-9]\d)?\d{2}))|(29[\/]0?2[\/]((1[6-9]|[2-9]\d)?(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)|00)))|(((0[1-9]|[12]\d|3[01])(0[13578]|1[02])((1[6-9]|[2-9]\d)?\d{2}))|((0[1-9]|[12]\d|30)(0[13456789]|1[012])((1[6-9]|[2-9]\d)?\d{2}))|((0[1-9]|1\d|2[0-8])02((1[6-9]|[2-9]\d)?\d{2}))|(2902((1[6-9]|[2-9]\d)?(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)|00))))$/;
		var errorMessage = 'ANDA SALAH MENGISI FIELD TANGGAL\n\nFORMAT TANGGAL YANG BENAR ADALAH :\n\nhari/bulan/tahun (13/12/1999)';
    	if ((fld.value.match(RegExPattern)) && (fld.value!='')) {
        	return true;
    	}else {
        	alert(errorMessage);
        	fld.value='';
			fld.focus();
      } }
	}

    function cnumber(e){
		var nv='';
		var pt=false;
		for (x=0; x<e.value.length; x++)
		{
			c = e.value.substring(x,x+1);
			if (isInt(c) || ((c == ',' || c=='.') && (pt == false)) || ((x == 0) && (c == '-')))
			{
				nv += c;
				if (c == '.') { pt=true; }
			}
		}
		e.value = nv;
	}

    function number(field,event){
		var kc = event.keyCode;
		var temp = field.value;

		if ( kc >= 65 && kc <= 90){ // letters
			window.alert('Isi Dengan Angka Saja');
			field.value = "";
		}
	}

    function isInt(v)
	{
		return (parseInt(v) == v);
	}

function rupiah(value)
{

value += '';
x = value.split('.');
x1 = x[0];
x2 = x.length > 1 ? '.' + x[1] : '';
var rgx = /(\d+)(\d{3})/;
while (rgx.test(x1)) {
x1 = x1.replace(rgx, '$1' + '.' + '$2');
}

return 'Rp ' + x1 + x2 + ",00";
};

function CurrencyFormatted(amount) {
		var i = parseFloat(amount);
		if(isNaN(i)) { i = 0.00; }
		var minus = '';
		if(i < 0) { minus = '-'; }
		i = Math.abs(i);
		i = parseInt((i + .005) * 100);
		i = i / 100;
		s = new String(i);
		if(s.indexOf('.') < 0) { s += '.'; }
		if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
		s = minus + s;
		return s;
	}
	function CommaFormatted(amount){
		var delimiter = ","; // replace comma if desired
		var a = amount.split('.',2)
		var d = a[1];
		var i = parseInt(a[0]);
		if(isNaN(i)) { return ''; }
		var minus = '';
		if(i < 0) { minus = '-'; }
		i = Math.abs(i);
		var n = new String(i);
		var a = [];
		while(n.length > 3)
		{
			var nn = n.substr(n.length-3);
			a.unshift(nn);
			n = n.substr(0,n.length-3);
		}
		if(n.length > 0) { a.unshift(n); }
		n = a.join(delimiter);
		if(d.length < 1) { amount = n; }
		else { amount = n + '.' + d; }
		amount = minus + amount;
		
		return amount;
		
	}
	/*======== end of Format Pecahan ========*/
	/*Menghilangkan Koma*/
	function removeCommas(str) {
		return str.replace(/,/g, "");
	}
	/*==== end of remove koma ====*/

	function focus_uang(field){
		pot = field.value;
		field.value= removeCommas(pot);
	}

	function blur_uang(field,flag){
		pot = field.value;
		
		var result = CurrencyFormatted(pot);
		var temp = CommaFormatted(result);
		if(flag){
			if(temp!=0){
				field.value=temp;	
			}else{
				field.value="";	
			}
		}else{
			field.value=temp;
		}
	}
    function setPostfixNo(){
        var x= new Date();
        var tgl = x.getDate();
        var bln = x.getMonth()+1;
        var thn = x.getFullYear();
        return tgl+'/0'+bln+'/'+thn;
    }
