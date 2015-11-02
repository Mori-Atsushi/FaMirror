$(function() {
	var flag = true; //tureなら撮影可能

	var check_user = function(data) {
		console.log(data);
		if(data.face.length == 1) {
			$('#message').text(data.face[0].candidate[0].confidence + '%あなたは' + data.face[0].candidate[0].person_name + 'です。');
		} else {
			$('#message').text('認証失敗');			
		}
		flag = true;
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
			name: $(seet).find('.name').val()
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
});