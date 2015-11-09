<?php
session_start();
$time = date(H); $manth = date(n); $day = date(j); $week = date(w);
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn && $_POST['user_id'] !== '') {
	mysql_select_db('famirror', $conn);
	$take_info = 'user_name_p, weather_notification, weather_region, weather_detail, weather_temperature, weather_tomorrow';
	$sql = 'SELECT ' . $take_info . ' FROM user WHERE user_id = ' . $_POST['user_id'] . ' AND family_id = ' . $_SESSION['family'];
	$user = mysql_fetch_assoc(mysql_query($sql));
}

$message = greeting($time);
$message = $message . user_name($user);
$message = $message . set_date($manth, $day, $week);
if($user['weather_notification'])
	$message = $message . weather($user);

echo $message;


function greeting($time) {
	if($time < 4 || $time >= 18)
		return 'こんばんは、';
	else if($time < 11)
		return 'おはようございます、';
	else
		return 'こんにちは、';
}

function user_name($user) {
	return $user['user_name_p'] . 'さん。';
}

function set_date($manth, $day, $week) {
	$week_ja = array('日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日');
	return '今日は' . $manth . '月' . $day . '日、' . $week_ja[$week] . 'です。';
}

function weather($user) {
	$url = 'http://weather.livedoor.com/forecast/webservice/json/v1?city=' . $user['weather_region'];
	$res = json_decode(file_get_contents($url), true );
	$return = '今日の' . $res['location']['city'] . 'の天気は、' . $res['forecasts'][0]['telop'] . '、です。';

	if($user['weather_temperature']) {
		$min = $res['forecasts'][0]['temperature']['min'];
		$max = $res['forecasts'][0]['temperature']['max'];

		if($min)
			$return = $return . '最低気温は、' . $min['celsius'] . '度、';
		if($max)
			$return = $return . '最高気温は、' . $max['celsius'] . '度';
		if($min || $max)
			$return = $return . 'の、予報です。';
	}

	if($user['weather_detail'])
		$return = $return . $res['description']['text'];

	if($user['weather_tomorrow'])
		$return = $return . '明日の天気は、' . $res['forecasts'][0]['telop'] . '、になっています。';
	return $return;
}
?>