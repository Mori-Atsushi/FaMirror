<?php

session_start();
$sign_up_flag = !empty($_SESSION['email']);
$mirror_flag = !empty($_SESSION['family']);

$top = '<section class="top">
		<header class="siteheader"></header>
		<div class="main">
			<h2>一瞬の時間もムダにしない、<br>新しい朝を。</h2>
		</div>
		<footer class="sitefooter">
			<button onclick="location.href=\'php/OAuth.php\'"></button>
		</footer>
	</section>';

$sign_up = '<section id="sign_up" class="sign_up">
		<header class="page_header">
			<h1>顔登録</h1>
		</header>
		
		<div class="main">
			<div class="square"></div>
		</div>
		<div class="exp">
			<p id="message">四角形の中に顔を入れてカメラボタンを押してください。</p>
			<div class="status"><div id="progress_gage" class="gage"></div><span id="progress"></span></div>
			<div id="shot" class="shot"></div>
		</div>
	</section>

	<div id="black_screen" class="black_screen">
		<div id="get_name_popup" class="get_name_popup">
			<h2>表示名と読み方を入力してください</h2>
			<label for="name">表示名</label>
			<input type="text" name="name" class="name" value="' . $_SESSION['name'] . '">
			<label for="name_p">読み方(ひらがな)</label>
			<input type="text" name="name_p" class="name_p" value="">
			<button id="get_name_submit">完了</button>
		</div>
	</div>';

$base = '<section id="base" class="base">
		<h1 id="auth">認証</h1>
		<p id="message"></p>
		<div id="setting_b">設定</div>
	</section>';


$setting = '<section id="setting" class="setting">
		<header class="page_header">
			<div id="setting_back">戻る</div>
			<h1>設定</h1>
		</header>
		<div class="main">
			<ul id="user_list"></ul>
			<button onclick="location.href=\'php/OAuth.php?id=add\'" class="add">+</button>
		</div>
	</section>';

$detail = '<section id="detail" class="detail">
		<header class="siteheader">
			<div class="operate">
				<button id="detail_back" class="back"></button>
				<button class="gear"></button>
			</div>
			<div class="icon"></div>
			<h1 id="detail_user_name"></h1>
		</header>
		<div class="main">
			<ul>
				<li id="item_weather">天気</li>
				<li id="item_trash">ごみ</li>
				<li id="item_calender">カレンダー</li>
			</ul>
		</div>
	</section>';

$video = '<video id="mirror" class="mirror" autoplay></video>
	<canvas id="canvas" class="temp_pic"></canvas>';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width,maximum-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/test.css">
	<title>FaMirror</title>
</head>

<body>
	<?php
	if($sign_up_flag || $mirror_flag) {
		if($sign_up_flag) {
			echo '<style type="text/css"><!-- #base { display : none; } --></style>';
			echo $sign_up;
		}
		echo $video;
		echo $base;
		echo $setting;
		echo $detail;
	} else {
		echo $top;
	}

	if($sign_up_flag || $mirror_flag) {
		echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
		echo '<script type="text/javascript" src="./js/mirror.js"></script>';
		echo '<script type="text/javascript" src="./js/db.js"></script>';
		echo '<script type="text/javascript" src="./js/facepp.js"></script>';
		echo '<script type="text/javascript" src="./js/speak.js"></script>';
		echo '<script type="text/javascript" src="./js/script.js"></script>';
		if($sign_up_flag) {
			echo '<script type="text/javascript" src="./js/sign_up.js"></script>';
		}
	}
	?>
</body>
</html>
