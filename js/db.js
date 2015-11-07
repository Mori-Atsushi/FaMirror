var user_data;

var send_db = function(url, data, callback) {
	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(data, dataType) {
			callback(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log('Error : ' + errorThrown);
		}
	});
}

var get_data = function(callback) {
	var url = 'php/data.php';
	var data;
	var set_data = function(data) {
		user_data = data;
		callback();
	};
	send_db(url, data, set_data) ;
}