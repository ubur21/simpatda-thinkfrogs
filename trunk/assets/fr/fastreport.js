jQuery.download = function(url, data, method){
	//url and data options required
	if( url && data ){ 
		//data can be string of parameters or array/object
		data = typeof data == 'string' ? data : jQuery.param(data);
		//split params into form inputs
		var inputs = '';
		jQuery.each(data.split('&'), function(){ 
			var pair = this.split('=');
			inputs+='<input type="hidden" name="'+ pair[0] +'" value="'+ pair[1] +'" />'; 
		});
		//send request
		jQuery('<form action="'+ url +'" method="'+ (method||'post') +'">'+inputs+'</form>')
		.appendTo('body').submit().remove();
	};
};

function fastReportStart(_name, _fr3, _format, _opt, _att){
	jQuery.ajax({
		type: "POST",
		url: root + '/fastreport/start',
		data: { name: _name, fr3: _fr3, format: _format, opt: _opt, att: _att },
		beforeSend: function(){
			jQuery('#lock_screen').html('<div class="mask_layer"><div>Sedang proses...<br><img src="http://'+location.host+'/simpatda_ci/themes/brown/images/ajax-loader.gif"></div></div>');
		},
		success: function(data) { 
			jQuery('#lock_screen').html('');
			var url = root + '/fastreport/view/' + data + '/' + _name + '/' + _format + '/' + _att;
			window.open( url ,'_blank');
		}, 
		error: function(data){ alert(data) }
	});
	//	$.download('fastreport/start', 'name='+_name+'&fr3='+_fr3+'&format='+_format+'&att='+_att)
}
