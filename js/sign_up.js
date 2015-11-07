$(function() {
	var count, max = 5; //写真を撮る枚数
	var family_id, user_id;
	var face_id = new Array(max);
	var speed_slow = 1000, speed_fast = 100;
	var name, name_p;

	var flag = true; //trueなら撮影可能

	//写真撮影及び送信
	var roop = function() {
		$('#progress').text((count + 1) + '/5');
		$('#progress_gage').animate({width: (count * 20 + 10) + '%'}, speed_slow);
		var blob = snapshot();
		detection_detect(blob, check_img);
	};

	//写真に人が写っていたか確認する。
	var check_img = function(data) {
		if(data.face.length == 1) {
			face_id[count++] = data.face[0].face_id;
			if(count >= max) {
				$('#message').text('登録中…');
				$('#progress_gage').animate({width: '100%'}, speed_slow / 2);
				get_name();				
			} else {
				roop();
			}
		} else {
			speak('撮影に失敗しました。顔がちゃんと写っていることを確認して、もう一度カメラボタンを押してください。');
			$('#message').text('もう一度やり直してください。');
			$('#progress_gage').animate({width: '0%'}, speed_fast);
			$('#progress').text('');
			flag = true;
		}
	};

	//表示名と読み仮名を取得
	var get_name = function() {
		var popup = $('#get_name_popup');
		var seet = $('#black_screen');
		seet.fadeIn();
		popup.animate({top: '30%'});
		$('#get_name_submit').click( function() {
			name = popup.find('.name').val();
			name_p = popup.find('.name_p').val();
			seet.fadeOut('normal', function() {
				seet.remove();
			});
			regist_db();
		});
	};

	//データベースにユーザー情報を登録する
	var regist_db = function() {
		var url = 'php/regist.php';
		var data = {
			name: name,
			name_p: name_p
		};
		send_db(url, data, new_person_create);
	};

	//人を登録
	var new_person_create = function(data) {
		family_id = data.family_id;
		user_id = data.user_id;
		var person_name = family_id + ':' + user_id;
		var callback;
		if(user_id == 1)
			callback = function(){ group_create(family_id, person_name, finish); };
		else
			callback = function(){ group_add_person(family_id, person_name, finish); };

		person_create(person_name, face_id, callback);
	}

	//終了処理
	var finish = function() {
		$('#message').text('登録完了');
		speak('登録が完了しました。');
	};

	//処理ここから
	speak('ようこそ、ファミラーへ。');
	speak('顔登録を開始します。画面に顔が映るようにして、カメラボタンを押してください。');

	//ボタンイベント
	$("#shot").click( function() {
		if(flag == true) {
			$('#message').text('そのままお待ち下さい…');
			speak('撮影中です。しばらくそのまま、お待ちください。');
			count = 0;
			flag = false;
			roop();
		}
	});
});