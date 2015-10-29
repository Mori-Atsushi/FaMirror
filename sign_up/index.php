<?php
// アプリケーション設定
define('CONSUMER_KEY', '482245107839-ltqcd5n13loc9oerhh00gpaehsn6okt3.apps.googleusercontent.com');
define('CONSUMER_SECRET', '56bapESDOFaQgDdeBg-M4-_W');
define('CALLBACK_URL', 'http://localhost/famirror/sign_up/');

// URL
define('TOKEN_URL', 'https://accounts.google.com/o/oauth2/token');
define('INFO_URL', 'https://www.googleapis.com/oauth2/v1/userinfo');


//--------------------------------------
// アクセストークンの取得
//--------------------------------------
$params = array(
	'code' => $_GET['code'],
	'grant_type' => 'authorization_code',
	'redirect_uri' => CALLBACK_URL,
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
$access_token = $token['access_token'];

$params = array('access_token' => $access_token);
$res = file_get_contents(INFO_URL . '?' . http_build_query($params));
$array = json_decode($res, true);

//セッションスタート
session_start();
if(empty($array['name'])) {
	if($_SESSION['name'] == '')
		header('Location: ../');
	} else {
		$_SESSION['name'] = $array['name'];
		$_SESSION['mail'] = $array['email'];
}

$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn) {
	mysql_select_db('famirror', $conn);
	$sql = 'SELECT family_id FROM user WHERE user_mail = "'. $_SESSION['mail'] .'"';
	if(mysql_num_rows(mysql_query($sql, $conn)) !== 0)
		header('Location: ../mirror/');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width,maximum-scale=1.0">
	<link rel="stylesheet" href="../css/style.css">
	<title>FaMirror | 新規登録</title>
</head>
<body>
	<header class="site_header">
		<h1>顔登録</h1>
	</header>

	<video id="mirror" class="mirror" autoplay></video>
	<canvas id="canvas" class="temp_pic"></canvas>
	
	<section class="exp">
		<p id="message">顔を登録します。<br>四角形の中に顔を入れてカメラボタンをタップしてください。</p>
		<div id="shot" class="shot"></div>
	</section>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="../js/facepp.js"></script>
	<script src="../js/mirror.js"></script>
	<script id="script" src="../js/sign_up.js"></script>
</body>









