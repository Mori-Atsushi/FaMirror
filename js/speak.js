/*
	speak(string);  // 読み上げする

	引数: 読み上げさせたい文字列
	戻り値: なし
	詳細:
	> 文字列に句点、エクスクラメーションマーク、クエスチョンマークが含まれていたら、
	  そこで区切って、読み上げのキューに入れる。
	> このメソッドが呼び出されると、現在の読み上げを停止して、新たに読み上げの
	  キューに入れる。
	> リロード時はキャンセルせずに残る。
	> 対応言語は日本語


	speakInit();  // 読み上げ停止

	戻り値: なし
	詳細:
	> 読み上げを強制的に停止する。

*/

var msg = [];

var speakInit = function() {
	if(msg[0] === undefined)
		return ;
	speechSynthesis.cancel();
	msg = [];
};

var speak = function (message) {
	speakInit();
	message = message.replace(/ /g, '');
	var texts = message.split(/[。！？.!?]/);
	var len = texts.length;

	for(var i = 0; i < len; i++) {
		if(texts[i] !== '') {
			msg[i] = new SpeechSynthesisUtterance(texts[i]);
			msg[i].lang = "ja-JP";
			speechSynthesis.speak(msg[i]);
		}
	}
};
