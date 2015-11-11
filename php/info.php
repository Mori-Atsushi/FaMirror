<?php
require 'collect.php';

// アプリケーション設定
define('CONSUMER_KEY', '482245107839-ltqcd5n13loc9oerhh00gpaehsn6okt3.apps.googleusercontent.com');
define('CONSUMER_SECRET', '56bapESDOFaQgDdeBg-M4-_W');

// URL
define('TOKEN_URL', 'https://accounts.google.com/o/oauth2/token');

session_start();
$time = date(H); $manth = date(n); $day = date(j); $week = date(w);
$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn && $_POST['user_id'] !== '') {
	mysql_select_db('famirror', $conn);
	$sql = 'SELECT * FROM user WHERE user_id = ' . $_POST['user_id'] . ' AND family_id = ' . $_SESSION['family'];
	$user = mysql_fetch_assoc(mysql_query($sql));
}


//--------------------------------------
// アクセストークンの取得
//--------------------------------------
$params = array(
	'grant_type' => 'refresh_token',
	'refresh_token' => $user['refresh_token'],
	'client_id' => CONSUMER_KEY,
	'client_secret' => CONSUMER_SECRET,
);

// POST送信
$options = array('http' => array(
	'method' => 'POST',
	'content' => http_build_query($params)
));
$res = file_get_contents(TOKEN_URL, false, stream_context_create($options));

// レスポンス取得
$token = json_decode($res, true);
if(isset($token['error'])){
	echo 'エラー発生';
	exit;
}

$message = greeting($time);
$message = $message . user_name($user);
$message = $message . set_date($manth, $day, $week);
if($user['weather_notification'])
	$message = $message . weather($user);
if($user['trash_notification'])
	$message = $message . trash($user, $conn, $day, $week);
if($user['calendar_notification'])
	$message = $message . calender($user, $token['access_token']);
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