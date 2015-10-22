$(function() {
	var video = document.getElementById('mirror');
	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');
	var mirror = $('#mirror');

	$('#shot').click(function() {
		$('#canvas').width(mirror.width()).height(mirror.height());
		$('#img').width(mirror.width()).height(mirror.height());
		ctx.drawImage(video, 0, 0);
		document.getElementById('img').src = canvas.toDataURL('image/webp');
	});

});