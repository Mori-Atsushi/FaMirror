$(function() {
	var data;

	var change_celect = function(ok) {
		var select = $('#weather_area');
		var num = ok.val();
		if(num == -1) {
			select.html('<option value="-1">先に都道府県を選択してください</option>');
		} else {
			select.html('<option value="-1">地域を選択してください</option>');
			for(var i = 0; i < data[num]['area'].length; i++)
				select.append('<option value="' + data[num]['area'][i]['id'] + '">' + data[num]['area'][i]['name'] + '</option>');
		}
	};

	jQuery.getJSON('data/weatherArea.json', function(d) {
		data = d;
		var select = $('#weather_prefecture');
		for(var i = 0; i < data.length; i++)
			select.append('<option value="' + i + '">' + data[i]['prefecture'] + '</option>');
	});

	$('#weather_prefecture').change( function() {
		change_celect($(this));
	});

	$('#item_weather').click( function() {
		if(user_data[user_id]['weather_area'] != null) {
			$('#weather_prefecture').val(user_data[user_id]['weather_area']);
			change_celect($("#weather_prefecture"));
		}

		if(user_data[user_id]['weather_region'] != null)
			$('#weather_area').val(user_data[user_id]['weather_region']);

		$('#weather_detail').val(user_data[user_id]['weather_detail']);
		$('#weather_temperature').val(user_data[user_id]['weather_temperature']);
		$('#weather_tomorrow').val(user_data[user_id]['weather_tomorrow']);

		$('#setting_weather').animate({'left': '0%'}, speed);
		$('#detail').animate({'left': '-100%'}, speed);
	});

	$('#weather_back').click( function() {
		var d;
		var weather_region, weather_area;
		if((d = $('#weather_prefecture').val()) == -1) {
			user_data[user_id]['weather_area'] = null;
			weather_region = 'null';
		} else {
			user_data[user_id]['weather_area'] = d;
			weather_region = d;
		}

		if((d = $('#weather_area').val()) == -1) {
			user_data[user_id]['weather_region'] = null;
			weather_area = 'null';
		} else {
			user_data[user_id]['weather_region'] = d;
			weather_area = d;
		}

		user_data[user_id]['weather_detail'] = $('#weather_detail').val();
		user_data[user_id]['weather_temperature'] = $('#weather_temperature').val();
		user_data[user_id]['weather_tomorrow'] = $('#weather_tomorrow').val();

		var setting = ['weather_area', 'weather_region', 'weather_detail', 'weather_temperature', 'weather_tomorrow'];
		var data_array = [weather_region, weather_area, user_data[user_id]['weather_detail'], user_data[user_id]['weather_temperature'], user_data[user_id]['weather_tomorrow']];
		send_setting(setting, data_array);
		$(this).parents('section').animate({'left': '100%'}, speed);
		$('#detail').animate({'left': '0%'}, speed);
	});
});