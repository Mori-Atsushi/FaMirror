
var alarm = 7, content = 0, time = 1;

var check_alarm = function() {
	var flag = change_flag(user_data[user_id]['setting'][alarm]['notification']);
	if(flag) {
		var onof = user_data[user_id]['setting'][alarm]['config']['onof'];
		var D = new Date();
		if(change_flag(onof[D.getDay()]['notification']))
			start_alarm(function(){});
	} else {
		speak('音声案内を終了します。');
	}
}

var start_alarm = function(callback) {
	var alarm_data = user_data[user_id]['setting'][alarm]['config'];
	var h = alarm_data['choose'][time]['val'][0] + alarm_data['choose'][time]['val'][1];
	var m = alarm_data['choose'][time]['val'][3] + alarm_data['choose'][time]['val'][4];
	var t = Number(h) * 60 + Number(m);

	var D = new Date();
	var now = D.getHours() * 60 + D.getMinutes();
	var target = t - now;
	var target_m = target % 60;
	var target_h = (target - target_m) / 60;
	var message = alarm_data['choose'][content]['val'];
	if(target_m >= 0)
		message += 'まで、';
	else
		message += 'から、';

	if(target_h != 0)
		message += Math.abs(target_h) + '時間';
	message += Math.abs(target_m) + '分';

	if(target_m >= 0)
		message += 'です。';
	else
		message += '経ちました。';

	message += '音声案内を終了します。ß';

	speak(message, callback);
}

var change_flag = function(flag) {
	if(flag == 0)
		flag = false;
	if(flag == 1)
		flag = true;
	return flag;
}