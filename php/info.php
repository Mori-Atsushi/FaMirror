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
?>