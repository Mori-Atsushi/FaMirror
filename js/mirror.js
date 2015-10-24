var video = document.getElementById('mirror');
var canvas = document.getElementById('canvas');
var img = document.getElementById('img');
var ctx = canvas.getContext('2d');
var localMediaStream = null;
var api_key = '64f0b8d4729734b49f231e5b0c1f4523';
var	api_secret = 'bABwx_lmF99mpbGy9M3ZSzsJqiiAoNpb';
var url = 'https://apius.faceplusplus.com/detection/detect';
var request_url = url + '?api_key=' + api_key + '&api_secret=' + api_secret;

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
		img.src = canvas.toDataURL('image/png');
	}
}

var roop = function() {
	snapshot();
	console.log(request_url);
	img.onload = function() {
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

		$.ajax({
			url: request_url,
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
		}).done(function( data ) {
			console.log(data.face[0]);
		});
		return false;		
	};

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
	roop();
});