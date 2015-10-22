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


//--------------------------------------
// ユーザー情報を取得してみる
//--------------------------------------
$params = array('access_token' => $access_token);
$res = file_get_contents(INFO_URL . '?' . http_build_query($params));
$array = json_decode($res, true);

//セッションスタート
session_start();
$_SESSION['name'] = $array[name];
$_SESSION['mail'] = $array[email];
if($_SESSION['name'] == '')
	header('Location: ../');

$conn = mysql_connect('localhost', 'famirror', 'famirrorproject');

if($conn) {
	mysql_select_db('famirror', $conn);
	$sql = "SELECT family_id FROM `family` WHERE `user_id1` = '". $array[email] ."' OR `user_id2` = '". $array[email] ."' OR `user_id3` = '". $array[email] ."' OR `user_id4` = '". $array[email] ."' OR `user_id5` = '". $array[email] ."' OR `user_id6` = '". $array[email] ."' OR `user_id7` = '". $array[email] ."' OR `user_id8` = '". $array[email] ."' OR `user_id9` = '". $array[email] ."' OR `user_id10` = '". $array[email] ."'";
	if(mysql_num_rows(mysql_query($sql, $conn)) !== 0)
		header('Location: ../mirror/');	
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<link rel="stylesheet" href="../css/style.css">
	<title>FaMirror | 新規登録</title>
</head>
	<h1>ようこそ<?php echo $array[name]; ?>さん</h1>
	<video class="mirror" id="mirror" autoplay></video>
	<script type="text/javascript" src="../js/mirror.js"></script>
<body>













