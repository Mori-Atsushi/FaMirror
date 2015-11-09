$(function() {
	var flag = true; //tureなら撮影可能

	//顔認証のあとの処理
	var check_user = function(data) {
		if(data.face.length == 1) {
			$('#message').text(data.face[0].candidate[0].confidence + '%あなたは' + data.face[0].candidate[0].person_name + 'です。');
			get_info(data.face[0].candidate[0].person_name);
		} else {
			$('#message').text('認証失敗');			
		}
		flag = true;
	};

	//音声案内用の文章を取得
	var get_info = function(user) {
		var url = 'php/info.php';
		var id = user.split(':');
		var data = {
			user_id: id[1]
		};
		send_db(url, data, speak);
	};

	//設定画面作成
	var create_member_list = function() {
		for(var i = 0; i < user_data.length; i++) {
			var id = 'member_' + (i + 1);
			$('#user_list').append('<li id="' + id + '" class="member" ><div></div>' + user_data[i].user_name + '</li>');
			$('#' + id).children('div').css({ 'background-image' : 'url("./icon/' + user_data[i].img + '")'});
		}

		//メンバー選択
		$('#user_list li').click( function() {
			user_id = $(this).attr('id').split('_')[1] - 1;
			create_detail();
		});
	};

	//設定項目選択画面生成
	var create_detail = function() {
		var detail = $('#detail');
		var setting = $('#setting');
		$('#detail_icon').css({ 'background-image' : 'url("./icon/' + user_data[user_id].img + '")'});
		$('#detail_user_name').text(user_data[user_id].user_name);
		for(var i = 0; i < user_notif[user_id].length ; i++) {
			if(user_notif[user_id][i] == 1)
				$('#detail_list li').eq(i).children('div').addClass('checked');
			else
				$('#detail_list li').eq(i).children('div').removeClass('checked');			
		}

		detail.animate({'left': '0%'}, speed);	
		setting.animate({'left': '-100%'}, speed);

		$('#detail_back').click(function() {
			detail.animate({'left': '100%'}, speed);
			setting.animate({'left': '0%'}, speed);
		});
	};

	//通知のオンオフ
	var notification_toggle = function(icon_div) {
		icon_div.toggleClass('checked');
		var data = icon_div.attr('class')  == 'checked';
		var setting_name = icon_div.parent('li').attr('id').split('_')[1];
		var num = $('#detail_list li').index(icon_div.parent('li'));
		send_notification(setting_name, data, num);
	};

	//各設定画面の作成
	var create_setting = function(setting_name) {
		var seen = $('#setting_' + setting_name);
		var detail = $('#detail');
		seen.animate({'left': '0%'}, speed);
		detail.animate({'left': '-100%'}, speed);
	};

	start_mirror(); //鏡開始
	get_data(create_member_list); //メンバーデータ取得

	//認証開始
	$('#auth').click( function() {
		if(flag == true) {
			$('#message').text('認証中');
			flag = false;
			var blob = snapshot();
			recognition_identify('1', blob, check_user);
		}
	});

	//設定ボタンクリック
	$('#setting_b').click( function() {
		$('#setting').animate({'left': '0%'}, speed);
	});

	//設定画面から戻る
	$('#setting_back').click( function() {
		$('#setting').animate({'left': '100%'}, speed);
	});

	$('#detail_list li div').click( function(e) {
		e.stopPropagation();
		notification_toggle($(this));
	});
});