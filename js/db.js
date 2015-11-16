var user_data, user_length, speek_data;
var speed = 256; //アニメーションのスピード
var user_id;
var member_list_flag = true; //メンバーリストを生成したらfalseへ
var color = ['pink', 'blue', 'green', 'red', 'bgreen'];

//サーバーにデータを送る
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

//ユーザー情報を取得する
var get_data = function() {
	var url = 'php/data.php';
	var data;
	send_db(url, data, set_data);
}

//データをセット
var set_data = function(data) {
	console.log(data);
	user_length = data.length;
	user_data = data;
};

//設定を送る
var send_setting = function(setting_array, callback) {
	var url = 'php/setting.php';
	var data = {
		user_id: (user_id + 1),
		set: setting_array,
	}
	send_db(url, data, callback);
};

//通知情報の設定を行う
var send_notification = function(setting_name, data, num) {
	if(data == true)
		data = 1;
	else
		data = 0;

	var setting_array = [setting_name + '_notification'];
	var data_array = [data];
	user_notif[user_id][num] = data;
	send_setting(setting_array, data_array);
};

var listen_sample = function(type, callback) {
	var url = 'php/sample.php';
	var data = {
		user_id : (user_id + 1),
		type : type
	};
	var back = function(data) {
		speak(data['speak'], callback);
	}
	send_db(url, data, back);
}

//アイコンを送る
var send_icon = function(file, callback) {
	var url = 'php/upload_icon.php';
	var fd = new FormData();
	fd.append('img', file);
	fd.append('user_id', (user_id + 1));

	$.ajax({
		processData: false,
		contentType: false,
		url: url,
		type: 'POST',
		data: fd,
		success: function(data, dataType) {
			callback(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log('Error : ' + errorThrown);
		}
	});
};

//ユーザー削除
var send_delete = function() {
	var url = 'php/user_delete.php';
	var data = {
		user_id : (user_id + 1)
	};

	var $form = $('<form/>', {'action': url, 'method': 'post'});
	for(var key in data)
		$form.append($('<input/>', {'type': 'hidden', 'name': key, 'value': data[key]}));
	$form.appendTo(document.body);
	$form.submit();
};

