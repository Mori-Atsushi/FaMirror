/*
	speak.js  Ver 3

	[ Ver 2 との主な変更点 ]
	- 中点を読点に自動置換
	-　speak()の引数にstopFlag追加

	[ Ver 1 との主な変更点 ]
	- speak()の引数にcallback追加



	speak(string, callback, stopFlag);  // 読み上げする

	引数
	  > string    # 読み上げさせたい文字列
	  > callback  # コールバック関数(省略可能)
	  > initFlag  # 現在の再生を停止させるかどうか(省略可能:初期値true)
	                true: 停止させる
	                false: 停止させない
	戻り値
	  > なし
	詳細
	  > 文字列に句点、エクスクラメーションマーク、クエスチョンマークが含まれていたら、
	    そこで区切って、読み上げのキューに入れる。
	  > このメソッドが呼び出されると、現在の読み上げを停止して、新たに読み上げの
	    キューに入れる。
	  > リロード時はキャンセルせずに残る。
	  > 対応言語は日本語。
	  > 読み上げ中に speakInit() によってキャンセルされた場合は、callback
	    は、起動しない。
	  > 第2引数に null を指定、あるいは省略すると初期値として function(){}
	    が指定される。
	  > 第3引数に false を指定すると、再生中の読み上げは停止させずに、新たに
	    読み上げのキューに入れる。省略すると、初期値 true が指定される。



	speakInit();  // 読み上げ停止

	戻り値
	  > なし
	詳細
	  > 読み上げを強制的に停止する。

*/

var msg = [];

var speakInit = function() {
	if(msg[0] === undefined)
		return ;
	speechSynthesis.cancel();
	msg[msg.length-1].onend = function(){};
	msg = [];
};

var speak = function (message, callback, initFlag) {
	if(callback === undefined || callback === null)
		callback = function(){};
	if(initFlag === undefined)
		initFlag = true;

	if(initFlag === true)
		speakInit();
	message = message.replace(/ /g, '').replace(/・/g, '、');
	var texts = message.split(/[。！？.!?]/);
	var len = texts.length;

	for(var i = 0; i < len; i++) {
		msg[i] = new SpeechSynthesisUtterance(texts[i]);
		msg[i].lang = "ja-JP";
		speechSynthesis.speak(msg[i]);
	}
	msg[i-1].onend = callback;
};
