$(function() {
	var flag = true; //tureなら撮影可能
	var speed = 300; //アニメーションのスピード

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
		var url = './info.php';
		var id = user.split(':');
		var data = {
			user_id: id[1]
		};
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(data, dataType) {
				speak(data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log('Error : ' + errorThrown);
			}
		});
	};

	start_mirror();

	//認証開始
	$('#auth').click( function() {
		if(flag == true) {
			$('#message').text('認証中');
			flag = false;
			var blob = snapshot();
			recognition_identify('1', blob, check_user);
		}
	});

	$('.submit').click( function() {
		var seet = $(this).parents('.start_setting');
		var url = './setting.php';
		var data = {
			user_id: $(seet).attr('id'),
			name: $(seet).find('.name').val(),
			name_p: $(seet).find('.name_p').val()
		};
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(data, dataType) {
				if(data == true)
					$(seet).fadeOut();
				else
					console.log(data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log('Error : ' + errorThrown);
			}
		});
	});

	$('#setting').click( function() {
		$('#user_select').animate({'left': '0%'}, speed);
	});

	$('#back_top').click( function() {
		$('#user_select').animate({'left': '100%'}, speed);
	});
});