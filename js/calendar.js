var calendar = function() {
	var data;
	var calendar_id = 2;
	var calendar_data;

	//ゴミ設定画面へ
	$('#item_calendar').click( function() {
		calendar_data = user_data[user_id]['setting'][calendar_id]['config'];

		var flag = user_data[user_id]['setting'][calendar_id]['notification'] == '1';
		$('#calendar_notification').prop('checked', flag);


/*		if(weather_data['prefecture'] != null) {
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

		change_onof();*/

		$('#setting_calendar').animate({'left': '0%'}, speed);
		$('#detail').animate({'left': '-100%'}, speed);
	});

	//データを送信して戻る
	$('#calendar_back').click( function() {
/*		var d;
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

		send_setting(send_data);
		if(user_data[user_id]['setting'][weather_id]['notification'] == 1)
			$('#item_weather div').addClass('checked');
		else
			$('#item_weather div').removeClass('checked');*/

		$('#setting_calendar').animate({'left': '100%'}, speed);
		$('#detail').animate({'left': '0%'}, speed);
	});
}