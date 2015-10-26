var video = document.getElementById('mirror');
var canvas = document.getElementById('canvas');
var ctx = canvas.getContext('2d');
var localMediaStream = null;

//カメラ使えるかチェック
var hasGetUserMedia = function() {
	return (navigator.getUserMedia || navigator.webkitGetUserMedia ||
		navigator.mozGetUserMedia || navigator.msGetUserMedia);
};

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

		return blob;
	}
};

var start_mirror = function() {
	if(!hasGetUserMedia()) {
		alert("未対応ブラウザです。");
	}

	window.URL = window.URL || window.webkitURL;
	navigator.getUserMedia  = navigator.getUserMedia || navigator.webkitGetUserMedia ||
								navigator.mozGetUserMedia || navigator.msGetUserMedia;
	navigator.getUserMedia({video: true}, function(stream) {
		video.src = window.URL.createObjectURL(stream);
		localMediaStream = stream;
	}, onFailSoHard);
}