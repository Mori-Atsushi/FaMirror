var user_data, user_length;
var speed = 256; //アニメーションのスピード
var user_id;
var user_notif = new Array;

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
	user_length = data.length;
	for(var i = 0; i < user_length; i++) {
		user_notif[i] = new Array();
		user_notif[i][0] = data[i].weather_notification;
		user_notif[i][1] = data[i].trash_notification;
		user_notif[i][2] = data[i].calendar_notification;
	}
	user_data = data;
};

//設定を送る
var send_setting = function(setting_array, data_array) {
	var url = 'php/setting.php';
	var data = {
		user_id: (user_id + 1),
		setting: setting_array,
		data: data_array
	}
	send_db(url, data, function(data){console.log(data);} );	
}


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
}

