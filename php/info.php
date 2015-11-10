<?php
session_start();
$time = date(H); $manth = date(n); $day = date(j); $week = date(w);
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');
$_POST['user_id'] = 1;

if($conn && $_POST['user_id'] !== '') {
	mysql_select_db('famirror', $conn);
	$sql = 'SELECT * FROM user WHERE user_id = ' . $_POST['user_id'] . ' AND family_id = ' . $_SESSION['family'];
	$user = mysql_fetch_assoc(mysql_query($sql));
}

$message = greeting($time);
$message = $message . user_name($user);
$message = $message . set_date($manth, $day, $week);
if($user['weather_notification'])
	$message = $message . weather($user);
if($user['trash_notification'])
	$message = $message . trash($user, $conn, $day, $week);
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
	$url = 'http://weather.livedoor.com/forecast/webservice/json/v1?city=' . $user['weather_area'];
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

function trash($user, $conn, $day, $week) {
	$return = '今日回収されるゴミは、';

	if(($m = check_trash($conn, $day, $week, $user['trash_id'])) == '')
		$return = $return . 'ありません。';
	else
		$return = $return . $m . 'です。';

	if($user['trash_tomorrow']) {
		$return = $return . '明日回収されるゴミは、';
	if(($m = check_trash($conn, $day + 1, $week + 1, $user['trash_id'])) == '')
		$return = $return . 'ありません。';		
	else
		$return = $return . $m . 'です。';
	}

	return $return;	
}

function check_trash($conn, $day, $week, $trash_id) {
	$week_ja = array('日', '月', '火', '水', '木', '金', '土');
	$week_num = ceil($day / 7);
	$week_to = $week_ja[$week];

	if($conn && $_POST['user_id'] !== '') {
		mysql_select_db('famirror', $conn);
		$sql = 'SELECT type, wday FROM trash_types WHERE trash_id = ' . $trash_id;
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			if(strpos($row['wday'], $week_to) !== false) {
				for($i = 1; $i <= 5; $i++) {
					if($i != $week_num && strpos($row['wday'], $week_to . $i) !== false) {
						break;
					}
				}
				if($i > 5)
					$return = $return . $row['type'] . '、';
			}
		}
	}

	return $return;	
}
?>