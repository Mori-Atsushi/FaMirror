$(function() {
	var data;
	var weather_id = 0;

	//セレクタが入れ替わった時の動作
	var change_celect = function(ok) {
		var select = $('#weather_area');
		var num = ok.val();
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

	$('#weather_prefecture').change( function() {
		change_celect($(this));
	});

	//天気設定画面へ
	$('#item_weather').click( function() {
		if(user_data[user_id]['setting'][weather_id]['config']['prefecture'] != null) {
			$('#weather_prefecture').val(user_data[user_id]['setting'][weather_id]['config']['prefecture']);
			change_celect($("#weather_prefecture"));
		}

		if(user_data[user_id]['setting'][weather_id]['config']['area'] != null)
			$('#weather_area').val(user_data[user_id]['setting'][weather_id]['config']['area']);

		$('#weather_detail').val(user_data[user_id]['setting'][weather_id]['config']['detail']);
		$('#weather_temperature').val(user_data[user_id]['setting'][weather_id]['config']['temperature']);
		$('#weather_tomorrow').val(user_data[user_id]['setting'][weather_id]['config']['tomorrow']);

		$('#setting_weather').animate({'left': '0%'}, speed);
		$('#detail').animate({'left': '-100%'}, speed);
	});

	//データを送信して戻る
	$('#weather_back').click( function() {
		var d;
		var prefecture, area;
		if((d = $('#weather_prefecture').val()) == -1) {
			user_data[user_id]['setting'][weather_id]['config']['prefecture'] = null;
			prefecture = 'null';
		} else {
			user_data[user_id]['setting'][weather_id]['config']['prefecture'] = d;
			prefecture = d;
		}

		if((d = $('#weather_area').val()) == -1) {
			user_data[user_id]['setting'][weather_id]['config']['area'] = null;
			area = 'null';
		} else {
			user_data[user_id]['setting'][weather_id]['config']['area'] = d;
			area = d;
		}

		user_data[user_id]['setting'][weather_id]['config']['detail'] = $('#weather_detail').val();
		user_data[user_id]['setting'][weather_id]['config']['temperature'] = $('#weather_temperature').val();
		user_data[user_id]['setting'][weather_id]['config']['tomorrow'] = $('#weather_tomorrow').val();

		var send_data = [
			{
				name : 'weather_prefecture',
				data : prefecture
			}, {
				name : 'weather_area',
				data : area
			}, {
				name : 'weather_detail',
				data : user_data[user_id]['setting'][weather_id]['config']['detail']
			}, {
				name : 'weather_temperature',
				data : user_data[user_id]['setting'][weather_id]['config']['temperature']
			}, {
				name : 'weather_tomorrow',
				data : user_data[user_id]['setting'][weather_id]['config']['tomorrow']
			}
		];

		send_setting(send_data);
		$(this).parents('section').animate({'left': '100%'}, speed);
		$('#detail').animate({'left': '0%'}, speed);
	});
});