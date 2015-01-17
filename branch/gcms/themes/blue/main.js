
var windowList = new Array();
var windowCount = 0;

function arrangeWindowDiv() {
  wlist = document.getElementById("wnd_list");
  dy = 0;
  y = 0;
  h = 0;
  for (i = 0; i < windowList.length; i ++) {
    div = windowList[i][1];
    if (windowList[i][0]) {
	  dx = 0;
      for (j = 0; j < windowList.length; j ++) {
        if (windowList[j][0] && (windowList[j][3] < windowList[i][3])) dx += windowList[j][1].offsetWidth;
	  }
      div.style.left = dx;
      div.style.top = dy + y;
      if (h < div.offsetHeight) h = div.offsetHeight;
	}
    dy -= div.offsetHeight;
  }
  wlist.style.height = h;
}

function initWindowDiv(div, wnd, icon) {
  windowCount ++;
  div.setAttribute('class', 'window_icon');
  div.style.cursor = 'pointer';
  if (!icon || (icon == 'undefined')) icon = 'images/window_small.png';
  div.innerHTML = '<div class="box_win">'+ wnd.document.title+'</div>';
  div.style.visibility = 'visible';
  arrangeWindowDiv();
}

/** 
 * membuka window anak - fungsi ini harus ada
 */
function addSubWindow(wnd, icon) {
  div = null;
  for (i = 0; i < windowList.length; i ++) {
    /* sudah ada, tolak! */
    if (windowList[i][0] == wnd) return;
    /* hasil reload? cari posisi lama... */
    if (!windowList[i][0] && (windowList[i][2] == wnd)) {
      windowList[i][0] = wnd;
      div = windowList[i][1];
	  initWindowDiv(div, wnd, icon);
      break;
    }    
  }
  /* nggak nemu, ini pasti window baru */
  if (!div) {
    for (i = 0; i < windowList.length; i ++) {
      /* re-use tempat dalam array */
      if (!windowList[i][0]) {
        windowList[i][0] = wnd;
        windowList[i][2] = wnd;
		windowList[i][3] = windowCount;
		initWindowDiv(windowList[i][1], wnd, icon);
        break;
      }    
    }
  }
  /* array penuh, tambahkan */
  if (!div) {
    w = new Array();
    w[0] = wnd;
    w[2] = wnd;
    div = document.createElement('div');
    w[1] = div;
	w[3] = windowCount;
    windowList.push(w);
    n = windowList.length - 1;
    div.setAttribute('id', 'divWnd' + n.toString());
    eval('div.onclick = function() { windowList['+n+'][0].focus(); };');
    wlist = document.getElementById("wnd_list");
    wlist.appendChild(div);
	initWindowDiv(div, wnd, icon);
  }
}

/** 
 * menutup window anak - fungsi ini harus ada
 */
function removeSubWindow(wnd) {
  n = -1;
  for (i = 0; i < windowList.length; i ++) {
    if (windowList[i][0] == wnd) {
      div = windowList[i][1]; 
      div.style.visibility = "hidden";
      windowList[i][0] = null;
	  n = windowList[i][3];
    }
  }
  for (i = 0; i < windowList.length; i ++) {
    if (windowList[i][3] > n) windowList[i][3] --;
  }
  windowCount --;
  arrangeWindowDiv();
}
