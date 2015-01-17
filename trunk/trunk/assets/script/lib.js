/**/
/* === MEMBUKA WINDOW BARU ========================================================================== */

/* tampilkan form */
function wnd_open_form(url, wname, width, height) {
  gcms_open_form_int(url, wname, width, height);
}

/* tampilkan form - internal use only */
function wnd_open_form_int(url, wname, width, height) {
	//half the screen width minus half the new window width (plus 5 pixel borders).
	iMyWidth = (window.screen.width/2) - (100 + 75);
	//half the screen height minus half the new window height (plus title and status bars).
	iMyHeight = (window.screen.height/2) - (100 + 50);	
	if (window.opener) owner = window.opener; 
	else owner = window;
	wnd = owner.open(url, wname, 'width=' + width + ',height=' + height + 'left=' + iMyWidth + ',top=' + iMyHeight + ',screenX=' + iMyWidth + ',screenY='+ iMyHeight + ',toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,copyhistory=0,resizable=0');
	wnd.opener = owner;
	wnd.focus();
	return wnd;
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

    function isInt(v)
	{
		return (parseInt(v) == v);
	}

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
	
	function currentDate(){
		var x= new Date();
		var tgl = x.getDate();
		var bln = x.getMonth()+1;
		var thn = x.getFullYear();
		return tgl+'/0'+bln+'/'+thn;
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