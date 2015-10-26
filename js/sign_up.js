$(function() {
	var count;
	var family_no;
	var person_name;
	var face_id = new Array(max);

	var mail = $('#script').attr('mail');

	var flag = true; //trueなら撮影可能
	var max = 5; //写真を撮る枚数

	//写真撮影及び送信
	var roop = function() {
		var blob = snapshot();
		var formData = new FormData();
		formData.append('img', blob);

		var request_url = detection_detect_url + api + '&mode=oneface';
		send_img(request_url, formData, check_img);
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
			$('#message').text('もう一度やり直してください。');
			flag = true;
		}
	};

	//データベースにユーザー情報を登録する
	var regist_db = function() {
		var regist_url = './regist.php';
		var regist_data = {email : mail};
		$.ajax({
			url: regist_url,
			type: 'POST',
			data: regist_data,
			success: function(data, dataType) {
				family_no = data;
				new_person_create();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log('Error : ' + errorThrown);
			}
		});
	};

	//人を登録
	var new_person_create = function() {
		person_name = family_no + ':1';
		person_create(person_name, face_id, new_group_create);
	}

	//グループを登録
	var new_group_create = function() {
		group_name = family_no;
		group_create(group_name, person_name, finish);
	}

	//終了処理
	var finish = function() {
		$('#message').text('登録完了');
	};

	start_mirror();

	//ボタンイベント
	$("#shot").click( function() {
		if(flag == true) {
			$('#message').text('撮影中(1/5)');
			count = 0;
			flag = false;
			roop();
		}
	});
});