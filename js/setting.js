var setting = function() {
	var weather = 0, trash = 1, calendar = 1;
	var data, id, name, i;
	var regional_data = new Array(); //天気地域データ
	var listen_div = 0;

	//セレクタが入れ替わった時の動作
	var change_celect = function(name1, name2) {
		var select = $('#' + name + '_' + name2);
		var num = $('#' + name + '_' + name1).val();
		console.log('#' + name + '_' + name1);
		if(num == -1) {
			select.html('<option value="-1">先に都道府県を選択してください</option>');
		} else {
			num = num.split('_');
			var x = num[0], y = num[1];
			select.html('<option value="-1">地域を選択してください</option>');
			for(var i = 0; i < regional_data[id][x][name1][y]['area'].length; i++)
				select.append('<option value="' + regional_data[id][x][name1][y][name2][i]['id'] + '">' + regional_data[id][x][name1][y][name2][i]['name'] + '</option>');
		}
	};

	//disabledかそうでないか調べて設定する
	var change_onof = function() {
		var flag = !$('#' + name + '_notification').prop('checked');
		if(typeof(data['select']) != 'undefined') {
			for(var i = 0; i < data['select'].length; i++)
				$('#' + name + '_' + data['select'][i]['name']).prop("disabled", flag);
		}

		for(var i = 0; i < data['onof'].length; i++)
			$('#' + name + '_' + data['onof'][i]['name']).prop("disabled", flag);
	};

	// データを送信する
	var send_data = function(callback) {
		var d, num = 0;
		var setting_data = new Array();

		if(typeof(data['select']) != 'undefined') {
			for(var i = 0; i < data['select'].length; i++) {
				data['select'][i]['val'] = $('#' + name + '_' + data['select'][i]['name']).val();
				if(data['select'][i]['val'] == -1) {
					setting_data[num] = {
						name : name + '_' + data['select'][i]['name'],
						data : 'null'
					}
				} else {
					setting_data[num] = {
						name : name + '_' + data['select'][i]['name'],
						data : data['select'][i]['val']
					}
				}
				num++;
			}
		}

		for(var i = 0; i < data['onof'].length; i++) {
			data['onof'][i]['notification'] = $('#' + name + '_' + data['onof'][i]['name']).prop('checked');
			setting_data[num] = {
				name : name + '_' + data['onof'][i]['name'],
				data : data['onof'][i]['notification']
			}
			num++;
		}

		user_data[user_id]['setting'][id]['notification'] = $('#' + name + '_notification').prop('checked');

		setting_data[num] = {
			name : name + '_notification',
			data : user_data[user_id]['setting'][id]['notification']
		}

		user_data[user_id]['setting'][id]['config'] = data;
		send_setting(setting_data, callback);
	};

	//天気地域設定
	jQuery.getJSON('data/weatherArea.json', function(d) {
		console.log(d);
		regional_data[weather] = d;
		var select = $('#weather_prefecture');
		for(var i = 0; i < regional_data[weather].length; i++) {
			select.append('<optgroup label="' + regional_data[weather][i]['region'] + '">');
			for(var j = 0; j < regional_data[weather][i]['prefecture'].length; j++)
				select.append('<option value="' + i + '_' + j + '">' + regional_data[weather][i]['prefecture'][j]['name'] + '</option>');
		}
	});

	$('.settings_notification').change( change_onof ); //通知onof変更

	//サンプル再生
	$('.listen_sample').click( function() {
		if(listen_div == 0) {
			listen_div = $(this).parent('div');
			send_data( function(data) {
				listen_div.removeClass('play').addClass('stop');
				listen_sample(name);
			});
		} else {
			speakInit();
			listen_div.removeClass('stop').addClass('play');
			listen_div = 0;
		}
	});

	//各設定画面へ
	$('#detail_list li').click( function() {
		id = $('#detail_list li').index(this);
		name = $(this).attr('id').split('_')[1];
		data = user_data[user_id]['setting'][id]['config'];

		var flag = user_data[user_id]['setting'][id]['notification'] == '1';
		$('#' + name + '_notification').prop('checked', flag);
		console.log(flag);

		if(typeof(data['select']) != 'undefined') {
			for(i = 0; i < data['select'].length; i++) {
				if(data['select'][i]['val'] != null) {
					$('#' + name + '_' + data['select'][i]['name']).val(data['select'][i]['val']);
					if(i + 1 < data['select'].length)
						change_celect(data['select'][i]['name'], data['select'][i + 1]['name']);
				} else {
					$('#' + name + '_' + data['select'][i]['name']).val('-1');			
				}			
			}
		}

		for(var i = 0; i < data['onof'].length; i++) {
			flag = data['onof'][i]['notification'] == '1';
			$('#' + name + '_' + data['onof'][i]['name']).prop('checked', flag);			
		}

		change_onof();

		$('#setting_' + name).animate({'left': '0%'}, speed);
		$('#detail').animate({'left': '-100%'}, speed);

	});

	//データを送信して戻る
	$('.settings_back').click( function() {

		send_data( function(data) { console.log(data); });

		if(user_data[user_id]['setting'][id]['notification'] == 1)
			$('#item_' + name + ' div').addClass('checked');
		else
			$('#item_' + name + ' div').removeClass('checked');

		speakInit();
		$('#setting_' + name).animate({'left': '100%'}, speed);
		$('#detail').animate({'left': '0%'}, speed);
	});
};