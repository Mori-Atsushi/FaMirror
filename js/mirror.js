navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || window.navigator.mozGetUserMedia;
window.URL = window.URL || window.webkitURL;

var mirror = document.getElementById('mirror');
var localStream = null;
navigator.getUserMedia({video: true, audio: false},
	function(stream) {
		mirror.src = window.URL.createObjectURL(stream);
	}, function(err) {
		console.log(eer);
	});

