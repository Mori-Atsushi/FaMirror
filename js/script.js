$(function() {
	var flag = true; //tureなら撮影可能
	var now_balloon;

	//顔認証のあとの処理
	var check_user = function(data) {
		if(data.face.length == 1) {
			user_id = data.face[0].candidate[0].person_name.split(':')[1] - 1;
			$('#message').text(user_data[user_id]['user_name']);
			get_info(data.face[0].candidate[0].person_name);
		} else {
			var messe = $('#message');
			var height_messe = messe.height();

			speak('認証に失敗しました。もう一度やり直してください。');
			$('#exp_text').text('もう一度認証ボタンを押してください。');
			messe.animate({'top' : -height_messe}, speed);
			$('#exp').animate({'bottom' : '0'}, speed);
			$('#base_header').animate({'top' : '0'}, speed);		
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
		send_db(url, data, speak_info);
	};

	//情報を話す。
	var speak_info = function(data) {
		speek_data = data;
		var message = data['message'];
		var num = user_data[user_id]['setting'].length - 1;
		var width = 0;
		var icon_box = $('#icon_box'), launcher = $('#launcher');
		var launcher_height = launcher.height(), launcher_width = launcher.width();
		var right_flag = $('#auth_cancel').css('position') == 'fixed';
		icon_box.html('');
		for(var i = 0; i < num; i++) {
			if(change_flag(user_data[user_id]['setting'][i]['notification'])) {
				icon_box.append('<li id="launcher_' + user_data[user_id]['setting'][i]['name'] + '" class="' + i + '"></li>');
				if(right_flag)
					width += icon_box.children('li').outerHeight(true);
				else
					width += icon_box.children('li').outerWidth(true);
				message += data['setting'][i]['speak'];
			}
		}
		speak(message, check_alarm);
		launcher.addClass(color[user_id]);
		if(right_flag) {
			icon_box.height(width);
			launcher
				.css({'display' : 'block', 'opacity' : 0, 'bottom' : 0, 'right' : -launcher_width})
				.animate({'right' : 0, 'opacity' : 100}, speed);
		} else {
			icon_box.width(width);
			launcher
				.css({'display' : 'block', 'opacity' : 0, 'bottom' : -launcher_height, 'right' : 0})
				.animate({'bottom' : 0, 'opacity' : 100}, speed);
		}

	};

	//設定画面作成
	var create_member_list = function() {
		for(var i = 0; i < user_data.length; i++) {
			var id = 'member_' + (i + 1);
			$('#user_list').append('<li id="' + id + '" class="member" ><div></div><span>' + user_data[i].user_name + '</span></li>');
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

		detail.addClass(color[user_id % 5]).animate({'left': '0%'}, speed);	
		setting.animate({'left': '-100%'}, speed);
	};

	start_mirror(); //鏡開始
	get_data(); //メンバーデータ取得
	profile(); //プロフィール設定
	setting();

	//認証開始
	$('#auth').click( function() {
		if(flag == true) {
			var messe = $('#message');
			var exp = $('#exp');
			var head = $('#base_header');

			var height_messe = messe.height();
			var height_exp = exp.height();
			var height_head = head.height();

			speak('認証中です。');
			flag = false;

			var blob = snapshot();
			recognition_identify('1', blob, check_user);

			messe.text('認証中…').css({'top' : -height_messe}).animate({'top' : 0}, speed);
			exp.animate({'bottom' : -height_exp}, speed);
			head.animate({'top' : -height_head}, speed);
		}
	});

	$('#auth_cancel').click( function() {
		speakInit();
		var messe = $('#message'), launcher = $('#launcher');
		var right_flag = $('#auth_cancel').css('position') == 'fixed';
		var height_messe = messe.height();

		if(right_flag) {
			var width = launcher.width();
			launcher.animate({'right' : -width, 'opacity' : 0}, speed, function() {
				launcher.removeClass(color[user_id]).css({'display' : 'none'});
			});
		} else {
			var height = launcher.height();
			launcher.animate({'bottom' : -height, 'opacity' : 0}, speed, function() {
				launcher.removeClass(color[user_id]).css({'display' : 'none'});
			});
		}

		messe.animate({'top' : -height_messe}, speed);
		$('#exp').animate({'bottom' : '0'}, speed);
		$('#base_header').animate({'top' : '0'}, speed);	
	});

	$('#showhide').click( function() {
		var launcher = $('#launcher');
		var right_flag = $('#auth_cancel').css('position') == 'fixed';
		var show_flag;
		if(right_flag)
			show_flag = launcher.css('right') == '0px';
		else
			show_flag = launcher.css('bottom') == '0px';			

		if(show_flag) {
			$(this).addClass('reverse');
			if(right_flag) {
				var width = launcher.width();
				launcher.animate({'right' : -width}, speed);
			} else {
				var height = launcher.height();
				launcher.animate({'bottom' : -height}, speed);
			}
		} else {
			$(this).removeClass('reverse');
			if(right_flag)
				launcher.animate({'right' : 0}, speed);
			else
				launcher.animate({'bottom' : 0}, speed);			
		}
	});

	$('#icon_box').on( 'click', 'li', function() {
		var name = $(this).attr('class');
		var balloon = $('#balloon');
		balloon.fadeOut(speed, function(){
			if(now_balloon == name) {
				now_balloon = '';
			} else {
				balloon.html('<h3>' + speek_data['setting'][name]['name'] + '</h3><ul></ul>');
				balloon_ul = balloon.children('ul');
				for(var i = 0; i < speek_data['setting'][name]['list'].length; i++)
					balloon_ul.append('<li><h4>' + speek_data['setting'][name]['list'][i]['name'] + '</h4><p>' + speek_data['setting'][name]['list'][i]['content'] + '</p></li>');
				balloon.fadeIn(speed);
				now_balloon = name;
			}
		});
	});

	$('#balloon').click( function() {
		$(this).fadeOut(speed);
	});

	//設定ボタンクリック
	$('#setting_b').click( function() {
		if(member_list_flag == true) {
			create_member_list(); //メンバー選択画面生成
			member_list_flag = false;
		}
		speakInit();
		$('#setting').animate({'left': '0%'}, speed);
		$('#base').animate({'left': '-100%'}, speed);
		$('#video').animate({'left': '-100%'}, speed);
	});

	//設定画面から戻る
	$('#setting_back').click( function() {
		$('#setting').animate({'left': '100%'}, speed);
		$('#base').animate({'left': '0%'}, speed);
		$('#video').animate({'left': '0%'}, speed);
	});

	//メンバー選択
	$('#user_list').on( 'click', 'li', function() {
		user_id = $(this).attr('id').split('_')[1] - 1;
		create_detail();
	});

	//メンバー選択に戻る
	$('#detail_back').click(function() {
		$('#detail').animate({'left': '100%'}, speed, function() {
			$('#detail').removeClass(color[user_id % 5]);
		});
		$('#setting').animate({'left': '0%'}, speed);
	});

	//フルスクリーン
	$('#fullscreen').click(function() {
		if(document.webkitIsFullScreen) {
			if(document.webkitCancelFullScreen)
				document.webkitCancelFullScreen();
		} else {
			if(document.body.webkitRequestFullScreen) {
				document.body.webkitRequestFullScreen();
				start_mirror(); //鏡開始
			}
		}

	});
});