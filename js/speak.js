var speak = function (message) {
	var msg = new SpeechSynthesisUtterance(message);
	msg.lang = "ja-JP"; // 言語指定
	window.speechSynthesis.speak(msg);
}