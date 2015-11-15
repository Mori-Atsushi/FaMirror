var profile = function() {
	//画像変更
	var loading = 'icon/loading.gif';
	(new Image()).src = loading;

	var change_img = function(data) {
		var timestamp = new Date().getTime();
		user_data[user_id]['img'] = data + '?' + timestamp;
		$('#profile_icon').attr('src', 'icon/' + user_data[user_id]['img']);
		$('#detail_icon').css({ 'background-image' : 'url("./icon/' + user_data[user_id].img + '")'});
		$('#member_' + (user_id + 1) + ' div').css({ 'background-image' : 'url("./icon/' + user_data[user_id].img + '")'});
	};

	//プロフィールを開く
	$('#item_profile').click( function() {
		var d;
		$('#name').val(user_data[user_id]['user_name']);
		$('#name_p').val(user_data[user_id]['user_name_p']);
		if((d = user_data[user_id]['birthday']) != '')
			$('#birthday').val(d);
		$('#profile_icon').attr('src', 'icon/' + user_data[user_id]['img']);

		$('#setting_profile').addClass(color[user_id % 5]).animate({'left': '0%'}, speed);
		$('#detail').animate({'left': '-100%'}, speed);
	});

	//プロフィールを閉じる
	$('#profile_back').click( function() {
		user_data[user_id]['user_name'] = $('#name').val();
		user_data[user_id]['user_name_p'] = $('#name_p').val();
		user_data[user_id]['birthday'] = $('#birthday').val();

		var send_data = [
			{
				name : 'user_name',
				data : user_data[user_id]['user_name']
			}, {
				name : 'user_name_p',
				data : user_data[user_id]['user_name_p']
			}, {
				name : 'birthday',
				data : user_data[user_id]['birthday']
			}
		];
		send_setting(send_data, function(data) { console.log(data); });
		$('#member_' + (user_id + 1) + ' span').text(user_data[user_id].user_name);
		$('#detail_user_name').text(user_data[user_id].user_name);
		$('#setting_profile').animate({'left': '100%'}, speed, function() {
			$('#setting_profile').removeClass(color[user_id % 5]);
		});
		$('#detail').animate({'left': '0%'}, speed);
	});

	//fileが入力されたら
	$('#profile_icon_file').change( function() {
		$('#profile_icon_file_name').text($(this)[0].files[0].name);
		$('#profile_icon').attr('src', loading);
		send_icon($(this)[0].files[0], change_img);
	});

	//ユーザー削除ボタン
	$('#delete_button').click( function() {
		var popup = $('#user_delete');
		popup.children('p').text(user_data[user_id]['user_name']);
		$('#black_screen').fadeIn(speed);
		popup.animate({bottom: '40%'}, speed);		
	});

	$('#user_delete_ok').click( function() {
		send_delete();
	});

	$('#user_delete_cancel').click( function() {
		$('#black_screen').fadeOut(speed, function() {
			$('#user_delete').css({bottom: '100%'});
		});
	});
}