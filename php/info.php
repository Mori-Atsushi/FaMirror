<?php
require 'collect.php';

session_start();
$time = date(H); $manth = date(n); $day = date(j); $week = date(w);
//$week = 1;
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');
//$_POST['user_id'] = 1;
if($conn && $_POST['user_id'] !== '') {
	mysql_select_db('famirror', $conn); mysql_query('SET NAMES utf8', $conn );
	$sql = 'SELECT * FROM user WHERE user_id = ' . $_POST['user_id'] . ' AND family_id = ' . $_SESSION['family'];
	$user = mysql_fetch_assoc(mysql_query($sql));
}

$message = greeting($time);
$message = $message . user_name($user);
$message = $message . set_date($manth, $day, $week);
$message = $message . birthday($user['birthday']);

$array = array('message' => $message);
$setting = array();

if($user['weather_notification'])
	$setting[0] = weather($user);
if($user['trash_notification'])
	$setting[1] = trash($user, $conn, $day, $week);
if($user['calendar_notification'])
	$setting[2] = calendar($user);
if($user['timetable_notification'])
	$setting[3] = timetable($user, $conn, $week);
if($user['bus_notification'])
	$setting[4] = bus($user, $conn);
if($user['horoscope_notification'])
	$setting[5] = horoscope($user);
if($user['lunch_notification'])
	$setting[6] = lunch($user, $conn);

$array += array('setting' => $setting);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($array);

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

function birthday($birthday) {
	$y = date(Y); $m = date(m); $d = date(d);
	$b_m = $birthday[5] . $birthday[6];
	$b_d = $birthday[8] . $birthday[9];
	if($b_m == $m && $b_d == $d) {
		$b_y = $birthday[0] . $birthday[1] . $birthday[2] . $birthday[3];
		$old = $y - $b_y;
		return $old . '歳のお誕生日、おめでとうございます！';
	}
}
?>