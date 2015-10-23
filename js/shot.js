$(function() {
	var video = document.getElementById('mirror');
	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');
	var mirror = $('#mirror');

	$('#shot').click(function() {
		ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
		document.getElementById('img').src = canvas.toDataURL('image/png');
	});
});