<?php

session_start();
if(!empty($_SESSION['family']))
	header('Location: ./mirror/');

// アプリケーション設定
define('CONSUMER_KEY', '482245107839-ltqcd5n13loc9oerhh00gpaehsn6okt3.apps.googleusercontent.com');
define('CALLBACK_URL', 'http://localhost/famirror/sign_up/');

// URL
define('AUTH_URL', 'https://accounts.google.com/o/oauth2/auth');


//--------------------------------------
// 認証ページにリダイレクト
//--------------------------------------
$params = array(
	'client_id' => CONSUMER_KEY,
	'redirect_uri' => CALLBACK_URL,
	'scope' => 'openid profile email',
	'response_type' => 'code',
);

// リダイレクト
header("Location: " . AUTH_URL . '?' . http_build_query($params));
?>