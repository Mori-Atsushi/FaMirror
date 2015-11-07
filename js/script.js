$(function() {
	var flag = true; //tureなら撮影可能
	var speed = 256; //アニメーションのスピード

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
		for(var i = 0; i < user_data.length; i++)
			$('#user_list').append('<li id="member_' + (i + 1) + '" class="member" >' + user_data[i].user_name + '</li>');

		//メンバー選択
		$('#user_list li').click( function() {
			create_detail($(this).attr('id').split('_')[1]);
		});
	};

	var create_detail = function(user_id) {
		$('#detail_user_name').text(user_data[0].user_name);
		$('#detail').animate({'left': '0%'}, speed);	
		$('#setting').animate({'left': '-100%'}, speed);
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

	//メンバー選択画面に戻る
	$('#detail_back').click(function() {
		$('#setting').animate({'left': '0%'}, speed);
		$('#detail').animate({'left': '100%'}, speed);
	});

});