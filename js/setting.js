var setting = function() {
	var weather = 0, trash = 1, calendar = 2, timetable = 3;
	var data, id, name, i;
	var regional_data = new Array(); //天気地域データ
	var regional_trash = ['都道府県', '市区町村', '地域１', '地域２'];
	var regional_timetable = ['学校名', '学年', 'クラス・学科'];
	var listen_div = 0;
	var next = new Array();

	//セレクタが入れ替わった時の動作
	var change_celect = function(no) {
		switch (id) {
			case weather :
				weather_change();
				break;
			case trash :
				trash_change(no);
				break;
			case timetable :
				timetable_change(no);
				break;
		}
	}

	var weather_change = function() {
		var select = $('#weather_area');
		var num = $('#weather_prefecture').val();
		if(num == -1) {
			select.html('<option value="-1">先に都道府県を選択してください</option>');
		} else {
			num = num.split('_');
			var x = num[0], y = num[1];
			select.html('<option value="-1">地域を選択してください</option>');
			for(var i = 0; i < regional_data[weather][x]['prefecture'][y]['area'].length; i++)
				select.append('<option value="' + regional_data[weather][x]['prefecture'][y]['area'][i]['id'] + '">' + regional_data[weather][x]['prefecture'][y]['area'][i]['name'] + '</option>');
		}
	};

	var trash_change = function(no) {
		if($('#' + name + '_' + data['select'][no + 1]['name']).val() != -3) {
			var num = $('#' + name + '_' + data['select'][no]['name']).val();
			if(num < 0) {
				if($('#' + name + '_' + data['select'][no + 1]['name']).val() != -1) {
					for(var i = no + 1; i < data['select'].length; i++) {
						var select = $('#' + name + '_' + data['select'][i]['name']);
						select.html('<option value="-1">先に' + regional_trash[no] + 'を選択してください</option>');
					}
				}
			} else {
				var next_name = data['select'][no + 1]['name'];
				var last = false;
				if(no == 0) {
					num = num.split('_');
					next[no] = regional_data[trash][num[0]]['prefecture'][num[1]]['city'];
				} else {
					next[no] = next[no - 1][num][next_name];
					if(typeof(next[no][0]['id']) != 'undefined')
						last = true;
				}
				var select = $('#' + name + '_' + next_name);
				select.html('<option value="-2">' + regional_trash[no + 1] + 'を選択してください</option>');

				for(var i = 0; i < next[no].length; i++) {
					if(last)
						select.append('<option value="' + next[no][i]['id'] + '">' + next[no][i]['name'] + '</option>');
					else
						select.append('<option value="' + i + '">' + next[no][i]['name'] + '</option>');
				}

				if(last && no < 2) {
					select = $('#' + name + '_' + data['select'][no + 2]['name']);
					select.html('<option value="-3">選択する必要はありません。</option>');
				} else {
					for(var i = no + 2; i < data['select'].length; i++) {
						select = $('#' + name + '_' + data['select'][i]['name']);
						select.html('<option value="-1">先に' + regional_trash[no + 1] + 'を選択してください</option>');
					}
				}
			}
		}
	};

	var timetable_change = function(no) {
		if($('#' + name + '_' + data['select'][no + 1]['name']).val() != -3) {
			var num = $('#' + name + '_' + data['select'][no]['name']).val();
			if(num < 0) {
				if($('#' + name + '_' + data['select'][no + 1]['name']).val() != -1) {
					for(var i = no + 1; i < data['select'].length; i++) {
						var select = $('#' + name + '_' + data['select'][i]['name']);
						select.html('<option value="-1">先に' + regional_timetable[no] + 'を選択してください</option>');
					}
				}
			} else {
				var next_name = data['select'][no + 1]['name'];
				if(no == 0)
					next[no] = regional_data[timetable][num]['grade'];
				else
					next[no] = next[no - 1][num][next_name];
				var select = $('#' + name + '_' + next_name);
				select.html('<option value="-2">' + regional_timetable[no + 1] + 'を選択してください</option>');

				for(var i = 0; i < next[no].length; i++) {
					if(no == 0)
						select.append('<option value="' + i + '">' + next[no][i]['name'] + '</option>');
					else
						select.append('<option value="' + next[no][i]['id'] + '">' + next[no][i]['name'] + '</option>');
				}

				for(var i = no + 2; i < data['select'].length; i++) {
					select = $('#' + name + '_' + data['select'][i]['name']);
					select.html('<option value="-1">先に' + regional_timetable[no + 1] + 'を選択してください</option>');
				}
			}
		}
	};

	//disabledかそうでないか調べて設定する
	var change_onof = function() {
		var flag = !$('#' + name + '_notification').prop('checked');
		if(typeof(data['select']) != 'undefined') {
			for(var i = 0; i < data['select'].length; i++)
				$('#' + name + '_' + data['select'][i]['name']).prop('disabled', flag);
		}

		if(typeof(data['onof']) != 'undefined') {
			for(var i = 0; i < data['onof'].length; i++)
				$('#' + name + '_' + data['onof'][i]['name']).prop('disabled', flag);
		}

		if(typeof(data['choose']) != 'undefined') {
			for(var i = 0; i < data['choose'].length; i++)
				$('#' + name + '_' + data['choose'][i]['name']).prop('disabled', flag);
		}

		if(flag)
			$('#setting_' + name).addClass('disabled')
		else
			$('#setting_' + name).removeClass('disabled')
	};

	// データを送信する
	var send_data = function(callback) {
		var d, num = 0;
		var setting_data = new Array();

		if(typeof(data['select']) != 'undefined') {
			for(var i = 0; i < data['select'].length; i++) {
				data['select'][i]['val'] = $('#' + name + '_' + data['select'][i]['name']).val();
				setting_data[num] = {
					name : name + '_' + data['select'][i]['name'],
					data : data['select'][i]['val']
				}
				num++;
			}
		}

		if(typeof(data['onof']) != 'undefined') {
			for(var i = 0; i < data['onof'].length; i++) {
				data['onof'][i]['notification'] = $('#' + name + '_' + data['onof'][i]['name']).prop('checked');
				setting_data[num] = {
					name : name + '_' + data['onof'][i]['name'],
					data : data['onof'][i]['notification']
				}
				num++;
			}
		}

		if(typeof(data['choose']) != 'undefined') {
			for(var i = 0; i < data['choose'].length; i++) {
				data['choose'][i]['val'] = $('#' + name + '_' + data['choose'][i]['name']).val();
				setting_data[num] = {
					name : name + '_' + data['choose'][i]['name'],
					data : data['choose'][i]['val']
				}
				num++;
			}
		}

		user_data[user_id]['setting'][id]['notification'] = $('#' + name + '_notification').prop('checked');

		setting_data[num] = {
			name : name + '_notification',
			data : user_data[user_id]['setting'][id]['notification']
		}

		user_data[user_id]['setting'][id]['config'] = data;
		send_setting(setting_data, callback);
	};

	var listen_finish = function() {
		listen_div.removeClass('stop').addClass('play');
		listen_div = 0;
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

	//ゴミ地域設定
	jQuery.getJSON('data/trashArea.json', function(d) {
		console.log(d);
		regional_data[trash] = d;
		var select = $('#trash_prefecture');
		for(var i = 0; i < regional_data[trash].length; i++) {
			select.append('<optgroup label="' + regional_data[trash][i]['region'] + '">');
			for(var j = 0; j < regional_data[trash][i]['prefecture'].length; j++)
				select.append('<option value="' + i + '_' + j + '">' + regional_data[trash][i]['prefecture'][j]['name'] + '</option>');
		}
	});

	//ゴミ地域設定
	jQuery.getJSON('data/timetableState.json', function(d) {
		console.log(d);
		regional_data[timetable] = d;
		var select = $('#timetable_school');
		for(var i = 0; i < regional_data[timetable].length; i++) {
			select.append('<option value="' + i + '">' + regional_data[timetable][i]['school'] + '</option>');
		}
	});

	$('.settings_notification').change( change_onof ); //通知onof変更

	//セレクタ変更
	$('.setting_select').change( function() {
		var this_select = $(this).attr('id').split('_')[1];
		for(var i = 0; i < data['select'].length; i++) {
			if(data['select'][i]['name'] == this_select)
				break;
		}
		change_celect(i);
	});

	//サンプル再生
	$('.listen_sample').click( function() {
		if(listen_div == 0) {
			listen_div = $(this).parent('div');
			send_data( function(data) {
				listen_div.removeClass('play').addClass('stop');
				listen_sample(name, listen_finish);
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

		if(typeof(data['select']) != 'undefined') {
			for(i = 0; i < data['select'].length; i++) {
				$('#' + name + '_' + data['select'][i]['name']).val(data['select'][i]['val']);
				if(i + 1 < data['select'].length)
					change_celect(i);
			}
		}

		if(typeof(data['onof']) != 'undefined') {
			for(var i = 0; i < data['onof'].length; i++) {
				flag = data['onof'][i]['notification'] == '1';
				$('#' + name + '_' + data['onof'][i]['name']).prop('checked', flag);			
			}
		}

		if(typeof(data['choose']) != 'undefined') {
			for(var i = 0; i < data['choose'].length; i++)
				$('#' + name + '_' + data['choose'][i]['name']).val(data['choose'][i]['val']);
		}

		change_onof();

		$('#setting_' + name).addClass(color[user_id % 5]).animate({'left': '0%'}, speed);
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
		$('#setting_' + name).animate({'left': '100%'}, speed, function() {
			$('#setting_' + name).removeClass(color[user_id % 5]);
		});
		$('#detail').animate({'left': '0%'}, speed);
	});
};