var weather = function() {
	var data;
	var weather_id = 0;
	var weather_data;

	//セレクタが入れ替わった時の動作
	var change_celect = function() {
		var select = $('#weather_area');
		var num = $('#weather_prefecture').val();
		if(num == -1) {
			select.html('<option value="-1">先に都道府県を選択してください</option>');
		} else {
			num = num.split('_');
			var x = num[0], y = num[1];
			select.html('<option value="-1">地域を選択してください</option>');
			for(var i = 0; i < data[x]['prefecture'][y]['area'].length; i++)
				select.append('<option value="' + data[x]['prefecture'][y]['area'][i]['id'] + '">' + data[x]['prefecture'][y]['area'][i]['name'] + '</option>');
		}
	};

	var change_onof = function() {
		var flag = !$('#weather_notification').prop('checked');
		$('#weather_prefecture').prop("disabled", flag);
		$('#weather_area').prop("disabled", flag);
		for(var i = 0; i < weather_data['onof'].length; i++)
			$('#weather_' + weather_data['onof'][i]['name']).prop("disabled", flag);
	};

	var send_weather_data = function(callback) {
		var d;
		var prefecture, area;
		if((d = $('#weather_prefecture').val()) == -1) {
			weather_data['prefecture'] = null;
			prefecture = 'null';
		} else {
			weather_data['prefecture'] = d;
			prefecture = d;
		}

		if((d = $('#weather_area').val()) == -1) {
			weather_data['area'] = null;
			area = 'null';
		} else {
			weather_data['area'] = d;
			area = d;
		}

		user_data[user_id]['setting'][weather_id]['notification'] = $('#weather_notification').prop('checked');

		for(var i = 0; i < weather_data['onof'].length; i++)
			weather_data['onof'][i]['notification'] = $('#weather_' + weather_data['onof'][i]['name']).prop('checked');	

		var send_data = [
			{
				name : 'weather_notification',
				data : user_data[user_id]['setting'][weather_id]['notification']
			}, {
				name : 'weather_prefecture',
				data : prefecture
			}, {
				name : 'weather_area',
				data : area
			}, {
				name : 'weather_detail',
				data : weather_data['onof'][0]['notification']
			}, {
				name : 'weather_temperature',
				data : weather_data['onof'][1]['notification']
			}, {
				name : 'weather_tomorrow',
				data : weather_data['onof'][2]['notification']
			}
		];

		send_setting(send_data, callback);
	};

	jQuery.getJSON('data/weatherArea.json', function(d) {
		console.log(d);
		data = d;
		var select = $('#weather_prefecture');
		for(var i = 0; i < data.length; i++) {
			select.append('<optgroup label="' + data[i]['region'] + '">');
			for(var j = 0; j < data[i]['prefecture'].length; j++)
				select.append('<option value="' + i + '_' + j + '">' + data[i]['prefecture'][j]['name'] + '</option>');
		}
	});

	$('#weather_prefecture').change( change_celect ); //セレクタ変更
	$('#weather_notification').change( change_onof ); //通知onof変更
	$('#weather_sample').click( function() {
		send_weather_data( function(data) {
			listen_sample('weather');
		});
	}); //サンプル再生

	//天気設定画面へ
	$('#item_weather').click( function() {
		weather_data = user_data[user_id]['setting'][weather_id]['config'];

		var flag = user_data[user_id]['setting'][weather_id]['notification'] == '1';
		$('#weather_notification').prop('checked', flag);


		if(weather_data['prefecture'] != null) {
			$('#weather_prefecture').val(weather_data['prefecture']);
			change_celect();
		} else {
			$('#weather_prefecture').val('-1');			
		}

		if(weather_data['area'] != null)
			$('#weather_area').val(weather_data['area']);
		else
			$('#weather_area').val('-1');

		for(var i = 0; i < weather_data['onof'].length; i++) {
			flag = weather_data['onof'][i]['notification'] == '1';
			$('#weather_' + weather_data['onof'][i]['name']).prop('checked', flag);			
		}

		change_onof();

		$('#setting_weather').animate({'left': '0%'}, speed);
		$('#detail').animate({'left': '-100%'}, speed);
	});

	//データを送信して戻る
	$('#weather_back').click( function() {
		send_weather_data( function(data) { console.log(data); });

		if(user_data[user_id]['setting'][weather_id]['notification'] == 1)
			$('#item_weather div').addClass('checked');
		else
			$('#item_weather div').removeClass('checked');

		$('#setting_weather').animate({'left': '100%'}, speed);
		$('#detail').animate({'left': '0%'}, speed);
	});
};