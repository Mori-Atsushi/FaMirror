var video = document.getElementById('mirror');
var canvas = document.getElementById('canvas');
var img = document.getElementById('img');
var ctx = canvas.getContext('2d');
var localMediaStream = null;

var api_key = '64f0b8d4729734b49f231e5b0c1f4523';
var	api_secret = 'bABwx_lmF99mpbGy9M3ZSzsJqiiAoNpb';
var api = '?api_key=' + api_key + '&api_secret=' + api_secret;

var url = 'https://apius.faceplusplus.com';
var detection_detect_url = url + '/detection/detect';
var person_create_url = url + '/person/create';
var group_create_url = url + '/group/create';

var count;
var flag = true; //trueなら撮影可能
var max = 5; //写真を撮る枚数
var family_no;
var person_name;
var face_id = new Array(max);

var mail = $('#script').attr('mail');

//カメラ使えるかチェック
var hasGetUserMedia = function() {
	return (navigator.getUserMedia || navigator.webkitGetUserMedia ||
		navigator.mozGetUserMedia || navigator.msGetUserMedia);
}

//エラー
var onFailSoHard = function(e) {
	console.log('エラー!', e);
};

//カメラ画像キャプチャ
var snapshot = function() {
	if (localMediaStream) {
		var w = $('#mirror').width();
		var h = $('#mirror').height();
		$('#canvas').attr('width', w);
		$('#canvas').attr('height', h);
		ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
	}
}

//face++に画像を送信する
var sent = function() {
	var can = canvas.toDataURL();
	var base64Data = can.split(',')[1], // Data URLからBase64のデータ部分のみを取得
	data = window.atob(base64Data), // base64形式の文字列をデコード
	buff = new ArrayBuffer(data.length),
	arr = new Uint8Array(buff),
	blob, i, dataLen;

	// blobの生成
	for( i = 0, dataLen = data.length; i < dataLen; i++){
		arr[i] = data.charCodeAt(i);
	}
	blob = new Blob([arr], {type: 'image/png'});
	var formData = new FormData();
	formData.append('img', blob);

	var request_url = detection_detect_url + api + '&mode=oneface';
	$.ajax({
		url: request_url,
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function(data, dataType) {
			if(data.face.length == 1) {
				face_id[count++] = data.face[0].face_id;
				if(count >= max) {
					$('#message').text('撮影完了');
					regist_db();
				} else {
					$('#message').text('撮影中(' + (count + 1) + '/5)');
					roop();
				}
			} else {
				$('#message').text('もう一度やり直してください。');
				flag = true;
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log('Error : ' + errorThrown);
		}
	});
}

var roop = function() {
	snapshot();
	sent();
}

//データベースにユーザー情報を登録する
var regist_db = function() {
	var regist_url = './regist.php';
	var regist_data = {email : mail};
	$.ajax({
		url: regist_url,
		type: 'POST',
		data: regist_data,
		success: function(data, dataType) {
			console.log(data);
			family_no = data;
			person_create();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log('Error : ' + errorThrown);
        }

	});
}

//face++に人を登録する
var person_create = function() {
	person_name = '&person_name=' + family_no + ':1';
	var face = '&face_id=';
	for(var i = 0; i < max; i++) {
		face += face_id[i];
		if(i < max - 1)
			face += ',';
	}

	var request_url = person_create_url + api + person_name + face;

	$.ajax({
		url: request_url,
		type: 'POST',
		success: function(data, dataType) {
			group_create();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log('Error : ' + errorThrown);
		}
	});
}

//face++のグループを作成する
var group_create = function() {
	var group_name = '&group_name=' + family_no;
	var request_url = group_create_url + api + group_name + person_name;
	console.log(request_url);
	$.ajax({
		url: request_url,
		type: 'POST',
		success: function(data, dataType) {
			console.log(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log('Error : ' + errorThrown);
		}
	});
}
 
if (!hasGetUserMedia()) {
    alert("未対応ブラウザです。");
}
 
window.URL = window.URL || window.webkitURL;
navigator.getUserMedia  = navigator.getUserMedia || navigator.webkitGetUserMedia ||
                          navigator.mozGetUserMedia || navigator.msGetUserMedia;
navigator.getUserMedia({video: true}, function(stream) {
  video.src = window.URL.createObjectURL(stream);
  localMediaStream = stream;
}, onFailSoHard);

//ボタンイベント
$("#shot").click( function() {
	if(flag == true) {
		$('#message').text('撮影中(1/5)');
		count = 0;
		flag = false;
		roop();
	}
});