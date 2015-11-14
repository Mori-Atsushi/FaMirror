<?php
require 'collect.php';

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
if($user['calendar_notification'])
	$message = $message . calendar($user);
if($user['horoscope_notification'])
	$message = $message . horoscope($user);
if($user['timetable_notification'])
	$message = $message . timetable($user, $conn, $week);
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
?>