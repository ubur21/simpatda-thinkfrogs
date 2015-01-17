/* resize divs sesuai ukuran window */

function resize_divs() {
  var con = document.getElementById("container-page");
  var cnt = document.getElementById("content");
  var hdr = document.getElementById("form_header");

  cnt.style.height = con.offsetHeight - hdr.offsetHeight;
  cnt.style.height = cnt.style.height;
}

/* cek size periodik */

var form_oldw = 0;
var form_oldh = 0;

function form_check_size() {
  var con = document.getElementById("container-page");
  if ((form_oldw != con.offsetWidth) || (form_oldh != con.offsetHeight)) {
    resize_divs();
    form_oldw = con.offsetWidth;
    form_oldh = con.offsetHeight;
  }
}

