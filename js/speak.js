var speak = function (message) {
	var msg = new SpeechSynthesisUtterance(message);
	msg.lang = "ja-JP";
	window.speechSynthesis.speak(msg);
};