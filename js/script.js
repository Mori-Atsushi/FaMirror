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
		$('#user_list').html('');
		for(var i = 0; i < user_data.length; i++) {
			var id = 'member_' + (i + 1);
			$('#user_list').append('<li id="' + id + '" class="member" ><div></div>' + user_data[i].user_name + '</li>');
			$('#' + id).children('div').css({ 'background-image' : 'url("./icon/' + user_data[i].img + '")'});
		}
	};

	//設定項目選択画面生成
	var create_detail = function() {
		var detail = $('#detail');
		var setting = $('#setting');
		$('#detail_icon').css({ 'background-image' : 'url("./icon/' + user_data[user_id].img + '")'});
		$('#detail_user_name').text(user_data[user_id].user_name);
		for(var i = 0; i < user_data[user_id]['setting'].length ; i++) {
			if(user_data[user_id]['setting'][i]['notification'] == 1)
				$('#detail_list li').eq(i).children('div').addClass('checked');
			else
				$('#detail_list li').eq(i).children('div').removeClass('checked');			
		}

		detail.animate({'left': '0%'}, speed);	
		setting.animate({'left': '-100%'}, speed);
	};

	start_mirror(); //鏡開始
	get_data(); //メンバーデータ取得
	profile(); //プロフィール設定
	weather(); //天気設定

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
		create_member_list();
		$('#setting').animate({'left': '0%'}, speed);
	});

	//設定画面から戻る
	$('#setting_back').click( function() {
		$('#setting').animate({'left': '100%'}, speed);
	});

	//メンバー選択
	$('#user_list').on( 'click', 'li', function() {
		user_id = $(this).attr('id').split('_')[1] - 1;
		create_detail();
	});

	//メンバー選択に戻る
	$('#detail_back').click(function() {
		$('#detail').animate({'left': '100%'}, speed);
		$('#setting').animate({'left': '0%'}, speed);
	});
});