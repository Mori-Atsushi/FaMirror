$(function() {
	var count, max = 5; //写真を撮る枚数
	var family_id, user_id;
	var person_name;
	var face_id = new Array(max);

	var flag = true; //trueなら撮影可能

	//写真撮影及び送信
	var roop = function() {
		var blob = snapshot();
		detection_detect(blob, check_img);
	};

	//写真に人が写っていたか確認する。
	var check_img = function(data) {
		if(data.face.length == 1) {
			face_id[count++] = data.face[0].face_id;
			if(count >= max) {
				$('#message').text('登録中');
				regist_db();
			} else {
				$('#message').text('撮影中(' + (count + 1) + '/5)');
				roop();
			}
		} else {
			speak('撮影に失敗しました。顔がちゃんと写っていることを確認して、もう一度カメラボタンを押してください。');
			$('#message').text('もう一度やり直してください。');
			flag = true;
		}
	};

	//データベースにユーザー情報を登録する
	var regist_db = function() {
		var regist_url = './regist.php';
		$.ajax({
			url: regist_url,
			type: 'POST',
			success: function(data, dataType) {
				family_id = data.family_id;
				user_id = data.user_id;
				new_person_create();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log('Error : ' + errorThrown);
			}
		});
	};

	//人を登録
	var new_person_create = function() {
		person_name = family_id + ':' + user_id;
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
		window.location.href = "../mirror/";
	};

	//処理ここから
	start_mirror();
	speak('ようこそ、ファミラーへ。');
	speak('顔登録を開始します。画面に顔が映るようにして、カメラボタンを押してください。');

	//ボタンイベント
	$("#shot").click( function() {
		if(flag == true) {
			$('#message').text('撮影中(1/5)');
			speak('撮影中です。しばらくそのまま、お待ちください。');
			count = 0;
			flag = false;
			roop();
		}
	});
});