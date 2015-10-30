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

	$('#submit').click( function() {
		var url = './setting.php';
		var data = {
			name: $("#name").val()
    	};
    	console.log(JSON.stringify(data));
		$.ajax({
			url: url,
			type: 'POST',
			data: JSON.stringify(data),
			success: function(data, dataType) {
				$('.start_setting').fadeOut();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log('Error : ' + errorThrown);
			}
		});
	});
});