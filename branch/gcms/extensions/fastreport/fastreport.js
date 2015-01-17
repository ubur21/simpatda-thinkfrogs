function fastReportStart(name, fr3, format, param, attachment){
	//gcms_ajax_request('request.php?mod=fastreport&action=start&name=' + name + '&fr3=' + fr3 + '&format=' + format + '&param=' + param + '&attachment=' + attachment, _fastReportCheck, _fastReportFailed);
	var param = '&'+param ;
	gcms_open_report(fr3,format,param);
}

function fastReportStartGrid(name, fr3, format, param, attachment){
	gcms_open_report(fr3,format,param);
}
function _fastReportFailed(){
	window.alert('Report Gagal!');
}

function _fastReportCheck(out){
	gcms_ajax_request('request.php?mod=fastreport&action=check&out=' + out, _fastReportDone, _fastReportFailed);
}

function _fastReportDone(st){
	eval(st);
}