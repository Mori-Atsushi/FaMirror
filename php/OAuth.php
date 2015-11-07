<?php
// アプリケーション設定
define('CONSUMER_KEY', '482245107839-ltqcd5n13loc9oerhh00gpaehsn6okt3.apps.googleusercontent.com');
define('CALLBACK_URL', 'http://localhost/famirror/php/sign_up.php');

// URL
define('AUTH_URL', 'https://accounts.google.com/o/oauth2/auth');

// 認証ページにリダイレクト
$params = array(
	'client_id' => CONSUMER_KEY,
	'redirect_uri' => CALLBACK_URL,
	'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/calendar.readonly https://www.googleapis.com/auth/gmail.readonly',
	'response_type' => 'code',
	'access_type' => 'offline',
	'approval_prompt' => 'force',
);

// リダイレクト
if($_GET['id'] == 'add')
	header("Location: https://accounts.google.com/AddSession?continue=" . AUTH_URL . urlencode('?' . http_build_query($params)));
else
	header("Location: " . AUTH_URL . '?' . http_build_query($params));

?>