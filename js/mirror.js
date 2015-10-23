var video = document.getElementById('mirror');
var canvas = document.getElementById('canvas');
var img = document.getElementById('img');
var ctx = canvas.getContext('2d');
var localMediaStream = null;
 
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
		ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
		img.src = canvas.toDataURL('image/png');
	}
}


//顔認証
var facecheck = function() {
	img.onload = function() {
	console.log('check');
		$('#img').faceDetection({
			complete: function(obj) {
				if(obj.length >  0)
					console.log('OK');
				else
					console.log('No');						
			}, error: function(code, message) {
				// エラーすると原因を示すテキストを取得できるのでアラート表示する
				alert( 'Error:' + message ) ;
			}
		});
		img.src = '';
	}
}

var roop = function() {
	snapshot();
	facecheck();
	setTimeout(roop, 2000);
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